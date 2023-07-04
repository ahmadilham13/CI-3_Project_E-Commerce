<?php

use PhpParser\Node\Stmt\Break_;

defined('BASEPATH') OR exit('No direct script access allowed');

class Checkout extends MY_Controller {

    private $id;
    
    public function __construct()
    {
        parent::__construct();
        $isLogin    = $this->session->userdata('is_login');
        $this->id   = $this->session->userdata('id');
        
        if(!$isLogin) {
            redirect(base_url());
            return;
        }

        $params = array('server_key' => 'SB-Mid-server-dnDSJ_7_gR2EyJC-PeakXorP', 'production' => false);
		$this->load->library('midtrans');
		$this->midtrans->config($params);
		$this->load->helper('url');	
    }

    public function index($input = null) {
        if(isset($_GET['status_code'])) {
            redirect(base_url("checkout/create/$_GET[order_id]"));
        } else {
            if($this->session->userdata('user_checkout_data')) {
                $this->session->unset_userdata('user_checkout_data');
            }
        }
        $this->checkout->table = 'cart';
        $data['cart']        = $this->checkout->select(
            [
                'cart.id', 'cart.qty', 'cart.subtotal',
                'product.title', 'product.image', 'product.price'
            ]
        )
        ->join('product')
        ->where('cart.id_user', $this->id)
        ->get();

        if(!$data['cart']) {
            $this->session->set_flashdata('warning', 'Tidak ada product di dalam keranjang');
            redirect(base_url());
        }

        $data['input']      =  $input ? $input :  (object)  $this->checkout->getDefaultValues();
        $data['title']      = 'Checkout';
        $data['page']       = 'pages/checkout/index';
        $data['pay_process'] = true;
        $data['sandbox_url']    = 'https://app.sandbox.midtrans.com/snap/snap.js';
        $data['client_key']     = 'SB-Mid-client-qtnk4WOC80ON1Cqh';

        $this->view($data);
    }

    public function create($order_id) {

        // check order in midtrans
        $order_mercant = $this->midtrans->status($order_id);
        if($order_mercant) {
            // echo '<pre>'; print_r($order); echo '</pre>';
            $userCheckoutData = $this->session->userdata('user_checkout_data');
            $transaction_status = $order_mercant->transaction_status;
            $status = '';
            switch($transaction_status) {
                case 'settlement':
                    $status = 'paid';
                    break;
                case 'deny':
                    $status = 'cancel';
                    break;
                case 'pending':
                    $status = 'waiting';
                    break;
            }
            $data = [
                'id_user'       => $this->id,
                'date'          => $order_mercant->transaction_time,
                'invoice'       => $order_id,
                'total'         => $order_mercant->gross_amount,
                'name'          => trim($userCheckoutData['first_name'].' '.$userCheckoutData['last_name']),
                'address'       => $userCheckoutData['address'],
                'phone'         => $userCheckoutData['phone'],
                'status'        => $status,
            ];
            
             if($order = $this->checkout->create($data)) {
                $cart = $this->db->where('id_user', $this->id)
                        ->get('cart')->result_array();
                foreach($cart as $row) {
                    $row['id_orders']   = $order;
                    unset($row['id'], $row['id_user']);
                    $this->db->insert('orders_detail', $row);
                }

                $this->db->where('id_user', $this->id);
                $this->db->delete('cart');

                $this->session->set_flashdata('success', 'Checkout Transaction Successfully');
                $data['title']      = 'Checkout Success';
                $data['content']    = (object) $data;
                $data['page']       = 'pages/checkout/success';
                $data['mercant_order']  = $order_mercant;

                $this->view($data);   
            } else {
                $this->session->set_flashdata('error', 'Checkout gagal');
                return $this->index($input);
            }
        } else {
            redirect(base_url());
        }
    }

    public function pay() {
        $form_data = array();
        parse_str($_POST['form_user_data'], $form_data);
        
        $first_name = $form_data['first_name'];
        $last_name = $form_data['last_name'];
        $address = $form_data['address'];
        $phone = $form_data['phone'];

        $user_data = [
            'first_name' => $first_name,
            'last_name'  => $last_name,
            'address'    => $address,
            'phone'      => $phone
        ];

        // delete if exists user_checkout_data
        if($this->session->userdata('user_checkout_data')) {
            $this->session->unset_userdata('user_checkout_data');
        }
        
        $this->session->set_userdata('user_checkout_data', $user_data);

        
        $this->checkout->table = 'cart';
        $data['cart']        = $this->checkout->select(
            [
                'cart.id', 'cart.qty', 'cart.subtotal',
                'product.title', 'product.price'
            ]
        )
        ->join('product')
        ->where('cart.id_user', $this->id)
        ->get();

        if(!$data['cart']) {
            echo '{"status": false, "message": "product in cart undefined"}';
            return;
        }
        
        // start snap

        $order_id = $this->id.date('YmdHis');
        $total_amount = array_sum(array_column($data['cart'], 'subtotal'));

        $transaction_details = array(
            'order_id'  => $order_id,
            'gross_amount'  => $total_amount,
        );

        $detail_item = [];
        foreach($data['cart'] as $row) {
            $item_detail = array(
                'id'    => $row->id,
                'price' => $row->price,
                'quantity'  => $row->qty,
                'name'      => $row->title
            ); 
            array_push($detail_item, $item_detail);
        }

        // for now using only billing address

        $billing_address = array(
            'first_name'    => $first_name,
            'last_name'     => $last_name,
            'address'       => $address,
            'city'          => 'Jambi',
            'postal_code'   => '37481',
            'phone'         => $phone,
            'country_code'  => 'IDN'
        );

        $customer_details = array(
            'first_name'    => $first_name,
            'last_name'     => $last_name,
            'email'         => $this->session->userdata('email'),
            'phone'         => $phone,
            'billing_address'  => $billing_address,
            // 'shipping_address' => $shipping_address
          );

        // Data yang akan dikirim untuk request redirect_url.
        $credit_card['secure'] = true;
        //ser save_card true to enable oneclick or 2click
        //$credit_card['save_card'] = true;

        $time = time();
        $custom_expiry = array(
            'start_time' => date("Y-m-d H:i:s O",$time),
            'unit' => 'minute', 
            'duration'  => 60
        );

        $transaction_data = array(
            'transaction_details'=> $transaction_details,
            'item_details'       => $detail_item,
            'customer_details'   => $customer_details,
            'credit_card'        => $credit_card,
            'expiry'             => $custom_expiry,
            'callback_url'  => base_url(),
        );

        error_log(json_encode($transaction_data));
		$snapToken = $this->midtrans->getSnapToken($transaction_data);
		error_log($snapToken);
		echo $snapToken;

    }

}

/* End of file Checkout.php */

?>
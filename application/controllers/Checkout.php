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
        $this->changeTable('cart');
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

        $this->view($data);
    }

    public function create() {
        if(!$_POST) {
            redirect(base_url('checkout'));
        } else {
            $input = (object) $this->input->post(null, true);
        }

        $this->changeTable('orders');
        if(!$this->checkout->validate()) {
            return $this->index($input);
        }

        $total      = $this->db->select_sum('subtotal')
                        ->where('id_user', $this->id)
                        ->get('cart')
                        ->row()
                        ->subtotal;

        if(empty($total)) {
            return redirect(base_url());
        }
        $data       = [
            'id_user'       => $this->id,
            'date'          => date('YmdHis'),
            'invoice'       => $this->id.date('YmdHis'),
            'total'         => $total,
            'first_name'    => $input->first_name,
            'last_name'    => $input->last_name,
            'name'          => trim($input->first_name.' '.$input->last_name),
            'email'         => strtolower($input->email),
            'address'       => $input->address,
            'city'          => $input->city,
            'postal_code'   => $input->postal_code,
            'phone'         => $input->phone,
            'status'        => 'pending',
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

            $this->session->set_flashdata('success', 'Data Checkout Successfully');
            $data['title']      = 'Checkout Success';
            $data['content']    = (object) $data;
            $data['page']       = 'pages/checkout/success';
            $data['pay_process'] = true;
            $data['sandbox_url']    = 'https://app.sandbox.midtrans.com/snap/snap.js';
            $data['client_key']     = 'SB-Mid-client-qtnk4WOC80ON1Cqh';


            $this->view($data);   
        } else {
            $this->session->set_flashdata('error', 'Checkout gagal');
            return $this->index($input);
        }
    }

    public function changeTable($table) {
        $this->checkout->table = $table;
    }

}

/* End of file Checkout.php */

?>
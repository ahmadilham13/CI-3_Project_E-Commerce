<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Pay extends MY_Controller {

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

        $params = array('server_key' => env('SERVER_KEY'), 'production' => env('PRODUCTION'));
		$this->load->library('midtrans');
		$this->midtrans->config($params);
		$this->load->helper('url');	
    }

    public function index() {
        $invoice = $_POST['order_id'];
        
        $data['order']  = $this->pay->where('invoice', $invoice)->first();

        if(!$data['order'] || $data['order']->id_user != $this->id) {
            $this->sesison->set_flashdata('warning', 'Orders not found');
            redirect(base_url('myorder'));
        }

        // get order detail
        $this->pay->table = 'orders_detail';
        $data['order_detail']   = $this->pay->select([
            'orders_detail.id_product', 'orders_detail.qty',
            'orders_detail.subtotal', 'product.title', 'product.price'
        ])
        ->join('product')
        ->where('orders_detail.id_orders', $data['order']->id)
        ->get();
        
        $firstName = $data['order']->first_name;
        $lastName = $data['order']->last_name;
        $address = $data['order']->address;
        $phone = $data['order']->phone;

        $total = $this->db->select_sum('subtotal')
                    ->where('id_orders', $data['order']->id)
                    ->get('orders_detail')
                    ->row()
                    ->subtotal;

        $transactionDetail = array(
            'order_id'  => $invoice,
            'gross_amount'  => $total
        );

        $detailItem = [];
        foreach($data['order_detail'] as $row) {
            $itemDetail = array(
                'id'        => $row->id_product,
                'price'     => $row->price,
                'quantity'  => $row->qty,
                'name'      => $row->title
            );
            array_push($detailItem, $itemDetail);
        }

        // for now only using billing address

        $billingAddress = array(
            'first_name'        => $firstName,
            'last_name'         => $lastName,
            'address'           => $address,
            'city'              => 'Jambi',
            'postal_code'       => '37481',
            'phone'             => $phone,
            'country_code'      => 'IDN'
        );
        

        $customerDetail = array(
            'first_name'    => $firstName,
            'last_name'     => $lastName,
            'email'         => 'ahmadilham130599@gmail.com',
            'phone'         => $phone,
            'billing_address'  => $billingAddress,
          );

        // Data yang akan dikirim untuk request redirect_url.
        $creditCard['secure'] = true;
        //ser save_card true to enable oneclick or 2click
        //$credit_card['save_card'] = true;

        $time = time();
        $customExpired = array(
            'start_time'    => date("Y-m-d H:i:s O",$time),
            'unit'          => 'minute',
            'duration'      => 60
        );

        $transactionData = array(
            'transaction_details'=> $transactionDetail,
            'item_details'       => $detailItem,
            'customer_details'   => $customerDetail,
            'credit_card'        => $creditCard,
            'expiry'             => $customExpired,
        );

        error_log(json_encode($transactionData));
        $snapToken = $this->midtrans->getSnapToken($transactionData);
        error_log($snapToken);
        echo $snapToken;
    }

    public function proofPayment() {
        $statusCode = $_GET['status_code'];
        $orderId = $_GET['order_id'];

        switch($statusCode) {
            case '200':
                $this->successPayment($orderId);
                break;
            case '201':
                if($this->updateStatus($orderId)) {
                    $this->session->set_flashdata('warning', 'Waiting for your paiment');
                    redirect(base_url("myorder/detail/$orderId"));
                } else {
                    $this->session->set_flashdata('warning', 'Something Wrong for update your status Order');
                    redirect(base_url("myorder/detail/$orderId"));
                }
                break;
            case '406':
                $statusMessage = $_GET['message'] ? $_GET['message'] : "Payment Cancelled";
                $this->session->set_flashdata('warning', $statusMessage);
                redirect(base_url("myorder/detail/$orderId"));
                return;
                break;
        }
    }

    public function updateStatus($orderId) {
        // check order in midtrans
        $data['order']  = $this->pay->where('invoice', $orderId)->first();

        if(!$data['order'] || $data['order']->id_user != $this->id) {
            redirect(base_url('myorder'));
        }

        $orderMercant = $this->midtrans->status($orderId);
        if($orderMercant) {
            $transactionStatus = $orderMercant->transaction_status;
            $status = '';
            switch($transactionStatus) {
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
                'update_date'   => $orderMercant->transaction_time,
                'status'        => $status
            ];
            
            if($this->pay->where('invoice', $orderId)->update($data)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }


    }

    public function image_required() {
        if(empty($_FILES) || $_FILES['image']['name'] === '') {
            $this->session->set_flashdata('image_error', 'Bukti Transfer harus diisi');
            return false;
        }
        return true;
    }


    public function successPayment($orderId) {
        $data['order']  = $this->pay->where('invoice', $orderId)->first();

        if(!$data['order'] || $data['order']->id_user != $this->id) {
            $this->session->set_flashdata('warning', 'Orders not found!!');
            redirect(base_url('myorder'));
        }

        if($data['order']->status !== 'pending') {
            $this->session->set_flashdata('warning', 'Bukti Transfer sudah Dikirimkan');
            redirect(base_url("myorder/detail/$orderId"));
        }

        if(!$_POST) {
            $data['input']  = (object) $this->pay->getDefaultValues();
        } else {
            $data['input']  = (object) $this->input->post(null, true);
        }

        if(!empty($_FILES) && $_FILES['iamge']['name'] !== '') {
            $imageName = url_title($orderId, '-', true). '-'. date('YmdHis');
            $upload = $this->pay->uploadImage('image', $imageName);
            if($upload) {
                $data['input']->image       = $upload['file_name'];
            } else {
                $this->session->set_flashdata('error', 'Ops! Terjadi kesalahan');
                // redirect(base_url("pay/proofPayment/$orderId?order_id=$orderId"));
                redirect(base_url("pay/proofPayment/?order_id=$orderId"));
            }
        }

        if(!$this->pay->validate()) {
            $data['title']          = 'Prof Detail Payment';
            $data['form_action']    = base_url("pay/proofPayment/?order_id=$orderId");
            $data['page']           = 'pages/pay/index';

            $this->view($data);
            return;
        }

        $this->pay->table = 'orders_confirm';

        if($this->pay->create($data['input'])) {
            $this->pay->table = 'orders';
            if($this->updateStatus($orderId)) {
                $this->session->set_flashdata('success', 'Data Order Berhasil Di Update, Status: Paid');
            } else {
                $this->sesison->set_flashdata('error', 'Ops! Terjadi Kesalahan');
            }
        } else {
            $this->session->set_flashdata('error', 'Ops! Terjadi kesalahan');
        }
        redirect(base_url("myorder/detail/$orderId"));
    }

}

/* End of file Pay.php */

?>
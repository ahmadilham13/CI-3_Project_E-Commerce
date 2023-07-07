<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Myorder extends MY_Controller {

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

    public function index() {
        $data['title']      = 'Daftar Order';
        $data['content']    = $this->myorder->where('id_user', $this->id)
                                ->orderBy('date', 'DESC')->get();
        $data['page']       = 'pages/myorder/index';

        $this->view($data);
    }

    public function detail($invoice) {
        $data['order']      = $this->myorder->where('invoice', $invoice)->first();

        if(!$data['order']) {
            $this->session->set_flashdata('warning', 'Orders not found!!');
            redirect(base_url('myorder'));
        }

        $this->myorder->table = 'orders_detail';
        $data['order_detail']       = $this->myorder->select([
            'orders_detail.id_orders', 'orders_detail.id_product', 'orders_detail.qty',
            'orders_detail.subtotal', 'product.title', 'product.image', 'product.price'
        ])
        ->join('product')
        ->where('orders_detail.id_orders', $data['order']->id)
        ->get();

        if($data['order']->status !== 'waiting') {
            $this->myorder->table = 'orders_confirm';
            $data['order_confirm']  = $this->myorder->where('id_orders', $data['order']->id)->first();
        }
        $data['pay_process'] = true;
        $data['sandbox_url']    = 'https://app.sandbox.midtrans.com/snap/snap.js';
        $data['client_key']     = 'SB-Mid-client-qtnk4WOC80ON1Cqh';
        $data['page']           = 'pages/myorder/detail';

        $this->view($data);
    }

    public function confirm($invoice) {
        $data['order']      = $this->myorder->where('invoice', $invoice)->first();

        if(!$data['order']) {
            $this->session->set_flashdata('warning', 'Orders not found!!');
            redirect(base_url('myorder'));
        }

        if($data['order']->status !== 'waiting') {
            $this->session->set_flashdata('warning', 'Bukti transfer sudah dikirim');
            redirect(base_url("myorder/detail/$invoice"));
        }

        if(!$_POST) {
            $data['input']      = (object) $this->myorder->getDefaultValues();
        } else {
            $data['input']      = (object) $this->input->post(null, true);
        }

        if(!empty($_FILES) && $_FILES['image']['name'] !== '') {
            $imageName  = url_title($invoice, '-', true).'-'. date('YmdHis');
            $upload     = $this->myorder->uploadImage('image', $imageName);
            if($upload) {
                $data['input']->image       = $upload['file_name'];
            } else {
                redirect(base_url("myorder/confirm/$invoice"));
            }
        }

        if(!$this->myorder->validate()) {
            $data['title']          = 'Confirm Order';
            $data['form_action']    = base_url("myorder/confirm/$invoice");
            $data['product_script'] = '/assets/js/uploadImage.js';
            $data['page']           = 'pages/myorder/confirm';

            $this->view($data);
            return;
        }

        $this->myorder->table = 'orders_confirm';


        if($this->myorder->create($data['input'])) {
            $this->myorder->table = 'orders';
            $this->myorder->where('id', $data['input']->id_orders)->update(['status' => 'paid']);
            $this->session->set_flashdata('success', 'Data Order berhasil diupdate');
        } else {
            $this->session->set_flashdata('error', 'Ops! Terjadi kesalahan');
        }

        redirect(base_url("myorder/detail/$invoice"));
    }

    public function image_required() {
        if(empty($_FILES) || $_FILES['image']['name'] === '') {
            $this->session->set_flashdata('image_error', 'Bukti Transfer harus diisi');
            return false;
        }
        return true;
    }

}

/* End of file Myorder.php */

?>
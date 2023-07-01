<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends MY_Controller {

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
    }

    public function index() {
        $data['title']          = 'Keranjang Belanja';
        $data['content']        = $this->cart->select(
            [
                'cart.id', 'cart.qty', 'cart.subtotal',
                'product.title', 'product.image', 'product.price'
            ]
        )
        ->join('product')
        ->where('cart.id_user', $this->id)
        ->get();
        $data['page']           = 'pages/cart/index';

        return $this->view($data);
    }

    public function add() {
        if(!$_POST || $this->input->post('qty') < 1) { 
            $this->session->set_flashdata('error', 'qty tidak boleh kosong');
            redirect(base_url());
        } else {
            $input              = (object) $this->input->post(null, true);

            $this->cart->table  = 'product';
            $product            = $this->cart->where('id', $input->id_product)->first();

            $this->cart->table  = 'cart';
            $cart               = $this->cart->where('id_user', $this->id)->where('id_product', $input->id_product)->first();
            $subtotal           = $product->price * $input->qty;
            if($cart) {
                $data   = [
                    'qty'       => $cart->qty + $input->qty,
                    'subtotal'  => $cart->subtotal + $subtotal,
                ];

                if($this->cart->where('id', $cart->id)->update($data)) {
                    $this->session->set_flashdata('success', 'Product Berhasil ditambah ke keranjang');
                } else {
                    $this->session->set_flashdata('error', 'Product gagal ditambah ke keranjang');
                }

                redirect(base_url(''));
            }

            $data   = [
                'id_user'       => $this->id,
                'id_product'    => $input->id_product,
                'qty'           => $input->qty,
                'subtotal'      => $subtotal,
            ];

            if($this->cart->create($data)) {
                $this->session->set_flashdata('success', 'Product Berhasil ditambah ke keranjang');
            } else {
                $this->session->set_flashdata('error', 'Product gagal ditambah ke keranjang');
            }

            redirect(base_url(''));
        }
    }

    public function update($id) {
        if(!$_POST || $this->input->post('qty') < 1) { 
            $this->session->set_flashdata('error', 'qty tidak boleh kosong');
            redirect(base_url('cart/index'));
        }

        $data['content']    = $this->cart->where('id', $id)->first();

        if(!$data['content']) {
            $this->session->set_flashdata('warning', 'Data Not Found!!');
            redirect(base_url('cart/index'));
        }

        $data['input']      = (object)  $this->input->post(null, true);
        $this->cart->table  = 'product';
        $product            = $this->cart->where('id', $data['content']->id_product)->first();

        $subtotal           = $data['input']->qty * $product->price;
        $cart               = [
            'qty'       => $data['input']->qty,
            'subtotal'  => $subtotal,
        ];

        $this->cart->table  = 'cart';

        if($this->cart->where('id', $id)->update($cart)) {
            $this->session->set_flashdata('success', 'Product Berhasil Diupdate');
        } else {
            $this->session->set_flashdata('error', 'Product gagal Diupdate');
        }

        redirect(base_url('cart/index'));
    }

    public function delete($id) {
        if(!$_POST) {
            redirect(base_url('cart/index'));
            return;
        }

        if(!$this->cart->where('id', $id)->first()) {
            $this->session->set_flashdata('warning', 'Cart not Found!!!');
            redirect(base_url('cart/index'));
            return;
        }

        if($this->cart->where('id', $id)->delete()) {
            $this->session->set_flashdata('success', 'Cart Deleted Successfully');
        } else {
            $this->session->set_flashdata('error', 'Cart Deleted Failed');
        }
        redirect(base_url('cart/index'));
    }
    

}

/* End of file Cart.php */

?>
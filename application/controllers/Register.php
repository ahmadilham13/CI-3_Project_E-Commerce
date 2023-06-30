<?php 


defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends MY_Controller {

    
    public function __construct()
    {
        parent::__construct();
        $is_login = $this->session->userdata('is_login');

        if($is_login) {
            redirect(base_url());
            return;
        }
    }

    public function index() {
        if(!$_POST) {
            $input = (object) $this->register->getDefaultValues();
        } else {
            $input = (object) $this->input->post(null, true);
        }

        if(!$this->register->validate()) {
            $data['title']      = 'Register';
            $data['input']      = $input;
            $data['page']       = 'pages/auth/register';
            
            $this->view($data);
            return;
        }

        if($this->register->run($input)) {
            $user_id = $this->session->userdata('id');

            if(!emailVerify($user_id)) {
                $this->session->set_flashdata('success', 'Berhasil Melakukan Registrasi, Cek Email untuk verifikasi email');
                redirect(base_url('verification'));
            } else {
                $this->session->set_flashdata('success', 'Berhasil Melakukan Registrasi');
                redirect(base_url());
            }
        } else {
            $this->session->set_flashdata('error', 'Oops! terjadi suatu kesalahan registrasi');
            redirect(base_url('/register'));
        }
    }
    
}

/* End of file Register.php */

?>
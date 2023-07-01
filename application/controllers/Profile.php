<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MY_Controller {

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
    
    public function index()
    {
        $data['title']      = 'Profile';
        $data['content']    = $this->profile->where('id', $this->id)->first();
        $data['page']       = 'pages/profile/index';

        $this->view($data);
    }

    public function update($id) {
        $data['content']    = $this->profile->where('id', $id)->first();

        if(!$data['content']) {
            $this->session->set_flashdata('warning', 'User not found');
            redirect(base_url('profile'));
        }

        if(!$_POST) {
            $data['input']      = $data['content'];
        } else {
            $data['input']      = (object) $this->input->post(null, true);
            if($data['input']->password !== '') {
                $data['input']->password = hashEncrypt($data['input']->password);
            } else {
                $data['input']->password = $data['content']->password;
            }
        }

        if(!empty($_FILES) && $_FILES['image_profile']['name'] !== '') {
            $imageName  = url_title($data['input']->name, '-', true).'-'. date('YmdHis');
            $upload     = $this->profile->uploadImage('image_profile', $imageName);
            if($upload) {
                if($data['content']->image_profile !== '') {
                    $this->profile->deleteImage($data['content']->image_profile);
                }
                $data['input']->image_profile       = $upload['file_name'];
            } else {
                redirect(base_url("profile/update/$id"));
            }
        }

        if(!$this->profile->validate()) {
            $data['title']          = 'Ubah Data Profile';
            $data['form_action']    = base_url("profile/update/$id");
            $data['user_script'] = '/assets/js/uploadImage.js';
            $data['page']           = 'pages/profile/form';

            $this->view($data);
            return;
        }

        if($this->profile->runUpdate($id, $data['input'], $data['content'])) {
            $this->session->set_flashdata('success', 'Profile Berhasil disimpan');
        } else {
            $this->session->set_flashdata('error', 'Ops! Terjadi kesalahan');
        }

        redirect(base_url('profile'));
    }

    public function unique_email() {
        $email  = $this->input->post('email');
        $id     = $this->input->post('id');
        $user   = $this->profile->where('email', $email)->first();

        if($user) {
            if($user->id != $id) {
                $this->load->library('form_validation');
                $this->form_validation->set_message('unique_email', '%s sudah digunakan!');
                return false;
            }
        }

        return true;
    }

}

/* End of file Profile.php */

?>
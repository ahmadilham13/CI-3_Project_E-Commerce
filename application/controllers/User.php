<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {

    
    public function __construct()
    {
        parent::__construct();
        $role = $this->session->userdata('role');
        if($role != 'admin') {
            redirect(base_url('/'));
            return;
        }
    }
        

    public function index($page = null)
    {
        $data['title']      = 'Admin: Pengguna';
        $data['content']    = $this->user->select(
            [
                'user.id', 'user.name', 'user.email', 'user.role', 'user.is_active', 'user.image_profile',
                'email_verification.is_verification AS email_verify'
            ]
        )
        ->joinEmailVerify('email_verification')
        ->paginate($page)
        ->get();
        $data['total_rows'] = $this->user->count();
        $data['pagination'] = $this->user->makePagination(
            base_url('user'), 2, $data['total_rows']
        );
        $data['page']       = 'pages/user/index';
        $data['numbers']    = ($this->user->dataPerPage() * $page) - $page;

        $this->view($data);
    }

    public function search($page = null) {
        if(isset($_POST['keyword'])) {
            $this->session->set_userdata('keyword', $this->input->post('keyword'));
        } else {
            redirect(base_url('user'));
        }

        $keyword    = $this->session->userdata('keyword');

        $data['title']      = 'Admin: Pengguna';
        $data['content']    = $this->user->select(
            [
                'user.id', 'user.name', 'user.email', 'user.role', 'user.is_active', 'user.image_profile',
                'email_verification.is_verification AS email_verify'
            ]
        )
        ->joinEmailVerify('email_verification')
        ->like('user.name', $keyword)
        ->orLike('user.email', $keyword)
        ->paginate($page)
        ->get();
        $data['total_row']      = $this->user->like('user.name', $keyword)->orLike('user.email', $keyword)->count();
        $data['pagination']     = $this->user->makePagination(
            base_url('user/search'), 3, $data['total_row']
        );
        $data['page']           = 'pages/user/index';
        $data['numbers']        = ($this->user->dataPerPage() * $page) - $page;
        
        $this->view($data);
    }

    public function reset() {
        $this->session->unset_userdata('keyword');
        redirect(base_url('user'));
    }

    public function create() {
        if(!$_POST) {
            $input = (object) $this->user->getDefaultValues();
        } else {
            $input = (object) $this->input->post(null, true);
        }

        if(!empty($_FILES) && $_FILES['image_profile']['name'] !== '') {
            $imageName  = url_title($input->name, '-', true).'-'. date('YmdHis');
            $upload     = $this->user->uploadImage('image_profile', $imageName);
            if($upload) {
                $input->image_profile       = $upload['file_name'];
            } else {
                redirect(base_url('user/create'));
            }
        }

        if(!$this->user->validate()) {
            $data['title']          = 'Add User';
            $data['input']          = $input;
            $data['form_action']    = base_url('user/create');
            $data['user_script'] = '/assets/js/uploadImage.js';
            $data['page']           = 'pages/user/form';

            $this->view($data);
            return;
        }

        $newUserId = $this->user->run($input);
        if($newUserId) {
            $this->user->createEmailVerify($newUserId);
            $this->session->set_flashdata('success', 'Data User Berhasil disimpan');
        } else {
            $this->session->set_flashdata('error', 'Ops! Terjadi kesalahan');
        }

        redirect(base_url('user'));
    }

    public function edit($id) {
        $data['content']    = $this->user->where('id', $id)->first();

        if(!$data['content']) {
            $this->session->set_flashdata('warning', 'User not found');
            redirect(base_url('user'));
        }

        if(!$_POST) {
            $data['input']      = $data['content'];
        } else {
            $data['input']      = (object) $this->input->post(null, true);
        }

        if(!empty($_FILES) && $_FILES['image_profile']['name'] !== '') {
            $imageName  = url_title($data['input']->name, '-', true).'-'. date('YmdHis');
            $upload     = $this->user->uploadImage('image_profile', $imageName);
            if($upload) {
                if($data['content']->image_profile !== '') {
                    $this->user->deleteImage($data['content']->image_profile);
                }
                $data['input']->image_profile       = $upload['file_name'];
            } else {
                redirect(base_url('user/create'));
            }
        }

        if(!$this->user->validate()) {
            $data['title']          = 'Edit User';
            $data['form_action']    = base_url("user/edit/$id");
            $data['user_script'] = '/assets/js/uploadImage.js';
            $data['page']           = 'pages/user/form';

            $this->view($data);
            return;
        }

        if($this->user->runUpdate($id, $data['input'])) {
            $this->session->set_flashdata('success', 'Data Berhasil disimpan');
        } else {
            $this->session->set_flashdata('error', 'Ops! Terjadi kesalahan');
        }

        redirect(base_url('user'));
    }

    public function delete($id) {
        if(!$_POST) {
            redirect(base_url('user'));
            return;
        }

        $user = $this->user->where('id', $id)->first();

        if(!$user) {
            $this->session->set_flashdata('warning', 'User not Found!!!');
            redirect(base_url('user'));
            return;
        }

        if($this->user->where('id', $id)->delete()) {
            $this->user->deleteImage($user->image_profile);
            $this->user->deleteEmailVerifiy($user->id);
            $this->session->set_flashdata('success', 'Users Deleted Successfully');
        } else {
            $this->session->set_flashdata('error', 'Users Deleted Failed');
        }
        redirect(base_url('user'));
    }

    public function unique_email() {
        $email  = $this->input->post('email');
        $id     = $this->input->post('id');
        $user   = $this->user->where('email', $email)->first();

        if($user) {
            if($user->id != $id) {
                $this->load->library('form_validation');
                $this->form_validation->set_message('unique_email', '%s sudah digunakan!');
                return false;
            }
        }

        return true;
    }

    public function password_required() {
        
        if(!$this->input->post('id')) {
            if(empty($this->input->post('password'))) {
                $this->load->library('form_validation');
                $this->form_validation->set_message('password_required', '%s Required!');
                return false;
            }
        }

        return true;
    }

}

/* End of file User.php */

?>
<?php 


defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends MY_Controller {

    
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
        $data['title']          = 'Admin: Category';
        $data['content']        = $this->category->paginate($page)->get();
        $data['total_row']      = $this->category->count();
        $data['pagination']     = $this->category->makePagination(
            base_url('category'), 2, $data['total_row']
        );
        $data['page']           = 'pages/category/index';
        $data['numbers']        = ($this->category->dataPerPage() * $page) - $page;
        
        $this->view($data);
    }

    public function search($page = null) {
        if(isset($_POST['keyword'])) {
            $this->session->set_userdata('keyword', $this->input->post('keyword'));
        } else {
            redirect(base_url('category'));
        }

        $keyword    = strtolower(str_replace(" ", "-",  $this->session->userdata('keyword')));

        $data['title']          = 'Admin: Category';
        $data['content']        = $this->category->orLike('slug', $keyword)->paginate($page)->get();
        $data['total_row']      = $this->category->orLike('slug', $keyword)->count();
        $data['pagination']     = $this->category->makePagination(
            base_url('category/search'), 3, $data['total_row']
        );
        $data['page']           = 'pages/category/index';
        $data['numbers']        = ($this->category->dataPerPage() * $page) - $page;
        
        $this->view($data);
    }

    public function reset() {
        $this->session->unset_userdata('keyword');
        redirect(base_url('category'));
    }

    public function create() {
        if(!$_POST) {
            $input = (object) $this->category->getDefaultValues();
        } else {
            $input = (object) $this->input->post(null, true);
        }

        if(!$this->category->validate()) {
            $data['title']          = 'Tambah Category';
            $data['input']          = $input;
            $data['form_action']    = base_url('category/create');
            $data['page']           = 'pages/category/form';

            $this->view($data);
            return;
        }

        if($this->category->create($input)) {
            $this->session->set_flashdata('success', 'Data Berhasil disimpan');
        } else {
            $this->session->set_flashdata('error', 'Ops! Terjadi kesalahan');
        }

        redirect(base_url('category'));
    }

    public function edit($id) {
        $data['content']        = $this->category->where('id', $id)->first();

        if(!$data['content']) {
            $this->session->set_flashdata('warning', 'Category not Found!!!');
            redirect(base_url('category'));
            return;
        }

        if(!$_POST) {
            $data['input'] = $data['content'];
        } else {
            $data['input']  = (object)  $this->input->post(null, true);
        }

        if(!$this->category->validate()) {
            $data['title']          = 'Edit Category';
            $data['form_action']    = base_url("category/edit/$id");
            $data['page']           = 'pages/category/form';

            $this->view($data);
            return;
        }

        if($this->category->where('id', $id)->update($data['input'])) {
            $this->session->set_flashdata('success', 'Data Updated Successfully');
        } else {
            $this->session->set_flashdata('error', 'Data Updated Failed');
        }
        redirect(base_url('category'));
    }

    public function delete($id) {
        if(!$_POST) {
            redirect(base_url('category'));
            return;
        }

        if(!$this->category->where('id', $id)->first()) {
            $this->session->set_flashdata('warning', 'Category not Found!!!');
            redirect(base_url('category'));
            return;
        }

        if($this->category->where('id', $id)->delete()) {
            $this->session->set_flashdata('success', 'Category Deleted Successfully');
        } else {
            $this->session->set_flashdata('error', 'Category Deleted Failed');
        }
        redirect(base_url('category'));
    }

    public function unique_slug() {
        $slug   = $this->input->post('slug');
        $id     = $this->input->post('id');
        $category = $this->category->where('slug', $slug)->first();

        if($category) {
            if($category->id != $id) {
                $this->load->library('form_validation');
                $this->form_validation->set_message('unique_slug', '%s sudah digunakan!');
                return false;
            }
        }

        return true;
    }

}

/* End of file Category.php */

?>
<?php 


defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends MY_Controller {

    public function index()
    {
        $sess_data = ['id', 'name', 'email', 'role', 'is_login'];
        $this->session->unset_userdata($sess_data);
        $this->session->sess_destroy();
        // $this->session->set_flashdata('success', 'Logout Successfully');
        redirect(base_url());
    }

}

/* End of file Logout.php */

?>
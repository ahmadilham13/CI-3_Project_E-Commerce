<?php 


defined('BASEPATH') OR exit('No direct script access allowed');

class Verification extends MY_Controller {


    
    public function __construct()
    {
        parent::__construct();
        if(!$this->session->userdata('is_login')) {
            redirect(base_url());
        }
    }
    

    public function index()
    {

        if(emailVerify($this->session->userdata('id'))) {
            redirect(base_url());
            return;
        }
        if(!$_POST) {
            $data['title'] = 'Email Verification';
            if($_GET && $_GET['verification_code']) {
                $data['page'] = 'pages/email-verification/verification';
                $data['otp_style'] = '/assets/css/otp.css';
                $data['otp_script'] = '/assets/js/otp.js';
            }else {
                $this->verification->checkingEmailVerification();
                $data['page'] = 'pages/email-verification/notification';
            }
            $this->view($data);
            return;
        }

        $otpCode = '';
        $linkCode = $_POST['link_code'];
        foreach($_POST as $key => $value) {
            if($key == "link_code") {
                continue;
            }
            $otpCode .= $value;
        }
        if($this->verification->run($otpCode, $linkCode)) {
            $this->session->set_flashdata('success', 'Berhasil Melakukan Login and Your Email has been Verify, Thank you :)');
            redirect(base_url());
        } else {
            $this->session->set_flashdata('error', 'Invalid OTP');
            redirect(base_url('verification?verification_code=' . $linkCode));
        }

    }
}

/* End of file Verification.php */

?>
<?php 


defined('BASEPATH') OR exit('No direct script access allowed');

class Otp extends MY_Controller {

    
    public function __construct()
    {
        parent::__construct();
        
    }
    

    public function index() {
        if(!$_POST) {
            $input = (object) $this->otp->getDefaultValues();
        } else {
            $input = (object) $this->input->post(null, true);
        }

        if(!$this->otp->validate()) {
            $data['title']  = 'OTP Verification';
            $data['otp_style'] = '/assets/css/otp.css';
            $data['otp_script'] = '/assets/js/otp.js';
            $data['page']   = 'pages/auth/otp';
            $data['input']  = $input;
            $this->otp->generateOtp();
            $this->view($data);
            return;
        }

        $otpCode = '';
        foreach($input as $key => $value) {
            $otpCode .= $value;
        }
        if($this->otp->run($otpCode)) {
                if($this->otp->setUserLoginned()) {
                    $user_id = $this->session->userdata('id');
                    if(!emailVerify($user_id)) {
                        $this->session->set_flashdata('warning', 'Your E-Mail not verify!!');
                        redirect(base_url('verification'));
                    } else {
                        $this->session->set_flashdata('success', 'Berhasil Melakukan Login');
                        redirect(base_url());
                    }
                } else {
                    $this->session->set_flashdata('error', 'Oops! You Cannot Login right now, try again later');
                    redirect(base_url('/login'));
                }
        } else {
            $this->session->set_flashdata('error', 'Oops! your OTP not correctly');
            redirect(base_url('/otp'));
        }
    }

}

/* End of file Verification_otp.php */

?>
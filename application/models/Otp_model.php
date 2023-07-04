<?php 


defined('BASEPATH') OR exit('No direct script access allowed');

class Otp_model extends MY_Model {

    protected $table = 'login_otp';
    
    public function generateOtp() {
        $user_id = $this->session->userdata('user_id_otp');
        // check if exist user in login_otp table
        $query = $this->where('user_id', $user_id)
                        ->first();
        if($query) {
            $this->updateOtp();
        } else {
            $this->createOtp();
        }
    }

    public function run($otpCode) {
        if($otpCode) {
            $user_id = $this->session->userdata('user_id_otp');
            if($user_id) {
                $query = $this->where('user_id', $user_id)
                        ->first();
                if(!empty($query) && $query->otp_code == $otpCode) {
                    $data = [
                        'otp_used'  => true
                    ];
                    $this->where('user_id', $user_id);
                    $this->update($data);
                    
                    if($query->expired > date("Y-m-d H:i:s")) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public function setUserLoginned() {
        $user_id = $this->session->userdata('user_id_otp');
        $user_name = $this->session->userdata('user_name_otp');
        $user_email = $this->session->userdata('user_email_otp');
        $user_role = $this->session->userdata('user_role_otp');

        if($user_id && $user_name && $user_email && $user_role) {
            $sess_data = [
                'id'        => $user_id,
                'name'      => $user_name,
                'email'     => $user_email,
                'role'      => $user_role,
                'is_login'  => true,
            ];
            $this->session->set_userdata($sess_data);
            // delete user otp
            $sess_data = ['user_id_otp', 'user_name_otp', 'user_email_otp', 'user_role_otp'];
            $this->session->unset_userdata($sess_data);
            // $this->session->sess_destroy();
            return true;
        }
        // keep delete the user otp
        $sess_data = ['user_id_otp', 'user_name_otp', 'user_email_otp', 'user_role_otp'];
        $this->session->unset_userdata($sess_data);
        $this->session->sess_destroy();
        return false;
    }

    public function getDefaultValues() {
        return [
            'otp_1' => '',
            'otp_2' => '',
            'otp_3' => '',
            'otp_4' => '',
        ];
    }

    public function getValidationRules() {
        $validationRules = [
            [
                'field' => 'otp_1',
                'label' => 'OTP',
                'rules' => 'required'
            ],
            [
                'field' => 'otp_2',
                'label' => 'OTP',
                'rules' => 'required'
            ],
            [
                'field' => 'otp_3',
                'label' => 'OTP',
                'rules' => 'required'
            ],
            [
                'field' => 'otp_4',
                'label' => 'OTP',
                'rules' => 'required'
            ]
        ];
        return $validationRules;
    }

    public function updateOtp() {  
        $user_id = $this->session->userdata('user_id_otp');
        $user_email = $this->session->userdata('user_email_otp');
        $user_role  = $this->session->userdata('user_role_otp');
        if($user_id && $user_email && $user_role) {
            $data = [
                'user_hash' => userHash($user_email, $user_role),
                'otp_code'  => randomNumber(4),
                'otp_used'  => false,
                'expired'   => otpExpired(),
            ];
            $this->where('user_id', $user_id);
            return $this->update($data);
        } else {
            $this->session->set_flashdata('error', 'Oops! Please insert your Email again');
            redirect(base_url('/login'));              
        }
    }

    public function createOtp() {
        $user_id = $this->session->userdata('user_id_otp');
        $user_email = $this->session->userdata('user_email_otp');
        $user_role  = $this->session->userdata('user_role_otp');
        if($user_id && $user_email && $user_role) {
            $data = [
                'user_id'   => $user_id,
                'user_hash' => userHash($user_email, $user_role),
                'otp_code'  => randomNumber(4),
                'otp_used'  => false,
                'expired'   => otpExpired(),
            ];
            return $this->create($data);
        } else {
            $this->session->set_flashdata('error', 'Oops! Please insert your Email again');
            redirect(base_url('/login'));  
        }
    }

}

/* End of file Otp_model.php */

?>
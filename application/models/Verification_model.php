<?php 


defined('BASEPATH') OR exit('No direct script access allowed');

class Verification_model extends MY_Model {

    protected $table = 'email_verification';

    public function checkingEmailVerification() {
        if(!empty($this->session->userdata('is_login'))) {
            $user_id = $this->session->userdata('id');
            // check the table with current user
            $query = $this->where('user_id', $user_id)
                        ->first();
            if($query) {
                $this->updateVerificationOtp($user_id);
            } else {
                $this->createVerificationOtp($user_id);
            }
        }
    }

    public function updateVerificationOtp($user_id) {
        $data = [
            'link_code'         => generateLink(),
            'otp'               => randomNumber(4),
            'otp_expired'       => otpExpired(),
            'is_verification'   => false,
        ];
        $this->where('user_id', $user_id);
        return $this->update($data);
    }

    public function createVerificationOtp($user_id) {
        $data = [
            'user_id'           => $user_id,
            'link_code'         => generateLink(),
            'otp'               => randomNumber(4),
            'otp_expired'       => otpExpired(),
            'is_verification'   => false
        ];
        return $this->create($data);
    }

    public function run($otpCode, $linkCode) {
        $user_id = $this->session->userdata('id');
        // check the user in table
        $query = $this->where('user_id', $user_id)
                ->where('otp', $otpCode)
                ->where('link_code', $linkCode)
                ->first();
        if(!$query) {
            return false;
        }

        $data = [
            'link_code'         => '',
            'otp'               => '',
            'otp_expired'       => '',
            'is_verification'   => true,
        ];
        $this->where('user_id', $user_id);
        $this->update($data);
        return true;
    }
    

}

/* End of file Verification_model.php */

?>
<?php 

    function getDropdownList($table, $columns) {
        $CI         =& get_instance();
        $query      = $CI->db->select($columns)->from($table)->get();
        if($query->num_rows() >= 1) {
            $option1 = ['' => '- Select -'];
            $option2 = array_column($query->result_array(), $columns[1], $columns[0]);
            $options = $option1 + $option2;

            return $options;
        }

        return $options = ['' => '- Select -'];
    }

    function getCategories() {
        $CI         =& get_instance();
        $query      = $CI->db->get('category')->result();
        return $query;
    }

    function getCart() {
        $CI         =& get_instance();
        $userId     = $CI->session->userdata('id');

        if($userId) {
            $query      = $CI->db->where('id_user', $userId)->count_all_results('cart');
            return $query;
        }

        return false;
    }

    function hashEncrypt($input) {
        $hash   = password_hash($input, PASSWORD_DEFAULT);
        return $hash;
    }

    function hashEncryptVerify($input, $hash) {
        if(password_verify($input, $hash)) {
            return true;
        } else {
            return false;
        }
    }

    function randomNumber($digits){
        $min = pow(10, $digits - 1);
        $max = pow(10, $digits) - 1;
        return mt_rand($min, $max);
    }

    function userHash($userEmail, $userRole) {
        $userHash = hash("md5", $userEmail . $userRole);
        return $userHash;
    }

    function otpExpired() {
        $newTime = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." +2 minutes"));
        return $newTime;
    }

    function generateLink() {
        $uniqId = uniqid();

        $randStart = rand(1,5);

        $rand8Char = substr($uniqId,$randStart,8);
        return $rand8Char;
    }

    function emailVerify($user_id) {
        $table_name = 'email_verification';
        $CI         =& get_instance();
        $query      = $CI->db->query("SELECT * FROM $table_name WHERE user_id=".$user_id."");
        if($query) {
            $is_verify = $query->result()[0]->is_verification;
            if(!$is_verify) {
                return false;
            }
        } else {
            return false;
        }
        return true;
    }

    function userRole() {
        return [
            'admin'     => 'Admin',
            'member'    => 'Member',
        ];
    }


?>
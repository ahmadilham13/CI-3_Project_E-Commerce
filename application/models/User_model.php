<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends MY_Model {

    public function getDefaultValues() {
        return [
            'name'      => '',
            'email'     => '',
            'password'  => '',
            'role'      => '',
            'is_active' => false,
            'image_profile' => '',
        ];
    }

    public function getValidationRules() {
        $validationRules = [
            [
                'field'         => 'name',
                'label'         => 'Nama User',
                'rules'         => 'trim|required'
            ],
            [
                'field'         => 'email',
                'label'         => 'E-Mail',
                'rules'         => 'trim|required|valid_email|callback_unique_email',
            ],
            [
                'field'         => 'password',
                'label'         => 'Password',
                'rules'         => 'min_length[8]|callback_password_required'
            ],
        ];

        return $validationRules;
    }

    public function run($input) {
        $data = [
            'name'          => $input->name,
            'email'         => strtolower($input->email),
            'password'      => hashEncrypt($input->password),
            'role'          => $input->role,
            'image_profile' => $input->image_profile
        ];

        // create user
        $userId = $this->create($data);
        return $userId;
    }

    public function runUpdate($id, $input) {
        $data = [
            'name'  => $input->name,
            'email' => strtolower($input->email),
            'role'  => $input->role,
            'image_profile' => $input->image_profile
        ];
        if(!empty($input->password)) {
            $data['password']   = hashEncrypt($input->password);
        }

        // update user
        $userId = $this->user->where('id', $id)->update($data);
        return true;
    }

    public function uploadImage($fieldName, $fileName) {
        $config = [
            'upload_path'       => './images/profiles',
            'file_name'         => $fileName,
            'allowed_types'     => 'jpg|gif|png|jpeg|JPG|PNG',
            'max_size'          => 1024,
            'max_width'         => 0,
            'max_height'        => 0,
            'overwrite'         => true,
            'file_ext_tolower'  => true,
        ];

        $this->load->library('upload', $config);

        if($this->upload->do_upload($fieldName)) {
            return $this->upload->data();
        } else {
            $this->session->set_flashdata('image_error', $this->upload->display_errors('', ''));
            return false;
        }
    }

    public function dataPerPage() {
        return $this->perPage;
    }

    public function createEmailVerify($userId) {
        $data = [
            'user_id'   => $userId,
            'is_verification'   => false,
        ];
        $this->db->insert('email_verification', $data);
        return $this->db->insert_id();
    }

    public function deleteImage($fieldName) {
        if(file_exists("./images/profiles/$fieldName")) {
            unlink("./images/profiles/$fieldName");
        }
    }

    public function deleteEmailVerifiy($user_id) {
        $this->db->where('user_id', $user_id)
            ->delete('email_verification');
    }

}

/* End of file User_model.php */

?>
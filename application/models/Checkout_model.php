<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Checkout_model extends MY_Model {

    public $table = 'orders';

    public function getDefaultValues() {
        $this->table = 'user';
        $userId = $this->session->userdata('id');
        $query = $this->where('id', $userId)
                        ->where('is_active', 1)
                        ->first();

            return [
                'first_name'            => $query->first_name ? $query->first_name : '',
                'last_name'             => $query->last_name ? $query->last_name : '',
                'address'               => '',
                'email'                 => $query->email ? $query->email : '',
                'phone'                 => $query->phone ? $query->phone : '',
                'status'                => '',
                'city'                  => '',
                'postal_code'           => '',
            ];

    }
    
    public function getValidationRules() {
        $validationRules = [
            [
                'field'     => 'first_name',
                'label'     => 'First Name',
                'rules'     => 'trim|required'
            ],
            [
                'field'     => 'last_name',
                'label'     => 'Last Name',
                'rules'     => 'trim|required'
            ],
            [
                'field'     => 'address',
                'label'     => 'Alamat',
                'rules'     => 'trim|required'
            ],
            [
                'field'     => 'phone',
                'label'     => 'Phone',
                'rules'     => 'trim|required|max_length[15]'
            ],
        ];

        return $validationRules;
    }

}

/* End of file Checkout_model.php */

?>
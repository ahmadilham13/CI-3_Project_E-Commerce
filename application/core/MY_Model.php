<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {

    protected $table    = '';

    
    public function __construct()
    {
        parent::__construct();
        if(!$this->table) {
            $this->table = strtolower(
                str_replace('_model', '', get_class($this))
            );
        }
    }
    
    /**
     * Fungsi validasi input
     * Rules: Dideklarasikan dalam masing-masing model
     */
    public function validate() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimeters(
            '<small class="form-text text-danger">', '</small>'
        );
        $validationRules = $this->getValidationRules();

        $this->form_validation->set_rules($validationRules);

        return $this->form_validation->run();
    }
    
    public function select($columns) {
        $this->db->select($columns);
        return $this;
    }

    public function where($columns, $condition) {
        $this->db->where($columns, $condition);
        return $this;
    }

    public function like($columns, $condition) {
        $this->db->like($columns, $condition);
        return $this;
    }

    public function orLike($columns, $condition) {
        $this->db->or_like($columns, $condition);
        return $this;
    }

    public function join($table, $type = 'left') {
        $this->db->join($table, "$this->table.id_$table = $table.id", $type);
        return $this;
    }

    public function orderBy($columns, $order = 'asc') {
        $this->db->order_by($columns, $order);
        return $this;
    }

    public function first() {
        return $this->db->get($this->table)->row();
    }

    public function get() {
        return $this->db->get($this->table)->result();
    }

    public function count() {
        return $this->db->count_all_results($this->table);
    }

    public function create($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($data) {
        return $this->db->update($this->table, $data);
    }

    public function delete() {
        $this->db->delete($this->table);
        return $this->db->affected_rows();
    }
}

/* End of file MY_Model.php */

?>
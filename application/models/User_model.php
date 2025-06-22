<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    private $table = 'users';
    private $table_desa = 'kode_desa';

    public function __construct() {
        parent::__construct();
    }

    public function get_all() {
        return $this->db->get($this->table)->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function create($data) {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id) {
        return $this->db->delete($this->table, ['id' => $id]);
    }

    public function get_role($user) {
        if ($user->is_admin) {
            return 'admin';
        } elseif (!empty($user->desa_id)) {
            return 'admin_desa';
        }
        return 'user';
    }

    public function get_all_desa() {
        return $this->db->get($this->table_desa)->result();
    }

    public function get_desa_by_id($desa_id) {
        return $this->db->get_where($this->table_desa, ['desa_id' => $desa_id])->row();
    }
}
?>
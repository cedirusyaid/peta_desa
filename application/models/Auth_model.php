<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

    private $table = 'users';

    public function __construct() {
        parent::__construct();
    }

    public function login($username, $password) {
        $user = $this->db->get_where($this->table, ['username' => $username])->row();
        
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        
        return false;
    }

    public function get_user_role($user) {
        if ($user->is_admin) {
            return 'admin';
        } elseif (!empty($user->desa_id)) {
            return 'admin_desa';
        }
        return 'user';
    }
}
?>
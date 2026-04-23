<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

    public function cek_login($username, $password) {
        $this->db->where('username', $username);
        $query = $this->db->get('users');
        $user  = $query->row_array();

        // Cek password bcrypt
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}
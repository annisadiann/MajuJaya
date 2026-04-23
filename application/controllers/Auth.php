<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Auth_model');
    }

    public function index() {
        redirect('auth/login');
    }

    public function login() {
    if ($this->session->userdata('logged_in')) {
            $role = $this->session->userdata('role');
            redirect($role === 'kasir' ? 'kasir' : 'barang');
        }
        $this->load->view('auth/login');
    }

    public function proses_login() {
    $username = $this->input->post('username');
    $password = $this->input->post('password');

    $user = $this->Auth_model->cek_login($username, $password);

    if ($user) {
            $this->session->set_userdata([
                'nama'      => $user['nama'],
                'role'      => $user['role'],
                'logged_in' => true
            ]);
            redirect($user['role'] === 'kasir' ? 'kasir' : 'barang');
        }   else {
            redirect('auth/login?error=invalid');
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('auth/login');
    }
}
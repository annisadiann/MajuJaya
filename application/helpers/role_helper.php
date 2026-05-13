<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function cek_admin() {
    $CI =& get_instance();
    
    $logged = $CI->session->userdata('logged_in');
    $role   = $CI->session->userdata('role');
    log_message('error', "CEK_ADMIN - logged_in: " . var_export($logged, true) . " | role: $role");
    
    if (!$logged) {
        redirect('auth/login');
    }
    if ($role !== 'superadmin' && $role !== 'admin') {
        redirect('kasir');
    }
}

function cek_login() {
    $CI =& get_instance();
    $logged = $CI->session->userdata('logged_in');
    if (!$logged) {
        redirect('auth/login');
    }
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function cek_admin() {
    $CI =& get_instance();
    if (!$CI->session->userdata('logged_in')) {
        redirect('auth/login');
    }
    if ($CI->session->userdata('role') !== 'superadmin' && $CI->session->userdata('role') !== 'admin') {
        redirect('kasir');
    }
}

function cek_login() {
    $CI =& get_instance();
    if (!$CI->session->userdata('logged_in')) {
        redirect('auth/login');
    }
}
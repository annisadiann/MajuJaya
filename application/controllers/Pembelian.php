<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembelian extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Pembelian_model');
        cek_admin(); // hanya admin
    }

    public function index() {
        $limit  = 10;
        $page   = max(1, (int)($this->input->get('page') ?? 1));
        $total  = $this->Pembelian_model->count_pembelian();
        $total_hal = ceil($total / $limit) ?: 1;
        $page   = min($page, $total_hal);
        $offset = ($page - 1) * $limit;

        $data['pembelian']  = $this->Pembelian_model->get_pembelian($limit, $offset);
        $data['total_data'] = $total;
        $data['total_hal']  = $total_hal;
        $data['page']       = $page;

        $this->load->view('pembelian_view', $data);
    }
}
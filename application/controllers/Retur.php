<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Retur extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Retur_model');
        cek_login();
    }

    public function index() {
        $limit  = 10;
        $page   = max(1, (int)($this->input->get('page') ?? 1));
        $total  = $this->Retur_model->count_retur();
        $total_hal = ceil($total / $limit) ?: 1;
        $page   = min($page, $total_hal);
        $offset = ($page - 1) * $limit;

        $data['retur']      = $this->Retur_model->get_retur($limit, $offset);
        $data['grand_total'] = $this->Retur_model->get_grand_total();
        $data['total_data']  = $total;
        $data['total_hal']   = $total_hal;
        $data['page']        = $page;

        $this->load->view('retur_view', $data);
    }
}
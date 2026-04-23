<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stok extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Stok_model');
        cek_admin(); 
    }

    public function index() {
        $limit  = 10;
        $page   = max(1, (int)($this->input->get('page') ?? 1));
        $total  = $this->Stok_model->count_barang();
        $total_hal = ceil($total / $limit) ?: 1;
        $page   = min($page, $total_hal);
        $offset = ($page - 1) * $limit;

        $data['barang']      = $this->Stok_model->get_tracking($limit, $offset);
        $data['total_barang'] = $total;
        $data['stok_habis']  = $this->Stok_model->count_stok_habis();
        $data['total_hal']   = $total_hal;
        $data['page']        = $page;

        $this->load->view('stok_view', $data);
    }

    public function history($id_barang) {
        $id_barang = (int)$id_barang;
        $filter    = $this->input->get('filter') ?? '';
        $limit     = 5;
        $page      = max(1, (int)($this->input->get('page') ?? 1));

        $barang = $this->Stok_model->get_barang_by_id($id_barang);
        if (!$barang) redirect('stok');

        $total     = $this->Stok_model->count_history($id_barang, $filter);
        $total_hal = ceil($total / $limit) ?: 1;
        $page      = min($page, $total_hal);
        $offset    = ($page - 1) * $limit;

        $data['barang']    = $barang;
        $data['history']   = $this->Stok_model->get_history($id_barang, $filter, $limit, $offset);
        $data['filter']    = $filter;
        $data['total_hal'] = $total_hal;
        $data['page']      = $page;

        $this->load->view('history_stok_view', $data);
    }
}
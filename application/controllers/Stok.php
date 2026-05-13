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
        $nama   = $this->input->get('nama')   ?? '';
        $dari   = $this->input->get('dari')   ?? '';
        $sampai = $this->input->get('sampai') ?? '';

        $total     = $this->Stok_model->count_barang($nama, $dari, $sampai);
        $total_hal = ceil($total / $limit) ?: 1;
        $page      = min($page, $total_hal);
        $offset    = ($page - 1) * $limit;

        $barang = $this->Stok_model->get_tracking($limit, $offset, $nama, $dari, $sampai);

        $detail_transaksi = [];
        if ($dari || $sampai) {
            foreach ($barang as $b) {
                $detail_transaksi[$b['id_barang']] = $this->Stok_model->get_detail_transaksi($b['id_barang'], $dari, $sampai);
            }
        }

        $data['barang']           = $barang;
        $data['detail_transaksi'] = $detail_transaksi;
        $data['total_barang']     = $total;
        $data['stok_habis']       = $this->Stok_model->count_stok_habis();
        $data['total_hal']        = $total_hal;
        $data['page']             = $page;
        $data['nama']             = $nama;
        $data['dari']             = $dari;
        $data['sampai']           = $sampai;

        $this->load->view('stok_view', $data);
    }

    public function history($id_barang) {
        $id_barang = (int)$id_barang;
        $filter    = $this->input->get('filter') ?? '';
        $dari      = $this->input->get('dari')   ?? '';
        $sampai    = $this->input->get('sampai') ?? '';
        $limit     = 5;
        $page      = max(1, (int)($this->input->get('page') ?? 1));

        $barang = $this->Stok_model->get_barang_by_id($id_barang);
        if (!$barang) redirect('stok');

        $total     = $this->Stok_model->count_history($id_barang, $filter, $dari, $sampai);
        $total_hal = ceil($total / $limit) ?: 1;
        $page      = min($page, $total_hal);
        $offset    = ($page - 1) * $limit;

        $data['barang']    = $barang;
        $data['history']   = $this->Stok_model->get_history($id_barang, $filter, $limit, $offset, $dari, $sampai);
        $data['filter']    = $filter;
        $data['dari']      = $dari;
        $data['sampai']    = $sampai;
        $data['total_hal'] = $total_hal;
        $data['page']      = $page;

        $this->load->view('history_stok_view', $data);
    }
}
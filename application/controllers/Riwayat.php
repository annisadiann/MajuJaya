<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayat extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Riwayat_model');
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    public function index() {
        $dari   = $this->input->get('dari')   ?? '';
        $sampai = $this->input->get('sampai') ?? '';
        $barang = $this->input->get('barang') ?? '';
        $page   = max(1, (int)($this->input->get('page') ?? 1));
        $limit  = 10;

        $total        = $this->Riwayat_model->count_transaksi($dari, $sampai, $barang);
        $total_hal    = ceil($total / $limit) ?: 1;
        $page         = min($page, $total_hal);
        $offset       = ($page - 1) * $limit;

        $data['transaksi']    = $this->Riwayat_model->get_transaksi($dari, $sampai, $barang, $limit, $offset);
        // echo "<pre>";
        // print_r($data['transaksi']);
        // die;
        $data['grand_total']  = $this->Riwayat_model->get_grand_total($dari, $sampai, $barang);
        $data['total_data']   = $total;
        $data['total_hal']    = $total_hal;
        $data['page']         = $page;
        $data['dari']         = $dari;
        $data['sampai']       = $sampai;
        $data['barang']       = $barang;
        $data['daftar_barang'] = $this->Riwayat_model->get_daftar_barang();
        $data['detail_all']   = $this->Riwayat_model->get_all_detail();

        $data['error']        = $this->session->flashdata('error');

        $this->load->view('riwayat_view', $data);
    }

    public function simpan_retur() {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            redirect('riwayat');
        }

        $no_transaksi = $this->input->post('no_transaksi');
        $nama_barang  = $this->input->post('nama_barang');
        $harga_satuan = (int)$this->input->post('harga_satuan');
        $jumlah_retur = (int)$this->input->post('jumlah_retur');
        $jumlah_beli  = (int)$this->input->post('jumlah_beli');
        $tanggal_beli = $this->input->post('tanggal_beli');

        $batas = date('Y-m-d', strtotime($tanggal_beli . ' +1 day'));
        if (date('Y-m-d') > $batas) {
            $this->session->set_flashdata('error', 'Batas waktu retur sudah lewat!');
            redirect('riwayat');
        }

        if ($jumlah_retur > $jumlah_beli) {
            $this->session->set_flashdata('error', 'Jumlah retur melebihi jumlah beli!');
            redirect('riwayat');
        }

        $sudah_retur = $this->Riwayat_model->get_total_retur($no_transaksi, $nama_barang);
        if (($sudah_retur + $jumlah_retur) > $jumlah_beli) {
            $this->session->set_flashdata('error', 'Total retur melebihi jumlah beli!');
            redirect('riwayat');
        }

        $result = $this->Riwayat_model->simpan_retur(
            $no_transaksi, $nama_barang, $harga_satuan, $jumlah_retur
        );

        $this->load->view('retur_sukses_view', [
            'no_retur'    => $result['no_retur'],
            'total_retur' => $result['total_retur'],
        ]);
    }
}
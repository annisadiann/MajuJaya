<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Barang_model');
        cek_admin();
    }

    public function index() {
        $limit        = 10;
        $page         = max(1, (int)($this->input->get('page') ?? 1));
        $total        = $this->Barang_model->count_barang();
        $totalHalaman = ceil($total / $limit) ?: 1;
        $offset       = ($page - 1) * $limit;

        $data['rows']         = $this->Barang_model->get_barang($limit, $offset);
        $data['page']         = $page;
        $data['totalHalaman'] = $totalHalaman;
        $data['nama']         = $this->session->userdata('nama');
        $data['role']         = $this->session->userdata('role');

        $this->load->view('barang/index', $data);
    }

    public function tambah() {
    $data['error'] = $this->session->flashdata('error');
    $this->load->view('tambah_barang_view', $data);
}

    public function simpan() {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            redirect('barang');
        }

        $nama_barang = $this->input->post('nama_barang');
        $harga_beli  = (int)$this->input->post('harga_beli');
        $harga_jual  = (int)$this->input->post('harga_jual');
        $jumlah_stok = (int)$this->input->post('jumlah');

        // Validasi
        if ($harga_jual <= $harga_beli) {
            $this->session->set_flashdata('error', 'Harga jual harus lebih besar dari harga beli!');
            redirect('barang/tambah');
        }

        if ($jumlah_stok < 0) {
            $this->session->set_flashdata('error', 'Jumlah stok tidak boleh negatif!');
            redirect('barang/tambah');
        }

        $this->Barang_model->simpan_barang($nama_barang, $harga_beli, $harga_jual, $jumlah_stok);

        $this->session->set_flashdata('success', 'Barang berhasil ditambahkan!');
        redirect('barang');
    }
}
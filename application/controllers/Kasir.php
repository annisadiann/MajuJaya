<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kasir extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Kasir_model');
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        $role = $this->session->userdata('role');
        if ($role === 'superadmin' || $role === 'admin') {
            redirect('barang');
        }
    }

    public function index() {
        $this->load->model('Pelanggan_model');

        $search = $this->input->get('search') ?? '';
        $data['search'] = $search;

        if ($search !== '') {
            $data['barang']        = $this->Kasir_model->search_barang($search);
            $data['total_halaman'] = 1;
            $data['page']          = 1;
        } else {
            $limit  = 10;
            $page   = max(1, (int)($this->input->get('page') ?? 1));
            $total  = $this->Kasir_model->count_barang();
            $offset = ($page - 1) * $limit;

            $data['barang']        = $this->Kasir_model->get_barang($limit, $offset);
            $data['total_halaman'] = ceil($total / $limit) ?: 1;
            $data['page']          = $page;
        }

        $data['error']            = $this->session->flashdata('error');
        $data['success']          = $this->session->flashdata('success');
        $data['daftar_pelanggan'] = $this->Pelanggan_model->get_all();

        $this->load->view('kasir_view', $data);
    }

    public function simpan() {
        $jumlah_input = $this->input->post('jumlah');
        $jumlah_beli  = [];
        foreach ($jumlah_input as $id => $jumlah) {
            if ((int)$jumlah > 0) {
                $jumlah_beli[(int)$id] = (int)$jumlah;
            }
        }
        if (empty($jumlah_beli)) {
            $this->session->set_flashdata('error', 'Pilih minimal 1 barang untuk dijual!');
            redirect('kasir');
        }
        foreach ($jumlah_beli as $id => $jumlah) {
            $barang = $this->Kasir_model->get_barang_by_id($id);
            if ($jumlah > $barang['stok']) {
                $this->session->set_flashdata('error', "Stok {$barang['nama_barang']} tidak cukup!");
                redirect('kasir');
            }
        }

        $this->load->model('Pelanggan_model');
        $id_pelanggan = $this->input->post('id_pelanggan');
        if ($id_pelanggan === 'baru') {
            $nama_baru   = trim($this->input->post('nama_pelanggan_baru'));
            $alamat_baru = trim($this->input->post('alamat_baru') ?? '');
            if ($nama_baru === '') {
                $this->session->set_flashdata('error', 'Nama pelanggan baru tidak boleh kosong!');
                redirect('kasir');
            }
            $id_pelanggan = $this->Pelanggan_model->simpan($nama_baru, $alamat_baru);
        } elseif ($id_pelanggan === '') {
            $id_pelanggan = null;
        }

        $result = $this->Kasir_model->simpan_transaksi($jumlah_beli, $id_pelanggan);
        $this->load->view('kasir_sukses_view', [
            'no_transaksi'  => $result['no_transaksi'],
            'detail_barang' => $result['detail_barang'],
            'total_harga'   => $result['total_harga'],
        ]);
    }

    public function ajax_search() {
        $keyword = $this->input->get('search') ?? '';
        if (trim($keyword) === '') {
            $barang = $this->Kasir_model->get_barang(100, 0);
        } else {
            $barang = $this->Kasir_model->search_barang($keyword);
        }
        echo json_encode($barang);
    }
}
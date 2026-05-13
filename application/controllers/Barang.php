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
        $data['activeTab']    = $this->input->get('tab') ?? 'pembelian';

        $this->load->view('barang/index', $data);
    }

    public function tambah() {
        $data['error'] = $this->session->flashdata('error');
        $this->load->view('tambah_barang_view', $data);
    }

    public function edit_nama() {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            redirect('barang');
        }
        $id        = (int)$this->input->post('id_barang');
        $nama_baru = trim($this->input->post('nama_barang'));
        if ($nama_baru === '') {
            $this->session->set_flashdata('error', 'Nama barang tidak boleh kosong!');
            redirect('barang?tab=pembelian');
        }
        $this->Barang_model->update_nama($id, $nama_baru);
        $this->session->set_flashdata('success', 'Nama barang berhasil diubah!');
        redirect('barang?tab=pembelian');
    }

    public function simpan() {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            redirect('barang');
        }
        $nama_barang = $this->input->post('nama_barang');
        $harga_beli  = (int)$this->input->post('harga_beli');
        $harga_jual  = (int)$this->input->post('harga_jual');
        $jumlah_stok = (int)$this->input->post('jumlah');
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

    public function proses_penjualan() {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            redirect('barang');
        }
        $jumlah_input = $this->input->post('jumlah');
        $jumlah_beli  = [];
        foreach ($jumlah_input as $id => $jumlah) {
            if ((int)$jumlah > 0) {
                $jumlah_beli[(int)$id] = (int)$jumlah;
            }
        }
        if (empty($jumlah_beli)) {
            $this->session->set_flashdata('error', 'Pilih minimal 1 barang untuk dijual!');
            redirect('barang?tab=penjualan');
        }
        foreach ($jumlah_beli as $id => $jumlah) {
            $barang = $this->Barang_model->get_barang_by_id($id);
            if ($jumlah > $barang['stok']) {
                $this->session->set_flashdata('error', "Stok {$barang['nama_barang']} tidak cukup!");
                redirect('barang?tab=penjualan');
            }
        }
        $this->load->model('Kasir_model');
        $result = $this->Kasir_model->simpan_transaksi($jumlah_beli);
        $this->session->set_userdata('transaksi_sukses', [
            'no_transaksi'  => $result['no_transaksi'],
            'detail_barang' => $result['detail_barang'],
            'total_harga'   => $result['total_harga'],
        ]);
        redirect('barang/sukses_penjualan');
    }

    public function simpan_pembelian() {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            redirect('barang');
        }
        $jumlah_input      = $this->input->post('jumlah')      ?? [];
        $harga_beli_input  = $this->input->post('harga_beli')  ?? [];
        $harga_jual_input  = $this->input->post('harga_jual')  ?? [];
        $nama_barang_input = $this->input->post('nama_barang') ?? [];

        $ada_perubahan = false;
        $tanggal       = date('Y-m-d H:i:s');

        foreach ($jumlah_input as $id => $jumlah) {
            $id     = (int)$id;
            $jumlah = (int)str_replace('.', '', $jumlah);

            $barang           = $this->Barang_model->get_barang_by_id($id);
            $stok_sebelum     = (int)$barang['stok'];
            $harga_beli_baru  = isset($harga_beli_input[$id]) && $harga_beli_input[$id] !== '' ? (int)str_replace('.', '', $harga_beli_input[$id]) : null;
            $harga_jual_baru  = isset($harga_jual_input[$id]) && $harga_jual_input[$id] !== '' ? (int)str_replace('.', '', $harga_jual_input[$id]) : null;
            $harga_beli_final = $harga_beli_baru ?? (int)$barang['harga_beli'];
            $harga_jual_final = $harga_jual_baru ?? (int)$barang['harga_jual'];
            $nama_baru        = isset($nama_barang_input[$id]) && trim($nama_barang_input[$id]) !== ''
                                ? trim($nama_barang_input[$id])
                                : $barang['nama_barang'];

            if ($harga_jual_baru !== null && $harga_jual_baru <= $harga_beli_final) {
                $this->session->set_flashdata('error', 'Harga jual harus lebih besar dari harga beli!');
                redirect('barang?tab=pembelian');
            }

            if ($jumlah > 0) {
                $ada_perubahan = true;
                $stok_sesudah  = $stok_sebelum + $jumlah;
                $this->Barang_model->update_stok_pembelian($id, $stok_sesudah, $harga_beli_final, $harga_jual_final, $nama_baru);
                $this->Barang_model->catat_tambah_stok($id, $jumlah, $harga_beli_final, $tanggal);
                $this->Barang_model->catat_history_stok($id, $jumlah, $stok_sebelum, $stok_sesudah,
                    "Restok dari supplier @ Rp " . number_format($harga_beli_final, 0, ',', '.'), $tanggal);
            } elseif ($harga_beli_baru !== null || $harga_jual_baru !== null || $nama_baru !== $barang['nama_barang']) {
                $ada_perubahan = true;
                $this->Barang_model->update_harga($id, $harga_beli_final, $harga_jual_final, $nama_baru);
            }
        }

        if (!$ada_perubahan) {
            $this->session->set_flashdata('error', 'Tidak ada perubahan yang disimpan!');
            redirect('barang?tab=pembelian');
        }

        $this->session->set_flashdata('success', 'Data berhasil diperbarui!');
        redirect('barang?tab=pembelian');
    }

    public function hapus() {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            redirect('barang');
        }
        $id     = (int)$this->input->post('id_barang');
        $barang = $this->Barang_model->get_barang_by_id($id);

        if ($barang['stok'] > 0) {
            $this->session->set_flashdata('error', 'Barang tidak bisa dihapus karena masih ada stok!');
            redirect('barang');
        }

        $this->Barang_model->hapus_barang($id);
        $this->session->set_flashdata('success', 'Barang berhasil dihapus!');
        redirect('barang');
    }

    public function sukses_penjualan() {
        $data = $this->session->userdata('transaksi_sukses');
        if (!$data) redirect('barang');
        $this->session->unset_userdata('transaksi_sukses');
        $this->load->view('kasir_sukses_view', $data);
    }
}
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

    $jumlah_beli = [];
    foreach ($jumlah_input as $id => $jumlah) {
        if ((int)$jumlah > 0) {
            $jumlah_beli[(int)$id] = (int)$jumlah;
        }
    }

    if (empty($jumlah_beli)) {
        $this->session->set_flashdata('error', 'Pilih minimal 1 barang untuk dijual!');
        redirect('barang?tab=penjualan');
    }

    // Cek stok
    foreach ($jumlah_beli as $id => $jumlah) {
        $barang = $this->Barang_model->get_barang_by_id($id);
        if ($jumlah > $barang['stok']) {
            $this->session->set_flashdata('error', "Stok {$barang['nama_barang']} tidak cukup!");
            redirect('barang?tab=penjualan');
        }
    }

    $this->load->model('Kasir_model');
    $result = $this->Kasir_model->simpan_transaksi($jumlah_beli);

    $this->load->view('kasir_sukses_view', [
        'no_transaksi'  => $result['no_transaksi'],
        'detail_barang' => $result['detail_barang'],
        'total_harga'   => $result['total_harga'],
    ]);
}

    public function simpan_pembelian() {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            redirect('barang');
        }

        $jumlah_input     = $this->input->post('jumlah')     ?? [];
        $harga_beli_input = $this->input->post('harga_beli') ?? [];
        $harga_jual_input = $this->input->post('harga_jual') ?? [];

        $ada_perubahan = false;
        $tanggal       = date('Y-m-d H:i:s');

        foreach ($jumlah_input as $id => $jumlah) {
            $jumlah = (int)$jumlah;
            $id     = (int)$id;

            $barang           = $this->Barang_model->get_barang_by_id($id);
            $stok_sebelum     = (int)$barang['stok'];
            $harga_beli_baru  = isset($harga_beli_input[$id]) && $harga_beli_input[$id] !== '' ? (int)$harga_beli_input[$id] : null;
            $harga_jual_baru  = isset($harga_jual_input[$id]) && $harga_jual_input[$id] !== '' ? (int)$harga_jual_input[$id] : null;
            $harga_beli_final = $harga_beli_baru ?? (int)$barang['harga_beli'];
            $harga_jual_final = $harga_jual_baru ?? (int)$barang['harga_jual'];

            // Validasi harga jual > harga beli
            if ($harga_jual_baru !== null && $harga_jual_baru <= $harga_beli_final) {
                $this->session->set_flashdata('error', 'Harga jual harus lebih besar dari harga beli!');
                redirect('barang?tab=pembelian');
            }

            if ($jumlah > 0) {
                $ada_perubahan = true;
                $stok_sesudah  = $stok_sebelum + $jumlah;

                $this->Barang_model->update_stok_pembelian($id, $stok_sesudah, $harga_beli_final, $harga_jual_final);
                $this->Barang_model->catat_tambah_stok($id, $jumlah, $harga_beli_final, $tanggal);
                $this->Barang_model->catat_history_stok($id, $jumlah, $stok_sebelum, $stok_sesudah, "Restok dari supplier @ Rp " . number_format($harga_beli_final, 0, ',', '.'), $tanggal);

            } elseif ($harga_beli_baru !== null || $harga_jual_baru !== null) {
                $ada_perubahan = true;
                $this->Barang_model->update_harga($id, $harga_beli_final, $harga_jual_final);
            }
        }

        if (!$ada_perubahan) {
            $this->session->set_flashdata('error', 'Tidak ada perubahan yang disimpan!');
            redirect('barang?tab=pembelian');
        }

        $this->session->set_flashdata('success', 'Data berhasil diperbarui!');
        redirect('barang?tab=pembelian');
    }
}
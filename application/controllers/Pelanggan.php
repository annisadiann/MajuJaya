<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pelanggan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Pelanggan_model');
        cek_admin();
    }

    public function index() {
        $data['pelanggan'] = $this->Pelanggan_model->get_all();
        $this->load->view('pelanggan_view', $data);
    }

    public function simpan() {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            redirect('pelanggan');
        }

        $nama   = trim($this->input->post('nama_pelanggan'));
        $alamat = trim($this->input->post('alamat') ?? '');

        if ($nama === '') {
            $this->session->set_flashdata('error', 'Nama pelanggan tidak boleh kosong!');
            redirect('barang?tab=penjualan');
        }

        $this->Pelanggan_model->simpan($nama, $alamat);
        $this->session->set_flashdata('success', 'Pelanggan berhasil ditambahkan!');
        redirect('barang?tab=penjualan');
    }

    public function get_list() {
        $pelanggan = $this->Pelanggan_model->get_all();
        echo json_encode($pelanggan);
    }
}
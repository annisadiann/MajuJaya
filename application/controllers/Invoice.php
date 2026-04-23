<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Invoice_model');
        cek_login();
    }

    public function index() {
        $no = $this->input->get('no');
        if (!$no) redirect('riwayat');

        $transaksi = $this->Invoice_model->get_transaksi($no);
        if (!$transaksi) redirect('riwayat');

        $data['transaksi'] = $transaksi;
        $data['detail']    = $this->Invoice_model->get_detail($no);

        $this->load->view('invoice_view', $data);
    }

    public function thermal() {
        $no = $this->input->get('no');
        if (!$no) redirect('riwayat');

        $transaksi = $this->Invoice_model->get_transaksi($no);
        if (!$transaksi) redirect('riwayat');

        $data['transaksi'] = $transaksi;
        $data['detail']    = $this->Invoice_model->get_detail($no);

        $this->load->view('invoice_thermal_view', $data);
    }
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_model extends CI_Model {

    public function get_transaksi($no) {
        return $this->db->get_where('transaksi', ['no_transaksi' => $no])->row_array();
    }

    public function get_detail($no) {
        return $this->db->get_where('detail_transaksi', ['no_transaksi' => $no])->result_array();
    }
}
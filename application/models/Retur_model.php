<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Retur_model extends CI_Model {

    public function count_retur() {
        return $this->db->count_all('retur');
    }

    public function get_retur($limit, $offset) {
        return $this->db->query("
            SELECT r.*, p.nama_pelanggan, p.kode_pelanggan
            FROM retur r
            LEFT JOIN transaksi t ON r.no_transaksi = t.no_transaksi
            LEFT JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
            ORDER BY r.tanggal_retur DESC
            LIMIT $limit OFFSET $offset
        ")->result_array();
    }

    public function get_grand_total() {
        $result = $this->db->select_sum('total_retur')->get('retur')->row_array();
        return $result['total_retur'] ?? 0;
    }
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembelian_model extends CI_Model {

    public function count_pembelian() {
        return $this->db->count_all('tambah_stok');
    }

    public function get_pembelian($limit, $offset) {
        return $this->db->select('ts.*, b.nama_barang')
                        ->from('tambah_stok ts')
                        ->join('barang b', 'ts.id_barang = b.id_barang')
                        ->order_by('ts.tanggal', 'DESC')
                        ->limit($limit, $offset)
                        ->get()
                        ->result_array();
    }
}
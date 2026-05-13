<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pelanggan_model extends CI_Model {

    public function generate_kode() {
        $result = $this->db->query("
            SELECT kode_pelanggan FROM pelanggan 
            ORDER BY id_pelanggan DESC 
            LIMIT 1
        ")->row_array();

        if (!$result) {
            return 'C001';
        }

        $angka = (int) substr($result['kode_pelanggan'], 1);
        return 'C' . str_pad($angka + 1, 3, '0', STR_PAD_LEFT);
    }

    public function get_all() {
        return $this->db->order_by('nama_pelanggan')->get('pelanggan')->result_array();
    }

    public function get_by_id($id) {
        return $this->db->get_where('pelanggan', ['id_pelanggan' => $id])->row_array();
    }

    public function simpan($nama, $alamat = '') {
        $kode = $this->generate_kode();
        $this->db->insert('pelanggan', [
            'kode_pelanggan' => $kode,
            'nama_pelanggan' => $nama,
            'alamat'         => $alamat,
        ]);
        return $this->db->insert_id();
    }
}
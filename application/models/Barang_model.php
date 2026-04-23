<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang_model extends CI_Model {

    public function count_barang() {
        return $this->db->count_all('barang');
    }

    public function get_barang($limit, $offset) {
        return $this->db->get('barang', $limit, $offset)->result_array();
    }

    public function simpan_barang($nama_barang, $harga_beli, $harga_jual, $jumlah_stok) {
        $this->db->insert('barang', [
            'nama_barang' => $nama_barang,
            'harga_beli'  => $harga_beli,
            'harga_jual'  => $harga_jual,
            'stok'        => $jumlah_stok,
            'stok_awal'   => $jumlah_stok,
        ]);

        $id_barang = $this->db->insert_id();
        $tanggal   = date('Y-m-d H:i:s');

        if ($jumlah_stok > 0) {
            $this->db->insert('history_stok', [
                'id_barang'    => $id_barang,
                'jenis'        => 'tambah',
                'jumlah'       => $jumlah_stok,
                'stok_sebelum' => 0,
                'stok_sesudah' => $jumlah_stok,
                'keterangan'   => 'Stok awal barang baru',
                'tanggal'      => $tanggal,
            ]);
        }
    }
}
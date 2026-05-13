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

    public function get_barang_by_id($id) {
    return $this->db->get_where('barang', ['id_barang' => $id])->row_array();
    }

    public function update_stok_pembelian($id, $stok_baru, $harga_beli, $harga_jual, $nama_barang = null) {
        $data = [
            'stok'       => $stok_baru,
            'harga_beli' => $harga_beli,
            'harga_jual' => $harga_jual,
        ];
        if ($nama_barang !== null) {
            $data['nama_barang'] = $nama_barang;
        }
        $result = $this->db->where('id_barang', $id)->update('barang', $data);
        
        log_message('error', "UPDATE barang id=$id stok=$stok_baru result=" . ($result ? 'true' : 'false'));
        
        return $result;
    }

    public function update_harga($id, $harga_beli, $harga_jual, $nama_barang = null) {
        $data = [
            'harga_beli' => $harga_beli,
            'harga_jual' => $harga_jual,
        ];
        if ($nama_barang !== null) {
            $data['nama_barang'] = $nama_barang;
        }
        $this->db->where('id_barang', $id)->update('barang', $data);
    }

    public function catat_tambah_stok($id, $jumlah, $harga_beli, $tanggal) {
        $this->db->insert('tambah_stok', [
            'id_barang'    => $id,
            'jumlah_tambah' => $jumlah,
            'harga_beli'   => $harga_beli,
            'tanggal'      => $tanggal,
        ]);
    }

    public function catat_history_stok($id, $jumlah, $stok_sebelum, $stok_sesudah, $keterangan, $tanggal) {
        $this->db->insert('history_stok', [
            'id_barang'    => $id,
            'jenis'        => 'tambah',
            'jumlah'       => $jumlah,
            'stok_sebelum' => $stok_sebelum,
            'stok_sesudah' => $stok_sesudah,
            'keterangan'   => $keterangan,
            'tanggal'      => $tanggal,
        ]);
    }

    public function update_nama($id, $nama_baru) {
        $this->db->where('id_barang', $id)->update('barang', [
            'nama_barang' => $nama_baru
        ]);
    }

    public function hapus_barang($id) {
        $this->db->where('id_barang', $id)->delete('barang');
    }
}
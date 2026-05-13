<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kasir_model extends CI_Model {

    public function count_barang() {
        return $this->db->count_all('barang');
    }

    public function get_barang($limit, $offset) {
        return $this->db->limit($limit, $offset)->get('barang')->result_array();
    }

    public function get_barang_by_id($id) {
        return $this->db->get_where('barang', ['id_barang' => $id])->row_array();
    }

    public function simpan_transaksi($jumlah_beli, $id_pelanggan = null) {
        $tanggal  = date('Y-m-d H:i:s');
        $hari_ini = date('Y-m-d');

        $urutan = $this->db
            ->where("DATE(tanggal)", $hari_ini)
            ->count_all_results('transaksi') + 1;
        $no_transaksi = date('d/m/Y') . '-' . str_pad($urutan, 2, '0', STR_PAD_LEFT);

        $total_harga   = 0;
        $detail_barang = [];

        foreach ($jumlah_beli as $id => $jumlah) {
            $barang   = $this->get_barang_by_id($id);
            $subtotal = $barang['harga_jual'] * $jumlah;
            $total_harga += $subtotal;

            $detail_barang[] = [
                'id_barang'    => $id,
                'nama_barang'  => $barang['nama_barang'],
                'harga_satuan' => $barang['harga_jual'],
                'jumlah'       => $jumlah,
                'total_harga'  => $subtotal,
                'stok_sebelum' => $barang['stok'],
                'stok_sesudah' => $barang['stok'] - $jumlah,
            ];
        }

        $this->db->insert('transaksi', [
            'no_transaksi' => $no_transaksi,
            'tanggal'      => $tanggal,
            'jumlah'       => count($jumlah_beli),
            'total_harga'  => $total_harga,
            'id_pelanggan' => $id_pelanggan,  // ← tambahan
        ]);

        foreach ($detail_barang as $item) {
            $this->db->insert('detail_transaksi', [
                'no_transaksi' => $no_transaksi,
                'nama_barang'  => $item['nama_barang'],
                'jumlah'       => $item['jumlah'],
                'harga_satuan' => $item['harga_satuan'],
                'total_harga'  => $item['total_harga'],
            ]);

            $this->db->where('id_barang', $item['id_barang'])
                    ->set('stok', 'stok - ' . $item['jumlah'], FALSE)
                    ->update('barang');

            $this->db->insert('history_stok', [
                'id_barang'    => $item['id_barang'],
                'jenis'        => 'kurang',
                'jumlah'       => $item['jumlah'],
                'stok_sebelum' => $item['stok_sebelum'],
                'stok_sesudah' => $item['stok_sesudah'],
                'keterangan'   => 'Terjual via transaksi',
                'no_transaksi' => $no_transaksi,
                'tanggal'      => $tanggal,
            ]);
        }

        return compact('no_transaksi', 'detail_barang', 'total_harga');
    }

    public function search_barang($keyword) {
        $keywords = array_filter(explode(' ', $keyword));
        $this->db->group_start();
        foreach ($keywords as $k) {
            $this->db->or_like('nama_barang', $k);
        }
        $this->db->group_end();
        return $this->db->order_by('nama_barang', 'ASC')
                        ->get('barang')
                        ->result_array();
    }
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stok_model extends CI_Model {

    public function count_barang() {
        return $this->db->count_all('barang');
    }

    public function count_stok_habis() {
        return $this->db->where('stok', 0)->count_all_results('barang');
    }

    public function get_barang_by_id($id) {
        return $this->db->get_where('barang', ['id_barang' => $id])->row_array();
    }

    public function get_tracking($limit, $offset) {
        return $this->db->query("
            SELECT
                b.id_barang,
                b.nama_barang,
                b.stok_awal,
                COALESCE((SELECT SUM(jumlah_tambah) FROM tambah_stok    WHERE id_barang = b.id_barang), 0) as ditambah,
                COALESCE((SELECT SUM(jumlah)        FROM detail_transaksi WHERE nama_barang = b.nama_barang), 0) as terjual,
                COALESCE((SELECT SUM(jumlah_retur)  FROM retur           WHERE nama_barang = b.nama_barang), 0) as diretur,
                b.stok_awal
                + COALESCE((SELECT SUM(jumlah_tambah) FROM tambah_stok    WHERE id_barang = b.id_barang), 0)
                - COALESCE((SELECT SUM(jumlah)        FROM detail_transaksi WHERE nama_barang = b.nama_barang), 0)
                + COALESCE((SELECT SUM(jumlah_retur)  FROM retur           WHERE nama_barang = b.nama_barang), 0) as stok_akhir
            FROM barang b
            ORDER BY b.nama_barang
            LIMIT $limit OFFSET $offset
        ")->result_array();
    }

    public function count_history($id_barang, $filter) {
        $sql = "SELECT COUNT(*) as total FROM history_stok WHERE id_barang = ?";
        $params = [$id_barang];

        if ($filter === 'tambah') {
            $sql .= " AND jenis = 'tambah'";
        } elseif ($filter === 'kurang') {
            $sql .= " AND jenis = 'kurang'";
        } elseif ($filter === 'retur') {
            $sql .= " AND keterangan LIKE '%Retur%'";
        }

        return $this->db->query($sql, $params)->row_array()['total'];
    }

    public function get_history($id_barang, $filter, $limit, $offset) {
        $sql = "SELECT * FROM history_stok WHERE id_barang = ?";
        $params = [$id_barang];

        if ($filter === 'tambah') {
            $sql .= " AND jenis = 'tambah'";
        } elseif ($filter === 'kurang') {
            $sql .= " AND jenis = 'kurang'";
        } elseif ($filter === 'retur') {
            $sql .= " AND keterangan LIKE '%Retur%'";
        }

        $sql .= " ORDER BY tanggal DESC LIMIT $limit OFFSET $offset";

        return $this->db->query($sql, $params)->result_array();
    }
}
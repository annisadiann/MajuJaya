<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stok_model extends CI_Model {

    public function count_barang($nama = '', $dari = '', $sampai = '') {
        $where = [];
        $params = [];
        if ($nama) {
            $where[] = "b.nama_barang LIKE ?";
            $params[] = "%$nama%";
        }
        if ($dari || $sampai) {
            $sub = "EXISTS (SELECT 1 FROM history_stok h WHERE h.id_barang = b.id_barang";
            if ($dari)   { $sub .= " AND DATE(h.tanggal) >= '$dari'"; }
            if ($sampai) { $sub .= " AND DATE(h.tanggal) <= '$sampai'"; }
            $sub .= ")";
            $where[] = $sub;
        }
        $whereSql = $where ? "WHERE " . implode(" AND ", $where) : "";
        return $this->db->query("SELECT COUNT(*) as total FROM barang b $whereSql", $params)->row_array()['total'];
    }

    public function count_stok_habis() {
        return $this->db->where('stok', 0)->count_all_results('barang');
    }

    public function get_barang_by_id($id) {
        return $this->db->get_where('barang', ['id_barang' => $id])->row_array();
    }

    public function get_tracking($limit, $offset, $nama = '', $dari = '', $sampai = '') {
        $where = [];
        $params = [];
        if ($nama) {
            $where[] = "b.nama_barang LIKE ?";
            $params[] = "%$nama%";
        }
        if ($dari || $sampai) {
            $sub = "EXISTS (SELECT 1 FROM history_stok h WHERE h.id_barang = b.id_barang";
            if ($dari)   { $sub .= " AND DATE(h.tanggal) >= '$dari'"; }
            if ($sampai) { $sub .= " AND DATE(h.tanggal) <= '$sampai'"; }
            $sub .= ")";
            $where[] = $sub;
        }
        $whereSql = $where ? "WHERE " . implode(" AND ", $where) : "";

        $tglWhere = '';
        if ($dari)   $tglWhere .= " AND DATE(tanggal) >= '$dari'";
        if ($sampai) $tglWhere .= " AND DATE(tanggal) <= '$sampai'";

        $params[] = $limit;
        $params[] = $offset;

        return $this->db->query("
            SELECT
                b.id_barang,
                b.nama_barang,
                b.stok_awal,
                COALESCE((
                    SELECT SUM(jumlah) FROM history_stok
                    WHERE id_barang = b.id_barang
                    AND jenis = 'tambah'
                    AND keterangan NOT LIKE '%Retur%'
                    $tglWhere
                ), 0) as ditambah,
                COALESCE((
                    SELECT SUM(jumlah) FROM history_stok
                    WHERE id_barang = b.id_barang
                    AND jenis = 'kurang'
                    AND keterangan NOT LIKE '%Retur%'
                    $tglWhere
                ), 0) as terjual,
                COALESCE((
                    SELECT SUM(jumlah) FROM history_stok
                    WHERE id_barang = b.id_barang
                    AND keterangan LIKE '%Retur%'
                    $tglWhere
                ), 0) as diretur,
                b.stok as stok_akhir
            FROM barang b $whereSql
            ORDER BY b.nama_barang
            LIMIT ? OFFSET ?
        ", $params)->result_array();
    }

    public function get_detail_transaksi($id_barang, $dari = '', $sampai = '') {
        $sql = "SELECT * FROM history_stok WHERE id_barang = ?";
        $params = [$id_barang];
        if ($dari)   $sql .= " AND DATE(tanggal) >= '$dari'";
        if ($sampai) $sql .= " AND DATE(tanggal) <= '$sampai'";
        $sql .= " ORDER BY tanggal ASC";
        return $this->db->query($sql, $params)->result_array();
    }

    public function count_history($id_barang, $filter, $dari = '', $sampai = '') {
        $sql = "SELECT COUNT(*) as total FROM history_stok WHERE id_barang = ?";
        $params = [$id_barang];
        if ($filter === 'tambah') {
            $sql .= " AND jenis = 'tambah' AND keterangan NOT LIKE '%Retur%'";
        } elseif ($filter === 'kurang') {
            $sql .= " AND jenis = 'kurang'";
        } elseif ($filter === 'retur') {
            $sql .= " AND keterangan LIKE '%Retur%'";
        }
        if ($dari)   $sql .= " AND DATE(tanggal) >= '$dari'";
        if ($sampai) $sql .= " AND DATE(tanggal) <= '$sampai'";
        return $this->db->query($sql, $params)->row_array()['total'];
    }

    public function get_history($id_barang, $filter, $limit, $offset, $dari = '', $sampai = '') {
        $sql = "SELECT * FROM history_stok WHERE id_barang = ?";
        $params = [$id_barang];
        if ($filter === 'tambah') {
            $sql .= " AND jenis = 'tambah' AND keterangan NOT LIKE '%Retur%'";
        } elseif ($filter === 'kurang') {
            $sql .= " AND jenis = 'kurang'";
        } elseif ($filter === 'retur') {
            $sql .= " AND keterangan LIKE '%Retur%'";
        }
        if ($dari)   $sql .= " AND DATE(tanggal) >= '$dari'";
        if ($sampai) $sql .= " AND DATE(tanggal) <= '$sampai'";
        $sql .= " ORDER BY tanggal DESC LIMIT $limit OFFSET $offset";
        return $this->db->query($sql, $params)->result_array();
    }
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayat_model extends CI_Model {

    private function build_where($dari, $sampai, $barang) {
        $where = [];
        if ($dari)   $where[] = "DATE(tanggal) >= " . $this->db->escape($dari);
        if ($sampai) $where[] = "DATE(tanggal) <= " . $this->db->escape($sampai);
        if ($barang) $where[] = "no_transaksi IN (SELECT no_transaksi FROM detail_transaksi WHERE nama_barang = " . $this->db->escape($barang) . ")";
        return $where ? implode(' AND ', $where) : '1=1';
    }

    public function count_transaksi($dari, $sampai, $barang) {
        $where = $this->build_where($dari, $sampai, $barang);
        return $this->db->query("SELECT COUNT(*) as total FROM transaksi WHERE $where")
                        ->row_array()['total'];
    }

    public function get_transaksi($dari, $sampai, $barang, $limit, $offset) {
        $where = $this->build_where($dari, $sampai, $barang);
        $rows  = $this->db->query(
            "SELECT * FROM transaksi WHERE $where ORDER BY tanggal DESC LIMIT $limit OFFSET $offset"
        )->result_array();

        foreach ($rows as &$row) {
            $no  = $row['no_transaksi'];
            $tgl = $row['tanggal'];

            $details = $this->db->query(
                "SELECT dt.*, COALESCE(SUM(r.jumlah_retur),0) as sudah_retur
                 FROM detail_transaksi dt
                 LEFT JOIN retur r ON dt.no_transaksi = r.no_transaksi AND dt.nama_barang = r.nama_barang
                 WHERE dt.no_transaksi = " . $this->db->escape($no) . "
                 GROUP BY dt.id_detail"
            )->result_array();

            $bisa_retur = false;
            foreach ($details as $d) {
                if ($d['sudah_retur'] < $d['jumlah']) {
                    $bisa_retur = true;
                    break;
                }
            }

            $batas       = date('Y-m-d', strtotime($tgl . ' +1 day'));
            $masa_berlaku = (date('Y-m-d') <= $batas);

            $row['bisa_retur']   = $bisa_retur;
            $row['masa_berlaku'] = $masa_berlaku;
        }

        return $rows;
    }

    public function get_grand_total($dari, $sampai, $barang) {
        $where = $this->build_where($dari, $sampai, $barang);
        $result = $this->db->query("SELECT SUM(total_harga) as grand FROM transaksi WHERE $where")->row_array();
        return $result['grand'] ?? 0;
    }

    public function get_daftar_barang() {
        return $this->db->query(
            "SELECT DISTINCT nama_barang FROM detail_transaksi ORDER BY nama_barang"
        )->result_array();
    }

    public function get_all_detail() {
        $rows = $this->db->query(
            "SELECT dt.*, COALESCE(SUM(r.jumlah_retur),0) as sudah_retur
             FROM detail_transaksi dt
             LEFT JOIN retur r ON dt.no_transaksi = r.no_transaksi AND dt.nama_barang = r.nama_barang
             GROUP BY dt.id_detail"
        )->result_array();

        $detail = [];
        foreach ($rows as $d) {
            $detail[$d['no_transaksi']][] = $d;
        }
        return $detail;
    }

    public function get_total_retur($no_transaksi, $nama_barang) {
        $result = $this->db->query(
            "SELECT SUM(jumlah_retur) as total FROM retur WHERE no_transaksi = " . $this->db->escape($no_transaksi) .
            " AND nama_barang = " . $this->db->escape($nama_barang)
        )->row_array();
        return $result['total'] ?? 0;
    }

    public function simpan_retur($no_transaksi, $nama_barang, $harga_satuan, $jumlah_retur) {
        $total_retur   = $harga_satuan * $jumlah_retur;
        $tanggal_retur = date('Y-m-d H:i:s');
        $hari_ini      = date('Y-m-d');

        $urutan = $this->db->query(
            "SELECT COUNT(*) as total FROM retur WHERE DATE(tanggal_retur) = '$hari_ini'"
        )->row_array()['total'] + 1;
        $no_retur = 'RTR-' . date('d/m/Y') . '-' . str_pad($urutan, 2, '0', STR_PAD_LEFT);

        $this->db->insert('retur', [
            'no_retur'      => $no_retur,
            'no_transaksi'  => $no_transaksi,
            'tanggal_retur' => $tanggal_retur,
            'nama_barang'   => $nama_barang,
            'jumlah_retur'  => $jumlah_retur,
            'harga_satuan'  => $harga_satuan,
            'total_retur'   => $total_retur,
        ]);

        $barang = $this->db->get_where('barang', ['nama_barang' => $nama_barang])->row_array();
        $stok_sebelum = $barang['stok'];
        $stok_sesudah = $stok_sebelum + $jumlah_retur;

        $this->db->where('nama_barang', $nama_barang)
                 ->set('stok', 'stok + ' . $jumlah_retur, FALSE)
                 ->update('barang');

        $this->db->insert('history_stok', [
            'id_barang'    => $barang['id_barang'],
            'jenis'        => 'tambah',
            'jumlah'       => $jumlah_retur,
            'stok_sebelum' => $stok_sebelum,
            'stok_sesudah' => $stok_sesudah,
            'keterangan'   => 'Retur dari transaksi',
            'no_transaksi' => $no_transaksi,
            'tanggal'      => $tanggal_retur,
        ]);

        return compact('no_retur', 'total_retur');
    }
}
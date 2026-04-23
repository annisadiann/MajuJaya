<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Retur_model extends CI_Model {

    public function count_retur() {
        return $this->db->count_all('retur');
    }

    public function get_retur($limit, $offset) {
        return $this->db->order_by('tanggal_retur', 'DESC')
                        ->limit($limit, $offset)
                        ->get('retur')
                        ->result_array();
    }

    public function get_grand_total() {
        $result = $this->db->select_sum('total_retur')->get('retur')->row_array();
        return $result['total_retur'] ?? 0;
    }
}
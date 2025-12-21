<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_stores extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getStoresData($id = null) {
        if ($id) {
            $sql = "SELECT * FROM stores WHERE id = ?";
            $query = $this->db->query($sql, array($id));
            return $query->row_array();
        }

        $sql = "SELECT * FROM stores";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function create($data) {
        if ($data) {
            $create = $this->db->insert('stores', $data);
            return ($create == true) ? true : false;
        }
    }

    public function update($data, $id) {
        if ($id && $data) {
            $this->db->where('id', $id);
            return $this->db->update('stores', $data);
        }
        return false;
    }

    public function remove($id) {
        if ($id) {
            $this->db->where('id', $id);
            $delete = $this->db->delete('stores');
            return ($delete == true) ? true : false;
        }
    }

    public function countTotalStores() {
        $sql = "SELECT COUNT(*) as count FROM stores";
        $query = $this->db->query($sql);
        return $query->row()->count;
    }

    public function getActiveStore() {
        $sql = "SELECT * FROM stores WHERE active = 1";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}

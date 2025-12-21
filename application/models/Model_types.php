<?php
class Model_types extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function getTypeData($id = null) {
        if ($id) {
            $sql = "SELECT * FROM types WHERE id = ?";
            $query = $this->db->query($sql, array($id));
            return $query->row_array();
        }

        $sql = "SELECT * FROM types";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getActiveTypes() {
        $sql = "SELECT * FROM types WHERE active = 1";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function create($data) {
        if ($data) {
            $create = $this->db->insert('types', $data);
            return ($create == true) ? true : false;
        }
    }

    public function update($data, $id) {
        if ($id && $data) {
            $this->db->where('id', $id);
            return $this->db->update('types', $data);
        }
        return false;
    }

    public function remove($id) {
        if ($id) {
            $this->db->where('id', $id);
            $delete = $this->db->delete('types');
            return ($delete == true) ? true : false;
        }
    }

    public function countTotalTypes() {
        $sql = "SELECT COUNT(*) as count FROM types";
        $query = $this->db->query($sql);
        return $query->row_array()['count'];
    }
}

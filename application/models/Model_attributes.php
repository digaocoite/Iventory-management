<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_attributes extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getActiveAttributeData() {
        $sql = "SELECT * FROM attributes WHERE active = ?";
        $query = $this->db->query($sql, array(1));
        return $query->result_array();
    }

    public function getAttributeData($id = null) {
        if ($id) {
            $sql = "SELECT * FROM attributes WHERE id = ?";
            $query = $this->db->query($sql, array($id));
            return $query->row_array();
        }

        $sql = "SELECT * FROM attributes";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function countAttributeValue($id = null) {
        if ($id) {
            $sql = "SELECT * FROM attribute_value WHERE attribute_parent_id = ?";
            $query = $this->db->query($sql, array($id));
            return $query->num_rows();
        }
    }

    public function getAttributeValueData($id = null) {
        $sql = "SELECT * FROM attribute_value WHERE attribute_parent_id = ?";
        $query = $this->db->query($sql, array($id));
        return $query->result_array();
    }

    public function getAttributeValueById($id = null) {
        $sql = "SELECT * FROM attribute_value WHERE id = ?";
        $query = $this->db->query($sql, array($id));
        return $query->row_array();
    }

    public function create($data) {
        if ($data) {
            $insert = $this->db->insert('attributes', $data);
            return ($insert == true) ? true : false;
        }
        return false;
    }

    public function update($data, $id) {
        if ($data && $id) {
            $this->db->where('id', $id);
            $update = $this->db->update('attributes', $data);
            return ($update == true) ? true : false;
        }
        return false;
    }

    public function remove($id) {
        if ($id) {
            $this->db->where('id', $id);
            $delete = $this->db->delete('attributes');
            return ($delete == true) ? true : false;
        }
        return false;
    }

    public function createValue($data) {
        if ($data) {
            $insert = $this->db->insert('attribute_value', $data);
            return ($insert == true) ? true : false;
        }
        return false;
    }

    public function updateValue($data, $id) {
        if ($data && $id) {
            $this->db->where('id', $id);
            $update = $this->db->update('attribute_value', $data);
            return ($update == true) ? true : false;
        }
        return false;
    }

    public function removeValue($id) {
        if ($id) {
            $this->db->where('id', $id);
            $delete = $this->db->delete('attribute_value');
            return ($delete == true) ? true : false;
        }
        return false;
    }

}

?>


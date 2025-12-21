<?php

class Model_products extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function getProductData($id = null) {
        if($id) {
            $sql = "SELECT * FROM products WHERE id = ?";
            $query = $this->db->query($sql, array($id));
            return $query->row_array();
        }

        $sql = "SELECT * FROM products";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function create($data) {
        if($data) {
            $insert = $this->db->insert('products', $data);
            return ($insert == true) ? true : false;
        }
    }

    public function update($data, $id) {
        if($data && $id) {
            $this->db->where('id', $id);
            $update = $this->db->update('products', $data);
            return ($update == true) ? true : false;
        }
    }

    public function remove($id) {
        if($id) {
            $this->db->where('id', $id);
            $delete = $this->db->delete('products');
            return ($delete == true) ? true : false;
        }
    }

    public function getStoresData($store_id) {
        $sql = "SELECT * FROM stores WHERE id = ?";
        $query = $this->db->query($sql, array($store_id));
        return $query->row_array();
    }

    public function getCategoryData($category_id) {
        $sql = "SELECT * FROM categories WHERE id = ?";
        $query = $this->db->query($sql, array($category_id));
        return $query->row_array();
    }

    public function getActiveAttributeData() {
        $sql = "SELECT * FROM attributes WHERE active = 1";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getAttributeValueData($attribute_id) {
        $sql = "SELECT * FROM attribute_values WHERE attribute_parent_id = ?";
        $query = $this->db->query($sql, array($attribute_id));
        return $query->result_array();
    }

    public function getActiveStore() {
        $sql = "SELECT * FROM stores WHERE active = 1";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}
?>


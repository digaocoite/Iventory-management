<?php

class Model_products extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getProductData($id = null)
    {
        if ($id) {
            $sql = "SELECT * FROM products WHERE id = ?";
            $query = $this->db->query($sql, array($id));
            return $query->row_array();
        }

        $sql = "SELECT * FROM products";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function create($data)
    {
        if ($data) {
            $create = $this->db->insert('products', $data);
            return ($create == true) ? true : false;
        }
    }

    public function update($data, $id)
    {
        if ($id && $data) {
            $this->db->where('id', $id);
            return $this->db->update('products', $data);
        }
        return false;
    }

    public function remove($id)
    {
        if ($id) {
            $this->db->where('id', $id);
            $delete = $this->db->delete('products');
            return ($delete == true) ? true : false;
        }
    }

    public function getActiveProductData()
    {
        $sql = "SELECT * FROM products";  // No 'active' field, so fetch all products
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function countTotalProducts()
    {
        $sql = "SELECT COUNT(*) as count FROM products";
        $query = $this->db->query($sql);
        return $query->row_array()['count'];
    }

    public function getProductDataByCategory($category_id)
    {
        $sql = "SELECT * FROM products WHERE type_id = ?";
        $query = $this->db->query($sql, array($category_id));
        return $query->result_array();
    }
}


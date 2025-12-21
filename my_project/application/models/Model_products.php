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
            $sql = "SELECT id, name, reference_number, category_id, qty, store_id, availability, expiration_date, LOT FROM products WHERE id = ?";
            $query = $this->db->query($sql, array($id));
            return $query->row_array();
        }

        $sql = "SELECT id, name, reference_number, category_id, qty, store_id, availability, expiration_date, LOT FROM products";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function create($data)
    {
        if ($data) {
            $insert = $this->db->insert('products', $data);
            return ($insert == true) ? true : false;
        }
    }

    public function update($data, $id)
    {
        if ($data && $id) {
            $this->db->where('id', $id);
            $update = $this->db->update('products', $data);
            return ($update == true) ? true : false;
        }
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
        $sql = "SELECT * FROM products WHERE availability = ?";
        $query = $this->db->query($sql, array(1));
        return $query->result_array();
    }

    public function getProductDataByStore($store_id)
    {
        $sql = "SELECT * FROM products WHERE store_id = ?";
        $query = $this->db->query($sql, array($store_id));
        return $query->result_array();
    }

    public function countTotalProducts()
    {
        $sql = "SELECT * FROM products";
        $query = $this->db->query($sql);
        return $query->num_rows();
    }
}
?>


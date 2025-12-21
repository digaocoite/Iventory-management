<?php

class Model_orders extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create()
    {
        $user_id = $this->session->userdata('id');
        $order_number = $this->input->post('order_number');
        $data = array(
            'order_number' => $order_number,
            'customer_name' => $this->input->post('customer_name'),
            'customer_address' => $this->input->post('customer_address'),
            'customer_phone' => $this->input->post('customer_phone'),
            'date_time' => strtotime(date('Y-m-d h:i:s a')),
            'gross_amount' => $this->input->post('gross_amount_value'),
            'service_charge' => $this->input->post('service_charge_value'),
            'vat_charge' => $this->input->post('vat_charge_value'),
            'net_amount' => $this->input->post('net_amount_value'),
            'discount' => $this->input->post('discount'),
            'paid_status' => 2,
            'user_id' => $user_id
        );

        $insert = $this->db->insert('orders', $data);
        $order_id = $this->db->insert_id();

        $this->load->model('model_products');

        $count_product = count($this->input->post('product'));
        for($x = 0; $x < $count_product; $x++) {
            $items = array(
                'order_id' => $order_id,
                'product_id' => $this->input->post('product')[$x],
                'qty' => $this->input->post('qty')[$x],
                'rate' => $this->input->post('rate_value')[$x],
                'amount' => $this->input->post('amount_value')[$x],
            );

            $this->db->insert('orders_item', $items);

            $product_data = $this->model_products->getProductData($this->input->post('product')[$x]);
            $qty = (int) $product_data['qty'] - (int) $this->input->post('qty')[$x];

            $update_product = array('qty' => $qty);

            $this->model_products->update($update_product, $this->input->post('product')[$x]);
        }

        return ($order_id) ? $order_id : false;
    }

    public function countTotalPaidOrders()
    {
        $sql = "SELECT * FROM orders WHERE paid_status = ?";
        $query = $this->db->query($sql, array(1));
        return $query->num_rows();
    }

    public function getOrdersData()
    {
        $sql = "SELECT * FROM orders ORDER BY id DESC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}


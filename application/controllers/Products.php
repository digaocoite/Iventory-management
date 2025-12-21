<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->not_logged_in();
        $this->data['page_title'] = 'Products';
        $this->load->model('model_products');
        $this->load->model('model_stores');
        $this->load->model('model_attributes');
        $this->load->model('model_types');  // Ensure the types model is loaded
    }

    public function index() {
        if (!in_array('viewProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
        $this->render_template('products/index', $this->data);
    }

    public function fetchProductData() {
        $result = array('data' => array());
        try {
            $data = $this->model_products->getProductData();
            foreach ($data as $key => $value) {
                $store_data = $this->model_stores->getStoresData($value['store_id']);
                $type_data = $this->model_types->getTypeData($value['type_id']);
                $expiration_date = $value['expiration_date'];
                $expires_in = $this->calculateExpiresIn($expiration_date);

                $expires_text = ($expires_in <= 10) ? 'Urgent! ' . $expires_in . ' days' : $expires_in . ' days';

                $buttons = '';
                if (in_array('updateProduct', $this->permission)) {
                    $buttons .= '<a href="'.base_url('products/update/'.$value['id']).'" class="btn btn-default"><i class="fa fa-pencil"></i></a>';
                }
                if (in_array('deleteProduct', $this->permission)) {
                    $buttons .= '<button type="button" class="btn btn-default" onclick="removeFunc('.$value['id'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';
                }

                $availability = ($value['availability'] == 1) ? 'Active' : 'Inactive';
                $qty_status = '';
                if ($value['qty'] <= 10) {
                    $qty_status = 'Low !';
                } else if ($value['qty'] <= 0) {
                    $qty_status = 'Out of stock !';
                }

                $type_name = isset($type_data['name']) ? $type_data['name'] : 'Unknown';

                $result['data'][$key] = array(
                    $value['name'],  // Product name
                    $value['reference_number'],  // Reference number
                    $value['LOT'],  // LOT
                    $type_name,  // Type name
                    $value['qty'] . ' ' . $qty_status,  // Quantity and status
                    $store_data['name'],  // Store name
                    $availability,  // Availability
                    $value['expiration_date'],  // Expiration date
                    $expires_text,  // Expires in
                    $buttons  // Action buttons
                );
            }

            echo json_encode($result);
        } catch (Exception $e) {
            log_message('error', 'Error in fetchProductData: ' . $e->getMessage());
            echo json_encode(['data' => [], 'error' => $e->getMessage()]);
        }
    }

    private function calculateExpiresIn($expiration_date) {
        $now = time();
        $expiration = strtotime($expiration_date);
        $diff = $expiration - $now;
        return round($diff / (60 * 60 * 24)); // Returns days until expiration
    }

    public function create() {
        if (!in_array('createProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        $this->form_validation->set_rules('reference_number', 'Reference Number', 'trim|required');
        $this->form_validation->set_rules('product_name', 'Product name', 'trim|required');
        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        $this->form_validation->set_rules('qty', 'Qty', 'trim|required');
        $this->form_validation->set_rules('store', 'Store', 'trim|required');
        $this->form_validation->set_rules('availability', 'Availability', 'trim|required');
        $this->form_validation->set_rules('expiration_date', 'Expiration Date', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            $data = array(
                'reference_number' => $this->input->post('reference_number'),
                'name' => $this->input->post('product_name'),
                'LOT' => $this->input->post('LOT'),
                'qty' => $this->input->post('qty'),
                'description' => $this->input->post('description'),
                'attribute_value_id' => json_encode($this->input->post('attributes_value_id') ?? []),
                'type_id' => $this->input->post('type'),
                'store_id' => $this->input->post('store'),
                'availability' => $this->input->post('availability'),
                'expiration_date' => $this->input->post('expiration_date')
            );

            $create = $this->model_products->create($data);
            if ($create == true) {
                $this->session->set_flashdata('success', 'Successfully created');
                redirect('products/', 'refresh');
            } else {
                $this->session->set_flashdata('errors', 'Error occurred!!');
                redirect('products/create', 'refresh');
            }
        } else {
            $attribute_data = $this->model_attributes->getActiveAttributeData();

            $attributes_final_data = array();
            foreach ($attribute_data as $k => $v) {
                $attributes_final_data[$k]['attribute_data'] = $v;

                $value = $this->model_attributes->getAttributeValueData($v['id']);

                $attributes_final_data[$k]['attribute_value'] = $value;
            }

            $this->data['attributes'] = $attributes_final_data;
            $this->data['types'] = $this->model_types->getActiveTypes();  // Updated line
            $this->data['stores'] = $this->model_stores->getActiveStore();

            $this->render_template('products/create', $this->data);
        }
    }

    public function update($product_id) {
        if (!in_array('updateProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        if (!$product_id) {
            redirect('dashboard', 'refresh');
        }

        $this->form_validation->set_rules('reference_number', 'Reference Number', 'trim|required');
        $this->form_validation->set_rules('product_name', 'Product name', 'trim|required');
        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        $this->form_validation->set_rules('qty', 'Qty', 'trim|required');
        $this->form_validation->set_rules('store', 'Store', 'trim|required');
        $this->form_validation->set_rules('availability', 'Availability', 'trim|required');
        $this->form_validation->set_rules('expiration_date', 'Expiration Date', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            $data = array(
                'reference_number' => $this->input->post('reference_number'),
                'name' => $this->input->post('product_name'),
                'LOT' => $this->input->post('LOT'),
                'qty' => $this->input->post('qty'),
                'description' => $this->input->post('description'),
                'attribute_value_id' => json_encode($this->input->post('attributes_value_id') ?? []),
                'type_id' => $this->input->post('type'),
                'store_id' => $this->input->post('store'),
                'availability' => $this->input->post('availability'),
                'expiration_date' => $this->input->post('expiration_date')
            );

            $update = $this->model_products->update($data, $product_id);
            if ($update == true) {
                $this->session->set_flashdata('success', 'Successfully updated');
                redirect('products/', 'refresh');
            } else {
                $this->session->set_flashdata('errors', 'Error occurred!!');
                redirect('products/update/'.$product_id, 'refresh');
            }
        } else {
            $attribute_data = $this->model_attributes->getActiveAttributeData();
            $attributes_final_data = array();
            foreach ($attribute_data as $k => $v) {
                $attributes_final_data[$k]['attribute_data'] = $v;
                $value = $this->model_attributes->getAttributeValueData($v['id']);
                $attributes_final_data[$k]['attribute_value'] = $value;
            }

            $this->data['attributes'] = $attributes_final_data;
            $this->data['types'] = $this->model_types->getActiveTypes();  // Updated line
            $this->data['stores'] = $this->model_stores->getActiveStore();

            // Fetch product data
            $product_data = $this->model_products->getProductData($product_id);
            $product_data['description'] = $product_data['description'] ?? '';
            $product_data['attribute_value_id'] = isset($product_data['attribute_value_id']) && is_string($product_data['attribute_value_id']) ? json_decode($product_data['attribute_value_id'], true) : [];
            $this->data['product_data'] = $product_data;

            $this->render_template('products/edit', $this->data);
        }
    }

    public function remove() {
        if (!in_array('deleteProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        $product_id = $this->input->post('product_id');
        $response = array();

        if ($product_id) {
            $delete = $this->model_products->remove($product_id);
            if ($delete == true) {
                $response['success'] = true;
                $response['messages'] = "Successfully removed";
            } else {
                $response['success'] = false;
                $response['messages'] = "Error in the database while removing the product information";
            }
        } else {
            $response['success'] = false;
            $response['messages'] = "Refresh the page again!!";
        }

        echo json_encode($response);
    }
}
?>


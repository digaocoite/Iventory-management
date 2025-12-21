<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Brands extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->not_logged_in(); // Ensure user is logged in

        $this->data['page_title'] = 'Brands';

        $this->load->model('model_brands'); // Load the Brands model
    }

    public function index()
    {
        if (!in_array('viewBrand', $this->permission)) {
            redirect('dashboard', 'refresh'); // Redirect to dashboard if user lacks permission
        }

        $result = $this->model_brands->getBrandData(); // Fetch all brands

        $this->data['results'] = $result;

        $this->render_template('brands/index', $this->data); // Render the view
    }

    public function fetchBrandData()
    {
        $result = array('data' => array());

        $data = $this->model_brands->getBrandData();
        foreach ($data as $key => $value) {

            // Buttons for actions
            $buttons = '';
            if (in_array('viewBrand', $this->permission)) {
                $buttons .= '<button type="button" class="btn btn-default" onclick="editBrand(' . $value['id'] . ')" data-toggle="modal" data-target="#editBrandModal"><i class="fa fa-pencil"></i></button>';
            }
            if (in_array('deleteBrand', $this->permission)) {
                $buttons .= ' <button type="button" class="btn btn-default" onclick="removeBrand(' . $value['id'] . ')" data-toggle="modal" data-target="#removeBrandModal"><i class="fa fa-trash"></i></button>';
            }

            // Status label based on active status
            $status = ($value['active'] == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-warning">Inactive</span>';

            $result['data'][$key] = array(
                $value['name'],
                $status,
                $buttons
            );
        }

        echo json_encode($result); // Return JSON response for DataTables
    }

    public function fetchBrandDataById($id)
    {
        if ($id) {
            $data = $this->model_brands->getBrandData($id); // Fetch brand data by ID
            echo json_encode($data); // Return JSON response
        }

        return false;
    }

    public function create()
    {
        if (!in_array('createBrand', $this->permission)) {
            redirect('dashboard', 'refresh'); // Redirect to dashboard if user lacks permission
        }

        $response = array();

        // Form validation rules
        $this->form_validation->set_rules('brand_name', 'Brand name', 'trim|required');
        $this->form_validation->set_rules('active', 'Active', 'trim|required');

        $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

        if ($this->form_validation->run() == TRUE) {
            $data = array(
                'name' => $this->input->post('brand_name'),
                'active' => $this->input->post('active'),
            );

            $create = $this->model_brands->create($data); // Create brand
            if ($create == true) {
                $response['success'] = true;
                $response['messages'] = 'Successfully created';
            } else {
                $response['success'] = false;
                $response['messages'] = 'Error in the database while creating the brand information';
            }
        } else {
            $response['success'] = false;
            foreach ($_POST as $key => $value) {
                $response['messages'][$key] = form_error($key);
            }
        }

        echo json_encode($response); // Return JSON response
    }

    public function update($id)
    {
        if (!in_array('updateBrand', $this->permission)) {
            redirect('dashboard', 'refresh'); // Redirect to dashboard if user lacks permission
        }

        $response = array();

        if ($id) {
            $this->form_validation->set_rules('edit_brand_name', 'Brand name', 'trim|required');
            $this->form_validation->set_rules('edit_active', 'Active', 'trim|required');

            $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

            if ($this->form_validation->run() == TRUE) {
                $data = array(
                    'name' => $this->input->post('edit_brand_name'),
                    'active' => $this->input->post('edit_active'),
                );

                $update = $this->model_brands->update($data, $id); // Update brand
                if ($update == true) {
                    $response['success'] = true;
                    $response['messages'] = 'Successfully updated';
                } else {
                    $response['success'] = false;
                    $response['messages'] = 'Error in the database while updating the brand information';
                }
            } else {
                $response['success'] = false;
                foreach ($_POST as $key => $value) {
                    $response['messages'][$key] = form_error($key);
                }
            }
        } else {
            $response['success'] = false;
            $response['messages'] = 'Error: please refresh the page again!';
        }

        echo json_encode($response); // Return JSON response
    }

    public function remove()
    {
        if (!in_array('deleteBrand', $this->permission)) {
            redirect('dashboard', 'refresh'); // Redirect to dashboard if user lacks permission
        }

        $brand_id = $this->input->post('brand_id');
        $response = array();
        if ($brand_id) {
            $delete = $this->model_brands->remove($brand_id); // Remove brand

            if ($delete == true) {
                $response['success'] = true;
                $response['messages'] = "Successfully removed";
            } else {
                $response['success'] = false;
                $response['messages'] = "Error in the database while removing the brand information";
            }
        } else {
            $response['success'] = false;
            $response['messages'] = "Refresh the page and try again";
        }

        echo json_encode($response); // Return JSON response
    }
}


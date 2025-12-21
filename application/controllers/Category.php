<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->not_logged_in();

        $this->data['page_title'] = 'Category';

        $this->load->model('model_types');
        $this->load->model('model_products');
    }

    public function index()
    {
        if (!in_array('viewCategory', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        $this->render_template('category/index', $this->data);
    }

    public function fetchCategoryDataById($id)
    {
        if ($id) {
            $data = $this->model_types->getTypeData($id);
            echo json_encode($data);
        }

        return false;
    }

    public function fetchCategoryData() {
        $result = array('data' => array());

        $data = $this->model_types->getTypeData();

        foreach ($data as $key => $value) {
            // button
            $buttons = '';

            if(in_array('updateCategory', $this->permission)) {
                $buttons .= '<button type="button" class="btn btn-default" onclick="editFunc('.$value['id'].')" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil"></i></button>';
            }

            if(in_array('deleteCategory', $this->permission)) {
                $buttons .= ' <button type="button" class="btn btn-default" onclick="removeFunc('.$value['id'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';
            }

            $status = ($value['active'] == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-warning">Inactive</span>';

            $category_link = '<a href="'.base_url('category/view/'.$value['id']).'">'.$value['name'].'</a>';

            $result['data'][$key] = array(
                $category_link,
                $status,
                $buttons
            );
        } // /foreach

        echo json_encode($result);
    }

    public function view($category_id)
    {
        if (!in_array('viewCategory', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        if ($category_id) {
            $category_data = $this->model_types->getTypeData($category_id);
            $products_data = $this->model_products->getProductDataByCategory($category_id);

            $this->data['category_name'] = $category_data['name'];
            $this->data['products'] = $products_data;

            $this->render_template('category/view', $this->data);
        }
    }

    public function create()
    {
        if (!in_array('createCategory', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        $response = array();

        $this->form_validation->set_rules('category_name', 'Category name', 'trim|required');
        $this->form_validation->set_rules('active', 'Active', 'trim|required');

        $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

        if ($this->form_validation->run() == TRUE) {
            $data = array(
                'name' => $this->input->post('category_name'),
                'active' => $this->input->post('active'),
            );

            $create = $this->model_types->create($data);
            if ($create == true) {
                $response['success'] = true;
                $response['messages'] = 'Successfully created';
            } else {
                $response['success'] = false;
                $response['messages'] = 'Error in the database while creating the category information';
            }
        } else {
            $response['success'] = false;
            foreach ($_POST as $key => $value) {
                $response['messages'][$key] = form_error($key);
            }
        }

        echo json_encode($response);
    }

    public function update($id)
    {
        if (!in_array('updateCategory', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        $response = array();

        if ($id) {
            $this->form_validation->set_rules('edit_category_name', 'Category name', 'trim|required');
            $this->form_validation->set_rules('edit_active', 'Active', 'trim|required');

            $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

            if ($this->form_validation->run() == TRUE) {
                $data = array(
                    'name' => $this->input->post('edit_category_name'),
                    'active' => $this->input->post('edit_active'),
                );

                $update = $this->model_types->update($data, $id);
                if ($update == true) {
                    $response['success'] = true;
                    $response['messages'] = 'Successfully updated';
                } else {
                    $response['success'] = false;
                    $response['messages'] = 'Error in the database while updating the category information';
                }
            } else {
                $response['success'] = false;
                foreach ($_POST as $key => $value) {
                    $response['messages'][$key] = form_error($key);
                }
            }
        } else {
            $response['success'] = false;
            $response['messages'] = 'Error please refresh the page again!!';
        }

        echo json_encode($response);
    }

    public function remove()
    {
        if (!in_array('deleteCategory', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        $category_id = $this->input->post('category_id');

        $response = array();
        if ($category_id) {
            $delete = $this->model_types->remove($category_id);
            if ($delete == true) {
                $response['success'] = true;
                $response['messages'] = "Successfully removed";
            } else {
                $response['success'] = false;
                $response['messages'] = "Error in the database while removing the category information";
            }
        } else {
            $response['success'] = false;
            $response['messages'] = "Refresh the page again!!";
        }

        echo json_encode($response);
    }
}
?>


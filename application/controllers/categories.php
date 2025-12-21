<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->not_logged_in();
        $this->data['page_title'] = 'Categories';
        $this->load->model('model_category');
        $this->load->model('model_products');
    }

    public function index() {
        $this->data['categories'] = $this->model_category->getActiveCategoryData();
        $this->render_template('categories/index', $this->data);
    }

    public function view($category) {
        $this->data['products'] = $this->model_products->getProductsByCategory($category);
        $this->data['category_name'] = $category;
        $this->render_template('categories/view', $this->data);
    }

    public function create() {
        $this->form_validation->set_rules('category_name', 'Category name', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            $data = array(
                'name' => $this->input->post('category_name'),
                'active' => 1
            );

            $create = $this->model_category->create($data);
            if ($create == true) {
                $this->session->set_flashdata('success', 'Successfully created');
                redirect('categories/', 'refresh');
            } else {
                $this->session->set_flashdata('errors', 'Error occurred!!');
                redirect('categories/create', 'refresh');
            }
        } else {
            $this->render_template('categories/create', $this->data);
        }
    }
}
?>


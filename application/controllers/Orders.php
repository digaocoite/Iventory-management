<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->not_logged_in();
        $this->data['page_title'] = 'Orders';
        $this->load->model('model_orders');
        $this->load->model('model_products');
        $this->load->model('model_company');
    }

    public function index()
    {
        redirect('orders/manage');
    }

    public function create()
    {
        if(!in_array('createOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        $this->form_validation->set_rules('customer_name', 'Customer name', 'trim|required');
        $this->form_validation->set_rules('gross_amount', 'Gross Amount', 'trim|required');
        $this->form_validation->set_rules('net_amount', 'Net Amount', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            $order_id = $this->model_orders->create();

            if($order_id) {
                $this->session->set_flashdata('success', 'Successfully created');
                redirect('orders/manage', 'refresh');
            } else {
                $this->session->set_flashdata('errors', 'Error occurred!!');
                redirect('orders/create', 'refresh');
            }
        } else {
            $company = $this->model_company->getCompanyData(1);
            $this->data['is_service_enabled'] = ($company['service_charge_value'] > 0) ? true : false;
            $this->data['is_vat_enabled'] = ($company['vat_charge_value'] > 0) ? true : false;
            $this->data['products'] = $this->model_products->getActiveProductData();
            $this->render_template('orders/create_order', $this->data);
        }
    }

    public function manage()
    {
        if(!in_array('viewOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        $this->data['page_title'] = 'Manage Orders';
        $this->data['orders'] = $this->model_orders->getOrdersData();
        $this->render_template('orders/manage_orders', $this->data);
    }
}


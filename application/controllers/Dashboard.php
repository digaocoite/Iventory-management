<?php

class Dashboard extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->not_logged_in();
        $this->data['page_title'] = 'Dashboard';

        $this->load->model('model_products');
        $this->load->model('model_orders');
        $this->load->model('model_users');
        $this->load->model('model_stores');
        $this->load->model('model_types');
    }

    public function index()
    {
        $this->data['total_products'] = $this->model_products->countTotalProducts();
        $this->data['total_paid_orders'] = $this->model_orders->countTotalPaidOrders();
        $this->data['total_users'] = $this->model_users->countTotalUsers();
        $this->data['total_stores'] = $this->model_stores->countTotalStores();
        $this->data['total_types'] = $this->model_types->countTotalTypes();

        $this->render_template('dashboard', $this->data);
    }
}


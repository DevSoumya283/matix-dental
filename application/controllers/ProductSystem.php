<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProductSystem extends MW_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Productdata_model');
        $this->load->library('ion_auth');

    }

    public function index() {
        $this->load->view('product_system');
    }

    public function save_excel_data() {
        $data = $this->input->post('data');
        $this->Productdata_model->save_excel_rows($data);
        echo 'success';
    }

    public function get_products() {
        $products = $this->Productdata_model->get_all_products();

        if (empty($products)) {
            // Fallback: get field names (columns) from table
            $fields = $this->db->list_fields('products');
            echo json_encode(['headers_only' => $fields]);
        } else {
            echo json_encode($products);
        }
    }


    public function export_all_products() {
        $this->Productdata_model->export_all_products();
    }

    public function export_single_product($id) {
        $this->load->model('Productdata_model');
        $product = $this->Productdata_model->get_product_by_id($id);

        if (!$product) show_404();

        // Set headers for Excel download
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=product_{$id}.xlsx");
        header("Pragma: no-cache");
        header("Expires: 0");

        // Load view with one row wrapped in array
        $this->load->view('export', ['products' => [$product]]);
}



    public function add_product_options() {
        $product_id = $this->input->post('product_id');
        $columns = explode(',', $this->input->post('columns'));
        $this->Productdata_model->add_options($product_id, $columns);
        echo 'added';
    }
}

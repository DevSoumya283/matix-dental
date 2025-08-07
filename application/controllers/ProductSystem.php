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

    // public function save_excel_data() {
    //     $data = $this->input->post('data');
    //     $this->Productdata_model->save_excel_rows($data);
    //     echo 'success';
    // }



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

    public function get_product_options_json()
    {
        // Handle range param
        $range = $this->input->get('range');
        if ($range && strpos($range, '-') !== false) {
            list($start, $end) = explode('-', $range);
            $offset = is_numeric($start) ? (int)$start : 0;
            $limit = (is_numeric($end) ? (int)$end : 10) - $offset;
        } else {
            $offset = 0;
            $limit = 100; // or fetch all
        }

        $products = $this->Products_model->get_all($limit, $offset);

        $option_rows = $this->db
            ->select('product_id, name')
            ->from('product_options')
            ->where_in('product_id', array_column($products, 'id'))
            ->get()
            ->result();

        $options_map = [];
        foreach ($option_rows as $opt) {
            $options_map[$opt->product_id][] = trim($opt->name);
        }

        $result = [];
        foreach ($products as $product) {
            $result[] = [
                'id' => $product->id,
                'mpn' => $product->mpn,
                'name' => $product->name,
                'options' => isset($options_map[$product->id]) ? implode(',', $options_map[$product->id]) : '',
            ];
        }

        echo json_encode($result);
    }

    public function update_session_row()
    {
        $rowIndex = $this->input->post('row_index');
        $rowData = json_decode($this->input->post('row_data'), true);

        if (!is_array($rowData)) {
            show_error("Invalid row data.");
        }

        $sessionData = $this->session->userdata('upload_excel_data') ?? [];

        $sessionData[$rowIndex] = $rowData;

        $this->session->set_userdata('upload_excel_data', $sessionData);

        echo json_encode(['status' => 'success']);
    }


    // price tab

    public function get_price_data()
    {
        $this->load->database();

        // Get all columns from skus and products except id, created_at, updated_at
        $query = $this->db->query("
            SELECT 
                skus.product_id AS parent_product_id,
                skus.sku_code AS SKU, 
                products.mpn,
                skus.name AS Product_Name,
                skus.price,
                skus.retail_price,
                products.base_price,
                skus.stock_quantity,
                skus.image,
                skus.status,

                
                products.description,
                products.brand,
                products.category_id,
                products.item_code,
                products.active


            FROM skus
            LEFT JOIN products ON skus.product_id = products.id
        ");

        $skuData = $query->result_array();

        // Get all product options and values
        $optionQuery = $this->db->query("
            SELECT pov.product_id, po.name AS option_name, pov.value
            FROM product_option_values pov
            JOIN product_options po ON pov.option_id = po.id
        ");

        $optionRows = $optionQuery->result_array();

        // Group options by product_id
        $optionMap = [];
        foreach ($optionRows as $row) {
            $pid = $row['product_id'];
            $optionMap[$pid][$row['option_name']] = $row['value'];
        }

        // Merge SKU + Product with Options
        foreach ($skuData as &$row) {
            $pid = $row['parent_product_id'];
            if (isset($optionMap[$pid])) {
                foreach ($optionMap[$pid] as $option => $value) {
                    $row[$option] = $value;
                }
            }
        }

        echo json_encode($skuData);
    }



}

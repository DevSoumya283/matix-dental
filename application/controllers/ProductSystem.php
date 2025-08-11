<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProductSystem extends MW_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Productdata_model');
        $this->load->library('ion_auth');
    }

    public function index()
    {
        $this->load->view('product_system');
    }

    // public function save_excel_data() {
    //     $data = $this->input->post('data');
    //     $this->Productdata_model->save_excel_rows($data);
    //     echo 'success';
    // }



    public function get_products()
    {
        $products = $this->Productdata_model->get_all_products();

        if (empty($products)) {
            // Fallback: get field names (columns) from table
            $fields = $this->db->list_fields('products');
            echo json_encode(['headers_only' => $fields]);
        } else {
            echo json_encode($products);
        }
    }


    public function export_all_products()
    {
        $this->Productdata_model->export_all_products();
    }

    public function export_single_product($id)
    {
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



    public function add_product_options()
    {
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

        // Step 1: Get base SKU + Product data
        $query = $this->db->query("
        SELECT 
            skus.id AS sku_id,
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

        // Step 2: Get all options for all products
        $optionNamesQuery = $this->db->query("
        SELECT id, product_id, name FROM product_options
    ");
        $allProductOptions = $optionNamesQuery->result_array();

        // Group option names by product_id
        $productOptionNames = [];
        foreach ($allProductOptions as $opt) {
            $productOptionNames[$opt['product_id']][] = $opt['name'];
        }

        // Step 3: Get all SKU option values
        $skuOptionsQuery = $this->db->query("
        SELECT 
            sov.sku_id,
            pov.id AS value_id,
            pov.product_id,
            po.name AS option_name,
            pov.value
        FROM sku_option_values sov
        JOIN product_option_values pov ON sov.value_id = pov.id
        JOIN product_options po ON pov.option_id = po.id
    ");
        $skuOptionRows = $skuOptionsQuery->result_array();

        // Group options by sku_id
        $skuOptionMap = [];
        foreach ($skuOptionRows as $row) {
            $skuOptionMap[$row['sku_id']][$row['option_name']] = $row['value'];
        }

        // Step 4: Merge into final SKU data with only relevant options per product
        foreach ($skuData as &$sku) {
            $sku_id = $sku['sku_id'];
            $product_id = $sku['parent_product_id'];

            // Get relevant options for this product_id
            $optionNames = isset($productOptionNames[$product_id]) ? $productOptionNames[$product_id] : [];

            foreach ($optionNames as $optionName) {
                $sku[$optionName] = isset($skuOptionMap[$sku_id][$optionName]) ? $skuOptionMap[$sku_id][$optionName] : '';
            }
        }

        echo json_encode($skuData);
    }
}

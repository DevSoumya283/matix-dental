<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/third_party/spout/src/Spout/Autoloader/autoload.php';
use Box \ Spout \ Reader \ ReaderFactory;
use Box \ Spout \ Writer \ WriterFactory;
use Box \ Spout \ Common \ Type;

class ProductsUpload extends MW_Controller {

    function __construct() {

        parent::__construct();
        $this->load->library('elasticsearch');
        $this->load->helper('MY_privilege_helper');
        $this->load->helper('MY_support_helper');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->model('Images_model');
        $this->load->model('Order_model');
        $this->load->model('Organization_groups_model');
        $this->load->model('Organization_model');
        $this->load->model('Products_model');
        $this->load->model('Product_answer_model');
        $this->load->model('Product_pricing_model');
        $this->load->model('Product_question_model');
        $this->load->model('Prepopulated_list_model');
        $this->load->model('Prepopulated_product_model');
        $this->load->model('Product_custom_field_model');
        $this->load->model('BuyingClub_model');
        $this->load->model('Role_model');
        $this->load->model('User_model');
        $this->load->library('auth');
        $this->load->model('Vendor_model');
        $this->load->model('Vendor_groups_model');
        $this->load->library('email'); // load email library
        $this->load->helper('my_email_helper');


        $this->load->library('session');
    }

            // 01-08-25
    public function export()
    {
        set_time_limit(0);
        ini_set("memory_limit", "12288M");

        // Handle range param
        $range = $this->input->post('range');
        if ($range && strpos($range, '-') !== false) {
            list($start, $end) = explode('-', $range);
            $offset = is_numeric($start) ? (int)$start : 0;
            $limit = (is_numeric($end) ? (int)$end : 10) - $offset;
        } else {
            // Default to first 10 records
            $offset = 0;
            $limit = 10;
        }

        // Final fallback protection
        if ($limit <= 0) {
            $limit = 10;
        }

        // Header definition
        $headerRow = [
            'id', 'matix_id', 'mpn', 'item_code', 'name','description', 'extended_description', 'keywords',
            'manufacturer', 'shipping_restrictions', 'brand','license_required', 'category_id', 'base_price','active'
        ];

        // Prepare file
        $random_name = rand(1, 10000000000);
        $filename = $random_name . '.xlsx';
        $uploadPath = FCPATH . 'assets/uploads/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0775, true);
        }

        $file_path = $uploadPath . $filename;

        // Use OpenSpout writer (assumed)
        $writer = WriterFactory::create(Type::XLSX);
        $writer->openToFile($file_path);
        $writer->addRow($headerRow);

        // Fetch products
        $products = $this->Products_model->get_all($limit, $offset);

        if (!empty($products)) {
            $mpns = array_filter(array_column($products, 'id'));
            $pricing_map = $this->Products_model->get_prices_by_mpn_array($mpns);

            foreach ($products as $product) {
                $price = isset($pricing_map[$product->id]) ? $pricing_map[$product->id]['price'] : '';
                $retail_price = isset($pricing_map[$product->id]) ? $pricing_map[$product->id]['retail_price'] : '';

                $products_data = [
                    $product->id ?? '',
                    $product->matix_id ?? '',
                    $product->mpn ?? '',
                    $product->item_code ?? '',
                    $product->name ?? '',
                    strip_tags($product->description ?? ''),
                    strip_tags($product->extended_description ?? ''),
                    $product->keywords ?? '',
                    $product->manufacturer ?? '',
                    $product->shipping_restrictions ?? '',
                    $product->brand ?? '',
                    $product->license_required ?? '',
                    $product->category_id ?? '',
                    $product->base_price ?? '',
                    $product->active ?? ''
                ];

                $writer->addRow($products_data);
            }
        }

        $writer->close();

        // Trigger download
        header('Content-Description: File Transfer');
        header('Content-Type: application/force-download');
        header("Content-Disposition: attachment; filename=\"" . basename($filename) . "\";");
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));
        ob_clean();
        flush();
        readfile($file_path);
        exit;
    }
    private function getOrCreateOption($product_id, $name)
    {
        $option = $this->db->get_where('product_options', [
            'product_id' => $product_id,
            'name' => $name
        ])->row();

        if (!$option) {
            $this->db->insert('product_options', [
                'product_id' => $product_id,
                'name'       => $name,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            return $this->db->insert_id();
        }

        return $option->id;
    }

    private function getOrCreateOptionValue($option_id, $value)
    {
        $row = $this->db->get_where('product_option_values', [
            'option_id' => $option_id,
            'value'     => $value
        ])->row();

        if (!$row) {
            $this->db->insert('product_option_values', [
                'option_id'   => $option_id,
                'value'       => $value,
                'created_at'  => date('Y-m-d H:i:s')
            ]);
            return $this->db->insert_id();
        }

        return $row->id;
    }

    private function linkSkuOptionValues($sku_id, $value_ids = [])
    {
        $insert_batch = [];
        foreach ($value_ids as $value_id) {
            $insert_batch[] = [
                'sku_id'   => $sku_id,
                'value_id' => $value_id
            ];
        }
        if (!empty($insert_batch)) {
            $this->db->insert_batch('sku_option_values', $insert_batch);
        }
    }

    public function save_data()
    {
        $excel_data = $this->input->post('excel_data');
        // echo"<pre>";print_r($excel_data); echo"</pre>";die('ll');
        $file_name = $this->input->post('file_name');
        $vendor_id = '8';
        $elasticsearch_enabled = true;

        if (!$excel_data) {
            echo json_encode(['status' => 'error', 'message' => 'No data to save']);
            return;
        }

        $decoded_data = json_decode($excel_data, true);

        if ($decoded_data === null) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid JSON format']);
            return;
        }

        $empty_rows = [];
        $new_product_array = [];

        foreach ($decoded_data as $i => $row) {
            if ($i === 0) continue;

            $mpn = $row[2];
            if (empty($mpn)) {
                $empty_rows[] = $i;
                continue;
            }

            $vendors_product_id = $row[4];
            $matix_id = implode("-", [$mpn, $vendors_product_id]);

            $existing_product = $this->Products_model->select('id', 'matix_id')->get_by(['mpn' => $mpn]);
            $matrix_id_value = $existing_product ? $existing_product->id : 'p-' . time();

            $category_id = $row[12];
            $c_id = explode(",", str_replace('"', '', $category_id));
            $categories_list = [];

            foreach ($c_id as $cid) {
                $cid = trim($cid);
                if ($cid !== "") {
                    $query = 'SELECT t1.id as lev1_id, t2.id as lev2_id, t3.id as lev3_id, t4.id as lev4_id, t5.id as lev5_id
                            FROM categories AS t1
                            LEFT JOIN categories AS t2 ON t2.id = t1.parent_id
                            LEFT JOIN categories AS t3 ON t3.id = t2.parent_id
                            LEFT JOIN categories AS t4 ON t4.id = t3.parent_id
                            LEFT JOIN categories AS t5 ON t5.id = t4.parent_id
                            WHERE t1.id = ' . $cid;

                    $output = $this->db->query($query)->result();

                    if (!empty($output)) {
                        foreach (['lev1_id', 'lev2_id', 'lev3_id', 'lev4_id', 'lev5_id'] as $lev) {
                            if (!empty($output[0]->$lev)) {
                                $categories_list[] = $output[0]->$lev;
                            }
                        }
                    }
                }
            }

            $categories_list = array_unique($categories_list);
            $categories = !empty($categories_list) ? '"' . implode('","', $categories_list) . '"' : null;

            $product_data = [
                'item_code' => $row[3],
                'name' => $row[4],
                'description' => $row[5],
                'extended_description' => $row[6],
                'keywords' => $row[7],
                'manufacturer' => $row[8],
                'shipping_restrictions' => $row[9],
                'brand' => $row[10],
                'license_required' => ucfirst(strtolower($row[11])),
                'category_id' => $categories,
                'base_price' => $row[13],
                'active' => $row[14],
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Only set these on insert
            if (!$existing_product) {
                $product_data['matix_id'] = $matrix_id_value;
                $product_data['mpn'] = $mpn;
                $product_data['created_at'] = date('Y-m-d H:i:s');
                $new_product_array[] = $product_data;

                if (count($new_product_array) == 100) {
                    $this->db->insert_batch('products', $new_product_array);
                    $first_insert_id = $this->db->insert_id();
                    for ($j = 0; $j < count($new_product_array); $j++) {
                        $new_id = $first_insert_id + $j;
                        if ($elasticsearch_enabled) {
                            $this->elasticsearch->add("products", $new_id, $new_product_array[$j]);
                        }
                    }
                    $new_product_array = [];
                }
            } else {
                // Update existing product — don't touch mpn or matix_id
                $this->db->update('products', $product_data, ['id' => $existing_product->id]);

                if ($elasticsearch_enabled) {
                    $this->elasticsearch->add("products", $existing_product->id, $product_data);
                }
            }
        }

        // Final insert batch
        if (!empty($new_product_array)) {
            $this->db->insert_batch('products', $new_product_array);
            $first_insert_id = $this->db->insert_id();
            for ($j = 0; $j < count($new_product_array); $j++) {
                $new_id = $first_insert_id + $j;
                if ($elasticsearch_enabled) {
                    $this->elasticsearch->add("products", $new_id, $new_product_array[$j]);
                }
            }
        }

        $response = ['status' => 'success', 'message' => 'Products uploaded successfully.'];
        if (count($empty_rows) > 0) {
            $response['warning'] = 'Some rows were skipped because MPNs were blank.';
            $response['skipped_rows'] = $empty_rows;
        }

        echo json_encode($response);
    }

    // 01-08-25
    public function export_option_column()
    {
        set_time_limit(0);
        ini_set("memory_limit", "12288M");

        // Handle range param
        $range = $this->input->post('range');
        if ($range && strpos($range, '-') !== false) {
            list($start, $end) = explode('-', $range);
            $offset = is_numeric($start) ? (int)$start : 0;
            $limit = (is_numeric($end) ? (int)$end : 10) - $offset;
        } else {
            $offset = 0;
            $limit = 10;
        }

        if ($limit <= 0) {
            $limit = 10;
        }

        $headerRow = [
            'id', 'matix_id', 'mpn',  'name','options'
        ];

        $random_name = rand(1, 10000000000);
        $filename = $random_name . '.xlsx';
        $uploadPath = FCPATH . 'assets/uploads/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0775, true);
        }

        $file_path = $uploadPath . $filename;

        $writer = WriterFactory::create(Type::XLSX);
        $writer->openToFile($file_path);
        $writer->addRow($headerRow);

        $products = $this->Products_model->get_all($limit, $offset);

        // Build map: product_id => [option1, option2, ...]
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

        // Pricing map (if applicable)
        $mpns = array_filter(array_column($products, 'id'));
        $pricing_map = $this->Products_model->get_prices_by_mpn_array($mpns);

        foreach ($products as $product) {
            $price = $pricing_map[$product->id]['price'] ?? '';
            $retail_price = $pricing_map[$product->id]['retail_price'] ?? '';

            $options_str = isset($options_map[$product->id]) ? implode(',', $options_map[$product->id]) : '';

            $products_data = [
                $product->id ?? '',
                $product->matix_id ?? '',
                $product->mpn ?? '',                
                $product->name ?? '',                
                $options_str
            ];

            $writer->addRow($products_data);
        }

        $writer->close();

        // Download
        header('Content-Description: File Transfer');
        header('Content-Type: application/force-download');
        header("Content-Disposition: attachment; filename=\"" . basename($filename) . "\";");
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));
        ob_clean();
        flush();
        readfile($file_path);
        exit;
    }
    public function insert_options()
    {
        $excel_data = $this->input->post('excel_data');
       
        if (!$excel_data) {
            echo json_encode(['status' => 'error', 'message' => 'No data to save']);
            return;
        }

        $decoded_data = json_decode($excel_data, true);
        if ($decoded_data === null) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid JSON format']);
            return;
        }

        $empty_rows = [];
        $new_product_array = [];

        foreach ($decoded_data as $i => $row) {
            if ($i === 0) continue;

            $mpn = $row[2];
            if (empty($mpn)) {
                $empty_rows[] = $i;
                continue;
            }

            $vendors_product_id = $row[4];
            $matix_id = implode("-", [$mpn, $vendors_product_id]);

            $existing_product = $this->Products_model->select('id', 'matix_id')->get_by(['mpn' => $mpn]);
            $matrix_id_value = $existing_product ? $existing_product->id : 'p-' . time();

            $category_id = $row[12];
            $c_id = explode(",", str_replace('"', '', $category_id));
            $categories_list = [];

            foreach ($c_id as $cid) {
                $cid = trim($cid);
                if ($cid !== "") {
                    $query = 'SELECT t1.id as lev1_id, t2.id as lev2_id, t3.id as lev3_id, t4.id as lev4_id, t5.id as lev5_id
                            FROM categories AS t1
                            LEFT JOIN categories AS t2 ON t2.id = t1.parent_id
                            LEFT JOIN categories AS t3 ON t3.id = t2.parent_id
                            LEFT JOIN categories AS t4 ON t4.id = t3.parent_id
                            LEFT JOIN categories AS t5 ON t5.id = t4.parent_id
                            WHERE t1.id = ' . $cid;

                    $output = $this->db->query($query)->result();

                    if (!empty($output)) {
                        foreach (['lev1_id', 'lev2_id', 'lev3_id', 'lev4_id', 'lev5_id'] as $lev) {
                            if (!empty($output[0]->$lev)) {
                                $categories_list[] = $output[0]->$lev;
                            }
                        }
                    }
                }
            }

            $categories_list = array_unique($categories_list);
            $categories = !empty($categories_list) ? '"' . implode('","', $categories_list) . '"' : null;

            $product_data = [
                'item_code' => $row[3],
                'name' => $row[4],
                'description' => $row[5],
                'extended_description' => $row[6],
                'keywords' => $row[7],
                'manufacturer' => $row[8],
                'shipping_restrictions' => $row[9],
                'brand' => $row[10],
                'license_required' => ucfirst(strtolower($row[11])),
                'category_id' => $categories,
                'base_price' => $row[13],
                'active' => $row[14],
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Only set these on insert
            if (!$existing_product) {
                $product_data['matix_id'] = $matrix_id_value;
                $product_data['mpn'] = $mpn;
                $product_data['created_at'] = date('Y-m-d H:i:s');
                $new_product_array[] = $product_data;

                if (count($new_product_array) == 100) {
                    $this->db->insert_batch('products', $new_product_array);
                    $first_insert_id = $this->db->insert_id();
                    for ($j = 0; $j < count($new_product_array); $j++) {
                        $new_id = $first_insert_id + $j;
                        if ($elasticsearch_enabled) {
                            $this->elasticsearch->add("products", $new_id, $new_product_array[$j]);
                        }
                    }
                    $new_product_array = [];
                }
            } else {
                // Update existing product — don't touch mpn or matix_id
                $this->db->update('products', $product_data, ['id' => $existing_product->id]);

                if ($elasticsearch_enabled) {
                    $this->elasticsearch->add("products", $existing_product->id, $product_data);
                }
            }
        }

        // Final insert batch
        if (!empty($new_product_array)) {
            $this->db->insert_batch('products', $new_product_array);
            $first_insert_id = $this->db->insert_id();
            for ($j = 0; $j < count($new_product_array); $j++) {
                $new_id = $first_insert_id + $j;
                if ($elasticsearch_enabled) {
                    $this->elasticsearch->add("products", $new_id, $new_product_array[$j]);
                }
            }
        }

        $response = ['status' => 'success', 'message' => 'Products uploaded successfully.'];
        if (count($empty_rows) > 0) {
            $response['warning'] = 'Some rows were skipped because MPNs were blank.';
            $response['skipped_rows'] = $empty_rows;
        }

        echo json_encode($response);
    }
    private function guessOptionName($index)
{
    $map = [
        15 => 'weight',
        16 => 'size',
        17 => 'material',
        18 => 'color',
        19 => 'flavor',
        20 => 'shade',
        // extend as per your Excel format
    ];

    return $map[$index] ?? 'option_' . $index;
}

}
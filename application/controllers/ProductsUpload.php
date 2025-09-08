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
        $this->load->helper('download');

        $this->load->library('upload'); 
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
            'id','mpn', 'item_code', 'name','description', 'extended_description', 'keywords',
            'manufacturer', 'shipping_restrictions', 'brand','license_required', 'category_id', 'base_price','active'
        ];

        // Prepare file
        $random_name = rand(1, 100000);
        $filename = 'base-products-' . $random_name . '.csv';
        $uploadPath = FCPATH . 'assets/uploads/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0775, true);
        }

        $file_path = $uploadPath . $filename;
        $file = fopen($file_path, 'w');

        fputs($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($file, $headerRow);

        $products = $this->Products_model->get_all($limit, $offset);

        if (!empty($products)) {
            $mpns = array_filter(array_column($products, 'id'));
            $pricing_map = $this->Products_model->get_prices_by_mpn_array($mpns);

            foreach ($products as $product) {
                $price = isset($pricing_map[$product->id]) ? $pricing_map[$product->id]['price'] : '';
                $retail_price = isset($pricing_map[$product->id]) ? $pricing_map[$product->id]['retail_price'] : '';

                $products_data = [
                    isset($product->id) ? $product->id : '',
                    isset($product->mpn) ? $product->mpn : '',
                    isset($product->item_code) ? $product->item_code : '',
                    isset($product->name) ? $product->name : '',
                    isset($product->description) ? strip_tags($product->description) : '',
                    isset($product->extended_description) ? strip_tags($product->extended_description) : '',
                    isset($product->keywords) ? $product->keywords : '',
                    isset($product->manufacturer) ? $product->manufacturer : '',
                    isset($product->shipping_restrictions) ? $product->shipping_restrictions : '',
                    isset($product->brand) ? $product->brand : '',
                    isset($product->license_required) ? $product->license_required : '',
                    isset($product->category_id) ? $product->category_id : '',
                    isset($product->base_price) ? $product->base_price : '',
                    isset($product->active) ? $product->active : ''
                ];


                fputcsv($file, $products_data);

            }
        }

           fclose($file);


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

            $random_number =  rand(1111111, 9999999);
            $product_data = [
                'item_code' => $row[3],
                'matix_id' => 'p-'.$random_number,
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
            'id',  'mpn',  'name','options'
        ];

        $random_name = rand(1, 10000000000);
        $filename = $random_name . '_options.xlsx';
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
          $price = isset($pricing_map[$product->id]['price']) ? $pricing_map[$product->id]['price'] : '';
          $retail_price = isset($pricing_map[$product->id]['retail_price']) ? $pricing_map[$product->id]['retail_price'] : '';


            $options_str = isset($options_map[$product->id]) ? implode(',', $options_map[$product->id]) : '';

            $products_data = [
                isset($product->id) ? $product->id : '',
                isset($product->mpn) ? $product->mpn : '',
                isset($product->name) ? $product->name : '',
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
            echo json_encode(['status' => 'error', 'message' => 'No Excel data provided']);
            return;
        }

        $decoded_data = json_decode($excel_data, true);

        if (!$decoded_data || count($decoded_data) < 2) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid or empty Excel data']);
            return;
        }

        $headers = $decoded_data[0];
        $rows = array_slice($decoded_data, 1);

        $created = 0;
        $skipped = [];

        foreach ($rows as $i => $row) {
            $row_index = $i + 2;

            $row_data = array_combine($headers, $row);

          $mpn = isset($row_data['mpn']) ? trim($row_data['mpn']) : '';
          $product_id = isset($row_data['id']) ? trim($row_data['id']) : '';


            if (!$product_id || !$mpn) {
                $skipped[] = $row_index;
                continue;
            }

            $product = $this->db->get_where('products', ['id' => $product_id, 'mpn' => $mpn])->row();
            if (!$product) {
                $skipped[] = $row_index;
                error_log("Row $row_index skipped: No product found for ID: $product_id, MPN: $mpn");
                continue;
            }

            $option_string = isset($row_data['options']) ? $row_data['options'] : '';
            if (empty(trim($option_string))) continue;

            $options = explode(',', $option_string);
            $display_order = 1;

            foreach ($options as $opt) {
                $option_name = trim($opt);
                if (!$option_name) continue;

                $exists = $this->db->get_where('product_options', [
                    'product_id' => $product_id,
                    'name' => $option_name
                ])->row();

                if (!$exists) {
                    $this->db->insert('product_options', [
                        'product_id'    => $product_id,
                        'name'          => $option_name,
                        'display_order' => $display_order,
                        'created_at'    => date('Y-m-d H:i:s'),
                        'updated_at'    => date('Y-m-d H:i:s'),
                    ]);
                    $created++;
                }

                $display_order++;
            }
        }

        $message = "Options inserted successfully. Total: {$created}";
        if (!empty($skipped)) {
            $message .= ". Skipped rows: " . implode(', ', $skipped);
        }

        echo json_encode(['status' => 'success', 'message' => $message]);
    }

    private function array_to_csv_with_headers($headers, $data, $delimiter = ",", $newline = "\r\n")
    {
        $output = implode($delimiter, $headers) . $newline;

        foreach ($data as $row) {
            $row_data = [];
            foreach ($headers as $head) {
                    $row_data[] = '"' . str_replace('"', '""', isset($row[$head]) ? $row[$head] : '') . '"';
            }
            $output .= implode($delimiter, $row_data) . $newline;
        }

        return $output;
    }



    public function export_variant_csv($product_id)
    {
        $this->load->helper('download');

        // Fetch base product
        $product = $this->db->get_where('products', ['id' => $product_id])->row_array();
        if (!$product) {
            echo "Invalid Product ID"; return;
        }

        // Fetch options and option values
        $options = $this->db->get_where('product_options', ['product_id' => $product_id])->result_array();
        $option_names = array_column($options, 'name', 'id'); // [option_id => name]

        $option_value_map = []; // [option_id => [option_value_id => value]]
        foreach ($options as $opt) {
            $values = $this->db->get_where('product_option_values', ['option_id' => $opt['id']])->result_array();
            foreach ($values as $val) {
                $option_value_map[$opt['id']][$val['id']] = $val['value'];
            }
        }

        // Create consistent headers
       $headers = ['id', 'mpn', 'name', 'item_code', 'description', 'extended_description', 'keywords', 'manufacturer', 'shipping_restrictions', 
       'brand', 'license_required', 'category_id', 'base_price', 'price', 'retail_price', 'stocks', 'sku'];

        foreach ($option_names as $name) {
            $headers[] = ($name); // Color, Size, etc.
        }

        $export_data = [];

        // Fetch SKUs
        $skus = $this->db->get_where('skus', ['product_id' => $product_id])->result_array();

        if (!empty($skus)) {
            // Export SKUs with linked option values
            foreach ($skus as $sku) {
               $row = [
                    'id' => isset($product['id']) ? $product['id'] : '',
                    'mpn' => isset($product['mpn']) ? $product['mpn'] : '',
                    'name' => isset($product['name']) ? $product['name'] : '',
                    'item_code' => isset($product['item_code']) ? $product['item_code'] : '',
                    'description' => isset($product['description']) ? $product['description'] : '',
                    'extended_description' => isset($product['extended_description']) ? $product['extended_description'] : '',
                    'keywords' => isset($product['keywords']) ? $product['keywords'] : '',
                    'manufacturer' => isset($product['manufacturer']) ? $product['manufacturer'] : '',
                    'shipping_restrictions' => isset($product['shipping_restrictions']) ? $product['shipping_restrictions'] : '',
                    'brand' => isset($product['brand']) ? $product['brand'] : '',
                    'license_required' => isset($product['license_required']) ? $product['license_required'] : '',
                    'category_id' => isset($product['category_id']) ? $product['category_id'] : '',
                    'base_price' => isset($product['base_price']) ? $product['base_price'] : '',
                    'price' => isset($sku['price']) ? $sku['price'] : '',
                    'retail_price' => isset($sku['retail_price']) ? $sku['retail_price'] : '',
                    'stocks' => isset($sku['stock_quantity']) ? $sku['stock_quantity'] : '',
                    'sku' => isset($sku['sku']) ? $sku['sku'] : '',
                ];



                // Initialize option columns blank
                foreach ($option_names as $opt_id => $opt_name) {
                    $row[ucfirst($opt_name)] = '';
                }

                $sku_options = $this->db->get_where('sku_option_values', ['sku_id' => $sku['id']])->result_array();
                foreach ($sku_options as $sku_opt) {
                    $option_value_id = $sku_opt['option_value_id'];

                    $opt_val = $this->db->get_where('product_option_values', ['id' => $option_value_id])->row();
                    if ($opt_val) {
                        $option_id = $opt_val->option_id;
                        $option_name = ucfirst(isset($option_names[$option_id]) ? 'Option ' . $option_id: Null);
                        $row[$option_name] = $opt_val->value;
                    }
                }

                $export_data[] = $row;
            }
        } else {
            // No SKUs — create 1 blank row with options for user to fill
            $row = [
                'id' => isset($product['id']) ? $product['id'] : '',
                'mpn' => isset($product['mpn']) ? $product['mpn'] : '',
                'name' => isset($product['name']) ? $product['name'] : '',
                'item_code' => isset($product['item_code']) ? $product['item_code'] : '',
                'description' => isset($product['description']) ? $product['description'] : '',
                'extended_description' => isset($product['extended_description']) ? $product['extended_description'] : '',
                'keywords' => isset($product['keywords']) ? $product['keywords'] : '',
                'manufacturer' => isset($product['manufacturer']) ? $product['manufacturer'] : '',
                'shipping_restrictions' => isset($product['shipping_restrictions']) ? $product['shipping_restrictions'] : '',
                'brand' => isset($product['brand']) ? $product['brand'] : '',
                'license_required' => isset($product['license_required']) ? $product['license_required'] : '',
                'category_id' => isset($product['category_id']) ? $product['category_id'] : '',
                'base_price' => isset($product['base_price']) ? $product['base_price'] : '',
                'price' => isset($sku['price']) ? $sku['price'] : '',
                'retail_price' => isset($sku['retail_price']) ? $sku['retail_price'] : '',
                'stocks' => isset($sku['stock_quantity']) ? $sku['stock_quantity'] : '',
                'sku' => isset($sku['sku']) ? $sku['sku'] : '',
            ];


            foreach ($option_names as $opt_name) {
                $row[ucfirst($opt_name)] = '';
            }

            $export_data[] = $row;
        }

        // Format CSV
        $csv = $this->array_to_csv_with_headers($headers, $export_data);
        force_download($product['mpn'] . '_variants.csv', $csv);
    }


    public function insert_variants() 
    {
        $excel_data = $this->input->post('excel_data');
        if (!$excel_data) {
            echo json_encode(['status' => 'error', 'message' => 'No Excel data provided']);
            return;
        }

        $decoded_data = json_decode($excel_data, true);
        if (!$decoded_data || !is_array($decoded_data)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid Excel data']);
            return;
        }

        $batchSize = 100;
        $rows = array_chunk($decoded_data, $batchSize);
        $inserted = 0;
        $updated = 0;
        $skipped = 0;

        $this->db->trans_start(); // Start transaction

        $all_options = $this->db->get('product_options')->result_array();
        $option_map = [];
        foreach ($all_options as $opt) {
            $option_map[$opt['product_id']][$opt['name']] = $opt['id'];
        }

        $sku_inserts = [];
        $option_value_inserts = [];
        $sku_option_value_inserts = [];
        $child_product_inserts = [];

        foreach ($rows as $batch) {
            foreach ($batch as $row) {
                $mpn = trim($row['mpn']);
                $sku_code = trim($row['sku']);
                $product = $this->db->get_where('products', ['mpn' => $mpn])->row();
                if (!$product) {
                    log_message('error', "Skipped row due to missing product with mpn: $mpn");
                    $skipped++;
                    continue;
                }

                $parent_product_id = $product->id;

                // Insert new child product for each SKU
                $child_product_data = [
                    'parent_product_id'     => $parent_product_id,
                    'mpn'                   => $mpn,
                    'item_code'             => isset($row['item_code']) ? $row['item_code'] : '',
                    'description'           => isset($row['description']) ? $row['description'] : '',
                    'extended_description'  => isset($row['extended_description']) ? $row['extended_description'] : '',
                    'keywords'              => isset($row['keywords']) ? $row['keywords'] : '',
                    'manufacturer'          => isset($row['manufacturer']) ? $row['manufacturer'] : '',
                    'shipping_restrictions' => isset($row['shipping_restrictions']) ? $row['shipping_restrictions'] : '',
                    'brand'                 => isset($row['brand']) ? $row['brand'] : '',
                    'license_required'      => isset($row['license_required']) ? $row['license_required'] : '',
                    'category_id'           => isset($row['category_id']) ? $row['category_id'] : '',
                    'base_price'            => isset($row['base_price']) ? $row['base_price'] : '',
                    'created_at'            => date('Y-m-d H:i:s'),
                    'updated_at'            => date('Y-m-d H:i:s'),
                ];


                $child_product_inserts[] = $child_product_data;

                // Prepare SKU data (this will reference the existing parent product's ID)
                $sku_data = [
                    'product_id' => $parent_product_id, // Link the SKU to the parent product
                    'sku_code' => $sku_code,
                    'price'          => isset($row['price']) ? $row['price'] : 0,
                    'name'           => isset($row['name']) ? $row['name'] : null,
                    'retail_price'   => isset($row['retail_price']) ? $row['retail_price'] : 0,
                    'stock_quantity' => isset($row['stocks']) ? $row['stocks'] : 0,

                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];

                $sku_inserts[] = $sku_data;

                // Link options to the SKU
                foreach ($row as $key => $value) {
                    if (in_array($key, ['id', 'mpn', 'name', 'sku', 'price', 'retail_price', 'stocks']) || $value === '') continue;

                    if (isset($option_map[$parent_product_id][$key])) {
                        $option_id = $option_map[$parent_product_id][$key];
                    } else {
                        $skipped++;
                        continue;
                    }

                    $value_row = $this->db->get_where('product_option_values', ['option_id' => $option_id, 'value' => $value])->row();
                    if (!$value_row) {
                        $option_value_data = [
                            'product_id' => $parent_product_id,
                            'option_id' => $option_id,
                            'value' => $value,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        $this->db->insert('product_option_values', $option_value_data);
                        $option_value_id = $this->db->insert_id();
                        $option_value_inserts[] = $option_value_data;
                    } else {
                        $option_value_id = $value_row->id;
                    }

                    $sku_option_value_inserts[] = [
                        'sku_id' => null, // will update later after SKU insert
                        'value_id' => $option_value_id
                    ];
                }
            }
        }

            // Insert the child products in a batch
            // if (!empty($child_product_inserts)) {
            //     $this->db->insert_batch('products', $child_product_inserts);
            //     $inserted += count($child_product_inserts);

            //     // Get the first inserted product ID
            //     $first_inserted_product_id = $this->db->insert_id();

            //     $child_product_ids = range($first_inserted_product_id, $first_inserted_product_id + count($child_product_inserts) - 1);
            // }

            if (!empty($sku_inserts)) {                

                $this->db->insert_batch('skus', $sku_inserts);
                $first_inserted_sku_id = $this->db->insert_id();
                $inserted += count($sku_inserts);
                $sku_ids = range($first_inserted_sku_id, $first_inserted_sku_id + count($sku_inserts) - 1);
            }

        // Link SKU and option values
            if (!empty($sku_option_value_inserts)) {
                $sku_index = 0; 

                $sku_count = count($sku_ids);
                $value_count = count($sku_option_value_inserts);

                foreach ($sku_option_value_inserts as $index => &$sku_option_value_data) {
                    if (isset($sku_ids[$sku_index])) {
                        $sku_option_value_data['sku_id'] = $sku_ids[$sku_index];

                        if (($index + 1) % ($value_count / $sku_count) == 0) {
                            $sku_index++;
                        }
                    }
                }

                $this->db->insert_batch('sku_option_values', $sku_option_value_inserts);
            }



        $this->db->trans_complete(); 
        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['status' => 'error', 'message' => 'Transaction failed']);
            return;
        }

        echo json_encode([
            'status' => 'success',
            'inserted' => $inserted,
            'updated' => $updated,
            'skipped' => $skipped
        ]);
    }

/////////
 public function export_sku_data()
{
    // Get SKU data from the model
    $this->db->select('sku_code, price, retail_price, stock_quantity, image');
    $this->db->from('skus');
    $query = $this->db->get();
    $sku_data = $query->result_array();

    if (empty($sku_data)) {
        echo json_encode(['status' => 'error', 'message' => 'No SKU data available to export.']);
        return;
    }

    // Set CSV headers
    $headers = ['sku_code', 'price', 'retail_price', 'stock_quantity','image'];

    // Prepare CSV data
    $export_data = [];

    foreach ($sku_data as $sku) {
        $row = [
            $sku['sku_code'],
            $sku['price'],
            $sku['retail_price'],
            $sku['stock_quantity'],
            $sku['image']
        ];
        $export_data[] = $row;
    }

    // Convert to CSV format and trigger download
    $csv = $this->array_to_csv_with_headers2($headers, $export_data);

    // Send the headers and file for download
    force_download('sku_data.csv', $csv);
}

public function array_to_csv_with_headers2($headers, $data)
{
    // Open a temporary file for CSV output
    $output = fopen('php://temp', 'r+');

    // Write the headers row to the CSV
    fputcsv($output, $headers);

    // Write each data row to the CSV
    foreach ($data as $row) {
        fputcsv($output, $row);
    }

    // Reset file pointer to the beginning
    rewind($output);

    // Get the CSV content from the temporary file
    $csv_content = stream_get_contents($output);

    // Close the temporary file
    fclose($output);

    return $csv_content;
}

public function update_sku_from_csv()
{
    $this->load->helper(['form', 'url']);

    $excel_data = $this->input->post('excel_data');

    if (!$excel_data) {
        echo json_encode(['status' => 'error', 'message' => 'No Excel data provided']);
        return;
    }

    $decoded_data = json_decode($excel_data, true);

    if (!$decoded_data || !is_array($decoded_data) || count($decoded_data) < 2) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid Excel data']);
        return;
    }

    // First row contains headers
    $headers = $decoded_data[0];
    $sku_data_rows = array_slice($decoded_data, 1);

    foreach ($sku_data_rows as $row) {
        $row_data = array_combine($headers, $row); // ['sku_code' => ..., 'price' => ..., etc.]

        if (!isset($row_data['sku_code'])) {
            continue; // skip invalid rows
        }

        $this->db->where('sku_code', $row_data['sku_code']);
        $this->db->update('skus', [
            'price'          => isset($row_data['price']) ? $row_data['price'] : null,
            'retail_price'   => isset($row_data['retail_price']) ? $row_data['retail_price'] : null,
            'stock_quantity' => isset($row_data['stock_quantity']) ? $row_data['stock_quantity'] : null,
            'image'          => isset($row_data['image']) ? $row_data['image'] : null

        ]);
    }

    echo json_encode(['status' => 'success', 'message' => 'SKU data updated successfully.']);
}


    // Function to parse CSV and return data as an associative array
    private function parse_csv($file_path) {
        $csv_data = [];

        if (($handle = fopen($file_path, 'r')) !== false) {
            $headers = fgetcsv($handle);  // Get the headers row

            while (($row = fgetcsv($handle)) !== false) {
                $csv_data[] = array_combine($headers, $row);
            }

            fclose($handle);
        }

        return $csv_data;
    }

    // Function to update SKUs in the database from the CSV data
    private function update_skus_in_db($csv_data) {
        foreach ($csv_data as $row) {
            $sku_code = $row['sku_code'];  // SKU Code
            $price = $row['price'];  // New price
            $retail_price = $row['retail_price'];  // New retail price
            $stock_quantity = $row['stock_quantity'];  // New stock quantity

            // Prepare data for updating
            $data = [
                'price' => $price,
                'retail_price' => $retail_price,
                'stock_quantity' => $stock_quantity
            ];

            // Update SKU in the database
            $this->db->where('sku_code', $sku_code);
            $this->db->update('skus', $data);
        }
    }
}
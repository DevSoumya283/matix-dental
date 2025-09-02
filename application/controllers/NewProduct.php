<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . '/third_party/spout/src/Spout/Autoloader/autoload.php';
use Box \ Spout \ Reader \ ReaderFactory;
use Box \ Spout \ Writer \ WriterFactory;
use Box \ Spout \ Common \ Type;


class NewProduct extends MW_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'file']);
        $this->load->model('Productdata_model');
        $this->load->model('Products_model');
        $this->load->model('ProductNormalizer_model');
        $this->load->library('ion_auth');

        $this->load->helper('download');

        $this->load->library('upload'); 
    }

    public function index()
    {
        $this->load->view('new_product');
    }

    // For 1st tab 



    public function export_new()
    {
        set_time_limit(0);
        ini_set("memory_limit", "12288M");

         $range = $this->input->get('range');
            if ($range && strpos($range, '-') !== false) {
            list($start, $end) = explode('-', $range);
            $offset = is_numeric($start) ? (int)$start : 0;
            $limit  = (is_numeric($end) ? (int)$end : 10) - $offset;
        } else {
            $offset = 0;
            $limit  = 10;
        }
        if ($limit <= 0) $limit = 10;

            // Optional: validate range
            if (!is_numeric($limit) || !is_numeric($offset)) {
                show_error("Invalid range values.");
                return;
            }

        $headerRow = array(
             'id', 'matix_id', 'mpn', 'item_code', 'name', 'description',
            'extended_description', 'keywords', 'manufacturer',
            'shipping_restrictions', 'brand', 'license_required',
            'category_id', 'base_price', 'active'
        );

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
        $mpns = array_filter(array_column($products, 'id'));
        $pricing_map = $this->Products_model->get_prices_by_mpn_array($mpns);
        foreach ($products as $product) {
                $price = isset($pricing_map[$product->id]) ? $pricing_map[$product->id]['price'] : '';
                $retail_price = isset($pricing_map[$product->id]) ? $pricing_map[$product->id]['retail_price'] : '';

                $products_data = [
                            $product->id,
                            $product->matix_id,
                            $product->mpn,
                            $product->item_code,
                            $product->name,
                            strip_tags($product->description),
                            strip_tags($product->extended_description),
                            $product->keywords,
                            $product->manufacturer,
                            $product->shipping_restrictions, // Changed from product_procedures
                            $product->brand,
                            $product->license_required,
                            $product->category_id,
                            $product->base_price, // You'll need to get this from somewhere
                            $product->active
                        ];
                $writer->addRow($products_data);
            }
        

        $writer->close();

        // Force download
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
    


    public function save_data()
        {
            $excel_data = $this->input->post('excel_data');
            $file_name = $this->input->post('file_name');
            $vendor_id = '8';
            $elasticsearch_enabled = false;

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

                // $existing_product = $this->Products_model->select('id', 'matix_id')->get_by(['mpn' => $mpn]);
                
                $sql = "SELECT id, matix_id FROM products WHERE mpn = ? LIMIT 1";
                $existing_product = $this->db->query($sql, [$mpn])->row();
    
                $matix_id_value = $existing_product ? $existing_product->id : 'p-' . time();

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
                    'matix_id' => !empty($row[3]) ? $row[3] : 'p-' . $random_number,
                    'parent_product' => '1',
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


    // Preview the uploaded Excel
    public function preview_options_excel()
    {
        if (!empty($_FILES['file']['name'])) {
            $inputFileName = $_FILES['file']['tmp_name'];

            try {
                $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
                $sheet = $objPHPExcel->getSheet(0);
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                $data = [];
                for ($row = 1; $row <= $highestRow; $row++) {
                    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                    $data[] = $rowData[0];
                }

                echo json_encode(['status' => 'success', 'data' => $data]);
            } catch (Exception $e) {
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            }
        }
    }

    public function save_options_excel()
    {
        $rows = json_decode($this->input->post('rows'), true);
        if (!$rows || count($rows) < 2) {
            echo json_encode(['status' => 'error', 'message' => 'No valid data found']);
            return;
        }

        // Remove header row
        array_shift($rows);

        $missing_products = [];
        $options_map = []; // [product_id => [option_type => option_id]]
        $values_map  = []; // [product_id => [option_type => [value_id => option_code]]]

        $this->db->trans_start();

        foreach ($rows as $row) {
            $product_id  = trim($row[0]); // matix_id
            $option_type = trim($row[1]);
            $option_code = trim($row[2]);
            $value       = trim($row[3]);

            if (!$product_id || !$option_type || !$option_code || !$value) {
                continue; // skip incomplete
            }

            // 1️⃣ Check product exists
            $product_exists = $this->db->get_where('products', ['matix_id' => $product_id])->row();
            if (!$product_exists) {
                $missing_products[] = $product_id;
                continue;
            }

            // 2️⃣ Get or insert option (global uniqueness by type + code)
            $option = $this->db->get_where('product_options', [
                'option_type' => $option_type,
                'option_code' => $option_code
            ])->row();

            if (!$option) {
                $this->db->insert('product_options', [
                    'option_type' => $option_type,
                    'option_code' => $option_code
                ]);
                $option_id = $this->db->insert_id();
            } else {
                $option_id = $option->option_id;
            }
            $options_map[$product_id][$option_type] = $option_id;

            // 3️⃣ Get or insert value (unique per option_id + value)
            $value_row = $this->db->get_where('product_option_values', [
                'option_id' => $option_id,
                'value'     => $value
            ])->row();

            if (!$value_row) {
                $this->db->insert('product_option_values', [
                    'option_id'  => $option_id,
                    'product_id' => $product_id,
                    'value'      => $value
                ]);
                $value_id = $this->db->insert_id();
            } else {
                $value_id = $value_row->value_id;

                // Update product_id if different
                if ($value_row->product_id != $product_id) {
                    $this->db->update(
                        'product_option_values',
                        ['product_id' => $product_id],
                        ['value_id' => $value_id]
                    );
                }
            }

            // Map for SKU creation
            $values_map[$product_id][$option_type][$value_id] = $option_code;
        }

        // 4️⃣ SKU generation
        // foreach ($values_map as $product_id => $options) {
        //     $combinations = $this->cartesianProduct(array_values($options));

        //     foreach ($combinations as $combo) {
        //         $sku_code = $product_id . '-' . implode('-', $combo);

        //         $sku_exists = $this->db->get_where('skus', [
        //             'product_id' => $product_id,
        //             'sku_code'   => $sku_code
        //         ])->row();

        //         if (!$sku_exists) {
        //             $this->db->insert('skus', [
        //                 'product_id'      => $product_id,
        //                 'sku_code'        => $sku_code,
        //                 'price'           => null,
        //                 'retail_price'    => null,
        //                 'stock_quantity'  => null,
        //                 'status'          => 'active',
        //                 'exclude_from_whitelabels_1' => 0,
        //                 'exclude_from_whitelabels_2' => 0,
        //                 'exclude_from_marketplace' => 0,
        //                 'minimum_threshold' => null
        //             ]);
        //             $sku_id = $this->db->insert_id();


        //             foreach ($combo as $code) {
        //                 foreach ($options as $opt_values) {
        //                     $value_id = array_search($code, $opt_values);
        //                     if ($value_id !== false) {
        //                         $this->db->insert('sku_option_values', [
        //                             'sku_id'   => $sku_id,
        //                             'value_id' => $value_id
        //                         ]);
        //                     }
        //                 }
        //             }
        //         }
        //     }
        // }
        // 4️⃣ SKU generation
        foreach ($values_map as $product_id => $options) {
            $combinations = $this->cartesianProduct(array_values($options));

            foreach ($combinations as $combo) {
                $sku_code = $product_id . '-' . implode('-', $combo);

                // Avoid duplicate SKUs (skus table)
                $sku_exists = $this->db->get_where('skus', [
                    'product_id' => $product_id,
                    'sku_code'   => $sku_code
                ])->row();

                if (!$sku_exists) {
                    // Insert into skus
                    $this->db->insert('skus', [
                        'product_id'      => $product_id,
                        'parent_product_id'       => $product_id,
                        'sku_code'        => $sku_code,
                        'price'           => null,
                        'retail_price'    => null,
                        'stock_quantity'  => null,
                        'status'          => 'active',
                        'exclude_from_whitelabels_1' => 0,
                        'exclude_from_whitelabels_2' => 0,
                        'exclude_from_marketplace'   => 0,
                        'minimum_threshold'          => null
                    ]);
                    $sku_id = $this->db->insert_id();

                    // Insert SKU option values
                    foreach ($combo as $code) {
                        foreach ($options as $opt_values) {
                            $value_id = array_search($code, $opt_values);
                            if ($value_id !== false) {
                                $this->db->insert('sku_option_values', [
                                    'sku_id'   => $sku_id,
                                    'value_id' => $value_id
                                ]);
                            }
                        }
                    }

                    // 🔹 Insert into product_pricings table
                    // Get product details first
                    $product = $this->db->select('id, mpn, matix_id')
                        ->where('matix_id', $product_id)
                        ->get('products')
                        ->row();

                    if ($product) {
                        // Check if sku already exists in product_pricings
                        $pricing_exists = $this->db->get_where('product_pricings', [
                            'product_id' => $product->id,
                            // 'sku'        => $sku_code
                        ])->row();

                        $vendor_id = $this->input->post('vendor_id');
                        if (!$pricing_exists) {
                            $this->db->insert('product_pricings', [
                                'product_id'        => $product->id,
                                'sku'               => $sku_code,
                                'vendor_product_id' => $product->mpn,   // mpn from products table
                                'matix_id'          => $product->matix_id,
                                'minimum_threshold' => 0,
                                'vendor_id'         => $vendor_id,
                                'price'             => null,
                                'retail_price'      => null,
                                'active'            => 1,
                                'quantity'          => null,
                                'exclude_from_marketplace' => 0,
                                'exclude_from_whitelabels_1' => 0,
                                'exclude_from_whitelabels_2' => 0,
                                'created_at'        => date('Y-m-d H:i:s'),
                                'updated_at'        => date('Y-m-d H:i:s'),
                            ]);
                        }
                        else{
                            $this->db->update('product_pricings', [
                                'sku'               => $sku_code,
                                'created_at'        => date('Y-m-d H:i:s'),
                                'updated_at'        => date('Y-m-d H:i:s'),
                            ]);
                        }
                    }
                }
            }
        }


        $this->db->trans_complete();

        $response = [
            'status'  => 'success',
            'message' => 'Options and SKUs processed successfully'
        ];

        if (!empty($missing_products)) {
            $response['status'] = 'warning';
            $response['missing_products'] = array_unique($missing_products);
            $response['message'] .= ' | Missing products: ' . implode(', ', $missing_products);
        }

        echo json_encode($response);
    }


    // Helper function for cartesian product
    private function cartesianProduct($arrays)
    {
        $result = [[]];
        foreach ($arrays as $property => $property_values) {
            $tmp = [];
            foreach ($result as $result_item) {
                foreach ($property_values as $value) {
                    $tmp[] = array_merge($result_item, [$value]);
                }
            }
            $result = $tmp;
        }
        return $result;
    }

    public function export_newoptions()
    {
        // Set execution limits
        set_time_limit(0);
        ini_set("memory_limit", "12288M");

        try {
            // Header definition (product_id instead of id)
            $headerRow = [
                'Product_id',  
                'Option_type',
                'Option_code',
                'Value'
            ];

            // Prepare filename
            $filename = 'product_options_' . date('Y-m-d') . '.csv';

            // Set headers for download
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Pragma: no-cache');
            header('Expires: 0');

            // Create file pointer connected to the output stream
            $output = fopen('php://output', 'w');

            // Add BOM for UTF-8 (helps with Excel compatibility)
            fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Add headers to CSV
            fputcsv($output, $headerRow);

            // Fetch only matix_id from products table
            $this->db->select('matix_id');
            $query = $this->db->get('products');

            if ($query->num_rows() > 0) {
                $products = $query->result();

                foreach ($products as $product) {
                    $option_data = [
                        isset($product->matix_id) ? $product->matix_id : '', // matrix_id
                        '', // Option_type empty
                        '', // Option_code empty
                        ''  // Value empty
                    ];

                    fputcsv($output, $option_data);
                }
            }


            fclose($output);
        } catch (Exception $e) {
            // If there's an error, show a simple message
            echo "Error: " . $e->getMessage();
        }

        exit;
    }


    public function get_all_options()
    {
        $data = $this->db->get('product_options')->result_array();
        echo json_encode(['status' => 'success', 'data' => $data]);
    }
    public function get_all_products()
    {
        $data = $this->db->limit(50)->order_by('id', 'ASC')->get('products')->result_array();
        echo json_encode(['status' => 'success', 'data' => $data]);
    }



    // 3rd tab
    public function get_all_products_with_options()
    {
        $this->db->select('
        p.matix_id AS product_id,
        p.name,
        p.mpn,
        s.sku_id,
        s.sku_code,
        po.option_id,
        po.option_code,
        po.option_type AS option_name,
        pov.value_id,
        pov.value AS option_value
    ');
        $this->db->from('skus s');
        $this->db->join('products p', 's.product_id = p.matix_id', 'left');
        $this->db->join('sku_option_values sov', 's.sku_id = sov.sku_id', 'left');
        $this->db->join('product_option_values pov', 'sov.value_id = pov.value_id', 'left');
        $this->db->join('product_options po', 'pov.option_id = po.option_id', 'left');
        $this->db->order_by('p.id', 'ASC');
        $this->db->order_by('s.sku_id', 'ASC');

        $query = $this->db->get();

        echo json_encode([
            'status' => 'success',
            'data'   => $query->result_array()
        ]);
    }

    public function ajax_get_vendors()
    {
        $vendors = $this->db->select('id, name')
            ->from('vendors')
            ->order_by('name', 'ASC')
            ->get()
            ->result();

        echo json_encode($vendors);
    }

    // Update Price tab 

    public function updateskuprice()
    {
        $json = json_decode(file_get_contents("php://input"), true);

        if (!$json || !isset($json['rows']) || count($json['rows']) < 2) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
            return;
        }

        $rows = $json['rows'];
        $headers = array_map('strtolower', $rows[0]); // lowercase headers
        array_shift($rows); // remove header row

        foreach ($rows as $row) {
            $rowData = [];
            foreach ($headers as $i => $h) {
                if (!empty($h) && isset($row[$i])) {
                    $rowData[$h] = trim($row[$i]);
                }
            }

            if (empty($rowData['product_sku'])) continue;
            $sku = $rowData['product_sku'];

            // Prepare update data only if provided
            $skuUpdate = [];
            $pricingUpdate = [];

            if (isset($rowData['quantity'])) {
                $skuUpdate['stock_quantity'] = $rowData['quantity'];
                $pricingUpdate['quantity']   = $rowData['quantity'];
            }
            if (isset($rowData['price'])) {
                $skuUpdate['price'] = $rowData['price'];
                $pricingUpdate['price'] = $rowData['price'];
            }
            if (isset($rowData['retail_price'])) {
                $skuUpdate['retail_price'] = $rowData['retail_price'];
                $pricingUpdate['retail_price'] = $rowData['retail_price'];
            }

            // 🔹 SKUs table
            $q = $this->db->get_where('skus', ['sku_code' => $sku]);
            if ($q->num_rows() > 0) {
                if (!empty($skuUpdate)) {
                    $this->db->where('sku_code', $sku)->update('skus', $skuUpdate);
                }
            } else {
                $skuUpdate['sku_code'] = $sku;
                $this->db->insert('skus', $skuUpdate);
            }

            // 🔹 Product Pricings table
            $q2 = $this->db->get_where('product_pricings', ['sku' => $sku]);
            if ($q2->num_rows() > 0) {
                if (!empty($pricingUpdate)) {
                    $this->db->where('sku', $sku)->update('product_pricings', $pricingUpdate);
                }
            } else {
                $pricingUpdate['sku'] = $sku;
                $this->db->insert('product_pricings', $pricingUpdate);
            }
        }

        echo json_encode(['status' => 'success', 'message' => 'Prices updated successfully']);
    }

    public function normalize_products()
    {
        $size='20';
        $summary =  $this->ProductNormalizer_model->normalize_existing_products($size);
        // $summary =  $this->ProductNormalizer_model->getProductsWithOptions($size);

        echo"<pre>";
        print_r($summary);die();
        echo"</pre>";
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($summary));
    }

    public function getProductsWithOptions()
    {
        $size='20';
        $summary =  $this->ProductNormalizer_model->getProductsWithOptions($size);

        echo"<pre>";
        print_r($summary);die();
        echo"</pre>";
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($summary));
    }


    // for Varient Product

    public function get_products() { 
        $search = $this->input->post('search')['value']; 
        $start = $this->input->post('start'); 
        $length = $this->input->post('length'); 

        // ✅ Get all parent products first
        $parentProducts = $this->db->select('matix_id, name') 
            ->from('products') 
            ->where('parent_product', 1) 
            ->get()->result(); 

        $this->db->from('products'); 
        if (!empty($search)) { 
            $this->db->group_start(); 
            $this->db->like('name', $search); 
            $this->db->or_like('mpn', $search); 
            $this->db->group_end(); 
        } 
        $totalFiltered = $this->db->count_all_results('', false); 
        $this->db->limit($length, $start); 
        $query = $this->db->get(); 
        $products = $query->result(); 

        $data = []; 
        foreach ($products as $p) { 
            $isParent = ($p->parent_product == 1); 
            
            // ✅ check if this product already has parent in skus
            $sku = $this->db->select('parent_product_id') 
                ->from('skus') 
                ->where('product_id', $p->matix_id) 
                ->get()->row(); 
            $selectedParentId = $sku ? $sku->parent_product_id : null; 
            $isVariant = !empty($selectedParentId); // Check if this product is a variant

            $row = [ 
                $p->matix_id, 
                $p->name, 
                $p->mpn 
            ]; 

            // Parent checkbox - UPDATED LOGIC
            if ($isParent) {
                $row[] = '<input type="checkbox" class="set-parent" data-id="'.$p->matix_id.'" data-name="'.$p->name.'" checked>';
            } else if ($isVariant) {
                $row[] = '<span class="badge bg-primary ">Variant Product</span>';
            } else {
                $row[] = '<input type="checkbox" class="set-parent" data-id="'.$p->matix_id.'" data-name="'.$p->name.'">';
            }

            // Variants dropdown - UPDATED LOGIC
            if ($isParent) { 
                $row[] = '<span class="badge bg-success">Parent Product</span>'; 
            } else { 
                // Always show dropdown for non-parent products (including variants)
                $dropdown = '<select class="variant-select form-control" data-id="'.$p->matix_id.'">'; 
                $dropdown .= '<option value="">-- Select Parent --</option>'; 
                // ✅ Add all available parents
                foreach ($parentProducts as $parent) { 
                    $selected = ($selectedParentId == $parent->matix_id) ? 'selected' : ''; 
                    $dropdown .= '<option value="'.$parent->matix_id.'" '.$selected.'>'.$parent->name.' ('.$parent->matix_id.')</option>'; 
                } 
                $dropdown .= '</select>'; 
                $row[] = $dropdown; 
            } 

            $data[] = $row; 
        } 

        echo json_encode([ 
            "draw" => intval($this->input->post('draw')), 
            "recordsTotal" => $this->db->count_all('products'), 
            "recordsFiltered" => $totalFiltered, 
            "data" => $data 
        ]); 
    } 

    // Update parent product (check / uncheck)
    public function set_parent_product() { 
        $matix_id = $this->input->post('matix_id'); 
        $status = $this->input->post('status'); // 1 = checked, 0 = unchecked

        try { 
            if ($status == 1) { 
                // Mark as parent
                $this->db->where('matix_id', $matix_id); 
                $update = $this->db->update('products', ['parent_product' => 1]); 
                if (!$update) { 
                    throw new Exception("Failed to update parent product"); 
                } 
                echo json_encode(['status' => 'success', 'action' => 'set_parent']); 
            } else { 
                // Remove parent flag
                $this->db->where('matix_id', $matix_id); 
                $update = $this->db->update('products', ['parent_product' => 0]); 
                if (!$update) { 
                    throw new Exception("Failed to unset parent product"); 
                } 
                // Clear all its variants from skus table
                $this->db->where('parent_product_id', $matix_id); 
                $this->db->update('skus', ['parent_product_id' => NULL]); 
                echo json_encode(['status' => 'success', 'action' => 'unset_parent']); 
            } 
        } catch (Exception $e) { 
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]); 
        } 
    } 

    // Update variant product in skus
    public function set_variant_product() { 
        $matix_id = $this->input->post('matix_id'); // variant product id
        $parent_id = $this->input->post('parent_id'); // parent product id

        try { 
            $this->db->where('product_id', $matix_id); 
            $update = $this->db->update('skus', ['parent_product_id' => $parent_id]); 
            if (!$update) { 
                throw new Exception("Failed to update variant product"); 
            } 
            echo json_encode(['status' => 'success', 'message' => 'Variant product linked successfully']); 
        } catch (Exception $e) { 
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]); 
        } 
    }

    // NEW METHOD: Remove variant product relationship
    public function remove_variant_product() { 
        $matix_id = $this->input->post('matix_id'); // variant product id

        try { 
            // Check if the product exists in skus table
            $existing = $this->db->where('product_id', $matix_id)->get('skus')->row();
            
            if ($existing) {
                // Update existing record to remove parent relationship
                $this->db->where('product_id', $matix_id); 
                $update = $this->db->update('skus', ['parent_product_id' => NULL]); 
                if (!$update) { 
                    throw new Exception("Failed to remove variant product relationship"); 
                }
            }
            
            echo json_encode(['status' => 'success', 'message' => 'Parent relationship removed successfully']); 
        } catch (Exception $e) { 
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]); 
        } 
    }

}

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
        $this->load->library('ion_auth');

        $this->load->helper('download');

        $this->load->library('upload'); 
    }

    public function index()
    {
        $this->load->view('new_product');
    }

    // For 1st tab 

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
            'id',
            'mpn',
            'item_code',
            'name',
            'description',
            'extended_description',
            'keywords',
            'manufacturer',
            'shipping_restrictions',
            'brand',
            'license_required',
            'category_id',
            'base_price',
            'active'
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
                'matix_id' => 'p-' . $random_number,
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


    // public function save_options_excel()
    // {
    //     $rows = json_decode($this->input->post('rows'), true);
    //     if (!$rows || count($rows) < 2) {
    //         echo json_encode(['status' => 'error', 'message' => 'No valid data found']);
    //         return;
    //     }

    //     // Remove header row
    //     array_shift($rows);

    //     $missing_products = [];

    //     foreach ($rows as $row) {
    //         $product_id   = trim($row[0]);
    //         $option_type  = trim($row[1]);
    //         $option_code  = trim($row[2]);
    //         $value        = trim($row[3]);

    //         if (!$option_type || !$option_code || !$value) {
    //             continue; // skip incomplete rows
    //         }

    //         // 1. Check if option exists in products_option2 (by all 3 columns)
    //         $option = $this->db->get_where('products_option2', [
    //             'Option_type' => $option_type,
    //             'Option_code' => $option_code,
    //             'Value'       => $value
    //         ])->row();

    //         if ($option) {
    //             $option_id = $option->id;
    //         } else {
    //             // Avoid duplicate Option_code error
    //             $option_code_exists = $this->db->get_where('products_option2', [
    //                 'Option_code' => $option_code
    //             ])->row();

    //             if ($option_code_exists) {
    //                 $option_id = $option_code_exists->id;
    //             } else {
    //                 $this->db->insert('products_option2', [
    //                     'Option_type' => $option_type,
    //                     'Option_code' => $option_code,
    //                     'Value'       => $value
    //                 ]);
    //                 $option_id = $this->db->insert_id();
    //             }
    //         }

    //         // 2. If product_id is provided, validate and link
    //         if (!empty($product_id)) {
    //             // Check if product exists
    //             $product_exists = $this->db->get_where('products', ['id' => $product_id])->num_rows();

    //             if ($product_exists == 0) {
    //                 // Store missing product ID for alert
    //                 $missing_products[] = $product_id;
    //                 continue;
    //             }

    //             // Link to product_options_value3 if not already linked
    //             $exists = $this->db->get_where('product_options_value3', [
    //                 'product_id' => $product_id,
    //                 'options_id' => $option_id
    //             ])->num_rows();

    //             if ($exists == 0) {
    //                 $this->db->insert('product_options_value3', [
    //                     'product_id' => $product_id,
    //                     'options_id' => $option_id
    //                 ]);
    //             }
    //         }
    //     }

    //     // Prepare response
    //     $response = [
    //         'status' => 'success',
    //         'message' => 'Options processed successfully',
    //         'refresh' => true // tell frontend to refresh tab
    //     ];

    //     if (!empty($missing_products)) {
    //         $response['missing_products'] = array_unique($missing_products);
    //         $response['status'] = 'warning';
    //         $response['message'] .= ' | Missing products: ' . implode(', ', $missing_products);
    //     }

    //     echo json_encode($response);
    // }


    // public function save_options_excel()
    // {
    //     $rows = json_decode($this->input->post('rows'), true);
    //     if (!$rows || count($rows) < 2) {
    //         echo json_encode(['status' => 'error', 'message' => 'No valid data found']);
    //         return;
    //     }

    //     // Remove header row
    //     array_shift($rows);

    //     $missing_products = [];
    //     $duplicate_option_codes = [];

    //     foreach ($rows as $row) {
    //         $matix_id    = trim($row[0]);  // string product_id e.g. "p-8455463"
    //         $option_type = trim($row[1]);
    //         $option_code = trim($row[2]);
    //         $value       = trim($row[3]);

    //         if (!$matix_id || !$option_type || !$option_code || !$value) {
    //             continue; // skip incomplete rows
    //         }

    //         // Check if product with this matix_id exists in products table
    //         $product_exists = $this->db->get_where('products', ['matix_id' => $matix_id])->num_rows();

    //         if ($product_exists == 0) {
    //             $missing_products[] = $matix_id;
    //             continue;
    //         }

    //         // Check if Option_code already exists in products_option2
    //         $option = $this->db->get_where('products_option2', ['Option_code' => $option_code])->row();

    //         if ($option) {
    //             // Duplicate option_code found, skip insert
    //             $duplicate_option_codes[] = $option_code;
    //             $option_id = $option->id;
    //         } else {
    //             // Insert new option
    //             $this->db->insert('products_option2', [
    //                 'Option_type' => $option_type,
    //                 'Option_code' => $option_code,
    //                 'Value'       => $value
    //             ]);
    //             $option_id = $this->db->insert_id();
    //         }

    //         $exists = $this->db->get_where('product_options_value3', [
    //             'product_id' => $matix_id,
    //             'options_id' => $option_id
    //         ])->num_rows();

    //         if ($exists == 0) {
    //             $this->db->insert('product_options_value3', [
    //                 'product_id' => $matix_id,
    //                 'options_id' => $option_id
    //             ]);
    //         }
    //     }

    //     // Prepare response
    //     $response = [
    //         'status' => 'success',
    //         'message' => 'Options processed successfully',
    //         'refresh' => true
    //     ];

    //     if (!empty($missing_products)) {
    //         $response['missing_products'] = array_unique($missing_products);
    //         $response['status'] = 'warning';
    //         $response['message'] .= ' | Missing products with product_id: ' . implode(', ', $missing_products);
    //     }

    //     if (!empty($duplicate_option_codes)) {
    //         $response['duplicate_option_codes'] = array_unique($duplicate_option_codes);
    //         $response['status'] = 'warning';
    //         $response['message'] .= ' | Duplicate Option_code(s) skipped: ' . implode(', ', $duplicate_option_codes);
    //     }

    //     echo json_encode($response);
    // }

    // public function save_options_excel()
    // {
    //     $rows = json_decode($this->input->post('rows'), true);
    //     if (!$rows || count($rows) < 2) {
    //         echo json_encode(['status' => 'error', 'message' => 'No valid data found']);
    //         return;
    //     }

    //     // Remove header row
    //     array_shift($rows);

    //     $missing_products = [];

    //     foreach ($rows as $row) {
    //         $matix_id    = trim($row[0]);  // string product_id e.g. "p-8455463"
    //         $option_type = trim($row[1]);
    //         $option_code = trim($row[2]);
    //         $value       = trim($row[3]);

    //         if (!$matix_id || !$option_type || !$option_code || !$value) {
    //             continue; // skip incomplete rows
    //         }

    //         $product_exists = $this->db->get_where('products', ['matix_id' => $matix_id])->num_rows();

    //         $option_code_exits = $this->db->get_where('products_option2', ['Option_code' => $option_code])->num_rows();

    //         if($option_code_exits){

    //         }

    //         if ($product_exists == 0) {
    //             $missing_products[] = $matix_id;
    //             continue;
    //         }

    //         // 1. Check if option exists in products_option2 (by all 3 columns)
    //         $option = $this->db->get_where('products_option2', [
    //             'Option_type' => $option_type,
    //             'Option_code' => $option_code,
    //             'Value'       => $value
    //         ])->row();

    //         if ($option) {
    //             $option_id = $option->id;
    //         } else {
    //             // Avoid duplicate Option_code error
    //             $option_code_exists = $this->db->get_where('products_option2', [
    //                 'Option_code' => $option_code
    //             ])->row();

    //             if ($option_code_exists) {
    //                 $option_id = $option_code_exists->id;
    //             } else {
    //                 $this->db->insert('products_option2', [
    //                     'Option_type' => $option_type,
    //                     'Option_code' => $option_code,
    //                     'Value'       => $value
    //                 ]);
    //                 $option_id = $this->db->insert_id();
    //             }
    //         }

    //         $exists = $this->db->get_where('product_options_value3', [
    //             'product_id' => $matix_id,
    //             'options_id' => $option_id
    //         ])->num_rows();

    //         if ($exists == 0) {
    //             $this->db->insert('product_options_value3', [
    //                 'product_id' => $matix_id,
    //                 'options_id' => $option_id
    //             ]);
    //         }
    //     }

    //     // Prepare response
    //     $response = [
    //         'status' => 'success',
    //         'message' => 'Options processed successfully',
    //         'refresh' => true
    //     ];

    //     if (!empty($missing_products)) {
    //         $response['missing_products'] = array_unique($missing_products);
    //         $response['status'] = 'warning';
    //         $response['message'] .= ' | Missing products with product_id: ' . implode(', ', $missing_products);
    //     }

    //     echo json_encode($response);
    // }

    // public function save_options_excel()
    // {
    //     $rows = json_decode($this->input->post('rows'), true);
    //     if (!$rows || count($rows) < 2) {
    //         echo json_encode(['status' => 'error', 'message' => 'No valid data found']);
    //         return;
    //     }

    //     // Remove header row
    //     array_shift($rows);

    //     $missing_products = [];
    //     $duplicate_option_codes = [];

    //     foreach ($rows as $row) {
    //         $matix_id    = trim($row[0]);  // string product_id e.g. "p-8455463"
    //         $option_type = trim($row[1]);
    //         $option_code = trim($row[2]);
    //         $value       = trim($row[3]);

    //         if (!$option_type || !$option_code || !$value) {
    //             continue; // skip incomplete rows
    //         }

    //         // 1. Check if option exists in products_option2 (by all 3 columns)
    //         $option = $this->db->get_where('products_option2', [
    //             'Option_type' => $option_type,
    //             'Option_code' => $option_code,
    //             'Value'       => $value
    //         ])->row();

    //         if ($option) {
    //             $option_id = $option->id;
    //         } else {
    //             // Check if Option_code already exists (for duplicate alert)
    //             $option_code_exists = $this->db->get_where('products_option2', [
    //                 'Option_code' => $option_code
    //             ])->row();

    //             if ($option_code_exists) {
    //                 // Mark as duplicate but continue processing
    //                 $duplicate_option_codes[] = $option_code;
    //                 $option_id = $option_code_exists->id;
    //             } else {
    //                 // Insert new option
    //                 $this->db->insert('products_option2', [
    //                     'Option_type' => $option_type,
    //                     'Option_code' => $option_code,
    //                     'Value'       => $value
    //                 ]);
    //                 $option_id = $this->db->insert_id();
    //             }
    //         }

    //         // 2. If product_id is provided, validate and link
    //         if (!empty($matix_id)) {
    //             // Check if product with this matix_id exists in products table
    //             $product_exists = $this->db->get_where('products', ['matix_id' => $matix_id])->num_rows();

    //             if ($product_exists == 0) {
    //                 $missing_products[] = $matix_id;
    //                 continue;
    //             }

    //             // Link to product_options_value3 if not already linked
    //             $exists = $this->db->get_where('product_options_value3', [
    //                 'product_id' => $matix_id,
    //                 'options_id' => $option_id
    //             ])->num_rows();

    //             if ($exists == 0) {
    //                 $this->db->insert('product_options_value3', [
    //                     'product_id' => $matix_id,
    //                     'options_id' => $option_id
    //                 ]);
    //             }
    //         }
    //     }

    //     // Prepare response
    //     $response = [
    //         'status' => 'success',
    //         'message' => 'Options processed successfully',
    //         'refresh' => true
    //     ];

    //     if (!empty($missing_products)) {
    //         $response['missing_products'] = array_unique($missing_products);
    //         $response['status'] = 'warning';
    //         $response['message'] .= ' | Missing products with product_id: ' . implode(', ', $missing_products);
    //     }

    //     if (!empty($duplicate_option_codes)) {
    //         $response['duplicate_option_codes'] = array_unique($duplicate_option_codes);
    //         $response['status'] = 'warning';
    //         $response['message'] .= ' | Duplicate Option_code(s) found: ' . implode(', ', $duplicate_option_codes);
    //     }

    //     echo json_encode($response);
    // }

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
            $product_id  = trim($row[0]); // matrix_id
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
                            'sku'        => $sku_code
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









    // public function export_newoptions()
    // {
    //     // Set execution limits
    //     set_time_limit(0);
    //     ini_set("memory_limit", "12288M");

    //     try {
    //         // Header definition
    //         $headerRow = [
    //             'id',
    //             'Option_type',
    //             'Option_code',
    //             'Value'
    //         ];

    //         // Prepare filename
    //         $filename = 'product_options_' . date('Y-m-d_H-i-s') . '.csv';

    //         // Set headers for download
    //         header('Content-Type: application/csv');
    //         header('Content-Disposition: attachment; filename="' . $filename . '"');
    //         header('Pragma: no-cache');
    //         header('Expires: 0');

    //         // Create file pointer connected to the output stream
    //         $output = fopen('php://output', 'w');

    //         // Add BOM for UTF-8 (helps with Excel compatibility)
    //         fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

    //         // Add headers to CSV
    //         fputcsv($output, $headerRow);

    //         // Fetch data directly from database
    //         $query = $this->db->get('products_option');

    //         if ($query->num_rows() > 0) {
    //             $product_options = $query->result();

    //             foreach ($product_options as $option) {
    //                 $option_data = [
    //                     isset($option->id) ? $option->id : '',
    //                     isset($option->Option_type) ? $option->Option_type : '',
    //                     isset($option->Option_code) ? $option->Option_code : '',
    //                     isset($option->Value) ? $option->Value : ''
    //                 ];

    //                 fputcsv($output, $option_data);
    //             }
    //         }

    //         fclose($output);
    //     } catch (Exception $e) {
    //         // If there's an error, show a simple message
    //         echo "Error: " . $e->getMessage();
    //     }

    //     exit;
    // }

    public function export_newoptions()
    {
        // Set execution limits
        set_time_limit(0);
        ini_set("memory_limit", "12288M");

        try {
            // Header definition (product_id instead of id)
            $headerRow = [
                'product_id',  // <-- renamed column
                'Option_type',
                'Option_code',
                'Value'
            ];

            // Prepare filename
            $filename = 'product_options_' . date('Y-m-d_H-i-s') . '.csv';

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
        $data = $this->db->get('products')->result_array();
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
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');



class NewProduct extends MW_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'file']);
        $this->load->model('Productdata_model');
        $this->load->library('ion_auth');
    }

    public function index()
    {
        $this->load->view('new_product');
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

}

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/third_party/spout/src/Spout/Autoloader/autoload.php';

// Use the Spout Namespaces lets
use Box \ Spout \ Reader \ ReaderFactory;
use Box \ Spout \ Writer \ WriterFactory;
use Box \ Spout \ Common \ Type;

class TestController extends MW_Controller {

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

    

    public function update_sku_products($batchSize = 1000)
    {
        $this->load->database();

        // Increase limits for large data handling
        ini_set('memory_limit', '512M');
        set_time_limit(0);

        while (true) {
            // Fetch next batch where sku is NULL
            $products = $this->db
                ->where('sku IS NULL', null, false)
                ->limit($batchSize)
                ->get('products')
                ->result();

            if (empty($products)) {
                break; // All done
            }

            foreach ($products as $product) {
                $mpn = trim($product->mpn ?? '');
                $sizeRaw = trim($row->size ?? '');
                $size = preg_replace('/[^\d.]/', '', $sizeRaw);

                $weightRaw = trim($row->weight ?? '');
                $weight = preg_replace('/[^\d.]/', '', $weightRaw);

                $middlePart = '';
                if (!empty($size)) {
                    $middlePart = $size;
                } elseif (!empty($weight)) {
                    $middlePart = $weight;
                } else {
                    $middlePart = rand(100, 999);
                }

                $randomSuffix = rand(1000, 9999);
                $sku = 'SKU-' . $mpn . '-' . $middlePart . '-' . $randomSuffix;

                $this->db->where('id', $product->id)->update('products', ['sku' => $sku]);
            }
        }

        echo "SKU update for NULL values completed.";
    }

    public function update_sku_products_pricing($batchSize = 1000)
    {
        $this->load->database();

        ini_set('memory_limit', '512M');
        set_time_limit(0);

        while (true) {
            // Get product_pricings with NULL sku and join product details
            $this->db->select('pp.id as pricing_id, p.mpn, p.size, p.weight')
                ->from('product_pricings as pp')
                ->join('products as p', 'pp.product_id = p.id', 'left')
                ->where('pp.sku IS NULL', null, false)
                ->limit($batchSize);

            $records = $this->db->get()->result();

            if (empty($records)) break;

            foreach ($records as $row) {
                $mpn = trim($row->mpn ?? '');
                $sizeRaw = trim($row->size ?? '');
                $size = preg_replace('/[^\d.]/', '', $sizeRaw);

                $weightRaw = trim($row->weight ?? '');
                $weight = preg_replace('/[^\d.]/', '', $weightRaw);

                $middle = '';
                if (!empty($size)) {
                    $middle = $size;
                } elseif (!empty($weight)) {
                    $middle = $weight;
                } else {
                    $middle = rand(100, 999);
                }

                $sku = 'SKU-' . $mpn . '-' . $middle . '-' . rand(1000, 9999);

                // Update product_pricing sku
                $this->db->where('id', $row->pricing_id)->update('product_pricings', ['sku' => $sku]);
            }
        }

        echo "SKU update completed for product_pricings.";
    }

}
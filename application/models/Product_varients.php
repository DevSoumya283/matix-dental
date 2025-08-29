<?php

class Product_varients extends MY_Model
{

    public $_table = 'skus'; // Correct table name (your variants live here)
    public $primary_key = 'sku_id'; // adjust if your PK is different
    public $fillable = array();
    public $protected = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Memc');
        $this->load->model('PDOhandler');
    }

    // public function get_variant_dropdown($product_id)
    // {
    //     // Step 1: Get matix_id from products
    //     $this->db->select('matix_id');
    //     $this->db->where('id', $product_id);
    //     $product = $this->db->get('products')->row();

    //     if (!$product) {
    //         return [];
    //     }

    //     $matix_id = $product->matix_id;

    //     // Step 2: Join product_option_values + product_options + skus
    //     $this->db->select('po.option_type, po.option_code, pov.value, s.sku_code');
    //     $this->db->from('product_option_values pov');
    //     $this->db->join('product_options po', 'po.option_id = pov.option_id');
    //     $this->db->join('skus s', 's.product_id = pov.product_id');
    //     $this->db->where('pov.product_id', $matix_id);

    //     $this->db->group_by('po.option_type');  
    //     // $this->db->group_by('s.sku_code'); 

    //     return $this->db->get()->result();
    // }

    public function get_variant_dropdown($product_id)
    {
        // Step 1: Get matix_id from products
        $this->db->select('matix_id');
        $this->db->where('id', $product_id);
        $product = $this->db->get('products')->row();

        if (!$product) {
            return [];
        }

        $matix_id = $product->matix_id;

        // Step 2: Join product_option_values + product_options + skus
        $this->db->select('po.option_type, pov.value, s.sku_code, s.price');
        $this->db->from('product_option_values pov');
        $this->db->join('product_options po', 'po.option_id = pov.option_id');
        $this->db->join('skus s', 's.product_id = pov.product_id');
        $this->db->where('pov.product_id', $matix_id);
        // $this->db->group_by('po.option_type, s.sku_code'); // prevent duplicates , s.sku_code
        $this->db->distinct('s.sku_code'); 

        return $this->db->get()->result();
    }
}

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




    // Get options for a product
    // public function get_options_by_product($product_id) {
    //     $this->db->select('*');
    //     $this->db->from('product_options');
    //     $this->db->where('product_id', $product_id);
    //     $query = $this->db->get();
    //     return $query->result_array();
    // }

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

//     // Step 2: Get all variant SKUs with option value
//     $this->db->select('pov.value, s.sku_code, s.price');
//     $this->db->from('product_option_values pov');
//     $this->db->join('skus s', 's.product_id = pov.product_id');
//     $this->db->where('pov.product_id', $matix_id);
//     $this->db->group_by(['pov.value', 's.sku_code']); // each value → sku
//     return $this->db->get()->result();
// }

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

//     // Step 2: Get option values linked to SKUs (unique per value + sku)
//     $this->db->distinct();
//     $this->db->select('pov.value, s.sku_code, s.price');
//     $this->db->from('product_option_values pov');
//     $this->db->join('sku_option_values sov', 'sov.value_id = pov.value_id');
//     $this->db->join('skus s', 's.sku_id = sov.sku_id');
//     $this->db->where('pov.product_id', $matix_id);

//     return $this->db->get()->result();
// }


public function get_product_options($product_id)
{
    // 1. Get matrix_id (family id)
    $this->db->select('matix_id');
    $this->db->where('id', $product_id);
    $product = $this->db->get('products')->row();
    if (!$product) return [];

    $matix_id = $product->matix_id;

    // 2. Get options & values for this family
    $this->db->select('po.option_type, po.option_code, pov.value_id, pov.value');
    $this->db->from('product_options po');
    $this->db->join('product_option_values pov', 'po.option_id = pov.option_id');
    $this->db->where('pov.product_id', $matix_id);
    $this->db->order_by('po.option_type, pov.value');
    $rows = $this->db->get()->result();

    // 3. Group by option_type
    $options = [];
    foreach ($rows as $r) {
        $options[$r->option_type][] = [
            'value_id' => $r->value_id,
            'value'    => $r->value
        ];
    }

    return $options; // ex: ['Color'=>[['value_id'=>1,'value'=>'Red'], ...], 'Size'=>[...] ]
}

public function get_sku_by_values($values = [])
{
    if (empty($values)) {
        return null;
    }

    $this->db->select('s.sku_code, s.price');
    $this->db->from('skus s');
    $this->db->join('sku_option_values sov', 'sov.sku_id = s.sku_id');
    $this->db->where_in('sov.value_id', $values);
    $this->db->group_by('s.sku_id');
    $this->db->having('COUNT(DISTINCT sov.value_id) = '.count($values));
    return $this->db->get()->row();
}


}

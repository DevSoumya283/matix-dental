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


    public function get_product_options($product_id)
    {
        // 1. Get matrix_id
        $this->db->select('matix_id');
        $this->db->where('id', $product_id);
        $product = $this->db->get('products')->row();
        if (!$product) return [];

        $matix_id = $product->matix_id;

        // 2. Get ALL option values linked to SKUs of this family
        $this->db->select('po.option_type, po.option_code, pov.value_id, pov.value');
        $this->db->from('skus s');
        $this->db->join('sku_option_values sov', 's.sku_id = sov.sku_id');
        $this->db->join('product_option_values pov', 'sov.value_id = pov.value_id');
        $this->db->join('product_options po', 'pov.option_id = po.option_id');
        $this->db->where('s.product_id', $matix_id);
        $this->db->order_by('po.option_type, pov.value');
        $rows = $this->db->get()->result();

        // 3. Group by option_type
        $options = [];
        foreach ($rows as $r) {
            $options[$r->option_type][$r->value_id] = [
                'value_id' => $r->value_id,
                'value'    => $r->value
            ];
        }

        // remove duplicates (keep unique values)
        foreach ($options as $k => $vals) {
            $options[$k] = array_values($vals);
        }

        return $options;
    }


    // public function get_sku_by_values($product_id, $values = [])
    // {
    //     if (empty($values)) {
    //         return null;
    //     }

    //     // get matrix_id
    //     $this->db->select('matix_id');
    //     $this->db->where('id', $product_id);
    //     $product = $this->db->get('products')->row();
    //     if (!$product) return null;

    //     $matix_id = $product->matix_id;

    //     $this->db->select('s.sku_id, s.sku_code, s.price, s.retail_price, COUNT(DISTINCT sov.value_id) as matched_count, 
    //                    (SELECT COUNT(*) FROM sku_option_values WHERE sku_id = s.sku_id) as total_options');
    //     $this->db->from('skus s');
    //     $this->db->join('sku_option_values sov', 's.sku_id = sov.sku_id');
    //     $this->db->where('s.product_id', $matix_id);
    //     $this->db->where_in('sov.value_id', $values);
    //     $this->db->group_by('s.sku_id');
    //     $this->db->having('matched_count = ' . count($values));     // must match all selected
    //     $this->db->having('matched_count = total_options');        // must not have extra options

    //     return $this->db->get()->row();
    // }


    public function get_sku_by_values($product_id, $values = [])
    {
        if (empty($values)) {
            return null;
        }

        // get matrix_id
        $this->db->select('matix_id');
        $this->db->where('id', $product_id);
        $product = $this->db->get('products')->row();
        if (!$product) return null;

        $matix_id = $product->matix_id;

        $this->db->select('s.sku_id, s.sku_code, pp.price, pp.retail_price, 
                       COUNT(DISTINCT sov.value_id) as matched_count, 
                       (SELECT COUNT(*) FROM sku_option_values WHERE sku_id = s.sku_id) as total_options');
        $this->db->from('skus s');
        $this->db->join('sku_option_values sov', 's.sku_id = sov.sku_id');
        $this->db->join('product_pricings pp', 'pp.sku = s.sku_code AND pp.matix_id = s.product_id', 'left');
        $this->db->where('s.product_id', $matix_id);
        $this->db->where_in('sov.value_id', $values);
        $this->db->group_by('s.sku_id');
        $this->db->having('matched_count = ' . count($values));     // must match all selected
        $this->db->having('matched_count = total_options');        // must not have extra options

        return $this->db->get()->row();
    }
}

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


    // public function get_product_options($product_id)
    // {
    //     // 1. Get matrix_id
    //     $this->db->select('matix_id');
    //     $this->db->where('id', $product_id);
    //     $product = $this->db->get('products')->row();
    //     if (!$product) return [];

    //     $matix_id = $product->matix_id;

    //     // 2. Get ALL option values linked to SKUs of this family
    //     $this->db->select('po.option_type, po.option_code, pov.value_id, pov.value');
    //     $this->db->from('skus s');
    //     $this->db->join('sku_option_values sov', 's.sku_id = sov.sku_id');
    //     $this->db->join('product_option_values pov', 'sov.value_id = pov.value_id');
    //     $this->db->join('product_options po', 'pov.option_id = po.option_id');
    //     $this->db->where('s.product_id', $matix_id);
    //     $this->db->order_by('po.option_type, pov.value');
    //     $rows = $this->db->get()->result();

    //     // 3. Group by option_type
    //     $options = [];
    //     foreach ($rows as $r) {
    //         $options[$r->option_type][$r->value_id] = [
    //             'value_id' => $r->value_id,
    //             'value'    => $r->value
    //         ];
    //     }

    //     // remove duplicates (keep unique values)
    //     foreach ($options as $k => $vals) {
    //         $options[$k] = array_values($vals);
    //     }

    //     return $options;
    // }


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

    // showing price from pricing table

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

    //     $this->db->select('s.sku_id, s.sku_code, pp.price, pp.retail_price, 
    //                    COUNT(DISTINCT sov.value_id) as matched_count, 
    //                    (SELECT COUNT(*) FROM sku_option_values WHERE sku_id = s.sku_id) as total_options');
    //     $this->db->from('skus s');
    //     $this->db->join('sku_option_values sov', 's.sku_id = sov.sku_id');
    //     $this->db->join('product_pricings pp', 'pp.sku = s.sku_code AND pp.matix_id = s.product_id', 'left');
    //     $this->db->where('s.product_id', $matix_id);
    //     $this->db->where_in('sov.value_id', $values);
    //     $this->db->group_by('s.sku_id');
    //     $this->db->having('matched_count = ' . count($values));     // must match all selected
    //     $this->db->having('matched_count = total_options');        // must not have extra options

    //     return $this->db->get()->row();
    // }

    // showing options => ALL OKAY

//     public function get_product_options($product_id)
//     {
//         // 1. Get matrix_id
//         $this->db->select('matix_id');
//         $this->db->where('id', $product_id);
//         $product = $this->db->get('products')->row();
//         if (!$product) return [];

//         $matix_id = $product->matix_id;

//         // 2. Get ALL option values linked to SKUs of this family
//         $this->db->select('po.option_type, po.option_code, pov.value_id, pov.value');
//         $this->db->from('skus s');
//         $this->db->join('sku_option_values sov', 's.sku_id = sov.sku_id');
//         $this->db->join('product_option_values pov', 'sov.value_id = pov.value_id');
//         $this->db->join('product_options po', 'pov.option_id = po.option_id');
//         $this->db->where('s.product_id', $matix_id);
//         $this->db->order_by('po.option_type, pov.value');
//         $rows = $this->db->get()->result();

//         // 3. Group by option_type
//         $options = [];
//         foreach ($rows as $r) {
//             $options[$r->option_type][$r->value_id] = [
//                 'value_id' => $r->value_id,
//                 'value'    => $r->value
//             ];
//         }

//         // remove duplicates (keep unique values)
//         foreach ($options as $k => $vals) {
//             $options[$k] = array_values($vals);
//         }

//         return $options;
//     }
    
//     public function get_sku_by_values($product_id, $values = [])
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

//     // main query to get SKU + prices
//     $this->db->select('s.sku_id, s.sku_code, pp.price, pp.retail_price, 
//                        COUNT(DISTINCT sov.value_id) as matched_count, 
//                        (SELECT COUNT(*) FROM sku_option_values WHERE sku_id = s.sku_id) as total_options');
//     $this->db->from('skus s');
//     $this->db->join('sku_option_values sov', 's.sku_id = sov.sku_id');
//     $this->db->join('product_pricings pp', 'pp.sku = s.sku_code AND pp.matix_id = s.product_id', 'left');
//     $this->db->where('s.product_id', $matix_id);
//     $this->db->where_in('sov.value_id', $values);
//     $this->db->group_by('s.sku_id');
//     $this->db->having('matched_count = ' . count($values));
//     $this->db->having('matched_count = total_options');

//     $sku = $this->db->get()->row();

//     if (!$sku) {
//         return null;
//     }

//     // fetch option_type:value pairs for this SKU
//     $this->db->select('po.option_type, pov.value');
//     $this->db->from('sku_option_values sov');
//     $this->db->join('product_option_values pov', 'sov.value_id = pov.value_id');
//     $this->db->join('product_options po', 'pov.option_id = po.option_id');
//     $this->db->where('sov.sku_id', $sku->sku_id);
//     $options = $this->db->get()->result();

//     $option_pairs = [];
//     foreach ($options as $opt) {
//         $option_pairs[$opt->option_type] = $opt->value;
//     }

//     // attach options to SKU object
//     $sku->options = $option_pairs;

//     return $sku;
// }


// TODAY

/** Initial options for first render (from in-stock SKUs only) */
    public function get_product_options($product_id)
    {
        $this->db->select('matix_id');
        $this->db->where('id', $product_id);
        $product = $this->db->get('products')->row();
        if (!$product) return [];

        $matix_id = $product->matix_id;

        $this->db->select('po.option_type, po.option_code, pov.value_id, pov.value');
        $this->db->from('skus s');
        $this->db->join('sku_option_values sov', 's.sku_id = sov.sku_id');
        $this->db->join('product_option_values pov', 'sov.value_id = pov.value_id');
        $this->db->join('product_options po', 'pov.option_id = po.option_id');
        $this->db->where('s.product_id', $matix_id);
        $this->db->where('s.stock_quantity >', 0); // only in-stock
        $this->db->order_by('po.option_type, pov.value');
        $rows = $this->db->get()->result();

        $options = [];
        foreach ($rows as $r) {
            $options[$r->option_type][$r->value_id] = [
                'value_id' => $r->value_id,
                'value'    => $r->value
            ];
        }
        foreach ($options as $k => $vals) {
            $options[$k] = array_values($vals); // unique by value_id
        }
        return $options;
    }

    /** Return a concrete SKU iff ALL selected values match one in-stock SKU */
    public function get_sku_by_values($product_id, $values = [])
    {
        if (empty($values)) return null;

        // Ensure array of ints
        $values = array_values(array_unique(array_map('intval', (array)$values)));

        // get matrix + base price
        $this->db->select('matix_id, base_price');
        $this->db->where('id', $product_id);
        $product = $this->db->get('products')->row();
        if (!$product) return null;

        $matix_id = $product->matix_id;

        $this->db->select('s.sku_id, s.sku_code,
            COALESCE(pp.price, '.$this->db->escape($product->base_price).')        AS price,
            COALESCE(pp.retail_price, '.$this->db->escape($product->base_price).') AS retail_price,
            COUNT(DISTINCT sov.value_id) AS matched_count,
            (SELECT COUNT(*) FROM sku_option_values x WHERE x.sku_id = s.sku_id) AS total_options
        ');
        $this->db->from('skus s');
        $this->db->join('sku_option_values sov', 's.sku_id = sov.sku_id');
        $this->db->join('product_pricings pp', 'pp.sku = s.sku_code AND pp.matix_id = s.product_id', 'left');
        $this->db->where('s.product_id', $matix_id);
        $this->db->where('s.stock_quantity >', 0);
        $this->db->where_in('sov.value_id', $values);
        $this->db->group_by('s.sku_id');

        // must contain ALL selected values, and selection must cover ALL options of that SKU
        $this->db->having('matched_count = ' . count($values));
        $this->db->having('matched_count = total_options');

        $sku = $this->db->get()->row();
        if (!$sku) return null;

        // fetch option pairs for this SKU
        $this->db->select('po.option_type, pov.value, pov.value_id');
        $this->db->from('sku_option_values sov');
        $this->db->join('product_option_values pov', 'sov.value_id = pov.value_id');
        $this->db->join('product_options po', 'pov.option_id = po.option_id');
        $this->db->where('sov.sku_id', $sku->sku_id);
        $opts = $this->db->get()->result();

        $option_pairs = [];
        foreach ($opts as $o) {
            $option_pairs[$o->option_type] = ['value' => $o->value, 'value_id' => $o->value_id];
        }
        $sku->options = $option_pairs;
        return $sku;
    }

    /**
     * Return AVAILABLE option values for each option_type that can co-exist
     * with ALL current selections, restricted to in-stock SKUs.
     * If no selection, same as get_product_options().
     */
    public function get_available_options($product_id, $selectedValues = [])
    {
        $selectedValues = array_values(array_unique(array_map('intval', (array)$selectedValues)));

        // matrix id
        $this->db->select('matix_id');
        $this->db->where('id', $product_id);
        $product = $this->db->get('products')->row();
        if (!$product) return [];

        $matix_id = $product->matix_id;

        if (empty($selectedValues)) {
            // No selection yet → all available from in-stock SKUs
            return $this->get_product_options($product_id);
        }

        // Find SKUs that contain ALL selected value_ids (same SKU), and are in stock
        $this->db->select('s.sku_id, COUNT(DISTINCT sov.value_id) AS matched_count');
        $this->db->from('skus s');
        $this->db->join('sku_option_values sov', 's.sku_id = sov.sku_id');
        $this->db->where('s.product_id', $matix_id);
        $this->db->where('s.stock_quantity >', 0);
        $this->db->where_in('sov.value_id', $selectedValues);
        $this->db->group_by('s.sku_id');
        $this->db->having('matched_count = ' . count($selectedValues));
        $skuRows = $this->db->get()->result_array();

        if (empty($skuRows)) return []; // nothing compatible

        $sku_ids = array_map(function($r){ return (int)$r['sku_id']; }, $skuRows);

        // From those SKUs, get all option values that remain possible
        $this->db->select('po.option_type, pov.value_id, pov.value');
        $this->db->from('sku_option_values sov');
        $this->db->join('product_option_values pov', 'sov.value_id = pov.value_id');
        $this->db->join('product_options po', 'pov.option_id = po.option_id');
        $this->db->where_in('sov.sku_id', $sku_ids);
        $this->db->order_by('po.option_type, pov.value');
        $rows = $this->db->get()->result();

        $available = [];
        foreach ($rows as $r) {
            $available[$r->option_type][$r->value_id] = [
                'value_id' => $r->value_id,
                'value'    => $r->value
            ];
        }
        foreach ($available as $k => $vals) {
            $available[$k] = array_values($vals); // unique by value_id
        }
        return $available;
    }


}

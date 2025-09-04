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

        $this->db->select('po.option_type, po.option_code, pov.value_id, pov.value, s.stock_quantity');
        $this->db->from('skus s');
        $this->db->join('sku_option_values sov', 's.sku_id = sov.sku_id');
        $this->db->join('product_option_values pov', 'sov.value_id = pov.value_id');
        $this->db->join('product_options po', 'pov.option_id = po.option_id');
        $this->db->where('s.product_id', $matix_id);
        $this->db->order_by('po.option_type, pov.value');
        $rows = $this->db->get()->result();

        $options = [];
        foreach ($rows as $r) {
            $options[$r->option_type][$r->value_id] = [
                'value_id' => $r->value_id,
                'value'    => $r->value,
                'stock'    => (int)$r->stock_quantity
            ];
        }
        foreach ($options as $k => $vals) {
            $options[$k] = array_values($vals);
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
    // sanitize selected ids
    $selectedValues = array_values(array_unique(array_map('intval', (array)$selectedValues)));

    // 1) get matix_id
    $this->db->select('matix_id');
    $this->db->where('id', $product_id);
    $product = $this->db->get('products')->row();
    if (!$product) return ['all' => [], 'valid' => []];
    $matix_id = $product->matix_id;

    // 2) ALL values for the product family (used to always show all options)
    $this->db->select('po.option_type, pov.value_id, pov.value');
    $this->db->from('skus s');
    $this->db->join('sku_option_values sov', 's.sku_id = sov.sku_id');
    $this->db->join('product_option_values pov', 'sov.value_id = pov.value_id');
    $this->db->join('product_options po', 'pov.option_id = po.option_id');
    $this->db->where('s.product_id', $matix_id);
    $this->db->order_by('po.option_type, pov.value');
    $rows_all = $this->db->get()->result();

    $all = [];
    $valueIdToType = [];
    foreach ($rows_all as $r) {
        $all[$r->option_type][$r->value_id] = [
            'value_id' => (int)$r->value_id,
            'value'    => $r->value
        ];
        $valueIdToType[(int)$r->value_id] = $r->option_type;
    }
    foreach ($all as $k => $vals) {
        $all[$k] = array_values($vals);
    }

    // 3) Map selected values to their option_type (group selected by type)
    $selected_by_type = [];
    if (!empty($selectedValues)) {
        foreach ($selectedValues as $vid) {
            $vid = (int)$vid;
            if (isset($valueIdToType[$vid])) {
                $selected_by_type[$valueIdToType[$vid]][] = $vid;
            } else {
                // fallback lookup if value_id not present in $valueIdToType
                $this->db->select('po.option_type');
                $this->db->from('product_option_values pov');
                $this->db->join('product_options po', 'pov.option_id = po.option_id');
                $this->db->where('pov.value_id', $vid);
                $row = $this->db->get()->row();
                if ($row) $selected_by_type[$row->option_type][] = $vid;
            }
        }
    }

    // 4) For each option_type compute valid values while EXCLUDING the selection(s) of that same option_type.
    $valid = [];
    // initialize keys so we always return same set of keys
    foreach ($all as $type => $vals) {
        $valid[$type] = [];
    }

    foreach ($all as $type => $vals) {
        // filter_values = selectedValues EXCLUDING selections that belong to this $type
        $filter_values = [];
        foreach ($selectedValues as $sv) {
            $sv = (int)$sv;
            if (!empty($selected_by_type[$type]) && in_array($sv, $selected_by_type[$type], true)) {
                // skip (exclude the selection of the same type)
                continue;
            }
            $filter_values[] = $sv;
        }

        // Find SKUs that contain ALL filter_values and are in-stock
        $this->db->select('s.sku_id');
        $this->db->from('skus s');
        $this->db->join('sku_option_values sov', 's.sku_id = sov.sku_id');
        $this->db->where('s.product_id', $matix_id);
        $this->db->where('s.stock_quantity >', 0);

        if (!empty($filter_values)) {
            $this->db->where_in('sov.value_id', $filter_values);
            $this->db->group_by('s.sku_id');
            // ensure the sku contains all filter values
            $this->db->having('COUNT(DISTINCT sov.value_id) = ' . count($filter_values));
        } else {
            // no filter_values => all in-stock SKUs for family
            $this->db->group_by('s.sku_id');
        }

        $skuRows = $this->db->get()->result_array();
        if (empty($skuRows)) {
            $valid[$type] = [];
            continue;
        }
        $sku_ids = array_column($skuRows, 'sku_id');

        // collect values for this option_type from those SKUs
        $this->db->select('DISTINCT pov.value_id, pov.value', FALSE);
        $this->db->from('sku_option_values sov');
        $this->db->join('product_option_values pov', 'sov.value_id = pov.value_id');
        $this->db->join('product_options po', 'pov.option_id = po.option_id');
        $this->db->join('skus s', 'sov.sku_id = s.sku_id');
        $this->db->where_in('sov.sku_id', $sku_ids);
        $this->db->where('po.option_type', $type);
        $this->db->where('s.stock_quantity >', 0);
        $this->db->order_by('pov.value');
        $rows2 = $this->db->get()->result();

        $tmp = [];
        foreach ($rows2 as $r2) {
            $tmp[(int)$r2->value_id] = [
                'value_id' => (int)$r2->value_id,
                'value'    => $r2->value
            ];
        }
        $valid[$type] = array_values($tmp);
    }

    return [
        'all'   => $all,   // all option values for the product family (always shown)
        'valid' => $valid  // option values that are compatible with current selection and in-stock
    ];
}



}

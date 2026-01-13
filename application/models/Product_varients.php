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

    public function skubyProductId($productId = '')
    {
        $this->db->select('*');
        $this->db->from('skus');
        $this->db->where_in('product_id', $productId);
        $query = $this->db->get();

        return $query->result();  
    }

    public function get_highest_quantity_skus_new($products)
    {
        if (empty($products)) {
            return [];
        }

        // Normalize products input
        if (isset($products[0]) && is_array($products[0])) {
            $products = array_column($products, 'id');
        }

        // Fetch matix_ids (STRING values)
        $matix_ids = $this->db
            ->select('matix_id')
            ->from('products')
            ->where_in('id', $products)
            ->get()
            ->result_array();

        $matix_ids = array_column($matix_ids, 'matix_id');

        if (empty($matix_ids)) {
            return [];
        }

        // Properly quote string IDs
        $quoted_ids = array_map(function ($id) {
            return $this->db->escape($id);
        }, $matix_ids);

        $subquery = "
            SELECT product_id, MAX(stock_quantity) AS max_quantity
            FROM skus
            WHERE product_id IN (" . implode(',', $quoted_ids) . ")
            GROUP BY product_id
        ";

        $this->db->select('s.sku_code, s.stock_quantity, s.product_id');
        $this->db->from('skus s');
        $this->db->join(
            "($subquery) max_skus",
            's.product_id = max_skus.product_id
            AND s.stock_quantity = max_skus.max_quantity',
            'inner'
        );
        $this->db->where_in('s.product_id', $matix_ids);

        return $this->db->get()->result();
    }

    public function get_quantity_by_sku($sku='')
    {
        return $this->db
            ->select('stock_quantity')
            ->from('skus')
            ->where('sku_code', $sku)
            ->get()
            ->row();
    }


    public function skuDetails($sku_code = '')
    {
        // Step 1: Get the SKU row
        $this->db->select('*');
        $this->db->where('sku_code', $sku_code);
        $sku = $this->db->get('skus')->row_array();

        if (!$sku) return []; // SKU not found

        // Step 2: Get options for this SKU
        $this->db->select('po.option_type, po.option_code, pov.value_id, pov.value, s.stock_quantity');
        $this->db->from('skus s');
        $this->db->join('sku_option_values sov', 's.sku_id = sov.sku_id');
        $this->db->join('product_option_values pov', 'sov.value_id = pov.value_id');
        $this->db->join('product_options po', 'pov.option_id = po.option_id');
        $this->db->where('s.sku_id', $sku['sku_id']);
        $this->db->order_by('po.option_type, pov.value');
        $option_rows = $this->db->get()->result();

        // Step 3: Format options
        $options = [];
        foreach ($option_rows as $r) {
            $options[$r->option_type][] = [
                'value_id' => $r->value_id,
                'value'    => $r->value,
                'stock'    => (int)$r->stock_quantity
            ];
        }

        // Step 4: Merge options into SKU array
        $sku['options'] = $options;

        return $sku;
    }
    public function get_highest_quantity_skus($products=[]) {
        $matix_ids = array_column($products, 'matix_id');
        $subquery = "SELECT product_id, MAX(stock_quantity) as max_quantity 
                    FROM skus 
                    WHERE product_id IN ('" . implode("','", $matix_ids) . "') 
                    GROUP BY product_id";
        
        $this->db->select('s.sku_code, s.stock_quantity, s.product_id');
        $this->db->from('skus s');
        $this->db->join("($subquery) max_skus", 's.product_id = max_skus.product_id AND s.stock_quantity = max_skus.max_quantity', 'inner');
        $this->db->where_in('s.product_id', $matix_ids);
        
        $query = $this->db->get();
        return $query->result();
    }
    // TODAY

    /** Initial options for first render (from in-stock SKUs only) */
    public function get_product_options($product_id)
    {
        $this->db->select('matix_id');
        $this->db->where('id', $product_id);
        $product = $this->db->get('products')->row();
        if (!$product) return [];

        $matix_id = $product->matix_id;

        $this->db->select('po.option_type, po.option_code, pov.value_id, pov.value, IFNULL(MAX(pp.quantity),0) AS stock_quantity');
        $this->db->from('skus s');
        $this->db->join(
            'product_pricings pp',
            'pp.sku = s.sku_code AND pp.matix_id = s.product_id AND pp.active = 1',
            'left'
        );
        $this->db->join('sku_option_values sov', 's.sku_id = sov.sku_id');
        $this->db->join('product_option_values pov', 'sov.value_id = pov.value_id');
        $this->db->join('product_options po', 'pov.option_id = po.option_id');
        $this->db->where('s.product_id', $matix_id);
        // $this->db->order_by('po.option_type, pov.value');
        $this->db->group_by('po.option_type, pov.value_id');
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

        $this->db->select('s.sku_id, s.sku_code,s.stock_quantity,s.image,
                COALESCE(pp.price, ' . $this->db->escape($product->base_price) . ')        AS price,
                COALESCE(pp.retail_price, ' . $this->db->escape($product->base_price) . ') AS retail_price,
                COUNT(DISTINCT sov.value_id) AS matched_count,
                (SELECT COUNT(*) FROM sku_option_values x WHERE x.sku_id = s.sku_id) AS total_options
            ');
        $this->db->from('skus s');
        $this->db->join('sku_option_values sov', 's.sku_id = sov.sku_id');
        $this->db->join('product_pricings pp', 'pp.sku = s.sku_code AND pp.matix_id = s.product_id', 'left');
        $this->db->where('s.product_id', $matix_id);
        // $this->db->where('s.stock_quantity >', 0);
        $this->db->where('pp.quantity >', 0);
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
        // $this->db->select('po.option_type, pov.value_id, pov.value');
        $this->db->select('
            po.option_type,
            pov.value_id,
            pov.value,
            IFNULL(MAX(pp.quantity), 0) AS stock
        ');
        $this->db->from('skus s');
        $this->db->join(
            'product_pricings pp',
            'pp.sku = s.sku_code AND pp.matix_id = s.product_id AND pp.active = 1',
            'left'
        );
        $this->db->join('sku_option_values sov', 's.sku_id = sov.sku_id');
        $this->db->join('product_option_values pov', 'sov.value_id = pov.value_id');
        $this->db->join('product_options po', 'pov.option_id = po.option_id');
        // $this->db->where('s.product_id', $matix_id);
        $this->db->order_by('po.option_type, pov.value');
        $this->db->group_by('po.option_type, pov.value_id');
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
            $this->db->join(
            'product_pricings pp',
            'pp.sku = s.sku_code AND pp.matix_id = s.product_id AND pp.active = 1',
            'inner'
            );

            $this->db->where('s.product_id', $matix_id);
            // $this->db->where('s.stock_quantity >', 0);
            $this->db->where('pp.quantity >', 0);

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
            // $this->db->select('DISTINCT pov.value_id, pov.value', FALSE);
            $this->db->select('
                pov.value_id,
                pov.value,
                MAX(pp.quantity) AS stock
            ', FALSE);
            $this->db->from('sku_option_values sov');
            $this->db->join('product_option_values pov', 'sov.value_id = pov.value_id');
            $this->db->join('product_options po', 'pov.option_id = po.option_id');
            $this->db->join('skus s', 'sov.sku_id = s.sku_id');
            $this->db->join(
            'product_pricings pp',
            'pp.sku = s.sku_code AND pp.matix_id = s.product_id AND pp.active = 1',
            'inner'
            );
            $this->db->where_in('sov.sku_id', $sku_ids);
            $this->db->where('po.option_type', $type);
            // $this->db->where('s.stock_quantity >', 0);
            $this->db->where('pp.quantity >', 0);
            $this->db->group_by('pov.value_id');
            $this->db->order_by('s.sku_id');
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


    // THIS IS FOR MODEL 

    public function get_sku_by_values_for_model($product_id, $values = [])
    {
        // Ensure array of ints
        $values = array_values(array_unique(array_map('intval', (array)$values)));

        // get matrix + base price
        $this->db->select('matix_id, base_price');
        $this->db->where('id', $product_id);
        $product = $this->db->get('products')->row();
        if (!$product) return null;

        $matix_id = $product->matix_id;

        // if no values selected → no specific SKU yet
        if (empty($values)) {
            return null;
        }

        $this->db->select('s.sku_id, s.stock_quantity, s.sku_code,s.image,
            COALESCE(pp.price, ' . $this->db->escape($product->base_price) . ')        AS price,
            COALESCE(pp.retail_price, ' . $this->db->escape($product->base_price) . ') AS retail_price,
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
            $option_pairs[$o->option_type] = [
                'value'    => $o->value,
                'value_id' => $o->value_id
            ];
        }
        $sku->options = $option_pairs;

        return $sku;
    }

    public function get_available_options_for_model($product_id, $selectedValues = [])
    {
        $selectedValues = array_values(array_unique(array_map('intval', (array)$selectedValues)));

        // 1) get matix_id
        $this->db->select('matix_id');
        $this->db->where('id', $product_id);
        $product = $this->db->get('products')->row();
        if (!$product) return ['all' => [], 'valid' => []];
        $matix_id = $product->matix_id;

        // 2) ALL options (regardless of stock)
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

        // 3) Group selected values by type
        $selected_by_type = [];
        foreach ($selectedValues as $vid) {
            if (isset($valueIdToType[$vid])) {
                $selected_by_type[$valueIdToType[$vid]][] = $vid;
            }
        }

        // 4) VALID options (must exist in at least one in-stock SKU)
        $valid = [];
        foreach ($all as $type => $vals) {
            $filter_values = [];
            foreach ($selectedValues as $sv) {
                if (!empty($selected_by_type[$type]) && in_array($sv, $selected_by_type[$type], true)) {
                    continue;
                }
                $filter_values[] = (int)$sv;
            }

            $this->db->select('s.sku_id, s.stock_quantity');
            $this->db->from('skus s');
            $this->db->join('sku_option_values sov', 's.sku_id = sov.sku_id');
            $this->db->where('s.product_id', $matix_id);
            $this->db->where('s.stock_quantity >', 0);

            if (!empty($filter_values)) {
                $this->db->where_in('sov.value_id', $filter_values);
                $this->db->group_by('s.sku_id');
                $this->db->having('COUNT(DISTINCT sov.value_id) = ' . count($filter_values));
            } else {
                $this->db->group_by('s.sku_id');
            }

            $skuRows = $this->db->get()->result_array();
            if (empty($skuRows)) {
                $valid[$type] = [];
                continue;
            }
            $sku_ids = array_column($skuRows, 'sku_id');

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
            'all'   => $all,
            'valid' => $valid
        ];
    }


    /**
 * Return the first in-stock SKU for the product family (with prices and option pairs),
 * or null if none found.
 */
    public function get_first_instock_sku_for_model($product_id)
    {
        // get matix_id + base_price
        $this->db->select('matix_id, base_price');
        $this->db->where('id', $product_id);
        $product = $this->db->get('products')->row();
        if (!$product) return null;

        $matix_id = $product->matix_id;

        // find first in-stock SKU (order by sku_id asc — change if you want other ordering)
        $this->db->select('s.sku_id, s.stock_quantity, s.sku_code,
            COALESCE(pp.price, '.$this->db->escape($product->base_price).')        AS price,
            COALESCE(pp.retail_price, '.$this->db->escape($product->base_price).') AS retail_price,
            s.stock_quantity
        ');
        $this->db->from('skus s');
        $this->db->join('product_pricings pp', 'pp.sku = s.sku_code AND pp.matix_id = s.product_id', 'left');
        $this->db->where('s.product_id', $matix_id);
        $this->db->where('s.stock_quantity >', 0);
        $this->db->order_by('s.sku_id', 'ASC');
        $this->db->limit(1);

        $sku = $this->db->get()->row();
        if (!$sku) return null;

        // fetch option pairs for this SKU (option_type => { value, value_id })
        $this->db->select('po.option_type, pov.value, pov.value_id');
        $this->db->from('sku_option_values sov');
        $this->db->join('product_option_values pov', 'sov.value_id = pov.value_id');
        $this->db->join('product_options po', 'pov.option_id = po.option_id');
        $this->db->where('sov.sku_id', $sku->sku_id);
        $opts = $this->db->get()->result();

        $option_pairs = [];
        foreach ($opts as $o) {
            // If multiple values of same option_type for a SKU — last wins (shouldn't happen normally)
            $option_pairs[$o->option_type] = [
                'value'    => $o->value,
                'value_id' => $o->value_id
            ];
        }
        $sku->options = $option_pairs;

        return $sku;
    }




}

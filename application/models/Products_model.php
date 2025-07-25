<?php

class Products_model extends MY_Model {

    public $_table = 'products'; // you MUST mention the table name
    public $primary_key = 'id'; // you MUST mention the primary key
    public $fillable = array(); // If you want, you can set an array with the fields that can be filled by insert/update
    public $protected = array(); // ...Or you can set an array with the fields that cannot be filled by insert/update

    public function __construct() {
        parent::__construct();
    }

    public function record_count() {
        return $this->db->count_all("products");
    }

    public function check_rating($products, $rating) {
        $output = [];
        for ($i = 0; $i < count($products); $i++) {
            if ($products[$i]->average_rating >= $rating) {
                $output[] = $products[$i];
            }
        }
        return $output;
    }

    public function check_license($products, $required) {
        $output = [];
        for ($i = 0; $i < count($products); $i++) {
            if ($required == "Yes") {
                if ($products[$i]->license_required != "Yes") {
                    $output[] = $products[$i];
                }
            } else {
                if ($products[$i]->license_required == "Yes") {
                    $output[] = $products[$i];
                }
            }
        }
        return $output;
    }

    static function cmp($a, $b) {
        return strcmp($a->manufacturer, $b->manufacturer);
    }

    private function array_sort($array, $on, $order = SORT_ASC) {

        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }

    public function check_purchased($products) {
        $output = [];
        $ordered_items = [];
        if (isset($_SESSION["user_id"])) {
            $user_id = $_SESSION["user_id"];
            $data['orders'] = $this->Order_model->get_many_by(array('user_id' => $user_id, 'restricted_order' => '0', 'order_status !=' => 'Cancelled'));
            foreach ($data['orders'] as $key) {
                $data['order_items'] = $this->Order_items_model->get_many_by(array('order_id' => $key->id));
                foreach ($data['order_items'] as $row) {
                    $ordered_items[] = $row->product_id;
                }
            }
        }
        for ($i = 0; $i < count($products); $i++) {
            if (in_array($products[$i]->id, $ordered_items)) {
                $output[] = $products[$i];
            }
        }
        return $output;
    }

    private function array_orderby() {
        $args = func_get_args();
        $data = array_shift($args);
        foreach ($args as $n => $field) {
            if (is_string($field)) {
                $tmp = array();
                foreach ($data as $key => $row)
                    $tmp[$key] = $row[$field];
                $args[$n] = $tmp;
            }
        }
        $args[] = &$data;
        call_user_func_array('array_multisort', $args);
        return array_pop($args);
    }


    public function get_products($limit, $start) {
        $this->db->limit($limit, $start);
        $query = $this->db->get('products');
        return ($query->num_rows() > 0) ? $query->result() : FALSE;
    }

    public function getRelatedProducts($productId, $categoryId, $vendorId = null)
    {
        $sql = "SELECT p.*
                FROM products AS p
                JOIN product_pricings AS pp
                    ON p.id = pp.product_id
                JOIN vendors AS v
                    ON pp.vendor_id = v.id
                WHERE p.category_id LIKE '%\"$categoryId\"%'
                ";

        if($vendorId && $this->config->item('limit_to_vendor_products')){
            $sql .= "AND pp.vendor_id = $vendorId
                    ";
        }

        if(!empty($this->config->item('whitelabel')->vendor_id)){
            $sql .= "AND p.id NOT IN (
                        SELECT pp.product_id
                        FROM product_pricings AS pp
                        JOIN product_site_exclusion AS pse
                            ON pse.product_pricing_id = pp.id
                        WHERE pse.vendor_id = " . $this->config->item('whitelabel')->vendor_id .
                        " AND pse.store_id = 0
                    )";
        }
        $sql .= "AND p.id != $productId
                AND p.active = 1
                ORDER BY RAND()
                LIMIT 0,16
                ";


        // Debugger::debug($sql);
        $relatedProducts = $this->db->query($sql)->result();

        return $relatedProducts;
    }

    public function getProductPricings($productId)
    {
        $sql = "SELECT *
                FROM product_pricings WHERE product_id = $productId";

        $pricings = $this->db->query($sql)->result();


        return $pricings;
    }

    public function searchProducts($category = null, $manufacturer = null, $vendorId = null, $procedure = null, $listId = null, $licenseRequired = null, $purchased = null, $start, $perPage)
    {
        $userLocations = [];
        foreach($_SESSION['userLocations'] as $location){
            $userLocations[] = $location->id;
        }
        Debugger::debug($this->config->item('whitelabel'));
        $sql = "SELECT SQL_CALC_FOUND_ROWS
                    p.*,
                    pp.retail_price, pp.price, pp.vendor_name, pp.retail_price AS product_price
                FROM products AS p
                JOIN product_category AS pc
                    ON p.category_id = pc.category_id
                JOIN (
                    SELECT p1.product_id, v.name as vendor_name, v.id AS vendor_id,
                        GREATEST(MAX(p1.retail_price), MAX(p1.price)) AS retail_price,
                        LEAST(MIN(p1.retail_price), MIN(p1.price)) AS price
                    FROM product_pricings AS p1
                    JOIN vendors AS v
                        ON v.id = p1.vendor_id
                    WHERE p1.active = 1
                    ";

        if($this->config->item('whitelabel') && $this->config->item('whitelabel')->limit_to_vendor_products && empty($procedure)){
            $sql .= "AND p1.vendor_id = " . $this->config->item('whitelabel')->vendor_id;
        }

        $sql .= "    GROUP BY p1.product_id
                ) AS pp
                    ON pp.product_id = p.id
                ";

        if(!empty($userLocations)){
            $sql .= "LEFT JOIN (
                        SELECT DISTINCT(oi.product_id)
                        FROM orders AS o
                        JOIN order_items AS oi
                            ON o.id = oi.order_id
                        WHERE o.location_id IN (" . implode(',', $userLocations) . ")
                    ) AS oi_ids
                        ON oi_ids.product_id = p.id
                    ";
        }

        $sql .= "WHERE 1 = 1
                ";

        if ($category != null) { //Filter by browse categories dropdown
            $sql .= "AND pc.category_id = $category
                    ";
        } else if ($manufacturer != null) { //Filter based on manufacturer from browse dropdown
            $sql .= "AND p.manufacturer LIKE '%" . $manufacturer . "%'
                    ";
        } else if ($vendorId != null) { //get seletced vendor products from browse dropdown
            $sql .= "AND pp.vendor_id = " . $vendorId . "
                    ";
        } else if ($procedure != null) { //Filter based on product procedure from browse dropdown
            $sql .= "AND p.product_procedures LIKE '%" . $procedure . "%'
                    ";
        } else if ($listId != null) { //Filter based on shopping lists from browse dropdown
            $data['product_list'] = $this->Prepopulated_list_model->get_by(['id' => $listId]);
            $data['pre_data'] = $this->Prepopulated_product_model->get_many_by(array('list_id' => $listId));
            $list_products = [];
            foreach ($data['pre_data'] as $key) {
                $list_products[] = $key->product_id;
            }
            $in_products = join(",", $list_products);
            $sql .= "AND p.id IN (" . $in_products . ")
                    ";
        }

        if (!empty($licenseRequired) && $licenseRequired == 'Yes'){
            $sql .= "AND p.license_required != 'yes'
                    ";
        }
        if(!empty($purchased)){
            $sql .= "AND oi_ids.product_id IS NOT NULL
                    ";
        }
        $sql .= "AND p.active = 1
                ";

                // site exclusion
        // $sql .= "AND "
        //Sort
        $option = $this->input->get("option_value");
        //$start = $per_page * ($page - 1);
        if ($option != null) {
            if ($option == "price") { // sorting on price by ascending
                $sql .= " ORDER BY pp.retail_price ASC
                        ";
            }
            if ($option == "vendor") { // sorting on vendor name by ascending
                $sql .= " ORDER BY vendor_name ASC
                        ";
            }
            if ($option == "mfc") { // sorting on manufacturer name by ascending
                $sql .= " ORDER BY p.manufacturer ASC
                        ";
            }
        }

        $sql .= " LIMIT $start, $perPage";
        Debugger::debug($sql);
        $products = $this->db->query($sql)->result();
        $totalProducts = $this->db->query('SELECT FOUND_ROWS() AS count')->result()[0]->count;

        return [$products, $totalProducts];
    }
    //2025
    public function get_all_products_with_prices()
    {
        $this->db->select('products.*, product_pricings.price, product_pricings.vendor_id, product_pricings.retail_price, product_pricings.active');
        $this->db->from('products');
        $this->db->join('product_pricings', 'products.mpn = product_pricings.vendor_product_id', 'left');
        $this->db->group_by('products.id'); 
        $this->db->order_by('products.id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }


}

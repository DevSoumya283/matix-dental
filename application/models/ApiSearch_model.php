<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Library to perforn full text searches (FTS)
 */
class ApiSearch_model extends MY_Model
{
    public function run($category,
                        $manufacturer,
                        $vendorId,
                        $procedure,
                        $listId,
                        $licenseRequired,
                        $purchased,
                        $searchString = null,
                        $start = 0,
                        $perPage = 10,
                        $option = null)
    {
        // Debugger::debug($start . ':' .$perPage);
        // Debugger::debug($listId, '$listId');
        if(empty($searchString)){
            //return false;
        }
        if($option == 'undefined'){
            $option = null;
        }
        $sql = "SELECT SQL_CALC_FOUND_ROWS
                    p.id, p.name, p.description, p.manufacturer, p.license_required,
                    i.photo,
                    pp.retail_price, pp.price, pp.vendor_name, pp.retail_price AS product_price, pp.vendor_count, pp.vendor_id
                ";
        if(!empty($searchString)) {
            $sql .= ", IF(
                               p.name LIKE '$searchString%',  20, IF(p.name LIKE '%$searchString%', 10, 0)
                        )
                        + IF(`description` LIKE '%$searchString%', 5,  0)
                        AS `weight`,
                        i.photo
                    ";
        }

        $sql .= "FROM products AS p
                JOIN (
                    SELECT DISTINCT p1.product_id, v.name as vendor_name, v.id AS vendor_id,
                        GREATEST(MAX(p1.retail_price), MAX(p1.price)) AS retail_price,
                        LEAST(MIN(p1.retail_price), MIN(p1.price)) AS price,
                        count(v.id) AS vendor_count
                    FROM product_pricings AS p1
                    JOIN vendors AS v
                        ON v.id = p1.vendor_id
                    WHERE p1.active = 1
                    ";
        if ($vendorId != null) { //get seletced vendor products from browse dropdown
            $sql .= "AND p1.vendor_id = " . $vendorId . "
                    ";
        }
        if($this->config->item('whitelabel') && $this->config->item('whitelabel')->limit_to_vendor_products && empty($procedure)){
            $sql .= "AND p1.vendor_id = " . $this->config->item('whitelabel')->vendor_id;
        }
        $sql .= "    GROUP BY p1.product_id
                ) AS pp
                    ON pp.product_id = p.id
                ";
        if($purchased == 'Yes'){
            $sql .= "JOIN (
                        SELECT DISTINCT oi.product_id
                        FROM orders AS o
                        JOIN order_items AS oi
                            ON o.id = oi.order_id
                        WHERE o.user_id = " . $_SESSION['user_id'] . "
                    ) as ip ON ip.product_id = p.id
                    ";
        }
        $sql .= "LEFT JOIN (
                    SELECT id, model_id, photo
                    FROM images
                    WHERE image_type = 'mainimg'
                    GROUP BY model_id
                    ORDER BY id DESC
                ) AS i ON p.id = i.model_id
            ";
        if(!empty($listId)){
            $sql .= "JOIN prepopulated_products AS pr_pr
                        ON p.id = pr_pr.product_id
                    ";
        }

        $sql .= "WHERE p.active = 1
                ";
        if(!empty($searchString)) {
            $sql .= "AND (p.name LIKE '%$searchString%' OR p.name LIKE '%$searchString%'
                    OR p.description LIKE '%$searchString%'
                    OR p.mpn LIKE '%$searchString%')

                    ";
        }
        if ($category != null) { //Filter by browse categories dropdown
            $sql .= "AND p.category_id LIKE '%\"$category\"%'
                    ";
        } else if ($manufacturer != null) { //Filter based on manufacturer from browse dropdown
            $sql .= "AND p.manufacturer LIKE '%" . $manufacturer . "%'
                    ";
        } else if ($procedure != null) { //Filter based on product procedure from browse dropdown
            $sql .= "AND p.product_procedures LIKE '%" . $procedure . "%'
                    ";
        } else if(!empty($listId)){
            $sql .= "AND pr_pr.list_id = " . $listId . "
                    ";
        }
        // Debugger::debug($licenseRequired, '$licenseRequired');
        if($licenseRequired == 'Yes'){
            $sql .= "AND p.license_required != 'Yes'
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
        //Sort
        //$option = $this->input->get("option_value");
        //$start = $per_page * ($page - 1);
        if(!empty($option) || !empty($searchString)){
            $sql .= "ORDER BY ";
            if (!empty($option)) {
                // Debugger::debug($option, '$option');
                if ($option == "price") { // sorting on price by ascending
                    $sql .= "pp.retail_price ASC";
                }
                if ($option == "mfc") { // sorting on manufacturer name by ascending
                    $sql .= "p.manufacturer ASC";
                }
            }

            if(!empty($searchString)) {
                if(!empty($option)){
                    $sql .= ", ";
                }
                $sql .= "weight DESC
                            ";
            }
        }

        $sql .= " LIMIT $start, $perPage";
        // Debugger::debug($sql);

        $products = $this->db->query($sql)->result();

        $ids = [];
        foreach($products as $product){
            $ids[] = $product->id;
        }

        Debugger::debug($sql);
        $totalProducts = $this->db->query('SELECT FOUND_ROWS() AS count')->result()[0]->count;
        // Debugger::debug($products);
        return ['results' => $products, 'totalResults' => $totalProducts, 'productIds' => $ids];
    }
}
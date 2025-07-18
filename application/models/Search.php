<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Library to perforn full text searches (FTS)
 */
class ApiSearch extends MY_Model
{
    public function run($searchString = null)
    {
        if(empty($searchString)){
            return false;
        }

        $sql = "SELECT SQL_CALC_FOUND_ROWS
                    p.*,
                    pp.retail_price, pp.price, pp.vendor_name, pp.retail_price AS product_price
                FROM products AS p
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

        $sql .= "WHERE 1 = 1
                 AND p.active = 1
                 ";

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


    }
}
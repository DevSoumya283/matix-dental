<?php

class Product_pricing_model extends MY_Model {

    public $_table = 'product_pricings'; // you MUST mention the table name
    public $primary_key = 'id'; // you MUST mention the primary key
    public $fillable = array(); // If you want, you can set an array with the fields that can be filled by insert/update
    public $protected = array(); // ...Or you can set an array with the fields that cannot be filled by insert/update

    public function __construct() {
        parent::__construct();
        $this->load->model('Memc');
        $this->load->model('PDOhandler');
    }

    /**
     *      Order By
     *          It is not the Best method to order_by but its a method to do it so.
     *          i.    $field is to define the field in the table to order by.
     *          ii.   $flow is to define its for ASC or DESC
     */
    public function productPrice_order_by($field, $flow) {
        $table_name = 'product_pricings';
        $order = 'price';
        echo $field;
        exit;
        if ($flow == 0) {
            $this->db->order_by($order, 'ASC');
        } else {
            $this->db->order_by($order, 'DESC');
        }
        $this->db->where('vendor_id', $field);
        $result = $this->db->get($table_name);
        return $result;
    }

    public function toggleProductPricingActive($productPricingId, $value = 0)
    {
        $params = [':productPricingId' => $productPricingId,
                   ':value' => $value];

        if($productPricingId){
            Debugger::debug('setting '.$field.' to '.$value.' for '.$productPricingId);

            $sql = "UPDATE product_pricings
                    SET active = :value,
                        updated_at = CURRENT_TIMESTAMP
                    WHERE id = :productPricingId
                    ";

            $this->PDOhandler->query($sql, $params);

            return true;
        } else {
            return false;
        }
    }

    public function toggleProductDisplay($productPricingId, $vendorId, $storeId = 0)
    {
        $params = [
            ':productPricingId' => $productPricingId,
            ':vendorId' => $vendorId,
            ':storeId' => (empty($storeId)) ? 0 : $storeId
        ];
        Debugger::debug($_POST);
        Debugger::debug($params);

        // die(var_dump($_POST));
        // exit;

        $sql = "INSERT INTO product_site_exclusion (
                    product_pricing_id, vendor_id, store_id
                ) VALUES (
                    :productPricingId, :vendorId, :storeId
                ) ON DUPLICATE KEY UPDATE
                    to_delete = 1;
                ";

        $this->PDOhandler->query($sql, $params);
    }

    public function cleanProductDisplay()
    {
        $sql = "DELETE FROM product_site_exclusion
                WHERE to_delete = 1";

        $this->PDOhandler->query($sql, $params);
    }

    public function getBestPrice($productId)
    {
        $sql = "SELECT *
                FROM product_pricings
                WHERE product_id = $productId
                AND price = (
                    SELECT MIN(price)
                    FROM product_pricings
                    WHERE product_id = $productId
                    AND price > 0
                )
                AND active = 1
                ";

        $priceInfo = $this->db->query($sql)->result();
        // Debugger::debug($pricing);
        return $priceInfo;
    }

    public function getPrices($productId)
    {
        $sql = "SELECT *
                FROM product_pricings AS pp
                WHERE pp.product_id = $productId
                AND pp.active = 1
                ";

        $prices = $this->db->query($sql)->result();

        return $prices;
    }

    public function getPricesMarketplace($productId)
    {
        $sql = "SELECT *
                FROM product_pricings AS pp
                WHERE pp.product_id = $productId
                AND pp.active = 1
                AND pp.exclude_from_marketplace = 0
                ";

        $prices = $this->db->query($sql)->result();

        return $prices;
    }


    public function applyVendorDiscount($priceInfo, $vendorId)
    {
        if(!empty($_SESSION['vendorDiscounts'][$vendorId])){

        }

        return $priceInfo;
    }

    public function getAllPrices($ids)
    {
        $in = "";
        foreach ($ids as $i => $item)
        {
            $key = ":id".$i;
            $in .= " :id" . $i . ",";
            $in_params[$key] = $item; // collecting values into key-value array
        }
        $in = rtrim($in,","); // :id0,:id1,:id2

        $sql = "SELECT p.*
                FROM product_pricings AS p
                LEFT JOIN buying_club_product_pricing AS bcp
                    ON bcp.product_id = p.product_id
                LEFT JOIN vendors AS v
                    on p.vendor_id = v.id
                WHERE p.active = 1
                and v.active = 1
                and p.product_id IN ($in)
                GROUP BY p.id";

        Debugger::debug($sql);
        return $this->PDOhandler->query($sql, $in_params);
    }

    public function fixPrices($prices)
    {

    }
}

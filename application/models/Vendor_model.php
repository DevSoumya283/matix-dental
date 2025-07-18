<?php

class Vendor_model extends MY_Model {

    public $_table = 'vendors'; // you MUST mention the table name
    public $primary_key = 'id'; // you MUST mention the primary key
    public $fillable = array(); // If you want, you can set an array with the fields that can be filled by insert/update
    public $protected = array(); // ...Or you can set an array with the fields that cannot be filled by insert/update

    public function __construct() {
        parent::__construct();
        $this->load->model('Memc');
        $this->load->model('PDOhandler');
        $this->load->helper('MY_privilege_helper');
        $this->load->helper('MY_support_helper');
        $this->load->helper('my_email_helper');
    }

    public function phone_validation($data) {

        $this->form_validation->set_rules('phone', 'phone', 'trim|required');

        if ($this->form_validation->run() === False) {
            $result['status'] = "0";
            $result['error'] = validation_errors();
        } else {
            $res = $this->vendor_model->get_by(array('phone' => $data['phone']));
            if ($res == null) {
                $result['status'] = "0";
                $result['message'] = "Phone Number does not Exist";
            } else {
                $result['status'] = "1";
                $result['user_id'] = $res->id;
                $result['message'] = "Logged in successfully";
            }
        }
        return $result;
    }

    public function loadProducts($vendorId = null, $promos = null, $categorySelect = null, $productStatus = null, $orderBy, $limit, $offset, $pricingScaleId = null, $siteId = 0)
    {
        switch ($orderBy) {
            case 1:
                $orderBy = ' pp.price asc';
                break;
            case 2:
                $orderBy = ' pp.price desc';
                break;
            default:
                $orderBy = ' p.name asc';
        }

        $sql = "SELECT p.id, p.name, p.item_code,
                       pp.id as pricing_id, pp.vendor_id, pp.vendor_product_id, pp.price, pp.retail_price,
                       pp.exclude_from_marketplace, pp.active as status, pc.active,
                       bcpp.sale_price AS scale_price,
                       pse.product_pricing_id AS hidden_id
                FROM products as p
                JOIN product_pricings as pp
                    ON p.id=pp.product_id
                LEFT JOIN ( SELECT product_pricing_id, 1
                            FROM product_site_exclusion
                            WHERE vendor_id = :vendorId
                            AND store_id = :siteId ) AS pse
                    ON pse.product_pricing_id = pp.id
                LEFT JOIN ( SELECT product_id, sale_price
                            FROM pricing_scale_product_pricing
                            WHERE vendor_id = :vendorId
                            AND pricing_scale_id = :pricingScaleId ) AS bcpp
                    ON bcpp.product_id = p.id
                ";

        if ($promos != null && $promos != "") {
            if ($promos == 2) {
                $sql .= " INNER JOIN promo_codes as pc ON p.id = pc.product_id and pp.vendor_id = pc.vendor_id
                ";
            } else {
                $sql .= " LEFT JOIN promo_codes as pc ON p.id = pc.product_id and pp.vendor_id = pc.vendor_id
                ";
            }
        } else {
            $sql .= " LEFT JOIN promo_codes as pc ON p.id = pc.product_id and pp.vendor_id = pc.vendor_id
            ";
        }

        $params = [':orderBy' => $orderBy];

        if ($vendorId != null && $vendorId != "") {
            $params[':vendorId'] = $vendorId;
            $params[':pricingScaleId'] = $pricingScaleId;
            $sql .= " WHERE pp.vendor_id = :vendorId
                    ";
        }
        if ($categorySelect != null && $categorySelect != "") {
            $params[':categorySelect'] = '%"' . $categorySelect . '"%';
            $sql .= " AND p.category_id like :categorySelect
                    ";
        }
        if ($productStatus != null && $productStatus != "") {
            $params[':productStatus'] = $productStatus;
            $sql .= " AND pp.active = :productStatus
                    ";
        }
        $params[':siteId'] = (!empty($siteId)) ? $siteId : 0;

        $sql .= "ORDER BY $orderBy
                ";

        if($limit != 'all'){
            $sql .= " LIMIT " . (int) $limit . " OFFSET " . (int) $offset;
        }

        $products = $this->PDOhandler->query($sql, $params, 'fetchAll');

        return $products;
    }

    public function searchProducts($vendorId, $search, $orderBy, $limit, $offset, $pricingScaleId = null)
    {
        if ($orderBy != null) {
            if ($orderBy == 0) {
                $orderBy = ' p.name asc';
            }
            if ($orderBy == 1) {
                $orderBy = ' pp.price asc';
            }
            if ($orderBy == 2) {
                $orderBy = ' pp.price desc';
            }
        } else {
            $orderBy = ' p.name asc';
        }

        $sql = "SELECT pp.id, pp.vendor_id, pp.vendor_product_id, pp.price, pp.retail_price, pp.exclude_from_marketplace, bcpp.sale_price AS scale_price, pp.active as status,
                    p.name, p.item_code, pc.active
                FROM products as p
                LEFT JOIN product_pricings as pp
                    ON p.id=pp.product_id
                LEFT JOIN promo_codes as pc
                    ON p.id = pc.product_id and pp.vendor_id = pc.vendor_id
                LEFT JOIN ( SELECT product_id, sale_price
                            FROM pricing_scale_product_pricing
                            WHERE vendor_id = :vendorId
                            AND pricing_scale_id = :pricingScaleId ) AS bcpp
                ON bcpp.product_id = p.id
                WHERE (p.name like :search or p.mpn like :search or pp.vendor_product_id like :search or p.description like :search)
                AND pp.vendor_id = :vendorId
                ORDER BY $orderBy
                LIMIT " . (int) $limit . " OFFSET " . (int) $offset;

        $params = [
            ':pricingScaleId' => $pricingScaleId,
            ':search' => '%' . $search . '%',
            ':vendorId' => $vendorId,
            ':orderBy' => $orderBy,
            ':limit' => (int) $limit,
            ':offset' => (int) $offset
        ];

        $products = $this->PDOhandler->query($sql, $params, 'fetchAll');

        return $products;
    }

    public function getSearchTotalCount($vendorId, $search, $orderBy)
    {

        $countSql = "SELECT count(*) as count
                    FROM products as p
                    LEFT JOIN product_pricings as pp
                       ON p.id=pp.product_id
                    LEFT JOIN promo_codes as pc
                        ON p.id = pc.product_id and pp.vendor_id = pc.vendor_id
                    WHERE (p.name like :search or p.mpn like :search or pp.vendor_product_id like :search or p.description like :search)
                AND pp.vendor_id = :vendorId";

        $params = [
            ':search' => '%' . $search . '%',
            ':vendorId' => $vendorId
        ];

        $result = $this->PDOhandler->query($countSql, $params, 'fetch');

        return $result->count;
    }

    public function getAllSummary()
    {
        $this->db->select('id, name')->from('vendors');

        return $this->db->get()->result();
    }

    public function loadVendorPricings($productId)
    {
        $params = [':productId' => $productId];

        $sql = "SELECT pp.price, pp.vendor_id, pp.retail_price, v.name, pc.title, pc.conditions, so.shipping_price, so.shipping_type, vp.policy_name
                FROM product_pricings pp
                LEFT JOIN vendors v
                    ON pp.vendor_id = v.id
                LEFT JOIN promo_codes pc
                    ON pp.product_id = pc.product_id
                LEFT JOIN vendor_policies vp
                    ON v.id = vp.vendor_id
                LEFT JOIN shipping_options so
                    ON v.id = so.vendor_id
                WHERE pp.product_id = :productId
                AND v.active = 1
                AND pp.active = 1
                ";

        if(!empty($this->config->item('whitelabel_vendor_id'))){
            $sql .= "AND pp.vendor_id = :vendorId
                    ";
            $params[':vendorId'] = $this->config->item('whitelabel_vendor_id');
        } else {
            $sql .= "AND CONCAT(v.id, '-', 0, '-', pp.product_id) NOT IN (
                            SELECT DISTINCT CONCAT(pse.vendor_id, '-', 0, '-', pp.product_id)
                            FROM product_site_exclusion AS pse
                            JOIN product_pricings AS pp
                                ON pp.id = pse.product_pricing_id
                        )
                    ";
            // $sql .= "AND pp.exclude_from_marketplace = 0
                    // ";
        }


        $sql .= "GROUP BY pp.price, v.id";

        $result = $this->PDOhandler->query($sql, $params, 'fetchAll');
        return $result;
    }

}

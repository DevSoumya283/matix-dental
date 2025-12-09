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

    public function loadProducts_old($vendorId = null, $promos = null, $categorySelect = null, $productStatus = null, $orderBy, $limit, $offset, $pricingScaleId = null, $siteId = 0)
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

        $sql = "SELECT DISTINCT p.id, p.name, p.matix_id, p.item_code,
                    pp.id as pricing_id, pp.vendor_id, pp.vendor_product_id, 
                    COALESCE(pp.price, p.base_price) AS price,
                    COALESCE(pp.retail_price, p.base_price) AS retail_price,
                    pp.exclude_from_marketplace, pp.active as status, 
                    bcpp.sale_price AS scale_price,
                    pse.product_pricing_id AS hidden_id,
                    sku_max.sku_code, sku_max.stock_quantity
                FROM products as p
                JOIN (
                    SELECT product_id, MIN(id) AS pricing_id
                    FROM product_pricings
                    WHERE (:vendorId IS NULL OR vendor_id = :vendorId)
                    GROUP BY product_id
                ) AS pp2 ON pp2.product_id = p.id
                JOIN product_pricings AS pp ON pp.id = pp2.pricing_id
                LEFT JOIN (
                    SELECT s1.product_id, s1.sku_code, s1.stock_quantity
                    FROM skus s1
                    JOIN (
                        SELECT product_id, MAX(stock_quantity) AS max_qty
                        FROM skus
                        GROUP BY product_id
                    ) s2 ON s1.product_id = s2.product_id AND s1.stock_quantity = s2.max_qty
                    -- Add this to get only one record when stock_quantity is the same
                    WHERE s1.sku_id = (
                        SELECT MIN(s3.sku_id) 
                        FROM skus s3 
                        WHERE s3.product_id = s1.product_id 
                        AND s3.stock_quantity = s2.max_qty
                    )
                ) AS sku_max ON sku_max.product_id = p.matix_id
                LEFT JOIN ( 
                    SELECT product_pricing_id, 1
                    FROM product_site_exclusion
                    WHERE vendor_id = :vendorId
                    AND store_id = :siteId 
                ) AS pse ON pse.product_pricing_id = pp.id
                LEFT JOIN ( 
                    SELECT product_id, sale_price
                    FROM pricing_scale_product_pricing
                    WHERE vendor_id = :vendorId
                    AND pricing_scale_id = :pricingScaleId 
                ) AS bcpp ON bcpp.product_id = p.id
                ";

        // Handle promo codes without creating duplicates
        if ($promos != null && $promos != "") {
            if ($promos == 2) {
                $sql .= " INNER JOIN promo_codes as pc ON p.id = pc.product_id and pp.vendor_id = pc.vendor_id";
            } else {
                $sql .= " LEFT JOIN promo_codes as pc ON p.id = pc.product_id and pp.vendor_id = pc.vendor_id";
            }
        } else {
            $sql .= " LEFT JOIN promo_codes as pc ON p.id = pc.product_id and pp.vendor_id = pc.vendor_id";
        }

        $params = [
            ':vendorId' => $vendorId,
            ':pricingScaleId' => $pricingScaleId,
            ':siteId' => (!empty($siteId)) ? $siteId : 0
        ];

        $whereConditions = [];
        
        if ($vendorId != null && $vendorId != "") {
            $whereConditions[] = "pp.vendor_id = :vendorId";
        }
        
        if ($categorySelect != null && $categorySelect != "") {
            $params[':categorySelect'] = '%"' . $categorySelect . '"%';
            $whereConditions[] = "p.category_id like :categorySelect";
        }
        
        if ($productStatus != null && $productStatus != "") {
            $params[':productStatus'] = $productStatus;
            $whereConditions[] = "pp.active = :productStatus";
        }

        if (!empty($whereConditions)) {
            $sql .= " WHERE " . implode(" AND ", $whereConditions);
        }

        $sql .= " ORDER BY $orderBy";

        if ($limit != 'all') {
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
	$this->db->select('matix_id');
	$this->db->from('products');
	$this->db->where('id', $productId);
	$product = $this->db->get()->row();

	if ($product) {
   	 $matix_id = $product->matix_id;
	} else {
	    $matix_id = null; // or handle the "not found" case
	}
        // First get available SKU
        $sku_result = $this->db->select('sku_code')
                            ->from('skus')
                            ->where('product_id', $matix_id)
                            ->where('stock_quantity >', 0)
                            ->where('stock_quantity IS NOT NULL')
                            ->order_by('stock_quantity DESC, sku_id ASC')
                            ->limit(1)
                            ->get()
                            ->row();
        if(empty($sku_result))return Null;      
        $sku_code = $sku_result ? $sku_result->sku_code : null;
        
        $this->db->select('COALESCE(pp.price, p.base_price) as price,
                        COALESCE(pp.retail_price, p.base_price) as retail_price, 
                        pp.vendor_id, v.name, pc.title, pc.conditions, 
                        so.shipping_price, so.shipping_type, vp.policy_name');
        
        $this->db->from('product_pricings pp');
        $this->db->join('vendors v', 'pp.vendor_id = v.id', 'left');
        $this->db->join('products p', 'pp.product_id = p.id', 'left');
        $this->db->join('promo_codes pc', 'pp.product_id = pc.product_id', 'left');
        $this->db->join('vendor_policies vp', 'v.id = vp.vendor_id', 'left');
        $this->db->join('shipping_options so', 'v.id = so.vendor_id', 'left');
        
        $this->db->where('pp.product_id', $productId); // ✅ Specific table
        $this->db->where('v.active', 1);
        $this->db->where('pp.active', 1);
        
        // Add SKU condition
        if ($sku_code) {
            $this->db->where('pp.sku', $sku_code);
        } else {
            $this->db->where('pp.sku IS NOT NULL');
        }
        
        // Whitelabel vendor filter
        $whitelabel_vendor_id = $this->config->item('whitelabel_vendor_id');
        if (!empty($whitelabel_vendor_id)) {
            $this->db->where('pp.vendor_id', $whitelabel_vendor_id);
        } else {
            $this->db->where("CONCAT(v.id, '-', 0, '-', pp.product_id) NOT IN (
                SELECT DISTINCT CONCAT(pse.vendor_id, '-', 0, '-', pse.product_pricing_id)
                FROM product_site_exclusion pse
                WHERE pse.product_pricing_id = " . $this->db->escape($productId) . "
            )");
        }
        
        $this->db->group_by('pp.price, v.id');
        
        return $this->db->get()->result();
    }

    public function vendorPricingsBySku($skucode)
    {
        $params = [':skuCode' => $skucode];

        $sql = "SELECT COALESCE(pp.price, p.base_price) as price, 
                    COALESCE(pp.retail_price, p.base_price) as retail_price, 
                    pp.vendor_id, v.name, pc.title, pc.conditions, 
                    so.shipping_price, so.shipping_type, vp.policy_name
                FROM product_pricings pp
                LEFT JOIN vendors v ON pp.vendor_id = v.id
                LEFT JOIN products p ON pp.product_id = p.id 
                LEFT JOIN promo_codes pc ON pp.product_id = pc.product_id
                LEFT JOIN vendor_policies vp ON v.id = vp.vendor_id
                LEFT JOIN shipping_options so ON v.id = so.vendor_id
                WHERE pp.sku = :skuCode  -- Changed from sku to sku_code
                AND v.active = 1
                AND pp.active = 1";

        if (!empty($this->config->item('whitelabel_vendor_id'))) {
            $params[':vendorId'] = $this->config->item('whitelabel_vendor_id');
            $sql .= " AND pp.vendor_id = :vendorId";
            
            $sql .= " AND CONCAT(v.id, '-', 0, '-', pp.product_id) NOT IN (
                        SELECT DISTINCT CONCAT(pse.vendor_id, '-', 0, '-', pp.product_id)
                        FROM product_site_exclusion AS pse
                        JOIN product_pricings AS pp ON pp.id = pse.product_pricing_id
                    )";
            
            // Uncomment if you need this condition
            // $sql .= " AND pp.exclude_from_marketplace = 0";
        }
        
        $sql .= " GROUP BY pp.price, v.id";

        $result = $this->PDOhandler->query($sql, $params, 'fetchAll');
        return $result;
    }
}

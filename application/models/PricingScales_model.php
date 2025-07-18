<?php

class PricingScales_model extends MY_Model {

    public $_table = 'buying_clubs'; // you MUST mention the table name
    public $primary_key = 'id'; // you MUST mention the primary key
    public $fillable = array(); // If you want, you can set an array with the fields that can be filled by insert/update
    public $protected = array(); // ...Or you can set an array with the fields that cannot be filled by insert/update

    public function __construct() {
        parent::__construct();
        $this->load->model('Memc');
        $this->load->model('PDOhandler');
        $this->load->model('Vendor_model');
        $this->load->model('Vendor_groups_model');
    }

    public function loadAllByVendor($vendorId)
    {
        $sql = "SELECT ps.*
                FROM pricing_scales AS ps
                JOIN vendors AS v
                    ON ps.vendor_id = v.id
                WHERE ps.vendor_id = ?";

        $pricingScales = $this->Memc->get($sql, [$vendorId], 'pricing-scales-' . $vendorId, 3600, 'fetchAll');

        // Debugger::debug($pricingScales);
        return $pricingScales;
    }

    public function load($pricingScaleId)
    {
        $sql = "SELECT * FROM pricing_scales WHERE id = :pricingScaleId";

        return $this->Memc->get($sql, [':pricingScaleId' => $pricingScaleId], 'pricing-scale-' . $pricingScaleId, 3600, 'fetch');
    }

    public function productsCount($clubId)
    {
        $sql = "SELECT count(*) AS count
                FROM buying_club_products
                WHERE buying_club_id = ?";

        $result = $this->Memc->get($sql, [$clubId], 'products-count-' . $clubId, 3600, 'fetch');
        // Debugger::debug($result);
        return $result->count;
    }

    public function loadProducts($clubId, $vendor_id, $promos = null, $categorySelect = null, $productStatus = null, $offset = '0', $limit = 25)
    {
        return $vendor_products = $this->Vendor_model->loadProducts($vendor_id, $promos, $categorySelect, $productStatus, $order_by, $limit, $offset, $clubId);
    }

    public function loadVendorProducts($clubId, $vendorId, $start = '0', $limit = 25)
    {
        $sql = "SELECT p.id, p.name,  pp.*, bpp.sale_price
                FROM  products AS p
                JOIN product_pricings AS pp
                    ON p.id = pp.product_id
                LEFT JOIN buying_club_products AS bp
                    ON bp.product_id = p.id
                LEFT JOIN buying_club_product_pricing AS bpp
                    ON bpp.buying_club_product_id = bp.id
                WHERE  pp.vendor_id = (SELECT vendor_id FROM vendor_groups WHERE user_id = :userId)
                ORDER BY p.name ASC";

        return $this->Memc->get($sql, [':clubId' => $clubId, ':userId' => $vendorId], 'club-' .$clubId . '-products', 3600, 'fetchAll');
    }


    public function loadProductPricing($clubId, $userId = null, $start = 0, $limit = 25)
    {
        $sql = "SELECT p.name, bp.*, bpp.sale_price
                FROM buying_club_products AS bp
                JOIN products AS p
                    ON bp.product_id = p.id
                JOIN buying_club_product_pricing AS bpp
                    ON bp.id = bpp.buying_club_product_id
                WHERE bp.buying_club_id = :clubId
                ";
        if(!empty($userId)){
            $sql .= "AND bpp.vendor_id = (SELECT vendor_id FROM vendor_groups WHERE user_id = :user_id )
                    ";
        }
        $sql .= "LIMIT $start, $limit";

        return $this->Memc->get($sql, [':clubId' => $clubId, ':user_id' => $userId], 'club-' .$clubId . '-products', 3600, 'fetchAll');
    }

    public function savePrice($pricingScaleId, $productId, $vendorId, $salePrice)
    {
        $sql = "INSERT INTO pricing_scale_product_pricing (
                    pricing_scale_id,
                    product_id,
                    vendor_id,
                    sale_price
                ) VALUES (
                    :pricingScaleId,
                    :productId,
                    :vendorId,
                    :salePrice
                )
                ON DUPLICATE KEY UPDATE
                    sale_price = :salePrice";

        $params = [
            ':pricingScaleId' => $pricingScaleId,
            ':vendorId' => $vendorId,
            ':productId' => $productId,
            ':salePrice' => $salePrice
        ];

        $this->PDOhandler->query($sql, $params);
    }

    public function importPrice($pricingScaleName, $vendorName, $vendorSKU, $salePrice)
    {
        $sql = "INSERT INTO pricing_scale_product_pricing (
                    pricing_scale_id,
                    vendor_id,
                    product_id,
                    sale_price
                ) VALUES (
                    (SELECT id FROM pricing_scales WHERE name = :pricingScaleName),
                    (SELECT id FROM vendors WHERE name = :vendorName ),
                    (SELECT product_id FROM product_pricings WHERE vendor_product_id = :vendorSKU ),
                    :salePrice
                )
                ON DUPLICATE KEY UPDATE
                    sale_price = :salePrice";

        $params = [
            ':pricingScaleName' => trim($pricingScaleName),
            ':vendorName' => trim($vendorName),
            ':vendorSKU' => trim($vendorSKU),
            ':salePrice' => $salePrice
        ];

        $this->PDOhandler->query($sql, $params);
    }

    public function deleteMinimumPrice($pricingScaleName, $vendorName, $vendorSKU)
    {
        $sql = "DELETE FROM pricing_scale_product_pricing
                WHERE pricing_scale_id = (SELECT id FROM pricing_scales WHERE name = :pricingScaleName)
                AND vendor_id = (SELECT id FROM vendors WHERE name = :vendorName )
                AND product_id = (SELECT product_id FROM product_pricings WHERE vendor_product_id = :vendorSKU )";

        $params = [
            ':pricingScaleName' => trim($pricingScaleName),
            ':vendorName' => trim($vendorName),
            ':vendorSKU' => trim($vendorSKU)
        ];

        $this->PDOhandler->query($sql, $params);
    }

    public function deletePrice($buyingClubId, $productId, $vendorId)
    {
        $sql = "DELETE FROM buying_club_product_pricing
                WHERE buying_club_id = :buyingClubId
                AND product_id = :productId
                AND vendor_id = :vendorId";


        $params = [
            ':buyingClubId' => $buyingClubId,
            ':vendorId' => $vendorId,
            ':productId' => $productId
        ];

        $this->PDOhandler->query($sql, $params);
    }


    public function create($params)
    {
        if(!empty($params['name'])){
            try {
                $params = [
                    'name' => $params['name'],
                    'percentage_discount' => $params['percentageDiscount'],
                    'vendor_id' => $params['vendorId']
                ];

                $this->db->insert('pricing_scales', $params);

                $msgType = 'success';
                $msg = 'Pricing Scale created.';
            } catch (Exception $e){
                Debugger::debug($e->getMessage());

                $msgType = 'error';
                $msg = "Duplicate name";
            }
        } else {
            $msgType = 'error';
            $msg = "You must a name";
        }

        $this->session->set_flashdata($msgType, $msg);
    }


    public function save($params)
    {
        Debugger::debug($params);
        try {
            $this->db->set('name', $params['name']);
            $this->db->set('percentage_discount', $params['percentage_discount']);
            $this->db->where('id', $params['pricing_scale_id']);
            $this->db->update('pricing_scales');

            $msgType = 'success';
            $msg = 'Pricing Scale updated.';
        } catch(Excpection $e){
            $msgType = 'error';
            $msg = $e->getMessage();
        }
        $this->Memc->flush();

        $this->session->set_flashdata($msgType, $msg);
    }

    public function addOrganizations($upload)
    {
        $fp = fopen($upload['tmp_name'], 'r');
        $fields = fgetcsv($fp);

        $sql = "INSERT INTO buying_club_organizations (
                    buying_club_id,
                    organization_id
                ) VALUES (
                    (SELECT id FROM buying_clubs WHERE code = :code),
                    (SELECT id FROM organizations WHERE organization_name = :organization_name )
                )";

        // loop through all rows and insert
        while (($data = fgetcsv($fp)) !== FALSE) {

            $params = [
                ':code' => $data[0],
                ':organization_name' => $data[1]
            ];

            $this->PDOhandler->query($sql, $params);
        }
    }

    public function deleteVendor($clubId, $vendorId)
    {
        $sql = "DELETE FROM buying_club_vendors
                WHERE buying_club_id = :clubId
                AND vendor_id = :vendorId";


        $params = [
            ':clubId' => $clubId,
            ':vendorId' => $vendorId
        ];

        $this->PDOhandler->query($sql, $params);

        // clean pricing orphans
        $sql = "DELETE FROM buying_club_product_pricing
                WHERE buying_club_id = :clubId
                AND vendor_id = :vendorId";

        $this->PDOhandler->query($sql, $params);
    }

    public function deleteOrganization($clubId, $organizationId)
    {
        $sql = "DELETE FROM buying_club_organizations
                WHERE buying_club_id = :clubId
                AND organization_id = :organizationId";


        $params = [
            ':clubId' => $clubId,
            ':organizationId' => $organizationId
        ];

        $this->PDOhandler->query($sql, $params);
    }

    public function toggleActive($id)
    {
        $sql = "UPDATE pricing_scales
                    SET active = !active
                WHERE id = :id";

        $this->PDOhandler->query($sql, [':id' => $id]);
    }

    public function loadUserClubs($userId)
    {
        // Debugger::debug($userId);

        $sql = "SELECT bc.*, bd.percentage_discount, bd.vendor_id
                FROM buying_clubs AS bc
                JOIN buying_club_organizations AS bo
                    ON bc.id = bo.buying_club_id
                LEFT JOIN buying_club_discount AS bd
                    ON bc.id = bd.buying_club_id
                JOIN organization_groups AS og
                    ON bo.organization_id = og.organization_id
                WHERE og.user_id = :userId";

        $results = $this->PDOhandler->query($sql, [':userId' => $userId]);
        // Debugger::debug($results);
        $buyingClubs = [];
        foreach($results as $result){
            $buyingClubs[$result->id]['percentage_discounts'][$result->vendor_id] = $result->percentage_discount;
        }
        $buyingClubs[$result->id]['name'] = $result->name;
        $buyingClubs[$result->id]['code'] = $result->code;

        return $buyingClubs;
    }

    public function getBuyingClubPrices($buyingClubs, $productIds)
    {
        // Debugger::debug($buyingClubs);
            $params = [];
            // // Debugger::debug($buyingClubs, '$buyingClubs');
            $bcIn = "";
            foreach ($buyingClubs as $i => $item)
            {
                $key = ":bcid".$i;
                $bcIn .= " :bcid" . $i . ",";
                $params[$key] = $i; // collecting values into key-value array
            }
            $bcIn = rtrim($bcIn,","); // :id0,:id1,:id2

            $pIn = "";
            foreach ($productIds as $i => $item)
            {
                $key = ":pid".$i;
                $pIn .= " :pid" . $i . ",";
                $params[$key] = $item; // collecting values into key-value array
            }
            $pIn = rtrim($pIn,","); // :id0,:id1,:id2

            $sql = "SELECT bp.*
                    FROM buying_club_product_pricing AS bp
                    WHERE bp.buying_club_id IN ($bcIn)
                    AND bp.product_id IN ($pIn)
                    ORDER BY sale_price ASC
                    ";

            Debugger::debug($sql);
            Debugger::debug($params);
            $results = $this->PDOhandler->query($sql, $params);
            Debugger::debug($results, '$results');
            $clubPricings = [];

            foreach($results as $result){
                // Debugger::debug($result);
                $clubPricings[$result->buying_club_id][$result->product_id][$result->vendor_id] = $result->sale_price;
            }


        return $clubPricings;
    }

    public function getBestPrice($productId, $vendorId, $bcPrices, $userBuyingclubs, $regular_price)
    {
        // Debugger::debug([
        //     '$productId' => $productId,
        //     '$vendorId' => $vendorId,
        //     '$bcPrices' => $bcPrices,
        //     '$userBuyingclubs' => $userBuyingclubs,
        //     '$regular_price' => $regular_price
        // ]);
        // Debugger::debug($bcPrices, '$bcPrices');
        $bestPrice = null;
        foreach($userBuyingclubs as $clubId => $buyingClub){
            // first check if explicit price declared
            // Debugger::debug($bcPrices[$clubId]);
            // Debugger::debug($bcPrices[$clubId][$productId]);
            if(!empty($bcPrices[$clubId][$productId])){
                if(!empty($bcPrices[$clubId][$productId][$vendorId])){
                    // Debugger::debug($bcPrices[$clubId][$productId][$vendorId]);
                    if(empty($bestPrice) || $bestPrice > $bcPrices[$clubId][$productId][$vendorId]){
                        $bestPrice = $bcPrices[$clubId][$productId][$vendorId];
                    }
                }
            } else if(empty($bestPrice)) {
                $bestPrice =  null;
                //if no explicit price, check for percentage discount
                foreach($_SESSION['user_buying_clubs'] AS $buyingClub){
                    // Debugger::debug($buyingClub);
                    if($buyingClub['percentage_discounts'][$vendorId] > 0) {
                        $tmpPrice = $regular_price / 100 * (100 - $buyingClub['percentage_discounts'][$vendorId]);
                        if(empty($bestPrice) || $tmpPrice < $bestPrice){
                            $bestPrice = $tmpPrice;
                        }
                    }
                }
            }
        }

        if($bestPrice){
            $bestPrice = round($bestPrice, 2);
        }


        // Debugger::debug($bestPrice, '$bestPrice');
        return $bestPrice;
    }
}

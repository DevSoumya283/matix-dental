<?php

class BuyingClub_model extends MY_Model {

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

    public function loadAll($userId)
    {
        $sql = "SELECT b.*, u.first_name, u.last_name AS owner_name, vg.vendor_id AS vendor_id
                FROM buying_clubs AS b
                LEFT JOIN buying_club_vendors AS bv
                    ON b.id = bv.buying_club_id
                LEFT JOIN vendor_groups AS vg
                    ON bv.vendor_id = vg.vendor_id
                JOIN users AS u
                    ON b.owner_id = u.id
                WHERE (b.owner_id = :userId
                OR vg.user_id = :userId
                OR vg.vendor_id = :userId)
                GROUP BY b.id";

        return $this->Memc->get($sql, [':userId' => $userId], 'all-buying-clubs-user-' . $userId, 3600, 'fetchAll');
    }

    public function loadClubVendors()
    {
        $sql = "SELECT DISTINCT v.id, v.name
                FROM vendors AS v
                JOIN vendor_groups AS vg
                    ON v.id = vg.vendor_id
                JOIN buying_clubs AS bc
                    ON vg.user_id = bc.owner_id";

        return $this->Memc->get($sql, [], 'all-club-vendors', 3600, 'fetchAll');
    }

    public function loadClub($clubId, $vendorId = null)
    {

        $sql = "SELECT *
                FROM buying_clubs AS bc
                WHERE bc.id = :id";

        $params = [
            ':id' => $clubId
        ];

        $club = $this->Memc->get($sql, $params, 'buying-club-' . $clubId, 3600, 'fetch');
        $club->vendors = $this->loadVendors($clubId, $vendorId);
        $club->organizations = $this->loadOrganizations($clubId);
        $club->pricingScales = $this->loadPricingScales($clubId);

        return $club;
    }

    // public function loadClubVendors($clubId)
    // {
    //     $sql = "SELECT v.id, v.name
    //             FROM vendors AS v
    //             JOIN buying_club_vendors AS bv
    //                 ON bv.vendor_id = v.id
    //             WHERE bv.buying_club_id = :buyingClubId
    //             ORDER BY v.name ASC";

    //     $params = [':buyingClubId' => $clubId];

    //     return $result = $this->Memc->get($sql, $params, 'club-vendors-' . $clubId, 3600, 'fetchAll');
    // }

    public function vendorCount($clubId)
    {
        $sql = "SELECT count(*) AS count
                FROM buying_club_vendors
                WHERE buying_club_id = ?";

        $result = $this->Memc->get($sql, [$clubId], 'vendors-count-' . $clubId, 3600, 'fetch');
        // Debugger::debug($result);
        return $result->count;
    }

    public function organizationCount($clubId)
    {
        $sql = "SELECT count(*) AS count
                FROM buying_club_organizations
                WHERE buying_club_id = ?";

        $result = $this->Memc->get($sql, [$clubId], 'organization-count-' . $clubId, 3600, 'fetch');

        return $result->count;
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

    public function loadVendors($clubId, $vendorId = null, $start = 0, $limit = 25)
    {
        $params = [':clubId' => $clubId];

        $sql = "SELECT v.name, bv.*
                FROM buying_club_vendors AS bv
                JOIN vendors AS v
                    ON v.id = bv.vendor_id
                WHERE buying_club_id = :clubId
                ";
        if($vendorId){
            $params[':vendorId'] = $vendorId;
            $sql .= "AND v.id = :vendorId
                    ";
        }
        $sql .= "LIMIT $start, $limit";

        return $this->Memc->get($sql, $params, 'club-' .$clubId . '-vendors', 3600, 'fetchAll');
    }

    public function loadOrganizations($clubId, $start = 0, $limit = 25)
    {
        $sql = "SELECT o.organization_name AS name, bo.*
                FROM buying_club_organizations AS bo
                JOIN organizations AS o
                    ON o.id = bo.organization_id
                WHERE buying_club_id = :clubId
                LIMIT $start, $limit";

        return $this->Memc->get($sql, [':clubId' => $clubId], 'club-' .$clubId . '-organizations', 3600, 'fetchAll');
    }

    public function loadPricingScales($buyingClubId)
    {
        $sql = "SELECT * FROM buying_club_pricing_scale
                WHERE buying_club_id = :buyingClubId";

        return $this->Memc->get($sql, [':buyingClubId' => $buyingClubId], 'club-' .$buyingClubId . '-pricing-scales', 3600, 'fetchAll');
    }

    // public function loadProducts($clubId, $vendor_id, $promos = null, $categorySelect = null, $productStatus = null, $offset = '0', $limit = 25)
    // {
    //     // $this->Memc->flush();
    //     // $sql = "SELECT p.name, bp.*
    //     //         FROM buying_club_products AS bp
    //     //         JOIN products AS p
    //     //             ON bp.product_id = p.id
    //     //         WHERE buying_club_id = :clubId
    //     //         LIMIT 0, $limit";

    //     // return $this->Memc->get($sql, [':clubId' => $clubId], 'club-' .$clubId . '-products', 3600, 'fetchAll');
    //         // $products = $this->BuyingClub_model->loadProducts($clubId, $this->input->get('vendorId'), null, null, null, 'all');
    //         //             $this->BuyingClub_model->loadProducts($this->input->get('id'), $vendorId, $promos, $categorySelect, $productStatus, $offset = '0', $limit = 30);
    //     return $vendor_products = $this->Vendor_model->loadProducts($vendor_id, $promos, $categorySelect, $productStatus, $order_by, $limit, $offset, $clubId);
    //     //return $this->Vendor_model->searchProducts($vendor_id, $search, $order_by, $data['limit'], $offset);
    // }

    // public function loadVendorProducts($clubId, $vendorId, $start = '0', $limit = 25)
    // {
    //     $sql = "SELECT p.id, p.name,  pp.*, bpp.sale_price
    //             FROM  products AS p
    //             JOIN product_pricings AS pp
    //                 ON p.id = pp.product_id
    //             LEFT JOIN buying_club_products AS bp
    //                 ON bp.product_id = p.id
    //             LEFT JOIN buying_club_product_pricing AS bpp
    //                 ON bpp.buying_club_product_id = bp.id
    //             WHERE  pp.vendor_id = (SELECT vendor_id FROM vendor_groups WHERE user_id = :userId)
    //             ORDER BY p.name ASC";

    //     return $this->Memc->get($sql, [':clubId' => $clubId, ':userId' => $vendorId], 'club-' .$clubId . '-products', 3600, 'fetchAll');
    // }


    // public function loadProductPricing($clubId, $userId = null, $start = 0, $limit = 25)
    // {
    //     $sql = "SELECT p.name, bp.*, bpp.sale_price
    //             FROM buying_club_products AS bp
    //             JOIN products AS p
    //                 ON bp.product_id = p.id
    //             JOIN buying_club_product_pricing AS bpp
    //                 ON bp.id = bpp.buying_club_product_id
    //             WHERE bp.buying_club_id = :clubId
    //             ";
    //     if(!empty($userId)){
    //         $sql .= "AND bpp.vendor_id = (SELECT vendor_id FROM vendor_groups WHERE user_id = :user_id )
    //                 ";
    //     }
    //     $sql .= "LIMIT $start, $limit";

    //     return $this->Memc->get($sql, [':clubId' => $clubId, ':user_id' => $userId], 'club-' .$clubId . '-products', 3600, 'fetchAll');
    // }

    public function save($id = null, $name, $userId)
    {
        if(!empty($name)){

            if($id){
                $sql = "UPDATE buying_clubs SET name = :name WHERE id = :id";
                $this->PDOhandler->query($sql, [':id' => $id, ':name' => $name], null);
            } else {
                $sql = "INSERT INTO buying_clubs (name, owner_id) VALUES (:name, :owner_id)";
                $this->PDOhandler->query($sql, [':owner_id' => $userId, ':name' => $name], null);
            }

            // get the id if not existing and insert the vendor if required
            if(empty($id) && !empty($_SESSION['vendor_id'])){
                $buyingClub = $this->db->get_where('buying_clubs', ['name' => $name, 'owner_id' => $userId])->row();

                $params = [
                    ':name' => $name,
                    ':vendor_name' => "(SELECT name from vendors where id = " . $_SESSION['vendor_id'] . ")"
                ];

                $this->addVendor($params);
            }

            $msgType = 'success';
            if($id){
                $msg = 'Buying Club updated.';
            } else {
                $msg = 'Buying Club created.';
            }
        } else {
            $msgType = 'error';
            $msg = "You must enter a name";
        }

        $this->session->set_flashdata($msgType, $msg);
    }

    public function saveVendorDiscount($buyingClubId, $vendorId, $discountPercentage)
    {
        $sql = "INSERT INTO buying_club_discount (
                    buying_club_id,
                    vendor_id,
                    percentage_discount
                ) VALUES (
                    :buyingClubId,
                    :vendorId,
                    :discountPercentage
                )
                ON DUPLICATE KEY UPDATE
                    percentage_discount = :discountPercentage";

        $params = [
            ':buyingClubId' => $buyingClubId,
            ':vendorId' => $vendorId,
            ':discountPercentage' => $discountPercentage
        ];

        $this->PDOhandler->query($sql, $params, null);

        $this->session->set_flashdata('success', 'Buying Club discount updated.');
    }

    public function addVendors($upload)
    {
        $fp = fopen($upload['tmp_name'], 'r');
        $fields = fgetcsv($fp);
        Debugger::debug($fields);
        // loop through all rows and insert
        while (($data = fgetcsv($fp)) !== FALSE) {
            $params = [
                ':name' => $data[0],
                ':vendor_name' => $data[1]
            ];

            $this->addVendor($params);
        }
    }

    public function addVendor($params)
    {
        $sql = "INSERT INTO buying_club_vendors (
                    buying_club_id,
                    vendor_id
                ) VALUES (
                    (SELECT id FROM buying_clubs WHERE name = :name),
                    (SELECT id FROM vendors WHERE name = :vendor_name )
                )";

        $this->PDOhandler->query($sql, $params);
    }

    public function addProducts($upload)
    {
        // Debugger::debug('adding products');
        // add products
        $fp = fopen($upload['tmp_name'], 'r');
        $fields = fgetcsv($fp);

        $cleared = false;

        $pricingSql = "INSERT INTO buying_club_product_pricing (
                    buying_club_id,
                    vendor_id,
                    product_id,
                    sale_price
                ) VALUES (
                    :buyingClubId,
                    :vendorId,
                    (SELECT product_id
                     FROM product_pricings
                     WHERE vendor_product_id = :productId
                     AND vendor_id = :vendorId) ,
                    :sale_price
                )
                ON DUPLICATE KEY UPDATE
                    sale_price = :sale_price";

        // loop through all rows and insert
        while (($data = fgetcsv($fp)) !== FALSE) {
            // restrict who can update
            if($_SESSION['vendor_id'] == $data[1] || $this->User_model->can($_SESSION['user_permissions'], 'is-admin')){
                if(!empty($data[5])){
                    $params = [
                        ':buyingClubId' => $data[0],
                        ':vendorId' => $data[1],
                        ':productId' => $data[2],
                        ':sale_price' => $data[5]
                    ];

                    $this->PDOhandler->query($pricingSql, $params);
                }
            }
        }
    }

    public function savePricingScale($buyingClubId, $vendorId, $pricingScaleId)
    {
        $params = [
            ':buyingClubId' => $buyingClubId,
            ':vendorId' => $vendorId,
            ':pricingScaleId' => $pricingScaleId
        ];

        $sql = "INSERT INTO buying_club_pricing_scale (
                    buying_club_id,
                    vendor_id,
                    pricing_scale_id
                ) VALUES (
                    :buyingClubId,
                    :vendorId,
                    :pricingScaleId
                ) ON DUPLICATE KEY UPDATE
                    pricing_scale_id = :pricingScaleId";

        $this->PDOhandler->query($sql, $params);
    }

    public function addOrganizations($upload)
    {
        $fp = fopen($upload['tmp_name'], 'r');
        $fields = fgetcsv($fp);
        Debugger::debug($fields);

        $sql = "INSERT INTO buying_club_organizations (
                    buying_club_id,
                    organization_id
                ) VALUES (
                    (SELECT id FROM buying_clubs WHERE name = :name),
                    (SELECT id FROM organizations WHERE organization_name = :organization_name )
                )";

        // loop through all rows and insert
        while (($data = fgetcsv($fp)) !== FALSE) {
            Debugger::debug($data);
            $params = [
                ':name' => $data[0],
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
        $sql = "DELETE FROM buying_club_pricing_scale
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
        $sql = "UPDATE buying_clubs
                    SET active = !active
                WHERE id = :id";

        $this->PDOhandler->query($sql, [':id' => $id]);
    }

    public function loadUserClubs($userId)
    {
        // Debugger::debug($userId);

        $sql = "SELECT bc.*, ps.percentage_discount, ps.vendor_id
                FROM buying_clubs AS bc
                JOIN buying_club_organizations AS bo
                    ON bc.id = bo.buying_club_id
                LEFT JOIN buying_club_pricing_scale AS bps
                    ON bc.id = bps.buying_club_id
                LEFT JOIN pricing_scales AS ps
                    ON bps.pricing_scale_id = ps.id
                JOIN organization_groups AS og
                    ON bo.organization_id = og.organization_id
                WHERE og.user_id = :userId
                AND bc.active = 1";

        $results = $this->PDOhandler->query($sql, [':userId' => $userId]);
        // Debugger::debug($results);
        $buyingClubs = [];
        foreach($results as $result){
            $buyingClubs[$result->id]['name'] = $result->name;
            $buyingClubs[$result->id]['percentage_discounts'][$result->vendor_id] = $result->percentage_discount;
        }
        $buyingClubs[$result->id]['name'] = $result->name;

        // Debugger::debug($buyingClubs, 'BUYING CLUBS');

        return $buyingClubs;
    }

    public function getBuyingClubPrices($buyingClubs, $productIds)
    {
            $params = [];
            $bcIn = "";
            foreach ($buyingClubs as $i => $item)
            {
                $key = ":bcid".$i;
                $bcIn .= " :bcid" . $i . ",";
                $params[$key] = $i; // collecting values into key-value array
            }
            $bcIn = rtrim($bcIn,","); // :id0,:id1,:id2

            $pIn = "";
            //Debugger::debug($productIds, '$productIds', true);
            foreach ($productIds as $i => $item)
            {
                $key = ":pid".$i;
                $pIn .= " :pid" . $i . ",";
                $params[$key] = $item; // collecting values into key-value array
            }
            $pIn = rtrim($pIn,","); // :id0,:id1,:id2

            $sql = "SELECT pspp.*, bc.id AS buying_club_id
                    FROM buying_clubs AS bc
                    JOIN buying_club_pricing_scale AS bcps
                        ON bc.id = bcps.buying_club_id
                    JOIN pricing_scale_product_pricing AS pspp
                        ON bcps.pricing_scale_id = pspp.pricing_scale_id
                    WHERE bc.id IN ($bcIn)
                    AND pspp.product_id IN ($pIn)
                    ORDER BY sale_price ASC
                    ";

            // Debugger::debug($sql, '$sql', false, 'buyingClubs');
            // Debugger::debug($params, '$params', false, 'buyingClubs');
            $results = $this->PDOhandler->query($sql, $params);
            // Debugger::debug($results, '$results', false, 'buyingClubs');
            $clubPricings = [];

            foreach($results as $result){
                // Debugger::debug($result);
                $clubPricings[$result->buying_club_id][$result->product_id][$result->vendor_id] = $result->sale_price;
            }

            // Debugger::debug($clubPricings, '$clubPricings');
        return $clubPricings;
    }

    public function getBestPrice($productId, $vendorId, $bcPrices, $userBuyingclubs, $regular_price)
    {
        Debugger::debug([
            '$productId' => $productId,
            '$vendorId' => $vendorId,
            '$bcPrices' => $bcPrices,
            '$userBuyingclubs' => $userBuyingclubs,
            '$regular_price' => $regular_price
        ], 'getBestPrice input', false, 'buyingClubs');
        // Debugger::debug($bcPrices, '$bcPrices', false, 'buyingClubs');
        // Debugger::debug($userBuyingclubs, '$userBuyingclubs', false, 'buyingClubs');

        $bestPrice = null;
        // foreach($userBuyingclubs as $clubId => $buyingClub){
            // first check if explicit price declared
            // Debugger::debug($bcPrices[$clubId], '$bcPrices[$clubId]', false, 'buyingClubs');
            // Debugger::debug($bcPrices[$clubId][$productId], '$bcPrices[$clubId][$productId]', false, 'buyingClubs');

            /*if(!empty($bcPrices[$clubId][$productId])){
                if(!empty($bcPrices[$clubId][$productId][$vendorId])){
                    // Debugger::debug($bcPrices[$clubId][$productId][$vendorId]);
                    if(empty($bestPrice) || $bestPrice > $bcPrices[$clubId][$productId][$vendorId]){
                        $bestPrice = $bcPrices[$clubId][$productId][$vendorId];
                    }
                }
            } else*/
            // if(empty($bestPrice)) {
            //     // Debugger::debug($vendorId, '$vendorId', false, 'buyingClubs');
            //     $bestPrice =  null;
                //if no explicit price, check for percentage discount
        foreach($userBuyingclubs as $storeId => $buyingClub){
            Debugger::debug($buyingClub, '$buyingClub', false, 'buyingClubs');
            if(is_array($buyingClub['percentage_discounts'])) {
                foreach ($buyingClub['percentage_discounts'] as $vendorId => $discountPercentage) {
                    if (is_numeric($regular_price) && is_numeric($discountPercentage)) {
                        $tmpPrice = $regular_price / 100 * (100 - $discountPercentage);
                        Debugger::debug($tmpPrice, '$tmpPrice', false, 'buyingClubs');

                        if (empty($bestPrice) || $tmpPrice < $bestPrice) {
                            $bestPrice = $tmpPrice;
                        }
                    } else {
                        log_message('error', "Invalid discount or price: regular_price = $regular_price, discount = $discountPercentage");
                    }
                }

            }
        }
            // }
        // }

        if($bestPrice){
            $bestPrice = round($bestPrice, 2);
        }


        Debugger::debug($bestPrice, '$bestPrice', false, 'buyingClubs');
        return $bestPrice;
    }
}

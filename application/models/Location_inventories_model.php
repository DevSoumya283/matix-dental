<?php

class Location_inventories_model extends MY_Model {

    public $_table = 'location_inventories'; // you MUST mention the table name
    public $primary_key = 'id'; // you MUST mention the primary key
    public $fillable = array(); // If you want, you can set an array with the fields that can be filled by insert/update
    public $protected = array(); // ...Or you can set an array with the fields that cannot be filled by insert/update

    public function __construct() {
        parent::__construct();
    }

    public function loadByLocation($locationId, $categoryId = null)
    {

        $params = [':locationId' => $locationId];

        $sql = "SELECT c.nickname,
                       d.name, d.manufacturer, d.license_required, d.color, d.category_id,
                       e.photo,
                       a.id as inventory_id, a.*,
                       f.id,f.product_id,f.vendor_product_id,f.matix_id,f.vendor_id, f.price,f.retail_price,f.active,count(f.vendor_id) AS vendor_count,
                       min(NULLIF(f.retail_price,0)) as min_retail_price ,
                       min(NULLIF(f.price,0)) as minprice
                FROM location_inventories a
                LEFT JOIN organization_locations c
                    ON a.location_id=c.id
                LEFT JOIN products d
                    ON a.product_id=d.id
                LEFT JOIN images e
                    ON d.id=e.model_id AND model_name = 'products'
                LEFT JOIN product_pricings f
                    ON a.product_id = f.product_id
                WHERE a.location_id = :locationId
                ";
        if(!empty($categoryId)){
            $sql .= "AND d.category_id LIKE '%\"" . $categoryId . "\"%'
                    ";
        }
        $sql .= "GROUP BY a.product_id";


        $result = $this->PDOhandler->query($sql, $params, 'fetchAll');
        Debugger::debug($result);
        return $result;
    }

    public function exportCSV($data)
    {
        Debugger::debug($data['inventory_products']);
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=data.csv');
        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');
        $fields = ["product name", "vendor part #", "qty on hand"];
        foreach($data['classics'] as $category){
            if($category->count > 0){
                fputcsv($output, [$category->name]);
                fputcsv($output, $fields);
                foreach($data['inventory_products'] as $invProduct){
                    $productCats = explode(",", str_replace('"', '', $invProduct->category_id));
                    if(in_array($category->id, $productCats)){
                        fputcsv($output, [$invProduct->name, $invProduct->vendor_product_id, $invProduct->purchashed_qty]);
                    }
                }
                fputcsv($output, [""]);
            }
        }

    }

}

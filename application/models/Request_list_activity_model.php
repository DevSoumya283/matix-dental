<?php

class Request_list_activity_model extends MY_Model {

    public $_table = 'request_list_activities'; // you MUST mention the table name
    public $primary_key = 'id'; // you MUST mention the primary key
    public $fillable = array(); // If you want, you can set an array with the fields that can be filled by insert/update
    public $protected = array(); // ...Or you can set an array with the fields that cannot be filled by insert/update

    public function __construct() {
        parent::__construct();
    }

    public function addProduct($organization_id, $user_id, $product_id, $location_id) {
        $requests = array(
            'organization_id' => $organization_id,
            'user_id' => $user_id,
            'product_id' => $product_id,
            'location_id' => $location_id,
            'action' => 'moved item from',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        );
        $this->insert($requests);
    }

}

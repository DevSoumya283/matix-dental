<?php

class User_location_model extends MY_Model {

    public $_table = 'user_locations'; // you MUST mention the table name
    public $primary_key = 'id'; // you MUST mention the primary key
    public $fillable = array(); // If you want, you can set an array with the fields that can be filled by insert/update
    public $protected = array(); // ...Or you can set an array with the fields that cannot be filled by insert/update

    public function __construct() {
        parent::__construct();
    }

    /*
     *      Getting the Location(s) detail from organization_locations table by user_id
     */

    public function customer_locations($user_id)
    {
        $query = "SELECT * FROM user_locations z INNER JOIN organization_locations y ON y.id=z.organization_location_id WHERE z.user_id=" . $user_id;

        return $result = $this->db->query($query)->result();
    }

}

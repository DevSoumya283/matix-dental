<?php

class Admin_customer_notes_model extends MY_Model {

    public $_table = 'admin_customer_notes'; // you MUST mention the table name
    public $primary_key = 'id'; // you MUST mention the primary key
    public $fillable = array(); // If you want, you can set an array with the fields that can be filled by insert/update
    public $protected = array(); // ...Or you can set an array with the fields that cannot be filled by insert/update

    public function __construct() {
        parent::__construct();
    }

    public function admin_notes($user_id) {
        $query = "SELECT a.*,b.first_name,c.model_name,c.photo
                FROM admin_customer_notes a
                INNER JOIN users b
                    on b.id=a.admin_id
                LEFT JOIN images c
                    on c.model_id=a.admin_id
                WHERE a.customer_id=$user_id
                AND c.model_name = 'user'";

        return $result = $this->db->query($query)->result();
    }

}

<?php

class Order_items_model extends MY_Model {

    public $_table = 'order_items'; // you MUST mention the table name
    public $primary_key = 'id'; // you MUST mention the primary key
    public $fillable = array(); // If you want, you can set an array with the fields that can be filled by insert/update
    public $protected = array(); // ...Or you can set an array with the fields that cannot be filled by insert/update

    public function __construct() {
        parent::__construct();
    }

    public function get_product_order_stats($product_id)
    {
        $this->db->select('COUNT(*) as total, MAX(created_at) as last_order_date');
        $this->db->where('product_id', $product_id);
        $query = $this->db->get('order_items')->row();

        return [
            'total' => (int) $query->total,
            'last_order_date' => $query->last_order_date
        ];
    }

}

<?php

class Cart_model extends MY_Model {

    public $_table = 'carts'; // you MUST mention the table name
    public $primary_key = 'id'; // you MUST mention the primary key
    public $fillable = array(); // If you want, you can set an array with the fields that can be filled by insert/update
    public $protected = array(); // ...Or you can set an array with the fields that cannot be filled by insert/update

    public function __construct() {
        parent::__construct();
        $this->load->model('Memc');
        $this->load->model('PDOhandler');
    }

    public function convertCarts()
    {
        $sql = "SELECT * FROM user_autosaves";

        $results = $this->PDOhandler->query($sql, [], 'fetchAll');

        Debugger::debug($results);
    }

    public function loadCarts($organizationId)
    {
        $sql = "SELECT *
                FROM carts
                WHERE organization_id = :organizationId ";


    }

    public function saveCart($params)
    {
        $sql = "UPDATE carts SET

                WHERE cart_id = :cartId";
    }

    public function deleteCart($cartId)
    {
        $sql = "DELETE FROM cart_items WHERE cart_id =:cartId";



        $sql = "DELETE FROM carts WHERE cart_id = :cartId";


    }

    public function addToCart($params)
    {
        $sql = "INSERT INTO cart (

                ) VALUES (

                )";


    }

    public function deleteFromCart($itemId)
    {
        $sql = "DELETE FROM cart_items WHERE item_id = :itemId";
    }


}

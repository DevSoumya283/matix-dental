<?php

class User_autosave_model extends MY_Model {

    public $_table = 'user_autosaves'; // you MUST mention the table name
    public $primary_key = 'id'; // you MUST mention the primary key
    public $fillable = array(); // If you want, you can set an array with the fields that can be filled by insert/update
    public $protected = array(); // ...Or you can set an array with the fields that cannot be filled by insert/update

    public function __construct() {
        parent::__construct();
    }

    public function fetchCart($role_id, $organization_id = null, $user_id = null, $userLocations = null) {

        $is_student = in_array($role_id, unserialize(ROLES_STUDENTS));

        if ($is_student) {
            return $this->get_by(array('user_id' => $user_id));
        } else {
            $userLocationIds = $this->getUserLocationIds();

            if(!empty($userLocationIds)) {
                $sql = "SELECT *
                        FROM user_autosaves
                        WHERE organization_id = $organization_id
                        AND location_id IN (" . implode(',', $userLocationIds) . ")";

                $carts = $this->db->query($sql)->result();
                $combinedCarts = $this->combineCarts($carts);

                return $combinedCarts;
            }
        }
    }

    public function saveCart($role_id, $cartData, $organization_id = null, $user_id = null)
    {
        $is_student = in_array($role_id, unserialize(ROLES_STUDENTS));

        // if not cart data, load from db - why no cart data?
        if (!$cartData) {
            $cartData = $this->fetchCart($role_id, $organization_id, $user_id);
        }

        if ($is_student) {
            $sql = "INSERT INTO user_autosaves (
                    user_id,
                    cart
                ) VALUES (
                    $user_id,
                    '$cart'
                ) ON DUPLICATE KEY UPDATE
                    cart = '$cart'";

            $this->db->query($sql);
        } else {
            // split cart by location
            $cartByLocation = $this->splitCartByLocation($cartData);

            // loop through user locations and delete from db if no cart exists
            foreach($_SESSION['userLocations'] as $location){
                if (empty($cartByLocation[$location->id])) {
                    $sql = "DELETE FROM user_autosaves
                            WHERE organization_id = $organization_id
                            AND location_id = $location->id";

                    $this->db->query($sql);
                }
            }

            foreach ($cartByLocation as $locationId => $cart) {
                $cart = json_encode($cart);

                $sql = "INSERT INTO user_autosaves (
                    organization_id,
                    location_id,
                    cart
                ) VALUES (
                    $organization_id,
                    $locationId,
                    '$cart'
                ) ON DUPLICATE KEY UPDATE
                    cart = '$cart'";

                $this->db->query($sql);
            }
        }
        return $cartData;
    }

    /**
     * @param $cartData
     * @return array
     */
    public function splitCartByLocation($cartData)
    {
        $splitCart = [];

        // fix to empty cart
        $userLocationIds = $this->getUserLocationIds();
        foreach($userLocationIds as $locationId){
            $splitCart[$locationId] = [];
        }

        // populate cart locations if items in cart
        if (is_array($cartData)) {
            foreach ($cartData as $row) {
                $splitCart[$row['location_id']][] = $row;
            }
        }

        return $splitCart;
    }

    public function combineCarts($splitCarts)
    {
        $items = [];

        foreach ($splitCarts as $cart) {
            $tmp = json_decode($cart->cart);
            foreach($tmp as $k => $item){
                $items[$item->rowid] = $item;
                $organization_id = $item->organization_id;
            }
        }

        $combinedCarts = new stdClass;
        $combinedCarts->organization_id = $organization_id;
        $combinedCarts->cart = json_encode($items);

        return $combinedCarts;
    }

    public function getUserLocationIds()
    {
        $userLocationIds = [];

        foreach($_SESSION['userLocations'] as $location) {
            $userLocationIds[] = $location->id;
        }

        return $userLocationIds;
    }

}

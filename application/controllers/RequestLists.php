<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class RequestLists extends MW_Controller {

    function __construct() {

        parent::__construct();
        $this->load->model('Organization_model');
        $this->load->model('Organization_groups_model');
        $this->load->model('User_location_model');
        $this->load->model('Organization_location_model');
        $this->load->model('Products_model');
        $this->load->model('Product_pricing_model');
        $this->load->model('Vendor_model');
        $this->load->model('Review_model');
        $this->load->model('User_model');
        $this->load->model('Order_model');
        $this->load->model('Order_items_model');
        $this->load->model('Images_model');
        $this->load->model('User_licenses_model');
        $this->load->model('User_autosave_model');
        $this->load->model('Promo_codes_model');
        $this->load->model('Product_tax_model');
        $this->load->model('Request_list_model');
        $this->load->model('Request_list_activity_model');
        $this->load->model('Location_inventories_model');
        $this->load->model('BuyingClub_model');
        $this->load->helper('my_email_helper');
        $this->load->library('cart');
    }

    public function get_userlocations() { //get user assigned locations to add products to request lists
        $user_id = $_SESSION['user_id'];
        $data['locations'] = $this->User_location_model->get_many_by(array('user_id' => $user_id));
        if ($data['locations'] != null) {
            for ($i = 0; $i < count($data['locations']); $i++) {
                $data['user_locations'][] = $this->Organization_location_model->get_by(array('id' => $data['locations'][$i]->organization_location_id));
            }
            $data['requests'] = $this->Request_list_model->get_all();
            if (isset($_SESSION['location_id'])) {
                $location_id = $_SESSION['location_id'];
            } else {
                $location_id = "all";
            }
            if ($location_id == "all") {
                for ($j = 0; $j < count($data['user_locations']); $j++) {
                    foreach ($data['requests'] as $item) {
                        if ($item->location_id == $data['user_locations'][$j]->id) {
                            $product_pricings = $this->Product_pricing_model->get_by(array('vendor_id' => $item->vendor_id, 'product_id' => $item->product_id));
                            if (!(isset($data['user_locations'][$j]->item_count))) {
                                $data['user_locations'][$j]->item_count = 0;
                            }
                            if (!(isset($data['user_locations'][$j]->item_total))) {
                                $data['user_locations'][$j]->item_total = 0;
                            }
                            $data['user_locations'][$j]->item_count += $item->quantity;
                            if ($product_pricings->retail_price > 0) {
                                $data['user_locations'][$j]->item_total += ($product_pricings->retail_price * $item->quantity);
                            } else {
                                $data['user_locations'][$j]->item_total += ($product_pricings->price * $item->quantity);
                            }
                        }
                    }
                }
            } else {
                $data['user_locations'] = new stdClass();
                $locations = $this->Organization_location_model->get_by(array('id' => $location_id));
                $data['user_locations']->id = $locations->id;
                $data['user_locations']->updated_at = $locations->updated_at;
                $data['user_locations']->nickname = $locations->nickname;
                $data['user_locations']->item_count = 0;
                $data['user_locations']->item_total = 0;
                foreach ($data['requests'] as $item) {
                    if ($location_id == $item->location_id) {
                        $product_pricings = $this->Product_pricing_model->get_by(array('vendor_id' => $item->vendor_id, 'product_id' => $item->product_id));
                        $data['user_locations']->item_count = 0;
                        if (!(isset($data['user_locations']->item_count))) {
                            $data['user_locations']->item_count = 0;
                        }
                        if (!(isset($data['user_locations']->item_total))) {
                            $data['user_locations']->item_total = 0;
                        }
                          $data['user_locations']->item_count += $item->quantity;

                        if ($product_pricings->retail_price > 0) {
                            $data['user_locations']->item_total += ($product_pricings->retail_price * $item->quantity);
                        } else {
                            $data['user_locations']->item_total += ($product_pricings->price * $item->quantity);
                        }
                    }
                }
            }
        }
        echo json_encode($data);
    }

    //add products to request lists
    public function addRequest_products() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION["user_id"];
            $bRequestflag = TRUE;
            $aLocations = array();//location ids
            $organization = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
            $organization_id = $organization->organization_id;
            $requests_data = $this->Request_list_model->get_many_by(array('user_id' => $organization_id));
            $product_id = $this->input->post('product_id');
            $vendor_id = $this->input->post('vendor_id');
            $location_id = $this->input->post('location_id');
            $l_id = explode(",", $location_id);
            $qty = $this->input->post('qty');
            if ($requests_data != null) {
                foreach ($requests_data as $key) {
                    for ($i = 0; $i < count($l_id); $i++) {
                        if ($key->product_id == $product_id && $key->vendor_id == $vendor_id && $key->location_id == $l_id[$i]) {
                            $update_id = $key->id;
                            $old_qty = $key->quantity;
                            $product_id = $key->product_id;
                            $update_data = array(
                                'quantity' => $old_qty + $qty,
                            );
                            $this->Request_list_model->update($update_id, $update_data);
                            $this->session->set_flashdata("success", "Products added successfully");
                            $requests = array(
                                'organization_id' => $organization_id,
                                'user_id' => $user_id,
                                'product_id' => $product_id,
                                'location_id' => $l_id[$i],
                                'action' => 'updated',
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                            );
                            $this->Request_list_activity_model->insert($requests);
                            $bRequestflag = TRUE;
                            $aLocations[] = $l_id[$i];
                        } else {
                            $bRequestflag = FALSE;
                        }
                    }
                }
            } else {
                $bRequestflag = FALSE;
            }
            if (!($bRequestflag)) {
                for ($i = 0; $i < count($l_id); $i++) {
                    if (!(in_array($l_id[$i], $aLocations))) {
                        $insert_data = array(
                            'location_id' => $l_id[$i],
                            'user_id' => $organization_id,
                            'product_id' => $product_id,
                            'vendor_id' => $vendor_id,
                            'quantity' => $qty,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        );
                        $this->Request_list_model->insert($insert_data);
                        $this->session->set_flashdata("success", "Products added successfully");
                        $requests = array(
                            'organization_id' => $organization_id,
                            'user_id' => $user_id,
                            'product_id' => $product_id,
                            'location_id' => $l_id[$i],
                            'action' => 'added',
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        );
                        $this->Request_list_activity_model->insert($requests);
                    }
                }
            }
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    //move products from cart to request lists
    public function cart_to_requestlist() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $organization = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
            $organization_id = $organization->organization_id;
            $is_student = in_array($_SESSION['role_id'], unserialize(ROLES_STUDENTS));
            $rowid = $this->input->post('row_id');
            $vendor_id = $this->input->post('vendor_id');
            $location_id = $this->input->post('cartlocation_id');
            $cart = $this->cart->contents();
            foreach ($cart as $item) {
                if ($item['rowid'] == $rowid) {
                    $location_id = $item['location_id'];
                    $product_id = $item['pro_id'];
                    $qty = $item['qty'];
                }
            }
            $this->Request_list_model->addProduct($organization_id, $location_id, $product_id, $vendor_id, $qty);
            $this->Request_list_activity_model->addProduct($organization_id, $user_id, $product_id, $location_id);
            $cleardata = array(
                'rowid' => $rowid,
                'qty' => 0
            );
            $this->cart->update($cleardata);
            $data['cart_data'] = $this->cart->contents();
            $this->User_autosave_model->saveCart($_SESSION['role_id'], $data['cart_data'], $organization->organization_id, $user_id, $data['carts']);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    //set default one location to view request lists
    public function view_request_list() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $organization = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
            $organization_id = $organization->organization_id;
            if (isset($_SESSION['location_id'])) {
                $location_id = $_SESSION['location_id'];
            } else {
                $location_id = "all";
            }

            $data['request_locations'] = array();
            $data['request_product'] = array();
            $data['view_id'] = null;
            $data['location_id'] = $this->User_location_model->get_many_by(array('user_id' => $user_id));
            for ($i = 0; $i < count($data['location_id']); $i++) {
                if ($location_id == "all") {
                    $data['request_locations'][] = $this->Organization_location_model->get_by(array('id' => $data['location_id'][$i]->organization_location_id));
                    $data['request_product'][] = $this->Request_list_model->get_by(array('location_id' => $data['location_id'][$i]->organization_location_id));
                } else {
                    $data['request_locations'][] = $this->Organization_location_model->get_by(array('id' => $location_id));
                    $data['request_product'][] = $this->Request_list_model->get_by(array('location_id' => $location_id));
                }
                $data['user_locations'][] = $this->Organization_location_model->get_by(array('id' => $data['location_id'][$i]->organization_location_id));
            }

            if(!empty($user_id)){
                $data['userLicenses'] = $this->User_licenses_model->loadValidLicenses($user_id, 1);
                if(in_array($_SESSION['role_id'], unserialize(ROLES_TIER1_2)) && empty($data['userLicenses'])){
                   $this->session->set_flashdata("success", "A valid state license must be verified for purchasing items which require a license for this location.  <a href=\"/profile\" class=\"link\">Manage license.</a>");
                }
            }
          
            if ($data['request_locations'] != null && count($data['request_locations']) > 0) {
                header("Location: request-products?id=" . $data['request_locations'][0]->id);
            }
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    //view request list products
    public function request_products() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {


            $user_id = $_SESSION["user_id"];
            $organization = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
            $organization_id = $organization->organization_id;
            if (isset($_SESSION['location_id'])) {
                $location_id = $_SESSION['location_id'];
            } else {
                $location_id = "all";
            }
            $data['request_locations'] = array();
            $data['request_list'] = $this->Request_list_model->get_all();
            $data['location_id'] = $this->User_location_model->get_many_by(array('user_id' => $user_id));
            for ($i = 0; $i < count($data['location_id']); $i++) {
                if ($location_id == "all") {
                    $data['request_locations'][] = $this->Organization_location_model->get_by(array('id' => $data['location_id'][$i]->organization_location_id));
                } else {
                    $data['request_locations'] = $this->Organization_location_model->get_many_by(array('id' => $location_id));
                }
                $data['user_locations'][] = $this->Organization_location_model->get_by(array('id' => $data['location_id'][$i]->organization_location_id));
            }
            for ($j = 0; $j < count($data['request_locations']); $j++) {
                for ($i = 0; $i < count($data['request_list']); $i++) {
                    if ($data['request_list'][$i]->location_id == $data['request_locations'][$j]->id) {
                        if (!(isset($data['request_locations'][$j]->item_count))) {
                            $data['request_locations'][$j]->item_count = 0;
                        }
                        $data['request_locations'][$j]->item_count += $data['request_list'][$i]->quantity;
                    }
                }
            }
            $location_id = $this->input->get('id');
            $productIds = [];
            if ($location_id != null) {
                $RequestListCheck = $this->User_location_model->get_by(array('user_id' => $user_id, 'organization_location_id' => $location_id));

                if (!isset($RequestListCheck)) {
                    $this->session->set_flashdata('error', 'Invalid Entry');
                    header('Location: home');
                } else {
                    $data['locationName'] = $this->Organization_location_model->get_by(array('id' => $location_id));
                    $data['request_product'] = $this->Request_list_model->get_many_by(array('location_id' => $location_id));
                    // $data['request_product'] = $this->Request_list_model->loadProducts($location_id);

                    for ($i = 0; $i < count($data['request_product']); $i++) {
                        $product = $this->Products_model->get_by(array('id' => $data['request_product'][$i]->product_id));
                        $productIds[] = $product->id;
                        $product_pricing = $this->Product_pricing_model->get_by(array('product_id' => $data['request_product'][$i]->product_id, 'vendor_id' => $data['request_product'][$i]->vendor_id));
                        $vendor = $this->Vendor_model->get_by(array('id' => $data['request_product'][$i]->vendor_id));
                        $images = $this->Images_model->get_by(array('model_name' => 'products', 'model_id' => $data['request_product'][$i]->product_id));
                        $inventory = $this->Location_inventories_model->get_by(['product_id' => $product->id, 'location_id' => $location_id]);
                        $data['request_product'][$i]->product = $product;
                        $data['request_product'][$i]->images = $images;
                        $data['request_product'][$i]->product_pricing = $product_pricing;
                        $data['request_product'][$i]->inventory = $inventory;
                        $data['request_product'][$i]->vendor = $vendor;
                    }
                    $data['activity'] = $this->Request_list_activity_model->limit(35)->order_by('id', 'desc')->get_many_by(array('organization_id' => $organization_id, 'location_id' => $location_id));
                    for ($i = 0; $i < count($data['activity']); $i++) {
                        $images = $this->Images_model->get_by(array('model_name' => 'user', 'model_id' => $data['activity'][$i]->user_id));
                        $users = $this->User_model->get_by(array('id' => $data['activity'][$i]->user_id));
                        $products = $this->Products_model->get_by(array('id' => $data['activity'][$i]->product_id));
                        $data['activity'][$i]->images = $images;
                        $data['activity'][$i]->users = $users;
                        $data['activity'][$i]->products = $products;
                    }
                }
                if(!empty($user_id)){
                    $data['userLicenses'] = $this->User_licenses_model->loadValidLicenses($user_id, 1);
                    if(in_array($_SESSION['role_id'], unserialize(ROLES_TIER1_2)) && empty($data['userLicenses'][$data['locationName']->state])){
                       $this->session->set_flashdata("error", "A valid state license must be verified for purchasing items which require a license for this location.  <a href=\"/profile\" class=\"link\">Manage license.</a>");
                       $data['hasLicense'] = false;
                    } else {
                        $data['hasLicense'] = true;
                    }
                }
            }

            if(!empty($_SESSION['user_buying_clubs'])){
                // make class available to template
                $data['bcModel'] = $this->BuyingClub_model;
                // get buying club prices
                $data['bcPrices'] = $this->BuyingClub_model->getBuyingClubPrices($_SESSION['user_buying_clubs'], $productIds);
                $data['buyingClubs'] = $_SESSION['user_buying_clubs'];
            }
            $data['view_id'] = $location_id;
            $this->load->view('/templates/_inc/header', $data);
            $this->load->view('/templates/account/requests/v/index', $data);
            $this->load->view('/templates/_inc/footer');
            unset($_SESSION['error']);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function update_qty_request_list() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $organization = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
            $organization_id = $organization->organization_id;
            $update_id = $this->input->post('id');
            $qty = $this->input->post('qty');
            $this->Request_list_model->update($update_id, array('quantity' => $qty));
            $locations = $this->Request_list_model->get_by(array('id' => $update_id));
            $location_id = $locations->location_id;
            $product_id = $locations->product_id;
            $requests = array(
                'organization_id' => $organization_id,
                'user_id' => $user_id,
                'product_id' => $product_id,
                'location_id' => $location_id,
                'action' => 'updated',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $this->Request_list_activity_model->insert($requests);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    //single and selected request lists products add to cart
    public function request_list_product_addtocart() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $user = $this->User_model->get_by(array('id' => $user_id));
            $rid = $this->input->post("request_id");
            $location_id = $this->input->post("lid");
            $organization = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
            $organization_id = $organization->organization_id;
            $id = explode(",", $rid);
            for ($i = 0; $i < count($id); $i++) {
                $new = random_string('alnum', 16);
                $requests = $this->Request_list_model->get_by(array('id' => $id[$i]));
                $pricings = $this->Product_pricing_model->get_by(array('product_id' => $requests->product_id, 'vendor_id' => $requests->vendor_id));
                $products = $this->Products_model->get_by(array('id' => $requests->product_id));
                $pro_name = $products->name;
                $product_name = preg_replace('/[^A-Z a-z0-9\-]/', '', $pro_name);
                $product_id = $requests->product_id;
                $vendor_id = $requests->vendor_id;
                $rate = ($pricings->retail_price > 0) ? $pricings->retail_price :  $pricings->price;

                if(!empty($_SESSION['user_buying_clubs'])){
                    // get buying club prices
                    $bcPrices = $this->BuyingClub_model->getBuyingClubPrices($_SESSION['user_buying_clubs'], [$requests->product_id]);
                    $buyingClubs = $_SESSION['user_buying_clubs'];
                    $rate = $this->BuyingClub_model->getBestPrice($requests->product_id, $requests->vendor_id, $bcPrices, $buyingClubs, $rate);
                }

                $pqty = $requests->quantity;
                $license = $products->license_required;
                $cart_details = $this->User_autosave_model->fetchCart($_SESSION['role_id'], $organization_id, $user_id );
                $requests = [
                    'organization_id' => $organization_id,
                    'user_id' => $user_id,
                    'product_id' => $product_id,
                    'location_id' => $location_id,
                    'action' => 'moved item to',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                if ($license == 'Yes') {
                    $user_state = $this->Organization_location_model->get_by(array('id' => $location_id));
                    $state = $user_state->state;
                    $role_id = $_SESSION['role_id'];
                    if ($role_id == '10') {
                        $users = $this->Organization_groups_model->get_many_by(array('organization_id' => $organization_id));
                        for ($a = 0; $a < count($users); $a++) {
                            $admin[] = $this->User_model->get_by(array('id' => $users[$a]->user_id));
                        }
                        for ($b = 0; $b < count($admin); $b++) {
                            if ($admin[$i]->role_id >= '7' && $admin[$b]->role_id <= '9') {
                                $admin_id = $admin[$b]->id;
                                $user_license[] = $this->User_licenses_model->get_by(array('state' => $state, 'user_id' => $admin_id, 'approved' => '1'));
                            }
                        }
                    } else {
                        $user_license = $this->User_licenses_model->get_many_by(array('state' => $state, 'organization_id' => $organization_id, 'approved' => '1'));



                        if (is_null($user_license)){

                            $tier_1_2 = unserialize(ROLES_TIER1_2);
                            if (in_array($user->role_id,$tier_1_2)){
                                $org_users = $this->Organization_groups_model->get_users_by_user($user_id);

                                $user_license[] = $this->User_licenses_model->get_by(['state' => $state, 'user_id' => $org_users, 'approved' => '1']);
                            } else{
                                $user_licenses[] = $user_license;
                            }
                        }
                    }
                    if (count($user_license) > 0) {
                        $cartFlag = FALSE;
                        $today_date = date('Y-m-d');
                        $ExpireFlag = FALSE;
                        foreach ($user_license as $value) {
                            if ($value != null) {
                                $end_date = $value->expire_date;
                                if ($today_date <= $end_date) {
                                    $cartFlag = true;
                                } else {
                                    $ExpireFlag = TRUE;
                                }
                            }
                        }
                        if ($cartFlag) {
                            $bRlist = False;
                            if ($cart_details != null || $cart_details != "") {
                                $cart = $cart_details->cart;
                                $row = json_decode($cart);
                                if ($row != null) {
                                    foreach ($row as $item) {
                                        if ($item->pro_id == $product_id && $item->ven_id == $vendor_id && $item->location_id == $location_id) {
                                            $rowid = $item->rowid;
                                            $quantity = $item->qty;
                                            $new_qty = $pqty + $quantity;
                                            $cart_data = array($rowid => array(
                                                    'rowid' => $rowid,
                                                    'qty' => $new_qty,
                                                    'status' => 0,
                                            ));
                                            $this->cart->update($cart_data);
                                            $this->Request_list_activity_model->insert($requests);
                                            $this->Request_list_model->delete($id[$i]);
                                            $this->session->set_flashdata("success", "Products added to cart successfully");
                                            $bRlist = true;
                                        }
                                    }
                                }
                            }
                            if (!$bRlist) {
                                $list_data = array(
                                    'id' => $new,
                                    'qty' => $pqty,
                                    'name' => $product_name,
                                    'pro_id' => $product_id,
                                    'price' => $rate,
                                    'location_id' => $location_id,
                                    'status' => 0,
                                    'ven_id' => $vendor_id,
                                );
                                $this->cart->insert($list_data);
                                $this->Request_list_activity_model->insert($requests);
                                $this->Request_list_model->delete($id[$i]);
                                $this->session->set_flashdata("success", "Products added to cart successfully");
                            }
                        } else {
                            $this->session->set_flashdata("error", "Unable to purchase item: A valid state license must be verified for location purchasing.  <a href=\"/profile\" class=\"link\">Manage license.</a>");
                        }
                        if ($ExpireFlag) {
                            $this->session->set_flashdata("error", "Unable to purchase item: License has expired.  <a href=\"/profile\" class=\"link\">Manage license.</a>");
                        }
                    } else {
                        $this->session->set_flashdata("error", "Unable to purchase item: A valid state license must be verified for location purchasing.  <a href=\"/profile\" class=\"link\">Manage license.</a>");
                    }
                } else {
                    $bRlistelse = false;
                    if ($cart_details != null || $cart_details != "") {
                        $cart = $cart_details->cart;
                        $row = json_decode($cart);

                        if ($row != null) {
                            foreach ($row as $item) {
                                if ($item->location_id == $location_id && $item->pro_id == $product_id && $item->ven_id == $vendor_id) {
                                    $rowid = $item->rowid;
                                    $quantity = $item->qty;
                                    $new_qty = $pqty + $quantity;
                                    $cart_data = array($rowid => array(
                                            'rowid' => $rowid,
                                            'qty' => $new_qty,
                                            'status' => 0,
                                    ));
                                    $this->cart->update($cart_data);
                                    $this->Request_list_activity_model->insert($requests);
                                    $this->Request_list_model->delete($id[$i]);
                                    $this->session->set_flashdata("success", "Products added to cart successfully");
                                    $bRlistelse = True;
                                }
                            }
                        }
                    }
                    if (!$bRlistelse) {
                        $list_data = array(
                            'id' => $new,
                            'qty' => $pqty,
                            'name' => $product_name,
                            'pro_id' => $product_id,
                            'price' => $rate,
                            'location_id' => $location_id,
                            'status' => 0,
                            'ven_id' => $vendor_id,
                        );
                        $this->cart->insert($list_data);
                        $this->Request_list_activity_model->insert($requests);
                        $this->Request_list_model->delete($id[$i]);
                        $this->session->set_flashdata("success", "Products added to cart successfully");
                    }
                }
            }
            $data['cart_data'] = $this->cart->contents();
            $data['carts'] = $this->User_autosave_model->saveCart($_SESSION['role_id'], $data['cart_data'], $organization->organization_id, $user_id);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    //move all request lists to cart
    public function requests_all_tocart() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $user = $this->User_model->get_by(array('id' => $user_id));
            $location_id = $this->input->post('list_id');
            $data['request_lists'] = $this->Request_list_model->get_many_by(array('location_id' => $location_id));
            $organization = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
            $organization_id = $organization->organization_id;
            for ($i = 0; $i < count($data['request_lists']); $i++) {
                $pricing = $this->Product_pricing_model->get_by(array('product_id' => $data['request_lists'][$i]->product_id, 'vendor_id' => $data['request_lists'][$i]->vendor_id));
                $products = $this->Products_model->get_by(array('id' => $data['request_lists'][$i]->product_id));
                $new = random_string('alnum', 16);
                $p_name = $products->name;
                $product_name = preg_replace('/[^A-Z a-z0-9\-]/', '', $p_name);
                $price = ($pricing->retail_price > 0) ? $pricing->retail_price :  $pricing->price;

                if(!empty($_SESSION['user_buying_clubs'])){
                    // get buying club prices
                    $bcPrices = $this->BuyingClub_model->getBuyingClubPrices($_SESSION['user_buying_clubs'], [$data['request_lists'][$i]->product_id]);
                    $buyingClubs = $_SESSION['user_buying_clubs'];
                    $price = $this->BuyingClub_model->getBestPrice($data['request_lists'][$i]->product_id, $data['request_lists'][$i]->vendor_id, $bcPrices, $buyingClubs, $price);
                }

                $qty = $data['request_lists'][$i]->quantity;
                $pro_id = $data['request_lists'][$i]->product_id;
                $vendor_id = $data['request_lists'][$i]->vendor_id;
                $license = $products->license_required;
                $organization = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
                $organization_id = $organization->organization_id;
                $cart_details = $this->User_autosave_model->fetchCart($_SESSION['role_id'], $organization_id, $user_id);
                $requests = array(
                    'organization_id' => $organization_id,
                    'user_id' => $user_id,
                    'product_id' => $pro_id,
                    'location_id' => $location_id,
                    'action' => 'moved item to',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                if ($license == 'Yes') {
                    $user_state = $this->Organization_location_model->get_by(array('id' => $location_id));
                    $state = $user_state->state;
                    $role_id = $_SESSION['role_id'];
                    if ($role_id == '10') {
                        $users = $this->Organization_groups_model->get_many_by(array('organization_id' => $organization_id));
                        for ($a = 0; $a < count($users); $a++) {
                            $admin[] = $this->User_model->get_by(array('id' => $users[$a]->user_id));
                        }
                        for ($b = 0; $b < count($admin); $b++) {
                            if ($admin[$i]->role_id >= '7' && $admin[$b]->role_id <= '9') {
                                $admin_id = $admin[$b]->id;
                                $user_license[] = $this->User_licenses_model->get_by(array('state' => $state, 'user_id' => $admin_id, 'approved' => '1'));
                            }
                        }
                    } else {
                        $user_license[] = $this->User_licenses_model->get_by(array('state' => $state, 'user_id' => $user_id, 'approved' => '1'));

                        if (is_null($user_license)){
                            $tier_1_2 = unserialize(ROLES_TIER1_2);
                            if (in_array($user->role_id,$tier_1_2)){
                                $org_users = $this->Organization_groups_model->get_users_by_user($user_id);

                                $user_license[] = $this->User_licenses_model->get_by(['state' => $state, 'user_id' => $org_users, 'approved' => '1']);
                            } else{
                                $user_licenses[] = $user_license;
                            }
                        }
                    }
                    if (count($user_license) > 0) {
                        $cartFlag = FALSE;
                        $today_date = date('Y-m-d');
                        $ExpireFlag = FALSE;
                        foreach ($user_license as $value) {
                            if ($value != null) {
                                $end_date = $value->expire_date;
                                if ($today_date <= $end_date) {
                                    $cartFlag = true;
                                } else {
                                    $ExpireFlag = TRUE;
                                }
                            }
                        }
                        if ($cartFlag) {
                            $bFound = False;
                            if ($cart_details != null || $cart_details != "") {
                                $cart = $cart_details->cart;
                                $row = json_decode($cart);
                                if ($row != null) {
                                    foreach ($row as $item) {
                                        if ($item->location_id == $location_id && $item->pro_id == $pro_id && $item->ven_id == $vendor_id) {
                                            $rowid = $item->rowid;
                                            $quantity = $item->qty;
                                            $new_qty = $qty + $quantity;
                                            $cart_data = array($rowid => array(
                                                    'rowid' => $rowid,
                                                    'qty' => $new_qty,
                                                    'status' => 0,
                                            ));
                                            $this->cart->update($cart_data);
                                            $this->Request_list_activity_model->insert($requests);
                                            $this->Request_list_model->delete($data['request_lists'][$i]->id);
                                            $this->session->set_flashdata("success", "Products added to cart successfully");
                                            $data['cart_data'] = $cart_data;
                                            $bFound = True;
                                        }
                                    }
                                }
                            }
                            if (!$bFound) {
                                $item_data = array(
                                    'id' => $new,
                                    'qty' => $qty,
                                    'name' => $product_name,
                                    'pro_id' => $pro_id,
                                    'price' => $price,
                                    'location_id' => $location_id,
                                    'status' => 0,
                                    'ven_id' => $vendor_id,
                                );
                                $this->cart->insert($item_data);
                                $this->Request_list_activity_model->insert($requests);
                                $this->Request_list_model->delete($data['request_lists'][$i]->id);
                                $this->session->set_flashdata("success", "Products added to cart successfully");
                            }
                        } else {
                            $this->session->set_flashdata("error", "Unable to purchase item: A valid state license must be verified for location purchasing.   <a href=\"/profile\" class=\"link\">Manage license.</a>");
                        }
                        if ($ExpireFlag) {
                            $this->session->set_flashdata("error", "Unable to purchase item: License has expired.   <a href=\"/profile\" class=\"link\">Manage license.</a>");
                        }
                    } else {
                        $this->session->set_flashdata("error", "Unable to purchase item: A valid state license must be verified for location purchasing.  <a href=\"/profile\" class=\"link\">Manage license.</a>");
                    }
                } else {
                    //unlicense products to cart
                    $bElse = False;
                    if ($cart_details != null || $cart_details != "") {
                        $cart = $cart_details->cart;
                        $row = json_decode($cart);
                        if ($row != null) {
                            foreach ($row as $item) {
                                if ($item->location_id == $location_id && $item->pro_id == $pro_id && $item->ven_id == $vendor_id) {
                                    $rowid = $item->rowid;
                                    $quantity = $item->qty;
                                    $new_qty = $qty + $quantity;
                                    $cart_data = array($rowid => array(
                                            'rowid' => $rowid,
                                            'qty' => $new_qty,
                                            'status' => 0,
                                    ));
                                    $this->cart->update($cart_data);
                                    $this->Request_list_activity_model->insert($requests);
                                    $this->Request_list_model->delete($data['request_lists'][$i]->id);
                                    $data['cart_data'] = $cart_data;
                                    $bElse = True;
                                }
                            }
                        }
                    }
                    if (!$bElse) {
                        $item_data = array(
                            'id' => $new,
                            'qty' => $qty,
                            'name' => $product_name,
                            'pro_id' => $pro_id,
                            'price' => $price,
                            'location_id' => $location_id,
                            'status' => 0,
                            'ven_id' => $vendor_id,
                        );
                        $this->cart->insert($item_data);
                        $this->Request_list_activity_model->insert($requests);
                        $data['loop'] = "add cart else 2";
                        $this->Request_list_model->delete($data['request_lists'][$i]->id);
                        $this->session->set_flashdata("success", "Products added to cart successfully");
                    }
                }
                echo json_encode($data);
            }
            $data['cart_data'] = $this->cart->contents();
            $this->User_autosave_model->saveCart($_SESSION['role_id'], $data['cart_data'], $organization_id, $user_id);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function remove_request_item() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $delete_id = $this->input->post('request_id');
            $locations = $this->Request_list_model->get_by(array('id' => $delete_id));
            $organization = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
            $organization_id = $organization->organization_id;
            $location_id = $locations->location_id;
            $product_id = $locations->product_id;
            $requests = array(
                'organization_id' => $organization_id,
                'user_id' => $user_id,
                'product_id' => $product_id,
                'location_id' => $location_id,
                'action' => 'removed',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $this->Request_list_activity_model->insert($requests);
            $this->Request_list_model->delete($delete_id);
            $data['location_id'] = $location_id;

            echo json_encode($data);
            //$this->load->view('/templates/_inc/header');
            //$this->load->view('/templates/account/requests/request_list', $data);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function remove_multiple_requests() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $organization = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
            $organization_id = $organization->organization_id;
            $delete_id = explode(",", $this->input->post('user_id'));
            for ($i = 0; $i < count($delete_id); $i++) {
                $locations = $this->Request_list_model->get_by(array('id' => $delete_id[$i]));
                $location_id = $locations->location_id;
                $product_id = $locations->product_id;
                $requests = array(
                    'organization_id' => $organization_id,
                    'user_id' => $user_id,
                    'product_id' => $product_id,
                    'location_id' => $location_id,
                    'action' => 'removed',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                $this->Request_list_activity_model->insert($requests);
            }

            $this->Request_list_model->delete_many($delete_id);
            $data['location_id'] = $location_id;
            echo json_encode($data);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    //change request list vendor
    public function update_vendor_request_product() {
        if (isset($_SESSION["user_id"])) {
            $user_id = $_SESSION['user_id'];
            $update_id = $this->input->post('update_id');
            $vendor_id = $this->input->post('ven_id');
            $organization = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
            $organization_id = $organization->organization_id;
            $request_lists = $this->Request_list_model->get_by(array('id' => $update_id, 'user_id' => $organization_id));
            if ($request_lists != null) {
                $bFlag = FALSE;
                $location_id = $request_lists->location_id;
                $product_id = $request_lists->product_id;
                $request_list_data = $this->Request_list_model->get_by(array('product_id' => $product_id, 'vendor_id' => $vendor_id, 'user_id' => $organization_id, 'location_id' => $location_id));
                if ($request_list_data != null) {
                    $newqty = $request_lists->quantity + $request_list_data->quantity;
                    $update_product_data = array(
                        'quantity' => $newqty,
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    $this->Request_list_model->update($request_list_data->id, $update_product_data);
                    $this->Request_list_model->delete($update_id);
                    $bFlag = TRUE;
                } else {
                    if ($update_id != null) {
                        $update_data = array(
                            'vendor_id' => $vendor_id,
                            'updated_at' => date('Y-m-d H:i:s'),
                        );

                        $this->Request_list_model->update($update_id, $update_data);
                    }
                    $bFlag = TRUE;
                }
                if ($bFlag) {
                    $requests = array(
                        'organization_id' => $organization_id,
                        'user_id' => $user_id,
                        'product_id' => $product_id,
                        'location_id' => $location_id,
                        'action' => 'updated',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    $this->Request_list_activity_model->insert($requests);
                }
            } else {
                $this->session->set_flashdata("error", "Opps,Try again..");
            }
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    //get request list product price
    public function getprice() {
        $request_id = $this->input->post('request_id');
        $id = explode(",", $request_id);
        $rate = 0;
        for ($i = 0; $i < count($id); $i++) {
            $request = $this->Request_list_model->get_by(array('id' => $id[$i]));
            $pricings = $this->Product_pricing_model->get_by(array('product_id' => $request->product_id, 'vendor_id' => $request->vendor_id));
            if ($pricings->retail_price > 0) {
                $pro_price = $pricings->retail_price;
            } else {
                $pro_price = $pricings->price;
            }

            if(!empty($_SESSION['user_buying_clubs'])){
                // get buying club prices
                $bcPrices = $this->BuyingClub_model->getBuyingClubPrices($_SESSION['user_buying_clubs'], [$request->product_id]);
                $buyingClubs = $_SESSION['user_buying_clubs'];
                $pro_price = $this->BuyingClub_model->getBestPrice($request->product_id, $request->vendor_id, $bcPrices, $buyingClubs, $pro_price);
            }

            $qty = $request->quantity;
            $product_cost = $pro_price * $qty;
            $rate = $rate + $product_cost;
        }
        $data['pro_total'] = $rate;
        echo json_encode($data);
    }

    public function send_by_email()
    {
        if($this->input->get('pw') != '73-seventy=Three') die('Invalid access');

        // get all request lists
        $requestLists = $this->Request_list_model->getAllRequestListSummaries();

        // loop through request lists and get product info
        foreach($requestLists as $id => $requestList){
            $requestList->products = $this->Request_list_model->loadProducts($requestList->location_id);
            $users = $this->Request_list_model->getRequestListNotifiableUsers($requestList);
            //mail users
            if(!empty($requestList->products)){
                foreach($users as $user){
                    $this->Request_list_model->mailUser($requestList, $user);
                }
            }
        }
    }

    public function send_by_email_urgent()
    {
        if (isset($_SESSION["user_id"])) {
            // get request list
            $requestList = $this->Request_list_model->getAllRequestListSummaries($this->input->post('locationId'))[0];
            Debugger::debug($requestList, 'requestList');
            $requestList->products = $this->Request_list_model->loadProducts($requestList->location_id);
            $users = $this->Request_list_model->getRequestListNotifiableUsers($requestList);
            // Debugger::debug($requestList);
            Debugger::debug($users, '$users');
            // get product ids
            $productIds = [];
            foreach($requestList->products as $product){
                $productIds[] = $product->id;
            }

            $buyingClubs = $this->BuyingClub_model->loadUserClubs($_SESSION["user_id"]);
            Debugger::debug($buyingClubs);
            if(!empty($_SESSION['user_buying_clubs'])){
                // make class available to template
                // $data['bcModel'] = $this->BuyingClub_model;
                // get buying club prices
                $data['bcPrices'] = $this->BuyingClub_model->getBuyingClubPrices($buyingClubs, $productIds);
                $data['buyingClubs'] = $_SESSION['user_buying_clubs'];
                Debugger::debug($data['bcPrices']);
            }
            foreach($requestList->products as $k => $product){
                $requestList->products[$k]->price = $this->BuyingClub_model->getBestPrice($product->id, $product->vendor_id, $data['bcPrices'], $buyingClubs, $product->retail_price);
            }
            Debugger::debug($requestList);

            //mail users
            if(!empty($requestList->products)){
                foreach($users as $user){
                    // Debugger::debug($user, 'Mailing user');
                    $this->Request_list_model->mailUser($requestList, $user, true);
                }
            }

            echo json_encode([
                'success' => true
            ]);
        } else {
            die('You must be logged in');
        }
    }

    public function getPrices($requestList)
    {

    }
}

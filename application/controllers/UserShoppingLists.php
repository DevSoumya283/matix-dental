<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class UserShoppingLists extends MW_Controller {

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
        $this->load->model('Product_tax_model');
        $this->load->model('Prepopulated_list_model');
        $this->load->model('Prepopulated_product_model');
        $this->load->model('BuyingClub_model');
    }

    //crate new prepopulated lists
    public function newShoppingList() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $list_name = $this->input->post('listName');
            $location_ids = $this->input->post('location_id');
            if ($list_name != null && trim($list_name) !== "") {
                $location_id = explode(",", $location_ids);
                $data['organ_id'] = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
                $organization_id = $data['organ_id']->organization_id;
                if ($location_id != null && $location_id !== "") {
                    $list_exists = $this->Prepopulated_list_model->get_by(array('listname' => $list_name, 'user_id' => $organization_id));
                    if ($list_exists != null) {
                        $this->session->set_flashdata("error", "List name Already exist,Try Again.. ");
                    } else {
                        $insert_data = array(
                            'listname' => $list_name,
                            'user_id' => $organization_id,
                            'location_id' => $location_ids,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        );
                        $this->Prepopulated_list_model->insert($insert_data);
                        $this->session->set_flashdata("success", "List Created Successfully");
                    }
                } else {
                    $this->session->set_flashdata("error", "Failed to create list.  Please select at least 1 location..");
                }
            } else {
                $this->session->set_flashdata("error", "Opps!!!..Try again.");
            }
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("Location: view-product?id=1=" . $product_id);
        }
    }

    //view shopping lists deatails
    public function shopping_lists() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            if (isset($_SESSION['location_id'])) {
                $location_id = $_SESSION['location_id'];
            } else {
                $location_id = "all";
            }
            $list_id = array();
            $organization = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
            $data['prepopulated_list'] = $this->Prepopulated_list_model->get_many_by(array('user_id' => $organization->organization_id));
            $data['locations'] = $this->User_location_model->get_many_by(array('user_id' => $user_id));
            if ($data['prepopulated_list'] != null) {
                if ($location_id == "all") {
                    for ($i = 0; $i < count($data['prepopulated_list']); $i++) {
                        foreach ($data['locations'] as $value) {
                            $listLocationId = explode(',', $data['prepopulated_list'][$i]->location_id);
                            if (in_array($value->organization_location_id, $listLocationId)) {
                                if (!(in_array($data['prepopulated_list'][$i]->id, $list_id))) {
                                    $list_id[] = $data['prepopulated_list'][$i]->id;
                                }
                            }
                        }
                    }
                } else {
                    for ($i = 0; $i < count($data['prepopulated_list']); $i++) {
                        $listLocationId = explode(',', $data['prepopulated_list'][$i]->location_id);
                        if (in_array($location_id, $listLocationId)) {
                            if (!(in_array($data['prepopulated_list'][$i]->id, $list_id))) {
                                $list_id[] = $data['prepopulated_list'][$i]->id;
                            }
                        }
                    }
                }
            }
            $data['populated_products'] = null;
            $data['list_view'] = null;
            $data['prepopulated_lists'] = array();
            if ($list_id != null) {
                for ($k = 0; $k < count($list_id); $k++) {
                    $data['prepopulated_lists'][] = $this->Prepopulated_list_model->get_many_by(array('id' => $list_id[$k]));
                }
                for ($k = 0; $k < count($data['prepopulated_lists']); $k++) {
                    for ($j = 0; $j < count($data['prepopulated_lists'][$k]); $j++) {
                        $data['prepopulated_lists'][$k][$j]->item_count = 0;
                    }
                }
            }

            $productIds = [];

            if ($data['prepopulated_lists'] != null) {
                for ($k = 0; $k < count($data['prepopulated_lists']); $k++) {
                    for ($j = 0; $j < count($data['prepopulated_lists'][$k]); $j++) {
                        $data['list_view'] = $data['prepopulated_lists'][$k][$j];
                        $data['populated_products'] = $this->Prepopulated_product_model->get_many_by(array('list_id' => $data['prepopulated_lists'][$k][$j]->id));
                        for ($i = 0; $i < count($data['populated_products']); $i++) {
                            $productIds[] = $data['populated_products'][$i]->product_id;
                            $product = $this->Products_model->get_by(array('id' => $data['populated_products'][$i]->product_id));
                            $product_pricing = $this->Product_pricing_model->get_by(array('product_id' => $data['populated_products'][$i]->product_id, 'vendor_id' => $data['populated_products'][$i]->vendor_id));
                            $vendor = $this->Vendor_model->get_by(array('id' => $data['populated_products'][$i]->vendor_id));
                            $location = $this->Organization_location_model->get_by(array('id' => $data['populated_products'][$i]->location_id));
                            $images = $this->Images_model->get_by(array('model_id' => $data['populated_products'][$i]->product_id, 'model_name' => 'products', 'image_type' => 'mainimg'));
                            $data['populated_products'][$i]->product = $product;
                            $data['populated_products'][$i]->product_pricing = $product_pricing;
                            $data['populated_products'][$i]->vendor = $vendor;
                            $data['populated_products'][$i]->location = $location;
                            $data['populated_products'][$i]->images = $images;
                        }
                        for ($i = 0; $i < count($data['populated_products']); $i++) {
                            if ($data['populated_products'][$i]->list_id == $data['prepopulated_lists'][$k][$j]->id) {
                                //$data['prepopulated_lists'][$k][$j]->item_count += 1;
                                $data['prepopulated_lists'][$k][$j]->item_count += $data['populated_products'][$i]->quantity;
                            }
                        }
                    }
                }
            }

            if(!empty($_SESSION['user_buying_clubs'])){
                // make class available to template
                $data['bcModel'] = $this->BuyingClub_model;
                // get buying club prices
                $data['bcPrices'] = $this->BuyingClub_model->getBuyingClubPrices($_SESSION['user_buying_clubs'], $productIds);
                $data['buyingClubs'] = $_SESSION['user_buying_clubs'];
                Debugger::debug($data['bcPrices']);
            }

            for ($i = 0; $i < count($data['locations']); $i++) {

                $data['user_locations'][] = $this->Organization_location_model->get_by(array('id' => $data['locations'][$i]->organization_location_id));
            }
            $this->load->view('/templates/_inc/header', $data);
            $this->load->view('/templates/account/lists/index', $data);
            $this->load->view('/templates/_inc/footer');
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    //view seletecd shopping lists details
    public function shoppingListproducts() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $list_id = $this->input->get('id');
            $user_id = $_SESSION['user_id'];
            if ($list_id != null) {
                $PrepopulatedCheck = $this->Prepopulated_list_model->get_by(array('id' => $list_id));
                if (!isset($PrepopulatedCheck)) {
                    $this->session->set_flashdata('error', 'Invalid Entry');
                    header("Location: home");
                } else {
                    $data['list_id'] = $list_id;
                    $data['prepopulated_lists'] = array();
                    $data['populated_products'] = array();
                    $data['list_view'] = null;
                    $data['locations'] = $this->User_location_model->get_many_by(array('user_id' => $user_id));
                    if (isset($_SESSION['location_id'])) {
                        $location_id = $_SESSION['location_id'];
                    } else {
                        $location_id = "all";
                    }
                    $list_ids = array();
                    $organization = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
                    $data['prepopulated_list'] = $this->Prepopulated_list_model->get_many_by(array('user_id' => $organization->organization_id));
                    $data['locations'] = $this->User_location_model->get_many_by(array('user_id' => $user_id));
                    if ($location_id == "all") {
                        for ($i = 0; $i < count($data['prepopulated_list']); $i++) {
                            foreach ($data['locations'] as $value) {
                                $listLocationId = explode(',', $data['prepopulated_list'][$i]->location_id);
                                if (in_array($value->organization_location_id, $listLocationId)) {
                                    if (!(in_array($data['prepopulated_list'][$i]->id, $list_ids))) {
                                        $list_ids[] = $data['prepopulated_list'][$i]->id;
                                    }
                                }
                            }
                        }
                    } else {
                        for ($i = 0; $i < count($data['prepopulated_list']); $i++) {
                            $listLocationId = explode(',', $data['prepopulated_list'][$i]->location_id);
                            if (in_array($location_id, $listLocationId)) {
                                if (!(in_array($data['prepopulated_list'][$i]->id, $list_ids))) {
                                    $list_ids[] = $data['prepopulated_list'][$i]->id;
                                }
                            }
                        }
                    }
                    $data['prepopulated_lists'] = array();
                    if ($list_ids != null) {
                        for ($k = 0; $k < count($list_ids); $k++) {
                            $data['prepopulated_lists'][] = $this->Prepopulated_list_model->get_many_by(array('id' => $list_ids[$k]));
                        }
                    }
                    if ($data['prepopulated_lists'] != null) {
                        for ($k = 0; $k < count($data['prepopulated_lists']); $k++) {
                            for ($j = 0; $j < count($data['prepopulated_lists'][$k]); $j++) {
                                $data['prepopulated_lists'][$k][$j]->item_count = 0;
                                if ($list_id == $data['prepopulated_lists'][$k][$j]->id) {
                                    $data['list_view'] = $data['prepopulated_lists'][$k][$j];
                                }
                                $data['populated_products'] = $this->Prepopulated_product_model->get_many_by(array('list_id' => $data['prepopulated_lists'][$k][$j]->id));
                                for ($i = 0; $i < count($data['populated_products']); $i++) {
                                    $product = $this->Products_model->get_by(array('id' => $data['populated_products'][$i]->product_id));
                                    $product_pricing = $this->Product_pricing_model->get_by(array('product_id' => $data['populated_products'][$i]->product_id, 'vendor_id' => $data['populated_products'][$i]->vendor_id));
                                    $vendor = $this->Vendor_model->get_by(array('id' => $data['populated_products'][$i]->vendor_id));
                                    $location = $this->Organization_location_model->get_by(array('id' => $data['populated_products'][$i]->location_id));
                                    $images = $this->Images_model->get_by(array('model_id' => $data['populated_products'][$i]->product_id, 'model_name' => 'products', 'image_type' => 'mainimg'));
                                    $data['populated_products'][$i]->product = $product;
                                    $data['populated_products'][$i]->product_pricing = $product_pricing;
                                    $data['populated_products'][$i]->vendor = $vendor;
                                    $data['populated_products'][$i]->location = $location;
                                    $data['populated_products'][$i]->images = $images;
                                }
                                for ($i = 0; $i < count($data['populated_products']); $i++) {
                                    if ($data['populated_products'][$i]->list_id == $data['prepopulated_lists'][$k][$j]->id) {
                                        //$data['prepopulated_lists'][$k][$j]->item_count += 1;
                                        $data['prepopulated_lists'][$k][$j]->item_count += $data['populated_products'][$i]->quantity;
                                    }
                                }
                            }
                        }
                    }

                    if(!empty($_SESSION['user_buying_clubs'])){
                        // make class available to template
                        $data['bcModel'] = $this->BuyingClub_model;
                        // get buying club prices
                        $data['bcPrices'] = $this->BuyingClub_model->getBuyingClubPrices($_SESSION['user_buying_clubs'], $productIds);
                        $data['buyingClubs'] = $_SESSION['user_buying_clubs'];
                        Debugger::debug($data['bcPrices']);
                    }

                    if ($list_ids != null) {
                        $data['populated_products'] = $this->Prepopulated_product_model->get_many_by(array('list_id' => $list_id));
                        for ($i = 0; $i < count($data['populated_products']); $i++) {
                            $product = $this->Products_model->get_by(array('id' => $data['populated_products'][$i]->product_id));
                            $product_pricing = $this->Product_pricing_model->get_by(array('product_id' => $data['populated_products'][$i]->product_id, 'vendor_id' => $data['populated_products'][$i]->vendor_id));
                            $vendor = $this->Vendor_model->get_by(array('id' => $data['populated_products'][$i]->vendor_id));
                            $location = $this->Organization_location_model->get_by(array('id' => $data['populated_products'][$i]->location_id));
                            $images = $this->Images_model->get_by(array('model_id' => $data['populated_products'][$i]->product_id, 'model_name' => 'products', 'image_type' => 'mainimg'));
                            $data['populated_products'][$i]->product = $product;
                            $data['populated_products'][$i]->product_pricing = $product_pricing;
                            $data['populated_products'][$i]->vendor = $vendor;
                            $data['populated_products'][$i]->location = $location;
                            $data['populated_products'][$i]->images = $images;
                        }
                    }
                    $data['locations'] = $this->User_location_model->get_many_by(array('user_id' => $user_id));
                    for ($i = 0; $i < count($data['locations']); $i++) {
                        $data['user_locations'][] = $this->Organization_location_model->get_by(array('id' => $data['locations'][$i]->organization_location_id));
                    }
                }
            }

            $this->load->view('/templates/_inc/header', $data);
            $this->load->view('/templates/account/lists/index', $data);
            $this->load->view('/templates/_inc/footer');
        } else {
            $this->session->set_flashdata("error", "Please login to continue.");
            header("Location: login");
        }
    }

    //delete selected shopping list products
    public function deleteshoppingListproducts() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $delete_id = explode(",", $this->input->post('user_id'));
            $this->Prepopulated_product_model->delete_many($delete_id);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    //delete shopping lists name
    public function delete_shopping_list() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $delete_id = $this->input->post('listId');
            $this->Prepopulated_list_model->delete($delete_id);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("Location: view-product?id=1=" . $product_id);
        }
    }

    public function getShoppingListprice() {
        $list_id = $this->input->post('list_id');
        //$location_id = $this->input->post('location_id');
        $update_qty = $this->input->post('update_qty');
        $id = explode(",", $list_id);
        $rate = 0;
        for ($i = 0; $i < count($update_qty); $i++) {
            $shopping_list = $this->Prepopulated_product_model->get_by(array('id' => $id[$i]));
            $location_ids = explode(",", $shopping_list->location_id);
            $location_id = array_values(array_unique($location_ids));
            $data['location_name'] = array();
            for ($j = 0; $j < count($location_id); $j++) {
                if (!(in_array($location_id[$j], $data['location_name']))) {
                    $data['location_name'][] = $this->Organization_location_model->select('id,nickname', 'asc')->get($location_id[$j]);
                }
            }
            $pricings = $this->Product_pricing_model->get_by(array('product_id' => $shopping_list->product_id, 'vendor_id' => $shopping_list->vendor_id));
            if ($pricings->retail_price > 0) {
                $pro_price = $pricings->retail_price;
            } else {
                $pro_price = $pricings->price;
            }
            $product_cost = $pro_price * $update_qty[$i];
            $rate = $rate + $product_cost;
        }
        $data['pro_total'] = $rate;
        echo json_encode($data);
    }

    //selected shooping lists add to cart
    public function selected_list_addtocart() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $user = $this->User_model->get_by(array('id' => $user_id));
            $rid = $this->input->post("list_id");
            $listqty = $this->input->post("qty");
            $organization = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
            $organization_id = $organization->organization_id;
            $id = explode(",", $rid);
            $qty = explode(",", $listqty);
            for ($i = 0; $i < count($id); $i++) {
                $shopping_lists = $this->Prepopulated_product_model->get_by(array('id' => $id[$i]));
                $pricings = $this->Product_pricing_model->get_by(array('product_id' => $shopping_lists->product_id, 'vendor_id' => $shopping_lists->vendor_id));
                $products = $this->Products_model->get_by(array('id' => $shopping_lists->product_id));
                $product_name[] = $products->name;
                $product_id[] = $shopping_lists->product_id;
                $vendor_id[] = $shopping_lists->vendor_id;
                $rate[] = $pricings->price;
                $license[] = $products->license_required;
                $cart_details = $this->User_autosave_model->fetchCart($_SESSION['role_id'], $organization->organization_id, $user_id);
                $location_ids = explode(",", $shopping_lists->location_id);
                $location_id = array_values(array_unique($location_ids));
            }
            $licenceFlag = FALSE; //product check if licencen or not
            $bUnlicenceFlag = FALSE;
            $aLicensed=array();
            for ($i = 0; $i < count($location_id); $i++) {
                for ($j = 0; $j < count($product_id); $j++) {
                    if (ucfirst(strtolower($license[$j])) == 'Yes') {
                         $aLicensed[] = $j;
                        $licenceFlag = TRUE;
                    } else {
                        $bUnlicenceFlag = TRUE;
                    }
                }
                if ($licenceFlag) {
                     //licenced products add to cart here...
                    $user_state = $this->Organization_location_model->get_by(array('id' => $location_id[$i]));
                    $role_id = $_SESSION['role_id'];
                    $cartFlag = FALSE;
                    $ExpireFlag = FALSE;
                    if ($role_id == '10') {
                        $users = $this->Organization_groups_model->get_many_by(array('organization_id' => $organization_id));
                        for ($a = 0; $a < count($users); $a++) {
                            $admin[] = $this->User_model->get_by(array('id' => $users[$a]->user_id));
                        }
                        for ($b = 0; $b < count($admin); $b++) {
                            if ($admin[$i]->role_id >= '7' && $admin[$b]->role_id <= '9') {
                                $admin_id = $admin[$b]->id;
                                $user_license[] = $this->User_licenses_model->get_by(array('state' => $user_state->state, 'user_id' => $admin_id, 'approved' => '1'));
                            }
                        }
                    } else {
                        $user_license = $this->User_licenses_model->get_by(array('state' => $user_state->state, 'user_id' => $user_id, 'approved' => '1'));

                        if (is_null($user_license)){
                            unset($user_license);
                            $tier_1_2 = unserialize(ROLES_TIER1_2);
                            if (in_array($user->role_id,$tier_1_2)){
                                $org_users = $this->Organization_groups_model->get_users_by_user($user_id);

                                $user_license[] = $this->User_licenses_model->get_by(['state' => $user_state->state, 'user_id' => $org_users, 'approved' => '1']);

                            }
                        }

                        $today_date = date('Y-m-d');
                        if ($user_license != null) {
                            $end_date = $user_license->expire_date;
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
                                    if ($item->location_id == $location_id[$i]) {
                                        for ($j = 0; $j < count($product_id); $j++) {
                                            if ($item->pro_id == $product_id[$j] && $item->ven_id == $vendor_id[$j]) {
                                                $rowid = $item->rowid;
                                                $quantity = $item->qty;
                                                $new_qty = $qty[$j] + $quantity;
                                                $cart_data = array($rowid => array(
                                                        'rowid' => $rowid,
                                                        'qty' => $new_qty,
                                                        'status' => 0,
                                                ));
                                                $this->cart->update($cart_data);
                                                $data['test'][] = '1';
                                                $this->session->set_flashdata("success", "Products added to cart successfully");
                                                $bRlist = true;
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            $bRlist = False;
                        }
                        if (!$bRlist) {
                            for ($j = 0; $j < count($product_id); $j++) {
                                 $new = random_string('alnum', 16);
                                //$new_qty = $qty[$j];
                                $list_data = array(
                                    'id' => $new,
                                    'qty' => $qty[$j],
                                    'name' => $product_name[$j],
                                    'pro_id' => $product_id[$j],
                                    'price' => $rate[$j],
                                    'location_id' => $location_id[$i],
                                    'status' => 0,
                                    'ven_id' => $vendor_id[$j],
                                );
                                 $this->cart->insert($list_data);
                             }
                              $this->session->set_flashdata("success", "Products added to cart successfully");
                        }
                    } else {
                        $this->session->set_flashdata("error", "Unable to purchase item: A license is required.");
                    }
                    if ($ExpireFlag) {
                        $this->session->set_flashdata("error", "Unable to purchase item: License has expired.");
                    }
                }

                if ($bUnlicenceFlag) { //unlicenced products
                    $bRlistelse = FALSE;
                    if ($cart_details != null || $cart_details != "") {
                        $cart = $cart_details->cart;
                        $row = json_decode($cart);
                        if ($row != null) {
                            foreach ($row as $item) {
                                if ($item->location_id == $location_id[$i]) {
                                    for ($j = 0; $j < count($product_id); $j++) {
                                        if (!(in_array($j, $aLicensed))) {
                                        if ($item->pro_id == $product_id[$j] && $item->ven_id == $vendor_id[$j]) {
                                            $rowid = $item->rowid;
                                            $quantity = $item->qty;
                                            $new_qty = $qty[$j] + $quantity;
                                            $cart_data = array($rowid => array(
                                                    'rowid' => $rowid,
                                                    'qty' => $new_qty,
                                                    'status' => 0,
                                            ));
                                            $this->cart->update($cart_data);
                                            $this->session->set_flashdata("success", "Products added to cart successfully");
                                            $bRlistelse = TRUE;
                                        }
                                    }
                                  }
                                }
                            }
                        }
                     } else {
                        $bRlistelse = FALSE;
                    }
                    if (!$bRlistelse) {
                        for ($j = 0; $j < count($product_id); $j++) {
                            if (!(in_array($j, $aLicensed))) {
                             $new = random_string('alnum', 16);
                            $list_data = array(
                                'id' => $new,
                                'qty' => $qty[$j],
                                'name' => $product_name[$j],
                                'pro_id' => $product_id[$j],
                                'price' => $rate[$j],
                                'location_id' => $location_id[$i],
                                'status' => 0,
                                'ven_id' => $vendor_id[$j],
                            );
                           $this->cart->insert($list_data);
                        }
                       }
                        $this->session->set_flashdata("success", "Products added to cart successfully");
                    }
                }
            }

            $data['cart_data'] = $this->cart->contents();
            $this->User_autosave_model->saveCart($_SESSION['role_id'], $data['cart_data'], $organization_id, $user_id);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    //move all shopping lists products to cart
    public function shopping_all_tocart() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $user = $this->User_model->get_by('id', $user_id);
            $list_id = $this->input->post('list_id');
            $location_details = $this->Prepopulated_list_model->get($list_id);
            $data['shopping_lists'] = $this->Prepopulated_product_model->get_many_by(array('list_id' => $list_id));
            $organization = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
            $organization_id = $organization->organization_id;
            $location_ids = explode(",", $location_details->location_id);
            $location_id = array_values(array_unique($location_ids));
            for ($i = 0; $i < count($data['shopping_lists']); $i++) {
                $pricing = $this->Product_pricing_model->get_by(array('product_id' => $data['shopping_lists'][$i]->product_id, 'vendor_id' => $data['shopping_lists'][$i]->vendor_id));
                $products = $this->Products_model->get_by(array('id' => $data['shopping_lists'][$i]->product_id));
                $new = random_string('alnum', 16);
                $p_name = $products->name;
                $product_name = preg_replace('/[^A-Z a-z0-9\-]/', '', $p_name);
                $price = $pricing->price;
                $qty = $data['shopping_lists'][$i]->quantity;
                $pro_id = $data['shopping_lists'][$i]->product_id;
                $vendor_id = $data['shopping_lists'][$i]->vendor_id;
                $license = $products->license_required;
                $organization = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
                $organization_id = $organization->organization_id;
                $cart_details = $this->User_autosave_model->fetchCart($_SESSION['role_id'], $organization->organization_id, $user_id);
                for ($p = 0; $p < count($location_id); $p++) {
                    if ($license == 'Yes') {
                        $user_state = $this->Organization_location_model->get_by(array('id' => $location_id[$p]));
                        $state = $user_state->state;
                        $role_id = $_SESSION['role_id'];
                        if ($role_id == '10') { //students licence check
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
                        } else { //admin licence check
                            $user_license = $this->User_licenses_model->get_by(array('state' => $state, 'user_id' => $user_id, 'approved' => '1'));

                            if (is_null($user_license)){
                                $tier_1_2 = unserialize(ROLES_TIER1_2);
                                if (in_array($user->role_id,$tier_1_2)){
                                    $org_users = $this->Organization_groups_model->get_users_by_user($user_id);

                                    $user_licenses[] = $this->User_licenses_model->get_by(['state' => $state, 'user_id' => $org_users, 'approved' => '1']);
                                } else{
                                    $user_licenses[] = $user_license;
                                }
                            }

                        }
                        if (count($user_license) > 0) {
                            $today_date = date('Y-m-d');
                            $cartFlag = FALSE;
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
                                            if ($item->location_id == $location_id[$p] && $item->pro_id == $data['shopping_lists'][$i]->product_id && $item->ven_id == $data['shopping_lists'][$i]->vendor_id) {
                                                $rowid = $item->rowid;
                                                $quantity = $item->qty;
                                                $new_qty = $qty + $quantity;
                                                $cart_data = array($rowid => array(
                                                        'rowid' => $rowid,
                                                        'qty' => $new_qty,
                                                        'status' => 0,
                                                ));
                                                $this->cart->update($cart_data);
                                                $this->session->set_flashdata("success", "Products added to cart successfully");
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
                                        'location_id' => $location_id[$p],
                                        'status' => 0,
                                        'ven_id' => $vendor_id,
                                    );
                                    $this->cart->insert($item_data);
                                    $this->session->set_flashdata("success", "Products added to cart successfully");
                                }
                            } else {
                                $this->session->set_flashdata("error", "Unable to purchase item: A license is required.");
                            }
                            if ($ExpireFlag) {
                                $this->session->set_flashdata("error", "Unable to purchase item: License has expired.");
                            }
                        } else {
                            $this->session->set_flashdata("error", "Unable to purchase item: A license is required.");
                        }
                    } else {
                        $bElse = False;
                        if ($cart_details != null || $cart_details != "") {
                            $cart = $cart_details->cart;
                            $row = json_decode($cart);
                            if ($row != null) {
                                foreach ($row as $item) {
                                    if ($item->location_id == $location_id[$p] && $item->pro_id == $pro_id && $item->ven_id == $vendor_id) {
                                        $rowid = $item->rowid;
                                        $quantity = $item->qty;
                                        $new_qty = $qty + $quantity;
                                        $cart_data = array($rowid => array(
                                                'rowid' => $rowid,
                                                'qty' => $new_qty,
                                                'status' => 0,
                                        ));
                                        $this->cart->update($cart_data);
                                        $this->session->set_flashdata("success", "Products added to cart successfully");
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
                                'location_id' => $location_id[$p],
                                'status' => 0,
                                'ven_id' => $vendor_id,
                            );
                            $this->cart->insert($item_data);
                            $this->session->set_flashdata("success", "Products added to cart successfully");
                        }
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

    //add single products to existing shopping lists
    public function addto_shoppinglist() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $flag = TRUE;
            $product_id = $this->input->post('pro_id');
            $vendor_id = $this->input->post('vendor_id');
            $list_id = $this->input->post('list_id');
            $location_details = $this->Prepopulated_list_model->get($list_id);
            $location_ids = $location_details->location_id;
            $insert_data = array(
                'list_id' => $list_id,
                'user_id' => $user_id,
                'product_id' => $product_id,
                'vendor_id' => $vendor_id,
                'quantity' => '1',
                'location_id' => $location_ids,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            );
            $this->Prepopulated_product_model->insert($insert_data);
            $this->session->set_flashdata("success", "Products added to List successfully");
        } else {
            header("Location: view-product?id=1=" . $product_id);
        }
    }

    //add single products to new shopping lists
    public function add_new_shopinglist() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $list_name = $this->input->post('listName');
            $location_ids = $this->input->post('location_id');
            $user_id = $_SESSION['user_id'];
            if ($list_name != null && trim($list_name) !== "") {
                $data['organ_id'] = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
                $organization_id = $data['organ_id']->organization_id;
                $list_exists = $this->Prepopulated_list_model->get_by(array('listname' => $list_name, 'user_id' => $organization_id));
                if ($list_exists != null) {
                    $this->session->set_flashdata("error", "List name Already exist,Try Again.. ");
                } else {
                    $location_id = explode(",", $location_ids);
                    if ($location_id != null && $location_id !== "") {
                        $insert_data = array(
                            'listname' => $list_name,
                            'user_id' => $organization_id,
                            'location_id' => $location_ids,
                            'created_at' => date('Y-m-d H:i:s'),
                        );
                        $this->Prepopulated_list_model->insert($insert_data);
                        $list_id = $this->db->insert_id();
                        if ($list_id != null) {
                            $product_id = $this->input->post('product_id');
                            $v_id = $this->input->post('v_id');
                            $shoping_data = array(
                                'list_id' => $list_id,
                                'user_id' => $user_id,
                                'product_id' => $product_id,
                                'vendor_id' => $v_id,
                                'quantity' => '1',
                                'location_id' => $location_ids,
                                'created_at' => date('Y-m-d H:i:s'),
                            );
                            $this->Prepopulated_product_model->insert($shoping_data);
                            $this->session->set_flashdata("success", "Products added to List successfully");
                        }
                    } else {
                        $this->session->set_flashdata("error", "List cann't be create,Select atleast One Location..");
                    }
                }
            } else {
                $this->session->set_flashdata("error", "Opps!!!..Try again.");
            }
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("Location: view-product?id=1=" . $product_id);
        }
    }

    //delete single product in shopping lists from products view
    public function delete_shoping_products() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $list_id = $this->input->post('list_id');
            $product_id = $this->input->post('product_id');
            $lists_details = $this->Prepopulated_product_model->get_by(array('list_id' => $list_id, 'product_id' => $product_id));
            $this->Prepopulated_product_model->delete($lists_details->id);
            $this->session->set_flashdata("error", "This Product Deleted From List ..");
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    //update shopping lists name
    public function update_listname() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $id = $this->input->post('list_id');
            $user_id = $_SESSION['user_id'];
            $list_name = $this->input->post('listName');
            if ($list_name != null && trim($list_name) !== "") {
                $data['organ_id'] = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
                $organization_id = $data['organ_id']->organization_id;
                $list_exists = $this->Prepopulated_list_model->get_by(array('listname' => $list_name, 'user_id' => $organization_id));
                if ($list_exists != null) {
                    $this->session->set_flashdata("error", "List name Already exist,Try Again.. ");
                } else {
                    $update_data = array(
                        'listname' => $list_name,
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    $this->Prepopulated_list_model->update($id, $update_data);
                    $this->session->set_flashdata("success", "List Name Updated Successfully");
                }
            } else {
                $this->session->set_flashdata("error", "Oops!!! Try Again..");
            }
            header("Location: shopping-products?id=" . $id);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    //change prepopulated lists vendor
    public function changeShoppingLists_productVendor() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $update_id = $this->input->post('update_id');
            $vendor_id = $this->input->post('ven_id');
            if ($update_id != null) {
                $update_data = array(
                    'vendor_id' => $vendor_id,
                    'updated_at' => date('Y-m-d H:i:s'),
                );

                $this->Prepopulated_product_model->update($update_id, $update_data);
            }
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

}

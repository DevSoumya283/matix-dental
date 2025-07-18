<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends MW_Controller {

    function __construct() {

        parent::__construct();
        $this->load->model('Organization_model');
        $this->load->model('Organization_groups_model');
        $this->load->model('User_location_model');
        $this->load->model('Organization_location_model');
        $this->load->model('Products_model');
        $this->load->model('Product_pricing_model');
        $this->load->model('Vendor_model');
        $this->load->model('User_model');
        $this->load->model('Order_model');
        $this->load->model('Order_items_model');
        $this->load->model('Order_promotion_model');
        $this->load->model('Shipping_options_model');
        $this->load->model('User_payment_option_model');
        $this->load->model('Images_model');
        $this->load->model('User_licenses_model');
        $this->load->model('User_autosave_model');
        $this->load->model('Promo_codes_model');
        $this->load->model('Product_tax_model');
        $this->load->model('Request_list_model');
        $this->load->model('Request_list_activity_model');
        $this->load->library('cart');
        $this->load->library('email');
        $this->load->library('stripe');
        $this->load->helper('my_email_helper');
    }

    public function cart_view() {
        $tier_1_2_roles = unserialize(ROLES_TIER1_2);
        $tier_3roles = unserialize(ROLES_TIER3);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $tier_1_2_roles))) {
            $user_id = $_SESSION['user_id'];
            $user = $this->User_model->get_by(array('id' => $user_id));



            $l_id = $this->input->get('id');
            if ($l_id != null) {

                if($user->stripe_id){
                    $paymentMethods = $this->get_payment_methods($user);
                }else{
                    $paymentMethods = [];
                }

                $organization_id = $this->Organization_groups_model->get_by(array('user_id' => $user_id))->organization_id;

                // tier 1 - get all payment methods for the organisation
                if(in_array($user->role_id, unserialize(ROLES_TIER1))){
                    $organization_accounts = $this->Organization_groups_model->organizationGroup_users($organization_id, '');
                    foreach($organization_accounts as $account){
                        if($account->stripe_id && $account->user_id != $user->user_id){
                            $paymentMethods = array_merge($paymentMethods, $this->get_payment_methods($account));
                        }
                    }
                }


                // tier 2 - get tier 1 payment methods
                if(in_array($user->role_id, unserialize(ROLES_TIER2))){
                    $orgAdminId = $this->Organization_model->get_by(['id' => $organization_id])->admin_user_id;
                    $orgAdminUser = $this->User_model->get_by(['id' => $orgAdminId]);
                    $paymentMethods = array_merge($paymentMethods, $this->get_payment_methods($orgAdminUser));
                }


                $data['user_payment_methods'] = $paymentMethods;

                

                if(!empty($user->default_payment_method)){
                    foreach($paymentMethods as $method){
                        if($method->id == $user->default_payment_method){
                            $data['user_payment_method'] = $method;
                        }
                    }
                } else {
                    $data['user_payment_method'] = reset($paymentMethods);
                }

                $data['user'] = $user;
                $data['locations'] = $this->User_location_model->get_many_by(array('user_id' => $user_id));
                for ($i = 0; $i < count($data['locations']); $i++) {
                    $data['user_locations'][] = $this->Organization_location_model->get_by(array('id' => $data['locations'][$i]->organization_location_id));
                }
                $data['cart'] = $this->cart->contents();
                if ($data['cart'] != null) {
                    $independent_vendors = array();
                    $matix_vendors = array();
                    $i = 0;
                    foreach ($data['cart'] as $item) {
                        if ($item['location_id'] == $l_id && $item['status'] == 0) {
                            $vendors = $this->Vendor_model->get($item['ven_id']);
                            Debugger::debug($data['cart'], '$cart');
                            Debugger::debug($item, '$item');
                            Debugger::debug($vendors, '$vendors');
                            if ($i == 0) {
                                if ($vendors->vendor_type == '1') {
                                    $matix_vendors[$item['ven_id']] = array();
                                    $matix_vendors[$item['ven_id']][] = $item;
                                } else {
                                    $independent_vendors[$item['ven_id']] = array();
                                    $independent_vendors[$item['ven_id']][] = $item;
                                }
                            } else {
                                if ($vendors->vendor_type == '1') {
                                    if (isset($matix_vendors[$item['ven_id']])) {
                                        $matix_vendors[$item['ven_id']][] = $item;
                                    } else {
                                        $matix_vendors[$item['ven_id']] = array();
                                        $matix_vendors[$item['ven_id']][] = $item;
                                    }
                                } else {
                                    if (isset($independent_vendors[$item['ven_id']])) {
                                        $independent_vendors[$item['ven_id']][] = $item;
                                    } else {
                                        $independent_vendors[$item['ven_id']] = array();
                                        $independent_vendors[$item['ven_id']][] = $item;
                                    }
                                }
                            }
                            //set vendor shipping values
                            $data['lowestshipping'] = $this->Shipping_options_model->order_by('shipping_price', 'ASC')->get_by(array('vendor_id' => $item['ven_id']));
                            if ($data['lowestshipping'] != null && $data['lowestshipping'] !== "") {
                                if (!(isset($_SESSION['session_shipping'][$data['lowestshipping']->vendor_id]))) {
                                    $shipping_array = array(
                                        'shipvendor' => $data['lowestshipping']->vendor_id,
                                        'shipid' => $data['lowestshipping']->id,
                                        'shippingprice' => $data['lowestshipping']->shipping_price
                                    );
                                    $_SESSION['session_shipping'][$data['lowestshipping']->vendor_id] = $shipping_array;
                                }
                            }
                            $i += 1;
                        }
                    }
                    //get tax details
                    $data['user_zip'] = $this->Organization_location_model->get_by(array('id' => $l_id));
                    $zip_code = $data['user_zip']->zip;
                    $data['tax_details'] = $this->Product_tax_model->get_by(array('ZipCode' => $zip_code));
                    $data['shipping'] = $this->Shipping_options_model->get_all();
                    // Debugger::debug($data);
                    $data['cart_details'] = $independent_vendors;
                    $data['matix_vendor'] = $matix_vendors;
                    $array_keys_cart = array_keys($data['cart_details']);
                    $matix_cart_keys = array_keys($data['matix_vendor']);
                    $k = 0;
                    $total = 0;
                    $tax = 0;
                    $m = 0;
                    //matix vendor detials
                    foreach ($data['matix_vendor'] as $row) {
                        for ($i = 0; $i < count($row); $i++) {
                            $products = $this->Products_model->get_by(array('id' => $row[$i]['pro_id']));
                            $data['vendors'] = $this->Vendor_model->get_all();
                            $price = $this->Product_pricing_model->get_by(array('product_id' => $row[$i]['pro_id'], 'vendor_id' => $row[$i]['ven_id']));
                            $data['image'] = $this->Images_model->get_many_by(array('model_name' => 'vendor'));
                            $data['matix_vendor'][$matix_cart_keys[$m]][$i] = new StdClass;
                            $data['matix_vendor'][$matix_cart_keys[$m]][$i]->products = $products;
                            $data['matix_vendor'][$matix_cart_keys[$m]][$i]->price = $price;
                            $data['matix_vendor'][$matix_cart_keys[$m]][$i]->cart = $row[$i];
                            $subtotal = $data['matix_vendor'][$matix_cart_keys[$m]][$i]->cart['subtotal'];
                            if ($data['tax_details'] != null) {
                                $tax = $subtotal * $data['tax_details']->EstimatedCombinedRate;
                            }
                            $total = $total + $subtotal + $tax;
                        }

                        $m += 1;
                    }
                    //Independent vendor details
                    foreach ($data['cart_details'] as $row) {
                        for ($i = 0; $i < count($row); $i++) {
                            $products = $this->Products_model->get_by(array('id' => $row[$i]['pro_id']));
                            $data['vendors'] = $this->Vendor_model->get_all();
                            $price = $this->Product_pricing_model->get_by(array('product_id' => $row[$i]['pro_id'], 'vendor_id' => $row[$i]['ven_id']));
                            $data['lowestshipping'] = $this->Shipping_options_model->order_by('shipping_price', 'ASC')->get_by(array('vendor_id' => $row[$i]['ven_id']));
                            $data['image'] = $this->Images_model->get_many_by(array('model_name' => 'vendor'));
                            $data['cart_details'][$array_keys_cart[$k]][$i] = new StdClass;
                            $data['cart_details'][$array_keys_cart[$k]][$i]->products = $products;
                            $data['cart_details'][$array_keys_cart[$k]][$i]->price = $price;
                            $data['cart_details'][$array_keys_cart[$k]][$i]->cart = $row[$i];

                            $subtotal = $data['cart_details'][$array_keys_cart[$k]][$i]->cart['subtotal'];
                            if ($data['tax_details'] != null) {
                                $tax = $subtotal * $data['tax_details']->EstimatedCombinedRate;
                            }
                            $total = $total + $subtotal + $tax;
                        }
                        $k += 1;
                    }
                    $this->updatePromoDetails($l_id);
                    $data['total'] = $total;
                    $data['location_id'] = $l_id;
                    $this->load->view('/templates/cart/index', $data);
                } else {
                    header("Location:home");
                }
            } else {
                header("Location:home");
            }
        } else if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $tier_3roles))) {
            header("Location:home");
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function shipping_price_list() { //get shipping price detials
        $shipping_id = $this->input->post("s_type");
        $cart_vendor_id = $this->input->post("vendor_id");
        if ($shipping_id != "") {
            $data['s_price'] = $this->Shipping_options_model->get_by(array('id' => $shipping_id));
            $shipvendor = $data['s_price']->vendor_id;
            $shipping_price = $data['s_price']->shipping_price;
            $bFound = false;
            if (isset($_SESSION['session_shipping'])) { //set shipping options in session
                $session_count = count($_SESSION['session_shipping']);
                $shippings = $_SESSION['session_shipping'];
                $shippingkey = array_keys($_SESSION['session_shipping']);
                for ($k = 0; $k < $session_count; $k++) {
                    if ($shippings[$shippingkey[$k]]['shipvendor'] == $cart_vendor_id) {
                        $id = $shippings[$shippingkey[$k]]['shipvendor'];
                        unset($_SESSION['session_shipping'][$id]);
                        $bFound = TRUE;
                    }
                }
            }
            if ($bFound) {
                $id = random_string('alnum', 16);
                $shipid = $shipping_id;
                $shipping_array = array(
                    'shipvendor' => $shipvendor,
                    'shipid' => $shipid,
                    'shippingprice' => $shipping_price
                );
                $_SESSION['session_shipping'][$shipvendor] = $shipping_array;
            } else {
                $id = random_string('alnum', 16);
                $shipid = $shipping_id;
                $shipping_array = array(
                    'shipvendor' => $shipvendor,
                    'shipid' => $shipid,
                    'shippingprice' => $shipping_price
                );
                $_SESSION['session_shipping'][$shipvendor] = $shipping_array;
            }
        } else {
            if (isset($_SESSION['session_shipping'])) {
                $session_count = count($_SESSION['session_shipping']);
                $shippings = $_SESSION['session_shipping'];
                $shippingkey = array_keys($_SESSION['session_shipping']);
                for ($k = 0; $k < $session_count; $k++) {
                    if ($shippings[$shippingkey[$k]]['shipvendor'] == $cart_vendor_id) {
                        $id = $shippings[$shippingkey[$k]]['shipvendor'];
                        unset($_SESSION['session_shipping'][$id]);
                        $bFound = TRUE;
                    }
                }
            }
        }
    }

    public function updatePromoDetails($location_id) {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $tax_location = $this->Organization_location_model->get_by(array('id' => $location_id));
            $tax_data = $this->Product_tax_model->get_by(array('ZipCode' => $tax_location->zip));
            if (isset($_SESSION['session_promos'])) {
                $session_promos = $_SESSION['session_promos'];
                $_SESSION['session_promos'] = array();
                // Sort Promos based on priority.
                // Price - 1,promo-2, Shipping - 4, Tax - 3
                $priority = array();
                foreach ($session_promos as $key => $row) {
                    $priority[$key] = $row['priority'];
                }
                array_multisort($priority, SORT_ASC, $session_promos);
                foreach ($session_promos as $item) {
                    if (($item['promocode'] != null || $item['promocode'] != "") && $item['promolocation'] == $location_id) {
                        $promo_code = $item['promocode'];
                        $data['promo'] = $this->Promo_codes_model->get_by(array('code' => $promo_code, 'active' => '1'));
                        if ($data['promo'] != null) {
                            $data['cart'] = $this->cart->contents();
                            $vendor_id = $data['promo']->vendor_id;
                            $start_date = date("Y-m-d");
                            $end_date = $data['promo']->end_date;
                            if (strtotime($end_date) <= 0) {
                                $end_date = date("Y-m-d");
                            }
                            if (strtotime($data['promo']->start_date) > 0) {
                                $start_date = date("Y-m-d", strtotime($data['promo']->start_date));
                            }
                            if (strtotime($data['promo']->end_date) >= strtotime(date("Y-m-d"))) {
                                $end_date = date("Y-m-d", strtotime($data['promo']->end_date));
                            }
                            $today_date = date('Y-m-d');
                            $user_qty = 0;
                            $subtotal_discount = 0.00;
                            $shipping_discount = 0.00;
                            $tax_discount = 0.00;
                            $promodiscount = 0.00;
                            $product_discount = 0.00;
                            $subtotal = 0.00;
                            $product_totals = 0;
                            $product_tax = 0;
                            $cart_tax = 0;
                            $data['error'] = "";
                            $session_product_discount = 0;
                            $session_shipping_dicount = 0;
                            foreach ($data['cart'] as $item) {
                                if ($item['ven_id'] == $data['promo']->vendor_id && $item['location_id'] == $location_id) {
                                    $subtotal += $item['subtotal'];
                                    $user_qty += $item['qty'];
                                }
                                if ($data['promo']->product_id != null) {
                                    if ($item['pro_id'] == $data['promo']->product_id && $item['ven_id'] == $data['promo']->vendor_id && $item['location_id'] == $location_id) {
                                        $product_totals += $item['subtotal'];
                                    }
                                }
                                if (isset($_SESSION['session_promos'])) {
                                    foreach ($_SESSION['session_promos'] as $promo_items) {
                                        if ($promo_items['promolocation'] == $location_id && $promo_items['vendorid'] == $data['promo']->vendor_id) {
                                            $cart_tax = $promo_items['product_tax'];
                                            $session_product_discount = $promo_items['product_discount'];
                                            $session_final_price_dicount = $promo_items['final_price_dicount'];
                                            $session_shipping_dicount = $promo_items['shipping_discount'];
                                        }
                                    }
                                }
                            }
                            foreach ($data['cart'] as $item) {
                                if ($data['promo']->product_id != null) {
                                    //Products Promo codes starts to apply here....
                                    if ($item['ven_id'] == $vendor_id && $item['location_id'] == $location_id) {
                                        $product_qty = $item['qty'];
                                        if ($today_date <= $end_date && $today_date >= $start_date) {
                                            if ((($data['promo']->threshold_type == '2') && ($product_qty == $data['promo']->threshold_count)) || (($data['promo']->threshold_type == '1') && ($product_qty >= $data['promo']->threshold_count))) {
                                                if ($item['pro_id'] == $data['promo']->product_id && $item['ven_id'] == $data['promo']->vendor_id && $item['location_id'] == $location_id) {
                                                    $product_total = $item['subtotal'];
                                                    if ($data['promo']->discount_type == '%') {
                                                        if ($data['promo']->discount_on == 'Final Price') {
                                                            if ($session_product_discount > 0) {
                                                                $new_total = $product_total - $session_product_discount;
                                                                $product_discount = $new_total * (($data['promo']->discount) / 100);
                                                                $promodiscount = $product_discount;
                                                            } else {
                                                                $product_discount = $product_total * (($data['promo']->discount) / 100);
                                                                $promodiscount = $product_discount;
                                                            }
                                                            $cart_product_subtotal = $subtotal - $promodiscount;
                                                            $product_tax = round($cart_product_subtotal * $tax_data->EstimatedCombinedRate, 2);
                                                            $data['error'] = "";
                                                            $priority = '1';
                                                        } else if ($data['promo']->discount_on == 'Shipping') {
                                                            if (isset($_SESSION['session_shipping'])) {
                                                                foreach ($_SESSION['session_shipping'] as $key) {
                                                                    if ($key['shipvendor'] == $item['ven_id']) {
                                                                        if ($key['shippingprice'] != null) {
                                                                            if ($session_shipping_dicount > 0) {
                                                                                $new_shipping = $key['shippingprice'] - $session_shipping_dicount;
                                                                                $shipping_discount = $new_shipping * (($data['promo']->discount) / 100);
                                                                                $promodiscount = $shipping_discount;
                                                                            } else {
                                                                                $shipping_discount = $key['shippingprice'] * (($data['promo']->discount) / 100);
                                                                                $promodiscount = $shipping_discount;
                                                                            }
                                                                            $data['error'] = "";
                                                                            $priority = '4';
                                                                        } else {
                                                                            $data['error'] = "Please Select Your shipping";
                                                                        }
                                                                    }
                                                                }
                                                            } else {
                                                                $data['error'] = "Please Select Your shipping";
                                                            }
                                                        }
                                                    } else if ($data['promo']->discount_type == '$') {
                                                        if ($data['promo']->discount_on == 'Final Price') {
                                                            if ($session_product_discount > 0) {
                                                                $new_total = $product_total - $session_product_discount;
                                                                $subtotal_discount = $new_total - ($data['promo']->discount);
                                                                $promodiscount = $data['promo']->discount;
                                                                $product_discount = $data['promo']->discount;
                                                            } else {
                                                                $subtotal_discount = $product_total - ($data['promo']->discount);
                                                                $promodiscount = $data['promo']->discount;
                                                                $product_discount = $data['promo']->discount;
                                                            }

                                                            $cart_product_subtotal = $subtotal - $product_discount;
                                                            $product_tax = round($cart_product_subtotal * $tax_data->EstimatedCombinedRate, 2);
                                                            $data['error'] = "";
                                                            $priority = '1';
                                                        } else if ($data['promo']->discount_on == 'Shipping') {
                                                            if (isset($_SESSION['session_shipping'])) {
                                                                foreach ($_SESSION['session_shipping'] as $key) {
                                                                    if ($key['shipvendor'] == $item['ven_id']) {
                                                                        if ($key['shippingprice'] != null) {
                                                                            if ($session_shipping_dicount > 0) {
                                                                                $new_shipping = $key['shippingprice'] - $session_shipping_dicount;
                                                                                $shipping_discount = $new_shipping - ($data['promo']->discount);
                                                                                $promodiscount = $shipping_discount;
                                                                            } else {
                                                                                $shipping_discount = $key['shippingprice'] - ($data['promo']->discount);
                                                                                $promodiscount = $shipping_discount;
                                                                            }

                                                                            $data['error'] = "";
                                                                            $priority = '4';
                                                                        } else {
                                                                            $data['error'] = "Please Select Your shipping";
                                                                        }
                                                                    }
                                                                }
                                                            } else {
                                                                $data['error'] = "Please Select Your shipping";
                                                            }
                                                        }
                                                    }

                                                    if ($data['error'] == "") {
                                                        $promoid = $data['promo']->id;
                                                        $product_id = $data['promo']->product_id;
                                                        $use_with_promos = $data['promo']->use_with_promos;
                                                        $pid = random_string('alnum', 16);
                                                        if ($data['promo']->free_product_id != null || $data['promo']->free_product_id != "") {
                                                            $freeproducts = $this->Products_model->get_by(array('id' => $data['promo']->free_product_id));
                                                            $free_prodcutpricings = $product_pricing = $this->Product_pricing_model->get_by(array('product_id' => $data['promo']->free_product_id, 'vendor_id' => $vendor_id));
                                                            if ($free_prodcutpricings->retail_price > 0) {
                                                                $free_product_price = $free_prodcutpricings->retail_price;
                                                            } else {
                                                                $free_product_price = $free_prodcutpricings->price;
                                                            }
                                                            $freeproduct_id = $freeproducts->id;
                                                            $pro_name = $freeproducts->name;
                                                            $free_product = array(
                                                                'promoid' => $promoid,
                                                                'promocode' => $promo_code,
                                                                'promodiscount' => $promodiscount,
                                                                'vendorid' => $vendor_id,
                                                                'product_id' => $product_id,
                                                                'shipping_discount' => $shipping_discount,
                                                                'product_discount' => $product_discount,
                                                                'tax_dicount' => '',
                                                                'subtotal_discount' => '',
                                                                'freeproduc_name' => $pro_name,
                                                                'free_productid' => $freeproduct_id,
                                                                'free_price' => $free_product_price,
                                                                'promolocation' => $location_id,
                                                                'use_with_promos' => $use_with_promos,
                                                                'product_tax' => $product_tax,
                                                                'final_price_dicount' => $product_discount,
                                                                'priority' => $priority,
                                                                'loop' => '1'
                                                            );
                                                            $_SESSION['session_promos'][$pid] = $free_product;
                                                        } else {
                                                            $product_promo = array(
                                                                'promoid' => $promoid,
                                                                'promocode' => $promo_code,
                                                                'promodiscount' => $promodiscount,
                                                                'vendorid' => $vendor_id,
                                                                'product_id' => $product_id,
                                                                'shipping_discount' => $shipping_discount,
                                                                'product_discount' => $product_discount,
                                                                'tax_dicount' => '',
                                                                'subtotal_discount' => '',
                                                                'freeproduc_name' => '',
                                                                'free_productid' => '',
                                                                'free_price' => '',
                                                                'promolocation' => $location_id,
                                                                'use_with_promos' => $use_with_promos,
                                                                'product_tax' => $product_tax,
                                                                'final_price_dicount' => $product_discount,
                                                                'priority' => $priority,
                                                                'loop' => '2'
                                                            );
                                                            $_SESSION['session_promos'][$pid] = $product_promo;
                                                        }
                                                    }
                                                } else {
                                                    $data['error'] = "Invalid For This location or Vendor";
                                                }
                                            } else {
                                                $data['error'] = "You are not Eligble";
                                            }
                                        } else {
                                            $data['error'] = "Promo Codes expired";
                                        }
                                    } else {
                                        $data['error'] = "Invalid Promo for this individual Products";
                                    }
                                } else {
                                    //Vendor Promo codes starts to apply here....
                                    if ($item['ven_id'] == $vendor_id && $item['location_id'] == $location_id) {
                                        if ($today_date <= $end_date && $today_date >= $start_date) {
                                            if ($data['promo']->product_id == "" || $data['promo']->product_id == null) {
                                                if ((($data['promo']->threshold_type == '2') && ($user_qty == $data['promo']->threshold_count)) || (($data['promo']->threshold_type == '1') && ($user_qty >= $data['promo']->threshold_count))) {
                                                    $data['valid_promos'] = $this->Promo_codes_model->get_by(array('id' => $data['promo']->id));
                                                    if ($item['ven_id'] == $data['promo']->vendor_id && $item['location_id'] == $location_id) {
                                                        if ($data['promo']->discount_type == '%') {
                                                            if ($data['promo']->discount_on == 'Final Price') {
                                                                if ($session_product_discount > 0) {
                                                                    $new_subtotals = $subtotal - $session_product_discount;
                                                                    $subtotal_discount = $new_subtotals * (($data['promo']->discount) / 100);
                                                                    $promodiscount = $subtotal_discount;
                                                                    $product_discount = $subtotal_discount;
                                                                    $new_subtotal = $new_subtotals - $promodiscount;
                                                                } else {
                                                                    $subtotal_discount = $subtotal * (($data['promo']->discount) / 100);
                                                                    $promodiscount = $subtotal_discount;
                                                                    $product_discount = $subtotal_discount;
                                                                    $new_subtotal = $subtotal - $promodiscount;
                                                                }
                                                                $product_tax = round($new_subtotal * $tax_data->EstimatedCombinedRate, 2);
                                                                $priority = '2';
                                                                $data['error'] = "";
                                                            } else if ($data['promo']->discount_on == 'Shipping') {
                                                                if (isset($_SESSION['session_shipping'])) {
                                                                    foreach ($_SESSION['session_shipping'] as $key) {
                                                                        if ($key['shipvendor'] == $item['ven_id']) {
                                                                            if ($key['shippingprice'] != null) {
                                                                                if ($session_shipping_dicount > 0) {
                                                                                    $new_shipping = $key['shippingprice'] - $session_shipping_dicount;
                                                                                    $shipping_discount = $new_shipping * (($data['promo']->discount) / 100);
                                                                                    $promodiscount = $shipping_discount;
                                                                                } else {
                                                                                    $shipping_discount = $key['shippingprice'] * (($data['promo']->discount) / 100);
                                                                                    $promodiscount = $shipping_discount;
                                                                                }
                                                                                $data['error'] = "";
                                                                                $priority = '4';
                                                                            } else {
                                                                                $data['error'] = "Please Select Your Shipping";
                                                                            }
                                                                        }
                                                                    }
                                                                } else {
                                                                    $data['error'] = "Please Select Your Shipping";
                                                                }
                                                            } else if ($data['promo']->discount_on == 'Tax') {
                                                                if ($cart_tax > 0) {
                                                                    $tax_total = $cart_tax;
                                                                    $tax_discount = $tax_total * (($data['promo']->discount) / 100);
                                                                    $promodiscount = $tax_discount;
                                                                } else {
                                                                    $tax_total = $subtotal * $tax_data->EstimatedCombinedRate;
                                                                    $tax_discount = $tax_total * (($data['promo']->discount) / 100);
                                                                    $promodiscount = $tax_discount;
                                                                }
                                                                $data['error'] = "";
                                                                $priority = '3';
                                                            }
                                                        } else if ($data['promo']->discount_type == '$') {
                                                            if ($data['promo']->discount_on == 'Final Price') {
                                                                if ($session_product_discount > 0) {
                                                                    $new_subtotal = $subtotal - $session_product_discount;
                                                                    $subtotal_discount = $new_subtotal - ($data['promo']->discount);
                                                                    $promodiscount = $data['promo']->discount;
                                                                    $product_discount = $data['promo']->discount;
                                                                } else {
                                                                    $subtotal_discount = $subtotal - ($data['promo']->discount);
                                                                    $promodiscount = $data['promo']->discount;
                                                                    $product_discount = $data['promo']->discount;
                                                                    $new_subtotal = $subtotal - $product_discount;
                                                                }
                                                                $product_tax = round($new_subtotal * $tax_data->EstimatedCombinedRate, 2);
                                                                $data['error'] = "";
                                                                $priority = '2';
                                                            } else if ($data['promo']->discount_on == 'Shipping') {
                                                                if (isset($_SESSION['session_shipping'])) {
                                                                    foreach ($_SESSION['session_shipping'] as $key) {
                                                                        if ($key['shipvendor'] == $item['ven_id']) {
                                                                            if ($key['shippingprice'] != null) {
                                                                                if ($session_shipping_dicount > 0) {
                                                                                    $new_shipping = $key['shippingprice'] - $session_shipping_dicount;
                                                                                    $shipping_discount = $new_shipping - ($data['promo']->discount);
                                                                                    $promodiscount = $shipping_discount;
                                                                                } else {
                                                                                    $shipping_discount = $key['shippingprice'] - ($data['promo']->discount);
                                                                                    $promodiscount = $shipping_discount;
                                                                                }
                                                                                $data['error'] = "";
                                                                                $priority = '4';
                                                                            } else {
                                                                                $data['error'] = "Please Select Your shipping";
                                                                            }
                                                                        }
                                                                    }
                                                                } else {
                                                                    $data['error'] = "Please Select Your shipping";
                                                                }
                                                            } else if ($data['promo']->discount_on == 'Tax') {
                                                                if ($cart_tax > 0) {
                                                                    $tax_total = $cart_tax;
                                                                    $tax_discount = $tax_total * (($data['promo']->discount) / 100);
                                                                    $promodiscount = $tax_discount;
                                                                } else {
                                                                    $tax_total = $subtotal * $tax_data->EstimatedCombinedRate;
                                                                    $tax_discount = $tax_total - ($data['promo']->discount);
                                                                    $promodiscount = $tax_discount;
                                                                }
                                                                $data['error'] = "";
                                                                $priority = '3';
                                                            }
                                                        }
                                                        if ($data['error'] == "") {
                                                            $promocode = $promo_code;
                                                            $promoid = $data['promo']->id;
                                                            $product_id = $data['promo']->product_id;
                                                            $use_with_promos = $data['promo']->use_with_promos;
                                                            $pid = random_string('alnum', 16);
                                                            if ($data['promo']->free_product_id != null || $data['promo']->free_product_id != "") {
                                                                $freeproducts = $this->Products_model->get_by(array('id' => $data['promo']->free_product_id));
                                                                $free_prodcutpricings = $product_pricing = $this->Product_pricing_model->get_by(array('product_id' => $data['promo']->free_product_id, 'vendor_id' => $vendor_id));
                                                                if ($free_prodcutpricings->retail_price > 0) {
                                                                    $free_product_price = $free_prodcutpricings->retail_price;
                                                                } else {
                                                                    $free_product_price = $free_prodcutpricings->price;
                                                                }
                                                                $freeproduct_id = $freeproducts->id;
                                                                $pro_name = $freeproducts->name;
                                                                $free_product = array(
                                                                    'promoid' => $promoid,
                                                                    'promocode' => $promo_code,
                                                                    'promodiscount' => $promodiscount,
                                                                    'vendorid' => $vendor_id,
                                                                    'product_id' => $product_id,
                                                                    'shipping_discount' => $shipping_discount,
                                                                    'product_discount' => '',
                                                                    'tax_dicount' => $tax_discount,
                                                                    'subtotal_discount' => $subtotal_discount,
                                                                    'freeproduc_name' => $pro_name,
                                                                    'free_productid' => $freeproduct_id,
                                                                    'free_price' => $free_product_price,
                                                                    'promolocation' => $location_id,
                                                                    'use_with_promos' => $use_with_promos,
                                                                    'product_tax' => $product_tax,
                                                                    'final_price_dicount' => $product_discount,
                                                                    'priority' => $priority,
                                                                    'loop' => ''
                                                                );
                                                                $promoFlag = FALSE;
                                                                if (isset($_SESSION['session_promos'])) {
                                                                    foreach ($_SESSION['session_promos'] as $row) {
                                                                        if ($row['promocode'] != null || $row['promocode'] != "") {
                                                                            if ($row['promocode'] == $promocode && $row['free_productid'] == $freeproduct_id) {

                                                                                $promoFlag = TRUE;
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                                if (!$promoFlag) {
                                                                    $_SESSION['session_promos'][$pid] = $free_product;
                                                                }
                                                            } else {
                                                                $elseFlag = FALSE;
                                                                $product_promo = array(
                                                                    'promoid' => $promoid,
                                                                    'promocode' => $promo_code,
                                                                    'promodiscount' => $promodiscount,
                                                                    'vendorid' => $vendor_id,
                                                                    'product_id' => $product_id,
                                                                    'shipping_discount' => $shipping_discount,
                                                                    'product_discount' => '',
                                                                    'tax_dicount' => $tax_discount,
                                                                    'subtotal_discount' => $subtotal_discount,
                                                                    'freeproduc_name' => '',
                                                                    'free_productid' => '',
                                                                    'free_price' => '',
                                                                    'promolocation' => $location_id,
                                                                    'use_with_promos' => $use_with_promos,
                                                                    'product_tax' => $product_tax,
                                                                    'final_price_dicount' => $product_discount,
                                                                    'priority' => $priority,
                                                                    'loop' => ''
                                                                );
                                                                $elseFlag = FALSE;
                                                                if (isset($_SESSION['session_promos'])) {
                                                                    foreach ($_SESSION['session_promos'] as $row) {
                                                                        if ($row['promocode'] != null || $row['promocode'] != "") {
                                                                            if ($row['promocode'] == $promocode) {
                                                                                $elseFlag = TRUE;
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                                if (!$elseFlag) {
                                                                    $_SESSION['session_promos'][$pid] = $product_promo;
                                                                }
                                                            }
                                                        }
                                                    } else {
                                                        $data['error'] = "Invalid For This location or Vendor";
                                                    }
                                                } else {
                                                    $data['error'] = "You are not eligble";
                                                }
                                            } else {

                                                $data['error'] = "Invalid promo for this location";
                                            }
                                        } else {
                                            $data['error'] = "Promo code expired";
                                        }
                                    } else {
                                        $data['error'] = "Invalid Promo";
                                    }
                                }
                            } //foreah end
                        } else {
                            $data['error'] = "Invalid";
                        }
                    }
                }
            }
        }
    }

    public function getPromoDetails() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $promo_code = $this->input->post('promocode');
            $location_id = $this->input->post('location_id');
            $tax_location = $this->Organization_location_model->get_by(array('id' => $location_id));
            $tax_data = $this->Product_tax_model->get_by(array('ZipCode' => $tax_location->zip));
            $bFound = false;
            $bApplied = false;
            if (isset($_SESSION['session_promos'])) {
                foreach ($_SESSION['session_promos'] as $item) {
                    if ($item['promocode'] != null || $item['promocode'] != "") {
                        if ($item['promocode'] == $promo_code && $item['promolocation'] == $location_id) {
                            $data['error'] = "Already Applied";
                            $bFound = TRUE;
                        } else {
                            $data['promo'] = $this->Promo_codes_model->get_by(array('code' => $promo_code, 'active' => '1'));
                            if ($data['promo'] != null) {
                                if ($data['promo']->vendor_id == $item['vendorid'] && $item['promolocation'] == $location_id) {
                                    if ($item['use_with_promos'] == 0 || $data['promo']->use_with_promos == '0') {
                                        $data['error'] = "This promo code cannot be used with another promo code";
                                        $bFound = TRUE;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if (!$bFound) {
                $data['promo'] = $this->Promo_codes_model->get_by(array('code' => $promo_code, 'active' => '1'));
                if ($data['promo'] != null) {
                    $data['cart'] = $this->cart->contents();
                    $vendor_id = $data['promo']->vendor_id;
                    $start_date = date("Y-m-d");

                    $end_date = $data['promo']->end_date;
                    if (strtotime($end_date) <= 0) {
                        $end_date = date("Y-m-d");
                    }
                    if (strtotime($data['promo']->start_date) > 0) {
                        $start_date = date("Y-m-d", strtotime($data['promo']->start_date));
                    }
                    if (strtotime($data['promo']->end_date) >= strtotime(date("Y-m-d"))) {
                        $end_date = date("Y-m-d", strtotime($data['promo']->end_date));
                    }
                    $today_date = date('Y-m-d');
                    $user_qty = 0;
                    $subtotal_discount = 0.00;
                    $shipping_discount = 0.00;
                    $tax_discount = 0.00;
                    $promodiscount = 0.00;
                    $product_discount = 0.00;
                    $subtotal = 0.00;
                    $product_totals = 0;
                    $product_qty = 0;
                    $product_tax = 0;
                    $cart_tax = 0;
                    $use_with_promos = 1;
                    $data['error'] = "";
                    $session_product_discount = 0;
                    $session_final_price_dicount = 0;
                    $session_shipping_dicount = 0;
                    foreach ($data['cart'] as $item) {
                        if ($item['ven_id'] == $data['promo']->vendor_id && $item['location_id'] == $location_id) {
                            $subtotal += $item['subtotal'];
                            $user_qty += $item['qty'];
                        }
                        if ($data['promo']->product_id != null) {
                            if ($item['pro_id'] == $data['promo']->product_id && $item['ven_id'] == $data['promo']->vendor_id && $item['location_id'] == $location_id) {
                                $product_totals += $item['subtotal'];
                            }
                        }
                        //check tax value in seesion
                        if (isset($_SESSION['session_promos'])) {
                            foreach ($_SESSION['session_promos'] as $promo_items) {
                                if ($promo_items['promolocation'] == $location_id && $promo_items['vendorid'] == $data['promo']->vendor_id) {
                                    $cart_tax = $promo_items['product_tax'];
                                    $session_product_discount = $promo_items['product_discount'];
                                    $session_final_price_dicount = $promo_items['final_price_dicount'];
                                    $session_shipping_dicount = $promo_items['shipping_discount'];
                                }
                            }
                        }
                    }
                    foreach ($data['cart'] as $item) {
                        if ($data['promo']->product_id != null) {
                            //Product Promo Codes starts to apply here..
                            if ($item['ven_id'] == $vendor_id && $item['location_id'] == $location_id) {
                                $product_qty = $item['qty'];
                                if ($today_date <= $end_date && $today_date >= $start_date) {
                                    if ((($data['promo']->threshold_type == '2') && ($product_qty == $data['promo']->threshold_count)) || (($data['promo']->threshold_type == '1') && ($product_qty >= $data['promo']->threshold_count))) {
                                        if ($item['pro_id'] == $data['promo']->product_id && $item['ven_id'] == $data['promo']->vendor_id && $item['location_id'] == $location_id) {
                                            $product_total = $item['subtotal'];
                                            if ($data['promo']->discount_type == '%') {
                                                if ($data['promo']->discount_on == 'Final Price') {
                                                    if ($session_product_discount > 0) {
                                                        $new_total = $product_total - $session_product_discount;
                                                        $product_discount = $new_total * (($data['promo']->discount) / 100);
                                                        $promodiscount = $product_discount;
                                                    } else {
                                                        $product_discount = $product_total * (($data['promo']->discount) / 100);
                                                        $promodiscount = $product_discount;
                                                    }
                                                    $cart_product_subtotal = $subtotal - $promodiscount;
                                                    $product_tax = round($cart_product_subtotal * $tax_data->EstimatedCombinedRate, 2);
                                                    $data['error'] = "";
                                                    $priority = '1';
                                                    $bApplied = true;
                                                } else if ($data['promo']->discount_on == 'Shipping') {
                                                    if (isset($_SESSION['session_shipping'])) {
                                                        foreach ($_SESSION['session_shipping'] as $key) {
                                                            if ($key['shipvendor'] == $item['ven_id']) {
                                                                if ($key['shippingprice'] != null) {
                                                                    if ($session_shipping_dicount > 0) {
                                                                        $new_shipping = $key['shippingprice'] - $session_shipping_dicount;
                                                                        $shipping_discount = $new_shipping * (($data['promo']->discount) / 100);
                                                                        $promodiscount = $shipping_discount;
                                                                    } else {
                                                                        $shipping_discount = $key['shippingprice'] * (($data['promo']->discount) / 100);
                                                                        $promodiscount = $shipping_discount;
                                                                    }

                                                                    $data['error'] = "";
                                                                    $priority = '4';
                                                                    $bApplied = true;
                                                                } else {
                                                                    $data['error'] = "Please Select Your shipping";
                                                                }
                                                            }
                                                        }
                                                    } else {
                                                        $data['error'] = "Please Select Your shipping";
                                                    }
                                                }
                                            } else if ($data['promo']->discount_type == '$') {
                                                if ($data['promo']->discount_on == 'Final Price') {
                                                    if ($session_product_discount > 0) {
                                                        $new_total = $product_total - $session_product_discount;
                                                        $subtotal_discount = $new_total - ($data['promo']->discount);
                                                        $promodiscount = $data['promo']->discount;
                                                        $product_discount = $data['promo']->discount;
                                                    } else {
                                                        $subtotal_discount = $product_total - ($data['promo']->discount);
                                                        $promodiscount = $data['promo']->discount;
                                                        $product_discount = $data['promo']->discount;
                                                    }
                                                    $cart_product_subtotal = $subtotal - $product_discount;
                                                    $product_tax = round($cart_product_subtotal * $tax_data->EstimatedCombinedRate, 2);
                                                    $priority = '1';
                                                    $data['error'] = "";
                                                    $bApplied = true;
                                                } else if ($data['promo']->discount_on == 'Shipping') {
                                                    if (isset($_SESSION['session_shipping'])) {
                                                        foreach ($_SESSION['session_shipping'] as $key) {
                                                            if ($key['shipvendor'] == $item['ven_id']) {
                                                                if ($key['shippingprice'] != null) {
                                                                    if ($session_shipping_dicount > 0) {
                                                                        $new_shipping = $key['shippingprice'] - $session_shipping_dicount;
                                                                        $shipping_discount = $new_shipping - ($data['promo']->discount);
                                                                        $promodiscount = $shipping_discount;
                                                                    } else {
                                                                        $shipping_discount = $key['shippingprice'] - ($data['promo']->discount);
                                                                        $promodiscount = $shipping_discount;
                                                                    }
                                                                    $data['error'] = "";
                                                                    $priority = '4';
                                                                    $bApplied = true;
                                                                } else {
                                                                    $data['error'] = "Please Select Your shipping";
                                                                }
                                                            }
                                                        }
                                                    } else {
                                                        $data['error'] = "Please Select Your shipping";
                                                    }
                                                }
                                            }
                                            if ($bApplied == true) {
                                                $promocode = $promo_code;
                                                $promoid = $data['promo']->id;
                                                $product_id = $data['promo']->product_id;
                                                $use_with_promos = $data['promo']->use_with_promos;
                                                $vendorid = $vendor_id;
                                                $pid = random_string('alnum', 16);
                                                if ($data['promo']->free_product_id != null || $data['promo']->free_product_id != "") {
                                                    $freeproducts = $this->Products_model->get_by(array('id' => $data['promo']->free_product_id));
                                                    $free_prodcutpricings = $product_pricing = $this->Product_pricing_model->get_by(array('product_id' => $data['promo']->free_product_id, 'vendor_id' => $vendor_id));
                                                    if ($free_prodcutpricings->retail_price > 0) {
                                                        $free_product_price = $free_prodcutpricings->retail_price;
                                                    } else {
                                                        $free_product_price = $free_prodcutpricings->price;
                                                    }
                                                    $freeproduct_id = $freeproducts->id;
                                                    $pro_name = $freeproducts->name;
                                                    $free_product = array(
                                                        'promoid' => $promoid,
                                                        'promocode' => $promo_code,
                                                        'promodiscount' => $promodiscount,
                                                        'vendorid' => $vendor_id,
                                                        'product_id' => $product_id,
                                                        'shipping_discount' => $shipping_discount,
                                                        'product_discount' => $product_discount,
                                                        'tax_dicount' => '',
                                                        'subtotal_discount' => '',
                                                        'freeproduc_name' => $pro_name,
                                                        'free_productid' => $freeproduct_id,
                                                        'free_price' => $free_product_price,
                                                        'promolocation' => $location_id,
                                                        'use_with_promos' => $use_with_promos,
                                                        'product_tax' => $product_tax,
                                                        'final_price_dicount' => $product_discount,
                                                        'priority' => $priority,
                                                        'loop' => '5'
                                                    );
                                                    $_SESSION['session_promos'][$pid] = $free_product;
                                                    $bApplied = true;
                                                } else {
                                                    $product_promo = array(
                                                        'promoid' => $promoid,
                                                        'promocode' => $promo_code,
                                                        'promodiscount' => $promodiscount,
                                                        'vendorid' => $vendor_id,
                                                        'product_id' => $product_id,
                                                        'shipping_discount' => $shipping_discount,
                                                        'product_discount' => $product_discount,
                                                        'tax_dicount' => '',
                                                        'subtotal_discount' => '',
                                                        'freeproduc_name' => '',
                                                        'free_productid' => '',
                                                        'free_price' => '',
                                                        'promolocation' => $location_id,
                                                        'use_with_promos' => $use_with_promos,
                                                        'product_tax' => $product_tax,
                                                        'final_price_dicount' => $product_discount,
                                                        'priority' => $priority,
                                                        'loop' => '6'
                                                    );
                                                    $_SESSION['session_promos'][$pid] = $product_promo;
                                                    $bApplied = true;
                                                }
                                            }
                                        } else {
                                            $data['error'] = "Invalid For This location or Vendor";
                                        }
                                    } else {
                                        $data['error'] = "You are not eligble";
                                    }
                                } else {
                                    $data['error'] = "Promo code expired";
                                }
                            } else {
                                $data['error'] = "Invalid promo";
                            }
                        } else {
                            //Vendor Promo Codes starts to apply here..
                            if ($item['ven_id'] == $vendor_id && $item['location_id'] == $location_id) {
                                if ($today_date <= $end_date && $today_date >= $start_date) {
                                    if ($data['promo']->product_id == "" || $data['promo']->product_id == null) {
                                        if ((($data['promo']->threshold_type == '2') && ($user_qty == $data['promo']->threshold_count)) || (($data['promo']->threshold_type == '1') && ($user_qty >= $data['promo']->threshold_count))) {
                                            $data['valid_promos'] = $this->Promo_codes_model->get_by(array('id' => $data['promo']->id));
                                            if ($item['ven_id'] == $data['promo']->vendor_id && $item['location_id'] == $location_id) {
                                                if ($data['promo']->discount_type == '%') {
                                                    if ($data['promo']->discount_on == 'Final Price') {
                                                        if ($session_product_discount > 0) {
                                                            $new_subtotals = $subtotal - $session_product_discount;
                                                            $subtotal_discount = $new_subtotals * (($data['promo']->discount) / 100);
                                                            $promodiscount = $subtotal_discount;
                                                            $product_discount = $subtotal_discount;
                                                            $new_subtotal = $new_subtotals - $subtotal_discount;
                                                        } else {
                                                            $subtotal_discount = $subtotal * (($data['promo']->discount) / 100);
                                                            $promodiscount = $subtotal_discount;
                                                            $product_discount = $subtotal_discount;
                                                            $new_subtotal = $subtotal - $subtotal_discount;
                                                        }
                                                        $cart_product_subtotal = $subtotal - $promodiscount;
                                                        $product_tax = round($new_subtotal * $tax_data->EstimatedCombinedRate, 2);
                                                        $bApplied = true;
                                                        $data['error'] = "";
                                                        $priority = '2';
                                                    } else if ($data['promo']->discount_on == 'Shipping') {
                                                        if (isset($_SESSION['session_shipping'])) {
                                                            foreach ($_SESSION['session_shipping'] as $key) {
                                                                if ($key['shipvendor'] == $item['ven_id']) {
                                                                    if ($key['shippingprice'] != null) {
                                                                        if ($session_shipping_dicount > 0) {
                                                                            $new_shipping = $key['shippingprice'] - $session_shipping_dicount;
                                                                            $shipping_discount = $new_shipping * (($data['promo']->discount) / 100);
                                                                            $promodiscount = $shipping_discount;
                                                                        } else {
                                                                            $shipping_discount = $key['shippingprice'] * (($data['promo']->discount) / 100);
                                                                            $promodiscount = $shipping_discount;
                                                                        }
                                                                        $data['error'] = "";
                                                                        $priority = '4';
                                                                        $bApplied = true;
                                                                    } else {
                                                                        $data['error'] = "Please Select Your Shipping";
                                                                    }
                                                                }
                                                            }
                                                        } else {
                                                            $data['error'] = "Please Select Your Shipping";
                                                        }
                                                    } else if ($data['promo']->discount_on == 'Tax') {
                                                        if ($cart_tax > 0) {
                                                            $tax_total = $cart_tax;
                                                            $tax_discount = $tax_total * (($data['promo']->discount) / 100);
                                                            $promodiscount = $tax_discount;
                                                        } else {
                                                            $tax_total = $subtotal * $tax_data->EstimatedCombinedRate;
                                                            $tax_discount = $tax_total * (($data['promo']->discount) / 100);
                                                            $promodiscount = $tax_discount;
                                                        }
                                                        $bApplied = true;
                                                        $data['error'] = "";
                                                        $priority = '3';
                                                    }
                                                } else if ($data['promo']->discount_type == '$') {
                                                    if ($data['promo']->discount_on == 'Final Price') {
                                                        if ($session_product_discount > 0) {
                                                            $new_subtotal = $subtotal - $session_product_discount;
                                                            $subtotal_discount = $new_subtotal - ($data['promo']->discount);
                                                            $promodiscount = $data['promo']->discount;
                                                            $product_discount = $data['promo']->discount;
                                                        } else {
                                                            $subtotal_discount = $subtotal - ($data['promo']->discount);
                                                            $promodiscount = $data['promo']->discount;
                                                            $product_discount = $data['promo']->discount;
                                                            $new_subtotal = $subtotal - $product_discount;
                                                        }
                                                        $cart_product_subtotal = $subtotal - $product_discount;
                                                        $product_tax = round($new_subtotal * $tax_data->EstimatedCombinedRate, 2);
                                                        $data['error'] = "";
                                                        $priority = '2';
                                                        $bApplied = true;
                                                    } else if ($data['promo']->discount_on == 'Shipping') {
                                                        if (isset($_SESSION['session_shipping'])) {
                                                            foreach ($_SESSION['session_shipping'] as $key) {
                                                                if ($key['shipvendor'] == $item['ven_id']) {
                                                                    if ($key['shippingprice'] != null) {
                                                                        if ($session_shipping_dicount > 0) {
                                                                            $new_shipping = $key['shippingprice'] - $session_shipping_dicount;
                                                                            $shipping_discount = $new_shipping - ($data['promo']->discount);
                                                                            $promodiscount = $shipping_discount;
                                                                        } else {
                                                                            $shipping_discount = $key['shippingprice'] - ($data['promo']->discount);
                                                                            $promodiscount = $shipping_discount;
                                                                        }

                                                                        $data['error'] = "";
                                                                        $priority = '4';
                                                                        $bApplied = true;
                                                                    } else {
                                                                        $data['error'] = "Please Select Your shipping";
                                                                    }
                                                                }
                                                            }
                                                        } else {
                                                            $data['error'] = "Please Select Your shipping";
                                                        }
                                                    } else if ($data['promo']->discount_on == 'Tax') {
                                                        if ($cart_tax > 0) {
                                                            $tax_total = $cart_tax;
                                                            $tax_discount = $tax_total * (($data['promo']->discount) / 100);
                                                            $promodiscount = $tax_discount;
                                                        } else {
                                                            $tax_total = $subtotal * $tax_data->EstimatedCombinedRate;
                                                            $tax_discount = $tax_total - ($data['promo']->discount);
                                                            $promodiscount = $tax_discount;
                                                        }
                                                        $data['error'] = "";
                                                        $priority = '3';
                                                        $bApplied = true;
                                                    }
                                                }

                                                if ($data['error'] == "") {
                                                    $promocode = $promo_code;
                                                    $promoid = $data['promo']->id;
                                                    $product_id = $data['promo']->product_id;
                                                    $use_with_promos = $data['promo']->use_with_promos;
                                                    $vendorid = $vendor_id;
                                                    $pid = random_string('alnum', 16);
                                                    if ($data['promo']->free_product_id != null || $data['promo']->free_product_id != "") {
                                                        $freeproducts = $this->Products_model->get_by(array('id' => $data['promo']->free_product_id));
                                                        $free_prodcutpricings = $product_pricing = $this->Product_pricing_model->get_by(array('product_id' => $data['promo']->free_product_id, 'vendor_id' => $vendor_id));
                                                        if ($free_prodcutpricings->retail_price > 0) {
                                                            $free_product_price = $free_prodcutpricings->retail_price;
                                                        } else {
                                                            $free_product_price = $free_prodcutpricings->price;
                                                        }
                                                        $freeproduct_id = $freeproducts->id;
                                                        $pro_name = $freeproducts->name;
                                                        $free_product = array(
                                                            'promoid' => $promoid,
                                                            'promocode' => $promo_code,
                                                            'promodiscount' => $promodiscount,
                                                            'vendorid' => $vendor_id,
                                                            'product_id' => $product_id,
                                                            'shipping_discount' => $shipping_discount,
                                                            'product_discount' => $product_discount,
                                                            'tax_dicount' => $tax_discount,
                                                            'subtotal_discount' => $subtotal_discount,
                                                            'freeproduc_name' => $pro_name,
                                                            'free_productid' => $freeproduct_id,
                                                            'free_price' => $free_product_price,
                                                            'promolocation' => $location_id,
                                                            'use_with_promos' => $use_with_promos,
                                                            'product_tax' => $product_tax,
                                                            'final_price_dicount' => $product_discount,
                                                            'priority' => $priority,
                                                            'loop' => '7'
                                                        );
                                                        $bApplied = true;
                                                        $promoFlag = FALSE;
                                                        if (isset($_SESSION['session_promos'])) {
                                                            foreach ($_SESSION['session_promos'] as $row) {
                                                                if ($row['promocode'] != null || $row['promocode'] != "") {
                                                                    if ($row['promocode'] == $promocode && $row['free_productid'] == $freeproduct_id) {

                                                                        $promoFlag = TRUE;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        if (!$promoFlag) {
                                                            $_SESSION['session_promos'][$pid] = $free_product;
                                                        }
                                                    } else {
                                                        $product_promo = array(
                                                            'promoid' => $promoid,
                                                            'promocode' => $promo_code,
                                                            'promodiscount' => $promodiscount,
                                                            'vendorid' => $vendor_id,
                                                            'product_id' => $product_id,
                                                            'shipping_discount' => $shipping_discount,
                                                            'product_discount' => $product_discount,
                                                            'tax_dicount' => $tax_discount,
                                                            'subtotal_discount' => $subtotal_discount,
                                                            'freeproduc_name' => '',
                                                            'free_productid' => '',
                                                            'free_price' => '',
                                                            'promolocation' => $location_id,
                                                            'use_with_promos' => $use_with_promos,
                                                            'product_tax' => $product_tax,
                                                            'final_price_dicount' => $product_discount,
                                                            'priority' => $priority,
                                                            'loop' => '8'
                                                        );
                                                        $bApplied = true;

                                                        $_SESSION['session_promos'][$pid] = $product_promo;
                                                    }
                                                }
                                            } else {
                                                $data['error'] = "Invalid For This location or Vendor";
                                            }
                                        } else {
                                            $data['error'] = "You are not eligble";
                                        }
                                    } else {

                                        $data['error'] = "Invalid promo for this location";
                                    }
                                } else {
                                    $data['error'] = "Promo code expired";
                                }
                            } else {
                                $data['error'] = "Invalid promo";
                            }
                        }
                    } //foreah end
                } else {
                    $data['error'] = "Invalid";
                }
            }
            if ($bFound == false) {
                if (isset($_SESSION['session_promos'])) {
                    foreach ($_SESSION['session_promos'] as $item) {
                        if ($item['promocode'] != null || $item['promocode'] != "") {
                            if ($item['promocode'] == $promo_code && $item['promolocation'] == $location_id) {
                                $data['error'] = "";
                            }
                        }
                    }
                }
            }
            if ($bApplied == true) {
                $data['error'] = "";
            }
            echo json_encode($data);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function promoremove_cart() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $id = $this->input->post('promoremove_id');
            unset($_SESSION['session_promos'][$id]);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function change_cart_payment() {
        //session_destroy();
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $payment_id = $this->input->post('p_id');
            $update_query = ('UPDATE users SET default_payment_method ='. $payment_id.' WHERE id = '.$user_id);

            $this->db->query($update_query);
            $reponse = array('payment' => $payment_id);
            echo json_encode($reponse);
        }
    }

    public function cart_update() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $rowid = $this->input->post('row_id');
            $qty = $this->input->post('qty');
            $update_data = array($rowid => array(
                    'rowid' => $rowid,
                    'qty' => $qty,
                    'status' => 0,
            ));
            $this->cart->update($update_data);
            $data['cart_data'] = $this->cart->contents();
            $organization = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
            $data['carts'] = $this->User_autosave_model->saveCart($_SESSION['role_id'], $data['cart_data'], $organization->organization_id, $user_id);
            echo json_encode($data);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    //remove independent vendor orders from cart
    public function remove_cart_vendor() {
        $roles = unserialize(ROLES_USERS);
        Debugger::debug($_POST);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $data['message'] = "";
            $vendor_id = $this->input->post('vendor_id');
            $cart_location_id = $this->input->post('cart_location_id');
            $cart = $this->cart->contents();
            foreach ($cart as $item) {
                if ($item['ven_id'] == $vendor_id && $item['location_id'] == $cart_location_id) {

                    $rowid[] = $item['rowid'];
                }
            }
            for ($i = 0; $i < count($rowid); $i++) {
                $update_data = array($rowid[$i] => array(
                        'rowid' => $rowid[$i],
                        'qty' => 0
                ));
                $this->cart->update($update_data);
            }
            $cart_data = $this->cart->contents();
            if (isset($_SESSION['session_shipping'])) {
                $session_count = count($_SESSION['session_shipping']);
                $shippings = $_SESSION['session_shipping'];
                $shippingkey = array_keys($_SESSION['session_shipping']);
                for ($k = 0; $k < $session_count; $k++) {
                    if ($shippings[$shippingkey[$k]]['shipvendor'] == $vendor_id) {
                        $id = $shippingkey[$k];
                        unset($_SESSION['session_shipping'][$id]);
                    }
                }
            }
            $organization = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
            $this->User_autosave_model->saveCart($_SESSION['role_id'], $cart_data, $organization->organization_id, $user_id);
            foreach ($cart_data as $value) {
                if ($value['location_id'] == $cart_location_id) {
                    $data['message'] = "message";
                } else {
                    $data['message'] = "";
                }
            }
            echo json_encode($data);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    //remove matix vendors orders from cart
    public function remove_matix_vendor() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $data['message'] = "";
            $cart_location_id = $this->input->post('cart_location_id');
            $cart = $this->cart->contents();
            foreach ($cart as $item) {
                if ($item['location_id'] == $cart_location_id) {
                    $vendors = $this->Vendor_model->get($item['ven_id']);
                    if ($vendors->vendor_type == '1') {
                        $vendor_id[] = $vendors->id;
                        if ($vendors->id == $item['ven_id']) {
                            $rowid[] = $item['rowid'];
                        }
                    }
                }
            }
            for ($i = 0; $i < count($rowid); $i++) {
                $update_data = array($rowid[$i] => array(
                        'rowid' => $rowid[$i],
                        'qty' => 0
                ));
                $this->cart->update($update_data);
            }
            $cart_data = $this->cart->contents();
            if (isset($_SESSION['session_shipping'])) {
                $session_count = count($_SESSION['session_shipping']);
                $shippings = $_SESSION['session_shipping'];
                $shippingkey = array_keys($_SESSION['session_shipping']);
                for ($k = 0; $k < $session_count; $k++) {
                    if ($shippings[$shippingkey[$k]]['shipvendor'] == $vendor_id[$k]) {
                        $id = $shippingkey[$k];
                        unset($_SESSION['session_shipping'][$id]);
                    }
                }
            }
            $organization = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
            $this->User_autosave_model->saveCart($_SESSION['role_id'], $cart_data, $organization->organization_id, $user_id);
            foreach ($cart_data as $value) {
                if ($value['location_id'] == $cart_location_id) {
                    $data['message'] = "message";
                } else {
                    $data['message'] = "";
                }
            }
            echo json_encode($data);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    // independent vendors orders save later from cart
    public function vendor_save_later() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $data['message'] = "";
            $vendor_id = $this->input->post('vendor_id');
            $cart_location_id = $this->input->post('cart_location_id');
            $cart = $this->cart->contents();
            $cart_data = $this->cart->contents();
            $organization = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
            $organization_id = $organization->organization_id;
            foreach ($cart as $item) {
                if ($item['location_id'] == $cart_location_id) {
                    $vendors = $this->Vendor_model->get($item['ven_id']);
                    if ($vendor_id == $item['ven_id']) {
                        $rowid = $item['rowid'];
                        $insert_data = array(
                            'location_id' => $cart_location_id,
                            'user_id' => $organization_id,
                            'product_id' => $item['pro_id'],
                            'vendor_id' => $vendor_id,
                            'quantity' => $item['qty'],
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        );
                        $data['requests'] = $this->Request_list_model->get_by(array('product_id' => $item['pro_id'], 'location_id' => $cart_location_id, 'vendor_id' => $vendor_id));
                        if ($data['requests'] != null) {
                            $update_id = $data['requests']->id;
                            $old_qty = $data['requests']->quantity;
                            $new_qty = $old_qty + $item['qty'];
                            $update_data = array(
                                'quantity' => $new_qty,
                                'updated_at' => date('Y-m-d H:i:s')
                            );
                            $this->Request_list_model->update($update_id, $update_data);
                        } else {
                            $this->Request_list_model->insert($insert_data);
                        }
                        $requests = array(
                            'organization_id' => $organization_id,
                            'user_id' => $user_id,
                            'product_id' => $item['pro_id'],
                            'location_id' => $cart_location_id,
                            'action' => 'moved item from',
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        );
                        $this->Request_list_activity_model->insert($requests); //Insert Request list activities
                        $cleardata = array(
                            'rowid' => $rowid,
                            'qty' => 0
                        );
                        $this->cart->update($cleardata);

                        if (isset($_SESSION['session_shipping'])) { //remove vendor shippings
                            $session_count = count($_SESSION['session_shipping']);
                            $shippings = $_SESSION['session_shipping'];
                            $shippingkey = array_keys($_SESSION['session_shipping']);
                            for ($k = 0; $k < $session_count; $k++) {
                                if ($shippings[$shippingkey[$k]]['shipvendor'] == $vendor_id) {
                                    $id = $shippingkey[$k];
                                    unset($_SESSION['session_shipping'][$id]);
                                }
                            }
                        }
                    }
                }
            }
            $this->User_autosave_model->saveCart($_SESSION['role_id'], $cart_data, $organization->organization_id, $user_id);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    // matix vendors orders save later from cart
    public function matix_save_later() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $data['message'] = "";
            $cart_location_id = $this->input->post('cart_location_id');
            $cart = $this->cart->contents();
            $organization = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
            $organization_id = $organization->organization_id;
            foreach ($cart as $item) {
                if ($item['location_id'] == $cart_location_id) {
                    $vendors = $this->Vendor_model->get($item['ven_id']);
                    if ($vendors->vendor_type == '1') {
                        $vendor_id = $vendors->id;
                        if ($vendors->id == $item['ven_id']) {
                            $rowid = $item['rowid'];
                            $insert_data = array(
                                'location_id' => $cart_location_id,
                                'user_id' => $organization_id,
                                'product_id' => $item['pro_id'],
                                'vendor_id' => $vendor_id,
                                'quantity' => $item['qty'],
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                            );
                            $data['requests'] = $this->Request_list_model->get_by(array('product_id' => $item['pro_id'], 'location_id' => $cart_location_id, 'vendor_id' => $vendor_id));
                            if ($data['requests'] != null) {
                                $update_id = $data['requests']->id;
                                $old_qty = $data['requests']->quantity;
                                $new_qty = $old_qty + $item['qty'];
                                $update_data = array(
                                    'quantity' => $new_qty,
                                    'updated_at' => date('Y-m-d H:i:s')
                                );
                                $this->Request_list_model->update($update_id, $update_data);
                            } else {
                                $this->Request_list_model->insert($insert_data);
                            }
                            $requests = array(
                                'organization_id' => $organization_id,
                                'user_id' => $user_id,
                                'product_id' => $item['pro_id'],
                                'location_id' => $cart_location_id,
                                'action' => 'moved item from',
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                            );
                            $this->Request_list_activity_model->insert($requests); //Insert Request list activities
                            $cleardata = array(
                                'rowid' => $rowid,
                                'qty' => 0
                            );
                            $this->cart->update($cleardata);

                            if (isset($_SESSION['session_shipping'])) { //remove vendor shippings
                                $session_count = count($_SESSION['session_shipping']);
                                $shippings = $_SESSION['session_shipping'];
                                $shippingkey = array_keys($_SESSION['session_shipping']);
                                for ($k = 0; $k < $session_count; $k++) {
                                    if ($shippings[$shippingkey[$k]]['shipvendor'] == $vendor_id) {
                                        $id = $shippingkey[$k];
                                        unset($_SESSION['session_shipping'][$id]);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $cart_data = $this->cart->contents();
            $this->User_autosave_model->saveCart($_SESSION['role_id'], $cart_data, $organization->organization_id, $user_id);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function cart_clear() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $data['message'] = "";
            $rowid = $this->input->post('row_id');
            $update_data = array(
                'rowid' => $rowid,
                'qty' => 0
            );
            $this->cart->update($update_data);
            $data['cart_data'] = $this->cart->contents();
            $organization = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
            $data['carts'] = $this->User_autosave_model->saveCart($_SESSION['role_id'], $data['cart_data'], $organization->organization_id, $user_id);

        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    private function get_payment_methods($user){


        $customer = $this->stripe->getCustomer($user->stripe_id); //live mode
       // $customer = 'asdasd32165465asdsadasd';
        $users_payments = $this->User_payment_option_model->get_many_by(['user_id' => $user->id]);
        $payment_methods = [];
        foreach ($users_payments as $users_payment) {
            //get payment methods
           if ($customer && isset($customer->sources)) {
                try {
                    $payment_method = $customer->sources->retrieve($users_payment->token);
                } catch (\Exception $e) {
                    log_message('error', "Stripe token fetch failed for token: {$users_payment->token} - " . $e->getMessage());
                    $payment_method = null;
                }
            } else {
                $payment_method = null;
            }

            $users_payment_obj = new stdClass();
            $users_payment_obj->id = $users_payment->id;
            $users_payment_obj->token = $users_payment->token;
            $users_payment_obj->user_id = $user->id;
            $users_payment_obj->payment_type = isset($payment_method->object) ? $payment_method->object : $users_payment->payment_type;
            $users_payment_obj->card_type = isset($payment_method->brand) ? $payment_method->brand : null;
            $users_payment_obj->exp_month = isset($payment_method->exp_month) ? $payment_method->exp_month : null;
            $users_payment_obj->exp_year = isset($payment_method->exp_year) ? $payment_method->exp_year : null;
            $users_payment_obj->cc_number = isset($payment_method->last4) ? $payment_method->last4 : null;
            $users_payment_obj->cc_name = isset($payment_method->name) ? $payment_method->name : $users_payment->cc_name;
            $users_payment_obj->bank_name = isset($payment_method->bank_name) ? $payment_method->bank_name : $users_payment->bank_name;
            $users_payment_obj->ba_routing_number = isset($payment_method->routing_number) ? $payment_method->routing_number : $users_payment->ba_routing_number;
            $users_payment_obj->ba_account_number = isset($payment_method->last4) ? $payment_method->last4 : $users_payment->ba_account_number;

            $users_payment_obj->created_at = $users_payment->created_at;
            $users_payment_obj->updated_at = $users_payment->update_at;
            $payment_methods []= $users_payment_obj;
        }

        return $payment_methods;
    }
}

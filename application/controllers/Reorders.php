<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reorders extends MW_Controller {

    function __construct() {

        parent::__construct();
        $this->load->model('Organization_groups_model');
        $this->load->model('User_location_model');
        $this->load->model('Organization_location_model');
        $this->load->model('Products_model');
        $this->load->model('Product_pricing_model');
        $this->load->model('Vendor_model');
        $this->load->model('User_model');
        $this->load->model('Order_model');
        $this->load->model('Order_items_model');
        $this->load->model('Shipping_options_model');
        $this->load->model('Images_model');
        $this->load->model('User_licenses_model');
        $this->load->model('User_autosave_model');
        $this->load->model('Promo_codes_model');
        $this->load->model('Product_tax_model');
        $this->load->model('Vendor_groups_model');
        $this->load->model('User_payment_option_model');
        $this->load->library('email');
        $this->load->library('stripe');
        $this->load->helper('my_email_helper');
    }

    public function get_for_reorder() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $order_id = $this->input->post('order_id');
            $data['orders'] = $this->Order_model->get_by(array('id' => $order_id));
            $data['locations'] = $this->Organization_location_model->get_by(array('id' => $data['orders']->location_id));
            $data['shippings'] = $this->Shipping_options_model->get_many_by(array('vendor_id' => $data['orders']->vendor_id));
            $data['tax_rate'] = $this->Product_tax_model->get_by(array('ZipCode' => $data['locations']->zip));
            $locations = $this->User_location_model->get_many_by(array('user_id' => $user_id));
            for ($i = 0; $i < count($locations); $i++) {
                $data['user_locations'][] = $this->Organization_location_model->get_by(array('id' => $locations[$i]->organization_location_id));
            }
            $data['tax'] = 0;
            $data['shipping'] = $this->Shipping_options_model->get_by(array('id' => $data['orders']->shipment_id));
            $user_payment = $this->User_payment_option_model->get_by(array('id' => $data['orders']->payment_id, 'user_id' => $user_id));

            $user = $this->User_model->get_by(array('id' => $data['orders']->user_id));
            $customer = $this->stripe->getCustomer($user->stripe_id);
            // Payment method
            $payment_method = $customer->sources->retrieve($user_payment->token);
            $users_payment_obj = new stdClass();
            $users_payment_obj->id = $user_payment->id;
            $users_payment_obj->token = $user_payment->token;
            $users_payment_obj->user_id = $user_id;
            $users_payment_obj->payment_type = $payment_method->object;
            $users_payment_obj->card_type = $payment_method->brand;
            $users_payment_obj->exp_month = $payment_method->exp_month;
            $users_payment_obj->exp_year = $payment_method->exp_year;
            $users_payment_obj->cc_number = $payment_method->last4;
            $users_payment_obj->cc_name = $payment_method->name;
            $users_payment_obj->bank_name = $payment_method->bank_name;
            $users_payment_obj->ba_routing_number = $payment_method->routing_number;
            $users_payment_obj->ba_account_number = $payment_method->last4;
            $users_payment_obj->created_at = $user_payment->created_at;
            $users_payment_obj->updated_at = $user_payment->update_at;
            $data['payment'] = $users_payment_obj;
            unset($users_payment_obj);


            $user_payments = $this->User_payment_option_model->get_many_by(array('user_id' => $user_id));
            $user = $this->User_model->get_by(array('id' => $user_id));
            $customer = $this->stripe->getCustomer($user->stripe_id);
            foreach ($user_payments as $user_payment) {
                // Payment method
                $payment_method = $customer->sources->retrieve($user_payment->token);
                $users_payment_obj = new stdClass();
                $users_payment_obj->id = $user_payment->id;
                $users_payment_obj->token = $user_payment->token;
                $users_payment_obj->user_id = $user_id;
                $users_payment_obj->payment_type = $payment_method->object;
                $users_payment_obj->card_type = $payment_method->brand;
                $users_payment_obj->exp_month = $payment_method->exp_month;
                $users_payment_obj->exp_year = $payment_method->exp_year;
                $users_payment_obj->cc_number = $payment_method->last4;
                $users_payment_obj->cc_name = $payment_method->name;
                $users_payment_obj->bank_name = $payment_method->bank_name;
                $users_payment_obj->ba_routing_number = $payment_method->routing_number;
                $users_payment_obj->ba_account_number = $payment_method->last4;
                $users_payment_obj->created_at = $user_payment->created_at;
                $users_payment_obj->updated_at = $user_payment->update_at;
                $data['payments'][] = $users_payment_obj;
                unset($users_payment_obj);
            }

            $data['order_items'] = $this->Order_items_model->get_many_by(array('order_id' => $order_id, 'price!=' => '0.00'));
            for ($i = 0; $i < count($data['order_items']); $i++) {
                $p_details = $this->Products_model->get_by(array('id' => $data['order_items'][$i]->product_id));
                $image = $this->Images_model->get_by(array('model_id' => $data['order_items'][$i]->product_id, 'model_name' => 'products', 'image_type' => 'mainimg'));
                $vendor = $this->Vendor_model->get_by(array('id' => $data['order_items'][$i]->vendor_id));
                $product_pricing = $this->Product_pricing_model->get_by(array('product_id' => $data['order_items'][$i]->product_id, 'vendor_id' => $data['order_items'][$i]->vendor_id));
                $data['order_items'][$i]->products = $p_details;
                $data['order_items'][$i]->images = $image;
                $data['order_items'][$i]->vendors = $vendor;
                $data['order_items'][$i]->pricing = $product_pricing;
                if ($data['tax_rate'] != null) {
                    $data['tax'] += ($data['tax_rate']->EstimatedCombinedRate) * $data['order_items'][$i]->quantity * $data['order_items'][$i]->price;
                }
            }
            echo json_encode($data);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function get_location() { //get changed reorder location details
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $location_id = $this->input->post('reorderlocation_id');
            $data['location'] = $this->Organization_location_model->get($location_id);
            echo json_encode($data);
        }
    }

    public function get_payments() { // get changed reorder payments detials
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $payment_id = $this->input->post('payment_id');
            $user_payment = $this->User_payment_option_model->get($payment_id);

            $user = $this->User_model->get_by(array('id' => $user_id));
            $customer = $this->stripe->getCustomer($user->stripe_id);
            // Payment method
            $payment_method = $customer->sources->retrieve($user_payment->token);
            $users_payment_obj = new stdClass();
            $users_payment_obj->id = $user_payment->id;
            $users_payment_obj->token = $user_payment->token;
            $users_payment_obj->user_id = $user_id;
            $users_payment_obj->payment_type = $payment_method->object;
            $users_payment_obj->card_type = $payment_method->brand;
            $users_payment_obj->exp_month = $payment_method->exp_month;
            $users_payment_obj->exp_year = $payment_method->exp_year;
            $users_payment_obj->cc_number = $payment_method->last4;
            $users_payment_obj->cc_name = $payment_method->name;
            $users_payment_obj->bank_name = $payment_method->bank_name;
            $users_payment_obj->ba_routing_number = $payment_method->routing_number;
            $users_payment_obj->ba_account_number = $payment_method->last4;
            $users_payment_obj->created_at = $user_payment->created_at;
            $users_payment_obj->updated_at = $user_payment->update_at;
            $data['payment'] = $users_payment_obj;
            unset($users_payment_obj);

            echo json_encode($data);
        }
    }

    public function add_reorder_location() { //add reorder users location
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $data['organ_id'] = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
            $organization_id = $data['organ_id']->organization_id;
            $nickname = $this->input->post('nickname');
            $address1 = $this->input->post('address1');
            $address2 = $this->input->post('address2');
            $state = $this->input->post('state');
            $zip = $this->input->post('zip');
            if ($organization_id != null) {
                $insert_data = array(
                    'organization_id' => $organization_id,
                    'nickname' => $nickname,
                    'address1' => $address1,
                    'address2' => $address2,
                    'zip' => $zip,
                    'state' => $state,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );

                if ($insert_data != null) {
                    $this->Organization_location_model->insert($insert_data);
                    $insert_id = $this->db->insert_id();
                    $insert_location = array(
                        'user_id' => $user_id,
                        'organization_location_id' => $insert_id,
                    );
                    $this->User_location_model->insert($insert_location);
                    $locations = $this->User_location_model->get_many_by(array('user_id' => $user_id));
                    for ($i = 0; $i < count($locations); $i++) {
                        $data['user_locations'][] = $this->Organization_location_model->get_by(array('id' => $locations[$i]->organization_location_id));
                    }
                    $data['insert_id'] = $insert_id;
                    echo json_encode($data);
                }
            }
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("Location: view-product?id=1=" . $product_id);
        }
    }

    //check user reorder prodocuts if licenced and get products delivery time...
    public function reorders_products_check() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $product_id = $this->input->post('product_id');
            $reorderlocation_id = $this->input->post('reorderlocation_id');
            $qty = $this->input->post('quantity');
            $payment = $this->input->post('payment');
            $shipping_id = $this->input->post('shipping_id');
            $product_count = count($product_id);
            $data['locations'] = $this->Organization_location_model->get_by(array('id' => $reorderlocation_id));
            $data['reorder_tax'] = $this->Product_tax_model->get_by(array('ZipCode' => $data['locations']->zip));
            $user_payment = $this->User_payment_option_model->get_by(array('id' => $payment, 'user_id' => $user_id));

            $user = $this->User_model->get_by(array('id' => $user_id));
            $customer = $this->stripe->getCustomer($user->stripe_id);
            // Payment method
            $payment_method = $customer->sources->retrieve($user_payment->token);
            $users_payment_obj = new stdClass();
            $users_payment_obj->id = $user_payment->id;
            $users_payment_obj->token = $user_payment->token;
            $users_payment_obj->user_id = $user_id;
            $users_payment_obj->payment_type = $payment_method->object;
            $users_payment_obj->card_type = $payment_method->brand;
            $users_payment_obj->exp_month = $payment_method->exp_month;
            $users_payment_obj->exp_year = $payment_method->exp_year;
            $users_payment_obj->cc_number = $payment_method->last4;
            $users_payment_obj->cc_name = $payment_method->name;
            $users_payment_obj->bank_name = $payment_method->bank_name;
            $users_payment_obj->ba_routing_number = $payment_method->routing_number;
            $users_payment_obj->ba_account_number = $payment_method->last4;
            $users_payment_obj->created_at = $user_payment->created_at;
            $users_payment_obj->updated_at = $user_payment->update_at;
            $data['payment'] = $users_payment_obj;
            unset($users_payment_obj);

            $data['shippings'] = $this->Shipping_options_model->get_by(array('id' => $shipping_id));
            switch ($data['shippings']->delivery_time) {
                case "Same Day":
                    $data['shippings']->delivery_time = date('l, M. d');
                    break;
                case "Next Business Day":
                    $data['shippings']->delivery_time = date('l, M. d', strtotime(' +1 Weekday'));
                    break;
                case "2 Business Days":
                    $data['shippings']->delivery_time = date('l, M. d', strtotime(' +2 Weekday'));
                    break;
                case "3 Business Days":
                    $data['shippings']->delivery_time = date('l, M. d', strtotime(' +3 Weekday'));
                    break;
                case "1-5 Business Days":
                    $data['shippings']->delivery_time = date('l, M. d', strtotime(' +5 Weekday'));
                    break;
                case "7-10 Business Days":
                    $data['shippings']->delivery_time = date('l, M. d', strtotime(' +10 Weekday'));
                    break;
                default:
                    $data['shippings']->delivery_time = "n.a.";
                    break;
            }
            $data['error'] = "";
            for ($i = 0; $i < $product_count; $i++) {
                if ($qty[$i] > 0) {
                    $product_licence = $this->Products_model->get_by(array('id' => $product_id[$i]));
                    $license = $product_licence->license_required;
                    if ($license == 'Yes') {
                        $organization = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
                        $organization_id = $organization->organization_id;
                        $user_license = $this->User_licenses_model->get_by(array('state' => $data['locations']->state, 'user_id' => $user_id, 'approved' => '1'));


                        if (is_null($user_license)){
                            $tier_1_2 = unserialize(ROLES_TIER1_2);
                            if (in_array($user->role_id,$tier_1_2)){
                                $org_users = $this->Organization_groups_model->get_users_by_user($user_id);

                                $user_license = $this->User_licenses_model->get_by(['state' => $data['locations']->state, 'user_id' => $org_users, 'approved' => '1']);
                            }
                        }


                        if ($user_license != null) {
                            $end_date = $user_license[0]->expire_date;
                            $today_date = date('Y-m-d');
                            if ($today_date <= $end_date) {
                                $data['error'] = "";
                            } else {
                                $data['error'] = "License expired";
                            }
                        } else {
                            $data['error'] = "No license";
                        }
                    } else {
                        $data['error'] = "";
                    }
                }
            }
            echo json_encode($data);
        }
    }

    //submit Reorders
    public function reorder() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $user = $this->User_model->get_by(['id' => $user_id]);
            $total = $this->input->post('order_total');
            $vendor_id = $this->input->post('vendor_id');
            $order_count = count($vendor_id);
            $shipment_id = $this->input->post('shipping_id');
            $shipment_price = $this->input->post('shiping_price');
            $product_id = $this->input->post('product_id');
            $location_id = $this->input->post('location');
            $price = $this->input->post('product_price');
            $qty = $this->input->post('quantity');
            $subtotal = $this->input->post('subtotalValue');
            $formtax = $this->input->post('tax');
            $description = 'Order placed from Matixdental.com';
            $role_id = $_SESSION['role_id'];
            $data['restricted_orders'] = array();
            $data['success_order'] = array();
            $payment_id = $this->input->post('paymentMethod');
            $payment_token = $this->input->post('payment_token');
            $data['user_address'] = $this->Organization_location_model->get_by(array('id' => $location_id));
            $address1 = $data['user_address']->address1;
            $address2 = $data['user_address']->address2;
            $city = $data['user_address']->city;
            $state = $data['user_address']->state;
            $zip = $data['user_address']->zip;
            $spend_budget = $data['user_address']->spend_budget;
            $nickname = $data['user_address']->nickname;
            $organization_id = $data['user_address']->organization_id;
            $data['user_zip'] = $this->Organization_location_model->get_by(array('id' => $location_id));
            $zip_code = $data['user_zip']->zip;
            $data['tax_details'] = $this->Product_tax_model->get_by(array('ZipCode' => $zip_code));
            $start_tax = 0;
            $data['organ_users'] = $this->Organization_groups_model->get_many_by(array('organization_id' => $organization_id));
            $data['order_total'] = $this->Order_model->get_many_by(array('location_id' => $location_id));
            $order_total = 0;
            for ($i = 0; $i < count($data['order_total']); $i++) {
                $old_total = $data['order_total'][$i]->total;
                $order_total = $old_total + $order_total;
            }
            for ($i = 0; $i < $order_count; $i++) {
                $product_count = count($product_id);
                for ($j = 0; $j < $product_count; $j++) {
                    $sub_total = ($price[$j] * $qty[$j]);
                    $products = $this->Products_model->get_by(array('id' => $product_id[$j]));
                    $licence = $products->license_required;
                    if ($licence != 'Yes' || $price[$j] == '0') {

                    } else {
                        $restricted_orders[] = $i;
                    }
                }
            }
            $orderFlag = False;
            if ($role_id == "10") {
                for ($i = 0; $i < $order_count; $i++) {
                    $product_count = count($product_id);
                    $insert_data = array(
                        'order_status' => 'New',
                        'location_id' => $location_id,
                        'total' => $total,
                        'tax' => $formtax,
                        'shipping_price' => $shipment_price,
                        'user_id' => $user_id,
                        'vendor_id' => $vendor_id,
                        'address1' => $address1,
                        'address2' => $address2,
                        'city' => $city,
                        'state' => $state,
                        'zip' => $zip,
                        'nickname' => $nickname,
                        'shipment_id' => $shipment_id,
                        'payment_id' => $payment_id,
                        'restricted_order' => '1',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    );
                    if (in_array($i, $restricted_orders)) {
                        //THIS IS A RESTRICTED ORDER. Add items as restricted_orders and restricted_order_items .
                        $this->Order_model->insert($insert_data);
                        $restricted_id = $this->db->insert_id();
                        for ($j = 0; $j < $product_count; $j++) {
                            $sub_totals = ($price[$j] * $qty[$j]);
                            $cart_data = array(
                                'order_id' => $restricted_id,
                                'item_status' => '0',
                                'shipment_id' => $shipment_id,
                                'shipping_price' => $shipment_price,
                                'product_id' => $product_id[$j],
                                'vendor_id' => $vendor_id,
                                'price' => $price[$j],
                                'quantity' => $qty[$j],
                                'total' => $sub_totals,
                                'restricted_order' => '1',
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s')
                            );
                            $this->Order_items_model->insert($cart_data);
                            $this->session->set_flashdata('error', 'Order is pending, and will be processed if approved.');
                        }
                        $data['restricted_orders'][] = $this->Order_model->get_by(array('id' => $restricted_id));
                    } else {
                        $orderFlag = True;
                    }
                }
            } else if ($role_id == "1" || $role_id == "3" || $role_id == "4" || $role_id == "7") {
                $orderFlag = True;
            }
            if ($orderFlag) {
                $product_count = count($product_id);
                $insert_data = array(
                    'order_status' => 'New',
                    'location_id' => $location_id,
                    'total' => $total,
                    'tax' => $formtax,
                    'shipping_price' => $shipment_price,
                    'user_id' => $user_id,
                    'vendor_id' => $vendor_id,
                    'address1' => $address1,
                    'address2' => $address2,
                    'city' => $city,
                    'state' => $state,
                    'zip' => $zip,
                    'nickname' => $nickname,
                    'shipment_id' => $shipment_id,
                    'payment_id' => $payment_id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                );
                $vendor = $this->Vendor_model->get($vendor_id);
                if ($vendor->vendor_type == '0') {
                    $payment_cost = $total * 100;

                    $payment_data = array(
                        'amount' => round($payment_cost),
                        'customer' => $user->stripe_id,
                        'source' => $payment_token,
                        'description' => $description
                    );
                    $vendor = $this->Vendor_model->get($vendor_id);
                    if ($vendor->payment_id != null && $vendor->payment_id != "") {
                        $payment_data['destination'] = $vendor->payment_id;
                    }
                    $output = $this->stripe->addCharge($payment_data);

                    $this->Order_model->insert($insert_data);
                    $insert_id = $this->db->insert_id();
                    for ($j = 0; $j < $product_count; $j++) {
                        $sub_totals = ($price[$j] * $qty[$j]);
                        $tax = $sub_totals * $data['tax_details']->EstimatedCombinedRate;
                        if ($qty[$j] == '0') {

                        } else {
                            $cart_data = array(
                                'order_id' => $insert_id,
                                'item_status' => '0',
                                'shipment_id' => $shipment_id,
                                'shipping_price' => $shipment_price,
                                'product_id' => $product_id[$j],
                                'vendor_id' => $vendor_id,
                                'price' => $price[$j],
                                'quantity' => $qty[$j],
                                'picked' => $qty[$j],
                                'tax' => $tax,
                                'total' => $sub_totals,
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s')
                            );
                            $this->Order_items_model->insert($cart_data);
                        }
                    }
                    $this->session->set_flashdata('success', 'Order Success!');
                    $data['success_order'][] = $this->Order_model->get_by(array('id' => $insert_id));
                } else {
                    try {
                        $payment_cost = $total * 100;

                        $payment_data = array(
                            'amount' => round($payment_cost),
                            'customer' => $payment_token,
                            'description' => $description
                        );
                        $vendor = $this->Vendor_model->get($vendor_id);
                        if ($vendor->payment_id != null && $vendor->payment_id != "") {
                            $payment_data['destination'] = $vendor->payment_id;
                            $payment_data['application_fee'] = round($payment_cost * 0.07);
                        }
                        $output = $this->stripe->addCharge($payment_data);
                        $this->Order_model->insert($insert_data);
                        $insert_id = $this->db->insert_id();
                        for ($j = 0; $j < $product_count; $j++) {
                            $sub_totals = ($price[$j] * $qty[$j]);
                            $tax = $sub_totals * $data['tax_details']->EstimatedCombinedRate;
                            if ($qty[$j] == '0') {
                                //remove products ,users set qty as zero
                            } else {
                                $cart_data = array(
                                    'order_id' => $insert_id,
                                    'item_status' => '0',
                                    'shipment_id' => $shipment_id,
                                    'shipping_price' => $shipment_price,
                                    'product_id' => $product_id[$j],
                                    'vendor_id' => $vendor_id,
                                    'price' => $price[$j],
                                    'quantity' => $qty[$j],
                                    'picked' => $qty[$j],
                                    'tax' => $tax,
                                    'total' => $sub_totals,
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s')
                                );
                                $this->Order_items_model->insert($cart_data);
                            }
                        }
                        $this->session->set_flashdata('success', 'Order Success!');
                        $data['success_order'][] = $this->Order_model->get_by(array('id' => $insert_id));
                    } catch (Exception $e) {
                        $this->session->set_flashdata('error', 'Invalid payment credentials.');
                        header("Location:history");
                    }
                }
            }
            $user = $this->User_model->get_by(array('id' => $user_id));
            $user_name = $user->first_name;
            $email = $user->email;
            $shipping_address = $address1 . "<br/>" . $address2 . "<br/>" . $city . ", " . $state . "<br/>" . $zip;

            //ordres detials sent
            if ($insert_id != "" || $insert_id != null) {
                foreach ($data['organ_users'] as $key) {
                    //Budget Exceeded notification
                    $data['user'] = $this->User_model->get_many_by(array('id' => $key->user_id));
                    for ($i = 0; $i < count($data['user']); $i++) {
                        if ($data['user'][$i]->email_setting4 == '1' && ($data['user'][$i]->role_id == '3' || $data['user'][$i]->role_id == '7')) {
                            $mail = $data['user'][$i]->email;
                            if ($spend_budget > 0) {
                                if ($order_total > $spend_budget) {
                                    $subject = 'Budget Exceeded';
                                        $message = "<div style='text-align: center;'>"
                                                . "<hr style='width: 40px; color: #e8eaf1; background-color: #e8eaf1; border: 1px solid #e8eaf1;'>"
                                                . "<br />"
                                                . "Hi " . $user_name . ",<br />"
                                                . "</div>"
                                                . "<p style='color: #61646d; text-align: center; padding: 0 20px;'>Just letting you know that the location below has exceeded the maximum spend budget. "
                                                . "<a href=" . base_url() . "details?location_id=" . $location_id . ">"
                                                . "Click here</a> to make changes to this location's maximum spend budget and other settings.</p><br/>"
                                                . "<table cellpadding='0' cellspacing='0' border='0' style='border: 1px solid #d8d8d8; width: 100%; padding: 18px 24px; -moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius: 4px;' class='100p'>"
                                                . "<tr style='font-size: 16px;'>"
                                                . "<td style='text-align: left;'>" . $nickname . "<br /><span style='text-align: left; font-size: 12px; color: #61646d;'>" . $shipping_address . "</span></td>"
                                                . "<td style='text-align: right; vertical-align: top;'>" . $order_total . " (Total)<br /><span style='font-size: 12px;'>Max $" . $spend_budget . "</span></td>"
                                                . "</tr>"
                                                . "</table>";

                                    $email_data = array(
                                        'subject' => $subject,
                                        'message' => $message
                                    );
                                    $body = $this->load->view('/templates/email/alert/index.php', $email_data, TRUE);
                                    $mail_status = send_matix_email($body, $subject, $mail);
                                }
                            }
                        }
                    }
                }
                //Orders Emails sent
                for ($i = 0; $i < count($data['success_order']); $i++) {
                    $vendor = $this->Vendor_model->get_by(array('id' => $data['success_order'][$i]->vendor_id));
                    $vendor_image = $this->Images_model->get_by(array('model_id' => $data['success_order'][$i]->vendor_id, 'model_name' => 'vendor'));
                    $vendors_details = $this->Vendor_groups_model->get_by(array('vendor_id' => $data['success_order'][$i]->vendor_id));
                    $vendor_email_details = $this->User_model->get_by(array('id' => $vendors_details->user_id));
                    $vendor_name = $vendor->name;
                    $order_id = $insert_id;
                    $data['order_details'] = $this->Order_items_model->get_many_by(array('order_id' => $data['success_order'][$i]->id));
                    $data['orders'] = $this->Order_model->get_by(array('id' => $data['success_order'][$i]->id));
                    $location_id = $data['orders']->location_id;
                    //PAYMENT
                    $user_payment = $this->User_payment_option_model->get_by(array('user_id' => $user_id, 'id' => $data['orders']->payment_id));
                    $user = $this->User_model->get_by(array('id' => $user_id));
                    $customer = $this->stripe->getCustomer($user->stripe_id);
                    // Payment method
                    $payment_method = $customer->sources->retrieve($user_payment->token);
                    $users_payment_obj = new stdClass();
                    $users_payment_obj->id = $user_payment->id;
                    $users_payment_obj->token = $user_payment->token;
                    $users_payment_obj->user_id = $user_id;
                    $users_payment_obj->payment_type = $payment_method->object;
                    $users_payment_obj->card_type = $payment_method->brand;
                    $users_payment_obj->exp_month = $payment_method->exp_month;
                    $users_payment_obj->exp_year = $payment_method->exp_year;
                    $users_payment_obj->cc_number = $payment_method->last4;
                    $users_payment_obj->cc_name = $payment_method->name;
                    $users_payment_obj->bank_name = $payment_method->bank_name;
                    $users_payment_obj->ba_routing_number = $payment_method->routing_number;
                    $users_payment_obj->ba_account_number = $payment_method->last4;
                    $users_payment_obj->created_at = $user_payment->created_at;
                    $users_payment_obj->updated_at = $user_payment->update_at;
                    $data['payment'] = $users_payment_obj;
                    unset($users_payment_obj);


                    $data['shipping_address'] = $this->Organization_location_model->get_by(array('id' => $location_id));
                    $data['shippment'] = $this->Shipping_options_model->get_by(array('id' => $data['orders']->shipment_id));
                    $data['vendor_image'] = $this->Images_model->get_by(array('model_id' => $data['orders']->vendor_id, 'model_name' => 'vendor'));
                    $data['vendor_details'] = $this->Vendor_model->get_by(array('id' => $data['orders']->vendor_id));

                    $order_vendor_ids = [];
                    for ($k = 0; $k < count($data['order_details']); $k++) {
                        $product_image = $this->Images_model->get_by(array('model_id' => $data['order_details'][$k]->product_id, 'model_name' => 'products', 'image_type' => 'mainimg'));
                        $product_pricing = $this->Product_pricing_model->get_by(array('product_id' => $data['order_details'][$k]->product_id, 'vendor_id' => $data['order_details'][$k]->vendor_id));
                        $product = $this->Products_model->get_by(array('id' => $data['order_details'][$k]->product_id));
                        $vendors = $this->Vendor_model->get_by(array('id' => $data['order_details'][$k]->vendor_id));
                        $data['order_details'][$k]->product_image = $product_image;
                        $data['order_details'][$k]->Product_details = $product_pricing;
                        $data['order_details'][$k]->product = $product;
                        $order_vendor_ids[] = $data['order_details'][$k]->vendor_id;
                    }
                    $order_vendor_ids = array_unique($order_vendor_ids);

                    $data['promos'] = "";
                    if ($_SESSION['user_id'] != null) {
                        $useremail = $user->email;
                        $subject = 'Order Confirmation';
                        $data['message'] = "Hi,<br /> Thank you for using Matixdental. Your shipping information is below <br/>";
                        $body = $this->load->view('/templates/email/order/index.php', $data, TRUE);
                        $mail_status = send_matix_email($body, $subject, $useremail);
                    }

                    # Loop all vendors in the order
                    foreach ($order_vendor_ids as $order_vendor_id) {
                        # Get each vendor's users
                        $vendor_users = $this->Vendor_groups_model->get_many_by(['vendor_id' => $order_vendor_id]);
                        foreach ($vendor_users as $vendor_user) {
                            # Get each vendor's user details
                            $vendor_user_details = $this->User_model->get_by(['id' => $vendor_user->user_id]);
                            # Email the vendor user
                            if ($vendor_user_details->email_setting2 == '1') {
                                $vendor_email = $vendor_user_details->email;
                                $subject = 'New Order Notification';
                                $data['message'] = "Hi,<br /> A Matix user has placed an order with your company. Please see below for details. As per your contract with Matix, you are responsible for fulfilling this purchase order immediately. Thank you";
                                $body1 = $this->load->view('/templates/email/order/index', $data, TRUE);
                                # Send email
                                send_matix_email($body1, $subject, $vendor_email);
                            }
                        }
                    }

                }
            }
            //sent Restricted Orders sent...
            if ($restricted_id != "" || $restricted_id != null) {
                for ($j = 0; $j < count($data['restricted_orders']); $j++) {
                    $data['order_details'] = $this->Order_items_model->get_many_by(array('order_id' => $data['restricted_orders'][$j]->id));
                    $data['orders'] = $this->Order_model->get_by(array('id' => $data['restricted_orders'][$j]->id));
                    $location_id = $data['orders']->location_id;
                    $user_payment = $this->User_payment_option_model->get_by(array('user_id' => $user_id, 'id' => $data['orders']->payment_id));

                    $user = $this->User_model->get_by(array('id' => $user_id));
                    $customer = $this->stripe->getCustomer($user->stripe_id);
                    // Payment method
                    $payment_method = $customer->sources->retrieve($user_payment->token);
                    $users_payment_obj = new stdClass();
                    $users_payment_obj->id = $user_payment->id;
                    $users_payment_obj->token = $user_payment->token;
                    $users_payment_obj->user_id = $user_id;
                    $users_payment_obj->payment_type = $payment_method->object;
                    $users_payment_obj->card_type = $payment_method->brand;
                    $users_payment_obj->exp_month = $payment_method->exp_month;
                    $users_payment_obj->exp_year = $payment_method->exp_year;
                    $users_payment_obj->cc_number = $payment_method->last4;
                    $users_payment_obj->cc_name = $payment_method->name;
                    $users_payment_obj->bank_name = $payment_method->bank_name;
                    $users_payment_obj->ba_routing_number = $payment_method->routing_number;
                    $users_payment_obj->ba_account_number = $payment_method->last4;
                    $users_payment_obj->created_at = $user_payment->created_at;
                    $users_payment_obj->updated_at = $user_payment->update_at;
                    $data['payment'] = $users_payment_obj;
                    unset($users_payment_obj);

                    $data['shipping_address'] = $this->Organization_location_model->get_by(array('id' => $location_id));
                    $data['shippment'] = $this->Shipping_options_model->get_by(array('id' => $data['orders']->shipment_id));
                    $data['vendor_image'] = $this->Images_model->get_by(array('model_id' => $data['orders']->vendor_id, 'model_name' => 'vendor'));
                    for ($k = 0; $k < count($data['order_details']); $k++) {
                        $data['vendor_details'] = $this->Vendor_model->get_by(array('id' => $data['order_details'][$k]->vendor_id));
                        $product_image = $this->Images_model->get_by(array('model_id' => $data['order_details'][$k]->product_id, 'model_name' => 'products', 'image_type' => 'mainimg'));
                        $product_pricing = $this->Product_pricing_model->get_by(array('product_id' => $data['order_details'][$k]->product_id, 'vendor_id' => $data['order_details'][$k]->vendor_id));
                        $product = $this->Products_model->get_by(array('id' => $data['order_details'][$k]->product_id));
                        $vendors = $this->Vendor_model->get_by(array('id' => $data['order_details'][$k]->vendor_id));
                        $data['order_details'][$k]->product_image = $product_image;
                        $data['order_details'][$k]->Product_details = $product_pricing;
                        $data['order_details'][$k]->product = $product;
                        $data['order_details'][$k]->vendor = $vendors;
                    }
                    $subject = 'Restricted Items Order';
                    $data['message'] = "Hi,<br /> Thank you for using Matixdental. Your order has restricted items and is sent to your organization approver for review. The order will be submitted to the vendor once it is approved. You will not be charged until your order is approved. <br/>";
                    $body = $this->load->view('/templates/email/order/index.php', $data, TRUE);
                    $mail_status = send_matix_email($body, $subject, $email);
                    $users = $this->Organization_groups_model->get_many_by(array('organization_id' => $organization_id));
                    for ($i = 0; $i < count($users); $i++) {
                        $admin[] = $this->User_model->get_by(array('id' => $users[$i]->user_id));
                    }
                    for ($i = 0; $i < count($admin); $i++) {
                        if ($admin[$i]->role_id == '7' || $admin[$i]->role_id == '8' || $admin[$i]->role_id == '9') {
                            $admin_email = $admin[$i]->email;
                            if ($admin_email != null) {
                                $subject = 'Restricted Items Approval Request';
                                $data['message'] = "Hi, <br>" . ucwords($user_name) . "<br />  has requested to purchase the following items available for purchase with License only.";

                                $body = $this->load->view('/templates/email/order/index.php', $data, TRUE);
                                $mail_status = send_matix_email($body, $subject, $admin_email);
                            }
                        }
                    }
                }
            }
            echo json_encode($data);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

}

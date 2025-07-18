<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CartCheckout extends MW_Controller {

    function __construct() {

        parent::__construct();
        $this->load->model('Organization_groups_model');
        $this->load->model('User_location_model');
        $this->load->model('Organization_location_model');
        $this->load->model('Organization_model');
        $this->load->model('Products_model');
        $this->load->model('Product_pricing_model');
        $this->load->model('Vendor_model');
        $this->load->model('User_model');
        $this->load->model('Order_model');
        $this->load->model('Order_items_model');
        $this->load->model('Shipping_options_model');
        $this->load->model('User_payment_option_model');
        $this->load->model('Images_model');
        $this->load->model('User_licenses_model');
        $this->load->model('User_autosave_model');
        $this->load->model('Promo_codes_model');
        $this->load->model('Product_tax_model');
        $this->load->model('Order_promotion_model');
        $this->load->model('Vendor_groups_model');
        $this->load->library('cart');
        $this->load->library('email');
        $this->load->library('stripe');
        $this->load->helper('my_email_helper');
    }

    public function proceed_to_checkout() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $user = $this->User_model->get_by('id', $user_id);
            $payment_id = $this->input->post('payment_id');
            $user_payments = $this->User_payment_option_model->get_by(array('id' => $payment_id));
            $payment_token = $user_payments->token;
            $payment_owner = $this->User_model->get_by('id', $user_payments->user_id);
            $stripeId = $payment_owner->stripe_id;
            $location_id = $this->input->post('location_name');
            $data['user_address'] = $this->Organization_location_model->get_by(array('id' => $location_id));
            // Debugger::debug($data['user_address']);
            $organization_id = $data['user_address']->organization_id;
            $data['user_organization'] = $this->Organization_model->get_by(array('id' => $data['user_address']->organization_id));
            $data['organ_users'] = $this->Organization_groups_model->get_many_by(array('organization_id' => $organization_id));
            $data['order_total'] = $this->Order_model->get_many_by(array('location_id' => $location_id));
            $address1 = $data['user_address']->address1;
            $address2 = $data['user_address']->address2;
            $city = $data['user_address']->city;
            $state = $data['user_address']->state;
            $zip = $data['user_address']->zip;
            $data['order_tax'] = $this->Product_tax_model->get_by(array('ZipCode' => $zip));
            $nickname = $data['user_address']->nickname;
            $spend_budget = $data['user_address']->spend_budget;
            $description = 'Order placed from Matixdental.com';
            $restricted_id = "";
            $insert_id = "";
            $role_id = $_SESSION['role_id'];
            $data['restricted_orders'] = array();
            $data['success_order'] = array();
            $data['view_oreder_link'] = "";
            $order_total = 0;
            for ($i = 0; $i < count($data['order_total']); $i++) {
                $old_total = $data['order_total'][$i]->total;
                $order_total = $old_total + $order_total;
            }
            $orderFlag = FALSE;
            $bLicenceFlag = TRUE;
            $matixVendorFlag = FALSE;
            $independentVendorFlag = FALSE;
            $data['cart'] = $this->cart->contents();
            foreach ($data['cart'] as $row) {
                if ($row['location_id'] == $location_id && $row['status'] == 0) {
                    $vendor_ids[] = $row['ven_id'];
                }
            }
            $unique_vendors = array_values(array_unique($vendor_ids));
            $order_count = count($unique_vendors);
            $vendor_id = $unique_vendors;
            $restricted_items = array();
            $orders_total = array();
            $aLicenExpiry = array();
            // Check if user having licenced products in cart, check their licence.
            for ($i = 0; $i < $order_count; $i++) {
                foreach ($data['cart'] as $row) {
                    if ($row['ven_id'] == $vendor_id[$i] && $row['location_id'] == $location_id && $row['status'] == 0) {
                        $product_count = count($row['rowid'][$i]);
                        for ($j = 0; $j < $product_count; $j++) {
                            $products = $this->Products_model->get_by(array('id' => $row['pro_id']));
                            $licence = $products->license_required;
                            if ($licence == 'Yes') {
                                if ($role_id == '10') {
                                    for ($a = 0; $a < count($data['organ_users']); $a++) {
                                        $admin[] = $this->User_model->get_by(array('id' => $data['organ_users'][$a]->user_id));
                                    }
                                    for ($b = 0; $b < count($admin); $b++) {
                                        if ($admin[$i]->role_id >= '7' && $admin[$b]->role_id <= '9') {
                                            $admin_id = $admin[$b]->id;
                                            $user_license[] = $this->User_licenses_model->get_by(array('state' => $state, 'user_id' => $admin_id, 'approved' => '1'));
                                            $userLicenses = $this->User_licenses_model->loadValidLicenses($admin_id, $state, 1);
                                            Debugger::debug($userLicenses);
                                        }
                                    }
                                } else {
                                    $user_license = $this->User_licenses_model->get_by(array('state' => $state, 'user_id' => $user_id, 'approved' => '1'));

                                    if (is_null($user_license)) {
                                        $tier_1_2 = unserialize(ROLES_TIER1_2);
                                        if (in_array($user->role_id, $tier_1_2)) {
                                            $org_users = $this->Organization_groups_model->get_users_by_user($user_id);

                                            $user_licenses[] = $this->User_licenses_model->get_by(array('state' => $state, 'user_id' => $org_users, 'approved' => '1'));
                                        } else {
                                            $user_licenses[] = $user_license;
                                        }
                                    }
                                    $today_date = date('Y-m-d');
                                    if (count($user_licenses) > 0) {
                                        foreach ($user_licenses as $value) {
                                            if ($value != null) {
                                                $end_date = $value->expire_date;
                                                if ($today_date <= $end_date) {
                                                    $restricted_items[] = $i;
                                                    break;
                                                } else {
                                                    $aLicenExpiry[] = $i;
                                                    break;
                                                }
                                            } else {
                                                $aLicenExpiry[] = $i;
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                $unLicence_vendors = [];
                //student orders goes here
                if ($role_id == "10") {
                    for ($i = 0; $i < $order_count; $i++) {
                        if (in_array($i, $restricted_items)) {
                            //THIS IS A RESTRICTED ORDER. Add items to orders and order_items tables.
                            $subtotal = 0;
                            $products = new stdClass();
                            $products->item_count = 0;
                            $products->shipping_price = 0;
                            $products->promo_discount = 0;
                            $products->shipid = "";
                            $order_tax = 0;
                            foreach ($data['cart'] as $item) {
                                if ($item['ven_id'] == $vendor_id[$i] && $item['location_id'] == $location_id) {
                                    $subtotal += $item['subtotal'];
                                }
                            }
                            if (isset($_SESSION['session_shipping'])) {
                                foreach ($_SESSION['session_shipping'] as $vendor_shipping) {
                                    if ($vendor_id[$i] == $vendor_shipping['shipvendor']) {
                                        if (!(isset($products->shipping_price))) {
                                            $products->shipping_price = 0;
                                        }
                                        if (!(isset($products->shipid))) {
                                            $products->shipid = 0;
                                        }
                                        $products->shipid = $vendor_shipping['shipid'];
                                        $products->shipping_price = $vendor_shipping['shippingprice'];
                                    }
                                }
                            }
                            if (isset($_SESSION['session_promos'])) {
                                foreach ($_SESSION['session_promos'] as $vendor_promos) {
                                    if ($vendor_id[$i] == $vendor_promos['vendorid']) {
                                        if (!(isset($products->promo_discount))) {
                                            $products->promo_discount = 0;
                                        }
                                        $products->promo_discount = $vendor_promos['promodiscount'];
                                    }
                                }
                            }
                            if ($data['order_tax'] != null && $data['order_tax']) {
                                $order_tax = $subtotal * $data['order_tax']->EstimatedCombinedRate;
                            }
                            $order_total = ($subtotal + $order_tax + $products->shipping_price) - $products->promo_discount;
                            if ($order_total > 0) {
                                $payment_cost = round($order_total * 100);
                                $payment_data = array(
                                    'amount' => $payment_cost,
                                    'customer' => $stripeId,
                                    'source' => $payment_token,
                                    'description' => $description
                                );
                                $this->stripe->addCharge($payment_data);
                                $this->session->set_flashdata('success', 'Payment Success!');
                                $insert_data = array(
                                    'order_status' => 'New',
                                    'location_id' => $location_id,
                                    'total' => round($order_total, 2),
                                    'tax' => $order_tax,
                                    'shipping_price' => round($products->shipping_price, 2),
                                    'user_id' => $user_id,
                                    'vendor_id' => $vendor_id[$i],
                                    'address1' => $address1,
                                    'address2' => $address2,
                                    'city' => $city,
                                    'state' => $state,
                                    'zip' => $zip,
                                    'nickname' => $nickname,
                                    'shipment_id' => $products->shipid,
                                    'payment_id' => $payment_id,
                                    'restricted_order' => '1',
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s')
                                );
                                $this->Order_model->insert($insert_data);
                                $restricted_id = $this->db->insert_id();
                            }
                            foreach ($data['cart'] as $item) {
                                if ($item['ven_id'] == $vendor_id[$i] && $item['location_id'] == $location_id) {
                                    if ($data['order_tax'] != null) {
                                        $item_tax = $item['subtotal'] * $data['order_tax']->EstimatedCombinedRate;
                                    } else {
                                        $item_tax = 0.00;
                                    }
                                    $order_items = array(
                                        'order_id' => $restricted_id,
                                        'item_status' => '0',
                                        'shipment_id' => $products->shipid,
                                        'shipping_price' => round($products->shipping_price, 2),
                                        'product_id' => $item['pro_id'],
                                        'vendor_id' => $vendor_id[$i],
                                        'price' => $item['price'],
                                        'tax' => $item_tax,
                                        'quantity' => $item['qty'],
                                        'picked' => $item['qty'],
                                        'total' => $item['subtotal'],
                                        'restricted_order' => '1',
                                        'created_at' => date('Y-m-d H:i:s'),
                                        'updated_at' => date('Y-m-d H:i:s')
                                    );
                                    $this->Order_items_model->insert($order_items);
                                    $this->session->set_flashdata('success', 'Order Success!');
                                    $update_data = array($item['rowid'] => array(
                                        'rowid' => $item['rowid'],
                                        'qty' => 0
                                    ));
                                    $this->cart->update($update_data);
                                }
                            }
                            if (isset($_SESSION['session_promos'])) {
                                $promocount = count($_SESSION['session_promos']);
                                $promos = $_SESSION['session_promos'];
                                $promokey = array_keys($_SESSION['session_promos']);
                                for ($k = 0; $k < $promocount; $k++) {
                                    if ($promos[$promokey[$k]]['vendorid'] == $vendor_id[$i]) {
                                        $id = $promokey[$k];
                                        $promoid = $promos[$promokey[$k]]['promoid'];
                                        $promo_discount = $promos[$promokey[$k]]['promodiscount'];
                                        $insert_promo = array(
                                            'order_id' => $restricted_id,
                                            'user_id' => $user_id,
                                            'promo_id' => $promoid,
                                            'discount_value' => round($promo_discount, 2),
                                            'restricted_order' => '1',
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'updated_at' => date('Y-m-d H:i:s')
                                        );
                                        $this->Order_promotion_model->insert($insert_promo);
                                        if ($promos[$promokey[$k]]['free_productid'] != null) {
                                            $free_productid = $promos[$promokey[$k]]['free_productid'];
                                            $promo_data = array(
                                                'order_id' => $restricted_id,
                                                'item_status' => '0',
                                                'shipment_id' => $products->shipid,
                                                'shipping_price' => $products->shipping_price,
                                                'product_id' => $free_productid,
                                                'vendor_id' => $vendor_id[$i],
                                                'price' => '0.00',
                                                'tax' => '0.00',
                                                'quantity' => '1',
                                                'picked' => '1',
                                                'total' => '0.00',
                                                'created_at' => date('Y-m-d H:i:s'),
                                                'updated_at' => date('Y-m-d H:i:s')
                                            );
                                            $this->Order_items_model->insert($promo_data);
                                        }
                                        unset($_SESSION['session_promos'][$id]);
                                    }
                                }
                            }
                            $this->session->set_flashdata('error', 'Order is pending, and will be processed if approved.');
                            $data['restricted_orders'][] = $this->Order_model->get_by(array('id' => $restricted_id));
                        } else if (in_array($i, $aLicenExpiry)) {
                            $unLicence_vendors[] = $vendor_id[$i];
                            //THIS ORDER can't be processed. user without licence or expired .
                            $bLicenceFlag = FALSE;
                            $orderFlag = FALSE;
                        } else {
                            //Non Restricted orders...
                            $orderFlag = True;
                            $order_vendor_id[] = $vendor_id[$i];
                            $orders_total[] = $vendor_id[$i];
                        }
                    }
                } else if ($role_id == "1" || $role_id == "3" || $role_id == "4" || $role_id == "7") {

                    $orderFlag = True;
                    $orders_total = array_values(array_unique($vendor_ids));
                    $order_vendor_id = array_values(array_unique($vendor_ids));
                }
                //starts order placed based on vendor type
                $cart_total_orders = count($orders_total);

                Debugger::debug($cart_total_orders, '$cart_total_orders');

                if ($orderFlag) {
                    for ($i = 0; $i < $cart_total_orders; $i++) {
                        if (!(in_array($i, $aLicenExpiry))) {
                            $vendor = $this->Vendor_model->get($order_vendor_id[$i]);

                            Debugger::debug($vendor, '$vendor');

                            if ($vendor->vendor_type == '0') {
                                $independentVendorId = $vendor->id;
                                $independentVendorFlag = TRUE;
                            } else {
                                $matixVendorId = $vendor->id;
                                $matixVendorFlag = TRUE;
                            }


                            // if(!empty($this->input->post('vendor_id'))){
                            //     if($this->input->post('vendor_id') != $vendor->id){
                            //         continue;
                            //     }
                            // }
                            //independent vendor order goes here
                            if ($independentVendorFlag) {
                                $subtotal = 0;
                                $products = new stdClass();
                                $products->item_count = 0;
                                $products->shipping_price = 0;
                                $products->promo_discount = 0;
                                $products->shipid = "";
                                $order_tax = 0;
                                foreach ($data['cart'] as $item) {
                                    if ($item['ven_id'] == $independentVendorId && $item['location_id'] == $location_id) {
                                        $subtotal += $item['subtotal'];
                                    }
                                }
                                if (isset($_SESSION['session_shipping'])) {
                                    foreach ($_SESSION['session_shipping'] as $vendor_shipping) {
                                        if ($independentVendorId == $vendor_shipping['shipvendor']) {
                                            if (!(isset($products->shipping_price))) {
                                                $products->shipping_price = 0;
                                            }
                                            if (!(isset($products->shipid))) {
                                                $products->shipid = 0;
                                            }
                                            $products->shipid = $vendor_shipping['shipid'];
                                            $products->shipping_price = $vendor_shipping['shippingprice'];
                                        }
                                    }
                                }
                                if (isset($_SESSION['session_promos'])) {
                                    foreach ($_SESSION['session_promos'] as $vendor_promos) {
                                        if ($independentVendorId == $vendor_promos['vendorid']) {
                                            if (!(isset($products->promo_discount))) {
                                                $products->promo_discount = 0;
                                            }
                                            $products->promo_discount = $vendor_promos['promodiscount'];
                                        }
                                    }
                                }
                                if ($data['order_tax'] != null && $data['order_tax']) {
                                    $order_tax = $subtotal * $data['order_tax']->EstimatedCombinedRate;
                                }
                                $order_total = ($subtotal + $order_tax + $products->shipping_price) - $products->promo_discount;
                                if ($order_total > 0) {
                                    $vendor_token = $this->stripe->createToken(
                                        ["customer" => $payment_owner->stripe_id],
                                        ["stripe_account" => $vendor->payment_id]
                                    );

                                    $payment_cost = round($order_total * 100);
                                    $payment_data = [
                                        'amount' => $payment_cost,
                                        'source' => $vendor_token->id,
                                        'description' => $description
                                    ];

                                    $chargeOptions = null;
                                    if ($vendor->payment_id != null && $vendor->payment_id != "") {
                                        #Make it a direct charge
                                        $chargeOptions = ['stripe_account' => $vendor->payment_id];
                                    }

                                    $this->stripe->addCharge($payment_data, $chargeOptions);
                                    $this->session->set_flashdata('success', 'Payment Success!');
                                    $insert_data = array(
                                        'site_id' => ((!empty(config_item('whitelabel'))) ? config_item('whitelabel')->id : null),
                                        'order_status' => 'New',
                                        'location_id' => $location_id,
                                        'total' => round($order_total, 2),
                                        'tax' => $order_tax,
                                        'shipping_price' => round($products->shipping_price, 2),
                                        'user_id' => $user_id,
                                        'vendor_id' => $independentVendorId,
                                        'address1' => $address1,
                                        'address2' => $address2,
                                        'city' => $city,
                                        'state' => $state,
                                        'zip' => $zip,
                                        'nickname' => $nickname,
                                        'shipment_id' => $products->shipid,
                                        'payment_id' => $payment_id,
                                        'created_at' => date('Y-m-d H:i:s'),
                                        'updated_at' => date('Y-m-d H:i:s')
                                    );
                                    $this->Order_model->insert($insert_data);
                                    $insert_id = $this->db->insert_id();
                                }
                                foreach ($data['cart'] as $item) {
                                    if ($item['ven_id'] == $independentVendorId && $item['location_id'] == $location_id) {
                                        if ($data['order_tax'] != null) {
                                            $item_tax = $item['subtotal'] * $data['order_tax']->EstimatedCombinedRate;
                                        } else {
                                            $item_tax = 0.00;
                                        }
                                        $order_items = array(
                                            'order_id' => $insert_id,
                                            'item_status' => '0',
                                            'shipment_id' => $products->shipid,
                                            'shipping_price' => round($products->shipping_price, 2),
                                            'product_id' => $item['pro_id'],
                                            'vendor_id' => $independentVendorId,
                                            'price' => $item['price'],
                                            'tax' => $item_tax,
                                            'quantity' => $item['qty'],
                                            'picked' => $item['qty'],
                                            'total' => $item['subtotal'],
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'updated_at' => date('Y-m-d H:i:s')
                                        );
                                        $this->Order_items_model->insert($order_items);
                                        $this->session->set_flashdata('success', 'Order Success!');
                                        $update_data = array($item['rowid'] => array(
                                            'rowid' => $item['rowid'],
                                            'qty' => 0
                                        ));
                                        $this->cart->update($update_data);
                                    }
                                }
                                if (isset($_SESSION['session_promos'])) {
                                    $promocount = count($_SESSION['session_promos']);
                                    $promos = $_SESSION['session_promos'];
                                    $promokey = array_keys($_SESSION['session_promos']);
                                    for ($k = 0; $k < $promocount; $k++) {
                                        if ($promos[$promokey[$k]]['vendorid'] == $independentVendorId) {
                                            $id = $promokey[$k];
                                            $promoid = $promos[$promokey[$k]]['promoid'];
                                            $promo_discount = $promos[$promokey[$k]]['promodiscount'];
                                            $insert_promo = array(
                                                'order_id' => $insert_id,
                                                'user_id' => $user_id,
                                                'promo_id' => $promoid,
                                                'discount_value' => round($promo_discount, 2),
                                                'created_at' => date('Y-m-d H:i:s'),
                                                'updated_at' => date('Y-m-d H:i:s')
                                            );
                                            $this->Order_promotion_model->insert($insert_promo);
                                            if ($promos[$promokey[$k]]['free_productid'] != null) {
                                                $free_productid = $promos[$promokey[$k]]['free_productid'];
                                                $promo_data = array(
                                                    'order_id' => $insert_id,
                                                    'item_status' => '0',
                                                    'shipment_id' => $products->shipid,
                                                    'shipping_price' => $products->shipping_price,
                                                    'product_id' => $free_productid,
                                                    'vendor_id' => $independentVendorId,
                                                    'price' => '0.00',
                                                    'tax' => '0.00',
                                                    'quantity' => '1',
                                                    'picked' => '1',
                                                    'total' => '0.00',
                                                    'created_at' => date('Y-m-d H:i:s'),
                                                    'updated_at' => date('Y-m-d H:i:s')
                                                );
                                                $this->Order_items_model->insert($promo_data);
                                            }
                                            unset($_SESSION['session_promos'][$id]);
                                        }
                                    }
                                }
                                $independentVendorFlag = FALSE;
                            }

                            if ($matixVendorFlag) {
                                // Matix vendor order goes here
                                try {
                                    $subtotal = 0;
                                    $products = new stdClass();
                                    $products->item_count = 0;
                                    $products->shipping_price = 0;
                                    $products->promo_discount = 0;
                                    $products->shipid = "";
                                    $order_tax = 0;
                                    foreach ($data['cart'] as $item) {
                                        if ($item['ven_id'] == $matixVendorId && $item['location_id'] == $location_id) {
                                            $subtotal += $item['subtotal'];
                                        }
                                    }
                                    if (isset($_SESSION['session_shipping'])) {
                                        foreach ($_SESSION['session_shipping'] as $vendor_shipping) {
                                            if ($matixVendorId == $vendor_shipping['shipvendor']) {
                                                if (!(isset($products->shipping_price))) {
                                                    $products->shipping_price = 0;
                                                }
                                                if (!(isset($products->shipid))) {
                                                    $products->shipid = 0;
                                                }
                                                $products->shipid = $vendor_shipping['shipid'];
                                                $products->shipping_price = $vendor_shipping['shippingprice'];
                                            }
                                        }
                                    }
                                    if (isset($_SESSION['session_promos'])) {
                                        foreach ($_SESSION['session_promos'] as $vendor_promos) {
                                            if ($matixVendorId == $vendor_promos['vendorid']) {
                                                if (!(isset($products->promo_discount))) {
                                                    $products->promo_discount = 0;
                                                }
                                                $products->promo_discount = $vendor_promos['promodiscount'];
                                            }
                                        }
                                    }
                                    if ($data['order_tax'] != null && $data['order_tax']) {
                                        $order_tax = $subtotal * $data['order_tax']->EstimatedCombinedRate;
                                    }
                                    $order_total = ($subtotal + $order_tax + $products->shipping_price) - $products->promo_discount;
                                    if ($order_total > 0) {

                                        $payment_cost = round($order_total * 100);
                                        $payment_data = array(
                                            'amount' => $payment_cost,
                                            'customer' => $stripeId,
                                            'source' => $payment_token,
                                            'description' => $description
                                        );
                                        $matixvendor = $this->Vendor_model->get($matixVendorId);
                                        if ($matixvendor->payment_id != null && $matixvendor->payment_id != "") {
                                            $payment_data['destination'] = $matixvendor->payment_id;
                                            $payment_data['application_fee'] = round($payment_cost * 0.07);
                                        }
                                        $this->stripe->addCharge($payment_data);
                                        $this->session->set_flashdata('success', 'Payment Success!');
                                        $insert_data = array(
                                            'order_status' => 'New',
                                            'location_id' => $location_id,
                                            'total' => round($order_total, 2),
                                            'tax' => $order_tax,
                                            'shipping_price' => round($products->shipping_price, 2),
                                            'user_id' => $user_id,
                                            'vendor_id' => $matixVendorId,
                                            'address1' => $address1,
                                            'address2' => $address2,
                                            'city' => $city,
                                            'state' => $state,
                                            'zip' => $zip,
                                            'nickname' => $nickname,
                                            'shipment_id' => $products->shipid,
                                            'payment_id' => $payment_id,
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'updated_at' => date('Y-m-d H:i:s')
                                        );
                                        $this->Order_model->insert($insert_data);
                                        $insert_id = $this->db->insert_id();
                                    }
                                    foreach ($data['cart'] as $item) {
                                        if ($item['ven_id'] == $matixVendorId && $item['location_id'] == $location_id) {

                                            if ($data['order_tax'] != null) {
                                                $item_tax = $item['subtotal'] * $data['order_tax']->EstimatedCombinedRate;
                                            } else {
                                                $item_tax = 0.00;
                                            }
                                            $order_items = array(
                                                'order_id' => $insert_id,
                                                'item_status' => '0',
                                                'shipment_id' => $products->shipid,
                                                'shipping_price' => round($products->shipping_price, 2),
                                                'product_id' => $item['pro_id'],
                                                'vendor_id' => $matixVendorId,
                                                'price' => $item['price'],
                                                'tax' => $item_tax,
                                                'quantity' => $item['qty'],
                                                'picked' => $item['qty'],
                                                'total' => $item['subtotal'],
                                                'created_at' => date('Y-m-d H:i:s'),
                                                'updated_at' => date('Y-m-d H:i:s')
                                            );
                                            $this->Order_items_model->insert($order_items);
                                            $this->session->set_flashdata('success', 'Order Success!');
                                            $update_data = array($item['rowid'] => array(
                                                'rowid' => $item['rowid'],
                                                'qty' => 0
                                            ));
                                            $this->cart->update($update_data);
                                        }
                                    }
                                    if (isset($_SESSION['session_promos'])) {
                                        $promocount = count($_SESSION['session_promos']);
                                        $promos = $_SESSION['session_promos'];
                                        $promokey = array_keys($_SESSION['session_promos']);
                                        for ($k = 0; $k < $promocount; $k++) {
                                            if ($promos[$promokey[$k]]['vendorid'] == $matixVendorId) {
                                                $id = $promokey[$k];
                                                $promoid = $promos[$promokey[$k]]['promoid'];
                                                $promo_discount = $promos[$promokey[$k]]['promodiscount'];
                                                $insert_promo = array(
                                                    'order_id' => $insert_id,
                                                    'user_id' => $user_id,
                                                    'promo_id' => $promoid,
                                                    'discount_value' => round($promo_discount, 2),
                                                    'created_at' => date('Y-m-d H:i:s'),
                                                    'updated_at' => date('Y-m-d H:i:s')
                                                );
                                                $this->Order_promotion_model->insert($insert_promo);
                                                if ($promos[$promokey[$k]]['free_productid'] != null) {
                                                    $free_productid = $promos[$promokey[$k]]['free_productid'];
                                                    $promo_data = array(
                                                        'order_id' => $insert_id,
                                                        'item_status' => '0',
                                                        'shipment_id' => $products->shipid,
                                                        'shipping_price' => $products->shipping_price,
                                                        'product_id' => $free_productid,
                                                        'vendor_id' => $matixVendorId,
                                                        'price' => '0.00',
                                                        'tax' => '0.00',
                                                        'quantity' => '1',
                                                        'picked' => '1',
                                                        'total' => '0.00',
                                                        'created_at' => date('Y-m-d H:i:s'),
                                                        'updated_at' => date('Y-m-d H:i:s')
                                                    );
                                                    $this->Order_items_model->insert($promo_data);
                                                }
                                                unset($_SESSION['session_promos'][$id]);
                                            }
                                        }
                                    }
                                } catch (Exception $e) {
                                    $this->session->set_flashdata('error', 'Invalid payment credentials.');
                                    header("Location:cart?id=" . $location_id);
                                }
                                $matixVendorFlag = FALSE;
                            }
                            $data['success_order'][] = $this->Order_model->get_by(array('id' => $insert_id));
                        } else { //user don't have licence or expired
                            $bLicenceFlag = FALSE;
                            $unLicence_vendors[] = $vendor_id[$i];
                        }
                    }
                }
                if (!($bLicenceFlag)) { // Display a message,if the user done have licence or expired
                    if ($unLicence_vendors != null) {
                        $vendor_name = array();
                        foreach ($unLicence_vendors as $key) {
                            $vendor_names = $this->Vendor_model->get($key);
                            $vendor_name[] = $vendor_names->name;
                        }
                    }
                    if (count($vendor_name) > 0) {
                        $this->session->set_flashdata('error', 'The products you are trying to purchase from ' . join(", ", $vendor_name) . ' require a license for ' . $state . '. Please add a valid license for ' . $state . ' and try purchasing again once the license is approved.');
                    }
                }
                $user = $this->User_model->get_by(array('id' => $user_id));
                $user_name = $user->first_name;
                $email = $user->email;
                $shipping_address = $address1 . "<br/>" . $address2 . "<br/>" . $city . ", " . $state . "<br/>" . $zip;

                //starts to sent orders details
                if ($insert_id != "" || $insert_id != null) {
                    // sent budget exceed notification
                    foreach ($data['organ_users'] as $key) {
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
                    //sent Orders Email
                    for ($k = 0; $k < count($data['success_order']); $k++) {
                        $vendors = $this->Vendor_model->get_by(array('id' => $data['success_order'][$k]->vendor_id));
                        $data['success_order'][$k]->vendors = $vendors;
                        $vendor_image = $this->Images_model->get_by(array('model_id' => $data['success_order'][$k]->vendor_id, 'model_name' => 'vendor'));
                        $data['success_order'][$k]->vendor_image = null;
                        if ($vendor_image != null) {
                            $data['success_order'][$k]->vendor_image = $vendor_image;
                        }
                    }

                    for ($i = 0; $i < count($data['success_order']); $i++) {
                        $data['order_details'] = $this->Order_items_model->get_many_by(array('order_id' => $data['success_order'][$i]->id));
                        $data['orders'] = $this->Order_model->get_by(array('id' => $data['success_order'][$i]->id));
                        $location_id = $data['orders']->location_id;
                        $data['payments'] = $this->User_payment_option_model->get_by(array('user_id' => $user_id, 'id' => $data['orders']->payment_id));
                        $data['shipping_address'] = $this->Organization_location_model->get_by(array('id' => $location_id));
                        $data['shippment'] = $this->Shipping_options_model->get_by(array('id' => $data['orders']->shipment_id));
                        $data['vendor_image'] = $this->Images_model->get_by(array('model_id' => $data['orders']->vendor_id, 'model_name' => 'vendor'));
                        $order_vendor_ids = [];
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

                            $order_vendor_ids[] = $data['order_details'][$k]->vendor_id;
                        }
                        $order_vendor_ids = array_unique($order_vendor_ids);

                        $data['promos'] = $this->Order_promotion_model->get_many_by(array('order_id' => $data['success_order'][$i]->id));
                        if ($data['promos'] != null) {
                            for ($k = 0; $k < count($data['promos']); $k++) {
                                $promocode = $this->Promo_codes_model->get_by(array('id' => $data['promos'][$k]->promo_id));
                                $data['promos'][$k]->promocode = $promocode;
                            }
                        }
                        $useremail = $user->email;
                        $user_name = $user->first_name;
                        $subject = 'Order Confirmation';
                        $data['subject'] = $subject;
                        $data['orderUser'] = $user;

                        $data['message'] = "<div style='text-align: center; line-height: 25px;'>"
                            . "<hr style='margin: 0 auto; width: 40px; color: #e8eaf1; background-color: #e8eaf1; border: 1px solid #e8eaf1;'>"
                            . "<br />"
                            . "Hi " . $user_name . ", thanks for shopping with us. We'll send you a notification when your order ships. If you have any questions about your order, please contact the vendor directly:"
                            . "<br />"
                            . "</div>";


                        $body = $this->load->view('/templates/email/order/index.php', $data, TRUE);
                        $mail_status = send_matix_email($body, $subject, $useremail);

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
                        /*
                        Send notification to other users:
                            Email all tier 1s
                            Email All tier 2s at the location of the order
                        */
                        $sql = "SELECT u.first_name, u.last_name, u.email, u.role_id, ul.organization_location_id AS location_id, u.role_id, ol.nickname AS location_name, ol.organization_id
                                FROM users AS u
                                JOIN user_locations AS ul
                                    ON u.id = ul.user_id
                                JOIN organization_locations AS ol
                                    ON ol.id = ul.organization_location_id
                                JOIN roles AS r
                                    ON u.role_id = r.id
                                WHERE ol.organization_id = $organization_id";

                        $orgUsers = $this->db->query($sql)->result();

                        $sentEmails = [];
                        $subject = 'Order Confirmation';
                        $data['subject'] = 'Order Confirmation';
                        foreach($orgUsers as $orgUser){
                            if($orgUser->email != $useremail
                               && ($orgUser->location_id == $location_id || $orgUser->role_id == '3' || $orgUser->role_id == '7')
                               && $orgUser->role_id != '5'
                               && $orgUser->role_id != '6'
                               && !in_array($orgUser->email, $sentEmails)) {

                                $userName = $orgUser->first_name . ' ' . $orgUser->last_name;
                                $data['message'] = "<div style='text-align: center; line-height: 25px;'>"
                                                 . "<hr style='margin: 0 auto; width: 40px; color: #e8eaf1; background-color: #e8eaf1; border: 1px solid #e8eaf1;'>"
                                                 . "<br />"
                                                 . $user_name . " just placed this order for " . $orgUser->location_name . ", we will send a notification when the order is processed."
                                                 . "<br />"
                                                 . "</div>";

                                $body = $this->load->view('/templates/email/order/index.php', $data, TRUE);

                                $mail_status = send_matix_email($body, $subject, $orgUser->email);
                                $sentEmails[] = $orgUser->email;
                                // send test email to len
                                // $mail_status = send_matix_email($body, $subject, 'lenlyle@gmail.com');
                                //Debugger::debug($mail_status);
                            }
                        }
                    }
                }
                //starts to sent Resticted orders details
                if ($restricted_id != "" || $restricted_id != null) {
                    for ($k = 0; $k < count($data['restricted_orders']); $k++) {
                        $vendors = $this->Vendor_model->get_by(array('id' => $data['restricted_orders'][$k]->vendor_id));
                        $data['restricted_orders'][$k]->vendors = $vendors;
                        $vendor_image = $this->Images_model->get_by(array('model_id' => $data['restricted_orders'][$k]->vendor_id, 'model_name' => 'vendor'));
                        $data['restricted_orders'][$k]->vendor_image = null;
                        if ($vendor_image != null) {
                            $data['restricted_orders'][$k]->vendor_image = $vendor_image;
                        }
                    }
                    for ($j = 0; $j < count($data['restricted_orders']); $j++) {
                        $view_order_id = $data['restricted_orders'][$j]->id;
                        $data['order_details'] = $this->Order_items_model->get_many_by(array('order_id' => $data['restricted_orders'][$j]->id));
                        $data['orders'] = $this->Order_model->get_by(array('id' => $data['restricted_orders'][$j]->id));
                        $location_id = $data['orders']->location_id;
                        $data['payments'] = $this->User_payment_option_model->get_by(array('user_id' => $user_id, 'id' => $data['orders']->payment_id));
                        $data['shipping_address'] = $this->Organization_location_model->get_by(array('id' => $location_id));
                        $data['shippment'] = $this->Shipping_options_model->get_by(array('id' => $data['orders']->shipment_id));
                        $data['vendor_image'] = $this->Images_model->get_by(array('model_id' => $data['orders']->vendor_id, 'model_name' => 'vendor'));
                        for ($k = 0; $k < count($data['order_details']); $k++) {
                            $order_view_id = $data['restricted_orders'][$j]->id;
                            $data['vendor_details'] = $this->Vendor_model->get_by(array('id' => $data['restricted_orders'][$j]->vendor_id));
                            $product_image = $this->Images_model->get_by(array('model_id' => $data['order_details'][$k]->product_id, 'model_name' => 'products', 'image_type' => 'mainimg'));
                            $product_pricing = $this->Product_pricing_model->get_by(array('product_id' => $data['order_details'][$k]->product_id, 'vendor_id' => $data['order_details'][$k]->vendor_id));
                            $product = $this->Products_model->get_by(array('id' => $data['order_details'][$k]->product_id));
                            $vendors = $this->Vendor_model->get_by(array('id' => $data['order_details'][$k]->vendor_id));
                            $data['order_details'][$k]->product_image = $product_image;
                            $data['order_details'][$k]->Product_details = $product_pricing;
                            $data['order_details'][$k]->product = $product;
                            $data['order_details'][$k]->vendor = $vendors;
                        }

                        $data['promos'] = $this->Order_promotion_model->get_many_by(array('order_id' => $data['restricted_orders'][$j]->id));
                        if ($data['promos'] != null) {
                            for ($k = 0; $k < count($data['promos']); $k++) {
                                $promocode = $this->Promo_codes_model->get_by(array('id' => $data['promos'][$k]->promo_id));
                                $data['promos'][$k]->promocode = $promocode;
                            }
                        }
                        $subject = 'Restricted Items Order';
                        $data['message'] = "Hi, <br>Thank you for using Matixdental. Your order has restricted items and is sent to your organization approver for review. The order will be submitted to the vendor once it is approved. You will not be charged until your order is approved. <br/>";
                        $body = $this->load->view('/templates/email/order/index.php', $data, TRUE);
                        $mail_status = send_matix_email($body, $subject, $email);
                        $users = $this->Organization_groups_model->get_many_by(array('organization_id' => $organization_id));
                        for ($i = 0; $i < count($users); $i++) {
                            $admin[] = $this->User_model->get_by(array('id' => $users[$i]->user_id));
                        }
                        for ($i = 0; $i < count($admin); $i++) {
                            if ($admin[$i]->role_id >= '7' && $admin[$i]->role_id <= '9') {
                                $admin_email = $admin[$i]->email;
                                if ($admin_email != null) {
                                    $data['view_oreder_link'] = "<a href='" . base_url() . "view-pending?id=" . $view_order_id . "' style='padding: 20px 40px 20px 40px;width:auto;display: block;text-decoration: none;border:0;text-align: center;font-weight: bold;font-size: 16px;font-family: Arial, sans-serif;color: #ffffff;background: #2893FF;border: 1px solid #317ED0;-moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius: 4px;line-height:normal;' class='button_link'>View Order</a>";
                                    $subject = 'Restricted Items Approval Request';
                                    $data['message'] = "Hi, <br>User " . ucwords($user_name) . " has requested to purchase the following items available for purchase with License only.";
                                    $body = $this->load->view('/templates/email/order/index.php', $data, TRUE);
                                    $mail_status = send_matix_email($body, $subject, $admin_email);
                                }
                            }
                        }
                    }
                }

                $data['cart_data'] = $this->cart->contents();
                $data['carts'] = $this->User_autosave_model->saveCart($_SESSION['role_id'], $data['cart_data'], $organization_id, $user_id);
                $data['locations'] = $this->User_location_model->get_many_by(array('user_id' => $user_id));
                for ($i = 0; $i < count($data['locations']); $i++) {
                    $data['user_locations'][] = $this->Organization_location_model->get_by(array('id' => $data['locations'][$i]->organization_location_id));
                }
                $qty = 0;
                $data['cart'] = $this->cart->contents();
                if ($data['cart'] != null) {
                    foreach ($data['cart'] as $item) {
                        if ($item['location_id'] == $location_id && $item['status'] == 0) {
                            $cart_qty = $item['qty'];
                            $qty = $qty + $cart_qty;
                        }
                    }
                }
                $data['item_count'] = $qty;
                $data['checkout_location_id'] = $location_id;
                $this->load->view('/templates/_inc/header', $data);
                $this->load->view('/templates/cart/complete/index', $data);
                $this->load->view('/templates/_inc/footer');
            }

        } else {
                $this->session->set_flashdata("error", "Please login to continue");
                header("location: user-loginpage");
        }

    }

}

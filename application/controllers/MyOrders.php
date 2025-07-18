<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MyOrders extends MW_Controller {

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
        $this->load->model('Shipping_options_model');
        $this->load->model('Images_model');
        $this->load->model('Recurring_order_model');
        $this->load->model('Recurring_order_item_model');
        $this->load->model('Order_item_return_model');
        $this->load->model('Order_return_model');
        $this->load->model('User_licenses_model');
        $this->load->model('User_autosave_model');
        $this->load->model('Promo_codes_model');
        $this->load->model('Product_tax_model');
        $this->load->model('Order_promotion_model');
        $this->load->model('Vendor_groups_model');
        $this->load->model('User_payment_option_model');
        $this->load->helper('date');
        $this->load->library('email');
        $this->load->library('stripe');
        $this->load->helper('my_email_helper');
    }

    // JM: 7/31/18 - Utility function to set the User Info on Orders
    public function populateUserInfo($orders) {
      foreach ($orders as $order) {
        $user = $this->User_model->get_by('id', $order->user_id);
        $order->user_info = $user;
      }
      return $orders;
    }

    public function order_history() {
        $roles = unserialize(ROLES_USERS);

        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $data['orders'] = array();
            $data['recurring_order'] = array();
            if (isset($_SESSION['location_id'])) {
                $location_id = $_SESSION['location_id'];
            } else {
                $location_id = "all";
            }
            $sort_location_id = $this->input->post('sort_location_id');
            $data['sortBy_date'] = $this->input->post('sortBy_date');
            $duration = 30;
            if ($data['sortBy_date'] == null || $data['sortBy_date'] == "") {
                $duration = 30;
            } else {
                $duration = $data['sortBy_date'];
            }
            $date_sort = date('Y-m-d', strtotime("-" . $duration . " days"));
            $data['sort_location_id'] = $sort_location_id;

            /* JM: 8/5/18
            ** Moving user locations population outside of the conditionals to use in various queries.
            */
            $locations = $this->User_location_model->get_many_by(array('user_id' => $user_id));
            $user_locations = [];
            $user_location_ids = [];
            for ($i = 0; $i < count($locations); $i++) {
              $user_locations[] = $this->Organization_location_model->get_many_by(array('id' => $locations[$i]->organization_location_id));
              $user_location_ids[] = $locations[$i]->organization_location_id;
            }

            $organizationId = $this->Organization_groups_model->get_by(['user_id' => $user_id])->organization_id;
            $orgLocations = $this->Organization_location_model->get_many_by(array('organization_id' => $organizationId));
            $orgLocationIds = [];
            foreach($orgLocations as $orgLocation){
                $orgLocationIds[] = $orgLocation->id;
            }
            Debugger::debug($orgLocations, '$orgLocations');
            //selected location based data orders retrive
            Debugger::debug($location_id);
            if ($location_id == "all") {
                Debugger::debug($sort_location_id);
                if ($sort_location_id != null && $sort_location_id != "") {
                    //$data['order'] = $this->Order_model->order_by('id', 'desc')->get_many_by(array('user_id' => $user_id, 'location_id' => $sort_location_id, 'created_at >=' => $date_sort)); // This will restrict the orders to only current user.
                    $data['order'] = $this->Order_model->order_by('id', 'desc')->get_many_by(array('location_id' => $sort_location_id, 'created_at >=' => $date_sort));
                    $this->populateUserInfo($data['order']); //JM: 7/31/18
                    $data['orders_data'] = $this->Order_model->get_many_by(array('user_id' => $user_id, 'restricted_order' => '0', 'location_id' => $sort_location_id, 'created_at >=' => $date_sort));
                    $data['recurring_order'] = $this->Recurring_order_model->get_many_by(array('user_id' => $user_id, 'location_id' => $sort_location_id, 'created_at >=' => $date_sort));
                } else {
                    //$data['order'] = $this->Order_model->order_by('id', 'desc')->get_many_by(array('user_id' => $user_id, 'created_at >=' => $date_sort)); // This will restrict the orders to only current user.
                    // JM: 08/05/18 Showing all locations for Tier 1 and only the users locations for tier 2
                    if ($_SESSION['role_id'] == 3) {
                        $data['order'] = $this->Order_model->order_by('id', 'desc')->get_many_by(array('location_id' => $orgLocationIds, 'created_at >=' => $date_sort));
                    }  else if (in_array($_SESSION['role_id'], [4,5,6])){
                        $data['order'] = $this->Order_model->order_by('id', 'desc')->get_many_by(array('location_id' => $user_location_ids, 'created_at >=' => $date_sort));
                    }
                    $this->populateUserInfo($data['order']); //JM: 7/31/18
                    $data['orders_data'] = $this->Order_model->get_many_by(array('user_id' => $user_id, 'restricted_order' => '0', 'created_at >=' => $date_sort));
                    $data['recurring_order'] = $this->Recurring_order_model->get_many_by(array('user_id' => $user_id, 'created_at >=' => $date_sort));
                }
                // Check license for the latest order state
                    if (!empty($data['order']) && isset($data['order'][0]->state)) {
                        $state = $data['order'][0]->state;

                        $user_license = $this->User_licenses_model->get_by([
                            'state' => $state,
                            'user_id' => $user_id,
                            'approved' => '1'
                        ]);

                        if (empty($user_license)) {
                           $msg = 'The products you are trying to purchase from ' . $data['order'][0]->vendor->name .
                                 ' require a license for ' . $state .
                                '<button class="btn btn--s btn--primary modal--toggle" style="border-radius: 4px; padding: 6px 12px;" data-target="#addNewLicenseModal">Add License</button>';

                            $this->session->set_flashdata('error', $msg);
                        }
                    }
                    
            } else {
                if ($sort_location_id != null && $sort_location_id != "") {
                  // $data['order'] = $this->Order_model->order_by('id', 'desc')->get_many_by(array('user_id' => $user_id, 'location_id' => $sort_location_id, 'created_at >=' => $date_sort)); // This will restrict the orders to only current user.
                    $data['order'] = $this->Order_model->order_by('id', 'desc')->get_many_by(array('location_id' => $sort_location_id, 'created_at >=' => $date_sort));
                    $this->populateUserInfo($data['order']); //JM: 7/31/18
                    $data['orders_data'] = $this->Order_model->get_many_by(array('user_id' => $user_id, 'restricted_order' => '0', 'location_id' => $sort_location_id, 'created_at >=' => $date_sort));

                    $data['recurring_order'] = $this->Recurring_order_model->get_many_by(array('user_id' => $user_id, 'location_id' => $sort_location_id, 'created_at >=' => $date_sort));
                } else {
                    Debugger::debug('here');
                    // $data['order'] = $this->Order_model->order_by('id', 'desc')->get_many_by(array('user_id' => $user_id, 'location_id' => $location_id, 'created_at >=' => $date_sort)); // This will restrict the orders to only current user.
                    $data['order'] = $this->Order_model->order_by('id', 'desc')->get_many_by(array('created_at >=' => $date_sort));
                    $this->populateUserInfo($data['order']); //JM: 7/31/18
                    $data['orders_data'] = $this->Order_model->get_many_by(array('user_id' => $user_id, 'restricted_order' => '0', 'location_id' => $location_id, 'created_at >=' => $date_sort));
                    $data['recurring_order'] = $this->Recurring_order_model->get_many_by(array('user_id' => $user_id, 'location_id' => $location_id, 'created_at >=' => $date_sort));
                }
            }

            $data['returns'] = array();
            $data['recurrings'] = array();
            $data['reviews'] = array();
            $data['vendor_data'] = array();
            $data['restricted_orders'] = array();
            $data['return_orders'] = $this->Order_return_model->get_all();
            for ($i = 0; $i < count($data['order']); $i++) {
                $bFound = false;
                for ($j = 0; $j < count($data['return_orders']); $j++) {

                    if ($data['return_orders'][$j]->order_id == $data['order'][$i]->id) {

                        $query = 'SELECT a.id,b.id,b.total,c.name,a.return_status,a.updated_at,sum(d.quantity)as total_quantity FROM order_returns a LEFT JOIN orders b ON a.order_id=b.id LEFT JOIN vendors c ON b.vendor_id=c.id LEFT JOIN order_items d ON a.order_id=d.order_id where a.order_id=' . $data['return_orders'][$j]->order_id . '';
                        $data['returns'][] = $this->db->query($query)->result();

                        $bFound = true;
                    }
                }
                if (!$bFound) {
                    $data['orders'][] = $this->Order_model->get_by(array('id' => $data['order'][$i]->id));
                    for ($j = 0; $j < count($data['orders']); $j++) {
                        $data['vendors'] = $this->Vendor_model->get_by(array('id' => $data['orders'][$j]->vendor_id));
                        $data['orders'][$j]->vendor = $data['vendors'];
                    }
                }
            }
            $data['reviews'] = $this->Review_model->get_many_by(array('user_id' => $user_id, 'model_name' => 'vendor'));
            $unique_vendors = [];
            if ($data['orders_data'] != "") {
                for ($i = 0; $i < count($data['orders_data']); $i++) {
                    $bFounds = false;

                    for ($j = 0; $j < count($data['reviews']); $j++) {
                        if ($data['reviews'][$j]->model_id == $data['orders_data'][$i]->vendor_id) {
                            $bFounds = true;
                        }
                    }
                    if (!$bFounds) {
                        if (!(in_array($data['orders_data'][$i]->vendor_id, $unique_vendors))) {
                            $data['vendor_data'][] = $this->Vendor_model->get_by(array('id' => $data['orders_data'][$i]->vendor_id));
                            for ($j = 0; $j < count($data['vendor_data']); $j++) {
                                $images = $images = $this->Images_model->get_by(array('model_name' => 'vendor', 'model_id' => $data['vendor_data'][$j]->id));
                                $order = $this->Order_model->get_by(array('vendor_id' => $data['vendor_data'][$j]->id));
                                $data['vendor_data'][$j]->images = $images;
                                $data['vendor_data'][$j]->order = $order;
                            }
                            $unique_vendors[] = $data['orders_data'][$i]->vendor_id;
                        }
                    }
                }
            }
            for ($j = 0; $j < count($data['recurring_order']); $j++) {
                $query = 'SELECT a.id,b.total,c.name,d.photo,e.nickname,a.start_date,a.frequency,a.order_id FROM recurring_orders a
                      LEFT JOIN orders b ON a.order_id=b.id
                      LEFT JOIN vendors c ON b.vendor_id=c.id
                      LEFT JOIN images d ON c.id=d.model_id and d.model_name="vendor"
                      LEFT JOIN organization_locations e ON e.id=a.location_id
                      where a.order_id=' . $data['recurring_order'][$j]->order_id;
                $data['recurrings'][] = $this->db->query($query)->result();
            }

            // JM: 08/05/18 Adding User Location data to the context
            $data['user_locations'] = $user_locations;

            // Old user location population
            // $data['locations'] = $this->User_location_model->get_many_by(array('user_id' => $user_id));
            // for ($i = 0; $i < count($data['locations']); $i++) {
            //     $data['user_locations'][] = $this->Organization_location_model->get_many_by(array('id' => $data['locations'][$i]->organization_location_id));
            // }

            // echo '<pre>';
            // print_r($data);
            // echo '</pre>';
            // die('orderDetails');


          
            $this->load->view('/templates/_inc/header', $data);
            $this->load->view('/templates/account/orders/index', $data);
            $this->load->view('/templates/_inc/footer');
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function get_for_ordercancel() { //get order details to cancel this order
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $order_id = $this->input->post('order_id');
            $data['orders'] = $this->Order_model->get_by(array('id' => $order_id));
            $data['location'] = $this->Organization_location_model->get_by(array('id' => $data['orders']->location_id));
            $data['vendor'] = $this->Vendor_model->get_by(array('id' => $data['orders']->vendor_id));
            echo json_encode($data);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function get_pendingorders() { //get students restricted orders before approve
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $restricted_id = $this->input->post('restricted_id');
            $data['orders'] = $this->Order_model->get_by(array('id' => $restricted_id));
            $data['location'] = $this->Organization_location_model->get_by(array('id' => $data['orders']->location_id));
            $data['vendor'] = $this->Vendor_model->get_by(array('id' => $data['orders']->vendor_id));
            echo json_encode($data);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function process_recurring_orders() {
        $user_id = $_SESSION['user_id'];
        $user = $this->User_model->get_by('id', $user_id);
        $recurring_orders = $this->Recurring_order_model->get_all();
        if ($recurring_orders != null) {
            for ($i = 0; $i < count($recurring_orders); $i++) {
                $duration = 0;
                $type = 'day';
                switch ($recurring_orders[$i]->frequency) {
                    case 'Daily': $duration = 1;
                        $type = 'day';
                        break;
                    case 'Weekly': $duration = 7;
                        $type = 'day';
                        break;
                    case 'Bi-weekly': $duration = 14;
                        $type = 'day';
                        break;
                    case 'Monthly': $duration = 1;
                        $type = 'month';
                        break;
                    case 'Quarterly': $duration = 3;
                        $type = 'month';
                        break;
                    case 'Yearly': $duration = 1;
                        $type = 'year';
                        break;
                }
                $today = date('Y-m-d');
                $time = strtotime($recurring_orders[$i]->start_date);
                $final = date("Y-m-d", strtotime("+" . $duration . " " . $type));
                $subtotal = '0.00';
                $total = '0.00';
                $shipment_price = '0.00';
                if ($recurring_orders[$i]->start_date == $today) {
                    //Process Order
                    $location_id = $recurring_orders[$i]->location_id;
                    $location_inventory = $this->Location_inventories_model->get_by(array('location_id' => $location_id));
                    $locations = $this->Organization_location_model->get_by(array('id' => $location_id));
                    $address1 = $locations->address1;
                    $address2 = $locations->address2;
                    $city = $locations->city;
                    $state = $locations->state;
                    $zip = $locations->zip;
                    $nickname = $locations->nickname;
                    $description = 'Recurring order payment';
                    $tax = $this->Product_tax_model->get_by(array('ZipCode' => $zip));
                    if ($tax->EstimatedCombinedRate != null) {
                        $tax_rate = $tax->EstimatedCombinedRate;
                    } else {
                        $tax_rate = '0.00';
                    }
                    $orders = $this->Order_model->get_by(array('id' => $recurring_orders[$i]->order_id));
                    $vendor_id = $orders->vendor_id;
                    $payment = $this->User_payment_option_model->get_by(array('id' => $recurring_orders[$i]->payment_id));
                    $payment_id = $recurring_orders[$i]->payment_id;
                    $payment_token = $payment->token;
                    $shipment = $this->Shipping_options_model->get_by(array('id' => $orders->shipment_id));
                    $shipment_price = $shipment->shipping_price;
                    $shipment_id = $shipment->id;
                    $recurring_items = $this->Recurring_order_item_model->get_many_by(array('recurring_id' => $recurring_orders[$i]->id));
                    for ($j = 0; $j < count($recurring_items); $j++) {
                        $product_id = $recurring_items[$j]->product_id;
                        $quantity = $recurring_items[$j]->quantity;
                        $pricings = $this->Product_pricing_model->get_by(array('product_id' => $product_id, 'vendor_id' => $vendor_id));
                        $subtotal = $quantity * ($pricings->price);
                    }
                    $total = $subtotal + $total + $tax_rate + $shipment_price;
                    //orders
                    $insert_data = array(
                        'order_status' => 'New',
                        'location_id' => $location_id,
                        'total' => $total,
                        'tax' => $tax_rate,
                        'shipping_price' => $shipment_price,
                        'promo_discount' => '',
                        'promocode_id' => '',
                        'user_id' => $recurring_orders[$i]->user_id,
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
                    //payment
                    try {
                        $payment_cost = round($total * 100);

                        $payment_data = [
                            'amount' => $payment_cost,
                            'description' => $description
                        ];

                        $vendor = $this->Vendor_model->get($vendor_id);

                        $matixVendorFlag = ($vendor->vendor_type == '0') ? FALSE : TRUE;
                        $independentVendorFlag = ($vendor->vendor_type == '0') ? TRUE : FALSE;
                        $chargeOptions = NULL;

                        if ($vendor->payment_id != NULL && $vendor->payment_id != "") {
                            if ($independentVendorFlag){
                                $vendor_token = $this->stripe->createToken(
                                    ["customer" => $user->stripe_id],
                                    ["stripe_account" => $vendor->payment_id]
                                );
                                $payment_data['source'] = $vendor_token->id;
                                $chargeOptions = ['stripe_account' => $vendor->payment_id];
                            }else{
                                $payment_data['customer'] = $payment_token;
                                $payment_data['destination'] = $vendor->payment_id;

                                if ($matixVendorFlag){
                                    $payment_data['application_fee'] = round($payment_cost * 0.07);
                                }
                            }
                        }

                        $output = $this->stripe->addCharge($payment_data, $chargeOptions);
                        $this->Order_model->insert($insert_data);
                        $insert_id = $this->db->insert_id();
                        //order items
                        $new_qty = 0;
                        for ($j = 0; $j < count($recurring_items); $j++) {
                            $product_id = $recurring_items[$j]->product_id;
                            $quantity = $recurring_items[$j]->quantity;
                            $pricings = $this->Product_pricing_model->get_by(array('product_id' => $product_id, 'vendor_id' => $vendor_id));
                            $subtotal = $quantity * ($pricings->price);
                            $cart_data = array(
                                'order_id' => $insert_id,
                                'item_status' => '0',
                                'shipment_id' => $shipment_id,
                                'shipping_price' => $shipment_price,
                                'product_id' => $product_id,
                                'vendor_id' => $vendor_id,
                                'price' => $pricings->price,
                                'quantity' => $quantity,
                                'picked' => $quantity,
                                'total' => $subtotal,
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s')
                            );
                            $this->Order_items_model->insert($cart_data);
                        }
                        $this->Recurring_order_model->update($recurring_orders[$i]->id, array('start_date' => $final));
                    } catch (Exception $e) {
                        die('Invalid Credentials');
                    }
                }
                $user_id = $recurring_orders[$i]->user;
                $user = $this->User_model->get_by(array('id' => $user_id));
                $email = $user->email;
                if ($insert_id != "" || $insert_id != null) {
                    $order_id = $insert_id;
                    $data['order_details'] = $this->Order_items_model->get_many_by(array('order_id' => $order_id));
                    $data['orders'] = $this->Order_model->get_by(array('id' => $order_id));
                    $location_id = $data['orders']->location_id;
                    $user_payment = $this->User_payment_option_model->get_by(array('id' => $data['orders']->payment_id));
                    $owner = $this->User_model->get_by(array('id' => $user_payment->user_id));
                    $customer = $this->stripe->getCustomer($owner->stripe_id);
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
                    $data['payments'] = $users_payment_obj;
                    unset($users_payment_obj);

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

                    if ($_SESSION['user_id'] != null) {
                        $subject = 'Recurring Order Confirmation';
                        $data['message'] = "Hi,<br /> Thank you for using Matixdental. Your shipping information is given below <br/>";
                        $body = $this->load->view('/templates/email/order/index.php', $data, TRUE);
                        $mail_status = send_matix_email($body, $subject, $email);
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
        }
    }

    //cancen orders
    public function cancel_order() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $order_id = $this->input->post('order_id');
            $order_items = $this->Order_items_model->get_many_by(array('order_id' => $order_id));
            $cancel_orders = array(
                'order_status' => '5',
                'updated_at' => date('Y-m-d H:i:s')
            );
            $this->Order_model->update($order_id, $cancel_orders);
            $return_orders = array(
                'order_id' => $order_id,
                'reason' => 'cancell order',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            );
            $this->Order_return_model->insert($return_orders);
            $insert_id = $this->db->insert_id();
            for ($i = 0; $i < count($order_items); $i++) {
                $insert_data = array(
                    'order_return_id' => $insert_id,
                    'order_item_id' => $order_items[$i]->id,
                    'quantity' => $order_items[$i]->quantity,
                    'status' => '0',
                    'action' => '0',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                $this->Order_item_return_model->insert($insert_data);
            }
            header("Location:history");
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    //student cancel restricted Orders
    public function cancel_restricted_orders() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $order_id = $this->input->post('restricted_id');
            $order_items = $this->Order_items_model->get_many_by(array('order_id' => $order_id));
            $cancel_order = array(
                'order_status' => '5',
                'updated_at' => date('Y-m-d H:i:s')
            );
            $this->Order_model->update($order_id, $cancel_order);
            for ($i = 0; $i < count($order_items); $i++) {
                $update_id = $order_items[$i]->id;
                $cancel_orders = array(
                    'item_status' => '1',
                    'updated_at' => date('Y-m-d H:i:s')
                );
                $this->Order_items_model->update($update_id, $cancel_orders);
            }
            header("Location:history");
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function recurring_order() { //insert recuuring order details
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $order_id = $this->input->post('order_id');
            $recurring = $this->input->post('recurring');
            $recurring_date = date("Y-m-d", strtotime($this->input->post('recurring_date')));
            $orders = $this->Order_model->get_by(array('id' => $order_id));
            $location_id = $orders->location_id;
            $payment_id = $orders->payment_id;
            $insert_data = array(
                'order_id' => $order_id,
                'user_id' => $user_id,
                'location_id' => $location_id,
                'payment_id' => $payment_id,
                'start_date' => $recurring_date,
                'frequency' => $recurring,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            );
            $this->Recurring_order_model->insert($insert_data);
            $insert_id = $this->db->insert_id();
            $data['order_items'] = $this->Order_items_model->get_many_by(array('order_id' => $order_id));
            for ($i = 0; $i < count($data['order_items']); $i++) {
                $product_id = $data['order_items'][$i]->product_id;
                $quantity = $data['order_items'][$i]->quantity;
                $items_data = array(
                    'recurring_id' => $insert_id,
                    'product_id' => $product_id,
                    'quantity' => $quantity,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                );

                $this->Recurring_order_item_model->insert($items_data);
            }
            header("Location:history");
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function view_recurring_orders() { //view recuuring order item details
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];

            $recurring_id = $this->input->get('id');
            $data['recurring_order'] = $this->Recurring_order_model->get_by(array('id' => $recurring_id, 'user_id' => $user_id));
            if (!isset($data['recurring_order'])) {
                $this->session->set_flashdata('error', 'Invalid Entry');
                header("Location: home");
            } else {
                $order_id = $data['recurring_order']->order_id;
                $data['order_details'] = $this->Order_items_model->get_many_by(array('order_id' => $order_id));
                $data['orders'] = $this->Order_model->get_by(array('id' => $order_id));
                $location_id = $data['recurring_order']->location_id;

                $user_payment = $this->User_payment_option_model->get_by(array('id' => $data['orders']->payment_id));
                $owner = $this->User_model->get_by(array('id' => $user_payment->user_id));
                $customer = $this->stripe->getCustomer($owner->stripe_id);
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
                $data['payments'] = $users_payment_obj;
                unset($users_payment_obj);

                $data['shipping_address'] = $this->Organization_location_model->get_by(array('id' => $location_id));
                $data['shippment'] = $this->Shipping_options_model->get_by(array('id' => $data['orders']->shipment_id));
                $data['vendor_image'] = $this->Images_model->get_by(array('model_id' => $data['orders']->vendor_id, 'model_name' => 'vendor'));
                $data['vendor_details'] = $this->Vendor_model->get_by(array('id' => $data['orders']->vendor_id));

                for ($i = 0; $i < count($data['order_details']); $i++) {

                    $product_image = $this->Images_model->get_by(array('model_id' => $data['order_details'][$i]->product_id, 'model_name' => 'products', 'image_type' => 'mainimg'));
                    $product_pricing = $this->Product_pricing_model->get_by(array('product_id' => $data['order_details'][$i]->product_id, 'vendor_id' => $data['order_details'][$i]->vendor_id));
                    $data['products'] = $this->Products_model->get_by(array('id' => $data['order_details'][$i]->product_id));
                    $vendors = $this->Vendor_model->get_by(array('id' => $data['order_details'][$i]->vendor_id));
                    $data['order_details'][$i]->product_image = $product_image;
                    $data['order_details'][$i]->Product_details = $product_pricing;
                    $data['order_details'][$i]->product = $data['products'];
                    $data['order_details'][$i]->vendor = $vendors;
                }
            }
            $this->load->view('/templates/_inc/header');
            $this->load->view('/templates/account/orders/r/number/index', $data);
            $this->load->view('/templates/_inc/footer');
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    //update recurring frequency
    public function update_frequency() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $frequency = $this->input->post('frequency');
            $recurring_id = $this->input->post('recurring_id');

            $this->Recurring_order_model->update($recurring_id, array('frequency' => $frequency, 'updated_at' => date('Y-m-d H:i:s')));
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function delete_recuring() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $delete_id = $this->input->post('recurring_id');
            $this->Recurring_order_model->delete($delete_id);
            header("Location:recurring");
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function view_orders() { //view user order details
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $user = $this->User_model->get_by(array('id' => $user_id));

            $order_id = $this->input->get('id');
            $data['orders'] = $this->Order_model->get_by(array('id' => $order_id));
            if (!isset($data['orders'])) {
                $this->session->set_flashdata('error', 'Invalid Entry');
                header('Location: home');
            } else {
                $data['order_details'] = $this->Order_items_model->get_many_by(array('order_id' => $order_id));
                $location_id = $data['orders']->location_id;

                $user_payment = $this->User_payment_option_model->get_by(['id' => $data['orders']->payment_id]);
                $owner = $this->User_model->get_by(['id' => $user_payment->user_id]);

                /* JM: 8/14/18
                ** Adding conditional to prevent Stripe from erroring with NULL.
                */
                $customer = (isset($owner->stripe_id)) ? $this->stripe->getCustomer($owner->stripe_id) : NULL;


                // Payment method
                /* JM: 8/14/18
                ** Adding conditional to prevent Stripe from erroring with NULL.
                */
                $payment_method = (isset($user_payment->token)) ? $customer->sources->retrieve($user_payment->token) : NULL;
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
                $data['payments'] = $users_payment_obj;
                unset($users_payment_obj);

                $data['shipping_address'] = $this->Organization_location_model->get_by(array('id' => $location_id));
                $data['shippment'] = $this->Shipping_options_model->get_by(array('id' => $data['orders']->shipment_id));
                $data['vendor_image'] = $this->Images_model->get_by(array('model_id' => $data['orders']->vendor_id, 'model_name' => 'vendor'));
                for ($i = 0; $i < count($data['order_details']); $i++) {
                    $data['vendor_details'] = $this->Vendor_model->get_by(array('id' => $data['order_details'][$i]->vendor_id));
                    $product_image = $this->Images_model->get_by(array('model_id' => $data['order_details'][$i]->product_id, 'model_name' => 'products', 'image_type' => 'mainimg'));
                    $product_pricing = $this->Product_pricing_model->get_by(array('product_id' => $data['order_details'][$i]->product_id, 'vendor_id' => $data['order_details'][$i]->vendor_id));
                    $data['products'] = $this->Products_model->get_by(array('id' => $data['order_details'][$i]->product_id));
                    $vendors = $this->Vendor_model->get_by(array('id' => $data['order_details'][$i]->vendor_id));
                    $data['order_details'][$i]->product_image = $product_image;
                    $data['order_details'][$i]->Product_details = $product_pricing;
                    $data['order_details'][$i]->product = $data['products'];
                    $data['order_details'][$i]->vendor = $vendors;
                }
                $data['promos'] = $this->Order_promotion_model->get_many_by(array('order_id' => $order_id));
                for ($i = 0; $i < count($data['promos']); $i++) {
                    $promocode = $this->Promo_codes_model->get_by(array('id' => $data['promos'][$i]->promo_id));
                    $data['promos'][$i]->promocode = $promocode;
                }
                $query = "SELECT b.id,b.discount_value,c.code,c.manufacturer_coupon,c.conditions  FROM orders a INNER JOIN order_promotions b on b.order_id=a.id INNER JOIN promo_codes c on c.id=b.promo_id WHERE a.id=$order_id";
                $data['allpromotions'] = $this->db->query($query)->result();
            }


            $organizationId = $this->Organization_groups_model->get_by(['user_id' => $user_id])->organization_id;
            $data['previousOrders'] = $this->Order_model->loadOrders($organizationId, 187);


            if (!empty($data['orders']) && isset($data['orders']->state)) {
                $state = $data['orders']->state;

                $user_license = $this->User_licenses_model->get_by([
                    'state' => $state,
                    'user_id' => $user_id,
                    'approved' => '1'
                ]);

                if (empty($user_license)) {
                            $this->session->set_flashdata('error', 'The products you are trying to purchase from ' . $data['vendor_details']->name . ' require a license for ' . $data['orders']->state . '. Please add a valid license for ' . $data['orders']->state . ' <button class="btn btn--s btn--primary modal--toggle" style="border-radius: 4px; padding: 6px 12px;" data-target="#addNewLicenseModal">Add License</button>');

                }
            }

            $this->load->view('/templates/_inc/header');
            $this->load->view('/templates/account/orders/o/number/index', $data);
            $this->load->view('/templates/_inc/footer');
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function pending_orders() { //students view their Restricted orders details
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $order_id = $this->input->get('id');
            $data['order_details'] = $this->Order_items_model->get_many_by(array('order_id' => $order_id));
            $data['orders'] = $this->Order_model->get_by(array('id' => $order_id));
            $location_id = $data['orders']->location_id;

            $user_payment = $this->User_payment_option_model->get_by(array('id' => $data['orders']->payment_id));
            $owner = $this->User_model->get_by(array('id' => $user_payment->user_id));
            $customer = $this->stripe->getCustomer($owner->stripe_id);
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
            $data['payments'] = $users_payment_obj;
            unset($users_payment_obj);

            $data['shipping_address'] = $this->Organization_location_model->get_by(array('id' => $location_id));
            $data['shippment'] = $this->Shipping_options_model->get_by(array('id' => $data['orders']->shipment_id));
            $data['vendor_image'] = $this->Images_model->get_by(array('model_id' => $data['orders']->vendor_id, 'model_name' => 'vendor'));
            for ($i = 0; $i < count($data['order_details']); $i++) {
                $data['vendor_details'] = $this->Vendor_model->get_by(array('id' => $data['order_details'][$i]->vendor_id));
                $product_image = $this->Images_model->get_by(array('model_id' => $data['order_details'][$i]->product_id, 'model_name' => 'products', 'image_type' => 'mainimg'));
                $product_pricing = $this->Product_pricing_model->get_by(array('product_id' => $data['order_details'][$i]->product_id, 'vendor_id' => $data['order_details'][$i]->vendor_id));
                $data['products'] = $this->Products_model->get_by(array('id' => $data['order_details'][$i]->product_id));
                $vendors = $this->Vendor_model->get_by(array('id' => $data['order_details'][$i]->vendor_id));
                $data['order_details'][$i]->product_image = $product_image;
                $data['order_details'][$i]->Product_details = $product_pricing;
                $data['order_details'][$i]->product = $data['products'];
                $data['order_details'][$i]->vendor = $vendors;
            }
            $data['promos'] = $this->Order_promotion_model->get_many_by(array('order_id' => $order_id));
            for ($i = 0; $i < count($data['promos']); $i++) {
                $promocode = $this->Promo_codes_model->get_by(array('id' => $data['promos'][$i]->promo_id));
                $data['promos'][$i]->promocode = $promocode;
            }
            $query = "SELECT b.id,b.discount_value,c.code,c.manufacturer_coupon,c.conditions  FROM orders a INNER JOIN order_promotions b on b.order_id=a.id INNER JOIN promo_codes c on c.id=b.promo_id WHERE a.id=$order_id";
            $data['allpromotions'] = $this->db->query($query)->result();
            $this->load->view('/templates/_inc/header');
            $this->load->view('/templates/account/orders/o/pending/index', $data);
            $this->load->view('/templates/_inc/footer');
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    //remove vendors from vendor feedback.....
    public function reject_vendor() {
        if (isset($_SESSION["user_id"])) {
            $vendor_id = $this->input->post('vendor_id');
            $user_id = $_SESSION['user_id'];
            $insert_data = array(
                'user_id' => $user_id,
                'model_name' => 'vendor',
                'model_id' => $vendor_id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $this->Review_model->insert($insert_data);
        }
    }

    public function get_order_items() { //get oreder items to return
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $order_id = $this->input->post('order_id');
            $data['order_items'] = $this->Order_items_model->get_many_by(array('order_id' => $order_id));
            for ($i = 0; $i < count($data['order_items']); $i++) {
                $products = $this->Products_model->get_by(array('id' => $data['order_items'][$i]->product_id));
                $images = $this->Images_model->get_by(array('model_id' => $data['order_items'][$i]->product_id, 'model_name' => 'products'));
                $pricing = $this->Product_pricing_model->get_by(array('product_id' => $data['order_items'][$i]->product_id, 'vendor_id' => $data['order_items'][$i]->vendor_id));
                $data['order_items'][$i]->pricing = $pricing;
                $data['order_items'][$i]->products = $products;
                $data['order_items'][$i]->images = $images;
            }
            echo json_encode($data);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function return_orders() { //get return order deatails
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $order_id = $this->input->post('order_id');
            $reason = $this->input->post('reason');
            $insert_return_orders = array(
                'order_id' => $order_id,
                'reason' => $reason,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            );
            $this->Order_return_model->insert($insert_return_orders);
            $insert_id = $this->db->insert_id();
            $data['order_items'] = $this->Order_items_model->get_many_by(array('order_id' => $order_id));
            for ($i = 0; $i < count($data['order_items']); $i++) {
                $quantity = $data['order_items'][$i]->quantity;
                $order_items_id = $data['order_items'][$i]->id;
                $insert_data = array(
                    'order_return_id' => $insert_id,
                    'order_item_id' => $order_items_id,
                    'quantity' => $quantity,
                    'status' => '0',
                    'action' => '0',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                );
                $this->Order_item_return_model->insert($insert_data);
            }
            $data['return_id'] = $insert_id;
            $orders = $this->Order_model->get_by(array('id' => $order_id));
            $data['vendors'] = $this->Vendor_model->get_by(array('id' => $orders->vendor_id));
            $data['location'] = $this->Organization_location_model->get_by(array('id' => $orders->location_id));
            echo json_encode($data);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function addshippingLocation() { //change recuuring orders shipping locations
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $data['organ_id'] = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
            $organization_id = $data['organ_id']->organization_id;
            $location = $this->input->post('location');
            $recurring_id = $this->input->post('recurring_id');
            if ($location == '1') {
                $nickname = $this->input->post('locationNickname');
                $address1 = $this->input->post('locationAddress1');
                $address2 = $this->input->post('locationAddress2');
                $state = $this->input->post('state');
                $zip = $this->input->post('locationZip');
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

                        $this->Recurring_order_model->update($recurring_id, array('location_id' => $insert_id, 'updated_at' => date('Y-m-d H:i:s')));
                    }
                }
            }
            header("Location: view-recurring-orders?id=" . $recurring_id);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function search_orders() { //search orders from dash board
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $search = $this->input->post('search');
            if ($search != null) {
                $input_date = date('Y-m-d', strtotime($search));
                $query = "select o.* from orders o, order_items oi, products p where o.id=oi.order_id and oi.product_id=p.id and (o.id like '%$search%' or p.name like '%$search%' or (o.created_at >='$input_date 00:00:00' and o.created_at <='$input_date 23:59:59')) and user_id = '$user_id' group by o.id";
                $data['orders'] = $this->db->query($query)->result();
                for ($i = 0; $i < count($data['orders']); $i++) {
                    $location = $this->Organization_location_model->get_by(array('id' => $data['orders'][$i]->location_id));
                    $vendors = $this->Vendor_model->get_by(array('id' => $data['orders'][$i]->vendor_id));
                    $data['orders'][$i]->location = $location;
                    $data['orders'][$i]->vendors = $vendors;
                }
                $this->load->view('/templates/_inc/header');
                $this->load->view('/templates/account/orders/search/index', $data);
                $this->load->view('/templates/_inc/footer');
            } else {
                header("Location:dashboard");
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

}

<?php

/*
 * 1. Working on Vendor Product Price Section .
 */

//  NOTES:  SELECT * FROM `product_pricings` where vendor_id=1 order by  price desc ;
class VendorProductAction extends MW_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Review_model');
        $this->load->model('Role_model');
        $this->load->model('Vendor_model');
        $this->load->model('Vendor_groups_model');
        $this->load->model('Products_model');
        $this->load->model('Product_pricing_model');
        $this->load->model('Promo_codes_model');
        $this->load->model('Order_promotion_model');
        $this->load->model('Shipping_options_model');
        $this->load->model('Order_model');
        $this->load->model('Organization_model');
        $this->load->model('Order_items_model');
        $this->load->model('Organization_groups_model');
        $this->load->model('Order_tracking');
        $this->load->model('Order_items_model');
        $this->load->model('User_location_model');
        $this->load->model('User_licenses_model');
        $this->load->model('User_payment_option_model');
        $this->load->model('Organization_location_model');
        $this->load->model('User_vendor_notes_model');
        $this->load->model('Order_promotion_model');
        $this->load->model('Vendor_order_notes_model');
        $this->load->model('Vendor_order_activities_model');
        $this->load->model('Order_item_return_model');
        $this->load->model('Location_inventories_model');
        $this->load->helper('MY_privilege_helper');
        $this->load->helper('MY_support_helper');
        $this->load->helper('my_email_helper');
        $this->load->library('email');
        $this->load->library('stripe');
    }

    /*
     *      Vendor Dashboard.
     *      To activate/Deactivate products.
     */

    public function productPrice_status()
    {
        $admin_roles = unserialize(ROLES_VENDORS);
        Debugger::debug('activating products');
        Debugger::debug($_POST);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {

            $productPricingIds = explode(',', $this->input->post('productPr_id'));

            foreach($productPricingIds as $k => $productPricingId){
                $this->Product_pricing_model->toggleProductPricingActive($productPricingId, $this->input->post('select'));
            }

            header('Location: vendor-products-dashboard?siteSelect=' . $this->input->post('site_id'));
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *      Vendor Dashboard.
     *      To show/hide products.
     */

    public function toggleProductDisplay()
    {
        $admin_roles = unserialize(ROLES_VENDORS);
        Debugger::debug('toggling display');
        Debugger::debug($_POST);

        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {

            $productIds = explode(',', $this->input->post('product_id'));

            foreach($productIds as $k => $productId){
                $this->Product_pricing_model->toggleProductDisplay($productId, $this->input->post('vendor_id'), $this->input->post('site_id'));
            }

            $this->Product_pricing_model->cleanProductDisplay();

            header('Location: vendor-products-dashboard?siteSelect=' . $this->input->post('site_id'));
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function productPrice_selection() {
        $vendor_id = $this->input->post('vendor_id');
        $status = $this->input->post('status');

        $sql = "SELECT pp.id,pp.vendor_product_id,pp.vendor_id,pp.price,pp.active as status,p.name,p.item_code,pc.active
                FROM products as p
                LEFT JOIN product_pricings as pp ON p.id=pp.product_id
                LEFT JOIN promo_codes as pc ON p.id = pc.product_id and pp.vendor_id = pc.vendor_id
                WHERE pp.vendor_idt = " . $vendor_id;

        switch ($status) {
            case 0:
                $sql .= " ORDER BY p.name asc";
                break;
            case 1:
                $sql .= "ORDER BY pp.price asc";
                break;
            case 2:
                $sql .=" ORDER BY pp.price desc";
                break;
        }

                $data['vendor_products'] = $this->db->query($query)->result();

        echo json_encode($data['vendor_products']);
    }

    /*
     *  To Delete the PromoCodes from VENDOR    DashBoard
     *          (i)   promoProduct_delete()   for Product Promo
     *          (ii)  promoforAll_delete()    PromoCode for All the Product
     */

    public function promoProduct_delete() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $delete_id = explode(",", $this->input->post('promo_id'));
            $this->Promo_codes_model->delete_many($delete_id);
            $this->session->set_flashdata('success', 'Promo code(s) Deleted successfully..');
            header("Location: view-promo-product");
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *      Vendor Dashboard
     *      @Promos
     *          1. Delete all promo code with promo id.
     */

    public function promoforAll_delete() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $delete_id = explode(",", $this->input->post('promo_id'));
            $this->Promo_codes_model->delete_many($delete_id);
            $this->session->set_flashdata('success', 'Promo code(s) Deleted successfully..');
            header("Location: vendor-dashboard");
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *      Vendor Dashboard
     *          @Orders
     *              1.Vendor orders will be shown here, with urgent orders and normal orders
     */

    public function vendor_orders() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $user_id = $_SESSION['user_id'];
            $vendor_detail = $this->Vendor_groups_model->get_by(array('user_id' => $user_id));
            $vendor_id = $vendor_detail->vendor_id;
            if ($vendor_id != null) {
                $search = $this->input->post('search');
                if ($search != null) {
                    $input_date = date('Y-m-d', strtotime($search));
                    $query = "SELECT o.id,o.total,o.order_status,q.first_name,o.created_at,p.delivery_time FROM orders o LEFT JOIN shipping_options p on o.shipment_id = p.id  LEFT JOIN users q on o.user_id = q.id where (o.id like '%$search%' or q.first_name like '%$search%' or (o.created_at >='$input_date 00:00:00' and o.created_at <='$input_date 23:59:59')) and o.vendor_id=$vendor_id  and o.restricted_order='0' ";
                    $data['orders_received'] = $this->db->query($query)->result();
                } else {
                    $query = "SELECT o.id,o.total,o.order_status,q.first_name,o.created_at,p.delivery_time FROM orders o LEFT JOIN shipping_options p on o.shipment_id = p.id LEFT JOIN users q on o.user_id = q.id where o.vendor_id=$vendor_id and o.order_status !='Cancelled' and o.order_status !='Shipped' and o.order_status !='Delivered' and o.restricted_order='0' group by o.id";
                    $data['orders_received'] = $this->db->query($query)->result();
                }

                if ($data['orders_received'] != null) {
                    $date = date("Y-m-d");
                    for ($i = 0; $i < count($data['orders_received']); $i++) {
                        $data['orders_received'][$i]->created_at = date('M d, Y', strtotime($data['orders_received'][$i]->created_at));
                        switch ($data['orders_received'][$i]->delivery_time) {
                            case "Same Day":
                                $data['orders_received'][$i]->delivery_time = date('M d, Y', strtotime($data['orders_received'][$i]->created_at));
                                break;
                            case "Next Business Day":
                                $data['orders_received'][$i]->delivery_time = date('M d, Y', strtotime($data['orders_received'][$i]->created_at . ' +1 Weekday'));
                                break;
                            case "2 Business Days":
                                $data['orders_received'][$i]->delivery_time = date('M d, Y', strtotime($data['orders_received'][$i]->created_at . ' +2 Weekday'));
                                break;
                            case "3 Business Days":
                                $data['orders_received'][$i]->delivery_time = date('M d, Y', strtotime($data['orders_received'][$i]->created_at . ' +3 Weekday'));
                                break;
                            case "1-5 Business Days":
                                $data['orders_received'][$i]->delivery_time = date('M d, Y', strtotime($data['orders_received'][$i]->created_at . ' +5 Weekday'));
                                break;
                            case "7-10 Business Days":
                                $data['orders_received'][$i]->delivery_time = date('M d, Y', strtotime($data['orders_received'][$i]->created_at . ' +10 Weekday'));
                                break;
                        }
                    }
                }
            }
            $data['My_vendor_users'] = "";
            $data['vendor_shipping'] = "";
            $data['selection'] = 1;
            $data['promoCodes_active'] = "";
            $data['NorderCount'] = order_count(); // To show the Latest Order Count.
            $data['ReturnCount'] = return_count();
            $this->load->view('/templates/_inc/header-vendor.php');
            $this->load->view('/templates/vendor-admin/orders/index.php', $data);
            $this->load->view('/templates/_inc/footer-vendor.php');

        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *  Vendor Dashboard
     *      @Orders->single Order
     */

    public function single_order_details() {
        /*
         * @todo: Get answers to:
         *  - How does line 236 affect to line 225
         *  - We're using shipping method to get the delivery time dynamically meaning if its 2 days delivery time
         * will always be 2 days from now
         *
         * */
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
           
            $order_id = $this->input->get('order_id');
            $vendor_id = $_SESSION['vendor_id'];
            if ($order_id != null) {
                //  Shipping Methods
                $data['order_details'] = "";  // To Validate Order_id
                $data['order_details'] = $this->Order_model->get_by(array('id' => $order_id, 'vendor_id' => $vendor_id));
                if (!isset($data['order_details'])) {
                    $this->session->set_flashdata('error', 'Not Valid Entry');
                    header('Location: vendor-dashboard');
                } else {
                    $data['user_details'] = $this->User_model->get($data['order_details']->user_id);
                    // get organization details
                    $sql = "SELECT o.*
                            FROM organizations AS o
                            JOIN organization_groups AS og
                                ON o.id = og.organization_id
                            WHERE user_id = " . $data['order_details']->user_id;

                    $data['user_details']->organization = $this->db->query($sql)->result()[0];

                    /*
                     *      Vendor_id was getting from Orders Table because it will be Viewed by Two Actors
                     *      1.Vendor(Actor)
                     *      2.SuperAdmin/Admin(Actor)
                     */
                    $order_user_id = $data['order_details']->user_id;
                    $user = $this->User_model->get_by(array('id' => $order_user_id));
                    $data['user_license'] = $this->User_licenses_model->get_by(array('state' => $data['order_details']->state, 'user_id' => $user->id, 'approved' => '1'));

                    $vendor_id = $data['order_details']->vendor_id;
                    $data['order_details']->card_number = "";
                    $data['order_details']->ba_account_number = "";
                    $data['order_details']->payment_type = "";
                    $data['order_details']->shipping_method = "";
                    $data['order_details']->delivery_time = "";
                    $shippping_method = $this->Shipping_options_model->get_by(array('id' => $data['order_details']->shipment_id));
                    $payment_method = $this->User_payment_option_model->get($data['order_details']->payment_id);
                    if ($shippping_method != null) {
                        $data['order_details']->shipping_method = $shippping_method->shipping_type;
                        switch ($shippping_method->delivery_time) {
                            case "Same Day":
                                $data['order_details']->delivery_time = date('M d, Y', strtotime($data['order_details']->created_at));
                                break;
                            case "Next Business Day":
                                $data['order_details']->delivery_time = date('M d, Y', strtotime($data['order_details']->created_at . ' +1 Weekday'));
                                break;
                            case "2 Business Days":
                                $data['order_details']->delivery_time = date('M d, Y', strtotime($data['order_details']->created_at . ' +2 Weekday'));
                                break;
                            case "3 Business Days":
                                $data['order_details']->delivery_time = date('M d, Y', strtotime($data['order_details']->created_at . ' +3 Weekday'));
                                break;
                            case "1-5 Business Days":
                                $data['order_details']->delivery_time = date('M d, Y', strtotime($data['order_details']->created_at . ' +5 Weekday'));
                                break;
                            case "1-5 Business Days":
                                $data['order_details']->delivery_time = date('M d, Y', strtotime($data['order_details']->created_at . ' +5 Weekday'));
                                break;
                            case "7-10 Business Days":
                                $data['order_details']->delivery_time = date('M d, Y', strtotime($data['order_details']->created_at . ' +10 Weekday'));
                                break;
                        }
                        $date = date("Y-m-d");
                        if (date('Y-m-d', strtotime($data['order_details']->delivery_time)) == $date) {
                            $data['order_details']->delivery_time = "Today";
                        }
                    }

                    if ($payment_method != null) {
                        $cardUser = $this->User_model->get_by(array('id' => $payment_method->user_id));
                        $customer = $this->stripe->getCustomer($cardUser->stripe_id);
                        // Payment method
                        $payment_method = $customer->sources->retrieve($payment_method->token);


                        $data['order_details']->payment_type = $payment_method->object;
                        $data['order_details']->card_type = $payment_method->brand;
                        $data['order_details']->exp_month = $payment_method->exp_month;
                        $data['order_details']->exp_year = $payment_method->exp_year;
                        $data['order_details']->cc_number  = $payment_method->last4;
                        $data['order_details']->cc_name = $payment_method->name;
                        $data['order_details']->bank_name = $payment_method->bank_name;
                    }


                    //  Order Address
                    $data['order_address'] = $this->Organization_location_model->get($data['order_details']->location_id);

                    $data['promos'] = $this->Order_promotion_model->get_many_by(array('order_id' => $order_id, 'restricted_order' => '0'));
                    for ($i = 0; $i < count($data['promos']); $i++) {
                        $promocode = $this->Promo_codes_model->get_by(array('id' => $data['promos'][$i]->promo_id));
                        $data['promos'][$i]->promocode = $promocode;
                    }
                    $query = "SELECT a.id,b.id as orderItem_id,b.promo_code_id,c.title,e.name,e.mpn,d.price,d.retail_price,d.vendor_product_id,b.price as product_order_price,b.picked,b.quantity FROM orders a  inner join order_items b on b.order_id=a.id and a.restricted_order='0' left join promo_codes c on c.id=b.promo_code_id INNER JOIN product_pricings d on d.product_id=b.product_id INNER JOIN products e on e.id=b.product_id WHERE a.id=$order_id and a.vendor_id=$vendor_id group by b.id";
                    $data['purchased_product'] = $this->db->query($query)->result();


                    // Calculation Section
                    $query = "SELECT a.id,b.id as order_id,b.shipping_price,b.tax,sum(a.total) as total ,a.quantity FROM order_items a INNER JOIN orders b on a.order_id=b.id and b.restricted_order='0' INNER JOIN shipping_options c on b.shipment_id=c.id  where a.order_id=$order_id and a.vendor_id=$vendor_id";
                    $data['calculation_section'] = $this->db->query($query)->result();
                    if ($data['calculation_section'] != null) {
                        $data['grand_total'] = 00000000000000;

                        for ($i = 0; $i < count($data['calculation_section']); $i++) {
                            $shipping_price = $data['calculation_section'][$i]->shipping_price;
                            $data['grand_total'] = $data['calculation_section'][$i]->total;
                        }
                        //  PROMOTIONs View
                        $query = "SELECT b.id,b.discount_value,c.code,c.manufacturer_coupon,c.conditions  FROM orders a INNER JOIN order_promotions b on b.order_id=a.id INNER JOIN promo_codes c on c.id=b.promo_id WHERE a.id=$order_id and a.restricted_order='0'";
                        $data['allpromotions'] = $this->db->query($query)->result();
                    }

                    //      VENDOR NOTES

                    $data['vendor_message'] = $this->Vendor_order_notes_model->get_many_by(array('order_id' => $order_id));
                    if ($data['vendor_message'] != null) {
                        for ($i = 0; $i < count($data['vendor_message']); $i++) {
                            $data['vendor_message'][$i]->user_name = "";
                            $data['vendor_message'][$i]->model_name = "";
                            $data['vendor_message'][$i]->model_photo = "";
                            $data['vendor_details'] = $this->User_model->get($data['vendor_message'][$i]->vendor_user_id);
                            $data['user_image'] = $this->Images_model->get_by(array('model_name' => 'user', 'model_id' => $data['vendor_message'][$i]->vendor_user_id));
                            if ($data['vendor_details'] != null) {
                                $data['vendor_message'][$i]->user_name = $data['vendor_details']->first_name;
                            }
                            if ($data['user_image'] != null) {
                                $data['vendor_message'][$i]->model_name = $data['user_image']->model_name;
                                $data['vendor_message'][$i]->model_photo = $data['user_image']->photo;
                            }
                        }
                    }
                    //  Response from Vendor.
                    $created_at = date('Y-m-d', strtotime("-1 month"));
                    $data['vendor_notes'] = $this->Vendor_order_activities_model->limit(5)->order_by('created_at', 'desc')->get_many_by(array('order_id' => $order_id, 'created_at > ' => $created_at));
                    for ($i = 0; $i < count($data['vendor_notes']); $i++) {
                        $data['vendor_notes'][$i]->created_atUpdate = "";
                        $c_date = strtotime("now");
                        $password_changed = strtotime($data['vendor_notes'][$i]->created_at);
                        if ($password_changed != "") {
                            $changed_time = $c_date - $password_changed;
                            $data['vendor_notes'][$i]->created_atUpdate = $this->Vendor_order_activities_model->humanTiming($changed_time);
                        }
                    }
                    if ($data['vendor_notes'] != null) {
                        for ($i = 0; $i < count($data['vendor_notes']); $i++) {
                            $data['vendor_notes'][$i]->model_name = "";
                            $data['vendor_notes'][$i]->model_photo = "";
                            $data['vendor_notes'][$i]->user_name = "";
                            $data['vendor_details'] = $this->User_model->get($data['vendor_notes'][$i]->vendor_user_id);
                            $data['user_image'] = $this->Images_model->get_by(array('model_name' => 'user', 'model_id' => $data['vendor_notes'][$i]->vendor_user_id));
                            if ($data['user_image'] != null) {
                                $data['vendor_notes'][$i]->model_name = $data['user_image']->model_name;
                                $data['vendor_notes'][$i]->model_photo = $data['user_image']->photo;
                            }
                            $data['vendor_notes'][$i]->user_name = $data['vendor_details']->first_name;
                        }
                    }
                }
            }
            $data['order_user'] = $orderUser;
            $data['My_vendor_users'] = "";
            $data['vendor_shipping'] = "";
            $data['order_id'] = $order_id;
            $data['promoCodes_active'] = "";
            $data['NorderCount'] = order_count(); // To Get the Latest Order Count.
            $data['ReturnCount'] = return_count();
            $data['shipDate'] = date("Y-m-d H:i:s");    // TO Show date in Process-order.php MODAL

            Debugger::debug($data['user_details']);

            $this->load->view('/templates/_inc/header-vendor.php');
            $this->load->view('/templates/vendor-admin/orders/o/number/index.php', $data);
            $this->load->view('/templates/_inc/footer-vendor.php');

            
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *  Vendor Dashboard
     *      @AJAX call
     *      1. In single order page, select the quantity to increase the count of orders.
     */

    public function orderItem_pickedUpdate() {
        if (isset($_SESSION['user_id'])) {
            $vendor_id = $_SESSION['vendor_id'];
            if ($vendor_id != null) {
                $item_id = $this->input->post('order_itemId');
                $increment = $this->input->post('increment');
                $orderItem_id = $this->Order_items_model->get($item_id);
                if ($orderItem_id != null) {
                    $update_data = array(
                        'picked' => $increment,
                        'updated_at' => date('Y-m-d  H:i:s'),
                    );
                    $this->Order_items_model->update($item_id, $update_data);
                }
                echo true;
            }
        }
    }

    /*
     *      Vendor Dashboard
     *          @Complete
     *              1.It will provide with all completed order based on Vendor.
     */

    public function orders_completed() {

        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $user_id = $_SESSION['user_id'];
            $vendor_detail = $this->Vendor_groups_model->get_by(array('user_id' => $user_id));
            $vendor_id = $vendor_detail->vendor_id;
            if ($vendor_id != null) {
                $search = $this->input->post('search');
                if ($search != null) {
                    $query = "SELECT o.id,o.total,o.order_status,o.updated_at,q.first_name,o.created_at,p.delivery_time FROM orders o LEFT JOIN shipping_options p on o.shipment_id = p.id LEFT JOIN users q on o.user_id = q.id where o.vendor_id=$vendor_id and o.restricted_order='0' and (o.id like '%$search%' or q.first_name like '%$search%') and o.order_status in(2,3,4)";
                    $data['completed_orders'] = $this->db->query($query)->result();
                } else {
                    $query = "SELECT o.id,o.total,o.order_status,o.updated_at,q.first_name,o.created_at,p.delivery_time FROM orders o LEFT JOIN shipping_options p on o.shipment_id = p.id LEFT JOIN users q on o.user_id = q.id where o.vendor_id=" . $vendor_id . " and o.restricted_order='0' and o.order_status in(2,3,4)";
                    $data['completed_orders'] = $this->db->query($query)->result();
                }
            }
            $data['My_vendor_users'] = "";
            $data['vendor_shipping'] = "";
            $data['promoCodes_active'] = "";
            $data['NorderCount'] = order_count(); // To get the Order Count.
            $data['ReturnCount'] = return_count();
            $this->load->view('/templates/_inc/header-vendor.php');
            $this->load->view('/templates/vendor-admin/orders/complete/index.php', $data);
            $this->load->view('/templates/_inc/footer-vendor.php');
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function order_selections() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $user_id = $_SESSION['user_id'];
            $vendor_detail = $this->Vendor_groups_model->get_by(array('user_id' => $user_id));
            $vendor_id = $vendor_detail->vendor_id;
            if ($vendor_id != null) {
                $select = $this->input->post('selection');
                switch ($select) {
                    case 1: //Select all
                        $query = "SELECT o.id,o.total,o.order_status,q.first_name,o.created_at,p.delivery_time FROM orders o LEFT JOIN shipping_options p on o.shipment_id = p.id LEFT JOIN users q on o.user_id = q.id where o.vendor_id=$vendor_id and o.restricted_order='0' and o.order_status not in(5)";
                        $data['orders_received'] = $this->db->query($query)->result();
                        break;
                    case 2: //  New
                        $query = "SELECT o.id,o.total,o.order_status,q.first_name,o.created_at,p.delivery_time FROM orders o LEFT JOIN shipping_options p on o.shipment_id = p.id LEFT JOIN users q on o.user_id = q.id where o.vendor_id=$vendor_id and o.restricted_order='0' and o.order_status='New'";
                        $data['orders_received'] = $this->db->query($query)->result();
                        break;
                    case 3: //  In progress & New
                        $query = "SELECT o.id,o.total,o.order_status,q.first_name,o.created_at,p.delivery_time FROM orders o LEFT JOIN shipping_options p on o.shipment_id = p.id LEFT JOIN users q on o.user_id = q.id where o.vendor_id=$vendor_id and o.restricted_order='0' and o.order_status in(1,2)";
                        $data['orders_received'] = $this->db->query($query)->result();
                        break;
                    case 4: // Shipped
                        $query = "SELECT o.id,o.total,o.order_status,q.first_name,o.created_at,p.delivery_time FROM orders o LEFT JOIN shipping_options p on o.shipment_id = p.id LEFT JOIN users q on o.user_id = q.id where o.vendor_id=$vendor_id and o.restricted_order='0' and o.order_status in (3)";
                        $data['orders_received'] = $this->db->query($query)->result();
                        break;
                }
                if ($data['orders_received'] != null) {
                    $date = date("Y-m-d");
                    for ($i = 0; $i < count($data['orders_received']); $i++) {
                        $data['orders_received'][$i]->created_at = date('M d, Y', strtotime($data['orders_received'][$i]->created_at));
                        switch ($data['orders_received'][$i]->delivery_time) {
                            case 0:
                                $data['orders_received'][$i]->delivery_time = date('M d, Y', strtotime($data['orders_received'][$i]->created_at));
                                break;
                            case 1:
                                $data['orders_received'][$i]->delivery_time = date('M d, Y', strtotime($data['orders_received'][$i]->created_at . ' +1 Weekday'));
                                break;
                            case 2:
                                $data['orders_received'][$i]->delivery_time = date('M d, Y', strtotime($data['orders_received'][$i]->created_at . ' +2 Weekday'));
                                break;
                            case 3:
                                $data['orders_received'][$i]->delivery_time = date('M d, Y', strtotime($data['orders_received'][$i]->created_at . ' +3 Weekday'));
                                break;
                            case 4:
                                $data['orders_received'][$i]->delivery_time = date('M d, Y', strtotime($data['orders_received'][$i]->created_at . ' +5 Weekday'));
                                break;
                            case 5:
                                $data['orders_received'][$i]->delivery_time = date('M d, Y', strtotime($data['orders_received'][$i]->created_at . ' +10 Weekday'));
                                break;
                        }
                    }
                }
            }
            $data['selection'] = $select;
            $data['NorderCount'] = order_count(); // To Get the Latest Order Count.
            $data['ReturnCount'] = return_count();
            $data['My_vendor_users'] = "";
            $data['vendor_shipping'] = "";
            $data['promoCodes_active'] = "";
            $this->load->view('/templates/vendor-admin/orders/index.php', $data);
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function orders_shipped() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $user_id = $_SESSION['user_id'];
            $vendor_id = $_SESSION['vendor_id'];
            $order_id = $this->input->get('order_id');
            if ($order_id != null) {
                $data['order_report'] = "";
                $data['order_report'] = $this->Order_model->get_by(array('id' => $order_id, 'vendor_id' => $vendor_id));
                if (!isset($data['order_report'])) {
                    $this->session->set_flashdata('error', 'Not Valid Entry');
                    header('Location: vendor-dashboard');
                } else {
                    // Order details
                    $query = "SELECT a.payment_id,a.id,a.user_id,a.created_at,b.shipping_type,a.shipped_date,a.total from orders a LEFT JOIN shipping_options b on b.id=a.shipment_id LEFT JOIN user_locations c on c.id=a.location_id LEFT JOIN organization_locations d on d.id=c.organization_location_id  WHERE a.id=$order_id and a.restricted_order='0' and a.vendor_id=$vendor_id";
                    $data['order_details'] = $this->db->query($query)->result();
                    Debugger::debug($data['order_details']);
                    $orderUser = $this->User_model->get_by(array('id' => $data['order_details'][0]->user_id));
                    Debugger::debug($orderUser);
                    // get organization details
                    $sql = "SELECT o.*
                            FROM organizations AS o
                            JOIN organization_groups AS og
                                ON o.id = og.organization_id
                            WHERE user_id = " . $data['order_details'][0]->user_id;
                    Debugger::debug($sql);
                    $orderUser->organization = $this->db->query($sql)->result()[0];

                    $data['orderUser'] = $orderUser;

                    $data['payment_details'] = "";
                    if ($data['order_details'] != null) {
                        for ($i = 0; $i < count($data['order_details']); $i++) {

                            $user_payment = $this->User_payment_option_model->get_by(array('id' => $data['order_details'][$i]->payment_id));

                            $user = $this->User_model->get_by(array('id' => $user_payment->user_id));

                            $customer = $this->stripe->getCustomer($user->stripe_id);
                            // Payment method

                            $payment_method = $customer->sources->retrieve($user_payment->token);
                            $users_payment_obj = new stdClass();
                            $users_payment_obj->id = $user_payment->id;
                            $users_payment_obj->token = $user_payment->token;
                            $users_payment_obj->payment_type = $payment_method->object;
                            $users_payment_obj->card_type = $payment_method->brand;
                            $users_payment_obj->cc_number = $payment_method->last4;
                            $users_payment_obj->cc_name = $payment_method->name;
                            $users_payment_obj->bank_name = $payment_method->bank_name;
                            $users_payment_obj->ba_routing_number = $payment_method->routing_number;
                            $users_payment_obj->ba_account_number = $payment_method->last4;
                            $data['users_payments'][] = $users_payment_obj;
                            unset($users_payment_obj);
                        }
                    }
                    // Order Items and Details
                    $query = "SELECT a.id,b.total,d.retail_price,b.promo_code_id,c.title,e.name,e.mpn,d.price,b.picked as quantity,e.manufacturer FROM orders a  inner join order_items b on b.order_id=a.id left join promo_codes c on c.id=b.promo_code_id INNER JOIN product_pricings d on d.product_id=b.product_id  INNER JOIN products e on e.id=b.product_id  WHERE a.id=$order_id and a.restricted_order='0' and a.vendor_id=$vendor_id group by b.id";
//                $query = "SELECT b.mpn,b.name,a.total,a.quantity FROM order_items a INNER JOIN products b on b.id =a.product_id where a.order_id=$order_id and a.vendor_id=".$vendor_id;
                    $data['order_items'] = $this->db->query($query)->result();

                    // Location of Delivery
                    $query = "select c.nickname,c.address1,c.address2,c.city,c.state,c.zip,d.first_name from orders a INNER JOIN user_locations b on b.user_id=a.user_id INNER JOIN users d on d.id=a.user_id INNER JOIN organization_locations c on c.id=b.organization_location_id where a.id=$order_id and a.restricted_order='0' group by a.id=$order_id";
                    $data['locations'] = $this->db->query($query)->result();

                    // Calculation Section
                    $query = "SELECT a.id,b.id as order_id,b.shipping_price,b.tax,sum(a.total) as total ,a.quantity FROM order_items a INNER JOIN orders b on a.order_id=b.id INNER JOIN shipping_options c on b.shipment_id=c.id  where a.order_id=$order_id and b.restricted_order='0' and a.vendor_id=$vendor_id";
                    $data['calculation_section'] = $this->db->query($query)->result();

                    if ($data['calculation_section'] != null) {
                        $data['grand_total'] = 00000000000000;
                        //  PROMOTIONs View
                        $query = "SELECT b.id,b.discount_value,c.code,c.manufacturer_coupon,c.conditions  FROM orders a INNER JOIN order_promotions b on b.order_id=a.id INNER JOIN promo_codes c on c.id=b.promo_id WHERE a.restricted_order='0' and a.id=$order_id";
                        $data['allpromotions'] = $this->db->query($query)->result();

                        for ($i = 0; $i < count($data['calculation_section']); $i++) {
                            $shipping_price = $data['calculation_section'][$i]->shipping_price;
                            $data['grand_total'] = $data['calculation_section'][$i]->total;
                        }
                    }

                    //  VENDOR NOTES

                    $query = "SELECT a.*,b.first_name,c.model_name,c.photo FROM vendor_order_notes a  INNER JOIN users b on b.id=a.vendor_user_id LEFT JOIN images c on c.model_id=a.vendor_user_id  WHERE a.vendor_id=$vendor_id and a.order_id=$order_id";
                    $data['vendor_message'] = $this->db->query($query)->result();

                    $created_at = date('Y-m-d', strtotime("-1 month"));
                    $data['order_activity'] = $this->Vendor_order_activities_model->limit(5)->order_by('created_at', 'desc')->get_many_by(array('order_id' => $order_id, 'created_at > ' => $created_at));
                    if ($data['order_activity'] != null) {
                        for ($i = 0; $i < count($data['order_activity']); $i++) {
                            $data['order_activity'][$i]->model_name = "";
                            $data['order_activity'][$i]->model_photo = "";
                            $data['order_activity'][$i]->user_name = "";
                            $data['order_activity'][$i]->model_name = "";
                            $data['order_activity'][$i]->model_photo = "";
                            $data['vendor_details'] = $this->User_model->get($data['order_activity'][$i]->vendor_user_id);
                            $data['user_image'] = $this->Images_model->get_by(array('model_name' => 'user', 'model_id' => $data['order_activity'][$i]->vendor_user_id));
                            if ($data['user_image'] != null) {
                                $data['order_activity'][$i]->model_name = $data['user_image']->model_name;
                                $data['order_activity'][$i]->model_photo = $data['user_image']->photo;
                            }
                            $data['order_activity'][$i]->user_name = $data['vendor_details']->first_name;
                        }
                    }
                }
            }
            $data['order_id'] = $order_id;
            $data['My_vendor_users'] = "";
            $data['vendor_shipping'] = "";
            $data['promoCodes_active'] = "";
            $data['NorderCount'] = order_count(); // To Get the Latest Order Count.
            $data['ReturnCount'] = return_count();
            $this->load->view('/templates/vendor-admin/orders/o/complete/index.php', $data);
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function order_processed() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $user = $this->User_model->get_by(array('id' => $_SESSION['user_id']));

            $vendor_id = $_SESSION['vendor_id'];
            $order_id = $this->input->post('order_id');
            $shipped_date = date('Y-m-d', strtotime($this->input->post('shipDate')));
            if ($order_id != null) {
                $update_data = array(
                    'shipped_date' => $shipped_date,
                    'order_status' => '3',
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                if ($update_data != null) {
                    $this->Order_model->update($order_id, $update_data);
                    $activity_insert = array(
                        'vendor_id' => $_SESSION['vendor_id'],
                        'vendor_user_id' => $_SESSION['user_id'],
                        'order_id' => $order_id,
                        'status' => 'Processed',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    if ($activity_insert != null) {
                        $this->Vendor_order_activities_model->insert($activity_insert);
                    }
                }

                $insert_data = array(
                    'order_id' => $order_id,
                    'package_id1' => $this->input->post('shipment1Tracking'),
                    'package_id2' => $this->input->post('shipment2Tracking'),
                    'package_id3' => $this->input->post('shipment3Tracking'),
                    'package_id4' => $this->input->post('shipment4Tracking'),
                    'package_id5' => $this->input->post('shipment5Tracking'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),
                );

                if ($insert_data != null) {
                    $package_id = $this->Order_tracking->insert($insert_data);
                    //inventory update
                    $data['orders'] = $this->Order_model->get_by(array('id' => $order_id));

                    if(!empty($data['orders']->site_id)){
                        $this->processWhitelabel($this->Whitelabel_model->load($data['orders']->site_id));
                    }
                    $location_id = $data['orders']->location_id;//  Order Address
                    $orderAddress = $this->Organization_location_model->get($location_id);
                    $location_inventory = $this->Location_inventories_model->get_many_by(array('location_id' => $location_id));
                    $data['order_details'] = $this->Order_items_model->get_many_by(array('order_id' => $order_id));
                    Debugger::debug($data['order_details']);
                    // $product_count = count($data['order_details']);
                    $orderUser = $this->User_model->get_by(array('id' => $data['orders']->user_id));
                    // get organization details
                    $sql = "SELECT o.*
                            FROM organizations AS o
                            JOIN organization_groups AS og
                                ON o.id = og.organization_id
                            WHERE user_id = " . $data['orders']->user_id;

                    $orderUser->organization = $this->db->query($sql)->result()[0];
                    $data['orderUser'] = $orderUser;
                    $bFlag = False;
                    for ($j = 0; $j < count($data['order_details']); $j++) {

                        if ($location_inventory != null) {
                            $qty = 0;
                            $new_qty = 0;
                            for ($k = 0; $k < count($location_inventory); $k++) {
                                if ($data['order_details'][$j]->product_id == $location_inventory[$k]->product_id) {
                                    $qty = $data['order_details'][$j]->picked;
                                    $update_id = $location_inventory[$k]->id;
                                    $db_qty = $location_inventory[$k]->purchashed_qty;
                                    $old_qty = $qty + $db_qty;
                                    $new_qty = $old_qty + $new_qty;
                                    $update_data = array(
                                        'purchashed_qty' => $new_qty,
                                        'updated_at' => date('Y-m-d H:i:s')
                                    );
                                    $bFlag = True;
                                    $this->Location_inventories_model->update($update_id, $update_data);
                                }
                            }
                        }
                        if (!$bFlag) {
                            $insert_data = array(
                                'location_id' => $location_id,
                                'product_id' => $data['order_details'][$j]->product_id,
                                'purchashed_qty' => $data['order_details'][$j]->picked,
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s')
                            );
                            $this->Location_inventories_model->insert($insert_data);
                        }
                    } //end
                }
                // if($data['user_details']->email_setting5 == '1'){
                $accountName = $data['user_details']->first_name;
                $accountEmail = $data['user_details']->email;
                $data['orders'] = $this->Order_model->get($order_id);
                $data['order_details'] = $this->Order_items_model->get_many_by(array('order_id' => $order_id, 'restricted_order' => '0'));
                $data['user_details'] = $this->User_model->get($data['orders']->user_id);
                if ($data['order_details'] != null) {
                    for ($n = 0; $n < count($data['order_details']); $n++) {
                        $quantityCount = $data['order_details'][$n]->quantity;
                        $pickedCount = $data['order_details'][$n]->picked;
                        if ($quantityCount != $pickedCount) {
                            $data['order_details'][$n]->quantity = $pickedCount;
                        }
                    }
                }
                //$data['orders'] = $this->Order_model->get($order_id);
                if ($data['orders'] != null) {
                    $data['orders']->package_id1 = "";
                    $data['orders']->package_id2 = "";
                    $data['orders']->package_id3 = "";
                    $data['orders']->package_id4 = "";
                    $data['orders']->package_id5 = "";
                    $package_details = $this->Order_tracking->get($package_id);
                    if ($package_details != null) {
                        $data['orders']->package_id1 = $package_details->package_id1;
                        $data['orders']->package_id2 = $package_details->package_id2;
                        $data['orders']->package_id3 = $package_details->package_id3;
                        $data['orders']->package_id4 = $package_details->package_id4;
                        $data['orders']->package_id5 = $package_details->package_id5;
                    }
                }
                $location_id = $data['orders']->location_id;
                $payment_method = $this->User_payment_option_model->get_by(array('id' => $data['orders']->payment_id));


                if ($user_payment != null) {
                    $customer = $this->stripe->getCustomer($data['user_details']->stripe_id);
                    // Payment method
                    $payment_method = $customer->sources->retrieve($user_payment->token);
                    $data['payment_method']->payment_type = $payment_method->object;
                    $data['payment_method']->card_type = $payment_method->brand;
                    $data['payment_method']->exp_month = $payment_method->exp_month;
                    $data['payment_method']->exp_year = $payment_method->exp_year;
                    $data['payment_method']->cc_number  = $payment_method->last4;
                    $data['payment_method']->cc_name = $payment_method->name;
                    $data['payment_method']->bank_name = $payment_method->bank_name;
                }


                $data['shipping_address'] = $this->Organization_location_model->get_by(array('id' => $location_id));
                $data['shippment'] = $this->Shipping_options_model->get_by(array('id' => $data['orders']->shipment_id));
                if ($data['shippment'] != null) {
                    $carrier = $data['shippment']->carrier;
                }
                $data['vendor_image'] = $this->Images_model->get_by(array('model_id' => $data['orders']->vendor_id, 'model_name' => 'vendor'));
                $data['vendor_details'] = $this->Vendor_model->get_by(array('id' => $data['orders']->vendor_id));
                for ($k = 0; $k < count($data['order_details']); $k++) {
                    $data['promos'] = $this->Order_promotion_model->get_many_by(array('order_id' => $order_id, 'restricted_order' => '0'));
                    if ($data['promos'] != null) {
                        for ($m = 0; $m < count($data['promos']); $m++) {
                            $data['promos'][$m]->promocode = "";
                            $data['promos'][$m]->promocode = $this->Promo_codes_model->get_by(array('id' => $data['promos'][$m]->promo_id));
                        }
                    }
                    $product_image = $this->Images_model->get_by(array('model_id' => $data['order_details'][$k]->product_id, 'model_name' => 'products', 'image_type' => 'mainimg'));
                    $product_pricing = $this->Product_pricing_model->get_by(array('product_id' => $data['order_details'][$k]->product_id, 'vendor_id' => $data['order_details'][$k]->vendor_id));
                    $product = $this->Products_model->get_by(array('id' => $data['order_details'][$k]->product_id));
                    $vendors = $this->Vendor_model->get_by(array('id' => $data['order_details'][$k]->vendor_id));
                    $data['order_details'][$k]->product_image = $product_image;
                    $data['order_details'][$k]->Product_details = $product_pricing;
                    $data['order_details'][$k]->product = $product;
                    $data['order_details'][$k]->vendor = $vendors;
                }

                $vendor_name = $data['order_details'][0]->vendor->name;
                $vendor_email = $data['order_details'][0]->vendor->email;
                $subject = "Your Order Was Shipped!";
                $data['subject'] = $subject;

                $data['message'] = "<div style='text-align: center; line-height: 25px;'>"
                                        . "<hr style='margin: 0 auto; width: 40px; color: #e8eaf1; background-color: #e8eaf1; border: 1px solid #e8eaf1;'>"
                                        . "<br />"
                                        . "Hi " . $orderUser->first_name . ' ' . $orderUser->last_name . ", " . $vendor_name . " has marked your order as shipped! You can track it's progress by using the tracking information below (if supplied by the vendor). If you have any questions about your order, please <a href=\"mailto:" . $vendor_email . "\">contact the vendor directly</a>:"
                                        . "<br />"
                                    . "</div>";

                if ($data['orders']->package_id1 != null && $data['orders']->package_id1 != "") {
                    $data['tracking_numbers'][0] = $data['orders']->package_id1;
                }
                if ($data['orders']->package_id2 != null && $data['orders']->package_id2 != "") {
                    $data['tracking_numbers'][1] = $data['orders']->package_id2;
                }
                if ($data['orders']->package_id3 != null && $data['orders']->package_id3 != "") {
                    $data['tracking_numbers'][2] = $data['orders']->package_id3;
                }
                if ($data['orders']->package_id4 != null && $data['orders']->package_id4 != "") {
                    $data['tracking_numbers'][3] = $data['orders']->package_id4;
                }
                if ($data['orders']->package_id5 != null && $data['orders']->package_id5 != "") {
                    $data['tracking_numbers'][4] = $data['orders']->package_id5;
                }


                $body = $this->load->view('/templates/email/order/shipped/index.php', $data, TRUE);
                $mail_status = send_matix_email($body, $subject, $data['user_details']->email);

                /*
                Send notification to other users:
                    Email all tier 1s
                    Email All tier 2s at the location of the order
                */


                // get all users
                $sql = "SELECT u.email, u.role_id, ol.id AS location_id
                        FROM users AS u
                        JOIN organization_groups AS og
                            ON og.user_id = u.id
                        JOIN organization_locations AS ol
                            ON og.organization_id = ol.organization_id
                        WHERE og.organization_id = $orderAddress->organization_id";

                $orgUsers = $this->db->query($sql)->result();


                $sentEmails = [];
                $data['subject'] = 'Order Confirmation';
                foreach($orgUsers as $orgUser){
                    if($orgUser->email != $data['user_details']->email
                       && ($orgUser->location_id == $location_id || $orgUser->role_id == '3' || $orgUser->role_id == '7')
                       && $orgUser->role_id != '5'
                       && $orgUser->role_id != '6'
                       && !in_array($orgUser->email, $sentEmails)) {
                        // Debugger::debug('mailing ' . $orgUser->email);
                        $data['message'] = "<div style='text-align: center; line-height: 25px;'>"
                                         . "<hr style='margin: 0 auto; width: 40px; color: #e8eaf1; background-color: #e8eaf1; border: 1px solid #e8eaf1;'>"
                                         . "<br />"
                                         . $orderUser->first_name . ' ' . $orderUser->last_name . " just placed this order for " . $orderAddress->nickname . ", we will send a notification when the order is processed."
                                         . "<br />"
                                         . "</div>";

                        $body = $this->load->view('/templates/email/order/index.php', $data, TRUE);

                        $mail_status = send_matix_email($body, $subject, $orgUser->email);
                        $sentEmails[] = $orgUser->email;
                        // send test email to len
                        // $mail_status = send_matix_email($body, $subject, 'lenlyle@gmail.com');
                        // Debugger::debug($mail_status);
                    }
                }

                $this->session->set_flashdata('success', 'Order Processed and email is  sent');
                header("Location: vendor-order-Processing?order_id=" . $order_id);
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function processed_order() {
        if(config_item('whitelabel')){
            $this->processWhitelabel(config_item('whitelabel'));
        }
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $order_id = $this->input->get('order_id');
            $vendor_id = $_SESSION['vendor_id'];
            if ($order_id != null) {
                $data['order_report'] = $this->Order_model->get($order_id);
                $query = "SELECT a.id,b.first_name,d.organization_name,a.total,a.user_id,c.nickname,c.address1,c.address2,c.city,c.state,c.zip FROM orders a INNER JOIN users b on b.id=a.user_id INNER JOIN organization_locations c on c.id=a.location_id INNER JOIN organizations d on d.id=c.organization_id WHERE a.vendor_id=$vendor_id and a.restricted_order='0' and a.id=$order_id";
                $data['order_processed'] = $this->db->query($query)->result();
                // Order details
                $query = "SELECT a.payment_id,a.id,a.created_at,a.user_id,b.shipping_type,a.shipped_date,a.total from orders a LEFT JOIN shipping_options b on b.id=a.shipment_id LEFT JOIN user_locations c on c.id=a.location_id LEFT JOIN organization_locations d on d.id=c.organization_location_id  WHERE a.id=$order_id and a.restricted_order='0' and a.vendor_id=$vendor_id";
                $data['order_details'] = $this->db->query($query)->result();
                $data['payment_details'] = [];
                if ($data['order_details'] != null) {
                    for ($i = 0; $i < count($data['order_details']); $i++) {
                        $user_payment = $this->User_payment_option_model->get_by(array('id' => $data['order_details'][$i]->payment_id));
                        $user = $this->User_model->get_by(array('id' => $user_payment->user_id));
                        $customer = $this->stripe->getCustomer($user->stripe_id);
                        // Payment method

                        $payment_method = $customer->sources->retrieve($user_payment->token);

                        $users_payment_obj = new stdClass();
                        $users_payment_obj->id = $user_payment->id;
                        $users_payment_obj->token = $user_payment->token;
                        $users_payment_obj->payment_type = $payment_method->object;
                        $users_payment_obj->card_type = $payment_method->brand;
                        $users_payment_obj->cc_number = $payment_method->last4;
                        $users_payment_obj->cc_name = $payment_method->name;
                        $users_payment_obj->bank_name = $payment_method->bank_name;
                        $users_payment_obj->ba_routing_number = $payment_method->routing_number;
                        $users_payment_obj->ba_account_number = $payment_method->last4;
                        $data['payment_details'][] = $users_payment_obj;
                        unset($users_payment_obj);

                    }
                }
                // Order Items and Details
                $query = "SELECT a.id,b.total,d.retail_price,b.promo_code_id,c.title,e.name,e.mpn,d.price,b.picked as quantity,e.manufacturer FROM orders a  inner join order_items b on b.order_id=a.id left join promo_codes c on c.id=b.promo_code_id INNER JOIN product_pricings d on d.product_id=b.product_id  INNER JOIN products e on e.id=b.product_id  WHERE a.id=$order_id and a.restricted_order='0' and a.vendor_id=$vendor_id group by b.id";
                $data['order_items'] = $this->db->query($query)->result();

                // Location of Delivery
                $query = "select c.nickname,c.address1,c.address2,c.city,c.state,c.zip,d.first_name from orders a INNER JOIN user_locations b on b.user_id=a.user_id INNER JOIN users d on d.id=a.user_id INNER JOIN organization_locations c on c.id=b.organization_location_id where a.id=$order_id and a.restricted_order='0' group by a.id=$order_id";
                $data['locations'] = $this->db->query($query)->result();

                // Calculation Section
                $query = "SELECT a.id,b.id as order_id,b.shipping_price,b.tax,sum(a.total) as total ,a.quantity FROM order_items a INNER JOIN orders b on a.order_id=b.id INNER JOIN shipping_options c on b.shipment_id=c.id  where a.order_id=$order_id and b.restricted_order='0' and a.vendor_id=$vendor_id";
                $data['calculation_section'] = $this->db->query($query)->result();

                if ($data['calculation_section'] != null) {
                    $data['grand_total'] = 00000000000000;
                    //  PROMOTIONs View
                    $query = "SELECT b.id,b.discount_value,c.code,c.manufacturer_coupon,c.conditions  FROM orders a INNER JOIN order_promotions b on b.order_id=a.id INNER JOIN promo_codes c on c.id=b.promo_id WHERE a.restricted_order='0' and a.id=$order_id";
                    $data['allpromotions'] = $this->db->query($query)->result();

                    for ($i = 0; $i < count($data['calculation_section']); $i++) {
                        $shipping_price = $data['calculation_section'][$i]->shipping_price;
                        $data['grand_total'] = $data['calculation_section'][$i]->total;
                    }
                }



                $data['order_id'] = $order_id;
                $data['My_vendor_users'] = "";
                $data['vendor_shipping'] = "";
                $data['promoCodes_active'] = "";
                $data['NorderCount'] = order_count(); // To Get the Latest Order Count.
                $data['ReturnCount'] = return_count();
                $this->load->view('/templates/vendor-admin/orders/o/processed/index.php', $data);
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function Cancel_order() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $order_id = $this->input->post('order_id');
            $vendor_id = $_SESSION['vendor_id'];
            $user = $this->User_model->get_by(array('id' => $_SESSION['user_id']));
            if ($order_id != null) {
                $update_data = array(
                    'order_status' => '5',
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                if ($update_data != null) {
                    $this->Order_model->update($order_id, $update_data);
                    $order_details = $this->Order_model->get($order_id);
                    if(!empty($order_details->site_id)){
                        $this->processWhitelabel($this->Whitelabel_model->load($order_details->site_id));
                    }
                    $data['user_details'] = $this->User_model->get($order_details->user_id);
                    $activity_insert = array(
                        'vendor_id' => $_SESSION['vendor_id'],
                        'vendor_user_id' => $_SESSION['user_id'],
                        'order_id' => $order_id,
                        'status' => 'Cancelled',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    if ($activity_insert != null) {
                        $this->Vendor_order_activities_model->insert($activity_insert);
                    }
                    $accountName = $data['user_details']->first_name;
                    $accountEmail = $data['user_details']->email;
                    $data['order_details'] = $this->Order_items_model->get_many_by(array('order_id' => $order_id, 'restricted_order' => '0'));
                    $data['orders'] = $this->Order_model->get($order_id);
                    $location_id = $data['orders']->location_id;
                    // Payment method
                    $user_payment = $this->User_payment_option_model->get_by(array('id' => $data['orders']->payment_id));
                    $user = $this->User_model->get_by(array('id' => $user_payment->user_id));
                    $customer = $this->stripe->getCustomer($user->stripe_id);
                    Debugger::debug($user);
                    $payment_method = $customer->sources->retrieve($user_payment->token);
                    $users_payment_obj = new stdClass();
                    $users_payment_obj->id = $user_payment->id;
                    $users_payment_obj->token = $user_payment->token;
                    $users_payment_obj->payment_type = $payment_method->object;
                    $users_payment_obj->card_type = $payment_method->brand;
                    $users_payment_obj->cc_number = $payment_method->last4;
                    $users_payment_obj->cc_name = $payment_method->name;
                    $users_payment_obj->bank_name = $payment_method->bank_name;
                    $users_payment_obj->ba_routing_number = $payment_method->routing_number;
                    $users_payment_obj->ba_account_number = $payment_method->last4;
                    $data['payments'] = $users_payment_obj;
                    unset($users_payment_obj);

                    $data['user'] = $user;
                    $data['shipping_address'] = $this->Organization_location_model->get_by(array('id' => $location_id));
                    $data['shippment'] = $this->Shipping_options_model->get_by(array('id' => $data['orders']->shipment_id));
                    $data['vendor_image'] = $this->Images_model->get_by(array('model_id' => $data['orders']->vendor_id, 'model_name' => 'vendor'));
                    $data['vendor_details'] = $this->Vendor_model->get_by(array('id' => $data['orders']->vendor_id));
                    for ($k = 0; $k < count($data['order_details']); $k++) {
                        $data['promos'] = $this->Order_promotion_model->get_many_by(array('order_id' => $order_id));
                        if ($data['promos'] != null) {
                            for ($m = 0; $m < count($data['promos']); $m++) {
                                $data['promos'][$m]->promocode = "";
                                $data['promos'][$m]->promocode = $this->Promo_codes_model->get_by(array('id' => $data['promos'][$m]->promo_id));
                            }
                        }
                        $product_image = $this->Images_model->get_by(array('model_id' => $data['order_details'][$k]->product_id, 'model_name' => 'products', 'image_type' => 'mainimg'));
                        $product_pricing = $this->Product_pricing_model->get_by(array('product_id' => $data['order_details'][$k]->product_id, 'vendor_id' => $data['order_details'][$k]->vendor_id));
                        $product = $this->Products_model->get_by(array('id' => $data['order_details'][$k]->product_id));
                        $vendors = $this->Vendor_model->get_by(array('id' => $data['order_details'][$k]->vendor_id));
                        $data['order_details'][$k]->product_image = $product_image;
                        $data['order_details'][$k]->Product_details = $product_pricing;
                        $data['order_details'][$k]->product = $product;
                        $data['order_details'][$k]->vendor = $vendors;
                    }
                    $subject = "Order Cancelled";
                    $data['subject'] = $subject;
                    $data['message'] = "<div style='text-align: center; line-height: 25px;'>"
                        . "<hr style='margin: 0 auto; width: 40px; color: #e8eaf1; background-color: #e8eaf1; border: 1px solid #e8eaf1;'>"
                        . "<br />"
                        . "Hi " . $accountName . ", the order below has been cancelled. If the order has already been charged, you'll be refunded the total amount shown below. If you have any questions about this order, please contact the vendor directly:"
                        . "<br />"
                    . "</div>";
                    $body = $this->load->view('/templates/email/order/cancelled/index.php', $data, TRUE);
                    $mail_status = send_matix_email($body, $subject, $accountEmail);

                    /*
                    Send notification to other users:
                        Email all tier 1s
                        Email All tier 2s at the location of the order
                    */
                    $orgResult = $this->Organization_groups_model->get_by(array('user_id' => $user->id));

                    $sql = "SELECT u.first_name, u.last_name, u.email, u.role_id, ul.organization_location_id AS location_id, u.role_id, ol.nickname AS location_name, ol.organization_id
                            FROM users AS u
                            JOIN user_locations AS ul
                                ON u.id = ul.user_id
                            JOIN organization_locations AS ol
                                ON ol.id = ul.organization_location_id
                            JOIN roles AS r
                                ON u.role_id = r.id
                            WHERE ol.organization_id = $orgResult->organization_id";

                    $orgUsers = $this->db->query($sql)->result();

                    $sentEmails = [];
                    $data['subject'] = 'Order Confirmation';
                    foreach($orgUsers as $orgUser){
                        if($orgUser->email != $user->email
                           && ($orgUser->location_id == $location_id || $orgUser->role_id == '3' || $orgUser->role_id == '7')
                           && $orgUser->role_id != '5'
                           && $orgUser->role_id != '6'
                           && !in_array($orgUser->email, $sentEmails)) {

                            $userName = $orgUser->first_name . ' ' . $orgUser->last_name;
                            $data['message'] = "<div style='text-align: center; line-height: 25px;'>"
                                             . "<hr style='margin: 0 auto; width: 40px; color: #e8eaf1; background-color: #e8eaf1; border: 1px solid #e8eaf1;'>"
                                             . "<br />"
                                             . $accountName . " just cancelled the order below. If the order has already been charged, you'll be refunded the total amount shown below. If you have any questions about this order, please contact the vendor directly:"
                                            . "<br />"
                                            . "</div>";

                            $body = $this->load->view('/templates/email/order/index.php', $data, TRUE);

                            $mail_status = send_matix_email($body, $subject, $orgUser->email);
                            $sentEmails[] = $orgUser->email;
                            // send test email to len
                            // $mail_status = send_matix_email($body, $subject, 'lenlyle@gmail.com');
                            // Debugger::debug($mail_status);
                        }
                    }
                }
            }
            header("Location: vendor-orders");
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

}

<?php

use Stripe\Exception\InvalidRequestException;

defined('BASEPATH') OR exit('No direct script access allowed');

class UserDashboard extends MW_Controller {

    function __construct() {

        parent::__construct();
        $this->load->model('Organization_model');
        $this->load->model('Organization_groups_model');
        $this->load->model('User_location_model');
        $this->load->model('Organization_location_model');
        $this->load->model('Products_model');
        $this->load->model('Request_list_model');
        $this->load->model('Product_pricing_model');
        $this->load->model('Vendor_model');
        $this->load->model('Review_model');
        $this->load->model('Role_model');
        $this->load->model('User_model');
        $this->load->model('Order_model');
        $this->load->model('Order_items_model');
        $this->load->model('Shipping_options_model');
        $this->load->model('User_payment_option_model');
        $this->load->model('Images_model');
        $this->load->model('Order_item_return_model');
        $this->load->model('Order_return_model');
        $this->load->model('User_licenses_model');
        $this->load->model('User_autosave_model');
        $this->load->model('Promo_codes_model');
        $this->load->model('Product_tax_model');
        $this->load->model('Request_list_activity_model');
        $this->load->model('Order_promotion_model');
        $this->load->model('Vendor_groups_model');
        $this->load->model('NotificationEmails');
        $this->load->library('encryption');
        $this->load->library('stripe');
        $this->load->library('cart');
        $this->load->library('email');
        $this->load->helper('my_email_helper');
    }

    //view UserDashboard
    public function dashboard() {
        $roles = unserialize(ROLES_USERS);
        $roles_vendor = unserialize(ROLES_VENDORS);
        $roles_admin = unserialize(ROLES_ADMINS);
        if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], base_url()) != 0) {
            $this->session->set_flashdata("error", "");
            $this->session->set_flashdata("success", "");
        }
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $data['locations'] = $this->User_location_model->get_many_by(array('user_id' => $user_id));
            for ($i = 0; $i < count($data['locations']); $i++) {
                $data['user_locations'][] = $this->Organization_location_model->get_by(array('id' => $data['locations'][$i]->organization_location_id));
            }
            $this_month = date('Y-m-01');
            $today_date = date('Y-m-d');
            if (isset($_SESSION['location_id'])) {
                $locations_id = $_SESSION['location_id'];
            } else {
                $locations_id = "all";
            }
            if ($locations_id == "all") {
                $data['license'] = $this->User_licenses_model->order_by('id', 'desc')->get_by(array('user_id' => $user_id, 'approved' => 1, 'expire_date >=' => $today_date));
                if ($data['license'] == null) {
                    $data['license'] = $this->User_licenses_model->order_by('id', 'desc')->get_by(array('user_id' => $user_id));
                }
                $sql = "SELECT sum(total) as totals from orders where order_status != 'Cancelled' and restricted_order ='0' and user_id='" . $user_id . "' and created_at >='" . $this_month . "'";
            } else {
                $user_state = $this->Organization_location_model->get_by(array('id' => $locations_id));
                $data['license'] = $this->User_licenses_model->get_by(array('user_id' => $user_id, 'state' => $user_state->state));
                $sql = "SELECT sum(total) as totals from orders where order_status != 'Cancelled' and restricted_order ='0' and user_id='" . $user_id . "' and location_id='" . $locations_id . "' AND created_at >='" . $this_month . "'";
            }
            $data['total_spend'] = $this->db->query($sql)->result();
            $data['user_image'] = $this->Images_model->get_by(array('model_id' => $_SESSION["user_id"], 'model_name' => 'user'));
            $organization_details = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
            $organization_id = $organization_details->organization_id;
            $data['organization_id'] = $organization_id;
            $data['organization_role_id'] = $_SESSION['role_id'];
            $data['Manage_usersPage'] = 0;  // Date: 3/17/2017 To load the DEFINED page. (dashboard/Manage-users)
            $this->load->view('/templates/_inc/header', $data);
            $this->load->view('/templates/account/index', $data);
            $this->load->view('/templates/_inc/footer');
        } else if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles_vendor))) {
            header("location: vendor-dashboard");
        } else if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles_admin))) {
            header("location: superAdmins-Account");
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function get_ytd() { //get order total per year
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $this_year = date('Y-01-01');
            $user_id = $_SESSION['user_id'];
            if (isset($_SESSION['location_id'])) {
                $location_id = $_SESSION['location_id'];
            } else {
                $location_id = "all";
            }
            if ($location_id == "all") {
                $sql = "SELECT sum(total) as totals from orders where order_status != 'Cancelled' and restricted_order ='0' and user_id='" . $user_id . "' AND created_at >='" . $this_year . "'";
            } else {
                $sql = "SELECT sum(total) as totals from orders where order_status != 'Cancelled' and restricted_order ='0' and user_id='" . $user_id . "' and location_id='" . $location_id . "' AND created_at >='" . $this_year . "'";
            }
            $data['total_spend'] = $this->db->query($sql)->result();
            echo json_encode($data);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function get_cartdetails() { //get user cart details
        $user_id = $_SESSION['user_id'];
        $data['locations'] = $this->User_location_model->get_many_by(array('user_id' => $user_id));
        $userLicenses = $this->User_licenses_model->loadValidLicenses($user_id, 1);
        for ($i = 0; $i < count($data['locations']); $i++) {
            $location = $this->Organization_location_model->get_by(array('id' => $data['locations'][$i]->organization_location_id));
            $location->licences = $userLicenses[$location->state];
            $data['user_locations'][] = $location;
        }
        $organization = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
        $data['cart'] = $this->User_autosave_model->fetchCart($_SESSION['role_id'], $organization->organization_id, $user_id);
        if (isset($_SESSION['location_id'])) {
            $location_id = $_SESSION['location_id'];
        } else {
            $location_id = "all";
        }

        if ($data['cart'] != null) {
            $cart = $data['cart']->cart;
            $row = json_decode($cart);
            if ($location_id == "all") {
                foreach ($row as $item) {
                    for ($j = 0; $j < count($data['user_locations']); $j++) {
                        if ($item->location_id == $data['user_locations'][$j]->id && $item->status == 0) {
                            if (!(isset($data['user_locations'][$j]->item_count))) {
                                $data['user_locations'][$j]->item_count = 0;
                            }
                            if (!(isset($data['user_locations'][$j]->item_total))) {
                                $data['user_locations'][$j]->item_total = 0;
                            }
                            $data['user_locations'][$j]->item_count += $item->qty;
                            $product_pricings = $this->Product_pricing_model->get_by(array('product_id' => $item->pro_id, 'vendor_id' => $item->ven_id));
                            if ($product_pricings->retail_price > 0) {
                                $data['user_locations'][$j]->item_total += ($product_pricings->retail_price * $item->qty);
                            } else {
                                $data['user_locations'][$j]->item_total += ($item->price * $item->qty);
                            }
                        }
                    }
                }
            } else {
                $data['user_locations'] = new stdClass();
                $locations = $this->Organization_location_model->get_by(array('id' => $location_id));
                $data['user_locations']->nickname = $locations->nickname;
                $data['user_locations']->id = $locations->id;
                $data['user_locations']->item_count = 0;
                $data['user_locations']->item_total = 0;
                foreach ($row as $item) {
                    if ($location_id == $item->location_id && $item->status == 0) {
                        if (!(isset($data['user_locations']->item_count))) {
                            $data['user_locations']->item_count = 0;
                        }
                        if (!(isset($data['user_locations']->item_total))) {
                            $data['user_locations']->item_total = 0;
                        }
                        //$data['user_locations']->item_count += 1;
                        $product_pricings = $this->Product_pricing_model->get_by(array('product_id' => $item->pro_id, 'vendor_id' => $item->ven_id));
                        $data['user_locations']->item_count += $item->qty;
                        if ($product_pricings->retail_price > 0) {
                            $data['user_locations']->item_total += ($product_pricings->retail_price * $item->qty);
                        } else {
                            $data['user_locations']->item_total += ($item->price * $item->qty);
                        }
                        $data['user_locations']->id = $locations->id;
                        $data['user_locations']->updated_at = $locations->updated_at;
                    } else {
                        $locations = $this->Organization_location_model->get_by(array('id' => $location_id));
                        $data['user_locations']->updated_at = $locations->updated_at;
                        $data['user_locations']->id = $locations->id;
                    }
                }
            }
        }
        echo json_encode($data);
    }

    public function addtocart() { //products add to cart
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION["user_id"];
            $user = $this->User_model->get_by(array('id' => $user_id));
            $location_id = $this->input->post('location_id');
            $data['location_id'] = $location_id;
            $vendor_id = $this->input->post('vendor_id');
            $product_id = $this->input->post('product_id');
            $qty = $this->input->post('cartqty');
            $price = $this->input->post('price');
            $l_id = explode(",", $location_id);
            $product_licence = $this->Products_model->get_by(array('id' => $product_id));
            $pro_name = $product_licence->name;
            $product_name = preg_replace('/[^A-Z a-z0-9\-]/', '', $pro_name);
            $license = $product_licence->license_required;
            $insert = "";
            $organization = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
            $organization_id = $organization->organization_id;
            $is_student = in_array($_SESSION['role_id'], unserialize(ROLES_STUDENTS));
            $cart_details = $this->User_autosave_model->fetchCart($_SESSION['role_id'], $organization->organization_id, $user_id);
            for ($i = 0; $i < count($l_id); $i++) {
                $new = random_string('alnum', 16);
                if ($license == 'Yes') {
                    //licenced Products
                    $user_state = $this->Organization_location_model->get_by(array('id' => $l_id[$i]));
                    $state = $user_state->state;
                    $data['state'] = $state;
                    //Students licence check
                    if ($is_student) {
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
                        $user_license = $this->User_licenses_model->get_by(array('state' => $state, 'user_id' => $user_id, 'approved' => '1'));

                        if (is_null($user_license)){
                            $tier_1_2 = unserialize(ROLES_TIER1_2);
                            if (in_array($user->role_id,$tier_1_2)){
                                $org_users = $this->Organization_groups_model->get_users_by_user($user_id);

                                $user_license[] = $this->User_licenses_model->get_by(['state' => $state, 'user_id' => $org_users, 'approved' => '1']);

                            } else {
                                $user_licenses[] = $user_license;
                            }
                        }
                    }
                    $user_license = (is_array($user_license)) ? $user_license : array($user_license);
                    $today_date = date('Y-m-d');
                    $cartFlag = FALSE;
                    $data['user_license'] = $user_license;
                    $data['license_count'] = count($user_license);
                    if (count($user_license) > 0 || $is_student) {
                        $expireFlag = FALSE;
                        foreach ($user_license as $value) {
                            if ($value != null) {
                                $end_date = $value->expire_date;
                                if ($today_date <= $end_date) {
                                    $cartFlag = true;
                                } else {
                                    $expireFlag = true;
                                }
                            }
                        }
                        $cartFlag=true;
                        if ($cartFlag || $is_student) {
                            $cartData = false;
                            if ($cart_details != null || $cart_details != "") {
                                $cart = $cart_details->cart;
                                $row = json_decode($cart);
                                if ($row != null || $row != "") {
                                    foreach ($row as $item) {
                                        if ($item->location_id == $l_id[$i]) {
                                            if ($item->pro_id == $product_id && $item->ven_id == $vendor_id) {
                                                $rowid = $item->rowid;
                                                $quantity = $item->qty;
                                                $new_qty = $qty + $quantity;
                                                $carts_data = array($rowid => array(
                                                    'rowid' => $rowid,
                                                    'qty' => $new_qty,
                                                    'status' => '0',
                                                ));
                                                $insert = $this->cart->update($carts_data);
                                                $this->session->set_flashdata("success", "Products added to cart successfully");
                                            }
                                        }
                                    }
                                    if ($insert == null || $insert == "") {
                                        $cartData = TRUE;
                                    }
                                } else {
                                    $cartData = TRUE;
                                }
                            } else {
                                $cartData = TRUE;
                            }
                            if ($cartData) {
                                $insert_data = array(
                                    'id' => $new,
                                    'qty' => $qty,
                                    'name' => $product_name,
                                    'price' => $price,
                                    'pro_id' => $product_id,
                                    'location_id' => $l_id[$i],
                                    'status' => '0',
                                    'ven_id' => $vendor_id
                                );
                                $this->cart->insert($insert_data);
                                $this->session->set_flashdata("success", "Products added to cart successfully");
                            }
                        } else if ( ! $is_student) {
                            $data['cart_message'][] = $user_state->nickname;
                        }
                        if ($expireFlag && ! $is_student) {
                            $data['expire_message'][] = $user_state->nickname;
                        }
                    } else if ( ! $is_student) {
                         $data['cart_message'][] = $user_state->nickname;
                    }
                } else {
                    //Unlicenced Products
                    $cartData = FALSE;
                    if ($cart_details != null || $cart_details != "") {
                        $cart = $cart_details->cart;
                        $row = json_decode($cart);
                        if ($row != null || $row != "") {
                            foreach ($row as $item) {
                                if ($item->location_id == $l_id[$i]) {
                                    if ($item->pro_id == $product_id && $item->ven_id == $vendor_id) {
                                        $rowid = $item->rowid;
                                        $quantity = $item->qty;
                                        $new_qty = $qty + $quantity;
                                        $carts_data = array($rowid => array(
                                                'rowid' => $rowid,
                                                'qty' => $new_qty,
                                                'status' => 0,
                                        ));
                                        $insert = $this->cart->update($carts_data);
                                        $this->session->set_flashdata("success", "Products added to cart successfully");
                                    }
                                }
                            }
                            if ($insert == null || $insert == "") {
                                $cartData = TRUE;
                            }
                        } else {
                            $cartData = TRUE;
                        }
                    } else {
                        $cartData = TRUE;
                    }
                    if ($cartData) {
                        $insert_data = array(
                            'id' => $new,
                            'qty' => $qty,
                            'name' => $product_name,
                            'price' => $price,
                            'pro_id' => $product_id,
                            'location_id' => $l_id[$i],
                            'status' => 0,
                            'ven_id' => $vendor_id
                        );
                        $this->cart->insert($insert_data);
                        $this->session->set_flashdata("success", "Products added to cart successfully");
                    }
                }
            }
            $cart_data = $this->cart->contents();
            $data['sss'] = $cart_data;
            $this->User_autosave_model->saveCart($_SESSION['role_id'], $cart_data, $organization_id, $user_id);
            echo json_encode($data);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function get_userpayments() { //get all users payement lists
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $location_id = $this->input->post('id');
            $this->User_payment_option_model->update($location_id, array('updated_at' => date('Y-m-d H:i:s')));
            $data['payments'] = $this->User_payment_option_model->get_many_by(array('user_id' => $_SESSION['user_id']));
            echo json_encode($data);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function user_locations() { //get users assigned locations details
        if (isset($_SESSION["user_id"])) {
            $user_id = $_SESSION['user_id'];
            $data['location_id'] = $this->User_location_model->get_many_by(array('user_id' => $user_id));
            for ($i = 0; $i < count($data['location_id']); $i++) {
                $data['user_locations'][] = $this->Organization_location_model->get_by(array('id' => $data['location_id'][$i]->organization_location_id));
            }
            echo json_encode($data);
        }
    }

    public function view_profile() { //view user profile based on login id
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $data['user_details'] = $this->User_model->get_by(array('id' => $user_id));
            $c_date = strtotime("now");
            $data['locations'] = $this->User_location_model->get_many_by(array('user_id' => $user_id));
            for ($i = 0; $i < count($data['locations']); $i++) {
                $data['user_locations'][] = $this->Organization_location_model->order_by('id', 'desc')->get_by(array('id' => $data['locations'][$i]->organization_location_id));
            }
            $password_changed = strtotime($data['user_details']->password_last_updated_at);
            if ($password_changed != "") {
                $changed_time = $c_date - $password_changed;
                $data['password_last_updated'] = $this->User_model->humanTiming($changed_time);
            } else {
                $password_changed = strtotime($data['user_details']->created_at);
                $changed_time = $c_date - $password_changed;
                $data['password_last_updated'] = $this->User_model->humanTiming($changed_time);
            }
            if (isset($_SESSION['location_id'])) {
                $location_id = $_SESSION['location_id'];
            } else {
                $location_id = "all";
            }
            if ($location_id == "all") {
                $data['license'] = $this->User_licenses_model->get_many_by(array('user_id' => $user_id));
            } else {
                $user_state = $this->Organization_location_model->get_by(array('id' => $location_id));
                $data['license'] = $this->User_licenses_model->get_many_by(array('state' => $user_state->state, 'user_id' => $user_id));
            }
            $data['user_image'] = $this->Images_model->get_by(array('model_id' => $user_id, 'model_name' => 'user'));
            $this->load->view('/templates/_inc/header', $data);
            $this->load->view('/templates/account/profile/index', $data);
            $this->load->view('/templates/_inc/footer');
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function update_name() { //update user name with profile image
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $data['user_data'] = $this->Images_model->get_by(array('model_id' => $user_id, 'model_name' => 'user'));
            $user_name = $this->input->post('accountName');
            $salutation = $this->input->post('accountTitle');
            $new_file_name = time() . preg_replace('/[^a-zA-Z0-9_.]/', '_', $_FILES['accountAvatar']['name']);
            $_FILES['accountAvatar']['name'] = $new_file_name;
            // $_FILES['accountAvatar']['type'] = $_FILES['accountAvatar']['type'];
            // $_FILES['accountAvatar']['tmp_name'] = $_FILES['accountAvatar']['tmp_name'];
            // $_FILES['accountAvatar']['error'] = $_FILES['accountAvatar']['error'];
            // $_FILES['accountAvatar']['size'] = $_FILES['accountAvatar']['size'];
            $config['upload_path'] = 'uploads/user/profile/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg'; //set image types
            $config['max_size'] = 1024; //set matximum image size
            $config['remove_spaces'] = true;
            $config['overwrite'] = false;
            $config['max_width'] = '';
            $config['max_height'] = '';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('accountAvatar')&& $this->input->post('accountAvatar')!=null) {
                $this->session->set_flashdata('error', 'The uploaded file exceeds the maximum allowed size (1MB)');
            } else {
                $image_uploaded = $this->upload->data();
                $config['image_library'] = 'gd2';
                $config['quality'] = '60';
                $config['source_image'] = $this->upload->upload_path . $this->upload->file_name;
                $this->load->library('image_lib', $config);
                $this->image_lib->resize();

                if ($image_uploaded != null) {
                    $fileName = $image_uploaded['file_name'];
                }
                if ($_FILES['accountAvatar']['tmp_name'] != '') {
                    $update_data = array(
                        'photo' => $fileName,
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    $insert_data = array(
                        'photo' => $fileName,
                        'model_id' => $user_id,
                        'model_name' => 'user',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    if ($data['user_data'] != '' && $fileName != '') {
                        $update_id = $data['user_data']->id;
                        $this->Images_model->update($update_id, $update_data);
                    } else {
                        $this->Images_model->insert($insert_data);
                    }
                }
            }
            $up_data = array(
                'first_name' => $user_name,
                'salutation' => $salutation,
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $this->User_model->update($user_id, $up_data);
            $_SESSION['user_name']=$user_name;
            header("Location: profile");
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function update_contact() { //update user contact details based on login id
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $update_id = $_SESSION['user_id'];
            $phone = $this->input->post('accountPhone');
            $update_data = array(
                'phone1' => $phone,
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $this->User_model->update($update_id, $update_data);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function update_password() { //change user password
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $update_id = $_SESSION['user_id'];
            $old_pass = md5($this->input->post('pwCurrent'));
            $new_pass = md5($this->input->post('passwordNew'));
            $data['users'] = $this->User_model->get_by(array('id' => $update_id));
            if ($old_pass == ($data['users']->password)) {
                $update_data = array(
                    'password' => $new_pass,
                    'password_last_updated_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                $this->User_model->update($update_id, $update_data);
                $this->session->set_flashdata('success', 'Password changed successfully.');
            } else {
                $this->session->set_flashdata('error', 'Error: Wrong password. Please try again.');
            }
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function add_license() { //add licence to purchase licenced products
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles)) || $_SESSION['role_id'] == 1) {
            $user_id = ($this->input->post('user_id')) ? $this->input->post('user_id') : $_SESSION['user_id'];
            $organisation = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
            $organization_id = $organisation->organization_id;
            $license_id = $this->input->post('license_id');
            $license_no = $this->input->post('accountLicense');
            $dea_no = $this->input->post('accountDEA');
            $expire_date = $this->input->post('licenseExpiry');
            $your_date = date("M d, Y", strtotime($expire_date));
            $exp_date = date("Y-m-d", strtotime($expire_date));
            $state = $this->input->post('state');
            $insert_data = array(
                'user_id' => $user_id,
                'organization_id' => $organization_id,
                'license_no' => $license_no,
                'dea_no' => $dea_no,
                'expire_date' => $exp_date,
                'state' => $state,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            if(!empty($license_id) && $_SESSION['role_id'] == 1 ){
                $this->User_licenses_model->update($license_id, $insert_data);
                header("location: " . $_SERVER['HTTP_REFERER']);
                $this->NotificationEmails->sendUserLicenseAddedToAdmin('updated', $user_id, $license_no, $state, $exp_date, $dea_no);
            } else {
                $this->User_licenses_model->insert($insert_data);
                $this->session->set_flashdata("success", "License added successfully. ");
                $this->NotificationEmails->sendUserLicenseAddedToAdmin('added', $user_id, $license_no, $state, $exp_date, $dea_no);

                if($_SESSION['role_id'] == 1 ){
                    header("location: " . $_SERVER['HTTP_REFERER']);
                    exit;
                }
            }
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function delete_licence() { //delete user license
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $license_id = $this->input->post('licenseId');
            $this->User_licenses_model->delete($license_id);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function email_settings() { //user view their email settings details
        $roles = unserialize(ROLES_USERS);
        $roles_vendor = unserialize(ROLES_VENDORS);
        $roles_admin = unserialize(ROLES_ADMINS);
        if (isset($_SERVER['HTTP_REFERER'])) {
            if (strpos($_SERVER['HTTP_REFERER'], base_url()) != 0) {
                $this->session->set_flashdata("error", "");
                $this->session->set_flashdata("success", "");
            }
        }
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $data['email'] = $this->User_model->get_by(array('id' => $user_id));
            $data['locations'] = $this->User_location_model->get_many_by(array('user_id' => $user_id));
            for ($i = 0; $i < count($data['locations']); $i++) {

                $data['user_locations'][] = $this->Organization_location_model->get_by(array('id' => $data['locations'][$i]->organization_location_id));
            }
            $this->load->view('/templates/_inc/header', $data);
            $this->load->view('/templates/account/email-settings/index', $data);
            $this->load->view('/templates/_inc/footer');
        } else if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles_vendor))) {
            header("location: vendor-settings-dashboard");
        } else if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles_admin))) {
            header("location: superAdmins-Account");
        } else {
            $this->session->set_flashdata("error", "Please login to continue.");
            header("location: login");
        }
    }

    public function update_email_settings() { //update user email settings
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $update_id = $_SESSION['user_id'];
            $e1 = $this->input->post('e1');
            $e2 = $this->input->post('e2');
            $e3 = $this->input->post('e3');
            $e4 = $this->input->post('e4');
            $e5 = $this->input->post('e5');
            $e6 = $this->input->post('e6');
            $e7 = $this->input->post('e7');

            $update_data = array(
                'email_setting1' => $e1,
                'email_setting2' => $e2,
                'email_setting3' => $e3,
                'email_setting4' => $e4,
                'email_setting5' => $e5,
                'email_setting6' => $e6,
                'email_setting7' => $e7,
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $this->User_model->update($update_id, $update_data);
            header("Location:email-settings");
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function payments() { //user view their payments details
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $user = $this->User_model->get_by(array('id' => $user_id));
            if ($user->stripe_id) {
              /* JM: 8/15/18
              ** Adding conditional to prevent Stripe from erroring with NULL.
              */
                $customer = (isset($user->stripe_id)) ? $this->stripe->getCustomer($user->stripe_id) : NULL;

                $users_payments = $this->User_payment_option_model->get_many_by(['user_id' => $user_id]);

                foreach ($users_payments as $users_payment) {

                    //get payment methods
                    /* JM: 8/15/18
                    ** Adding conditional to prevent Stripe from erroring with NULL.
                    */
                    $payment_method = (isset($customer)) ? $customer->sources->retrieve($users_payment->token) : NULL;
                    $users_payment_obj = new stdClass();
                    $users_payment_obj->id = $users_payment->id;
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
                    $users_payment_obj->created_at = $users_payment->created_at;
                    $users_payment_obj->updated_at = $users_payment->update_at;

                    $data['users_payments'][] = $users_payment_obj;
                    unset($users_payment_obj);
                }
            }
           
            $organization_id = $this->Organization_groups_model->get_by(array('user_id' => $user_id))->organization_id;
            if(in_array($user->role_id, unserialize(ROLES_TIER2))){
                $account_parent_id = $this->Organization_model->get_by(array('id' => $organization_id))->admin_user_id;
                $parent = $this->User_model->get_by(array('id' => $account_parent_id));
                if ($parent->stripe_id) {
                    $data['users_parent_payments'] = $this->get_payment_methods($parent);
                }
            }
            if(in_array($user->role_id, unserialize(ROLES_TIER1))){
                $organization_accounts = $this->Organization_groups_model->organizationGroup_users($organization_id, '');
                $filtered_t2_accounts = array_filter($organization_accounts, function($account){
                   return in_array($account->role_id, unserialize(ROLES_TIER2)) && $account->stripe_id;
                });
                $data['users_child_payments'] = [];
                if(empty(!$filtered_t2_accounts)){
                    $data['users_child_payments'] = array_merge(...array_map(function ($account){
                        return $this->get_payment_methods($account);
                    },$filtered_t2_accounts));
                }
            }
            if ($user->stripe_id) {
                $data['users_payments'] = $this->get_payment_methods($user);
            }
            $data['user'] = $user;
            $data['locations'] = $this->User_location_model->get_many_by(array('user_id' => $user_id));
            for ($i = 0; $i < count($data['locations']); $i++) {

                $data['user_locations'][] = $this->Organization_location_model->get_by(array('id' => $data['locations'][$i]->organization_location_id));
            }
            $this->load->view('/templates/_inc/header', $data);
            $this->load->view('/templates/account/payments/index', $data);
            $this->load->view('/templates/_inc/footer');
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function add_card_details() { //user add credit card or debit card details
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $user = $this->User_model->get_by(array('id' => $user_id));
            $email = $user->email;
            $stripeId = $user->stripe_id;
            $token = $this->input->post('token');

            // Check for existing Stripe customer
            if (!$stripeId){
                // Add stripe customer
                $customer = ['email' => $email,
                    'description' => $user->first_name . ' ' . $user->last_name,];
                $customer = $this->stripe->addCustomer($customer);
                $stripeId = $customer->id;
                // Update DB
                $update_data = ['stripe_id' => $stripeId];
                $this->User_model->update($user_id, $update_data);
            }else{
                $customer = $this->stripe->getCustomer($stripeId);
            }

            try {
                // Add card to stripe
                $card = $customer->sources->create(["source" => $token]);

                // Add card to DB
                $insert_data = [
                    'user_id' => $user_id,
                    'payment_type' => 'card',
                    'token' => $card->id,
                    'cc_name' => $card->name,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                $this->User_payment_option_model->insert($insert_data);
                $data['insert_id'] = $this->db->insert_id();
                $data['payments'] = $this->User_payment_option_model->get_many_by(['user_id' => $user_id]);
                $this->session->set_flashdata("success", "Payment method added successfully");
            } catch (Exception $e) {
                $this->session->set_flashdata("error", $e->getMessage());
            }
                echo json_encode($data);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function add_bank_details() { //user add bank account  details
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $user = $this->User_model->get_by(array('id' => $user_id));
            $email = $user->email;
            $stripeId = $user->stripe_id;
            $public_token = $this->input->post('public_token');
            $account_id = $this->input->post('account_id');

            //validate bank account
            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => 'https://tartan.plaid.com/exchange_token',
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/x-www-form-urlencoded',
                ),
                CURLOPT_POSTFIELDS => http_build_query(array(
                    'client_id' => $this->config->item('plaid')['client-id'],
                    'secret' => $this->config->item('plaid')['secret'],
                    'public_token' => $public_token,
                    'account_id' => $account_id
                ))
            ));
            $response = curl_exec($ch);
            curl_close($ch);
            $tok = json_decode($response);
            $bankAccountToken = $tok->stripe_bank_account_token;

            //customer create
            $token = $this->input->post('token');

            // Check for existing Stripe customer
            if (!$stripeId){
                // Add stripe customer
                $customer = ['email' => $email];
                $customer = $this->stripe->addCustomer($customer);
                $stripeId = $customer->id;
                // Update DB
                $update_data = ['stripe_id' => $stripeId];
                $this->User_model->update($user_id, $update_data);
            }else{
                $customer = $this->stripe->getCustomer($stripeId);
            }

            // Add bank account to stripe
            $bankAccount = $customer->sources->create(["source" => $bankAccountToken]);

            // Add bank account to DB
            $bank_name = $this->input->post('bankname');
            $account_holder_name = $this->input->post('account_holder_name');
            $bank_acc4 = $this->input->post('bank_acc4');
            $routing_number = $this->input->post('routing_number');
            $insert_data = array(
                'user_id' => $user_id,
                'payment_type' => 'bank',
                'token' => $bankAccount->id,
                'bank_name' => $bank_name,
                'ba_routing_number' => $routing_number,
                'ba_account_number' => $bank_acc4,
                'cc_name' => $account_holder_name,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $this->User_payment_option_model->insert($insert_data);
            $data['insert_id'] = $this->db->insert_id();
            $data['payments'] = $this->User_payment_option_model->get_many_by(array('user_id' => $user_id));
            $this->session->set_flashdata("success", "Bank account added successfully");
            echo json_encode($data);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function update_card_details() { //update credit/debit card details
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $user = $this->User_model->get_by(array('id' => $user_id));

            $update_id = $this->input->post('card_id');
            $payments = $this->User_payment_option_model->get_by(array('id' => $update_id));
            $token = $payments->token;
            $exp_month = $this->input->post('exp_month');
            $exp_year = $this->input->post('exp_year');
            $paymentCardName = $this->input->post('updatepaymentCardName');
            try {
                //validate the card detials
                $customer = $this->stripe->getCustomer($user->stripe_id);
                $card = $customer->sources->retrieve($token);
                $card->name = $paymentCardName;
                $card->exp_month = $exp_month;
                $card->exp_year = $exp_year;
                $card->save();
                $update_data = array(
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                $this->User_payment_option_model->update($update_id, $update_data);
                $data['message'] = "";
            } catch (Exception $e) {
                $body = $e->getMessage();
                if (strpos($body, "You cannot set 'name'") === 0) {

                    $data['message'] = "Name cannot be blank.";
                } else {
                    $data['message'] = $e->getMessage();
                }
            }
            echo json_encode($data);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function update_bank_payment() { //update bank account details
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $useremail = $this->User_model->get_by(array('id' => $user_id));
            $email = $useremail->email;
            $update_id = $this->input->post('bank_id');
            $public_token = $this->input->post('public_token');
            $account_id = $this->input->post('account_id');
            //validate bank account
            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => 'https://tartan.plaid.com/exchange_token',
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/x-www-form-urlencoded',
                ),
                CURLOPT_POSTFIELDS => http_build_query(array(
                    'client_id' => $this->config->item('plaid')['client-id'],
                    'secret' => $this->config->item('plaid')['secret'],
                    'public_token' => $public_token,
                    'account_id' => $account_id
                ))
            ));
            $response = curl_exec($ch);
            curl_close($ch);
            $tok = json_decode($response);
            $bankAccountToken = $tok->stripe_bank_account_token;
            //customer create
            $token = $this->input->post('token');
            $customer = array(
                'email' => $email,
                'source' => $bankAccountToken,
            );
            $output = $this->stripe->addCustomer($customer);
            $customer_id = $output->id;
            $bank_name = $this->input->post('bankname');
            $account_holder_name = $this->input->post('account_holder_name');
            $bank_acc4 = $this->input->post('bank_acc4');
            $routing_number = $this->input->post('routing_number');
            $update_data = array(
                'user_id' => $user_id,
                'payment_type' => 'bank',
                'token' => $customer_id,
                'bank_name' => $bank_name,
                'ba_routing_number' => $routing_number,
                'ba_account_number' => $bank_acc4,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $this->User_payment_option_model->update($update_id, $update_data);

            $update_data = array(
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );

            try {
                // Stripe
                $customer = $this->stripe->getCustomer($useremail->stripe_id);
                $bank_account = $customer->sources->retrieve($token);
                $bank_account->account_holder_name = $account_holder_name;
                $bank_account->metadata = ['user_id' => $user_id,
                    'bank_name' => $bank_name,
                    'ba_routing_number' => $routing_number,
                    'ba_account_number' => $bank_acc4,
                    ];
                $bank_account->save();

                $this->User_payment_option_model->update($update_id, $update_data);
                $data['message'] = "";
            } catch (Exception $e) {
                $body = $e->getMessage();
                if (strpos($body, "You cannot set 'name'") === 0) {

                    $data['message'] = "Name cannot be blank.";
                } else {
                    $data['message'] = $e->getMessage();
                }
            }
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function delete_payment() { //delete user payments
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $user = $this->User_model->get_by(['id' => $user_id]);
            $stripeId = $user->stripe_id;
            $delete_id = $this->input->post('delete_id');
            $payment = $this->User_payment_option_model->get_by(['id' => $delete_id]);
            $paymentToken = $payment->token;
            if ($user_id != null) {
                $this->User_payment_option_model->delete($delete_id);
                try {
                    $customer = $this->stripe->getCustomer($stripeId);
                    $customer->sources->retrieve($paymentToken)->delete();
                } catch (\Stripe\Exception\InvalidRequestException $e) {
                  log_message('error', 'Stripe Source Deletion Failed: ' . $e->getMessage());
                }
            }

            $this->User_payment_option_model->delete($delete_id);
            $data['users_payments'] = $this->User_payment_option_model->get_many_by(array('user_id' => $user->id));
            $this->session->set_flashdata("success", "Payment method deleted successfully");
            $this->load->view('/templates/_inc/header');
            $this->load->view('/templates/account/payments/index', $data);
            $this->load->view('/templates/_inc/footer');
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function processStripeWebhook()
    {
        if ($this->input->post('type') === 'customer.source.deleted') {
            print_r($this->input->post('data'));
        }
    }

    public function company() { //view organization details
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $data['organ_id'] = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
            if ($data['organ_id'] != null) {
                $data['company_info'] = $this->Organization_location_model->get_by(array('organization_id' => $data['organ_id']->organization_id));
                $data['company_detail'] = $this->Organization_model->get_by(array('id' => $data['organ_id']->organization_id));
            }
            $data['locations'] = $this->User_location_model->get_many_by(array('user_id' => $user_id));
            for ($i = 0; $i < count($data['locations']); $i++) {

                $data['user_locations'][] = $this->Organization_location_model->get_by(array('id' => $data['locations'][$i]->organization_location_id));
            }
            $this->load->view('/templates/_inc/header', $data);
            $this->load->view('/templates/account/company/index', $data);
            $this->load->view('/templates/_inc/footer');
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function updateCompanyName() { // update company name
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $update_id = $this->input->post('company_id');
            $c_name = $this->input->post('companyName');
            if ($c_name != null) {
                $update_data = array(
                    'organization_name' => $this->input->post('companyName'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );

                $this->Organization_model->update($update_id, $update_data);
            }
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function updateCompanyAddress() { //update company address
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $update_id = $this->input->post('company_id');
            $address1 = $this->input->post('companyAddress1');
            $address2 = $this->input->post('companyAddress2');
            $city = $this->input->post('companyCity');
            $state = $this->input->post('state');
            $zip = $this->input->post('companyZip');
            $update_data = array(
                'address1' => $address1,
                'address2' => $address2,
                'city' => $city,
                'state' => $state,
                'zip' => $zip,
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $this->Organization_location_model->update($update_id, $update_data);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function upadateComapanyTaxId() { //update comapny Tax id details
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $update_id = $this->input->post('company_id');
            $tax = $this->input->post('companyTaxID');
            if ($tax != null) {
                $update_data = array(
                    'tax_id' => $tax,
                    'updated_at' => date('Y-m-d H:i:s'),
                );

                $this->Organization_model->update($update_id, $update_data);
            }
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function get_vendorDetails() { //get vendor details to change vendors in request or shopping lists
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $product_id = $this->input->post('product_id');
            $data['products'] = $this->Product_pricing_model->get_many_by(array('product_id' => $product_id));
            for ($i = 0; $i < count($data['products']); $i++) {
                $data['review'] = $this->Review_model->get_many_by(array('model_name' => 'vendor', 'model_id' => $data['products'][$i]->vendor_id));
                $vendors = $this->Vendor_model->get_many_by(array('id' => $data['products'][$i]->vendor_id));

                $data['products'][$i]->reviews = $data['review'];
                $data['products'][$i]->vendors = $vendors;
            }
            echo json_encode($data);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    // private function get_payment_methods($user){ //Live
    //     $customer = $this->stripe->getCustomer($user->stripe_id);
    //     $users_payments = $this->User_payment_option_model->get_many_by(['user_id' => $user->id]);
    //     $payment_methods = [];
    //     foreach ($users_payments as $users_payment) {
    //         //get payment methods
    //         $payment_method = $customer->sources->retrieve($users_payment->token);
    //         $users_payment_obj = new stdClass();
    //         $users_payment_obj->id = $users_payment->id;
    //         $users_payment_obj->user_id = $user->id;
    //         $users_payment_obj->payment_type = $payment_method->object;
    //         $users_payment_obj->card_type = $payment_method->brand;
    //         $users_payment_obj->exp_month = $payment_method->exp_month;
    //         $users_payment_obj->exp_year = $payment_method->exp_year;
    //         $users_payment_obj->cc_number = $payment_method->last4;
    //         $users_payment_obj->cc_name = $payment_method->name;
    //         $users_payment_obj->bank_name = $payment_method->bank_name;
    //         $users_payment_obj->ba_routing_number = $payment_method->routing_number;
    //         $users_payment_obj->ba_account_number = $payment_method->last4;
    //         $users_payment_obj->created_at = $users_payment->created_at;
    //         $users_payment_obj->updated_at = $users_payment->update_at;
    //         $payment_methods []= $users_payment_obj;
    //     }
    //     return $payment_methods;
    // }

    private function get_payment_methods($user)
    {
        $payment_methods = [];

        try {
            $customer = $this->stripe->getCustomer($user->stripe_id);
        } catch (\Exception $e) {
            log_message('error', "Stripe customer not found for user ID: {$user->id} - " . $e->getMessage());
            $customer = null;
        }

        $users_payments = $this->User_payment_option_model->get_many_by(['user_id' => $user->id]);

        foreach ($users_payments as $users_payment) {
            $payment_method = null;

            if ($customer && isset($customer->sources)) {
                try {
                    $payment_method = $customer->sources->retrieve($users_payment->token);
                } catch (\Exception $e) {
                    log_message('error', "Stripe token fetch failed for token: {$users_payment->token} - " . $e->getMessage());
                }
            }

            $users_payment_obj = new stdClass();
            $users_payment_obj->id = $users_payment->id;
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


            $payment_methods[] = $users_payment_obj;
        }

        return $payment_methods;
    }
}

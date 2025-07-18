<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ManageClasses extends MW_Controller {

    function __construct() {

        parent::__construct();
        $this->load->model('Location_inventories_model');
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
        $this->load->model('Order_promotion_model');
        $this->load->model('Vendor_groups_model');
        $this->load->model('User_payment_option_model');
        $this->load->model('Class_student_model');
        $this->load->model('Class_model');
        $this->load->model('Role_model');
        $this->load->library('email');
        $this->load->library('auth');
        $this->load->library('stripe');
        $this->load->helper('my_email_helper');
    }

    public function classes() {   //Manage classes
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $data['location_id'] = $this->User_location_model->get_many_by(array('user_id' => $user_id));
            for ($i = 0; $i < count($data['location_id']); $i++) {
                $data['user_locations'][] = $this->Organization_location_model->get_by(array('id' => $data['location_id'][$i]->organization_location_id));
            }
            $organisation = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
            $organization_id = $organisation->organization_id;
            $data['classes'] = $this->Class_model->get_many_by(array('organization_id' => $organization_id));
            $query = "SELECT a.id,a.class_name,a.created_at,b.student_id,count(b.student_id)as students from classes a LEFT JOIN class_students b on b.class_id=a.id where a.organization_id = '" . $organization_id . "'group by a.id ";
            $data['classes'] = $this->db->query($query)->result();
            if ($data['classes'] != null) {
                for ($j = 0; $j < count($data['classes']); $j++) {
                    $user_id = $data['classes'][$j]->student_id;
                    $data['classes'][$j]->order_count = 0;
                    if ($user_id != null) {
                        $data['classes'][$j]->order_count = count($this->Order_model->get_many_by(array('user_id' => $user_id, 'order_status !=' => 'Cancelled', 'restricted_order' => '1')));
                    }
                }
            }
            $this->load->view('/templates/_inc/header', $data);
            $this->load->view('/templates/account/classes/index', $data);
            $this->load->view('/templates/_inc/footer');
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function students() { // view students details based in assigned classes
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $class_id = $this->input->get('id');
            if ($class_id != null) {
                $organization_details=$this->Organization_groups_model->get_by(array('user_id' =>$_SESSION['user_id']));
                $data['class_name'] = $this->Class_model->get_by(array('id' => $class_id,'organization_id' =>$organization_details->organization_id));
                if (!isset($data['class_name'])) {
                    $this->session->set_flashdata('error', 'Invalid Entry');
                    header('Location: home');
                } else {
                    $data['location_id'] = $this->User_location_model->get_many_by(array('user_id' => $_SESSION['user_id']));
                    for ($i = 0; $i < count($data['location_id']); $i++) {
                        $data['user_locations'][] = $this->Organization_location_model->get_by(array('id' => $data['location_id'][$i]->organization_location_id));
                    }
                    $data['students'] = $this->Class_student_model->get_many_by(array('class_id' => $class_id));
                    $data['students_details'] = [];
                    $data['student_orders'] = array();
                    if ($data['students'] != null) {
                        for ($i = 0; $i < count($data['students']); $i++) {
                            $query = 'SELECT a.id,b.first_name,a.student_id,c.role_tier,d.photo FROM class_students a
                            LEFT JOIN users b ON a.student_id=b.id
                            LEFT JOIN roles c ON b.role_id=c.id
                            LEFT JOIN images d ON b.id=d.model_id and d.model_name="user"
                            where a.student_id=' . $data['students'][$i]->student_id . ' and a.class_id=' . $class_id;
                            $data['students_details'][$i] = $this->db->query($query)->result();
                        }
                    }
                    $product = 'SELECT b.id,b.order_status,b.created_at,b.total,c.photo,d.first_name, b.id as item_count FROM class_students a LEFT JOIN orders b ON a.student_id=b.user_id LEFT JOIN images c ON b.user_id=c.model_id and c.model_name="user" LEFT JOIN users d ON b.user_id=d.id where b.order_status!="Cancelled" and b.restricted_order="1" and a.class_id=' . $class_id;
                    $data['product_details'] = $this->db->query($product)->result();
                    //   FOR Assign-Students Model
                    $user_id = $_SESSION['user_id'];
                    if ($user_id != null) {
                        $organization = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
                        $organization_id = $organization->organization_id;
                        $query = "select *,u.id as student_id from users as u left join organization_groups as og on og.user_id = u.id left join class_students as cs on cs.student_id = u.id left join images as ui on ui.model_id=u.id and ui.model_name='user'  where og.organization_id = $organization_id and u.role_id = 10 and u.id not in (select student_id from class_students where class_id=$class_id) group by cs.student_id";
                        $data['select_students'] = $this->db->query($query)->result();
                    }
                }
            }
            $data['class_id'] = $class_id;  // NOTE : class_id is called for unassign-student.php MODAL
            $this->load->view('/templates/_inc/header', $data);
            $this->load->view('/templates/account/classes/c/index', $data);
            $this->load->view('/templates/_inc/footer');
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function search_StudentName() { //search students name through assign popup model
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            if ($user_id != null) {
                $organization = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
                $organization_id = $organization->organization_id;
            }
            if ($organization_id != null) {
                $search = $this->input->post('search');
                $class_id = $this->input->post('class_id');
                $query = "select *,u.id as student_id,u.first_name from users as u left join organization_groups as og on og.user_id = u.id left join class_students as cs on cs.student_id = u.id left join images as ui on ui.model_id=u.id and ui.model_name='user'  where  u.first_name like '%$search%' AND og.organization_id =$organization_id and u.role_id = 10 and u.id not in (select student_id from class_students where class_id=$class_id)";
                $data['select_students'] = $this->db->query($query)->result();
                echo json_encode($data['select_students']);
            }
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function Invite_aStudent() { //invite new student based on classes
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            if ($user_id != null) {
                $organization = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
                $organization_id = $organization->organization_id;
                $accountName = $this->input->post('accountName');
                $accountEmail = $this->input->post('accountEmail');
                $email_check = $this->User_model->get_by(array('email' => $accountEmail));
                $class_id = $this->input->post('class_id');
                if ($email_check == null) {
                    if ($organization_id != null) {
                        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
                        $token = array(); //remember to declare $pass as an array
                        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
                        for ($i = 0; $i < 30; $i++) {
                            $n = rand(0, $alphaLength);
                            if ($i < 8) {
                                $pass[] = $alphabet[$n];
                            }
                            $token[] = $alphabet[$n];
                        }
                        $register_confirm_token = implode($token);
                        $password = implode($pass);
                        $role_id = 10;
                        $insert_data = array(
                            'first_name' => $accountName,
                            'email' => $accountEmail,
                            'role_id' => $role_id,
                            'new_password' => $this->auth->hashPassword($password),
                            'confirmation_token' => $register_confirm_token,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        );
                        if ($insert_data != null) {
                            $new_user_id = $this->User_model->insert($insert_data);
                            $organization_group = array(
                                'user_id' => $new_user_id,
                                'organization_id' => $organization_id,
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                            );
                            $organization_group_id = $this->Organization_groups_model->insert($organization_group);
                            $insert_class = array(
                                'student_id' => $new_user_id,
                                'class_id' => $class_id,
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                            );
                            $class_insert = $this->Class_student_model->insert($insert_class);
                            $data['roles'] = $this->Role_model->get($role_id);
                            $role_name = $data['roles']->role_name;
                            $subject = 'Organization user invitation';
                            $message = "Hi,<br />"
                                    . " Welcome to the Matix marketplace. Please click below to confirm your organization student account and login with given details."
                                    . "<table>"
                                    . "<tr><td>Email:</td><td>$accountEmail</td></tr>"
                                    . "<tr><td>Password</td><td>$password</td></tr>"
                                    . "</table>"
                                    . "<br /><b>Note :</b> Please change the password once logged-in"
                                    . "" . "<a href='" . base_url() . "superadmin-organization-confirmation?register_confirm_token=" . $register_confirm_token . "' style='padding: 20px 40px 20px 40px;width:auto;display: block;text-decoration: none;border:0;text-align: center;font-weight: bold;font-size: 16px;font-family: Arial, sans-serif;color: #ffffff;background: #2893FF;border: 1px solid #317ED0;-moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius: 4px;line-height:normal;' class='button_link'>Login</a>";
                            $email_data = array(
                                'subject' => $subject,
                                'message' => $message
                            );
                            $body = $this->load->view('/templates/email/index.php', $email_data, TRUE);
                            $mail_status = send_matix_email($body, $subject, $accountEmail);
                        }
                        $this->session->set_flashdata('success', 'Invitation sent to the Student');
                        header('Location: students?id=' . $class_id);
                    }
                } else {
                    $this->session->set_flashdata('error', 'Email already exists. Please try again.');
                    header('Location: students?id=' . $class_id);
                }
            }
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: login");
        }
    }

    public function Adding_aStudent() { //assign a student through assign pop up model
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $student_id = $this->input->post('student_id');
            $class_id = $this->input->post('class_id');
            $classes = $this->Class_student_model->get_by(array('student_id' => $student_id, 'class_id' => $class_id));
            if ($classes == null) {
                if ($class_id != null) {
                    $insert_class = array(
                        'student_id' => $student_id,
                        'class_id' => $class_id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    $class_insert = $this->Class_student_model->insert($insert_class);
                }
            }
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function createnew_class() { //crate new class name
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $organisation = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
            $organization_id = $organisation->organization_id;
            $class_name = $this->input->post('className');
            $insert_class = array(
                'class_name' => $class_name,
                'organization_id' => $organization_id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $this->Class_model->insert($insert_class);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function update_class() { //update class name
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $update_id = $this->input->post('class_id');
            $class_name = $this->input->post('className');
            $update_class = array(
                'class_name' => $class_name,
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $this->Class_model->update($update_id, $update_class);
            header("Location: students?id=" . $update_id);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function remove_student() { //remove students from that class
        if (isset($_SESSION['user_id'])) {
            $delete_id = $this->input->post('student_id');
            $class_id = $this->input->post('class_id');
            $this->Class_student_model->delete($delete_id);
            $this->session->set_flashdata('success', 'Student(s) removed.');
            header("Location:students?id=" . $class_id);
        } else {
            $this->session->set_flashdata('success', 'Unauthorized to remove user(s).');
            header("Location:home");
        }
    }

    public function unassign_students() { //unassign selected student from the particular class
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $class_id = $this->input->post('class_id');
            $delete_id = explode(",", $class_id);
            $this->Class_student_model->delete_many($delete_id);
            header("Location:classes");
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function getstudent() { //get single students detail baed on student id
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $student_id = $this->input->post('student_id');
            $data['students'] = $this->User_model->get_by(array('id' => $student_id));
            $data['images'] = $this->Images_model->get_by(array('model_id' => $student_id, 'model_name' => 'user'));
            echo json_encode($data);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function getstudents() { //get selected students details
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $student = $this->input->post('student_id');
            $s_id = explode(",", $student);
            for ($i = 0; $i < count($s_id); $i++) {
                $class_students = $this->Class_student_model->get_by(array('id' => $s_id[$i]));
                $student_id = $class_students->student_id;
                $data['images'] = $this->Images_model->get_by(array('model_name' => 'user', 'model_id' => $student_id));
            }
            echo json_encode($data);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function approve_restricteditems() { //approve restricetd orders based on student assigned classes
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $order_id = $this->input->post('order_id');
            $approve_user_id = $_SESSION['user_id'];
            $data['orders'] = $this->Order_model->get_by(array('id' => $order_id));
            $user = $this->User_model->get_by(array('id' => $data['orders']->user_id));
            $user_name = $user->first_name;
            $email = $user->email;
            $order_delete = $data['orders']->id;
            $user_id = $data['orders']->user_id;
            $location_id = $data['orders']->location_id;
            $total = $data['orders']->total;
            $tax = $data['orders']->tax;
            $shipment_price = $data['orders']->shipping_price;
            $promo_discount = $data['orders']->promo_discount;
            $promocode_id = $data['orders']->promocode_id;
            $vendor_id = $data['orders']->vendor_id;
            $shipment_id = $data['orders']->shipment_id;
            $payment_id = $data['orders']->payment_id;
            $address1 = $data['orders']->address1;
            $address2 = $data['orders']->address2;
            $city = $data['orders']->city;
            $state = $data['orders']->state;
            $zip = $data['orders']->zip;
            $nickname = $data['orders']->nickname;
            $description = 'Order placed from Matixdental.com';
            //check user license who has to approve restricted orders
            $user_license = $this->User_licenses_model->get_by(array('state' => $state, 'user_id' => $approve_user_id, 'approved' => '1'));
            if ($user_license != null && $user_license !== "") {
                $end_date = $user_license->expire_date;
                $today_date = date('Y-m-d');
                if ($today_date <= $end_date) {
                    $vendor = $this->Vendor_model->get($vendor_id);
                    if ($vendor->vendor_type == '0') {
                        //independent vendor ordrers goes here...
                        $payments = $this->User_payment_option_model->get_by(array('id' => $payment_id));
                        $payment_token = $payments->token;
                        $payment_cost = $total * 100;

                        $payment_data = array(
                            'amount' => round($payment_cost),
                            'customer' => $payment_token,
                            'description' => $description
                        );

                        if ($vendor->payment_id != null && $vendor->payment_id != "") {
                            $payment_data['destination'] = $vendor->payment_id;
                        }
                        $output = $this->stripe->addCharge($payment_data);

                        $insert_data = array(
                            'order_status' => 'New',
                            'location_id' => $location_id,
                            'total' => $total,
                            'tax' => $tax,
                            'shipping_price' => $shipment_price,
                            'promo_discount' => $promo_discount,
                            'promocode_id' => $promocode_id,
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
                            'restricted_order' => '0',
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        );
                        $this->Order_model->update($order_id, $insert_data);
                        $insert_id = $order_id;
                        $data['order_items'] = $this->Order_items_model->get_many_by(array('order_id' => $order_id));
                        for ($i = 0; $i < count($data['order_items']); $i++) {
                            $update_order_id = $data['order_items'][$i]->id;
                            $product_id = $data['order_items'][$i]->product_id;
                            $price = $data['order_items'][$i]->price;
                            $qty = $data['order_items'][$i]->quantity;
                            $subtotal = $data['order_items'][$i]->total;
                            $insert_items = array(
                                'order_id' => $insert_id,
                                'item_status' => '0',
                                'shipment_id' => $shipment_id,
                                'shipping_price' => $shipment_price,
                                'product_id' => $product_id,
                                'vendor_id' => $vendor_id,
                                'price' => $price,
                                'quantity' => $qty,
                                'picked' => $qty,
                                'total' => $subtotal,
                                'restricted_order' => '0',
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s')
                            );
                            $this->Order_items_model->update($update_order_id, $insert_items);
                        }
                        $data['restricted_promos'] = $this->Order_promotion_model->get_many_by(array('order_id' => $order_id));
                        for ($i = 0; $i < count($data['restricted_promos']); $i++) {
                            $update_promo_id = $data['restricted_promos'][$i]->id;
                            $promoid = $data['restricted_promos'][$i]->promo_id;
                            $deletepromoid = $data['restricted_promos'][$i]->id;
                            $promo_discount = $data['restricted_promos'][$i]->discount_value;
                            $insert_promo = array(
                                'order_id' => $insert_id,
                                'user_id' => $user_id,
                                'promo_id' => $promoid,
                                'discount_value' => $promo_discount,
                                'restricted_order' => '0',
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s')
                            );
                            $this->Order_promotion_model->update($update_promo_id, $insert_promo);
                        }
                    } else {
                        //matix vendors order  goes here..
                        $payments = $this->User_payment_option_model->get_by(array('id' => $payment_id));
                        $payment_token = $payments->token;
                        $payment_cost = $total * 100;
                        try {
                            $payment_data = array(
                                'amount' => round($payment_cost),
                                'customer' => $payment_token,
                                'description' => $description
                            );

                            if ($vendor->payment_id != null && $vendor->payment_id != "") {
                                $payment_data['destination'] = $vendor->payment_id;
                                $payment_data['application_fee'] = round($payment_cost * 0.07);
                            }
                            $output = $this->stripe->addCharge($payment_data);
                            $insert_data = array(
                                'order_status' => 'New',
                                'location_id' => $location_id,
                                'total' => $total,
                                'tax' => $tax,
                                'shipping_price' => $shipment_price,
                                'promo_discount' => $promo_discount,
                                'promocode_id' => $promocode_id,
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
                                'restricted_order' => '0',
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s')
                            );
                            $this->Order_model->update($order_id, $insert_data);
                            $insert_id = $order_id;
                            $data['order_items'] = $this->Order_items_model->get_many_by(array('order_id' => $order_id));
                            for ($i = 0; $i < count($data['order_items']); $i++) {
                                $update_order_id = $data['order_items'][$i]->id;
                                $product_id = $data['order_items'][$i]->product_id;
                                $price = $data['order_items'][$i]->price;
                                $qty = $data['order_items'][$i]->quantity;
                                $subtotal = $data['order_items'][$i]->total;

                                $insert_items = array(
                                    'order_id' => $insert_id,
                                    'item_status' => '0',
                                    'shipment_id' => $shipment_id,
                                    'shipping_price' => $shipment_price,
                                    'product_id' => $product_id,
                                    'vendor_id' => $vendor_id,
                                    'price' => $price,
                                    'quantity' => $qty,
                                    'picked' => $qty,
                                    'total' => $subtotal,
                                    'restricted_order' => '0',
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s')
                                );
                                $this->Order_items_model->update($update_order_id, $insert_items);
                            }
                            $data['restricted_promos'] = $this->Order_promotion_model->get_many_by(array('order_id' => $order_id));
                            for ($i = 0; $i < count($data['restricted_promos']); $i++) {
                                $update_promo_id = $data['restricted_promos'][$i]->id;
                                $promoid = $data['restricted_promos'][$i]->promo_id;
                                $deletepromoid = $data['restricted_promos'][$i]->id;
                                $promo_discount = $data['restricted_promos'][$i]->discount_value;
                                $insert_promo = array(
                                    'order_id' => $insert_id,
                                    'user_id' => $user_id,
                                    'promo_id' => $promoid,
                                    'discount_value' => $promo_discount,
                                    'restricted_order' => '0',
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s')
                                );
                                $this->Order_promotion_model->update($update_promo_id, $insert_promo);
                            }
                        } catch (Exception $e) {
                            $this->session->set_flashdata('error', 'Invalid payment credentials.');
                            header("Location:view-pending?id=" . $order_id);
                        }
                    }
                    $user = $this->User_model->get_by(array('id' => $user_id));
                    if ($insert_id != "" || $insert_id != null) {
                        $vendor = $this->Vendor_model->get_by(array('id' => $vendor_id));
                        $vendor_email = $vendor->email;
                        $order_id = $insert_id;
                        $data['order_details'] = $this->Order_items_model->get_many_by(array('order_id' => $order_id));
                        $data['orders'] = $this->Order_model->get_by(array('id' => $order_id));
                        $location_id = $data['orders']->location_id;
                        $data['payments'] = $this->User_payment_option_model->get_by(array('user_id' => $user_id, 'id' => $data['orders']->payment_id));
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
                        $data['promos'] = $this->Order_promotion_model->get_many_by(array('order_id' => $order_id));
                        if ($data['promos'] != null) {

                            for ($k = 0; $k < count($data['promos']); $k++) {
                                $promocode = $this->Promo_codes_model->get_by(array('id' => $data['promos'][$k]->promo_id));
                                $data['promos'][$k]->promocode = $promocode;
                            }
                        }
                        if ($_SESSION['user_id'] != null) {
                            if ($user->email_setting2 == '1') {
                                $useremail = $user->email;
                                $subject = 'Order Confirmation';
                                $data['message'] = "Hi,<br /> Your request to purchase the following restricted items has been approved and your order has been submitted:<br/>";
                                $body = $this->load->view('/templates/email/order/index.php', $data, TRUE);
                                $mail_status = send_matix_email($body, $subject, $useremail);
                            }
                        }
                        if ($vendor_email != "") {
                            $subject = 'New Order Notification';
                            $data['message'] = "Hi,<br /> A Matix user has placed an order with your company. Please see below for details. As per your contract with Matix, you are responsible for fulfilling this purchase order immediately. Thank you";
                            $body1 = $this->load->view('/templates/email/order/index', $data, TRUE);
                            $mail_status = send_matix_email($body1, $subject, $vendor_email);
                        }
                    }
                    $this->session->set_flashdata("success", "Order Approved successfully");
                    header("Location: classes");
                } else {
                    $this->session->set_flashdata("error", "Unable to Approve this Order: License has expired.");
                    header("Location: view-pending?id=" . $order_id);
                }
            } else {
                $this->session->set_flashdata("error", "Please enter license information before Approve this Order.");
                header("Location: view-pending?id=" . $order_id);
            }
        } else {
            header("Location:home");
        }
    }

    public function reject_restricteditems() { //Reject restricetd orders based on student assigned classes
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $order_id = $this->input->post('order_id');
            $data['orders'] = $this->Order_model->get_by(array('id' => $order_id));
            $user = $this->User_model->get_by(array('id' => $data['orders']->user_id));
            $user_name = $user->first_name;
            $email = $user->email;
            $order_id = $data['orders']->id;
            $update_data = array(
                'restricted_order' => '-1',
                'updated_at' => date('Y-m-d H:i:s')
            );
            $this->Order_model->update($order_id, $update_data);
            $data['order_details'] = $this->Order_items_model->get_many_by(array('order_id' => $order_id));
            $vendor = $this->Vendor_model->get_by(array('id' => $data['orders']->vendor_id));
            $vendor_image = $this->Images_model->get_by(array('model_id' => $vendor_id[$i], 'model_name' => 'vendor'));
            $vendor_email = $vendor->email;
            $vendor_name = $vendor->name;
            $data['orders'] = $this->Order_model->get_by(array('id' => $order_id));
            $location_id = $data['orders']->location_id;
            $data['payments'] = $this->User_payment_option_model->get_by(array('user_id' => $data['orders']->user_id, 'id' => $data['orders']->payment_id));
            $data['shipping_address'] = $this->Organization_location_model->get_by(array('id' => $data['orders']->location_id));
            $data['shippment'] = $this->Shipping_options_model->get_by(array('id' => $data['orders']->shipment_id));
            $data['vendor_image'] = $this->Images_model->get_by(array('model_id' => $data['orders']->vendor_id, 'model_name' => 'vendor'));
            for ($k = 0; $k < count($data['order_details']); $k++) {
                $update_id = $data['order_details'][$k]->id;
                $data['vendor_details'] = $this->Vendor_model->get_by(array('id' => $data['order_details'][$k]->vendor_id));
                $product_image = $this->Images_model->get_by(array('model_id' => $data['order_details'][$k]->product_id, 'model_name' => 'products', 'image_type' => 'mainimg'));
                $product_pricing = $this->Product_pricing_model->get_by(array('product_id' => $data['order_details'][$k]->product_id, 'vendor_id' => $data['order_details'][$k]->vendor_id));
                $product = $this->Products_model->get_by(array('id' => $data['order_details'][$k]->product_id));
                $vendors = $this->Vendor_model->get_by(array('id' => $data['order_details'][$k]->vendor_id));
                $data['order_details'][$k]->product_image = $product_image;
                $data['order_details'][$k]->Product_details = $product_pricing;
                $data['order_details'][$k]->product = $product;
                $data['order_details'][$k]->vendor = $vendors;
                $updates_data = array(
                    'restricted_order' => '-1',
                    'updated_at' => date('Y-m-d H:i:s')
                );
                $this->Order_items_model->update($update_id, $updates_data);
            }
            $data['promos'] = $this->Order_promotion_model->get_many_by(array('order_id' => $order_id));
            if ($data['promos'] != null) {
                for ($k = 0; $k < count($data['promos']); $k++) {
                    $update_promoId = $data['promos'][$k]->id;
                    $promocode = $this->Promo_codes_model->get_by(array('id' => $data['promos'][$k]->promo_id));
                    $data['promos'][$k]->promocode = $promocode;
                    $updates_promodata = array(
                        'restricted_order' => '-1',
                        'updated_at' => date('Y-m-d H:i:s')
                    );
                    $this->Order_promotion_model->update($update_promoId, $updates_promodata);
                }
            }
            if ($_SESSION['user_id'] != null) {
                $subject = 'Restricted Items Status';
                $data['message'] = "Hi," . "<br /> Your request to purchase the following restricted items has been denied and your order has been cancelled .<br/>";
                $body = $this->load->view('/templates/email/order/index.php', $data, TRUE);
                $mail_status = send_matix_email($body, $subject, $email);
            }

            $order_update = $data['orders']->id;
            header("Location:classes");
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function view_restricted_items() { //view restricetd orders based on student assigned classes
        $roles = unserialize(ROLES_TIER1_2_AB);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id']))) {
            if ((in_array($_SESSION['role_id'], $roles))) {
                $order_id = $this->input->get('id');
                if ($order_id != null) {
                    $organization_details = $this->Organization_groups_model->get_by(array('user_id' => $_SESSION['user_id']));
                    if ($organization_details != null) {
                        $organization_id = $organization_details->organization_id;
                        $Restricted_order = $this->Order_model->get_by(array('id' => $order_id,'restricted_order' =>'1'));
                        $student_id = $Restricted_order->user_id;
                        $organizationCheck = $this->Organization_groups_model->get_by(array('user_id' => $student_id));
                        $studentOrganization_id = $organizationCheck->organization_id;
                        if ($studentOrganization_id == $organization_id) {
                            $data['order'] = $order_id;
                            $data['vendor_images'] = "";
                            $data['user_images'] = "";
                            $data['users'] = "";
                            $data['restricted_orders'] = $this->Order_items_model->get_many_by(array('order_id' => $order_id));
                            if ($data['restricted_orders'] != null) {
                                $data['locations'] = $this->Order_model->get_by(array('id' => $order_id));
                                $data['location'] = $this->Organization_location_model->get_by(array('id' => $data['locations']->location_id));
                                for ($i = 0; $i < count($data['restricted_orders']); $i++) {
                                    $data['vendors'] = $this->Vendor_model->get_by(array('id' => $data['restricted_orders'][$i]->vendor_id));
                                    $data['users'] = $this->User_model->get_by(array('id' => $data['locations']->user_id));
                                    $data['lists'] = $this->Class_student_model->get_by(array('student_id' => $data['locations']->user_id));
                                    $data['user_images'] = $this->Images_model->get_by(array('model_id' => $data['locations']->user_id, 'model_name' => 'user'));
                                    $data['vendor_images'] = $this->Images_model->get_by(array('model_id' => $data['restricted_orders'][$i]->vendor_id, 'model_name' => 'vendor'));
                                    $product_images = $this->Images_model->get_by(array('model_id' => $data['restricted_orders'][$i]->product_id, 'model_name' => 'products'));
                                    $products = $this->Products_model->get_by(array('id' => $data['restricted_orders'][$i]->product_id));
                                    $product_pricing = $this->Product_pricing_model->get_by(array('product_id' => $data['restricted_orders'][$i]->product_id, 'vendor_id' => $data['restricted_orders'][$i]->vendor_id));
                                    $data['restricted_orders'][$i]->products = $products;
                                    $data['restricted_orders'][$i]->Product_details = $product_pricing;
                                    $data['restricted_orders'][$i]->product_images = $product_images;
                                }
                                $data['promos'] = $this->Order_promotion_model->get_many_by(array('order_id' => $order_id));
                                if ($data['promos'] != null) {
                                    for ($k = 0; $k < count($data['promos']); $k++) {
                                        $promocode = $this->Promo_codes_model->get_by(array('id' => $data['promos'][$k]->promo_id));
                                        $data['promos'][$k]->promocode = $promocode;
                                    }
                                }
                                $this->load->view('/templates/_inc/header');
                                $this->load->view('/templates/account/classes/c/pending-order/index', $data);
                                $this->load->view('/templates/_inc/footer');
                            } else {
                                header("Location:classes");
                            }
                        } else {
                            $this->session->set_flashdata('error', 'Invalid Entry');
                            header('Location: home');
                        }
                    }
                }
            } else {
                header("Location:dashboard");
            }
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

}

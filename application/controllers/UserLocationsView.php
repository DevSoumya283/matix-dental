<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class UserLocationsView extends MW_Controller {

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
        $this->load->model('Shipping_options_model');
        $this->load->model('Images_model');
        $this->load->model('Order_return_model');
        $this->load->model('User_autosave_model');
        $this->load->model('User_licenses_model');
        $this->load->model('Location_inventories_model');
        $this->load->model('Request_list_model');
        $this->load->model('Role_model');
        $this->load->model('Request_list_activity_model');
        $this->load->helper('my_email_helper');
    }

    //add new location
    public function add_userLocation() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $data['organ_id'] = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
            $organization_id = $data['organ_id']->organization_id;
            $nickname = $this->input->post('nickName');
            $address1 = $this->input->post('address');
            $address2 = $this->input->post('unit');
            $state = $this->input->post('state');
            $zip = $this->input->post('zip');
            if ($organization_id != null) {
                $insert_data = array(
                    'organization_id' => $organization_id,
                    'nickname' => $nickname,
                    'address1' => $address1,
                    'address2' => $address2,
                    'zip' => $zip,
                    'city' => $this->input->post('city'),
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
                }
            }
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("Location: login");
        }
    }

    public function manageLocations() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $data['data_view'] = $this->input->post('data_view');
            if ($data['data_view'] == null || $data['data_view'] == "") {
                $data['data_view'] = "list";
            }
            $sortBy = $this->input->post('sortBy');
            $sort_field = "name";
            if ($sortBy != null) {
                $sort_field = $sortBy;
            }
            $data['sort_field'] = $sort_field;
            $data['location_id'] = $this->User_location_model->get_many_by(array('user_id' => $user_id));
            $data['organisation'] = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
            if ($sort_field == "name") {
                $ol_query = "select ol.* from user_locations ul, organization_locations ol where ol.id = ul.organization_location_id and ol.organization_id=" . $data['organisation']->organization_id . " and ul.user_id=$user_id order by ol.nickname asc";
                $data['user_locations'] = $this->db->query($ol_query)->result();
            } else if ($sort_field == "state") {
                $ol_query = "select ol.* from user_locations ul, organization_locations ol where ol.id = ul.organization_location_id and ol.organization_id=" . $data['organisation']->organization_id . " and ul.user_id=$user_id order by ol.state asc";
                $data['user_locations'] = $this->db->query($ol_query)->result();
            } else {
                $ol_query = "select ol.* from user_locations ul, organization_locations ol where ol.id = ul.organization_location_id and ol.organization_id=" . $data['organisation']->organization_id . " and ul.user_id=$user_id";
                $data['user_locations'] = $this->db->query($ol_query)->result();
            }
            // put users locations in the session
            $_SESSION['userLocations'] =  $data['user_locations'];

            $data['request_lists'] = $this->db->query("select * from request_lists")->result_array();
            $data['users'] = $this->db->query("select organization_location_id from user_locations")->result_array();
            $date_check = date("Y-m-01");
            $data['orders'] = $this->db->query("select location_id,total from orders where order_status != 'Cancelled' and restricted_order='0' and created_at >= '$date_check' and user_id=" . $user_id . " ")->result_array();
            $data['cart'] = $this->User_autosave_model->fetchCart($_SESSION['role_id'], $data['organisation']->organization_id, $user_id);
            if (isset($_SESSION['location_id'])) {
                $location_id = $_SESSION['location_id'];
            } else {
                $location_id = "all";
            }
            if ($location_id == "all") {
                for ($i = 0; $i < count($data['location_id']); $i++) {
                    if ($sort_field == "name") {
                        $ol_query = "select ol.* from user_locations ul, organization_locations ol where ol.id = ul.organization_location_id and ol.organization_id=" . $data['organisation']->organization_id . " and ul.user_id=$user_id order by ol.nickname asc";
                        $data['user_location'] = $this->db->query($ol_query)->result();
                    } else if ($sort_field == "state") {
                        $ol_query = "select ol.* from user_locations ul, organization_locations ol where ol.id = ul.organization_location_id and ol.organization_id=" . $data['organisation']->organization_id . " and ul.user_id=$user_id order by ol.state asc";
                        $data['user_location'] = $this->db->query($ol_query)->result();
                    } else {
                        $ol_query = "select ol.* from user_locations ul, organization_locations ol where ol.id = ul.organization_location_id and ol.organization_id=" . $data['organisation']->organization_id . " and ul.user_id=$user_id";
                        $data['user_location'] = $this->db->query($ol_query)->result();
                    }
                }
            } else {
                $data['user_location'] = $this->Organization_location_model->get_many_by(array('id' => $location_id));
            }


            for ($j = 0; $j < count($data['user_location']); $j++) {
                if ($data['cart'] != null) {
                    $cart = $data['cart']->cart;
                    $row = json_decode($cart);
                    // $tmpCart = json_decode($row);
                    $cart = [];

                    foreach ($row as $item) {
                        $cart[$item->rowid] = (array) $item;

                        if ($item->location_id == $data['user_location'][$j]->id) {
                            if (!(isset($data['user_location'][$j]->item_count))) {
                                $data['user_location'][$j]->item_count = 0;
                            }
                            if (!(isset($data['user_location'][$j]->item_total))) {
                                $data['user_location'][$j]->item_total = 0;
                            }
                            $data['user_location'][$j]->item_count += $item->qty;
                            $data['user_location'][$j]->item_total += ($item->price * $item->qty);
                        }
                    }

                    $_SESSION['cart_contents'] = $cart;
                }

                foreach ($data['request_lists'] as $items) {
                    if (isset($data['user_location'][$j]->id)) {
                        if ($items['location_id'] == $data['user_location'][$j]->id) {
                            if (!(isset($data['user_location'][$j]->request_count))) {
                                $data['user_location'][$j]->request_count = 0;
                            }
                            $data['user_location'][$j]->request_count += $items['quantity'];
                        }
                    }
                }
                foreach ($data['users'] as $locations) {
                    if (isset($data['user_location'][$j]->id)) {
                        if ($locations['organization_location_id'] == $data['user_location'][$j]->id) {
                            if (!(isset($data['user_location'][$j]->user_count))) {
                                $data['user_location'][$j]->user_count = 0;
                            }
                            $data['user_location'][$j]->user_count += 1;
                        }
                    }
                }
                if (!(isset($data['user_location'][$j]->order_total))) {
                    $data['user_location'][$j]->order_total = 0;
                }
                foreach ($data['orders'] as $orders) {
                    if (isset($data['user_location'][$j]->id)) {
                        if ($orders['location_id'] == $data['user_location'][$j]->id) {
                            if (!(isset($data['user_location'][$j]->order_total))) {
                                $data['user_location'][$j]->order_total = 0;
                            }
                            $data['user_location'][$j]->order_total += $orders['total'];
                        }
                    }
                }
                $data['inventory'] = "";
                if (isset($data['user_location'][$j]->id)) {
                    $data['inventory'] = $this->Location_inventories_model->get_many_by(array('location_id' => $data['user_locations'][$j]->id));
                }
                foreach ($data['inventory'] as $inventories) {
                    if (isset($data['user_location'][$j]->id)) {
                        if ($inventories->location_id == $data['user_location'][$j]->id) {
                            if (!(isset($data['user_location'][$j]->inventory_count))) {
                                $data['user_location'][$j]->inventory_count = 0;
                            }
                            $data['user_location'][$j]->inventory_count += $inventories->purchashed_qty;
                        }
                    }
                }
            }
            if ($sort_field == "YTD") {
                $data['user_location'] = sort_order_total($data['user_location']);
            }

            // Debugger::debug($data, 'Location data');
            $this->load->view('/templates/_inc/header', $data);
            $this->load->view('/templates/account/locations/index', $data);
            $this->load->view('/templates/_inc/footer');
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    //view single location veiew
    public function locationBasedOrdersList() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];

            $data['bcModel'] = $this->BuyingClub_model;
            $data['userLicenses'] = $this->User_licenses_model->loadValidLicenses($user_id, 1);
            $data['organization_role_id'] = $_SESSION['role_id']; // NOTE: Adding this Role id for Inviting the Users in MODAL
            $data['roles'] = $this->Role_model->loadAllRoles();
            $location_id = $this->input->get('location_id');
            if ($location_id != null) {
                $LocationCheck = $this->User_location_model->get_by(array('user_id' => $user_id, 'organization_location_id' => $location_id));
                if (!isset($LocationCheck)) {
                    $this->session->set_flashdata('error', 'Invalid Entry');
                    header('Location: home');
                } else {
                    $organization = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
                    $organization_id = $organization->organization_id;
                    $data['location_id'] = $location_id;
                    $data['orders'] = array();
                    //Orders Lists
                    $data['orders'] = $this->db->query("SELECT *,SUM(total) as order_total FROM orders where restricted_order='0' and user_id = " . $user_id . " and location_id = " . $location_id . "")->result_array();
                    // $data['request'] = $this->db->query("SELECT *,count(location_id) as list_count FROM request_lists WHERE user_id = " . $user_id . " AND location_id = " . $location_id . "")->result_array();

                    $request =  $this->Request_list_model->get_many_by(array('location_id' => $location_id));
                    $data['request'] = (array)$request;
                    Debugger::debug($data['request']);
                    $data['no_of_users'] = $this->db->query("SELECT *,count(organization_location_id) as user_count FROM user_locations WHERE  organization_location_id = " . $location_id . "")->result_array();
                    $data['inventory'] = $this->Location_inventories_model->get_many_by(array('location_id' => $location_id));
                    $data['location_name'] = $this->Organization_location_model->get_by(array('id' => $location_id));
                    $data['cart'] = $this->User_autosave_model->fetchCart($_SESSION['role_id'], $organization->organization_id, $user_id);
                    if ($data['cart'] != null) {
                        $cart = $data['cart']->cart;
                        $row = json_decode($cart);
                        foreach ($row as $item) {
                            if ($item->location_id == $location_id) {
                                $data['cart_count'] = $item->qty;
                            } else {
                                $data['cart_count'] = 0;
                            }
                        }
                    }
                    $data['order'] = $this->Order_model->get_many_by(array('location_id' => $location_id, 'restricted_order' => '0', 'user_id' => $user_id));
                    for ($i = 0; $i < count($data['order']); $i++) {
                        $data['vendors'] = $this->Vendor_model->get_by(array('id' => $data['order'][$i]->vendor_id));
                        $vendorImages = $this->Images_model->get_by(array('model_name' => 'vendor', 'model_id' => $data['order'][$i]->vendor_id));
                        $data['order'][$i]->vendor = $data['vendors'];
                        $data['order'][$i]->vendorImages = $vendorImages;
                    }
                    //Request lists
                    $data['location'] = $this->Request_list_model->get_many_by(array('user_id' => $user_id));
                    $data['request_product'] = $this->Request_list_model->get_many_by(array('location_id' => $location_id));
                    for ($i = 0; $i < count($data['request_product']); $i++) {
                        $product = $this->Products_model->get_by(array('id' => $data['request_product'][$i]->product_id));
                        $product_pricing = $this->Product_pricing_model->get_by(array('product_id' => $data['request_product'][$i]->product_id, 'vendor_id' => $data['request_product'][$i]->vendor_id));
                        $productImages = $this->Images_model->get_by(array('model_name' => 'products', 'model_id' => $data['request_product'][$i]->product_id));
                        $vendor = $this->Vendor_model->get_by(array('id' => $data['request_product'][$i]->vendor_id));
                        $data['request_product'][$i]->productImages = $productImages;
                        $data['request_product'][$i]->product = $product;
                        $data['request_product'][$i]->product_pricing = $product_pricing;
                        $data['request_product'][$i]->vendor = $vendor;
                    }
                    //Inventory Lists
                    $query = 'SELECT a.id,a.location_id,c.nickname,d.name,a.product_id,d.manufacturer,d.license_required,a.purchashed_qty,a.minimum_threshold,a.updated_at,e.photo FROM location_inventories a
                        LEFT JOIN organization_locations c ON a.location_id=c.id
                        LEFT JOIN products d ON a.product_id=d.id
                        LEFT JOIN images e ON a.product_id=e.model_id and model_name ="products"
                        where a.location_id=' . $location_id . ' group by a.product_id';
                    $data['category_products'] = $this->db->query($query)->result();
                    $data['selected_category'] = $this->input->post('categories');
                    if ($data['selected_category'] == null || $data['selected_category'] == "") {
                        $query = 'SELECT a.id,a.location_id,c.nickname,d.name,a.product_id,d.manufacturer,d.license_required,a.purchashed_qty,a.minimum_threshold,a.updated_at,e.photo FROM location_inventories a
                        LEFT JOIN organization_locations c ON a.location_id=c.id
                        LEFT JOIN products d ON a.product_id=d.id
                        LEFT JOIN images e ON a.product_id=e.model_id and model_name ="products"
                        where a.location_id=' . $location_id . ' group by a.product_id';
                        $data['inventory'] = $this->db->query($query)->result();
                    } else {
                        $query = 'SELECT a.id,a.location_id,c.nickname,d.name,a.product_id,d.manufacturer,d.license_required,a.purchashed_qty,a.minimum_threshold,a.updated_at,e.photo FROM location_inventories a
                        LEFT JOIN organization_locations c ON a.location_id=c.id
                        LEFT JOIN products d ON a.product_id=d.id
                        LEFT JOIN images e ON a.product_id=e.model_id and model_name ="products"
                        where d.category_id like \'%"' . $data['selected_category'] . '"%\' and a.location_id=' . $location_id . ' group by a.product_id';
                        $data['inventory'] = $this->db->query($query)->result();
                    }

                    $data['list_id'] = $this->input->get('location_id');
                    if (isset($data['list_id'])) {
                        $data['inventory_products'] = $this->Location_inventories_model->loadByLocation($data['list_id'], $data['selected_category']);
                        $data['category_products'] = $data['inventory_products'];
                    }

                    $data['products'] = [];
                    foreach($data['category_products'] as $category_product){
                        $data['products'][] = $this->Products_model->get_by(['id' => $category_product->product_id]);
                    }
                    $data['catId'] = [];
                    foreach($data['products'] as $product){
                        $data['catId'][] = explode(",", str_replace('"', '', $product->category_id));
                    }
                    $data['catIdCount'] = $data['catId'];
                    $classic_categories = $this->Category_model->get_many_by(array('parent_id' => 1));
                    for ($i = 0; $i < count($classic_categories); $i++) {
                        $count = 0;
                        for ($j = 0; $j < count($data['catIdCount']); $j++) {
                            if (in_array($classic_categories[$i]->id, $data['catIdCount'][$j])) {
                                $count += 1;
                            }
                        }
                        $classic_categories[$i]->count = $count;
                    }
                    $data['classics'] = $classic_categories;
                    $densist_categories = $this->Category_model->get_many_by(array('parent_id' => 2));
                    for ($i = 0; $i < count($densist_categories); $i++) {
                        $count = 0;
                        for ($j = 0; $j < count($data['catIdCount']); $j++) {
                            if (in_array($densist_categories[$i]->id, $data['catIdCount'][$j])) {
                                $count += 1;
                            }
                        }
                        $densist_categories[$i]->count = $count;
                    }
                    $data['dentists'] = $densist_categories;
                    //Manage Users
                    $organ_id = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
                    $data['organ_data'] = $this->Organization_groups_model->get_many_by(array('organization_id' => $organ_id->organization_id));
                    $data['user_data'] = $this->User_location_model->get_many_by(array('organization_location_id' => $location_id));
                    for ($i = 0; $i < count($data['organ_data']); $i++) {
                        $bFound = false;
                        for ($j = 0; $j < count($data['user_data']); $j++) {
                            if ($data['organ_data'][$i]->user_id == $data['user_data'][$j]->user_id) {
                                $data['users'] = $this->User_model->get_by(array('id' => $data['user_data'][$j]->user_id));
                                $roles = $this->Role_model->get_by(array('id' => $data['users']->role_id));
                                $userImages = $this->Images_model->get_by(array('model_name' => 'user', 'model_id' => $data['user_data'][$j]->user_id));
                                $data['user_data'][$j]->roles = $roles;
                                $data['user_data'][$j]->userImage = $userImages;
                                $data['user_data'][$j]->users = $data['users'];

                                $bFound = true;
                            }
                        }
                        if (!$bFound) {
                            $data['unassign_users'] = $this->User_model->get_many_by(array('id' => $data['organ_data'][$i]->user_id, 'status' => '1'));
                        }
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
                    for ($i = 0; $i < count($data['user_data']); $i++) {
                        $organization_id_group[] = $this->Location_inventories_model->get_by(array('location_id' => $data['user_data'][$i]->organization_location_id));
                    }
                    $query = "SELECT b.id as user_id,b.first_name,b.role_id,b.email,d.model_name,d.photo FROM organization_groups a  INNER JOIN  users b on a.user_id=b.id LEFT JOIN images d on d.model_name='user' and d.model_id=b.id WHERE a.organization_id=$organization_id and b.id not in (select user_id from user_locations where organization_location_id = $location_id)";
                    $data['organizationUsers'] = $this->db->query($query)->result();
                    $data['location_id'] = $location_id;  //using For assign-user.php MODAL
                    $data['organization_id'] = $organization_id; //using For assign-user.php MODAL
                    $user_id = $_SESSION['user_id'];
                    $data['locations'] = $this->User_location_model->get_many_by(array('user_id' => $user_id));
                    for ($i = 0; $i < count($data['locations']); $i++) {
                        $data['user_locations'][] = $this->Organization_location_model->get_by(array('id' => $data['locations'][$i]->organization_location_id));
                    }
                }
            }
            $this->load->view('/templates/_inc/header', $data);
            $this->load->view('/templates/account/locations/l/index', $data);
            $this->load->view('/templates/_inc/footer');
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

//update location nickname
    public function updateNickname() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $update_id = $this->input->post('location_id');
            $nickname = $this->input->post('locationName');
            if ($nickname != null) {
                $update_data = array(
                    'nickname' => $nickname,
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                $this->Organization_location_model->update($update_id, $update_data);
                header("Location: settings?location_id=" . $update_id);
            }
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function updateLocationAddress() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $update_id = $this->input->post('location_id');
            $update_data = array(
                'address1' => $this->input->post('locationAddress1'),
                'address2' => $this->input->post('locationAddress2'),
                'zip' => $this->input->post('locationZip'),
                'city' => $this->input->post('locationCity'),
                'state' => $this->input->post('state'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            if ($update_data != null) {
                $this->Organization_location_model->update($update_id, $update_data);
                header("Location: settings?location_id=" . $update_id);
            }
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function update_spendBudget() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $update_id = $this->input->post('location_id');
            $update_data = array(
                'spend_budget' => $this->input->post('locationBudget'),
                'budget_duration' => $this->input->post('locationBudgetRange'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $this->Organization_location_model->update($update_id, $update_data);
            header("Location: settings?location_id=" . $update_id);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function saveUserRole()
    {
        $this->Role_model->updateUserRole($this->input->post('userId'), $this->input->post('roleId'));

        echo json_encode(['success' => true]);
    }

}

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Home page
 */
class Home extends MW_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Category_model');
        $this->load->model('Products_model');
        $this->load->model('Product_pricing_model');
        $this->load->model('Vendor_model');
        $this->load->model('Prepopulated_list_model');
        $this->load->model('Prepopulated_product_model');
        $this->load->model('Review_model');
        $this->load->model('Order_model');
        $this->load->model('Images_model');
        $this->load->model('Page_model');
        $this->load->model('Promo_codes_model');
        $this->load->model('User_licenses_model');
        $this->load->model('User_location_model');
        $this->load->model('User_autosave_model');
        $this->load->model('Organization_location_model');
        $this->load->model('Organization_groups_model');
        $this->load->model('BuyingClub_model');
        $this->load->model('ApiSearch_model');
        $this->load->model('My_model');
        $this->load->model('Memc');
        $this->load->model('PDOhandler');
        $this->load->helper("url");
        $this->load->library('pagination');
        $this->load->library('cart');
    }

    public function terms_conditions() {
        $this->load->view('/templates/_inc/header');
        if($this->config->item('whitelabel')){
            $data['page'] = $this->Page_model->loadPage($this->config->item('whitelabel'), 'terms');
            $this->load->view('templates/static/holder.php', $data);
        } else {
            $this->load->view('templates/static/terms/index.php');
        }
        $this->load->view('/templates/_inc/footer');
    }

    public function privacy_policy() {
        $this->session->set_flashdata("error", "");
        $this->session->set_flashdata("success", "");
        $this->load->view('/templates/_inc/header');
        if($this->config->item('whitelabel')){
            $data['page'] = $this->Page_model->loadPage($this->config->item('whitelabel')->id, 'privacy');
            $this->load->view('templates/static/holder.php', $data);
        } else {
            $this->load->view('templates/static/privacy/index.php');
        }
        $this->load->view('/templates/_inc/footer');
    }

    public function returns_policy() {
        $this->load->view('/templates/_inc/header');
        if($this->config->item('whitelabel')){
            $data['page'] = $this->Page_model->loadPage($this->config->item('whitelabel')->id, 'returns');
            $this->load->view('templates/static/holder.php', $data);
        } else {
            $this->load->view('templates/static/returns/index.php');
        }
        $this->load->view('/templates/_inc/footer');
    }

    public function shipping_policy() {
        $this->load->view('/templates/_inc/header');
        if($this->config->item('whitelabel')){
            $data['page'] = $this->Page_model->loadPage($this->config->item('whitelabel')->id, 'shipping');
            $this->load->view('templates/static/holder.php', $data);
        } else {
            $this->load->view('templates/static/shipping/index.php');
        }
        $this->load->view('/templates/_inc/footer');
    }

    public function contact() {
        $this->load->view('/templates/_inc/header');
        if($this->config->item('whitelabel')){
            $data['page'] = $this->Page_model->loadPage($this->config->item('whitelabel')->id, 'contact');
            $this->load->view('templates/static/holder.php', $data);
        } else {
            $this->load->view('templates/static/contact/index.php');
        }
        $this->load->view('/templates/_inc/footer');
    }

    public function faq() {
        $this->load->view('/templates/_inc/header');
        if($this->config->item('whitelabel')){
            $data['page'] = $this->Page_model->loadPage($this->config->item('whitelabel')->id, 'faq');
            $this->load->view('templates/static/holder.php', $data);
        } else {
            $this->load->view('templates/static/faq/index.php');
        }
        $this->load->view('/templates/_inc/footer');
    }

    public function home() {
        $data['letters'] = $this->getManufacturerLetters();
        $this->load->view('/templates/_inc/header', $data);
        if($this->config->item('whitelabel')){
            $data['page'] = $this->Page_model->loadPage($this->config->item('whitelabel')->id, 'home');
            $this->load->view('templates/static/home_holder.php', $data);
        } else {
            $this->load->view('templates/static/home/index.php');
        }
        $this->load->view('/templates/_inc/footer');
    }

    public function static_home() {
        $data['letters'] = $this->getManufacturerLetters();
        $this->load->view('/templates/_inc/header', $data);
        if($this->config->item('whitelabel')){
            $data['page'] = $this->Page_model->loadPage($this->config->item('whitelabel')->id, 'home');
            $data['page']->hero = (!empty($data['page']->hero)) ? $data['page']->hero : 'banner-home.jpg';
            $heroType = explode('/', mime_content_type(FCPATH . 'assets/img/heros/' . $data['page']->hero))[0];
            $data['herotype'] = (!empty($data['page']->hero)) ? $heroType : 'image';
            $showDefaultIfNotSet = true;
            $data['categoryLinks'] = $this->Page_model->loadHomeCategories($this->config->item('whitelabel')->id, $showDefaultIfNotSet);
            $this->load->view('templates/static/home_holder.php', $data);
        } else {
            $this->load->view('templates/static/home/index.php');
        }
        $this->load->view('/templates/_inc/footer');
    }

    public function how_it_works() {
        $this->load->view('/templates/_inc/header');
        if($this->config->item('whitelabel')){
            $data['page'] = $this->Page_model->loadPage($this->config->item('whitelabel')->id, 'about');
            $this->load->view('templates/static/holder.php', $data);
        } else {
            $this->load->view('templates/static/how-it-works/index.php');
        }
        $this->load->view('/templates/_inc/footer');
    }

    public function mission() {
        $this->load->view('/templates/_inc/header');
        if($this->config->item('whitelabel')){
            $data['page'] = $this->Page_model->loadPage($this->config->item('whitelabel')->id, 'mission');
            $this->load->view('templates/static/holder.php', $data);
        } else {
            $this->load->view('templates/static/mission/index.php');
        }
        $this->load->view('/templates/_inc/footer');
    }

    public function index() { //default home page view with product details
        // Redirect to /home if not already there
        if($_SERVER['REQUEST_URI'] == '/')
        {
            header("Location: /home", true, 301);
            exit();
        }

        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $organization = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
            $autosave_data = $this->User_autosave_model->fetchCart($_SESSION['role_id'], $organization->organization_id, $user_id);
            $assigned_user_locations = $this->User_location_model->get_many_by(array('user_id' => $user_id));
            $cart_data = $this->cart->contents();
            if ($autosave_data != null) { //get user cart values,insert into cart session
                $carts = $autosave_data->cart;
                $row = json_decode($carts);
                if ($cart_data == null) {
                    if ($row != null) {
                        foreach ($row as $item) {
                            for ($i = 0; $i < count($assigned_user_locations); $i++) {
                                if ($item->location_id == $assigned_user_locations[$i]->organization_location_id) {
                                    $id = $item->id;
                                    $qty = $item->qty;
                                    $name = $item->name;
                                    $price = $item->price;
                                    $pro_id = $item->pro_id;
                                    $location_id = $item->location_id;
                                    $ven_id = $item->ven_id;
                                    $row_id = $item->rowid;
                                    $cart_data = array(
                                        'id' => $id,
                                        'qty' => $qty,
                                        'name' => $name,
                                        'price' => $price,
                                        'pro_id' => $pro_id,
                                        'location_id' => $location_id,
                                        'status'=> 0,
                                        'ven_id' => $ven_id,
                                    );
                                    $this->cart->insert($cart_data);
                                }
                            }
                        }
                    }
                    $cart_data = $this->cart->contents();
                }
            }
        }
        $page = 0;
        $category = $this->input->get("category");
        $vendor_id = $this->input->get("vendor_id");
        $manufacturer = $this->input->get("manufacturer");
        $list_id = $this->input->get("listid");
        $procedure = $this->input->get("procedure");
        $page = $this->input->get("page");
        $page = (empty($page)) ? '0' : $page;
        $perPage = $this->input->get("per_page");
        $perPage = (empty($perPage)) ? 10 : $perPage;
        $start = $perPage * $page;
        $end = ($page + 1) * $perPage;

        $data['start'] = $start;
        $data['end'] = $end;
        $data['per_page'] = $per_page;
        $count_products = false;

        $result = $this->ApiSearch_model->run($category,
                                              $manufacturer,
                                              $vendor_id,
                                              $procedure,
                                              $list_id,
                                              $licenseRequired,
                                              $purchased,
                                              $search_data,
                                              $start,
                                              $perPage,
                                              $option);

        // Debugger::debug($result);
        // Debugger::debug($result['totalResults']);

        // Debugger::debug($perPage);

        $data['products'] = $result['results'];
        $total_products = $result['totalResults'];
        $productIds = $result['productIds'];
        $data['pages'] = ceil($result['totalResults'] / $perPage);

        $data['cat_id'] = $category;
        $data['manufacturer'] = $manufacturer;
        $data['procedure'] = $procedure;
        $data['list_id'] = $list_id;
        $data['vendor_id'] = $vendor_id;

        Debugger::debug($data['pages'], 'pages');
        if (isset($_SESSION['userLocations'])) {
            $locationIds = [];
            foreach ($_SESSION['userLocations'] as $k => $location) {
                $locationIds[] = $location->id;
            }

            // list orders for all locations user is associated with
            if(!empty($locationIds)){
                $data['orders'] = $this->Order_model->get_many_by(array('location_id' => $locationIds, 'restricted_order' => '0'));
            }
            // $data['orders'] = $this->Order_model->get_many_by(array('user_id' => $_SESSION["user_id"], 'restricted_order' => '0'));

        }


        $data['buyingClubs'] = $_SESSION['user_buying_clubs'];

        if (isset($_SESSION["user_id"])) {
            $user_id = $_SESSION['user_id'];
            $users = $this->User_model->get_by(array('id' => $user_id));
            $_SESSION['role_id'] = $users->role_id;
            $data['locations'] = $this->User_location_model->get_many_by(array('user_id' => $user_id));
            for ($i = 0; $i < count($data['locations']); $i++) {
                $data['user_locations'][] = $this->Organization_location_model->get_by(array('id' => $data['locations'][$i]->organization_location_id));
            }
        }
        // $data['category'] = $this->Category_model->get_many_by(array('parent_id' => ''));

            $data['bcModel'] = $this->BuyingClub_model;
        if(!empty($_SESSION['user_buying_clubs'])){
            Debugger::debug($_SESSION['user_buying_clubs'], 'user_buying_clubs', false, 'buyingClubs');
            // make class available to template
            $data['bcModel'] = $this->BuyingClub_model;
            // get buying club prices
            $data['bcPrices'] = $this->BuyingClub_model->getBuyingClubPrices($_SESSION['user_buying_clubs'], $productIds);
            Debugger::debug($_SESSION['bcPrices'], 'bcPrices', false, 'buyingClubs');

            $data['buyingClubs'] = $_SESSION['user_buying_clubs'];
        }

        if(!empty($user_id) &! $this->User_model->can($_SESSION['user_permissions'], 'is-admin') &! $this->User_model->can($_SESSION['user_permissions'], 'is-vendor')){
            $data['userLicenses'] = $this->User_licenses_model->loadValidLicenses($user_id, 1);
            if(in_array($_SESSION['role_id'], unserialize(ROLES_TIER1_2)) && empty($data['userLicenses'])){
               $this->session->set_flashdata("success", "A valid state license must be verified for purchasing items which require a license.  <a href=\"/profile\" class=\"link\">Manage license.</a>");
            }
        }


        $data['letters'] = $this->getManufacturerLetters();
        // Debugger::debug($data['letters']);
        $data['start'] = $start;
        $data['end'] = $end;
        $data['pages_count'] = $total_products;
        $this->load->view('/templates/_inc/header', $data);
        $this->load->view('/templates/browse/index', $data);
        $this->load->view('/templates/_inc/footer');
    }

    public function set_session() { // maintain user selected loction in session
        if (isset($_SESSION['user_id'])) {
            $_SESSION['location_id'] = "";
            $location_id = $this->input->post('location_id');
            $_SESSION['location_id'] = $location_id;
        }
    }

    public function get_cart_data() { //get user cart values based ,user selectd shopping location
        if (isset($_SESSION['user_id'])) {
            if (isset($_SESSION['location_id'])) {
                $location_id = $_SESSION['location_id'];
            } else {
                $location_id = "all";
            }
            $user_id = $_SESSION['user_id'];
            $organization = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
            $organization_id = $organization->organization_id;
            $locations = $this->User_location_model->get_many_by(array('user_id' => $user_id));
            if ($locations != null) {
                $cart_details = $this->User_autosave_model->fetchCart($_SESSION['role_id'], $organization_id, $user_id);
                if ($cart_details != null || $cart_details != "") {
                    $cart = $cart_details->cart;
                    $row = json_decode($cart);
                    if ($row != null) {
                        if ($location_id == "all") { //if location is not
                            for ($i = 0; $i < count($locations); $i++) {
                                $data['user_locations'][] = $this->Organization_location_model->get_by(array('id' => $locations[$i]->organization_location_id));
                            }
                            foreach ($row as $item) {
                                for ($j = 0; $j < count($data['user_locations']); $j++) {
                                    if ($item->location_id == $data['user_locations'][$j]->id && $item->status == 0) {
                                        if (!(isset($data['user_locations'][$j]->item_count))) {
                                            $data['user_locations'][$j]->item_count = 0;
                                            $data['user_locations'][$j]->name = $data['user_locations'][$j]->nickname;
                                            $data['user_locations'][$j]->id = $data['user_locations'][$j]->id;
                                        }
                                        $data['user_locations'][$j]->item_count += $item->qty;
                                    } else {
                                        $data['user_locations'][$j]->name = $data['user_locations'][$j]->nickname;
                                        $data['user_locations'][$j]->id = $data['user_locations'][$j]->id;
                                    }
                                }
                            }
                        } else { // get cart values based on shopping location
                            $data['user_locations'] = new stdClass();
                            $data['user_locations']->item_count = 0;
                            foreach ($row as $item) {
                                if ($location_id == $item->location_id && $item->status == 0) {
                                    $locations = $this->Organization_location_model->get_by(array('id' => $location_id));
                                    if (!(isset($data['user_locations']->item_count))) {
                                        $data['user_locations']->item_count = 0;
                                    }

                                    $data['user_locations']->name = $locations->nickname;
                                    $data['user_locations']->id = $locations->id;
                                    $data['user_locations']->item_count += $item->qty;
                                } else {
                                    $locations = $this->Organization_location_model->get_by(array('id' => $location_id));
                                    $data['user_locations']->name = $locations->nickname;
                                    $data['user_locations']->id = $locations->id;
                                }
                            }
                        }
                    } else {
                        if ($location_id != "all") {
                            $data['user_locations'] = new stdClass();
                            $data['user_locations']->item_count = 0;
                            $locations = $this->Organization_location_model->get_by(array('id' => $location_id));
                            $data['user_locations']->name = $locations->nickname;
                            $data['user_locations']->id = $locations->id;
                        } else {
                            for ($i = 0; $i < count($locations); $i++) {
                                $data['user_locations'][] = $this->Organization_location_model->get_by(array('id' => $locations[$i]->organization_location_id));
                            }
                            for ($j = 0; $j < count($data['user_locations']); $j++) {
                                if (!(isset($data['user_locations'][$j]->item_count))) {
                                    $data['user_locations'][$j]->item_count = 0;
                                    $data['user_locations'][$j]->name = $data['user_locations'][$j]->nickname;
                                    $data['user_locations'][$j]->id = $data['user_locations'][$j]->id;
                                }
                            }
                        }
                    }
                } else {
                    if ($location_id != "all") {

                        $data['user_locations'] = new stdClass();
                        $data['user_locations']->item_count = 0;
                        $locations = $this->Organization_location_model->get_by(array('id' => $location_id));
                        $data['user_locations']->name = $locations->nickname;
                        $data['user_locations']->id = $locations->id;
                    } else {

                        for ($i = 0; $i < count($locations); $i++) {
                            $data['user_locations'][] = $this->Organization_location_model->get_by(array('id' => $locations[$i]->organization_location_id));
                        }
                        for ($j = 0; $j < count($data['user_locations']); $j++) {
                            if (!(isset($data['user_locations'][$j]->item_count))) {
                                $data['user_locations'][$j]->item_count = 0;
                                $data['user_locations'][$j]->name = $data['user_locations'][$j]->nickname;
                                $data['user_locations'][$j]->id = $data['user_locations'][$j]->id;
                            }
                        }
                    }
                }
            } else {
                $data['error'] = "You dont have any locations";
            }
            echo json_encode($data);
        } else {
            header("Location:home");
        }
    }

    public function login() {
        if (isset($_SESSION['user_id'])) {
            unset($_SESSION['user_role']);
            unset($_SESSION['user_privilege']);
            unset($_SESSION['user_id']);
            unset($_SESSION['user_name']);
            $this->session->sess_destroy();
        }
        $this->load->view('/templates/login/index');
    }

    public function get_category() { //get Acrylics family sub categories to show in browse dropdown.
        if($this->config->item('whitelabel') && $this->config->item('limit_to_vendor_products')){
            $data['categories'] = $this->Category_model->getCategories(1, $this->config->item('whitelabel_vendor_id'));
            // $data['category'] = $this->Category_model->getCategoriesForVendor(1, $this->config->item('whitelabel_vendor_id'));
        } else {
            $data['categories'] = $this->Category_model->getCategories('1');
        }
        echo json_encode($data);
    }

    public function get_category_children() { //get child categories based on,user selected parent category
        $parent_id = $this->input->get("parent");
        $category = $this->Category_model->getCategories($parent_id);
        echo json_encode($category);
    }

    public function get_category_parents() { //get parent categories based on,user selected parent category
        $category_id = $this->input->get("category");
        // Debugger::debug($category_id);
        // stop ajax error if no category
        if(!empty($category_id)) {
            $category = $this->Category_model->getCategoryParents($category_id);
            echo json_encode($category);
        }
    }

    public function get_vendors() { //get all active vendors
        $data['vendors'] = $this->Vendor_model->get_many_by(array('active' => 1));
        echo json_encode($data);
    }

    public function get_manufactures()
    { //get all manufactures
        $letters = $this->getManufacturerLetters();
        $startingLetter = $this->input->post("startingLetter");
        // Debugger::debug($letters);
        // Debugger::debug($startingLetter);
        if(!in_array($startingLetter, $letters)){
            $startingLetter = $letters[0];
            if(is_numeric($startingLetter)){
                $startingLetter = '0-9';
            }
        }
        $sql = "SELECT DISTINCT manufacturer
                FROM products AS p
                JOIN product_pricings AS pp
                    ON p.id = pp.product_id
                WHERE 1 = 1
                ";

        if($startingLetter){
            $sql .= "AND manufacturer REGEXP '^[$startingLetter]'
                    ";
        }
        if($this->config->item('whitelabel_vendor_id') && $this->config->item('limit_to_vendor_products')){
            $sql .= "AND pp.vendor_id = " . $this->config->item('whitelabel_vendor_id');
        }

        // Debugger::debug($sql);
        // Debugger::debug($this->config->item('whitelabel_vendor_id'));

        $manufacturers = $this->db->query($sql)->result();

        // Debugger::debug($manufacturers);

        $data['manufacturer'] = $manufacturers;
        $data['letters'] = $letters;
        echo json_encode($data);
    }

    public function getManufacturerLetters()
    {
        $sql = "SELECT DISTINCT LEFT(p.manufacturer, 1) as letter
                FROM products AS p
                JOIN product_pricings AS pp
                    ON p.id = pp.product_id
                ";

        if($this->config->item('whitelabel_vendor_id') && $this->config->item('limit_to_vendor_products')){
            $sql .= "WHERE pp.vendor_id = " . $this->config->item('whitelabel_vendor_id');
        }

        $sql .= " ORDER BY letter";
        // Debugger::debug($sql);
        $result = $this->db->query($sql)->result();

        // Debugger::debug($result);
        $firstLetters = [];

        foreach($result as $k => $letter){
            $firstLetters[] = $letter->letter;
        }

        // Debugger::debug($firstLetters);

        return $firstLetters;
    }

    public function get_procedures()
    { //get all product procedures
        $params = [':vendor_id' => $this->config->item('whitelabel_vendor_id')];

        $sql = "SELECT p.product_procedures
                FROM products AS p
                JOIN product_pricings AS pp
                    ON p.id = pp.product_id
                WHERE p.product_procedures IS NOT NULL
                AND p.product_procedures != ''
                ";
        if($this->config->item('limit_to_vendor_products')){
            $sql .= "AND pp.vendor_id = :vendor_id
                    ";
        }
        $sql .= "GROUP BY product_procedures";

        $data['procedures'] = $this->PDOhandler->query($sql, $params);
        Debugger::debug($data['procedures']);
        echo json_encode($data);
    }

    //this is superAdmin prepopulated lists
    public function get_product_lists()
    {
        $params = [':vendor_id' => $this->config->item('whitelabel_vendor_id')];
        $sql = "SELECT DISTINCT pl.*
                FROM prepopulated_lists AS pl
                JOIN prepopulated_products AS pp
                    ON pl.id = pp.list_id
                JOIN product_pricings AS pps
                    ON pp.product_id = pps.product_id
                WHERE pl.user_id = 0
                ";

        if($this->config->item('limit_to_vendor_products')){
            $sql .= "AND pps.vendor_id = :vendor_id
                    ";
        }
        $sql .= "ORDER BY pl.listname ASC";

        $data['shopping_list'] = $this->PDOhandler->query($sql, $params);

        echo json_encode($data);
    }

    public function get_vendor_price() { //get vendor pricing, products add to cart
        $vendor_id = $this->input->post("vendor_id");
        $product_id = $this->input->post("product_id");
        $data['product_price'] = $this->Product_pricing_model->get_by(array('product_id' => $product_id, 'vendor_id' => $vendor_id));
        if(!empty($_SESSION['user_buying_clubs'])){
            
            // $retailPrice =
            $bcPrices = $this->BuyingClub_model->getBuyingClubPrices($_SESSION['user_buying_clubs'], [$product_id]);
            $bcBestPrice = $this->BuyingClub_model->getBestPrice($product_id, $vendor_id, $bcPrices, $_SESSION['user_buying_clubs'], $data['product_price']->retail_price);

            if(!empty($bcBestPrice) && $bcBestPrice > 0){
                $data['product_price']->price = $bcBestPrice;
                $data['clubPrice'] = true;
            }
        }

        $user_id = $_SESSION['user_id'];
        $data['locations'] = $this->User_location_model->get_many_by(array('user_id' => $user_id));
        $organization = $this->Organization_groups_model->get_by(array('user_id' => $user_id));

        $organization_licenses = $this->User_licenses_model->get_many_by(['organization_id' => $organization->organization_id]);

        for ($i = 0; $i < count($data['locations']); $i++) {
            $organization_location = $this->Organization_location_model->get_by(array('id' => $data['locations'][$i]->organization_location_id));

            foreach ($organization_licenses as $organization_license){
                if ($organization_license->state == $organization_location->state &&
                    $organization_license->approved == 1 &&
                    $organization_license->expire_date >= date('Y-m-d')){
                    $organization_location->license = $organization_license;
                    break;
                }
            }

            $data['user_locations'][] = $organization_location;
        }

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
                if ($row != null) {
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
                                $data['user_locations'][$j]->item_total += ($item->price * $item->qty);
                            }
                        }
                    }
                }
            } else {
                $data['user_locations'] = new stdClass();
                $locations = $this->Organization_location_model->get_by(array('id' => $location_id));
                $data['user_locations']->id = $locations->id;
                $data['user_locations']->nickname = $locations->nickname;
                $data['user_locations']->item_count = 0;
                $data['user_locations']->item_total = 0;
                if ($row != null) {
                    foreach ($row as $item) {
                        if ($location_id == $item->location_id && $item->status == 0) {
                            if (!(isset($data['user_locations']->item_count))) {
                                $data['user_locations']->item_count = 0;
                            }
                            if (!(isset($data['user_locations']->item_total))) {
                                $data['user_locations']->item_total = 0;
                            }
                            $data['user_locations']->item_count += $item->qty;
                            $data['user_locations']->item_total += ($item->price * $item->qty);
                        }
                    }
                }
            }
        }


        echo json_encode($data);
    }

    public function rebuildCatNav()
    {
        $returnItems = [];
        $currentCategory = $this->input->get("category");
        $tree = $this->Category_model->getCategoryParents($currentCategory);
        Debugger::debug($tree);
        // take the root off the tree
        $rootCat = array_shift($tree);

        $returnItems['parent'] = ($rootCat->category_id == $this->input->get("category")) ? 'classic' : 'dental';

        if(empty($tree)){
            if($this->config->item('whitelabel') && $this->config->item('whitelabel')->limit_to_vendor_products){
                $tree[] = $this->Category_model->getCategories($this->input->get("category"), $this->config->item('whitelabel')->vendor_id);
                // $data['category'] = $this->Category_model->getCategoriesForVendor(1, $this->config->item('whitelabel_vendor_id'));
            } else {
                $tree[] = $this->Category_model->getCategories($this->input->get("category"));
            }
        }
        $hasChildren = (is_array($tree[0])) ? false : true;


        $returnItems['menu'] = $this->buildMenu($tree, $currentCategory, 2, $hasChildren);

        echo json_encode($returnItems);
    }

    public function buildMenu($tree, $currentCategory, $level = 2, $hasChildren)
    {
        $items = array_shift($tree);
        // Debugger::debug($items);
        // slight hack to accomodate multi-item root level
        if(is_object($items)){
            // juggle types
            $tmp = $items;
            $items = [];
            $items[] = $tmp;
        }

        foreach($items as $k => $item){
            if(isset($item->id)){
                $item->category_id = $item->id;
            }
            $categoryItem = $this->Category_model->find($item->category_id);


            $htmlString .= '<li class="item item--parent refresh--content is--expanded subchild ">
                                <a class="link fontWeight--2 ' . (($currentCategory == $categoryItem->id) ? 'is--active' : '') . '" href="javascript:;" id="cat' . $categoryItem->id . '" catid="' . $categoryItem->id . '">' . $categoryItem->name . '</a>
                                <ul class="list level_' . $level . '"' . ((empty($hasChildren)) ? 'style="display: none;"' : '') . '>
                                ';
            if(count($tree) > 0){
                $htmlString .= $this->buildMenu($tree, $currentCategory, $level + 1, $hasChildren);
            } else {
                //load children
                $children = $this->Category_model->getCategoryChildren($currentCategory, $this->config->item('whitelabel')->vendor_id);
                foreach($children as $child){
                    $htmlString .= '<li class="item"><a class="link" id="' . $child->id . '" catid="' . $child->id . '">' . $child->name . '</a><ul class="list level_3" style="display:none;"></ul></li>';
                }
            }
            $htmlString .= '    </ul>';
            if ($categoryItem->separator_after == "1" || ($categoryItem->name == 'Restorative' && !$hasChildren &&  count($items) -1 != $k)) {
                $htmlString .= '<li class="item item--parent refresh--content is--expanded subchild cat-separator">';
                $htmlString .= '<a href="javascript:;">------------------</a>';
                $htmlString .= '</li>';
            }
        }



        return $htmlString;
    }
}

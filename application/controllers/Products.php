
<?php

class Products extends MW_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Products_model');
        $this->load->model('Category_model');
        $this->load->model('Review_model');
        $this->load->model('Product_question_model');
        $this->load->model('Product_pricing_model');
        $this->load->model('Prepopulated_product_model');
        $this->load->model('Order_model');
        $this->load->model('User_licenses_model');
        $this->load->model('User_location_model');
        $this->load->model('Organization_location_model');
        $this->load->model('Images_model');
        $this->load->model('Order_items_model');
        $this->load->model('Promo_codes_model');
        $this->load->model('Vendor_model');
        $this->load->model('User_model');
        $this->load->model('Shipping_options_model');
        $this->load->model('Request_list_model');
        $this->load->model('Product_answer_model');
        $this->load->model('Prepopulated_list_model');
        $this->load->model('Product_custom_field_model');
        $this->load->model('Organization_groups_model');
        $this->load->model('Flagged_reviews_model');
        $this->load->model('BuyingClub_model');
        $this->load->model('ApiSearch_model');
        $this->load->library('elasticsearch');
        $this->load->helper('MY_privilege_helper');
        $this->load->library('email');
        $this->load->helper('my_email_helper');
    }

    //Function to apply all filtering and sorting.
    public function search_products() {


        $data['bcModel'] = $this->BuyingClub_model;
        //Criteria
        // Debugger::debug($_GET);
        $category = $this->input->get("category");
        $vendor_id = $this->input->get("vendor_id");
        $manufacturer = $this->input->get("manufacturer");
        $option = $this->input->get('option_value');
        $data['list_id'] = $list_id = $this->input->get("listid");
        $data['procedure'] = $procedure = $this->input->get("procedure");
        $search_data = $this->input->get('search');
        $licenseRequired = $this->input->get("license");
        $purchased = $this->input->get("purchased");
        //Pagination
        // Debugger::debug($_GET);
        $page = $this->input->get("page");
        $page = (empty($page)) ? 0 : $page;
                $perPage = $this->input->get("per_page");

        $perPage = (empty($perPage)) ? 10 : $perPage;
        $start = $perPage * $page;
        $end = ($page + 1) * $perPage;

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

        // // Debugger::debug($result);
        // Debugger::debug($result['totalResults']);

        $data['products'] = $result['results'];
        $total_products = $result['totalResults'];
        $productIds = $result['productIds'];

        $data['pages'] = ceil($total_products / $perPage);

        $data['page'] = $page;
        $data['grid'] = $this->input->get("grid");
        if ($data['grid'] == null || $data['grid'] == "" || $data['grid'] == "0") {
            $data['grid'] = "list";
        } else {
            $data['grid'] = "grid";
        }
        //Filters
        $license_required = $this->input->get("license");
        $purchased = $this->input->get("purchased");
        $rating = $this->input->get('rate_data');
        if ($rating != null && $rating != "") { //filter by seleted ratings
            //$data['products'] = $this->Products_model->check_rating($data['products'], $rating);
        }

        if ($end > $total_products) {
            $end = $total_products;
        }
        if(!empty($_SESSION['user_buying_clubs'])){
            // make class available to template
            $data['bcModel'] = $this->BuyingClub_model;
            // get buying club prices
            $data['bcPrices'] = $this->BuyingClub_model->getBuyingClubPrices($_SESSION['user_buying_clubs'], $productIds);
            $data['buyingClubs'] = $_SESSION['user_buying_clubs'];
            // Debugger::debug($data['bcPrices']);
        }

        $data['start'] = $start;
        $data['end'] = $end;
        $data['per_page'] = $per_page;
        $data['pages'] = ceil($total_products / $perPage);
        // // Debugger::debug($_SESSION);
        if(!empty($_SESSION['user_id']) &! $this->User_model->can($_SESSION['user_permissions'], 'is-admin') &! $this->User_model->can($_SESSION['user_permissions'], 'is-vendor')){
            $data['userLicenses'] = $this->User_licenses_model->loadValidLicenses($_SESSION['user_id'], 1);
            if(in_array($_SESSION['role_id'], unserialize(ROLES_TIER1_2)) && empty($data['userLicenses'])){
               $this->session->set_flashdata("success", "A valid state license must be verified for purchasing items which require a license.  <a href=\"/profile\" class=\"link\">Manage license.</a>");
            }
        }
        $this->load->view('/templates/product/product_list.php', $data);
    }

//Default search from search bar
    public function products_search() {
        $data['bcModel'] = $this->BuyingClub_model;
        $search_data = $this->input->get('q');
        $search_data = preg_replace('/[^a-zA-Z0-9\']/', ' ', $search_data);
        $data['search_term'] = $search_data;

        // $result = $this->ApiSearch_model->run($this->input->get('q'));

        $result = $this->ApiSearch_model->run($this->input->get('category'),
                                              $this->input->get('manufacturer'),
                                              $this->input->get('vendor_id'),
                                              $this->input->get('procedure'),
                                              $this->input->get('list_id'),
                                              $licenseRequired,
                                              $purchased,
                                              $this->input->get('q'),
                                              $start = 0,
                                              $perPage = 10,
                                              $option = null);

        $data['products'] = $result['results'];

        $page = 0;
        $per_page = 10;
        $start = $per_page * $page;
        $end = ($page + 1) * $per_page;

        if ($data['products']) {
            $data['category'] = $this->Category_model->get_many_by(array('parent_id' => ''));

            $total_products = $result['totalResults'];

            $no_of_pages = $total_products / $per_page;
            if ($end > $total_products) {
                $end = $total_products;
            }

            $data['bcPrices'] = $this->BuyingClub_model->getBuyingClubPrices($_SESSION['user_buying_clubs'], $productIds);

            if (isset($_SESSION['user_id'])) {
                $data['orders'] = $this->Order_model->get_many_by(array('user_id' => $_SESSION["user_id"], 'restricted_order' => '0'));
            }
            $data['start'] = $start;
            $data['end'] = $end;
            $data['pages'] = ceil($no_of_pages);
            // Debugger::debug($data);
            $this->load->view('/templates/_inc/header');
            $this->load->view('/templates/browse/index', $data);
            $this->load->view('/templates/_inc/footer');
        } else {
            $data['categories'] = $this->Category_model->get_many_by(array('parent_id' => '1'));
            $this->load->view('/templates/_inc/header');
            $this->load->view('/templates/browse/no-results/index', $data);
            $this->load->view('/templates/_inc/footer');
        }
    }

    public function view_products() { //single products view from home page
        $roles = unserialize(ROLES_USERS);
        $data['has_license'] = 0;
        $product_id = $this->input->get('id');
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {

            $userId = $_SESSION["user_id"];

            $organization_details = $this->Organization_groups_model->get_by(array('user_id' => $userId));

            $data['has_license'] = $this->User_licenses_model->checkLicenses($organization_details->id);
            Debugger::debug($data['has_license'], 'has_license');

            //check this sing products already add in user shopping lists products
            $query = "select c.id,c.listname from user_locations a INNER JOIN organization_locations b on b.id =a.organization_location_id INNER JOIN prepopulated_lists c on c.location_id=b.id where a.user_id=$userId";
            $data['shopping_lists'] = $this->db->query($query)->result();

            if ($data['shopping_lists'] != null) {
                for ($i = 0; $i < count($data['shopping_lists']); $i++) {
                    $data['shopping_lists'][$i]->product_id = "";
                    $prepopulatedProduct = $this->Prepopulated_product_model->get_by(array('list_id' => $data['shopping_lists'][$i]->id, 'product_id' => $product_id));
                    if ($prepopulatedProduct != null) {
                        $data['shopping_lists'][$i]->product_id = $prepopulatedProduct->product_id;
                    }
                }
            }

        } else {
            $data['shopping_lists'] = [];
        }

        //get product details
        if ($product_id != null) {

            $data['bcModel'] = $this->BuyingClub_model;
            // Debugger::debug($_SESSION);
            // if(!empty($_SESSION['user_buying_clubs'][0]['name'])){
            //     $data['bcPrices'] = $this->BuyingClub_model->getBuyingClubPrices($_SESSION['user_buying_clubs'], [$product_id]);
            //     $data['buyingClubs'] = $_SESSION['user_buying_clubs'];
            // }

            $data['product'] = $this->Products_model->get_by(array('id' => $product_id));


            if ($data['product'] != null && $data['product'] !== "") {


                if(!empty($_SESSION['user_buying_clubs'][0]['name'])){
                    $data['bcPrices'] = $this->BuyingClub_model->getBuyingClubPrices($_SESSION['user_buying_clubs'], [$product_id]);
                    $data['buyingClubs'] = $_SESSION['user_buying_clubs'];
                }

                $data['custom_fields'] = $this->Product_custom_field_model->get_many_by(array('product_id' => $product_id));
                $data['sub_image'] = $this->Images_model->get_many_by(array('model_id' => $product_id, 'model_name' => 'products', 'image_type' => 'subimg'));

                $data['main_image'] = $this->Images_model->get_by(array('model_id' => $product_id, 'model_name' => 'products', 'image_type' => 'mainimg'));
                if(!empty($this->config->item('whitelabel_vendor_id'))){
                    $data['products_pricings'] = $this->Product_pricing_model->order_by('price', 'asc')->get_many_by(array('product_id' => $product_id, 'active' => 1, 'vendor_id' => $this->config->item('whitelabel_vendor_id')));
                } else {
                    $data['products_pricings'] = $this->Product_pricing_model->getPricesMarketplace($product_id);
                }
                // Debugger::debug($data['products_pricings']);
                $lowestPrice = $this->Product_pricing_model->getBestPrice($product_id);
                $data['product_qstn'] = $this->Product_question_model->order_by('id', 'desc')->get_many_by(array('product_id' => $product_id));
                $data['prepopulated_products'] = "";

                if (isset($_SESSION['user_id']) && (in_array($_SESSION['role_id'], $roles))) {
                    $data['prepopulated_products'] = $this->Prepopulated_product_model->get_by(array('user_id' => $userId, 'product_id' => $product_id));
                }
                for ($i = 0; $i < count($data['product_qstn']); $i++) {  //get product related ,user questions
                    $answers = $this->Product_answer_model->order_by('upvotes', 'desc')->get_many_by(array('question_id' => $data['product_qstn'][$i]->id));
                    $answer = 'SELECT *,COUNT(question_id)as total FROM product_answers WHERE question_id=' . $data['product_qstn'][$i]->id . '';
                    $answer_count = $this->db->query($answer)->result();
                    $data['product_qstn'][$i]->answers = $answers;
                }

                $query = ('SELECT p.*,count(p.vendor_id) as vendor_total,GREATEST(max(price), max(retail_price))as max_value,min(NULLIF(retail_price,0)) as min_value ,min(NULLIF(price,0)) as minprice_value from product_pricings as p LEFT JOIN vendors as v on p.vendor_id=v.id  where p.active=1 and v.active=1 and p.product_id=' . $product_id . ' GROUP BY p.price');

                // // Debugger::debug($data['products_pricings']);
                // // Debugger::debug($lowestPrice);

                $data['price'] = "";
                $this->db->query($query)->result();
                $data['retail_price'] = max(array_column($data['price'], 'retail_price'));
                if(empty($data['products_pricings'])){
                    $data['inactive'] = true;
                    $this->session->set_flashdata('error', 'Product unavailable');
                }

                $options = $this->input->get('options');

                if (isset($options) && $options == '1') { //sorting products review (most recent or recently)
                    $data['product_review'] = $this->Review_model->order_by('updated_at', 'desc')->get_many_by(array('model_id' => $product_id, 'model_name' => 'products'));
                } else {
                    $review_query = "select *, (COALESCE(upvotes, 0)/(COALESCE(upvotes, 0)+COALESCE(downvotes, 0))) as top_rated from reviews where model_id=$product_id and model_name='products' order by top_rated desc, upvotes desc";
                    $data['product_review'] = $this->db->query($review_query)->result();
                }
                //get product related ,user reviews

                for ($i = 0; $i < count($data['product_review']); $i++) {
                    $users = $this->User_model->select('id,first_name')->get_by(array('id' => $data['product_review'][$i]->user_id));
                    $data['product_review'][$i]->users = $users;
                }
                $data['review'] = $this->Review_model->get_many_by(array('model_name' => 'products', 'model_id' => $product_id));
                $data['one_star'] = $this->Review_model->get_many_by(array('model_name' => 'products', 'model_id' => $product_id, 'rating' => '1'));
                $data['two_star'] = $this->Review_model->get_many_by(array('model_name' => 'products', 'model_id' => $product_id, 'rating' => '2'));
                $data['three_star'] = $this->Review_model->get_many_by(array('model_name' => 'products', 'model_id' => $product_id, 'rating' => '3'));
                $data['four_star'] = $this->Review_model->get_many_by(array('model_name' => 'products', 'model_id' => $product_id, 'rating' => '4'));
                $data['five_star'] = $this->Review_model->get_many_by(array('model_name' => 'products', 'model_id' => $product_id, 'rating' => '5'));
                $vendors = ('SELECT a.price,a.vendor_id,a.retail_price,b.name,b.id,c.title,c.conditions,d.shipping_price,d.shipping_type,f.policy_name FROM product_pricings a LEFT JOIN vendors b ON a.vendor_id=b.id LEFT JOIN promo_codes c ON a.product_id=c.product_id  LEFT JOIN vendor_policies f ON b.id=f.vendor_id LEFT JOIN shipping_options d ON b.id=d.vendor_id where a.product_id=' . $product_id . ' and b.active=1 and a.active=1' . ((!empty($this->config->item('whitelabel_vendor_id'))) ? " and a.vendor_id = " . $this->config->item('whitelabel_vendor_id') : "" ) . ' group by a.price,b.id');

                $data['vendors'] = $this->Vendor_model->loadVendorPricings($product_id);
                // $data['vendors'] = $this->db->query($vendors)->result();

                if ($data['vendors'] != null) { //get active vendors ,shipping and promo details
                    for ($i = 0; $i < count($data['vendors']); $i++) {
                        $data['vendors'][$i]->promo_title = "";
                        $data['vendors'][$i]->promo_instructions = "";
                        $promo_code = $this->Promo_codes_model->get_by(array('product_id' => $product_id, 'vendor_id' => $data['vendors'][$i]->vendor_id, 'active' => '1'));

                        if ($promo_code != null) {
                            $data['vendors'][$i]->promo_title = $promo_code->title;
                            $data['vendors'][$i]->promo_instructions = $promo_code->conditions;
                        }
                        $data['product_promos'] = $this->Promo_codes_model->order_by('id', 'asc')->get_many_by(array('product_id' => $product_id, 'active' => '1'));
                        ;
                        $vendor_review = $this->Review_model->get_many_by(array('model_name' => 'vendor', 'model_id' => $data['vendors'][$i]->vendor_id));
                        $data['vendors'][$i]->average_rating = 0;
                        $total_score = 0;
                        if ($vendor_review != null) {
                            for ($j = 0; $j < count($vendor_review); $j++) {
                                $total_score += $vendor_review[$j]->rating;
                                if (count($vendor_review) == 0) {
                                    $data['vendors'][$i]->average_rating = 0;
                                } else {
                                    $data['vendors'][$i]->average_rating = $total_score / count($vendor_review);
                                }
                            }
                        }

                        $vendor_shippings = $this->Shipping_options_model->order_by('delivery_time', 'desc')->get_many_by(array('vendor_id' => $data['vendors'][$i]->vendor_id, 'delivery_time !=' => ''));
                        for ($j = 0; $j < count($vendor_shippings); $j++) {
                            switch ($vendor_shippings[$j]->delivery_time) {
                                case "Same Day":
                                    $data['vendors'][$i]->delivery_time = date('M d, Y');
                                    break;
                                case "Next Business Day":
                                    $data['vendors'][$i]->delivery_time = date('M d, Y', strtotime(' +1 Weekday'));
                                    break;
                                case "2 Business Days":
                                    $data['vendors'][$i]->delivery_time = date('M d, Y', strtotime(' +2 Weekday'));
                                    break;
                                case "3 Business Days":
                                    $data['vendors'][$i]->delivery_time = date('M d, Y', strtotime(' +3 Weekday'));
                                    break;
                                case "1-5 Business Days":
                                    $data['vendors'][$i]->delivery_time = date('M d, Y', strtotime(' +5 Weekday'));
                                    break;
                                case "7-10 Business Days":
                                    $data['vendors'][$i]->delivery_time = date('M d, Y', strtotime(' +10 Weekday'));
                                    break;
                                default:
                                    $data['vendors'][$i]->delivery_time = "n.a.";
                                    break;
                            }
                        }
                    }
                }
                /* Set Values Here So Product/index.php view is cleaner */
                    $category_id = $this->input->get('category');
                    // Debugger::debug($category_id, '$category_id');
                    // Debugger::debug($data['product']);
                    if ($category_id) {
                        $data['category'] = $this->Category_model->get($category_id);
                    } elseif(!empty($data['product']->category_id) && $data['product']->category_id != '""') {
                        // only grab categories if product has any
                        $cats = explode('","', substr($data['product']->category_id, 1, -1));
                        $sql = "SELECT id, parent_id FROM categories WHERE id IN (" . implode(',', $cats) . ")";
                        // Debugger::debug($sql, '$sql');
                        $categories = $this->db->query($sql)->result();
                        $done = false;
                        $classic = [];
                        $dental = [];

                        // split categories into classic and dental
                        // Debugger::debug($categories, '$categories');

                        // while (!$done) {
                            foreach ($categories as $k => $cat) {
                                if ($cat->parent_id == 1) {
                                    $classic[$cat->parent_id] = $cat;
                                    unset($categories[$k]);
                                } else if ($cat->parent_id == 2) {
                                    $dental[$cat->parent_id] = $cat;
                                    unset($categories[$k]);
                                }

                                if (!empty($classic[$cat->parent_id])) {
                                    $classic[$cat->id] = $cat;
                                    $category_id = $cat->id;
                                    unset($categories[$k]);
                                } else if (!empty($dental[$cat->parent_id])) {
                                    $dental[$cat->id] = $cat;
                                    unset($categories[$k]);
                                }

                                if (empty($categories)) {
                                    $done = true;
                                }
                            // }
                        }
                        $data['category'] = $this->Category_model->get($category_id);
                    }

                    $data['category_id'] = $category_id;
                    $data['product_id']  = $product_id;


                /**
                 * While Loop: Grabs Category Hierarchy For Breadcrumbs
                 */

                    /* Set Values For Loop */
                    $category = $this->Category_model->find($category_id);
                    $category_hierarchy[] = $category;
                    $parent_id = $category->parent_id;
                    $loop = 1;

                    /* loop until no parent_id exists */
                    while($parent_id)
                    {

                        $category = $category_hierarchy[$loop-1];
                        $category_hierarchy[] = $this->Category_model->find($category->parent_id);
                        $parent_id = $category_hierarchy[$loop]->parent_id;
                        $loop++;

                    } $data["category_hierarchy"] = array_reverse($category_hierarchy);


                $data['options'] = $options;
                if (isset($_SESSION['user_id'])) { //get user assigned locations
                    $data['locations'] = $this->User_location_model->get_many_by(array('user_id' => $_SESSION['user_id']));
                    for ($i = 0; $i < count($data['locations']); $i++) {
                        $data['user_locations'][] = $this->Organization_location_model->get_by(array('id' => $data['locations'][$i]->organization_location_id));
                    }
                }


                $relatedProducts = $this->Products_model->getRelatedProducts($product_id, $category_id, $this->config->item('whitelabel_vendor_id'));

                foreach ($relatedProducts as $k => $product){
                    $product_price = $this->Product_pricing_model->order_by('price', 'asc')->get_by(array('product_id' => $product->id, 'active' => 1));
                    $images = $this->Images_model->get_by(array('model_name' => 'products', 'image_type' => 'mainimg', 'model_id' => $product->id));
                    $promos = $this->Promo_codes_model->get_by(array('product_id' => $product->id, 'active' => '1'));
                    $data['review'] = $this->Review_model->get_many_by(array('model_name' => 'products'));
                    $query = ('SELECT p.*,GREATEST(max(price), max(retail_price))as max_value,min(NULLIF(retail_price,0)) as min_value ,min(NULLIF(price,0)) as minprice_value from product_pricings as p LEFT JOIN vendors as v on p.vendor_id=v.id  where p.active=1 and v.active=1 and p.product_id=' . $product->id . ' GROUP BY p.price');
                    $query1 = ('SELECT count(p.vendor_id) as v_total from product_pricings p LEFT JOIN vendors v on p.vendor_id=v.id WHERE p.active=1 and v.active=1 and p.product_id=' . $product->id);
                    $vendorPrice = $this->db->query($query);
                    $vendorPrice = $vendorPrice ? $vendorPrice->result() : false;
                    $vendorTotal = $this->db->query($query1);
                    $vendorTotal = $vendorTotal ? $vendorTotal->result() : false;
                    $relatedProducts[$k]->product_price = $product_price;
                    $relatedProducts[$k]->images = $images;
                    $relatedProducts[$k]->promos = $promos;
                    $relatedProducts[$k]->price = $vendorPrice;
                    $relatedProducts[$k]->vendor_total = $vendorTotal;
                }

                //// Debugger::debug($relatedProducts);

                $data['related_products'] = $relatedProducts;

             

                $this->load->view('/templates/_inc/header', $data);
                $this->load->view('/templates/product/index', $data);
                $this->load->view('/templates/_inc/footer');
            } else {
                $this->session->set_flashdata('error', 'No Products Found.');
                header("Location:home");
            }

        }
    }

    public function get_productimage() { //get products image
        $product_id = $this->input->post('pro_id');
        $data['images'] = $this->Images_model->get_many_by(array('model_name' => 'products', 'model_id' => $product_id));
        echo json_encode($data);
    }

    public function product_questions() { //add product questions
        $roles = unserialize(ROLES_USERS);
        $vendor_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles)) || in_array($_SESSION['role_id'], $vendor_roles)) {
            $user_id = $_SESSION['user_id'];
            $product_id = $this->input->post('product_id');
            $insert_data = array(
                'product_id' => $this->input->post('product_id'),
                'question' => $this->input->post('questions'),
                'asked_by' => $user_id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            if ($insert_data != null) {
                $this->Product_question_model->insert($insert_data);
                header("Location: view-product?id=" . $product_id);
            }
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function add_answer() { //add products answer
        $roles = unserialize(ROLES_USERS);
        $vendor_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles)) || in_array($_SESSION['role_id'], $vendor_roles)) {
            $user_id = $_SESSION['user_id'];
            $question = $this->input->post('question_id');
            $answer = $this->input->post('answerQuestion');
            $insert_data = array(
                'question_id' => $question,
                'answer' => $answer,
                'answered_by' => $user_id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );

            $this->Product_answer_model->insert($insert_data);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function flag_answer() { // flagging product answers
        $roles = unserialize(ROLES_USERS);
        $vendor_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles)) || in_array($_SESSION['role_id'], $vendor_roles)) {
            $answer_id = $this->input->post('answer_id');
            $question = $this->input->post('question');
            $answer = $this->input->post('Answer');
            $product_name = $this->input->post('product_name');
            $p_id = $this->input->post('p_id');
            $user_name = $_SESSION['user_name'];
            $user_id = $_SESSION['user_id'];
            $flaggedCheck = $this->Flagged_reviews_model->get_by(array('model_id' => $answer_id, 'user_id' => $user_id, 'model_name' => 'answerby'));
            if ($flaggedCheck == "" && $flaggedCheck == null) {
                $insertFlag_data = array(
                    'user_id' => $user_id,
                    'model_id' => $answer_id,
                    'model_name' => 'answerby',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                if ($insertFlag_data != null) {
                    $result = $this->Flagged_reviews_model->insert($insertFlag_data);
                }
                $query_flag_vendor = "select * from users where role_id=1 or role_id=2";
                $data['users'] = $this->db->query($query_flag_vendor)->result();
                for ($i = 0; $i < count($data['users']); $i++) {
                    $email = $data['users'][$i]->email;
                    $subject = 'Answer Flagged';
                    $message = "Hi, <br />"
                            . $user_name . " has Flagged the following answer<br>"
                            . "<table cellpadding='5' cellspacing='5' border='0' width='100' style='width: 450px; padding:5px; background-color:#ffffff; border-bottom:1px solid #E8EAF1; border-top:4px solid #13C4A3;' class='100p'>"
                            . "<tr><td>Product Name: </td><td> " . $product_name . "</td></tr>"
                            . "<tr><td>Question: </td><td> " . $question . "</td></tr>"
                            . "<tr><td>Answer: </td><td> " . $answer . "</td></tr></table> <br/ > <br/ >"
                            . "<a href='" . base_url() . "SPadmin-products?product_id=" . $p_id . "' style='padding: 20px 40px 20px 40px;width:auto;display: block;text-decoration: none;border:0;text-align: center;font-weight: bold;font-size: 16px;font-family: Arial, sans-serif;color: #ffffff;background: #2893FF;border: 1px solid #317ED0;-moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius: 4px;line-height:normal;' class='button_link'>View Product</a>";

                    $email_data = array(
                        'subject' => $subject,
                        'message' => $message
                    );
                    $body = $this->load->view('/templates/email/index.php', $email_data, TRUE);
                    $mail_status = send_matix_email($body, $subject, $email);
                }
                $this->session->set_flashdata('success', 'The  answer is flagged');
                header("location: view-product?id=" . $p_id);
            } else {
                $this->session->set_flashdata('error', 'The answer is already flagged by you');
                header("location: view-product?id=" . $p_id);
            }
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function flag_review() { // flagging product reviews
        $roles = unserialize(ROLES_USERS);
        $vendor_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles)) || in_array($_SESSION['role_id'], $vendor_roles)) {
            $review_id = $this->input->post('review_id');
            $review_title = $this->input->post('review_title');
            $answer = $this->input->post('review_comments');
            $product_name = $this->input->post('product_name');
            $p_id = $this->input->post('p_id');
            $user_name = $_SESSION['user_name'];
            $user_id = $_SESSION['user_id'];
            $flaggedCheck = $this->Flagged_reviews_model->get_by(array('model_id' => $review_id, 'user_id' => $user_id, 'model_name' => 'product'));
            if ($flaggedCheck == "" && $flaggedCheck == null) {
                $insertFlag_data = array(
                    'user_id' => $user_id,
                    'model_id' => $review_id,
                    'model_name' => 'product',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                if ($insertFlag_data != null) {
                    $result = $this->Flagged_reviews_model->insert($insertFlag_data);
                    if ($result != null) {
                        $reviewCount = $this->Review_model->get($review_id);
                        $flagcount = 1;
                        $update_review = array(
                            'flag_count' => $flagcount + $reviewCount->flag_count,
                            'updated_at' => date('Y-m-d H:i:s'),
                        );
                        $this->Review_model->update($reviewCount->id, $update_review);
                    }
                }
                $query_flag_vendor = "select * from users where role_id=1 or role_id=2";
                $data['users'] = $this->db->query($query_flag_vendor)->result();
                for ($i = 0; $i < count($data['users']); $i++) {
                    $email = $data['users'][$i]->email;
                    $subject = 'Review Flagged';
                    $message = "Hi,<br />"
                            . $user_name . " has flagged this product review <br>"
                            . "<table cellpadding='5' cellspacing='5' border='0' width='100' style='width: 450px; padding:5px; background-color:#ffffff; border-bottom:1px solid #E8EAF1; border-top:4px solid #13C4A3;' class='100p'>"
                            . "<tr style='width: 100px;'><td>Product Name: </td><td> $product_name</td></tr>"
                            . "<tr style='width: 100px;'><td>Review Title: </td><td> $review_title</td></tr>"
                            . "<tr style='width: 100px;'><td>Review Comment: </td><td> $answer</td></tr></table><br><br>"
                            . "<a href='" . base_url() . "SPadmin-products?product_id=" . $p_id . "' style='padding: 20px 40px 20px 40px;width:auto;display: block;text-decoration: none;border:0;text-align: center;font-weight: bold;font-size: 16px;font-family: Arial, sans-serif;color: #ffffff;background: #2893FF;border: 1px solid #317ED0;-moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius: 4px;line-height:normal;' class='button_link'>View Product</a>";
                    $email_data = array(
                        'subject' => $subject,
                        'message' => $message
                    );
                    $body = $this->load->view('/templates/email/index.php', $email_data, TRUE);
                    $mail_status = send_matix_email($body, $subject, $email);
                }
                $this->session->set_flashdata("success", "The product review is flagged");
                header("Location:view-product?id=" . $p_id);
            } else {
                $this->session->set_flashdata("error", "This product review is already flagged by you ");
                header("Location:view-product?id=" . $p_id);
            }
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function add_product_rating() { /* product Reviews and rating */
        $roles = unserialize(ROLES_USERS);
        $vendor_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles)) || in_array($_SESSION['role_id'], $vendor_roles)) {
            $user_id = $_SESSION['user_id'];
            $product_id = $this->input->post('product_id');
            $insert_data = array(
                'user_id' => $user_id,
                'model_id' => $product_id,
                'rating' => $this->input->post('rating1'),
                'model_name' => 'products',
                'title' => $this->input->post('reviewTitle'),
                'comment' => $this->input->post('productReview'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );

            if ($insert_data != null) {
                $this->Review_model->insert($insert_data);
            }
            $review = $this->Review_model->get_many_by(array('model_id' => $product_id, 'model_name' => 'products'));
            $rating = 0;
            $reviewcount = count($review);
            if ($reviewcount > 0) {
                foreach ($review as $row) {
                    $rating = $rating + $row->rating;
                }
                $rating = $rating / $reviewcount;
            } else {
                $rating = $this->input->post('rating1');
            }
            $update_data = array(
                'average_rating' => $rating,
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $this->Products_model->update($product_id, $update_data);
            $product_info = $this->Products_model->get_by(array('id' => $product_id));
            $product_array = (array) $product_info;
            $this->elasticsearch->delete("products", $product_id);
            $this->elasticsearch->add("products", $product_id, $product_array);

            header("Location: view-product?id=" . $product_id);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function upvote_answer() { //upvote product answer
        $roles = unserialize(ROLES_USERS);
        $vendor_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles)) || in_array($_SESSION['role_id'], $vendor_roles)) {
            $update_id = $this->input->post('update_id');
            $upvote_data = $this->Product_answer_model->get_by(array('id' => $update_id));
            $db_upvote = $upvote_data->upvotes;
            $update_data = $db_upvote + 1;
            $this->Product_answer_model->update($update_id, array('upvotes' => $update_data));
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function downvote_answer() { //downvote product answer
        $roles = unserialize(ROLES_USERS);
        $vendor_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles)) || in_array($_SESSION['role_id'], $vendor_roles)) {
            $update_id = $this->input->post('update_id');
            $upvote_data = $this->Product_answer_model->get_by(array('id' => $update_id));
            $db_downvote = $upvote_data->downvotes;
            $update_data = $db_downvote + 1;
            $this->Product_answer_model->update($update_id, array('downvotes' => $update_data));
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function upvote_review() { //upvote product review
        $roles = unserialize(ROLES_USERS);
        $vendor_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles)) || in_array($_SESSION['role_id'], $vendor_roles)) {
            $update_id = $this->input->post('update_id');
            $upvote_data = $this->Review_model->get_by(array('id' => $update_id));
            $db_upvote = $upvote_data->upvotes;
            $update_data = $db_upvote + 1;
            $this->Review_model->update($update_id, array('upvotes' => $update_data));
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function downvote_review() { //downvote product review
        $roles = unserialize(ROLES_USERS);
        $vendor_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles)) || in_array($_SESSION['role_id'], $vendor_roles)) {
            $update_id = $this->input->post('update_id');
            $upvote_data = $this->Review_model->get_by(array('id' => $update_id));
            $db_downvote = $upvote_data->downvotes;
            $update_data = $db_downvote + 1;
            $this->Review_model->update($update_id, array('downvotes' => $update_data));
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function add_elastic_mapping() {
        set_time_limit(0);
        $limit = 100;
        $total_count = $this->Products_model->count_all();
        $pages = ceil($total_count / $limit);
        for ($k = 0; $k < $pages; $k++) {
            $offset = $k * $limit;
            $products = $this->Products_model->limit($limit, $offset)->get_all();
            for ($i = 0; $i < count($products); $i++) {
                $product_array = (array) $products[$i];
                $product_array['mpn'] = str_replace("-", "", $product_array['mpn']);
                $vendor_product_id = "";
                $vendor_pricings = $this->Product_pricing_model->get_many_by(array('product_id' => $products[$i]->id));
                if ($vendor_pricings != null && count($vendor_pricings) > 0) {
                    for ($j = 0; $j < count($vendor_pricings); $j++) {
                        $vendor_product_id .= ((str_replace("-", "", $vendor_pricings[$j]->vendor_product_id)) . ',');
                    }
                    $product_array['vendor_product_id'] = $vendor_product_id;
                }
                $this->elasticsearch->add("products", $products[$i]->id, $product_array);
            }
        }
    }

    public function elastic_play() {
        $action = $this->input->get("action");
        $id = $this->input->get("id");
        $product = $this->elasticsearch->get("products", $id);
        if ($action == "edit") {
            $product_info = $product['_source'];
            $product_info['vendor_product_id'] = $product_info['vendor_product_id'] . "," . "ABCD";
            $this->elasticsearch->delete("products", $id);
            $this->elasticsearch->add("products", $id, $product_info);
        } else {
            print_r($product);
        }
    }

    public function get_vendor_ratings() { //get active vendors and their ratings to view in products page
        $vendor_id = $this->input->post('v_id');
        $data['vendor_review'] = $this->Review_model->get_many_by(array('model_name' => 'vendor', 'model_id' => $vendor_id));
        $data['average_rating'] = "0";
        $total_score = 0;
        if ($data['vendor_review'] != null) {
            for ($i = 0; $i < count($data['vendor_review']); $i++) {
                $total_score += $data['vendor_review'][$i]->rating;
                if (count($data['vendor_review']) == 0) {
                    $data['average_rating'] = 0;
                } else {
                    $data['average_rating'] = $total_score / count($data['vendor_review']);
                }
            }
        }
        $data['vendors'] = $this->Shipping_options_model->get_many_by(array('vendor_id' => $vendor_id));
        for ($i = 0; $i < count($data['vendors']); $i++) {
            switch ($data['vendors'][$i]->delivery_time) {
                case 0:
                    $data['vendors'][$i]->delivery_time = date('M d, Y');
                    break;
                case 1:
                    $data['vendors'][$i]->delivery_time = date('M d, Y', strtotime(' +1 Weekday'));
                    break;
                case 2:
                    $data['vendors'][$i]->delivery_time = date('M d, Y', strtotime(' +2 Weekday'));
                    break;
                case 3:
                    $data['vendors'][$i]->delivery_time = date('M d, Y', strtotime(' +3 Weekday'));
                    break;
                case 4:
                    $data['vendors'][$i]->delivery_time = date('M d, Y', strtotime(' +5 Weekday'));
                    break;
                case 5:
                    $data['vendors'][$i]->delivery_time = date('M d, Y', strtotime(' +10 Weekday'));
                    break;
            }
        }

        echo json_encode($data);
    }

    public function add_answer_customer() {
        $roles = unserialize(ROLES_USERS);
        $vendor_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles)) || in_array($_SESSION['role_id'], $vendor_roles)) {
            $user_id = $_SESSION['user_id'];
            $product_id = $this->input->post('product_id');
            $question = $this->input->post('question_id');
            $answer = $this->input->post('answerQuestion');
            $insert_data = array(
                'question_id' => $question,
                'answer' => $answer,
                'answered_by' => $user_id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $this->Product_answer_model->insert($insert_data);
            header("Location: view-product?id=" . $product_id);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

}

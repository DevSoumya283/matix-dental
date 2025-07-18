<?php

/*
 * 1. Working on Vendor Page.
 */

//  NOTES:  SELECT * FROM `product_pricings` where vendor_id=1 order by  price desc ;
class VendorDashboard extends MW_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Business_hour_model');
        $this->load->model('User_model');
        $this->load->model('Review_model');
        $this->load->model('Role_model');
        $this->load->model('Vendor_model');
        $this->load->model('Vendor_groups_model');
        $this->load->model('Products_model');
        $this->load->model('Product_pricing_model');
        $this->load->model('Promo_codes_model');
        $this->load->model('Shipping_options_model');
        $this->load->model('Order_model');
        $this->load->model('Organization_model');
        $this->load->model('Organization_groups_model');
        $this->load->model('User_licenses_model');
        $this->load->model('Order_items_model');
        $this->load->model('Images_model');
        $this->load->model('User_location_model');
        $this->load->model('Organization_location_model');
        $this->load->model('Order_promotion_model');
        $this->load->model('User_vendor_notes_model');
        $this->load->model('Order_item_return_model');
        $this->load->model('vendor_order_notes_model');
        $this->load->helper('MY_privilege_helper');
        $this->load->helper('MY_support_helper');
        $this->load->helper('my_email_helper');
        $this->load->model('Vendor_policies_model');
        $this->load->model('Vendor_customer_notes_model');
        $this->load->model('Whitelabel_model');
        $this->load->library('stripe');
    }

    public function update_companyDetails() {
        $vendor_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $vendor_roles))) {
            $update_shipping = [
                'name' => $this->input->post('companyName'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $vendor_id = $this->input->post('vendor_id');
            $this->Vendor_model->update($vendor_id, $update_shipping);
            if ($_FILES['companyLogo']['name'] != null) {
                $new_file_name = time() . preg_replace('/[^a-zA-Z0-9_.]/', '_', $_FILES['companyLogo']['name']);
                $_FILES['companyLogo']['name'] = $new_file_name;
                $_FILES['companyLogo']['type'] = $_FILES['companyLogo']['type'];
                $_FILES['companyLogo']['tmp_name'] = $_FILES['companyLogo']['tmp_name'];
                $_FILES['companyLogo']['error'] = $_FILES['companyLogo']['error'];
                $_FILES['companyLogo']['size'] = $_FILES['companyLogo']['size'];
                $config['upload_path'] = 'uploads/vendor/logo/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = 1024;
                $config['remove_spaces'] = true;
                $config['overwrite'] = false;
                $config['max_width'] = '';
                $config['max_height'] = '';
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('companyLogo')) {
                    $this->session->set_flashdata('error', 'The uploaded file exceeds the maximum allowed size (1MB)');
                } else {
                    $image_uploaded = $this->upload->data();

                    $config['image_library'] = 'gd2';
                    $config['quality'] = '60';
                    $config['source_image'] = $this->upload->upload_path . $this->upload->file_name;
                    $this->load->library('image_lib', $config);
                    $this->image_lib->resize();
                }
                if ($image_uploaded != null) {
                    $fileName = $image_uploaded['file_name'];
                }
                if ($fileName != null) {
                    $file_data = array(
                        'model_id' => $vendor_id,
                        'model_name' => 'vendor',
                        'photo' => $fileName,
                        'image_type' => 'logo',
                        'updated_at' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s'),
                    );
                    $company_logo_details = $this->Images_model->get_by(array('model_name' => 'vendor', 'model_id' => $vendor_id));
                    if ($company_logo_details != "") {
                        $this->Images_model->update($company_logo_details->id, $file_data);
                    } else {
                        $this->Images_model->insert($file_data);
                    }
                }
            }
            header('Location: vendor-settings-dashboard');
        } else {
            $this->session->set_flashdata('error', 'Please contact Vendor to update vendor details.');
            header('Location: vendor-dashboard');
        }
    }

    public function update_password() {
        $vendor_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $vendor_roles))) {
            $user_id = $_SESSION['user_id'];
            $currentpassword = md5($this->input->post('pwCurrent'));
            $user_detail = $this->User_model->get($user_id);
            $db_password = $user_detail->password;
            if ($currentpassword == $db_password) {
                $newpassword = md5($this->input->post('password'));
                $confirmpassword = md5($this->input->post('passwordNew'));
                if (($currentpassword != $newpassword) && ($newpassword == $confirmpassword)) {
                    $update_data = array(
                        'reset_password_token' => "",
                        'password' => $newpassword,
                        'password_last_updated_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    if ($update_data != null) {
                        $this->User_model->update($user_id, $update_data);
                        $this->session->set_flashdata('success', 'Password updated successfully.');
                        header('Location: vendor-settings-dashboard');
                    } else {
                        $this->session->set_flashdata('error', 'Error saving password.');
                        header('Location: vendor-settings-dashboard');
                    }
                } else {
                    $this->session->set_flashdata('error', 'New password and confirm password do not match.');
                    header('Location: vendor-settings-dashboard');
                }
            } else {
                $this->session->set_flashdata('error', 'Passwords do not match..');
                header('Location: vendor-settings-dashboard');
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     * Vendor Dashboard
     *  Vendor Products wil be shown here.
     */

    public function vendor_products() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $Category_select = $this->input->get("categorySelect");
            $productStatus = $this->input->get("productStatus");
            $promos = $this->input->get('promos');
            $user_id = $_SESSION['user_id'];
            $siteId = $this->input->get('site_id');
            if(empty($siteId)){
                $siteId = (empty($this->input->get('siteSelect'))) ? 0 : $this->input->get('siteSelect');
            }

            $data['vendor_detail'] = $this->Vendor_groups_model->get_by(array('user_id' => $user_id));
            if ($data['vendor_detail'] != null) {
                $vendor_id = $data['vendor_detail']->vendor_id;
            }

            $data['siteInfo'] = $this->Whitelabel_model->load($siteId);
            Debugger::debug($vendor_id, 'vendor id');
            Debugger::debug($data['siteInfo'], 'site');
            $data['siteId'] = $siteId;
            $data['vendorId'] = $vendor_id;
            $search = $this->input->get('search');
            $order_by = $this->input->get('order_by');
            $data['order_by'] = ($order_by != null) ? $order_by : 0;
            $data['limit'] = 30;
            $valuein = $this->input->get("p");
            $offset = 0;
            if ($valuein != null) {
                $offset = ($valuein - 1) * $data['limit'];
            }
            $data['sites'] = $this->Whitelabel_model->loadAll();
            if ($search != null && $search != "") {
                $data['search'] = $search;

                $vendor_products = $this->Vendor_model->searchProducts($vendor_id, $search, $order_by, $data['limit'], $offset);
                // Debugger::debug($vendor_products);
                $resultsCount = $this->Vendor_model->getSearchTotalCount($vendor_id, $search, $order_by);
            } else {
                $data['search'] = "";

                $vendor_products = $this->Vendor_model->loadProducts($vendor_id, $promos, $Category_select, $productStatus, $order_by, $data['limit'], $offset, null, $siteId);
                Debugger::debug($vendor_products, $siteId);
                if (($Category_select != null) || ($promos != null) || ($productStatus != null)) {
                    if ($Category_select != null && $Category_select != "") {
                        $Category_select = "and p.category_id like '%\"$Category_select\"%'";
                    } else {
                        $Category_select = "";
                    }
                    if ($promos == 2) {
                        $promoValue = " INNER ";
                    } else {
                        $promoValue = " LEFT ";
                    }
                    if ($productStatus == null && $productStatus == "") {
                        $productStatus = 1;
                    }
                    $total_count_query = "select count(pp.id) as vendor_products_count from products as p LEFT JOIN product_pricings as pp ON p.id=pp.product_id $promoValue JOIN promo_codes as pc ON p.id = pc.product_id and pp.vendor_id = pc.vendor_id where pp.vendor_id=$vendor_id $Category_select and pp.active=$productStatus";
                } else {
                    $total_count_query = "select count(pp.id) as vendor_products_count from products as p LEFT JOIN product_pricings as pp ON p.id=pp.product_id LEFT JOIN promo_codes as pc ON p.id = pc.product_id and pp.vendor_id = pc.vendor_id where pp.vendor_id=$vendor_id";
                }
                $total_vendor_products = $this->db->query($total_count_query)->result();
                $resultsCount = $total_vendor_products[0]->vendor_products_count;
            }

            $data['vendor_products'] = $vendor_products;
            $data['total_rows'] = $resultsCount;

            $this->load->library('pagination');
            $config['base_url'] = base_url() . '/vendor-products-dashboard';
            $config['total_rows'] = $data['total_rows'];
            $config['per_page'] = $data['limit'];
            $this->pagination->initialize($config);
            $config['enable_query_strings'] = TRUE;
            $config['page_query_string'] = TRUE;
            $config['use_page_numbers'] = TRUE;

            // Categories START ***
            $queryproduct = $this->db->query("select *,f.id as location_id from product_pricings a INNER JOIN products b on b.id=a.product_id INNER JOIN order_items c on c.product_id=a.product_id INNER JOIN orders d on d.id=c.order_id INNER JOIN user_locations e on e.user_id=d.user_id INNER JOIN organization_locations f on f.id=e.organization_location_id where d.restricted_order='0'and a.vendor_id=$vendor_id");
            $data['products'] = $queryproduct->result();
            if ($data['products'] != null) {
                for ($i = 0; $i < count($data['products']); $i++) {
                    $data['productId'][] = $data['products'][$i]->id;
                    $data['location'][] = $data['products'][$i]->location_id;
                    $data['vendor'][] = $data['products'][$i]->vendor_id;
                    $data['catId'][] = explode(",", str_replace('"', '', $data['products'][$i]->category_id));
                }
                $data['catIdCount'] = $data['catId'];
                $classic_categories = $this->Category_model->get_many_by(array('parent_id' => 1));
                for ($i = 0; $i < count($classic_categories); $i++) {
                    $count = 0;
                    for ($j = 0; $j < count($data['catIdCount']); $j++) {
                        if (in_array($classic_categories[$i]->id, $data['catIdCount'][$j])) {
                            $count +=1;
                        }
                    }
                    $classic_categories[$i]->count = $count;
                }
                $data['classic'] = $classic_categories;
                $densist_categories = $this->Category_model->get_many_by(array('parent_id' => 2));
                for ($i = 0; $i < count($densist_categories); $i++) {
                    $count = 0;
                    for ($j = 0; $j < count($data['catIdCount']); $j++) {
                        if (in_array($densist_categories[$i]->id, $data['catIdCount'][$j])) {
                            $count +=1;
                        }
                    }
                    $densist_categories[$i]->count = $count;
                }
                $data['dentist'] = $densist_categories;
            }
            // Categories END ***

            $data['My_vendor_users'] = "";
            $data['vendor_shipping'] = "";    // Defined for the #edit-user.php Modal
            $data['promoCodes_active'] = "";
            $data['NorderCount'] = order_count(); // To Get the Latest Order Count.
            $data['ReturnCount'] = return_count();
            $this->load->view('/templates/_inc/header-vendor.php');
            $this->load->view('/templates/vendor-admin/products/index.php', $data);
            $this->load->view('/templates/_inc/footer-vendor.php');

        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     * Vendor Dashboard
     *  @products ->single Page
     *      1. To update price and add promo code for the Product.
     */

    public function vendor_productsPrice_edit() {
        $admin_roles = unserialize(ROLES_VENDORS);
        Debugger::debug($admin_roles, '$admin_roles');
        if($this->User_model->can($_SESSION['user_permissions'], 'edit-pricing')  ){
            $user_id = $_SESSION['user_id'];
            $vendor_id = $_SESSION['vendor_id'];
            if ($vendor_id != null) {
                $productPrice_id = $this->input->get('productPrice_id');

                $data['productPricing'] = $this->Product_pricing_model->get_by(['id' => $productPrice_id]);
                if (!isset($data['productPricing'])) {
                    $this->session->set_flashdata('error', 'Invalid Entry');
                    header('Location: vendor-dashboard');
                } else {
                    //  1. To get The Product_Name from the Given ID.
                    $data['productName'] = $this->Products_model->get_by(array('id' => $data['productPricing']->product_id));
                    $data['productName']->product_image = "";
                    if ($data['productName'] != null) {
                        $product_images = $this->Images_model->get_by(array('model_name' => 'products', 'image_type' => 'mainimg', 'model_id' => $data['productPricing']->product_id));
                        if ($product_images != null) {
                            $data['productName']->product_image = $product_images;
                        }
                    }
                    //  2. Getting the Promo_codes for the Particular Product based on Vendor.
                    $data['promoCodes'] = $this->Promo_codes_model->get_by(array('product_id' => $data['productPricing']->product_id, 'vendor_id' => $vendor_id));
                    if ($data['promoCodes'] != null) {
                        $data['promoCodes']->free_product_name = "";
                        $data['freeproductName'] = $this->Products_model->get($data['promoCodes']->free_product_id);
                        Debugger::debug($data['freeproductName']);
                        if (!empty($data['freeproductName'])) {
                            $data['promoCodes']->free_product_name = $data['freeproductName']->name;
                        }
                    }

                    $data['My_vendor_users'] = "";
                    $data['vendor_shipping'] = "";    // Defined for the #edit-user.php Modal
                    $data['promoCodes_active'] = "";
                    $data['NorderCount'] = order_count(); // To Get the Latest Order Count.
                    $data['ReturnCount'] = return_count();
                    $this->load->view('/templates/_inc/header-vendor.php');
                    $this->load->view('/templates/vendor-admin/products/sku/index.php', $data);
                    $this->load->view('/templates/_inc/footer-vendor.php');
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function vendor_productPrice_update() {
        $vendor_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $vendor_roles))) {
            Debugger::debug($_POST);
            $productPricing_id = $this->input->post('productPricing_id');
            $salePrice = str_replace('$', '', $this->input->post('salePrice'));
            $price = str_replace('$', '', $this->input->post('productPrice'));
            $saleCurrency = str_replace(',', '', $salePrice);
            $currency = str_replace(',', '', $price);
            $excludeMarketplace = $this->input->post('exclude_from_marketplace');
            if ($productPricing_id != null) {
                $productPrice_data = array(
                    'retail_price' => $currency,
                    'price' => $saleCurrency,
                    'exclude_from_marketplace' => $excludeMarketplace,
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                $this->Product_pricing_model->update($productPricing_id, $productPrice_data);
            }
            $this->session->set_flashdata('success', 'Promotions and Pricing for the Products are updated');
            header("Location: product-pricing-vendorEdit?productPrice_id=" . $productPricing_id);
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function vendor_productSku_update() {
        $vendor_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $vendor_roles))) {
            $productPricing_id = $this->input->post('productPricing_id');
            if ($productPricing_id != null) {
                $productPrice_data = array(
                    'vendor_product_id' => $this->input->post('productSKU'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                $this->Product_pricing_model->update($productPricing_id, $productPrice_data);
            }
            $this->session->set_flashdata('success', 'Promotions and Pricing for the Products are updated');
            header("Location: product-pricing-vendorEdit?productPrice_id=" . $productPricing_id);
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *  VendorDashboard
     *      @AJAX call
     *      Free product Search for Promo code(Product).
     */

    public function search_promoProduct() {
        $vendor_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $vendor_roles))) {
            $vendor_id = $_SESSION['vendor_id'];
            if ($vendor_id != null) {
                $search = $this->input->post('search');
                $query = "SELECT a.vendor_product_id,b.name,b.id as product_id FROM product_pricings a INNER join products  b on b.id=a.product_id where (a.vendor_product_id like '%$search%' or b.name like '%$search%' or b.mpn like '%$search%')  and vendor_id=$vendor_id";
                $data['vendor_promoProduct'] = $this->db->query($query)->result();
                echo json_encode($data['vendor_promoProduct']);
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    //  VendorDashboard ->  @Shipping
    public function shipping_partners() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {

            $user_id = $_SESSION['user_id'];
            $vendor_detail = $this->Vendor_groups_model->get_by(array('user_id' => $user_id));
            $vendor_id = $vendor_detail->vendor_id;
            if ($vendor_id != null) {
                $data['vendor_shipping'] = $this->Shipping_options_model->get_many_by(array('vendor_id' => $vendor_id));
                $data['My_vendor_users'] = ""; // Defined for the #edit-user.php Modal
                $data['promoCodes_active'] = "";
                $data['NorderCount'] = order_count(); // To Get the Latest Order Count.
                $data['ReturnCount'] = return_count();
                
                $this->load->view('/templates/_inc/header-vendor.php');
                $this->load->view('/templates/vendor-admin/shipping/index.php', $data);
                $this->load->view('/templates/_inc/footer-vendor.php');

            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    //  VendorDashboard ->  @Shipping  Add Shipping for Vendor.
    public function addVendor_shipping_dashboard() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $user_id = $_SESSION['user_id'];
            $vendor_detail = $this->Vendor_groups_model->get_by(array('user_id' => $user_id));
            $vendor_id = $vendor_detail->vendor_id;
            $shipping_price = str_replace('$', '', $this->input->post('createShippingCost'));
            $currency = str_replace(',', '', $shipping_price);
            $insert_data = array('carrier' => $this->input->post('createShippingCarrier'),
                'shipping_type' => $this->input->post('createShippingType'),
                'delivery_time' => $this->input->post('createShippingSpeed'),
                'max_weight' => $this->input->post('createMaxWeight'),
                'max_dimension' => $this->input->post('createMaxDimensions'),
                'shipping_price' => $currency,
                'description' => $this->input->post('description'),
                'restrictions' => $this->input->post('restrictions'),
                'vendor_id' => $vendor_id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            if ($insert_data != null) {
                $insert_data = $this->Shipping_options_model->insert($insert_data);
                $this->session->set_flashdata('success', 'Shipping options saved.');
                header('Location: vendor-shipping-partners');
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

//  VendorDashboard ->  @Shipping // Update shipping Information
    public function updateVendor_shipping_dashboard() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $user_id = $_SESSION['user_id'];
            $vendor_detail = $this->Vendor_groups_model->get_by(array('user_id' => $user_id));
            $vendor_id = $vendor_detail->vendor_id;
            if ($vendor_id != null) {
                $shipping_id = $this->input->post('shipping_id');
                if ($shipping_id != null) {
                    $shipping_price = str_replace('$', '', $this->input->post('editShippingCost'));
                    $currency = str_replace(',', '', $shipping_price);
                    $update_data = array(
                        'carrier' => $this->input->post('editShippingCarrier'),
                        'shipping_type' => $this->input->post('editShippingType'),
                        'delivery_time' => $this->input->post('editShippingSpeed'),
                        'max_weight' => $this->input->post('editMaxWeight'),
                        'max_dimension' => $this->input->post('editMaxDimensions'),
                        'shipping_price' => $currency,
                        'description' => $this->input->post('description'),
                        'restrictions' => $this->input->post('restrictions'),
                        'vendor_id' => $vendor_id,
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    if ($update_data != null) {
                        $this->Shipping_options_model->update($shipping_id, $update_data);
                        $this->session->set_flashdata('success', 'Shipping methods updated successfully.');
                        header("Location: vendor-shipping-partners");
                    }
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *  Vendor Dashboard
     *      @Settings
     *          1.Vendor details
     */

    public function vendor_settings_dashboard() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $user = $this->User_model->get_by(array('id' => $_SESSION['user_id']));

            $vendor = $this->Vendor_model
                ->select('vendors.*, vendor_groups.user_id, vendor_groups.vendor_id')
                ->join('vendor_groups', 'vendors.id = vendor_groups.vendor_id ')
                ->get_by(array('user_id' => $user->id));

            try {
                $account = $this->stripe->getAccount($vendor->payment_id);
            }catch (Exception $exception){
                $this->session->set_flashdata('error', 'Stripe account for vendor doesn\'t exist');
            }


            $data['company_logo'] = $this->Images_model->get_by(array('model_name' => 'vendor', 'model_id' => $vendor->id));
            $data['profile_photo'] = $this->Images_model->get_by(array('model_name' => 'user', 'model_id' => $user->id));
            $data['vendor_settings'] = $this->Vendor_model->get($vendor->id);
            $data['bank'] = $account->external_accounts->data[0];

            $tax_info = new stdClass();
            $tax_info->ssn_last_4_provided = $account->legal_entity->ssn_last_4_provided;
            $tax_info->personal_id_number_provided =  $account->legal_entity->personal_id_number_provided;
            $tax_info->business_tax_id_provided = $account->legal_entity->business_tax_id_provided;
            $data['tax_info'] = $tax_info;

            $tos_info = new stdClass();
            $tos_info->date = $account->tos_acceptance->date;
            $tos_info->ip = $account->tos_acceptance->ip;
            $tos_info->user_agent = $account->tos_acceptance->user_agent;
            $data['tos_info'] = $tos_info;

            $query = "SELECT * from business_hours where vendor_id=$vendor->id group by open_time, close_time";
            $data['business'] = $this->db->query($query)->result();
            $data['all_business'] = $this->Business_hour_model->get_many_by(array('vendor_id' => $vendor->id));
            $data['business_hours'] = $this->Business_hour_model->get_many_by(array('vendor_id' => $vendor->id));
            if ($data['business_hours'] != null) {
                for ($i = 0; $i < count($data['business_hours']); $i++) {
                    $data['business_hours'][$i]->status = "";
                    if ($data['business_hours'][$i]->day != null) {
                        $data['business_hours'][$i]->status = 1;
                    }
                }
            }
            $c_date = strtotime("now");
            $password_changed = strtotime($user->password_last_updated_at);
            if ($password_changed != "") {
                $changed_time = $c_date - $password_changed;
                $data['password_last_updated'] = $this->User_model->humanTiming($changed_time);
            } else {
                $password_changed = strtotime($user->created_at);
                $changed_time = $c_date - $password_changed;
                $data['password_last_updated'] = $this->User_model->humanTiming($changed_time);
            }
            $data['vendor_policies'] = "";
            if ($vendor != null) {
                $data['vendor_policies'] = $this->Vendor_policies_model->get_many_by(array('vendor_id' => $vendor->id));
            }
            $data['My_vendor_users'] = "";
            $data['vendor_shipping'] = "";    // Defined for the #edit-user.php Modal
            $data['promoCodes_active'] = "";
            $data['NorderCount'] = order_count(); // To Get the Latest Order Count.
            $data['ReturnCount'] = return_count();
            $data['vendor'] = $vendor;
            $data['user'] = $user;
            $this->load->view('templates/vendor-admin/settings/index', $data);
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *  Vendor Dashboard
     *      @Settings
     *          1.Vendor Profile image edit.
     */

    public function vendor_userProfile() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            if ($_FILES['companyLogo']['name'] != null) {
                $new_file_name = time() . preg_replace('/[^a-zA-Z0-9_.]/', '_', $_FILES['companyLogo']['name']);
                $_FILES['companyLogo']['name'] = $new_file_name;
                $_FILES['companyLogo']['type'] = $_FILES['companyLogo']['type'];
                $_FILES['companyLogo']['tmp_name'] = $_FILES['companyLogo']['tmp_name'];
                $_FILES['companyLogo']['error'] = $_FILES['companyLogo']['error'];
                $_FILES['companyLogo']['size'] = $_FILES['companyLogo']['size'];
                $config['upload_path'] = 'uploads/user/profile/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = 1024;
                $config['remove_spaces'] = true;
                $config['overwrite'] = false;
                $config['max_width'] = '';
                $config['max_height'] = '';
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('companyLogo')) {
                    $this->session->set_flashdata('error', 'The uploaded file exceeds the maximum allowed size (1MB)');
                } else {
                    $image_uploaded = $this->upload->data();

                    $config['image_library'] = 'gd2';
                    $config['quality'] = '60';
                    $config['source_image'] = $this->upload->upload_path . $this->upload->file_name;
                    $this->load->library('image_lib', $config);
                    $this->image_lib->resize();
                }
                //                    $fileName = str_replace(' ', '_', $_FILES['companyLogo']['name']);
                if ($image_uploaded != null) {
                    $fileName = $image_uploaded['file_name'];
                }
                if ($fileName != null) {
                    $file_data = array(
                        'model_id' => $_SESSION['user_id'],
                        'model_name' => 'user',
                        'photo' => $fileName,
                        'image_type' => 'logo',
                        'updated_at' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s'),
                    );
                    $profile_photo = $this->Images_model->get_by(array('model_name' => 'user', 'model_id' => $_SESSION['user_id']));
                    if ($profile_photo != "") {
                        $this->Images_model->update($profile_photo->id, $file_data);
                    } else {
                        $this->Images_model->insert($file_data);
                    }
                }
            }
            header('Location: vendor-settings-dashboard');
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *  Vendor Dashboard
     *      @Settings
     *          1.Update Bank details
     */

    public function update_bankDetails() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            try {
                $user = $this->User_model->get_by(['id' => $_SESSION['user_id']]);

                $vendor = $this->Vendor_model
                    ->select('vendors.*, vendor_groups.user_id, vendor_groups.vendor_id')
                    ->join('vendor_groups', 'vendors.id = vendor_groups.vendor_id ')
                    ->get_by(['user_id' => $user->id]);

                $dob = strtotime($this->input->post('vendor_dob'));
                if ($vendor->payment_id) {
                    $account = $this->stripe->getAccount($vendor->payment_id);
                } else {
                    $account = $this->stripe->addAccount(
                        [
                            'type' => 'custom',
                            'country' => 'US',
                            'email' => $vendor->email,
                        ]
                    );
                }

                foreach ($account->external_accounts->all() as $bank) {
                    $bank->delete();
                }

                $account->legal_entity->address->city = $vendor->city;
                $account->legal_entity->address->line1 = $vendor->address1;
                $account->legal_entity->address->postal_code = $vendor->zip;
                $account->legal_entity->address->state = $vendor->state;
                $account->legal_entity->dob->day = date("d", $dob);
                $account->legal_entity->dob->month = date("m", $dob);
                $account->legal_entity->dob->year = date("Y", $dob);
                $account->legal_entity->first_name = $user->first_name;
                $account->legal_entity->last_name = $user->last_name;

                $account->tos_acceptance->date = time();
                $account->tos_acceptance->ip = $_SERVER['REMOTE_ADDR'];

                $account->legal_entity->type = $this->input->post('account_holder_type');
                if ($account->legal_entity->type == 'company') {
                    //$account->legal_entity->additional_owners = ;
                    $account->legal_entity->business_name = $vendor->name;
                    $account->legal_entity->business_tax_id = $vendor->tax_id;
                    $account->legal_entity->personal_address->city = $vendor->personal_city;
                    $account->legal_entity->personal_address->line1 = $vendor->personal_address1;
                    $account->legal_entity->personal_address->postal_code = $vendor->personal_zip;
                }
                $account->save();

                $bank = $account->external_accounts->create(["external_account" => $this->input->post('token')]);
                $this->Vendor_model->update(
                    $vendor->id,
                    [
                        'account_holder_name' => $account->business_name,
                        'payment_id' => $account->id,
                        'account_type' => $account->legal_entity->type,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]
                );
            }catch (Exception $exception){
                $this->session->set_flashdata('error', $exception->getMessage());
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *  Vendor Dashboard
     *      @Settings
     *          1.Update Name
     */

    public function vendor_settings_update() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $name = $this->input->post('name');
            if ($name != null) {
                $update_Vname = array(
                    'name' => $name,
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                if ($update_Vname != null) {
                    $vendor_id = $this->input->post('id');
                    $this->Vendor_model->update($vendor_id, $update_Vname);
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *  Vendor Dashboard
     *      @Settings
     *          1.Update Shipping Address
     */

    public function vendor_settings_updateShipping() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $update_shipping = array(
                'shipment_address1' => $this->input->post('companyAddress1'),
                'shipment_address2' => $this->input->post('companyAddress2'),
                'shipment_state' => $this->input->post('shipment_state'),
                'shipment_city' => $this->input->post('companyCity'),
                'shipment_zip' => $this->input->post('companyZip'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            if ($update_shipping != null) {
                $vendor_id = $this->input->post('vendor_id');
                $this->Vendor_model->update($vendor_id, $update_shipping);
                header('Location: vendor-settings-dashboard');
            }
        }
    }

    /*
     *  Vendor Dashboard
     *      @Settings
     *          1.Update Personal Address.
     */

    public function vendor_settings_updatepersonal() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $update_shipping = array(
                'personal_address1' => $this->input->post('personalAddress1'),
                'personal_address2' => $this->input->post('personalAddress2'),
                'personal_city' => $this->input->post('personalcity'),
                'personal_state' => $this->input->post('personal_state'),
                'personal_zip' => $this->input->post('personalZip'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            if ($update_shipping != null) {
                $vendor_id = $this->input->post('vendor_id');
                $this->Vendor_model->update($vendor_id, $update_shipping);
                header('Location: vendor-settings-dashboard');
            }
        }
    }


    /*
     *  Vendor Dashboard
     *      @Settings
     *          1.Agree Terms of Service
     */
    public function vendor_settings_agree_tos(){
        $admin_roles = unserialize(ROLES_VENDORS);

        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {

            $user = $this->User_model->get_by(array('id' => $_SESSION['user_id']));

            $vendor = $this->Vendor_model
                ->select('vendors.*, vendor_groups.user_id, vendor_groups.vendor_id')
                ->join('vendor_groups', 'vendors.id = vendor_groups.vendor_id ')
                ->get_by(array('user_id' => $user->id));

            if ($vendor->payment_id) {
                $account = $this->stripe->getAccount($vendor->payment_id);
            } else {
                $account = $this->stripe->addAccount([
                    'type' => 'custom',
                    'country' => 'US',
                    'email' => $vendor->email,
                ]);
            }

            $account->tos_acceptance->date = time();
            $account->tos_acceptance->ip = $_SERVER['SERVER_ADDR'];

            try{
                $account->save();

                $this->Vendor_model->update($vendor->id, [
                    'payment_id' => $account->id,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }catch (Exception $exception){
                $this->session->set_flashdata('error', $exception->getMessage());
            }

            header("Location:vendor-settings-dashboard");
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *  Vendor Dashboard
     *      @Settings
     *          1.Update Vendor Business Address
     */

    public function vendor_settings_businessAddress() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $update_shipping = array(
                'address1' => $this->input->post('businessAddress1'),
                'address2' => $this->input->post('businessAddress2'),
                'city' => $this->input->post('businessCity'),
                'zip' => $this->input->post('businessZip'),
                'state' => $this->input->post('state'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            if ($update_shipping != null) {
                $vendor_id = $this->input->post('vendor_id');
                $this->Vendor_model->update($vendor_id, $update_shipping);
                header('Location: vendor-settings-dashboard');
            }
        }
    }

    /*
     *  Vendor Dashboard
     *      @Settings
     *          1.Update Vendor Contact details.
     */

    public function vendor_contactsUpdate() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $phone = $this->input->post('companyPhone');
            $phoneNum = preg_replace('/(\W*)/', '', $phone);
            $update_shipping = array(
                'phone' => $phoneNum,
                'email' => $this->input->post('companyEmail'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            if ($update_shipping != null) {
                $vendor_id = $this->input->post('vendor_id');
                $this->Vendor_model->update($vendor_id, $update_shipping);
                header('Location: vendor-settings-dashboard');
            }
        }
    }

    /*
     *  Vendor Dashboard
     *      @Settings
     *          1.Update Vendor Description
     */

    public function update_aboutUs() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $description = $this->input->post('description');
            $update_shipping = array(
                'description' => $this->input->post('description'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            if ($update_shipping != null) {
                $vendor_id = $this->input->post('vendor_id');
                $this->Vendor_model->update($vendor_id, $update_shipping);
                header('Location: vendor-settings-dashboard');
            }
        }
    }

    /*
     *  Vendor Dashboard
     *      @Settings
     *          1.Update Vendor Short Description
     */

    public function update_vendorBio() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $update_shipping = array(
                'short_description' => $this->input->post('short_description'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            if ($update_shipping != null) {
                $vendor_id = $this->input->post('vendor_id');
                $this->Vendor_model->update($vendor_id, $update_shipping);
                header('Location: vendor-settings-dashboard');
            }
        }
    }

    /*
     *  Vendor Dashboard
     *      @Settings
     *          1.Update Vendor Email settings.
     */

    public function vendor_email_settings() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $update_id = $_SESSION['user_id'];
            $e3 = $this->input->post('e3');
            $e6 = $this->input->post('e6');
            $e2 = $this->input->post('e2');
            $update_data = array(
                'email_setting3' => $e3,
                'email_setting6' => $e6,
                'email_setting2' => $e2,
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $this->User_model->update($update_id, $update_data);
            header("Location:vendor-settings-dashboard");
        }
    }

    /*
     *  Vendor Dashboard
     *      @Settings
     *          1.Update Vendor Tax details update
     */

    public function vendor_settings_updatetax() {
        $admin_roles = unserialize(ROLES_VENDORS);

        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {

            $user = $this->User_model->get_by(array('id' => $_SESSION['user_id']));

            $vendor = $this->Vendor_model
                ->select('vendors.*, vendor_groups.user_id, vendor_groups.vendor_id')
                ->join('vendor_groups', 'vendors.id = vendor_groups.vendor_id ')
                ->get_by(array('user_id' => $user->id));

            $tax_id = $this->input->post('companyTaxID');
            $ssn_last4 = substr($this->input->post('ssnLast'), -4);
            $personal_id_number = $this->input->post('personalId');

            if ($vendor->payment_id) {
                $account = $this->stripe->getAccount($vendor->payment_id);
            } else {
                $account = $this->stripe->addAccount([
                    'type' => 'custom',
                    'country' => 'US',
                    'email' => $vendor->email,
                ]);
            }

            $account->legal_entity->ssn_last_4 = $ssn_last4;
            $account->legal_entity->personal_id_number = $personal_id_number;
            $account->legal_entity->business_tax_id = $tax_id;
            try{
                $account->save();

                if ($account->tos_acceptance->date ==  null){
                    $this->session->set_flashdata('error', 'You must accept Terms of Service to complete Tax ID verification');
                }
            }catch (Exception $exception){
                $this->session->set_flashdata('error', $exception->getMessage());
            }


            $this->Vendor_model->update($vendor->id, [
                'payment_id' => $account->id,
                'account_type' => $account->legal_entity->type,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            header("Location:vendor-settings-dashboard");
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }

    }

    /*
     *  Vendor Dashboard
     *      @Settings
     *          1.Update Vendor Bank Details
     */

    public function vendor_settings_updatebankDetails() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $update_Vname = array(
                'account_number' => $this->input->post('paymentAccountNum'),
                'routing_number' => $this->input->post('paymentRoutingNum'),
                'bank_name' => $this->input->post('paymentBankName'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            if ($update_Vname != null) {
                $vendor_id = $this->input->post('vendor_id');
                $this->Vendor_model->update($vendor_id, $update_Vname);
                header('Location: vendor-settings-dashboard');
            }
        }
    }

    /*
     *  Vendor Dashboard
     *      @Settings
     *          1.Update Vendor refund Instructions
     */

    public function vendor_settings_updaterefunds() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $update_Vname = array(
                'refund_instructions' => $this->input->post('refundInstruction'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            if ($update_Vname != null) {
                $vendor_id = $this->input->post('vendor_id');
                $this->Vendor_model->update($vendor_id, $update_Vname);
                header('Location: vendor-settings-dashboard');
            }
        }
    }

    /*
     *  Vendor Dashboard
     *      @Settings
     *          1.Update Vendor Policies.
     */

    public function vendor_settings_updatepolicies() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $update_Vname = array(
                'description' => $this->input->post('vendor_policies'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            if ($update_Vname != null) {
                $vendor_id = $this->input->post('id');
                $this->Vendor_model->update($vendor_id, $update_Vname);
            }
        }
    }

    /*
     * Vendor Dashboard
     *      @Customers
     *          1.Customers purchased from this vendor will be showm here.
     */

    public function vendors_customer() {
        /*
         *  Based on Vendors id the customers are shown.
         */
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $user_id = $_SESSION['user_id'];
            $vendor_id = $_SESSION['vendor_id'];
            Debugger::debug($_SESSION);
            $search = $this->input->get('search');
            $data['limit'] = 30;
            $valuein = $this->input->get("p");
            $offset = 0;
            if ($valuein != null) {
                $offset = ($valuein - 1) * $data['limit'];
            }
            if ($search != null && $search != "") {
                $query = 'SELECT c.id as user_id,c.first_name as name,b.id,count(b.id) as quantity,c.email,e.organization_name,b.created_at,sum(b.total) as total  FROM order_items a INNER JOIN orders b on b.id=a.order_id and b.restricted_order="0" INNER JOIN users c on b.user_id=c.id INNER JOIN organization_groups d on d.user_id=c.id INNER JOIN organizations e on d.organization_id=e.id where b.vendor_id=' . $vendor_id . ' and (c.first_name like "%' . $search . '%" or c.last_name like "%' . $search . '%" or e.organization_name like "%' . $search . '%") group by b.user_id limit ' . $offset . ',' . $data['limit'] . '';
                $data['customer_vendors'] = $this->db->query($query)->result();
                $data['total_count'] = 0;
                $query1 = "SELECT count(*) as search_count FROM (SELECT c.id as user_id,c.first_name as name,b.id,count(b.id) as quantity,c.email,e.organization_name,b.created_at,sum(b.total) as total  FROM order_items a INNER JOIN orders b on b.id=a.order_id and b.restricted_order='0' INNER JOIN users c on b.user_id=c.id INNER JOIN organization_groups d on d.user_id=c.id INNER JOIN organizations e on d.organization_id=e.id where b.vendor_id=' . $vendor_id . ' and (c.first_name like '%" . $search . "%' or c.last_name like '%" . $search . "%' or e.organization_name like '%" . $search . "%') group by b.user_id) count";
                $count_query = $this->db->query($query1)->result();
                if ($count_query != null) {
                    $data['total_count'] = $count_query[0]->search_count;
                }
            } else {
                $query = 'SELECT c.id as user_id,c.first_name as name,b.id,count(b.id) as quantity,c.email,e.organization_name,b.created_at,sum(b.total) as total  FROM order_items a INNER JOIN orders b on b.id=a.order_id and b.restricted_order="0" INNER JOIN users c on b.user_id=c.id INNER JOIN organization_groups d on d.user_id=c.id INNER JOIN organizations e on d.organization_id=e.id where b.vendor_id=' . $vendor_id . ' group by b.user_id limit ' . $offset . ',' . $data['limit'] . '';
                Debugger::debug($query);
                $data['customer_vendors'] = $this->db->query($query)->result();
                $data['total_count'] = 0;
                $query1 = "SELECT count(*) as search_count FROM (SELECT c.id as user_id,c.first_name as name,b.id,count(b.id) as quantity,c.email,e.organization_name,b.created_at,sum(b.total) as total  FROM order_items a INNER JOIN orders b on b.id=a.order_id and b.restricted_order=0 INNER JOIN users c on b.user_id=c.id INNER JOIN organization_groups d on d.user_id=c.id INNER JOIN organizations e on d.organization_id=e.id where b.vendor_id=" . $vendor_id . " group by b.user_id) count";
                $count_query = $this->db->query($query1)->result();
                if ($count_query != null) {
                    $data['total_count'] = $count_query[0]->search_count;
                }
            }
            $this->load->library('pagination');
            $config['base_url'] = base_url() . '/vendors-customer-dashboard';
            $config['total_rows'] = $data['total_count'];
            $config['per_page'] = $data['limit'];
            $this->pagination->initialize($config);
            $config['enable_query_strings'] = TRUE;
            $config['page_query_string'] = TRUE;
            $config['use_page_numbers'] = TRUE;

            $data['My_vendor_users'] = "";
            $data['vendor_shipping'] = "";
            $data['promoCodes_active'] = "";
            $data['NorderCount'] = order_count(); // To Get the Latest Order Count.
            $data['ReturnCount'] = return_count();
            $this->load->view('/templates/_inc/header-vendor.php');
            $this->load->view('/templates/vendor-admin/customers/index.php', $data);
            $this->load->view('/templates/_inc/footer-vendor.php');

        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function customer_purchase_details() {
        /*
         *  1. Customer's First order with this Vendor should be shown.
         */
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $vendor_id = $_SESSION['vendor_id'];
            $user_id = $this->input->get('user_id');
            $user = $this->User_model->get_by('id', $user_id);

            if ($user_id != null) {
                // Condition check for Vendor Customer Users or Not
                $customer_info = $this->User_model->CustomerCheck($user_id, $vendor_id);
                if ($customer_info == null) {
                    $this->session->set_flashdata('error', 'Invalid Entry');
                    header('Location: vendor-dashboard');
                } else {
                    $query = "SELECT e.photo,a.*,c.organization_name,sum(d.total)as total FROM users a INNER JOIN organization_groups b on b.user_id=a.id INNER JOIN organizations c on c.id=b.organization_id INNER JOIN orders d on d.user_id=a.id LEFT JOIN images e on e.model_name='user' and e.model_id=a.id where d.restricted_order = '0' and a.id=$user_id";
                    $data['user_details'] = $this->db->query($query)->result();
                    $startDate = date("Y-m-d", strtotime("-1 year"));
                    $now = date('Y-m-d', now());
                    $data['orderList'] = $this->Order_model->order_by('id', 'desc')->get_many_by(array("created_at BETWEEN '" . $startDate . "' and '" . $now . "'", 'user_id' => $user_id, 'restricted_order' => '0'));
                    if ($data['orderList'] != null) {
                        for ($i = 0; $i < count($data['orderList']); $i++) {
                            $data['orderList'][$i]->location = "";
                            $orderLocation = $this->Organization_location_model->get_by(array('id' => $data['orderList'][$i]->location_id));
                            if ($orderLocation != null) {
                                $data['orderList'][$i]->location = $orderLocation->nickname;
                            }
                        }
                    }
                    $query = "SELECT * FROM users a INNER JOIN user_locations e on e.user_id=a.id INNER JOIN organization_locations f on e.organization_location_id=f.id INNER JOIN organizations g on g.id=f.organization_id where a.id=$user_id";
                    $data['organization_details'] = $this->db->query($query)->result();
                    $query = "SELECT * FROM users a INNER JOIN user_locations e on e.user_id=a.id INNER JOIN organization_locations f on e.organization_location_id=f.id INNER JOIN organizations g on g.id=f.organization_id where a.id=$user_id";
                    $data['user_location'] = $this->db->query($query)->result();
                }
            }

            $tier_1_2 = unserialize(ROLES_TIER1_2);
            if (in_array($user->role_id, $tier_1_2)) {
                $org_users = $this->Organization_groups_model->get_users_by_user($user_id);
                $data['user_licenses'] = $this->User_licenses_model->get_many_by(['user_id' => $org_users]);
                $data['licences']  = $this->User_licenses_model->get_many_by(['user_id' => $org_users, 'approved' => '1']);
            } else {
                $data['user_licenses'] = $this->User_licenses_model->get_many_by(array('user_id' => $user_id));
                $data['licences']  = $this->User_licenses_model->get_many_by(['user_id' => $user_id, 'approved' => '1']);
            }

            /*
             *      1. Get how many orders are made by the customer.
             *      2. From Order_Item table check how many orders are made by particular vendor.
             *      3. By that get the count of orders purchased by $this Vendor.
             */
            $vendor_user_id = $_SESSION['user_id'];
            $query = "SELECT a.*,b.first_name,c.photo FROM vendor_customer_notes a  INNER JOIN users b on b.id=$vendor_user_id INNER JOIN images c on c.model_id=$vendor_user_id WHERE a.vendor_id=$vendor_id and customer_id=$user_id";
            $data['Vendor_Customer_notes'] = $this->db->query($query)->result();
//            $data['Vendor_Customer_notes']=$this->Vendor_customer_notes_model->get_many_by(array('customer_id' =>$user_id,'vendor_id' =>$vendor_id));
            $data['My_vendor_users'] = "";
            $data['vendor_shipping'] = "";
            $data['product_details'] = "";    // The Object is Defined for Delete-product.php Modal
            $data['promoCodes_active'] = "";
            $data['NorderCount'] = order_count(); // To Get the Latest Order Count.
            $data['ReturnCount'] = return_count();
            $data['customer_id'] = $user_id;

            $this->load->view('/templates/_inc/header-vendor.php');
            $this->load->view('templates/vendor-admin/customers/c/number/index.php', $data);
            $this->load->view('/templates/_inc/footer-vendor.php');
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function customer_orderStatus() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $selection = $this->input->post('selection');
            $customer_id = $this->input->post('customer_id');
            $Order_reportByDay = $this->input->post('Order_reportByDay');
            if ($selection != null) {
                if ($Order_reportByDay != null) {
                    if ($Order_reportByDay == 1) {
                        $startDate = date("Y-m-d", strtotime("-30 days"));
                        $now = date('Y-m-d', now());
                    }
                    if ($Order_reportByDay == 2) {
                        $startDate = date("Y-m-d", strtotime("1 month"));
                        $now = date('Y-m-d', now());
                    }
                    if ($Order_reportByDay == 3) {
                        $startDate = date("Y-m-d", strtotime("3 month"));
                        $now = date('Y-m-d', now());
                    }
                    if ($Order_reportByDay == 4) {
                        $startDate = date("Y-m-d", strtotime("1 year"));
                        $now = date('Y-m-d', now());
                    }
                } else {
                    $startDate = date("Y-m-d", strtotime("-1 year"));
                    $now = date('Y-m-d', now());
                }
                switch ($selection) {
                    case 1:
                        $data['orderList'] = $this->Order_model->order_by('id', 'desc')->get_many_by(array("created_at BETWEEN '" . $startDate . "' and '" . $now . "'", 'user_id' => $customer_id, 'restricted_order' => '0'));
                        if ($data['orderList'] != null) {
                            for ($i = 0; $i < count($data['orderList']); $i++) {
                                $data['orderList'][$i]->location = "";
                                $orderLocation = $this->Organization_location_model->get_by(array('id' => $data['orderList'][$i]->location_id));
                                if ($orderLocation != null) {
                                    $data['orderList'][$i]->location = $orderLocation->nickname;
                                }
                            }
                        }
                        break;
                    case 2:
                        $data['orderList'] = $this->Order_model->order_by('id', 'desc')->get_many_by(array("created_at BETWEEN '" . $startDate . "' and '" . $now . "'", 'user_id' => $customer_id, 'restricted_order' => '0', 'order_status in( 1,2)'));
                        if ($data['orderList'] != null) {
                            for ($i = 0; $i < count($data['orderList']); $i++) {
                                $data['orderList'][$i]->location = "";
                                $orderLocation = $this->Organization_location_model->get_by(array('id' => $data['orderList'][$i]->location_id));
                                if ($orderLocation != null) {
                                    $data['orderList'][$i]->location = $orderLocation->nickname;
                                }
                            }
                        }
                        break;
                    case 3:
                        $data['orderList'] = $this->Order_model->order_by('id', 'desc')->get_many_by(array("created_at BETWEEN '" . $startDate . "' and '" . $now . "'", 'user_id' => $customer_id, 'restricted_order' => '0', 'order_status in(3)'));
                        if ($data['orderList'] != null) {
                            for ($i = 0; $i < count($data['orderList']); $i++) {
                                $data['orderList'][$i]->location = "";
                                $orderLocation = $this->Organization_location_model->get_by(array('id' => $data['orderList'][$i]->location_id));
                                if ($orderLocation != null) {
                                    $data['orderList'][$i]->location = $orderLocation->nickname;
                                }
                            }
                        }
                        break;
                    case 4:
                        $data['orderList'] = $this->Order_model->order_by('id', 'desc')->get_many_by(array("created_at BETWEEN '" . $startDate . "' and '" . $now . "'", 'user_id' => $customer_id, 'restricted_order' => '0', 'order_status in(4)'));
                        if ($data['orderList'] != null) {
                            for ($i = 0; $i < count($data['orderList']); $i++) {
                                $data['orderList'][$i]->location = "";
                                $orderLocation = $this->Organization_location_model->get_by(array('id' => $data['orderList'][$i]->location_id));
                                if ($orderLocation != null) {
                                    $data['orderList'][$i]->location = $orderLocation->nickname;
                                }
                            }
                        }
                        break;
                }
                $this->load->view('templates/vendor-admin/customers/c/number/order-report.php', $data);
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function Customer_OrderBy_Month() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $selection = $this->input->post('selection');
            $customer_id = $this->input->post('customer_id');
            $customer_Orders = $this->input->post('customer_Orders');
            if ($customer_Orders != null) {
                if ($customer_Orders == 1) {
                    $customer_Orders = "order_status in(1,2,3,4)";
                }
                if ($customer_Orders == 2) {
                    $customer_Orders = "order_status in(1,2)";
                }
                if ($customer_Orders == 3) {
                    $customer_Orders = "order_status in(3)";
                }
                if ($customer_Orders == 4) {
                    $customer_Orders = "order_status in(4)";
                }
            }
            if ($selection != null) {
                switch ($selection) {
                    case 1:
                        $startDate = date("Y-m-d", strtotime("-30 days"));
                        $now = date('Y-m-d', now());
                        $data['orderList'] = $this->Order_model->order_by('id', 'desc')->get_many_by(array("created_at BETWEEN '" . $startDate . "' and '" . $now . "'", 'user_id' => $customer_id, 'restricted_order' => '0', '' . $customer_Orders . ''));
                        if ($data['orderList'] != null) {
                            for ($i = 0; $i < count($data['orderList']); $i++) {
                                $data['orderList'][$i]->location = "";
                                $orderLocation = $this->Organization_location_model->get_by(array('id' => $data['orderList'][$i]->location_id));
                                if ($orderLocation != null) {
                                    $data['orderList'][$i]->location = $orderLocation->nickname;
                                }
                            }
                        }
                        break;
                    case 2:
                        $startDate = date("Y-m-d", strtotime("-3 month"));
                        $now = date('Y-m-d', now());
                        $data['orderList'] = $this->Order_model->order_by('id', 'desc')->get_many_by(array("created_at BETWEEN '" . $startDate . "' and '" . $now . "'", 'user_id' => $customer_id, 'restricted_order' => '0', '' . $customer_Orders . ''));
                        if ($data['orderList'] != null) {
                            for ($i = 0; $i < count($data['orderList']); $i++) {
                                $data['orderList'][$i]->location = "";
                                $orderLocation = $this->Organization_location_model->get_by(array('id' => $data['orderList'][$i]->location_id));
                                if ($orderLocation != null) {
                                    $data['orderList'][$i]->location = $orderLocation->nickname;
                                }
                            }
                        }
                        break;
                    case 3:
                        $startDate = date("Y-m-d", strtotime("-6 month"));
                        $now = date('Y-m-d', now());
                        $data['orderList'] = $this->Order_model->order_by('id', 'desc')->get_many_by(array("created_at BETWEEN '" . $startDate . "' and '" . $now . "'", 'user_id' => $customer_id, 'restricted_order' => '0', '' . $customer_Orders . ''));
                        if ($data['orderList'] != null) {
                            for ($i = 0; $i < count($data['orderList']); $i++) {
                                $data['orderList'][$i]->location = "";
                                $orderLocation = $this->Organization_location_model->get_by(array('id' => $data['orderList'][$i]->location_id));
                                if ($orderLocation != null) {
                                    $data['orderList'][$i]->location = $orderLocation->nickname;
                                }
                            }
                        }
                        break;
                    case 4:
                        $startDate = date("Y-m-d", strtotime("-1 year"));
                        $now = date('Y-m-d', now());
                        $data['orderList'] = $this->Order_model->order_by('id', 'desc')->get_many_by(array("created_at BETWEEN '" . $startDate . "' and '" . $now . "'", 'user_id' => $customer_id, 'restricted_order' => '0', '' . $customer_Orders . ''));
                        if ($data['orderList'] != null) {
                            for ($i = 0; $i < count($data['orderList']); $i++) {
                                $data['orderList'][$i]->location = "";
                                $orderLocation = $this->Organization_location_model->get_by(array('id' => $data['orderList'][$i]->location_id));
                                if ($orderLocation != null) {
                                    $data['orderList'][$i]->location = $orderLocation->nickname;
                                }
                            }
                        }
                        break;
                }
                $this->load->view('templates/vendor-admin/customers/c/number/order-report.php', $data);
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function vendors_notes_insert() {
        /*
         *      1. user_id
         *      2. vendor_user_id
         *      3. User_image and Name haveto work with that later.
         */
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $user_id = $_SESSION['user_id'];
            $order_id = $this->input->post('order_id');
            if ($order_id != null) {
                $insert_data = array(
                    'vendor_user_id' => $_SESSION['user_id'],
                    'vendor_id' => $_SESSION['vendor_id'],
                    'order_id' => $order_id,
                    'message' => $this->input->post('note'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                if ($insert_data != null) {
                    $this->vendor_order_notes_model->insert($insert_data);
                }
            } else {
                $customer_id = $this->input->post('customer_id');
                if ($customer_id != null) {
                    $insert_data = array(
                        'customer_id' => $customer_id,
                        'vendor_id' => $_SESSION['vendor_id'],
                        'message' => $this->input->post('note'),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    if ($insert_data != null) {
                        $this->Vendor_customer_notes_model->insert($insert_data);
                    }
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *      Vendor Dashboard
     *      @Dashboard
     *          1. Total sales,order shipped, Products List, Customers of vendor
     *          2. Active Promotions
     *          3. Ratings
     */

    public function vendors_dashboard() {
        /*
         *      1. In order_items Table item_status field=3 is for Urgent Requirements.
         *      2. In order_items Table item_status field=4 is for Shipped.
         */
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $user_id = $_SESSION['user_id'];
            $vendor_detail = $this->Vendor_groups_model->get_by(array('user_id' => $user_id));
            if ($vendor_detail != null) {
                $vendor_id = $vendor_detail->vendor_id;
                $data['urgent_count'] = 0;
                $query = "SELECT o.id,o.total,o.order_status,q.first_name,o.created_at,p.delivery_time FROM orders o LEFT JOIN shipping_options p on o.shipment_id = p.id LEFT JOIN users q on o.user_id = q.id where o.vendor_id=$vendor_id and o.order_status !='Cancelled' and o.order_status !='Shipped' and o.order_status !='Delivered' and o.restricted_order='0' group by o.id";
                $data['ordersUrgent_count'] = $this->db->query($query)->result();
                if ($data['ordersUrgent_count'] != null) {
                    $date = date("Y-m-d");
                    for ($i = 0; $i < count($data['ordersUrgent_count']); $i++) {
                        $data['ordersUrgent_count'][$i]->created_at = date('M d, Y', strtotime($data['ordersUrgent_count'][$i]->created_at));
                        switch ($data['ordersUrgent_count'][$i]->delivery_time) {
                            case "Same Day":
                                $data['ordersUrgent_count'][$i]->delivery_time = date('M d, Y', strtotime($data['ordersUrgent_count'][$i]->created_at));
                                break;
                            case "Next Business Day":
                                $data['ordersUrgent_count'][$i]->delivery_time = date('M d, Y', strtotime($data['ordersUrgent_count'][$i]->created_at . ' +1 Weekday'));
                                break;
                            case "2 Business Days":
                                $data['ordersUrgent_count'][$i]->delivery_time = date('M d, Y', strtotime($data['ordersUrgent_count'][$i]->created_at . ' +2 Weekday'));
                                break;
                            case "3 Business Days":
                                $data['ordersUrgent_count'][$i]->delivery_time = date('M d, Y', strtotime($data['ordersUrgent_count'][$i]->created_at . ' +3 Weekday'));
                                break;
                            case "1-5 Business Days":
                                $data['ordersUrgent_count'][$i]->delivery_time = date('M d, Y', strtotime($data['ordersUrgent_count'][$i]->created_at . ' +5 Weekday'));
                                break;
                            case "7-10 Business Days":
                                $data['ordersUrgent_count'][$i]->delivery_time = date('M d, Y', strtotime($data['ordersUrgent_count'][$i]->created_at . ' +10 Weekday'));
                                break;
                        }
                        if (date('Y-m-d', strtotime($data['ordersUrgent_count'][$i]->delivery_time)) <= date('Y-m-d')) {
                            $data['urgent_count'] = $data['urgent_count'] + 1;
                        }
                    }
                }
                $data['vendor_product_count'] = "";
                $data['vendor_products'] = $this->db->query("select count(*) as vendor_product_count from product_pricings where vendor_id=$vendor_id")->result();
                if ($data['vendor_products'] != null) {
                    //      To get the Number of Products does vendor have for Sales in Dentomatix.
                    $data['vendor_product_count'] = $data['vendor_products'][0]->vendor_product_count;
                }

                // Vendor's Promo code details and status.

                $data['vendor_promo_codes'] = $this->Promo_codes_model->get_many_by(array('vendor_id' => $vendor_id));
                if ($data['vendor_promo_codes'] != null) {
                    for ($i = 0; $i < count($data['vendor_promo_codes']); $i++) {

                        /*
                         *      To INACTIVE the PromoCode, which are EXPIRED based on end_date.
                         */

                        $promo_codes = $this->Promo_codes_model->get($data['vendor_promo_codes'][$i]->id);
                        if ($promo_codes != null) {
                            $today = date('Y-m-d', now());
                            $start_date = date('Y-m-d', strtotime($data['vendor_promo_codes'][$i]->start_date));
                            $end_date = date('Y-m-d', strtotime($data['vendor_promo_codes'][$i]->end_date));
                            if ($end_date == '-0001-11-30') {
                                $end_date = '1970-01-01';
                            }
                            if (($end_date != '1970-01-01') && ($end_date <= $today)) {
                                $update_promo = array(
                                    'active' => '0',
                                    'updated_at' => date('Y-m-d H:i:s'),
                                );
                                $this->Promo_codes_model->update($data['vendor_promo_codes'][$i]->id, $update_promo);
                            }
                        }
                        $data['vendor_promo_codes'][$i]->promo_count = 0;
                        $order_promotion = $this->Order_promotion_model->get_many_by(array('promo_id' => $data['vendor_promo_codes'][$i]->id, 'restricted_order' => '0'));
                        if ($order_promotion != null) {
                            $data['vendor_promo_codes'][$i]->promo_count = count($order_promotion);
                        }
                    }
                }
                $data['total_count'] = "";
                $data['orderItems_status_count'] = "";
                $data['orders_count'] = 0;
                $data['orderItems_shipped_count'] = 0;
                $data['customer_count'] = 0;
                $data['positive_count'] = 0;
                $data['neutral_count'] = 0;
                $data['negative_count'] = 0;
                $data['positive_rating_amonth'] = 0;
                $data['positive_rating_sixmonth'] = 0;
                $data['positive_rating_year'] = 0;
                $data['neutral_rating_amonth'] = 0;
                $data['neutral_rating_sixmonth'] = 0;
                $data['neutral_rating_year'] = 0;
                $data['negative_rating_amonth'] = 0;
                $data['negative_rating_sixmonth'] = 0;
                $data['negative_rating_year'] = 0;
                $data['orders'] = $this->Order_model->get_many_by(array('vendor_id' => $vendor_id, 'restricted_order' => '0', 'order_status not in(5)'));
                if ($data['orders'] != null) {
                    for ($k = 0; $k < count($data['orders']); $k++) {
                        $data['orders_count'] = $data['orders_count'] + $data['orders'][$k]->total;
                        if ($data['orders'][$k]->order_status == "Shipped") {
                            $data['orderItems_shipped_count'] = $data['orderItems_shipped_count'] + 1;
                        }
                    }
                }
                //Customer Count
                $query = "SELECT count(a.id) FROM users a INNER JOIN orders b on a.id=b.user_id where b.vendor_id=$vendor_id group by a.id";
                $customer_count = $this->db->query($query)->result();
                if ($customer_count != null) {
                    for ($l = 0; $l < count($customer_count); $l++) {
                        $data['customer_count'] = $data['customer_count'] + 1;
                    }
                }

                $data['vendors_rating'] = $this->Review_model->get_many_by(array('model_id' => $vendor_id));
                /*
                 *      The Reviews for Vendors are defined based on rating field in the reviews table and getting
                 *      the reviews by { model_id }   which is considered to be vendor_id.
                 *      1. Three states of Reviews are shown to the vendor.
                 *          i.Positive
                 *          ii.Neutral
                 *          iii.Negative
                 */
                if ($data['vendors_rating'] != null) {
                    $today = date("Y-m-d");
                    $date = date_create($today);
                    $month = date_create($today);
                    $year = date_create($today);
                    date_sub($date, date_interval_create_from_date_string('30 days'));
                    date_sub($month, date_interval_create_from_date_string('180 days'));
                    date_sub($year, date_interval_create_from_date_string('365 days'));
                    $six_months = date_format($month, "Y-m-d");
                    $one_month = date_format($date, "Y-m-d");
                    $one_year = date_format($year, "Y-m-d");
                    $data['positive_rating_details'] = "";
                    $data['neutral_rating_details'] = "";
                    $data['negative_rating_details'] = "";
                    if ($data['vendors_rating'] != null) {
                        for ($i = 0; $i < count($data['vendors_rating']); $i++) {
                            //   $data['positive_rating_details'][$i]="";
                            $rating = $data['vendors_rating'][$i]->rating;
                            if ($rating > 3) {
                                $data['positive_count'] = $data['positive_count'] + 1;
                                $data['positive_rating_details'] = $this->Review_model->get_many_by(array('model_id' => $vendor_id, 'rating' => $rating));
                            } elseif ($rating > 2) {
                                $data['neutral_count'] = $data['neutral_count'] + 1;
                                $data['neutral_rating_details'] = $this->Review_model->get_many_by(array('model_id' => $vendor_id, 'rating' => $rating));
                            } else {
                                $data['negative_count'] = $data['negative_count'] + 1;
                                $data['negative_rating_details'] = $this->Review_model->get_many_by(array('model_id' => $vendor_id, 'rating' => $rating));
                            }
                        }
                    }
                    if ($data['positive_rating_details'] != null) {
                        for ($i = 0; $i < count($data['positive_rating_details']); $i++) {
                            $positive_rating = date('Y-m-d', strtotime($data['positive_rating_details'][$i]->created_at));
                            if (($today > $positive_rating) && ($one_month < $positive_rating)) {
                                $data['positive_rating_amonth'] = $data['positive_rating_amonth'] + 1;
                                //  echo $positive_rating . "<br>";
                            }
                            if (($today > $positive_rating) && ($six_months < $positive_rating)) {
                                $data['positive_rating_sixmonth'] = $data['positive_rating_sixmonth'] + 1;
                            }
                            if (($today > $positive_rating) && ($one_year < $positive_rating)) {
                                $data['positive_rating_year'] = $data['positive_rating_year'] + 1;
                            }
                        }
                    }
                    if ($data['neutral_rating_details'] != null) {
                        for ($i = 0; $i < count($data['neutral_rating_details']); $i++) {
                            $neutral_rating = date('Y-m-d', strtotime($data['neutral_rating_details'][$i]->created_at));
                            if (($today > $neutral_rating) && ($one_month < $neutral_rating)) {
                                $data['neutral_rating_amonth'] = $data['neutral_rating_amonth'] + 1;
//                            echo $neutral_rating . "<br>";
                            }
                            if (($today > $neutral_rating) && ($six_months < $neutral_rating)) {
                                $data['neutral_rating_sixmonth'] = $data['neutral_rating_sixmonth'] + 1;
                            }
                            if (($today > $neutral_rating) && ($one_year < $neutral_rating)) {
                                $data['neutral_rating_year'] = $data['neutral_rating_year'] + 1;
                            }
                        }
                    }
                    if ($data['negative_rating_details'] != null) {
                        for ($i = 0; $i < count($data['negative_rating_details']); $i++) {
                            $negative_rating = date('Y-m-d', strtotime($data['negative_rating_details'][$i]->created_at));
                            if (($today > $negative_rating) && ($one_month < $negative_rating)) {
                                $data['negative_rating_amonth'] = $data['negative_rating_amonth'] + 1;
//                            echo $negative_rating . "<br>";
                            }
                            if (($today > $negative_rating) && ($six_months < $negative_rating)) {
                                $data['negative_rating_sixmonth'] = $data['negative_rating_sixmonth'] + 1;
                            }
                            if (($today > $negative_rating) && ($one_year < $negative_rating)) {
                                $data['negative_rating_year'] = $data['negative_rating_year'] + 1;
                            }
                        }
                    }
//                    echo "Today :".$today."<br>OneMonth :".$one_month."<br>Six Month :".$six_months."<br>A Year :".$one_year;exit;
                }

                $query = "select count(f.id)as total_returns from orders a INNER JOIN order_returns f on f.order_id=a.id and a.restricted_order='0' where a.order_status=5 and a.vendor_id=$vendor_id and f.return_status in(1,2);";
                $data['returned_orders'] = $this->db->query($query)->result();
                if ($data['returned_orders'] != null) {
                    for ($k = 0; $k < count($data['returned_orders']); $k++) {
                        $_SESSION['order_return'] = $data['returned_orders'][$k]->total_returns;
                    }
                }
            }
            $data['My_vendor_users'] = "";
            $data['vendor_shipping'] = "";    // Defined for the #edit-user.php Modal
            $data['promoCodes_active'] = "";
            $data['NorderCount'] = order_count();
            $data['ReturnCount'] = return_count();

            $this->load->view('/templates/_inc/header-vendor.php');
            $this->load->view('/templates/vendor-admin/dashboard/index.php', $data);
            $this->load->view('/templates/_inc/footer-vendor.php');

            

        } else {
            $this->session->set_flashdata('error', 'Please login to continue.');
            header('Location: login');
        }
    }

    /*
     *      Vendor Dashboard
     *      @Shipping
     *          Vendor shipping deletes are deleted from here.
     */

    public function deleteSelect_shipping_dashboard() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $delete_id = explode(",", $this->input->post('shipping_ids'));
            $this->Shipping_options_model->delete_many($delete_id);
            $this->session->set_flashdata('success', 'Shipping methods deleted.');
            header("Location: vendor-shipping-partners");
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *  vendor Dashboard
     *      @Settings
     *          Adding business Hours for the Vendor
     */

    public function adding_businessHours() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $user_id = $_SESSION['user_id'];
            $vendor_detail = $this->Vendor_groups_model->get_by(array('user_id' => $user_id));
            $vendor_id = $vendor_detail->vendor_id;
            $hours_details = $this->Business_hour_model->delete_by(array('vendor_id' => $vendor_id));
            if (check_permissions('shipping_options', 'delete')) {

                if ($monday = $this->input->post('monday') != null) {
                    $start_date = date('g:i A', strtotime($this->input->post('monday_startT')));
                    $end_date = date('g:i A', strtotime($this->input->post('monday_endT')));
                    $insert_data = array(
                        'vendor_id' => $vendor_id,
                        'open_time' => date('Y-m-d H:i:s', strtotime($start_date)),
                        'close_time' => date('Y-m-d H:i:s', strtotime($end_date)),
                        'day' => '1',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    if ($insert_data != null) {
                        $this->Business_hour_model->insert($insert_data);
                    }
                }

                if ($tuesday = $this->input->post('tuesday') != null) {
                    $start_date = date('g:i A', strtotime($this->input->post('tuesday_startT')));
                    $end_date = date('g:i A', strtotime($this->input->post('tuesday_endT')));
                    $insert_data = array(
                        'vendor_id' => $vendor_id,
                        'open_time' => date('Y-m-d H:i:s', strtotime($start_date)),
                        'close_time' => date('Y-m-d H:i:s', strtotime($end_date)),
                        'day' => '2',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    if ($insert_data != null) {
                        $this->Business_hour_model->insert($insert_data);
                    }
                }

                if ($wednesday = $this->input->post('wednesday') != null) {
                    $start_date = date('g:i A', strtotime($this->input->post('wednesday_startT')));
                    $end_date = date('g:i A', strtotime($this->input->post('wednesday_endT')));
                    $insert_data = array(
                        'vendor_id' => $vendor_id,
                        'open_time' => date('Y-m-d H:i:s', strtotime($start_date)),
                        'close_time' => date('Y-m-d H:i:s', strtotime($end_date)),
                        'day' => '3',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    if ($insert_data != null) {
                        $this->Business_hour_model->insert($insert_data);
                    }
                }

                if ($thursday = $this->input->post('thursday') != null) {
                    $start_date = date('g:i A', strtotime($this->input->post('thursday_startT')));
                    $end_date = date('g:i A', strtotime($this->input->post('thursday_endT')));
                    $insert_data = array(
                        'vendor_id' => $vendor_id,
                        'open_time' => date('Y-m-d H:i:s', strtotime($start_date)),
                        'close_time' => date('Y-m-d H:i:s', strtotime($end_date)),
                        'day' => '4',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    if ($insert_data != null) {
                        $this->Business_hour_model->insert($insert_data);
                    }
                }
                if ($friday = $this->input->post('friday') != null) {
                    $start_date = date('g:i A', strtotime($this->input->post('friday_startT')));
                    $end_date = date('g:i A', strtotime($this->input->post('friday_endT')));
                    $insert_data = array(
                        'vendor_id' => $vendor_id,
                        'open_time' => date('Y-m-d H:i:s', strtotime($start_date)),
                        'close_time' => date('Y-m-d H:i:s', strtotime($end_date)),
                        'day' => '5',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    if ($insert_data != null) {
                        $this->Business_hour_model->insert($insert_data);
                    }
                }
                if ($saturday = $this->input->post('saturday') != null) {
                    $start_date = date('g:i A', strtotime($this->input->post('saturday_startT')));
                    $end_date = date('g:i A', strtotime($this->input->post('saturday_endT')));
                    $insert_data = array(
                        'vendor_id' => $vendor_id,
                        'open_time' => date('Y-m-d H:i:s', strtotime($start_date)),
                        'close_time' => date('Y-m-d H:i:s', strtotime($end_date)),
                        'day' => '6',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    if ($insert_data != null) {
                        $this->Business_hour_model->insert($insert_data);
                    }
                }
                if ($sunday = $this->input->post('sunday') != null) {
                    $start_date = date('g:i A', strtotime($this->input->post('sunday_startT')));
                    $end_date = date('g:i A', strtotime($this->input->post('sunday_endT')));
                    $insert_data = array(
                        'vendor_id' => $vendor_id,
                        'open_time' => date('Y-m-d H:i:s', strtotime($start_date)),
                        'close_time' => date('Y-m-d H:i:s', strtotime($end_date)),
                        'day' => '7',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    if ($insert_data != null) {
                        $this->Business_hour_model->insert($insert_data);
                    }
                }
                $this->session->set_flashdata('success', 'Business hours saved.');
                header('Location: vendor-settings-dashboard');
            } else {
                $this->session->set_flashdata('error', 'Please input business hours.');
                header('Location: vendor-settings-dashboard');
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function buyingClubs()
    {
        if($_SESSION['role_id'] == 11){
            $data['userType'] = 'vendor';
            $this->load->view('/templates/buying-clubs/list.php', $data);
        } else {
            $this->session->set_flashdata('error', 'You do not have access to that page');
            header('Location: login');
        }
    }
}

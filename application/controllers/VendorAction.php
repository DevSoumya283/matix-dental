<?php

/*
 *  It's Vendor Dashboard actions
 */

class VendorAction extends MW_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Review_model');
        $this->load->model('Role_model');
        $this->load->model('Vendor_model');
        $this->load->model('Vendor_groups_model');
        $this->load->model('Vendor_policies_model');
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
    }

    /*
     *      Vendor Dashboard
     *          @Product
     *              To activate the Promocode.
     */
    /*
      $admin_roles = unserialize(ROLES_VENDORS);
      if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {

      } else {
      $this->session->set_flashdata('error', 'Please login with authorized account.');
      header('Location: login');
      }
     *
     */

    public function promo_activate() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            // Explode() converts string into array with dividing by comma(,) separate.
            //  1. So that we can pass that has array in update_many() function.
            $promo_ids = explode(',', $this->input->post('promo_ids1'));
            $update_data = array(
                'active' => '1',
                'updated_at' => date('Y-m-d H:i:s'),
            );
            if ($update_data != null) {
                $this->Promo_codes_model->update_many($promo_ids, $update_data);
            }
            $this->session->set_flashdata('success', 'Promo code(s) activated.');
            header("Location: view-promo-product");
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *      Vendor Dashboard
     *          @Product
     *              To deactivate the Promocode.
     */

    public function promo_deactivate() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            // Explode() converts string into array with dividing by comma(,) separate.
            //  1. So that we can pass that has array in update_many() function.
            $promo_ids = explode(',', $this->input->post('promo_ids2'));
//        echo "<pre>";print_r($promo_ids);exit;
            $update_data = array(
                'active' => '0',
                'updated_at' => date('Y-m-d H:i:s'),
            );
            if ($update_data != null) {
                $this->Promo_codes_model->update_many($promo_ids, $update_data);
            }
            $this->session->set_flashdata('success', 'Promo code(s) activated.');
            header("Location: view-promo-product");
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *      Vendor Dashboard
     *          @Dashboard
     *              To delete the Selected Promocode.
     */

    public function promo_delete() {
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
     *          To show the selected Promo code.
     */

    public function promo_selection() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $user_id = $_SESSION['user_id'];
            $vendor_detail = $this->Vendor_groups_model->get_by(array('user_id' => $user_id));
            $vendor_id = $vendor_detail->vendor_id;
            if ($vendor_id != null) {
                $selection = $this->input->post('select');
                switch ($selection) {
                    case 0:
                        $data['promoCodes_active'] = $this->Promo_codes_model->get_many_by(array('vendor_id' => $vendor_detail->vendor_id, 'product_id is not' => NULL));
                        for ($i = 0; $i < count($data['promoCodes_active']); $i++) {
                            $prodcutPricing = $this->Product_pricing_model->get_by(array('vendor_id' => $data['promoCodes_active'][$i]->vendor_id));
                            $product_name = $this->Products_model->get_by(array('id' => $prodcutPricing->product_id));
                            $data['promoCodes_active'][$i]->product = $product_name->name;
                            $data['promoCodes_active'][$i]->productPricing_id = $prodcutPricing->id;
                        }
                        break;
                    case 1:
                        $data['promoCodes_active'] = $this->Promo_codes_model->get_many_by(array('vendor_id' => $vendor_detail->vendor_id, 'active' => '1', 'product_id is not' => NULL));
                        for ($i = 0; $i < count($data['promoCodes_active']); $i++) {
                            $prodcutPricing = $this->Product_pricing_model->get_by(array('vendor_id' => $data['promoCodes_active'][$i]->vendor_id));
                            $product_name = $this->Products_model->get_by(array('id' => $prodcutPricing->product_id));
                            $data['promoCodes_active'][$i]->product = $product_name->name;
                            $data['promoCodes_active'][$i]->productPricing_id = $prodcutPricing->id;
                        }
                        break;
                    case 2:
                        $data['promoCodes_active'] = $this->Promo_codes_model->get_many_by(array('vendor_id' => $vendor_detail->vendor_id, 'active' => '0', 'product_id is not' => NULL));
                        for ($i = 0; $i < count($data['promoCodes_active']); $i++) {
                            $prodcutPricing = $this->Product_pricing_model->get_by(array('vendor_id' => $data['promoCodes_active'][$i]->vendor_id));
                            $product_name = $this->Products_model->get_by(array('id' => $prodcutPricing->product_id));
                            $data['promoCodes_active'][$i]->product = $product_name->name;
                            $data['promoCodes_active'][$i]->productPricing_id = $prodcutPricing->id;
                        }
                        break;
                    case 3:
                        $data['promoCodes_active'] = $this->Promo_codes_model->get_many_by(array('vendor_id' => $vendor_detail->vendor_id, 'product_id is not' => NULL, 'end_date <=now()'));
                        for ($i = 0; $i < count($data['promoCodes_active']); $i++) {
                            $prodcutPricing = $this->Product_pricing_model->get_by(array('vendor_id' => $data['promoCodes_active'][$i]->vendor_id));
                            $product_name = $this->Products_model->get_by(array('id' => $prodcutPricing->product_id));
                            $data['promoCodes_active'][$i]->product = $product_name->name;
                            $data['promoCodes_active'][$i]->productPricing_id = $prodcutPricing->id;
                        }
                        break;
                }
            }
            echo json_encode($data);
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *      Vendor Dashboard
     *          To activate,deactive and delete the promo code.
     */

    public function promoCode_selections() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            if ($_SESSION['user_id'] != null) {
                $user_id = $_SESSION['user_id'];
                $vendor_detail = $this->Vendor_groups_model->get_by(array('user_id' => $user_id));
                $vendor_id = $vendor_detail->vendor_id;
                $promoAll = $this->input->post('promoAll');
                if ($vendor_id != null) {
                    $selection = $this->input->post('selection');
                    $promo_ids = explode(',', $this->input->post('promo_id'));
                    switch ($selection) {
                        case 'activate':
                            $update_data = array(
                                'active' => '1',
                                'updated_at' => date('Y-m-d H:i:s'),
                            );
                            if ($update_data != null) {
                                $this->Promo_codes_model->update_many($promo_ids, $update_data);
                                echo true;
                            }
                            break;
                        case 'deactivate':
                            $update_data = array(
                                'active' => '0',
                                'updated_at' => date('Y-m-d H:i:s'),
                            );
                            if ($update_data != null) {
                                $this->Promo_codes_model->update_many($promo_ids, $update_data);
                                echo true;
                            }
                            break;
                        case 'delete':
                            $this->Promo_codes_model->delete_many($promo_ids);
                            echo true;
                            break;
                    }
                    if ($promoAll == 1) {
                        header("Location: view-promo-code");
                    } else {
                        header("Location: view-promo-product");
                    }
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function promoCode_select_forAll() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $user_id = $_SESSION['user_id'];
            $vendor_detail = $this->Vendor_groups_model->get_by(array('user_id' => $user_id));
            $vendor_id = $vendor_detail->vendor_id;
            if ($vendor_id != null) {
                $selection = $this->input->post('select');
                switch ($selection) {
                    case 0:
                        $data['promoCodes_active'] = $this->Promo_codes_model->get_many_by(array('vendor_id' => $vendor_detail->vendor_id, 'product_id' => NULL));
                        for ($i = 0; $i < count($data['promoCodes_active']); $i++) {
                            $prodcutPricing = $this->Product_pricing_model->get_by(array('vendor_id' => $data['promoCodes_active'][$i]->vendor_id));
                            $product_name = $this->Products_model->get_by(array('id' => $prodcutPricing->product_id));
                            $data['promoCodes_active'][$i]->product = $product_name->name;
                            $data['promoCodes_active'][$i]->productPricing_id = $prodcutPricing->id;
                        }
                        break;
                    case 1:
                        $data['promoCodes_active'] = $this->Promo_codes_model->get_many_by(array('vendor_id' => $vendor_detail->vendor_id, 'active' => '1', 'product_id' => NULL));
                        for ($i = 0; $i < count($data['promoCodes_active']); $i++) {
                            $prodcutPricing = $this->Product_pricing_model->get_by(array('vendor_id' => $data['promoCodes_active'][$i]->vendor_id));
                            $product_name = $this->Products_model->get_by(array('id' => $prodcutPricing->product_id));
                            $data['promoCodes_active'][$i]->product = $product_name->name;
                            $data['promoCodes_active'][$i]->productPricing_id = $prodcutPricing->id;
                        }
                        break;
                    case 2:
                        $data['promoCodes_active'] = $this->Promo_codes_model->get_many_by(array('vendor_id' => $vendor_detail->vendor_id, 'active' => '0', 'product_id' => NULL));
                        for ($i = 0; $i < count($data['promoCodes_active']); $i++) {
                            $prodcutPricing = $this->Product_pricing_model->get_by(array('vendor_id' => $data['promoCodes_active'][$i]->vendor_id));
                            $product_name = $this->Products_model->get_by(array('id' => $prodcutPricing->product_id));
                            $data['promoCodes_active'][$i]->product = $product_name->name;
                            $data['promoCodes_active'][$i]->productPricing_id = $prodcutPricing->id;
                        }
                        break;
                    case 3:
                        $data['promoCodes_active'] = $this->Promo_codes_model->get_many_by(array('vendor_id' => $vendor_detail->vendor_id, 'product_id' => NULL, 'end_date <=now()'));
                        for ($i = 0; $i < count($data['promoCodes_active']); $i++) {
                            $prodcutPricing = $this->Product_pricing_model->get_by(array('vendor_id' => $data['promoCodes_active'][$i]->vendor_id));
                            $product_name = $this->Products_model->get_by(array('id' => $prodcutPricing->product_id));
                            $data['promoCodes_active'][$i]->product = $product_name->name;
                            $data['promoCodes_active'][$i]->productPricing_id = $prodcutPricing->id;
                        }
                        break;
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
        echo json_encode($data);
    }

    public function new_promoCode_allProduct() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $vendor_id = $_SESSION['vendor_id'];
            if ($vendor_id != null) {
                $promo_id = $this->input->post('PromoId');
                $promoCode = $this->input->post('code');
                $promoexists = $this->Promo_codes_model->get_by(array('code' => $promoCode));
                if ($promoexists == null) {
                    $end_date = null;
                    if ($this->input->post("end_date") != null && $this->input->post("end_date") != "") {
                        $end_date = date("Y-m-d", strtotime($this->input->post("end_date")));
                    }
                    $insert_promo = array(
                        'title' => $this->input->post('promoTitle'),
                        'code' => $this->input->post('code'),
                        'discount' => $this->input->post('discount'),
                        'discount_type' => $this->input->post('discount_type'),
                        'discount_on' => $this->input->post('discount_on'),
                        'threshold_count' => $this->input->post('threshold_count'),
                        'threshold_type' => $this->input->post('threshold_type'),
                        'start_date' => date('Y-m-d H:i:s', strtotime($this->input->post('start_date'))),
                        'end_date' => ($end_date != null) ? $end_date : NULL,
                        'conditions' => $this->input->post('conditions'),
                        'manufacturer_coupon' => $this->input->post('manufacturer_coupon'),
                        'free_product_id' => $this->input->post('free_product_id'),
                        'product_free' => $this->input->post('product_free'),
                        'use_with_promos' => $this->input->post('use_with_promos'),
                        'vendor_id' => $vendor_id,
                        'active' => '1',
                        'updated_at' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s'),
                    );
                    if ($insert_promo != null) {
                        $promo_id = $this->Promo_codes_model->insert($insert_promo);
                    }
                } else {
                    $this->session->set_flashdata('error', 'Promo code already exists.');
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function update_promoCode() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $vendor_id = $_SESSION['vendor_id'];
            if ($vendor_id != null) {
                $promo_id = $this->input->post('PromoId');
                if ($promo_id != null) {
                    $end_date = NULL;
                    if ($this->input->post("end_date") != null && $this->input->post("end_date") != "") {
                        $end_date = date("Y-m-d", strtotime($this->input->post("end_date")));
                    }
                    $update_promo = array(
                        'title' => $this->input->post('promoTitle'),
                        'code' => $this->input->post('code'),
                        'discount' => $this->input->post('discount'),
                        'discount_type' => $this->input->post('discount_type'),
                        'discount_on' => $this->input->post('discount_on'),
                        'threshold_count' => $this->input->post('threshold_count'),
                        'threshold_type' => $this->input->post('threshold_type'),
                        'start_date' => date('Y-m-d H:i:s', strtotime($this->input->post('start_date'))),
                        'end_date' => ($end_date != null) ? $end_date : NULL,
                        'manufacturer_coupon' => $this->input->post('manufacturer_coupon'),
                        'conditions' => ($this->input->post('manufacturer_coupon') == 0) ? NULL : $this->input->post('conditions'),
                        'product_free' => $this->input->post('product_free'),
                        'free_product_id' => ($this->input->post('product_free') == 1) ? $this->input->post('free_product_id') : "",
                        'use_with_promos' => $this->input->post('use_with_promos'),
                        'vendor_id' => $vendor_id,
                        'active' => '1',
                        'updated_at' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s'),
                    );
                    if ($update_promo != null) {
                        $promo_id = $this->Promo_codes_model->update($promo_id, $update_promo);
                        echo true;
                    }
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *      The Below function works with a Ajax call.
     *      1. Two times the function will be called
     *          i.  VendorDashboard
     *          ii. SuperAdminDashboard
     *  To delete the user(s) of Particular Vendor.
     */

    public function vendors_user_delete() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $delete_id = explode(",", $this->input->post('user_id'));
            for ($i = 0; $i < count($delete_id); $i++) {
                $vendor_detail = $this->Vendor_groups_model->get_by(array('user_id' => $delete_id[$i]));
                $vendor_delete_id = $vendor_detail->id;
                $this->Vendor_groups_model->delete($vendor_delete_id);
            }
            $this->User_model->delete_many($delete_id);
            header("Location: view-vendors");
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *      Vendor Dashboard
     *      @Settings
     *          To delete the Vendor Policies.
     */

    public function delete_vendorPolicy() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $delete_id = $this->input->post('policy_id');
            if ($delete_id != null) {
                $this->Vendor_policies_model->delete($delete_id);
                $this->session->set_flashdata('success', 'Vendor policy deleted');
                header('Location: vendor-settings-dashboard');
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *      Vendor Dashboard
     *      @Settings
     *          To Add Vendor Policies.
     */

    public function add_vendorPolicy() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $vendor_id = $this->input->post('vendor_id');
            if ($vendor_id != null) {
                $insert_data = array(
                    'vendor_id' => $vendor_id,
                    'policy_name' => $this->input->post('policyName'),
                    'description' => $this->input->post('policyDesc'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                if ($insert_data != null) {
                    $this->Vendor_policies_model->insert($insert_data);
                    $this->session->set_flashdata('success', 'New policy updated for product sales. ');
                    header('Location: vendor-settings-dashboard');
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *      Vendor Dashboard
     *      @Settings
     *          To update the Refund Instructions.
     */

    public function update_refundInstructions() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $vendor_id = $this->input->post('vendor_id');
            if ($vendor_id != null) {
                $update_data = array(
                    'refund_instructions' => $this->input->post('refund_instructions'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                $this->Vendor_model->update($vendor_id, $update_data);
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *      Vendor Dashboard
     *      Promocode based on selection from the view page.
     */

    public function promoCode_active_state() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $data['select'] = "";
            $select = $this->input->get('select');
            $data['limit'] = 30;
            $valuein = $this->input->get("p");
            $offset = 0;
            if ($valuein != null) {
                $offset = ($valuein - 1) * $data['limit'];
            }
            $data['select'] = $select;
            $vendor_id = $_SESSION['vendor_id'];
            if ($vendor_id != null) {
                switch ($select) {
                    case 0:
                        $query = "SELECT a.*,count(d.id)as used, a.id,a.end_date,a.title,a.active,c.name as product,b.id as productPricing_id,d.promo_code_id FROM promo_codes a LEFT JOIN product_pricings b on b.product_id=a.product_id LEFT JOIN products c on c.id=b.product_id  LEFT JOIN order_items d ON d.promo_code_id=a.id and d.restricted_order='0'  where a.vendor_id=$vendor_id and a.product_id is not null group by a.id limit $offset," . $data['limit'] . "";
                        $data['promoCodes_active'] = $this->db->query($query)->result();
                        $query1 = "SELECT count(*) as search_count FROM (SELECT a.id FROM promo_codes a LEFT JOIN product_pricings b on b.product_id=a.product_id LEFT JOIN products c on c.id=b.product_id  LEFT JOIN order_items d ON d.promo_code_id=a.id and d.restricted_order='0'  where a.vendor_id=$vendor_id and a.product_id is not null group by a.id) count";
                        $count_query = $this->db->query($query1)->result();
                        if ($count_query != null) {
                            $data['total_count'] = $count_query[0]->search_count;
                        }
                        if ($data['promoCodes_active'] != null) {
                            for ($i = 0; $i < count($data['promoCodes_active']); $i++) {
                                $data['promoCodes_active'][$i]->used = 0;
                                $data['promoCodes_active'][$i]->used = count($this->Order_promotion_model->get_many_by(array('promo_id' => $data['promoCodes_active'][$i]->id, 'restricted_order' => '0')));
                            }
                        }
                        break;
                    case 1:
                        $query = "SELECT a.*,count(d.id)as used, a.id,a.end_date,a.title,a.active,c.name as product,b.id as productPricing_id,d.promo_code_id FROM promo_codes a LEFT JOIN product_pricings b on b.product_id=a.product_id LEFT JOIN products c on c.id=b.product_id  LEFT JOIN order_items d ON d.promo_code_id=a.id and d.restricted_order='0' where a.active=1 and a.vendor_id=$vendor_id and a.product_id is not null group by a.id limit $offset," . $data['limit'] . "";
                        $data['promoCodes_active'] = $this->db->query($query)->result();
                        $query1 = "SELECT count(*) as search_count FROM (SELECT a.id FROM promo_codes a LEFT JOIN product_pricings b on b.product_id=a.product_id LEFT JOIN products c on c.id=b.product_id  LEFT JOIN order_items d ON d.promo_code_id=a.id and d.restricted_order='0' where a.active=1 and a.vendor_id=$vendor_id and a.product_id is not null group by a.id) count";
                        $count_query = $this->db->query($query1)->result();
                        if ($count_query != null) {
                            $data['total_count'] = $count_query[0]->search_count;
                        }
                        if ($data['promoCodes_active'] != null) {
                            for ($i = 0; $i < count($data['promoCodes_active']); $i++) {
                                $data['promoCodes_active'][$i]->used = 0;
                                $data['promoCodes_active'][$i]->used = count($this->Order_promotion_model->get_many_by(array('promo_id' => $data['promoCodes_active'][$i]->id, 'restricted_order' => '0')));
                            }
                        }
                        break;
                    case 2:
                        $query = "SELECT a.*,count(d.id)as used, a.id,a.end_date,a.title,a.active,c.name as product,b.id as productPricing_id,d.promo_code_id FROM promo_codes a LEFT JOIN product_pricings b on b.product_id=a.product_id LEFT JOIN products c on c.id=b.product_id  LEFT JOIN order_items d ON d.promo_code_id=a.id  and d.restricted_order='0' where a.active=0 and a.vendor_id=$vendor_id and a.product_id is not null group by a.id limit $offset," . $data['limit'] . "";
                        $data['promoCodes_active'] = $this->db->query($query)->result();
                        $query1 = "SELECT count(*) as search_count FROM (SELECT a.id FROM promo_codes a LEFT JOIN product_pricings b on b.product_id=a.product_id LEFT JOIN products c on c.id=b.product_id  LEFT JOIN order_items d ON d.promo_code_id=a.id  and d.restricted_order='0' where a.active=0 and a.vendor_id=$vendor_id and a.product_id is not null group by a.id) count";
                        $count_query = $this->db->query($query1)->result();
                        if ($count_query != null) {
                            $data['total_count'] = $count_query[0]->search_count;
                        }
                        if ($data['promoCodes_active'] != null) {
                            for ($i = 0; $i < count($data['promoCodes_active']); $i++) {
                                $data['promoCodes_active'][$i]->used = 0;
                                $data['promoCodes_active'][$i]->used = count($this->Order_promotion_model->get_many_by(array('promo_id' => $data['promoCodes_active'][$i]->id, 'restricted_order' => '0')));
                            }
                        }
                        break;
                    case 3:
                        $query = "SELECT a.*,count(d.id)as used, a.id,a.end_date,a.title,a.active,c.name as product,b.id as productPricing_id,d.promo_code_id FROM promo_codes a LEFT JOIN product_pricings b on b.product_id=a.product_id LEFT JOIN products c on c.id=b.product_id  LEFT JOIN order_items d ON d.promo_code_id=a.id  and d.restricted_order='0' where a.end_date < now() and a.vendor_id=$vendor_id and a.product_id is not null group by a.id limit $offset," . $data['limit'] . "";
                        $data['promoCodes_active'] = $this->db->query($query)->result();
                        $query1 = "SELECT count(*) as search_count FROM (SELECT a.id FROM promo_codes a LEFT JOIN product_pricings b on b.product_id=a.product_id LEFT JOIN products c on c.id=b.product_id  LEFT JOIN order_items d ON d.promo_code_id=a.id  and d.restricted_order='0' where a.end_date < now() and a.vendor_id=$vendor_id and a.product_id is not null group by a.id) count";
                        $count_query = $this->db->query($query1)->result();
                        if ($count_query != null) {
                            $data['total_count'] = $count_query[0]->search_count;
                        }
                        if ($data['promoCodes_active'] != null) {
                            for ($i = 0; $i < count($data['promoCodes_active']); $i++) {
                                $data['promoCodes_active'][$i]->used = 0;
                                $data['promoCodes_active'][$i]->used = count($this->Order_promotion_model->get_many_by(array('promo_id' => $data['promoCodes_active'][$i]->id, 'restricted_order' => '0')));
                            }
                        }
                        break;
                }
                $this->load->library('pagination');
                $config['base_url'] = base_url() . '/promoCode-active-State';
                $config['total_rows'] = $data['total_count'];
                $config['per_page'] = $data['limit'];
                $this->pagination->initialize($config);
                $config['enable_query_strings'] = TRUE;
                $config['page_query_string'] = TRUE;
                $config['use_page_numbers'] = TRUE;

                $data['My_vendor_users'] = "";
                $data['vendor_shipping'] = "";    // Defined for the #edit-user.php Modal
                $data['promoCode_All'] = "";
                $data['NorderCount'] = order_count(); // To Get the Latest Order Count.
                $data['ReturnCount'] = return_count();
                $this->load->view('/templates/vendor-admin/promos/product/index.php', $data);
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function promoCode_TitleandCode() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $user_id = $_SESSION['user_id'];
            $vendor_groups = $this->Vendor_groups_model->get_by(array('user_id' => $user_id));
            $data['vendor_id'] = $vendor_groups->vendor_id;
            if ($data['vendor_id'] != null) {
                $insert_data = array(
                    'title' => $this->input->post('createPromoTitle'),
                    'code' => $this->input->post('promoCode'),
                    'discount' => $this->input->post('discount'),
                    'discount_type' => $this->input->post('discount_type'),
                    'discount_on' => $this->input->post('discount_on'),
                    'threshold_count' => $this->input->post('threshold_count'),
                    'threshold_type' => $this->input->post('threshold_type'),
                    'start_date' => $this->input->post('start'),
                    'end_date' => $this->input->post('end'),
                    'active' => '1',
                    'vendor_id' => $data['vendor_id'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                $data['promo_id'] = $this->Promo_codes_model->insert($insert_data);
                header('Location: view-promo-code');
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function promoCodeAll_active_state() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $select = $this->input->get('select');
            $vendor_group = $this->Vendor_groups_model->get_by(array('user_id' => $_SESSION['user_id']));
            $vendor_id = $vendor_group->vendor_id;
            $data['limit'] = 30;
            $valuein = $this->input->get("p");
            $offset = 0;
            if ($valuein != null) {
                $offset = ($valuein - 1) * $data['limit'];
            }
            if ($vendor_id != null) {
                switch ($select) {
                    case 0:
                        $query = "SELECT a.*,sum(b.discount_value) as promoPrice FROM promo_codes a LEFT JOIN order_promotions b on b.promo_id=a.id and b.restricted_order='0' where a.product_id is null and  a.vendor_id=$vendor_id group by a.id limit $offset," . $data['limit'] . "";
                        $data['promoCodes_active'] = $this->db->query($query)->result();
                        $data['total_count'] = 0;
                        $query1 = "SELECT count(*) as search_count FROM (SELECT a.id FROM promo_codes a LEFT JOIN order_promotions b on b.promo_id=a.id and b.restricted_order='0' where a.product_id is null and  a.vendor_id=$vendor_id group by a.id) count";
                        $count_query = $this->db->query($query1)->result();
                        if ($count_query != null) {
                            $data['total_count'] = $count_query[0]->search_count;
                        }
                        if ($data['promoCodes_active'] != null) {
                            for ($i = 0; $i < count($data['promoCodes_active']); $i++) {
                                $data['promoCodes_active'][$i]->used = 0;
                                $usedPromo = $this->Order_items_model->get_many_by(array('promo_code_id' => $data['promoCodes_active'][$i]->id, 'restricted_order' => '0'));
                                if ($usedPromo != null) {
                                    $data['promoCodes_active'][$i]->used = count($usedPromo);
                                }
                            }
                        }
                        break;
                    case 1:
                        $query = "SELECT a.*,sum(b.discount_value) as promoPrice FROM promo_codes a LEFT JOIN order_promotions b on b.promo_id=a.id and b.restricted_order='0' where a.product_id is null and  a.vendor_id=$vendor_id and a.active=1 group by a.id limit $offset," . $data['limit'] . "";
                        $data['promoCodes_active'] = $this->db->query($query)->result();
                        $data['total_count'] = 0;
                        $query1 = "SELECT count(*) as search_count FROM (SELECT a.*,sum(b.discount_value) as promoPrice FROM promo_codes a LEFT JOIN order_promotions b on b.promo_id=a.id and b.restricted_order='0' where a.product_id is null and  a.vendor_id=$vendor_id and a.active=1 group by a.id) count";
                        $count_query = $this->db->query($query1)->result();
                        if ($count_query != null) {
                            $data['total_count'] = $count_query[0]->search_count;
                        }
                        if ($data['promoCodes_active'] != null) {
                            for ($i = 0; $i < count($data['promoCodes_active']); $i++) {
                                $data['promoCodes_active'][$i]->used = 0;
                                $usedPromo = $this->Order_promotion_model->get_many_by(array('promo_id' => $data['promoCodes_active'][$i]->id, 'restricted_order' => '0'));
                                if ($usedPromo != null) {
                                    $data['promoCodes_active'][$i]->used = count($usedPromo);
                                }
                            }
                        }
                        break;
                    case 2:
                        $query = "SELECT a.*,sum(b.discount_value) as promoPrice FROM promo_codes a LEFT JOIN order_promotions b on b.promo_id=a.id and b.restricted_order='0' where a.product_id is null and  a.vendor_id=$vendor_id and a.active=0 group by a.id limit $offset," . $data['limit'] . "";
                        $data['promoCodes_active'] = $this->db->query($query)->result();
                        $data['total_count'] = 0;
                        $query1 = "SELECT count(*) as search_count FROM (SELECT a.*,sum(b.discount_value) as promoPrice FROM promo_codes a LEFT JOIN order_promotions b on b.promo_id=a.id and b.restricted_order='0' where a.product_id is null and  a.vendor_id=$vendor_id and a.active=0 group by a.id) count";
                        $count_query = $this->db->query($query1)->result();
                        if ($count_query != null) {
                            $data['total_count'] = $count_query[0]->search_count;
                        }
                        if ($data['promoCodes_active'] != null) {
                            for ($i = 0; $i < count($data['promoCodes_active']); $i++) {
                                $data['promoCodes_active'][$i]->used = 0;
                                $usedPromo = $this->Order_promotion_model->get_many_by(array('promo_id' => $data['promoCodes_active'][$i]->id, 'restricted_order' => '0'));
                                if ($usedPromo != null) {
                                    $data['promoCodes_active'][$i]->used = count($usedPromo);
                                }
                            }
                        }
                        break;
                    case 3:
                        $query = "SELECT a.*,sum(b.discount_value) as promoPrice FROM promo_codes a LEFT JOIN order_promotions b on b.promo_id=a.id and b.restricted_order='0' where a.product_id is null and a.end_date < now() and a.vendor_id=$vendor_id group by a.id limit $offset," . $data['limit'] . "";
                        $data['promoCodes_active'] = $this->db->query($query)->result();
                        $data['total_count'] = 0;
                        $query1 = "SELECT count(*) as search_count FROM (SELECT a.*,sum(b.discount_value) as promoPrice FROM promo_codes a LEFT JOIN order_promotions b on b.promo_id=a.id and b.restricted_order='0' where a.product_id is null and a.end_date < now() and a.vendor_id=$vendor_id group by a.id) count";
                        $count_query = $this->db->query($query1)->result();
                        if ($count_query != null) {
                            $data['total_count'] = $count_query[0]->search_count;
                        }
                        if ($data['promoCodes_active'] != null) {
                            for ($i = 0; $i < count($data['promoCodes_active']); $i++) {
                                $data['promoCodes_active'][$i]->used = 0;
                                $usedPromo = $this->Order_promotion_model->get_many_by(array('promo_id' => $data['promoCodes_active'][$i]->id, 'restricted_order' => '0'));
                                if ($usedPromo != null) {
                                    $data['promoCodes_active'][$i]->used = count($usedPromo);
                                }
                            }
                        }
                        break;
                }

                $this->load->library('pagination');
                $config['base_url'] = base_url() . '/promoreport-active-State';
                $config['total_rows'] = $data['total_count'];
                $config['per_page'] = $data['limit'];
                $this->pagination->initialize($config);
                $config['enable_query_strings'] = TRUE;
                $config['page_query_string'] = TRUE;
                $config['use_page_numbers'] = TRUE;

                $data['My_vendor_users'] = "";
                $data['vendor_shipping'] = "";    // Defined for the #edit-user.php Modal
                $data['select'] = $select;
                $data['NorderCount'] = order_count(); // To Get the Latest Order Count.
                $data['ReturnCount'] = return_count();
                $this->load->view('/templates/vendor-admin/promos/codes/index.php', $data);
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    //Vendor Dashboard @Promos -> Delete Promo code all from Vendor dashboard .
    public function delete_promoCodeAll() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $delete_id = explode(",", $this->input->post('promo_id'));
            $this->Promo_codes_model->delete_many($delete_id);
            $this->session->set_flashdata('success', 'Promo code(s) Deleted successfully..');
            header("Location: view-promo-code");
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *      Vendor Dashboard
     *          @product ->single Page->promo code
     *          1. Search for a Free Product in Vendor Products list
     *          @AJAX call.
     */

    public function promotionProduct_Vendor() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $search = $this->input->post('search');
            $vendor_id = $_SESSION['vendor_id'];
            if ($vendor_id != null) {
                $search = $this->input->post('search');
                $data['vendor_promoProduct'] = $this->Product_pricing_model->get_by(array("vendor_product_id like" => '%' . $search . '%', 'vendor_id' => $vendor_id));
                if ($data['vendor_promoProduct'] != null) {
                    $data['vendor_promoProduct']->name = "";
                    $product_name = $this->Products_model->get_by(array('id' => $data['vendor_promoProduct']->product_id));
                    if ($product_name != null) {
                        $data['vendor_promoProduct']->name = $product_name->name;
                    }
                }
                echo json_encode($data['vendor_promoProduct']);
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    // To activate Vendor Product
    public function activate_vendor_Product() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $activate = $this->input->post('activate');
            $product_pricing_id = $this->input->post('product_pricing_id');
            if ($activate != null) {
                $update_data = array(
                    'active' => $activate,
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                if ($update_data != null) {
                    $result = $this->Product_pricing_model->update($product_pricing_id, $update_data);
                    echo $result;
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function promoCode_statusVendor() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $user_id = $_SESSION['user_id'];
            $vendor_detail = $this->Vendor_groups_model->get_by(array('user_id' => $user_id));
            if ($vendor_detail != null) {
                $vendor_id = $vendor_detail->vendor_id;

                // Getting the PromoCode with Product_id.
                //      *-> Conditioned with NOT NULL for product_id to all the PROMO_CODE.

                $data['vendor_promoProduct_active'] = "";
                $data['vendor_promoProduct_Inactive'] = "";
                $data['vendor_promoCode_Product'] = $this->Promo_codes_model->get_many_by(array('vendor_id' => $vendor_id, 'product_id IS NOT' => NULL));
                if ($data['vendor_promoCode_Product'] != null) {
                    $data['vendor_promoProduct_active'] = count($this->Promo_codes_model->get_many_by(array('vendor_id' => $vendor_id, 'product_id IS NOT' => NULL, 'active' => 1)));
                    $data['vendor_promoProduct_Inactive'] = count($this->Promo_codes_model->get_many_by(array('vendor_id' => $vendor_id, 'product_id IS NOT' => NULL, 'active' => 0)));
                }

                // Getting the  the PromoCode for all the Vendor Products.
                //      *-> Conditioned with NULL for all the PROMO_CODE

                $data['vendor_promoCodeActive'] = "";
                $data['vendor_promoCodeInactive'] = "";
                $data['vendor_promoCode_all'] = $this->Promo_codes_model->get_many_by(array('vendor_id' => $vendor_id, 'product_id' => NULL));
                if ($data['vendor_promoCode_all'] != null) {
                    $data['vendor_promoCodeActive'] = count($this->Promo_codes_model->get_many_by(array('vendor_id' => $vendor_id, 'product_id' => NULL, 'active' => 1)));
                    $data['vendor_promoCodeInactive'] = count($this->Promo_codes_model->get_many_by(array('vendor_id' => $vendor_id, 'product_id' => NULL, 'active' => 0)));
                }
            }

            $data['My_vendor_users'] = "";
            $data['vendor_shipping'] = "";    // Defined for the #edit-user.php Modal
            $data['promoCodes_active'] = "";
            $data['NorderCount'] = order_count(); // To Get the Latest Order Count.
            $data['ReturnCount'] = return_count();
            $this->load->view('/templates/_inc/header-vendor.php');
            $this->load->view('/templates/vendor-admin/promos/index.php', $data);
            $this->load->view('/templates/_inc/footer-vendor.php');

        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    // Vendor Dashboard @Product - Promo code  List of Promo codes are shown here.

    public function vendor_promoProduct_page() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $user_id = $_SESSION['user_id'];
            $vendor_id = $_SESSION['vendor_id'];
            $data['limit'] = 30;
            $valuein = $this->input->get("p");
            $offset = 0;
            if ($valuein != null) {
                $offset = ($valuein - 1) * $data['limit'];
            }
            if ($vendor_id != null) {
                $query = "SELECT c.name,a.*,a.id,a.end_date,a.title,a.active,c.name as product,b.id as productPricing_id FROM promo_codes a INNER JOIN product_pricings b on b.product_id=a.product_id INNER JOIN products c on c.id=b.product_id  where a.vendor_id=$vendor_id and a.product_id is not null group by a.id limit $offset," . $data['limit'] . "";
                $data['promoCodes_active'] = $this->db->query($query)->result();
                $data['total_count'] = 0;
                $query1 = "SELECT count(*) as search_count FROM (SELECT a.id FROM promo_codes a INNER JOIN product_pricings b on b.product_id=a.product_id INNER JOIN products c on c.id=b.product_id  where a.vendor_id=$vendor_id and a.product_id is not null group by a.id) count";
                $count_query = $this->db->query($query1)->result();
                if ($count_query != null) {
                    $data['total_count'] = $count_query[0]->search_count;
                }
                if ($data['promoCodes_active'] != null) {
                    for ($i = 0; $i < count($data['promoCodes_active']); $i++) {
                        $data['promoCodes_active'][$i]->used = 0;
                        $data['promoCodes_active'][$i]->used = count($this->Order_promotion_model->get_many_by(array('promo_id' => $data['promoCodes_active'][$i]->id, 'restricted_order' => '0')));
                    }
                }
                $this->load->library('pagination');
                $config['base_url'] = base_url() . '/view-promo-product';
                $config['total_rows'] = $data['total_count'];
                $config['per_page'] = $data['limit'];
                $this->pagination->initialize($config);
                $config['enable_query_strings'] = TRUE;
                $config['page_query_string'] = TRUE;
                $config['use_page_numbers'] = TRUE;

                $data['My_vendor_users'] = "";
                $data['vendor_shipping'] = "";    // Defined for the #edit-user.php Modal
                $data['select'] = 0;
                $data['promoCode_All'] = 0;
                $data['NorderCount'] = order_count(); // To Get the Latest Order Count.
                $data['ReturnCount'] = return_count();

                $this->load->view('/templates/_inc/header-vendor.php');
                $this->load->view('/templates/vendor-admin/promos/product/index.php', $data);
                $this->load->view('/templates/_inc/footer-vendor.php');


            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    // Vendor Dashboard @Promo 1. PromoCode for all the products.
    public function vendor_promoCode_page() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $user_id = $_SESSION['user_id'];
            $vendor_detail = $this->Vendor_groups_model->get_by(array('user_id' => $user_id));
            $vendor_id = $_SESSION['vendor_id'];
            $data['limit'] = 30;
            $valuein = $this->input->get("p");
            $offset = 0;
            if ($valuein != null) {
                $offset = ($valuein - 1) * $data['limit'];
            }
            if ($vendor_detail != null) {
                $query = "SELECT a.*,b.discount_value,b.promo_id,sum(b.discount_value) as promoPrice FROM promo_codes a LEFT JOIN order_promotions b on b.promo_id=a.id and b.restricted_order='0' where a.product_id is null and a.vendor_id=$vendor_id group by a.id limit $offset," . $data['limit'] . "";
                $data['promoCodes_active'] = $this->db->query($query)->result();
                $data['total_count'] = 0;
                $query1 = "SELECT count(*) as search_count FROM (SELECT a.id FROM promo_codes a LEFT JOIN order_promotions b on b.promo_id=a.id and b.restricted_order='0' where a.product_id is null and a.vendor_id=$vendor_id group by a.id) count";
                $count_query = $this->db->query($query1)->result();
                if ($count_query != null) {
                    $data['total_count'] = $count_query[0]->search_count;
                }
                if ($data['promoCodes_active'] != null) {
                    for ($i = 0; $i < count($data['promoCodes_active']); $i++) {
                        $data['promoCodes_active'][$i]->used = 0;
                        $data['promoCodes_active'][$i]->free_product_name = "";
                        $usedPromo = $this->Order_promotion_model->get_many_by(array('promo_id' => $data['promoCodes_active'][$i]->id, 'restricted_order' => '0'));
                        if ($usedPromo != null) {
                            $data['promoCodes_active'][$i]->used = count($usedPromo);
                        }
                        $data['promoCodes_active'][$i]->free_product_name = "";
                        if ($data['promoCodes_active'][$i]->product_free != 0) {
                            $data['freeproductName'] = $this->Products_model->get($data['promoCodes_active'][$i]->free_product_id);
                            $data['promoCodes_active'][$i]->free_product_name = $data['freeproductName']->name;
                        }
                    }
                }
            }
            $this->load->library('pagination');
            $config['base_url'] = base_url() . '/view-promo-code';
            $config['total_rows'] = $data['total_count'];
            $config['per_page'] = $data['limit'];
            $this->pagination->initialize($config);
            $config['enable_query_strings'] = TRUE;
            $config['page_query_string'] = TRUE;
            $config['use_page_numbers'] = TRUE;

            $data['My_vendor_users'] = "";
            $data['vendor_shipping'] = "";    // Defined for the #edit-user.php Modal
            $data['select'] = 0;
            $data['promoCode_All'] = 1;
            $data['NorderCount'] = order_count(); // To Get the Latest Order Count.
            $data['ReturnCount'] = return_count();

            $this->load->view('/templates/_inc/header-vendor.php');
            $this->load->view('/templates/vendor-admin/promos/codes/index.php', $data);
            $this->load->view('/templates/_inc/footer-vendor.php');
            
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function vendor_promoCode_update() {
        /*
         *      1.Free Product with the Promo Code Yet to be Worked.
         */
        if (isset($_SESSION['user_id'])) {
            $promo_id = $this->input->post('promo_id');
            $product_id = $this->input->post('product_id');
            $productPricing_id = $this->input->post('productPricing_id');
            $start_date = $this->input->post('start_date');
            $promo_dates = $this->input->post('promo_dates');
            $end_date = $this->input->post('end_date');
            if ($promo_dates != null && $promo_dates == "range") {
                if ($this->input->post("start_date") != null && $this->input->post("start_date") != "") {
                    $start_date = date("Y-m-d", strtotime($start_date));
                } else {
                    $start_date = null;
                }
                if ($this->input->post("end_date") != null && $this->input->post("end_date") != "") {
                    $end_date = date("Y-m-d", strtotime($end_date));
                } else {
                    $end_date = null;
                }
            }

            if ($promo_dates != null && $promo_dates == "end_date_only") {
                $end_date_only = $this->input->post("end_date_only");
                if ($end_date_only != null && $end_date_only != "01/01/1970") {
                    $start_date = null;
                    $end_date = date("Y-m-d", strtotime($end_date_only));
                }
            }

            if ($promo_id != null) {
                $promocodeget = $this->input->post('promoCode');
                $promocodeCheck = $this->Promo_codes_model->get_by(array('code' => $promocodeget));
                if ($promo_id == $promocodeCheck->id || $promocodeCheck == null) {
                    $promocodeValid = true;
                } else {
                    $promocodeValid = false;
                }
                if ($promocodeValid == true) {
                    $update_data = array(
                        'title' => $this->input->post('promoTitle'),
                        'code' => $this->input->post('promoCode'),
                        'discount' => $this->input->post('promoValue'),
                        'discount_type' => $this->input->post('discount_type'),
                        'discount_on' => $this->input->post('discount_on'),
                        'threshold_count' => $this->input->post('promoThreshold'),
                        'threshold_type' => ($this->input->post('promoThreshold') != null) ? "1" : "",
                        'start_date' => $start_date,
                        'end_date' => $end_date,
                        'product_free' => $this->input->post('product_free'),
                        'product_id' => $product_id,
                        'free_product_id' => $this->input->post('free_product_id'),
                        'use_with_promos' => ($this->input->post('use_with_promos') != null) ? 1 : 0,
                        'manufacturer_coupon' => $this->input->post('manufacturer_coupon'),
                        'conditions' => ($this->input->post('manufacturer_coupon') == null) ? "" : $this->input->post('conditions'),
                        'active' => '1',
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    $this->session->set_flashdata('success', 'Promo Code Updated');
                    $this->Promo_codes_model->update($promo_id, $update_data);
                    header("Location: product-pricing-vendorEdit?productPrice_id=" . $productPricing_id);
                } else {
                    $this->session->set_flashdata('error', 'Promo Code is already exists');
                    header("Location: product-pricing-vendorEdit?productPrice_id=" . $productPricing_id);
                }
            } else {
                $product_details = $this->Product_pricing_model->get($productPricing_id);
                $promocodeget = $this->input->post('promoCode');
                $promocodeCheck = $this->Promo_codes_model->get_by(array('code' => $promocodeget));
                if ($promocodeCheck == null) {
                    $insert_data = array(
                        'title' => $this->input->post('promoTitle'),
                        'code' => $this->input->post('promoCode'),
                        'discount' => $this->input->post('promoValue'),
                        'discount_type' => $this->input->post('discount_type'),
                        'discount_on' => $this->input->post('discount_on'),
                        'threshold_count' => $this->input->post('promoThreshold'),
                        'threshold_type' => ($this->input->post('promoThreshold') != null) ? "1" : "",
                        'start_date' => $start_date,
                        'end_date' => $end_date,
                        'product_free' => $this->input->post('product_free'),
                        'vendor_id' => $product_details->vendor_id,
                        'product_id' => $product_id,
                        'free_product_id' => $this->input->post('free_product_id'),
                        'use_with_promos' => ($this->input->post('use_with_promos') != null) ? 1 : 0,
                        'manufacturer_coupon' => $this->input->post('manufacturer_coupon'),
                        'conditions' => ($this->input->post('manufacturer_coupon') == null) ? "" : $this->input->post('conditions'),
                        'active' => '1',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    if ($insert_data != null) {
                        $this->Promo_codes_model->insert($insert_data);
                        $this->session->set_flashdata('success', 'Promo Code Created');
                        header("Location: product-pricing-vendorEdit?productPrice_id=" . $productPricing_id);
                    }
                } else {
                    $this->session->set_flashdata('error', 'Promo Code Name already exists');
                    header("Location: product-pricing-vendorEdit?productPrice_id=" . $productPricing_id);
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Session expired. Please login again.');
            header('Location: login');
        }
    }

}

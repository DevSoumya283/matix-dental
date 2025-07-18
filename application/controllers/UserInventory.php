<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class UserInventory extends MW_Controller {

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
        $this->load->model('BuyingClub_model');
        $this->load->model('Role_model');
        $this->load->model('Category_model');
        $this->load->library('email');
        $this->load->helper('my_email_helper');
    }

    //view inventories
    public function view_inventory() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $data['bcModel'] = $this->BuyingClub_model;
            $data['location_id'] = $this->User_location_model->get_many_by(['user_id' => $user_id]);
            $data['list_id'] = null;
            foreach($data['location_id'] as $location_id){
                $data['user_locations'][] = $this->Organization_location_model->get_by(['id' => $location_id->organization_location_id]);
            }
            // Debugger::debug($data['user_locations']);
            $location_ids = [];
            $data['inventories'] = $this->Location_inventories_model->order_by('location_id', 'asc')->get_all();
            // Debugger::debug($data['inventories'], 'inventories');
            $data['userLicenses'] = $this->User_licenses_model->loadValidLicenses($user_id, 1);
            for ($j = 0; $j < count($data['user_locations']); $j++) {
                for ($i = 0; $i < count($data['inventories']); $i++) {
                    if ($data['inventories'][$i]->location_id == $data['user_locations'][$j]->id) {
                        $location_ids[] = $data['user_locations'][$j]->id;
                        $data['user_locations'][$j]->item_count++;
                    }
                }
            }

            // Debugger::debug($data['user_locations'], '$counts');

            if ($this->input->get('id') != null){
                $data['list_id'] = $this->input->get('id');
            }else if($location_ids != null) {
                $data['list_id'] = min($location_ids);
            } else {
                $data['list_id'] = 0;
            }
            $data['selected_category'] = $this->input->post('categories');
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
                if(!empty($product->category_id)){
                    $data['catId'][] = explode(",", str_replace('"', '', $product->category_id));
                }
            }

            $data['catIdCount'] = $data['catId'];
            $classic_categories = $this->Category_model->get_many_by(['parent_id' => 1]);
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
            // Debugger::debug($classic_categories, '$classic_categories');

            if(!empty($this->input->get('csv'))){
                $this->Location_inventories_model->exportCSV($data);
            } else {
                $this->load->view('/templates/_inc/header', $data);
                $this->load->view('/templates/account/inventory/index', $data);
                $this->load->view('/templates/_inc/footer');
            }
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    //update inventory Update On-Hand Qty
    public function update_inventory() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $id = explode(",", $this->input->post('update_id'));
            $qty = $this->input->post('qty');
            for ($i = 0; $i < count($id); $i++) {
                $this->Location_inventories_model->update($id[$i], array('purchashed_qty' => $qty));
                $data['user_address'] = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
                $organization_id = $data['user_address']->organization_id;
                $quantity = $this->Location_inventories_model->get_by(array('id' => $id[$i]));
                $minimum_threshold = $quantity->minimum_threshold;
                $purchashed_qty = $quantity->purchashed_qty;
                if ($minimum_threshold > $purchashed_qty) {
                    $data['organ_users'] = $this->Organization_groups_model->get_many_by(array('organization_id' => $organization_id));
                    foreach ($data['organ_users'] as $key) {
                        $data['user'] = $this->User_model->get_many_by(array('id' => $key->user_id));
                        for ($i = 0; $i < count($data['user']); $i++) {
                            if ($data['user'][$i]->email_setting1 == '1' && ($data['user'][$i]->role_id == '3' || $data['user'][$i]->role_id == '7')) {
                                $email = $data['user'][$i]->email;
                                $inventory_data = $this->Location_inventories_model->get_by(array('minimum_threshold' => $minimum_threshold));
                                $product = $this->Products_model->get_by(array('id' => $inventory_data->product_id));
                                $product_name = $product->name;
                                $product_img  = 0;
                                $manufacturer = $product->manufacturer;
                                $manufacturer_link = base() . "home?manufacturer=" . urlencode($manufacturer);


                                $subject = "Low Inventory Alert";
                                $message = "<div style='text-align: center;'>"
                                        . "<hr style='width: 40px; color: #e8eaf1; background-color: #e8eaf1; border: 1px solid #e8eaf1;'>"
                                        . "<br />"
                                        . "Hi " . $user_name . ",<br />"
                                        . "</div>"

                                        . "<p style='color: #61646d; text-align: center; padding: 0 20px;'>Just letting you know that the on-hand inventory for the item below has fallen below it's set minimum threshold.  "
                                        . "<a href=" . base_url() . "details?location_id=" . $location_id . ">"
                                        . "Click here</a> to change these settings or make updates to your inventory.</p><br/>"

                                        . "<table cellpadding='0' cellspacing='0' style='border: 1px solid #d8d8d8; width: 100%; padding: 12px 16px; -moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius: 4px;' class='100p'>"
                                            . "<tr>"
                                                ."<td style='text-align: center; vertical-align: middle;'>"
                                                    . "<div style='height: 50px; width: 50px; background: #61646d;' data-img='" . $product_img . "''></div>"
                                                ."</td>"
                                                ."<td>"
                                                    . "<table cellpadding='0' cellspacing='0'style='color: #61646d; background:#fff; '>"
                                                        . "<tr>"
                                                            . "<td style='text-align: left; font-size: 10px;'>Product Name</td><td>" . $product_name . "</td>"
                                                            . "<td style='color: #E52626; text-align: right; font-size: 12px;'>" . $purchashed_qty . " (On-Hand)</td>"
                                                        . "</tr>"
                                                        . "<tr>"
                                                            . "<td style='font-size: 10px;'>By: <a href='" . $manufacturer_link . "'>" . $manufacturer . "</a></td>"
                                                            . "<td style='font-size: 8px; text-align: right;'>Min Qty: " . $minimum_threshold . "</td>"
                                                        . "</tr>"
                                                        . "<tr>"
                                                            . "<td> Set inventory threshold</td>"
                                                            . "<td></td>"
                                                        . "</tr>"
                                                    . "</table>"
                                                . "</td>"
                                            ."</tr>"
                                        . "</table>"

                                        . "<br/>"

                                        . "<a href=' " . base_url() . "view-product?id=" . $inventory_data->product_id ."' style='padding: 20px 40px 20px 40px;width:auto;display: block;text-decoration: none;border:0;text-align: center;font-weight: bold;font-size: 16px;font-family: Arial, sans-serif;color: #ffffff;background: #2893FF;border: 1px solid #317ED0;-moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius: 4px;line-height:normal;' class='button_link'>Buy It Again</a>";

                                $email_data = array(
                                    'subject' => $subject,
                                    'message' => $message
                                );
                                $body = $this->load->view('/templates/email/alert/index.php', $email_data, TRUE);
                                $mail_status = send_matix_email($body, $subject, $email);
                            }
                        }
                    }
                }
            }
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    //update inventory Threshold Qty
    public function update_lowqty() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $id = explode(",", $this->input->post('update_id'));
            $qty = $this->input->post('qty');
            for ($i = 0; $i < count($id); $i++) {
                $this->Location_inventories_model->update($id[$i], ['minimum_threshold' => $qty]);
            }
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    //remove inventory
    public function remove_inventory() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $delete_id = explode(",", $this->input->post('user_id'));
            $this->Location_inventories_model->delete_many($delete_id);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

}

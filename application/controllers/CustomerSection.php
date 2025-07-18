<?php

/*
 *      SuperAdmin's Customer Section Dashboard.
 *          ***    Tasks Happening in this Controller     ***
 *      1. Activate the User
 *      2. Deactivate the Customer
 *      3. Customer Purchase Report Page and Details
 */

class CustomerSection extends MW_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('MY_privilege_helper');
        $this->load->helper('MY_support_helper');
        $this->load->helper('my_email_helper');
        $this->load->model('Order_model');
        $this->load->model('Order_items_model');
        $this->load->model('Admin_customer_notes_model');
        $this->load->model('Organization_groups_model');
        $this->load->model('Organization_location_model');
        $this->load->model('Organization_model');
        $this->load->model('Images_model');
        $this->load->model('Role_model');
        $this->load->model('Permissions_model');
        $this->load->model('RolePermissions_model');
        $this->load->model('Shipping_options_model');
        $this->load->model('User_licenses_model');
        $this->load->model('User_location_model');
        $this->load->model('User_model');
        $this->load->model('User_vendor_notes_model');
        $this->load->model('User_payment_option_model');
        $this->load->model('Vendor_model');
        $this->load->library('Stripe');
    }

    /*
     *  1. It works for all the Users to Activate and Deactivate Users.
     *      (i) Called from different View Pages.
     *      (ii) @AJAX call.
     */

    public function customer_action() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $select = $this->input->post('select');
            $user_ids = explode(',', $this->input->post('user_id'));
            switch ($select) {
                case 0:
                    $update_data = array(
                        'status' => '0',
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    if ($update_data != null) {
                        $this->User_model->update_many($user_ids, $update_data);
                    }
                    break;
                case 1:
                    $update_data = array(
                        'status' => '1',
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    if ($update_data != null) {
                        $this->User_model->update_many($user_ids, $update_data);
                    }
                    break;
            }
            header('Location: customer-list');
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function customer_purchase_report() {
        /*
         *  SuperAdmin dashboard
         *      @Customers->single User.
         */
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            if (isset($_SESSION['user_id'])) {
                $user_id = $this->input->get('user_id');
                $user = $this->User_model->get_by('id', $user_id);
                // Customer Details
                $data['customer_report'] = $this->User_model->get_by(array('id' => $user_id, 'role_id not in(1,2,11)'));
                if ($data['customer_report'] != null) {
                    $data['customer_report']->role_name = "";
                    $data['customer_report']->organization_name = "";
                    // Customer Profile Image
                    $data['user_profile'] = $this->Images_model->get_by(array('model_name' => 'user', 'model_id' => $user_id));
                    // Customer Role in the Organization(Institution/Company).
                    $role = $this->Role_model->get_by(array('id' => $data['customer_report']->role_id));
                    // Organization Details
                    $organization_group = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
                    if ($organization_group != null) {
                        $organization = $this->Organization_model->get_by(array('id' => $organization_group->organization_id));
                        $data['organization_id'] = $organization_group->organization_id;
                        $data['customer_report']->role_name = $role->role_tier;
                        $data['customer_report']->organization_name = $organization->organization_name;
                    }
                    // Customer Order details
                    $orders = $this->Order_model->get_many_by(array('user_id' => $user_id, 'restricted_order' => '0'));
                    $data['customer_report']->total = "";
                    $data['customer_report']->license_count = 0;
                    if ($orders != null) {
                        for ($i = 0; $i < count($orders); $i++) {
                            $total_orders = $orders[$i]->total;
                            $data['customer_report']->total = $total_orders + $data['customer_report']->total;
                        }
                    }
                    // Customer License to Purchase the resticted Items


                    $tier_1_2 = unserialize(ROLES_TIER1_2);
                    if (in_array($user->role_id, $tier_1_2)) {
                        $org_users = $this->Organization_groups_model->get_users_by_user($user_id);
                        $data['user_licenses'] = $this->User_licenses_model->get_many_by(['user_id' => $org_users]);
                        $user_license = $this->User_licenses_model->get_many_by(['user_id' => $org_users, 'approved' => '1']);
                    } else {
                        $data['user_licenses'] = $this->User_licenses_model->get_many_by(array('user_id' => $user_id));
                        $user_license = $this->User_licenses_model->get_many_by(['user_id' => $user_id, 'approved' => '1']);
                    }


                    if ($user_license != null) {
                        $data['customer_report']->license_count = count($user_license);
                    }
                    // Last 1 month Order details will be shown here.
                    $startDate = date("Y-m-d", strtotime("-30 days"));
                    $now = date('Y-m-d', now());
                    $data['latest_reports'] = $this->Order_model->order_by('id', 'desc')->get_many_by(array("created_at >=" => $startDate, 'user_id' => $user_id, 'restricted_order' => '0'));
                    Debugger::debug($data['latest_reports']);
                    if ($data['latest_reports'] != null) {
                        for ($i = 0; $i < count($data['latest_reports']); $i++) {
                            $data['latest_reports'][$i]->vendor_name = "";
                            $data['latest_reports'][$i]->image_name = "";
                            $orderItems = $this->Order_items_model->get_many_by(array('order_id' => $data['latest_reports'][$i]->id));
                            if ($orderItems != null) {
                                for ($j = 0; $j < count($orderItems); $j++) {
                                    $data['latest_reports'][$i]->image_name = $this->Images_model->get_by(array('model_name' => 'products', 'image_type' => 'mainimg', 'model_id' => $orderItems[$j]->product_id));
                                }
                            }
                            $vendor_details = $this->Vendor_model->get_by(array('id' => $data['latest_reports'][$i]->vendor_id));
                            if ($vendor_details != null) {
                                $data['latest_reports'][$i]->vendor_name = $vendor_details->name;
                            }
                        }
                    }

                    //      To Display the Location(s) Assigned to Single user with shipment count based on Orders.
                    $data['location'] = $this->User_location_model->customer_locations($user_id);
                    if ($data['location'] != null) {
                        for ($i = 0; $i < count($data['location']); $i++) {
                            $data['location'][$i]->shipment_count = "";
                            $orders_count = $this->Order_model->get_many_by(array('location_id' => $data['location'][$i]->organization_location_id, 'restricted_order' => '0'));
                            $data['location'][$i]->shipment_count = count($orders_count);
                        }
                    }
                    /*
                     *      Location are displayed in (Location Assignment) MODAL
                     *          1. Shows all the Organization Locations.
                     */
                    if ($organization_group != null) {
                        $data['organizaton_locations'] = $this->Organization_location_model->get_many_by(array('organization_id' => $organization_group->organization_id));
                        if ($data['organizaton_locations'] != null) {
                            for ($i = 0; $i < count($data['organizaton_locations']); $i++) {
                                $data['organizaton_locations'][$i]->status = 0;
                                $data['organizaton_locations'][$i]->user_location_id = "";
                                $user_locations = $this->User_location_model->get_by(array('organization_location_id' => $data['organizaton_locations'][$i]->id, 'user_id' => $user_id));
                                if ($user_locations != null) {
                                    if ($user_locations->user_id == $user_id) {
                                        $data['organizaton_locations'][$i]->status = 1;
                                        $data['organizaton_locations'][$i]->user_location_id = $user_locations->id;
                                    } else {
                                        $data['organizaton_locations'][$i]->status = 0;
                                    }
                                }
                            }
                        }
                    } else {
                        $data['organizaton_locations'] = "";
                    }
                    //      Add Notes
                    $data['customer_note'] = $this->Admin_customer_notes_model->admin_notes($user_id);
                    $data['user_approval'] = user_counts();
                    $data['flagged_count'] = flagged_count();
                    $data['answer_count'] = flaggedAnswer_count();
                    $data['vendor_shipping'] = "";
                    $data['My_vendor_users'] = "";
                    $data['customer_id'] = $user_id;      // To Assign and Unsign userLocation
                    $data['promoCodes_active'] = "";
                } else {
                    $this->session->set_flashdata('error', 'Invalid Entry');
                    header('Location: vendorsIn-list');
                }
                $this->load->view('/templates/_inc/header-admin.php');
                $this->load->view('/templates/admin/customers/c/number/index.php', $data);
                $this->load->view('/templates/_inc/footer-admin.php');
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function customer_SAdmin_editBy() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $user_id = $this->input->post('user_id');
            if ($user_id != null) {
                $update_data = array(
                    'first_name' => $this->input->post('accountName'),
                    'email' => $this->input->post('accountEmail'),
                    'phone1' => $this->input->post('accountPhone'),
                    'role_id' => $this->input->post('role_id'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                if ($update_data != null) {
                    $this->User_model->update($user_id, $update_data);
                }
                $organization_group = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
                $organization = $this->Organization_model->get_by(array('id' => $organization_group->organization_id));
                if ($organization != null) {
                    $organization_id = $organization->id;
                    $org_update = array(
                        'organization_name' => $this->input->post('accountCompany'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    if ($org_update != null) {
                        $this->Organization_model->update($organization_id, $org_update);
                    }
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *      SuperAdmin Dashboard
     *      @Pending
     */

    public function getAll_customers_Pending() {
        /*
         *  Pending License with User details
         *      1. 30 Customers whose license have to be approved will be shown here with the count of license to be approved.
         */

        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $search = $this->input->get('search');
            $data['limit'] = 30;
            $data['total_count'] = 0;
            $valuein = $this->input->get("p");
            $offset = 0;
            if ($valuein != null) {
                $offset = ($valuein - 1) * $data['limit'];
            }
            if ($search != null) {
                $query = "select a.user_id,a.approved,count(a.approved) as license_count,s.status,s.id,s.first_name,r.organization_name,r.organization_type,s.created_at from user_licenses a  LEFT JOIN users s on s.id=a.user_id LEFT JOIN organization_groups p on s.id = p.user_id LEFT JOIN roles q on s.role_id = q.id LEFT JOIN organizations r on r.id = p.organization_id where a.approved='0' and s.first_name LIKE '%$search%' or r.organization_name LIKE '%$search%' GROUP by a.user_id limit $offset," . $data['limit'] . "";
                $data['organizations_request'] = $this->db->query($query)->result();
                $data['total_count'] = 0;
                $query1 = "SELECT count(*) as search_count FROM (select a.user_id,a.approved,count(a.approved) as license_count,s.status,s.id,s.first_name,r.organization_name,r.organization_type,s.created_at from user_licenses a  LEFT JOIN users s on s.id=a.user_id LEFT JOIN organization_groups p on s.id = p.user_id LEFT JOIN roles q on s.role_id = q.id LEFT JOIN organizations r on r.id = p.organization_id where a.approved='0' and s.first_name LIKE '%$search%' or r.organization_name LIKE '%$search%' GROUP by a.user_id) count";
                $count_query = $this->db->query($query1)->result();
                if ($count_query != null) {
                    $data['total_count'] = $count_query[0]->search_count;
                }
            } else {
                $query = "select a.user_id,a.approved,count(a.approved) as license_count,s.status,s.id,s.first_name,r.organization_name,r.organization_type,s.created_at from user_licenses a LEFT JOIN users s on s.id=a.user_id LEFT JOIN organization_groups p on s.id = p.user_id LEFT JOIN roles q on s.role_id = q.id LEFT JOIN organizations r on r.id = p.organization_id  where a.approved='0' GROUP by a.user_id limit $offset," . $data['limit'] . "";
                $data['organizations_request'] = $this->db->query($query)->result();
                Debugger::debug($data['organizations_request']);
                $data['total_count'] = 0;
                $query1 = "SELECT count(*) as search_count FROM (select a.user_id,a.approved,count(a.approved) as license_count,s.status,s.id,s.first_name,r.organization_name,r.organization_type,s.created_at from user_licenses a LEFT JOIN users s on s.id=a.user_id LEFT JOIN organization_groups p on s.id = p.user_id LEFT JOIN roles q on s.role_id = q.id LEFT JOIN organizations r on r.id = p.organization_id  where a.approved='0' GROUP by a.user_id) count";
                $count_query = $this->db->query($query1)->result();
                if ($count_query != null) {
                    $data['total_count'] = $count_query[0]->search_count;
                }
            }
            $this->load->library('pagination');
            $config['base_url'] = base_url() . '/customerSection-accept-customers';
            $config['total_rows'] = $data['total_count'];
            $config['per_page'] = $data['limit'];
            $this->pagination->initialize($config);
            $config['enable_query_strings'] = TRUE;
            $config['page_query_string'] = TRUE;
            $config['use_page_numbers'] = TRUE;

            $data['user_approval'] = user_counts();
            $data['flagged_count'] = flagged_count();
            $data['answer_count'] = flaggedAnswer_count();
            $data['My_vendor_users'] = "";
            $data['vendor_shipping'] = "";
            $data['promoCodes_active'] = "";
            $this->load->view('/templates/_inc/header-admin.php');
            $this->load->view('templates/admin/customers/pending/index.php', $data);
            $this->load->view('/templates/_inc/footer-admin.php');

        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *  SuperAdmin Dashboard
     *      @Customer->Single User
     *          1.Approve single License
     *          2. Email send to User
     */

    public function approve_user_license() {
        // Approve a single license.
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $customer_id = $this->input->post('customer_id');
            $license_id = $this->input->post('license_id');
            $update_data = array(
                'approved' => '1',
                'updated_at' => date('Y-m-d H:i:s'),
            );
            if ($update_data != null) {
                $result = $this->User_licenses_model->update_by(array('id' => $license_id), $update_data);
                if ($result != null) {
                    $userLicenses = $this->User_licenses_model->get_by(array('user_id' => $customer_id, 'id' => $license_id));
                    $user_details = $this->User_model->get($customer_id);
                    // Email send to User
                    if ($userLicenses != null) {
                        $accountName = $user_details->first_name;
                        $accountEmail = $user_details->email;
                        $expire_date = date('M d, Y', strtotime($userLicenses->expire_date));
                        $subject = "License Verified";
                        $message = "<div style='text-align: center;'>"
                        . "<hr style='width: 40px; color: #e8eaf1; background-color: #e8eaf1; border: 1px solid #e8eaf1;'>"
                        . "<br />"
                        . "Hi " . $accountName . ",<br />"
                        . "</div>"
                        . "<p style='color: #61646d; text-align: center; padding: 0 20px;'>The Licenses below have been verified. You can now purchase items that require a license in the states they have been issued.</p><br>"
                        . "<table style='border: 1px solid #d8d8d8; width: 100%; padding: 28px 16px; -moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius: 4px;'>"
                        . "<tr>"
                        . "<td>$userLicenses->license_no</td>"
                        . "<td>$userLicenses->dea_no</td>"
                        . "<td>$expire_date</td>"
                        . "<td style='color: #21CEB3; text-align: center;'>$userLicenses->state</td>"
                        . "</tr>"
                        . "<tr style='font-size: 14px;'><td style='text-align:left;'>License Number</td><td style='text-align:left;'>DEA Number</td><td style='text-align:left;'>Expires</td><td style='text-align:center;'>State</td></tr>"
                        . "</table>";
                        $email_data = array(
                            'subject' => $subject,
                            'message' => $message
                        );
                        $body = $this->load->view('/templates/email/index.php', $email_data, TRUE);
                        $mail_status = send_matix_email($body, $subject, $accountEmail);
                    }
                    echo $result;
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *  SuperAdmin Dashboard
     *      @Customer->Single User
     *          1.Disapprove single License
     *          2. Email send to User
     */

    public function disapprove_user_license() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $customer_id = $this->input->post('customer_id');
            $license_id = $this->input->post('license_id');
            $update_data = array(
                'approved' => '-1',
                'updated_at' => date('Y-m-d H:i:s'),
            );
            if ($update_data != null) {
                $result = $this->User_licenses_model->update_by(array('id' => $license_id), $update_data);
                if ($result != null) {
                    $userLicenses = $this->User_licenses_model->get_by(array('user_id' => $customer_id, 'id' => $license_id));
                    $user_details = $this->User_model->get($customer_id);
                    if ($userLicenses != null) {
                        $accountName = $user_details->first_name;
                        $accountEmail = $user_details->email;
                        $subject = "Account licenses denied";
                        $message = "Hi,<br />Here is a summary of your dental license status:<br>
                        <table><thead><tr><th style='text-align:left;'><b>License #</b></th><th style='text-align:left;'><b>DEA #</b></th><th style='text-align:left;'><b>Expires</b></th><th style='text-align:left;'><b>State</b></th><th style='text-align:left;'><b>Status</b></th></tr></thead><tbody>";
                        $expire_date = date('M d, Y', strtotime($userLicenses->expire_date));
                        $message.="<tr>"
                                . "<td>$userLicenses->license_no</td>"
                                . "<td>$userLicenses->dea_no</td>"
                                . "<td>$expire_date</td>"
                                . "<td>$userLicenses->state</td>"
                                . "<td><button style='padding: 5px 5px 5px 5px;width: auto;text-decoration: none;border: 0;text-align: center;font-weight: bold;font-size: 14px;font-family: Arial, sans-serif;color: #FFFFFF;background: #E74E59;border: 1px solid #dfdfddf;-moz-border-radius: 4px;-webkit-border-radius: 0px;border-radius: 0px;line-height: normal;'>Denied</a></td>"
                                . "</tr>";
                        $message.="</tbody></table>";
                        $email_data = array(
                            'subject' => $subject,
                            'message' => $message
                        );
                        $body = $this->load->view('/templates/email/index.php', $email_data, TRUE);
                        $mail_status = send_matix_email($body, $subject, $accountEmail);
                    }
                    echo $result;
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *  SuperAdmin Dashboard
     *      @Customer->Single User
     *          1.Approve All License of the User
     *          2. Email send to User with details of License.
     */

    public function approveAll_license() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $customer_id = $this->input->post('customer_id');
            $update_data = array(
                'approved' => '1',
                'updated_at' => date('Y-m-d H:i:s'),
            );
            if ($update_data != null) {
                $this->session->set_flashdata('success', 'License approved. Notification email sent.');
                $result = $this->User_licenses_model->update_by(array('user_id' => $customer_id), $update_data);
                if ($result != null) {
                    $userLicenses = $this->User_licenses_model->get_many_by(array('user_id' => $customer_id, 'approved' => '1'));
                    $user_details = $this->User_model->get($customer_id);
                    if ($userLicenses != null) {
                        $accountName = $user_details->first_name;
                        $accountEmail = $user_details->email;
                        $subject = "Account licenses approval";
                        $message = "Hi,<br />Here is a summary of your dental license status:<br>
                        <table style='border:1px;'><thead><tr><th style='text-align:left;'><b>License #</b></th><th style='text-align:left;'><b>DEA #</b></th><th style='text-align:left;'><b>Expires</b></th><th style='text-align:left;'><b>State</b></th><th style='text-align:left;'><b>Status</b></th></tr></thead><tbody>";
                        foreach ($userLicenses as $licenses) {
                            $expire_date = date('M d, Y', strtotime($licenses->expire_date));
                            $message.="<tr>"
                                    . "<td>$licenses->license_no</td>"
                                    . "<td>$licenses->dea_no</td>"
                                    . "<td>$expire_date</td>"
                                    . "<td>$licenses->state</td>";
                            if (date("Y-m-d", strtotime($licenses->expire_date)) > date("Y-m-d")) {
                                $message .= "<td><button style='padding: 5px 5px 5px 5px;width: auto;text-decoration: none;border: 0;text-align: center;font-weight: bold;font-size: 14px;font-family: Arial, sans-serif;color: #FFFFFF;background: #13C4A3;border: 1px solid #dfdfddf;-moz-border-radius: 4px;-webkit-border-radius: 0px;border-radius: 0px;line-height: normal;'>Approved</a></td>";
                            } else {
                                $message .= "<td><button style='padding: 5px 5px 5px 5px;width: auto;text-decoration: none;border: 0;text-align: center;font-weight: bold;font-size: 14px;font-family: Arial, sans-serif;color: #FFFFFF;background: #E74E59;border: 1px solid #dfdfddf;-moz-border-radius: 4px;-webkit-border-radius: 0px;border-radius: 0px;line-height: normal;'>Expired</a></td>";
                            }
                            $message .= "</tr>";
                        }
                        $message.="</tbody></table>";
//                    echo $message;exit;
                        $email_data = array(
                            'subject' => $subject,
                            'message' => $message
                        );
                        $body = $this->load->view('/templates/email/index.php', $email_data, TRUE);
                        $mail_status = send_matix_email($body, $subject, $accountEmail);
                    }
                }
            }
            header("location: customer-details-page?user_id=$customer_id");
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *  SuperAdmin Dashboard
     *      @Customer->Single User
     *          1.Deny All License of the User
     *          2. Email send to User with details of License.
     */

    public function denyAll_license() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $customer_id = $this->input->post('customer_id');
            $update_data = array(
                'approved' => '-1',
                'updated_at' => date('Y-m-d H:i:s'),
            );
            if ($update_data != null) {
                $this->session->set_flashdata('error', 'License denied. Notification email sent.');
                $result = $this->User_licenses_model->update_by(array('user_id' => $customer_id), $update_data);
                if ($result != null) {
                    $userLicenses = $this->User_licenses_model->get_many_by(array('user_id' => $customer_id));
                    $user_details = $this->User_model->get($customer_id);
                    $accountName = $user_details->first_name;
                    $accountEmail = $user_details->email;
                    $subject = "Account licenses denied";
                    $message = "Hi,<br />Here is a summary of your dental license status:<br>
                        <table style='border:1px;'><thead><tr><th style='text-align:left;'><b>License #</b></th><th style='text-align:left;'><b>DEA #</b></th><th style='text-align:left;'><b>Expires</b></th><th style='text-align:left;'><b>State</b></th><th style='text-align:left;'><b>Status</b></th></tr></thead><tbody>";
                    foreach ($userLicenses as $licenses) {
                        $expire_date = date('M d, Y', strtotime($licenses->expire_date));
                        $message.="<tr>"
                                . "<td>$licenses->license_no</td>"
                                . "<td>$licenses->dea_no</td>"
                                . "<td>$expire_date</td>"
                                . "<td>$licenses->state</td>"
                                . "<td><button style='padding: 5px 5px 5px 5px;width: auto;text-decoration: none;border: 0;text-align: center;font-weight: bold;font-size: 14px;font-family: Arial, sans-serif;color: #FFFFFF;background: #E74E59;border: 1px solid #dfdfddf;-moz-border-radius: 4px;-webkit-border-radius: 0px;border-radius: 0px;line-height: normal;'>Denied</a></td>"
                                . "</tr>";
                    }
                    $message.="</tbody></table>";
//                    echo $message;exit;
                    $email_data = array(
                        'subject' => $subject,
                        'message' => $message
                    );
                    $body = $this->load->view('/templates/email/index.php', $email_data, TRUE);
                    $mail_status = send_matix_email($body, $subject, $accountEmail);
                }
            }
            header("location: customer-details-page?user_id=$customer_id");
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *  SuperAdmin Dashboard
     *      @AJAX call
     *          1. Unassign User from this location.
     */

    public function unassign_UserLocation() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $customer_id = $this->input->post('customer_id');
            $location_id = $this->input->post('location_id');
            if ($customer_id != null) {
                $this->User_location_model->delete($location_id);
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *  SuperAdmin Dashboard
     *      @AJAX call
     *          1. Assign User from this location.
     */

    public function assign_UserLocation() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $customer_id = $this->input->post('customer_id');
            $organization_location_id = $this->input->post('organization_location_id');
            $userLocationCheck = $this->User_location_model->get_by(array('user_id' => $customer_id, 'organization_location_id' => $organization_location_id));
            if ($userLocationCheck == null) {
                if ($customer_id != null) {
                    $insert_data = array(
                        'user_id' => $customer_id,
                        'organization_location_id' => $organization_location_id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    if ($insert_data != null) {
                        $this->User_location_model->insert($insert_data);
                    }
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *  SuperAdmin Dashboard
     *      @AJAX call
     *          1. Search User location  in (Location Assignment) MODAL.
     */

    public function search_UserLocation() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $organization_id = $this->input->post('organization_id');
            $customer_id = $this->input->post('customer_id');
            $search = $this->input->post('search');
            $query = "SELECT z.id,z.organization_id,z.nickname FROM organization_locations z where z.organization_id=$organization_id and  z.nickname like '%$search%'";
            $data['organizaton_locations'] = $this->db->query($query)->result();
            if ($data['organizaton_locations'] != null) {
                for ($i = 0; $i < count($data['organizaton_locations']); $i++) {
                    $data['organizaton_locations'][$i]->user_location_id = "";
                    $data['organizaton_locations'][$i]->status = 0;
                    $user_locations = $this->User_location_model->get_by(array('organization_location_id' => $data['organizaton_locations'][$i]->id));
                    if ($user_locations != null) {
                        if ($user_locations->user_id == $customer_id) {
                            $data['organizaton_locations'][$i]->status = 1;
                            $data['organizaton_locations'][$i]->user_location_id = $user_locations->id;
                        } else {
                            $data['organizaton_locations'][$i]->status = 0;
                        }
                    }
                }

                echo json_encode($data['organizaton_locations']);
            } else {
                echo json_encode($data['organizaton_locations'] = "");
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function cancelOrder_SuperAdmin() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $order_id = $this->input->post('order_id');
            $customer_id = $this->input->post('customer_id');
            if ($order_id != null) {
                $update_data = array(
                    'order_status' => '5',
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                if ($update_data != null) {
                    $this->Order_model->update($order_id, $update_data);
                    $order_details = $this->Order_model->get($order_id);
                    $data['user_details'] = $this->User_model->get($order_details->user_id);
                    $accountName = $data['user_details']->first_name;
                    $accountEmail = $data['user_details']->email;
                    $order_id = $order_details->id;
                    $subject = "Purchase Order Cancelled";
                    $message = "Hi,<br />Your order placed in Dentomatix has been cancelled.<br><b>Order ID :</b>$order_id";
                    $email_data = array(
                        'subject' => $subject,
                        'message' => $message
                    );
                    $body = $this->load->view('/templates/email/index.php', $email_data, TRUE);
                    $mail_status = send_matix_email($body, $subject, $accountEmail);
//                    $this->email->from('natehornsby@gmail.com', 'Nate Hornsby');
//                    $this->email->to($accountEmail);
//                    $this->email->subject($subject);
//                    $this->email->message($body);
//                    $this->email->send();
                }
            }
            header("Location: customer-details-page?user_id=" . $customer_id);
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *  SuperAdmin Dashboard
     *  Based on select from the User Single page Orders are shown from here.
     */

    public function orderStatus_Admin() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $order_status = $this->input->post('order_status');
            $customer_id = $this->input->post('customer_id');
            $timelimit = $this->input->post('timelimit');

            if (isset($timelimit) && $timelimit != "") {
                $created_at = date("Y-m-d", strtotime("-" . $timelimit . ' days'));
            } else {
                $created_at = date("Y-m-d", strtotime("-30 days"));
            }

            if (isset($order_status) && $order_status != "-1") {
                if ($order_status == "new") {
                    $order_status_filter = "(order_status = 'New' or order_status='In Progress')";
                }
                if ($order_status == "shipped") {
                    $order_status_filter = "(order_status = 'Shipped')";
                }
                if ($order_status == "delivered") {
                    $order_status_filter = "(order_status = 'Delivered')";
                }
            } else {
                $order_status_filter = "1=1";
            }

            $data['latest_reports'] = $this->Order_model->get_many_by(array("created_at >= " => $created_at, 'user_id' => $customer_id, $order_status_filter, 'restricted_order' => '0'));
            if ($data['latest_reports'] != null) {
                for ($i = 0; $i < count($data['latest_reports']); $i++) {
                    $data['latest_reports'][$i]->image_name = "";
                    $orderItems = $this->Order_items_model->get_many_by(array('order_id' => $data['latest_reports'][$i]->id));
                    if ($orderItems != null) {
                        for ($j = 0; $j < count($orderItems); $j++) {
                            $data['latest_reports'][$i]->image_name = $this->Images_model->get_by(array('model_name' => 'products', 'image_type' => 'mainimg', 'model_id' => $orderItems[$j]->product_id));
                        }
                    }
                    $data['latest_reports'][$i]->vendor_name = "";
                    $vendor_details = $this->Vendor_model->get_by(array('id' => $data['latest_reports'][$i]->vendor_id));
                    if ($vendor_details != null) {
                        $data['latest_reports'][$i]->vendor_name = $vendor_details->name;
                    }
                }
            }
            $this->load->view('templates/admin/customers/c/number/order-by/order-status.php', $data);
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function orderStatus_ReportByDays() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $order_selects = $this->input->post('order_selects');
            $customer_id = $this->input->post('customer_id');
            $selection = $this->input->post('selection');
            if ($order_selects != null) {
                switch ($order_selects) {
                    case 1:
                        $startDate = date("Y-m-d", strtotime("-30 days"));
                        $now = date('Y-m-d', now());
                        $data['latest_reports'] = $this->Order_model->get_many_by(array("created_at BETWEEN '" . $startDate . "' and '" . $now . "'", 'user_id' => $customer_id, 'restricted_order' => '0'));
                        if ($data['latest_reports'] != null) {
                            for ($i = 0; $i < count($data['latest_reports']); $i++) {
                                $data['latest_reports'][$i]->image_name = "";
                                $orderItems = $this->Order_items_model->get_many_by(array('order_id' => $data['latest_reports'][$i]->id));
                                if ($orderItems != null) {
                                    for ($j = 0; $j < count($orderItems); $j++) {
                                        $data['latest_reports'][$i]->image_name = $this->Images_model->get_by(array('model_name' => 'products', 'image_type' => 'mainimg', 'model_id' => $orderItems[$j]->product_id));
                                    }
                                }
                                $data['latest_reports'][$i]->vendor_name = "";
                                $vendor_details = $this->Vendor_model->get_by(array('id' => $data['latest_reports'][$i]->vendor_id));
                                if ($vendor_details != null) {
                                    $data['latest_reports'][$i]->vendor_name = $vendor_details->name;
                                }
                            }
                        }
                        break;
                    case 2:
                        $startDate = date("Y-m-d", strtotime("-3 months"));
                        $now = date('Y-m-d', now());
                        $data['latest_reports'] = $this->Order_model->order_by('id', 'desc')->get_many_by(array("created_at BETWEEN '" . $startDate . "' and '" . $now . "'", 'user_id' => $customer_id, 'restricted_order' => '0'));
                        if ($data['latest_reports'] != null) {
                            for ($i = 0; $i < count($data['latest_reports']); $i++) {
                                $data['latest_reports'][$i]->image_name = "";
                                $orderItems = $this->Order_items_model->get_many_by(array('order_id' => $data['latest_reports'][$i]->id));
                                if ($orderItems != null) {
                                    for ($j = 0; $j < count($orderItems); $j++) {
                                        $data['latest_reports'][$i]->image_name = $this->Images_model->get_by(array('model_name' => 'products', 'image_type' => 'mainimg', 'model_id' => $orderItems[$j]->product_id));
                                    }
                                }
                                $data['latest_reports'][$i]->vendor_name = "";
                                $vendor_details = $this->Vendor_model->get_by(array('id' => $data['latest_reports'][$i]->vendor_id));
                                if ($vendor_details != null) {
                                    $data['latest_reports'][$i]->vendor_name = $vendor_details->name;
                                }
                            }
                        }
                        break;
                    case 3:
                        $startDate = date("Y-m-d", strtotime("-6 months"));
                        $now = date('Y-m-d', now());
                        $data['latest_reports'] = $this->Order_model->order_by('id', 'desc')->get_many_by(array("created_at BETWEEN '" . $startDate . "' and '" . $now . "'", 'user_id' => $customer_id, 'restricted_order' => '0'));
                        if ($data['latest_reports'] != null) {
                            for ($i = 0; $i < count($data['latest_reports']); $i++) {
                                $data['latest_reports'][$i]->image_name = "";
                                $orderItems = $this->Order_items_model->get_many_by(array('order_id' => $data['latest_reports'][$i]->id));
                                if ($orderItems != null) {
                                    for ($j = 0; $j < count($orderItems); $j++) {
                                        $data['latest_reports'][$i]->image_name = $this->Images_model->get_by(array('model_name' => 'products', 'image_type' => 'mainimg', 'model_id' => $orderItems[$j]->product_id));
                                    }
                                }
                                $data['latest_reports'][$i]->vendor_name = "";
                                $vendor_details = $this->Vendor_model->get_by(array('id' => $data['latest_reports'][$i]->vendor_id));
                                if ($vendor_details != null) {
                                    $data['latest_reports'][$i]->vendor_name = $vendor_details->name;
                                }
                            }
                        }
                        break;
                    case 4:
                        $startDate = date("Y-m-d", strtotime("-1 year"));
                        $now = date('Y-m-d', now());
                        $data['latest_reports'] = $this->Order_model->order_by('id', 'desc')->get_many_by(array("created_at BETWEEN '" . $startDate . "' and '" . $now . "'", 'user_id' => $customer_id, 'restricted_order' => '0'));
                        if ($data['latest_reports'] != null) {
                            for ($i = 0; $i < count($data['latest_reports']); $i++) {
                                $data['latest_reports'][$i]->image_name = "";
                                $orderItems = $this->Order_items_model->get_many_by(array('order_id' => $data['latest_reports'][$i]->id));
                                if ($orderItems != null) {
                                    for ($j = 0; $j < count($orderItems); $j++) {
                                        $data['latest_reports'][$i]->image_name = $this->Images_model->get_by(array('model_name' => 'products', 'image_type' => 'mainimg', 'model_id' => $orderItems[$j]->product_id));
                                    }
                                }
                                $data['latest_reports'][$i]->vendor_name = "";
                                $vendor_details = $this->Vendor_model->get_by(array('id' => $data['latest_reports'][$i]->vendor_id));
                                if ($vendor_details != null) {
                                    $data['latest_reports'][$i]->vendor_name = $vendor_details->name;
                                }
                            }
                        }
                        break;
                }
                $this->load->view('templates/admin/customers/c/number/order-by/order-status.php', $data);
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *  SuperAdmin Dashboard
     */

    public function customer_order_details() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $order_id = $this->input->get('order_id');
            if ($order_id != null) {
                //  Shipping Methods
                $data['order_details'] = $this->Order_model->get($order_id);
                $order_user_id = $data['order_details']->user_id;
                $user = $this->User_model->get_by(array('id' => $order_user_id));
                $data['customer_details'] = $this->User_model->get($data['order_details']->user_id);
                $vendor_id = $data['order_details']->vendor_id;
                $data['customer_id'] = $data['order_details']->user_id;
                $data['order_details']->card_type = "";
                $data['order_details']->cc_number = "";
                $data['order_details']->bank_number = "";
                $data['order_details']->payment_type = "";
                $data['order_details']->shipping_method = "";
                $data['order_details']->delivery_time = "";
                $data['order_details']->bank_name = "";
                $data['order_details']->ba_account_number = "";
                $shippping_method = $this->Shipping_options_model->get_by(array('id' => $data['order_details']->shipment_id));
                $payment_method = $this->User_payment_option_model->get($data['order_details']->payment_id);
                if ($shippping_method != null) {
                    $data['order_details']->shipping_method = $shippping_method->shipping_type;
                }
                if ($payment_method != null) {

                    $customer = $this->stripe->getCustomer($user->stripe_id);
                    // Payment method
                    $payment_method = $customer->sources->retrieve($payment_method->token);
                    Debugger::debug($payment_method);
                    $data['order_details']->id = $order_id;
                    $data['order_details']->token = $payment_method->token;
                    $data['order_details']->payment_type = $payment_method->object;
                    $data['order_details']->card_type = $payment_method->brand;
                    $data['order_details']->exp_month = $payment_method->exp_month;
                    $data['order_details']->exp_year = $payment_method->exp_year;
                    $data['order_details']->cc_number  = $payment_method->last4;
                    $data['order_details']->cc_name = $payment_method->name;
                    $data['order_details']->bank_name = $payment_method->bank_name;
                    $data['order_details']->ba_routing_number = $payment_method->routing_number;
                    $data['order_details']->bank_number = $payment_method->last4;

                    $date = date("Y-m-d");
                    if (date('Y-m-d', strtotime($data['order_details']->created_at)) == $date) {
                        $data['order_details']->delivery_time = "Today";
                    }
                }
                //  Order Items without PromoCode
                //$query = 'SELECT s.id,q.retail_price,s.id,r.name,r.matix_id,s.price,s.quantity  as picked,q.quantity FROM order_items s INNER JOIN orders p on p.id = s.order_id INNER JOIN product_pricings q on q.id= s.product_id INNER JOIN products r on r.id=q.product_id where s.order_id=' . $order_id . ' and s.promo_code_id in(0)';
                $query = "SELECT a.id,b.id as orderItem_id,d.retail_price,b.promo_code_id,c.title,e.name,e.mpn,d.price,b.picked,b.quantity FROM orders a  inner join order_items b on b.order_id=a.id left join promo_codes c on c.id=b.promo_code_id INNER JOIN product_pricings d on d.product_id=b.product_id INNER JOIN products e on e.id=b.product_id WHERE a.id=$order_id group by b.id";
                $data['purchased_product'] = $this->db->query($query)->result();
                //  Order Items with PromoCode
                //  Order Address
                $data['delivery_name'] = $this->User_location_model->get($data['order_details']->location_id);
                $data['order_address'] = "";
                if ($data['delivery_name'] != null) {
                    $data['order_address'] = $this->Organization_location_model->get($data['delivery_name']->organization_location_id);
                }

                // Calculation Section
                $query = "SELECT a.id,b.id as order_id,b.shipping_price,b.tax,sum(a.total) as total ,a.quantity,d.discount,d.discount_type,d.discount_on FROM order_items a INNER JOIN orders b on a.order_id=b.id INNER JOIN shipping_options c on b.shipment_id=c.id LEFT JOIN promo_codes d on a.promo_code_id=d.id where order_id=$order_id";
                $data['calculation_section'] = $this->db->query($query)->result();
                if ($data['calculation_section'] != null) {
                    //  PROMOTIONs View
                    $query = "SELECT b.id,b.discount_value,c.code,b.promo_id,c.manufacturer_coupon,c.conditions   FROM orders a INNER JOIN order_promotions b on b.order_id=a.id INNER JOIN promo_codes c on c.id=b.promo_id WHERE a.id=$order_id";
                    $data['allpromotions'] = $this->db->query($query)->result();
                    $data['grand_total'] = 00000000000000;
                    for ($i = 0; $i < count($data['calculation_section']); $i++) {
                        $data['grand_total'] = $data['calculation_section'][$i]->total;
                    }
                }
            }
            $data['user_approval'] = user_counts();
            $data['flagged_count'] = flagged_count();
            $data['answer_count'] = flaggedAnswer_count();
            $data['My_vendor_users'] = "";
            $data['vendor_shipping'] = "";
            $data['product_details'] = "";    // The Object is Defined for Delete-product.php Modal
            $data['promoCodes_active'] = "";
            $this->load->view('/templates/admin/customers/c/number/order/index.php', $data);
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

}

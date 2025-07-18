<?php

/*
 *      SuperAdmin's Customer Section Dashboard.
 *          ***    Tasks Happening in this Controller     ***
 *      1. Activate the User
 *      2. Deactivate the Customer
 *      3. Customer Purchase Report Page and Details
 */

class OrganizationSection extends MW_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('MY_privilege_helper');
        $this->load->model('Admin_organization_notes_model');
        $this->load->model('Order_model');
        $this->load->model('Organization_groups_model');
        $this->load->model('Organization_location_model');
        $this->load->model('Organization_model');
        $this->load->model('Role_model');
        $this->load->model('User_licenses_model');
        $this->load->model('User_location_model');
        $this->load->model('User_model');
        $this->load->model('Vendor_model');
        $this->load->library('email'); // load email library
        $this->load->library('auth');
        $this->load->helper('my_email_helper');
        $this->load->helper('MY_support_helper');
    }

    /*
     *      SuperAdmin Dashboard
     *          1.Solo Organization details
     */

    public function organization_account() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $organization_id = $this->input->get('organization_id');
            if ($organization_id != null) {
                // Organization Details
                $data['organization_details'] = $this->Organization_model->get($organization_id);
                if ($data['organization_details'] != null) {
                    $organization_id = $data['organization_details']->id;
                    $organization_type = $data['organization_details']->organization_type;
                    $organization_admin_id = $data['organization_details']->admin_user_id;
                    $organization_group = $this->Organization_groups_model->get_many_by(array('organization_id' => $organization_id));
                    for ($i = 0; $i < count($organization_group); $i++) {
                        $orgUser_id = $organization_group[$i]->user_id;
                        if ($orgUser_id != null) {
                            if ($organization_type == "Business") {
                                $role_id = 3;
                            }
                            if ($organization_type == "School") {
                                $role_id = 7;
                            }
                            if ($organization_admin_id == '0' || $organization_admin_id == NUll) {
                                $data['organization_admin'] = $this->User_model->get_by(array('id' => $orgUser_id, 'role_id' => $role_id));
                            } else {
                                $data['organization_admin'] = $this->User_model->get_by(array('id' => $organization_admin_id, 'role_id' => $role_id));
                            }
                        }
                    }
                } else {
                    $this->session->set_flashdata('error', 'Invalid Entry');
                    header('Location: vendorsIn-list');
                }
                // User(s) detail  in Organization
                $data['organization_group'] = $this->Organization_groups_model->get_many_by(array('organization_id' => $organization_id));
                if ($data['organization_group'] != null) {
                    for ($i = 0; $i < count($data['organization_group']); $i++) {
                        $user_details = $this->User_model->get_by(array('id' => $data['organization_group'][$i]->user_id, 'role_id' => $role_id));
                        $data['organization_details']->first_name = "";
                        if ($user_details != null) {
                            $data['organization_details']->first_name = $user_details->first_name;
                            $data['organization_role_id'] = $user_details->role_id;
                        }
                    }
                }
                if ($data['organization_group'] != null) {
                    //      Search Section.
                    $search = $this->input->post('search');
                    if ($search != null) {
                        $query = "select y.id as user_id,y.first_name as name,y.created_at as user_createdAt,y.status as user_status,x.role_name as user_Role from organization_groups z INNER JOIN users y ON y.id=z.user_id INNER JOIN roles x ON x.id=y.role_id where (y.first_name LIKE '%$search%' or y.email like '%$search%') and z.organization_id=" . $organization_id;
                        $data['organization_group'] = $this->db->query($query)->result();
                        $data['orders_count']=[];
                        if($data['organization_group']!=null) {
                        for ($i = 0; $i < count($data['organization_group']); $i++) {
                            $data['orders_count'][] = $this->Order_model->get_many_by(array('user_id' => $data['organization_group'][$i]->user_id, 'restricted_order' => '0'));
                        }
                        }
                    } else {
                        for ($i = 0; $i < count($data['organization_group']); $i++) {
                            $user_details = $this->User_model->get_by(array('id' => $data['organization_group'][$i]->user_id));
                            $data['organization_group'][$i]->name = "";
                            $data['organization_group'][$i]->user_createdAt = "";
                            $data['organization_group'][$i]->user_Role = "";
                            $data['organization_group'][$i]->user_status = "";
                            if ($user_details != null) {
                                $roles = $this->Role_model->get_by(array('id' => $user_details->role_id));
                                $data['organization_group'][$i]->name = $user_details->first_name;
                                $data['organization_group'][$i]->user_createdAt = $user_details->created_at;
                                $data['organization_group'][$i]->user_status = $user_details->status;
                                if ($roles != null) {
                                    $data['organization_group'][$i]->user_Role = $roles->role_name;
                                }
                            }
                            $data['orders_count'][] = $this->Order_model->get_many_by(array('user_id' => $data['organization_group'][$i]->user_id, 'restricted_order' => '0'));
                        }
                    }
                }
            }
            // Organization Location(s)
            $data['organization_details']->total_count = "";
            $data['organization_details']->total_orders = "";
            for ($i = 0; $i < count($data['orders_count']); $i++) {
                for ($j = 0; $j < count($data['orders_count'][$i]); $j++) {
                    $data['organization_details']->total_count = $data['organization_details']->total_count + $data['orders_count'][$i][$j]->total;
                    $data['organization_details']->total_orders = $data['organization_details']->total_orders + 1;
                }
            }
            $data['organization_location'] = $this->Organization_location_model->get_many_by(array('organization_id' => $organization_id));
            if ($data['organization_location'] != null) {
                for ($k = 0; $k < count($data['organization_location']); $k++) {
                    $data['organization_location'][$k]->shipment = "";
                    $orders = $this->Order_model->get_many_by(array('location_id' => $data['organization_location'][$k]->id, 'restricted_order' => '0'));
                    $data['organization_location'][$k]->shipment = count($orders);
                }
            }
            //      Add Notes
            $query = "SELECT a.*,b.first_name,c.photo,c.model_name FROM admin_organization_notes a  INNER JOIN users b on b.id=a.admin_id LEFT JOIN images c on c.model_id=a.admin_id WHERE a.organization_id=$organization_id";
            $data['customer_note'] = $this->db->query($query)->result();
            $data['vendor_shipping'] = "";
            $data['My_vendor_users'] = "";
            $data['organization_id'] = $organization_id;     //      To search the Users.
            $data['organization_type'] = $organization_type;
            $data['promoCodes_active'] = "";
            $data['user_approval'] = user_counts();
            $data['flagged_count'] = flagged_count();
            $data['answer_count'] = flaggedAnswer_count();
            $this->load->view('/templates/_inc/header-admin.php');
            $this->load->view('/templates/admin/organizations/o/number/index.php', $data);
            $this->load->view('/templates/_inc/footer-admin.php');
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *  SuperAdmin Dashboard
     *      Add new Location to the Organization by superadmin/admin.
     */

    public function newLocation_add() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $organization_id = $this->input->post('organization_id');
            $organization_groups = $this->Organization_groups_model->get_by(array('organization_id' => $organization_id));
            if (isset($organization_id)) {
                $insert_data = array(
                    'organization_id' => $organization_id,
                    'nickname' => $this->input->post('nickName'),
                    'address1' => $this->input->post('address1'),
                    'address2' => $this->input->post('address2'),
                    'city' => $this->input->post('city'),
                    'zip' => $this->input->post('zip'),
                    'state' => $this->input->post('state'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                if ($insert_data != null) {
                    $location_id = $this->Organization_location_model->insert($insert_data);
                }
            }
            header('Location: organization-details-page?organization_id=' . $organization_id);
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *  SuperAdmin Dashboard
     *      Register a New organization and sending a notification for the created Organization.
     */

    public function organization_register() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $email = $this->input->post('email');
            $email_check = $this->User_model->get_by(array('email' => $email));
            if ($email_check == null) {
                $selection = $this->input->post('selection');
                $insert_org = array(
                    'organization_name' => $this->input->post('orgName'),
                    'organization_type' => $selection,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                $organization_id = $this->Organization_model->insert($insert_org);
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
                    $accountEmail = $this->input->post('email');
                    $accountName = $this->input->post('first_name');
                    if ($selection == 1) {
                        $role_id = 3;
                    }
                    if ($selection == 2) {
                        $role_id = 7;
                    }
                    $insert_data = array(
                        'email' => $accountEmail,
                        'first_name' => $accountName,
                        'role_id' => $role_id,
                        'new_password' => $this->auth->hashPassword($password),
                        'confirmation_token' => $register_confirm_token,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    if ($insert_data != null) {
                        $user_id = $this->User_model->insert($insert_data);
                        // Update: The user will be Admin for This Organization. Date: 05/09/2017
                        $update_org = array(
                            'admin_user_id' => $user_id,
                            'updated_at' => date('Y-m-d H:i:s'),
                        );
                        $this->Organization_model->update($organization_id, $update_org);
                        $organization_group = array(
                            'user_id' => $user_id,
                            'organization_id' => $organization_id,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        );
                        $organization_group_id = $this->Organization_groups_model->insert($organization_group);
                        $data['roles'] = $this->Role_model->get($role_id);
                        $role_name = $data['roles']->role_name;
                        $subject = 'Organization Invitation';
                        $message = "Hi,<br/>"
                                . "Welcome to the Matix marketplace. Please click below to confirm your organization account and login with given details."
                                . "<table>";
                        if ($role_id != 10) {
                            $message .= "<tr><td><b>Role Name :</b></td><td>$role_name</td></tr>";
                        }
                        $message .= "<tr>"
                                . "<td><b>Email :</b></td><td>$accountEmail</td>"
                                . "</tr>"
                                . "<tr>"
                                . "<td><b>Password :</b></td><td>$password</td>"
                                . "</tr>"
                                . "</table>"
                                . "<br /><b>Note :</b> Please change the password once logged-in"
                                . "<a href='" . $this->config->item('site_url') . "superadmin-organization-confirmation?register_confirm_token=" . $register_confirm_token . "' style='padding: 20px 40px 20px 40px;width:auto;display: block;text-decoration: none;border:0;text-align: center;font-weight: bold;font-size: 16px;font-family: Arial, sans-serif;color: #ffffff;background: #2893FF;border: 1px solid #317ED0;-moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius: 4px;line-height:normal;' class='button_link'>Confirm Account</a>";
                        $email_data = array(
                            'subject' => $subject,
                            'message' => $message
                        );
                        $body = $this->load->view('/templates/email/index.php', $email_data, TRUE);
                        $mail_status = send_matix_email($body, $subject, $accountEmail);
//                    $this->email->from('natehornsby@gmail.com', 'Nate Hornsby');
//                    $this->email->to($email);
//                    $this->email->subject($subject);
//                    $this->email->message($body);
//                    $this->email->send();
                    }
                    $this->session->set_flashdata('success', 'Invitation sent to organization.');
                    header('Location: organizations-list');
                }
            } else {
                $this->session->set_flashdata('error', 'Email already exists. Please try again.');
                header('Location: organizations-list');
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     * SuperAdmin Dashboard
     *      Account Conformation
     */

    public function organization_account_register() {
        $confirmation_token = $this->input->get('register_confirm_token');
        $user_detail = $this->User_model->get_by(array('confirmation_token' => $confirmation_token));
        if ($user_detail != null) {
            $confirm_token_tb = $user_detail->confirmation_token;
            $user_id = $user_detail->id;
        }
        if ($confirmation_token == $confirm_token_tb) {
            $update_data = array(
                'confirmation_token' => "",
                'status' => '1',
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $this->User_model->update($user_id, $update_data);
            $this->session->set_flashdata('success', 'Account approved. Please sign in.');
            header("Location: login");
        } else {
            $this->session->set_flashdata('error', 'Your account is not  approved. Please try again');
            header("Location: login");
        }
    }

    /*
     *  SuperAdmin Dashboard
     *      Activate/Deactivate the User(s) in Organization.
     */

    public function organization_UserStatusChange() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $organization_id = $this->input->post('organization_id');
            $select = $this->input->post('select');
            $user_ids = explode(',', $this->input->post('user_id'));
            $update_data = array(
                'status' => $select,
                'updated_at' => date('Y-m-d H:i:s'),
            );
            if ($update_data != null) {
                $this->User_model->update_many($user_ids, $update_data);
            }
            header('Location: organization-details-page?organization_id=' . $organization_id);
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *      SuperAdmin dashboard
     *          1. Inviting a New User for the Organization.
     */

    public function invitation_UserOrganization() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $accountEmail = $this->input->post('accountEmail');
            $accountName = $this->input->post('accountName');
            $role_id = $this->input->post('role_id');
            $organization_id = $this->input->post('organization_id');
            $email_check = $this->User_model->get_by(array('email' => $accountEmail));
            if ($email_check != null) {
                $this->session->set_flashdata('error', 'Email already exists. Please try again.');
                header("location: superAdmins-Users");
                exit();
            } else {
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
                $insert_data = array(
                    'email' => $accountEmail,
                    'first_name' => $accountName,
                    'role_id' => $role_id,
                    'new_password' => $this->auth->hashPassword($password),
                    'confirmation_token' => $register_confirm_token,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                if ($insert_data != null) {
                    $user_id = $this->User_model->insert($insert_data);
                    // Organization registration
                    if ($user_id != null) {
                        $insertInto_group = array(
                            'user_id' => $user_id,
                            'organization_id' => $organization_id,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        );
                        $this->Organization_groups_model->insert($insertInto_group);
                    }
                    $data['roles'] = $this->Role_model->get($role_id);
                    $role_name = $data['roles']->role_name;
                    $subject = 'Organization User Invitation';
                    $message = "Hi,<br />"
                            . "<br>Welcome to the Matix marketplace. Please click below to confirm your account and login with given password"
                            . "<table>";
                    if ($role_id != 10) {
                        $message .= "<tr><td><b>Role Name :</b></td><td>$role_name</td></tr>";
                    }
                    $message .= "<tr>"
                            . "<td><b>Email :</b></td><td>$accountEmail</td>"
                            . "</tr>"
                            . "<tr>"
                            . "<td><b>Password :</b></td><td>$password</td>"
                            . "</tr>"
                            . "</table>"
                            . "<br /><b>Note :</b> Please change the password once logged-in"
                            . "<a href='" . base_url() . "superadmin-registrater-confirmation?register_confirm_token=" . $register_confirm_token . "'  style='padding: 20px 40px 20px 40px;width:auto;display: block;text-decoration: none;border:0;text-align: center;font-weight: bold;font-size: 16px;font-family: Arial, sans-serif;color: #ffffff;background: #2893FF;border: 1px solid #317ED0;-moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius: 4px;line-height:normal;' class='button_link'>Confirm</a>";
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
//                    NOTE:   ( superadmin-registrater-confirmation ) Using same function for registration confirmation.
                }
                $this->session->set_flashdata('success', 'Invitation is sent to user.');
                header('Location: organization-details-page?organization_id=' . $organization_id);
            }
            $this->session->set_flashdata('success', 'Invitation is sent to user.');
            header('Location: organization-details-page?organization_id=' . $organization_id);
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *  SuperAdmin Dashboard
     *      AJAX call for searching the Organization name and to include the Customer in Organization.
     */

    public function create_NewCustomer() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $organization = $this->input->post('organization_name');
            $organization_id = substr($organization, 0, strpos($organization, "||"));
            if ($organization_id != null) {
                $accountEmail = $this->input->post('accountEmail');
                $accountName = $this->input->post('accountName');
                $role_id = $this->input->post('role_id');
                $email_check = $this->User_model->get_many_by(array('email' => $accountEmail));
                if ($email_check != null) {
                    $this->session->set_flashdata('error', 'Email already exists. Please try again.');
                    header("location: customer-list");
                    exit();
                } else {
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
                    $insert_data = array(
                        'email' => $accountEmail,
                        'first_name' => $accountName,
                        'role_id' => $role_id,
                        'new_password' => $this->auth->hashPassword($password),
                        'confirmation_token' => $register_confirm_token,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    if ($insert_data != null) {
                        $user_id = $this->User_model->insert($insert_data);

                        $inser_organization = array(
                            'user_id' => $user_id,
                            'organization_id' => $organization_id,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        );
                        $this->Organization_groups_model->insert($inser_organization);
                        $organization_details = $this->Organization_model->get($organization_id);
                        $organization_name = $organization_details->organization_name;
                        $data['roles'] = $this->Role_model->get($role_id);
                        $role_name = $data['roles']->role_name;
                        $subject = 'Invitation from the Organization';
                        $message = "Hi,<br />"
                                . "Welcome to the Matix marketplace. Please click below to confirm your account email address."
                                . "<table>"
                                . "<tr>"
                                . "<td><b>Organization:</b></td><td>$organization_name</td>"
                                . "</tr>";
                        if ($role_id != 10) {
                            $message .= "<tr><td><b>Role Name :</b></td><td>$role_name</td></tr>";
                        }
                        $message .= "<tr>"
                                . "<td><b>Email :</b></td><td>$accountEmail</td>"
                                . "</tr>"
                                . "<tr>"
                                . "<td><b>Password :</b></td><td>$password</td>"
                                . "</tr>"
                                . "</table>"
                                . "<br /><b>Note :</b> Please change the password once logged-in"
                                . "" . "<a href='" . base_url() . "superadmin-registrater-confirmation?register_confirm_token=" . $register_confirm_token . "'  style='padding: 20px 40px 20px 40px;width:auto;display: block;text-decoration: none;border:0;text-align: center;font-weight: bold;font-size: 16px;font-family: Arial, sans-serif;color: #ffffff;background: #2893FF;border: 1px solid #317ED0;-moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius: 4px;line-height:normal;' class='button_link'>Acitvate</a>";
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
                    $this->session->set_flashdata('success', 'Invitation sent to the user');
                    header('Location: customer-list');
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *      SuperAdmin Dashboard
     *      @Organization // Single Organization
     *      @AJAX call from Edit Organization(MODAL)
     */

    public function get_organizationUser() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $organization_id = $this->input->post('organization_id');
            $emailid = $this->input->post('emailId');
            if ($organization_id != null) {
                $data['organization_details'] = $this->Organization_groups_model->organizationGroup_users($organization_id, $emailid);
                if ($data['organization_details'] != null) {
                    echo json_encode($data['organization_details']);
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function organization_UserUpdate() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $orgEmail = $this->input->post('orgEmail');
            $admin_user_id = $this->input->post('admin_user_id');
            $organization_id = $this->input->post('organization_id');
            if ($organization_id != null) {
                $update_data = array(
                    'organization_name' => $this->input->post('orgName'),
                    'admin_user_id' => $admin_user_id,
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                if ($update_data != null) {
                    $this->Organization_model->update($organization_id, $update_data);
                }
                $organization_details = $this->Organization_model->get($organization_id);
                if ($organization_details != null) {
                    if ($organization_details->organization_type == "Business") {
                        $update_user = array(
                            'role_id' => '3',
                            'updated_at' => date('Y-m-d H:i:s'),
                        );
                    } else {
                        $update_user = array(
                            'role_id' => '7',
                            'updated_at' => date('Y-m-d H:i:s'),
                        );
                    }
                    $this->User_model->update($admin_user_id, $update_user);
                }
                $this->session->set_flashdata('success', 'The organization admin is changed');
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

}

<?php

class Search extends MW_Controller {
    /*
     *  Search result.
     */

    public function __construct() {
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
        $this->load->model('Prepopulated_list_model');
        $this->load->model('Prepopulated_product_model');
        $this->load->model('Product_question_model');
        $this->load->model('Role_model');
        $this->load->model('User_model');
        $this->load->model('Class_student_model');
        $this->load->model('Order_model');
        $this->load->model('Order_items_model');
        $this->load->model('Shipping_options_model');
        $this->load->model('User_payment_option_model');
        $this->load->model('Images_model');
        $this->load->model('Recurring_order_model');
        $this->load->model('Recurring_order_item_model');
        $this->load->model('Order_item_return_model');
        $this->load->model('Order_return_model');
        $this->load->model('User_licenses_model');
        $this->load->model('Location_inventories_model');
        $this->load->model('Product_answer_model');
        $this->load->model('User_autosave_model');
        $this->load->model('Promo_codes_model');
        $this->load->model('Product_tax_model');
        $this->load->model('Class_student_model');
        $this->load->model('Class_model');
        $this->load->model('Request_list_activity_model');
        $this->load->library('encryption');
        $this->load->model('ApiSearch_model');
        $this->load->helper('string');
        $this->load->helper('date');
        $this->load->library('cart');
        $this->load->library('email');
        $this->load->library('auth');
        $this->load->library('stripe');
        $this->load->helper('my_email_helper');
        $this->load->library('elasticsearch');
    }

    public function search_page() {
        if (isset($_SESSION['user_id']) != null) {
            $this->render('home', 'full_width');
            $this->load->view('user/search/search_page.php');
        }
    }

//     public function search_results() {
//         $data['q'] = $this->input->get('q');
//         $searching = $this->elasticsearch->query_all($data['q']);
//         if ($searching != null) {
//             if ($searching['hits']['total'] >= 1) {
//                 $data['search_results'] = $this->elasticsearch->query("products", $data['q']);
// //            echo "<pre>";print_r($data['search_results']);echo "</pre>";exit;
//                 $this->render('home', 'full_width');
//                 $this->load->view('user/search/search_page_success.php', $data);
//             } else {
//                 $this->render('home', 'full_width');
//                 $this->load->view('user/search/search_empty.php', $data);
//             }
//         }
//     }

    public function addUser_ToLocation() {
        if (isset($_SESSION['user_id'])) {
            $addUser_id = $this->input->post('user_id');
            $user_locationCheck = $this->User_location_model->get_by(array('user_id' => $addUser_id, 'organization_location_id' => $this->input->post('location_id')));
            if ($user_locationCheck == null) {
                $insert_data = array(
                    'user_id' => $this->input->post('user_id'),
                    'organization_location_id' => $this->input->post('location_id'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                if ($insert_data != null) {
                    $result = $this->User_location_model->insert($insert_data);
                }
            }
        }
    }

    public function invite_UserwithLocation() {
        if (isset($_SESSION['user_id'])) {
            $accountEmail = $this->input->post('accountEmail');
            $accountName = $this->input->post('accountName');
            $role_id = $this->input->post('role_id');
            $organization_id = $this->input->post('organization_id');
            $location_id = $this->input->post('location_id');
            $email_check = $this->User_model->get_by(array('email' => $accountEmail));
            if ($email_check != null) {
                if ($email_check->status == 0 && $email_check->confirmation_token == null) {
                    $update_user_id = $email_check->id;
                    $update_data = array(
                        'first_name' => $accountName,
                        'role_id' => $role_id,
                        'status' => '1',
                        'updated_at' => date('Y-m-d H:i:s'),
                    );

                    if ($update_data != null) {
                        $this->User_model->update($update_user_id, $update_data);
                        if ($update_user_id != null) {
                            $insertInto_group = array(
                                'user_id' => $update_user_id,
                                'organization_id' => $organization_id,
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                            );
                            $this->Organization_groups_model->insert($insertInto_group);

                            $location_data = array(
                                'user_id' => $update_user_id,
                                'organization_location_id' => $location_id,
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                            );
                            if ($location_data != null) {
                                $result = $this->User_location_model->insert($location_data);
                            }
                        }
                    }
                    $this->session->set_flashdata('success', 'Email already exists.This User Account activated successfully');
                } else {
                    $this->session->set_flashdata('error', 'Email already exists. Please try again.');
                }

                header("location: users?location_id=$location_id");
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
                        $location_data = array(
                            'user_id' => $user_id,
                            'organization_location_id' => $location_id,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        );
                        if ($location_data != null) {
                            $result = $this->User_location_model->insert($location_data);
                        }
                    }
                    $data['roles'] = $this->Role_model->get($role_id);
                    $role_name = $data['roles']->role_name;
                    $subject = 'Organization user invitation';
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
                            . "<br />Note : Please change the password once logged-in"
                            . "<a href='" . base_url() . "superadmin-registrater-confirmation?register_confirm_token=" . $register_confirm_token . "'  style='padding: 20px 40px 20px 40px;width:auto;display: block;text-decoration: none;border:0;text-align: center;font-weight: bold;font-size: 16px;font-family: Arial, sans-serif;color: #ffffff;background: #2893FF;border: 1px solid #317ED0;-moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius: 4px;line-height:normal;' class='button_link'>Login</a>";
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
                header("location: users?location_id=$location_id");
            }
            $this->session->set_flashdata('success', 'Invitation is sent to user.');
            header("location: users?location_id=$location_id");
        }
    }

    public function Search_UserwithLocation() {
        $organization_id = $this->input->post('organization_id');
        $location_id = $this->input->post('location_id');
        $search = $this->input->post('search');
        if ($search != null) {
            $query = "SELECT b.id as user_id,b.first_name,b.role_id,b.email,d.model_name,d.photo FROM organization_groups a  INNER JOIN  users b on a.user_id=b.id LEFT JOIN images d on d.model_name='user' and d.model_id=b.id WHERE a.organization_id=$organization_id and b.first_name like '%$search%'  and b.id not in (select user_id from user_locations where organization_location_id = $location_id)";
            $data['organizationUsers'] = $this->db->query($query)->result();
            echo json_encode($data['organizationUsers']);
        }
    }

    public function Unassign_UserLocation() {
        $location_id = $this->input->post('location_id');
        $user_id = $this->input->post('user_id');
        $delete_id = explode(",", $user_id);
        if ($location_id != null) {
            if ($delete_id != null) {
                $user_locations = "";
                for ($i = 0; $i < count($delete_id); $i++) {
                    $this->User_location_model->delete_by(array('user_id' => $delete_id[$i], 'organization_location_id' => $location_id));
                    $user_locations = $this->User_location_model->get_by(array('user_id' => $delete_id[$i]));
                    if ($user_locations == null) {
                        $update_data = array(
                            'status' => '0',
                            'login_status' => '0',
                            'updated_at' => date('Y-m-d H:i:s')
                        );
                        $this->User_model->update($delete_id[$i], $update_data);
                        $unassign_user_id = $this->Organization_groups_model->get_by(array('user_id' => $delete_id[$i]));
                        $this->Organization_groups_model->delete($unassign_user_id->id);
                    }
                }
            }
            $this->session->set_flashdata('success', 'The user is removed from this Location');
            header("Location: users?location_id=$location_id");
        }
    }

    public function Remove_UserLocation() {
        $location_id = $this->input->post('location_id');
        $user_location_id = $this->input->post('user_location_id');
        $user_id = $this->input->post('user_id');
        if ($location_id != null) {
            $this->User_location_model->delete_by(array('user_id' => $user_id, 'organization_location_id' => $location_id));
        }
        $user_locations = "";
        $user_locations = $this->User_location_model->get_by(array('user_id' => $user_id));
        if ($user_locations == null && $user_locations == "") {
            $update_data = array(
                'status' => '0',
                'login_status' => '0',
                'updated_at' => date('Y-m-d H:i:s')
            );
            $this->User_model->update($user_id, $update_data);
            $unassign_user_id = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
            $this->Organization_groups_model->delete($unassign_user_id->id);
        }
        $this->session->set_flashdata('success', 'The user is removed from this Location');
        header("Location: users?location_id=$location_id");
    }

    public function Yearly_TotalCount() {
        $date_check = date('Y-m-01');
        $user_id = $_SESSION['user_id'];
        $timeframe = $this->input->post('timeframe');

        if ($timeframe == null) {
            $date_check = date('Y-m-01');
        } else {
            if ($timeframe == "0") {
                $date_check = date('Y-m-01');
            }
            if ($timeframe == "1") {
                $date_check = date('Y-01-01');
            }
        }
        $location_id = $this->input->post('location_id');
        $sql = "SELECT sum(total) as totals from orders where order_status != 'Cancelled' and restricted_order = '0' and user_id='" . $user_id . "' and location_id='" . $location_id . "' and created_at >='" . $date_check . "'";
        $data['total_spend'] = $this->db->query($sql)->result();
        echo json_encode($data['total_spend']);
    }

    public function search()
    {
         $result = $this->ApiSearch_model->run($category,
                                              $manufacturer,
                                              $vendor_id,
                                              $procedure,
                                              $list_id,
                                              $licenseRequired,
                                              $purchased,
                                              $this->input->post('query'),
                                              $start = 0,
                                              $perPage = 10,
                                              $option = null);

        Debugger::debug($result['totalResults']);
        echo json_encode($result);
    }
}

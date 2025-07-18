<?php

/*
 * 1. Working on Vendor Page.
 */

class Vendor extends MW_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Messages_model');
        $this->load->model('Review_model');
        $this->load->model('Role_model');
        $this->load->model('Vendor_model');
        $this->load->model('Images_model');
        $this->load->model('Vendor_groups_model');
        $this->load->model('Vendor_policies_model');
        $this->load->model('Business_hour_model');
        $this->load->model('Flagged_reviews_model');
        $this->load->model('Shipping_options_model');
        $this->load->helper('my_privilege_helper');
        $this->load->helper('MY_support_helper');
        $this->load->helper('my_email_helper');
        $this->load->library('email'); // load email library
        $this->load->library('auth'); // load auth library
    }

    public function vendor_page() {
        /*
         *  1. The Vendor Page is Comes from the Product_page where the User chooses the Vendors detail.
         *  2. Based on Vendor ID passed from the Product Page it should get the Vendor_details.
         *  3. From Vendor_details have to get the Reviews of Vendor as well.
         *  4. Star Rating based on all the Reviews given by users have to be done
         */

        //$user_id = $this->input->get('user_id');
        if(isset($_SESSION['user_id'])) {
        $vendors_userId = $this->input->get('user_id');
        $vendor_detail = $this->Vendor_groups_model->get_by(array('user_id' => $vendors_userId));
        $vendor_id = $vendor_detail->vendor_id;
        $data['user_detail'] = $this->User_model->get_by(array('id' => $vendors_userId));
        $data['vendor_review'] = $this->Review_model->get_many_by(array('model_name' => 'vendor', 'model_id' => $vendor_id));
        if ($data['vendor_review'] != null) {
            for ($i = 0; $i < count($data['vendor_review']); $i++) {
                $user = $this->User_model->get_by(array('id' => $data['vendor_review'][$i]->user_id));
                $user_name = "";
                if ($user != null) {
                    $user_name = $user->first_name;
                }
                $data['vendor_review'][$i]->first_name = $user_name;
                $data['total_ratings'] = count($data['vendor_review']);
                $average_rating = "0";
                $total_score = 0;
                $data['total_ratings'] = "0";
                if ($data['vendor_review'] != null) {
                    for ($j = 0; $j < count($data['vendor_review']); $j++) {
                        $total_score += $data['vendor_review'][$j]->rating;
                    }
                    if (count($data['vendor_review']) == 0) {
                        $average_rating = 0;
                    } else {
                        $data['average_rating'] = $total_score / count($data['vendor_review']);
                        $data['total_ratings'] = count($data['vendor_review']);
                    }
                }
            }
        }
        $this->render('home', 'full_width');
        $this->load->view('vendor/vendor_profile.php', $data);
        } else {
            $this->session->set_flashdata('error', 'Session expired. Please login again.');
            header('Location: login');
        }
    }

    Public function sendTo_vendor() {
        $insert_data = array(
            'to' => $this->input->post('email'),
            'subject' => $this->input->post('subject'),
            'message' => $this->input->post('message'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        );
        if ($insert_data != null) {
            $insert_data = $this->Messages_model->insert($insert_data);
            $this->session->set_flashdata('success', 'The message is Send ');
            header('Location: vendor-Page');
        }
    }

    public function vendor_review() {

        if (isset($_SESSION["user_id"])) {
            $user_id = $_SESSION['user_id'];
            $vendor_id = $this->input->post('vendor_id');
            $insert_data = array(
                'user_id' => $user_id,
                'rating' => $this->input->post('rating'),
                'title' => $this->input->post('review_title'),
                'comment' => $this->input->post('message'),
                'model_name' => 'vendor',
                'model_id' => $vendor_id,
                'speed' => $this->input->post('speed'),
                'service' => $this->input->post('service'),
                'ease' => $this->input->post('ease'),
                'responsiveness' => $this->input->post('responsiveness'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            if ($insert_data != null) {
                $this->Review_model->insert($insert_data);
                // header('Location: vendor-profile');

                $from = $this->input->post("from");
                if ($from != null && $from == "history") {
                    header("Location: history");
                } else {
                    header("Location: vendor-profile?id=" . $vendor_id);
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Session expired. Please login again.');
            header('Location: login');
        }
    }

    public function upvote_vendor() {
        if(isset($_SESSION['user_id'])) {
        $update_id = $this->input->post('update_id');
        $upvote_data = $this->Review_model->get_by(array('id' => $update_id));
        $db_upvote = $upvote_data->upvotes;
        $update_data = $db_upvote + 1;
        $this->Review_model->update($update_id, array('upvotes' => $update_data));
    } else {
        $this->session->set_flashdata('error', 'Session expired. Please login again.');
            header('Location: login');
        }
    }

    public function raising_flag() {
        /*
         * When Flag is Raised a  Message should send to the Admin.
         *  1. It should be happening here.
         */
        $review_id = $this->input->post('review_id');
        if ($review_id != null) {
            $flag_review = $this->Review_model->get_by(array('id' => $review_id));
            $model_id = $flag_review->model_id;
//            if($flag_review->flag_count!=null) {
//                $flag_count=$flag_review->flag_count+1;
//            } else {
//                $flag_count=1;
//            }
            // If-case SHort-Handed in the Process.
            $flag_count = ($flag_review->flag_count != null ? $flag_count = $flag_review->flag_count + 1 : $flag_count = 1);
            $update_data = array(
                'flag_count' => $flag_count,
                'updated_at' => date('Y-m-d H:i:s'),
            );
            if ($update_data != null) {
                $this->Review_model->update($review_id, $update_data);
                $this->session->set_flashdata('success', 'Flag successful. Admin will be notified.');
                header('Location: vendor-Page?user_id=' . $model_id);
            }
        }
    }

    public function flag_vendor_review() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $review_id = $this->input->post('review_id');
            $review_title = $this->input->post('review_title');
            $answer = $this->input->post('review_comments');
            $product_name = $this->input->post('product_name');
            $p_id = $this->input->post('p_id');
            $user_name = $_SESSION['user_name'];
            $user_id = $_SESSION['user_id'];
            $flaggedCheck = $this->Flagged_reviews_model->get_by(array('model_id' => $review_id, 'user_id' => $user_id, 'model_name' => 'vendor'));
            if ($flaggedCheck == "" && $flaggedCheck == null) {
                $insertFlag_data = array(
                    'user_id' => $user_id,
                    'model_id' => $review_id,
                    'model_name' => 'vendor',
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
                            . $user_name . " has flagged the following review:<br>"
                            . "<table cellpadding='5' cellspacing='5' border='0' width='100' style='width: 450px; padding:5px; background-color:#ffffff; border-bottom:1px solid #E8EAF1; border-top:4px solid #13C4A3;' class='100p'>"
                            . "<tr style='width: 100px;'><td>Vendor Name: </td><td> $product_name</td></tr>"
                            . "<tr style='width: 100px;'><td>Review Title: </td><td> $review_title</td></tr>"
                            . "<tr style='width: 100px;'><td>Review Comment: </td><td> $answer</td></tr></table><br><br>"
                            . "<a href='" . base_url() . "vendor-profile?id=" . $p_id . "' style='padding: 20px 40px 20px 40px;width:auto;display: block;text-decoration: none;border:0;text-align: center;font-weight: bold;font-size: 16px;font-family: Arial, sans-serif;color: #ffffff;background: #2893FF;border: 1px solid #317ED0;-moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius: 4px;line-height:normal;' class='button_link'>View Vendor Profile</a>";
                    $email_data = array(
                        'subject' => $subject,
                        'message' => $message
                    );
                    $body = $this->load->view('/templates/email/index.php', $email_data, TRUE);
                    $mail_status = send_matix_email($body, $subject, $email);
                }
                $this->session->set_flashdata("success", "The review is flagged ");
                header("Location:vendor-profile?id=" . $p_id);
            } else {
                $this->session->set_flashdata("error", "The vendor review is already flagged by you ");
                header("Location:vendor-profile?id=" . $p_id);
            }
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function vendor_invitation() {
        if (isset($_SESSION['user_id']) != null) {
            $user_id = $_SESSION['user_id'];
            $vendor_detail = $this->Vendor_groups_model->get_by(array('user_id' => $user_id));
            $vendor_id = $vendor_detail->vendor_id;
            $email = $this->input->post('userEmail');
            $role_id = $this->input->post('role_id');
            $role_details = $this->Role_model->get($role_id);
            $role_name = $role_details->role_name;
            $email_check = $this->User_model->get_by(array('email' => $email));
//            print_r($email_check);exit;
            if ($email_check != null) {
                $this->session->set_flashdata('error', 'Email already exists. Please try again.');
                header("location: view-vendors");
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
                    'first_name' => $this->input->post('first_name'),
                    'email' => $email,
                    'role_id' => $role_id,
                    'new_password' => $this->auth->hashPassword($password),
                    'confirmation_token' => $register_confirm_token,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                if ($insert_data != null) {
                    $user_id = $this->User_model->insert($insert_data);
                    if ($user_id != null) {
                        $insert_group = array(
                            'user_id' => $user_id,
                            'vendor_id' => $vendor_id,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        );
                        if ($insert_group != null) {
                            $vendor_group = $this->Vendor_groups_model->insert($insert_group);
                        }
                    }
                }
                if ($vendor_id != null) {
                    $subject = "Vendor Invitation";
                    $message = "Hi,"
                            . "<table>"
                            . "<tr>"
                            . "<td>Email:</td><td>$email</td>"
                            . "</tr>"
                            . "<tr>"
                            . "<td>Password:</td><td>$password</td>"
                            . "</tr>"
                            . "</table>"
                            . "<br>Welcome to the Matix marketplace. Please click below to confirm your account email address<br>"
                            . "" . "<a href='" . base_url() . "user-registration-page?register_confirm_token=" . $register_confirm_token . "' style='padding: 20px 40px 20px 40px;width:auto;display: block;text-decoration: none;border:0;text-align: center;font-weight: bold;font-size: 16px;font-family: Arial, sans-serif;color: #ffffff;background: #2893FF;border: 1px solid #317ED0;-moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius: 4px;line-height:normal;' class='button_link'>Account Login</a>";
                    $email_data = array(
                        'subject' => $subject,
                        'message' => $message
                    );
                    $body = $this->load->view('/templates/email/index.php', $email_data, TRUE);
                    $mail_status = send_matix_email($body, $subject, $email);
//                    $this->email->from('natehornsby@gmail.com', 'Nate Hornsby');
//                    $this->email->to($email);
//                    $this->email->subject($subject);
//                    $this->email->message($body);
//                    $this->email->send();
                    $this->session->set_flashdata('success', 'New user created. Verification email sent.');
                    header("location: view-vendors");
                }
                header("location: view-vendors");
            }
        }
    }

    public function view_vendors() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $data['user_roles'] = $this->Role_model->get_all();
            $user_id = $_SESSION['user_id'];
            $vendor_users = $this->Vendor_groups_model->get_by(array('user_id' => $user_id));
            $vendor_id = $vendor_users->vendor_id;
            if ($vendor_id != null) {
                $data['My_vendor_users'] = $this->Vendor_groups_model->get_many_by(array('vendor_id' => $vendor_id));
                for ($i = 0; $i < count($data['My_vendor_users']); $i++) {
                    $data['My_vendor_users'][$i]->Image = "";
                    $user_details = $this->User_model->get_by(array('id' => $data['My_vendor_users'][$i]->user_id));
                    if ($user_details != null) {
                        $data['My_vendor_users'][$i]->Image = $this->Images_model->get_by(array('model_name' => 'user', 'model_id' => $user_details->id));
                    }
                    $data['My_vendor_users'][$i]->user_id = $user_details->id;
                    $data['My_vendor_users'][$i]->email = $user_details->email;
                    $data['My_vendor_users'][$i]->name = $user_details->first_name;
                    $data['My_vendor_users'][$i]->created = $user_details->created_at;
                    $vendor_user_roles = $this->Role_model->get_by(array('id' => $user_details->role_id));
                    $data['My_vendor_users'][$i]->role = $vendor_user_roles->role_name;
                }
            }
            $data['vendor_shipping'] = "";
            $data['promoCodes_active'] = "";
            $data['NorderCount'] = order_count(); // To Get the Latest Order Count.
            $data['ReturnCount'] = return_count();
//            $this->load->view('vendor/vendor_user_page', $data);
            $this->load->view('templates/vendor-admin/users/index.php', $data);
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function vendor_user_edit() {
        if (isset($_SESSION['user_id']) != null) {
            $user_id = $this->input->get('user_id');
            if ($user_id != null) {
                $data['user_details'] = $this->User_model->get_by(array('id' => $user_id));
                $data['user_role'] = $this->Role_model->get_all();
                $this->render('home', 'full_width');
                $this->load->view('vendor/user_edit.php', $data);
            }
        }
    }

    public function update_vendor_user() {
        if (isset($_SESSION["user_id"]) != null) {
            $user_id = $this->input->post('user_id');
            if ($user_id != null) {
                $update_data = array(
                    'first_name' => $this->input->post('accountName'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                if ($update_data != null) {
                    $this->User_model->update($user_id, $update_data);
                    $this->session->set_flashdata('success', 'Vendor Information updated successfully.');
                    header("Location: view-vendors");
                }
            }
        }
    }

    public function delete_vendor_user() {
        if (isset($_SESSION['user_id']) != null) {
            $user_id = $this->input->post('user_id');
            if ($user_id != null) {
                $this->User_model->delete($user_id);
                $this->Vendor_groups_model->delete_by(array('user_id' => $user_id));
                $this->session->set_flashdata('success', 'The Vendor  Successfully Deleted the User');
                header("Location: view-vendors");
            }
        }
    }

    public function delete_vendor_review() {
        $roles_admin = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles_admin))) {
            $review_id = $this->input->get('review_id');
            $vendor_id = $this->input->get('vendor_id');
            if ($review_id != null) {
                $this->Review_model->delete($review_id);
                $this->session->set_flashdata('success', 'Deleted vendor review successfully.');
                header("Location: vendor-profile?id=" . $vendor_id);
            }
        } else {
            $this->session->set_flashdata('success', 'Please contact Vendor');
            header('Location: login');
        }
    }

    public function vendor_role_register() {
        $register_confirm_token = $this->input->get('register_confirm_token');
        $user_details = $this->User_model->get_by(array('confirmation_token' => $register_confirm_token));
//        echo "<pre>";print_r($user_details);exit;
        $user_id = $user_details->id;
        if ($user_id != null) {
            $update_data = array(
                'status' => '1',
                'confirmation_token' => '',
                'updated_at' => date('Y-m-d H:i:s'),
            );
            if ($update_data != null) {
                $this->User_model->update($user_id, $update_data);
                $this->session->set_flashdata('success', 'Please update default password.');
                header('Location: login');
            }
        } else {
            $this->session->set_flashdata('success', 'Please contact Vendor');
            header('Location: login');
        }
    }

    public function user_vendor_register() {
        $user_id = $this->input->get('user_id');
        $this->render('home', 'full_width');
        $data['user_detail'] = $this->User_model->get($user_id);
        $this->load->view('vendor/user_vendors_register.php', $data);
    }

    public function user_vendor_update() {
        $user_id = $this->input->post('user_id');
        if ($user_id != null) {
            $update_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'password' => md5($this->input->post('password')),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            if ($update_data != null) {
                $this->User_model->update($user_id, $update_data);
                $this->session->set_flashdata('success', 'You have created a user account in Matix. Please login.');
                header("Location: user-loginpage");
            }
        }
    }

    public function view_profile() {
        if ((isset($_SESSION['role_id']) && $_SESSION['role_id'] == '3') || $this->input->get('id')) {
            $vendors_userId = $this->input->get('id');
            $vendor_details = $this->Vendor_model->get_by(array('id' => $vendors_userId));
            $vendor_id = $vendor_details->id;
        } elseif ($_SESSION['role_id'] == '11') {
            $vendors_userId = $_SESSION["user_id"];
            $vendor_id = $_SESSION['vendor_id'];
        }
        $data['vendor'] = $this->Vendor_model->get_by(array('id' => $vendor_id));
        if (!isset($data['vendor'])) {
            $this->session->set_flashdata('error', 'Not valid Entry');
            header('Location: home');
        } else {
            $query = "SELECT * from business_hours where vendor_id=$vendor_id group by open_time, close_time";
            $data['business'] = $this->db->query($query)->result();
            $data['all_business'] = $this->Business_hour_model->get_many_by(array('vendor_id' => $vendor_id));


            $data['user_detail'] = $this->User_model->get_by(array('id' => $vendors_userId));
            $options = $this->input->get('options');
            if (isset($options) && $options == '1') {
                $data['vendor_review'] = $this->Review_model->order_by('updated_at', 'desc')->get_many_by(array('model_name' => 'vendor', 'model_id' => $vendor_id));
            } else {
                $review_query = "select *, (COALESCE(upvotes, 0)/(COALESCE(upvotes, 0)+COALESCE(downvotes, 0))) as top_rated from reviews where model_id=$vendor_id and model_name='vendor' order by top_rated desc, upvotes desc";
                $data['vendor_review'] = $this->db->query($review_query)->result();
//             $data['vendor_review'] = $this->Review_model->order_by('rating','desc')->get_many_by(array('model_name' => 'vendor', 'model_id' => $vendor_id));
            }
            $data['options'] = $options;
            $data['image'] = $this->Images_model->get_by(array('model_id' => $vendor_id, 'model_name' => 'vendor'));
            $data['vendor_policy'] = $this->Vendor_policies_model->get_many_by(array('vendor_id' => $vendor_id));
            $data['shippings'] = $this->Shipping_options_model->get_many_by(array('vendor_id' => $vendor_id));
            $data['speed'] = "";
            $data['service'] = "";
            $data['ease'] = "";
            $data['responsiveness'] = "";
            $data['total_ratings'] = "0";
            $data['average_rating'] = "0";
            $total_score = 0;
            $speed_score = 0;
            $service_score = 0;
            $ease_score = 0;
            $resp_score = 0;

            if ($data['vendor_review'] != null) {
                for ($i = 0; $i < count($data['vendor_review']); $i++) {
                    $user = $this->User_model->get_by(array('id' => $data['vendor_review'][$i]->user_id));
                    $data['total_ratings'] = count($data['vendor_review']);
                    $total_score += $data['vendor_review'][$i]->rating;
                    $speed_score += $data['vendor_review'][$i]->speed;
                    $service_score += $data['vendor_review'][$i]->service;
                    $ease_score += $data['vendor_review'][$i]->ease;
                    $resp_score += $data['vendor_review'][$i]->responsiveness;
                    if (count($data['vendor_review']) == 0) {
                        $data['average_rating'] = 0;
                    } else {
                        $data['speed'] = $speed_score / count($data['vendor_review']);
                        $data['service'] = $service_score / count($data['vendor_review']);
                        $data['ease'] = $ease_score / count($data['vendor_review']);
                        $data['responsiveness'] = $resp_score / count($data['vendor_review']);
                        $data['average_rating'] = $total_score / count($data['vendor_review']);
                        $data['total_ratings'] = count($data['vendor_review']);
                    }
                    $data['vendor_review'][$i]->user = $user;
                }
            }
        }
        $data['vendor_shipping'] = "";
        $data['My_vendor_users'] = "";
        if ((isset($_SESSION['role_id']) && $_SESSION['role_id'] == '3') || $this->input->get('id')) {
            $this->load->view('/templates/_inc/header');
            $this->load->view('/templates/vendor/index', $data);
            $this->load->view('/templates/_inc/footer');
        } elseif ($_SESSION['role_id'] == '11') {
            $data['promoCodes_active'] = "";
            $this->load->view('/templates/_inc/header-vendor');
            $this->load->view('/templates/vendor/index', $data);
            $this->load->view('/templates/_inc/footer-vendor');
        }
    }

}

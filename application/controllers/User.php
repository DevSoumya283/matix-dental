<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MW_Controller {
    /*
     *   The User Controller
     *   My_Model is called to Insert the DaTa in Database.
     */

    function __construct() {
        parent::__construct();
        $this->load->model('Order_model');
        $this->load->model('User_model'); // The User_model is a bridge.
        $this->load->model('Role_model');
        $this->load->model('RolePermissions_model');
        $this->load->model('BuyingClub_model');
        $this->load->model('Vendor_model');
        $this->load->model('Vendor_groups_model');
        $this->load->model('Order_model');
        $this->load->library('session');
        $this->load->library('auth');
        $this->load->model('Organization_groups_model');
        $this->load->model('User_location_model');
        $this->load->model('User_payment_option_model');
        $this->load->model('NotificationEmails');
        $this->load->library('email'); // load email library
        $this->load->helper('MY_privilege_helper');
        $this->load->helper('MY_support_helper');
        $this->load->helper('my_email_helper');
    }

    public function user_loginpage() {
        $this->load->view('/templates/login/index');
    }

    public function user_register() {
        $this->input->post('email');

        $insert_data = array(
            'email' => $this->input->post('email'),
            'first_name' => $this->input->post('first_name'),
            'last_name' => $this->input->post('last_name'),
            'salutation' => $this->input->post('salutation'),
            'role_id' => $this->input->post('role_id'),
            //'password' => $password,
            'new_password' => $this->auth->passwordHash($this->input->post('password')),
            'phone1' => $this->input->post('phone1'),
            'phone2' => $this->input->post('phone2'),
            'email_setting1' => $this->input->post('email_settings1'),
            'email_setting2' => $this->input->post('email_settings2'),
            'email_setting3' => $this->input->post('email_settings3'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'password_last_updated_at' => date('Y-m-d H:i:s'),
            'last_login_time' => '',
            'last_login_ip' => '',
            'reset_password_sent_at' => '',
            'confirmation_token' => $confirm_password_reset_token,
        );

        $email = $insert_data['email'];
        $email_check = $this->User_model->email_validation($insert_data);
        // By Using E-mail validation it checks whether email exists or not.
        if ($email_check['status'] == 1) {
            $this->session->set_flashdata('error', 'Email already exists.');
            header("location: user-register");
        } else {
            if ($insert_data != null) {
                $this->User_model->insert($insert_data);
                $subject = 'Account Confirmation Email';
                $message = "Hi,<br /> "
                        . "Welcome to matixdental. Please click below to confirm your account.<br />" . "<a href='" . config_item('site_url') . "register-confirmation?register_confirm_token=" . $confirm_password_reset_token . "' style='padding: 20px 40px 20px 40px;width:auto;display: block;text-decoration: none;border:0;text-align: center;font-weight: bold;font-size: 16px;font-family: Arial, sans-serif;color: #ffffff;background: #2893FF;border: 1px solid #317ED0;-moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius: 4px;line-height:normal;' class='button_link'>Confirm Registration</a>";
                $email_data = array(
                    'subject' => $subject,
                    'message' => $message
                );
                $body = $this->load->view('/templates/email/index.php', $email_data, TRUE);
                send_matix_email($body, $subject, $email);
            }
        }
        header("Location: user-loginpage");
    }

    public function register_confirmation() {

        $confirmation_token = $this->input->get('register_confirm_token');
        $user_detail = $this->User_model->get_by(array('confirmation_token' => $confirmation_token));
        if ($user_detail != null) {
            $confirm_token_tb = $user_detail->confirmation_token;
            $user_id = $user_detail->id;
        }
        Debugger::debug($_GET);
        die('test');
        exit;

        if ($confirmation_token == $confirm_token_tb) {
            $update_data = array(
                'confirmation_token' => "",
                'status' => '1',
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $this->User_model->update($user_id, $update_data);
            $this->session->set_flashdata('success', 'Account approved. Please sign in.');
            header("Location: user-loginpage");
        } else {
            $this->session->set_flashdata('success', 'Your account is not approved. Please try again');
            header("Location: user-register");
        }
    }

    public function user_edit() {
        $user_id = $this->input->get('user_id');
        $this->render('home', 'full_width');
        $data['user_detail'] = $this->User_model->get($user_id);
        $this->load->view('user/user_edit.php', $data);
    }

    public function user_update() {

        $user_id = $this->input->post('id');
        $update_data = array(
            'first_name' => $this->input->post('first_name'),
            'last_name' => $this->input->post('last_name'),
            'salutation' => $this->input->post('salutation'),
            'phone1' => $this->input->post('phone1'),
            'phone2' => $this->input->post('phone2'),
            'email_setting1' => $this->input->post('email_settings1'),
            'email_setting2' => $this->input->post('email_settings2'),
            'email_setting3' => $this->input->post('email_settings3'),
            'password_last_updated_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'last_login_time' => '',
            'last_login_ip' => '',
            'reset_password_sent_at' => '',
        );
        if ($update_data != null) {
            $this->User_model->update($user_id, $update_data);
            $this->session->set_flashdata('success', 'User Information Updated Successfully');
            header("Location:user-edit-page?user_id=" . $user_id);
        }
    }

    /*
     * The function is used to delete the user of SuperAdmin users
     */
    public function user_delete() {

        $user_id = $this->input->get('user_id');
        if ($user_id != null) {
            $this->User_model->delete($user_id);
            $this->session->set_flashdata('error', 'The selected user(s) is deleted from the system');
            header("Location: superAdmins-Users");
        }
    }

    public function user_login() {
        $email = $this->input->post('accountEmail');
        $password = $this->input->post('accountPW');
        $data = array(
            'email' => $email,
            'password' => $password,
        );

        $valid = $this->auth->login($email, $password);

        Debugger::debug($valid, '$valid');

        if(!empty($valid)){
            Debugger::debug('Authentication successful', 'AUTH');

            // load the user
            $user_id = $valid['user_id'];
            $data['user_detail'] = $this->User_model->get_by(array('id' => $user_id));
            $invite = $data['user_detail']->invite;
            // populate the session
            $_SESSION['user_id'] = $data['user_detail']->id;
            $_SESSION['user_name'] = $data['user_detail']->first_name;
            $_SESSION['role_id'] = $data['user_detail']->role_id;
            $_SESSION['email'] = $data['user_detail']->email;
            $_SESSION['user_permissions'] = $this->RolePermissions_model->loadPermissions($data['user_detail']->role_id);
            $_SESSION['user_buying_clubs'] = $this->BuyingClub_model->loadUserClubs($user_id);
            $data['user_role'] = $this->Role_model->get($role_id);
            $_SESSION['user_role'] = $data['user_role'];

            switch ($valid['role_id']) {
                case 1:
                    // superadmin
                    $this->loginSuperadmin($valid);
                    break;
                case 2:
                    // admin
                    $this->loginAdmin($valid);
                    break;
                case 3:
                case 4:
                case 5:
                case 6:
                case 7:
                case 8:
                case 9:
                case 10:
                    $this->loginNormalUser($valid);
                case 11:
                    $this->loginVendor($valid);
                    break;
                case 12:
                default:
                    $this->failedLogin();
                    break;
            }
        }
    /*
        // check if account email confirmed
        if ($valid['role_id'] > 3 && $valid['role_id'] < 11 && empty($valid['email_confirmed'])) {
            // create confirmation hash
            $valid['confirmation_code'] = $this->User_model->createConfirmationToken($valid['user_id']);
            // update user with confirmation hash
            $this->User_model->updateConfirmationCode($valid['user_id'], $valid['confirmation_code']);

            $this->NotificationEmails->sendUserConfirmationEmail($valid);
            $this->session->set_flashdata('error', $valid['message']);
            if(isset($valid['user_id']) && isset($valid['confirmation_token'])){
                if (isset($valid['user_id']) && empty($valid['email_confirmed'])){
                    $this->session->set_flashdata('error', 'You must use the link on confirmation email to confirm email address. <a href="' . config_item('site_url') . 'accountRegister-resend-email?userId=' . $valid['user_id'] . '">Resend email</a>');
                    header("Location: signin");
                    exit;
                }
                header("Location: " . config_item('site_url') . "accountRegister-confirmation?register_confirm_token=" . $valid['confirmation_token']);
            } else {
                header("location: login");
            }
        } else {
            $user_id = $valid['user_id'];
            $data['user_detail'] = $this->User_model->get_by(array('id' => $user_id));
            Debugger::debug($data['user_detail']);
            $role_id = $data['user_detail']->role_id;
            $invite = $data['user_detail']->invite;
            $_SESSION['user_id'] = $data['user_detail']->id;
            $_SESSION['user_name'] = $data['user_detail']->first_name;
            $_SESSION['role_id'] = $data['user_detail']->role_id;
            $_SESSION['email'] = $data['user_detail']->email;
            $_SESSION['user_permissions'] = $this->RolePermissions_model->loadPermissions($data['user_detail']->role_id);
            $_SESSION['user_buying_clubs'] = $this->BuyingClub_model->loadUserClubs($user_id);
            $data['user_role'] = $this->Role_model->get($role_id);
            $_SESSION['user_role'] = $data['user_role'];

            // Debugger::debug($_SESSION);
            if (($role_id == "1" || $role_id == "2") && ! isset($_SESSION['user_name'])) {
                return;
            }
            elseif ($role_id == "11") {
                Debugger::debug($_SESSION, '$_SESSION');
                Debugger::debug(config_item('whitelabel'));
                $vendor_detail = $this->Vendor_groups_model->get_by(array('user_id' => $user_id));
                $vendor_id = $vendor_detail->vendor_id;
                Debugger::debug($vendor_id);

                if(!empty(config_item('whitelabel')) && config_item('whitelabel')->vendor_id != $vendor_id){
                    Debugger::debug(config_item('marketplaceDomain'));
                    header('Location: http://' . config_item('marketplaceDomain') . '/login');
                    exit;
                }
                $active = 1;
                if ($vendor_id != null) {
                    $vendorCompany = $this->Vendor_model->get($vendor_id);
                    $active = $vendorCompany->active;
                }
                if (isset($_SESSION['user_id']) && $active == "1") {
                    $_SESSION['vendor_id'] = $vendor_id;
                }
                else {
                    $this->session->set_flashdata('error', 'Please contact Matix admin.');
                    header('Location: login');
                }
            } elseif (($role_id > 2) && ($role_id < 11)) {
                if ((($role_id == 3) || ($role_id == 7)) && ($invite == 1)) {
                    $data['stepone'] = "";
                    $data['steptwo'] = "";
                    $data['stepthree'] = "";
                    $userLocation = $this->User_location_model->get_by(array('user_id' => $user_id));
                    if ($userLocation != null) {
                        $data['stepone'] = 1;
                        $data['steptwo'] = "";
                        $data['stepthree'] = 3;
                        $userPaymentDetails = $this->User_payment_option_model->get_by(array('user_id' => $user_id));
                        if ($userPaymentDetails == null) {
                            Debugger::debug('missing payment');
                            $this->session->set_flashdata('error', 'Please complete your registration - payment information required.');
                            header('Location: /accountRegister-stepOnePage?s=3');
                            exit;
                        }
                        elseif ( ! isset($_SESSION['user_id'])) {
                            return;
                        }
                    } else {
                        Debugger::debug('location missing');
                        $data['stepone'] = 1;
                        $data['steptwo'] = 2;
                        $this->session->set_flashdata('error', 'Please complete your registration - location information required.');
                        header('Location: /accountRegister-stepOnePage?s=2');
                        exit;
                    }
                } elseif ( ! isset($_SESSION['user_id'])) {
                    return;
                }
            } else {
                $data['organ_data'] = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
                $login_status = $data['user_detail']->login_status;
                if ($data['organ_data'] == null && $login_status == 0) {
                    $data['stepone'] = "";
                    $data['steptwo'] = "";
                    $data['stepthree'] = "";
                    $this->load->view('/templates/complete-registration/index', $data);
                } else {
                    $this->User_model->get_by(array('id' => $user_id));
                    if ( ! isset($_SESSION['user_id'])) {
                        return;
                    }
                }
            }

            // Post login redirects.
            if (in_array($role_id, [2, 3, 4, 7, 8, 9])) {
                header('Location: locations');
            }
            else if (in_array($role_id, [1])) {
                header('Location: vendorsIn-list');
            }
            else if (in_array($role_id, [11])) {
                header('Location: vendors-dashboard');
            }
            else {
               // header('Location: /');
            }

            exit;
                if (isset($_SESSION['user_id']) && $active == "1") {
                    $_SESSION['vendor_id'] = $vendor_id;
                }
        }
    */
    }

    public function loginSuperadmin($valid)
    {
        header('Location: vendorsIn-list');
        exit;
    }

    public function loginAdmin($valid)
    {
        // header("Location: signin");
        exit;
    }

    public function loginNormalUser($valid)
    {
        Debugger::debug('logging in normal user');
        // check if email confirmed
        Debugger::debug($valid);
        if(empty($valid['email_confirmed'])){
            $confirmationToken = $this->User_model->createConfirmationToken($valid['user_id']);
            $this->User_model->updateConfirmationCode($valid['user_id'], $confirmationToken);
            $this->NotificationEmails->sendUserConfirmationEmail($valid);
            $this->session->set_flashdata('error', 'You must use the link on confirmation email to confirm email address. <a href="' . config_item('site_url') . 'accountRegister-resend-email?userId=' . $valid['user_id'] . '">Resend email</a>');
            header("Location: signin");
            exit;
        }

        $userLocation = $this->User_location_model->get_many_by(array('user_id' => $valid['user_id']));
        Debugger::debug($userLocation, '$userLocation');
        if (empty($userLocation)){
            Debugger::debug('location missing');
            $data['stepone'] = 1;
            $data['steptwo'] = 2;
            $this->session->set_flashdata('error', 'Please complete your registration - location information required.');
            // header('Location: /accountRegister-stepOnePage?s=2');
            header('Location: /locations');
            exit;
        }

        $userPaymentDetails = $this->User_payment_option_model->get_many_by(array('user_id' => $valid['user_id']));

        Debugger::debug($userPaymentDetails, '$userPaymentDetails');

        if (empty($userPaymentDetails)) {
            Debugger::debug('missing payment');
            $this->session->set_flashdata('error', 'Please complete your registration - payment information required.');
            // header('Location: /accountRegister-stepOnePage?s=3');
            header('Location: /payments');
            exit;
        }

        header('Location: locations');
        exit;
    }

    public function loginVendor($valid)
    {
        Debugger::debug(config_item('whitelabel'), 'WHITELABEL');
        $vendor_detail = $this->Vendor_groups_model->get_by(array('user_id' => $valid['user_id']));

        $vendor_id = $vendor_detail->vendor_id;
        $_SESSION['vendor_id'] = $vendor_id;

        header('Location: vendors-dashboard');
        exit;
    }

    public function failedLogin()
    {
        $this->session->set_flashdata('error', 'Email or password incorrect.');
        header("Location: signin");
    }

    public function dashboard() {

        $user_id = $_SESSION['user_id'];
        $user_details = $this->User_model->get($user_id);
        $role_id = $user_details->role_id;
        if ($role_id == null) {
            $update_data = array(
                'login_status' => '0',
            );
        } else {
            $update_data = array(
                'login_status' => '1',
            );
        }
        $this->User_model->update($user_id, $update_data);
        unset($_SESSION['user_role']);
        unset($_SESSION['user_privilege']);
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['vendor_id']);
        $this->session->sess_destroy();
        header("Location: home");
    }

    public function forgot_password() {
        Debugger::debug('here');
        $accountEmail = $this->input->post('accountForgotEmail');
        $user = $this->User_model->get_by(array('email' => $accountEmail));


        if ($user == null) {
            $this->session->set_flashdata('error', 'E-mail doesn\'t Exist');
            header("Location: login");
            exit();
        } else {

            $passwordResetToken = md5(time() . microtime() . rand(0, 10000));

            $update_data = array(
                'reset_password_token' => $passwordResetToken,
                'updated_at' => date('Y-m-d H:i:s'),
            );
            Debugger::debug($update_data);
            if ($update_data != null) {
                $this->User_model->update($user->id, $update_data);
            }

            if ($user != null) {
                $subject = 'Reset Your Password';
                $message = "<div style='text-align: center;'>"
                        . "<hr style='width: 40px; color: #e8eaf1; background-color: #e8eaf1; border: 1px solid #e8eaf1;'>"
                        . "<br />"
                        . "Hi " . $user->first_name . ",<br />"
                        . "</div>"
                        . "<p style='color: #61646d; text-align: center; padding: 0 20px;'>You're receiving this email because we received a request to reset your password. Please click below to reset your password. If you didn't request a password reset and think that your account may be compromised, send us an email to <a href='mailto:support@matixdental.com'>support@matixdental.com</a></p><br />"
                        . "<a href='" . config_item('site_url') . "reset-password?reset_password_token=" . $passwordResetToken . "' style='padding: 20px 40px 20px 40px;width:auto;display: block;text-decoration: none;border:0;text-align: center;font-weight: bold;font-size: 16px;font-family: Arial, sans-serif;color: #ffffff;background: #2893FF;border: 1px solid #317ED0;-moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius: 4px;line-height:normal;' class='button_link'>Reset My Password</a>";
                $email_data = array(
                    'subject' => $subject,
                    'message' => $message
                );
                $body = $this->load->view('/templates/email/index.php', $email_data, TRUE);
                send_matix_email($body, $subject, $accountEmail);

                if($this->input->post('superAdmin') == true){
                    $this->session->set_flashdata('success', 'Password reset email sent');
                    header("Location: " . $_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_flashdata('success', 'Please check your email for reset password instructions');
                    header("location: signin");
                }
            } else {
                $this->session->set_flashdata('error', 'Forgot password page link not generated. Please try again.');
                header("location: signin");
            }
        }
    }

    public function reset_password_page() {

        $token = $this->input->get('reset_password_token');
        $data['user_token'] = $this->User_model->get_by(array('reset_password_token' => $token));
        if ($data['user_token'] != null) {
            $this->load->view('/templates/password/index.php', $data);
        } else {
            $this->session->set_flashdata('error', 'Reset password token invalid. Please try again.');
            header('Location: user-loginpage');
        }
    }

    public function change_user_password() {
        $user_id = $this->input->post('id');
        $token = $this->input->post('reset_token');
        $password = $this->input->post('password');
        $confirm_password = $this->input->post('passwordAgain');
        $user_detail = $this->User_model->get($user_id);

        Debugger::debug($user_detail);

        $old_token = false;
        if ($user_detail != null) {
            $old_token = $user_detail->reset_password_token;
        }
        if ($password != $confirm_password) {
            $this->session->set_flashdata('error', 'Password and password confirmation does not match.');
        } else if ($token == $old_token) {
            $user_id = $user_detail->id;
            $update_data = array(
                'reset_password_token' => "",
                'new_password' => $this->auth->hashPassword($password),
                'status' => '1',
                'password_last_updated_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $this->User_model->update($user_id, $update_data);
            // check signup completed
            if(!empty($user_detail->confirmation_token)){
                $this->session->set_flashdata('success', 'Password updated.  Please complete signup.');
                header("Location: " . config_item('site_url') . "accountRegister-confirmation?register_confirm_token=" . $user_detail->confirmation_token);
                exit;
            }

            // log in user
            $_SESSION['user_id'] = $user_detail->id;
            $_SESSION['user_name'] = $user_detail->first_name;
            $_SESSION['role_id'] = $user_detail->role_id;
            $_SESSION['email'] = $user_detail->email;
            $_SESSION['user_permissions'] = $this->RolePermissions_model->loadPermissions($user_detail->role_id);
            $_SESSION['user_buying_clubs'] = $this->BuyingClub_model->loadUserClubs($user_detail->id);
            $_SESSION['user_role'] = $this->Role_model->get($user_detail->role_id);

            $this->session->set_flashdata('success', 'Password updated.');
            header('Location: home');
        }
        else {
            $this->session->set_flashdata('error', 'Password not updated. Please sign in.');
            header('Location: user-loginpage');
        }
    }

    public function update_password() {
        $user_id = $this->input->get('user_id');
        if ($user_id != null) {
            $data['user_detail'] = $this->User_model->get($user_id);
            $this->render('home', 'full_width');
            $this->load->view('user/change_password_page.php', $data);
        }
    }

    public function updatepassword_reset() {
        $user_id = $this->input->post('id');
        $current_password = $this->input->post('currentpassword');
        $new_password = $this->input->post('newpassword');
        $confirm_password = $this->input->post('confirmpassword');
        if ($user_id != null) {
            $user_detail = $this->User_model->get($user_id);
            $old_password = $user_detail->password;
            if ($new_password != $confirm_password) {
                $this->session->set_flashdata('error', 'Password and password confirmation does not match.');
                header('Location: user-change-password?user_id=' . $user_id);
            } elseif ($this->auth->verifyPassword($new_pasword, $old_password) || $this->auth->verifyNewPassword($new_pasword, $old_password)) {
                $this->session->set_flashdata('error', 'Current password and new password cannot be the same.');
                header('Location: user-change-password?user_id=' . $user_id);
            } else {
                $update_data = array(
                    'new_password' => $this->auth->hashPassword($new_password),
                    'password_last_updated_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                $this->User_model->update($user_id, $update_data);
                $this->session->set_flashdata('success', 'Password changed successfully. Redirecting to login.');
                header('Location: user-loginpage');
            }
        }
    }

    public function user_logOut() {
        unset($_SESSION['user_role']);
        unset($_SESSION['user_privilege']);
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['vendor_id']);
        $this->session->sess_destroy();
        if (!(isset($_SESSION['user_id'])) || $_SESSION['user_id'] == '') {
            header('Location: login');
        }
    }

    public function Manage_users() {
        $roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles))) {
            $user_id = $_SESSION['user_id'];
            $data['location_id'] = $this->User_location_model->get_many_by(array('user_id' => $user_id));
            for ($i = 0; $i < count($data['location_id']); $i++) {

                $data['user_locations'][] = $this->Organization_location_model->get_by(array('id' => $data['location_id'][$i]->organization_location_id));
            }
            if (isset($_SESSION['location_id'])) {
                $location_id = $_SESSION['location_id'];
            } else {
                $location_id = "all";
            }
            if ($user_id != null) {
                $order_options = array("name" => "b.first_name", "state" => " e.state ", "spend" => "spend_total");
                $order_by = $this->input->post("order");
                if (isset($order_by) && $order_by != "") {
                    $order = $order_options[$order_by];
                    $data['order_by'] = $order_by;
                } else {
                    $order = " b.first_name ";
                    $data['order_by'] = "name";
                }

                $organization = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
                if ($organization != null) {
                    $organization_id = $organization->organization_id;
                }
                if ($organization_id != null) {
                    //$data['user_locations'] = $this->Organization_location_model->order_by('id', 'desc')->get_many_by(array('organization_id' => $organization_id));
                    // $query = "SELECT b.role_id,b.id,b.email,b.first_name,c.role_name,b.status,b.created_at,d.organization_location_id,e.nickname,count(b.id) as count  FROM organization_groups a INNER JOIN users b on b.id=a.user_id INNER JOIN roles c on c.id=b.role_id LEFT JOIN user_locations d on d.user_id=b.id LEFT JOIN organization_locations e on e.id=d.organization_location_id where a.organization_id=$organization_id group by b.id";
                    if ($location_id == "all") {
                        if ($order == "spend_total") {
                            $query = "SELECT sum(o.total) as spend_total, e.state, b.role_id,b.id,b.email,b.first_name,c.role_name,b.status,b.created_at,d.organization_location_id,e.nickname,f.photo,count(b.id) as count FROM organization_groups a INNER JOIN users b on b.id=a.user_id INNER JOIN roles c on c.id=b.role_id LEFT JOIN user_locations d on d.user_id=b.id LEFT JOIN organization_locations e on e.id=d.organization_location_id LEFT JOIN images f on b.id = f.model_id and f.model_name='user' left join orders o on f.model_id=o.user_id and o.order_status != 'Cancelled' and o.restricted_order ='0' where a.organization_id= $organization_id group by b.id order by $order desc ";
                        } else {
                            $query = "SELECT e.state, b.role_id,b.id,b.email,b.first_name,c.role_name,b.status,b.created_at,d.organization_location_id,e.nickname,f.photo,count(b.id) as count FROM organization_groups a INNER JOIN users b on b.id=a.user_id INNER JOIN roles c on c.id=b.role_id LEFT JOIN user_locations d on d.user_id=b.id LEFT JOIN organization_locations e on e.id=d.organization_location_id LEFT JOIN images f on b.id = f.model_id and f.model_name='user' where a.organization_id= $organization_id group by b.id order by $order ";
                        }
                    } else {
                        if ($order == "spend_total") {
                            $query = "SELECT sum(o.total) as spend_total, e.state, b.role_id,b.id,b.email,b.first_name,c.role_name,b.status,b.created_at,d.organization_location_id,e.nickname,f.photo,count(b.id) as count FROM organization_groups a INNER JOIN users b on b.id=a.user_id INNER JOIN roles c on c.id=b.role_id LEFT JOIN user_locations d on d.user_id=b.id INNER JOIN organization_locations e on e.id=" . $location_id . " LEFT JOIN images f on b.id = f.model_id and f.model_name='user' left join orders o on f.model_id=o.user_id and o.order_status != 'Cancelled' and o.restricted_order ='0' where a.organization_id=$organization_id group by b.id order by $order desc";
                        } else {
                            $query = "SELECT e.state, b.role_id,b.id,b.email,b.first_name,c.role_name,b.status,b.created_at,d.organization_location_id,e.nickname,f.photo,count(b.id) as count FROM organization_groups a INNER JOIN users b on b.id=a.user_id INNER JOIN roles c on c.id=b.role_id LEFT JOIN user_locations d on d.user_id=b.id INNER JOIN organization_locations e on e.id=" . $location_id . " LEFT JOIN images f on b.id = f.model_id and f.model_name='user' where a.organization_id=$organization_id group by b.id order by $order ";
                        }
                    }

                    $data['user_details'] = $this->db->query($query)->result();
                }
            }
            $data['organization_id'] = $organization_id;
            $data['organization_role_id'] = $_SESSION['role_id'];
            $data['Manage_usersPage'] = 1;
            $this->load->view('/templates/_inc/header', $data);
            $this->load->view('/templates/account/users/index.php', $data);
            $this->load->view('/templates/_inc/footer');
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function Invite_users() {
        if (isset($_SESSION['user_id'])) {
            $accountEmail = $this->input->post('accountEmail');
            $accountName = $this->input->post('accountName');
            $role_id = $this->input->post('role_id');
            $organization_id = $this->input->post('organization_id');
            $location_id = $this->input->post('location_id');
            $invite_sender = $_SESSION['user_name'];
            $email_check = $this->User_model->get_by(array('email' => $accountEmail));
            if ($email_check != null) {
                $this->session->set_flashdata('error', 'Email already exists. Please try again.');
                header("location: Manage-Users");
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
                    if ($user_id != null) {
                        $insertInto_group = array(
                            'user_id' => $user_id,
                            'organization_id' => $organization_id,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        );
                        $this->Organization_groups_model->insert($insertInto_group);

                        $insert_location = array(
                            'user_id' => $user_id,
                            'organization_location_id' => $location_id,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        );

                        $this->User_location_model->insert($insert_location);
                    }
                    $data['roles'] = $this->Role_model->get($role_id);
                    $role_name = $data['roles']->role_name;
                    $subject = 'Activate Your Account';
                    $message = "<div style='text-align: center;'>"
                            . "<hr style='width: 40px; color: #e8eaf1; background-color: #e8eaf1; border: 1px solid #e8eaf1;'>"
                            . "<br />"
                            . "Hi " . $accountEmail . ",<br />"
                            . "</div>"
                            . "<p style='color: #61646d; text-align: center; padding: 0 20px;'>" . $invite_sender . " has invited you to join their organization as a \"". $role_name . "\". Click below to activate your account and set a password.</p><br/>"


                            // . "<table cellpadding='0' cellspacing='0' style='border: 1px solid #d8d8d8; width: 100%; padding: 12px 16px; -moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius: 4px;' class='100p'>"

                            //     . "<tr>"
                            //         . "<td style='color: #2893FF;'>"
                            //             . $accountEmail
                            //         . "</td>"
                            //         . "<td style='text-align: right;'>"
                            //             . $password
                            //         . "</td>"
                            //     . "</tr>"

                            // . "</table>"

                            . "<br/>"

                            . "<a href='" . config_item('site_url') . "user-create-password?register_confirm_token=" . $register_confirm_token . "' style='padding: 20px 40px 20px 40px;width:auto;display: block;text-decoration: none;border:0;text-align: center;font-weight: bold;font-size: 16px;font-family: Arial, sans-serif;color: #ffffff;background: #2893FF;border: 1px solid #317ED0;-moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius: 4px;line-height:normal;' class='button_link'>Activate My Account</a>"

                            . "<p style='font-size: 12px; color: #BEBEBE; text-align: center; padding: 0 20px;'>You can change your password after logging in by navigating to \"My Account > Dashboard >\" and clicking \"Change Your Password\".</p><br/>";

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

                // Date: 3/17/2017 To load the DEFINED page. (dashboard/Manage-users)

                $Manage_page = $this->input->post('Manage_page');
                if ($Manage_page == 1) {
                    $this->session->set_flashdata('success', 'Invitation is sent to user.');
                    header('Location: Manage-Users');
                } else {
                    $this->session->set_flashdata('success', 'Invitation is sent to user.');
                    header('Location: dashboard');
                }
            }
        } else {
            header("Location:home");
        }
    }


    public function newAccountCreatePassword()
    {
        // check for token
        if(!empty($this->input->get('register_confirm_token'))) {
            Debugger::debug('test token');
            if(!$data['user_token'] = $this->User_model->get_by(array('confirmation_token' => $this->input->get('register_confirm_token')))){
                Debugger::debug('user not found');
                $this->session->set_flashdata('error', 'Invalid token.');
                header('Location: home');
            } else {
                Debugger::debug($data);
                //login and show password change form
                $this->session->set_flashdata('success', 'Please set a password for your account.');
            $this->load->view('/templates/password/index.php', $data);


            }
        } else if(isset($_SESSION['user_id'])){
        // validate and save pasword

        // wipe confirmation token

        // set flashdata

        } else {
        // redirect to home
            header('Location: home');
        }
    }

    public function update_OrganizationUsers() {
        if (isset($_SESSION['user_id'])) {
            $user_id = $this->input->post('user_id');
            if ($user_id != null) {
                $update_data = array(
                    'first_name' => $this->input->post('accountName'),
                    'role_id' => $this->input->post('role_id'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                if ($update_data != null) {
                    $this->User_model->update($user_id, $update_data);
                    $this->session->set_flashdata('success', 'User details updated.');
                    header('Location: Manage-Users');
                }
                $this->session->set_flashdata('success', 'User details updated.');
                header('Location: Manage-Users');
            }
            $this->session->set_flashdata('success', 'User details updated.');
            header('Location: Manage-Users');
        }
    }

    public function can($userPermissions, $permissionCode)
    {
        // check if user has permission to do task
        foreach($userPermissions as $permission){
            if($permission->permission_code == $permissionCode && $permission->value == 1){
                return true;
            }
        }

        return false;
    }

    public function activateAccount()
    {
        // validate token

        // display password change form
    }

}

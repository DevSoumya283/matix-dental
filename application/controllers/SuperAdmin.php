<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SuperAdmin extends MW_Controller {

    function __construct() {

        parent::__construct();
        $this->load->helper('MY_privilege_helper');
        $this->load->model('Order_model');
        $this->load->model('Role_model');
        $this->load->model('User_model');
        $this->load->model('Vendor_model');
        $this->load->model('Vendor_groups_model');
        $this->load->library('email'); // load email library
        $this->load->library('auth');
        $this->load->helper('my_email_helper');
    }

    public function vendorUser_action() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $select = $this->input->post('select');
            $user_ids = explode(',', $this->input->post('user_id'));
            $user = $user_ids[0];
            if ($user != null) {
                $vendor_groups = $this->Vendor_groups_model->get_by(array('user_id' => $user));
            }
            $vendor_id = $vendor_groups->vendor_id;
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
            header("Location: vendors-sales-report?vendor_id=" . $vendor_id);
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function addNew_vendor() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $vendor_id = $this->input->post('vendor_id');
            $vendor_detail = $this->Vendor_model->get($vendor_id);
            $userName = $this->input->post('accountName');
            $accountEmail = $this->input->post('accountEmail');
            $role_id = $this->input->post('role_id');
            $role_details = $this->Role_model->get($role_id);
            $role_name = $role_details->role_name;
            $email_check = $this->User_model->get_by(array('email' => $accountEmail));
            if ($email_check != null) {
                $this->session->set_flashdata('error', 'Email already exists. Please try again.');
                //header("location: view-vendors");
                header("location: vendors-sales-report?vendor_id=" . $vendor_id);
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
                    'first_name' => $userName,
                    'email' => $accountEmail,
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
                    $subject = 'Invitation from the DentoMatix Admin';
                    $message = "Hi,<br />Welcome to the Matix marketplace. Please click below to confirm your account email address. "
                            . "<table>"
                            . "<tr>"
                            . "<td><b>Email :</b></td><td>$accountEmail</td>"
                            . "</tr>"
                            . "<tr>"
                            . "<td><b>Password :</b></td><td>$password</td>"
                            . "</tr>"
                            . "</table>"
                            . "Login with the above details as a Vendor in Dentomatix.<br /> <b>Note:</b> Please change the Auto Generated Password once logged In"
                            . "<a href='" . config_item('site_url') . "user-registration-page?register_confirm_token=" . $register_confirm_token . "' style='padding: 20px 40px 20px 40px;width:auto;display: block;text-decoration: none;border:0;text-align: center;font-weight: bold;font-size: 16px;font-family: Arial, sans-serif;color: #ffffff;background: #2893FF;border: 1px solid #317ED0;-moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius: 4px;line-height:normal;' class='button_link'>Confirm</a>";
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
                    $this->session->set_flashdata('success', 'New user created. Verification email sent.');
                    header("location: vendors-sales-report?vendor_id=" . $vendor_id);
                }
                header("location: vendors-sales-report?vendor_id=" . $vendor_id);
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function deleteVendor_user() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $vendor_id = $this->input->post('vendor_id');
            $delete_id = explode(",", $this->input->post('user_id'));
            for ($i = 0; $i < count($delete_id); $i++) {
                $vendor_detail = $this->Vendor_groups_model->get_by(array('user_id' => $delete_id[$i]));
                $vendor_delete_id = $vendor_detail->id;
                $this->Vendor_groups_model->delete($vendor_delete_id);
            }
            $this->User_model->delete_many($delete_id);
            $this->session->set_flashdata('success', 'The vendor user is deleted');
            header("Location: vendors-sales-report?vendor_id=$vendor_id");
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

}

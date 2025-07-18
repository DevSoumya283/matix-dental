<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CreateUser extends MW_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Organization_groups_model');
        $this->load->model('Organization_location_model');
        $this->load->model('Organization_model');
        $this->load->model('Role_model');
        $this->load->model('User_licenses_model');
        $this->load->model('User_location_model');
        $this->load->model('User_model');
        $this->load->model('User_payment_option_model');
        $this->load->model('Vendor_groups_model');
        $this->load->model('NotificationEmails');
        $this->load->library('auth');
        $this->load->helper('my_email_helper');
        $this->load->helper('MY_privilege_helper');
    }

    //      Creating a New Account.
    public function newAccount() {
        $email = $this->input->post('accountNewEmail');
        $password = $this->input->post('password');
        $confirmpassword = $this->input->post('passwordAgain');


        $confirmationToken = $this->User_model->createConfirmationToken($email);

        if ($password == $confirmpassword) {
            $insert_data = [
                'email' => $email,
                'new_password' => $this->auth->hashPassword($password),
                'confirmation_token' => $confirmationToken,
                'invite' => '1'
            ];
            $accountEmail = $insert_data['email'];
            $email_check = $this->User_model->email_check($email);
            if ($email_check == 1) {
                $this->session->set_flashdata('error', 'Email already exists. Please try again.');
                header("location: user-loginpage");
            } else {
                if ($insert_data != null) {
                    $this->User_model->insert($insert_data);
                    $_SESSION['confirmation_token'] = $confirm_password_reset_token;

                    // email user confirmation
                    $this->NotificationEmails->sendUserConfirmationEmail($insert_data);
                }
                $this->session->set_flashdata('success', 'Confirmation is generated. Please check your email.');
                header("location: user-loginpage");
            }
        } else {
            $this->session->set_flashdata('error', 'Registration confirmation page is not  generated. Please Try again');
            header("location: user-loginpage");
        }
        header("location: user-loginpage");
    }

    public function resendEmail()
    {
        $user = $this->User_model->get_by(array('id' => $this->input->get('userId')));
        Debugger::debug($user);
        $userData = [
            'email' => $user->email,
            'confirmation_token' => $user->confirmation_token
        ];

        $this->NotificationEmails->sendUserConfirmationEmail($userData);
        $this->session->set_flashdata('success', 'Confirmation is generated. Please check your email.');
                header("location: user-loginpage");
    }


    //  Account Conformation
    public function confirmEmail() {
        $confirmation_token = $this->input->get('register_confirm_token');
        $user_detail = $this->User_model->get_by(array('confirmation_token' => $confirmation_token));
        Debugger::debug($_GET);
        Debugger::debug($user_detail);
        if ($user_detail != null) {
            $user_id = $user_detail->id;
            $_SESSION['user_id'] = $user_id;
        }
        if (!empty($confirmation_token)) {
            $this->User_model->update($user_id, ['email_confirmed' => 1]);
            if($user_id != null) {
                $this->session->set_flashdata('success', 'Please complete the registration');
                header("Location: accountRegister-stepOnePage");
            } else {
                $this->session->set_flashdata('error', 'That confirmation code couild not be found.');
                header("Location: signin");
            }
        } else {
            $this->session->set_flashdata('error', 'Your account is not approved. Please try again');
            header("Location: signin");
        }
    }

    // Registration Flow.
    public function accountDetails() {
        /*
         * The #stepone,#steptwo and #stepthree defines where the user canceled(CANCEL) the Registration details and
         * continue from there once again logged into dentomatix.
         */
        $user_id = $_SESSION['user_id'];
        Debugger::debug($user_id);
        $data['userDetails'] = $this->User_model->get_by(array('id' => $user_id));
        $data['userDetails']->organization = $this->Organization_model->get_by(['admin_user_id' => $user_id]);
        $data['userDetails']->license = $this->User_licenses_model->get_by(['user_id' => $user_id]);
        $data['userDetails']->location = $this->Organization_location_model->get_by(['organization_id' => $data['userDetails']->organization->id]);
        Debugger::debug($data['userDetails']);
        Debugger::debug($data['userDetails']->organization->id);
        if ($user_id != null) {
            $data['stepone'] = "";
            $data['steptwo'] = "";
            $data['stepthree'] = "";
            $this->load->view('/templates/complete-registration/index', $data);
        } else {
            $this->session->set_flashdata('error', 'Session expired, please restart signup from email.');
            header("Location: signin");
        }
    }

    // Collecting User data.
    public function submitDetailsLicense() {
        /*
         *  From here,
          1.user  2.User_licenses 3.Organizations 4. Organization_groups
         * The information about the registering users are stored in the above tables.
         */
        Debugger::debug($_POST);

        if (isset($_SESSION['user_id'])) {
            Debugger::debug($_SESSION);
            $user_id = $_SESSION['user_id'];
            $userTb_details = array(
                'first_name' => $this->input->post('accountName'),
                'salutation' => $this->input->post('accountTitle'),
                'phone1' => $this->input->post('accountPhone'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            if ($userTb_details != null) {
                $_SESSION['user_name'] = $this->input->post('accountName');
                $this->User_model->update($user_id, $userTb_details);
            }
            $license_select = $this->input->post('license_select');
            if ($license_select != null) {
                $userLicenceTb = array(
                    'license_no' => $this->input->post('accountLicense'),
                    'dea_no' => $this->input->post('accountDEA'),
                    'expire_date' => date('Y-m-d', strtotime($this->input->post('licenseExpiry'))),
                    'state' => $this->input->post('LicensedState'),
                    'user_id' => $user_id,
                    'approved' => '0',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                if ($userLicenceTb != null) {
                    if(!empty($this->input->post('license_id'))){
                        // $userLicenceTb['id'] =  $this->input->post('license_id');
                        $licens_id = $this->User_licenses_model->update($this->input->post('license_id'), $userLicenceTb);
                        $action = "updated";
                    } else {
                        $licens_id = $this->User_licenses_model->insert($userLicenceTb);
                        $action = 'created';
                    }
                    // notify admin
                    $this->NotificationEmails->sendUserLicenseAddedToAdmin($action, $userLicenceTb['user_id'], $userLicenceTb['license_no'], $userLicenceTb['state'], $userLicenceTb['expire_date'], $userLicenceTb['dea_no']);
                }
            }
            $orgType = $this->input->post('orgType');
            if ($orgType != null) {
                if ($orgType == 1) {
                    $insert_org = array(
                        'organization_name' => $this->input->post('companyName'),
                        'tax_id' => $this->input->post('companyTaxID'),
                        'organization_type' => $orgType,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    // RoleId= 3    CORPORATE ADMIN
                    $update_role = array(
                        'role_id' => '3',
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    $this->User_model->update($user_id, $update_role);
                } elseif ($orgType == 2) {
                    $insert_org = array(
                        'organization_name' => $this->input->post('schoolName'),
                        'tax_id' => $this->input->post('schoolTaxID'),
                        'organization_type' => $orgType,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    $update_role = array(
                        'role_id' => '7',
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    $this->User_model->update($user_id, $update_role);
                }
                if(!empty($this->input->post('orgId'))){
                    $this->Organization_model->update($this->input->post('orgId'), $insert_org);
                    $organization_id = $this->input->post('orgId');
                } else {
                    $organization_id = $this->Organization_model->insert($insert_org);
                }
                if ($organization_id != null) {
                    // Update: The user will be Admin for This Organization. Date: 05/09/2017
                    $update_org = array(
                        'admin_user_id' => $user_id,
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    $this->Organization_model->update($organization_id, $update_org);
                    $insertOrg_grp = array(
                        'user_id' => $user_id,
                        'organization_id' => $organization_id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    $this->Organization_groups_model->insert($insertOrg_grp);
                    if ($licens_id != null) {
                        $update_license = array(
                            'organization_id' => $organization_id,
                            'updated_at' => date('Y-m-d H:i:s'),
                        );
                        $this->User_licenses_model->update($licens_id, $update_license);
                    }
                }
            }
        }
    }

    // Organization Location details
    public function submitLocationInfo() {
        /*
         *   From here, Organization's location are stored in Organization_location table.
         *    To singular the Organization details we are storing user_id and Organization_location_id in User_location table.
         */

        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $user_roles = $this->User_model->get($user_id);
            if ($user_roles != null) {
                $_SESSION['role_id'] = $user_roles->role_id;
            }
            $user_details = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
            if ($user_details != null) {
                $organization_id = $user_details->organization_id;
            }


            $user_location = array(
                'organization_id' => $organization_id,
                'nickname' => $this->input->post('locationName'),
                'address1' => $this->input->post('companyAddress1'),
                'address2' => $this->input->post('companyAddress2'),
                'city' => $this->input->post('companyCity'),
                'zip' => $this->input->post('companyZip'),
                'state' => $this->input->post('state'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            if ($user_location != null) {
                Debugger::debug('load location');
                Debugger::debug($_POST);
                Debugger::debug($this->input->post('location_id'));
                if(!empty($this->input->post('location_id'))){
                    // $userLicenceTb['id'] =  $this->input->post('license_id');
                    $this->Organization_location_model->update($this->input->post('location_id'), $user_location);
                    $action = "updated";
                    $location_id = $this->input->post('location_id');
                } else {
                    $location_id = $this->Organization_location_model->insert($user_location);
                    $action = 'created';
                }
                // $location_id = $this->Organization_location_model->insert($user_location);
                Debugger::debug($location_id, '$location_id');
                if ($location_id != null) {
                    $insert = array(
                        'user_id' => $_SESSION['user_id'],
                        'organization_location_id' => $location_id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    if ($insert != null) {
                        if(!empty($this->input->post('location_id'))){
                            $this->User_location_model->update($insert);
                        } else {
                            $this->User_location_model->insert($insert);
                        }
                        // $update_data = array(
                        //     'confirmation_token' => "",
                        //     'status' => '1',
                        //     'updated_at' => date('Y-m-d H:i:s'),
                        // );
                        // $this->User_model->update($user_id, $update_data);
                    }
                }
            }
        }
    }

    // card details
    public function submitCardDetails() {
        if (isset($_SESSION['user_id'])) {
            $card_details = array(
                'user_id' => $_SESSION['user_id'],
                'payment_type' => $this->input->post('payment_type'),
                'cc_number' => $this->input->post('paymentCardNum'),
                'cc_name' => $this->input->post('paymentCardName'),
                'exp_date' => $this->input->post('paymentExpiry'),
                'cc_code' => $this->input->post('paymentSecurity'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            if ($card_details != null) {
                $this->User_payment_option_model->insert($card_details);
                $update_data = array(
                    'confirmation_token' => "",
                    'status' => '1',
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                $this->User_model->update($_SESSION['user_id'], $update_data);
            }
        }
    }

    // Bank details
    public function submitBankDetails() {
        if (isset($_SESSION['user_id'])) {
            $bank_details = array(
                'user_id' => $_SESSION['user_id'],
                'payment_type' => $this->input->post('payment_type'),
                'bank_name' => $this->input->post('paymentBankName'),
                'ba_account_number' => $this->input->post('paymentAccountNum'),
                'ba_routing_number' => $this->input->post('paymentRoutingNum'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            if ($bank_details != null) {
                $this->User_payment_option_model->insert($bank_details);
                $update_data = array(
                    'confirmation_token' => "",
                    'status' => '1',
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                $this->User_model->update($_SESSION['user_id'], $update_data);
            }
        }
    }

    // End of Registration process and taking to the Browse Page with user details on SESSION.
    public function user_browserPage() {
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $data['user_detail'] = $this->User_model->get($user_id);
            $role_id = $data['user_detail']->role_id;
            $_SESSION['user_id'] = $data['user_detail']->id;
            $_SESSION['user_name'] = $data['user_detail']->first_name;
            $_SESSION['role_id'] = $data['user_detail']->role_id;
            $this->load->view('/templates/_inc/header');
            $this->load->view('/templates/browse/index', $data);
            $this->load->view('/templates/_inc/footer');
        }
    }

    public function setSignupComplete()
    {
        $update_data = array(
            'confirmation_token' => "",
            'status' => '1',
            'updated_at' => date('Y-m-d H:i:s'),
        );
        $this->User_model->update($_SESSION['user_id'], $update_data);
    }

    //  Pending User will be deleted from the Superadmin/Admin from the SuperAdmin Dashboard(Pending).
    public function deletePendingUser() {
        if (isset($_SESSION['user_id'])) {
            $delete_id = explode(",", $this->input->post('user_id'));
            if ($delete_id != null) {
                for ($i = 0; $i < count($delete_id); $i++) {
                    $this->User_licenses_model->delete_by(array('user_id' => $delete_id[$i], 'approved' => '0'));
                }
            }
            $this->session->set_flashdata('success', 'License is deleted');
            header("Location: customerSection-accept-customers");
        }
    }

    //  Pending User will be Denied from the Superadmin/Admin from the SuperAdmin Dashboard(Pending)(SELECT).
    public function deny_pending_user() {
        if (isset($_SESSION['user_id'])) {
            $delete_id = explode(",", $this->input->post('user_id'));
            if ($delete_id != null) {
                for ($i = 0; $i < count($delete_id); $i++) {
                    $update_data = array(
                        'approved' => '-1',
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    if ($update_data != null) {
                        $this->session->set_flashdata('error', 'License denied. Notification email sent.');
                        $result = $this->User_licenses_model->update_by(array('user_id' => $delete_id[$i], 'approved' => '0'), $update_data);
                        if ($result != null) {
                            $userLicenses = $this->User_licenses_model->get_many_by(array('user_id' => $delete_id[$i]));
                            $user_details = $this->User_model->get($delete_id[$i]);
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
                            $email_data = array(
                                'subject' => $subject,
                                'message' => $message
                            );
                            $body = $this->load->view('/templates/email/index.php', $email_data, TRUE);
                            $mail_status = send_matix_email($body, $subject, $accountEmail);
                        }
                    }
                }
            }
            header("Location: customerSection-accept-customers");
        }
    }

    //Superadmin/Admin will approve All the License of Pending users
    public function approvePendingUser() {
        if (isset($_SESSION['user_id'])) {
            $delete_id = explode(",", $this->input->post('user_id'));
            if ($delete_id != null) {
                for ($i = 0; $i < count($delete_id); $i++) {
                    $update_data = array(
                        'approved' => '1',
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    if ($update_data != null) {
                        $this->session->set_flashdata('success', 'License approved. Notification email sent.');
                        $result = $this->User_licenses_model->update_by(array('user_id' => $delete_id[$i]), $update_data);
                        if ($result != null) {
                            $userLicenses = $this->User_licenses_model->get_many_by(array('user_id' => $delete_id[$i], 'approved' => '1'));
                            $user_details = $this->User_model->get($delete_id[$i]);
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
                                $email_data = array(
                                    'subject' => $subject,
                                    'message' => $message
                                );
                                $body = $this->load->view('/templates/email/index.php', $email_data, TRUE);
                                $mail_status = send_matix_email($body, $subject, $accountEmail);
                            }
                        }
                    }
                }
            }
            $this->session->set_flashdata('success', 'Selected users are approved. Email sent.');
            header("Location: customerSection-accept-customers");
        }
    }

    public function update_spvendorUser() {
        if (isset($_SESSION['user_id']) != null) {

            $user_id = $this->input->post('vendor_user_id');
            $vendor_groups = $this->Vendor_groups_model->get_by(array('user_id' => $user_id));
            $vendor_id = $vendor_groups->vendor_id;
            if ($user_id != null) {
                $update_data = array(
                    'role_id' => $this->input->post('vendor_role'),
                    'first_name' => $this->input->post('accountName'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                if ($update_data != null) {
                    $this->User_model->update($user_id, $update_data);
                    $this->session->set_flashdata('success', 'Vendor Information updated successfully.');
                    header("Location: vendors-sales-report?vendor_id=" . $vendor_id);
                }
            }
        }
    }

}

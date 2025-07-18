<?php
defined('BASEPATH') OR exit('No direct script access allowed.');

Class NotificationEmails extends MY_Model
{
    public function sendUserConfirmationEmail($userData)
    {
        // Debugger::debug($userData);
        $subject = 'Activate Your Account';
        $message = "<div style='text-align: center;'>"
                . "<hr style='width: 40px; color: #e8eaf1; background-color: #e8eaf1; border: 1px solid #e8eaf1;'>"
                . "<br />"
                . "Hi " . $userData['email'] . ",<br />"
                . "</div>"
                . "<p style='color: #61646d; text-align: center; padding: 0 20px;'>Thank you for registering your account. Please click below to activate your account and start shopping with Matix Dental.</p><br/>"
                . "<a href=' " . config_item('site_url') . "accountRegister-confirmation?register_confirm_token=" . $userData['confirmation_token'] . "' style='padding: 20px 40px 20px 40px;width:auto;display: block;text-decoration: none;border:0;text-align: center;font-weight: bold;font-size: 16px;font-family: Arial, sans-serif;color: #ffffff;background: #2893FF;border: 1px solid #317ED0;-moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius: 4px;line-height:normal;' class='button_link'>Activate My Account</a>";

                // Debugger::debug($message);
        $email_data = array(
            'subject' => $subject,
            'message' => $message
        );
        $body = $this->load->view('/templates/email/index.php', $email_data, TRUE);
        send_matix_email($body, $subject, $userData['email']);
    }

    public function sendUserLicenseAddedToAdmin($action, $userId, $licenseNo, $state, $expDate, $deaNo)
    {
        $userDetails = $this->User_model->get_by(array('id' => $userId));

        $query_admin_users = "select * from users where role_id=1 or role_id=2";
        $admin = $this->db->query($query_admin_users)->result();
        for ($i = 0; $i < count($admin); $i++) { //send email to superadmin for licence verification
            $email = $admin[$i]->email;
            $subject = 'Purchasing Power Approval Request';
            $message = "Hi, <br />"
                    . "A new license was " . $action . " by " . $_SESSION['user_name'] . " <br>Details below:"
                    . "<table cellpadding='0' cellspacing='0' border='0' width='100' style='width: 300px; padding:5px; background-color:#ffffff; border-bottom:1px solid #E8EAF1; border-top:4px solid #13C4A3;' class='100p'>"
                    . "<tr style='width: 100px;'><td>License Number:</td><td>$licenseNo</td></tr>"
                    . "<tr style='width: 100px;'><td>State</td><td>$state</td></tr>"
                    . "<tr style='width: 100px;'><td>Exp</td><td>$expDate</td></tr>"
                    . "<tr style='width: 100px;'><td>DEA Number</td><td>$deaNo</td></tr></table><br>"
                    . "<a href='" . config_item('site_url') . "customer-details-page?user_id=" . $userDetails->id . "' style='padding: 20px 40px 20px 40px;width:auto;display: block;text-decoration: none;border:0;text-align: center;font-weight: bold;font-size: 16px;font-family: Arial, sans-serif;color: #ffffff;background: #2893FF;border: 1px solid #317ED0;-moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius: 4px;line-height:normal;' class='button_link'>View License</a>";
            $email_data = array(
                'subject' => $subject,
                'message' => $message
            );
            $email_data = array(
                'subject' => $subject,
                'message' => $message
            );
            $body = $this->load->view('/templates/email/index.php', $email_data, TRUE);
            $mail_status = send_matix_email($body, $subject, $email);
        }
    }
}

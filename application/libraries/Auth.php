<?php

/**
 * Login in password management
 */
class auth {

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('User_model');
    }

    public function login($username, $password)
    {
        Debugger::debug($username . ' - ' . $password, 'Logging in');
        // load user
        if ($user = $this->CI->User_model->get_by(['email' => $username])) {
            // user found
            Debugger::debug($user);
            if (empty($user->new_password)) {
                Debugger::debug('has no new password');

                // no new password, check old password valid
                if ($this->verifyPassword($password, $user->password)) {
                    // valid password
                    $result['status'] = "0";
                    // $result['user_id'] = $user->id;
                    $this->sendPasswordResetEmail($user);
                } else {
                    // fail
                    $result['status'] = "0";
                    $result['message'] = "Incorrect Password or Email";
                }
            } else {
                Debugger::debug('has new password');

                if ($this->verifyNewPassword($password, $user->new_password)) {
                    // Valid login
                    $result['status'] = "1";
                    $result['user_id'] = $user->id;
                    $result['role_id'] = $user->role_id;
                    $result['email'] = $username;
                    $result['email_confirmed'] = $user->email_confirmed;
                    $result['confirmation_token'] = $user->confirmation_token;
                    $result['message'] = "Logged in successfully";
                } else {
                    // invalid password
                    $result['status'] = "0";
                    $result['message'] = "Incorrect Password or Email";
                }
            }
        } else {
            // fail
            $result['status'] = "0";
            $result['message'] = "Incorrect Password or Email";
        }

        return $result;
    }

    public function sendPasswordResetEmail($user)
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 30; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        $reset_password_token = implode($pass);
        $update_data = array(
            'reset_password_token' => $reset_password_token,
            'updated_at' => date('Y-m-d H:i:s'),
        );
        if ($update_data != null) {
            $this->CI->User_model->update($user->id, $update_data);
        }
        if ($user != null) {
            $subject = 'Reset Your Password';
            $message = "<div style='text-align: center;'>"
                    . "<hr style='width: 40px; color: #e8eaf1; background-color: #e8eaf1; border: 1px solid #e8eaf1;'>"
                    . "<br />"
                    . "Hi " . $user->first_name . ",<br />"
                    . "</div>"
                    . "<p style='color: #61646d; text-align: center; padding: 0 20px;'>You're receiving this email because we received a request to reset your password. Please click below to reset your password. If you didn't request a password reset and think that your account may be compromised, send us an email to <a href='mailto:support@matixdental.com'>support@matixdental.com</a></p><br />"
                    . "<a href='" . config_item('site_url') . "reset-password?reset_password_token=" . $reset_password_token . "' style='padding: 20px 40px 20px 40px;width:auto;display: block;text-decoration: none;border:0;text-align: center;font-weight: bold;font-size: 16px;font-family: Arial, sans-serif;color: #ffffff;background: #2893FF;border: 1px solid #317ED0;-moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius: 4px;line-height:normal;' class='button_link'>Reset My Password</a>";
            $email_data = array(
                'subject' => $subject,
                'message' => $message
            );
            $body = $this->CI->load->view('/templates/email/index.php', $email_data, TRUE);
            send_matix_email($body, $subject, $user->email);

            if($this->CI->input->post('superAdmin') == true){
                $this->CI->session->set_flashdata('success', 'Password reset email sent');
            } else {
                $this->CI->session->set_flashdata('success', 'Please check your email for reset password instructions');
            }
        }
    }

    public function verifyPassword($password, $userPassword)
    {
        return $userPassword == md5($password);
    }

    public function verifyNewPassword($password, $userNewPassword)
    {
        Debugger::debug($password . ':' . $userNewPassword);
        return password_verify($password, $userNewPassword);
    }


    public function logout()
    {
        // destroy the session

    }

    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }



}

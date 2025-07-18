<?php

class User_model extends MY_Model {

    public $_table = 'users'; // you MUST mention the table name
    public $primary_key = 'id'; // you MUST mention the primary key
    public $fillable = array(); // If you want, you can set an array with the fields that can be filled by insert/update
    public $protected = array(); // ...Or you can set an array with the fields that cannot be filled by insert/update

    public function __construct() {
        parent::__construct();
        $this->load->model('PDOhandler');
    }

    public function login_validation($data) {

        $this->form_validation->set_rules('accountEmail', 'Email', 'trim|required');
        $this->form_validation->set_rules('accountPW', 'Password', 'trim|required');

        if ($this->form_validation->run() === False) {
            $result['status'] = "0";
            $result['error'] = validation_errors();
        } else {
            $res = $this->User_model->get_by(array('email' => $data['email'], 'password' => $data['password']));
            $sql = $this->db->last_query();
            if ($res != null) {
                if ($res->status == '1') {
                    $result['status'] = "1";
                    $result['user_id'] = $res->id;
                    $result['message'] = "Logged in successfully";
                } else {
                    $result['status'] = "0";
                    $result['user_id'] = $res->id;
                    $result['confirmation_token'] = $res->confirmation_token;
                    $result['email_confirmed'] = $res->email_confirmed;
                    $result['message'] = "Incorrect Password or Email id";
                }
            } else {
                $result['status'] = "0";
                $result['message'] = "Incorrect Password or Email id";
            }
        }
        return $result;
    }

    public function email_validation($data) {
        $this->form_validation->set_rules('accountForgotEmail', 'Email', 'trim|required');

        if ($this->form_validation->run() === False) {
            $result['status'] = "0";
            $result['error'] = validation_errors();
        } else {
            $res = $this->User_model->get_by(array('email' => $data));
//            print_r($res);exit;
            if ($res == null) {
                $result['status'] = "0";
                $result['message'] = "Email-id Does Not Exist";
            } else {
                $result['status'] = "1";
                $result['user_id'] = $res->id;
                $result['message'] = "Logged in successfully";
            }
        }
        return $result;
    }

    public function email_check($email) {
        $this->db->select('email');
        $this->db->where('email', $email);
        $query = $this->db->get('users');
        if ($query->num_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    function humanTiming($time) {
        $time = ($time < 1) ? 1 : $time;
        $tokens = array(
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );

        foreach ($tokens as $unit => $text) {
            if ($time < $unit)
                continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '');
        }
    }

    /*
     *      Used in Vendor Dashboard
     *          1. To check whether the Users are Customers of Vendor(s).
     */
    public function CustomerCheck($user_id, $vendor_id) {
        $query = "SELECT a.user_id FROM orders  a WHERE a.user_id=$user_id and a.vendor_id=$vendor_id limit 1";
        $userCheck = $this->db->query($query)->result();
        if ($userCheck != null && count($userCheck) > 0) {
            return true;
        } else {
            return 0;
        }
    }

    public function loggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    public function can($permissions = null, $permissionCode)
    {
        foreach($permissions as $permission){
            if($permission['code'] == $permissionCode && !empty($permission['value'])){
                return true;
            }
        }

        return false;
    }

    public function createConfirmationToken($email)
    {
        // get userId from db
        $userId = $this->db->select('id')->from('users')->where('email', $email)->get()->id;

        // return unique hash
        return md5($userId . '-' . $email);
    }

    public function updateConfirmationCode($userId, $confirmationCode)
    {
        $sql = "UPDATE users SET
                    confirmation_token = :confirmationCode
                WHERE id = :id";

        $params = [
            ':confirmationCode' => $confirmationCode,
            ':id' => $userId
        ];

        $this->PDOhandler->query($sql, $params, null);
    }
}

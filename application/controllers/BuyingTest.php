<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class BuyingTest extends MW_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Role_model');
        $this->load->model('RolePermissions_model');
        $this->load->model('Permissions_model');
        $this->load->helper('MY_support_helper');
    }

    public function list()
    {
        die('here');
    }
}
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class NewCart extends MW_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Cart_model');
    }

    public function import()
    {
        $this->Cart_model->convertCarts();
    }
}
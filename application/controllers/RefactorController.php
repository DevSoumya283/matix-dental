<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class RefactorController extends MW_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('Memc');
        $this->load->model('PDOhandler');
        $this->load->library('Refactor');
    }

	public function product_categories()
	{
		$refactor = new Refactor;

		$refactor->fixProductCategories();

		die('Refactor done!');
	}
}
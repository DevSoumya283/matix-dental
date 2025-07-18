<?php

/**
 * Controller to be called from CLI only
 * Reference: http://www.codeigniter.com/user_guide/general/cli.html
 */
class SecretTools extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Category_model');
        $this->load->model('User_model');
    }

    public function selectCategoryList()
    {

    }

    public function importCategoryList()
    {
        if(config_item('allowTableRefresh')){
            if($this->User_model->loggedIn()){
                $this->Category_model->importCategoryList('categories_upload.csv');
            } else {
                die('You must be logged in');
            }
        } else {
            die('This action is not permitted');
        }

    }
}

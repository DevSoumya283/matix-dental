<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class WhiteLabels extends MW_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Whitelabel_model');
        $this->load->model('Category_model');
        $this->load->model('Vendor_model');
        $this->load->model('Page_model');
        $this->load->model('Vendor_groups_model');
        $this->load->helper('MY_support_helper');
    }

    public function listAll()
    {
        if($this->User_model->loggedIn() && $this->User_model->can($_SESSION['user_permissions'], 'edit-white-labels')){
            $data = $this->getCounts();
            $data['sites'] = $this->Whitelabel_model->loadAll();
            $data['vendors'] = $this->Vendor_model->getAllSummary();
            $data['userType'] = ($this->User_model->can($_SESSION['user_permissions'], 'is-admin') ? 'admin' : 'vendor');
            
            $this->load->view('/templates/_inc/header-admin.php');
            $this->load->view('/templates/admin/white-labels/list.php', $data);
            $this->load->view('/templates/_inc/footer-admin.php');

        } else {
            $this->session->set_flashdata('error', 'You do not have access to that page');
            header('Location: /login');
        }
    }

    public function load()
    {
        if($this->User_model->loggedIn() && $this->User_model->can($_SESSION['user_permissions'], 'edit-white-labels')){
            $site = $this->Whitelabel_model->load($this->input->post('id'));
            Debugger::debug($site);

            echo json_encode($site);
        } else {
            $this->session->set_flashdata('error', 'You do not have access to that page');
            header('Location: /login');
        }
    }

    public function loadPage()
    {
        if($this->User_model->loggedIn() && $this->User_model->can($_SESSION['user_permissions'], 'edit-white-labels')){
            $response = [];
            $response['site'] = $this->Whitelabel_model->load($this->input->post('whitelabelId'));
            $response['page'] = $this->Page_model->loadPage($this->input->post('whitelabelId'), $this->input->post('name'));
            $response['page']->site_id = $this->input->post('whitelabelId');
            Debugger::debug($response['page']);
            if($this->input->post('name') == 'home'){
                $response['categoryLinks'] = $this->Page_model->loadHomeCategories($this->input->post('whitelabelId'));
                Debugger::debug($response['categoryLinks']);
            }
            echo json_encode($response);
        } else {
            $this->session->set_flashdata('error', 'You do not have access to that page');
            header('Location: /login');
        }
    }

    public function loadVendorCategories()
    {
        $response['cats'] = $this->Category_model->getCategories(1, $this->config->item('whitelabel_vendor_id'));
        // $response['cats'] = $this->Category_model->getCategories($this->input->post('parent_id'), $this->input->post('vendor_id') );
        echo json_encode($response);
    }

    public function addHomeCategory()
    {
        // $siteId, $categoryName, $categoryId, $categoryImage, $slotNo
        Debugger::debug($_POST);
        Debugger::debug($_FILES);
        $catlink = $this->Page_model->addHomeCategory($_POST, $_FILES['categoryImage']);

        echo json_encode($catlink);
    }

    public function removeHomeCategory()
    {
        $this->Page_model->removeHomeCategory($this->input->post('linkId'));
    }

    public function loadHomeCategories()
    {

    }

    public function loadCategoryChildren($parentId)
    {

    }

    public function save()
    {
        if($this->User_model->loggedIn() && $this->User_model->can($_SESSION['user_permissions'], 'edit-white-labels')){
            Debugger::debug($_POST);
            Debugger::debug($_FILES);
            // first save white label and logo
            $this->Whitelabel_model->save($_POST, $_FILES['whitelabelLogo']);
            $this->session->set_flashdata('success', 'Site saved.');
            $this->Memc->flush();
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            $this->session->set_flashdata('error', 'You do not have access to that page');
            header('Location: /login');
        }
    }

    public function edit()
    {
        if($this->User_model->loggedIn() && $this->User_model->can($_SESSION['user_permissions'], 'edit-white-labels')){
            $data = $this->getCounts();
            if($data['site'] = $this->Whitelabel_model->load($this->input->get('id'))){
                $data['vendors'] = $this->Vendor_model->getAllSummary();
                $data['userType'] = ($this->User_model->can($_SESSION['user_permissions'], 'is-admin') ? 'admin' : 'vendor');
                $data['pages'] = $this->Page_model->loadAll();
                Debugger::debug($data);
                
                $this->load->view('/templates/_inc/header-admin.php');
                $this->load->view('/templates/admin/white-labels/edit.php', $data);
                $this->load->view('/templates/_inc/footer-admin.php');


            } else {
                $this->session->set_flashdata('error', 'You do not have access to edit that site');
                header('Location: /white-labels');
            }
        } else {
            $this->session->set_flashdata('error', 'You do not have access to that page');
            header('Location: /login');
        }
    }

    public function savePage()
    {
        if($this->User_model->loggedIn() && $this->User_model->can($_SESSION['user_permissions'], 'edit-white-labels')){
            Debugger::debug($_POST);
            Debugger::debug($_FILES);
            $this->Page_model->saveSitePage($_POST, $_FILES['whitelabelHero']);
            // $this->Page_model->saveSitePage($this->input->post('site_id'), $this->input->post('name'), $this->input->post('page_title'), $this->input->post('tagline'), $this->input->post('content'), $_FILES['whitelabelHero']);
            $this->Memc->flush();
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            $this->session->set_flashdata('error', 'You do not have access to that page');
            header('Location: /login');
        }
    }

    public function saveHero()
    {
        if($this->User_model->loggedIn() && $this->User_model->can($_SESSION['user_permissions'], 'edit-white-labels')){
            Debugger::debug($_POST);
            Debugger::debug($_FILES);
            $this->Page_model->saveSitePage($_POST, $_FILES['whitelabelHero']);
            // $this->Page_model->saveSitePage($this->input->post('site_id'), $this->input->post('name'), $this->input->post('page_title'), $this->input->post('tagline'), $this->input->post('content'), $_FILES['whitelabelHero']);
            $this->Memc->flush();
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            $this->session->set_flashdata('error', 'You do not have access to that page');
            header('Location: /login');
        }
    }

    public function getCounts()
    {
        $counts = [
            'user_approval' => user_counts(),
            'flagged_count' => flagged_count(),
            'answer_count' => flaggedAnswer_count(),
            'ReturnCount' => return_count()
        ];

        if($this->User_model->can($_SESSION['user_permissions'], 'is-vendor')){
            $counts['NorderCount'] = order_count();
        }

        return $counts;
    }
}
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class RolesAdmin extends MW_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Role_model');
        $this->load->model('RolePermissions_model');
        $this->load->model('Permissions_model');
        $this->load->helper('MY_support_helper');
    }

    public function listRoles()
    {
        if($this->User_model->loggedIn() && $this->User_model->can($_SESSION['user_permissions'], 'edit-roles')){
            $roles = $this->Role_model->loadAllRoles();
            $permissions = $this->Permissions_model->loadAllPermissions();
            $groups = $this->Permissions_model->loadAllGroups();

            $this->load->view('/templates/_inc/header-admin.php');
            $this->load->view('/templates/admin/roles/index.php', [
                                    'roles' => $roles,
                                    'permissions' => $permissions,
                                    'groups' => $groups,
                                    'user_approval' => user_counts(),
                                    'flagged_count' => flagged_count(),
                                    'answer_count' => flaggedAnswer_count(),
                                ]);

            $this->load->view('/templates/_inc/footer-admin.php');
        } else {
            $this->session->set_flashdata('error', 'You do not have access to that page');
            header('Location: /login');
        }
    }
}
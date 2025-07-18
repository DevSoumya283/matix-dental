<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PermissionsAdmin extends MW_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Role_model');
        $this->load->model('RolePermissions_model');
        $this->load->model('Permissions_model');
    }

    public function loadGroups()
    {
        $groups = $this->Permissions_model->loadAllGroups();

        echo json_encode($groups);
    }

    public function savePermission()
    {
        $this->Permissions_model->savePermission($this->input->post('name'), $this->input->post('code'), $this->input->post('groupId'), $this->input->post('groupName'));
        $permissions = $this->Permissions_model->loadAllPermissions();

        echo json_encode($permissions);
    }

    public function loadPermissions()
    {
        $roleId = $this->input->post('roleId');
        $permissions = $this->Permissions_model->loadAllPermissions($roleId);

        echo json_encode($permissions);
    }

    public function saveRolePermission()
    {
        $this->RolePermissions_model->saveRolePermission($this->input->post('roleId'), $this->input->post('permissionId'), $this->input->post('value'));
        $permissions = $this->Permissions_model->loadAllPermissions();

        echo json_encode($permissions);
    }

    public function loadRolePermissions()
    {
        $roleId = $this->input->post('roleId');
        $permissions = $this->RolePermissions_model->loadPermissions($roleId);

        echo json_encode($permissions);
    }
}
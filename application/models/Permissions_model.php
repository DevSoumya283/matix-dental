<?php

class Permissions_model extends MY_Model {

    public $_table = 'permissions'; // you MUST mention the table name
    public $primary_key = 'id'; // you MUST mention the primary key
    public $fillable = array(); // If you want, you can set an array with the fields that can be filled by insert/update
    public $protected = array(); // ...Or you can set an array with the fields that cannot be filled by insert/update

    public function __construct() {
        parent::__construct();
        $this->load->model('Memc');
        $this->load->model('PDOhandler');
    }

    public function loadAllPermissions()
    {
        $sql = "SELECT * FROM permissions";

        $permissions = $this->Memc->get($sql, [], 'all-permissions', 3600, 'fetchAll');

        return $permissions;
    }

    public function savePermission($name, $code, $groupId = null, $groupName = null)
    {
        $groupId = (empty($groupId) && !empty($this->input->post('groupName'))) ? $this->Permissions_model->saveGroup($this->input->post('groupName')) : $this->input->post('groupId');

        Debugger::debug($groupId);

        $sql = "INSERT INTO permissions
                    (id, group_id, name, code)
                VALUES
                    (:id, :groupId, :name, :code)
                ON DUPLICATE KEY UPDATE name = :name";

        $params = [
            ':id' => $id,
            ':name' => $name,
            ':code' => $code,
            ':groupId' => $groupId

        ];

        $this->PDOhandler->query($sql, $params);

        $this->Memc->flush();
    }

    public function deletePermission($id)
    {
        $sql = "DELETE FROM permissions
                WHERE id = :id";

        $this->PDOhandler->query($sql, [':id' => $id]);
    }

    public function loadAllGroups()
    {
        $sql = "SELECT *
                FROM permission_groups
                ORDER BY name ASC";

        $permissionGroups = $this->Memc->get($sql, [], 'all-permission-groups', 3600, 'fetchAll');

        return $permissionGroups;
    }

    public function loadGroupByName($name)
    {
        $sql = "SELECT *
                FROM permission_groups
                WHERE name = :name";

        $group = $this->Memc->get($sql, [':name' => $name], 'group-' . $name, 3600, 'fetch');

        return $group;
    }

    public function saveGroup($name = null, $id = null)
    {
        $sql = "INSERT INTO permission_groups
                    (id, name)
                VALUES
                    (:id, :name)
                ON DUPLICATE KEY UPDATE name = :name";

        $params = [
            ':id' => $id,
            ':name' => $name
        ];

        $groupId = $this->PDOhandler->query($sql, $params);
        Debugger::debug($groupId, 'groupId');

        $this->cache->delete('all-permission-groups');
    }
}

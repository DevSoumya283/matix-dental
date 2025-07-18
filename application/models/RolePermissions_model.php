<?php

class RolePermissions_model extends MY_Model {

    public $_table = 'role_permission'; // you MUST mention the table name
    public $primary_key = 'id'; // you MUST mention the primary key
    public $fillable = array(); // If you want, you can set an array with the fields that can be filled by insert/update
    public $protected = array(); // ...Or you can set an array with the fields that cannot be filled by insert/update

    public function __construct() {
        parent::__construct();
        $this->load->model('Memc');
        $this->load->model('PDOhandler');
    }

    public function loadPermissions($roleId)
    {
        $sql = "SELECT p.*, rp.*
                FROM permissions AS p
                LEFT JOIN (
                    SELECT *
                    FROM role_permission as perms
                    WHERE perms.role_id = :roleId
                ) AS rp
                    ON p.id = rp.permission_id";


        $result = $this->Memc->get($sql, [':roleId' => $roleId], 'role-permissions-' . $roleId, 3600, 'fetchAll');

        $permissions = [];
        foreach($result as $permission){
            $permissions[$permission->id] = ['code' => $permission->code,
                                             'value' => $permission->value];
        }

        return $permissions;
    }

    public function saveRolePermission($roleId, $permissionId, $value)
    {
        $value = (int)($value === 'true');

        $sql = "INSERT INTO role_permission (
                    role_id, permission_id, `value`
                ) VALUES (:role_id, :permission_id, :value)
                ON DUPLICATE KEY UPDATE `value` = :value";

        $params = [
            ':role_id' => $roleId,
            ':permission_id' => $permissionId,
            ':value' => $value
        ];

        $this->PDOhandler->query($sql, $params);

        // clear cached permissions
        $this->cache->delete('role-permissions-' . $roleId);
    }
}

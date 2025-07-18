<?php

class Organization_groups_model extends MY_Model {

    public $_table = 'organization_groups'; // you MUST mention the table name
    public $primary_key = 'id'; // you MUST mention the primary key
    public $fillable = array(); // If you want, you can set an array with the fields that can be filled by insert/update
    public $protected = array(); // ...Or you can set an array with the fields that cannot be filled by insert/update

    public function __construct() {
        parent::__construct();
    }

    public function organizationGroup_users($organization_id, $emailid) {
        $query = "SELECT b.id,b.email,b.first_name,b.role_id,b.stripe_id,a.organization_id FROM organization_groups a INNER JOIN users b on b.id=a.user_id WHERE a.organization_id=$organization_id and b.email like '%$emailid%'";
        $result = $this->db->query($query)->result();
        if ($result != null) {
            return $result;
        } else {
            return NULL;
        }
    }

    public function get_users_by_user($user_id)
    {
        $users = [];

        $organization_group = $this->get_by(['user_id' => $user_id]);

        $organization = $organization_group->organization_id;

        $organization_groups = $this->Organization_groups_model->get_many_by(['organization_id' => $organization]);

        foreach ($organization_groups as $group) {
            $users[] = $group->user_id;
        }

        return $users;
    }

}

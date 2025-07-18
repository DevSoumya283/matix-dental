<?php

class User_licenses_model extends MY_Model {

    public $_table = 'user_licenses'; // you MUST mention the table name
    public $primary_key = 'id'; // you MUST mention the primary key
    public $fillable = array(); // If you want, you can set an array with the fields that can be filled by insert/update
    public $protected = array(); // ...Or you can set an array with the fields that cannot be filled by insert/update

    public function __construct() {
        parent::__construct();
        $this->load->model('Organization_groups_model');
        $this->load->model('Organization_location_model');
        $this->load->model('User_location_model');
        $this->load->model('Memc');
        $this->load->model('PDOhandler');
    }

    public function loadValidLicenses($userId, $approved)
    {
        $orgUsers = $this->Organization_groups_model->get_users_by_user($userId);

        $userLicenses = $this->get_many_by(array('user_id' => $orgUsers, 'approved' => $approved));

        $sortedLicenses = [];

        $todayDate = date('Y-m-d');
        foreach($userLicenses as $license){
            if($license->expire_date >= $todayDate){
                $sortedLicenses[$license->state][] = $license;
            }
        }

        return $sortedLicenses;
    }

    public function checkLicenses($orgId)
    {
        $this->Memc->flush();

        $sql = "SELECT DISTINCT state
                FROM user_licenses
                WHERE approved = 1
                AND organization_id = 2
                AND expire_date >= CURRENT_DATE()";

        $validLicenseStates = $this->Memc->get($sql, [':orgId' => $orgId], null, 3600, 'fetchAll');

        $orgLocations = $this->Organization_location_model->loadAllLocations($orgId);
        Debugger::debug($orgLocations);
        Debugger::debug($validLicenseStates, 'validLicenseStates');

        $count = 0;
        foreach($orgLocations as $id => $location){
            foreach($validLicenseStates as $id => $state){
                if($location->state == $state->state){
                    $count++;
                }
            }
        }

        if (count($validLicenseStates) == 0){
            $has_license = 0;
        } elseif ($count == count($orgLocations)){
            $has_license = 1;
        } else {
            $has_license = 2;
        }

        return $has_license;
    }
}

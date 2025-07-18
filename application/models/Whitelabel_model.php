<?php

class Whitelabel_model extends MY_Model {

    public $_table = 'sites'; // you MUST mention the table name
    public $primary_key = 'id'; // you MUST mention the primary key
    public $fillable = array(); // If you want, you can set an array with the fields that can be filled by insert/update
    public $protected = array(); // ...Or you can set an array with the fields that cannot be filled by insert/update

    public function __construct() {
        parent::__construct();
        $this->load->model('Memc');
        $this->load->model('PDOhandler');
        $this->load->model('User_model');
    }

    public function load($id)
    {
        if(!empty($id)){
            $this->db->where('id', $id);

            if($this->User_model->can($_SESSION['user_permissions'], 'is-vendor')){
                $this->db->where('vendor_id', $_SESSION['vendor_id']);
            }

            $query = $this->db->get('sites');
            $site = $query->row();
        } else {
            $site = new stdClass();
            $site->id = 0;
            $site->name = 'Marketplace';
        }

        return $site;
    }

    public function save($params, $logo)
    {
        Debugger::debug($params);
        $checkboxFields = [
            'limit_to_vendor_products'
            // 'hide_selected_products_1',
            // 'hide_selected_products_2'
        ];

        foreach($checkboxFields as $field){
            if(empty($params[$field])){
                $params[$field] = 0;
            }
        }

        foreach($params as $k => $v){
            if($k != 'id'){
                $this->db->set($k, $v);
            }
        }

        Debugger::debug($params);
        $logoPath = FCPATH . 'assets/img/logos/';
        if(!empty($logo['tmp_name'])){
            $logo['name'] = str_replace(' ', '_', $logo['name']);
            while(file_exists($logoPath . $logo['name'])){
                $nameBits = explode('.', $logo['name']);
                $ext = array_pop($nameBits);

                $logo['name'] = implode('', $nameBits) . '1' . '.' . $ext;
            }

            Debugger::debug('moving file');
            move_uploaded_file($logo['tmp_name'], $logoPath . $logo['name']);

            $this->db->set('logo', $logo['name']);
        }

        // save site
        if(empty($params['id'])){
            $this->db->insert('sites');
        } else {
            $this->db->where('id', $params['id']);
            $this->db->update('sites');
        }

        $this->Memc->flush();


    }

    public function testDomain($domain)
    {
        $shortName = $this->getShortName($domain);
        $domain = $this->cleanDomain($domain);


        $sql = "SELECT *
                FROM sites
                WHERE domain = :domain
                OR short_name = :shortName";

        $whitelabel = $this->Memc->get($sql, [':domain' => $domain, ':shortName' => $shortName], 'whitelabel-domain-' . $shortName, 60, 'fetch');

        return $whitelabel;
    }

    public function getShortName($domain)
    {
        $urlBits = explode('.', $domain);

        return $urlBits[0];
    }

    public function cleanDomain($domain)
    {
        $urlBits = explode('.', $domain);

        // if www discard
        if($urlBits[0] == 'www') {
            $tmp = array_shift($urlBits);
        }

        return implode('.', $urlBits);
    }

    public function loadAll()
    {
        $sql = "SELECT s.*, v.name AS vendor_name
                FROM sites AS s
                LEFT JOIN vendors AS v
                    ON s.vendor_id = v.id
                ";
        if($this->User_model->can($_SESSION['user_permissions'], 'is-vendor')){
            $sql .= "WHERE s.vendor_id = ?";
        }

        return $this->db->query($sql, [$_SESSION['vendor_id']])->result();
    }

}

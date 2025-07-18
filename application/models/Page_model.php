<?php

class Page_model extends MY_Model {

    public $_table = 'pages'; // you MUST mention the table name
    public $primary_key = 'id'; // you MUST mention the primary key
    public $fillable = array(); // If you want, you can set an array with the fields that can be filled by insert/update
    public $protected = array(); // ...Or you can set an array with the fields that cannot be filled by insert/update

    public function __construct() {
        parent::__construct();
        $this->load->model('Memc');
        $this->load->model('PDOhandler');
    }

    public function loadAll($whitelabelId = null)
    {
        $sql = "SELECT p1.name, COALESCE(p2.page_title, p1.page_title) AS page_title
                FROM pages AS p1
                LEFT JOIN (SELECT *
                    FROM pages
                    WHERE site_id = :whitelabelId) AS p2
                ON p1.name = p2.name
                WHERE p1.site_id IS NULL";

        $result = $this->Memc->get($sql, [':whitelabelId' => $whitelabelId], 'site-pages-' . $whitelabelId, 86400, 'fetchAll');

        return $result;
    }

    public function loadPage($whitelabelId, $name)
    {
        $this->Memc->flush();
        $sql = "SELECT COALESCE(p2.hero, p1.hero) AS hero,
                       COALESCE(p2.id, NULL) AS id,
                       COALESCE(p2.content, p1.content) AS content,
                       COALESCE(p2.page_title, p1.page_title) AS page_title,
                       COALESCE(p2.tagline, p1.tagline) AS tagline,
                       p1.name,
                       :whitelabelId as site_id

                FROM pages AS p1
                LEFT JOIN (SELECT *
                      FROM pages
                      WHERE site_id = :whitelabelId
                      AND name = :name) AS p2
                    ON p1.name = p2.name
                WHERE p1.name = :name";

        $result = $this->Memc->get($sql, [':whitelabelId' => $whitelabelId, ':name' => $name], null, 0);
        Debugger::debug($result);
        return $result;
    }

    function loadHomeCategories($siteId, $showDefault = false)
    {
        $sql = "SELECT hc.*, c.parent_id
                FROM home_categories AS hc
                LEFT JOIN categories AS c
                    ON hc.category_id = c.id ";
        if(!empty($siteId)){
            $sql .= "WHERE site_id = :siteId ";
        } else {
            $sql .= "WHERE site_id IS NULL ";
        }

        $result = $this->Memc->get($sql, [':siteId' => $siteId], 'home-categories-' . $siteId, 6000, 'fetchAll');

        if(empty($result) && $showDefault){
            $result = $this->loadHomeCategories(NULL);
        }
        Debugger::debug($result);
        return $result;
    }

    function addHomeCategory($params, $catImage)
    {
        Debugger::debug($params);
        if(!empty($params['child_category_id'])){
            $params['category_id'] = $params['child_category_id'];
        }
        $allowedFields = ['site_id', 'category_name', 'category_id'];

        foreach($params as $k => $v){
            if(in_array($k, $allowedFields)){
                $this->db->set($k, $v);
            }
        }

        $path = FCPATH . 'uploads/sites/catmenu/';
        if(!empty($catImage['tmp_name'])){
            $catImage['name'] = $this->uploadImage($path, $catImage);
            $params['category_image'] = $catImage['name'];
            $this->db->set('category_image', $catImage['name']);
        }

        // save site
        if(empty($params['id'])){
            $this->db->insert('home_categories');
            $params['id'] = $this->db->insert_id();
        } else {
            $this->db->where('id', $params['id']);
            $this->db->update('home_categories');
        }

        $this->Memc->flush();
        Debugger::debug($result);
        return $params;
    }

    public function removeHomeCategory($linkId)
    {
        $sql = "DELETE
                FROM home_categories
                WHERE id = :id";

        $this->PDOhandler->query($sql, [':id' => $linkId]);
    }

    public function saveSitePage($params, $hero)
    {
        Debugger::debug($params);
        $allowedFields = ['site_id', 'name', 'page_title', 'tagline', 'content'];

        foreach($params as $k => $v){
            if(in_array($k, $allowedFields)){
                $this->db->set($k, $v);
            }
        }

        $path = FCPATH . 'assets/img/heros/';
        if(!empty($hero['tmp_name'])){
            $hero['name'] = $this->uploadImage($path, $hero);

            $this->db->set('hero', $hero['name']);
        }

        // save site
        if(empty($params['id'])){
            $this->db->insert('pages');
        } else {
            $this->db->where('id', $params['id']);
            $this->db->update('pages');
        }

        $this->Memc->flush();
    }

    public function uploadImage($path, $image)
    {
        if(!empty($image['tmp_name'])){
            while(file_exists($path . $image['name'])){
                $image['name'] = str_replace(' ', '_', $image['name']);
                $nameBits = explode('.', $image['name']);
                $ext = array_pop($nameBits);
                $image['name'] = implode('', $nameBits) . '1' . '.' . $ext;
            }

            Debugger::debug('moving file to ' . $path . $image['name']);
            move_uploaded_file($image['tmp_name'], $path . $image['name']);
        }
        return $image['name'];
    }

}

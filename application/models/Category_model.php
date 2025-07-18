<?php

class Category_model extends MY_Model {

    public $_table = 'categories'; // you MUST mention the table name
    public $primary_key = 'id'; // you MUST mention the primary key
    public $fillable = array(); // If you want, you can set an array with the fields that can be filled by insert/update
    public $protected = array(); // ...Or you can set an array with the fields that cannot be filled by insert/update

    public function __construct() {
        parent::__construct();
        $this->load->model('Memc');
        $this->load->model('PDOhandler');
        $this->load->library('Refactor');
    }

    /* Find Category Model Based Off id */
    public function find($id)
    {
        $sql = "SELECT *
                FROM categories
                WHERE id = :id";

        return $this->Memc->get($sql, [':id' => $id], 'category_' . $id, 3600, 'fetch');
    }

    public function getMenu($parent_id) {
        return $this->db->select('id, name, separator_after')
            ->where(array('parent_id' => $parent_id, 'parent_id2' => $parent_id))
            ->order_by('order', 'asc')
            ->order_by('name', 'asc')
            ->get('categories')
            ->result();
    }

    public function getCategories($parentId, $vendorId = 5)
    {
        $params = [':parentId' => $parentId,
                   ':vendorId' => $vendorId];

        $sql = 'SELECT cats.id, TRIM(cats.name) AS name, cats.separator_after
                FROM (
                    SELECT  DISTINCT c.id, c.name, c.separator_after, c.parent_id, c.order
                    from categories AS c
                    WHERE c.preorder_l > (select preorder_l
                                          FROM categories
                                          where id = :parentId)
                    AND c.preorder_r < (select preorder_r
                                          FROM categories
                                          where id = :parentId)
                    ) AS cats
                WHERE cats.parent_id = :parentId
                AND cats.id IN (SELECT x.id
                                 FROM   categories AS x
                                 JOIN product_category AS y
                                    ON y.category_id = x.id
                                 JOIN product_pricings AS z
                                    ON y.product_id = z.product_id
                                 WHERE  z.vendor_id = :vendorId
                                 AND z.active = 1)
                ORDER BY cats.`order`, cats.name ASC';

        $result = $this->PDOhandler->query($sql, $params);
        // $result = $this->Memc->get($sql, $params, 'load_cats_' . $parentId . '_' . $vendorId, 36000, 'fetchAll');

        // Debugger::debug($result, 'categories');
        return $result;
    }



    public function getCategoriesForVendor($parentId, $vendorId)
    {
        $sql = 'SELECT DISTINCT c.id, c.name, c.separator_aftercwtch
                FROM `categories` c
                INNER JOIN `products` p
                    ON FIND_IN_SET(CONCAT(\'"\',c.id, \'"\'), p.category_id) AND c.parent_id = :parentId
                INNER JOIN `product_pricings` pp
                    ON p.id = pp.product_id AND pp.active = 1
                WHERE pp.vendor_id = :vendorId
                ORDER BY `order`, `name` ASC';


        $result = $this->PDOhandler->query($sql, [':parentId' => $parentId, ':vendorId' => $vendorId]);

        Debugger::debug($result, "Categories for $parentId - $vendorId");
        return $result;
    }

    public function getCategoryBranch($categoryId, &$branch = [])
    {
        array_unshift($branch, $this->get($categoryId));

        if($branch[0]->parent_id != 0){
            $this->getCategoryBranch($branch[0]->parent_id, $branch);
        }

        return $branch;
    }

    public function getCategoryParents($categoryId)
    {
        $result = $this->getCategoryBranch($categoryId);

        if ($result != null) {
            return $result;
        } else {
            return 0;
        }

    }

    public function getCategoryChildren($categoryId, $vendorId = 5)
    {
        if(empty($vendorId)) {
            $vendorId = 5;
        }

        $sql = "SELECT *
                FROM categories AS c
                WHERE c.parent_id = :categoryId
                AND c.id IN (SELECT x.id
                                 FROM   categories AS x
                                 JOIN product_category AS y
                                    ON y.category_id = x.id
                                 JOIN product_pricings AS z
                                    ON y.product_id = z.product_id
                                 WHERE  z.vendor_id = :vendorId
                                 AND z.active = 1)
                ORDER BY c.`order`, c.name ASC;";

        // $result = $this->PDOhandler->query($sql, [':categoryId' => $categoryId,  ':vendorId' => $vendorId]);

        $result = $this->Memc->get($sql, [':categoryId' => $categoryId, ':vendorId' => $vendorId], 'Category_' . $categoryId . '_children', 3600, 'fetchAll');

        return $result;
    }


    public function importCategoryList($filename)
    {
        Debugger::debug('opening csv');
        echo 'opening ' . $filename . '<br>';
        $_table = 'categories';
        if ( ($handle = fopen($filename, "r")) !== FALSE) {
            echo 'parsing lines<br>';
            flush();
            // clear existing data
            $sql = "DELETE FROM " . $table;
            $result = $this->PDOhandler->query($sql, []);

            $sql = "INSERT IGNORE INTO " . $table . " (
                        id,
                        name,
                        parent_id,
                        preorder_l,
                        preorder_r
                    ) VALUES (
                        :id,
                        :name,
                        :parent_id,
                        :preorder_l,
                        :preorder_r
                    )";


            while (($line = fgetcsv($handle, 4096)) !== FALSE) {
                Debugger::debug($line);
                // echo $line;
                // $bits = explode(',', $line);
                // Debugger::debug($bits);
                $params = [
                    ':id' => $line[0],
                    ':name' => trim($line[1]),
                    ':parent_id' => $line[2],
                    ':preorder_l' => $line[6],
                    ':preorder_r' => $line[7]
                ];

                $result = $this->PDOhandler->query($sql, $params);
            }
            fclose($handle);

            $count = 0;
            // $this->createCategoryPreorderTraversal(1, $count);
            // $this->createCategoryPreorderTraversal(2, $count);

            echo "done";
        } else {
            die('could not open file: ' . config_item('homeDir') . $filename);
        }
    }

    public function createCategoryPreorderTraversal($nodeId, &$count)
    {
        // set left
        $count++;
        $this->setPtLeft($nodeId, $count);

        //check children, process if found
        $children = $this->getCategoryChildren($nodeId);

        if(!empty($children)){
            foreach($children as $category){
                $this->createCategoryPreorderTraversal($category->id, $count);
            }
        }

        // set right
        $count++;
        $this->setPtRight($nodeId, $count);

        return;
    }

    private function setPtLeft($nodeId, $count)
    {
        $sql = "UPDATE categories set
                preorder_l = :count
                WHERE id = :nodeId";

        Debugger::debug($sql);

        $this->PDOhandler->query($sql, [':count' => $count, ':nodeId' => $nodeId]);
    }

    private function setPtRight($nodeId, $count)
    {
        $sql = "UPDATE categories set
                preorder_r = :count
                WHERE id = :nodeId";

        $this->PDOhandler->query($sql, [':count' => $count, ':nodeId' => $nodeId]);
    }

}

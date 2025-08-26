<?php
defined('BASEPATH') or exit('No direct script access allowed');

class NewProducts extends MW_Controller
{
    private $table; // holds products table name

    public function __construct()
    {
        parent::__construct();
        $this->load->database();

        // ✅ define table name here
        $this->table = 'products';
    }

    private function get_nav_categories()
    {
        // Get only parent categories (parent_id = 0) that have products
        $sql = "SELECT DISTINCT c.* 
                FROM categories c
                WHERE c.parent_id = 0 
                AND EXISTS (
                    SELECT 1 FROM {$this->table} p 
                    WHERE FIND_IN_SET(c.id, p.category_id)
                )
                ORDER BY c.name";
        return $this->db->query($sql)->result();
    }

    private function get_used_categories()
    {
        // Get all categories that have products (for sidebar)
        $sql = "SELECT DISTINCT c.* 
                FROM categories c
                WHERE EXISTS (
                    SELECT 1 FROM {$this->table} p 
                    WHERE FIND_IN_SET(c.id, p.category_id)
                )
                ORDER BY c.parent_id, c.name";
        return $this->db->query($sql)->result();
    }

    private function get_subcategories($parent_id)
    {
        // Get subcategories that have products
        $sql = "SELECT DISTINCT c.* 
                FROM categories c
                WHERE c.parent_id = ? 
                AND EXISTS (
                    SELECT 1 FROM {$this->table} p 
                    WHERE FIND_IN_SET(c.id, p.category_id)
                )
                ORDER BY c.name";
        return $this->db->query($sql, [$parent_id])->result();
    }

    private function get_all_nav_categories()
    {
        // Get all parent categories for navbar (regardless of products)
        return $this->db->where('parent_id', 0)->order_by('name')->get('categories')->result();
    }

    private function organize_sidebar_categories($categories)
    {
        $organized = [];
        $parent_categories = [];
        $child_categories = [];

        // Separate parent and child categories
        foreach ($categories as $category) {
            if ($category->parent_id == 0) {
                $parent_categories[] = $category;
            } else {
                $child_categories[] = $category;
            }
        }

        // Organize with children under parents
        foreach ($parent_categories as $parent) {
            $organized[] = $parent;
            foreach ($child_categories as $child) {
                if ($child->parent_id == $parent->id) {
                    $organized[] = $child;
                }
            }
        }

        return $organized;
    }

    private function attach_product_details(&$products)
    {
        foreach ($products as &$product) {
            // Get primary category (first one in the list)
            $category_ids = explode(',', str_replace('"', '', $product->category_id));
            $primary_category_id = !empty($category_ids) ? trim($category_ids[0]) : null;

            if ($primary_category_id) {
                $cat = $this->db->where('id', $primary_category_id)->get('categories')->row();
                $product->category_name = $cat ? $cat->name : 'Uncategorized';
                $product->primary_category_id = $primary_category_id;
            } else {
                $product->category_name = 'Uncategorized';
                $product->primary_category_id = null;
            }

            // Store all category IDs for this product
            $product->all_category_ids = $category_ids;

            // Pricings
            $product->pricings = $this->db->where('vendor_product_id', $product->matix_id)
                ->get('product_pricings')->result();

            // SKUs
            $product->skus = $this->db->where('product_id', $product->matix_id)
                ->get('skus')->result();

            // Options
            $this->db->select('po.option_type, pov.value, pov.value_id');
            $this->db->from('product_options po');
            $this->db->join('product_option_values pov', 'po.option_id = pov.option_id');
            $this->db->where('pov.product_id', $product->id);
            $product->options = $this->db->get()->result();

            foreach ($product->skus as &$sku) {
                $this->db->select('po.option_type, pov.value');
                $this->db->from('sku_option_values sov');
                $this->db->join('product_option_values pov', 'sov.value_id = pov.value_id');
                $this->db->join('product_options po', 'pov.option_id = po.option_id');
                $this->db->where('sov.sku_id', $sku->sku_id);
                $sku->options = $this->db->get()->result();
            }
        }
    }

    private function get_products_by_category($category_id)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE category_id LIKE ?";

        // will match "14" inside:  "183","182","14","1"
        return $this->db->query($sql, ['%"' . $category_id . '"%'])->result();
    }

    public function index()
    {
        // Navigation: All parent categories (for navbar dropdown)
        $data['nav_categories'] = $this->get_all_nav_categories();

        // Sidebar: All categories with products, organized
        $all_used_categories = $this->get_used_categories();
        $data['sidebar_categories'] = $this->organize_sidebar_categories($all_used_categories);
        $data['sidebar_category'] = null; // For backward compatibility with view
        $data['sidebar_subcategories'] = []; // For backward compatibility with view

        // Get all products
        $products = $this->db->get($this->table)->result();
        $this->attach_product_details($products);
        $data['products'] = $products;

        $this->load->view('new_products', $data);
    }

    public function category($category_id)
    {
        // ✅ Always load navbar categories (parent categories)
        $data['nav_categories'] = $this->get_all_nav_categories();

        // Get clicked category
        $current_category = $this->db->where('id', $category_id)->get('categories')->row();

        $sidebar_parent = null;
        $sidebar_children = [];

        if ($current_category) {
            // If it has a parent → get parent
            if ($current_category->parent_id != 0) {
                $sidebar_parent = $this->db->where('id', $current_category->parent_id)->get('categories')->row();
            }

            // If it has children → get children
            $sidebar_children = $this->db->where('parent_id', $current_category->id)
                ->order_by('`order`, name')
                ->get('categories')->result();
        }

        $data['current_category']  = $current_category;
        $data['sidebar_parent']    = $sidebar_parent;
        $data['sidebar_children']  = $sidebar_children;

        // Fetch products for this category
        $data['products'] = $this->get_products_by_category($category_id);

        $this->load->view('new_products', $data);
    }
}

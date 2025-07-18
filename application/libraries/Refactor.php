<?php

// require_once(dirname(__FILE__) . '/Product.php');

// require_once(realpath(BASEPATH . '../') . '/application/libraries/Product.php');

class Refactor
{
	public function __construct()
	{
		// get the CI instance for db queries
		$this->CI = $CI =& get_instance();
        $this->CI->load->model('Memc');
        $this->CI->load->model('PDOhandler');
	}

	/**
	 *	Insert product categories into new table
	 */
	public function fixProductCategories()
	{
		$productTools = new Product;

		// make sure the table exists, create if not
		$this->createProductCategoryLinkTable();

		// get all products and categories
		$productCategories = $productTools->loadCategoriesOld();
		// loop through products and insert categories into new table
		foreach($productCategories as $product) {
			$categoryIds = $this->extractProductCategories($product->category_id);

			foreach($categoryIds as $categoryId) {
				// check if it has children

				// insert into new table
				if(!empty($categoryId)){
					$productTools->saveCategory($product->id, $categoryId);
				}
			}
		}

		Debugger::debug('done fixing');

		// $this->createPreorderTraversal(1);
		// $this->createPreorderTraversal(2);
	}

	/**
	 *	Extract product categories
	 *
	 *	@param string $categoryString
	 *
	 *	@return array
	 */
	private function extractProductCategories($categoryString)
	{
		return  explode(',', str_replace('"', '', $categoryString));
	}


	/**
	 *	Create category link table
	 */
	public function createProductCategoryLinkTable()
	{
		// check if the table exists, barf if it does
		$sql = "SHOW TABLES LIKE 'product_category'";
		$table = $this->CI->db->query($sql)->result();
		if(!empty($table)){
			// does it have left and right?
			$sql = "SHOW COLUMNS FROM `categories` LIKE 'preorder_l'";
			$result = $this->CI->PDOhandler->query($sql, []);
			Debugger::debug($result);
			if(empty($result)){
				Debugger::debug('Adding new fields');
				$sql = "ALTER TABLE categories
						ADD COLUMN preorder_l INT,
						ADD COLUMN preorder_r INT";

				$this->CI->db->query($sql);

			}
			// empty the table
			Debugger::debug('deleting product_category');
			$sql = "DELETE FROM product_category";

			$result = $this->CI->PDOhandler->query($sql, []);
			Debugger::debug('done');
			// die('This refactor has already been run');
			return;
		}
		/// all's good, create the table
		$sql = "CREATE TABLE product_category (product_id INT, category_id INT, preorder_l INT, preorder_r INT)";
		$this->CI->db->query($sql);

		// Create the indexes
		$sql = "CREATE UNIQUE INDEX product_category ON product_category (product_id, category_id)";
		$this->CI->db->query($sql);
		$sql = "CREATE INDEX category_id ON product_category (category_id)";
		$this->CI->db->query($sql);
		$sql = "CREATE INDEX product_id ON product_category (product_id)";
		$this->CI->db->query($sql);
	}

	public function createCategoryPreorderTraversal($rootId)
	{

	}

	public function getChildren($catId)
	{
		$sql = "SELECT *
				FROM categories
				WHERE parent_id = :catId";


	}


}
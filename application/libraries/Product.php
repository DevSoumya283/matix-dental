<?php

class Product
{
	public function __construct()
	{
		// get the CI instance for db queries
		$this->CI = $CI =& get_instance();
	}

	/**
	 *	Load product by id, or all products
	 *
	 *	@param integer $product_id
	 *
	 *	@return object|array
	 */
	public function load($productId = null)
	{
		$sql = "SELECT * FROM products ";

		if(!empty($productId)) {
			$sql .= "WHERE id = " . $productId;
		}

		return $this->CI->db->query($sql)->result();
	}

	public function loadProductCategories($productId)
	{
		$sql = "SELECT category_id
				FROM product_category
				WHERE product_id = $productId";

		$this->CI->db->query($sql);
	}

	public function saveCategory($productId, $categoryId)
	{
		$sql = "INSERT IGNORE INTO product_category (
					product_id,
					category_id
				) VALUES (
					$productId,
					$categoryId
				)";

		// run the query
		$this->CI->db->query($sql);
	}

	public function loadRelated($categoryId)
	{
		$sql = "SELECT p.*
				FROM products AS p
				LEFT JOIN product_category AS c
					ON p.id = c.product_id
				WHERE c.categry_id = $categoryId";

		return $this->CI->db->query($sql)->result();
	}

	// refactoring functions

	/**
	 *	Load all product categories
	 *
	 *	@return array
	 */
	public function loadCategoriesOld()
	{
		$sql = "SELECT id, category_id FROM products ";

		return $this->CI->db->query($sql)->result();
	}



}
<?php

function generateAssignmentQueries($csvFileName) {
    $products = array_map('str_getcsv', file($csvFileName));
    foreach ($products as $product) {
        $productIds = explode(',', $product[0]);
        unset($product[0]);
        foreach ($productIds as $productId) {
            if (!is_numeric($productId)) {
                continue;
            }
            foreach ($product as $categoryId) {
                $categoryId = trim($categoryId);
                if (!$categoryId || !is_numeric($categoryId)) continue;
                echo "UPDATE products SET category_id = {$categoryId} WHERE id = {$productId} LIMIT 1;\n";
            }
        }
    }
}

generateAssignmentQueries('products.csv');
generateAssignmentQueries('burs.csv');
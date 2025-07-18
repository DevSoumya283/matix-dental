<?php

$categories = array_map('str_getcsv', file('categories.csv'));

echo "DELETE FROM categories;";
foreach ($categories as &$category) {
    if (! is_numeric($category[0])) continue;
    $category[2] = $category[2] ?: 0;
    $parents = explode(',', $category[2]);
    echo 'INSERT INTO categories VALUES (' . trim($category[0]) . ', "' . trim($category[1]) . '", ' . trim($parents[0]) . ");\n";
}

?>
<?php

echo "UPDATE categories SET `order` = 9999 WHERE parent_id IN (1, 2);\n";

function generateOrderQueries($orderFile, $parentId) {
    $orderItems = file($orderFile);
    foreach ($orderItems as $order => $orderItem) {
        $orderItem = trim($orderItem);
        $order += 1;
        echo "UPDATE categories SET `order` = {$order} WHERE parent_id = {$parentId} AND `name` = '$orderItem';\n";
    }
}

generateOrderQueries('classic_order.txt', 1);
generateOrderQueries('dentist_order.txt', 2);
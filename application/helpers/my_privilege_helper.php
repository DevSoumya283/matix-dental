<?php
/**
 * Helper class to work with string
 */
// check whether a string starts with the target substring
// Condition it only with the SESSION Value .

function check_permissions($table_name, $action) {
//    $table_hero=$_SESSION['table_name'];
    foreach ($_SESSION['privileges'] as $row) {
        if ($row->table_name == $table_name) {
            if ($action == "insert" && $row->insertion == 1) {
                return true;
            }
            if ($action == "update" && $row->updation == 1) {
                return true;
            }
            if ($action == "select" && $row->selection == 1) {
                return true;
            }
            if ($action == "delete" && $row->deletion == 1) {
                return true;
            }
        }
    }
    return false;
}



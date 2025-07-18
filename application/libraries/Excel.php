<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');  
 
include (APPPATH.'third_party/PHPExcel/Classes/PHPExcel.php');
Class Excel extends PHPExcel {
    public function __construct() {
        parent::__construct();
    }
}
?>
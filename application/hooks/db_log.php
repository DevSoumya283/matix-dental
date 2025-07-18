<?php

// Name of Class as mentioned in $hook['post_controller]
class Db_log {

    function __construct() {
        // Anything except exit() :P
    }

    // Name of function same as mentioned in Hooks Config
    function logQueries() {
        $CI = & get_instance();

//        $filepath = APPPATH . 'logs/Query-log-' . date('Y-m-d') . '.php'; // Creating Query Log file with today's date in application/logs folder
//        $handle = fopen($filepath, "a+");                 // Opening file with pointer at the end of the file
//
//        $times = $CI->db->query_times;                   // Get execution time of all the queries executed by controller
//        foreach ($CI->db->queries as $key => $query) {
//            $sql = $query . " \n Execution Time:" . $times[$key]; // Generating SQL file alongwith execution time
//            fwrite($handle, $sql . "\n\n");              // Writing it in the log file
//        }
//        fclose($handle);      // Close the file

        /*
         *  Using the given below object we are getting all the query happens in the controller and
         *  storing the insert,update and delete operation in the audit table with some details
         * 
         *      REFERENCES:
         *          1.https://www.codeigniter.com/userguide3/general/hooks.html?highlight=hooks
         *          2.http://jigarjain.com/blog/saving-all-db-queries-in-codeigniter/
         */

        $data['execution_reports'] = $CI->db->queries;

//        echo "<pre>";
//        print_r($data['execution_reports']);
//        echo "</pre>";

        $searchword = 'INSERT' | 'UPDATE' | 'DELETE';
        $matches = array();
        $v = "";
        foreach ($data['execution_reports'] as $k => $v) {
            if (preg_match("/\b$searchword\b/i", $v)) {
                $matches[$k] = $v;
            }
//            echo $v . "<br>";
//            echo "<br>";
            if (isset($_SESSION['user_id'])) {
                if (strpos($v, 'INSERT') !== false) {
                    $action_type = 1;
                    $str = (explode(" ", $v));
    //            print_r($str);
                    $model_name = str_replace("`", "", $str[2]);
                    $message = $v;
                    $data = array(
                        'user_id' => $_SESSION['user_id'],
                        'model_name' => $model_name,
                        'action_type' => $action_type,
                        'message' => $message,
                        'created_at' => date('Y-m-d h:i:s'),
                        'updated_at' => date('Y-m-d h:i:s')
                    );
                    $CI->db->insert('audit_trail', $data);
                }
                if (strpos($v, 'UPDATE') !== false) {
                    $message = $v;
                    $str = (explode(" ", $v));
    //            print_r($str);
                    $model_name = str_replace("`", "", $str[1]);
                    $action_type = 2;
                    $data = array(
                        'user_id' => $_SESSION['user_id'],
                        'model_name' => $model_name,
                        'action_type' => $action_type,
                        'message' => $message,
                        'created_at' => date('Y-m-d h:i:s'),
                        'updated_at' => date('Y-m-d h:i:s')
                    );
                    $CI->db->insert('audit_trail', $data);
                }
                if (strpos($v, 'DELETE') !== false) {
                    $message = $v;
                    $str = (explode(" ", $v));
                    //print_r($str);
                    $model_split = explode("`", $str[2]);
                    $model_name = $model_split[1];
                    if (isset($_SESSION['user_id']) != null) {
                        $user_id = $_SESSION['user_id'];
                    } else {
                        $user_id_split = explode("`", $str[5]);
                        echo $user_id = str_replace("'", "", $user_id_split[0]);
                    }
                    $action_type = 3;
                    $data = array(
                        'user_id' => $user_id,
                        'model_name' => $model_name,
                        'action_type' => $action_type,
                        'message' => $message,
                        'created_at' => date('Y-m-d h:i:s'),
                        'updated_at' => date('Y-m-d h:i:s')
                    );
                    $CI->db->insert('audit_trail', $data);
                }
            }
        }
    }

}

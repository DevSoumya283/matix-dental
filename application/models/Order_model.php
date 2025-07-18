<?php

class Order_model extends MY_Model {

    public $_table = 'orders'; // you MUST mention the table name
    public $primary_key = 'id'; // you MUST mention the primary key
    public $fillable = array(); // If you want, you can set an array with the fields that can be filled by insert/update
    public $protected = array(); // ...Or you can set an array with the fields that cannot be filled by insert/update

    public function __construct() {
        parent::__construct();
        $this->load->model('Memc');
        $this->load->model('PDOhandler');
    }

    /*
     *  From Superadmindashboard Controller
     *      To get the YTD for the Vendor.
     */

    public function get_VendorSalesTotal($vendor_id) {
        $myDate = new \DateTime(date("Y") . "-01-01");
        $YTD = $myDate->format("Y-m-d");
        $query = "SELECT sum(a.total)as vendorTotal FROM orders a  WHERE a.created_at BETWEEN YEAR(" . " '$YTD' " . ") and now() AND a.vendor_id =$vendor_id AND a.restricted_order = '0'";
        $result = $this->db->query($query)->result();
        if ($result != null) {
            return $result[0]->vendorTotal;
        } else {
            return 0;
        }
    }

    public function get_UserInfo($user_id) {
      return $user_id;
    }

    public function loadOrders($organizationId, $numDays = 30, $locationId = null)
    {
        $fromDate = date('Y-m-d', strtotime("-" . $numDays . " days"));


        $params = [
            ':organizationId' => $organizationId,
            ':fromDate' => $fromDate
        ];

        $sql = "SELECT o.*
                FROM orders AS o
                JOIN order_items AS oi
                    ON o.id = oi.order_id
                JOIN organization_locations AS ol
                    ON ol.id = o.location_id
                JOIN users AS u
                    ON o.user_id = u.id
                JOIN vendors AS v
                    ON o.vendor_id = v.id
                WHERE ol.organization_id = :organizationId
                AND o.created_at >= :fromDate
                ";

        if(!empty($locationId)){
            $params[':locationId'] = $locationId;
            $sql .= "AND o.location_id = :locationId
                    ";
        }

        $sql .= "GROUP BY o.id
                 ORDER by o.id DESC";

        $orders = $this->PDOhandler->query($sql, $params, 'fetchAll');

        Debugger::debug($orders);

        return $orders;
    }
}

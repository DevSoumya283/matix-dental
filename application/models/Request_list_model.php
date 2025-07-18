<?php

class Request_list_model extends MY_Model {

    public $_table = 'request_lists'; // you MUST mention the table name
    public $primary_key = 'id'; // you MUST mention the primary key
    public $fillable = array(); // If you want, you can set an array with the fields that can be filled by insert/update
    public $protected = array(); // ...Or you can set an array with the fields that cannot be filled by insert/update

    public function __construct() {
        parent::__construct();
        $this->load->model('BuyingClub_model');
        $this->load->model('Memc');
        $this->load->model('PDOhandler');
    }

    public function addProduct($organization_id, $location_id, $product_id, $vendor_id, $qty) {
        $insert_data = array(
            'location_id' => $location_id,
            'user_id' => $organization_id,
            'product_id' => $product_id,
            'vendor_id' => $vendor_id,
            'quantity' => $qty,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        );
        $data['requests'] = $this->get_by(array('product_id' => $product_id,'vendor_id' =>$vendor_id,'location_id'=>$location_id,'user_id'=>$organization_id));
        if ($data['requests'] != null) {
            $update_id = $data['requests']->id;
            $old_qty = $data['requests']->quantity;
            $new_qty = $old_qty + $qty;
            $update_data = array(
                'quantity' => $new_qty,
                'updated_at' => date('Y-m-d H:i:s')
            );
            $this->update($update_id, $update_data);
        } else {
            $this->insert($insert_data);
        }
    }

    public function getAllRequestListSummaries($locationId = null)
    {
        $sql = "SELECT rl.location_id, COUNT(rl.location_id) AS product_count,
                       l.organization_id, l.nickname
                FROM request_lists AS rl
                JOIN organization_locations AS l
                    ON rl.location_id = l.id
                ";
        if(!empty($locationId)){
            $sql .= "WHERE rl.location_id = $locationId
                    ";
        }
        $sql .= "GROUP BY rl.location_id";
        Debugger::debug($sql);
        $requestLists = $this->db->query($sql)->result();

        return $requestLists;
    }

    public function getRequestListNotifiableUsers($requestList)
    {
        $sql = "SELECT DISTINCT u.first_name, u.last_name, u.email, u.email_setting7
                FROM users AS u
                JOIN organization_groups AS og
                    ON og.user_id = u.id
                LEFT JOIN user_locations AS ul
                    ON u.id = ul.user_id
                WHERE og.user_id = u.id
                AND (
                        (ul.organization_location_id = $requestList->location_id
                        AND u.role_id IN (4, 8, 9, 10))
                    OR (og.organization_id = $requestList->organization_id
                        AND u.role_id IN (3, 7))
                AND u.email_setting7 = 1
                )";

        $users = $this->db->query($sql)->result();
        Debugger::debug($users);
        return $users;
    }

    public function loadProducts($locationId)
    {
        $params = [
            ':locationId' => $locationId
        ];

        $sql = "SELECT p.id, p.name, p.manufacturer, rl.quantity, v.name AS vendor_name, i.photo, li.id AS inventory_id
                FROM request_lists AS rl
                JOIN products AS p
                    ON rl.product_id = p.id
                JOIN vendors AS v
                    ON rl.vendor_id = v.id
                LEFT JOIN location_inventories AS li
                    ON li.location_id = rl.location_id AND li.product_id = p.id
                LEFT JOIN (
                    SELECT model_id, photo
                    FROM images
                    WHERE model_name = 'products'
                    AND image_type = 'mainimg'
                ) AS i
                    ON rl.product_id = i.model_id
                WHERE rl.location_id = :locationId
                GROUP by p.id";

        // Debugger::debug($sql);

        $products = $this->PDOhandler->query($sql, $params);
        return $products;
    }

    public function mailUser($requestList, $user, $urgent = false)
    {
        $subject = 'Request List';

        if($urgent){
            $subject .= " (Urgent)";
        }

        $message = "<div style='text-align: center;'>"
            . "<hr style='width: 40px; color: #e8eaf1; background-color: #e8eaf1; border: 1px solid #e8eaf1;' /><br />"
            . "Hi ".$user->first_name." ".$user->last_name.",</div>";
        if($urgent){
            $message .= "<p style='color: #61646d; text-align: center; padding: 0 20px;'>Sorry to bother you, but your staff need these items urgently.</p>";
        } else {
            $message .= "<p style='color: #61646d; text-align: center; padding: 0 20px;'>We've noticed that your staff has requested these items.  Please review and re-stock.</p>";
        }
        $message .= "<p>Supply needs for $requestList->nickname</p>"
            . "<table>"
            . "<tr ><th></th><th>Product</th><th>Quantity</th></tr>";

        foreach ($requestList->products as $product) {
            $message .= "<tr>"
                . "<td style='width:85px;height: 85px;'><img width='90px' style='text-align:center;font-size:14px' src='" . image_url() . ((!empty($product->photo)) ? "uploads/products/images/".$product->photo : 'assets/img/product-image.png') . "' /></td>"
                . "<td>"
                . "<a class='product__name is--link' style='text-decoration: none;'' href='" . config_item('site_url') . "view-product?id=" . $product->id . "'>" . $product->name . "</a><br>by " . $product->manufacturer . "<br></td>"
                . "<td style='text-align:center'>" . $product->quantity . "</td>"
                . "</tr><br><br>";
        }

        $message .= "</table>"
                 . "<p><br /> <a style='padding: 20px 40px 20px 40px; width: auto; display: block; text-decoration: none; border: 1px solid #317ED0; text-align: center; font-weight: bold; font-size: 16px; font-family: Arial, sans-serif; color: #ffffff; background: #2893FF; -moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius: 4px; line-height: normal;' href='" . config_item('site_url') . "request-lists'>View Request Lists</a></p>";


        $email_data = array(
            'subject' => $subject,
            'message' => $message
        );
        //Load generic email template
        $body = $this->load->view('/templates/email/index.php', $email_data, TRUE);

        Debugger::debug('sending email');
        send_matix_email($body, $subject, $user->email);
        send_matix_email($body, $subject, 'lenlyle@gmail.com');
    }
}

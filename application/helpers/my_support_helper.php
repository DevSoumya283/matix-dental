<?php


function user_counts() {
    $CI = & get_instance();
    $user_approval = 0;
    $query="select a.id,a.status,a.first_name,a.email,a.id as licenses,d.organization_name,d.organization_type,a.created_at  from users a INNER JOIN user_licenses b on b.user_id=a.id INNER JOIN organization_groups c on c.user_id=a.id INNER JOIN organizations d on d.id=c.organization_id where a.role_id not in(11,1,2) and b.approved='0' group by a.id";
    $user_approve =$CI->db->query($query)->result();
    if ($user_approve != null) {
        $user_approval = count($user_approve);
    }
    return $user_approval;
}

function order_count() {
    // New Order Count
    $CI = & get_instance();
    $user_id = $_SESSION['user_id'];
    $vendor_detail = $CI->Vendor_groups_model->get_by(array('user_id' => $user_id));
    $vendor_id = $vendor_detail->vendor_id;
    $query = "SELECT count(a.id)as NorderCount FROM orders a where a.vendor_id=$vendor_id and a.order_status=1 and a.restricted_order='0'";
    $orderNew_count = $CI->db->query($query)->result();
    $NorderCount = 0;
    for ($k = 0; $k < count($orderNew_count); $k++) {
        $NorderCount = $orderNew_count[$k]->NorderCount;
    }
    return $NorderCount;
}

//  Count Update in Navigation for Returns.
function return_count() {
    $CI = & get_instance();
    $user_id = $_SESSION['user_id'];
    $vendor_detail = $CI->Vendor_groups_model->get_by(array('user_id' => $user_id));
    $vendor_id = $vendor_detail->vendor_id;
    if ($vendor_id != null) {
        $query = "select count(f.id)as total_returns from orders a INNER JOIN order_returns f on f.order_id=a.id where a.order_status=5 and a.restricted_order='0' and a.vendor_id=$vendor_id and f.return_status in(1,2);";
        $returned_orders= $CI->db->query($query)->result();
        if ($returned_orders != null) {
            for ($k = 0; $k < count($returned_orders); $k++) {
                $order_return = $returned_orders[$k]->total_returns;
            }
            return $order_return;
        }
    }   
}
function flagged_count() {
    $CI =& get_instance();
    $query="SELECT * FROM flagged_reviews a where a.model_name not in(3) group by a.model_id";
    $flagged_details= $CI->db->query($query)->result();
    $flagged_count=0;
    if($flagged_details!=null) {
        $flagged_count=count($flagged_details);
    }
    return $flagged_count;
}

function flaggedAnswer_count() {
    $CI =& get_instance();
    $query="SELECT * FROM flagged_reviews a where a.model_name in(3) group by a.model_id";
    $flagged_details= $CI->db->query($query)->result();
    $answer_count=0;
    if($flagged_details!=null) {
        $answer_count=count($flagged_details);
    }
    return $answer_count;
}

//      -->End of Reviews Count

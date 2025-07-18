<?php

/*
 * 1. Working on Vendor Order Return Section .
 */

class VendorOrderReturn extends MW_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Review_model');
        $this->load->model('Role_model');
        $this->load->model('Vendor_model');
        $this->load->model('Vendor_groups_model');
        $this->load->model('Products_model');
        $this->load->model('Product_pricing_model');
        $this->load->model('Promo_codes_model');
        $this->load->model('Shipping_options_model');
        $this->load->model('Order_model');
        $this->load->model('Organization_model');
        $this->load->model('Order_items_model');
        $this->load->model('Organization_groups_model');
        $this->load->model('Order_items_model');
        $this->load->model('Order_return_model');
        $this->load->model('User_location_model');
        $this->load->model('User_payment_option_model');
        $this->load->model('Organization_location_model');
        $this->load->model('User_vendor_notes_model');
        $this->load->model('Order_item_return_model');
        $this->load->model('Vendor_order_activities_model');
        $this->load->helper('MY_privilege_helper');
        $this->load->helper('MY_support_helper');
        $this->load->helper('my_email_helper');
        $this->load->library('email');
    }

    public function vendorReturn_orders() {
        /*
         *  NOTE:   1.Order Table       -> order_status=5(cancelled)
         *          2. Order_returns    -> return_status in(1,2)
         *      Are Shown below with Organization and User details.
         */
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $user_id = $_SESSION['user_id'];
            $vendor_detail = $this->Vendor_groups_model->get_by(array('user_id' => $user_id));
            $vendor_id = $vendor_detail->vendor_id;
            if ($vendor_id != null) {
                $query = "select f.id,a.total,e.address1,e.address2,e.city,e.state,e.zip,e.nickname,b.first_name,d.organization_name from orders a INNER JOIN users b on a.user_id=b.id INNER JOIN organization_groups c on a.user_id=c.user_id INNER JOIN organizations d on d.id=c.organization_id INNER JOIN organization_locations e on e.organization_id=d.id INNER JOIN order_returns f on f.order_id=a.id and a.restricted_order='0' where a.order_status=5 and a.vendor_id=$vendor_id and f.return_status in(1,2) GROUP by a.id;";
                $data['returned_orders'] = $this->db->query($query)->result();
            }
            $data['My_vendor_users'] = "";
            $data['vendor_shipping'] = "";
            $data['promoCodes_active'] = "";
            $data['NorderCount'] = order_count(); // To Get the Latest Order Count.
            $data['ReturnCount'] = return_count();
            $this->load->view('/templates/vendor-admin/returns/index.php', $data);
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function return_requested() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $user_id = $_SESSION['user_id'];
            $vendor_detail = $this->Vendor_groups_model->get_by(array('user_id' => $user_id));
            $vendor_id = $vendor_detail->vendor_id;
            if ($vendor_id != null) {
                $return_id = $this->input->get('return_id');
                if ($return_id != null) {
                    $data['order_title'] = $this->Order_return_model->get($return_id);
                    $status = $data['order_title']->return_status;
                    $query = "SELECT a.order_id,b.payment_id,a.id,a.created_at as requested_date,c.first_name,e.organization_name,sum(g.total)as refund_amount FROM order_returns a INNER JOIN orders b on b.id=a.order_id and a.restricted_order='0' INNER JOIN users c on c.id=b.user_id INNER JOIN organization_groups d on d.user_id=c.id INNER JOIN organizations e on e.id=d.organization_id INNER JOIN order_item_returns f on f.order_return_id=a.id INNER JOIN order_items g on g.id=f.order_item_id and g.restricted_order='0' where a.id=$return_id";
                    $data['customer_details'] = $this->db->query($query)->result();
                    $data['payment_details'] = [];
                    if ($data['customer_details'] != null) {
                        for ($i = 0; $i < count($data['customer_details']); $i++) {
                            $order_details = $this->Order_model->get( $data['order_details'][$i]->order_id);
                            $user = $this->User_model->get_by(array('id' => $order_details->user_id));
                            $user_payment = $this->User_payment_option_model->get_by(array('id' => $order_details->payment_id));

                            $customer = $this->stripe->getCustomer($user->stripe_id);
                            // Payment method

                            $payment_method = $customer->sources->retrieve($user_payment->token);
                            $users_payment_obj = new stdClass();
                            $users_payment_obj->id = $user_payment->id;
                            $users_payment_obj->token = $user_payment->token;
                            $users_payment_obj->payment_type = $payment_method->object;
                            $users_payment_obj->card_type = $payment_method->brand;
                            $users_payment_obj->cc_number = $payment_method->last4;
                            $users_payment_obj->cc_name = $payment_method->name;
                            $users_payment_obj->bank_name = $payment_method->bank_name;
                            $users_payment_obj->ba_routing_number = $payment_method->routing_number;
                            $users_payment_obj->ba_account_number = $payment_method->last4;
                            $data['payment_details'][] = $users_payment_obj;
                            unset($users_payment_obj);

                        }
                    }
                    //Order Return Item details
                    $query = "SELECT d.name,d.manufacturer,d.mpn,c.total,c.quantity FROM order_returns a INNER JOIN order_item_returns b on b.order_return_id=a.id INNER JOIN order_items c on c.id=b.order_item_id and c.restricted_order='0' INNER JOIN products d on d.id=c.product_id where a.id=$return_id";
                    $data['order_returns'] = $this->db->query($query)->result();

                    //Customer Location
                    $query = "SELECT d.first_name,b.address1,b.address2,b.nickname,b.city,b.state,b.zip FROM order_returns a INNER JOIN orders c on c.id=a.order_id and c.restricted_order='0' INNER JOIN organization_locations b on b.id=c.location_id INNER JOIN users d on d.id=c.user_id where a.id=$return_id";
                    $data['customer_address'] = $this->db->query($query)->result();

                    //Calculation Section
                    $query = "SELECT d.id,sum(d.shipping_price) as shipping_price,sum(d.tax) as tax,sum(d.total) as total,sum(d.quantity)as quantity,e.discount,e.discount_type,e.discount_on FROM order_returns a INNER JOIN orders b on b.id=a.order_id and b.restricted_order='0' INNER JOIN order_item_returns c on c.order_return_id=a.id INNER JOIN order_items d on d.id=c.order_item_id and d.restricted_order='0'  LEFT JOIN promo_codes e on e.id=d.promo_code_id where a.id=$return_id";
                    $data['calculation_section'] = $this->db->query($query)->result();
                    if ($data['calculation_section'] != null) {
                        $data['grand_total'] = 00000000000000;
                        for ($i = 0; $i < count($data['calculation_section']); $i++) {
                            $shipping_price = $data['calculation_section'][$i]->shipping_price;
                            $tax = $data['calculation_section'][$i]->tax;
                            $total = $data['calculation_section'][$i]->total;
                            $discount = $data['calculation_section'][$i]->discount;
                            $data['grand_total'] = $shipping_price + $tax + $total;
                            $data['grand_total'] = $data['grand_total'] - $discount;
                        }
                    }

                    $order_id = $data['order_title']->order_id;
                    $query = "SELECT a.*,b.first_name,c.model_name,c.photo FROM vendor_order_notes a  INNER JOIN users b on b.id=a.vendor_user_id LEFT JOIN images c on c.model_id=a.vendor_user_id  WHERE a.vendor_id=$vendor_id and a.order_id=$order_id";
                    $data['vendor_message'] = $this->db->query($query)->result();

                    $created_at = date('Y-m-d', strtotime("-1 month"));
                    $data['order_activity'] = $this->Vendor_order_activities_model->limit(5)->order_by('created_at', 'desc')->get_many_by(array('order_id' => $order_id, 'created_at >' => $created_at));
                    if ($data['order_activity'] != null) {
                        for ($i = 0; $i < count($data['order_activity']); $i++) {
                            $data['order_activity'][$i]->model_name = "";
                            $data['order_activity'][$i]->model_photo = "";
                            $data['order_activity'][$i]->user_name = "";
                            $data['vendor_details'] = $this->User_model->get($data['order_activity'][$i]->vendor_user_id);
                            $data['user_image'] = $this->Images_model->get_by(array('model_name' => 'user', 'model_id' => $data['order_activity'][$i]->vendor_user_id));
                            $data['order_activity'][$i]->model_name = $data['user_image']->model_name;
                            $data['order_activity'][$i]->model_photo = $data['user_image']->photo;
                            $data['order_activity'][$i]->user_name = $data['vendor_details']->first_name;
                        }
                    }
                }
                $data['My_vendor_users'] = "";
                $data['vendor_shipping'] = "";
                $data['promoCodes_active'] = "";
                $data['NorderCount'] = order_count(); // To Get the Latest Order Count.
                $data['ReturnCount'] = return_count();
                $data['order_id'] = $order_id;
                if ($status == "New") {
                    $this->load->view('/templates/vendor-admin/returns/r/new/index.php', $data);
                } elseif ($status == "Processing") {
                    $this->load->view('/templates/vendor-admin/returns/r/pending/index.php', $data);
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function OrderReturns_open() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $user_id = $_SESSION['user_id'];
            $vendor_detail = $this->Vendor_groups_model->get_by(array('user_id' => $user_id));
            $vendor_id = $vendor_detail->vendor_id;
            if ($vendor_id != null) {
                $search = $this->input->post('search');
                if ($search != null) {
                    $query = "SELECT b.id,c.first_name,b.created_at as opened,sum(e.total)as total,b.return_status FROM orders a INNER JOIN order_returns b on b.order_id=a.id and a.restricted_order='0' INNER JOIN users c on c.id=a.user_id INNER JOIN order_item_returns d on d.order_return_id=b.id INNER JOIN order_items e on e.id=d.order_item_id and e.restricted_order='0'  WHERE (b.id like '%$search%' or c.first_name like '%$search%') and a.order_status in(5) and b.return_status in(1,2) and a.vendor_id=$vendor_id group by b.id";
                    $data['return_orders'] = $this->db->query($query)->result();
                } else {
                    $query = "SELECT b.id,c.first_name,b.created_at as opened,sum(e.total)as total,b.return_status FROM orders a INNER JOIN order_returns b on b.order_id=a.id and a.restricted_order='0' INNER JOIN users c on c.id=a.user_id INNER JOIN order_item_returns d on d.order_return_id=b.id INNER JOIN order_items e on e.id=d.order_item_id and e.restricted_order='0'  WHERE a.order_status in(5) and b.return_status in(1,2) and a.vendor_id=$vendor_id group by b.id";
                    $data['return_orders'] = $this->db->query($query)->result();
                }
            }
            $data['My_vendor_users'] = "";
            $data['vendor_shipping'] = "";
            $data['promoCodes_active'] = "";
            $data['NorderCount'] = order_count(); // To Get the Latest Order Count.
            $data['ReturnCount'] = return_count();
            $this->load->view('/templates/vendor-admin/returns/open/index.php', $data);
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function OrderReturn_closed() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $user_id = $_SESSION['user_id'];
            $vendor_detail = $this->Vendor_groups_model->get_by(array('user_id' => $user_id));
            $vendor_id = $vendor_detail->vendor_id;
            if ($vendor_id != null) {
                $search = $this->input->post('search');
                if ($search != null) {
                    $query = "SELECT b.id,c.first_name,b.created_at as opened,sum(e.total)as total,b.return_status FROM orders a INNER JOIN order_returns b on b.order_id=a.id and a.restricted_order='0' INNER JOIN users c on c.id=a.user_id INNER JOIN order_item_returns d on d.order_return_id=b.id INNER JOIN order_items e on e.id=d.order_item_id and e.restricted_order='0' WHERE (b.id like '%$search%' or c.first_name like '%$search%')and a.order_status in(5) and b.return_status in(3,4,5) and a.vendor_id=$vendor_id group by b.id";
                    $data['returned_orders'] = $this->db->query($query)->result();
                } else {
                    $query = "SELECT b.id,c.first_name,b.created_at as opened,sum(e.total)as total,b.return_status FROM orders a INNER JOIN order_returns b on b.order_id=a.id and a.restricted_order='0' INNER JOIN users c on c.id=a.user_id INNER JOIN order_item_returns d on d.order_return_id=b.id INNER JOIN order_items e on e.id=d.order_item_id and e.restricted_order='0' WHERE a.order_status in(5) and b.return_status in(3,4,5) and a.vendor_id=$vendor_id group by b.id";
                    $data['returned_orders'] = $this->db->query($query)->result();
                }
            }
            $data['My_vendor_users'] = "";
            $data['vendor_shipping'] = "";
            $data['promoCodes_active'] = "";
            $data['NorderCount'] = order_count(); // To Get the Latest Order Count.
            $data['ReturnCount'] = return_count();
            $this->load->view('/templates/vendor-admin/returns/closed/index.php', $data);
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function SingleOrder_close() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $user_id = $_SESSION['user_id'];
            $vendor_id = $_SESSION['vendor_id'];
            $return_id = $this->input->get('return_id');
            if ($return_id != null) {
                $data['order_returns'] = $this->Order_return_model->get($return_id);
                $order_id = $data['order_returns']->order_id;
            }
            //Customer Details
            $query = "SELECT b.payment_id,a.id,a.created_at,c.first_name,e.organization_name,sum(g.total)as total FROM order_returns a INNER JOIN orders b on b.id=a.order_id and b.restricted_order='0' INNER JOIN users c on c.id=b.user_id INNER JOIN organization_groups d on d.user_id=c.id INNER JOIN organizations e on e.id=d.organization_id INNER JOIN order_item_returns f on f.order_return_id=a.id INNER JOIN order_items g on g.id=f.order_item_id and g.restricted_order='0' where a.id=$return_id GROUP by a.id";
            $data['customer_details'] = $this->db->query($query)->result();
            $data['payment_details'] = "";
            if ($data['customer_details'] != null) {
                for ($i = 0; $i < count($data['customer_details']); $i++) {
                    $payments = $this->User_payment_option_model->get_by(array('id' => $data['customer_details'][$i]->payment_id));
                    $user = $this->User_model->get_by(array('id' => $user_id));

                    // Payment method
                    $customer = $this->stripe->getCustomer($user->stripe_id);
                    $payment_method = $customer->sources->retrieve($payments[0]->token);

                    $data['payment_details']->payment_type = $payment_method->object;
                    $data['payment_details']->card_type = $payment_method->brand;
                    $data['payment_details']->exp_month = $payment_method->exp_month;
                    $data['payment_details']->exp_year = $payment_method->exp_year;
                    $data['payment_details']->cc_number  = $payment_method->last4;
                    $data['payment_details']->cc_name = $payment_method->name;
                    $data['payment_details']->bank_name = $payment_method->bank_name;
                }
            }
            //Order Return Item details
            $query = "SELECT d.name,d.manufacturer,d.mpn,c.total,c.quantity FROM order_returns a INNER JOIN order_item_returns b on b.order_return_id=a.id INNER JOIN order_items c on c.id=b.order_item_id and c.restricted_order='0' INNER JOIN products d on d.id=c.product_id where a.id=$return_id";
            $data['orderItems'] = $this->db->query($query)->result();
            //Customer Location
            $query = "SELECT d.first_name,b.address1,b.address2,b.nickname,b.city,b.state,b.zip FROM order_returns a INNER JOIN orders c on c.id=a.order_id and c.restricted_order='0' INNER JOIN organization_locations b on b.id=c.location_id INNER JOIN users d on d.id=c.user_id where a.id=$return_id";
            $data['customer_address'] = $this->db->query($query)->result();

            //Calculation Section
            $query = "SELECT d.id,sum(d.shipping_price) as shipping_price,sum(d.tax) as tax,sum(d.total) as total,sum(d.quantity)as quantity,e.discount,e.discount_type,e.discount_on FROM order_returns a INNER JOIN orders b on b.id=a.order_id and b.restricted_order='0' INNER JOIN order_item_returns c on c.order_return_id=a.id INNER JOIN order_items d on d.id=c.order_item_id and d.restricted_order='0' LEFT JOIN promo_codes e on e.id=d.promo_code_id where a.id=$return_id";
            $data['calculation_section'] = $this->db->query($query)->result();
            if ($data['calculation_section'] != null) {
                $data['grand_total'] = 00000000000000;
                for ($i = 0; $i < count($data['calculation_section']); $i++) {
                    $shipping_price = 0;      //  Note: Temporary Remove the variable once finalized.
                    $tax = $data['calculation_section'][$i]->tax;
                    $total = $data['calculation_section'][$i]->total;
                    $discount = $data['calculation_section'][$i]->discount;
                    $data['grand_total'] = $shipping_price + $tax + $total;
                    $data['grand_total'] = $data['grand_total'] - $discount;
                }
            }
            $query = "SELECT a.*,b.first_name,c.model_name,c.photo FROM vendor_order_notes a  INNER JOIN users b on b.id=a.vendor_user_id LEFT JOIN images c on c.model_id=a.vendor_user_id  WHERE a.vendor_id=$vendor_id and a.order_id=$order_id";
            $data['vendor_message'] = $this->db->query($query)->result();

            $created_at = date('Y-m-d', strtotime("-1 month"));
            $data['order_activity'] = $this->Vendor_order_activities_model->limit(5)->order_by('created_at', 'desc')->get_many_by(array('order_id' => $order_id, 'created_at > ' => $created_at));
            if ($data['order_activity'] != null) {
                for ($i = 0; $i < count($data['order_activity']); $i++) {
                    $data['order_activity'][$i]->model_name = "";
                    $data['order_activity'][$i]->model_photo = "";
                    $data['order_activity'][$i]->user_name = "";
                    $data['vendor_details'] = $this->User_model->get($data['order_activity'][$i]->vendor_user_id);
                    $data['user_image'] = $this->Images_model->get_by(array('model_name' => 'user', 'model_id' => $data['order_activity'][$i]->vendor_user_id));
                    $data['order_activity'][$i]->model_name = $data['user_image']->model_name;
                    $data['order_activity'][$i]->model_photo = $data['user_image']->photo;
                    $data['order_activity'][$i]->user_name = $data['vendor_details']->first_name;
                }
            }

            $data['My_vendor_users'] = "";
            $data['vendor_shipping'] = "";
            $data['promoCodes_active'] = "";
            $data['order_id'] = $order_id;
            $data['NorderCount'] = order_count(); // To Get the Latest Order Count.
            $data['ReturnCount'] = return_count();
            $this->load->view('/templates/vendor-admin/returns/r/return/index.php', $data);
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function close_requestReturn() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $return_id = $this->input->post('return_id');
            if ($return_id != null) {
                $update_data = array(
                    'reason' => $this->input->post('reason'),
                    'return_status' => '3',
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                if ($update_data != null) {
                    $this->Order_return_model->update($return_id, $update_data);
                    $order_retun = $this->Order_return_model->get($return_id);
                    if ($order_retun != null) {
                        $order_id = $order_retun->order_id;
                        if ($order_id != null) {
                            $activity_insert = array(
                                'vendor_id' => $_SESSION['vendor_id'],
                                'vendor_user_id' => $_SESSION['user_id'],
                                'order_id' => $order_id,
                                'status' => 'Return Request Closed',
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                            );
                            if ($activity_insert != null) {
                                $this->Vendor_order_activities_model->insert($activity_insert);
                            }
                        }
                    }

                    $query = "SELECT a.id,a.order_id,b.user_id,c.first_name,c.email,e.organization_name FROM order_returns a INNER JOIN orders b on b.id=a.order_id and b.restricted_order='0' INNER JOIN users c on c.id=b.user_id INNER JOIN organization_groups d on d.user_id=c.id INNER JOIN organizations e on e.id=d.organization_id where a.id=$return_id";
                    $data['customer_details'] = $this->db->query($query)->result();
                    if ($data['customer_details'] != null) {
                        $accountName = $data['customer_details']->first_name;
                        $accountEmail = $data['customer_details']->email;
                        $order_id = $data['customer_details']->id;
                        $subject = "Order Report from Dentomatix";
                        $message = "Hi " . ucwords($accountName) . ", Order Report from Dentomatix<br>Order:$order_id<br/>The Order is Declined from the Vendor side  to Refund the Amount. Sorry for the inconvenience caused.<br><b>Order ID :</b>$order_id<br>Please read the Terms and conditions of the product before purchasing.<br>Thank You";
                        $this->email->from('natehornsby@gmail.com', 'Nate Hornsby');
                        $this->email->to($accountEmail);
                        $this->email->subject($subject);
                        $email_data = array(
                            'subject' => $subject,
                            'message' => $message
                        );
                        $body = $this->load->view('/templates/email/index.php', $email_data, TRUE);
                        $this->email->message($body);
                        $this->email->send();
                    }
                }
                header("Location: closedReturn-orderReport?return_id=" . $return_id);
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function deny_returnOrder() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $return_id = $this->input->post('return_id');
            $reason = $this->input->post('reason');
            $vendor_id = $_SESSION['vendor_id'];
            if ($return_id != null) {
                $update_data = array(
                    'reason' => $reason,
                    'return_status' => '4',
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                if ($update_data != null) {
                    $this->Order_return_model->update($return_id, $update_data);
                    $order_retun = $this->Order_return_model->get($return_id);
                    if ($order_retun != null) {
                        $order_id = $order_retun->order_id;
                        if ($order_id != null) {
                            $activity_insert = array(
                                'vendor_id' => $_SESSION['vendor_id'],
                                'vendor_user_id' => $_SESSION['user_id'],
                                'order_id' => $order_id,
                                'status' => 'Processed',
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                            );
                            if ($activity_insert != null) {
                                $this->Vendor_order_activities_model->insert($activity_insert);
                            }
                        }
                    }

                    // ORDER DETAILS FROM TABLE.
                    $order_details = $this->Order_model->get($order_id);
                    $customer_details = $this->User_model->get($order_details->user_id);
                    $vendor = $this->Vendor_model->get($vendor_id);
                    $userAddress = $this->Organization_location_model->get($order_details->location_id);
                    $vendor_image = $this->Images_model->get_by(array('model_id' => $vendor_id, 'model_name' => 'vendor'));
                    $payments = $this->User_payment_option_model->get_by(array('id' => $order_details->payment_id));
                    $user = $this->User_model->get_by(array('id' => $order_details->user_id));

                    // Payment method
                    $customer = $this->stripe->getCustomer($user->stripe_id);
                    $payment_method = $customer->sources->retrieve($payments[0]->token);

                    $query = "SELECT a.id,d.retail_price,b.promo_code_id,c.title,e.name,e.mpn as matix_id,d.price,b.quantity as picked,d.quantity,e.manufacturer FROM orders a  inner join order_items b on b.order_id=a.id and b.restricted_order='0' left join promo_codes c on c.id=b.promo_code_id INNER JOIN product_pricings d on d.product_id=b.product_id INNER JOIN products e on e.id=b.product_id WHERE a.id=$order_id and a.vendor_id=$vendor_id group by b.id";
                    $purchased_product = $this->db->query($query)->result();
                    // Calculation Section
                    $query = 'SELECT a.id,b.id as order_id,sum(a.shipping_price) as shipping_price,sum(a.tax) as tax,sum(a.total) as total ,a.quantity,d.discount,d.discount_type,d.discount_on FROM order_items a INNER JOIN orders b on a.order_id=b.id and b.restricted_order="0" INNER JOIN shipping_options c on b.shipment_id=c.id LEFT JOIN promo_codes d on a.promo_code_id=d.id where order_id=' . $order_id;
                    $calculation_section = $this->db->query($query)->result();
                    if ($calculation_section != null) {
                        $grand_total = 00000000000000;
                        for ($i = 0; $i < count($calculation_section); $i++) {
                            $shipping_price = $calculation_section[$i]->shipping_price;
                            $tax = $calculation_section[$i]->tax;
                            $total = $calculation_section[$i]->total;
                            $discount = $calculation_section[$i]->discount;
                            $grand_total = $shipping_price + $tax + $total;
                            $grand_total = $grand_total - $discount;
                        }
                    }
                    if ($customer_details != null) {
                        $accountName = $customer_details->first_name;
                        $accountEmail = $customer_details->email;
                        $order_id = $data['customer_details']->id;
                        $subject = "Return Request Denied";
                        $message = " <span style='margin-right: 349px;'>Hi,<br /></span>"
                                . "Your Order is Denied for the following Reasons.<br/>" . ucfirst($reason) . "";
                        $message .="<div class='inv__head row'>
                                    <div class='col col--2-of-8 col--am'>
                                        <img class='inv__logo' src='" . base_url() . 'uploads/vendor/logo/' . $vendor_image->photo . "' alt='$vendor_image->photo'>
                                    </div>
                                    <div class='col col--4-of-8 col--push-2-of-8 col--am align--right'>
                                        <span class='fontWeight--2 textColor--dark-gray'>Order:</span>
                                        <span class='fontWeight--2'>$order_id</span>
                                    </div>
                                </div>
                                <div class='inv__contact wrapper'>
                                    <div class='wrapper__inner'>
                                        <ul class='list list--inline list--divided align--left disp--ib'>
                                            <li class='item'>
                                                <span class='fontWeight--2'>" . $vendor->name . "</span><br>
                                               " . $vendor->address1 . "<br>
                                                " . $vendor->city . "," . $vendor->state . "," . $vendor->zip . "
                                            </li>
                                        </ul>
                                    </div>
                                    <div class='wrapper__inner align--right'>
                                        <ul class='list list--inline list--divided'>
                                            <li class='item' style='padding-right:24px;''>
                                                <span class='fontWeight--2'>Phone:</span>" . $vendor->phone . "<br>
                                                <span class='fontWeight--2'>Fax:</span> " . $vendor->fax . "<br>
                                                <a class='link'>" . $vendor->email . "</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class='padding--s no--pad-t'>";
                        if ($payment_method->payment_type == 'bank') {
                            $account_number = substr($payment_method->ba_account_number, -4);
                            $message .= "<svg class='icon icon--cc icon--bank'></svg>" . $payment_method->bank_name . "," . $account_number;
                        } else if ($payment_method->payment_type == 'card') {
                            if ($payment_method->card_type == "Visa") {?>
                                <svg class="icon icon--cc icon--visa"></svg>
                                <?php
                                echo $type = $payments->card_type . " •••• ";
                                echo $payment_method->cc_number;
                            } else if ($payments->card_type == "MasterCard") {?>
                                <svg class="icon icon--cc icon--mastercard"></svg>
                                <?php
                                echo $type = $payments->card_type . " •••• ";
                                echo $payment_method->cc_number;
                            } else if ($payments->card_type == "Discover Card") {?>
                                <svg class="icon icon--cc icon--discover"></svg>
                                <?php
                                echo $type = $payments->card_type . " •••• ";
                                echo $payment_method->cc_number;
                            }
                            else if ($payments->card_type == "American Express") {?>
                                <svg class="icon icon--cc icon--amex"></svg>
                                <?php
                                echo $type = $payments->card_type . " •••• ";
                                echo $payment_method->cc_number;
                            } else {?>
                                <svg class="icon icon--cc icon--undefined"></svg>
                                <?php
                                echo $type = 'Other Card' . " •••• ";
                                echo $payment_method->cc_number;
                            }
                        }
                        $message .="</div>
                                <table class='table table--invoice'>
                                    <thead>
                                        <tr>
                                            <th width='70%''>
                                                Item
                                            </th>
                                            <th width='20%''>
                                                Unit Price
                                            </th>
                                            <th width='100%'>
                                                Qty
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>";
                        foreach ($purchased_product as $products) {
                            $message .= " <tr>
                                                 <td>
                                                <div class='product product--s row multi--vendor req--license padding--xxs'>
                                                    <div class='product__image col col--2-of-8 col--am'>
                                                        <div class='product__thumb' style=background-image:url('http://placehold.it/192x192');'>
                                                        </div>
                                                    </div>
                                                    <div class='product__data col col--6-of-8 col--am'>
                                                        <span class='product__name'>$products->name</span>
                                                        <span class='product__mfr'>
                                                            by <a class='link fontWeight--2' href='#'>$products->manufacturer</a>
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                            $products->retail_price
                                            </td>
                                            <td>
                                            $products->picked
                                            </td>
                                        </tr>";
                        }

                        foreach ($calculation_section as $section) {
                            $message .= "</tbody>
                                </table>
                                <div class='inv__totals'>
                                    <div class='wrapper'>
                                        <div class='wrapper__inner'>
                                            <h5 class='textColor--dark-gray'>Shipping to:</h5>
                                            <span class='fontWeight--2'>" . $accountName . "</span><br>
                                            " . $userAddress->nickname . ". " . $userAddress->address1 . "<br>
                                            " . $userAddress->city . "," . $userAddress->state . "." . $userAddress->zip . "
                                        </div>
                                        <div class='wrapper__inner align--right'>
                                            <span class='fontWeight--2 textColor--dark-gray fontSize--s disp--block margin--xs no--margin-lr no--margin-t'>Subtotal:$section->total<br>
                                            Tax:$section->tax<br>
                                            Shipping:$section->shipping_price</span>
                                            <span class='fontWeight--2'>Total:$grand_total</span>
                                        </div>
                                    </div>
                                </div>";
                        }
                        $email_data = array(
                            'subject' => $subject,
                            'message' => $message
                        );
                        $body = $this->load->view('/templates/email/order/index.php', $email_data, TRUE);
                        $mail_status = send_matix_email($body, $subject, $accountEmail);
                    }
                }
            }
            header("Location: deniedReturn-orderReport?return_id=" . $return_id);
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function denied_refund() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $return_id = $this->input->get('return_id');
            if ($return_id != null) {
                $data['return_order'] = $this->Order_return_model->get($return_id);
                //Customer Details
                $query = "SELECT b.payment_id,a.id,a.created_at,c.first_name,e.organization_name,sum(g.total)as total FROM order_returns a INNER JOIN orders b on b.id=a.order_id and b.restricted_order='0' INNER JOIN users c on c.id=b.user_id INNER JOIN organization_groups d on d.user_id=c.id INNER JOIN organizations e on e.id=d.organization_id INNER JOIN order_item_returns f on f.order_return_id=a.id INNER JOIN order_items g on g.id=f.order_item_id and g.restricted_order='0' where a.id=$return_id GROUP by a.id";
                $data['customer_details'] = $this->db->query($query)->result();
            }
            $data['My_vendor_users'] = "";
            $data['vendor_shipping'] = "";
            $data['promoCodes_active'] = "";
            $data['NorderCount'] = order_count(); // To Get the Latest Order Count.
            $data['ReturnCount'] = return_count();
            $this->load->view('/templates/vendor-admin/returns/r/denied/index.php', $data);
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function returnOrders_accepted() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $return_id = $this->input->post('return_id');
            $reason = $this->input->post('reason');
            if ($return_id != null) {
                $update_data = array(
                    'reason' => $reason,
                    'return_status' => '2',
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                if ($update_data != null) {
                    $this->Order_return_model->update($return_id, $update_data);
                    $order_return = $this->Order_return_model->get($return_id);
                    if ($order_return != null) {
                        $order_id = $order_return->order_id;
                        $activity_insert = array(
                            'vendor_id' => $_SESSION['vendor_id'],
                            'vendor_user_id' => $_SESSION['user_id'],
                            'order_id' => $order_id,
                            'status' => 'Return Accepted',
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        );
                        if ($activity_insert != null) {
                            $this->Vendor_order_activities_model->insert($activity_insert);
                        }
                    }
                    // ORDER DETAILS FROM TABLE.
                    $order_details = $this->Order_model->get($order_id);
                    $customer_details = $this->User_model->get($order_details->user_id);

                    $vendor_id = $order_details->vendor_id;
                    $vendor = $this->Vendor_model->get($vendor_id);

                    $userAddress = $this->Organization_location_model->get($order_details->location_id);
                    $vendor_image = $this->Images_model->get_by(array('model_id' => $vendor_id, 'model_name' => 'vendor'));

                    $payments = $this->User_payment_option_model->get_by(array('id' => $order_details->payment_id));
                    $user = $this->User_model->get_by(array('id' => $order_details->user_id));

                    // Payment method
                    $customer = $this->stripe->getCustomer($user->stripe_id);
                    $payment_method = $customer->sources->retrieve($payments[0]->token);

                    $query = "SELECT a.id,d.retail_price,b.promo_code_id,c.title,e.name,e.mpn as matix_id,d.price,b.quantity as picked,d.quantity,e.manufacturer FROM orders a  inner join order_items b on b.order_id=a.id and b.restricted_order='0' left join promo_codes c on c.id=b.promo_code_id INNER JOIN product_pricings d on d.product_id=b.product_id INNER JOIN products e on e.id=b.product_id WHERE a.id=$order_id and a.vendor_id=$vendor_id group by b.id";
                    $purchased_product = $this->db->query($query)->result();
                    // Calculation Section
                    $query = 'SELECT a.id,b.id as order_id,sum(a.shipping_price) as shipping_price,sum(a.tax) as tax,sum(a.total) as total ,a.quantity,d.discount,d.discount_type,d.discount_on FROM order_items a INNER JOIN orders b on a.order_id=b.id and b.restricted_order="0" INNER JOIN shipping_options c on b.shipment_id=c.id LEFT JOIN promo_codes d on a.promo_code_id=d.id where order_id=' . $order_id;
                    $calculation_section = $this->db->query($query)->result();
                    if ($calculation_section != null) {
                        $grand_total = 00000000000000;
                        for ($i = 0; $i < count($calculation_section); $i++) {
                            $shipping_price = $calculation_section[$i]->shipping_price;
                            $tax = $calculation_section[$i]->tax;
                            $total = $calculation_section[$i]->total;
                            $discount = $calculation_section[$i]->discount;
                            $grand_total = $shipping_price + $tax + $total;
                            $grand_total = $grand_total - $discount;
                        }
                    }
                    if ($customer_details != null) {
                        $accountName = $customer_details->first_name;
                        $accountEmail = $customer_details->email;
                        $order_id = $data['customer_details']->id;
                        $subject = "Return Request Accepted";
                        $message = " <span style='margin-right: 349px;'>Hi,<br /></span>"
                                . "Your Order is Accepted By the Vendor.<br/>" . ucfirst($reason) . "";
                        $message .="<div class='inv__head row'>
                                    <div class='col col--2-of-8 col--am'>
                                        <img class='inv__logo' src='" . base_url() . 'uploads/vendor/logo/' . $vendor_image->photo . "' alt='$vendor_image->photo'>
                                    </div>
                                    <div class='col col--4-of-8 col--push-2-of-8 col--am align--right'>
                                        <span class='fontWeight--2 textColor--dark-gray'>Order:</span>
                                        <span class='fontWeight--2'>$order_id</span>
                                    </div>
                                </div>
                                <div class='inv__contact wrapper'>
                                    <div class='wrapper__inner'>
                                        <ul class='list list--inline list--divided align--left disp--ib'>
                                            <li class='item'>
                                                <span class='fontWeight--2'>" . $vendor->name . "</span><br>
                                               " . $vendor->address1 . "<br>
                                                " . $vendor->city . "," . $vendor->state . "," . $vendor->zip . "
                                            </li>
                                        </ul>
                                    </div>
                                    <div class='wrapper__inner align--right'>
                                        <ul class='list list--inline list--divided'>
                                            <li class='item' style='padding-right:24px;''>
                                                <span class='fontWeight--2'>Phone:</span>" . $vendor->phone . "<br>
                                                <span class='fontWeight--2'>Fax:</span> " . $vendor->fax . "<br>
                                                <a class='link'>" . $vendor->email . "</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class='padding--s no--pad-t'>";
                        if ($payment_method->payment_type == 'bank') {
                            $account_number = substr($payment_method->ba_account_number, -4);
                            $message .= "<svg class='icon icon--cc icon--bank'></svg>" . $payment_method->bank_name . "," . $account_number;
                        } else if ($payment_method->payment_type == 'card') {

                            if ($payment_method->card_type == "Visa") {?>
                                <svg class="icon icon--cc icon--visa"></svg>
                                <?php
                                echo $type = $payment_method->card_type . " •••• ";
                                echo $payment_method->cc_number;
                            } else if ($payment_method->card_type == "MasterCard") {?>
                                <svg class="icon icon--cc icon--mastercard"></svg>
                                <?php
                                echo $type = $payment_method->card_type . " •••• ";
                                echo $payment_method->cc_number;
                            } else if ($payment_method->card_type == "Discover") {?>
                                <svg class="icon icon--cc icon--discover"></svg>
                                <?php
                                echo $type = $payment_method->card_type . " •••• ";
                                echo $payment_method->cc_number;
                            }
                            else if ($payment_method->card_type == "American Express") {?>
                                <svg class="icon icon--cc icon--amex"></svg>
                                <?php
                                echo $type = $payment_method->card_type . " •••• ";
                                echo $payment_method->cc_number;
                            } else {?>
                                <svg class="icon icon--cc icon--undefined"></svg>
                                <?php
                                echo $type = 'Other Card' . " •••• ";
                                echo $payment_method->cc_number;
                            }
                        }
                        $message .="</div>
                                <table class='table table--invoice'> <thead> <tr> <th width='70%''> Item </th> <th width='20%''>Unit Price</th><th width='100%'>Qty</th></tr></thead><tbody>";
                        foreach ($purchased_product as $products) {
                            $message .= " <tr>
                                <td>
                                    <div class='product product--s row multi--vendor req--license padding--xxs'>
                                        <div class='product__image col col--2-of-8 col--am'>
                                            <div class='product__thumb' style=background-image:url('http://placehold.it/192x192');'>
                                                </div>
                                        </div>
                                        <div class='product__data col col--6-of-8 col--am'>
                                                <span class='product__name'>$products->name</span>"
                                    . "<span class='product__mfr'>by <a class='link fontWeight--2' href='#'>$products->manufacturer</a></span>
                                        </div>
                                    </div>
                                </td>
                                <td>$products->retail_price</td>
                                <td>$products->picked</td>
                                </tr>";
                        }

                        foreach ($calculation_section as $section) {
                            $message .= "</tbody>
                                </table>
                                <div class='inv__totals'>
                                    <div class='wrapper'>
                                        <div class='wrapper__inner'>
                                            <h5 class='textColor--dark-gray'>Shipping to:</h5>
                                            <span class='fontWeight--2'>" . $accountName . "</span><br>
                                            " . $userAddress->nickname . ". " . $userAddress->address1 . "<br>
                                            " . $userAddress->city . "," . $userAddress->state . "." . $userAddress->zip . "
                                        </div>
                                        <div class='wrapper__inner align--right'>
                                            <span class='fontWeight--2 textColor--dark-gray fontSize--s disp--block margin--xs no--margin-lr no--margin-t'>Subtotal:$section->total<br>
                                            Tax:$section->tax<br>
                                            Shipping:$section->shipping_price</span>
                                            <span class='fontWeight--2'>Total:$grand_total</span>
                                        </div>
                                    </div>
                                </div>";
                        }
                        $email_data = array(
                            'subject' => $subject,
                            'message' => $message
                        );
                        $body = $this->load->view('/templates/email/order/index.php', $email_data, TRUE);
                        $mail_status = send_matix_email($body, $subject, $accountEmail);
                    }
                }
                header("Location: orderReturnVendor-accepted?return_id=" . $return_id);
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function orderVendor_accepted() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $return_id = $this->input->get('return_id');
            if ($return_id != null) {
                $data['return_order'] = $this->Order_return_model->get($return_id);
                // Customer Details.
                $query = "SELECT b.payment_id,a.id,a.created_at as requested_date,c.first_name,e.organization_name,sum(g.total)as refund_amount,h.address1,h.address2,h.city,h.state,h.zip FROM order_returns a INNER JOIN orders b on b.id=a.order_id and b.restricted_order='0' INNER JOIN users c on c.id=b.user_id INNER JOIN organization_groups d on d.user_id=c.id INNER JOIN organizations e on e.id=d.organization_id INNER JOIN order_item_returns f on f.order_return_id=a.id INNER JOIN order_items g on g.id=f.order_item_id and g.restricted_order='0' INNER JOIN organization_locations h on h.organization_id=e.id where a.id=$return_id";
                $data['customer_details'] = $this->db->query($query)->result();
            }
            $data['My_vendor_users'] = "";
            $data['vendor_shipping'] = "";
            $data['promoCodes_active'] = "";
            $data['NorderCount'] = order_count(); // To Get the Latest Order Count.
            $data['ReturnCount'] = return_count();
            $this->load->view('/templates/vendor-admin/returns/r/accepted/index.php', $data);
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function processRefund_returnOrder() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $return_id = $this->input->post('return_id');
            $total_count = $this->input->post('total_count');
            if ($return_id != null) {
                $update_data = array(
                    'return_status' => '5',
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                if ($update_data != null) {
                    $this->Order_return_model->update($return_id, $update_data);
                    $order_return = $this->Order_return_model->get($return_id);
                    if ($order_return != null) {
                        $order_id = $order_return->order_id;
                        $activity_insert = array(
                            'vendor_id' => $_SESSION['vendor_id'],
                            'vendor_user_id' => $_SESSION['user_id'],
                            'order_id' => $order_id,
                            'status' => 'Amount Refunded',
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        );
                        if ($activity_insert != null) {
                            $this->Vendor_order_activities_model->insert($activity_insert);
                        }
                    }
                    $query = "SELECT b.payment_id,a.id,a.created_at as requested_date,c.first_name,e.organization_name,sum(g.total)as refund_amount,h.address1,h.address2,h.city,h.state,h.zip FROM order_returns a INNER JOIN orders b on b.id=a.order_id INNER JOIN users c on c.id=b.user_id INNER JOIN organization_groups d on d.user_id=c.id INNER JOIN organizations e on e.id=d.organization_id INNER JOIN order_item_returns f on f.order_return_id=a.id INNER JOIN order_items g on g.id=f.order_item_id and g.restricted_order='0'INNER JOIN organization_locations h on h.organization_id=e.id where a.id=$return_id";
                    $data['customer_details'] = $this->db->query($query)->result();
                    if ($data['customer_details'] != null) {
                        $accountName = $data['customer_details']->first_name;
                        $accountEmail = $data['customer_details']->email;
                        $order_id = $data['customer_details']->id;
                        $subject = "Order Report from DentoMatix";
                        $message = "Hi " . ucwords($accountName) . ", Order Report from Dentomatix<br>Return Id:$return_id<br/>The Requested Refund for the ordered product is Accepted from the Vendor side  and it will be  Refunded to the given Account. Sorry for the inconvenience caused.<br><b>Order ID :</b>$return_id<br>Please read the Terms and conditions of the product before purchasing.<br>Thank You";
                        $this->email->from('natehornsby@gmail.com', 'Nate Hornsby');
                        $this->email->to($accountEmail);
                        $this->email->subject($subject);
                        $email_data = array(
                            'subject' => $subject,
                            'message' => $message
                        );
                        $body = $this->load->view('/templates/email/index.php', $email_data, TRUE);
                        $this->email->message($body);
                        $this->email->send();
                    }
                }
                header("Location: orderReturn-transferAmount?return_id=" . $return_id);
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function orderReturn_finished() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $return_id = $this->input->get('return_id');
            $data['total_count'] = "";
            if ($return_id != null) {
                $query = "SELECT b.payment_id,a.id,a.created_at as requested_date,c.first_name,e.organization_name,sum(g.total)as refund_amount,h.address1,h.address2,h.city,h.state,h.zip FROM order_returns a INNER JOIN orders b on b.id=a.order_id and b.restricted_order='0' INNER JOIN users c on c.id=b.user_id INNER JOIN organization_groups d on d.user_id=c.id INNER JOIN organizations e on e.id=d.organization_id INNER JOIN order_item_returns f on f.order_return_id=a.id INNER JOIN order_items g on g.id=f.order_item_id and g.restricted_order='0'INNER JOIN organization_locations h on h.organization_id=e.id where a.id=$return_id";
                $data['customer_details'] = $this->db->query($query)->result();
            }
            $data['My_vendor_users'] = "";
            $data['vendor_shipping'] = "";
            $data['return_id'] = $return_id;
            $data['promoCodes_active'] = "";
            $data['NorderCount'] = order_count(); // To Get the Latest Order Count.
            $data['ReturnCount'] = return_count();
            $this->load->view('/templates/vendor-admin/returns/r/refunded/index.php', $data);
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

}

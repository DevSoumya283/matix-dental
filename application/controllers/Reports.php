<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends MW_Controller {
    /*
     *   The User Controller
     *   My_Model is called to Insert the DaTa in Database.
     */

    function __construct() {

        parent::__construct();

        $this->load->model('Products_model');
        $this->load->model('Category_model');
        $this->load->model('Organization_model');
        $this->load->model('Organization_groups_model');
        $this->load->model('User_location_model');
        $this->load->model('Organization_location_model');
        $this->load->model('Products_model');
        $this->load->model('Request_list_model');
        $this->load->model('Product_pricing_model');
        $this->load->model('Vendor_model');
        $this->load->model('Review_model');
        $this->load->model('Prepopulated_list_model');
        $this->load->model('Prepopulated_product_model');
        $this->load->model('Product_question_model');
        $this->load->model('Role_model');
        $this->load->model('User_model');
        $this->load->model('Order_model');
        $this->load->model('Order_items_model');
        $this->load->model('Shipping_options_model');
        $this->load->model('User_payment_option_model');
        $this->load->model('Images_model');
        $this->load->model('Recurring_order_model');
        $this->load->model('Order_item_return_model');
        $this->load->model('Order_return_model');
        $this->load->model('User_licenses_model');
        $this->load->model('Location_inventories_model');
        $this->load->model('Product_answer_model');
        $this->load->library('encryption');
        $this->load->helper('string');
        $this->load->helper('date');
        $this->load->library('cart');
    }

    public function products_filters() {

        $users_roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $users_roles))) {
            $user_id = $_SESSION['user_id'];
            $data['organization_group'] = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
            $organization_id = "";
            if ($data['organization_group'] != null) {
                $organization_id = $data['organization_group']->organization_id;
            }

            $categories = $this->input->post("categories");
            $start_date = $this->input->post("start_date");
            $end_date = $this->input->post("end_date");
            $locName = $this->input->post("locName");
            $vendorName = $this->input->post("vendorName");

            $start_date = $this->input->post("start_date");
            $end_date = $this->input->post("end_date");

            if ($start_date != null && $start_date != "") {
                $start_date = date("Y-m-d", strtotime($start_date));
            } else {
                $start_date = "";
            }
            if ($end_date != null && $end_date != "") {
                $end_date = date("Y-m-d", strtotime($end_date));
            } else {
                $end_date = "";
            }

            //Snapshot Section
// Total Spent and Top Spending Location & Location Graph Start

            $query1 = "SELECT  sum(oi.quantity) as total_quantity, sum(oi.total) as total_price, oi.product_id, p.*, o.*, oi.*, ol.nickname FROM order_items as oi, orders as o left join organization_groups as og on o.user_id=og.user_id, products as p, organization_locations as ol where oi.order_id=o.id and o.order_status != 'Cancelled' and o.restricted_order ='0' and og.organization_id=$organization_id and p.id = oi.product_id and o.location_id = ol.id ";

            if ($start_date != "") {
                $query1 .= " and o.created_at >= '$start_date' ";
            }

            if ($end_date != "") {
                $query1 .= " and o.created_at <= '$end_date' ";
            }

            if ($locName != null && $locName != "") {
                $query1 .= " and o.location_id = $locName ";
            }

            if ($vendorName != null && $vendorName != "") {
                $query1 .= " and o.vendor_id = $vendorName ";
            }

            if ($categories != null && $categories != "") {
                $query1 .= " and p.category_id like '%\"" . $categories . "\"%'";
            }

            $query1 .= " group by o.location_id order by total_price desc";

            $total_spent_query = $this->db->query($query1);
            $total_spent_result = $total_spent_query->result();

            $data['total_spent'] = 0;
            $data['top_location'] = "";
            if ($total_spent_result != null && count($total_spent_result) > 0) {
                for ($i = 0; $i < count($total_spent_result); $i++) {
                    if ($i == 0) {
                        $data['top_location'] = $total_spent_result[$i]->nickname;
                    }
                    $data['total_spent'] += $total_spent_result[$i]->total_price;
                }
            }

            $data['spending_by_location'] = $total_spent_result;
// Total Spent and Top Spending Location & Location Graph End
// Vendors Graph Start

            $query2 = "SELECT  sum(oi.quantity) as total_quantity, sum(oi.total) as total_price, oi.product_id, o.vendor_id, p.*, o.*, oi.*, v.name as vendor_name  FROM order_items as oi, orders as o left join organization_groups as og on o.user_id=og.user_id, products as p, vendors as v where oi.order_id=o.id and o.order_status != 'Cancelled' and o.restricted_order='0' and og.organization_id=$organization_id and p.id = oi.product_id and v.id = o.vendor_id ";


            if ($start_date != "") {
                $query2 .= " and o.created_at >= '$start_date' ";
            }

            if ($end_date != "") {
                $query2 .= " and o.created_at <= '$end_date' ";
            }

            if ($locName != null && $locName != "") {
                $query2 .= " and o.location_id = $locName ";
            }

            if ($vendorName != null && $vendorName != "") {
                $query2 .= " and o.vendor_id = $vendorName ";
            }

            if ($categories != null && $categories != "") {
                $query2 .= " and p.category_id like '%\"" . $categories . "\"%'";
            }

            $query2 .= "  group by o.vendor_id order by total_price desc ";

            $favorite_vendor_query = $this->db->query($query2);

            $favorite_vendor_result = $favorite_vendor_query->result();
            $data['top_vendor'] = "";
            if ($favorite_vendor_result != null && count($favorite_vendor_result) > 0) {
                for ($i = 0; $i < count($favorite_vendor_result); $i++) {
                    if ($i == 0) {
                        $data['top_vendor'] = $favorite_vendor_result[$i]->vendor_name;
                    }
                }
            }
            $data['spending_vendors'] = $favorite_vendor_result;
// Vendors Graph End
//Group By Category Graph Start
            $query3 = "SELECT oi.tax as item_tax, sum(oi.quantity) as total_quantity, sum(oi.total) as total_price, oi.product_id as product_id, p.*, o.*, oi.*, ol.nickname, v.name as vendor_name FROM order_items as oi, orders as o left join organization_groups as og on o.user_id=og.user_id, products as p, organization_locations as ol, vendors as v where oi.order_id=o.id and o.order_status != 'Cancelled' and o.restricted_order='0' and og.organization_id=$organization_id and p.id = oi.product_id and o.location_id = ol.id and v.id=oi.vendor_id";


            if ($start_date != "") {
                $query3 .= " and o.created_at >= '$start_date' ";
            }

            if ($end_date != "") {
                $query3 .= " and o.created_at <= '$end_date' ";
            }

            if ($locName != null && $locName != "") {
                $query3 .= " and o.location_id = $locName ";
            }

            if ($vendorName != null && $vendorName != "") {
                $query3 .= " and o.vendor_id = $vendorName ";
            }

            if ($categories != null && $categories != "") {
                $query3 .= " and p.category_id like '%\"" . $categories . "\"%'";
            }


            $query3 .= "  group by oi.product_id order by total_price desc ";


            $products_query = $this->db->query($query3);
            $products_result = $products_query->result();

            $classic_categories = $this->Category_model->get_many_by(array('parent_id' => 1));
            for ($i = 0; $i < count($classic_categories); $i++) {
                $amount = 0;
                $count = 0;
                for ($j = 0; $j < count($products_result); $j++) {
                    $product_categories = explode(",", str_replace('"', '', $products_result[$j]->category_id));
                    if (in_array($classic_categories[$i]->id, $product_categories)) {
                        $amount += $products_result[$j]->total_price;
                        $count += 1;
                    }
                }
                $classic_categories[$i]->amount = $amount;
                $classic_categories[$i]->count = $count;
            }

//Group By Category Graph End
// Purchases Tab
            $data['total_purchase_item'] = 0;
            if ($products_result != null) {
                $data['total_purchase_item'] = count($products_result);
            }

            $data['top_category'] = "";
            $highest_value = 0;
            for ($i = 0; $i < count($classic_categories); $i++) {
                if ($classic_categories[$i]->amount > $highest_value) {
                    if ($categories != null && $categories != "") {
                        if ($classic_categories[$i]->id == $categories) {
                            $data['top_category'] = $classic_categories[$i]->name;
                        }
                    } else {
                        $data['top_category'] = $classic_categories[$i]->name;
                    }
                    $highest_value = $classic_categories[$i]->amount;
                }
            }

            if ($products_result != null) {
                for ($i = 0; $i < count($products_result); $i++) {
                    $products_result[$i]->category_name = "-";
                    $product_categories = explode(",", str_replace('"', '', $products_result[$i]->category_id));
                    for ($j = 0; $j < count($classic_categories); $j++) {
                        if ($categories != null && $categories != "") {
                            if ($categories == $classic_categories[$j]->id) {
                                $products_result[$i]->category_name = $classic_categories[$j]->name;
                                break;
                            }
                        } else {
                            if (in_array($classic_categories[$j]->id, $product_categories)) {
                                if ($categories != null && $categories != "") {
                                    if ($classic_categories[$i]->id == $categories) {
                                        $products_result[$i]->category_name = $classic_categories[$j]->name;
                                        break;
                                    }
                                } else {
                                    $products_result[$i]->category_name = $classic_categories[$j]->name;
                                    break;
                                }
                            }
                        }
                    }
                }
            }

            $data['products_result'] = $products_result;


            $this->load->view('/templates/account/reports/product_data', $data);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function tax_filters() {

        $users_roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $users_roles))) {
            $user_id = $_SESSION['user_id'];
            $data['organization_group'] = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
            $organization_id = "";
            if ($data['organization_group'] != null) {
                $organization_id = $data['organization_group']->organization_id;
            }

            $categories = $this->input->post("categories");
            $start_date = $this->input->post("start_date");
            $end_date = $this->input->post("end_date");
            $locName = $this->input->post("locName");
            $vendorName = $this->input->post("vendorName");

            $start_date = $this->input->post("start_date");
            $end_date = $this->input->post("end_date");

            if ($start_date != null && $start_date != "") {
                $start_date = date("Y-m-d", strtotime($start_date));
            } else {
                $start_date = "";
            }
            if ($end_date != null && $end_date != "") {
                $end_date = date("Y-m-d", strtotime($end_date));
            } else {
                $end_date = "";
            }


            //Group By Category Graph Start
            $query3 = "SELECT sum(oi.tax) as item_tax, sum(oi.quantity) as total_quantity, sum(oi.total) as total_price, oi.product_id as product_id, p.*, o.*, oi.*, ol.nickname, v.name as vendor_name FROM order_items as oi, orders as o left join organization_groups as og on o.user_id=og.user_id, products as p, organization_locations as ol, vendors as v where oi.order_id=o.id and o.order_status != 'Cancelled' and o.restricted_order='0' and og.organization_id=$organization_id and p.id = oi.product_id and o.location_id = ol.id and oi.tax != 0 and v.id=oi.vendor_id";

            if ($start_date != "") {
                $query3 .= " and o.created_at >= '$start_date' ";
            }

            if ($end_date != "") {
                $query3 .= " and o.created_at <= '$end_date' ";
            }

            if ($locName != null && $locName != "") {
                $query3 .= " and o.location_id = $locName ";
            }

            if ($vendorName != null && $vendorName != "") {
                $query3 .= " and o.vendor_id = $vendorName ";
            }

            if ($categories != null && $categories != "") {
                $query3 .= " and p.category_id like '%\"" . $categories . "\"%'";
            }


            $query3 .= "  group by oi.product_id order by total_price desc ";

            $products_query = $this->db->query($query3);
            $tax_result = $products_query->result();


            $classic_categories = $this->Category_model->get_many_by(array('parent_id' => 1));
            for ($i = 0; $i < count($classic_categories); $i++) {
                $amount = 0;
                $count = 0;
                for ($j = 0; $j < count($tax_result); $j++) {
                    $product_categories = explode(",", str_replace('"', '', $tax_result[$j]->category_id));
                    if (in_array($classic_categories[$i]->id, $product_categories)) {
                        $amount += $tax_result[$j]->total_price;
                        $count += 1;
                    }
                }
                $classic_categories[$i]->amount = $amount;
                $classic_categories[$i]->count = $count;
            }

            $data['classics_graphs'] = $classic_categories;
            $data['classics'] = $classic_categories;

            $dentist_categories = $this->Category_model->get_many_by(array('parent_id' => 2));
            for ($i = 0; $i < count($dentist_categories); $i++) {
                $amount = 0;
                $count = 0;
                for ($j = 0; $j < count($tax_result); $j++) {
                    $product_categories = explode(",", str_replace('"', '', $tax_result[$j]->category_id));
                    if (in_array($dentist_categories[$i]->id, $product_categories)) {
                        $amount += $tax_result[$j]->total_price;
                        $count += 1;
                    }
                }
                $dentist_categories[$i]->amount = $amount;
                $dentist_categories[$i]->count = $count;
            }

            $data['dentists'] = $dentist_categories;
            if ($tax_result != null) {
                for ($i = 0; $i < count($tax_result); $i++) {
                    $tax_result[$i]->category_name = "-";
                    $product_categories = explode(",", str_replace('"', '', $tax_result[$i]->category_id));
                    for ($j = 0; $j < count($classic_categories); $j++) {
                        if (in_array($classic_categories[$j]->id, $product_categories)) {
                            $tax_result[$i]->category_name = $classic_categories[$j]->name;
                            break;
                        }
                    }
                }
            }

            //Tax Tab
            $data['total_taxable_items'] = 0;
            $data['total_tax'] = 0;

            for ($i = 0; $i < count($tax_result); $i++) {
                if ($tax_result[$i]->item_tax != 0) {
                    $data['total_taxable_items'] += 1;
                    $data['total_tax'] += $tax_result[$i]->item_tax;
                }
            }

            $data['tax_result'] = $tax_result;



            $this->load->view('/templates/account/reports/tax_data', $data);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function orders_filters() {


        $users_roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $users_roles))) {
            $user_id = $_SESSION['user_id'];
            $data['organization_group'] = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
            $organization_id = "";
            if ($data['organization_group'] != null) {
                $organization_id = $data['organization_group']->organization_id;
            }

            $categories = $this->input->post("categories");
            $start_date = $this->input->post("start_date");
            $end_date = $this->input->post("end_date");
            $locName = $this->input->post("locName");
            $vendorName = $this->input->post("vendorName");

            $start_date = $this->input->post("start_date");
            $end_date = $this->input->post("end_date");

            if ($start_date != null && $start_date != "") {
                $start_date = date("Y-m-d", strtotime($start_date));
            } else {
                $start_date = "";
            }
            if ($end_date != null && $end_date != "") {
                $end_date = date("Y-m-d", strtotime($end_date));
            } else {
                $end_date = "";
            }

            //Snapshot Section
// Total Spent and Top Spending Location & Location Graph Start

            $query1 = "SELECT  sum(o.total) as total_price, o.*, ol.nickname FROM orders as o left join organization_groups as og on o.user_id=og.user_id, organization_locations as ol where o.order_status != 'Cancelled' and o.restricted_order='0' and og.organization_id=$organization_id and o.location_id = ol.id ";

            if ($start_date != "") {
                $query1 .= " and o.created_at >= '$start_date' ";
            }

            if ($end_date != "") {
                $query1 .= " and o.created_at <= '$end_date' ";
            }

            if ($locName != null && $locName != "") {
                $query1 .= " and o.location_id = $locName ";
            }

            if ($vendorName != null && $vendorName != "") {
                $query1 .= " and o.vendor_id = $vendorName ";
            }

            $query1 .= " group by o.location_id order by total_price desc";

            $total_spent_query = $this->db->query($query1);
            $total_spent_result = $total_spent_query->result();

            $data['total_spent'] = 0;
            $data['top_location'] = "";
            if ($total_spent_result != null && count($total_spent_result) > 0) {
                for ($i = 0; $i < count($total_spent_result); $i++) {
                    if ($i == 0) {
                        $data['top_location'] = $total_spent_result[$i]->nickname;
                    }
                    $data['total_spent'] += $total_spent_result[$i]->total_price;
                }
            }

            $data['spending_by_location'] = $total_spent_result;
// Total Spent and Top Spending Location & Location Graph End
// Vendors Graph Start

            $query2 = "SELECT  sum(oi.quantity) as total_quantity, sum(oi.total) as total_price, oi.product_id, o.vendor_id, p.*, o.*, oi.*, v.name as vendor_name  FROM order_items as oi, orders as o left join organization_groups as og on o.user_id=og.user_id, products as p, vendors as v where oi.order_id=o.id and o.order_status != 'Cancelled' and o.restricted_order='0' and og.organization_id=$organization_id and p.id = oi.product_id and v.id = o.vendor_id ";


            if ($start_date != "") {
                $query2 .= " and o.created_at >= '$start_date' ";
            }

            if ($end_date != "") {
                $query2 .= " and o.created_at <= '$end_date' ";
            }

            if ($locName != null && $locName != "") {
                $query2 .= " and o.location_id = $locName ";
            }

            if ($vendorName != null && $vendorName != "") {
                $query2 .= " and o.vendor_id = $vendorName ";
            }

            if ($categories != null && $categories != "") {
                $query2 .= " and p.category_id like '%\"" . $categories . "\"%'";
            }

            $query2 .= "  group by o.vendor_id order by total_price desc ";

            $favorite_vendor_query = $this->db->query($query2);

            $favorite_vendor_result = $favorite_vendor_query->result();
            $data['top_vendor'] = "";
            if ($favorite_vendor_result != null && count($favorite_vendor_result) > 0) {
                for ($i = 0; $i < count($favorite_vendor_result); $i++) {
                    if ($i == 0) {
                        $data['top_vendor'] = $favorite_vendor_result[$i]->vendor_name;
                    }
                }
            }
            $data['spending_vendors'] = $favorite_vendor_result;

            //Orders Tab

            $query3 = "SELECT  sum(oi.quantity) as total_quantity, sum(oi.total) as total_price, oi.product_id, o.id as order_id, o.created_at as order_created_at,  p.*, o.*, oi.*, ol.nickname as location_name, v.name as vendor_name FROM order_items as oi, orders as o left join organization_groups as og on o.user_id=og.user_id, products as p, organization_locations as ol, vendors as v where oi.order_id=o.id and o.order_status != 'Cancelled' and o.restricted_order='0' and og.organization_id=$organization_id and p.id = oi.product_id and o.location_id = ol.id and v.id = o.vendor_id ";



            if ($start_date != "") {
                $query3 .= " and o.created_at >= '$start_date' ";
            }

            if ($end_date != "") {
                $query3 .= " and o.created_at <= '$end_date' ";
            }

            if ($locName != null && $locName != "") {
                $query3 .= " and o.location_id = $locName ";
            }

            if ($vendorName != null && $vendorName != "") {
                $query3 .= " and o.vendor_id = $vendorName ";
            }

            if ($categories != null && $categories != "") {
                $query3 .= " and p.category_id like '%\"" . $categories . "\"%'";
            }


            $query3 .= " group by oi.order_id order by total_price desc ";


            $orders_query = $this->db->query($query3);
            $orders_result = $orders_query->result();


            $data['total_orders'] = 0;
            $data['total_vendors'] = 0;
            $order_vendors = [];
            if ($orders_result != null) {
                $data['total_orders'] = count($orders_result);
                for ($i = 0; $i < count($orders_result); $i++) {
                    $order_vendors[] = $orders_result[$i]->vendor_id;
                }
            }

            $data['total_vendors'] = count(array_unique($order_vendors));
            $data['orders_result'] = $orders_result;


            $this->load->view('/templates/account/reports/order_data', $data);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: user-loginpage");
        }
    }

    public function compare_purchases() {
        $user_id = $_SESSION['user_id'];
        if (isset($user_id) && $user_id != null) {
            $product_one = $this->input->get("product1");
            $product_two = $this->input->get("product2");

            $product_one_detail = $this->Products_model->get($product_one);
            $product_two_detail = $this->Products_model->get($product_two);
            $data['product_one_name'] = $product_one_detail->name;
            $data['product_two_name'] = $product_two_detail->name;

            $organization_group = $this->Organization_groups_model->get_by(array('user_id' => $user_id));

            if ($organization_group != null) {
                $organization_id = $organization_group->organization_id;

                $organization_id_group = $this->Organization_groups_model->get_many_by(array('organization_id' => $organization_id));
                $orders = [];
                for ($i = 0; $i < count($organization_id_group); $i++) {
                    $org_user_id = $organization_id_group[$i]->user_id;
                    $orders[] = $this->Order_model->get_many_by(array('user_id' => $org_user_id, 'restricted_order' => '0'));
                }

                $order_items_product1 = [];
                $order_items_product2 = [];
                for ($i = 0; $i < count($orders); $i++) {
                    for ($j = 0; $j < count($orders[$i]); $j++) {
                        $order_items_product1[] = $this->Order_items_model->get_many_by(array('order_id' => $orders[$i][$j]->id, "product_id" => $product_one));
                        $order_items_product2[] = $this->Order_items_model->get_many_by(array('order_id' => $orders[$i][$j]->id, "product_id" => $product_two));
                    }
                }


                $first_date = strtotime("-1 year");
                $data['chart_xaxis'] = [];
                $data['chart_yaxis_product_one'] = [];
                $data['chart_yaxis_product_two'] = [];
                for ($k = 1; $k <= 12; $k+=1) {

                    $data['chart_xaxis'][] = date("M", strtotime($k . " month", $first_date));
                    $month_total_product_one = 0;
                    for ($i = 0; $i < count($order_items_product1); $i++) {
                        for ($j = 0; $j < count($order_items_product1[$i]); $j++) {
                            if (strtotime($order_items_product1[$i][$j]->created_at) >= strtotime(($k - 1) . " month", $first_date) && strtotime($order_items_product1[$i][$j]->created_at) <= strtotime($k . " month", $first_date)) {
                                $month_total_product_one += $order_items_product1[$i][$j]->total;
                            }
                        }
                    }
                    $data['chart_yaxis_product_one'][] = $month_total_product_one;

                    $month_total_product_two = 0;
                    for ($i = 0; $i < count($order_items_product2); $i++) {
                        for ($j = 0; $j < count($order_items_product2[$i]); $j++) {
                            if (strtotime($order_items_product2[$i][$j]->created_at) >= strtotime(($k - 1) . " month", $first_date) && strtotime($order_items_product2[$i][$j]->created_at) <= strtotime($k . " month", $first_date)) {
                                $month_total_product_two += $order_items_product2[$i][$j]->total;
                            }
                        }
                    }
                    $data['chart_yaxis_product_two'][] = $month_total_product_two;
                }
            }
        }

        echo json_encode($data);
    }

    public function compare_tax() {

        $user_id = $_SESSION['user_id'];
        if (isset($user_id) && $user_id != null) {
            $product_one = $this->input->get("product1");
            $product_two = $this->input->get("product2");

            $product_one_detail = $this->Products_model->get($product_one);
            $product_two_detail = $this->Products_model->get($product_two);
            $data['product_one_name'] = $product_one_detail->name;
            $data['product_two_name'] = $product_two_detail->name;

            $organization_group = $this->Organization_groups_model->get_by(array('user_id' => $user_id));

            if ($organization_group != null) {
                $organization_id = $organization_group->organization_id;

                $organization_id_group = $this->Organization_groups_model->get_many_by(array('organization_id' => $organization_id));
                $orders = [];
                for ($i = 0; $i < count($organization_id_group); $i++) {
                    $org_user_id = $organization_id_group[$i]->user_id;
                    $orders[] = $this->Order_model->get_many_by(array('user_id' => $org_user_id, 'restricted_order' => '0'));
                }

                $order_items_product1 = [];
                $order_items_product2 = [];
                for ($i = 0; $i < count($orders); $i++) {
                    for ($j = 0; $j < count($orders[$i]); $j++) {
                        $order_items_product1[] = $this->Order_items_model->get_many_by(array('order_id' => $orders[$i][$j]->id, "product_id" => $product_one));
                        $order_items_product2[] = $this->Order_items_model->get_many_by(array('order_id' => $orders[$i][$j]->id, "product_id" => $product_two));
                    }
                }


                $first_date = strtotime("-1 year");
                $data['chart_xaxis'] = [];
                $data['chart_yaxis_product_one'] = [];
                $data['chart_yaxis_product_two'] = [];
                for ($k = 1; $k <= 12; $k+=1) {

                    $data['chart_xaxis'][] = date("M", strtotime($k . " month", $first_date));
                    $month_total_product_one = 0;
                    for ($i = 0; $i < count($order_items_product1); $i++) {
                        for ($j = 0; $j < count($order_items_product1[$i]); $j++) {
                            if (strtotime($order_items_product1[$i][$j]->created_at) >= strtotime(($k - 1) . " month", $first_date) && strtotime($order_items_product1[$i][$j]->created_at) <= strtotime($k . " month", $first_date)) {
                                $month_total_product_one += $order_items_product1[$i][$j]->tax;
                            }
                        }
                    }
                    $data['chart_yaxis_product_one'][] = $month_total_product_one;

                    $month_total_product_two = 0;
                    for ($i = 0; $i < count($order_items_product2); $i++) {
                        for ($j = 0; $j < count($order_items_product2[$i]); $j++) {
                            if (strtotime($order_items_product2[$i][$j]->created_at) >= strtotime(($k - 1) . " month", $first_date) && strtotime($order_items_product2[$i][$j]->created_at) <= strtotime($k . " month", $first_date)) {
                                $month_total_product_two += $order_items_product2[$i][$j]->tax;
                            }
                        }
                    }
                    $data['chart_yaxis_product_two'][] = $month_total_product_two;
                }
            }
        }

        echo json_encode($data);
    }

    public function reports() {
        $users_roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $users_roles))) {
            $user_id = $_SESSION['user_id'];
            $data['organization_group'] = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
            $organization_id = "";
            if ($data['organization_group'] != null) {
                $organization_id = $data['organization_group']->organization_id;
            }

            //Snapshot Section
// Total Spent and Top Spending Location & Location Graph Start
            $total_spent_query = $this->db->query("SELECT  sum(o.total) as total_price, o.*, ol.nickname FROM orders as o left join organization_groups as og on o.user_id=og.user_id, organization_locations as ol where o.order_status != 'Cancelled' and o.restricted_order='0' and og.organization_id=$organization_id and o.location_id = ol.id group by o.location_id order by total_price desc");
            $total_spent_result = $total_spent_query->result();


            $data['total_spent'] = 0;
            $data['top_location'] = "";
            if ($total_spent_result != null && count($total_spent_result) > 0) {
                for ($i = 0; $i < count($total_spent_result); $i++) {
                    if ($i == 0) {
                        $data['top_location'] = $total_spent_result[$i]->nickname;
                    }
                    $data['total_spent'] += $total_spent_result[$i]->total_price;
                }
            }

            $data['spending_by_location'] = $total_spent_result;
// Total Spent and Top Spending Location & Location Graph End
// Vendors Graph Start
            $favorite_vendor_query = $this->db->query("SELECT  sum(o.total) as total_price, o.vendor_id, v.name as vendor_name  FROM orders as o left join organization_groups as og on o.user_id=og.user_id, vendors as v where o.order_status != 'Cancelled' and o.restricted_order='0' and og.organization_id=$organization_id and v.id = o.vendor_id group by o.vendor_id order by total_price desc ");

            $favorite_vendor_result = $favorite_vendor_query->result();
            $data['top_vendor'] = "";
            if ($favorite_vendor_result != null && count($favorite_vendor_result) > 0) {
                for ($i = 0; $i < count($favorite_vendor_result); $i++) {
                    if ($i == 0) {
                        $data['top_vendor'] = $favorite_vendor_result[$i]->vendor_name;
                    }
                }
            }
            $data['spending_vendors'] = $favorite_vendor_result;
// Vendors Graph End
// Top Spending Month Start
            $favorite_month_query = $this->db->query("SELECT sum(o.total) as total_price, MONTH(o.created_at) as month FROM orders as o left join organization_groups as og on o.user_id=og.user_id where o.order_status != 'Cancelled' and o.restricted_order='0' and og.organization_id=$organization_id  group by month order by total_price desc limit 1");

            $favorite_month_result = $favorite_month_query->result();
            $data['top_month'] = "";
            if ($favorite_month_result != null && count($favorite_month_result) > 0) {
                for ($i = 0; $i < count($favorite_month_result); $i++) {
                    if ($i == 0) {
                        $data['top_month'] = $favorite_month_result[$i]->month;
                    }
                }
            }

//Top Spending Month End
//Group By Category Graph Start
            $products_query = $this->db->query("SELECT sum(oi.tax) as item_tax, sum(oi.quantity) as total_quantity, sum(oi.total) as total_price, oi.product_id as product_id, p.*, ol.nickname, v.name as vendor_name FROM order_items as oi, orders as o left join organization_groups as og on o.user_id=og.user_id, products as p, organization_locations as ol, vendors as v where oi.order_id=o.id and o.order_status != 'Cancelled' and o.restricted_order='0' and og.organization_id=$organization_id and p.id = oi.product_id and o.location_id = ol.id and v.id=oi.vendor_id group by oi.product_id order by total_price desc");
            $products_result = $products_query->result();

            $classic_categories = $this->Category_model->get_many_by(array('parent_id' => 1));
            for ($i = 0; $i < count($classic_categories); $i++) {
                $amount = 0;
                $count = 0;
                for ($j = 0; $j < count($products_result); $j++) {
                    $product_categories = explode(",", str_replace('"', '', $products_result[$j]->category_id));
                    if (in_array($classic_categories[$i]->id, $product_categories)) {
                        $amount += $products_result[$j]->total_price;
                        $count += 1;
                    }
                }
                $classic_categories[$i]->amount = $amount;
                $classic_categories[$i]->count = $count;
            }

            $data['classics_graphs'] = $classic_categories;
            $data['classics'] = $classic_categories;

            $dentist_categories = $this->Category_model->get_many_by(array('parent_id' => 2));
            for ($i = 0; $i < count($dentist_categories); $i++) {
                $amount = 0;
                $count = 0;
                for ($j = 0; $j < count($products_result); $j++) {
                    $product_categories = explode(",", str_replace('"', '', $products_result[$j]->category_id));
                    if (in_array($dentist_categories[$i]->id, $product_categories)) {
                        $amount += $products_result[$j]->total_price;
                        $count += 1;
                    }
                }
                $dentist_categories[$i]->amount = $amount;
                $dentist_categories[$i]->count = $count;
            }

            $data['dentists'] = $dentist_categories;

//Group By Category Graph End
//Monthly Spending Graph Start
            $data['current_year_label'] = date("Y");
            $current_year_query = $this->db->query("SELECT  sum(o.total) as total_price, MONTH(o.created_at) as month FROM  orders as o left join organization_groups as og on o.user_id=og.user_id where o.order_status != 'Cancelled' and o.restricted_order='0' and og.organization_id=$organization_id and YEAR(o.created_at) = " . $data['current_year_label'] . " group by month order by month");

            $current_year_result = $current_year_query->result();

            $current_year_data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            for ($i = 0; $i < count($current_year_data); $i++) {
                for ($j = 0; $j < count($current_year_result); $j++) {
                    if (intval($current_year_result[$j]->month) == ($i + 1)) {
                        $current_year_data[$i] = $current_year_result[$j]->total_price;
                    }
                }
            }

            $data['current_year'] = $current_year_data;

            $data['previous_year_label'] = date("Y", strtotime("-1 year"));
            $previous_year_query = $this->db->query("SELECT  sum(o.total) as total_price, MONTH(o.created_at) as month FROM orders as o left join organization_groups as og on o.user_id=og.user_id where o.order_status != 'Cancelled' and o.restricted_order='0' and og.organization_id=$organization_id and YEAR(o.created_at) = " . $data['previous_year_label'] . " group by month order by month");

            $previous_year_result = $previous_year_query->result();
            $previous_year_data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            for ($i = 0; $i < count($previous_year_data); $i++) {
                for ($j = 0; $j < count($previous_year_result); $j++) {
                    if (intval($previous_year_result[$j]->month) == ($i + 1)) {
                        $previous_year_data[$i] = $previous_year_result[$j]->total_price;
                    }
                }
            }

            $data['previous_year'] = $previous_year_data;

// Monthly Spending Graph End
            //Orders Tab

            $orders_query = $this->db->query("SELECT sum(o.total) as total_price, o.id as order_id, o.created_at as order_created_at, o.*, ol.nickname as location_name, v.name as vendor_name FROM  orders as o left join organization_groups as og on o.user_id=og.user_id, organization_locations as ol, vendors as v where o.order_status != 'Cancelled' and o.restricted_order='0' and og.organization_id=$organization_id and o.location_id = ol.id and v.id = o.vendor_id group by o.id order by total_price desc");
            $orders_result = $orders_query->result();

            $data['total_orders'] = 0;
            $data['total_vendors'] = 0;
            $order_vendors = [];
            if ($orders_result != null) {
                $data['total_orders'] = count($orders_result);
                for ($i = 0; $i < count($orders_result); $i++) {
                    $order_vendors[] = $orders_result[$i]->vendor_id;
                }
            }

            $data['total_vendors'] = count(array_unique($order_vendors));
            $data['orders_result'] = $orders_result;


// Purchases Tab
            $data['total_purchase_item'] = 0;
            if ($products_result != null) {
                $data['total_purchase_item'] = count($products_result);
            }

            $data['top_category'] = "";
            $highest_value = 0;
            for ($i = 0; $i < count($classic_categories); $i++) {
                if ($classic_categories[$i]->amount > $highest_value) {
                    $data['top_category'] = $classic_categories[$i]->name;
                    $highest_value = $classic_categories[$i]->amount;
                }
            }

            if ($products_result != null) {
                for ($i = 0; $i < count($products_result); $i++) {
                    $products_result[$i]->category_name = "-";
                    $product_categories = explode(",", str_replace('"', '', $products_result[$i]->category_id));
                    for ($j = 0; $j < count($classic_categories); $j++) {
                        if (in_array($classic_categories[$j]->id, $product_categories)) {
                            $products_result[$i]->category_name = $classic_categories[$j]->name;
                            break;
                        }
                    }
                }
            }

            $data['products_result'] = $products_result;

            //Tax Tab
            $tax_query = $this->db->query("SELECT sum(oi.tax) as item_tax, sum(oi.quantity) as total_quantity, sum(oi.total) as total_price, oi.product_id as product_id, p.*, o.*, oi.*, ol.nickname, v.name as vendor_name FROM order_items as oi, orders as o left join organization_groups as og on o.user_id=og.user_id, products as p, organization_locations as ol, vendors as v where oi.order_id=o.id and o.order_status != 'Cancelled' and o.restricted_order='0' and og.organization_id=$organization_id and p.id = oi.product_id and o.location_id = ol.id and v.id=oi.vendor_id and oi.tax != 0 group by oi.product_id order by total_price desc");
            $tax_result = $tax_query->result();

            if ($tax_result != null) {
                for ($i = 0; $i < count($tax_result); $i++) {
                    $tax_result[$i]->category_name = "-";
                    $product_categories = explode(",", str_replace('"', '', $tax_result[$i]->category_id));
                    for ($j = 0; $j < count($classic_categories); $j++) {
                        if (in_array($classic_categories[$j]->id, $product_categories)) {
                            $tax_result[$i]->category_name = $classic_categories[$j]->name;
                            break;
                        }
                    }
                }
            }


            $data['total_taxable_items'] = 0;
            $data['total_tax'] = 0;

            for ($i = 0; $i < count($tax_result); $i++) {
                if ($tax_result[$i]->item_tax != 0) {
                    $data['total_taxable_items'] += 1;
                    $data['total_tax'] += $tax_result[$i]->item_tax;
                }
            }
            $data['tax_result'] = $tax_result;
            $data['locations'] = $this->User_location_model->get_many_by(array('user_id' => $user_id));
            for ($i = 0; $i < count($data['locations']); $i++) {
                $data['user_locations'][] = $this->Organization_location_model->get_by(array('id' => $data['locations'][$i]->organization_location_id));
            }
            $this->load->view('/templates/_inc/header', $data);
            $this->load->view('/templates/account/reports/index', $data);
            $this->load->view('/templates/_inc/footer');
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: login");
        }
    }

    public function view_account() {

        $users_roles = unserialize(ROLES_USERS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $users_roles))) {
            $user_id = $_SESSION['user_id'];
            $data['organization_group'] = $this->Organization_groups_model->get_by(array('user_id' => $user_id));
            $organization_id = "";
            if ($data['organization_group'] != null) {
                $organization_id = $data['organization_group']->organization_id;
            }

            $start_date = $this->input->post("start_date");
            $end_date = $this->input->post("end_date");

            if ($start_date != null && $start_date != "") {
                $start_date = date("Y-m-d", strtotime($start_date));
            } else {
                $start_date = "";
            }
            if ($end_date != null && $end_date != "") {
                $end_date = date("Y-m-d", strtotime($end_date));
            } else {
                $end_date = "";
            }
            //Snapshot Section
// Total Spent and Top Spending Location & Location Graph Start
            $query = "SELECT  sum(o.total) as total_price, o.*, ol.nickname FROM orders as o left join organization_groups as og on o.user_id=og.user_id, organization_locations as ol  where o.order_status != 'Cancelled' and o.restricted_order='0' and og.organization_id=$organization_id and o.location_id = ol.id ";

            if ($start_date != "") {
                $query .= " and o.created_at >= '$start_date' ";
            }

            if ($end_date != "") {
                $query .= " and o.created_at <= '$end_date' ";
            }

            $query .= " group by o.location_id order by total_price desc";

            $total_spent_query = $this->db->query($query);
            $total_spent_result = $total_spent_query->result();

            $data['total_spent'] = 0;
            $data['top_location'] = "";
            if ($total_spent_result != null && count($total_spent_result) > 0) {
                for ($i = 0; $i < count($total_spent_result); $i++) {
                    if ($i == 0) {
                        $data['top_location'] = $total_spent_result[$i]->nickname;
                    }
                    $data['total_spent'] += $total_spent_result[$i]->total_price;
                }
            }

            $data['spending_by_location'] = $total_spent_result;
// Total Spent and Top Spending Location & Location Graph End
// Vendors Graph Start

            $query2 = "SELECT  sum(o.total) as total_price, o.vendor_id, v.name as vendor_name  FROM orders as o left join organization_groups as og on o.user_id=og.user_id, vendors as v where o.order_status != 'Cancelled' and o.restricted_order='0' and og.organization_id=$organization_id and v.id = o.vendor_id ";

            if ($start_date != "") {
                $query2 .= " and o.created_at >= '$start_date' ";
            }

            if ($end_date != "") {
                $query2 .= " and o.created_at <= '$end_date' ";
            }


            $query2 .= " group by o.vendor_id order by total_price desc ";

            $favorite_vendor_query = $this->db->query($query2);

            $favorite_vendor_result = $favorite_vendor_query->result();
            $data['top_vendor'] = "";
            if ($favorite_vendor_result != null && count($favorite_vendor_result) > 0) {
                for ($i = 0; $i < count($favorite_vendor_result); $i++) {
                    if ($i == 0) {
                        $data['top_vendor'] = $favorite_vendor_result[$i]->vendor_name;
                    }
                }
            }
            $data['spending_vendors'] = $favorite_vendor_result;
// Vendors Graph End
// Top Spending Month Start
            $query3 = "SELECT sum(o.total) as total_price, MONTH(o.created_at) as month FROM orders as o left join organization_groups as og on o.user_id=og.user_id where o.order_status != 'Cancelled' and o.restricted_order='0' and og.organization_id=$organization_id ";



            if ($start_date != "") {
                $query3 .= " and o.created_at >= '$start_date' ";
            }

            if ($end_date != "") {
                $query3 .= " and o.created_at <= '$end_date' ";
            }


            $query3 .= " group by month order by total_price desc limit 1";
            $favorite_month_query = $this->db->query($query3);


            $favorite_month_result = $favorite_month_query->result();
            $data['top_month'] = "";
            if ($favorite_month_result != null && count($favorite_month_result) > 0) {
                for ($i = 0; $i < count($favorite_month_result); $i++) {
                    if ($i == 0) {
                        $data['top_month'] = $favorite_month_result[$i]->month;
                    }
                }
            }

//Top Spending Month End
//Group By Category Graph Start

            $query4 = "SELECT sum(oi.tax) as item_tax, sum(oi.quantity) as total_quantity, sum(oi.total) as total_price, oi.product_id as product_id, p.*, ol.nickname, v.name as vendor_name FROM order_items as oi, orders as o left join organization_groups as og on o.user_id=og.user_id, products as p, organization_locations as ol, vendors as v where oi.order_id=o.id and o.order_status != 'Cancelled' and o.restricted_order='0' and og.organization_id=$organization_id and p.id = oi.product_id and o.location_id = ol.id and v.id=oi.vendor_id";

            if ($start_date != "") {
                $query4 .= " and o.created_at >= '$start_date' ";
            }

            if ($end_date != "") {
                $query4 .= " and o.created_at <= '$end_date' ";
            }


            $query4 .= " group by oi.product_id order by total_price desc";



            $products_query = $this->db->query($query4);
            $products_result = $products_query->result();

            $classic_categories = $this->Category_model->get_many_by(array('parent_id' => 1));
            for ($i = 0; $i < count($classic_categories); $i++) {
                $amount = 0;
                for ($j = 0; $j < count($products_result); $j++) {
                    $product_categories = explode(",", str_replace('"', '', $products_result[$j]->category_id));
                    if (in_array($classic_categories[$i]->id, $product_categories)) {
                        $amount += $products_result[$j]->total_price;
                    }
                }
                $classic_categories[$i]->amount = $amount;
            }

            $data['classics_graphs'] = $classic_categories;

//Group By Category Graph End
//Monthly Spending Graph Start
            $data['current_year_label'] = date("Y");
            $current_year_query = $this->db->query("SELECT  sum(o.total) as total_price, MONTH(o.created_at) as month FROM  orders as o left join organization_groups as og on o.user_id=og.user_id where o.order_status != 'Cancelled' and o.restricted_order='0' and og.organization_id=$organization_id and YEAR(o.created_at) = " . $data['current_year_label'] . " group by month order by month");
            $current_year_result = $current_year_query->result();
            $current_year_data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            for ($i = 0; $i < count($current_year_data); $i++) {
                for ($j = 0; $j < count($current_year_result); $j++) {
                    if (intval($current_year_result[$j]->month) == ($i + 1)) {
                        $current_year_data[$i] = $current_year_result[$j]->total_price;
                    }
                }
            }
            $data['current_year'] = $current_year_data;
            $data['previous_year_label'] = date("Y", strtotime("-1 year"));
            $previous_year_query = $this->db->query("SELECT  sum(o.total) as total_price, MONTH(o.created_at) as month FROM orders as o left join organization_groups as og on o.user_id=og.user_id where o.order_status != 'Cancelled' and o.restricted_order='0' and og.organization_id=$organization_id and YEAR(o.created_at) = " . $data['previous_year_label'] . " group by month order by month");
            $previous_year_result = $previous_year_query->result();
            $previous_year_data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            for ($i = 0; $i < count($previous_year_data); $i++) {
                for ($j = 0; $j < count($previous_year_result); $j++) {
                    if (intval($previous_year_result[$j]->month) == ($i + 1)) {
                        $previous_year_data[$i] = $previous_year_result[$j]->total_price;
                    }
                }
            }

            $data['previous_year'] = $previous_year_data;

// Monthly Spending Graph End
            $this->load->view('/templates/account/reports/account_data', $data);
        } else {
            $this->session->set_flashdata("error", "Please login to continue");
            header("location: login");
        }
    }

}

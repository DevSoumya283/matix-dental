<?php

class VendorReports extends MW_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('elasticsearch');
        $this->load->helper('MY_privilege_helper');
        $this->load->helper('MY_support_helper');
        $this->load->helper('my_email_helper');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->model('Images_model');
        $this->load->model('Category_model');
        $this->load->model('Order_model');
        $this->load->model('Order_items_model');
        $this->load->model('Order_item_return_model');
        $this->load->model('Organization_groups_model');
        $this->load->model('Organization_model');
        $this->load->model('Products_model');
        $this->load->model('Product_pricing_model');
        $this->load->model('Product_question_model');
        $this->load->model('Prepopulated_list_model');
        $this->load->model('Prepopulated_product_model');
        $this->load->model('Product_custom_field_model');
        $this->load->model('Role_model');
        $this->load->model('Shipping_options_model');
        $this->load->model('User_model');
        $this->load->model('Vendor_model');
        $this->load->model('Vendor_groups_model');
        $this->load->library('email');
    }

    /*
     *    Vendor DashBoard
     *    @Reports
     *        First Page of Vendor Reports with the links for Given below reports.
     *        1.Orders Report 2.Sales Report 3.Customer Report 4.Shipping Report
     */

    public function firstPage_reports() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
                $data['My_vendor_users'] = "";
                $data['vendor_shipping'] = "";
                $data['product_details'] = "";    // The Object is Defined for Delete-product.php Modal
                $data['promoCodes_active'] = "";
                $data['NorderCount'] = order_count(); // To Get the Latest Order Count.
                $data['ReturnCount'] = return_count();
                $this->load->view('/templates/vendor-admin/reports/index.php', $data);
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    //    @Order_reports // 1 year.
    public function order_reportsVendor() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $data['start_date'] = date("Y-m-d", strtotime("-1 year"));    // 1 year
            $data['end_date'] = date("Y-m-d"); // Today
            $user_id = $_SESSION['user_id'];
            $vendor_groups = $this->Vendor_groups_model->get_by(array('user_id' => $user_id));
            $vendor_id = $vendor_groups->vendor_id;
            $startDate = date("Y-m-d", strtotime("-1 year")); // 1 Year
            $endDate = date("Y-m-d", now());
            $reportBy = 2;     //   1 => Day; 2 => Week; 3 => Month;
            if ($vendor_id != null) {

                $order_count_query = "select count(*) as order_count from orders where order_status != 'Cancelled' and restricted_order='0' and vendor_id=" . $vendor_id;
                $data['order_count_result'] = $this->db->query($order_count_query)->result();

                $order_count_shipped_query = "select count(*) as order_count from orders where (order_status='Shipped' || order_status='Complete') and restricted_order='0' and vendor_id=" . $vendor_id;
                $data['order_count_shipped_result'] = $this->db->query($order_count_shipped_query)->result();

                $vendor_total_revenue_query = "select o.id as orders_order_id, o.*, u.*, ot.* from orders o left join order_trackings ot on ot.order_id=o.id, users u where o.order_status != 'Cancelled' and o.restricted_order='0' and o.user_id=u.id and o.vendor_id=" . $vendor_id . " group by o.id order by o.total desc";
                $vendor_total_revenue_result = $this->db->query($vendor_total_revenue_query)->result();

                $total_revenue = 0.0;
                if ($vendor_total_revenue_result != null) {
                    for ($i = 0; $i < count($vendor_total_revenue_result); $i++) {
                        $vendor_total_revenue_result[$i]->package_count = 0;
                        $total_revenue += ($vendor_total_revenue_result[$i]->total);
                        if ($vendor_total_revenue_result[$i]->package_id1 != "") {
                            $vendor_total_revenue_result[$i]->package_count += 1;
                        }
                        if ($vendor_total_revenue_result[$i]->package_id2 != "") {
                            $vendor_total_revenue_result[$i]->package_count += 1;
                        }
                        if ($vendor_total_revenue_result[$i]->package_id3 != "") {
                            $vendor_total_revenue_result[$i]->package_count += 1;
                        }
                        if ($vendor_total_revenue_result[$i]->package_id4 != "") {
                            $vendor_total_revenue_result[$i]->package_count += 1;
                        }
                        if ($vendor_total_revenue_result[$i]->package_id5 != "") {
                            $vendor_total_revenue_result[$i]->package_count += 1;
                        }
                    }
                }

                $data['total_revenue'] = $total_revenue;

                $data['vendor_total_revenue_result'] = $vendor_total_revenue_result;
                // Category Section  END    ******
                //  Configuration Filter Events.

                $query5 = "select e.id,e.nickname from product_pricings a INNER JOIN orders b on b.vendor_id=a.vendor_id and b.restricted_order='0' INNER JOIN user_locations c on c.user_id=b.user_id INNER JOIN organization_groups d on d.user_id=b.user_id INNER JOIN organization_locations e on e.organization_id=d.organization_id where  a.vendor_id=$vendor_id GROUP by e.id";
                $data['config_locations'] = $this->db->query($query5)->result();

                $chart_query = $this->db->query("SELECT * FROM  orders o where o.order_status != 'Cancelled' and o.restricted_order='0' and o.vendor_id=$vendor_id and o.created_at>='" . date('Y-m-d', strtotime("-1 year")) . "' order by o.created_at desc");
                $current_year_result = $chart_query->result();

                $data['chart_xaxis'] = [];
                $data['chart_yaxis'] = [];
                $timestamp_diff = 608400;

                for ($k = strtotime("-1 year"); $k <= strtotime("now"); $k+=$timestamp_diff) {
                    $data['chart_xaxis'][] = date("M d", ($k));
                    $week_total = 0;
                    for ($i = 0; $i < count($current_year_result); $i++) {
                        if (strtotime($current_year_result[$i]->created_at) >= $k && strtotime($current_year_result[$i]->created_at) <= ($k + $timestamp_diff)) {
                            $week_total += 1;
                        }
                    }
                    $data['chart_yaxis'][] = $week_total;
                }
            }
            $data['start_date'] = $startDate;
            $data['end_date'] = $endDate;
            $data['reportBy'] = $reportBy; // Defining Day || Week || Month.
            $data['My_vendor_users'] = "";
            $data['vendor_shipping'] = "";
            $data['product_details'] = "";    // The Object is Defined for Delete-product.php Modal
            $data['promoCodes_active'] = "";
            $data['NorderCount'] = order_count(); // To Get the Latest Order Count.
            $data['ReturnCount'] = return_count();

            $data['vendorOrderFilter'] = "VendorOrderFilter";
            $this->load->view('/templates/_inc/header-vendor');
            $this->load->view('/templates/vendor-admin/reports/orders-rep/index.php', $data);
            $this->load->view('/templates/_inc/footer-vendor');

        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

//    @Order_reports // Selection.
    public function Orderfilter_Render() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $vendor_id = $_SESSION['vendor_id'];

            if ($vendor_id != null) {
                $vendorStartDate = $this->input->post("vendorStartDate");
                $vendorEndDate = $this->input->post("vendorEndDate");
                // If Date Not selected.
                if ($vendorStartDate == null || $vendorStartDate == "") {
                    $data['start_date'] = date("Y-m-d", strtotime("-1 year"));
                } else {
                    $data['start_date'] = date("Y-m-d", strtotime($vendorStartDate));
                }
                // If Date Not selected.
                if ($vendorEndDate == null || $vendorEndDate == "") {
                    $data['end_date'] = date("Y-m-d");
                } else {
                    $data['end_date'] = date("Y-m-d", strtotime($vendorEndDate));
                }
                //   1 => Day; 2 => Week; 3 => Month;  The Report shown based on Resolution selection.
                $resolution = $this->input->post("resolution");
                if ($resolution == null || $resolution == "") {
                    $reportBy = 2;
                } else {
                    $reportBy = $resolution;
                }

                if ($reportBy == 1) {
                    $timestamp_diff = 86400;
                }
                if ($reportBy == 2) {
                    $timestamp_diff = 604800;
                }
                if ($reportBy == 3) {
                    $timestamp_diff = (86400 * 30);
                }

                $location_id = $this->input->post("location_id");

                $order_count_query = "select count(*) as order_count from orders where order_status != 'Cancelled' and restricted_order='0' and created_at>='" . $data['start_date'] . "' and created_at <= '" . $data['end_date'] . " 23:59:59' and vendor_id=" . $vendor_id;
                if ($location_id != null && $location_id != "") {
                    $order_count_query .= " and location_id=$location_id";
                }

                $data['order_count_result'] = $this->db->query($order_count_query)->result();

                $order_count_shipped_query = "select count(*) as order_count from orders where (order_status='Shipped' || order_status='Complete') and restricted_order='0' and created_at>='" . $data['start_date'] . "' and created_at <= '" . $data['end_date'] . " 23:59:59' and vendor_id=" . $vendor_id;
                if ($location_id != null && $location_id != "") {
                    $order_count_shipped_query .= " and location_id=$location_id";
                }
                $data['order_count_shipped_result'] = $this->db->query($order_count_shipped_query)->result();

                $vendor_total_revenue_query = "select o.id as orders_order_id, o.*, u.*, ot.* from orders o left join order_trackings ot on ot.order_id=o.id, users u where o.order_status != 'Cancelled' and o.restricted_order='0' and o.user_id=u.id and o.vendor_id=" . $vendor_id . " and o.created_at>='" . $data['start_date'] . "' and o.created_at <= '" . $data['end_date'] . " 23:59:59'  ";
                if ($location_id != null && $location_id != "") {
                    $vendor_total_revenue_query .= " and o.location_id=$location_id";
                }
                $vendor_total_revenue_query .= " group by o.id order by o.total desc";

                $vendor_total_revenue_result = $this->db->query($vendor_total_revenue_query)->result();

                $total_revenue = 0.0;
                if ($vendor_total_revenue_result != null) {
                    for ($i = 0; $i < count($vendor_total_revenue_result); $i++) {
                        $vendor_total_revenue_result[$i]->package_count = 0;
                        $total_revenue += ($vendor_total_revenue_result[$i]->total);
                        if ($vendor_total_revenue_result[$i]->package_id1 != "") {
                            $vendor_total_revenue_result[$i]->package_count += 1;
                        }
                        if ($vendor_total_revenue_result[$i]->package_id2 != "") {
                            $vendor_total_revenue_result[$i]->package_count += 1;
                        }
                        if ($vendor_total_revenue_result[$i]->package_id3 != "") {
                            $vendor_total_revenue_result[$i]->package_count += 1;
                        }
                        if ($vendor_total_revenue_result[$i]->package_id4 != "") {
                            $vendor_total_revenue_result[$i]->package_count += 1;
                        }
                        if ($vendor_total_revenue_result[$i]->package_id5 != "") {
                            $vendor_total_revenue_result[$i]->package_count += 1;
                        }
                    }
                }

                $data['total_revenue'] = $total_revenue;

                $data['vendor_total_revenue_result'] = $vendor_total_revenue_result;
                // Category Section  END    ******
                //  Configuration Filter Events.

                $chart_query = "SELECT * FROM  orders o where o.order_status != 'Cancelled' and o.restricted_order='0' and o.vendor_id=$vendor_id and o.created_at>='" . $data['start_date'] . " 00:00:00' and o.created_at <= '" . $data['end_date'] . " 23:59:59' ";
                if ($location_id != null && $location_id != "") {
                    $chart_query .= " and o.location_id=" . $location_id;
                }
                $chart_query .= " order by o.created_at desc";
                $current_year_result = $this->db->query($chart_query)->result();

                $data['chart_xaxis'] = [];
                $data['chart_yaxis'] = [];

                for ($k = strtotime($data['start_date']); $k <= strtotime($data['end_date'] . ' 23:59:59'); $k+=$timestamp_diff) {
                    $data['chart_xaxis'][] = date("M d", ($k));
                    $week_total = 0;
                    for ($i = 0; $i < count($current_year_result); $i++) {
                        if (strtotime($current_year_result[$i]->created_at) >= $k && strtotime($current_year_result[$i]->created_at) <= ($k + $timestamp_diff)) {
                            $week_total += 1;
                        }
                    }
                    $data['chart_yaxis'][] = $week_total;
                }
            }
            $data['reportBy'] = $reportBy;
            $this->load->view('/templates/vendor-admin/reports/orders-rep/chart-report-render.php', $data);
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    // @Vendor_sales // 1 Year
    public function vendorReports_Sales() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $vendor_id = $_SESSION['vendor_id'];
            $data['total_item'] = "";
            $data['Manufacturers'] = "";
            if ($vendor_id != null) {
                $startDate = date("Y-m-d", strtotime("-1 year"));   // 1 Year
                $endDate = date("Y-m-d", now());
                $reportBy = 2;     //   1 => Day; 2 => Week; 3 => Month;
                //     Items Count
                $vendorStartDate = $this->input->post("vendorStartDate");
                $vendorEndDate = $this->input->post("vendorEndDate");
                if ($vendorStartDate == null || $vendorStartDate == "") {
                    $data['start_date'] = date("Y-m-d", strtotime("-1 year")); // If date is not selected
                } else {
                    $data['start_date'] = date("Y-m-d", strtotime($vendorStartDate));
                }

                if ($vendorEndDate == null || $vendorEndDate == "") {
                    $data['end_date'] = date("Y-m-d"); // If date is not selected
                } else {
                    $data['end_date'] = date("Y-m-d", strtotime($vendorEndDate));
                }

                $resolution = $this->input->post("resolution");
                if ($resolution == null || $resolution == "") {
                    $reportBy = 2;
                } else {
                    $reportBy = $resolution;
                }

                if ($reportBy == 1) {
                    $timestamp_diff = 86400;
                }
                if ($reportBy == 2) {
                    $timestamp_diff = 604800;
                }
                if ($reportBy == 3) {
                    $timestamp_diff = (86400 * 30);
                }

                $location_id = $this->input->post("location_id");
                $manufacturer_name = $this->input->post("manufacturer_name");
                $Category_select = $this->input->post("Category_select");

                $data['total_items'] = 0;
                $data['manufacturers'] = 0;
                $data['total_revenue'] = 0.00;
                $data['overall_revenue'] = 0.00;
                $unique_manufacturers = [];
                $unique_orders = [];
                $query = "select sum(oi.total) as order_item_total, pp.vendor_product_id, p.name as product_name, p.category_id, p.manufacturer, oi.* from order_items oi, orders o, product_pricings pp, products p  where o.id=oi.order_id and o.order_status != 'Cancelled'  and o.restricted_order='0' and oi.product_id=pp.product_id and p.id=pp.product_id and o.vendor_id=pp.vendor_id and o.vendor_id=$vendor_id group by oi.product_id";
                $data['items'] = $this->db->query($query)->result();
                if ($data['items'] != null) {
                    $data['total_items'] = count($data['items']);
                    for ($i = 0; $i < count($data['items']); $i++) {
                        $data['overall_revenue'] += $data['items'][$i]->order_item_total;
                        $data['total_revenue'] += $data['items'][$i]->order_item_total;
                        if (!(in_array($data['items'][$i]->manufacturer, $unique_manufacturers))) {
                            $unique_manufacturers[] = $data['items'][$i]->manufacturer;
                        }
                        if (!(in_array($data['items'][$i]->order_id, $unique_orders))) {
                            $unique_orders[] = $data['items'][$i]->order_id;
                        }
                    }
                }

                $data['manufacturers'] = count($unique_manufacturers);

                if (count($unique_orders) > 0) {
                    $query_order_promos = "select * from order_promotions where restricted_order='0' and order_id in (" . implode(",", $unique_orders) . ")";
                    $result_order_promos = $this->db->query($query_order_promos)->result();
                    if ($result_order_promos != null) {
                        for ($i = 0; $i < count($result_order_promos); $i++) {
                            $data['total_revenue'] -= $result_order_promos[$i]->discount_value;
                        }
                    }
                }


                $classic_categories = $this->Category_model->get_many_by(array('parent_id' => 1));
                $unique_categories = [];
                for ($i = 0; $i < count($classic_categories); $i++) {
                    for ($j = 0; $j < count($data['items']); $j++) {
                        $order_product_categories = explode(",", $data['items'][$j]->category_id);
                        if (in_array('"' . $classic_categories[$i]->id . '"', $order_product_categories)) {
                            $unique_categories[] = $classic_categories[$i]->id;
                        }
                    }
                }
                $data['unique_product_categories'] = array_unique($unique_categories);

                for ($j = 0; $j < count($data['items']); $j++) {
                    $data['items'][$j]->category = "-";
                    for ($i = 0; $i < count($classic_categories); $i++) {
                        $order_product_categories = explode(",", $data['items'][$j]->category_id);
                        if (in_array('"' . $classic_categories[$i]->id . '"', $order_product_categories)) {
                            $data['items'][$j]->category = $classic_categories[$i]->name;
                        }
                    }
                }


                // Product Details

                $timestamp_diff = 604800;
                $query4 = "select oi.total as order_item_total, pp.vendor_product_id, p.name as product_name, p.category_id, p.manufacturer, oi.* from order_items oi, orders o, product_pricings pp, products p  where o.id=oi.order_id and o.order_status != 'Cancelled'  and o.restricted_order='0' and oi.product_id=pp.product_id and p.id=pp.product_id and o.vendor_id=pp.vendor_id and  o.vendor_id=$vendor_id order by oi.created_at asc";
                $data['OrderChart'] = $this->db->query($query4)->result();
                $data['chart_xaxis'] = [];
                $data['chart_yaxis'] = [];
                if ($data['OrderChart'] != null) {
                    for ($k = strtotime("-1 year"); $k <= strtotime("now"); $k+=$timestamp_diff) {
                        $data['chart_xaxis'][] = date("M d", ($k));
                        $week_total = 0;
                        for ($i = 0; $i < count($data['OrderChart']); $i++) {
                            if (strtotime($data['OrderChart'][$i]->created_at) >= $k && strtotime($data['OrderChart'][$i]->created_at) <= ($k + $timestamp_diff)) {
                                $week_total += $data['OrderChart'][$i]->order_item_total;
                            }
                        }
                        $data['chart_yaxis'][] = $week_total;
                    }
                }
                //  Configuration Filter Events.

                $query5 = "select e.id,e.nickname from product_pricings a INNER JOIN orders b on b.vendor_id=a.vendor_id INNER JOIN user_locations c on c.user_id=b.user_id INNER JOIN organization_groups d on d.user_id=b.user_id INNER JOIN organization_locations e on e.organization_id=d.organization_id where a.vendor_id=$vendor_id and b.restricted_order='0' GROUP by e.id";
                $data['config_locations'] = $this->db->query($query5)->result();

                $query6 = "SELECT b.manufacturer FROM product_pricings a  INNER JOIN products b on b.id=a.product_id WHERE a.vendor_id=$vendor_id group by b.manufacturer";
                $data['config_manufacturer'] = $this->db->query($query6)->result();

                // Categories START ***
                $queryproduct = $this->db->query("select *,f.id as location_id from product_pricings a INNER JOIN products b on b.id=a.product_id INNER JOIN order_items c on c.product_id=a.product_id INNER JOIN orders d on d.id=c.order_id INNER JOIN user_locations e on e.user_id=d.user_id INNER JOIN organization_locations f on f.id=e.organization_location_id where d.restricted_order='0'and a.vendor_id=$vendor_id");
                $data['products'] = $queryproduct->result();
                if ($data['products'] != null) {
                    for ($i = 0; $i < count($data['products']); $i++) {
                        $data['productId'][] = $data['products'][$i]->id;
                        $data['location'][] = $data['products'][$i]->location_id;
                        $data['vendor'][] = $data['products'][$i]->vendor_id;
                        $data['catId'][] = explode(",", str_replace('"', '', $data['products'][$i]->category_id));
                    }
                    $data['catIdCount'] = $data['catId'];
                    $classic_categories = $this->Category_model->get_many_by(array('parent_id' => 1));
                    for ($i = 0; $i < count($classic_categories); $i++) {
                        $count = 0;
                        for ($j = 0; $j < count($data['catIdCount']); $j++) {
                            if (in_array($classic_categories[$i]->id, $data['catIdCount'][$j])) {
                                $count +=1;
                            }
                        }
                        $classic_categories[$i]->count = $count;
                    }
                    $data['classic'] = $classic_categories;
                    $densist_categories = $this->Category_model->get_many_by(array('parent_id' => 2));
                    for ($i = 0; $i < count($densist_categories); $i++) {
                        $count = 0;
                        for ($j = 0; $j < count($data['catIdCount']); $j++) {
                            if (in_array($densist_categories[$i]->id, $data['catIdCount'][$j])) {
                                $count +=1;
                            }
                        }
                        $densist_categories[$i]->count = $count;
                    }
                    $data['dentist'] = $densist_categories;
                }
                // Categories END ***
            }
            $data['start_date'] = $startDate;
            $data['end_date'] = $endDate;
            $data['reportBy'] = $reportBy; // Defining Day || Week || Month.
            $data['My_vendor_users'] = "";
            $data['vendor_shipping'] = "";
            $data['product_details'] = "";    // The Object is Defined for Delete-product.php Modal
            $data['promoCodes_active'] = "";
            $data['NorderCount'] = order_count(); // To Get the Latest Order Count.
            $data['ReturnCount'] = return_count();
            $data['vendorOrderFilter'] = "VendorSalesFilter";
            $this->load->view('/templates/vendor-admin/reports/sales-rep/index.php', $data);
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    // VendorSales Report Filter
    public function OrderSales_FilterReport() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $vendor_id = $_SESSION['vendor_id'];
            $data['total_item'] = "";
            $data['Manufacturers'] = "";
            if ($vendor_id != null) {
                $startDate = date("Y-m-d", strtotime("-1 year"));
                $endDate = date("Y-m-d", now());
                $reportBy = 2;     //   1 => Day; 2 => Week; 3 => Month;
                //     Items Count
                $vendorStartDate = $this->input->post("vendorStartDate");
                $vendorEndDate = $this->input->post("vendorEndDate");

                if ($vendorStartDate == null || $vendorStartDate == "") {
                    $data['start_date'] = date("Y-m-d", strtotime("-1 year"));
                } else {
                    $data['start_date'] = date("Y-m-d", strtotime($vendorStartDate));
                }

                if ($vendorEndDate == null || $vendorEndDate == "") {
                    $data['end_date'] = date("Y-m-d");
                } else {
                    $data['end_date'] = date("Y-m-d", strtotime($vendorEndDate));
                }

                $resolution = $this->input->post("resolution");
                if ($resolution == null || $resolution == "") {
                    $reportBy = 2;
                } else {
                    $reportBy = $resolution;
                }

                if ($reportBy == 1) {
                    $timestamp_diff = 86400;
                }
                if ($reportBy == 2) {
                    $timestamp_diff = 604800;
                }
                if ($reportBy == 3) {
                    $timestamp_diff = (86400 * 30);
                }

                $location_id = $this->input->post("location_id");
                $manufacturer_name = $this->input->post("manufacturer_name");
                $Category_select = $this->input->post("Category_select");


                $data['total_items'] = 0;
                $data['manufacturers'] = 0;
                $data['total_revenue'] = 0.00;
                $data['overall_revenue'] = 0.00;
                $unique_manufacturers = [];
                $unique_orders = [];
                $query = "select sum(oi.total) as order_item_total, pp.vendor_product_id, p.name as product_name, p.category_id, p.manufacturer, oi.* from order_items oi, orders o, product_pricings pp, products p  where o.id=oi.order_id and o.order_status != 'Cancelled' and o.restricted_order='0' and oi.product_id=pp.product_id and p.id=pp.product_id and o.vendor_id=pp.vendor_id and o.vendor_id=$vendor_id and o.created_at >= '" . $data['start_date'] . "' and o.created_at <= '" . $data['end_date'] . " 23:59:59' ";
                if ($location_id != null && $location_id != "") {
                    $query .= " and o.location_id=$location_id ";
                }
                if ($manufacturer_name != null && $manufacturer_name != "") {
                    $query .= " and p.manufacturer='$manufacturer_name'";
                }
                if ($Category_select != null && $Category_select != "") {
                    $query .= " and p.category_id like '%\"$Category_select\"%' ";
                }
                $query .= " group by oi.product_id";
//        echo $query;

                $data['items'] = $this->db->query($query)->result();
                if ($data['items'] != null) {
                    $data['total_items'] = count($data['items']);
                    for ($i = 0; $i < count($data['items']); $i++) {
                        $data['overall_revenue'] += $data['items'][$i]->order_item_total;
                        $data['total_revenue'] += $data['items'][$i]->order_item_total;
                        if (!(in_array($data['items'][$i]->manufacturer, $unique_manufacturers))) {
                            $unique_manufacturers[] = $data['items'][$i]->manufacturer;
                        }
                        if (!(in_array($data['items'][$i]->order_id, $unique_orders))) {
                            $unique_orders[] = $data['items'][$i]->order_id;
                        }
                    }
                }

                $data['manufacturers'] = count($unique_manufacturers);
                if (count($unique_orders) > 0) {
                    $query_order_promos = "select * from order_promotions where restricted_order='0' and order_id in (" . implode(",", $unique_orders) . ")";
                    $result_order_promos = $this->db->query($query_order_promos)->result();
                    if ($result_order_promos != null) {
                        for ($i = 0; $i < count($result_order_promos); $i++) {
                            $data['total_revenue'] -= $result_order_promos[$i]->discount_value;
                        }
                    }
                }

                $classic_categories = $this->Category_model->get_many_by(array('parent_id' => 1));
                $unique_categories = [];
                for ($i = 0; $i < count($classic_categories); $i++) {
                    for ($j = 0; $j < count($data['items']); $j++) {
                        $order_product_categories = explode(",", $data['items'][$j]->category_id);
                        if (in_array('"' . $classic_categories[$i]->id . '"', $order_product_categories)) {
                            $unique_categories[] = $classic_categories[$i]->id;
                        }
                    }
                }
                $data['unique_product_categories'] = array_unique($unique_categories);

                for ($j = 0; $j < count($data['items']); $j++) {
                    $data['items'][$j]->category = "-";
                    if ($Category_select != null && $Category_select != "") {
                        for ($i = 0; $i < count($classic_categories); $i++) {
                            if ($Category_select == $classic_categories[$i]->id) {
                                $data['items'][$j]->category = $classic_categories[$i]->name;
                            }
                        }
                    } else {
                        for ($i = 0; $i < count($classic_categories); $i++) {
                            $order_product_categories = explode(",", $data['items'][$j]->category_id);
                            if (in_array('"' . $classic_categories[$i]->id . '"', $order_product_categories)) {
                                $data['items'][$j]->category = $classic_categories[$i]->name;
                            }
                        }
                    }
                }

                // Product Details
                $query4 = "select oi.total as order_item_total, pp.vendor_product_id, p.name as product_name, p.category_id, p.manufacturer, oi.* from order_items oi, orders o, product_pricings pp, products p  where o.id=oi.order_id and o.order_status != 'Cancelled' and o.restricted_order='0' and o.created_at >= '" . $data['start_date'] . "' and o.created_at <= '" . $data['end_date'] . " 23:59:59' and oi.product_id=pp.product_id and p.id=pp.product_id and o.vendor_id=pp.vendor_id and  o.vendor_id=$vendor_id ";
                if ($location_id != null && $location_id != "") {
                    $query4 .= " and o.location_id=$location_id ";
                }
                if ($manufacturer_name != null && $manufacturer_name != "") {
                    $query4 .= " and p.manufacturer='$manufacturer_name'";
                }
                if ($Category_select != null && $Category_select != "") {
                    $query4 .= " and p.category_id like '%\"$Category_select\"%'";
                }

                $query4 .= " group by oi.product_id order by created_at asc ";
                $data['OrderChart'] = $this->db->query($query4)->result();
                $data['chart_xaxis'] = [];
                $data['chart_yaxis'] = [];
                if ($data['OrderChart'] != null) {
                    for ($k = strtotime($data['start_date']); $k <= strtotime($data['end_date'] . ' 23:59:59'); $k+=$timestamp_diff) {
                        $data['chart_xaxis'][] = date("M d", ($k));
                        $week_total = 0;
                        for ($i = 0; $i < count($data['OrderChart']); $i++) {
                            if (strtotime($data['OrderChart'][$i]->created_at) >= $k && strtotime($data['OrderChart'][$i]->created_at) <= ($k + $timestamp_diff)) {
                                $week_total += $data['OrderChart'][$i]->order_item_total;
                            }
                        }
                        $data['chart_yaxis'][] = $week_total;
                    }
                }
                //  Configuration Filter Events.
                $query5 = "select e.id,e.nickname from product_pricings a INNER JOIN orders b on b.vendor_id=a.vendor_id INNER JOIN user_locations c on c.user_id=b.user_id INNER JOIN organization_groups d on d.user_id=b.user_id INNER JOIN organization_locations e on e.organization_id=d.organization_id where b.restricted_order='0'and a.vendor_id=$vendor_id GROUP by e.id";
                $data['config_locations'] = $this->db->query($query5)->result();

                $query6 = "SELECT b.manufacturer FROM product_pricings a  INNER JOIN products b on b.id=a.product_id WHERE a.vendor_id=$vendor_id group by b.manufacturer";
                $data['config_manufacturer'] = $this->db->query($query6)->result();

                // Categories START ***
                $queryproduct = $this->db->query("select *,f.id as location_id from product_pricings a INNER JOIN products b on b.id=a.product_id INNER JOIN order_items c on c.product_id=a.product_id INNER JOIN orders d on d.id=c.order_id INNER JOIN user_locations e on e.user_id=d.user_id INNER JOIN organization_locations f on f.id=e.organization_location_id where d.restricted_order='0' and a.vendor_id=$vendor_id");
                $data['products'] = $queryproduct->result();
                if ($data['products'] != null) {
                    for ($i = 0; $i < count($data['products']); $i++) {
                        $data['productId'][] = $data['products'][$i]->id;
                        $data['location'][] = $data['products'][$i]->location_id;
                        $data['vendor'][] = $data['products'][$i]->vendor_id;
                        $data['catId'][] = explode(",", str_replace('"', '', $data['products'][$i]->category_id));
                    }
                    $data['catIdCount'] = $data['catId'];
                    $classic_categories = $this->Category_model->get_many_by(array('parent_id' => 1));
                    for ($i = 0; $i < count($classic_categories); $i++) {
                        $count = 0;
                        for ($j = 0; $j < count($data['catIdCount']); $j++) {
                            if (in_array($classic_categories[$i]->id, $data['catIdCount'][$j])) {
                                $count +=1;
                            }
                        }
                        $classic_categories[$i]->count = $count;
                    }
                    $data['classic'] = $classic_categories;
                    $densist_categories = $this->Category_model->get_many_by(array('parent_id' => 2));
                    for ($i = 0; $i < count($densist_categories); $i++) {
                        $count = 0;
                        for ($j = 0; $j < count($data['catIdCount']); $j++) {
                            if (in_array($densist_categories[$i]->id, $data['catIdCount'][$j])) {
                                $count +=1;
                            }
                        }
                        $densist_categories[$i]->count = $count;
                    }
                    $data['dentist'] = $densist_categories;
                }
                // Categories END ***
            }
            $data['reportBy'] = $reportBy;
            $this->load->view('/templates/vendor-admin/reports/sales-rep/chart-render.php', $data);
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    // @CustomerReports
    public function OrderReports_customer() {

        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $data['start_date'] = date("Y-m-d", strtotime("-1 year"));
            $data['end_date'] = date("Y-m-d");
            $user_id = $_SESSION['user_id'];
            $vendor_groups = $this->Vendor_groups_model->get_by(array('user_id' => $user_id));
            $vendor_id = $vendor_groups->vendor_id;
            $startDate = date("Y-m-d", strtotime("-1 year"));
            $endDate = date("Y-m-d", now());
            $reportBy = 2;     //   1 => Day; 2 => Week; 3 => Month;
            if ($vendor_id != null) {

                $user_count_query = "select count(*) as user_count, sum(total) as order_total, user_id, created_at from orders where order_status != 'Cancelled' and restricted_order='0' and vendor_id=" . $vendor_id . " group by user_id order by created_at asc";
                $data['user_count_result'] = $this->db->query($user_count_query)->result();

                $new_users = 0;
                $total_order_value = 0;
                $user_ids = [];
                $organizations = 0;
                if ($data['user_count_result'] != null) {
                    for ($i = 0; $i < count($data['user_count_result']); $i++) {
                        $user_ids[] = $data['user_count_result'][$i]->user_id;
                        $total_order_value += $data['user_count_result'][$i]->order_total;
                        if ($data['user_count_result'][$i]->user_count == 1) {
                            $new_users +=1;
                        }
                        $data['user_count_result'][$i]->first_name = "";
                        $data['user_count_result'][$i]->organization_name = "";
                        $user_query = "select u.first_name, o.organization_name from users u, organizations o, organization_groups og where o.id=og.organization_id and u.id=og.user_id and u.id=" . $data['user_count_result'][$i]->user_id . " limit 1";
                        $user_result = $this->db->query($user_query)->result();
                        if ($user_result != null) {
                            $data['user_count_result'][$i]->first_name = $user_result[0]->first_name;
                            $data['user_count_result'][$i]->organization_name = $user_result[0]->organization_name;
                        }
                    }
                    $organization_count_query = "select distinct(organization_id) from organization_groups where user_id in (" . join(",", $user_ids) . ");";
                    $organization_count_result = $this->db->query($organization_count_query)->result();
                    if ($organization_count_result != null) {
                        $organizations = count($organization_count_result);
                    }
                }

                if ($total_order_value == 0) {
                    $total_order_value = 1;
                }

                $data['total_order_value'] = $total_order_value;
                $data['new_users'] = $new_users;
                $data['organizations'] = $organizations;


                //  Configuration Filter Events.
                $query5 = "select e.id,e.nickname from product_pricings a INNER JOIN orders b on b.vendor_id=a.vendor_id INNER JOIN user_locations c on c.user_id=b.user_id INNER JOIN organization_groups d on d.user_id=b.user_id INNER JOIN organization_locations e on e.organization_id=d.organization_id where b.restricted_order='0' and  a.vendor_id=$vendor_id GROUP by e.id";
                $data['config_locations'] = $this->db->query($query5)->result();

                $data['chart_xaxis'] = [];
                $data['chart_yaxis'] = [];
                $timestamp_diff = 608400;

                for ($k = strtotime("-1 year"); $k <= strtotime("now"); $k+=$timestamp_diff) {
                    $data['chart_xaxis'][] = date("M d", ($k));
                    $week_total = 0;
                    for ($i = 0; $i < count($data['user_count_result']); $i++) {
                        if (strtotime($data['user_count_result'][$i]->created_at) >= $k && strtotime($data['user_count_result'][$i]->created_at) <= ($k + $timestamp_diff)) {
                            $week_total += 1;
                        }
                    }
                    $data['chart_yaxis'][] = $week_total;
                }
            }
            $data['start_date'] = $startDate;
            $data['end_date'] = $endDate;
            $data['reportBy'] = $reportBy; // Defining Day || Week || Month.
            $data['My_vendor_users'] = "";
            $data['vendor_shipping'] = "";
            $data['product_details'] = "";    // The Object is Defined for Delete-product.php Modal
            $data['promoCodes_active'] = "";
            $data['NorderCount'] = order_count(); // To Get the Latest Order Count.
            $data['ReturnCount'] = return_count();

            $data['vendorOrderFilter'] = "VendorCustomerFilter";
            $this->load->view('/templates/vendor-admin/reports/customer-rep/index.php', $data);
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    // Vendor Customer Render
    public function OrderCustomer_Render() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $vendor_id = $_SESSION['vendor_id'];
            if ($vendor_id != null) {

                $vendorStartDate = $this->input->post("vendorStartDate");
                $vendorEndDate = $this->input->post("vendorEndDate");

                if ($vendorStartDate == null || $vendorStartDate == "") {
                    $data['start_date'] = date("Y-m-d", strtotime("-1 year"));
                } else {
                    $data['start_date'] = date("Y-m-d", strtotime($vendorStartDate));
                }

                if ($vendorEndDate == null || $vendorEndDate == "") {
                    $data['end_date'] = date("Y-m-d");
                } else {
                    $data['end_date'] = date("Y-m-d", strtotime($vendorEndDate));
                }

                $resolution = $this->input->post("resolution");
                if ($resolution == null || $resolution == "") {
                    $reportBy = 2;
                } else {
                    $reportBy = $resolution;
                }

                if ($reportBy == 1) {
                    $timestamp_diff = 86400;
                }
                if ($reportBy == 2) {
                    $timestamp_diff = 604800;
                }
                if ($reportBy == 3) {
                    $timestamp_diff = (86400 * 30);
                }

                $location_id = $this->input->post("location_id");


                $user_count_query = "select count(*) as user_count, sum(total) as order_total, user_id, created_at from orders where order_status != 'Cancelled' and restricted_order='0' and created_at >= '" . $data['start_date'] . "' and created_at <= '" . $data['end_date'] . "' and vendor_id=" . $vendor_id;

                if ($location_id != null && $location_id != "") {
                    $user_count_query .= " and location_id=" . $location_id;
                }

                $user_count_query .= " group by user_id order by created_at asc";


                $data['user_count_result'] = $this->db->query($user_count_query)->result();

                $new_users = 0;
                $total_order_value = 0;
                $user_ids = [];
                $organizations = 0;
                if ($data['user_count_result'] != null) {
                    for ($i = 0; $i < count($data['user_count_result']); $i++) {
                        $user_ids[] = $data['user_count_result'][$i]->user_id;
                        $total_order_value += $data['user_count_result'][$i]->order_total;
                        if ($data['user_count_result'][$i]->user_count == 1) {
                            $new_users +=1;
                        }
                        $data['user_count_result'][$i]->first_name = "";
                        $data['user_count_result'][$i]->organization_name = "";
                        $user_query = "select u.first_name, o.organization_name from users u, organizations o, organization_groups og where o.id=og.organization_id and u.id=og.user_id and u.id=" . $data['user_count_result'][$i]->user_id . " limit 1";
                        $user_result = $this->db->query($user_query)->result();
                        if ($user_result != null) {
                            $data['user_count_result'][$i]->first_name = $user_result[0]->first_name;
                            $data['user_count_result'][$i]->organization_name = $user_result[0]->organization_name;
                        }
                    }
                    $organization_count_query = "select distinct(organization_id) from organization_groups where user_id in (" . join(",", $user_ids) . ");";
                    $organization_count_result = $this->db->query($organization_count_query)->result();
                    if ($organization_count_result != null) {
                        $organizations = count($organization_count_result);
                    }
                }

                if ($total_order_value == 0) {
                    $total_order_value = 1;
                }

                $data['total_order_value'] = $total_order_value;
                $data['new_users'] = $new_users;
                $data['organizations'] = $organizations;

                $data['chart_xaxis'] = [];
                $data['chart_yaxis'] = [];

                for ($k = strtotime($data['start_date']); $k <= strtotime($data['end_date'] . ' 23:59:59'); $k+=$timestamp_diff) {
                    $data['chart_xaxis'][] = date("M d", ($k));
                    $week_total = 0;
                    for ($i = 0; $i < count($data['user_count_result']); $i++) {
                        if (strtotime($data['user_count_result'][$i]->created_at) >= $k && strtotime($data['user_count_result'][$i]->created_at) <= ($k + $timestamp_diff)) {
                            $week_total += 1;
                        }
                    }
                    $data['chart_yaxis'][] = $week_total;
                }
            }
            $data['reportBy'] = $reportBy;
            $this->load->view('/templates/vendor-admin/reports/customer-rep/chart-customer-render.php', $data);
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    // @VendorShipping Reports
    public function OrderReports_Shipping() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $data['start_date'] = date("Y-m-d", strtotime("-1 year")); // 1 year
            $data['end_date'] = date("Y-m-d");
            $user_id = $_SESSION['user_id'];
            $vendor_groups = $this->Vendor_groups_model->get_by(array('user_id' => $user_id));
            $vendor_id = $vendor_groups->vendor_id;
            $startDate = date("Y-m-d", strtotime("-1 year"));
            $endDate = date("Y-m-d", now());
            $reportBy = 2;     //   1 => Day; 2 => Week; 3 => Month;
            if ($vendor_id != null) {

                $shipment_count_query = "select so.carrier, so.shipping_type, o.shipment_id, sum(o.shipping_price) as shipping_total, count(*) as orders_count, o.created_at from orders o, shipping_options so where o.shipment_id=so.id and o.order_status != 'Cancelled'  and o.restricted_order='0' and o.vendor_id=" . $vendor_id . " group by o.shipment_id order by o.shipping_price desc";
                $data['shipment_count_result'] = $this->db->query($shipment_count_query)->result();

                $shipping_methods = 0;
                $total_shipment_value = 0;
                $total_orders = 0;
                if ($data['shipment_count_result'] != null) {
                    for ($i = 0; $i < count($data['shipment_count_result']); $i++) {
                        $shipping_methods += 1;
                        $total_shipment_value += $data['shipment_count_result'][$i]->shipping_total;
                        $total_orders += $data['shipment_count_result'][$i]->orders_count;
                    }
                }

                if ($total_shipment_value == 0) {
                    $total_shipment_value = 1;
                }

                $data['shipping_methods'] = $shipping_methods;
                $data['total_shipment_value'] = $total_shipment_value;
                $data['total_orders'] = $total_orders;

                $vendor_total_shipping_query = "select o.created_at as order_created_at, o.id as orders_order_id, o.*, ot.* from orders o left join order_trackings ot on ot.order_id=o.id where o.order_status != 'Cancelled' and o.restricted_order='0' and o.vendor_id=" . $vendor_id . " group by o.id order by order_created_at";
                $vendor_total_shipping_result = $this->db->query($vendor_total_shipping_query)->result();

                $total_shipments = 0;
                if ($vendor_total_shipping_result != null) {
                    for ($i = 0; $i < count($vendor_total_shipping_result); $i++) {
                        if ($vendor_total_shipping_result[$i]->package_id1 != "") {
                            $total_shipments += 1;
                        }
                        if ($vendor_total_shipping_result[$i]->package_id2 != "") {
                            $total_shipments += 1;
                        }
                        if ($vendor_total_shipping_result[$i]->package_id3 != "") {
                            $total_shipments += 1;
                        }
                        if ($vendor_total_shipping_result[$i]->package_id4 != "") {
                            $total_shipments += 1;
                        }
                        if ($vendor_total_shipping_result[$i]->package_id5 != "") {
                            $total_shipments += 1;
                        }
                    }
                }

                $data['total_shipments'] = $total_shipments;

                $data['vendor_total_shipping_result'] = $vendor_total_shipping_result;


                //  Configuration Filter Events.
                $query5 = "select e.id,e.nickname from product_pricings a INNER JOIN orders b on b.vendor_id=a.vendor_id INNER JOIN user_locations c on c.user_id=b.user_id INNER JOIN organization_groups d on d.user_id=b.user_id INNER JOIN organization_locations e on e.organization_id=d.organization_id where  b.restricted_order='0' and a.vendor_id=$vendor_id GROUP by e.id";
                $data['config_locations'] = $this->db->query($query5)->result();

                $data['chart_xaxis'] = [];
                $data['chart_yaxis'] = [];
                $timestamp_diff = 608400;

                for ($k = strtotime("-1 year"); $k <= strtotime("now"); $k+=$timestamp_diff) {
                    $data['chart_xaxis'][] = date("M d", ($k));
                    $week_total = 0;
                    for ($i = 0; $i < count($data['vendor_total_shipping_result']); $i++) {
                        if (strtotime($data['vendor_total_shipping_result'][$i]->order_created_at) >= $k && strtotime($data['vendor_total_shipping_result'][$i]->order_created_at) <= ($k + $timestamp_diff)) {
                            $week_total += $data['vendor_total_shipping_result'][$i]->shipping_price;
                        }
                    }
                    $data['chart_yaxis'][] = $week_total;
                }
            }
            $data['total_item'] = "";
            $data['Manufacturers'] = "";
            $data['reportBy'] = $reportBy; // Defining Day || Week || Month.
            $data['My_vendor_users'] = "";
            $data['vendor_shipping'] = "";
            $data['product_details'] = "";    // The Object is Defined for Delete-product.php Modal
            $data['promoCodes_active'] = "";
            $data['NorderCount'] = order_count(); // To Get the Latest Order Count.
            $data['ReturnCount'] = return_count();
            $data['start_date'] = $startDate;
            $data['end_date'] = $endDate;
            $data['vendorOrderFilter'] = "VendorShippingFilter";
            $this->load->view('/templates/vendor-admin/reports/shipping-rep/index.php', $data);
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function OrderShipping_Render() {
        $admin_roles = unserialize(ROLES_VENDORS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $vendor_id = $_SESSION['vendor_id'];
            if ($vendor_id != null) {

                $vendorStartDate = $this->input->post("vendorStartDate");
                $vendorEndDate = $this->input->post("vendorEndDate");

                if ($vendorStartDate == null || $vendorStartDate == "") {
                    $data['start_date'] = date("Y-m-d", strtotime("-1 year"));
                } else {
                    $data['start_date'] = date("Y-m-d", strtotime($vendorStartDate));
                }

                if ($vendorEndDate == null || $vendorEndDate == "") {
                    $data['end_date'] = date("Y-m-d");
                } else {
                    $data['end_date'] = date("Y-m-d", strtotime($vendorEndDate));
                }

                $resolution = $this->input->post("resolution");
                if ($resolution == null || $resolution == "") {
                    $reportBy = 2;
                } else {
                    $reportBy = $resolution;
                }

                if ($reportBy == 1) {
                    $timestamp_diff = 86400;
                }
                if ($reportBy == 2) {
                    $timestamp_diff = 604800;
                }
                if ($reportBy == 3) {
                    $timestamp_diff = (86400 * 30);
                }

                $location_id = $this->input->post("location_id");


                $shipment_count_query = "select so.carrier, so.shipping_type, o.shipment_id, sum(o.shipping_price) as shipping_total, count(*) as orders_count, o.created_at from orders o, shipping_options so where o.shipment_id=so.id and o.order_status != 'Cancelled' and o.restricted_order='0' and o.created_at >= '" . $data['start_date'] . "' and o.created_at <= '" . $data['end_date'] . " 23:59:59' and o.vendor_id=" . $vendor_id;

                if ($location_id != null && $location_id != "") {
                    $shipment_count_query .= " and location_id=" . $location_id;
                }

                $shipment_count_query .= " group by o.shipment_id order by o.shipping_price desc";

                $data['shipment_count_result'] = $this->db->query($shipment_count_query)->result();

                $shipping_methods = 0;
                $total_shipment_value = 0;
                $total_orders = 0;
                if ($data['shipment_count_result'] != null) {
                    for ($i = 0; $i < count($data['shipment_count_result']); $i++) {
                        $shipping_methods += 1;
                        $total_shipment_value += $data['shipment_count_result'][$i]->shipping_total;
                        $total_orders += $data['shipment_count_result'][$i]->orders_count;
                    }
                }

                if ($total_shipment_value == 0) {
                    $total_shipment_value = 1;
                }

                $data['shipping_methods'] = $shipping_methods;
                $data['total_shipment_value'] = $total_shipment_value;
                $data['total_orders'] = $total_orders;

                $vendor_total_shipping_query = "select o.created_at as order_created_at, o.id as orders_order_id, o.*, ot.* from orders o left join order_trackings ot on ot.order_id=o.id where o.order_status != 'Cancelled' and o.restricted_order='0' and o.created_at >= '" . $data['start_date'] . "' and o.created_at <= '" . $data['end_date'] . " 23:59:59' and o.vendor_id=" . $vendor_id;
                if ($location_id != null && $location_id != "") {
                    $vendor_total_shipping_query .= " and o.location_id=" . $location_id;
                }
                $vendor_total_shipping_query .= " group by o.id order by order_created_at";
                $vendor_total_shipping_result = $this->db->query($vendor_total_shipping_query)->result();

                $total_shipments = 0;
                if ($vendor_total_shipping_result != null) {
                    for ($i = 0; $i < count($vendor_total_shipping_result); $i++) {
                        if ($vendor_total_shipping_result[$i]->package_id1 != "") {
                            $total_shipments += 1;
                        }
                        if ($vendor_total_shipping_result[$i]->package_id2 != "") {
                            $total_shipments += 1;
                        }
                        if ($vendor_total_shipping_result[$i]->package_id3 != "") {
                            $total_shipments += 1;
                        }
                        if ($vendor_total_shipping_result[$i]->package_id4 != "") {
                            $total_shipments += 1;
                        }
                        if ($vendor_total_shipping_result[$i]->package_id5 != "") {
                            $total_shipments += 1;
                        }
                    }
                }

                $data['total_shipments'] = $total_shipments;

                $data['vendor_total_shipping_result'] = $vendor_total_shipping_result;


                //  Configuration Filter Events.
                $query5 = "select e.id,e.nickname from product_pricings a INNER JOIN orders b on b.vendor_id=a.vendor_id INNER JOIN user_locations c on c.user_id=b.user_id INNER JOIN organization_groups d on d.user_id=b.user_id INNER JOIN organization_locations e on e.organization_id=d.organization_id where b.restricted_order='0' and a.vendor_id=$vendor_id GROUP by e.id";
                $data['config_locations'] = $this->db->query($query5)->result();

                $data['chart_xaxis'] = [];
                $data['chart_yaxis'] = [];

                for ($k = strtotime($data['start_date']); $k <= strtotime($data['end_date'] . ' 23:59:59'); $k+=$timestamp_diff) {
                    $data['chart_xaxis'][] = date("M d", ($k));
                    $week_total = 0;
                    for ($i = 0; $i < count($data['vendor_total_shipping_result']); $i++) {
                        if (strtotime($data['vendor_total_shipping_result'][$i]->order_created_at) >= $k && strtotime($data['vendor_total_shipping_result'][$i]->order_created_at) <= ($k + $timestamp_diff)) {
                            $week_total += $data['vendor_total_shipping_result'][$i]->shipping_price;
                        }
                    }
                    $data['chart_yaxis'][] = $week_total;
                }
            }
            $data['reportBy'] = $resolution;
            $this->load->view('/templates/vendor-admin/reports/shipping-rep/chart-render-shipping.php', $data);
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

}

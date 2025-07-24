<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/third_party/spout/src/Spout/Autoloader/autoload.php';

// Use the Spout Namespaces lets
use Box \ Spout \ Reader \ ReaderFactory;
use Box \ Spout \ Writer \ WriterFactory;
use Box \ Spout \ Common \ Type;

class SuperAdminDashboard extends MW_Controller {

    function __construct() {

        parent::__construct();
        $this->load->library('elasticsearch');
        $this->load->helper('MY_privilege_helper');
        $this->load->helper('MY_support_helper');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->model('Images_model');
        $this->load->model('Order_model');
        $this->load->model('Organization_groups_model');
        $this->load->model('Organization_model');
        $this->load->model('Products_model');
        $this->load->model('Product_answer_model');
        $this->load->model('Product_pricing_model');
        $this->load->model('Product_question_model');
        $this->load->model('Prepopulated_list_model');
        $this->load->model('Prepopulated_product_model');
        $this->load->model('Product_custom_field_model');
        $this->load->model('BuyingClub_model');
        $this->load->model('Role_model');
        $this->load->model('User_model');
        $this->load->library('auth');
        $this->load->model('Vendor_model');
        $this->load->model('Vendor_groups_model');
        $this->load->library('email'); // load email library
        $this->load->helper('my_email_helper');


        $this->load->library('session');
    }

    public function getAll_vendors() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $search = $this->input->get("search");
            $data['limit'] = 30;
            $valuein = $this->input->get("p");
            $offset = 0;
            if ($valuein != null) {
                $offset = ($valuein - 1) * $data['limit'];
            }
            if ($search != null) {
                $data['vendors'] = $this->Vendor_model->limit($data['limit'], $offset)->get_many_by(array("name like" => '%' . $search . '%'));
                $query1 = "SELECT count(*) as search_count FROM vendors a  WHERE  a.name like '%$search%'";
                $count_query = $this->db->query($query1)->result();
                if ($count_query != null) {
                    $data['total_count'] = $count_query[0]->search_count;
                }
            } else {
                $data['vendors'] = $this->Vendor_model->limit($data['limit'], $offset)->get_all();
                $query1 = "SELECT count(*) as search_count FROM vendors a  WHERE  a.name like '%$search%'";
                $count_query = $this->db->query($query1)->result();
                if ($count_query != null) {
                    $data['total_count'] = $count_query[0]->search_count;
                }
            }
            $this->load->library('pagination');
            $config['base_url'] = base_url() . '/vendorsIn-list';
            $config['total_rows'] = $data['total_count'];
            $config['per_page'] = $data['limit'];
            $this->pagination->initialize($config);
            $config['enable_query_strings'] = TRUE;
            $config['page_query_string'] = TRUE;
            $config['use_page_numbers'] = TRUE;

            $total = 0;
            for ($i = 0; $i < count($data['vendors']); $i++) {
                $vendor_groups = $this->Vendor_groups_model->get_many_by(array('vendor_id' => $data['vendors'][$i]->id));
                if ($vendor_groups != null) {
                    $data['vendors'][$i]->total_users = count($vendor_groups);
                }
                $data['vendors'][$i]->total = $this->Order_model->get_VendorSalesTotal($data['vendors'][$i]->id);
            }
            $data['user_approval'] = user_counts();
            $data['flagged_count'] = flagged_count();
            $data['answer_count'] = flaggedAnswer_count();
            $this->load->view('/templates/_inc/header-admin.php');
            $this->load->view('/templates/admin/vendors/index.php', $data);
            $this->load->view('/templates/_inc/footer-admin.php');
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function getAll_products() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $search = $this->input->get('search');
            if ($search == null) {
                $search = "";
            }
            $vendor_id = $this->input->get('vendor_id');
            if ($vendor_id != null) {
                $data['vendors'] = $this->Vendor_model->get_many_by(array('active' => 1));
            } else {
                $data['vendors'] = $this->Vendor_model->get($vendor_id);
            }
            $data['limit'] = 30;
            $valuein = $this->input->get("p");
            $offset = 0;
            if ($valuein != null) {
                $offset = ($valuein - 1) * $data['limit'];
            }
            Debugger::debug($search, 'running search');
            if ($search != null && $search != "") {
                if ($vendor_id != null) {
                    $query = "SELECT  z.* FROM products z INNER JOIN product_pricings y on z.id=y.product_id INNER JOIN vendors x on x.id=y.vendor_id where z.mpn like '%$search%' or x.name like '%$search%' or z.manufacturer like '%$search%' or z.description like '%$search%' or z.name like '%$search%'  and y.vendor_id=$vendor_id group by z.id limit $offset," . $data['limit'] . "";
                    Debugger::debug($query);
                    $data['products'] = $this->db->query($query)->result();
                    $data['total_count'] = 0;
                    $query1 = "SELECT count(*) as search_count FROM (select distinct z.id from products z INNER JOIN product_pricings y on z.id=y.product_id INNER JOIN vendors x on x.id=y.vendor_id where z.mpn like '%$search%' or x.name like '%$search%' or z.manufacturer like '%$search%' or z.description like '%$search%' or z.name like '%$search%' and y.vendor_id=$vendor_id) count";
                    $count_query = $this->db->query($query1)->result();
                    if ($count_query != null) {
                        $data['total_count'] = $count_query[0]->search_count;
                    }
                } else {
                    $query = "SELECT  z.* FROM products z INNER JOIN product_pricings y on z.id=y.product_id INNER JOIN vendors x on x.id=y.vendor_id where z.mpn like '%$search%' or x.name like '%$search%' or z.manufacturer like '%$search%' or z.description like '%$search%' or z.name like '%$search%' OR y.vendor_product_id LIKE '%$search%' OR y.matix_id LIKE '%$search%' group by z.id limit $offset," . $data['limit'] . "";
                    Debugger::debug($query);
                    $data['products'] = $this->db->query($query)->result();
                    $data['total_count'] = 0;
                    $query1 = "SELECT count(*) as search_count FROM (select distinct z.id from products z INNER JOIN product_pricings y on z.id=y.product_id INNER JOIN vendors x on x.id=y.vendor_id where z.mpn like '%$search%' or x.name like '%$search%' or z.manufacturer like '%$search%' or z.description like '%$search%' or z.name like '%$search%') count";
                    $count_query = $this->db->query($query1)->result();
                    if ($count_query != null) {
                        $data['total_count'] = $count_query[0]->search_count;
                    }
                }
            } else {
                if ($vendor_id != null) {
                    $query = "SELECT b.* FROM product_pricings a INNER JOIN products b on b.id=a.product_id WHERE a.vendor_id=$vendor_id limit $offset," . $data['limit'] . "";
                    $data['products'] = $this->db->query($query)->result();
                    $data['total_count'] = 0;
                    $query1 = "SELECT count(*) as search_count FROM (SELECT b.* FROM product_pricings a INNER JOIN products b on b.id=a.product_id WHERE a.vendor_id=$vendor_id) count";
                    $count_query = $this->db->query($query1)->result();
                    if ($count_query != null) {
                        $data['total_count'] = $count_query[0]->search_count;
                    }
                } else {
                    $data['products'] = $this->Products_model->limit($data['limit'], $offset)->get_all();
                    $data['total_count'] = $this->Products_model->count_all();
                }
            }
            $this->load->library('pagination');
            $config['base_url'] = base_url() . '/product-catalog';
            $config['total_rows'] = $data['total_count'];
            $config['per_page'] = $data['limit'];
            $this->pagination->initialize($config);
            $config['enable_query_strings'] = TRUE;
            $config['page_query_string'] = TRUE;
            $config['use_page_numbers'] = TRUE;

            foreach ($data['products'] as $value) {
                $value->low_price = 10000000;
                $value->total = 0;
                $value->avg_price = 0;
                $value->vendor_name = array();
                if ($vendor_id != null) {
                    $data['product_price'] = $this->Product_pricing_model->get_many_by(array('product_id' => $value->id, 'vendor_id' => $vendor_id));
                } else {
                    $data['product_price'] = $this->Product_pricing_model->get_many_by(array('product_id' => $value->id, 'active' => '1'));
                }

                if ($data['product_price'] != null) {
                    for ($i = 0; $i < count($data['product_price']); $i++) {

                        // Set price variables

                        $regular_price = (isset($data['product_price'][$i]->price)) ? $data['product_price'][$i]->price : 0.00;

                        $retail_price = (isset($data['product_price'][$i]->retail_price)) ? $data['product_price'][$i] : 0.00;

                        // Correct pricing inconsistencies

                        if($regular_price == 0 && $retail_price != 0)
                        {
                            // Swap values
                            list($data['product_price'][$i]->price,$data['product_price'][$i]->retail_price) = array($data['product_price'][$i]->retail_price,$data['product_price'][$i]->price);
                        }

                        if ($value->low_price > $data['product_price'][$i]->price) {
                            $value->low_price = $data['product_price'][$i]->price;
                        }
                        $value->total += $data['product_price'][$i]->price;

                        $data['vendor_details'] = $this->Vendor_model->get_by(array('id' => $data['product_price'][$i]->vendor_id));
                        if ($data['vendor_details'] != null) {
                            $value->vendor_name[] = $data['vendor_details']->id . "-" . $data['vendor_details']->name;
                        }
                    }
                    $value->avg_price = $value->total / count($data['product_price']);
                }
            }
            //  Pre-Populated List(S)
            $data['pre_populatedLists'] = $this->Prepopulated_list_model->get_many_by(array('user_id' => '0'));
            $data['user_approval'] = user_counts();
            $data['flagged_count'] = flagged_count();
            $data['answer_count'] = flaggedAnswer_count();
            $data['My_vendor_users'] = "";
            $data['vendor_shipping'] = "";
            $data['product_details'] = "";    // The Object is Defined for Delete-product.php Modal
            $data['promoCodes_active'] = "";
            $this->load->view('/templates/_inc/header-admin.php');
            $this->load->view('/templates/admin/catalog/index.php', $data);
            $this->load->view('/templates/_inc/footer-admin.php');
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function getAll_AdminUsers() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $user_id = $_SESSION['user_id'];
            $search = $this->input->post('search');
            if ($search != null) {
                $query = "SELECT * FROM users a WHERE (a.email like '%$search%' or a.first_name like '%$search%' or a.last_name like '%$search%') and  a.role_id in(1,2) and a.id not in($user_id)";
                $data['superAdmin'] = $this->db->query($query)->result();
            } else {
                $data['superAdmin'] = $this->User_model->get_many_by(array('role_id in(1,2) and id not in(' . $user_id . ')'));
            }
            if ($data['superAdmin'] != null) {
                for ($i = 0; $i < count($data['superAdmin']); $i++) {
                    $roles = $this->Role_model->get_by(array('id' => $data['superAdmin'][$i]->role_id));
                    $data['superAdmin'][$i]->role_name = $roles->role_name;
                }
            }
            $data['user_approval'] = user_counts();
            $data['flagged_count'] = flagged_count();
            $data['answer_count'] = flaggedAnswer_count();
            $data['My_vendor_users'] = "";
            $data['vendor_shipping'] = "";
            $data['promoCodes_active'] = "";
            $data['adminUserPage'] = 1;
            $this->load->view('/templates/_inc/header-admin.php');
            $this->load->view('templates/admin/users/index.php', $data);
            $this->load->view('/templates/_inc/footer-admin.php');

        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function getAll_customers() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $search = $this->input->get('search');
            $data['limit'] = 30;
            $valuein = $this->input->get("p");
            $offset = 0;
            if ($valuein != null) {
                $offset = ($valuein - 1) * $data['limit'];
            }
            if ($search != null && $search != "") {
                $query = "SELECT r.status,r.id,r.first_name,s.organization_name,r.created_at,q.role_name FROM organizations s INNER JOIN organization_groups p on s.id = p.organization_id INNER JOIN users r on r.id = p.user_id INNER JOIN roles q on r.role_id = q.id where r.role_id not in(11,1,2) and (r.first_name LIKE '%$search%' or s.organization_name LIKE '%$search%') group by r.id limit $offset," . $data['limit'] . "";
                $data['organizations_list'] = $this->db->query($query)->result();
                $query1 = "SELECT count(*) as search_count FROM (SELECT r.status,r.id,r.first_name,s.organization_name,r.created_at,q.role_name FROM organizations s INNER JOIN organization_groups p on s.id = p.organization_id INNER JOIN users r on r.id = p.user_id INNER JOIN roles q on r.role_id = q.id where r.role_id not in(11,1,2) and (r.first_name LIKE '%$search%' or s.organization_name LIKE '%$search%') group by r.id) count";
                $count_query = $this->db->query($query1)->result();
                if ($count_query != null) {
                    $data['total_count'] = $count_query[0]->search_count;
                }
            } else {
                $query = "SELECT r.status,r.id,r.first_name,s.organization_name,r.created_at,q.role_name FROM organizations s INNER JOIN organization_groups p on s.id = p.organization_id INNER JOIN users r on r.id = p.user_id INNER JOIN roles q on r.role_id = q.id where r.role_id not in(11,1,2) group by r.id limit $offset," . $data['limit'] . "";
                $data['organizations_list'] = $this->db->query($query)->result();
                $query1 = "SELECT count(*) as search_count FROM (SELECT r.status,r.id,r.first_name,s.organization_name,r.created_at,q.role_name FROM organizations s INNER JOIN organization_groups p on s.id = p.organization_id INNER JOIN users r on r.id = p.user_id INNER JOIN roles q on r.role_id = q.id where r.role_id not in(11,1,2) group by r.id) count";
                $count_query = $this->db->query($query1)->result();
                if ($count_query != null) {
                    $data['total_count'] = $count_query[0]->search_count;
                }
            }
            $this->load->library('pagination');
            $config['base_url'] = base_url() . '/customer-list';
            $config['total_rows'] = $data['total_count'];
            $config['per_page'] = $data['limit'];
            $this->pagination->initialize($config);
            $config['enable_query_strings'] = TRUE;
            $config['page_query_string'] = TRUE;
            $config['use_page_numbers'] = TRUE;
            // The below Object is used in MODAL to show all organization. 1. To invite customer for a organization
            $data['organizations'] = $this->Organization_model->get_all();
            $data['user_approval'] = user_counts();
            $data['flagged_count'] = flagged_count();
            $data['answer_count'] = flaggedAnswer_count();
            $data['My_vendor_users'] = "";
            $data['vendor_shipping'] = "";
            $data['promoCodes_active'] = "";
            $this->load->view('/templates/_inc/header-admin.php');
            $this->load->view('/templates/admin/customers/index.php', $data);
            $this->load->view('/templates/_inc/footer-admin.php');
           
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function vendors_sales_report() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $vendor_id = $this->input->get('vendor_id');
            $data['search'] = $this->input->post("search");
            $data['vendor_report'] = $this->Vendor_model->get_by(array('id' => $vendor_id));
            if ($data['vendor_report'] == null) {
                $this->session->set_flashdata('error', 'Invalid Entry');
                header('Location: vendorsIn-list');
            } else {
                $start_date = date("Y-m-d", strtotime("-1 year"));
//                SELECT * FROM `orders` where created_at BETWEEN YEAR('2016-01-01') and now() and vendor_id=1;
                $orders = $this->Order_model->get_many_by(array("created_at BETWEEN YEAR('" . $start_date . "') and now()", 'vendor_id' => $vendor_id, 'restricted_order' => '0'));
//                $products = $this->Product_pricing_model->get_many_by(array('vendor_id' => $vendor_id));
                $data['vendor_groups'] = $this->Vendor_groups_model->get_many_by(array('vendor_id' => $vendor_id));
                $data['vendor_report']->total_orders = "";
                $data['vendor_report']->total_products = "";
                $data['vendor_report']->vendors_count = "";
                $data['vendor_report']->total = "";
                $data['vendor_report']->monthly_total = "";
                $vendor_users = [];
                $data['vendor_users'] = "";
                if ($data['vendor_groups'] != null) {
                    $data['vendor_report']->vendors_count = count($data['vendor_groups']);
                    for ($i = 0; $i < count($data['vendor_groups']); $i++) {
                        if ($data['search'] == null) {
                            $user = $this->User_model->get_by(array('id' => $data['vendor_groups'][$i]->user_id));
                            if ($user != null) {
                                $vendor_users[] = $user;
                            }
                        } else {
                            $query = "SELECT * from users where id=" . $data['vendor_groups'][$i]->user_id . " and (email like '%" . $data['search'] . "%' or first_name like '%" . $data['search'] . "%' or last_name like '%" . $data['search'] . "%') limit 1";
                            $result = $this->db->query($query)->row();
                            if ($result != null) {
                                $vendor_users[] = $result;
                            }
                        }
                    }
                    $data['vendor_users'] = $vendor_users;
                }
                $data['vendor_report']->total_products = 0;
                $data['vendor_products'] = $this->db->query("select count(*) as vendor_product_count from product_pricings where vendor_id=$vendor_id")->result();
                if ($data['vendor_products'] != null) {
                    //      To get the Number of Products does vendor have for Sales in Dentomatix.
                    $data['vendor_report']->total_products = $data['vendor_products'][0]->vendor_product_count;
                }
                if ($orders != null) {
                    $total = "";
                    $data['vendor_report']->total_orders = count($orders);
                    foreach ($orders as $row) {
                        $total = $total + $row->total;
                        $data['vendor_report']->total = $total;
                    }
                }
                $date = date('Y-m-01');
                $now = date('Y-m-d', now());
                $monthly_orders = $this->Order_model->get_many_by(array("created_at BETWEEN '" . $date . "' and '" . $now . "'", 'vendor_id' => $vendor_id, 'restricted_order' => '0'));
                if ($monthly_orders != null) {
                    $total = 0;
                    foreach ($monthly_orders as $row) {
                        $total = $total + $row->total;
                        $data['vendor_report']->monthly_total = $total;
                    }
                }
            }
            $data['user_approval'] = user_counts();
            $data['flagged_count'] = flagged_count();
            $data['answer_count'] = flaggedAnswer_count();
            $data['vendor_userInvitationId'] = $vendor_id;
            $data['My_vendor_users'] = "";
            $data['vendor_shipping'] = "";
            $data['promoCodes_active'] = "";
            $this->load->view('/templates/_inc/header-admin.php');
            $this->load->view('templates/admin/vendors/v/number/index.php', $data);
            $this->load->view('/templates/_inc/footer-admin.php');
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function superAdmin_account() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $user_id = $_SESSION['user_id'];
            $data['superAdmin_account'] = $this->User_model->get_by(array('id' => $user_id));
            if ($data['superAdmin_account'] != null) {
                $data['profile_image'] = $this->Images_model->get_by(array('model_name' => 'user', 'model_id' => $data['superAdmin_account']->id));
            }
            $c_date = strtotime("now");
            $password_changed = strtotime($data['superAdmin_account']->password_last_updated_at);
            if ($password_changed != "") {
                $changed_time = $c_date - $password_changed;
                $data['password_last_updated'] = $this->User_model->humanTiming($changed_time);
            } else {
                $password_changed = strtotime($data['superAdmin_account']->created_at);
                $changed_time = $c_date - $password_changed;
                $data['password_last_updated'] = $this->User_model->humanTiming($changed_time);
            }
            $data['user_approval'] = user_counts();
            $data['flagged_count'] = flagged_count();
            $data['answer_count'] = flaggedAnswer_count();
            $data['My_vendor_users'] = "";
            $data['vendor_shipping'] = "";
            $data['promoCodes_active'] = "";
            $this->load->view('/templates/_inc/header-admin.php');
            $this->load->view('templates/admin/account/index.php', $data);
            $this->load->view('/templates/_inc/footer-admin.php');
            
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function Admin_profile_Add() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $new_file_name = time() . preg_replace('/[^a-zA-Z0-9_.]/', '_', $_FILES['companyLogo']['name']);
            $_FILES['companyLogo']['name'] = $new_file_name;
            $_FILES['companyLogo']['type'] = $_FILES['companyLogo']['type'];
            $_FILES['companyLogo']['tmp_name'] = $_FILES['companyLogo']['tmp_name'];
            $_FILES['companyLogo']['error'] = $_FILES['companyLogo']['error'];
            $_FILES['companyLogo']['size'] = $_FILES['companyLogo']['size'];
            $config['upload_path'] = 'uploads/user/profile/';
//                $config['upload_path'] = 'uploads/user/profile/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 1024;
            $config['remove_spaces'] = true;
            $config['overwrite'] = false;
            $config['max_width'] = '';
            $config['max_height'] = '';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('companyLogo')) {
                $this->session->set_flashdata('error', 'The uploaded file exceeds the maximum allowed size (1MB)');
            } else {
                $image_uploaded = $this->upload->data();

                $config['image_library'] = 'gd2';
                $config['quality'] = '60';
                $config['source_image'] = $this->upload->upload_path . $this->upload->file_name;
                $this->load->library('image_lib', $config);
                $this->image_lib->resize();
            }
            if ($image_uploaded != null) {
                $fileName = $image_uploaded['file_name'];
            }
            //$fileName = str_replace(' ', '_', $_FILES['companyLogo']['name']);
            if ($fileName != null) {
                $file_data = array(
                    'model_id' => $_SESSION['user_id'],
                    'model_name' => 'user',
                    'photo' => $fileName,
                    'image_type' => 'logo',
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),
                );
                $profile_photo = $this->Images_model->get_by(array('model_name' => 'user', 'model_id' => $_SESSION['user_id']));
                if ($profile_photo != "") {
                    $this->Images_model->update($profile_photo->id, $file_data);
                } else {
                    $this->Images_model->insert($file_data);
                }
            }
            header('Location: superAdmins-Account');
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function account_info_update() {
        Debugger::debug($_POST, 'POST');
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $id = $this->input->post('profile_edit');
            $user_id = $_SESSION['user_id'];
            switch ($id) {
                case 1:
                    $name = $this->input->post('accountEmail');
                    $update_data = array(
                        'first_name' => $this->input->post('accountName'),
//                        'email' => $this->input->post('accountEmail'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    if ($update_data != null) {
                        $this->User_model->update($user_id, $update_data);
                        echo true;
                    }
                    break;
                case 2:
                    $currentpassword = $this->input->post('pwCurrent');
                    $user = $this->User_model->get($user_id);
                    if (  $this->auth->verifyNewPassword($currentpassword, $user->new_password) || $this->auth->verifyPassword($currentpassword, $user->password) ) {
                        if ( ($this->input->post('pwCurrent') != $this->input->post('password')) && ($this->input->post('password') == $this->input->post('passwordNew')) ) {
                            Debugger::debug('updating');
                            $update_data = array(
                                'reset_password_token' => "",
                                'password' => '',
                                'new_password' => $this->auth->hashPassword($this->input->post('password')),
                                'password_last_updated_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                            );
                            if ($update_data != null) {
                                $this->User_model->update($user_id, $update_data);
                                echo true;
                            }
                        } else {
                            $this->session->set_flashdata('error', 'New password cannot be the same as current password.');
                        }
                    } else {
                        $this->session->set_flashdata('error', 'Invalid current password.');
                    }
                    break;
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function deactivate_vendors() {
        /*
         *  1. When a vendor is deactivated.  He should not be Allowed to logged in.
         */
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $vendors_id = explode(",", $this->input->post('user_id'));
            if ($vendors_id != null) {
                $update_data = array(
                    'active' => '0',
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                $this->Vendor_model->update_many($vendors_id, $update_data);
                $product_price_update = array(
                    'active' => '0',
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                $this->Product_pricing_model->update_by(array('vendor_id' => $vendors_id), $product_price_update);
                $this->session->set_flashdata('error', 'The selected Vendor(s) deactivated');
                header('Location: vendorsIn-list');
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function activate_vendors() {
        /*
         *  1. When a vendor is deactivated.  He should not be Allowed to logged in.
         */
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $vendors_id = explode(",", $this->input->post('user_id'));
            if ($vendors_id != null) {
                $update_data = array(
                    'active' => '1',
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                $this->Vendor_model->update_many($vendors_id, $update_data);
                $product_price_update = array(
                    'active' => '1',
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                $this->Product_pricing_model->update_by(array('vendor_id' => $vendors_id), $product_price_update);
                $this->session->set_flashdata('success', 'The selected  Vendor(s) are activated');
                header('Location: vendorsIn-list');
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     * SuperAdmin Dashboard
     *      @Organizations
     *        i. List of organizations in the System will be shown from here with required information.
     */

    public function organizations_list() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $data['organization_lists'] = $this->Organization_model->get_all();
            $data['limit'] = 30;
            $valuein = $this->input->get("p");
            $offset = 0;
            if ($valuein != null) {
                $offset = ($valuein - 1) * $data['limit'];
            }
            if ($data['organization_lists'] != null) {
                $search = $this->input->get('search');
                if ($search != null) {
                    $query = "SELECT o.id,o.organization_name,o.organization_type,o.created_at,r.role_name FROM organizations o INNER JOIN organization_groups p ON p.organization_id=o.id INNER JOIN users q ON q.id=p.user_id INNER JOIN roles r ON r.id=q.role_id where q.role_id not in(1,2,11) and (q.first_name LIKE '%$search%' or o.organization_name LIKE '%$search%') group by o.id limit $offset," . $data['limit'] . " ";
                    $data['organizations_list'] = $this->db->query($query)->result();
                    $data['total_count'] = 0;
                    $query1 = "SELECT count(*) as search_count FROM (SELECT o.id,o.organization_name,o.organization_type,o.created_at,r.role_name FROM organizations o INNER JOIN organization_groups p ON p.organization_id=o.id INNER JOIN users q ON q.id=p.user_id INNER JOIN roles r ON r.id=q.role_id where q.role_id not in(1,2,11) and (q.first_name LIKE '%$search%' or o.organization_name LIKE '%$search%') group by o.id) count";
                    $count_query = $this->db->query($query1)->result();
                    if ($count_query != null) {
                        $data['total_count'] = $count_query[0]->search_count;
                    }
                } else {
                    $query = "SELECT o.id,o.organization_name,o.organization_type,o.created_at,r.role_name FROM organizations o INNER JOIN organization_groups p ON p.organization_id=o.id INNER JOIN users q ON q.id=p.user_id INNER JOIN roles r ON r.id=q.role_id where q.role_id not in(1,2,11) group by o.id limit $offset," . $data['limit'] . "";
                    $data['organizations_list'] = $this->db->query($query)->result();
                    $data['total_count'] = 0;
                    $query1 = "SELECT count(*) as search_count FROM (SELECT o.id,o.organization_name,o.organization_type,o.created_at,r.role_name FROM organizations o INNER JOIN organization_groups p ON p.organization_id=o.id INNER JOIN users q ON q.id=p.user_id INNER JOIN roles r ON r.id=q.role_id where q.role_id not in(1,2,11) and (q.first_name LIKE '%$search%' or o.organization_name LIKE '%$search%') group by o.id) count";
                    $count_query = $this->db->query($query1)->result();
                    if ($count_query != null) {
                        $data['total_count'] = $count_query[0]->search_count;
                    }
                }
            }
            $this->load->library('pagination');
            $config['base_url'] = base_url() . '/organizations-list';
            $config['total_rows'] = $data['total_count'];
            $config['per_page'] = $data['limit'];
            $this->pagination->initialize($config);
            $config['enable_query_strings'] = TRUE;
            $config['page_query_string'] = TRUE;
            $config['use_page_numbers'] = TRUE;

            $data['user_approval'] = user_counts();
            $data['flagged_count'] = flagged_count();
            $data['answer_count'] = flaggedAnswer_count();
            $data['My_vendor_users'] = "";
            $data['vendor_shipping'] = "";
            $data['promoCodes_active'] = "";
            $this->load->view('/templates/_inc/header-admin.php');
            $this->load->view('/templates/admin/organizations/index.php', $data);
            $this->load->view('/templates/_inc/footer-admin.php');
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function buyingClubs()
    {
        if(!empty($_SESSION['role_id']) && $_SESSION['role_id'] == 1){
            $buyingClubs = $this->BuyingClub_model->loadAll($_SESSION['user_id']);

            // set up nav counts
            $data = [
                'user_approval' => user_counts(),
                'flagged_count' => flagged_count(),
                'answer_count' => flaggedAnswer_count(),
                'buyingClubs' => $buyingClubs
            ];

            $data['userType'] = 'admin';
            $this->load->view('/templates/buying-clubs/list.php', $data);
        } else {
            $this->session->set_flashdata('error', 'You do not have access to that page');
            header('Location: login');
        }

    }

    //   To update the User Name and Role.
    public function SPadmin_edit() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $user_id = $this->input->post('user_id');
            if ($user_id != null) {
                $update_data = array(
                    'first_name' => $this->input->post('accountName'),
                    'role_id' => $this->input->post('role_id'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                if ($update_data != null) {
                    $this->User_model->update($user_id, $update_data);
                    $this->session->set_flashdata('success', 'The user details are updated');
                    header('Location: superAdmins-Users');
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *  SuperAdmin Dashboard
     *      @users
     *          Inviting Admin.
     *              1. Only SuperAdmin (role_id =1) should invite a SuperAdmin/Admin
     */

    public function SPuser_invitation() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $accountEmail = $this->input->post('accountEmail');
            $accountName = $this->input->post('accountName');
            $role_id = $this->input->post('role_id');
            $email_check = $this->User_model->get_many_by(array('email' => $accountEmail));
            if ($email_check != null) {
                $this->session->set_flashdata('error', 'Email already exists. Please try again.');
                header("location: superAdmins-Users");
                exit();
            } else {
                $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
                $token = array(); //remember to declare $pass as an array
                $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
                for ($i = 0; $i < 30; $i++) {
                    $n = rand(0, $alphaLength);
                    if ($i < 8) {
                        $pass[] = $alphabet[$n];
                    }
                    $token[] = $alphabet[$n];
                }
                $register_confirm_token = implode($token);
                $password = implode($pass);
                $insert_data = array(
                    'email' => $accountEmail,
                    'first_name' => $accountName,
                    'role_id' => $role_id,
                    'new_password' => $this->auth->hashPassword($password),
                    'confirmation_token' => $register_confirm_token,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                if ($insert_data != null) {
                    $this->User_model->insert($insert_data);
                    $data['roles'] = $this->Role_model->get($role_id);
                    $role_name = $data['roles']->role_name;
                    $subject = 'Account Confirmation Email';
                    $message = "Hi,<br />"
                            . "<br>Welcome to the Matixdental. Please click below to confirm your account email address and login with auto generated password. <br>"
                            . "<table>";
                    if ($role_id != 10) {
                        $message .= "<tr><td><b>Role Name :</b></td><td>$role_name</td></tr>";
                    }
                    $message .= "<tr><td><b>Email :</b></td><td>$accountEmail</td></tr>"
                            . "<tr><td><b>Password :</b></td><td>$password</td></tr></table>"
                            . "<b>Note :</b>Please change the password once logged-in"
                            . "<a href='" . base_url() . "superadmin-registrater-confirmation?register_confirm_token=" . $register_confirm_token . "' style='padding: 20px 40px 20px 40px;width:auto;display: block;text-decoration: none;border:0;text-align: center;font-weight: bold;font-size: 16px;font-family: Arial, sans-serif;color: #ffffff;background: #2893FF;border: 1px solid #317ED0;-moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius: 4px;line-height:normal;' class='button_link'>Confirm Registration</a>";
                    $email_data = array(
                        'subject' => $subject,
                        'message' => $message
                    );
                    $body = $this->load->view('/templates/email/index.php', $email_data, TRUE);
                    send_matix_email($body, $subject, $accountEmail);
                }
                $this->session->set_flashdata('success', 'Invitation is sent to user.');
                header('Location: superAdmins-Users');
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function SPregistrater_confirmation() {
        $confirmation_token = $this->input->get('register_confirm_token');
        $user_detail = $this->User_model->get_by(array('confirmation_token' => $confirmation_token));
        if ($user_detail != null) {
            $confirm_token_tb = $user_detail->confirmation_token;
            $user_id = $user_detail->id;
        }
        if ($confirmation_token == $confirm_token_tb) {
            $update_data = array(
                'confirmation_token' => "",
                'status' => '1',
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $this->User_model->update($user_id, $update_data);
            $this->session->set_flashdata('success', 'Account approved. Please sign in.');
            header("Location: login");
        } else {
            $this->session->set_flashdata('success', 'Your account is not  approved. Please try again');
            header("Location: user-register");
        }
    }

    public function adminPasswordReset_notification() {
        /*
         *      NOTE: THE function is called Twice from the SuperAdmin Dashboard.
         *              1. To Reset Password for Multiple Users.
         */
        $user_id = $this->input->post('user_id');
        $multiple_user = explode(",", $user_id);
        $user_details = $this->User_model->get_many_by(array('id' => $multiple_user));
        if ($user_details != null) {
            for ($i = 0; $i < count($user_details); $i++) {
                $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
                $token = array(); //remember to declare $pass as an array
                $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
                for ($j = 0; $j < 30; $j++) {
                    $n = rand(0, $alphaLength);
                    $token[] = $alphabet[$n];
                }
                $reset_password_token = implode($token);
                $user_id = $user_details[$i]->id;
                $accountEmail = $user_details[$i]->email;
                $update_data = array(
                    'password' => '',
                    'status' => '0',
                    'confirmation_token' => '',
                    'reset_password_token' => $reset_password_token,
                    'reset_password_sent_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                if ($update_data != null) {
                    $user_id = $this->User_model->update($user_id, $update_data);
                    $subject = 'Reset Password';
                    $message = "Hi,<br />"
                            . " Please click below to set a new password for your account."
                            . "<a href='" . config_item('site_url') . "superadmin-reset-password-token?reset_password_token=" . $reset_password_token . "' style='padding: 20px 40px 20px 40px;width:auto;display: block;text-decoration: none;border:0;text-align: center;font-weight: bold;font-size: 16px;font-family: Arial, sans-serif;color: #ffffff;background: #2893FF;border: 1px solid #317ED0;-moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius: 4px;line-height:normal;' class='button_link'>Reset Password</a>"
                            . "If you didn’t mean to reset your password, then you can just ignore this email; your password will not change. <br /><b>Note :</b> Please change the password within 24 hours.";
                    $email_data = array(
                        'subject' => $subject,
                        'message' => $message
                    );
                    $body = $this->load->view('/templates/email/index.php', $email_data, TRUE);
                    $mail_status = send_matix_email($body, $subject, $accountEmail);
                    $this->session->set_flashdata('success', 'Password notification sent successfully.');
                }
                /*
                 * IF RESET PASSWORD
                 *              1. Vendor-user it will take you to => step:1
                 *              2. Admin-user it will take you to => step:2
                 */
                $vendor_id = $this->input->post('vendor_id');
                if ($vendor_id != null) {
                    header("Location: vendors-sales-report?vendor_id=$vendor_id"); // step:1
                } else {
                    header("Location: superAdmins-Users");   // step:2
                }
            }
        }
    }

    public function adminPasswordReset_change() {
        $reset_password_token = $this->input->get('reset_password_token');
        $user_detail = $this->User_model->get_by(array('reset_password_token' => $reset_password_token));
        if ($user_detail != null) {
            $date = $user_detail->reset_password_sent_at;
            $currentTime = date("Y-m-d H:m:s");
            $expireTime = date("Y-m-d H:i:s", strtotime('24 hours', strtotime($date)));
            if ($currentTime <= $expireTime) {
                $update_data = array(
                    'status' => '1',
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                if ($update_data != null) {
                    $user_id = $this->User_model->update($user_detail->id, $update_data);
                }
                $data['user_token'] = $user_detail;
                $this->session->set_flashdata('success', 'Please Update your Password');
                $this->load->view('/templates/password/index.php', $data);
            } else {
                $this->session->set_flashdata('error', 'The Password is expired Please Processed throw Forgot Password Email');
                header("Location: login");
            }
        }
    }

    public function deleteMultiple_admin() {
        /*
         *  Check if the existing user is also included . If included exclude  them and send show a error message.
         */
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $delete_id = explode(",", $this->input->post('user_id'));
            $this->User_model->delete_many($delete_id);
            header('Location: superAdmins-Users');
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function product_report() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $product_id = $this->input->get('product_id');
            if ($product_id != null) {
                $data['product_details'] = $this->Products_model->get($product_id);
                if ($data['product_details'] == null) {
                    $this->session->set_flashdata('error', 'Invalid Entry');
                    header('Location: vendorsIn-list');
                } else {
                    $data['product_details']->category_id = str_replace('"', '', $data['product_details']->category_id);
                    $data['product_images'] = $this->Images_model->get_many_by(array('model_name' => 'products', 'model_id' => $product_id));
                    $data['product_customes'] = $this->Product_custom_field_model->get_many_by(array('product_id' => $product_id));
                    $data['product_questions'] = $this->Product_question_model->get_many_by(array('product_id' => $product_id));
                    for ($i = 0; $i < count($data['product_questions']); $i++) {
                        $answers = $this->Product_answer_model->order_by('id', 'desc')->get_many_by(array('question_id' => $data['product_questions'][$i]->id));
                        $data['product_questions'][$i]->answers = $answers;
                    }
                    $data['product_pricing'] = $this->Product_pricing_model->get_by(array('product_id' => $product_id));
                    // STAR RATING ********
                    $data['one_star'] = count($this->Review_model->get_many_by(array('model_name' => 'products', 'model_id' => $product_id, 'rating' => '1')));
                    $data['two_star'] = count($this->Review_model->get_many_by(array('model_name' => 'products', 'model_id' => $product_id, 'rating' => '2')));
                    $data['three_star'] = count($this->Review_model->get_many_by(array('model_name' => 'products', 'model_id' => $product_id, 'rating' => '3')));
                    $data['four_star'] = count($this->Review_model->get_many_by(array('model_name' => 'products', 'model_id' => $product_id, 'rating' => '4')));
                    $data['five_star'] = count($this->Review_model->get_many_by(array('model_name' => 'products', 'model_id' => $product_id, 'rating' => '5')));
                    $selection = $this->input->post('selection');
                    if ($selection == null) {
                        $selection = 'rating';
                    }
                    $data['selection'] = $selection;
                    $data['product_review'] = $this->Review_model->limit(2, 0)->order_by($selection, 'desc')->get_many_by(array('model_id' => $product_id, 'model_name' => 'products'));
                    if ($data['product_review'] != null) {
                        for ($i = 0; $i < count($data['product_review']); $i++) {
                            $users = $this->User_model->select('id,first_name')->get_by(array('id' => $data['product_review'][$i]->user_id));
                            $data['product_review'][$i]->users = $users;
                        }
                    }
                    $data["vendor_list"] = $this->Vendor_model->get_many_by(array('active' => '1'));
                    if ($data["vendor_list"] != null) {
                        for ($i = 0; $i < count($data["vendor_list"]); $i++) {
                            $data["vendor_list"][$i]->product_active = "";
                            $data["vendor_list"][$i]->product_active = $this->Product_pricing_model->get_by(array('vendor_id' => $data["vendor_list"][$i]->id, 'product_id' => $product_id));
                        }
                    }
                }
            } else {
                $this->session->set_flashdata('error', 'Invalid Entry');
                header('Location: vendorsIn-list');
            }
            $data['My_vendor_users'] = "";
            $data['vendor_shipping'] = "";
            $data['promoCodes_active'] = "";
            $this->load->view('/templates/_inc/header-admin.php');
            $this->load->view('/templates/admin/catalog/product/index.php', $data);
            $this->load->view('/templates/_inc/footer-admin.php');
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function inactivate_productPricing() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $productPricing_id = $this->input->post('productPricing_id');
            if ($productPricing_id != null) {
                $update_pricing = array(
                    'active' => '0',
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                if ($update_pricing != null) {
                    $this->session->set_flashdata('error', 'The product is inactive for this vendor');
                    $result = $this->Product_pricing_model->update($productPricing_id, $update_pricing);
                    if ($result == 1) {
                        echo $result;
                    }
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function activate_productPricing() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $productPricing_id = $this->input->post('productPricing_id');
            $product_pricingDetails = $this->Product_pricing_model->get($productPricing_id);
            if ($product_pricingDetails != null) {
                $update_pricing = array(
                    'active' => '1',
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                if ($update_pricing != null) {
                    $this->Product_pricing_model->update($productPricing_id, $update_pricing);
                    $rows = $this->db->affected_rows();
                    if ($rows > 0){
                        $this->session->set_flashdata('success', 'The product is active for this vendor');
                    }
                    echo $rows;
                }
            } else {
                $product_id = $this->input->post('product_id');
                $vendor_id = $this->input->post('vendor_id');
                $product_pricingCheck = $this->Product_pricing_model->get_by(array('product_id' => $product_id, 'vendor_id' => $vendor_id));
                if ($product_pricingCheck == null) {
                    $productCopy = $this->Product_pricing_model->get_by(array('product_id' => $product_id));
                    if ($productCopy != null) {
                        $insert_data = array(
                            'product_id' => $product_id,
                            'vendor_id' => $vendor_id,
                            'price' => $productCopy->price,
                            'retail_price' => $productCopy->retail_price,
                            'active' => '1',
                            'quantity' => $productCopy->quantity,
                            'minimum_threshold' => $productCopy->minimum_threshold,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        );
                        if ($insert_data != null) {
                            $this->Product_pricing_model->insert($insert_data);
                            $rows = $this->db->affected_rows();
                            if ($rows > 0){
                                $this->session->set_flashdata('success', 'The product is included for the Vendor');
                            }
                            echo $rows;
                        }
                    }
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function search_vendorCatalog() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $search = $this->input->post('search');
            $product_id = $this->input->post('product_id');
            if ($search != null) {
                $data["vendor_list"] = $this->Vendor_model->get_many_by(array('active' => '1', 'name like "%' . $search . '%"'));
                if ($data["vendor_list"] != null) {
                    for ($i = 0; $i < count($data["vendor_list"]); $i++) {
                        $data["vendor_list"][$i]->product_active = "";
                        $data["vendor_list"][$i]->product_active = $this->Product_pricing_model->get_by(array('vendor_id' => $data["vendor_list"][$i]->id, 'product_id' => $product_id));
                    }
                    echo json_encode($data['vendor_list']);
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function TwoMore_ReviewProducts() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $product_id = $this->input->post('product_id');
            $offset = $this->input->post('offset');
            $selection = $this->input->post('selection');
            if ($selection == null) {
                $selection = "rating";
            }
            if ($product_id != null) {
                $data['product_review'] = $this->Review_model->limit(2, $offset)->order_by($selection, 'desc')->get_many_by(array('model_id' => $product_id, 'model_name' => 'products'));
                if ($data['product_review'] != null) {
                    for ($i = 0; $i < count($data['product_review']); $i++) {
                        $users = $this->User_model->select('id,first_name')->get_by(array('id' => $data['product_review'][$i]->user_id));
                        $data['product_review'][$i]->users = $users;
                    }
                    $this->load->view('/templates/admin/catalog/product/review_extend.php', $data);
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function TwoMore_Answers() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $question_id = $this->input->post('product_id');
            $offset = $this->input->post('offset');
            if ($question_id != null) {
                $data['product_questions'] = $this->Product_question_model->get_by(array('id' => $question_id));
                if ($data['product_questions'] != null) {
                    $data['answers'] = $this->Product_answer_model->limit(2, $offset)->order_by('id', 'desc')->get_many_by(array('question_id' => $data['product_questions']->id));
                }
            }
            $this->load->view('/templates/admin/catalog/product/answer_extend.php', $data);
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function product_SPupdate() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $product_id = $this->input->post('productId');

            if(!empty($this->input->post('category_code'))){
                $cats = explode(',', $this->input->post('category_code'));
                $catString = '"' . implode($cats, '","') . '"';
            } else {
                $catString = '';
            }

            if ($product_id != null) {
                $update_data = array(
                    'name' => $this->input->post('productName'),
                    'description' => $this->input->post('description'),
                    'manufacturer' => $this->input->post('productMfr'),
                    'contents' => $this->input->post('productContents'),
                    'license_required' => $this->input->post('license_required'),
                    'quantity_per_box' => $this->input->post('productQtyPerUnit'),
                    'weight' => $this->input->post('productWeight'),
                    'weight_type' => $this->input->post('weight_type'),
                    'set_rate' => $this->input->post('set_rate'),
                    'viscosity' => $this->input->post('viscosity'),
                    'band_thickness' => $this->input->post('band_thickness'),
                    'handle_size' => $this->input->post('handle_size'),
                    'handle_finish' => $this->input->post('handle_finish'),
                    'tax_per_state' => $this->input->post('tax_per_state'),
                    'tip_finish' => $this->input->post('tip_finish'),
                    'tip_diameter' => $this->input->post('tip_diameter'),
                    'tip_material' => $this->input->post('tip_material'),
                    'head_diameter' => $this->input->post('head_diameter'),
                    'head_length' => $this->input->post('head_length'),
                    'returnable' =>$this->input->post('returnable'),
                    'diameter' => $this->input->post('diameter'),
                    'category_id' => $catString,
                    'arch' => $this->input->post('arch'),
                    'shaft_dimensions' => $this->input->post('shaft_dimensions'),
                    'anatomic_use' => $this->input->post('anatomic_use'),
                    'blade_description' => $this->input->post('blade_description'),
                    'instrument_description' => $this->input->post('instrument_description'),
                    'palm_thickness' => $this->input->post('palm_thickness'),
                    'finger_thickness' => $this->input->post('finger_thickness'),
                    'texture' => $this->input->post('texture'),
                    'delivery_system' => $this->input->post('delivery_system'),
                    'volume' => $this->input->post('volume'),
                    'dimensions' => $this->input->post('dimensions'),
                    'stone_type' => $this->input->post('stone_type'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                if ($update_data != null) {
                    $update_ProdPrice = array(
                        'active' => $this->input->post('active'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    if ($update_ProdPrice != null) {
                        $this->Product_pricing_model->update_by(array('product_id' => $product_id), $update_ProdPrice);
                    }
                    $productKey = $this->input->post('productKey1');
                    $productValue = $this->input->post('productValue1');
                    if ($productKey != null) {
                        $this->Product_custom_field_model->delete_by(array('product_id' => $product_id));
                        for ($k = 0; $k < count($productKey); $k++) {
                            if ($productKey != null) {
                                $insert_customFields = array(
                                    'product_id' => $product_id,
                                    'field' => $productKey[$k],
                                    'value' => $productValue[$k],
                                    'updated_at' => date('Y-m-d H:i:s'),
                                    'created_at' => date('Y-m-d H:i:s'),
                                );
                                if ($insert_customFields != null) {
                                    $this->Product_custom_field_model->insert($insert_customFields);
                                }
                            }
                        }
                    }
                    // UPDATING HAS MAIN IMAGE.
                    $productImage = $this->input->post('productImage');
                    if ($productImage != null) {
                        $Main_change = $this->Images_model->get_by(array('model_name' => 'products', 'image_type' => 'mainimg', 'model_id' => $product_id));
                        if ($Main_change != null) {
                            $update_mainImage = array(
                                'image_type' => 'subimg',
                                'updated_at' => date('Y-m-d'),
                            );
                            if ($update_mainImage != null) {
                                $this->Images_model->update($Main_change->id, $update_mainImage);
                            }
                        }
                        $mainImage = array(
                            'image_type' => 'mainimg',
                            'updated_at' => date('Y-m-d'),
                        );
                        if ($mainImage != null) {
                            $this->Images_model->update($productImage, $mainImage);
                        }
                    }

                    $files = $_FILES;
                    $count = count($_FILES['productImages']['name']);
                    $images = $_FILES['productImages']['name'];
                    $imageid = $this->input->post("image_id");

                    for ($i = 0; $i < $count; $i++) {
                        if ($images[$i] != "") {
                            $new_file_name = time() . preg_replace('/[^a-zA-Z0-9_.]/', '_', $files['productImages']['name'][$i]);
                            $_FILES['productImages']['name'] = $new_file_name;
                            $_FILES['productImages']['type'] = $files['productImages']['type'][$i];
                            $_FILES['productImages']['tmp_name'] = $files['productImages']['tmp_name'][$i];
                            $_FILES['productImages']['error'] = $files['productImages']['error'][$i];
                            $_FILES['productImages']['size'] = $files['productImages']['size'][$i];
                            $config['upload_path'] = 'uploads/products/images/';
                            $config['allowed_types'] = 'gif|jpg|png|jpeg';
                            $config['max_size'] = 1024;
                            $config['remove_spaces'] = true;
                            $config['overwrite'] = false;
                            $config['max_width'] = '';
                            $config['max_height'] = '';
                            $this->load->library('upload', $config);
                            $this->upload->initialize($config);
                            if (!$this->upload->do_upload('productImages')) {
                                $this->session->set_flashdata('error', 'The uploaded file exceeds the maximum allowed size (1MB)');
                            } else {
                                $fileName = "";
                                $image_uploaded = $this->upload->data();
                                if ($image_uploaded != null) {
                                    $fileName = $image_uploaded['file_name'];
                                }

                                $config['image_library'] = 'gd2';
                                $config['quality'] = '60';
                                $config['source_image'] = $this->upload->upload_path . $this->upload->file_name;
                                $this->load->library('image_lib', $config);
                                $this->image_lib->resize();

                                $images[] = $fileName;


                                if ($i == 0) {
                                    $type = 'mainimg';
                                } else {
                                    $type = 'subimg';
                                }
                            }
                            if ($fileName != '') {
                                $file_data = array(
                                    'model_id' => $product_id,
                                    'model_name' => 'products',
                                    'photo' => $fileName,
                                    'image_type' => $type,
                                    'created_at' => date('Y-m-d H:i:s')
                                );

                                if ($imageid[$i] != "") {
                                    $this->Images_model->update($imageid[$i], $file_data);
                                } else {
                                    $this->Images_model->insert($file_data);
                                }
                            }
                        }
                    }
                    $this->Products_model->update($product_id, $update_data);

                    $this->elasticsearch->add("products", $product_id, $update_data);
                    header('Location: SPadmin-products?product_id=' . $product_id);
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function bulk_image_uploader() {
        set_time_limit(0);
        ini_set("memory_limit", "12288M");
        ini_set("upload_max_filesize", "160M");
        ini_set("post_max_size", "160M");
        $this->load->library('image_lib', array());
        $uploadPath = 'uploads/bulk-images/';
        $archiveName = time() . '.zip';
        if (!move_uploaded_file($_FILES['imageArchive']['tmp_name'], $uploadPath . $archiveName)) {
            $this->session->set_flashdata('error', $_FILES['imageArchive']['error'] ? $_FILES['imageArchive']['error'] : 'Problem uploading file.');
            header('Location: product-catalog');
            exit;
        }
        exec('cd ' . $uploadPath . '; unzip ' . $archiveName, $output, $result);
        if ($result != 0) {
            $this->session->set_flashdata('error', 'Error extracting image archive.');
            header('Location: product-catalog');
            exit;
        }
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($uploadPath),
            RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($iterator as $path) {
            if ($path->isDir()) {
                continue;
            }
            if (!preg_match('/^([A-Za-z0-9\-\/\s]+)_?([0-9]+)?.(jpe?g|gif|png)$/', $path->getFilename(), $matches)) {
                continue;
            }

            $this->image_lib->initialize(array(
                'max_size' => 1024,
                'image_library' => 'gd2',
                'quality' => 60,
                'source_image' => $path->__toString()
            ));
            $this->image_lib->resize();

            $new_filename = time() . '_' . $path->getFilename();
            rename($path->__toString(), 'uploads/products/images/' . $new_filename);
            $product = $this->Products_model->get_by(['mpn'=> $matches[1]]);

            if ($product){
                $this->Images_model->insert(array(
                    'model_id' => $product->id,
                    'model_name' => 'products',
                    'photo' => $new_filename,
                    'image_type' => ($matches[2] == 1 || !$matches[2])? 'mainimg' : 'subimg',
                    'created_at' => date('Y-m-d H:i:s')
                ));
            }
        }

        $this->load->helper('my_directory');
        rrmdir($uploadPath);
        mkdir_if_not_exist($uploadPath);

        $this->session->set_flashdata('success', 'Images added successfully.');
        header('Location: product-catalog');
    }

    public function export_products() {
        set_time_limit(0);
        ini_set("memory_limit", "12288M");
        $headerRow = array('id', 'matix_id', 'mpn', 'item_code', 'name', 'description', 'extended_description', 'keywords', 'manufacturer', 'product_procedures', 'shipping_restrictions', 'brand', 'category_code','arch', 'weight', 'size', 'weight_type', 'license_required', 'category_id', 'color', 'msds_location', 'created_at', 'updated_at', 'unit_of_measure_selling', 'manufacturer_item_no', 'manufacturer_ins_sheet', 'quantity_per_box', 'previous_item_no', 'sample', 'ship_weight', 'fluoride', 'flavor', 'shade', 'grit', 'set_rate', 'viscosity', 'firmness', 'handle_size', 'handle_finish', 'tip_finish', 'tip_diameter', 'tip_material', 'head_diameter', 'head_length', 'diameter', 'shaft_dimensions', 'shaft_description', 'blade_description', 'anatomic_use', 'instrument_description', 'palm_thickness', 'finger_thickness', 'texture', 'delivery_system', 'volume', 'dimensions', 'stone_type', 'stone_separation_time', 'setting_time', 'band_thickness', 'contents','returnable', 'tax_per_state', 'average_rating');
        $random_name = rand(1, 10000000000);
        $filename = $random_name . '.xlsx';
        $uploadPath = FCPATH . 'assets/uploads/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0775, true);
        }

        $file_path = 'assets/uploads/' . $filename; //set file path to download
        $writer = WriterFactory::create(Type::XLSX);
        $writer->openToFile($file_path);
        $firstSheet = $writer->getCurrentSheet();
        $writer->addRow($headerRow); //set header row
        $writer->setCurrentSheet($firstSheet);
        $limit = 100;
        $total_count = $this->Products_model->count_all();
        $pages = ceil($total_count / $limit);
        for ($k = 0; $k < $pages; $k++) {
            $offset = $k * $limit;
            $products = $this->Products_model->limit($limit, $offset)->get_all();
            //Create a project details an array
            for ($i = 0; $i < count($products); $i++) {
                $products_data = array(
                    $products[$i]->id,
                    $products[$i]->matix_id,
                    $products[$i]->mpn,
                    $products[$i]->item_code,
                    $products[$i]->name,
                    strip_tags($products[$i]->description),
                    strip_tags($products[$i]->extended_description),
                    $products[$i]->keywords,
                    $products[$i]->manufacturer,
                    $products[$i]->product_procedures,
                    $products[$i]->shipping_restrictions,
                    $products[$i]->brand,
                    $products[$i]->category_code,
                    $products[$i]->arch,
                    $products[$i]->weight,
                    $products[$i]->size,
                    $products[$i]->weight_type,
                    $products[$i]->license_required,
                    $products[$i]->category_id,
                    $products[$i]->color,
                    $products[$i]->msds_location,
                    $products[$i]->created_at,
                    $products[$i]->updated_at,
                    $products[$i]->unit_of_measure_selling,
                    $products[$i]->manufacturer_item_no,
                    $products[$i]->manufacturer_ins_sheet,
                    $products[$i]->quantity_per_box,
                    $products[$i]->previous_item_no,
                    $products[$i]->sample,
                    $products[$i]->ship_weight,
                    $products[$i]->fluoride,
                    $products[$i]->flavor,
                    $products[$i]->shade,
                    $products[$i]->grit,
                    $products[$i]->set_rate,
                    $products[$i]->viscosity,
                    $products[$i]->firmness,
                    $products[$i]->handle_size,
                    $products[$i]->handle_finish,
                    $products[$i]->tip_finish,
                    $products[$i]->tip_diameter,
                    $products[$i]->tip_material,
                    $products[$i]->head_diameter,
                    $products[$i]->head_length,
                    $products[$i]->diameter,
                    $products[$i]->shaft_dimensions,
                    $products[$i]->shaft_description,
                    $products[$i]->blade_description,
                    $products[$i]->anatomic_use,
                    $products[$i]->instrument_description,
                    $products[$i]->palm_thickness,
                    $products[$i]->finger_thickness,
                    $products[$i]->delivery_system,
                    $products[$i]->volume,
                    $products[$i]->dimensions,
                    $products[$i]->stone_type,
                    $products[$i]->stone_separation_time,
                    $products[$i]->setting_time,
                    $products[$i]->band_thickness,
                    $products[$i]->contents,
                    $products[$i]->returnable,
                    $products[$i]->tax_per_state,
                    $products[$i]->average_rating
                );
                $writer->addRow($products_data); //write product details
            }
        }

        $writer->close();
        $name = 'matix.xlsx';
        header('Content-Description: File Transfer');
        header('Content-Type: application/force-download');
        header("Content-Disposition: attachment; filename=\"" . basename($filename) . "\";");
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));
        ob_clean();
        flush();
        readfile($file_path);
        exit;
    }

    public function status() {
        if ($this->elasticsearch->status() == null) {
            echo "ERROR";
        }
    }

    public function import() {
        set_time_limit(0);
        ini_set("memory_limit", "12288M");
        $elasticsearch_enabled = false;
        if ($elasticsearch_enabled && $this->elasticsearch->status() == null) {
            $this->session->set_flashdata('error', 'Error uploading the products. Please contact your website administrator.');
            header("location: product-catalog");
        } else {
            // File upload validation
            if (empty($_FILES["productCatalogFile"]["name"])) {
                $this->session->set_flashdata('error', 'No file selected.');
                redirect("product-catalog");
                return;
            }
            // Set upload path
            $uploadPath = FCPATH . 'assets/uploads/';
            if (!is_dir($uploadPath) && !mkdir($uploadPath, 0775, true)) {
                $this->session->set_flashdata('error', 'Failed to create upload folder.');
                redirect("product-catalog");
                return;
            }

            // Upload configuration
            $config = [
                'upload_path' => $uploadPath,
                'allowed_types' => 'xlsx|xls',
                'file_name' => bin2hex(random_bytes(8)) . '.xlsx',
                'max_size' => 10240
            ];

            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('productCatalogFile')) {
                $this->session->set_flashdata('error', 'Upload failed: ' . $this->upload->display_errors());
                redirect("product-catalog");
                return;
            }

            $file_uploaded = $this->upload->data();
            $file_path = $file_uploaded['full_path'];
            if ($file_uploaded != null) {
                // $file_path = $file_uploaded['full_path']; //local server file read
                $reader = ReaderFactory::create(Type::XLSX); //set Type file xlsx
                $reader->open($file_path); //open the file
                $empty_rows = [];
                $new_product_array = [];
                $existing_product_array = [];
                $price_array = [];
                $update_price = [];
                $newprice_array = [];
                foreach ($reader->getSheetIterator() as $sheet) {
                    //Rows iterator
                    $i = 0;
                    foreach ($sheet->getRowIterator() as $row) {
                        //echo "$i -  $row[0] <br />";
                        //$i += 1;
                        if ($i > 0) {
                            $mpn = $row[3];
                            $manufacturer = $row[6];
                            if ($mpn != null) {
                                // Debugger::debug($row);
                                $vendors_product_id = $row[4];
                                $matix_id = array($mpn, $vendors_product_id);
                                $join_matix = implode("-", $matix_id);

                                $existing_product = $this->Products_model->select('id')->get_by(['mpn' => $mpn]);
                                // Debugger::debug($existing_product, 'existing product');
                                $category_id = $row[1];
                                // Debugger::debug($row[1]);
                                $c_id = explode(",", str_replace('"', '', $category_id));
                                $categories_list = [];
                                for ($k = 0; $k < count($c_id); $k++) {
                                    if (trim($c_id[$k]) != "") {
                                        $query = 'SELECT t1.id as lev1_id, t2.id as lev2_id, t3.id as lev3_id, t4.id as lev4_id, t5.id as lev5_id
                                                        FROM categories AS t1
                                                        LEFT JOIN categories AS t2 ON t2.id = t1.parent_id
                                                        LEFT JOIN categories AS t3 ON t3.id = t2.parent_id
                                                        LEFT JOIN categories AS t4 ON t4.id = t3.parent_id
                                                        LEFT JOIN categories AS t5 ON t5.id = t4.parent_id
                                                        WHERE t1.id = ' . trim($c_id[$k]);

                                        $output = $this->db->query($query)->result();

                                        if ($output != null) {
                                            if ($output[0]->lev1_id != null) {
                                                $categories_list[] = $output[0]->lev1_id;
                                            }
                                            if ($output[0]->lev2_id != null) {
                                                $categories_list[] = $output[0]->lev2_id;
                                            }
                                            if ($output[0]->lev3_id != null) {
                                                $categories_list[] = $output[0]->lev3_id;
                                            }
                                            if ($output[0]->lev4_id != null) {
                                                $categories_list[] = $output[0]->lev4_id;
                                            }
                                            if ($output[0]->lev5_id != null) {
                                                $categories_list[] = $output[0]->lev5_id;
                                            }
                                        }
                                    }
                                }

                                $categories_list = array_unique($categories_list);
                                if(!empty($categories_list)){
                                    $categories = '"' . implode('","', $categories_list) . '"';
                                }

                                $product_data = [
                                    'matix_id' => $row[0],
                                    'category_id' => $categories,
                                    'mpn' => $mpn,
                                    'manufacturer' => $row[6],
                                    'name' => $row[7],
                                    'brand' => $row[8],
                                    'description' => $row[11],
                                    'color' => $row[12],
                                    'quantity_per_box' => $row[13],
                                    'size' => $row[14],
                                    'license_required' => ucfirst(strtolower($row[20])),
                                    'msds_location' => $row[22],
                                    'manufacturer_ins_sheet' => $row[23],
                                    'previous_item_no' => $row[24],
                                    'product_procedures' => $row[25],
                                    'tax_per_state' => $row[26],
                                    'shipping_restrictions' => $row[27],
                                    'sample' => $row[33],
                                    'ship_weight' => $row[34],
                                    'fluoride' => $row[35],
                                    'flavor' => $row[36],
                                    'shade' => $row[37],
                                    'grit' => $row[38],
                                    'set_rate' => $row[39],
                                    'viscosity' => $row[40],
                                    'firmness' => $row[41],
                                    'handle_size' => $row[42],
                                    'handle_finish' => $row[43],
                                    'tip_finish' => $row[44],
                                    'tip_diameter' => $row[45],
                                    'tip_material' => $row[46],
                                    'head_diameter' => $row[47],
                                    'head_length' => $row[48],
                                    'diameter' => $row[49],
                                    'shaft_dimensions' => $row[50],
                                    'category_code' => $row[51],
                                    'arch' => $row[52],
                                    'shaft_description' => $row[53],
                                    'blade_description' => $row[54],
                                    'anatomic_use' => $row[55],
                                    'instrument_description' => $row[56],
                                    'palm_thickness' => $row[57],
                                    'finger_thickness' => $row[58],
                                    'texture' => $row[59],
                                    'delivery_system' => $row[60],
                                    'volume' => $row[61],
                                    'dimensions' => $row[62],
                                    'stone_type' => $row[63],
                                    'stone_separation_time' => $row[64],
                                    'setting_time' => $row[65],
                                    'band_thickness' => $row[66],
                                    'contents' => $row[67],
                                    'average_rating' => "",
                                    'returnable' => $row[21],
                                ];
                                if ($existing_product == null) {
                                    $product_data['created_at'] = date('Y-m-d H:i:s');

                                    $new_product_array[] = $product_data;

                                    $vendor_data = array(
                                        'product_id' => '',
                                        'vendor_product_id' => $row[4],
                                        'matix_id' => $join_matix,
                                        'vendor_id' => $vendor_id,
                                        'price' => $row[10],
                                        'active' => 1,
                                        'retail_price' => $row[5],
                                        'created_at' => date('Y-m-d H:i:s'),
                                        'updated_at' => date('Y-m-d H:i:s'),
                                    );

                                    Debugger::debug($vendor_data, 'vendor_data)');

                                    $price_array[] = $vendor_data;
                                    if (count($new_product_array) == 100) {
                                        $this->db->insert_batch('products', $new_product_array);
                                        $total_affected_rows = $this->db->affected_rows();
                                        $first_insert_id = $this->db->insert_id();
                                        $last_id = ($first_insert_id + $total_affected_rows - 1);
                                        if ($first_insert_id > 0) {
                                            $current_loop_counter = 0;
                                            for ($insert_id = $first_insert_id; $insert_id <= $last_id; $insert_id++) {
                                                $price_array[$current_loop_counter]['product_id'] = $insert_id;
                                                $new_product_array[$current_loop_counter]['mpn'] = (str_replace("-", "", $new_product_array[$current_loop_counter]['mpn']));
                                                if ($elasticsearch_enabled) {
                                                        $this->elasticsearch->add("products", $insert_id, $product_data);
                                                    }  
                                                    $current_loop_counter += 1;
                                            }
                                            $this->db->insert_batch('product_pricings', $price_array);
                                            $price_array = [];
                                        }
                                        $new_product_array = [];
                                    }
                                } else {
                                    // unset mpn as we found the product with that and it can't change
                                    unset($product_data['mpn']);
                                    // don't update empty fields
                                    foreach($product_data as $k => $v){
                                        if(empty($v)){
                                            unset($product_data[$k]);
                                        }
                                    }
                                    $active = (is_string($row[5])) ? 0 : 1;
                                    Debugger::debug($product_data, '$product_data');

                                    if ($elasticsearch_enabled && $row[4] != "") {
                                        $product = $this->elasticsearch->get("products", $existing_product->id);
                                        $product_info = $product['_source'];

                                        $vendor_product_id = ((str_replace("-", "", $row[4])) . ',');

                                        $product_info['vendor_product_id'] = $product_info['vendor_product_id'] . "," . $vendor_product_id;
                                        $this->elasticsearch->delete("products", $existing_product->id);
                                        $this->elasticsearch->add("products", $existing_product->id, $product_info);
                                    }

                                    $vendor_pricing = $this->Product_pricing_model->select('id')->get_by(array('product_id' => $existing_product->id, 'vendor_id' => $vendor_id));

                                    $active = (is_string($row[5])) ? 0 : 1;

                                    if ($vendor_pricing != null) {
                                        $update_vendor_data = array(
                                            'product_id' => $existing_product->id,
                                            'vendor_product_id' => $row[4],
                                            'matix_id' => $join_matix,
                                            'vendor_id' => $vendor_id,
                                            'price' => $row[5],
                                            'active' => $active,
                                            'retail_price' => $row[10],
                                            'updated_at' => date('Y-m-d H:i:s'),
                                        );

                                        if($active == 0){
                                            unset($update_vendor_data['price']);
                                        }

                                        foreach($update_vendor_data as $k => $v){
                                            if(empty($v) && $k != 'active'){
                                                Debugger::debug('unsetting ' . $k);
                                                unset($update_vendor_data[$k]);
                                            }
                                        }

                                        Debugger::debug($update_vendor_data, '$update_vendor_data post check');
                                        $this->db->update('product_pricings', $update_vendor_data, ['id' => $vendor_pricing->id]);
                                        $sql = $this->db->update_string('product_pricings', $update_vendor_data, "id = $vendor_pricing->id");
                                        Debugger::debug($sql);

                                    } else {
                                        $vendornew_data = array(
                                            'product_id' => $existing_product->id,
                                            'vendor_product_id' => $row[4],
                                            'matix_id' => $join_matix,
                                            'vendor_id' => $vendor_id,
                                            'price' => $row[10],
                                            'active' => $active,
                                            'retail_price' => $row[5],
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'updated_at' => date('Y-m-d H:i:s'),
                                        );

                                        $newprice_array[] = $vendornew_data;
                                        if (count($newprice_array) == 100) {
                                            $this->db->insert_batch('product_pricings', $newprice_array);
                                            $newprice_array = [];
                                        }
                                    }
                                    if(!empty($product_data)){
                                        $product_data['updated_at'] = date('Y-m-d H:i:s');
                                        $product_data['id'] = $existing_product->id;
                                        $this->db->update('products', $product_data, "id = $existing_product->id");

                                        $sql = $this->db->update_string('products', $product_data, "id = $existing_product->id");
                                        Debugger::debug($sql);
                                    }


                                }
                            } else {
                                $empty_rows[] = $i;
                            }
                        }
                        $i+=1;
                    }
                }
                if ($new_product_array != null && $new_product_array !== "") {
                    $this->db->insert_batch('products', $new_product_array);
                    $total_affected_rows = $this->db->affected_rows();
                    $first_insert_id = $this->db->insert_id();
                    $last_id = ($first_insert_id + $total_affected_rows - 1);
                    if ($first_insert_id > 0) {
                        $current_loop_counter = 0;
                        for ($insert_id = $first_insert_id; $insert_id <= $last_id; $insert_id++) {
                            $price_array[$current_loop_counter]['product_id'] = $insert_id;
                            $new_product_array[$current_loop_counter]['mpn'] = (str_replace("-", "", $new_product_array[$current_loop_counter]['mpn']));
                            $this->elasticsearch->add("products", $insert_id, $new_product_array[$current_loop_counter]);
                            $current_loop_counter += 1;
                        }
                        $this->db->insert_batch('product_pricings', $price_array);
                        $price_array = [];
                    }
                    $new_product_array = [];
                }

                if ($update_price != null && $update_price !== "") {
                    $this->db->update_batch('product_pricings', $update_price, 'id');
                    $update_price = [];
                }
                if ($newprice_array != null && $newprice_array !== "") {
                    $this->db->insert_batch('product_pricings', $newprice_array);
                    $newprice_array = [];
                }

                $this->session->set_flashdata('success', 'Products Uploaded successfully. ');
                if (count($empty_rows) > 0) {
                    $this->session->set_flashdata('success', 'Products Uploaded successfully, And the following rows on the excel could not be uploaded because the MPNs were blank.');
                }
            }
            $path = $file_path;
            if (file_exists($path)) {
                    $reader->close(); 

                unlink($path) or die('failed deleting: ' . $path);
            }
            header("location: product-catalog");
        }
    }

    /*
     *  SuperAdminDashboard
     *      @Vendor
     *          1. Create a New Vendor(MODAL). From the modal.
     *              i. Vendor Registration
     *              ii. Send mail to vendor.
     */

    public function SPvendor_registration() {
        $user_name = $this->input->post('accountName');
        $accountEmail = $this->input->post('accountEmail');
        $email_check = $this->User_model->get_by(array('email' => $accountEmail));
        if ($email_check == null) {
            $insert_data = array(
                'name' => $this->input->post('vendorName'),
                'address1' => $this->input->post('vendorAddress1'),
                'address2' => $this->input->post('vendorAddress2'),
                'state' => $this->input->post('state'),
                'zip' => $this->input->post('companyZip'),
                'email' => $accountEmail,
                'vendor_type' => $this->input->post('vendor_type'),
                'active' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            if ($insert_data != null) {
                $vendor_id = $this->Vendor_model->insert($insert_data);
                $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
                $token = array(); //remember to declare $pass as an array
                $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
                for ($i = 0; $i < 30; $i++) {
                    $n = rand(0, $alphaLength);
                    if ($i < 8) {
                        $pass[] = $alphabet[$n];
                    }
                    $token[] = $alphabet[$n];
                }
                $register_confirm_token = implode($token);
                $password = implode($pass);
                $role_id = 11;
                $insert_data = array(
                    'email' => $accountEmail,
                    'first_name' => $user_name,
                    'role_id' => $role_id,
                    'new_password' => $this->auth->hashPassword($password),
                    'confirmation_token' => $register_confirm_token,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                if ($insert_data != null) {
                    $email_check = $this->User_model->get_by(array('email' => $accountEmail));
                    if ($email_check == null) {
                        $user_id = $this->User_model->insert($insert_data);
                        if ($user_id != null) {
                            $insert_vendor = array(
                                'user_id' => $user_id,
                                'vendor_id' => $vendor_id,
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                            );
                            $vendor_groupId = $this->Vendor_groups_model->insert($insert_vendor);
                        }
                        $data['roles'] = $this->Role_model->get($role_id);
                        $role_name = $data['roles']->role_name;
                        $subject = 'Vendor Invitation from DentoMatix';
                        $message = "Hi " . ucwords($user_name) . "," . "<br />Welcome to the Matix marketplace. Please click below to confirm your account email address. <br>"
                                . "<table>"
                                . "<tr>"
                                . "<td><b>Email :</b></td><td>$accountEmail</td>"
                                . "</tr>";
                        if ($role_id != 10) {
                            $message .= "<tr><td><b>Role Name :</b></td><td>$role_name</td></tr>";
                        }
                        $message .= "<tr>"
                                . "<td><b>Password :</b></td><td>$password</td>"
                                . "</tr>"
                                . "</table>"
                                . "<br> <b>Note :</b>Change the auto Generated Password once logged-in"
                                . "<a href='" . base_url() . "superadmin-registrater-confirmation?register_confirm_token=" . $register_confirm_token . "' style='padding: 20px 40px 20px 40px;width:auto;display: block;text-decoration: none;border:0;text-align: center;font-weight: bold;font-size: 16px;font-family: Arial, sans-serif;color: #ffffff;background: #2893FF;border: 1px solid #317ED0;-moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius: 4px;line-height:normal;' class='button_link'>Account Register</a>";
                        $email_data = array(
                            'subject' => $subject,
                            'message' => $message
                        );
                        $body = $this->load->view('/templates/email/index.php', $email_data, TRUE);
                        $mail_status = send_matix_email($body, $subject, $accountEmail);
                        $this->session->set_flashdata('success', 'Vendor created successfully invitation sent. ');
                    } else {
                        $this->session->set_flashdata('error', 'The Vendor Email Id already exists in our records.');
                    }
                    $this->session->set_flashdata('success', ' New Vendor created successfully.');
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Email already exists. Please try again.');
        }
    }

    /*
     *  SuperAdminDashboard
     *      @Vendor -single page
     *      @AJAX call
     *          1. Change  vendor type
     */

    public function change_vendorType() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $vendor_id = $this->input->post('vendor_id');
            $update_data = array(
                'vendor_type' => $this->input->post('vendor_type'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            if ($update_data != null) {
                $this->session->set_flashdata('success', 'Vendor type is changed');
                $this->Vendor_model->update($vendor_id, $update_data);
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }




    /// New Part

    public function save_data() {
        
        $excel_data = $this->input->post('excel_data');
        $file_name = $this->input->post('file_name');

        if (!$excel_data) {
            echo json_encode(['status' => 'error', 'message' => 'No data to save']);
            return;
        }

        // Decode JSON data
        $decoded_data = json_decode($excel_data, true);

        echo "<pre>";
        print_r($decoded_data);
        echo "</pre>";
        die('hsh');
        
        if (!$decoded_data) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid data format']);
            return;
        }

        // Prepare data for database
        $db_data = array(
            'file_name' => $file_name,
            'excel_data' => $excel_data,
            'row_count' => count($decoded_data),
            'created_at' => date('Y-m-d H:i:s')
        );

        if ($this->Excel_model->save_excel_data($db_data)) {
            // Clear session data after successful save
            $this->session->unset_userdata('excel_data');
            $this->session->unset_userdata('file_name');
            echo json_encode(['status' => 'success', 'message' => 'Data saved to database successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error saving data to database']);
        }
    }


    public function showpage() {
        $data['title'] = 'Excel File Manager';
        $this->load->view('excel_manager', $data);
    }

    public function store_session_data() {
        $excel_data = $this->input->post('excel_data');
        $file_name = $this->input->post('file_name');

        if ($excel_data && $file_name) {
            $decoded_data = json_decode($excel_data, true);
     
            $this->session->set_userdata('excel_data', $decoded_data);
            $this->session->set_userdata('file_name', $file_name);
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
        }
    }

    public function update_cell() {
        $row = $this->input->post('row');
        $col = $this->input->post('col');
        $value = $this->input->post('value');

        $excel_data = $this->session->userdata('excel_data');
        if ($excel_data && isset($excel_data[$row][$col])) {
            $excel_data[$row][$col] = $value;
            $this->session->set_userdata('excel_data', $excel_data);
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid cell reference']);
        }
    }

    public function delete_row() {
        $row = $this->input->post('row');
        $excel_data = $this->session->userdata('excel_data');
        
        if ($excel_data && isset($excel_data[$row])) {
            unset($excel_data[$row]);
            $excel_data = array_values($excel_data); // Reindex array
            $this->session->set_userdata('excel_data', $excel_data);
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid row reference']);
        }
    }



    public function get_saved_data() {
        $data = $this->Excel_model->get_all_excel_data();
        echo json_encode(['status' => 'success', 'data' => $data]);
    }

    public function view_data($id) {
        $data = $this->Excel_model->get_excel_data_by_id($id);
        if ($data) {
            echo json_encode(['status' => 'success', 'data' => $data]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Data not found']);
        }
    }

    public function delete_saved_data($id) {
        if ($this->Excel_model->delete_excel_data($id)) {
            echo json_encode(['status' => 'success', 'message' => 'Data deleted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error deleting data']);
        }
    }

}

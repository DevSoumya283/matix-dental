<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SPadminCatalog extends MW_Controller {

    function __construct() {

        parent::__construct();
        $this->load->library('elasticsearch');
        $this->load->helper('MY_privilege_helper');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('MY_support_helper');
        $this->load->helper('my_email_helper');
        $this->load->model('Admin_customer_notes_model');
        $this->load->model('Admin_organization_notes_model');
        $this->load->model('Images_model');
        $this->load->model('Order_model');
        $this->load->model('Organization_groups_model');
        $this->load->model('Organization_model');
        $this->load->model('Products_model');
        $this->load->model('Product_pricing_model');
        $this->load->model('Product_question_model');
        $this->load->model('Product_answer_model');
        $this->load->model('Prepopulated_list_model');
        $this->load->model('Prepopulated_product_model');
        $this->load->model('Product_custom_field_model');
        $this->load->model('Role_model');
        $this->load->model('Review_model');
        $this->load->model('User_model');
        $this->load->model('Vendor_model');
        $this->load->model('Vendor_groups_model');
    }

    /*
     *  SuperAdmin DashBoard
     *      @Catalog
     *      Delete all the Selected Product(s).
     */

    public function delete_products() {
        if (isset($_SESSION['user_id'])) {
            $delete_id = explode(',', $this->input->post('product_id'));
            $vendorId = $this->input->post('vendor_id');
            Debugger::debug($vendorId);
            if ($delete_id != null) {
                for ($i = 0; $i < count($delete_id); $i++) {
                    // Delete from database.
                    $success = $this->Product_pricing_model->deactivateProduct($delete_id[$i], $vendorId);
                }
                if($success){
                    $this->session->set_flashdata('success', 'Product deactivated successfully..');
                } else {
                    $this->session->set_flashdata('error', 'Could not deactive product');
                }
                header("Location: product-catalog");
            }
        }
    }

    /*
     *  SuperAdmin DashBoard
     *      @Catalog
     *      Add selected Product(s) to Prepopulated list (Assign to Pre-Populated List(s)) MODAL
     *          1. By selecting the Existing Lists
     */

    public function addProduct_prepopList() {
        if (isset($_SESSION['user_id'])) {
            $product_id = explode(',', $this->input->post('product_id'));
            $list_id = $this->input->post('list_id');
            if ($product_id != null) {
                for ($i = 0; $i < count($product_id); $i++) {
                    $listId_Exists = $this->Prepopulated_product_model->get_by(array('list_id' => $list_id, 'product_id' => $product_id[$i]));
                    if ($listId_Exists == null) {
                        $prepopulatedProduct_insert = array(
                            'list_id' => $list_id,
                            'user_id' => '0',
                            'product_id' => $product_id[$i],
                            'updated_at' => date('Y-m-d H:i:s'),
                            'created_at' => date('Y-m-d H:i:s'),
                        );
                        if ($prepopulatedProduct_insert != null) {
                            $this->Prepopulated_product_model->insert($prepopulatedProduct_insert);
                        }
                    }
                }
            }
        }
    }

    /*
     *  SuperAdmin DashBoard
     *      @Catalog
     *      Search for the Prepopulated List with name.
     *          1. By then add selected product(s) to  the Existing Lists.
     */

    public function search_prepopList() {
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $search = $this->input->post('search');
            $query = "SELECT * FROM prepopulated_lists z  WHERE z.listname like '%$search%' and z.user_id='0'";
            $data['pre_populatedLists'] = $this->db->query($query)->result();
            if ($data['pre_populatedLists'] != null) {
                $this->load->view('/templates/admin/catalog/modal-search/list_search.php', $data);
            }
//            echo json_encode($data['prepopulated_search']);
        }
    }

    /*
     *  SuperAdmin DashBoard
     *      @Catalog -> Product Single Page
     *      Delete images
     */

    public function product_imageDelete() {
        $product_id = $this->input->get('product_id');
        $image_id = $this->input->get('image_id');
        if ($image_id != null) {
            $this->Images_model->delete($image_id);
            $main_img = $this->Images_model->get_by(array('model_id' => $product_id, 'model_name' => 'products', 'image_type' => 'mainimg'));
            if ($main_img == null) {
                $image_details = $this->Images_model->get_many_by(array('model_id' => $product_id, 'model_name' => 'products'));
                for ($i = 0; $i < count($image_details); $i++) {
                    $image_type = 'mainimg';
                    if ($i == 0) {
                        $update_img = array(
                            'image_type' => 'mainimg',
                            'updated_at' => date('Y-m-d H:i:s'),
                        );
                        $this->Images_model->update($image_details[$i]->id, $update_img);
                    }
                }
            }
        }
        header('Location: SPadmin-products?product_id=' . $product_id);
    }

    /*
     *  SuperAdmin DashBoard
     *  @Lists
     *      1.Prepopulated list created by superAdmin/admin are shown here with
     *  the count of products in the individual prepopulate lists.
     */

    public function prepopulated_lists() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $user_id = $_SESSION['user_id'];
            if ($user_id != null) {
                $data['prepopulated_lists'] = $this->Prepopulated_list_model->get_many_by(array('user_id' => '0'));
                if ($data['prepopulated_lists'] != null) {
                    for ($i = 0; $i < count($data['prepopulated_lists']); $i++) {
                        $data['prepopulated_lists'][$i]->items = 0;
                        $prepopulated_product = $this->Prepopulated_product_model->get_many_by(array('list_id' => $data['prepopulated_lists'][$i]->id));
                        if ($prepopulated_product != null) {
                            $data['prepopulated_lists'][$i]->items = count($prepopulated_product);
                        }
                    }
                }
            }
            $data['user_approval'] = user_counts();
            $data['flagged_count'] = flagged_count();
            $data['answer_count'] = flaggedAnswer_count();
            $data['My_vendor_users'] = "";
            $data['vendor_shipping'] = "";
            $data['list_detail'] = "";
            $data['promoCodes_active'] = "";
            $this->load->view('/templates/_inc/header-admin.php');
            $this->load->view('/templates/admin/list/index.php', $data);
            $this->load->view('/templates/_inc/footer-admin.php');
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *  SuperAdmin DashBoard
     *  @Lists
     *      Delete all the selected List(s) with the Product(s) in Prepopulated Products.
     */

    public function delete_lists() {
        $list_id = explode(',', $this->input->post('list_id'));
        if ($list_id != null) {
            for ($i = 0; $i < count($list_id); $i++) {
                $this->Prepopulated_list_model->delete($list_id[$i]);
                $this->Prepopulated_product_model->delete_by(array('list_id' => $list_id[$i]));
            }
            $this->session->set_flashdata('error', 'Pre-Populated list deleted.');
            header('Location: prepopulated-lists');
        }
    }

    /*
     *  SuperAdmin DashBoard
     *  @Lists,@Catalog
     *      1.Creating a New List by SuperAdmin/Admin.
     */

    public function add_lists() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $list_name = $this->input->post('listName');
            if ($list_name != null) {
                $insert_data = array(
                    'listname' => $list_name,
                    'user_id' => '0',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                $list_id = $this->Prepopulated_list_model->insert($insert_data);
                if ($list_id != null) {
                    $product_In = $this->input->post('product_id');
                    if ($product_In != null) {
                        $product_id = explode(',', $product_In);
                        for ($i = 0; $i < count($product_id); $i++) {
                            $prepopulatedProduct_insert = array(
                                'list_id' => $list_id,
                                'user_id' => '0',
                                'product_id' => $product_id[$i],
                                'updated_at' => date('Y-m-d H:i:s'),
                                'created_at' => date('Y-m-d H:i:s'),
                            );
                            if ($prepopulatedProduct_insert != null) {
                                $this->Prepopulated_product_model->insert($prepopulatedProduct_insert);
                            }
                        }
                        // By $admin value in the MODAL, Defining the List created from @list or @Catalog
                        $admin = $this->input->post('admin');
                        if ($admin != null) {
                            // MODAL Called from Catalog and will be redirected to the existing Page
                            $this->session->set_flashdata('success', 'New Pre populated list created.');
                        } else {
                            // MODAL Called from List and will be redirected to the List Page
                            $this->session->set_flashdata('success', 'New Pre populated list created.');
                            header('Location: prepopulated-lists');
                        }
                    }
                    $this->session->set_flashdata('success', 'New Pre populated list created.');
                    header('Location: prepopulated-lists');
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *  SuperAdmin DashBoard
     *  @List(s) Detail Page.
     *      1. Product name, Prepopulated product Id will be shown here with the average price.
     */

    public function Lists_details() {

        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $data['limit'] = 30;
            $valuein = $this->input->get("p");
            $offset = 0;
            if ($valuein != null) {
                $offset = ($valuein - 1) * $data['limit'];
            }
            $list_id = $this->input->get('list_id');
            if ($list_id != null) {
                $prePopulatedList = $this->Prepopulated_list_model->get_by(array('id' => $list_id, 'user_id' => '0'));
                if ($prePopulatedList == null) {
                    $this->session->set_flashdata('error', 'Invalid Entry');
                    header('Location: vendorsIn-list');
                } else {
                    $query = "SELECT a.id,b.name,b.mpn,b.id as product_id,sum(c.price)/COUNT(c.product_id) as average FROM prepopulated_products a INNER JOIN products b on b.id=a.product_id LEFT JOIN product_pricings c on c.product_id=b.id WHERE  a.list_id=" . $list_id . " group by a.product_id limit $offset," . $data['limit'] . "";
                    $data['prepopulated_products'] = $this->db->query($query)->result();
                    $query1 = "SELECT count(*) as search_count FROM (SELECT a.id,b.name,b.mpn,b.id as product_id,sum(c.price)/COUNT(c.product_id) as average FROM prepopulated_products a INNER JOIN products b on b.id=a.product_id LEFT JOIN product_pricings c on c.product_id=b.id WHERE  a.list_id=" . $list_id . " group by a.product_id) count";
                    $count_query = $this->db->query($query1)->result();
                    $data['total_count'] = 0;
                    if ($count_query != null) {
                        $data['total_count'] = $count_query[0]->search_count;
                    }
                    $prepopulated_products = $this->Prepopulated_product_model->get_many_by(array('list_id' => $list_id));
                    $data['list_count'] = count($prepopulated_products);
                }
            }
            //  Edit The PRepopulated List.
            $data['list_detail'] = $this->Prepopulated_list_model->get($list_id);

            $this->load->library('pagination');
            $config['base_url'] = base_url() . '/prepopulated-lists-detail';
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
            $data['prepopulated_id'] = $list_id;
            $data['promoCodes_active'] = "";
            $this->load->view('/templates/_inc/header-admin.php');
            $this->load->view('/templates/admin/list/populated/index.php', $data);
            $this->load->view('/templates/_inc/footer-admin.php');

        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    public function delete_prepopulatedProduct() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $list_id = explode(',', $this->input->post('list_id'));
            $prepopulated_id = $this->input->post('prepopulated_id');
            if ($list_id != null) {
                $this->Prepopulated_product_model->delete_many($list_id);
            }
            $this->session->set_flashdata('error', 'The selected product(s) are removed from the List');
            header('Location: prepopulated-lists-detail?list_id=' . $prepopulated_id);
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    // Add Note by SuperAdmin/Admin to the pier
    public function superadmin_notes() {
        if (isset($_SESSION['user_id'])) {
            $customer_id = $this->input->post('customer_id');

            if ($customer_id != null) {
                $insert_data = array(
                    'customer_id' => $customer_id,
                    'admin_id' => $_SESSION['user_id'],
                    'message' => $this->input->post('note'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                if ($insert_data != null) {
                    $this->Admin_customer_notes_model->insert($insert_data);
                }
            }
        }
    }

    // Add Note by SuperAdmin/Admin to the pier about the Customer
    public function organization_notes() {
        $organization_id = $this->input->post('organization_id');
        if ($organization_id != null) {
            $insert_data = array(
                'organization_id' => $organization_id,
                'admin_id' => $_SESSION['user_id'],
                'message' => $this->input->post('note'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            if ($insert_data != null) {
                $this->Admin_organization_notes_model->insert($insert_data);
            }
        }
    }

    // Delete the question and Answer from the Product catalog single page.
    public function delete_productQuestion() {
        if (isset($_SESSION['user_id'])) {
            $question_id = $this->input->get('question_id');
            $product_id = $this->input->get('product_id');
            if ($question_id != null) {
                $this->Product_question_model->delete($question_id);
                $this->Product_answer_model->delete_by(array('question_id' => $question_id));
            }
            header('Location: SPadmin-products?product_id=' . $product_id);
        }
    }

// Delete alone answers.
    public function delete_productAnswer() {
        if (isset($_SESSION['user_id'])) {
            $answer_id = $this->input->get('answer_id');
            $product_id = $this->input->get('product_id');
            if ($answer_id != null) {
                $this->Product_answer_model->delete($answer_id);
            }
            header('Location: SPadmin-products?product_id=' . $product_id);
        }
    }

    // Delete a Review
    public function delete_productReview() {
        if (isset($_SESSION['user_id'])) {
            $review_id = $this->input->get('review_id');
            $product_id = $this->input->get('product_id');
            if ($review_id != null) {
                $this->Review_model->delete($review_id);
            }
            header('Location: SPadmin-products?product_id=' . $product_id);
        }
    }

    // Update the List name.
    public function update_newPopulatedName() {
        if (isset($_SESSION['user_id'])) {
            $list_id = $this->input->post('list_id');
            if ($list_id != null) {
                $update_data = array(
                    'listname' => $this->input->post('listName'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                if ($update_data != null) {
                    $this->Prepopulated_list_model->update($list_id, $update_data);
                }
                $this->session->set_flashdata('success', 'Prepopulated Name updated');
                header('Location: prepopulated-lists-detail?list_id=' . $list_id);
            }
        }
    }

}

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ReviewsAdmin extends MW_Controller {

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
        $this->load->model('Flagged_reviews_model');
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

    // NEEDED FOR FUNCTIONS
//    $admin_roles = unserialize(ROLES_ADMINS);
//        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
//
//        } else {
//            $this->session->set_flashdata('error', 'Please login with authorized account.');
//            header('Location: login');
//        }

    /*
     *  SuperAdminDashboard
     *      @Reviews
     *          1.Show all the Reviews which are flagged by(All user(s))
     *              1.Flagged By
     *              2. Reviewed By
     */
    public function reviews_admin() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $data['limit'] = 30;
            $valuein = $this->input->get("p");
            $offset = 0;
            if ($valuein != null) {
                $offset = ($valuein - 1) * $data['limit'];
            }
            $data['total_count'] = 0;
            $query = "SELECT b.id,b.user_id,b.model_id,b.title,b.comment,a.created_at,a.model_name FROM flagged_reviews a INNER JOIN reviews b on b.id=a.model_id where a.model_name not in(3) GROUP by b.id";
            $data['ReviewsAdminCount'] = $this->db->query($query)->result();
            if ($data['ReviewsAdminCount'] != null) {
                $data['total_count'] = count($data['ReviewsAdminCount']);
            }
            $query = "SELECT b.id,b.user_id,b.model_id,b.title,b.comment,a.created_at,a.model_name FROM flagged_reviews a INNER JOIN reviews b on b.id=a.model_id where a.model_name not in(3) GROUP by b.id limit $offset," . $data['limit'] . "";
            $data['ReviewsAdmin'] = $this->db->query($query)->result();

            if ($data['ReviewsAdmin'] != null) {
                $flaggedDetails = "";
                for ($i = 0; $i < count($data['ReviewsAdmin']); $i++) {
                    $data['ReviewsAdmin'][$i]->reviewedUser = "";
                    $data['ReviewsAdmin'][$i]->flaggedUser = "";
                    $data['ReviewsAdmin'][$i]->reviewedModelName = "";
                    $data['ReviewsAdmin'][$i]->reviewedUser = $this->User_model->get($data['ReviewsAdmin'][$i]->user_id);
                    // Check whether Vendor||User
                    if($data['ReviewsAdmin'][$i]->reviewedUser!=null) {
                    if ($data['ReviewsAdmin'][$i]->reviewedUser->role_id == "11") {
                        $data['ReviewsAdmin'][$i]->reviewedUser->vendor_id = "";
                        $vendorGroups = $this->Vendor_groups_model->get_by(array('user_id' => $data['ReviewsAdmin'][$i]->reviewedUser->id));
                        if ($vendorGroups != null) {
                            $data['ReviewsAdmin'][$i]->reviewedUser->vendor_id = $vendorGroups->vendor_id;
                        }
                    }
                    }
                    // Getting User_details for Flagged By
                    $flaggedDetails = $this->Flagged_reviews_model->get_many_by(array('model_id' => $data['ReviewsAdmin'][$i]->id, 'model_name not in(3)'));
                    if ($flaggedDetails != null) {
                        for ($j = 0; $j < count($flaggedDetails); $j++) {
                            $data['ReviewsAdmin'][$i]->flaggedUser[] = $this->User_model->get($flaggedDetails[$j]->user_id);
                            //  Check whether the User is Vendor || Customer
                            for ($k = 0; $k < count($data['ReviewsAdmin'][$i]->flaggedUser); $k++) {
                                if ($data['ReviewsAdmin'][$i]->flaggedUser[$k]->role_id == "11") {
                                    $data['ReviewsAdmin'][$i]->flaggedUser[$k]->vendor_id = "";
                                    $vendorGroups = $this->Vendor_groups_model->get_by(array('user_id' => $data['ReviewsAdmin'][$i]->flaggedUser[$k]->id));
                                    if ($vendorGroups != null) {
                                        $data['ReviewsAdmin'][$i]->flaggedUser[$k]->vendor_id = $vendorGroups->vendor_id;
                                    }
                                }
                            }
                        }
                    }
                    // Getting Product || Vendor Name
                    if ($data['ReviewsAdmin'][$i]->model_name == "product") {
                        $productDetails = $this->Products_model->get_by(array('id' => $data['ReviewsAdmin'][$i]->model_id));
                        $data['ReviewsAdmin'][$i]->reviewedModelName = $productDetails->name;
                    }
                    if ($data['ReviewsAdmin'][$i]->model_name == "vendor") {
                        $productDetails = $this->Vendor_model->get_by(array('id' => $data['ReviewsAdmin'][$i]->model_id));
                        $data['ReviewsAdmin'][$i]->reviewedModelName = $productDetails->name;
                    }
                }
            }
            $this->load->library('pagination');
            $config['base_url'] = base_url() . '/superAdmin-Reviews';
            $config['total_rows'] = $data['total_count'];
            $config['per_page'] = $data['limit'];
            $this->pagination->initialize($config);
            $config['enable_query_strings'] = TRUE;
            $config['page_query_string'] = TRUE;
            $config['use_page_numbers'] = TRUE;

            $data['flagged_count'] = flagged_count();
            $data['user_approval'] = user_counts();
            $data['answer_count'] = flaggedAnswer_count();
            $data['My_vendor_users'] = "";
            $data['vendor_shipping'] = "";
            $data['promoCodes_active'] = "";
            
            $this->load->view('/templates/_inc/header-admin.php');
            $this->load->view('/templates/admin/reviews/index.php', $data);
            $this->load->view('/templates/_inc/footer-admin.php');

        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *  SuperAdminDashboard
     *      @Answers
     *          1.Show all the answer which are flagged by(All user(s))
     *              1.Flagged By
     *              2. Answer By
     */

    public function flagged_answers() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $data['limit'] = 30;
            $valuein = $this->input->get("p");
            $offset = 0;
            if ($valuein != null) {
                $offset = ($valuein - 1) * $data['limit'];
            }
            $data['total_count'] = 0;
            $query = "SELECT b.id,a.user_id,a.model_id,b.answer,c.question,d.first_name as author,a.created_at,e.id as product_id,e.name,f.vendor_id FROM flagged_reviews a INNER JOIN product_answers b on b.id=a.model_id INNER JOIN product_questions c on c.id=b.question_id INNER JOIN users d on d.id=b.answered_by INNER JOIN products e on e.id=c.product_id LEFT JOIN vendor_groups f on f.user_id=d.id and d.role_id in(11) WHERE a.model_name in(3) group by b.id";
            $data['FlaggedAnswersCount'] = $this->db->query($query)->result();
            if ($data['FlaggedAnswersCount'] != null) {
                $data['total_count'] = count($data['FlaggedAnswersCount']);
            }
            $query = "SELECT b.id,a.user_id,a.model_id,b.answer,c.question,d.first_name as author,a.created_at,e.id as product_id,e.name,f.vendor_id FROM flagged_reviews a INNER JOIN product_answers b on b.id=a.model_id INNER JOIN product_questions c on c.id=b.question_id INNER JOIN users d on d.id=b.answered_by INNER JOIN products e on e.id=c.product_id LEFT JOIN vendor_groups f on f.user_id=d.id and d.role_id in(11) WHERE a.model_name in(3) group by b.id limit $offset," . $data['limit'] . "";
            $data['FlaggedAnswers'] = $this->db->query($query)->result();
            if ($data['FlaggedAnswers'] != null) {
                for ($i = 0; $i < count($data['FlaggedAnswers']); $i++) {
                    $data['FlaggedAnswers'][$i]->flaggedUser = "";
                    $flaggedDetails = $this->Flagged_reviews_model->get_many_by(array('model_id' => $data['FlaggedAnswers'][$i]->model_id, 'model_name in(3)'));
                    if ($flaggedDetails != null) {
                        for ($j = 0; $j < count($flaggedDetails); $j++) {
                            $data['FlaggedAnswers'][$i]->flaggedUser[] = $this->User_model->get($flaggedDetails[$j]->user_id);
                            if ($data['FlaggedAnswers'][$i]->flaggedUser != null) {
                                //  Check whether the User is Vendor || Customer
                                for ($k = 0; $k < count($data['FlaggedAnswers'][$i]->flaggedUser); $k++) {
                                    if ($data['FlaggedAnswers'][$i]->flaggedUser[$k]->role_id == "11") {
                                        $data['FlaggedAnswers'][$i]->flaggedUser[$k]->vendor_id = "";
                                        $vendorGroups = $this->Vendor_groups_model->get_by(array('user_id' => $data['FlaggedAnswers'][$i]->flaggedUser[$k]->id));
                                        if ($vendorGroups != null) {
                                            $data['FlaggedAnswers'][$i]->flaggedUser[$k]->vendor_id = $vendorGroups->vendor_id;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            // Pagination Config

            $this->load->library('pagination');
            $config['base_url'] = base_url() . '/superAdmin-Answers';
            $config['total_rows'] = $data['total_count'];
            $config['per_page'] = $data['limit'];
            $this->pagination->initialize($config);
            $config['enable_query_strings'] = TRUE;
            $config['page_query_string'] = TRUE;
            $config['use_page_numbers'] = TRUE;
            // Ends here

            $data['user_approval'] = user_counts();
            $data['flagged_count'] = flagged_count();
            $data['answer_count'] = flaggedAnswer_count();
            $data['My_vendor_users'] = "";
            $data['vendor_shipping'] = "";
            $data['promoCodes_active'] = "";
            
            $this->load->view('/templates/_inc/header-admin.php');
            $this->load->view('/templates/admin/answers/index.php', $data);
            $this->load->view('/templates/_inc/footer-admin.php');

        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *      Unflag
     *          1. Unflag the Reviews
     *          2. Unflag the Answers
     *      NOTE: Unflag  Reviews || Answers both are performed here.
     *      Same Model(unflag-items.php) is used for unflagging Reviews||Answers.
     */

    public function unflag_reviews() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $delete_id = explode(',', $this->input->post('flagged_id'));
            if ($delete_id != null) {
                $flagCheck = $this->Flagged_reviews_model->get_by(array('model_id' => $delete_id[0]));
                if ($flagCheck->model_name != "answerby") {
                    $this->Flagged_reviews_model->delete_by(array('model_id' => $delete_id, 'model_name not in(3)'));
                } else {
                    $this->Flagged_reviews_model->delete_by(array('model_id' => $delete_id, 'model_name in(3)'));
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

    /*
     *      Delete Flagged Reviews
     *          1. Delete the Reviews
     *          2. Delete the Answers
     *      NOTE: Delete  Reviews || Answers both are performed here.
     *      Same Model(delete-flagged-items.php) is used for deleting Reviews||Answers.
     */

    public function deleteFlagged_reviews() {
        $admin_roles = unserialize(ROLES_ADMINS);
        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $admin_roles))) {
            $delete_id = explode(',', $this->input->post('flagged_id'));
            if ($delete_id != null) {
                /*
                 *  Below ($flagCheck) is Used for Check whether its a ANSWERS || PRODUCTS,VENDOR
                 *      @Reviews ->delete
                 *      @Answers ->delete
                 */
                $flagCheck = $this->Flagged_reviews_model->get_by(array('model_id' => $delete_id[0]));
                if ($flagCheck->model_name != "answerby") {
                    for ($i = 0; $i < count($delete_id); $i++) {
                        $reviewWillDelete = $this->Review_model->get_by(array('id' => $delete_id[$i], 'model_name' => 'products'));
                        $review = $this->Review_model->get_many_by(array('model_id' => $reviewWillDelete->model_id, 'model_name' => 'products','id not in('.$reviewWillDelete->id.')'));
                        $rating = 0;
                        $reviewcount = count($review);
                        if ($reviewcount > 0) {
                            foreach ($review as $row) {
                                $rating = $rating + $row->rating;
                            }
                            $rating = $rating / $reviewcount;
                        } else {
                            $rating = $this->input->post('rating1');
                        }
                        $update_data = array(
                            'average_rating' => $rating,
                            'updated_at' => date('Y-m-d H:i:s'),
                        );
                        $this->Products_model->update($reviewWillDelete->model_id, $update_data);
                        $this->elasticsearch->add("products", $reviewWillDelete->model_id, $update_data);
                    }
                    $this->Flagged_reviews_model->delete_by(array('model_id' => $delete_id, 'model_name not in(3)'));
                    $this->Review_model->delete_by(array('id' => $delete_id));
                } else {
                    $this->Flagged_reviews_model->delete_by(array('model_id' => $delete_id, 'model_name in(3)'));
                    $this->Product_answer_model->delete_by(array('id' => $delete_id));
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Please login with authorized account.');
            header('Location: login');
        }
    }

}

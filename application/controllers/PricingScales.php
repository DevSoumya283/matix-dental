<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PricingScales extends MW_Controller {


    function __construct() {
        parent::__construct();
        $this->load->model('PricingScales_model');
        $this->load->model('PricingScales_model');
        $this->load->model('User_model');
        $this->load->model('Vendor_model');
        $this->load->model('Vendor_groups_model');
        $this->load->helper('MY_support_helper');
    }

    public function listAll()
    {
        if($this->User_model->loggedIn() && $this->User_model->can($_SESSION['user_permissions'], 'list-pricing-scales')){
            $data = $this->getCounts();
            $data['vendorId'] = ($_SESSION['vendor_id']) ? $_SESSION['vendor_id'] : $this->input->get('vendorId');
            $data['vendors'] = $this->db->get('vendors')->result();
            $data['pricingScales'] = $this->PricingScales_model->loadAllByVendor($data['vendorId']);
            $data['userType'] = ($this->User_model->can($_SESSION['user_permissions'], 'is-admin') ? 'admin' : 'vendor');

            // Debugger::debug($data);
            $this->load->view('/templates/_inc/header-admin.php');
            $this->load->view('/templates/pricing-scales/list.php', $data);
            $this->load->view('/templates/_inc/footer-admin.php');
        } else {
            $this->session->set_flashdata('error', 'You do not have access to that page');
            header('Location: /login');
        }
    }

    public function create()
    {
        if($this->User_model->loggedIn() && $this->User_model->can($_SESSION['user_permissions'], 'edit-pricing-scales')){
            $this->PricingScales_model->create($_POST);

            $this->Memc->flush();
        }
    }

    public function edit()
    {
        if($this->User_model->loggedIn() && $this->User_model->can($_SESSION['user_permissions'], 'edit-pricing-scales')){
            $data = $this->getCounts();

            $data['vendorId'] = ($_SESSION['vendor_id']) ? $_SESSION['vendor_id'] : $this->input->get('vendorId');
            $data['pricingScale'] = $this->PricingScales_model->load($this->input->get('id'));
            $data['userType'] = ($this->User_model->can($_SESSION['user_permissions'], 'is-admin') ? 'admin' : 'vendor');
            
            $this->load->view('/templates/_inc/header-admin.php');
            $this->load->view('/templates/pricing-scales/edit.php', $data);
            $this->load->view('/templates/_inc/footer-admin.php');

            
        } else {
            $this->session->set_flashdata('error', 'You do not have access to that page');
            header('Location: /login');
        }
    }

    public function save()
    {
        if($this->User_model->loggedIn() && $this->User_model->can($_SESSION['user_permissions'], 'edit-pricing-scales')){
            $this->PricingScales_model->save($_POST);
        } else {
            die("You don't have permission to do this");
        }
    }

    public function toggleActive()
    {
        $this->PricingScales_model->toggleActive($this->input->post('id'));
        $this->Memc->flush();
    }

    public function manageProducts()
    {
        //  Debugger::debug($_GET);
        if($this->User_model->can($_SESSION['user_permissions'], 'is-vendor') || $this->User_model->can($_SESSION['user_permissions'], 'is-admin') ){
            $data = $this->getCounts();
            $data['pricingScale'] = $this->PricingScales_model->load($this->input->get('id'));
            $data['userType'] = ($this->User_model->can($_SESSION['user_permissions'], 'is-admin') ? 'admin' : 'vendor');
            $data['search'] = $this->input->get('search');
            $data['limit'] = 30;
            if($this->input->get('p')){
                $offset = $data['limit'] * ($this->input->get('p') - 1);
            }
            $data['order_by'] = $this->input->get('order_by');
            $categorySelect = $this->input->get("categorySelect");
            $productStatus = $this->input->get("productStatus");
            $promos = $this->input->get('promos');
            if(!empty($this->input->get('vendorId'))){
                $vendorId = $this->input->get('vendorId');
                $data['vendor_detail'] = $this->Vendor_groups_model->get_by(array('id' => $vendorId));
            } else {
                $data['vendor_detail'] = $this->Vendor_groups_model->get_by(array('user_id' => $_SESSION['user_id']));
                if ($data['vendor_detail'] != null) {
                    $vendorId = $data['vendor_detail']->vendor_id;
                }
            }
            $data['vendorId'] = $vendorId;
            $config['base_url'] = base_url() . '/pricing-scales/manage-products?id=' . $this->input->get('id');
            if(!empty($data['search']) && !empty($vendorId)){
                // Debugger::debug($this->input->get('id'), '$this->input->get(\'id\')');
                $data['vendor_products'] = $this->Vendor_model->searchProducts($vendorId, $data['search'],  $data['order_by'], $data['limit'], $offset, $data['pricingScale']->id);
                $data['total_rows'] = $this->Vendor_model->getSearchTotalCount($vendorId, $data['search'], $data['order_by']);

            } else if( !empty($vendorId)){
                $data['vendor_products'] = $this->Vendor_model->loadProducts($vendorId, $promos, $categorySelect, $productStatus, $data['order_by'], $data['limit'], $offset, $this->input->get('id'));

                if (($Category_select != null) || ($promos != null) || ($productStatus != null)) {
                    if ($Category_select != null && $Category_select != "") {
                        $Category_select = "and p.category_id like '%\"$Category_select\"%'";
                    } else {
                        $Category_select = "";
                    }
                    if ($promos == 2) {
                        $promoValue = " INNER ";
                    } else {
                        $promoValue = " LEFT ";
                    }
                    if ($productStatus == null && $productStatus == "") {
                        $productStatus = 1;
                    }
                    $total_count_query = "select count(pp.id) as vendor_products_count from products as p LEFT JOIN product_pricings as pp ON p.id=pp.product_id $promoValue JOIN promo_codes as pc ON p.id = pc.product_id and pp.vendor_id = pc.vendor_id where pp.vendor_id=$vendorId $Category_select and pp.active=$productStatus";
                } else {
                    $total_count_query = "select count(pp.id) as vendor_products_count from products as p LEFT JOIN product_pricings as pp ON p.id=pp.product_id LEFT JOIN promo_codes as pc ON p.id = pc.product_id and pp.vendor_id = pc.vendor_id where pp.vendor_id=$vendorId";
                }

                $total_vendor_products = $this->db->query($total_count_query)->result();
                 $data['total_rows'] = $total_vendor_products[0]->vendor_products_count;
            }


            $this->load->library('pagination');
            $config['base_url'] = base_url() . '/pricing-scales/manage-products?1=1';
            $config['total_rows'] = $data['total_rows'];
            $config['per_page'] = $data['limit'];
            $this->pagination->initialize($config);
            $config['enable_query_strings'] = TRUE;
            $config['page_query_string'] = TRUE;
            $config['use_page_numbers'] = TRUE;

            // Categories START ***
            if( !empty($vendorId)){
                $queryproduct = $this->db->query("select *,f.id as location_id from product_pricings a INNER JOIN products b on b.id=a.product_id INNER JOIN order_items c on c.product_id=a.product_id INNER JOIN orders d on d.id=c.order_id INNER JOIN user_locations e on e.user_id=d.user_id INNER JOIN organization_locations f on f.id=e.organization_location_id where d.restricted_order='0'and a.vendor_id=$vendorId");
                $data['products'] = $queryproduct->result();
            } else {
                $data['vendors'] = $this->BuyingClub_model->loadClubVendors($this->input->get('id'));
                // Debugger::debug($data['vendors']);
            }
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
                $dentist_categories = $this->Category_model->get_many_by(array('parent_id' => 2));
                for ($i = 0; $i < count($dentist_categories); $i++) {
                    $count = 0;
                    for ($j = 0; $j < count($data['catIdCount']); $j++) {
                        if (in_array($dentist_categories[$i]->id, $data['catIdCount'][$j])) {
                            $count +=1;
                        }
                    }
                    $dentist_categories[$i]->count = $count;
                }
                $data['dentist'] = $dentist_categories;
            }
            // Categories END ***
            $this->load->view('/templates/_inc/header-admin.php');
            $this->load->view('/templates/vendor-admin/pricing-scales/product-index.php', $data);
            $this->load->view('/templates/_inc/footer-admin.php');
            
        } else {
            $this->session->set_flashdata('error', 'You do not have access to that page');
            header('Location: /login');
        }
    }

    public function importProductPricings()
    {
        if(!empty($_FILES['pricingScaleDataFile']) && ($this->User_model->can($_SESSION['user_permissions'], 'edit-pricing-scales'))){
            $fp = fopen($_FILES['pricingScaleDataFile']['tmp_name'], 'r');
            $fields = fgetcsv($fp);

            while (! feof($fp)){
                $data = fgetcsv($fp);
                if(!empty($data[3])){
                    $this->PricingScales_model->importPrice($data[0], $data[1], $data[2], $data[3]);
                } else {
                    // no price set, delete pricing minimum
                    $this->PricingScales_model->deleteMinimumPrice($data[0], $data[1], $data[2]);
                }
            }
            $this->Memc->flush();
        }
    }

    public function saveProductPricing()
    {
        Debugger::debug('saveProductPricing');

        if(($this->User_model->can($_SESSION['user_permissions'], 'edit-pricing-scales')) || $this->User_model->can($_SESSION['user_permissions'], 'is-admin')){
            if($this->input->post('scale_price') > 0){
                Debugger::debug('saving price');
                $this->PricingScales_model->savePrice($this->input->post('pricing_scale_id'), $this->input->post('product_id'), $this->input->post('vendor_id'), $this->input->post('scale_price'));
            } else {
                Debugger::debug('deleting price');
                $this->PricingScales_model->deletePrice($this->input->post('club_id'), $this->input->post('product_id'), $this->input->post('vendor_id'));
            }

            $this->Memc->flush();
            echo number_format($this->input->post('scale_price'), 2);
        } else {
            $this->session->set_flashdata('error', 'You do not have access to that page');
            header('Location: /login');
        }
    }

    public function savePrice($pricingScaleId, $productId, $vendorId, $salePrice)
    {
        $sql = "INSERT INTO pricing_scale_product_pricing (
                    pricing_scale_id,
                    product_id,
                    vendor_id,
                    sale_price
                ) VALUES (
                    :pricingScaleId,
                    :productId,
                    :vendorId,
                    :salePrice
                )
                ON DUPLICATE KEY UPDATE
                    sale_price = :salePrice";

        $params = [
            ':pricingScaleId' => $pricingScaleId,
            ':vendorId' => $vendorId,
            ':productId' => $productId,
            ':salePrice' => $salePrice
        ];

        $this->PDOhandler->query($sql, $params);
    }

    public function deletePrice($pricingScaleId, $productId, $vendorId)
    {
        $sql = "DELETE FROM pricing_scale_product_pricing
                WHERE pricing_scale_id = :pricingScaleId
                AND product_id = :productId
                AND vendor_id = :vendorId";


        $params = [
            ':pricingScaleId' => $pricingScaleId,
            ':vendorId' => $vendorId,
            ':productId' => $productId
        ];

        $this->PDOhandler->query($sql, $params);
    }

    public function getCounts()
    {
        $counts = [
            'user_approval' => user_counts(),
            'flagged_count' => flagged_count(),
            'answer_count' => flaggedAnswer_count(),
            'ReturnCount' => return_count()
        ];

        if($this->User_model->can($_SESSION['user_permissions'], 'is-vendor')){
            $counts['NorderCount'] = order_count();
        }

        return $counts;
    }
}
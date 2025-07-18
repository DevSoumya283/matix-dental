<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class BuyingClubs extends MW_Controller {


    function __construct() {
        parent::__construct();
        $this->load->model('BuyingClub_model');
        $this->load->model('PricingScales_model');
        $this->load->model('User_model');
        $this->load->model('Vendor_model');
        $this->load->model('Vendor_groups_model');
        $this->load->helper('MY_support_helper');
    }

    public function listAll()
    {
        if($this->User_model->loggedIn() && $this->User_model->can($_SESSION['user_permissions'], 'list-buying-clubs')){
            $data = $this->getCounts();
            $data['buyingClubs'] = $this->BuyingClub_model->loadAll($_SESSION['user_id']);
            $data['buying_club_owners'] = $this->BuyingClub_model->loadClubVendors();
            $data['userType'] = ($this->User_model->can($_SESSION['user_permissions'], 'is-admin') ? 'admin' : 'vendor');
            if($this->input->get('vendorId')){
                $data['bcVendorId'] = $this->input->get('vendorId');
                $data['buyingClubs'] = array_merge($this->BuyingClub_model->loadAll($data['bcVendorId']));
            }
            Debugger::debug($data);
            $this->load->view('/templates/_inc/header-admin.php');
            $this->load->view('/templates/buying-clubs/list.php', $data);
            $this->load->view('/templates/_inc/footer-admin.php');
        } else {
            $this->session->set_flashdata('error', 'You do not have access to that page');
            header('Location: /login');
        }
    }

    public function edit()
    {
        if($this->User_model->loggedIn() && $this->User_model->can($_SESSION['user_permissions'], 'edit-own-buying-clubs')){
            $data = $this->getCounts();
            $data['buyingClub'] = $this->BuyingClub_model->loadClub($this->input->get('id'), $_SESSION['vendor_id']);
            if(!empty($_SESSION['vendor_id']) && empty($data['buyingClub']->vendors)){

            }
            $data['vendors'] = $this->BuyingClub_model->loadVendors($this->input->get('id'));
            $data['userType'] = ($this->User_model->can($_SESSION['user_permissions'], 'is-admin') ? 'admin' : 'vendor');
            // pricing scales
            foreach($data['vendors'] as $k => $vendor){
                // Debugger::debug($vendor);
                $data['pricingScales'][$vendor->vendor_id] = $this->PricingScales_model->loadAllByVendor($vendor->vendor_id);
            }

            // Debugger::debug($data);
            $this->load->view('/templates/_inc/header-admin.php');
            $this->load->view('/templates/buying-clubs/edit.php', $data);
            $this->load->view('/templates/_inc/footer-admin.php');
        } else {
            $this->session->set_flashdata('error', 'You do not have access to that page');
            header('Location: /login');
        }
    }

    public function loadVendors()
    {
        $vendors = $this->BuyingClub_model->loadVendors($this->input->post('id'));
        //Debugger::debug($vendors, '$vendors');

        $output = "";
        foreach($vendors as $vendor){
            $output .= '<tr>
                            <td>' . $vendor->name . '</td>';
            if($this->User_model->can($_SESSION['user_permissions'], 'is-admin')){
                $output .= '<td><a href="#" class="btn btn--s btn--primary is--pos btn--dir delete-vendor" data-club_id="' . $vendor->buying_club_id . '" data-vendor_id="' . $vendor->vendor_id . '" data-type="vendor">X</a></td>';
            }
            $output .= '</tr>
                        ';
        }
        echo $output;
    }

    public function loadOrganizations()
    {
        $organizations = $this->BuyingClub_model->loadOrganizations($this->input->post('id'));
        // Debugger::debug($organizations, '$organizations');

        $output = "";
        foreach($organizations as $organization){
            $output .= '<tr>
                            <td>' . $organization->name . '</td>';
            if($this->User_model->can($_SESSION['user_permissions'], 'is-admin')){
                $output .= '<td><a href="#" class="btn btn--s btn--primary is--pos btn--dir delete-organization" data-club_id="' . $organization->buying_club_id . '" data-organization_id="' . $organization->organization_id . '">X</a></td>';
            }
            $output .= '</tr>
                        ';
        }
        echo $output;
    }
    public function manageVendors()
    {
        if($this->User_model->loggedIn() && $this->User_model->can($_SESSION['user_permissions'], 'edit-own-buying-clubs')){
            $data = $this->getCounts();
            $data['buyingClub'] = $this->BuyingClub_model->loadClub($this->input->get('id'), $_SESSION['vendor_id']);
            $data['userType'] = ($this->User_model->can($_SESSION['user_permissions'], 'is-admin') ? 'admin' : 'vendor');
            $data['vendors'] = $this->BuyingClub_model->loadVendors($this->input->get('id'));

            $this->load->view('/templates/buying-clubs/manage-vendors.php', $data);
        } else {
            $this->session->set_flashdata('error', 'You do not have access to that page');
            header('Location: /login');
        }
    }

    public function manageOrganizations()
    {
        if($this->User_model->loggedIn() && $this->User_model->can($_SESSION['user_permissions'], 'edit-own-buying-clubs')){
            $data = $this->getCounts();
            $data['buyingClub'] = $this->BuyingClub_model->loadClub($this->input->get('id'));
            $data['userType'] = ($this->User_model->can($_SESSION['user_permissions'], 'is-admin') ? 'admin' : 'vendor');
            $data['organizations'] = $this->BuyingClub_model->loadOrganizations($this->input->get('id'));

            $this->load->view('/templates/buying-clubs/manage-organizations.php', $data);
        } else {
            $this->session->set_flashdata('error', 'You do not have access to that page');
            header('Location: /login');
        }
    }

    public function savePricingScale()
    {
        foreach($_POST['pricing_scales'] AS $vendorId => $pricingScaleId){
            $this->BuyingClub_model->savePricingScale($_POST['bc_id'], $vendorId, $pricingScaleId);
        }
        $this->Memc->flush();
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

    public function create()
    {
        Debugger::debug($_POST);
        $this->BuyingClub_model->save(null, $this->input->post('name'), $this->input->post('userId'));

        if(!empty($_SESSION['vendor_id'])){
            Debugger::debug('adding vendor');
            $sql = "INSERT INTO buying_club_vendors (
                        buying_club_id,
                        vendor_id
                    ) VALUES (
                        (SELECT id FROM buying_clubs WHERE name = :name AND owner_id = :ownerId),
                        :vendorId
                    )";
            $params = [
                ':name' =>  $this->input->post('name'),
                ':ownerId' => $this->input->post('userId'),
                ':vendorId' => $_SESSION['vendor_id']
            ];

            $this->PDOhandler->query($sql, $params);
        }
        $this->Memc->flush();
    }

    public function save()
    {
        Debugger::debug($_POST);
        $this->BuyingClub_model->save($this->input->post('club_id'), $this->input->post('name'), $_SESSION['user_id']);

        $this->Memc->flush();
    }

    public function import()
    {
        // Debugger::debug('importing');
        Debugger::debug($_FILES, 'IMPORTING');
        if($this->User_model->loggedIn() && $this->User_model->can($_SESSION['user_permissions'], 'edit-own-buying-clubs')){
            if(!empty($_FILES['buyingClubVendorDataFile'])){
                $this->BuyingClub_model->addVendors($_FILES['buyingClubVendorDataFile']);
            } else if(!empty($_FILES['buyingClubOrganizationDataFile'])){
                $this->BuyingClub_model->addOrganizations($_FILES['buyingClubOrganizationDataFile']);
            } else if(!empty($_FILES['pricingScaleDataFile'])){
                $this->PricingScale_model->addProducts($_FILES['pricingScaleDataFile']);
            }

            $this->session->set_flashdata('success', 'Buying Club ' . $upload . ' updated.');

            $this->Memc->flush();

            // header('Location: ' . $_SERVER['HTTP_REFERER']);
            echo "done";
        } else {
            $this->session->set_flashdata('error', 'You do not have access to that page');
            header('Location: /login');
        }
    }

    public function exportVendorProducts()
    {
        // if(($this->User_model->can($_SESSION['user_permissions'], 'edit-own-buying-clubs') && $this->input->get('userId') == $_SESSION['user_id']) || $this->User_model->can($_SESSION['user_permissions'], 'is-admin')){
            $products = $this->Vendor_model->loadProducts($this->input->get('vendorId'), null, null, null, 'p.name asc', all, '0', $this->input->get('club_id'));
            // output headers so that the file is downloaded rather than displayed
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=vendorProducts.csv');

            // create a file pointer connected to the output stream
            $output = fopen('php://output', 'w');

            // output the column headings
            fputcsv($output, array('Buying Club Id', 'Vendor Id', 'Matix Product ID', 'Retail Price', 'Sale Price', 'Club Price'));


            // loop over the rows, outputting them
            foreach ($products as $product){
                fputcsv($output, [ $this->input->get('club_id'), $this->input->get('vendorId'), $product->vendor_product_id, trim($product->price), $product->price, $product->club_price]);
            }
        // } else {
        //     $this->session->set_flashdata('error', 'You do not have access to that page');
        //     // header('Location: /login');
        // }
    }

    public function deleteVendor()
    {
        $this->BuyingClub_model->deleteVendor($this->input->post('clubId'), $this->input->post('vendorId'));
        $this->Memc->flush();
        echo json_encode(['done' => 1]);
    }

    public function deleteOrganization()
    {
        $this->BuyingClub_model->deleteOrganization($this->input->post('clubId'), $this->input->post('organizationId'));
        $this->Memc->flush();
        echo json_encode(['done' => 1]);
    }

    public function toggleActive()
    {
        $this->BuyingClub_model->toggleActive($this->input->post('id'));
        $this->Memc->flush();
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . '/third_party/spout/src/Spout/Autoloader/autoload.php';
use Box \ Spout \ Reader \ ReaderFactory;
use Box \ Spout \ Writer \ WriterFactory;
use Box \ Spout \ Common \ Type;


class ProductNOrmalizer extends MW_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'file']);
        $this->load->model('Productdata_model');
        $this->load->model('ProductNormalizer_model');
        $this->load->library('ion_auth');

        $this->load->helper('download');

        $this->load->library('upload'); 
    }

        public function normalize_products()
    {
        $size='20';
        $summary =  $this->ProductNormalizer_model->normalize_existing_products($size);
        // $summary =  $this->ProductNormalizer_model->getProductsWithOptions($size);

        echo"<pre>";
        print_r($summary);die();
        echo"</pre>";
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($summary));
    }

        public function getProductsWithOptions()
    {
        $size='20';
        $summary =  $this->ProductNormalizer_model->getProductsWithOptions($size);

        echo"<pre>";
        print_r($summary);die();
        echo"</pre>";
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($summary));
    }
}
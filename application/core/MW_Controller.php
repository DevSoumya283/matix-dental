<?php
/**
 * Author: https://github.com/davinder17s
 * Email: davinder17s@gmail.com
 * Repository: https://github.com/davinder17s/codeigniter-middleware
 */
class MW_Controller extends MY_Controller
{
    protected $middlewares = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Whitelabel_model');
        $this->load->model('Page_model');
        $this->processWhitelabel($this->Whitelabel_model->testDomain($_SERVER['HTTP_HOST']));
        $this->_run_middlewares();
    }

    protected function middleware()
    {
        return array();
    }

    protected function _run_middlewares()
    {
        $this->load->helper('inflector');
        $middlewares = $this->middleware();
        foreach($middlewares as $middleware){
            $middlewareArray = explode('|', str_replace(' ', '', $middleware));
            $middlewareName = $middlewareArray[0];
            $runMiddleware = true;
            if(isset($middlewareArray[1])){
                $options = explode(':', $middlewareArray[1]);
                $type = $options[0];
                $methods = explode(',', $options[1]);
                if ($type == 'except') {
                    if (in_array($this->router->method, $methods)) {
                        $runMiddleware = false;
                    }
                } else if ($type == 'only') {
                    if (!in_array($this->router->method, $methods)) {
                        $runMiddleware = false;
                    }
                }
            }
            $filename = ucfirst(camelize($middlewareName)) . 'Middleware';
            if ($runMiddleware == true) {
                if (file_exists(APPPATH . 'middlewares/' . $filename . '.php')) {
                    require APPPATH . 'middlewares/' . $filename . '.php';
                    $ci = &get_instance();
                    $object = new $filename($this, $ci);
                    $object->run();
                    $this->middlewares[$middlewareName] = $object;
                } else {
                    if (ENVIRONMENT == 'development') {
                        show_error('Unable to load middleware: ' . $filename . '.php');
                    } else {
                        show_error('Sorry something went wrong.');
                    }
                }
            }
        }
    }

    public function processWhitelabel($whitelabel)
    {
        if(!empty($whitelabel) && $whitelabel->whitelabel == 1){
            $this->config->set_item('whitelabel', $whitelabel);
            $this->config->set_item('logo', $whitelabel->logo);
            $this->config->set_item('limit_to_vendor_products', $whitelabel->limit_to_vendor_products);
            $this->config->set_item('whitelabel_vendor_id', $whitelabel->vendor_id);
            $this->config->set_item('name', $whitelabel->name);
            $this->config->set_item('hide_selected_products', $whitelabel->hide_selected_products);
            $this->config->set_item('tagline', $whitelabel->tagline);
            $this->config->set_item('bg-color', $whitelabel->bg_color);
            $this->config->set_item('btn-color-1', $whitelabel->btn_color_1);
            $this->config->set_item('btn-color-2', $whitelabel->btn_color_2);
            $this->config->set_item('domain', $whitelabel->short_name . '.' . $this->config->item('domain'));
            // use vanity url if exists
            if(!empty(config_item('whitelabel')->domain)) {
                $this->config->set_item('domain', config_item('whitelabel')->domain);
            }

            $this->config->set_item('site_url', 'http://' . $this->config->item('domain') . '/');
        }

        $pages = $this->Page_model->loadAll($whitelabel->id);
        $menuLinks = [];
        foreach($pages as $page){
            $menuLinks[$page->name] = $page->page_title;
        }

        $this->config->set_item('menu-links', $menuLinks);
    }
}
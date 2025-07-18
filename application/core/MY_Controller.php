<?php

/**
 * Base controllers for different purposes
 * 	- MY_Controller: for Frontend Website
 * 	- Admin_Controller: for Admin Panel (require login), extends from MY_Controller
 * 	- API_Controller: for API Site, extends from REST_Controller
 */
class MY_Controller extends MX_Controller {

	// Values to be obtained automatically from router
	protected $mModule = '';			// module name (empty = Frontend Website)
	protected $mCtrler = 'home';		// current controller
	protected $mAction = 'index';		// controller function being called
	protected $mMethod = 'GET';			// HTTP request method

	// Config values from config/ci_bootstrap.php
	protected $mConfig = array();
	protected $mBaseUrl = array();
	protected $mSiteName = '';
	protected $mMetaData = array();
	protected $mScripts = array();
	protected $mStylesheets = array();

	// Values and objects to be overrided or accessible from child controllers
	protected $mPageTitlePrefix = '';
	protected $mPageTitle = '';
	protected $mBodyClass = '';
	protected $mMenu = array();
	protected $mBreadcrumb = array();

	// Multilingual
	protected $mMultilingual = FALSE;
	protected $mLanguage = 'en';
	protected $mAvailableLanguages = array();

	// Data to pass into views
	protected $mViewData = array();

	// Login user
	protected $mPageAuth = array();
	protected $mUser = NULL;
	protected $mUserGroups = array();
	protected $mUserMainGroup;

	// Constructor
	public function __construct()
	{
		parent::__construct();

		// router info
		$this->mModule = $this->router->fetch_module();
		$this->mCtrler = $this->router->fetch_class();
		$this->mAction = $this->router->fetch_method();
		$this->mMethod = $this->input->server('REQUEST_METHOD');
		//$this->output->enable_profiler(TRUE);
		$this->load->model('User_model');
		$this->load->model('Category_model');
		$this->load->model('Products_model');
		$this->load->model('Product_pricing_model');
		$this->load->model('Review_model');
		$this->load->model('Images_model');
                $this->load->model('Order_model');
		$this->load->model('Promo_codes_model');
		$this->load->model('User_autosave_model');
		$this->load->model('User_location_model');
		$this->load->model('Organization_location_model');


		// initial setup
		$this->_setup();
	}

	// Setup values from file: config/ci_bootstrap.php
	private function _setup()
	{
		$config = $this->config->item('ci_bootstrap');

		// load default values
		$this->mBaseUrl = empty($this->mModule) ? base_url() : base_url($this->mModule).'/';
		$this->mSiteName = empty($config['site_name']) ? '' : $config['site_name'];
		$this->mPageTitlePrefix = empty($config['page_title_prefix']) ? '' : $config['page_title_prefix'];
		$this->mPageTitle = empty($config['page_title']) ? '' : $config['page_title'];
		$this->mBodyClass = empty($config['body_class']) ? '' : $config['body_class'];
		$this->mMenu = empty($config['menu']) ? array() : $config['menu'];
		$this->mMetaData = empty($config['meta_data']) ? array() : $config['meta_data'];
		$this->mScripts = empty($config['scripts']) ? array() : $config['scripts'];
		$this->mStylesheets = empty($config['stylesheets']) ? array() : $config['stylesheets'];
		$this->mPageAuth = empty($config['page_auth']) ? array() : $config['page_auth'];
		$this->load->driver('cache', array('adapter' => 'memcached', 'backup' => 'file'));
		// multilingual setup
		$lang_config = empty($config['languages']) ? array() : $config['languages'];
		if ( !empty($lang_config) )
		{
			$this->mMultilingual = TRUE;
			$this->load->helper('language');

			// redirect to Home page in default language
//			if ( empty($this->uri->segment(1)) )
			if ( !($this->uri->segment(1)) )
			{
				$home_url = base_url($lang_config['default']).'/';
				redirect($home_url);
			}

			// get language from URL, or from config's default value (in ci_bootstrap.php)
			$this->mAvailableLanguages = $lang_config['available'];
			$language = array_key_exists($this->uri->segment(1), $this->mAvailableLanguages) ? $this->uri->segment(1) : $lang_config['default'];

			// append to base URL
			$this->mBaseUrl.= $language.'/';

			// autoload language files
			foreach ($lang_config['autoload'] as $file)
				$this->lang->load($file, $this->mAvailableLanguages[$language]['value']);

			$this->mLanguage = $language;
		}

		// restrict pages
		$uri = ($this->mAction=='index') ? $this->mCtrler : $this->mCtrler.'/'.$this->mAction;
		if ( !empty($this->mPageAuth[$uri]) && !$this->ion_auth->in_group($this->mPageAuth[$uri]) )
		{
			$page_404 = $this->router->routes['404_override'];
			$redirect_url = empty($this->mModule) ? $page_404 : $this->mModule.'/'.$page_404;
			redirect($redirect_url);
		}

		// push first entry to breadcrumb
		if ($this->mCtrler!='home')
		{
			$page = $this->mMultilingual ? lang('home') : 'Home';
			$this->push_breadcrumb($page, '');
		}

		// get user data if logged in
		if ( $this->ion_auth->logged_in() )
		{
			$this->mUser = $this->ion_auth->user()->row();
			if ( !empty($this->mUser) )
			{
				$this->mUserGroups = $this->ion_auth->get_users_groups($this->mUser->id)->result();

				// TODO: get group with most permissions (instead of getting first group)
				$this->mUserMainGroup = $this->mUserGroups[0]->name;
			}
		}

		$this->mConfig = $config;
	}

	// Verify user login (regardless of user group)
	protected function verify_login($redirect_url = NULL)
	{
		if ( !$this->ion_auth->logged_in() )
		{
			if ( $redirect_url==NULL )
				$redirect_url = $this->mConfig['login_url'];

			redirect($redirect_url);
		}
	}

	// Verify user authentication
	// $group parameter can be name, ID, name array, ID array, or mixed array
	// Reference: http://benedmunds.com/ion_auth/#in_group
	protected function verify_auth($group = 'members', $redirect_url = NULL)
	{
		if ( !$this->ion_auth->logged_in() || !$this->ion_auth->in_group($group) )
		{
			if ( $redirect_url==NULL )
				$redirect_url = $this->mConfig['login_url'];

			redirect($redirect_url);
		}
	}

	// Add script files, either append or prepend to $this->mScripts array
	// ($files can be string or string array)
	protected function add_script($files, $append = TRUE, $position = 'foot')
	{
		$files = is_string($files) ? array($files) : $files;
		$position = ($position==='head' || $position==='foot') ? $position : 'foot';

		if ($append)
			$this->mScripts[$position] = array_merge($this->mScripts[$position], $files);
		else
			$this->mScripts[$position] = array_merge($files, $this->mScripts[$position]);
	}

	// Add stylesheet files, either append or prepend to $this->mStylesheets array
	// ($files can be string or string array)
	protected function add_stylesheet($files, $append = TRUE, $media = 'screen')
	{
		$files = is_string($files) ? array($files) : $files;

		if ($append)
			$this->mStylesheets[$media] = array_merge($this->mStylesheets[$media], $files);
		else
			$this->mStylesheets[$media] = array_merge($files, $this->mStylesheets[$media]);
	}

	// Render template
	protected function render($view_file, $layout = 'default')
	{
		// automatically generate page title
		if ( empty($this->mPageTitle) )
		{
			if ($this->mAction=='index')
				$this->mPageTitle = humanize($this->mCtrler);
			else
				$this->mPageTitle = humanize($this->mAction);
		}

		$this->mViewData['module'] = $this->mModule;
		$this->mViewData['ctrler'] = $this->mCtrler;
		$this->mViewData['action'] = $this->mAction;

		$this->mViewData['site_name'] = $this->mSiteName;
		$this->mViewData['page_title'] = $this->mPageTitlePrefix.$this->mPageTitle;
		$this->mViewData['current_uri'] = empty($this->mModule) ? uri_string(): str_replace($this->mModule.'/', '', uri_string());
		$this->mViewData['meta_data'] = $this->mMetaData;
		$this->mViewData['scripts'] = $this->mScripts;
		$this->mViewData['stylesheets'] = $this->mStylesheets;
		$this->mViewData['page_auth'] = $this->mPageAuth;

		$this->mViewData['base_url'] = $this->mBaseUrl;
		$this->mViewData['menu'] = $this->mMenu;
		$this->mViewData['user'] = $this->mUser;
		$this->mViewData['ga_id'] = empty($this->mConfig['ga_id']) ? '' : $this->mConfig['ga_id'];
		$this->mViewData['body_class'] = $this->mBodyClass;

		// automatically push current page to last record of breadcrumb
		$this->push_breadcrumb($this->mPageTitle);
		$this->mViewData['breadcrumb'] = $this->mBreadcrumb;

		// multilingual
		$this->mViewData['multilingual'] = $this->mMultilingual;
		if ($this->mMultilingual)
		{
			$this->mViewData['available_languages'] = $this->mAvailableLanguages;
			$this->mViewData['language'] = $this->mLanguage;
		}

		// debug tools - CodeIgniter profiler
		$debug_config = $this->mConfig['debug'];
		if (ENVIRONMENT==='development' && !empty($debug_config))
		{
			$this->output->enable_profiler($debug_config['profiler']);
		}
		 if (isset($_SESSION["user_id"])) {
		 	$data['locations'] = $this->User_location_model->get_many_by(array('user_id' => $_SESSION["user_id"]));
                        $data['orders']=$this->Order_model->get_many_by(array('user_id'=>$_SESSION["user_id"]));
            for ($i = 0; $i < count($data['locations']); $i++) {
                $data['user_locations'][] = $this->Organization_location_model->get_by(array('id' => $data['locations'][$i]->organization_location_id));
            }
        }

		$data['category']=$this->Category_model->get_many_by(array('parent_id' => ''));
		  $page = 0;
          $per_page = 10;

        $query = "select p.* from products p, vendors v, product_pricings pp where p.id=pp.product_id and pp.active=1 and pp.vendor_id=v.id and v.active=1 group by p.id limit 10";
        $data['products'] = $this->db->query($query)->result();
        $start = $per_page * $page;
        $end = ($page + 1) * $per_page;
         $data['page'] = $page;
         $total_products =$this->Products_model->count_all();
        //$total_products = count($data['products']);
        $no_of_pages=$total_products/ $per_page;
        if ($end > $total_products) {
            $end = $total_products;
        }

         for ($i = $start; $i < $end ; $i++) {
            $product_price=$this->Product_pricing_model->order_by('price','asc')->get_by(array('product_id'=>$data['products'][$i]->id, 'active' => 1));
            $images=$this->Images_model->get_by(array('model_name'=>'products','image_type'=>'mainimg','model_id'=>$data['products'][$i]->id));
            $promos=$this->Promo_codes_model->get_by(array('product_id'=>$data['products'][$i]->id,'active'=>'1','end_date > ' => date("Y-m-d")));
            $data['review']=$this->Review_model->get_many_by(array('model_name'=>'products'));

            //$query=('SELECT p.* from product_pricings as p LEFT JOIN vendors as v on p.vendor_id=v.id WHERE p.price =(select max(price) from product_pricings where active=1 and product_id='.$data['products'][$i]->id.') or p.price =(select min(price)  from product_pricings where active=1 and product_id='.$data['products'][$i]->id.') and p.active=1 and v.active=1 and p.product_id='.$data['products'][$i]->id.' GROUP BY p.price');
            $query=('SELECT p.*,GREATEST(max(price), max(retail_price))as max_value,min(NULLIF(retail_price,0)) as min_value ,min(NULLIF(price,0)) as minprice_value from product_pricings as p LEFT JOIN vendors as v on p.vendor_id=v.id  where p.active=1 and v.active=1 and p.product_id=' . $data['products'][$i]->id .' GROUP BY p.price');
            $query1=('SELECT count(p.vendor_id) as v_total from product_pricings p LEFT JOIN vendors v on p.vendor_id=v.id WHERE p.active=1 and v.active=1 and p.product_id='.$data['products'][$i]->id);

            $data['vendors_price'] = $this->db->query($query)->result();
            $data['vendor_total'] = $this->db->query($query1)->result();

            $data['products'][$i]->product_price=$product_price;
            $data['products'][$i]->images=$images;
            $data['products'][$i]->promos=$promos;
            $data['products'][$i]->price=$data['vendors_price'];
            $data['products'][$i]->vendor_total=$data['vendor_total'];
        }
        $data['start']=$start;
        $data['end']=$end;
        $data['pages']=ceil($no_of_pages);

		//$this->mViewData['inner_view'] = $view_file;
		//$this->load->view('/templates/_inc/header',$this->mViewData);
		// $this->load->view('/templates/_inc/header',$data);
		// $this->load->view('/templates/browse/index',$data);
        // $this->load->view('/templates/_inc/nav/browse-cats',$data);
		// debug tools - display view data
		  $roles = unserialize (ROLES_USERS);
		  $roles_vendor = unserialize (ROLES_VENDORS);
		  $roles_admin = unserialize (ROLES_ADMINS);
        if (!(isset($_SESSION['user_id']))){
            $this->load->view('/templates/_inc/header',$data);
            $this->load->view('/templates/browse/index',$data);
            $this->load->view('/templates/_inc/footer', $this->mViewData);
        } else if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'],$roles))) {
            $this->load->view('/templates/_inc/header',$data);
            $this->load->view('/templates/browse/index',$data);
            $this->load->view('/templates/_inc/footer', $this->mViewData);
         } else if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'],$roles_vendor))) {
            redirect('vendor-dashboard');
         } else  if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'],$roles_admin))) {
            redirect('vendorsIn-list');
         } else {
            $this->load->view('/templates/login/index');
          }

            if (ENVIRONMENT==='development' && !empty($debug_config) && !empty($debug_config['view_data']))
            {
                    $this->output->append_output('<hr/>'.print_r($this->mViewData, TRUE));
            }
	}

	// Output JSON string
	protected function render_json($data, $code = 200)
	{
		$this->output
		->set_status_header($code)
		->set_content_type('application/json')
		->set_output(json_encode($data));

		// force output immediately and interrupt other scripts
		global $OUT;
		$OUT->_display();
		exit;
	}

	// Add breadcrumb entry
	// (Link will be disabled when it is the last entry, or URL set as '#')
	protected function push_breadcrumb($name, $url = '#', $append = TRUE)
	{
		$entry = array('name' => $name, 'url' => $url);

		if ($append)
			$this->mBreadcrumb[] = $entry;
		else
			array_unshift($this->mBreadcrumb, $entry);
	}
}

// include base controllers
require APPPATH."core/controllers/Admin_Controller.php";
require APPPATH."core/controllers/Api_Controller.php";
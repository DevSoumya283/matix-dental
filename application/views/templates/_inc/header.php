<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <meta name="viewport" content="width=device-width, height=device-height">
    <title><?php echo $this->config->item('name'); ?></title>
    <meta name="description" content="">

    <!-- Icons -->
    <?=$this->load->view('templates/_inc/icons.php') ?>
    <!-- build:css css/main.min.css -->
    <link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>assets/css/main.css">


    <!-- custom overrides -->
    <style>
    .nav__top{ background-color: #<?php echo $this->config->item('bg-color'); ?> !important; }
    .navbar{ background-color: #<?php echo $this->config->item('bg-color'); ?> !important; }
    .btn--primary{ background-color: #<?php echo $this->config->item('btn-color-2'); ?> !important; }
    .btn.is--pos{ background-color: #<?php echo $this->config->item('btn-color-1'); ?> !important; }
</style>
<!-- endbuild -->

<!-- Libraries -->

<link href="<?php echo base_url(); ?>lib/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url(); ?>lib/bootstrap-timepicker/bootstrap-timepicker.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url(); ?>lib/animate-css/animate.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url(); ?>lib/jquery-flexslider2/flexslider.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url(); ?>assets/css/sortable-theme-minimal.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>assets/css/owl.carousel.min.css">
<link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>assets/css/owl.theme.default.min.css">
<link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>assets/css/bootstrap.min.css">
<link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>assets/css/custom.css">
<link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>assets/css/responsive.css">
<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/owl.carousel.min.js"></script>
</head>

<body>




    <div id="pageLoad" style="width:100%; height:100%; background-color:#fff;position:fixed;top:0;left:0;z-index:99999;"></div>

    <header class="header header__main d-none d-sm-block" id="top">
        <?php
        $users = unserialize(ROLES_USERS);
        $tier_1_2 = unserialize(ROLES_TIER1_2);
        ?>
        <!-- Top Navigation -->
        <!-- NOTE: This block is only displayed if a user is logged in -->
        <nav class="nav nav__top" >
            <div class="wrapper wrapper--fixed">
                <div class="wrapper__inner">
                    <!-- User Info -->
                    <ul class="list list--inline list--reversed list--divided fontSize--s fontWeight--2">
                        <?php $topname="";  if (isset($_SESSION["user_id"]) && isset($_SESSION['role_id'])) { ?>

                        <?php if (isset($_SESSION["role_id"]) && ($_SESSION["role_id"] == "11")) { ?>
                        <li class="item">Welcome, <a href="<?php echo base_url(); ?>vendor-dashboard"><?php echo $_SESSION['user_name']; ?></a></li>
                        <?php } else {
                            $user_sql="select * from users where id=".$_SESSION["user_id"];
                            $user_data = $this->db->query($user_sql)->result();
                            foreach ($user_data as $value) {
                                $user_title = $value->salutation;
                            }

                            $topname="Welcome,".$_SESSION['user_name'];
                            if($user_title!=null && $user_title!="")
                            {
                              $topname.= ", " . $user_title;
                          }
                          ?>
                          <li class="item">Welcome, <?php echo '<a class="link" href="/dashboard">'.$_SESSION['user_name'].'</a>'; ?> <?php if($user_title!=null && $user_title!=""){ echo ", " . $user_title; } ?></li>
                          <?php } ?>
                          <!-- <li class="item"><a href="<?php // echo base_url('inbox');              ?>">Inbox (0)</a></li> -->
                          <?php
                      } else {
                        ?>
                        <li class="item"><a href="<?php echo base_url('signin'); ?>">Log In/Sign Up</a></li>
                        <?php }
                        ?>

                        <!-- templates/inbox -->
                    </ul>
                </div>
                <?php if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $users))) { ?>
                <div class="wrapper__inner align--right">
                    <!-- Location Settings -->
                    <ul class="list list--inline list--reversed list--divided fontSize--s fontWeight--2">
                        <li class="item">
                            <svg class="icon icon--location" style="transform:translateY(6px);"><use xlink:href="#icon-location"></use></svg>
                            <div class="select select--text is--reversed">
                                <label class="label">Shopping for:</label>
                                <select aria-label="Select a Sorting Option" class="view_locations">
                                    <option value="all" selected>All Locations</option>
                                    <?php
                                    foreach ($user_locations as $key) {
                                        if (isset($_SESSION['location_id'])) {
                                            ?>
                                            <option  <?php if (isset($_SESSION['location_id']) && $_SESSION['location_id'] == $key->id) echo 'selected'; ?> value="<?php echo $key->id; ?>"><?php echo $key->nickname; ?></option>
                                            <?php } else if (isset($key) && $key != null && isset($key->id) && isset($key->nickname)) { ?>
                                            <option  value="<?php echo $key->id; ?>"><?php echo $key->nickname; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <a class="link link--icon margin--xs no--margin-tb no--margin-r" style="transform:translateY(-2px);">
                                <svg class="icon icon--help modal--toggle" data-target="#shoppingModeModal"><use xlink:href="#icon-help"></use></svg>
                            </a>
                        </li>
                    </ul>
                </div>
                <?php } else { ?>
                <!-- <a class="link" href="<?php echo base_url('user-logOut'); ?>">Sign Out</a>
                -->                    <?php } ?>
            </div>
        </nav>
        <!-- /Top Navigation -->

        <!-- Main Navigation -->
        <nav class="nav nav__main">
            <div class="wrapper wrapper--fixed">
                <div class="row">
                    <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                        <a class="logo logo__main" href="<?php echo base_url('home'); ?>"><!-- templates/browse -->
                            <img src="<?php echo base_url() . 'assets/img/logos/' . $this->config->item('logo'); ?>" style="width: 140px;margin-top: 10px;" alt=""/>
                        </a>
                    </div>
                    <div class="col-lg-6 col-md-8 col-sm-8 col-xs-12">
                        <div class="wrapper">
                            <div class="wrapper__inner" style="width:104px;">
                                <a href="#" class="link link--dropdown link--toggle fontSize--l view_category" data-target="#browseDropdown">
                                    Browse
                                </a>
                            </div>
                            <div class="wrapper__inner">
                                <form action="<?php echo base_url('search'); ?>" method="GET" onsubmit="return q.value">
                                    <div class="input__group input__group--inline">
                                        <input id="q" class="input input__text" type="search"  placeholder="Search by product, vendor, manufacturer, etc…" name="q" autocomplete="off" required>
                                        <label for="q" class="label">
                                            <svg class="icon icon--search textColor--gray"><use xlink:href="#icon-search"></use></svg>
                                        </label>
                                    </div>
                                </form>
                                <div id="search-results" class="col-md-10">
                                    <!-- <div id="search-results-title">Search Results</div> -->
                                    <div id="searchResultsList"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--                END OF LOGO AND SERACH-->


                    <!--            START OF LOGGED IN USER MENU-->
                    <?php if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $users))) { ?>
                    <div class="col col--3-of-12 col--am align--right">
                        <div id="accountDropdown" class="link link--dropdown fontSize--l pull--up-xxs">
                            My Account
                            <div class="popover fontSize--m" style="min-width:176px;">
                                <div class="popover__inner">
                                    <ul class="list">
                                        <li>
                                            <a class="link" href="<?php echo base_url('dashboard'); ?>">Dashboard</a>
                                            <!-- templates/account' -->
                                        </li>
                                        <?php if(User_model::can($_SESSION['user_permissions'], 'view-my-orders')){ ?>
                                            <li>
                                                <a class="link" href="<?php echo base_url('history'); ?>">My Orders</a>
                                                <!-- 'templates/account/orders/history' -->
                                            </li>
                                            <?php } ?>
                                            <?php if(User_model::can($_SESSION['user_permissions'], 'view-organization')){ ?>
                                                <li>
                                                    <a class="link" href="<?php echo base_url('company'); ?>">Organization</a>
                                                    <!-- 'templates/account/company' -->
                                                </li>
                                                <?php } ?>
                                                <?php if(User_model::can($_SESSION['user_permissions'], 'view-reports')){ ?>
                                                    <li>
                                                        <a class="link" href="<?php echo base_url('snapshot'); ?>">Reports</a>
                                                        <!-- 'templates/account/reports/snapshot' -->
                                                    </li>
                                                    <?php } ?>
                                                    <?php if(User_model::can($_SESSION['user_permissions'], 'view-users')){ ?>
                                                        <li>
                                                            <a class="link" href="<?php echo base_url('Manage-Users'); ?>">Users</a>
                                                            <!-- 'templates/account/users' -->
                                                        </li>
                                                        <?php } ?>
                                                        <?php if(User_model::can($_SESSION['user_permissions'], 'view-locations')){ ?>
                                                            <li>
                                                                <a class="link" href="<?php echo base_url('locations'); ?>">Locations</a>
                                                                <!--  templates/account/locations -->
                                                            </li>
                                                            <?php } ?>
                                                            <?php if(User_model::can($_SESSION['user_permissions'], 'view-inventory')){ ?>
                                                                <li>
                                                                    <a class="link" href="<?php echo base_url('manage-inventory'); ?>">Inventory</a>
                                                                    <!-- templates/account/inventory -->
                                                                </li>
                                                                <?php } ?>
                                                                <li>
                                                                    <a class="link" href="<?php echo base_url('shopping-lists'); ?>">Shopping Lists</a>
                                                                </li>

                                                            </ul>
                                                        </div>
                                                        <div class="popover__bottom fontSize--s">
                                                            Not <?php echo $_SESSION['user_name']; ?>?
                                                            <a class="link" href="<?php echo base_url('user-logOut'); ?>">Sign Out</a>
                                                            <!-- templates/login -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="shopButtons">
                                                    <!-- Multi-Cart -->
                                                    <?php
                                                    $cartData = $_SESSION['cart_contents'];
                                                    $session_user_id = $_SESSION['user_id'];
                                                    if(!empty($cartData)){
                                                        $has_cart_badge = true;
                                                    }
                                                    $query = "SELECT * FROM organization_groups where user_id=$session_user_id";
                                                    $oragan_result = $this->db->query($query)->result();
                                                    foreach ($oragan_result as $value) {
                                                        $organization_id = $value->organization_id;
                                                    }
                            /*
                            $has_cart_badge = false;
                            if (isset($_SESSION['user_id'])) {
                                $cart_query = "SELECT * FROM user_autosaves where organization_id = $organization_id";
                                $cart_result = $this->db->query($cart_query)->result();
                                if ($cart_result != null && count($cart_result) > 0) {
                                    for ($x = 0; $x < count($cart_result); $x++) {
                                        if ($cart_result[$x]->cart != "[]") {
                                            $has_cart_badge = true;
                                        }
                                    }
                                }
                            }*/
                            ?>
                            <?php if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $tier_1_2))) { ?>

                            <div class="btn btn--m btn--tertiary btn--icon link--dropdown <?php echo ($has_cart_badge) ? "has--badge" : "" ?> badge--s view-cart">
                                <svg class="icon icon--cart-m" style="height: 100%"><use xlink:href="#icon-cart-m"></use></svg>
                                <div class="popover popover__overflow fontSize--m">
                                    <div class="popover__inner">
                                        <ul class="list list--spaced user-cart"></ul>

                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            <!-- /Multi-Cart -->
                            <?php
                            $session_user_id = $_SESSION['user_id'];
                            $has_rl_badge = false;
                            if (isset($_SESSION['user_id'])) {
                                $request_list_query = "SELECT * FROM request_lists where user_id=$organization_id AND location_id != 0 AND product_id != 0 ";
                                $request_list_result = $this->db->query($request_list_query)->result();
                                if ($request_list_result != null && count($request_list_result) > 0) {
                                    $has_rl_badge = true;
                                }
                            }
                            ?>
                            <!-- Multi-List -->
                            <?php if(!empty($user_locations)){ ?>
                            <div class="btn btn--m btn--tertiary btn--icon link--dropdown <?php echo ($has_rl_badge) ? "has--badge" : "" ?> badge--s is--link view_lists" data-target="<?php echo base_url('request-lists'); ?>"><!-- templates/account/requests -->
                                <svg class="icon icon--list-m" style="height: 100%"><use xlink:href="#icon-list-m"></use></svg>
                            </div>
                          <?php } ?>
                            <!-- /Multi-List -->

                        </div>
                    </div>
                    <?php } ?>
                    <!--                END OF LOGGED IN USER MENU-->

                </div>
            </div>
        </nav>
        <span class="success">
            <?php if ($this->session->flashdata('success') != "") { ?>
            <div class="banner is--pos">
                <span class="banner__text">
                    <?php echo $this->session->flashdata('success') ?>
                </span>
                <a class="link link--icon dismiss--banner"><svg class="icon icon--x"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-x"></use></svg></a>
            </div>
            <?php } ?>
        </span>
        <span class="nolicence">
            <?php if ($this->session->flashdata('error') != "") { ?>
            <div class="banner is--neg">
                <span class="banner__text">
                    <?php echo $this->session->flashdata('error') ?>
                </span>
                <a class="link link--icon dismiss--banner"><svg class="icon icon--x"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-x"></use></svg></a>
            </div>
            <?php } ?>
        </span>
        <!--START OF PUBLIC MENU-->
        <div id="browseDropdown">

            <div class="multi__menu">
                <!--            MAIN MENU ITEMS-->
                <div class="cat__types">
                    <ul class="list list--l">
                        <li class="item">
                            <a class="multi__menu--toggle link view_category is--active" data-target="#menuCategory">Category</a>
                        </li>
                        <li class="item">
                            <a class="multi__menu--toggle link view_procedure" data-target="#menuProcedure">Procedure</a>
                        </li>
                        <?php if(empty($this->config->item( 'whitelabel' ))){ ?>
                        <li class="item">
                            <a class="multi__menu--toggle link view_vendor" data-target="#menuVendor">Vendor</a>
                        </li>
                        <?php } ?>
                        <li class="item">
                            <a class="multi__menu--toggle link view_mfc" data-target="#menuManufacturer">Manufacturer</a>
                        </li>
                        <li class="item">
                            <a class="multi__menu--toggle link view-pro-list" data-target="#menuProductLists">Product Lists</a>
                        </li>
                    </ul>
                </div>

                <!--            WRITING SUB MENU FROM AJAX MOUSE HOVER-->
                <div class="cat__items multi__menu--states">
                    <div id="menuCategory" class="multi__menu--state row is--active">
                        <!-- <?php include(INCLUDE_PATH . '/_inc/nav/browse-cats.php'); ?> -->
                        <?=$this->load->view('templates/_inc/nav/browse-cats.php') ?>
                    </div>
                    <div id="menuProcedure" class="multi__menu--state row">
                        <!-- <?php include(INCLUDE_PATH . '/_inc/nav/browse-procedure.php'); ?> -->
                        <?=$this->load->view('templates/_inc/nav/browse-procedure.php') ?>
                    </div>
                    <?php if(empty($this->config->item( 'whitelabel' ))){ ?>
                    <div id="menuVendor" class="multi__menu--state row">
                        <!-- <?php include(INCLUDE_PATH . '/_inc/nav/browse_vendors.php'); ?> -->
                        <?=$this->load->view('templates/_inc/nav/browse_vendors.php') ?>

                    </div>
                    <?php } ?>
                    <div id="menuManufacturer" class="multi__menu--state">
                        <!-- <?php include(INCLUDE_PATH . '/_inc/nav/browse_manufacturer.php'); ?> -->
                        <?=$this->load->view('templates/_inc/nav/browse_manufacturer.php') ?>

                    </div>
                    <div id="menuProductLists" class="multi__menu--state">
                        <!-- <?php include(INCLUDE_PATH . '/_inc/nav/browse_product_list.php'); ?> -->
                        <?=$this->load->view('templates/_inc/nav/browse_product_list.php') ?>
                    </div>
                </div>
                <!--            END OF SUB MENU-->
            </div>

        </div>
        <!--END OF PUBLIC MENU-->
        <!-- /Main Navigation -->
    </header>

<?php if($this->uri->segment(1) == 'home' || $this->uri->segment(1) == 'browse' || $this->uri->segment(1) == 'search'){ ?>
 <div class="container">
    <br>
    <div class=" no--margin no--pad" style="max-width: 150px">
       <div class="tab__group" data-target="#categoryTree">
            <label class="tab cat_view_header" id="classic" value="is--classic-view" parent="1">
                <input type="radio" name="categoryView" id="classicTitle">
                <span>Classic </span>
            </label>
            <label class="tab cat_view_header" id="dentist" value="is--dentist-view" parent="2">
                <input type="radio" name="categoryView" id="dentistTitle">
                <span>Dentist  </span>
            </label>
        </div>
    </div> <!-- end of cat -->
</div>
<!-- end of search form -->
<?php } ?>

    </div>

    <!-- MOBILE MENEU -->
    <div class="d-block d-sm-none">
         <div class="container-fluid ">

            <div class="row public-menurow">
                <div class="col no-padding">
                    <a class="" href="<?php echo base_url('home'); ?>">
                        <img class="logo-img" src="<?php echo base_url() . 'assets/img/logos/' . $this->config->item('logo'); ?>" alt=""/>
                    </a>
                </div>
                <div class="col mt-2">
                    <div class="login float-right d-none"><a href="<?=base_url('signin'); ?>">Login</a></div>
                </div>
                <div class="col-xs-2">
                    <nav class="navbar navbar-expand-lg navbar-light displayinline no-padding">
                        <button class="navbar-toggler publicnav" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                    </nav>
                    <?php if (!isset($_SESSION['user_id'])) {?>
                        <a class="textColor--white displayinline usericon" href="<?=base_url('signin'); ?>"><i class="fa-user fas fontSize--l textColor--white"></i></a>
                    <?php } ?>
                     <?php if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $users))) { ?>
                      <a class="textColor--white displayinline accounticon" href="#"><i class="fa-user fas fontSize--l textColor--white"></i></a>
                       <?php } ?>
                </div>
            </div>

        </div><!-- end of container fluid -->

        <?php  $marginclass=""; if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $users))) {
            $marginclass="mt-20";
            ?>
             <div class="container-fluid mb-3">
                <div class="row">
                    <div class="col-xs-10 bg-skyblue" style="width: 72%">
                           <div class="mb4 padding--xs">
                                <a class="textColor--white " href="<?=base_url('dashboard'); ?>"><?=$topname?></a>
                                <br>
                            </div>
                    </div>
                     <div class="col-xs-2">
                          <div id="shopButtons" class="ml-3">
                <!-- Multi-Cart -->
                <?php if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $tier_1_2))) { ?>
                    <div class="btn btn--m btn--tertiary btn--icon link--dropdown <?php echo ($has_cart_badge) ? "has--badge" : "" ?> badge--s view-cart">
                        <svg class="icon icon--cart-m" style="height: 100%"><use xlink:href="#icon-cart-m"></use></svg>
                        <div class="popover popover__overflow fontSize--m">
                            <div class="popover__inner">
                                <ul class="list list--spaced user-cart"></ul>

                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="btn btn--m btn--tertiary btn--icon link--dropdown <?php echo ($has_rl_badge) ? "has--badge" : "" ?> badge--s is--link view_lists" data-target="<?php echo base_url('request-lists'); ?>"><!-- templates/account/requests -->
                    <svg class="icon icon--list-m" style="height: 100%"><use xlink:href="#icon-list-m"></use></svg>
                </div>
            </div>
        </div>
    </div>

 </div>
<?php } ?>
<!-- row which loads public menus -->
  <div class="container-fluid" style="margin-top: 10px">
 <div class="row navbar-holder">
        <div class="col-sm-12 " style="padding: 0px;">
            <!--    public mobile menu-->
            <nav class="navbar navbar-expand-lg navbar-light public-mobnav">
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="view-category" href="#"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Category
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="view-procedure" href="#"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Procedure
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="view-vendor" href="#" data-toggle="dropdown">
                                Vendor
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="view-mfc" href="#"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Manufacturer
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="view-pro-list" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Product List
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="browse-dropdown-loader">
            Loading ...
        </div>
        <div class="browse-dropdown">
        </div>
        <div class="col-xs-8 menu-div d-none">
            <div class="row alpha-row d-none">
                <ul class="alpha">
                    <li class="alphas 0-9">0-9</li>
                    <?php
                    $alphas = range('A', 'Z');
                    foreach ($alphas as $key => $value):
                        ?>
                        <li class="alphas <?=$value?>"><?=$value?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="row menu-row">
                <div class="col-xs-4">
                    <ul class="menulist">
                        <!-- <li>Loading...</li> -->
                    </ul>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- end of pubic menu -->

    <!-- logged in user menu -->
<?php if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $users))) { ?>
<div class="container"  style="margin-top: -10px">
    <?php if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $users))) { ?>
        <nav class="navbar navbar-expand-lg navbar-light bg-light" style="">
            <div class="collapse navbar-collapse" id="navbarNavDropdownAccount">
                <ul class="navbar-nav">
                    <li class="nav-item nav-user">
                        <a class="nav-link" href="<?= base_url('dashboard'); ?>">Dashboard</a>
                    </li>
                      <li class="nav-item nav-user">
                        <?php if(User_model::can($_SESSION['user_permissions'], 'view-my-orders')){ ?>
                        <a class="nav-link" href="<?= base_url('history'); ?>">My Orders</a>
                        <?php } ?>
                    </li>
                        <?php if(User_model::can($_SESSION['user_permissions'], 'view-organization')){ ?>
                     <li class="nav-item nav-user">
                        <a class="nav-link" href="<?= base_url('history'); ?>">My Organization</a>
                    </li>
                        <?php } ?>
                        <?php if(User_model::can($_SESSION['user_permissions'], 'view-reports')){ ?>
                     <li class="nav-item nav-user">
                        <a class="nav-link" href="<?= base_url('snapshot'); ?>">My Reports</a>
                    </li>
                        <?php } ?>
                        <?php if(User_model::can($_SESSION['user_permissions'], 'view-users')){ ?>
                     <li class="nav-item nav-user">
                        <a class="nav-link" href="<?= base_url('Manage-Users'); ?>">Users</a>
                    </li>
                        <?php } ?>
                        <?php if(User_model::can($_SESSION['user_permissions'], 'view-locations')){ ?>
                     <li class="nav-item nav-user">
                        <a class="nav-link" href="<?= base_url('locations'); ?>">Locations</a>
                    </li>
                        <?php } ?>
                        <?php if(User_model::can($_SESSION['user_permissions'], 'view-inventory')){ ?>
                       <li class="nav-item nav-user">
                        <a class="nav-link" href="<?= base_url('snapshot'); ?>">Inventory</a>
                         </li>
                        <?php } ?>
                          <li class="nav-item nav-user">
                        <a class="nav-link" href="<?= base_url('shopping-lists'); ?>">Shopping List</a>
                         </li>
                          <li class="nav-item nav-user">
                        <a class="nav-link" href="<?= base_url('signin'); ?>">Signout</a>
                         </li>

                </ul>
            </div>
        </nav>
    <?php } ?>
</div>
 <?php } ?>
<!-- end -->



 <div class="container">
    <div class="col-xs-12">
        <form action="<?php echo base_url('search'); ?>" method="GET" onsubmit="return q.value">
            <div class="input__group input__group--inline">
                <input id="q" class="input input__text" type="search"  placeholder="Search by product, vendor, manufacturer, etc…" name="q" required>
                <label for="q" class="label">
                    <svg class="icon icon--search textColor--gray"><use xlink:href="#icon-search"></use></svg>
                </label>
            </div>
        </form>
    </div>
    <br>

    </div>
    <!-- end of search form -->
</div>



     <button class="navbar-toggler loggednav d-none" type="button" data-toggle="collapse" data-target="#navbarNavDropdownAccount" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

    <script>
          $(document).on('click','.nav-link',function(){
        $('.menu-div').removeClass('d-none');
    });
    $(document).on('click','.publicnav',function(){
        var toggle=$(this).attr('aria-expanded');
                        if(toggle=="true"){
                    $('.menu-row').html('');
                        }
                         var toggle=$('.loggednav').attr('aria-expanded');
                        if(toggle=="true"){
                        $('.loggednav ').trigger('click');
                        }
    });

           $(document).on('click','.accounticon',function(){
        // $('.public-mobnav .navbar-collapse').addClass('show');
        $('.loggednav ').trigger('click');
              var toggle=$('.publicnav').attr('aria-expanded');
                        if(toggle=="true"){
                        $('.publicnav ').trigger('click');
                        }


              });
           $(document).on('click','.usericon',function(){
            if($(".login").css("margin-right") == "50px")
            {
            $(".login").animate({"margin-right": '-=50px'});
            $('.login').addClass('d-none');
            }
            else{
          //  $('.login').removeClass('d-none');
           // $(".login").animate({"margin-right": '+=50px'});
            }
  });
    </script>

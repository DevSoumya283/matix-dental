<!-- Content Section -->
<div class="overlay__wrapper ">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>
    <!-- Breadcrumbs Bar -->
    <div class="bar padding--xs bg--lightest-gray ">
        <div class="wrapper wrapper--fixed">
            <ul class="list list--inline list--breadcrumbs">
                <li class="item">
                    <a class="link" href="<?php echo base_url('dashboard'); ?>">Account</a>
                </li>
                <li class="item is--active">
                    Manage Orders
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breadcrumbs Bar -->
    <!-- Main Content -->
    <section class="content__wrapper wrapper--fixed has--sidebar-l sidebar--no-fill mobile-center">
        <div class="content__main">
            <div class="row row--full-height">
                <!-- Sidebar -->
                <div class="sidebar col-md-3 col-xs-12 bg--white">
                    <!-- Page Info -->
                    <div class="sidebar__group">
                        <h3>Manage Orders</h3>
                        <p class="no--margin-tb"></p>
                    </div>
                    <!-- /Page Info -->
                    <!-- Tabs -->
                    <div class="sidebar__group">
                        <div class="tab__group is--vertical" data-target="#ordersContent">
                            <label class="tab state--toggle" value="history">
                                <input type="radio" name="ordersTabs" >
                                <span><a class="link order_history">Order History</a></span>
                            </label>
                            <label class="tab state--toggle" value="recurring">
                                <input type="radio" name="ordersTabs">
                                <span><a class="link recurring_link">Recurring Orders</a></span>
                            </label>
                            <?php if ($_SESSION['role_id'] != '6') { ?>
                                <label class="tab state--toggle" value="feedback">
                                    <input type="radio" name="ordersTabs">
                                    <span><a class="link vendor_rate_link">Rate Vendors</a></span>
                                </label>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- /Tabs -->
                </div>
                <!-- /Sidebar -->
                <!-- Content -->
                <?php
                $tier_1_2 = unserialize(ROLES_TIER1_2);
                $tier_1_2_3a = unserialize(ROLES_TIER1_2_3A);
                ?>
                <div id="ordersContent" class="content col-md-9 col-xs-12">
                    <?php if($_SERVER['REQUEST_URI'] == '/history'){
                        $this->load->view('templates/account/orders/partials/order_tab.php');
                        // include(INCLUDE_PATH . '/account/orders/partials/order_tab.php');
                    } else if($_SERVER['REQUEST_URI'] == '/returns'){
                        $this->load->view('templates/account/orders/partials/returns_tab.php');
                        // include(INCLUDE_PATH . '/account/orders/partials/returns_tab.php');
                    } else if($_SERVER['REQUEST_URI'] == '/recurring'){
                        $this->load->view('templates/account/orders/partials/recurring_tab.php');
                        // include(INCLUDE_PATH . '/account/orders/partials/recurring_tab.php');
                    } else if($_SERVER['REQUEST_URI'] == '/feedback'){
                        $this->load->view('templates/account/orders/partials/vendors_tab.php');
                        // include(INCLUDE_PATH . '/account/orders/partials/vendors_tab.php');
                    } ?>
                </div>
            </div>
    </section>
    <!-- /Main Content -->
</div>
<!-- /Content Section -->
<!-- Modals -->
<?php $this->load->view('templates/_inc/shared/modals/orders-vendor-review.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/order-cancellation.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/reorder.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/make-recurring.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/cancel-recurring.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/add-new-license.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/pending-order-cencellation.php'); ?>




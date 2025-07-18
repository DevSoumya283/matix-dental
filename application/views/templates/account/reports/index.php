
<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <!-- Breadcrumbs Bar -->
    <div class="bar padding--xs bg--lightest-gray">
        <div class="wrapper wrapper--fixed">
            <ul class="list list--inline list--breadcrumbs">
                <li class="item">
                    <a class="link" href="<?php echo base_url('dashboard'); ?>">Account</a>
                </li>
                <li class="item is--active">
                    Reports
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breadcrumbs Bar -->
    <!-- Main Content -->
    <section class="container wrapper--fixed has--sidebar-l sidebar--no-fill bg--lightest-gray">
        <div class="content__main">
            <div class="row row--full-height">
                <!-- Sidebar -->
                <div class="sidebar col-md-2 col-xs-12 mobile-center padding--l no--pad-l">
                    <!-- Request List Info -->
                    <div class="sidebar__group">
                        <h3>Reports</h3>
                    </div>
                    <!-- /Request List Info -->

                    <!-- Location Tabs -->
                    <div class="sidebar__group">
                        <div class="tab__group is--vertical" data-target="#reportsContent">
                            <label class="tab state--toggle" value="snapshot">
                                <input type="radio" name="reportTabs" checked>
                                <span><a class="link">Account</a></span>
                            </label>
                            <label class="tab state--toggle" value="orders">
                                <input type="radio" name="reportTabs">
                                <span><a class="link">Orders</a></span>
                            </label>
                            <label class="tab state--toggle" value="purchases">
                                <input type="radio" name="reportTabs">
                                <span><a class="link">Purchases</a></span>
                            </label>
                            <label class="tab state--toggle" value="tax">
                                <input type="radio" name="reportTabs">
                                <span><a class="link">Tax</a></span>
                            </label>
                        </div>
                    </div>
                    <!-- /Location Tabs -->
                </div>
                <!-- /Sidebar -->
                <!-- Content -->
                <div id="reportsContent" class="content col-md-10 col-xs-12 is--account">
                    <!-- ACCOUNT REPORT -->
                    <?php
                     $this->load->view('templates/account/reports/_inc/account.php');
                     $this->load->view('templates/account/reports/_inc/orders.php');
                     $this->load->view('templates/account/reports/_inc/product.php');
                     $this->load->view('templates/account/reports/_inc/tax.php');
                     ?>
                </div>
                <!-- /Content -->
            </div>
        </div>
    </section>
    <!-- /Main Content -->

</div>
<!-- /Content Section -->

<!-- Modals -->
     <?php $this->load->view('templates/_inc/shared/modals/products-comparison.php'); ?>

<!-- Charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.min.js"></script>




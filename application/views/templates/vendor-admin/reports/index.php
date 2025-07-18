<?php $this->load->view('templates/_inc/header-vendor.php'); ?>

<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <section class="content__wrapper has--sidebar-l">
        <div class="content__main">
            <div class="row row--full-height">

                <!-- Sidebar -->
                <div class="sidebar col col--2-of-12" style="padding: 12px;">
                    <?php $this->load->view('templates/vendor-admin/_inc/nav.php'); ?>
                </div>
                <!-- /Sidebar -->

                <!-- Content Area -->
                <div class="content col col--9-of-12 col--push-1-of-12">
                    <div class="wrapper">
                        <div class="wrapper__inner align--center">
                            <h2>Generate a Report:</h2>
                            <div class="well">
                                <ul class="list list--inline list--divided">
                                    <li class="item">
                                        <a class="link" href="<?php echo base_url(); ?>order-reports-Vendor">Orders Report</a>
                                    </li>
                                    <li class="item">
                                        <a class="link" href="<?php echo base_url(); ?>order-reports-Sales">Sales Report</a>
                                    </li>
                                    <li class="item">
                                        <a class="link" href="<?php echo base_url(); ?>order-reports-customer">Customer Report</a>
                                    </li>
                                    <li class="item">
                                        <a class="link" href="<?php echo base_url(); ?>order-Reports-Shipping">Shipping Report</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Content Area -->

            </div>
        </div>
    </section>
</div>
<!-- /Content Section -->

<?php
$this->load->view('templates/_inc/footer-vendor.php');?>

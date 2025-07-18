<?php include(INCLUDE_PATH . '/_inc/header-vendor.php'); ?>

<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <section class="content__wrapper has--sidebar-l">
        <div class="content__main">
            <div class="row row--full-height">

                <!-- Sidebar -->
                <div class="sidebar col col--2-of-12" style="padding: 12px !important;">
                    <?php //include(INCLUDE_PATH . '/vendor-admin/_inc/nav.php'); ?>
                    <?php $this->load->view('templates/vendor-admin/_inc/nav.php'); ?>
                </div>
                <!-- /Sidebar -->

                <!-- Content Area -->
                <div class="content col col--9-of-12 col--push-1-of-12">
                    <div class="wrapper">
                        <div class="wrapper__inner">
                            <div class="row">
                                <div class="col col--4-of-8">
                                    <div class="well card align--center">
                                        <span class="fontSize--l textColor--dark-gray">Product Promotions</span>
                                        <hr>
                                        <ul class="list list--inline list--divided">
                                            <li class="item">
                                                <h5>Active</h5>
                                                <?php echo($vendor_promoProduct_active != null) ? $vendor_promoProduct_active : "0"; ?>
                                            </li>
                                            <li class="item">
                                                <h5>Expired</h5>
                                                <?php echo ($vendor_promoProduct_Inactive != null) ? $vendor_promoProduct_Inactive : "0"; ?>
                                            </li>
                                            <li class="item">
                                                <a class="btn btn--primary btn--m is--link" href="<?php echo base_url(); ?>view-promo-product">View/Create</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col col--4-of-8">
                                    <div class="well card align--center">
                                        <span class="fontSize--l textColor--dark-gray">Promo Codes</span>
                                        <hr>
                                        <ul class="list list--inline list--divided">
                                            <li class="item">
                                                <h5>Active</h5>
                                                <?php echo($vendor_promoCodeActive != null) ? $vendor_promoCodeActive : "0"; ?>
                                            </li>
                                            <li class="item">
                                                <h5>Expired</h5>
                                                <?php echo($vendor_promoCodeInactive != null) ? $vendor_promoCodeInactive : "0"; ?>
                                            </li>
                                            <li class="item">
                                                <a class="btn btn--primary btn--m is--link" href="<?php echo base_url(); ?>view-promo-code">View/Create</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
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

<?php //include(INCLUDE_PATH . '/_inc/footer-vendor.php'); ?>
<?php //$this->load->view('templates/_inc/footer-vendor.php'); ?>

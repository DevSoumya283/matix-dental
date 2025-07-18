<?php include(INCLUDE_PATH . '/_inc/header-vendor.php'); ?>

<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <!-- Breadcrumbs Bar -->
    <div class="bar padding--xs bg--lightest-gray">
        <div class="wrapper wrapper--fixed">
            <ul class="list list--inline list--breadcrumbs">
                <li class="item">
                    <a class="link" href="<?php echo base_url(); ?>vendorReturn-orders">Returns</a>
                </li>
                <li class="item is--active">
                    Return <?php echo $return_order->id; ?>
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breadcrumbs Bar -->

    <section class="content__wrapper has--sidebar-l bg--lightest-gray">
        <div class="content__main">
            <div class="row row--full-height">

                <!-- Sidebar -->
                <div class="sidebar col col--2-of-12">
                    <?php include(INCLUDE_PATH . '/vendor-admin/_inc/nav.php'); ?>
                </div>
                <!-- /Sidebar -->

                <!-- Content Area -->
                <div class="content col col--9-of-12 col--push-1-of-12">

                    <div class="align--center">
                        <h2>Return Request Accepted</h2>
                        <p>Instructions have been sent to the customer to ship the items back to you.</p>
                    </div>

                    <!-- Accepted Return Request -->
                    <hr>
                    <div class="order well card is--pos">
                        <div class="heading__group wrapper border--dashed padding--s no--pad-lr no--pad-t">
                            <div class="wrapper__inner">
                                <h4 class="textColor--darkest-gray">Return <?php echo $return_order->id; ?></h4>
                            </div>
                            <div class="wrapper__inner align--right">
                                <ul class="list list--inline list--divided fontWeight--2 fontSize--s">
                                    <li class="item">
                                        <a class="link">Print Itemized List</a>
                                    </li>
                                    <li class="item">
                                        <button class="btn btn--s btn--primary is--link" data-target="<?php echo base_url(); ?>return-requested-OrderDetail?return_id=<?php echo $return_order->id; ?>">View Return</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <?php if ($customer_details != null) { ?>
                            <?php foreach ($customer_details as $details) { ?>
                                <div class="row">
                                    <div class="order__info col col--10-of-12 col--am">
                                        <ul class="list list--inline list--stats list--divided">
                                            <li class="item item--stat stat-s">
                                                <div class="text__group">
                                                    <span class="line--main"><?php echo $details->address1; ?>, <?php echo $details->address2; ?></span>
                                                    <span class="line--sub"><?php echo $details->state; ?>, <?php echo $details->zip; ?></span>
                                                </div>
                                            </li>
                                            <li class="item item--stat stat-s">
                                                <div class="text__group">
                                                    <span class="line--main"><?php echo $details->first_name; ?></span>
                                                    <span class="line--sub">Customer Name</span>
                                                </div>
                                            </li>
                                            <li class="item item--stat stat-s">
                                                <div class="text__group">
                                                    <span class="line--main"><?php echo $details->organization_name; ?></span>
                                                    <span class="line--sub">Company</span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="order__btn col col--2-of-12 col--am align--right">
                                        <ul class="list list--inline list--stats">
                                            <li class="item item--stat">
                                                <div class="text__group">
                                                    <span class="line--main font"><?php echo "$" . $details->refund_amount; ?></span>
                                                    <span class="line--sub">Requested Refund</span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            <?php }
                        } ?>
                    </div>
                    <!-- /Accepted Return Request -->

                </div>
                <!-- /Content Area -->

            </div>
        </div>
    </section>
</div>
<!-- /Content Section -->

<?php
include(INCLUDE_PATH . '/_inc/footer-vendor.php');

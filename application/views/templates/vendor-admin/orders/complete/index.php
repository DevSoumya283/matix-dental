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

                    <!-- /New & In-Progress Orders -->
                    <div class="heading__group border--dashed">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <h3>Completed Orders</h3>
                            </div>
                            <div class="wrapper__inner">
                                <form method="post" action="<?php echo base_url(); ?>vendor-orders-completed">
                                    <div class="input__group input__group--inline">
                                        <input id="site-search" class="input input__text" type="search" value="" placeholder="Search by number, date, customer, etc..." name="search" required>
                                        <label for="site-search" class="label">
                                            <svg class="icon icon--search textColor--gray"><use xlink:href="#icon-search"></use></svg>
                                        </label>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div style="overflow: hidden; overflow-x: scroll;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>
                                    Order
                                </th>
                                <th>
                                    Customer
                                </th>
                                <th>
                                    Opened
                                </th>
                                <th>
                                    Completed
                                </th>
                                <th>
                                    Order Total
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Single Return -->
                            <?php if ($completed_orders != null) { ?>
                                <?php foreach ($completed_orders as $orders) { ?>
                                    <tr>
                                        <td class="fontWeight--2">
                                            <a class="link" href="<?php echo base_url(); ?>orders-shipped?order_id=<?php echo $orders->id; ?>"><?php echo $orders->id; ?></a>
                                        </td>
                                        <td>
                                            <?php echo $orders->first_name; ?>
                                        </td>
                                        <td>
                                            <?php echo date('M d, Y', strtotime($orders->created_at)); ?>
                                        </td>
                                        <td>
                                            <?php echo date('M d, Y', strtotime($orders->updated_at)); ?>
                                        </td>
                                        <td>
                                            <?php echo "$" . number_format(floatval($orders->total), 2); ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td>No Orders Found</td>
                                </tr>
                            <?php } ?>
                            <!-- Single Return -->
                        </tbody>
                    </table>
                    <!-- /New & In-Progress Orders -->
                    </div>
                </div>
                <!-- /Content Area -->

            </div>
        </div>
    </section>
</div>
<!-- /Content Section -->
<?php //include(INCLUDE_PATH . '/_inc/footer-vendor.php');?>
<?php //$this->load->view('templates/_inc/footer-vendor.php'); ?>

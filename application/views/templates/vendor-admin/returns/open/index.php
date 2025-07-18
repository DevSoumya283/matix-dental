<?php include(INCLUDE_PATH . '/_inc/header-vendor.php'); ?>

<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <section class="content__wrapper has--sidebar-l">
        <div class="content__main">
            <div class="row row--full-height">

                <!-- Sidebar -->
                <div class="sidebar col col--2-of-12">
                    <?php include(INCLUDE_PATH . '/vendor-admin/_inc/nav.php'); ?>
                </div>
                <!-- /Sidebar -->

                <!-- Content Area -->
                <div class="content col col--9-of-12 col--push-1-of-12">

                    <!-- /New & In-Progress Orders -->
                    <div class="heading__group border--dashed">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <h3>Open Returns (<?php echo $_SESSION['order_return']; ?>)</h3>
                            </div>
                            <div class="wrapper__inner">
                                <form method="post" action="<?php echo base_url(); ?>vendor-orderReturns-open">
                                    <div class="input__group input__group--inline">
                                        <input id="site-search" class="input input__text" type="text" value="" placeholder="Search by number, date, customer, etc..." name="search" value="" required>
                                        <label for="site-search" class="label">
                                            <svg class="icon icon--search textColor--gray"><use xlink:href="#icon-search"></use></svg>
                                        </label>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>
                                    Return
                                </th>
                                <th>
                                    Customer
                                </th>
                                <th>
                                    Opened
                                </th>
                                <th>
                                    Total Refund
                                </th>
                                <th>
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Single Return -->
                            <?php if ($return_orders != null) { ?>
                                <?php foreach ($return_orders as $orders) { ?>
                                    <tr>
                                        <td class="fontWeight--2"><?php // echo ROOT_PATH . 'templates/vendor-admin/returns/r/new';     ?>
                                            <a class="link" href="<?php echo base_url(); ?>return-requested-OrderDetail?return_id=<?php echo $orders->id; ?>"><?php echo $orders->id; ?></a>
                                        </td>
                                        <td>
                                            <?php echo $orders->first_name; ?>
                                        </td>
                                        <td>
                                            <?php echo (date('M d Y', strtotime($orders->opened))); ?>
                                        </td>
                                        <td>
                                            <?php echo "$" . $orders->total; ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($orders->return_status == "New") {
                                                echo "Needs Approval";
                                            } else {
                                                echo "Pending Return";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td>
                                        No Order Returns.
                                    </td>
                                </tr>    
                            <?php } ?>
                            <!-- Single Return -->
                        </tbody>
                    </table>
                    <!-- /New & In-Progress Orders -->

                </div>
                <!-- /Content Area -->

            </div>
        </div>
    </section>
</div>
<!-- /Content Section -->

<?php
include(INCLUDE_PATH . '/_inc/footer-vendor.php');

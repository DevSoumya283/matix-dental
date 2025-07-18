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
                                <h3>Closed Returns</h3>
                            </div>
                            <div class="wrapper__inner">
                                <form method="post" action="<?php echo base_url(); ?>vendor-orderReturn-closed">
                                    <div class="input__group input__group--inline">
                                        <input id="site-search" class="input input__text" type="text"  value="" placeholder="Search by number, date, customer, etc..." name="search" required>
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
                                    Closed
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
                            <?php if ($returned_orders != null) { ?>
                                <?php foreach ($returned_orders as $returns) { ?>
                                    <tr>
                                        <td class="fontWeight--2">
                                            <a class="link" href="<?php echo base_url(); ?>closedReturn-orderReport?return_id=<?php echo $returns->id; ?>"><?php echo $returns->id; ?></a>
                                        </td>
                                        <td>
                                            <?php echo $returns->first_name; ?>
                                        </td>
                                        <td>
                                            <?php echo date('M d Y', strtotime($returns->opened)); ?>
                                        </td>
                                        <td>
                                            <?php echo "$" . $returns->total; ?>
                                        </td>
                                        <td>
                                            <?php echo $returns->return_status; ?>
                                        </td>
                                    </tr>
                                <?php }
                            } ?>
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

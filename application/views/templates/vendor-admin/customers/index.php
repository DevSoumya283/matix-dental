
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
                    <!-- Customers -->
                    <div class="heading__group border--dashed">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <h3>Customers</h3>
                            </div>
                            <div class="wrapper__inner">
                                <form method="get" action="<?php echo base_url(); ?>vendors-customer-dashboard">
                                    <div class="input__group input__group--inline">
                                        <input id="site-search" class="input input__text" type="search" value="" placeholder="Search by customer name, company, etc..." name="search" required>
                                        <label for="site-search" class="label">
                                            <svg class="icon icon--search textColor--gray"><use xlink:href="#icon-search"></use></svg>
                                        </label>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div style="overflow: hidden; overflow-x: scroll;">
                    <table class="table" data-controls="#controlsCustomers">
                        <thead>
                            <tr>
                                <th>
                                    Customer
                                </th>
                                <th>
                                    Company
                                </th>
                                <th>
                                    Created
                                </th>
                                <th>
                                    Orders
                                </th>
                                <th>
                                    Total Spent
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Single Return -->
                            <?php if ($customer_vendors != null) { ?>
                                <?php foreach ($customer_vendors as $customer) { ?>
                                    <tr>
                                        <td>
                                            <a class="link fontWeight--2" href="<?php echo base_url(); ?>customer-purchase-details?user_id=<?php echo $customer->user_id; ?>"><?php echo $customer->name; ?></a>
                                        </td>
                                        <td>
                                            <?php echo $customer->organization_name; ?>
                                        </td>
                                        <td>
                                            <?php echo date('M d, Y', strtotime($customer->created_at)); ?>
                                        </td>
                                        <td>
                                            <?php echo $customer->quantity; ?>
                                        </td>
                                        <td>
                                            <?php echo '$' . number_format(floatval($customer->total), 2); ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="5">
                                        No customer(s) yet.
                                    </td>
                                </tr>    
                            <?php } ?>
                            <!-- Single Return -->
                        </tbody>
                    </table>
                </div>
                    <!-- /Customers -->
                    <?php echo $this->pagination->create_links(); ?>
                </div>
                <!-- /Content Area -->

            </div>
        </div>
    </section>
</div>
<!-- /Content Section -->

<?php //include(INCLUDE_PATH . '/_inc/footer-vendor.php');?>
 
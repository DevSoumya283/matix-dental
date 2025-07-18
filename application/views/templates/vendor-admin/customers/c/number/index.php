<?php include(INCLUDE_PATH . '/_inc/header-vendor.php'); ?>

<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <!-- Breadcrumbs Bar -->
    <div class="bar padding--xs bg--lightest-gray">
        <div class="wrapper wrapper--fixed">
            <ul class="list list--inline list--breadcrumbs">
                <li class="item">
                    <a class="link" href="<?php echo base_url(); ?>vendors-customer-dashboard">Customers</a>
                </li>
                <li class="item is--active">
                    <?php foreach ($user_details as $users) { ?>
                        <?php echo ucfirst($users->first_name); ?>, <?php echo $users->salutation; ?>
                    <?php } ?>
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breadcrumbs Bar -->

    <section class="content__wrapper has--sidebar-l">
        <div class="content__main">
            <div class="row row--full-height">

                <!-- Sidebar -->
                <div class="sidebar col col--2-of-12">
                    <?php //include(INCLUDE_PATH . '/vendor-admin/_inc/nav.php'); ?>
                    <?php $this->load->view('templates/vendor-admin/_inc/nav.php'); ?>

                </div>
                <!-- /Sidebar -->

                <!-- Content Area -->
                <div class="content col col--9-of-12 col--push-1-of-12">

                    <div class="border border--dashed border--1 border--light border--b" style="padding-bottom:16px;">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <ul class="list list--inline list--divided">
                                    <li class="item">
                                        <a class="link"><?php foreach ($user_details as $users) { ?><?php echo $users->email; ?><?php } ?></a>
                                    </li>
                                    <li class="item">
                                        <?php foreach ($user_details as $users) { ?>
                                            <?php
                                            echo ($users->phone1 != null) ? $users->phone1 : "";
                                            ?>
                                        <?php } ?>
                                    </li>
                                </ul>
                            </div>
                            <div class="wrapper__inner align--right">
                                <!--                                <button class="btn btn--primary btn--s modal--toggle" data-target="#composeUserMessageModal">Send Message</button>-->
                                <button class="btn btn--primary btn--s modal--toggle"  data-target="#composeUserMessageModal">Send Message</button>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Info Bar -->
                    <div class="card well" style="margin-top:16px;">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <ul class="list list--inline list--divided list--stats">
                                    <li class="item">
                                        <div class="entity__group">
                                            <?php
                                            foreach ($user_details as $users) {
                                                if ($users != null) {
                                                    ?>
                                                    <div class="avatar avatar--m" style="background-image:url('<?php echo image_url(); ?>uploads/user/profile/<?php echo $users->photo; ?>');"></div>
                                                <?php } else { ?>
                                                    <div class="avatar avatar--m" style="background-image:url('<?php echo image_url(); ?>assets/img/avatar-default.png');"></div>
                                                <?php } ?>
                                            <?php } ?>
                                            <div class="text__group">
                                                <?php foreach ($user_details as $users) { ?>
                                                    <span class="line--main"><?php echo ucwords($users->first_name); ?></span>
                                                    <span class="line--sub"><?php echo ucwords($users->organization_name); ?></span>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="item">
                                        <div class="text__group">
                                            <?php foreach ($user_details as $users) { ?>
                                                <span class="line--main"><?php echo date('M d, Y', strtotime($users->created_at)); ?></span>
                                                <span class="line--sub">Customer Since</span>
                                            <?php } ?>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="wrapper__inner">
                                <ul class="list list--inline list--divided list--stats align--right">
                                    <li class="item">
                                        <div class="text__group">
                                            <?php foreach ($user_details as $users) { ?>
                                                <span class="line--main"><?php echo "$" . $users->total; ?></span>
                                                <span class="line--sub">Lifetime Value</span>
                                            <?php } ?>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- /Customer Info Bar -->

                    <hr>
                    <br>

                    <!-- Orders -->
                    <div class="heading__group border--dashed padding--s no--pad-lr no--pad-t">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <h3>Order History</h3>
                            </div>
                            <div class="wrapper__inner align--right">
                                <input type="hidden" name="customer_id" id="customer_id" value="<?php echo $customer_id; ?>">
                                <ul class="list list--inline list--divided">
                                    <li class="item">
                                        <div class="select select--text">
                                            <label class="label">Status</label>
                                            <select aria-label="Select a Sorting Option" name="customer_orders" class="customer_Orders">
                                                <option value="1">Show All</option>
                                                <option value="2">New &amp; In Progress</option>
                                                <option value="3">Shipped</option>
                                                <option value="4">Completed</option>
                                            </select>
                                        </div>
                                    </li>
                                    <li class="item">
                                        <div class="select select--text">
                                            <label class="label">Placed in the</label>
                                            <select aria-label="Select a Sorting Option" name="orderDays" class="Order_reportByDay">
                                                <option value="1">Last 30 Days</option>
                                                <option value="2">Last 3 Months</option>
                                                <option value="3">Last 6 Months</option>
                                                <option value="4">Last Year</option>
                                            </select>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="well bg--lightest-gray" style="max-height:480px;">
                        <!-- Single Order -->
                        <?php if ($orderList != null) { ?>
                            <div class="order_status">
                                <?php foreach ($orderList as $list) { ?>
                                    <div class="order well card">
                                        <div class="heading__group wrapper border--dashed padding--s no--pad-lr no--pad-t">
                                            <div class="wrapper__inner">
                                                <h4 class="textColor--darkest-gray">Order <?php echo $list->id; ?></h4>
                                            </div>
                                            <div class="wrapper__inner align--right">
                                                <button class="btn btn--s btn--tertiary is--link" data-target="<?php echo base_url(); ?>vendor-order-details?order_id=<?php echo $list->id; ?>">View Order</button>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="order__info col col--10-of-12 col--am">
                                                <ul class="list list--inline list--stats list--divided">
                                                    <li class="item item--stat stat-s">
                                                        <div class="text__group">
                                                            <span class="line--main"><?php echo $list->location; ?></span>
                                                            <span class="line--sub">Location</span>
                                                        </div>
                                                    </li>
                                                    <li class="item item--stat stat-s">
                                                        <div class="text__group">
                                                            <span class="line--main"><?php echo date('M d, Y', strtotime($list->created_at)); ?></span>
                                                            <span class="line--sub">Order Date</span>
                                                        </div>
                                                    </li>
                                                    <li class="item item--stat stat-s">
                                                        <div class="text__group">
                                                            <span class="line--main"><?php echo $list->order_status; ?></span>
                                                            <span class="line--sub">Status</span>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="order__btn col col--2-of-12 col--am align--right">
                                                <ul class="list list--inline list--stats">
                                                    <li class="item item--stat">
                                                        <div class="text__group">
                                                            <span class="line--main font"><?php echo "$" . $list->total; ?></span>
                                                            <span class="line--sub">Order Total</span>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>

                        <!-- /Single Order -->
                    </div>
                    <!-- /Orders -->

                    <br><br>

                    <!-- License Info -->
                    <div class="heading__group border--dashed">
                        <h3>License(s)</h3>
                    </div>
                    <div class="license">

                        <?php if (!empty($licences)) { ?>
                            <?php foreach ($licences as $licence) { ?>
                                <!-- License Card Item -->
                                <div class="license__card card padding--s is--verified">
                                    <ul class="list list--table list--stats list--divided">
                                        <li class="item item--stat stat-s">
                                            <div class="text__group">
                                                <span class="line--main"><?php echo $licence->license_no; ?></span>
                                                <span class="line--sub">License #</span>
                                            </div>
                                        </li>
                                        <li class="item item--stat stat-s">
                                            <div class="text__group">
                                                <span class="line--main"><?php echo $licence->dea_no; ?></span>
                                                <span class="line--sub">DEA #</span>
                                            </div>
                                        </li>
                                        <li class="item item--stat stat-s">
                                            <div class="text__group">
                                                <span class="line--main"><?php echo date('M d, Y', strtotime($licence->expire_date)); ?></span>
                                                <span class="line--sub">Expires</span>
                                            </div>
                                        </li>
                                        <li class="item item--stat stat-s" style="padding-left:20px;">
                                            <div class="text__group">
                                                <span class="line--main"><?php echo $licence->state; ?></span>
                                                <span class="line--sub">State</span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <?php
                            }
                        }
                        ?>
                        <!-- /License Card Item -->
                    </div>
                    <!-- /License Info -->

                    <br><br>

                    <!-- Locations -->
                    <div class="heading__group border--dashed">
                        <h3>Locations</h3>
                    </div>
                    <table class="table" data-controls="#controlsShipping">
                        <thead>
                            <tr>
                                <th>
                                    Nickname
                                </th>
                                <th>
                                    Shipping Address
                                </th>
                                <th>
                                    Shipments
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Single Promo -->
                            <?php if ($user_location != null) { ?>
                                <?php foreach ($user_location as $location) { ?>
                                    <tr>
                                        <td>
                                            <?php echo $location->nickname; ?>
                                        </td>
                                        <td>
                                            <?php echo $location->address1; ?>, <?php echo $location->address2; ?><br>
                                            <?php echo $location->city; ?>, <?php echo $location->zip; ?>
                                        </td>
                                        <td>
                                            0
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                            <!-- Single Promo -->
                        </tbody>
                    </table>
                    <!-- /Locations -->

                    <hr>

                    <!-- Notes -->
                    <div class="well bg--lightest-gray" style="max-height:400px;">
                        <div class="heading__group border--dashed wrapper">
                            <div class="wrapper__inner">
                                <h4>Notes</h4>
                            </div>
                            <div class="wrapper__inner align--right">
                                <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">
                                <button class="btn btn--tertiary btn--s modal--toggle VendorCustomerId" data-customer_id="<?php echo $customer_id; ?>" data-target="#addNoteModal">Add Note</button>
                            </div>
                        </div>
                        <ul class="list list--activity fontSize--s" style="max-height:400px;">

                            <!-- Single Note -->
                            <?php if ($Vendor_Customer_notes != null) { ?>
                                <?php foreach ($Vendor_Customer_notes as $notes) { ?>
                                    <li class="item item--note">
                                        <div class="wrapper">
                                            <div class="col col--2-of-12 wrapper__inner align--center padding--m no--pad-tb">
                                                <div class="entity__group">
                                                    <?php if ($notes->photo != null) { ?>
                                                        <div class="avatar avatar--s" style="background-image:url('<?php echo image_url(); ?>uploads/user/profile/<?php echo $notes->photo; ?>'); margin:0 0 8px 0;"></div>
                                                    <?php } else { ?>
                                                        <div class="avatar avatar--s" style="background-image:url('<?php echo image_url(); ?>assets/img/ph-avatar.jpg'); margin:0 0 8px 0;"></div>
                                                    <?php } ?>
                                                    <span class="disp--block fontWeight--2"><?php echo $notes->first_name; ?></span>
                                                </div>
                                                <span class="fontSize--xs"><?php echo date('M d, Y', strtotime($notes->created_at)); ?></span>
                                            </div>
                                            <div class="col col--10-of-12 wrapper__inner">
                                                <div class="well card" style="min-height: 85px;">
                                                    <?php echo $notes->message; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                <?php } ?>
                            <?php } ?>
                            <!-- /Single Note -->

                        </ul>
                    </div>
                    <!-- /Notes -->

                </div>
                <!-- /Content Area -->

            </div>
        </div>
    </section>
</div>
<!-- /Content Section -->

<!-- Modals -->
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/add-note.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/compose-user-message.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/footer-vendor.php');?>

<?php $this->load->view('templates/_inc/shared/modals/add-note.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/compose-user-message.php'); ?>
<?php $this->load->view('templates/_inc/footer-vendor.php'); ?>

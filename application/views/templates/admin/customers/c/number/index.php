<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>
    <!-- Breadcrumbs Bar -->
    <div class="bar padding--xs bg--lightest-gray">
        <div class="wrapper wrapper--fixed">
            <ul class="list list--inline list--breadcrumbs">
                <li class="item">
                    <a class="link" href="<?php echo base_url(); ?>customer-list">Customers</a>
                </li>
                <li class="item is--active">
                    <?php echo $customer_report->first_name; ?>
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
                    <?php //include(INCLUDE_PATH . '/admin/_inc/nav.php'); ?>
                    <?php $this->load->view('templates/admin/_inc/nav.php'); ?>
                </div>
                <!-- /Sidebar -->
                <!-- Content Area -->
                <div class="content col col--9-of-12 col--push-1-of-12">
                    <div class="border border--dashed border--1 border--light border--b" style="padding-bottom:16px;">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <ul class="list list--inline list--divided">
                                    <li class="item">
                                        <button class="btn btn--tertiary btn--s btn--icon modal--toggle" data-target="#editCustomerModal"><svg class="icon icon--settings"><use xlink:href="#icon-settings"></use></svg></button>
                                    </li>
                                    <li class="item">
                                        <a class="link"><?php echo $customer_report->email; ?></a>
                                    </li>
                                    <li class="item">
                                        <?php
                                        if ($customer_report->phone1 != null) {
                                            echo $customer_report->phone1;
                                        }
                                        ?>
                                    </li>
                                    <li class="item">
                                        <?php echo ($customer_report->role_name != null) ? $customer_report->role_name : ''; ?>
                                    </li>
                                    <li class="item">
                                        <form id="formForgot" class="form__group" action="/forgot-password" method="post">
                                            <input type="hidden" name="accountForgotEmail" value="<?php echo $customer_report->email; ?>" \>
                                            <input type="hidden" name="superAdmin" value="true" \>

                                            <button class="btn btn--m btn--primary btn--block save--toggle form--submit page--reload" >Password Reset</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                            <!--                            <div class="wrapper__inner align--right">
                                                            <button class="btn btn--primary btn--s modal--toggle" data-target="#composeUserMessageModal">Send Message</button>
                                                        </div>-->
                        </div>
                    </div>

                    <!-- Customer Info Bar -->
                    <div class="card well" style="margin-top:16px;">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <ul class="list list--inline list--divided list--stats">
                                    <li class="item">
                                        <div class="entity__group">
                                            <?php if ($user_profile != null) { ?>
                                                <div class="avatar avatar--m" style="background-image:url('<?php echo image_url(); ?>uploads/user/profile/<?php echo $user_profile->photo; ?>');"></div>
                                            <?php } else { ?>
    <!--                                                <div class="avatar avatar--m" style="background-image:url('<?php echo image_url(); ?>assets/img/ph-avatar.jpg');"></div>-->
                                                <div class="avatar avatar--m" style="background-image:url('<?php echo image_url(); ?>assets/img/avatar-default.png');" ></div>
                                            <?php } ?>
                                            <div class="text__group">
                                                <span class="line--main"><?php echo $customer_report->first_name; ?></span>
                                                <span class="line--sub"><?php echo $customer_report->organization_name; ?></span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="item">
                                        <div class="text__group">
                                            <span class="line--main"><?php echo date('M d, Y', strtotime($customer_report->created_at)); ?></span>
                                            <span class="line--sub">Customer Since</span>
                                        </div>
                                    </li>
                                    <li class="item">
                                        <div class="text__group">
                                            <span class="line--main"><?php echo ($customer_report->license_count != null) ? $customer_report->license_count : "0"; ?> Approved</span>
                                            <span class="line--sub">
                                                <a class="link" href="#license">State Licence(s)</a>
                                            </span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="wrapper__inner">
                                <ul class="list list--inline list--divided list--stats align--right">
                                    <li class="item">
                                        <div class="text__group">
                                            <span class="line--main"><?php echo ($customer_report->total != null) ? "$" . number_format(floatval($customer_report->total), 2, ".", "") : "$0.00"; ?></span>
                                            <span class="line--sub">Lifetime Value</span>
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
                                <ul class="list list--inline list--divided">
                                    <li class="item">
                                        <div class="select select--text">
                                            <label class="label">Status</label>
                                            <select aria-label="Select a Sorting Option" name="order_selects" class="order_selects">
                                                <option value="-1">Show All</option>
                                                <option value="new">New &amp; In Progress</option>
                                                <option value="shipped">Shipped</option>
                                                <option value="delivered">Completed</option>
                                            </select>
                                        </div>
                                    </li>
                                    <li class="item">
                                        <div class="select select--text">
                                            <label class="label">Placed in the</label>
                                            <select aria-label="Select a Sorting Option" name="orderBy_select" class="orderBy_select" data-customer_id="<?php echo $customer_id; ?>">
                                                <option value="30">Last 30 Days</option>
                                                <option value="90">Last 3 Months</option>
                                                <option value="180">Last 6 Months</option>
                                                <option value="365">Last Year</option>
                                            </select>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                        <div class="well bg--lightest-gray" style="max-height:480px;">
                            <!-- Single Order -->
                            <div class="order_Report">
                                <?php if ($latest_reports != null) { ?>
                                <?php foreach ($latest_reports as $orders) { ?>
                                    <div class="order well card">
                                        <div class="heading__group wrapper border--dashed padding--s no--pad-lr no--pad-t">
                                            <div class="wrapper__inner">
                                                <h4 class="textColor--darkest-gray">Order <?php echo $orders->id; ?></h4>
                                            </div>
                                            <div class="wrapper__inner align--right">
                                                <ul class="list list--inline list--divided fontWeight--2 fontSize--s">
                                                    <!--                                                    <li class="item">
                                                                                                            <a class="link  is--neg modal--toggle order_cancel" data-order_id="<?php echo $orders->id; ?>" data-target="#cancelOrderAdminModal">Cancel</a>
                                                                                                        </li>-->
                                                    <li class="item">
                                                        <button class="btn btn--s btn--tertiary is--link" data-target="<?php echo base_url(); ?>superAdmin-order-details?order_id=<?php echo $orders->id; ?>">View Order</button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="order__info col col--10-of-12 col--am">
                                                <ul class="list list--inline list--stats list--divided">
                                                    <?php if ($orders->image_name != null) { ?>
                                                        <li class="item" style="width:88px;">
                                                            <div class="order__logo" style="background-image:url('<?php echo image_url(); ?>uploads/products/images/<?php echo $orders->image_name->photo; ?>');"></div>
                                                        </li>
                                                    <?php } else { ?>
                                                        <li class="item" style="width:88px;">
                                                            <div class="order__logo" style="background-image:url('<?php echo image_url(); ?>assets/img/product-image.png');"></div>
                                                        </li>
                                                    <?php } ?>
                                                    <li class="item item--stat stat-s">
                                                        <div class="text__group">
                                                            <span class="line--main"><?php echo $orders->vendor_name; ?></span>
                                                            <span class="line--sub">Purchased From</span>
                                                        </div>
                                                    </li>
                                                    <li class="item item--stat stat-s">
                                                        <div class="text__group">
                                                            <span class="line--main"><?php echo date('M d, Y', strtotime($orders->created_at)); ?></span>
                                                            <span class="line--sub">Order Date</span>
                                                        </div>
                                                    </li>
                                                    <li class="item item--stat stat-s">
                                                        <div class="text__group">
                                                            <span class="line--main"><?php echo $orders->order_status; ?></span>
                                                            <span class="line--sub">Status</span>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="order__btn col col--2-of-12 col--am align--right">
                                                <ul class="list list--inline list--stats">
                                                    <li class="item item--stat">
                                                        <div class="text__group">
                                                            <span class="line--main font"><?php echo "$" . number_format(floatval($orders->total), 2); ?></span>
                                                            <span class="line--sub">Order Total</a>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                <?php }
                                    } else { ?>
                                <div class=" align--center">
                                    This customer has not purchased anything yet.
                                </div>
                            <?php } ?>
                            </div>
                            <!-- /Single Order -->
                        </div>

                    <!-- /Orders -->

                    <br><br>

                    <!-- License Info -->
                    <div id="license" class="heading__group border--dashed">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <h3>License(s)</h3>
                            </div>
                                <div class="wrapper__inner align--right">
                            <?php if ($user_licenses != null) { ?>
                                    <form method="post" action="<?php echo base_url(); ?>approve-all-license" style="display:inline;">
                                        <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">
                                        <button class="btn btn--s btn--primary" id="<?php echo $customer_id; ?>">Approve All</button>
                                    </form>
                                    <form method="post" action="<?php echo base_url(); ?>deny-all-license" style="display:inline;">
                                        <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">
                                        <button class="btn btn--s btn--primary is--neg" id="<?php echo $customer_id; ?>">Deny All</button>
                                    </form>

                                    <br><br>
                            <?php } ?>
                                        <button class="btn btn--s btn--primary btn--tertiary edit-license  modal--toggle"  data-id="<?php echo $customer_id; ?>" data-target="#editLicenseModal">Add License</button>
                                    </form>
                                </div>

                        </div>
                    </div>
                    <?php if ($user_licenses != null) { ?>
                        <div class="license">
                            <!-- License Card Item -->
                            <?php for ($i = 0; $i < count($user_licenses); $i++) { ?>
                                <div class="license__card card padding--s <?php
                                if ($user_licenses[$i]->approved == 1) {
                                    echo "is--verified";
                                }
                                ?>">
                                    <ul class="list list--table list--stats list--divided">
                                        <li class="item item--stat stat-s">
                                            <div class="text__group">
                                                <span class="line--main license_no"><?php echo $user_licenses[$i]->license_no; ?></span>
                                                <span class="line--sub">License #</span>
                                            </div>
                                        </li>
                                        <li class="item item--stat stat-s">
                                            <div class="text__group">
                                                <span class="line--main dea_no"><?php echo $user_licenses[$i]->dea_no; ?></span>
                                                <span class="line--sub">DEA #</span>
                                            </div>
                                        </li>
                                        <li class="item item--stat stat-s">
                                            <div class="text__group">
                                                <span class="line--main expire_date"><?php echo date('M d, Y', strtotime($user_licenses[$i]->expire_date)); ?></span>
                                                <span class="line--sub">Expires</span>
                                            </div>
                                        </li>
                                        <li class="item item--stat stat-s">
                                            <div class="text__group">
                                                <span class="line--main state"><?php echo $user_licenses[$i]->state; ?></span>
                                                <span class="line--sub">State</span>
                                            </div>
                                        </li>
                                        <li class="item" style="width:120px;">
                                            <?php if ($user_licenses[$i]->approved == 1) { ?>
                                                <button id="<?php echo $user_licenses[$i]->id; ?>" class="btn btn--s is--pos btn--toggle btn--block disapprove_license" data-before="Approve" data-after="&#10003;" type="button"></button>
                                            <?php } else { ?>
                                                <button id="<?php echo $user_licenses[$i]->id; ?>" class="btn btn--s btn--tertiary btn--toggle btn--block approve_license" data-before="Approve" data-after="&#10003;" type="button"></button>
                                            <?php } ?>
                                        </li>
                                        <li class="item" style="width:120px;">
                                            <button data-license-id="<?php echo $user_licenses[$i]->id; ?>" data-target="#editLicenseModal" class="btn btn--s btn--tertiary btn--block modal--toggle edit-license" type="button" >Edit</button>
                                        </li>
                                    </ul>
                                </div>
                            <?php } ?>
                            <!-- /License Card Item -->
                        </div>
                    <?php } else { ?>
                        <div class="well align--center">
                            This customer is not registered  with License (s).
                        </div>
                    <?php } ?>
                    <!-- /License Info -->

                    <br><br>

                    <!-- Locations -->
                    <div class="heading__group border--dashed">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <h3>Locations</h3>
                            </div>
                            <div class="wrapper__inner align--right">
                                <button class="btn btn--tertiary btn--s modal--toggle" data-target="#assignLocationsModal">Assign/Unassign</button>
                            </div>
                        </div>
                    </div>
                    <?php if ($location != null) { ?>
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
                                <!-- Single Location -->
                                <?php foreach ($location as $userLocation) { ?>
                                    <tr>
                                        <td>
                                            <?php echo $userLocation->nickname; ?>
                                        </td>
                                        <td>
                                            <?php
                                            echo $userLocation->address1 . "<br>";
                                            echo $userLocation->address2 . "<br>";
                                            echo $userLocation->city . "<br>";
                                            echo $userLocation->state . "-", $userLocation->zip;
                                            ?>
                                        </td>
                                        <td>
                                            <?php echo $userLocation->shipment_count; ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <!-- Single Location -->
                            </tbody>
                        </table>
                    <?php } else { ?>
                        <div class="well align--center">
                            This customer is not assigned to any locations.
                        </div>
                    <?php } ?>

                    <!--
                        NOTE: The following '.well' is the markup for the empty state for a user's assigned locations list.
                    -->
                    <!-- <div class="well align--center">
                        This customer is not assigned to any locations.
                    </div> -->

                    <!-- /Locations -->

                    <hr>

                    <!-- Notes -->
                    <div class="well bg--lightest-gray" style="max-height:400px;">
                        <div class="heading__group border--dashed wrapper">
                            <div class="wrapper__inner">
                                <h4>Notes</h4>
                            </div>
                            <div class="wrapper__inner align--right">
                                <button class="btn btn--tertiary btn--s modal--toggle adminAddCustomerNote" data-customer_id="<?php echo $customer_id; ?>" data-target="#adminNoteModal">Add Note</button>
                            </div>
                        </div>
                        <ul class="list list--activity fontSize--s" style="max-height:400px;">

                            <!-- Single Note -->
                            <?php if ($customer_note != null) { ?>
                                <?php foreach ($customer_note as $notes) { ?>
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
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/edit-customer.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/edit-license.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/assign-locations.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/cancel-order-admin.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/compose-user-message.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/admin-note.php'); ?>

<?php $this->load->view('templates/_inc/shared/modals/edit-customer.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/edit-license.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/assign-locations.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/cancel-order-admin.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/compose-user-message.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/admin-note.php'); ?>


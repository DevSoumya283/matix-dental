<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>
    <!-- Account Bar -->
    <div class="bar padding--m bg--lightest-gray">
        <div class="wrapper wrapper--fixed row">
            <div class="wrapper__inner col-md-4 col-xs-12">
                <ul class="list list--inline list--divided list--stats">
                    <li class="item">
                        <div class="entity__group">
                            <?php
                            $users = unserialize(ROLES_USERS);
                            $tier1 = unserialize(ROLES_TIER1);
                            $tier2 = unserialize(ROLES_TIER2);
                            $tier_1_2 = unserialize(ROLES_TIER1_2);
                            $tier2ab = unserialize(ROLES_TIER2_AB);
                            $tier3 = unserialize(ROLES_TIER3);
                            $tier_1_2ab = unserialize(ROLES_TIER1_2_AB);
                            $tier_1_2_3a = unserialize(ROLES_TIER1_2_3A);
                            $tier_1_2_roles = unserialize(ROLES_TIER1_2);
                            $tier_1_2a = unserialize(ROLES_TIER1_2A);
                            if ($user_image != null) {
                                ?>
                                <div class="avatar avatar--l" style="background-repeat: no-repeat; background-image:url('/uploads/user/profile/<?php echo $user_image->photo; ?>');"></div>
                                <?php } else { ?>

                                <div class="avatar avatar--l" style="background-image:url('<?php echo image_url(); ?>assets/img/avatar-default.png');"></div>
                                <?php } ?>
                                <div class="text__group">
                                    <span class="line--main"><?php
                                    if ($_SESSION['user_name'] != null) {
                                        echo $_SESSION['user_name'];
                                    } else {
                                        echo "";
                                    }
                                    ?></span>
                                    <a class="link fontSize--s fontWeight--2" href="<?php echo base_url('profile'); ?>">View My Profile</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <?php if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $users))) { ?>
                <div class="wrapper__inner align--right col-md-7 md-offset-1 col-xs-12">
                    <ul class="list list--inline list--divided list--stats dashboard-toplist">
                        <?php if ($license != null) { ?>
                        <li class="item item--stat">
                            <div class="text__group">
                                <span class="line--main"><?php echo $license->license_no; ?></span>
                                <span class="line--sub">License Number</span>
                            </div>
                        </li>
                        <li class="item item--stat">
                            <div class="text__group">
                                <?php if ($license->approved == 0) { ?>
                                <span class="line--main">Not Verified</span>
                                <?php } else { ?><span class="line--main">Verified</span> <?php } ?>
                                <span class="line--sub">Purchasing Power</a>
                                </div>
                            </li>
                            <?php
                        } else {
                            echo '<li class="item item--stat"><div class="text__group"><span class="line--sub">You do not have license for this Location</span></div></li>';
                        }
                        ?>
                        <?php
                        if ($total_spend != null) {
                            foreach ($total_spend as $key) {
                                ?>
                                <li class="item item--stat">
                                    <div class="text__group">
                                        <?php if ($key->totals != null) { ?>
                                        <span class="line--main year">$<?php echo number_format($key->totals, 2, '.', ','); ?></span>
                                        <?php } else { ?>
                                        <span class="line--main year">$0.00</span>
                                        <?php } ?>
                                        <span class="line--sub">Total Spend</span>
                                        <div class="select select--text">
                                            <select aria-label="Select a Range" class="ytd">
                                                <option selected>MTD</option>
                                                <option value="1">YTD</option>
                                            </select>
                                        </div>
                                    </div>
                                </li>
                                <?php
                            }
                        }
                        ?>
                    </ul>
                </div>
                <?php } ?>
            </div>
        </div>
        <!-- /Account Bar -->
        <!-- Main Content -->
        <section class="content__wrapper">
            <div class="content__main padding--xl no--pad--lr mobile-center">
                <!-- Order History -->
                <?php if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $tier_1_2))) { ?>
                <div class="row ">
                    <div class="col-md-3 col-xs-12">
                        <h3>My Orders</h3>
                        <span class="textColor--dark-gray"></span>
                    </div>
                    <div class="col-md-9 col-xs-12">
                        <div class="well well--tall row">
                            <div class="col-md-4 col-xs-12">
                                <h3 class="textColor--dark-gray">Manage Orders</h3>
                                <ul class="list">
                                    <li class="item">
                                        <a class="link" href="<?php echo base_url('history'); ?>">Order History</a>
                                    </li>

                                    <li class="item">
                                        <a class="link" href="<?php echo base_url('recurring'); ?>">Recurring Orders</a>
                                    </li>

                                    <?php if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $tier_1_2_3a))) { ?>
                                    <li class="item">
                                        <a class="link" href="<?php echo base_url('feedback'); ?>">Leave Vendor Feedback</a>
                                    </li>

                                    <?php } ?>
                                </ul>
                            </div>
                            <div class="col-md-7 col-xs-12 mobile-pt30">
                                <h3 class="textColor--dark-gray">Find an Order</h3>
                                <form method="post" action="<?php echo base_url(); ?>search-orders">
                                    <div class="input__group input__group--inline">
                                        <input id="site-search" class="input input__text" type="search" value="" placeholder="Search by order number, date, product, etc…" name="search" required>
                                        <label for="site-search" class="label">
                                            <svg class="icon icon--search textColor--gray"><use xlink:href="#icon-search"></use></svg>
                                        </label>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <!-- /Order History -->
                <br>
                <!-- My Account -->
                <div class="row">
                    <div class="col-md-3 col-xs-12">
                        <h3>My Account</h3>
                        <span class="textColor--dark-gray"></span>
                    </div>
                    <div class="col-md-9 col-xs-12">
                        <div class="well well--tall row">
                            <div class="col-md-4 col-xs-12">
                                <h3 class="textColor--dark-gray">Account Details</h3>
                                <ul class="list">
                                    <li class="item">
                                        <a class="link" href="<?php echo base_url('edit-profile'); ?>">Edit Your Profile</a>
                                    </li>
                                    <li class="item">
                                        <a class="link" href="<?php echo base_url('edit-password'); ?>">Change Your Password</a>
                                    </li>
                                    <?php if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $tier_1_2)))) { ?>
                                    <li class="item">
                                        <a class="link" href="<?php echo base_url('email-settings'); ?>">Change Email Settings</a>
                                    </li>

                                    <?php } ?>
                                    <?php if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $tier_1_2ab))) { ?>
                                    <li class="item">
                                        <a class="link" href="<?php echo base_url('profile'); ?>">Manage Your License</a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <?php if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $tier_1_2_roles)))) { ?>
                            <div class="col-md-4 col-xs-12 mobile-pt30">
                                <h3 class="textColor--dark-gray">Payment</h3>
                                <ul class="list">
                                    <li class="item">
                                        <a class="link" href="<?php echo base_url('payments'); ?>">Manage Payment Methods</a>
                                    </li>

                                    <li class="item">
                                        <a class="link modal--toggle" data-target="#newPaymentModal" data-state-target="#paymentMethods" data-state="is--cc">Add a Credit or Debit Card</a>
                                    </li>
                                    <li class="item">
                                        <a class="link modal--toggle" data-target="#newPaymentModal" data-state-target="#paymentMethods" data-state="is--bank">Add a Bank Account</a>
                                    </li>
                                </ul>
                            </div>
                            <?php } ?>
                            <?php if ($_SESSION['role_id'] != '6') { ?>
                            <div class="col col--4-of-12 mobile-pt30">
                                <h3 class="textColor--dark-gray">Reports</h3>
                                <ul class="list">
                                    <li class="item">
                                        <a class="link" href="<?php echo base_url('snapshot'); ?>">Account Snapshot</a>
                                    </li>

                                    <li class="item">
                                        <a class="link" href="<?php echo base_url('orders'); ?>">Orders Report</a>
                                    </li>

                                    <li class="item">
                                        <a class="link" href="<?php echo base_url('purchases'); ?>">Purchase Report</a>
                                    </li>

                                    <li class="item">
                                        <a class="link" href="<?php echo base_url('tax'); ?>">Taxable Purchases</a>
                                    </li>
                                </ul>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <!-- /My Account -->
                <br>
                <!-- Organization -->
                <div class="row">
                    <div class="col-md-3 col-xs-12">
                        <h3>My Organization</h3>
                        <span class="textColor--dark-gray"></span>
                    </div>
                    <div class="col-md-9 col-xs-12">
                        <div class="well well--tall row">
                            <div class="col-md-4 col-xs-12">
                                <h3 class="textColor--dark-gray">Company Details</h3>
                                <ul class="list">
                                    <li class="item">
                                        <a class="link" href="<?php echo base_url('company'); ?>">Edit Company Details</a>
                                    </li>

                                    <li class="item">
                                        <a class="link" href="<?php echo base_url('shopping-lists'); ?>">Manage Shopping Lists</a>
                                    </li>

                                    <?php
                                    $role_lists = unserialize(ROLES_LISTS);
                                    if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $role_lists)))) {
                                        ?>
                                        <li class="item">
                                            <a class="link modal--toggle get_locations" data-target="#newPrepopulatedListModal">Create a Shopping List</a>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <div class="col-md-4 col-xs-12 mobile-pt30">
                                    <h3 class="textColor--dark-gray">Locations</h3>
                                    <ul class="list">
                                        <?php if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $tier_1_2_roles))) { ?>
                                        <?php if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $tier_1_2a))) { ?>
                                        <li class="item">
                                            <a class="link" href="<?php echo base_url('locations'); ?>">Manage Locations</a>
                                        </li>

                                        <?php } ?>
                                        <?php } ?>
                                        <?php if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $tier1))) { ?>
                                        <li class="item">
                                            <a class="link modal--toggle" data-target="#addNewLocationModal">Add a New Location</a>
                                        </li>
                                        <?php } ?>
                                        <?php if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $tier_1_2_roles ) || in_array($_SESSION['role_id'], unserialize(ROLES_TIER3)) )) { ?>
                                        <?php if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $tier_1_2a) || in_array($_SESSION['role_id'], unserialize(ROLES_TIER3)))) { ?>
                                        <li class="item">
                                            <a class="link" href="<?php echo base_url('manage-inventory'); ?>">Manage Your Inventory</a>
                                        </li>

                                        <?php } ?>
                                        <?php } ?>
                                        <?php if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $users))) { ?>
                                        <li class="item">
                                            <a class="link" href="<?php echo base_url('request-lists'); ?>">View Request Lists</a>
                                        </li>

                                        <?php } ?>
                                    </ul>
                                </div>
                                <div class="col-md-4 col-xs-12 mobile-pt30">
                                    <h3 class="textColor--dark-gray">Users</h3>
                                    <ul class="list">
                                        <?php if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $tier_1_2)))) { ?>
                                        <?php if (isset($_SESSION['role_id']) && ($_SESSION['role_id'] == '7' || $_SESSION['role_id'] == '8' || $_SESSION['role_id'] == '9')) { ?>
                                        <li class="item">
                                            <a class="link" href="<?php echo base_url('classes'); ?>">Manage Classes</a>
                                        </li>

                                        <?php } ?>
                                        <?php } ?>
                                        <li class="item">
                                            <a class="link" href="<?php echo base_url(); ?>Manage-Users">Manage Users</a>
                                        </li>

                                        <?php if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $tier_1_2ab)))) { ?>
                                        <li class="item">
                                            <a class="link modal--toggle get_user_locations" data-target="#addOrganizationUserModal">Add a New User</a>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Organization -->
                </div>
            </section>
            <!-- /Main Content -->
        </div>
        <!-- /Content Section -->
        <!-- Modals -->
        <?php $this->load->view('templates/_inc/shared/modals/new-payment.php'); ?>
        <?php $this->load->view('templates/_inc/shared/modals/new-list.php'); ?>
        <?php $this->load->view('templates/_inc/shared/modals/new-location.php'); ?>
        <?php $this->load->view('templates/_inc/shared/modals/add-organization-user.php'); ?>



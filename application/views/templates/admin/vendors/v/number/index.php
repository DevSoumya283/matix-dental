
<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <!-- Breadcrumbs Bar -->
    <div class="bar padding--xs bg--lightest-gray">
        <div class="wrapper wrapper--fixed">
            <ul class="list list--inline list--breadcrumbs">
                <li class="item">
                    <a class="link" href="<?php echo base_url(); ?>vendorsIn-list">Vendors</a>
                </li>
                <li class="item is--active">
                    <?php if ($vendor_report != null) { ?>
                        <?php echo $vendor_report->name; ?>
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
                <div class="sidebar col col--2-of-12"style="padding:12px">
                    <?php //include(INCLUDE_PATH . '/admin/_inc/nav.php'); ?>
                    <?php $this->load->view('templates/admin/_inc/nav.php'); ?>
                    
                </div>
                <!-- /Sidebar -->

                <!-- Content Area -->
                <div class="content col col--9-of-12 col--push-1-of-12">

                    <div class="border border--dashed border--1 border--light border--b" style="padding-bottom:16px;">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <h3 class="no--margin-b">Vendor Details</h3>
                            </div>
                            <div class="wrapper__inner align--right">
                                <ul class="list list--inline list--divided">
                                    <li class="item">
                                        <a class="link fontWeight--2 fontSize--s" href="<?php echo base_url(); ?>product-catalog?vendor_id=<?php echo $vendor_userInvitationId ?>">View Catalog</a>
                                    </li>
                                    <li class="item">
                                        <div class="tab__group">
                                            <label class="tab VendorTypeChange" value="type-independent" data-vendor_type='0' data-vendor_id='<?php echo $vendor_userInvitationId ?>'>
                                                <input type="radio" name="vendorType" class="vendor_type"  value="0" <?php echo ($vendor_report->vendor_type == "0") ? "checked" : ""; ?>>
                                                <span>Independent</span>
                                            </label>
                                            <label class="tab VendorTypeChange" value="type-matix" data-vendor_type='1' data-vendor_id='<?php echo $vendor_userInvitationId ?>'>
                                                <input type="radio" name="vendorType" class="vendor_type" value="1" <?php echo ($vendor_report->vendor_type == "1") ? "checked" : ""; ?>>
                                                <span>Matix</span>
                                            </label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Info Bar -->
                    <div class="card well" style="margin-top:16px;">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <ul class="list list--inline list--divided list--stats">
                                    <li class="item">
                                        <div class="text__group">
                                            <span class="line--main"><?php echo $vendor_report->name; ?></span>
                                            <span class="line--sub">Vendor since <?php echo date('M d, Y', strtotime($vendor_report->created_at)); ?></span>
                                        </div>
                                    </li>
                                    <li class="item">
                                        <div class="text__group">
                                            <span class="line--main"><?php echo $vendor_report->address1, $vendor_report->address2 ?></span>
                                            <span class="line--sub"><?php echo $vendor_report->country, '-' . $vendor_report->zip; ?></span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="wrapper__inner">
                                <ul class="list list--inline list--divided list--stats align--right">
                                    <li class="item">
                                        <div class="text__group">
                                            <span class="line--main"><?php echo $vendor_report->name; ?></span>
                                            <span class="line--sub"><?php echo $vendor_report->email; ?></span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- /Customer Info Bar -->

                    <hr>
                    <br>

                    <!-- Account Info -->
                    <div class="row">
                        <div class="col col--3-of-12 col--am">
                            <div class="well card align--center is--pos">
                                <h5>Revenue (MTD)</h5>
                                <h3 class="no--margin-b"><?php echo ($vendor_report->monthly_total != null) ? "$" . number_format(floatval($vendor_report->monthly_total), 2) : "0.00" ?></h3>
                            </div>
                        </div>
                        <div class="col col--3-of-12 col--am">
                            <div class="well card align--center is--pos">
                                <h5>Revenue (YTD)</h5>
                                <h3 class="no--margin-b"><?php echo($vendor_report->total != null) ? "$" . number_format(floatval($vendor_report->total), 2) : "0.00" ?></h3>
                            </div>
                        </div>
                        <div class="col col--3-of-12 col--am">
                            <div class="well card align--center is--pos">
                                <h5>Orders (YTD)</h5>
                                <h3 class="no--margin-b"><?php echo($vendor_report->total_orders != null) ? $vendor_report->total_orders : "0" ?></h3>
                            </div>
                        </div>
                        <div class="col col--3-of-12 col--am">
                            <div class="well card align--center is--pos">
                                <h5>Products Listed</h5>
                                <h3 class="no--margin-b"><?php echo($vendor_report->total_products != null) ? $vendor_report->total_products : "0" ?></h3>
                            </div>
                        </div>
                    </div>
                    <!-- /Account Info -->

                    <hr>
                    <br>

                    <!-- Users List -->
                    <div class="heading__group border--dashed">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <h3>Users (<?php echo $vendor_report->vendors_count; ?>)</h3>
                            </div>
                            <div class="wrapper__inner">
                                <div class="input__group input__group--inline">
                                    <form action="<?php echo base_url(); ?>vendors-sales-report?vendor_id=<?php echo $vendor_userInvitationId ?>" method="post">
                                        <input id="vendor-search" class="input input__text" type="search" placeholder="Search by user name, email, etc..." name="search" required>
                                        <label for="site-search" class="label">
                                            <svg class="icon icon--search textColor--gray"><use xlink:href="#icon-search"></use></svg>
                                        </label>
                                    </form>
                                </div>
                            </div>
                            <div id="controlsTable" class="contextual__controls wrapper__inner align--right">
                                <button class="btn btn--tertiary btn--s contextual--hide modal--toggle" data-target="#addNewVendorUserModal">Create User</button>
                                <ul class="list list--inline list--divided fontWeight--2 fontSize--s is--contextual is--off">
                                    <li class="item">
                                        <a class="link modal--toggle" data-target="#SPvendorresetPasswordModal">Reset Password</a>
                                    </li>
                                    <li class="item">
                                        <a class="link modal--toggle" data-target="#confirmVendorUserActivationModal">Activate</a>
                                    </li>
                                    <li class="item">
                                        <a class="link modal--toggle" data-target="#confirmVendorUserDeactivationModal">Deactivate</a>
                                    </li>
                                    <li class="item">
                                        <a class="link modal--toggle is--neg" data-target="#deleteVendorUserModal">Delete</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div style="overflow: hidden; overflow-x: scroll;">
                        <table class="table" data-controls="#controlsTable">
                        <thead>
                            <tr>
                                <th width="3%">
                                    <label class="control control__checkbox">
                                        <input type="checkbox" <?php echo ($vendor_users != null) ? "class='is--selector'" : ""; ?> <?php echo ($vendor_users != null) ? "id='selectAll'" : ""; ?>>
                                        <div class="control__indicator"></div>
                                    </label>
                                </th>
                                <th>
                                    Name
                                </th>
                                <th>
                                    Email
                                </th>
                                <th>
                                    Created
                                </th>
                                <th>
                                    Role
                                </th>
                                <th>
                                    Status
                                </th>
                                <th>
                                    &nbsp;
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Single User -->
                            <?php if ($vendor_users != null) { ?>
                                <?php foreach ($vendor_users as $user) { ?>
                                    <tr>
                                        <td>
                                            <label class="control control__checkbox">
                                                <input type="checkbox" name="checkboxRow" class="singleCheckbox" value="<?php echo $user->id; ?>">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </td>
                                        <td class="fontWeight--2">
                                            <?php echo $user->first_name; ?>
                                        </td>
                                        <td>
                                            <a class="link" href="mailto:<?php echo $user->email; ?>"><?php echo $user->email; ?></a>
                                        </td>
                                        <td>
                                            <?php echo date('M d, Y', strtotime($user->created_at)); ?>
                                        </td>
                                        <td>
                                            <?php echo ($user->role_id == 11) ? "Vendor-Admin" : ""; ?>
                                        </td>
                                        <td>
                                            <?php echo ($user->status == 1) ? "Active" : "Inactive"; ?>
                                        </td>
                                        <td class="align--center">
                                            <button class="btn btn--tertiary btn--s btn--icon modal--toggle" data-target="#editVendorUserModal<?php echo $user->id; ?>"><svg class="icon icon--settings"><use xlink:href="#icon-settings"></use></svg></button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="5">No user(s) found</td>
                                </tr>
                            <?php } ?>
                            <!-- Single User -->
                        </tbody>
                    </table>
                    <!-- /Users List -->
                    </div>
                </div>
                <!-- /Content Area -->

            </div>
        </div>
    </section>
</div>
<!-- /Content Section -->

<!-- Modals -->
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/new-vendor-user.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/edit-vendor-user.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/delete-vendor-user.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/confirm-vendorUser-activation.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/confirm-vendorUser-deactivation.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/SPvendor-reset-password.php'); ?> 

<?php $this->load->view('templates/_inc/shared/modals/new-vendor-user.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/edit-vendor-user.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/delete-vendor-user.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/confirm-vendorUser-activation.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/confirm-vendorUser-deactivation.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/SPvendor-reset-password.php'); ?>
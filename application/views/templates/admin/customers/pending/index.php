<?php include(INCLUDE_PATH . '/_inc/header-admin.php'); ?>

<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <section class="content__wrapper has--sidebar-l">
        <div class="content__main">
            <div class="row row--full-height">

                <!-- Sidebar -->
                <div class="sidebar col col--2-of-12" style="padding: 12px;">
                    <?php //include(INCLUDE_PATH . '/admin/_inc/nav.php'); ?>
                    <?php $this->load->view('templates/admin/_inc/nav.php'); ?>
                </div>
                <!-- /Sidebar -->

                <!-- Content Area -->
                <div class="content col col--9-of-12 col--push-1-of-12">
                    <!-- Customers -->
                    <div class="heading__group border--dashed">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <h3>Customers (Pending Approval)</h3>
                            </div>
                            <div class="wrapper__inner">
                                <form method="get" action="<?php echo base_url(); ?>customerSection-accept-customers">
                                    <div class="input__group input__group--inline">
                                        <input id="site-search" class="input input__text" type="search" value="" placeholder="Search by name, company, etc..." name="search" required>
                                        <label for="site-search" class="label">
                                            <svg class="icon icon--search textColor--gray"><use xlink:href="#icon-search"></use></svg>
                                        </label>
                                    </div>
                                </form>
                            </div>
                            <div id="controlsTable" class="contextual__controls wrapper__inner align--right">
                                <?php if ($organizations_request != null) { ?>
                                    <ul class="list list--inline list--divided fontWeight--2 fontSize--s is--contextual is--off">
                                        <li class="item">
                                            <a class="link modal--toggle" data-target="#approvePendingUserModal">Approve</a>
                                        </li>
                                        <li class="item">
                                            <a class="link modal--toggle" data-target="#denyPendingUserModal">Deny</a>
                                        </li>
                                        <li class="item">
                                            <a class="link  modal--toggle is--neg" data-target="#deletePendingUserModal">Delete</a>
                                        </li>
                                    </ul>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="overlay__wrapper" style="overflow: scroll;">
                    <div style="overflow: hidden; overflow-x: scroll;">
                    <table class="table" data-controls="#controlsTable">
                        <thead>
                            <tr>
                                <th width="3%">
                                    <label class="control control__checkbox">
                                        <input type="checkbox" class=" is--selector" id="selectAll">
                                        <div class="control__indicator"></div>
                                    </label>
                                </th>
                                <th>
                                    Customer
                                </th>
                                <th>
                                    Organization
                                </th>
                                <th>
                                    Type
                                </th>
                                <th>
                                    Created
                                </th>
                                <th>
                                    License(s)
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Single Return -->
                            <?php if ($organizations_request != null) { ?>
                                <?php foreach ($organizations_request as $user) { ?>
                                    <tr>
                                        <td>
                                            <label class="control control__checkbox">
                                                <input type="checkbox" name="checkboxRow" class="singleCheckbox" value="<?php echo $user->id; ?>">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </td>
                                        <td class="fontWeight--2">
                                            <a class="link fontWeight--2" href="<?php echo base_url(); ?>customer-details-page?user_id=<?php echo $user->id; ?>"><?php echo $user->first_name; ?></a>
                                        </td>
                                        <td>
                                            <?php echo $user->organization_name; ?>
                                        </td>
                                        <td>
                                            <?php echo $user->organization_type; ?>
                                        </td>
                                        <td>
                                            <?php echo date('M d, Y', strtotime($user->created_at)); ?>
                                        </td>
                                        <td>
                                            <?php echo $user->license_count; ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="5">
                                        No user(s) found
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
                </div>
                <!-- /Content Area -->

            </div>
        </div>
    </section>
</div>
<!-- /Content Section -->
<?php include(INCLUDE_PATH . '/_inc/shared/modals/approve-pending-user.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/deny-pending-user.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/delete-pending-user.php'); ?>
<?php
include(INCLUDE_PATH . '/_inc/footer-admin.php');

<?php include(INCLUDE_PATH . '/_inc/header-admin.php'); ?>

<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

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
                    <!-- Customers -->
                    <div class="heading__group border--dashed">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <h3>Customers</h3>
                            </div>
                            <div class="wrapper__inner">
                                <form action="<?php echo base_url(); ?>customer-list" method="get">
                                    <div class="input__group input__group--inline">
                                        <input id="site-search" class="input input__text" type="search" value="" placeholder="Search by customer name, company, etc..." name="search" required>
                                        <label for="site-search" class="label">
                                            <svg class="icon icon--search textColor--gray"><use xlink:href="#icon-search"></use></svg>
                                        </label>
                                    </div>
                                </form>
                            </div>
                            <div id="controlsTable" class="contextual__controls wrapper__inner align--right">
                                <button class="btn btn--primary btn--m contextual--hide modal--toggle" data-target="#addNewCustomerModal">New Customer</button>
                                <?php if ($organizations_list != null) { ?>
                                    <ul class="list list--inline list--divided fontWeight--2 fontSize--s is--contextual is--off">
                                        <li class="item">
                                            <a class="link modal--toggle" data-target="#confirmUserActivationModal">Activate</a>
                                        </li>
                                        <li class="item">
                                            <a class="link modal--toggle" data-target="#confirmUserDeactivationModal">Deactivate</a>
                                        </li>
                                    </ul>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
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
                                    Created
                                </th>
                                <th>
                                    Role
                                </th>
                                <th>
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Single Return -->
                            <?php if ($organizations_list != null) { ?>
                                <?php foreach ($organizations_list as $user) { ?>
                                    <tr>
                                        <td>
                                            <label class="control control__checkbox">
                                                <input type="checkbox" name="checkboxRow" class="singleCheckbox" value="<?php echo $user->id; ?>">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </td>
                                        <td>
                                            <a class="link fontWeight--2" href="<?php echo base_url(); ?>customer-details-page?user_id=<?php echo $user->id; ?>"><?php echo $user->first_name; ?></a>
                                        </td>
                                        <td>
                                            <?php echo $user->organization_name; ?>
                                        </td>
                                        <td>
                                            <?php echo date('M d, Y', strtotime($user->created_at)); ?>
                                        </td>
                                        <td>
                                            <?php echo $user->role_name; ?>
                                        </td>
                                        <td>
                                            <?php echo ($user->status == 1) ? "Active" : "Inactive"; ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="5">No user(s) found</td>
                                </tr>
                            <?php } ?>
                            <!-- Single Return -->
                        </tbody>
                    </table>
                    <?php echo $this->pagination->create_links(); ?>
                    </div>
                    <!-- /Customers -->
                </div>
                <!-- /Content Area -->

            </div>
        </div>
    </section>
</div>
<!-- /Content Section -->

<!-- Modals -->
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/new-customer.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/confirm-user-activation.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/confirm-user-deactivation.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/footer-admin.php'); ?>

<?php $this->load->view('templates/_inc/shared/modals/new-customer.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/confirm-user-activation.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/confirm-user-deactivation.php'); ?>
<?php //$this->load->view('templates/_inc/footer-admin.php'); ?>
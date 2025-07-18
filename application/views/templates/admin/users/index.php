<?php include(INCLUDE_PATH . '/_inc/header-admin.php'); ?>

<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

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
                    <!-- Customers -->
                    <div class="heading__group border--dashed">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <h3>Admin Users</h3>
                            </div>
                            <div class="wrapper__inner">
                                <div class="input__group input__group--inline">
                                    <form method="post" name="adminsearch" action="<?php echo base_url(); ?>superAdmins-Users" style="display: inline;">
                                        <input id="site-search" class="input input__text" type="search" value="" placeholder="Search by name, email, etc..." name="search" required>
                                        <label for="site-search" class="label">
                                            <svg class="icon icon--search textColor--gray"><use xlink:href="#icon-search"></use></svg>
                                        </label>
                                    </form>
                                </div>
                            </div>
                            <div id="controlsTable" class="contextual__controls wrapper__inner align--right">
                                <button class="btn btn--primary btn--m contextual--hide modal--toggle" data-target="#addNewAdminModal">Create User</button>
                                <?php if ($superAdmin != null) { ?>
                                    <ul class="list list--inline list--divided fontWeight--2 fontSize--s is--contextual is--off">
                                        <li class="item">
                                            <a class="link modal--toggle" data-target="#resetPasswordModal">Reset Password</a>
                                        </li>
                                        <li class="item">
                                            <a class="link is--neg modal--toggle" data-target="#deleteMultipleModal">Delete</a>
                                        </li>
                                    </ul>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <table class="table" data-controls="#controlsTable">
                        <thead>
                            <tr>
                                <th width="3%">
                                    <label class="control control__checkbox">
                                        <input type="checkbox" class="is--selector" id="selectAll" value="">
                                        <div class="control__indicator"></div>
                                    </label>
                                </th>
                                <th>
                                    User
                                </th>
                                <th>
                                    Email
                                </th>
                                <th>
                                    Role
                                </th>
                                <th>
                                    Created
                                </th>
                                <th>
                                    &nbsp;
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Single Return -->
                            <?php if ($superAdmin != null) { ?>
                                <?php foreach ($superAdmin as $user) { ?>
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
                                            <?php echo $user->email; ?>
                                        </td>
                                        <td>
                                            <?php echo $user->role_name; ?>
                                        </td>
                                        <td>
                                            <?php echo date('M d, Y', strtotime($user->created_at)); ?>
                                        </td>
                                        <td class="align--center">
                                            <button class="btn btn--tertiary btn--s btn--icon modal--toggle" data-target="#editAdminModal<?php echo $user->id; ?>"><svg class="icon icon--settings"><use xlink:href="#icon-settings"></use></svg></button>
                                            <button class="btn btn--tertiary btn--s btn--icon btn--link modal--toggle" data-target="#deleteUserModal<?php echo $user->id; ?>"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></button>
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
                    <!-- /Customers -->
                </div>
                <!-- /Content Area -->

            </div>
        </div>
    </section>
</div>
<!-- /Content Section -->

<!-- Modals -->
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/new-admin.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/edit-admin.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/delete-admin.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/reset-password.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/delete-multiple-admin.php'); ?>

<?php //include(INCLUDE_PATH . '/_inc/footer-admin.php');?>

<?php $this->load->view('templates/_inc/shared/modals/new-admin.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/edit-admin.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/delete-admin.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/reset-password.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/delete-multiple-admin.php'); ?>
<?php $this->load->view('templates/_inc/footer-admin.php'); ?>

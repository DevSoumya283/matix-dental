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
                    <!-- Admin Profile -->
                    <div class="accordion__group">
                        <div class="accordion__section">
                            <div class="accordion__title wrapper">
                                <div class="wrapper__inner">
                                    <h3>Profile photo</h3>
                                </div>
                                <div class="wrapper__inner align--right">
                                    <a class="link link--expand">Edit</a>
                                </div>
                            </div>
                            <div class="accordion__content">
                                <div class="accordion__preview">
                                    <div class="entity__group">
                                        <?php if ($profile_image != null) { ?>
                                            <div class="vendor__logo" style="width:160px;">
                                                <img src="<?php echo base_url(); ?>uploads/user/profile/<?php echo $profile_image->photo; ?>" alt="<?php echo $profile_image->photo; ?>">
                                            </div>
                                        <?php } else { ?>
                                            <div class="vendor__logo" style="width:160px;">
                                                <img src="<?php echo base_url(); ?>assets/img/avatar-default.png" alt="">
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="accordion__edit">
                                    <form id="formCompanyName" class="form__group" action="<?php echo base_url(); ?>admin-profile-insert" method="post" enctype="multipart/form-data">
                                        <div class="row border border--dashed border--t border--light border--1 padding--xs no--pad-lr no--pad-b margin--s no--margin-lr">
                                            <h5>Upload Profile photo</h5>
                                            <div class="input__group is--inline">
                                                <input id="companyLogo" name="companyLogo" class="input input--file" type="file">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <button class="btn btn--m btn--primary btn--block save--toggle form--submit">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Company Name -->
                    <!-- Name -->
                    <div class="accordion__group">
                        <div class="accordion__section">
                            <div class="accordion__title wrapper">
                                <div class="wrapper__inner">
                                    <h3>Account Info</h3>
                                </div>
                                <div class="wrapper__inner align--right">
                                    <a class="link link--expand">Edit</a>
                                </div>
                            </div>
                            <div class="accordion__content">
                                <div class="accordion__preview">
                                    <ul class="list list--inline list--divided">
                                        <li class="item">
                                            <span class="fontWeight--2">Name:</span>
                                            <?php echo $superAdmin_account->first_name; ?>
                                        </li>
                                        <li class="item">
                                            <span class="fontWeight--2">Email:</span>
                                            <?php echo $superAdmin_account->email; ?>
                                        </li>
                                    </ul>
                                </div>
                                <div class="accordion__edit">
                                    <form id="formName" class="form__group" action="<?php echo base_url(); ?>superAdmin-accountName-change" method="post">
                                        <div class="row">
                                            <div class="input__group is--inline">
                                                <input id="accountName" name="accountName" class="input not--empty" type="text" value="<?php echo $superAdmin_account->first_name; ?>" required>
                                                <label class="label" for="accountName">Full Name</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input__group is--inline">
                                                <input id="accountEmail" name="accountEmail" class="input not--empty" type="email" value="<?php echo $superAdmin_account->email; ?>" pattern=.*\S.* required>
                                                <label class="label" for="accountEmail">Email Address</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <input type="hidden" name="profile_edit" value="1">
                                            <button class="btn btn--m btn--primary btn--block save--toggle form--submit page--reload" data-target="#formName">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Account Info -->

                    <!-- Password -->
                    <div class="accordion__group">
                        <div class="accordion__section">
                            <div class="accordion__title wrapper">
                                <div class="wrapper__inner">
                                    <h3>Password</h3>
                                </div>
                                <div class="wrapper__inner align--right">
                                    <a class="link link--expand">Edit</a>
                                </div>
                            </div>
                            <div class="accordion__content">
                                <div class="accordion__preview">
                                    last updated <?php echo $password_last_updated; ?> ago.
                                </div>
                                <div class="accordion__edit">
                                    <form id="formPassword" class="form__group" action="<?php echo base_url(); ?>superAdmin-accountName-change" method="post">
                                        <div class="row">
                                            <div class="input__group is--inline">
                                                <input id="pwCurrent" name="pwCurrent" class="input" type="password" placeholder="" pattern=.*\S.* required>
                                                <label class="label" for="pwCurrent">Current Password</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input__group is--inline">
                                                <input id="password" name="password" class="input" type="password" placeholder="Must be at least 6 characters" minlength="6" required>
                                                <label class="label" for="password">New Password</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input__group is--inline">
                                                <input id="passwordNew" name="passwordNew" class="input" type="password" placeholder="Type your new password again" required>
                                                <label class="label" for="passwordNew">Confirm Password</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <input type="hidden" name="profile_edit" value="2">
                                            <button class="btn btn--m btn--primary btn--block save--toggle form--submit page--reload" data-target="#formPassword" type="submit">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Password -->

                </div>
                <!-- /Content Area -->

            </div>
        </div>
    </section>
</div>
<!-- /Content Section -->

<!-- Modals -->

<?php //include(INCLUDE_PATH . '/_inc/footer-admin.php');?>
<?php $this->load->view('templates/_inc/footer-admin.php'); ?>

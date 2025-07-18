<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>
    <!-- Breadcrumbs Bar -->
    <div class="bar padding--xs bg--lightest-gray">
        <div class="wrapper wrapper--fixed">
            <ul class="list list--inline list--breadcrumbs">
                <li class="item">
                    <a class="link" href="<?php echo base_url('dashboard'); ?>">Account</a>
                </li>
                <li class="item is--active">
                    Your Profile
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breadcrumbs Bar -->
    <!-- Main Content -->
    <section class="content__wrapper wrapper--fixed">
        <div class="content__main">
            <div class="content row">
                <div class="col-md-7 col-xs-12 col--centered">
                    <!-- Name -->
                    <div class="accordion__group">
                        <div class="accordion__section  editname">
                            <div class="accordion__title wrapper">
                                <div class="wrapper__inner col-md-12">
                                    <h3>Name</h3>
                                </div>
                                <div class="wrapper__inner align--right">
                                    <a class="link editprofile link--expand">Edit</a>
                                </div>
                            </div>
                            <div class="accordion__content">
                                <div class="accordion__preview">
                                    <div class="entity__group" style="margin:3px 0 2px 1px;">
                                        <?php
                                        if ($user_image != null) {
                                            ?>
                                            <div class="avatar avatar--l" style="background-image:url('/uploads/user/profile/<?php echo $user_image->photo; ?>'); background-repeat: no-repeat;"></div>
                                            <?php } else { ?>
                                            <div class="avatar avatar--l" style="background-image:url('<?php echo image_url(); ?>assets/img/avatar-default.png');"></div>
                                            <?php } ?>
                                            <div class="text__group">
                                                <?php echo $user_details->first_name; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion__edit">
                                        <form id="formName" class="form__group" action="<?php echo base_url(); ?>update-profile" method="post" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="input__group is--inline col-md-12 c0l-xs-12">
                                                    <input id="accountName" name="accountName" class="input not--empty" type="text" value="<?php echo $user_details->first_name; ?>" required>
                                                    <label class="label" for="accountName">Full Name</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input__group is--inline col-md-12 c0l-xs-12">
                                                    <input id="accountTitle" name="accountTitle" class="input not--empty" type="text" value="<?php echo $user_details->salutation; ?>">
                                                    <label class="label" for="accountTitle">Title (optional)</label>
                                                </div>
                                            </div>
                                            <div class="row border border--dashed border--t border--light border--1 padding--xs no--pad-lr no--pad-b margin--s no--margin-lr">
                                                <h5 class="pl-3">Upload a Profile Image</h5>
                                                <div class="input__group is--inline col-md-12 c0l-xs-12">
                                                    <input id="accountAvatar" name="accountAvatar" class="input input--file not--empty" type="file">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <button class="btn btn--m btn--primary btn--block save--toggle user--profile page--reload" data-target="#formName">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Name -->
                        <!-- Contact Info -->
                        <div class="accordion__group col-md-12 col-xs-12">
                            <div class="accordion__section">
                                <div class="accordion__title wrapper">
                                    <div class="wrapper__inner">
                                        <h3>Contact Information</h3>
                                    </div>
                                    <div class="wrapper__inner align--right">
                                        <a class="link link--expand">Edit</a>
                                    </div>
                                </div>
                                <div class="accordion__content">
                                    <div class="accordion__preview">
                                        <ul class="list list--inline list--divided">
                                            <li class="item">
                                                <span class="fontWeight--2">Email:</span>
                                                <?php echo $user_details->email; ?>
                                            </li>
                                            <li class="item">
                                                <span class="fontWeight--2">Phone:</span>
                                                <?php echo $user_details->phone1; ?>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="accordion__edit">
                                        <form id="formContact" class="form__group" action="<?php echo base_url("update-contact"); ?>" method="post" >
                                            <div class="row">
                                                <div class="input__group is--inline">
                                                    <input id="accountEmail" name="accountEmail" class="input not--empty" type="email" value="<?php echo $user_details->email; ?>"  disabled="">
                                                    <label class="label" for="accountEmail"></label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input__group is--inline">
                                                    <input id="accountPhone" name="accountPhone" class="input input--phone not--empty" type="text" value="<?php echo $user_details->phone1; ?>" required>
                                                    <label class="label" for="accountPhone">Phone Number</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <button class="btn btn--m btn--primary btn--block save--toggle form--submit page--reload" data-target="#formContact">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Contact Info -->
                        <!-- Password -->
                        <div class="accordion__group col-md-12 col-xs-12" id="password_tab">
                            <div class="accordion__section editpassowrd">
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
                                        <br>
                                    </div>
                                    <div class="accordion__edit">
                                        <form id="formPassword" class="form__group" action="<?php echo base_url("change-password"); ?>" method="post">
                                            <div class="row">
                                                <div class="input__group is--inline  col-md-12 col-xs-12">
                                                    <input id="pwCurrent" name="pwCurrent" class="input" type="password" placeholder="" pattern=.*\S.*  required>
                                                    <label class="label pl-4" for="pwCurrent">Current Password</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input__group is--inline  col-md-12 col-xs-12">
                                                    <input id="password" name="password" class="input" type="password" placeholder="Must be at least 6 characters" minlength="6" required>
                                                    <label class="label pl-4" for="password">New Password</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input__group is--inline  col-md-12 col-xs-12">
                                                    <input id="passwordNew" name="passwordNew" class="input" type="password" placeholder="Type your new password again" onChange="checkPasswordMatch();" required>
                                                    <label class="label pl-4" for="passwordNew">Confirm Password</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <button class="btn btn--m btn--primary btn--block  form--submit page--reload" data-target="#formPassword">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- License Info -->
                        <?php
                        $tier1 = unserialize(ROLES_TIER1);
                        $tier1_2 = unserialize(ROLES_TIER1_2);
                        $tier_1_2ab = unserialize(ROLES_TIER1_2_AB);
                        if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $tier_1_2ab))) {
                            ?>
                            <div class=" padding--xxs margin--s no--margin-lr no--margin-t no--pad-t no--pad-lr border--1 border--dashed border--lightest border--b">
                                <div class="wrapper__inner col-md-12">
                                    <h3 class="no--margin textColor--dark-gray">License Information</h3>
                                </div>
                                <div class="wrapper__inner align--right">
                                    <a class="link link--expand fontWeight--2 modal--toggle" data-target="#addNewLicenseModal" style="font-size:14px;">(+) New License</a>
                                </div>
                            </div>
                            <?php if ($license != null) { ?>
                            <div class="license">
                                <!-- License Card Item -->
                                <?php
                                for ($i = 0; $i < count($license); $i++) {
                                    $ex_date = $license[$i]->expire_date;
                                    $expire_date = date("M d, Y", strtotime($ex_date));
                                    if ($license[$i]->approved == 1) {
                                        ?>
                                        <div class="license__card card padding--s is--verified">
                                            <?php } else { ?>
                                            <div class="license__card card padding--s">
                                                <?php } ?>
                                                <ul class="list list--stats  list--inline list--divided">
                                                    <li class="item item--stat stat-s">
                                                        <div class="text__group">
                                                            <span class="line--main"><?php echo $license[$i]->license_no; ?></span>
                                                            <span class="line--sub">License #</span>
                                                        </div>
                                                    </li>
                                                    <li class="item item--stat stat-s">
                                                        <div class="text__group">
                                                            <span class="line--main"><?php echo $license[$i]->dea_no; ?></span>
                                                            <span class="line--sub">DEA #</span>
                                                        </div>
                                                    </li>
                                                    <li class="item item--stat stat-s">
                                                        <div class="text__group">
                                                            <span class="line--main"><?php echo $expire_date; ?></span>
                                                            <span class="line--sub">Expires</span>
                                                        </div>
                                                    </li>
                                                    <li class="item item--stat stat-s">
                                                        <div class="text__group">
                                                            <span class="line--main"><?php echo $license[$i]->state; ?></span>
                                                            <span class="line--sub">State</span>
                                                        </div>
                                                    </li>
                                                    <li class="item item--stat stat-s">
                                                        <button class="btn btn--s btn--icon btn--tertiary btn--link modal--toggle close--tab delete_licence" data-id="<?php echo $license[$i]->id; ?>" data-lno="<?php echo $license[$i]->license_no; ?>" data-ldea="<?php echo $license[$i]->dea_no; ?>" data-ldate="<?php echo $expire_date; ?>" data-lstate="<?php echo $license[$i]->state; ?>"
                                                            data-target="#deleteLicenseModal">
                                                            <svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg>
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                            <!-- /License Card Item -->
                                            <?php } ?>
                                        </div>
                                        <?php } ?>
                                        <?php } ?>
                                        <!-- /License Info -->
                                    </div>
                                </div>
                            </div>
                        </section>
                        <!-- /Main Content -->
                    </div>
                    <!-- /Content Section -->
                    <!-- Modals -->
                    <?php $this->load->view('templates/_inc/shared/modals/add-new-license.php'); ?>
                    <?php $this->load->view('templates/_inc/shared/modals/delete-license-confirmation.php'); ?>
<!-- <?php include(INCLUDE_PATH . '/_inc/shared/modals/add-new-license.php'); ?>
    <?php include(INCLUDE_PATH . '/_inc/shared/modals/delete-license-confirmation.php'); ?> -->
    <script>
        function checkPasswordMatch() {
            var password = $("#password").val();
            var confirmPassword = $("#passwordNew").val();
            if (password != confirmPassword) {
                alert("Passwords do not match.");
                $("#password").val("");
                $("#passwordNew").val("");
                return false;
            }
        }
    </script>
<!-- /Password -->
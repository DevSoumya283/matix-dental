<?php $this->load->view('templates/_inc/header-vendor.php'); ?>
<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>
    <section class="content__wrapper has--sidebar-l">
        <div class="content__main">
            <div class="row row--full-height">

                <!-- Sidebar -->
                <div class="sidebar col col--2-of-12">
                    <?php $this->load->view('templates/vendor-admin/_inc/nav.php'); ?>
                </div>
                <!-- /Sidebar -->

                <!-- Content Area -->
                <div class="content col col--9-of-12 col--push-1-of-12">

                    <!-- Company Name -->
                    <div class="accordion__group">
                        <div class="accordion__section">
                            <div class="accordion__title wrapper">
                                <div class="wrapper__inner">
                                    <h3>Company Name</h3>
                                </div>
                                <div class="wrapper__inner align--right">
                                    <a class="link link--expand">Edit</a>
                                </div>
                            </div>
                            <div class="accordion__content">
                                <div class="accordion__preview">
                                    <div class="entity__group">
                                        <div class="vendor__logo" style="width:160px;">
                                            <?php if ($company_logo != null && $company_logo != "") { ?>
                                                <img src="<?php echo image_url(); ?>uploads/vendor/logo/<?php echo $company_logo->photo; ?>" alt="<?php echo $company_logo->photo; ?>">
                                            <?php } else { ?>
                                                <img src="<?php echo image_url(); ?>assets/img/product-image.png" alt="">
                                            <?php } ?>
                                        </div>
                                        <?php echo $vendor_settings->name; ?>
                                    </div>
                                </div>
                                <div class="accordion__edit">
                                    <form id="formCompanyName" class="form__group" action="<?php echo base_url(); ?>vendor-company-details" method="post" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="input__group is--inline">
                                                <input type="hidden" name="vendor_id" value="<?php echo $vendor_settings->id; ?>">
                                                <input id="companyName" name="companyName" class="input not--empty" type="text" value="<?php echo $vendor_settings->name; ?>" required>
                                                <label class="label" for="companyName">Company Name</label>
                                            </div>
                                        </div>
                                        <div class="row border border--dashed border--t border--light border--1 padding--xs no--pad-lr no--pad-b margin--s no--margin-lr">
                                            <h5>Upload Company Logo</h5>
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

                    <!-- Short Bio -->
                    <div class="accordion__group">
                        <div class="accordion__section">
                            <div class="accordion__title wrapper">
                                <div class="wrapper__inner">
                                    <h3>Short Bio</h3>
                                </div>
                                <div class="wrapper__inner align--right">
                                    <a class="link link--expand">Edit</a>
                                </div>
                            </div>
                            <div class="accordion__content">
                                <div class="accordion__preview">
                                    <?php echo $vendor_settings->short_description; ?>
                                </div>
                                <div class="accordion__edit">
                                    <form id="companyShortBioForm" class="form__group" action="<?php echo base_url(); ?>vendor-settings-vendorBio" method="post">
                                        <div class="row">
                                            <input type="hidden" name="vendor_id" value="<?php echo $vendor_settings->id; ?>">
                                            <textarea class="input input--l"  name="short_description" placeholder="Enter your short bio..." required><?php echo $vendor_settings->short_description; ?></textarea>
                                        </div>
                                        <div class="row">
                                            <button class="btn btn--primary btn--m btn--block form--submit save--toggle">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Short Bio -->
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
                                    <form id="formPassword" class="form__group" action="<?php echo base_url(); ?>vendor-settings-password-update" method="post">
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
                                            <button class="btn btn--m btn--primary btn--block save--toggle" type="submit">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Password -->
                    <!-- Email Settings -->
                    <div class="accordion__group">
                        <div class="accordion__section">
                            <div class="accordion__title wrapper">
                                <div class="wrapper__inner">
                                    <h3>Email settings</h3>
                                </div>
                                <div class="wrapper__inner align--right">
                                    <a class="link link--expand">Edit</a>
                                </div>
                            </div>
                            <div class="accordion__content">
                                <div class="accordion__preview">
                                    <label class="control control__checkbox">
                                        <input type="checkbox" disabled="" name="e3_v" value="1" <?php if ($user->email_setting3 == 1) echo 'checked'; ?>>
                                        <div class="control__indicator"></div>
                                        Receive “New Message” Notification
                                    </label>
                                    <li class="item">
                                        <label class="control control__checkbox">
                                            <input type="checkbox" disabled="" name="e6_v"  value="1" <?php if ($user->email_setting6 == 1) echo 'checked'; ?>>
                                            <div class="control__indicator"></div>
                                            Receive “Newsletter”
                                        </label>
                                    </li>
                                    <li class="item">
                                        <label class="control control__checkbox">
                                            <input type="checkbox" name="e2"  value="1" <?php if ($user->email_setting2 == 1) echo 'checked'; ?>>
                                            <div class="control__indicator"></div>
                                            Receive “New Order” Notification
                                        </label>
                                    </li>
                                </div>
                                <div class="accordion__edit">
                                    <form id="companyShortBioForm" class="form__group" action="<?php echo base_url('vendor-email-settings'); ?>" method="post">
                                        <div class="row">
                                            <input type="hidden" name="vendor_id" value="<?php echo $vendor_settings->id; ?>">
                                            <ul class="list">

                                                <li class="item">
                                                    <label class="control control__checkbox">
                                                        <input type="checkbox" name="e3" value="1" <?php if ($user->email_setting3 == 1) echo 'checked'; ?>>
                                                        <div class="control__indicator"></div>
                                                        Receive “New Message” Notification
                                                    </label>
                                                </li>

                                                <li class="item">
                                                    <label class="control control__checkbox">
                                                        <input type="checkbox" name="e6"  value="1" <?php if ($user->email_setting6 == 1) echo 'checked'; ?>>
                                                        <div class="control__indicator"></div>
                                                        Receive “Newsletter”
                                                    </label>
                                                </li>
                                                <li class="item">
                                                    <label class="control control__checkbox">
                                                        <input type="checkbox" name="e2"  value="1" <?php if ($user->email_setting2 == 1) echo 'checked'; ?>>
                                                        <div class="control__indicator"></div>
                                                        Receive “New Order” Notification
                                                    </label>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="row">
                                            <button class="btn btn--primary btn--m btn--block form--submit save--toggle">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Email Settings -->
                    <!-- Short Bio -->
                    <div class="accordion__group">
                        <div class="accordion__section">
                            <div class="accordion__title wrapper">
                                <div class="wrapper__inner">
                                    <h3>About Us</h3>
                                </div>
                                <div class="wrapper__inner align--right">
                                    <a class="link link--expand">Edit</a>
                                </div>
                            </div>
                            <div class="accordion__content">
                                <div class="accordion__preview">
                                    <?php echo $vendor_settings->description; ?>
                                </div>
                                <div class="accordion__edit">
                                    <form id="companyAboutForm" class="form__group" action="<?php echo base_url(); ?>vendor-settings-aboutUs" method="post">
                                        <div class="row">
                                            <input type="hidden" name="vendor_id" value="<?php echo $vendor_settings->id; ?>">
                                            <textarea name="description" class="input input--l" placeholder="Enter your short bio..." required><?php echo $vendor_settings->description; ?></textarea>
                                        </div>
                                        <div class="row">
                                            <button class="btn btn--primary btn--m btn--block form--submit">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Short Bio -->
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
                                        <div class="vendor__logo" style="width:160px;">
                                            <?php if ($profile_photo != null && $profile_photo != "") { ?>
                                                <img src="<?php echo image_url(); ?>uploads/user/profile/<?php echo $profile_photo->photo; ?>" alt="<?php echo $profile_photo->photo; ?>">
                                            <?php } else { ?>

                                                <img src="<?php echo image_url(); ?>/assets/img/avatar-default.png" alt="">

                                                <!--                                                <a class="product__thumb" href="#" style="background-image:url('http://placehold.it/192x192');"></a>-->
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion__edit">
                                    <form id="formCompanyName" class="form__group" action="<?php echo base_url(); ?>vendor-user-profile" method="post" enctype="multipart/form-data">
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
                    <!-- Personal Address -->
                    <div id="personalAddress" class="accordion__group">
                        <div class="accordion__section">
                            <div class="accordion__title wrapper">
                                <div class="wrapper__inner">
                                    <h3>Personal Address</h3>
                                </div>
                                <div class="wrapper__inner align--right">
                                    <a class="link link--expand">Edit</a>
                                </div>
                            </div>
                            <div class="accordion__content">
                                <div class="accordion__preview">
                                    <?php echo ($vendor_settings->personal_address1 != null) ? ucfirst($vendor_settings->personal_address1) : "" ?><?php echo ($vendor_settings->personal_city != null && trim($vendor_settings->personal_city) != "") ? ", " : "" ?><?php echo ($vendor_settings->personal_city != null) ? ucfirst($vendor_settings->personal_city) : "" ?><?php echo ($vendor_settings->personal_zip != null && trim($vendor_settings->personal_zip) != "") ? ", " : " "; ?><?php echo ($vendor_settings->personal_state != null) ? ucfirst($vendor_settings->personal_state . " ") : ""; ?><?php echo ($vendor_settings->personal_zip != null) ? ucfirst($vendor_settings->personal_zip) : ""; ?>
                                </div>
                                <div class="accordion__edit">
                                    <form id="companyAddressForm" class="form__group" action="<?php echo base_url(); ?>vendor-settings-update-personal" method="post">
                                        <div class="row">
                                            <div class="input__group is--inline">
                                                <input  name="vendor_id" class="input not--empty" type="hidden" value="<?php echo $vendor_settings->id; ?>" required>
                                                <input id="personalAddress1" name="personalAddress1" class="input not--empty" type="text" value="<?php echo $vendor_settings->personal_address1; ?>" required>
                                                <label class="label" for="personalAddress1">Address Line 1</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col col--1-of-2">
                                                <div class="input__group is--inline">
                                                    <input id="personalAddress2" name="personalAddress2" class="input not--empty" type="text" value="<?php echo $vendor_settings->personal_address2; ?>" required>
                                                    <label class="label" for="personalAddress2">Unit/Suite/#</label>
                                                </div>
                                            </div>
                                            <div class="col col--1-of-2">
                                                <div class="input__group is--inline">
                                                    <input id="personalCity" name="personalcity" class="input not--empty" type="text" value="<?php echo $vendor_settings->personal_city; ?>" required>
                                                    <label class="label" for="city">City</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col col--1-of-2">
                                                <div class="select">
                                                    <select name="personal_state" required>
                                                        <option value="default" disabled="" selected>Choose State</option>
                                                        <option value="AL"<?php echo ($vendor_settings->personal_state == "AL" ? "selected" : ""); ?> >Alabama</option>
                                                        <option value="AK"<?php echo ($vendor_settings->personal_state == "AK" ? "selected" : ""); ?>>Alaska</option>
                                                        <option value="AZ"<?php echo ($vendor_settings->personal_state == "AZ" ? "selected" : ""); ?>>Arizona</option>
                                                        <option value="AR"<?php echo ($vendor_settings->personal_state == "AR" ? "selected" : ""); ?>>Arkansas</option>
                                                        <option value="CA"<?php echo ($vendor_settings->personal_state == "CA" ? "selected" : ""); ?>>California</option>
                                                        <option value="CO"<?php echo ($vendor_settings->personal_state == "CO" ? "selected" : ""); ?>>Colorado</option>
                                                        <option value="CT"<?php echo ($vendor_settings->personal_state == "CT" ? "selected" : ""); ?>>Connecticut</option>
                                                        <option value="DE"<?php echo ($vendor_settings->personal_state == "DE" ? "selected" : ""); ?>>Delaware</option>
                                                        <option value="DC"<?php echo ($vendor_settings->personal_state == "DC" ? "selected" : ""); ?>>District Of Columbia</option>
                                                        <option value="FL"<?php echo ($vendor_settings->personal_state == "FL" ? "selected" : ""); ?>>Florida</option>
                                                        <option value="GA"<?php echo ($vendor_settings->personal_state == "GA" ? "selected" : ""); ?>>Georgia</option>
                                                        <option value="HI"<?php echo ($vendor_settings->personal_state == "HI" ? "selected" : ""); ?>>Hawaii</option>
                                                        <option value="ID"<?php echo ($vendor_settings->personal_state == "ID" ? "selected" : ""); ?>>Idaho</option>
                                                        <option value="IL"<?php echo ($vendor_settings->personal_state == "IL" ? "selected" : ""); ?>>Illinois</option>
                                                        <option value="IN"<?php echo ($vendor_settings->personal_state == "IN" ? "selected" : ""); ?>>Indiana</option>
                                                        <option value="IA"<?php echo ($vendor_settings->personal_state == "IA" ? "selected" : ""); ?>>Iowa</option>
                                                        <option value="KS"<?php echo ($vendor_settings->personal_state == "KS" ? "selected" : ""); ?>>Kansas</option>
                                                        <option value="KY"<?php echo ($vendor_settings->personal_state == "KY" ? "selected" : ""); ?>>Kentucky</option>
                                                        <option value="LA"<?php echo ($vendor_settings->personal_state == "LA" ? "selected" : ""); ?>>Louisiana</option>
                                                        <option value="ME"<?php echo ($vendor_settings->personal_state == "ME" ? "selected" : ""); ?>>Maine</option>
                                                        <option value="MD"<?php echo ($vendor_settings->personal_state == "MD" ? "selected" : ""); ?>>Maryland</option>
                                                        <option value="MA"<?php echo ($vendor_settings->personal_state == "MA" ? "selected" : ""); ?>>Massachusetts</option>
                                                        <option value="MI"<?php echo ($vendor_settings->personal_state == "MI" ? "selected" : ""); ?>>Michigan</option>
                                                        <option value="MN"<?php echo ($vendor_settings->personal_state == "MN" ? "selected" : ""); ?>>Minnesota</option>
                                                        <option value="MS"<?php echo ($vendor_settings->personal_state == "MS" ? "selected" : ""); ?>>Mississippi</option>
                                                        <option value="MO"<?php echo ($vendor_settings->personal_state == "MO" ? "selected" : ""); ?>>Missouri</option>
                                                        <option value="MT"<?php echo ($vendor_settings->personal_state == "MT" ? "selected" : ""); ?>>Montana</option>
                                                        <option value="NE"<?php echo ($vendor_settings->personal_state == "NE" ? "selected" : ""); ?>>Nebraska</option>
                                                        <option value="NV"<?php echo ($vendor_settings->personal_state == "NV" ? "selected" : ""); ?>>Nevada</option>
                                                        <option value="NH"<?php echo ($vendor_settings->personal_state == "NH" ? "selected" : ""); ?>>New Hampshire</option>
                                                        <option value="NJ"<?php echo ($vendor_settings->personal_state == "NJ" ? "selected" : ""); ?>>New Jersey</option>
                                                        <option value="NM"<?php echo ($vendor_settings->personal_state == "NM" ? "selected" : ""); ?>>New Mexico</option>
                                                        <option value="NY"<?php echo ($vendor_settings->personal_state == "NY" ? "selected" : ""); ?>>New York</option>
                                                        <option value="NC"<?php echo ($vendor_settings->personal_state == "NC" ? "selected" : ""); ?>>North Carolina</option>
                                                        <option value="ND"<?php echo ($vendor_settings->personal_state == "ND" ? "selected" : ""); ?>>North Dakota</option>
                                                        <option value="OH"<?php echo ($vendor_settings->personal_state == "OH" ? "selected" : ""); ?>>Ohio</option>
                                                        <option value="OK"<?php echo ($vendor_settings->personal_state == "OK" ? "selected" : ""); ?>>Oklahoma</option>
                                                        <option value="OR"<?php echo ($vendor_settings->personal_state == "OR" ? "selected" : ""); ?>>Oregon</option>
                                                        <option value="PA"<?php echo ($vendor_settings->personal_state == "PA" ? "selected" : ""); ?>>Pennsylvania</option>
                                                        <option value="RI"<?php echo ($vendor_settings->personal_state == "RI" ? "selected" : ""); ?>>Rhode Island</option>
                                                        <option value="SC"<?php echo ($vendor_settings->personal_state == "SC" ? "selected" : ""); ?>>South Carolina</option>
                                                        <option value="SD"<?php echo ($vendor_settings->personal_state == "SD" ? "selected" : ""); ?>>South Dakota</option>
                                                        <option value="TN"<?php echo ($vendor_settings->personal_state == "TN" ? "selected" : ""); ?>>Tennessee</option>
                                                        <option value="TX"<?php echo ($vendor_settings->personal_state == "TX" ? "selected" : ""); ?>>Texas</option>
                                                        <option value="UT"<?php echo ($vendor_settings->personal_state == "UT" ? "selected" : ""); ?>>Utah</option>
                                                        <option value="VT"<?php echo ($vendor_settings->personal_state == "VT" ? "selected" : ""); ?>>Vermont</option>
                                                        <option value="VA"<?php echo ($vendor_settings->personal_state == "VA" ? "selected" : ""); ?>>Virginia</option>
                                                        <option value="WA"<?php echo ($vendor_settings->personal_state == "WA" ? "selected" : ""); ?>>Washington</option>
                                                        <option value="WV"<?php echo ($vendor_settings->personal_state == "WV" ? "selected" : ""); ?>>West Virginia</option>
                                                        <option value="WI"<?php echo ($vendor_settings->personal_state == "WI" ? "selected" : ""); ?>>Wisconsin</option>
                                                        <option value="WY"<?php echo ($vendor_settings->personal_state == "WY" ? "selected" : ""); ?>>Wyoming</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col col--1-of-2">
                                                <div class="input__group is--inline">
                                                    <input id="personalZip" name="personalZip" class="input not--empty" type="text" placeholder="90210" value="<?php echo $vendor_settings->personal_zip; ?>" required>
                                                    <label class="label" for="Zip">Zip</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <button class="btn btn--primary btn--m btn--block form--submit save--toggle">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Personal Address -->
                    <!-- Contact Information -->
                    <div class="accordion__group">
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
                                            <span class="fontWeight--2">Phone:</span>
                                            <?php
                                            $phone = ($vendor_settings->phone != 0) ? $vendor_settings->phone : "-";
                                            $areacode = substr($phone, 0, 3);
                                            $prefix = substr($phone, 3, 3);
                                            $number = substr($phone, 6, 4);
                                            echo $phone_number = "(" . $areacode . ")" . $prefix . "-" . $number;
                                            ?>
                                        </li>
                                        <li class="item">
                                            <span class="fontWeight--2">Email:</span> <?php echo $vendor_settings->email; ?>
                                        </li>
                                    </ul>
                                </div>
                                <div class="accordion__edit">
                                    <form id="formCompanyContact" class="form__group" action="<?php base_url(); ?>vendorSettings-ContactUpdate" method="post">
                                        <div class="row">
                                            <div class="input__group is--inline">
                                                <input type="hidden" name="vendor_id" value="<?php echo $vendor_settings->id; ?>">
                                                <input id="companyPhone" name="companyPhone" class="input input--phone not--empty" type="text" value="<?php echo $vendor_settings->phone; ?>" required>
                                                <label class="label" for="companyPhone">Phone Number</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input__group is--inline">
                                                <input id="companyEmail" name="companyEmail" class="input not--empty" type="email" value="<?php echo $vendor_settings->email; ?>" required>
                                                <label class="label" for="companyEmail">Email Address</label>
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
                    <!-- /Contact Information -->

                    <!-- Business Hours -->
                    <div class="accordion__group">
                        <div class="accordion__section">
                            <div class="accordion__title wrapper">
                                <div class="wrapper__inner">
                                    <h3>Business Hours</h3>
                                </div>
                                <div class="wrapper__inner align--right">
                                    <a class="link fontWeight--2 fontSize--s modal--toggle" data-target="#editHoursModal">Edit</a>
                                </div>
                            </div>
                            <div class="accordion__content">
                                <div class="accordion__preview">
                                    <ul class="list list--inline list--divided">
                                        <?php
                                        if ($business != null) {
                                            for ($i = 0; $i < count($business); $i++) {
                                                ?>
                                                <li class="item">
                                                    <span class="fontWeight--2">
                                                        <?php echo substr_replace($business[$i]->day, "", 3) ?><?php for ($j = 0; $j < count($all_business); $j++) { ?><?php if ($business[$i]->day != $all_business[$j]->day && $business[$i]->open_time == $all_business[$j]->open_time && $business[$i]->close_time == $all_business[$j]->close_time) { ?>, <?php echo substr_replace($all_business[$j]->day, "", 3) ?><?php } ?><?php } ?>
                                                    </span>
                                                    &ndash; <?php echo date('g:i A', strtotime($business[$i]->open_time)) . " - " ?>
                                                    <?php echo date('g:i A', strtotime($business[$i]->close_time)) ?>
                                                </li>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Business Hours -->

                    <!-- Business Address -->
                    <div class="accordion__group">
                        <div class="accordion__section">
                            <div class="accordion__title wrapper">
                                <div class="wrapper__inner">
                                    <h3>Business Address</h3>
                                </div>
                                <div class="wrapper__inner align--right">
                                    <a class="link link--expand">Edit</a>
                                </div>
                            </div>
                            <div class="accordion__content">
                                <div class="accordion__preview">
                                    <?php echo ($vendor_settings->address1 != null) ? ucfirst($vendor_settings->address1) : "" ?><?php echo ($vendor_settings->address2 != null && trim($vendor_settings->address2) != "") ? ", " : "" ?><?php echo ($vendor_settings->address2 != null) ? ucfirst($vendor_settings->address2) : "" ?><?php echo ($vendor_settings->city != null && trim($vendor_settings->city) != "") ? ", " : "" ?><?php echo ($vendor_settings->city != null) ? ucfirst($vendor_settings->city) : "" ?><?php echo ($vendor_settings->state != null && trim($vendor_settings->state) != "") ? ", " : "" ?><?php echo ($vendor_settings->state != null) ? ucfirst($vendor_settings->state) : "" ?><?php echo ($vendor_settings->zip != null && trim($vendor_settings->zip) != "") ? " " : ""; ?><?php echo ($vendor_settings->zip != null) ? ucfirst($vendor_settings->zip) : ""; ?>
                                </div>
                                <div class="accordion__edit">
                                    <form id="businessAddressForm" class="form__group" action="<?php echo base_url(); ?>vendor-settings-businessAddress" method="post">
                                        <div class="row">
                                            <div class="input__group is--inline">
                                                <input type="hidden" name="vendor_id" value="<?php echo $vendor_settings->id; ?>">
                                                <input id="businessAddress1" name="businessAddress1" class="input not--empty" type="text" value="<?php echo $vendor_settings->address1; ?>" required>
                                                <label class="label" for="businessAddress1">Address Line 1</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col col--1-of-2">
                                                <div class="input__group is--inline">
                                                    <input id="businessAddress2" name="businessAddress2" class="input not--empty" type="text" value="<?php echo $vendor_settings->address2; ?>" required>
                                                    <label class="label" for="businessAddress2">Unit/Suite/#</label>
                                                </div>
                                            </div>
                                            <div class="col col--1-of-2">
                                                <div class="input__group is--inline">
                                                    <input id="businessCity" name="businessCity" class="input not--empty" type="text" placeholder="Los Angeles" value="<?php echo $vendor_settings->city; ?>" required>
                                                    <label class="label" for="businessCity">City</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col col--1-of-2">
                                                <div class="select">
                                                    <select name="state" required>
                                                        <option value="default" disabled="" selected>Choose State</option>
                                                        <option value="AL"<?php echo ($vendor_settings->state == "AL" ? "selected" : ""); ?> >Alabama</option>
                                                        <option value="AK"<?php echo ($vendor_settings->state == "AK" ? "selected" : ""); ?>>Alaska</option>
                                                        <option value="AZ"<?php echo ($vendor_settings->state == "AZ" ? "selected" : ""); ?>>Arizona</option>
                                                        <option value="AR"<?php echo ($vendor_settings->state == "AR" ? "selected" : ""); ?>>Arkansas</option>
                                                        <option value="CA"<?php echo ($vendor_settings->state == "CA" ? "selected" : ""); ?>>California</option>
                                                        <option value="CO"<?php echo ($vendor_settings->state == "CO" ? "selected" : ""); ?>>Colorado</option>
                                                        <option value="CT"<?php echo ($vendor_settings->state == "CT" ? "selected" : ""); ?>>Connecticut</option>
                                                        <option value="DE"<?php echo ($vendor_settings->state == "DE" ? "selected" : ""); ?>>Delaware</option>
                                                        <option value="DC"<?php echo ($vendor_settings->state == "DC" ? "selected" : ""); ?>>District Of Columbia</option>
                                                        <option value="FL"<?php echo ($vendor_settings->state == "FL" ? "selected" : ""); ?>>Florida</option>
                                                        <option value="GA"<?php echo ($vendor_settings->state == "GA" ? "selected" : ""); ?>>Georgia</option>
                                                        <option value="HI"<?php echo ($vendor_settings->state == "HI" ? "selected" : ""); ?>>Hawaii</option>
                                                        <option value="ID"<?php echo ($vendor_settings->state == "ID" ? "selected" : ""); ?>>Idaho</option>
                                                        <option value="IL"<?php echo ($vendor_settings->state == "IL" ? "selected" : ""); ?>>Illinois</option>
                                                        <option value="IN"<?php echo ($vendor_settings->state == "IN" ? "selected" : ""); ?>>Indiana</option>
                                                        <option value="IA"<?php echo ($vendor_settings->state == "IA" ? "selected" : ""); ?>>Iowa</option>
                                                        <option value="KS"<?php echo ($vendor_settings->state == "KS" ? "selected" : ""); ?>>Kansas</option>
                                                        <option value="KY"<?php echo ($vendor_settings->state == "KY" ? "selected" : ""); ?>>Kentucky</option>
                                                        <option value="LA"<?php echo ($vendor_settings->state == "LA" ? "selected" : ""); ?>>Louisiana</option>
                                                        <option value="ME"<?php echo ($vendor_settings->state == "ME" ? "selected" : ""); ?>>Maine</option>
                                                        <option value="MD"<?php echo ($vendor_settings->state == "MD" ? "selected" : ""); ?>>Maryland</option>
                                                        <option value="MA"<?php echo ($vendor_settings->state == "MA" ? "selected" : ""); ?>>Massachusetts</option>
                                                        <option value="MI"<?php echo ($vendor_settings->state == "MI" ? "selected" : ""); ?>>Michigan</option>
                                                        <option value="MN"<?php echo ($vendor_settings->state == "MN" ? "selected" : ""); ?>>Minnesota</option>
                                                        <option value="MS"<?php echo ($vendor_settings->state == "MS" ? "selected" : ""); ?>>Mississippi</option>
                                                        <option value="MO"<?php echo ($vendor_settings->state == "MO" ? "selected" : ""); ?>>Missouri</option>
                                                        <option value="MT"<?php echo ($vendor_settings->state == "MT" ? "selected" : ""); ?>>Montana</option>
                                                        <option value="NE"<?php echo ($vendor_settings->state == "NE" ? "selected" : ""); ?>>Nebraska</option>
                                                        <option value="NV"<?php echo ($vendor_settings->state == "NV" ? "selected" : ""); ?>>Nevada</option>
                                                        <option value="NH"<?php echo ($vendor_settings->state == "NH" ? "selected" : ""); ?>>New Hampshire</option>
                                                        <option value="NJ"<?php echo ($vendor_settings->state == "NJ" ? "selected" : ""); ?>>New Jersey</option>
                                                        <option value="NM"<?php echo ($vendor_settings->state == "NM" ? "selected" : ""); ?>>New Mexico</option>
                                                        <option value="NY"<?php echo ($vendor_settings->state == "NY" ? "selected" : ""); ?>>New York</option>
                                                        <option value="NC"<?php echo ($vendor_settings->state == "NC" ? "selected" : ""); ?>>North Carolina</option>
                                                        <option value="ND"<?php echo ($vendor_settings->state == "ND" ? "selected" : ""); ?>>North Dakota</option>
                                                        <option value="OH"<?php echo ($vendor_settings->state == "OH" ? "selected" : ""); ?>>Ohio</option>
                                                        <option value="OK"<?php echo ($vendor_settings->state == "OK" ? "selected" : ""); ?>>Oklahoma</option>
                                                        <option value="OR"<?php echo ($vendor_settings->state == "OR" ? "selected" : ""); ?>>Oregon</option>
                                                        <option value="PA"<?php echo ($vendor_settings->state == "PA" ? "selected" : ""); ?>>Pennsylvania</option>
                                                        <option value="RI"<?php echo ($vendor_settings->state == "RI" ? "selected" : ""); ?>>Rhode Island</option>
                                                        <option value="SC"<?php echo ($vendor_settings->state == "SC" ? "selected" : ""); ?>>South Carolina</option>
                                                        <option value="SD"<?php echo ($vendor_settings->state == "SD" ? "selected" : ""); ?>>South Dakota</option>
                                                        <option value="TN"<?php echo ($vendor_settings->state == "TN" ? "selected" : ""); ?>>Tennessee</option>
                                                        <option value="TX"<?php echo ($vendor_settings->state == "TX" ? "selected" : ""); ?>>Texas</option>
                                                        <option value="UT"<?php echo ($vendor_settings->state == "UT" ? "selected" : ""); ?>>Utah</option>
                                                        <option value="VT"<?php echo ($vendor_settings->state == "VT" ? "selected" : ""); ?>>Vermont</option>
                                                        <option value="VA"<?php echo ($vendor_settings->state == "VA" ? "selected" : ""); ?>>Virginia</option>
                                                        <option value="WA"<?php echo ($vendor_settings->state == "WA" ? "selected" : ""); ?>>Washington</option>
                                                        <option value="WV"<?php echo ($vendor_settings->state == "WV" ? "selected" : ""); ?>>West Virginia</option>
                                                        <option value="WI"<?php echo ($vendor_settings->state == "WI" ? "selected" : ""); ?>>Wisconsin</option>
                                                        <option value="WY"<?php echo ($vendor_settings->state == "WY" ? "selected" : ""); ?>>Wyoming</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col col--1-of-2">
                                                <div class="input__group is--inline">
                                                    <input id="businessZip" name="businessZip" class="input not--empty" type="text" placeholder="90210" value="<?php echo $vendor_settings->zip; ?>" required>
                                                    <label class="label" for="businessZip">Zip</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <button class="btn btn--primary btn--m btn--block form--submit">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Business Address -->

                    <!-- Shipping Address -->
                    <div class="accordion__group">
                        <div class="accordion__section">
                            <div class="accordion__title wrapper">
                                <div class="wrapper__inner">
                                    <h3>Shipping Address</h3>
                                </div>
                                <div class="wrapper__inner align--right">
                                    <a class="link link--expand">Edit</a>
                                </div>
                            </div>
                            <div class="accordion__content">
                                <div class="accordion__preview">
                                    <?php echo ($vendor_settings->shipment_address1 != null) ? ucfirst($vendor_settings->shipment_address1) : "" ?><?php echo ($vendor_settings->shipment_address2 != null && trim($vendor_settings->shipment_address2) != "") ? ", " : "" ?><?php echo ($vendor_settings->shipment_address2 != null) ? ucfirst($vendor_settings->shipment_address2) : "" ?><?php echo ($vendor_settings->shipment_city != null && trim($vendor_settings->shipment_city) != "") ? ", " : "" ?><?php echo ($vendor_settings->shipment_city != null) ? ucfirst($vendor_settings->shipment_city) : "" ?><?php echo ($vendor_settings->shipment_zip != null && trim($vendor_settings->shipment_zip) != "") ? ", " : ""; ?><?php echo ($vendor_settings->shipment_state != null) ? ucfirst($vendor_settings->shipment_state . " ") : ""; ?><?php echo ($vendor_settings->shipment_zip != null) ? ucfirst($vendor_settings->shipment_zip) : ""; ?>
                                </div>
                                <div class="accordion__edit">
                                    <form id="companyAddressForm" class="form__group" action="<?php echo base_url(); ?>vendor-settings-update-shipping" method="post">
                                        <div class="row">
                                            <div class="input__group is--inline">
                                                <input  name="vendor_id" class="input not--empty" type="hidden" value="<?php echo $vendor_settings->id; ?>" required>
                                                <input id="companyAddress1" name="companyAddress1" class="input not--empty" type="text" value="<?php echo $vendor_settings->shipment_address1; ?>" required>
                                                <label class="label" for="companyAddress1">Address Line 1</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col col--1-of-2">
                                                <div class="input__group is--inline">
                                                    <input id="companyAddress2" name="companyAddress2" class="input" type="text" value="<?php echo $vendor_settings->shipment_address2; ?>">
                                                    <label class="label" for="companyAddress2">Unit/Suite/#</label>
                                                </div>
                                            </div>
                                            <div class="col col--1-of-2">
                                                <div class="input__group is--inline">
                                                    <input id="companyCity" name="companyCity" class="input not--empty" type="text" placeholder="Los Angeles" value="<?php echo $vendor_settings->shipment_city; ?>" required>
                                                    <label class="label" for="companyCity">City</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col col--1-of-2">
                                                <div class="select">
                                                    <select name="shipment_state" required>
                                                        <option value="default" disabled="" selected>Choose State</option>
                                                        <option value="AL"<?php echo ($vendor_settings->shipment_state == "AL" ? "selected" : ""); ?> >Alabama</option>
                                                        <option value="AK"<?php echo ($vendor_settings->shipment_state == "AK" ? "selected" : ""); ?>>Alaska</option>
                                                        <option value="AZ"<?php echo ($vendor_settings->shipment_state == "AZ" ? "selected" : ""); ?>>Arizona</option>
                                                        <option value="AR"<?php echo ($vendor_settings->shipment_state == "AR" ? "selected" : ""); ?>>Arkansas</option>
                                                        <option value="CA"<?php echo ($vendor_settings->shipment_state == "CA" ? "selected" : ""); ?>>California</option>
                                                        <option value="CO"<?php echo ($vendor_settings->shipment_state == "CO" ? "selected" : ""); ?>>Colorado</option>
                                                        <option value="CT"<?php echo ($vendor_settings->shipment_state == "CT" ? "selected" : ""); ?>>Connecticut</option>
                                                        <option value="DE"<?php echo ($vendor_settings->shipment_state == "DE" ? "selected" : ""); ?>>Delaware</option>
                                                        <option value="DC"<?php echo ($vendor_settings->shipment_state == "DC" ? "selected" : ""); ?>>District Of Columbia</option>
                                                        <option value="FL"<?php echo ($vendor_settings->shipment_state == "FL" ? "selected" : ""); ?>>Florida</option>
                                                        <option value="GA"<?php echo ($vendor_settings->shipment_state == "GA" ? "selected" : ""); ?>>Georgia</option>
                                                        <option value="HI"<?php echo ($vendor_settings->shipment_state == "HI" ? "selected" : ""); ?>>Hawaii</option>
                                                        <option value="ID"<?php echo ($vendor_settings->shipment_state == "ID" ? "selected" : ""); ?>>Idaho</option>
                                                        <option value="IL"<?php echo ($vendor_settings->shipment_state == "IL" ? "selected" : ""); ?>>Illinois</option>
                                                        <option value="IN"<?php echo ($vendor_settings->shipment_state == "IN" ? "selected" : ""); ?>>Indiana</option>
                                                        <option value="IA"<?php echo ($vendor_settings->shipment_state == "IA" ? "selected" : ""); ?>>Iowa</option>
                                                        <option value="KS"<?php echo ($vendor_settings->shipment_state == "KS" ? "selected" : ""); ?>>Kansas</option>
                                                        <option value="KY"<?php echo ($vendor_settings->shipment_state == "KY" ? "selected" : ""); ?>>Kentucky</option>
                                                        <option value="LA"<?php echo ($vendor_settings->shipment_state == "LA" ? "selected" : ""); ?>>Louisiana</option>
                                                        <option value="ME"<?php echo ($vendor_settings->shipment_state == "ME" ? "selected" : ""); ?>>Maine</option>
                                                        <option value="MD"<?php echo ($vendor_settings->shipment_state == "MD" ? "selected" : ""); ?>>Maryland</option>
                                                        <option value="MA"<?php echo ($vendor_settings->shipment_state == "MA" ? "selected" : ""); ?>>Massachusetts</option>
                                                        <option value="MI"<?php echo ($vendor_settings->shipment_state == "MI" ? "selected" : ""); ?>>Michigan</option>
                                                        <option value="MN"<?php echo ($vendor_settings->shipment_state == "MN" ? "selected" : ""); ?>>Minnesota</option>
                                                        <option value="MS"<?php echo ($vendor_settings->shipment_state == "MS" ? "selected" : ""); ?>>Mississippi</option>
                                                        <option value="MO"<?php echo ($vendor_settings->shipment_state == "MO" ? "selected" : ""); ?>>Missouri</option>
                                                        <option value="MT"<?php echo ($vendor_settings->shipment_state == "MT" ? "selected" : ""); ?>>Montana</option>
                                                        <option value="NE"<?php echo ($vendor_settings->shipment_state == "NE" ? "selected" : ""); ?>>Nebraska</option>
                                                        <option value="NV"<?php echo ($vendor_settings->shipment_state == "NV" ? "selected" : ""); ?>>Nevada</option>
                                                        <option value="NH"<?php echo ($vendor_settings->shipment_state == "NH" ? "selected" : ""); ?>>New Hampshire</option>
                                                        <option value="NJ"<?php echo ($vendor_settings->shipment_state == "NJ" ? "selected" : ""); ?>>New Jersey</option>
                                                        <option value="NM"<?php echo ($vendor_settings->shipment_state == "NM" ? "selected" : ""); ?>>New Mexico</option>
                                                        <option value="NY"<?php echo ($vendor_settings->shipment_state == "NY" ? "selected" : ""); ?>>New York</option>
                                                        <option value="NC"<?php echo ($vendor_settings->shipment_state == "NC" ? "selected" : ""); ?>>North Carolina</option>
                                                        <option value="ND"<?php echo ($vendor_settings->shipment_state == "ND" ? "selected" : ""); ?>>North Dakota</option>
                                                        <option value="OH"<?php echo ($vendor_settings->shipment_state == "OH" ? "selected" : ""); ?>>Ohio</option>
                                                        <option value="OK"<?php echo ($vendor_settings->shipment_state == "OK" ? "selected" : ""); ?>>Oklahoma</option>
                                                        <option value="OR"<?php echo ($vendor_settings->shipment_state == "OR" ? "selected" : ""); ?>>Oregon</option>
                                                        <option value="PA"<?php echo ($vendor_settings->shipment_state == "PA" ? "selected" : ""); ?>>Pennsylvania</option>
                                                        <option value="RI"<?php echo ($vendor_settings->shipment_state == "RI" ? "selected" : ""); ?>>Rhode Island</option>
                                                        <option value="SC"<?php echo ($vendor_settings->shipment_state == "SC" ? "selected" : ""); ?>>South Carolina</option>
                                                        <option value="SD"<?php echo ($vendor_settings->shipment_state == "SD" ? "selected" : ""); ?>>South Dakota</option>
                                                        <option value="TN"<?php echo ($vendor_settings->shipment_state == "TN" ? "selected" : ""); ?>>Tennessee</option>
                                                        <option value="TX"<?php echo ($vendor_settings->shipment_state == "TX" ? "selected" : ""); ?>>Texas</option>
                                                        <option value="UT"<?php echo ($vendor_settings->shipment_state == "UT" ? "selected" : ""); ?>>Utah</option>
                                                        <option value="VT"<?php echo ($vendor_settings->shipment_state == "VT" ? "selected" : ""); ?>>Vermont</option>
                                                        <option value="VA"<?php echo ($vendor_settings->shipment_state == "VA" ? "selected" : ""); ?>>Virginia</option>
                                                        <option value="WA"<?php echo ($vendor_settings->shipment_state == "WA" ? "selected" : ""); ?>>Washington</option>
                                                        <option value="WV"<?php echo ($vendor_settings->shipment_state == "WV" ? "selected" : ""); ?>>West Virginia</option>
                                                        <option value="WI"<?php echo ($vendor_settings->shipment_state == "WI" ? "selected" : ""); ?>>Wisconsin</option>
                                                        <option value="WY"<?php echo ($vendor_settings->shipment_state == "WY" ? "selected" : ""); ?>>Wyoming</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col col--1-of-2">
                                                <div class="input__group is--inline">
                                                    <input id="companyZip" name="companyZip" class="input not--empty" type="text" placeholder="90210" value="<?php echo $vendor_settings->shipment_zip; ?>" required>
                                                    <label class="label" for="companyZip">Zip</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <button class="btn btn--primary btn--m btn--block form--submit save--toggle">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Shipping Address -->

                    <!-- TOS -->
                    <div id="TaxID" class="accordion__group">
                        <div class="accordion__section is--expanded">
                            <div class="accordion__title wrapper">
                                <div class="wrapper__inner">
                                    <h3>Terms of Service</h3>
                                </div>
                            </div>
                            <div class="accordion__content">
                                <div class="accordion__edit">
                                    <span class="fontWeight--2">
                                        To complete account registration, it is required to agree to our<br>
                                        <a class="link" href="<?php echo base_url(); ?>terms-conditions" target="_blank">Services Agreement</a> and the
                                        <a class="link" href="https://stripe.com/us/connect-account/legal" target="_blank">Stripe Connected Account Agreement</a>.<br><br>
                                    </span>


                                    <form id="AgreeTOSForm" class="form__group" action="<?php echo base_url(); ?>vendor-settings-agree-tos" method="post">
                                        <div class="row">
                                            <ul class="list list--inline list--divided">
                                                <li class="item">
                                                    <?php if ($tos_info->date == null){ ?>
                                                        <button class="btn btn--primary btn--m form--submit">Agree</button>
                                                    <?php }else {?>
                                                        <span class="fontWeight--2">Date agreed:</span><span id="companyTaxIDLabel"><?php echo " " . date('m/d/Y', $tos_info->date); ?></span>
                                                    <?php }?>
                                                </li>
                                            </ul>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- /TOS-->

                    <!-- Tax ID -->
                    <div id="TaxID" class="accordion__group">
                        <div class="accordion__section">
                            <div class="accordion__title wrapper">
                                <div class="wrapper__inner">
                                    <h3>Tax ID</h3>
                                </div>
                                <div class="wrapper__inner align--right">
                                    <a class="link link--expand">Edit</a>
                                </div>
                            </div>
                            <div class="accordion__content">
                                <div class="accordion__preview">
                                    <ul class="list list--inline list--divided">
                                        <li class="item">
                                            <span class="fontWeight--2">Tax ID:</span><span id="companyTaxIDLabel"><?php echo " " . ($tax_info->business_tax_id_provided ? 'Provided' : 'Not Provided'); ?></span>
                                        </li>
                                        <li class="item">
                                            <span class="fontWeight--2">Last 4 SSN:</span><span id="ssnLastLabel"> <?php echo " " . ($tax_info->ssn_last_4_provided ? 'Provided' : 'Not Provided'); ?></span>
                                        </li>
                                        <li class="item">
                                            <span class="fontWeight--2">Personal ID Number: </span><span id="personalIdLabel"><?php echo " " . ($tax_info->personal_id_number_provided ? 'Provided' : "Not Provided"); ?></span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="accordion__edit">
                                    <form id="companyTaxIdForm" class="form__group" action="<?php base_url(); ?>vendor-settings-update-taxid" method="post">
                                        <div class="row">
                                            <div class="input__group is--inline">
                                                <input type="hidden" name="vendor_id" value="<?php echo $vendor_settings->id; ?>">
                                                <!-- input input--tax-id not--empty -->
                                                <input id="companyTaxID" name="companyTaxID" class="input input--tax-id" type="text" value="" required>
                                                <label class="label" for="companyTaxID">Tax ID Number</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input__group is--inline">
                                                <input id="ssnLast" name="ssnLast" class="input" type="text" value="" maxlength="4" required>
                                                <label class="label" for="ssn">Last 4 SSN:</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input__group is--inline">
                                                <input id="personalId" name="personalId" class="input" type="text" value="" maxlength="9" required>
                                                <label class="label" for="personalId">Personal ID Number</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <button class="btn btn--primary btn--m btn--block form--submit save--toggle">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Tax ID -->

                    <!-- Bank Account Info -->
                    <div class="accordion__group">
                        <div class="accordion__section">
                            <div class="accordion__title wrapper">
                                <div class="wrapper__inner">
                                    <h3>Bank Account</h3>
                                </div>
                                <div class="wrapper__inner align--right">
                                    <a class="link link--expand">Edit</a>
                                </div>
                            </div>
                            <div class="accordion__content">
                                <div class="accordion__preview">
                                    <ul class="list list--inline list--divided">
                                        <li class="item">
                                            <span class="fontWeight--2">Bank Name:</span>
                                            <?php echo $bank->bank_name; ?>
                                        </li>
                                        <li class="item">
                                            <span class="fontWeight--2">Account No:</span>
                                            <?php echo '*******'.$bank->last4; ?>
                                        </li>
                                        <li class="item">
                                            <span class="fontWeight--2">Routing No:</span>
                                            <?php echo $bank->routing_number; ?>
                                        </li>
                                    </ul>
                                </div>
                                <div class="accordion__edit">
                                  <p class="vendor-bank-errors" style="color: red;"></p>

                                  <form id="formBank" class="form__group" action="" method="post">
                                        <div class="row">
                                            <div class="input__group is--inline">
                                                <input type="hidden" name="vendor_id" value="<?php echo $vendor_settings->id ?>">
                                                <input id="accountHolderName" name="accountHolderName" value="<?php echo $vendor_settings->account_holder_name; ?>" class="input <?php echo ($vendor_settings->account_holder_name != null) ? "not--empty" : ""; ?>" type="text" required>
                                                <label class="label" for="paymentBankName">Account Holder Name</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="select">
                                                <input class="input input--date <?php echo ($vendor_settings->dob != null) ? "not--empty" : ""; ?>" id="startDate" placeholder="Date of Birth" name="vendorDob"  value="<?php echo date('m/d/y', strtotime($vendor_settings->dob)); ?>"  style="padding-right: 32px;" type="text">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="select">
                                                <select name="accountholderType" id="accountholderType" class="sub_category" required="">
                                                    <option <?php
                                                    if ($vendor_settings->account_type == "individual") {
                                                        echo "selected";
                                                    }
                                                    ?> value="individual">Individual</option>
                                                    <option <?php
                                                    if ($vendor_settings->account_type == "company") {
                                                        echo "selected";
                                                    }
                                                    ?> value="company">Company</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input__group is--inline">
                                                <input id="paymentAccountNum" name="paymentAccountNum"  value="<?php echo $vendor_settings->account_number; ?>" class="input <?php echo ($vendor_settings->account_number != null) ? "not--empty" : ""; ?>" type="number" required>
                                                <label class="label" for="paymentAccountNum">Account Number</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input__group is--inline">
                                                <input id="paymentRoutingNum" name="paymentRoutingNum" value="<?php echo $vendor_settings->routing_number; ?>" class="input <?php echo ($vendor_settings->routing_number != null) ? "not--empty" : ""; ?>" type="number" required>
                                                <label class="label" for="paymentRoutingNum">Routing Number</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <button class="btn btn--m btn--primary btn--block save--toggle form--submit add-vendor-bank" type="button">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Bank Account Info -->

                    <!-- Return Intructions -->
                    <div class="accordion__group">
                        <div class="accordion__section">
                            <div class="accordion__title wrapper">
                                <div class="wrapper__inner">
                                    <h3>Return Instructions</h3>
                                </div>
                                <div class="wrapper__inner align--right">
                                    <a class="link link--expand">Edit</a>
                                </div>
                            </div>
                            <div class="accordion__content">
                                <div class="accordion__preview">
                                    <?php echo $vendor_settings->refund_instructions; ?>
                                </div>
                                <div class="accordion__edit">
                                    <form id="returnInstructionsForm" class="form__group"  action="<?php echo base_url(); ?>vendor-settings-update-refund" method="post">
                                        <div class="row">
                                            <input type="hidden" name="vendor_id" value="<?php echo $vendor_settings->id ?>">
                                            <textarea name="refundInstruction" class="input input--l" placeholder="Enter your default return instructions..."><?php echo $vendor_settings->refund_instructions; ?></textarea>
                                        </div>
                                        <div class="row">
                                            <!--                                            <button class="btn btn--primary btn--m btn--block form--submit save--toggle no--refresh" data-target="#returnInstructionsForm">Save Changes</button>-->
                                            <button class="btn btn--primary btn--m btn--block form--submit" data-target="#returnInstructionsForm">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Return Instructions -->

                    <!-- Vendor Policies -->
                    <div class="accordion__group">
                        <div class="accordion__section">
                            <div class="accordion__title wrapper">
                                <div class="wrapper__inner">
                                    <h3>Vendor Policies</h3>
                                </div>
                                <div class="wrapper__inner align--right">
                                    <a class="link modal--toggle fontWeight--2 fontSize--s" data-target="#newVendorPolicyModal">(+) New Policy</a>
                                </div>
                            </div>
                            <div class="accordion__content">
                                <div class="accordion__preview" style="max-height:340px;">
                                    <div class="well bg--lightest-gray">
                                        <div class="well card" style="padding:16px;">
                                            <?php if ($vendor_policies != null) { ?>
                                                <?php foreach ($vendor_policies as $policy) { ?>
                                                    <div class="wrapper">
                                                        <div class="wrapper__inner">
                                                            <div class="text__group">
                                                                <span class="line--main"><?php echo $policy->policy_name; ?></span>
                                                                <span class="line--sub"><?php echo $policy->description; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="wrapper__inner align--right">
                                                            <button class="btn btn--tertiary btn--s btn--icon btn--link modal--toggle" data-target="#deletePolicyModal<?php echo $policy->id; ?>"><svg class="icon icon--x"><use xlink:href="#icon-x"></use> </svg></button>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <div class="wrapper">
                                                    <div class="wrapper__inner">
                                                        No Policies created.
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Vendor Policies -->

                </div>
                <!-- /Content Area -->

            </div>
        </div>
    </section>
</div>
<!-- /Content Section -->
<!-- Delete Vendor Policy Modal -->
<?php if ($vendor_policies != null) { ?>
    <?php foreach ($vendor_policies as $policy) { ?>
        <div id="deletePolicyModal<?php echo $policy->id; ?>" class="modal modal--m">
            <div class="modal__wrapper modal__wrapper--transition padding--l">
                <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
                <div class="modal__header center center--h align--left">
                    <h2 class="fontSize--l">Delete Vendor Policy?</h2>
                </div>
                <form  action="<?php echo base_url(); ?>delete-vendor-policy" method="post">
                    <div class="modal__body center center--h align--left cf">
                        <div class="modal__content">
                            <hr>
                            <div class="well card" style="padding:16px;">
                                <input type="hidden" name="policy_id" value="<?php echo $policy->id; ?>">
                                <div class="text__group">
                                    <span class="line--main"><?php echo $policy->policy_name; ?></span>
                                    <span class="line--sub"><?php echo $policy->description; ?></span>
                                </div>
                            </div>
                            <hr>
                            <div class="footer__group border--dashed">
                                <button class="btn btn--m btn--block is--neg form--submit">Delete Policy</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal__overlay modal--toggle"></div>
        </div>
    <?php } ?>
<?php } ?>
<!-- /Delete Prepopulated List Modal -->


<!-- Modals -->
<?php $this->load->view('templates/_inc/shared/modals/new-policy.php'); ?>
<?php // include(INCLUDE_PATH.'/_inc/shared/modals/delete-policy.php');  ?>
<?php $this->load->view('templates/_inc/shared/modals/edit-hours.php'); ?>

<?php
$this->load->view('templates/_inc/footer-vendor.php');?>

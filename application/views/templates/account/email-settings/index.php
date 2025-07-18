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
                    Email Settings
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breadcrumbs Bar -->
    <?php
    $tier_1_2 = unserialize(ROLES_TIER1_2);
    $tier_1_2a = unserialize(ROLES_TIER1_2A);
    ?>
    <!-- Main Content -->
    <form action="<?php echo base_url("update-email-settings"); ?>" method="post">
        <section class="content__wrapper wrapper--fixed">
            <div class="content__main">
                <div class="content row">
                    <div class="col-md-6 col-xs-12 col--centered">
                        <div class="heading__group border--dashed">
                            <h3 class="mobile-center">Email Settings</h3>
                            <p class="mobile-center">Choose what you'd like us to notify you about by email.</p>
                        </div>
                        <!-- Notification Settings Group -->
                        <div class="box__group">
                            <ul class="list">
                                <li class="item">
                                    <label class="control control__checkbox">
                                        <input type="checkbox" name="e1"  value="1" <?php if ($email->email_setting1 == 1) echo 'checked'; ?>>
                                        <div class="control__indicator"></div>
                                        Receive “Low Inventory” Notification
                                    </label>
                                </li>
                                <!--                            <li class="item">
                                                                <label class="control control__checkbox">
                                                                    <input type="checkbox" name="e2"  value="1" <?php //if ($email->email_setting2 == 1) echo 'checked';  ?>>
                                                                    <div class="control__indicator"></div>
                                                                    Receive “New Order” Notification
                                                                </label>
                                                            </li>-->
                                                            <?php if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $tier_1_2)))) { ?>
                                                            <li class="item">
                                                                <label class="control control__checkbox">
                                                                    <input type="checkbox" name="e3" value="1" <?php if ($email->email_setting3 == 1) echo 'checked'; ?>>
                                                                    <div class="control__indicator"></div>
                                                                    Receive “New Message” Notification
                                                                </label>
                                                            </li>
                                                            <?php } ?>
                                                            <?php if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $tier_1_2a)))) { ?>
                                                            <li class="item">
                                                                <label class="control control__checkbox">
                                                                    <input type="checkbox" name="e4"  value="1" <?php if ($email->email_setting4 == 1) echo 'checked'; ?>>
                                                                    <div class="control__indicator"></div>
                                                                    Receive “Budget Exceeded” Notification
                                                                </label>
                                                            </li>
                                                            <?php } ?>
                                                            <li class="item">
                                                                <label class="control control__checkbox">
                                                                    <input type="checkbox" name="e5"  value="1" <?php if ($email->email_setting5 == 1) echo 'checked'; ?>>
                                                                    <div class="control__indicator"></div>
                                                                    Order Confirmation
                                                                </label>
                                                            </li>
                                                            <li class="item">
                                                                <label class="control control__checkbox">
                                                                    <input type="checkbox" name="e6"  value="1" <?php if ($email->email_setting6 == 1) echo 'checked'; ?>>
                                                                    <div class="control__indicator"></div>
                                                                    Receive “Newsletter” Notification
                                                                </label>
                                                            </li>
                                                            <li class="item">
                                                                <label class="control control__checkbox">
                                                                    <input type="checkbox" name="e7"  value="1" <?php if ($email->email_setting7 == 1) echo 'checked'; ?>>
                                                                    <div class="control__indicator"></div>
                                                                    Receive “Request List” Notification
                                                                </label>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <!-- /Notification Settings Group -->
                                                    <!-- /Notification Settings Group -->
                                                    <div class="footer__group border--dashed">
                                                        <button class="btn btn--m btn--primary btn--block save--toggle form--submit no--refresh ">Save Changes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </form>
                                <!-- /Main Content -->
                            </div>
<!-- /Content Section -->

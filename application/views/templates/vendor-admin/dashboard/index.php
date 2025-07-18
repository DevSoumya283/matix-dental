<?php include(INCLUDE_PATH . '/_inc/header-vendor.php'); ?>

<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <section class="content__wrapper has--sidebar-l bg--lightest-gray">
        <div class="content__main">
            <div class="row row--full-height">

                <!-- Sidebar -->
                <div class="sidebar col col--2-of-12 " style="padding: 18px;">
                    <?php //include(INCLUDE_PATH . '/vendor-admin/_inc/nav.php'); ?>
                    <?php $this->load->view('templates/vendor-admin/_inc/nav.php'); ?>

                </div>
                <!-- /Sidebar -->

                <!-- Content Area -->
                <div class="content col col--9-of-12 col--push-1-of-12">

                    <div class="well card is--neg">
                        <div class="wrapper">
                            <div class="wrapper__inner has--icon">
                                <svg class="icon icon--alert textColor--negative"><use xlink:href="#icon-alert"></use></svg>
                            </div>
                            <div class="wrapper__inner">
                                <h3 class="no--margin">(<?php echo ($urgent_count != null) ? $urgent_count : "0"; ?>) Urgent Orders</h3>
                            </div>
                            <div class="wrapper__inner align--right">
                                <button class="btn btn--primary btn--s is--link" data-target="<?php echo base_url(); ?>vendor-orders">View Order(s)</button>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Account Info -->
                    <div class="row">
                        <div class="col col--3-of-12 col--am">
                            <div class="well card align--center is--pos">
                                <h5>Total Sales</h5>
                                <?php if ($orders_count != null) { ?>
                                    <h3 class="no--margin-b"><span class="stat__value truncate"><?php echo "$" . number_format(floatval($orders_count), 2); ?></span></h3>
                                <?php } else { ?>
                                    <h3 class="no--margin-b">0.00</h3>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col col--3-of-12 col--am">
                            <div class="well card align--center is--pos">
                                <h5>Orders Shipped</h5>
                                <h3 class="no--margin-b"><?php echo ($orderItems_shipped_count != null) ? $orderItems_shipped_count : "0"; ?></h3>
                            </div>
                        </div>
                        <div class="col col--3-of-12 col--am">
                            <div class="well card align--center is--pos">
                                <h5>Products Listed</h5>
                                <?php if ($vendor_product_count != null) { ?>
                                    <h3 class="no--margin-b"><?php echo $vendor_product_count; ?></h3>
                                <?php } else { ?>
                                    <h3 class="no--margin-b">0</h3>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col col--3-of-12 col--am">
                            <div class="well card align--center is--pos">
                                <h5>Customers</h5>
                                <?php if ($customer_count != null) { ?>
                                    <h3 class="no--margin-b"><?php echo $customer_count; ?></h3>
                                <?php } else { ?>
                                    <h3 class="no--margin-b">0</h3>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <!-- /Account Info -->

                    <br><br>

                    <!-- Promos -->
                    <div class="heading__group border--dashed">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <h3>Active Promotions</h3>
                            </div>
                            <div id="controlsInventory" class="contextual__controls wrapper__inner align--right">
                                <button class="btn btn--tertiary btn--s contextual--hide is--link" data-target="<?php echo base_url(); ?>promoCode-status-vendors">View All Promotions</button>
                                <ul class="list list--inline list--divided margin--s no--margin-tb no--margin-r is--contextual is--off">
                                    <li class="item">
                                        <a class="link modal--toggle is--neg" data-target="#deleteConfirmationModal">Delete Selected</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="report table-scroll">
                        <table class="table" data-controls="#controlsInventory">
                            <thead>
                                <tr>
                                    <th width="5%">
                                        <label class="control control__checkbox">
                                            <input type="checkbox" class=" is--selector" id="selectAll">
                                            <div class="control__indicator"></div>
                                        </label>
                                    </th>
                                    <th>
                                        Code
                                    </th>
                                    <th>
                                        Type
                                    </th>
                                    <th>
                                        Used
                                    </th>
                                    <th>
                                        Status
                                    </th>
                                    <th>
                                        Expiration Date
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Single Promo -->
                                <?php if ($vendor_promo_codes != null) { ?>
                                    <?php foreach ($vendor_promo_codes as $active) { ?>
                                        <tr>
                                            <td>
                                                <label class="control control__checkbox">
                                                    <input type="checkbox"  class="singleCheckbox" name="checkboxRow" value="<?php echo $active->id; ?>">
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </td>
                                            <td class="fontWeight--2">
                                                <a class="link"><?php echo $active->code; ?></a>
                                            </td>
                                            <td>
                                                <?php // echo ($active->threshold_count!=null)? $active->threshold_count: "Unlimited" ?>
                                                <?php
                                                if (($active->end_date == "0000-00-00" || $active->end_date == null) || ($active->end_date == "1970-01-01")) {
                                                    echo "Ongoing";
                                                } else {
                                                    echo "Fixed Date";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php echo $active->promo_count; ?>
                                            </td>
                                            <td>
                                                <?php echo ($active->active == 1) ? "Active" : "Inactive"; ?>
                                            </td>
                                            <td>
                                                <?php // echo (date('Y-m-d', strtotime($active->end_date)) == "1970-01-01") ? "N/A" : date('M d, Y', strtotime($active->end_date)); ?>
                                                <?php
                                                if (($active->end_date == "0000-00-00" || $active->end_date == null) || ($active->end_date == "1970-01-01")) {
                                                    echo "N/A";
                                                } else {
                                                    echo date('M d, Y', strtotime($active->end_date));
                                                }
                                                ?>

                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="6">
                                            No Promotions created Yet.
                                        </td>
                                    </tr>
                                <?php } ?>
                                <!-- Single Promo -->
                            </tbody>
                        </table>
                    </div>
                    <!-- /Promos -->

                    <br><br>

                    <!-- Ratings -->
                    <div class="heading__group border--dashed">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <h3>Ratings</h3>
                            </div>
                            <?php $user_id = $_SESSION["user_id"]; ?>
                            <div class="wrapper__inner align--right">
                                <button class="btn btn--tertiary btn--s is--link " data-id="<?php echo $user_id; ?>" data-target="<?php echo base_url('vendor-profile'); ?>">View Vendor Profile</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col--4-of-12">
                            <div class="well card align--center is--pos">
                                <?php if ($positive_count != null) { ?>
                                    <h3><?php echo $positive_count; ?> Positive</h3>
                                <?php } else { ?>
                                    <h3>0 Positive</h3>
                                <?php } ?>
                                <ul class="list list--inline list--divided">
                                    <li class="item">
                                        <h6>1 Month</h6>
                                        <?php if ($positive_rating_amonth != null) { ?>
                                            <?php echo $positive_rating_amonth; ?>
                                            <?php
                                        } else {
                                            echo 0;
                                        }
                                        ?>
                                    </li>
                                    <li class="item">
                                        <h6>6 Months</h6>
                                        <?php if ($positive_rating_sixmonth != null) { ?>
                                            <?php echo $positive_rating_sixmonth; ?>
                                            <?php
                                        } else {
                                            echo 0;
                                        }
                                        ?>
                                    </li>
                                    <li class="item">
                                        <h6>1 Year</h6>
                                        <?php if ($positive_rating_year != null) { ?>
                                            <?php echo $positive_rating_year; ?>
                                            <?php
                                        } else {
                                            echo 0;
                                        }
                                        ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col col--4-of-12">
                            <div class="well card align--center is--neutral">
                                <?php if ($neutral_count != null) { ?>
                                    <h3><?php echo $neutral_count; ?> Neutral</h3>
                                <?php } else { ?>
                                    <h3>0 Neutral</h3>
                                <?php } ?>
                                <ul class="list list--inline list--divided">
                                    <li class="item">
                                        <h6>1 Month</h6>
                                        <?php if ($neutral_rating_amonth != null) { ?>
                                            <?php echo $neutral_rating_amonth; ?>
                                            <?php
                                        } else {
                                            echo 0;
                                        }
                                        ?>
                                    </li>
                                    <li class="item">
                                        <h6>6 Months</h6>
                                        <?php if ($neutral_rating_sixmonth != null) { ?>
                                            <?php echo $neutral_rating_sixmonth; ?>
                                            <?php
                                        } else {
                                            echo 0;
                                        }
                                        ?>
                                    </li>
                                    <li class="item">
                                        <h6>1 Year</h6>
                                        <?php if ($neutral_rating_year != null) { ?>
                                            <?php echo $neutral_rating_year; ?>
                                            <?php
                                        } else {
                                            echo 0;
                                        }
                                        ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col col--4-of-12">
                            <div class="well card align--center is--neg">
                                <?php if ($negative_count != null) { ?>
                                    <h3><?php echo $negative_count; ?> Negative</h3>
                                <?php } else { ?>
                                    <h3>0 Negative</h3>
                                <?php } ?>
                                <ul class="list list--inline list--divided">
                                    <li class="item">
                                        <h6>1 Month</h6>
                                        <?php if ($negative_rating_amonth != null) { ?>
                                            <?php echo $negative_rating_amonth; ?>
                                            <?php
                                        } else {
                                            echo 0;
                                        }
                                        ?>
                                    </li>
                                    <li class="item">
                                        <h6>6 Months</h6>
                                        <?php if ($negative_rating_sixmonth != null) { ?>
                                            <?php echo $negative_rating_sixmonth; ?>
                                            <?php
                                        } else {
                                            echo 0;
                                        }
                                        ?>
                                    </li>
                                    <li class="item">
                                        <h6>1 Year</h6>
                                        <?php if ($negative_rating_year != null) { ?>
                                            <?php echo $negative_rating_year; ?>
                                            <?php
                                        } else {
                                            echo 0;
                                        }
                                        ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /Content Area -->

            </div>
        </div>
    </section>
</div>
<!-- /Content Section -->
<?php //include(INCLUDE_PATH . '/_inc/footer-vendor.php'); ?>

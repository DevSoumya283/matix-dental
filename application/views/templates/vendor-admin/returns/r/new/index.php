<?php include(INCLUDE_PATH . '/_inc/header-vendor.php'); ?>

<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <!-- Breadcrumbs Bar -->
    <div class="bar padding--xs bg--lightest-gray">
        <div class="wrapper wrapper--fixed">
            <ul class="list list--inline list--breadcrumbs">
                <li class="item">
                    <a class="link" href="<?php echo base_url(); ?>vendor-orderReturns-open">Returns</a>
                </li>
                <li class="item is--active">
                    Return <?php echo $order_title->id; ?>
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breadcrumbs Bar -->

    <section class="content__wrapper has--sidebar-l bg--lightest-gray">
        <div class="content__main">
            <div class="row row--full-height">

                <!-- Sidebar -->
                <div class="sidebar col col--2-of-12">
                    <?php include(INCLUDE_PATH . '/vendor-admin/_inc/nav.php'); ?>
                </div>
                <!-- /Sidebar -->

                <!-- Content Area -->
                <div class="content col col--9-of-12 col--push-1-of-12">

                    <!-- /Returns -->
                    <div class="heading__group border--dashed">
                        <h3>Return Request</h3>
                    </div>

                    <div class="well card is--neutral">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <ul class="list list--inline list--divided disp--ib fontWeight--2 fontSize--s">
                                    <li class="item">
                                        <h3 class="no--margin">Needs Approval</h3>
                                    </li>
                                    <li class="item">
                                        <a class="link">Export CSV</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="wrapper__inner align--right">
                                <ul class="list list--inline list--divided">
                                    <li class="item">
                                        <a class="link is--neg fontWeight--2 fontSize--s modal--toggle closeRequestOrder" data-return_id="<?php echo $order_title->id; ?>" data-target="#denyRequestModal">Deny Return</a>
                                    </li>
                                    <li class="item">
                                        <button class="btn btn--m btn--primary is--pos modal--toggle closeRequestOrder" data-return_id="<?php echo $order_title->id; ?>" data-target="#acceptRequestModal">Accept Request</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="invoice">
                        <div class="inv__contact no--pad-lr no--margin-t" style="border-top:none; padding:16px 0 32px 0;">
                            <div class="wrapper">
                                <?php if ($customer_details != null) { ?>
                                    <?php foreach ($customer_details as $details) { ?>
                                        <div class="wrapper__inner">
                                            <ul class="list list--inline list--divided list--stats">
                                                <li class="item item--stat">
                                                    <div class="text__group">
                                                        <span class="line--main"><?php echo $details->id; ?></span>
                                                        <span class="line--sub">Request Number</span>
                                                    </div>
                                                </li>
                                                <li class="item item--stat">
                                                    <div class="text__group">
                                                        <span class="line--main"><?php echo date('M d Y', strtotime($details->requested_date)); ?></span>
                                                        <span class="line--sub">Request Date</span>
                                                    </div>
                                                </li>
                                                <li class="item item--stat">
                                                    <div class="text__group">
                                                        <span class="line--main"><?php echo $details->first_name; ?></span>
                                                        <span class="line--sub"><?php echo $details->organization_name; ?></span>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="wrapper__inner align--right">
                                            <ul class="list list--inline list--divided list--stats">
                                                <li class="item item--stat align--right">
                                                    <div class="text__group">
                                                        <span class="line--main"><?php echo "$" . $details->refund_amount; ?></span>
                                                        <span class="line--sub">Refund Amount</span>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <div class="padding--s no--pad-t">
                            <?php if ($payment_details != null) { ?>
                                <?php if ($payment_details->payment_type == 'card') { ?>
                                    <i class="icon icon--cc icon--visa"></i>
                                    Visa •••• <?php echo $payment_details->cc_number; ?>
                                <?php } else { ?>
                                    <i class="icon icon--cc icon--bank"></i>
                                    bank •••• <?php echo $payment_details->ba_account_number; ?>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                        <table class="table table--invoice">
                            <thead>
                                <tr>
                                    <th width="55%">
                                        Item
                                    </th>
                                    <th width="20%">
                                        SKU
                                    </th>
                                    <th width="15%">
                                        Unit Price
                                    </th>
                                    <th width="10%">
                                        Qty
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Line Item -->
                                <?php if ($order_returns != null) { ?>
                                    <?php foreach ($order_returns as $returns) { ?>
                                        <tr>
                                            <td>
                                                <!-- Product -->
                                                <div class="product product--s multi--vendor req--license padding--xxs">
                                                    <div class="product__data">
                                                        <span class="product__name"><?php echo $returns->name; ?></span>
                                                        <span class="product__mfr">
                                                            by <a class="link fontWeight--2" href="#"><?php echo $returns->manufacturer; ?></a>
                                                        </span>
                                                    </div>
                                                </div>
                                                <!-- /Product -->
                                            </td>
                                            <td>
                                                <?php echo $returns->mpn; ?>
                                            </td>
                                            <td>
                                                <?php echo "$" . $returns->total; ?>
                                            </td>
                                            <td>
                                                <?php echo $returns->quantity; ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td>
                                            No items to Return
                                        </td>
                                    </tr>
                                <?php } ?>
                                <!-- /Line Item -->
                            </tbody>
                        </table>
                        <div class="inv__totals">
                            <div class="wrapper">
                                <?php if ($customer_address != null) { ?>
                                    <div class="wrapper__inner">
                                        <h5 class="textColor--dark-gray">Shipping to:</h5>
                                        <?php foreach ($customer_address as $address) { ?>
                                            <span class="fontWeight--2"><?php echo $address->first_name; ?><br></span>
                                            <?php echo $address->nickname; ?><br>
                                            <?php echo $address->address1; ?><br>
                                            <?php echo $address->address2; ?><br>
                                            <?php echo $address->city; ?>, <?php echo $address->zip; ?>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                <div class="wrapper__inner align--right">
                                    <?php foreach ($calculation_section as $total) { ?>
                                        <span class="fontWeight--2 textColor--dark-gray fontSize--s disp--block margin--xs no--margin-lr no--margin-t">Subtotal: <?php echo "$" . $total->total; ?><br>
                                            Tax: <?php echo ($total->tax != null) ? "$" . $total->tax : "0.00"; ?><br>
                                            Shipping: <?php echo "$" . $total->shipping_price; ?></span>
                                        <span class="fontWeight--2">Refund Total: <?php echo "$" . $grand_total; ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Invoice -->

                    <hr>

                    <!-- Notes -->
                    <div class="well" style="max-height:400px;">
                        <div class="heading__group border--dashed wrapper">
                            <div class="wrapper__inner">
                                <h4>Notes</h4>
                            </div>
                            <div class="wrapper__inner align--right">
                                <button class="btn btn--tertiary btn--s modal--toggle" data-target="#addNoteModal">Add Note</button>
                            </div>
                        </div>
                        <ul class="list list--activity fontSize--s" style="max-height:400px;">

                            <!-- Single Note -->
                            <?php if ($vendor_message != null) { ?>
                                <?php foreach ($vendor_message as $message) { ?>
                                    <li class="item item--note">
                                        <div class="wrapper">
                                            <div class="col col--2-of-12 wrapper__inner align--center padding--m no--pad-tb">
                                                <div class="entity__group">
                                                    <?php if ($message->photo != null) { ?>
                                                        <div class="avatar avatar--s" style="background-image:url('<?php echo image_url(); ?>uploads/user/profile/<?php echo $message->photo; ?>'); margin:0 0 8px 0;"></div>
                                                    <?php } else { ?>
                                                        <div class="avatar avatar--s" style="background-image:url('<?php echo image_url(); ?>assets/img/avatar-default.png'); margin:0 0 8px 0;"></div>
                                                    <?php } ?>
                                                    <span class="disp--block fontWeight--2"><?php echo $message->first_name; ?></span>
                                                </div>
                                                <span class="fontSize--xs"><?php echo date('M d, Y', strtotime($message->created_at)); ?></span>
                                            </div>
                                            <div class="col col--10-of-12 wrapper__inner">
                                                <div class="well card" style="min-height: 85px;">
                                                    <?php echo $message->message; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                <?php } ?>
                            <?php } ?>
                            <!-- /Single Note -->

                        </ul>
                    </div>
                    <!-- /Notes -->

                    <hr>

                    <!-- Recent Acitivity -->
                    <div class="well">
                        <div class="heading__group border--dashed wrapper">
                            <div class="wrapper__inner">
                                <h4>Recent Activity</h4>
                            </div>
                        </div>
                        <ul class="list list--activity fontSize--s">
                            <?php if ($order_activity != null) { ?>
                                <?php foreach ($order_activity as $activity) { ?>
                                    <li class="item">
                                        <div class="entity__group">
                                            <?php if ($activity->model_photo != null) { ?>
                                                <div class="avatar avatar--xs" style="background-image:url('<?php echo image_url(); ?>uploads/user/profile/<?php echo $activity->model_photo; ?>');"></div>
                                            <?php } else { ?>
                                                <div class="avatar avatar--xs" style="background-image:url('<?php echo image_url(); ?>assets/img/avatar-default.png');"></div>
                                            <?php } ?>
                                            <span class="fontWeight--2"><?php echo $activity->user_name; ?></span> marked this order <span class="fontWeight--2"><?php echo $activity->status; ?></span> <span class="fontSize--xs textColor--dark-gray"><?php echo date('M d, Y', strtotime($activity->created_at)); ?></span>
                                        </div>
                                    </li>
                                <?php } ?>
                            <?php } else { ?>
                                <li class="item">
                                    <div class="entity__group">
                                        Order Status will be shown here.
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <!-- /Recent Acitivity -->

                </div>
                <!-- /Content Area -->

            </div>
        </div>
    </section>
</div>
<!-- /Content Section -->

<!-- Modals -->
<?php include(INCLUDE_PATH . '/_inc/shared/modals/add-note.php'); ?>

<?php
include(INCLUDE_PATH . '/_inc/footer-vendor.php');

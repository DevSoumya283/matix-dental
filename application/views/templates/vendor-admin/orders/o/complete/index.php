<?php include(INCLUDE_PATH . '/_inc/header-vendor.php'); ?>

<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <!-- Breadcrumbs Bar -->
    <div class="bar padding--xs bg--lightest-gray">
        <div class="wrapper wrapper--fixed">
            <ul class="list list--inline list--breadcrumbs">
                <li class="item">
                    <a class="link" href="<?php echo base_url(); ?>vendor-orders-completed">Completed Orders</a>
                </li>
                <li class="item is--active">
                    Order <?php echo $order_id; ?>
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
                        <h3>Order Details</h3>
                    </div>

                    <div class="well card is--pos">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <h3 class="no--margin">Shipped</h3>
                            </div>
                            <div class="wrapper__inner align--right">
                                <ul class="list list--inline list--divided disp--ib fontWeight--2 fontSize--s">
                                    <li class="item">
                                        <a class="link print_view_order" href="javascript:void(0)">Print Packing Slip</a>
                                    </li>
                                    <li class="item">
                                        <a class="link" href="javascript:void(0)" onclick="printAddress();">Print Address Label</a>
                                    </li>
                                    <li class="item">
                                        <a class="link" id="export">Export CSV</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="invoice">
                        <div class="inv__contact no--pad-lr no--margin-t" style="border-top:none; padding:16px 0 32px 0;">
                            <ul class="list list--inline list--divided list--stats">
                                <?php if ($order_details != null) { ?>
                                    <?php foreach ($order_details as $orders) { ?>
                                        <li class="item item--stat">
                                            <div class="text__group">
                                                <span class="line--main"><?php echo $orders->id; ?></span>
                                                <span class="line--sub">Order Number</span>
                                            </div>
                                        </li>
                                        <li class="item item--stat">
                                            <div class="text__group">
                                                <span class="line--main"><?php echo date('M d, Y', strtotime($orders->created_at)); ?></span>
                                                <span class="line--sub">Order Date</span>
                                            </div>
                                        </li>
                                        <li class="item item--stat">
                                            <div class="text__group">
                                                <span class="line--main"><?php echo $orders->shipping_type; ?></span>
                                                <span class="line--sub">Shipping Method</span>
                                            </div>
                                        </li>
                                        <li class="item item--stat">
                                            <div class="text__group">
                                                <span class="line--main"><?php echo date('M d, Y', strtotime($orders->shipped_date)); ?></span>
                                                <span class="line--sub">Ship Date</span>
                                            </div>
                                        </li>
                                    <?php } ?>
                                <?php } ?>
                            </ul>
                        </div>
                        <div class="padding--s no--pad-t">
                        <?php

                            if ($users_payments != null) {
                            foreach ($users_payments as $row) {
                            ?>
                            <input type="hidden" name="payment_token" class="payment_token formvalue" value="<?php echo $row->token; ?>">
                            <input type="hidden" name="payment_id" class="pay_id" value="<?php echo $row->id; ?>">
                            <input type="hidden" name="payment_id" class="pay_id" value="<?php echo $row->id; ?>">
                            <?php
                            if (($row->payment_type) == 'card') {
                                $card_number = preg_replace('/[^0-9]/', '', $row->cc_number);
                                $inn = (int) mb_substr($card_number, 0, 2);
                                $card_num = substr($row->cc_number, -4);
                                ?>

                                <?php if ($row->card_type == 'Visa') { ?>

                                    <svg class="icon icon--cc icon--visa"></svg>
                                    <?php echo $row->card_type . " •••• "; ?><?php echo $row->cc_number; ?>
                                <?php } else if ($row->card_type == 'MasterCard') {
                                    ?>

                                    <svg class="icon icon--cc icon--mastercard"></svg>
                                    <?php
                                    echo $row->card_type . " •••• ";
                                    echo $row->cc_number;
                                    ?>
                                <?php } else if ($row->card_type == 'Discover') {
                                    ?>

                                    <svg class="icon icon--cc icon--discover"></svg>
                                    <?php
                                    echo $row->card_type . " •••• ";
                                    echo $row->cc_number;
                                    ?>
                                <?php } else if ($row->card_type == 'American Express') {
                                    ?>

                                    <svg class="icon icon--cc icon--amex"></svg>
                                    <?php
                                    echo $row->card_type . " •••• ";
                                    echo $row->cc_number;
                                    ?>
                                <?php } else {
                                    ?>

                                    <svg class="icon icon--cc icon--undefined"></svg>
                                    <?php
                                    echo $type = 'undefined Card' . " •••• ";
                                    echo $card_number;
                                    ?>
                                    <?php
                                }
                            } elseif (($row->payment_type) == 'bank') {
                                $account_number = substr($row->ba_account_number, -4);
                                ?>
                                <svg class="icon icon--cc icon--bank"></svg>
                                <?php echo $row->bank_name; ?> •••• <?php
                                print $account_number;
                            }
                            }
                            }?>



                        </div>
                        <table class="table table--invoice" id="export_order">
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
                                        Shipped
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($order_items != null) { ?>
                                    <?php foreach ($order_items as $items) { ?>
                                        <tr>
                                            <td>
                                                <!-- Product -->
                                                <div class="product product--s multi--vendor req--license padding--xxs">
                                                    <div class="product__data">
                                                        <span class="product__name"><?php echo $items->name; ?></span>
                                                        <span class="product__mfr">
                                                            by <a class="link fontWeight--2" href="#"><?php echo $items->manufacturer; ?></a>
                                                        </span>
                                                        <span class="textColor--accent fontWeight--2 disp--block"><?php echo $items->title; ?></span>
                                                    </div>
                                                </div>
                                                <!-- /Product -->
                                            </td>
                                            <td>
                                                <?php echo $items->mpn; ?>
                                            </td>
                                            <td>
                                                <?php echo "$" . $items->total; ?>
                                            </td>
                                            <td>
                                                <?php echo $items->quantity; ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                                <!-- /Line Item -->
                            </tbody>
                        </table>
                        <div class="inv__totals">
                            <div class="wrapper">
                                <div class="wrapper__inner">
                                    <h5 class="textColor--dark-gray">Shipping to:</h5>
                                    <div class="Invoice-Address"><p style="display: none;" >To:</p>
                                        <?php if ($locations != null) { ?>
                                            <?php foreach ($locations as $address) { ?>
                                                <span class="fontWeight--2"><?php echo $orderUser->organization->organization_name; ?></span><br>
                                                <span class="fontWeight--2"><?php echo $address->first_name; ?></span><br>
                                                <?php echo $address->address1; ?><?php echo $address->address2; ?><br>
                                                <?php echo $address->city . " "; ?><?php echo $address->state . " "; ?><?php echo $address->zip; ?><br>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="wrapper__inner align--right">
                                    <?php foreach ($calculation_section as $total) { ?>

                                        <span class="fontWeight--2 textColor--dark-gray fontSize--s disp--block margin--xs no--margin-lr no--margin-t">Subtotal: <?php echo "$" . $grand_total; ?><br>
                                            Tax: <?php echo "$" . $order_report->tax; ?><br>
                                            <?php if ($allpromotions != null) { ?>
                                                <span class="textColor--accent promo_code">
                                                    <?php foreach ($allpromotions as $promo) { ?>
                                                        Promo - <?php echo $promo->code; ?>:<?php echo "$" . number_format(floatval($promo->discount_value), 2, ".", ""); ?><br>
                                                    <?php } ?>
                                                </span>
                                            <?php } ?>
                                            Shipping: <?php echo "$" . $order_report->shipping_price; ?></span>
                                        <span class="fontWeight--2">Total: <?php echo "$" . number_format(floatval($order_report->total), 2); ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php if ($allpromotions != null) { ?>
                                <?php foreach ($allpromotions as $promo) { ?>
                                    <?php if ($promo->manufacturer_coupon == 1) { ?>
                                        <div class="wrapper">
                                            <b>Redemption instructions for promo code: <span class="textColor--accent promo_code"><?php echo $promo->code; ?></span></b>
                                            <p><?php echo $promo->conditions; ?></p>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- /Invoice -->

                    <!-- Invoice  Address-->
                    <div class="invoice--CustomerAddress">

                    </div>

                    <!-- /Invoice  Address-->

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
                                                        <div class="avatar avatar--s" style="background-image:url('<?php echo ROOT_PATH; ?>assets/img/ph-avatar.jpg'); margin:0 0 8px 0;"></div>
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
                                                <div class="avatar avatar--xs" style="background-image:url('<?php echo ROOT_PATH; ?>uploads/user/profile/<?php echo $activity->model_photo; ?>');"></div>
                                            <?php } else { ?>
                                                <div class="avatar avatar--xs" style="background-image:url('<?php echo ROOT_PATH; ?>assets/img/ph-avatar.jpg');"></div>
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
<?php include(INCLUDE_PATH . '/_inc/shared/modals/process-order.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/add-note.php'); ?>

<?php
include(INCLUDE_PATH . '/_inc/footer-vendor.php');

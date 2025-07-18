<?php include(INCLUDE_PATH . '/_inc/header-admin.php'); ?>

<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <!-- Breadcrumbs Bar -->
    <div class="bar padding--xs bg--lightest-gray">
        <div class="wrapper wrapper--fixed">
            <ul class="list list--inline list--breadcrumbs">
                <li class="item">
                    <a class="link" href="<?php echo base_url(); ?>">Orders</a>
                </li>
                <li class="item is--active">
                    Order <?php echo $order_details->id; ?>
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
                    <?php include(INCLUDE_PATH . '/admin/_inc/nav.php'); ?>
                </div>
                <!-- /Sidebar -->

                <!-- Content Area -->
                <div class="content col col--9-of-12 col--push-1-of-12">

                    <!-- /Returns -->
                    <div class="heading__group border--dashed">
                        <h3>Order Details</h3>
                    </div>

                    <div class="well card is--neutral">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <ul class="list list--inline list--divided disp--ib fontWeight--2 fontSize--s">
                                    <li class="item">
                                        <a class="link print_view_order" href="javascript:void(0)">Print Packing Slip</a>
                                    </li>
                                    <li class="item">
                                        <a class="link" id="export">Export CSV</a>
                                    </li>
                                </ul>
                            </div>
                            <!--                            <div class="wrapper__inner align--right">
                                                            <ul class="list list--inline list--divided">
                                                                <li class="item">
                                                                    <a class="link is--neg fontWeight--2 fontSize--s modal--toggle" data-target="#cancelOrderAdminModal">Cancel Order</a>
                                                                </li>
                                                                <li class="item">
                                                                    <button class="btn btn--m btn--primary modal--toggle" data-target="#processOrderModal">Process Order</button>
                                                                </li>
                                                            </ul>
                                                        </div>-->
                        </div>
                    </div>

                    <hr>

                    <div class="invoice">
                        <div class="inv__contact no--pad-lr no--margin-t" style="border-top:none; padding:16px 0 32px 0;">
                            <ul class="list list--inline list--divided list--stats">
                                <li class="item item--stat">
                                    <div class="text__group">
                                        <span class="line--main"><?php echo $order_details->id; ?></span>
                                        <span class="line--sub">Order Number</span>
                                    </div>
                                </li>
                                <li class="item item--stat">
                                    <div class="text__group">
                                        <span class="line--main"><?php echo date('M d, Y', strtotime($order_details->created_at)); ?></span>
                                        <span class="line--sub">Order Date</span>
                                    </div>
                                </li>
                                <li class="item item--stat">
                                    <div class="text__group">
                                        <span class="line--main"><?php echo $order_details->shipping_method; ?></span>
                                        <span class="line--sub">Shipping Method</span>
                                    </div>
                                </li>
                                <li class="item item--stat">
                                    <div class="text__group">
                                        <span class="line--main"><?php echo $order_details->delivery_time; ?></span>
                                        <span class="line--sub">Ship By</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="padding--s no--pad-t">
                            <?php

                            if (($order_details->payment_type) == 'card') {

                                if ($order_details->card_type == "Visa") {
                                    ?>
                                    <svg class="icon icon--cc icon--visa"></svg>
                                    <?php
                                    echo $type = $order_details->card_type . " •••• ";
                                    echo $order_details->cc_number;
                                } else if ($order_details->card_type == "MasterCard") {

                                    ?>

                                    <svg class="icon icon--cc icon--mastercard"></svg>
                                    <?php
                                    echo $type = $order_details->card_type . " •••• ";
                                    echo $order_details->cc_number;
                                } else if ($order_details->card_type == "Discover Card") {

                                    ?>

                                    <svg class="icon icon--cc icon--discover"></svg>
                                    <?php
                                    echo $type = $order_details->card_type . " •••• ";
                                    echo $order_details->cc_number;
                                }
                                else if ($order_details->card_type == "American Express") {
                                    ?>

                                    <svg class="icon icon--cc icon--amex"></svg>
                                    <?php
                                    echo $type = $order_details->card_type . " •••• ";
                                    echo $order_details->cc_number;
                                } else {
                                    ?>
                                    <svg class="icon icon--cc icon--undefined"></svg>
                                    <?php
                                    echo $type = 'Other Card' . " •••• ";
                                    echo $order_details->cc_number;
                                }
                            } elseif (($order_details->payment_type) == 'bank') {
                                $account_number = substr($order_details->ba_account_number, -4);
                                ?>
                                <svg class="icon icon--cc icon--bank"></svg>
                                <?php echo $order_details->bank_name; ?>••••<?php
                                print $account_number;
                            }
                            ?>
                        </div>
                        <table class="table table--invoice" id="export_order">
                            <thead>
                                <tr>
                                    <th width="45%">
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
                                    <th width="10%">
                                        Picked
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                <!-- Line Item -->
                                <?php if ($purchased_product != null) { ?>
                                    <?php foreach ($purchased_product as $purchase) { ?>
                                        <tr>
                                            <td>
                                                <!-- Product -->
                                                <div class="product product--s multi--vendor req--license padding--xxs">
                                                    <div class="product__data">
                                                        <span class="product__name"><?php echo $purchase->name; ?></span>
                                                    </div>
                                                </div>
                                                <!-- /Product -->
                                            </td>
                                            <td>
                                                <?php echo $purchase->mpn; ?>
                                            </td>
                                            <td>
                                                <?php echo "$" . number_format(floatval($purchase->price), 2); ?>
                                            </td>
                                            <td>
                                                <?php echo $purchase->picked; ?>
                                            </td>
                                            <td><input type="hidden" class="orderItem_id" name="orderItemId" value="<?php echo $purchase->id; ?>">
                                                <input class="input input--qty" name="picked" value="<?php echo $purchase->picked; ?>" type="number">
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                                <!-- /Line Item -->

                            </tbody>
                        </table>
                        <div class="inv__totals">
                            <div class="wrapper">
                                <div class="wrapper__inner">
                                    <?php if ($order_address != null) { ?>
                                        <h5 class="textColor--dark-gray">Shipping to:</h5>
                                        <span class="fontWeight--2"><?php echo $customer_details->first_name; ?></span><br>
                                        <?php echo $order_address->address1; ?><?php echo $order_address->address2; ?><br>
                                        <?php // echo $order_address->city . "  "; ?><?php echo $order_address->state . "  "; ?><?php echo $order_address->zip; ?>
                                    <?php } ?>
                                </div>
                                <div class="wrapper__inner align--right">
                                    <?php foreach ($calculation_section as $total) { ?>
                                        <span class="fontWeight--2 textColor--dark-gray fontSize--s disp--block margin--xs no--margin-lr no--margin-t">Subtotal: <?php echo "$" . number_format(floatval($grand_total), 2, ".", ""); ?><br>
                                            Tax: <?php echo "$" . number_format(floatval($order_details->tax), 2, ".", ""); ?><br>
                                            Shipping: <?php echo "$" . number_format(floatval($order_details->shipping_price), 2, ".", ""); ?></span>
                                        <?php if ($allpromotions != null) { ?>
                                            <span class="textColor--accent promo_code">
                                                <?php foreach ($allpromotions as $promo) { ?>
                                                    Promo - <?php echo $promo->code; ?> :<?php echo "$" . number_format(floatval($promo->discount_value), 2, ".", ""); ?><br>
                                                <?php } ?>
                                            </span>
                                        <?php } ?>
                                        <span class="fontWeight--2">Total: <?php echo "$" . number_format(floatval($order_details->total), 2, ".", ""); ?></span>
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
                </div>
                <!-- /Content Area -->

            </div>
        </div>
    </section>
</div>
<!-- /Content Section -->

<!-- Modals -->
<?php include(INCLUDE_PATH . '/_inc/shared/modals/cancel-order-admin.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/process-order.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/add-note.php'); ?>

<?php
include(INCLUDE_PATH . '/_inc/footer-admin.php');

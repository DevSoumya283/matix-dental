<?php include(INCLUDE_PATH . '/_inc/header-vendor.php'); ?>

<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <section class="content__wrapper has--sidebar-l">
        <div class="content__main">
            <div class="row row--full-height">

                <!-- Sidebar -->
                <div class="sidebar col col--2-of-12">
                    <?php include(INCLUDE_PATH . '/vendor-admin/_inc/nav.php'); ?>
                </div>
                <!-- /Sidebar -->

                <!-- Content Area -->
                <div class="content col col--9-of-12 col--push-1-of-12">

                    <div class="align--center">
                        <h2>Order Processed</h2>
                    </div>

                    <!-- Single Order -->
                    <hr>
                    <div class="order well card is--pos">
                        <div class="heading__group wrapper border--dashed padding--s no--pad-lr no--pad-t">
                            <div class="wrapper__inner">
                                <h4 class="textColor--darkest-gray">Order <?php echo $order_id; ?></h4>
                            </div>
                            <div class="wrapper__inner align--right">
                                <ul class="list list--inline list--divided fontWeight--2 fontSize--s">
                                    <li class="item">
                                        <a class="link print_view_order" href="javascript:void(0);">Print Packing Slip</a>
                                    </li>
                                    <li class="item">
                                        <a class="link" href="javascript:void(0);" onclick="printAddress();">Print Address Label</a>
                                    </li>
                                    <li class="item">
                                        <button class="btn btn--s btn--primary is--link" data-target="<?php echo base_url(); ?>orders-shipped?order_id=<?php echo $order_id; ?>">View Order</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row">
                            <?php if ($order_processed != null) { ?>
                                <?php foreach ($order_processed as $processed) { ?>
                                    <div class="order__info col col--10-of-12 col--am">
                                        <ul class="list list--inline list--stats list--divided">
                                            <li class="item item--stat stat-s">
                                                <div class="text__group">
                                                    <span class="line--main"><?php echo ($processed->address1 != null) ? $processed->address1 . "," : ""; ?><?php echo ($processed->address2 != null) ? $processed->address2 . "," : ""; ?></span>
                                                    <span class="line--sub"><?php echo ($processed->city != null) ? $processed->city . "," : ""; ?><?php echo ($processed->state != null) ? $processed->state . "," : ""; ?><?php echo ($processed->zip != null) ? $processed->zip . "," : ""; ?></span>
                                                </div>
                                            </li>
                                            <li class="item item--stat stat-s">
                                                <div class="text__group">
                                                    <span class="line--main"><?php echo $processed->first_name; ?></span>
                                                    <span class="line--sub">Customer Name</span>
                                                </div>
                                            </li>
                                            <li class="item item--stat stat-s">
                                                <div class="text__group">
                                                    <span class="line--main"><?php echo $processed->organization_name; ?></span>
                                                    <span class="line--sub">Company</span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="order__btn col col--2-of-12 col--am align--right">
                                        <ul class="list list--inline list--stats">
                                            <li class="item item--stat">
                                                <div class="text__group">
                                                    <span class="line--main font"><?php echo "$" . number_format(floatval($processed->total), 2); ?></span>
                                                    <span class="line--sub">Order Total</span>
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
                    <!-- /Single Order -->
                    <div class="invoice" style="display: none;">
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
                                                <?php echo "$" . number_format(floatval($items->total), 2); ?>
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
                                                <span class="fontWeight--2"><?php echo ($orderUser != null) ? $orderUser->organization->organization_name : ""; ?></span><br>
                                                <span class="fontWeight--2"><?php echo $address->first_name; ?></span><br>
                                                <?php echo $address->address1; ?><?php echo $address->address2; ?><br>
                                                <?php // echo ($address->city!=null)?$address->city."," : ""; ?>
                                                <?php echo ($address->state != null) ? $address->state . " " : ""; ?><?php echo $address->zip; ?><br>
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
                                            Shipping: <?php echo "$" . $order_report->shipping_price; ?></span>
                                        <?php if ($allpromotions != null) { ?>
        <!--                                            <span class="textColor--accent promo_code">-->
                                            <span class="fontSize--s fontWeight--2 textColor--accent promo_code">
                                                <?php foreach ($allpromotions as $promo) { ?>
                                                    Promo - <?php echo $promo->code; ?>:<?php echo "$" . number_format(floatval($promo->discount_value), 2, ".", ""); ?><br>
                                                <?php } ?>
                                            </span>
                                        <?php } ?>
                                        <span class="fontWeight--2">Total: <?php echo "$" . number_format(floatval($order_report->total), 2); ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php if ($allpromotions != null) { ?>
                                <?php foreach ($allpromotions as $promo) { ?>
                                    <?php if ($promo->manufacturer_coupon == 1) { ?>
                                        <div class="wrapper">
                                            <b>Redemption instructions for promo code:
            <!--                                                <span class="textColor--accent promo_code">-->
                                                <span class="fontSize--s fontWeight--2 textColor--accent promo_code">
                                                    <?php echo $promo->code; ?></span></b>
                                            <p><?php echo $promo->conditions; ?></p>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>

                </div>
                <!-- /Content Area -->

            </div>
        </div>
    </section>
</div>
<!-- /Content Section -->

<?php
include(INCLUDE_PATH . '/_inc/footer-vendor.php');

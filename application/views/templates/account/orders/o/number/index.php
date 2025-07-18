<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>
    <link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>assets/css/order-print.css?v=<?php echo $this->config->item('jsVersion'); ?>">
    <!-- Breadcrumbs Bar -->
    <div class="bar padding--xs bg--lightest-gray">
        <div class="wrapper wrapper--fixed">
            <ul class="list list--inline list--breadcrumbs">
                <li class="item">
                    <a class="link" href="<?php echo base_url('dashboard'); ?>">Account</a>
                </li>
                <li class="item">
                    <a class="link" href="<?php echo base_url('history'); ?>">Order History</a>
                </li>
                <li class="item is--active">
                    Order <?php echo $orders->id; ?>
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breadcrumbs Bar -->
    <!-- Main Content -->
    <section class="content__wrapper wrapper--fixed bg--lightest-gray">
        <div class="content__main">
            <?php if(!empty($previousOrders)){ ?>
                <div class="row pt-2">
                    <div class="m-1 pt-2 col-md-2 text-left">
                        Jump to order:
                    </div>
                    <div class="m-1 col-md-7 col-xs-12">
                        <div class="select mr-3"><select name="order_id" id="orderPicker"  onchange="if($('#orderPicker option:selected').val() > 0){window.location.replace('/view-orders?id='+$('#orderPicker option:selected').val())}">
                                <?php
                                foreach($previousOrders as $id => $order){
                                    $selected = '';
                                    if($orders->id == $order->id){
                                        $selected = 'selected';
                                    }

                                    $order->date = date('m-d-Y', strtotime($order->created_at));

                                    echo '<option value="' . $order->id . '" ' . $selected . '>
                                              Order#' . $order->id . ' - ' . $order->date . ' ' . $order->nickname . ' ($' . $order->total . ') [' . $order->order_status . ']
                                          </option>
                                         ';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <br>
            <?php } ?>
            <div class="row row--full-height">
                <div class="content col-md-9 col-xs-12">
                    <div class="invoice">
                        <div class="inv__head row">
                            <div class="col col--2-of-8 col--am" style="float:left">
                                <!--
                                    NOTE: This logo can be any size
                                -->
                                <?php if ($vendor_image != null) { ?>
                                    <img class="inv__logo" src="<?php echo $this->config->item('image_url'); ?>uploads/vendor/logo/<?php echo $vendor_image->photo; ?>" alt="">
                                <?php } ?>
                            </div>
                            <div class="col col--4-of-8 col--push-2-of-8 col--am align--right" style="float: right;">
                                <span class="fontWeight--2 textColor--dark-gray">Order:</span>
                                <span class="fontWeight--2"><?php echo $orders->id; ?></span><br>
                                <span class="fontWeight--2 textColor--dark-gray">Date:</span>
                                <span class="fontWeight--2"><?php echo date('m/d/Y', strtotime($orders->created_at)); ?></span>
                            </div>
                        </div>
                        <div class="inv__contact wrapper">
                            <div class="wrapper__inner">
                                <ul class="list list--inline list--divided align--left disp--ib">
                                    <li class="item">
                                        <span class="fontWeight--2"><?php echo $vendor_details->name; ?></span><br>
                                        <?php echo ($vendor_details->address1 != null) ? $vendor_details->address1 . ", " : ""; ?>
                                        <?php echo ($vendor_details->address2 != null) ? $vendor_details->address2 . ",<br>" : "<br>"; ?>
                                        <?php echo ($vendor_details->city != null) ? $vendor_details->city . ",<br>" : ""; ?>
                                        <?php echo ($vendor_details->state != null) ? $vendor_details->state . "  " : ""; ?><?php echo ($vendor_details->zip != null) ? $vendor_details->zip : ""; ?>
                                    </li>
                                </ul>
                            </div>
                            <div class="wrapper__inner align--right">
                                <ul class="list list--inline list--divided">
                                    <li class="item" style="padding-right:24px;">
                                        <?php if ($vendor_details->phone != null) { ?>
                                            <span class="fontWeight--2">Phone:</span>
                                            <?php
                                            $phonenum = $vendor_details->phone;
                                            echo "(" . substr($phonenum, 0, 3) . ") " . substr($phonenum, 3, 3) . "-" . substr($phonenum, 6);
                                            ?>
                                            <br>
                                        <?php } ?>
                                        <?php if ($vendor_details->fax != null) { ?>
                                            <span class="fontWeight--2">Fax:</span>
                                            <?php
                                            $fax = $vendor_details->fax;
                                            echo "(" . substr($fax, 0, 3) . ") " . substr($fax, 3, 4) . "-" . substr($fax, 6);
                                            ?>
                                            <br>
                                        <?php } ?>
                                        <?php if ($vendor_details->email != null) { ?>
                                            <a class="link"><?php echo $vendor_details->email; ?></a>
                                        <?php } ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="padding--s no--pad-t">
                            <!-- <svg class="icon icon--cc icon--visa"></svg> -->
                            <?php
                            if ($payments != null) {
                                if (($payments->payment_type) == 'card') {
                                    if ($payments->card_type == "Visa") {
                                        ?>
                                        <svg class="icon icon--cc icon--visa"></svg>
                                        <?php
                                        echo $type = $payments->card_type . " •••• " . $payments->cc_number;
                                    } else if ($payments->card_type == "MasterCard") {
                                        ?>
                                        <svg class="icon icon--cc icon--mastercard"></svg>
                                        <?php
                                        echo $type = $payments->card_type . " •••• " . $payments->cc_number;
                                    } else if ($payments->card_type == "Discover Card") {
                                        ?>
                                        <svg class="icon icon--cc icon--discover"></svg>
                                        <?php
                                        echo $type = $payments->card_type . " •••• " . $payments->cc_number;
                                    }
                                    else if ($payments->card_type == "American Express") {
                                        ?>
                                        <svg class="icon icon--cc icon--amex"></svg>
                                        <?php
                                        echo $type = $payments->card_type . " •••• " . $payments->cc_number;
                                    } else {
                                        ?>
                                        <svg class="icon icon--cc icon--undefined"></svg>
                                        <?php
                                        echo $type = 'Other Card' . " •••• " . $payments->cc_number;
                                    }
                                } elseif (($payments->payment_type) == 'bank') {
                                    $account_number = substr($payments->ba_account_number, -4);
                                    ?>
                                    <svg class="icon icon--cc icon--bank"></svg>
                                    <?php echo $payments->bank_name; ?> •••• <?php
                                    print $account_number;
                                }
                            }
                            ?>
                        </div>
                        <table class="table table--invoice">
                            <thead>
                                <tr>
                                    <th width="70%">
                                        Item
                                    </th>
                                    <th width="20%">
                                        Unit Price
                                    </th>
                                    <th width="100%">
                                        Qty
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $subtotal = 0;
                                $tax = 0;
                                $product_new_price = 0;
                                $promo_discount = 0;
                                for ($i = 0; $i < count($order_details); $i++) {
                                    $user_name = $_SESSION['user_name'];
                                    $s_total = $order_details[$i]->total;
                                    $pro_price = $order_details[$i]->price;
                                    $qty = $order_details[$i]->quantity;
                                    $p_price = $qty * $pro_price;
                                    $subtotal = $subtotal + $s_total;
                                    $tax = $orders->tax;
                                    $shipping_price = $order_details[$i]->shipping_price;
                                    $product_new_price = $product_new_price + $p_price;
                                    ?>
                                    <!-- Line Item -->
                                    <tr>
                                        <td>
                                            <!-- Product -->
                                            <?php if ($order_details[$i]->product->license_required == 'Yes') { ?>
                                                <div class="product product--s row multi--vendor req--license padding--xxs">
                                                <?php } else { ?>
                                                    <div class="product product--s row multi--vendor  padding--xxs">
                                                    <?php } ?>
                                                    <div class="product__image col-md-4 col-xs-12">
                                                        <?php
                                                        if ($order_details[$i]->product_image != null) {
                                                            ?>
                                                            <div class="product__thumb"  style="background-image:url('<?php echo image_url(); ?>uploads/products/images/<?php echo $order_details[$i]->product_image->photo; ?>');"> </div>
                                                        <?php } else { ?>
                                                            <div class="product__thumb"  style="background-image:url('<?php echo image_url(); ?>assets/img/product-image.png');"></div>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="product__data col-md-8 col-xs-12">
                                                        <span class="product__name is--link" data-target="view-product?id=<?php echo $order_details[$i]->product->id; ?>"><?php echo $order_details[$i]->product->name; ?></span><br>
                                                        <span class="product__mfr">
                                                            by <a class="link fontWeight--2" href="#"><?php echo $order_details[$i]->product->manufacturer; ?></a>
                                                        </span>
                                                    </div>
                                                </div>
                                                <!-- /Product -->
                                        </td>
                                        <td>
                                            <?php if ($order_details[$i]->price > 0) {
                                                ?>
                                                $<?php
                                                echo $order_details[$i]->price;
                                            } else {
                                                ?>
                                    <strike>$<?php echo $order_details[$i]->Product_details->price; ?></strike> <?php echo " Free"; ?>
                                <?php }
                                ?>
                                <?php //echo number_format(floatval($order_details[$i]->price), 2, ".", ""); ?>
                                </td>
                                <td>
                                    <?php echo $order_details[$i]->quantity; ?>
                                </td>
                                </tr>
                            <?php } ?>
                            <!-- /Line Item -->
                            </tbody>
                        </table>
                        <div class="inv__totals">
                            <div class="wrapper">
                                <div class="wrapper__inner">
                                    <h5 class="textColor--dark-gray">Shipping to:</h5>
                                    <span class="fontWeight--2"><?php echo $user_name; ?></span><br>
                                    <?php
                                    if ($shipping_address->address1 != null) {
                                        echo $shipping_address->address1;
                                    }
                                    ?>
                                    <?php
                                    if ($shipping_address->address2 != null) {
                                        echo $shipping_address->address2;
                                    }
                                    ?> <br>
                                    <?php
                                    if ($shipping_address->state != null) {
                                        echo $shipping_address->state . ",";
                                    } if ($shipping_address->zip != null) {
                                        echo $shipping_address->zip;
                                    }
                                    ?>
                                </div>
                                <div class="wrapper__inner align--right">
                                    <span class="fontWeight--2 textColor--dark-gray fontSize--s disp--block margin--xs no--margin-lr no--margin-t">Subtotal: $<?php echo number_format($subtotal, 2, '.', ','); ?><br>
                                        Tax: $<?php echo number_format($tax, 2, '.', ','); ?><br>
                                        Shipping: $<?php echo number_format($shipping_price, 2, '.', ','); ?><br> </span>
                                    <?php if ($promos != null) { ?>
            <!--                                        <span class="textColor--accent promo_code">-->
                                        <span class="fontSize--s fontWeight--2 textColor--accent promo_code">
                                            Promo –  <?php
                                            for ($k = 0; $k < count($promos); $k++) {
                                                if ($promos[$k]->promocode != null) {
                                                    echo $promos[$k]->promocode->code . ": (-$";
                                                    echo
                                                    number_format(floatval($promos[$k]->discount_value), 2, ".", "") . ") <br>";
                                                    $promo_discount += $promos[$k]->discount_value;
                                                }
                                            }
                                            ?>
                                        </span>
                                    <?php } ?>
                                    <?php
                                    $total = $subtotal + $tax + $shipping_price - ($promo_discount);
                                    $total_value = round($total, 2);
                                    ?>
                                    <span class="fontWeight--2">Total: $<?php echo number_format($total_value, 2, '.', ','); ?></span>
                                </div>
                            </div>
                            <?php if ($promos != null) { ?>
                                <?php for ($k = 0; $k < count($promos); $k++) { ?>
                                    <?php if ($promos[$k]->promocode != null) { ?>
                                        <?php if ($promos[$k]->promocode->manufacturer_coupon == 1) { ?>
                                            <div class="wrapper">
                                                <b>Redemption instructions for promo code:
                <!--                                                    <span class="textColor--accent promo_code">-->
                                                    <span class="fontSize--s fontWeight--2 textColor--accent promo_code">
                                                        <?php echo $promos[$k]->promocode->code; ?></span></b>
                                                <p><?php echo $promos[$k]->promocode->conditions; ?></p>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
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
                <!-- Sidebar -->
                <div class="sidebar col-md-3 col-xs-12">
                    <div class="sidebar__group margin--m mobile-center">
                        <ul class="list">
                            <li class="item margin--s no--margin-t no--margin-lr">
                                <button class="btn btn--s btn--primary modal--toggle re-order" data-id="<?php echo $orders->id; ?>" data-target="#reorderModal">Order It Again</button>
                            </li>
                            <li class="item">
                                <a class="link modal--toggle recurring-orders" data-id="<?php echo $orders->id; ?>" data-target="#makeRecurringModal">Make Recurring</a>
                            </li>
                            <?php if ($orders->order_status != 'Cancelled' && $orders->order_status != 'Shipped') { ?>
                                <!--                            <li class="item">
                                                                <a class="link modal--toggle order-returns" data-id="<?php echo $orders->id; ?>" data-target="#returnsModal">Return Item(s)</a>
                                                            </li>-->
                            <?php } ?>
                            <li class="item">
                                <a class="link print_view_order" href="javascript:void(0)">Print Order</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- /Sidebar -->
            </div>
        </div>
    </section>
    <!-- /Main Content -->
</div>
<!-- /Content Section -->
<!-- Modals -->


<?php $this->load->view('templates/_inc/shared/modals/reorder.php') ?>
<?php $this->load->view('templates/_inc/shared/modals/make-recurring.php') ?>
<?php $this->load->view('templates/_inc/shared/modals/returns.php') ?>
<?php $this->load->view('templates/_inc/shared/modals/add-new-license.php'); ?>

<!-- <?php include(INCLUDE_PATH . '/_inc/shared/modals/reorder.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/make-recurring.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/returns.php'); ?> -->
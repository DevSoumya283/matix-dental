<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <!-- Breadcrumbs Bar -->
    <div class="bar padding--xs bg--lightest-gray">
        <div class="wrapper wrapper--fixed">
            <ul class="list list--inline list--breadcrumbs">
                <li class="item">
                    <a class="link" href="<?php echo base_url('dashboard'); ?>">Account</a>
                </li>
                <li class="item">
                    <a class="link" href="<?php echo base_url('classes'); ?>">Manage Classes</a>
                </li>
                <li class="item">
                    <a class="link" href="<?php echo base_url(); ?>students?id=<?php echo $lists->class_id; ?>">Pending Orders</a>
                </li>
                <li class="item is--active">
                    Order <?php echo $order; ?>
                </li>
            </ul>
        </div>
    </div>

    <section class="content__wrapper wrapper--fixed bg--lightest-gray">
        <div class="content__main">
            <div class="row row--full-height">
                <div class="content col col--9-of-12">
                    <div class="invoice">
                        <div class="inv__head row">
                            <div class="col col--2-of-8 col--am">
                                <!--
                                    NOTE: This logo can be any size
                                -->
                                <?php if ($vendor_images != null) { ?>
                                    <img class="inv__logo" src="<?php echo base_url() ?>uploads/vendor/logo/<?php echo $vendor_images->photo; ?>" alt="">
                                <?php } else { ?>

                                <?php } ?>
                            </div>
                            <div class="col col--4-of-8 col--push-2-of-8 col--am align--right">
                                <span class="fontWeight--2 textColor--dark-gray">Order:</span>
                                <span class="fontWeight--2"><?php echo $order; ?></span>
                            </div>
                        </div>
                        <div class="inv__contact wrapper">
                            <div class="wrapper__inner fontWeight--2">
                                Requested by:
                            </div>
                            <div class="wrapper__inner align--right">
                                <div class="entity__group">
                                    <?php if ($user_images != null) { ?>
                                        <div class="avatar avatar--s" style="background-image:url('<?php echo image_url(); ?>uploads/user/profile/<?php echo $user_images->photo; ?>');"></div>
                                    <?php } else { ?>
                                        <div class="avatar avatar--s" style="background-image:url('<?php echo image_url(); ?>assets/img/avatar-default.png');"></div>
                                    <?php } ?>

                                    <?php if ($users->first_name != null) {
                                        echo $users->first_name;
                                    } else {
                                        echo"";
                                    } ?>
                                </div>
                            </div>
                        </div>
                        <!-- Banner Notification -->
                        <div class="banner is--warning">
                            <span class="banner__text">
                                This order is pending approval for restricted items.
                            </span>
                        </div>
                        <br>
                        <!-- /Banner Notification -->
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
                                if ($restricted_orders != null) {

                                    $subtotal = 0;
                                    $tax = 0;
                                    $product_new_price = 0;
                                    $promo_discount = 0;
                                    $promo_discount = 0;
                                    for ($i = 0; $i < count($restricted_orders); $i++) {
//                                    $sub=$restricted_orders[$i]->total;
//                                       $Subtotal=$Subtotal+$sub;
                                        $s_total = $restricted_orders[$i]->total;
                                        $s_tax = $restricted_orders[$i]->tax;
                                        $pro_price = $restricted_orders[$i]->price;
                                        $qty = $restricted_orders[$i]->quantity;
                                        $p_price = $qty * $pro_price;
                                        $subtotal = $subtotal + $s_total;
                                        $tax = $tax + $s_tax;
                                        $shipping_price = $restricted_orders[$i]->shipping_price;
                                        $product_new_price = $product_new_price + $p_price;
                                        ?>
                                        <!-- Line Item -->
                                        <tr>
                                            <td>
                                                <!-- Product -->
                                                    <?php if ($restricted_orders[$i]->products->license_required == 'Yes') { ?>
                                                    <div class="product product--s row multi--vendor req--license padding--xxs">
                                                        <?php } else { ?>
                                                        <div class="product product--s row multi--vendor padding--xxs">
                                                            <?php } ?>
                                                        <div class="product__image col col--2-of-8 col--am">

                                                            <?php
                                                            if ($restricted_orders[$i]->product_images != null) {
                                                                ?>
                                                                <a class="product__thumb" href="#" style="background-image:url('<?php echo image_url(); ?>uploads/products/images/<?php echo $restricted_orders[$i]->product_images->photo; ?>');"></a>

                                                            <?php
                                                            } else {
                                                                ?>
                                                                <a class="product__thumb" href="#" style="background-image:url('<?php echo image_url(); ?>assets/img/product-image.png');"></a>

        <?php } ?>
                                                        </div>
                                                        <div class="product__data col col--6-of-8 col--am">
                                                            <span class="product__name"><?php echo $restricted_orders[$i]->products->name; ?></span>
                                                            <span class="product__mfr">
                                                                by <a class="link fontWeight--2" href="#"><?php echo $restricted_orders[$i]->products->manufacturer; ?></a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <!-- /Product -->
                                            </td>
                                            <td>
                                                <?php if ($restricted_orders[$i]->price > 0) {
                                                    ?>
                                                    $ <?php echo $restricted_orders[$i]->price;
                                                } else {
                                                    ?>
                                        <strike>$<?php echo $restricted_orders[$i]->Product_details->price; ?></strike> <?php echo " Free"; ?>
                                        <?php } ?>
                                        <?php //echo $restricted_orders[$i]->price; ?>
                                    </td>
                                    <td>
                                    <?php echo $restricted_orders[$i]->quantity; ?>
                                    </td>
                                    </tr>
    <?php }
} ?>
                            <!-- /Line Item -->
                            </tbody>
                        </table>
                        <div class="inv__totals">
                            <div class="wrapper">
                                <div class="wrapper__inner">
                                    <h5 class="textColor--dark-gray">Shipping to:</h5>
                                    <span class="fontWeight--2"><?php echo $users->first_name; ?></span><br>
<?php if ($location->address1 != null) {
    echo $location->address1;
} ?>
<?php if ($location->address2 != null) {
    echo $location->address2;
} ?> <br>
<?php if ($location->state != null) {
    echo $location->state . ",";
} if ($location->zip != null) {
    echo $location->zip;
} ?>

                                </div>
                                <div class="wrapper__inner align--right">
                                    <span class="fontWeight--2 textColor--dark-gray fontSize--s disp--block margin--xs no--margin-lr no--margin-t">Subtotal: $<?php echo number_format(floatval($subtotal), 2, ".", ""); ?><br>
                                        Tax: $<?php echo number_format(floatval($locations->tax), 2, ".", "") ?><br>
                                        Shipping: $<?php echo number_format(floatval($locations->shipping_price), 2, ".", "") ?></span>
                                    <?php if ($promos != null) { ?>
        <!--                                        <span class="textColor--accent promo_code">-->
                                        <span class="fontSize--s fontWeight--2 textColor--accent promo_code">
                                            Promo –  <?php
                                    for ($k = 0; $k < count($promos); $k++) {
                                        echo $promos[$k]->promocode->code . ": ( -$";
                                        echo
                                        number_format(floatval($promos[$k]->discount_value), 2, ".", "") . ") <br>";
                                        $promo_discount += $promos[$k]->discount_value;
                                    }
                                    ?>
                                        </span>
<?php } ?>
<?php $total = $subtotal + $tax + $shipping_price - ($promo_discount);
?>
                                    <span class="fontWeight--2">Total: $<?php echo number_format(floatval($locations->total), 2, ".", "") ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Sidebar -->
                <div class="sidebar col col--2-of-12">
                    <div class="sidebar__group margin--m">
                        <ul class="list">
                            <li class="item margin--s no--margin-t no--margin-lr">
                                <button class="btn btn--l btn--block btn--primary modal--toggle approve_all" data-id="<?php echo $order; ?>" data-target="#approveOrderModal">Approve</button>
                            </li>
                            <li class="item margin--s no--margin-t no--margin-lr">
                                <button class="btn btn--s btn--block btn--secondary modal--toggle is--neg deny_all" data-id="<?php echo $order; ?>" data-target="#denyOrderModal">Deny</button>
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

<?php include(INCLUDE_PATH . '/_inc/shared/modals/approve-item-request.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/deny-item-request.php'); ?>


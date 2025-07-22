<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <!-- breadcrumb Bar -->
    <div class="bar padding--xs bg--lightest-gray">
        <div class="wrapper wrapper--fixed">
            <ul class="list list--inline list--breadcrumbs">
                <li class="item">
                    <a class="link" href="<?php echo base_url(); ?>vendor-orders">Orders</a>
                </li>
                <li class="item is--active">
                    Order <?php echo $order_details->id; ?>
                </li>
            </ul>
        </div>
    </div>
    <!-- /breadcrumb Bar -->

    <section class="content__wrapper has--sidebar-l bg--lightest-gray">
        <div class="content__main">
            <div class="row row--full-height">

                <!-- Sidebar -->
                <div class="sidebar col col--2-of-12" style="padding: 12px;">
                    <?php //include(INCLUDE_PATH . '/vendor-admin/_inc/nav.php'); ?>
                    <?php $this->load->view('templates/vendor-admin/_inc/nav.php'); ?>

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
                            <div class="wrapper__inner align--right">
                                <ul class="list list--inline list--divided">
                                    <?php if ($order_details->order_status == "New") { ?>
                                        <li class="item">
                                            <input type="hidden" id="order_cancel_id" name="order_id" value="<?php echo $order_details->id; ?>">
                                            <a class="link is--neg fontWeight--2 fontSize--s modal--toggle order_cancel_GetID"  data-target="#cancelOrderModal">Cancel Order</a>
                                        </li>
                                        <li class="item">
                                            <?php if (empty($user_license)) { ?>
    <button class="btn btn--m btn" disabled>Process Order</button>
<?php } else { ?>
    <button class="btn btn--m btn--primary modal--toggle" data-target="#processOrderModal">Process Order</button>
<?php } ?>

                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- user licence -->
                   <?php if (empty($user_license)) : ?>
                  
                    <div class="well card is--warning">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <p class="fontWeight--2 fontSize--s">
                                    ⚠️ License not available or expired for this user.
                                       <a href="<?php echo base_url('customer-purchase-details?user_id=' . $order_details->user_id); ?>" class="link" style="color: #007bff; text-decoration: underline;">
                                    Customer Details
                                </a>
                                </p>
                            </div>                           
                        </div>
                    </div>
                <?php endif; ?>

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

                                switch ($order_details->card_type){
                                    case 'Visa':
                                        $cardType = 'visa';
                                        break;
                                    case 'MasterCard':
                                        $cardType = 'mastercard';
                                        break;
                                    case 'Discover Card':
                                        $cardType = 'discover';
                                        break;
                                    case 'American Express':
                                        $cardType = 'amex';
                                        break;
                                    default:
                                        $cardType = 'undefined';
                                }


                                ?><svg class="icon icon--cc icon--<?php echo $cardType?>"></svg><?php
                                echo $type = $order_details->card_type . " •••• ";
                                echo $order_details->cc_number;

                            } elseif (($order_details->payment_type) == 'bank') {
                                $account_number = substr($order_details->ba_account_number, -4);
                                ?>
                                <svg class="icon icon--cc icon--bank"></svg>
                                <?php echo $order_details->bank_name; ?> •••• <?php
                                print $account_number;
                            }
                            ?>


                        </div>
                        <div style="overflow: hidden; overflow-x: scroll;">
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
                                                        <span class="textColor--accent fontWeight--2 disp--block"><?php echo $purchase->title; ?></span>
                                                    </div>
                                                </div>
                                                <!-- /Product -->
                                            </td>
                                            <td>
                                                <?php echo $purchase->vendor_product_id; ?>
                                            </td>
                                            <td>
                                                <?php echo "$" . number_format(floatval($purchase->product_order_price), 2, ".", ""); ?>
                                            </td>
                                            <td>
                                                <?php echo $purchase->quantity; ?>
                                            </td>
                                            <td>
                                                <span style="display: none;"><?php echo $purchase->picked; ?></span>
                                                <input class="input input--qty <?php echo ($order_details->order_status == "New") ? "incOrderItemCount" : ""; ?>"  data-orderItem_id="<?php echo $purchase->orderItem_id; ?>" name="picked" value="<?php echo $purchase->picked; ?>" type="number">
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                                <!-- /Line Item -->
                            </tbody>
                        </table>
                        </div>
                        <div class="inv__totals">
                            <div class="wrapper">
                                <div class="wrapper__inner">
                                    <?php if ($order_address != null) { ?>
                                        <h5 class="textColor--dark-gray">Shipping to:</h5>
                                        <span class="fontWeight--2"><?php echo ($user_details != null) ? $user_details->organization->organization_name : ""; ?></span><br>
                                        <span class="fontWeight--2"><?php echo $user_details->first_name . ' ' . $user_details->last_name; ?></span> <br/>
                                        <?php echo $order_address->address1; ?><?php echo $order_address->address2; ?>
                                        <?php // echo $order_address->city;  ?>
                                        <?php echo ($order_address->state != null) ? "<br>" . $order_address->state . " " : ""; ?><?php echo $order_address->zip; ?>
                                    <?php } ?>
                                </div>
                                <div class="wrapper__inner align--right">
                                    <?php foreach ($calculation_section as $total) { ?>
                                        <span class="fontWeight--2 textColor--dark-gray fontSize--s disp--block margin--xs no--margin-lr no--margin-t">Subtotal: <?php echo "$" . number_format(floatval($grand_total), 2, ".", ""); ?><br>
                                            Tax: <?php echo "$" . number_format(floatval($order_details->tax), 2, ".", ""); ?><br>
                                            Shipping: <?php echo "$" . number_format(floatval($order_details->shipping_price), 2); ?></span>
                                        <?php if ($allpromotions != null) { ?>
                    <!--                                    <span class="textColor--accent promo_code">-->
                                            <span class="fontSize--s fontWeight--2 textColor--accent promo_code">
                                                <?php foreach ($allpromotions as $promo) { ?>
                                                    Promo - <?php echo $promo->code; ?>:<?php echo "$" . number_format(floatval($promo->discount_value), 2, ".", ""); ?><br>
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

                                <?php foreach ($vendor_message as $notes) { ?>
                                    <li class="item item--note">
                                        <div class="wrapper">
                                            <div class=" col col--2-of-12 wrapper__inner align--center padding--m no--pad-tb">
                                                <div class="entity__group">
                                                    <?php if ($notes->model_photo != null) { ?>
                                                        <div class="avatar avatar--s" style="background-image:url('<?php echo image_url(); ?>uploads/user/profile/<?php echo $notes->model_photo; ?>')margin:0 0 8px 0;"></div>
                                                    <?php } else { ?>
                                                        <div class="avatar avatar--s" style="background-image:url('<?php echo image_url(); ?>assets/img/avatar-default.png'); margin:0 0 8px 0;"></div>
                                                    <?php } ?>
                                                    <span class="disp--block fontWeight--2"><?php echo $notes->user_name; ?></span>
                                                </div>
                                                <span class="fontSize--xs"><?php echo date('M d, Y', strtotime($notes->created_at)); ?></span>
                                            </div>
                                            <div class="col col--10-of-12 wrapper__inner">
                                                <div class="well card" style="min-height: 85px;">
                                                    <?php echo $notes->message; ?>
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

                    <!-- Recent Activity -->
                    <div class="well">
                        <div class="heading__group border--dashed wrapper">
                            <div class="wrapper__inner">
                                <h4>Recent Activity</h4>
                            </div>
                        </div>
                        <ul class="list list--activity fontSize--s">
                            <?php if ($vendor_notes != null) { ?>
                                <?php foreach ($vendor_notes as $user_notes) { ?>
                                    <li class="item">
                                        <div class="entity__group">
                                            <?php if ($user_notes->model_photo != null) { ?>
                                                <div class="avatar avatar--xs" style="background-image:url('<?php echo image_url(); ?>uploads/user/profile/<?php echo $user_notes->model_photo; ?>')"></div>
                                            <?php } else { ?>
                                                <div class="avatar avatar--xs" style="background-image:url('<?php echo image_url(); ?>assets/img/avatar-default.png');"></div>
                                            <?php } ?>
                                            <span class="fontWeight--2"><?php echo $user_notes->user_name; ?></span> marked this order <span class="fontWeight--2"><?php echo $user_notes->status; ?></span> <span class="fontSize--xs textColor--dark-gray"><?php // echo date('M d, Y', strtotime($user_notes->created_at));    ?> <?php echo ($user_notes->created_atUpdate != null) ? $user_notes->created_atUpdate . " ago" : ""; ?></span>
                                        </div>
                                    </li>
                                    <?php
                                }
                            }
                            ?>
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



<?php $this->load->view('templates/_inc/shared/modals/process-order.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/add-note.php'); ?>
<?php //$this->load->view('templates/_inc/footer-vendor.php'); ?>



<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title></title>
        <meta name="description" content="">
        <?php //echo "<pre>";print_r($cart); exit();?>
        <!-- Icons -->
        <?php include(INCLUDE_PATH . '/_inc/icons.php'); ?>

        <!-- build:css css/main.min.css -->
        <link href="<?php echo base_url(); ?>assets/css/main.css" rel="stylesheet" type="text/css">
        <!-- endbuild -->

        <!-- Libraries -->
        <link href="<?php echo base_url(); ?>lib/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url(); ?>lib/animate-css/animate.css" rel="stylesheet" type="text/css">
<link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>assets/css/bootstrap.min.css">
        <script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
<link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>assets/css/responsive.css">
<style type="text/css">
            .input__combo.has--tip input {width: 220px !important;}
            .input__combo.has--tip{border: none !important}
        </style>

    </head>

    <body class="no--scroll bg--darkest-gray">
        <form id="confirmCheckout" action="<?php echo base_url("cartcheckout"); ?>" class="form__group" method="post">
                       <header class="padding--s bg--white shadow--m">
                <div class="">
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-xl-3 col-12  mobile-center">
                             <a class="btn btn--tertiary btn--s btn--icon btn--link fontSize--l is--link" href="<?php echo base_url(); ?>home">←</a>
                              <a class="logo logo__main" href="<?php echo base_url(); ?>home">
                                    <?php if($this->config->item('whitelabel')){ ?>
                                        <img src="/assets/img/logos/<?php echo $this->config->item('logo'); ?>" style="width:200px;" />
                                    <?php } else { ?>
                                        <img src="<?php echo base_url(); ?>assets/img/logo-matix-mark.svg" width="80" alt=""/>
                                    <?php } ?>
                                </a>
                                    <div class="text__group ml-4" style="display: inline-block;">
                                    <span class="disp--block">Shopping for</span>
                                    <div class="select select--text select--l">
                                        <select class="no--pad-l switch-view" name="location_name">
                                            <?php
                                            $final_total = 0;
                                            for ($i = 0; $i < count($user_locations); $i++) {
                                                ?>
                                                <option <?php if ($user_zip->id == $user_locations[$i]->id) echo 'selected'; ?> value="<?php echo $user_locations[$i]->id; ?>"><?php echo $user_locations[$i]->nickname; ?></option>

                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                        </div>

                           <div class="col-md-6 col-lg-6 col-xl-3 col-12 mobile-center mobile-pt30">
                                <span>
                                    <h5 class="textColor--dark-gray">Payment Method <a class="link fontWeight--2 fontSize--s modal--toggle change_payment" data-location_id="<?php echo $location_id; ?>" data-target="#changePaymentModal">Change</a></h5></span>

                                <span class="disp--ib align--left newpayment">
                                    <?php
                                    $final_total = 0;

                                            ?>
                                            <input type="hidden" name="payment_token" class="payment_token formvalue" value="<?php echo $user_payment_method->token; ?>">
                                            <input type="hidden" name="payment_id" class="pay_id" value="<?php echo $user_payment_method->id; ?>">
                                            <input type="hidden" name="payment_id" class="pay_id" value="<?php echo $user_payment_method->id; ?>">
                                            <?php
                                            if (($user_payment_method->payment_type) == 'card') {
                                                $card_number = preg_replace('/[^0-9]/', '', $user_payment_method->cc_number);
                                                $inn = (int) mb_substr($card_number, 0, 2);
                                                $card_num = substr($user_payment_method->cc_number, -4);
                                                ?>

                                                <?php if ($user_payment_method->card_type == 'Visa') { ?>

                                                    <svg class="icon icon--cc icon--visa"></svg>
                                                    <?php echo $user_payment_method->card_type . " •••• "; ?><?php echo $user_payment_method->cc_number; ?>
                                                <?php } else if ($user_payment_method->card_type == 'MasterCard') {
                                                    ?>

                                                    <svg class="icon icon--cc icon--mastercard"></svg>
                                                    <?php
                                                    echo $user_payment_method->card_type . " •••• ";
                                                    echo $user_payment_method->cc_number;
                                                    ?>
                                                <?php } else if ($user_payment_method->card_type == 'Discover') {
                                                    ?>

                                                    <svg class="icon icon--cc icon--discover"></svg>
                                                    <?php
                                                    echo $user_payment_method->card_type . " •••• ";
                                                    echo $user_payment_method->cc_number;
                                                    ?>
                                                <?php } else if ($user_payment_method->card_type == 'American Express') {
                                                    ?>

                                                    <svg class="icon icon--cc icon--amex"></svg>
                                                    <?php
                                                    echo $user_payment_method->card_type . " •••• ";
                                                    echo $user_payment_method->cc_number;
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
                                            } elseif (($user_payment_method->payment_type) == 'bank') {
                                                $account_number = substr($user_payment_method->ba_account_number, -4);
                                                ?>
                                                <svg class="icon icon--cc icon--bank"></svg>
                                                <?php echo $user_payment_method->bank_name; ?> •••• <?php
                                                print $account_number;
                                    }
                                    ?>

                                </span>
                           </div>
                            <div class="col-md-6 col-lg-4 col-xl-2 col-12 mobile-center mobile-pt30">
                              <div class="input__combo has--tip promocode" data-tip="Applied to all applicable orders" data-tip-position="right" style="width: 300px;">
                                    <div style="display: block; width: 200px;">
                                        <div class="input__group is--inline no-padding" style="white-space: nowrap;">
                                            <input id="promoCode" name="promocode" class="input" type="text" value="" style="border-right: 1px solid #ccc; border-radius: 5px; width: 200px;"><input name="location_id" class="input location_id" type="hidden" value="<?php echo $location_id; ?>"><label class="label" for="promoCode">Promo Code</label>
                                        </div>
                                    </div>
                                    <div class="btn__group">
                                        <a class="btn btn--m btn--secondary apply-promocode" data-location_id="<?php echo $location_id; ?>">Apply</a>
                                    </div>
                                </div>
                           </div>
                           <div class="col-md-6 col-lg-6 col-xl-4 col-12 mobile-center mobile-pt30">
                              <div class="wrapper__inner align--right">
                                <ul class="list list--inline list--divided">
                                    <li class="item">
                                        <span class="disp--ib align--left fontWeight--2">
                                            <h5 class="textColor--dark-gray">Combined Total</h5>

                                            $<span class="total" id="overall"><?php echo number_format($total, 2, '.', ','); ?></span>
                                        </span>
                                    </li>
                            <li class="item first">

                                <button class="btn btn--l btn--primary modal--toggle checkout button_new submitOrderButton"   id="orderbutn">
                                    <?php
                                    $matix_count = 0;
                                    $independent_count = 0;
                                    $order_count = 0;
                                    $matixtotal = 0;
                                    if (isset($matix_vendor)) {
                                        $matix_count = count($matix_vendor);
                                    }
                                    if (isset($cart_details)) {
                                        $independent_count = count($cart_details);
                                    }
                                    $order_count = $independent_count + $matix_count;
                                    ?>
                                    Submit <span id="orderQty"><?php echo $order_count; ?></span> Orders
                                </button>
                            </li>
                        </ul>
                    </div>
                           </div>
                    </div>
                </div>
            </header>
            <div class="error_holder">
                <?php if ($this->session->flashdata('success') != "") { ?>
                    <div class="banner is--pos">
                        <span class="banner__text">
                            <?php echo $this->session->flashdata('success') ?>
                        </span>
                        <a class="link link--icon dismiss--banner"><svg class="icon icon--x"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-x"></use></svg></a>
                    </div>
                    <br />
                <?php } ?>
                <?php if ($this->session->flashdata('error') != "") { ?>
                    <div class="banner is--neg">
                        <span class="banner__text">
                            <?php echo $this->session->flashdata('error') ?>
                        </span>
                        <a class="link link--icon dismiss--banner"><svg class="icon icon--x"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-x"></use></svg></a>
                    </div>
                    <br />
                <?php } ?>
            </div>

            <?php
                // get vendor count
                $count = 0;
                if (isset($matix_vendor) && count($matix_vendor) > 0) {
                    $count++;
                }
                $vendor_count = count($cart_details);
                $cart = $cart_details;
                $key = array_keys($cart);
                for ($i = 0; $i < $vendor_count; $i++) {
                    foreach ($vendors as $vendor_data) {
                        if ($key[$i] == $vendor_data->id) {
                            $count++;
                        }
                    }
                }
                Debugger::debug($matix_vendor, '$matix_vendor');
                Debugger::debug($count, '$count');
                if($count > 1){
                    $lit = false;
                    ?>
                    <div class="row d-lg-none d-md-none">
                        <div class="col-md-3"></div>
                        <div class="col-md-6 col-sm-12 bg-white mt-1 p-2">
                            <div>Your cart has orders from <?php echo $count; ?> vendors.  To see the orders, please click the vendor names.</div>

                            <?php
                                $count = 0;
                                if (isset($matix_vendor) && count($matix_vendor) > 0) {
                                    $count++;
                                $lit = true; ?>
                                <!-- Order ( from Matix vendor group start) -->
                                <div class="text-center">
                                    <a class="cart_tap btn btn-primary mt-1 cart_tab on"  data-orderid="<?php echo $count; ?>" >MatixDental</a>
                                </div>

                            <?php }

                            $vendor_count = count($cart_details);
                            $cart = $cart_details;
                            $key = array_keys($cart);
                            for ($i = 0; $i < $vendor_count; $i++) {
                                foreach ($vendors as $vendor_data) {
                                    if ($key[$i] == $vendor_data->id) {
                                        $count++; ?>
                                        <div class="text-center">
                                            <a class="cart_tab btn btn-primary mt-1 cart_tab <?php if(empty($lit)){ echo "on"; $lit = true; } ?>"  data-orderid="<?php echo $count; ?>"><?php echo $vendor_data->name; ?></a>
                                        </div>
                                    <?php
                                    }
                                }
                            } ?>

                        </div>
                        <div class="col-md-3"></div>

                    </div>

                <?php } ?>


            <div class="p-2 mt-xs-0 mt-md-5">
                <div class="">
                        <?php
                        $count = 0;
                        $lit = false;
                        if (isset($matix_vendor) && count($matix_vendor) > 0) {
                            $count++;
                            $lit = true; ?>
                            <!-- Order ( from Matix vendor group start) -->
                            <div class="cart_tab on d-none d-md-inline" data-orderid="<?php echo $count; ?>">
                                MatixDental
                                <button class="btn btn--s btn--icon btn--tertiary btn--link modal--toggle close--tab close--vendor" data-counter="" data-count_items="<?php echo count($matix_vendor); ?>" data-logo="" data-vendor_id="" data-order_id="<?php echo $count; ?>" data-l_id="<?php echo $user_zip->id; ?>" data-target="#removeMatixModal"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></button>
                            </div>
                        <?php } ?>

                        <?php
                        $vendor_count = count($cart_details);
                        $cart = $cart_details;
                        $key = array_keys($cart);
                        for ($i = 0; $i < $vendor_count; $i++) {
                            foreach ($vendors as $vendor_data) {
                                if ($key[$i] == $vendor_data->id) {
                                    $count++; ?>
                                    <div class="cart_tab d-none d-md-inline <?php if(empty($lit)){ echo "on"; $lit = true; }  ?> p-2 rounded-top" data-orderid="<?php echo $count ?> ">
                                        <?php echo $vendor_data->name; ?>
                                        <button class="btn btn--s btn--icon btn--tertiary btn--link modal--toggle close--tab close--vendor" data-counter="<?php echo $key[$i]; ?>" data-count_items="<?php echo $product_count ?>" data-logo="<?php echo $vendor_logo ?>" data-vendor_id="<?php echo $key[$i]; ?>" data-order_id="<?php echo $count; ?>" data-l_id="<?php echo $user_zip->id; ?>"  data-target="#removeOrderModal"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></button>
                                    </div>
                                <?php } ?>
                            <?php }
                        }
                    $count = 0;
                    ?>
                    <?php if (isset($matix_vendor) && count($matix_vendor) > 0) {
                        $count++;
                        // Debugger::debug($matix_vendor)
                        ?>
                        <!-- Order ( from Matix vendor group start) -->
                        <div id="order<?php echo $count; ?>" class="page__tab col col-md-12 bg-white">

                            <div class="tab__head">
                                <div class="wrapper">
                                    <div class="wrapper__inner" style="width:168px; padding-right:16px;">
                                        <img class="inv__logo" src="<?php echo base_url() . 'assets/img/logo-matix.svg'; ?>" alt="">
                                    </div>
                                    <div class="wrapper__inner align--right">
                                        Items from: <span class="fontWeight--2"><?php echo count($matix_vendor); ?> Vendors</span>
                                    </div>
                                </div>
                            </div>
                            <div class="tab__content">
                                <div class="scroll scroll--y">
                                    <!-- Vendor Group -->
                                    <?php
                                    $matix_vendor_count = count($matix_vendor);
                                    $matix = $matix_vendor;
                                    $keys = array_keys($matix);
                                    $shipping_total = 0;
                                    $matix_total = 0;
                                    $matix_tax = 0;
                                    $matix_shipping = 0;
                                    $shipping_price = 0;
                                    Debugger::debug($keys, '$keys');
                                    for ($i = 0; $i < $matix_vendor_count; $i++) {
                                        $matix_product_count = count($matix[$keys[$i]]);
                                        ?>
                                        <div class="heading__group no--margin-b">
                                            <div class="wrapper">
                                                <div class="wrapper__inner">
                                                    <h4 class="textColor--darkest-gray">
                                                        <?php
                                                        foreach ($vendors as $vendor_data) {
                                                            if ($keys[$i] == $vendor_data->id) {
                                                                print_r($vendor_data->name);
                                                            }
                                                        }
                                                        ?>
                                                    </h4>
                                                </div>
                                                <div class="wrapper__inner align--right">
                                                    <div class="select select--text">
                                                        <label class="label">Shipping:</label>
                                                        <input type="hidden" name="ven_id[]" class="vendor_id" value="<?php echo $keys[$i] ?>">
                                                        <select class="no--pad-l shippings_type" name="shipping_type[]" data-counter="<?php echo $keys[$i]; ?>" id="shipping_type<?php echo $keys[$i]; ?>" required/>
                                                        <?php
                                                        $bFound = false;
                                                        Debugger::debug($keys[$i]);
                                                        for ($k = 0; $k < count($shipping); $k++) {

                                                            if ($keys[$i] == $shipping[$k]->vendor_id) {
                                                                if ((isset($_SESSION['session_shipping'])) && $_SESSION['session_shipping'] != "") {
                                                                    foreach ($_SESSION['session_shipping'] as $shippvalue) {
                                                                        if ($shippvalue['shipvendor'] == $shipping[$k]->vendor_id) {
                                                                            // Debugger::debug($_SESSION);
                                                                            $shipping_price = $shippvalue['shippingprice'];
                                                                            ?>


                                                                            <option <?php
                                                                            if ($shippvalue['shipid'] == $shipping[$k]->id) {
                                                                                echo "selected";
                                                                                $shipping_total = $shippvalue['shippingprice'];
                                                                            }
                                                                            ?> value="<?php echo $shipping[$k]->id; ?>"><?php
                                                                                    echo $shipping[$k]->shipping_type . "(";
                                                                                    echo $shipping[$k]->delivery_time . "/";
                                                                                    echo "$" . $shipping[$k]->shipping_price . ")"
                                                                                    ?></option>

                                                                            <?php
                                                                            $bFound = TRUE;
                                                                        }
                                                                    }
                                                                }
                                                                // Debugger::debug($bFound);
                                                                if (!$bFound) {
                                                                    ?>
                                                                    <option value="<?php echo $shipping[$k]->id; ?>">
                                                                        <?php
                                                                        echo $shipping[$k]->shipping_type . "(";
                                                                        echo $shipping[$k]->delivery_time . "/";
                                                                        echo "$" . $shipping[$k]->shipping_price . ")"
                                                                        ?></option>
                                                                    <?php
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        </select>
                                                        <input type="hidden" name="shipping_charge[]" class="shipping_amnt" id="shipping_amount_<?php echo $keys[$i]; ?>" value="<?php echo $shipping_price; ?>">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <table class="table table--invoice">
                                            <thead class="fontSize--s mh-200">
                                                <tr>
                                                    <th width="55%">
                                                        Item
                                                    </th>
                                                    <th class="align--center" width="20%">
                                                        Unit Price
                                                    </th>
                                                    <th class="align--center" width="25%">
                                                        Qty
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Line Item -->
                                                <?php
                                                $sub_total = 0;
                                                $promo_discount = 0;
                                                $tax = 0;
                                                $total_tax = 0;
                                                for ($j = 0; $j < $matix_product_count; $j++) {
                                                    ?>
                                                    <tr class="table__row">
                                                        <td>
                                                            <!-- Product -->
                                                            <?php //Debugger::debug($matix); ?>
                                                            <?php if ($matix[$keys[$i]][$j]->products->license_required == 'Yes') { ?>
                                                                <div class="product product--s req--license padding--xxs fontSize--s">
                                                            <?php } else { ?>
                                                                <div class="product product--s padding--xxs fontSize--s">
                                                            <?php } ?>
                                                                    <div class="product__data">
                                                                        <a  href="/view-product?id=<?php echo $matix[$keys[$i]][$j]->products->id; ?>">
                                                                        <span class="product__name is--link"><?php echo $matix[$keys[$i]][$j]->products->name; ?></span>
                                                                    </a>&nbsp;
                                                                        <?php if ($matix[$keys[$i]][$j]->cart['price'] != 0) { ?>
                                                                            <a class="link fontSize--xs fontWeight--2 delete--row cart-to-rlist" data-rowid="<?php echo $matix[$keys[$i]][$j]->cart['rowid']; ?>" data-vendor="<?php echo $matix[$keys[$i]][$j]->cart['ven_id'] ?>">Move to Request List</a>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                                <!-- /Product -->
                                                        </td>
                                                        <td class="align--center">
                                                            $<?php echo number_format(floatval($matix[$keys[$i]][$j]->cart['price']), 2, ".", ""); ?>

                                                        </td>

                                                        <td class="align--center">

                                                            <input type="hidden" name="rows_id[<?php echo $i ?>][]" class="row-id" value="<?php echo $matix[$keys[$i]][$j]->cart['rowid']; ?>">
                                                            <input type="hidden" name="session_rid[<?php echo $i ?>][]" class="row-id" value="<?php echo $matix[$keys[$i]][$j]->cart['rowid']; ?>">

                                                            <input type="hidden" name="product_id[<?php echo $i ?>]" value="<?php echo $matix[$keys[$i]][$j]->cart['pro_id'] ?>">
                                                            <input type="hidden" name="products_id[<?php echo $i ?>][]" value="<?php echo $matix[$keys[$i]][$j]->cart['pro_id']; ?>">
                                                            <input type="hidden"  name="subtotal[<?php echo $i ?>][]" class="sub_amount_<?php echo $keys[$i] ?>" value="<?php echo $matix[$keys[$i]][$j]->cart['subtotal']; ?>"  id="sub_amount_<?php echo $keys[$i] ?>" >
                                                            <input type="hidden" name="price[<?php echo $i ?>][]" value="<?php echo $matix[$keys[$i]][$j]->cart['price'] ?>">

                                                            <input type="hidden" name="vendor_id[<?php echo $i ?>]" value="<?php echo $matix[$keys[$i]][$j]->cart['ven_id']; ?>">

                                                            <?php if ($matix[$keys[$i]][$j]->cart['price'] != 0) { ?>
                                                                <input type="number" name="qty[<?php echo $i ?>][]" class="input input--qty input--s cart-qty"  min="1" value="<?php echo $matix[$keys[$i]][$j]->cart['qty']; ?>" >
                                                                <a class="link delete--row fontSize--l fontWeight--2 cart-delete" data-rowid="<?php echo $matix[$keys[$i]][$j]->cart['rowid']; ?>" style="transform:translateY(2px); margin-left:6px;">×</a>
                                                            <?php } else { ?>
                                                            <?php } ?>
                                                        </td>
                                                    </tr >

                                                    <?php
                                                    $ven_id = $matix[$keys[$i]][$j]->cart['ven_id'];
                                                    $subtotal = $matix[$keys[$i]][$j]->cart['subtotal'];
                                                    $aFlag = FALSE;
                                                    if ((isset($_SESSION['session_promos'])) && $_SESSION['session_promos'] != "") {
                                                        foreach ($_SESSION['session_promos']as $tax_value) {
                                                            if ($tax_value['vendorid'] == $keys[$i] && $tax_value['promolocation'] == $location_id) {
                                                                if ($tax_value['product_tax'] > 0) {
                                                                    $total_tax = $tax_value['product_tax'];
                                                                    $aFlag = True;
                                                                }
                                                            }
                                                        }
                                                    }
                                                    if (!$aFlag) {
                                                        if ($tax_details != null) {
                                                            $tax = ($subtotal * $tax_details->EstimatedCombinedRate);
                                                            $total_tax += $tax;
                                                        }
                                                    }
                                                    $sub_total += $subtotal;
                                                }
                                                ?>
                                                <?php
                                                if ((isset($_SESSION['session_promos'])) && $_SESSION['session_promos'] != "") {
                                                    $promocount = count($_SESSION['session_promos']);
                                                    $promos = $_SESSION['session_promos'];
                                                    $promokey = array_keys($_SESSION['session_promos']);
                                                    for ($k = 0; $k < $promocount; $k++) {
                                                        if ($promos[$promokey[$k]]['vendorid'] == $keys[$i] && $promos[$promokey[$k]]['promolocation'] == $location_id) {
                                                            if ($promos[$promokey[$k]]['free_productid'] != null) {
                                                                ?>
                                                                <tr class="table__row">
                                                                    <td ><span class="product__name"><?php print_r($promos[$promokey[$k]]['freeproduc_name']); ?> </span></td>
                                                                    <td class="align--center"> <?php if ($promos[$promokey[$k]]['free_price'] !== "") { ?> <strike>$<?php echo $promos[$promokey[$k]]['free_price']; ?></strike> <?php echo " Free"; ?><?php } ?></td>
                                                            <td class="align--center"> <input type="number" name="view_qty[]" class="input input--qty input--s cart-qty"  min="1" value="1" disabled></td>
                                                            </tr>

                                                            <?php
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                        <!-- Vendor Subtotal -->
                                        <div class="align--right margin--s no--margin-lr no--margin-b">
                                            <span class="fontWeight--2 textColor--dark-gray fontSize--xs disp--block margin--xs no--margin-lr no--margin-t">
                                                <input type="hidden" name="tax_total[]" class="tax" value="<?php echo $total_tax; ?>" id="tax_rate_<?php echo $keys[$i]; ?>">
                                                Tax: $<span class="tax" id="new_rate_<?php echo $keys[$i]; ?>"><?php echo number_format(floatval($total_tax), 2, ".", ","); ?></span><br>
                                                Shipping: $<span  id="shipping_rate_<?php echo $keys[$i] ?>"><?php echo number_format(floatval($shipping_total), 2, ".", ","); ?></span>
                                                <?php if ((isset($_SESSION['session_promos'])) && $_SESSION['session_promos'] != "") { ?>
                                                    <br>
                                                    <?php
                                                    $promocount = count($_SESSION['session_promos']);
                                                    $promos = $_SESSION['session_promos'];
                                                    $promokey = array_keys($_SESSION['session_promos']);
                                                    for ($k = 0; $k < $promocount; $k++) {
                                                        if ($promos[$promokey[$k]]['vendorid'] == $keys[$i]) {
                                                            ?>
                                                            <span class="promo__code target">
                                                                <a class="link margin--xxs no--margin-tb no--margin-l fontSize--m delete--target remove-promo" data-promoremove="<?php print_r($promokey[$k]); ?>">×</a>
                                                                <span class="textColor--accent promo_code" id="promo_code_<?php echo $keys[$i]; ?>">
                                                                    <input type="hidden" name="promocodes[]" class="promo_id" id="promo_id_<?php echo $keys[$i] ?>" value="<?php print_r($promos[$promokey[$k]]['promoid']) ?>">
                                                                    Promo – <?php print_r($promos[$promokey[$k]]['promocode']); ?>: (-$<?php print_r(number_format(floatval($promos[$promokey[$k]]['promodiscount']), 2, ".", ",")); ?>)
                                                                    <?php
                                                                    $sub_total -= $promos[$promokey[$k]]['promodiscount'];
                                                                    $promo_discount += $promos[$promokey[$k]]['promodiscount'];
                                                                    ?>

                                                                </span>
                                                                <br>
                                                            </span>
                                                            <?php
                                                        }
                                                    }
                                                }
                                                ?>
                                            </span>
                                            <?php
                                            $sub_total = $sub_total + $total_tax + $shipping_total;
                                            $matixtotal += $sub_total;
                                            $matix_tax += $total_tax;
                                            $matix_shipping += $shipping_total;
                                            ?>
                                            <input type="hidden" name="promo_discount[]" class="promo_discount_" id="promo_discount_<?php echo $keys[$i] ?>" value="<?php echo $promo_discount; ?>" >
                                            <input type="hidden" name="vendor_total[]" class="s_amount" value="<?php echo $sub_total; ?>" id="s_amount_<?php echo $keys[$i] ?>" >
                                            <span class="fontWeight--2 fontSize--s">Subtotal: $<span class="subtotal" id="subtotal_<?php echo $keys[$i]; ?>" ><?php echo number_format(floatval($sub_total), 2, ".", ","); ?></span></span>
                                        </div>
                                        <!-- /Vendor Group -->
                                        <hr>
                                    <?php }
                                    ?>
                                </div>
                            </div>
                            <div class="tab__footer wrapper">
                                <div class="wrapper">
                                    <div class="wrapper__inner">
                                        <h5 class="textColor--dark-gray">Shipping Method:</h5>
                                        Select above for each vendor.
                                    </div>
                                   <!--  <div class="wrapper__inner">
                                        <button class="btn btn--l btn--primary modal--toggle checkout button_new submitOrderButton" data-vendor_id="0"  id="orderbutn">
                                            Submit This Order
                                        </button>
                                    </div> -->
                                    <div class="wrapper__inner align--right">
                                        <span class="fontWeight--2 textColor--dark-gray fontSize--s disp--block margin--xs no--margin-lr no--margin-t">Tax: $<?php echo number_format(floatval($matix_tax), 2, ".", ","); ?><br>
                                            Shipping: $<?php echo number_format(floatval($matix_shipping), 2, ".", ","); ?></span>
                                        <span class="fontWeight--2">Order Total: $<?php echo number_format(floatval($matixtotal), 2, ".", ","); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <!-- Order ( from Matix vendor group end) -->
                    <!-- Order ( from Independent vendor group end) -->
                    <?php
                    $vendor_count = count($cart_details);
                    $cart = $cart_details;
                    $key = array_keys($cart);
                    $total = 0;
                    $shipping_total = 0;
                    for ($i = 0; $i < $vendor_count; $i++) {
                        $count++;
                        $product_count = count($cart[$key[$i]]);
                        ?>
                        <div id="order<?php echo $count; ?>" class="page__tab col col-md-12 <?php if($count > 1){ echo 'd-none'; } ?> bg-white">
                            <?php
                            foreach ($vendors as $vendor_data) {
                                if ($key[$i] == $vendor_data->id) {
                                    ?>
                                    <?php
                                    $vendor_logo = "";
                                    if ($image != null) {
                                        foreach ($image as $images) {
                                            if ($key[$i] == $images->model_id) {
                                                if ($images != null) {
                                                    $vendor_logo = $images->photo;
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                    <div class="tab__head">
                                        <div class="wrapper">
                                            <?php
                                            if ($image != null) {
                                                foreach ($image as $images) {
                                                    if ($key[$i] == $images->model_id) {
                                                        if ($images != null) {
                                                            ?>
                                                            <div class="wrapper__inner" style="width:168px; padding-right:16px;">
                                                                <img class="cart-logo" src="<?php echo base_url(); ?>uploads/vendor/logo/<?php echo $images->photo; ?>" alt="">
                                                            </div>
                                                            <?php
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                            <div class="wrapper__inner align--right">
                                                <ul class="list list--inline list--divided">
                                                    <li class="item" style="padding-right:24px;">
                                                        <?php if ($vendor_data->phone != null & $vendor_data->phone !== "") { ?>
                                                            <span class="fontWeight--2">Ph:</span>
                                                            <?php
                                                            $phonenum = $vendor_data->phone;
                                                            echo "(" . substr($phonenum, 0, 3) . ") " . substr($phonenum, 3, 3) . "-" . substr($phonenum, 6);
                                                            ?>
                                                            <br>
                                                        <?php } ?>
                                                        <a class="link"> <?php echo $vendor_data->email; ?></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                            <div class="tab__content">
                                <div class="scroll scroll--y">
                                    <table class="table table--invoice">
                                        <thead class="fontSize--s">
                                            <tr>
                                                <th width="55%">
                                                    Item
                                                </th>
                                                <th class="align--center" width="20%">
                                                    Unit Price
                                                </th>
                                                <th class="align--center" width="25%">
                                                    Qty
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Line Item -->
                                            <?php
                                            $sub_total = 0;
                                            $promo_discount = 0;
                                            $tax = 0;
                                            $total_tax = 0;
                                            $shipping_price = 0;
                                            for ($j = 0; $j < $product_count; $j++) {
                                                ?>
                                                <tr class="table__row">
                                                    <td>
                                                        <!-- Product -->
                                                        <?php if ($cart[$key[$i]][$j]->products->license_required == 'Yes') { ?>
                                                            <div class="product product--s req--license padding--xxs fontSize--s">
                                                        <?php } else { ?>
                                                            <div class="product product--s padding--xxs fontSize--s">
                                                        <?php } ?>
                                                                <div class="product__data">
                                                                <a href="/view-product?id=<?php echo $cart[$key[$i]][$j]->cart['pro_id']; ?>">
                                                                    <span class="product__name is--link"><?php echo $cart[$key[$i]][$j]->products->name; ?></span>
                                                                    <?php if ($cart[$key[$i]][$j]->cart['price'] != 0) { ?>
                                                                        <a class="link fontSize--xs fontWeight--2 delete--row cart-to-rlist" data-rowid="<?php echo $cart[$key[$i]][$j]->cart['rowid']; ?>" data-vendor="<?php echo $cart[$key[$i]][$j]->cart['ven_id'] ?>">Move to Request List</a>
                                                                    <?php } ?>
                                                                </a>
                                                                </div>
                                                            </div>
                                                            <!-- /Product -->
                                                    </td>
                                                    <td class="align--center">
                                                        $<?php echo number_format(floatval($cart[$key[$i]][$j]->cart['price']), 2, ".", ""); ?>

                                                    </td>

                                                    <td class="align--center">

                                                        <input type="hidden" name="rows_id[<?php echo $i ?>][]" class="row-id" value="<?php echo $cart[$key[$i]][$j]->cart['rowid']; ?>">
                                                        <input type="hidden" name="session_rid[<?php echo $i ?>][]" class="row-id" value="<?php echo $cart[$key[$i]][$j]->cart['rowid']; ?>">

                                                        <input type="hidden" name="product_id[<?php echo $i ?>]" value="<?php echo $cart[$key[$i]][$j]->cart['pro_id'] ?>">
                                                        <input type="hidden" name="products_id[<?php echo $i ?>][]" value="<?php echo $cart[$key[$i]][$j]->cart['pro_id']; ?>">
                                                        <input type="hidden"  name="subtotal[<?php echo $i ?>][]" class="sub_amount_<?php echo $key[$i] ?>" value="<?php echo $cart[$key[$i]][$j]->cart['subtotal']; ?>"  id="sub_amount_<?php echo $key[$i] ?>" >
                                                        <input type="hidden" name="price[<?php echo $i ?>][]" value="<?php echo $cart[$key[$i]][$j]->cart['price'] ?>">

                                                        <input type="hidden" name="vendor_id[<?php echo $i ?>]" value="<?php echo $cart[$key[$i]][$j]->cart['ven_id']; ?>">

                                                        <?php if ($cart[$key[$i]][$j]->cart['price'] != 0) { ?>
                                                            <input type="number" name="qty[<?php echo $i ?>][]" class="input input--qty input--s cart-qty"  min="1" value="<?php echo $cart[$key[$i]][$j]->cart['qty']; ?>" >
                                                            <a class="link delete--row fontSize--l fontWeight--2 cart-delete" data-rowid="<?php echo $cart[$key[$i]][$j]->cart['rowid']; ?>" style="transform:translateY(2px); margin-left:6px;">×</a>
                                                        <?php } else { ?>
                                                        <?php } ?>
                                                    </td>
                                                </tr >

                                                <?php
                                                $ven_id = $cart[$key[$i]][$j]->cart['ven_id'];
                                                $subtotal = $cart[$key[$i]][$j]->cart['subtotal'];
                                                $aFlag = FALSE;
                                                if ((isset($_SESSION['session_promos'])) && $_SESSION['session_promos'] != "") {
                                                    foreach ($_SESSION['session_promos']as $tax_value) {
                                                        if ($tax_value['vendorid'] == $key[$i] && $tax_value['promolocation'] == $location_id) {
                                                            if ($tax_value['product_tax'] > 0) {
                                                                $total_tax = $tax_value['product_tax'];
                                                                $aFlag = True;
                                                            }
                                                        }
                                                    }
                                                }
                                                if (!$aFlag) {
                                                    if ($tax_details != null) {
                                                        $tax = ($subtotal * $tax_details->EstimatedCombinedRate);
                                                        $total_tax += $tax;
                                                    }
                                                }
                                                $sub_total += $subtotal;
                                            }
                                            ?>
                                            <?php
                                            if ((isset($_SESSION['session_promos'])) && $_SESSION['session_promos'] != "") {
                                                $promocount = count($_SESSION['session_promos']);
                                                $promos = $_SESSION['session_promos'];
                                                $promokey = array_keys($_SESSION['session_promos']);
                                                for ($k = 0; $k < $promocount; $k++) {
                                                    if ($promos[$promokey[$k]]['vendorid'] == $key[$i] && $promos[$promokey[$k]]['promolocation'] == $location_id) {
                                                        if ($promos[$promokey[$k]]['free_productid'] != null) {
                                                            ?>
                                                            <tr class="table__row">
                                                                <td ><span class="product__name"><?php print_r($promos[$promokey[$k]]['freeproduc_name']); ?> </span></td>
                                                                <td class="align--center"> <?php if ($promos[$promokey[$k]]['free_price'] !== "") { ?> <strike>$<?php echo $promos[$promokey[$k]]['free_price']; ?></strike> <?php echo " Free"; ?><?php } ?></td>
                                                        <td class="align--center"> <input type="number" name="view_qty[]" class="input input--qty input--s cart-qty"  min="1" value="1" disabled></td>
                                                        </tr>

                                                        <?php
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                        </tbody>
                                    </table>

                                    <hr>
                                </div>
                            </div>
                            <div class="tab__footer wrapper">
                                <div class="wrapper">
                                    <div class="wrapper__inner" >
                                        <h5 class="textColor--dark-gray">Shipping Method:</h5>
                                        <div class="select select--text">
                                            <label class="label">Shipping:</label>
                                            <input type="hidden" name="ven_id[]" class="vendor_id" value="<?php echo $ven_id ?>">
                                            <select class="no--pad-l shippings_type" name="shipping_type[]" data-counter="<?php echo $key[$i]; ?>" id="shipping_type<?php echo $key[$i]; ?>" required/>
                                            <!--                       <option value="" selected >----Select Shipping Type-----</option>-->
                                            <?php
                                            $bFound = false;
                                            for ($k = 0; $k < count($shipping); $k++) {
                                                if ($key[$i] == $shipping[$k]->vendor_id) {
                                                    if ((isset($_SESSION['session_shipping'])) && $_SESSION['session_shipping'] != "") {
                                                        foreach ($_SESSION['session_shipping'] as $shippvalue) {
                                                            if ($shippvalue['shipvendor'] == $shipping[$k]->vendor_id) {
                                                                $shipping_price = $shippvalue['shippingprice'];
                                                                ?>


                                                                <option <?php
                                                                if ($shippvalue['shipid'] == $shipping[$k]->id) {
                                                                    echo "selected";
                                                                    $shipping_total = $shippvalue['shippingprice'];
                                                                }
                                                                ?> value="<?php echo $shipping[$k]->id; ?>"><?php
                                                                        echo $shipping[$k]->shipping_type . "(";
                                                                        echo $shipping[$k]->delivery_time . "/";
                                                                        echo "$" . $shipping[$k]->shipping_price . ")"
                                                                        ?></option>

                                                                <?php
                                                                $bFound = TRUE;
                                                            }
                                                        }
                                                    }
                                                    if (!$bFound) {
                                                        ?>
                                                        <option value="<?php echo $shipping[$k]->id; ?>">
                                                            <?php
                                                            echo $shipping[$k]->shipping_type . "(";
                                                            echo $shipping[$k]->delivery_time . "/";
                                                            echo "$" . $shipping[$k]->shipping_price . ")"
                                                            ?></option>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                            <input type="hidden" name="shipping_charge[]" class="shipping_amnt" id="shipping_amount_<?php echo $key[$i]; ?>" value="<?php echo $shipping_price; ?>">
                                            </select>
                                            <input type="hidden" name="tax_total[]" class="tax" value="<?php echo $total_tax; ?>" id="tax_rate_<?php echo $key[$i]; ?>">
                                        </div>
                                    </div>
                                    <!-- <div class="wrapper__inner">
                                        <button class="btn btn--l btn--primary modal--toggle checkout button_new submitOrderButton" data-vendor_id="<?php echo $key[$i]; ?>" id="orderbutn">
                                            Submit This Order
                                        </button>
                                    </div> -->
                                    <div class="wrapper__inner align--right">
                                        <span class="fontWeight--2 textColor--dark-gray fontSize--s disp--block margin--xs no--margin-lr no--margin-t" >
                                            Subtotal: $<span class="tax" id="new_rate_<?php echo $key[$i]; ?>"><?php echo number_format(floatval($sub_total), 2, ".", ","); ?></span><br>
                                            Tax: $<span class="tax" id="new_rate_<?php echo $key[$i]; ?>"><?php echo number_format(floatval($total_tax), 2, ".", ","); ?></span><br>
                                            Shipping: $<span  id="shipping_rate_<?php echo $key[$i] ?>"><?php echo number_format(floatval($shipping_total), 2, ".", ","); ?></span>

                                            <br>
                                            <?php
                                            if ((isset($_SESSION['session_promos'])) && $_SESSION['session_promos'] != "") {
                                                $promocount = count($_SESSION['session_promos']);
                                                $promos = $_SESSION['session_promos'];
                                                $promokey = array_keys($_SESSION['session_promos']);
                                                for ($k = 0; $k < $promocount; $k++) {
                                                    if ($promos[$promokey[$k]]['vendorid'] == $key[$i]) {
                                                        ?>
                                                        <span class="promo__code target">
                                                            <a class="link margin--xxs no--margin-tb no--margin-l fontSize--m delete--target remove-promo" data-promoremove="<?php print_r($promokey[$k]); ?>">×</a>
                                                            <span class="textColor--accent promo_code" id="promo_code_<?php echo $key[$i]; ?>">
                                                                <input type="hidden" name="promocodes[]" class="promo_id" id="promo_id_<?php echo $key[$i] ?>" value="<?php print_r($promos[$promokey[$k]]['promoid']) ?>">
                                                                Promo – <?php print_r($promos[$promokey[$k]]['promocode']); ?>: (-$<?php print_r(number_format(floatval($promos[$promokey[$k]]['promodiscount']), 2, ".", ",")); ?>)

                                                                <?php
                                                                $sub_total -= $promos[$promokey[$k]]['promodiscount'];
                                                                $promo_discount += $promos[$promokey[$k]]['promodiscount'];
                                                                ?>

                                                            </span>
                                                            <br>
                                                        </span>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>


                                            <?php
                                            $sub_total = $sub_total + $total_tax + $shipping_total;
                                            $total += $sub_total;
                                            ?>
                                            <input type="hidden" name="promo_discount[]" class="promo_discount_" id="promo_discount_<?php echo $key[$i] ?>" value="<?php echo $promo_discount; ?>" >
                                            <input type="hidden" name="vendor_total[]" class="s_amount" value="<?php echo $sub_total; ?>" id="s_amount_<?php echo $key[$i] ?>" >
                                        </span>
                                        <span class="fontWeight--2 ">Order Total: $</span><span class="subtotal" id="subtotal_<?php echo $key[$i]; ?>" ><?php echo number_format(floatval($sub_total), 2, ".", ","); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php
                    $final_total = round($total, 2) + round($matixtotal, 2);
                    ?>
                    <!-- Order ( from Independent vendor group end) -->
                </div>
            </div>
            <!-- /Content Section -->
        </form>
    </body>
    <script type="text/javascript">
        $(function () {
            $shipping = 0;
            $("#overall").html("<?php echo number_format(floatval($final_total), 2, ".", ","); ?>");
            //set cart order count
<?php
$matix_count = 0;
$independent_count = 0;
$order_count = 0;
if (isset($matix_vendor)) {
    $matix_count = count($matix_vendor);
}
if (isset($cart_details)) {
    $independent_count = count($cart_details);
}
$order_count = $independent_count + $matix_count;
?>
            $('#orderQty').html(<?php echo $order_count; ?>);

            //check cart values if empty redirect to home
            var order_total = $('.total').text();
            if (order_total == 0) {
                window.location.href = base_url + 'home';
            }
        });

        $('.submitOrderButton').on('click', function(){
            orderFormValidation(this);
        });

        function orderFormValidation(button)
        {
            console.log($(button).data('vendor_id'));

            var payment_id = $('.payment_token').val();
            var cart_total = $('#orderQty').html();

            if (cart_total > 0) {
                //if (payment_id != "" && payment_id !== undefined) { //live mode
                    var fields = $(".shippings_type");
                    var bError = false;
                    $.each(fields, function (i, field) {
                        if (!field.value) {
                            bError = true;
                            $(".error_holder").html('<div class="banner is--neg"><span class="banner__text">Select Shipping type</span><a class="link link--icon dismiss--banner"><svg class="icon icon--x"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-x"></use></svg></a></div>');

                        }
                    });
                // } else {
                //     $(".error_holder").html('<div class="banner is--neg"><span class="banner__text">This order cannot be processed. Please select or add a payment method</span><a class="link link--icon dismiss--banner"><svg class="icon icon--x"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-x"></use></svg></a></div>');
                // }

            }

            console.log('bError: '+bError);
            if (bError == false) {
                if($(button).data('vendor_id') == 'undefined'){
                    $('#orderbutn').data('target', '#confirmCheckoutModal');
                } else {
                    $('.submitOrderButton').data('target', '#confirmCheckoutSingleModal');
                    $('.vendor_id').val($(button).data('vendor_id'));
                }
            }

        }

    </script>

    <!-- Modals -->
                <?=$this->load->view('templates/_inc/shared/modals/change-payment.php'); ?>
    <?php include(INCLUDE_PATH . '/_inc/shared/modals/change-payment.php'); ?>
    <?php include('_modals/remove-order.php'); ?>
    <?php include('_modals/remove-matix.php'); ?>
    <?php include('_modals/confirm-checkout.php'); ?>
    <?php include('_modals/confirm-checkout-single.php'); ?>

    <!-- Scripts & Libraries -->
    <!--payment-->
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <script src="https://cdn.plaid.com/link/stable/link-initialize.js"></script>
    <!--payment-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery.detect-card.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery.maskedinput.min.js"></script>
    <script src="<?php echo base_url(); ?>lib/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="<?php echo base_url(); ?>lib/jquery-validate/jquery.validate.min.js"></script>

    <script src="<?php echo base_url(); ?>assets/js/main-addition.js"></script>
    <script type="text/javascript">
        var base_url = "<?php echo base_url(); ?>";
    var image_url = "<?php echo image_url(); ?>";
        Stripe.setPublishableKey('<?php echo $this->config->item('stripe')['pk_'.$this->config->item('stripe')['mode']];?>');
    </script>
    <!-- build:js js/main.min.js -->
    <script src="<?php echo base_url(); ?>assets/js/main.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/awesome.min.js"></script>
    <!-- endbuild -->

</html>

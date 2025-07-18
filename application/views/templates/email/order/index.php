<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no;" />
        <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />
        <meta name="format-detection" content="telephone=no" />
        <style type="text/css">


        </style>
        <!--[if gte mso 9]>
            <style type="text/css">
                #outlookholder {width:500px;}
            </style>
        <![endif]-->
    </head>
    <body style="width:100% !important; min-width: 100% !important; margin: 0; padding: 0; background-color: #E8EAF1; font-family: 'Lucida Grande', 'Arial', sans-serif;">
        <table cellpadding="0" cellspacing="0" border="0" width="100%" style=" width: 100%;" class="100p">
            <tr>
                <td align="center" valign="top" style="padding: 30px 20px 0 20px;">
                        <!--[if gte mso 9]><table id="outlookholder" border="0" cellspacing="0" cellpadding="0" align="center"><tr><td><![endif]-->
                        <!--[if (IE)]><table border="0" cellspacing="0" cellpadding="0" width="500" align="center"><tr><td><![endif]-->

                        <table cellpadding="0" cellspacing="0" border="0" width="600" style="width: 604px; background-color:#ffffff; padding: 30px 40px 30px 40px; border-bottom:1px solid #E8EAF1; border-top:4px solid #13C4A3;" class="100p">
                            <tr>
                                <td valign="top" class="header">
                                    <?php if(!empty($this->config->item('whitelabel'))){ ?>
                                        <div class="col--2-of-8 col--am" style="margin:0;padding:0;border:0;vertical-align:middle;display:table-cell;text-align:center;">
                                            <!--
                                                NOTE: This logo can be any size
                                            -->
                                                <img class="inv__logo" src="<?php echo config_item('site_url'); ?>assets/img/logos/<?php echo config_item('logo'); ?>" alt="" style="margin:0;padding:0;border:0;vertical-align:baseline;width:100%;">

                                        </div>



                                        <div class="col--4-of-8 col--push-2-of-8 col--am align--right" style="margin:0;padding:10px 20px 10px 0;border:0;vertical-align:middle;display:table-cell;text-align: right;line-height: 25px;">

                                            <?php
                                            $phonenum = $vendor_details->phone;
                                            echo "(" . substr($phonenum, 0, 3) . ") " . substr($phonenum, 3, 3) . "-" . substr($phonenum, 6);
                                            ?>
                                            <br/>
                                            <a class="link" style="margin:0;padding:0;border:0;vertical-align:baseline;text-decoration:none;display:inline-block;position:relative;color:#2893FF;"><?php echo $vendor_details->email; ?></a>
                                        </div>

                                    <?php } else { ?>
                                        <a href="#"><img src="<?php echo image_url(); ?>assets/img/matix@2x.png" width="220" style="display: block; width: 220px; height: 35px;" border="0" alt="Matix Dental" />
                                        </a>
                                    <?php } ?>
                                </td>
                            </tr>
                        </table>

                    <table cellpadding="0" cellspacing="0" border="0" width="600" style="width: 604px; background-color:#ffffff; padding:50px 60px 26px;" class="100p">
                        <tr>
                            <td style="font-size:30px;line-height:42px;font-family:Helvetica,Arial,sans-serif;color:#495165;text-align:center">
                                <?php echo $subject; ?>
                            </td>
                        </tr>
                    </table>




                    <table cellpadding="0" cellspacing="0" border="0" width="600" style="width: 604px; background-color:#ffffff; padding: 0px 40px 30px 40px; border-bottom:1px solid #E8EAF1;" class="100p">
                        <tr>
                            <td>
                                <!-- Main Content -->
                                <?php print_r($message); ?>
                                <section class="content__wrapper wrapper--fixed bg--lightest-gray" style="padding:0;border:0;vertical-align:baseline;display:flex;position:relative;"><div class="content__main" style="margin:0 auto;padding:0;border:0;vertical-align:baseline;width:100%;">
                                        <div class="row row--full-height" style="margin:0;padding:0;border:0;vertical-align:baseline;list-style:none;height:100%;">
                                            <div class="content col col--9-of-12" style="margin:0;padding:0;border:0;vertical-align:top;box-sizing:border-box;display:block;margin-right:0;min-height:1px;margin-left:0;width:100%;height:100%;">
                                                <?php if(empty($this->config->item('whitelabel'))){ ?>
                                                <div class="invoice" style="margin:0;padding:24px 0;border:0;vertical-align:baseline;background-color:#FFFFFF;">
                                                    <div class="row" style="margin:0;padding: 16px 0;border:0;vertical-align:baseline;width:100%;display:table;margin-top:16px;margin-bottom:24px;border:1px solid #E7EAF1;-moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius: 4px; border-collapse: separate !important;">
                                                        <div class="col--2-of-8 col--am" style="margin:0;padding:0;border:0;vertical-align:middle;display:table-cell;text-align:center;">
                                                            <!--
                                                                NOTE: This logo can be any size
                                                            -->
                                                            <?php
                                                            if ($vendor_image != null) { ?>
                                                                <img class="inv__logo" src="<?php echo config_item('site_url'); ?>uploads/vendor/logo/<?php echo $vendor_image->photo; ?>" alt="" style="margin:0;padding:0;border:0;vertical-align:baseline;width:100%;">
                                                                <?php } else { ?>
                                                                <img class="inv__logo" src="" alt="" style="margin:0;padding:0;border:0;vertical-align:baseline;width:100%;">
                                                                <?php } ?>

                                                        </div>



                                                        <div class="col--4-of-8 col--push-2-of-8 col--am align--right" style="margin:0;padding:10px 20px 10px 0;border:0;vertical-align:middle;display:table-cell;text-align: right;line-height: 25px;">

                                                            <?php
                                                            $phonenum = $vendor_details->phone;
                                                            echo "(" . substr($phonenum, 0, 3) . ") " . substr($phonenum, 3, 3) . "-" . substr($phonenum, 6);
                                                            ?>
                                                            <br/>
                                                            <a class="link" style="margin:0;padding:0;border:0;vertical-align:baseline;text-decoration:none;display:inline-block;position:relative;color:#2893FF;"><?php echo $vendor_details->email; ?></a>
                                                        </div>
                                                    </div>
                                                    <?php } ?>

                                                    <div class="inv__head row" style="margin:0;padding:16px 0;border:0;vertical-align:baseline;width:100%;display:table;margin-top:16px;margin-bottom:24px;">
                                                        <div class="col--4-of-8 col--am" class="col col--2-of-8 col--am" style="margin:0;padding:0;border:0;vertical-align:middle;display:table-cell;">
                                                            <span class="fontWeight--2" style="margin:0;padding:0;border:0;vertical-align:baseline;font-weight:bold !important;">Order:</span>
                                                            <span class="fontWeight--2" style="color: #2893FF;margin:0;padding:0;border:0;vertical-align:baseline;"><?php echo $orders->id; ?></span>
                                                        </div>

                                                        <div class="col--4-of-8 col--am align--right" style="margin:0;padding:0;border:0;vertical-align:middle;display:table-cell;text-align: right;">
                                                            <?php
                                                            if (($payments->payment_type) == 'card') {
                                                                $card_number = preg_replace('/[^0-9]/', '', $payments->cc_number);
                                                                $inn = (int) mb_substr($card_number, 0, 2);
                                                                $card_num = substr($payments->cc_number, -4);

                                                                if ($payments->card_type == "Visa") {
                                                                    ?>

                                                                    <svg class="icon icon--cc icon--visa" style='width:27px;height:19px;display:inline-block;fill:currentColor;margin-right:8px;vertical-align:middle;-webkit-transform:translateY(-2px);transform:translateY(-2px);background-color:transparent;background-image:url("../img/icons/credit-cards.png");background-position:-54px 0;'></svg>
                                                                    <?php
                                                                    echo $type = 'Visa' . "••••";
                                                                    echo $card_num;
                                                                } else if ($payments->card_type == "MasterCard") {
                                                                    ?>

                                                                    <svg class="icon icon--cc icon--mastercard" style='width:27px;height:19px;display:inline-block;fill:currentColor;margin-right:8px;vertical-align:middle;-webkit-transform:translateY(-2px);transform:translateY(-2px);background-color:transparent;background-image:url("../img/icons/credit-cards.png");background-position:-54px 0;'></svg>
                                                                    <?php
                                                                    echo $type = 'Mastercard' . "••••";
                                                                    echo $card_num;
                                                                } else if ($payments->card_type == "Discover Card") {
                                                                    ?>

                                                                    <svg class="icon icon--cc icon--discover" style='width:27px;height:19px;display:inline-block;fill:currentColor;margin-right:8px;vertical-align:middle;-webkit-transform:translateY(-2px);transform:translateY(-2px);background-color:transparent;background-image:url("../img/icons/credit-cards.png");background-position:-54px 0;'></svg>
                                                                    <?php
                                                                    echo $type = 'Discover Card' . "••••";
                                                                    echo $card_num;
                                                                } else if ($payments->card_type == "American Express") {
                                                                    ?>

                                                                    <svg class="icon icon--cc icon--amex" style='width:27px;height:19px;display:inline-block;fill:currentColor;margin-right:8px;vertical-align:middle;-webkit-transform:translateY(-2px);transform:translateY(-2px);background-color:transparent;background-image:url("../img/icons/credit-cards.png");background-position:-54px 0;'></svg>
                                                                    <?php
                                                                    echo $type = 'Amex' . "••••";
                                                                    echo $card_num;
                                                                } else {
                                                                    ?>

                                                                    <svg class="icon icon--cc icon--undefined" style='width:27px;height:19px;display:inline-block;fill:currentColor;margin-right:8px;vertical-align:middle;-webkit-transform:translateY(-2px);transform:translateY(-2px);background-color:transparent;background-image:url("../img/icons/credit-cards.png");background-position:-54px 0;'></svg>
                                                                    <?php
                                                                    echo $type = 'undefined Card' . "••••";
                                                                    echo $card_num;
                                                                }
                                                            } elseif (($payments->payment_type) == 'bank') {
                                                                $account_number = substr($payments->ba_account_number, -4);
                                                                ?>
                                                                <svg class="icon icon--cc icon--bank" style='width:27px;height:19px;display:inline-block;fill:currentColor;margin-right:8px;vertical-align:middle;-webkit-transform:translateY(-2px);transform:translateY(-2px);background-color:transparent;background-image:url("../img/icons/credit-cards.png");background-position:-54px 0;'></svg>
                                                                <?php echo $payments->bank_name; ?>••••<?php
                                                                print $account_number;
                                                            }
                                                            ?>
                                                        </div>

                                                    </div>


                                                    <table class="table table--invoice" style="margin:0;padding:0;border: 0;vertical-align:baseline;border-spacing:0;width:100%;border-radius:4px;border-collapse:separate;">
                                                        <thead style="margin: 0;
                                                               padding: 0;
                                                               border: 0;
                                                               vertical-align: baseline;">
                                                            <tr style="margin: 0;padding: 0;border: 0;vertical-align: baseline;">
                                                                <th width="60%" style="margin:0;padding:8px 16px;border:0;vertical-align:baseline;text-align:left;background-color:#E7EAF1;border-top-left-radius: 4px;">
                                                                    Item
                                                                </th>
                                                                <th width="25%" style="margin:0;padding:8px 16px;border:0;vertical-align:baseline;text-align:left;background-color:#E7EAF1;">
                                                                    Unit Price
                                                                </th>
                                                                <th width="15%" style="margin:0;padding:8px 16px;border:0;vertical-align:baseline;text-align:left;background-color:#E7EAF1;border-top-right-radius: 4px;">
                                                                    Qty
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="3" style="border-bottom-left-radius: 4px;border-bottom-right-radius: 4px;background: #fff;border-right: 1px solid #E7EAF1;border-left: 1px solid #E7EAF1;border-top: 0;border-bottom: 1px solid #E7EAF1;">
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                        <tbody style="margin: 0;
                                                               padding: 0;
                                                               border: 0;
                                                               vertical-align: baseline;">
                                                            <!-- Line Item -->
                                                            <?php
                                                            $subtotal = 0;
                                                            $tax = $orders->tax;
                                                            $product_new_price = 0;
                                                            $promo_discount = 0;
                                                            for ($i = 0; $i < count($order_details); $i++) {
                                                                $user_name = $_SESSION['user_name'];

                                                                $s_total = $order_details[$i]->total;
                                                                $pro_price = $order_details[$i]->price;
                                                                $qty = $order_details[$i]->quantity;
                                                                $p_price = $qty * $pro_price;
                                                                $subtotal = $subtotal + $s_total;

                                                                $shipping_price = $order_details[$i]->shipping_price;
                                                                $product_new_price = $product_new_price + $p_price;
                                                                ?>
                                                                <tr style="margin: 0;
                                                                    padding: 0;
                                                                    border: 0;
                                                                    vertical-align: baseline;">
                                                                    <td style="margin:0;padding:8px 16px;border:0;vertical-align:middle;border-collapse:collapse;border-left:1px solid #E7EAF1;font-size:14px;">
                                                                        <!-- Product -->
                                                                        <div class="product product--s row multi--vendor req--license padding--xxs" style="margin:0;padding:4px;border:0;vertical-align:baseline;list-style:none;margin-left:-16px;margin-top:24px;border-radius:4px;-webkit-transition:all 0.1s ease-in-out;transition:all 0.1s ease-in-out;padding-top:8px;padding-bottom:8px;">
                                                                            <div class="product__image col col--2-of-8 col--am" style="margin:0;padding:0;border:0;vertical-align:middle;box-sizing:border-box;display:inline-block;margin-right:0;min-height:1px;padding-left:16px;margin-left:0;width:25%;height:100%;">
                                                                                <?php
                                                                                if ($order_details[$i]->product_image != null) {
                                                                                    ?>
                                                                                    <div class="product__thumb" style="background-image:url('<?php echo image_url(); ?>uploads/products/images/<?php echo $order_details[$i]->product_image->photo; ?>');margin:0;padding:0;border:1px solid #E7EAF1;vertical-align:baseline;width:100%;height:0;display:block;padding-top:100%;position:relative;border-radius:4px;background-color:#FFFFFF;background-size:contain;background-position:center;background-repeat:no-repeat;">
                                                                                    </div>
                                                                                <?php } else { ?>
                                                                                    <div class="product__thumb" style="background-image:url('<?php echo image_url(); ?>assets/img/product-image.png');margin:0;padding:0;border:1px solid #E7EAF1;vertical-align:baseline;width:100%;height:0;display:block;padding-top:100%;position:relative;border-radius:4px;background-color:#FFFFFF;background-size:contain;background-position:center;background-repeat:no-repeat;">
                                                                                    </div>
                                                                                <?php } ?>
                                                                            </div>
                                                                            <div class="product__data col col--6-of-8 col--am" style="margin:0;padding:0;border:0;vertical-align:middle;box-sizing:border-box;display:inline-block;margin-right:0;min-height:1px;padding-left:16px;margin-left:0;width:70%;height:100%;">
                                                                                <span class="product__name" style="margin:0;padding:0;border:0;vertical-align:baseline;font-size:16px;line-height:24px;color:#495165;"><?php echo $order_details[$i]->product->name; ?></span>
                                                                                <span class="product__mfr" style="margin:0;padding:0;border:0;vertical-align:baseline;display:block;font-size:12px;">
                                                                                    by <a class="link fontWeight--2" href="#" style="margin:0;padding:0;border:0;vertical-align:baseline;text-decoration:none;display:inline-block;position:relative;color:#2893FF;font-weight:bold !important;"><?php echo $order_details[$i]->product->manufacturer; ?></a>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                        <!-- /Product -->
                                                                    </td>
                                                                    <td style="margin:0;padding:8px 16px;border:0;vertical-align:middle;border-collapse:collapse;border: 0;font-size:14px;">
                                                                        <?php if ($order_details[$i]->price > 0) {
                                                                            ?>
                                                                            $ <?php
                                                                            echo $order_details[$i]->price;
                                                                        } else {
                                                                            ?>

                                                                            <strike>$<?php echo $order_details[$i]->Product_details->price; ?> </strike> <?php echo " Free"; ?>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td style="margin:0;padding:8px 16px;border:0;vertical-align:middle;border-collapse:collapse;border-right:1px solid #E7EAF1;font-size:14px;">
                                                                        <?php echo $order_details[$i]->quantity; ?>
                                                                    </td>
                                                                </tr>

                                                            <?php } ?>
                                                            <?php $total = $subtotal + $tax + $shipping_price; ?>
                                                            <!-- /Line Item -->
                                                        </tbody>
                                                    </table>
                                                    <div class="inv__totals" style="margin:0;padding:16px;border:0;vertical-align:baseline;">
                                                        <div style="margin:0;padding:0;border:0;vertical-align:baseline;width:100%;display:table;">
                                                            <div class="wrapper__inner" style="margin:0;padding:0;border:0;vertical-align:middle;display:table-cell;">
                                                                <h5 class="textColor--darkest-gray" style="margin:0;padding:0;border:0;vertical-align:baseline;font-size:12px;">Shipping to:</h5>
                                                                <br>
                                                                <div style="margin:0;padding:0;border:0;vertical-align:baseline;font-size: 12px;line-height: 16px !important;">
                                                                <span class="fontWeight--2"><?php echo ($user_organization != null) ? $user_organization->organization_name : ""; ?></span><br>
                                                                <span class="fontWeight--2"><?php echo $orderUser->first_name . ' ' . $orderUser->last_name; ?></span> <br/>
                                                                  <?php echo $shipping_address->nickname; ?>
                                                                <br>
                                                                <?php echo $shipping_address->address1; ?>,
                                                                <?php echo $shipping_address->city; ?>
                                                                <br>
                                                                <?php echo $shipping_address->state; ?>,<?php echo $shipping_address->zip; ?>
                                                                </div>
                                                            </div>
                                                            <div class="wrapper__inner align--right" style="margin:0;padding:0;border:0;vertical-align:middle;text-align:right !important;display:table-cell;">
                                                                <span class="textColor--darkest-gray fontSize--s disp--block margin--xs no--margin-lr no--margin-t" style="margin:8px;padding:0;border:0;vertical-align:baseline;display:block !important;font-size:12px !important;line-height:16px !important;font-weight: normal !important; color: #495165 !important;margin-top:0 !important;margin-left:0 !important;margin-right:0 !important;">Subtotal: $<?php echo number_format($subtotal, 2, '.', ','); ?><br>Tax: $<?php echo number_format($tax, 2, '.', ','); ?><br>Shipping: $<?php echo number_format($shipping_price, 2, '.', ','); ?>
                                                                </span>
                                                                <?php if ($promos != null) { ?>
                                                                    <span style="color: #13C4A3 !important;">
                                                                        Promo –  <?php
                                                                        for ($k = 0; $k < count($promos); $k++) {
                                                                            if ($promos[$k]->promocode != null) {
                                                                                echo $promos[$k]->promocode->code . ": ( -$";
                                                                                echo
                                                                                number_format(floatval($promos[$k]->discount_value), 2, ".", "") . ") <br>";
                                                                                $promo_discount += $promos[$k]->discount_value;
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </span>

                                                                <?php } ?>

                                                                <?php $total = $subtotal + $tax + $shipping_price - ($promo_discount); ?>
                                                                <span class="fontWeight--2 textColor--darkest-gray" style="font-size: 12px;margin:0;padding:0;border:0;vertical-align:baseline;font-weight:bold !important;">Total: $<?php echo number_format($total, 2, '.', ','); ?></span>
                                                            </div>
                                                        </div>
                                                        <?php if ($promos != null) { ?>
                                                            <?php for ($k = 0; $k < count($promos); $k++) { ?>
                                                                <?php if ($promos[$k]->promocode != null) { ?>
                                                                    <?php if ($promos[$k]->promocode->manufacturer_coupon == 1) { ?>
                                                                        <div class="wrapper" style="margin:0;padding:0;border:0;vertical-align:baseline;width:100%;height:100%;display:table;">
                                                                            <b>Redemption instructions for promo code: <span style="color: #13C4A3 !important;"><?php echo $promos[$k]->promocode->code; ?></span></b>
                                                                            <p><?php echo $promos[$k]->promocode->conditions; ?></p>
                                                                        </div>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        <?php } ?>

                                                    </div>

                                                    <br/>
                                                    <br/>
                                                    <?php $view_order_link = base_url() . "view-orders?id=" . $orders->id; ?>
                                                    <a href="<?php echo $view_order_link; ?>" style='padding: 20px 40px 20px 40px;width:auto;display: block;text-decoration: none;border:0;text-align: center;font-weight: bold;font-size: 16px;font-family: Arial, sans-serif;color: #ffffff;background: #2893FF;border: 1px solid #317ED0;-moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius: 4px;line-height:normal;' class='button_link'>View Order Details</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section><!-- /Main Content -->
                            </td>
                        </tr>
                    </table>
                    <!--[if mso]></td></tr></table><![endif]-->
                    <!--[if (IE)]></td></tr></table><![endif]-->
                </td>
            </tr>
        </table>


        <table cellpadding="0" cellspacing="0" border="0" width="100%" style="width: 100%; border: 0 !important;" class="100p">
            <tr>
                <td align="center" valign="top" style="padding: 40px 20px 40px 20px;" class="container-mobile">
                    <!--[if gte mso 9]><table id="outlookholder" border="0" cellspacing="0" cellpadding="0" align="center"><tr><td><![endif]-->
                    <!--[if (IE)]><table border="0" cellspacing="0" cellpadding="0" width="500" align="center"><tr><td><![endif]-->
                    <table cellpadding="0" cellspacing="0" border="0" width="600" style="width: 600px;" class="100p">
                        <tr>
                            <td align="center" valign="top" style="font-size: 12px; line-height: 22px; font-family: Helvetica, Arial, sans-serif; color: #888888; font-weight: 400;">
                                700 S. Flower Street, Suite 2990 Los Angeles, CA 90017
                                <br/>
                                <a style="padding-right: 10px; color: #2893FF; text-decoration: underline; font-size:14px;" href="<?php echo base_url(); ?>dashboard">My Account</a>
                                <a style="padding-right: 10px; color: #2893FF; text-decoration: underline; font-size:14px;" href="<?php echo base_url(); ?>email-settings">Email Preferences</a>
                                <a style="color: #2893FF; text-decoration: underline; font-size:14px;" href="<?php echo base_url(); ?>privacy-policy">Privacy Policy</a>
                                <div>© 2017 Dentomatix, LLC. All Rights Reserved.</div>
                            </td>
                        </tr>
                    </table>
                    <!--[if mso]></td></tr></table><![endif]-->
                    <!--[if (IE)]></td></tr></table><![endif]-->
                </td>
            </tr>
        </table>
    </body>
</html>

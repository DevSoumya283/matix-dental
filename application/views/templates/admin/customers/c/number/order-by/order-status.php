<div class="order_status">
    <!-- Single Order -->
    <?php
    if(!empty($latest_reports)) {
        foreach ($latest_reports as $orders) { ?>
        <div class="order well card">
            <div class="heading__group wrapper border--dashed padding--s no--pad-lr no--pad-t">
                <div class="wrapper__inner">
                    <h4 class="textColor--darkest-gray">Order <?php echo $orders->id; ?></h4>
                </div>
                <div class="wrapper__inner align--right">
                    <ul class="list list--inline list--divided fontWeight--2 fontSize--s">
                        <!--                        <li class="item">
                                                    <a class="link is--neg modal--toggle order_cancel" data-order_id="<?php echo $orders->id; ?>" data-target="#cancelOrderAdminModal">Cancel</a>
                                                </li>-->
                        <li class="item">
                            <button class="btn btn--s btn--tertiary is--link" onclick="location.href = '<?php echo base_url(); ?>superAdmin-order-details?order_id=<?php echo $orders->id; ?>'">View Order</button>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="order__info col col--10-of-12 col--am">
                    <ul class="list list--inline list--stats list--divided">
                        <?php if ($orders->image_name != null) { ?>
                            <li class="item" style="width:88px;">
                                <div class="order__logo" style="background-image:url('<?php echo image_url(); ?>uploads/products/images/<?php echo $orders->image_name->photo; ?>');"></div>
                            </li>
                        <?php } else { ?>
                            <li class="item" style="width:88px;">
                                <div class="order__logo" style="background-image:url('<?php echo image_url(); ?>assets/img/product-image.png');"></div>
                            </li>
                        <?php } ?>
                        <li class="item item--stat stat-s">
                            <div class="text__group">
                                <span class="line--main"><?php echo $orders->vendor_name; ?></span>
                                <span class="line--sub">Purchased From</span>
                            </div>
                        </li>
                        <li class="item item--stat stat-s">
                            <div class="text__group">
                                <span class="line--main"><?php echo date('M d, Y', strtotime($orders->created_at)); ?></span>
                                <span class="line--sub">Order Date</span>
                            </div>
                        </li>
                        <li class="item item--stat stat-s">
                            <div class="text__group">
                                <span class="line--main"><?php echo $orders->order_status; ?></span>
                                <span class="line--sub">Status</span>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="order__btn col col--2-of-12 col--am align--right">
                    <ul class="list list--inline list--stats">
                        <li class="item item--stat">
                            <div class="text__group">
                                <span class="line--main font"><?php echo "$" . number_format(floatval($orders->total), 2); ?></span>
                                <span class="line--sub">Order Total</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    <?php }
        } else { ?>
        <div class=" align--center">
            This customer not purchased anything yet.
        </div>
        <?php } ?>
    <!-- /Single Order -->
</div>
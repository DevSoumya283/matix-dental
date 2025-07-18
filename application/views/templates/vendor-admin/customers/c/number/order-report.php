<div class="order_status">
    <?php foreach ($orderList as $list) { ?>
        <div class="order well card">
            <div class="heading__group wrapper border--dashed padding--s no--pad-lr no--pad-t">
                <div class="wrapper__inner">
                    <h4 class="textColor--darkest-gray">Order <?php echo $list->id; ?></h4>
                </div>
                <div class="wrapper__inner align--right">
                    <button class="btn btn--s btn--tertiary is--link" onclick="location.href = '<?php echo base_url(); ?>vendor-order-details?order_id=<?php echo $list->id; ?>'">View Order</button>
                </div>
            </div>
            <div class="row">
                <div class="order__info col col--10-of-12 col--am">
                    <ul class="list list--inline list--stats list--divided">
                        <li class="item item--stat stat-s">
                            <div class="text__group">
                                <span class="line--main"><?php echo $list->location; ?></span>
                                <span class="line--sub">Location</span>
                            </div>
                        </li>
                        <li class="item item--stat stat-s">
                            <div class="text__group">
                                <span class="line--main"><?php echo date('M d, Y', strtotime($list->created_at)); ?></span>
                                <span class="line--sub">Order Date</span>
                            </div>
                        </li>
                        <li class="item item--stat stat-s">
                            <div class="text__group">
                                <span class="line--main"><?php echo $list->order_status; ?></span>
                                <span class="line--sub">Status</span>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="order__btn col col--2-of-12 col--am align--right">
                    <ul class="list list--inline list--stats">
                        <li class="item item--stat">
                            <div class="text__group">
                                <span class="line--main font"><?php echo "$" . $list->total; ?></span>
                                <span class="line--sub">Order Total</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
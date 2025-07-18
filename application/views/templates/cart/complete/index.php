<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <!-- Main Content -->
    <section class="content__wrapper bg--lightest-gray">
        <div class="content__main padding--xl no--pad--lr">

            <div class="row">
                <div class="col col--9-of-12 col--centered">
                    <div class="align--center">
                        <h2>Orders Submitted</h2>
                        <p class="textColor--dark-gray">

                            If you have any questions about your order(s), please contact each vendor directly.
                        </p>
                    </div>

                    <!-- Success Order -->
                    <?php
                    if ($success_order != null) {
                        for ($i = 0; $i < count($success_order); $i++) {
                            ?>
                            <div class="order well card is--pos">
                                <div class="heading__group wrapper border--dashed padding--s no--pad-lr no--pad-t">
                                    <div class="wrapper__inner">
                                        <h4 class="textColor--darkest-gray">Order <?php echo $success_order[$i]->id; ?></h4>
                                    </div>
                                    <div class="wrapper__inner align--right">
                                        <ul class="list list--inline list--divided fontWeight--2 fontSize--s">
                                            <!--  <li class="item">
                                                 <a class="link modal--toggle" data-target="#orderCancellationModal">Cancel</a>
                                             </li>
                                             <li class="item">
                                                 <a class="link modal--toggle" data-target="#reorderModal">Order It Again</a>
                                             </li>
                                             <li class="item">
                                                 <a class="link modal--toggle" data-target="#makeRecurringModal">Make Recurring</a>
                                             </li> -->
                                            <li class="item">
                                                <button class="btn btn--s btn--primary is--link" data-target="<?php echo base_url(); ?>view-orders?id=<?php echo $success_order[$i]->id; ?>">View Order</button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="order__info col col--10-of-12 col--am">
                                        <ul class="list list--inline list--stats list--divided">
                                            <li class="item" style="width:88px;">
                                                <?php if ($success_order[$i]->vendor_image != null) { ?>
                                                    <div class="order__logo" style="background-image:url('<?php echo image_url(); ?>uploads/vendor/logo/<?php echo $success_order[$i]->vendor_image->photo; ?>'); background-repeat: no-repeat;"></div>
                                                <?php } else { ?>
                                                    <div class="order__logo" style="background-image:url('http://placehold.it/192x192');"></div>
                                                <?php } ?>
                                            </li>
                                            <li class="item item--stat stat-s">
                                                <div class="text__group">
                                                    <span class="line--main"><?php echo $success_order[$i]->vendors->name; ?></span>
                                                    <span class="line--sub">Vendor</span>
                                                </div>
                                            </li>
                                            <li class="item item--stat stat-s">
                                                <div class="text__group">
                                                    <span class="line--main"><?php echo $user_address->nickname; ?></span>
                                                    <span class="line--sub">Location</span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="order__btn col col--2-of-12 col--am align--right">
                                        <ul class="list list--inline list--stats">
                                            <li class="item item--stat">
                                                <div class="text__group">
                                                    <span class="line--main font">$<?php echo number_format($success_order[$i]->total,2, '.', ','); ?></span>
                                                    <span class="line--sub">Order Total</span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div> <br>
                            <?php
                        }
                    }
                    ?>
                    <!-- /Success Order -->
                    <!-- Restricted Order -->
                    <?php
                    if ($restricted_orders != null) {
                        for ($i = 0; $i < count($restricted_orders); $i++) {
                            ?>
                            <div class="order well card">
                                <div class="heading__group wrapper border--dashed padding--s no--pad-lr no--pad-t">
                                    <div class="wrapper__inner">
                                        <h4 class="textColor--darkest-gray">Order <?php echo $restricted_orders[$i]->id; ?>(Pending Approval)</h4>
                                    </div>
                                    <div class="wrapper__inner align--right">
                                        <ul class="list list--inline list--divided fontWeight--2 fontSize--s">
                                            <!--  <li class="item">
                                                 <a class="link modal--toggle" data-target="#orderCancellationModal">Cancel</a>
                                             </li>
                                             <li class="item">
                                                 <a class="link modal--toggle" data-target="#reorderModal">Order It Again</a>
                                             </li>
                                             <li class="item">
                                                 <a class="link modal--toggle" data-target="#makeRecurringModal">Make Recurring</a>
                                             </li> -->
                                            <li class="item">
                                                <button class="btn btn--s btn--primary is--link" data-target="<?php echo base_url(); ?>pending?id=<?php echo $restricted_orders[$i]->id; ?>">View Order</button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="order__info col col--10-of-12 col--am">
                                        <ul class="list list--inline list--stats list--divided">
                                            <li class="item" style="width:88px;">
                                                <?php if ($restricted_orders[$i]->vendor_image != null) { ?>
                                                    <div class="order__logo" style="background-image:url('<?php echo image_url(); ?>uploads/vendor/logo/<?php echo $restricted_orders[$i]->vendor_image->photo; ?>'); background-repeat: no-repeat;"></div>
                                                <?php } else { ?>
                                                    <div class="order__logo" style="background-image:url('http://placehold.it/192x192');"></div>
                                                <?php } ?>
                                            </li>
                                            <li class="item item--stat stat-s">
                                                <div class="text__group">
                                                    <span class="line--main"><?php echo $restricted_orders[$i]->vendors->name; ?></span>
                                                    <span class="line--sub">Vendor</span>
                                                </div>
                                            </li>
                                            <li class="item item--stat stat-s">
                                                <div class="text__group">
                                                    <span class="line--main"><?php echo $user_address->nickname; ?></span>
                                                    <span class="line--sub">Location</span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="order__btn col col--2-of-12 col--am align--right">
                                        <ul class="list list--inline list--stats">
                                            <li class="item item--stat">
                                                <div class="text__group">
                                                    <span class="line--main font">$<?php echo number_format($restricted_orders[$i]->total,2, '.', ','); ?></span>
                                                    <span class="line--sub">Order Total</span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div> <br>
                            <?php
                        }
                    }
                    ?>
                    <!-- /Restricted Order -->
                </div>
            </div>
            <?php
            if ($user_locations != null) {
                ?>
                <div class="row">
                    <div class="col col--9-of-12 col--centered">
                        <hr>
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <ul class="list list--inline list--divided">
                                    <li class="item">
                                        <?php if (isset($item_count) && ($item_count) > 0) { ?>
                                            <button class="btn btn--tertiary btn--m btn--icon btn--circle has--badge is--link" data-badge="<?php echo $item_count; ?>" data-target="<?php echo base_url(); ?>cart?id=<?php echo $checkout_location_id; ?>"><!-- templates/cart -->
                                                <svg class="icon icon--cart-s"><use xlink:href="#icon-cart-s"></use></svg></button>
                                            <?php
                                        } else {
                                            ?>
                                            <button class="btn btn--tertiary btn--m btn--icon btn--circle has--badge is--link" data-badge="0" data-target="#"><!-- templates/cart -->
                                                <svg class="icon icon--cart-s"><use xlink:href="#icon-cart-s"></use></svg></button>

                                        <?php } ?>
                                    </li>
                                    <li class="item">
                                        <div class="text__group">
                                            <span class="disp--block">Checkout for Another Location:</span>
                                            <div class="select select--text select--l">
                                                <select class="no--pad-l switch-view">
                                                    <?php for ($i = 0; $i < count($user_locations); $i++) { ?>
                                                        <option <?php
                                                        if ($checkout_location_id == $user_locations[$i]->id) {
                                                            echo "selected";
                                                        }
                                                        ?> value="<?php echo $user_locations[$i]->id; ?>" ><?php echo $user_locations[$i]->nickname; ?></option>
                                                        <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="wrapper__inner align--right">
                                <a class="btn btn--l btn--tertiary" href="<?php echo base_url('dashboard'); ?>">Return to Your Account</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }
            ?>
    </section>
    <!-- /Main Content -->
</div>
<!-- /Content Section -->

<!-- Modals -->
<?php include(INCLUDE_PATH . '/_inc/shared/modals/order-cancellation.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/reorder.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/make-recurring.php'); ?>

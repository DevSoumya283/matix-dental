<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>
    <!-- Breadcrumbs Bar -->
    <div class="bar padding--xs bg--lightest-gray">
        <div class="wrapper wrapper--fixed">
            <ul class="list list--inline list--breadcrumbs">
                <li class="item">
                    <a class="link" href="<?php echo base_url('dashboard'); ?>">Account</a>
                </li>
                <li class="item is--active">
                    Search Orders
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breadcrumbs Bar -->
    <!-- Main Content -->
    <section class="content__wrapper wrapper--fixed has--sidebar-l sidebar--no-fill">
        <div class="content__main">
            <div class="row row--full-height">
                <!-- Content -->
                <div id="ordersContent" class="content col-md-9 content offset-md-2 col-xs-12">
                    <!-- Orders Tab -->
                    <?php
                    if ($orders != null) {
                        for ($i = 0; $i < count($orders); $i++) {
                            $order_date = $orders[$i]->created_at;
                            $ordered_date = date("d. M, Y", strtotime($order_date));
                            ?>
                            <!-- Single Order -->
                            <div class="order well card">
                                <div class="heading__group wrapper border--dashed padding--s no--pad-lr no--pad-t">
                                    <div class="wrapper__inner">    
                                        <h4 class="textColor--darkest-gray">Order <?php echo $orders[$i]->id; ?></h4>
                                    </div>
                                    <div class="wrapper__inner align--right">
                                        <ul class="list list--inline list--divided fontWeight--2 fontSize--s">
                                            <li class="item">
                                                <a class="link modal--toggle cancel-order" data-id="<?php echo $orders[$i]->id; ?>" data-target="#orderCancellationModal">Cancel</a>
                                            </li>
                                            <li class="item">
                                                <a class="link modal--toggle re-order" data-id="<?php echo $orders[$i]->id; ?>" data-target="#reorderModal">Order It Again</a>
                                            </li>
                                            <li class="item">
                                                <a class="link modal--toggle recurring-orders" data-id="<?php echo $orders[$i]->id; ?>" data-target="#makeRecurringModal">Make Recurring</a>
                                            </li> 
                                            <li class="item">
                                                <button class="btn btn--s btn--primary is--link" data-target="<?php echo base_url(); ?>view-orders?id=<?php echo $orders[$i]->id; ?>">View Order</button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="order__info col-md-9 col-xs-12">
                                        <ul class="list list--inline list--stats list--divided">
                                            <!--                                        <li class="item" style="width:88px;">
                                                                                        <div class="order__logo" style="background-image:url('http://placehold.it/192x192');"></div>
                                                                                    </li>-->
                                            <li class="item item--stat stat-s">
                                                <div class="text__group">
                                                    <span class="line--main"><?php echo $orders[$i]->vendors->name; ?></span>
                                                    <span class="line--sub">Purchased From</span>
                                                </div>
                                            </li>
                                            <li class="item item--stat stat-s">
                                                <div class="text__group">
                                                    <span class="line--main"<?php echo $orders[$i]->location->nickname; ?></span>
                                                    <span class="line--sub">Shipped To</span>
                                                </div>
                                            </li>
                                            <li class="item item--stat stat-s">
                                                <div class="text__group">
                                                    <span class="line--main"><?php echo $ordered_date; ?></span>
                                                    <span class="line--sub">Order Date</span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="order__btn col col-md-3 col-xs-12 align--right">
                                        <ul class="list list--inline list--stats mobile-center">
                                            <li class="item item--stat">
                                                <div class="text__group">
                                                    <span class="line--main font">$<?php echo $orders[$i]->total; ?></span>
                                                    <span class="line--sub">Order Total</span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        //echo "No order(s) yet";
                        echo 'No orders matching your search criteria';
                    }
                    ?>
                    <!-- /Single Order -->
                    <!-- /Orders Tab -->
                </div>
                <!-- /Content -->
            </div>
        </div>
    </section>
    <!-- /Main Content -->
</div>
<!-- /Content Section -->
<!-- Modals -->
<?php $this->load->view('templates/_inc/shared/modals/orders-vendor-review.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/order-cancellation.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/reorder.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/make-recurring.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/cancel-recurring.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/pending-order-cencellation.php'); ?>


<!-- <?php include(INCLUDE_PATH . '/_inc/shared/modals/orders-vendor-review.php'); ?> 
<?php include(INCLUDE_PATH . '/_inc/shared/modals/order-cancellation.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/reorder.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/make-recurring.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/cancel-recurring.php'); ?> 
<?php include(INCLUDE_PATH . '/_inc/shared/modals/pending-order-cencellation.php'); ?> -->
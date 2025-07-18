<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>
    <section class="content__wrapper has--sidebar-l">
        <div class="content__main">
            <div class="row row--full-height">
                <!-- Sidebar -->
                <div class="sidebar col col--2-of-12" style="padding: 12px !important;">
                    <?php //include(INCLUDE_PATH . '/vendor-admin/_inc/nav.php'); ?>
                    <?php $this->load->view('templates/vendor-admin/_inc/nav.php'); ?>

                </div>
                <!-- /Sidebar -->
                <!-- Content Area -->
                <div class="content col col--9-of-12 col--push-1-of-12">

                    <!-- /New & In-Progress Orders -->
                    <div class="heading__group border--dashed">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <ul class="list list--inline list--divided">
                                    <li class="item">
                                        <h3>Orders</h3>
                                    </li>
                                    <li class="item">
                                        <form method="post" action="<?php echo base_url(); ?>view-orders-selection">
                                            <div class="select select--text">
                                                <label>Showing:</label>
                                                <select name="selection" onchange="this.form.submit()">
                                                    <option value="1"<?php echo ($selection == 1) ? "selected" : ""; ?>>All</option>
                                                    <option value="2"<?php echo ($selection == 2) ? "selected" : ""; ?>>New</option>
                                                    <option value="3"<?php echo ($selection == 3) ? "selected" : ""; ?>>In Progress</option>
                                                    <option value="4"<?php echo ($selection == 4) ? "selected" : ""; ?>>Shipped</option>
                                                </select>
                                            </div>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                            <div class="wrapper__inner">
                                <form method="post" action="<?php echo base_url(); ?>vendor-orders">
                                    <div class="input__group input__group--inline">
                                        <input id="site-search" class="input input__text" type="search" value="" placeholder="Search by number, date, customer, etc..." name="search" required>
                                        <label for="site-search" class="label">
                                            <svg class="icon icon--search textColor--gray"><use xlink:href="#icon-search"></use></svg>
                                        </label>
                                    </div>
                                </form>
                            </div>
                            <div id="controlsOrders" class="contextual__controls wrapper__inner align--right">
                                <a class="link fontWeight--2 fontSize--s modal--toggle is--contextual is--off" data-target="#cancelOrderModal">Cancel Selected</a>
                            </div>
                        </div>
                    </div>

                    <!-- New and In Progress -->
                    <div class="well no--pad" style="border:none; max-height:800px;">                       
                        <!-- Single Order -->
                        <?php if ($orders_received != null) { ?>
                            <?php foreach ($orders_received as $orders) { ?>                        
                                <div class="order well card <?php echo (date('Y-m-d', strtotime($orders->delivery_time)) <= date('Y-m-d')) ? "is--neg" : ""; ?>">
                                    <div class="heading__group wrapper border--dashed padding--s no--pad-lr no--pad-t">
                                        <div class="wrapper__inner">
                                            <h4 class="textColor--darkest-gray">Order <?php echo $orders->id; ?></h4>
                                        </div>
                                        <div class="wrapper__inner align--right">
                                            <ul class="list list--inline list--divided fontWeight--2 fontSize--s">
                                                <?php if ($orders->order_status == "New") { ?>
                                                    <li class="item">
                                                        <a class="link modal--toggle order_cancel is--neg" data-order_id="<?php echo $orders->id; ?>" data-target="#cancelOrderModal">Cancel</a>
                                                    </li>
                                                <?php } ?>
                                                <li class="item">
                                                    <button class="btn btn--s btn--primary is--link" data-target="<?php echo base_url(); ?>vendor-order-details?order_id=<?php echo $orders->id; ?>">View Order</button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="order__info col col--10-of-12 col--am">
                                            <ul class="list list--inline list--stats list--divided">
                                                <li class="item item--stat stat-s">
                                                    <div class="text__group">
                                                        <span class="line--main"><?php echo $orders->first_name; ?></span>
                                                        <span class="line--sub">Customer</span>
                                                    </div>
                                                </li>
                                                <li class="item item--stat stat-s">
                                                    <div class="text__group">
                                                        <span class="line--main"><?php echo $orders->created_at; ?></span>
                                                        <span class="line--sub">Order Date</span>
                                                    </div>
                                                </li>
                                                <li class="item item--stat stat-s">
                                                    <div class="text__group">
                                                        <span class="line--main"><?php echo $orders->order_status; ?></span>
                                                        <span class="line--sub">Status</span>
                                                    </div>
                                                </li>
                                                <li class="item item--stat stat-s">
                                                    <div class="text__group">
                                                        <span class="line--main <?php echo (date('Y-m-d', strtotime($orders->delivery_time)) == date('Y-m-d')) ? "textColor" : ""; ?> "><?php echo (date('Y-m-d', strtotime($orders->delivery_time)) == date('Y-m-d')) ? "Today" : $orders->delivery_time; ?></span>
                                                        <span class="line--sub">Ship By</span>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="order__btn col col--2-of-12 col--am align--right">
                                            <ul class="list list--inline list--stats">
                                                <li class="item item--stat">
                                                    <div class="text__group">
                                                        <span class="line--main font"><?php echo "$" . number_format(floatval($orders->total), 2); ?></span>
                                                        <span class="line--sub">Order Total</span>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } else { ?>
                            <div class="well align--center">
                                No order(s) yet
                            </div>
                        <?php } ?>
                        <!-- /Single Order -->
                    </div>
                    <!-- /New & In-Progress Orders -->
                </div>
                <!-- /Content Area -->
            </div>
        </div>
    </section>
</div>
<!-- /Content Section -->

<!-- Orders Tab -->
                        <div id="tabHistory" class="page__tab">
                            <?php //if ($orders != null) { ?>
                            <div class="heading__group wrapper border--dashed padding--s no--pad-lr no--pad-t">
                                <div class="wrapper__inner order-history">
                                    <h4>Order History</h4>
                                </div>
                                <div class="wrapper__inner align--right">
                                    <form name="manage_locations" method="post" action="<?php echo base_url(); ?>history" style="display:inline;">
                                        <ul class="list list--inline list--divided">
                                            <li class="item">
                                                <div class="select select--text">
                                                    <label class="label">Location</label>
                                                    <select name="sort_location_id" aria-label="Select a Sorting Option" onchange="document.manage_locations.submit();">
                                                        <option value="">Show All</option>
                                                        <?php
                                                        foreach ($user_locations as $key) {
                                                            foreach ($key as $row) {
                                                                ?>
                                                                <option <?php echo ($sort_location_id == $row->id) ? "selected" : "" ?> value="<?php echo $row->id; ?>"><?php echo $row->nickname; ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </li>
                                            <li class="item">
                                                <div class="select select--text">
                                                    <label class="label">Placed in the</label>
                                                    <select name="sortBy_date" aria-label="Select a Sorting Option" onchange="document.manage_locations.submit();">
                                                        <option value="30" <?php echo ($sortBy_date == "30") ? "selected" : ""; ?>>Last 30 Days</option>
                                                        <option value="90" <?php echo ($sortBy_date == "90") ? "selected" : ""; ?>>Last 3 Months</option>
                                                        <option value="180" <?php echo ($sortBy_date == "180") ? "selected" : ""; ?>>Last 6 Months</option>
                                                        <option value="365" <?php echo ($sortBy_date == "365") ? "selected" : ""; ?>>Last Year</option>
                                                    </select>
                                                </div>
                                            </li>
                                        </ul>
                                    </form>
                                </div>
                            </div>
                            <?php //} ?>
                            <div class="wrapper__inner">
                                <div class="row">
                                       <div class="col-md-4 col-xs-12">
                                    <h3 class="textColor--dark-gray">Find an Order</h3>
                                     </div>
                                <div class="col-md-8 col-xs-12">
                                <form method="post" action="<?php echo base_url(); ?>search-orders">
                                    <div class="input__group input__group--inline">
                                        <input id="site-search" class="input input__text" type="search" value="" placeholder="Search by order number, date, product, etc…" name="search" required>
                                        <label for="site-search" class="label">
                                            <svg class="icon icon--search textColor--gray"><use xlink:href="#icon-search"></use></svg>
                                        </label>
                                    </div>
                                </form>
                            </div>
                                </div>



                            <!-- Single Order -->
                            <?php
                            if ($orders != null) {
                                for ($i = 0; $i < count($orders); $i++) {
                                    $order_date = $orders[$i]->created_at;
                                    $ordered_date = date("M. d, Y", strtotime($order_date));
                                    if ($orders[$i]->order_status == 'Shipped' || $orders[$i]->order_status == 'Delivered') {
                                        ?>
                                        <div class="order well card is--pos">
                                            <?php } else { ?>
                                            <div class="order well card ">
            <?php } ?>
                                            <div class="heading__group wrapper border--dashed padding--s no--pad-lr no--pad-t">
                                                <div class="wrapper__inner is--pos">
                                                    <h4 class="textColor--darkest-gray">Order <?php echo $orders[$i]->id; ?>
                                                        <?php
                                                        if ($orders[$i]->restricted_order == '0') {
                                                            if ($orders[$i]->order_status != 'Delivered') {
                                                                if ($orders[$i]->order_status == 'New') {
                                                                    echo "(Pending)";
                                                                } else {
                                                                    echo "(" . $orders[$i]->order_status . ")";
                                                                }
                                                            }
                                                        } else if ($orders[$i]->restricted_order == '1' && $orders[$i]->order_status == 'Cancelled') {
                                                            echo "(" . $orders[$i]->order_status . ")";
                                                        } else if ($orders[$i]->restricted_order == '-1') {
                                                            echo "(Rejected)";
                                                        } else if ($orders[$i]->restricted_order == '1') {
                                                            echo "(Pending)";
                                                        }
                                                        ?>
                                                    </h4>
                                                    <h5>Ordered By: <?php echo $order[$i]->user_info->first_name." ".$order[$i]->user_info->last_name; ?></h5>
                                                </div>
                                                <div class="wrapper__inner align--right">
                                                    <ul class="list list--inline list--divided fontWeight--2 fontSize--s">
                                                        <?php if ($orders[$i]->restricted_order != '-1' && $orders[$i]->restricted_order != '1') { ?>
                <?php if ($orders[$i]->order_status != 'Cancelled' && $orders[$i]->order_status != 'Shipped') { ?>
                                                                <li class="item">
                                                                    <a class="link modal--toggle cancel-order" data-id="<?php echo $orders[$i]->id; ?>" data-target="#orderCancellationModal">Cancel</a>
                                                                </li>
                <?php } ?>
                                                            <li class="item">
                                                                <a class="link modal--toggle re-order" data-id="<?php echo $orders[$i]->id; ?>" data-target="#reorderModal">Order It Again</a>
                                                            </li>
                                                            <li class="item">
                                                                <a class="link modal--toggle recurring-orders" data-id="<?php echo $orders[$i]->id; ?>" data-target="#makeRecurringModal">Make Recurring</a>
                                                            </li>
                                                            <li class="item">
                                                                <button class="btn btn--s btn--primary is--link view-orders" data-target="<?php echo base_url(); ?>view-orders?id=<?php echo $orders[$i]->id; ?>">View Order</button>
                                                            </li>
                                                        <?php } else { ?>
                <?php if ($orders[$i]->order_status != 'Cancelled') { ?>
                                                                <li class="item">
                                                                    <a class="link modal--toggle cancel_pending" data-id="<?php echo $orders[$i]->id; ?>" data-target="#pendingCancellationModal">Cancel</a>
                                                                </li>
                <?php } ?>
                                                            <li class="item">
                                                                <button class="btn btn--s btn--primary is--link" data-target="<?php echo base_url(); ?>pending?id=<?php echo $orders[$i]->id; ?>">View Order</button>
                                                            </li>
            <?php } ?>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="order__info col-md-10 col-xs-12">
                                                    <ul class="list list--inline list--stats list--divided">
                                                        <!--  <li class="item" style="width:88px;">
                                                             <div class="order__logo" style="background-image:url('http://placehold.it/192x192');"></div>
                                                         </li> -->
                                                        <li class="item item--stat stat-s">
                                                            <div class="text__group">
                                                                <span class="line--main"><?php echo $orders[$i]->vendor->name; ?></span>
                                                                <span class="line--sub">Purchased From</span>
                                                            </div>
                                                        </li>
                                                        <li class="item item--stat stat-s">
                                                            <div class="text__group">
                                                                <span class="line--main"><?php echo $orders[$i]->nickname; ?></span>
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
                                                <div class="order__btn col-md-2 col-xs-12 mobile-center align--right">
                                                    <ul class="list list--inline list--stats">
                                                        <li class="item item--stat">
                                                            <div class="text__group">
                                                                <span class="line--main font">$<?php echo number_format($orders[$i]->total, 2, '.', ','); ?></span>
                                                                <span class="line--sub">Order Total</span>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div> <br>
                                        <!-- /Single Order -->
                                        <?php
                                    }
                                } else {
                                    echo "It's a bit empty here. Get started by browsing or searching for products above";
                                }
                                ?>
                                <!--student Orders-->
                                <!--student Orders-->
                            </div>
                            <!-- /Orders Tab -->
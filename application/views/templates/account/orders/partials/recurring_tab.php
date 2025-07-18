                        <!-- Recurring Orders Tab -->
                            <div id="tabRecurring" class="page__tab">
                                <div class="heading__group wrapper border--dashed padding--s no--pad-lr no--pad-t">
                                    <div class="wrapper__inner">
                                        <h4>Upcoming Recurring Orders</h4>
                                    </div>
                                    <div class="wrapper__inner align--right">
                                        <ul class="list list--inline list--divided">
                                            <li class="item">
                                                <div class="select select--text">
                                                    <label class="label">Location</label>
                                                    <form name="manage_locations_recurring" method="post" action="<?php echo base_url(); ?>recurring" style="display:inline;">
                                                        <select name="sort_location_id" aria-label="Select a Sorting Option" onchange="document.manage_locations_recurring.submit();">
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
                                                    </form>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- Single Recurring Order -->
                                <?php
                                if ($recurrings != null) {
                                    foreach ($recurrings as $key) {
                                        foreach ($key as $row) {
                                            $date = $row->start_date;
                                            $open_date = date("M. d ,Y", strtotime($date));
                                            ?>
                                            <div class="order well card">
                                                <div class="heading__group wrapper border--dashed padding--s no--pad-lr no--pad-t">
                                                    <div class="wrapper__inner">
                                                        <h4 class="textColor--darkest-gray disp--ib">Recurring <?php echo $row->id ?></h4>
                                                        (<?php echo $row->frequency ?>)
                                                    </div>
                                                    <div class="wrapper__inner align--right">
                <!--                                                <button class="btn btn--s btn--primary is--link" data-target="<?php echo base_url(); ?>view-recurring-orders?id=<?php echo $row->id; ?>">View/Edit</button> templates/account/orders/r/number
                                                        <button class="btn btn--s btn--secondary btn--s btn--icon modal--toggle delete_recurring" data-id="<?php echo $row->id ?>" data-target="#cancelRecurringModal"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></button>-->
                                                        <ul class="list list--inline list--divided fontWeight--2 fontSize--s">
                                                            <li class="item">
                                                                <a class="link modal--toggle delete_recurring" data-id="<?php echo $row->id ?>" data-target="#cancelRecurringModal" href="javascript:;">Cancel</a>
                                                            </li>
                                                            <li class="item">
                                                                <button class="btn btn--s btn--primary is--link" data-target="<?php echo base_url(); ?>view-recurring-orders?id=<?php echo $row->id; ?>">View/Edit</button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="order__info col col--10-of-12 col--am">
                                                        <ul class="list list--inline list--stats list--divided">
                                                            <!--                                                    <li class="item" style="width:88px;">
                                                                                                                       <div class="order__logo" style="background-image:url('http://placehold.it/192x192');"></div>
                                                                                                                </li>-->
                                                            <li class="item item--stat stat-s">
                                                                <div class="text__group">
                                                                    <span class="line--main"><?php echo $row->name ?></span>
                                                                    <span class="line--sub">Vendor</span>
                                                                </div>
                                                            </li>
                                                            <li class="item item--stat stat-s">
                                                                <div class="text__group">
                                                                    <span class="line--main"><?php echo $row->nickname ?></span>
                                                                    <span class="line--sub">Location</span>
                                                                </div>
                                                            </li>
                                                            <li class="item item--stat stat-s">
                                                                <div class="text__group">
                                                                    <span class="line--main"><?php echo $open_date ?></span>
                                                                    <span class="line--sub">Next Order</span>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="order__btn col col--2-of-12 col--am align--right">
                                                        <ul class="list list--inline list--stats">
                                                            <li class="item item--stat">
                                                                <div class="text__group">
                                                                    <span class="line--main font">$<?php echo number_format($row->total, 2, '.', ','); ?></span>
                                                                    <span class="line--sub">Order Total</span>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }
                                } else {
                                    echo "Create recurring orders from Order History";
                                }
                                ?>
                                <!-- /Single Recurring Order -->
                            </div>
                        <!-- /Recurring Orders Tab -->
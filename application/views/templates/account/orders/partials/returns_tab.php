 <!-- Returns Tab -->
                            <div id="tabReturns" class="page__tab">
                                <?php if ($returns != null) {
                                    ?>
                                    <div class="heading__group wrapper border--dashed padding--s no--pad-lr no--pad-t">
                                        <div class="wrapper__inner">
                                            <h4>Returns</h4>
                                        </div>
                                        <div class="wrapper__inner align--right">
                                            <ul class="list list--inline list--divided">
                                                <li class="item">
                                                    <div class="select select--text">
                                                        <label class="label">Location</label>
                                                        <select aria-label="Select a Sorting Option">
                                                            <option selected>Show All</option>
                                                            <?php
                                                            foreach ($user_locations as $key) {
                                                                foreach ($key as $row) {
                                                                    ?>
                                                                    <option value="<?php echo $row->id; ?>"><?php echo $row->nickname; ?></option>

                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="select select--text">
                                                        <label class="label">Status</label>
                                                        <select aria-label="Select a Sorting Option">
                                                            <option selected>Show All</option>
                                                            <option value="1">Pending</option>
                                                            <option value="2">Closed</option>
                                                        </select>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="select select--text">
                                                        <label class="label">Opened in the</label>
                                                        <select aria-label="Select a Sorting Option">
                                                            <option selected>Last 30 Days</option>
                                                            <option value="1">Last 3 Months</option>
                                                            <option value="2">Last 6 Months</option>
                                                            <option value="3">Last Year</option>
                                                        </select>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <!-- Single Return -->
                                    <?php
                                    foreach ($returns as $key) {
                                        foreach ($key as $row) {
                                            $date = $row->updated_at;
                                            $open_date = date("M. d ,Y", strtotime($date));
                                            ?>
                                            <div class="order well card">
                                                <div class="heading__group wrapper border--dashed padding--s no--pad-lr no--pad-t">
                                                    <div class="wrapper__inner">
                                                        <h4 class="textColor--darkest-gray">Return <?php echo $row->id; ?> </h4>
                                                    </div>
                                                    <div class="wrapper__inner align--right">
                                                        <ul class="list list--inline list--divided fontWeight--2 fontSize--s">
                                                            <!--li class="item">
                                                                <a class="link" href="<?php echo base_url(); ?>">Action Required!</a>
                                                            </li--><!-- templates/account/orders/return/number -->
                                                            <li class="item">
                                                                <button class="btn btn--s btn--primary is--link" data-target="<?php echo base_url(); ?>">View Return</button>
                                                                <!-- templates/account/orders/return/number -->
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="order__info col col--10-of-12 col--am">
                                                        <ul class="list list--inline list--stats list--divided">
                                                            <!-- <li class="item" style="width:88px;">
                                                                <div class="order__logo" style="background-image:url('http://placehold.it/192x192');"></div>
                                                            </li> -->
                                                            <li class="item item--stat stat-s">
                                                                <div class="text__group">
                                                                    <span class="line--main"><?php echo $row->name; ?></span>
                                                                    <span class="line--sub">Purchased From</span>
                                                                </div>
                                                            </li>
                                                            <li class="item item--stat stat-s">
                                                                <div class="text__group">
                                                                    <span class="line--main"><?php echo $open_date; ?></span>
                                                                    <span class="line--sub">Date Opened</span>
                                                                </div>
                                                            </li>
                                                            <li class="item item--stat stat-s">
                                                                <div class="text__group">
                                                                    <span class="line--main"><?php echo $row->return_status; ?></span>
                                                                    <span class="line--sub">Status</span>
                                                                </div>
                                                            </li>
                                                            <li class="item item--stat stat-s">
                                                                <div class="text__group">
                                                                    <span class="line--main"><?php echo $row->total_quantity; ?></span>
                                                                    <span class="line--sub">Items</span>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="order__btn col col--2-of-12 col--am align--right">
                                                        <ul class="list list--inline list--stats">
                                                            <li class="item item--stat">
                                                                <div class="text__group">
                                                                    <span class="line--main font">$<?php echo $row->total; ?></span>
                                                                    <span class="line--sub">Pending Refund</span>
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
                                    echo "It's a bit empty here. No Returns Found";
                                }
                                ?>
                                <!-- /Single Order -->

                                <!--
                                    Empty State (Only displayed if there are no results)
                                -->
                                <!-- <div class="align--center padding--m">
                                    <p>There aren't any returns to display.</p>
                                    <button class="btn btn--primary btn--m btn--dir">View Your Orders</button>
                                </div> -->
                                <!-- /Empty State -->
                            </div>
                            <!-- /Returns Tab -->
                        <div id="tabFeedback" class="page__tab">
                            <?php if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $tier_1_2_3a))) {
                                ?>
                                <?php if ($vendor_data != null) {
                                    ?>
                                    <div class="heading__group border--dashed padding--s no--pad-lr no--pad-t wrapper">
                                        <div class="wrapper__inner">
                                            <h4>Vendors Awating Feedback (<?php print_r(count($vendor_data)); ?>)</h4>
                                        </div>
                                        <div id="controlsFeedback" class="contextual__controls wrapper__inner align--right">
                                            <ul class="list list--inline fontWeight--2 fontSize--s margin--xs no--margin-tb no--margin-r">
                                                <li class="item">
                                                    <?php if ($vendor_data != null) { ?>
                                                        <a class="link">Dismiss All</a>
                                                    <?php } ?>
                                                </li>
                                                <li class="item margin--s no--margin-tb no--margin-r is--contextual is--off">
                                                    <a class="link">Dismiss Selected</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php
                                if ($vendor_data != null) {
                                    ?>
                                    <table class="table" data-controls="#controlsFeedback">
                                        <thead>
                                            <tr>
                                                <th width="5%">
                                                    <label class="control control__checkbox">
                                                        <input type="checkbox" class=" is--selector">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </th>
                                                <th width="75%">Vendor Information
                                                </th>
                                                <th width="20%">&nbsp;</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Vendor to Review -->
                                            <?php
                                            for ($i = 0; $i < count($vendor_data); $i++) {
                                                $dates = $vendor_data[$i]->order->created_at;
                                                $order_date = date("M. d ,Y", strtotime($dates));
                                                ?>
                                                <tr>
                                                    <td>
                                                        <label class="control control__checkbox">
                                                            <input type="checkbox" name="checkboxRow">
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <div class="order padding--xs no--pad-lr">
                                                            <ul class="list list--inline list--stats list--divided">
                                                                <li class="item" style="width:72px;">
                                                                    <?php if ($vendor_data[$i]->images != null) { ?>
                                                                        <div class="order__logo" style="background-image:url('<?php echo image_url(); ?>uploads/vendor/logo/<?php echo $vendor_data[$i]->images->photo; ?>');"></div>
                                                                    <?php } else { ?>
                                                                        <div class="order__logo" style="background-image:url('http://placehold.it/192x192');"></div>
                                                                    <?php } ?>
                                                                </li>
                                                                <li class="item item--stat stat-s">
                                                                    <div class="text__group">
                                                                        <span class="line--main"><?php print_r($vendor_data[$i]->name); ?></span>
                                                                        <span class="line--sub">Vendor Name</span>
                                                                    </div>
                                                                </li>
                                                                <li class="item item--stat stat-s">
                                                                    <div class="text__group">
                                                                        <span class="line--main"><?php print_r($order_date); ?></span>
                                                                        <span class="line--sub">Last Order</span
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                    <td class="align--center">
                                                        <button class="btn btn--s btn--primary modal--toggle order-vendor-review" data-id="<?php echo $vendor_data[$i]->id; ?>" data-vendorname="<?php echo $vendor_data[$i]->name ?>" data-target="#vendorReviewModal">Review</button>
                                                        <button class="btn btn--s btn--secondary btn--icon reject-vendor" data-id="<?php echo $vendor_data[$i]->id; ?>"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></button>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <!-- /Vendor to Review-->
                                        </tbody>
                                    </table>
                                <?php } else { ?>
                                    <div class="align--center padding--m">
                                        <p>Nice! You're all caught up with vendor feedback for now.</p>
                                    </div>
                                <?php } ?>
                                <!--
                                    Empty State (Only displayed if there is no feedback to leave)
                                -->
                                <?php if (count($vendor_data) == 0) { ?>
                                    <!--                        <div class="align--center padding--m">
                                                                <p>Nice! You're all caught up with vendor feedback for now.</p>
                                                            </div>-->
                                <?php } ?>
                                <!-- /Empty State -->
                            <?php } ?>
                        </div>
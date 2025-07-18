<style>
    @media screen and (max-width: 700px) {
        .content__wrapper.has--sidebar-r .sidebar .sidebar__group {
            margin-left: 0px;
        }
    }
</style>
<!-- Content Section -->
<div class="overlay__wrapper" id="aaaaaa">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <section class="content__wrapper has--sidebar-r">
        <div class="content__main">
            <div class="row row--full-height">

                <!-- Content Area -->
                <div class="content col col--8-of-12 padding--l no--pad-l">

                    <!-- Vendor Info -->
                    <div class="product product--list row multi--vendor has--promos has--sale content__block">
                        <div class="product__image col col--3-of-12 col--am">

                            <?php
                            if ($image != null) {
                                ?>
                                <a class="product__thumb no--border" href="#" style="background-image:url('<?php echo image_url(); ?>uploads/vendor/logo/<?php echo $image->photo; ?>');"></a>

                            <?php } else { ?>
                                <a class="product__thumb" href="#" style="background-image:url('<?php echo image_url(); ?>assets/img/product-image.png');"></a>

                            <?php } ?>

                        </div>
                        <div class="product__data col col--8-of-12 col--push-1-of-12 col--am">
                            <h2><?php echo ucwords($vendor->name); ?></h2>
                            <p><?php echo $vendor->short_description; ?></p>
                            <div class="ratings__box">
                                <div class="ratings__wrapper show--qty" data-raters="<?php echo $total_ratings; ?>" style="margin-left:-4px;">
                                    <?php
                                    $ratings = 0;
                                    if ($average_rating != null) {
                                        $db_rating = floatval($average_rating);
                                        $ratings = $db_rating * 20;
                                    }
                                    ?>
                                    <div class="ratings">
                                        <div class="stars" data-rating="<?php echo $ratings; ?>" style="width: <?php echo $ratings; ?>%"></div>
                                    </div>
                                </div>
                                <a class="link" href="#reviews">Read customer reviews</a>
                            </div>
                        </div>
                    </div>
                    <!-- /Vendor Info -->
                    <hr>
                    <!-- About Us -->
                    <div id="about" class="content__block">
                        <h3 class="title textColor--dark-gray">
                            <svg class="icon icon--info"><use xlink:href="#icon-info"></use></svg>
                            About Us
                        </h3>
                        <p><?php echo $vendor->description; ?></p>
                    </div>
                    <!-- /About Us -->
                    <hr>
                    <!-- Ratings & Reviews -->
                    <div id="reviews" class="content__block">
                        <div class="heading__group wrapper">
                            <div class="wrapper__inner">
                                <h3 class="title textColor--dark-gray">
                                    <svg class="icon icon--star-outline"><use xlink:href="#icon-star-outline"></use></svg>
                                    Ratings &amp; Reviews
                                </h3>
                            </div>
                            <?php
                            $tier_1_2_3a = unserialize(ROLES_TIER1_2_3A);
                            if (!isset($_SESSION['vendor_id']) && (isset($_SESSION['user_id']))) {
                                if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $tier_1_2_3a)))) {
                                    ?>
                                    <div class="wrapper__inner align--right">
                                        <button class="btn btn--primary btn--s modal--toggle vendor-review" data-id="<?php echo $vendor->id; ?>" data-target="#vendorReviewModal">Write a Review</button>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                        <ul class="list list--ratings list--inline list--divided padding--m no--pad-lr no--pad-t">
                            <li class="item">
                                <h5>Overall</h5>
                                <?php
                                if ($speed != "") {
                                    $speed_rating = floatval($speed);
                                } else {
                                    $speed_rating = 0;
                                }
                                if ($service != "") {
                                    $servise_rating = floatval($service);
                                } else {
                                    $servise_rating = 0;
                                }
                                if ($ease != "") {
                                    $ease_rating = floatval($ease);
                                } else {
                                    $ease_rating = 0;
                                }
                                if ($responsiveness != "") {
                                    $resp_rating = floatval($responsiveness);
                                } else {
                                    $resp_rating = 0;
                                }


                                $s_rating = $speed_rating * 20;
                                $service_rating = $servise_rating * 20;
                                $e_rating = $ease_rating * 20;
                                $r_rating = $resp_rating * 20;
                                ?>
                                <div class="ratings__wrapper has--title">
                                    <div class="ratings">
                                        <div class="stars" data-rating="<?php echo $ratings; ?>" style="width: <?php echo $ratings; ?>%"></div>

                                    </div>
                                </div>
                            </li>
                            <li class="item">
                                <h5>Shipping Speed</h5>
                                <div class="ratings__wrapper has--title">
                                    <div class="ratings">
                                        <div class="stars" data-rating="<?php echo $s_rating; ?>" style="width: <?php echo $s_rating; ?>%"></div>
                                    </div>
                                </div>
                            </li>
                            <li class="item">
                                <h5>Customer Service</h5>
                                <div class="ratings__wrapper has--title">
                                    <div class="ratings">
                                        <div class="stars" data-rating="<?php echo $service_rating; ?>" style="width: <?php echo $service_rating; ?>%"></div>
                                    </div>
                                </div>
                            </li>
                            <li class="item">
                                <h5>Ease of Transaction</h5>
                                <div class="ratings__wrapper has--title">
                                    <div class="ratings">
                                        <div class="stars" data-rating="<?php echo $e_rating; ?>" style="width: <?php echo $e_rating; ?>%"></div>
                                    </div>
                                </div>
                            </li>
                            <li class="item">
                                <h5>Responsiveness</h5>
                                <div class="ratings__wrapper has--title">
                                    <div class="ratings">
                                        <div class="stars" data-rating="<?php echo $r_rating; ?>" style="width: <?php echo $r_rating; ?>%"></div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="reviews__all">
                            <div class="padding--xs no--pad-lr border--1 border--solid border--light border--tb">
                                <div class="wrapper">
                                    <div class="wrapper__inner">
                                        <h4 class="no--margin">All Reviews</h4>
                                    </div>
                                    <div class="wrapper__inner align--right">
                                        <div class="select select--text">
                                            <label class="label">Sort by</label>
                                            <select aria-label="Select a Sorting Option" class="view_review">
                                                <option value="0" selected>Top Rated</option>
                                                <option <?php
                                                if ($options == '1') {
                                                    echo 'selected';
                                                }
                                                ?> value="1">Most Recent</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="vendor_id" class="vendor_id" value="<?php echo $vendor->id; ?>">
                            <!-- Single Review -->
                            <?php
                            if ($vendor_review != null) {
                                for ($i = 0; $i < count($vendor_review); $i++) {
                                    $originalDate = $vendor_review[$i]->updated_at;
                                    $newDate = date("d F Y ", strtotime($originalDate));
                                    $review_rating = floatval($vendor_review[$i]->rating);
                                    $user_rating = $review_rating * 20;
                                    ?>
                                    <div class="review">
                                        <h3 class="title"><?php echo $vendor_review[$i]->title; ?></h3>
                                        <span class="review__meta">
                                            <?php if ($vendor_review[$i]->user != null) { ?>

                                                <?php if ($options == 1) { ?>
                                                    Most Recent Review by
                                                <?php } else { ?>Top Rated Review by <?php } ?>  <?php
                                                echo $vendor_review[$i]->user->first_name;
                                            } else {
                                                echo "Anonymous user updated";
                                            }
                                            ?> on <?php echo $newDate; ?>
                                            <div class="ratings__wrapper ratings--s">
                                                <div class="ratings">

                                                    <div class="stars" data-rating="<?php echo $user_rating; ?>" style="width: <?php echo $user_rating; ?>%"></div>
                                                </div>
                                            </div>
                                        </span>
                                        <p class="review__text">
                                            <?php echo $vendor_review[$i]->comment; ?>
                                        </p>
                                        <span class="voting__meta">
                                            <span class="fontWeight--2"> <?php echo $vendor_review[$i]->upvotes; ?></span>
                                            <?php if ($vendor_review[$i]->upvotes > 0) { ?>
                                                people found
                                                <?php if ($vendor_review[$i]->user != null) { ?>
                                                    <?php
                                                    echo $vendor_review[$i]->user->first_name;
                                                } else {
                                                    echo "Anonymous user updated";
                                                }
                                                ?> review helpful.

                                                <?php
                                            } else {

                                            }
                                            ?>
                                            <?php if (!isset($_SESSION['vendor_id']) && (isset($_SESSION['user_id']))) { ?>
                                                Was this helpful to you?
                                                <ul class="voting__links list list--inline">
                                                    <li><a class="link upvoted" data-id="<?php echo $vendor_review[$i]->id ?>" href="#">Yes</a></li>
                                                    <li><a class="link review_downvote" data-id="<?php echo $vendor_review[$i]->id ?>" href="#">No</a></li>
                                                    <li>|</li>
                                                    <li>
                                                        <?php $admin_roles = unserialize(ROLES_ADMINS); ?>

                                                        <?php if (isset($_SESSION['role_id']) && in_array($_SESSION['role_id'], $admin_roles)) { ?>
                                                            <a class="link fontSize--s fontWeight--2 is--neg" href="<?php echo base_url() ?>delete-vendor-review?review_id=<?php echo $vendor_review[$i]->id; ?>&vendor_id=<?php echo $vendor->id; ?>">Delete</a>
                                                        <?php } else { ?>
                                                            <a class="link modal--toggle review_vendor_flag" data-review="<?php echo $vendor_review[$i]->title; ?>" data-comment="<?php print_r($vendor_review[$i]->comment); ?>" data-p_name="<?php echo $vendor->name; ?>" data-pro_id="<?php echo $vendor->id ?>" data-review_id="<?php echo $vendor_review[$i]->id; ?>" data-target="#flagReviewModal" >Flag It</a>
                                                        <?php } ?>
                                                    </li>



                                                </ul>
                                            <?php } ?>
                                        </span>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                            <!-- /Single Review -->

                            <!-- <a class="link link--expand" href="javascript:;">(+) Load more reviews</a> -->
                        </div>
                    </div>
                    <!-- /Ratings & Reviews -->
                </div>
                <!-- /Content Area -->

                <!-- Sidebar -->
                <div class="sidebar col col--4-of-12" style="padding:12px">

                    <!-- View Products -->
                    <div class="sidebar__group">
                        <h4 class="textColor--dark-gray">Product Catalog</h4>
                        <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == '11') { ?>
                            <button class="btn btn--primary btn--l btn--block is--link" data-target="<?php echo base_url('vendor-products-dashboard') ?>">View Our Products</button>
                        <?php } else { ?>
                            <button class="btn btn--primary btn--l btn--block is--link browse_vendor" data-venid="<?php echo $vendor->id; ?>">View Our Products</button>
                        <?php } ?>
                    </div>
                    <!-- /View Products -->

                    <!-- Contact Info -->
                    <div class="sidebar__group">
                        <h4>Contact Information</h4>
                        <div class="list__combo">
                            <ul class="list list--box">
                                <li class="item">
                                    <div class="text__group group--equal">
                                        <span class="line--main">Mailing Address</span>
                                        <span class="line--main">
                                            <?php echo ucfirst($vendor->shipment_address1); ?><?php echo ($vendor->shipment_address2 != null) ? ", " . ucfirst($vendor->shipment_address2) : ""; ?>
                                            <br />
                                            <?php echo ($vendor->shipment_city != null) ? ucfirst($vendor->shipment_city) . ", " : ""; ?>
                                            <?php
                                            echo $vendor->shipment_state . " ";
                                            echo $vendor->shipment_zip;
                                            ?>
                                            <br />
                                        </span>
                                    </div>
                                    <div class="text__group">
                                        <span class="line--main">Phone Number</span>
                                        <span class="line--main">
                                            <?php
                                            if (strlen($vendor->phone) > 5) {
                                                $phone = ($vendor->phone != 0) ? $vendor->phone : "-";
                                                $areacode = substr($phone, 0, 3);
                                                $prefix = substr($phone, 3, 3);
                                                $number = substr($phone, 6, 4);
                                                echo $phone_number = "(" . $areacode . ")" . $prefix . "-" . $number;
                                            }
                                            ?>
                                        </span>
                                    </div>
                                    <div class="text__group">
                                        <span class="line--main">Business Hours</span>

                                        <!--  M&ndash;F 6:30AM &mdash; 5:00PM PST -->
                                        <?php
                                        if ($business != null) {
                                            for ($i = 0; $i < count($business); $i++) {
                                                ?>
                                                <span class="line--main">
                                                    <?php echo substr_replace($business[$i]->day, "", 3) ?><?php for ($j = 0; $j < count($all_business); $j++) { ?><?php if ($business[$i]->day != $all_business[$j]->day && $business[$i]->open_time == $all_business[$j]->open_time && $business[$i]->close_time == $all_business[$j]->close_time) { ?>, <?php echo substr_replace($all_business[$j]->day, "", 3) ?><?php } ?><?php } ?>
                                                    &ndash; <?php echo date('g:i A', strtotime($business[$i]->open_time)) . " - " ?>
                                                    <?php echo date('g:i A', strtotime($business[$i]->close_time)) ?>
                                                </span>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <span class="line--main">Not updated</span>
                                        <?php } ?>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- /Contact Info -->

                    <!-- Vendor Policies -->
                    <div class="sidebar__group">
                        <h4>Vendor Policies</h4>
                        <ul class="list list--box">
                            <?php
                            if ($vendor_policy != null) {
                                for ($i = 0; $i < count($vendor_policy); $i++) {
                                    ?>
                                    <li class="item">
                                        <div class="text__group">
                                            <span class="line--main"><?php echo $vendor_policy[$i]->policy_name; ?></span>
                                            <span class="line--sub"><?php echo $vendor_policy[$i]->description; ?></span>
                                        </div>
                                    </li>
                                    <?php
                                }
                            } else {
                                ?>
                                <li class="item">
                                    <div class="text__group">
                                        <span class="line--main">No Policies</span>
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <!-- /Vendor Policies -->

                    <!-- Shipping Options -->
                    <div class="sidebar__group">
                        <h4>Shipping Options</h4>
                        <ul class="list list--box">
                            <?php
                            if ($shippings != null) {
                                for ($i = 0; $i < count($shippings); $i++) {
                                    ?>
                                    <li class="item">
                                        <div class="wrapper">
                                            <div class="wrapper__inner">
                                                <div class="text__group">
                                                    <span class="line--main">
                                                        <?php echo $shippings[$i]->shipping_type; ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="wrapper__inner align--right">
                                                <div class="text__group">
                                                    <span>
                                                        <!--  5&ndash;7 -->
                                                        <?php echo ($shippings[$i]->delivery_time == "Same Day") ? "<b>Same</b> Day" : " "; ?>
                                                        <?php echo ($shippings[$i]->delivery_time == "Next Business Day") ? "<b>1</b> Day" : ""; ?>
                                                        <?php echo ($shippings[$i]->delivery_time == "2 Business Days") ? "<b>1-2</b> Days" : ""; ?>
                                                        <?php echo ($shippings[$i]->delivery_time == "3 Business Days") ? "<b>1-3</b> Days" : ""; ?>
                                                        <?php echo ($shippings[$i]->delivery_time == "1-5 Business Days") ? "<b>1-5</b> Days" : ""; ?>
                                                        <?php echo ($shippings[$i]->delivery_time == "7-10 Business Days") ? "<b>7-10</b> Days" : ""; ?>
                                                        <span class="fontWeight--1">
                                                            <!--  Days -->
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <?php
                                }
                            }
                            ?>
                        </ul>
                    </div>
                    <!-- /Shipping Options -->

                </div>
                <!-- /Sidebar -->

            </div>
        </div>
    </section>
</div>
<!-- /Content Section -->

<?php include(INCLUDE_PATH . '/_inc/shared/modals/ask-question.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/answer-question.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/vendor-review.php'); ?>
<?php
include(INCLUDE_PATH . '/_inc/shared/modals/flag_vendor_review.php');



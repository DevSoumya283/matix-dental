<div id="searchResults" class="is--<?php echo $grid; ?>-view cf">
    <?php
    if(!empty($procedure)){
        echo "<h3>Procedure: $procedure</h3>";
    }
    if(!empty($list_id)){
        echo "<h3>Product List: $product_list->listname</h3>";
    }
    for ($i = 0; $i < count($products); $i++) {
        if ($products[$i]->license_required == 'Yes') {
            ?>
            <div class="product product--list row multi--vendor has--promos has--sale req--license " data-target="<?php echo base_url('product'); ?>"><!-- templates/product -->
            <?php } else { ?>
            <div class="product product--list row " data-target="<?php echo base_url('product'); ?>">
            <?php } ?>
                <div class="product__image col-md-3 col-xs-12">
                    <?php
                    if ($products[$i]->photo != null) {
                        ?>
                        <a class="product__thumb" href="<?php echo base_url(); ?>view-product?id=<?php echo $products[$i]->id; ?>&category=<?php echo $this->input->get('category'); ?>" style="background-image:url('<?php echo image_url(); ?>uploads/products/images/<?php echo $products[$i]->photo; ?>');"></a>
                        <?php
                    } else {
                        ?>
                        <a class="product__thumb" href="<?php echo base_url(); ?>view-product?id=<?php echo $products[$i]->id; ?>&category=<?php echo $this->input->get('category'); ?>" style="background-image:url('<?php echo image_url(); ?>assets/img/product-image.png');"></a>
                    <?php } ?>
                </div>
                                    <div class="product__data col-md-9 col-xs-12">
                                            <span class="product__name is--link" data-target="<?php echo base_url(); ?>view-product?id=<?php echo $products[$i]->id; ?>&category=<?php echo trim(explode(',', $products[$i]->category_id)[0],'"'); ?>">
                                                <?php echo $products[$i]->name; ?>
                                            </span>
                                            <span class="product__mfr">
                                                by <a class="link fontWeight--2" href="#"><?php echo $products[$i]->manufacturer; ?></a>
                                            </span>
                                        <div class="row mt-4">
                                            <div class="col-md-7 col-xs-7">
                                                <div class="product__price">
                                                    <?php
                                                    if ($products[$i]->promos != "" || $products[$i]->promos != null) {
                                                        if (count($products[$i]->promos) > 1) {
                                                    ?>
                                                    <ul class="list list--inline list--prices" data-promo="">
                                                        <?php } else { ?>
                                                        <ul class="list list--inline list--prices" data-promo="<?php print_r($products[$i]->promos->title); ?>">
                                                            <?php
                                                            }
                                                        } else {
                                                            ?>
                                                            <ul class="list list--inline" data-promo="">
                                                                <?php } ?>
                                                                <?php
                                                                // product prices / specials
                                                                // print_r($products[$i]);
                                                                Debugger::debug($products[$i], 'product');
                                                                $regular_price = (isset($products[$i]->price)) ? $products[$i]->price : 0.00;
                                                                $retail_price = (isset($products[$i]->retail_price)) ? $products[$i]->retail_price : 0.00;
                                                                //correct pricing inconsistencies
                                                                if($regular_price < $retail_price)
                                                                {
                                                                    //swap values
                                                                    list($regular_price,$retail_price) = array($retail_price,$regular_price);
                                                                }
                                                                if(!empty($_SESSION['user_buying_clubs'])){
                                                                    Debugger::debug($products[$i]->product_price, 'product_price');
                                                                    $clubPrice = $bcModel->getBestPrice($products[$i]->id, $products[$i]->product_price->vendor_id, $bcPrices, $_SESSION['user_buying_clubs'], $retail_price);
                                                                }
                                                                if(!empty($clubPrice)){
                                                                    $regular_price = $clubPrice;
                                                                }
                                                                $regular_price = number_format($regular_price, 2);
                                                                if($retail_price > $regular_price) {
                                                                    //output price with special
                                                                    ?>
                                                                    <li style="font-size: 22px;font-weight: bold;text-decoration:line-through;">$<?php  echo $retail_price;  ?></li>
                                                                    <li style="font-size: 22px;font-weight: bold;color:#13C4A3;" <?php if(!empty($clubPrice)) echo 'class="club--price"'; ?>>$<?php echo $regular_price; ?></li>
                                                                    <?php
                                                                } else {
                                                                    //output normal price
                                                                    ?>
                                                                    <li style="font-size: 22px;font-weight: bold;" <?php if(!empty($clubPrice)){ echo 'class="club--price"'; }?>>$<?php echo $regular_price; ?></li>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </ul>

                                                </div>
                                                <?php
                                                $db_rating = floatval($products[$i]->average_rating);
                                                $ratings = $db_rating * 20;
                                                $user_count = 0;
                                                foreach ($review as $key) {
                                                    if ($key->model_id == $products[$i]->id) {
                                                        $db_count = count($key->model_id);
                                                        $user_count = $user_count + $db_count;
                                                    }
                                                }
                                                ?>

                                                   <div class="row d-none d-sm-block">
                                        <?php if(empty($this->config->item('whitelabel'))){ ?>
                                            <div class="product__vendor-range col-md-12">
                                                                    <?php
                                                                    $p_count = count($products[$i]->price);
                                                                    // if (isset($products[$i]->price[0])) {
                                                                    //     if ($products[$i]->price[0]->min_value != null) {
                                                                    //         echo "$" . $products[$i]->price[0]->min_value;
                                                                    //     } elseif ($products[$i]->price[0]->minprice_value != null) {
                                                                    //         echo "$" . $products[$i]->price[0]->minprice_value;
                                                                    //     } else {
                                                                    //         echo "$" . $products[$i]->price[0]->max_value;
                                                                    //     }
                                                                    // }
                                                                    if ($p_count > 1) {
                                                                        $total = $p_count - 1;
                                                                        if ($products[$i]->price[$total]->max_value != null) {
                                                                            echo " &ndash;  $" . $products[$i]->retail_price;
                                                                        }
                                                                    } else {
                                                                        echo "";
                                                                    }
                                                                    ?> (<?php echo $products[$i]->vendor_count; ?> Vendors)
                                                                </div>
                                        <?php } ?>
                                        <div class="ratings__wrapper show--qty col-md-12" data-raters="<?php echo $user_count; ?>">
                                            <div class="ratings">
                                                <div class="stars" data-rating="<?php echo $ratings; ?>" style="width: <?php echo $ratings; ?>%"></div>
                                            </div>
                                        </div>
                                    </div>

                                            </div>
                                            <?php
                                            $tier_1_2_roles = unserialize(ROLES_TIER1_2);
                                            $users = unserialize(ROLES_USERS);
                                            if (isset($_SESSION["user_id"])) {
                                                ?>
                                                <?php if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $users))) { ?>
                                                    <div class="col-md-5 col-xs-12 cart-div">
                                                        <div class="input__combo has--tip wrap" data-tip="" data-tip-position="left">
                                                            <input type="number" name="qty" class="input input--qty request_quantity aaa"  min="1" value="1">
                                                            <div class="btn__group">
                                                                <?php
                                                                if ($products[$i]->vendor_count > 0 && isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $tier_1_2_roles)))) { ?>
                                                                    <!-- if ($products[$i]->vendor_count > 0 && isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $tier_1_2_roles))) && ($products[$i]->license_required != 'Yes' || !empty($userLicenses))) { ?> -->
                                                                    <button class="btn btn--m btn--tertiary btn--icon modal--toggle add_cart" data-pid="<?php echo $products[$i]->id; ?>" data-name="<?php echo $products[$i]->name; ?>" data-price="<?php echo $regular_price?>" data-procolor="<?php echo $products[$i]->color; ?>" data-vendor_id="<?php echo $products[$i]->v_id ?>" data-license_required="<?php echo $products[$i]->license_required; ?>" data-target="#chooseLocationModal"><svg class="icon icon--cart-s"><use xlink:href="#icon-cart-s"></use></svg></button>
                                                                <?php } ?>
                                                                <?php if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $users))) { ?>
                                                                    <button class="btn btn--m btn--tertiary btn--icon modal--toggle add_request" data-id="<?php echo $products[$i]->id; ?>" data-vendor="<?php echo $products[$i]->vendor_id; ?>" data-target="#chooseRequestListModal"><svg class="icon icon--list-s"><use xlink:href="#icon-list-s"></use></svg></button>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <div class="col-md-5 col-xs-12 cart-div">
                                                    <div class="input__combo has--tip" data-tip="" data-tip-position="left">
                                                        <input type="number" class="input input--qty request_quantity aaa" min="1" value="1">
                                                        <div class="btn__group">
                                                            <button class="btn btn--m btn--tertiary btn--icon modal--toggle user_login"  data-target="#"><svg class="icon icon--cart-s"><use xlink:href="#icon-cart-s"></use></svg></button>
                                                            <button class="btn btn--m btn--tertiary btn--icon modal--toggle user_login" data-target="#"><svg class="icon icon--list-s"><use xlink:href="#icon-list-s"></use></svg></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                        </div>

                                    </div>
                                                                        <div class="row rating-row d-block d-sm-none">
                                        <?php if(empty($this->config->item('whitelabel'))){ ?>
                                            <div class="product__vendor-range col-md-12">
                                                                    <?php
                                                                    $p_count = count($products[$i]->price);
                                                                    if (isset($products[$i]->price[0])) {
                                                                        if ($products[$i]->price[0]->min_value != null) {
                                                                            echo "$" . $products[$i]->price[0]->min_value;
                                                                        } elseif ($products[$i]->price[0]->minprice_value != null) {
                                                                            echo "$" . $products[$i]->price[0]->minprice_value;
                                                                        } else {
                                                                            echo "$" . $products[$i]->price[0]->max_value;
                                                                        }
                                                                    }
                                                                    if ($p_count > 1) {
                                                                        $total = $p_count - 1;
                                                                        if ($products[$i]->price[$total]->max_value != null) {
                                                                            echo " &ndash;  $" . $products[$i]->price[$total]->max_value;
                                                                        }
                                                                    } else {
                                                                        echo "";
                                                                    }
                                                                    ?> (<?php echo $products[$i]->vendor_total[0]->v_total; ?> Vendors)
                                                                </div>
                                        <?php } ?>
                                        <div class="ratings__wrapper show--qty col-md-12" data-raters="<?php echo $user_count; ?>">
                                            <div class="ratings">
                                                <div class="stars" data-rating="<?php echo $ratings; ?>" style="width: <?php echo $ratings; ?>%"></div>
                                            </div>
                                        </div>
                                    </div>
                                        </div>
                                    <?php } ?>
                                    </div>
                                    <div class="well padding--xxs" >
                                       <br> <div class="row">
                                            <div class="col-md-6 col-xs-12">
                                                <div class="select select--text">
                                                    <label class="label fontWeight--1">Showing</label>
                                                    <select aria-label="Select a Sorting Option" class="per_page_count" data-category="<?php echo $this->input->get('category'); ?>">
                                                        <option <?php
                                                        if ($per_page == "10") {
                                                            echo " selected";
                                                        }
                                                        ?> value="10">10 items</option>
                                                        <option <?php
                                                        if ($per_page == "25") {
                                                            echo " selected";
                                                        }
                                                        ?> value="25">25 items</option>
                                                        <option <?php
                                                        if ($per_page == "50") {
                                                            echo " selected";
                                                        }
                                                        ?> value="50">50 items</option>
                                                        <option <?php
                                                        if ($per_page == "100") {
                                                            echo " selected";
                                                        }
                                                        ?> value="100">100 items</option>
                                                    </select>
                                                    <label class="label padding--xs no--pad-r no--pad-tb fontWeight--1">per page</label>
                                                </div>
                                            </div>
                                            <div  class="col-md-6 col-xs-12 align--right">
                                                <ul class="list list--inline list--pagination">
                                                    <input type="hidden" name="" class="total_page" value="<?php echo $pages; ?>">
                                                    <!--
                                                        NOTE:
                                                        Use '.is--hidden' to display the needed components for pagination. See below:
                                                        1 - '#pageFirst' is only displayed if the   user is passed the first 3 pages.
                                                        2 -  '#pagePrev' is only displayed if the user is passed the first page.
                                                        3 - '#pageMiddle' is only displayed if there are more than 10 pages of results. In this case, the first 3 pages are shown, the middle pages are hidden with the '...' and the last page is shown at the end.
                                                        4 - '#pageNext' & '#pageLast' are only displayed if the user is not on the last page.
                                                    -->
                                                    <?php
                                                    $pagination_start = 1;
                                                    $pagination_end = 3;
                                                    $pagination_limit = 3;
                                                    if ($page > 1 && $pages > 3) {
                                                        $pagination_start = $page + 1;
                                                        if (($pagination_start + $pagination_limit) > $pages) {
                                                            $pagination_end = $pages;
                                                        } else {
                                                            $pagination_end = $pagination_start + $pagination_limit;
                                                        }
                                                    } else {
                                                        $pagination_start = $page + 1;
                                                        if ($pages > 3) {
                                                            $pagination_end = $pagination_start + $pagination_limit;
                                                        } else {
                                                            $pagination_end = $pages;
                                                        }
                                                    }

                                                    ?>

                                                    <?php if ($page >= 3) { ?>
                                                        <li class="page " id="pageFirst">
                                                            <a class="link pagesnippet" href="#">First</a>
                                                        </li>
                                                    <?php } ?>
                                                    <?php if ($page > 0) { ?>
                                                        <li class="page " id="pagePrev">
                                                            <a class="link pagesnippet" href="#">&larr;</a>
                                                        </li>
                                                    <?php } ?>
                                                    <?php for ($i = $pagination_start; $i <= $pagination_end; $i++) { ?>
                                                        <li class="page is--third <?php echo ($page == ($i - 1)) ? "is--active" : "" ?>">
                                                            <a class="link pagesnippet" id="page_no" href="#" ><?php echo $i; ?></a>
                                                        </li>
                                                    <?php } ?>
                                                    <?php if ($pages > 3 && $page != ($pages - 1)) { ?>
                                                        <li class="page" id="pageMiddle">...</li>
                                                        <li class="page is--last">
                                                            <a class="link pagesnippet" id="page_no" href="#"><?php echo $pages ?></a>
                                                        </li>
                                                    <?php } ?>
                                                    <?php if ($pages > ($page + 1)) { ?>
                                                        <li class="page" id="pageNext">
                                                            <a class="link pagesnippet" href="#">&rarr;</a>
                                                        </li>
                                                        <li class="page" id="pageLast">
                                                            <a class="link pagesnippet" href="#">Last</a>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /Search Results Pagination -->
                                    <?php include(INCLUDE_PATH . '/_inc/shared/modals/choose-location.php'); ?>
                                    <?php include(INCLUDE_PATH . '/_inc/shared/modals/choose-request-list.php'); ?>
                                    <script src="<?php echo base_url(); ?>assets/js/jquery.detect-card.js"></script>
                                    <script src="<?php echo base_url(); ?>assets/js/jquery.maskedinput.min.js"></script>
                                    <script src="<?php echo base_url(); ?>lib/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
                                    <script src="<?php echo base_url(); ?>lib/bootstrap-timepicker/bootstrap-timepicker.js"></script>
                                    <script src="<?php echo base_url(); ?>lib/jquery-maskmoney/jquery.maskMoney.min.js"></script>
                                    <script src="<?php echo base_url(); ?>lib/jquery-validate/jquery.validate.min.js"></script>
                                    <script src="<?php echo base_url(); ?>lib/jquery-flexslider2/jquery.flexslider-min.js"></script>
                                    <!-- build:js js/main.min.js -->
                                    <script src="<?php echo base_url(); ?>assets/js/moment.js"></script>
                                    <script src="<?php echo base_url(); ?>assets/js/main-addition.js"></script>
                                    <!-- endbuild -->
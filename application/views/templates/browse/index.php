<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>
    <section class="content__wrapper has--sidebar-l">
        <div class="content__main container">
            <div style="margin-top: 0px" class="row">
                <div class="col-md-2 no--margin no--pad" style="max-width: 150px;z-index: 99">
                    <div class="tab__group d-none" data-target="#categoryTree">
                        <?php foreach ($category as $row) { ?>
                            <label class="tab cat_view" id="<?php echo trim(strtolower($row->name)); ?>" value="is--<?php echo trim(strtolower($row->name)); ?>-view" parent="<?php echo $row->id ?>">
                                <input type="radio" name="categoryView" id="<?php echo trim(strtolower($row->name)); ?>Title">
                                <span><?php echo $row->name; ?></span>
                            </label>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-md-2 no--margin no--pad d-block d-sm-none" style="max-width: 90px">
                    <select name="filter" class="form-control filter-select">
                        <option selected value="">Filter</option>
                        <option>Highest Rated</option>
                        <option>License not required</option>
                        <?php
                        if (isset($_SESSION["userLocations"])) {
                            if ($orders != null) {
                                ?>
                                <option>Items I've Purchased</option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <!--  <div class="col-md-1 no--margin no--pad" style="max-width: 30px">
                 <i class="fa fa-recycle pointer filterslide" aria-hidden="true"></i>
                 </div> -->
                <div class="col-md-2 no--margin no--pad d-block d-sm-none" style="max-width: 90px">
                    <select name="filter2" class="form-control filter-select" id="sort_products1">
                        <option selected value="">Relevance</option>
                        <option value="price">Price</option>
                        <option value="vendor">Vendor(s)</option>
                        <option value="mfc">Manfacturer</option>
                    </select>
                </div>
                <div class="col-md-2 no--margin no--pad sortfilter d-none" style="max-width: 150px">
                    <div class="select select--text">
                        <label class="label">Sort by</label>
                        <select aria-label="Select a Sorting Option" id="sort_products2" >
                            <option selected>Relevance</option>
                            <option value="price">Price</option>
                            <option value="vendor">Vendor(s)</option>
                            <option value="mfc">Manfacturer</option>
                        </select>
                    </div>
                </div>
                <ul class="list list--tree fontSize--s abcd d-block d-sm-none" style="margin-top:24px;">
                    <ul>
                        <div class="col-md-2 no--margin no--pad d-none" style="max-width: 150px">
                            <div class="tab__group" data-target="#searchResults">
                                <label class="tab select_view" data-view="list" value="is--list-view">
                                    <input type="radio" name="groupName" checked>
                                    <span>List View</span>
                                </label>
                            </div>
                        </div>
            </div>


            <script>
                $(document).ready(function(){
                    $(".filterslide").click(function(){
                        $(".slide-row").slideToggle("slow");
                    });
                });
            </script>
            <div class="row" style="margin-top: 0px">
                <!-- Sidebar -->
                <div class="sidebar col-md-4 col-xs-12  d-none d-sm-block">
                    <div class="sidebar__group">
                        <div class="group__heading">
                            <!-- Categories -->
                            <!--    <div class="tab__group" data-target="#categoryTree">
                                <?php foreach ($category as $row) { ?>
                                    <label class="tab cat_view" id="<?php echo trim(strtolower($row->name)); ?>" value="is--<?php echo trim(strtolower($row->name)); ?>-view" parent="<?php echo $row->id ?>">
                                        <input type="radio" name="categoryView" id="<?php echo trim(strtolower($row->name)); ?>Title">
                                        <span><?php echo $row->name; ?></span>
                                    </label>
                                <?php } ?>
                            </div> -->


                            <a class="link fontSize--s fontWeight--2" style="display:none;">View All</a>
                            <ul class="list list--tree fontSize--s abcd   d-none d-sm-block" style="margin-top:24px;">
                                <ul>
                                    <!-- /Categories -->
                        </div>
                    </div>
                    <?php if(!empty($this->input->get("category"))){
                        ?>
                        <script>
                            $.getJSON("/rebuild-cat-nav?category=" + <?php echo $this->input->get("category"); ?>, function (data) {
                                $('.list--tree').html(data.menu);
                            });
                        </script>
                    <?php } ?>
                    <!-- Filters -->
                    <div class="sidebar__group">
                        <div class="group__heading">
                            Filters
                            <a class="link fontSize--s fontWeight--2 uncheck show_filter1clear" style="display:none;">Clear All</a>
                        </div>
                        <!-- Filter Group -->
                        <div id="filters">
                            <div class="list__title fontWeight--2">Product</div>
                            <form class="filters filters--form" action="">
                                <ul class="list product_filter">
                                    <?php
                                    if (isset($_SESSION["userLocations"])) {
                                        if ($orders != null) {
                                            ?>
                                            <li class="item filter1">
                                                <label class="control control__checkbox">
                                                    <input type="checkbox" name="checkbox" class="myproduct checkbox_cart">
                                                    <div class="control__indicator"></div>
                                                    Items I've Purchased
                                                </label>
                                            </li> <?php
                                        }
                                    }
                                    ?>
                                    <li class="item filter1">
                                        <label class="control control__checkbox">
                                            <input type="checkbox" name="checkbox" class="license_data checkbox_check" value="yes">
                                            <div class="control__indicator"></div>
                                            License Not Required
                                        </label>
                                    </li>
                                </ul>
                            </form>

                            <!-- Promotions -->
                            <div class="">
                                <div class="list__title fontWeight--2 is--active">
                                    Rating
                                    <a class="link fontSize--xs fontWeight--2 uncheck_radio show_filter2clear" style="display:none;">Clear</a>
                                </div>
                                <form class="filters filters--form" action="">
                                    <ul class="list list--ratings in--sidebar">
                                        <li class="item filter2">
                                            <label class="control control__radio">
                                                <input type="radio" name="rating" value="5" class="rates">
                                                <div class="control__indicator"></div>
                                                <div class="ratings__wrapper">
                                                    <div class="ratings">
                                                        <div class="stars" data-rating="100"></div>
                                                        <div class="stars__mask"></div>
                                                    </div>
                                                </div>
                                            </label>
                                        </li>
                                        <li class="item filter2">
                                            <label class="control control__radio">
                                                <input type="radio" name="rating" value="4" class="rates">
                                                <div class="control__indicator"></div>
                                                <div class="ratings__wrapper">
                                                    <div class="ratings">
                                                        <div class="stars" data-rating="80"></div>
                                                    </div>
                                                </div>
                                                +
                                            </label>
                                        </li>
                                        <li class="item filter2">
                                            <label class="control control__radio">
                                                <input type="radio" name="rating" value="3" class="rates">
                                                <div class="control__indicator"></div>
                                                <div class="ratings__wrapper">
                                                    <div class="ratings">
                                                        <div class="stars" data-rating="60"></div>
                                                    </div>
                                                </div>
                                                +
                                            </label>
                                        </li>
                                        <li class="item filter2">
                                            <label class="control control__radio">
                                                <input type="radio" name="rating" value="2" class="rates">
                                                <div class="control__indicator"></div>
                                                <div class="ratings__wrapper">
                                                    <div class="ratings">
                                                        <div class="stars" data-rating="40"></div>
                                                    </div>
                                                </div>
                                                +
                                            </label>
                                        </li>
                                        <li class="item filter2">
                                            <label class="control control__radio">
                                                <input type="radio" name="rating" value="1" class="rates">
                                                <div class="control__indicator"></div>
                                                <div class="ratings__wrapper">
                                                    <div class="ratings">
                                                        <div class="stars" data-rating="20"></div>
                                                    </div>
                                                </div>
                                                +
                                            </label>
                                        </li>
                                    </ul>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /Filters -->
                </div>
                <!-- /Sidebar -->
                <!-- Content Area -->
                <div class="content col-md-8 col-xs-12">
                    <!-- Search Result Controls -->
                    <div class="padding--xs no--pad-lr border--1 border--solid border--light border--b">
                        <div class="wrapper ">
                            <div class="wrapper__inner d-none">
                                <div class="tab__group" data-target="#searchResults">
                                    <label class="tab select_view" data-view="list" value="is--list-view">
                                        <input type="radio" name="groupName" checked>
                                        <span>List View</span>
                                    </label>
                                    <!--   <label class="tab select_view " data-view="grid" value="is--grid-view">
                                          <input type="radio" name="groupName">
                                          <span>Grid View</span>
                                      </label> -->
                                </div>
                            </div>
                            <div class="wrapper__inner align--right d-none d-sm-block">
                                <div class="select select--text">
                                    <label class="label">Sort by</label>
                                    <select aria-label="Select a Sorting Option" id="sort_products3">
                                        <option selected>Relevance</option>
                                        <option value="price">Price</option>
                                        <option value="mfc">Manfacturer</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Search Result Controls -->
                    <div id="resultsWrapper">
                        <!-- Search Results -->
                        <div id="searchResults" class="is--list-view cf">
                            <!--product list.php-->
                            <?php
                            //Debugger::debug($products);
                            for ($i = 0; $i < count($products); $i++) {
                            if ($products[$i]->license_required == 'Yes') {
                            ?>
                            <div class="product product--list row multi--vendor has--promos req--license has--sale" data-target="<?php echo base_url('product'); ?>"><!-- templates/product -->
                                <?php } else { ?>
                                <div class="product product--list row multi--vendor has--promo  has--sale" data-target="<?php echo base_url('product'); ?>">
                                    <?php } ?>
                                    <div class="product__image col-md-3 col-xs-12">
                                        <?php
                                        if ($products[$i]->photo != null) {
                                            ?>
                                            <a class="product__thumb" onclick="window.location.href='<?php echo base_url(); ?>view-product?id=<?php echo $products[$i]->id; ?>&category=' + (parseInt(category) ? category : '') + '&'" style="background-image:url('<?php echo image_url(); ?>uploads/products/images/<?php echo $products[$i]->photo; ?>');"></a>
                                            <?php
                                        } else {
                                            ?>
                                            <a class="product__thumb" onclick="window.location.href='<?php echo base_url(); ?>view-product?id=<?php echo $products[$i]->id; ?>&category=' + (parseInt(category) ? category : '') + '&'" style="background-image:url('<?php echo image_url(); ?>assets/img/product-image.png');"></a>
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
                                                                // Debugger::debug($products[$i], 'product', true);
                                                                $regular_price = (isset($products[$i]->price)) ? $products[$i]->price : 0.00;
                                                                $retail_price = (isset($products[$i]->retail_price)) ? $products[$i]->retail_price : 0.00;
                                                                //correct pricing inconsistencies
                                                                // if($regular_price == 0 && $retail_price != 0)
                                                                // {
                                                                //     //swap values
                                                                //     list($regular_price,$retail_price) = array($retail_price,$regular_price);
                                                                // }
                                                                if(!empty($_SESSION['user_buying_clubs'])){
                                                                    Debugger::debug($products[$i]->product_price, 'product_price');
                                                                    $this->load->model('BuyingClub_model');
                                                                    $clubPrice = $this->BuyingClub_model->getBestPrice($products[$i]->id, $products[$i]->vendor_id, $bcPrices, $_SESSION['user_buying_clubs'], $regular_price);
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
                                                    // Debugger::debug($products[$i]);
                                            if (isset($_SESSION["user_id"])) {
                                                ?>
                                                <?php if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $users))) {
                                                    ?>
                                                    <div class="col-md-5 col-xs-12 cart-div">
                                                        <div class="input__combo has--tip wrap" data-tip="" data-tip-position="left">
                                                            <input type="number" name="qty" class="input input--qty request_quantity aaa"  min="1" value="1">
                                                            <div class="btn__group">
                                                                <?php if ($products[$i]->vendor_count > 0 && isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $tier_1_2_roles))) ) { ?>
                                                                    <button class="btn btn--m btn--tertiary btn--icon modal--toggle add_cart" data-pid="<?php echo $products[$i]->id; ?>" data-name="<?php echo $products[$i]->name; ?>" data-price="<?php echo $regular_price?>" data-procolor="<?php echo $products[$i]->color; ?>" data-vendor_id="<?php echo $products[$i]->vendor_id ?>" data-license_required="<?php echo $products[$i]->license_required; ?>" data-target="#chooseLocationModal"><svg class="icon icon--cart-s"><use xlink:href="#icon-cart-s"></use></svg></button>
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
                                <!--product list.php-->
                            </div>
                            <!-- /Search Results -->
                            <!-- Search Results Pagination -->
                            <div class="well padding--xxs" id="pagination_results">
                                <br><div class="row">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="select select--text">
                                            <label class="label fontWeight--1">Showing</label>
                                            <select aria-label="Select a Sorting Option" class="per_page_count" data-category="<?php echo $this->input->get('category'); ?>">
                                                <option selected value="10">10 items</option>
                                                <option value="25">25 items</option>
                                                <option value="50">50 items</option>
                                                <option value="100">100 items</option>
                                            </select>
                                            <label class="label padding--xs no--pad-r no--pad-tb fontWeight--1">per page</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12 align--right">
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
                                            // Debugger::debug($page, '$page');
                                            // Debugger::debug($pages, '$pages');
                                            // Debugger::debug($pagination_start, '$pagination_start');
                                            // Debugger::debug($pagination_end, '$pagination_end', true);
                                            ?>
                                            <?php if ($page >= 3) { ?>
                                                <li class="page " id="pageFirst">
                                                    <a class="  link pagesnippet" href="#">First</a>
                                                </li>
                                            <?php } ?>
                                            <?php if ($page > 0) { ?>
                                                <li class="page " id="pagePrev">
                                                    <a class="link pagesnippet" href="#">&larr;</a>
                                                </li>
                                            <?php } ?>
                                            <?php for ($i = $pagination_start; $i <= $pagination_end; $i++) { ?>
                                                <li class="page is--third <?php echo ($page == ($i - 1)) ? "is--active" : "" ?>"pages>
                                                    <a class="link pagesnippet" id="page_no" href="#" data-category="<? echo $this->input->get('category'); ?>"><?php echo $i; ?></a>
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
                        </div>
                    </div>
                    <!-- /Content Area -->
                </div>
            </div>
    </section>
</div>
<!-- /Content Section -->
<!-- Modals -->
<?php $this->load->view('templates/_inc/shared/modals/choose-location.php')?>
<?php $this->load->view('templates/_inc/shared/modals/choose-request-list.php')?>


<script type="text/javascript">
    $('.select_view').on('click',function(){
        var type=$(this).attr('data-view');
        if(type=="grid"){
            $('.product__image').removeClass('col-md-3');code
            $('.product__image').addClass('col-md-12');
        }
        else{
            $('.product__image').removeClass('col-md-12');
            $('.product__image').addClass('col-md-3');

        }
    });
</script>
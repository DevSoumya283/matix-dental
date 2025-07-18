<!-- Content Section -->
         <link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>assets/css/jsRapStar.css">
<div class="overlay__wrapper">
    <!-- Breadcrumbs Bar -->
    <div class="bar padding--xs bg--lightest-gray">
        <div class="wrapper wrapper--fixed">
            <ul class="list list--inline list--breadcrumbs">
                <?php if($category_hierarchy): ?>
                    <?php foreach($category_hierarchy as $category): ?>
                    <li class="item">
                        <a class="link bread-crumb-link" href="<?=base_url('home')?>?category=<?=$category->id?>">
                            <?=$category->name;?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                <?php endif; ?>
                <li class="item is--active">
                    <?=$product->name;?>
                </li>
            </ul>
        </div>
</div>
    <!-- /Breadcrumbs Bar -->
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>
    <!-- Banner Notification -->
    <?php if($product->license_required == 'Yes'): ?>
        <?php if($has_license != 1): ?>
            <div class="banner is--warning">
                <span class="banner__text">
                    <?php if($has_license == 0): ?>
                        A license is required to purchase this product in this location.
                    <?php elseif($has_license == 2): ?>
                        A license is required to purchase this product in one or more locations.
                    <?php endif; ?>
                    <?php $roles = unserialize(ROLES_USERS); ?>
                    <?php $vendor_roles = unserialize(ROLES_VENDORS); ?>
                    <?php $tier_1_2ab = unserialize(ROLES_TIER1_2_AB); ?>
                    <?php if (isset($_SESSION["user_id"])): ?>
                        <?php if (($_SESSION['role_id']) && (in_array($_SESSION['role_id'], $tier_1_2ab))): ?>
                            <a class="banner__action link modal--toggle" data-target="#addNewLicenseModal">
                                Add a License
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </span>
                <a class="link link--icon dismiss--banner"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <!-- /Banner Notification -->
    <section class="content__wrapper has--sidebar-r">
        <div class="content__main container">
            <div class="row row--full-height">
                <!-- Content Area -->
                <div class="content col-md-8 col-xs-12">
                    <?php                    // product prices / sales set
                    $regular_price = (isset($products_pricings[0]->price)) ? $products_pricings[0]->price : 0.00;
                    $retail_price = (isset($products_pricings[0]->retail_price)) ? $products_pricings[0]->retail_price : 0.00;
                    //correct pricing inconsistencies
                    // Debugger::debug($products_pricings);
                    // Debugger::debug($regular_price, '$regular_price');
                    // Debugger::debug($retail_price, '$retail_price');
                    // if($regular_price == 0 && $retail_price != 0)
                    // {
                    //     //swap values
                    //     list($regular_price,$retail_price) = array($retail_price,$regular_price);
                    // }
                    $sale = ($retail_price < $regular_price && $retail_price != 0) ? 'data-promo=""' : null;
                    // check if there is a promo
                    $has_promos = false;
                    if ($vendors != null) {
                        foreach($vendors as $vendor){
                            $has_promos = ($vendor->promo_title != "" || $sale != null) ? true : false;
                        }
                    }
                    // set product css classes
                    $product_classes = "product testing product--list row multi--vendor has--sale margin--m no--margin-t no--margin-l no--margin-r req--license ";
                    $product_classes = ($has_promos) ? $product_classes . 'has--promos ' : $product_classes;
                    $curproductid = $product->id;
                    ?>
                    <!-- Product Info -->
                    <div class="<?php echo $product_classes; ?>">
                        <div class="product__image col-md-4 col-xs-12" style="transform:translateX(-16px);">
                            <!-- Product Image(s) -->
                            <div class="row">
                                <!--
                                    NOTE: If there is only 1 product image, the following <div> should be removed.
                                -->
                                <div class="col-md-2 col-xs-12">
                                    <ul class="list list--thumbs">
                                        <!--
                                            NOTE: If more than 3 additional photos are attached, they are hidden from this list using CSS.
                                        -->
                                        <?php
                                        if ($sub_image != null) {
                                            for ($i = 0; $i < count($sub_image); $i++) {
                                                ?>
                                                <li class="item">
                                                    <a href="javascript:;" class="modal--toggle" data-target="#viewImageModal">
                                                        <div class="product__thumb" style="background-image:url('<?php echo image_url(); ?>uploads/products/images/<?php echo $sub_image[$i]->photo; ?>');"></div>
                                                    </a>
                                                </li>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </ul>
                                </div>
                                <!--
                                    NOTE: If there is only 1 product image, the following <div> should have no classes.
                                -->
                                <div class="col-md-9 col-xs-12">
                                    <?php if ($main_image != null) { ?>
                                        <a href="javascript:;" class="modal--toggle" data-target="#viewImageModal" >
                                            <div class="product__thumb popup_image" style="background-image:url('<?php echo image_url(); ?>uploads/products/images/<?php echo $main_image->photo; ?>')">
                                            </div>
                                        </a>
                                        <?php
                                    } else {
                                        ?>
                                        <div class="product__thumb" style="background-image:url('<?php echo image_url(); ?>assets/img/product-image.png')">
                                            <input type="hidden" name="aaaa"  value="<?php echo $product->id; ?>" id="field-function_purpose">
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <!-- /Product Image(s) -->
                        </div>
                        <div class="product__data col-md-7 col-xs-12" style="padding-left:32px !important;">
                            <?php if ($product->license_required == 'Yes') { ?>
                                <span class="product__name"><?php echo $product->name; ?></span>
                            <?php } else { ?>
                                <span style="font-size: 22px;"><?php echo $product->name; ?></span>
                            <?php }
                            ?>
                            <span class="product__mfr">
                                by <a class="link fontWeight--2" href="#"><?php echo $product->manufacturer; ?></a>
                            </span>
                            <div class="product__controls wrapper">
                                <div class="wrapper__inner">
                                    <div id="product_price" class="product__price">
                                        <?php if ($products_pricings[0]->retail_price != $retail_price || $has_promos == true) { ?>
                                            <ul class="list list--inline list--prices" <?php echo $sale; ?>>
                                        <?php } else { ?>
                                            <ul class="list list--inline" <?php echo $sale; ?>>
                                        <?php } ?>
                                                <?php
                                                $vendor_id = $products_pricings[0]->vendor_id;
                                                $product_id = $product->id;
                                                $clubPrice = null;
                                                $clubPrice = $bcModel->getBestPrice($product_id, $vendor_id, $bcPrices, $_SESSION['user_buying_clubs'], $retail_price);
                                                Debugger::debug($regular_price, '$regular_price');
                                                if(!empty($clubPrice)){
                                                    $retail_price = $clubPrice;
                                                }
                                                $retail_price = number_format($retail_price, 2);
                                                Debugger::debug($regular_price, '$regular_price');
                                                Debugger::debug($retail_price, '$retail_price');
                                                // product prices / specials displayed
                                                if($retail_price < $regular_price && $retail_price != 0)
                                                {
                                                    //output price with special
                                                    ?>
                                                    <li class="retail-price" style="font-size: 22px;font-weight: bold;text-decoration:line-through;">$<?php  echo $regular_price; ?></li>
                                                    <li class="sale-price <?php if(!empty($clubPrice)) echo 'club--price'; ?>" style="font-size: 22px;font-weight: bold;color:#13C4A3;">$<?php echo $retail_price; ?></li>
                                                    <?php
                                                }
                                                else
                                                {
                                                    //output normal price
                                                    if(empty($inactive)){
                                                    ?>
                                                    <li class="regular-price <?php if(!empty($clubPrice)) echo 'club--price'; ?>" style="font-size: 22px;font-weight: bold;" >$<?php echo $regular_price; ?></li>
                                                    <?php
                                                    }
                                                }
                                                ?>
                                            </ul>
                                            <?php if(empty($this->config->item('whitelabel'))){ ?>
                                            <span id="product_vendor-range" class="product__vendor-range">
                                                <?php
                                                $p_count = count($price);
                                                if (isset($price[0])) {
                                                    if (is_object($price[0])){
                                                        if ($price[0]->min_value != null) {
                                                            echo "$" . $price[0]->min_value;
                                                        } elseif ($price[0]->minprice_value != null) {
                                                            echo "$" . $price[0]->minprice_value;
                                                        } else {
                                                            echo "$" . $price[0]->max_value;
                                                        }
                                                    }
                                                }
                                                if ($p_count > 1) {
                                                    $total = $p_count - 1;
                                                    if ($price[$total]->max_value != null) {
                                                        echo " &ndash;  $" . $price[$total]->max_value;
                                                    }
                                                } else {
                                                    echo "";
                                                }
                                                ?> (<?php echo count($products_pricings) ?> Vendors)
                                            </span>
                                        <?php } ?>
                                    </div>
                                    <div class="ratings__box">
                                        <?php
                                        $db_rating = floatval($product->average_rating);
                                        $ratings = $db_rating * 20;
                                        $user_count = 0;
                                        if ($product_review != null) {
                                            $user_count = count($product_review);
                                        }
                                        ?>
                                        <div class="ratings__wrapper show--qty aaa" data-raters="<?php echo $user_count; ?>">
                                            <div class="ratings">
                                                <div class="stars" data-rating="<?php echo $ratings; ?>" style="width:<?php echo $ratings; ?>%" ></div>
                                            </div>
                                        </div>
                                        <a class="link" href="#reviews">Read customer reviews</a>
                                    </div>
                                    <br>
                                    <div id="list_price" hidden><?php echo $retail_price; ?></div>
                                    <!-- <button class="btn btn--tertiary btn--s modal--toggle" data-target="#chooseVariationModal">More Options Available</button> -->
                                </div>
                            </div>
                        </div>
                    </div>
  <?php if (empty($inactive)){ ?>
  <!-- shows only on mobile -->
                     <div class="sidebar d-block d-sm-none">
                    <div class="sidebar__group">
                        <h4>Choose Qty:<?php // echo $regular_price; ?></h4>
                        <div class="row no--margin-l">
                            <input type="number" name="quantity" class="input input--qty not--empty width--50 sqty" min="1" value="1">
                            <input type="hidden" name="p_id" class="p_id" value="<?php echo $product->id; ?>">
                        </div>
                    </div>
                    <div class="sidebar__group">
                        <h4 class="mobile-center">Purchase from:</h4>
                        <div id="vendor_list_container" class="list__combo">
                            <ul class="list list--box has--btn">
                                <?php
                                // Debugger::debug($vendors);
                                if (!empty($vendors)) {
                                    for ($i = 0; $i < count($vendors); $i++) {
                                        // product prices / specials set
                                        $regular_price = (isset($vendors[$i]->price)) ? $vendors[$i]->price : 0.00;
                                        $retail_price = (isset($vendors[$i]->retail_price)) ? $vendors[$i]->retail_price : 0.00;
                                        if($regular_price == 0 && $retail_price != 0)
                                        {
                                            //swap values
                                            list($regular_price,$retail_price) = array($retail_price,$regular_price);
                                        }
                                        $clubPrice = false;
                                        $bcBestPrice = $bcModel->getBestPrice($product->id, $vendors[$i]->vendor_id, $bcPrices, $_SESSION['user_buying_clubs'], $retail_price);
                                        //$bcBestPrice = $bcModel->getBestPrice($vendors[$i], $bcPrices, $regular_price);
                                        if(!empty($bcBestPrice) && $bcBestPrice > 0){
                                            $regular_price = $bcBestPrice;
                                            $clubPrice = true;
                                        }
                                        $promo = ($retail_price > $regular_price && $retail_price != 0) ? 'has--promo' : null;
                                        ?>
                                        <!-- Vendor -->
                                        <li class="item">
                                            <div class="wrapper">
                                                <div class="wrapper__inner">
                                                    <label class="mdisplayblock control control__radio <?php echo ($vendors[$i]->promo_title != "") ? "has--promo" : "" ?>">
                                                        <input type="radio" name="vendor" class="vendor_id" id="vendor_id_<?php echo $i; ?>"  value="<?php echo $vendors[$i]->vendor_id; ?>" <?php echo $i == 0 ? 'checked' : ''; ?> >

                                                        <div class="control__indicator"></div>
                                                        <div class="control__text text__group">
                                                            <span class="line--main vendor_ratings "></span>
                                                            <?php if ($vendors[$i]->promo_title != "") { ?>
                                                                <span class="line--sub"><?php echo $vendors[$i]->promo_title; ?> </span>
                                                            <?php } else if($clubPrice){ ?>
                                                                <span class="line--sub"><?php echo $vendors[$i]->promo_title; ?> Club Price</span>
                                                            <?php } else { ?>
                                                                <span class="line--sub">Regular Price</span> <?php } ?>
                                                            <input type="hidden" name="vendor" class="v_id" id="vendor_id_<?php echo $i; ?>"  value="<?php  echo $vendors[$i]->vendor_id; ?>">
                                                        </div>
                                                    </label><?php echo $vendors[$i]->name; ?>
                                                </div>
                                                <div class="wrapper__inner align--right">
                                                    <div class="text__group">
                                                        <?php
                                                            if($retail_price > $regular_price && $retail_price != 0)
                                                            {
                                                            ?>
                                                            <span class="line--main has--promo<?php if($clubPrice){ echo 'club--price'; } ?>" id="vendor_price_<?php echo $vendors[$i]->vendor_id; ?>" data-price="<?php echo $regular_price; ?>" data-retail-price="<?php echo $retail_price; ?>">$<?php echo number_format($regular_price, 2); ?></span>
                                                        <?php } else { ?>
                                                            <span class="line--main" id="vendor_price_<?php echo $vendors[$i]->vendor_id; ?>" data-price="<?php echo $regular_price; ?>" data-retail-price="<?php echo $retail_price; ?>">$<?php echo number_format($regular_price, 2); ?></span>
                                                        <?php } ?>
                                                        <?php if ($vendors[$i]->policy_name != null) { ?>
                                                            <span class="line--sub"><?php echo $vendors[$i]->policy_name; ?></span>
                                                        <?php } elseif ($vendors[$i]->shipping_price != null) { ?>
                                                            <span class="line--sub">+<?php echo $vendors[$i]->shipping_price; ?> Shipping</span>
                                                        <?php } else { ?>
                                                            <span class="line--sub"><?php echo $vendors[$i]->shipping_price; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Vendor Info Popover -->
                                            <?php
                                            $users = unserialize(ROLES_USERS);
                                            if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $users))) {
                                                ?>
                                                <div class="popover popover--left align--center">
                                                    <div class="popover__inner">
                                                        <small>Get it by</small>
                                                        <span class="fontWeight--2 disp--block delivery_date"><?php echo (isset($vendors[$i]->delivery_time)) ? $vendors[$i]->delivery_time : "-" ?></span>
                                                    </div>
                                                    <div class="popover__bottom">
                                                        <div class="ratings__wrapper">
                                                            <a class="vendor_profile" href="<?php echo base_url(); ?>vendor-profile?id=<?php echo $vendors[$i]->id ?>">
                                                                <div class="ratings vendor_star">
                                                                    <div class='stars' data-rating="<?php echo ($vendors[$i]->average_rating * 20) ?>" style="width:<?php echo ($vendors[$i]->average_rating * 20) ?>%"></div>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <!-- /Vendor Info Popover -->
                                        </li>
                                        <!-- /Vendor -->
                                        <?php
                                    }
                                }
                                ?>
                            </ul>
                            <!--
                                NOTE: The button copy will change depending on whether the user has permission to purchase the item (i.e. Item doesn't require license, or it does and the user has a license):
                                It should only ever say "request" or "purchase"
                            -->
                            <?php
                            $tier_1_2_roles = unserialize(ROLES_TIER1_2);
                            $tier_3_users = unserialize(ROLES_TIER3);
                            $users = unserialize(ROLES_USERS);
                            if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $tier_1_2_roles)))) {
                                ?>
                                <button class="btn btn--primary btn--l btn--block modal--toggle add_single_cart"
                                        data-pid="<?php echo $curproductid; ?>" data-name="<?php echo $product->name; ?>"
                                        data-price="" data-license_required="<?php echo $product->license_required; ?>" data-procolor="" data-vendor_id=""
                                        data-target="#chooseLocationModal">Request/Purchase</button>
                                    <?php } elseif (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $tier_3_users)))) {
                                        ?>
                                <button class="btn btn--primary btn--l btn--block modal--toggle add_single_request"
                                        data-pid="<?php echo $curproductid; ?>"
                                        data-target="#chooseRequestListModal">Request/Purchase</button>
                                    <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                    <!-- Save to List -->
                    <!-- if ($_SESSION['role_id'] == '3' || $_SESSION['role_id'] == '4' || $_SESSION['role_id'] == '5' || $_SESSION['role_id'] == '6' || $_SESSION['role_id'] == '7' || $_SESSION['role_id'] == '8' || $_SESSION['role_id'] == '9' || $_SESSION['role_id'] == '10') {   ?> -->
                    <?php
                    $Shopping_list_users = unserialize(ROLES_SHOPPINGLISTS);
                    if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $Shopping_list_users))) {
                        ?>
                        <div class="sidebar__group align--center no--margin-t pull--up-l" style="position:relative; z-index:1;">
                            <div id="accountDropdown" class="link link--dropdown">
                                Save to Shopping List
                                <div class="popover fontSize--m" style="min-width:176px;">
                                    <div class="popover__inner" style="max-height:160px;">
                                        <ul class="list">
                                            <li class="item" >
                                                <?php
                                                if ($shopping_lists != null) {
                                                    for ($k = 0; $k < count($shopping_lists); $k++) {
                                                        ?>
                                                        <label class="control control__checkbox">
                                                            <input type="checkbox" name="checkboxRow" class="<?php echo ($shopping_lists[$k]->product_id == $product->id) ? " remove-shoppinglist-product " : " add-shoppinglist-product " ?>" <?php echo ($shopping_lists[$k]->product_id == $product->id) ? "checked" : "" ?> value="<?php echo $shopping_lists[$k]->id; ?>">
                                                            <div class="control__indicator">
                                                            </div> <?php echo $shopping_lists[$k]->listname; ?>
                                                            <br>
                                                        </label>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    No List Found
                                                <?php } ?>
                                            </li>
                                        </ul>
                                    </div>
                                    <?php
                                    if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $Shopping_list_users)))) {
                                        ?>
                                        <div class="popover__bottom fontSize--s fontWeight--2">
                                            <a class="link modal--toggle add-new-list get_locations" style="transform:translateY(-2px);" data-toggle="modal" data-target="#shoppingListModal">+ Create New List</a>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <!-- /Save to List -->
                    <!-- Choose Variations -->
                    <!-- <div class="sidebar__group">
                        <h4>Choose from other variations:</h4>
                        <button class="btn btn--tertiary btn--m btn--block">3 Options Available</button>
                    </div> -->
                    <!-- /Choose Variations -->
                </div>
                    <!-- /Product Info -->
                    <hr>
                    <!-- Product Description -->
                     <?php if(!empty($product->description)): ?>
                    <div id="description">
                        <h3 class="title textColor--dark-gray">
                            <svg class="icon icon--details"><use xlink:href="#icon-details"></use></svg>
                            Product Description
                        </h3>
                        <p><?php echo nl2br($product->description); ?></p>
                        <!--  <ul class="list list--bulleted">
                             <li>Can be cured in the mouth</li>
                             <li>Minimizes distortion</li>
                             <li>Ensures fit of the denture</li>
                             <li>Low exothermic heat</li>
                             <li>110°F at 2mm thickness</li>
                             <li>Increases patient comfort</li>
                             <li>Dense, stable and color-fast</li>
                             <li>Easy to finish and polish</li>
                             <li>Can be used to extend denture borders and posterior palatal seal</li>
                         </ul> -->
                    </div>
                    <hr>
                     <?php endif; ?>
                    <!-- /Product Description -->
                    <!-- Special Offers & Promotions -->
                     <?php if($vendors != null && $vendors[0]->promo_title != null): ?>
                    <div id="promos">
                        <h3 class="title textColor--dark-gray">
                            <svg class="icon icon--promo"><use xlink:href="#icon-promo"></use></svg>
                            Special Offers &amp; Promotions
                        </h3>
                        <ul class="list list--bulleted">
                            <?php
                            if ($vendors != null) {
                                for ($i = 0; $i < count($vendors); $i++) {
                                    if ($vendors[$i]->promo_title != null && $vendors[$i]->promo_title != "") {
                                        if ($vendors[$i]->promo_instructions != null && $vendors[$i]->promo_instructions != "") {
                                            ?>
                                            <li>
                                                <span class="textColor--accent fontWeight--2"><?php echo $vendors[$i]->promo_title; ?></span> when purchasing from <?php echo $vendors[$i]->name; ?> &nbsp; <a class="link modal--toggle viewpromo" data-rules="<?php echo $vendors[$i]->promo_instructions; ?>" data-target="#viewRestrctionsModal">Read Restrictions</a>
                                            </li>
                                        <?php } else {
                                            ?>
                                            <li>
                                                <span class="textColor--accent fontWeight--2"><?php echo $vendors[$i]->promo_title; ?></span> when purchasing from <?php echo $vendors[$i]->name; ?>.
                                            </li>
                                            <?php
                                        }
                                    }
                                }
                            } else {
                                echo "No Special Offers &amp; Promotions Available ";
                            }
                            ?>
                        </ul>
                    </div>
                    <hr>
                     <?php endif; ?>
                    <!-- /Special Offers & Promotions -->
                    <!-- Product Details -->
                    <div id="details">
                        <h3 class="title textColor--dark-gray">
                            <svg class="icon icon--info"><use xlink:href="#icon-info"></use></svg>
                            Product Details
                        </h3>
                        <table class="table table--horizontal table--align-lr">
                            <tbody>
                                <?php if ($product->manufacturer != null) { ?>
                                    <tr>
                                        <td width="40%">Manufacturer</td>
                                        <td width="60%"><?php echo $product->manufacturer; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->mpn)) { ?>
                                    <tr>
                                        <td>MPN</td>
                                        <td><?php echo $product->mpn; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->license_required)) { ?>
                                    <tr>
                                        <td>License Required</td>
                                        <td><?php echo (strtolower($product->license_required) == "yes") ? "Required" : "Not Required"; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->quantity_per_box)) { ?>
                                    <tr>
                                        <td>Quantity/Box</td>
                                        <td><?php echo $product->quantity_per_box; ?></td>
                                    </tr>
                                <?php } ?>
                                    <?php if(!empty($product->returnable)) { ?>
                                    <tr>
                                        <td>Returnable</td>
                                        <td>
                                            <?php echo $product->returnable; ?>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                <?php if (!empty($product->weight)) { ?>
                                    <tr>
                                        <td>Weight</td>
                                        <td><?php echo $product->weight; ?> <?php echo $product->weight_type; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->contents)) { ?>
                                    <tr>
                                        <td>Contents</td>
                                        <td><?php echo $product->contents; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->msds_location)) { ?>
                                    <tr>
                                        <td>MSDS</td>
                                        <td>
                                            <?php
                                            if (!empty($product->msds_location)) {
                                                $msds = $product->msds_location;
                                                ;
                                                $msds_location = explode(" ", $msds);
                                                for ($i = 0; $i < count($msds_location); $i++) {
                                                    ?>
                                                    <a class="link" href="<?php echo $msds_location[$i] ?>" target="_blank">MSDS
                                                    </a><br />
                                                <?php } ?>
                                                <?php
                                            } else {
                                                echo "";
                                            }
                                            ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->size)) { ?>
                                    <tr>
                                        <td>Size</td>
                                        <td><?php echo $product->size; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->color)) { ?>
                                    <tr>
                                        <td>Color</td>
                                        <td><?php echo $product->color; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->fluoride)) { ?>
                                    <tr>
                                        <td>Fluoride</td>
                                        <td><?php echo $product->fluoride; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->flavor)) { ?>
                                    <tr>
                                        <td>Flavor</td>
                                        <td><?php echo $product->flavor; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->shade)) { ?>
                                    <tr>
                                        <td>Shade</td>
                                        <td><?php echo $product->shade; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->grit)) { ?>
                                    <tr>
                                        <td>Grit</td>
                                        <td><?php echo $product->grit; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->set_rate)) { ?>
                                    <tr>
                                        <td>Set Rate</td>
                                        <td><?php echo $product->set_rate; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->viscosity)) { ?>
                                    <tr>
                                        <td>Viscosity</td>
                                        <td><?php echo $product->viscosity; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->firmness)) { ?>
                                    <tr>
                                        <td>Firmness</td>
                                        <td><?php echo $product->firmness; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->grit)) { ?>
                                    <tr>
                                        <td>Grit</td>
                                        <td><?php echo $product->grit; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->tip_finish)) { ?>
                                    <tr>
                                        <td>Tip Finish</td>
                                        <td><?php echo $product->tip_finish; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->tip_diameter)) { ?>
                                    <tr>
                                        <td>Tip Diameter</td>
                                        <td><?php echo $product->tip_diameter; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->tip_material)) { ?>
                                    <tr>
                                        <td>Tip Material</td>
                                        <td><?php echo $product->tip_material; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->head_diameter)) { ?>
                                    <tr>
                                        <td>Head Diameter</td>
                                        <td><?php echo $product->head_diameter; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->head_length)) { ?>
                                    <tr>
                                        <td>Head Length</td>
                                        <td><?php echo $product->head_length; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->diameter)) { ?>
                                    <tr>
                                        <td>Diameter</td>
                                        <td><?php echo $product->diameter; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->category_code)) { ?>
                                    <tr>
                                        <td>Category Code</td>
                                        <td><?php echo $product->category_code; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->arch)) { ?>
                                    <tr>
                                        <td>Arch</td>
                                        <td><?php echo $product->arch; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->shaft_dimensions)) { ?>
                                    <tr>
                                        <td>Shaft Dimensions</td>
                                        <td><?php echo $product->shaft_dimensions; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->shaft_description)) { ?>
                                    <tr>
                                        <td>Shaft Description</td>
                                        <td><?php echo $product->shaft_description; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->blade_description)) { ?>
                                    <tr>
                                        <td>Blade Description</td>
                                        <td><?php echo $product->blade_description; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->anatomic_use)) { ?>
                                    <tr>
                                        <td>Anatomic Use</td>
                                        <td><?php echo $product->anatomic_use; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->instrument_description)) { ?>
                                    <tr>
                                        <td>Instrument Description</td>
                                        <td><?php echo $product->instrument_description; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->palm_thickness)) { ?>
                                    <tr>
                                        <td>Palm Thickness</td>
                                        <td><?php echo $product->palm_thickness; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->finger_thickness)) { ?>
                                    <tr>
                                        <td>Finger Thickness</td>
                                        <td><?php echo $product->finger_thickness; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->texture)) { ?>
                                    <tr>
                                        <td>Texture</td>
                                        <td><?php echo $product->texture; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->delivery_system)) { ?>
                                    <tr>
                                        <td>Delivery System</td>
                                        <td><?php echo $product->delivery_system; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->volume)) { ?>
                                    <tr>
                                        <td>Volume</td>
                                        <td><?php echo $product->volume; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->dimensions)) { ?>
                                    <tr>
                                        <td>Dimensions</td>
                                        <td><?php echo $product->dimensions; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->handle_size)) { ?>
                                    <tr>
                                        <td>Handle Size</td>
                                        <td><?php echo $product->handle_size; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->handle_finish)) { ?>
                                    <tr>
                                        <td>Handle Finish</td>
                                        <td><?php echo $product->handle_finish; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->stone_type)) { ?>
                                    <tr>
                                        <td>Stone Type</td>
                                        <td><?php echo $product->stone_type; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->stone_separation_time)) { ?>
                                    <tr>
                                        <td>Stone Separation Time</td>
                                        <td><?php echo $product->stone_separation_time; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->setting_time)) { ?>
                                    <tr>
                                        <td>Setting Time</td>
                                        <td><?php echo $product->setting_time; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->band_thickness)) { ?>
                                    <tr>
                                        <td>Band Thickness</td>
                                        <td><?php echo $product->band_thickness; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($product->tax_per_state)) { ?>
                                    <tr>
                                        <td>Tax Per State</td>
                                        <td><?php echo $product->tax_per_state; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php
                                if (!empty($custom_fields)) {
                                    for ($i = 0; $i < count($custom_fields); $i++) {
                                        ?>
                                        <?php if (!empty($custom_fields[$i]->field) && !empty($custom_fields[$i]->value)) { ?>
                                            <tr>
                                                <td><?php echo $custom_fields[$i]->field; ?></td>
                                                <td><?php echo $custom_fields[$i]->value; ?></td>
                                            </tr>
                                        <?php } ?>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /Product Details -->
                    <!-- Questions & Answers -->
                     <?php if (count($product_qstn) > 0) { ?>
                    <div id="qa">
                        <div class="row">
                            <div class="col col--4-of-8 col--am">
                                <h3 class="title textColor--dark-gray">
                                    <svg class="icon icon--questions"><use xlink:href="#icon-questions"></use></svg>
                                    Questions &amp; Answers
                                </h3>
                            </div>
                            <?php if (isset($_SESSION["user_id"])) { ?>
                                <div class="col col--4-of-8 col--am align--right">
                                    <button class="btn btn--primary btn--s modal--toggle questions" data-id="<?php echo $product->id; ?>" data-target="#askQuestionModal">Ask a Question</button>
                                </div>
                            <?php } ?>
                        </div>
                        <?php if (count($product_qstn) > 0) { ?>
                            <!-- /Single Q&A Block (Multiple Answers) -->
                            <div class="qa">
                                <!-- Question -->
                                <?php for ($i = 0; $i < count($product_qstn); $i++) { ?>
                                    <div class="question row fontWeight--2">
                                        <div class="col col--1-of-8">
                                            <span class="fontSize--s">Question</span>
                                        </div>
                                        <div class="col col--7-of-8">
                                            <?php echo ucfirst($product_qstn[$i]->question); ?>
                                            <?php if (($product_qstn[$i]->answers) != null) { ?>
                                                <?php if (isset($_SESSION["user_id"]) && $_SESSION['user_id'] != $product_qstn[$i]->asked_by) { ?>
                                                    <a class="link fontSize--s margin--xs no--margin-tb no--margin-r modal--toggle answer_this" data-qsn="<?php echo $product_qstn[$i]->question; ?>"  data-id="<?php echo $product_qstn[$i]->id; ?>"  data-target="#answerQuestionModal">Answer this.</a>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <?php if (isset($_SESSION["user_id"]) && $_SESSION['user_id'] != $product_qstn[$i]->asked_by) { ?>
                                                    <a class="link fontSize--s fontWeight--2 margin--xs no--margin-tb no--margin-r modal--toggle answer_this" data-qsn="<?php echo $product_qstn[$i]->question; ?>"  data-id="<?php echo $product_qstn[$i]->id; ?>" data-target="#answerQuestionModal">Be the first to answer this.</a>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <!-- /Question -->
                                    <!-- /Top Answer -->
                                    <?php
                                    if ($product_qstn[$i]->answers != null) {
                                        $answer_total = count($product_qstn[$i]->answers);
                                        if ($answer_total > 0) {
                                            $answer_total = ($answer_total > 1) ? 1 : $answer_total;
                                            for ($j = 0; $j < $answer_total; $j++) {
                                                ?>
                                                <div class="answers__top row">
                                                    <div class="col col--1-of-8">
                                                        <span class="fontSize--s fontWeight--2">Top Answer</span>
                                                    </div>
                                                    <div class="col col--7-of-8">
                                                        <div class="answer">
                                                            <?php
                                                            print_r($product_qstn[$i]->answers[$j]->answer);
                                                            ?>
                                                            <span class="voting__meta">
                                                                <?php if ($product_qstn[$i]->answers[$j]->upvotes != 0) { ?>
                                                                    <span class="fontWeight--2">
                                                                        <?php
                                                                        print_r($product_qstn[$i]->answers[$j]->upvotes);
                                                                        ?>
                                                                    </span>
                                                                    people found <span class="fontWeight--2"></span> answer helpful.
                                                                <?php } else { ?>  <?php } ?>
                                                                <?php
                                                                $roles = unserialize(ROLES_USERS);
                                                                $vendor_roles = unserialize(ROLES_VENDORS);
                                                                if (isset($_SESSION["user_id"])) {
                                                                    if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles)) || in_array($_SESSION['role_id'], $vendor_roles)) {
                                                                        ?>
                                                                        Was this helpful to you?
                                                                        <ul class="voting__links list list--inline">
                                                                            <li><a class="link answer_upvote" data-id="<?php print_r($product_qstn[$i]->answers[$j]->id); ?>" href="#">Yes</a></li>
                                                                            <li><a class="link answer_downvote" data-id="<?php print_r($product_qstn[$i]->answers[$j]->id); ?>" href="#">No</a></li>
                                                                            <li>|</li>
                                                                            <li><a class="link modal--toggle answer_flag" data-question="<?php echo $product_qstn[$i]->question; ?>" data-answer="<?php print_r($product_qstn[$i]->answers[$j]->answer); ?>" data-answer_id="<?php print_r($product_qstn[$i]->answers[$j]->id); ?>" data-p_name="<?php echo $product->name; ?>"  data-p_id="<?php echo $product->id; ?>" data-target="#flagAnswerModal">Flag It</a></li>
                                                                        </ul>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        } else {
                                            echo "Not answered yet.";
                                        }
                                        ?>
                                        <div  style="display:none; background-color: #FAFBFC; padding: 15px;border-radius:5px; border: 1px solid #E7EAF1;" id="more_answers_<?php echo $product_qstn[$i]->id ?>">
                                            <div class="answers__top row">
                                                <div class="col col--1-of-8">
                                                    <span class="fontSize--s fontWeight--2">Other Answers</span>
                                                </div>
                                                <?php for ($j = 1; $j < count($product_qstn[$i]->answers); $j++) { ?>
                                                    <?php if ($j > 1) { ?>
                                                        <div class="col col--1-of-8">
                                                        </div>
                                                    <?php } ?>
                                                    <div class="col col--7-of-8">
                                                        <div class="answer" style="border-bottom:1px dashed #E7EAF1; padding-bottom: 10px;padding-top: 5px;">
                                                            <?php print_r($product_qstn[$i]->answers[$j]->answer); ?>
                                                            <span class="voting__meta">
                                                                <?php if ($product_qstn[$i]->answers[$j]->upvotes != 0) { ?>
                                                                    <span class="fontWeight--2">
                                                                        <?php
                                                                        print_r($product_qstn[$i]->answers[$j]->upvotes);
                                                                        ?>
                                                                    </span>
                                                                    people found <span class="fontWeight--2"></span> answer helpful.
                                                                <?php } else { ?>  <?php } ?>
                                                                <?php
                                                                if (isset($_SESSION["user_id"])) {
                                                                    if ($_SESSION['role_id'] == '3' || $_SESSION['role_id'] == '4' || $_SESSION['role_id'] == '6' || $_SESSION['role_id'] == '7' || $_SESSION['role_id'] == '8' || $_SESSION['role_id'] == '9' || $_SESSION['role_id'] == '10' || $_SESSION['role_id'] == '11') {
                                                                        ?>
                                                                        Was this helpful to you?
                                                                        <ul class="voting__links list list--inline">
                                                                            <li><a class="link answer_upvote" data-id="<?php print_r($product_qstn[$i]->answers[$j]->id); ?>" href="#">Yes</a></li>
                                                                            <li><a class="link answer_downvote" data-id="<?php print_r($product_qstn[$i]->answers[$j]->id); ?>" href="#">No</a></li>
                                                                            <li>|</li>
                                                                            <li><a class="link modal--toggle answer_flag" data-question="<?php echo $product_qstn[$i]->question; ?>" data-answer="<?php print_r($product_qstn[$i]->answers[$j]->answer); ?>" data-answer_id="<?php print_r($product_qstn[$i]->answers[$j]->id); ?>" data-p_id="<?php echo $product->id; ?>"  data-target="#flagAnswerModal">Flag It</a></li>
                                                                        </ul>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <?php
                                            if (isset($_SESSION["user_id"])) {
                                                if ($_SESSION['role_id'] == '3' || $_SESSION['role_id'] == '4' || $_SESSION['role_id'] == '5' || $_SESSION['role_id'] == '6' || $_SESSION['role_id'] == '7' || $_SESSION['role_id'] == '8' || $_SESSION['role_id'] == '9' || $_SESSION['role_id'] == '10' || $_SESSION['role_id'] == '11') {
                                                    ?>
                                                    <div class="answers__top row" style="padding-top:16px;">
                                                        <div class="col col--1-of-8">
                                                            <span class="fontSize--s fontWeight--2"></span>
                                                        </div>
                                                        <div class="col col--7-of-8">
                                                            <form method="post" name="" action="<?php echo base_url(); ?>add-answer-customer">
                                                                <div class="answers__top row">
                                                                    <div class="col col--7-of-8">
                                                                        <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                                                                        <textarea name="answerQuestion" class="input input--m " id="answerQuestionValue" placeholder="Help out the Matix community by answering this question..." required></textarea>
                                                                    </div>
                                                                    <div class="col col--1-of-8" style="padding-top:25px;">
                                                                        <input type="hidden" name="question_id" value="<?php echo $product_qstn[$i]->id ?>">
                                                                        <button class="btn btn--tertiary btn--block btn--s no--pad-lr" type="submit">Submit</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                    <?php } ?>
                                    <?php if (($product_qstn[$i]->answers != null) && count($product_qstn[$i]->answers > 1)) { ?>
                                        <a onclick="$('#more_answers_<?php echo $product_qstn[$i]->id ?>').toggle('show')" class="link link--expand" href="javascript:void(0);">(+) Show (<?php echo (count($product_qstn[$i]->answers) - 1) ?>) more answers</a>
                                    <?php } ?>
                                <?php } ?>
                                <!-- /Top Answer -->
                                <!-- Other Answers -->
                                <!--                              <div class="answers__other row">
                                                                 <div class="col col--1-of-8">
                                                                     <span class="fontSize--s fontWeight--2">Other Answers</span>
                                                                 </div>
                                                                 <div class="col col--7-of-8">
                                                                     <ul class="list list--answers">
                                                                         <li class="answer">
                                                                             Yes... Obviously it is totally lead free. People haven’t put lead in mouth products since 1973. This was bad and you should feel bad.
                                                                             <span class="voting__meta">
                                                                                 <span class="fontWeight--2">213</span>
                                                                                 people found <span class="fontWeight--2">Fuller McCallister's</span> answer helpful. Was this helpful to you?
                                                                                 <ul class="voting__links list list--inline">
                                                                                     <li><a class="link" href="">Yes</a></li>
                                                                                     <li><a class="link" href="">No</a></li>
                                                                                     <li>|</li>
                                                                                     <li><a class="link modal--toggle" data-target="#flagReviewModal">Flag It</a></li>
                                                                                 </ul>
                                                                             </span>
                                                                         </li>
                                                                         <li class="answer">
                                                                             Yes... Obviously it is totally lead free. People haven’t put lead in mouth products since 1973. This was bad and you should feel bad.
                                                                             <span class="voting__meta">
                                                                                 <span class="fontWeight--2">213</span>
                                                                                 people found <span class="fontWeight--2">Fuller McCallister's</span> answer helpful. Was this helpful to you?
                                                                                 <ul class="voting__links list list--inline">
                                                                                     <li><a class="link" href="">Yes</a></li>
                                                                                     <li><a class="link" href="">No</a></li>
                                                                                     <li>|</li>
                                                                                     <li><a class="link modal--toggle" data-target="#flagReviewModal">Flag It</a></li>
                                                                                 </ul>
                                                                             </span>
                                                                         </li>
                                                                         <li class="answer">
                                                                             <div class="col col--7-of-8 col--am no--pad-l">
                                                                                 <textarea class="input input--m" placeholder="Help out the Matix community by answering this question..."></textarea>
                                                                             </div>
                                                                             <div class="col col--1-of-8 col--am">
                                                                                 <button class="btn btn--tertiary btn--block btn--s no--pad-lr">Submit</button>
                                                                             </div>
                                                                         </li>
                                                                     </ul>
                                                                 </div>
                                                             </div>   -->
                                <!--  <a class="link link--expand fontSize--s fontWeight--2 align--right" href="javascript:;" data-target=".qa">(+) Show 2 more answers</a> -->
                                <!-- /Other Answers -->
                            </div>
                            <!-- /Single Q&A Block (Multiple Answers) -->
                            <!-- /Single Q&A Block (No Answer) -->
                        <?php } else { ?> <p>No Results found</p> <?php } ?>
                    </div>
                    <hr>
                      <?php } ?>
                    <!-- /Questions & Answers -->
                    <!-- Ratings & Reviews -->

                     <?php
            $total=count($five_star)+count($four_star)+count($three_star)+count($two_star)+count($one_star);
         $avg=    5*count($five_star)+4*count($four_star)+3*count($three_star)+2*count($two_star)+1*count($one_star)/$total;
                            ?>
                            <?php if($avg > 0): ?>
                    <div id="reviews">
                        <div class="heading__group wrapper">
                            <div class="wrapper__inner">
                                <h3 >
                                    <svg class="icon icon--star-outline"><use xlink:href="#icon-star-outline"></use></svg>
                                    Ratings &amp; Reviews
                                </h3>
                            </div>
                            <div class="wrapper__inner align--right">
                                <?php if (isset($_SESSION["user_id"]) && ($_SESSION['role_id']!=5)) { ?>
                                    <button class="btn btn--primary btn--s modal--toggle add-rating" data-id="<?php echo $product->id; ?>" data-target="#productReviewModal">Write a Review</button>
                                <?php } ?>
                            </div>
                        </div>
                        <ul class="list list--ratings list--inline list--divided padding--m no--pad-lr no--pad-t rating-div d-none">

                            <li class="item">
                                <h5><?php echo count($five_star); ?> rated it:</h5>
                                <div class="ratings__wrapper has--title">
                                    <div class="ratings">
                                        <div class="stars" data-rating="100"></div>
                                    </div>
                                </div>
                            </li>
                            <li class="item">
                                <h5><?php echo count($four_star); ?> rated it:</h5>
                                <div class="ratings__wrapper has--title">
                                    <div class="ratings">
                                        <div class="stars" data-rating="80"></div>
                                    </div>
                                </div>
                            </li>
                            <li class="item">
                                <h5><?php echo count($three_star); ?> rated it:</h5>
                                <div class="ratings__wrapper has--title">
                                    <div class="ratings">
                                        <div class="stars" data-rating="60"></div>
                                    </div>
                                </div>
                            </li>
                            <li class="item">
                                <h5><?php echo count($two_star); ?> rated it:</h5>
                                <div class="ratings__wrapper has--title">
                                    <div class="ratings">
                                        <div class="stars" data-rating="40"></div>
                                    </div>
                                </div>
                            </li>
                            <li class="item">
                                <h5><?php echo count($one_star); ?> rated it:</h5>
                                <div class="ratings__wrapper has--title">
                                    <div class="ratings">
                                        <div class="stars" data-rating="20"></div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                         <div class="text-center"><h3><b><a href="javascript:void(0)" class="link avgrating">Average Rating:</a></b></h3></div>
                            <div class="row">
                                <div style="pointer-events: none" id="avgrating" start="<?=$avg?>"></div>
                            </div>
                        <div class="text-center"><h4><b><?=$avg?> From <?=$total?></b></h4></div>

                         <?php if ($product_review != null) { ?>
                        <div class="reviews__all">
                            <?php if ($product_review != null) { ?>
                                <div class="padding--xs no--pad-lr border--1 border--solid border--light border--tb">
                                    <div class="wrapper">
                                        <div class="wrapper__inner">
                                            <h4 class="no--margin">All Reviews</h4>
                                        </div>
                                        <div class="wrapper__inner align--right">
                                            <input type="hidden" name="" class="product_id" value="<?php echo $product->id; ?>">
                                            <div class="select select--text">
                                                <label class="label">Sort by</label>
                                                <select aria-label="Select a Sorting Option" class="view_product">
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
                                <!-- Single Review -->
                                <?php
                                $total_review = count($product_review);
                                $total_review = ($total_review > 1) ? 1 : $total_review;
                                for ($i = 0; $i < $total_review; $i++) {
                                    $originalDate = $product_review[$i]->updated_at;
                                    $newDate = date("F j, Y", strtotime($originalDate));
                                    $db_rating = floatval($product_review[$i]->rating);
                                    $ratings = $db_rating * 20;
                                    ?>
                                    <div class="review">
                                        <h3 class="title"><?php echo ucfirst($product_review[$i]->title); ?></h3>
                                        <span class="review__meta">
                                            Reviewed by <?php echo ($product_review[$i]->users!=null)? $product_review[$i]->users->first_name: "" ?> on <?php echo $newDate; ?>
                                            <div class="ratings__wrapper ratings--s">
                                                <div class="ratings">
                                                    <div class="stars" data-rating="<?php echo $ratings; ?>" style="width: <?php echo $ratings; ?>%"></div>
                                                </div>
                                            </div>
                                        </span>
                                        <p class="review__text">
                                            <?php echo ucfirst($product_review[$i]->comment); ?>
                                        </p>
                                        <span class="voting__meta">
                                            <span class="fontWeight--2"><?php echo $product_review[$i]->upvotes; ?></span>
                                            <?php if ($product_review[$i]->upvotes != null) { ?>
                                                people found this helpful.
                                            <?php } else { ?> <?php } ?>
                                                        <?php
                                                        $roles = unserialize(ROLES_USERS);
                                                        $vendor_roles = unserialize(ROLES_VENDORS);
                                                        if (isset($_SESSION['user_id'])) {
                                                            if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $roles)) || in_array($_SESSION['role_id'], $vendor_roles)) {
                                                                ?>
                                                    Was this helpful to you?
                                                    <ul class="voting__links list list--inline">
                                                        <li><a class="link review_upvote" data-id="<?php echo $product_review[$i]->id; ?>" href="#">Yes</a></li>
                                                        <li><a class="link review_downvote" data-id="<?php echo $product_review[$i]->id; ?>" href="#">No</a></li>
                                                        <li>|</li>
                                                        <li><a class="link modal--toggle review_flag" data-review_id="<?php echo $product_review[$i]->id; ?>"
                                                               data-review="<?php echo $product_review[$i]->title; ?>" data-comment="<?php print_r($product_review[$i]->comment); ?>" data-p_name="<?php echo $product->name; ?>" data-pro_id="<?php echo $product->id; ?>"
                                                               data-target="#flagReviewModal">Flag It</a></li>
                                                    </ul>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </span>
                                    </div>
                                <?php } ?>
                                <!-- /Single Review -->

                                <div id="more_reviews" style="display:none;">
                                    <?php
                                    for ($i = 1; $i < count($product_review); $i++) {
                                        $originalDate = $product_review[$i]->updated_at;
                                        $newDate = date("F j, Y", strtotime($originalDate));
                                        $db_rating = floatval($product_review[$i]->rating);
                                        $ratings = $db_rating * 20;
                                        ?>
                                        <div class="review">
                                            <h3 class="title"><?php echo ucfirst($product_review[$i]->title); ?></h3>
                                            <span class="review__meta">
                                                Reviewed by <?php echo $product_review[$i]->users->first_name ?> on <?php echo $newDate; ?>
                                                <div class="ratings__wrapper ratings--s">
                                                    <div class="ratings">
                                                        <div class="stars" data-rating="<?php echo $ratings; ?>" style="width: <?php echo $ratings; ?>%"></div>
                                                    </div>
                                                </div>
                                            </span>
                                            <p class="review__text">
                                                <?php echo ucfirst($product_review[$i]->comment); ?>
                                            </p>
                                            <span class="voting__meta">
                                                <span class="fontWeight--2"><?php echo $product_review[$i]->upvotes; ?></span>
                                                <?php if ($product_review[$i]->upvotes != null) { ?>
                                                    people found this helpful.
                                                <?php } else { ?> <?php } ?>
                                                <?php
                                                if (isset($_SESSION['user_id'])) {
                                                    if ($_SESSION['role_id'] == '3' || $_SESSION['role_id'] == '4' || $_SESSION['role_id'] == '6' || $_SESSION['role_id'] == '7' || $_SESSION['role_id'] == '8' || $_SESSION['role_id'] == '9' || $_SESSION['role_id'] == '10') {
                                                        ?>
                                                        Was this helpful to you?
                                                        <ul class="voting__links list list--inline">
                                                            <li><a class="link review_upvote" data-id="<?php echo $product_review[$i]->id; ?>" href="#">Yes</a></li>
                                                            <li><a class="link review_downvote" data-id="<?php echo $product_review[$i]->id; ?>" href="#">No</a></li>
                                                            <li>|</li>
                                                            <li><a class="link modal--toggle review_flag"
                                                                   data-review="<?php echo $product_review[$i]->title; ?>"  data-review_id="<?php echo $product_review[$i]->id; ?>" data-comment="<?php print_r($product_review[$i]->comment); ?>" data-p_name="<?php echo $product->name; ?>" data-pro_id="<?php echo $product->id; ?>"
                                                                   data-target="#flagReviewModal">Flag It</a></li>
                                                        </ul>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </span>
                                        </div>
                                    <?php } ?>
                                </div>
                                <a onclick="$('#more_reviews').toggle('show')" class="link link--expand" href="javascript:void(0);">(+) Show (<?php echo (count($product_review) - 1) ?>) more reviews</a>
                            <?php } else { ?> <p>No Results Found </p><?php } ?>
                        </div>
                         <?php } ?>
                    </div>
                      <?php endif; ?>
                    <!-- /Ratings & Reviews -->
                </div>
                <!-- /Content Area -->
                <!-- Sidebar -->
                <?php if (empty($inactive)){ ?>
                <!-- hidden on mobile -->
                <div class="sidebar col-md-4 col-xs-12  d-none d-sm-block">
                    <div class="sidebar__group">
                        <h4>Choose Qty:<?php // echo $regular_price; ?></h4>
                        <div class="row no--margin-l">
                            <input type="number" name="quantity" class="input input--qty not--empty width--50 sqty" min="1" value="1">
                            <input type="hidden" name="p_id" class="p_id" value="<?php echo $product->id; ?>">
                        </div>
                    </div>
                    <div class="sidebar__group">
                        <h4>Purchase from:</h4>
                        <div id="vendor_list_container" class="list__combo">
                            <ul class="list list--box has--btn">
                                <?php
                                // Debugger::debug($vendors);
                                if (!empty($vendors)) {
                                    for ($i = 0; $i < count($vendors); $i++) {
                                        // product prices / specials set
                                        // Debugger::debug($vendors[$i]);
                                        $regular_price = (isset($vendors[$i]->price)) ? $vendors[$i]->price : 0.00;
                                        $retail_price = (isset($vendors[$i]->retail_price)) ? $vendors[$i]->retail_price : 0.00;
                                        if($regular_price == 0 && $retail_price != 0)
                                        {
                                            //swap values
                                            list($regular_price,$retail_price) = array($retail_price,$regular_price);
                                        }
                                        $clubPrice = false;
                                        $bcBestPrice = $bcModel->getBestPrice($product->id, $vendors[$i]->vendor_id, $bcPrices, $_SESSION['user_buying_clubs'], $retail_price);
                                        //$bcBestPrice = $bcModel->getBestPrice($vendors[$i], $bcPrices, $regular_price);
                                        if(!empty($bcBestPrice) && $bcBestPrice > 0){
                                            $regular_price = $bcBestPrice;
                                            $clubPrice = true;
                                        }
                                        $promo = ($retail_price > $regular_price && $retail_price != 0) ? 'has--promo' : null;
                                        // Debugger::debug($vendors);
                                        ?>
                                        <!-- Vendor -->
                                        <li class="item">
                                            <div class="wrapper">
                                                <div class="wrapper__inner">
                                                    <label class="control control__radio mdisplayblock <?php echo ($vendors[$i]->promo_title != "") ? "has--promo" : "" ?>">
                                                        <input type="radio" name="vendor" class="vendor_id" id="vendor_id_<?php echo $i; ?>"  value="<?php echo $vendors[$i]->vendor_id; ?>" <?php echo $i == 0 ? 'checked' : ''; ?> >

                                                        <div class="control__indicator"></div>
                                                        <div class="control__text text__group">
                                                            <span class="line--main vendor_ratings "><?php echo $vendors[$i]->name; ?></span>
                                                            <?php if ($vendors[$i]->promo_title != "") { ?>
                                                                <span class="line--sub"><?php echo $vendors[$i]->promo_title; ?> </span>
                                                            <?php } else if($clubPrice){ ?>
                                                                <span class="line--sub"><?php echo $vendors[$i]->promo_title; ?> Club Price</span>
                                                            <?php } else { ?>
                                                                <span class="line--sub">Regular Price</span> <?php } ?>
                                                            <input type="hidden" name="vendor" class="v_id" id="vendor_id_<?php echo $i; ?>"  value="<?php echo $vendors[$i]->vendor_id; ?>">
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="wrapper__inner align--right">
                                                    <div class="text__group">
                                                        <?php
                                                            if($retail_price > $regular_price && $retail_price != 0)
                                                            {
                                                            ?>
                                                            <span class="line--main has--promo<?php if($clubPrice){ echo 'club--price'; } ?>" id="vendor_price_<?php echo $vendors[$i]->vendor_id; ?>" data-price="<?php echo $regular_price; ?>" data-retail-price="<?php echo $retail_price; ?>">$<?php echo number_format($regular_price, 2); ?></span>
                                                        <?php } else { ?>
                                                            <span class="line--main" id="vendor_price_<?php echo $vendors[$i]->vendor_id; ?>" data-price="<?php echo $regular_price; ?>" data-retail-price="<?php echo $retail_price; ?>">$<?php echo number_format($regular_price, 2); ?></span>
                                                        <?php } ?>
                                                        <?php if ($vendors[$i]->policy_name != null) { ?>
                                                            <span class="line--sub"><?php echo $vendors[$i]->policy_name; ?></span>
                                                        <?php } elseif ($vendors[$i]->shipping_price != null) { ?>
                                                            <span class="line--sub">+<?php echo $vendors[$i]->shipping_price; ?> Shipping</span>
                                                        <?php } else { ?>
                                                            <span class="line--sub"><?php echo $vendors[$i]->shipping_price; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Vendor Info Popover -->
                                            <?php
                                            $users = unserialize(ROLES_USERS);
                                            if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $users))) {
                                                ?>
                                                <div class="popover popover--left align--center">
                                                    <div class="popover__inner">
                                                        <small>Get it by</small>
                                                        <span class="fontWeight--2 disp--block delivery_date"><?php echo (isset($vendors[$i]->delivery_time)) ? $vendors[$i]->delivery_time : "-" ?></span>
                                                    </div>
                                                    <div class="popover__bottom">
                                                        <div class="ratings__wrapper">
                                                            <a class="vendor_profile" href="<?php echo base_url(); ?>vendor-profile?id=<?php echo $vendors[$i]->id ?>">
                                                                <div class="ratings vendor_star">
                                                                    <div class='stars' data-rating="<?php echo ($vendors[$i]->average_rating * 20) ?>" style="width:<?php echo ($vendors[$i]->average_rating * 20) ?>%"></div>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <!-- /Vendor Info Popover -->
                                        </li>
                                        <!-- /Vendor -->
                                        <?php
                                    }
                                }
                                ?>
                            </ul>
                            <!--
                                NOTE: The button copy will change depending on whether the user has permission to purchase the item (i.e. Item doesn't require license, or it does and the user has a license):
                                It should only ever say "request" or "purchase"
                            -->
                            <?php
                            $tier_1_2_roles = unserialize(ROLES_TIER1_2);
                            $tier_3_users = unserialize(ROLES_TIER3);
                            $users = unserialize(ROLES_USERS);
                            if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $tier_1_2_roles)))) {
                                ?>
                                <button class="btn btn--primary btn--l btn--block modal--toggle add_single_cart"
                                        data-pid="<?php echo $curproductid; ?>" data-name="<?php echo $product->name; ?>"
                                        data-price="" data-license_required="<?php echo $product->license_required; ?>" data-procolor="" data-vendor_id=""
                                        data-target="#chooseLocationModal">Request/Purchase</button>
                                    <?php } elseif (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $tier_3_users)))) {
                                        ?>
                                <button class="btn btn--primary btn--l btn--block modal--toggle add_single_request"
                                        data-pid="<?php echo $curproductid; ?>"
                                        data-target="#chooseRequestListModal">Request/Purchase</button>
                                    <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                    <!-- Save to List -->
                    <!-- if ($_SESSION['role_id'] == '3' || $_SESSION['role_id'] == '4' || $_SESSION['role_id'] == '5' || $_SESSION['role_id'] == '6' || $_SESSION['role_id'] == '7' || $_SESSION['role_id'] == '8' || $_SESSION['role_id'] == '9' || $_SESSION['role_id'] == '10') {   ?> -->
                    <?php
                    $Shopping_list_users = unserialize(ROLES_SHOPPINGLISTS);
                    if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $Shopping_list_users))) {
                        ?>
                        <div class="sidebar__group align--center no--margin-t pull--up-l" style="position:relative; z-index:1;">
                            <div id="accountDropdown" class="link link--dropdown">
                                Save to Shopping List
                                <div class="popover fontSize--m" style="min-width:176px;">
                                    <div class="popover__inner" style="max-height:160px;">
                                        <ul class="list">
                                            <li class="item" >
                                                <?php
                                                if ($shopping_lists != null) {
                                                    for ($k = 0; $k < count($shopping_lists); $k++) {
                                                        ?>
                                                        <label class="control control__checkbox">
                                                            <input type="checkbox" name="checkboxRow" class="<?php echo ($shopping_lists[$k]->product_id == $product->id) ? " remove-shoppinglist-product " : " add-shoppinglist-product " ?>" <?php echo ($shopping_lists[$k]->product_id == $product->id) ? "checked" : "" ?> value="<?php echo $shopping_lists[$k]->id; ?>">
                                                            <div class="control__indicator">
                                                            </div> <?php echo $shopping_lists[$k]->listname; ?>
                                                            <br>
                                                        </label>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    No List Found
                                                <?php } ?>
                                            </li>
                                        </ul>
                                    </div>
                                    <?php
                                    if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $Shopping_list_users)))) {
                                        ?>
                                        <div class="popover__bottom fontSize--s fontWeight--2">
                                            <a class="link modal--toggle add-new-list get_locations" style="transform:translateY(-2px);" data-target="#shoppingListModal">+ Create New List</a>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <!-- /Save to List -->
                    <!-- Choose Variations -->
                    <!-- <div class="sidebar__group">
                        <h4>Choose from other variations:</h4>
                        <button class="btn btn--tertiary btn--m btn--block">3 Options Available</button>
                    </div> -->
                    <!-- /Choose Variations -->
                </div>
                <!-- /Sidebar -->
            </div>


        </div>
    </section>
</div>
<!-- /Content Section -->
<!-- View Image Modal -->
<div id="viewImageModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <div class="flexslider">
                <ul class="slides">
                    <?php if ($main_image != null) { ?>
                        <li data-thumb="<?php echo image_url(); ?>uploads/products/images/<?php echo $main_image->photo; ?>">
                            <img src="<?php echo image_url(); ?>uploads/products/images/<?php echo $main_image->photo; ?>" />
                        </li>
                    <?php } ?>
                    <?php if ($sub_image != null) { ?>
                        <?php for ($i = 0; $i < count($sub_image); $i++) { ?>
                            <li data-thumb="<?php echo image_url(); ?>uploads/products/images/<?php echo $sub_image[$i]->photo; ?>">
                                <img src="<?php echo image_url(); ?>uploads/products/images/<?php echo $sub_image[$i]->photo; ?>" />
                            </li>
                        <?php } ?>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /View Image Modal -->
      <?php if(!empty($related_products)) { ?>
<div class="container">
      <!-- Related carousel -->
       <h3 class="title textColor--dark-gray">
                            <svg class="icon icon--details"><use xlink:href="#icon-details"></use></svg>
                            Related Products
                        </h3>
      <?php if(!empty($related_products)) { ?>
            <div id="productsowl" class=" owl-carousel owl-theme">
                  <?php foreach ($related_products as $relatedProduct){
                        if ($relatedProduct->images != null) {
                            $imgurl= image_url().'uploads/products/images/'.$relatedProduct->images->photo;
                        } else {
                            $imgurl= image_url().'assets/img/product-image.png';
                        }
                     //price
                    $regular_price = (isset($relatedProduct->product_price->price)) ? $relatedProduct->product_price->price : 0.00;
                    $retail_price = (isset($relatedProduct->product_price->retail_price)) ? $relatedProduct->product_price->retail_price : 0.00;
                    if($regular_price == 0 && $retail_price != 0) {
                        list($regular_price,$retail_price) = array($retail_price,$regular_price);
                    }
                    $bcPrices = $bcModel->getBuyingClubPrices($_SESSION['user_buying_clubs'], [$relatedProduct->id]);
                    // Debugger::debug($bcPrices, '$bcPrices');
                    $clubPrice = false;
                    $bcBestPrice = $bcModel->getBestPrice($relatedProduct->id, $relatedProduct->vendor_id, $bcPrices, $_SESSION['user_buying_clubs'], $retail_price);
                    // Debugger::debug($retail_price . '-' . $bcBestPrice, 'bcbestprice');
                    if(!empty($bcBestPrice) && $bcBestPrice > 0){
                        $regular_price = $bcBestPrice;
                        $clubPrice = true;
                    }
                    $price="";
                    if($retail_price > $regular_price) {
                       $price= $retail_price;
                    }
                    else{
                       $price= $regular_price;
                    }

                  ?>
                    <div class="item item-box ralated-productbox" style="">
                            <div class="item-img" >
                                <a href="<?=base_url();?>view-product?id=<?=$relatedProduct->id; ?>&category=category=<?=$category_id?>">
                                    <img style="max-height: 215px;min-height: 150px" src="<?=$imgurl?>" alt="Restorative">
                                </a>
                            </div>
                            <div class="product__vendor-range text-center"><?=$relatedProduct->name; ?>
                                  <span class="product__mfr">
                                                by <a class="link fontWeight--2" href="#"><?php echo $relatedProduct->manufacturer; ?></a>
                                            </span>
                            </div>

                            <h4 class="text-center">$ <?=$price?></h4>
                            <div class="product__vendor-range text-center">
                                 <?php
                            $p_count = count($relatedProduct->price);
                            if (isset($relatedProduct->price[0])) {
                                if ($relatedProduct->price[0]->min_value != null) {
                                    echo "$" . $relatedProduct->price[0]->min_value;
                                } elseif ($relatedProduct->price[0]->minprice_value != null) {
                                    echo "$" . $relatedProduct->price[0]->minprice_value;
                                } else {
                                    echo "$" . $relatedProduct->price[0]->max_value;
                                }
                            }
                            if ($p_count > 1) {
                                $total = $p_count - 1;
                                if ($relatedProduct->price[$total]->max_value != null) {
                                    echo " &ndash;  $" . $relatedProduct->price[$total]->max_value;
                                }
                            } else {
                                echo "";
                            }
                            ?> (<?php echo $relatedProduct->vendor_total[0]->v_total; ?> Vendors)
                            </div>

                    </div>
                      <?php } ?>
            </div>
            <?php } ?>

                    <!-- end carousel -->
</div>
 <?php } ?>
<br><br><br>
  <!-- Button to Open the Modal -->


<style type="text/css">
    .rapStar{text-shadow: none;}
    label.control.control__checkbox {
    display: block;
}
</style>
  <script>

$(document).ready(function(){
        (function($){
    $.fn.jsRapStar = function(options){

        return this.each(function(){
            this.opt = $.extend({
                star:'&#9733',
                colorFront:'yellow',
                colorBack:'white',
                enabled:true,
                step:true,
                starHeight:32,
                length:6,
                onClick:null,
                onMousemove:null,
                onMouseleave:null
            },options);
            var base = this;
            var starH = Array(this.opt.length + 1).join('<span>' + this.opt.star + '</span>');
            this.StarB = $(this).addClass('rapStar').css({color:this.opt.colorBack,'font-size':this.opt.starHeight + 'px'}).html(starH);
            var start = parseFloat($(this).attr('start'));
            var sw = this.StarB.width() / this.opt.length;
            var aw = start * sw;
            this.StarF = $('<div>').addClass('rapStarFront').css({color:this.opt.colorFront}).html(starH).width(aw).appendTo(this);
            if(this.opt.enabled){
                $(this).bind({
                    mousemove:function(e){
                        e.preventDefault();
                        var relativeX = e.clientX - $(base)[0].getBoundingClientRect().left;
                        var e = Math.floor(relativeX / sw) + 1;
                        if(base.opt.step) newWidth = e * sw;
                        else newWidth = relativeX;
                        this.StarF.width(newWidth);
                        if(base.opt.onMousemove)
                            base.opt.onMousemove.call(base,newWidth / sw);
                    },
                    mouseleave:function(e){
                        this.StarF.width(aw);
                        if(base.opt.onMouseleave)
                            base.opt.onMouseleave.call(base,start);
                    },
                    click:function(e){
                        e.preventDefault();
                        aw = newWidth;
                        this.StarF.width(newWidth);
                        start = newWidth / sw;
                        if(base.opt.onClick)
                            base.opt.onClick.call(base,start);
                    }
                });
            }else
                $(this).addClass('rapStarDisable');
        })
    }
})(jQuery);

$('#avgrating').jsRapStar({colorFront:'#FFBC00',length:5,starHeight:28,step:false});
    $(document).on('click','.avgrating',function(){
    $('.rating-div').removeClass('d-none');
    });
});
        $('#productsowl').owlCarousel({
            loop:false,
            margin:10,
            nav:true,
            dots:true,
            slideSpeed: 10,
            autoplay:true,
            autoplayHoverPause:true,
            responsive:{
                0:{
                    items:1
                },
                460:{
                    items:1
                },
                600:{
                    items:3,
                    slideBy: 1
                },
                1000:{
                    items:3,
                    slideBy: 1
                },
                1100:{
                    items:5,
                    slideBy: 1
                }
            }
        })
    </script>

<script>
(function ($){
    $(document).ready(function() {
      if (window.history && window.history.pushState) {
        window.history.pushState('forward', null, './#forward');
        $(window).on('popstate', function() {
          alert('Back button was pressed.');
        });
      }
    });
});
if(window.location.href.search("#") != -1)
    window.location.href = "/home?category=<?=$category_id?>";
</script>
<script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css"/>
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css"/>
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script type="text/javascript">
$('#searchResults').slick({
    dots: true,
    infinite: true,
    slidesToShow: 4,
    slidesToScroll: 4,
    arrows: true
});
</script>
<style type="text/css">
.slick-next:before, .slick-prev:before {
    color: #2893ff;
}
</style>

<? //php include(INCLUDE_PATH.'/_inc/shared/modals/view-image.php');  ?>
    <?=$this->load->view('templates/_inc/shared/modals/ask-question.php') ?>
    <?=$this->load->view('templates/_inc/shared/modals/answer-question.php') ?>
    <?=$this->load->view('templates/_inc/shared/modals/choose-location.php') ?>
    <?=$this->load->view('templates/_inc/shared/modals/product-review.php') ?>
    <?=$this->load->view('templates/_inc/shared/modals/flag-review.php') ?>
    <?=$this->load->view('templates/_inc/shared/modals/answer-flag.php') ?>
    <?=$this->load->view('templates/_inc/shared/modals/shopping-list.php') ?>
    <?=$this->load->view('templates/_inc/shared/modals/add-new-license.php') ?>
    <?=$this->load->view('templates/_inc/shared/modals/choose-variation.php') ?>
    <?=$this->load->view('templates/_inc/shared/modals/veiw-promo-restrictions.php') ?>
    <?=$this->load->view('templates/_inc/shared/modals/choose-request-list.php') ?>


<!-- <?php include(INCLUDE_PATH . '/_inc/shared/modals/ask-question.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/answer-question.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/choose-location.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/product-review.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/flag-review.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/answer-flag.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/shopping-list.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/add-new-license.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/choose-variation.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/veiw-promo-restrictions.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/choose-request-list.php'); ?>
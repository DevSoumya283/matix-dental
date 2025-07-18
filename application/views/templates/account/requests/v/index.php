
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
                    Manage Request Lists
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breadcrumbs Bar -->

    <!-- Main Content -->
    <section class="content__wrapper wrapper--fixed has--sidebar-l sidebar--no-fill">
        <div class="content__main">
            <div class="row row--full-height">

                <!-- Sidebar -->
                <div class="sidebar col-md-4 mobile-center col-xs-12 bg--white padding--l no--pad-l">
                    <!-- Request List Info -->
                    <div class="sidebar__group">
                        <h3>Request Lists</h3>
                        <p class="no--margin-tb">View the items that have been requested by non-purchasing users for a selected location.</p>
                    </div>
                    <!-- /Request List Info -->

                    <!-- Location Tabs -->
                    <div class="sidebar__group">
                        <div class="tab__group is--vertical" data-target="#locationContent">
                            <?php
                            $tier_1_2_roles = unserialize(ROLES_TIER1_2);
                            if ($request_locations != null) {
                                for ($i = 0; $i < count($request_locations); $i++) {
                                    ?>
                                    <label class="tab state--toggle has--badge" value="" data-badge="<?php
                                    if (isset($request_locations[$i]->item_count) && $request_locations[$i]->item_count >0) {
                                        echo $request_locations[$i]->item_count;
                                    } else {
                                        echo "-";
                                    }
                                    ?>" onclick="location.href = '<?php echo base_url() ?>request-products?id=<?php echo $request_locations[$i]->id; ?>'">
                                        <input type="radio" name="locationTabs" <?php
                                        if ($view_id == $request_locations[$i]->id) {
                                            echo "checked";
                                        }
                                        ?> value="<?php echo $request_locations[$i]->id; ?>" >
                                        <span><a class="link select_location"> <?php echo $request_locations[$i]->nickname; ?></a></span>
                                    </label>

                                    <?php
                                }
                            } else {
                                echo "";
                            }
                            ?>
                        </div>
                    </div>
                    <!-- /Location Tabs -->
                </div>
                <!-- /Sidebar -->

                <!-- Content -->
                <div id="locationContent" class="content col-md-8 col-xs-12">

                    <div class="page__tab">
                        <?php
                        if ($request_product != null) {
                            ?>
                            <div id="controlsRequests" class="contextual__controls wrapper__inner align--right">
                                <button class="btn btn--tertiary btn--s contextual--hide email_urgent_request" data-id="<?php echo $locationName->id; ?>" style="display: inline-block;" data-id="<?php echo $locationName->id; ?>">Email Urgent Request</button>
                            </div>
                            <br>
                            <div class="heading__group border--dashed padding--s no--pad-lr no--pad-t wrapper">
                                <div class="wrapper__inner">
                                    <h4>
                                        <?php
                                        echo "Items Requested for " . $locationName->nickname;
                                        ?>
                                    </h4>
                                </div>
                                <?php
                                foreach ($request_product as $row) {
                                    $locat_id = $row->location_id;
                                }
                                ?>

                                <div id="controlsRequests" class="contextual__controls wrapper__inner align--right">
                                    <?php
                                    //$tier_1_2_roles = unserialize (ROLES_TIER1_2);
                                    if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $tier_1_2_roles)))) {
                                        ?>
                                        <button class="btn btn--tertiary btn--s contextual--hide move_all_to_cart" data-id="<?php echo $locationName->id; ?>" style="display: inline-block;">Move All Items to Cart</button>
                                        <button class="btn btn--primary btn--s is--contextual is--off modal--toggle selected_list" data-target="#addSelectionsToCartModal">Move Selections to Cart</button>
                                    <?php } ?>
                                    <ul class="list list--inline fontWeight--2 fontSize--s margin--xs no--margin-tb no--margin-r is--contextual is--off">
                                        <li class="item">
                                            <?php
                                            foreach ($request_product as $row) {
                                                $locat_id = $row->location_id;
                                            }
                                            ?>
                                            <a class="link modal--toggle deletelocationrequest_products" data-id="" data-target="#removeSelectedItemsModal">Remove</a>
                                        </li>
                                    </ul>
                                </div>

                            </div>

                            <table class="table" data-controls="#controlsRequests">
                                <thead>
                                    <tr>
                                        <th width="5%">
                                            <label class="control control__checkbox">
                                                <input type="checkbox" class=" is--selector" id="selectAll">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </th>
                                        <th width="65%">Product
                                        </th>
                                        <th width="15%">Quantity
                                        </th>
                                        <th width="25%" class="dn">&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Requested Item -->
                                    <?php
                                    for ($i = 0; $i < count($request_product); $i++) {
                                        ?>
                                        <?php if ($request_product[$i]->product != null) { ?>
                                            <tr>
                                                <td>
                                                    <label class="control control__checkbox" id="general-content">
                                                        <input type="checkbox" name="checkboxRow" class="singleCheckbox rl-item" value="<?php echo $request_product[$i]->id; ?>">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </td>

                                                <td>
                                                    <!-- Product -->
                                                    <div class="product product--s row multi--vendor req--license padding--xxs">
                                                        <div class="product__image col col--2-of-8 col--am dn">
                                                            <a href="<?php echo base_url(); ?>view-product?id=<?php echo $request_product[$i]->product_id; ?>">
                                                            <?php
                                                            if ($request_product[$i]->images != null) {
                                                                ?>
                                                                <div class="product__thumb" style="background-image:url('<?php echo image_url(); ?>uploads/products/images/<?php echo $request_product[$i]->images->photo; ?>');">
                                                                <?php } else { ?>
                                                                    <div class="product__thumb" style="background-image:url('<?php echo image_url(); ?>assets/img/product-image.png');">
                                       <!-- <div class="avatar avatar--s" style="background-image:url('<?php //echo base_url();    ?>assets/img/ph-avatar.jpg');"></div> -->
                                                                    <?php } ?>
                                                                </div>
                                                            </a>
                                                            </div>

                                                            <div class="product__data col col--6-of-8 col--am">


                                                                <input type="hidden" name="l_id" class="l_id" value="<?php echo $locat_id; ?>">
                                                                <input type="hidden" name="request_ids" id="user_id" class="request_ids" value="">
                                                                <input type="hidden" name="locationName"  class="locationName" value="<?php echo $locationName->nickname; ?>">

                                                                <a class="link" href="<?php echo base_url(); ?>view-product?id=<?php echo $request_product[$i]->product_id; ?>">
                                                                    <?php
                                                                    if ($request_product[$i]->product->license_required == 'Yes') {
                                                                        echo "<span class='product__name'>";
                                                                        echo $request_product[$i]->product->name;
                                                                        ?></span>
                                                                        <?php
                                                                    } else {
                                                                        echo "<span>";
                                                                        echo $request_product[$i]->product->name;
                                                                        ?></span>
                                                                    <?php } ?>
                                                                </a>

                                                                <span class="product__mfr">
                                                                    <?php if ($request_product[$i]->product != null) { ?>
                                                                        by <a class="link fontWeight--2" href="#">
                                                                            <?php echo $request_product[$i]->product->manufacturer; ?>
                                                                        </a>
                                                                    <?php } ?>
                                                                </span>
                                                                <?php
                                                                    $strikethrough = null;
                                                                    // Debugger::debug($request_product[$i]);
                                                                    if(!empty($_SESSION['user_buying_clubs'])){
                                                                        $clubPrice = $bcModel->getBestPrice($request_product[$i]->product->id, $request_product[$i]->product_pricing->vendor_id, $bcPrices, $_SESSION['user_buying_clubs'], $request_product[$i]->product_pricing->retail_price);
                                                                        Debugger::debug($clubPrice, 'Club price');
                                                                        if(!empty($clubPrice)){
                                                                            $request_product[$i]->product_pricing->retail_price  = $clubPrice;
                                                                        }
                                                                    }
                                                                ?>
                                                                    <?php

                                                                    // Debugger::debug($request_product[$i]);
                                                                    if($request_product[$i]->product_pricing->retail_price < $request_product[$i]->product_pricing->price && $request_product[$i]->product_pricing->retail_price > 0) {
                                                                        //output price with special
                                                                        ?>
                                                                        <li style="font-size: 22px;font-weight: bold;text-decoration:line-through;">$<?php  echo $request_product[$i]->product_pricing->price;  ?></li>
                                                                        <li style="font-size: 22px;font-weight: bold;color:#13C4A3;" <?php if(!empty($clubPrice)) echo 'class="club--price"'; ?>>$<?php echo number_format($request_product[$i]->product_pricing->retail_price, 2); ?></li>

                                                                        <?php
                                                                    } else {
                                                                        //output normal price
                                                                        ?>
                                                                        <li style="font-size: 22px;font-weight: bold;" <?php if(!empty($clubPrice)){ echo 'class="club--price"'; }?>>$<?php echo $request_product[$i]->product_pricing->price; ?></li>

                                                                        <?php
                                                                    }
                                                                    ?>
<!--
                                                                <!-- </span> -->
                                                                <span class="fontSize--s">
                                                                    (<?php echo $request_product[$i]->vendor->name; ?>)
                                                                    <a class="link fontWeight--2 fontSize--xs modal--toggle change_vendor" data-list_id="<?php echo $request_product[$i]->id; ?>" data-product_id="<?php echo $request_product[$i]->product_id; ?>" data-vendor_id="<?php echo $request_product[$i]->vendor->id; ?>" data-target="#changeVendorModal">Change</a></span>
                                                            </div>
                                                        </div>
                                                        <!-- /Product -->
                                                </td>

                                                <td>
                                                    <?php if(!empty($request_product[$i]->inventory->purchashed_qty)){
                                                        echo 'Stock: ' . $request_product[$i]->inventory->purchashed_qty;
                                                    } ?>
                                                    <br><br>
                                                    <input type="number" class="input input--qty width--100 r_quantity update_rqty" min="1" value="<?php echo $request_product[$i]->quantity; ?>" >
                                                    <input type="hidden" class="request_id"  value="<?php echo $request_product[$i]->id; ?>" >
                                                     <div class="d-block d-sm-none mt-2">
                                                           <?php if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $tier_1_2_roles))) && ($request_product[$i]->product->license_required != 'Yes' || $hasLicense)) { ?>
                                                        <button class="btn btn--s btn--primary btn--icon pull--down-xxs modal--toggle request_list single" data-lname="<?php echo $locationName->nickname; ?>" data-request_id="<?php echo $request_product[$i]->id; ?>" data-p_id="<?php echo $request_product[$i]->product_id; ?>" data-target="#addSelectionsToCartModal"><svg class="icon icon--cart-s"><use xlink:href="#icon-cart-s"></use></svg></button>
                                                    <?php } ?>
                                                    <button class="modal--toggle btn btn--s btn--secondary btn--icon pull--down-xxs remove-rquest" data-rid="<?php echo $request_product[$i]->id; ?>" data-target="#removeRequestItemsModal"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></button>
                                                     </div>
                                                </td>
                                                <td class="align--center dn">
                                                    <?php if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $tier_1_2_roles))) && ($request_product[$i]->product->license_required != 'Yes' || $hasLicense)) { ?>
                                                        <button class="btn btn--s btn--primary btn--icon pull--down-xxs modal--toggle request_list single" data-lname="<?php echo $locationName->nickname; ?>" data-request_id="<?php echo $request_product[$i]->id; ?>" data-p_id="<?php echo $request_product[$i]->product_id; ?>" data-target="#addSelectionsToCartModal"><svg class="icon icon--cart-s"><use xlink:href="#icon-cart-s"></use></svg></button>
                                                    <?php } ?>
                                                    <button class="modal--toggle btn btn--s btn--secondary btn--icon pull--down-xxs remove-rquest" data-rid="<?php echo $request_product[$i]->id; ?>" data-target="#removeRequestItemsModal"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></button>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php
                                    }
                                } else {
                                    ?> <tr>

                                        <!-- Empty State -->
                                <div class="well align--center">
                                    <p>
                                        No items have been requested for this location.
                                    </p>
                                    <button class="btn btn--primary btn--m btn--dir is--next is--link" data-target="<?php echo base_url('home'); ?>">Start Shopping</button>
                                </div>
                                <!-- /Empty State -->
                        </div>
                        </tr>
                    <?php } ?>
                    <!-- /Requested Item -->
                    </tbody>
                    </table>
                    <?php
                    if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $tier_1_2_roles)))) {
                        if ($activity != null) {
                            ?>
                            <div class="well">

                                <div class="heading__group border--dashed wrapper">
                                    <div class="wrapper__inner">
                                        <h4>Recent Activity</h4>
                                    </div>
                                </div>
                                <ul class="list list--activity fontSize--s">
                                    <?php
                                    for ($i = 0; $i < count($activity); $i++) {

                                        $date = $activity[$i]->updated_at;
                                        $up_date = date("d. M ,Y", strtotime($date));
                                        ?>

                                        <li class="item">
                                            <div class="entity__group">
                                                <?php if ($activity[$i]->images != null) { ?>
                                                    <div class="avatar avatar--xs" style="background-image:url('<?php echo image_url(); ?>uploads/user/profile/<?php echo $activity[$i]->images->photo; ?>');"></div>
                                                <?php } else { ?>
                                                    <div class="avatar avatar--xs" style="background-image:url('<?php echo image_url(); ?>assets/img/avatar-default.png');"></div>
                                                <?php } ?>

                                                <span class="fontWeight--2"><?php echo $activity[$i]->users->first_name; ?></span>
                                                <?php
                                                if ($activity[$i]->action == 'moved item to') {
                                                    echo 'moved'
                                                    ?>
                                                    <span class="fontWeight--2">
                                                        <a href="l/view-product?id=<?php echo $activity[$i]->products->id; ?>" class="link"><?php echo (isset($activity[$i]->products)) ? $activity[$i]->products->name : ""; ?></a>
                                                    </span>
                                                    to cart
                                                    <?php
                                                } else if ($activity[$i]->action == 'moved item from') {
                                                    echo 'added '
                                                    ?>
                                                    <span class="fontWeight--2">
                                                        <a href="/view-product?id=<?php echo $activity[$i]->products->id; ?>" class="link"><?php echo (isset($activity[$i]->products)) ? $activity[$i]->products->name : ""; ?></a>
                                                    </span>
                                                    from cart
                                                    <?php
                                                } else {
                                                    echo $activity[$i]->action;
                                                    ?>
                                                    <span class="fontWeight--2">
                                                        <a href="/view-product?id=<?php echo $activity[$i]->products->id; ?>" class="link"><?php echo (isset($activity[$i]->products)) ? $activity[$i]->products->name : ""; ?></a>
                                                    </span>
                                                <?php } ?>
                                                <span class="fontSize--xs textColor--dark-gray"> <?php echo $up_date ?> </span>
                                            </div>
                                        </li>
                                    <?php } ?>
                                    <!--  <li class="item">
                                         <div class="entity__group">
                                             <div class="avatar avatar--xs" style="background-image:url('<?php //echo base_url();    ?>assets/img/ph-avatar.jpg');"></div>
                                             <span class="fontWeight--2">Kevin McCallister</span> removed <span class="fontWeight--2">Some Other Product</span> <span class="fontSize--xs textColor--dark-gray">3 hours ago</span>
                                         </div>
                                     </li>
                                     <li class="item">
                                         <div class="entity__group">
                                             <div class="avatar avatar--xs" style="background-image:url('<?php //echo base_url();   ?>assets/img/ph-avatar.jpg');"></div>
                                             <span class="fontWeight--2">Kevin McCallister</span> removed <span class="fontWeight--2">Osung PBWPBW Impression Tray with Wing (Nickel) Partial, PB</span> <span class="fontSize--xs textColor--dark-gray">3 days ago</span>
                                         </div>
                                     </li> -->
                                </ul>

                            </div>
                        <?php
                        }
                    }
                    ?>

                </div>
                <!-- /Content -->

            </div>
        </div>
    </section>
    <!-- /Main Content -->

</div>
<!-- /Content Section -->

<!-- Modals -->


<?php $this->load->view('templates/_inc/shared/modals/change-item-vendor.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/remove-items.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/remove-request-item.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/add-selected-items.php'); ?>
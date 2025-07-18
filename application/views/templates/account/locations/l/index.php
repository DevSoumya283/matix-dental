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
                <li class="item">
                    <a class="link" href="<?php echo base_url('locations'); ?>">Manage Locations</a>
                </li>
                <li class="item is--active">
                    <?php echo $location_name->nickname; ?>
                </li>
            </ul>
        </div>
    </div>
    <?php
    $tier_1_2ab = unserialize(ROLES_TIER1_2_AB);
    $tier_1_2_roles = unserialize(ROLES_TIER1_2);
    $tier1 = unserialize(ROLES_TIER1);
    ?>
    <!-- /Breadcrumbs Bar -->
    <!-- Main Content -->
    <section class="content__wrapper wrapper--fixed has--sidebar-l sidebar--no-fill">
        <div class="content__main">
            <div class="row row--full-height">
                <!-- Sidebar -->
                <div class="sidebar col-md-3 bg--white mobile-center">
                    <!-- Location Info -->
                    <div class="sidebar__group" style="padding-right:32px;">
                        <h3> <?php echo $location_name->nickname; ?></h3>
                        <div class="location__map card margin--s no--margin-lr locationmapdetail" style="max-height:120px;" data-address="<?php echo $location_name->address1; ?>,<?php echo $location_name->zip; ?>"></div>
                        <p class="no--margin-tb"><?php echo $location_name->address1; ?><br><?php echo $location_name->state; ?>, <?php echo $location_name->zip; ?></p>
                        <?php if ($_SESSION['role_id'] == '3' || $_SESSION['role_id'] == '7') { ?>
                            <a class="link fontSize--s fontWeight--2" href="javascript:void(0)" onclick="$('#location_settings').click();">Make Changes</a>
                        <?php } ?>
                    </div>
                    <!-- /Location Info -->
                    <!-- Location Tabs -->
                    <div class="sidebar__group mobile-center">
                        <div class="tab__group is--vertical" data-target="#locationContent">
                            <label class="tab state--toggle" value="details">
                                <input type="radio" name="locationTabs" checked>
                                <span><a class="link">Location Details</a></span>
                            </label>
                            <?php
                            foreach ($request as $key) {
                                $list_count += $key->quantity;
                            }
                                if ($list_count > 0) {
                                    ?>
                                    <label class="tab state--toggle has--badge" value="requests" data-badge="<?php echo $list_count; ?>" value="requests" id="requests">
                                        <input type="radio" name="locationTabs">
                                        <span><a class="link" id="request">Request List</a></span>
                                    </label>
                                    <?php
                                } else {
                                    ?>
                                    <label class="tab state--toggle has--badge" value="requests" data-badge="0" value="requests" id="requests">
                                        <input type="radio" name="locationTabs">
                                        <span><a class="link" id="request">Request List</a></span>
                                    </label>
                                    <?php
                                }
                            ?>
                            <label class="tab state--toggle" value="inventory">
                                <input type="radio" name="locationTabs">
                                <span><a class="link" id="inventory">Inventory</a></span>
                            </label>
                            <label class="tab state--toggle" value="users">
                                <input type="radio" name="locationTabs">
                                <span><a class="link" id="users">Manage Users</a></span>
                            </label>
                            <?php if ($_SESSION['role_id'] == '3' || $_SESSION['role_id'] == '7') { ?>
                                <label class="tab state--toggle" value="settings">
                                    <input type="radio" name="locationTabs">
                                    <span><a class="link" id="location_settings" >Settings</a></span>
                                </label>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- /Location Tabs -->
                </div>
                <!-- /Sidebar -->
                <!-- Content -->
                <div id="locationContent" class="content col-md-9">
                    <div id="tabDetails" class="page__tab">
                        <!-- Location Stats -->
                        <div class="well no--margin-t">
                            <ul class="list list--inline list--divided list--stats">
                                <li class="item item--stat">
                                    <?php
                                    if (isset($cart_count)) {
                                        ?>
                                        <button class="btn btn--tertiary btn--m btn--icon btn--circle has--badge is--link" data-badge="<?php echo $cart_count; ?>" data-target="<?php echo base_url(); ?>cart?id=<?php echo$location_name->id; ?>"><!-- templates/cart --><svg class="icon icon--cart-s"><use xlink:href="#icon-cart-s"></use></svg></button>
                                        <?php
                                    } else {
                                        ?>
                                        <button class="btn btn--tertiary btn--m btn--icon btn--circle has--badge is--link" data-badge="0" data-target="#"><svg class="icon icon--cart-s"><use xlink:href="#icon-cart-s"></use></svg></button>
                                    <?php }
                                    ?>
                                </li>
                                <li class="item item--stat">
                                    <div class="text__group">
                                        <span class="line--main">
                                            <?php
                                            foreach ($orders as $key) {
                                                echo number_format($key['order_total'], 2, '.', ',');
                                            }
                                            ?>
                                        </span>
                                        <span class="line--sub">Total Spend</span>
                                        <div class="select select--text">
                                            <select aria-label="Select a Range">
                                                <option selected>MTD</option>
                                                <option value="1">YTD</option>
                                            </select>
                                        </div>
                                    </div>
                                </li>
                                <li class="item item--stat">
                                    <div class="text__group">
                                        <span class="line--main">
                                            <?php
                                            echo $list_count . " Items";
                                            ?>
                                        </span>
                                        <span class="line--sub">
                                            <a class="link"  href="javascript:void(0)" onclick="$('#requests').click();" data-target="#requests">View Requests</a>
                                        </span>
                                    </div>
                                </li>
                                <li class="item item--stat">
                                    <div class="text__group">
                                        <span class="line--main">
                                            <?php echo count($inventory); ?> Items</span>
                                        <span class="line--sub">
                                            <a class="link"  href="javascript:void(1)" onclick="$('#inventory').click();" data-target="#inventory">Manage Inventory</a>
                                        </span>
                                    </div>
                                </li>
                                <li class="item item--stat">
                                    <div class="text__group">
                                        <span class="line--main">
                                            <?php
                                            foreach ($no_of_users as $key) {
                                                echo $key['user_count'] . " Users";
                                            }
                                            ?>
                                        </span>
                                        <span class="line--sub">
                                            <a class="link"  href="javascript:void(2)" onclick="$('#users').click();" data-target="#users">Manage Users</a>
                                        </span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <br>
                        <br>
                        <!-- Orders -->
                        <div class="heading__group border--dashed padding--s no--pad-lr no--pad-t">
                            <div class="wrapper">
                                <div class="wrapper__inner">
                                    <h3 class="selecteditems">Order History</h3>
                                </div>
                                <div class="wrapper__inner align--right">
                                    <ul class="list list--inline list--divided">
                                        <li class="item">
                                            <div class="select select--text">
                                                <label class="label">Showing</label>
                                                <select aria-label="Select a Sorting Option">
                                                    <option value="30">Last 30 Days</option>
                                                    <option value="90">Last 3 Months</option>
                                                    <option value="180">Last 6 Months</option>
                                                    <option value="365">Last Year</option>
                                                </select>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- Location  based Orders -->
                        <div class="well bg--lightest-gray" style="max-height:480px;">
                            <!-- Single Order -->
                            <?php
                            if ($order != null) {
                                for ($i = 0; $i < count($order); $i++) {
                                    $date = $order[$i]->created_at;
                                    $today = date('M. j, Y', strtotime($date));
                                    if ($order[$i]->order_status == 'Shipped' || $order[$i]->order_status == 'Delivered') {
                                        ?>
                                        <div class="order well card is--pos">
                                        <?php } else { ?>
                                            <div class="order well card ">
                                            <?php } ?>
                                            <div class="heading__group wrapper border--dashed padding--s no--pad-lr no--pad-t">
                                                <div class="wrapper__inner">
                                                    <h4 class="textColor--darkest-gray">Order <?php echo $order[$i]->id; ?></h4>
                                                </div>
                                                <div class="wrapper__inner align--right">
                                                    <ul class="list list--inline list--divided fontWeight--2 fontSize--s">
                                                        <?php if ($order[$i]->order_status != 'Cancelled' && $order[$i]->order_status != 'Shipped') { ?>
                                                            <li class="item">
                                                                <a class="link modal--toggle cancel-order" data-id="<?php echo $order[$i]->id; ?>" data-target="#orderCancellationModal">Cancel</a>
                                                            </li>
                                                        <?php } ?>
                                                        <li class="item">
                                                            <button class="btn btn--s btn--tertiary is--link" data-target="<?php echo base_url(); ?>view-orders?id=<?php echo $order[$i]->id; ?>">View Order</button>
                                                            <!-- templates/vendor-admin/orders/o/number -->
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="order__info col-md-10 col-xs-12">
                                                    <ul class="list list--inline list--stats list--divided">
                                                        <li class="item orderlogo">
                                                            <?php
                                                            if ($order[$i]->vendorImages != null) {
                                                                ?>
                                                                <div class="order__logo" style="background-image:url('<?php echo image_url(); ?>uploads/vendor/logo/<?php echo $order[$i]->vendorImages->photo; ?>'); background-repeat: no-repeat;"></div>
                                                            <?php } else { ?>
                                                                <div class="order__logo" style="background-image:url('http://placehold.it/192x192');"></div>
                                                            <?php } ?>
                                                        </li>
                                                        <li class="item item--stat stat-s">
                                                            <div class="text__group">
                                                                <span class="line--main"><?php echo $order[$i]->vendor->name; ?></span>
                                                                <span class="line--sub">Purchased From</span>
                                                            </div>
                                                        </li>
                                                        <li class="item item--stat stat-s">
                                                            <div class="text__group">
                                                                <span class="line--main"><?php echo $today; ?></span>
                                                                <span class="line--sub">Order Date</span>
                                                            </div>
                                                        </li>
                                                        <li class="item item--stat stat-s">
                                                            <div class="text__group">
                                                                <span class="line--main">
                                                                    <?php echo $order[$i]->order_status; ?>
                                                                </span>
                                                                <span class="line--sub">Status</span>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="order__btn col-md-2 col-xs-12 col--am align--right">
                                                    <ul class="list list--inline list--stats mobile-center">
                                                        <li class="item item--stat">
                                                            <div class="text__group">
                                                                <span class="line--main font">$<?php echo $order[$i]->total; ?></span>
                                                                <span class="line--sub">Order Total</span>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    <?php }
                                    ?>
                                    <!-- /Orders -->
                                <?php } ?>
                            </div>
                        </div>
                        <!-- Location  based Orders -->
                        <!-- Request Lists -->
                        <div id="tabRequests" class="page__tab">
                            <div class="heading__group border--dashed padding--s no--pad-lr no--pad-t wrapper">
                                <div class="wrapper__inner">
                                    <h4>Request List</h4>
                                </div>
                                <?php if ($request_product != null) { ?>
                                    <div id="controlsRequests" class="contextual__controls wrapper__inner align--right">
                                        <button class="btn btn--tertiary btn--s contextual--hide move_all_to_cart" data-id="<?php echo $location_id ?>" style="display: inline-block;">Move All Items to Cart</button>
                                        <button class="btn btn--primary btn--s is--contextual is--off modal--toggle selected_list" data-target="#addSelectionsToCartModal">Move Selections to Cart</button>
                                        <ul class="list list--inline fontWeight--2 fontSize--s margin--xs no--margin-tb no--margin-r is--contextual is--off">
                                            <li class="item">
                                                <?php
                                                foreach ($request_product as $location) {
                                                    $locat_id = $location->location_id;
                                                }
                                                ?>
                                                <a class="link modal--toggle deletelocationrequest_products" data-id="<?php echo $locat_id; ?>" data-lname="<?php echo $location_name->nickname; ?>" data-target="#removeSelectedItemsModal">Remove</a>
                                            </li>
                                        </ul>
                                    </div>
                                <?php } ?>
                            </div>
                            <table class="table table-responsive" data-controls="#controlsRequests">
                                <?php if ($request_product != null) { ?>
                                    <thead>
                                        <tr>
                                            <th width="5%">
                                                <label class="control control__checkbox">
                                                    <input type="checkbox" class=" is--selector" id="selectAllLocation">
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
                                <?php } ?>
                                <tbody>
                                    <!-- Requested Item -->
                                    <?php
                                    if ($request_product != null) {
                                        for ($i = 0; $i < count($request_product); $i++) {
                                            ?>
                                            <tr>
                                                <td>
                                                    <label class="control control__checkbox" id="general-content">
                                                        <input type="checkbox" name="checkboxRow" class="singleLocation" value="<?php echo $request_product[$i]->id; ?>">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </td>
                                                <td>
                                                    <!-- Product -->
                                                    <div class="product product--s row multi--vendor req--license padding--xxs req_dev">
                                                        <div class="product__image col col--2-of-8 col--am d-none d-sm-block">
                                                            <?php
                                                            if ($request_product[$i]->productImages != null) {
                                                                ?>
                                                                <div class="product__thumb" data-target="<?php echo base_url(); ?>view-product?id=<?php echo $request_product[$i]->product->id; ?>" style="background-image:url('<?php echo image_url(); ?>uploads/products/images/<?php echo $request_product[$i]->productImages->photo; ?>');"></div>
                                                            <?php } else { ?>
                                                                <div class="product__thumb" data-target="<?php echo base_url(); ?>view-product?id=<?php echo $request_product[$i]->product->id; ?>" style="background-image:url('<?php echo image_url(); ?>assets/img/product-image.png');"></div>
                                                            <?php } ?>
                                                        </div>
                                                        <input type="hidden" name="request_ids" id="user_id" class="request_ids" value="">
                                                        <input type="hidden" name="l_id" class="l_id" value="<?php echo $location_name->id; ?>">
                                                        <div class="product__data col col--6-of-8 col--am">
                                                            <?php if ($request_product[$i]->product->license_required == 'Yes') { ?>
                                                                <span class='product__name' data-target="<?php echo base_url(); ?>view-product?id=<?php echo $request_product[$i]->product->id; ?>">
                                                                    <?php echo $request_product[$i]->product->name;
                                                                    ?></span>
                                                            <?php } else {
                                                                ?>
                                                                <span data-target="<?php echo base_url(); ?>view-product?id=<?php echo $request_product[$i]->product->id; ?>">
                                                                    <?php echo $request_product[$i]->product->name;
                                                                    ?></span>
                                                            <?php }
                                                            ?>
                                                            <span class="product__mfr">
                                                                by <a class="link fontWeight--2" href="#">
                                                                    <?php echo $request_product[$i]->product->manufacturer; ?>
                                                                </a>
                                                            </span>
                                                            <span class="fontSize--s fontWeight--2">
                                                                $<?php echo ($request_product[$i]->product_pricing->price > 0) ? $request_product[$i]->product_pricing->price : $request_product[$i]->product_pricing->retail_price; ?>
                                                            </span>
                                                            <span class="fontSize--s">
                                                                (<?php echo $request_product[$i]->vendor->name; ?>)
                                                                <a class="link fontWeight--2 fontSize--xs modal--toggle change_vendor" data-list_id="<?php echo $request_product[$i]->id; ?>" data-product_id="<?php echo $request_product[$i]->product_id; ?>" data-vendor_id="<?php echo $request_product[$i]->vendor->id; ?>" data-target="#changeVendorModal">Change</a></span>
                                                        </div>
                                                    </div>
                                                    <!-- /Product -->
                                                </td>
                                                <td class="">
                                                    <input type="number" class="input input--qty width--100 r_quantity update_rqty" min="1" value="<?php echo $request_product[$i]->quantity; ?>">
                                                    <input type="hidden" class="request_id"  value="<?php echo $request_product[$i]->id; ?>" >
                                                    <div class="d-block d-sm-none"><br>
                                                         <button class="btn btn--s btn--primary btn--icon pull--down-xxs modal--toggle request_list" data-request_id="<?php echo $request_product[$i]->id; ?>" data-target="#addSelectionsToCartModal"><svg class="icon icon--cart-s"><use xlink:href="#icon-cart-s"></use></svg></button>
                                                    <button class="modal--toggle btn btn--s btn--secondary btn--icon pull--down-xxs remove-rquest" data-rid="<?php echo $request_product[$i]->id; ?>" data-target="#removeRequestItemsModal"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></button>
                                                    </div>
                                                </td>
                                                <td class="align--center d-none d-sm-block">
                                                    <button class="btn btn--s btn--primary btn--icon pull--down-xxs modal--toggle request_list" data-request_id="<?php echo $request_product[$i]->id; ?>" data-target="#addSelectionsToCartModal"><svg class="icon icon--cart-s"><use xlink:href="#icon-cart-s"></use></svg></button>
                                                    <button class="modal--toggle btn btn--s btn--secondary btn--icon pull--down-xxs remove-rquest" data-rid="<?php echo $request_product[$i]->id; ?>" data-target="#removeRequestItemsModal"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></button>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        ?> <tr>
                                            <?php
                                            echo "No products";
                                        }
                                        ?></tr>
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
                                                        <span class="fontWeight--2"><?php echo $activity[$i]->users->first_name; ?> </span>
                                                        <?php
                                                        if ($activity[$i]->action == 'moved item to') {
                                                            echo 'moved'
                                                            ?>
                                                            <span class="fontWeight--2">
                                                                <?php echo (isset($activity[$i]->products)) ? $activity[$i]->products->name : ""; ?>
                                                            </span>
                                                            to cart
                                                            <?php
                                                        } else if ($activity[$i]->action == 'moved item from') {
                                                            echo 'added '
                                                            ?>
                                                            <span class="fontWeight--2">
                                                                <?php echo (isset($activity[$i]->products)) ? $activity[$i]->products->name : ""; ?>
                                                            </span>
                                                            from cart
                                                            <?php
                                                        } else {
                                                            echo $activity[$i]->action;
                                                            ?>
                                                            <span class="fontWeight--2">
                                                                <?php echo (isset($activity[$i]->products)) ? $activity[$i]->products->name : ""; ?>
                                                            </span>
                                                        <?php } ?>
                                                        <span class="fontSize--xs textColor--dark-gray"><?php echo $up_date ?></span>
                                                    </div>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                        <!-- Request Lists -->
                        <!-- Inventory -->
                        <div id="tabInventory" class="page__tab">
                            <?php if ($inventory != null) { ?>
                                <div class="heading__group border--dashed padding--s no--pad-lr no--pad-t wrapper">
                                    <div class="wrapper__inner">
                                        <h4 class="disp--ib">Inventory</h4>
                                        <div class="select select--text">
                                            <form method="post" name="categorySearch" action="<?php echo base_url(); ?>inventory?location_id=<?php echo $location_name->id; ?>" style="display:inline;">
                                                <select name="categories" class="all_categories" onchange="document.categorySearch.submit();">
                                                    <option value="" selected>All Categories</option>
                                                    <option disabled="" >&nbsp;</option>
                                                    <option disabled="" >— Classic View</option>
                                                    <?php for ($i = 0; $i < count($classics); $i++) {
                                                        if($classics[$i]->count > 0) { ?>
                                                        <option <?php echo ($selected_category == $classics[$i]->id) ? "selected" : ""; ?> value="<?php echo $classics[$i]->id; ?>"><?php
                                                            echo $classics[$i]->name;
                                                            echo "&nbsp;" . "(" . $classics[$i]->count . ")";
                                                            ?>
                                                        </option>
                                                    <?php
                                                        }
                                                    } ?>
                                                    <option disabled="">&nbsp;</option>
                                                    <option disabled="">— Dentist View</option>
                                                    <?php for ($i = 0; $i < count($dentists); $i++) { ?>
                                                        <option <?php echo ($selected_category == $dentists[$i]->id) ? "selected" : ""; ?> value="<?php echo $dentists[$i]->id; ?>">
                                                            <?php
                                                            echo $dentists[$i]->name;
                                                            echo "&nbsp;" . "(" . $dentists[$i]->count . ")";
                                                            ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </form>
                                        </div>
                                    </div>
                                    <div id="controlsInventory" class="contextual__controls wrapper__inner align--right">
                                        <button class="btn btn--tertiary btn--s contextual--hide print_inventory">Print Inventory List</button>
                                        <ul class="list list--inline list--divided margin--s no--margin-tb no--margin-r is--contextual is--off">
                                            <li class="item">
                                                <a class="link modal--toggle update-inventory" data-target="#updateQtyModal">Update On-Hand Qty</a>
                                            </li>
                                            <li class="item update_threshold">
                                                <a class="link modal--toggle update_threshold" data-target="#updateThresholdModal" >Update Low Qty</a>
                                            </li>
                                            <li class="item">
                                                <a class="link modal--toggle" data-target="#removeInventoryItemsModal">Remove</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <table class="table inventory_table" data-controls="#controlsInventory">
                                    <thead>
                                        <tr>
                                            <th width="5%">
                                                <label class="control control__checkbox">
                                                    <input type="checkbox" class=" is--selector" id="selectAll">
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </th>
                                            <th width="55%">Item</th>
                                            <th width="14%">On Hand</th>
                                            <th width="14%">Low Qty</th>
                                            <th width="20%">Quick-Add</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Single Item -->
                                        <?php
                                        foreach($inventory_products as $product){
                                            // Debugger::debug($product);
                                            $update_date = $product->updated_at;
                                            $modified_date = date('M. j, Y', strtotime($update_date));
                                            ?>
                                            <tr>
                                                <td>
                                                    <label class="control control__checkbox">
                                                        <input type="checkbox" name="checkboxRow" class="singleCheckbox" value="<?php print_r($product->id); ?>">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </td>
                                                <td>
                                                    <!-- Product -->
                                                    <?php
                                                    // Debugger::debug($product);
                                                    if ($product->products->license_required == 'Yes') { ?>
                                                        <div class="product product--s row multi--vendor req--license">
                                                        <?php } else { ?>
                                                            <div class="product product--s row multi--vendor">
                                                            <?php } ?>
                                                            <div class="product__image col-md-4 col-xs-12">
                                                                <?php
                                                                if ($product->photo != null) {
                                                                    ?>
                                                                    <div class="product__thumb" data-target="<?php echo base_url(); ?>view-product?id=<?php echo $product->products->id; ?>" style="background-image:url('<?php echo image_url(); ?>uploads/products/images/<?php echo $product->photo; ?>');"></div>
                                                                <?php } else { ?>
                                                                    <div class="product__thumb" data-target="<?php echo base_url(); ?>view-product?id=<?php echo $product->products->id; ?>" style="background-image:url('<?php echo image_url(); ?>assets/img/product-image.png');"></div>
                                                                <?php } ?>
                                                            </div>
                                                            <div class="product__data col-md-8 col-xs-12">
                                                                <span class="product__name is--link" data-target="<?php echo base_url(); ?>view-product?id=<?php echo $product->id; ?>">
                                                                    <?php echo $product->name; ?>
                                                                </span>
                                                                <span class="product__mfr">
                                                                    by <a class="link fontWeight--2" href="#">
                                                                        <?php echo $product->manufacturer; ?></a>
                                                                </span>
                                                                <span class="fontSize--s textColor--dark-gray">Last modified <?php echo $modified_date; ?></span>
                                                            </div>
                                                        </div>
                                                        <!-- /Product -->
                                                </td>
                                                <td>
                                                    <input type="number" class="input input--qty width--100 inventory_qty" min="0" value="<?php echo $product->purchashed_qty; ?>">
                                                </td>
                                                <td>
                                                    <input type="number" class="input input--qty width--100 threshold_qty" min="0" value="<?php echo $product->minimum_threshold; ?>">
                                                </td>
                                                <td class="align--center">
                                                    <div class="input__combo wrap">
                                                            <input type="number" class="input input--qty aaa request_quantity" min="1" value="1">
                                                            <div class="btn__group">
                                                                <?php

                                                                $regular_price = (isset($product->price)) ? $product->price : 0.00;
                                                                $retail_price = (isset($product->retail_price)) ? $product->retail_price : 0.00;

                                                                if(!empty($_SESSION['user_buying_clubs'])){
                                                                    // Debugger::debug($product->price, 'product_price');
                                                                    $clubPrice = $bcModel->getBestPrice($product->id, $product->vendor_id, $bcPrices, $_SESSION['user_buying_clubs'], $regular_price);
                                                                }

                                                                if(!empty($clubPrice)){
                                                                    $regular_price = $clubPrice;
                                                                }
                                                                $regular_price = number_format($regular_price, 2);

                                                                if ($product->vendor_count > 0 && User_model::can($_SESSION['user_permissions'], 'add_cart') && ($product->license_required != 'Yes' || !empty($userLicenses))) { ?>
                                                                    <button class="btn btn--m btn--tertiary btn--icon modal--toggle add_cart" data-pid="<?php echo $product->id; ?>" data-name="<?php echo $product->name; ?>" data-price="<?php echo $regular_price; ?>" data-procolor="<?php echo $product->color; ?>" data-vendor_id="<?php echo $product->vendor_id ?>" data-license_required="<?php echo $product->license_required; ?>" data-target="#chooseLocationModal"><svg class="icon icon--cart-s"><use xlink:href="#icon-cart-s"></use></svg></button>
                                                                <?php  } ?>
                                                                <?php if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $tier_1_2))) { ?>
                                                                <button class="btn btn--m btn--tertiary btn--icon modal--toggle add_cart" data-pid="<?php echo $product->product_id; ?>" data-name="<?php echo $product->name; ?>" data-price="<?php
                                                                if ($product->min_retail_price > 0) {
                                                                    echo $product->min_retail_price;
                                                                } else {
                                                                    echo $product->minprice;
                                                                }
                                                                ?>" data-procolor="<?php echo $product->color; ?>" data-vendor_id="<?php echo $product->vendor_id; ?>" data-target="#chooseLocationModal"><svg class="icon icon--cart-s"><use xlink:href="#icon-cart-s"></use></svg></button>
                                                                <?php } ?>
                                                                <button class="btn btn--m btn--tertiary btn--icon modal--toggle add_request" data-id="<?php echo $product->product_id; ?>" data-vendor="<?php echo $product->vendor_id; ?>" data-price="<?php echo $regular_price; ?>" data-target="#chooseRequestListModal"><svg class="icon icon--list-s"><use xlink:href="#icon-list-s"></svg></button>


                                                                </div>
                                                            </div>
                                                    <!-- <button class="btn btn--s btn--primary is--link" data-target="<?php echo base_url(); ?>view-product?id=<?php echo $inventory[$i]->products->id; ?>">View</button> -->
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        <!-- /Single Item -->
                                    </tbody>
                                </table>
                            <?php } ?>
                        </div>
                        <!-- Inventory -->
                        <!-- Users -->
                        <div id="tabUsers" class="page__tab">
                            <div class="heading__group border--dashed padding--s no--pad-lr no--pad-t wrapper">
                                <div class="wrapper__inner">
                                    <h4>Manage Users</h4>
                                </div>
                                <?php if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $tier1)))) { ?>
                                    <div id="controlsUsers" class="contextual__controls wrapper__inner align--right">
                                        <button class="btn btn--tertiary btn--s modal--toggle contextual--hide" data-target="#assignUsersModal">Assign User(s)</button>
                                        <ul class="list list--inline list--divided margin--s no--margin-tb no--margin-r is--contextual is--off">
                                            <li class="item">
                                                <a class="link modal--toggle" data-target="#unassignSelectedUsersLocationModal">Unassign Selected</a>
                                            </li>
                                        </ul>
                                    </div>
                                <?php } ?>
                            </div>
                            <table class="table table-responsive" data-controls="#controlsUsers">
                                <thead>
                                    <tr>
                                        <?php if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $tier1)))) { ?>
                                            <th width="5%">
                                                <?php if ($_SESSION['user_id']) { ?>
                                                    <label class="control control__checkbox">
                                                        <input type="checkbox" class=" is--selector" id="selectAll">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                <?php } ?>
                                            </th>
                                        <?php } ?>
                                        <th width="30%">Name
                                        </th>
                                        <th width="35%" class="dn">Role
                                            <?php if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $tier1)))) { ?>
                                            </th>
                                            <th width="15%" class="dn">&nbsp;</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Single User -->
                                    <?php
                                    if ($user_data != null) {
                                        for ($i = 0; $i < count($user_data); $i++) {
                                            ?>
                                            <?php if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $tier1)))) { ?>
                                                <tr>
                                                    <td>
                                                        <?php if ($user_data[$i]->users->id != $_SESSION['user_id']) { ?>
                                                            <label class="control control__checkbox">
                                                                <input type="checkbox" name="checkboxRow"  class="singleCheckbox" value="<?php echo $user_data[$i]->user_id; ?>" >
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        <?php } ?>
                                                    </td>
                                                <?php } ?>
                                                <td>
                                                    <div class="entity__group" style="width: 300px">
                                                        <?php if ($user_data[$i]->userImage) { ?>
                                                            <div class="avatar avatar--s" style="background-image:url('<?php echo image_url(); ?>uploads/user/profile/<?php echo $user_data[$i]->userImage->photo; ?>');"></div>
                                                        <?php } else { ?>
                                                            <div class="avatar avatar--s" style="background-image:url('<?php echo image_url(); ?>assets/img/avatar-default.png');"></div>
                                                        <?php } ?>
                                                        <div class="text__group">
                                                            <?php echo $user_data[$i]->users->first_name; ?><?php echo ($user_data[$i]->users->id == $_SESSION['user_id']) ? " (You)" : ""; ?>
                                                        </div>

                                                    </div>
                                                    <div class="d-block d-sm-none mt-2">

                                                    <?php if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $tier1)))) { ?>
                                                        <?php if($user_data[$i]->users->id != $_SESSION['user_id']) { ?>
                                                        <div class="select wrapper__inner" style="width: 250px;display: inline-block;">
                                                            <?php $role_limit = 7;
                                                            if ($organization_role_id < $role_limit) {
                                                                ?>
                                                                <select name="role_id" class="selectRole" aria-label="Select a Title"  data-user_id="<?php echo $user_data[$i]->users->id; ?>" required>
                                                                    <option disabled="" selected="" value="default">Select Role</option>
                                                                    <option value="3"<?php if($user_data[$i]->roles->id == 3){?> selected <?php } ?>>Corporate Admin- Tier 1</option>
                                                                    <option value="4"<?php if($user_data[$i]->roles->id == 4){?> selected <?php } ?>>Purchasing Manager- Tier 2</option>
                                                                    <option value="5"<?php if($user_data[$i]->roles->id == 5){?> selected <?php } ?>>Office Manager- Tier 3A</option>
                                                                    <option value="6"<?php if($user_data[$i]->roles->id == 6){?> selected <?php } ?>>Office Assistant- Tier 3B</option>
                                                                </select>
                                                            <?php } else { ?>
                                                                <select name="role_id" class="selectRole" aria-label="Select a Title" data-user_id="<?php echo $user_data[$i]->users->id; ?>" required>
                                                                    <option disabled="" selected="" value="default">Select Role</option>
                                                                    <option value="7"<?php if($user_data[$i]->roles->id == 7){?> selected <?php } ?>>Institution Admin- Tier 1</option>
                                                                    <option value="8"<?php if($user_data[$i]->roles->id == 8){?> selected <?php } ?>>Institution Director- Tier 2A</option>
                                                                    <option value="9"<?php if($user_data[$i]->roles->id == 9){?> selected <?php } ?>>Instructor- Tier 2B</option>
                                                                    <option value="10"<?php if($user_data[$i]->roles->id == 10){?> selected <?php } ?>>Student- Tier 2C</option>
                                                                </select>
                                                            <?php }
                                                        ?>
                                                        </div>
                                                    <?php } else {
                                                            echo $roles[$user_data[$i]->roles->id - 1]->role_name . ' - ' . $roles[$user_data[$i]->roles->id - 1]->role_tier;
                                                        }
                                                    }  else {
                                                        echo $roles[$user_data[$i]->roles->id - 1]->role_name . ' - ' . $roles[$user_data[$i]->roles->id - 1]->role_tier;
                                                    }
                                                    ?>
                                                      <?php if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $tier1)))) { ?>
                                                        <?php if ($user_data[$i]->users->id != $_SESSION['user_id']) { ?>
                                                            <button class="btn btn--s btn--secondary btn--icon modal--toggle ml-1" data-target="#unassignUserModal<?php echo $user_data[$i]->id; ?>"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></button>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    </div>


                                                </td>
                                                <td class="dn">
                                                    <?php if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $tier1)))) { ?>
                                                        <?php if($user_data[$i]->users->id != $_SESSION['user_id']) { ?>
                                                        <div class="select wrapper__inner" style="width: 300px">
                                                            <?php $role_limit = 7;
                                                            if ($organization_role_id < $role_limit) {
                                                                ?>
                                                                <select name="role_id" class="selectRole" aria-label="Select a Title"  data-user_id="<?php echo $user_data[$i]->users->id; ?>" required>
                                                                    <option disabled="" selected="" value="default">Select Role</option>
                                                                    <option value="3"<?php if($user_data[$i]->roles->id == 3){?> selected <?php } ?>>Corporate Admin- Tier 1</option>
                                                                    <option value="4"<?php if($user_data[$i]->roles->id == 4){?> selected <?php } ?>>Purchasing Manager- Tier 2</option>
                                                                    <option value="5"<?php if($user_data[$i]->roles->id == 5){?> selected <?php } ?>>Office Manager- Tier 3A</option>
                                                                    <option value="6"<?php if($user_data[$i]->roles->id == 6){?> selected <?php } ?>>Office Assistant- Tier 3B</option>
                                                                </select>
                                                            <?php } else { ?>
                                                                <select name="role_id" class="selectRole" aria-label="Select a Title" data-user_id="<?php echo $user_data[$i]->users->id; ?>" required>
                                                                    <option disabled="" selected="" value="default">Select Role</option>
                                                                    <option value="7"<?php if($user_data[$i]->roles->id == 7){?> selected <?php } ?>>Institution Admin- Tier 1</option>
                                                                    <option value="8"<?php if($user_data[$i]->roles->id == 8){?> selected <?php } ?>>Institution Director- Tier 2A</option>
                                                                    <option value="9"<?php if($user_data[$i]->roles->id == 9){?> selected <?php } ?>>Instructor- Tier 2B</option>
                                                                    <option value="10"<?php if($user_data[$i]->roles->id == 10){?> selected <?php } ?>>Student- Tier 2C</option>
                                                                </select>
                                                            <?php }
                                                        ?>
                                                        </div>
                                                    <?php } else {
                                                            echo $roles[$user_data[$i]->roles->id - 1]->role_name . ' - ' . $roles[$user_data[$i]->roles->id - 1]->role_tier;
                                                        }
                                                    }  else {
                                                        echo $roles[$user_data[$i]->roles->id - 1]->role_name . ' - ' . $roles[$user_data[$i]->roles->id - 1]->role_tier;
                                                    }
 ?>
                                                </td>
                                                <?php if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $tier1)))) { ?>
                                                    <td class="align--center dn">
                                                        <?php if ($user_data[$i]->users->id != $_SESSION['user_id']) { ?>
                                                            <button class="btn btn--s btn--secondary btn--icon modal--toggle" data-target="#unassignUserModal<?php echo $user_data[$i]->id; ?>"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></button>
                                                        <?php } ?>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td class="align--center">
                                                No users for this Location
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <!-- /Single User -->
                                </tbody>
                            </table>
                        </div>
                        <!-- Users -->
                        <!-- settings -->
                        <div id="tabSettings" class="page__tab row">
                            <!-- Nickname -->
                            <div class="accordion__group">
                                <div class="accordion__section">
                                    <div class="accordion__title wrapper">
                                        <div class="wrapper__inner">
                                            <h3>Nickname</h3>
                                        </div>
                                        <div class="wrapper__inner align--right">
                                            <a class="link link--expand">Edit</a>
                                        </div>
                                    </div>
                                    <div class="accordion__content">
                                        <div class="accordion__preview">
                                            <?php echo $location_name->nickname; ?>
                                        </div>
                                        <div class="accordion__edit">
                                            <form id="formLocationNickname" class="form__group" action="<?php echo base_url("update-locationame"); ?>" method="post">
                                                <div class="row">
                                                    <div class="input__group is--inline col-md-12">
                                                        <input id="locationName" name="locationName" class="input not--empty" type="text" placeholder="Location #3" value="<?php echo $location_name->nickname; ?>" required>
                                                        <input type="hidden" name="location_id" value="<?php echo $location_name->id; ?>" id="#form_output">
                                                        <label class="label" for="locationName">Nickname</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <button class="btn btn--primary btn--m btn--block save--toggle form--submit">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /Nickname -->
                            <!-- Address -->
                            <div class="accordion__group">
                                <div class="accordion__section">
                                    <div class="accordion__title wrapper">
                                        <div class="wrapper__inner">
                                            <h3>Shipping Address</h3>
                                        </div>
                                        <div class="wrapper__inner align--right">
                                            <a class="link link--expand">Edit</a>
                                        </div>
                                    </div>
                                    <div class="accordion__content">
                                        <div class="accordion__preview">
                                            <?php
                                            if ($location_name->address1 != null) {
                                                echo $location_name->address1 .", " ;
                                            }
                                            if ($location_name->address2 != null) {
                                                echo  $location_name->address2 .", " ;
                                            }
                                            if ($location_name->city != null) {
                                                echo  $location_name->city .", ";
                                            }
                                            if ($location_name->state != null) {
                                                echo $location_name->state ." " ;
                                            }
                                            if ($location_name->zip != null) {
                                                echo $location_name->zip;
                                            }
                                            ?>
                                        </div>
                                        <div class="accordion__edit">
                                            <form id="formLocationAddress" class="form__group" action="<?php echo base_url("update-address"); ?>" method="post">
                                                <input type="hidden" name="location_id" value="<?php echo $location_name->id; ?>">
                                                <div class="row">
                                                    <div class="input__group is--inline col-md-12">
                                                        <input id="locationAddress1" name="locationAddress1" class="input not--empty" type="text" placeholder="7855 Winterfell Way" value="<?php echo $location_name->address1; ?>" required>
                                                        <label class="label" for="locationAddress1">Address Line 1</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="input__group is--inline">
                                                            <input id="locationAddress2" name="locationAddress2" class="input not--empty" type="text" placeholder="Unit 3" value="<?php echo $location_name->address2; ?>">
                                                            <label class="label" for="locationAddress2">Unit/Suite/#</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="input__group is--inline">
                                                            <input id="locationCity" name="locationCity" class="input not--empty" type="text" placeholder="Los Angeles" value="<?php echo $location_name->city; ?>">
                                                            <label class="label" for="locationCity">City</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="input__group is--inline">
                                                            <div class="select">
                                                                <select name="state" required>
                                                                    <option value="default" disabled="" selected>Choose State</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'AL') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="AL">Alabama</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'AK') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="AK">Alaska</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'AZ') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="AZ">Arizona</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'AR') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="AR">Arkansas</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'CA') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="CA">California</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'CO') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="CO">Colorado</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'CT') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="CT">Connecticut</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'CT') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="CT">Delaware</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'DC') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="DC">District Of Columbia</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'FL') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="FL">Florida</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'GA') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="GA">Georgia</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'HI') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="HI">Hawaii</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'ID') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="ID">Idaho</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'IL') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="IL">Illinois</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'IN') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="IN">Indiana</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'IA') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="IA">Iowa</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'KS') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="KS">Kansas</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'KY') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="KY">Kentucky</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'LA') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="LA">Louisiana</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'ME') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="ME">Maine</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'MD') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="MD">Maryland</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'MA') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="MA">Massachusetts</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'MI') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="MI">Michigan</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'MN') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="MN">Minnesota</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'MS') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="MS">Mississippi</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'MO') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="MO">Missouri</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'MT') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="MT">Montana</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'NE') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="NE">Nebraska</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'NV') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="NV">Nevada</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'NH') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="NH">New Hampshire</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'NJ') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="NJ">New Jersey</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'NM') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="NM">New Mexico</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'NY') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="NY">New York</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'NC') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="NC">North Carolina</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'ND') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="ND">North Dakota</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'OH') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="OH">Ohio</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'OK') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="OK">Oklahoma</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'OR') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="OR">Oregon</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'PA') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="PA">Pennsylvania</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'RI') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="RI">Rhode Island</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'SC') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="SC">South Carolina</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'SD') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="SD">South Dakota</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'TN') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="TN">Tennessee</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'TX') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="TX">Texas</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'UT') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="UT">Utah</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'VT') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="VT">Vermont</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'VA') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="VA">Virginia</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'WA') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="WA">Washington</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'WV') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="WV">West Virginia</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'WI') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="WI">Wisconsin</option>
                                                                    <option <?php
                                                                    if ($location_name->state == 'WY') {
                                                                        echo"selected";
                                                                    }
                                                                    ?> value="WY">Wyoming</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="input__group is--inline">
                                                            <input id="locationZip" name="locationZip" class="input not--empty" type="text" placeholder="90210" value="<?php echo $location_name->zip; ?>" required>
                                                            <label class="label" for="locationZip">Zip</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <button class="btn btn--primary btn--m btn--block save--toggle form--submit">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /Address -->
                            <!-- Budget -->
                            <div class="accordion__group">
                                <div class="accordion__section">
                                    <div class="accordion__title wrapper">
                                        <div class="wrapper__inner">
                                            <h3>Spend Budget <a class="link link--icon margin--xs no--margin-tb no--margin-r" style="transform:translateY(-2px);">
                                        <svg class="icon icon--help modal--toggle" data-target="#spendBudgetModel"><use xlink:href="#icon-help"></use></svg>
                                    </a></h3>
                                        </div>
                                        <div class="wrapper__inner align--right">
                                            <a class="link link--expand">Edit</a>
                                        </div>
                                    </div>
                                    <div class="accordion__content">
                                        <div class="accordion__preview">
                                            $<?php echo $location_name->spend_budget; ?>
                                            <?php
                                            if ($location_name->budget_duration != null) {
                                                echo "(" . $location_name->budget_duration . ")";
                                            }
                                            ?>
                                        </div>
                                        <div class="accordion__edit">
                                            <form id="formLocationBudget" class="form__group" action="<?php echo base_url("updateSpendBudget"); ?>" method="post">
                                                <input type="hidden" name="location_id" value="<?php echo $location_name->id; ?>">
                                                <div class="row">
                                                    <div class="input__group is--inline col-md-12">
                                                        <input id="locationBudget" name="locationBudget" class="input not--empty" type="text" value="<?php echo $location_name->spend_budget; ?>" required>
                                                        <label class="label" for="locationBudget">Budget ($)</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="select col-md-12">
                                                        <select name="locationBudgetRange" required>
                                                            <option disabled>Select Timeframe</option>
                                                            <option
                                                                <?php if ($location_name->budget_duration == 'Daily') echo 'selected' ?> value="Daily">Daily
                                                            </option>
                                                            <option
                                                                <?php if ($location_name->budget_duration == 'Weekly') echo 'selected' ?> value="Weekly">Weekly    </option>
                                                            <option  <?php if ($location_name->budget_duration == 'Bi-Weekly') echo 'selected' ?> value="Bi-Weekly">Bi-Weekly
                                                            </option>
                                                            <option
                                                                <?php if ($location_name->budget_duration == 'Monthly') echo 'selected' ?> value="Monthly" >Monthly</option>
                                                            <option
                                                                <?php if ($location_name->budget_duration == 'Quarterly') echo 'selected' ?> value="Quarterly">Quarterly
                                                            </option>
                                                            <option
                                                                <?php if ($location_name->budget_duration == 'Yearly') echo 'selected' ?> value="Yearly">Yearly
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <button class="btn btn--primary btn--m btn--block save--toggle form--submit">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /Budget -->
                        </div>
                        <!-- settings -->
                    </div>
                    <!-- /Content -->
                </div>
            </div>
    </section>
    <!-- /Main Content -->
</div>
<script src="/assets/js/users.js"></script>
<!-- /Content Section -->

<?php $this->load->view('templates/_inc/shared/modals/order-cancellation.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/assign-users.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/unassign-users.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/unassign-selected-users-location.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/change-item-vendor.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/add-selected-items.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/remove-items.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/remove-inventory.php') ?>
<?php $this->load->view('templates/_inc/shared/modals/update-qty.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/update-low-qty.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/remove-request-item.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/help-spend-budget.php'); ?>

<?=$this->load->view('templates/_inc/shared/modals/choose-location.php') ?>
<?=$this->load->view('templates/_inc/shared/modals/choose-request-list.php') ?>

<!-- <?php include(INCLUDE_PATH . '/_inc/shared/modals/order-cancellation.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/assign-users.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/unassign-users.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/unassign-selected-users-location.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/change-item-vendor.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/add-selected-items.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/remove-items.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/remove-inventory.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/update-qty.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/update-low-qty.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/remove-request-item.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/help-spend-budget.php'); ?> -->
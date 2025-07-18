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
                    Manage Inventory
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
                <div class="sidebar col-md-3 col-xs-12 mobile-center bg--white padding--l no--pad-l">
                    <!-- Request List Info -->
                    <div class="sidebar__group">
                        <h3>Manage Inventory</h3>
                        <p class="no--margin-tb">View the quantities of items that you have purchased and have on hand at a location.</p>
                    </div>
                    <!-- /Request List Info -->
                    <!-- Location Tabs -->
                    <div class="sidebar__group">
                        <div class="tab__group is--vertical" data-target="#locationContent">
                            <?php
                            if ($user_locations != null) {
                                for ($i = 0; $i < count($user_locations); $i++) {
                                    if (isset($user_locations[$i]->id)) {
                                        if (isset($user_locations[$i]->item_count) && ($user_locations[$i]->item_count) > 0) {
                                            ?>
                                            <label class="tab state--toggle has--badge" value="" data-badge="<?php echo $user_locations[$i]->item_count; ?>" onclick="location.href = '<?php echo base_url() ?>manage-inventory?id=<?php echo $user_locations[$i]->id; ?>'">
                                                <?php } else { ?>
                                                <label class="tab state--toggle has--badge" value="" data-badge="0" onclick="location.href = '<?php echo base_url() ?>manage-inventory?id=<?php echo $user_locations[$i]->id; ?>'">
                                                    <?php } ?>
                                                    <input type="radio" name="locationTabs" <?php echo ($list_id != null && $user_locations[$i]->id == $list_id) ? "checked" : "" ?> >
                                                    <span><a class="link" href="javascript:void(0)"><?php echo $user_locations[$i]->nickname; ?></a></span>
                                                </label>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <!-- /Location Tabs -->
                        </div>
                        <!-- /Sidebar -->
                        <!-- Content -->
                        <div id="locationContent" class="content col-md-9 col-xs-12">
                            <div class="page__tab">
                                <div class="heading__group border--dashed padding--s no--pad-lr no--pad-t wrapper">
                                    <div class="wrapper__inner">
                                        <h4 class="disp--ib">Inventory</h4>
                                        <div class="select select--text">
                                            <form method="post" name="categorySearch" action="<?php echo base_url(); ?><?php echo (isset($list_id) && $list_id != null) ? "manage-inventory?id=" . $list_id : "manage-inventory" ?>" style="display:inline;">
                                                <select name="categories" class="all_categories" onchange="document.categorySearch.submit();">
                                                    <option value="" selected>All Categories</option>
                                                    <option disabled="" >&nbsp;</option>
                                                    <option disabled="" >— Classic View</option>
                                                    <?php for ($i = 0; $i < count($classics); $i++) { ?>
                                                    <?php if($classics[$i]->count > 0) {
                                                        ?>
                                                        <option <?php echo ($selected_category == $classics[$i]->id) ? "selected" : ""; ?> value="<?php echo $classics[$i]->id; ?>"><?php
                                                        echo $classics[$i]->name;
                                                        echo "&nbsp;" . "(" . $classics[$i]->count . ")";
                                                        ?>
                                                    </option>
                                                    <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </form>
                                        </div>
                                    </div>
                                    <div id="controlsInventory" class="contextual__controls wrapper__inner align--right">
                                        <button class="btn btn--tertiary btn--m contextual--hide print_inventory">Print Inventory List</button>
                                        <a class="btn btn--tertiary btn--m contextual--hide download_inventory" href="/manage-inventory?id=<?php echo $list_id; ?>&csv=true">Download Inventory List</a>
                                        <ul class="list list--inline list--divided margin--s no--margin-tb no--margin-r is--contextual is--off">
                                            <li class="item">
                                                <a class="link modal--toggle update-inventory" data-target="#updateQtyModal">Update On-Hand Qty</a>
                                            </li>
                                            <li class="item">
                                                <a class="link modal--toggle update_threshold" data-target="#updateThresholdModal">Update Low Qty</a>
                                                <input type="hidden" name="" id="user_id" class="i_id" value="">
                                            </li>
                                            <li class="item">
                                                <a class="link modal--toggle" data-target="#removeInventoryItemsModal">Remove</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <table class="table inventory_table table-responsive" data-controls="#controlsInventory">
                                    <thead>
                                        <tr>
                                            <th width="5%">
                                                <label class="control control__checkbox">
                                                    <input type="checkbox" class=" is--selector" id="selectAll">
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </th>
                                            <th width="47%">Item</th>
                                            <th width="14%">On Hand</th>
                                            <th width="14%">Low Qty</th>
                                            <th width="20%">Quick-Add</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Single Item -->
                                        <?php
                                        if ($user_locations != null) {
                                            $p_count = count($inventory_products);
                                            $row = $inventory_products;
                                    // Debugger::debug($inventory_products);
                                            $key = array_keys($row);
                                            foreach($inventory_products as $product){ ?>
                                            <tr>
                                                <td>
                                                    <label class="control control__checkbox">
                                                        <input type="checkbox" name="checkboxRow" class="singleCheckbox" value="<?php print_r($product->inventory_id); ?>">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </td>
                                                <td>
                                                    <!-- Product -->
                                                    <?php if ($product->license_required == 'Yes') { ?>
                                                    <div class="product product--s product--list row multi--vendor  has--sale req--license " data-target="<?php echo base_url('product'); ?>">
                                                        <?php } else { ?><div class="product product--list row " data-target="<?php echo base_url('product'); ?>"> <?php } ?>
                                                            <div class="product__image col-md-4 col-xs-12">
                                                                <?php if ($product->photo != null) { ?>
                                                                <div class="product__thumb is--link" data-target="<?php echo base_url(); ?>view-product?id=<?php print_r($product->product_id); ?>" style="background-image:url('<?php echo image_url(); ?>uploads/products/images/<?php print_r($product->photo); ?>');"> </div>
                                                                <?php } else { ?>
                                                                <div class="product__thumb is--link" data-target="<?php echo base_url(); ?>view-product?id=<?php print_r($product->product_id); ?>" style="background-image:url('<?php echo image_url(); ?>assets/img/product-image.png');">
                                                                </div>
                                                                <?php } ?>
                                                            </div>
                                                            <div class="product__data col-md-8 col-xs-12">
                                                                <span class="productname-inv product__name is--link" data-target="<?php echo base_url(); ?>view-product?id=<?php print_r($product->product_id); ?>"><?php print_r($product->name); ?></span>
                                                                <span class="product__mfr">
                                                                    by <a class="link fontWeight--2" href="#"><?php print_r($product->manufacturer); ?></a>
                                                                </span>
                                                                <span class="fontSize--s textColor--dark-gray">Last modified
                                                                    <?php
                                                                    echo date('M d, Y', strtotime($product->updated_at));
                                                                    ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <!-- /Product -->
                                                    </td>
                                                    <td>
                                                        <input type="number" class="input input--qty width--100 inventory_qty" min="0" value="<?php print_r($product->purchashed_qty); ?>">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="input input--qty width--100 threshold_qty" min="0" value="<?php print_r($product->minimum_threshold); ?>">
                                                    </td>
                                                    <td class="align--center">
                                                        <div class="input__combo wrap">
                                                            <input type="number" class="input input--qty aaa request_quantity" min="1" value="1">
                                                            <div class="btn__group">
                                                                <?php
                                                                // Debugger::debug($product, 'product', false, 'Inventory');

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

                                                                if ($product->vendor_count > 0 && User_model::can($_SESSION['user_permissions'], 'add_cart') ) { ?>
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
                                                        </td>
                                                    </tr>
                                                    <?php }
                                                } ?>
                                                <!-- /Single Item -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- /Content -->
                            </div>
                        </div>
                    </section>
                    <!-- /Main Content -->
                </div>
                <!-- /Content Section -->
                <?=$this->load->view('templates/_inc/shared/modals/choose-location.php') ?>
                <?=$this->load->view('templates/_inc/shared/modals/choose-request-list.php') ?>
                <?=$this->load->view('templates/_inc/shared/modals/remove-inventory.php') ?>
                <?=$this->load->view('templates/_inc/shared/modals/update-qty.php') ?>
                <?=$this->load->view('templates/_inc/shared/modals/update-low-qty.php') ?>

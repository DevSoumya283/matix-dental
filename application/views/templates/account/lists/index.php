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
                    Manage Shopping Lists
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
                        <h3>Shopping Lists</h3>
                        <p class="no--margin-tb">View and create lists of items that users can quickly and easily add them to request lists and carts.</p>
                    </div>
                    <!-- /Request List Info -->
                    <!-- Location Tabs -->
                    <div class="sidebar__group">
                        <div class="tab__group is--vertical" data-target="#locationContent">
                            <?php
                            if ($prepopulated_lists != null) {
                                for ($i = 0; $i < count($prepopulated_lists); $i++) {
                                    for ($j = 0; $j < count($prepopulated_lists[$i]); $j++) {
                                        if (isset($prepopulated_lists[$i][$j]->item_count) && ($prepopulated_lists[$i][$j]->item_count) > 0) {
                                            ?>
                                            <label onclick="location.href = '<?php echo base_url() ?>shopping-products?id=<?php echo $prepopulated_lists[$i][$j]->id; ?>';" class="tab state--toggle has--badge" value="" data-badge="<?php echo $prepopulated_lists[$i][$j]->item_count; ?>">
                                                <?php } else { ?>
                                                <label onclick="location.href = '<?php echo base_url() ?>shopping-products?id=<?php echo $prepopulated_lists[$i][$j]->id; ?>';" class="tab state--toggle has--badge" value="" data-badge="0">
                                                    <?php } ?>
                                                    <input type="radio" name="locationTabs" <?php if ($list_view != null && $list_view->id == $prepopulated_lists[$i][$j]->id) { ?> checked <?php } ?>>
                                                    <span><a class="link" href="#"><?php echo $prepopulated_lists[$i][$j]->listname; ?></a></span>
                                                </label>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <!-- /Location Tabs -->
                            <!-- Location Tabs -->
                            <?php
                            $role_lists = unserialize(ROLES_SHOPPINGLISTS);
                            $role_users = unserialize(ROLES_USERS);
                            $tier1_2 = unserialize(ROLES_TIER1_2);
                            if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $role_lists)))) {
                                ?>
                                <div class="sidebar__group">
                                    <a class="link fontWeight--2 fontSize--s modal--toggle get_locations"  data-target="#newPrepopulatedListModal">+ Create New List</a>
                                </div>
                                <?php } ?>
                                <!-- /Location Tabs -->
                            </div>
                            <!-- /Sidebar -->
                            <!-- Content -->
                            <div id="locationContent" class="content col-md-9 col-xs-12">
                                <div class="page__tab">
                                    <div class="heading__group border--dashed padding--s no--pad-lr no--pad-t wrapper">
                                        <?php
                                        if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $role_lists)))) {
                                            if ($list_view != null) {
                                                ?>
                                                <div class="wrapper__inner">
                                                    <h4 class="disp--ib">
                                                        <?php echo $list_view->listname; ?></h4>
                                                        <input type="hidden" value="<?php echo $list_view->id; ?>" class="list_id">
                                                        <a class="link fontWeight--2 fontSize--s modal--toggle update_list" data-id="<?php echo $list_view->id; ?>" data-name="<?php echo $list_view->listname; ?>" data-location_id="<?php echo $list_view->location_id; ?>" data-target="#renamePrepopulatedListModal">Rename</a>
                                                        <input type="hidden" name="location_id" class="location_id" value="<?php echo $list_view->location_id; ?>">
                                                    </div>
                                                    <?php
                                                }
                                            }
                                            ?>
                                            <?php if ($populated_products != null) { ?>
                                            <div id="controlsRequests" class="contextual__controls wrapper__inner align--right">
                                                <?php if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $tier1_2)))) { ?>
                                                <button class="btn btn--tertiary btn--s contextual--hide move_allListToCart" data-id="<?php echo $list_view->id; ?>">Add All Items to Cart</button>
                                                <button class="btn btn--primary btn--s is--contextual is--off modal--toggle selected_shoppinglist" data-target="#addSelectionsListToCartModal">Add Selections to Cart</button>
                                                <?php } ?>
                                                <?php if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $role_lists)))) { ?>
                                                <ul class="list list--inline fontWeight--2 fontSize--s margin--xs no--margin-tb no--margin-r is--contextual is--off">
                                                    <li class="item">
                                                        <a class="link modal--toggle" data-id="" data-target="#removeItemsModal">Remove</a>
                                                    </li>
                                                </ul>
                                                <?php } ?>
                                            </div>
                                            <?php } else { ?>
                                            <?php } ?>
                                        </div>
                                        <?php if ($populated_products != null) { ?>
                                        <table class="table table-responsive" data-controls="#controlsRequests">
                                            <thead>
                                                <tr>
                                                    <th width="5%">
                                                        <label class="control control__checkbox">
                                                            <input type="checkbox" class=" is--selector" id="selectAll">
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </th>
                                                    <th width="70%">Product
                                                    </th>
                                                    <th width="30%" class="align--center dn">Quick Add</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Requested Item -->
                                                <?php
                                                for ($i = 0; $i < count($populated_products); $i++) {
                                                    if (isset($populated_products[$i]->product)){
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <label class="control control__checkbox">
                                                                    <input type="checkbox" name="checkboxRow" class="singleCheckbox" value="<?php echo $populated_products[$i]->id; ?>">
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <!-- Product -->
                                                                <div class="product product--s row multi--vendor req--license padding--xxs">
                                                                    <div class="product__image col-md-3 col-xs-12">
                                                                        <?php if (isset($populated_products[$i]->images)) { ?>
                                                                        <div class="product__thumb" style="background-image:url('<?php echo image_url(); ?>uploads/products/images/<?php echo $populated_products[$i]->images->photo; ?>');">
                                                                        </div>
                                                                        <?php } else { ?>
                                                                        <div class="product__thumb" style="background-image:url('<?php echo image_url(); ?>assets/img/product-image.png');">
                                                                        </div>
                                                                        <?php } ?>
                                                                    </div>
                                                                    <input type="hidden" name="product_list_id" id="user_id" class="product_list_id" value="">
                                                                    <div class="product__data col-md-6 col-xs-12">
                                                                        <span class=" is--link" data-target="<?php echo base_url(); ?>view-product?id=<?php echo $populated_products[$i]->product->id; ?>">
                                                                            <?php
                                                                            if ($populated_products[$i]->product->license_required == 'Yes') {
                                                                                ?>
                                                                                <span class="product__name">
                                                                                    <?php
                                                                                } else {
                                                                                    echo"";
                                                                                }
                                                                                ?>
                                                                                <input type="hidden" name="p_name[<?php echo $populated_products[$i]->id ?>]" value="<?php echo $populated_products[$i]->product->name; ?>"><?php echo $populated_products[$i]->product->name; ?>
                                                                            </span>
                                                                        </span>
                                                                        <span class="product__mfr">
                                                                            by <a class="link fontWeight--2" href="#">
                                                                                <?php echo $populated_products[$i]->vendor->name; ?>
                                                                            </a>
                                                                        </span>
                                                                        <span class="fontSize--s fontWeight--2">$
                                                                            <?php
                                                                            Debugger::debug($populated_products[$i]);
                                                                            if(!empty($_SESSION['user_buying_clubs'])){
                                                                                $clubPrice = $bcModel->getBestPrice($populated_products[$i]->product->id, $populated_products[$i]->product_pricing->vendor_id, $bcPrices, $_SESSION['user_buying_clubs'], $populated_products[$i]->product_pricing->retail_price);
                                                                            }
                                                                            Debugger::debug($clubPrice, '$clubPrice');
                                                                            if(!empty($clubPrice)){
                                                                                $populated_products[$i]->product_pricing->retail_price = $clubPrice;
                                                                            }
                                                                            if ($populated_products[$i]->product_pricing->retail_price > 0) {
                                                                                echo number_format(floatval($populated_products[$i]->product_pricing->retail_price), 2, ".", "");
                                                                            } else {
                                                                                echo number_format(floatval($populated_products[$i]->product_pricing->price), 2, ".", "");
                                                                            }
                                                                            ?></span>
                                                                            <span class="fontSize--s">(<?php echo $populated_products[$i]->vendor->name; ?>) <a class="link fontWeight--2 fontSize--xs modal--toggle vendor_change <?php if(!empty($clubPrice)) echo 'club--price'; ?>"  data-list_id="<?php echo $populated_products[$i]->id; ?>" data-product_id="<?php echo $populated_products[$i]->product_id; ?>" data-vendor_id="<?php echo $populated_products[$i]->vendor_id; ?>" data-target="#changeVendorsModal">Change</a></span>
                                                                        </div>
                                                                    </div>
                                                                    <!-- /Product -->
                                                                    <div class="d-block d-sm-none">
                                                                                <div class="input__combo ml-5 mt-2">
                                                                        <input type="number" class="input input--qty request_quantity" min="1" value="<?php echo $populated_products[$i]->quantity; ?>">
                                                                        <input type="hidden" class="update_list_id"  value="<?php echo $populated_products[$i]->id; ?>" >
                                                                        <div class="btn__group">
                                                                            <?php if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $tier1_2)))) { ?>
                                                                            <button class="btn btn--m btn--tertiary btn--icon modal--toggle list-to-cart" data-pid="<?php echo $populated_products[$i]->product->id; ?>" data-name="<?php echo $populated_products[$i]->product->name; ?>" data-price="<?php echo $populated_products[$i]->product_pricing->price; ?>" data-procolor="<?php echo $populated_products[$i]->product->color; ?>" data-vendor_id="<?php echo $populated_products[$i]->vendor->id ?>" data-target="#chooseLocationModal"><svg class="icon icon--cart-s"><use xlink:href="#icon-cart-s"></use></svg></button>
                                                                            <?php } ?>
                                                                            <button class="btn btn--m btn--tertiary btn--icon modal--toggle list-torequest" data-id="<?php echo $populated_products[$i]->product->id; ?>" data-vendor="<?php echo $populated_products[$i]->vendor->id; ?>" data-target="#chooseRequestListModal"><svg class="icon icon--list-s"><use xlink:href="#icon-list-s"></svg></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="align--center dn">
                                                                    <div class="input__combo">
                                                                        <input type="number" class="input input--qty request_quantity" min="1" value="<?php echo $populated_products[$i]->quantity; ?>">
                                                                        <input type="hidden" class="update_list_id"  value="<?php echo $populated_products[$i]->id; ?>" >
                                                                        <div class="btn__group">
                                                                            <?php if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $tier1_2)))) { ?>
                                                                            <button class="btn btn--m btn--tertiary btn--icon modal--toggle list-to-cart" data-pid="<?php echo $populated_products[$i]->product->id; ?>" data-name="<?php echo $populated_products[$i]->product->name; ?>" data-price="<?php echo $populated_products[$i]->product_pricing->price; ?>" data-procolor="<?php echo $populated_products[$i]->product->color; ?>" data-vendor_id="<?php echo $populated_products[$i]->vendor->id ?>" data-target="#chooseLocationModal"><svg class="icon icon--cart-s"><use xlink:href="#icon-cart-s"></use></svg></button>
                                                                            <?php } ?>
                                                                            <button class="btn btn--m btn--tertiary btn--icon modal--toggle list-torequest" data-id="<?php echo $populated_products[$i]->product->id; ?>" data-vendor="<?php echo $populated_products[$i]->vendor->id; ?>" data-target="#chooseRequestListModal"><svg class="icon icon--list-s"><use xlink:href="#icon-list-s"></svg></button>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                        <!-- /Requested Item -->
                                                    </tbody>
                                                </table>
                                                <?php if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $role_lists)))){ ?>
                                                <a class="link fontSize--s fontWeight--2 is--neg margin--m no--margin-l modal--toggle delete_name"
                                                data-id="<?php echo $list_view->listname; ?>" data-target="#deletePrepopulatedListModal" >Delete List</a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <?php } else { ?>
                                        <div id="locationContent" class="content col col--8-of-12 col--push-1-of-12">
                                            <div class="empty">
                                                Save items to the list by browsing for a product and clicking the "Save to Shopping List" button.
                                            </div>
                                            <?php
                                            if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $role_lists)))) {
                                                if ($populated_products != null) {
                                                    ?>
                                                    <a class="link fontSize--s fontWeight--2 is--neg margin--m no--margin-l modal--toggle delete_name"
                                                    data-id="<?php echo $list_view->listname; ?>" data-target="#deletePrepopulatedListModal" >Delete List</a>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <!-- /Content -->
                                </div>
                            </div>
                        </section>
                        <!-- /Main Content -->
                    </div>
                    <!-- /Content Section -->
                    <!-- Modals -->
                    <?php 
                    $this->load->view('templates/_inc/shared/modals/new-list.php'); 
                    $this->load->view('templates/_inc/shared/modals/rename-list.php'); 
                    $this->load->view('templates/_inc/shared/modals/delete-list.php');
                    $this->load->view('templates/_inc/shared/modals/choose-location.php');
                    $this->load->view('templates/_inc/shared/modals/choose-request-list.php');
                    $this->load->view('templates/_inc/shared/modals/change-shopping-list-vendor.php');
                    $this->load->view('templates/_inc/shared/modals/remove-shopping-list-items.php');
                    $this->load->view('templates/_inc/shared/modals/add-selected-shopping-items.php');
                    ?>




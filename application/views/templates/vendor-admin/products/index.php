<?php include(INCLUDE_PATH . '/_inc/header-vendor.php'); ?>

<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <section class="content__wrapper has--sidebar-l">
        <div class="content__main">
            <div class="row row--full-height">

                <!-- Sidebar -->
                <div class="sidebar col col--2-of-12" style="padding: 12px !important;">
                    <?php //include(INCLUDE_PATH . '/vendor-admin/_inc/nav.php'); ?>
                    <?php $this->load->view('templates/vendor-admin/_inc/nav.php'); ?>
                </div>
                <!-- /Sidebar -->

                <!-- Content Area -->
                <div class="content col col--9-of-12 col--push-1-of-12">
                    <form method="get" name="OrderingProduct" action="/vendor-products-dashboard">
                    <input type="hidden" name="site_id" value="<?php echo $siteId; ?>" id="site_id">
                        <div class="heading__group border--dashed">
                            <div class="wrapper">
                                <div class="wrapper__inner">
                                    <h3>Products</h3>
                                </div>
                                <div>
                                    <div class="wrapper__inner">
                                        <div class="input__group input__group--inline">
                                            <input id="site-search" class="input input__text" type="search" value="<?php echo $search ?>" placeholder="Search by product, SKU, description, etc..." name="search" required>
                                            <label for="site-search" class="label">
                                                <svg class="icon icon--search textColor--gray"><use xlink:href="#icon-search"></use></svg>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="controlsOrders" class="contextual__controls is--contextual  is--off">
                            <?php if ($vendor_products != null) { ?>
                                <ul class="list list--inline list--divided fontWeight--2 fontSize--s contextual__controls is--contextual is--off " style="margin-bottom: 10px;">
                                    <li class="item">
                                        <a class="link modal--toggle" data-target="#confirmProductActivationModal">Activate</a>
                                    </li>
                                    <li class="item">
                                        <a class="link modal--toggle" data-target="#confirmProductDeactivationModal">Deactivate</a>
                                    </li>
                                    <li class="item">
                                        <a class="link modal--toggle" data-target="#confirmHideMarketplaceModal">Toggle Display</a>
                                    </li>
                                   <!--  <li class="item">
                                        <a class="link modal--toggle" data-target="#confirmShowMarketplaceModal">Show MP</a>
                                    </li>
                                    <li class="item">
                                        <a class="link modal--toggle" data-target="#confirmProduct1HideModal">Toggle WL 1</a>
                                    </li>
                                    <li class="item">
                                        <a class="link modal--toggle" data-target="#confirmProduct2HideModal">Toggle WL 2</a>
                                    </li> -->
                                </ul>
                            <?php } ?>
                        </div>
                        <!-- Filters -->
                        <!--                    -->
                        <button type="button" id="ProductPriceFilterstop" class="btn btn--tertiary btn--s btn--icon margin--s no--margin-tb no--margin-l has--tip modal--toggle"  data-target="#filterVendorProductsModal" data-tip="Configure Filters" data-tip-position="top"><svg class="icon icon--settings"><use xlink:href="#icon-settings"></use></svg></button>
                        <ul class="list list--inline list--filters disp--ib">
                            <li class="item item--filter">
                                Showing All Products
    <!--                        <a class="filter--clear" href="#"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>-->
                            </li>
                        </ul>
                        <div class="select select--text float--right">
                            <label class="label">Order by: </label>
                            <!--id="productPrice_change" -->
                            <select name="order_by" onchange="document.OrderingProduct.submit();">
                                <option value="0" <?php echo ($order_by == 0) ? "selected" : ""; ?>>Alphabetical</option>
                                <option value="2" <?php echo ($order_by == 2) ? "selected" : ""; ?>>Price (High to Low)</option>
                                <option value="1" <?php echo ($order_by == 1) ? "selected" : ""; ?>> Price (Low to High)</option>
                            </select>
                        </div>
                        <!-- /Filters -->
                    </form>

                    <hr>
                    <div class="header-group">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <h3>
                                    <?php echo $siteInfo->name; ?>
                                    products
                                </h3>
                            </div>
                            <?php if(!empty($sites)) { ?>
                            <div class="wrapper__inner">
                                <div class="select">
                                    <select name="siteSelect" id="siteSelect">
                                        <option value="">Select store</option>
                                        <option value="0" <?php if($siteInfo->id == 0){ echo "selected"; }?>>Marketplace</option>
                                        <?php foreach($sites as $x => $site){
                                            echo '<option value="' . $site->id . '" ' . (($siteInfo->id == $site->id) ? "selected" : '') . '>' . $site->name . '</option>';
                                        } ?>
                                    </select>
                                </div>
                            </div>
                        <?php } ?>
                        </div>
                    </div>
                    <div style="overflow: hidden; overflow-x: scroll;">
                    <!-- Product List -->
                    <table class="table productPrice_row" data-controls="#controlsOrders">
                        <thead>
                            <tr>
                                <th width="3%">
                                    <label class="control control__checkbox">
                                        <input type="checkbox" class=" is--selector" id="selectAll">
                                        <div class="control__indicator"></div>
                                    </label>
                                </th>
                                <th>
                                    Product
                                </th>
                                <th>
                                    Status
                                </th>
                                <th>
                                    SKU
                                </th>
                                <th>
                                    Unit Price
                                </th>
                                <th>
                                    Promo
                                </th>
                                <th>
                                    Display
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Single Product -->
                            <?php if ($vendor_products != null) { ?>
                                <?php foreach ($vendor_products as $productsPricing) { ?>
                                    <tr class="vendor_products">
                                        <td>
                                            <label class="control control__checkbox">
                                                <input type="checkbox" name="checkboxRow" class="singleCheckbox" value="<?php echo $productsPricing->pricing_id; ?>">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </td>
                                        <td class="fontWeight--2">
                                            <a class="link" href="<?php echo base_url(); ?>product-pricing-vendorEdit?productPrice_id=<?php echo $productsPricing->pricing_id; ?>"><?php echo $productsPricing->name; ?></a>
                                        </td>
                                        <td>
                                            <?php echo ($productsPricing->status == 1) ? "Active" : "Inactive"; ?>
                                        </td>
                                        <td class="fontWeight--2">
                                            <?php echo $productsPricing->vendor_product_id; ?>
                                        </td>
                                        <td>
                                            <?php echo "$" . number_format(floatval($productsPricing->price > $productsPricing->retail_price ? $productsPricing->price : $productsPricing->retail_price), 2, ".", ""); ?>
                                        </td>
                                        <td>
                                            <?php echo ($productsPricing->active == 1) ? "Yes" : "No"; ?>
                                        </td>
                                        <td>
                                            <?php echo (empty($productsPricing->hidden_id)) ? "Show" : "Hide"; ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="5">
                                        No product(s) found
                                    </td>
                                </tr>
                            <?php } ?>
                            <!-- /Single Product -->
                        </tbody>
                    </table>
                    <!-- /Product List -->
                </div>
                    <?php echo $this->pagination->create_links(); ?>
                </div>
                <!-- /Content Area -->

            </div>
        </div>
    </section>
</div>
<!-- /Content Section -->

<script src="<?php echo base_url(); ?>assets/js/adminVendor.js"></script>

<!-- Modals -->

<?php $this->load->view('templates/_inc/shared/modals/filter-vendor-products.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/confirm-product-activation.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/confirm-product-deactivation.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/confirm-hide-marketplace-view.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/confirm-show-marketplace-view.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/confirm-products1-toggle.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/confirm-products2-toggle.php'); ?>

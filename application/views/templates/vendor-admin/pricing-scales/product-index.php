<?php include(INCLUDE_PATH . '/_inc/header-vendor.php'); ?>

<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <section class="content__wrapper has--sidebar-l">
        <div class="content__main">
            <div class="row row--full-height">

                <!-- Sidebar -->
                <div class="sidebar col col--2-of-12">
                    <?php //include(INCLUDE_PATH . '/' . (User_model::can($_SESSION['user_permissions'], 'is-admin') ? 'admin' : 'vendor-admin') . '/_inc/nav.php'); ?>
                     <?php
                        $folder = User_model::can($_SESSION['user_permissions'], 'is-admin') ? 'admin' : 'vendor-admin';
                        $this->load->view('templates/' . $folder . '/_inc/nav.php'); 

                    ?>
                </div>
                <!-- /Sidebar -->

                <!-- Content Area -->
                <div class="content col col--9-of-12 col--push-1-of-12">
                    <?php if(empty($vendorId)){ ?>
                        <form action="/pricing-scales/select-vendor" method="post">
                            <div class="select">
                                <select name="vendor_id" id="vendorSelect" data-pricing_scale_id="<?php echo $pricingScale->id; ?>" >
                                    <option value="">Select vendor</option>
                                    <?php foreach($vendors as $vendor){
                                        echo '<option value="'.$vendor->id.'">'.$vendor->name.'</option>';
                                    } ?>
                                </select>
                            </div>
                        </form>
                    <?php } else { ?>
                    <form method="get" name="OrderingProduct" action="/pricing-scales/manage-products">
                        <input type="hidden" name="id" value="<?php echo $pricingScale->id; ?>" >
                        <input type="hidden" name="vendorId" value="<?php echo $vendorId; ?>" >
                        <div class="heading__group border--dashed">
                            <div class="wrapper">
                                <div class="wrapper__inner">
                                    <h3>"<?php echo $pricingScale->name ?>" pricing</h3>
                                </div>
                                <div class="wrapper__inner">
                                    <div class="input__group input__group--inline">
                                        <input id="site-search" class="input input__text" type="search" value="<?php echo $search ?>" placeholder="Search by product, SKU, description, etc..." name="search" required>
                                        <label for="site-search" class="label">
                                            <svg class="icon icon--search textColor--gray"><use xlink:href="#icon-search"></use></svg>
                                        </label>
                                    </div>
                                </div>
                                <!-- <div id="controlsOrders" class="contextual__controls wrapper__inner align--right">
                                    <?php if ($vendor_products != null) { ?>
                                        <ul class="list list--inline list--divided fontWeight--2 fontSize--s is--contextual is--off">
                                            <li class="item">
                                                <a class="link modal--toggle" data-target="#confirmProductActivationModal">Activate</a>
                                            </li>
                                            <li class="item">
                                                <a class="link modal--toggle" data-target="#confirmProductDeactivationModal">Deactivate</a>
                                            </li>
                                            <li class="item">
                                                <a class="link modal--toggle" data-target="#confirmProductShowModal">Show</a>
                                            </li>
                                            <li class="item">
                                                <a class="link modal--toggle" data-target="#confirmProductHideModal">Hide</a>
                                            </li>
                                        </ul>
                                    <?php } ?>
                                </div> -->
                            </div>
                        </div>

                        <!-- Filters -->`
                        <!--                    -->
                        <button type="button" id="ProductPriceFilterstop" class="btn btn--tertiary btn--s btn--icon margin--s no--margin-tb no--margin-l has--tip modal--toggle"  data-target="#filterVendorProductsModal" data-tip="Configure Filters" data-tip-position="top"><svg class="icon icon--settings"><use xlink:href="#icon-settings"></use></svg></button>
                        <ul class="list list--inline list--filters disp--ib">
                            <li class="item item--filter">
                                Showing All Products
    <!--                        <a class="filter--clear" href="#"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>-->
                            </li>
                        </ul>
                        <!-- <div class="col col--3-of-12">
                            <a class="btn btn--s btn--primary is--pos btn--dir" href="/buying-club/export-vendor-products?clubId=<?php echo $buyingClub->id; ?>&vendorId=<?php echo $vendor_detail->vendor_id; ?>&userId=<?php echo $_SESSION['user_id']; ?>">Download my product list</a>
                        </div> -->
                        <div class="col col--4-of-12">
                            <a href="#" class="btn btn--s btn--primary btn--block addItems modal--toggle " data-target="#uploadBuyingClubProductsDataModal" data-type="product-pricings" data-user_id="<?php echo $_SESSION['user_id']; ?>" data-vendor_id="<?php echo $vendor_detail->vendor_id; ?>" data-club_id="<?php echo $buyingClub->id; ?>">Upload Product Pricings</a>
                        </div>
                        <div class="select select--text float--right">
                            <label class="label">Order by: </label>
                            <!--id="productPrice_change" -->
                            <select name="order_by" onchange="document.OrderingProduct.submit();">
                                <option value="0" <?php echo ($order_by == 0) ? "selected" : ""; ?>>Alphabetical</option>
                                <option value="2" <?php echo ($order_by == 2) ? "selected" : ""; ?>>Price (High to Low)</option>
                                <option value="1" <?php echo ($order_by == 1) ? "selected" : ""; ?>>Price (Low to High)</option>
                            </select>
                        </div>
                        <!-- /Filters -->
                    </form>

                    <hr>

                    <!-- Product List -->
                    <table class="table productPrice_row" data-controls="#controlsOrders">
                        <thead>
                            <tr>
                                <!-- <th width="3%">
                                    <label class="control control__checkbox">
                                        <input type="checkbox" class=" is--selector" id="selectAll">
                                        <div class="control__indicator"></div>
                                    </label>
                                </th> -->
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
                                    Sale Price
                                </th>
                                <th>
                                    Scale Price
                                </th>
                                <th>
                                    Fixed Minimum
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Single Product -->
                            <?php if ($vendor_products != null) {
                                foreach ($vendor_products as $productsPricing) {
                                    ?>
                                    <tr class="vendor_products">
                                        <!-- <td>
                                            <label class="control control__checkbox">
                                                <input type="checkbox" name="checkboxRow" class="singleCheckbox" value="<?php echo $productsPricing->id; ?>">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </td> -->
                                        <td class="fontWeight--2" >
                                            <a class="link" href="<?php echo base_url(); ?>product-pricing-vendorEdit?productPrice_id=<?php echo $productsPricing->id; ?>"><?php echo $productsPricing->name; ?></a>
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
                                            <?php echo "$" . number_format(floatval($productsPricing->price < $productsPricing->retail_price ? $productsPricing->price : $productsPricing->retail_price), 2, ".", ""); ?>
                                        </td>
                                        <td>
                                            <?php
                                                $discountPrice = floatval($productsPricing->price < $productsPricing->retail_price ? $productsPricing->price : $productsPricing->retail_price) / 100 * (100 - $pricingScale->percentage_discount);
                                                echo "$" . number_format((empty($productsPricing->club_price) ? $discountPrice : $productsPricing->club_price), 2);


                                            ?>
                                        </td>
                                        <td style="">
                                            <div class="input__group is--inline margin--xs no--margin-lr no--margin-t">
                                                <input name="discount" class="input club_price not--empty" data-vendor_id="<?php echo $vendor_detail->vendor_id; ?>" data-product_id="<?php echo $productsPricing->id; ?>" data-pricing_scale_id="<?php echo $pricingScale->id; ?>" type="text" value="<?php echo $productsPricing->scale_price; ?>" required>
                                                <label class="label" for="club_price">$</label>
                                            </div>
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
                    <?php echo $this->pagination->create_links();
                }?>
                </div>
                <!-- /Content Area -->

            </div>
        </div>
    </section>
</div>
<!-- /Content Section -->

<script src="/assets/js/pricingScales.js"></script>

<!-- Modals -->
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/upload-pricing-scale-products.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/filter-vendor-products.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/footer-vendor.php');?>

<?php $this->load->view('templates/_inc/shared/modals/upload-pricing-scale-products.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/filter-vendor-products.php'); ?>
<?php $this->load->view('templates/_inc/footer-vendor.php'); ?>

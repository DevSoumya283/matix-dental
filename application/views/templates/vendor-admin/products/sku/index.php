<?php include(INCLUDE_PATH . '/_inc/header-vendor.php'); ?>

<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>
    <!-- Breadcrumbs Bar -->
    <div class="bar padding--xs bg--lightest-gray">
        <div class="wrapper wrapper--fixed">
            <ul class="list list--inline list--breadcrumbs">
                <li class="item">
                    <a class="link" href="<?php echo base_url(); ?>vendor-products-dashboard">Products</a>
                </li>
                <li class="item is--active">
                    Details (<?php echo ($productName->mpn != null) ? $productName->mpn : "###"; ?>)
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breadcrumbs Bar -->
    <section class="content__wrapper has--sidebar-l">
        <div class="content__main">
            <div class="row row--full-height">

                <!-- Sidebar -->
                <div class="sidebar col col--2-of-12">
                    <?php //include(INCLUDE_PATH . '/vendor-admin/_inc/nav.php'); ?>
                    <?php $this->load->view('templates/vendor-admin/_inc/nav.php'); ?>
                </div>
                <!-- /Sidebar -->

                <!-- Content Area -->
                <div class="content col col--9-of-12 col--push-1-of-12">

                    <!-- Product Info Bar -->
                    <div class="heading__group border--dashed">
                        <h3>Product Details</h3>
                    </div>
                    <div class="card well" style="margin-top:16px;">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <!-- Product -->
                                <div class="product product--s row multi--vendor req--license padding--xxs">
                                    <div class="product__image col col--1-of-8 col--am">
                                        <?php if ($productName->product_image != null) { ?>
                                            <a href="<?php echo base_url(); ?>view-product?id=<?php echo $productName->id; ?>">
                                                <div class="product__thumb" style="background-image:url('<?php echo image_url(); ?>uploads/products/images/<?php echo $productName->product_image->photo; ?>');">
                                                </div>
                                            </a>
                                        <?php } else { ?>
                                            <div class="product__thumb" style="background-image:url('<?php echo image_url(); ?>assets/img/product-image.png');">
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="product__data col col--7-of-8 col--am">
                                        <a class="link fontWeight--2" href="<?php echo base_url(); ?>view-product?id=<?php echo $productName->id; ?>">
                                            <span class="product__name"><?php echo $productName->name; ?></span>
                                        </a>
                                        <span class="product__mfr">
                                            by <a class="link fontWeight--2" href="#"><?php echo $productName->manufacturer; ?></a>
                                        </span>
                                    </div>
                                </div>
                                <!-- /Product -->
                            </div>
                            <div class="wrapper__inner align--right">
                                <div class="tab__group">
                                    <label class="tab ActivateProduct" data-product_pricing_id="<?php echo $productPricing->id; ?>" data-activate="1">
                                        <input type="radio" name="productStatus"  value="1"<?php if ($productPricing->active == 1) echo 'checked'; ?>>
                                        <span>Active</span>
                                    </label>
                                    <label class="tab ActivateProduct" data-product_pricing_id="<?php echo $productPricing->id; ?>" data-activate="0">
                                        <input type="radio" name="productStatus" value="0"<?php if ($productPricing->active == 0) echo 'checked'; ?>>
                                        <span>Inactive</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Product Info Bar -->

                    <hr>


                    <!-- Pricing -->
                    <div class="row">
                        <div class="col col--3-of-12 col--am">
                            <h3>Pricing</h3>
                        </div>
                        <div class="col col--9-of-12 col--am">
                            <div class="well bg--lightest-gray">
                                <form id="formPrice" action="<?php echo base_url(); ?>productPrice-vendor-update" method="post">
                                    <input type="hidden" name="productPricing_id" value="<?php echo $productPricing->id; ?>" required>

                                    <div class="input__group is--inline">
                                        <input id="productPrice" name="productPrice" class="input input--currency not--empty" type="text" value="<?php echo $productPricing->retail_price; ?>" data-prefix="$">
                                        <label class="label" for="productPrice">Unit Price</label>
                                    </div>
                                    <br />
                                    <div class="input__group is--inline">
                                        <input id="salePrice" name="salePrice" class="input input--currency not--empty" type="text" value="<?php echo $productPricing->price; ?>" data-prefix="$">
                                        <label class="label" for="salePrice">Sale Price</label>
                                    </div>
                                    <div class="input__group is--inline">
                                        <label class="control control__checkbox">
                                            <input type="checkbox" name="exclude_from_marketplace" class="singleCheckbox" value="1" <?php if ($productPricing->exclude_from_marketplace) { echo 'checked="checked"'; } ?> >
                                            <div class="control__indicator"></div>
                                            Hide from Marketplace
                                        </label>
                                    </div>
                                    <hr>
                                    <button class="btn btn--primary btn--block btn--m save--toggle form--submit" data-target="#formPrice">Update Price</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /Pricing -->

                    <hr>

                    <!-- Promos -->
                    <div class="row">
                        <div class="col col--3-of-12 col--am">
                            <h3>Promotions</h3>
                            <p>Build your own custom promotion by using the recipe builder.</p>
                        </div>
                        <div class="col col--9-of-12 col--am">
                            <div class="well bg--lightest-gray" style="overfow:visible;">

                                <form id="formPromo" action="<?php echo base_url(); ?>promoCode-vendor-update" method="post">
                                    <!-- Title -->
                                    <input type="hidden" name="product_id" value="<?php echo $productPricing->product_id; ?>" required>
                                    <h5 class="title title--dark">Title</h5>
                                    <div class="row">
                                        <div class="col col--12-of-12 col--am">
                                            <?php if ($promoCodes != null) { ?>
                                                <div class="input__group is--inline">
                                                    <input id="promoThreshold" name="promoTitle" class="input not--empty" type="text" value="<?php echo $promoCodes->title; ?>" required>
                                                    <label class="label" for="promoTitle">Promo Title</label>
                                                </div>
                                            <?php } else { ?>
                                                <div class="input__group is--inline">
                                                    <input id="promoThreshold" name="promoTitle" class="input not--empty" type="text" value="" required>
                                                    <label class="label" for="promoTitle">Promo Title</label>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <br>
                                    <h5 class="title title--dark">Code</h5>
                                    <div class="row">
                                        <div class="col col--12-of-12 col--am">
                                            <?php if ($promoCodes != null) { ?>
                                                <div class="input__group is--inline">
                                                    <input id="promoThreshold" name="promoCode" class="input not--empty" type="text" value="<?php echo $promoCodes->code; ?>" required>
                                                    <label class="label" for="promoTitle">Promo Code</label>
                                                </div>
                                            <?php } else { ?>
                                                <div class="input__group is--inline">
                                                    <input id="promoThreshold" name="promoCode" class="input not--empty" type="text" value="" required>
                                                    <label class="label" for="promoTitle">Promo Code</label>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <br>
                                    <!-- /Title -->
                                    <!-- Discount -->
                                    <h5 class="title title--dark">Discount</h5>
                                    <div class="row">
                                        <div class="col col--5-of-12 col--am">
                                            <div class="row">
                                                <div class="col col--5-of-8 col--am">
                                                    <div class="input__group is--inline">
                                                        <input type="hidden" name="productPricing_id" value="<?php echo $productPricing->id; ?>" required>
                                                        <?php if ($promoCodes != null) { ?>
                                                            <input type="hidden" name="promo_id" value="<?php echo $promoCodes->id; ?>" required>
                                                        <?php } ?>
                                                        <?php if ($promoCodes != null) { ?>
                                                            <input id="promoValue" name="promoValue" class="input" type="text" value="<?php echo $promoCodes->discount; ?>" min="0.00" required>
                                                        <?php } else { ?>
                                                            <input id="promoValue" name="promoValue" class="input" type="text" value="" min="0.00" required><!-- min="0.01" -->
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="col col--3-of-8 col--am">
                                                    <?php if ($promoCodes != null) { ?>
                                                        <div class="select">
                                                            <select name="discount_type">
                                                                <option value="1"<?php echo ($promoCodes->discount_type == "%") ? "selected" : ""; ?>>%</option>
                                                                <option value="2"<?php echo ($promoCodes->discount_type == "$") ? "selected" : ""; ?>>$</option>
                                                            </select>
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="select">
                                                            <select name="discount_type">
                                                                <option value="1">%</option>
                                                                <option value="2">$</option>
                                                            </select>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col col--2-of-12 col--am align--center">
                                            off
                                        </div>
                                        <div class="col col--5-of-12 col--am">
                                            <?php if ($promoCodes != null) { ?>
                                                <div class="select">
                                                    <select name="discount_on">
                                                        <option value="Final Price"<?php if ($promoCodes->discount_on == 'Final Price') echo "selected"; ?>>Final Price</option>
                                                        <option value="Shipping"<?php if ($promoCodes->discount_on == 'Shipping') echo "selected"; ?>>Shipping</option>
                                                    </select>
                                                </div>
                                            <?php } else { ?>
                                                <div class="select">
                                                    <select name="discount_on">
                                                        <option value="Final Price">Final Price</option>
                                                        <option value="Shipping">Shipping</option>
                                                    </select>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <!-- /Discount -->

                                    <br>
                                    <!-- Threshold -->
                                    <h5 class="title title--dark">Threshold</h5>
                                    <div class="row">
                                        <div class="col col--12-of-12 col--am">
                                            <?php if ($promoCodes != null) { ?>
                                                <div class="input__group is--inline">
                                                    <input id="promoThreshold" name="promoThreshold" class="input not--empty" type="number" value="<?php echo $promoCodes->threshold_count; ?>" min="1" required>
                                                    <label class="label" for="promoThreshold">Minimum Qty</label>
                                                </div>
                                            <?php } else { ?>
                                                <div class="input__group is--inline">
                                                    <input id="promoThreshold" name="promoThreshold" class="input not--empty" type="number" value="" min="1" required>
                                                    <label class="label" for="promoThreshold">Minimum Qty</label>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <!-- /Threshold -->

                                    <br>

                                    <!-- Schedule & Expiry -->
                                    <h5 class="title title--dark">Schedule &amp; Expiration Date</h5>
                                    <div class="row field__group">
                                        <div class="col col--12-of-12 col--am">
                                            <?php if ($promoCodes != null) { ?>
                                                <?php $startdate = date('m-d-Y', strtotime($promoCodes->start_date)); ?>
                                                <label class="control control__checkbox">
                                                    <input class="control__conditional" name="promo_dates" type="checkbox" data-target="#condSchedule" value="range" <?php
                                                    if (($startdate != null) && ($startdate != "01-01-1970") && ($startdate != "00-00-0000") && ($startdate != "11-30--0001")) {
                                                        echo " checked";
                                                    } else {
                                                        "";
                                                    }
                                                    ?>>
                                                    <div class="control__indicator"></div>
                                                    Run this promo during a date-range
                                                </label>
                                            <?php } else { ?>
                                                <label class="control control__checkbox">
                                                    <input class="control__conditional" name="promo_dates" type="checkbox" value="range" data-target="#condSchedule">
                                                    <div class="control__indicator"></div>
                                                    Run this promo during a date-range
                                                </label>
                                            <?php } ?>
                                            <?php if ($promoCodes != null) { ?>
                                                <div id="condSchedule" class="is--conditional starts--hidden no--pad" style="border:none; <?php
                                                if (($startdate != null) && ($startdate != "01-01-1970") && ($startdate != "00-00-0000") && ($startdate != "11-30--0001")) {
                                                    echo " display : block; ";
                                                } else {
                                                    "";
                                                }
                                                ?>">
                                                    <div class="input__group input__group--date-range is--inline input-daterange">
                                                        <div class="range__icon">
                                                            <svg class="icon icon--calendar"><use xlink:href="#icon-calendar"></use></svg>
                                                        </div>
                                                        <div class="range__fields">
                                                            <input type="text" class="input input--date" placeholder="MM/DD/YYYY" name="start_date" value="<?php echo (strtotime($promoCodes->start_date) > 0) ? date('m-d-Y', strtotime($promoCodes->start_date)) : ""; ?>" required>
                                                            <input  type="text" class="input input--date" placeholder="MM/DD/YYYY" name="end_date" value="<?php echo (strtotime($promoCodes->end_date) > 0) ? date('m-d-Y', strtotime($promoCodes->end_date)) : ""; ?>" required>
                                                        </div>
                                                    </div>
                                                    <br>
                                                </div>
                                            <?php } else { ?>
                                                <div id="condSchedule" class="is--conditional starts--hidden no--pad" style="border:none;">
                                                    <div class="input__group input__group--date-range is--inline input-daterange">
                                                        <div class="range__icon">
                                                            <svg class="icon icon--calendar"><use xlink:href="#icon-calendar"></use></svg>
                                                        </div>
                                                        <div class="range__fields">
                                                            <input type="text" class="input input--date" placeholder="MM/DD/YYYY" name="start_date" value="" required>
                                                            <input type="text" class="input input--date" placeholder="MM/DD/YYYY" name="end_date" value="" required>
                                                        </div>
                                                    </div>
                                                    <br>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="row field__group">
                                        <div class="col col--12-of-12 col--am">
                                            <?php if ($promoCodes != null) { ?>
                                                <?php
                                                $startdate = date('m-d-Y', strtotime($promoCodes->start_date));
                                                $endDate = date('m-d-Y', strtotime($promoCodes->end_date));
                                                ?>
                                                <label class="control control__checkbox">
                                                    <input class="control__conditional" name="promo_dates" value="end_date_only" type="checkbox" data-target="#condExpiry" <?php
                                                    if ((($startdate == "01-01-1970") || ($startdate == null) || ($startdate == "00-00-0000") || ($startdate == "11-30--0001")) && (($endDate != "01-01-1970") && ($endDate != null) && ($endDate != "00-00-0000") && ($endDate != "11-30--0001"))) {
                                                        echo " checked";
                                                    } else {
                                                        "";
                                                    }
                                                    ?>>
                                                    <div class="control__indicator"></div>
                                                    Promo will expire on a set date
                                                </label>
                                            <?php } else { ?>
                                                <label class="control control__checkbox">
                                                    <input class="control__conditional" name="promo_dates" type="checkbox" value="end_date_only" data-target="#condExpiry">
                                                    <div class="control__indicator"></div>
                                                    Promo will expire on a set date
                                                </label>
                                            <?php } ?>
                                            <?php
                                            if ($promoCodes != null) {
                                                $endDate = date('m-d-Y', strtotime($promoCodes->end_date));
                                                ?>
                                                <div id="condExpiry" class="is--conditional starts--hidden no--pad" style="border:none; <?php
                                                if ((($startdate == null) || ($startdate == "01-01-1970") || ($startdate == "00-00-0000") || ($startdate == "11-30--0001")) && (($endDate != "01-01-1970") && ($endDate != null) && ($endDate != "00-00-0000") && ($endDate != "11-30--0001"))) {
                                                    echo " display : block; ";
                                                } else {
                                                    "";
                                                }
                                                ?>">
                                                    <div class="wrapper">
                                                        <div class="wrapper__inner has--icon">
                                                            <svg class="icon icon--calendar"><use xlink:href="#icon-calendar"></use></svg>
                                                        </div>
                                                        <div class="wrapper__inner">
                                                            <input type="text" class="input input--date" placeholder="MM/DD/YYYY" name="end_date_only" value="<?php echo (strtotime($promoCodes->end_date) > 0) ? date('m-d-Y', strtotime($promoCodes->end_date)) : ""; ?>" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } else { ?>
                                                <div id="condExpiry" class="is--conditional starts--hidden no--pad" style="border:none;">
                                                    <div class="wrapper">
                                                        <div class="wrapper__inner has--icon">
                                                            <svg class="icon icon--calendar"><use xlink:href="#icon-calendar"></use></svg>
                                                        </div>
                                                        <div class="wrapper__inner">
                                                            <input type="text" class="input input--date" placeholder="MM/DD/YYYY" name="end_date_only" value="" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <!-- /Schedule & Expiry -->

                                    <br>

                                    <!-- Additional Options -->
                                    <h5 class="title title--dark">Additional Options</h5>
                                    <!-- Free Products -->
                                    <div class="row field__group">
                                        <div class="col col--12-of-12 col--am">
                                            <?php if ($promoCodes != null && $promoCodes->product_free == "1") { ?>
                                                <label class="control control__checkbox">
                                                    <input class="control__conditional" type="checkbox" name="product_free" <?php echo ($promoCodes->product_free == 1) ? "checked" : " "; ?> value="1" data-target="#condProduct">
                                                    <div class="control__indicator"></div>
                                                    Offer a free product with this promotion
                                                </label>
                                            <?php } else { ?>
                                                <label class="control control__checkbox">
                                                    <input class="control__conditional" type="checkbox" name="product_free" value="1" data-target="#condProduct">
                                                    <div class="control__indicator"></div>
                                                    Offer a free product with this promotion
                                                </label>
                                            <?php }
                                            Debugger::debug($promoCodes); ?>
                                            <?php if ($promoCodes != null && $promoCodes->product_free == "1") { ?>
                                                <div id="condProduct" class="is--conditional <?php echo ($promoCodes->product_free == "1") ? "" : "starts--hidden"; ?> no--pad" style="<?php echo ($promoCodes->product_free == "1") ? " border:inline; " : " border:none;"; ?>">
                                                    <div class="input__group input__group--inline has--dropdown">
                                                        <input class="input input__text product_SearchId" type="hidden" placeholder="free product id" name="free_product_id" value="<?php echo $promoCodes->free_product_id; ?>">
                                                        <input id="product-search" class="input input__text product_Search <?php echo ($promoCodes->product_free == "1") ? "not--empty valid" : ""; ?>"  type="search" placeholder="Search by product, manufacturer, SKU, etc…" name="search" value="<?php echo $promoCodes->free_product_name; ?>" required>
                                                        <label for="product-search" class="label">
                                                            <svg class="icon icon--search textColor--gray"><use xlink:href="#icon-search"></use></svg>
                                                        </label>
                                                        <div id="product_list" class="input__dropdown" style="display: none; background: #fff; padding:5px 10px;"></div>
                                                    </div>
                                                    <br>
                                                </div>
                                            <?php } else { ?>
                                                <div id="condProduct" class="is--conditional starts--hidden no--pad" style="border:none;">
                                                    <div class="input__group input__group--inline has--dropdown">
                                                        <input class="input input__text product_SearchId" type="hidden" name="free_product_id" value="">
                                                        <input id="product-search" class="input input__text product_Search" type="search" autocomplete="off" placeholder="Search by product, manufacturer, SKU, etc…" name="search" required>
                                                        <label for="product-search" class="label">
                                                            <svg class="icon icon--search textColor--gray"><use xlink:href="#icon-search"></use></svg>
                                                        </label>
                                                        <div id="product_list" class="input__dropdown" style="display: none; background: #fff; padding:5px 10px;"></div>
                                                    </div>
                                                    <br>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="row field__group">
                                        <div class="col col--12-of-12 col--am">
                                            <?php if ($promoCodes != null) { ?>
                                                <label class="control control__checkbox">
                                                    <input class="control__conditional" type="checkbox" name="use_with_promos" value="1"<?php echo ($promoCodes->use_with_promos == 1) ? "checked" : ""; ?>>
                                                    <div class="control__indicator"></div>
                                                    Allow use with other promotions
                                                </label>
                                            <?php } else { ?>
                                                <label class="control control__checkbox">
                                                    <input class="control__conditional" type="checkbox" name="use_with_promos" value="0">
                                                    <div class="control__indicator"></div>
                                                    Allow use with other promotions
                                                </label>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="row field__group">
                                        <div class="col col--12-of-12 col--am">
                                            <?php if ($promoCodes != null) { ?>
                                                <label class="control control__checkbox">
                                                    <input class="control__conditional" type="checkbox" name="manufacturer_coupon" value="1"<?php echo ($promoCodes->manufacturer_coupon == 1) ? "checked" : ""; ?>  data-target="#condInstructions">
                                                    <div class="control__indicator"></div>
                                                    This is a manufacturer promotion/coupon
                                                </label>
                                                <div id="condInstructions" class="is--conditional starts--hidden no--pad" style="<?php echo ($promoCodes->manufacturer_coupon == 1) ? "display:inline;" : "border:none;"; ?>">
                                                    <textarea name="conditions" class="input input--m" maxlength="400" placeholder="Enter your instructions here... (max 400 chars.)" required><?php echo trim($promoCodes->conditions); ?></textarea>
                                                    <br>
                                                </div>
                                            <?php } else { ?>
                                                <label class="control control__checkbox">
                                                    <input class="control__conditional" type="checkbox" name="manufacturer_coupon" value="1"   data-target="#condInstructions">
                                                    <div class="control__indicator"></div>
                                                    This is a manufacturer promotion/coupon
                                                </label>
                                                <div id="condInstructions" class="is--conditional starts--hidden no--pad" style="">
                                                    <textarea name="conditions" class="input input--m" maxlength="400" placeholder="Enter your instructions here... (max 400 chars.)" required></textarea>
                                                    <br>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <!-- /Additional Options -->
                                    <br>
                                    <hr>
                                    <div class="row no--margin-l">
                                        <button class="btn btn--primary btn--m btn--block save--toggle form--submit" data-target="#formPromo">Save Promotion</button>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>
                    <!-- /Promos -->

                    <hr>

                    <!-- Vendor SKU -->
                    <div class="row">
                        <div class="col col--3-of-12 col--am">
                            <h3>SKU</h3>
                            <p>This should be your own unique identifier.</p>
                        </div>
                        <div class="col col--9-of-12 col--am">
                            <div class="well bg--lightest-gray">
                                <form id="formPrice" action="<?php echo base_url(); ?>productPriceSku-update" method="post">
                                    <?php if ($productPricing != null) { ?>
                                        <div class="input__group is--inline">
                                            <input type="hidden" name="productPricing_id" value="<?php echo $productPricing->id; ?>" required>
                                            <input id="productSKU" name="productSKU" class="input not--empty" type="text" value="<?php echo $productPricing->vendor_product_id; ?>" required>
                                            <label class="label" for="productSKU">Product SKU</label>
                                        </div>
                                    <?php } else { ?>
                                        <div class="input__group is--inline">
                                            <input type="hidden" name="productPricing_id" value="" required>
                                            <input id="productSKU" name="productSKU" class="input not--empty" type="text" value="" required>
                                            <label class="label" for="productSKU">Product SKU</label>
                                        </div>
                                    <?php } ?>
                                    <hr>
                                    <button class="btn btn--primary btn--block btn--m save--toggle form--submit" data-target="#formPrice">Update SKU</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /Vendor SKU -->
                    <hr>
                    <!-- Universal Product Info -->
                    <div class="row">
                        <div class="col col--3-of-12 col--am">
                            <h3>Product Info</h3>
                            <p>This information is searchable by customers and is not editable.</p>
                        </div>
                        <div class="col col--9-of-12 col--am">
                            <div class="well bg--lightest-gray">
                                <!-- Product Description -->
                                <div id="description">
                                    <h3 class="title textColor--dark-gray">
                                        <svg class="icon icon--details"><use xlink:href="#icon-details"></use></svg>
                                        Product Description
                                    </h3>
                                    <?php echo $productName->description; ?>
                                </div>
                                <!-- /Product Description -->

                                <br><br>

                                <!-- Product Details -->
                                <div id="details">
                                    <h3 class="title textColor--dark-gray">
                                        <svg class="icon icon--info"><use xlink:href="#icon-info"></use></svg>
                                        Product Details
                                    </h3>
                                    <table class="table table--horizontal table--align-lr">
                                        <tbody>
                                            <?php if ($productName->manufacturer != null) { ?>
                                                <tr>
                                                    <td width="40%">Manufacturer</td>
                                                    <td width="60%"><?php echo $productName->manufacturer; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->license_required != null) { ?>
                                                <tr>
                                                    <td>License Required</td>
                                                    <td><?php echo $productName->license_required; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productPricing->quantity != null) { ?>
                                                <tr>
                                                    <td>Quantity/Box</td>
                                                    <td><?php echo $productPricing->quantity; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->returnable != null) { ?>
                                                <tr>
                                                    <td>Returnable</td>
                                                    <td><?php echo ucfirst($productName->returnable); ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->weight != null) { ?>
                                                <tr>
                                                    <td>Weight</td>
                                                    <td><?php echo $productName->weight; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->size != null) { ?>
                                                <tr>
                                                    <td>Size</td>
                                                    <td><?php echo $productName->size; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->color != null) { ?>
                                                <tr>
                                                    <td>Color</td>
                                                    <td><?php echo $productName->color; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->quantity_per_box != null) { ?>
                                                <tr>
                                                    <td>Quantity Per Box</td>
                                                    <td><?php echo $productName->quantity_per_box; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->fluoride != null) { ?>
                                                <tr>
                                                    <td>Fluoride</td>
                                                    <td><?php echo $productName->fluoride; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->flavor != null) { ?>
                                                <tr>
                                                    <td>Flavor</td>
                                                    <td><?php echo $productName->flavor; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->shade != null) { ?>
                                                <tr>
                                                    <td>Shade</td>
                                                    <td><?php echo $productName->shade; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->grit != null) { ?>
                                                <tr>
                                                    <td>Grit</td>
                                                    <td><?php echo $productName->grit; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->set_rate!= null) { ?>
                                                <tr>
                                                    <td>Set Rate</td>
                                                    <td><?php echo $productName->set_rate; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->viscosity != null) { ?>
                                                <tr>
                                                    <td>Viscosity</td>
                                                    <td><?php echo $productName->viscosity; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->firmness != null) { ?>
                                                <tr>
                                                    <td>Firmness</td>
                                                    <td><?php echo $productName->firmness; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->grit != null) { ?>
                                                <tr>
                                                    <td>Grit</td>
                                                    <td><?php echo $productName->grit; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->handle_size != null) { ?>
                                                <tr>
                                                    <td>Handle Size</td>
                                                    <td><?php echo $productName->handle_size; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->handle_finish != null) { ?>
                                                <tr>
                                                    <td>Handle Finish</td>
                                                    <td><?php echo $productName->handle_finish; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->tip_finish != null) { ?>
                                                <tr>
                                                    <td>Tip Finish</td>
                                                    <td><?php echo $productName->tip_finish; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->tip_diameter != null) { ?>
                                                <tr>
                                                    <td>Tip Diameter</td>
                                                    <td><?php echo $productName->tip_diameter; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->tip_material != null) { ?>
                                                <tr>
                                                    <td>Tip Material</td>
                                                    <td><?php echo $productName->tip_material; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->head_diameter != null) { ?>
                                                <tr>
                                                    <td>Head Diameter</td>
                                                    <td><?php echo $productName->head_diameter; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->head_length != null) { ?>
                                                <tr>
                                                    <td>Head Length</td>
                                                    <td><?php echo $productName->head_length; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->diameter != null) { ?>
                                                <tr>
                                                    <td>Diameter</td>
                                                    <td><?php echo $productName->diameter; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->arch != null) { ?>
                                                <tr>
                                                    <td>Arch</td>
                                                    <td><?php echo $productName->arch; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->shaft_dimensions != null) { ?>
                                                <tr>
                                                    <td>Shaft Dimensions</td>
                                                    <td><?php echo $productName->shaft_dimensions; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->shaft_description != null) { ?>
                                                <tr>
                                                    <td>Shaft Description</td>
                                                    <td><?php echo $productName->shaft_description; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->blade_description != null) { ?>
                                                <tr>
                                                    <td>Blade Description</td>
                                                    <td><?php echo $productName->blade_description; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->anatomic_use != null) { ?>
                                                <tr>
                                                    <td>Anatomic Use</td>
                                                    <td><?php echo $productName->anatomic_use; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->instrument_description != null) { ?>
                                                <tr>
                                                    <td>Instrument Description</td>
                                                    <td><?php echo $productName->instrument_description; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->palm_thickness != null && $productName->palm_thickness != '0') { ?>
                                                <tr>
                                                    <td>Palm Thickness</td>
                                                    <td><?php echo $productName->palm_thickness; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->finger_thickness != null) { ?>
                                                <tr>
                                                    <td>Finger Thickness</td>
                                                    <td><?php echo $productName->finger_thickness; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->texture != null) { ?>
                                                <tr>
                                                    <td>Texture</td>
                                                    <td><?php echo $productName->texture; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->delivery_system != null) { ?>
                                                <tr>
                                                    <td>Delivery System</td>
                                                    <td><?php echo $productName->delivery_system; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->volume != null) { ?>s
                                                <tr>
                                                    <td>Volume</td>
                                                    <td><?php echo $productName->volume; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->dimensions != null) { ?>
                                                <tr>
                                                    <td>Dimensions</td>
                                                    <td><?php echo $productName->dimensions; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->handle_finish != null) { ?>
                                                <tr>
                                                    <td>Handle Finish</td>
                                                    <td><?php echo $productName->handle_finish; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->stone_type != null) { ?>
                                                <tr>
                                                    <td>Stone Type</td>
                                                    <td><?php echo $productName->stone_type; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->stone_separation_time != null) { ?>
                                                <tr>
                                                    <td>Stone Separation Time</td>
                                                    <td><?php echo $productName->stone_separation_time; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->setting_time != null) { ?>
                                                <tr>
                                                    <td>Setting Time</td>
                                                    <td><?php echo $productName->setting_time; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->band_thickness != null) { ?>
                                                <tr>
                                                    <td>Band Thickness</td>
                                                    <td><?php echo $productName->band_thickness; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->contents != null) { ?>
                                                <tr>
                                                    <td>Contents</td>
                                                    <td><?php echo $productName->contents; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->tax_per_state != null) { ?>
                                                <tr>
                                                    <td>Tax Per State</td>
                                                    <td><?php echo $productName->tax_per_state; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($productName->average_rating != null) { ?>
                                                <tr>
                                                    <td>Average Rating</td>
                                                    <td><?php echo $productName->average_rating; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /Product Details -->
                            </div>
                        </div>
                    </div>
                    <!-- /Universal Product Info -->
                    <!--
                        NOTE: The following data is not editable by the vendor and is taken directly from the universal product data.
                    -->
                </div>
                <!-- /Content Area -->

            </div>
        </div>
    </section>
</div>
<!-- /Content Section -->

<?php //include(INCLUDE_PATH . '/_inc/footer-vendor.php'); ?>
<?php $this->load->view('templates/_inc/footer-vendor.php'); ?>

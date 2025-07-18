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

                    <!-- Promo Codes -->
                    <div class="heading__group border--dashed">
                        <div class="wrapper">
                            <form method="get" action="<?php echo base_url(); ?>promoCode-active-State">
                                <div class="wrapper__inner">
                                    <ul class="list list--inline list--divided">
                                        <li class="item">
                                            <h3>Product Promotions</h3>
                                        </li>
                                        <li class="item">
                                            <div class="select select--text">
                                                <label class="label">Showing:</label>
                                                <select name="select" onchange="this.form.submit()">
                                                    <option value="0"<?php echo ($select == "0") ? "selected" : "" ?>>All</option>
                                                    <option value="1"<?php echo ($select == "1") ? "selected" : "" ?>>Active</option>
                                                    <option value="2"<?php echo ($select == "2") ? "selected" : "" ?>>Inactive</option>
                                                    <option value="3"<?php echo ($select == "3") ? "selected" : "" ?>>Expired</option>
                                                </select>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </form>
                            <div id="controlsPromos" class="contextual__controls wrapper__inner align--right">
                                <button class="btn btn--s btn--tertiary contextual--hide modal--toggle" data-target="#createNewProductModal">Create New</button>
                                <ul class="list list--inline list--divided is--contextual is--off">
                                    <li class="item">
                                        <a class="link modal--toggle is--contextual is--off" data-target="#confirmPromoActivationModal">Activate</a>
                                    </li>
                                    <li class="item">
                                        <a class="link modal--toggle is--contextual is--off" data-target="#confirmPromoDeactivationModal">Deactivate</a>
                                    </li>
                                    <li class="item">
                                        <a class="link modal--toggle is--contextual is--off is--neg" data-target="#deletePromoCodeProductModal">Delete</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div style="overflow: hidden; overflow-x: scroll;">
                    <table class="table" data-controls="#controlsPromos">
                        <thead>
                            <tr>
                                <th width="3%">
                                    <label class="control control__checkbox">
                                        <input type="checkbox" <?php echo ($promoCodes_active != null) ? "class='is--selector'" : ""; ?> id="selectAll">
                                        <div class="control__indicator"></div>
                                    </label>
                                </th>
                                <th>
                                    Product
                                </th>
                                <th>
                                    Description
                                </th>
                                <th>
                                    Used
                                </th>
                                <th>
                                    Expiration
                                </th>
                                <th>
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Single Promo -->
                            <?php if ($promoCodes_active != null) { ?>
                                <?php foreach ($promoCodes_active as $active) { ?>
                                    <tr>
                                        <td>
                                            <label class="control control__checkbox">
                                                <input type="checkbox" name="checkboxRow" class="singleCheckbox" value="<?php echo $active->id; ?>">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </td>
                                        <td>
                                            <a class="link fontWeight--2" href="<?php echo base_url(); ?>product-pricing-vendorEdit?productPrice_id=<?php echo $active->productPricing_id; ?>"><?php echo $active->product; ?></a>
                                        </td>
                                        <td>
                                            <?php echo ($active->title != null) ? $active->title : ""; ?>
                                        </td>
                                        <td>
                                            <?php echo ($active->used != null) ? $active->used : "0"; ?>
                                        </td>
                                        <td>
                                            <?php echo ($active->end_date != "1970-01-01" && $active->end_date != "0000-00-00") ? date('M d, Y', strtotime($active->end_date)) : "N/A"; ?>
                                        </td>
                                        <td>
                                            <?php echo ($active->active == 1) ? "Active" : "Inactive"; ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="5">
                                        No Promo Codes Created.
                                    </td>
                                </tr>
                            <?php } ?>
                            <!-- Single Promo -->
                        </tbody>
                    </table>
                    </div>
                    <!-- /Promo Codes -->
                    <?php echo $this->pagination->create_links(); ?>
                </div>
                <!-- /Content Area -->

            </div>
        </div>
    </section>
</div>
<!-- /Content Section -->

<!-- Modals -->
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/confirm-promo-activation.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/confirm-promo-deactivation.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/delete-promoCode-product.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/footer-vendor.php'); ?>

<?php $this->load->view('templates/_inc/shared/modals/confirm-promo-activation.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/confirm-promo-deactivation.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/delete-promoCode-product.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/create-new-product.php'); ?>
<?php //$this->load->view('templates/_inc/footer-vendor.php'); ?>



<?php include(INCLUDE_PATH . '/_inc/header-vendor.php'); ?>

<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <section class="content__wrapper has--sidebar-l">
        <div class="content__main">
            <div class="row row--full-height">

                <!-- Sidebar -->
                <div class="sidebar col col--2-of-12"style="padding: 12px;">
                    <?php //include(INCLUDE_PATH . '/vendor-admin/_inc/nav.php'); ?>
                    <?php $this->load->view('templates/vendor-admin/_inc/nav.php'); ?>
                </div>
                <!-- /Sidebar -->

                <!-- Content Area -->
                <div class="content col col--9-of-12 col--push-1-of-12">
                    <!-- Promo Codes -->
                    <div class="heading__group border--dashed">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <h3>Shipping Methods</h3>
                            </div>
                            <div id="controlsShipping" class="contextual__controls wrapper__inner align--right">
                                <button class="btn btn--s btn--tertiary contextual--hide modal--toggle" data-target="#createShippingMethodModal">Create New</button>
                                <ul class="list list--inline list--divided is--contextual is--off">
                                    <li class="item">
                                        <a class="link modal--toggle is--contextual is--off is--neg" data-target="#deleteShippingMethodModal">Delete</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div style="overflow: hidden; overflow-x: scroll;">
                    <table class="table" data-controls="#controlsShipping">
                        <thead>
                            <tr>
                                <th width="3%">
                                    <label class="control control__checkbox">
                                        <input type="checkbox" class=" is--selector" id="selectAll">
                                        <div class="control__indicator"></div>
                                    </label>
                                </th>
                                <th>
                                    Carrier
                                </th>
                                <th>
                                    Name
                                </th>
                                <th>
                                    Ship Speed
                                </th>
                                <th>
                                    Restrictions
                                </th>
                                <th>
                                    Cost
                                </th>
                                <th width="10%">
                                    &nbsp;
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Single Promo -->
                            <?php if ($vendor_shipping != null) { ?>
                                <?php foreach ($vendor_shipping as $active) { ?>
                                    <tr>
                                        <td>
                                            <label class="control control__checkbox">
                                                <input type="checkbox" name="checkboxRow" class="singleCheckbox" value="<?php echo $active->id; ?>">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </td>
                                        <td>
                                            <?php echo $active->carrier; ?>
                                        </td>
                                        <td>
                                            <?php echo $active->shipping_type; ?>
                                        </td>
                                        <td>
                                            <?php echo $active->delivery_time; ?>
                                        </td>
                                        <td>
                                            <span class="fontWeight--2">Max Weight:</span> <?php echo $active->max_weight; ?> lbs<br>
                                            <span class="fontWeight--2">Max Dimensions:</span> <?php echo $active->max_dimension; ?> in
                                        </td>
                                        <td>
                                            <?php echo "$" . $active->shipping_price; ?>
                                        </td>
                                        <td class="align--center">
                                            <button class="btn btn--s btn--primary btn--icon modal--toggle" data-target="#editShippingMethodModal<?php echo $active->id; ?>"><svg class="icon icon--settings"><use xlink:href="#icon-settings"></use></svg></button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                            <!-- Single Promo -->
                        </tbody>
                    </table>
                    </div>
                    <!-- /Promo Codes -->
                </div>
                <!-- /Content Area -->

            </div>
        </div>
    </section>
</div>
<!-- /Content Section -->

<!-- Modals -->
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/delete-shipping-method.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/footer-vendor.php'); ?> 

<?php $this->load->view('templates/_inc/shared/modals/delete-shipping-method.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/create-shipping-method.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/edit-shipping-method.php'); ?>
<?php $this->load->view('templates/_inc/footer-vendor.php'); ?>

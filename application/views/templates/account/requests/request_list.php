
<div class="page__tab">
    <?php
    $tier_1_2_roles = unserialize(ROLES_TIER1_2);
    if ($request_product != null) {
        ?>
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
            <?php if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $tier_1_2_roles)))) { ?>
                <div id="controlsRequests" class="contextual__controls wrapper__inner align--right">
                    <button class="btn btn--tertiary btn--s contextual--hide move_all_to_cart" data-id="<?php echo $locationName->id; ?>" style="display: inline-block;">Move All Items to Cart</button>

                    <button class="btn btn--primary btn--s is--contextual is--off modal--toggle selected_list" data-target="#addSelectionsToCartModal">Move Selections to Cart</button>

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
            <?php } ?>
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
                    <th width="25%">&nbsp;</th>
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
                                    <input type="checkbox" name="checkboxRow" class="singleCheckbox" value="<?php echo $request_product[$i]->id; ?>">
                                    <div class="control__indicator"></div>
                                </label>
                            </td>

                            <td>
                                <!-- Product -->
                                <div class="product product--s row multi--vendor req--license padding--xxs">
                                    <div class="product__image col col--2-of-8 col--am">
                                        <?php
                                        if ($request_product[$i]->images != null) {
                                            ?>
                                            <div class="product__thumb" style="background-image:url('<?php echo image_url(); ?>uploads/products/images/<?php echo $request_product[$i]->images->photo; ?>');">
                                            <?php } else { ?>
                                                <div class="product__thumb" style="background-image:url('<?php echo image_url(); ?>assets/img/product-image.png');">
                   <!-- <div class="avatar avatar--s" style="background-image:url('<?php //echo base_url();    ?>assets/img/ph-avatar.jpg');"></div> -->
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <div class="product__data col col--6-of-8 col--am">


                                            <input type="hidden" name="l_id" class="l_id" value="<?php echo $locat_id; ?>">
                                            <input type="hidden" name="request_ids" id="user_id" class="request_ids" value="">
                                            <input type="hidden" name="locationName"  class="locationName" value="<?php echo $locationName->nickname; ?>">

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
                                            <?php }
                                            ?>

                                            <span class="product__mfr">
                                                <?php if ($request_product[$i]->product != null) { ?>
                                                    by <a class="link fontWeight--2" href="#">
                                                        <?php echo $request_product[$i]->product->manufacturer; ?>
                                                    </a>
                                                <?php } ?>
                                            </span>
                                            <span class="fontSize--s fontWeight--2">
                                                $<?php echo number_format(floatval($request_product[$i]->product_pricing->price), 2, ".", ""); ?>
                                            </span>
                                            <span class="fontSize--s">
                                                (<?php echo $request_product[$i]->vendor->name; ?>)
                                                <a class="link fontWeight--2 fontSize--xs modal--toggle change_vendor" data-list_id="<?php echo $request_product[$i]->id; ?>" data-product_id="<?php echo $request_product[$i]->product_id; ?>" data-vendor_id="<?php echo $request_product[$i]->vendor->id; ?>" data-target="#changeVendorModal">Change</a></span>
                                        </div>
                                    </div>
                                    <!-- /Product -->
                            </td>

                            <td>
                                <input type="number" class="input input--qty width--100 r_quantity update_rqty" min="1" value="<?php echo $request_product[$i]->quantity; ?>" >
                                <input type="hidden" class="request_id"  value="<?php echo $request_product[$i]->id; ?>" >
                            </td>
                            <td class="align--center">
                                <?php if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $tier_1_2_roles))) && ($request_product[$i]->product->license_required != 'Yes' || $hasLicense)
                                      ) {
                                    <button class="btn btn--s btn--primary btn--icon pull--down-xxs modal--toggle request_list_single" data-lname="<?php echo $locationName->nickname; ?>" data-request_id="<?php echo $request_product[$i]->id; ?>" data-p_id="<?php echo $request_product[$i]->product_id; ?>" data-target="#addSelectionsToCartModal"><svg class="icon icon--cart-s"><use xlink:href="#icon-cart-s"></use></svg></button>
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
                <button class="btn btn--primary btn--m btn--dir is--next is--link" data-target="<?php echo base_url() . 'templates/browse'; ?>">Start Shopping</button>
            </div>
            <!-- /Empty State -->
    </div>
    </tr>
<?php } ?>
<!-- /Requested Item -->
</tbody>
</table>
<?php if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $tier_1_2_roles)))) { ?>
    <div class="well">
        <?php if ($activity != null) { ?>
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

                            <span class="fontWeight--2"><?php echo $activity[$i]->users->first_name; ?></span> <?php echo $activity[$i]->action; ?> <span class="fontWeight--2">

                                <?php echo (isset($activity[$i]->products)) ? $activity[$i]->products->name : ""; ?>
                            </span> <span class="fontSize--xs textColor--dark-gray"><?php echo $up_date ?></span>
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
                         <div class="avatar avatar--xs" style="background-image:url('<?php //echo base_url();    ?>assets/img/ph-avatar.jpg');"></div>
                         <span class="fontWeight--2">Kevin McCallister</span> removed <span class="fontWeight--2">Osung PBWPBW Impression Tray with Wing (Nickel) Partial, PB</span> <span class="fontSize--xs textColor--dark-gray">3 days ago</span>
                     </div>
                 </li> -->
            </ul>
        <?php } ?>
    </div>
<?php } ?>

<!-- Scripts & Libraries -->
<?php include(INCLUDE_PATH . '/_inc/shared/modals/change-item-vendor.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/remove-items.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/remove-request-item.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/add-selected-items.php'); ?>

 <!--<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>-->
<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.detect-card.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.maskedinput.min.js"></script>
<script src="<?php echo base_url(); ?>lib/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url(); ?>lib/bootstrap-timepicker/bootstrap-timepicker.js"></script>
<script src="<?php echo base_url(); ?>lib/jquery-maskmoney/jquery.maskMoney.min.js"></script>
<script src="<?php echo base_url(); ?>lib/jquery-validate/jquery.validate.min.js"></script>
<script src="<?php echo base_url(); ?>lib/jquery-flexslider2/jquery.flexslider-min.js"></script>
<script type="text/javascript">
    var base_url = "<?php echo base_url(); ?>";
    var image_url = "<?php echo image_url(); ?>";
</script>
<!-- build:js js/main.min.js -->
<script src="<?php echo base_url(); ?>assets/js/main.js?time=<?php echo time(); ?>"></script>

<script src="<?php echo base_url(); ?>assets/js/moment.js"></script>
<script src="<?php echo base_url(); ?>assets/js/main-addition.js"></script>
<script src="<?php echo base_url(); ?>assets/js/awesome.min.js"></script>
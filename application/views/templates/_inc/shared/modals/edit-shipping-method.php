<!-- Edit Shipping Method Modal -->
<?php if ($vendor_shipping != null) { ?>
    <?php foreach ($vendor_shipping as $active) { ?>
        <div id="editShippingMethodModal<?php echo $active->id; ?>" class="modal modal--m">
            <div class="modal__wrapper modal__wrapper--transition padding--l">
                <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
                <div class="modal__header center center--h align--left">
                    <h2>Edit shipping method</h2>
                    <p class="margin--l no--margin-r no--margin-t no--margin-l">Edit the following details below and save your changes.</p>
                </div>
                <div class="modal__body center center--h align--left cf">
                    <form id="editShippingMethod" class="form__group" action="<?php echo base_url(); ?>update-shipping-vendorDashboard" method="post">
                        <input type="hidden" name="shipping_id" value="<?php echo $active->id; ?>">
                        <div class="modal__content">
                            <div class="row">
                                <div class="input__group is--inline">
                                    <div class="select">
                                        <select name="editShippingCarrier" required>
                                            <option disabled>Select Carrier</option>
                                            <option value="1"<?php echo ($active->carrier == "US Postal Service") ? "selected" : ""; ?>>US Postal Service</option>
                                            <option value="2"<?php echo ($active->carrier == "UPS") ? "selected" : ""; ?>>UPS</option>
                                            <option value="3"<?php echo ($active->carrier == "FedEx") ? "selected" : ""; ?>>FedEx</option>
                                            <option value="4"<?php echo ($active->carrier == "DHL") ? "selected" : ""; ?>>DHL</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input__group is--inline">
                                    <div class="select">
                                        <select name="editShippingType" required>
                                            <option disabled>Select Shipping Method</option>
                                            <option value="1"<?php echo ($active->shipping_type == "Standard Ground") ? "selected" : ""; ?>>Standard Ground</option>
                                            <option value="2"<?php echo ($active->shipping_type == "Priority Mail") ? "selected" : ""; ?>>Priority Mail</option>
                                            <option value="3"<?php echo ($active->shipping_type == "Priority Mail Express") ? "selected" : ""; ?>>Priority Mail Express</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input__group is--inline">
                                    <div class="select">
                                        <select name="editShippingSpeed" required>
                                            <option disabled>Select Timeframe</option>
        <!--                                            <option value="" selected><?php echo $active->delivery_time; ?></option>-->
                                            <option value="1"<?php echo ($active->delivery_time == "Same Day") ? "selected" : ""; ?>>Same Day</option>
                                            <option value="2"<?php echo ($active->delivery_time == "Next Business Day") ? "selected" : ""; ?>>Next Business Day</option>
                                            <option value="3"<?php echo ($active->delivery_time == "2 Business Days") ? "selected" : ""; ?>>2 Business Days</option>
                                            <option value="4"<?php echo ($active->delivery_time == "3 Business Days") ? "selected" : ""; ?>>3 Business Days</option>
                                            <option value="5"<?php echo ($active->delivery_time == "1-5 Business Days") ? "selected" : ""; ?>>1-5 Business Days</option>
                                            <option value="6"<?php echo ($active->delivery_time == "7-10 Business Days") ? "selected" : ""; ?>>7-10 Business Days</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <h5 class="title">Details</h5>
                            <div class="row form__row">
                                <div class="col col--2-of-6 col--am">
                                    <span class="fontSize--m fontWeight--2">Max Weight</span>
                                </div>
                                <div class="col col--4-of-6 col--am">
                                    <div class="input__group is--inline">
                                        <input id="editMaxWeight" name="editMaxWeight" type="number" class="input input--m width-100 not--empty" min="1" value="<?php echo $active->max_weight; ?>" required>
                                        <label class="label" for="editMaxWeight">lbs.</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row form__row">
                                <div class="col col--2-of-6 col--am">
                                    <span class="fontSize--m fontWeight--2">Max Dimensions </span>
                                </div>
                                <div class="col col--4-of-6 col--am">
                                    <div id="editMaxDimensions" class="input__group is--inline">
                                        <input type="text" name="editMaxDimensions" class="input input--m width-100 not--empty" value="<?php echo $active->max_dimension; ?>" placeholder="L×W×H" required>
                                        <label class="label" for="editMaxDimensions">in.</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row form__row">
                                <div class="col col--2-of-6 col--am">
                                    <span class="fontSize--m fontWeight--2">Cost</span>
                                </div>
                                <div class="col col--4-of-6 col--am">
                                    <div class="input__group is--inline">
                                        <input id="editShippingCost" name="editShippingCost" type="text" class="input input--m input--currency-zero width-100 not--empty" min="$0.00" value="<?php echo $active->shipping_price; ?>" data-prefix="$" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footer__group border--dashed">
                            <button class="btn btn--m btn--primary btn--block save--toggle form--submit" data-target="#editShippingMethod">Save</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal__overlay modal--toggle"></div>
        </div>
    <?php } ?>
<?php } ?>
<!-- /Edit Shipping Method Modal -->

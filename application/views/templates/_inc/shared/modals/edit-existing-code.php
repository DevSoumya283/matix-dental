<!-- Edit Existing Code Modal -->
<?php if ($promoCodes_active != null) { ?>
    <?php foreach ($promoCodes_active as $active) { ?>
        <div id="editExistingCodeModal<?php echo $active->id; ?>" class="modal modal--m">
            <div class="modal__wrapper modal__wrapper--transition padding--l singlePromoCodeUpdate">
                <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
                <div class="modal__header wrapper margin--m no--margin-lr no--margin-t">
                    <h2 class="no--margin">Edit existing promo code</h2>
                </div>
                <div class="modal__body center center--h align--left cf">
                    <div class="modal__content">

                        <!-- Create Promo -->
                        <div class="form form--multistep">
                            <!-- Step One Promo Title -->
                            <form id="editFormPromo1-<?php echo $active->id; ?>" class="form__group">
                                <h3 class="fontSize--l textColor--dark-gray">1. Please title your promotion</h3>

                                <div class="row form__row">
                                    <div class="col col--1-of-6">
                                        <span class="fontSize--m fontWeight--2">Title</span>
                                    </div>
                                    <div class="col col--5-of-6">
                                        <div class="input__group is--inline">
                                            <input name="PromoId" class="PromoId input not--empty" type="hidden" value="<?php echo $active->id; ?>">
                                            <input name="createPromoTitle" class=" createPromoTitle input not--empty" type="text" required value="<?php echo $active->title; ?>">
                                            <label class="label" for="createPromoTitle">Promo Title</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row form__row">
                                    <div class="col col--1-of-6">
                                        <span class="fontSize--m fontWeight--2">Code</span>
                                    </div>
                                    <div class="col col--5-of-6">
                                        <div class="input__group is--inline">
                                            <input name="promoCode" class="input promoCode <?php echo ($active->code != null) ? "not--empty" : ""; ?>" type="text" value="<?php echo $active->code; ?>">
                                            <label class="label" for="createPromoCode">Promo Code</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row margin--l no--margin-b no--margin-lr">
                                    <button class="btn btn--m btn--primary btn--block save--toggle form--submit  go--next" data-target="#editFormPromo1-<?php echo $active->id; ?>" data-next="#editFormPromo2-<?php echo $active->id; ?>">Next</button>
                                </div>
                            </form>
                            <!-- /Step One Promo Title -->

                            <!-- Step Two Promo Details -->
                            <form id="editFormPromo2-<?php echo $active->id; ?>" class="form__group starts--hidden" action="">
                                <h3 class="fontSize--l textColor--dark-gray">2. Enter Promo Details</h3>

                                <!-- Discount -->
                                <div class="margin--l no--margin-lr no--margin-t">
                                    <p class="fontSize--m fontWeight--2 textColor--darkest-gray margin--xs no--margin-lr no--margin-t">Discount</p>
                                    <div class="row form__row">
                                        <div class="col col--3-of-12 col--am">
                                            <input type="number"   name="discount" required class="discount input input--m width-100" min="0" value="<?php echo $active->discount; ?>">
                                        </div>
                                        <div class="select col col--2-of-12 col--am">
                                            <select class="discount_type" name="discount_type" required>
                                                <option disabled value="default">Select a Denomination</option>
                                                <option value="1"<?php echo ($active->discount_type == "%") ? "selected" : ""; ?>>%</option>
                                                <option value="2"<?php echo ($active->discount_type == "$") ? "selected" : ""; ?>>$</option>
                                            </select>
                                        </div>
                                        <div class="col col--1-of-12 col--am align--center">
                                            <span class="fontSize--s fontWeight--2 textColor--darkest-gray">of</span>
                                        </div>
                                        <div class="select col col--6-of-12 col--am">
                                            <select name="discount_on" class="discount_on" required>
                                                <option disabled value="default">Select a Parameter</option>
                                                <option value="Final Price"<?php echo ($active->discount_on == "Final Price") ? "selected" : ""; ?>>Final Price</option>
                                                <option value="Shipping"<?php echo ($active->discount_on == "Shipping") ? "selected" : ""; ?>>Shipping</option>
                                                <option value="Tax"<?php echo ($active->discount_on == "Tax") ? "selected" : ""; ?>>Tax</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Threshold -->
                                <div class="margin--l no--margin-lr no--margin-t">
                                    <p class="fontSize--m fontWeight--2 textColor--darkest-gray margin--xs no--margin-lr no--margin-t">Threshold</p>
                                    <div class="row form__row">
                                        <div class="col col--3-of-6">
                                            <input type="number" required class="threshold_count input input--m width-100"  value="<?php echo $active->threshold_count; ?>"><!-- min=""  -->
                                        </div>
                                        <div class="select col col--3-of-6">
                                            <select name="threshold_type" class="threshold_type" required>
                                                <option value="1" <?php echo ($active->threshold_type==1)? "selected" : " "; ?>>Or more</option>
                                                <option value="2" <?php echo ($active->threshold_type==2)? "selected" : " "; ?>>Exactly</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Time Range -->
                                <div class="margin--l no--margin-lr no--margin-t">
                                    <p class="fontSize--m fontWeight--2 textColor--darkest-gray margin--xs no--margin-lr no--margin-t">Time Range</p>
                                    <div class="row">
                                        <div class="input__group input__group--date-range is--inline input-daterange" style="width:330px;">
                                            <svg class="icon icon--calendar"><use xlink:href="#icon-calendar"></use></svg>
                                            <?php
                                            $startdate = date('m-d-Y', strtotime($active->start_date));
                                            if (($startdate != null) && ($startdate != "01-01-1970") && ($startdate != "00-00-0000") && ($startdate != "11-30--0001")) {
                                                ?>
                                                <input type="text" class="start_date input input--date" placeholder="MM/DD/YYYY" name="start" value="<?php echo date('m/d/Y', strtotime($active->start_date)); ?>">
                                            <?php } else { ?>
                                                <input type="text" class="start_date input input--date" placeholder="MM/DD/YYYY" name="start" value="">
                                            <?php } ?>
                                            <?php
                                            $enddate = date('m-d-Y', strtotime($active->end_date));
                                            if (($enddate != null) && ($enddate != "01-01-1970") && ($enddate != "00-00-0000") && ($enddate != "11-30--0001")) {
                                                ?>
                                                <input type="text"  class="end_date input input--date" placeholder="MM/DD/YYYY" name="end" value="<?php echo date('m/d/Y', strtotime($active->end_date)); ?>">
                                            <?php } else { ?>
                                                <input type="text"  class="end_date input input--date" placeholder="MM/DD/YYYY" name="end" value="">
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="wrapper margin--l no--margin-b no--margin-lr">
                                    <div class="wrapper__inner">
                                        <input type="button" class="btn btn--m btn--primary save--toggle go--previous" data-target="#editFormPromo2-<?php echo $active->id; ?>" data-next="#editFormPromo1-<?php echo $active->id; ?>" value="Back">
                                    </div>
                                    <div class="wrapper__inner align--right">
                                        <button class="btn btn--m btn--primary save--toggle form--submit  go--next" data-target="#editFormPromo2-<?php echo $active->id; ?>" data-next="#editFormPromo3-<?php echo $active->id; ?>" type="submit">Next</button>
                                    </div>
                                </div>
                            </form>
                            <!-- /Step Two Promo Details -->
                            <!-- Step Three Other Details -->
                            <form id="editFormPromo3-<?php echo $active->id; ?>" class="form__group starts--hidden">
                                <h3 class="fontSize--l textColor--dark-gray">3. Other Details</h3>

                                <!-- Product ID -->
                                <?php if ($active->product_free == 0) { ?>
                                    <div class="row field__group">
                                        <label class="control control__checkbox">
                                            <input class="control__conditional product_free" type="checkbox" value="1"  required data-target="#editFreePromo-<?php echo $active->id; ?>">
                                            <div class="control__indicator"></div>
                                            <span class="fontSize--m fontWeight--2">Offer a free product with this promo?</span>
                                        </label>
                                        <div id="editFreePromo-<?php echo $active->id; ?>" class="is--conditional starts--hidden" style="display: none;">
                                            <div class="row">
                                                <div class="input__group is--inline">
                                                    <input name="free_product_id" class="input not--empty free_product_idSearch free_product_id" type="text" required value="">
                                                    <label class="label" for="productId">Product SKU</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input__group is--inline">
                                                    <input type="hidden" name="free_productpricing_id" class="free_productpricing_id" value="">
                                                    <input  id="product_Name" name="product_Name" class="product_Name input not--empty" type="text" value="" disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <div class="row field__group">
                                        <label class="control control__checkbox">
                                            <input class="control__conditional product_free" type="checkbox" value="<?php echo $active->product_free; ?>" <?php echo ($active->product_free == 0) ? "" : "checked"; ?> required data-target="#editFreePromo-<?php echo $active->id; ?>">
                                            <div class="control__indicator"></div>
                                            <span class="fontSize--m fontWeight--2">Offer a free product with this promo?</span>
                                        </label>
                                        <div id="editFreePromo-<?php echo $active->id; ?>" class="is--conditional <?php echo ($active->product_free == 0) ? "starts--hidden" : ""; ?>" style="<?php echo ($active->product_free == 0) ? "display: none;" : "display: block;"; ?>">
                                            <div class="row">
                                                <div class="input__group is--inline">
                                                    <input name="free_product_id" class="input not--empty free_product_idSearch free_product_id" type="text" required value="<?php echo $active->free_product_id; ?>">
                                                    <label class="label" for="productId">Product SKU</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input__group is--inline">
                                                    <input type="hidden" name="free_productpricing_id" class="free_productpricing_id" value="">
                                                    <input  id="product_Name" name="product_Name" class="product_Name input not--empty" type="text" value="<?php echo $active->free_product_name; ?>" disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                    
                                <?php } ?>

                                <!-- Allow Other Promos -->
                                <?php if($active->use_with_promos=='1') { ?>
                                <div class="item margin--xs no--margin-lr no--margin-t">
                                    <label class="control control__checkbox">
                                        <input type="checkbox" name="use_with_promos" class="use_with_promos" value="" <?php echo ($active->use_with_promos == 1) ? "checked" : ""; ?>>
                                        <div class="control__indicator"></div>
                                        <span class="fontSize--m fontWeight--2">Allow to be used with other promotions?</span>
                                    </label>
                                </div>
                                <?php } else { ?>
                                <div class="item margin--xs no--margin-lr no--margin-t">
                                    <label class="control control__checkbox">
                                        <input type="checkbox" name="use_with_promos" class="use_with_promos" value="">
                                        <div class="control__indicator"></div>
                                        <span class="fontSize--m fontWeight--2">Allow to be used with other promotions?</span>
                                    </label>
                                </div>                                    
                                <?php } ?>

                                <!-- Manufacturer Coupon -->
                                <div class="row field__group">
                                    <label class="control control__checkbox">
                                        <input class="control__conditional manufacturer_coupon" type="checkbox" name="manufacturer_coupon" value="<?php echo $active->manufacturer_coupon; ?>" <?php echo ($active->manufacturer_coupon == 1) ? "checked" : ""; ?>  data-target="#editManufacturerCoupon-<?php echo $active->id; ?>">
                                        <div class="control__indicator"></div>
                                        <span class="fontSize--m fontWeight--2">This is a manufacturer coupon</span>
                                    </label>
                                    <div id="editManufacturerCoupon-<?php echo $active->id; ?>" class="is--conditional starts--hidden" style="<?php echo ($active->manufacturer_coupon == 1) ? "display: inline;" : "display: none;"; ?>">
                                        <div class="row">
                                            <div class="input__group is--inline">
                                                <textarea name="conditions" placeholder="Enter submission and redemption instructions..." class="input input--l input--show-placeholder conditions"><?php echo $active->conditions; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="wrapper margin--m no--margin-lr no--margin-b">
                                    <div class="wrapper__inner">
                                        <input type="button" class="btn btn--m btn--primary save--toggle go--previous" data-target="#editFormPromo2-<?php echo $active->id; ?>" data-next="#editFormPromo2-<?php echo $active->id; ?>" value="Back">
                                    </div>
                                    <div class="wrapper__inner align--right">
                                        <button data-promoCodeId="<?php echo $active->id; ?>" class="btn btn--m btn--primary save--toggle close--modal promoCodeUpdate" type="submit">Finish</button>
                                    </div>
                                </div>
                            </form>
                            <!-- /Step Three Other Details -->
                        </div>
                        <!-- /Create Promo -->

                    </div>
                </div>
            </div>
            <div class="modal__overlay modal--toggle"></div>
        </div>
    <?php } ?>
<?php } ?>
<!-- /Edit Existing Code Modal -->
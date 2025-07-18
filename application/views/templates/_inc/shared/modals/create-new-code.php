<!-- Create New Code Modal -->
<div id="createNewCodeModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header wrapper margin--m no--margin-lr no--margin-t">
            <h2 class="no--margin">Create new promo code</h2>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">

                <!-- Create Promo -->
                <div class="form form--multistep">
                    <!-- Step One Promo Title -->
                    <!-- From1-->
                    <form id="formPromo1" class="form__group" method="post">
                        <h3 class="fontSize--l textColor--dark-gray">1. Please title your promotion</h3>

                        <div class="row form__row">
                            <div class="col col--1-of-6">
                                <span class="fontSize--m fontWeight--2">Title</span>
                            </div>
                            <div class="col col--5-of-6">
                                <div class="input__group is--inline">
                                    <input id="createPromoTitle" name="createPromoTitle" class="input" type="text" required>
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
                                    <input id="promoCode" name="promoCode" class="input" type="text"  value="" required>
                                    <label class="label" for="promoCode">Promo Code</label>
                                </div>
                            </div>
                        </div>
                        <div class="row margin--l no--margin-b no--margin-lr">
                            <button class="btn btn--m btn--primary btn--block save--toggle form--submit go--next" data-target="#formPromo1" data-next="#formPromo2">Next</button>
                        </div>
                    </form>
                    <!-- /Step One Promo Title -->

                    <!-- Step Two Promo Details -->
                    <!-- From2-->
                    <form id="formPromo2" class="form__group starts--hidden" method="post">
                        <h3 class="fontSize--l textColor--dark-gray">2. Enter Promo Details</h3>

                        <!-- Discount -->
                        <div class="margin--l no--margin-lr no--margin-t">
                            <p class="fontSize--m fontWeight--2 textColor--darkest-gray margin--xs no--margin-lr no--margin-t">Discount</p>
                            <div class="row form__row">
                                <div class="col col--3-of-12 col--am">
                                    <input type="number" id="discount" name="discount" class="input input--m width-100" min="0" value="0">
                                </div>
                                <div class="select col col--2-of-12 col--am">
                                    <select  id="discount_type" name="discount_type" required>
                                        <option disabled value="default">Select a Denomination</option>
                                        <option selected value="1">%</option>
                                        <option value="2">$</option>
                                    </select>
                                </div>
                                <div class="col col--1-of-12 col--am align--center">
                                    <span class="fontSize--s fontWeight--2 textColor--darkest-gray">of</span>
                                </div>
                                <div class="select col col--6-of-12 col--am">
                                    <select name="discount_on" id="discount_on" required>
                                        <option disabled selected value="default">Select a Parameter</option>
                                        <option value="Final Price">Final Price</option>
                                        <option value="Shipping">Shipping</option>
                                        <option value="Tax">Tax</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Threshold -->
                        <div class="margin--l no--margin-lr no--margin-t">
                            <p class="fontSize--m fontWeight--2 textColor--darkest-gray margin--xs no--margin-lr no--margin-t">Threshold</p>
                            <div class="row form__row">
                                <div class="col col--3-of-6">
                                    <input id="threshold_count" type="number" class="input input--m width-100" min="1" value="">
                                </div>
                                <div  class="select col col--3-of-6">
                                    <select name="threshold_type" id="threshold_type" required>
                                        <option disabled selected value="default">Select a Threshold</option>
                                        <option value="1">Or more</option>
                                        <option value="2">Exactly</option>
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
                                    <input type="text" id="start_date" value="" class="input input--date" placeholder="MM/DD/YYYY" name="start">
                                    <input type="text" id="end_date" value="" class="input input--date" placeholder="MM/DD/YYYY" name="end">
                                </div>
                            </div>
                        </div>

                        <div class="wrapper margin--l no--margin-b no--margin-lr">
                            <div class="wrapper__inner">
                                <input type="button" class="btn btn--m btn--primary save--toggle go--previous" data-target="#formPromo2" data-next="#formPromo1" value="Back" />
                            </div>
                            <div class="wrapper__inner align--right">
                                <button class="btn btn--m btn--primary save--toggle form--submit go--next" data-target="#formPromo2" data-next="#formPromo3">Next</button>
                            </div>
                        </div>
                    </form>
                    <!-- /Step Two Promo Details -->

                    <!-- Step Three Other Details -->
                    <!-- From3-->
                    <form id="formPromo3" class="form__group starts--hidden">
                        <h3 class="fontSize--l textColor--dark-gray">3. Other Details</h3>

                        <!-- Product ID -->
                        <div class="row field__group">
                            <label class="control control__checkbox">
                                <input class="control__conditional" id="product_free" value="1" type="checkbox" data-target="#freePromo">
                                <div class="control__indicator"></div>
                                <span class="fontSize--m fontWeight--2">Offer a free product with this promo?</span>
                            </label>
                            <div id="freePromo" class="is--conditional starts--hidden" style="display: none;">
                                <div class="row">
                                    <div class="input__group is--inline">
                                        <input type="hidden" name="free_productpricing_id" class="free_productpricing_id" value="">
                                        <input id="free_product_id"  name="free_product_id" class="input free_product_idSearch" type="text" required>
                                        <label class="label" for="free_product_id">Product SKU</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input__group is--inline">
                                        <input id="product_Name" name="product_Name" class="input not--empty product_Name" type="text" value="" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Allow Other Promos -->
                        <div class="item margin--xs no--margin-lr no--margin-t">
                            <label class="control control__checkbox">
                                <input type="checkbox" name="use_with_promos" id="use_with_promos" value="">
                                <div class="control__indicator"></div>
                                <span class="fontSize--m fontWeight--2">Allow to be used with other promotions?</span>
                            </label>
                        </div>

                        <!-- Manufacturer Coupon -->
                        <div class="row field__group">
                            <label class="control control__checkbox">
                                <input class="control__conditional" type="checkbox" id="manufacturer_coupon" value="1" data-target="#manufacturerCoupon">
                                <div class="control__indicator"></div>
                                <span class="fontSize--m fontWeight--2">This is a manufacturer coupon</span>
                            </label>
                            <div id="manufacturerCoupon" class="is--conditional starts--hidden" style="display: none;">
                                <div class="row">
                                    <div class="input__group is--inline">
                                        <textarea id="conditions" name="conditions" placeholder="Enter submission and redemption instructions..." class="input input--l input--show-placeholder"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="wrapper margin--m no--margin-lr no--margin-b">
                            <div class="wrapper__inner">
                                <button class="btn btn--m btn--primary save--toggle go--previous" data-target="#formPromo2" data-next="#formPromo2">Back</button>
                            </div>
                            <div class="wrapper__inner align--right">
                                <button class="btn btn--m btn--primary save--toggle close--modal form--submit promoCodeCreate page--reload" data-target="#formPromo3" type="button">Finish</button>
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
<!-- /Create New Code Modal -->
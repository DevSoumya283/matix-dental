<!-- Vendor Review Modal -->
<div id="vendorReviewModal" class="modal modal--l">
    <div class="modal__wrapper modal__wrapper--transition padding--l no--pad-r no--pad-l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Review <?php echo $vendor->name ?></h2>
            <p class="margin--m no--margin-r no--margin-t no--margin-l">Please write your review with care, so that other users will find it helpful.</p>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">
                <form id="vendorReviewForm" class="form__group" action="<?php echo base_url("vendor-review"); ?>" method="post">
                    <!-- Star Rating -->
                    <div class="padding--m no--pad-r no--pad-t no--pad-l border--light border--1 border--solid border--b">
                        <div class="row form__row">
                            <div class="col col--4-of-6">
                                <span class="fontSize--m">What was your <span class="fontWeight--2">overall rating</span> of this vendor?</span>
                            </div>
                            <input type="hidden" name="vendor_id" class="v_id" value="">
                            <div class="col col--2-of-6 cf">
                                <fieldset class="star-rating float--right">
                                    <input type="radio" id="field1_star5" name="rating" value="5" />
                                    <label class="full" for="field1_star5"></label>

                                    <input type="radio" id="field1_star4" name="rating" value="4" />
                                    <label class="full" for="field1_star4"></label>

                                    <input type="radio" id="field1_star3" name="rating" value="3" />
                                    <label class="full" for="field1_star3"></label>

                                    <input type="radio" id="field1_star2" name="rating" value="2" />
                                    <label class="full" for="field1_star2"></label>

                                    <input type="radio" id="field1_star1" name="rating" value="1" />
                                    <label class="full" for="field1_star1"></label>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <div class="padding--m no--pad-r no--pad-l border--light border--1 border--solid border--b">
                        <div class="padding--s no--pad-r no--pad-t no--pad-l">
                            <div class="row form__row">
                                <div class="col col--4-of-6">
                                    <span class="fontSize--m">How satisfied were you with the <span class="fontWeight--2">shipping speed</span>?</span>
                                </div>
                                <div class="col col--2-of-6 cf">
                                    <fieldset class="star-rating float--right">
                                        <input type="radio" id="field2_star5" name="speed" value="5" />
                                        <label class="full" for="field2_star5"></label>

                                        <input type="radio" id="field2_star4" name="speed" value="4" />
                                        <label class="full" for="field2_star4"></label>

                                        <input type="radio" id="field2_star3" name="speed" value="3" />
                                        <label class="full" for="field2_star3"></label>

                                        <input type="radio" id="field2_star2" name="speed" value="2" />
                                        <label class="full" for="field2_star2"></label>

                                        <input type="radio" id="field2_star1" name="speed" value="1" />
                                        <label class="full" for="field2_star1"></label>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                        <div class="padding--s no--pad-r no--pad-t no--pad-l ">
                            <div class="row form__row">
                                <div class="col col--4-of-6">
                                    <span class="fontSize--m">How would you rate the vendor's <span class="fontWeight--2">customer service</span>?</span>
                                </div>
                                <div class="col col--2-of-6 cf">
                                    <fieldset class="star-rating float--right">
                                        <input type="radio" id="field3_star5" name="service" value="5" />
                                        <label class="full" for="field3_star5"></label>
                                        <input type="radio" id="field3_star4" name="service" value="4" />
                                        <label class="full" for="field3_star4"></label>

                                        <input type="radio" id="field3_star3" name="service" value="3" />
                                        <label class="full" for="field3_star3"></label>

                                        <input type="radio" id="field3_star2" name="service" value="2" />
                                        <label class="full" for="field3_star2"></label>

                                        <input type="radio" id="field3_star1" name="service" value="1" />
                                        <label class="full" for="field3_star1"></label>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                        <div class="padding--s no--pad-r no--pad-t no--pad-l">
                            <div class="row form__row">
                                <div class="col col--4-of-6">
                                    <span class="fontSize--m">How would you rate the <span class="fontWeight--2">ease of transaction</span>?</span>
                                </div>
                                <div class="col col--2-of-6 cf">
                                    <fieldset class="star-rating float--right">
                                        <input type="radio" id="field4_star5" name="ease" value="5" />
                                        <label class="full" for="field4_star5"></label>

                                        <input type="radio" id="field4_star4" name="ease" value="4" />
                                        <label class="full" for="field4_star4"></label>

                                        <input type="radio" id="field4_star3" name="ease" value="3" />
                                        <label class="full" for="field4_star3"></label>

                                        <input type="radio" id="field4_star2" name="ease" value="2" />
                                        <label class="full" for="field4_star2"></label>

                                        <input type="radio" id="field4_star1" name="ease" value="1" />
                                        <label class="full" for="field4_star1"></label>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                        <div class="row form__row">
                            <div class="col col--4-of-6">
                                <span class="fontSize--m">What was the vendor's level of <span class="fontWeight--2">responsiveness</span>?</span>
                            </div>
                            <div class="col col--2-of-6 cf">
                                <fieldset class="star-rating float--right">
                                    <input type="radio" id="field5_star5" name="responsiveness" value="5" />
                                    <label class="full" for="field5_star5"></label>
                                    <input type="radio" id="field5_star4" name="responsiveness" value="4" />
                                    <label class="full" for="field5_star4"></label>
                                    <input type="radio" id="field5_star3" name="responsiveness" value="3" />
                                    <label class="full" for="field5_star3"></label>
                                    <input type="radio" id="field5_star2" name="responsiveness" value="2" />
                                    <label class="full" for="field5_star2"></label>
                                    <input type="radio" id="field5_star1" name="responsiveness" value="1" />
                                    <label class="full" for="field5_star1"></label>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <!-- /Star Rating -->

                    <!-- Review Message -->
                    <div class="padding--l no--pad-r no--pad-b no--pad-l">

                        <div class="form__group margin--m no--margin-r no--margin-t no--margin-l">
                            <div class="row form__row">
                                <div class="col col--1-of-6 col--am">
                                    <span class="fontSize--m fontWeight--2">Review Title</span>
                                </div>
                                <div class="col col--5-of-6 col--am">
                                    <div class="input__group is--inline">
                                        <div class="input__group is--inline">
                                            <input id="accountName" class="input" name="review_title" type="text" placeholder="" value="" pattern=".*\S.*" required>
                                            <label class="label" for="accountName">Enter your title...</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row form__row">
                            <div class="col col--6-of-6">
                                <div class="input__group is--inline">
                                    <textarea name="message" maxlength="1000" placeholder="Enter your review... (max 1000 characters)" class="input input--l input--show-placeholder"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row margin--s no--margin-r no--margin-b no--margin-l">
                            <button class="btn btn--m btn--primary btn--block save--toggle form--submit" data-target="#vendorReviewForm">Submit</button>
                        </div>
                    </div>
                    <!-- Review Message -->
                </form>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Vendor Review Modal -->

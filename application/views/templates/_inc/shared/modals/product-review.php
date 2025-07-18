<!-- Product Review Modal -->
<div id="productReviewModal" class="modal modal--l">
    <div class="modal__wrapper modal__wrapper--transition padding--l no--pad-r no--pad-l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Product Review</h2>
            <p class="margin--m no--margin-r no--margin-t no--margin-l">Please write your review with care, so that other users will find it helpful.</p>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">
                <form id="productReviewForm" action="<?php echo base_url(); ?>add-product-rating" method="post" class="form__group" action="">
                    <div class="padding--m no--pad-r no--pad-t no--pad-l border--light border--1 border--solid border--b">
                        <input type="hidden"  name="product_id" class="pro_id" value="">
                        <div class="row no--margin-l">
                            <div class="col col--4-of-6 no--pad-l">
                                <span class="fontSize--m fontWeight--2">How would you rate this product?</span>
                            </div>
                            <div class="col col--2-of-6 cf">
                                <fieldset class="star-rating float--right">
                                    <input type="radio" id="field1_star5" name="rating1" value="5" required/>
                                    <label class="full" for="field1_star5"></label>

                                    <input type="radio" id="field1_star4" name="rating1" value="4"/>
                                    <label class="full" for="field1_star4"></label>

                                    <input type="radio" id="field1_star3" name="rating1" value="3"/>
                                    <label class="full" for="field1_star3"></label>

                                    <input type="radio" id="field1_star2" name="rating1" value="2"/>
                                    <label class="full" for="field1_star2"></label>

                                    <input type="radio" id="field1_star1" name="rating1" value="1"/>
                                    <label class="full" for="field1_star1"></label>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <div class="padding--l no--pad-r no--pad-b no--pad-l">
                        <div class="row no--margin-l">
                            <div class="margin--m no--margin-r no--margin-t no--margin-l">
                                <div class="row">
                                    <div class="col col--1-of-6 col--am">
                                        <span class="fontSize--m fontWeight--2">Review Title</span>
                                    </div>
                                    <div class="col col--5-of-6 col--am">
                                        <div class="input__group is--inline">
                                            <div class="input__group is--inline">
                                                <input id="reviewTitle" name="reviewTitle" class="input" type="text" required/>
                                                <label class="label" for="reviewTitle">Enter your title...</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col--6-of-6">
                                    <div class="input__group is--inline">
                                        <textarea name="productReview" maxlength="1000" placeholder="Enter your review...(max 1000 characters)" class="input input--l input--show-placeholder"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row margin--s no--margin-r no--margin-b no--margin-l">
                                <button class="btn btn--m btn--primary btn--block form--submit" data-target="#productReviewForm">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Product Review Modal -->

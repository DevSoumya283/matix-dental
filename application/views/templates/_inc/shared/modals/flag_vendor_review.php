<!-- Flag Review Modal -->
<div id="flagReviewModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Flag review as inappropriate?</h2>
            <p class="margin--m no--margin-r no--margin-t no--margin-l">Are you sure you want to flag this review as inappropriete? If yo do, it will be submitted to an admin to review.</p>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">
                <form id="askQuestionForm" action="<?php echo base_url("flag-vendor-review"); ?>" class="form__group" method="post">
                    <div class="row align--right">
                        <input type="hidden" name="review_id" class="review_id" value="">
                        <input type="hidden" name="review_title" class="review_title" value="">
                        <input type="hidden" name="review_comments" class="review_comments" value="">
                        <input type="hidden" name="product_name" class="p_name" value="">
                        <input type="hidden" name="p_id" class="p_id" value="">
                        <ul class="list list--inline">
                            <li class="item">
                                <button class="btn btn--m btn--secondary default--action">Cancel</button>
                            </li>
                            <li class="item">
                                <button type="submit" class="btn btn--m btn--primary default--action form--submit">Confirm</button>
                            </li>
                        </ul>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Flag Review Modal -->

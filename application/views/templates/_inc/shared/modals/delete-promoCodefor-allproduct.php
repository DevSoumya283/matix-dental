<!-- Delete Confirmation Modal -->
<div id="deletePromoCodeforAllproductModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Are you sure?</h2>
            <p class="margin--m no--margin-r no--margin-t no--margin-l">Deleting these product promotions is an irreversible action. Please make sure you want to do this.</p>
        </div>
        <form method="post" action="<?php echo base_url(); ?>vendor-PromoCode-delete">
            <div class="modal__body center center--h align--left cf">
                <div class="modal__content">
                    <div class="footer__group border--dashed">
                        <input type="hidden" name="promo_id" value="" id="product_id">
                        <button class="btn btn--m btn--block is--neg form--submit">Confirm Delete</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Delete Confirmation Modal -->

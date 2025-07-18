<!-- Confirm Promo Activation Modal -->
<div id="confirmPromoActivationModal" class="modal modal--m">
    <form action="<?php echo base_url(); ?>vendorAction-promoCode-selections" method="post">
        <div class="modal__wrapper modal__wrapper--transition padding--l">
            <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
            <div class="modal__header center center--h align--left">
                <h2>Activate Selected Promotion(s)?</h2>
                <p class="margin--m no--margin-r no--margin-t no--margin-l">They will be re-activated immediately.</p>
            </div>
            <div class="modal__body center center--h align--left cf">
                <div class="modal__content">
                    <div class="footer__group border--dashed">
                        <input type="hidden" name="promo_id" value="" id="product_id">
                        <input type="hidden" name="selection" value="activate">
                        <?php if ($promoCode_All != null) { ?>
                            <input type="hidden" name="promoAll" value="<?php echo $promoCode_All; ?>">
                        <?php } ?>
                        <button class="btn btn--m btn--block btn--primary form--submit page--reload" type="submit">Activate Selected</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Confirm Promo Activation Modal -->

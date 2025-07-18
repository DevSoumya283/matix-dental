<!-- Delete Shipping Method Modal -->
<div id="deleteShippingMethodModal" class="modal modal--m">
    <form method="post" action="<?php echo base_url(); ?>deleteSelect-shipping-vendorDashboard">
        <div class="modal__wrapper modal__wrapper--transition padding--l">
            <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
            <div class="modal__header center center--h align--left">
                <h2 class="fontSize--l">Delete Shipping Method?</h2>
            </div>
            <div class="modal__body center center--h align--left cf">
                <div class="modal__content">
                    <hr>
                    <div class="well card" style="padding:16px;">
                        <div class="text__group">
                            The Selected Shipping Method(s) will be deleted from the system and cannot be Retrieved.
                        </div>
                    </div>
                    <hr>
                    <div class="footer__group border--dashed">
                        <input type="hidden" name="shipping_ids" value="" id="deleteUser_id">
                        <button class="btn btn--m btn--block is--neg form--submit">Delete Shipping Method</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Delete Shipping Method Modal -->

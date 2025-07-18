<!-- Cancel Order Modal -->
<div id="cancelOrderAdminModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <form method="post" action="<?php echo base_url(); ?>Cancel-OrderBy-SuperAdmin">
            <div class="modal__header center center--h align--left">
                <h2>Are you sure?</h2>
                <p>The order will be immediately cancelled and the customer notified.</p>
            </div>
            <div class="modal__body center center--h align--left cf">
                <div class="modal__content">
                    <div class="footer__group border--dashed">
                        <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">
                        <input type="hidden" name="order_id" value="" id="order_id">
                        <button class="btn btn--m btn--block is--neg form--submit" type="submit">Cancel Order</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Cancel Order Modal -->

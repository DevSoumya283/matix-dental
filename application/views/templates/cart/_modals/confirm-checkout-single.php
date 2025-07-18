<div id="confirmCheckoutSingleModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header">
            <h2>Confirm Checkout</h2>
        </div>
        <input type="hidden" name="payment_token" class="pay_token" value="">
        <input type="hidden" name="total_cost" class="total_cost" value="">
        <input type="hidden" name="vendor_id" class="vendor_id" value="">

        <div class="modal__body cf">
            <div class="modal__content">
                <p>By submitting this order you acknowledge blah blah blah. Lorem ipsum dolor sit amet consecteru blah blah.</p>
            </div>
        </div>
        <div class="footer__group border--dashed">
            <button class="btn btn--m btn--primary btn--block cart-checkout">Submit Order</button>
           <!--  data-href="<?php //echo base_url() . 'templates/cart/complete';  ?>" -->
        </div>

    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>

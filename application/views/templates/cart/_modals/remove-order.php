<!-- Remove Order Modal -->
<div id="removeOrderModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header">
            <h2>Remove Order?</h2>
            <p class="margin--m no--margin-r no--margin-t no--margin-l">You can remove all the items from your cart, or save them for later.</p>
        </div>
        <div class="modal__body cf">
            <div class="modal__content">
                <div class="invoice">
                    <div class="inv__head no--pad row">
                        <div class="col col--3-of-8 col--am">
                            <img class="inv__logo vendor_logo_remove_items" src="/assets/img/ph-vendor-logo.png" alt="">
                        </div>
                        <div class="col col--5-of-8 col--am align--right">
                            <span class="fontWeight--2 textColor--dark-gray">Items:</span>
                            <span class="fontWeight--2 cart_items"></span>
                        </div>
                    </div>
                </div>
                <div class="footer__group border--dashed">
                    <div class="wrapper">
                        <div class="wrapper__inner">
                            <input type="hidden" name="" class="vendor_id" value="">
                            <input type="hidden" name="" class="location_id" value="">
                            <a class="link fontSize--s fontWeight--2 is--neg modal--toggle  remove-cart-vendor">Delete Items</a>
                        </div>
                        <div class="wrapper__inner align--right">
                            <input type="hidden" class="counter" name="" value="">
                            <button class="btn btn--m btn--primary modal--toggle vendor-save-later">Save for Later</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Remove Order Modal-->

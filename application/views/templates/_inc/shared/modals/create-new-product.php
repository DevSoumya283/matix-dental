<!-- Create New Product Modal -->
<div id="createNewProductModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Create a new product promo</h2>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">
                <p class="margin--m no--margin-lr no--margin-t">To create a new product promotion, please follow the instructions below:</p>
                <ol class="list list--decimal">
                    <li class="padding--xs no--pad-t no--pad-lr">Visit your product catalogue</li>
                    <li class="padding--xs no--pad-t no--pad-lr">Select the desired product</li>
                    <li>Enter the promotion details</li>
                </ol>
            </div>
            <div class="footer__group border--dashed">
                <a class="btn btn--m btn--primary btn--block default--action" href="<?php echo base_url(); ?>vendor-products-dashboard">Go to Product Catalogue</a>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Create New Product Modal -->

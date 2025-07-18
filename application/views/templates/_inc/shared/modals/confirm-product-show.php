<!-- Confirm Product Deactivation Modal -->
<div id="confirmProductShowModal" class="modal modal--m">
    <form action="<?php echo base_url(); ?>VendorProductAction-display" method="post">
        <div class="modal__wrapper modal__wrapper--transition padding--l">
            <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
            <div class="modal__header center center--h align--left">
                <h2>Show Selected Products?</h2>
                <p class="margin--m no--margin-r no--margin-t no--margin-l">These product(s) will be displayed in white label stores that select to hide items.</p>
            </div>
            <div class="modal__body center center--h align--left cf">
                <div class="modal__content">
                    <div class="footer__group border--dashed">
                        <input type="hidden" name="productPr_id" value="" id="product_id">
                        <input type="hidden" name="select" value="0">
                        <button class="btn btn--m btn--block is--neg default--action">Show Selected</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Confirm Product Deactivation Modal -->

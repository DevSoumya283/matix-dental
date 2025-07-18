<!-- Delete Product Modal -->
<div id="deleteProductModal" class="modal modal--m">
    <form method="post" action="<?php echo base_url(); ?>product-delete">
        <div class="modal__wrapper modal__wrapper--transition padding--l">
            <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
            <div class="modal__header center center--h align--left">
                <h2>Are you sure?</h2>
                <p class="margin--m no--margin-r no--margin-t no--margin-l">Deleting this product will remove it from all vendor's catalogs and cannot be undone.</p>
            </div>
            <div class="modal__body center center--h align--left cf">
                <div class="modal__content">
                    <div class="footer__group border--dashed">
                        <?php if ($product_details != null) { ?>
                            <input type="hidden" name="product_id" id="product_id" value="<?php echo $product_details->id; ?>">
                        <?php } else { ?>
                            <input type="hidden" name="product_id" id="product_id" value="">    
                        <?php } ?>
                        <button class="btn btn--m btn--block is--neg form--submit">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Delete Product Modal -->

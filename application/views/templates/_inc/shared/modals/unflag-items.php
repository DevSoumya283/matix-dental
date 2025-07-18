<!-- Unflag Items  Modal -->
<div id="unflagItemsModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Unflag Item(s)?</h2>
            <p class="margin--m no--margin-r no--margin-t no--margin-l">The selected item(s) will be unflagged and removed from the list.</p>
        </div>
        <form id="unflagItemsForm" method="post" action="<?php echo base_url(); ?>unflag-reviews">
            <div class="modal__body center center--h align--left cf">
                <div class="modal__content">
                    <div class="footer__group border--dashed">
                        <input type="hidden" name="flagged_id" value="" id="product_id">
                        <button class="btn btn--m btn--primary btn--block form--submit page--reload" data-target="#unflagItemsForm">Unflag Items</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Unflag Items  Modal -->
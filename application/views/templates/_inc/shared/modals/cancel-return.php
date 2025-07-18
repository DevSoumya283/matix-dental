<!-- Delete Prepopulated List Modal -->
<div id="cancelReturnModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Are you sure?</h2>
            <p> You can request to return these item(s) later if you wish.</p>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">
                <div class="footer__group border--dashed">
                    <button class="btn btn--m btn--block is--neg default--action is--link" data-target="<?php echo ROOT_PATH . 'templates/account/orders/returns'; ?>">Cancel Request</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Delete Prepopulated List Modal -->

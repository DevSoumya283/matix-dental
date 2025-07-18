<!-- Cancel Recurring Order Modal -->
<div id="cancelRecurringModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Are you sure?</h2>
            <p>This will immediately cancel all future scheduled orders. You can make a recurring order again later if you wish.</p>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">
                <form id="formPassword" class="form__group" action="<?php echo base_url('delete-recuring'); ?>" method="post">
                    <input type="hidden" name="recurring_id" class="recurring_id" value="">
                    <div class="footer__group border--dashed">
                        <button class="btn btn--m btn--block is--neg default--action is--link form--submit">Cancel Recurring Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Cancel Recurring Order Modal -->

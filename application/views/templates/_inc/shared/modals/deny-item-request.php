<!-- Deny Item Request Modal -->
<div id="denyOrderModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Deny requested item(s)</h2>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">
              <form id="renameClassForm" class="form__group" action="<?php echo base_url("deny-selected"); ?>" method="post" >
              
                <input type="hidden" name="order_id" class="order_id" value="">
                <p class="margin--m no--margin-r no--margin-t no--margin-l">They will be removed from the user's request list.</p>
                <div class="footer__group border--dashed">
                    <button class="btn btn--m btn--block is--neg default--action form--submit">Deny Request(s)</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Deny Item Request Modal -->

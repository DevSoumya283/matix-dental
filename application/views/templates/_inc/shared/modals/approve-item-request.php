<!-- Approve Item Request Modal -->
<div id="approveOrderModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Approve requested item(s)</h2>
        </div>
        <div class="modal__body center center--h align--left cf">

            <div class="modal__content">
                <form id="renameClassForm" class="form__group" action="<?php echo base_url("approve-selected"); ?>" method="post" >

                    <input type="hidden" name="order_id" class="c_id" value="">
                    <p class="margin--m no--margin-r no--margin-t no--margin-l">They will be added to the requesting user's cart.</p>
                    <div class="footer__group border--dashed">
                        <button class="btn btn--m btn--primary btn--block default--action">Approve Request(s)</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Approve Item Request Modal -->

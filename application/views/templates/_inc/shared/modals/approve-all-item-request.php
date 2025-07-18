<!-- Approve Item Request Modal -->
<div id="approveAllRequestModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Approve All requested item(s)</h2>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">
                <form id="renameClassForm" class="form__group" action="<?php echo base_url("approve-all-restricteditems"); ?>" method="post" >
                    <input type="hidden" name="class_id" class="c_id" value="">
                    <p class="margin--m no--margin-r no--margin-t no--margin-l">They will be added to the requesting user's cart.</p>
                    <div class="footer__group border--dashed">
                        <button class="btn btn--m btn--primary btn--block default--action form--submit page--reload">Approve Request(s)</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Approve Item Request Modal -->

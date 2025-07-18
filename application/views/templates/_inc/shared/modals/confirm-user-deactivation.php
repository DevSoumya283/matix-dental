<!-- Confirm User Deactivation Modal -->
<div id="confirmUserDeactivationModal" class="modal modal--m">
    <form action="<?php echo base_url(); ?>customer-activate-SPdashboard" method="post">
        <div class="modal__wrapper modal__wrapper--transition padding--l">
            <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
            <div class="modal__header center center--h align--left">
                <h2>Deactivate Selected Users?</h2>
                <p class="margin--m no--margin-r no--margin-t no--margin-l">They will no longer be allowed to log in.</p>
            </div>
            <div class="modal__body center center--h align--left cf">
                <div class="modal__content">
                    <div class="footer__group border--dashed">
                        <input type="hidden" name="user_id" value="" id="user_id">
                        <input type="hidden" name="select" value="0">
                        <button class="btn btn--m btn--block default--action is--neg">Deactivate Selected</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Confirm User Deactivation Modal -->

<!-- Unassign User Modal -->
<?php foreach ($user_data as $user) { ?>
    <div id="unassignUserModal<?php echo $user->id; ?>" class="modal modal--m">
        <div class="modal__wrapper modal__wrapper--transition padding--l">
            <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
            <div class="modal__header center center--h align--left mobile-center">
                <h2>Unassign this user?</h2>
                <p class="margin--l no--margin-r no--margin-t no--margin-l">The will no longer be able to manage this location.</p>
            </div>
            <div class="modal__body center center--h align--left cf modal-margin">
                <div class="modal__content">
                    <form id="newListForm" class="form__group" action="<?php echo base_url(); ?>unassign-userLocation-organization" method="post">
                        <div class="footer__group border--dashed">
                            <input type="hidden" name="location_id" value="<?php echo $location_id; ?>">
                            <input type="hidden" name="user_location_id" value="<?php echo $user->id; ?>">
                            <input type="hidden" name="user_id" value="<?php echo $user->user_id; ?>">
                            <button class="btn btn--m btn--block is--neg form--submit">Unassign User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal__overlay modal--toggle"></div>
    </div>
<?php } ?>
<!-- /Unassign User Modal -->

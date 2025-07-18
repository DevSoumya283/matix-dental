<!-- Delete Admin Modal -->
<div id="deleteMultipleModal" class="modal modal--m">
    <form id="resetPassword" action="<?php echo base_url(); ?>SPdashboard-deletemultiple-admin" method="post">
        <div class="modal__wrapper modal__wrapper--transition padding--l">
            <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
            <div class="modal__header center center--h align--left">
                <h2>Delete this user?</h2>
                <p class="margin--m no--margin-r no--margin-t no--margin-l">Are you sure? This cannot be undone.</p>
            </div>
            <div class="modal__body center center--h align--left cf">
                <div class="modal__content">
                    <div class="card padding--s">
                        <div class="entity__group">                        
                            The selected user(s) will be deleted from the system and cannot be Retrieved.
                        </div>
                    </div>
                    <div class="footer__group border--dashed">
                        <input type="hidden" name="user_id" value="" id="deleteUser_id">
                        <button class="btn btn--m btn--block is--neg default--action">Delete User</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Delete User Modal -->

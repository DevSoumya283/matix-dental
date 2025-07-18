<!-- Delete Admin Modal -->
<?php foreach ($superAdmin as $user) { ?>
    <div id="deleteUserModal<?php echo $user->id; ?>" class="modal modal--m">
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
                            <?php echo $user->first_name; ?> (<?php echo $user->role_name; ?>)
                        </div>
                    </div>
                    <div class="footer__group border--dashed">
                        <a class="btn btn--m btn--block is--neg default--action" href="<?php echo base_url(); ?>user-delete?user_id=<?php echo $user->id; ?>">Delete User</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal__overlay modal--toggle"></div>
    </div>
<?php } ?>
<!-- /Delete User Modal -->

<!-- Edit Admin Modal -->
<?php foreach ($superAdmin as $user) { ?>
    <div id="editAdminModal<?php echo $user->id; ?>" class="modal modal--m">
        <div class="modal__wrapper modal__wrapper--transition padding--l">
            <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
            <div class="modal__header center center--h align--left">
                <h2>Edit Admin</h2>
            </div>
            <div class="modal__body center center--h align--left cf">
                <form id="editAdmin" class="modal__content" action="<?php echo base_url(); ?>SPdashboard-admins-edit" method="post">
                    <input type="hidden" name="user_id" value="<?php echo $user->id; ?>">
                    <div class="input__group is--inline margin--xs no--margin-lr no--margin-t">
                        <input id="accountName" name="accountName" class="input not--empty" type="text" value="<?php echo $user->first_name; ?>" required>
                        <label class="label" for="accountName">Name</label>
                    </div>
                    <div class="input__group is--inline margin--xs no--margin-lr no--margin-t">
                        <input id="accountEmail" name="accountEmail" class="input not--empty" type="email" placeholder="email@example.com" value="<?php echo $user->email; ?>" pattern=".*\S.*" disabled>
                        <label class="label" for="accountEmail">email@example.com</label>
                    </div>
                    <div class="select">
                        <select name="role_id" required>
                            <option disabled>Select Role</option>
                            <option value="<?php echo $user->role_id; ?>" selected><?php echo $user->role_name; ?></option>
                            <option value="<?php echo ($user->role_id == 2) ? "1" : "2"; ?>"><?php echo ($user->role_id == 2) ? "Super-Admin" : "Admin" ?></option>
                        </select>
                    </div>
                    <div class="footer__group border--dashed">
                        <button class="btn btn--m btn--primary btn--block save--toggle form--submit" data-target="#editAdmin" type="submit">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal__overlay modal--toggle"></div>
    </div>
<?php } ?>
<!-- /New Admin Modal -->

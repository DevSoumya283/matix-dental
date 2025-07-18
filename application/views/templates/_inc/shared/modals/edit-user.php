<!-- Edit User Modal -->
<?php
if ($My_vendor_users != null) {
    foreach ($My_vendor_users as $row) {
        ?>
        <div id="editUserModal<?php echo $row->id; ?>" class="modal modal--m">
            <div class="modal__wrapper modal__wrapper--transition padding--l">
                <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
                <div class="modal__header center center--h align--left">
                    <h2>Edit User</h2>
                </div>
                <div class="modal__body center center--h align--left cf">
                    <div class="modal__content">
                        <form id="editUser" class="form__group" action="<?php echo base_url(); ?>update-vendor-user" method="post">
                            <div class="row">
                                <div class="input__group is--inline">
                                    <input id="accountName" name="accountName" class="input not--empty" type="text" placeholder="Kevin McCallister" value="<?php echo $row->name; ?>" required>
                                    <label class="label" for="accountName">Full Name</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input__group is--inline">
                                    <input type="hidden" name="user_id" value="<?php echo $row->user_id; ?>">
                                    <input id="userEmail" name="userEmail" class="input not--empty" type="email" placeholder="email@example.com" value="<?php echo $row->email; ?>" pattern=".*\S.*" disabled>
                                    <label class="label" for="userEmail">Email Address</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="select">
                                    <select name="userRole" required>
                                        <!--						  <option disabled value="default">Select Role</option>-->
                                        <option value="<?php echo $row->role; ?>" selected><?php echo $row->role; ?></option>
                                        <!--						  <option value="2">Tier Two</option>
                                                                                          <option value="3">Tier Three</option>-->
                                    </select>
                                </div>
                            </div>
                            <div class="footer__group border--dashed border--light">
                                <button class="btn btn--m btn--primary btn--block save--toggle form--submit">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal__overlay modal--toggle"></div>
        </div>
    <?php
    }
}
?>
<!-- /Edit User Modal -->

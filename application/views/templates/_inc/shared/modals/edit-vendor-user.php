<!-- Edit Vendor User Modal -->
<?php if ($vendor_users != null) { ?>
    <?php foreach ($vendor_users as $user) { ?>
        <div id="editVendorUserModal<?php echo $user->id; ?>" class="modal modal--m">
            <div class="modal__wrapper modal__wrapper--transition padding--l">
                <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
                <div class="modal__header center center--h align--left">
                    <h2>Edit User</h2>
                </div>
                <div class="modal__body center center--h align--left cf">
                    <form id="editVendorUserForm" class="form__group modal__content" action="<?php echo base_url(); ?>edit-vendorUser-SPdashboard" method="post">
                        <div class="row">
                            <div class="input__group is--inline">
                                <input type="hidden" name="vendor_user_id" value="<?php echo $user->id; ?>">
                                <input id="accountName" name="accountName" class="input not--empty" type="text" placeholder="Kevin McCallister" value="<?php echo $user->first_name; ?>" required>
                                <label class="label" for="accountName">Full Name</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input__group is--inline">
                                <input id="accountEmail" name="accountEmail" class="input not--empty" type="email" placeholder="email@example.com" value="<?php echo $user->email; ?>" pattern=".*\S.*" disabled>
                                <label class="label" for="accountEmail">Email Address</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="select">
                                <select aria-label="Select a Title" name="vendor_role" required>
                                    <!--                            <option disabled value="default">Select Role</option>-->
                                    <option value="11" selected><?php echo ($user->role_id == 11) ? "Vendor-Admin" : ""; ?></option>
                                    <!--                            <option value="2">Role Two</option>
                                                                <option value="3">Role Three</option>-->
                                </select>
                            </div>
                        </div>
                        <div class="footer__group border--dashed">
                            <button class="btn btn--m btn--primary btn--block save--toggle form--submit page--reload" data-target="#editVendorUserForm" type="submit">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal__overlay modal--toggle"></div>
        </div>
    <?php } ?>
<?php } ?>
<!-- /Edit Vendor User Modal -->

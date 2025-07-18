<!-- New Vendor User Modal -->
<div id="addNewVendorUserModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Create User</h2>
        </div>
        <div class="modal__body center center--h align--left cf">
            <form id="newVendorUserForm" class="form__group modal__content" action="<?php echo base_url(); ?>add-newUser-Vendor" method="post">
                <div class="row">
                    <div class="input__group is--inline">
                        <input type="hidden" name="vendor_id" value="<?php echo $vendor_userInvitationId ?>">
                        <input id="accountName" name="accountName" class="input" type="text" placeholder="Kevin McCallister" required>
                        <label class="label" for="accountName">Full Name</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input__group is--inline">
                        <input id="accountEmail" name="accountEmail" class="input" type="email" placeholder="email@example.com" pattern=".*\S.*" required>
                        <label class="label" for="accountEmail">Email Address</label>
                    </div>
                </div>
                <div class="row">
                    <div class="select">
                        <select aria-label="Select a Title" name="role_id" required>
                            <!--                            <option disabled selected value="default">Select Role</option>-->
                            <option value="11">Vendor</option>
                            <!--                            <option value="2">Role Two</option>
                                                        <option value="3">Role Three</option>-->
                        </select>
                    </div>
                </div>
                <div class="footer__group border--dashed">
                    <button class="btn btn--m btn--primary btn--block save--toggle form--submit" data-target="#newVendorUserForm" type="submit">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /New Vendor User Modal -->

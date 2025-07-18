<!-- New Admin Modal -->
<div id="addNewAdminModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Create New Admin</h2>
        </div>
        <div class="modal__body center center--h align--left cf">
            <form id="newAdmin" class="modal__content" action="<?php echo base_url(); ?>SPdashboard-invite-SPusers" method="post">
                <div class="input__group is--inline margin--xs no--margin-lr no--margin-t">
                    <input id="accountName" name="accountName" class="input" type="text" required>
                    <label class="label" for="accountName">Full Name</label>
                </div>
                <div class="input__group is--inline margin--xs no--margin-lr no--margin-t">
                    <input id="accountEmail" name="accountEmail" class="input" type="email" placeholder="email@example.com" pattern=".*\S.*" required>
                    <label class="label" for="accountEmail">Email Address</label>
                </div>
                <div class="select">
                    <select name="role_id"required>
                        <option disabled selected>Select Role</option>
                        <option value="1">Super Admin</option>
                        <option value="2">Admin</option>
                    </select>
                </div>
                <div class="footer__group border--dashed">
                    <button class="btn btn--m btn--primary btn--block save--toggle form--submit" data-target="#newAdmin" type="submit">Create Admin User</button>
                </div>
            </form>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /New Admin Modal -->

<!-- Invite New User Modal -->
<div id="inviteUserModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Invite a new user</h2>
        </div>
        <div class="modal__body center center--h align--left cf">
            <form id="inviteNewUser" class="form__group" method="post" action="<?php echo base_url(); ?>vendor-invitation">
                <div class="modal__content">
                    <div class="row">
                        <div class="input__group is--inline">
                            <input id="userEmail" name="first_name" class="input" type="text" required>
                            <label class="label" for="userEmail">User Name</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input__group is--inline">
                            <input id="userEmail" name="userEmail" class="input" type="email" pattern=".*\S.*" required>
                            <label class="label" for="userEmail">Email Address</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input__group is--inline">
                            <div class="select">
                                <select name="role_id" required>
                                    <!--                      <option disabled selected value="default">Select a Role</option>-->
                                    <option value="11">Vendor</option>
                                    <!--
                                                          <option value="1">Tier 2</option>
                                                          <option value="2">Tier 3</option>-->
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="footer__group border--dashed">
                    <button class="btn btn--m btn--primary btn--block save--toggle form--submit" data-target="#inviteNewUser">Send Invitation</button>
                </div>
            </form>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Invite New User Modal -->

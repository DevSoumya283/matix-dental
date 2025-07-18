<!-- edit Users Modal -->

<?php foreach ($user_details as $details) { ?>

<div id="editOrganizationUserModal<?php echo $details->id; ?>" class="modal modal--m">

    <div class="modal__wrapper modal__wrapper--transition padding--l">

        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>

        <div class="modal__header center center--h align--left mobile-center">

            <h2>Edit a user</h2>

        </div>

        <div class="modal__body center center--h align--left cf">

            <form id="newUser" class="modal__content" action="<?php echo base_url(); ?>update-organization-user" method="post">

                <div class="input__group is--inline margin--xs no--margin-lr no--margin-t">

                    <input type="hidden" name="user_id" value="<?php echo $details->id; ?>">

                    <input type="hidden" name="organization_id" value="<?php echo $organization_id; ?>">

                    <input id="accountName" name="accountName"   value="<?php echo $details->first_name; ?>" class="input <?php echo ($details->first_name != null) ? "not--empty" : ""; ?>" type="text" placeholder="Name" required>

                    <label class="label" for="accountName">Name</label>

                </div>

                <div class="input__group is--inline margin--xs no--margin-lr no--margin-t">

                    <input id="accountEmail"   value="<?php echo $details->email; ?>" class="input <?php echo ($details->email != null) ? "not--empty" : ""; ?>" type="email" placeholder="email@example.com" pattern=".*\S.*" disabled>

                    <label class="label" for="accountEmail">Email Address</label>

                </div>

                <div class="select">



                    <?php

                    $role_limit = 7;

                    if ($organization_role_id < $role_limit) {

                        ?>

                        <select name="role_id" aria-label="Select a Title" required>

                            <option value="3"<?php echo ($details->role_id == "3") ? "selected" : ""; ?>>Corporate Admin- Tier 1</option>

                            <option value="4" <?php echo ($details->role_id == "4") ? "selected" : ""; ?>>Purchasing Manager- Tier 2</option>

                            <option value="5" <?php echo ($details->role_id == "5") ? "selected" : ""; ?>>Office Manager- Tier 3A</option>

                            <option value="6" <?php echo ($details->role_id == "6") ? "selected" : ""; ?>>Office Assistant- Tier 3B</option>                        

                        </select>

                        <?php } else { ?>

                        <select name="role_id" aria-label="Select a Title" required>

                            <option value="7" <?php echo ($details->role_id == "7") ? "selected" : ""; ?>>Institution Admin- Tier 1</option>

                            <option value="8" <?php echo ($details->role_id == "8") ? "selected" : ""; ?>>Institution Director- Tier 2A</option>

                            <option value="9" <?php echo ($details->role_id == "9") ? "selected" : ""; ?>>Instructor- Tier 2B</option>

                            <option value="10" <?php echo ($details->role_id == "10") ? "selected" : ""; ?>>Student- Tier 2C</option>

                        </select>           

                        <?php } ?>

                    </div>

                    <div class="footer__group border--dashed">

                        <button class="btn btn--m btn--primary btn--block save--toggle form--submit" data-target="#editOrganizationUserModal<?php echo $details->id; ?>" type="submit">Save Changes</button>

                    </div>

                </form>

            </div>

        </div>

        <div class="modal__overlay modal--toggle"></div>

    </div>

    <?php } ?>

    <!-- /Assign Users Modal -->




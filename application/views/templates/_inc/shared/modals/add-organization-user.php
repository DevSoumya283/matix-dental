<!-- Assign Users Modal -->
<div id="addOrganizationUserModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left mobile-center">
            <h2>Add a new user</h2>
        </div>
        <div class="modal__body center center--h align--left cf">
            <form id="addOrganizationUser" class="modal__content" action="<?php echo base_url(); ?>invite-user" method="post">
                <div class="input__group is--inline margin--xs no--margin-lr no--margin-t">
                    <input type="hidden" name="Manage_page" value="<?php echo $Manage_usersPage; ?>">
                    <input type="hidden" name="organization_id" value="<?php echo $organization_id; ?>">
                    <input id="accountName" name="accountName" class="input" type="text" placeholder="Name" required>
                    <label class="label" for="accountName">Name</label>
                </div>
                <div class="input__group is--inline margin--xs no--margin-lr no--margin-t">
                    <input id="accountEmail" name="accountEmail" class="input" type="email" placeholder="email@example.com" pattern=".*\S.*" required>
                    <label class="label" for="accountEmail">Email Address</label>
                </div>
                <div class="select">                    
                    <?php
                    $role_limit = 7;
                    if ($organization_role_id < $role_limit) {
                        ?>
                        <select name="role_id" aria-label="Select a Title" required>
                            <option disabled="" selected="" value="default">Select Role</option>
                            <option value="3">Corporate Admin- Tier 1</option>
                            <option value="4">Purchasing Manager- Tier 2</option>
                            <option value="5">Office Manager- Tier 3A</option>
                            <option value="6">Office Assistant- Tier 3B</option>                        
                        </select>
                        <?php } else { ?>
                        <select name="role_id" aria-label="Select a Title" required>
                            <option disabled="" selected="" value="default">Select Role</option>
                            <option value="7">Institution Admin- Tier 1</option>
                            <option value="8">Institution Director- Tier 2A</option>
                            <option value="9">Instructor- Tier 2B</option>
                            <option value="10">Student- Tier 2C</option>                        
                        </select>           
                        <?php } ?>
                    </div><br>
                    <div class="select">
                        <select name="location_id" aria-label="Select a Title" class="locations" required>
                            <option value="">----Select Location-----</option>
                            <?php for ($x = 0; $x < count($user_locations); $x++) { ?>
                            <option value="<?php echo $user_locations[$x]->id; ?>"><?php echo $user_locations[$x]->nickname; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="footer__group border--dashed">
                        <button class="btn btn--m btn--primary btn--block save--toggle form--submit" type="submit">Add Users</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal__overlay modal--toggle"></div>
    </div>
<!-- /Assign Users Modal -->
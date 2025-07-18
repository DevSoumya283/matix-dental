<!-- Assign Users Modal -->
<div id="addNewUserModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Add a new user</h2>
        </div>
        <div class="modal__body center center--h align--left cf">
            <form id="newUser" class="modal__content" action="<?php echo base_url(); ?>invite-organization-user" method="post">
                <div class="input__group is--inline margin--xs no--margin-lr no--margin-t">
                    <input type="hidden" name="organization_id" value="<?php echo $organization_id; ?>">
                    <input id="accountName" name="accountName" class="input" type="text" placeholder="Name" required>
                    <label class="label" for="accountName">Name</label>
                </div>
                <div class="input__group is--inline margin--xs no--margin-lr no--margin-t">
                    <input id="accountEmail" name="accountEmail" class="input" type="email" placeholder="email@example.com" pattern=".*\S.*" required>
                    <label class="label" for="accountEmail">Email Address</label>
                </div>
                <div class="select">

                    <?php if ($organization_type == 'Business') { ?>
                        <select name="role_id" aria-label="Select a Title" required>
                            <option disabled="" selected="" value="default">Select Role</option>
                            <option value="3">Corporate Admin- Tier 1</option>
                            <option value="4">Purchasing Manager- Tier 2</option>
                            <option value="5">Office Manager- Tier 3A</option>
                            <option value="6">Office Assistant- Tier 3B</option>                        
                        </select>
                    <?php } ?>
                    <?php if ($organization_type == 'School') { ?>
                        <select name="role_id" aria-label="Select a Title" required>
                            <option disabled="" selected="" value="default">Select Role</option>
                            <option value="7">Institution Admin- Tier 1</option>
                            <option value="8">Institution Director- Tier 2A</option>
                            <option value="9">Instructor- Tier 2B</option>
                            <option value="10">Student- Tier 2C</option>                        
                        </select>                        
                    <?php } ?>
                </div>
                <div class="footer__group border--dashed">
                    <button class="btn btn--m btn--primary btn--block save--toggle form--submit" data-target="#newUser" type="submit">Add Users</button>
                </div>
            </form>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Assign Users Modal -->

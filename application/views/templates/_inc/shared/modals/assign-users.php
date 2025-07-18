<!-- Assign Users Modal -->
<div id="assignUsersModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition no--pad">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x page__reloadLocation"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left padding--l">
            <h2>Assign users to this location</h2>
            <form action="">
                <div class="input__group input__group--inline">
                    <input id="site-search" class="input input__text userLocationAddSearch" type="text" value="" placeholder="Search by name..." name="search" required>
                    <label for="site-search" class="label">
                        <svg class="icon icon--search textColor--gray"><use xlink:href="#icon-search"></use></svg>
                    </label>
                </div>
            </form>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content padding--l bg--lightest-gray">
                <div id="user_results">
                    <ul class="add-users">
                        <?php if ($organizationUsers != null) { ?>
                            <?php foreach ($organizationUsers as $user) { ?>
                                <li class="user padding--s no--pad-l no--pad-r cf">
                                    <div class="entity__group">
                                        <?php if ($user->photo != null) { ?>
                                            <div class="avatar avatar--s" style="background-image:url('<?php echo image_url(); ?>uploads/user/profile/<?php echo $user->photo ?>');"></div>
                                        <?php } else { ?>
                                            <div class="avatar avatar--s" style="background-image:url('<?php echo image_url(); ?>assets/img/avatar-default.png');"></div>
                                        <?php } ?>
                                        <?php echo $user->first_name; ?>
                                    </div>
                                    <button class="btn btn--s btn--tertiary btn--toggle float--right width--fixed-75 assign_userLocation" data-location_id="<?php echo $location_id; ?>"  value="<?php echo $user->user_id; ?>" data-before="Select" data-after="&#10003;" type="button"></button>
                                </li>
                            <?php } ?>
                        <?php } else { ?>
                            <li class="user padding--s no--pad-l no--pad-r cf">
                                <div class="entity__group">
                                    No Users to Assign
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                    <div class="footer__group border--dashed">
                        <button class="btn btn--m btn--primary btn--block page__reloadLocation" type="submit">Add Selected User(s)</button>
                    </div>
                </div>
            </div>
            <div class="modal__footer padding--l">
                <p class="fontSize--m textColor--darkest-gray">Create New User</p>
                <form id="formName" class="form__group" method="post" action="<?php echo base_url(); ?>Invite-User-forLocation">
                    <div class="wrapper">
                        <div class="input__group is--inline wrapper__inner padding--xs no--pad-t no--pad-b no--pad-l">
                            <input id="accountEmail" name="accountName" class="input" type="text" placeholder="Name" required>
                            <label class="label" for="accountName">Name</label>
                        </div>
                        <div class="input__group is--inline wrapper__inner padding--xs no--pad-t no--pad-b no--pad-l">
                            <input type="hidden" class="location_id" name="location_id" value="<?php echo $location_id; ?>">
                            <input type="hidden" class="organization_id" name="organization_id" value="<?php echo $organization_id; ?>">
                            <input id="accountEmail" name="accountEmail" class="input" type="email" placeholder="Email Address" pattern=".*\S.*" required>
                            <label class="label" for="accountEmail">Email Address</label>
                        </div>
                        <div class="select wrapper__inner">
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
                        </div>
                        <!--                        <div class="wrapper__inner">
                                                    <button class="btn btn--s btn--tertiary btn--toggle float--right width--fixed-75" data-before="Select" data-after="&#10003;" type="button"></button>
                                                </div>-->
                    </div>
                    <div class="footer__group border--dashed">
                        <button class="btn btn--m btn--primary btn--block save--toggle form--submit" data-target="#formName" type="submit">Add Users</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Assign Users Modal -->

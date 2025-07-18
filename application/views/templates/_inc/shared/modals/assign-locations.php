<!-- Assign Locations Modal -->
<div id="assignLocationsModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition no--pad">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left padding--l">
            <h2>Location Assignment</h2>
            <form method="post" action="">
                <div class="input__group input__group--inline">
                    <input type="hidden" name="organization_id" id="organization_id" value="<?php echo $organization_id; ?>">
                    <input id="site-search" class="input input__text search_location" type="search" value="" placeholder="Search by name..." name="search" required>
                    <label for="site-search" class="label">
                        <svg class="icon icon--search textColor--gray"><use xlink:href="#icon-search"></use></svg>
                    </label>
                </div>
            </form>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content padding--l bg--lightest-gray">
                <input type="hidden" name="customer_id" id="customer_id" value="<?php echo $customer_id ?>">
                <ul class="list list--entities" id="org_location_ul">
                    <!-- Single Location -->
                    <?php foreach ($organizaton_locations as $locations) { ?>
                        <li class="item card padding--xs cf org_location">
                            <input type="hidden" name="organization_location_id" id="organization_location_id" value="<?php echo $locations->id; ?>">
                            <div class="wrapper">
                                <div class="wrapper__inner">
                                    <?php echo $locations->nickname; ?>
                                </div>
                                <?php if ($locations->status == 1) { ?>
                                    <div class="wrapper__inner align--right">
                                        <button class="btn btn--s is--pos btn--toggle width--fixed-75 unassign_location" data-before="Select" data-location_id="<?php echo $locations->user_location_id; ?>"  data-after="&#10003;" type="button"></button>
                                    </div>
                                <?php } else { ?>
                                    <div class="wrapper__inner align--right">
                                        <button class="btn btn--s btn--tertiary btn--toggle width--fixed-75 assign_location" data-before="Select" data-after="&#10003;" type="button"></button>
                                    </div>
                                <?php } ?>
                            </div>
                        </li>
                    <?php } ?>
                    <!-- /Single Location -->
                </ul>
            </div>
            <div class="modal__footer padding--l">
                <div class="footer__group border--dashed">
                    <button class="btn btn--m btn--primary btn--block save--toggle page__reloadLocation" type="submit">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Assign Users Modal -->

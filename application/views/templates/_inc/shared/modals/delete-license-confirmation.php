<!-- Delete License Confirmation Modal -->
<div id="deleteLicenseModal" class="modal modal--l">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Are you sure?</h2>
            <p class="margin--m no--margin-r no--margin-t no--margin-l">Deleting this license is an irreversible action. Please make sure you want to do this.</p>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">
                <!-- License Card Item -->
                <form  id="FormDelete" class="form__group" action="<?php echo base_url("delete-licence"); ?>" method="post">
                    <div class="license__card card padding--s in--modal">
                        <ul class="list list--table list--stats list--divided">
                            <li class="item item--stat stat-s">
                                <div class="text__group">
                                    <span class="line--main del_id"></span>
                                    <input type="hidden" name="licenseId" value="" id="licenseId" />
                                    <span class="line--sub">License #</span>
                                </div>
                            </li>
                            <li class="item item--stat stat-s">
                                <div class="text__group">
                                    <span class="line--main" id="liDea"></span>
                                    <span class="line--sub">DEA #</span>
                                </div>
                            </li>
                            <li class="item item--stat stat-s">
                                <div class="text__group">
                                    <span class="line--main" id="liDate"></span>
                                    <span class="line--sub">Expires</span>
                                </div>
                            </li>
                            <li class="item item--stat stat-s">
                                <div class="text__group">
                                    <span class="line--main" id="liState"></span>
                                    <span class="line--sub">State</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <!-- /License Card Item -->
                    <div class="footer__group border--dashed">
                        <button class="btn btn--m btn--block default--action is--neg form--submit page--reload" data-target="#FormDelete">Confirm Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Delete License Confirmation Modal -->
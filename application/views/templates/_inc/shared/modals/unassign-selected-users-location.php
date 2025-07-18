<!-- Unassign User Modal -->
<div id="unassignSelectedUsersLocationModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left mobile-center">
            <h2>Unassign selected user(s)?</h2>
            <p class="margin--l no--margin-r no--margin-t no--margin-l">They will no longer be able to manage this location.</p>
        </div>
        <div class="modal__body center center--h align--left cf">

            <div class="modal__content modal-margin">
                <form class="form__group" action="<?php echo base_url(); ?>unassign-user-manage-location" method="post">
                    <input type="hidden" name="user_id" id="user_id" value="">
                    <input type="hidden" name="location_id" value="<?php echo $location_id; ?>">
                    <table>
                        <tbody class="student_image">
                            The selected User(s) will be removed from this Location
                        </tbody>
                    </table>
                    <div class="footer__group border--dashed">
                        <button class="btn btn--m btn--block is--neg form--submit" type="submit">Remove Selected Users</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Unassign User Modal -->

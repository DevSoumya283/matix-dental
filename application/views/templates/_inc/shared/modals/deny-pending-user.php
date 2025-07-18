<!-- /Approve Pending User Modal -->
<div id="denyPendingUserModal" class="modal modal--m">
    <form id="DeletePendingUser" action="<?php echo base_url(); ?>deny-pending-user" method="post">
        <div class="modal__wrapper modal__wrapper--transition padding--l">
            <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
            <div class="modal__header center center--h align--left">
                <h2>Deny this license?</h2>
                <p class="margin--m no--margin-r no--margin-t no--margin-l">Are you sure? This cannot be undone.</p>
            </div>
            <div class="modal__body center center--h align--left cf">
                <div class="modal__content">
                    <div class="card padding--s">
                        <div class="entity__group">
    <!--                        <div class="avatar avatar--s" style="background-image:url('<?php echo image_url(); ?>assets/img/ph-avatar.jpg');"></div>-->
                            The selected user(s) license will be Denied
                        </div>
                    </div>
                    <div class="footer__group border--dashed">
                        <input type="hidden" name="user_id" value="" id="deleteUser_id">
                        <button class="btn btn--m btn--primary is--neg btn--block form--submit">Deny User</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- Approve Pending User Modal -->

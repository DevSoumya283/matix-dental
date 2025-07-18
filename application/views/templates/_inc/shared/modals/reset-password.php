<!-- Reset Password Modal -->
<div id="resetPasswordModal" class="modal modal--m">
    <form id="resetPassword" action="<?php echo base_url(); ?>SPdashboard-passwordReset-notification" method="post">
        <div class="modal__wrapper modal__wrapper--transition padding--l">
            <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
            <div class="modal__header center center--h align--left">
                <h2>Send a password reset?</h2>
                <p class="margin--m no--margin-r no--margin-t no--margin-l">The selected user(s) will recieve a link to reset their password via email, and will remain active for 24 hours.</p>
            </div>
            <div class="modal__body center center--h align--left cf">
                <div class="modal__content">
                    <div class="footer__group border--dashed">
                        <input type="hidden" name="user_id" value="" id="user_id">
                        <!--                        <button class="btn btn--primary btn--m btn--block default--action form--submit page--reload" data-target="#resetPassword" type="submit">Reset Password</button>-->
                        <button class="btn btn--primary btn--m btn--block form--submit page--reload" type="submit">Reset Password</button>
                    </div>
                </div>
            </div>
        </div>
    </form>        
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Reset Password Modal -->

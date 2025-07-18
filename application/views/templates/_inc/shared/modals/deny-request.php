<!-- Deny Request Modal -->
<div id="denyRequestModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Are you sure?</h2>
            <p class="margin--m no--margin-lr no--margin-t">This will cancel the request and inform the customer.</p>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">
                <hr>
                <p class="margin--s no--margin-lr no--margin-t textColor--darkest-gray fontWeight--2">Reason for denial (optional)</p>
                <?php // echo ROOT_PATH . 'templates/vendor-admin/returns/r/denied'; ?>
                <form id="denyRequestForm" class="form__group" action="<?php echo base_url(); ?>deny-request-OrderReturn" method="post">
                    <div class="row">
                        <div class="input__group is--inline">
                            <input type="hidden" name="return_id" value="" class="return_id">
                            <textarea name="reason" placeholder="Enter the reason for refusing this request..." class="input input--l input--show-placeholder">                               
                            </textarea>
                        </div>
                    </div>
                    <div class="footer__group border--dashed">
                        <button class="btn btn--m btn--primary btn--block is--neg btn--block save--toggle form--submit" type="submit" data-target="#denyRequestForm">Deny Return Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Deny Request Modal -->

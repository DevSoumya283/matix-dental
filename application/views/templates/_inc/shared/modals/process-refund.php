<!-- Process Refund Modal -->
<div id="processRefundModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <form method="post" action="<?php echo base_url(); ?>processRefund-returnOrder">
<!--            data-target="<?php // echo ROOT_PATH . 'templates/vendor-admin/returns/r/refunded';    ?>">-->
            <div class="modal__header center center--h align--left">
                <h2>Process refund?</h2>
                <p class="margin--m no--margin-lr no--margin-t">The customer will be notified and their <?php echo $payment_details->payment_type . " Number "; ?> (ending in <?php
                    if ($payment_details->payment_type == "bank") {
                        echo $payment_details->ba_account_number;
                    } else {
                        echo $payment_details->cc_number;
                    }
                    ?>) will be refunded <span class="fontWeight--2"><?php echo "$" . $grand_total; ?></span> immediately.</p>
            </div>
            <div class="modal__body center center--h align--left cf">
                <input  type="hidden" name="return_id" value="" class="return_id">
                <input type="hidden" name="total_count" value="<?php echo $grand_total; ?>">
                <div class="modal__content">
                    <button class="btn btn--primary btn--block btn--m is--link" type="submit">Process Refund</button>
                </div>
            </div>
        </form>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Process Refund Modal -->

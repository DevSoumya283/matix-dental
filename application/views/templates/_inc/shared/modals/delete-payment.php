<!-- Delete Payment Modal -->
<div id="deletePaymentModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left mobile-center">
            <h2>Are you sure?</h2>
            <p class="margin--m no--margin-r no--margin-t no--margin-l">Are you sure? This will be effective immediately.</p>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">
                <form  id="deletePayment" class="form__group" action="<?php echo base_url("delete-payment"); ?>" method="post">
                    <table class="table table--payments fontSize--s">
                        <tbody>
                            <tr class="payment__method">
                                <td width="70%">
                                    <svg class="icon icon--cc icon--undefined" style="float: left;"><p class="cnum"></p></svg>
                                    <input type="hidden" name="delete_id" value="" class="delete_id">
                                </td>
                                <td width="30%">
                                    Exp. <p class="expdate"></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="footer__group border--dashed">
                        <button class="btn btn--primary btn--m btn--block save--toggle is--neg form--submit page--reload" data-target="#deletePayment">Delete Payment Method</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Delete Payment Modal -->
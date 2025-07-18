<!-- Bank Account Modal -->
<div id="bankAccountModal" class="modal modal--m modal--overlap">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Bank Account</h2>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">
                <!-- Payment Method Form -->
                <div class="box__group">
                    <form id="paymentMethodBank" class="form__group method__forms cc" action="" method="post">
                        <!-- Bank Account -->
                        <input type="hidden" name="paymentId" value=""  class="p_id">
                        <input type="hidden" name="paymentId" value=""  class="paymentType">
                        <span class="payment-errors" style="color: red;"></span>
                        <div id="methodBank" class="method__form disp--block">
                            <div class="row">
                                <div class="input__group is--inline">
                                    <input id="paymentBankName" type="text" value="" class="input paymentBankName" name="paymentBankName"  pattern=.*\S.* disabled="">
                                    <label class="label" for="paymentBankName"></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input__group is--inline">
                                    <input id="accountholdersName" name="accountholderName" class="input cname" type="text" required>
                                    <label class="label" for="accountholderName"></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="select">
                                    <select name="accountholderType"  id="accountholdersType" class="sub_category" required>
                                        <option value="individual">Individual</option>
                                        <option value="company">Company</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input__group is--inline">
                                    <input id="paymentAccountNumber" class="input paymentAccountNum" name="paymentAccountNum" type="text" value="" pattern=.*\S.* required>
                                    <label class="label" for="paymentAccountNum"></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input__group is--inline">
                                    <input id="paymentRoutingNumber" class="input paymentRoutingNum"  name="paymentRoutingNum" type="text" value="" pattern=.*\S.* required>
                                    <label class="label" for="paymentRoutingNum"></label>
                                </div>
                            </div>
                        </div>
                        <!-- /Bank Account -->

                        <div class="footer__group border--dashed row">
                            <div class="col col--3-of-6 col--ab">
                                <a class="fontSize--s textColor--negative fontWeight--2 modal--toggle modal--overlap-btn del_payment" data-id="" data-target="#deletePaymentModal">Delete Payment Method</a>
                            </div>
                            <div class="col col--3-of-6 col--ab">
                                <button class="btn btn--m btn--primary btn--block save--toggle form--submit update_bank" data-target="#paymentMethodBank">Save Payment Method</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /Payment Method Form -->
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Bank Account Modal -->

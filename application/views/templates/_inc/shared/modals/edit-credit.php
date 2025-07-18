<!-- Credit Card Modal -->
<div id="creditCardModal" class="modal modal--m modal--overlap">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left mobile-center">
            <h2>Credit/Debit Card</h2>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">
                <!-- Payment Method Form -->
                <div class="box__group">
                    <!-- Credit/Debit Card -->
                    <span class="payment-errors" style="color: red;"></span>
                    <input type="hidden" name="payment_token" class="token" value="">
                    <input type="hidden" name="payment_id" class="p_id" value="">
                    <input type="hidden" name="paymentType" class="paymentType" value="">
                    <div id="methodCreditCard" class="form__group method__form">
                        <div class="row">
                            <div class="input__group is--inline has--icon cc col-md-12 col-xs-12">
                             <label class="label" for="paymentCardNum"></label>
                             <svg class="icon icon--cc icon--undefined"></svg>
                             <input id="paymentCardNumber" name="paymentCardNum" class="input input--cc paymentCardNum" type="text" disabled>
                         </div>
                     </div>
                     <div class="row">
                        <div class="input__group is--inline col-md-12 col-xs-12">
                            <input id="paymentCardName" name="paymentCardName" class="input paymentCardName" type="text" required>
                            <label class="label" for="paymentCardName"></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <div class="input__group is--inline">
                               <input id="paymentExpire" class="input input--cc-exp paymentExpiry" name="paymentExpiry" type="text" value="" required>
                               <label class="label" for="paymentExpiry"></label>
                           </div>
                       </div>
                       <div class="col-md-6 col-xs-12">
                        <div class="input__group is--inline">
                            <input id="paymentSecurityNum" name="paymentSecurity " class="input paymentSecurity" type="text" disabled>
                            <label class="label" for="paymentSecurity"></label>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Credit/Debit Card -->
            <div class="footer__group border--dashed row" id="newUser">
                <div class="col-md-6 col-xs-6">
                    <a class="fontSize--s textColor--negative fontWeight--2 modal--toggle modal--overlap-btn del_payment" data-id="" data-target="#deletePaymentModal">Delete Payment Method</a>
                </div>
                <div class="col-md-6 col-xs-6">
                    <button class="btn btn--m btn--primary btn--block save--toggle form--submit update-card" >Save Payment Method</button>
                </div>
            </div>
        </div>
        <!-- /Payment Method Form -->
    </div>
</div>
</div>
<div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Credit Card Modal -->
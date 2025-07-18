<!-- Payment Method Form -->
<div class="box__group" >
    <div id="paymentMethods" class="states__group method__forms is--cc" data-states="is--cc is--bank">
       <form id="newPaymentForm" action="" method="post">
        <div class="tab__group tab--block" data-target="#paymentMethods">
            <label class="tab" value="is--cc">
                <input type="radio" name="paymentType" value="1" checked>
                <span>Credit/Debit Card</span>
            </label>
            <label class="tab" value="is--bank">
                <input type="radio" name="paymentType" value="2">
                <span>Bank Account</span>
            </label>
        </div>
        <!-- Credit/Debit Card -->
        <span class="payment-errors" style="color: red;"></span>
        <div id="methodCreditCard" class="form__group method__form">
            <div class="row">
                <div class="input__group is--inline has--icon cc col-md-12 col-xs-12">
                    <input id="paymentCardNum" name="paymentCardNum" class="input input--cc" type="text" required>
                    <label class="label pl-4" for="paymentCardNum">Card Number</label>
                    <svg class="icon icon--cc icon--undefined"></svg>
                </div>
            </div>
            <div class="row">
                <div class="input__group is--inline col-md-12 col-xs-12">
                    <input id="paymentCardName" name="paymentCardName" class="input" type="text" required>
                    <label class="label pl-4" for="paymentCardName">Name on Card</label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <div class="input__group is--inline">
                        <input id="paymentExpiry" name="paymentExpiry" class="input input--cc-exp" type="text" required>
                        <label class="label pl-4" for="paymentExpiry">Expiry(MM/YY)</label>
                    </div>
                </div>
                <div class="col-md-6 col-xs-12 paymentSecurity">
                    <div class="input__group is--inline ">
                        <input id="paymentSecurity" name="paymentSecurity" class="input" type="text" required>
                        <label class="label pl-4" for="paymentSecurity">Security Code</label>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Credit/Debit Card -->
        <!-- Bank Account -->
        <div id="methodBank" class="form__group method__form">
            <div class="row">
                <div class="input__group is--inline col-md-12 col-xs-12">
                    <input id="accountholderName" name="accountholderName" class="input" type="text" required>
                    <label class="label pl-4" for="accountholderName">Account Holder Name</label>
                </div>
            </div>
            <div class="row">
               <div class="select col-md-12 col-xs-12">
                <select name="accountholderType"  id="accountholderType" class="sub_category" required>
                  <option value="individual">Individual</option>
                  <option value="company">Company</option>
              </select>
          </div>
      </div>
      <div class="row">
        <div class="input__group is--inline col-md-12 col-xs-12">
            <input id="paymentAccountNum" name="paymentAccountNum" class="input" type="number" required>
            <label class="label pl-4" for="paymentAccountNum">Account Number</label>
        </div>
    </div>
    <div class="row">
        <div class="input__group is--inline col-md-12 col-xs-12">
            <input id="paymentRoutingNum" name="paymentRoutingNum" class="input" type="number" required>
            <label class="label pl-4" for="paymentRoutingNum">Routing Number</label>
        </div>
    </div>
</div>
<!-- /Bank Account -->
<div class="footer__group border--dashed">
    <button class="btn btn--m btn--primary btn--block save--toggle add-payment" id="newword">Save Payment Method</button>
</div>
</form>
</div>
</div>
<!-- /Payment Method Form -->
<!-- Change Payment Modal -->
<div id="changePaymentModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header">
            <h2>Change Payment Method</h2>
        </div>
        <div class="modal__body cf margin--l no--margin-b no--margin-lr">
            <div class="modal__content">
                <div class="row no--margin-l">
                    <ul class="list list--text">
                        <li class="item">
                            <label class="control control__radio">
                                <input type="radio" name="paymentMethod" selected>
                                <div class="control__indicator"></div>
                                <p class="no--margin textColor--darkest-gray"><svg class="icon icon--cc icon--visa"></svg>Visa ending in 4545</p>
                            </label>
                        </li>
                        <li class="item margin--m no--margin-lr no--margin-b wrapper">
                            <label class="control control__radio wrapper__inner valign--top">
                                <input type="radio" name="paymentMethod">
                                <div class="control__indicator"></div>
                            </label>
                            <div class="wrapper__inner width--100">
                                <!-- Payment Method Form -->
                                <div class="box__group">
                                    <div class="tab__group tab--block" data-target="#paymentMethods">
                                        <label class="tab" value="is--cc">
                                            <input type="radio" name="paymentType" checked="">
                                            <span>Credit/Debit Card</span>
                                        </label>
                                        <label class="tab" value="is--bank">
                                            <input type="radio" name="paymentType">
                                            <span>Bank Account</span>
                                        </label>
                                    </div>
                                    <div id="paymentMethods" class="method__forms is--cc">
                                        <div id="methodCreditCard" class="form__group method__form">
                                            <!-- Credit/Debit Card -->
                                            <div class="row">
                                                <div class="input__group is--inline has--icon cc">
                                                    <input id="paymentCardNum" class="input input--cc" type="text" value="" pattern=".*\S.*" required>
                                                    <label class="label" for="paymentCardNum">Card Number</label>
                                                    <svg class="icon icon--cc icon--undefined"></svg>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input__group is--inline">
                                                    <input id="paymentCardName" class="input" type="text" value="" pattern=".*\S.*" required>
                                                    <label class="label" for="paymentCardName">Name on Card</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col col--1-of-2">
                                                    <div class="input__group is--inline">
                                                        <input id="paymentExpiry" class="input input--cc-exp" type="text" value="" pattern=".*\S.*" required>
                                                        <label class="label" for="paymentExpiry">Expiration Date (MM/YY)</label>
                                                    </div>
                                                </div>
                                                <div class="col col--1-of-2">
                                                    <div class="input__group is--inline">
                                                        <input id="paymentSecurity" class="input" type="text" value="" pattern=".*\S.*" required>
                                                        <label class="label" for="paymentSecurity">Security Code</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /Credit/Debit Card -->
                                        </div>
                                        <div id="methodBank" class="form__group method__form">
                                            <!-- Bank Account -->
                                            <div class="row">
                                                <div class="input__group is--inline">
                                                    <input id="accountholderName" class="input" type="text" value="" pattern=".*\S.*" required>
                                                    <label class="label" for="accountholderName">Account Holder Name</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input__group is--inline">
                                                    <input id="paymentAccountNum" class="input" type="text" value="" pattern=".*\S.*" required>
                                                    <label class="label" for="paymentAccountNum">Account Number</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input__group is--inline">
                                                    <input id="paymentRoutingNum" class="input" type="text" value="" pattern=".*\S.*" required>
                                                    <label class="label" for="paymentRoutingNum">Routing Number</label>
                                                </div>
                                            </div>
                                            <!-- /Bank Account -->
                                        </div>
                                    </div>
                                    <!-- /Payment Method Form -->
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="footer__group border--dashed">
                    <button class="btn btn--m btn--primary btn--block modal--toggle default--action">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Change Payment Modal-->

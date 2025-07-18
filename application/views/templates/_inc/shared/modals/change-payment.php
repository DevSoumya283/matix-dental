<!-- Change Payment Modal -->
<div id="changePaymentModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x">
            <svg class="icon icon--x">
                <use xlink:href="#icon-x"></use>
            </svg>
        </a>
        <div class="modal__header mobile-center">
            <h2>Change Payment Method</h2>
        </div>
        <div class="modal__body cf modal-margin">
            <div class="modal__content">

                <div class="row margin--l no--margin-lr">
                    <ul class="list list--text w-100">
                        <li class="payment">
                        </li>
                        <?php
                        if (isset($user_payment_methods) && !empty($user_payment_methods)){
                        foreach ($user_payment_methods as $row) {
                        ?>
                    <li class="item">
                    <label class="control control__radio">
                    <input type="radio" name="paymentMethod" value="<?php echo $row->id; ?>">
                    <div class="control__indicator"></div>
                    <p class="no--margin textColor--darkest-gray">
                    <?php
                    $card_number = preg_replace('/[^0-9]/', '', $row->cc_number);
                    $inn = (int)mb_substr($card_number, 0, 2);
                    $card_number = substr($row->cc_number, -4);
                    $account_number = substr($row->ba_account_number, -4);
                    if (($row->payment_type) == 'card') {
                        ?>
                        <?php if ($row->card_type == 'Visa') { ?>
                            <svg class="icon icon--cc icon--visa"></svg>
                            <?php echo $row->card_type . " •••• "; ?>
                            <?php echo $row->cc_number; ?>
                        <?php } else if ($row->card_type == 'MasterCard') {
                            ?>
                            <svg class="icon icon--cc icon--mastercard"></svg>
                            <?php
                            echo $row->card_type . " •••• ";
                            echo $row->cc_number;
                            ?>
                        <?php } else if ($row->card_type == 'Discover') {
                            ?>
                            <svg class="icon icon--cc icon--discover"></svg>
                            <?php
                            echo $row->card_type . " •••• ";
                            echo $row->cc_number;
                            ?>
                        <?php } else if ($row->card_type == 'American Express') {
                            ?>
                            <svg class="icon icon--cc icon--amex"></svg>
                            <?php
                            echo $row->card_type . " •••• ";
                            echo $row->cc_number;
                            ?>
                        <?php } else {
                            ?>
                            <svg class="icon icon--cc icon--undefined"></svg>
                            <?php
                            echo $type = 'undefined Card' . " •••• ";
                            echo $card_number;
                            ?>
                        <?php }
                        ?>
                        </p>
                        </label>
                        </li>
                        <?php
                    } elseif (($row->payment_type) == 'bank') {
                    ?>
                        <li class="item">
                            <label class="control control__radio">
                                <input type="radio" name="paymentMethod" value="<?php echo $row->id; ?>">
                                <div class="control__indicator"></div>
                                <p class="no--margin textColor--darkest-gray">
                                    <svg class="icon icon--cc icon--bank"></svg>
                                    <?php echo $row->bank_name; ?> Account •••• <?php print $account_number; ?>
                                </p>
                                <?php
                                }
                                }
                                }
                                if (in_array($user->role_id, unserialize(ROLES_TIER2)) && isset($tier1_payment_methods) && !empty($tier1_payment_methods)) {
                                ?>
                                <span><small>Tier 1 payment methods</small></span>
                                <?php
                                foreach ($tier1_payment_methods

                                as $row) {
                                ?>
                    <li class="item">
                    <label class="control control__radio">
                    <input type="radio" name="paymentMethod" value="<?php echo $row->id; ?>">
                    <div class="control__indicator"></div>
                    <p class="no--margin textColor--darkest-gray">
                    <?php
                    $card_number = preg_replace('/[^0-9]/', '', $row->cc_number);
                    $inn = (int)mb_substr($card_number, 0, 2);
                    $card_number = substr($row->cc_number, -4);
                    $account_number = substr($row->ba_account_number, -4);
                    if (($row->payment_type) == 'card') {
                        ?>
                        <?php if ($row->card_type == 'Visa') { ?>
                            <svg class="icon icon--cc icon--visa"></svg>
                            <?php echo $row->card_type . " •••• "; ?>
                            <?php echo $row->cc_number; ?>
                        <?php } else if ($row->card_type == 'MasterCard') {
                            ?>
                            <svg class="icon icon--cc icon--mastercard"></svg>
                            <?php
                            echo $row->card_type . " •••• ";
                            echo $row->cc_number;
                            ?>
                        <?php } else if ($row->card_type == 'Discover') {
                            ?>
                            <svg class="icon icon--cc icon--discover"></svg>
                            <?php
                            echo $row->card_type . " •••• ";
                            echo $row->cc_number;
                            ?>
                        <?php } else if ($row->card_type == 'American Express') {
                            ?>
                            <svg class="icon icon--cc icon--amex"></svg>
                            <?php
                            echo $row->card_type . " •••• ";
                            echo $row->cc_number;
                            ?>
                        <?php } else {
                            ?>
                            <svg class="icon icon--cc icon--undefined"></svg>
                            <?php
                            echo $type = 'undefined Card' . " •••• ";
                            echo $card_number;
                            ?>
                        <?php }
                        ?>
                        </p>
                        </label>
                        </li>
                        <?php
                    } elseif (($row->payment_type) == 'bank') {
                    ?>
                    <li class="item">
                    <label class="control control__radio">
                    <input type="radio" name="paymentMethod" value="<?php echo $row->id; ?>">
                    <div class="control__indicator"></div>
                    <p class="no--margin textColor--darkest-gray">
                        <svg class="icon icon--cc icon--bank"></svg>
                        <?php echo $row->bank_name; ?> Account •••• <?php print $account_number; ?>
                    </p>
                    <?php
                    }
                    ?>
                    </label>
                    </li>
                    <?php } ?>
                    <?php
                    } elseif (in_array($user->role_id, unserialize(ROLES_TIER1)) && isset($tier2_payment_methods) && !empty($tier2_payment_methods)) {
                        ?>
                        <span><small>Tier 2 payment methods</small></span>
                        <?php
                        foreach ($tier2_payment_methods as $row) {
                            ?>
                            <li class="item">
                            <label class="control control__radio">
                            <input type="radio" name="paymentMethod" value="<?php echo $row->id; ?>">
                            <div class="control__indicator"></div>
                            <p class="no--margin textColor--darkest-gray">
                            <?php
                            $card_number = preg_replace('/[^0-9]/', '', $row->cc_number);
                            $inn = (int)mb_substr($card_number, 0, 2);
                            $card_number = substr($row->cc_number, -4);
                            $account_number = substr($row->ba_account_number, -4);
                            if (($row->payment_type) == 'card') {
                                ?>
                                <?php if ($row->card_type == 'Visa') { ?>
                                    <svg class="icon icon--cc icon--visa"></svg>
                                    <?php echo $row->card_type . " •••• "; ?>
                                    <?php echo $row->cc_number; ?>
                                <?php } else if ($row->card_type == 'MasterCard') {
                                    ?>
                                    <svg class="icon icon--cc icon--mastercard"></svg>
                                    <?php
                                    echo $row->card_type . " •••• ";
                                    echo $row->cc_number;
                                    ?>
                                <?php } else if ($row->card_type == 'Discover') {
                                    ?>
                                    <svg class="icon icon--cc icon--discover"></svg>
                                    <?php
                                    echo $row->card_type . " •••• ";
                                    echo $row->cc_number;
                                    ?>
                                <?php } else if ($row->card_type == 'American Express') {
                                    ?>
                                    <svg class="icon icon--cc icon--amex"></svg>
                                    <?php
                                    echo $row->card_type . " •••• ";
                                    echo $row->cc_number;
                                    ?>
                                <?php } else {
                                    ?>
                                    <svg class="icon icon--cc icon--undefined"></svg>
                                    <?php
                                    echo $type = 'undefined Card' . " •••• ";
                                    echo $card_number;
                                    ?>
                                <?php }
                                ?>
                                </p>
                                </label>
                                </li>
                                <?php
                            } elseif (($row->payment_type) == 'bank') {
                                ?>
                                <li class="item">
                                <label class="control control__radio">
                                <label class="control control__radio">
                                <input type="radio" name="paymentMethod" value="<?php echo $row->id; ?>">
                                <div class="control__indicator"></div>
                                <p class="no--margin textColor--darkest-gray">
                                    <svg class="icon icon--cc icon--bank"></svg>
                                    <?php echo $row->bank_name; ?> Account •••• <?php print $account_number; ?>
                                </p>
                                <?php
                            }
                            ?>
                            </label>
                            </li>
                        <?php } ?>
                        <?php
                    }
                    ?>
                        <li class="item">
                            <label class="control control__radio">
                                <input type="radio" class="control__conditional newpay" name="paymentMethod" value="new"
                                       data-target="#condNewPaymentMethod">
                                <div class="control__indicator"></div>
                                <p class="no--margin textColor--darkest-gray">
                                    <svg class="icon icon--cc icon--undefined"></svg>
                                    New Payment Method
                                </p>
                            </label>
                        </li>
                        <li id="condNewPaymentMethod" class="item is--conditional starts--hidden">
                            <div class="wrapper__inner width--100">
                                <!-- Payment Method Form -->
                                <div class="box__group">
                                    <div id="paymentMethods" class="states__group method__forms is--cc"
                                         data-states="is--cc is--bank">
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
                                                    <div class="input__group is--inline has--icon cc col-12 pl-0">
                                                        <input id="paymentCardNum" name="paymentCardNum"
                                                               class="input input--cc" type="text" required>
                                                        <label class="label" for="paymentCardNum">Card Number</label>
                                                        <svg class="icon icon--cc icon--undefined"></svg>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="input__group is--inline col-12 pl-0">
                                                        <input id="paymentCardName" name="paymentCardName" class="input"
                                                               type="text" required>
                                                        <label class="label" for="paymentCardName">Name on Card</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col col--1-of-2">
                                                        <div class="input__group is--inline">
                                                            <input id="paymentExpiry" name="paymentExpiry"
                                                                   class="input input--cc-exp" type="text" required>
                                                            <label class="label"
                                                                   for="paymentExpiry">Expiry(MM/YY)</label>
                                                        </div>
                                                    </div>
                                                    <div class="col col--1-of-2">
                                                        <div class="input__group is--inline">
                                                            <input id="paymentSecurity" name="paymentSecurity"
                                                                   class="input" type="text" required>
                                                            <label class="label" for="paymentSecurity">Security
                                                                Code</label>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <!-- /Credit/Debit Card -->

                                            <!-- Bank Account -->
                                            <div id="methodBank" class="form__group method__form">
                                                <div class="row">
                                                    <div class="input__group is--inline col-12 pl-0">
                                                        <input id="accountholderName" name="accountholderName"
                                                               class="input" type="text" required>
                                                        <label class="label" for="accountholderName">Account Holder
                                                            Name</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="select col-12 pl-0">
                                                        <select name="accountholderType" id="accountholderType"
                                                                class="sub_category" required>
                                                            <option value="individual">Individual</option>
                                                            <option value="company">Company</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="input__group is--inline col-12 pl-0">
                                                        <input id="paymentAccountNum" name="paymentAccountNum"
                                                               class="input" type="number" required>
                                                        <label class="label" for="paymentAccountNum">Account
                                                            Number</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="input__group is--inline col-12 pl-0">
                                                        <input id="paymentRoutingNum" name="paymentRoutingNum"
                                                               class="input" type="number" required>
                                                        <label class="label" for="paymentRoutingNum">Routing
                                                            Number</label>
                                                    </div>
                                                </div>

                                            </div>
                                            <!-- /Bank Account -->

                                            <div class="footer__group border--dashed">
                                                <button class="btn btn--m btn--primary btn--block save--toggle add-payment"
                                                        id="newword">Save Payment Method
                                                </button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="footer__group border--dashed" id="close_model">
                    <button class="btn btn--m btn--primary btn--block save--toggle form--submit page--reload payment-data">
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Change Payment Modal-->

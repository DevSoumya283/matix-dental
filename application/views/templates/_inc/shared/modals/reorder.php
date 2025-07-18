<!--Reorder Modal -->
<div id="reorderModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x reorder-clear"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header wrapper margin--m no--margin-lr no--margin-t">
            <div class="wrapper__inner width--50 mobile-center">
                <h2 class="no--margin">Reorder</h2>
            </div>
            <div id="subTotal" class="wrapper__inner width--50 valign--bottom align--right mo  mobile-center">
                <span class="fontSize--s textColor--dark-gray">Subtotal: </span>$<span class="fontWeight--2 new_sub_total"></span>
            </div>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">
                <!-- Reorder Process -->
                <div class="form form--multistep">
                    <!-- Step One Items -->
                    <form id="formReorder1" class="form__group" action="" method="post">
                        <input type="hidden" name="" class="order_id" value="">
                        <h3 class="fontSize--l textColor--darkest-gray">1. Items</h3>
                        <span class="erroMesage" style="color: red;"></span>
                        <table class="table margin--xs no--margin-t no--margin-lr" data-controls="#controlsRequests">
                            <tbody class="order_details">
                                <!-- Requested Item -->
                                <!-- /Requested Item -->
                            </tbody>
                        </table>
                        <input type="hidden" name="subtotalValue" class="subtotalValue" value=""> 
                        <div class="row margin--l no--margin-b no--margin-lr">
                            <button class="btn btn--m btn--primary btn--block save--toggle stepone" data-target="#formReorder1" data-next="#formReorder2" type="button">Next</button>
                        </div>
                    </form>
                    <!-- /Step One Items -->
                    <!-- Step Two Checkout -->
                    <form id="formReorder2" class="form__group starts--hidden" action="" method="post">
                        <h3 class="fontSize--l textColor--darkest-gray">2. Checkout Details</h3>
                        <span class="licenseErroMesage" style="color: red;" ></span>
                        <div class="row">
                            <!-- Location -->
                            <div class="accordion__group">
                                <div class="accordion__section">
                                    <div class="accordion__title wrapper">
                                        <div class="wrapper__inner">
                                            <h3>Location</h3>
                                        </div>
                                        <div class="wrapper__inner align--right">
                                            <a class="link link--expand">Edit</a>
                                        </div>
                                    </div>
                                    <div class="accordion__content">
                                        <div class="accordion__preview">
                                            <span class="fontWeight--2 nickname"></span>
                                            <span class="location_name"></span>
                                        </div>
                                        <div class="accordion__edit">
                                            <div class="row">
                                                <ul class="list list--text">
                                                    <li class="item other_locations close_new" id="other_locations">
                                                    </li>
                                                    <li class="new_locations" id="new_locations" style="display: none;"></li>
                                                    <?php
                                                    $tier1 = unserialize(ROLES_TIER1);
                                                    ?>
                                                    <?php if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $tier1))) { ?>
                                                        <li class="item">
                                                            <label class="control control__radio">
                                                                <input type="radio" class="control__conditional" data-target="#condNewLocation" name="location" value="1">
                                                                <div class="control__indicator"></div>
                                                                <p class="no--margin textColor--darkest-gray">(+) Create New Location</p>
                                                            </label>
                                                        </li>
                                                    <?php } ?>
                                                    <li id="condNewLocation" class="item is--conditional starts--hidden">
                                                        <div class="row margin--xs no--margin-t no--margin-r no--margin-l">
                                                            <div class="input__group is--inline">
                                                                <input id="locationNickname" name="locationNickname"  class="input" type="text" placeholder="ACME Inc." required>
                                                                <label class="label" for="locationNickname">Nickname</label>
                                                            </div>
                                                        </div>
                                                        <div class="row margin--xs no--margin-b no--margin-r no--margin-l">
                                                            <div class="input__group is--inline">
                                                                <input id="locationAddress1" name="locationAddress1"  class="input" type="text" placeholder="7855 Winterfell Way" required>
                                                                <label class="label" for="locationAddress1">Address Line 1</label>
                                                            </div>
                                                        </div>
                                                        <div class="row margin--xs no--margin-b no--margin-r">
                                                            <div class="col col--1-of-2">
                                                                <div class="input__group is--inline">
                                                                    <input id="locationAddress2" name="locationAddress2" class="input" type="text" placeholder="Unit 3" required>
                                                                    <label class="label" for="locationAddress2">Unit/Suite/#</label>
                                                                </div>
                                                            </div>
                                                            <div class="col col--1-of-2">
                                                                <div class="input__group is--inline">
                                                                    <input id="locationZip" name="locationZip" class="input" type="text" placeholder="90210" required>
                                                                    <label class="label" for="locationZip">Zip</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row margin--xs no--margin-b no--margin-r">
                                                            <div class="select">
                                                                <select name="state" required id="state">
                                                                    <option value="default" disabled="" selected>Choose State</option>
                                                                    <option value="AL">Alabama</option>
                                                                    <option value="AK">Alaska</option>
                                                                    <option value="AZ">Arizona</option>
                                                                    <option value="AR">Arkansas</option>
                                                                    <option value="CA">California</option>
                                                                    <option value="CO">Colorado</option>
                                                                    <option value="CT">Connecticut</option>
                                                                    <option value="DE">Delaware</option>
                                                                    <option value="DC">District Of Columbia</option>
                                                                    <option value="FL">Florida</option>
                                                                    <option value="GA">Georgia</option>
                                                                    <option value="HI">Hawaii</option>
                                                                    <option value="ID">Idaho</option>
                                                                    <option value="IL">Illinois</option>
                                                                    <option value="IN">Indiana</option>
                                                                    <option value="IA">Iowa</option>
                                                                    <option value="KS">Kansas</option>
                                                                    <option value="KY">Kentucky</option>
                                                                    <option value="LA">Louisiana</option>
                                                                    <option value="ME">Maine</option>
                                                                    <option value="MD">Maryland</option>
                                                                    <option value="MA">Massachusetts</option>
                                                                    <option value="MI">Michigan</option>
                                                                    <option value="MN">Minnesota</option>
                                                                    <option value="MS">Mississippi</option>
                                                                    <option value="MO">Missouri</option>
                                                                    <option value="MT">Montana</option>
                                                                    <option value="NE">Nebraska</option>
                                                                    <option value="NV">Nevada</option>
                                                                    <option value="NH">New Hampshire</option>
                                                                    <option value="NJ">New Jersey</option>
                                                                    <option value="NM">New Mexico</option>
                                                                    <option value="NY">New York</option>
                                                                    <option value="NC">North Carolina</option>
                                                                    <option value="ND">North Dakota</option>
                                                                    <option value="OH">Ohio</option>
                                                                    <option value="OK">Oklahoma</option>
                                                                    <option value="OR">Oregon</option>
                                                                    <option value="PA">Pennsylvania</option>
                                                                    <option value="RI">Rhode Island</option>
                                                                    <option value="SC">South Carolina</option>
                                                                    <option value="SD">South Dakota</option>
                                                                    <option value="TN">Tennessee</option>
                                                                    <option value="TX">Texas</option>
                                                                    <option value="UT">Utah</option>
                                                                    <option value="VT">Vermont</option>
                                                                    <option value="VA">Virginia</option>
                                                                    <option value="WA">Washington</option>
                                                                    <option value="WV">West Virginia</option>
                                                                    <option value="WI">Wisconsin</option>
                                                                    <option value="WY">Wyoming</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="footer__group border--dashed">
                                                            <a class="btn btn--m btn--primary btn--block save--toggle add-reorderLocation">Add new Location</a>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /Location -->
                            <!-- Payment Method -->
                            <div class="accordion__group">
                                <div class="accordion__section">
                                    <div class="accordion__title wrapper">
                                        <div class="wrapper__inner">
                                            <h3>Payment Method</h3>
                                        </div>
                                        <div class="wrapper__inner align--right">
                                            <a class="link link--expand">Edit</a>
                                        </div>
                                    </div>
                                    <div class="accordion__content">
                                        <div class="accordion__preview payments">
                                        </div>
                                        <div class="accordion__edit">
                                            <div class="row">
                                                <ul class="list list--text">
                                                    <li class="item new_payments close_new" id="new_payments">
                                                    </li>
                                                    <li class="new_reoderpayments" id="new_reoderpayments" style="display: none;"></li>
                                                    <li class="item">
                                                        <label class="control control__radio">
                                                            <input type="radio" class="control__conditional" value="0" name="paymentMethod" data-target="#condNewPaymentMethod">
                                                            <div class="control__indicator"></div>
                                                            <p class="no--margin textColor--darkest-gray"><svg class="icon icon--cc icon--undefined"></svg> New Payment Method</p>
                                                        </label>
                                                    </li>
                                                    <li id="condNewPaymentMethod" class="item is--conditional starts--hidden">
                                                        <div class="wrapper__inner width--100">
                                                            <!-- Payment Method Form -->
                                                            <div class="box__group">
                                                                <div id="paymentMethods" class="method__forms is--cc">
                                                                    <div class="tab__group tab--block" data-target="#paymentMethods">
                                                                        <label class="tab" value="is--cc">
                                                                            <input type="radio" name="paymentType"  value="1"  checked="">
                                                                            <span>Credit/Debit Card</span>
                                                                        </label>
                                                                        <label class="tab" value="is--bank">
                                                                            <input type="radio" name="paymentType"  value="2">
                                                                            <span>Bank Account</span>
                                                                        </label>
                                                                    </div>
                                                                    <span class="payment-errors" style="color: red;"></span>
                                                                    <div id="methodCreditCard" class="form__group method__form">
                                                                        <!-- Credit/Debit Card -->
                                                                        <div class="row">
                                                                            <div class="input__group is--inline has--icon cc">
                                                                                <input id="paymentCardNum" name="paymentCardNum" class="input input--cc" type="text" required>
                                                                                <label class="label" for="paymentCardNum">Card Number</label>
                                                                                <svg class="icon icon--cc icon--undefined"></svg>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="input__group is--inline">
                                                                                <input id="paymentCardName" name="paymentCardName" class="input" type="text" required>
                                                                                <label class="label" for="paymentCardName">Name on Card</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col col--1-of-2">
                                                                                <div class="input__group is--inline">
                                                                                    <input id="paymentExpiry" name="paymentExpiry" class="input input--cc-exp" type="text" required>
                                                                                    <label class="label" for="paymentExpiry">Expiry(MM/YY)</label>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col col--1-of-2">
                                                                                <div class="input__group is--inline">
                                                                                    <input id="paymentSecurity" name="paymentSecurity" class="input" type="text" required>
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
                                                                                <input id="accountholderName" name="accountholderName" class="input" type="text" required>
                                                                                <label class="label" for="accountholderName">Account Holder Name</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="select">
                                                                                <select name="accountholderType"  id="accountholderType" class="sub_category" required>
                                                                                    <option value="individual">Individual</option>
                                                                                    <option value="company">Company</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="input__group is--inline">
                                                                                <input id="paymentAccountNum" name="paymentAccountNum" class="input" type="number" required>
                                                                                <label class="label" for="paymentAccountNum">Account Number</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="input__group is--inline">
                                                                                <input id="paymentRoutingNum" name="paymentRoutingNum" class="input" type="number" required>
                                                                                <label class="label" for="paymentRoutingNum">Routing Number</label>
                                                                            </div>
                                                                        </div>
                                                                        <!-- /Bank Account -->
                                                                    </div>
                                                                    <div class="footer__group border--dashed">
                                                                        <a class="btn btn--m btn--primary btn--block save--toggle add-reorderpayment">Save Payment Method</a>
                                                                    </div>
                                                                </div>
                                                                <!-- /Payment Method Form -->
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /Payment Method -->
                            <!-- Shipping Method -->
                            <div class="margin--s no--margin-t no--margin-lr border--1 border--dashed border--light border--b">
                                <h3 class="no--margin textColor--dark-gray">Shipping Method</h3>
                            </div>
                            <div class="row">
                                <div class="select">
                                    <input type="hidden" name="shiping_price" class="reorder_shipping_price" value="">
                                    <select name="shipping_id" aria-label="Select a Title" class="shipping " required>
                                        <!--                                              <option value="">----Select Sub Category-----</option>-->
                                    </select>
                               <!--  <span class="line--sub">Buy 10, Get 1</span> -->
                                </div>
                            </div>
                            <!-- /Shipping Method -->
                        </div>
                        <div class="wrapper margin--l no--margin-b no--margin-lr">
                            <div class="wrapper__inner">
                                <!--                       <a class="link fontSize--s fontWeight--2 modal--toggle default--action">Back</a>-->
                                <a class="link fontSize--s fontWeight--2 go--previous" data-next="#formReorder1">Back</a>
                            </div>
                            <div class="wrapper__inner align--right">
                                <button class="btn btn--m btn--primary save--toggle steptwo" data-target="#formReorder2" data-next="#formReorder3" type="button">Next</button>
                            </div>
                        </div>
                    </form>
                    <!-- /Step Two Checkout -->
                    <!-- Step Three Confirmation -->
                    <form id="formReorder3" class="form__group starts--hidden" action="" method="post">
                        <h3 class="fontSize--l textColor--darkest-gray">3. Confirmation</h3>
                        <div class="row">
                            <!-- Order Details -->
                            <div class="accordion__group no--margin">
                                <div class="accordion__section">
                                    <div class="accordion__title wrapper">
                                        <div class="wrapper__inner">
                                            <h3>Order Details</h3>
                                        </div>
                                    </div>
                                    <div class="accordion__content margin--m no--margin-lr">
                                        <div class="accordion__preview">
                                            <div class="wrapper">
                                                <div class="wrapper__inner valign--top width--50">
                                                    <div class="control__text text__group">
        <!--                                                <span id="old_location">
                                                        <span class="line--main location_name" id="location_name"></span>
                                                        <span class="line--sub fontSize--s location_data" id="location_data"></span>
                                                        </span>-->
                                                        <span class="line--main chanedlocation_name" ></span>
                                                        <span class="line--sub fontSize--s changedlocation_data"></span>
                                                    </div>
                                                </div>
                                                <div class="wrapper__inner valign--top width--50">
                                                    <div class="control__text text__group">
        <!--                                                <span id="old_shipping">
                                                        <span class="line--main shiping_date"></span>(<span class="shipping_prices"></span>)
                                                        <span class="line--sub fontSize--s shipping_type"></span>
                                                        </span>-->
                                                        <span class="changed_shipping" id="changed_shipping">
                                                            <span class="line--main changed_shiping_date"></span>
            <!--                                                (<span class="changed_shipping_prices"></span>)-->
                                                            <span class="line--sub fontSize--s changedshipping_type"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row margin--m no--margin-lr no--margin-b">
                                                <p class="no--margin textColor--darkest-gray changed_payments" id="changed_payments"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="padding--m no--pad-lr border border--1 border--dashed border--lightest border--t">
                                        <div id="single_value"></div>
                                        <input type="hidden" name="tax" class="taxes tax_value" value=""> 
                                        <input type="hidden" name="order_total" class="final_reorder" value=""> 
                                        <input type="hidden" name="payment_token" class="payment_token" value="">
                                        <table class="table table--horizontal table--align-lr">
                                            <tbody class="order_conformation">
    <!-- <tr>
        <td><span class="fontWeight--2">Total</span></td>
        <td><span class="fontSize--m fontWeight--2">$770.00</span></td>
    </tr> -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- /Order Details -->
                            <div class="wrapper">
                                <div class="wrapper__inner">
                                    <!--                           <a class="link fontSize--s fontWeight--2 modal--toggle default--action" data-target="formReorder2">Back</a>-->
                                    <a class="link fontSize--s fontWeight--2 go--previous" data-next="#formReorder2">Back</a>
                                </div>
                                <div class="wrapper__inner align--right">
                                    <button class="btn btn--m btn--primary save--toggle form--submit reorders" type="button">Place Order</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- /Step Three Confirmation -->
                </div>
                <!-- /Reorder Process -->
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Reorder Modal -->
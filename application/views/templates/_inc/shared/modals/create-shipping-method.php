<!-- Create Shipping Method Modal -->
<div id="createShippingMethodModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Create shipping method</h2>
            <p class="margin--l no--margin-r no--margin-t no--margin-l">Add the following details below and save your changes.</p>
        </div>
        <div class="modal__body center center--h align--left cf">
            <form id="createShippingMethod" class="form__group" action="<?php echo base_url(); ?>add-shipping-vendorDashboard" method="post">
                <div class="modal__content">
                    <div class="row">
                        <div class="input__group is--inline">
                            <div class="select">
                                <select required name="createShippingCarrier">
                                    <option disabled selected>Select Carrier</option>
                                    <option value="1">US Postal Service</option>
                                    <option value="2">UPS</option>
                                    <option value="3">FedEx</option>
                                    <option value="4">DHL</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input__group is--inline">
                            <div class="select">
                                <select required name="createShippingType">
                                    <option disabled selected>Select Shipping Method</option>
                                    <option value="1">Standard Ground</option>
                                    <option value="2">Priority Mail</option>
                                    <option value="3">Priority Mail Express</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input__group is--inline">
                            <div class="select">
                                <select required name="createShippingSpeed">
                                    <option disabled selected>Select Timeframe</option>
                                    <option value="1">Same Day</option>
                                    <option value="2">Next Business Day</option>
                                    <option value="3">2 Business Days</option>
                                    <option value="4">3 Business Days</option>
                                    <option value="5">1-5 Business Days</option>
                                    <option value="6">7-10 Business Days</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h5 class="title">Details</h5>
                    <div class="row form__row">
                        <div class="col col--2-of-6 col--am">
                            <span class="fontSize--m fontWeight--2">Max Weight</span>
                        </div>
                        <div class="col col--4-of-6 col--am">
                            <div class="input__group is--inline">
                                <input id="createMaxWeight" name="createMaxWeight" type="number" class="input input--m width-100" min="1" required>
                                <label class="label" for="createMaxWeight">lbs.</label>
                            </div>
                        </div>
                    </div>
                    <div class="row form__row">
                        <div class="col col--2-of-6 col--am">
                            <span class="fontSize--m fontWeight--2">Max Dimensions </span>
                        </div>
                        <div class="col col--4-of-6 col--am">
                            <div id="createMaxDimensions"  class="input__group is--inline">
                                <input type="text" name="createMaxDimensions" class="input input--m width-100" placeholder="L×W×H" required>
                                <label class="label" for="createMaxDimensions">in.</label>
                            </div>
                        </div>
                    </div>
                    <div class="row form__row">
                        <div class="col col--2-of-6 col--am">
                            <span class="fontSize--m fontWeight--2">Cost </span>
                        </div>
                        <div class="col col--4-of-6 col--am">
                            <div class="input__group is--inline">
                                <input id="createShippingCost" name="createShippingCost" type="text" class="input input--m input--currency-zero width-100" min="$0.01"  data-prefix="$" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="footer__group border--dashed">
                    <!--                    data-target="#createShippingMethod" -->
                    <button class="btn btn--m btn--primary btn--block save--toggle form--submit">Save</button>
                </div>
            </form>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Create Shipping Method Modal -->

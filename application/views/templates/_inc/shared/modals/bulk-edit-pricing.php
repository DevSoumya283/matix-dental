<!-- Bulk Edit Pricing Modal -->
<div id="bulkEditPricingModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Bulk edit pricing</h2>
            <p class="margin--l no--margin-r no--margin-t no--margin-l">Add the following details below and save your changes.</p>
        </div>
        <div class="modal__body center center--h align--left cf">
            <form id="shippingMethod" class="form__group" action="">
                <div class="modal__content">

                    <!-- Discount -->
                    <div class="margin--l no--margin-lr no--margin-t">
                        <p class="fontSize--m fontWeight--2 textColor--darkest-gray margin--xs no--margin-lr no--margin-t">Discount</p>
                        <div class="row form__row">
                            <div class="col col--3-of-12 col--am">
                                <input type="number" class="input input--m width-100" min="1" value="1">
                            </div>
                            <div class="select col col--2-of-12 col--am">
                                <select required>
                                    <option disabled value="default">Select a Denomination</option>
                                    <option selected value="1">%</option>
                                    <option value="1">$</option>
                                </select>
                            </div>
                            <div class="col col--1-of-12 col--am align--center">
                                <span class="fontSize--s fontWeight--2 textColor--darkest-gray">of</span>
                            </div>
                            <div class="select col col--6-of-12 col--am">
                                <select required>
                                    <option disabled selected value="default">Select a Parameter</option>
                                    <option value="1">Final Price</option>
                                    <option value="1">Shipping</option>
                                    <option value="2">Tax</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Time Range -->
                    <div class="margin--l no--margin-lr no--margin-t">
                        <p class="fontSize--m fontWeight--2 textColor--darkest-gray margin--xs no--margin-lr no--margin-t">Time Range</p>
                        <div class="row">
                            <div class="input__group input__group--date-range is--inline input-daterange" style="width:330px;">
                                <svg class="icon icon--calendar"><use xlink:href="#icon-calendar"></use></svg>
                                <input type="text" class="input input--date" placeholder="MM/DD/YYYY" name="start">
                                <input type="text" class="input input--date" placeholder="MM/DD/YYYY" name="end">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="footer__group border--dashed">
                    <button class="btn btn--m btn--primary btn--block no--refresh" data-target="#shippingMethod" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Bulk Edit Pricing Modal -->
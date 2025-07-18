<!-- Edit Customer Modal -->
<div id="editLicenseModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2 id="license-modal-title">Edit License</h2>
        </div>
        <div class="modal__body center center--h align--left cf">
            <form id="editLicenseForm" class="form__group modal__content" action="/add-license" method="post">
                <input type="hidden" name="user_id" value="<?php echo $customer_report->id; ?>">
                <input type="hidden" name="user_id" value="<?php echo $customer_report->id; ?>">
                <input type="hidden" id="license_id" name="license_id" value="">
                <div class="row">
                    <div class="input__group is--inline">
                        <input id="accountLicense" name="accountLicense" class="input" type="text" maxlength="14" required>
                        <label class="label" for="accountLicense">License #</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input__group is--inline">
                        <input id="accountDEA" name="accountDEA" class="input" maxlength="14" type="text">
                        <label class="label" for="accountDEA">DEA #</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input__group is--inline">
                        <input id="licenseExpiry" name="licenseExpiry" class="input input--license--date" placeholder="MM/DD/YYYY" type="text" required>
                        <label class="label" for="licenseExpiry">Expiration Date (MM/DD/YYYY)</label>
                    </div>
                </div>
                    <div class="row">
                        <div class="select">
                            <select name="state" id="licenseState" required>
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
                <hr>
                <div class="footer__group border--dashed">
                    <button class="btn btn--m btn--primary btn--block save--toggle form--submit page--reload"  data-target="#formLicense">Save License</button>
                </div>
            </form>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Edit Customer Modall -->

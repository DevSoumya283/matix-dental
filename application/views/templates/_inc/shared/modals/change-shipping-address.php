<!-- Change Shipping Address Modal -->
<div id="changeShippingAddressModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header">
            <h2>Change Shipping Location</h2>
            <hr class="margin--xs no--margin-lr no--margin-t border--lightest">
        </div>
        <div class="modal__body cf">
            <div class="modal__content">
                <form id="formNewLocation" class="form__group" action="<?php echo base_url("addshippingLocation"); ?>" method="post">
                    <input type="hidden" name="recurring_id" class="recurring_id" value="">
                    <div class="row no--margin-l">
                        <ul class="list list--text">
                            <li class="item">
                                <label class="control control__radio">
                                    <input type="radio" name="location" value="0" checked>
                                    <div class="control__indicator"></div>
                                    <p class="no--margin textColor--darkest-gray"><span class="fontWeight--2 location_name"></span></p>
                                </label>
                            </li>
                            <li class="item">
                                <label class="control control__radio">
                                    <input type="radio" class="control__conditional" data-target="#condNewLocation" name="location" value="1">
                                    <div class="control__indicator"></div>
                                    <p class="no--margin textColor--darkest-gray">(+) Create New Location</p>
                                </label>
                            </li>
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
                                </div><br>
                                <div class="row">
                                    <div class="select">
                                        <select name="state" required>
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
                            </li>
                        </ul>
                    </div>

                    <div class="footer__group border--dashed">
                        <button class="btn btn--m btn--primary btn--block save--toggle form--submit" data-target="#formNewLocation">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Change Shipping Address Modal-->

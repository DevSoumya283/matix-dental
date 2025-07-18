<!-- Add New Location Modal -->
<div id="addNewLocationOrganizationModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Add New Location</h2>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">
                <form id="newLocation" class="form__group" action="<?php echo base_url(); ?>organization-newlocation-add" method="post">
                    <div class="row">
                        <div class="input__group is--inline">
                            <input type="hidden" name="organization_id" value="<?php echo $organization_id; ?>">
                            <input id="nickName" name="nickName" class="input" type="text" placeholder="eg. Downtown Office" pattern=".*\S.*" required>
                            <label class="label" for="nickName">Nickname*</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input__group is--inline">
                            <input id="address" name="address1" class="input" type="text" required>
                            <label class="label" for="address1">Address Line 1*</label>
                        </div>
                    </div>
                    <div class="row wrapper">
                        <div class="input__group is--inline wrapper__inner padding--xs no--pad-l no--pad-t no--pad-b">
                            <input id="unit" name="address2" class="input" type="text">
                            <label class="label" for="address2">Unit/Suite/#</label>
                        </div>
                        <div class="input__group is--inline">
                            <input id="city" name="city" class="input" type="text" required>
                            <label class="label" for="city">City</label>
                        </div>
                    </div>
                    <div class="row wrapper">
                        <div class="input__group is--inline wrapper__inner padding--xs no--pad-l no--pad-t no--pad-b">
                            <div class="select">
                                <select name="state" required style="width: 261px;">
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
                        <div class="input__group is--inline">
                            <input id="zip" name="zip" class="input" type="text" required>
                            <label class="label" for="zip">Zipcode</label>
                        </div>
                    </div>
                    <div class="row footer__group border--dashed">
                        <button class="btn btn--m btn--primary btn--block save--toggle form--submit" data-target="#newLocation" type="submit">Add new location</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Add New Location Modal -->

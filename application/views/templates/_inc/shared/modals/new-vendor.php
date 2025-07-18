<!-- New Vendor Modal -->
<div id="addNewVendorModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Create a New Vendor</h2>
        </div>
        <div class="modal__body center center--h align--left cf">
            <form id="newVendorForm" class="form__group modal__content" action="<?php echo base_url(); ?>SPdashboard-vendor-registration" method="post">
                <h5 class="title">Vendor Information</h5>
                <div class="row">
                    <div class="input__group is--inline">
                        <input id="vendorName" name="vendorName" class="input" type="text" placeholder="eg. Star Dental Supply, Inc." required>
                        <label class="label" for="vendorName">Vendor Name</label>
                    </div>
                </div>
                <div class="row">
                    <div class="select">
                        <select name="vendor_type" required>
                            <option disabled selected value="default">Select Vendor Type</option>
                            <option value="0">Independent</option>
                            <option value="1">Matix</option>
                        </select>
                    </div>
                </div>
                <br>
                <h5 class="title">Shipping Information</h5>
                <div class="row">
                    <div class="input__group is--inline">
                        <input id="vendorAddress1" name="vendorAddress1" class="input" type="text" required>
                        <label class="label" for="vendorAddress1">Address Line 1</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col col--1-of-2">
                        <div class="input__group is--inline">
                            <input id="vendorAddress2" name="vendorAddress2" class="input" type="text">
                            <label class="label" for="vendorAddress2">Unit/Suite/#</label>
                        </div>
                    </div>
                    <div class="col col--1-of-2">
                        <div class="input__group is--inline">
                            <input id="companyZip" name="companyZip" class="input input--zip" type="text" required>
                            <label class="label" for="companyZip">Zip</label>
                        </div>
                    </div>
                </div>
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
                <br>
                <h5 class="title">Administrator User</h5>
                <div class="row">
                    <div class="input__group is--inline">
                        <input id="accountName" name="accountName" class="input" type="text" placeholder="Kevin McCallister" required>
                        <label class="label" for="accountName">Full Name</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input__group is--inline">
                        <input id="accountEmail" name="accountEmail" class="input" type="email" placeholder="email@example.com" pattern=".*\S.*" required>
                        <label class="label" for="accountEmail">Email Address</label>
                    </div>
                </div>
                <div class="footer__group border--dashed">
                    <button class="btn btn--m btn--primary btn--block save--toggle form--submit page--reload" data-target="#newVendorForm" type="submit">Create Vendor</button>
                </div>
            </form>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /New Vendor Modal -->

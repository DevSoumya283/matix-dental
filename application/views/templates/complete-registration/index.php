<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title></title>
        <meta name="description" content="">
        <!-- Icons -->
        <?php include(INCLUDE_PATH . '/_inc/icons.php'); ?>
        <!-- build:css css/main.min.css -->
        <link href="<?php echo base_url(); ?>assets/css/main.css" rel="stylesheet" type="text/css">
        <!-- endbuild -->
        <!-- Libraries -->
        <link href="<?php echo base_url(); ?>lib/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url(); ?>lib/animate-css/animate.css" rel="stylesheet" type="text/css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
    </head>
    <body class="login bg--lightest-gray">

        <header class="bg--white padding--m border border--1 border--light border--solid border--b shadow--m">
            <div class="wrapper">
                <div class="wrapper__inner">
                    <a href="<?php echo base_url('browse'); ?>">
                        <img width="172" src="<?php echo base_url(); ?>assets/img/logo-matix.svg" alt="" style="vertical-align:middle;"/>
                    </a>
                </div>
                <div class="wrapper__inner align--right" style="width:50%;">
                    <a href="<?php echo base_url('view-dashboard'); ?>"> <button class="btn btn--secondary btn--s"> Cancel</button></a>
                </div>
            </div>
        </header>

        <div class="row">
            <span class="success">
                <?php if ($this->session->flashdata('success') != "") { ?>
                <div class="banner is--pos">
                    <span class="banner__text">
                        <?php echo $this->session->flashdata('success') ?>
                    </span>
                    <a class="link link--icon dismiss--banner"><svg class="icon icon--x"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-x"></use></svg></a>
                </div>
                <?php } ?>
            </span>
            <span class="nolicence">
                <?php if ($this->session->flashdata('error') != "") { ?>
                <div class="banner is--neg">
                    <span class="banner__text">
                        <?php echo $this->session->flashdata('error') ?>
                    </span>
                    <a class="link link--icon dismiss--banner"><svg class="icon icon--x"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-x"></use></svg></a>
                </div>
            </span>
        </div>

        <section class="content__wrapper">
            <?php } ?>
        </span>
            <?php $dataProcessValue = 1; ?>
            <div class="content__main">
                <div class="content">
                    <div class="panel panel--m panel--centered">
                        <div class="form form--multistep">
                            <?php
                            if ($steptwo == 2) {
                                $dataProcessValue = 2;
                            } elseif ($stepthree == 3) {
                                $dataProcessValue = 3;
                            }
                            ?>
                            <div id="progressRegistration" class="progress" data-progress="<?php echo $dataProcessValue ?>" data-steps="4">
                                <span class="progress__title">Registration Progress</span>
                                <span class="progress__percentage"></span>
                                <div class="progress__bar">
                                    <div class="progress__inner"></div>
                                </div>
                            </div>
                            <hr>
                            <!-- Step One -->

                            <form id="formAccount1" class="form__group <?php echo ($stepone == 1) ? "starts--hidden" : "" ?>" method="POST" action="<?php echo base_url(); ?>accountInformation-userDetails">
                                <h3 class="textColor--dark-gray">Your Information</h3>
                                <div class="row">
                                    <div class="input__group is--inline">
                                        <input id="accountName" name="accountName" class="input <?php if(!empty($userDetails->first_name)){ echo 'not--empty'; }?>" value="<?php echo $userDetails->first_name; ?>" type="text" placeholder="Kevin McCallister"  required >
                                        <label class="label" for="accountName">Full Name</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input__group is--inline">
                                        <input id="accountTitle" name="accountTitle" class="input <?php if(!empty($userDetails->salutation)){ echo 'not--empty'; }?>"  value="<?php echo $userDetails->salutation; ?>" type="text" placeholder="eg. DDS">
                                        <label class="label" for="accountTitle">Title (optional)</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input__group is--inline">
                                        <input id="accountPhone" name="accountPhone" class="input  <?php if(!empty($userDetails->phone1)){ echo 'not--empty'; }?> input--phone"  value="<?php echo $userDetails->phone1; ?>" type="text" required>
                                        <label class="label" for="accountPhone">Phone Number</label>
                                    </div>
                                </div>
                                <hr>
                                <h3 class="textColor--dark-gray">License Info <span class="fontSize--s fontWeight--1">(Required to purchase restricted items.)</span></h3>
                                <div class="row field__group">
                                    <label class="control control__checkbox">
                                        <input class="control__conditional" type="checkbox" name="license_select"  data-target="#condLicense" <?php if(!empty($userDetails->license)){ echo 'checked'; } ?>>
                                        <div class="control__indicator"></div>
                                        I want to add a new license
                                    </label>
                                    <div id="condLicense" class="is--conditional <?php if(empty($userDetails->license)){ ?>starts--hidden<?php } ?>">
                                        <?php if(!empty($userDetails->license)){ echo '<input type="hidden" name="license_id" value="' . $userDetails->license->id . '">'; } ?>
                                        <div class="row">
                                            <div class="input__group is--inline">
                                                <input id="accountLicense" name="accountLicense" maxlength="14" class="input <?php if(!empty($userDetails->license->license_no)){ echo 'not--empty'; } ?>" type="text" value="<?php echo $userDetails->license->license_no; ?>" required>
                                                <label class="label" for="accountLicense">License #</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input__group is--inline">
                                                <input id="accountDEA" name="accountDEA" maxlength="14" class="input <?php if(!empty($userDetails->license->dea_no)){ echo 'not--empty'; } ?>" value="<?php echo $userDetails->license->dea_no; ?>" type="text">
                                                <label class="label" for="accountDEA">DEA #</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input__group is--inline">
                                                <input id="licenseExpiry" name="licenseExpiry" class="input input--date <?php if(!empty($userDetails->license->expire_date)){ echo 'not--empty'; } ?>" placeholder="MM/DD/YYYY" value="<?php echo $userDetails->license->expire_date; ?>" type="text" required>
                                                <label class="label" for="accountDEA">Expiry(MM/DD/YYYY)</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="select">
                                                <select aria-label="Select a Title" name="LicensedState" id="licenseState">
                                                    <?php include(INCLUDE_PATH . '/_inc/shared/states.php'); ?>
                                                </select>
                                                <?php if(!empty($userDetails->license->state)){ ?>
                                                <script>
                                                    $( document ).ready(function() {
                                                        $('#licenseState').val('<?php echo $userDetails->license->state; ?>')
                                                    });
                                                </script>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <h3 class="textColor--dark-gray">Organization Info</h3>
                                <div class="box__group">
                                    <div class="tab__group tab--block">
                                        <label class="tab toggle--conditional" data-target=".org__type">
                                            <input type="radio" name="orgType" value="1" checked>
                                            <span>Company</span>
                                        </label>
                                        <label class="tab toggle--conditional" data-target=".org__type">
                                            <input type="radio" name="orgType" value="2">
                                            <span>School</span>
                                        </label>
                                    </div>
                                    <input type="hidden" name="orgId" value="<?php echo $userDetails->organization->id; ?>">
                                    <div id="orgCompany" class="tab__panel org__type is--visible">
                                        <div class="row">
                                            <div class="input__group is--inline">
                                                <input id="companyName" name="companyName" class="input <?php if(!empty($userDetails->organization->organization_name)){ echo 'not--empty'; }?>" value="<?php echo $userDetails->organization->organization_name; ?>" type="text" placeholder="ACME Inc." required>
                                                <label class="label" for="companyName">Company Name</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input__group is--inline">
                                                <input id="companyTaxID" name="companyTaxID" class="input input--tax-id <?php if(!empty($userDetails->organization->tax_id)){ echo 'not--empty'; }?>" value="<?php echo $userDetails->organization->tax_id; ?>" type="text">
                                                <label class="label" for="companyTaxID">Tax ID (optional)</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="orgSchool" class="tab__panel org__type is--hidden">
                                        <div class="row">
                                            <div class="input__group is--inline">
                                                <input id="schoolName" name="schoolName" class="input <?php if(!empty($userDetails->organization->organization_name)){ echo 'not--empty'; }?>" type="text" value="<?php echo $userDetails->organization->organization_name; ?>" placeholder="ACME University" required>
                                                <label class="label" for="schoolName">School Name</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input__group is--inline">
                                                <input id="schoolTaxID" name="schoolTaxID" class="input <?php if(!empty($userDetails->organization->tax_id)){ echo 'not--empty'; }?>" type="text" value="<?php echo $userDetails->organization->tax_id; ?>">
                                                <label class="label" for="schoolTaxID">Tax ID (optional)</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="input__group is--inline">
                                        <input type="checkbox" id="agreeTOU" name="" required style="border-radius: 3px;height: 16px;width: 16px;border: 1px solid #C1C9D7;box-sizing: border-box; display:inline;">
                                        <div class="control__indicator" style="display:inline;"></div>
                                        I have read and agree to the
                                        <a class="link" href="<?php echo base_url(); ?>terms-conditions" target="_blank">Terms of Use</a>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <button class="btn btn--m btn--primary btn--block save--toggle form--submit no--refresh go--next registration_process" data-target="#formAccount1" data-next="#formAccount2" type="submit">Save Account Info</button>
                                </div>
                            </form>
                            <!-- /Step One -->
                            <!-- Step Two -->
                            <form id="formAccount2" class="form__group <?php echo ($steptwo == 2) ? "" : "starts--hidden" ?>" action="<?php base_url(); ?>locationInfo-userDetails">
                                <h3 class="textColor--dark-gray no--margin-b">Set up your first location</h3>
                                <p>This is where your orders will be shipped. You'll be able to add additional locations and assign users to them later.</p>
                                <hr>
                                <div class="row">
                                    <?php if(!empty($userDetails->location)){ ?>
                                        <imput type="hidden" name="location_id" value="<?php echo $userDetails->location->id;?>">
                                    <?php } ?>
                                    <input id="organization_id" name="organization_id" class="input" type="hidden" required>
                                    <div class="input__group is--inline">
                                        <input id="locationName" name="locationName" class="input <?php if(!empty($userDetails->location->nickname)){ echo 'not--empty'; }?> " value="<?php echo $userDetails->location->nickname; ?>" type="text" required>
                                        <label class="label" for="loactionName">Nickname</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input__group is--inline">
                                        <input id="companyAddress1" name="companyAddress1" class="input <?php if(!empty($userDetails->location->address1)){ echo 'not--empty'; }?>" value="<?php echo $userDetails->location->address1; ?>" type="text" placeholder="Address Line 1" required>
                                        <label class="label" for="companyAddress1">Address Line 1</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col col--1-of-2">
                                        <div class="input__group is--inline">
                                            <input id="companyAddress2" name="companyAddress2" class="input <?php if(!empty($userDetails->location->address2)){ echo 'not--empty'; }?>" value="<?php echo $userDetails->location->address2; ?>" type="text" placeholder="Unit 3">
                                            <label class="label" for="companyAddress2">Unit/Suite/#</label>
                                        </div>
                                    </div>
                                    <div class="col col--1-of-2">
                                        <div class="input__group is--inline">
                                            <input id="companyCity" name="companyCity" class="input <?php if(!empty($userDetails->location->city)){ echo 'not--empty'; }?>" value="<?php echo $userDetails->location->city; ?>" type="text" placeholder="Los Angeles">
                                            <label class="label" for="companyCity">City</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col col--1-of-2">
                                        <div class="select">
                                            <select name="state" id="locationState" required>
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
                                    <div class="col col--1-of-2">
                                        <div class="input__group is--inline">
                                            <input id="companyZip" name="companyZip" class="input input--zip <?php if(!empty($userDetails->location->zip)){ echo 'not--empty'; }?>" value="<?php echo $userDetails->location->zip; ?>" type="text" placeholder="90210" required>
                                            <label class="label" for="companyZip">Zip</label>
                                        </div>
                                    </div>

                                                <?php if(!empty($userDetails->location->state)){ ?>
                                                <script>
                                                    $( document ).ready(function() {
                                                        $('#locationState').val('<?php echo $userDetails->location->state; ?>')
                                                    });
                                                </script>
                                                <?php } ?>
                                </div>
                                <hr>
                                <div class="row">
                                    <button class="btn btn--m btn--primary btn--block save--toggle form--submit go--next no--refresh registration_process" data-target="#formAccount2" data-next="#formAccount3" type="submit">Set Up Location</button>
                                </div>
                            </form>
                            <!-- /Step Two -->
                            <!-- Step Three -->
                            <div id="formAccount3" class="form__group <?php echo ($stepthree == 3) ? "" : "starts--hidden" ?>" action="">
                                <h3 class="textColor--dark-gray no--margin-b">Add a payment method</h3>
                                <p>Check out faster by saving a payment method to your account.</p>
                                <hr>
                                <!-- Payment Method Form -->
                                <div class="box__group">
                                    <div id="paymentMethods" class="method__forms is--cc">
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
                                                    <div class="input__group is--inline has--icon cc">
                                                        <input id="paymentCardNum" name="paymentCardNum" class="input input--cc" type="text" required>
                                                        <label class="label" for="paymentCardNum">Card Number</label>
                                                        <svg class="icon icon--cc icon--undefined"></svg>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="input__group is--inline">
                                                        <input id="paymentCardName" name="paymentCardName" class="input <?php if(!empty($userDetails->first_name)){ echo 'not--empty'; }?>" type="text" required>
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
                                                            <input id="paymentSecurity" name="paymentSecurity" class="input <?php if(!empty($userDetails->first_name)){ echo 'not--empty'; }?>" type="text" required>
                                                            <label class="label" for="paymentSecurity">Security Code</label>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <!-- /Credit/Debit Card -->
                                            <!-- Bank Account -->
                                            <div id="methodBank" class="form__group method__form">
                                                <div class="row">
                                                    <div class="input__group is--inline">
                                                        <input id="accountholderName" name="accountholderName" class="input <?php if(!empty($userDetails->first_name)){ echo 'not--empty'; }?>" type="text" required>
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
                                                        <input id="paymentAccountNum" name="paymentAccountNum" class="input <?php if(!empty($userDetails->first_name)){ echo 'not--empty'; }?>" type="number" required>
                                                        <label class="label" for="paymentAccountNum">Account Number</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="input__group is--inline">
                                                        <input id="paymentRoutingNum" name="paymentRoutingNum" class="input <?php if(!empty($userDetails->first_name)){ echo 'not--empty'; }?>" type="number" required>
                                                        <label class="label" for="paymentRoutingNum">Routing Number</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <button class="btn btn--m btn--primary btn--block save--toggle form--submit go--next no--refresh registration_process add-payment" id= "newword" data-target="#formAccount4"  type="submit">Save Payment Method</button>
                                            </div>
                                        </form>
                                        <!-- /Bank Account -->
                                    </div>
                                </div>
                                <!-- /Payment Method Form -->
                                <div class="align--center">
                                    <a class="link fontWeight--2 fontSize--s go--next skip--step" data-target="#formAccount4" onclick="setComplete();" data-next="#formAccount4">No thanks, I'll do this later</a>
                                </div>
                            </div>
                            <!-- /Step Three -->
                            <!-- Step Four (Success) -->
                            <div id="formAccount4" class="form__group starts--hidden align--center">
                                <h2 class="textColor--darkest-gray no--margin-b">You're all set!</h2>
<!--                                <p>Lorem ipsum dolor sit amet.</p>-->
                                <hr>
                                <div class="row">
                                    <a href="<?php echo base_url(); ?>user-browse-page" class="btn btn--m btn--primary btn--block">Start Shopping</a>
                                </div>
                            </div>
                            <!-- /Step Four (Success) -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <script>
            function setComplete(){
                console.log('completing set up')
                $.ajax({
                    url: '/accountRegister-setComplete',
                    method: 'GET'
                });
            }
        </script>
        <!-- <footer>&copy; Copyright 2017, Dentomatix, LLC</footer> -->
        <!--payment-->
        <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
        <script src="https://cdn.plaid.com/link/stable/link-initialize.js"></script>
        <!--payment-->
        <!-- Scripts & Libraries -->
        <script src="<?php echo base_url(); ?>assets/js/jquery.detect-card.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/jquery.maskedinput.min.js"></script>
        <script src="<?php echo base_url(); ?>lib/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
        <script src="<?php echo base_url(); ?>lib/jquery-validate/jquery.validate.min.js"></script>
        <script type="text/javascript">
            var base_url = "<?php echo base_url(); ?>";
            var image_url = "<?php echo image_url(); ?>";
            Stripe.setPublishableKey('<?php echo $this->config->item('stripe')['pk_'.$this->config->item('stripe')['mode']];?>');
        </script>
        <!-- build:js js/main.min.js -->
        <script src="<?php echo base_url(); ?>assets/js/main.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/awesome.min.js"></script>
        <!-- endbuild -->
    </body>
</html>
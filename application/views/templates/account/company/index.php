<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>
    <!-- Breadcrumbs Bar -->
    <div class="bar padding--xs bg--lightest-gray">
        <div class="wrapper wrapper--fixed">
            <ul class="list list--inline list--breadcrumbs">
                <li class="item">
                    <a class="link" href="<?php echo base_url('dashboard'); ?>">Account</a>
                </li>
                <li class="item is--active">
                    Company Profile
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breadcrumbs Bar -->
    <!-- Main Content -->
    <section class="content__wrapper wrapper--fixed has--sidebar-r sidebar--no-fill">
        <div class="content__main">
            <div class="row row--full-height">
                <div class="content col-md-8 col-xs-12">
                    <?php
                    $tier1 = unserialize(ROLES_TIER1);
                    if ($organ_id != null) {
                        ?>
                        <!-- Company Name -->
                        <div class="accordion__group">
                            <div class="accordion__section">
                                <div class="accordion__title wrapper">
                                    <div class="wrapper__inner">
                                        <h3>Company Name</h3>
                                    </div>
                                    <?php if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $tier1))) { ?>
                                        <div class="wrapper__inner align--right">
                                            <a class="link link--expand">Edit</a>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="accordion__content">
                                    <div class="accordion__preview">
                                        <?php echo $company_detail->organization_name; ?>
                                    </div>
                                    <div class="accordion__edit">
                                        <form id="companyNameForm" class="form__group" action="<?php echo base_url("update-companyName"); ?>" method="post">
                                            <input type="hidden" name="company_id" class="company_id" value="<?php echo $company_detail->id; ?>">
                                            <div class="row">
                                                <div class="input__group is--inline col-md-12">
                                                    <input id="companyName" name="companyName" class="input not--empty" type="text" value="<?php echo $company_detail->organization_name; ?>" required>
                                                    <label class="label pl-4" for="companyName">Company Name</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <button class="btn btn--primary btn--m btn--block form--submit save--toggle page--reload" data-target="#companyNameForm">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div><?php } else { ?>
                        <div class="accordion__group">
                            <div class="accordion__section">
                                <div class="accordion__title wrapper">
                                    <div class="wrapper__inner">
                                        <h3>Company Name</h3>
                                    </div>
                                    <?php if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $tier1))) { ?>
                                        <div class="wrapper__inner align--right">
                                            <a class="link link--expand">Edit</a>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="accordion__content">
                                    <div class="accordion__preview">
                                    </div>
                                    <div class="accordion__edit">
                                        <form id="companyNameForm" class="form__group" action="<?php echo base_url("UserDashboard/companyName"); ?>" method="post">
                                            <input type="hidden" name="company_id" class="company_id" value="">
                                            <div class="row">
                                                <div class="input__group is--inline col-md-12">
                                                    <input id="companyName" name="companyName" class="input not--empty" type="text" value="" required>
                                                    <label class="label" for="companyName">Company Name</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <button class="btn btn--primary btn--m btn--block form--submit save--toggle page--reload" data-target="#companyNameForm">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <!-- /Company Name -->
                    <!-- Address -->
                    <?php if ($company_info != null) { ?>
                        <div class="accordion__group">
                            <div class="accordion__section">
                                <div class="accordion__title wrapper">
                                    <div class="wrapper__inner">
                                        <h3>Address</h3>
                                    </div>
                                    <?php if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $tier1))) { ?>
                                        <div class="wrapper__inner align--right">
                                            <a class="link link--expand">Edit</a>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="accordion__content">
                                    <div class="accordion__preview">
                                        <?php
                                        if ($company_info->address1 != null) {
                                            echo $company_info->address1 . ", ";
                                        }
                                        if ($company_info->address2 != null) {
                                            echo $company_info->address2 . ", ";
                                        }
                                        if ($company_info->city != null) {
                                            echo $company_info->city . ", ";
                                        }
                                        if ($company_info->state != null) {
                                            echo $company_info->state . " ";
                                        }
                                        if ($company_info->zip != null) {
                                            echo $company_info->zip;
                                        }
                                        ?>
                                    </div>
                                    <div class="accordion__edit">
                                        <form id="companyAddressForm" class="form__group" action="<?php echo base_url("update-companyAddress"); ?>" method="post">
                                            <input type="hidden" name="company_id" class="company_id" value="<?php echo $company_info->id; ?>">
                                            <div class="row">
                                                <div class="input__group is--inline col-md-12">
                                                    <input id="companyAddress1" name="companyAddress1" class="input not--empty" type="text" value="<?php echo $company_info->address1; ?>" required>
                                                    <label class="label" for="companyAddress1">Address Line 1</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input__group is--inline col-md-12">
                                                    <input id="companyAddress2" name="companyAddress2" class="input" type="text" value="<?php echo $company_info->address2; ?>">
                                                    <label class="label pl-4" for="companyAddress2">Unit/Suite/#</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col col--1-of-2">
                                                    <div class="input__group is--inline col-md-12">
                                                        <input id="companyCity" name="companyCity" class="input not--empty" type="text" placeholder="Los Angeles" value="<?php echo $company_info->city; ?>" required>
                                                        <label class="label pl-4" for="companyCity">City</label>
                                                    </div>
                                                </div>
                                                <div class="col col--1-of-2">
                                                    <div class="input__group is--inline col-md-12">
                                                        <input id="companyZip" name="companyZip" class="input not--empty" type="text" placeholder="90210" value="<?php echo $company_info->zip; ?>" required>
                                                        <label class="label" for="companyZip">Zip</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input__group is--inline col-md-12">
                                                    <div class="select">
                                                        <select name="state" required>
                                                            <option value="default" disabled="" selected>Choose State</option>
                                                            <option <?php
                                                            if ($company_info->state == 'AL') {
                                                                echo"selected";
                                                            }
                                                            ?> value="AL">Alabama</option>
                                                            <option <?php
                                                            if ($company_info->state == 'AK') {
                                                                echo"selected";
                                                            }
                                                            ?> value="AK">Alaska</option>
                                                            <option <?php
                                                                if ($company_info->state == 'AZ') {
                                                                    echo"selected";
                                                                }
                                                                ?> value="AZ">Arizona</option>
                                                            <option <?php
                                                            if ($company_info->state == 'AR') {
                                                                echo"selected";
                                                            }
                                                            ?> value="AR">Arkansas</option>
                                                            <option <?php
                                                            if ($company_info->state == 'CA') {
                                                                echo"selected";
                                                            }
                                                            ?> value="CA">California</option>
                                                            <option <?php
                                                                if ($company_info->state == 'CO') {
                                                                    echo"selected";
                                                                }
                                                                ?> value="CO">Colorado</option>
                                                            <option <?php
                                                            if ($company_info->state == 'CT') {
                                                                echo"selected";
                                                            }
                                                            ?> value="CT">Connecticut</option>
                                                            <option <?php
                                                            if ($company_info->state == 'CT') {
                                                                echo"selected";
                                                            }
                                                            ?> value="CT">Delaware</option>
                                                            <option <?php
                                                                if ($company_info->state == 'DC') {
                                                                    echo"selected";
                                                                }
                                                                ?> value="DC">District Of Columbia</option>
                                                            <option <?php
                                                            if ($company_info->state == 'FL') {
                                                                echo"selected";
                                                            }
                                                            ?> value="FL">Florida</option>
                                                            <option <?php
                                                            if ($company_info->state == 'GA') {
                                                                echo"selected";
                                                            }
                                                            ?> value="GA">Georgia</option>
                                                            <option <?php
                                                                if ($company_info->state == 'HI') {
                                                                    echo"selected";
                                                                }
                                                                ?> value="HI">Hawaii</option>
                                                            <option <?php
                                                            if ($company_info->state == 'ID') {
                                                                echo"selected";
                                                            }
                                                            ?> value="ID">Idaho</option>
                                                            <option <?php
                                                            if ($company_info->state == 'IL') {
                                                                echo"selected";
                                                            }
                                                            ?> value="IL">Illinois</option>
                                                            <option <?php
                                                                if ($company_info->state == 'IN') {
                                                                    echo"selected";
                                                                }
                                                                ?> value="IN">Indiana</option>
                                                            <option <?php
                                                            if ($company_info->state == 'IA') {
                                                                echo"selected";
                                                            }
                                                            ?> value="IA">Iowa</option>
                                                            <option <?php
                                                            if ($company_info->state == 'KS') {
                                                                echo"selected";
                                                            }
                                                            ?> value="KS">Kansas</option>
                                                            <option <?php
                                                                if ($company_info->state == 'KY') {
                                                                    echo"selected";
                                                                }
                                                                ?> value="KY">Kentucky</option>
                                                            <option <?php
                                                            if ($company_info->state == 'LA') {
                                                                echo"selected";
                                                            }
                                                            ?> value="LA">Louisiana</option>
                                                            <option <?php
                                                            if ($company_info->state == 'ME') {
                                                                echo"selected";
                                                            }
                                                            ?> value="ME">Maine</option>
                                                            <option <?php
                                                                if ($company_info->state == 'MD') {
                                                                    echo"selected";
                                                                }
                                                                ?> value="MD">Maryland</option>
                                                            <option <?php
                                                            if ($company_info->state == 'MA') {
                                                                echo"selected";
                                                            }
                                                            ?> value="MA">Massachusetts</option>
                                                            <option <?php
                                                            if ($company_info->state == 'MI') {
                                                                echo"selected";
                                                            }
                                                            ?> value="MI">Michigan</option>
                                                            <option <?php
                                                                if ($company_info->state == 'MN') {
                                                                    echo"selected";
                                                                }
                                                                ?> value="MN">Minnesota</option>
                                                            <option <?php
                                                            if ($company_info->state == 'MS') {
                                                                echo"selected";
                                                            }
                                                            ?> value="MS">Mississippi</option>
                                                            <option <?php
                                                            if ($company_info->state == 'MO') {
                                                                echo"selected";
                                                            }
                                                            ?> value="MO">Missouri</option>
                                                            <option <?php
                                                                if ($company_info->state == 'MT') {
                                                                    echo"selected";
                                                                }
                                                                ?> value="MT">Montana</option>
                                                            <option <?php
                                                            if ($company_info->state == 'NE') {
                                                                echo"selected";
                                                            }
                                                            ?> value="NE">Nebraska</option>
                                                            <option <?php
                                                            if ($company_info->state == 'NV') {
                                                                echo"selected";
                                                            }
                                                            ?> value="NV">Nevada</option>
                                                            <option <?php
                                                                if ($company_info->state == 'NH') {
                                                                    echo"selected";
                                                                }
                                                                ?> value="NH">New Hampshire</option>
                                                            <option <?php
                                                            if ($company_info->state == 'NJ') {
                                                                echo"selected";
                                                            }
                                                            ?> value="NJ">New Jersey</option>
                                                            <option <?php
                                                            if ($company_info->state == 'NM') {
                                                                echo"selected";
                                                            }
                                                            ?> value="NM">New Mexico</option>
                                                            <option <?php
                                                            if ($company_info->state == 'NY') {
                                                                echo"selected";
                                                            }
                                                            ?> value="NY">New York</option>
                                                            <option <?php
                                                            if ($company_info->state == 'NC') {
                                                                echo"selected";
                                                            }
                                                            ?> value="NC">North Carolina</option>
                                                            <option <?php
                                                            if ($company_info->state == 'ND') {
                                                                echo"selected";
                                                            }
                                                            ?> value="ND">North Dakota</option>
                                                            <option <?php
                                                            if ($company_info->state == 'OH') {
                                                                echo"selected";
                                                            }
                                                            ?> value="OH">Ohio</option>
                                                            <option <?php
                                                            if ($company_info->state == 'OK') {
                                                                echo"selected";
                                                            }
                                                            ?> value="OK">Oklahoma</option>
                                                            <option <?php
                                                            if ($company_info->state == 'OR') {
                                                                echo"selected";
                                                            }
                                                            ?> value="OR">Oregon</option>
                                                            <option <?php
                                                            if ($company_info->state == 'PA') {
                                                                echo"selected";
                                                            }
                                                            ?> value="PA">Pennsylvania</option>
                                                            <option <?php
                                                            if ($company_info->state == 'RI') {
                                                                echo"selected";
                                                            }
                                                            ?> value="RI">Rhode Island</option>
                                                            <option <?php
                                                            if ($company_info->state == 'SC') {
                                                                echo"selected";
                                                            }
                                                            ?> value="SC">South Carolina</option>
                                                            <option <?php
                                                            if ($company_info->state == 'SD') {
                                                                echo"selected";
                                                            }
                                                            ?> value="SD">South Dakota</option>
                                                            <option <?php
                                                            if ($company_info->state == 'TN') {
                                                                echo"selected";
                                                            }
                                                            ?> value="TN">Tennessee</option>
                                                            <option <?php
                                                            if ($company_info->state == 'TX') {
                                                                echo"selected";
                                                            }
                                                            ?> value="TX">Texas</option>
                                                            <option <?php
                        if ($company_info->state == 'UT') {
                            echo"selected";
                        }
                        ?> value="UT">Utah</option>
                                                            <option <?php
                        if ($company_info->state == 'VT') {
                            echo"selected";
                        }
                        ?> value="VT">Vermont</option>
                                                            <option <?php
                                if ($company_info->state == 'VA') {
                                    echo"selected";
                                }
                                ?> value="VA">Virginia</option>
                                                            <option <?php
                                        if ($company_info->state == 'WA') {
                                            echo"selected";
                                        }
                                        ?> value="WA">Washington</option>
                                                            <option <?php
                                        if ($company_info->state == 'WV') {
                                            echo"selected";
                                        }
                                        ?> value="WV">West Virginia</option>
                                                            <option <?php
                                        if ($company_info->state == 'WI') {
                                            echo"selected";
                                        }
                                        ?> value="WI">Wisconsin</option>
                                                            <option <?php
                                        if ($company_info->state == 'WY') {
                                            echo"selected";
                                        }
                                        ?> value="WY">Wyoming</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <button class="btn btn--primary btn--m btn--block form--submit save--toggle page--reload" data-target="#companyAddressForm">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                                <?php } else { ?>
                        <!-- /Address -->
                        <!-- Address -->               
                        <div class="accordion__group">
                            <div class="accordion__section">
                                <div class="accordion__title wrapper">
                                    <div class="wrapper__inner">
                                        <h3>Address</h3>
                                    </div>
    <?php if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $tier1))) { ?>
                                        <div class="wrapper__inner align--right">
                                            <a class="link link--expand">Edit</a>
                                        </div>
    <?php } ?>
                                </div>
                                <div class="accordion__content">
                                    <div class="accordion__preview">
                                    </div>
                                    <div class="accordion__edit">
                                        <form id="companyAddressForm" class="form__group" action="<?php echo base_url("UserDashboard/companyAddress"); ?>" method="post">
                                            <input type="hidden" name="company_id" class="company_id" value="<?php echo $company_info->id; ?>">
                                            <div class="row">
                                                <div class="input__group is--inline">
                                                    <input id="companyAddress1" name="companyAddress1" class="input not--empty" type="text" value="" required>
                                                    <label class="label" for="companyAddress1">Address Line 1</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col col--1-of-2">
                                                    <div class="input__group is--inline">
                                                        <input id="companyAddress2" name="companyAddress2" class="input not--empty" type="text" value="" required>
                                                        <label class="label pl-4" for="companyAddress2">Unit/Suite/#</label>
                                                    </div>
                                                </div>
                                                <div class="col col--1-of-2">
                                                    <div class="input__group is--inline">
                                                        <input id="companyZip" name="companyZip" class="input not--empty" type="text" placeholder="90210" value="" required>
                                                        <label class="label" for="companyZip">Zip</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <button class="btn btn--primary btn--m btn--block form--submit save--toggle page--reload" data-target="#companyAddressForm">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Address -->
<?php } ?>
                    <!-- Tax ID -->
                            <?php if ($organ_id != null) { ?>
                        <div class="accordion__group">
                            <div class="accordion__section">
                                <div class="accordion__title wrapper">
                                    <div class="wrapper__inner">
                                        <h3>Tax ID</h3>
                                    </div>
    <?php if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $tier1))) { ?>
                                        <div class="wrapper__inner align--right">
                                            <a class="link link--expand">Edit</a>
                                        </div>
    <?php } ?>
                                </div>
                                <div class="accordion__content">
                                    <div class="accordion__preview">
    <?php echo $company_detail->tax_id; ?>
                                    </div>
                                    <div class="accordion__edit">
                                        <form id="companyTaxIdForm" class="form__group" action="<?php echo base_url("upadate-comapanyTaxId"); ?>" method="post">
                                            <input type="hidden" name="company_id" class="company_id" value="<?php echo $company_detail->id; ?>">
                                            <div class="row">
                                                <div class="input__group is--inline col-md-12">
                                                    <input id="companyTaxID" name="companyTaxID" class="input input--tax-id not--empty" type="text" value="<?php echo $company_detail->tax_id; ?>" required>
                                                    <label class="label" for="companyTaxID">Tax ID Number</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <button class="btn btn--primary btn--m btn--block form--submit save--toggle page--reload" data-target="#companyTaxIdForm">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Tax ID -->
<?php } else {
    ?>  
                        <!-- Tax ID -->
                        <div class="accordion__group">
                            <div class="accordion__section">
                                <div class="accordion__title wrapper">
                                    <div class="wrapper__inner">
                                        <h3>Tax ID</h3>
                                    </div>
    <?php if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $tier1))) { ?>
                                        <div class="wrapper__inner align--right">
                                            <a class="link link--expand">Edit</a>
                                        </div>
    <?php } ?>
                                </div>
                                <div class="accordion__content">
                                    <div class="accordion__preview">
                                    </div>
                                    <div class="accordion__edit">
                                        <form id="companyTaxIdForm" class="form__group" action="<?php echo base_url("UserDashboard/comapanyTaxId"); ?>" method="post">
                                            <input type="hidden" name="company_id" class="company_id" value="<?php echo $company_detail->id; ?>">
                                            <div class="row">
                                                <div class="input__group is--inline">
                                                    <input id="companyTaxID" name="companyTaxID" class="input input--tax-id not--empty" type="text" value="" required>
                                                    <label class="label" for="companyTaxID">Tax ID Number</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <button class="btn btn--primary btn--m btn--block form--submit save--toggle page--reload" data-target="#companyTaxIdForm">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Tax ID -->
<?php } ?>
                </div>
                <!-- Sidebar -->
                <div class="sidebar col-md-4 col-xs-12 bg--white">
                    <div class="sidebar__group mobile-center">
                        <h4>Manage Company</h4>
                        <ul class="list">
<?php
$tier3_roles = unserialize(ROLES_TIER3);
$tier_1_2_roles = unserialize(ROLES_TIER1_2);
if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $tier_1_2_roles)))) {
    ?>
                                <li class="item">
                                    <a class="link" href="<?php echo base_url('locations'); ?>">Locations</a>
                                </li>
                                <li class="item">
                                    <a class="link" href="<?php echo base_url('manage-inventory'); ?>">Inventory</a>
                                </li>
<?php } ?>
                            <li class="item">
                                <a class="link" href="<?php echo base_url('shopping-lists'); ?>">Shopping Lists</a>
                            </li>
                            <li class="item">
                                <a class="link" href="<?php echo base_url('request-lists'); ?>">Request Lists</a>
                            </li>
<?php if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $tier_1_2_roles)))) { ?>
                                <li class="item">
                                    <a class="link" href="<?php echo base_url('Manage-Users'); ?>">Users</a>
                                </li>
<?php } ?>
                        </ul>
                    </div>
                </div>
                <!-- /Sidebar -->
            </div>
        </div>
    </section>
    <!-- /Main Content -->
</div>
<!-- /Content Section -->
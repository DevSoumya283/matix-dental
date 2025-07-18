<!-- Configure Report Modal -->
<div id="configReportVendorModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Configure report</h2>
            <p class="no--margin">To configure report, please fill out the fields below:</p>
        </div>
        <hr class="margin--m no--margin-lr border--lightest">
        <div class="modal__body center center--h align--left cf">
            <form id="configReportVendorModal" class="form__group">
                <div class="modal__content">
                    <div class="row form__row" style="margin-bottom:24px;">
                        <div class="col col--6-of-6 col--am">
                            <div class="input__group input__group--date-range is--inline input-daterange">
                                <div class="range__icon">
                                    <svg class="icon icon--calendar"><use xlink:href="#icon-calendar"></use></svg>
                                </div>
                                <div class="range__fields">
                                    <input type="text" class="input input--date" id="vendorStartDate" placeholder="MM/DD/YYYY" name="start" value="" required>                                
                                    <input type="text" class="input input--date" id="vendorEndDate" placeholder="MM/DD/YYYY" name="end" value="" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <h5 class="title">Additional Filters</h5>
                    <div class="row">
                        <div class="input__group is--inline">
                            <div class="select">
                                <select class="Category_select" required>
                                    <option value="">All Categories</option>
                                    <option disabled="">&nbsp;</option>
                                    <option disabled="">— Classic View</option>
                                    <?php for ($i = 0; $i < count($classic); $i++) { ?> 
                                        <option value="<?php echo $classic[$i]->id; ?>"><?php
                                            echo $classic[$i]->name;
                                            echo "&nbsp;" . "(" . $classic[$i]->count . ")";
                                            ?>
                                        </option>
                                    <?php } ?>
                                    <option disabled="">&nbsp;</option>
                                    <option disabled="">— Dentist View</option>
                                    <?php for ($i = 0; $i < count($dentist); $i++) { ?> 
                                        <option value="<?php echo $dentist[$i]->id; ?>"><?php
                                            echo $dentist[$i]->name;
                                            echo "&nbsp;" . "(" . $dentist[$i]->count . ")";
                                            ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input__group is--inline">
                            <div class="select">
                                <select  class="location_id" required>
                                    <option value="">Select a Location</option>
                                    <?php foreach ($config_locations as $locations) { ?>
                                        <option value="<?php echo $locations->id; ?>"><?php echo $locations->nickname; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input__group is--inline">
                            <div class="select">
                                <select class="manufacturer_name" required>
                                    <option value="">Select a Manufacturer</option>
                                    <?php foreach ($config_manufacturer as $manu) { ?>
                                        <option value="<?php echo $manu->manufacturer; ?>"><?php echo $manu->manufacturer; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!--                    <div class="row">
                                            <div class="input__group is--inline">
                                                <div class="select">
                                                    <select required>
                                                        <option disabled selected value="default">Select Categories</option>
                                                        <option value="1">Category 1</option>
                                                        <option value="2">Category 2</option>
                                                        <option value="3">Category 3</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>-->
                </div>
                <div class="footer__group border--dashed">
                    <button class="btn btn--m btn--primary btn--block <?php echo ($vendorOrderFilter != null) ? $vendorOrderFilter : " "; ?>">Apply</button>
                </div>
            </form>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Configure Report Modal -->

<!--  --><!-- /Configure Report Modal -->
<!--<div id="configReportModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Configure report</h2>
            <p class="no--margin">To configure report, please fill out the fields below:</p>
        </div>
        <hr class="margin--m no--margin-lr border--lightest">
        <div class="modal__body center center--h align--left cf">
            <!---<form id="configReportForm" class="form__group">
            <div class="form__group">
                <div class="modal__content">
                    <div class="row form__row" style="margin-bottom:24px;">
                        <div class="col col--6-of-6 col--am">
                            <div class="input__group input__group--date-range is--inline input-daterange">
                                <div class="range__icon">
                                    <svg class="icon icon--calendar"><use xlink:href="#icon-calendar"></use></svg>
                                </div>
                                <div class="range__fields">
                                    <input type="text" class="input input--date cstartDate" placeholder="MM/DD/YYYY" name="start_date" name="rangeFrom">
                                    <input type="text" class="input input--date cendDate" placeholder="MM/DD/YYYY" name="end_date" name="rangeFrom">
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <h5 class="title">Additional Filters</h5>
                    <div class="row">
                    <input type="hidden" name="currenturl" class="curl" value="<?php echo base_url().$_SERVER['REQUEST_URI']; ?>">
                        <div class="input__group is--inline">
                            <div class="select">
                                <select name="categories" class="all_categories" required>
                                    <option disabled="" selected="">All Categories</option>
                                    <option >&nbsp;</option>
                                    <option >— Classic View</option>
                                    <?php for($i=0;$i<count($classics);$i++){ ?> 
                                    <option value="<?php echo $classics[$i]->name; ?>"><?php echo $classics[$i]->name; echo "&nbsp;"."(".$classics[$i]->count.")"; ?>
                                    </option>
                                     <?php } ?>                                    
                                    <option disabled="">&nbsp;</option>
                                    <option disabled="">— Dentist View</option>
                                    <?php for($i=0;$i<count($dentists);$i++){ ?> 
                                    <option value="<?php echo $dentists[$i]->name; ?>">
                                    <?php echo $dentists[$i]->name; echo "&nbsp;"."(".$dentists[$i]->count.")"; ?>
                                    </option>
                                     <?php } ?>

                                    <option selected="">All Categories</option>
                                    <option disabled="">&nbsp;</option>
                                    <option disabled="">— Classic View</option>
                                    <option value="1">Category One (37)</option>
                                    <option value="2">Category Two (4)</option>
                                    <option value="3">Category Three (17)</option>
                                    <option disabled="">&nbsp;</option>
                                    <option disabled="">— Dentist View</option>
                                    <option value="1">Category One (37)</option>
                                    <option value="2">Category Two (4)</option>
                                    <option value="3">Category Three (17)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input__group is--inline">
                            <div class="select">
                                <select name="locName" class="locations" required>
                                    <option disabled selected value="default">Select a Location</option>
                                    <?php for($i=0;$i<count($loc_Name);$i++){ ?>
                                    <option value="<?php echo $loc_Name[$i]->nickname ?>"><?php echo $loc_Name[$i]->nickname ?></option>
                                    <?php } ?>
                                    <!-- <option value="1">Location 1</option>
                                    <option value="1">Location 2</option>
                                    <option value="2">Location 3</option>
                                     
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input__group is--inline">
                            <div class="select">
                                <select name="vendorName" class="vendors" required>
                                    <option disabled selected value="default">Select a Vendor</option>
                                    <?php for($i=0;$i<count($ven_Name);$i++){ ?>
                                    <option value="<?php echo $ven_Name[$i]->name ?>"><?php echo $ven_Name[$i]->name ?></option>
                                    <?php } ?>
                                <!--     <option value="1">Vendor 1</option>
                                    <option value="1">Vendor 2</option>
                                    <option value="2">Vendor 3</option>
                                 </select>
                            </div>
                        </div>
                    </div>
                  <!--   <div class="row">
                        <div class="input__group is--inline">
                            <div class="select">
                                <select required>
                                    <option disabled selected value="default">Select Categories</option>
                                    <option value="1">Category 1</option>
                                    <option value="1">Category 2</option>
                                    <option value="2">Category 3</option>
                                </select>
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="footer__group border--dashed">
                    <button class="btn btn--m btn--primary btn--block save--toggle configure">Apply</button>
                    <!-- <button class="btn btn--m btn--primary btn--block save--toggle form--submit configure" data-target="#configReportForm">Apply</button> 
                </div>
                </div>
            <!--</form>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>

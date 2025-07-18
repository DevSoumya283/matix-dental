<div id="reportOrders" class="page__tab">
    <!-- Heading -->
    <div class="heading__group border--dashed padding--s no--pad-lr no--pad-t wrapper">
        <div class="wrapper__inner">
            <h4>Orders Report</h4>            
        </div>
        <div id="controlsOrdersReport" class="contextual__controls wrapper__inner align--right">
            <ul class="list list--inline fontWeight--2 fontSize--s disp--ib">
                <li class="item">
                    <a class="link print_orders_report" href="javascript:void(0)">Print</a>
                </li>
            </ul>
            <button class="btn btn--tertiary btn--s  margin--s no--margin-tb no--margin-r" id="exportscsv" data-export="export">Download</button>
            <button class="btn btn--primary btn--s  margin--xs no--margin-tb no--margin-r  is--contextual is--off modal--toggle" data-target="#productsComparisonModal">Compare</button>
        </div>
    </div>
    <!-- /Heading -->
    <!-- Filters -->
    <div class="row border border--dashed border--1 border--light border--b padding--m no--pad-lr no--pad-t pull--up-xs">
        <div class="col-md-6 col-xs-12">
            <div class="input__group input__group--date-range is--inline input-daterange">
                <div class="range__icon">
                    <svg class="icon icon--calendar"><use xlink:href="#icon-calendar"></use></svg>
                </div>
                <div class="range__fields">
                    <input type="text" class="input input--date start_order" placeholder="MM/DD/YYYY" name="start" value="" disabled="">
                    <input type="text" class="input input--date end_order" placeholder="MM/DD/YYYY" name="end" value="" disabled="">
                </div>
            </div>
        </div>
        <div class="col col--5-of-8 col--am align--right">
            <ul class="list list--inline list--filters disp--ib">
                <li class="item item--filter order_location" id="order_location" style="display: none;">
                    Location Name
                    <a class="filter--clear" href="#"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
                </li>
                <li class="item item--filter order_vendor" id="order_vendor" style="display: none;">
                    Vendor Name
                    <a class="filter--clear" href="#"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
                </li>
            </ul>
            <button class="btn btn--tertiary btn--s btn--icon margin--s no--margin-tb no--margin-r modal--toggle" data-target="#configReportModal1"><svg class="icon icon--settings"><use xlink:href="#icon-settings"></use></svg></button>
        </div>
    </div>
    <!-- /Filters -->
    <div id="configReportModal1" class="modal modal--m">
        <div class="modal__wrapper modal__wrapper--transition padding--l">
            <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
            <div class="modal__header center center--h align--left mobile-center">
                <h2>Configure report</h2>
                <p class="no--margin">To configure report, please fill out the fields below:</p>
            </div>
            <hr class="margin--m no--margin-lr border--lightest">
            <div class="modal__body center center--h align--left cf">
                <!---<form id="configReportForm" class="form__group">-->
                <div class="form__group">
                    <div class="modal__content modal-margin">
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
                        <input type="hidden" name="currenturl" class="curl active1" value="orders">
                        <div class="row">
                            <div class="input__group is--inline">
                                <div class="select">
                                    <select name="locName" class="locations order_locations" required>
                                        <option value="">Select a Location</option>
                                        <?php for ($i = 0; $i < count($spending_by_location); $i++) { ?>
                                            <option value="<?php echo $spending_by_location[$i]->location_id ?>"><?php echo $spending_by_location[$i]->nickname ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input__group is--inline">
                                <div class="select">
                                    <select name="vendorName" class="vendors order_vendors" required>
                                        <option value="">Select a Vendor</option>
                                        <?php for ($i = 0; $i < count($spending_vendors); $i++) { ?>
                                            <option value="<?php echo $spending_vendors[$i]->vendor_id ?>"><?php echo $spending_vendors[$i]->vendor_name ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="footer__group border--dashed modal-margin">
                        <button class="btn btn--m btn--primary btn--block save--toggle configure-orders">Apply</button>
                    </div>
                </div>
                <!--</form>-->
            </div>
        </div>
        <div class="modal__overlay modal--toggle"></div>
    </div>

    <!-- Report -->
    <div class="report order_report">
        <!-- Totals -->
     <?php $this->load->view('templates/account/reports/order_data.php'); ?>
    </div>

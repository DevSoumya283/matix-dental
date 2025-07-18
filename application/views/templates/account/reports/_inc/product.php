<div id="reportProduct" class="page__tab">
    <!-- Heading -->
    <div class="heading__group border--dashed padding--s no--pad-lr no--pad-t wrapper">
        <div class="wrapper__inner">
            <h4>Purchase Report</h4>
        </div>
        <div id="controlsProductReport" class="contextual__controls wrapper__inner align--right">
            <ul class="list list--inline fontWeight--2 fontSize--s disp--ib">
                <li class="item">
                    <a class="link print_product_report" href="javascript:void(0)">Print</a>
                </li>
            </ul>
            <button class="btn btn--tertiary btn--s  margin--s no--margin-tb no--margin-r" data-export="export" id="exportscsv2">Download</button>
            <button class="btn btn--primary btn--s  margin--xs no--margin-tb no--margin-r" id="productsComparisonButton" data-target="#productsComparisonModal">Compare</button>
        </div>
    </div>
    <!-- /Heading -->
    <!-- Purchased Products Modal -->
    <div id="productsComparisonModal" class="modal modal--l">
        <div class="modal__wrapper modal__wrapper--transition">
            <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
            <div class="modal__header center center--h align--left" style="max-width:640px;">
                <h2 class="fontSize--l">Comparing</h2>
            </div>
            <div class="modal__body center center--h align--left cf" style="max-width:640px;">
                <div class="modal__content">
                    <canvas id="chartProductsComparison" class="chart"></canvas>
                </div>
            </div>
        </div>
        <div class="modal__overlay modal--toggle"></div>
    </div>
    <!-- /Purchased Products Modal -->



    <!-- Filters -->
    <div class="row border border--dashed border--1 border--light border--b padding--m no--pad-lr no--pad-t pull--up-xs">
        <div class="col-md-6 col-xs-12">
            <div class="input__group input__group--date-range is--inline input-daterange">
                <div class="range__icon">
                    <svg class="icon icon--calendar"><use xlink:href="#icon-calendar"></use></svg>
                </div>
                <div class="range__fields">
                    <input type="text" class="input input--date startDate" placeholder="MM/DD/YYYY" name="start" disabled>
                    <input type="text" class="input input--date endDate" placeholder="MM/DD/YYYY" name="end" disabled>
                </div>
            </div>
        </div>
        <div class="col col--5-of-8 col--am align--right">
            <ul class="list list--inline list--filters disp--ib">
                <li class="item item--filter product_location" id="product_location" style="display: none;">
                    Location Name
                    <a class="filter--clear" href="#"></a>
                </li>
                <li class="item item--filter product_vendor" id="product_vendor" style="display: none;">
                    Vendor Name
                    <a class="filter--clear" href="#"></a>
                </li>
                <li class="item item--filter product_category" id="product_category" style="display: none;">
                    Category Name
                    <a class="filter--clear" href="#"></a>
                </li>
            </ul>
            <button class="btn btn--tertiary btn--s btn--icon margin--s no--margin-tb no--margin-r modal--toggle" data-target="#configReportModal2"><svg class="icon icon--settings"><use xlink:href="#icon-settings"></use></svg></button>
        </div>
    </div>
    <!-- /Filters -->
    <div id="configReportModal2" class="modal modal--m">
        <div class="modal__wrapper modal__wrapper--transition padding--l">
            <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
            <div class="modal__header center center--h align--left mobile-center">
                <h2>Configure report</h2>
                <p class="no--margin">To configure report, please fill out the fields below:</p>
            </div>
            <hr class="margin--m no--margin-lr border--lightest">
            <div class="modal__body center center--h align--left cf">
                <div class="form__group">
                    <div class="modal__content modal-margin">
                        <div class="row form__row" style="margin-bottom:24px;">
                            <div class="col col--6-of-6 col--am">
                                <div class="input__group input__group--date-range is--inline input-daterange">
                                    <div class="range__icon">
                                        <svg class="icon icon--calendar"><use xlink:href="#icon-calendar"></use></svg>
                                    </div>
                                    <div class="range__fields">
                                        <input type="text" class="input input--date cstartDate2" placeholder="MM/DD/YYYY" name="start_date" name="rangeFrom">
                                        <input type="text" class="input input--date cendDate2" placeholder="MM/DD/YYYY" name="end_date" name="rangeFrom">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <h5 class="title">Additional Filters</h5>
                        <div class="row">
                            <div class="input__group is--inline">
                                <div class="select">
                                    <select name="categories" class="all_categories2 product_categories" required>
                                        <option value="">All Categories</option>
                                        <option value="" >&nbsp;</option>
                                        <option value="" >— Classic View</option>
                                        <?php for ($i = 0; $i < count($classics); $i++) { ?> 
                                            <option value="<?php echo $classics[$i]->id; ?>"><?php
                                                echo $classics[$i]->name;
                                                echo "&nbsp;" . "(" . $classics[$i]->count . ")";
                                                ?>
                                            </option>
                                        <?php } ?>                                    
                                        <option value="" disabled="">&nbsp;</option>
                                        <option value="" disabled="">— Dentist View</option>
                                        <?php for ($i = 0; $i < count($dentists); $i++) { ?> 
                                            <option value="<?php echo $dentists[$i]->id; ?>">
                                                <?php
                                                echo $dentists[$i]->name;
                                                echo "&nbsp;" . "(" . $dentists[$i]->count . ")";
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
                                    <select name="locName" class="locations2 product_locations" required>
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
                                    <select name="vendorName" class="vendors2 product_vendors" required>
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
                        <button class="btn btn--m btn--primary btn--block save--toggle configure-products">Apply</button>
                    </div>
                </div>
                <!--</form>-->
            </div>
        </div>
        <div class="modal__overlay modal--toggle"></div>
    </div>
    <!-- Report -->
    <div class="report product_report">
     <?php $this->load->view('templates/account/reports/product_data.php'); ?>
    </div>
    <!-- /Report -->
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.min.js"></script>
<!-- Charts -->
<script type="text/javascript">
    $(document).ready(function () {
        $('#productsComparisonButton').hide();
        $(document).on("change", '.single-checkbox', function (e) {
            if ($('.single-checkbox:checked').length == 2) {
                $('#productsComparisonButton').show();
            } else {
                $('#productsComparisonButton').hide();
            }
        });
        $(document).on("click", "#productsComparisonButton", function () {
            var modal = $(this).data("target");
            activeModal = $(this).data("target");
            $("body").addClass("has-modal");
            if ($(document).height() > $(window).height()) {
                $("body").css({
                    "padding-right": $.scrollbarWidth()
                })
            }
            $(modal).addClass('is-visible');

            // If the modal has multi-states, open to the correct state
            if ($(this).is('[data-state]')) {
                var stateTarget = $(this).data('state-target'),
                        states = $(stateTarget).data('states'),
                        state = $(this).data('state'),
                        tabs = $(stateTarget).find('.tab__group input[type="radio"]'),
                        activeTab = $(stateTarget).find('.tab__group .tab[value="' + state + '"] input[type="radio"]');

                $(stateTarget).removeClass(states);
                $(stateTarget).addClass(state);

                // Set the tabs to the correct state (if applicable)
                $(tabs).prop('checked', false);
                $(activeTab).prop('checked', true);
            }

            // If contains there is a 'today date' input and it's not empty or changed
            if (($('.is--today').length) && (!$('.is--today').hasClass('not--empty'))) {
                todayDate();
            }

            var product1 = $('.single-checkbox:checked')[0].value;
            var product2 = $('.single-checkbox:checked')[1].value;
            $.getJSON(base_url + "compare-purchases?product1=" + product1 + "&product2=" + product2, function (data) {

                // Products Comparison
                var products = document.getElementById("chartProductsComparison");
                var chartProductsCompared = new Chart(products, {
                    type: 'line',
                    data: {
                        labels: data.chart_xaxis,
                        datasets: [{
                                label: data.product_one_name,
                                fill: true,
                                lineTension: 0.0,
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                borderColor: 'rgba(255,99,132,1)',
                                borderCapStyle: 'butt',
                                borderWidth: 2,
                                borderDash: [5, 5],
                                borderDashOffset: 0.0,
                                borderJoinStyle: 'miter',
                                pointBorderColor: 'rgba(255,99,132,1)',
                                pointBackgroundColor: "#fff",
                                pointBorderWidth: 4,
                                pointHoverRadius: 5,
                                pointHoverBackgroundColor: "rgba(255,255,255,1)",
                                pointHoverBorderColor: 'rgba(255,99,132,1)',
                                pointHoverBorderWidth: 2,
                                pointRadius: 1,
                                pointHitRadius: 10,
                                data: data.chart_yaxis_product_one,
                                spanGaps: false,
                            },
                            {
                                label: data.product_two_name,
                                fill: true,
                                lineTension: 0.0,
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderCapStyle: 'butt',
                                borderWidth: 2,
                                borderDash: [],
                                borderDashOffset: 0.0,
                                borderJoinStyle: 'miter',
                                pointBorderColor: 'rgba(54, 162, 235, 1)',
                                pointBackgroundColor: "#fff",
                                pointBorderWidth: 4,
                                pointHoverRadius: 5,
                                pointHoverBackgroundColor: 'rgba(255, 255, 255, 1)',
                                pointHoverBorderColor: 'rgba(54, 162, 235, 1)',
                                pointHoverBorderWidth: 2,
                                pointRadius: 1,
                                pointHitRadius: 10,
                                data: data.chart_yaxis_product_two,
                                spanGaps: false,
                            }]
                    }
                });
            });

        });
    });


</script>
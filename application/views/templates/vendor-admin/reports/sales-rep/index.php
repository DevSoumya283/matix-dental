<?php $this->load->view('templates/_inc/header-vendor.php'); ?>
<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>
    <section class="content__wrapper has--sidebar-l bg--lightest-gray">
        <div class="content__main">
            <div class="row row--full-height">
                <!-- Sidebar -->
                <div class="sidebar col col--2-of-12" style="padding: 12px;">
                    <?php $this->load->view('templates/vendor-admin/_inc/nav.php'); ?>
                </div>
                <!-- /Sidebar -->
                <!-- Content Area -->
                <div class="content col col--9-of-12 col--push-1-of-12">
                    <!-- Heading -->
                    <div class="heading__group border--dashed padding--s no--pad-lr no--pad-t">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <h3>Sales Report</h3>
                            </div>
                            <div class="wrapper__inner align--right">
                                <ul class="list list--inline fontWeight--2 fontSize--s disp--ib">
                                    <li class="item">
                                        <a class="link print_vendor_report" href="javascript:void(0)">Print</a>
                                    </li>
                                </ul>
                                <button class="btn btn--tertiary btn--s  margin--s no--margin-tb no--margin-r" id="export" data-export="export">Download</button>
                            </div>
                        </div>
                    </div>
                    <!-- /Heading -->
                    <!-- Filters -->
                    <div class="row border border--dashed border--1 border--light border--b padding--m no--pad-lr no--pad-t pull--up-xs">
                        <div class="col col--3-of-8 col--am">
                            <div class="input__group input__group--date-range is--inline input-daterange">
                                <svg class="icon icon--calendar"><use xlink:href="#icvendorStartDateon-calendar"></use></svg>
                                <input type="text" class="input input--date vendorStartDate" id="startDate" placeholder="MM/DD/YYYY" name="startDate" value="<?php echo date('m/d/Y', strtotime($start_date)); ?>" disabled>
                                <input type="text" class="input input--date vendorEndDate" id="endDate" placeholder="MM/DD/YYYY" name="endDate" value="<?php echo date('m/d/Y', strtotime($end_date)); ?>" disabled>
                            </div>
                        </div>
                        <div class="col col--5-of-8 col--am align--right">
                            <ul class="list list--inline list--filters disp--ib">
                                <li class="item item--filter" id="UseLocationName" style="display:none;">
                                    Location Name
                                    <a class="filter--clear" href="#"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
                                </li>
                                <li class="item item--filter" id="ManufacturerName" style="display:none;">
                                    Manufacturer Name
                                    <a class="filter--clear" href="#"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
                                </li>
                                <li class="item item--filter" id="CategoryName" style="display:none;">
                                    Category Name
                                    <a class="filter--clear" href="#"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
                                </li>
                            </ul>
                            <button class="btn btn--tertiary btn--s btn--icon margin--s no--margin-tb no--margin-r modal--toggle" data-target="#configReportVendorModal"><svg class="icon icon--settings"><use xlink:href="#icon-settings"></use></svg></button>
                        </div>
                    </div>
                    <!-- /Filters -->
                    <br>
                    <div class="reportData">
                        <div class="row">
                            <div class="col col--8-of-8 col--am">
                                <div class="report__panel">
                                    <h4 class="panel__title">
                                        <span class="disp--ib" style="transform:translateY(5px);">Total Revenue</span>
                                        <div class="tab__group tab__group--s float--right">
                                            <label class="tab  resolutionSale" data-resolution="1">
                                                <input type="radio"  name="showBy"  value="1" <?php echo ($reportBy == 1) ? "checked" : ""; ?>>
                                                <span>Day</span>
                                            </label>
                                            <label class="tab  resolutionSale" data-resolution="2">
                                                <input type="radio"  name="showBy" value="2" <?php echo ($reportBy == 2) ? "checked" : ""; ?>>
                                                <span>Week</span>
                                            </label>
                                            <label class="tab  resolutionSale" data-resolution="3">
                                                <input type="radio"  name="showBy" value="3" <?php echo ($reportBy == 3) ? "checked" : ""; ?>>
                                                <span>Month</span>
                                            </label>
                                        </div>
                                    </h4>
                                    <div class="panel__content">
                                        <canvas id="chartMain" class="chart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Report -->
                        <div class="report">
                            <!-- Totals -->
                            <div class="row margin--m no--margin-r no--margin-t">
                                <div class="col col--2-of-8 col--am">
                                    <div class="report__panel panel--stat">
                                        <div class="stat__group">
                                            <span class="stat__title">
                                                Total Revenue
                                            </span>
                                            <span class="stat__value truncate">
                                                <?php echo ($total_revenue != null) ? "$" . $total_revenue : "0.00"; ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col col--2-of-8 col--am">
                                    <div class="report__panel panel--stat">
                                        <div class="stat__group">
                                            <span class="stat__title">
                                                Items
                                            </span>
                                            <span class="stat__value truncate">
                                                <?php echo ($total_items != null) ? $total_items : "0"; ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col col--2-of-8 col--am">
                                    <div class="report__panel panel--stat">
                                        <div class="stat__group">
                                            <span class="stat__title">
                                                Categories
                                            </span>
                                            <span class="stat__value truncate">
                                                <?php echo count($unique_product_categories); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col col--2-of-8 col--am">
                                    <div class="report__panel panel--stat">
                                        <div class="stat__group">
                                            <span class="stat__title">
                                                Manufacturers
                                            </span>
                                            <span class="stat__value truncate">
                                                <?php echo ($manufacturers != null) ? $manufacturers : "0"; ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /Totals -->
                            <hr>
                            <div style="overflow: hidden; overflow-x: scroll;">
                            <table class="table" id="export_order">
                                <thead>
                                    <tr>
                                        <th width="30%">
                                            Item
                                        </th>
                                        <th>
                                            SKU
                                        </th>
                                        <th>
                                            Mfg
                                        </th>
                                        <th>
                                            Cat
                                        </th>
                                        <th>
                                            Sales
                                        </th>
                                        <th>
                                            % of Total
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Requested Item -->
                                    <?php if ($items != null) { ?>
                                        <?php foreach ($items as $details) { ?>
                                            <tr>
                                                <td class="fontWeight--2">
                                                    <?php echo $details->product_name; ?>
                                                </td>
                                                <td>
                                                    <?php echo $details->vendor_product_id; ?>
                                                </td>
                                                <td>
                                                    <?php echo $details->manufacturer; ?>
                                                </td>
                                                <td>
                                                    <?php echo $details->category; ?>
                                                </td>
                                                <td>
                                                    <?php echo "$" . number_format(floatval($details->order_item_total), 2); ?>
                                                </td>
                                                <td>
                                                    <div class="percentage" style="width:<?php echo number_format(floatval($details->order_item_total * 100 / $overall_revenue), 2); ?>%;"></div>
                                                    <?php echo number_format(floatval($details->order_item_total * 100 / $overall_revenue), 2) . "%"; ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                    <!-- /Requested Item -->
                                </tbody>
                            </table>
                            </div>
                        </div>
                        <!-- /Report -->
                    </div>
                    <!-- ReportData -->
                </div>
                <!-- /Content Area -->

            </div>
        </div>
    </section>
</div>
<!-- /Content Section -->

<!-- Charts -->
<script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.min.js"></script>
<script>
    var monthSpend = document.getElementById("chartMain");
    var chartSpendByMonth = new Chart(monthSpend, {
    type: 'line',
            data: {
            labels: [
<?php for ($i = 0; $i < count($chart_xaxis); $i++) { ?>
    <?php echo ($i > 0) ? "," : "" ?>'<?php echo $chart_xaxis[$i] ?>'
<?php } ?>
            ],
                    datasets: [{
                    fill: true,
                            lineTension: 0.0,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255,99,132,1)',
                            borderCapStyle: 'butt',
                            borderWidth: 2,
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
                            data: [
<?php for ($i = 0; $i < count($chart_yaxis); $i++) { ?>
    <?php echo ($i > 0) ? "," : "" ?>'<?php echo $chart_yaxis[$i] ?>'
<?php } ?>
                            ],
                            spanGaps: false,
                    }]
            },
            options: {
            legend: false
            }
    });
</script>
<?php $this->load->view('templates/_inc/shared/modals/config-report-vendor.php'); ?>
<?php
$this->load->view('templates/_inc/footer-vendor.php')?>

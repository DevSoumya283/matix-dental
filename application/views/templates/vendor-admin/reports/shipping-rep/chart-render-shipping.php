<div class="row">
    <div class="col col--8-of-8 col--am">
        <div class="report__panel">
            <h4 class="panel__title">
                <span class="disp--ib" style="transform:translateY(5px);">Expenses</span>
                <div class="tab__group tab__group--s float--right">
                    <label class="tab resolutionShipping" data-resolution="1">
                        <input type="radio" name="groupName"  value="1" <?php echo ($reportBy == 1) ? "checked" : ""; ?>>
                        <span>Day</span>
                    </label>
                    <label class="tab resolutionShipping" data-resolution="2">
                        <input type="radio" name="groupName" value="2" <?php echo ($reportBy == 2) ? "checked" : ""; ?>>
                        <span>Week</span>
                    </label>
                    <label class="tab resolutionShipping" data-resolution="3">
                        <input type="radio" name="groupName" value="3" <?php echo ($reportBy == 3) ? "checked" : ""; ?>>
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
                        Shipping Methods
                    </span>
                    <span class="stat__value truncate">
                        <?php echo ($shipping_methods != null) ? $shipping_methods : "0"; ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="col col--2-of-8 col--am">
            <div class="report__panel panel--stat">
                <div class="stat__group">
                    <span class="stat__title">
                        Orders
                    </span>
                    <span class="stat__value truncate">
                        <?php echo ($total_orders != null) ? $total_orders : "0"; ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="col col--2-of-8 col--am">
            <div class="report__panel panel--stat">
                <div class="stat__group">
                    <span class="stat__title">
                        Shipments
                    </span>
                    <span class="stat__value truncate">
                        <?php echo ($total_shipments != null) ? $total_shipments : "0"; ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="col col--2-of-8 col--am">
            <div class="report__panel panel--stat">
                <div class="stat__group">
                    <span class="stat__title">
                        Total Expenses
                    </span>
                    <span class="stat__value truncate">
                        <?php echo ($total_shipment_value != null) ? "$" . number_format(floatval($total_shipment_value), 2) : "0.00"; ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <!-- /Totals -->
    <hr>
    <table class="table" id="export_order">
        <thead>
            <tr>
                <th>
                    Name
                </th>
                <th>
                    Carrier
                </th>
                <th>
                    Total Spent
                </th>
                <th>
                    % of Total Shipping Expenses
                </th>
            </tr>
        </thead>
        <tbody>
            <!-- Table Row -->
            <?php if ($shipment_count_result != null) { ?>
                <?php foreach ($shipment_count_result as $details) { ?>
                    <tr>
                        <td>
                            <?php echo $details->shipping_type; ?>
                        </td>
                        <td>
                            <?php echo $details->carrier; ?>
                        </td>
                        <td>
                            <?php echo number_format(floatval($details->shipping_total), 2); ?>
                        </td>
                        <td>
                            <div class="percentage" style="width:<?php echo number_format(floatval($details->shipping_total * 100 / $total_shipment_value), 0); ?>%;"></div>
                            <?php echo number_format(floatval($details->shipping_total * 100 / $total_shipment_value), 2) . "%" ?>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
            <!-- /Table Row -->
        </tbody>
    </table>
</div>
<!-- /Report -->
<script>
    var monthSpend = document.getElementById("chartMain");
    var chartSpendByMonth = new Chart(monthSpend, {
    type: 'line',
            data: {
            labels: [<?php for ($i = 0; $i < count($chart_xaxis); $i++) { ?>
    <?php echo ($i > 0) ? "," : "" ?>'<?php echo $chart_xaxis[$i] ?>'
<?php } ?>],
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

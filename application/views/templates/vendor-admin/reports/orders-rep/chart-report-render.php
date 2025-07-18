<div class="row">
    <div class="col col--8-of-8 col--am">
        <div class="report__panel">
            <h4 class="panel__title">
                <span class="disp--ib" style="transform:translateY(5px);">Total Orders</span>
                <div class="tab__group tab__group--s float--right">
                    <label class="tab resolution" data-resolution="1">
                        <input type="radio" name="groupName" <?php echo ($reportBy == 1) ? "checked" : ""; ?>>
                        <span>Day</span>
                    </label>
                    <label class="tab resolution" data-resolution="2" >
                        <input type="radio" name="groupName" <?php echo ($reportBy == 2) ? "checked" : ""; ?>>
                        <span>Week</span>
                    </label>
                    <label class="tab resolution" data-resolution="3">
                        <input type="radio" name="groupName" <?php echo ($reportBy == 3) ? "checked" : ""; ?>>
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
        <div class="col col--4-of-12 col--am">
            <div class="report__panel panel--stat">
                <div class="stat__group">
                    <span class="stat__title">
                        Orders Received
                    </span>
                    <span class="stat__value truncate">
                        <?php echo ($order_count_result != null) ? $order_count_result[0]->order_count : "0"; ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="col col--4-of-12 col--am">
            <div class="report__panel panel--stat">
                <div class="stat__group">
                    <span class="stat__title">
                        Order Fulfilled
                    </span>
                    <span class="stat__value truncate">
                        <?php echo ($order_count_shipped_result != null) ? $order_count_shipped_result[0]->order_count : "0"; ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="col col--4-of-12 col--am">
            <div class="report__panel panel--stat">
                <div class="stat__group">
                    <span class="stat__title">
                        Total Revenue
                    </span>
                    <span class="stat__value truncate">
                        <?php echo ($total_revenue != null) ? "$" . number_format(floatval($total_revenue), 2) : "0.00"; ?>
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
                    Order Number
                </th>
                <th>
                    Customer
                </th>
                <th>
                    Packages
                </th>
                <th>
                    Order Total
                </th>
                <th>
                    % of Total Revenue
                </th>
            </tr>
        </thead>
        <tbody>
            <!-- Requested Item -->
            <?php if ($vendor_total_revenue_result != null) { ?>
                <?php foreach ($vendor_total_revenue_result as $report) { ?>
                    <tr>
                        <td>
                            <a class="link fontWeight--2" href="<?php echo base_url(); ?>vendor-order-details?order_id=<?php echo $report->orders_order_id; ?>"><?php echo $report->orders_order_id; ?></a>
                        </td>
                        <td>
                            <?php echo $report->first_name; ?>
                        </td>
                        <td>
                            <?php echo $report->package_count ?>
                        </td>
                        <td>
                            <?php echo "$" . number_format(floatval($report->total), 2); ?>
                        </td>
                        <td>
                            <div class="percentage" style="width:<?php echo number_format(floatval($report->total * 100 / $total_revenue), 0) . "%"; ?>;"></div>
                            <?php echo number_format(floatval($report->total * 100 / $total_revenue), 2) . "%"; ?>
                        </td>
                    </tr>
                <?php }
            }
            ?>
            <!-- /Requested Item -->
        </tbody>
    </table>
</div>
<!-- Charts -->
<script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.min.js"></script>
<script>
    var reportBy = 2;
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
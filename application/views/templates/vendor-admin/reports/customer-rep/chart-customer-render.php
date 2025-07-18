<div class="row">
    <div class="col col--8-of-8 col--am">
        <div class="report__panel">
            <h4 class="panel__title">
                <span class="disp--ib" style="transform:translateY(5px);">Customer Growth</span>
                <div class="tab__group tab__group--s float--right">
                    <label class="tab resolutionCustomer" data-resolution="1">
                        <input type="radio" value="1" name="groupName" <?php echo ($reportBy == 1) ? "checked" : ""; ?>>
                        <span>Day</span>
                    </label>
                    <label class="tab resolutionCustomer" data-resolution="2">
                        <input type="radio" value="2" name="groupName" <?php echo ($reportBy == 2) ? "checked" : ""; ?>>
                        <span>Week</span>
                    </label>
                    <label class="tab resolutionCustomer" data-resolution="3">
                        <input type="radio" value="3" name="groupName" <?php echo ($reportBy == 3) ? "checked" : ""; ?>>
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
                        Total
                    </span>
                    <span class="stat__value truncate">
                        <?php echo ($user_count_result != null) ? count($user_count_result) : "0"; ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="col col--4-of-12 col--am">
            <div class="report__panel panel--stat">
                <div class="stat__group">
                    <span class="stat__title">
                        New
                    </span>
                    <span class="stat__value truncate">
                        <?php echo ($new_users != null) ? $new_users : "0"; ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="col col--4-of-12 col--am">
            <div class="report__panel panel--stat">
                <div class="stat__group">
                    <span class="stat__title">
                        Organizations
                    </span>
                    <span class="stat__value truncate">
                        <?php echo ($organizations != null) ? $organizations : "0"; ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <!-- /Totals -->
    <hr>
    <div class="overlay__wrapper" style="overflow: scroll;">
    <table class="table" id="export_order">
        <thead>
            <tr>
                <th>
                    Customer
                </th>
                <th>
                    Organization
                </th>
                <th>
                    Orders
                </th>
                <th>
                    Spend Total
                </th>
                <th>
                    % of Total Revenue
                </th>
            </tr>
        </thead>
        <tbody>
            <!-- Table Row -->
            <?php if ($user_count_result != null) { ?>
                <?php foreach ($user_count_result as $report) { ?>
                    <tr>
                        <td class="fontWeight--2">
                            <a class="link" href="<?php echo base_url(); ?>customer-purchase-details?user_id=<?php echo $report->user_id; ?>"><?php echo ucwords($report->first_name); ?></a>
                        </td>
                        <td>
                            <?php echo ucwords($report->organization_name); ?>
                        </td>
                        <td>
                            <?php echo $report->user_count; ?>
                        </td>
                        <td>
                            <?php echo "$" . number_format(floatval($report->order_total), 2); ?>
                        </td>
                        <td>
                            <div class="percentage" style="width:<?php echo number_format(floatval($report->order_total * 100 / $total_order_value), 2); ?>%;"></div>
                            <?php echo number_format(floatval($report->order_total * 100 / $total_order_value), 2) . "%"; ?>
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
</div>
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
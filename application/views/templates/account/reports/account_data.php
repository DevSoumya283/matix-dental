<div class="row">
    <div class="col-md-3 col-xs-12">
        <div class="report__panel panel--stat">
            <div class="stat__group">
                <span class="stat__title">
                    Total Spent
                </span>
                <span class="stat__value truncate total_spent">
                    <?php
                    if ($total_spent != null) {
                        echo "$" . number_format(floatval($total_spent), 2);
                    }
                    ?>
                </span> 
            </div>
        </div>
    </div>
     <div class="col-md-3 col-xs-12">
        <div class="report__panel panel--stat">
            <div class="stat__group">
                <span class="stat__title">
                    Top Spender
                </span>
                <span class="stat__value truncate total_spender">
                    <?php
                    if ($top_location != null) {
                        echo $top_location;
                    } else {
                        echo '-';
                    }
                    ?>
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-xs-12">
        <div class="report__panel panel--stat">
            <div class="stat__group">
                <span class="stat__title">
                    Favorite Vendor
                </span>
                <span class="stat__value truncate favorite_vendor">
                    <?php
                    if ($top_vendor != null) {
                        echo $top_vendor;
                    } else {
                        echo '-';
                    }
                    ?>
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-xs-12">
        <div class="report__panel panel--stat">
            <div class="stat__group">
                <span class="stat__title">
                    Top Spend (YTD)
                </span>
                <span class="stat__value truncate monthName">
                    <?php
                    if ($top_month != null) {
                        $dateObj = DateTime::createFromFormat('!m', $top_month);
                        echo $dateObj->format('F');
                    } else {
                        echo '-';
                    }
                    ?>
                </span>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-xs-12">
        <div class="report__panel">
            <h4 class="panel__title">Spending by Location</h4>
            <div id="test" class="panel__content">
                <canvas id="chartSpendByLocation" class="chart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xs-12">
        <div class="report__panel">
            <h4 class="panel__title">Spending by Category</h4>
            <div class="panel__content">
                <canvas id="chartSpendByCat" class="chart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="report__panel">
            <h4 class="panel__title">Spending by Month</h4>
            <div class="panel__content">
                <canvas id="chartSpendByMonth" class="chart"></canvas>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="report__panel">
            <h4 class="panel__title">Spending by Vendor</h4>
            <div class="panel__content">
                <canvas id="chartSpendByVendor" class="chart"></canvas>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    //Spend by Location
    var locationSpend = document.getElementById("chartSpendByLocation");
    var chartSpendByLocation = new Chart(locationSpend, {
    animationEnabled: true,
            type: 'bar',
            data: {
            labels: [<?php if ($spending_by_location != null) { ?>
    <?php for ($i = 0; $i < count($spending_by_location); $i++) { ?>
        <?php echo ($i > 0) ? "," : "" ?>
                    "<?php echo $spending_by_location[$i]->nickname ?>"
    <?php } ?>
<?php } else { ?>
<?php } ?>],
                    datasets: [{
                    data:  [<?php if ($spending_by_location != null) { ?>
    <?php for ($i = 0; $i < count($spending_by_location); $i++) { ?>
        <?php echo ($i > 0) ? "," : "" ?>
                            "<?php echo $spending_by_location[$i]->total_price ?>"
    <?php } ?>
<?php } else { ?>
<?php } ?>],
                            backgroundColor: [
                                    'rgba(255, 99, 132, 0.2)',
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(255, 206, 86, 0.2)',
                                    'rgba(75, 192, 192, 0.2)',
                                    'rgba(153, 102, 255, 0.2)',
                                    'rgba(255, 159, 64, 0.2)'
                            ],
                            borderColor: [
                                    'rgba(255,99,132,1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)',
                                    'rgba(255, 159, 64, 1)'
                            ],
                            borderWidth: 1
                    }]
            },
            options: {
            legend: false,
                    scales: {
                    yAxes: [{
                    ticks: {
                    beginAtZero:true
                    }
                    }]
                    }
            }
    });
<?php
$classic_name = [];
$classic_amount = [];
?>
<?php
for ($i = 0; $i < count($classics_graphs); $i++) {
    if ($classics_graphs[$i]->amount != null) {
        $classic_name[] = $classics_graphs[$i]->name;
        $classic_amount[] = $classics_graphs[$i]->amount;
    }
}
?>
    // Spend by Category
    var catSpend = document.getElementById("chartSpendByCat");
    var chartSpendByCat = new Chart(catSpend, {
    type: 'doughnut',
            data: {
            labels: <?php echo json_encode($classic_name); ?>,
                    datasets: [{
                    data:<?php echo json_encode($classic_amount); ?>,
                            backgroundColor: [
                                    'rgba(255, 99, 132, 0.2)',
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(255, 206, 86, 0.2)',
                                    'rgba(75, 192, 192, 0.2)',
                                    'rgba(153, 102, 255, 0.2)',
                                    'rgba(255, 159, 64, 0.2)'
                            ],
                            borderColor: [
                                    'rgba(255,99,132,1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)',
                                    'rgba(255, 159, 64, 1)'
                            ],
                            borderWidth: 1
                    }]
            }
    });
// Spend by Month
    var monthSpend = document.getElementById("chartSpendByMonth");
    var chartSpendByMonth = new Chart(monthSpend, {
    type: 'line',
            data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                    label: <?php echo $previous_year_label; ?>,
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
                            data: [
<?php for ($i = 0; $i < count($previous_year); $i++) { ?>
    <?php echo ($i > 0) ? "," : "" ?>
                                "<?php echo $previous_year[$i] ?>"
<?php } ?>
                            ],
                            spanGaps: false,
                    },
                    {
                    label: "<?php echo $current_year_label; ?>",
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
                            data: [
<?php for ($i = 0; $i < count($current_year); $i++) { ?>
    <?php echo ($i > 0) ? "," : "" ?>
                                "<?php echo $current_year[$i] ?>"
<?php } ?>
                            ],
                            spanGaps: false,
                    }]
            }
    });
<?php
$vendors_name = [];
$vendors_total = [];

for ($i = 0; $i < count($spending_vendors); $i++) {
    $vendors_name[] = str_replace(',', '', $spending_vendors[$i]->vendor_name);
    $vendors_total[] = str_replace('"', '', floatval($spending_vendors[$i]->total_price));
}
?>

// Spend by Vendor
    var vendorSpend = document.getElementById("chartSpendByVendor");
    var chartSpendByVendor = new Chart(vendorSpend, {
    type: 'bar',
            data: {
            labels:<?php echo $vendorencode = json_encode($vendors_name); ?>,
                    datasets: [{
                    data: <?php echo json_encode($vendors_total); ?>,
                            backgroundColor: [
                                    'rgba(255, 99, 132, 0.2)',
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(255, 206, 86, 0.2)',
                                    'rgba(75, 192, 192, 0.2)',
                                    'rgba(153, 102, 255, 0.2)',
                                    'rgba(255, 159, 64, 0.2)'
                            ],
                            borderColor: [
                                    'rgba(255,99,132,1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)',
                                    'rgba(255, 159, 64, 1)'
                            ],
                            borderWidth: 1
                    }]
            },
            options: {
            legend: false,
                    scales: {
                    yAxes: [{
                    ticks: {
                    beginAtZero:true
                    }
                    }]
                    }
            }
    });

</script>
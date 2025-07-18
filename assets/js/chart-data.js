//// Spend by Location
//var locationSpend = document.getElementById("chartSpendByLocation");
//var chartSpendByLocation = new Chart(locationSpend, {
//    animationEnabled: true,
//    type: 'bar',
//    data: {
//        labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
//        datasets: [{
//            data: [18, 19, 3, 5, 2, 3],
//            backgroundColor: [
//                'rgba(255, 99, 132, 0.2)',
//                'rgba(54, 162, 235, 0.2)',
//                'rgba(255, 206, 86, 0.2)',
//                'rgba(75, 192, 192, 0.2)',
//                'rgba(153, 102, 255, 0.2)',
//                'rgba(255, 159, 64, 0.2)'
//            ],
//            borderColor: [
//                'rgba(255,99,132,1)',
//                'rgba(54, 162, 235, 1)',
//                'rgba(255, 206, 86, 1)',
//                'rgba(75, 192, 192, 1)',
//                'rgba(153, 102, 255, 1)',
//                'rgba(255, 159, 64, 1)'
//            ],
//            borderWidth: 1
//        }]
//    },
//    options: {
//        legend: false,
//        scales: {
//            yAxes: [{
//                ticks: {
//                    beginAtZero:true
//                }
//            }]
//        }
//    }
//});
//
//// Spend by Category
//var catSpend = document.getElementById("chartSpendByCat");
//var chartSpendByCat = new Chart(catSpend, {
//    type: 'doughnut',
//    data: {
//        labels: ['Trays', 'Burs', 'Flouride', 'Dentures'],
//        datasets: [{
//            data: [12, 19, 3, 5],
//            backgroundColor: [
//                'rgba(255, 99, 132, 0.2)',
//                'rgba(54, 162, 235, 0.2)',
//                'rgba(255, 206, 86, 0.2)',
//                'rgba(75, 192, 192, 0.2)',
//                'rgba(153, 102, 255, 0.2)',
//                'rgba(255, 159, 64, 0.2)'
//            ],
//            borderColor: [
//                'rgba(255,99,132,1)',
//                'rgba(54, 162, 235, 1)',
//                'rgba(255, 206, 86, 1)',
//                'rgba(75, 192, 192, 1)',
//                'rgba(153, 102, 255, 1)',
//                'rgba(255, 159, 64, 1)'
//            ],
//            borderWidth: 1
//        }]
//    }
//});
//
//// Spend by Month
//var monthSpend = document.getElementById("chartSpendByMonth");
//var chartSpendByMonth = new Chart(monthSpend, {
//    type: 'line',
//    data: {
//        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
//        datasets: [{
//            label: "2015",
//            fill: true,
//            lineTension: 0.0,
//            backgroundColor: 'rgba(255, 99, 132, 0.2)',
//            borderColor: 'rgba(255,99,132,1)',
//            borderCapStyle: 'butt',
//            borderWidth: 2,
//            borderDash: [5,5],
//            borderDashOffset: 0.0,
//            borderJoinStyle: 'miter',
//            pointBorderColor: 'rgba(255,99,132,1)',
//            pointBackgroundColor: "#fff",
//            pointBorderWidth: 4,
//            pointHoverRadius: 5,
//            pointHoverBackgroundColor: "rgba(255,255,255,1)",
//            pointHoverBorderColor: 'rgba(255,99,132,1)',
//            pointHoverBorderWidth: 2,
//            pointRadius: 1,
//            pointHitRadius: 10,
//            data: [1359, 1118, 1675, 1426, 1487, 1671, 721, 743, 1982, 1189, 1433, 1013],
//            spanGaps: false,
//        },
//        {
//            label: "2016",
//            fill: true,
//            lineTension: 0.0,
//            backgroundColor: 'rgba(54, 162, 235, 0.2)',
//            borderColor: 'rgba(54, 162, 235, 1)',
//            borderCapStyle: 'butt',
//            borderWidth: 2,
//            borderDash: [],
//            borderDashOffset: 0.0,
//            borderJoinStyle: 'miter',
//            pointBorderColor: 'rgba(54, 162, 235, 1)',
//            pointBackgroundColor: "#fff",
//            pointBorderWidth: 4,
//            pointHoverRadius: 5,
//            pointHoverBackgroundColor: 'rgba(255, 255, 255, 1)',
//            pointHoverBorderColor: 'rgba(54, 162, 235, 1)',
//            pointHoverBorderWidth: 2,
//            pointRadius: 1,
//            pointHitRadius: 10,
//            data: [536, 677, 847, 1255, 1234, 967, 1364, 1201, 1106, 1340],
//            spanGaps: false,
//        }]
//    }
//});
//
//// Spend by Vendor
//var vendorSpend = document.getElementById("chartSpendByVendor");
//var chartSpendByVendor = new Chart(vendorSpend, {
//    type: 'bar',
//    data: {
//        labels: ["Star Dental Supply, Inc.", "Discount Dental", "Harry Health", "Henry Schein", "DentalDams.com", "DDS Supply Inc."],
//        datasets: [{
//            data: [1201, 1990, 965, 1533, 2467, 499],
//            backgroundColor: [
//                'rgba(255, 99, 132, 0.2)',
//                'rgba(54, 162, 235, 0.2)',
//                'rgba(255, 206, 86, 0.2)',
//                'rgba(75, 192, 192, 0.2)',
//                'rgba(153, 102, 255, 0.2)',
//                'rgba(255, 159, 64, 0.2)'
//            ],
//            borderColor: [
//                'rgba(255,99,132,1)',
//                'rgba(54, 162, 235, 1)',
//                'rgba(255, 206, 86, 1)',
//                'rgba(75, 192, 192, 1)',
//                'rgba(153, 102, 255, 1)',
//                'rgba(255, 159, 64, 1)'
//            ],
//            borderWidth: 1
//        }]
//    },
//    options: {
//        legend: false,
//        scales: {
//            yAxes: [{
//                ticks: {
//                    beginAtZero:true
//                }
//            }]
//        }
//    }
//});
//
//// Products Comparison
//var products = document.getElementById("chartProductsComparison");
//var chartProductsCompared = new Chart(products, {
//    type: 'line',
//    data: {
//        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
//        datasets: [{
//            label: "3-Way Disposable Impression Trays by Sultan Healthcare",
//            fill: true,
//            lineTension: 0.0,
//            backgroundColor: 'rgba(255, 99, 132, 0.2)',
//            borderColor: 'rgba(255,99,132,1)',
//            borderCapStyle: 'butt',
//            borderWidth: 2,
//            borderDash: [5,5],
//            borderDashOffset: 0.0,
//            borderJoinStyle: 'miter',
//            pointBorderColor: 'rgba(255,99,132,1)',
//            pointBackgroundColor: "#fff",
//            pointBorderWidth: 4,
//            pointHoverRadius: 5,
//            pointHoverBackgroundColor: "rgba(255,255,255,1)",
//            pointHoverBorderColor: 'rgba(255,99,132,1)',
//            pointHoverBorderWidth: 2,
//            pointRadius: 1,
//            pointHitRadius: 10,
//            data: [1359, 1118, 1675, 1426, 1487, 1671, 721, 743, 1982, 1189, 1433, 1013],
//            spanGaps: false,
//        },
//        {
//            label: "3-Way Disposable Impression Trays by Star Dental Supply, Inc.",
//            fill: true,
//            lineTension: 0.0,
//            backgroundColor: 'rgba(54, 162, 235, 0.2)',
//            borderColor: 'rgba(54, 162, 235, 1)',
//            borderCapStyle: 'butt',
//            borderWidth: 2,
//            borderDash: [],
//            borderDashOffset: 0.0,
//            borderJoinStyle: 'miter',
//            pointBorderColor: 'rgba(54, 162, 235, 1)',
//            pointBackgroundColor: "#fff",
//            pointBorderWidth: 4,
//            pointHoverRadius: 5,
//            pointHoverBackgroundColor: 'rgba(255, 255, 255, 1)',
//            pointHoverBorderColor: 'rgba(54, 162, 235, 1)',
//            pointHoverBorderWidth: 2,
//            pointRadius: 1,
//            pointHitRadius: 10,
//            data: [536, 677, 847, 1255, 1234, 967, 1364, 1201, 1106, 1340],
//            spanGaps: false,
//        }]
//    }
//});

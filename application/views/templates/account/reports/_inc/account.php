<?php include(INCLUDE_PATH . '/_inc/shared/modals/products-comparison.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/config-report.php'); ?>
<!-- Charts -->
<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.min.js"></script>
<div id="reportAccount" class="page__tab">
    <!-- Heading -->
    <div class="heading__group border--dashed padding--s no--pad-lr no--pad-t wrapper">
        <div class="wrapper__inner">
            <h4>Account Snapshot</h4>
        </div>
        <div id="controlsRequests" class="contextual__controls wrapper__inner align--right">
            <ul class="list list--inline fontWeight--2 fontSize--s disp--ib">
                <li class="item">
                    <a class="link print_account_snapshot_report">Print</a>
                </li>
            </ul>
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
                    <input type="text" class="input input--date start_acc" placeholder="MM/DD/YYYY" name="start">
                    <input type="text" class="input input--date end_acc" placeholder="MM/DD/YYYY" name="end">
                </div>
            </div>
        </div>
    </div>
    <!-- /Filters -->
    <!-- Report -->
    <div class="report account_report">
     <?php $this->load->view('templates/account/reports/account_data.php'); ?>
    </div>
    <!-- /Report -->
</div>



<script type="text/javascript">

    $(document).ready(function () {
        $(".end_acc").change(function () {
            reload_snapshot();
        });
        $(".start_acc").change(function () {
            reload_snapshot();
        });

        function reload_snapshot() {
            $start_date = $(".start_acc").val();
            $end_date = $(".end_acc").val();
            $.ajax({
                type: "POST",
                url: base_url + "view-account",
                data: {start_date: $start_date, end_date: $end_date},
                success: function (data) {
                    $(".account_report").html(data);
                }
            });
        }
    });
</script>

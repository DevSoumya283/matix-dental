<div id="reportOrder" class="page__tab">
    <div class="row margin--m no--margin-r no--margin-t">
        <div class="col-md-3 col-xs-12">
            <div class="report__panel panel--stat">
                <div class="stat__group">
                    <span class="stat__title">
                        Total Orders
                    </span>
                    <span class="stat__value truncate Ocount">
                        <?php echo $total_orders ?>
                    </span>
                </div>
            </div>
        </div>
         <div class="col-md-3 col-xs-12">
            <div class="report__panel panel--stat">
                <div class="stat__group">
                    <span class="stat__title">
                        Purchased From
                    </span>
                    <span class="stat__value truncate purchaseFrom">
                        <?php echo $total_vendors; ?> Vendors
                    </span>
                </div>
            </div>
        </div>
         <div class="col-md-3 col-xs-12">
            <div class="report__panel panel--stat">
                <div class="stat__group">
                    <span class="stat__title">
                        Top Vendor
                    </span>
                    <span class="stat__value truncate nickName">
                        <?php
                        if ($top_vendor != null) {
                            echo $top_vendor;
                        } else {
                            echo '-';
                        }
                        ?>                        </span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-xs-12">
            <div class="report__panel panel--stat">
                <div class="stat__group">
                    <span class="stat__title">
                        Total Spent
                    </span>
                    <span class="stat__value truncate tSpent">
                        <?php
                        if ($total_spent != null) {
                            echo "$" . number_format(floatval($total_spent), 2);
                         }
                        ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <!-- /Totals -->
    <table class="table sortable-theme-light table-responsive" data-sortable id="exports_ordercsv">
        <thead>
            <tr>
                <th width="20%">
                    Order
                </th>
                <th width="25%">
                    Vendor
                </th>
                <th width="15%">
                    Shipped To
                </th>
                <th width="15%">
                    Order Date
                </th>
                <th width="20%">
                    Total
                </th>
            </tr>
        </thead>
        <tbody class="oList">
            <?php for ($i = 0; $i < count($orders_result); $i++) { ?>
                <!-- Requested Item -->
                <tr class="oListItems">
                    <td>
                        <span class="fontWeight--2 id"><?php echo $orders_result[$i]->order_id; ?></span>
                    </td>
                    <td>
                        <span class="fontWeight--2 name"><?php echo $orders_result[$i]->vendor_name; ?></span>
                    </td>
                    <td>
                        <span class="fontWeight--2 nickname"><?php echo $orders_result[$i]->location_name; ?></span>
                    </td>
                    <td>
                        <span class="fontWeight--2 order_at"><?php echo date('M d, Y', strtotime($orders_result[$i]->order_created_at)); ?></span>
                    </td>
                    <td>
                        <span class="fontWeight--2 order_total"><?php echo "$" . number_format(floatval($orders_result[$i]->total_price), 2); ?></span>
                    </td>
                </tr>
            <?php } ?>
            <!-- /Requested Item -->
        </tbody>
    </table>
</div>
</div>

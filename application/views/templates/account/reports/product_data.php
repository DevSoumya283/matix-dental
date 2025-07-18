<!-- Totals -->
<div class="row margin--m no--margin-r no--margin-t">
   <div class="col-md-3 col-xs-12">
        <div class="report__panel panel--stat">
            <div class="stat__group">
                <span class="stat__title">
                    Total Items
                </span>
                <span class="stat__value truncate total_purchase_item">
                    <?php echo $total_purchase_item; ?>
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-xs-12">
        <div class="report__panel panel--stat">
            <div class="stat__group">
                <span class="stat__title">
                    Top Category
                </span>
                <span class="stat__value truncate highest_cat">
                    <?php echo $top_category; ?>
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
                <span class="stat__value truncate purchase_top_vendor">
                    <?php echo $top_vendor; ?>
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-xs-12">
        <div class="report__panel panel--stat">
            <div class="stat__group">
                <span class="stat__title">
                    Total Spent
                </span>
                <span class="stat__value truncate purchase_total_spent">
                    $<?php echo number_format(floatval($total_spent), 2); ?>
                </span>
            </div>
        </div>
    </div>
</div>
<!-- /Totals -->
<table class="table table-responsive" id="exports_ordercsv2">
    <thead>
        <tr>
            <th width="5%">
            </th>
            <th width="40%">Product
            </th>
            <th width="17%">Category
            </th>
            <th width="10%">Qty
            </th>
            <th width="13%">Avg Price
            </th>
            <th width="15%">Total
            </th>
        </tr>
    </thead>
    <tbody class="purchaseList">

        <?php for ($i = 0; $i < count($products_result); $i++) { ?>                        
            <!-- Requested Item -->
            <tr class="purchaseitemss">
                <td>
                    <label class="control control__checkbox">
                        <input type="checkbox" name="checkboxRow" value="<?php echo $products_result[$i]->product_id ?>" class="single-checkbox">
                        <div class="control__indicator"></div>
                    </label>
                </td>
                <?php if (strtolower($products_result[$i]->license_required) == 'yes') { ?>
                    <td>
                        <!-- Product -->
                        <div class="product product--s row multi--vendor req--license padding--xxs">
                            <div class="product__data col col--8-of-8 col--am">
                                <span class="product__name"><?php echo $products_result[$i]->name ?></span>
                                <span class="product__mfr">
                                    by <a class="link fontWeight--2" href="#"><?php echo $products_result[$i]->manufacturer; ?></a>
                                </span>
                                <span class="fontSize--s fontWeight--2">Vendor:</span>
                                <span class="fontSize--s"><?php echo $products_result[$i]->vendor_name; ?></span>
                            </div>
                        </div>
                        <!-- /Product -->
                    </td>
                <?php } else { ?>
                    <td>
                        <!-- Product -->
                        <div class="product product--s row multi--vendor padding--xxs">
                            <div class="product__data col col--8-of-8 col--am">
                                <span class="product__name"><?php echo $products_result[$i]->name ?></span>
                                <span class="product__mfr">
                                    by <a class="link fontWeight--2" href="#"><?php echo $products_result[$i]->manufacturer; ?></a>
                                </span>
                                <span class="fontSize--s fontWeight--2">Vendor:</span>
                                <span class="fontSize--s"><?php echo $products_result[$i]->vendor_name; ?></span>
                            </div>
                        </div>
                        <!-- /Product -->
                    </td>
                <?php } ?>
                <td>
                    <?php echo $products_result[$i]->category_name ?>
                    <br/>
                    <em><?php
                        if ($products_result[$i]->product_procedures != null) {
                            echo "(" . $products_result[$i]->product_procedures . ")";
                        }
                        ?></em>
                </td>
                <td>
                    <?php echo $products_result[$i]->total_quantity; ?>
                </td>
                <td>
                    <?php if ($products_result[$i]->total_quantity > 0) { ?>
                        $<?php echo number_format(($products_result[$i]->total_price / $products_result[$i]->total_quantity), 2, ".", ""); ?>
                    <?php } else { ?>
                        -
                    <?php } ?>
                </td>
                <td>
                    $<?php echo number_format(floatval($products_result[$i]->total_price), 2); ?>
                </td>
            </tr>

        <?php } ?>
        <!-- /Requested Item -->
    </tbody>
</table>

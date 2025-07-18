<!-- Totals -->
<div class="row margin--m no--margin-r no--margin-t">
    <div class="col-md-6 col-xs-12">
        <div class="report__panel panel--stat">
            <div class="stat__group">
                <span class="stat__title">
                    Total Taxable Items
                </span>
                <span class="stat__value truncate total_tax_item">
                    <?php echo $total_taxable_items; ?>
                </span>
            </div>
        </div>
    </div>
     <div class="col-md-6 col-xs-12">
        <div class="report__panel panel--stat">
            <div class="stat__group">
                <span class="stat__title">
                    Total Tax Spent
                </span>
                <span class="stat__value truncate tax_total_spent">
                    $<?php echo number_format(floatval($total_tax), 2); ?>
                </span>
            </div>
        </div>
    </div>
</div>
<!-- /Totals -->
<table class="table table-responsive" id="exports_ordercsv3">
    <thead>
        <tr>
            <th width="5%">
                <label class="control control__checkbox">
                    <input type="checkbox" class=" is--selector">
                    <div class="control__indicator"></div>
                </label>
            </th>
            <th width="40%">Product
            </th>
            <th width="20%">Category
            </th>
            <th width="20%">Tax %
            </th>
            <th width="20%">Total
            </th>
        </tr>
    </thead>
    <tbody class="taxList">
        <?php
        for ($i = 0; $i < count($tax_result); $i++) {
            if ($tax_result[$i]->item_tax != 0) {
                ?>
                <!-- Requested Item -->
                <tr class="taxitemss">
                    <td>
                        <label class="control control__checkbox">
                            <input type="checkbox" name="checkboxRow" value="<?php echo $tax_result[$i]->product_id ?>" class="single-checkbox2">
                            <div class="control__indicator"></div>
                        </label>
                    </td>
                    <td>
                        <!-- Product -->
                        <?php if ($tax_result[$i]->license_required == 'Yes') { ?>
                            <div class="product product--s row multi--vendor req--license padding--xxs">
                            <?php } else { ?>
                                <div class="product product--s row multi--vendor  padding--xxs">
                                <?php } ?>
                                <div class="product__data col col--8-of-8 col--am">
                                    <span class="product__name"><?php echo $tax_result[$i]->name ?></span>
                                    <span class="product__mfr">
                                        by <a class="link fontWeight--2" href="#"><?php echo $tax_result[$i]->manufacturer; ?></a>
                                    </span>
                                    <span class="fontSize--s fontWeight--2">Vendor:</span>
                                    <span class="fontSize--s"><?php echo $tax_result[$i]->vendor_name; ?></span>
                                </div>
                            </div>
                            <!-- /Product -->
                    </td>
                    <td>
                        <?php echo $tax_result[$i]->category_name ?>
                        <br/>
                        <em><?php
                            if ($tax_result[$i]->product_procedures != null) {
                                echo "(" . $tax_result[$i]->product_procedures . ")";
                            }
                            ?></em>
                    </td>
                    <td>
                        <?php echo number_format(floatval(($tax_result[$i]->item_tax * 100) / $tax_result[$i]->total_price), 2); ?>%
                    </td>
                    <td>
                        $<?php echo number_format(floatval($tax_result[$i]->item_tax), 2); ?>
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
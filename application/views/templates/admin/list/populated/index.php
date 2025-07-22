<?php include(INCLUDE_PATH . '/_inc/header-admin.php'); ?>

<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <section class="content__wrapper has--sidebar-l">
        <div class="content__main">
            <div class="row row--full-height">

                <!-- Sidebar -->
                <div class="sidebar col col--2-of-12" style="padding:12px">
                    <?php //include(INCLUDE_PATH . '/admin/_inc/nav.php'); ?>
                    <?php $this->load->view('templates/admin/_inc/nav.php'); ?>
                </div>
                <!-- /Sidebar -->

                <!-- Content Area -->
                <div class="content col col--9-of-12 col--push-1-of-12">
                    <!-- Customers -->
                    <div class="heading__group border--dashed">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <?php if ($list_detail != null) { ?>
                                    <h3><?php echo $list_detail->listname; ?> (<?php echo ($list_count != null) ? $list_count : "0"; ?>)</h3>
                                <?php } else { ?>
                                    <h3>PrePopulated List (<?php echo ($list_count != null) ? $list_count : "0"; ?>)</h3>
                                <?php } ?>
                            </div>
                            <div id="controlsTable" class="contextual__controls wrapper__inner align--right">
                                <ul class="list list--inline list--divided fontWeight--2 fontSize--s ">
                                    <li class="item">
                                        <button class="btn btn--primary btn--m contextual--hide modal--toggle" data-target="#editNewListModal">Rename List</button>
                                    </li>
                                    <li class="item">
                                        <a class="link is--neg modal--toggle contextual--hide " data-target="#deleteNewListModal">Delete List</a>
                                    </li>
                                </ul>
                                <?php if ($prepopulated_products != null) { ?>
                                    <ul class="list list--inline list--divided fontWeight--2 fontSize--s is--contextual is--off">
                                        <li class="item">
                                            <button class="btn btn--primary btn--m modal--toggle" data-target="#editNewListModal">Rename List</button>
                                        </li>
                                        <li class="item">
                                            <a class="link is--neg modal--toggle" data-target="#deleteNewListModal">Delete List</a>
                                        </li>
                                        <li class="item">
                                            <a class="link is--neg modal--toggle" data-target="#deleteListProductModal">Remove Product(s)</a>
                                        </li>
                                    </ul>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div style="overflow: hidden; overflow-x: scroll;">
                        <table class="table" data-controls="#controlsTable">
                        <thead>
                            <tr>
                                <th width="3%">
                                    <label class="control control__checkbox">
                                        <input type="checkbox" class="is--selector hidedeleteList" id="selectAll" value="">
                                        <div class="control__indicator"></div>
                                    </label>
                                </th>
                                <th>
                                    ID
                                </th>
                                <th>
                                    Product
                                </th>
                                <th>
                                    Price(Avg)
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Single Return -->
                            <?php if ($prepopulated_products != null) { ?>
                                <?php foreach ($prepopulated_products as $products) { ?>
                                    <tr>
                                        <td>
                                            <label class="control control__checkbox">
                                                <input type="checkbox" name="checkboxRow" class="singleCheckbox" value="<?php echo $products->id; ?>">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </td>
                                        <td class="fontWeight--2">
                                            <a class="link fontWeight--2" href="<?php echo base_url(); ?>SPadmin-products?product_id=<?php echo $products->product_id; ?>"><?php echo $products->mpn; ?></a>
                                        </td>
                                        <td>
                                            <?php echo $products->name; ?>
                                        </td>
                                        <td>
                                            <?php echo "$" . number_format($products->average, 2, ".", ""); ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="5">
                                        No product(s) found
                                    </td>
                                </tr>
                            <?php } ?>
                            <!-- Single Return -->
                        </tbody>
                    </table>
                    <!-- /Customers -->
                    <?php echo $this->pagination->create_links(); ?>
                    </div>
                </div>
                <!-- /Content Area -->

            </div>
        </div>
    </section>
</div>
<!-- /Content Section -->

<!-- Modals -->
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/edit-new-list.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/delete-new-list.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/delete-new-listProduct.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/footer-admin.php'); ?>

<?php $this->load->view('templates/_inc/shared/modals/edit-new-list.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/delete-new-list.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/delete-new-listProduct.php'); ?>
<?php $this->load->view('templates/_inc/footer-admin.php'); ?>

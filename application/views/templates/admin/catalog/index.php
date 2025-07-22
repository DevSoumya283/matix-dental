<!-- Content Section -->
<?php if ($this->session->flashdata('message')) { ?>
    <div align="center" class="alert alert-success">      
        <?php echo $this->session->flashdata('message') ?>
    </div>
<?php } ?>
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <section class="content__wrapper has--sidebar-l">
        <div class="content__main">
            <div class="row row--full-height">

                <!-- Sidebar -->
                <div class="sidebar col col--2-of-12" style="padding: 12px;" >
                    <?php //include(INCLUDE_PATH . '/admin/_inc/nav.php'); ?>
                    <?php $this->load->view('templates/admin/_inc/nav.php'); ?>
                </div>
                <!-- /Sidebar -->

                <!-- Content Area -->
                <div class="content col col--9-of-12 col--push-1-of-12">

                    <div class="heading__group border--dashed">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <h3>Products (<?php echo ($total_count != null) ? $total_count : ""; ?>)</h3>
                            </div>
                            <div class="wrapper__inner">
                                <form method="get" action="<?php echo base_url(); ?>product-catalog">
                                    <div class="input__group input__group--inline">
                                        <input id="site-search" class="input input__text" type="search" value="" placeholder="Search by product ID, vendor, mfr, description, etc..." name="search" required>
                                        <label for="site-search" class="label">
                                            <svg class="icon icon--search textColor--gray"><use xlink:href="#icon-search"></use></svg>
                                        </label>
                                    </div>
                                </form>
                            </div>
                            <div class="wrapper__inner align--right">
                                <button class="btn btn--primary btn--m modal--toggle add_vendor" data-target="#uploadCatalogModal">Upload New</button>
                                <form method="post" action="<?php echo base_url(); ?>Backup-Products" style="display: inline;">
                                    <button class="btn btn--tertiary btn--m">Export Backup</button>
                                </form>
                                <button class="btn btn--tertiary btn--m contextual--hide modal--toggle" data-target="#bulkUploadModal">Bulk Images</button>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="heading__group border--dashed">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <button class="btn btn--tertiary btn--s btn--icon margin--s no--margin-tb no--margin-l has--tip" data-tip="Configure Filters" data-tip-position="right"><svg class="icon icon--settings"><use xlink:href="#icon-settings"></use></svg></button>
                                <ul class="list list--inline list--filters disp--ib">
                                    <li class="item item--filter">
                                        Showing All Products
                                        <!-- <a class="filter--clear" href="#"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a> -->
                                    </li>
                                </ul>
                            </div>
                            <div id="controlsCatalog" class="contextual__controls wrapper__inner align--right">
                                <?php if ($products != null) { ?>
                                    <ul class="list list--inline list--divided">
                                        <li class="item">
                                            <a class="link modal--toggle is--contextual is--off" data-target="#newPrepopulatedListAdminModal">Create New List</a>
                                        </li>
                                        <li class="item">
                                            <a class="link modal--toggle is--contextual is--off" data-target="#existingListsModal">Add to Existing List(s)</a>
                                        </li>
                                        <li class="item">
                                            <a class="link fontWeight--2 fontSize--s modal--toggle is--neg is--contextual is--off" data-target="#deleteProductModal">Delete Product</a>
                                        </li>
                                    </ul>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <!-- /Filters -->

                    <!-- Product List -->
                        <div style="overflow:scroll;"> 
                    <table class="table" data-controls="#controlsCatalog">
                        <thead>
                            <tr>
                                <th width="3%">
                                    <label class="control control__checkbox">
                                        <input type="checkbox" class=" is--selector" id="selectAll">
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
                                    Vendors
                                </th>
                                <th>
                                    Price (Low)
                                </th>
                                <th>
                                    Price (Avg)
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Single Product -->
                            <?php if ($products != null) { ?>
                                <?php foreach ($products as $active) { ?>
                                    <tr>
                                        <td>
                                            <label class="control control__checkbox">
                                                <input type="checkbox" name="checkboxRow" class="singleCheckbox" value="<?php echo $active->id; ?>">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </td>
                                        <td class="fontWeight--2 truncate">
                                            <a class="link" href="<?php base_url(); ?>SPadmin-products?product_id=<?php echo $active->id; ?>">
                                                <?php echo $active->mpn; ?>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="link" href="<?php base_url(); ?>SPadmin-products?product_id=<?php echo $active->id; ?>">
                                                <?php echo $active->name; ?>
                                            </a>
                                        </td>
                                        <td>
                                            <ul class="list list--truncated">
                                                <?php
                                                $vendor_name = $active->vendor_name;
                                                for ($i = 0; $i < count($vendor_name); $i++) {
                                                    $vendor_id = substr($vendor_name[$i], 0, strpos($vendor_name[$i], '-'));
                                                    $vendor = substr($vendor_name[$i], strpos($vendor_name[$i], '-') + 1);
                                                    ?>
                                                    <li class="item">
                                                        <a class="link" href="<?php echo base_url(); ?>vendors-sales-report?vendor_id=<?php echo $vendor_id; ?>"><?php echo $vendor; ?></a>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </td>
                                        <td>
                                            $<?php echo($active->low_price == 10000000) ? "0.00" : number_format(floatval($active->low_price), 2); ?>
                                        </td>
                                        <td>
                                            $<?php echo ($active->avg_price != null) ? number_format(floatval($active->avg_price), 2) : "0.00"; ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="5">
                                        No results found
                                    </td>
                                </tr>
                            <?php } ?>
                            <!-- /Single Product -->
                        </tbody>
                    </table>
                     </div>
                    <?php echo $this->pagination->create_links(); ?>
                    <!-- /Product List -->
                     </div>

                </div>
                <!-- /Content Area -->
            </div>
        </div>
    </section>
</div>
<!-- /Content Section -->

<!-- Modals -->
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/new-list-admin.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/existing-lists.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/delete-product.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/bulk-image-upload.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/upload-catalog.php'); ?>

<?php $this->load->view('templates/_inc/shared/modals/new-list-admin.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/existing-lists.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/delete-product.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/bulk-image-upload.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/upload-catalog.php'); ?>


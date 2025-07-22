<?php ini_set('display_errors', 'Off'); ?>
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
                    <!-- Vendors List -->
                    <div class="heading__group border--dashed">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <h3>Vendors</h3>
                            </div>
                            <div class="wrapper__inner">
                                <form action="<?php echo base_url(); ?>vendor-search" method="get">
                                    <div class="input__group input__group--inline">
                                        <input id="vendor_search" class="input input__text" type="search" placeholder="Search by vendor name..." name="search" required>
                                        <label for="site-search" class="label">
                                            <svg class="icon icon--search textColor--gray"><use xlink:href="#icon-search"></use></svg>
                                        </label>
                                    </div>
                                </form>
                            </div>
                            <div id="controlsVendors" class="contextual__controls wrapper__inner align--right">
                                <button class="btn btn--primary btn--m contextual--hide modal--toggle" data-target="#addNewVendorModal">New Vendor</button>
                                <ul class="list list--inline list--divided fontWeight--2 fontSize--s is--contextual is--off">
                                    <li class="item">
                                        <a class="link modal--toggle is--contextual is--off modal--toggle" data-target="#confirmVendorActivationModal">Activate</a>
                                    </li>
                                    <li class="item">
                                        <a class="link modal--toggle is--contextual is--off modal--toggle" data-target="#confirmVendorDeactivationModal">Deactivate</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div style="overflow: hidden; overflow-x: scroll;">
                    <table class="table" data-controls="#controlsVendors">
                        <thead>
                            <tr>
                                <th width="3%">
                                    <label class="control control__checkbox">
                                        <input type="checkbox" class=" is--selector" id="selectAll">
                                        <div class="control__indicator"></div>
                                    </label>
                                </th>
                                <th>
                                    Vendor
                                </th>
                                <th>
                                    Created
                                </th>
                                <th>
                                    Users
                                </th>
                                <th>
                                    Type
                                </th>
                                <th>
                                    Revenue (YTD)
                                </th>
                                <th>
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Single Vendor -->
                            <?php if ($vendors != null) { ?>
                                <?php foreach ($vendors as $active) { ?>
                                    <tr>
                                        <td>
                                            <label class="control control__checkbox">
                                                <input type="checkbox" name="checkboxRow" class="singleCheckbox" value="<?php echo $active->id; ?>">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </td>
                                        <td>
                                            <a class="link fontWeight--2" href="<?php echo base_url(); ?>vendors-sales-report?vendor_id=<?php echo $active->id; ?>"><?php echo $active->name; ?></a>
                                        </td>
                                        <td>
                                            <?php echo date('M d, Y', strtotime($active->created_at)); ?>
                                        </td>
                                        <td>
                                            <?php echo $active->total_users; ?>
                                        </td>
                                        <td>
                                            MFR Authorized
                                        </td>
                                        <td>
                                            <?php echo ($active->total) ? "$" . number_format(floatval($active->total),2) : "$0.00"; ?>
                                        </td>
                                        <td>
                                            <?php echo ($active->active == 1) ? "Active" : "Inactive"; ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="5">
                                        No Vendor(s) Found
                                    </td>
                                </tr>
                            <?php } ?>
                            <!-- Single Vendor -->
                        </tbody>
                    </table>
                    <?php echo $this->pagination->create_links(); ?>
                    </div>
                    <!-- /Vendors List -->
                </div>
                <!-- /Content Area -->

            </div>
        </div>
    </section>
</div>
<!-- /Content Section -->

<!-- Modals -->
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/new-vendor.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/confirm-vendor-activation.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/confirm-vendor-deactivation.php');?>

<?php $this->load->view('templates/_inc/shared/modals/new-vendor.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/confirm-vendor-activation.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/confirm-vendor-deactivation.php'); ?>

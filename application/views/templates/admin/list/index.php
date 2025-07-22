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
                                <h3>Prepopulated Lists</h3>
                            </div>
                            <div id="controlsTable" class="contextual__controls wrapper__inner align--right">
                                <button class="btn btn--primary btn--m  modal--toggle" data-target="#addNewListModal">New List</button>
                                <?php if ($prepopulated_lists != null) { ?>
                                    <ul class="list list--inline list--divided fontWeight--2 fontSize--s is--contextual is--off">
                                        <li class="item">
                                            <a class="link is--neg modal--toggle" data-target="#deleteNewListModal">Delete</a>
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
                                        <input type="checkbox" class="is--selector" id="selectAll" value="">
                                        <div class="control__indicator"></div>
                                    </label>
                                </th>
                                <th>
                                    List
                                </th>
                                <th>
                                    Created
                                </th>
                                <th>
                                    Items
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Single Return -->
                            <?php if ($prepopulated_lists != null) { ?>
                                <?php foreach ($prepopulated_lists as $lists) { ?>
                                    <tr>
                                        <td>
                                            <label class="control control__checkbox">
                                                <input type="checkbox" name="checkboxRow" class="singleCheckbox" value="<?php echo $lists->id; ?>">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </td>
                                        <td class="fontWeight--2">
                                            <a class="link fontWeight--2" href="<?php echo base_url(); ?>prepopulated-lists-detail?list_id=<?php echo $lists->id; ?>"><?php echo $lists->listname; ?></a>
                                        </td>
                                        <td>
                                            <?php echo date('M d, Y', strtotime($lists->created_at)); ?>
                                        </td>
                                        <td>
                                            <?php echo $lists->items; ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="4">
                                        No List's
                                    </td>
                                </tr>    
                            <?php } ?>
                            <!-- Single Return -->
                        </tbody>
                    </table>
                    <!-- /Customers -->
                    </div>
                </div>
                <!-- /Content Area -->

            </div>
        </div>
    </section>
</div>
<!-- /Content Section -->

<!-- Modals -->
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/add-new-list.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/delete-new-list.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/footer-admin.php'); ?>

<?php $this->load->view('templates/_inc/shared/modals/add-new-list.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/delete-new-list.php'); ?>
<?php //$this->load->view('templates/_inc/footer-admin.php'); ?>

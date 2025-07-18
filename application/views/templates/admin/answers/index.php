<?php include(INCLUDE_PATH . '/_inc/header-admin.php'); ?>
<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>
    <section class="content__wrapper has--sidebar-l">
        <div class="content__main">
            <div class="row row--full-height">

                <!-- Sidebar -->
                <div class="sidebar col col--2-of-12">
                    <?php //include(INCLUDE_PATH . '/admin/_inc/nav.php'); ?>
                    <?php $this->load->view('templates/admin/_inc/nav.php'); ?>
                </div>
                <!-- /Sidebar -->

                <!-- Content Area -->
                <div class="content col col--9-of-12 col--push-1-of-12">
                    <!-- Flagged Answers -->
                    <div class="heading__group border--dashed">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <h3>Flagged Answers</h3>
                            </div>
                            <div id="controlsTable" class="contextual__controls wrapper__inner align--right">
                                <?php if ($FlaggedAnswers != null) { ?>
                                    <ul class="list list--inline list--divided fontWeight--2 fontSize--s is--contextual is--off">
                                        <li class="item">
                                            <a class="link modal--toggle" data-target="#unflagItemsModal">Unflag</a>
                                        </li>
                                        <li class="item">
                                            <a class="link is--neg modal--toggle" data-target="#deleteFlaggedItemsModal">Delete</a>
                                        </li>
                                    </ul>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <table class="table" data-controls="#controlsTable">
                        <thead>
                            <tr>
                                <th width="3%">
                                    <label class="control control__checkbox">
                                        <input type="checkbox" class="is--selector" id="selectAll" value="">
                                        <div class="control__indicator"></div>
                                    </label>
                                </th>
                                <th width="40%">
                                    Answers
                                </th>
                                <th>
                                    Author
                                </th>
                                <th>
                                    Flagged By
                                </th>
                                <th>
                                    Product
                                </th>
                                <th>
                                    Date
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            <!-- Flagged Answer -->
                            <?php if ($FlaggedAnswers != null) { ?>
                                <?php foreach ($FlaggedAnswers as $flagged) { ?>
                                    <tr>
                                        <td>
                                            <label class="control control__checkbox">
                                                <input type="checkbox" name="checkboxRow" class="singleCheckbox" value="<?php echo $flagged->id; ?>">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </td>
                                        <td>
                                            <span class="fontWeight--2">Question:</span>
                                            <span class="fontSize--s"><?php echo $flagged->question; ?></span>
                                            <br>
                                            <span class="fontWeight--2">Answer:</span>
                                            <span class="fontSize--s"><?php echo $flagged->answer; ?></span>
                                        </td>
                                        <td class="fontWeight--2">
                                            <!-- NOTE: This should link to the author user's profile in the "customers" tab -->
                                            <?php if ($flagged->vendor_id != null) { ?>
                                                <a class="link" href="<?php echo base_url(); ?>vendors-sales-report?vendor_id=<?php echo $flagged->vendor_id; ?>"><?php echo $flagged->author; ?></a>
                                            <?php } else { ?>
                                                <a class="link" href="<?php echo base_url(); ?>customer-details-page?user_id=<?php echo $flagged->user_id; ?>"><?php echo $flagged->author; ?></a>
                                            <?php } ?>

                                        </td>
                                        <td class="fontWeight--2">
                                            <!-- NOTE: This should link to the user's profile in the "customers" tab -->
                                            <?php if ($flagged->flaggedUser != null && $flagged->flaggedUser != "") { ?>
                                                <?php for ($j = 0; $j < count($flagged->flaggedUser); $j++) { ?>
                                                    <?php if (isset($flagged->flaggedUser[$j]->vendor_id)) { ?>
                                                        <a class="link" href="<?php echo base_url(); ?>vendors-sales-report?vendor_id=<?php print_r($flagged->flaggedUser[$j]->vendor_id); ?>"><?php print_r($flagged->flaggedUser[$j]->first_name); ?></a>
                                                    <?php } else { ?>
                                                        <a class="link" href="<?php echo base_url(); ?>customer-details-page?user_id=<?php print_r($flagged->flaggedUser[$j]->id); ?>"><?php print_r($flagged->flaggedUser[$j]->first_name); ?></a>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php echo $flagged->name; ?>
                                        </td>
                                        <td>
                                            <?php echo date('M d, Y', strtotime($flagged->created_at)); ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <!-- /Flagged Answer -->

                                <!-- Empty -->
                            <?php } else { ?>
                                <tr>
                                    <td colspan="6">
                                        Nothing Flagged
                                    </td>
                                </tr>
                            <?php } ?>
                            <!-- /Empty -->

                        </tbody>
                    </table>
                    <!-- /Flagged Reviews -->
                    <?php echo $this->pagination->create_links(); ?>
                </div>
                <!-- /Content Area -->
            </div>
        </div>
    </section>
</div>
<!-- /Content Section -->

<!-- Modals -->
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/unflag-items.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/delete-flagged-items.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/footer-admin.php'); ?>

<?php $this->load->view('templates/_inc/shared/modals/unflag-items.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/delete-flagged-items.php'); ?>
<?php //$this->load->view('templates/_inc/footer-admin.php'); ?>

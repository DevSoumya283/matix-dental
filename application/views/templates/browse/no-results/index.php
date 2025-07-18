<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <section class="content__wrapper" style="min-height: 600px;">
        <div class="content__main">
            <div class="wrapper">
                <div class="wrapper__inner row">
                    <div class="col col--8-of-12 col--centered col--am">
                        <h2>No results found for <em>"<?php echo $search_term; ?>"</em></h2>
                        <p class="textColor--dark-gray">Try adjusting your search terms, checking your spelling or choosing from some popular categories below:</p>
                        <hr>
                        <div class="row">
                            <div class="col col--12-of-12">
                                <?php for ($i = 0; $i < count($categories); $i++) { ?>
                                    <div class="col col--2-of-8">
                                        <ul class="list " style="border:none;">
                                            <li class="item">
                                                <a class="link selectcategory" href="javascript:void(0)" data-id="<?php echo $categories[$i]->id; ?>"><?php echo $categories[$i]->name; ?></a>
                                            </li>
                                        </ul>
                                    </div>
                                <?php } ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- /Content Section -->

<!-- Modals -->
<?php include(INCLUDE_PATH . '/_inc/shared/modals/choose-location.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/choose-request-list.php'); ?>



<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <?php if(!empty($page->tagline)){ ?>
    <div class="banner banner--s banner--teal">
        <div class="wrapper">
            <div class="wrapper__inner">
                <h1>
                    <?php echo $page->page_title; ?>
                    <span class="fontSize--l disp--block margin--xs no--margin-t no--margin-lr"><?php echo $page->tagline; ?></span>
                </h1>
            </div>
        </div>
    </div>
    <?php } ?>

    <section class="content__wrapper bg--lightest-gray">
        <div class="content__main">
            <!-- Content Area -->
            <div class="content">
                <?php echo $page->content; ?>
            </div>
            <!-- /Content Area -->
        </div>
    </section>



</div>
<!-- /Content Section -->

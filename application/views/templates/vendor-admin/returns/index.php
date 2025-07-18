<?php include(INCLUDE_PATH . '/_inc/header-vendor.php'); ?>

<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <section class="content__wrapper has--sidebar-l">
        <div class="content__main">
            <div class="row row--full-height">

                <!-- Sidebar -->
                <div class="sidebar col col--2-of-12">
                    <?php include(INCLUDE_PATH . '/vendor-admin/_inc/nav.php'); ?>
                </div>
                <!-- /Sidebar -->

                <!-- Content Area -->
                <div class="content col col--9-of-12 col--push-1-of-12">

                    <!-- New Returns List -->
                    <div class="heading__group border--dashed">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <ul class="list list--inline list--divided">
                                    <li class="item">
                                        <h3>New Return Requests</h3>
                                    </li>
                                </ul>
                            </div>
                            <div class="wrapper__inner">
                                <div class="input__group input__group--inline">
                                    <input id="site-search" class="input input__text" type="search" value="" placeholder="Search by number, date, customer, etc..." name="search" required>
                                    <label for="site-search" class="label">
                                        <svg class="icon icon--search textColor--gray"><use xlink:href="#icon-search"></use></svg>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- New and In Progress -->
                    <div class="well no--pad" style="border:none; max-height:800px;">

                        <!-- Single Return Request -->
                        <?php if ($returned_orders != null) { ?>
                            <?php foreach ($returned_orders as $returns) { ?>
                                <div class="order well card">
                                    <div class="heading__group wrapper border--dashed padding--s no--pad-lr no--pad-t">
                                        <div class="wrapper__inner">
                                            <h4 class="textColor--darkest-gray">Return <?php echo $returns->id; ?></h4>
                                        </div>
                                        <div class="wrapper__inner align--right"><?php // echo ROOT_PATH . 'templates/vendor-admin/returns/r/new';  ?>
                                            <button class="btn btn--s btn--primary is--link" data-target="<?php echo base_url(); ?>return-requested-OrderDetail?return_id=<?php echo $returns->id; ?>">View Request</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="order__info col col--10-of-12 col--am">
                                            <ul class="list list--inline list--stats list--divided">
                                                <li class="item item--stat stat-s">
                                                    <div class="text__group">
                                                        <span class="line--main"><?php echo $returns->address1; ?>, <?php echo $returns->address2; ?></span>
                                                        <span class="line--sub"><?php echo $returns->city; ?>,</span>
                                                        <span class="line--sub"><?php echo $returns->state; ?>, <?php echo $returns->zip; ?></span>
                                                    </div>
                                                </li>
                                                <li class="item item--stat stat-s">
                                                    <div class="text__group">
                                                        <span class="line--main"><?php echo $returns->first_name; ?></span>
                                                        <span class="line--sub">Customer Name</a>
                                                    </div>
                                                </li>
                                                <li class="item item--stat stat-s">
                                                    <div class="text__group">
                                                        <span class="line--main"><?php echo $returns->organization_name; ?></span>
                                                        <span class="line--sub">Company</a>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="order__btn col col--2-of-12 col--am align--right">
                                            <ul class="list list--inline list--stats">
                                                <li class="item item--stat">
                                                    <div class="text__group">
                                                        <span class="line--main font"><?php echo "$" . $returns->total; ?></span>
                                                        <span class="line--sub">Requested Refund</span>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                        <!-- /Single Return Request -->

                    </div>
                    <!-- /New Returns List -->

                </div>
                <!-- /Content Area -->

            </div>
        </div>
    </section>
</div>
<!-- /Content Section -->

<?php
include(INCLUDE_PATH . '/_inc/footer-vendor.php');

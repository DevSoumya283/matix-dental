
<!-- Content Section -->

<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <!-- Breadcrumbs Bar -->
    <div class="bar padding--xs bg--lightest-gray">
        <div class="wrapper wrapper--fixed">
            <ul class="list list--inline list--breadcrumbs">
                <li class="item">
                    <a class="link" href="<?php echo base_url('dashboard'); ?>">Account</a>
                </li>
                <li class="item is--active">
                    Manage Request Lists
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breadcrumbs Bar -->

    <!-- Main Content -->
    <section class="content__wrapper wrapper--fixed has--sidebar-l sidebar--no-fill">
        <div class="content__main">
            <div class="row row--full-height">

                <!-- Sidebar -->
                <div class="sidebar col col--3-of-12 bg--white padding--l no--pad-l">
                    <!-- Request List Info -->
                    <div class="sidebar__group">
                        <h3>Request Lists</h3>
                        <p class="no--margin-tb">View the items that have been requested by non-purchasing users for a selected location.</p>
                    </div>
                    <!-- /Request List Info -->

                    <!-- Location Tabs -->
                    <div class="sidebar__group">
                        <div class="tab__group is--vertical" data-target="#locationContent">
                            <?php
                            if ($request_locations != null) {
                                for ($i = 0; $i < count($request_locations); $i++) {
                                    ?>
                                    <label class="tab state--toggle has--badge" value="" data-badge="<?php
                                    if ($request_locations[$i]->item_count > 0) {
                                        echo $request_locations[$i]->item_count;
                                    } else {
                                        echo "-";
                                    }
                                    ?>" onclick="location.href = '<?php echo base_url() ?>request-products?id=<?php echo $request_locations[$i]->id; ?>'">
                                        <input type="radio" name="locationTabs" value="<?php echo $request_locations[$i]->id; ?>" >
                                        <span><a class="link select_location"> <?php echo $request_locations[$i]->nickname; ?></a></span>
                                    </label>

                                <?php
                                }
                            } else {
                                echo "";
                            }
                            ?>
                        </div>
                    </div>
                    <!-- /Location Tabs -->
                </div>
                <!-- /Sidebar -->

                <!-- Content -->
                <div id="locationContent" class="content col col--8-of-12 col--push-1-of-12">

                    <div class="page__tab">



                        <!-- Empty State -->
                        <div class="well align--center">
                            <p>
                                Select Location And View Products <br>
                                (OR)


                            </p>
                            <button class="btn btn--primary btn--m btn--dir is--next is--link" data-target="<?php echo base_url() . 'templates/browse'; ?>">Start Shopping</button>
                        </div>
                        <!-- /Empty State -->
                    </div>

                </div>
                <!-- /Content -->

            </div>
        </div>
    </section>
    <!-- /Main Content -->

</div>
<!-- /Content Section -->

<!-- Modals -->



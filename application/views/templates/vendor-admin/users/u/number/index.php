<?php include(INCLUDE_PATH . '/_inc/header-vendor.php'); ?>

<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <!-- Breadcrumbs Bar -->
    <div class="bar padding--xs bg--lightest-gray">
        <div class="wrapper wrapper--fixed">
            <ul class="list list--inline list--breadcrumbs">
                <li class="item">
                    <a class="link" href="<?php echo ROOT_PATH . 'templates/account'; ?>">Customers</a>
                </li>
                <li class="item is--active">
                    Kevin McCallister, DDS
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breadcrumbs Bar -->

    <section class="content__wrapper has--sidebar-l">
        <div class="content__main">
            <div class="row row--full-height">

                <!-- Sidebar -->
                <div class="sidebar col col--2-of-12">
                    <?php include(INCLUDE_PATH . '/_inc/nav.php'); ?>
                </div>
                <!-- /Sidebar -->

                <!-- Content Area -->
                <div class="content col col--9-of-12 col--push-1-of-12">

                    <div class="border border--dashed border--1 border--light border--b" style="padding-bottom:16px;">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <ul class="list list--inline list--divided">
                                    <li class="item">
                                        <a class="link">kevin.mccallister@gmail.com</a>
                                    </li>
                                    <li class="item">
                                        (310) 555-7894
                                    </li>
                                </ul>
                            </div>
                            <div class="wrapper__inner align--right">
                                <button class="btn btn--primary btn--s">Send Message</button>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Info Bar -->
                    <div class="card well" style="margin-top:16px;">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <ul class="list list--inline list--divided list--stats">
                                    <li class="item">
                                        <div class="entity__group">
                                            <div class="avatar avatar--m" style="background-image:url('<?php echo ROOT_PATH; ?>assets/img/ph-avatar.jpg');"></div>
                                            <div class="text__group">
                                                <span class="line--main">Kevin McCallister</span>
                                                <span class="line--sub">Duncan Dental Group, LLC</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="item">
                                        <div class="text__group">
                                            <span class="line--main">Sep. 17, 2016</span>
                                            <span class="line--sub">Customer Since</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="wrapper__inner">
                                <ul class="list list--inline list--divided list--stats align--right">
                                    <li class="item">
                                        <div class="text__group">
                                            <span class="line--main">$56,568.98</span>
                                            <span class="line--sub">Lifetime Value</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- /Customer Info Bar -->

                    <hr>
                    <br>

                    <!-- Orders -->
                    <div class="heading__group border--dashed padding--s no--pad-lr no--pad-t">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <h3>Order History</h3>
                            </div>
                            <div class="wrapper__inner align--right">
                                <ul class="list list--inline list--divided">
                                    <li class="item">
                                        <div class="select select--text">
                                            <label class="label">Status</label>
                                            <select aria-label="Select a Sorting Option">
                                                <option selected>Show All</option>
                                                <option value="1">New &amp; In Progress</option>
                                                <option value="2">Shipped</option>
                                                <option value="3">Completed</option>
                                            </select>
                                        </div>
                                    </li>
                                    <li class="item">
                                        <div class="select select--text">
                                            <label class="label">Placed in the</label>
                                            <select aria-label="Select a Sorting Option">
                                                <option selected>Last 30 Days</option>
                                                <option value="1">Last 3 Months</option>
                                                <option value="2">Last 6 Months</option>
                                                <option value="3">Last Year</option>
                                            </select>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="well bg--lightest-gray" style="max-height:480px;">
                        <!-- Single Order -->
                        <div class="order well card">
                            <div class="heading__group wrapper border--dashed padding--s no--pad-lr no--pad-t">
                                <div class="wrapper__inner">
                                    <h4 class="textColor--darkest-gray">Order 123478956</h4>
                                </div>
                                <div class="wrapper__inner align--right">
                                    <ul class="list list--inline list--divided fontWeight--2 fontSize--s">
                                        <li class="item">
                                            <a class="link modal--toggle" data-target="#orderCancellationModal">Cancel</a>
                                        </li>
                                        <li class="item">
                                            <button class="btn btn--s btn--tertiary is--link" data-target="<?php echo ROOT_PATH . 'templates/account/orders/o/number'; ?>">View Order</button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row">
                                <div class="order__info col col--10-of-12 col--am">
                                    <ul class="list list--inline list--stats list--divided">
                                        <li class="item item--stat stat-s">
                                            <div class="text__group">
                                                <span class="line--main">Winnetka #661</span>
                                                <span class="line--sub">Location</a>
                                            </div>
                                        </li>
                                        <li class="item item--stat stat-s">
                                            <div class="text__group">
                                                <span class="line--main">Sept. 17, 2016</span>
                                                <span class="line--sub">Order Date</a>
                                            </div>
                                        </li>
                                        <li class="item item--stat stat-s">
                                            <div class="text__group">
                                                <span class="line--main">Shipped</span>
                                                <span class="line--sub">Status</a>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="order__btn col col--2-of-12 col--am align--right">
                                    <ul class="list list--inline list--stats">
                                        <li class="item item--stat">
                                            <div class="text__group">
                                                <span class="line--main font">$7,804.87</span>
                                                <span class="line--sub">Order Total</a>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- /Single Order -->
                    </div>
                    <!-- /Orders -->

                    <br><br>

                    <!-- License Info -->
                    <div class="heading__group border--dashed">
                        <h3>License(s)</h3>
                    </div>
                    <div class="license">
                        <!-- License Card Item -->
                        <div class="license__card card padding--s is--verified">
                            <ul class="list list--table list--stats list--divided">
                                <li class="item item--stat stat-s">
                                    <div class="text__group">
                                        <span class="line--main">123456789</span>
                                        <span class="line--sub">License #</span>
                                    </div>
                                </li>
                                <li class="item item--stat stat-s">
                                    <div class="text__group">
                                        <span class="line--main">AZ12345678</span>
                                        <span class="line--sub">DEA #</span>
                                    </div>
                                </li>
                                <li class="item item--stat stat-s">
                                    <div class="text__group">
                                        <span class="line--main">02/26/2016</span>
                                        <span class="line--sub">Expires</span>
                                    </div>
                                </li>
                                <li class="item item--stat stat-s" style="padding-left:20px;">
                                    <div class="text__group">
                                        <span class="line--main">IL</span>
                                        <span class="line--sub">State</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <!-- /License Card Item -->
                    </div>
                    <!-- /License Info -->

                    <br><br>

                    <!-- Locations -->
                    <div class="heading__group border--dashed">
                        <h3>Locations</h3>
                    </div>
                    <table class="table" data-controls="#controlsShipping">
                        <thead>
                            <tr>
                                <th>
                                    Nickname
                                </th>
                                <th>
                                    Shipping Address
                                </th>
                                <th>
                                    Shipments
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Single Promo -->
                            <tr>
                                <td>
                                    Winnetka #661
                                </td>
                                <td>
                                    1234 Address Street, Unit 3<br>
                                    Winnetka, IL 60601
                                </td>
                                <td>
                                    34
                                </td>
                            </tr>
                            <!-- Single Promo -->
                        </tbody>
                    </table>
                    <!-- /Locations -->

                    <hr>

                    <!-- Notes -->
                    <div class="well">
                        <div class="heading__group border--dashed wrapper">
                            <div class="wrapper__inner">
                                <h4>Notes</h4>
                            </div>
                            <div class="wrapper__inner align--right">
                                <button class="btn btn--s btn--tertiary">New Note</button>
                            </div>
                        </div>
                        <ul class="list list--activity fontSize--s">
                            <li class="item">
                                <div class="entity__group">
                                    <div class="avatar avatar--xs" style="background-image:url('<?php echo ROOT_PATH; ?>assets/img/ph-avatar.jpg');"></div>
                                    <span class="fontWeight--2">Bob Mackenzie:</span> This guy fresh as hell and he has a real nice haircut. Make sure to hook him up.
                                    <span class="fontSize--xs textColor--dark-gray">1 hour ago</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <!-- /Notes -->

                </div>
                <!-- /Content Area -->

            </div>
        </div>
    </section>
</div>
<!-- /Content Section -->

<?php include(INCLUDE_PATH . '/_inc/footer-vendor.php'); ?>

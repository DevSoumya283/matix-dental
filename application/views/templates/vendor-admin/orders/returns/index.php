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

                    <!-- /Returns -->
                    <div class="heading__group border--dashed">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <ul class="list list--inline list--divided">
                                    <li class="item">
                                        <h3>Returns</h3>
                                    </li>
                                    <li class="item">
                                        <div class="select select--text">
                                            <label class="label">Showing:</label>
                                            <select>
                                                <option value="0" selected>All</option>
                                                <option value="1">Open</option>
                                                <option value="2">Pending</option>
                                                <option value="3">Closed</option>
                                            </select>
                                        </div>
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
                            <div id="controlsOrders" class="contextual__controls wrapper__inner align--right">
                                <a class="link fontWeight--2 fontSize--s modal--toggle is--contextual is--off" data-target="#denyRequestModal">Cancel Selected</a>
                            </div>
                        </div>
                    </div>
                    <table class="table" data-controls="#controlsOrders">
                        <thead>
                            <tr>
                                <th width="3%">
                                    <label class="control control__checkbox">
                                        <input type="checkbox" class=" is--selector">
                                        <div class="control__indicator"></div>
                                    </label>
                                </th>
                                <th>
                                    Return
                                </th>
                                <th>
                                    Customer
                                </th>
                                <th>
                                    Opened
                                </th>
                                <th>
                                    Refund Amount
                                </th>
                                <th>
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Single Return -->
                            <tr>
                                <td>
                                    <label class="control control__checkbox">
                                        <input type="checkbox" name="checkboxRow">
                                        <div class="control__indicator"></div>
                                    </label>
                                </td>
                                <td>
                                    <a class="link fontWeight--2">123456789</a>
                                </td>
                                <td>
                                    Kevin McCallister
                                </td>
                                <td>
                                    Sep. 17, 2016
                                </td>
                                <td>
                                    $7,365.87
                                </td>
                                <td>
                                    Open
                                </td>
                            </tr>
                            <!-- Single Return -->
                            <!-- Single Return -->
                            <tr>
                                <td>
                                    <label class="control control__checkbox">
                                        <input type="checkbox" name="checkboxRow">
                                        <div class="control__indicator"></div>
                                    </label>
                                </td>
                                <td>
                                    <a class="link fontWeight--2">123456789</a>
                                </td>
                                <td>
                                    Kevin McCallister
                                </td>
                                <td>
                                    Sep. 17, 2016
                                </td>
                                <td>
                                    $7,365.87
                                </td>
                                <td>
                                    Pending
                                </td>
                            </tr>
                            <!-- Single Return -->
                            <!-- Single Return -->
                            <tr>
                                <td>
                                    <label class="control control__checkbox">
                                        <input type="checkbox" disabled>
                                        <div class="control__indicator"></div>
                                    </label>
                                </td>
                                <td>
                                    <a class="link fontWeight--2">123456789</a>
                                </td>
                                <td>
                                    Kevin McCallister
                                </td>
                                <td>
                                    Sep. 17, 2016
                                </td>
                                <td>
                                    $7,365.87
                                </td>
                                <td>
                                    Closed
                                </td>
                            </tr>
                            <!-- Single Return -->
                        </tbody>
                    </table>
                    <!-- /Returns -->

                </div>
                <!-- /Content Area -->

            </div>
        </div>
    </section>
</div>
<!-- /Content Section -->

<?php include(INCLUDE_PATH . '/_inc/footer-vendor.php'); ?>

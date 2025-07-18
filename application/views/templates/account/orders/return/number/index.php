<?php include(INCLUDE_PATH . '/_inc/header.php'); ?>

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
                <li class="item">
                    <a class="link" href="<?php echo ROOT_PATH . 'templates/account/orders/returns'; ?>">Returns</a>
                </li>
                <li class="item is--active">
                    Return 123478956
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breadcrumbs Bar -->

    <!-- Main Content -->
    <section class="content__wrapper wrapper--fixed bg--lightest-gray">
        <div class="content__main">
            <div class="row row--full-height">
                <div class="content col col--9-of-12">

                    <div class="invoice">
                        <div class="inv__head row">
                            <div class="col col--2-of-8 col--am">
                                <!--
                                    NOTE: This logo can be any size
                                -->
                                <img class="inv__logo" src="<?php echo ROOT_PATH . 'assets/img/ph-vendor-logo.png'; ?>" alt="">
                            </div>
                            <div class="col col--4-of-8 col--push-2-of-8 col--am align--right">
                                <span class="fontWeight--2 textColor--dark-gray">Return:</span>
                                <span class="fontWeight--2">123478956</span>
                            </div>
                        </div>
                        <div class="inv__contact wrapper">
                            <div class="wrapper__inner">
                                <ul class="list list--inline list--divided list--stats align--left disp--ib">
                                    <li class="item item--stat stat-s">
                                        <div class="text__group">
                                            <span class="line--main"><a class="link">123478956</a></span>
                                            <span class="line--sub">Original Order</a>
                                        </div>
                                    </li>
                                    <li class="item item--stat stat-s">
                                        <div class="text__group">
                                            <span class="line--main">Sep. 17, 2016</span>
                                            <span class="line--sub">Date Opened</a>
                                        </div>
                                    </li>
                                    <li class="item item--stat stat-s">
                                        <div class="text__group">
                                            <span class="line--main">Approved</span>
                                            <span class="line--sub">Status</a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="wrapper__inner align--right">
                                <svg class="icon icon--cc icon--visa"></svg>
                                Visa •••• 4545
                            </div>
                        </div>
                        <table class="table table--invoice">
                            <thead>
                                <tr>
                                    <th width="70%">
                                        Item
                                    </th>
                                    <th width="20%">
                                        Unit Price
                                    </th>
                                    <th width="100%">
                                        Qty
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Line Item -->
                                <tr>
                                    <td>
                                        <!-- Product -->
                                        <div class="product product--s row multi--vendor req--license padding--xxs">
                                            <div class="product__image col col--2-of-8 col--am">
                                                <div class="product__thumb" style="background-image:url('<?php echo image_url(); ?>assets/img/product-image.png');">
                                                </div>
                                            </div>
                                            <div class="product__data col col--6-of-8 col--am">
                                                <span class="product__name">Osung PBWPBW Impression Tray with Wing (Nickel) Partial, PB</span>
                                                <span class="product__mfr">
                                                    by <a class="link fontWeight--2" href="#">Osung</a>
                                                </span>
                                            </div>
                                        </div>
                                        <!-- /Product -->
                                    </td>
                                    <td>
                                        $33.33
                                    </td>
                                    <td>
                                        3
                                    </td>
                                </tr>
                                <!-- /Line Item -->
                            </tbody>
                        </table>
                        <div class="inv__totals">
                            <div class="wrapper">
                                <div class="wrapper__inner width--50">
                                    <h5 class="textColor--dark-gray">Return Notes:</h5>
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin malesuada eleifend consectetur. In hac habitasse platea dictumst. Proin gravida mi vel eros.
                                </div>
                                <div class="wrapper__inner align--right">
                                    <span class="fontWeight--2 textColor--dark-gray fontSize--s disp--block margin--xs no--margin-lr no--margin-t">Subtotal: $1,768.54<br>
                                        Tax: $323.14<br>
                                        Shipping: $17.95</span>
                                    <span class="fontWeight--2">Pending Refund: $2,109.63</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Sidebar -->
                <div class="sidebar col col--2-of-12">
                    <div class="sidebar__group margin--m">
                        <ul class="list">
                            <li class="item margin--s no--margin-t no--margin-lr">
                                <button class="btn btn--primary btn--l modal--toggle" data-target="#returnInstructionsModal">View Instructions</button>
                            </li>
                            <li class="item">
                                <a class="link">Print Return Slip</a>
                            </li>
                            <li class="item">
                                <a class="link modal--toggle" data-target="#contactVendorModal">Contact Vendor</a>
                            </li>
                            <li class="item">
                                <a class="link modal--toggle" data-target="#cancelReturnModal">Cancel Return</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- /Sidebar -->

            </div>
        </div>
    </section>
    <!-- /Main Content -->

</div>
<!-- /Content Section -->

<!-- Modals -->
<?php include(INCLUDE_PATH . '/_inc/shared/modals/return-instructions.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/contact-vendor.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/cancel-return.php'); ?>

<?php
include(INCLUDE_PATH . '/_inc/footer.php');

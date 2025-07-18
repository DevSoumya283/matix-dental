<?php include(INCLUDE_PATH . '/_inc/header-' . $userType . '.php'); ?>

<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <section class="content__wrapper has--sidebar-l">
        <div class="content__main">
            <div class="row row--full-height">

                <!-- Sidebar -->
                <div class="sidebar col col--2-of-12">
                    <?php //include(INCLUDE_PATH . '/' . (User_model::can($_SESSION['user_permissions'], 'is-admin') ? 'admin' : 'vendor-admin') . '/_inc/nav.php'); ?>
                   <?php
                        $folder = User_model::can($_SESSION['user_permissions'], 'is-admin') ? 'admin' : 'vendor-admin';
                        $this->load->view('templates/' . $folder . '/_inc/nav.php'); 

                    ?>
                </div>
                <!-- /Sidebar -->
                <div class="content col col--9-of-12 col--push-1-of-12">
                    <div class="heading__group border--dashed">
                        <div class="wrapper">
                        <div class="col col--9-of-12">
                            <h3>My Pricing Scales - <?php echo $pricingScale->name; ?></h3>
                        </div>
                        <!-- <div class="col col--3-of-12 ">
                            <a class="link modal--toggle editLink" data-user_id="<?php echo $role->id; ?>" data-target="#addBuyingClubModal" >Add Buying Club</a>
                        </div> -->
                    </div>
                </div>
            <form class="form__group modal__content">
                <input type="hidden" id="pricingScaleId" value="<?php echo $pricingScale->id; ?>">
                <div class="row">
                    <div class="col col--6-of-12 input__group is--inline">
                        <input id="name" name="name" class="input not--empty" type="text" value="<?php echo $pricingScale->name; ?>" required>
                        <label class="label" for="name">Name</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col col--3-of-12 input__group is--inline">

                        <input id="percentage_discount" name="percentage_discount" class="input not--empty" type="text" value="<?php echo $pricingScale->percentage_discount; ?>" required>
                        <label class="label" for="percentage_discount">% Discount</label>
                    </div>
                    <div class ="col col--9-of-12">This discount applies to all products from the vendor unless overwritten by a product discount</div>
                </div>
                <div class="row">
                    <div class="col col--6-of-12 input__group is--inline">
                        <a class="btn  btn--m btn--primary btn--block save-vendor-discount" href="#">Save</a>
                    </div>
                </div>
               <!--  <div class="row">
                    <div class="col col--6-of-12 input__group is--inline">
                        <a class="btn  btn--m btn--primary btn--block update-club" href="#">Save</a>
                    </div>
                </div> -->
                <div>
                    <div class="col col--2-of-12">Active:</div>
                    <div class="col col--10-of-12">
                        <input class="toggleActive" type="radio" name="active" value="Y" <?php if($pricingScale->active == 1) { echo 'checked="checked"'; }?>>Yes
                        <input class="toggleActive" type="radio" name="active" value="Y" <?php if($pricingScale->active == 0) { echo 'checked="checked"'; }?>>No
                    </div>
                </div>
                <div>
                    <div class="col col--2-of-12">Created on:</div>
                    <div class="col col--10-of-12"><?php echo date('m/d/Y', strtotime($pricingScale->created_at)); ?></div>
                </div>
                <div>
                    <div class="col col--2-of-12">Last Updated:</div>
                    <div class="col col--10-of-12"><?php echo date('m/d/Y', strtotime($pricingScale->updated_at)); ?></div>
                </div>
            </form>
                <br>
                <div>


                    <div class="col col--12-of-12">
                        <div class="card padding--s">
                            <div class="card-title">Product Pricing</div>
                            <div class="col col--12-of-12">

                            </div>
                            <a href="/pricing-scales/manage-products?id=<?php echo $pricingScale->id; ?>&vendorId=<?php echo $vendorId; ?>" class="btn btn--m btn--primary btn--block addItems" data-type="products">Manage Product Pricing</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>

<script src="/assets/js/pricingScales.js"></script>

<?php //include(INCLUDE_PATH . '/_inc/shared/modals/upload-buying-club-data.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/edit-list.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/footer-' . $userType . '.php'); ?>

<?php $this->load->view('templates/_inc/shared/modals/upload-buying-club-data.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/edit-list.php'); ?>
<?php $this->load->view('templates/_inc/footer-' . $userType . '.php'); ?>
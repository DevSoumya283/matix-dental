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

                    <?php if($vendorId){ ?>
                    <div class="heading__group border--dashed">
                        <div class="wrapper">
                            <div class="col col--9-of-12">
                                <h3>My Pricing Scales <img src="/assets/img/icons/help.svg" title="A Vendor may set different pricing tiers for it's preferred customers"/></h3>
                            </div>
                            <div class="col col--3-of-12 ">
                                <a class="link modal--toggle editLink" data-user_id="<?php echo $role->id; ?>" data-target="#addPricingScaleModal" >Add Pricing Scale</a>
                            </div>
                            <div style="overflow: hidden; overflow-x: scroll;">
                            <table class="table" data-controls="#controlsTable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Discount %</th>
                                        <th>Active</th>
                                        <th>Last Updated</th>
                                        <th></th>
                                    </tr>
                                </thead>
                            <?php foreach($pricingScales as $pricingScale){
                                    ?>
                                <tr>
                                    <td><?php echo $pricingScale->name; ?></td>
                                    <td><?php echo (!empty($pricingScale->percentage_discount)) ? $pricingScale->percentage_discount: 0; ?> %</td>
                                    <td><?php echo ($pricingScale->active) ? 'Yes' : 'No'; ?></td>
                                    <td><?php echo date_format(date_create($pricingScale->updated_at), 'm-d-Y'); ?></td>
                                    <td><a href="/pricing-scales/edit?id=<?php echo $pricingScale->id; ?>&vendorId=<?php echo $vendorId; ?>">Edit</a></td>
                                </tr>
                            <?php } ?>
                            </table>
                            </div>
                        </div>
                    </div>
                    <?php } else { ?>
                        <div class="select">
                            <select name="vendorSelect" id="vendorSelect">
                                <option value="">Select vendor</option>
                                <?php foreach($vendors as $k => $vendor){
                                    echo '<option value="' . $vendor->id . '">' . $vendor->name . '</option>';
                                 } ?>
                            </select>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="/assets/js/pricingScales.js"></script>

<?php //include(INCLUDE_PATH . '/_inc/shared/modals/add-pricing-scale.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/footer-' . $userType . '.php'); ?>

<?php $this->load->view('templates/_inc/shared/modals/add-pricing-scale.php'); ?>
<?php $this->load->view('templates/_inc/footer-' . $userType . '.php'); ?>
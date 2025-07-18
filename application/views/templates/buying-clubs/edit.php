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
                            <h3>My <?php echo (!empty($this->input->get('t'))) ?  'Pricing Scales' :  'Market Place'; ?> - <?php echo $buyingClub->name; $cols = 0;?></h3>
                        </div>
                        <!-- <div class="col col--3-of-12 ">
                            <a class="link modal--toggle editLink" data-user_id="<?php echo $role->id; ?>" data-target="#addBuyingClubModal" >Add Buying Club</a>
                        </div> -->
                    </div>
                </div>
            <form class="form__group modal__content">
                <input type="hidden" id="clubId" value="<?php echo $buyingClub->id; ?>">
                <?php if(User_model::can($_SESSION['user_permissions'], 'is-admin') || $buyingClub->owner_id == $_SESSION['user_id']){ ?>
                <div class="row">
                    <div class="col col--6-of-12 input__group is--inline">
                        <input id="name" name="name" class="input not--empty" type="text" value="<?php echo $buyingClub->name; ?>" required>
                        <label class="label" for="name">Name</label>
                    </div>
                </div>
                <?php } ?>

                <?php if(User_model::can($_SESSION['user_permissions'], 'is-admin') || $buyingClub->owner_id == $_SESSION['user_id']){ ?>
                <div class="row">
                    <div class="col col--6-of-12 input__group is--inline">
                        <a class="btn  btn--m btn--primary btn--block update-club" href="#">Save</a>
                    </div>
                </div>
                <div>
                    <div class="col col--1-of-12">Active:</div>
                    <div class="col col--11-of-12">
                        <input class="toggleActive" type="radio" name="active" value="Y" <?php if($buyingClub->active == 1) { echo 'checked="checked"'; }?>>Yes
                        <input class="toggleActive" type="radio" name="active" value="Y" <?php if($buyingClub->active == 0) { echo 'checked="checked"'; }?>>No
                    </div>
                </div>
                <!-- <div>
                    <div class="col col--2-of-12">Created on:</div>
                    <div class="col col--10-of-12"><?php echo date('m/d/Y', strtotime($buyingClub->created_at)); ?></div>
                </div>
                <div>
                    <div class="col col--2-of-12">Last Updated:</div>
                    <div class="col col--10-of-12"><?php echo date('m/d/Y', strtotime($buyingClub->updated_at)); ?></div>
                </div> -->
                <?php } ?>
            </form>
                <br>
                <div>
                    <?php if(User_model::can($_SESSION['user_permissions'], 'is-admin')){
                        $cols++;
                    ?>
                        <div class="col col--4-of-12">
                            <div class="card padding--s">
                                <div class="card-title">Vendors</div>
                                <div>
                                <?php if(count($buyingClub->vendors) > 0){
                                    foreach($buyingClub->vendors as $k => $vendor){ ?>
                                        <div class="" style="border-bottom: 1px solid grey;">
                                                <?php echo $vendor->name; ?>

                                                <a href="#" class="x-button delete-vendor" data-club_id="<?php echo $buyingClub->id; ?>" data-vendor_id="<?php echo $vendor->vendor_id; ?>">X</a>
                                        </div>
                                    <?php }
                                } else {
                                    echo "No vendors added.";
                                } ?>
                                </div>
                                <a href="#" class="btn btn--m btn--primary btn--block addItems modal--toggle " style="margin-top: 5px;" data-target="#uploadBuyingClubVendorDataModal" data-type="vendors">Add Vendors</a>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if(User_model::can($_SESSION['user_permissions'], 'is-admin') || $_SESSION['user_id'] == $buyingClub->owner_id){
                        $cols++;
                    ?>
                        <div class="col col--<?php echo ($cols == 2) ? 4 : (($cols == 1) ? 6 : 12) ?>-of-12">
                            <div class="card padding--s">
                                <div class="card-title">Organizations</div>
                                <div>
                                <?php if(count($buyingClub->organizations) > 0){
                                    foreach($buyingClub->organizations as $k => $organization){ ?>
                                        <div class="alt-rows" style="border-bottom: 1px solid grey;">
                                            <?php echo $organization->name; ?>
                                                <a href="#" class="x-button delete-organization" data-club_id="<?php echo $buyingClub->id; ?>" data-organization_id="<?php echo $organization->organization_id; ?>">X</a>
                                        </div>
                                    <?php }
                                } else {
                                    echo "No organizations added.";
                                } ?>
                                </div>
                                <a href="#" class="btn btn--m btn--primary btn--block addItems modal--toggle " data-target="#uploadBuyingClubOrganizationDataModal" data-type="organizations" data-user_id="<?php echo $_SESSION['user_id']; ?>" style="margin-top: 5px;">Add Organizations</a>
                            </div>
                        </div>
                    <?php } ?>

                        <div class="col col--<?php echo ($cols == 2) ? 4 : (($cols == 1) ? 6 : 12) ?>-of-12">
                            <div class="card padding--s">
                                <div class="card-title">Pricing Scale<?php if(User_model::can($_SESSION['user_permissions'], 'is-admin')){ ?>s<?php } ?></div>
                                <form id="pricing-scales" method="post">
                                    <input type="hidden" name="bc_id" value="<?php echo $buyingClub->id; ?>" />
                                    <?php
                                    if(!empty($vendors)){
                                        foreach($vendors as $vendor){
                                            echo '<div class="alt-rows">';
                                                $activeScale = null;
                                                echo $vendor->name;
                                                if(!empty($pricingScales)) {
                                                    foreach($buyingClub->pricingScales as $k => $scale){
                                                        if($scale->vendor_id == $vendor->vendor_id){
                                                            $activeScale = $scale->pricing_scale_id;
                                                        }
                                                    }
                                                    ?>

                                                    <div class="select ">
                                                        <select name="pricing_scales[<?php echo $vendor->vendor_id; ?>]" id="pricing-scale" style="width: 96%; margin: 2%;">
                                                        <option value="">Select Pricing Scale</option>
                                                        <?php foreach($pricingScales[$vendor->vendor_id] as $scale){
                                                            $selected = ($scale->id == $activeScale) ? 'selected' : '';
                                                            echo '<option value="' . $scale->id . '"' . $selected . '>' . $scale->name . '</option>';
                                                        } ?>
                                                        </select>
                                                    </div>
                                                <?php } else {
                                                    echo "no pricing scales added";
                                                }
                                            echo '</div>';
                                        }
                                    } else {
                                        echo "No vendors added";
                                    }
                                    ?>
                                </form>
                                <a href="#" class="btn btn--m btn--primary btn--block savePricingScale" data-type="products" style="margin-top: 5px;">Set Pricing Scale</a>
                            </div>
                        </div>

                </div>
            </div>
        </div>
    </section>
</div>

<script src="/assets/js/buyingClubs.js"></script>

<?php //include(INCLUDE_PATH . '/_inc/shared/modals/upload-buying-club-vendors.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/upload-buying-club-organizations.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/edit-list.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/footer-' . $userType . '.php'); ?>

<?php $this->load->view('templates/_inc/shared/modals/upload-buying-club-vendors.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/upload-buying-club-organizations.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/edit-list.php'); ?>
<?php $this->load->view('templates/_inc/footer-' . $userType . '.php'); ?>
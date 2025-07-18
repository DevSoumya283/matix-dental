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
                                <h3>My Market Places <img src="/assets/img/icons/help.svg" title="We invite selected vendors to participate in our market places at a select discount for our members"/></h3>
                            </div>
                            <?php if(User_model::can($_SESSION['user_permissions'], 'is-admin')){ ?>
                            <div class="col col--3-of-12 ">
                                <a class="link modal--toggle editLink" data-user_id="<?php echo $role->id; ?>" data-target="#addBuyingClubModal" >Add Market Place</a>
                            </div>
                            <?php } ?>
                            <div style="overflow: hidden; overflow-x: scroll;">
                            <table class="table" data-controls="#controlsTable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Created By</th>
                                        <th>Active</th>
                                        <th>Last Updated</th>
                                        <th></th>
                                    </tr>
                                </thead>
                            <?php foreach($buyingClubs as $buyingClub){
                                if((User_model::can($_SESSION['user_permissions'], 'is-admin') && $buyingClub->owner_id == $_SESSION['user_id']) || (User_model::can($_SESSION['user_permissions'], 'is-vendor') && $buyingClub->owner_id != $_SESSION['user_id'])){
                                ?>
                                <tr>
                                    <td><?php echo $buyingClub->name; ?></td>
                                    <td><?php echo $buyingClub->first_name . ' ' . $buying_club->last_name; ?></td>
                                    <td><?php echo ($buyingClub->active) ? 'Yes' : 'No'; ?></td>
                                    <td><?php echo date_format(date_create($buyingClub->updated_at), 'm-d-Y'); ?></td>
                                    <td><a href="/buying-club/edit?id=<?php echo $buyingClub->id; ?>">Edit</a></td>
                                </tr>
                            <?php }
                            } ?>
                            </table>
                            </div>
                        </div>
                    </div>

                    <?php if(User_model::can($_SESSION['user_permissions'], 'is-vendor') || User_model::can($_SESSION['user_permissions'], 'is-admin')){ ?>
                    <div class="heading__group border--dashed">
                        <div class="wrapper">
                            <div class="col col--9-of-12">
                                <h3>Buying Clubs <img src="/assets/img/icons/help.svg" title="A Vendor may set different pricing tiers for it's preferred customers"/></h3>
                            </div>
                            <?php if(User_model::can($_SESSION['user_permissions'], 'is-vendor') ) { ?>
                            <div class="col col--3-of-12 ">
                                <a class="link modal--toggle editLink" data-user_id="<?php echo $role->id; ?>" data-target="#addBuyingClubModal" >Add Buying Club</a>
                            </div>
                            <?php } ?>
                            <?php if(User_model::can($_SESSION['user_permissions'], 'is-admin')){ ?>
                                <div class="select">
                                    <select name="buying_club_vendor_id" id="bcVendorSelect">
                                        <option value="">Select vendor</option>
                                        <?php foreach($buying_club_owners as $vendor){
                                            echo '<option value="' . $vendor->id . '"' . (($bcVendorId == $vendor->id) ? ' selected' : '') . '>' . $vendor->name . '</option>';
                                        } ?>
                                    </select>
                                </div>
                            <?php } ?>
                                <table class="table" data-controls="#controlsTable">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Created By</th>
                                            <th>Active</th>
                                            <th>Last Updated</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                <?php foreach($buyingClubs as $buyingClub){
                                    if(( User_model::can($_SESSION['user_permissions'], 'is-vendor') && $buyingClub->owner_id == $_SESSION['user_id']) || ( User_model::can($_SESSION['user_permissions'], 'is-admin') && $buyingClub->owner_id != $_SESSION['user_id'])){
                                        ?>
                                    <tr>
                                        <td><?php echo $buyingClub->name; ?></td>
                                        <td><?php echo $buyingClub->first_name . ' ' . $buying_club->last_name; ?></td>
                                        <td><?php echo ($buyingClub->active) ? 'Yes' : 'No'; ?></td>
                                        <td><?php echo date_format(date_create($buyingClub->updated_at), 'm-d-Y'); ?></td>
                                        <td><a href="/buying-club/edit?id=<?php echo $buyingClub->id; ?>&t=ps">Edit</a></td>
                                    </tr>
                                <?php }
                                } ?>
                                </table>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="/assets/js/buyingClubs.js"></script>

<?php //include(INCLUDE_PATH . '/_inc/shared/modals/add-buying-club.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/add-pricing-scale.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/footer-' . $userType . '.php'); ?>

<?php $this->load->view('templates/_inc/shared/modals/add-buying-club.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/add-pricing-scale.php'); ?>
<?php //$this->load->view('templates/_inc/footer-' . $userType . '.php'); ?>
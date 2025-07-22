<?php include(INCLUDE_PATH . '/_inc/header-' . $userType . '.php'); ?>

<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <section class="content__wrapper has--sidebar-l">
        <div class="content__main">
            <div class="row row--full-height">

                <!-- Sidebar -->
                <div class="sidebar col col--2-of-12" style="padding: 12px;">
                    <?php //include(INCLUDE_PATH . '/' . (User_model::can($_SESSION['user_permissions'], 'is-admin') ? 'admin' : 'vendor-admin') . '/_inc/nav.php'); ?>
                    <?php
                        $folder = User_model::can($_SESSION['user_permissions'], 'is-admin') ? 'admin' : 'vendor-admin';
                        $this->load->view('templates/' . $folder . '/_inc/nav.php'); 

                    ?>
                </div>
                <!-- /Sidebar -->
                <div class="col col--9-of-12 col--push-1-of-12" style="overflow:scroll">
                    <div class="heading__group border--dashed">
                        <div class="wrapper">
                        <div class="col col--10-of-12">
                            <h3>My Sites</h3>
                        </div>
                        <?php if(User_model::can($_SESSION['user_permissions'], 'create-whitelabels')){ ?>
                        <div class="col col--2-of-12  align--right">
                            <a class="link modal--toggle editLink align--right" data-user_id="" data-target="#addWhiteLabelModal" >Add Site</a>
                        </div>
                        <?php } ?>
                        <div style="">
                        <div style="overflow: hidden; overflow-x: scroll;">
                        <table class="table" data-controls="#controlsTable">
                            <thead>
                                <tr>
                                    <th>Logo</th>
                                    <th>Name</th>
                                    <th>Short name</th>
                                    <th>Domain</th>
                                    <!-- <th>Whitelabel</th> -->
                                    <th>Vendor</th>
                                    <th>Last Updated</th>
                                    <th></th>
                                </tr>
                            </thead>
                        <?php foreach($sites as $site){ ?>
                            <tr>
                                <td><img src="/assets/img/logos/<?php echo $site->logo; ?>" style="width: 200px;"/></td>
                                <td><?php echo $site->name; ?></td>
                                <td><?php echo $site->short_name; ?></td>
                                <td><?php echo $site->domain; ?></td>
                                <!-- <td><?php  echo ($site->whitelabel) ? 'Yes' : 'No'; ?></td> -->
                                <td><?php echo $site->vendor_name; ?></td>
                                <td><?php echo date_format(date_create($site->updated_at), 'm-d-Y'); ?></td>
                                <!--  <td><a href="#" class="edit-whitelabel modal--toggle" data-id="<?php echo $site->id; ?>" data-target="#addWhiteLabelModal">Edit</td> -->
                                <td><a href="/white-labels/edit?id=<?php echo $site->id; ?>" class="edit-whitelabel" >Edit</td>
                            </tr>
                        <?php } ?>
                        </table>
                        </div>
                    </div>
                    <?php if(!empty($marketPlaces)){ ?>
                        <br />
                        <div class="heading__group border--dashed">
                            <div class="wrapper">
                            <div class="col col--10-of-12">
                                <h3>My Market Places</h3>
                            </div>
                            <?php if(User_model::can($_SESSION['user_permissions'], 'create-marketplaces')){ ?>
                            <div class="col col--2-of-12  align--right">
                                <a class="link modal--toggle editLink align--right" data-user_id="" data-target="#addWhiteLabelModal" >Add Market Place</a>
                            </div>
                            <?php } ?>
                            <div style="overflow: hidden; overflow-x: scroll;">
                                <table class="table" data-controls="#controlsTable">
                                <thead>
                                    <tr>
                                        <th>Logo</th>
                                        <th>Name</th>
                                        <th>Short name</th>
                                        <th>Domain</th>
                                        <!-- <th>Whitelabel</th> -->
                                        <th>Vendor</th>
                                        <th>Last Updated</th>
                                        <th></th>
                                    </tr>
                                </thead>
                            <?php foreach($sites as $site){ ?>
                                <tr>
                                    <td><img src="/assets/img/logos/<?php echo $site->logo; ?>" style="width: 200px;"/></td>
                                    <td><?php echo $site->name; ?></td>
                                    <td><?php echo $site->short_name; ?></td>
                                    <td><?php echo $site->domain; ?></td>
                                    <!-- <td><?php  echo ($site->whitelabel) ? 'Yes' : 'No'; ?></td> -->
                                    <td><?php echo $site->vendor_name; ?></td>
                                    <td><?php echo date_format(date_create($site->updated_at), 'm-d-Y'); ?></td>
                                    <!--  <td><a href="#" class="edit-whitelabel modal--toggle" data-id="<?php echo $site->id; ?>" data-target="#addWhiteLabelModal">Edit</td> -->
                                    <td><a href="/white-labels/edit?id=<?php echo $site->id; ?>" class="edit-whitelabel" >Edit</td>
                                </tr>
                            <?php } ?>
                            </table>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
         </div>
        </div>
    </div>
    </section>
</div>
<!--
<script src="/assets/js/whitelabels.js"></script> -->

<?php //include(INCLUDE_PATH . '/_inc/shared/modals/add-white-label.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/footer-' . $userType . '.php'); ?>

<?php $this->load->view('templates/_inc/shared/modals/add-white-label.php'); ?>
<?php //$this->load->view('templates/_inc/footer-' . $userType . '.php'); ?>
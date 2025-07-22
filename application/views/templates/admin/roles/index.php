

<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <section class="content__wrapper has--sidebar-l">
        <div class="content__main">
            <div class="row row--full-height">

                <!-- Sidebar -->
                <div class="sidebar col col--2-of-12" style="padding:12px">
                    <?php //include(INCLUDE_PATH . '/admin/_inc/nav.php') ?>
                     <?php $this->load->view('templates/admin/_inc/nav.php'); ?>
                </div>
                <!-- /Sidebar -->

                <!-- Content Area -->
                <div class="content col col--9-of-12 col--push-1-of-12">
                    <!-- Customers -->
                    <div class="heading__group border--dashed">
                        <div class="wrapper">
                            <div class="row">
                                <div class="col col--10-of-12">
                                    <h3>Roles</h3>
                                </div>
                                <div class="col col--2-of-12 ">
                                    <a class="link modal--toggle editLink" data-role_id="<?php echo $role->id; ?>" data-target="#addPermissionsModal" >Add Permission</a>
                                </div>
                            </div>
                            <div style="overflow: hidden; overflow-x: scroll;">
                                <table class="table" data-controls="#controlsTable">
                                <thead>
                                    <tr>
                                        <th>Role Name</th>
                                        <th>Role Tier</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    foreach($roles as $role){
                                ?>
                                        <tr>
                                            <td>
                                                <?php echo $role->role_name ?>
                                            </td>
                                            <td>
                                                <?php echo $role->role_tier ?>
                                            </td>
                                            <td class="center center--h">
                                                <ul class="list list--inline list--divided fontWeight--2 fontSize--s is--contextual is--off">
                                                    <li class="item">
                                                        <a class="link modal--toggle editLink" data-role_id="<?php echo $role->id; ?>" data-target="#managePermissionsModal" >Edit Permissions</a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                <?php } ?>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php //include(INCLUDE_PATH . '/_inc/shared/modals/manage-permissions.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/add-permission.php'); ?>

<?php $this->load->view('templates/_inc/shared/modals/manage-permissions.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/add-permission.php'); ?>


<script src="/assets/js/rolesAndPermissions.js"></script>
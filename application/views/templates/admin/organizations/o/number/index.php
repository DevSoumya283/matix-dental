
<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <!-- Breadcrumbs Bar -->
    <div class="bar padding--xs bg--lightest-gray">
        <div class="wrapper wrapper--fixed">
            <ul class="list list--inline list--breadcrumbs">
                <li class="item">
                    <a class="link" href="<?php echo base_url(); ?>organizations-list">Organizations</a>
                </li>
                <li class="item is--active">
                    <?php echo $organization_details->organization_name; ?>
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breadcrumbs Bar -->

    <section class="content__wrapper has--sidebar-l">
        <div class="content__main">
            <div class="row row--full-height">

                <!-- Sidebar -->
                <div class="sidebar col col--2-of-12"style="padding:12px">
                    <?php //include(INCLUDE_PATH . '/admin/_inc/nav.php'); ?>
                    <?php $this->load->view('templates/admin/_inc/nav.php'); ?>

                </div>
                <!-- /Sidebar -->

                <!-- Content Area -->
                <div class="content col col--9-of-12 col--push-1-of-12">

                    <div class="border border--dashed border--1 border--light border--b" style="padding-bottom:16px;">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <ul class="list list--inline">
                                    <li class="item fontWeight--2">
                                        Admin:
                                    </li>
                                    <li class="item">
                                        <a class="link" href="<?php echo base_url(); ?>customer-details-page?user_id=<?php echo $organization_admin->id; ?>"><?php echo $organization_admin->first_name; ?></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="wrapper__inner align--right">
                                <button class="btn btn--tertiary btn--s btn--icon modal--toggle" data-target="#editOrganizationModal"><svg class="icon icon--settings"><use xlink:href="#icon-settings"></use></svg></button>
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
                                            <div class="text__group">
                                                <span class="line--main"><?php echo $organization_details->organization_name; ?></span>
                                                <span class="line--sub">Created: <?php echo date('M d, Y', strtotime($organization_details->created_at)); ?></span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="item">
                                        <div class="entity__group">
                                            <div class="text__group">
                                                <span class="line--main"><?php echo $organization_details->organization_type; ?></span>
                                                <span class="line--sub">Type</span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="wrapper__inner">
                                <ul class="list list--inline list--divided list--stats align--right">
                                    <li class="item">
                                        <div class="text__group">
                                            <span class="line--main"><?php echo ($organization_details->total_count != null) ? "$" . $organization_details->total_count : "$0.00"; ?></span>
                                            <span class="line--sub">Lifetime Value</span>
                                        </div>
                                    </li>
                                    <li class="item">
                                        <div class="text__group">
                                            <span class="line--main"><?php echo ($organization_details->total_orders != null) ? $organization_details->total_orders : "0"; ?></span>
                                            <span class="line--sub">Orders</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- /Customer Info Bar -->

                    <hr>
                    <br>

                    <!-- Users -->
                    <div class="heading__group border--dashed">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <h3>Users</h3>
                            </div>
                            <div class="wrapper__inner">
                                <form method="post" action="<?php echo base_url(); ?>organization-details-page?organization_id=<?php echo $organization_id ?>">
                                    <div class="input__group input__group--inline">
                                        <input id="site-search" class="input input__text" type="text" value="" placeholder="Search by customer name, email, etc..." name="search" required>
                                        <label for="site-search" class="label">
                                            <svg class="icon icon--search textColor--gray"><use xlink:href="#icon-search"></use></svg>
                                        </label>
                                    </div>
                                </form>
                            </div>
                            <div id="controlsTable" class="contextual__controls wrapper__inner align--right">
                                <button class="btn btn--tertiary btn--s contextual--hide modal--toggle" data-target="#addNewUserModal">New User</button>
                                <ul class="list list--inline list--divided fontWeight--2 fontSize--s is--contextual is--off">
                                    <li class="item">
                                        <a class="link modal--toggle" data-target="#organizationUserActivationModal">Activate</a>
                                    </li>
                                    <li class="item">
                                        <a class="link modal--toggle" data-target="#organizationUserDeactivationModal">Deactivate</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div style="overflow: hidden; overflow-x: scroll;">
                        <table class="table" data-controls="#controlsTable">
                        <thead>
                            <tr>
                                <th width="3%">
                                    <label class="control control__checkbox">
                                        <input type="checkbox" class=" is--selector" id="selectAll">
                                        <div class="control__indicator"></div>
                                    </label>
                                </th>
                                <th>
                                    Customer
                                </th>
                                <th>
                                    Created
                                </th>
                                <th>
                                    Role
                                </th>
                                <th>
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Single User -->
                            <?php if($organization_group!=null) {  ?>
                            <?php foreach ($organization_group as $group) { ?>
                                <tr>
                                    <td>
                                        <label class="control control__checkbox">
                                            <input type="checkbox" name="checkboxRow" class="singleCheckbox" value="<?php echo $group->user_id; ?>">
                                            <div class="control__indicator"></div>
                                        </label>
                                    </td>
                                    <td>
                                        <a class="link fontWeight--2" href="<?php echo base_url(); ?>customer-details-page?user_id=<?php echo $group->user_id; ?>"><?php echo $group->name; ?></a>
                                    </td>
                                    <td>
                                        <?php echo date('M d, Y', strtotime($group->user_createdAt)); ?>
                                    </td>
                                    <td>
                                        <?php echo $group->user_Role; ?>
                                    </td>
                                    <td>
                                        <?php echo ($group->user_status == 1) ? "Active" : "Inactive"; ?>
                                    </td>
                                </tr>
                            <?php } ?>
                                <?php } else { ?>
                                <tr>
                                    <td colspan="5">No User(s) Found</td>
                                </tr>
                                <?php } ?>
                            <!-- Single User -->
                        </tbody>
                    </table>
                    <!-- /Users -->
                    </div>
                    <hr>
                    <br>

                    <!-- Locations -->
                    <div class="heading__group border--dashed">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <h3>Locations</h3>
                            </div>
                            <div class="wrapper__inner align--right">
                                <button class="btn btn--tertiary btn--s modal--toggle" data-target="#addNewLocationOrganizationModal">New Location</button>
                            </div>
                        </div>
                    </div>
                    <?php if ($organization_location != null) { ?>
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
                                <!-- Single Location -->
                                <?php foreach ($organization_location as $group) { ?>
                                    <tr>
                                        <td>
                                            <?php echo $group->nickname; ?>
                                        </td>
                                        <td>
                                            <?php echo $group->address1 . "<br>" . $group->address2 . "<br>" . $group->city . "<br>" . $group->state, '-', $group->zip; ?>
                                        </td>
                                        <td>
                                            <?php echo $group->shipment; ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <!-- Single Location -->
                            </tbody>
                        </table>
                    <?php } else { ?>
                        <div class="well align--center">
                            This customer is not assigned to any locations.
                        </div>
                    <?php } ?>
                    <!--
                        NOTE: The following '.well' is the markup for the empty state for a user's assigned locations list.
                    -->
                    <!-- <div class="well align--center">
                        This customer is not assigned to any locations.
                    </div> -->

                    <!-- /Locations -->

                    <hr>

                    <!-- Notes -->
                    <div class="well bg--lightest-gray" style="max-height:400px;">
                        <div class="heading__group border--dashed wrapper">
                            <div class="wrapper__inner">
                                <h4>Notes</h4>
                            </div>
                            <div class="wrapper__inner align--right">
                                <button class="btn btn--tertiary btn--s modal--toggle AdminOrganizationIdNotes" data-organization_id="<?php echo $organization_id ?>" data-target="#organizationNoteModal">Add Note</button>
                            </div>
                        </div>
                        <ul class="list list--activity fontSize--s" style="max-height:400px;">

                            <!-- Single Note -->
                            <?php if ($customer_note != null) { ?>
                                <?php foreach ($customer_note as $notes) { ?>
                                    <li class="item item--note">
                                        <div class="col col--2-of-12 wrapper">
                                            <div class="wrapper__inner align--center padding--m no--pad-tb">
                                                <div class="entity__group">
                                                    <?php if ($notes->photo != null) { ?>
                                                        <div class="avatar avatar--s" style="background-image:url('<?php echo image_url(); ?>uploads/user/profile/<?php echo $notes->photo; ?>'); margin:0 0 8px 0;"></div>
                                                    <?php } else { ?>
                                                        <div class="avatar avatar--s" style="background-image:url('<?php echo image_url(); ?>assets/img/ph-avatar.jpg'); margin:0 0 8px 0;"></div>
                                                    <?php } ?>
                                                    <span class="disp--block fontWeight--2"><?php echo $notes->first_name; ?></span>
                                                </div>
                                                <span class="fontSize--xs"><?php echo date('M d, Y', strtotime($notes->created_at)); ?></span>
                                            </div>
                                            <div class="col col--10-of-12 wrapper__inner">
                                                <div class="well card" style="min-height: 85px;">
                                                    <?php echo $notes->message; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                <?php } ?>
                            <?php } ?>
                            <!-- /Single Note -->

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

<!-- Modals -->
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/edit-organization.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/new-user.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/new-location-organization.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/organization-note.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/organization-user-activation.php'); ?>
<?php //include(INCLUDE_PATH . '/_inc/shared/modals/organization-user-deactivation.php'); ?>

<?php $this->load->view('templates/_inc/shared/modals/edit-organization.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/new-user.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/new-location-organization.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/organization-note.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/organization-user-activation.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/organization-user-deactivation.php'); ?>


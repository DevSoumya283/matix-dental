<?php $this->load->view('templates/_inc/header-vendor.php'); ?>

<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>

    <section class="content__wrapper has--sidebar-l">
        <div class="content__main">
            <div class="row row--full-height">

                <!-- Sidebar -->
                <div class="sidebar col col--2-of-12" style="padding: 12px;">
                    <?php $this->load->view('templates/vendor-admin/_inc/nav.php'); ?>
                </div>
                <!-- /Sidebar -->

                <!-- Content Area -->
                <div class="content col col--9-of-12 col--push-1-of-12">
                    <!-- Promo Codes -->
                    <div class="heading__group border--dashed">
                        <div class="wrapper">
                            <div class="wrapper__inner">
                                <h3>Users</h3>
                            </div>
                            <div id="controlsShipping" class="contextual__controls wrapper__inner align--right">
                                <button class="btn btn--s btn--tertiary contextual--hide modal--toggle" data-target="#inviteUserModal">Add New User</button>
                                <ul class="list list--inline list--divided is--contextual is--off">
                                    <li class="item">
                                        <a class="link fontWeight--2 fontSize--s modal--toggle is--contextual is--off is--neg modal--toggle" data-target="#deleteUserModal">Delete User</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div style="overflow: hidden; overflow-x: scroll;">
                    <table class="table" data-controls="#controlsShipping">
                        <thead>
                            <tr>
                                <th width="3%">
                                    <label class="control control__checkbox">
                                        <input type="checkbox" class=" is--selector" id="selectAll">
                                        <div class="control__indicator"></div>
                                    </label>
                                </th>
                                <th>
                                    User
                                </th>
                                <th>
                                    Email Address
                                </th>
                                <th>
                                    Created
                                </th>
                                <th>
                                    Role
                                </th>
                                <th width="10%">
                                    &nbsp;
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Single Promo -->
                            <?php foreach ($My_vendor_users as $row) { ?>
                                <tr>
                                    <td>
                                        <?php
                                        $user_id = $_SESSION['user_id'];
                                        if ($user_id == $row->user_id) {
                                            ?>

                                        <?php } else { ?>
                                            <label class="control control__checkbox">
                                                <input type="checkbox" name="checkboxRow" class="singleCheckbox" value="<?php echo $row->user_id; ?>">
                                                <div class="control__indicator"></div>
                                            </label>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <div class="entity__group">
                                            <?php if ($row->Image != null) { ?>
                                                <div class="avatar avatar--s" style="background-image:url('<?php echo image_url(); ?>uploads/user/profile/<?php echo $row->Image->photo; ?>');"></div>
                                            <?php } else { ?>
                                                <div class="avatar avatar--s" style="background-image:url('<?php echo image_url(); ?>assets/img/avatar-default.png');"></div>
                                            <?php } ?>
                                            <?php
                                            $user_id = $_SESSION['user_id'];
                                            if ($user_id == $row->user_id) {
                                                echo $row->name . "  &nbsp;(You)";
                                            } else {
                                                echo $row->name;
                                            }
                                            ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php echo $row->email; ?>
                                    </td>
                                    <td>
                                        <?php echo date('M d, Y', strtotime($row->created_at)); ?>
                                    </td>
                                    <td>
                                        <?php echo $row->role; ?>
                                    </td>
                                    <td class="align--center">
                                        <button class="btn btn--s btn--primary btn--icon modal--toggle" data-target="#editUserModal<?php echo $row->id; ?>"><svg class="icon icon--settings"><use xlink:href="#icon-settings"></use></svg></button>
                                    </td>
                                </tr>
                            <?php } ?>
                            <!-- Single Promo -->
                        </tbody>
                    </table>
                    </div>
                    <!-- /Promo Codes -->

                </div>
                <!-- /Content Area -->
            </div>
        </div>
    </section>
</div>
<!-- /Content Section -->

<!-- Modals -->
<?php $this->load->view('templates/_inc/shared/modals/delete-user.php'); ?>

<?php
$this->load->view('templates/_inc/footer-vendor.php'); ?>

<!-- Content Section -->
<div class="overlay__wrapper">
    <div class="overlay overlay__browse" data-target="#browseDropdown"></div>
    <!-- Breadcrumbs Bar -->
    <div class="bar padding--xs bg--lightest-gray">
        <div class="wrapper wrapper--fixed">
            <ul class="list list--inline list--breadcrumbs">
                <li class="item">
                    <a class="link" href="<?php echo base_url('dashboard') ?>">Account</a>
                </li>
                <li class="item is--active">
                    Manage Users
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breadcrumbs Bar -->
    <?php
    $tier_1_2ab = unserialize(ROLES_TIER1_2_AB);
    $tier1 = unserialize(ROLES_TIER1);
    $tier2 = unserialize(ROLES_TIER2);
    ?>
    <!-- Main Content -->
    <section class="content__wrapper wrapper--fixed">
        <div class="content__main">
            <div class="content">
                <div class="heading__group wrapper border--dashed padding--s no--pad-lr no--pad-t">
                    <div class="wrapper__inner">
                        <h3 class="disp--ib margin--xs no--margin-tb no--margin-l">Users</h3>
                        <?php if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $tier1)) || (in_array($_SESSION['role_id'], $tier2)))) { ?>
                            <button class="btn btn--tertiary btn--s modal--toggle get_user_locations" data-target="#addOrganizationUserModal">Add New</button>
                        <?php } ?>
                    </div>
                    <div class="wrapper__inner align--right">
                        <form name="manage_users_form" method="post" action="<?php echo base_url(); ?>Manage-Users" style="display:inline;">
                            <div class="select select--text margin--s no--margin-tb no--margin-l">
                                <label class="label">Order by</label>
                                <select name="order" aria-label="Select a Sorting Option" onchange="document.manage_users_form.submit();">
                                    <option value="name" <?php echo ($order_by == "name") ? "selected" : "" ?>>Alphabetical</option>
                                    <option value="state" <?php echo ($order_by == "state") ? "selected" : "" ?>>State</option>
                                    <option value="spend" <?php echo ($order_by == "spend") ? "selected" : "" ?>>Total Spend</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
                <table class="table table-responsive">
                    <thead>
                        <tr>
                            <th width="27%">
                                Name
                            </th>
                            <th width="12%">
                                Role
                            </th>
                            <th width="18%">
                                Email Address
                            </th>
                            <th width="20%">
                                Location(s)
                            </th>
                            <th width="9%">
                                Status
                            </th>
                            <th width="12%">
                                Created
                            </th>
                            <?php if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $tier1)))) { ?>
                                <th width="2%">
                                    &nbsp;
                                </th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- User -->
                        <?php if ($user_details != null) { ?>
                            <?php foreach ($user_details as $details) { ?>
                                <tr>
                                    <td>
                                        <div class="entity__group namewidth">
                                            <?php if ($details->photo != null) { ?>
                                                <div class="avatar avatar--s" style="background-image:url('<?php echo image_url(); ?>uploads/user/profile/<?php echo $details->photo; ?>');"></div>
                                            <?php } else { ?>
                                                <div class="avatar avatar--s" style="background-image:url('<?php echo image_url(); ?>assets/img/avatar-default.png');"></div>
                                            <?php } ?>
                                            <?php echo $details->first_name; ?><?php echo ($details->id == $_SESSION['user_id']) ? " (You)" : ""; ?>
                                        </div>
                                    </td>
                                    <td>
                                       <div style="width: 150px;"> <?php echo $details->role_name; ?></div>
                                    </td>
                                    <td>
                                        <?php echo $details->email; ?>
                                    </td>
                                    <td>
                                       <div style="width: 150px;"> 
 <?php echo $details->nickname; ?>
                                        <span class="textColor--dark-gray">
                                        <?php if ($details->count > 1) { ?>
                                            + <?php echo ($details->count-1); ?> more
                                        <?php } else {
                                            echo ($details->nickname == null) ? "None Assigned" : "";
                                         } ?></span>
                                       </div>
                                    </td>
                                    <td>
                                        <?php echo ($details->status == '1') ? "Active" : "Inactive"; ?>
                                    </td>
                                    <td>
                                         <div style="width: 100px;"> 
                                        <?php echo date('M d/y', strtotime($details->created_at)); ?>
                                         </div>
                                    </td>
                                    <?php if ($details->id != $_SESSION['user_id']) { ?>
                                        <?php if (isset($_SESSION['role_id']) && ((in_array($_SESSION['role_id'], $tier1)))) { ?>
                                            <td>
                                                <button class="btn btn--s btn--tertiary btn--link btn--icon modal--toggle" data-target="#editOrganizationUserModal<?php echo $details->id; ?>"><svg class="icon icon--settings"><use xlink:href="#icon-settings"></use></svg></button>
                                            </td>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                        <!-- /User -->
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <!-- /Main Content -->
</div>
<!-- /Content Section -->
<!-- Modals -->
<?php $this->load->view('templates/_inc/shared/modals/add-organization-user.php'); ?>
<?php $this->load->view('templates/_inc/shared/modals/edit-organization-user.php'); ?>

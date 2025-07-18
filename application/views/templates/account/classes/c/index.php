
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
                    <a class="link" href="<?php echo base_url('classes'); ?>">Manage Classes</a>
                </li>
                <li class="item is--active">
                    <?php echo $class_name->class_name; ?>
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breadcrumbs Bar -->

    <!-- Main Content -->
    <section class="content__wrapper wrapper--fixed has--sidebar-l sidebar--no-fill">
        <div class="content__main">
            <div class="row row--full-height">

                <!-- Sidebar -->
                <div class="sidebar col col--3-of-12 bg--white">
                    <!-- Class Info -->
                    <div class="sidebar__group" style="padding-right:32px;">
                        <h3><?php echo $class_name->class_name; ?> <a class="link fontSize--s fontWeight--2 modal--toggle class-rename" data-id="<?php echo $class_name->id; ?>" data-classname="<?php echo $class_name->class_name; ?>" data-target="#renameClassModal">Rename</a></h3>
                    </div>
                    <!-- /Class Info -->

                    <!-- Class Tabs -->
                    <div class="sidebar__group">
                        <div class="tab__group is--vertical" data-target="#classContent">
                            <label class="tab state--toggle" value="students">
                                <input type="radio" name="classTabs" checked>
                                <span><a class="link">Students</a></span>
                            </label>
                            <?php if ($product_details != null) { ?>
                                <label class="tab state--toggle has--badge" value="pending" data-badge="<?php echo count($product_details); ?>">
                                <?php } ?>
                                <input type="radio" name="classTabs">
                                <span><a class="link">Pending Orders</a></span>
                            </label>
                        </div>
                    </div>
                    <!-- /Class Tabs -->
                </div>
                <!-- /Sidebar -->

                <!-- Content -->

                <div id="classContent" class="content col col--8-of-12 col--push-1-of-12">
                    <div id="tabStudents" class="page__tab">
                        <div class="heading__group border--dashed padding--s no--pad-lr no--pad-t wrapper">
                            <div class="wrapper__inner">
                                <h4>Manage Students</h4>
                            </div>
                            <div id="controlsStudents" class="contextual__controls wrapper__inner align--right">
                                <button class="btn btn--tertiary btn--s modal--toggle contextual--hide" data-target="#assignUsersModal">Assign Student(s)</button>
                                <ul class="list list--inline list--divided margin--s no--margin-tb no--margin-r is--contextual is--off">
                                    <li class="item">
                                        <a class="link modal--toggle unassign_students" data-target="#unassignSelectedUsersModal">Unassign Selected</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <?php if ($students_details != null) { ?>
                            <table class="table" data-controls="#controlsStudents">
                                <thead>
                                    <tr>
                                        <th width="5%">
                                            <label class="control control__checkbox">
                                                <input type="checkbox" class=" is--selector" id="selectAll">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </th>
                                        <th width="50%">Name
                                        </th>
                                        <th width="25%">Role
                                        </th>
                                        <th width="15%">&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Single User -->
                                <input type="hidden" name="students" id="user_id" class="class_ids" value="">
                                <?php
                                foreach ($students_details as $key) {
                                    foreach ($key as $row) {
                                        ?>
                                        <tr>
                                            <td>
                                                <label class="control control__checkbox">
                                                    <input type="checkbox" name="checkboxRow" class="singleCheckbox" value="<?php echo $row->id; ?>">
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </td>
                                            <td>
                                                <div class="entity__group">
                                                    <?php if ($row->photo != null) { ?>
                                                        <div class="avatar avatar--s" style="background-image:url('<?php echo image_url(); ?>uploads/user/profile/<?php echo $row->photo; ?>');"></div>
                                                    <?php } else { ?>
                                                        <div class="avatar avatar--s" style="background-image:url('<?php echo image_url(); ?>assets/img/avatar-default.png');"></div>
                                                    <?php } ?>
                                                    <div class="text__group">
                                                        <?php echo $row->first_name; ?>

                                                    </div>
                                                </div>

                                            </td>
                                            <td>
                                                <?php echo $row->role_tier; ?>
                                            </td>
                                            <td class="align--center">
                                                <button class="btn btn--s btn--secondary btn--icon modal--toggle unassign-student" data-id="<?php echo $row->id; ?>" data-student="<?php echo $row->student_id; ?>" data-target="#unassignStudentModal"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></button>
                                            </td>
                                        </tr>
                                    <?php }
                                }
                                ?>
                                <!-- /Single User -->
                                </tbody>
                            </table>
<?php } ?>
                    </div>

                    <div id="tabRequests" class="page__tab">
                        <div class="heading__group border--dashed padding--s no--pad-lr no--pad-t wrapper">
                            <div class="wrapper__inner">
                                <h4>Orders Pending Approval</h4>
                            </div>
                        </div>
                        <?php
                        if ($product_details != null) {
                            for ($i = 0; $i < count($product_details); $i++) {
                                $date = $product_details[$i]->created_at;
                                $create_date = date("d. M ,Y", strtotime($date));
                                ?>
                                <!-- Single (Pending) -->

                                <div class="order well card">
                                    <div class="heading__group wrapper border--dashed padding--s no--pad-lr no--pad-t">
                                        <div class="wrapper__inner">
                                            <h4 class="textColor--darkest-gray">Order <?php print_r($product_details[$i]->id); ?> (Pending)</h4>
                                        </div>
                                        <div class="wrapper__inner align--right">
                                            <button class="btn btn--s btn--primary is--link" data-target="<?php echo base_url(); ?>view-pending?id=<?php echo $product_details[$i]->id; ?>">View Order</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="order__info col col--10-of-12 col--am">
                                            <ul class="list list--inline list--stats list--divided">
                                                <li class="item">
        <?php if ($product_details[$i]->photo) { ?>

                                                        <div class="avatar avatar--m" style="background-image:url('<?php echo image_url(); ?>uploads/user/profile/<?php echo $product_details[$i]->photo; ?>');"></div>
        <?php } else { ?>

                                                        <div class="avatar avatar--m" style="background-image:url('<?php echo image_url(); ?>assets/img/avatar-default.png');"></div>
        <?php } ?>
                                                </li>
                                                <li class="item item--stat stat-s">
                                                    <div class="text__group">
                                                        <span class="line--main"><?php print_r($product_details[$i]->first_name); ?></span>
                                                        <span class="line--sub">Student</span>
                                                    </div>
                                                </li>
                                                <li class="item item--stat stat-s">
                                                    <div class="text__group">
                                                        <span class="line--main"><?php echo $create_date; ?></span>
                                                        <span class="line--sub">Order Date</span>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="order__btn col col--2-of-12 col--am align--right">
                                            <ul class="list list--inline list--stats">
                                                <li class="item item--stat">
                                                    <div class="text__group">
                                                        <span class="line--main font">$<?php echo number_format($product_details[$i]->total, 2, '.', ','); ?></span>
                                                        <span class="line--sub">Order Total</span>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div> <br>
                                <!-- /Single Order (Pending) -->
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <!-- /Content -->

        </div>

    </section>
    <!-- /Main Content -->

</div>
<!-- /Content Section -->

<!-- Modals -->
<?php include(INCLUDE_PATH . '/_inc/shared/modals/assign-students.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/unassign-student.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/unassign-selected-users.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/approve-all-item-request.php'); ?>
<?php include(INCLUDE_PATH . '/_inc/shared/modals/rename-class.php'); ?>

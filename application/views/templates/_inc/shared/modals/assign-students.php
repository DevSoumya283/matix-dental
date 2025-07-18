<!-- Assign Students Modal -->
<div id="assignUsersModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition no--pad">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x page__reloadLocation"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left padding--l">
            <h2>Assign students to Classes</h2>
            <form action="">
                <div class="input__group input__group--inline">

                    <input id="student_search" class="input input__text" type="search"  data-class_id="<?php echo $class_id; ?>" value="" placeholder="Search by name..." name="search" required>
                    <input type="hidden" name="id" value="<?php echo $class_id; ?>" id="class_id">
                    <label for="site-search" class="label">
                        <svg class="icon icon--search textColor--gray"><use xlink:href="#icon-search"></use></svg>
                    </label>
                </div>
            </form>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content padding--l bg--lightest-gray assign_students">
                <div id="student_results">
                    <?php if ($select_students != null) { ?>
                        <ul class="add-users">
                            <?php foreach ($select_students as $students) { ?>
                                <li class="user padding--s no--pad-l no--pad-r cf">
                                    <div class="entity__group">
                                        <?php if ($students->model_name != null) { ?>
                                            <div class="avatar avatar--s" style="background-image:url('<?php echo image_url(); ?>uploads/user/profile/<?php echo $students->model_name; ?>')"></div>
                                        <?php } else { ?>
                                            <div class="avatar avatar--s" style="background-image:url('<?php echo image_url(); ?>assets/img/avatar-default.png');"></div>
                                        <?php } ?>
                                        <?php echo $students->first_name; ?>
                                    </div>
                                    <button class="btn btn--s btn--tertiary btn--toggle float--right width--fixed-75 adding_Students"  data-before="Select" data-after="&#10003;" type="button" value="<?php echo $students->student_id; ?>"></button>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php } else { ?>
                        <ul class="add-users">
                            <li>
                                No Students to Add
                            </li>
                        </ul>
                    <?php } ?>
                </div>
            </div>
            <div class="modal__footer padding--l">
                <p class="fontSize--m textColor--darkest-gray">Create New Student</p>
                <form id="formName" class="form__group" action="<?php echo base_url(); ?>create-student-forOrganization" method="post">
                    <div class="wrapper">
                        <div class="input__group is--inline wrapper__inner padding--xs no--pad-t no--pad-b no--pad-l">
                            <input id="accountEmail" name="accountName" class="input" type="text" placeholder="Student Name">
                            <!--                            <label class="label" for="accountName">Student Name</label>-->
                        </div>
                        <div class="input__group is--inline wrapper__inner padding--xs no--pad-t no--pad-b no--pad-l">
                            <input id="accountEmail" name="accountEmail" class="input" type="email" placeholder="Email Address" pattern=".*\S.*">
                            <!--                            <label class="label" for="accountEmail">Email Address</label>-->
                            <input  type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                        </div>
                        <div class="wrapper__inner">
                            <button class="btn btn--s btn--tertiary btn--toggle float--right width--fixed-75 student_create" data-before="Select" data-after="&#10003;" type="button"></button>
                        </div>
                    </div>
                    <div class="footer__group border--dashed">
                        <button class="btn btn--m btn--primary btn--block save--toggle form--submit" data-target="#formName" type="submit">Add Student(s)</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Assign Students Modal -->

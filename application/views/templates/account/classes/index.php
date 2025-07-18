
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
                <li class="item is--active">
                    Manage Classes
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breadcrumbs Bar -->
    <!-- Main Content -->
    <section class="content__wrapper wrapper--fixed">
        <div class="content__main">
            <div class="content">

                <div class="heading__group wrapper border--dashed padding--s no--pad-lr no--pad-t">
                    <div class="wrapper__inner">
                        <h3 class="disp--ib margin--xs no--margin-tb no--margin-l">Classes</h3>
                    </div>
                    <div class="wrapper__inner align--right">
                        <button class="btn btn--tertiary btn--m modal--toggle" data-target="#addNewClassModal">Add New Class</button>
                    </div>
                </div>

                <!-- Class List -->
                <div id="classList">
                    <!-- Single Class -->

                    <?php
                    if ($classes != null) {
                        for ($i = 0; $i < count($classes); $i++) {
                            $date = $classes[$i]->created_at;
                            $create_date = date("d. M ,Y", strtotime($date));
                            ?>
                            <div class="card well wrapper padding--s">
                                <div class="wrapper__inner">
                                    <ul class="list list--inline list--divided list--stats">
                                        <li class="location__requests item margin--s no--margin-tb no--margin-l">                                   
                                            <button class="btn btn--tertiary btn--m btn--icon btn--circle has--badge is--link" data-badge="<?php echo $classes[$i]->order_count; ?>" data-target="<?php echo base_url(); ?>students?id=<?php echo $classes[$i]->id; ?>"><svg class="icon icon--list-m"><use xlink:href="#icon-list-m"></use></svg></button>                                     
                                        </li>
                                        <li class="item item--stat">
                                            <div class="text__group">
                                                <span class="line--main truncate"><?php echo $classes[$i]->class_name; ?> </span>
                                                <span class="line--sub">Created: <?php echo $create_date; ?></span>
                                            </div>
                                        </li>


                                        <li class="item item--stat">

                                            <div class="text__group">
                                                <?php if ($classes[$i]->students > 0) { ?>
                                                    <span class="line--main"> <?php echo $classes[$i]->students; ?> Students</span>
                                                    <span class="line--sub">
                                                        <a class="link" href="<?php echo base_url(); ?>students?id=<?php echo $classes[$i]->id; ?>">Manage Students</a>
                                                    </span>
                                                <?php } else { ?>
                                                    <span class="line--main"> No Student</span>

                                                <?php } ?>
                                            </div>

                                        </li>

                                    </ul>
                                </div>
                                <div class="wrapper__inner align--right">
                                    <button class="btn btn--primary btn--s is--link" data-target="<?php echo base_url(); ?>students?id=<?php echo $classes[$i]->id; ?>">View Class</button>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                    <!-- /Single Class -->

                    <!-- Empty State -->
                    <!-- <div class="well wrapper border--dashed padding--s">
                        <a class="link fontWeight--2 fontSize--s">+ Add a new class</a>
                    </div> -->
                    <!-- /Empty State -->

                </div>
                <!-- /Class List -->

            </div>
        </div>
    </section>
    <!-- /Main Content -->

</div>
<!-- /Content Section -->

<!-- Modals -->
<?php include(INCLUDE_PATH . '/_inc/shared/modals/new-class.php'); ?>


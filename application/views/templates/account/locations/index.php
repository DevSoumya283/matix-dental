<!-- Content Section -->
<style type="text/css">
.card{flex-direction:inherit;}
</style>
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
                    Manage Locations
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breadcrumbs Bar -->
    <!-- Main Content -->
    <section class="content__wrapper wrapper--fixed">
        <div class="content__main">
            <div class="content">
                <div class="heading__group row border--dashed padding--s no--pad-lr no--pad-t">
                    <div class="col-md-6 col-xs-12 mobile-center">
                        <h3 class="disp--ib margin--xs no--margin-tb no--margin-l">Your Locations</h3>
                        <?php if ($_SESSION['role_id'] == '3' || $_SESSION['role_id'] == '7') { ?>
                        <button class="btn btn--tertiary btn--m modal--toggle" data-target="#addNewLocationModal">Add New</button>
                        <?php } ?>
                    </div>
                    <div class="col-md-6 col-xs-12 lg-rightalign mobile-center">
                        <div class="select select--text margin--s no--margin-tb no--margin-l">
                            <label class="label">Order by</label>
                            <form name="sorting_locations" method="post" action="<?php echo base_url(); ?>locations" style="display: inline;">
                                <input type="hidden" id="data_view" name="data_view" value="<?php echo $data_view ?>" />
                                <select name="sortBy" aria-label="Select a Sorting Option" onchange="document.sorting_locations.submit();">
                                    <option <?php echo ($sort_field == "name") ? "selected" : "" ?> value="name">Alphabetical</option>
                                    <option <?php echo ($sort_field == "state") ? "selected" : "" ?> value="state">State</option>
                                    <option <?php echo ($sort_field == "spend_total") ? "selected" : "" ?> value="spend_total">Total Spend</option>
                                </select>
                            </form>
                        </div>
                        <div class="tab__group" data-target="#locationsList">
                            <label class="tab select_view" data-view="list" value="is--list-view">
                                <input type="radio" name="groupName" <?php echo ($data_view == "list") ? "checked" : "" ?>>
                                <span>List View</span>
                            </label>
                            <label class="tab select_view" data-view="grid" value="is--grid-view">
                                <input type="radio" name="groupName" <?php echo ($data_view == "grid") ? "checked" : "" ?>>
                                <span>Grid View</span>
                            </label>
                        </div>
                    </div>
                </div>
                <!-- Locations List -->
                <div id="locationsList" class="is--<?php echo $data_view ?>-view cf">
                    <?php
                    if ($user_location != null) {
                        for ($i = 0; $i < count($user_location); $i++) {
                            if (isset($user_location[$i])) {
                                ?>
                                <!-- Single Location -->
                                <div class="location card well wrapper padding--s row">

                                    <div class="location__map" data-address="<?php
                                    if($user_location[$i]->address1!=null && $user_location[$i]->address1 != ""){
                                        echo $user_location[$i]->address1 . ", ";
                                    }
                                    if($user_location[$i]->address2!=null && $user_location[$i]->address2 != ""){
                                        echo $user_location[$i]->address2 . ", ";
                                    }
                                    if($user_location[$i]->city!=null && $user_location[$i]->city != ""){
                                        echo $user_location[$i]->city . ", ";
                                    }
                                    if($user_location[$i]->state!=null && $user_location[$i]->state!=""){
                                     echo $user_location[$i]->state . " ";
                                 }
                                 echo $user_location[$i]->zip;
                                 ?>"></div>
                                 <div class="location__info wrapper__inner col-md-5 col-xs-12">
                                    <ul class="list list--inline list--divided list--stats">
                                        <li class="location__requests item margin--s no--margin-tb no--margin-l">
                                            <?php if (isset($user_location[$i]->item_count) && ($user_location[$i]->item_count) > 0) { ?>
                                            <button class="btn btn--tertiary btn--m btn--icon btn--circle has--badge is--link" data-badge="<?php echo $user_location[$i]->item_count; ?>" data-target="<?php echo base_url(); ?>cart?id=<?php echo $user_location[$i]->id; ?>">
                                                <svg class="icon icon--cart-s"><use xlink:href="#icon-cart-s"></use></svg></button>
                                                <?php
                                            } else {
                                                ?>
                                                <button class="btn btn--tertiary btn--m btn--icon btn--circle has--badge is--link" data-badge="0" data-target="#">
                                                    <svg class="icon icon--cart-s"><use xlink:href="#icon-cart-s"></use></svg></button>
                                                    <?php } ?>
                                                </li>
                                                <li class="item item--stat">
                                                    <div class="text__group">
                                                        <span class="line--main truncate"><?php echo $user_location[$i]->nickname; ?>
                                                            <a class="location__link link" href="<?php echo base_url(); ?>details?location_id=<?php echo $user_location[$i]->id; ?>">View</a></span>

                                                            <span class="line--sub"><?php
                                                            if($user_location[$i]->address1!=null && $user_location[$i]->address1 != ""){
                                                                echo $user_location[$i]->address1 . ", ";
                                                            }
                                                            if($user_location[$i]->address2!=null && $user_location[$i]->address2 != ""){
                                                                echo $user_location[$i]->address2 . ", ";
                                                            }
                                                            if($user_location[$i]->city!=null && $user_location[$i]->city != ""){
                                                                echo $user_location[$i]->city . ", ";
                                                            }
                                                            if($user_location[$i]->state!=null && $user_location[$i]->state!=""){
                                                             echo $user_location[$i]->state . " ";
                                                         }
                                                         echo $user_location[$i]->zip;
                                                         ?></span>
                                                     </div>
                                                 </li>
                                             </ul>
                                         </div>
                                         <div class="location__more wrapper__inner col-md-5 col-xs-12">
                                            <ul class="list list--inline list--divided list--stats state stateslist">
                                                <li class="item item--stat">
                                                    <div class="text__group">
                                                        <span class="line--main">
                                                            <?php
                                                            if (isset($user_location[$i]->request_count) && ($user_location[$i]->request_count) > 0) {
                                                                echo $user_location[$i]->request_count . " Items";
                                                                ?>
                                                                <?php
                                                            } else {
                                                                echo "-";
                                                            }
                                                            ?>
                                                        </span>
                                                        <span class="line--sub">
                                                            <a class="link" href="<?php echo base_url(); ?>request-products?id=<?php echo $user_location[$i]->id; ?>">View Requests</a>
                                                        </span>
                                                    </div>
                                                </li>
                                                <li class="item item--stat">
                                                    <div class="text__group">
                                                        <span class="line--main">
                                                            <?php
                                                            if (isset($user_location[$i]->inventory_count) && ($user_location[$i]->inventory_count) > 0) {
                                                                echo $user_location[$i]->inventory_count . " Items";
                                                                ?>
                                                                <?php
                                                            } else {
                                                                echo "-";
                                                            }
                                                            ?>
                                                        </span>
                                                        <span class="line--sub">
                                                            <a class="link" href="<?php echo base_url(); ?>inventory?location_id=<?php echo $user_location[$i]->id; ?>">Manage Inventory</a>

                                                        </span>
                                                    </div>
                                                </li>
                                                <li class="item item--stat">
                                                    <div class="text__group">
                                                        <span class="line--main">
                                                            <?php
                                                            if ($user_location[$i]->user_count > 0) {
                                                                echo $user_location[$i]->user_count . " User" . "<br>";
                                                                ?>
                                                                <?php
                                                            } else {
                                                                echo "-";
                                                            }
                                                            ?>
                                                        </span>
                                                        <span class="line--sub">
                                                            <a class="link" href="<?php echo base_url(); ?>users?location_id=<?php echo $user_location[$i]->id; ?>">Manage Users</a>

                                                        </span>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="location__spend wrapper__inner align--right  col-md-2 col-xs-12">
                                            <ul class="list list--inline list--stats spend">
                                                <li class="item item--stat">
                                                    <div class="text__group">
                                                        <span class="line--main Yearly_total_<?php echo $user_location[$i]->id; ?>">
                                                            <?php
                                                            if (isset($user_location[$i]->order_total) && ($user_location[$i]->order_total) > 0) {
                                                                echo "$" . number_format($user_location[$i]->order_total, 2, '.', ',');
                                                            } else {
                                                                echo "-";
                                                            }
                                                            ?>
                                                        </span>
                                                        <span class="line--sub">Total Spend</span>
                                                        <div class="select select--text">
                                                            <select  name="user_location" data-user_location="<?php echo $user_location[$i]->id; ?>" aria-label="Select a Range" class="Yearly_Total">
                                                                <option value="0" selected>MTD</option>
                                                                <option value="1">YTD</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                        } else {
                            echo "No locations added.";
                        }
                        ?>
                        <!-- /Single Location -->
                    </div>
                    <!-- /Locations List -->
                </div>
            </div>
        </section>
        <!-- /Main Content -->
    </div>
    <!-- /Content Section -->

    <?php $this->load->view('templates/_inc/shared/modals/new-location.php'); ?>

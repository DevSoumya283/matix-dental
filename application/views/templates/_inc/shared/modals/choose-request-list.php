<!-- Choose Request List Modal -->
<style type="text/css">
    td.width--25.padding--xs.no--pad-t.no--pad-r.no--pad-l{display: none;}
</style>

<div id="chooseRequestListModal" class="modal modal--l">

    <div class="modal__wrapper modal__wrapper--transition padding--l no--pad-r no--pad-l">

        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>

        <div class="modal__header center center--h align--left mobile-center">

            <h2>Select Request List(s)</h2>

        </div>

        <div class="modal__body center center--h align--left cf">



            <div class="modal__content">

                <hr>



                <div class="padding--s no--pad-lr requestlist-div">

                    <table class="width--100">



                        <tr class="no_values">

                            <th class="width--20 padding--xs no--pad-t no--pad-r no--pad-l">

                                <span class="fontSize--s fontWeight--2">Name</span>

                            </th>
<!-- 
                            <th class="width--25 padding--xs no--pad-t no--pad-r no--pad-l">

                                <span class="fontSize--s fontWeight--2">Last Updated2</span>

                            </th> -->

                            <th class="width--15 padding--xs no--pad-t no--pad-r no--pad-l">

                                <span class="fontSize--s fontWeight--2">Items</span>

                            </th>

                            <th class="width--20 padding--xs no--pad-t no--pad-r no--pad-l">

                                <span class="fontSize--s fontWeight--2">Total Cost</span>

                            </th>

                            <th class="width--20 padding--xs no--pad-t no--pad-r no--pad-l"></th>

                        </tr>



                        <input type="hidden" name="location_id" class="locations" id="list--location" value="">

                        <input type="hidden" name="product_id" class="product" value="">

                        <input type="hidden" name="vendor_id" class="vendor" value="">

                        <input type="hidden" name="qty" class="quantity" value="">

                        <tbody class="request_data">



                        </tbody>

                    </table>

                </div>

                <span class="notempty">

                    <div class="row cf margin--s no--margin-r no--margin-b no--margin-l no-locations padding--xs">

                        <button type="button" class="btn btn--m btn--primary float--right default--action add_requests ">Done</button>

                    </div>

                </span>

                <?php

                $tier1 = unserialize(ROLES_TIER1);

                if (isset($_SESSION['user_id']) && (isset($_SESSION['role_id'])) && (in_array($_SESSION['role_id'], $tier1))) {

                    ?>

                    <span class="empty" style="display: none;">

                        <div class="row cf margin--s no--margin-r no--margin-b no--margin-l add_location">

                            <a class=" modal--toggle btn btn--m btn--primary float--right" data-target="#addNewLocationModal">Add Location</a>

                        </div>

                    </span>

                <?php } ?>

            </div>



        </div>

    </div>

    <div class="modal__overlay modal--toggle"></div>

</div>

<!-- /Choose Request List Modal -->

<?php include(INCLUDE_PATH . '/_inc/shared/modals/new-location.php'); ?>
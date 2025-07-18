<!-- Remove Selected Items Modal -->

<div id="removeInventoryItemsModal" class="modal modal--m">

    <div class="modal__wrapper modal__wrapper--transition padding--l">

        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>

        <div class="modal__header center center--h align--left">

            <h2 class="mobile-center">Remove selected items</h2>

            <p id="newUser" class="margin--m no--margin-r no--margin-t no--margin-l mobile-center">They will be removed from inventory until they are purchased again.</p>

        </div>





        <div class="modal__body center center--h align--left cf">

            <form  id="deleteLocationInventory" class="form__group" action="<?php echo base_url("remove-inventory"); ?>" method="post">

                <input type="hidden" name="user_id" id="user_id" value="" >



                <div class="modal__content">

                    <table class="table">

                        <tbody>

                            <tr>

                                <td>

                                    <ul class="list list--inline list--stats list--divided">

                                        <li class="item">

                                            <div class="text__group">

                                                <p class="no--margin textColor--darkest-gray locationName"></p>

                                            </div>

                                        </li>

                                        <li class="item item--stat stat-s">

                                            <div class="text__group">

                                                <p class="no--margin textColor--darkest-gray item_count">Items</p>

                                            </div>

                                        </li>

                                    </ul>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                    <div class="footer__group border--dashed">



                        <button class="btn btn--m btn--block  is--neg form--submit page--reload" data-target="#deleteLocationInventory" >Remove Selected Items</button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <div class="modal__overlay modal--toggle"></div>

</div>

<!-- /Remove Selected Items Modal


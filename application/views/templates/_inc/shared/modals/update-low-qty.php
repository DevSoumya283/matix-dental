<!-- Update Quantity Modal -->

<div id="updateThresholdModal" class="modal modal--m">

    <div class="modal__wrapper modal__wrapper--transition padding--l">

        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>

        <div class="modal__header center center--h align--left">

            <h2 class="fontSize--l mobile-center">Update Threshold Quantity</h2>

            <p class="margin--m no--margin-r no--margin-t no--margin-l mobile-center">This will update all the selected items.</p>

        </div>

        <form  id="inventoryLowqty" class="form__group" action="<?php echo base_url("update-lowqty"); ?>" method="post">

            <div class="modal__body center center--h align--left cf">

                <div class="modal__content">

                    <input type="hidden" name="update_id" id="user_id" value="" >

                    <input type="number" class="input input--qty not--empty width--100 low_qty" min="1" value="" name="qty">

                    <div class="footer__group border--dashed">

                        <button class="btn btn--m btn--primary btn--block save--toggle form--submit page--reload" data-target="#inventoryLowqty">Update Quantities</button>

                    </div>

                </div>

            </div>

        </form>

    </div>

    <div class="modal__overlay modal--toggle"></div>

</div>

<!-- /Update Quantity Modal -->
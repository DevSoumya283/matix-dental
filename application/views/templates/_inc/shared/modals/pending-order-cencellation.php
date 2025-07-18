<!-- Order Cancellation Modal -->
<div id="pendingCancellationModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Cancel this order?</h2>
            <p class="margin--m no--margin-r no--margin-t no--margin-l">To cancel this order, you must contact the seller directly.</p>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">
                <form  class="form__group" action="<?php echo base_url("cancel-restricted-orders"); ?>" method="post">
                    <div class="invoice margin--s no--margin-t no--margin-r no--margin-l">
                        <div class="inv__head no--pad row">
                            <div class="col col--3-of-8 col--am">
                                <img class="inv__logo" src="/assets/img/ph-vendor-logo.png" alt="">
                            </div>
                            <div class="col col--5-of-8 col--am align--right">
                                <span class="fontWeight--2 textColor--dark-gray">Order:</span>
                                <span class="fontWeight--2 restricted_id"></span>
                            </div>
                            <input type="hidden" name="restricted_id" class="restricted_id" value="">
                        </div>
                        <div class="inv__contact wrapper border--t" style="margin-bottom:0!important;padding:16px 0;">
                            <div class="wrapper__inner">
                                <ul class="list list--inline list--divided align--left disp--ib">
                                    <li class="locations">

                                    </li>
                                </ul>
                            </div>
                            <div class="wrapper__inner align--right">
                                <ul class="list list--inline list--divided">
                                    <li class="item">
                                        <span class="fontWeight--2">Phone:</span> <span class="phone"> <!-- 1 (800) 555-7845 --></span><br>
                                        <span class="fontWeight--2">Fax:</span > <span class="fax" > <!-- 1 (800) 555-7845 --> </span> <br>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="footer__group border--dashed">
                        <button class="btn btn--m btn--block is--neg default--action form--submit">Cancel Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Order Cancellation Modal

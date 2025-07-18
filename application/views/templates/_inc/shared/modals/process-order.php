<!-- Process Order Modal -->
<div id="processOrderModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Process Order</h2>
            <p>Enter the shipping details for this order. The customer will be notified of their udpated order status when you click "Submit."</p>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="well card bg--white">
                <ul class="list list--inline list--divided list--stats">
                    <li class="item item--stat">
                        <div class="text__group">
                            <span class="line--main"><?php echo $order_details->shipping_method; ?></span>
                            <span class="line--sub">Requested Shipping Method</span>
                        </div>
                    </li>
                    <li class="item item--stat">
                        <?php if ($order_address != null) { ?>
                            <div class="text__group">
                                <?php if($user_details!=null) { ?>
                                <span class="line--main"><?php echo $user_details->first_name; ?></span>
                                <?php } ?>
                                <span class="line--sub"><?php echo $order_address->address1; ?></span>
                                <span class="line--sub"><?php echo $order_address->address2; ?></span><br>
                                <span class="line--sub"><?php echo $order_address->city . " "; ?></span>
                                <span class="line--sub"><?php echo $order_address->state . " "; ?>,<?php echo $order_address->zip; ?></span>
                            </div>
                        <?php } ?>
                    </li>
                </ul>
            </div>
            <hr>
            <?php // echo ROOT_PATH . 'templates/vendor-admin/orders/o/processed'; ?>
            <form id="processOrderForm" class="form__group" action="<?php echo base_url(); ?>order-processed" method="post">
                <div class="modal__content">
                    <div class="row">
                        <div class="input__group is--inline">
                            <input type="hidden" id="order_id" name="order_id" value="<?php echo $order_id; ?>">
                            <input id="shipDate" name="shipDate" class="input input--date not--empty" value="<?php echo date('m/d/Y', strtotime($shipDate)); ?>" required>
                            <label class="label" for="shipDate">Ship Date</label>
                        </div>
                    </div>
                    <hr>
                    <h5 class="title">Tracking Number(s)</h5>
                    <div class="row">
                        <div class="col col--1-of-6 col--am fontWeight--2 fontSize--s">
                            Pkg #1
                        </div>
                        <div class="col col--5-of-6 col--am">
                            <div class="input__group is--inline">
                                <input id="shipment1Tracking" name="shipment1Tracking" class="input" type="text" required>
                                <label class="label" for="shipment1Tracking">Tracking #</label>
                            </div>
                        </div>
                    </div>
                    <br>
                    <h5 class="title">Additional Packages</h5>
                    <div class="row field__group">
                        <label class="control control__checkbox">
                            <input class="control__conditional" type="checkbox" data-target="#condAdditionalPkg">
                            <div class="control__indicator"></div>
                            Add additional packages &amp; tracking numbers
                        </label>
                        <div id="condAdditionalPkg" class="is--conditional starts--hidden">
                            <div class="row">
                                <div class="col col--1-of-6 col--am fontWeight--2 fontSize--s">
                                    Pkg #2
                                </div>
                                <div class="col col--5-of-6 col--am">
                                    <div class="input__group is--inline">
                                        <input id="shipment2Tracking" name="shipment2Tracking" class="input" type="text">
                                        <label class="label" for="shipment2Tracking">Tracking #</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col--1-of-6 col--am fontWeight--2 fontSize--s">
                                    Pkg #3
                                </div>
                                <div class="col col--5-of-6 col--am">
                                    <div class="input__group is--inline">
                                        <input id="shipment3Tracking" name="shipment3Tracking" class="input" type="text">
                                        <label class="label" for="shipment3Tracking">Tracking #</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col--1-of-6 col--am fontWeight--2 fontSize--s">
                                    Pkg #4
                                </div>
                                <div class="col col--5-of-6 col--am">
                                    <div class="input__group is--inline">
                                        <input id="shipment4Tracking" name="shipment4Tracking" class="input" type="text">
                                        <label class="label" for="shipment4Tracking">Tracking #</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col--1-of-6 col--am fontWeight--2 fontSize--s">
                                    Pkg #5
                                </div>
                                <div class="col col--5-of-6 col--am">
                                    <div class="input__group is--inline">
                                        <input id="shipment5Tracking" name="shipment5Tracking" class="input" type="text">
                                        <label class="label" for="shipment5Tracking">Tracking #</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="footer__group border--dashed">
                    <button class="btn btn--m btn--primary btn--block save--toggle form--submit" data-target="#processOrderForm">Submit</button>
                </div>
            </form>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Process Order Modal -->

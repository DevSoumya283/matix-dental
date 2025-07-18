<!-- Reorder Modal -->
<div id="returnsModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x close_return"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header wrapper margin--m no--margin-lr no--margin-t">
            <div class="wrapper__inner">
                <h2 class="no--margin">Return Item(s)</h2>
            </div>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">

                <!-- Reorder Process -->
                <div class="form form--multistep">
                    <!-- Step One Quantities -->
                    <form id="formReturns1" class="form__group" action="">
                        <h3 class="fontSize--l textColor--darkest-gray">1. Select Return Quantity</h3>

                        <table class="table margin--xs no--margin-t no--margin-lr" data-controls="#controlsRequests">
                            <tbody class="orders">
                                <!-- Item -->

                                <!-- /Item -->
                            </tbody>
                        </table>

                        <div class="row margin--l no--margin-b no--margin-lr">
                            <button class="btn btn--m btn--primary btn--block save--toggle form--submit go--next" data-target="#formReturns1" data-next="#formReturns2" type="submit">Next</button>
                        </div>
                    </form>
                    <!-- /Step One Quantities -->

                    <!-- Step Two Submit -->
                    <form id="formReturns2" class="form__group starts--hidden" action="" method="post">
                        <h3 class="fontSize--l textColor--darkest-gray">2. Reason for Return</h3>
                        <input type="hidden" name="order_id" class="order_id" value="">
                        <div class="row">
                            <div class="input__group is--inline">
                                <textarea name="reason" placeholder="Enter a short description as to why you're submitting this request..." class="input input--l input--show-placeholder" required></textarea>
                            </div>
                        </div>

                        <div class="wrapper margin--l no--margin-b no--margin-lr">
                            <div class="wrapper__inner">
                                <a class="link fontSize--s fontWeight--2 modal--toggle default--action">Back</a>
                            </div>
                            <div class="wrapper__inner align--right">
                                <button class="btn btn--m btn--primary save--toggle form--submit go--next returns" data-target="#formReturns2" data-next="#formReturns3" type="submit">Submit Return</button>
                            </div>
                        </div>
                    </form>
                    <!-- /Step Two Submit -->

                    <!-- Step Three Confirmation -->
                    <div id="formReturns3" class="form__group starts--hidden">
                        <h3 class="fontSize--l textColor--darkest-gray">Your request has been submitted!</h3>
                        <p>If you have any questions about the status of your request, please contact the vendor directly.</p>
                        <hr>
                        <div class="invoice margin--s no--margin-t no--margin-r no--margin-l">
                            <div class="inv__head no--pad row">
                                <div class="col col--3-of-8 col--am">
                                    <img class="inv__logo" src="/assets/img/ph-vendor-logo.png" alt="">
                                </div>
                                <div class="col col--5-of-8 col--am align--right">
                                    <span class="fontWeight--2 textColor--dark-gray">Return:</span>
                                    <span class="fontWeight--2 return_id"></span>
                                </div>
                            </div>
                            <div class="inv__contact wrapper border--t" style="margin-bottom:0!important;padding:16px 0;">
                                <div class="wrapper__inner">
                                    <ul class="list list--inline list--divided align--left disp--ib">
                                        <li class="item locations">

                                        </li>
                                    </ul>
                                </div>
                                <div class="wrapper__inner align--right">
                                    <ul class="list list--inline list--divided">
                                        <li class="item">
                                            <span class="fontWeight--2">Phone:</span> <span class="phone"></span><br>
                                            <span class="fontWeight--2">Fax:</span> <span class="fax"></span><br>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /Step Three Confirmation -->

                </div>
                <!-- /Reorder Process -->

            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Reorder Modal

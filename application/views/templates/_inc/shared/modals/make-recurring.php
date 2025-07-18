<!-- Make Recurring Order -->
<div id="makeRecurringModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left mobile-center">
            <h2>Make this a recurring order?</h2>
            <p class="margin--m no--margin-r no--margin-t no--margin-l modal-margin">Automatically reorder on a recurring basis. All the order details will remain the same.</p>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">
                <form id="makeRecurringForm" class="form__group modal-margin" action="<?php echo base_url("recurring-orders"); ?>" method="post" >
                    <div class="wrapper padding--s no--pad-lr no--pad-b border--1 border--dashed border--lightest border--t">
                        <input type="hidden" name="order_id" class="order_id" value="">
                        <div class="wrapper__inner width--50 recurring-start">
                            <div class="select select--text select--text-none">
                                <label class="label">Starting</label>
                                <div class="input__group input__group--date-range is--inline input-daterange input-daterange--single padding--xs no--pad-tb">
                                    <input type="text" name="recurring_date" class="input input--date not--empty" placeholder="MM/DD/YYYY"  style="width: 140px !important;" required>
                                </div>
                            </div>
                        </div>
                        <div class="wrapper__inner width--50">
                            <div class="select select--text">
                                <label class="label">How often?</label>
                                <select name="recurring" required>
                                    <option selected disabled>Make a Selection</option>
                                    <option value="Daily">Daily</option>
                                    <option value="Weekly">Weekly</option>
                                    <option value="Bi-weekly">Bi-Weekly</option>
                                    <option value="Monthly">Monthly</option>
                                    <option value="Quarterly">Quarterly</option>
                                    <option value="Yearly">Yearly</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="footer__group border--dashed">
                        <button class="btn btn--primary btn--m btn--block save--toggle form--submit" data-target="#makeRecurringForm">Save Recurring Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Make Recurring Order -->
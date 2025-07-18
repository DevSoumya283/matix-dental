<!-- Add New License Modal -->
<div id="addPricingScaleModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2 class="margin--m no--margin-lr no--margin-t">Create Pricing Scale</h2>
        </div>
        <div class="modal__body center center--h align--left cf">
            <form id="addPermissionForm" class="form__group" action="<?php echo base_url("add-buying-club"); ?>" method="post" >
                <div class="modal__content">
                    <input type="hidden" name="vendorId" id="vendorId" value="<?php echo $vendorId; ?>">
                    <div class="input__group is--inline margin--xs no--margin-lr no--margin-t">
                        <input id="name" name="name" class="input" type="text"  required>
                        <label class="label" for="name">Name</label>
                    </div>
                    <div class="input__group is--inline margin--xs no--margin-lr no--margin-t">
                        <input id="percentageDiscount" name="percentageDiscount" class="input" type="text"  required>
                        <label class="label" for="percentageDiscount">Percentage Discount</label>
                    </div>

                    <div class="center center--h footer__group">
                        <a href="#" class="btn btn--m btn--primary is--pos btn--dir is--next addPricingScale">Create Pricing Scale</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>


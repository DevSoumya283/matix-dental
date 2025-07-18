<!-- Create New Vendor Policy Modal -->
<div id="newVendorPolicyModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2 class="fontSize--l">Create new vendor policy</h2>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">
                <hr>
                <form id="newPolicyForm" class="form__group" action="<?php echo base_url(); ?>add-vendor-policy" method="post">
                    <div class="row">
                        <div class="input__group is--inline">
                            <input type="hidden" name="vendor_id" value="<?php echo $vendor_settings->id; ?>">
                            <input id="policyName" name="policyName" class="input" type="text" placeholder="e.g. Free Shipping" maxlength="30" required>
                            <label class="label" for="listName">Policy Name</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input__group is--inline">
                            <input id="policyDesc" name="policyDesc" class="input" type="text" placeholder="e.g. On orders over $35" maxlength="40" required>
                            <label class="label" for="listName">Description</label>
                        </div>
                    </div>
                    <div class="footer__group border--dashed">
                        <button class="btn btn--m btn--primary btn--block form--submit">Save List</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Create New Prepopulated List Modal -->

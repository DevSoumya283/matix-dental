<!-- New Customer Modal -->
<div id="addNewCustomerModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Create a New Customer</h2>
        </div>
        <!--         'templates/admin/customers/c/number'-->
        <div class="modal__body center center--h align--left cf">
            <form id="newCustomer" class="form__group modal__content" action="<?php echo base_url(); ?>create-newCustomer-Organization" method="post">
                <div class="row">
                    <div class="input__group is--inline">
                        <input id="accountName" name="accountName" class="input" type="text" placeholder="Kevin McCallister" required>
                        <label class="label" for="accountName">Full Name</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input__group is--inline">
                        <input id="accountEmail" name="accountEmail" class="input" type="email" placeholder="email@example.com" pattern=".*\S.*" required>
                        <label class="label" for="accountEmail">Email Address</label>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="select">
                        <select name="organization_name" aria-label="Select a Organization" class="customerSelection" required>
                            <option disabled selected value="default">Select Organization</option>
                            <?php if ($organizations != null) { ?>
                                <?php foreach ($organizations as $organ) { ?>
                                    <option value="<?php echo $organ->id . "||" . $organ->organization_type; ?>"><?php echo $organ->organization_name; ?></option>
                                <?php }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row customerCompany" style="display: none;">
                    <div class="select">
                        <select name="role_id" aria-label="Select a Title" required>
                            <option disabled="" selected="" value="default">Select Role</option>
                            <option value="3">Corporate Admin- Tier 1</option>
                            <option value="4">Purchasing Manager- Tier 2</option>
                            <option value="5">Office Manager- Tier 3A</option>
                            <option value="6">Office Assistant- Tier 3B</option>                        
                        </select>
                    </div>
                </div>
                <div class="row customerInstitution" style="display: none;">
                    <div class="select">
                        <select name="role_id" aria-label="Select a Title" required>
                            <option disabled="" selected="" value="default">Select Role</option>
                            <option value="7">Institution Admin- Tier 1</option>
                            <option value="8">Institution Director- Tier 2A</option>
                            <option value="9">Instructor- Tier 2B</option>
                            <option value="10">Student- Tier 2C</option>                        
                        </select>
                    </div>
                </div>
                <div class="footer__group border--dashed">
                    <button class="btn btn--m btn--primary btn--block save--toggle form--submit" data-target="#newCustomer" type="submit">Create Customer</button>
                </div>
            </form>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /New Customer Modal -->

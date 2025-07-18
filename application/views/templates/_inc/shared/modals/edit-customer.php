<!-- Edit Customer Modal -->
<div id="editCustomerModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Edit Customer</h2>
        </div>
        <div class="modal__body center center--h align--left cf">
            <form id="editCustomer" class="form__group modal__content" action="<?php echo base_url();?>customersection-edit-SAdmin" method="post">
                <input type="hidden" name="user_id" value="<?php echo $customer_report->id; ?>">
                <div class="row">
                    <div class="input__group is--inline">
                        <input id="accountName" name="accountName" class="input not--empty" type="text" placeholder="Kevin McCallister" value="<?php echo $customer_report->first_name; ?>" required>
                        <label class="label" for="accountName">Full Name</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input__group is--inline">
                        <input id="accountEmail" name="accountEmail" class="input not--empty" type="email" placeholder="email@example.com" value="<?php echo $customer_report->email; ?>" pattern=".*\S.*" required>
                        <label class="label" for="accountEmail">Email Address</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input__group is--inline">
                        <input id="accountPhone" name="accountPhone" class="input not--empty" type="text" placeholder="111-111-1111" value="<?php echo $customer_report->phone1; ?>" pattern=".*\S.*" required>
                        <label class="label" for="accountPhone">Phone Number</label>
                    </div>
                </div>
                <div class="row">
                        <div class="select">
                    <?php
                    $role_limit = 7;
                    if ($customer_report->role_id < $role_limit) {
                        ?>
                        <select name="role_id" aria-label="Select a Title" required>
                            <option disabled="" <?php echo ($customer_report->role_id==null)? "selected" : ""; ?> value="default">Select Role</option>
                            <option value="3" <?php echo ($customer_report->role_id==3)? "selected" : ""; ?>>Corporate Admin- Tier 1</option>
                            <option value="4" <?php echo ($customer_report->role_id==4)? "selected" : ""; ?>>Purchasing Manager- Tier 2</option>
                            <option value="5" <?php echo ($customer_report->role_id==5)? "selected" : ""; ?>>Office Manager- Tier 3A</option>
                            <option value="6" <?php echo ($customer_report->role_id==6)? "selected" : ""; ?>>Office Assistant- Tier 3B</option>
                        </select>
                    <?php } else { ?>
                        <select name="role_id" aria-label="Select a Title" required>
                            <option disabled="" <?php echo ($customer_report->role_id==null)? "selected" : ""; ?> value="default">Select Role</option>
                            <option value="7" <?php echo ($customer_report->role_id==7)? "selected" : ""; ?>>Institution Admin- Tier 1</option>
                            <option value="8" <?php echo ($customer_report->role_id==8)? "selected" : ""; ?>>Institution Director- Tier 2A</option>
                            <option value="9" <?php echo ($customer_report->role_id==9)? "selected" : ""; ?>>Instructor- Tier 2B</option>
                            <option value="10" <?php echo ($customer_report->role_id==10)? "selected" : ""; ?>>Student- Tier 2C</option>
                        </select>
                    <?php } ?>
                </div>
                </div>
                <hr>
                <div class="row">
                    <div class="input__group is--inline">
                        <input id="accountCompany" name="accountCompany" class="input not--empty" type="text" value="<?php echo $customer_report->organization_name; ?>" required>
                        <label class="label" for="accountCompany">Organization Name</label>
                    </div>
                </div>
                <div class="footer__group border--dashed">
                    <button class="btn btn--m btn--primary btn--block save--toggle form--submit page--reload" data-target="#editCustomer">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Edit Customer Modall -->

<!-- New Organization Modal -->
<div id="newOrganizationModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>New Organization</h2>
        </div>
        <div class="modal__body center center--h align--left cf">
            <form id="newOrganization" class="form__group modal__content" action="<?php echo base_url(); ?>new-organizations-register" method="post">
                <h5 class="title">Organization Info</h5>
                <div class="row">
                    <div class="input__group is--inline">
                        <input id="orgName" name="orgName" class="input" type="text" required>
                        <label class="label" for="orgName">Organization Name</label>
                    </div>
                </div>
                <div class="row">
                    <div class="select">
                        <select name="selection" required>
                            <option disabled selected value="default">Select Type</option>
                            <option value="1">Company</option>
                            <option value="2">School</option>
                        </select>
                    </div>
                </div>
                <br>
                <h5 class="title">Administrator</h5>
                <div class="row">
                    <div class="input__group is--inline">
                        <input id="orgName" name="first_name" class="input" type="text" placeholder="Admin Name" required>
                        <label class="label" for="orgAdmin">Admin Name</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input__group is--inline">
                        <input id="orgName" name="email" class="input" type="email" placeholder="Email address" required>
                        <label class="label" for="orgAdmin">Admin Email</label>
                    </div>
                </div>
                <div class="footer__group border--dashed">
                    <button class="btn btn--m btn--primary btn--block save--toggle form--submit" data-target="#newOrganization" type="submit">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Edit Customer Modall -->

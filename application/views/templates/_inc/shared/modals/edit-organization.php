<!-- Edit Organization Modal -->
<div id="editOrganizationModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2>Edit Organization</h2>
        </div>
        <div class="modal__body center center--h align--left cf">
            <form id="editOrganization" class="form__group modal__content" action="<?php echo base_url(); ?>organization-user-update" method="POST">
                <h5 class="title">Organization Info</h5>
                <div class="row">
                    <div class="input__group is--inline">
                        <input id="orgName" name="orgName" class="input not--empty" type="text" value="<?php echo $organization_details->organization_name; ?>" required>
                        <label class="label" for="orgName">Name</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input__group is--inline">
                        <input id="organizationType" name="organizationType" class="input not--empty" type="text" value="<?php echo ($organization_details->organization_type !=null) ? "$organization_details->organization_type" : ""; ?>" disabled>
                        <label class="label" for="organizationType">Organization Type</label>
                    </div>
                </div>
                <br>
                <h5 class="title">Administrator</h5>
                <div class="row">
                    <div class="input__group is--inline">
                        <input type="hidden" name="admin_user_id" value="" id="OrganizationUser_id">
                        <input type="hidden" name="organization_id" value="<?php echo $organization_details->id; ?>" id="Organization_id">
                        <input id="orgName" name="orgEmail" data-organization_id="<?php echo $organization_details->id; ?>" class="input not--empty UserchangeWithin" type="email" placeholder="Email address or name" value="<?php echo $organization_admin->email; ?>" required autocomplete="on">
                        <label class="label" for="orgEmail">Admin Email</label>
                        <div id="product_list" class="input__dropdown" style="display: none; background: #fff; padding:5px 10px;"></div>
                    </div>
                </div>
                <div class="footer__group border--dashed"><!-- -->
                    <button class="btn btn--m btn--primary btn--block save--toggle form--submit page--reload" data-target="#editOrganization">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Edit Customer Modall -->

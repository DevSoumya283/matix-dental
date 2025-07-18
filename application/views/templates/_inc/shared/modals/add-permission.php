<!-- Add New License Modal -->
<div id="addPermissionsModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2 class="margin--m no--margin-lr no--margin-t">Create Permissions</h2>
        </div>
        <div class="modal__body center center--h align--left cf">
            <form id="addPermissionForm" class="form__group" action="<?php echo base_url("add-permission"); ?>" method="post" >
            <div class="modal__content">

                <div class="input__group is--inline margin--xs no--margin-lr no--margin-t">
                    <input id="permissionName" name="name" class="input" type="text"  required>
                    <label class="label" for="accountEmail">Permission Name</label>
                </div>

                <div class="input__group is--inline margin--xs no--margin-lr no--margin-t">
                    <input id="permissionCode" name="code" class="input" type="text"  required>
                    <label class="label" for="accountEmail">Permission Code</label>
                </div>


                <div class="select">
                    <select name="groupId">
                        <option disabled="" selected="" value="default">Select Group</option>
                        <?php foreach($groups as $group){
                            echo '<option value="' . $group->id . '">' . $group->name . '</option>';
                        } ?>
                    </select>
                </div>
                <div class="center center--h">
                    Or create new group
                </div>
                <div class="">
                    <input name="groupName" placeholder="Group name" class="input input__text">
                </div>
                <div class="center center--h footer__group">
                    <a href="#" class="btn btn--m btn--primary is--pos btn--dir is--next addPermission">Create Permission</a>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>


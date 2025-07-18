<!-- Add New License Modal -->
<div id="managePermissionsModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2 class="margin--m no--margin-lr no--margin-t">Manage Permissions</h2>
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">
                <?php if(!empty($permissions)) { ?>
                <form id="permissions" class="form__group" action="<?php echo base_url("save-permissions"); ?>" method="post">
                    <input type="hidden" id="roleId" class="roleId" />
                    <div class="row ">
                        <?php foreach($groups as $group){
                            echo '<a href="#" class="group btn btn--s btn--primary is--pos btn--dir is--next" data-group_id="' . $group->id . '">' . ucwords(str_replace('-', ' ', $group->name)) . '</a>&nbsp;';
                        } ?>
                    </div>
                    <div class="row">
                        <table class="table" data-controls="#controlsTable">
                        <?php foreach($permissions as $permission) { ?>
                            <tr class="permission" data-group="<?php echo $permission->group_id; ?>">
                                <td><?php echo $permission->name ?></td>
                                <!-- <td><input type="checkbox" name="selectedPermissions[<?php echo $permission->id; ?>"></td> -->
                                <td class="center center--h">
                                    <label class="control control__checkbox">
                                        <input type="checkbox" name="checkboxRow" class="singleCheckbox permissionTrigger" id="permission_<?php echo $permission->id; ?>" data-permission_id="<?php echo $permission->id; ?>">
                                        <div class="control__indicator"></div>
                                    </label>
                                </td>
                            </tr>
                        <?php } ?>
                        </table>
                    </div><!--
                    <div class="footer__group border--dashed border--light">
                        <button class="btn btn--m btn--primary btn--block save--toggle page--reload">Save</button>
                    </div> -->
                </form>
            <?php } else { ?>
                No permissions added
            <?php } ?>

            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>


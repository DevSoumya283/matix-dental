
var Roles = {
    init: function(){
        $('.editLink').click(function(){
            Roles.loadRolePermissions($(this).data('role_id'));
        });

        $('.permissionTrigger').click(function(){

            Roles.saveRolePermission($(this).data('permission_id'), $(this).is(':checked') );
        });

        $('.group').click(function(){
            Roles.toggleButtons(this);
            Roles.filterPermissionGroup($(this).data('group_id'));
        })

        // default to group 1 view
        $('.group').each(function(id, link){
            if($(link).data('group_id') == 1){
                Roles.toggleButtons(link);
                Roles.filterPermissionGroup(1);
            }
        });

        $('.addPermission').click(function(){
            Roles.addPermission()
        })
    },

    loadGroups: function() {
        $.ajax({
            url: '/admin/load-groups',
            method: 'GET',
            dataType: 'json',
            success: function(response){
                console.log(groups);
            }
        });
    },

    loadRolePermissions: function(roleId, groupId){
        $('#roleId').val(roleId);
        $.ajax({
            url: '/admin/load-role-permissions',
            method: 'POST',
            dataType: 'json',
            data: {roleId: roleId},
            success: function(response){
                $.each(response, function(permissionId, permission){
                    var checked = (permission.value === "1") ? true : false;
                    $('#permission_' + permissionId).prop('checked', checked);
                });
            }
        });
    },

    saveRolePermission: function(permissionId, value){
        var roleId = $('#roleId').val();
        $.ajax({
            url: '/admin/save-role-permission',
            method: 'POST',
            dataType: 'json',
            data: {roleId: roleId, permissionId: permissionId, value: value},
            success: function(response){
                console.log('done')
            }
        })
    },

    toggleButtons: function(link){
        $('.group').addClass('is--pos')
        $(link).toggleClass('is--pos')
    },

    filterPermissionGroup: function(groupId){
        $('.permission').each(function(id, permission){
            if($(permission).data('group') == groupId){
                $(permission).show();
            } else {
                $(permission).hide();
            }
        })
    },

    addPermission: function(){
        console.log('creating permission');

        $.ajax({
            url: '/admin/save-permission',
            method: 'POST',
            dataType: 'json',
            data: $('#addPermissionForm').serialize(),
            success: function(response){
                $.each(response, function(permissionId, permission){
                    var checked = (permission.value === "1") ? true : false;
                    $('#permission_' + permissionId).prop('checked', checked);
                });
            }
        });
    }
}

$( document ).ready(function() {
  Roles.init();
});
var Users = {
    init: function(){
        $('.selectRole').click(function(){
            Users.assignRole(this)
        });
    },

    assignRole: function(select){
        console.log('changing role');

        $.ajax({
            url: '/location/save-user-role',
            method: 'POST',
            dataType: 'text',
            data: {userId: $(select).data('user_id'), roleId: $(select).val()},
            success: function(response){

            }
        })
    }
}


$( document ).ready(function() {
    Users.init();
});
var AdminVendor = {

    init: function(){
        $('#siteSelect').on('change', function(){
            AdminVendor.changeSite($(this).val());
        })
    },

    changeSite: function(siteId){
        document.location.href = '/vendor-products-dashboard?siteSelect=' + siteId;
        $_SESSION['adminVendorProductSite'] = siteId;
    }
}

$( document ).ready(function() {
    AdminVendor.init();
});

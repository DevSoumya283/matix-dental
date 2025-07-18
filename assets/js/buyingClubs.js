var BuyingClubs = {
    init: function(){
        $('.addBuyingClub').click(function(){
            BuyingClubs.create(this)
        });

        $('.addPricingScale').click(function(){
            BuyingClubs.createPricingScale(this)
        });

        $('.addItems').click(function(){
            var type = $(this).data('type');
            $('.type').text(type[0].toUpperCase()+type.slice(1));
            console.log(type);
            if(type == 'product-pricings'){
                document.getElementById("templateFile").href = '/buying-club/export-vendor-products?vendorId='+$(this).data('vendor_id')+'&club_id='+$(this).data('club_id');
            } else {
                document.getElementById("templateFile").href = '/assets/xls/buying-club-'+type+'.csv';
            }
        });

        $('.edit-list').click(function(){
            console.log($(this).data('type'))
            var type = $(this).data('type');
            $('.type').text(type[0].toUpperCase()+type.slice(1));
            BuyingClubs.loadData(type, false)
        })

        $('.update-club').click(function(){
            BuyingClubs.update()
        })

        $('.save-vendor-discount').click(function(){
            BuyingClubs.saveDiscount()
        })

        $('.toggleActive').click(function(){
            BuyingClubs.toggleActive();
        })

        $("#uploadVendorsForm").on('submit',(function(e) {
            console.log('vendors');
            e.preventDefault();
            BuyingClubs.importData(this);
        }))

        $("#uploadOrganizationsForm").on('submit',(function(e) {
            console.log('organizations')
            e.preventDefault();
            BuyingClubs.importData(this);
        }))

        $('.savePricingScale').click(function(){
            console.log('save pricing');
            BuyingClubs.savePricingScale(this);
        })

        $('.club_price').on('change', function(){
            console.log($(this));
            BuyingClubs.saveProductPricing(this);
        })

        $('#vendorSelect').on('change', function(){
            window.location.href = '/buying-club/manage-products?vendorId='+$(this).val()+'&id='+$(this).data('club_id');
        })

        $('#bcVendorSelect').on('change', function(){
            window.location.href = '/buying-clubs?vendorId='+$(this).val();
        })

        $('.delete-vendor').click(function(){
            BuyingClubs.deleteVendor(this);
        })

        $('.delete-organization').click(function(){
            BuyingClubs.deleteOrganization(this);
        })

        $('#filterProductsForm').attr('action', '/buying-club/manage-products?id=');
    },

    create: function(button){
        console.log('create');
        $.ajax({
            url: '/buying-club/create',
            method: 'POST',
            dataType: 'text',
            data: {name: $('#name').val(), code: $('#code').val(), userId: $('#userId').val()},
            success: function(response){
                location.reload();
            }
        })
    },

    savePricingScale: function(button){
        console.log($('#pricing-scale').val());
        if($('#pricing-scale').val() != ''){
            $.ajax({
                url: '/buying-club/save-pricing-scale',
                method: 'POST',
                dataType: 'text',
                data: $('#pricing-scales').serialize(),
                success: function(response){
                    // location.reload();
                }
            })
        }
    },

    update: function(){
        $.ajax({
            url: '/buying-club/save',
            method: 'POST',
            dataType: 'text',
            data: {club_id: $('#clubId').val(), name: $('#name').val(), code: $('#code').val(), discount: $('#percentage_discount').val(), userId: $('#userId').val(), discount: $('#percentage_discount').val()},
            success: function(response){
                console.log('done');
                location.reload();
            }
        })
    },

    loadData: function(type, append = false) {
        $.ajax({
            url: '/buying-club/load-'+type,
            method: 'POST',
            dataType: 'text',
            data: {id: $('#clubId').val()},
            success: function(response){
                console.log(response);
                if(append){
                    $('#dataList').append(response);
                } else {
                    $('#dataList').html(response);
                }

                // add delete triggers
                $('.delete-vendor').click(function(){
                    BuyingClubs.deleteVendor(this);
                });

                $('.delete-organization').click(function(){
                    BuyingClubs.deleteOrganization(this);
                });

                $('.delete-product').click(function(){
                    BuyingClubs.deleteProduct(this);
                });
            }
        })
    },

    deleteVendor: function(item){
        console.log('deleting vendor')
        console.log(item)

        $.ajax({
            url: "/buying-club/delete-vendor",
            type: "POST",
            data:  {clubId: $(item).data('club_id'), vendorId: $(item).data('vendor_id')},
            dataType: 'json',
            success: function(data) {
                console.log(data);
                location.reload();
            }
        });
    },

    deleteOrganization: function(item){
        console.log('deleting organization')
        console.log(item)

        $.ajax({
            url: "/buying-club/delete-organization",
            type: "POST",
            data:  {clubId: $(item).data('club_id'), organizationId: $(item).data('organization_id')},
            dataType: 'json',
            success: function(data) {
                location.reload();
            }
        });
    },

    deleteProduct: function(item){
        console.log('deleting vendor')
        console.log(item)

        $.ajax({
            url: "/buying-club/delete-product",
            type: "POST",
            data:  {clubId: $(item).data('club_id'), productId: $(item).data('product_id')},
            dataType: 'json',
            success: function(data) {
                // location.reload();
            }
        });
    },

    importData: function(form){
        $.ajax({
            url: "/buying-club-import",
            type: "POST",
            data:  new FormData(form),
            contentType: false,
            cache: false,
            processData:false,
            success: function(data) {
                location.reload();
            }
        });
    },

    toggleActive: function(){
        $.ajax({
            url: '/buying-club/toggle-active',
            method: 'POST',
            dataType: 'json',
            data: {id: $('#clubId').val()},
            success: function(response){}
        })
    }


}

$( document ).ready(function() {
    BuyingClubs.init();
});
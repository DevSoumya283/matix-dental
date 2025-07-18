var PricingScales = {
    init: function(){
        $('.addPricingScale').click(function(){
            PricingScales.create(this)
        });

        $('.addItems').click(function(){
            var type = $(this).data('type');
            $('.type').text(type[0].toUpperCase()+type.slice(1));
            console.log(type);
            document.getElementById("templateFile").href = '/pricing-scales/export-vendor-products?vendorId='+$(this).data('vendor_id')+'&club_id='+$(this).data('club_id');

        });

        $('.edit-list').click(function(){
            console.log($(this).data('type'))
            var type = $(this).data('type');
            $('.type').text(type[0].toUpperCase()+type.slice(1));
            PricingScales.loadData(type, false)
        })

        $('.update-club').click(function(){
            PricingScales.update()
        })

        $('.save-vendor-discount').click(function(){
            PricingScales.saveDiscount()
        })

        $('.toggleActive').click(function(){
            PricingScales.toggleActive();
        })

        $("#uploadForm").on('submit',(function(e) {
            e.preventDefault();
            PricingScales.importData(this);
        }))

        $('.club_price').on('change', function(){
            console.log($(this));
            PricingScales.saveProductPricing(this);
        })

        $('#vendorSelect').on('change', function(){
            window.location.href = '/pricing-scales?vendorId='+$(this).val();
            //window.location.href = '/pricing-scales/manage-products?vid='+$(this).val()+'&id='+$(this).data('club_id');
        })

        $('#filterProductsForm').attr('action', '/pricing-scales/manage-products?id=');
    },

    create: function(button){
        $.ajax({
            url: '/pricing-scales/create',
            method: 'POST',
            dataType: 'text',
            data: {vendorId: $('#vendorId').val(), name: $('#name').val(), percentageDiscount: $('#percentageDiscount').val()},
            success: function(response){
                location.reload();
            }
        })
    },

    saveDiscount: function(){
        $.ajax({
            url: '/pricing-scales/save',
            method: 'POST',
            dataType: 'text',
            data: {pricing_scale_id: $('#pricingScaleId').val(), name: $('#name').val(), percentage_discount: $('#percentage_discount').val()},
            success: function(response){
                location.reload();
            }
        })
    },

    saveProductPricing: function(input){
        console.log('saving');
        $.ajax({
            url: '/pricing-scales/save-product-pricing',
            method: 'POST',
            dataType: 'text',
            data: {pricing_scale_id:  $(input).data('pricing_scale_id'), product_id: $(input).data('product_id'), vendor_id: $(input).data('vendor_id'), scale_price: $(input).val()},
            success: function(response){
                $(input).val(response);
            }
        })
    },

    loadData: function(type, append = false) {
        $.ajax({
            url: '/pricing-scales/load-'+type,
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
                    PricingScales.deleteVendor(this);
                });

                $('.delete-organization').click(function(){
                    PricingScales.deleteOrganization(this);
                });

                $('.delete-product').click(function(){
                    PricingScales.deleteProduct(this);
                });
            }
        })
    },

    deleteProduct: function(item){
        console.log('deleting vendor')
        console.log(item)

        $.ajax({
            url: "/pricing-scales/delete-product",
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
            url: "/pricing-scales/import-products",
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
            url: '/pricing-scales/toggle-active',
            method: 'POST',
            dataType: 'json',
            data: {id: $('#pricingScaleId').val()},
            success: function(response){}
        })
    }


}

$( document ).ready(function() {
    PricingScales.init();
});
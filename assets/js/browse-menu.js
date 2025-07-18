var BrowseMenu = {
    init: function(){
        console.log('initialising');

        $(document).on('click', function(){
            BrowseMenu.closeNav();
        });
        $('.browse-dropdown').on('click', function(e){
            e.stopPropagation();
        })
        $('.nav-item a').click(function(){
            $('.browse-dropdown-loader').slideDown();
            $('.nav-item a').removeClass('active');
            $(this).addClass('active');
            if($('.browse-dropdown').is(':hidden')){
                $('.browse-dropdown-loader').slideDown();
            }
        });
        $('.view-category').click(function(){
            BrowseMenu.loadCategories(this)
        });
        $('.view-procedure').click(function(){
            BrowseMenu.loadProcedures(this)
        });
        $('.view-vendor').click(function(){
            BrowseMenu.loadVendors(this)
        });
        $('.view-mfc').click(function(){
            BrowseMenu.loadManufacturers()
        });
        $('.view-pro-list').click(function(){
            BrowseMenu.loadProdLists(this)
        });
    },

    closeNav: function(){
        $('.nav-item a').removeClass('active');
        if($('.browse-dropdown').is(':visible')){
            $('.browse-dropdown').slideUp();
        }
        $('#navbarNavDropdown').collapse('hide');
    },

    reloadProducts: function(field, value){
        console.log(field+':'+value);
        console.log(document.location.pathname);
        if(document.location.pathname != '/home'){
            window.location.href = '/home?' + field +'=' + value;
            return;
        }
        category = (field == 'category') ? value : '';
        procedure = (field == 'procedure') ? value : '';
        vendor_id = (field == 'vendor_id') ? value : '';
        manufacturer = (field == 'manufacturer') ? value : '';
        listid = (field == 'listid') ? value : '';
        search = "";
        page = 0;
        if(field == 'category'){
            rebuildCatNav(category);
        }
        BrowseMenu.closeNav();
        refresh_products();
    },

    splitIntoCols: function(data, dataKeyName, idField, nameField, numCols = 2){
        var itemCount = data.length;
        var i = 0;
        var numPerCol = Math.ceil(itemCount / numCols);
        var html = "";
        for(col = 0; col < numCols; col++ ){
            html += '<div class="list-col-'+numCols+'">';
            html += '<ul class="browse-list">';
            for(start = i; i < numPerCol * (col + 1); i++) {
                if(typeof data[i] != 'undefined'){
                    html += '<li><a href="#" data-'+dataKeyName+'="' + data[i][idField] + '">' + data[i][nameField] + '</a></li>';
                }
            }
            html += '</ul>';
            html += '</div>'
        }

        return html;
    },

    loadCategories: function(){
        $.ajax({
            type: "GET",
            url: base_url + "view-category",
            dataType: "json",
            success: function (data) {
                var cols = 2;
                var html = BrowseMenu.splitIntoCols(data.categories, 'category_id', 'id', 'name', cols);

                $('.browse-dropdown-loader').slideUp();
                $('.browse-dropdown').html(html).slideDown();
                $('.browse-dropdown').find('a').on('click', function(){
                    BrowseMenu.reloadProducts('category', $(this).data('category_id'));
                })
            }
        });
    },

    loadProcedures: function(){
        $.ajax({
            type: "GET",
            url: base_url + "view-procedures",
            dataType: "json",
            success: function (data) {
                var cols = 2;
                var html = BrowseMenu.splitIntoCols(data.procedures, 'procedure', 'product_procedures', 'product_procedures', cols);

                $('.browse-dropdown-loader').slideUp();
                $('.browse-dropdown').html(html).slideDown();
                $('.browse-dropdown').find('a').on('click', function(){
                    BrowseMenu.reloadProducts('procedure', $(this).data('procedure'));
                })
            }
        });
    },

    loadVendors: function(){
        $.ajax({
            type: "GET",
            url: base_url + "get-vendors",
            dataType: "json",
            success: function (data) {
                var cols = 2;
                var html = BrowseMenu.splitIntoCols(data.vendors, 'vendor_id', 'id', 'name', cols);

                $('.browse-dropdown-loader').slideUp();
                $('.browse-dropdown').html(html).slideDown();
                $('.browse-dropdown').find('a').on('click', function(){
                    BrowseMenu.reloadProducts('vendor_id', $(this).data('vendor_id'));
                })
            }
        });

    },

    loadManufacturers: function(startingLetter){
        startingLetter = typeof startingLetter !== 'undefined' ? startingLetter : 'A';
        console.log(startingLetter)
        console.log('loading manufacturers');
        $.ajax({
            type: "POST",
            url: base_url + "view-manufactures",
            dataType: "json",
            data: {'startingLetter': startingLetter},
            success: function (data) {
                console.log(data);
                $('.browse-dropdown-loader').slideUp();
                var numberFound = false;
                var html = '<div id="alphabet-holder"><ul id="alphabet" style="width: 100%">';
                for (var i = 0; i < data.letters.length; i++) {
                    if(!isNaN(data.letters[i])){
                        if(!numberFound){
                            data.letters[i] = '0-9';
                            numberFound = true;
                        } else {
                            continue;
                        }
                    }
                    html += '<li>' + data.letters[i] + '</li>';
                }
                html += '</ul></div>';

                var cols = 2;
                html += BrowseMenu.splitIntoCols(data.manufacturer, 'manufacturer', 'manufacturer', 'manufacturer', cols);




                $('.browse-dropdown-loader').slideUp();
                $('.browse-dropdown').html(html).slideDown();
                $('.browse-dropdown').find('a').on('click', function(){
                    BrowseMenu.reloadProducts('manufacturer', $(this).data('manufacturer'));
                })

                $('#alphabet li').on('click', function(){
                    $('#alphabet li').removeClass('selected');
                    $(this).addClass('selected');
                    BrowseMenu.loadManufacturers($(this).text());
                })
            }
        });
    },

    loadProdLists: function(){
        console.log('loading product lists');
        $.ajax({
            type: "GET",
            url: base_url + "get-product-lists",
            dataType: "json",
            success: function (data) {
                console.log(data);
                var cols = 2;
                var html = '';
                html += BrowseMenu.splitIntoCols(data.shopping_list, 'listid', 'id', 'listname', cols);




                $('.browse-dropdown-loader').slideUp();
                $('.browse-dropdown').html(html).slideDown();
                $('.browse-dropdown').find('a').on('click', function(){
                    BrowseMenu.reloadProducts('listid', $(this).data('listid'));
                })

                $('#alphabet li').on('click', function(){
                    console.log('clicked');
                    $('#alphabet li').removeClass('selected');
                    $(this).addClass('selected');
                    BrowseMenu.loadManufacturers($(this).text());
                })
            }
        });

    }
}

console.log('here');

$( document ).ready(function() {
    BrowseMenu.init();
});
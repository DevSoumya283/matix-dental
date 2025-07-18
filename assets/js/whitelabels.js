var vendorId;
var Whitelabels = {
    init: function(){

        var editor = new Jodit('#editor', {
            width: 'auto'
        });

        $('.edit-whitelabel').on('click', function(){
            console.log('editing');
            Whitelabels.edit($(this).data('whitelabel-id'), $(this.data('name')));
        })

        $('.editLink').on('click', function(){
            console.log('clear');
            $('.action').text('Create');
            $('.edit-whitelabel').val('Create White Label Site');
            $('#id').val('')
            $('#name').val('')
            $('#short_name').val('')
            $('#domain').val('')
            $('#vendor_id').val('')
            $('#logo').attr('src', '').hide()
        })

        $('#categories').on('change', function(){
            console.log($(this).val())
            if($(this).val() > 0){
                console.log('load 2')
                var callback = function(categories){
                    if(categories.length > 0){
                        $('#child_categories').empty();
                        $('#child_categories').append(new Option('All', ''));

                        $(categories).each(function(id, cat){
                            $('#child_categories').append(new Option(cat.name, cat.id));
                        })

                        $('#child_cat_holder').show();
                    } else {
                            $('#child_cat_holder').hide();
                    }
                }

                var categories = Whitelabels.loadCategories($(this).val(), vendorId, callback)
                console.log(categories)
            }
        })

        $('.pageTrigger').on('click', function(){
            Whitelabels.editPage(editor, $(this).data('whitelabelId'), $(this).data('name'));
        })

        $('#categoryForm').on('submit', function(e){
            e.preventDefault();
            console.log('add category');
            var formData = new FormData(this)
            console.log(formData);
            Whitelabels.addCategory(formData);
        })
    },

    edit: function(id){
        $.ajax({
            url: '/white-labels/load',
            method: 'POST',
            dataType: 'json',
            data: {id: id},
            success: function(response){
                console.log(response)
                $('.action').text('Update')
                $('.edit-whitelabel').val('Update White Label Site');
                $('#id').val(response.id)
                $('#name').val(response.name).click().focus()
                $('#short_name').val(response.short_name).focus()
                $('#domain').val(response.domain).focus()
                $('#vendor_id').val(response.vendor_id)
                $('#logo').attr('src', '/assets/img/logos/' + response.logo).show()
            }
        });
    },

    editPage: function(editor, whitelabelId, name){
        $.ajax({
            url: '/white-labels/load-page',
            method: 'POST',
            dataType: 'json',
            data: {whitelabelId: whitelabelId, name: name},
            success: function(response){
                console.log(response)
                $('#editPageForm').find('#id').val(response.page.id);
                $('.site_id').val(response.page.site_id);
                $('.vendor_id').val(response.site.vendor_id);
                $('#page_name').val(name);
                Whitelabels.setField('page_title', response.page.page_title);
                Whitelabels.setField('tagline', response.page.tagline);
                if(name == 'home'){
                    $('.hero-uploader').removeClass('is--hidden');
                    $('.category-images').removeClass('is--hidden');
                    // load categories

                } else {
                    $('.hero-uploader').addClass('is--hidden')
                    $('.category-images').addClass('is--hidden')
                }
                editor.value = response.page.content
                $('.pageName').text(response.page.name);
                if(name == 'home'){
                    //inject hero if found
                    if(response.page.hero != null){
                        var re = /(?:\.([^.]+))?$/;
                        var ext = re.exec(response.page.hero)[1];
                        // console.log('hero found: '+ response.page.hero + ' ' + ext);
                        if(ext == 'mp4' || ext == 'webm'){
                            $('.hero').html('<video style="width:100%;"  playsinline autoplay muted loop><source src="/assets/img/heros/'+response.page.hero+'" type="video/mp4"></video>');
                        } else {
                            $('.hero').html('<img style="width:100%;" src="/assets/img/heros/'+response.page.hero+'">');
                        }
                            $('.hero').removeClass('is--hidden');
                    }

                    var callback = function(categories){
                        if(categories != null){
                            console.log(categories)
                            $('#categories').empty();
                            $('#categories').append(new Option('Select Category', ''));
                            $(categories).each(function(id, cat){
                                $('#categories').append(new Option(cat.name, cat.id));
                            })
                        }
                    }
                    vendorId = response.site.vendor_id;

                    var categories = Whitelabels.loadCategories(1, response.site.vendor_id, callback)

                    if(response.categoryLinks != null){
                         $('#catLinks').html('');
                        $(response.categoryLinks).each(function(id, catlink){
                            Whitelabels.insertCatLink(catlink);
                        });
                    }
                }
            }
        });
    },

    insertCatLink: function(catlink){
        // console.log(catlink);
        // console.log(catlink.category_name);
        var link = '<div class="col col--1-of-6">';
            link += '    <a class="card card--img is--link align--center" data-target="/home?category='+catlink.category_id+'&catRoot=dentist">';
            link += '        <img src="/uploads/sites/catmenu/'+catlink.category_image+'" alt="'+catlink.category_name+'">';
            link += '        <span class="card__label">'+catlink.category_name+'</span>';
            link += '    </a>';
            link += '    <div class="center center--h footer__group">';
            link += '        <a class="btn btn--m btn--primary is--pos btn--dir is--next removeCategory" data-link_id="'+catlink.id+'">Delete</a>';
            link += '    </div>';
            link += '</div>';

            $('#catLinks').append(link);

            $('.removeCategory').on('click', function(){
                Whitelabels.removeCategory(this);
            })
    },

    loadCategories: function(rootCat, vendorId, callback){
        $.ajax({
            type:'POST',
            url: '/white-labels/load-vendor-categories',
            data: {'parent_id': rootCat,'vendor_id': vendorId},
            datatype: 'json',
            success:function(data){
                var response = JSON.parse(data);
                callback(response.cats);
            },
            error: function(data){
            }
        });
    },

    addCategory: function(formData){
        $.ajax({
            type:'POST',
            url: '/white-labels/add-home-category',
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            datatype: 'json',
            success:function(data){
                Whitelabels.insertCatLink(JSON.parse(data));
            },
            error: function(data){
                console.log("error");
                console.log(data);
            }
        });
    },

    removeCategory: function(removeButton){
        console.log(removeButton)
        console.log($(removeButton))
        var linkId = $(removeButton).data('link_id')
        $(removeButton).closest('div.col').remove();
        $.ajax({
            type:'POST',
            url: '/white-labels/remove-home-category',
            data: {linkId: linkId},
            success:function(data){

            }
        });
    },


    setField: function(name, value){
        $('#'+name).val(value);
        $('#'+name).addClass('not--empty');
    }
}


$( document ).ready(function() {
    Whitelabels.init();
});

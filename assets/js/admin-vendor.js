$(document).on("click", '#OrderLocationClose', function () {
    $('#UseLocationName').css('display', 'none');
    var data = {};
    if ($('#vendorStartDate').val() != "" && $('#vendorEndDate').val() != "") {
        $('.vendorStartDate').val($('#vendorStartDate').val());
        $('.vendorEndDate').val($('#vendorEndDate').val());

        var vendorStartDate = $('.vendorStartDate').val();
        var vendorEndDate = $('.vendorEndDate').val();

        if (vendorStartDate != "" && vendorEndDate != "") {
            data['vendorStartDate'] = vendorStartDate;
            data['vendorEndDate'] = vendorEndDate;
        }
    }
    data['Reportstatus'] = reportBy;
    data['reportBy'] = reportBy;
    data['resolution'] = reportBy;
    $.ajax({
        type: "POST",
        url: "order-filter-Report",
        data: data,
        success: function (data) {
            $(".reportData").html(data);
        }
    });
});
$(document).on("click", ".ManufacturClose", function () {
    var data = {};
    var reportBy=2;
     $(this).parents('li').css('display','none');
     var selectionid=$(this).parents('li').attr('id');
     if (selectionid == "ManufacturerName") {
         $('.manufacturer_name').val("");
     }
     if (selectionid == "UseLocationName") {
         $('.location_id').val("");
     }
     if (selectionid == "CategoryName") {
         $('.Category_select').val("");
     }
    if ($('.manufacturer_name').val() != "") {
        var manufacturer_name = $('.manufacturer_name').val();
        data["manufacturer_name"] = manufacturer_name;
    } else {
        $('#ManufacturerName').text("Manufacturer Name");
    }
    if ($('.location_id').val() != "") {
        var location_id = $('.location_id').val();
        var location_name = $('.location_id option:selected').text();
        data['location_id'] = location_id;
    } else {
        $('#UseLocationName').text("Location Name");
    }

    if ($('.Category_select').val() != "") {
        var Category_select = $('.Category_select').val();
        var Category_name = $('.Category_select option:selected').text();
        data['Category_select'] = Category_select;
    } else {
        $('#CategoryName').text("Category Name");
    }

    if ($('#vendorStartDate').val() != "" && $('#vendorEndDate').val() != "") {
        $('.vendorStartDate').val($('#vendorStartDate').val());
        $('.vendorEndDate').val($('#vendorEndDate').val());

        var vendorStartDate = $('.vendorStartDate').val();
        var vendorEndDate = $('.vendorEndDate').val();

        if (vendorStartDate != "" && vendorEndDate != "") {
            data['vendorStartDate'] = vendorStartDate;
            data['vendorEndDate'] = vendorEndDate;
        }
    }
    data['Reportstatus'] = reportBy;
    data['reportBy'] = reportBy;
    data['resolution'] = reportBy;
    $.ajax({
        type: "POST",
        url: "orderSales-filter-Report",
        data: data,
        success: function (data) {
            $(".reportData").html(data);
        }
    });

});
$(document).on("click", "#ShippingLocationClose", function () {
    reportBy = 2;
    var data = {};
    $('#UseLocationName').css('display', 'none');
    if ($('#vendorStartDate').val() != "" && $('#vendorEndDate').val() != "") {
        $('.vendorStartDate').val($('#vendorStartDate').val());
        $('.vendorEndDate').val($('#vendorEndDate').val());

        var vendorStartDate = $('.vendorStartDate').val();
        var vendorEndDate = $('.vendorEndDate').val();

        if (vendorStartDate != "" && vendorEndDate != "") {
            data['vendorStartDate'] = vendorStartDate;
            data['vendorEndDate'] = vendorEndDate;
        }
    }

    data['Reportstatus'] = reportBy;
    data['reportBy'] = reportBy;
    data['resolution'] = reportBy;

    $.ajax({
        type: "POST",
        url: "orderShipping-filter-Report",
        data: data,
        success: function (data) {
            $(".reportData").html(data);
        }
    });
});
$(document).on("click", "#CustomerLocationClose", function () {
    var reportBy = 2;
    $('#UseLocationName').css('display', 'none');
    var data = {};
    if ($('#vendorStartDate').val() != "" && $('#vendorEndDate').val() != "") {
        alert($('.vendorStartDate').val($('#vendorStartDate').val()));
        $('.vendorEndDate').val($('#vendorEndDate').val());
        var vendorStartDate = $('.vendorStartDate').val();
        var vendorEndDate = $('.vendorEndDate').val();
        if (vendorStartDate != "" && vendorEndDate != "") {
            data['vendorStartDate'] = vendorStartDate;
            data['vendorEndDate'] = vendorEndDate;
        }
    }
    data['Reportstatus'] = reportBy;
    data['reportBy'] = reportBy;
    data['resolution'] = reportBy;
    $.ajax({
        type: "POST",
        url: "orderCustomer-filter-Report",
        data: data,
        success: function (data) {
            $(".reportData").html(data);
        }
    });
});

$(document).ready(function () {
    var reportBy = 2;
    $('.VendorOrderFilter').click(function () {
        var data = {};
        if ($('.location_id').val() != "") {
            var location_id = $('.location_id').val();
            var location_name = $('.location_id option:selected').text();
            $('#UseLocationName').css('display','block');
            $('#UseLocationName').html(location_name + ' <a class="filter--clear" href="#"><svg class="icon icon--x" id="OrderLocationClose"><use xlink:href="#icon-x"></use></svg></a>');
            data['location_id'] = location_id;
        } else {
            $('#UseLocationName').text("Location Name");
        }

        if ($('#vendorStartDate').val() != "" && $('#vendorEndDate').val() != "") {
            $('.vendorStartDate').val($('#vendorStartDate').val());
            $('.vendorEndDate').val($('#vendorEndDate').val());

            var vendorStartDate = $('.vendorStartDate').val();
            var vendorEndDate = $('.vendorEndDate').val();

            if (vendorStartDate != "" && vendorEndDate != "") {
                data['vendorStartDate'] = vendorStartDate;
                data['vendorEndDate'] = vendorEndDate;
            }
        }

        data['Reportstatus'] = reportBy;
        data['reportBy'] = reportBy;
        data['resolution'] = reportBy;

        $.ajax({
            type: "POST",
            url: "order-filter-Report",
            data: data,
            success: function (data) {
                $(".reportData").html(data);
            }
        });
        $(".modal").removeClass('is-visible');
        //Clear form contents, reset fields
        if ($(activeModal).find('form').length) {
            var fields = $('.modal').find('.input');
            $(fields).removeClass('not--empty');
        }

        setTimeout(function () {
            $("body").removeClass("has-modal");
            if ($(document).height() > $(window).height()) {
                $("body").css({
                    "padding-right": "0"
                })
            }
        }, 400);
        return false;
    });

    $('.VendorSalesFilter').click(function () {
        var data = {};
        if ($('.manufacturer_name').val() != "") {
            var manufacturer_name = $('.manufacturer_name').val();
//            $('#ManufacturerName').css('display','block');
            $('#ManufacturerName').show();
            $('#ManufacturerName').html(manufacturer_name + ' <a class="filter--clear" href="#"><svg class="icon icon--x ManufacturClose" ><use xlink:href="#icon-x"></use></svg></a>');
            data["manufacturer_name"] = manufacturer_name;
        } else {
            $('#ManufacturerName').text("Manufacturer Name");
        }
        if ($('.location_id').val() != "") {
            var location_id = $('.location_id').val();
            $('#UseLocationName').show();
            var location_name = $('.location_id option:selected').text();
            $('#UseLocationName').html(location_name + ' <a class="filter--clear" href="#"><svg class="icon icon--x ManufacturClose"><use xlink:href="#icon-x"></use></svg></a>');
            data['location_id'] = location_id;
        } else {
            $('#UseLocationName').text("Location Name");
        }

        if ($('.Category_select').val() != "") {
            var Category_select = $('.Category_select').val();
            var Category_name = $('.Category_select option:selected').text();
            $('#CategoryName').show();
            $('#CategoryName').html(Category_name  + ' <a class="filter--clear" href="#"><svg class="icon icon--x ManufacturClose"><use xlink:href="#icon-x"></use></svg></a>');
            data['Category_select'] = Category_select;
        } else {
            $('#CategoryName').text("Category Name");
        }

        if ($('#vendorStartDate').val() != "" && $('#vendorEndDate').val() != "") {
            $('.vendorStartDate').val($('#vendorStartDate').val());
            $('.vendorEndDate').val($('#vendorEndDate').val());

            var vendorStartDate = $('.vendorStartDate').val();
            var vendorEndDate = $('.vendorEndDate').val();

            if (vendorStartDate != "" && vendorEndDate != "") {
                data['vendorStartDate'] = vendorStartDate;
                data['vendorEndDate'] = vendorEndDate;
            }
        }

        data['Reportstatus'] = reportBy;
        data['reportBy'] = reportBy;
        data['resolution'] = reportBy;
        $.ajax({
            type: "POST",
            url: "orderSales-filter-Report",
            data: data,
            success: function (data) {
                $(".reportData").html(data);
            }
        });
        $(".modal").removeClass('is-visible');
        //Clear form contents, reset fields
        if ($(activeModal).find('form').length) {
            var fields = $('.modal').find('.input');
            $(fields).removeClass('not--empty');
        }

        setTimeout(function () {
            $("body").removeClass("has-modal");
            if ($(document).height() > $(window).height()) {
                $("body").css({
                    "padding-right": "0"
                })
            }
        }, 400);
        return false;
    });

    $('.VendorShippingFilter').click(function () {
        var data = {};
        if ($('.location_id').val() != "") {
            var location_id = $('.location_id').val();
            var location_name = $('.location_id option:selected').text();
            $('#UseLocationName').css('display','block');
            $('#UseLocationName').html(location_name + ' <a class="filter--clear" href="#"><svg class="icon icon--x" id="ShippingLocationClose"><use xlink:href="#icon-x"></use></svg></a>');
            data['location_id'] = location_id;
        } else {
            $('#UseLocationName').text("Location Name");
        }

        if ($('#vendorStartDate').val() != "" && $('#vendorEndDate').val() != "") {
            $('.vendorStartDate').val($('#vendorStartDate').val());
            $('.vendorEndDate').val($('#vendorEndDate').val());

            var vendorStartDate = $('.vendorStartDate').val();
            var vendorEndDate = $('.vendorEndDate').val();

            if (vendorStartDate != "" && vendorEndDate != "") {
                data['vendorStartDate'] = vendorStartDate;
                data['vendorEndDate'] = vendorEndDate;
            }
        }

        data['Reportstatus'] = reportBy;
        data['reportBy'] = reportBy;
        data['resolution'] = reportBy;

        $.ajax({
            type: "POST",
            url: "orderShipping-filter-Report",
            data: data,
            success: function (data) {
                $(".reportData").html(data);
            }
        });
        $(".modal").removeClass('is-visible');
        //Clear form contents, reset fields
        if ($(activeModal).find('form').length) {
            var fields = $('.modal').find('.input');
            $(fields).removeClass('not--empty');
        }

        setTimeout(function () {
            $("body").removeClass("has-modal");
            if ($(document).height() > $(window).height()) {
                $("body").css({
                    "padding-right": "0"
                })
            }
        }, 400);
        return false;
    });
    $('.VendorCustomerFilter').click(function () {
        var data = {};
        if ($('.location_id').val() != "") {
            var location_id = $('.location_id').val();
            var location_name = $('.location_id option:selected').text();
            $('#UseLocationName').css('display','block');
            $('#UseLocationName').html(location_name + ' <a class="filter--clear" href="#"><svg class="icon icon--x" id="CustomerLocationClose"><use xlink:href="#icon-x"></use></svg></a>');
            data['location_id'] = location_id;
        } else {
            $('#UseLocationName').text("Location Name");
        }

        if ($('#vendorStartDate').val() != "" && $('#vendorEndDate').val() != "") {
            $('.vendorStartDate').val($('#vendorStartDate').val());
            $('.vendorEndDate').val($('#vendorEndDate').val());

            var vendorStartDate = $('.vendorStartDate').val();
            var vendorEndDate = $('.vendorEndDate').val();

            if (vendorStartDate != "" && vendorEndDate != "") {
                data['vendorStartDate'] = vendorStartDate;
                data['vendorEndDate'] = vendorEndDate;
            }
        }

        data['Reportstatus'] = reportBy;
        data['reportBy'] = reportBy;
        data['resolution'] = reportBy;
        $.ajax({
            type: "POST",
            url: "orderCustomer-filter-Report",
            data: data,
            success: function (data) {
                $(".reportData").html(data);
            }
        });
        $(".modal").removeClass('is-visible');
        //Clear form contents, reset fields
        if ($(activeModal).find('form').length) {
            var fields = $('.modal').find('.input');
            $(fields).removeClass('not--empty');
        }

        setTimeout(function () {
            $("body").removeClass("has-modal");
            if ($(document).height() > $(window).height()) {
                $("body").css({
                    "padding-right": "0"
                })
            }
        }, 400);
        return false;
    });

    $("#export").click(function () {
        $("#export_order").tableToCSV();
    });

    $('.incOrderItemCount').change(function () {
        var increment = $(this).val();
        var order_itemId = $(this).attr('data-orderItem_id');
        $.ajax({
            type: "POST",
            url: "orderItem-picked-Increment",
            data: {'increment': increment, 'order_itemId': order_itemId},
            success: function (data) {
                if (data == true) {
                    location.reload();
                }
            }
        });
    });

    $('.customerSelection').change(function () {
        var selection = $(this).val();
        if (selection.indexOf("Business") > -1) {
            $('.customerCompany').css('display', 'block');
            $('.customerInstitution').css('display', 'none');
        } else {
            $('.customerInstitution').css('display', 'block');
            $('.customerCompany').css('display', 'none');
        }
    });

    $('#student_search').keyup(function () {
        var search = $(this).val();
        var class_id = $(this).data('class_id');
        $.ajax({
            type: "POST",
            url: "assign-students-ToClass",
            data: {'search': search, 'class_id': class_id},
            success: function (data) {
                $('#student_results ul').empty("");
                var data = JSON.parse(data);
                for (i = 0; i < data.length; i++) {
                    $('#student_results ul').append('<li class="user padding--s no--pad-l no--pad-r cf"><div class="entity__group"><div class="avatar avatar--s" style="background-image:url()"></div></div>' + data[i].first_name + '<button class="btn btn--s btn--tertiary btn--toggle float--right width--fixed-75 adding_Students btn--tertiary" value="' + data[i].student_id + '" data-before="Select" data-after="&#10003;" type="button"></button></li>');
                }
            }
        });
    });
    $(document).on("click", '.adding_Students', function () {
        var student_id = $(this).val();
        var class_id = $('#class_id').val();
        if ($(this).hasClass("btn--tertiary")) {
            $(this).removeClass("btn--tertiary").addClass("is--pos");
        }
        $.ajax({
            type: "POST",
            url: "adding-Customer-ToClasses",
            data: {'student_id': student_id, 'class_id': class_id},
            success: function (data) {
                console.log(data);
            }
        });
    });
    var offset = 2;
    $('.GetTwoMoreReviews').click(function () {
        var selection = $('.AdminReview_Sort').val();
        var product_id = $('#productID').val();
        $.ajax({
            type: 'POST',
            url: 'get-TwoMore-ReviewsProduct',
            data: {'offset': offset, 'product_id': product_id, 'selection': selection},
            success: function (data) {
                offset += 2;
                $(".Extended_ReviewTwo").append(data);
            }
        });
    });
    $('.GetTwoMoreAnswersAdmin').click(function () {
        var product_id = $(this).attr("data-question_id");
        var answer_offset = $(this).attr("data-answer_offset");
        $this = $(this);
        $.ajax({
            type: 'POST',
            url: 'get-TwoMore-Answers',
            data: {'offset': answer_offset, 'product_id': product_id},
            success: function (data) {
                if (data != null) {
                    $(".Extended_Answer_" + product_id).append(data);
                    $this.attr("data-answer_offset", (parseInt($this.attr("data-answer_offset")) + 2));
                }
            }
        });
    });
    $('.order_selects').change(function () {
        var order_selects = $(".order_selects").val();
        var customer_id = $('#user_id').val();
        var timelimit = $('.orderBy_select').val()
        $.ajax({
            type: 'POST',
            url: 'Order-statusReport-Admin',
            data: {'order_status': order_selects, 'customer_id': customer_id, 'timelimit': timelimit},
            success: function (data) {
                $('.order_Report').html(data);
            }
        });
    });
    $('.orderBy_select').change(function () {
        var order_selects = $(".order_selects").val();
        var customer_id = $(this).data('customer_id');
        console.log($('input #user_id'));
        console.log(customer_id);
        var timelimit = $('.orderBy_select').val()

        $.ajax({
            type: 'POST',
            url: 'Order-statusReport-Admin',
            data: {'order_status': order_selects, 'customer_id': customer_id, 'timelimit': timelimit},
            success: function (data) {
                console.log(data);
                $('.order_Report').html(data);
            }
        });
    });

    $('.customer_Orders').change(function () {
        var selection = $(this).val();
        var customer_id = $('#customer_id').val();
        var Order_reportByDay=$('.Order_reportByDay').val();
        $.ajax({
            type: 'POST',
            url: 'customer-orderBy-Status',
            data: {'customer_id': customer_id, 'selection': selection,'Order_reportByDay':Order_reportByDay},
            success: function (data) {
                $('.order_status').html(data);
            }
        });
    });
    $('.Order_reportByDay').change(function () {
        var selection = $(this).val();
        var customer_id = $('#customer_id').val();
        var customer_Orders=$('.customer_Orders').val();
        $.ajax({
            type: 'POST',
            url: 'customer-orderBy-Month',
            data: {'customer_id': customer_id, 'selection': selection, 'customer_Orders':customer_Orders},
            success: function (data) {
                $('.order_status').html(data);
            }
        });
    });

    $('.order_cancel_GetID').click(function () {
        var order_id = $('#order_cancel_id').val();
        $(".cancel_order_id").val(order_id);
    });
    $('.adminAddCustomerNote').click(function () {
        var customer_id = $(this).data('customer_id');
        $('.customer_id').val(customer_id);
    });
    $('.AdminOrganizationIdNotes').click(function () {
        var organization_id = $(this).data('organization_id');
        $('.organization_id').val(organization_id);
    });
    $('.VendorCustomerId').click(function () {
        var customer_id = $(this).data('customer_id')
        $('.customer_id').val(customer_id);
    });
});
$(document).on("change", ".AdminReview_Sort", function () {
    var offset;
    var selection = $(this).val();
    var product_id = $('.product_id').val();
    $.ajax({
        type: 'POST',
        url: 'get-TwoMore-ReviewsProduct',
        data: {'product_id': product_id, 'selection': selection, 'offset': offset},
        success: function (data) {
            $('.review').empty("");
            $(".Extended_ReviewTwo").append(data);
        }
    });
});



$(function () {
    $(document).on("click", ".resolution", function (e) {
        e.preventDefault();
        if($(this).data('requestRunning')) {
            return;
        }
        $(this).data('requestRunning',true);
        reportBy = $(this).attr("data-resolution");
        var data = {};
        if ($('.location_id').val() != "") {
            var location_id = $('.location_id').val();
            var location_name = $('.location_id option:selected').text();
            $('#UseLocationName').html(location_name + ' <a class="filter--clear" href="#"><svg class="icon icon--x" id="OrderLocationClose"><use xlink:href="#icon-x"></use></svg></a>');
            data['location_id'] = location_id;
        } else {
            $('#UseLocationName').text("Location Name");
        }

        if ($('#vendorStartDate').val() != "" && $('#vendorEndDate').val() != "") {
            $('.vendorStartDate').val($('#vendorStartDate').val());
            $('.vendorEndDate').val($('#vendorEndDate').val());

            var vendorStartDate = $('.vendorStartDate').val();
            var vendorEndDate = $('.vendorEndDate').val();

            if (vendorStartDate != "" && vendorEndDate != "") {
                data['vendorStartDate'] = vendorStartDate;
                data['vendorEndDate'] = vendorEndDate;
            }
        }

        data['Reportstatus'] = reportBy;
        data['reportBy'] = reportBy;
        data['resolution'] = reportBy;
        $.ajax({
            type: "POST",
            url: "order-filter-Report",
            data: data,
            success: function (data) {
                $(".reportData").html(data);
            },
            complete: function() {
                $(this).data('requestRunning',false);
            }
        });
    });

    $(document).on("click", ".resolutionSale", function (e) {
        e.preventDefault();
        if($(this).data('requestRunning')) {
            return;
        }
        $(this).data('requestRunning',true);
        reportBy = $(this).attr("data-resolution");
        var data = {};
        if ($('.manufacturer_name').val() != "") {
            var manufacturer_name = $('.manufacturer_name').val();
            $('#ManufacturerName').html(manufacturer_name + ' <a class="filter--clear" href="#"><svg class="icon icon--x ManufacturClose" ><use xlink:href="#icon-x"></use></svg></a>');
            data["manufacturer_name"] = manufacturer_name;
        } else {
            $('#ManufacturerName').text("Manufacturer Name");
        }
        if ($('.location_id').val() != "") {
            var location_id = $('.location_id').val();
            var location_name = $('.location_id option:selected').text();
            $('#UseLocationName').html(location_name + ' <a class="filter--clear" href="#"><svg class="icon icon--x ManufacturClose"><use xlink:href="#icon-x"></use></svg></a>');
            data['location_id'] = location_id;
        } else {
            $('#UseLocationName').text("Location Name");
        }

        if ($('.Category_select').val() != "") {
            var Category_select = $('.Category_select').val();
            var Category_name = $('.Category_select option:selected').text();
            $('#CategoryName').html(Category_name  + ' <a class="filter--clear" href="#"><svg class="icon icon--x ManufacturClose"><use xlink:href="#icon-x"></use></svg></a>');
            data['Category_select'] = Category_select;
        } else {
            $('#CategoryName').text("Category Name");
        }

        if ($('#vendorStartDate').val() != "" && $('#vendorEndDate').val() != "") {
            $('.vendorStartDate').val($('#vendorStartDate').val());
            $('.vendorEndDate').val($('#vendorEndDate').val());

            var vendorStartDate = $('.vendorStartDate').val();
            var vendorEndDate = $('.vendorEndDate').val();

            if (vendorStartDate != "" && vendorEndDate != "") {
                data['vendorStartDate'] = vendorStartDate;
                data['vendorEndDate'] = vendorEndDate;
            }
        }

        data['Reportstatus'] = reportBy;
        data['reportBy'] = reportBy;
        data['resolution'] = reportBy;
        $.ajax({
            type: "POST",
            url: "orderSales-filter-Report",
            data: data,
            success: function (data) {
                $(".reportData").html(data);
            },
            complete: function() {
                $(this).data('requestRunning',false);
            }
        });
    });
    $(document).on("click", ".resolutionCustomer", function (e) {
        e.preventDefault();
        if($(this).data('requestRunning')) {
            return;
        }
        $(this).data('requestRunning',true);
        reportBy = $(this).attr("data-resolution");
        var data = {};
        if ($('.location_id').val() != "") {
            var location_id = $('.location_id').val();
            var location_name = $('.location_id option:selected').text();
            $('#UseLocationName').html(location_name + ' <a class="filter--clear" href="#"><svg class="icon icon--x" id="CustomerLocationClose"><use xlink:href="#icon-x"></use></svg></a>');
            data['location_id'] = location_id;
        } else {
            $('#UseLocationName').text("Location Name");
        }
        if ($('#vendorStartDate').val() != "" && $('#vendorEndDate').val() != "") {
            $('.vendorStartDate').val($('#vendorStartDate').val());
            $('.vendorEndDate').val($('#vendorEndDate').val());

            var vendorStartDate = $('.vendorStartDate').val();
            var vendorEndDate = $('.vendorEndDate').val();

            if (vendorStartDate != "" && vendorEndDate != "") {
                data['vendorStartDate'] = vendorStartDate;
                data['vendorEndDate'] = vendorEndDate;
            }
        }
        data['Reportstatus'] = reportBy;
        data['reportBy'] = reportBy;
        data['resolution'] = reportBy;
        $.ajax({
            type: "POST",
            url: "orderCustomer-filter-Report",
            data: data,
            success: function (data) {
                $(".reportData").html(data);
            },
            complete: function() {
                $(this).data('requestRunning',false);
            }
        });
    });
    $(document).on("click", ".resolutionShipping", function (e) {
        e.preventDefault();
            if ( $(this).data('requestRunning') ) {
            return;
            }
            $(this).data('requestRunning', true);
        reportBy = $(this).attr("data-resolution");
        var data = {};
        if ($('.location_id').val() != "") {
            var location_id = $('.location_id').val();
            var location_name = $('.location_id option:selected').text();
            $('#UseLocationName').html(location_name + ' <a class="filter--clear" href="#"><svg class="icon icon--x" id="ShippingLocationClose"><use xlink:href="#icon-x"></use></svg></a>');
            data['location_id'] = location_id;
        } else {
            $('#UseLocationName').text("Location Name");
        }
        if ($('#vendorStartDate').val() != "" && $('#vendorEndDate').val() != "") {
            $('.vendorStartDate').val($('#vendorStartDate').val());
            $('.vendorEndDate').val($('#vendorEndDate').val());

            var vendorStartDate = $('.vendorStartDate').val();
            var vendorEndDate = $('.vendorEndDate').val();

            if (vendorStartDate != "" && vendorEndDate != "") {
                data['vendorStartDate'] = vendorStartDate;
                data['vendorEndDate'] = vendorEndDate;
            }
        }

        data['Reportstatus'] = reportBy;
        data['reportBy'] = reportBy;
        data['resolution'] = reportBy;
        $.ajax({
            type: "POST",
            url: "orderShipping-filter-Report",
            data: data,
            success: function (data) {
                $(".reportData").html(data);
            },
             complete: function() {
            $(this).data('requestRunning', false);
        }
        });
    });
});

function PrintElem() {
    var mywindow = window.open('', 'PRINT', 'height=400,width=600');
    mywindow.document.write('<html><head><title>' + document.title + '</title>');
    mywindow.document.write('<link href="' + base_url + 'assets/css/main.css" rel="stylesheet" type="text/css">');
    mywindow.document.write('<link href="' + base_url + 'lib/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" type="text/css">');
    mywindow.document.write('<link href="' + base_url + 'lib/bootstrap-timepicker/bootstrap-timepicker.css" rel="stylesheet" type="text/css">');
    mywindow.document.write('<link href="' + base_url + 'lib/animate-css/animate.css" rel="stylesheet" type="text/css">');

    mywindow.document.write('</head><body>');
    var order_content = $(".invoice").html();
    mywindow.document.write('<div class="overlay__wrapper">');
    mywindow.document.write('<section class="content__wrapper has--sidebar-l bg--lightest-gray">');
    mywindow.document.write('<div class="content__main">');
    mywindow.document.write('<div class="row row--full-height">');
    mywindow.document.write('<div class="invoice">');

    mywindow.document.write(order_content);
    mywindow.document.write('</div>');
    mywindow.document.write('</div>');
    mywindow.document.write('</div>');
    mywindow.document.write('</section>');
    mywindow.document.write('</div>');
    mywindow.document.write('</body></html>');

    //mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/

    setTimeout(function () {
        mywindow.print();
    }, 1000);

    //mywindow.close();

    return true;

}
function printAddress() {
    var mywindow = window.open('', 'PRINT', 'height=400,width=600');
    mywindow.document.write('<html><head><title>' + document.title + '</title>');
    mywindow.document.write('<link href="' + base_url + 'assets/css/main.css" rel="stylesheet" type="text/css">');
    mywindow.document.write('<link href="' + base_url + 'lib/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" type="text/css">');
    mywindow.document.write('<link href="' + base_url + 'lib/bootstrap-timepicker/bootstrap-timepicker.css" rel="stylesheet" type="text/css">');
    mywindow.document.write('<link href="' + base_url + 'lib/animate-css/animate.css" rel="stylesheet" type="text/css">');

    mywindow.document.write('</head><body>');
    var order_content = $(".Invoice-Address").html();
    mywindow.document.write('<div class="overlay__wrapper">');
    mywindow.document.write('<section class="content__wrapper has--sidebar-l bg--lightest-gray">');
    mywindow.document.write('<div class="content__main">');
    mywindow.document.write('<div class="row row--full-height">');
    mywindow.document.write('<div class="Invoice-Address">');

    mywindow.document.write(order_content);
    mywindow.document.write('</div>');
    mywindow.document.write('</div>');
    mywindow.document.write('</div>');
    mywindow.document.write('</section>');
    mywindow.document.write('</div>');
    mywindow.document.write('</body></html>');

    //mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/

    setTimeout(function () {
        mywindow.print();
    }, 2000);
    //mywindow.close();

    return true;

}

$(document).ready(function () {
    $(".product_Search").keyup(function () {
        var search = $(this).val();
        if (search == "") {
            $("#product_list").html("");
        } else {
            $.ajax({
                type: "POST",
                url: "search-promoProduct-AddProduct",
                dataType: 'json',
                data: {'search': search},
                success: function (data) {
                    if (data.length > 0) {
                        var htmlString = "";
                        htmlString += "<ul>"
                        for (var i = 0; i < data.length; i++) {
                            htmlString += '<li style="padding: 5px; border-bottom: 1px solid #aaa;"><a class="select_free_product" href="javascript:void(0)" data-product_id="' + data[i].product_id + '">' + data[i].name + '</a></li>';
                            //$('.product_Search').val(data[i].name);
                            //$('.product_SearchId').val(data[i].product_id);
                        }
                        htmlString += "</ul>";
                        $("#product_list").html(htmlString);
                        $("#product_list").show();
                    } else {
                        $("#product_list").html("No matching products");
                        $("#product_list").show();
                    }
                }
            });
        }
    });

    $(document).on("click", ".select_free_product", function () {
        $('.product_SearchId').val($(this).attr("data-product_id"));
        $("#product_list").hide();
        $('.product_Search').val($(this).text());
    });
});
$(document).on("click", ".assign_userLocation", function () {
    var location_id = $(this).attr("data-location_id");
    $(this).addClass('is--pos');
    $(this).removeClass('btn--tertiary');
    var user_id = $(this).val();
    $.ajax({
        type: "POST",
        url: "add-userIn-Organization-location",
        data: {'location_id': location_id, 'user_id': user_id},
        success: function (data) {
        }
    });
});
$(document).ready(function () {
    $('.userLocationAddSearch').keyup(function () {
        var search = $(this).val();
        var location_id = $('.location_id').val();
        var organization_id = $('.organization_id').val();
        $.ajax({
            type: "POST",
            url: "search-Organization-locationUsers",
            data: {'search': search, 'location_id': location_id, 'organization_id': organization_id},
            success: function (data) {
                $('#user_results ul').empty("");
                var data = JSON.parse(data);
                for (i = 0; i < data.length; i++) {
                    $('#user_results ul').append('<li class="user padding--s no--pad-l no--pad-r cf"><div class="entity__group"><div class="avatar avatar--s" style="background-image:url()"></div></div>' + data[i].first_name + '<button class="btn btn--s btn--tertiary btn--toggle float--right width--fixed-75 assign_userLocation btn--tertiary" data-location_id="' + location_id + '" value="' + data[i].user_id + '" data-before="Select" data-after="&#10003;" type="button"></button></li>');
                }
            }
        });
    });
});
$(document).ready(function () {
    var title = new Array();
    $('#selectAllLocation').click(function () {
        if ($('.singleLocation').attr('checked')) {
            $(".singleLocation").each(function () {
                $(this).attr('checked', false);
                var user_id = $(this).val();
                var index = title.indexOf(user_id);
                if (index > -1) {
                    title.splice(index, 1);
                }
                $('#user_id,#deleteUser_id,#product_id').val(title);

            });
            $(this).attr('checked', false);
        } else {
            $(this).attr('checked', true);
            $(".singleLocation").each(function () {
                $(this).attr('checked', true);
                var user_id = $(this).val();
                var index = title.indexOf(user_id);
                title[title.length] = user_id;
                $('#user_id,#deleteUser_id,#product_id').val(title);
                n = Object.keys(title).length;
                n += 'Items',
                        $('.item_count').html(n);

            });
        }
    });

    $('.singleLocation').click(function () {
        if ($(this).attr('checked')) {
            $(this).attr('checked', false);
            var user_id = $(this).val();
            var index = title.indexOf(user_id);
            if (index > -1) {
                title.splice(index, 1);
            }
            $('#user_id,#deleteUser_id,#product_id').val(title);
        } else {
            $(this).attr('checked', true);
            var user_id = $(this).val();
            title[title.length] = user_id;
            $('#user_id,#deleteUser_id,#product_id').val(title);
            n = Object.keys(title).length;
            n += 'Items',
                    $('.item_count').html(n);
        }
    });
});
$(document).on("click", ".inactiveProductPricing", function () {
    var productPricing_id = $(this).attr("data-productPricing_id");
    var product_id = $(this).attr("data-product_id");
    var vendor_id = $(this).val();
    $.ajax({
        type: "POST",
        url: "inactive-productPricing-SPdashboard",
        data: {'productPricing_id': productPricing_id, 'product_id': product_id, 'vendor_id': vendor_id},
        success: function (data) {
            if (data == '1') {
                location.reload();
            }
        }
    });
});
$(document).on("click", ".AddingProductPricing", function () {
    var productPricing_id = $(this).attr("data-productPricing_id");
    var product_id = $(this).attr("data-product_id");
    var vendor_id = $(this).val();
    $.ajax({
        type: "POST",
        url: "activate-productPricing-SPdashboard",
        data: {'productPricing_id': productPricing_id, 'product_id': product_id, 'vendor_id': vendor_id},
        success: function (data) {
            if (data == '1') {
                location.reload();
            }
        }
    });
});
$(document).ready(function () {
    $('#searchVendors').keyup(function () {
        var search = $(this).val();
        var product_id = $('.product_id').val();
        $.ajax({
            type: "POST",
            url: "search-vendor-SPCatalog",
            dataType: 'json',
            data: {'search': search, 'product_id': product_id},
            success: function (data) {
                $('#vendor_results').html("");
                for (i = 0; i < data.length; i++) {
                    $('#vendor_results').append('<li class="item card padding--xs cf"><div class="wrapper"><div class="wrapper__inner">' + data[i].name + '</div><div class="wrapper__inner align--right"><button class="btn btn--s btn--toggle width--fixed-75' + ((data[i].product_active.active == 1) ? " is--pos inactiveProductPricing " : " btn--tertiary AddingProductPricing ") + '" data-before="Select" data-after="&#10003;" type="button" data-product_id="' + product_id + '"  data-productPricing_id=" ' + data[i].product_active.id + ' " value=""></button></div></div></li>');
                }
            }
        });
    });
});
$(document).on('click', '.page__reloadLocation', function () {
    location.reload();
});
$(document).on('click', '.ActivateProduct', function () {
    var activate = $(this).attr('data-activate');
    var product_pricing_id = $(this).attr('data-product_pricing_id');
    $.ajax({
        type: "POST",
        url: "activate-vendor-Product",
        data: {'activate': activate, 'product_pricing_id': product_pricing_id},
        success: function (data) {
            if (data == '1') {
                location.reload();
            }
        }
    });
});
$(document).on('click', '#ProductPriceFilterstop', function () {
    return false;
});
$(document).on('click','.VendorTypeChange',function(){
    var vendor_type=$(this).attr('data-vendor_type');
    var vendor_id=$(this).attr('data-vendor_id');
    $.ajax({
        type: "POST",
        url: "change-vendor-Type",
        data: {'vendor_type':vendor_type,'vendor_id':vendor_id},
        success: function (data) {
            location.reload();
        }
    });
});
var Search = {
    init: function(){
        $(document).on('keyup', '#q', function(){
            // ajax search with current value
            Search.search($(this).val())
        });
        $(document).click(function(){
            $("#search-results").slideUp();
        });
        $("#search-results").click(function(e){
            e.stopPropagation();
        });
    },

    search(queryString){
        console.log('running search');
        console.log(queryString);
        $.ajax({
            url: '/api-search',
            method: 'POST',
            dataType: 'json',
            data: {query: queryString},
            success: function(response){
                $('#searchResultsList').empty();
                // console.log($('#searchResultsList').html())
                var results = '';
                response.results.forEach(function(item, index){
                    console.log(item)
                    results += Search.processResult(item);
                });
                $('#searchResultsList').html(results);
                $('#search-results').slideDown('fast');
            }
        });
    },

    processResult: function(result){
        console.log(result)
        // var item = "<div>" + result.name +"</div>";


        var item =  '<div class="row product product--list row multi--vendor has--promos req--license has--sale" data-target="/product">' +
                    '    <div class="search-image-holder">' +
                    '       <a class="image-small" href="/view-product?id=' + result.id + '&amp;category=' + (parseInt(result.category) ? result.category : '') + '" style="background-image:url(\'https://beta.matixdental.com/uploads/products/images/' + result.photo + '\');"></a>' +
                    '    </div>' +
                    '    <div class="product__data col-md-9 col-xs-12">' +
                    '        <span class=" is--link" data-target="/view-product?id=4&amp;category=1201">'+
                    '            <a class="" href="/view-product?id=' + result.id + '&amp;category=' + (parseInt(result.category) ? result.category : '') + '">' + result.name + '</a>' +
                    '        <span class="product__mfr"> by <a class="link fontWeight--2" href="#">' + result.manufacturer + '</a></span>' +
                    '                    <div style="font-size: 14px;font-weight: bold;">$' + result.retail_price + '</div>' +
                    '                    <div class="">$' + result.retail_price + ' (' + result.vendor_count + ' Vendors)</div>' +
                    '    </div>' +
                    '</div>';
        return item;
    }
}

$( document ).ready(function() {
    Search.init();
});
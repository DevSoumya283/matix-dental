// Styleguide Menu
$('.lsg__menu-item').click(function(){
    $('.lsg__menu-item').removeClass('--open');
    $(this).addClass('--open');
});

var color = '';
$('.lsg__color').click(function() {
    var x = $(this).css('backgroundColor');
    hexc(x);
    alert(color);
})

function hexc(colorval) {
    var parts = colorval.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
    delete(parts[0]);
    for (var i = 1; i <= 3; ++i) {
        parts[i] = parseInt(parts[i]).toString(16);
        if (parts[i].length == 1) parts[i] = '0' + parts[i];
    }
    color = '#' + parts.join('');
}

$('.lsg__color').each(function(){
    var bg = $(this).css('backgroundColor');
    hexc(bg);
    $(this).append('<span class="lsg__color-hex">' + color + '</span>');
});

// Modal Open
$('.modal--toggle').on('click', function(e) {
    var modal = $(this).data("target");
    $("body").addClass("has-modal");
    $(modal).addClass('is-visible');
});

// Modal Close
$(".modal__close, .modal__overlay").click(function(){
    $(".modal").removeClass('is-visible');
    setTimeout(function(){
        $("body").removeClass("has-modal");
    }, 400);
});

// Button Confirm
$(".btn--confirm").click(function(){
    var replace = $(this).data("replace");
    $(this).removeClass("btn--tertiary");
    $(this).addClass("is--added");
    $(this).html(replace);
});

// Button Toggle
$(".btn--toggle").click(function(){
    $(this).toggleClass("btn--tertiary is--pos");
});
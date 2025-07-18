// Modal Open
$('.modal--toggle').on('click', function(e) {
    var modal = $(this).data("target");
    activeModal = $(this).data("target");
    $("body").addClass("has-modal");
    if ($(document).height() > $(window).height()) {
        $("body").css({
        	"padding-right": $.scrollbarWidth()
        })
    }
    $(modal).addClass('is-visible');

    // If the modal has multi-states, open to the correct state
    if ($(this).is('[data-state]')){
        var stateTarget = $(this).data('state-target'),
            states = $(stateTarget).data('states'),
            state = $(this).data('state'),
            tabs = $(stateTarget).find('.tab__group input[type="radio"]'),
            activeTab = $(stateTarget).find('.tab__group .tab[value="' + state + '"] input[type="radio"]');

        $(stateTarget).removeClass(states);
        $(stateTarget).addClass(state);

        // Set the tabs to the correct state (if applicable)
        $(tabs).prop('checked', false);
        $(activeTab).prop('checked', true);
    }
    if ($(activeModal).find('.form--multistep').length){
       var first = $(activeModal).find('.form__group:first-child');
        $(activeModal).find('.form__group').hide();
        $(first).show();
   }
    // If contains there is a 'today date' input and it's not empty or changed
    if (($('.is--today').length) && (!$('.is--today').hasClass('not--empty'))){
        todayDate();
    }
});

// Modal Close
$(".modal__close, .modal__overlay, .default--action").click(function(){
    $(".modal").removeClass('is-visible');

    //Clear form contents, reset fields
    if ($(window.activeModal).find('form').length){
        var fields = $('.modal').find('.input');
        $('.modal').find('form')[0].reset();
        $(fields).removeClass('not--empty');
    }

    setTimeout(function(){
        $("body").removeClass("has-modal");
        if ($(document).height() > $(window).height()) {
            $("body").css({
            	"padding-right": "0"
            })
        }
    }, 400);
});

// Modal Overlap Open
$('.modal--overlap-btn').on('click', function(e) {
	$('.modal--overlap').removeClass("is-visible");
});

// Star Rating
$(".star-rating label").click(function(){
	$(this).parent().find("label").css({"background-color": "#C1C9D7"});
	$(this).css({"background-color": "#FFBC00"});
	$(this).nextAll().css({"background-color": "#FFBC00"});
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

// Assign User Button Toggle
$(".user").click(function(){
	$(this).find("button").toggleClass("btn--tertiary is--pos");
});

$(".user .btn--toggle").click(function(){
    $(this).toggleClass("btn--tertiary is--pos");
});

// Detect Scroll Bar Width
$.scrollbarWidth = function() {
  var parent, child, width;

  if(width===undefined) {
    parent = $('<div style="width:50px;height:50px;overflow:auto"><div/></div>').appendTo('body');
    child=parent.children();
    width=child.innerWidth()-child.height(99).innerWidth();
    parent.remove();
  }

 return width;
};

// Reorder Modal
$('#textareaReorder2 button').click(function(){
    $("#subTotal").fadeOut();
});

// Edit Textarea Instructions
$("#editInstructions").click(function(){
    $('#instructions').prop('disabled', false);
    $("#instructions").removeClass("is--disabled");
    $("#saveInstructions").removeClass("is--hidden");
});

$("#saveInstructions").click(function(){
    $('#instructions').prop('disabled', true);
    $("#instructions").addClass("is--disabled");
    $("#saveInstructions").addClass("is--hidden");
});

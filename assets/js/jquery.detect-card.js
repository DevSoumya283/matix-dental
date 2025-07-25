(function() {
  var $,
    __indexOf = [].indexOf || function(item) { for (var i = 0, l = this.length; i < l; i++) { if (i in this && this[i] === item) return i; } return -1; };

  $ = jQuery;

  $.fn.extend({
    detectCard: function(options) {
      var Card, log, settings;
      settings = {
        debug: false,
        supported: ['visa', 'mastercard', 'maestro', 'american-express', 'discover', 'jcb', 'diners-club']
      };
      settings = $.extend(settings, options);
      log = function(msg) {
        if (settings.debug) {
          return typeof console !== "undefined" && console !== null ? console.log(msg) : void 0;
        }
      };
      Card = (function() {
        function Card() {}

        Card.prototype.type = '';

        Card.prototype.detected_type = '';

        Card.prototype.number = '';

        Card.prototype.type_has_changed = function() {
          return this.type !== this.detected_type;
        };

        Card.prototype.detect_type = function() {
          this.detected_type = this.get_detected_type();
          if (this.detected_type == null) {
            this.detected_type = '';
          }
          return log("Card type detected was: " + this.detected_type);
        };

        Card.prototype.get_detected_type = function() {
          if (this.is_a_valid_number()) {
            log("Card number '" + this.number + "' is valid");
            return this.get_type();
          }
        };

        Card.prototype.get_type = function() {
          if (this.number.match(/^4/)) {
            return 'visa';
          } else if (this.number.match(/^5[1-5]/)) {
            return 'mastercard';
          } else if (this.number.match(/^3[47]/)) {
            return 'american-express';
          } else if (this.number.match(/^6(?:011|5)/)) {
            return 'discover';
          } else if (this.number.match(/^(?:2131|1800|35)/)) {
            return 'jcb';
          } else if (this.number.match(/^3(?:0[0-5]|[68])/)) {
            return 'diners-club';
          } else if (this.number.match(/^5018|5020|5038|5893|6304|67(59|61|62|63)|0604/)) {
            return 'maestro';
          } else {
            return '';
          }
        };

        Card.prototype.is_a_valid_number = function() {
          return this.number !== "" && !isNaN(this.number);
        };

        Card.prototype.update_type = function() {
          log("Changed card type from '" + this.type + "' to '" + this.detected_type + "'");
          return this.type = this.detected_type;
        };

        return Card;

      })();
      return this.each(function() {
        var card, get_card_number_from, remove_spaces_from;
        card = new Card;
        $(this).on('keyup', function(e) {
          var is_supported, _ref;
          card.number = get_card_number_from($(this).val());
          card.detect_type();
          if (card.type_has_changed()) {
            card.update_type();
            is_supported = (_ref = card.type, __indexOf.call(settings.supported, _ref) >= 0);
            return $(this).trigger('cardChange', {
              type: card.type,
              supported: is_supported
            });
          }
        });
        get_card_number_from = function(card_input) {
          return remove_spaces_from(card_input);
        };
        return remove_spaces_from = function(value) {
          return value.replace(/\s/g, '');
        };
      });
    }
  });

}).call(this);

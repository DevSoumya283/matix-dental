//Product Images Slider
$(window).load(function () {
  if ($(".flexslider").length) {
    $(".flexslider").flexslider({
      animation: "slide",
      controlNav: "thumbnails",
      directionNav: false,
      slideshow: false,
    });
  }
});
//New payment validation
var card_id = "";
var paymentCardName = "";
var from_reg = false;
$(document).ready(function () {
  $(document).on("click", ".add-payment", function () {
    if ($(this).hasClass("registration_process")) {
      from_reg = true;
    }
    var saveBtn = $(".add-payment"),
      initialText = $(".add-payment").text(),
      target = $(".add-payment").data("target"),
      nextStep = $(".add-payment").data("next");
    $(saveBtn).html('<div class="loader loader--light"></div>');
    $(saveBtn).attr("disabled", "disabled");
    if ($("#paymentMethods").hasClass("is--cc")) {
      //card details send to validations..
      var expiry = $("#paymentExpiry").val().split("/");
      console.log($("#paymentCardName").val());
      Stripe.card.createToken(
        {
          name: $("#paymentCardName").val(),
          number: $("#paymentCardNum").val(),
          cvc: $("#paymentSecurity").val(),
          exp_month: expiry[0],
          exp_year: expiry[1],
        },
        stripeCardResponseHandler
      );
    } else if ($("#paymentMethods").hasClass("is--bank")) {
      //bank account details send to validations..
      Stripe.bankAccount.createToken(
        {
          country: "US",
          currency: "USD",
          routing_number: $("#paymentRoutingNum").val(),
          account_number: $("#paymentAccountNum").val(),
          account_holder_name: $("#accountholderName").val(),
          account_holder_type: $("#accountholderType").val(),
        },
        stripeBankResponseHandler
      );
    }
    return false;
  });
  function stripeCardResponseHandler(status, response) {
    var saveBtn = $(".add-payment"),
      initialText = $(".add-payment").text(),
      target = $(".add-payment").data("target"),
      nextStep = $(".add-payment").data("next");
    $(saveBtn).html('<div class="loader loader--light"></div>');
    if (response.error) {
      // Problem!
      //   // Show the errors on the form
      $(".payment-errors").html(response.error.message);
      $(saveBtn).removeAttr("disabled");
      $(saveBtn).html(initialText).removeClass("is--pos");
      $("#newword").text("Save Payment Method");
    } else {
      // Token was created!
      //   // Get the token ID:
      $(saveBtn).removeAttr("disabled");
      $.ajax({
        type: "POST",
        url: base_url + "add-card-details",
        data: { token: response.id },
        success: function (data) {
          if (from_reg == true) {
            saveBtn.parents(".form__group").toggle(600);
            $(nextStep).toggle(600);
            $("html, body").animate({ scrollTop: 0 }, 600);
            $("#formAccount4").show();
            // increment progress bar
            var tar = $(saveBtn).closest(".form--multistep").find(".progress"),
              cur = $(tar).attr("data-progress");
            cur++;
            $(tar).attr("data-progress", cur);
            updateProgress(tar, cur);
          } else {
            $("#newPaymentForm").hide();
            $("#condNewPaymentMethod").hide();
            location.reload();
          }
          from_reg = false;
        },
      });
    }
  }

  function stripeBankResponseHandler(status, response) {
    var saveBtn = $(".add-payment"),
      initialText = $(".add-payment").text(),
      target = $(".add-payment").data("target"),
      nextStep = $(".add-payment").data("next");
    $(saveBtn).html('<div class="loader loader--light"></div>');
    if (response.error) {
      // Problem!
      $(".payment-errors").html(response.error.message);
      $(saveBtn).removeAttr("disabled");
      $(saveBtn).html(initialText).removeClass("is--pos");
      $("#newword").text("Save Payment Method");
    } else {
      // Token created!
      // Get the token ID:
      $(saveBtn).removeAttr("disabled");
      var linkHandler = Plaid.create({
        env: Config.plaid.env,
        clientName: "Dentomatix",
        key: Config.plaid.public_key,
        product: "auth",
        selectAccount: true,
        onSuccess: function (public_token, metadata) {
          // Send the public_token and account ID to your app server.
          $.ajax({
            type: "POST",
            url: base_url + "add-bank-details",
            data: {
              token: response.id,
              account_id: metadata.account_id,
              public_token: public_token,
            },
            success: function (data) {
              $(saveBtn).removeAttr("disabled");
              $(saveBtn)
                .html('<div class="checkmark"></div>')
                .addClass("is--pos");
              if (from_reg == true) {
                saveBtn.parents(".form__group").toggle(600);
                $(nextStep).toggle(600);
                $("html, body").animate({ scrollTop: 0 }, 600);
                $("#formAccount4").show();
                // increment progress bar
                var tar = $(saveBtn)
                    .closest(".form--multistep")
                    .find(".progress"),
                  cur = $(tar).attr("data-progress");
                cur++;
                $(tar).attr("data-progress", cur);
                updateProgress(tar, cur);
              } else {
                $("#newPaymentForm").hide();
                $("#condNewPaymentMethod").hide();
                location.reload();
              }
              from_reg = false;
            },
            error: function (jXHR, textStatus, errorThrown) {
              $(saveBtn).text(initialText).removeClass("is--pos");
              $(saveBtn).removeAttr("disabled");
              alert(errorThrown);
            },
          });
        },
      });
      linkHandler.open();
    }
  }
  //update payment validation
  $(document).on("click", ".update-card", function () {
    var saveBtn = $(".update-card"),
      initialText = $(".update-card").text(),
      target = $(".update-card").data("target"),
      nextStep = $(".update-card").data("next");
    $(saveBtn).html('<div class="loader loader--light"></div>');
    $(saveBtn).attr("disabled", "disabled");
    var card_id = $(".p_id").val();
    var updatepaymentCardName = $("#paymentCardName").val();
    var expiry = $("#paymentExpire").val();
    var arr = expiry.split("/");
    var exp_month = arr[0];
    var exp_year = arr[1];
    $.ajax({
      type: "POST",
      url: base_url + "update-card-details",
      data: {
        card_id: card_id,
        exp_month: exp_month,
        exp_year: exp_year,
        updatepaymentCardName: updatepaymentCardName,
      },
      success: function (data) {
        var data = JSON.parse(data);
        if (data.message != "") {
          $(".payment-errors").html(data.message);
          $(saveBtn).removeAttr("disabled");
          $(saveBtn).html(initialText).removeClass("is--pos");
        } else {
          $(saveBtn).removeAttr("disabled");
          $(saveBtn).html('<div class="checkmark"></div>').addClass("is—pos");
          $("#editCardForm").hide();
          location.reload();
        }
      },
    });
  });

  //update bank account details
  var bank_id = "";
  $(document).on("click", ".update_bank", function () {
    bank_id = $(".p_id").val();
    Stripe.bankAccount.createToken(
      {
        country: "US",
        currency: "USD",
        routing_number: $("#paymentRoutingNumber").val(),
        account_number: $("#paymentAccountNumber").val(),
        account_holder_name: $("#accountholdersName").val(),
        account_holder_type: $("#accountholdersType").val(),
      },
      stripeUpdateBankResponseHandler
    );
    return false;
  });
  function stripeUpdateBankResponseHandler(status, response) {
    var saveBtn = $(".update_bank"),
      initialText = $(".update_bank").text();
    $(saveBtn).attr("disabled", "disabled");
    $(saveBtn).html('<div class="loader loader--light"></div>');
    if (response.error) {
      // Problem!
      $(".payment-errors").html(response.error.message);
      $(saveBtn).text(initialText).removeClass("is--pos");
      $(saveBtn).removeAttr("disabled");
    } else {
      // Token created!
      // Get the token ID:
      var token = response.id;
      var bankname = response.bank_account.bank_name;
      var account_holder_name = response.bank_account.account_holder_name;
      var bank_acc4 = response.bank_account.last4;
      var routing_number = response.bank_account.routing_number;
      var linkHandler = Plaid.create({
        env: Config.plaid.env,
        clientName: "Dentomatix",
        key: Config.plaid.public_key,
        product: "auth",
        selectAccount: true,
        onSuccess: function (public_token, metadata) {
          // Send the public_token and account ID to your app server.
          $.ajax({
            type: "POST",
            url: base_url + "update-bank-details",
            data: {
              token: token,
              bank_id: bank_id,
              bankname: bankname,
              account_id: metadata.account_id,
              public_token: public_token,
              bank_acc4: bank_acc4,
              routing_number: routing_number,
              account_holder_name: account_holder_name,
            },
            success: function (data) {
              $(saveBtn).removeAttr("disabled");
              $(saveBtn)
                .html('<div class="checkmark"></div>')
                .addClass("is—pos");
              location.reload();
            },
            error: function (jXHR, textStatus, errorThrown) {
              $(saveBtn).text(initialText).removeClass("is--pos");
              $(saveBtn).removeAttr("disabled");
              alert(errorThrown);
            },
          });
        },
      });
      linkHandler.open();
    }
  }

  //create vendor bank account
  var vendor_dob = "";
  $('input[name="ssnLast"]').keyup(function (e) {
    if (/\D/g.test(this.value)) {
      // Filter non-digits from input value.
      this.value = this.value.replace(/\D/g, "");
    }
  });

  $(".add-vendor-bank").click(function () {
    vendor_dob = $("#vendorDob").val();
    var personalAddress1 = $("#personalAddress1").val();
    var personalCity = $("#personalCity").val();
    var personalZip = $("#personalZip").val();
    var ssnLast = $("#ssnLast").val();
    var companyTaxIDProvided = $("#companyTaxIDLabel").text().trim();
    var ssnLastProvided = $("#ssnLastLabel").text().trim();

    if (vendor_dob != "") {
      if (personalAddress1 != "" && personalCity != "" && personalZip != "") {
        Stripe.bankAccount.createToken(
          {
            country: "US",
            currency: "USD",
            routing_number: $("#paymentRoutingNum").val(),
            account_number: $("#paymentAccountNum").val(),
            account_holder_name: $("#accountHolderName").val(),
            account_holder_type: $("#accountholderType").val(),
          },
          stripeVendorBankResponseHandler
        );
        return false;
      } else {
        alert(
          "Personal details cannot be blank, please fill your personal address"
        );
        $("#personalAddress .link--expand").click();

        $("html,body").animate({
          scrollTop: $("#personalAddress").offset().top,
        });
      }
    } else {
      alert("Date of birth cannot be empty");
      return false;
    }
  });

  function stripeVendorBankResponseHandler(status, response) {
    var saveBtn = $(".add-vendor-bank"),
      initialText = $(".add-vendor-bank").text();
    $(saveBtn).attr("disabled", "disabled");
    $(saveBtn).html('<div class="loader loader--light"></div>');
    if (response.error) {
      // Problem!

      //   // Show the errors on the form
      $(".vendor-bank-errors").text(response.error.message);
      $(saveBtn).text(initialText).removeClass("is--pos");
      $(saveBtn).removeAttr("disabled");

      $(".payment-errors").html(response.error.message);
    } else {
      // Token created!
      // Get the token ID:
      var data = {
        token: response.id,
        bankname: response.bank_account.bank_name,
        bank_acc4: response.bank_account.last4,
        routing_number: response.bank_account.routing_number,
        account_holder_name: response.bank_account.account_holder_name,
        account_holder_type: response.bank_account.account_holder_type,
        vendor_dob: vendor_dob,
      };
      $.ajax({
        type: "POST",
        url: base_url + "add-vendor-bank-details",
        data: data,
        success: function (data) {
          $(saveBtn).removeAttr("disabled");
          $(saveBtn).html('<div class="checkmark"></div>').addClass("is—pos");
          location.reload();
        },
        error: function (jXHR, textStatus, errorThrown) {
          $(saveBtn).text(initialText).removeClass("is--pos");
          $(saveBtn).removeAttr("disabled");
          alert(errorThrown);
        },
      });
    }
  }

  $(document).on("click", ".cancel-order", function () {
    var order_id = $(this).data("id");
    $.ajax({
      type: "POST",
      url: base_url + "get-for-ordercancel",
      data: { order_id: order_id },
      success: function (data) {
        console.log("vendor loaded");
        var data = JSON.parse(data);
        var locations = "";
        if (data.location.nickname !== null) {
          locations += data.location.nickname + "<br>";
        }
        if (data.location.city !== null && data.location.city !== "") {
          locations += data.location.city + ",";
        }
        if (data.location.state !== null && data.location.state !== "") {
          locations += data.location.state + ",";
        }
        if (data.location.zip !== null && data.location.zip !== "") {
          locations += data.location.zip;
        }

        var vendor_phone = data.vendor.phone;
        var vendor_fax = data.vendor.fax;
        var vendor_logo = data.vendor.logo;
        $(".locations").html(locations);
        $(".order_id").val(order_id);
        $(".order_id").html(order_id);
        $(".inv__logo").attr("src", vendor_logo);
        $(".phone").html(vendor_phone);
        $(".fax").html(vendor_fax);
      },
      error: function (jXHR, textStatus, errorThrown) {
        alert(errorThrown);
      },
    });
  });
  //cancel restricted oreder by student before admin approved
  $(document).on("click", ".cancel_pending", function () {
    var saveBtn = $(this),
      initialText = $(this).text();
    $(saveBtn).attr("disabled", "disabled");
    $(saveBtn).html('<div class="loader loader--light"></div>');
    var restricted_id = $(this).data("id");
    $.ajax({
      type: "POST",
      url: base_url + "get-pendingorders",
      data: { restricted_id: restricted_id },
      success: function (data) {
        console.log("vendor loaded");
        $(saveBtn).removeAttr("disabled");
        $(saveBtn).html('<div class="checkmark"></div>').addClass("is—pos");
        var data = JSON.parse(data);
        var locations =
          data.location.nickname +
          "<br>" +
          data.location.city +
          "," +
          data.location.state +
          "," +
          data.location.zip;
        var vendor_phone = data.vendor.phone;
        var vendor_fax = data.vendor.fax;
        var vendor_logo = data.vendor.logo;
        $(".locations").html(locations);
        $(".restricted_id").val(restricted_id);
        $(".restricted_id").html(restricted_id);
        $(".inv__logo").attr("src", vendor_logo);
        $(".phone").html(vendor_phone);
        $(".fax").html(vendor_fax);
      },
      error: function (jXHR, textStatus, errorThrown) {
        $(saveBtn).text(initialText).removeClass("is--pos");
        $(saveBtn).removeAttr("disabled");
        alert(errorThrown);
      },
    });
  });

  $(document).on("click", ".recurring-orders", function () {
    var order_id = $(this).data("id");
    $(".order_id").val(order_id);
  });

  $(document).on("click", ".delete_recurring", function () {
    var recurring_id = $(this).data("id");
    -$(".recurring_id").val(recurring_id);
  });
  $(document).on("click", ".change-recurring-location", function () {
    var recurring_id = $(this).data("id");
    var location_name = $(this).data("location_name");
    $(".recurring_id").val(recurring_id);
    $(".location_name").html(location_name);
  });
  $(document).on("change", ".change-frequency", function () {
    var frequency = $(this).val();
    var recurring_id = $(".recurring_id").val();
    $.ajax({
      type: "POST",
      url: base_url + "update-frequency",
      data: { frequency: frequency, recurring_id: recurring_id },
      success: function (data) {
        location.reload();
      },
      error: function (jXHR, textStatus, errorThrown) {
        alert(errorThrown);
      },
    });
  });

  //cart checkout
  $(document).on("click", ".checkout", function () {
    var payment_token = $(".payment_token").val();
    $(".subtotal").each(function () {
      $total = Number($(this).html());
    });
    var total_amount = $(".total").html();
    $(".pay_token").val(payment_token);
    $(".total_cost").val(total_amount);
    return false;
  });

  // change shipping options in cart view
  $(document).on("change", ".shippings_type", function () {
    var s_type = $(this).val();
    var vendor_id = $(this).parent().find(".vendor_id").val();
    var counter = $(this).attr("data-counter");
    $.ajax({
      type: "POST",
      url: base_url + "get-shipping-price",
      data: { s_type: s_type, vendor_id: vendor_id },
      success: function (data) {
        location.reload();
      },
    });
  });
});

//cart quantity update
var mouse_in_qty = false;
var cursor_in_qty = false;
var product_qty = 0;
var product_row_id = 0;
$(document).on("mouseover", ".cart-qty", function () {
  mouse_in_qty = true;
  product_qty = $(this).val();
  product_row_id = $(this).closest("td").find(".row-id").val();
});

$(document).on("focus", ".cart-qty", function () {
  cursor_in_qty = true;
  product_qty = $(this).val();
  product_row_id = $(this).closest("td").find(".row-id").val();
});

$(document).on("mouseout", ".cart-qty", function () {
  var qty = $(this).val();
  var row_id = $(this).closest("td").find(".row-id").val();

  if (mouse_in_qty == true && qty != product_qty && row_id == product_row_id) {
    $.ajax({
      type: "POST",
      url: base_url + "update-cart",
      data: { qty: qty, row_id: row_id },
      success: function (data) {
        location.reload();
      },
    });
  }
});

$(document).on("blur", ".cart-qty", function () {
  var qty = $(this).val();
  var row_id = $(this).closest("td").find(".row-id").val();

  if (
    (mouse_in_qty == true || cursor_in_qty == true) &&
    qty != product_qty &&
    row_id == product_row_id
  ) {
    $.ajax({
      type: "POST",
      url: base_url + "update-cart",
      data: { qty: qty, row_id: row_id },
      success: function (data) {
        location.reload();
      },
    });
  }
  mouse_in_qty = false;
  cursor_in_qty = false;
  product_qty = 0;
  product_row_id = 0;
});

//move products cart to request lists
$(document).on("click", ".cart-to-rlist", function () {
  var row_id = $(this).data("rowid");
  var vendor_id = $(this).data("vendor");
  var cartlocation_id = $(".location_id").val();
  $.ajax({
    type: "POST",
    url: base_url + "cart-to-requestlist",
    data: {
      row_id: row_id,
      vendor_id: vendor_id,
      cartlocation_id: cartlocation_id,
    },
    success: function (data) {
      location.reload();
    },
  });
});
//delete applied promos from cart
$(document).on("click", ".remove-promo", function () {
  var promoremove_id = $(this).data("promoremove");
  $.ajax({
    type: "POST",
    url: base_url + "promoremove-cart",
    data: { promoremove_id: promoremove_id },
    success: function (data) {
      location.reload();
    },
  });
});

//delete cart products
$(document).on("click", ".cart-delete", function () {
  var row_id = $(this).data("rowid");
  var location_id = $(".location_id").val();
  $.ajax({
    type: "POST",
    url: base_url + "cart-clear",
    data: { row_id: row_id, location_id: location_id },
    success: function (data) {
      location.reload();
    },
  });
});

//applly promo codes in cart
var promocodes = [];
$(document).ready(function () {
  $(document).on("click", ".apply-promocode", function () {
    var promocode = $("#promoCode").val();
    var location_id = $(this).data("location_id");
    $.ajax({
      type: "POST",
      url: base_url + "get-promos",
      data: { promocode: promocode, location_id: location_id },
      success: function (data) {
        var data = JSON.parse(data);
        if (data.error != "") {
          $(".error_holder").html("");
          $(".error_holder").html(
            '<div class="banner is--neg"><span class="banner__text">' +
              data.error +
              '</span><a class="link link--icon dismiss--banner"><svg class="icon icon--x"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-x"></use></svg></a></div><br />'
          );
        } else {
          location.reload();
        }
      },
    });
  });
  $(document).on("click", ".show", function () {
    location.reload();
  });
});

$(document).ready(function () {
  $(document).on("click", ".class-rename", function () {
    var class_id = $(this).data("id");
    var class_name = $(this).data("classname");
    $(".class_name").val(class_name);
    $(".c_id").val(class_id);
  });
  $(document).on("click", ".approve_all", function () {
    //approve restricted orders
    var class_id = $(this).data("id");
    $(".c_id").val(class_id);
  });
  $(document).on("click", ".deny_all", function () {
    //reject restricted orders
    var order_id = $(this).data("id");
    $(".order_id").val(order_id);
  });
});

$(document).ready(function () {
  //before login try to add products,Redirect to login page
  $(document).on("click", ".user_login", function () {
    window.location.href = base_url + "signin";
  });

  $(document).on("click", ".close_return", function () {
    window.location.href = base_url + "history";
  });
  $(document).on("click", ".unauthentcated", function () {
    alert("You are not able To Purchase this Product");
  });
  $(document).on("click", ".order_history", function () {
    window.location.href = base_url + "history";
  });
  $(document).on("click", ".recurring_link", function () {
    window.location.href = base_url + "recurring";
  });
  $(document).on("click", ".vendor_rate_link", function () {
    window.location.href = base_url + "feedback";
  });
});

//uncheck radio stars in products filtering in home page
$(document).on("click", ".uncheck_radio", function () {
  $('input[name="rating"]').each(function (index, val) {
    $(val).attr("checked", false);
    $(".show_filter2clear").hide();
    rate_data = "";
    if ($(".checkbox_check").is(":checked")) {
      license = "Yes";
    } else {
      license = "";
    }
    if ($(".checkbox_cart").is(":checked")) {
      purchased = "Yes";
    } else {
      purchased = "";
    }
    option_value = $("#sort_products :selected").attr("value");
    page = "0";
    refresh_products();
  });
});
//uncheck check box in products filtering
$(document).on("click", ".uncheck", function () {
  $('input[name="checkbox"]').each(function (index, val) {
    $(val).attr("checked", false);
    $(".show_filter1clear").hide();
    if ($(".rates").is(":checked")) {
      rate_data = $("input[name=rating]:checked").val();
    } else {
      rate_data = "";
    }
    license = "";
    purchased = "";
    option_value = $("#sort_products :selected").attr("value");
    page = "0";
    refresh_products();
  });
});
// uncheck selected Filters from home page search
$(document).on("click", ".filter1", function () {
  if ($('input[name="checkbox"]').is(":checked")) {
    $(".show_filter1clear").show();
  } else {
    $(".show_filter1clear").hide();
  }
});
// uncheck selected ratings from home page search
$(document).on("click", ".filter2", function () {
  if ($('input[name="rating"]').is(":checked")) {
    $(".show_filter2clear").show();
  } else {
    $(".show_filter2clear").hide();
  }
});
$(document).on("click", ".answer_this", function () {
  //add new product answer.
  var q_id = $(this).data("id");
  var qstn = $(this).data("qsn");

  $(".qstn_id").val(q_id);
  $(".question").html(qstn);
});
$(document).ready(function () {
  //product answer upvote.
  $(document).on("click", ".answer_upvote", function () {
    var update_id = $(this).data("id");
    $.ajax({
      type: "POST",
      url: base_url + "upvote-answer",
      data: { update_id: update_id },
      success: function (data) {
        location.reload();
      },
    });
  });
  $(document).on("click", ".answer_downvote", function () {
    //product answer downvote.
    var update_id = $(this).data("id");
    $.ajax({
      type: "POST",
      url: base_url + "downvote-answer",
      data: { update_id: update_id },
      success: function (data) {
        location.reload();
      },
    });
  });

  $(document).on("click", ".review_upvote", function () {
    //product reviews upvote.
    var update_id = $(this).data("id");
    $.ajax({
      type: "POST",
      url: base_url + "upvote-review",
      data: { update_id: update_id },
      success: function (data) {
        location.reload();
      },
    });
  });
  $(document).on("click", ".review_downvote", function () {
    //product review downvote.
    var update_id = $(this).data("id");
    $.ajax({
      type: "POST",
      url: base_url + "downvote-review",
      data: { update_id: update_id },
      success: function (data) {
        location.reload();
      },
    });
  });

  $(document).on("click", ".answer_flag", function () {
    //product answer flagging.
    var answer_id = $(this).data("answer_id");
    var fquestion = $(this).data("question");
    var fanswer = $(this).data("answer");
    var pro_name = $(this).data("p_name");
    var pro_id = $(this).data("p_id");
    //alert(pro_name);
    $(".answer_id").val(answer_id);
    $(".question").val(fquestion);
    $(".answer").val(fanswer);
    $(".p_name").val(pro_name);
    $(".p_id").val(pro_id);
  });
  $(document).on("click", ".review_flag", function () {
    //product reviews flagging..
    var review_id = $(this).attr("data-review_id");
    var freview = $(this).data("review");
    var comment = $(this).data("comment");
    var pro_name = $(this).data("p_name");
    var pro_id = $(this).data("pro_id");
    $(".review_id").val(review_id);
    $(".review_title").val(freview);
    $(".review_comments").val(comment);
    $(".p_name").val(pro_name);
    $(".p_id").val(pro_id);
  });

  $(document).on("click", ".review_vendor_flag", function () {
    //product reviews flagging by vendor.
    var review_id = $(this).data("review_id");
    var freview = $(this).data("review");
    var comment = $(this).data("comment");
    var pro_name = $(this).data("p_name");
    var pro_id = $(this).data("pro_id");
    $(".review_id").val(review_id);
    $(".review_title").val(freview);
    $(".review_comments").val(comment);
    $(".p_name").val(pro_name);
    $(".p_id").val(pro_id);
  });
});

function todayDate() {
  var d = new Date(),
    month = d.getMonth() + 1,
    day = d.getDate(),
    output =
      (("" + day).length < 2 ? "0" : "") +
      day +
      "/" +
      (("" + month).length < 2 ? "0" : "") +
      month +
      "/" +
      (("" + month).length < 2 ? "0" : "") +
      d.getFullYear();
  //Set 'Today' inputs initial value
  if ($(".is--today").length) {
    $(".is--today").each(function () {
      $(this).val(output);
      $(this).addClass("not--empty");
    });
  }
}
// Today's Date
$(document).ready(function () {
  todayDate();
});

// Products radio buttons (pricing)
$(document).ready(function () {
  var vendor_id = 0;
  var vendor_price = "";
  var has_promo = false;

  $("#vendor_list_container input[type=radio]:first", this).attr(
    "checked",
    true
  );

  $(".vendor_id").click(function () {
    vendor_id = $(this).val();
    vendor_price = $("#vendor_price_" + vendor_id).attr("data-price");
    vendor_retail_price = $("#vendor_price_" + vendor_id).attr(
      "data-retail-price"
    );
    has_promo = $("#vendor_price_" + vendor_id).hasClass("has--promo");
    var product_price_html = "";

    if (has_promo) {
      product_price_html =
        "<ul class='list list--inline list--prices' data-promo=''> \
                                    <li class='retail-price' style='font-size: 22px;font-weight: bold;text-decoration:line-through'>$" +
        vendor_retail_price +
        "</li> \
                                    <li class='sale-price' style='color:#13C4A3;font-size: 22px;font-weight: bold;'>$" +
        vendor_price +
        "</li> \
                                  </ul>";
    } else {
      product_price_html =
        "<ul class='list list--inline'> \
                                    <li class='regular-price' style='font-size: 22px;font-weight: bold;'>$" +
        vendor_price +
        "</li> \
                                  </ul>";
    }
    product_price_html +=
      "<span class='product__vendor-range'>" +
      $(".product__vendor-range").html() +
      "</span>";
    $("#product_price").html(product_price_html);
  });
});

$(document).ready(function () {
  $(".end_order").change(function () {
    $start_date = $(".start_order").val();
    $end_date = $(".end_order").val();
    $.ajax({
      type: "POST",
      url: base_url + "view_select_order",
      data: { start_date: $start_date, end_date: $end_date },
      success: function (data) {
        if (data != null) {
          var total_res = JSON.parse(data);
          console.log(total_res);
          var htmlStr = "";
          $(".oListItems").remove();
          $(".Ocount").empty();
          $(".purchaseFrom").empty();
          $(".nickName").empty();
          $(".tSpent").empty();
          $(".Ocount").append(total_res.total_orders);
          $(".purchaseFrom").append(total_res.purchased_from + " Vendors");
          $(".nickName").append(total_res.top_vendor.name);
          $(".tSpent").append(total_res.total_spent);
          for (var i = 0; i < total_res.orders.length; i++) {
            htmlStr = $(".oList").append(
              '<tr class="oListItems">' +
                "<td>" +
                '<label class="control control__checkbox">' +
                '<input type="checkbox" name="checkboxRow">' +
                '<div class="control__indicator"></div>' +
                "</label>" +
                "</td>" +
                "<td>" +
                '<span class="fontWeight--2 id">' +
                total_res.orders[i].id +
                "</span>" +
                "</td>" +
                "<td>" +
                '<span class="fontWeight--2 name">' +
                total_res.orders[i].name +
                "</span>" +
                "</td>" +
                "<td>" +
                '<span class="fontWeight--2 nickname">' +
                total_res.orders[i].nickname +
                "</span>" +
                "</td>" +
                "<td>" +
                '<span class="fontWeight--2 order_at">' +
                total_res.created_at[i] +
                "</span>" +
                "</td>" +
                "<td>" +
                '<span class="fontWeight--2 order_total">' +
                "$" +
                total_res.orders[i].total +
                "</span>" +
                "</td>" +
                "</tr>"
            );
          }
        }
      },
    });
  });
});

//add rating
$(document).ready(function () {
  $(".add-rating").on("click", function () {
    var product_id = $(this).data("id");
    $(".pro_id").val(product_id);
  });
});
$(document).on("click", ".select_view", function () {
  $("#data_view").val($(this).attr("data-view"));
  if ($(this).attr("data-view") == "grid") {
    grid = 1;
  } else {
    grid = 0;
  }
});

// Tabs & Toggles
$(".tab").on("click", function (e) {
  var targetState = $(this).attr("value");
  if (!$(this).children("input").is(":checked")) {
    var states = $(this)
        .parents(".tab__group")
        .find(".tab")
        .map(function () {
          return $(this).attr("value");
        }),
      string = [];

    $.each(states, function (index, value) {
      var state = value;
      string.push(state);
    });
    var classes = string.join(" "),
      groupTarget = $(this).closest(".tab__group").data("target");
    $(groupTarget).removeClass(classes);
    $(groupTarget).addClass(targetState);
    e.preventDefault();
    $(this).children("input").prop("checked", true);
    // Add the appropriate state to the current url
    if ($(this).hasClass("state--toggle")) {
      //window.history.pushState("string", "Title", targetState);
      // TODO: Redraw charts if necessary
      if ($(groupTarget).find(".chart").length) {
        //code on redraw here
      }
    }
  }
});

$(document).on("click", ".cart_tab", function () {
  // dim other tabs
  $(".cart_tab").removeClass("on");
  $(this).addClass("on");

  $(".page__tab").addClass("d-none").hide();
  console.log($(this).data("orderid"));
  $("#order" + $(this).data("orderid"))
    .removeClass("d-none")
    .show();
  // highlight tabs

  // hide all carts

  //show cart
});

//Checking for the currently active page tab
$(function () {
  if (window.location.pathname.indexOf("/edit-profile") >= 0) {
    $(".editname").toggleClass("is--expanded");
  }
  if (window.location.pathname.indexOf("/edit-password") >= 0) {
    $(".editpassowrd").toggleClass("is--expanded");
    location.href = "#password_tab";
  }
  if ($(".state--toggle").length) {
    var path = window.location.pathname.replace(/\/$/, ""),
      active = path.split("/").pop(),
      tab = $(".tab[value=" + active + "]");
    $(".tab[value=" + active + "] input").prop("checked", true);
    var groupTarget = $(tab).closest(".tab__group").data("target");
    $(groupTarget).addClass(active);
  }
});

//Check current active page
$(function () {
  if ($(".is--main-nav").length) {
    var url = window.location.pathname.replace(/\/$/, "");
    var page = url.substr(url.lastIndexOf("/") + 1);
    var curLink = $('.link[href$="' + page + '"]');
    var curItem = $(curLink).closest(".item--parent");

    $(curItem).addClass("is--expanded");
    $(curLink).addClass("is--active");
  }
  if ($(".state--toggle").length) {
  }
});

//Calculate field label widths and label padding
$(window).load(function () {
  $(".input__group.is--inline")
    .not(".no-padding")
    .each(function () {
      var labelWidth = $(this).find(".label").width() + 32;
      $(this)
        .find(".input")
        .css({ "padding-right": labelWidth + "px" });
    });
});
$(document).ready(function () {
  var headerHeight = $("header").outerHeight(),
    footerHeight = 0;
  $(".footer").each(function () {
    footerHeight += $(this).outerHeight();
  });
  bodyHeight = $(window).height() - headerHeight - footerHeight;
  $(".content__wrapper").css({ "min-height": bodyHeight + "px" });
});

function fillRemaining(target) {
  var headerHeight = $("header").outerHeight(),
    bodyHeight = $(window).height() - headerHeight;
  $(target).outerHeight(bodyHeight + "px");
}

$(document).ready(fillRemaining(".container--fill"));
$(window).resize(function () {
  fillRemaining(".container--fill");
});
$(function () {
  // Browse Dropdown
  $(document).on("click", ".link--toggle, .overlay__browse", function () {
    var tar = $(this).data("target");
    $(tar).toggleClass("is--open");
    $(".overlay__wrapper").toggleClass("has--overlay");
  });
});

// Multi Menus
$(".multi__menu--toggle").hover(function () {
  var parent = $(this).closest(".multi__menu"),
    toggle = $(parent).find(".multi__menu--toggle"),
    tarClass = $(parent).find(".multi__menu--state"),
    target = $(this).data("target");

  $(toggle).removeClass("is--active");
  $(tarClass).removeClass("is--active");
  $(this).addClass("is--active");
  $(target).addClass("is--active");
});
$(document).on("click", ".category_clear", function () {
  $(".subchild").hide();
  $(".category_clear").hide();
  location.reload();
});

// Linkable Elements
$(".is--link").click(function () {
  var target = $(this).data("target");
  window.location.href = target;
});

$("#sort_products3").on("change", function () {
  option_value = $("#sort_products3 :selected").attr("value");
  page = 0;
  refresh_products();
});
//main parent view
$(document).on("click", ".cat_view", function () {
  selectCategory(this);
});
$(document).on("click", ".cat_view_header", function () {
  selectCategoryheader(this);
});

$(document).on("click", ".cat-rowul .refresh--content .link", function () {
  selectCategoryheader(this);
});

// Category Trees
$(document).on("click", ".list--tree .refresh--content .link", function () {
  selectCategory(this);
});

function splitUp(arr, n) {
  var rest = arr.length % n, // how much to divide
    restUsed = rest, // to keep track of the division over the elements
    partLength = Math.floor(arr.length / n),
    result = [];

  for (var i = 0; i < arr.length; i += partLength) {
    var end = partLength + i,
      add = false;

    if (rest !== 0 && restUsed) {
      // should add one element for the division
      end++;
      restUsed--; // we've used one division element now
      add = true;
    }

    result.push(arr.slice(i, end)); // part of the array

    if (add) {
      i++; // also increment i in the case we added an extra element for division
    }
  }

  return result;
}

//browse categories
$(document).ready(function () {
  function truncate(str, no_words) {
    return str.split(" ").splice(0, no_words).join(" ");
  }

  $(document).on("click", "view_category_mob", function () {
    console.log("clicked");
  });

  $(document).on("click", ".view_category", function () {
    $(".alpha-row").addClass("d-none");
    $.ajax({
      type: "GET",
      url: base_url + "view-category",
      success: function (data) {
        var data = JSON.parse(data);
        var categories = "";
        var chunks = splitUp(data.categories, 4);
        // var chunks=splitUp(data.categories,4);
        if (data.categories != null) {
          // for (var i = 0; i < data.categories.length; i++) {
          //    categories += "<div class='col col--3-of-12'><ul class='list list--categories'><li><a class='link browse_category' href='javascript:;' catid=" + data.categories[i].id + ">" + data.categories[i].name + "</a></li></ul></div>";
          // }
          if (typeof chunks[0] != "undefined") {
            categories +=
              "<div class='col-md-3'><ul class='menulist menu-category'>";

            for (var i = 0; i < chunks[0].length; i++) {
              categories +=
                "<li><a href='#' data-category_id='" +
                chunks[0][i].id +
                "'>" +
                chunks[0][i].name +
                "</a></li>";
            }
            categories += "</ul></div>";
          }
          if (typeof chunks[1] != "undefined") {
            categories +=
              "<div class='col-md-3'><ul class='menulist menu-category'>";
            for (var i = 0; i < chunks[1].length; i++) {
              categories +=
                "<li><a href='#' data-category_id='" +
                chunks[1][i].id +
                "'>" +
                chunks[1][i].name +
                "</a></li>";
            }
            categories += "</ul></div>";
          }
          if (typeof chunks[2] != "undefined") {
            categories +=
              "<div class='col-md-3'><ul class='menulist menu-category'>";
            for (var i = 0; i < chunks[2].length; i++) {
              categories +=
                "<li><a href='#' data-category_id='" +
                chunks[2][i].id +
                "'>" +
                chunks[2][i].name +
                "</a></li>";
            }
            categories += "</ul></div>";
          }
          if (typeof chunks[2] != "undefined") {
            categories +=
              "<div class='col-md-3'><ul class='menulist menu-category'>";
            for (var i = 0; i < chunks[3].length; i++) {
              categories +=
                "<li><a href='#' data-category_id='" +
                chunks[3][i].id +
                "'>" +
                chunks[3][i].name +
                "</a></li>";
            }
            categories += "</ul></div>";
          }
        } //endif

        $(".browse_categories").html(categories);

        // add links
        $(".menu-category")
          .find("a")
          .on("click", function () {
            category = $(this).data("category_id");
            procedure = "";
            vendor_id = "";
            manufacturer = "";
            listid = "";
            search = "";
            page = 0;
            rebuildCatNav(category);
            $(".link--toggle").click();
          });
      },
    });
  });

  $(document).on("click", ".browse_category", function () {
    var cat = $(this).attr("catid");
    if (document.location.pathname.match(/[^\/]+$/)[0] != "home") {
      window.location.href = "/home?category=" + cat;
      return;
    }
    loadCats($("#classic"), 1, $(this).attr("catid"));
    $(".link--toggle").click();
    category = $(this).attr("catid");
    procedure = "";
    vendor_id = "";
    manufacturer = "";
    listid = "";
    search = "";
    page = 0;
  });
});
//no results catgories
$(document).on("click", ".selectcategory", function () {
  category = $(this).data("id");
  procedure = "";
  vendor_id = "";
  manufacturer = "";
  listid = "";
  search = "";
  page = 0;
  refresh_products();
});

//load procedures
$(document).ready(function () {
  function truncate(str, no_words) {
    return str.split(" ").splice(0, no_words).join(" ");
  }
  $(".view_procedure").on("click", function () {
    $(".alpha-row").addClass("d-none");
    $.ajax({
      type: "POST",
      url: base_url + "view-procedures",
      success: function (data) {
        var data = JSON.parse(data);
        console.log(data);
        var product_pro = "";
        var product_pro2 = "";
        if (data.procedures != null) {
          var chunks = splitUp(data.procedures, 4);
          var chunks2 = splitUp(data.procedures, 2);

          $(chunks).each(function (i, chunk) {
            product_pro +=
              "<div class='col-md-3'><ul class='menulist menu-procedure'>";
            $(chunk).each(function (j, procedure) {
              console.log(procedure);
              product_pro +=
                "<li><a href='#' data-procedure='" +
                procedure.product_procedures +
                "'>" +
                procedure.product_procedures;
            });
            product_pro += "</ul></div>";
          });

          $(chunks2).each(function (i, chunk) {
            product_pro2 +=
              "<div class='col-md-3'><ul class='menulist menu-procedure'>";
            $(chunk).each(function (j, procedure) {
              product_pro2 +=
                "<li><a href='#' data-procedure='" +
                procedure.product_procedures +
                "'>" +
                procedure.product_procedures;
            });
            product_pro2 += "</ul></div>";
          });
        } //endif
        else {
          product_pro =
            "<ul class='list list--categories'><li class='item item--parent refresh--content'>No items found</li></ul>";
          product_pro2 +=
            "<div class='col-xs-6'><ul class='menulist menu-procedure'><li>No items found</li></ul></div>";
        }
        console.log(data.procedures);

        $(".product_procedures").html(product_pro);
        $(".menu-row").html(product_pro2);

        $(".menu-procedure")
          .find("a")
          .on("click", function () {
            procedure = $(this).data("procedure");
            category = "";
            vendor_id = "";
            manufacturer = "";
            listid = "";
            search = "";
            page = 0;
            rebuildCatNav(category);
            $(".link--toggle").click();
          });
      },
    });
  });
  $(document).on("click", ".browse_procedures", function () {
    $(".link--toggle").click();
    procedure = $(this).attr("proid");
    vendor_id = "";
    manufacturer = "";
    listid = "";
    search = "";
    category = "";
    page = 0;
    refresh_products();
  });
});

//load vendors
$(document).ready(function () {
  function truncate(str, no_words) {
    return str.split(" ").splice(0, no_words).join(" ");
  }

  $(".view_vendor").on("click", function () {
    $(".alpha-row").addClass("d-none");

    $.ajax({
      type: "POST",
      url: base_url + "get-vendors",
      success: function (data) {
        var data = JSON.parse(data);
        var vendors = "";
        var vendors2 = "";
        var chunks = splitUp(data.vendors, 3);
        var chunks2 = splitUp(data.vendors, 2);

        $(chunks).each(function (i, chunk) {
          vendors += "<div class='col-md-3'><ul class='menulist menu-vendors'>";
          $(chunk).each(function (j, vendor) {
            vendors +=
              "<li><a href='#' data-vendor_id='" +
              vendor.id +
              "'>" +
              vendor.name +
              "</a></li>";
          });
          vendors += "</ul></div>";
        });
        $(chunks2).each(function (i, chunk) {
          vendors2 +=
            "<div class='col-md-3'><ul class='menulist menu-vendors'>";
          $(chunk).each(function (j, vendor) {
            vendors2 +=
              "<li><a href='#' data-vendor_id='" +
              vendor.id +
              "'>" +
              vendor.name +
              "</a></li>";
          });
          vendors2 += "</ul></div>";
        });
        // console.log(vendors);

        $(".vendor_data").html(vendors);
        $(".menu-row").html(vendors2);
        // add links
        $(".menu-vendors")
          .find("a")
          .on("click", function () {
            procedure = "";
            category = "";
            vendor_id = $(this).data("vendor_id");
            manufacturer = "";
            listid = "";
            search = "";
            page = 0;
            rebuildCatNav(category);
            $(".link--toggle").click();
          });
      },
    });
  });

  $(document).on("click", ".browse_vendors", function () {
    $(".link--toggle").click();
    vendor_id = $(this).attr("venid");
    category = "";
    procedure = "";
    manufacturer = "";
    listid = "";
    search = "";
    page = 0;
    refresh_products();
  });
});
$(document).on("click", ".browse_vendor", function () {
  vendor_id = $(this).data("venid");
  category = "";
  procedure = "";
  manufacturer = "";
  listid = "";
  search = "";
  page = 0;
  refresh_products();
});
//load mfc
$(document).ready(function () {
  function truncate(str, no_words) {
    return str.split(" ").splice(0, no_words).join(" ");
  }
  $(".view_mfc").on("click", function () {
    $("li.link").removeClass("on");
    $("li:contains('A')").addClass("on");
    $(".alpha-row").removeClass("d-none");
    $(".alphas").css("background-color", "#fff");
    $(".A").css("background-color", "yellow");

    $.ajax({
      type: "POST",
      url: base_url + "view-manufactures",
      data: { startingLetter: "A" },
      success: function (data) {
        var data = JSON.parse(data);
        var chunks2 = splitUp(data.manufacturer, 2);
        var chunks = splitUp(data.manufacturer, 4);
        // console.log(data);
        var mfc = "",
          mfc2 = "",
          letters = "";
        // for (var i = 0; i < data.manufacturer.length; i++) {
        //     mfc += "<div class='col col--3-of-12'><ul class='list list--categories'><li class='item item--parent refresh--content'><a class='link browse_mfc' href='javascript:;' mfcid='" + data.manufacturer[i].manufacturer + "'>" + data.manufacturer[i].manufacturer + "</a></li></ul></div>";
        // }

        mfc += "<div class='col-md-3'><ul class='menulist'>";
        if (typeof chunks[0] != "undefined") {
          for (var i = 0; i < chunks[0].length; i++) {
            mfc +=
              "<li><a href='#' data-manufacturer_id='" +
              chunks[0][i].manufacturer +
              "'>" +
              chunks[0][i].manufacturer +
              "</a></li>";
          }
          mfc += "</ul></div>";
        }
        mfc += "<div class='col-md-3'><ul class='menulist'>";
        if (typeof chunks[1] != "undefined") {
          for (var i = 0; i < chunks[1].length; i++) {
            mfc +=
              "<li><a href='#' data-manufacturer_id='" +
              chunks[1][i].manufacturer +
              "'>" +
              chunks[1][i].manufacturer +
              "</a></li>";
          }
          mfc += "</ul></div>";
        }
        mfc += "<div class='col-md-3'><ul class='menulist'>";
        if (typeof chunks[2] != "undefined") {
          for (var i = 0; i < chunks[2].length; i++) {
            mfc +=
              "<li><a href='#' data-manufacturer_id='" +
              chunks[2][i].manufacturer +
              "'>" +
              chunks[2][i].manufacturer +
              "</a></li>";
          }
          mfc += "</ul></div>";
        }
        mfc += "<div class='col-md-3'><ul class='menulist'>";
        if (typeof chunks[3] != "undefined") {
          for (var i = 0; i < chunks[3].length; i++) {
            mfc +=
              "<li><a href='#' data-manufacturer_id='" +
              chunks[3][i].manufacturer +
              "'>" +
              chunks[3][i].manufacturer +
              "</a></li>";
          }
          mfc += "</ul></div>";
        }

        if (typeof chunks2[0] != "undefined") {
          mfc2 += "<div class='col-xs-6'><ul class='menulist'>";
          for (var i = 0; i < chunks2[0].length; i++) {
            var limited_str = truncate(chunks2[0][i].manufacturer, 2);
            mfc2 +=
              "<li class='pullmenu-items'><a href='#' data-manufacturer_id='" +
              chunks2[0][i].manufacturer +
              "'>" +
              limited_str +
              "</a></li>";
          }
          mfc2 += "</ul></div>";
        }
        if (typeof chunks2[1] != "undefined") {
          mfc2 += "<div class='col-xs-6'><ul class='menulist'>";
          for (var i = 0; i < chunks2[1].length; i++) {
            var limited_str = truncate(chunks2[1][i].manufacturer, 2);
            mfc2 +=
              "<li class='pullmenu-items'><a href='#' data-manufacturer_id='" +
              chunks2[1][i].manufacturer +
              "'>" +
              limited_str +
              "</a></li>";
          }
          mfc2 += "</ul></div>";
        }

        $(".mfc_data_holder").html(mfc);
        // console.log(mfc);
        $(".menu-row").html(mfc2);
        var numberFound = false;
        for (var i = 0; i < data.letters.length; i++) {
          // console.log(typeof data.letters[i])
          if (!isNaN(data.letters[i])) {
            // console.log(data.letters[i]);
            if (!numberFound) {
              // console.log('change number to 0-9')
              data.letters[i] = "0-9";
              numberFound = true;
            } else {
              continue;
            }
          }
        }

        $(".mfc_data_holder")
          .find("a")
          .on("click", function () {
            procedure = "";
            category = "";
            vendor_id = "";
            manufacturer = $(this).data("manufacturer_id");
            listid = "";
            search = "";
            page = 0;
            $(".link--toggle").click();
            rebuildCatNav();
          });
      },
    });
  });

  $("li.link").on("click", function () {
    console.log("loading mans");
    $("li.link").removeClass("on");
    $(this).addClass("on");
    $.ajax({
      type: "POST",
      url: base_url + "view-manufactures",
      data: { startingLetter: $(this).text() },
      success: function (data) {
        var data = JSON.parse(data);
        var chunks = splitUp(data.manufacturer, 4);
        var mfc = "";
        // for (var i = 0; i < data.manufacturer.length; i++) {
        //     mfc += "<div class='col col--3-of-12'><ul class='list list--categories'><li class='item item--parent refresh--content'><a class='link browse_mfc' href='javascript:;' mfcid='" + data.manufacturer[i].manufacturer + "'>" + data.manufacturer[i].manufacturer + "</a></li></ul></div>";
        // }
        if (typeof chunks[0] != "undefined") {
          mfc += "<div class='col-md-3'><ul class='menulist'>";
          for (var i = 0; i < chunks[0].length; i++) {
            mfc +=
              "<li><a href='#' data-manufacturer_id='" +
              chunks[0][i].manufacturer +
              "'>" +
              chunks[0][i].manufacturer +
              "</a></li>";
          }
          mfc += "</ul></div>";
        }

        if (typeof chunks[1] != "undefined") {
          mfc += "<div class='col-md-3'><ul class='menulist'>";
          for (var i = 0; i < chunks[1].length; i++) {
            mfc +=
              "<li><a href='#' data-manufacturer_id='" +
              chunks[1][i].manufacturer +
              "'>" +
              chunks[1][i].manufacturer +
              "</a></li>";
          }
          mfc += "</ul></div>";
        }

        if (typeof chunks[2] != "undefined") {
          mfc += "<div class='col-md-3'><ul class='menulist'>";
          for (var i = 0; i < chunks[2].length; i++) {
            mfc +=
              "<li><a href='#' data-manufacturer_id='" +
              chunks[2][i].manufacturer +
              "'>" +
              chunks[2][i].manufacturer +
              "</a></li>";
          }
          mfc += "</ul></div>";
        }

        if (typeof chunks[3] != "undefined") {
          mfc += "<div class='col-md-3'><ul class='menulist'>";
          for (var i = 0; i < chunks[3].length; i++) {
            mfc +=
              "<li><a href='#' data-manufacturer_id='" +
              chunks[3][i].manufacturer +
              "'>" +
              chunks[3][i].manufacturer +
              "</a></li>";
          }
          mfc += "</ul></div>";
        }

        $(".mfc_data_holder").html(mfc);

        $(".mfc_data_holder")
          .find("a")
          .on("click", function () {
            procedure = "";
            category = "";
            vendor_id = "";
            manufacturer = $(this).data("manufacturer_id");
            listid = "";
            search = "";
            page = 0;
            $(".link--toggle").click();
            rebuildCatNav();
          });
      },
    });
  });

  // $('.alphabet li').on('click', function(e){
  //    var letter=$(this).text();
  //     e.preventDefault();
  //            $('.alphas').css("background-color", "#fff");
  //            $('.'+letter).css("background-color", "yellow");

  //    $.ajax({
  //        type: "POST",
  //        url: base_url + "view-manufactures",
  //        data: ({'startingLetter': letter}),
  //        success: function (data) {
  //            console.log(data);
  //            var data = JSON.parse(data);
  //             var chunks=splitUp(data.manufacturer, 2);
  //            var mfc = "";
  //            for (var i = 0; i < data.manufacturer.length; i++) {
  //              //  mfc += "<a class='dropdown-item' href='"+ data.manufacturer[i].manufacturer +"'>"+ data.manufacturer[i].manufacturer + "</a>";
  //            }
  //             mfc += "<div class='col-xs-6'><ul class='menulist'>";
  //            for(var i = 0; i < chunks[0].length; i++) {
  //                var limited_str=truncate(chunks[0][i].manufacturer,2);
  //                   mfc += "<li class='pullmenu-items'><a href='#' data-manufacturer_id='" + chunks[0][i].manufacturer +"'>"+ limited_str + "</a></li>";
  //            }
  //             mfc += "</ul></div>";
  //             mfc += "<div class='col-xs-6'><ul class='menulist'>";
  //            for(var i = 0; i < chunks[1].length; i++) {
  //                var limited_str=truncate(chunks[1][i].manufacturer,2);
  //                   mfc += "<li class='pullmenu-items'><a href='#' data-manufacturer_id='" + chunks[1][i].manufacturer +"'>"+ limited_str + "</a></li>";
  //            }
  //            mfc += "</ul></div>";
  //            $('.menu-row').html(mfc);

  //            $('.mfc_data_holder').find('a').on('click', function(){
  //                procedure = "";
  //                category = "";
  //                vendor_id = ""
  //                manufacturer = $(this).data('manufacturer_id');;
  //                listid = "";
  //                search = "";
  //                page = 0;
  //                console.log('closing');
  //                $('.link--toggle').click();
  //                rebuildCatNav(category);
  //            })
  //        }
  //    });
  // })

  // $(document).on("click", ".browse_mfc", function () {
  //     $('.link--toggle').click();
  //     manufacturer = $(this).attr('mfcid');
  //     vendor_id = "";
  //     category = "";
  //     procedure = "";
  //     listid = "";
  //     search = "";
  //     page = 0;
  //     refresh_products();

  // });
});
//view super admin Product List
$(document).ready(function () {
  //      NOTE :  The code is commented because it is redirecting the dashboard in vendor section.
  $(document).on("click", ".product__name", function () {
    if ($(this).data("target") !== undefined && $(this).data("target") != "") {
      location.href = $(this).data("target");
    }
  });
  $(".view-pro-list").on("click", function () {
    $(".alpha-row").addClass("d-none");

    $.ajax({
      type: "POST",
      url: base_url + "get-product-lists",
      success: function (data) {
        var data = JSON.parse(data);
        var product_list = "";
        var product_list2 = "";
        var chunks = splitUp(data.shopping_list, 3);
        var chunks2 = splitUp(data.shopping_list, 2);
        if (data.shopping_list.length > 0) {
          // for (var i = 0; i < data.shopping_list.length; i++) {
          //     product_list += "<div class='col col--3-of-12'><ul class='list list--categories'><li class='item item--parent refresh--content'><a class='link view_list' href='javascript:;' listid=" + data.shopping_list[i].id + ">" + data.shopping_list[i].listname + "</a></li></ul></div>";
          // }

          if (typeof chunks[0] != "undefined") {
            product_list += "<div class='col-md-3'><ul class='menulist'>";
            for (var i = 0; i < chunks[0].length; i++) {
              product_list +=
                "<li><a href='#' data-list_id='" +
                chunks[0][i].id +
                "'>" +
                chunks[0][i].listname +
                "</a></li>";
            }
            product_list += "</ul></div>";
          }

          if (typeof chunks[1] != "undefined") {
            product_list += "<div class='col-md-3'><ul class='menulist'>";
            for (var i = 0; i < chunks[1].length; i++) {
              product_list +=
                "<li><a href='#' data-list_id='" +
                chunks[1][i].id +
                "'>" +
                chunks[1][i].listname +
                "</a></li>";
            }
            product_list += "</ul></div>";
          }

          if (typeof chunks[2] != "undefined") {
            product_list += "<div class='col-md-3'><ul class='menulist'>";
            for (var i = 0; i < chunks[2].length; i++) {
              product_list +=
                "<li><a href='#' data-list_id='" +
                chunks[2][i].id +
                "'>" +
                chunks[2][i].listname +
                "</a></li>";
            }
            product_list += "</ul></div>";
          }

          if (typeof chunks[3] != "undefined") {
            product_list += "<div class='col-md-3'><ul class='menulist'>";
            for (var i = 0; i < chunks[3].length; i++) {
              product_list +=
                "<li><a href='home?category=" +
                chunks[3][i].id +
                "'>" +
                chunks[3][i].listname +
                "</a></li>";
            }
            product_list += "</ul></div>";
          }
          //for mobile
          product_list2 += "<div class='col-xs-6'><ul class='menulist'>";
          for (var i = 0; i < chunks2[0].length; i++) {
            product_list2 +=
              "<li class='pullmenu-items'><a href='#' data-list_id='" +
              chunks2[0][i].id +
              "'>" +
              chunks2[0][i].listname +
              "</a></li>";
          }
          product_list2 += "</ul></div>";
          product_list2 += "<div class='col-xs-6'><ul class='menulist'>";
          for (var i = 0; i < chunks2[1].length; i++) {
            product_list2 +=
              "<li class='pullmenu-items'><a href='#' data-list_id='" +
              chunks2[1][i].id +
              "'>" +
              chunks2[1][i].listname +
              "</a></li>";
          }
          product_list2 += "</ul></div>";
        } else {
          product_list =
            "<ul class='list list--categories'><li class='item item--parent refresh--content'>No items found</li></ul>";
          product_list2 +=
            "<div class='col-xs-6'><ul class='menulist'><li>No items found</li></ul></div>";
        }

        $(".list_data").html(product_list);
        $(".menu-row").html(product_list2);

        $(".menulist")
          .find("a")
          .on("click", function () {
            procedure = "";
            category = "";
            vendor_id = "";
            manufacturer = "";
            listid = $(this).data("list_id");
            search = "";
            page = 0;
            $(".link--toggle").click();
            rebuildCatNav();
          });
      },
    });
  });
});

//search  unlicenced products

$(document).on("click", ".license_data", function () {
  if ($(".checkbox_check").is(":checked")) {
    license = "Yes";
    page = "0";
    refresh_products();
  } else {
    license = "";
    refresh_products();
  }
});

$(document).on("change", ".filter-select", function () {
  if ($(this).val() == "License not required") {
    license = "Yes";
    page = "0";
    refresh_products();
  } else if ($(this).val() == "Items I've Purchased") {
    purchased = "Yes";
    page = "0";
    refresh_products();
  } else if ($(this).val() == "Highest Rated") {
    rate_data = 5;
    page = "0";
    refresh_products();
  } else {
    license = "";
    purchased = "";
    rate_data = "";
    refresh_products();
  }
});

//load  I have purchased products

$(document).on("click", ".myproduct", function () {
  if ($(".checkbox_cart").is(":checked")) {
    purchased = "Yes";
    page = "0";
    refresh_products();
  } else {
    purchased = "";
    refresh_products();
  }
});

function refresh_products() {
  //search product details ,based on user selected options...
  if (
    window.location.pathname.indexOf("/en") >= 0 ||
    window.location.pathname.indexOf("/home") >= 0 ||
    window.location.pathname.indexOf("/search") >= 0
  ) {
    $.ajax({
      type: "GET",
      url:
        base_url +
        "search-products?grid=" +
        grid +
        "&search=" +
        search +
        "&rate_data=" +
        rate_data +
        "&license=" +
        license +
        "&category=" +
        (parseInt(category) ? category : "") +
        "&option_value=" +
        option_value +
        "&manufacturer=" +
        manufacturer +
        "&purchased=" +
        purchased +
        "&procedure=" +
        procedure +
        "&vendor_id=" +
        vendor_id +
        "&listid=" +
        listid +
        "&page=" +
        page +
        "&per_page=" +
        per_page,
      success: function (data) {
        $("#pagination_results").hide();
        $("#resultsWrapper").html(data);
        clearTimeout();
        setTimeout(function () {
          $(".loader").fadeOut(2000);
          window.location.href = "#top";
        }, 1000);
      },
    });
  } else {
    var parameter_browse = "";
    if (manufacturer != "") {
      parameter_browse = "manufacturer=" + manufacturer;
    }
    if (vendor_id != "") {
      parameter_browse = "vendor_id=" + vendor_id;
    }
    if (category != "") {
      parameter_browse = "category=" + category;
    }
    if (procedure != "") {
      parameter_browse = "procedure=" + procedure;
    }
    if (listid != "") {
      parameter_browse = "listid=" + listid;
    }

    location.href = "home?" + parameter_browse;
  }
}

//rating based products load
$(document).ready(function () {
  $(document).on("click", ".rates", function () {
    rate_data = $("input[name=rating]:checked").val();
    page = "";
    refresh_products();
  });
});

//get product image ,to view in pop up ..
$(document).ready(function () {
  $(document).on("click", ".popup_image", function () {
    var pro_id = $("#field-function_purpose").val();
    $.ajax({
      type: "POST",
      url: base_url + "get-productimage",
      data: { pro_id: pro_id },
      success: function (data) {
        var data = JSON.parse(data); //object
        var product_image = "";
        var img_url = image_url;
        console.log(img_url + "uploads/products/images/");
        console.log(data.images);
        for (var i = 0; i < data.images.length; i++) {
          product_image +=
            "<li data-thumb='" +
            img_url +
            "uploads/products/images/" +
            data.images[i].photo +
            "'><img src='" +
            img_url +
            "uploads/products/images/" +
            data.images[i].photo +
            "'></li>";
          console.log(product_images);
        }

        $(".product_images").html(product_image);
      },
    });
  });
});

//add products to request Lists
$(document).ready(function () {
  $(document).on("click", ".add_request", function () {
    console.log("add to rl");
    var product_id = $(this).data("id");
    var vendor_id = $(this).data("vendor");
    var p_color = $(this).data("procolor");
    var p_price = $(this).data("price");
    var pqty = $(this).closest(".wrap").find(".aaa").val();
    var p_qty_price = pqty * p_price;
    p_qty_price = parseFloat(Math.round(p_qty_price * 100) / 100).toFixed(2);
    var qty = $(this).closest(".wrap").find(".request_quantity").val();
    $.ajax({
      type: "POST",
      url: base_url + "get-userlocations",
      success: function (data) {
        var data = JSON.parse(data); //object
        console.log(data);
        console.log(vendor_id);
        var requst_lists = "";
        if (data.locations != "") {
          if (data.user_locations.length >= 1) {
            for (var i = 0; i < data.user_locations.length; i++) {
              var myDate = new Date(data.user_locations[i].updated_at);
              var updatede_date = moment(myDate).format("ll");
              requst_lists +=
                "<tr><td class='width--20 padding--xs no--pad-t no--pad-r no--pad-l'><p class='no--margin-b no--margin-t no--margin-r no--margin-l'>" +
                data.user_locations[i].nickname +
                "</p></td><td class='width--25 padding--xs no--pad-t no--pad-r no--pad-l'><p class='no--margin-b no--margin-t no--margin-r no--margin-l'>" +
                updatede_date +
                "</p></td> ";
              if (pqty != null && pqty != 0) {
                requst_lists +=
                  "<td class='width--15 padding--xs no--pad-t no--pad-r no--pad-l'><p class='no--margin-b no--margin-t no--margin-r no--margin-l'>" +
                  pqty +
                  "</p></td><td class='width--20 padding--xs no--pad-t no--pad-r no--pad-l'><p class='no--margin-b no--margin-t no--margin-r no--margin-l'> $" +
                  Math.floor(p_price * pqty * 100) / 100 +
                  "</p> </td> <td class='width--20 padding--xs no--pad-t no--pad-r no--pad-l'>";
              } else {
                requst_lists +=
                  "<td>-</td><td>-</td><td class='width--20 padding--xs no--pad-t no--pad-r no--pad-l'>";
              }
              requst_lists +=
                "<button class='btn btn--s btn--tertiary btn--confirm width--100 select_button save_request' data-pro_id=" +
                product_id +
                " data-vendors=" +
                vendor_id +
                "  data-location=" +
                data.user_locations[i].id +
                " data-qty=" +
                qty +
                " data-replace='&#10003; Added'>+ Request</button> </td></tr>";
            }
          } else {
            var myDate = new Date(data.user_locations.updated_at);
            var updatede_date = moment(myDate).format("ll");
            requst_lists +=
              "<tr><td class='width--20 padding--xs no--pad-t no--pad-r no--pad-l'><p class='no--margin-b no--margin-t no--margin-r no--margin-l'>" +
              data.user_locations.nickname +
              "</p></td><td class='width--25 padding--xs no--pad-t no--pad-r no--pad-l'><p class='no--margin-b no--margin-t no--margin-r no--margin-l'>" +
              updatede_date +
              "</p></td> ";
            if (
              data.user_locations.item_count != null &&
              data.user_locations.item_count != 0
            ) {
              requst_lists +=
                "<td class='width--15 padding--xs no--pad-t no--pad-r no--pad-l'><p class='no--margin-b no--margin-t no--margin-r no--margin-l'>" +
                data.user_locations.item_count +
                "</p></td><td class='width--20 padding--xs no--pad-t no--pad-r no--pad-l'><p class='no--margin-b no--margin-t no--margin-r no--margin-l'> $" +
                parseFloat(
                  Math.round(data.user_locations.item_total * 100) / 100
                ).toFixed(2) +
                "</p> </td> <td class='width--20 padding--xs no--pad-t no--pad-r no--pad-l'>";
            } else {
              requst_lists +=
                "<td>-</td><td>-</td><td class='width--20 padding--xs no--pad-t no--pad-r no--pad-l'>";
            }
            requst_lists +=
              "<button class='btn btn--s btn--tertiary btn--confirm width--100 select_button save_request' data-pro_id=" +
              product_id +
              " data-vendors=" +
              vendor_id +
              "  data-location=" +
              data.user_locations.id +
              " data-qty=" +
              qty +
              " data-replace='&#10003; Added'>+ Request</button> </td></tr>";
          }
        } else {
          requst_lists +=
            "<tr><center><td colspan='4'>No Locations Found</td></center></tr>";
          $(".no_values").hide();
          $(".no-locations").hide();
          $(".notempty").hide();
          $(".empty").show();
        }
        $(".request_data").html(requst_lists);
      },
    });
  });
  //add single products to request list from products details view page
  $(document).on("click", ".add_single_request", function () {
    var product_id = $(this).data("pid");
    var vendor_id = $("input[name=vendor]:checked").val();
    var qty = $(".sqty").val();
    $.ajax({
      type: "POST",
      url: base_url + "get-userlocations",
      success: function (data) {
        var data = JSON.parse(data); //object
        var requst_lists = "";
        if (data.locations != "") {
          if (data.user_locations.length >= 1) {
            for (var i = 0; i < data.user_locations.length; i++) {
              var myDate = new Date(data.user_locations[i].updated_at);
              var updatede_date = moment(myDate).format("ll");
              requst_lists +=
                "<tr><td class='width--20 padding--xs no--pad-t no--pad-r no--pad-l'><p class='no--margin-b no--margin-t no--margin-r no--margin-l'>" +
                data.user_locations[i].nickname +
                "</p></td><td class='width--25 padding--xs no--pad-t no--pad-r no--pad-l'><p class='no--margin-b no--margin-t no--margin-r no--margin-l'>" +
                updatede_date +
                "</p></td> ";
              if (
                data.user_locations[i].item_count != null &&
                data.user_locations.item_count != 0
              ) {
                requst_lists +=
                  "<td class='width--15 padding--xs no--pad-t no--pad-r no--pad-l'><p class='no--margin-b no--margin-t no--margin-r no--margin-l'>" +
                  data.user_locations[i].item_count +
                  "</p></td><td class='width--20 padding--xs no--pad-t no--pad-r no--pad-l'><p class='no--margin-b no--margin-t no--margin-r no--margin-l'> $" +
                  parseFloat(
                    Math.round(data.user_locations[i].item_total * 100) / 100
                  ).toFixed(2) +
                  "</p> </td> <td class='width--20 padding--xs no--pad-t no--pad-r no--pad-l'>";
              } else {
                requst_lists +=
                  "<td>-</td><td>-</td><td class='width--20 padding--xs no--pad-t no--pad-r no--pad-l'>";
              }
              requst_lists +=
                "<button class='btn btn--s btn--tertiary btn--confirm width--100 select_button save_request' data-pro_id=" +
                product_id +
                " data-vendors=" +
                vendor_id +
                "  data-location=" +
                data.user_locations[i].id +
                " data-qty=" +
                qty +
                " data-replace='&#10003; Added'>+ Request</button> </td></tr>";
            }
          } else {
            var myDate = new Date(data.user_locations.updated_at);
            var updatede_date = moment(myDate).format("ll");
            requst_lists +=
              "<tr><td class='width--20 padding--xs no--pad-t no--pad-r no--pad-l'><p class='no--margin-b no--margin-t no--margin-r no--margin-l'>" +
              data.user_locations.nickname +
              "</p></td><td class='width--25 padding--xs no--pad-t no--pad-r no--pad-l'><p class='no--margin-b no--margin-t no--margin-r no--margin-l'>" +
              updatede_date +
              "</p></td> ";
            if (
              data.user_locations.item_count != null &&
              data.user_locations.item_count != 0
            ) {
              requst_lists +=
                "<td class='width--15 padding--xs no--pad-t no--pad-r no--pad-l'><p class='no--margin-b no--margin-t no--margin-r no--margin-l'>" +
                data.user_locations.item_count +
                "</p></td><td class='width--20 padding--xs no--pad-t no--pad-r no--pad-l'><p class='no--margin-b no--margin-t no--margin-r no--margin-l'> $" +
                parseFloat(
                  Math.round(data.user_locations.item_total * 100) / 100
                ).toFixed(2) +
                "</p> </td> <td class='width--20 padding--xs no--pad-t no--pad-r no--pad-l'>";
            } else {
              requst_lists +=
                "<td>-</td><td>-</td><td class='width--20 padding--xs no--pad-t no--pad-r no--pad-l'>";
            }
            requst_lists +=
              "<button class='btn btn--s btn--tertiary btn--confirm width--100 select_button save_request' data-pro_id=" +
              product_id +
              " data-vendors=" +
              vendor_id +
              "  data-location=" +
              data.user_locations.id +
              " data-qty=" +
              qty +
              " data-replace='&#10003; Added'>+ Request</button> </td></tr>";
          }
        } else {
          requst_lists +=
            "<tr><center><td colspan='4'>No Locations Found</td></center></tr>";
          $(".no_values").hide();
          $(".no-locations").hide();
          $(".notempty").hide();
          $(".empty").show();
        }
        $(".request_data").html(requst_lists);
      },
    });
  });

  $(document).on("click", ".list-torequest", function () {
    //add shopping list products to request lists
    var product_id = $(this).data("id");
    var vendor_id = $(this).data("vendor");
    var qty = $(this)
      .parent()
      .parent()
      .parent()
      .find(".request_quantity")
      .val();
    $.ajax({
      type: "POST",
      url: base_url + "get-userlocations",
      success: function (data) {
        var data = JSON.parse(data); //object
        var requst_lists = "";
        // console.log(data);
        // console.log(vendor_id);
        if (data.locations != "") {
          if (data.user_locations.length >= 1) {
            for (var i = 0; i < data.user_locations.length; i++) {
              var myDate = new Date(data.user_locations[i].updated_at);
              var updatede_date = moment(myDate).format("ll");
              requst_lists +=
                "<tr><td class='width--20 padding--xs no--pad-t no--pad-r no--pad-l'><p class='no--margin-b no--margin-t no--margin-r no--margin-l'>" +
                data.user_locations[i].nickname +
                "</p></td><td class='width--25 padding--xs no--pad-t no--pad-r no--pad-l'><p class='no--margin-b no--margin-t no--margin-r no--margin-l'>" +
                updatede_date +
                "</p></td> ";
              if (
                data.user_locations[i].item_count != null &&
                data.user_locations.item_count != 0
              ) {
                requst_lists +=
                  "<td class='width--15 padding--xs no--pad-t no--pad-r no--pad-l'><p class='no--margin-b no--margin-t no--margin-r no--margin-l'>" +
                  data.user_locations[i].item_count +
                  "</p></td><td class='width--20 padding--xs no--pad-t no--pad-r no--pad-l'><p class='no--margin-b no--margin-t no--margin-r no--margin-l'> $" +
                  parseFloat(
                    Math.round(data.user_locations[i].item_total * 100) / 100
                  ).toFixed(2) +
                  "</p> </td> <td class='width--20 padding--xs no--pad-t no--pad-r no--pad-l'>";
              } else {
                requst_lists +=
                  "<td>-</td><td>-</td><td class='width--20 padding--xs no--pad-t no--pad-r no--pad-l'>";
              }
              requst_lists +=
                "<button class='btn btn--s btn--tertiary btn--confirm width--100 select_button save_request' data-pro_id=" +
                product_id +
                " data-vendors=" +
                vendor_id +
                "  data-location=" +
                data.user_locations[i].id +
                " data-qty=" +
                qty +
                " data-replace='&#10003; Added'>+ Request</button> </td></tr>";
            }
          } else {
            var myDate = new Date(data.user_locations.updated_at);
            var updatede_date = moment(myDate).format("ll");
            requst_lists +=
              "<tr><td class='width--20 padding--xs no--pad-t no--pad-r no--pad-l'><p class='no--margin-b no--margin-t no--margin-r no--margin-l'>" +
              data.user_locations.nickname +
              "</p></td><td class='width--25 padding--xs no--pad-t no--pad-r no--pad-l'><p class='no--margin-b no--margin-t no--margin-r no--margin-l'>" +
              updatede_date +
              "</p></td> ";
            if (
              data.user_locations.item_count != null &&
              data.user_locations.item_count != 0
            ) {
              requst_lists +=
                "<td class='width--15 padding--xs no--pad-t no--pad-r no--pad-l'><p class='no--margin-b no--margin-t no--margin-r no--margin-l'>" +
                data.user_locations.item_count +
                "</p></td><td class='width--20 padding--xs no--pad-t no--pad-r no--pad-l'><p class='no--margin-b no--margin-t no--margin-r no--margin-l'> $" +
                parseFloat(
                  Math.round(data.user_locations.item_total * 100) / 100
                ).toFixed(2) +
                "</p> </td> <td class='width--20 padding--xs no--pad-t no--pad-r no--pad-l'>";
            } else {
              requst_lists +=
                "<td>-</td><td>-</td><td class='width--20 padding--xs no--pad-t no--pad-r no--pad-l'>";
            }
            requst_lists +=
              "<button class='btn btn--s btn--tertiary btn--confirm width--100 select_button save_request' data-pro_id=" +
              product_id +
              " data-vendors=" +
              vendor_id +
              "  data-location=" +
              data.user_locations.id +
              " data-qty=" +
              qty +
              " data-replace='&#10003; Added'>+ Request</button> </td></tr>";
          }
        } else {
          requst_lists +=
            "<tr><center><td colspan='4'>No Locations Found</td></center></tr>";
          $(".no_values").hide();
          $(".no-locations").hide();
          $(".notempty").hide();
          $(".empty").show();
        }
        $(".request_data").html(requst_lists);
      },
    });
  });

  $(document).on("click", ".select_button", function () {
    $(this).toggleClass("is--added");
    $(this).toggleClass("btn--tertiary");
    if ($(this).hasClass("is--added")) {
      $(this).html("&#10003; Added");
    } else {
      if ($(this).hasClass("save_request")) {
        $(this).html("+ Request");
      } else {
        $(this).html("+ Add to cart");
      }
    }
  });

  //request list quantity update
  $(document).on("blur", ".update_rqty", function () {
    var qty = $(this).val();
    var id = $(this).closest("td").find(".request_id").val();
    if (qty != "") {
      $.ajax({
        type: "POST",
        url: base_url + "update-request-qty",
        data: { qty: qty, id: id },
        success: function (data) {},
      });
    }
  });
});
$(document).ready(function () {
  $(document).on("click", ".save_request", function () {
    var product_id = $(this).data("pro_id");
    var vendor_id = $(this).data("vendors");
    var location_id = $(this).data("location");
    var qty = $(this).data("qty");
    if ($("#list--location").val() == "") {
      $("#list--location").val(location_id);
    } else {
      $("#list--location").val($("#list--location").val() + "," + location_id);
    }
    $(".product").val(product_id);
    $(".vendor").val(vendor_id);
    $(".quantity").val(qty);
    if ($(this).data("added") == true) {
      qty = qty * -1;
      $(".success").html("");
      $(this).data("added", false);
    } else {
      $(this).data("added", true);
    }

    $.ajax({
      type: "POST",
      url: base_url + "add-request-products",
      data: {
        product_id: product_id,
        vendor_id: vendor_id,
        location_id: location_id,
        qty: qty,
      },
      success: function (data) {
        // $(saveBtn).removeAttr("disabled");
        $("#list--location").val("");
        $(".view_lists").addClass("has--badge");
        var smessage = "Products added successfully";
        $(".success").html(
          '<div class="banner is--pos"><span class="banner__text">' +
            smessage +
            '</span><a class="link link--icon dismiss--banner"><svg class="icon icon--x"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-x"></use></svg></a></div><br />'
        );
        // $(saveBtn).text(initialText).removeClass('is--pos');
      },
    });
  });
});

$(document).ready(function () {
  //add products to request lists..
  $(document).on("click", ".add_requests", function () {
    return;
    var saveBtn = $(this),
      initialText = $(this).text();
    $(saveBtn).attr("disabled", "disabled");
    $(saveBtn).html('<div class="loader loader--light"></div>');
    var product_id = $(".product").val();
    var location_id = $("#list--location").val();
    var vendor_id = $(".vendor").val();
    var qty = $(".quantity").val();
    $.ajax({
      type: "POST",
      url: base_url + "add-request-products",
      data: {
        product_id: product_id,
        vendor_id: vendor_id,
        location_id: location_id,
        qty: qty,
      },
      success: function (data) {
        $(saveBtn).removeAttr("disabled");
        $("#list--location").val("");
        $(".view_lists").addClass("has--badge");
        var smessage = "Products added successfully";
        $(".success").html(
          '<div class="banner is--pos"><span class="banner__text">' +
            smessage +
            '</span><a class="link link--icon dismiss--banner"><svg class="icon icon--x"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-x"></use></svg></a></div><br />'
        );
        $(saveBtn).text(initialText).removeClass("is--pos");
      },
    });
  });
});
//request list view
$(document).ready(function () {
  $(document).on("click", ".select_location", function () {
    var id = $("input[name=locationTabs]:checked").val();
    window.location.href = base_url + "request-products?id=" + id;
  });
  $(document).on("click", ".remove-requestitem", function () {
    //delete single product from request lists
    var request_id = $(".r_id").val();
    $.ajax({
      type: "POST",
      url: base_url + "remove-request-item",
      data: { request_id: request_id },
      success: function (data) {
        location.reload();
      },
    });
  });
  $(document).on("click", ".remove-multiple-requests", function () {
    //delete seleted products from request lists
    var user_id = $("#user_id").val();
    $.ajax({
      type: "POST",
      url: base_url + "remove-multiple-requests",
      data: { user_id: user_id },
      success: function (data) {
        location.reload();
      },
    });
  });
});
//products add to cart
$(document).ready(function () {
  $(document).on("click", ".add_cart", function () {
    var license_required = $(this).data("license_required");
    var product_id = $(this).data("pid");
    var pro_name = $(this).data("name");
    var vendor_id = $(this).data("vendor_id");
    var p_color = $(this).data("procolor");
    var p_price = $(this).data("price");
    var pqty = $(this).closest(".wrap").find(".aaa").val();
    var p_qty_price = pqty * p_price;
    p_qty_price = parseFloat(Math.round(p_qty_price * 100) / 100).toFixed(2);

    $.ajax({
      type: "POST",
      url: base_url + "get-cartdetails",
      success: function (data) {
        var data = JSON.parse(data); //object
        var cart_lists = "";
        if (data.locations != "") {
          if (data.user_locations.length >= 1) {
            for (var i = 0; i < data.user_locations.length; i++) {
              var myDate = new Date(data.user_locations[i].updated_at);
              var updatede_date = moment(myDate).format("ll");
              cart_lists +=
                "<tr><td><span class='fontWeight--2'>" +
                data.user_locations[i].nickname +
                "</span></td>";
              if (pqty > 0) {
                cart_lists +=
                  "<td>" + pqty + "</td><td> $" + p_qty_price + "</td>";
              } else {
                cart_lists += "<td>-</td><td>-</td>";
              }

              console.log(data.user_locations[i].licences || {});
              cart_lists +=
                "<td><button class='btn btn--s btn--tertiary btn--confirm btn--block select_button addcart ' data-p_id=" +
                product_id +
                " data-qty= " +
                pqty +
                " data-pname=" +
                pro_name +
                " data-aprice=" +
                p_price +
                " data-location_id=" +
                data.user_locations[i].id +
                "  data-vendors= " +
                vendor_id +
                "  data-pcolor=" +
                p_color +
                "   'data-replace='&#10003; Added'>+ Add to cart</button> </td> </tr>";
            }
          } else {
            cart_lists +=
              "<tr><td><span class='fontWeight--2'>" +
              data.user_locations.nickname +
              "</span></td>";
            if (pqty != null && pqty != 0) {
              cart_lists +=
                "<td>" + pqty + "</td><td> $" + p_qty_price + "</td>";
            } else {
              cart_lists += "<td>-</td><td>-</td>";
            }

            cart_lists +=
              "<td><button class='btn btn--s btn--tertiary btn--confirm btn--block select_button addcart' data-p_id=" +
              product_id +
              " data-qty= " +
              pqty +
              " data-pname=" +
              pro_name +
              " data-aprice=" +
              p_price +
              " data-location_id=" +
              data.user_locations.id +
              "  data-vendors= " +
              vendor_id +
              "  data-pcolor=" +
              p_color +
              "   'data-replace='&#10003; Added'>+ Add to cart</button> </td> </tr>";
          }
        } else {
          cart_lists +=
            "<tr><center><td colspan='4'>No Locations Found</td></center></tr>";
          $(".empty-data").hide();
          $(".no-submit").hide();
          $(".notempty").hide();
          $(".empty").show();
        }
        $(".cart_details").html(cart_lists);
      },
    });
  });
});

//add products to user selected shopping locations
$(document).on("click", ".add_single_cart", function () {
  var license_required = $(this).data("license_required");
  var product_id = $(this).data("pid");
  var pro_name = $(this).data("name");
  var p_color = $(this).data("procolor");
  var pqty = $(".sqty").val();
  var vendor_id = $('input[name="vendor"]:checked').val();
  var date = new Date();
  $.ajax({
    type: "POST",
    url: base_url + "get-vendor-price",
    data: { vendor_id: vendor_id, product_id: product_id },
    success: function (data) {
      //console.log(data);
      var data = JSON.parse(data);
      var p_price = 0;

      if (data.product_price.price > 0) {
        p_price = data.product_price.price;
      } else {
        p_price = data.product_price.retail_price;
      }

      var p_qty_price = pqty * p_price;
      p_qty_price = parseFloat(Math.round(p_qty_price * 100) / 100).toFixed(2);

      var cart_lists = "";
      if (data.locations != "") {
        if (data.user_locations.length >= 1) {
          for (var i = 0; i < data.user_locations.length; i++) {
            var myDate = new Date(data.user_locations[i].updated_at);

            var updatede_date = moment(myDate).format("ll");
            cart_lists +=
              "<tr><td><span class='fontWeight--2'>" +
              data.user_locations[i].nickname +
              "</span></td>";

            if (pqty > 0) {
              cart_lists +=
                "<td>" + pqty + "</td><td> $ " + p_qty_price + "</td>";
            } else {
              cart_lists += "<td>-</td><td>-</td>";
            }

            if (
              license_required == "Yes" &&
              data.user_locations[i].license != undefined
            ) {
              var license_expiration = new Date(
                data.user_locations[i].license.expire_date
              );
              if (license_expiration >= date) {
                cart_lists +=
                  "<td><button class='btn btn--s btn--tertiary btn--confirm btn--block select_button addcart' data-p_id=" +
                  product_id +
                  " data-qty= " +
                  pqty +
                  " data-pname=" +
                  pro_name +
                  " data-aprice=" +
                  p_price +
                  " data-location_id=" +
                  data.user_locations[i].id +
                  "  data-vendors= " +
                  vendor_id +
                  "  data-pcolor=" +
                  p_color +
                  " data-replace='&#10003; Added'>+ Add to cart</button> </td> ";
              }
            } else {
              cart_lists += "<td><span>License Required</span></td> ";
            }

            cart_lists += "</tr>";
          }
        } else {
          cart_lists +=
            "<tr><td><span class='fontWeight--2'>" +
            data.user_locations.nickname +
            "</span></td>";

          if (pqty > 0) {
            cart_lists +=
              "<td>" + pqty + "</td><td> $ " + p_qty_price + "</td>";
          } else {
            cart_lists += "<td>-</td><td>-</td>";
          }

          if (
            license_required == "Yes" &&
            data.user_locations.license != undefined
          ) {
            var license_expiration = new Date(
              data.user_locations.license.expire_date
            );
            if (license_expiration >= date) {
              cart_lists +=
                "<td><button class='btn btn--s btn--tertiary btn--confirm btn--block select_button addcart' data-p_id=" +
                product_id +
                " data-qty= " +
                pqty +
                " data-pname=" +
                pro_name +
                " data-aprice=" +
                p_price +
                " data-location_id=" +
                data.user_locations.id +
                "  data-vendors= " +
                vendor_id +
                "  data-pcolor=" +
                p_color +
                " data-replace='&#10003; Added'>+ Add to cart</button> </td> ";
            }
          } else {
            cart_lists += "<td><span>License Required</span></td> ";
          }

          cart_lists += "</tr>";
        }
      } else {
        cart_lists +=
          "<tr><center><td colspan='4'>No Locations Found</td></center></tr>";
        $(".empty-data").hide();
        $(".no-submit").hide();
        $(".notempty").hide();
        $(".empty").show();
      }

      $(".cart_details").html(cart_lists);
    },
  });
});

//shopping lists to cart
$(document).on("click", ".list-to-cart", function () {
  var product_id = $(this).data("pid");
  var pro_name = $(this).data("name");
  var p_color = $(this).data("procolor");
  var pqty = $(this).parent().parent().parent().find(".request_quantity").val();
  var vendor_id = $(this).data("vendor_id");
  var p_price = $(this).data("price");
  $.ajax({
    type: "POST",
    url: base_url + "get-cartdetails",
    success: function (data) {
      var data = JSON.parse(data); //object
      var cart_lists = "";
      if (data.locations != "") {
        if (data.user_locations.length >= 1) {
          for (var i = 0; i < data.user_locations.length; i++) {
            var myDate = new Date(data.user_locations[i].updated_at);
            var updatede_date = moment(myDate).format("ll");
            cart_lists +=
              "<tr><td><span class='fontWeight--2'>" +
              data.user_locations[i].nickname +
              "</span></td>";
            if (data.user_locations[i].item_count != null) {
              cart_lists +=
                "<td>" +
                data.user_locations[i].item_count +
                "</td><td> $" +
                parseFloat(
                  Math.round(data.user_locations[i].item_total * 100) / 100
                ).toFixed(2) +
                "</td>";
            } else {
              cart_lists += "<td>-</td><td>-</td>";
            }
            cart_lists +=
              "<td><button class='btn btn--s btn--tertiary btn--confirm btn--block select_button addcart' data-p_id=" +
              product_id +
              " data-qty= " +
              pqty +
              " data-pname=" +
              pro_name +
              " data-aprice=" +
              p_price +
              " data-location_id=" +
              data.user_locations[i].id +
              "  data-vendors= " +
              vendor_id +
              "  data-pcolor=" +
              p_color +
              "   'data-replace='&#10003; Added'>+ Add to cart</button> </td> </tr>";
          }
        } else {
          cart_lists +=
            "<tr><td><span class='fontWeight--2'>" +
            data.user_locations.nickname +
            "</span></td>";
          if (
            data.user_locations.item_count != null &&
            data.user_locations.item_count > 0
          ) {
            cart_lists +=
              "<td>" +
              data.user_locations.item_count +
              "</td><td> $" +
              parseFloat(
                Math.round(data.user_locations.item_total * 100) / 100
              ).toFixed(2) +
              "</td>";
          } else {
            cart_lists += "<td>-</td><td>-</td>";
          }
          cart_lists +=
            "<td><button class='btn btn--s btn--tertiary btn--confirm btn--block select_button addcart' data-p_id=" +
            product_id +
            " data-qty= " +
            pqty +
            " data-pname=" +
            pro_name +
            " data-aprice=" +
            p_price +
            " data-location_id=" +
            data.user_locations.id +
            "  data-vendors= " +
            vendor_id +
            "  data-pcolor=" +
            p_color +
            "   'data-replace='&#10003; Added'>+ Add to cart</button> </td> </tr>";
        }
      } else {
        cart_lists +=
          "<tr><center><td colspan='4'>No Locations Found</td></center></tr>";
        $(".empty-data").hide();
        $(".no-submit").hide();
        $(".notempty").hide();
        $(".empty").show();
      }
      $(".cart_details").html(cart_lists);
    },
  });
});

$(document).ready(function () {
  $(document).on("click", ".addcart", function () {
    var product_id = $(this).data("p_id");
    var pro_name = $(this).data("pname");
    var l_id = $(this).data("location_id");
    var v_id = $(this).data("vendors");
    var p_color = $(this).data("pcolor");
    var qty = $(this).data("qty");
    var a_price = $(this).data("aprice");
    if ($(this).data("added") == true) {
      qty = qty * -1;
      $(".success").html("");
      $(this).data("added", false);
    } else {
      $(this).data("added", true);
    }

    $.ajax({
      type: "POST",
      url: base_url + "add-cart-products",
      data: {
        price: a_price,
        vendor_id: v_id,
        product_id: product_id,
        location_id: l_id,
        cartqty: qty,
        p_name: pro_name,
      },
      success: function (data) {
        // console.log(data);
        var data = JSON.parse(data);
        console.log(data);
        if (data.cart_message != null) {
          var message =
            "Please enter license information for the state you are purchasing for before purchasing restricted items(s).";
          if (data.cart_message.length >= 1) {
            $(".nolicence").html(
              '<div class="banner is--neg"><span class="banner__text">' +
                message +
                '</span><a class="link link--icon dismiss--banner"><svg class="icon icon--x"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-x"></use></svg></a></div><br />'
            );
          }
        } else if (data.expire_message != null) {
          var expiremessage = "Unable to purchase item: License has expired.";
          if (data.expire_message.length >= 1) {
            $(".nolicence").html(
              '<div class="banner is--neg"><span class="banner__text">' +
                expiremessage +
                '</span><a class="link link--icon dismiss--banner"><svg class="icon icon--x"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-x"></use></svg></a></div><br />'
            );
          }
        } else {
          var smessage = "Products added to cart successfully";
          $(".success").html(
            '<div class="banner is--pos"><span class="banner__text">' +
              smessage +
              '</span><a class="link link--icon dismiss--banner"><svg class="icon icon--x"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-x"></use></svg></a></div><br />'
          );
        }
        $("#list--locations").val("");
        $(".user-cart").html("");
        $(".view-cart").addClass("has--badge");
      },
      error: function (jXHR, textStatus, errorThrown) {
        $(saveBtn).text(initialText).removeClass("is--pos");
        $(saveBtn).removeAttr("disabled");
        alert(errorThrown);
      },
    });
  });
});

$(document).ready(function () {
  $(document).on("click", ".addTocart", function () {
    var saveBtn = $(this),
      initialText = $(this).text();
    $(saveBtn).attr("disabled", "disabled");
    $(saveBtn).html('<div class="loader loader--light"></div>');
    var product_id = $(".product").val();
    var location_id = $("#list--locations").val();
    var vendor_id = $(".vendor").val();
    var price = $(".pro_price").val();
    var cartqty = $(".quantity").val();
    var p_color = $(".pro_color").val();
    var p_name = $(".p_name").val();
    $.ajax({
      type: "POST",
      url: base_url + "add-cart-products",
      data: {
        price: price,
        vendor_id: vendor_id,
        product_id: product_id,
        location_id: location_id,
        cartqty: cartqty,
        p_name: p_name,
      },
      success: function (data) {
        $(saveBtn).removeAttr("disabled");
        $(saveBtn).html('<div class="checkmark"></div>').addClass("is--pos");
        var data = JSON.parse(data);
        if (data.cart_message != null) {
          var message =
            "Please enter license information for the state you are purchasing for before purchasing restricted items(s).";
          if (data.cart_message.length >= 1) {
            $(".nolicence").html(
              '<div class="banner is--neg"><span class="banner__text">' +
                message +
                '</span><a class="link link--icon dismiss--banner"><svg class="icon icon--x"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-x"></use></svg></a></div><br />'
            );
          }
        } else if (data.expire_message != null) {
          var expiremessage = "Unable to purchase item: License has expired.";
          if (data.expire_message.length >= 1) {
            $(".nolicence").html(
              '<div class="banner is--neg"><span class="banner__text">' +
                expiremessage +
                '</span><a class="link link--icon dismiss--banner"><svg class="icon icon--x"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-x"></use></svg></a></div><br />'
            );
          }
        } else {
          var smessage = "Products added to cart successfully";
          $(".success").html(
            '<div class="banner is--pos"><span class="banner__text">' +
              smessage +
              '</span><a class="link link--icon dismiss--banner"><svg class="icon icon--x"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-x"></use></svg></a></div><br />'
          );
        }
        $("#list--locations").val("");
        $(".user-cart").html("");
        $(".view-cart").addClass("has--badge");
        $(saveBtn).text(initialText).removeClass("is--pos");
      },
      error: function (jXHR, textStatus, errorThrown) {
        $(saveBtn).text(initialText).removeClass("is--pos");
        $(saveBtn).removeAttr("disabled");
        alert(errorThrown);
      },
    });
  });
});
//add populated lists with locations
$(document).ready(function () {
  $(document).on("click", ".get_user_locations", function () {
    $(".locations").html('<option value="">----Select Location-----</option>');
    $.ajax({
      type: "POST",
      url: base_url + "get-user-locations",
      success: function (data) {
        var data = JSON.parse(data);
        for (var i = 0; i < data.user_locations.length; i++) {
          $(".locations").append(
            '<option value="' +
              data.user_locations[i].id +
              '">' +
              data.user_locations[i].nickname +
              "</option>"
          );
        }
      },
    });
  });
  //get user assigned locations
  $(document).on("click", ".get_locations", function () {
    $.ajax({
      type: "POST",
      url: base_url + "get-user-locations",
      success: function (data) {
        var data = JSON.parse(data);
        var user_locations = "";
        for (var i = 0; i < data.user_locations.length; i++) {
          user_locations +=
            "<li class='item card padding--xs cf'><div class='wrapper'>";
          user_locations +=
            "<div class='rapper__inner'>" +
            data.user_locations[i].nickname +
            "</div>";
          user_locations += "<div class='wrapper__inner align--right'>";
          user_locations +=
            "<button class='btn btn--s btn--tertiary btn--toggle width--fixed-75 selectlist select-list' data-location_id=" +
            data.user_locations[i].id +
            " type='button'>Select</button>";
          user_locations += "</div></div> </li>";
        }
        $(".locations").html(user_locations);
      },
    });
  });
  // select shpping list location
  $(document).on("click", ".selectlist", function () {
    $(this).addClass("is--pos");
    $(this).addClass("unselect-list");
    $(this).removeClass("btn--tertiary");
    $(this).html("✓");
    $(this).removeClass("selectlist");
    var location_id = $(this).data("location_id");
    if ($("#shoppinglist--locations").val() == "") {
      $("#shoppinglist--locations").val(location_id);
    } else {
      $("#shoppinglist--locations").val(
        $("#shoppinglist--locations").val() + "," + location_id
      );
    }
  });
  //unselect shopping list location
  $(document).on("click", ".unselect-list", function () {
    $(this).removeClass("is--pos");
    $(this).addClass("btn--tertiary");
    $(this).html("Select");
    $(this).removeClass("unselect-list");
    $(this).addClass("selectlist");
    var value = $(this).data("location_id");
    var list = $("#shoppinglist--locations").val();
    var aaa = removeValue(list, value); // 2,3
    $("#shoppinglist--locations").val(aaa);
  });
  //remove unselect list location
  function removeValue(list, value, separator) {
    separator = separator || ",";
    var values = list.split(separator);
    for (var i = 0; i < values.length; i++) {
      if (values[i] == value) {
        values.splice(i, 1);
        return values.join(separator);
      }
    }
    return list;
  }
});
// Populate Qty Select Dropdowns with Values
$(function () {
  var $select = $(".qty--1-100");
  for (i = 2; i <= 10; i++) {
    $select.append($("<option></option>").val(i).html(i));
  }
});

// Instantiate ratings objects
$(".ratings__wrapper .stars").each(function () {
  var $rating = $(this).data("rating");
  $(this).width($rating + "%");
});

// Toggle '.is--expanded' on target
$(".link--expand").click(function () {
  var target = $(this).closest($(this).data("target"));
  $(target).toggleClass("is--expanded");
  console.log(target);
});

// Accordions
$(".accordion__section .link--expand").click(function () {
  var family = $(".accordion__section"),
    parent = $(this).closest(".accordion__section"),
    c = "is--expanded";
  $(parent).toggleClass(c);
});

// Input Masking

jQuery(function ($) {
  if ($(".input--currency").length) {
    $(".input--currency").maskMoney();
  }
  if ($(".input--currency-zero").length) {
    $(".input--currency-zero").maskMoney({ allowZero: true });
  }
  $(".input--phone").mask("(999) 999-9999", {
    placeholder: " ",
  });
  $(".input--tax-id").mask("99-9999999", {
    placeholder: " ",
  });
  $(".input--tax-id").mask("99-9999999", {
    placeholder: " ",
  });
  $(".input--cc-exp").mask("99/99", {
    placeholder: " ",
  });
  $(".input--license--date").mask("99/99/9999", {
    placeholder: " ",
  });
  $(".input--date").mask("99/99/9999", {
    placeholder: " ",
  });
  $(".input--zip").mask("99999", {
    placeholder: " ",
  });
});

// Credit Card Field Detection/Masking
$(".input--cc")
  .detectCard()
  .on("cardChange", function (e, card) {
    var input = $(this),
      cardType = card.type;

    $(input)
      .closest(".input__group")
      .children(".icon--cc")
      .replaceWith('<i class="icon icon--cc icon--' + cardType + '"></i>');

    if (cardType == "visa") {
      $(input)
        .mask("9999 9999 9999 9999", { placeholder: " ", autoclear: false })
        .get(0)
        .setSelectionRange(1, 1);
    } else if (cardType == "mastercard") {
      $(input)
        .mask("9999 9999 9999 9999", { placeholder: " ", autoclear: false })
        .get(0)
        .setSelectionRange(2, 2);
    } else if (cardType == "discover" && $(input).val() == "6011") {
      $(input)
        .mask("9999 9999 9999 9999", { placeholder: " ", autoclear: false })
        .get(0)
        .setSelectionRange(5, 5);
    } else if (cardType == "discover" && $(input).val() == "65") {
      $(input)
        .mask("9999 9999 9999 9999", { placeholder: " ", autoclear: false })
        .get(0)
        .setSelectionRange(2, 2);
    } else if (cardType == "american-express") {
      $(input)
        .mask("9999 999999 99999", { placeholder: " ", autoclear: false })
        .get(0)
        .setSelectionRange(2, 2);
    }
  });

// Google Maps for Locations Page
$(document).ready(function () {
  $(".location__map").each(function () {
    var address = $(this).data("address").replace(/\s+/g, "+"),
      url =
        "https://maps.googleapis.com/maps/api/staticmap?center=" +
        address +
        "&zoom=13&&markers=color:red|" +
        address +
        "&size=260x300&key=AIzaSyDAf60ttExclEetfF9qjYA6CqVAVW3pQp0";
    $(this).html('<img src="' + url + '" alt="">');
    console.log(url);
  });
});

//Table Checkboxes
function toggleControls(controls) {
  console.log("toggling");
  var conTarget = $(controls).find(".is--contextual"),
    hidden = $(controls).find(".contextual--hide"),
    tarContainer = $('[data-controls="' + controls + '"]'),
    checked = $(tarContainer).find('input[name="checkboxRow"]:checked');

  if ($(checked).length) {
    $(conTarget).addClass("is--on");
    $(conTarget).removeClass("is--off");
    $(hidden).css({ display: "none" });
  } else {
    $(conTarget).toggleClass("is--on is--off");
    $(hidden).css({ display: "inline-block" });
  }
}

// Listen for the parent checkbox selector
$(".control__checkbox .is--selector").change(function () {
  var controls = $(this).parents("[data-controls]").data("controls"),
    targets = $(this)
      .parents("[data-controls]")
      .find('input[name="checkboxRow"]');
  if ($(this).is(":checked")) {
    $(targets).prop("checked", true);
  } else {
    $(targets).prop("checked", false);
  }
  toggleControls(controls);
});
// Uncheck parent selector if child checkbox is unchecked
$('input[name="checkboxRow"]').change(function () {
  console.log("toggling");
  var controls = $(this).parents("[data-controls]").data("controls"),
    target = $(this)
      .parents("[data-controls]")
      .find(".control__checkbox .is--selector");
  if ($(target).prop("checked", true)) {
    $(target).prop({
      checked: false,
    });
  }
  toggleControls(controls);
});
// Datepicker
$(".input__group--date-range, .input--date").datepicker();
$(".input--license--date").datepicker({
  startDate: new Date(),
});

// Timepicker
$(function () {
  if ($(".input--time").length) {
    $(".input--time").timepicker();
  }
});

//Toggling Panels with Buttons
$(".panel--toggle").click(function () {
  var target = $(this).data("target");
  $(".panel__tab").removeClass("is--visible").addClass("is--hidden");
  $(target).toggleClass("is--visible is--hidden");
});

//Adding animate.css classes
$.fn.extend({
  animateCss: function (animationName) {
    var animationEnd =
      "webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend";
    this.addClass("animated " + animationName).one(animationEnd, function () {
      $(this).removeClass("animated " + animationName);
    });
  },
});

//Check if input field is empty or not
$(".input").blur(function () {
  var t = $(this).val();
  if (t == "") {
    $(this).removeClass("not--empty");
  } else {
    $(this).addClass("not--empty");
  }
});

$(".input__group.has--dropdown .input").keyup(function () {
  var t = $(this).val();
  if (t == "") {
    $(this).removeClass("not--empty");
  } else {
    $(this).addClass("not--empty");
  }
});

$(document).on("click", ".go--previous", function () {
  var saveBtn = $(this),
    target = $(this).data("target"),
    nextStep = $(this).data("next");
  $("html, body").animate({ scrollTop: 0 }, 600);
  saveBtn.parents(".form__group").toggle(600);
  $(nextStep).toggle(600);
  return false;
});

$(document).on("click", ".skip--step", function () {
  var saveBtn = $(this),
    target = $(this).data("target"),
    nextStep = $(this).data("next");
  $("html, body").animate({ scrollTop: 0 }, 600);
  saveBtn.parents(".form__group").toggle(600);
  $(nextStep).toggle(600);
  return false;
});

//Submitting and validating forms
$(".form--submit").click(function () {
  var saveBtn = $(this),
    initialText = $(this).text(),
    target = $(this).data("target"),
    nextStep = $(this).data("next");
  $(target).validate({
    //   focusInvalid: false,
    ignore: ":not(:visible)",
    rules: {
      password: "required",
      passwordAgain: {
        equalTo: "#password",
      },
    },
    submitHandler: function (form) {
      // do other things for a valid form
      $(saveBtn).attr("disabled", "disabled");
      $(saveBtn).html('<div class="loader loader--light"></div>');
      setTimeout(function () {
        if (saveBtn.parents(".accordion__section").length) {
          $(saveBtn).closest(".accordion__section").removeClass("is--expanded");
          $(saveBtn).children(".checkmark").fadeOut(250);
          $(saveBtn).text(initialText).removeClass("is--pos");
        }
        if (saveBtn.hasClass("user--profile")) {
          var formName = document.getElementById("formName");
          var fd = new FormData(formName);
          $.ajax({
            url: $(form).attr("action") || window.location.pathname,
            data: fd,
            cache: false,
            contentType: false,
            processData: false,
            type: "POST",
            success: function (data) {
              $(saveBtn)
                .html('<div class="checkmark"></div>')
                .addClass("is--pos");
              $(saveBtn).removeAttr("disabled");
              $("#form_output").html(data);
              location.reload();
            },
            error: function (jXHR, textStatus, errorThrown) {
              $(saveBtn).text(initialText).removeClass("is--pos");
              $(saveBtn).removeAttr("disabled");
              alert(errorThrown);
            },
          });
        } else if (saveBtn.hasClass("no--refresh")) {
          $.ajax({
            url: $(form).attr("action") || window.location.pathname,
            type: "POST",
            data: $(form).serialize(),
            success: function (data) {
              $("#form_output").html(data);
              $(saveBtn)
                .html('<div class="checkmark"></div>')
                .addClass("is--pos");
              $(saveBtn).removeAttr("disabled");
              if (saveBtn.hasClass("registration_process")) {
                saveBtn.parents(".form__group").toggle(600);
                console.log(nextStep);
                $(nextStep).toggle(600);
                $("html, body").animate({ scrollTop: 0 }, 600);
                // increment progress bar
                var tar = $(saveBtn)
                    .closest(".form--multistep")
                    .find(".progress"),
                  cur = $(tar).attr("data-progress");
                cur++;
                $(tar).attr("data-progress", cur);
                updateProgress(tar, cur);
              }
            },
            error: function (jXHR, textStatus, errorThrown) {
              $(saveBtn).text(initialText).removeClass("is--pos");
              $(saveBtn).removeAttr("disabled");
              alert(errorThrown);
            },
          });
        } else if (saveBtn.hasClass("page--reload")) {
          $.ajax({
            url: $(form).attr("action") || window.location.pathname,
            type: "POST",
            data: $(form).serialize(),
            success: function (data) {
              $("#form_output").html(data);
              $(saveBtn)
                .html('<div class="checkmark"></div>')
                .addClass("is--pos");
              $(saveBtn).removeAttr("disabled");
              location.reload();
            },
            error: function (jXHR, textStatus, errorThrown) {
              $(saveBtn).text(initialText).removeClass("is--pos");
              $(saveBtn).removeAttr("disabled");
              alert("Error processing your request. Please try again.");
            },
          });
        } else if (saveBtn.hasClass("go--next")) {
          $(saveBtn).html('<div class="checkmark"></div>').addClass("is--pos");
          $(saveBtn).removeAttr("disabled");
          $("html, body").animate({ scrollTop: 0 }, 600);
          saveBtn.parents(".form__group").toggle(600);
          $(nextStep).toggle(600);
          // increment progress bar
          var tar = $(saveBtn).closest(".form--multistep").find(".progress"),
            cur = $(tar).attr("data-progress");
          cur++;
          $(tar).attr("data-progress", cur);
          updateProgress(tar, cur);
        } else if (saveBtn.hasClass("go--previous")) {
          $(saveBtn).html('<div class="checkmark"></div>').addClass("is--pos");
          $(saveBtn).removeAttr("disabled");
          $("html, body").animate({ scrollTop: 0 }, 600);
          saveBtn.parents(".form__group").toggle(600);
          $(nextStep).toggle(600);

          // increment progress bar
          var tar = $(saveBtn).closest(".form--multistep").find(".progress"),
            cur = $(tar).attr("data-progress");
          cur++;
          $(tar).attr("data-progress", cur);
          updateProgress(tar, cur);
        } else {
          form.submit();
        }
      }, 600);
    },
    invalidHandler: function (event, validator) {
      $(saveBtn).addClass("is--neg").animateCss("shake");
      setTimeout(function () {
        $(saveBtn).removeClass("is--neg");
      }, 600);
    },
  });
});

//Submitting and validating forms

//end
//view Promo restrictions
$(document).on("click", ".viewpromo", function () {
  var view_rules = $(this).data("rules");

  $(".promoRestrictions").html(view_rules);
});

$(document).on("click", ".pagesnippet", function (e) {
  e.preventDefault();
  page = $(this).text();
  var total_page = $(".total_page").val();
  if (page == "First") {
    page = "0";
  } else if (page == "Last") {
    page = total_page - 1;
  } else if (page == total_page) {
    page = total_page - 1;
  } else if (page == "←") {
    var closerpage = $("#page_no").text();
    page = closerpage - 2;
  } else if (page == "→") {
    page = $("#page_no").text();
  } else {
    page = page - 1;
  }

  if (search != "") {
    category = "";
    procedure = "";
    vendor_id = "";
    manufacturer = "";
    listid = "";
  }
  refresh_products();
});

$(document).on("click", ".upvoted", function () {
  var update_id = $(this).data("id");
  $.ajax({
    type: "POST",
    url: base_url + "upvote-vendor",
    data: { update_id: update_id },
    success: function (data) {
      location.reload();
    },
  });
});

$(document).on("change", ".per_page_count", function () {
  category = $(this).data("category");
  per_page = $(this).val();
  page = 0;
  refresh_products();
});

//vendor review
$(document).on("click", ".vendor-review", function () {
  var vendor_id = $(this).data("id");
  $(".v_id").val(vendor_id);
});

//vendor review
$(document).on("click", ".order-vendor-review", function () {
  var vendor_id = $(this).data("id");
  var vendor_name = $(this).data("vendorname");
  $(".v_id").val(vendor_id);
  $(".v_name").html(vendor_name);
});
//reject vendor review
$(document).on("click", ".reject-vendor", function () {
  var vendor_id = $(this).data("id");
  $.ajax({
    type: "POST",
    url: base_url + "reject-vendor",
    data: { vendor_id: vendor_id },
    success: function (data) {
      location.reload();
    },
  });
});

//prepopulated lists delete
$(document).on("click", ".delete_name", function () {
  var list = $(this).data("id");
  var lid = $(".list_id").val();
  $("#listname").text(list);
  $("#listId").val(lid);
});

$(document).on("click", ".update_list", function () {
  var lid = $(this).data("id");
  var lname = $(this).data("name");
  $(".listname").val(lname);
  $(".list_id").val(lid);
});
//add products to shopping list
$(document).on("click", ".add-shoppinglist-product", function () {
  var saveBtn = $(this),
    initialText = $(this).text();
  $(saveBtn).attr("disabled", "disabled");
  $(saveBtn).html('<div class="loader loader--light"></div>');
  var qty = $(".sqty").val();
  var pro_id = $(".p_id").val();
  var vendor_id = $("input[name=vendor]:checked").val();
  if ($(this).attr("checked")) {
    $(this).attr("checked", false);
    var lists_id = $(this).val();
  } else {
    $(this).attr("checked", true);
    var lists_id = $(this).val();
  }
  $.ajax({
    type: "POST",
    url: base_url + "addto-shoppinglist",
    data: { list_id: lists_id, pro_id: pro_id, vendor_id: vendor_id, qty: qty },
    success: function (data) {
      $(saveBtn).removeAttr("disabled");
      $(saveBtn).html('<div class="checkmark"></div>').addClass("is—pos");
      location.reload();
    },
  });
});
//remove products from shopping lists
$(document).on("click", ".remove-shoppinglist-product", function () {
  var list_id = $(this).val();
  var product_id = $(".p_id").val();
  $.ajax({
    type: "POST",
    url: base_url + "delete-shoping-products",
    data: { list_id: list_id, product_id: product_id },
    success: function (data) {
      location.reload();
    },
  });
});

$(document).on("click", ".add-new-list", function () {
  //crate new shopping lists
  var qty = $(".sqty").val();
  var pro_id = $(".p_id").val();
  var vendor_id = $("input[name=vendor]:checked").val();
  $(".quantity").val(qty);
  $(".product_id").val(pro_id);
  $(".v_id").val(vendor_id);
});

//user_licese delete
$(document).on("click", ".delete_licence", function () {
  var license_id = $(this).data("id");
  var l_no = $(this).data("lno");
  var l_dea = $(this).data("ldea");
  var l_date = $(this).data("ldate");
  var l_state = $(this).data("lstate");
  $(".del_id").html(l_no);
  $("#liDea").html(l_dea);
  $("#liDate").html(l_date);
  $("#liState").html(l_state);
  $("#licenseId").val(license_id);
});

//edit user card payment
$(document).on("click", ".user_payment", function () {
  var pay_id = $(this).data("id");
  var c_num = "**** **** ****" + $(this).data("card");
  var c_name = $(this).data("cname");
  var e_month = $(this).data("emonth");
  var e_year = $(this).data("eyear");
  var exp_year = e_year % 100;
  var cvv_num = "****";
  var pay_type = "card";
  var token = $(this).data("token");
  var e_date = e_month + "/" + exp_year;
  $(".paymentCardNum").val(c_num);
  $("#paymentCardName").val(c_name);
  $(".paymentExpiry").val(e_date);
  $(".paymentSecurity").val(cvv_num);
  $(".paymentType").val(pay_type);
  $(".p_id").val(pay_id);
  $(".token").val(token);
});

//edit user bank account
$(document).on("click", ".user_bank", function () {
  var pay_id = $(this).data("id");
  var bankname = $(this).data("bank");
  var routing_num = $(this).data("routing");
  var acnum = "**** **** ****" + $(this).data("account");
  var cname = $(this).data("cname");
  var pay_type = "bank";
  $(".paymentBankName").val(bankname);
  $(".paymentRoutingNum").val(routing_num);
  $(".paymentAccountNum").val(acnum);
  $(".paymentType").val(pay_type);
  $(".p_id").val(pay_id);
  $(".cname").val(cname);
});
//delete user payment
$(document).on("click", ".del_payment", function () {
  var dell_id = $(".p_id").val();
  var p_type = $(".paymentType").val();
  var cardnum = "";
  var exdate = "";
  if (p_type == "card") {
    cardnum = $(".paymentCardNum").val();
    exdate = $(".paymentExpiry").val();
  } else {
    cardnum = $(".paymentAccountNum").val();
    exdate = "Never Expires";
  }
  cc_num = "••••";
  (cc_num += cardnum.slice(-4)), $(".delete_id").val(dell_id);
  $(".expdate").html(exdate);
  $(".cnum").html(cc_num);
});

//unassign students
$(document).ready(function () {
  $(document).on("click", ".unassign-student", function () {
    var class_id = $(this).data("id");
    var student_id = $(this).data("student");
    $(".class_id").val(class_id);
    $.ajax({
      type: "POST",
      url: base_url + "getstudent",
      data: { student_id: student_id },
      success: function (data) {
        var student_name = "";
        var student_image = "";
        var img_url = base_url;
        var data = JSON.parse(data);
        var image = "avatar-default.png";
        if (data.students.first_name != null) {
          student_name = data.students.first_name;
        } else {
          student_name = data.students.email;
        }
        if (data.images != null) {
          student_image +=
            "<div class='avatar avatar--s' style='background-image:url('" +
            img_url +
            "uploads/user/profile/" +
            data.images.photo +
            "');''></div>";
        } else {
          student_image +=
            "<div class='avatar avatar--s' style='background-image:url('" +
            img_url +
            "assets/img/" +
            image +
            "')';></div>";
        }
        $(".sname").html(student_name);
        $(".student_image").html(student_image);
      },
    });
  });

  $(document).on("click", ".unassign_students", function () {
    var class_ids = $(".class_ids").val();
    $.ajax({
      type: "POST",
      url: base_url + "getstudents",
      data: { student_id: class_ids },
      success: function (data) {
        //alert(data);
        var img_url = base_url;
        var data = JSON.parse(data);
        var student_image = "";
        if (data.images != null) {
          for (var i = 0; i < data.images.length; i++) {
            if (data.images.length.length >= 4) {
              student_image +=
                "<td class='padding--xs no--pad-t no--pad-r no--pad-b valign--middle'><div class='avatar avatar--m disp--ib margin--xxs no--margin-t no--margin--b no--margin-l' style='background-image:url('" +
                img_url +
                "uploads/user/profile/" +
                data.images[i].photo +
                "');'></div></td>";
            } else if (data.images.length < 4) {
              student_image +=
                " <td class='padding--xs no--pad-t no--pad-r no--pad-b valign--middle'><div class='avatar avatar--m disp--ib margin--xxs no--margin-t no--margin--b no--margin-l' style='background-image:url('" +
                img_url +
                "uploads/user/profile/" +
                data.images[i].photo +
                "');'></div></td>";
              student_image +=
                " <td class='padding--xs no--pad-t no--pad-r no--pad-b valign--middle'><p class='no--pad'>" +
                data.images[i].length +
                "+more </td>";
            }
          }
        } else {
          for (var i = 0; i < class_ids.length; i++) {
            student_image +=
              "<td class='padding--xs no--pad-t no--pad-r no--pad-b valign--middle'><div class='avatar avatar--m disp--ib margin--xxs no--margin-t no--margin--b no--margin-l' style='background-image:url('http://placehold.it/192x192');'></div></td>";
          }
        }
        $(".student_image").html(student_image);
      },
    });
  });
});

$(".request_list")
  .unbind("click")
  .click(function () {
    //get single request lists price
    var request_id = $(this).data("request_id");
    var l_id = $(".l_id").val();
    var l_name = $(".locationName").val();

    var pqty = $(this).parent().prev().find(".r_quantity").val();
    $(".lid").val(l_id);
    if ($(this).hasClass("single")) {
      $(".qty").val(1);
      $(".item_count").html(pqty + " Item");
    } else {
      if ($(".qty").val() == "") {
        $(".qty").val(pqty);
      } else {
        $(".qty").val($(".qty").val() + "," + pqty);
      }
    }
    $.ajax({
      type: "POST",
      url: base_url + "getrequest-list-price",
      data: { request_id: request_id },
      success: function (data) {
        var data = JSON.parse(data);
        var product_price = data.pro_total;
        $(".total").html(
          parseFloat(Math.round(product_price * 100) / 100).toFixed(2)
        );
      },
    });
    $(".request_id").val(request_id);
  });

$(document).on("click", ".selected_list", function () {
  //get seletted request lists price
  var request_id = $(".request_ids").val();
  var l_id = $(".l_id").val();
  var l_name = $(".locationName").val();
  var pqty = $(this).parent().prev().find(".r_quantity").val();
  var update_qty = [];
  $.each($("input[name='checkboxRow']:checked"), function () {
    var values = $(this).parent().parent().parent().find(".r_quantity").val();
    update_qty.push(values);
    $(".lid").val(l_id);
  });
  $(".qty").val(update_qty);
  $.ajax({
    type: "POST",
    url: base_url + "getrequest-list-price",
    data: { request_id: request_id },
    success: function (data) {
      var data = JSON.parse(data);
      var product_price = data.pro_total;
      $(".total").html(
        parseFloat(Math.round(product_price * 100) / 100).toFixed(2)
      );
    },
  });
  $(".request_id").val(request_id);
});
// move all request lists to cart
$(document).ready(function () {
  $(document).on("click", ".move_all_to_cart", function () {
    var saveBtn = $(this),
      initialText = $(this).text();
    $(saveBtn).attr("disabled", "disabled");
    $(saveBtn).html('<div class="loader loader--light"></div>');
    var lists_id = $(this).data("id");
    $.ajax({
      type: "POST",
      url: base_url + "request-all-tocart",
      data: { list_id: lists_id },
      success: function (data) {
        $(saveBtn).removeAttr("disabled");
        $(saveBtn).html('<div class="checkmark"></div>').addClass("is—pos");
        location.reload();
      },
      error: function (jXHR, textStatus, errorThrown) {
        $(saveBtn).text(initialText).removeClass("is--pos");
        $(saveBtn).removeAttr("disabled");
        alert(errorThrown);
      },
    });
  });
});

$(document).ready(function () {
  //move selected shopping list to cart
  $(document).on("click", ".selected_shoppinglist", function () {
    var list_id = $(".product_list_id").val();
    var pqty = $(this).parent().prev().find(".request_quantity").val();
    var update_qty = [];
    $.each($("input[name='checkboxRow']:checked"), function () {
      var values = $(this)
        .parent()
        .parent()
        .parent()
        .find(".request_quantity")
        .val();
      update_qty.push(values);
    });
    $(".qty").val(update_qty);
    $.ajax({
      type: "POST",
      url: base_url + "getshopping-list-price",
      data: { list_id: list_id, update_qty: update_qty },
      success: function (data) {
        var data = JSON.parse(data);
        if (data != null) {
          var product_price = data.pro_total;
          var location_names = "";
          if (data.location_name != null) {
            var name_length = data.location_name.length;
            if (data.location_name.length >= 1) {
              for (var i = 0; i < data.location_name.length; i++) {
                location_names += data.location_name[i].nickname + ",";
              }
            } else {
              location_names += data.location_name.nickname;
            }
            location_names = location_names.slice(0, -1); //remove last comma in location name.
            $(".locationName").html(location_names);
            $(".listlocationid").val(data.location_name.id);
          }
          $(".total").html(product_price.toFixed(2));
        }
      },
    });
  });

  //move all shopping list to cart
  $(document).on("click", ".move_allListToCart", function () {
    var saveBtn = $(this),
      initialText = $(this).text();
    $(saveBtn).attr("disabled", "disabled");
    $(saveBtn).html('<div class="loader loader--light"></div>');

    var list_id = $(this).data("id");
    //alert(list_id);
    $.ajax({
      type: "POST",
      url: base_url + "shoppingList-all-tocart",
      data: { list_id: list_id },
      success: function (data) {
        $(saveBtn).removeAttr("disabled");
        $(saveBtn).html('<div class="checkmark"></div>').addClass("is—pos");
        location.reload();
      },
      error: function (jXHR, textStatus, errorThrown) {
        // alert(data);
        $(saveBtn).text(initialText).removeClass("is--pos");
        $(saveBtn).removeAttr("disabled");
        alert(errorThrown);
      },
    });
  });
});

$(document).on("click", ".updates", function () {
  $("#updateThresholdModal").close();
  location.reload();
});
$(document).on("click", ".remove-rquest", function () {
  var id = $(this).data("rid");
  $(".r_id").val(id);
});
$(document).on("click", ".update-inventory", function () {
  var get_qty = [];
  $.each($("input[name='checkboxRow']:checked"), function () {
    var qtys = $(this).parent().parent().parent().find(".inventory_qty").val();
    get_qty.push(qtys);
  });
  $(".product_qty").val(get_qty);
});

$(document).ready(function () {
  $(".inventory_qty").bind("input", function () {
    var qty = $(this).val();
    var update_id = $(this).closest("tr").find("[type=checkbox]").val();
    $.ajax({
      type: "POST",
      url: base_url + "update-inventory",
      data: { qty: qty, update_id: update_id },
      success: function (data) {},
      error: function (jXHR, textStatus, errorThrown) {
        alert(errorThrown);
      },
    });
  });

  $(".threshold_qty").bind("input", function () {
    var qty = $(this).val();
    var update_id = $(this).closest("tr").find("[type=checkbox]").val();
    $.ajax({
      type: "POST",
      url: base_url + "update-lowqty",
      data: { qty: qty, update_id: update_id },
      success: function (data) {},
      error: function (jXHR, textStatus, errorThrown) {
        // alert(data);
        alert(errorThrown);
      },
    });
  });
});

$(document).on("click", ".update_threshold", function () {
  var update_qty = [];
  var update_id = $(".i_id").val();
  $.each($("input[name='checkboxRow']:checked"), function () {
    var values = $(this)
      .parent()
      .parent()
      .parent()
      .find(".threshold_qty")
      .val();
    update_qty.push(values);
  });
  $(".low_qty").val(update_qty);
});

//products questions insert
$(document).on("click", ".questions", function () {
  var p_id = $(this).data("id");
  $(".product_id").val(p_id);
});

$(document).on("change", ".view_review", function () {
  var options = $(this).val();
  var ven_id = $(".vendor_id").val();
  window.location.href =
    base_url + "vendor-profile?id=" + ven_id + "&options=" + options;
});
$(document).on("change", ".view_product", function () {
  var options = $(this).val();
  var product_id = $(".product_id").val();
  window.location.href =
    base_url + "view-product?id=" + product_id + "&options=" + options;
});

$(document).ready(function () {
  $(document).on("change", ".view_locations", function () {
    //set user seletcted shopping locations in session
    var cart_location_id = $(this).val();
    $.ajax({
      type: "POST",
      url: base_url + "set-session",
      data: { location_id: cart_location_id },
      success: function (data) {
        location.reload();
      },
    });
  });

  $(document).on("change", ".switch-view", function () {
    var id = $(this).val();
    window.location.href = base_url + "cart?id=" + id;
  });

  $(document).on("click", ".add_location", function () {
    $("#chooseLocationModal").hide();
    $("#chooseRequestListModal").hide();
  });

  $("#shopButtons .popover").hide();
  $(".view-cart")
    .click(function () {
      $.ajax({
        type: "POST",
        url: base_url + "get-cart-data",
        success: function (data) {
          var data = JSON.parse(data);
          if (data.user_locations != null) {
            if (data.user_locations.length == 1) {
              window.location.href =
                base_url + "cart?id=" + data.user_locations[0].id;
            }
          }
        },
      });
    })
    .mouseover(function () {
      if ($(".user-cart").html() == "") {
        $.ajax({
          type: "POST",
          url: base_url + "get-cart-data",
          success: function (data) {
            var data = JSON.parse(data);
            var carts = "";
            var carts2 = "";
            if (data.user_locations != null) {
              if (data.user_locations.length > 1) {
                for (var i = 0; i < data.user_locations.length; i++) {
                  if (data.user_locations[i].item_count > 0) {
                    carts +=
                      "<li class='item '><a class='link has--badge'  href='cart?id=" +
                      data.user_locations[i].id +
                      "' cart_id=" +
                      data.user_locations[i].id +
                      " data-badge='" +
                      data.user_locations[i].item_count +
                      "'>" +
                      data.user_locations[i].name +
                      " </a></li>";
                    carts2 +=
                      "<a class='dropdown-item' href='cart?id=" +
                      data.user_locations[i].id +
                      "'>(" +
                      data.user_locations[i].item_count +
                      ")" +
                      data.user_locations[i].name +
                      "</a>";
                  } else {
                    carts +=
                      "<li class='item'><a class='link has--badge'  data-badge='0'>" +
                      data.user_locations[i].name +
                      " </a></li>";
                    carts2 +=
                      "<a class='dropdown-item' href='#'>" +
                      data.user_locations[i].name +
                      "</a>";
                  }
                }
                $(".user-cart").html(carts);
                $(".user-cart2").html(carts2);
                $("#shopButtons .popover").show();
              }
            }
          },
        });
      }
    });

  $(document).on("click", ".cart-view", function () {
    var id = $(this).attr("cart_id");

    window.location.href = base_url + "cart?id=" + id;
  });

  $(document).on("change", ".ytd", function () {
    var ymd = $(this).val();
    if (ymd == "MTD") {
      window.location.href = base_url + "dashboard";
    } else {
      $.ajax({
        url: base_url + "get-ytd",
        success: function (data) {
          var data = JSON.parse(data);
          var yearly = "";
          for (var i = 0; i < data.total_spend.length; i++) {
            if (data.total_spend[i].totals != null) {
              var yearly_total = parseFloat(
                Math.round(data.total_spend[i].totals * 100) / 100
              ).toFixed(2);
              yearly += "$" + Number(yearly_total).toLocaleString("en");
            } else {
              yearly += "$ 0.00";
            }

            $(".year").text(yearly);
          }
        },
      });
    }
  });
  $(document).on("change", ".Yearly_Total", function () {
    var location_id = $(this).attr("data-user_location");
    var yearly = $(this).val();
    $.ajax({
      type: "POST",
      url: base_url + "location-based-yearlyTotal",
      data: { location_id: location_id, timeframe: yearly },
      success: function (data) {
        var output = JSON.parse(data);
        var totals = "-";
        if (!isNaN(parseFloat(output[0].totals))) {
          totals = "$" + parseFloat(output[0].totals).toFixed(2);
        }
        $(".Yearly_total_" + location_id).html(totals);
      },
    });
  });
});

//change Request List product Vendor
$(document).on("click", ".change_vendor", function () {
  var list_id = $(this).data("list_id");
  var product_id = $(this).data("product_id");
  var old_vendor_id = $(this).data("vendor_id");
  $.ajax({
    type: "POST",
    url: base_url + "get-vendors-details",
    data: { product_id: product_id },
    success: function (data) {
      var data = JSON.parse(data); //object
      var vendr_data = "";
      var vendor_ratings = 0;
      for (var i = 0; i < data.products.length; i++) {
        var reviews = data.products[i].reviews;
        var vendor_id = data.products[i].vendor_id;
        vendor_ratings = 0;
        if (reviews == null) {
        } else {
          for (var j = 0; j < data.products[i].reviews.length; j++) {
            var product_ven_id = data.products[i].reviews[j].model_id;
            if (product_ven_id == vendor_id) {
              var rating = Number(data.products[i].reviews[j].rating);
              vendor_ratings = vendor_ratings + rating;
            } else {
              vendor_ratings = Number(data.products[i].reviews[j].rating);
            }
          }
        }
        var price = 0;
        if (data.products[i].retail_price > 0) {
          price = data.products[i].retail_price;
        } else {
          price = data.products[i].price;
        }
        var myDate = new Date(data.products[i].updated_at);
        var updatede_date = moment(myDate).format("ll");
        for (var k = 0; k < data.products[i].vendors.length; k++) {
          var vendor_name = data.products[i].vendors[k].name;
          if (vendor_name != null) {
            vendr_data +=
              "<tr> <td class='width--25 padding--xs no--pad-t no--pad-r no--pad-l'><p class='no--margin-b no--margin-t no--margin-r no--margin-l'>" +
              vendor_name +
              "</p></td><td class='width--15 padding--xs no--pad-t no--pad-r no--pad-l'><p class='no--margin-b no--margin-t no--margin-r no--margin-l'>$" +
              price +
              "</p></td><td class='width--20 padding--xs no--pad-t no--pad-r no--pad-l'><p class='no--margin-b no--margin-t no--margin-r no--margin-l'>" +
              updatede_date +
              "</p></td><td class='width--20 padding--xs no--pad-t no--pad-r no--pad-l'><div class='ratings__wrapper ratings--m' data-raters='12'><div class='ratings'>  ";
            if (reviews == null) {
              vendr_data +=
                "<div class='stars' data-rating='0' style='width: 0%;'></div>";
            } else {
              vendr_data +=
                "<div class='stars' data-rating='" +
                vendor_ratings +
                "' style='width: " +
                vendor_ratings +
                "%;'></div>";
            }
            if (old_vendor_id == data.products[i].vendors[k].id) {
              vendr_data +=
                "</div></div></td><td> <button class='btn btn--s btn--toggle width--100 is--pos is--pos  update_vendor' data-vendor_id=" +
                data.products[i].vendors[k].id +
                " data-list_id=" +
                list_id +
                " data-target='#ChangeVendor'>&#10003; Selected</button></td></tr>";
            } else {
              vendr_data +=
                "</div></div></td><td> <button class='btn btn--s btn--tertiary save--toggle width--100 default--action  update_vendor' data-vendor_id=" +
                data.products[i].vendors[k].id +
                " data-list_id=" +
                list_id +
                " data-target='#ChangeVendor'>Select</button></td></tr>";
            }
          }
        }
      }
      $(".vendors_details").html(vendr_data);
    },
  });
});

//update request list vendor
$(document).on("click", ".update_vendor", function () {
  var ven_id = $(this).data("vendor_id");
  var update_id = $(this).data("list_id");
  $.ajax({
    type: "POST",
    url: base_url + "update-vendor-request-product",
    data: { update_id: update_id, ven_id: ven_id },
    success: function (data) {
      location.reload();
    },
  });
});

//change shopping list vendor
$(document).on("click", ".vendor_change", function () {
  var list_id = $(this).data("list_id");
  var product_id = $(this).data("product_id");
  var old_vendor_id = $(this).data("vendor_id");
  $.ajax({
    type: "POST",
    url: base_url + "get-vendors-details",
    data: { product_id: product_id },
    success: function (data) {
      var data = JSON.parse(data); //object
      var vendr_data = "";
      var vendor_ratings = 0;
      for (var i = 0; i < data.products.length; i++) {
        var reviews = data.products[i].reviews;
        var vendor_id = data.products[i].vendor_id;
        vendor_ratings = 0;
        if (reviews == null) {
        } else {
          for (var j = 0; j < data.products[i].reviews.length; j++) {
            var product_ven_id = data.products[i].reviews[j].model_id;
            if (product_ven_id == vendor_id) {
              var rating = Number(data.products[i].reviews[j].rating);
              vendor_ratings = vendor_ratings + rating;
            } else {
              vendor_ratings = Number(data.products[i].reviews[j].rating);
            }
          }
        }
        var price = 0;
        if (data.products[i].retail_price > 0) {
          price = data.products[i].retail_price;
        } else {
          price = data.products[i].price;
        }
        var myDate = new Date(data.products[i].updated_at);
        var updatede_date = moment(myDate).format("ll");
        for (var k = 0; k < data.products[i].vendors.length; k++) {
          var vendor_name = data.products[i].vendors[k].name;
          if (vendor_name != null) {
            vendr_data +=
              "<tr> <td class='width--25 padding--xs no--pad-t no--pad-r no--pad-l'><p class='no--margin-b no--margin-t no--margin-r no--margin-l'>" +
              vendor_name +
              "</p></td><td class='width--15 padding--xs no--pad-t no--pad-r no--pad-l'><p class='no--margin-b no--margin-t no--margin-r no--margin-l'>$" +
              price +
              "</p></td><td class='width--20 padding--xs no--pad-t no--pad-r no--pad-l'><p class='no--margin-b no--margin-t no--margin-r no--margin-l'>" +
              updatede_date +
              "</p></td><td class='width--20 padding--xs no--pad-t no--pad-r no--pad-l'><div class='ratings__wrapper ratings--m' data-raters='12'><div class='ratings'>  ";
            if (reviews == null) {
              vendr_data +=
                "<div class='stars' data-rating='0' style='width: 0%;'></div>";
            } else {
              vendr_data +=
                "<div class='stars' data-rating='" +
                vendor_ratings +
                "' style='width: " +
                vendor_ratings +
                "%;'></div>";
            }
            if (old_vendor_id == data.products[i].vendors[k].id) {
              vendr_data +=
                "</div></div></td><td> <button class='btn btn--s btn--toggle width--100 is--pos is--pos update_vendors' data-vendor_id=" +
                data.products[i].vendors[k].id +
                " data-list_id=" +
                list_id +
                " data-target='#ChangeVendor'>&#10003; Selected</button></td></tr>";
            } else {
              vendr_data +=
                "</div></div></td><td> <button class='btn btn--s btn--tertiary save--toggle width--100 default--action  update_vendors' data-vendor_id=" +
                data.products[i].vendors[k].id +
                " data-list_id=" +
                list_id +
                " data-target='#ChangeVendor'>Select</button></td></tr>";
            }
          }
        }
      }
      $(".vendors_detail").html(vendr_data);
    },
  });
});

//update shopping list vendor
$(document).on("click", ".update_vendors", function () {
  var ven_id = $(this).data("vendor_id");
  var update_id = $(this).data("list_id");
  $.ajax({
    type: "POST",
    url: base_url + "change-shopping-list-vendor",
    data: { update_id: update_id, ven_id: ven_id },
    success: function (data) {
      location.reload();
    },
  });
});
$(document).ready(function () {
  $(document).on("click", ".change_payment", function () {
    //change payment mode in cart
    var id = $(this).data("location_id");
    $.ajax({
      type: "POST",
      url: base_url + "get-paymentlist",
      success: function (data) {
        var data = JSON.parse(data);
        var payment_options = "";
        for (var i = 0; i < data.payments.length; i++) {
          payment_options +=
            "<li class='item payment_id'><input type='hidden' name='' value=" +
            id +
            " class='location_id '><label class='control control__radio'><input type='radio' class='close_new' name='paymentMethod' value=" +
            data.payments[i].id +
            " ><div class='control__indicator'></div> <p class='no--margin textColor--darkest-gray'>";
          if (data.payments[i].payment_type == "card") {
            var card_number = data.payments[i].cc_number;
            var card_type = data.payments[i].card_type;
            var inn = card_number.substr(0, 2);
            var card_num = card_number.slice(-4);
            if (card_type == "Visa") {
              payment_options +=
                "<svg class='icon icon--cc icon--visa'></svg>" +
                card_type +
                " •••• " +
                card_number +
                "</p></label></li>";
            } else if (card_type == "MasterCard") {
              payment_options +=
                "<svg class='icon icon--cc icon--mastercard'></svg>" +
                card_type +
                " •••• " +
                card_number +
                "</p></label></li>";
            } else if (card_type == "Discover") {
              payment_options +=
                "<svg class='icon icon--cc icon--discover'></svg>" +
                card_type +
                " •••• " +
                card_number +
                "</p></label></li>";
            } else if (card_type == "American Express") {
              payment_options +=
                "<svg class='icon icon--cc icon--amex'></svg>" +
                card_type +
                " •••• " +
                card_number +
                "</p></label></li>";
            } else {
              payment_options +=
                "<svg class='icon icon--cc icon--undefined'></svg>undefined •••• " +
                card_num +
                "</p></label></li>";
            }
          } else if (data.payments[i].payment_type == "bank") {
            var account_number = data.payments[i].ba_account_number;
            account_number = account_number.substr(-4);
            payment_options +=
              " <svg class='icon icon--cc icon--bank'></svg>" +
              data.payments[i].bank_name +
              " •••• " +
              account_number +
              "</p></label></li>";
          }
        }
        $(".payment").html(payment_options);
      },
    });
  });

  $(document).on("click", ".newpay", function () {
    $("#close_model").hide();
  });
  $(document).on("click", ".close_new", function () {
    $("#condNewPaymentMethod").hide();
    $("#condNewLocation").hide();
    $("#close_model").show();
  });
});

$(document).ready(function () {
  $(document).on("click", ".cart-checkout", function () {
    var saveBtn = $(".cart-checkout"),
      initialText = $(".cart-checkout").text(),
      target = $(".cart-checkout").data("target"),
      nextStep = $(".cart-checkout").data("next");
    $(saveBtn).html('<div class="loader loader--light"></div>');
    var payment_id = $(".payment_token").val();

    // if (payment_id != "" && payment_id !== undefined) { live
    var fields = $(".shippings_type");
    var bError = false;
    $.each(fields, function (i, field) {
      if (!field.value) {
        bError = true;
        alert("Select Shipping type");
      }
    });
    // } else {
    //     alert("This order can't be processed. Please select or add a payment method");
    // }

    if (bError == false) {
      $(saveBtn).html('<div class="loader loader--light"></div>');
      $(saveBtn).attr("disabled", "disabled");
      setTimeout(function () {
        $("#confirmCheckout").submit();
      }, 100);
    } else {
      $(saveBtn).removeAttr("disabled");
      $(saveBtn).text(initialText).removeClass("is--pos");
    }
  });
});
$(document).on("click", ".payment-data", function () {
  //get changed payments details in cart
  var p_id = $("input[name=paymentMethod]:checked").val();
  $.ajax({
    type: "POST",
    url: base_url + "change-cart-payment",
    data: { p_id: p_id },
    success: function (data) {
      window.location.reload();
    },
  });
});

//Conditional Fields
$(".toggle--conditional").click(function () {
  var target = $(this).data("target");
  $(target).toggleClass("is--hidden is--visible");
});
$(".control__conditional").change(function () {
  var target = $(this).data("target");
  $(target).toggle(400, function () {});
});
$(".control__conditional").bind("deselect", function () {
  var target = $(this).data("target");
  $(target).hide(400, function () {});
  $("#close_model").show();
});

// Binds a 'deselect' event to radio buttons
$('input[type="radio"]').bind("click", function () {
  $('input[name="' + $(this).attr("name") + '"]')
    .not($(this))
    .trigger("deselect");
});

// Delete table row
$(".table .delete--row").click(function () {
  var parent = $(this).closest(".page__tab");
  var row = $(this).closest("table").find(".table__row");
  $(this)
    .closest("tr")
    .toggle(200, function () {
      $(this).remove();
      //Check if no rows exist (for orders in the cart)
      if ($(row).length <= 1) {
        collapseTab(parent);
      }
    });
});

// Progress Bar
function updateAllProgress(target) {
  $(target).each(function () {
    var steps = $(this).data("steps"),
      cur = $(this).data("progress"),
      progress = (cur / steps) * 100 + "%",
      bar = $(this).find(".progress__inner"),
      counter = $(this).find(".progress__percentage");

    bar.animate({ width: progress }, 600);
    counter.text(progress);
  });
}
function updateProgress(target, step) {
  console.log(target);
  var steps = $(target).data("steps"),
    progress = (step / steps) * 100 + "%",
    bar = $(target).find(".progress__inner"),
    counter = $(target).find(".progress__percentage");

  bar.animate({ width: progress }, 600);
  counter.text(progress);
}
$(document).ready(function () {
  updateAllProgress(".progress");
});

$("#up").click(function () {
  var tar = $(this).data("target"),
    cur = $(tar).attr("data-progress");
  cur++;
  $(tar).attr("data-progress", cur);
  updateProgress(tar, cur);
});

$("#down").click(function () {
  var tar = $(this).data("target"),
    cur = $(tar).attr("data-progress");
  cur--;
  $(tar).attr("data-progress", cur);
  updateProgress(tar, cur);
});

// Close tabs on Cart
$(".close--tab").click(function () {
  var target = $(this).closest(".page__tab");
  var modal = $(this).data("target");
  var defBtn = $(modal).find(".default--action");
  var saveLater = $(modal).find(".vendor-save-later");
  var matixSaveLater = $(modal).find(".matix-save-later");
  var removeVendor = $(modal).find(".remove-cart-vendor");
  var removeMatixVendor = $(modal).find(".remove-matix-vendor");

  $(defBtn).click(function () {
    $(".modal").removeClass("is-visible");
    setTimeout(function () {
      $("body").removeClass("has-modal");
    }, 400);
    collapseTab(target);
  });
  //independent vendor save later
  $(saveLater).click(function () {
    $(".modal").removeClass("is-visible");
    setTimeout(function () {
      $("body").removeClass("has-modal");
      var vendor_id = $(".vendor_id").val();
      var cart_location_id = $(".location_id").val();

      $.ajax({
        type: "POST",
        url: base_url + "vendor-save-later",
        data: { vendor_id: vendor_id, cart_location_id: cart_location_id },
        success: function (data) {
          location.reload();
        },
      });
    }, 500);
    collapseTab(target);
  });
  //matix vendors save later
  $(matixSaveLater).click(function () {
    $(".modal").removeClass("is-visible");
    setTimeout(function () {
      $("body").removeClass("has-modal");
      var cart_location_id = $(".location_id").val();
      $.ajax({
        type: "POST",
        url: base_url + "matix-save-later",
        data: { cart_location_id: cart_location_id },
        success: function (data) {
          location.reload();
        },
      });
    }, 500);
    collapseTab(target);
  });
  //remove independent vendor
  $(removeVendor).click(function () {
    $(".modal").removeClass("is-visible");
    setTimeout(function () {
      $("body").removeClass("has-modal");
      var vendor_id = $(".vendor_id").val();
      var cart_location_id = $(".location_id").val();
      $.ajax({
        type: "POST",
        url: base_url + "remove-cart-vendor",
        data: { vendor_id: vendor_id, cart_location_id: cart_location_id },
        success: function (data) {
          location.reload();
        },
      });
    }, 500);
    collapseTab(target);
  });
  //remove matix vendor
  $(removeMatixVendor).click(function () {
    $(".modal").removeClass("is-visible");
    setTimeout(function () {
      $("body").removeClass("has-modal");
      var cart_location_id = $(".location_id").val();
      $.ajax({
        type: "POST",
        url: base_url + "remove-matix-vendor",
        data: { cart_location_id: cart_location_id },
        success: function (data) {
          location.reload();
        },
      });
    }, 500);
    collapseTab(target);
  });
});

function collapseTab(target) {
  $(target).css({ "flex-grow": "0", overflow: "hidden", "min-width": "0" });
  $(target).children().fadeOut(100);
  $(target).animate(
    {
      width: 0,
      padding: 0,
      opacity: 0,
    },
    200,
    function () {
      $(this).remove();
    }
  );
}

//upload excel With vendor
$(document).ready(function () {
  $(".add_vendor").click(function () {
    $.ajax({
      url: base_url + "get-vendors",
      success: function (data) {
        var data = JSON.parse(data);
        var vendor = "";
        vendor += "<option selected disabled>Select a Vendor</option>";
        for (var i = 0; i < data.vendors.length; i++) {
          vendor +=
            "<option value=" +
            data.vendors[i].id +
            ">" +
            data.vendors[i].name +
            "</option>";
        }
        $(".vendors_data").html(vendor);
      },
    });
  });
});

// Target Delete Buttons
$(".delete--target").click(function () {
  var target = $(this).closest(".target");
  $(target).fadeOut(200, function () {
    $(target).remove();
  });
});

// Notification Banners
$(document).on("click", ".dismiss--banner", function () {
  $(this).closest(".banner").toggle();
});

//WYSIWYG Editor (Froala)
$(document).ready(function () {
  if ($(".input--wysiwyg").length) {
    $(".input--wysiwyg").froalaEditor({
      toolbarSticky: false,
    });
  }
});

$(".add--row").click(function () {
  var parent = $(this).parents(".table");
  var row = $(this).parents("tr");
  $(row).clone(true).insertAfter(row).find(".input").val("");
});
/*
 *      For now it is used to get the user_id from the table.
 *          1. SuperAdminDashboard ->  Admin Users
 */
$(document).ready(function () {
  var title = new Array();
  $("#selectAll").click(function () {
    if ($(".singleCheckbox").attr("checked")) {
      $(".singleCheckbox").each(function () {
        $(this).attr("checked", false);
        var user_id = $(this).val();
        var index = title.indexOf(user_id);
        if (index > -1) {
          title.splice(index, 1);
        }
        $("#user_id,#deleteUser_id,#product_id").val(title);
      });
      $(this).attr("checked", false);
    } else {
      $(this).attr("checked", true);
      $(".singleCheckbox").each(function () {
        $(this).attr("checked", true);
        var user_id = $(this).val();
        var index = title.indexOf(user_id);
        title[title.length] = user_id;
        $("#user_id,#deleteUser_id,#product_id").val(title);
        n = Object.keys(title).length;
        (n += " Items"), $(".item_count").html(n);
      });
    }
  });

  $(".singleCheckbox").click(function () {
    if ($(this).attr("checked")) {
      $(this).attr("checked", false);
      var user_id = $(this).val();
      var index = title.indexOf(user_id);
      if (index > -1) {
        title.splice(index, 1);
      }
      $("#user_id,#deleteUser_id,#product_id").val(title);
    } else {
      $(this).attr("checked", true);
      var user_id = $(this).val();
      title[title.length] = user_id;
      $("#user_id,#deleteUser_id,#product_id").val(title);
      n = Object.keys(title).length;
      (n += " Items"), $(".item_count").html(n);
    }
  });
});
$(document).ready(function () {
  $(".approve_license").click(function () {
    var saveBtn = $(this),
      initialText = $(this).text();
    $(saveBtn).attr("disabled", "disabled");
    $(saveBtn).html('<div class="loader loader--light"></div>');
    var customer_id = $("#user_id").val();
    var license_id = $(this).attr("id");
    $(this).parent().parent().parent().addClass("is--verified");
    $.ajax({
      type: "POST",
      url: base_url + "approve-user-license",
      data: { license_id: license_id, customer_id: customer_id },
      success: function (data) {
        if (data == "1") {
          $(saveBtn).removeAttr("disabled");
          $(saveBtn).html('<div class="checkmark"></div>').addClass("is—pos");
          location.reload();
        } else {
          $(saveBtn).text(initialText).removeClass("is--pos");
          $(saveBtn).removeAttr("disabled");
        }
      },
      error: function (jXHR, textStatus, errorThrown) {
        $(saveBtn).text(initialText).removeClass("is--pos");
        $(saveBtn).removeAttr("disabled");
        alert(errorThrown);
      },
    });
  });
  $(".disapprove_license").click(function () {
    var saveBtn = $(this),
      initialText = $(this).text();
    $(saveBtn).attr("disabled", "disabled");
    $(saveBtn).html('<div class="loader loader--light"></div>');
    var customer_id = $("#user_id").val();
    var license_id = $(this).attr("id");
    $(this).parent().parent().parent().removeClass("is--verified");
    if ($(this).parent().find(".license__card")) {
      $(".license__card")
        .parent()
        .parent()
        .parent()
        .removeClass("is--verified");
    }
    $.ajax({
      type: "POST",
      url: base_url + "disapprove-license",
      data: { license_id: license_id, customer_id: customer_id },
      success: function (data) {
        if (data == "1") {
          $(saveBtn).removeAttr("disabled");
          $(saveBtn).html('<div class="checkmark"></div>').addClass("is—pos");
          location.reload();
        } else {
          $(saveBtn).text(initialText).removeClass("is--pos");
          $(saveBtn).removeAttr("disabled");
        }
      },
      error: function (jXHR, textStatus, errorThrown) {
        $(saveBtn).text(initialText).removeClass("is--pos");
        $(saveBtn).removeAttr("disabled");
        alert(errorThrown);
      },
    });
  });
  $(".approveAll").click(function () {
    var saveBtn = $(this),
      initialText = $(this).text();
    $(saveBtn).attr("disabled", "disabled");
    $(saveBtn).html('<div class="loader loader--light"></div>');

    var customer_id = $(this).attr("id");
    $.ajax({
      type: "POST",
      url: base_url + "approve-all-license",
      data: { customer_id: customer_id },
      success: function (data) {
        $(saveBtn).removeAttr("disabled");
        $(saveBtn).html('<div class="checkmark"></div>').addClass("is—pos");
        location.reload();
      },
      error: function (jXHR, textStatus, errorThrown) {
        $(saveBtn).text(initialText).removeClass("is--pos");
        $(saveBtn).removeAttr("disabled");
        alert(errorThrown);
      },
    });
  });
  $(".denyAll").click(function () {
    var saveBtn = $(this),
      initialText = $(this).text();
    $(saveBtn).attr("disabled", "disabled");
    $(saveBtn).html('<div class="loader loader--light"></div>');
    var customer_id = $(this).attr("id");
    $.ajax({
      type: "POST",
      url: base_url + "deny-all-license",
      data: { customer_id: customer_id },
      success: function (data) {
        $(saveBtn).removeAttr("disabled");
        $(saveBtn).html('<div class="checkmark"></div>').addClass("is—pos");
        location.reload();
      },
      error: function (jXHR, textStatus, errorThrown) {
        $(saveBtn).text(initialText).removeClass("is--pos");
        $(saveBtn).removeAttr("disabled");
        alert(errorThrown);
      },
    });
  });

  $(document).on("click", ".unassign_location", function () {
    $(this).addClass("btn--tertiary");
    $(this).removeClass("is--pos");
    var location_id = $(this).attr("data-location_id");
    var customer_id = $("#customer_id").val();
    $.ajax({
      type: "POST",
      url: base_url + "unassign-user-location",
      data: { customer_id: customer_id, location_id: location_id },
      success: function (data) {},
    });
  });

  $(document).on("click", ".assign_location", function () {
    $(this).addClass("is--pos");
    $(this).removeClass("btn--tertiary");
    var customer_id = $("#customer_id").val();
    var organization_location_id = $(this)
      .parents("li")
      .find("#organization_location_id")
      .val();
    $.ajax({
      type: "POST",
      url: base_url + "assign-user-location",
      data: {
        customer_id: customer_id,
        organization_location_id: organization_location_id,
      },
      success: function (data) {},
    });
  });

  $(".search_location").keyup(function () {
    var organization_id = $("#organization_id").val();
    var customer_id = $("#customer_id").val();
    var search = $(this).val();
    $.ajax({
      type: "POST",
      url: base_url + "search-organization-location",
      dataType: "json",
      data: {
        search: search,
        organization_id: organization_id,
        customer_id: customer_id,
      },
      success: function (data) {
        $(".org_location").remove();
        if (data.length > 0) {
          for (var i = 0; i < data.length; i++) {
            var htmlString =
              "<li class='item card padding--xs cf org_location'><input type='hidden' name='organization_location_id' id='organization_location_id' value=" +
              data[i].id +
              "><div class='wrapper'><div class='wrapper__inner'>" +
              data[i].nickname +
              "</div><div class='wrapper__inner align--right'><button class='btn btn--s " +
              (data[i].status == "1"
                ? " unassign_location is--pos "
                : " assign_location  btn--tertiary ") +
              " btn--toggle width--fixed-75  ' data-after='&#10003;'  data-before='Select'  data-location_id='" +
              data[i].user_location_id +
              "' type='button'></button></div></div></li>";
            $("#org_location_ul").append(htmlString);
          }
        }
      },
    });
  });
  $(document).on("click", ".includeProToPrepopList", function () {
    $(this).val("✓");
    $(this).removeClass("btn--tertiary").addClass("is--pos");
    var product_id = $("#product_id").val();
    var list_id = $(this).data("list_id");
    $.ajax({
      type: "POST",
      url: base_url + "add-productTo-PrePopulatedList",
      data: { list_id: list_id, product_id: product_id },
      success: function (data) {},
    });
  });
  $(".prepopSearch").keyup(function () {
    var search = $(this).val();
    $.ajax({
      type: "POST",
      url: "search-prePopulated-List",
      data: { search: search },
      success: function (data) {
        $(".ListSearch").html(data);
      },
    });
  });

  $(".promoCodeCreate").click(function () {
    var saveBtn = $(this),
      initialText = $(this).text();
    $(saveBtn).attr("disabled", "disabled");
    $(saveBtn).html('<div class="loader loader--light"></div>');

    var PromoId = $("#PromoId").val();
    var promoTitle = $("#createPromoTitle").val();
    var code = $("#promoCode").val();
    var discount = $("#discount").val();
    var discount_type = $("#discount_type").val();
    var discount_on = $("#discount_on").val();
    var threshold_count = $("#threshold_count").val();
    var threshold_type = $("#threshold_type").val();
    var start_date = $("#start_date").val();
    var end_date = $("#end_date").val();
    var product_free = $("#product_free").is(":checked") ? 1 : 0;
    var free_product_id = $(".free_productpricing_id").val();
    var product_Name = $("#product_Name").val();
    var conditions = $("#conditions").val();
    var use_with_promos = $("#use_with_promos").is(":checked") ? 1 : 0;
    var manufacturer_coupon = $("#manufacturer_coupon").is(":checked") ? 1 : 0;
    $.ajax({
      type: "POST",
      url: base_url + "vendors-dashboard-newPromo",
      data: {
        PromoId: PromoId,
        promoTitle: promoTitle,
        code: code,
        discount: discount,
        discount_type: discount_type,
        discount_on: discount_on,
        threshold_count: threshold_count,
        threshold_type: threshold_type,
        start_date: start_date,
        end_date: end_date,
        conditions: conditions,
        manufacturer_coupon: manufacturer_coupon,
        free_product_id: free_product_id,
        use_with_promos: use_with_promos,
        product_free: product_free,
        product_Name: product_Name,
      },
      success: function (data) {
        $(saveBtn).removeAttr("disabled");
        $(saveBtn).html('<div class="checkmark"></div>').addClass("is—pos");
        location.reload();
      },
      error: function (jXHR, textStatus, errorThrown) {
        $(saveBtn).text(initialText).removeClass("is--pos");
        $(saveBtn).removeAttr("disabled");
        alert(errorThrown);
      },
    });
  });
  $(".promoCodeUpdate").click(function () {
    var saveBtn = $(this),
      initialText = $(this).text();
    $(saveBtn).attr("disabled", "disabled");
    $(saveBtn).html('<div class="loader loader--light"></div>');
    var promoId = $(this).attr("data-promoCodeId");
    var promoTitle = $("#editExistingCodeModal" + promoId)
      .find(".createPromoTitle")
      .val();
    var code = $("#editExistingCodeModal" + promoId)
      .find(".promoCode")
      .val();
    var discount = $("#editExistingCodeModal" + promoId)
      .find(".discount")
      .val();
    var discount_type = $("#editExistingCodeModal" + promoId)
      .find(".discount_type")
      .val();
    var discount_on = $("#editExistingCodeModal" + promoId)
      .find(".discount_on")
      .val();
    var threshold_count = $("#editExistingCodeModal" + promoId)
      .find(".threshold_count")
      .val();
    var threshold_type = $("#editExistingCodeModal" + promoId)
      .find(".threshold_type")
      .val();
    var start_date = $("#editExistingCodeModal" + promoId)
      .find(".start_date")
      .val();
    var end_date = $("#editExistingCodeModal" + promoId)
      .find(".end_date")
      .val();
    var free_product_id = $("#editExistingCodeModal" + promoId)
      .find(".free_productpricing_id")
      .val();
    var product_Name = $("#editExistingCodeModal" + promoId)
      .find(".product_Name")
      .val();
    var product_free = $("#editExistingCodeModal" + promoId)
      .find(".product_free")
      .is(":checked")
      ? 1
      : 0;
    var conditions = $("#editExistingCodeModal" + promoId)
      .find(".conditions")
      .val();
    var use_with_promos = $("#editExistingCodeModal" + promoId)
      .find(".use_with_promos")
      .is(":checked")
      ? 1
      : 0;
    var manufacturer_coupon = $("#editExistingCodeModal" + promoId)
      .find(".manufacturer_coupon")
      .is(":checked")
      ? 1
      : 0;
    $.ajax({
      type: "POST",
      url: base_url + "vendors-dashboard-updatePromoCode",
      data: {
        PromoId: promoId,
        promoTitle: promoTitle,
        code: code,
        discount: discount,
        discount_type: discount_type,
        discount_on: discount_on,
        threshold_count: threshold_count,
        threshold_type: threshold_type,
        start_date: start_date,
        end_date: end_date,
        conditions: conditions,
        manufacturer_coupon: manufacturer_coupon,
        free_product_id: free_product_id,
        use_with_promos: use_with_promos,
        product_Name: product_Name,
        product_free: product_free,
      },
      success: function (data) {
        if (data == 1) {
          $(saveBtn).removeAttr("disabled");
          $(saveBtn).html('<div class="checkmark"></div>').addClass("is—pos");
          location.reload();
        } else {
          $(saveBtn).text(initialText).removeClass("is--pos");
          $(saveBtn).removeAttr("disabled");
        }
      },
      error: function (jXHR, textStatus, errorThrown) {
        $(saveBtn).text(initialText).removeClass("is--pos");
        $(saveBtn).removeAttr("disabled");
        alert(errorThrown);
      },
    });
    return false;
  });
});
$(document).ready(function () {
  $(".order_cancel").click(function () {
    var order_id = $(this).data("order_id");
    $("#order_id").val(order_id);
  });

  $(".closeRequestOrder").click(function () {
    var return_id = $(this).data("return_id");
    $(".return_id").val(return_id);
  });

  $(".processRefundOrder").click(function () {
    var return_id = $(this).data("return_id");
    $(".return_id").val(return_id);
  });
});
//order items returns
$(document).ready(function () {
  $(".order-returns").click(function () {
    var order_id = $(this).data("id");
    $.ajax({
      type: "POST",
      url: base_url + "get-orders-items",
      data: { order_id: order_id },
      success: function (data) {
        var data = JSON.parse(data);
        var img_url = base_url;
        var order_items = "";
        for (var i = 0; i < data.order_items.length; i++) {
          order_items += "<tr><td class='padding--xs no--pad-lr'>";
          order_items +=
            "<div class='product product--s row multi--vendor req--license'>";
          order_items += "<div class='product__image col col--2-of-8 col--am'>";
          if (data.order_items[i].images != null) {
            order_items +=
              "<div class='product__thumb' style='background-image:url(" +
              img_url +
              "uploads/products/images/" +
              data.order_items[i].images.photo +
              "');'></div></div>";
          } else {
            order_items +=
              "<div class='product__thumb' style='background-image:url('');'></div></div>";
          }
          order_items += "<div class='product__data col col--6-of-8 col--am'>";
          order_items +=
            "<span class='product__name'>" +
            data.order_items[i].products.name +
            "</span>";
          order_items +=
            "<span class='product__mfr'>by <a class='link fontWeight--2' href=''>" +
            data.order_items[i].products.manufacturer +
            "</a></span>";
          order_items +=
            "<span class='fontSize--s fontWeight--2'>$" +
            data.order_items[i].pricing.price +
            "</span></div></div></td>";
          order_items +=
            "<td class='padding--s no--pad-tb'><input type='number' class='input input--qty not--empty' min='1' value=" +
            data.order_items[i].quantity +
            "></td></tr>";
        }
        $(".orders").html(order_items);
        $(".order_id").val(order_id);
      },
    });
  });

  $(".returns").click(function () {
    $.ajax({
      type: "POST",
      url: base_url + "return-orders",
      data: $("#formReturns2").serialize(),
      success: function (data) {
        var data = JSON.parse(data);
        var locations =
          data.location.nickname +
          "<br>" +
          data.location.city +
          "," +
          data.location.state +
          "," +
          data.location.zip;
        $(".locations").html(locations);
        $(".phone").html(data.vendors.phone);
        $(".fax").html(data.vendors.fax);
        $(".return_id").html(data.return_id);
      },
    });
  });
});
//reorder
$(document).ready(function () {
  $(".re-order").click(function () {
    var order_id = $(this).data("id");
    $.ajax({
      type: "POST",
      url: base_url + "get-orders",
      data: { order_id: order_id },
      success: function (data) {
        var data = JSON.parse(data);
        var payment_info = "";
        var shipping_price = 0;
        if (data.shipping != null) {
          shipping_price = Number(data.shipping.shipping_price);
        } else {
          shipping_price = 0;
        }
        var orderitems = "";
        var new_tax = 0;
        var new_price = 0;
        var licence = "";
        var subtotal = 0;
        var img_url = base_url;
        for (var i = 0; i < data.order_items.length; i++) {
          var vendor = data.order_items[i].vendors.name;
          sub = Number(data.order_items[i].total);
          subtotal = subtotal + sub;
          var p_tax = Number(data.tax);
          new_tax = new_tax + p_tax;
          var p_price = Number(data.order_items[i].pricing.price);
          var qty = Number(data.order_items[i].quantity);
          var p_rate = p_price * qty;
          new_price = new_price + p_rate;
          var old_total = new_price + new_tax + shipping_price;
          orderitems += "<tr><td class='padding--xs no--pad-lr'>";
          if (data.order_items[i].products.license_required == "Yes") {
            orderitems +=
              "<div class='product product--s row multi--vendor req--license'>";
          } else {
            orderitems += "<div class='product product--s row multi--vendor'>";
          }
          if (data.order_items[i].images != null) {
            orderitems +=
              "<div class='product__image col col--2-of-8 col--am'><div class='product__thumb' style='background-image:url(\"" +
              img_url +
              "uploads/products/images/" +
              data.order_items[i].images.photo +
              "')>";
          } else {
            orderitems +=
              "<div class='product__image col col--2-of-8 col--am'><div class='product__thumb' style='background-image:url('" +
              img_url +
              "uploads/products/images/');'>";
          }
          orderitems +=
            "</div></div> <div class='product__data col col--6-of-8 col--am'><span class='product__name'>" +
            data.order_items[i].products.name +
            "</span><span class='product__mfr'>by <a class='link fontWeight--2' href='#'>" +
            data.order_items[i].products.manufacturer +
            "</a></span><span class='fontSize--s fontWeight--2'>$" +
            data.order_items[i].pricing.price +
            "</span><span class='fontSize--s'>(" +
            data.order_items[i].vendors.name +
            ") </span></div></div></td>";
          orderitems +=
            "<td class='padding--s no--pad-tb'><input type='number' name='quantity[]' class='input input--qty  quantity_update reorder_qty' min='0' value='" +
            data.order_items[i].quantity +
            "' id=quantity" +
            i +
            ">";
          orderitems +=
            "<input type='hidden' name='product_id[]' class='reorder_product_id' value=" +
            data.order_items[i].product_id +
            "><input type='hidden' name='product_price[]' class='pro_price' value=" +
            data.order_items[i].pricing.price +
            " id='product_price'" +
            i +
            "></td>";
          orderitems += "<tr>";
        }
        for (var i = 0; i < data.shippings.length; i++) {
          $(".shipping").append(
            '<option value="' +
              data.shippings[i].id +
              '" ' +
              (data.shippings[i].id == data.shipping.id ? "selected" : "") +
              ">" +
              data.shippings[i].shipping_type +
              "</option>"
          );
        }
        var locations = "";
        if (data.locations.address1 != null) {
          locations += data.locations.address1;
        }
        if (data.locations.address2) {
          locations += "," + data.locations.address2;
        }
        if (data.locations.state) {
          locations += "," + data.locations.state;
        }
        var payment_options = "";
        var other_payments = "";
        var payment_id = "";
        var payment_token = "";
        if (data.payment != null) {
          payment_id = data.payment.id;
          payment_token = data.payment.token;
          if (data.payment.payment_type == "card") {
            var card_number = data.payment.cc_number;
            var inn = card_number.substr(0, 2);
            var card_num = card_number.slice(-4);
            var card_type = data.payment.card_type;
            if (card_type == "Visa") {
              payment_options +=
                "<svg class='icon icon--cc icon--visa'></svg>" +
                card_type +
                " •••• " +
                card_num +
                "</p></label></li>";
            } else if (card_type == "MasterCard") {
              payment_options +=
                "<svg class='icon icon--cc icon--mastercard'></svg>" +
                card_type +
                " •••• " +
                card_num +
                "</p></label></li>";
            } else if (card_type == "Discover") {
              payment_options +=
                "<svg class='icon icon--cc icon--discover'></svg>" +
                card_type +
                " •••• " +
                card_num +
                "</p></label></li>";
            } else if (card_type == "American Express") {
              payment_options +=
                "<svg class='icon icon--cc icon--amex'></svg>" +
                card_type +
                " •••• " +
                card_num +
                "</p></label></li>";
            } else {
              payment_options +=
                "<svg class='icon icon--cc icon--undefined'></svg> •••• " +
                card_num +
                "</p></label></li>";
            }
          } else if (data.payment.payment_type == "bank") {
            var account_number = data.payment.ba_account_number;
            account_number = account_number.substr(-4);
            payment_options +=
              " <svg class='icon icon--cc icon--bank'></svg>" +
              data.payment.bank_name +
              " •••• " +
              account_number +
              "</p></label></li>";
          }
        } else {
          payment_id = "0";
          payment_token = "0";
          payment_options +=
            "<svg class='icon icon--cc icon--undefined'></svg>Update payment details</p></label></li>";
        }
        if (data.payments !== null) {
          for (var i = 0; i < data.payments.length; i++) {
            other_payments +=
              "<label class='control control__radio'><input type='radio' class='reorder_payments' name='paymentMethod' " +
              (data.payments[i].id == payment_id ? "checked" : "") +
              " value='" +
              data.payments[i].id +
              "'><div class='control__indicator'></div><p class='no--margin textColor--darkest-gray'></p></label>";
            if (data.payments[i].payment_type == "card") {
              var card_number = data.payments[i].cc_number;
              var card_type = data.payments[i].card_type;
              var inn = card_number.substr(0, 2);
              var card_num = card_number.slice(-4);
              if (card_type == "Visa") {
                other_payments +=
                  "<svg class='icon icon--cc icon--visa'></svg>" +
                  card_type +
                  "•••• " +
                  card_number +
                  "</p></label></li>";
              } else if (card_type == "MasterCard") {
                other_payments +=
                  "<svg class='icon icon--cc icon--mastercard'></svg>" +
                  card_type +
                  " •••• " +
                  card_number +
                  "</p></label></li>";
              } else if (card_type == "Discover") {
                other_payments +=
                  "<svg class='icon icon--cc icon--discover'></svg>" +
                  card_type +
                  " •••• " +
                  card_number +
                  "</p></label></li>";
              } else if (card_type == "American Express") {
                other_payments +=
                  "<svg class='icon icon--cc icon--amex'></svg>" +
                  card_type +
                  " •••• " +
                  card_number +
                  "</p></label></li>";
              } else {
                other_payments +=
                  "<svg class='icon icon--cc icon--undefined'></svg>undefined •••• " +
                  card_num +
                  "</p></label></li>";
              }
            } else if (data.payments[i].payment_type == "bank") {
              var account_number = data.payments[i].ba_account_number;
              account_number = account_number.substr(-4);
              other_payments +=
                " <svg class='icon icon--cc icon--bank'></svg>" +
                data.payments[i].bank_name +
                " •••• " +
                account_number +
                "</p></label></li>";
            }
          }
        }
        var other_locations = "";
        if (data.user_locations != null) {
          for (var i = 0; i < data.user_locations.length; i++) {
            other_locations +=
              "<label class='control control__radio'><input type='radio' name='location' class='reorder_location' " +
              (data.user_locations[i].id == data.locations.id
                ? "checked"
                : "") +
              " value='" +
              data.user_locations[i].id +
              "' ><div class='control__indicator'></div><p class='no--margin textColor--darkest-gray'><span class='fontWeight-2'>";
            if (data.user_locations[i].nickname != null) {
              other_locations += data.user_locations[i].nickname;
            }
            if (data.user_locations[i].address1 != null) {
              other_locations += ", " + data.user_locations[i].address1;
            }
            if (data.user_locations[i].address2) {
              other_locations += ", " + data.user_locations[i].address2;
            }
            if (data.user_locations[i].state) {
              other_locations += ", " + data.user_locations[i].state;
            }
            if (data.user_locations[i].zip) {
              other_locations += ", " + data.user_locations[i].zip;
            }
            other_locations += "</p></label></li>";
            other_locations += "</span></p></label></li>";
          }
        }
        var product_tax_value = 0;
        if (data.tax_rate != null) {
          product_tax_value = data.tax_rate.EstimatedCombinedRate;
        }
        $(".new_payments").html(other_payments);
        $(".other_locations").html(other_locations);
        var order_conformation = "";
        order_conformation +=
          "<tr><input type='hidden' class='tax_percentage' value='" +
          parseFloat(Math.round(product_tax_value * 100) / 100).toFixed(2) +
          "' /><input type='hidden' value=" +
          parseFloat(Math.round(subtotal * 100) / 100).toFixed(2) +
          " name='subtotal' class='reorder_sub_total'><td style='background-color: #FAFBFC'>Subtotal</td> <td class='new_sub_totals'>" +
          parseFloat(Math.round(new_price * 100) / 100).toFixed(2) +
          "</td></tr><tr><td>Tax</td> <td class='tax product_tax_value'> " +
          parseFloat(Math.round(new_tax * 100) / 100).toFixed(2) +
          "</td></tr><tr><td>Shipping</td><td class='shipping_price'> " +
          parseFloat(Math.round(shipping_price * 100) / 100).toFixed(2) +
          "<input type='hidden' value=" +
          parseFloat(Math.round(shipping_price * 100) / 100).toFixed(2) +
          " name='new_shipping_price' class='shipping_price'></td></tr><tr><td> <b>Total </b></td><td class='overall fontSize--m fontWeight--2'><input type='hidden' value=" +
          parseFloat(Math.round(old_total * 100) / 100).toFixed(2) +
          " name='new_total'> " +
          parseFloat(Math.round(old_total * 100) / 100).toFixed(2) +
          " </td></tr>";
        var single_value = "";
        single_value +=
          "<tr><input type='hidden' value=" +
          data.orders.location_id +
          " name='location_id'></tr><tr><input type='hidden' value=" +
          data.orders.vendor_id +
          " name='vendor_id'><input type='hidden' value=" +
          payment_id +
          " name='payment_id'><input type='hidden' value=" +
          data.shipping.id +
          " name='shipping_id'></tr>";
        $(".order_details").html(orderitems);
        var reorder_location = "";
        var nickname = "";
        if (data.locations.nickname != null) {
          nickname = data.locations.nickname;
        }
        if (data.locations.address1 != null) {
          reorder_location += ", " + data.locations.address1;
        }
        if (data.locations.address2) {
          reorder_location += ", " + data.locations.address2;
        }
        if (data.locations.state) {
          reorder_location += ", " + data.locations.state;
        }
        if (data.locations.zip) {
          reorder_location += " " + data.locations.zip;
        }
        var current_subtotal = parseFloat(
          Math.round(subtotal * 100) / 100
        ).toFixed(2);
        var parts = current_subtotal.split(".");
        var part1 = Number(parts[0]).toLocaleString("en");
        var part2 = parts[1];
        current_subtotal = part1 + "." + part2;
        var sub = parseFloat(Math.round(subtotal * 100) / 100).toFixed(2);
        $(".nickname").html(nickname);
        $(".location_name").html(reorder_location);
        $(".location_data").text(locations);
        $(".licenseErroMesage").html("");
        $(".payments").html(payment_options);
        $(".shipping_id").text(data.shipping.id);
        $(".vendor_name").html(vendor);
        $(".order_conformation").html(order_conformation);
        $("#single_value").html(single_value);
        $(".new_sub_total").html(current_subtotal);
        $(".reorder_sub_total").val(sub);
        $(".subtotalValue").val(
          parseFloat(Math.round(subtotal * 100) / 100).toFixed(2)
        );
        $(".shiping_date").html(data.shipping.delivery_time);
        $(".reorder_shipping_price").val(data.shipping.shipping_price);
        $(".shipping_prices").html(data.shipping.shipping_price);
        $(".shipping_type").html(data.shipping.shipping_type);
        $(".taxes").val(data.tax);
        $(".product_tax_value").html(product_tax_value);
      },
    });
  });

  $(".reorders").click(function () {
    var saveBtn = $(this),
      initialText = $(this).text();
    $(saveBtn).attr("disabled", "disabled");
    $(saveBtn).html('<div class="loader loader--light"></div>');
    var dataString = $(
      "#formReorder1, #formReorder2, #formReorder3"
    ).serialize();
    $.ajax({
      type: "POST",
      url: base_url + "reorder",
      data: dataString,
      success: function (data) {
        $(saveBtn).removeAttr("disabled");
        $(saveBtn).html('<div class="checkmark"></div>').addClass("is—pos");
        location.reload();
      },
      error: function (jXHR, textStatus, errorThrown) {
        $(saveBtn).text(initialText).removeClass("is--pos");
        $(saveBtn).removeAttr("disabled");
        alert(errorThrown);
      },
    });
  });
  $(document).on("click", ".add-reorderLocation", function () {
    var nickname = $("#locationNickname").val();
    var address1 = $("#locationAddress1").val();
    var address2 = $("#locationAddress2").val();
    var zip = $("#locationZip").val();
    var state = $("#state").val();
    $.ajax({
      type: "POST",
      url: base_url + "add-reorder-location",
      data: {
        nickname: nickname,
        address1: address1,
        address2: address2,
        zip: zip,
        state: state,
      },
      success: function (data) {
        $("#condNewLocation").hide();
        $("#other_locations").hide();
        $("#new_locations").show();
        var data = JSON.parse(data);
        var other_locations = "";
        if (data.user_locations != null) {
          for (var i = 0; i < data.user_locations.length; i++) {
            other_locations +=
              "<label class='control control__radio'><input type='radio' name='location' class='reorder_location'" +
              (data.user_locations[i].id == data.insert_id ? "checked" : "") +
              " value='" +
              data.user_locations[i].id +
              "' ><div class='control__indicator'></div><p class='no--margin textColor--darkest-gray'><span class='fontWeight-2'>";
            if (data.user_locations[i].nickname != null) {
              other_locations += data.user_locations[i].nickname;
            }
            if (data.user_locations[i].address1 != null) {
              other_locations += ", " + data.user_locations[i].address1;
            }
            if (data.user_locations[i].address2) {
              other_locations += ", " + data.user_locations[i].address2;
            }
            if (data.user_locations[i].state) {
              other_locations += ", " + data.user_locations[i].state;
            }
            if (data.user_locations[i].zip) {
              other_locations += ", " + data.user_locations[i].zip;
            }
            other_locations += "</p></label></li></span></p></label></li>";
          }
        }
        $(".new_locations").html(other_locations);
      },
    });
  });
});
$(document).on("change", ".quantity_update", function () {
  //in reorders quantity update
  var sub_total = 0;
  var tax = 0;
  $(".quantity_update").each(function (i, val) {
    var quantity = $(val).val();
    var product_price1 = $(this).parent().find(".pro_price").val();
    var product_price = Number(product_price1).toFixed(2);
    var single_total1 = product_price * quantity;
    var single_total = single_total1.toFixed(2);
    sub_total = sub_total + Number(single_total);
  });
  $(".erroMesage").html("");
  var current_subtotal = parseFloat(Math.round(sub_total * 100) / 100).toFixed(
    2
  );
  var sub = parseFloat(Math.round(sub_total * 100) / 100).toFixed(2);
  var parts = current_subtotal.split(".");
  var part1 = Number(parts[0]).toLocaleString("en");
  var part2 = parts[1];
  current_subtotal = part1 + "." + part2;
  $(".new_sub_total").html(current_subtotal);
  $(".reorder_sub_total").val(sub);
  tax = Number($(".tax_percentage").val()) * Number(sub_total);
  var s_price = Number($(".shipping_price").html());
  var overall = Number(sub_total) + Number(tax) + Number(s_price);
  var final_subtotal = parseFloat(Math.round(sub_total * 100) / 100).toFixed(2);
  var final = final_subtotal.split(".");
  var final1 = Number(final[0]).toLocaleString("en");
  var final2 = final[1];
  final_subtotal = final1 + "." + final2;
  $(".product_tax_value").html(tax.toFixed(2));
  $(".tax_value").val(Math.round(tax * 100) / 100);
  $(".overall").html(
    "<input type='hidden' value='" +
      parseFloat(Math.round(overall * 100) / 100).toFixed(2) +
      "' name='new_total' />" +
      final_subtotal
  );
});

$(document).on("change", ".shipping_rate", function () {
  var s_type = $(this).val();
  $.ajax({
    type: "POST",
    url: base_url + "get-shipping-price",
    data: { s_type: s_type },
    success: function (data) {
      var data = JSON.parse(data);
      $(".shipping_price").text(data);
      $(".reorder_shipping_price").val(data);
    },
  });
});
$(document).ready(function () {
  $(document).on("keyup", ".free_product_idSearch", function () {
    var search = $(this).val();
    if (search != "") {
      $.ajax({
        type: "POST",
        url: "search-promoProduct-myVendor",
        dataType: "json",
        data: { search: search },
        success: function (data) {
          if (data != null) {
            $(".free_productpricing_id").val(data.id);
            $(".product_Name").val(data.name);
          }
        },
      });
    } else {
      $(".product_Name").val("");
    }
  });
});

$(document).ready(function () {
  $(document).on("click", ".ReportByDay", function () {
    var reportBy = $(this).find(".Reportstatus").first().val();
    var startDate = $("#startDate").val();
    var endDate = $("#endDate").val();
    $.ajax({
      type: "POST",
      url: "order-reportsBy-DateDay",
      data: { reportBy: reportBy, startDate: startDate, endDate: endDate },
      success: function (data) {
        $(".reportData").html(data);
      },
    });
  });

  $(".alert-success")
    .fadeTo(2000, 500)
    .slideUp(500, function () {
      $(".alert-success").slideUp(500);
    });

  $(".alert-error")
    .fadeTo(2000, 500)
    .slideUp(500, function () {
      $(".alert-error").slideUp(500);
    });
});
$(document).on("click", ".close--vendor", function () {
  $(".vendor_id").val($(this).data("vendor_id"));
  $(".location_id").val($(this).data("l_id"));
  $(".cart_items").html($(this).attr("data-count_items"));
  $order_count = 0;
  $order_total = 0.0;
  $(".subtotal").each(function () {
    $order_total += parseFloat($(this).text());
    $order_count += 1;
  });
  $("#orderQty").html($order_count);
  $(".total").text(parseFloat(Math.round($order_total * 100) / 100).toFixed(2));
  console.log($(this).attr("data-logo"));
  if ($(this).attr("data-logo") != "") {
    $(".vendor_logo_remove_items").attr(
      "src",
      base_url + "uploads/vendor/logo/" + $(this).attr("data-logo")
    );
  }
  return false;
});

$(document).on("click", ".print_view_order", function () {
  window.print();
  return;
  var date = new Date();
  var mywindow = window.open("", "PRINT", "height=500,width=800");
  mywindow.document.write("<!DOCTYPE html>");
  mywindow.document.write(
    '<html style="max-width: 800px; min-width: 800px"><head><title>' +
      document.title +
      "</title>"
  );
  mywindow.document.write(
    '<link href="' +
      base_url +
      'assets/css/print.css" rel="stylesheet" type="text/css">'
  );
  mywindow.document.write(
    '<link href="' +
      base_url +
      'lib/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" type="text/css">'
  );
  mywindow.document.write(
    '<link href="' +
      base_url +
      'lib/bootstrap-timepicker/bootstrap-timepicker.css" rel="stylesheet" type="text/css">'
  );
  mywindow.document.write(
    '<link href="' +
      base_url +
      'lib/animate-css/animate.css" rel="stylesheet" type="text/css">'
  );
  mywindow.document.write(
    '<link href="' +
      base_url +
      'lib/jquery-flexslider2/flexslider.css" rel="stylesheet" type="text/css">'
  );
  mywindow.document.write(
    '</head><body style="max-width: 800px; min-width: 800px">'
  );

  $(".invoice").append(
    "<div class='invoice_print' style='display:none;'>" +
      $(".invoice").html() +
      "</div>"
  );

  $(".invoice_print")
    .find("div")
    .each(function () {
      if ($(this).css("background-image") != "none") {
        $(this).removeClass("product__thumb");
        $(this).prepend(
          '<img style="max-width: 110px"  src="' +
            $(this)
              .css("background-image")
              .replace(/"/g, "")
              .replace(/url\(|\)$/gi, "") +
            '">'
        );
        $(this).css("background-image", "..");
      }
    });
  var order_content = $(".invoice_print").html();
  mywindow.document.write('<div class="overlay__wrapper">');
  mywindow.document.write(
    '<section class="content__wrapper has--sidebar-l bg--lightest-gray">'
  );
  mywindow.document.write(
    '<div class="content__main" style="max-width: 800px; width: 100%; ">'
  );
  mywindow.document.write('<div class="row row--full-height">');
  mywindow.document.write('<div class="content col col--12-of-12">');
  mywindow.document.write('<div class="invoice">');
  mywindow.document.write(order_content);
  mywindow.document.write("</div>");
  mywindow.document.write("</div>");
  mywindow.document.write("</div>");
  mywindow.document.write("</div>");
  mywindow.document.write("</section>");
  mywindow.document.write("</div>");
  mywindow.document.write("</body></html>");
  //mywindow.document.close(); // necessary for IE >= 10
  mywindow.focus(); // necessary for IE >= 10*/
  setTimeout(function () {
    mywindow.print();
  }, 2000);
  //mywindow.close();
  return true;
});

$(document).on("click", ".print_account_snapshot_report", function () {
  var date = new Date();
  var mywindow = window.open("", "PRINT", "height=500,width=800");
  mywindow.document.write("<!DOCTYPE html>");
  mywindow.document.write(
    '<html style="max-width: 800px; min-width: 800px"><head><title>' +
      document.title +
      "</title>"
  );
  mywindow.document.write(
    '<link href="' +
      base_url +
      'assets/css/print.css" rel="stylesheet" type="text/css">'
  );
  mywindow.document.write(
    '<link href="' +
      base_url +
      'lib/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" type="text/css">'
  );
  mywindow.document.write(
    '<link href="' +
      base_url +
      'lib/bootstrap-timepicker/bootstrap-timepicker.css" rel="stylesheet" type="text/css">'
  );
  mywindow.document.write(
    '<link href="' +
      base_url +
      'lib/animate-css/animate.css" rel="stylesheet" type="text/css">'
  );
  mywindow.document.write(
    '<link href="' +
      base_url +
      'lib/jquery-flexslider2/flexslider.css" rel="stylesheet" type="text/css">'
  );
  mywindow.document.write(
    '</head><body style="max-width: 800px; min-width: 800px">'
  );

  $(".account_report").append(
    "<div class='account_report_print' style='display:none;'>" +
      $(".account_report").html() +
      "</div>"
  );

  var canvas_images = [];
  $(".account_report")
    .find("canvas")
    .each(function () {
      canvas_images.push('<img src="' + this.toDataURL() + '">');
    });
  var i = 0;
  $(".account_report_print")
    .find("canvas")
    .each(function () {
      $(this).parent().append(canvas_images[i]);
      $(this).remove();
      i += 1;
    });

  var order_content = $(".account_report_print").html();
  mywindow.document.write('<div class="overlay__wrapper">');
  mywindow.document.write(
    '<section class="content__wrapper has--sidebar-l bg--lightest-gray">'
  );
  mywindow.document.write(
    '<div class="content__main" style="max-width: 800px; width: 100%; ">'
  );
  mywindow.document.write('<div class="row row--full-height">');
  mywindow.document.write(
    '<div id="reportsContent" class="content col col--12-of-12 is--account snapshot" style="padding: 0px 5px 0px 20px;">'
  );
  mywindow.document.write(
    '<div class="content col col--12-of-12" style="padding: 0px;">'
  );
  mywindow.document.write('<div id="reportAccount" class="page__tab">');
  mywindow.document.write('<div class="report account_report">');
  mywindow.document.write(order_content);
  mywindow.document.write("</div>");
  mywindow.document.write("</div>");
  mywindow.document.write("</div>");
  mywindow.document.write("</div>");
  mywindow.document.write("</div>");
  mywindow.document.write("</div>");
  mywindow.document.write("</section>");
  mywindow.document.write("</div>");
  mywindow.document.write("</body></html>");
  //mywindow.document.close(); // necessary for IE >= 10
  mywindow.focus(); // necessary for IE >= 10*/
  setTimeout(function () {
    mywindow.print();
  }, 3000);
  //mywindow.close();
  return true;
});

$(document).on("click", ".print_orders_report", function () {
  var date = new Date();
  var mywindow = window.open("", "PRINT", "height=500,width=800");
  mywindow.document.write("<!DOCTYPE html>");
  mywindow.document.write(
    '<html style="max-width: 800px; min-width: 800px"><head><title>' +
      document.title +
      "</title>"
  );
  mywindow.document.write(
    '<link href="' +
      base_url +
      'assets/css/print.css" rel="stylesheet" type="text/css">'
  );
  mywindow.document.write(
    '<link href="' +
      base_url +
      'lib/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" type="text/css">'
  );
  mywindow.document.write(
    '<link href="' +
      base_url +
      'lib/bootstrap-timepicker/bootstrap-timepicker.css" rel="stylesheet" type="text/css">'
  );
  mywindow.document.write(
    '<link href="' +
      base_url +
      'lib/animate-css/animate.css" rel="stylesheet" type="text/css">'
  );
  mywindow.document.write(
    '<link href="' +
      base_url +
      'lib/jquery-flexslider2/flexslider.css" rel="stylesheet" type="text/css">'
  );
  mywindow.document.write(
    '</head><body style="max-width: 800px; min-width: 800px">'
  );

  var order_content = $(".order_report").html();
  mywindow.document.write('<div class="overlay__wrapper">');
  mywindow.document.write(
    '<section class="content__wrapper has--sidebar-l bg--lightest-gray">'
  );
  mywindow.document.write(
    '<div class="content__main" style="max-width: 800px; width: 100%; ">'
  );
  mywindow.document.write('<div class="row row--full-height">');
  mywindow.document.write(
    '<div id="reportsContent" class="content col col--12-of-12 is--account snapshot" style="padding: 0px 5px 0px 20px;">'
  );
  mywindow.document.write(
    '<div class="content col col--12-of-12" style="padding: 0px;">'
  );
  mywindow.document.write('<div id="reportAccount" class="page__tab">');
  mywindow.document.write('<div class="report account_report">');
  mywindow.document.write(order_content);
  mywindow.document.write("</div>");
  mywindow.document.write("</div>");
  mywindow.document.write("</div>");
  mywindow.document.write("</div>");
  mywindow.document.write("</div>");
  mywindow.document.write("</div>");
  mywindow.document.write("</section>");
  mywindow.document.write("</div>");
  mywindow.document.write("</body></html>");
  //mywindow.document.close(); // necessary for IE >= 10
  mywindow.focus(); // necessary for IE >= 10*/
  setTimeout(function () {
    mywindow.print();
  }, 3000);

  //mywindow.close();

  return true;
});

$(document).on("click", ".print_product_report", function () {
  var date = new Date();
  var mywindow = window.open("", "PRINT", "height=500,width=800");
  mywindow.document.write("<!DOCTYPE html>");
  mywindow.document.write(
    '<html style="max-width: 800px; min-width: 800px"><head><title>' +
      document.title +
      "</title>"
  );
  mywindow.document.write(
    '<link href="' +
      base_url +
      'assets/css/print.css" rel="stylesheet" type="text/css">'
  );
  mywindow.document.write(
    '<link href="' +
      base_url +
      'lib/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" type="text/css">'
  );
  mywindow.document.write(
    '<link href="' +
      base_url +
      'lib/bootstrap-timepicker/bootstrap-timepicker.css" rel="stylesheet" type="text/css">'
  );
  mywindow.document.write(
    '<link href="' +
      base_url +
      'lib/animate-css/animate.css" rel="stylesheet" type="text/css">'
  );
  mywindow.document.write(
    '<link href="' +
      base_url +
      'lib/jquery-flexslider2/flexslider.css" rel="stylesheet" type="text/css">'
  );
  mywindow.document.write(
    '</head><body style="max-width: 800px; min-width: 800px">'
  );

  var order_content = $(".product_report").html();
  mywindow.document.write('<div class="overlay__wrapper">');
  mywindow.document.write(
    '<section class="content__wrapper has--sidebar-l bg--lightest-gray">'
  );
  mywindow.document.write(
    '<div class="content__main" style="max-width: 800px; width: 100%; ">'
  );
  mywindow.document.write('<div class="row row--full-height">');
  mywindow.document.write(
    '<div id="reportsContent" class="content col col--12-of-12 is--account snapshot" style="padding: 0px 5px 0px 20px;">'
  );
  mywindow.document.write(
    '<div class="content col col--12-of-12" style="padding: 0px;">'
  );
  mywindow.document.write('<div id="reportAccount" class="page__tab">');
  mywindow.document.write('<div class="report account_report">');
  mywindow.document.write(order_content);
  mywindow.document.write("</div>");
  mywindow.document.write("</div>");
  mywindow.document.write("</div>");
  mywindow.document.write("</div>");
  mywindow.document.write("</div>");
  mywindow.document.write("</div>");
  mywindow.document.write("</section>");
  mywindow.document.write("</div>");
  mywindow.document.write("</body></html>");
  //mywindow.document.close(); // necessary for IE >= 10
  mywindow.focus(); // necessary for IE >= 10*/

  setTimeout(function () {
    mywindow.print();
  }, 3000);

  //mywindow.close();

  return true;
});

$(document).on("click", ".print_tax_report", function () {
  var date = new Date();
  var mywindow = window.open("", "PRINT", "height=500,width=800");
  mywindow.document.write("<!DOCTYPE html>");
  mywindow.document.write(
    '<html style="max-width: 800px; min-width: 800px"><head><title>' +
      document.title +
      "</title>"
  );
  mywindow.document.write(
    '<link href="' +
      base_url +
      'assets/css/print.css" rel="stylesheet" type="text/css">'
  );
  mywindow.document.write(
    '<link href="' +
      base_url +
      'lib/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" type="text/css">'
  );
  mywindow.document.write(
    '<link href="' +
      base_url +
      'lib/bootstrap-timepicker/bootstrap-timepicker.css" rel="stylesheet" type="text/css">'
  );
  mywindow.document.write(
    '<link href="' +
      base_url +
      'lib/animate-css/animate.css" rel="stylesheet" type="text/css">'
  );
  mywindow.document.write(
    '<link href="' +
      base_url +
      'lib/jquery-flexslider2/flexslider.css" rel="stylesheet" type="text/css">'
  );
  mywindow.document.write(
    '</head><body style="max-width: 800px; min-width: 800px">'
  );

  var order_content = $(".tax_report").html();
  mywindow.document.write('<div class="overlay__wrapper">');
  mywindow.document.write(
    '<section class="content__wrapper has--sidebar-l bg--lightest-gray">'
  );
  mywindow.document.write(
    '<div class="content__main" style="max-width: 800px; width: 100%; ">'
  );
  mywindow.document.write('<div class="row row--full-height">');
  mywindow.document.write(
    '<div id="reportsContent" class="content col col--12-of-12 is--account snapshot" style="padding: 0px 5px 0px 20px;">'
  );
  mywindow.document.write(
    '<div class="content col col--12-of-12" style="padding: 0px;">'
  );
  mywindow.document.write('<div id="reportAccount" class="page__tab">');
  mywindow.document.write('<div class="report account_report">');
  mywindow.document.write(order_content);
  mywindow.document.write("</div>");
  mywindow.document.write("</div>");
  mywindow.document.write("</div>");
  mywindow.document.write("</div>");
  mywindow.document.write("</div>");
  mywindow.document.write("</div>");
  mywindow.document.write("</section>");
  mywindow.document.write("</div>");
  mywindow.document.write("</body></html>");

  //mywindow.document.close(); // necessary for IE >= 10
  mywindow.focus(); // necessary for IE >= 10*/

  setTimeout(function () {
    mywindow.print();
  }, 3000);

  //mywindow.close();

  return true;
});

$(document).on("click", ".print_vendor_report", function () {
  var mywindow = window.open("", "PRINT", "height=500,width=800");
  mywindow.document.write("<!DOCTYPE html>");
  mywindow.document.write(
    '<html style="max-width: 800px; min-width: 800px"><head><title>' +
      document.title +
      "</title>"
  );
  mywindow.document.write(
    '<link href="' +
      base_url +
      'assets/css/print.css" rel="stylesheet" type="text/css">'
  );
  mywindow.document.write(
    '<link href="' +
      base_url +
      'lib/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" type="text/css">'
  );
  mywindow.document.write(
    '<link href="' +
      base_url +
      'lib/bootstrap-timepicker/bootstrap-timepicker.css" rel="stylesheet" type="text/css">'
  );
  mywindow.document.write(
    '<link href="' +
      base_url +
      'lib/animate-css/animate.css" rel="stylesheet" type="text/css">'
  );
  mywindow.document.write(
    '<link href="' +
      base_url +
      'lib/jquery-flexslider2/flexslider.css" rel="stylesheet" type="text/css">'
  );
  mywindow.document.write(
    '</head><body style="max-width: 800px; min-width: 800px">'
  );

  $(".reportData").append(
    "<div class='reportData_print' style='display:none;'>" +
      $(".reportData").html() +
      "</div>"
  );

  var canvas_images = [];
  $(".reportData")
    .find("canvas")
    .each(function () {
      canvas_images.push('<img src="' + this.toDataURL() + '">');
    });

  var i = 0;
  $(".reportData_print")
    .find("canvas")
    .each(function () {
      $(this).parent().append(canvas_images[i]);
      $(this).remove();
      i += 1;
    });

  var order_content = $(".reportData_print").html();

  mywindow.document.write('<div class="overlay__wrapper">');
  mywindow.document.write(
    '<section class="content__wrapper has--sidebar-l bg--lightest-gray">'
  );
  mywindow.document.write(
    '<div class="content__main" style="max-width: 800px; width: 100%; ">'
  );
  mywindow.document.write('<div class="row row--full-height">');
  mywindow.document.write(
    '<div class="content col col--12-of-12" style="padding: 0px 5px 0px 20px;">'
  );
  mywindow.document.write('<div class="reportData">');
  mywindow.document.write(order_content);
  mywindow.document.write("</div>");
  mywindow.document.write("</div>");
  mywindow.document.write("</div>");
  mywindow.document.write("</div>");
  mywindow.document.write("</section>");
  mywindow.document.write("</div>");
  mywindow.document.write("</body></html>");

  //mywindow.document.close(); // necessary for IE >= 10
  mywindow.focus(); // necessary for IE >= 10*/

  setTimeout(function () {
    mywindow.print();
  }, 3000);

  //mywindow.close();

  return true;
});

$(document).on("click", ".print_inventory", function () {
  var date = new Date();
  var mywindow = window.open("", "PRINT", "height=500,width=800");
  mywindow.document.write("<!DOCTYPE html>");
  mywindow.document.write(
    '<html style="max-width: 800px; min-width: 800px"><head><title>' +
      document.title +
      "</title>"
  );
  mywindow.document.write(
    '<link href="' +
      base_url +
      'assets/css/print.css" rel="stylesheet" type="text/css">'
  );
  mywindow.document.write(
    '<link href="' +
      base_url +
      'lib/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" type="text/css">'
  );
  mywindow.document.write(
    '<link href="' +
      base_url +
      'lib/bootstrap-timepicker/bootstrap-timepicker.css" rel="stylesheet" type="text/css">'
  );
  mywindow.document.write(
    '<link href="' +
      base_url +
      'lib/animate-css/animate.css" rel="stylesheet" type="text/css">'
  );
  mywindow.document.write(
    '<link href="' +
      base_url +
      'lib/jquery-flexslider2/flexslider.css" rel="stylesheet" type="text/css">'
  );
  mywindow.document.write(
    '</head><body style="max-width: 800px; min-width: 800px">'
  );

  $content = $(".inventory_table");

  $("#locationContent").append(
    "<div style='display:none;'><table class='table inventory_table_print' data-controls='#controlsInventory'>" +
      $(".inventory_table").html() +
      "</table></div>"
  );
  $(".inventory_table_print")
    .find("div")
    .each(function () {
      if ($(this).css("background-image") != "none") {
        $(this).removeClass("product__thumb");
        $(this).prepend(
          '<img style="max-width: 75px"  src="' +
            $(this)
              .css("background-image")
              .replace(/"/g, "")
              .replace(/url\(|\)$/gi, "") +
            '">'
        );
        $(this).css("background-image", "..");
      }
    });

  $(".inventory_table_print")
    .find("th:nth-child(5)")
    .each(function () {
      $(this).remove();
    });

  $(".inventory_table_print")
    .find("th:nth-child(1)")
    .each(function () {
      $(this).remove();
    });

  $(".inventory_table_print")
    .find("td:nth-child(5)")
    .each(function () {
      $(this).remove();
    });

  $(".inventory_table_print")
    .find("td:nth-child(1)")
    .each(function () {
      $(this).remove();
    });

  var order_content = $(".inventory_table_print").html();

  mywindow.document.write('<div class="overlay__wrapper">');
  mywindow.document.write(
    '<section class="content__wrapper has--sidebar-l bg--lightest-gray">'
  );
  mywindow.document.write(
    '<div class="content__main" style="max-width: 800px; width: 100%; ">'
  );
  mywindow.document.write('<div class="row row--full-height">');
  mywindow.document.write(
    '<div id="locationContent" class="content col col--8-of-12 col--push-1-of-12">'
  );
  mywindow.document.write(
    '<table class="table inventory_table" data-controls="#controlsInventory">'
  );
  mywindow.document.write(order_content);
  mywindow.document.write("</table>");
  mywindow.document.write("</div>");
  mywindow.document.write("</div>");
  mywindow.document.write("</div>");
  mywindow.document.write("</section>");
  mywindow.document.write("</div>");
  mywindow.document.write("</body></html>");

  //mywindow.document.close(); // necessary for IE >= 10
  mywindow.focus(); // necessary for IE >= 10*/

  setTimeout(function () {
    mywindow.print();
  }, 2000);

  //mywindow.close();

  return true;
});
$(document).ready(function () {
  $(document).on("click", ".add-reorderpayment", function () {
    //create new payment when reordering
    if ($("input[name=paymentType]:checked").val() == 1) {
      var expiry = $("#paymentExpiry").val().split("/");
      console.log("#paymentCardName".val());
      Stripe.card.createToken(
        {
          name: $("#paymentCardName").val(),
          number: $("#paymentCardNum").val(),
          cvc: $("#paymentSecurity").val(),
          exp_month: expiry[0],
          exp_year: expiry[1],
        },
        stripereorderCardResponseHandler
      );
    } else {
      Stripe.bankAccount.createToken(
        {
          country: "US",
          currency: "USD",
          routing_number: $("#paymentRoutingNum").val(),
          account_number: $("#paymentAccountNum").val(),
          account_holder_name: $("#accountholderName").val(),
          account_holder_type: $("#accountholderType").val(),
        },
        stripereorderBankResponseHandler
      );
    }
    return false;
  });
  function stripereorderCardResponseHandler(status, response) {
    if (response.error) {
      // Problem!
      //   // Show the errors on the form
      $(".payment-errors").html(response.error.message);
    } else {
      // Token was created!
      //   // Get the token ID:
      $.ajax({
        type: "POST",
        url: base_url + "add-card-details",
        data: { token: response.id },
        success: function (data) {
          $("#condNewPaymentMethod").hide();
          $("#new_payments").hide();
          $("#new_reoderpayments").show();
          var data = JSON.parse(data);

          var other_payments = "";
          if (data.payments !== null) {
            for (var i = 0; i < data.payments.length; i++) {
              other_payments +=
                "<label class='control control__radio'><input type='radio' class='reorder_payments' name='paymentMethod' " +
                (data.payments[i].id == data.insert_id ? "checked" : "") +
                " value='" +
                data.payments[i].id +
                "'><div class='control__indicator'></div><p class='no--margin textColor--darkest-gray'></p></label>";
              if (data.payments[i].payment_type == "card") {
                var card_number = data.payments[i].cc_number;
                var card_type = data.payments[i].card_type;
                var inn = card_number.substr(0, 2);
                var card_num = card_number.slice(-4);
                if (card_type == "Visa") {
                  other_payments +=
                    "<svg class='icon icon--cc icon--visa'></svg>" +
                    card_type +
                    "•••• " +
                    card_number +
                    "</p></label></li>";
                } else if (card_type == "MasterCard") {
                  other_payments +=
                    "<svg class='icon icon--cc icon--mastercard'></svg>" +
                    card_type +
                    " •••• " +
                    card_number +
                    "</p></label></li>";
                } else if (card_type == "Discover") {
                  other_payments +=
                    "<svg class='icon icon--cc icon--discover'></svg>" +
                    card_type +
                    " •••• " +
                    card_number +
                    "</p></label></li>";
                } else if (card_type == "American Express") {
                  other_payments +=
                    "<svg class='icon icon--cc icon--amex'></svg>" +
                    card_type +
                    " •••• " +
                    card_number +
                    "</p></label></li>";
                } else {
                  other_payments +=
                    "<svg class='icon icon--cc icon--undefined'></svg>undefined •••• " +
                    card_num +
                    "</p></label></li>";
                }
              } else if (data.payments[i].payment_type == "bank") {
                var account_number = data.payments[i].ba_account_number;
                account_number = account_number.substr(-4);
                other_payments +=
                  " <svg class='icon icon--cc icon--bank'></svg>" +
                  data.payments[i].bank_name +
                  " •••• " +
                  account_number +
                  "</p></label></li>";
              }
            }
          }
          $(".new_reoderpayments").html(other_payments);
        },
      });
    }
  }
  function stripereorderBankResponseHandler(status, response) {
    if (response.error) {
      // Problem!
      $(".payment-errors").html(response.error.message);
    } else {
      // Token created!

      var linkHandler = Plaid.create({
        env: Config.plaid.env,
        clientName: "Dentomatix",
        key: Config.plaid.public_key,
        product: "auth",
        selectAccount: true,
        onSuccess: function (public_token, metadata) {
          // Send the public_token and account ID to your app server.
          console.log("public_token: " + public_token);
          console.log("account ID: " + metadata.account_id);
          $.ajax({
            type: "POST",
            url: base_url + "add-bank-details",
            data: {
              token: response.id,
              account_id: metadata.account_id,
              public_token: public_token,
            },
            success: function (data) {
              $("#paymentMethods").hide();
              $("#new_payments").hide();
              $("#new_reoderpayments").show();
              var data = JSON.parse(data);
              var other_payments = "";
              if (data.payments !== null) {
                for (var i = 0; i < data.payments.length; i++) {
                  other_payments +=
                    "<label class='control control__radio'><input type='radio' name='paymentMethod' " +
                    (data.payments[i].id == data.insert_id ? "checked" : "") +
                    " value='" +
                    data.payments[i].id +
                    "'><div class='control__indicator'></div><p class='no--margin textColor--darkest-gray'></p></label>";
                  if (data.payments[i].payment_type == "card") {
                    var card_number = data.payments[i].cc_number;
                    var card_type = data.payments[i].card_type;
                    var inn = card_number.substr(0, 2);
                    var card_num = card_number.slice(-4);
                    if (card_type == "Visa") {
                      other_payments +=
                        "<svg class='icon icon--cc icon--visa'></svg>" +
                        card_type +
                        "•••• " +
                        card_number +
                        "</p></label></li>";
                    } else if (card_type == "MasterCard") {
                      other_payments +=
                        "<svg class='icon icon--cc icon--mastercard'></svg>" +
                        card_type +
                        " •••• " +
                        card_number +
                        "</p></label></li>";
                    } else if (card_type == "Discover") {
                      other_payments +=
                        "<svg class='icon icon--cc icon--discover'></svg>" +
                        card_type +
                        " •••• " +
                        card_number +
                        "</p></label></li>";
                    } else if (card_type == "American Express") {
                      other_payments +=
                        "<svg class='icon icon--cc icon--amex'></svg>" +
                        card_type +
                        " •••• " +
                        card_number +
                        "</p></label></li>";
                    } else {
                      other_payments +=
                        "<svg class='icon icon--cc icon--undefined'></svg>undefined •••• " +
                        card_num +
                        "</p></label></li>";
                    }
                  } else if (data.payments[i].payment_type == "bank") {
                    var account_number = data.payments[i].ba_account_number;
                    account_number = account_number.substr(-4);
                    other_payments +=
                      " <svg class='icon icon--cc icon--bank'></svg>" +
                      data.payments[i].bank_name +
                      " •••• " +
                      account_number +
                      "</p></label></li>";
                  }
                }
              }
              $(".new_reoderpayments").html(other_payments);
            },
          });
        },
      });
      linkHandler.open();
    }
  }
});
$(document).ready(function () {
  $(document).on("click", ".reorder-clear", function () {
    $(".shipping").val("");
  });

  $(document).on("click", ".stepone", function () {
    var subtotalValue = $(".reorder_sub_total").val();
    if (subtotalValue > 0) {
      var saveBtn = $(this),
        target = $(this).data("target"),
        nextStep = $(this).data("next");
      $("html, body").animate({ scrollTop: 0 }, 600);
      saveBtn.parents(".form__group").toggle(600);
      $(nextStep).toggle(600);
      $("#methodCreditCard").removeAttr("style");
      $("#methodBank").removeAttr("style");
      return false;
    } else {
      var error = "Unable to process this Order ,Quantity can't be empty";
      $(".erroMesage").html(error);
    }
    return false;
  });
  $(document).on("click", ".steptwo", function () {
    var dataString = $("#formReorder1").serialize();
    var reorderlocation_id = $("input[name='location']:checked").val();
    var payment = $("input[name='paymentMethod']:checked").val();
    var shipping_id = $(".shipping").val();
    var saveBtn = $(this),
      target = $(this).data("target"),
      nextStep = $(this).data("next");
    if (payment == undefined) {
      var message = "Please Select payment Mode";
      $(".licenseErroMesage").html(message);
    } else {
      $.ajax({
        type: "POST",
        url: base_url + "reorders-products-check",
        data:
          $("#formReorder1").serialize() +
          "&reorderlocation_id=" +
          reorderlocation_id +
          "&payment=" +
          payment +
          "&shipping_id=" +
          shipping_id,
        success: function (data) {
          var data = JSON.parse(data);
          var locations = "";
          var payment_options = "";
          if (data.error != "") {
            $(".licenseErroMesage").html(data.error);
            return false;
          } else {
            $("html, body").animate({ scrollTop: 0 }, 600);
            saveBtn.parents(".form__group").toggle(600);
            $(nextStep).toggle(600);
            var tax = 0;
            var tax_rate = 0;
            var shipping_changes = "";
            if (data.shippings != null) {
              shipping_changes += data.shippings.delivery_time;
              shipping_changes += " ($" + data.shippings.shipping_price + ")";
            }
            $(".changed_shiping_date").html(shipping_changes);
            $(".changedshipping_type").html(data.shippings.shipping_type);
            $(".chanedlocation_name").html(data.locations.nickname);
            if (data.locations.address1 != null) {
              locations += data.locations.address1 + "<br>";
            }
            if (data.locations.address2) {
              locations += data.locations.address2 + ", ";
            }
            if (data.locations.state) {
              locations += data.locations.state;
            }
            if (data.locations.zip) {
              locations += " " + data.locations.zip;
            }
            $(".changedlocation_data").html(locations);
            if (data.payment != null) {
              payment_id = data.payment.id;
              payment_token = data.payment.token;
              if (data.payment.payment_type == "card") {
                var card_number = data.payment.cc_number;
                var inn = card_number.substr(0, 2);
                var card_num = card_number.slice(-4);
                var card_type = data.payment.card_type;
                if (card_type == "Visa") {
                  payment_options +=
                    "<svg class='icon icon--cc icon--visa'></svg>" +
                    card_type +
                    " •••• " +
                    card_num +
                    "</p></label></li>";
                } else if (card_type == "MasterCard") {
                  payment_options +=
                    "<svg class='icon icon--cc icon--mastercard'></svg>" +
                    card_type +
                    " •••• " +
                    card_num +
                    "</p></label></li>";
                } else if (card_type == "Discover") {
                  payment_options +=
                    "<svg class='icon icon--cc icon--discover'></svg>" +
                    card_type +
                    " •••• " +
                    card_num +
                    "</p></label></li>";
                } else if (card_type == "American Express") {
                  payment_options +=
                    "<svg class='icon icon--cc icon--amex'></svg>" +
                    card_type +
                    " •••• " +
                    card_num +
                    "</p></label></li>";
                } else {
                  payment_options +=
                    "<svg class='icon icon--cc icon--undefined'></svg>Undefined •••• " +
                    card_num +
                    "</p></label></li>";
                }
              } else if (data.payment.payment_type == "bank") {
                var account_number = data.payment.ba_account_number;
                account_number = account_number.substr(-4);
                payment_options +=
                  " <svg class='icon icon--cc icon--bank'></svg>" +
                  data.payment.bank_name +
                  " •••• " +
                  account_number +
                  "</p></label></li>";
              }
              $(".payment_token").val(data.payment.token);
            }
            $("#old_payments").hide();
            $("#changed_payments").show();
            $(".changed_payments").html(payment_options);
            if (data.reorder_tax != null) {
              tax_rate = data.reorder_tax.EstimatedCombinedRate;
            }
            $(".tax_percentage").val(tax_rate);
            $(".shipping_price").html(data.shippings.shipping_price);
            var sub_total = $(".reorder_sub_total").val();
            tax = Number($(".tax_percentage").val()) * Number(sub_total);
            var s_price = Number($(".shipping_price").html());
            var overall = Number(sub_total) + Number(tax) + Number(s_price);
            var final_subtotal = parseFloat(
              Math.round(sub_total * 100) / 100
            ).toFixed(2);
            var final = final_subtotal.split(".");
            var final1 = Number(final[0]).toLocaleString("en");
            var final2 = final[1];
            final_subtotal = final1 + "." + final2;
            $(".product_tax_value").html(tax.toFixed(2));
            $(".tax_value").val(Math.round(tax * 100) / 100);
            var current_subtotal = parseFloat(
              Math.round(sub_total * 100) / 100
            ).toFixed(2);
            var parts = current_subtotal.split(".");
            var part1 = Number(parts[0]).toLocaleString("en");
            var part2 = parts[1];
            current_subtotal = part1 + "." + part2;
            var product_subtotal = Number(
              $(".reorder_sub_total").val()
            ).toLocaleString("en");
            $(".new_sub_totals").html("$" + current_subtotal);
            var product_tax = Number(
              $(".product_tax_value").html()
            ).toLocaleString("en");
            $(".product_tax_value").html("$" + product_tax);
            $(".shipping_price").html("$" + $(".shipping_price").html());
            $(".changed_shipping_prices").html(
              "$" + $(".changed_shipping_prices").html()
            );
            var final_total = parseFloat(
              Math.round(overall * 100) / 100
            ).toFixed(2);
            var parts = final_total.split(".");
            var part1 = Number(parts[0]).toLocaleString("en");
            var part2 = parts[1];
            final_total = part1 + "." + part2;
            $(".overall").html("$" + final_total);
            $(".final_reorder").val(overall);
            $(".tax_value").val(Math.round(tax * 100) / 100);
            $(".final").val(Math.round(overall * 100) / 100);
            return false;
          }
        },
      });
    }
    return false;
  });
  $(document).on("click", ".reorder_location", function () {
    var reorderlocation_id = $("input[name='location']:checked").val();
    $.ajax({
      type: "POST",
      url: base_url + "get-location",
      data: { reorderlocation_id: reorderlocation_id },
      success: function (data) {
        var data = JSON.parse(data);
        var selected_location = "";
        var nickname = "";
        if (data.location.nickname != null) {
          nickname = data.location.nickname;
        }
        if (data.location.address1 != null) {
          selected_location += ", " + data.location.address1;
        }
        if (data.location.address2) {
          selected_location += ", " + data.location.address2;
        }
        if (data.location.state) {
          selected_location += ", " + data.location.state;
        }
        if (data.location.zip) {
          selected_location += " " + data.location.zip;
        }
        $(".nickname").html(nickname);
        $(".location_name").html(selected_location);
      },
    });
  });

  $(document).on("click", ".reorder_payments", function () {
    var payment_id = $("input[name='paymentMethod']:checked").val();
    $.ajax({
      type: "POST",
      url: base_url + "get-paymetns",
      data: { payment_id: payment_id },
      success: function (data) {
        var data = JSON.parse(data);
        var payment_options = "";
        var payment_id = "";
        var payment_token = "";
        if (data.payment != null) {
          payment_id = data.payment.id;
          payment_token = data.payment.token;
          if (data.payment.payment_type == "card") {
            var card_number = data.payment.cc_number;
            var inn = card_number.substr(0, 2);
            var card_num = card_number.slice(-4);
            var card_type = data.payment.card_type;
            if (card_type == "Visa") {
              payment_options +=
                "<svg class='icon icon--cc icon--visa'></svg>" +
                card_type +
                " •••• " +
                card_num +
                "</p></label></li>";
            } else if (card_type == "MasterCard") {
              payment_options +=
                "<svg class='icon icon--cc icon--mastercard'></svg>" +
                card_type +
                " •••• " +
                card_num +
                "</p></label></li>";
            } else if (card_type == "Discover") {
              payment_options +=
                "<svg class='icon icon--cc icon--discover'></svg>" +
                card_type +
                " •••• " +
                card_num +
                "</p></label></li>";
            } else if (card_type == "American Express") {
              payment_options +=
                "<svg class='icon icon--cc icon--amex'></svg>" +
                card_type +
                " •••• " +
                card_num +
                "</p></label></li>";
            } else {
              payment_options +=
                "<svg class='icon icon--cc icon--undefined'></svg> •••• " +
                card_num +
                "</p></label></li>";
            }
          } else if (data.payment.payment_type == "bank") {
            var account_number = data.payment.ba_account_number;
            account_number = account_number.substr(-4);
            payment_options +=
              " <svg class='icon icon--cc icon--bank'></svg>" +
              data.payment.bank_name +
              " •••• " +
              account_number +
              "</p></label></li>";
          }
        } else {
          payment_id = "0";
          payment_token = "0";
          payment_options +=
            "<svg class='icon icon--cc icon--undefined'></svg>Update payment details</p></label></li>";
        }
        $(".payments").html(payment_options);
      },
    });
  });
  $(".test_class").click(function () {
    $(".location_error").html("");
  });
  $(document).on("click", ".add-shopping-list", function () {
    //create new shopping lists
    var locations_id = $("#shoppinglist--locations").val();
    if (!locations_id) {
      $(".location_error").html(
        "List cann't be create,Select atleast One Location.."
      );
    } else {
      $.ajax({
        type: "POST",
        url: base_url + "add-new-shopinglist",
        data: $("#newListForm").serialize(),
        success: function (data) {
          location.reload();
        },
      });
    }
  });
  $(document).on("click", ".save-list", function () {
    //create new shopping lists with products
    var locations_id = $("#shoppinglist--locations").val();
    if (!locations_id) {
      $(".location_error").html(
        "List cann't be create,Select atleast One Location.."
      );
    } else {
      $.ajax({
        type: "POST",
        url: base_url + "new_shoppinglist",
        data: $("#newListonlyForm").serialize(),
        success: function (data) {
          location.reload();
        },
      });
    }
  });
});

setTimeout(function () {
  $(".input:-webkit-autofill").each(function () {
    var elem = $(this);
    $(elem).addClass("not--empty");
  });
}, 100);

//Hide elements on page load
$(window).load(function () {
  setTimeout(function () {
    $("#pageLoad").fadeOut();
  }, 100);
});
$(document).on("keyup", ".UserchangeWithin", function () {
  var organization_id = $(this).attr("data-organization_id");
  var emailId = $(this).val();
  if (emailId === "") {
    $("#product_list").html("");
  } else {
    $.ajax({
      type: "POST",
      url: "organization-user-emailId",
      dataType: "json",
      data: { emailId: emailId, organization_id: organization_id },
      success: function (data) {
        if (data.length > 0) {
          var htmlString = "";
          htmlString += "<ul>";
          for (var i = 0; i < data.length; i++) {
            htmlString +=
              '<li style="padding: 5px; border-bottom: 1px solid #aaa;"><a class="select_organization_user" href="javascript:void(0)" data-user_id="' +
              data[i].id +
              '">' +
              data[i].email +
              "</a></li>";
          }
          htmlString += "</ul>";
          $("#product_list").html(htmlString);
          $("#product_list").show();
        } else {
          $("#product_list").html("No email found");
          $("#product_list").show();
        }
      },
    });
  }
});
$(document).on("click", ".select_organization_user", function () {
  $(".UserchangeWithin").val($(this).text());
  var user_id = $(this).attr("data-user_id");
  $("#OrganizationUser_id").val(user_id);
  $("#product_list").html("");
});

// // Category loaded through query string on page load
// $(function(){

//     category = $.urlParam('category');
//     if(window.localStorage.getItem('loaded')){
//         return;
//     }
//     $.getJSON(base_url + "category-parents?category=" + category, function (parentData) {
//         parent_category = parentData[0].category_id;

//         allCategories = parentData;

//         $current_div = $("label[parent="+ parent_category +"]");

//         $.getJSON(base_url + "category-list?parent=" + parent_category, function (data) {
//             var htmlString = '';

//             for (var i = 0; i < data.length; i++) {
//                 htmlString += '<li class="item item--parent refresh--content is--expanded subchild">';
//                 htmlString += '<a class="link fontWeight--2" href="javascript:;" catid="' + data[i].id + '">' + data[i].name + '</a>';
//                 htmlString += '<ul class="list level_2" style="display:none;"></ul>';
//                 htmlString += '</li>';

//                 if (data[i].separator_after == "1") {
//                     htmlString += '<li class="item item--parent refresh--content is--expanded subchild cat-separator">';
//                     htmlString += '<a href="javascript:;">------------------</a>';
//                     htmlString += '</li>';

//                 }
//             }
//             $current_div.parent().next().next('ul.list--tree').html(htmlString).show();

//         });
//         $(".category_clear").show();

//         var item = $('.list--tree .item .link'),
//             parent = $('.list--tree .refresh--content');

//         var $current_item = '';
//         page = "";
//         for (var j = 1; j < allCategories.length; j++) {
//             (function(x){
//                 $.getJSON(base_url + "category-list?parent=" + allCategories[x].category_id, function (data) {
//                     console.log(data);
//                     if (data.length > 0) {
//                         var htmlString = '';
//                         for (var i = 0; i < data.length; i++) {
//                             htmlString += '<li class="item">';
//                             htmlString += '<a class="link" catid="' + data[i].id + '">' + data[i].name + '</a>';
//                             htmlString += '<ul class="list level_3" style="display:none;"></ul>'
//                             htmlString += '</li>';
//                         }
//                         htmlString += '';
//                         $current_item = $(".link[catid='"+ allCategories[x].category_id +"']");

//                         $current_item.next().html(htmlString).show();
//                     }
//                 })
//                     .done(function(){

//                         $(item).removeClass('is--active');
//                         $current_item.addClass('is--active');
//                         $(parent).removeClass('is--expanded');
//                         $current_item.closest(parent).addClass('is--expanded');

//                         $current_item.parent().siblings('li').hide();

//                         $('input[name="categoryView"]').prop('checked', false);

//                     });
//             })(j);
//         }
//     });
// });

$(document).ready(function () {
  var q = $.urlParam("q") !== undefined ? $.urlParam("q") : "";
  q = q.replace(/\+/g, "%20");
  $('input[name="q"]').val(decodeURIComponent(q));

  var search = $.urlParam("search") !== undefined ? $.urlParam("search") : "";
  search = search.replace(/\+/g, "%20");
  $('input[name="search"]').val(decodeURIComponent(search));
});

// Get parameter from query string
$.urlParam = function (name, url) {
  if (!url) {
    url = window.location.href;
  }
  var results = new RegExp("[\\?&]" + name + "=([^&#]*)").exec(url);
  if (!results) {
    return undefined;
  }
  return results[1] || undefined;
};

// license editing
$(document).ready(function () {
  $(".edit-license").on("click", function () {
    var licenseId = $(this).data("license-id");
    var $holder = $(this).closest(".license__card");
    // convert date
    var expiry_date, exp;
    if ((expiry_date = $holder.find(".expire_date").text())) {
      var d = new Date(expiry_date);
      exp = d.getMonth() + 1 + "/" + d.getDate() + "/" + d.getFullYear();
      // $("#licenseExpiry").datepicker({
      //     format: 'dd/mm/yyyy',
      //     autoclose: true
      // }).datepicker("update", exp);
    } else {
      var d = new Date();
      exp = null;
      // $("#licenseExpiry").datepicker({
      //     format: 'dd/mm/yyyy',
      //     autoclose: true
      // }).datepicker("update", d);
    }
    $("#licenseExpiry")
      .datepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
      })
      .datepicker("update", exp);
    // set values
    $modal = $("#editLicenseModal");
    $modal.find("#license_id").val($(this).data("license-id"));
    $modal
      .find("#accountLicense")
      .val($holder.find(".license_no").text())
      .removeClass("not--empty");
    if ($holder.find(".license_no").text() != "") {
      $modal.find("#accountLicense").addClass("not--empty");
    }
    $modal
      .find("#accountDEA")
      .val($holder.find(".dea_no").text())
      .removeClass("not--empty");
    if ($holder.find(".dea_no").text() != "") {
      $modal.find("#accountDEA").addClass("not--empty");
    }
    $modal
      .find("#licenseExpiry")
      .val(exp)
      .trigger("click")
      .removeClass("not--empty");
    if (exp != null) {
      $modal.find("#licenseExpiry").addClass("not--empty");
    }
    $modal.find("#licenseState").val("default");
    if ($holder.find(".state").text() != "") {
      $modal.find("#licenseState").val($holder.find(".state").text());
    }
  });

  $(".email_urgent_request").on("click", function () {
    $.ajax({
      type: "POST",
      url: "email-request-urgent",
      dataType: "json",
      data: { locationId: $(this).data("id") },
      success: function (data) {
        console.log("done");
        $("span.success").html(
          '<div class="banner is--pos"><span class="banner__text">Request list mailed successfully!</span></div>'
        );
      },
    });
  });

  $(".rl-item, #selectAll").on("change", function () {
    var selectedIds = [];
    $(".rl-item").each(function () {
      if ($(this).prop("checked") == true) {
        selectedIds.push($(this).val());
      }
    });
    if (selectedIds.length > 0) {
      $(".selected_list").removeClass("is--off");
    } else {
      $(".selected_list").addClass("is--off");
    }
  });
});

function selectCategory(currentItem) {
  console.log(currentItem);
  page = 0;
  currentItem = $(currentItem);
  // console.log($current_item)
  if (typeof currentItem.attr("catid") != "undefined") {
    category = currentItem.attr("catid");
  } else {
    category = currentItem.attr("parent");
  }
  search = "";
  refresh_products();
  // loadCatChildren(currentItem);
  rebuildCatNav(category);
}

function selectCategoryheader($current_item) {
  // console.log($current_item);
  $current_item = $($current_item);
  // console.log($current_item)
  if (typeof $current_item.attr("catid") != "undefined") {
    category = $current_item.attr("catid");
  } else {
    category = $current_item.attr("parent");
  }

  rebuildCatNav(category);
}

function rebuildCatNav(category) {
  refresh_products();
  $.getJSON("/rebuild-cat-nav?category=" + category, function (data) {
    $(".list--tree").html(data.menu);
    console.log(data);
    var width = screen.width;
    if (width < 450) {
      $(".cat-rowul").html(data.menu);
      $(".cat-row").removeClass("d-none");
      $(".cat-row").slideDown("slow");
    }
    return;
  });
}

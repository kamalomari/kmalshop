$(document).ready(function() {
    'use strict';
    // Js niceScroll
    $("html").niceScroll({
        cursorcolor:"#dc143c",
        cursorwidth:"10px",
        background:"rgba(20,20,20,0.3)",
        cursorborder:"1px solid #dc143c",
        cursorborderradius:50
    });
    // Show Color Option Div When Click On The Gear
    $(".gear-check").click(function () {

        $(".color-option").fadeToggle();

    });
    // go to map as way smooth
    $(".mapClick").click(function () {
        $("html, body").animate({
            scrollTop : $("#map").offset().top
        }, 2000);
    });
    /*================================*/
    /*  Switch Between Login & Signup */
    $(".login-page h1 span").click(function () {

        $(this).addClass("selected").siblings().removeClass("selected");

        $(".login-page form").hide();

        $("." + $(this).data('class')).fadeIn(100);

        if ($(this).data('class') === 'singup'){
            $(this).css("color", "#dc143c");
        }else{
            $(".optClr").css("color", " rgba(101, 81, 81, 0.48)");
        }


    });

    /*================================*/
    /* Hide Placeholder On Form Focus */
    var placeH=$("[placeholder]");
    placeH.focus(function () {
        $(this).attr("data-text",$(this).attr("placeholder"));
        $(this).attr("placeholder","");
    });
    placeH.blur(function () {
        $(this).attr("placeholder",$(this).attr("data-text"));
    });
    /*================================*/
    /* Add Asterisk On Required Field */

    $('input').each(function () {

        if ($(this).attr('required') === 'required') {

            $(this).after('<span class="asterisk">*</span>');

        }

    });
    /*================================*/
    /* Confirmation Message On Button */

    $('.confirm').click(function () {

        return confirm('Are You Sure?');

    });

    //** Convert Password Field To Text Field On Hover **//

    var passField = $('.password');

    $('.show-pass').hover(function () {

        passField.attr('type', 'text');
        $('.show-pass').css({opacity: 1});

    }, function () {

        passField.attr('type', 'password');
        $('.show-pass').css({opacity: 0.7});

    });
    /*===========================*/
    /* Write Keyup automatic form*/
    $('.live').keyup(function () {

        $($(this).data('class')).text($(this).val());

    });

    /*=================*/
    /* Start Footer JS*/
    // Caching The Scroll Top Element
    // console.log($(document).height());
    var scrollButton = $("#scroll-top");
    $(window).scroll(function () {

        if ($(this).scrollTop() >= 700) {

            scrollButton.show();

        } else {

            scrollButton.hide();
        }
    });

    // Click On Button To Scroll Top

    scrollButton.click(function () {

        $("html,body").animate({ scrollTop : 0 }, 600);

    });

    var iframe = $('.main-content iframe')[0],
        menu_links = $('.items li a'),
        selected_link,
        href;


    $(window).on('hashchange', function() {

        if(window.location.hash){
            href = window.location.hash.substring(1);
            selected_link = $('a[href$="'+href+'"]');

            // Check if the hash is valid - it should exist as one of the menu items.
            if(selected_link.length){
                iframe.contentWindow.location.replace(href + '.html');

                menu_links.removeClass('active');
                selected_link.addClass('active');
            }
        }
        else{
            iframe.contentWindow.location.replace('Footer-with-logo.html');
            menu_links.removeClass('active');
            $(menu_links[0]).addClass('active');
        }

    });


    if(window.location.hash){
        $(window).trigger('hashchange');
    }


    menu_links.on('click', function (e) {
        e.preventDefault();

        window.location.hash = $(this).attr('href');
    });


    $('#template-select').on('change', function (e) {
        e.preventDefault();

        window.location.hash = $(this).find(':selected').data('href');
    });

    /* End Footer JS*/
    /*================*/
    /*=====================*/
    /*  Start  JS  contact */
    /*=====================*/
    /*global $, alert, console */

    $(function () {

        'use strict';

        var userError   = true,

            emailError  = true,

            msgError    = true;

        $('.username').blur(function () {

            if ($(this).val().length < 4) { // Show Error

                $(this).css('border', '1px solid #F00').parent().find('.custom-alert').fadeIn(200)

                    .end().find('.asterisx').fadeIn(100);

                userError = true;

            } else { // No Errors

                $(this).css('border', '1px solid #080').parent().find('.custom-alert').fadeOut(200)

                    .end().find('.asterisx').fadeOut(100);

                userError = false;

            }

        });

        $('.email').blur(function () {

            if ($(this).val() === '') {

                $(this).css('border', '1px solid #F00').parent().find('.custom-alert').fadeIn(200)

                    .end().find('.asterisx').fadeIn(100);

                emailError = true;

            } else {

                $(this).css('border', '1px solid #080').parent().find('.custom-alert').fadeOut(200)

                    .end().find('.asterisx').fadeOut(100);

                emailError = false;

            }

        });

        $('.message').blur(function () {

            if ($(this).val().length < 10) {

                $(this).css('border', '1px solid #F00').parent().find('.custom-alert').fadeIn(200)

                    .end().find('.asterisx').fadeIn(100);

                msgError = true;

            } else {

                $(this).css('border', '1px solid #080').parent().find('.custom-alert').fadeOut(200)

                    .end().find('.asterisx').fadeOut(100);

                msgError = false;

            }

        });

        // Submit Form Validation

        $('.contact-form').submit(function (e) {

            if (userError === true || emailError === true || msgError === true) {

                e.preventDefault();

                $('.username, .email, .message').blur();

            }

        });

    });
    /*=====================*/
    /*  End  JS  contact */
    /*=====================*/
});
/*===========================*/

/*  Upload image one Item   */
  function imagepreview1(input) {
      if ( input.files && input.files[0]){
          var filerd1= new FileReader();
          filerd1.onload=function (e) {
              $("#imagepreview1").attr("src",e.target.result);
          };
          filerd1.readAsDataURL(input.files[0]);
      }

  }
/*  Upload image two Item   */
function imagepreview2(input) {
    if ( input.files && input.files[0]){
        var filerd2= new FileReader();
        filerd2.onload=function (e) {
            $("#imagepreview2").attr("src",e.target.result);
        };
        filerd2.readAsDataURL(input.files[0]);
    }

}
/* Start JS Recaptcha disabled Button */
function recaptcha_callback() {
    $('#submitBtn').removeAttr('disabled');
}
/* End JS Recaptcha disabled Button */
/* Start Function enable and disable and required service paypal */
function enable_text(status) {
    "use strict";
    status = (status) ? false : true; //convert status boolean to text 'disabled'
    var txtS = document.getElementById("textDisable");//auto disabled
    txtS.disabled = status;
    if (status === false){
        txtS.setAttribute("required", "required");// auto set required
        document.getElementById("free").disabled=false;
        document.getElementById("free").setAttribute("required", "required");
        document.getElementById("paid").disabled=false;
        document.getElementById("paid").setAttribute("required", "required");
    }
    if(status === true){
        txtS.removeAttribute("required");// auto remove required
        document.getElementById("free").disabled=true;
        document.getElementById("free").removeAttribute("required");
        document.getElementById("paid").disabled=true;
        document.getElementById("paid").removeAttribute("required");
    }
}
function disable_text(status) {
    "use strict";
    status = (status) ? false : true; //convert status boolean to text 'disabled'
    var txtS = document.getElementById("textDisable");//auto disabled
    txtS.disabled = status;
    if (status === true){
        txtS.setAttribute("required", "required");// auto set required
        document.getElementById("textDisable").disabled=false;
        document.getElementById("free").disabled=false;
        document.getElementById("free").setAttribute("required", "required");
        document.getElementById("paid").disabled=false;
        document.getElementById("paid").setAttribute("required", "required");
    }
    if(status === false){
        txtS.removeAttribute("required");// auto remove required
        document.getElementById("textDisable").disabled=true;
        document.getElementById("free").disabled=true;
        document.getElementById("free").removeAttribute("required");
        document.getElementById("paid").disabled=true;
        document.getElementById("paid").removeAttribute("required");
        document.getElementById("textDisable").value="notPaypal";
    }
}
//                              Element.prototype.hide = function() {
//                                  this.hidden=true;
//                              };
//                              Element.prototype.show = function() {
//                                  this.hidden=false;
//                              };
function showHidePaypal() {
    var dsply1 = document.getElementById("paypalService");
    var dsply2 = document.getElementById("serviceShop");
    if (dsply1.style.display === "none" && dsply2.style.display === "none") {
        dsply1.style.display = "block";
        dsply2.style.display = "block";
    } else {
        dsply1.style.display = "none";
        dsply2.style.display = "none";
    }
}
/* End Function enable and disable and required service paypal */

/* Start function for change language by cookie JS */
function chooseLang(lang){
    var expiryDate = new Date();
    expiryDate.setMonth(expiryDate.getMonth() + 1);
    var location = window.location.href;
    document.cookie = "chooseLang="+ lang +"; expires="+ expiryDate +"; path=/php/Ecommerces/My-Kamal";
}
/* End function for change language by cookie JS */


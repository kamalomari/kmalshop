$(document).ready(function() {
    'use strict';
    /*=================*/
    /* Start Dashboard*/

    $('.toggle-info').click(function () {

        $(this).toggleClass('selected').parent().next('.panel-body').fadeToggle(100);

        if ($(this).hasClass('selected')) {

            $(this).html('<i class="fa fa-minus fa-lg"></i>');

        } else {

            $(this).html('<i class="fa fa-plus fa-lg"></i>');

        }

    });
    /*=========================*/
    /* Trigger The Selectboxit*/
    $("select").selectBoxIt({

        autoWidth: false

    });
    // Hide Placeholder On Form Focus
    var placeH=$("[placeholder]");
    placeH.focus(function () {
        $(this).attr("data-text",$(this).attr("placeholder"));
        $(this).attr("placeholder","");
    });
    placeH.blur(function () {
        $(this).attr("placeholder",$(this).attr("data-text"));
    });
    // Add Asterisk On Required Field

    $('input').each(function () {

        if ($(this).attr('required') === 'required') {

            $(this).after('<span class="asterisk">*</span>');

        }

    });
    // Confirmation Message On Button

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
    // Category View Option

    $('.cat h3').click(function () {

        $(this).next('.full-view').fadeToggle(300);

    });
    $('.option span').click(function () {

        $(this).addClass('active').siblings('span').removeClass('active');

        if ($(this).data('view') === 'full') {

            $('.cat .full-view').fadeIn(200);

        } else {

            $('.cat .full-view').fadeOut(200);

        }

    });
    // Show Delete Button On Child Cats

    $('.child-link').hover(function () {

        $(this).find('.show-delete').fadeIn(400);

    }, function () {

        $(this).find('.show-delete').fadeOut(400);

    });
    /*=====================*/
    /*  Start  JS  contact */
    /*=====================*/
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
    /*=====================*/
    /*  End  JS  contact */
    /*=====================*/
});
/* Start JS Recaptcha disabled Button */
function recaptcha_callback() {
    $('#submitBtn').removeAttr('disabled');
}
/* End JS Recaptcha disabled Button */

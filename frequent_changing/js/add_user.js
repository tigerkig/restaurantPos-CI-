$(document).ready(function() {
    "use strict";
    if ($(".checkbox_user").length == $(".checkbox_user:checked").length) {
        $("#checkbox_userAll").prop("checked", true);
    } else {
        $("#checkbox_userAll").removeAttr("checked");
    }
    // Check or Uncheck All checkboxes\
    $(document).on('change', '#checkbox_userAll', function() {
        $('.user_radio').each(function(i, obj) {
            if ($(this).is(':checked')) {
                $('#checkbox_userAll').prop('checked', false);
                $('.checkbox_user').prop('checked', false);
                return false;
            }
        });
        let checked = $(this).is(':checked');
        if (checked) {
            $(".checkbox_user").each(function() {
                $(this).prop("checked", true);
            });
        } else {
            $(".checkbox_user").each(function() {
                $(this).prop("checked", false);
            });
        }
    });

    $(document).on('click', '.checkbox_user', function() {
        $('.user_radio').each(function(i, obj) {
            if ($(this).is(':checked')) {
                $('#checkbox_userAll').prop('checked', false);
                $('.checkbox_user').prop('checked', false);

                return false;
            }
        });
        if ($(".checkbox_user").length == $(".checkbox_user:checked").length) {
            $("#checkbox_userAll").prop("checked", true);
        } else {
            $("#checkbox_userAll").prop("checked", false);
        }
    });
    $(document).on('click', '.user_radio', function() {
        $('#checkbox_userAll').prop('checked', false);
        $('.checkbox_user').prop('checked', false);
    });
    $(document).on('click', '#will_login_yes', function() {
        $('#will_login_section').fadeIn();
    });
    $(document).on('click', '#will_login_no', function() {
        $('#will_login_section').fadeOut();
    });
});
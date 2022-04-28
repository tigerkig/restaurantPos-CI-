$(document).ready(function() {
    "use strict";
    if ($(".checkbox_user").length == $(".checkbox_user:checked").length) {
        $("#checkbox_userAll").prop("checked", true);
    } else {
        $("#checkbox_userAll").removeAttr("checked");
    }
    // Check or Uncheck All checkboxes
    $(document).on('change', '#checkbox_userAll', function() {
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
    $(document).on('change', '.checkbox_user', function() {
        if ($(".checkbox_user").length == $(".checkbox_user:checked").length) {
            $("#checkbox_userAll").prop("checked", true);
        } else {
            $("#checkbox_userAll").prop("checked", false);
        }
    });
});
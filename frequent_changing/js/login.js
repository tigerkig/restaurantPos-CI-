$(function () {
    "use strict";
    $(document).on('click', '.set_credentials', function(){
       let username = $(this).attr("data-username");
       let password = $(this).attr("data-password");
       $("#email_address").val(username);
       $("#password").val(password);
    });
});
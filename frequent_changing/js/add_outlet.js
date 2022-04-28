$(function () {
    "use strict";
    $(document).on('change', '#checkbox_userAll', function(){
        let checked = $(this).is(':checked');
        if(checked){
            $(".checkbox_user").each(function(){
                $(this).prop("checked",true);
            });
            $(".checkbox_user_p").prop("checked", true);
        }else{
            $(".checkbox_user").each(function(){
                $(this).prop("checked",false);
            });
            $(".checkbox_user_p").prop("checked", false);
        }
    });

    $(document).on('click', '.checkbox_user', function(){
        if($(".checkbox_user").length == $(".checkbox_user:checked").length) {
            $("#checkbox_userAll").prop("checked", true);

        } else {
            $("#checkbox_userAll").prop("checked", false);
        }
    });
});
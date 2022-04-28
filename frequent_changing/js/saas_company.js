$(function() {
    "use strict";
    $(document).on('click', '.add_payment_now', function(e) {
        e.preventDefault();
        $("#expired_date").css("border","1px solid #ced4da");
        $("#amount").css("border","1px solid #ced4da");

        let id = $(this).attr('data-id');
        let business_name = $(this).attr('data-business_name');
        if(id){
            $(".business_name_txt").html(business_name);
            $("#hidden_company_id").val(id);
        }
    });

    // Validate form
    $(document).on('submit', '#manualPayment', function() {
        let expired_date = $("#expired_date").val();
        let amount = $("#amount").val();
        let error = false;
        if(expired_date==''){
            $("#expired_date").css("border","1px solid red");
            error = true;
        }else{
            $("#expired_date").css("border","1px solid #ced4da");
        }
        if(amount==''){
            $("#amount").css("border","1px solid red");
            error = true;
        }else{
            $("#amount").css("border","1px solid #ced4da");
        }

        if (error == true) {
            return false;
        }
    });

});
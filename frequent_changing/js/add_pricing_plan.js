$(function() {
    "use strict";
    function check_payment_type() {
        let payment_type = $("#payment_type").val();
        if(payment_type==2){
            $(".show_recurring").show();
        }else{
            $(".show_recurring").hide();
        }
    }
    $(document).on('change', '#payment_type', function() {
        //check_payment_type();
    });
    //check_payment_type();
});
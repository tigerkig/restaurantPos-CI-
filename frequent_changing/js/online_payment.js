$(function() {
    "use strict";
    $(document).on('click', '.payment_now', function(e) {
        e.preventDefault();
        let payment_method = $("#payment_method").val();

        if(payment_method){
            if(payment_method==1){
                $("#paypal_form").submit();
            }else if(payment_method==2){

                $("#stripe_form").submit();
            }else if(payment_method==3){

            }
        }else{
            let warning = $("#warning").val();
            let please_select_payment_method = $("#please_select_payment_method").val();
            let ok = $("#ok").val();
            swal({
                title: warning,
                text: please_select_payment_method,
                confirmButtonText: ok,
                confirmButtonColor: '#3c8dbc'
            });
        }

    });
});
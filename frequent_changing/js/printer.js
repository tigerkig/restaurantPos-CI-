$(function () {
    "use strict";
    function set_printing_type() {
        var this_value = $("#type").val();
        if(this_value=="linux" || this_value == "windows"){
            $(".network_div1").hide();
            $(".receipt_printer_div").show();
        }else if(this_value=="network"){
            $(".receipt_printer_div").hide();
            $(".network_div1").show();
        }else{
            $(".network_div1").hide();
            $(".receipt_printer_div").hide();
        }
    }

    $(document).on('change','#type' , function(e){
        set_printing_type();

    });
    set_printing_type();
});
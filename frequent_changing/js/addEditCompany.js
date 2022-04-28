$(document).ready(function() {
    "use strict";

    $(document).on('change', '#plan_id', function() {
        let trail_day = $(this).find(':selected').attr('data-trial_day');
        if(trail_day){
            if(trail_day=="111"){
                $("#access_day").val(30);
            }else{
                $("#access_day").val(trail_day);
            }
        }else{
            $("#access_day").val('00');
        }

    });


});
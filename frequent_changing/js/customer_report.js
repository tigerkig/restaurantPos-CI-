$(function() {
    "use strict";
    $(document).on('submit', '#customerReport', function() {
        let customer_id = $("#customer_id").val();
        let error = false;
        if (customer_id == "") {
            let op1 = $("#customer_id").data("select2");
            op1.open();
            error = true;
        }

        if (error == true) {
            return false;
        }
    });
});
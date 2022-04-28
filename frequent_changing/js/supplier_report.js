$(function() {
    "use strict";
    $(document).on('submit', '#supplierReport', function() {
        let supplier_id = $("#supplier_id").val();
        let error = false;
        if (supplier_id == "") {
            let op1 = $("#supplier_id").data("select2");
            op1.open();
            error = true;
        }

        if (error == true) {
            return false;
        }
    });
});
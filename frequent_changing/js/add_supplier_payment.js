$(function() {
    "use strict";
    $(document).on('change', '#supplier_id', function() {
        let supplier_id = $('#supplier_id').val();
        let csrf_name_= $("#csrf_name_").val();
        let csrf_value_= $("#csrf_value_").val();
        $.ajax({
            type: "GET",
            url: base_url + 'SupplierPayment/getSupplierDue',
            data: {
                supplier_id: supplier_id,
                csrf_name_: csrf_value_
            },
            success: function(data) {
                $("#remaining_due").show();
                $("#remaining_due").text(data);
                $(".alert").show();
            }
        });
    });
});
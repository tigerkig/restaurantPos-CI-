$(function() {
    "use strict";
    $(document).on('change','#customer_id' , function(e){
        let customer_id = $('#customer_id').val();
        let csrf_name_= $("#csrf_name_").val();
        let csrf_value_= $("#csrf_value_").val();
        let current_due = $("#current_due").val();

        $.ajax({
            type: "GET",
            url: base_url+'Customer_due_receive/getCustomerDue',
            data: {
                customer_id: customer_id,
                csrf_name_: csrf_value_
            },
            success: function(data) {
                $("#remaining_due").show();
                $("#remaining_due").text(current_due+": " + data );
            }
        });
    });
});
$(function() {
    "use strict";
    let ingredient_already_remain = $("#ingredient_already_remain").val();
    let supplier_field_required = $("#supplier_field_required").val();
    let date_field_required = $("#date_field_required").val();
    let at_least_ingredient = $("#at_least_ingredient").val();
    let paid_field_required = $("#paid_field_required").val();
    let are_you_sure = $("#are_you_sure").val();
    let warning = $("#warning").val();
    let a_error = $("#a_error").val();
    let ok = $("#ok").val();
    let cancel = $("#cancel").val();
    let alert2 = $("#alert").val();
    let ingredient_id_container = [];
    //Initialize Select2 Elements
    $('.select2').select2();
    let suffix =0;

    let tab_index = 4;

    $(document).on('change', '#ingredient_id', function() {
        let ingredient_details = $('#ingredient_id').val();
        if (ingredient_details != '') {
            let ingredient_details_array = ingredient_details.split('|');
            $(".rowCount").each(function() {
               let id_temp = $(this).attr('data-item_id');
               if(id_temp==(ingredient_details_array[0])){
                   swal({
                       title: alert2+"!",
                       text: ingredient_already_remain,
                       confirmButtonText: ok,
                       confirmButtonColor: '#3c8dbc'
                   });
                   $('#ingredient_id').val('').change();
                   exit;
                   return false;
               }
            });
            let currency = '';

            suffix++;
            tab_index++;

            let cart_row = '<tr class="rowCount" data-item_id="' + ingredient_details_array[0] + '" data-id="' + suffix + '" id="row_' + suffix + '">' +
                '<td style="padding-left: 10px;"><p id="sl_' + suffix + '">' + suffix + '</p></td>' +
                '<td><span style="padding-bottom: 5px;">' + ingredient_details_array[1] +
                '</span></td>' +
                '<input type="hidden" id="ingredient_id_' + suffix +
                '" name="ingredient_id[]" value="' + ingredient_details_array[0] + '"/>' +
                '<td><input type="text" tabindex="' + tab_index + '" id="unit_price_' + suffix +
                '" name="unit_price[]" onfocus="this.select();" class="form-control integerchk aligning" placeholder="Unit Price"/ value="' +
                ingredient_details_array[3] +
                '" onkeyup="return calculateAll();"/><span class="label_aligning">' + currency +
                '</span></td>' +
                '<td><input type="text" data-countID="' + suffix + '" tabindex="' + tab_index + 1 +
                '" id="quantity_amount_' + suffix +
                '" name="quantity_amount[]" onfocus="this.select();" class="form-control integerchk aligning countID"  placeholder="Qty/Amount" onkeyup="return calculateAll();" ><span class="label_aligning">' +
                ingredient_details_array[2] + '</span></td>' +
                '<td><input type="text" id="total_' + suffix +
                '" name="total[]" class="form-control aligning" placeholder="Total" readonly /><span class="label_aligning">' +
                currency + '</span></td>' +
                '<td><a class="btn btn-danger btn-xs" style="margin-left: 5px; margin-top: 10px;" onclick="return deleter(' +
                suffix + ',' + ingredient_details_array[0] +
                ');" ><i style="color:white" class="fa fa-trash"></i> </a></td>' +
                '</tr>';
            tab_index++;

            $('#purchase_cart tbody').append(cart_row);

            $('#ingredient_id').val('').change();
            calculateAll();
        }
    });


    // Validate form
    $(document).on('submit', '#purchase_form', function() {
        let supplier_id = $("#supplier_id").val();
        let date = $("#date").val();
        //let note = $("#note").val();
        let paid = $("#paid").val();
        let ingredientCount = $("#purchase_cart tbody tr").length;
        let error = false;


        if (supplier_id == "") {
            $("#supplier_id_err_msg").text(supplier_field_required);
            $(".supplier_id_err_msg_contnr").show(200);

            error = true;
        }

        if (date == "") {
            $("#date_err_msg").text(date_field_required);
            $(".date_err_msg_contnr").show(200);

            error = true;
        }

        if (ingredientCount < 1) {
            $("#ingredient_id_err_msg").text(at_least_ingredient);
            $(".ingredient_id_err_msg_contnr").show(200);

            error = true;
        }

        if (paid == "") {
            $("#paid_err_msg").text(paid_field_required);
            $(".paid_err_msg_contnr").show(200);
            error = true;
        }

        $(".countID").each(function() {
            let n = $(this).attr("data-countID");
            let quantity_amount = $.trim($("#quantity_amount_" + n).val());
            if (quantity_amount == '' || isNaN(quantity_amount)) {
                $("#quantity_amount_" + n).css({
                    "border-color": "red"
                }).show(200).delay(2000, function() {
                    $("#quantity_amount_" + n).css({
                        "border-color": "#d2d6de"
                    });
                });
                error = true;
            }
        });

        if (error == true) {
            return false;
        }
    });



})

function calculateAll() {
    let subtotal = 0;
    let i = 1;
    $(".rowCount").each(function() {
        let id = $(this).attr("data-id");
        let unit_price = $("#unit_price_" + id).val();
        let temp = "#sl_" + id;
        $(temp).html(i);
        i++;
        let quantity_amount = $("#quantity_amount_" + id).val();
        if ($.trim(unit_price) == "" || $.isNumeric(unit_price) == false) {
            unit_price = 0;
        }
        if ($.trim(quantity_amount) == "" || $.isNumeric(quantity_amount) == false) {
            quantity_amount = 0;
        }

        let quantity_amount_and_unit_price = parseFloat($.trim(unit_price)) * parseFloat($.trim(
            quantity_amount));

        $("#total_" + id).val(quantity_amount_and_unit_price.toFixed(2));
        subtotal += parseFloat($.trim($("#total_" + id).val()));
    });


    if (isNaN(subtotal)) {
        subtotal = 0;
    }


    $("#subtotal").val(subtotal);

    let other = parseFloat($.trim($("#other").val()));

    if ($.trim(other) == "" || $.isNumeric(other) == false) {
        other = 0;
    }

    let grand_total = parseFloat(subtotal) + parseFloat(other);

    grand_total = grand_total.toFixed(2);

    $("#grand_total").val(grand_total);

    let paid = $("#paid").val();

    if ($.trim(paid) == "" || $.isNumeric(paid) == false) {
        paid = 0;
    }

    let due = parseFloat(grand_total) - parseFloat(paid);

    /*        if($.trim(due)==""|| $.isNumeric(due)==false){
        due=0;
    }*/

    $("#due").val(due.toFixed(2));
}

function deleter(suffix, ingredient_id) {
    let alert2 = $("#alert").val();
    let are_you_sure = $("#are_you_sure").val();
    let warning = $("#warning").val();
    let a_error = $("#a_error").val();
    let ok = $("#ok").val();
    let cancel = $("#cancel").val();
    swal({
        title: alert2,
        text: are_you_sure,
        confirmButtonColor: '#3c8dbc',
        cancelButtonText: cancel,
        confirmButtonText: ok,
        showCancelButton: true
    }, function() {
        $("#row_" + suffix).remove();
        $("#paid").val('');
        calculateAll();
    });
}

function updateRowNo() {
    let numRows = $("#purchase_cart tbody tr").length;
    for (let r = 0; r < numRows; r++) {
        $("#purchase_cart tbody tr").eq(r).find("td:first p").text(r + 1);
    }
}

$('#supplierModal').on('hidden.bs.modal', function() {
    $(this).find('form').trigger('reset');
});
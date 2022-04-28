"use strict";
let ingredient_id_container = [];
let warning = $("#warning").val();
let a_error = $("#a_error").val();
let ok = $("#ok").val();
let cancel = $("#cancel").val();
let ingredient_already_remain = $("#ingredient_already_remain").val();
let consumption_amount = $("#consumption_amount").val();
let responsible_person_field_required = $("#responsible_person_field_required").val();
let date_field_required = $("#date_field_required").val();
let at_least_ingredient = $("#at_least_ingredient").val();
let note_field_cannot = $("#note_field_cannot").val();
let select = $("#select").val();

$(function() {

    //Initialize Select2 Elements
    $('.select2').select2();

    let suffix =$("#consumption_ingredients").val();
    $(document).on('change', '#ingredient_id', function() {
        let ingredient_details = $('#ingredient_id').val();

        if (ingredient_details != '') {

            let ingredient_details_array = ingredient_details.split('|');
            let index = ingredient_id_container.indexOf(ingredient_details_array[0]);

            if (index > -1) {
                swal({
                    title: warning,
                    text: ingredient_already_remain,
                    confirmButtonText: ok,
                    confirmButtonColor: '#3c8dbc'
                });
                $('#ingredient_id').val('').change();
                return false;
            }

            let currency = $("#currency").val();

            suffix++;

            let cart_row = '<tr class="rowCount" data-id="' + suffix + '" id="row_' + suffix + '">' +
                '<td style="padding-left: 10px;"><p id="sl_' + suffix + '">' + suffix + '</p></td>' +
                '<input type="hidden" id="ingredient_id_' + suffix +
                '" name="ingredient_id[]" value="' + ingredient_details_array[0] + '"/>' +
                '<td><span style="padding-bottom: 5px;">' + ingredient_details_array[1] +
                '</span></td>' +
                '<td class=""><input type="text" data-countID="' + suffix +
                '" id="consumption_amount_' + suffix +
                '" name="consumption_amount[]" onfocus="this.select();" class="form-control integerchk aligning" placeholder="'+consumption_amount+'" onkeyup="return calculateAll();"/><span class="label_aligning"> ' +
                ingredient_details_array[2] + '</span></td>' +
                '<td><select tabindex="4" class="form-control select2" name="consumption_status[]" id="consumption_status_' +
                suffix +
                '"><option value="">'+select+'</option><option value="Plus">Plus</option><option value="Minus">Minus</option></select></td>' +
                '<td><a class="btn btn-danger btn-xs" style="margin-left: 5px; margin-top: 10px;" onclick="return deleter(' +
                suffix + ',' + ingredient_details_array[0] +
                ');" ><i class="fa fa-trash"></i> </a></td>' +
                '</tr>';

            $('#suffix_hidden_field').val(suffix);

            $('#consumption_cart tbody').append(cart_row);

            ingredient_id_container.push(ingredient_details_array[0]);

            $('#ingredient_id').val('').change();
            calculateAll();

        }
    });
    // Validate form
    $(document).on('submit', '#consumption_form', function() {
        let date = $("#date").val();
        let employee_id = $("#employee_id").val();
        let note = $("#note").val();
        let ingredientCount = $("#consumption_cart tbody tr").length;
        let error = false;

        if (employee_id == "") {
            $("#employee_id_err_msg").text(responsible_person_field_required);
            $(".employee_id_err_msg_contnr").show(200);
            error = true;
        }

        if (date == "") {
            $("#date_err_msg").text(date_field_required);
            $(".date_err_msg_contnr").show(200);
            error = true;
        }

        if (ingredientCount < 1) {
            $("#ingredient_id_err_msg").text(at_least_ingredient);
            $(".ingredient_id_err_msg_contnr").show(200)
            error = true;
        }

        if (note.length > 200) {
            $("#note_err_msg").text(note_field_cannot);
            $(".note_err_msg_contnr").show(200);
            error = true;
        }
        $(".integerchk").each(function() {
            let n = $(this).attr("data-countID");
            let consumption_amount = $.trim($("#consumption_amount_" + n).val());
            if (consumption_amount == '' || isNaN(consumption_amount)) {
                $("#consumption_amount_" + n).css({
                    "border-color": "red"
                }).show(200).delay(2000, function() {
                    $("#consumption_amount_" + n).css({
                        "border-color": "#d2d6de"
                    });
                });
                error = true;
            }

            let consumption_status = $.trim($("#consumption_status_" + n).val());
            if (consumption_status == '') {
                $("#consumption_status_" + n).css({
                    "border-color": "red"
                }).show(200).delay(2000, function() {
                    $("#consumption_status_" + n).css({
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
    let total_loss = 0;
    let i = 1;
    $(".rowCount").each(function() {
        let id = $(this).attr("data-id");
        let consumption_amount = $("#consumption_amount_" + id).val();
        let temp = "#sl_" + id;
        $(temp).html(i);
        i++;
        if (typeof(consumption_amount) !== "undefined" && consumption_amount !== null) {

            let last_purchase_price = $("#last_purchase_price_" + id).val();

            if ($.trim(consumption_amount) == "" || $.isNumeric(consumption_amount) == false) {
                consumption_amount = 0;
            }
            if ($.trim(last_purchase_price) == "" || $.isNumeric(last_purchase_price) == false) {
                last_purchase_price = 0;
            }

            let loss_amount = parseFloat($.trim(consumption_amount)) * parseFloat($.trim(last_purchase_price));

            $("#loss_amount_" + id).val(loss_amount.toFixed(2));
            total_loss += parseFloat($.trim($("#loss_amount_" + id).val()));

        }
    });

    $("#total_loss").val(total_loss);

}

function deleter(suffix, ingredient_id) {
    let are_you_sure = $("#are_you_sure").val();
    swal({
        title: warning,
        text: are_you_sure,
        confirmButtonColor: '#3c8dbc',
        cancelButtonText: cancel,
        confirmButtonText: ok,
        showCancelButton: true
    }, function() {
        $("#row_" + suffix).remove();
        let ingredient_id_container_new = [];

        for (let i = 0; i < ingredient_id_container.length; i++) {
            if (ingredient_id_container[i] != ingredient_id) {
                ingredient_id_container_new.push(ingredient_id_container[i]);
            }
        }

        ingredient_id_container = ingredient_id_container_new;

        updateRowNo();
        calculateAll();
    });
}
function updateRowNo() {
    let numRows = $("#consumption_cart tbody tr").length;
    for (let r = 0; r < numRows; r++) {
        $("#consumption_cart tbody tr").eq(r).find("td:first p").text(r + 1);
    }
}

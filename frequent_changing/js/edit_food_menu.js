"use strict";
let warning = $("#warning").val();
let a_error = $("#a_error").val();
let ok = $("#ok").val();
let cancel = $("#cancel").val();
let ingredient_already_remain = $("#ingredient_already_remain").val();
let name_field_required = $("#name_field_required").val();
let category_field_required = $("#category_field_required").val();
let veg_item_field_required = $("#veg_item_field_required").val();
let beverage_item_field_required = $("#beverage_item_field_required").val();
let bar_item_field_required = $("#bar_item_field_required").val();
let description_field_can_not_exceed = $("#description_field_can_not_exceed").val();
let sale_price_field_required = $("#sale_price_field_required").val();
let are_you_sure = $("#are_you_sure").val();
let consumption = $("#consumption").val();

$(function() {
    "use strict";
    //Initialize Select2 Elements
    $('.select2').select2();
    $(document).on('keydown', '.integerchk', function(e) {
        /*$('.integerchk').keydown(function(e) {*/
        let keys = e.charCode || e.keyCode || 0;
        // allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
        // home, end, period, and numpad decimal
        return (
            keys == 8 ||
            keys == 9 ||
            keys == 13 ||
            keys == 46 ||
            keys == 110 ||
            keys == 86 ||
            keys == 190 ||
            (keys >= 35 && keys <= 40) ||
            (keys >= 48 && keys <= 57) ||
            (keys >= 96 && keys <= 105));
    });

    let suffix =$(".rowCount").length;

    let tab_index = 6;

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

            suffix++;
            tab_index++;

            let cart_row = '<tr class="rowCount" id="row_' + suffix + '">' +
                '<td style="width: 12%; padding-left: 10px;"><p>' + suffix + '</p></td>' +
                '<td class="ir_w_23"><span style="padding-bottom: 5px;">' + ingredient_details_array[
                    1] + '</span></td>' +
                '<input type="hidden" id="ingredient_id_' + suffix +
                '" name="ingredient_id[]" value="' + ingredient_details_array[0] + '"/>' +
                '<td style="width: 30%"><input type="text" tabindex="' + tab_index +
                '" id="consumption_' + suffix +
                '" name="consumption[]" onfocus="this.select();"  class="form-control integerchk aligning" class="ir_w_85" placeholder="'+consumption+'"/><span class="label_aligning">' +
                ingredient_details_array[2] + '</span></td>' +
                '<td class="ir_w_17"><a class="btn btn-danger btn-xs" style="margin-left: 5px; margin-top: 10px;" onclick="return deleter(' +
                suffix + ',' + ingredient_details_array[0] +
                ');" ><i class="fa fa-trash"></i> </a></td>' +
                '</tr>';

            $('#ingredient_consumption_table tbody').append(cart_row);

            ingredient_id_container.push(ingredient_details_array[0]);
            /*updateRowNo();*/
            $('#ingredient_id').val('').change();
            updateRowNo();
        }
    });


    // Validate form
    $(document).on('submit', '#food_menu_form', function() {
        let name = $("#name").val();
        let category_id = $("#category_id").val();
        let veg_item = $("#veg_item").val();
        let beverage_item = $("#beverage_item").val();
        let bar_item = $("#bar_item").val();
        let description = $("#description").val();
        let sale_price = $("#sale_price").val();
        let ingredientCount = $("#form-table tbody tr").length;
        let error = false;

        if (name == "") {
            $("#name_err_msg").text(name_field_required);
            $(".name_err_msg_contnr").show(200);
            error = true;
        }
        if (category_id == "") {
            $("#category_id_err_msg").text(category_field_required);
            $(".category_err_msg_contnr").show(200);
            error = true;
        }
        if (veg_item == "") {
            $("#veg_item_err_msg").text(veg_item_field_required);
            $(".veg_item_err_msg_contnr").show(200);
            error = true;
        }
        if (beverage_item == "") {
            $("#beverage_item_err_msg").text(beverage_item_field_required);
            $(".beverage_item_err_msg_contnr").show(200);
            error = true;
        }
        if (bar_item == "") {
            $("#bar_item_err_msg").text(bar_item_field_required);
            $(".bar_item_err_msg_contnr").show(200);
            error = true;
        }

        if (description.length > 200) {
            $("#description_err_msg").text(description_field_can_not_exceed);
            $(".description_err_msg_contnr").show(200);
            error = true;
        }

        if (sale_price == "") {
            $("#sale_price_err_msg").text(sale_price_field_required);
            $(".sale_price_err_msg_contnr").show(200);
            error = true;
        }
        for (let n = 1; n <= ingredient_id_container.length + 1; n++) {
            let ingredient_id = $.trim($("#ingredient_id_" + n).val());
            let consumption = $.trim($("#consumption_" + n).val());

            if (ingredient_id.length > 0) {
                if (consumption == '' || isNaN(consumption)) {
                    $("#consumption_" + n).css({
                        "border-color": "red"
                    }).show(200);
                    error = true;
                }
            }
        }

        if (error == true) {
            return false;
        }
    });
});

function deleter(suffix, ingredient_id) {
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
    });

}

function updateRowNo() {
    let numRows = $("#ingredient_consumption_table tbody tr").length;
    for (let r = 0; r < numRows; r++) {
        $("#ingredient_consumption_table tbody tr").eq(r).find("td:first p").text(r + 1);
    }
}
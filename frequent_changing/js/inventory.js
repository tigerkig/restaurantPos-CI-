$(function() {
    "use strict";
    let base_url = $("#base_url_").val();
    let stock_value = $("#stock_value").val();
    let currency = '';
    $('#stockValue').html(stock_value+': '+currency + $(
        '#grandTotal').val() +
        '<a class="top" title="" data-placement="top" data-toggle="tooltip" style="cursor:pointer" data-original-title="Calculated based on last purchase price and Ingredient with negative Stock Qty/Amount is not considered"><i data-feather="help-circle"></i></a>'
    );
    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
    });

    $(document).on('change','#food_id' , function(e){
        let value = this.value;
        if (value) {
            $('#category_id').prop('disabled', true);
            $('#ingredient_id').prop('disabled', true);
        } else {
            $('#category_id').prop('disabled', false);
            $('#ingredient_id').prop('disabled', false);
        }
    });
    $(document).on('change','#ingredient_id' , function(e){
        let ingredient_id = this.value;
        let category_id = $('select.category_id').find(':selected').val();
        if (ingredient_id || category_id) {
            $('#food_id').prop('disabled', true);
        } else {
            $('#food_id').prop('disabled', false);
        }
    });
    $(document).on('change','#category_id' , function(e){
        let category_id = this.value;
        if (category_id) {
            $('#food_id').prop('disabled', true);
        } else {
            $('#food_id').prop('disabled', false);
        }
        let options = '';
        let csrf_name_= $("#csrf_name_").val();
        let csrf_value_= $("#csrf_value_").val();
        let ingredient= $("#ingredient").val();
        $.ajax({
            type: 'get',
            url: base_url+'Inventory/getIngredientInfoAjax',
            data: {
                category_id: category_id,
                csrf_name_: csrf_value_
            },
            datatype: 'json',
            success: function(data) {
                let json = $.parseJSON(data);
                options += '<option  value="">'+ingredient+'</option>';
                $.each(json, function(i, v) {
                    options += '<option  value="' + v.id + '">' + v.name + '(' + v
                        .code + ')</option>';
                });
                $('#ingredient_id').html(options);
            }
        });
    });
    let category_id = $('select.category_id').find(':selected').val();
    let ingredient_id = $('select.ingredient_id').find(':selected').val();
    let food_id = $('select.food_id').find(':selected').val();
    if (food_id) {
        $('#category_id').prop('disabled', false);
        $('#ingredient_id').prop('disabled', false);

    } else if (ingredient_id || category_id) {
        $('#category_id').prop('disabled', false);
        $('#ingredient_id').prop('disabled', false);
    } else {
        if (food_id) {
            $('#category_id').prop('disabled', true);
            $('#ingredient_id').prop('disabled', true);
        }

    }
    if (category_id) {
        let selectedID = $("#hiddentIngredientID").val();
        let options = '';
        let csrf_name_= $("#csrf_name_").val();
        let csrf_value_= $("#csrf_value_").val();
        let ingredient= $("#ingredient").val();
        $.ajax({
            type: 'get',
            url: base_url+'Inventory/getIngredientInfoAjax',
            data: {
                category_id: category_id,
                csrf_name_: csrf_value_
            },
            datatype: 'json',
            success: function(data) {
                let json = $.parseJSON(data);
                options += '<option  value="">'+ingredient+'</option>';
                $.each(json, function(i, v) {
                    options += '<option  value="' + v.id + '">' + v.name + '(' + v.code +
                        ')</option>';
                });
                $('#ingredient_id').html(options);
                $('#ingredient_id').val(selectedID).change();
            }
        });
    }

});
$(function() {
    "use strict";
    $(document).on('submit', '#restaurant_setting_form', function(e) {
        let error = 0;

        let outlet_name = $('#outlet_name');
        let address = $('#address');
        let phone = $('#phone');
        let collect_tax_yes = $('#collect_tax_yes');
        let collect_tax_no = $('#collect_tax_no');
        let tax_title = $('#tax_title');
        let tax_registration_no = $('#tax_registration_no');
        let tax_is_gst_yes = $('#tax_is_gst_yes');
        let tax_is_gst_no = $('#tax_is_gst_no');
        let state_code = $('#state_code');
        let pre_or_post_payment_post = $('#pre_or_post_payment_post');

        if (outlet_name.val() == "") {
            error++;
            $('#outlet_name_error').fadeIn();
        }
        if (address.val() == "") {
            error++;
            $('#address_error').fadeIn();
        }
        if (phone.val() == "") {
            error++;
            $('#phone_error').fadeIn();
        }
        if (!collect_tax_yes.is(':checked') && !collect_tax_no.is(':checked')) {
            error++;
        }
        if (collect_tax_yes.is(':checked')) {
            if (tax_title.val() == "") {
                error++;
                $('#tax_title_error').fadeIn();
            }
            if (tax_registration_no.val() == "") {
                error++;
                $('#tax_registration_no').fadeIn();
            }
            if (!tax_is_gst_yes.is(':checked') && !tax_is_gst_no.is(':checked')) {
                error++;
            }
            if (tax_is_gst_yes.is(':checked')) {
                if (state_code.val() == "") {
                    error++;
                    $('#state_code_error').fadeIn();
                }
            }
        }


        if (pre_or_post_payment_post.val() == "") {
            error++;
        }
    });
    $(document).on('click', '.remove_this_tax_row', function(e) {
        let this_row = $(this);
        let this_row_id = this_row.attr('id').substr(20);
        $('#tax_row_' + this_row_id).remove();
        let j = 1;
        $('.remove_this_tax_row').each(function(i, obj) {
            $(this).attr('id', 'remove_this_tax_row_' + j);
            $(this).parent().parent().attr('id', 'tax_row_' + j);
            $(this).parent().parent().find('td:first-child').text(j);
            j++;
        });
    });
    $(document).on('click', '#remove_all_taxes', function() {
        $('.tax_single_row').remove();
    });
     $(document).on('click', '#collect_tax_yes', function(e) {
        $('#tax_yes_section').fadeIn();
    });
     $(document).on('click', '#collect_tax_no', function(e) {
        $('#tax_yes_section').fadeOut();
    });

     $(document).on('click', '#tax_is_gst_yes', function(e) {
        $('#gst_yes_section').fadeIn();
    });
     $(document).on('click', '#tax_is_gst_no', function(e) {
        $('#gst_yes_section').fadeOut();
    });
     $(document).on('click', '#add_tax', function(e) {
        let table_tax_body = $('#tax_table_body');
        let tax_body_row_length = table_tax_body.find('tr').length;
        let new_row_number = tax_body_row_length + 1;
        let show_tax_row = '';
        show_tax_row += '<tr class="tax_single_row" id="tax_row_' + new_row_number + '">';
        show_tax_row += '<td>' + new_row_number + '</td>';
        show_tax_row += '<td><input type="text" name="taxes[]" class="form-control"/></td>';
        show_tax_row += '<td><span class="remove_this_tax_row" id="remove_this_tax_row_' +
            new_row_number + '" style="cursor:pointer;">X</span></td>';
        show_tax_row += '</tr>';

        table_tax_body.append(show_tax_row);
    });
    $('input[type=radio][name=collect_vat]').change(function() {
        if (this.value == 'Yes') {
            $('#vat_reg_no_container').show();
        } else if (this.value == 'No') {
            $('#vat_reg_no_container').hide();
        }
    });


});
"use strict";
var base_url = $("base").attr("data-base");
var collect_tax = $("base[data-collect-tax]").attr("data-collect-tax");
var currency = $("base[data-currency]").attr("data-currency");
var role = $("base[data-role]").attr("data-role");
var collect_gst = $("base[data-collect-gst]").attr("data-collect-gst");
var gst_state_code = $("base[data-gst-state-code]").attr("data-gst-state-code");
var csrf_value_ = $("#csrf_value_").val();

$(document).ready(function () {
  $("#edit_customer").on("click", function () {
    var selected_customer = $("#walk_in_customer").val();
    if (selected_customer != "") {
      get_customer_for_edit(selected_customer);
    }
  });
  $("#search_running_orders").on("keyup", function () {
    var string = $(this).val();
    $("#order_details_holder .single_order").each(function (i, obj) {
      var order_number = $(this).find(".running_order_order_number").html();
      var table_name = $(this).find(".running_order_table_name").html();
      var waiter_name = $(this).find(".running_order_waiter_name").html();
      var customer_name = $(this).find(".running_order_customer_name").html();
      if (
        order_number.includes(string) ||
        table_name.includes(string) ||
        waiter_name.includes(string) ||
        customer_name.includes(string)
      ) {
        $(this).css("display", "block");
      } else {
        $(this).css("display", "none");
      }
    });
    $("#stop_refresh_for_search").html("no");
  });
  $("#walk_in_customer").on("change", function () {
    $("#select2-walk_in_customer-container").css("border", "none");
    do_addition_of_item_and_modifiers_price();
  });
  $("#select_waiter").on("change", function () {
    $("#select2-select_waiter-container").css("border", "none");
  });
  $("#kitchen_waiter_bar_button").on("click", function () {
    $("#kitchen_bar_waiter_panel_button_modal").slideDown(333);
  });
  $("#submit_table_modal").on("click", function () {
    $("#show_tables_modal2").slideUp(333);
  });

  function checkQty(tbl_id) {}

  $(".bottom_add").on("click", function () {
    var table_id = $(this).attr("id").substr(38);
    var order_number = $(
      "#single_table_order_details_bottom_order_" + table_id
    ).val();
    order_number = order_number == "" ? "New" : order_number;
    var person = $(
      "#single_table_order_details_bottom_person_" + table_id
    ).val();
    var available_sit = $("#sit_available_number_" + table_id).html();
    var sit_capacity_number = $("#sit_capacity_number_" + table_id).html();
    var total_person = 0;

    $(".person_tbl_" + table_id).each(function () {
      var tmp_v = Number($(this).html());
      total_person += tmp_v;
    });

    if (Number(person) + total_person > parseInt(sit_capacity_number)) {
      swal({
        title: warning + "!",
        text: exceeciding_seat,
        confirmButtonText: ok,
        confirmButtonColor: "#b6d6f6",
      });
      return false;
    }
    var now_available =
      parseInt(sit_capacity_number) - (Number(person) + total_person);
    if (Number(person) + total_person <= 0) {
      swal({
        title: warning + "!",
        text: seat_greater_than_zero,
        confirmButtonText: ok,
        confirmButtonColor: "#b6d6f6",
      });
      return false;
    }

    $("#sit_available_number_" + table_id).html(now_available);

    var table_book_row = "";
    table_book_row +=
      '<div class="single_row fix new_book_to_table" id="new_order_table_' +
      table_id +
      '">';
    table_book_row +=
      '<div class="floatleft fix column first_column">' +
      order_number +
      "</div>";
    table_book_row += '<div class="floatleft fix column second_column">-</div>';
    table_book_row +=
      '<div class="floatleft fix column third_column person_tbl_' +
      table_id +
      '">' +
      person +
      "</div>";
    table_book_row +=
      '<div class="floatleft fix column forth_column"><i class="fas fa-trash-alt remove_new_order_row_icon" id="single_row_table_delete_' +
      table_id +
      '"></i></div>';
    table_book_row += "</div>";
    $("#single_table_order_details_top_" + table_id).append($(table_book_row));
    $("#single_table_order_details_bottom_" + table_id).hide();
    // $('#single_table_order_details_top_'+table_id).remove();
  });
  $(document).on("click", ".running_order_right_arrow", function () {
    var sale_id = $(this).attr("id").substr(26);
    var flexible_div = $(this).parent().parent().height();
    $(".running_order_right_arrow").parent().parent().css("height", "18px");
    if (parseFloat(flexible_div) == parseFloat(18)) {
      $(this).parent().parent().css("height", "100%");
      $(this).addClass("rotated");
    } else if (parseFloat(flexible_div) > parseFloat(18)) {
      $(this).parent().parent().css("height", "18px");
      $(this).removeClass("rotated");
    }
  });
  $(document).on("focus", "#search_running_orders", function () {
    $(".running_order_right_arrow").parent().parent().css("height", "100%");
    $(".running_order_right_arrow").addClass("rotated");
  });
  $(document).on("blur", "#search_running_orders", function () {
    $(".running_order_right_arrow").parent().parent().css("height", "18px");
    $(".running_order_right_arrow").removeClass("rotated");
  });
  $(document).on("click", ".remove_table_order", function () {
    var orders_table_id = $(this).attr("id").substr(19);
    var r = confirm(are_you_sure_cancel_booking);
    if (r == false) {
      return false;
    }
    $(this).parent().parent().remove();
    $.ajax({
      url: base_url + "Sale/remove_a_table_booking_ajax",
      method: "POST",
      data: {
        orders_table_id: orders_table_id,
        csrf_irestoraplus: csrf_value_,
      },
      success: function (response) {
        response = JSON.parse(response);
        var current_available_sit = $(
          "#sit_available_number_" + response.table_id
        ).html();
        var cancelled_sit_number = response.persons;
        var new_available_sit =
          parseInt(current_available_sit) + parseInt(cancelled_sit_number);
        $("#sit_available_number_" + response.table_id).html(new_available_sit);
        // $('#single_notification_row_'+response).remove();
      },
      error: function () {
        alert(a_error);
      },
    });
  });

  $("#please_read_table_modal_button").on("click", function () {
    $("#please_read_modal").slideDown(333);
  });

  $("#please_read_close_button,#please_read_close_button_cross").on(
    "click",
    function () {
      $("#please_read_modal").slideUp(333);
    }
  );
  $("#table_modal_cancel_button,#proceed_without_table_button").on(
    "click",
    function () {
      $(".new_book_to_table").remove();
      $(".new_book_to_table").remove();
      $(".single_table_order_details_holder .bottom").css("display", "block");
      $("#show_tables_modal2").slideUp(333);
      reset_table_modal();
    }
  );
  $("#table_modal_cancel_button2").on("click", function () {
    $(".new_book_to_table").remove();
    $(".single_table_order_details_holder .bottom").css("display", "block");
    $("#show_tables_modal2").slideUp(333);
    reset_table_modal();
  });
  $(document).on("click", ".remove_new_order_row_icon", function () {
    var this_table_id = $(this).attr("id").substr(24);

    $(this).parent().parent().remove();
    var person = $(
      "#single_table_order_details_bottom_person_" + this_table_id
    ).val();
    var available_sit = $("#sit_available_number_" + this_table_id).html();
    var total_person = 0;
    var sit_capacity_number = $("#sit_capacity_number_" + this_table_id).html();
    $(".person_tbl_" + this_table_id).each(function () {
      var tmp_v = Number($(this).html());
      total_person += tmp_v;
    });

    if (total_person > parseInt(sit_capacity_number)) {
      swal({
        title: warning + "!",
        text: exceeciding_seat,
        confirmButtonText: ok,
        confirmButtonColor: "#b6d6f6",
      });
      return false;
    }
    var now_available = parseInt(sit_capacity_number) - total_person;
    if (total_person <= 0) {
      swal({
        title: warning + "!",
        text: seat_greater_than_zero,
        confirmButtonText: ok,
        confirmButtonColor: "#b6d6f6",
      });
      $("#sit_available_number_" + this_table_id).html(now_available);
      return false;
    }

    $("#sit_available_number_" + this_table_id).html(now_available);

    $("#single_table_order_details_bottom_" + this_table_id).css(
      "display",
      "block"
    );
    $("#single_table_order_details_bottom_person_" + this_table_id).val("1");
  });
  $(document).on("keydown", function (e) {
    if (e.ctrlKey && e.shiftKey && e.which == 70) {
      $("#search").focus();
    }
  });
  var interval;
  $("#notification_list_holder")
    .slimscroll({
      height: "300px",
    })
    .parent()
    .css({
      background: "none",
      border: "0px solid #184055",
    });

  $(".select_table_modal_info_holder2")
    .slimscroll({
      height: "450px",
    })
    .parent()
    .css({
      background: "none",
      border: "0px solid #184055",
    });

  $(".help_modal_info_holder")
    .slimscroll({
      height: "400px",
    })
    .parent()
    .css({
      background: "none",
      border: "0px solid #184055",
    });
  $("#calculator_button").on("click", function () {
    $("#calculator_main").fadeToggle(333);
    $(".overlayForCalculator").fadeToggle(111);
  });
  $(".overlayForCalculator").on("click", function () {
    $("#calculator_main").fadeOut(333);
    $(".overlayForCalculator").fadeOut(111);
  });

  set_calculator_position();
  $("#kitchen_status_item_details")
    .slimscroll({
      height: "100px",
    })
    .parent()
    .css({
      background: "none",
      border: "0px solid #184055",
    });
  $("#notification_remove_all").on("click", function () {
    if ($(".single_notification_checkbox:checked").length > 0) {
      var r = confirm(are_you_delete_notification);
      if (r == false) {
        return false;
      }
      var notifications = "";
      var j = 1;
      var checkbox_length = $(".single_notification_checkbox:checked").length;
      $(".single_notification_checkbox:checked").each(function (i, obj) {
        if (j == checkbox_length) {
          notifications += $(this).val();
        } else {
          notifications += $(this).val() + ",";
        }
        j++;
      });
      if (notifications != "") {
        var notifications_array = notifications.split(",");
        notifications_array.forEach(function (entry) {
          $("#single_notification_row_" + entry).remove();
        });
        //Then read the values from the array where 0 is the first
        //Since we skipped the first element in the array, we start at 1
        $.ajax({
          url: base_url + "Sale/remove_multiple_notification_ajax",
          method: "POST",
          data: {
            notifications: notifications,
            csrf_irestoraplus: csrf_value_,
          },
          success: function (response) {
            // $('#single_notification_row_'+response).remove();
          },
          error: function () {
            alert(a_error);
          },
        });
      }
    } else {
      swal({
        title: warning,
        text: no_notification_select,
        confirmButtonText: ok,
        confirmButtonColor: "#b6d6f6",
      });
    }
  });
  $(document).on("click", ".single_serve_b", function () {
    var notification_id = $(this).attr("id").substr(26);
    $.ajax({
      url: base_url + "Sale/remove_notication_ajax",
      method: "POST",
      data: {
        notification_id: notification_id,
        csrf_irestoraplus: csrf_value_,
      },
      success: function (response) {
        $("#single_notification_row_" + response).remove();
      },
      error: function () {
        alert(a_error);
      },
    });
  });
  $("#select_all_notification").on("change", function () {
    if ($(this).is(":checked")) {
      $(".single_notification_checkbox").prop("checked", true);
    } else {
      $(".single_notification_checkbox").prop("checked", false);
    }
  });
  // for same modal
  $("#notification_close").on("click", function () {
    $("#notification_list_modal").slideUp(333);
    $(".single_notification_checkbox").prop("checked", false);
    $("#select_all_notification").prop("checked", false);
  });
  // for same modal
  $("#notification_close2").on("click", function () {
    $("#notification_list_modal").slideUp(333);
    $(".single_notification_checkbox").prop("checked", false);
    $("#select_all_notification").prop("checked", false);
  });

  $("#notification_button").on("click", function () {
    $("#notification_list_modal").slideDown(333);
  });

  $("#open_hold_sales").on("click", function () {
    $("#show_sale_hold_modal").slideDown(333);
    get_all_hold_sales();
  });
  $(document).on(
    "mouseover",
    ".single_hold_sale,.single_last_ten_sale",
    function () {
      $(this).css("background-color", "#f7f7f7");
    }
  );
  $(document).on(
    "mouseout",
    ".single_hold_sale,.single_last_ten_sale",
    function () {
      $(this).css("background-color", "#ffffff");
      if ($(this).attr("data-selected") == "selected") {
        $(this).css("background-color", "#f7f7f7");
      }
    }
  );
  $(document).on("click", ".single_hold_sale", function () {
    //get hold id
    var hold_id = $(this).attr("id").substr(5);
    $(".single_hold_sale").css("background-color", "#ffffff");
    $(".single_hold_sale").attr("data-selected", "unselected");
    $(this).css("background-color", "#f7f7f7");
    $(this).attr("data-selected", "selected");
    //get all info of hold based on hold_id
    $.ajax({
      url: base_url + "Sale/get_single_hold_info_by_ajax",
      method: "POST",
      data: {
        hold_id: hold_id,
        csrf_irestoraplus: csrf_value_,
      },
      success: function (response) {
        response = JSON.parse(response);
        var order_type = "";
        var order_type_id = 0;
        $("#hold_waiter_id").html(response.waiter_id);
        $("#hold_waiter_name").html(response.waiter_name);
        $("#hold_customer_id").html(response.customer_id);
        $("#hold_customer_name").html(response.customer_name);
        $("#hold_table_id").html(response.table_id);
        $("#hold_table_name").html(response.table_name);
        if (response.order_type == 1) {
          order_type = "Dine In";
          order_type_id = 1;
        } else if (response.order_type == 2) {
          order_type = "Take Away";
          order_type_id = 2;
        } else if (response.order_type == 3) {
          order_type = "Delivery";
          order_type_id = 3;
        }
        $("#hold_order_type").html(order_type);
        $("#hold_order_type_id").html(order_type_id);
        var draw_table_for_hold_order = "";

        for (var key in response.items) {
          //construct div
          var this_item = response.items[key];

          var selected_modifiers = "";
          var selected_modifiers_id = "";
          var selected_modifiers_price = "";
          var modifiers_price = 0;
          var total_modifier = this_item.modifiers.length;
          var i = 1;
          for (var mod_key in this_item.modifiers) {
            var this_modifier = this_item.modifiers[mod_key];
            //get selected modifiers
            if (i == total_modifier) {
              selected_modifiers += this_modifier.name;
              selected_modifiers_id += this_modifier.modifier_id;
              selected_modifiers_price += this_modifier.modifier_price;
              modifiers_price = (
                parseFloat(modifiers_price) +
                parseFloat(this_modifier.modifier_price)
              ).toFixed(2);
            } else {
              selected_modifiers += this_modifier.name + ",";
              selected_modifiers_id += this_modifier.modifier_id + ",";
              selected_modifiers_price += this_modifier.modifier_price + ",";
              modifiers_price = (
                parseFloat(modifiers_price) +
                parseFloat(this_modifier.modifier_price)
              ).toFixed(2);
            }
            i++;
          }
          var discount_value =
            this_item.menu_discount_value != ""
              ? this_item.menu_discount_value
              : "0.00";
          draw_table_for_hold_order +=
            '<div class="single_item_modifier fix" id="hold_order_for_item_' +
            this_item.food_menu_id +
            '">';
          draw_table_for_hold_order += '<div class="first_portion fix">';
          draw_table_for_hold_order +=
            '<span class="item_vat_hold ir_display_none" id="hold_item_vat_percentage_table' +
            this_item.food_menu_id +
            '">' +
            this_item.menu_vat_percentage +
            "</span>";
          draw_table_for_hold_order +=
            '<span class="item_discount_hold ir_display_none" id="hold_item_discount_table' +
            this_item.food_menu_id +
            '">' +
            this_item.discount_amount +
            "</span>";
          draw_table_for_hold_order +=
            '<span class="item_price_without_discount_hold ir_display_none" id="hold_item_price_without_discount_' +
            this_item.food_menu_id +
            '">' +
            this_item.menu_price_without_discount +
            "</span>";
          draw_table_for_hold_order +=
            '<div class="single_order_column_hold first_column column fix"><span id="hold_item_name_table_' +
            this_item.food_menu_id +
            '">' +
            this_item.menu_name +
            "</span></div>";
          draw_table_for_hold_order +=
            '<div class="single_order_column_hold second_column column fix">' +
            currency +
            ' <span id="hold_item_price_table_' +
            this_item.food_menu_id +
            '">' +
            this_item.menu_unit_price +
            "</span></div>";
          draw_table_for_hold_order +=
            '<div class="single_order_column_hold third_column column fix"><span id="hold_item_quantity_table_' +
            this_item.food_menu_id +
            '">' +
            this_item.qty +
            "</span></div>";
          draw_table_for_hold_order +=
            '<div class="single_order_column_hold forth_column column fix"><span class="hold_special_textbox" id="hold_percentage_table_' +
            this_item.food_menu_id +
            '">' +
            discount_value +
            "</span></div>";
          draw_table_for_hold_order +=
            '<div class="single_order_column_hold fifth_column column fix">' +
            currency +
            ' <span id="hold_item_total_price_table_' +
            this_item.food_menu_id +
            '">' +
            this_item.menu_price_with_discount +
            "</span></div>";
          draw_table_for_hold_order += "</div>";
          if (selected_modifiers != "") {
            draw_table_for_hold_order += '<div class="second_portion fix">';
            draw_table_for_hold_order +=
              '<span id="hold_item_modifiers_id_table_' +
              this_item.food_menu_id +
              '" class="ir_display_none">' +
              selected_modifiers_id +
              "</span>";
            draw_table_for_hold_order +=
              '<span id="hold_item_modifiers_price_table_' +
              this_item.food_menu_id +
              '" class="ir_display_none">' +
              selected_modifiers_price +
              "</span>";
            draw_table_for_hold_order +=
              '<div class="single_order_column_hold first_column column fix"><span id="hold_item_modifiers_table_' +
              this_item.food_menu_id +
              '">' +
              selected_modifiers +
              "</span></div>";
            draw_table_for_hold_order +=
              '<div class="single_order_column_hold second_column column fix">' +
              currency +
              ' <span id="hold_item_modifiers_price_table_' +
              this_item.food_menu_id +
              '">' +
              modifiers_price +
              "</span></div>";
            draw_table_for_hold_order += "</div>";
          }
          if (this_item.menu_note != "") {
            draw_table_for_hold_order += '<div class="third_portion fix">';
            draw_table_for_hold_order +=
              '<div class="single_order_column_hold first_column column fix">Note: <span id="hold_item_note_table_' +
              this_item.food_menu_id +
              '">' +
              this_item.menu_note +
              "</span></div>";
            draw_table_for_hold_order += "</div>";
          }

          draw_table_for_hold_order += "</div>";
        }
        //add to top
        $(".item_modifier_details .modifier_item_details_holder").empty();
        $(".item_modifier_details .modifier_item_details_holder").prepend(
          draw_table_for_hold_order
        );

        var total_items_in_cart_with_quantity = 0;
        $(
          ".detail_hold_sale_holder .modifier_item_details_holder .single_item_modifier .first_portion .third_column span"
        ).each(function (i, obj) {
          total_items_in_cart_with_quantity =
            parseInt(total_items_in_cart_with_quantity) +
            parseInt($(this).html());
        });
        $("#total_items_in_cart_hold").html(total_items_in_cart_with_quantity);
        var sub_total_discount_hold =
          response.sub_total_discount_value != ""
            ? response.sub_total_discount_value
            : "0.00";
        $("#sub_total_show_hold").html(response.sub_total);
        $("#sub_total_hold").html(response.sub_total);
        $("#total_item_discount_hold").html(
          response.total_item_discount_amount
        );
        $("#discounted_sub_total_amount_hold").html(
          response.sub_total_discount_amount
        );
        $("#sub_total_discount_hold").html(sub_total_discount_hold);
        $("#sub_total_discount_amount_hold").html(
          response.sub_total_with_discount
        );
        var total_vat_section_to_show = "";
        $.each(JSON.parse(response.sale_vat_objects), function (key, value) {
          total_vat_section_to_show +=
            '<span class="tax_field_order_details" id="tax_field_order_details_' +
            value.tax_field_id +
            '">' +
            value.tax_field_type +
            "</span>: " +
            currency +
            ' <span class="tax_field_amount_order_details" id="tax_field_amount_order_details_' +
            value.tax_field_id +
            '">' +
            value.tax_field_amount +
            "</span><br/>";
        });
        $("#all_items_vat_hold").html(total_vat_section_to_show);
        $("#all_items_discount_hold").html(response.total_discount_amount);
        $("#delivery_charge_hold").html(response.delivery_charge);
        $("#total_payable_hold").html(response.total_payable);
        // $(".order_holder_hold").prepend('<span id="modification_'+sale_id+'" class="modification" class="ir_display_none">'+sale_id+'</span>');
        //do calculation on table
      },
      error: function () {
        alert(a_error);
      },
    });
  });

  $(document).on("click", ".single_last_ten_sale", function () {
    //get sale id
    var sale_id = $(this).attr("id").substr(9);
    $(".single_last_ten_sale").css("background-color", "#ffffff");
    $(".single_last_ten_sale").attr("data-selected", "unselected");
    $(this).css("background-color", "#cfcfcf");
    $(this).attr("data-selected", "selected");
    //get all info of sale based on sale_id
    $.ajax({
      url: base_url + "Sale/get_all_information_of_a_sale_ajax",
      method: "POST",
      data: {
        sale_id: sale_id,
        csrf_irestoraplus: csrf_value_,
      },
      success: function (response) {
        response = JSON.parse(response);
        var order_type = "";
        var order_type_id = 0;
        var invoice_type = "";
        var tables_booked = "";
        if (response.tables_booked.length > 0) {
          var w = 1;
          for (var k in response.tables_booked) {
            var single_table = response.tables_booked[k];
            if (w == response.tables_booked.length) {
              tables_booked += single_table.table_name;
            } else {
              tables_booked += single_table.table_name + ", ";
            }
            w++;
          }
        } else {
          tables_booked = "None";
        }
        $("#last_10_waiter_id").html(response.waiter_id);
        $("#last_10_waiter_name").html(response.waiter_name);
        $("#last_10_customer_id").html(response.customer_id);
        $("#last_10_customer_name").html(response.customer_name);
        $("#last_10_table_id").html(response.table_id);
        $("#last_10_table_name").html(tables_booked);
        if (response.order_type == 1) {
          order_type = "Dine In";
          order_type_id = 1;
          invoice_type = "A ";
        } else if (response.order_type == 2) {
          order_type = "Take Away";
          order_type_id = 2;
          invoice_type = "B ";
        } else if (response.order_type == 3) {
          order_type = "Delivery";
          order_type_id = 3;
          invoice_type = "C ";
        }
        $("#last_10_order_type").html(order_type);
        $("#last_10_order_type_id").html(order_type_id);
        $("#last_10_order_invoice_no").html(
          invoice_type + "" + response.sale_no
        );
        var draw_table_for_last_ten_sales_order = "";

        for (var key in response.items) {
          //construct div
          var this_item = response.items[key];

          var selected_modifiers = "";
          var selected_modifiers_id = "";
          var selected_modifiers_price = "";
          var modifiers_price = 0;
          var total_modifier = this_item.modifiers.length;
          var i = 1;
          for (var mod_key in this_item.modifiers) {
            var this_modifier = this_item.modifiers[mod_key];
            //get selected modifiers
            if (i == total_modifier) {
              selected_modifiers += this_modifier.name;
              selected_modifiers_id += this_modifier.modifier_id;
              selected_modifiers_price += this_modifier.modifier_price;
              modifiers_price = (
                parseFloat(modifiers_price) +
                parseFloat(this_modifier.modifier_price)
              ).toFixed(2);
            } else {
              selected_modifiers += this_modifier.name + ",";
              selected_modifiers_id += this_modifier.modifier_id + ",";
              selected_modifiers_price += this_modifier.modifier_price + ",";
              modifiers_price = (
                parseFloat(modifiers_price) +
                parseFloat(this_modifier.modifier_price)
              ).toFixed(2);
            }
            i++;
          }
          var discount_value =
            this_item.menu_discount_value != ""
              ? this_item.menu_discount_value
              : "0.00";
          draw_table_for_last_ten_sales_order +=
            '<div class="single_item_modifier fix" id="last_10_order_for_item_' +
            this_item.food_menu_id +
            '">';
          draw_table_for_last_ten_sales_order +=
            '<div class="first_portion fix">';
          draw_table_for_last_ten_sales_order +=
            '<span class="item_vat_hold ir_display_none" id="last_10_item_vat_percentage_table' +
            this_item.food_menu_id +
            '">' +
            this_item.menu_vat_percentage +
            "</span>";
          draw_table_for_last_ten_sales_order +=
            '<span class="item_discount_hold ir_display_none" id="last_10_item_discount_table' +
            this_item.food_menu_id +
            '">' +
            this_item.discount_amount +
            "</span>";
          draw_table_for_last_ten_sales_order +=
            '<span class="item_price_without_discount_hold ir_display_none" id="last_10_item_price_without_discount_' +
            this_item.food_menu_id +
            '">' +
            this_item.menu_price_without_discount +
            "</span>";
          draw_table_for_last_ten_sales_order +=
            '<div class="single_order_column_hold first_column column fix"><span id="last_10_item_name_table_' +
            this_item.food_menu_id +
            '">' +
            this_item.menu_name +
            "</span></div>";
          draw_table_for_last_ten_sales_order +=
            '<div class="single_order_column_hold second_column column fix">' +
            currency +
            ' <span id="last_10_item_price_table_' +
            this_item.food_menu_id +
            '">' +
            this_item.menu_unit_price +
            "</span></div>";
          draw_table_for_last_ten_sales_order +=
            '<div class="single_order_column_hold third_column column fix"><span id="last_10_item_quantity_table_' +
            this_item.food_menu_id +
            '">' +
            this_item.qty +
            "</span></div>";
          draw_table_for_last_ten_sales_order +=
            '<div class="single_order_column_hold forth_column column fix"><span class="hold_special_textbox" id="last_10_percentage_table_' +
            this_item.food_menu_id +
            '">' +
            discount_value +
            "</span></div>";
          draw_table_for_last_ten_sales_order +=
            '<div class="single_order_column_hold fifth_column column fix">' +
            currency +
            ' <span id="last_10_item_total_price_table_' +
            this_item.food_menu_id +
            '">' +
            this_item.menu_price_with_discount +
            "</span></div>";
          draw_table_for_last_ten_sales_order += "</div>";
          if (selected_modifiers != "") {
            draw_table_for_last_ten_sales_order +=
              '<div class="second_portion fix">';
            draw_table_for_last_ten_sales_order +=
              '<span id="last_10_item_modifiers_id_table_' +
              this_item.food_menu_id +
              '" class="ir_display_none">' +
              selected_modifiers_id +
              "</span>";
            draw_table_for_last_ten_sales_order +=
              '<span id="last_10_item_modifiers_price_table_' +
              this_item.food_menu_id +
              '" class="ir_display_none">' +
              selected_modifiers_price +
              "</span>";
            draw_table_for_last_ten_sales_order +=
              '<div class="single_order_column_hold first_column column fix"><span id="last_10_item_modifiers_table_' +
              this_item.food_menu_id +
              '">' +
              selected_modifiers +
              "</span></div>";
            draw_table_for_last_ten_sales_order +=
              '<div class="single_order_column_hold second_column column fix">' +
              currency +
              ' <span id="last_10_item_modifiers_price_table_' +
              this_item.food_menu_id +
              '">' +
              modifiers_price +
              "</span></div>";
            draw_table_for_last_ten_sales_order += "</div>";
          }
          if (this_item.menu_note != "") {
            draw_table_for_last_ten_sales_order +=
              '<div class="third_portion fix">';
            draw_table_for_last_ten_sales_order +=
              '<div class="single_order_column_hold first_column column fix">Note: <span id="last_10_item_note_table_' +
              this_item.food_menu_id +
              '">' +
              this_item.menu_note +
              "</span></div>";
            draw_table_for_last_ten_sales_order += "</div>";
          }

          draw_table_for_last_ten_sales_order += "</div>";
        }
        //add to top
        $(".item_modifier_details .modifier_item_details_holder").empty();
        $(".item_modifier_details .modifier_item_details_holder").prepend(
          draw_table_for_last_ten_sales_order
        );
        var total_items_in_cart_with_quantity = 0;
        $(
          ".last_ten_sales_holder .modifier_item_details_holder .single_item_modifier .first_portion .third_column span"
        ).each(function (i, obj) {
          total_items_in_cart_with_quantity =
            parseInt(total_items_in_cart_with_quantity) +
            parseInt($(this).html());
        });
        $("#total_items_in_cart_last_10").html(
          total_items_in_cart_with_quantity
        );
        var sub_total_discount_last_10 =
          response.sub_total_discount_value != ""
            ? response.sub_total_discount_value
            : "0.00";
        $("#sub_total_show_last_10").html(response.sub_total);
        $("#sub_total_last_10").html(response.sub_total);
        $("#total_item_discount_last_10").html(
          response.total_item_discount_amount
        );
        $("#discounted_sub_total_amount_last_10").html(
          response.sub_total_discount_amount
        );
        $("#sub_total_discount_last_10").html(sub_total_discount_last_10);
        $("#sub_total_discount_amount_last_10").html(
          response.sub_total_with_discount
        );
        var total_vat_section_to_show = "";
        $.each(JSON.parse(response.sale_vat_objects), function (key, value) {
          total_vat_section_to_show +=
            '<span class="tax_field_order_details" id="tax_field_order_details_' +
            value.tax_field_id +
            '">' +
            value.tax_field_type +
            "</span>:  " +
            currency +
            ' <span class="tax_field_amount_order_details" id="tax_field_amount_order_details_' +
            value.tax_field_id +
            '">' +
            value.tax_field_amount +
            "</span><br/>";
        });
        $("#all_items_vat_last_10").html(total_vat_section_to_show);
        $("#all_items_discount_last_10").html(response.total_discount_amount);
        $("#delivery_charge_last_10").html(response.delivery_charge);
        $("#total_payable_last_10").html(response.total_payable);
        // $(".order_holder_hold").prepend('<span id="modification_'+sale_id+'" class="modification" class="ir_display_none">'+sale_id+'</span>');
        //do calculation on table
      },
      error: function () {
        alert(a_error);
      },
    });
  });
  $("#delete_all_hold_sales_button").on("click", function () {
    if ($(".detail_hold_sale_holder .single_hold_sale").length > 0) {
      var r = confirm(are_you_delete_all_hold_sale);
      if (r == true) {
        //delete all information of hold based on hold_id
        $.ajax({
          url: base_url + "Sale/delete_all_holds_with_information_by_ajax",
          method: "POST",
          data: {
            csrf_irestoraplus: csrf_value_,
          },
          success: function (response) {
            if (response == 1) {
              $(
                ".hold_sale_modal_info_holder .detail_hold_sale_holder .hold_sale_left .detail_holder"
              ).empty();
            }

            $("#hold_waiter_id").html("");
            $("#hold_waiter_name").html("");
            $("#hold_customer_id").html("");
            $("#hold_customer_name").html("");
            $("#hold_table_id").html("");
            $("#hold_table_name").html("");
            $("#hold_order_type").html("");
            $("#hold_order_type_id").html("");
            $(".item_modifier_details .modifier_item_details_holder").empty();
            $("#total_items_in_cart_hold").html("0");
            $("#sub_total_show_hold").html("0.00");
            $("#sub_total_hold").html("0.00");
            $("#total_item_discount_hold").html("0.00");
            $("#discounted_sub_total_amount_hold").html("0.00");
            $("#sub_total_discount_hold").html("");
            $("#sub_total_discount_amount_hold").html("0.00");
            $("#all_items_vat_hold").html("0.00");
            $("#all_items_discount_hold").html("0.00");
            $("#delivery_charge_hold").html("0.00");
            $("#total_payable_hold").html("0.00");
            $("#show_sale_hold_modal").slideUp(333);
          },
          error: function () {
            alert(a_error);
          },
        });
      }
    } else {
      swal({
        title: warning + "!",
        text: no_hold,
        confirmButtonText: ok,
        confirmButtonColor: "#b6d6f6",
      });
    }
  });
  $("#hold_delete_button").on("click", function () {
    if ($(".single_hold_sale[data-selected=selected]").length > 0) {
      var hold_id = $(".single_hold_sale[data-selected=selected]")
        .attr("id")
        .substr(5);

      var r = confirm(sure_delete_this_hold);
      if (r == true) {
        //delete all information of hold based on hold_id
        $.ajax({
          url: base_url + "Sale/delete_all_information_of_hold_by_ajax",
          method: "POST",
          data: {
            hold_id: hold_id,
            csrf_irestoraplus: csrf_value_,
          },
          success: function (response) {
            get_all_hold_sales();
            $("#hold_waiter_id").html("");
            $("#hold_waiter_name").html("");
            $("#hold_customer_id").html("");
            $("#hold_customer_name").html("");
            $("#hold_table_id").html("");
            $("#hold_table_name").html("");
            $("#hold_order_type").html("");
            $("#hold_order_type_id").html("");
            $(".item_modifier_details .modifier_item_details_holder").empty();
            $("#total_items_in_cart_hold").html("0");
            $("#sub_total_show_hold").html("0.00");
            $("#sub_total_hold").html("0.00");
            $("#total_item_discount_hold").html("0.00");
            $("#discounted_sub_total_amount_hold").html("0.00");
            $("#sub_total_discount_hold").html("");
            $("#sub_total_discount_amount_hold").html("0.00");
            $("#all_items_vat_hold").html("0.00");
            $("#all_items_discount_hold").html("0.00");
            $("#delivery_charge_hold").html("0.00");
            $("#total_payable_hold").html("0.00");
            // $('#show_sale_hold_modal').slideUp(333);
          },
          error: function () {
            alert(a_error);
          },
        });
      }
    } else {
      swal({
        title: warning + "!",
        text: please_select_hold_sale,
        confirmButtonText: ok,
        confirmButtonColor: "#b6d6f6",
      });
    }
  });
  $("#last_ten_delete_button").on("click", function () {
    if ($(".single_last_ten_sale[data-selected=selected]").length > 0) {
      var sale_id = $(".single_last_ten_sale[data-selected=selected]")
        .attr("id")
        .substr(9);
      if (role != "Admin") {
        swal({
          title: warning,
          text: delete_only_for_admin,
          confirmButtonText: ok,
          confirmButtonColor: "#b6d6f6",
        });
        return false;
      } else {
        var r = confirm(sure_delete_this_order);
        if (r == true) {
          //delete all information of sale based on sale_id
          $.ajax({
            url: base_url + "Sale/cancel_particular_order_ajax",
            method: "POST",
            data: {
              sale_id: sale_id,
              csrf_irestoraplus: csrf_value_,
            },
            success: function (response) {
              reset_last_10_sales_modal();
              $("#show_last_ten_sales_modal").hide();
            },
            error: function () {
              alert(a_error);
            },
          });
        }
      }
    } else {
      swal({
        title: warning + "!",
        text: please_select_an_order,
        confirmButtonText: ok,
        confirmButtonColor: "#b6d6f6",
      });
    }
  });
  $("#last_ten_print_invoice_button").on("click", function () {
    if ($(".single_last_ten_sale[data-selected=selected]").length > 0) {
      var sale_id = $(".single_last_ten_sale[data-selected=selected]")
        .attr("id")
        .substr(9);
      print_invoice(sale_id);
    } else {
      swal({
        title: warning + "!",
        text: please_select_an_order,
        confirmButtonText: ok,
        confirmButtonColor: "#b6d6f6",
      });
    }
  });
  $("#hold_edit_in_cart_button").on("click", function () {
    var hold_id = $(".single_hold_sale[data-selected=selected]")
      .attr("id")
      .substr(5);
    if ($(".single_hold_sale[data-selected=selected]").length > 0) {
      //get total items in cart
      var total_items_in_cart = $(".order_holder .single_order").length;

      if (total_items_in_cart > 0) {
        var r = confirm(cart_not_empty);
        if (r == true) {
          $(".order_holder").empty();
          clearFooterCartCalculation();
          get_details_of_a_particular_hold(hold_id);
        }
      } else {
        clearFooterCartCalculation();
        get_details_of_a_particular_hold(hold_id);
      }
    } else {
      swal({
        title: warning + "!",
        text: please_select_hold_sale,
        confirmButtonText: ok,
        confirmButtonColor: "#b6d6f6",
      });
    }
  });
  $("#hold_sales_close_button,#hold_sales_close_button_cross").on(
    "click",
    function () {
      $("#hold_waiter_id").html("");
      $("#hold_waiter_name").html("");
      $("#hold_customer_id").html("");
      $("#hold_customer_name").html("");
      $("#hold_table_id").html("");
      $("#hold_table_name").html("");
      $("#hold_order_type").html("");
      $("#hold_order_type_id").html("");

      $(".item_modifier_details .modifier_item_details_holder").empty();
      $("#total_items_in_cart_hold").html("0");
      $("#sub_total_show_hold").html("0.00");
      $("#sub_total_hold").html("0.00");
      $("#total_item_discount_hold").html("0.00");
      $("#discounted_sub_total_amount_hold").html("0.00");
      $("#sub_total_discount_hold").html("");
      $("#sub_total_discount_amount_hold").html("0.00");
      $("#all_items_vat_hold").html("0.00");
      $("#all_items_discount_hold").html("0.00");
      $("#delivery_charge_hold").html("0.00");
      $("#total_payable_hold").html("0.00");
      $("#show_sale_hold_modal").slideUp(333);
    }
  );
  //when mouse out from single menu it stops animating and bring text center
  $(document).on("mouseleave", ".single_item", function () {
    var that = $(this);
    that.find(".item_name").css("margin-left", "-50%");
    clearInterval(interval);
  });
  $("#create_bill_and_close").on("click", function () {
    $("#print_type").val(2);
    if (
      $(".holder .order_details > .single_order[data-selected=selected]")
        .length > 0
    ) {
      var sale_id = $(
        ".holder .order_details .single_order[data-selected=selected]"
      )
        .attr("id")
        .substr(6);

      let newWindow = open(
        "print_bill/" + sale_id,
        "Print Invoice",
        "width=450,height=550"
      );
      newWindow.focus();

      newWindow.onload = function () {
        newWindow.document.body.insertAdjacentHTML("afterbegin");
      };
    } else {
      swal({
        title: warning + "!",
        text: please_select_order_to_proceed + "!",
        confirmButtonText: ok,
        confirmButtonColor: "#b6d6f6",
      });
    }
  });

  $(
    "#create_invoice_and_close,#order_details_create_invoice_close_order_button"
  ).on("click", function () {
    $("#print_type").val(1);
    if (
      $(".holder .order_details > .single_order[data-selected=selected]")
        .length > 0
    ) {
      var sale_id = $(
        ".holder .order_details .single_order[data-selected=selected]"
      )
        .attr("id")
        .substr(6);
      $.ajax({
        url: base_url + "Sale/get_all_information_of_a_sale_ajax",
        method: "POST",
        data: {
          sale_id: sale_id,
          csrf_irestoraplus: csrf_value_,
        },
        success: function (response) {
          response = JSON.parse(response);
          $("#finalize_total_payable").html(response.total_payable);
          $("#pay_amount_invoice_input").val(response.total_payable);
          $("#finalize_order_modal").show();
          $("#finalize_update_type").html("2"); //when 2 update payment method, close time and order_status to 3
          calculate_create_invoice_modal();
        },
        error: function () {
          alert(a_error);
        },
      });
    } else {
      swal({
        title: warning + "!",
        text: please_select_order_to_proceed + "!",
        confirmButtonText: ok,
        confirmButtonColor: "#b6d6f6",
      });
    }
  });
  $("#given_amount_input").on("keyup", function (e) {
    //if the letter is not digit then display error and don't type anything
    if (
      e.which != 110 &&
      !(e.which < 106 && e.which > 95) &&
      e.which != 190 &&
      e.which != 16 &&
      e.which != 53 &&
      e.which != 8 &&
      e.which != 0 &&
      (e.which < 48 || e.which > 57)
    ) {
      $(this).val("");
    }
    //get the value of the delivery charge amount
    var given_amount = $(this).val() != "" ? $(this).val() : 0;

    //check wether value is valid or not
    remove_last_two_digit_without_percentage(given_amount, $(this));

    given_amount = $(this).val() != "" ? $(this).val() : 0;
    var total_payable = $("#finalize_total_payable").html();
    var total_change = (
      parseFloat(given_amount) - parseFloat(total_payable)
    ).toFixed(2);
    $("#change_amount_input").val(total_change);
  });
  $("#pay_amount_invoice_input").on("keyup", function (e) {
    //if the letter is not digit then display error and don't type anything
    if (
      e.which != 110 &&
      !(e.which < 106 && e.which > 95) &&
      e.which != 190 &&
      e.which != 16 &&
      e.which != 53 &&
      e.which != 8 &&
      e.which != 0 &&
      (e.which < 48 || e.which > 57)
    ) {
      $(this).val("");
    }
    var paid_amount = $(this).val() != "" ? $(this).val() : 0;

    remove_last_two_digit_without_percentage(paid_amount, $(this));
    calculate_create_invoice_modal();
  });
  $("#finalize_order_cancel_button").on("click", function () {
    reset_finalize_modal();
  });

  //when mouse out from modal's migration text stops animating and bring text center
  $(document).on("mouseleave", ".modal_modifiers", function () {
    var that = $(this);
    that.find("p").css("margin-left", "-50%");
    clearInterval(interval);
  });

  $("#previous_category").on("click", function () {
    var parent_width = Math.ceil($(".select_category_inside").width());
    var child_width = Math.ceil($(".select_category_inside_inside").width());
    var fixed_to_move = child_width - parent_width;
    var current_position = parseInt(
      $(".select_category_inside_inside").css("margin-left")
    );
    var updated_position = current_position + 50;
    var update_position_unsigned = Math.abs(updated_position);
    if (0 > updated_position) {
      $(".select_category_inside_inside").css(
        "margin-left",
        updated_position + "px"
      );
    } else if (0 < updated_position) {
      $(".select_category_inside_inside").css("margin-left", "0px");
    }
  });
  $("#kitchen_status_button").on("click", function () {
    if (
      $(".holder .order_details > .single_order[data-selected=selected]")
        .length > 0
    ) {
      var sale_id = $(
        ".holder .order_details .single_order[data-selected=selected]"
      )
        .attr("id")
        .substr(6);
      $.ajax({
        url: base_url + "Sale/get_all_information_of_a_sale_ajax",
        method: "POST",
        data: {
          sale_id: sale_id,
          csrf_irestoraplus: csrf_value_,
        },
        success: function (response) {
          reset_time_interval();
          all_time_interval_operation();
          var order_object = JSON.parse(response);
          var order_type = order_object.order_type;
          var order_number = "";
          var order_type_name = "";
          if (order_type == 1) {
            order_number = "A " + order_object.sale_no;
            order_type_name = "Dine In";
          } else if (order_type == 2) {
            order_number = "B " + order_object.sale_no;
            order_type_name = "Take Away";
          } else if (order_type == 3) {
            order_number = "C " + order_object.sale_no;
            order_type_name = "Delivery";
          }
          var tables_booked = "";
          if (order_object.tables_booked.length > 0) {
            var w = 1;
            for (var k in order_object.tables_booked) {
              var single_table = order_object.tables_booked[k];
              if (w == order_object.tables_booked.length) {
                tables_booked += single_table.table_name;
              } else {
                tables_booked += single_table.table_name + ", ";
              }
              w++;
            }
          } else {
            tables_booked = "None";
          }
          var waiter_name =
            order_object.waiter_name == "" || order_object.waiter_name == null
              ? ""
              : order_object.waiter_name;
          var customer_name =
            order_object.customer_name == "" ||
            order_object.customer_name == null
              ? ""
              : order_object.customer_name;
          var table_name =
            order_object.table_name == "" || order_object.table_name == null
              ? ""
              : order_object.table_name;
          var order_time = new Date(Date.parse(order_object.date_time));
          var now = new Date();

          var days = parseInt((now - order_time) / (1000 * 60 * 60 * 24));
          var hours = parseInt(
            (Math.abs(now - order_time) / (1000 * 60 * 60)) % 24
          );
          var minute = parseInt(
            (Math.abs(now.getTime() - order_time.getTime()) / (1000 * 60)) % 60
          );
          var second = parseInt(
            (Math.abs(now.getTime() - order_time.getTime()) / 1000) % 60
          );
          minute = minute.toString();
          second = second.toString();
          minute = minute.length == 1 ? "0" + minute : minute;
          second = second.length == 1 ? "0" + second : second;

          var order_placed_at =
            "Order Placed at: " +
            order_time.getHours() +
            ":" +
            order_time.getMinutes() +
            "";
          var item_list_to_show = "";
          for (var key in order_object.items) {
            var single_item = order_object.items[key];
            if (single_item.item_type == "Kitchen Item") {
              var item_name = single_item.menu_name;
              var background_color = "";
              var current_condition = "In the queue";
              if (single_item.cooking_status == "Started Cooking") {
                var cooking_start_time = new Date(
                  Date.parse(single_item.cooking_start_time)
                );
                var now = new Date();

                var item_days = parseInt(
                  (now - cooking_start_time) / (1000 * 60 * 60 * 24)
                );
                var item_hours = parseInt(
                  (Math.abs(now - cooking_start_time) / (1000 * 60 * 60)) % 24
                );
                var item_minute = parseInt(
                  (Math.abs(now.getTime() - cooking_start_time.getTime()) /
                    (1000 * 60)) %
                    60
                );
                var item_second = parseInt(
                  (Math.abs(now.getTime() - cooking_start_time.getTime()) /
                    1000) %
                    60
                );
                item_minute = item_minute.toString();
                item_second = item_second.toString();
                item_minute =
                  item_minute.length == 1 ? "0" + item_minute : item_minute;
                item_second =
                  item_second.length == 1 ? "0" + item_second : item_second;

                background_color = 'style="background-color:#ADD8E6;"';
                current_condition =
                  "Started Cooking " +
                  item_minute +
                  ":" +
                  item_second +
                  " Min Ago";
              } else if (single_item.cooking_status == "Done") {
                var cooking_start_time = new Date(
                  Date.parse(single_item.cooking_start_time)
                );
                var now = new Date();

                var item_days = parseInt(
                  (now - cooking_start_time) / (1000 * 60 * 60 * 24)
                );
                var item_hours = parseInt(
                  (Math.abs(now - cooking_start_time) / (1000 * 60 * 60)) % 24
                );
                var item_minute = parseInt(
                  (Math.abs(now.getTime() - cooking_start_time.getTime()) /
                    (1000 * 60)) %
                    60
                );
                var item_second = parseInt(
                  (Math.abs(now.getTime() - cooking_start_time.getTime()) /
                    1000) %
                    60
                );
                item_minute = item_minute.toString();
                item_second = item_second.toString();
                item_minute =
                  item_minute.length == 1 ? "0" + item_minute : item_minute;
                item_second =
                  item_second.length == 1 ? "0" + item_second : item_second;

                background_color = ' style="background-color:#90EE90;"';
                current_condition =
                  "Done Cooking " +
                  item_minute +
                  ":" +
                  item_second +
                  " Min Ago";
              }
              item_list_to_show +=
                '<div class="kitchen_status_single_item fix" ' +
                background_color +
                ">";
              item_list_to_show +=
                '<div class="fix first">' + item_name + "</div>";
              item_list_to_show +=
                '<div class="fix second">' + single_item.qty + "</div>";
              item_list_to_show +=
                '<div class="fix third">' + current_condition + "</div>";
              item_list_to_show += "</div>";
            }
          }
          $("#kitchen_status_item_details").empty();
          $("#kitchen_status_order_number").html("");
          $("#kitchen_status_order_number").html(order_number);
          $("#kitchen_status_order_type").html("");
          $("#kitchen_status_order_type").html(order_type_name);
          $("#kitchen_status_waiter_name").html("");
          $("#kitchen_status_waiter_name").html(waiter_name);
          $("#kitchen_status_customer_name").html("");
          $("#kitchen_status_customer_name").html(customer_name);
          $("#kitchen_status_table").html("");
          $("#kitchen_status_table").html(tables_booked);
          $("#kitchen_status_order_placed").html("");
          $("#kitchen_status_order_placed").html(order_placed_at);
          $("#kitchen_status_ordered_minute").html("");
          $("#kitchen_status_ordered_minute").html(minute);
          $("#kitchen_status_ordered_second").html("");
          $("#kitchen_status_ordered_second").html(second);
          $("#kitchen_status_item_details").html(item_list_to_show);

          setInterval(function () {
            var s = $("#kitchen_status_ordered_second").text();
            var m = $("#kitchen_status_ordered_minute").text();
            s = parseInt(s);
            m = parseInt(m);
            s++;
            if (s == 60) {
              m++;
              s = 0;
            }
            m = m.toString();
            s = s.toString();
            m = m.length == 1 ? "0" + m : m;
            s = s.length == 1 ? "0" + s : s;
            $("#kitchen_status_ordered_second").html(s);
            $("#kitchen_status_ordered_minute").html(m);
          }, 1000);
        },
        error: function () {
          alert(a_error);
        },
      });

      $("#kitchen_status_modal").show();
    } else {
      swal({
        title: warning + "!",
        text: please_select_order_to_proceed + "!",
        confirmButtonText: ok,
        confirmButtonColor: "#b6d6f6",
      });
    }
  });
  $("#kitchen_status_close_button").on("click", function () {
    $("#kitchen_status_modal").hide();
  });
  $("#kitchen_status_close_button2").on("click", function () {
    $("#kitchen_status_modal").hide();
  });
  $("#help_button").on("click", function () {
    $("#help_modal").slideDown(333);
  });
  $("#help_close_button,#help_close_button_cross").on("click", function () {
    $("#help_modal").slideUp(333);
  });
  $("#kitchen_bar_waiter_modal_close_button_cross").on("click", function () {
    $("#kitchen_bar_waiter_panel_button_modal").slideUp(333);
  });
  $("#next_category").on("click", function () {
    var parent_width = Math.ceil($(".select_category_inside").width());
    var child_width = Math.ceil($(".select_category_inside_inside").width());
    var fixed_to_move = child_width - parent_width;
    var current_position = parseInt(
      $(".select_category_inside_inside").css("margin-left")
    );
    var updated_position = current_position - 50;
    var update_position_unsigned = Math.abs(updated_position);
    if (fixed_to_move > update_position_unsigned) {
      $(".select_category_inside_inside").css(
        "margin-left",
        updated_position + "px"
      );
    } else {
      $(".select_category_inside_inside").css(
        "margin-left",
        "-" + fixed_to_move + "px"
      );
    }
  });

  //this is to set height when site load
  var height_should_be =
    parseInt($(window).height()) -
    parseInt($(".top_header_part").height()) -
    20;
  $(".main_left").css("height", height_should_be + "px");
  $(".main_middle").css("height", height_should_be + "px");
  $(".main_right").css("height", height_should_be - 10 + "px");
  //end

  //load first category's items default at site load
  $(".specific_category_items_holder:first").show("1000");

  //end

  //get all images based on category when category button is clicked
  $(".category_button").on("click", function () {
    var str = $(this).attr("id");
    var res = str.substr(16);
    $("#searched_item_found").remove();
    $(".specific_category_items_holder").slideUp(555);
    $("#category_" + res).slideDown(555);
    $("#category_" + res).css("display", "flex");
  });

  //when anything is searched
  $("#search").on("keyup", function (e) {
    // if (e.keyCode == 13) {
    var searched_string = $(this).val().trim();
    var foundItems = searchItemAndConstructGallery(searched_string);
    var searched_category_items_to_show =
      '<div id="searched_item_found" class="specific_category_items_holder" style="display:flex;">';

    for (var key in foundItems) {
      if (foundItems.hasOwnProperty(key)) {
        searched_category_items_to_show +=
          '<div class="single_item fix" id="item_' +
          foundItems[key].item_id +
          '">';
        searched_category_items_to_show +=
          '<p class="item_price">' + foundItems[key].price + "</p>";
        searched_category_items_to_show +=
          '<span class="item_vat_percentage ir_display_none">' +
          foundItems[key].vat_percentage +
          "</span>";
        searched_category_items_to_show +=
          '<img src="' + foundItems[key].image + '" alt="" width="141">';
        searched_category_items_to_show +=
          '<p class="item_name">' +
          foundItems[key].item_name +
          " (" +
          foundItems[key].item_code +
          ")</p>";
        searched_category_items_to_show += "</div>";
      }
    }
    searched_category_items_to_show += "<div>";
    $("#searched_item_found").remove();
    $(".specific_category_items_holder").hide("1000");
    $(".category_items").prepend(searched_category_items_to_show);
    // }
  });

  $("#dine_in_button,#take_away_button,#delivery_button").on(
    "click",
    function () {
      // $('.main_top').find('button').css('background-color', '#F3F3F3');
      // $(this).css('background-color', 'red');
      $("#dine_in_button").css("border", "1px solid #A9BDCC");
      $("#take_away_button").css("border", "1px solid #A9BDCC");
      $("#delivery_button").css("border", "1px solid #A9BDCC");

      $(".main_top").find("button").attr("data-selected", "unselected");
      $(this).attr("data-selected", "selected");
      $("#table_button").attr("disabled", false);

      if ($(this).attr("id") == "dine_in_button") {
      } else if ($(this).attr("id") == "take_away_button") {
        $("#table_button").attr("disabled", true);
        $(".single_table_div[data-table-checked=checked]").attr(
          "data-table-checked",
          "unchecked"
        );
        // $('.single_table_div[data-table-checked=checked]').css('background-color', 'red');
      } else if ($(this).attr("id") == "delivery_button") {
        $("#table_button").attr("disabled", true);
        $(".single_table_div[data-table-checked=checked]").attr(
          "data-table-checked",
          "unchecked"
        );
        // $('.single_table_div[data-table-checked=checked]').css('background-color', '#ffffff');
      }
    }
  );

  //when single ite is clicked pop-up modal is appeared
  $(document).on("click", ".single_item", function () {
    var row_number = $("#modal_item_row").html();
    //get item/menu id from modal
    var item_id = $(this).attr("id").substr(5);
    //get item/menu name from modal
    var item_name = $(this).find(".item_name").html();
    //get item/menu vat percentage from modal
    var item_vat_percentage = $(this).find(".item_vat_percentage").html();
    item_vat_percentage =
      item_vat_percentage == "" ? "0.00" : item_vat_percentage;
    //discount amount from modal
    var item_discount_amount = parseFloat(0).toFixed(2);

    //discount input value of modal
    var discount_input_value = 0;

    //get item/menu price from modal
    var item_price = parseFloat($("#price_" + item_id).html()).toFixed(2);

    //get item/menu price from modal without discount
    var item_total_price_without_discount = parseFloat(item_price).toFixed(2);

    //get tax information
    var tax_information = "";
    // iterate over each item in the array
    for (var i = 0; i < window.items.length; i++) {
      // look for the entry with a matching `code` value
      if (items[i].item_id == item_id) {
        tax_information = items[i].tax_information;
      }
    }
    tax_information = IsJsonString(tax_information)
      ? JSON.parse(tax_information)
      : "";
    if (tax_information.length > 0) {
      for (k in tax_information) {
        tax_information[k].item_vat_amount_for_unit_item = (
          (parseFloat(item_price) *
            parseFloat(tax_information[k].tax_field_percentage)) /
          parseFloat(100)
        ).toFixed(2);
        tax_information[k].item_vat_amount_for_all_quantity = (
          parseFloat(tax_information[k].item_vat_amount_for_unit_item) *
          parseFloat(1)
        ).toFixed(2);
      }
    }

    //get vat amount for specific item/menu
    var item_vat_amount_for_unit_item = (
      (parseFloat(item_price) * parseFloat(item_vat_percentage)) /
      parseFloat(100)
    ).toFixed(2);

    //get item/menu total price from modal
    var item_total_price = parseFloat(item_price).toFixed(2);

    //get item/menu quantity from modal
    var item_quantity = "1";

    //get vat amount for specific item/menu
    var item_vat_amount_for_all_quantity = (
      parseFloat(item_vat_amount_for_unit_item) * parseFloat(item_quantity)
    ).toFixed(2);

    //get selected modifiers
    var selected_modifiers = "";
    var selected_modifiers_id = "";
    var selected_modifiers_price = "";

    //get modifiers price
    var modifiers_price = parseFloat(0).toFixed(2);

    //get note
    var note = "";

    //construct div
    var draw_table_for_order = "";

    draw_table_for_order +=
      row_number > 0
        ? ""
        : '<div class="single_order fix" id="order_for_item_' + item_id + '">';
    draw_table_for_order += '<div class="first_portion fix">';
    draw_table_for_order +=
      '<span class="item_previous_id ir_display_none" id="item_previous_id_table' +
      item_id +
      '"></span>';
    draw_table_for_order +=
      '<span class="item_cooking_done_time ir_display_none" id="item_cooking_done_time_table' +
      item_id +
      '"></span>';
    draw_table_for_order +=
      '<span class="item_cooking_start_time ir_display_none" id="item_cooking_start_time_table' +
      item_id +
      '"></span>';
    draw_table_for_order +=
      '<span class="item_cooking_status ir_display_none" id="item_cooking_status_table' +
      item_id +
      '"></span>';
    draw_table_for_order +=
      '<span class="item_type ir_display_none" id="item_type_table' +
      item_id +
      '"></span>';
    draw_table_for_order +=
      '<span class="item_vat ir_display_none" id="item_vat_percentage_table' +
      item_id +
      '">' +
      JSON.stringify(tax_information) +
      "</span>";
    // draw_table_for_order += '<span class="item_vat" id="item_vat_percentage_table'+item_id+'" class="ir_display_none">'+item_vat_percentage+'</span>';
    draw_table_for_order +=
      '<span class="item_discount ir_display_none" id="item_discount_table' +
      item_id +
      '">' +
      item_discount_amount +
      "</span>";
    draw_table_for_order +=
      '<span class="item_price_without_discount ir_display_none" id="item_price_without_discount_' +
      item_id +
      '">' +
      item_total_price_without_discount +
      "</span>";
    draw_table_for_order +=
      '<div class="single_order_column first_column fix"><i style="cursor:pointer;" class="fas fa-pencil-alt edit_item" id="edit_item_' +
      item_id +
      '"></i> <span id="item_name_table_' +
      item_id +
      '">' +
      item_name +
      "</span></div>";
    draw_table_for_order +=
      '<div class="single_order_column second_column fix">' +
      currency +
      ' <span id="item_price_table_' +
      item_id +
      '">' +
      item_price +
      "</span></div>";
    draw_table_for_order +=
      '<div class="single_order_column third_column fix"><i style="cursor:pointer;" class="fas fa-minus-circle decrease_item_table" id="decrease_item_table_' +
      item_id +
      '"></i> <span id="item_quantity_table_' +
      item_id +
      '">' +
      item_quantity +
      '</span> <i style="cursor:pointer;" class="fas fa-plus-circle increase_item_table" id="increase_item_table_' +
      item_id +
      '"></i></div>';
    draw_table_for_order +=
      '<div class="single_order_column forth_column fix"><input type="" name="" placeholder="Amt or %" class="special_textbox" id="percentage_table_' +
      item_id +
      '" value="' +
      discount_input_value +
      '" disabled></div>';
    draw_table_for_order +=
      '<div class="single_order_column fifth_column fix">' +
      currency +
      ' <span id="item_total_price_table_' +
      item_id +
      '">' +
      item_total_price +
      "</span></div>";
    draw_table_for_order += "</div>";
    if (selected_modifiers != "") {
      draw_table_for_order += '<div class="second_portion fix">';
      draw_table_for_order +=
        '<span id="item_modifiers_id_table_' +
        item_id +
        '" class="ir_display_none">' +
        selected_modifiers_id +
        "</span>";
      draw_table_for_order +=
        '<span id="item_modifiers_price_table_' +
        item_id +
        '" class="ir_display_none">' +
        selected_modifiers_price +
        "</span>";
      draw_table_for_order +=
        '<div class="single_order_column first_column fix"><span id="item_modifiers_table_' +
        item_id +
        '">' +
        selected_modifiers +
        "</span></div>";
      draw_table_for_order +=
        '<div class="single_order_column fifth_column fix">' +
        currency +
        ' <span id="item_modifiers_price_table_' +
        item_id +
        '">' +
        modifiers_price +
        "</span></div>";
      draw_table_for_order += "</div>";
    }
    if (note != "") {
      draw_table_for_order += '<div class="third_portion fix">';
      draw_table_for_order +=
        '<div class="single_order_column first_column fix">Note: <span id="item_note_table_' +
        item_id +
        '">' +
        note +
        "</span></div>";
      draw_table_for_order += "</div>";
    }

    draw_table_for_order += row_number > 0 ? "" : "</div>";

    //add to top if new it or update the row
    if (row_number > 0) {
      $(
        ".order_holder .single_order[data-single-order-row-no=" +
          row_number +
          "]"
      ).empty();
      $(
        ".order_holder .single_order[data-single-order-row-no=" +
          row_number +
          "]"
      ).html(draw_table_for_order);
    } else {
      $(".order_holder").append(draw_table_for_order);
    }

    reset_on_modal_close_or_add_to_cart();
    // return false;
    //do calculation on table
    do_addition_of_item_and_modifiers_price();
  });

  $("#cancel_button").on("click", function () {
    //get total items in cart
    var total_items_in_cart = $(".order_holder .single_order").length;
    if (total_items_in_cart > 0) {
      swal(
        {
          title: warning + "!",
          text: cart_not_empty_want_to_clear,
          confirmButtonColor: "#3c8dbc",
          confirmButtonText: ok,
          showCancelButton: true,
        },
        function () {
          $(".order_table_holder .order_holder").empty();
          clearFooterCartCalculation();
          $(".main_top").find("button").css("background-color", "#F3F3F3");
          $(".main_top").find("button").attr("data-selected", "unselected");
          $("#table_button").attr("disabled", false);
          $(".single_table_div[data-table-checked=checked]").attr(
            "data-table-checked",
            "unchecked"
          );
          $("#select_waiter").val("").trigger("change");
          $("#walk_in_customer").val("").trigger("change");
          $("#place_edit_order").html("Place Order");
        }
      );
    }
  });
  $(document).on("click", ".edit_item", function () {
    var single_order_element_object = $(this).parent().parent().parent();
    var row_number = $(this)
      .parent()
      .parent()
      .parent()
      .attr("data-single-order-row-no");
    var menu_id = $(this).attr("id").substr(10);
    var item_name = single_order_element_object
      .find("#item_name_table_" + menu_id)
      .html();
    var item_price = single_order_element_object
      .find("#item_price_table_" + menu_id)
      .html();
    var item_vat_percentage = single_order_element_object
      .find("#item_vat_percentage_table" + menu_id)
      .html();
    var item_discount_input_value = single_order_element_object
      .find("#percentage_table_" + menu_id)
      .val();
    var item_discount_amount = single_order_element_object
      .find("#item_discount_table" + menu_id)
      .html();
    var item_price_without_discount = single_order_element_object
      .find("#item_price_without_discount_" + menu_id)
      .html();
    var item_quantity = single_order_element_object
      .find("#item_quantity_table_" + menu_id)
      .html();
    var item_price_with_discount = parseFloat(
      single_order_element_object
        .find("#item_total_price_table_" + menu_id)
        .html()
    ).toFixed(2);
    var modifiers_price = parseFloat(0).toFixed(2);
    var cooking_status = single_order_element_object
      .find("#item_cooking_status_table" + menu_id)
      .html();
    if (cooking_status != "") {
      swal({
        title: warning + "!",
        text: progress_or_done_kitchen,
        confirmButtonText: ok,
        confirmButtonColor: "#b6d6f6",
      });
      return false;
    }
    if (
      single_order_element_object.find("#item_modifiers_price_table_" + menu_id)
        .length > 0
    ) {
      var comma_separeted_modifiers_price = single_order_element_object
        .find("#item_modifiers_price_table_" + menu_id)
        .html();
      var modifiers_price_array =
        comma_separeted_modifiers_price != ""
          ? comma_separeted_modifiers_price.split(",")
          : "";
      modifiers_price_array.forEach(function (modifier_price) {
        modifiers_price = (
          parseFloat(modifiers_price) + parseFloat(modifier_price)
        ).toFixed(2);
      });
      parseFloat(
        single_order_element_object
          .find("#item_modifiers_price_table_" + menu_id)
          .html()
      ).toFixed(2);
    }

    var note = single_order_element_object
      .find("#item_note_table_" + menu_id)
      .html();

    if (
      single_order_element_object.find("#item_modifiers_id_table_" + menu_id)
        .length > 0
    ) {
      var comma_seperted_modifiers_id = single_order_element_object
        .find("#item_modifiers_id_table_" + menu_id)
        .html();
      var modifiers_id =
        comma_seperted_modifiers_id != ""
          ? comma_seperted_modifiers_id.split(",")
          : "";
    }
    var modifiers_price_as_per_item_quantity = (
      parseFloat(modifiers_price) * parseFloat(item_quantity)
    ).toFixed(2);
    var total_price = (
      parseFloat(item_price_with_discount) +
      parseFloat(modifiers_price_as_per_item_quantity)
    ).toFixed(2);

    $("#modal_item_row").html(row_number);
    $("#modal_item_id").html(menu_id);
    $("#modal_item_name").html(item_name);
    $("#modal_item_price").html(item_price);
    $("#modal_item_price_variable").html(item_price_with_discount);
    $("#modal_item_price_variable_without_discount").html(
      item_price_without_discount
    );
    $("#modal_total_price").html(item_price);
    $("#modal_item_vat_percentage").html(item_vat_percentage);
    $("#modal_discount_amount").html(item_discount_amount);
    $("#item_quantity_modal").html(item_quantity);
    $("#modal_modifiers_unit_price_variable").html(modifiers_price);
    $("#modal_modifier_price_variable").html(
      modifiers_price_as_per_item_quantity
    );
    $("#modal_discount").val(item_discount_input_value);
    $("#modal_item_note").val(note);
    $("#modal_total_price").html(total_price);
    //add modifiers to pop up associated to menu
    var foundItems = search_by_menu_id(menu_id, window.items);
    var originalMenu = foundItems[0].modifiers;
    var modifiers = "";
    for (var key in originalMenu) {
      var selectedOrNot = "unselected";
      var backgroundColor = "";
      if (
        typeof modifiers_id !== "undefined" &&
        modifiers_id.includes(originalMenu[key].menu_modifier_id)
      ) {
        selectedOrNot = "selected";
        backgroundColor = 'style="background-color:#B5D6F6;"';
      } else {
        selectedOrNot = "unselected";
        backgroundColor = "";
      }
      modifiers +=
        "<div " +
        backgroundColor +
        ' class="modal_modifiers" data-selected="' +
        selectedOrNot +
        '" id="modifier_' +
        originalMenu[key].menu_modifier_id +
        '">';
      modifiers +=
        '<span class="modifier_price ir_display_none">' +
        originalMenu[key].menu_modifier_price +
        "</span>";
      modifiers += "<p>" + originalMenu[key].menu_modifier_name + "</p>";
      modifiers += "</div>";
    }
    $("#item_modal .section3").empty();
    $("#item_modal .section3").prepend(modifiers);
    $("#item_modal").slideDown(333);
  });

  $("#close_item_modal").on("click", function () {
    reset_on_modal_close_or_add_to_cart();
  });
  $("#close_add_customer_modal").on("click", function () {
    $("#customer_name_modal").css("border", "1px solid #B5D6F6");
    $("#customer_phone_modal").css("border", "1px solid #B5D6F6");
    reset_on_modal_close_or_add_customer();
  });
  $(document).on("click", ".modal_modifiers", function () {
    //get modifier price when it's selected
    var modifier_price = parseFloat(
      $(this).find(".modifier_price").html()
    ).toFixed(2);
    //get total modifier price
    // var total_modifier_price = parseFloat($('#modal_modifier_price_variable').html()).toFixed(2);
    var total_modifier_price = parseFloat(
      $("#modal_modifiers_unit_price_variable").html()
    ).toFixed(2);
    //add current modifier price to total modifier price
    if ($(this).attr("data-selected") == "unselected") {
      $(this).css("background-color", "#B5D6F6");
      $(this).attr("data-selected", "selected");
      var update_modifier_price = (
        parseFloat(total_modifier_price) + parseFloat(modifier_price)
      ).toFixed(2);
    } else if ($(this).attr("data-selected") == "selected") {
      $(this).css("background-color", "#E0E0E0");
      $(this).attr("data-selected", "unselected");
      var update_modifier_price = (
        parseFloat(total_modifier_price) - parseFloat(modifier_price)
      ).toFixed(2);
    }

    //update total modifier price html element
    // $('#modal_modifier_price_variable').html(update_modifier_price);
    $("#modal_modifiers_unit_price_variable").html(update_modifier_price);

    //update all total with item price
    update_all_total_price();
  });

  //initialize item list
  show_all_items();

  //show all when all button is clicked
  $(document).on("click", "#button_category_show_all", function () {
    $(".specific_category_items_holder").hide();
    var foundItems = searchItemAndConstructGallery("");
    var searched_category_items_to_show =
      '<div id="searched_item_found" class="specific_category_items_holder" style="display:flex;">';

    for (var key in foundItems) {
      if (foundItems.hasOwnProperty(key)) {
        searched_category_items_to_show +=
          '<div class="single_item fix" id="item_' +
          foundItems[key].item_id +
          '">';
        searched_category_items_to_show +=
          '<p class="item_price">' + foundItems[key].price + "</p>";
        searched_category_items_to_show +=
          '<img src="' + foundItems[key].image + '" alt="" width="141">';
        searched_category_items_to_show +=
          '<p class="item_name">' +
          foundItems[key].item_name +
          " (" +
          foundItems[key].item_code +
          ")</p>";
        searched_category_items_to_show += "</div>";
      }
    }
    searched_category_items_to_show += "<div>";
    $("#searched_item_found").remove();
    $(".specific_category_items_holder").hide("1000");
    $(".category_items").prepend(searched_category_items_to_show);
  });

  $("#increase_item_modal").on("click", function () {
    //get recent item price
    var current_item_price_modal = parseFloat(
      $("#modal_item_price").html()
    ).toFixed(2);
    //get current item quantity
    var current_item_quantity = parseInt($("#item_quantity_modal").html());
    //increase quantity
    current_item_quantity++;
    //update quantity
    $("#item_quantity_modal").html(current_item_quantity);

    //update all total with item price
    update_all_total_price();
  });
  $(document).on(
    "dblclick",
    ".holder .order_details > .single_order",
    function () {
      var sale_id = $(this).attr("id").substr(6);
      get_details_of_a_particular_order_for_modal(sale_id);
    }
  );
  $("#cancel_order_button").on("click", function () {
    if (
      $(".holder .order_details > .single_order[data-selected=selected]")
        .length > 0
    ) {
      var selected_order = $(
        ".holder .order_details > .single_order[data-selected=selected]"
      );
      var selected_order_started_cooking_items = selected_order.attr(
        "data-started-cooking"
      );
      var selected_order_done_cooking_items = selected_order.attr(
        "data-done-cooking"
      );
      if (
        selected_order_started_cooking_items > 0 ||
        selected_order_done_cooking_items > 0
      ) {
        swal({
          title: warning + "!",
          text: order_in_progress_or_done,
          confirmButtonText: ok,
          confirmButtonColor: "#b6d6f6",
        });
        return false;
      }
      var r = confirm(sure_cancel_this_order);
      if (r == true) {
        var sale_id = $(
          ".holder .order_details .single_order[data-selected=selected]"
        )
          .attr("id")
          .substr(6);
        cancel_order(sale_id);
      } else {
        return false;
      }
    } else {
      swal({
        title: warning + "!",
        text: please_select_order_to_proceed + "!",
        confirmButtonText: ok,
        confirmButtonColor: "#b6d6f6",
      });
    }
  });
  $("#plus_button").on("click", function () {
    $("#add_customer_modal").slideDown(333);
  });
  $(".modify_order_table_modal").on("click", function () {
    var table_id = $(this).attr("id").substr(19);
    //get total items in cart
    var total_items_in_cart = $(".order_holder .single_order").length;

    if (total_items_in_cart > 0) {
      var r = confirm(cart_not_empty);
      if (r == true) {
        $(".order_holder").empty();
        $.ajax({
          url: base_url + "Sale/get_all_information_of_a_sale_by_table_id_ajax",
          method: "post",
          data: {
            table_id: table_id,
            csrf_irestoraplus: csrf_value_,
          },
          success: function (response) {
            response = JSON.parse(response);
            arrange_info_on_the_cart_to_modify(response);
            $(".single_table_div[data-table-checked=checked]").css(
              "background-color",
              "#ffffff"
            );
            $(".single_table_div[data-table-checked=checked]").attr(
              "data-table-checked",
              "unchecked"
            );
            $("#show_tables_modal").slideUp(333);
          },
          error: function () {
            alert(a_error);
          },
        });
      }
    } else {
      $.ajax({
        url: base_url + "Sale/get_all_information_of_a_sale_by_table_id_ajax",
        method: "post",
        data: {
          table_id: table_id,
          csrf_irestoraplus: csrf_value_,
        },
        success: function (response) {
          response = JSON.parse(response);
          arrange_info_on_the_cart_to_modify(response);
          $(".single_table_div[data-table-checked=checked]").css(
            "background-color",
            "#ffffff"
          );
          $(".single_table_div[data-table-checked=checked]").attr(
            "data-table-checked",
            "unchecked"
          );
          $("#show_tables_modal").slideUp(333);
        },
        error: function () {
          alert(a_error);
        },
      });
    }
  });
  $("#table_button,#dine_in_button").on("click", function () {
    if ($(".order_table_holder .order_holder > .modification").length > 0) {
      var sale_id = $(
        ".order_table_holder .order_holder > .modification"
      ).html();
      var sale_no = $(".order_table_holder .order_holder > .modification").attr(
        "data-sale-no"
      );
    }
    if (typeof sale_no !== "undefined") {
      $("#order_number_or_new_text").html(sale_no);
      $(".bottom_order").val(sale_no);
    } else {
      $("#order_number_or_new_text").html("New");
    }
    $("#show_tables_modal2").slideDown(333);
    $(".bottom_person").val("1");
    $.ajax({
      url: base_url + "Sale/get_all_tables_with_new_status_ajax",
      method: "GET",
      success: function (response) {
        response = JSON.parse(response);
        var table_details = response.table_details;
        var table_availability = response.table_availability;

        for (var key in table_details) {
          var table_id = table_details[key].id;
          var orders_table = table_details[key].orders_table;
          var orders_table_number = orders_table.length;
          var tables_modal = "";
          tables_modal += '<div class="single_row header fix">';
          tables_modal +=
            '<div class="floatleft fix column first_column">Order</div>';
          tables_modal +=
            '<div class="floatleft fix column second_column">Time</div>';
          tables_modal +=
            '<div class="floatleft fix column third_column">Person</div>';
          tables_modal +=
            '<div class="floatleft fix column forth_column">Del</div>';
          tables_modal += "</div>";
          if (orders_table_number > 0) {
            for (var key2 in orders_table) {
              tables_modal += '<div class="single_row fix">';
              tables_modal +=
                '<div class="floatleft fix column first_column">' +
                orders_table[key2].sale_no +
                "</div>";
              tables_modal +=
                '<div class="floatleft fix column second_column">' +
                orders_table[key2].booked_in_minute +
                "M</div>";
              tables_modal +=
                '<div class="floatleft fix column third_column person_tbl_' +
                table_id +
                '">' +
                orders_table[key2].persons +
                "</div>";
              tables_modal +=
                '<div class="floatleft fix column forth_column"><i class="fas fa-trash-alt remove_table_order" id="remove_table_order_' +
                orders_table[key2].id +
                '"></i></div>';
              tables_modal += "</div>";
            }
          }
          if (
            $(
              "#single_table_order_details_top_" +
                table_id +
                " .new_book_to_table"
            ).length > 0
          ) {
            var new_row = $(
              "#single_table_order_details_top_" +
                table_id +
                " .new_book_to_table"
            ).html();
            tables_modal +=
              '<div class="single_row fix new_book_to_table" id="new_order_table_' +
              table_id +
              '">';
            tables_modal += new_row;
            tables_modal += "</div>";
          }
          $("#single_table_order_details_top_" + table_id).html(tables_modal);
        }
        $(".table_image").attr(
          "src",
          base_url + "assets/images/table_icon2.png"
        );
        for (var key in table_availability) {
          var table_id = table_availability[key].table_id;
          var sit_capacity = $("#sit_capacity_number_" + table_id).html();
          var booked_sit = table_availability[key].persons_number;
          var available_sit = parseInt(sit_capacity) - parseInt(booked_sit);
          $("#sit_available_number_" + table_id).html(available_sit);
          if (booked_sit != 0) {
            $("#single_table_info_holder_" + table_id)
              .find(".table_image")
              .attr("src", base_url + "assets/images/table_icon2_blue.png");
          }
        }
      },
      error: function () {
        alert(a_error);
      },
    });
  });
  $("#order_details_close_button").on("click", function () {
    $("#order_detail_modal").slideUp(333);
  });
  $("#order_details_close_button2").on("click", function () {
    $("#order_detail_modal").slideUp(333);
  });
  $("#close_order_button,#order_details_close_order_button").on(
    "click",
    function () {
      if (
        $(".holder .order_details > .single_order[data-selected=selected]")
          .length > 0
      ) {
        var sale_id = $(
          ".holder .order_details .single_order[data-selected=selected]"
        )
          .attr("id")
          .substr(6);
        if ($("#open_orders_order_status_" + sale_id).html() == "1") {
          swal({
            title: warning + "!",
            text: close_order_without,
            confirmButtonText: ok,
            confirmButtonColor: "#b6d6f6",
          });
          return false;
        }
        var r = confirm(want_to_close_order);

        if (r == true) {
          close_order(sale_id, 0);
        }
      } else {
        swal({
          title: warning + "!",
          text: please_select_open_order,
          confirmButtonText: ok,
          confirmButtonColor: "#b6d6f6",
        });
      }
    }
  );
  $("#decrease_item_modal").on("click", function () {
    //get recent item price
    var current_item_price_modal = parseFloat(
      $("#modal_item_price").html()
    ).toFixed(2);
    //get current item quantity
    var current_item_quantity = parseInt($("#item_quantity_modal").html());

    //decrease quantity if greater than 1
    if (current_item_quantity > 1) current_item_quantity--;

    //update quantity

    $("#item_quantity_modal").html(current_item_quantity);
    //update all total with item price
    update_all_total_price();
  });

  $("#last_ten_sales_close_button,#last_ten_sales_close_button_cross").on(
    "click",
    function () {
      $("#show_last_ten_sales_modal").slideUp(333);
      reset_last_10_sales_modal();
    }
  );
  $("#customer_dob_modal").datepicker({
    dateFormat: "yy-mm-dd",
    changeYear: true,
    changeMonth: true,
    showMonthAfterYear: true, //this is what you are looking for
    maxDate: 0,
  });
  $("#customer_doa_modal").datepicker({
    dateFormat: "yy-mm-dd",
    changeYear: true,
    changeMonth: true,
    showMonthAfterYear: true, //this is what you are looking for
    maxDate: 0,
  });
  $("#last_ten_sales_button").on("click", function () {
    $("#show_last_ten_sales_modal").slideDown(333);
    $.ajax({
      url: base_url + "Sale/get_last_10_sales_ajax",
      method: "GET",
      success: function (response) {
        var orders = JSON.parse(response);
        var last_10_orders = "";
        for (var key in orders) {
          var order_name = "";
          if (orders[key].order_type == "1") {
            order_name = "A " + orders[key].sale_no;
          } else if (orders[key].order_type == "2") {
            order_name = "B " + orders[key].sale_no;
          } else if (orders[key].order_type == "3") {
            order_name = "C " + orders[key].sale_no;
          }
          var tables_booked = "";
          if (orders[key].tables_booked.length > 0) {
            var w = 1;
            for (var k in orders[key].tables_booked) {
              var single_table = orders[key].tables_booked[k];
              if (w == orders[key].tables_booked.length) {
                tables_booked += single_table.table_name;
              } else {
                tables_booked += single_table.table_name + ", ";
              }
              w++;
            }
          } else {
            tables_booked = "None";
          }
          var table_name =
            orders[key].table_name != null ? orders[key].table_name : "&nbsp;";
          last_10_orders +=
            '<div class="single_last_ten_sale fix" id="last_ten_' +
            orders[key].id +
            '" data-selected="unselected">';
          last_10_orders +=
            '<div class="first_column column fix">' + order_name + "</div>";
          last_10_orders +=
            '<div class="second_column column fix">' +
            orders[key].customer_name +
            "</div>";
          last_10_orders +=
            '<div class="third_column column fix">' + tables_booked + "</div>";
          last_10_orders += "</div>";
        }
        $(".last_ten_sales_holder .hold_list_holder .detail_holder ").empty();
        $(".last_ten_sales_holder .hold_list_holder .detail_holder ").prepend(
          last_10_orders
        );
      },
      error: function () {
        alert(a_error);
      },
    });
  });
  //when add to card button is clicked information goes to table of middle to top
  $("#add_to_cart").on("click", function () {
    var row_number = $("#modal_item_row").html();
    //get item/menu id from modal
    var item_id = $("#modal_item_id").html();
    //remove if it is edited update of previous added item based on id
    // if($('#order_for_item_'+item_id).length>0){
    // 	$('#order_for_item_'+item_id).remove();
    // }

    //get item/menu name from modal
    var item_name = $("#modal_item_name").html();
    //get item/menu vat percentage from modal
    var item_vat_percentage = $("#modal_item_vat_percentage").html();
    item_vat_percentage =
      item_vat_percentage == "" ? "0.00" : item_vat_percentage;
    //discount amount from modal
    var item_discount_amount = parseFloat(
      $("#modal_discount_amount").html()
    ).toFixed(2);

    //discount input value of modal
    var discount_input_value = $("#modal_discount").val();

    //get item/menu price from modal
    var item_price = parseFloat($("#modal_item_price").html()).toFixed(2);

    //get item/menu price from modal without discount
    var item_total_price_without_discount = parseFloat(
      $("#modal_item_price_variable_without_discount").html()
    ).toFixed(2);

    //get vat amount for specific item/menu
    var item_vat_amount_for_unit_item = (
      (parseFloat(item_price) * parseFloat(item_vat_percentage)) /
      parseFloat(100)
    ).toFixed(2);

    //get item/menu total price from modal
    var item_total_price = parseFloat(
      $("#modal_item_price_variable").html()
    ).toFixed(2);

    //get item/menu quantity from modal
    var item_quantity = $("#item_quantity_modal").html();

    //get vat amount for specific item/menu
    var item_vat_amount_for_all_quantity = (
      parseFloat(item_vat_amount_for_unit_item) * parseFloat(item_quantity)
    ).toFixed(2);

    //get selected modifiers
    var selected_modifiers = "";
    var selected_modifiers_id = "";
    var selected_modifiers_price = "";
    var j = 1;
    $(".modal_modifiers[data-selected=selected]").each(function (i, obj) {
      if (j == $(".modal_modifiers[data-selected=selected]").length) {
        selected_modifiers += $(this).find("p").html();
        selected_modifiers_id += $(this).attr("id").substr(9);
        selected_modifiers_price += $(this).find(".modifier_price").html();
      } else {
        selected_modifiers += $(this).find("p").html() + ",";
        selected_modifiers_id += $(this).attr("id").substr(9) + ",";
        selected_modifiers_price +=
          $(this).find(".modifier_price").html() + ",";
      }
      j++;
    });

    //get modifiers price
    var modifiers_price = parseFloat(
      $("#modal_modifier_price_variable").html()
    ).toFixed(2);

    //get note
    var note = $("#modal_item_note").val();

    //construct div
    var draw_table_for_order = "";

    draw_table_for_order +=
      row_number > 0
        ? ""
        : '<div class="single_order fix" id="order_for_item_' + item_id + '">';
    draw_table_for_order += '<div class="first_portion fix">';
    draw_table_for_order +=
      '<span class="item_previous_id ir_display_none" id="item_previous_id_table' +
      item_id +
      '"></span>';
    draw_table_for_order +=
      '<span class="item_cooking_done_time ir_display_none" id="item_cooking_done_time_table' +
      item_id +
      '"></span>';
    draw_table_for_order +=
      '<span class="item_cooking_start_time ir_display_none" id="item_cooking_start_time_table' +
      item_id +
      '"></span>';
    draw_table_for_order +=
      '<span class="item_cooking_status ir_display_none" id="item_cooking_status_table' +
      item_id +
      '"></span>';
    draw_table_for_order +=
      '<span class="item_type ir_display_none" id="item_type_table' +
      item_id +
      '"></span>';
    draw_table_for_order +=
      '<span class="item_vat ir_display_none" id="item_vat_percentage_table' +
      item_id +
      '">' +
      item_vat_percentage +
      "</span>";
    draw_table_for_order +=
      '<span class="item_discount ir_display_none" id="item_discount_table' +
      item_id +
      '">' +
      item_discount_amount +
      "</span>";
    draw_table_for_order +=
      '<span class="item_price_without_discount ir_display_none" id="item_price_without_discount_' +
      item_id +
      '">' +
      item_total_price_without_discount +
      "</span>";
    draw_table_for_order +=
      '<div class="single_order_column first_column fix"><i style="cursor:pointer;" class="fas fa-pencil-alt edit_item" id="edit_item_' +
      item_id +
      '"></i> <span id="item_name_table_' +
      item_id +
      '">' +
      item_name +
      "</span></div>";
    draw_table_for_order +=
      '<div class="single_order_column second_column fix">' +
      currency +
      ' <span id="item_price_table_' +
      item_id +
      '">' +
      item_price +
      "</span></div>";
    draw_table_for_order +=
      '<div class="single_order_column third_column fix"><i style="cursor:pointer;" class="fas fa-minus-circle decrease_item_table" id="decrease_item_table_' +
      item_id +
      '"></i> <span id="item_quantity_table_' +
      item_id +
      '">' +
      item_quantity +
      '</span> <i style="cursor:pointer;" class="fas fa-plus-circle increase_item_table" id="increase_item_table_' +
      item_id +
      '"></i></div>';
    draw_table_for_order +=
      '<div class="single_order_column forth_column fix"><input type="" name="" placeholder="Amt or %" class="special_textbox" id="percentage_table_' +
      item_id +
      '" value="' +
      discount_input_value +
      '" disabled></div>';
    draw_table_for_order +=
      '<div class="single_order_column fifth_column fix">' +
      currency +
      ' <span id="item_total_price_table_' +
      item_id +
      '">' +
      item_total_price +
      "</span></div>";
    draw_table_for_order += "</div>";
    if (selected_modifiers != "") {
      draw_table_for_order += '<div class="second_portion fix">';
      draw_table_for_order +=
        '<span id="item_modifiers_id_table_' +
        item_id +
        '" class="ir_display_none">' +
        selected_modifiers_id +
        "</span>";
      draw_table_for_order +=
        '<span id="item_modifiers_price_table_' +
        item_id +
        '" class="ir_display_none">' +
        selected_modifiers_price +
        "</span>";
      draw_table_for_order +=
        '<div class="single_order_column first_column fix"><span id="item_modifiers_table_' +
        item_id +
        '">' +
        selected_modifiers +
        "</span></div>";
      draw_table_for_order +=
        '<div class="single_order_column fifth_column fix">' +
        currency +
        ' <span id="item_modifiers_price_table_' +
        item_id +
        '">' +
        modifiers_price +
        "</span></div>";
      draw_table_for_order += "</div>";
    }
    if (note != "") {
      draw_table_for_order += '<div class="third_portion fix">';
      draw_table_for_order +=
        '<div class="single_order_column first_column fix">Note: <span id="item_note_table_' +
        item_id +
        '">' +
        note +
        "</span></div>";
      draw_table_for_order += "</div>";
    }

    draw_table_for_order += row_number > 0 ? "" : "</div>";

    //add to top if new it or update the row
    if (row_number > 0) {
      $(
        ".order_holder .single_order[data-single-order-row-no=" +
          row_number +
          "]"
      ).empty();
      $(
        ".order_holder .single_order[data-single-order-row-no=" +
          row_number +
          "]"
      ).html(draw_table_for_order);
    } else {
      $(".order_holder").append(draw_table_for_order);
    }

    reset_on_modal_close_or_add_to_cart();
    // return false;
    //do calculation on table
    do_addition_of_item_and_modifiers_price();
  });
  //when plus sign is clicked in the table
  $(document).on(
    "click",
    ".single_order .first_portion .third_column .increase_item_table",
    function () {
      var single_order_element_object = $(this).parent().parent().parent();
      //get item id
      var item_id = $(this).attr("id").substr(20);

      //get this item quantity
      var item_quantity = $(this).parent().find("span").html();

      //get this item's unit price
      var unit_price = $(this)
        .parent()
        .parent()
        .find(".second_column span")
        .html();
      var cooking_status = single_order_element_object
        .find("#item_cooking_status_table" + item_id)
        .html();
      if (cooking_status != "") {
        swal({
          title: warning + "!",
          text: progress_or_done_kitchen,
          confirmButtonText: ok,
          confirmButtonColor: "#b6d6f6",
        });
        return false;
      }
      //increase item quantity
      item_quantity++;

      //increase item's total price based on unit price and quantity
      var updated_total_price = (
        parseFloat(item_quantity) * parseFloat(unit_price)
      ).toFixed(2);

      //update total price of this item to view
      $(this)
        .parent()
        .parent()
        .find(".item_price_without_discount")
        .html(updated_total_price);

      //update quantity of this item to view
      $(this).parent().find("span").html(item_quantity);

      //do calculation on update values
      do_addition_of_item_and_modifiers_price();
    }
  );
  //when - sign is clicked in the table
  $(document).on(
    "click",
    ".single_order .first_portion .third_column .decrease_item_table",
    function () {
      var single_order_element_object = $(this).parent().parent().parent();
      //get item id
      var item_id = $(this).attr("id").substr(20);
      //get this item quantity
      var item_quantity = $(this).parent().find("span").html();
      //get this item's unit price
      var unit_price = $(this)
        .parent()
        .parent()
        .find(".second_column span")
        .html();
      var cooking_status = single_order_element_object
        .find("#item_cooking_status_table" + item_id)
        .html();
      if (cooking_status != "") {
        swal({
          title: warning + "!",
          text: progress_or_done_kitchen,
          confirmButtonText: ok,
          confirmButtonColor: "#b6d6f6",
        });
        return false;
      }
      //decrease item quantity if greater then 1 or remove full item from table
      if (item_quantity > 1) {
        //decrease item quantity
        item_quantity--;

        //decrease item's total price based on unit price and quantity
        var updated_total_price = (
          parseFloat(item_quantity) * parseFloat(unit_price)
        ).toFixed(2);

        //update total price of this item to view
        $(this)
          .parent()
          .parent()
          .find(".item_price_without_discount")
          .html(updated_total_price);

        //update quantity of this item to view
        $(this).parent().find("span").html(item_quantity);

        //do calculation on update values
        do_addition_of_item_and_modifiers_price();
      } else {
        $("#order_for_item_" + item_id).remove();
        // clearFooterCartCalculation();
        do_addition_of_item_and_modifiers_price();
      }
      //decrease item's total price bsed on unit price and quantity
    }
  );

  //add discount for specific item
  $(document).on(
    "keyup",
    ".single_order .first_portion .forth_column input",
    function (e) {
      //if the letter is not digit then display error and don't type anything
      if (
        e.which != 110 &&
        !(e.which < 106 && e.which > 95) &&
        e.which != 190 &&
        e.which != 16 &&
        e.which != 53 &&
        e.which != 8 &&
        e.which != 0 &&
        (e.which < 48 || e.which > 57)
      ) {
        $(this).val("");
      }
      do_addition_of_item_and_modifiers_price();
    }
  );

  //add discount for specific item in modal
  $(document).on("keyup", "#modal_discount", function (e) {
    //if the letter is not digit then display error and don't type anything
    if (
      e.which != 110 &&
      !(e.which < 106 && e.which > 95) &&
      e.which != 190 &&
      e.which != 16 &&
      e.which != 53 &&
      e.which != 8 &&
      e.which != 0 &&
      (e.which < 48 || e.which > 57)
    ) {
      $(this).val("");
    }
    update_all_total_price();
  });
  $("#sub_total_discount").on("keyup", function (e) {
    //if the letter is not digit then display error and don't type anything
    if (
      e.which != 110 &&
      !(e.which < 106 && e.which > 95) &&
      e.which != 190 &&
      e.which != 16 &&
      e.which != 53 &&
      e.which != 8 &&
      e.which != 0 &&
      (e.which < 48 || e.which > 57)
    ) {
      $(this).val("");
    }
    do_addition_of_item_and_modifiers_price();
  });
  $("#delivery_charge").on("keyup", function (e) {
    //if the letter is not digit then display error and don't type anything
    if (
      e.which != 110 &&
      !(e.which < 106 && e.which > 95) &&
      e.which != 190 &&
      e.which != 16 &&
      e.which != 53 &&
      e.which != 8 &&
      e.which != 0 &&
      (e.which < 48 || e.which > 57)
    ) {
      $(this).val("");
    }
    do_addition_of_item_and_modifiers_price();
  });

  $("#walk_in_customer").select2({
    dropdownCssClass: "bigdrop",
  });
  // sound effect
  let placeOrderSound = new Howl({
    src: [base_url + "assets/media/alert_alarm.mp3"],
  });

  $("#place_order_operation").on("click", function () {
    var sale_id = 0;
    if ($(".order_table_holder .order_holder > .modification").length > 0) {
      var sale_id = $(
        ".order_table_holder .order_holder > .modification"
      ).html();
    }
    var selected_order_type_object = $(".main_top").find(
      "button[data-selected=selected]"
    );
    var total_items_in_cart = $(".order_holder .single_order").length;
    var sub_total = parseFloat($("#sub_total_show").html()).toFixed(2);
    var total_vat = parseFloat($("#all_items_vat").html()).toFixed(2);
    var total_payable = parseFloat($("#total_payable").html()).toFixed(2);
    var total_item_discount_amount = parseFloat(
      $("#total_item_discount").html()
    ).toFixed(2);
    var sub_total_with_discount = parseFloat(
      $("#discounted_sub_total_amount").html()
    ).toFixed(2);
    var sub_total_discount_amount = parseFloat(
      $("#sub_total_discount_amount").html()
    ).toFixed(2);
    var total_discount_amount = parseFloat(
      $("#all_items_discount").html()
    ).toFixed(2);
    var delivery_charge =
      $("#delivery_charge").val() != ""
        ? parseFloat($("#delivery_charge").val()).toFixed(2)
        : parseFloat(0).toFixed(2);
    var sub_total_discount_value = $("#sub_total_discount").val();
    var sub_total_discount_type = "";
    var customer_id = $("#walk_in_customer").val();
    var waiter_id = $("#select_waiter").val();
    var sale_vat_objects = [];
    $("#all_items_vat .tax_field").each(function (i, obj) {
      var tax_field_id = $(this).attr("id").substr(10);
      var tax_field_type = $(this).html();
      var tax_field_amount = $("#tax_field_amount_" + tax_field_id).html();
      sale_vat_objects.push({
        tax_field_id: tax_field_id,
        tax_field_type: tax_field_type,
        tax_field_amount: tax_field_amount,
      });
    });

    if (total_items_in_cart == 0) {
      // swal({
      // 	title: warning + "!",
      // 	text: cart_empty,
      // 	confirmButtonText: ok,
      // 	confirmButtonColor: '#b6d6f6'
      // })
      $(".cardIsEmpty").css("border", "2px solid red");
      setTimeout(function () {
        $(".cardIsEmpty").css("border", "none");
      }, 2000);
      placeOrderSound.play();
      return false;
    }
    if (
      sub_total_discount_value.length > 0 &&
      sub_total_discount_value.substr(sub_total_discount_value.length - 1) ==
        "%"
    ) {
      sub_total_discount_type = "percentage";
    } else {
      sub_total_discount_type = "plain";
    }
    if (selected_order_type_object.length > 0) {
      var order_type = 1;
      if (selected_order_type_object.attr("id") == "delivery_button") {
        order_type = 3;
        if (customer_id == "") {
          $("#select2-walk_in_customer-container").css(
            "border",
            "1px solid red"
          );
          var op1 = $("#walk_in_customer").data("select2");
          var op2 = $("#select_waiter").data("select2");
          op1.open();
          op2.close();

          return false;
        }
        if (customer_id == "1") {
          $("#select2-walk_in_customer-container").css(
            "border",
            "1px solid red"
          );
          var op1 = $("#walk_in_customer").data("select2");
          var op2 = $("#select_waiter").data("select2");
          op1.open();
          op2.close();
          return false;
        }

        var address_available = searchCustomerAddress(customer_id);
        if (address_available[0].customer_address == "") {
          $("#select2-walk_in_customer-container").css(
            "border",
            "1px solid red"
          );
          var op1 = $("#walk_in_customer").data("select2");
          var op2 = $("#select_waiter").data("select2");
          op1.open();
          op2.close();
          return false;
        }
      } else if (selected_order_type_object.attr("id") == "dine_in_button") {
        order_type = 1;
        if (waiter_id == "") {
          $("#select2-select_waiter-container").css("border", "1px solid red");
          $("#select2-select_waiter-container").css("border", "1px solid red");
          var op1 = $("#walk_in_customer").data("select2");
          var op2 = $("#select_waiter").data("select2");
          op1.close();
          op2.open();
          return false;
        }
        if (customer_id == "") {
          $("#select2-walk_in_customer-container").css(
            "border",
            "1px solid red"
          );
          var op1 = $("#walk_in_customer").data("select2");
          var op2 = $("#select_waiter").data("select2");
          op1.open();
          op2.close();
          return false;
        }
      } else if (selected_order_type_object.attr("id") == "take_away_button") {
        order_type = 2;

        if (waiter_id == "") {
          $("#select2-select_waiter-container").css("border", "1px solid red");
          var op1 = $("#walk_in_customer").data("select2");
          var op2 = $("#select_waiter").data("select2");
          op1.close();
          op2.open();
          return false;
        }
        if (customer_id == "") {
          $("#select2-walk_in_customer-container").css(
            "border",
            "1px solid red"
          );
          var op1 = $("#walk_in_customer").data("select2");
          var op2 = $("#select_waiter").data("select2");
          op1.open();
          op2.close();
          return false;
        }
      }

      var order_status = 1;

      var order_info = "{";
      order_info += '"customer_id":"' + customer_id + '",';
      order_info += '"waiter_id":"' + waiter_id + '",';
      order_info += '"total_items_in_cart":"' + total_items_in_cart + '",';
      order_info += '"sub_total":"' + sub_total + '",';
      order_info += '"total_vat":"' + total_vat + '",';
      order_info += '"total_payable":"' + total_payable + '",';
      order_info +=
        '"total_item_discount_amount":"' + total_item_discount_amount + '",';
      order_info +=
        '"sub_total_with_discount":"' + sub_total_with_discount + '",';
      order_info +=
        '"sub_total_discount_amount":"' + sub_total_discount_amount + '",';
      order_info += '"total_discount_amount":"' + total_discount_amount + '",';
      order_info += '"delivery_charge":"' + delivery_charge + '",';
      order_info +=
        '"sub_total_discount_value":"' + sub_total_discount_value + '",';
      order_info +=
        '"sub_total_discount_type":"' + sub_total_discount_type + '",';
      // order_info += '"selected_table":"'+selected_table+'",';
      order_info += '"order_type":"' + order_type + '",';
      order_info += '"order_status":"' + order_status + '",';
      order_info +=
        '"sale_vat_objects":' + JSON.stringify(sale_vat_objects) + ",";
      var orders_table = "";
      orders_table += '"orders_table":';
      orders_table += "[";
      var x = 1;
      var total_orders_table = $(".new_book_to_table").length;
      $(".new_book_to_table").each(function (i, obj) {
        var table_id = $(this).attr("id").substr(16);
        var person = $(this).find(".third_column").html();
        if (x == total_orders_table) {
          orders_table +=
            '{"table_id":"' + table_id + '", "persons":"' + person + '"}';
        } else {
          orders_table +=
            '{"table_id":"' + table_id + '", "persons":"' + person + '"},';
        }

        x++;
      });

      orders_table += "],";
      order_info += orders_table;
      var items_info = "";
      items_info += '"items":';
      items_info += "[";

      if ($(".order_holder .single_order").length > 0) {
        var k = 1;
        $(".order_holder .single_order").each(function (i, obj) {
          var item_id = $(this).attr("id").substr(15);
          var item_name = $(this)
            .find("#item_name_table_" + item_id)
            .html();
          var item_vat = $(this).find(".item_vat").html();
          var item_discount = $(this)
            .find("#percentage_table_" + item_id)
            .val();
          var discount_type = "";
          if (
            item_discount.length > 0 &&
            item_discount.substr(item_discount.length - 1) == "%"
          ) {
            discount_type = "percentage";
          } else {
            discount_type = "plain";
          }
          var item_previous_id = $(this)
            .find("#item_previous_id_table" + item_id)
            .html();
          var item_cooking_done_time = $(this)
            .find("#item_cooking_done_time_table" + item_id)
            .html();
          var item_cooking_start_time = $(this)
            .find("#item_cooking_start_time_table" + item_id)
            .html();
          var item_cooking_status = $(this)
            .find("#item_cooking_status_table" + item_id)
            .html();
          var item_type = $(this)
            .find("#item_type_table" + item_id)
            .html();
          var item_price_without_discount = $(this)
            .find(".item_price_without_discount")
            .html();
          var item_unit_price = $(this)
            .find("#item_price_table_" + item_id)
            .html();
          var item_quantity = $(this)
            .find("#item_quantity_table_" + item_id)
            .html();
          var tmp_qty = $("#tmp_qty_" + item_id).val();
          var p_qty = $("#p_qty_" + item_id).val();
          var item_price_with_discount = $(this)
            .find("#item_total_price_table_" + item_id)
            .html();
          var item_discount_amount = (
            parseFloat(item_price_without_discount) -
            parseFloat(item_price_with_discount)
          ).toFixed(2);

          items_info +=
            '{"item_id":"' +
            item_id +
            '", "item_name":"' +
            item_name +
            '", "item_vat":' +
            item_vat +
            ",";
          items_info +=
            '"item_discount":"' +
            item_discount +
            '","discount_type":"' +
            discount_type +
            '","item_price_without_discount":"' +
            item_price_without_discount +
            '",';
          items_info +=
            '"item_unit_price":"' +
            item_unit_price +
            '","item_quantity":"' +
            item_quantity +
            '","tmp_qty":"' +
            tmp_qty +
            '","p_qty":"' +
            p_qty +
            '",';
          items_info +=
            '"item_previous_id":"' +
            item_previous_id +
            '","item_cooking_done_time":"' +
            item_cooking_done_time +
            '",';
          items_info +=
            '"item_cooking_start_time":"' +
            item_cooking_start_time +
            '","item_cooking_status":"' +
            item_cooking_status +
            '","item_type":"' +
            item_type +
            '",';
          items_info +=
            '"item_price_with_discount":"' +
            item_price_with_discount +
            '","item_discount_amount":"' +
            item_discount_amount +
            '"';

          if ($(this).find(".second_portion").length > 0) {
            var modifiers_id = $(this)
              .find("#item_modifiers_id_table_" + item_id)
              .html();
            var modifiers_price = $(this)
              .find("#item_modifiers_price_table_" + item_id)
              .html();
            items_info +=
              ',"modifiers_id":"' +
              modifiers_id +
              '", "modifiers_price":"' +
              modifiers_price +
              '"';
          } else {
            items_info += ',"modifiers_id":"", "modifiers_price":""';
          }
          if ($(this).find(".third_portion").length > 0) {
            var item_note = $(this)
              .find("#item_note_table_" + item_id)
              .html();
            items_info += ',"item_note":"' + item_note + '"';
          } else {
            items_info += ',"item_note":""';
          }
          items_info +=
            k == $(".order_holder .single_order").length ? "}" : "},";
          k++;
        });
      }
      items_info += "]";
      order_info += items_info + "}";

      var order_object = JSON.stringify(order_info);

      add_sale_by_ajax(order_object, sale_id);
    } else {
      $("#dine_in_button").css("border", "1px solid red");
      $("#take_away_button").css("border", "1px solid red");
      $("#delivery_button").css("border", "1px solid red");
    }
  });

  $("#direct_invoice").on("click", function () {
    var sale_id = 0;
    if ($(".order_table_holder .order_holder > .modification").length > 0) {
      var sale_id = $(
        ".order_table_holder .order_holder > .modification"
      ).html();
    }
    var selected_order_type_object = $(".main_top").find(
      "button[data-selected=selected]"
    );
    var total_items_in_cart = $(".order_holder .single_order").length;
    var sub_total = parseFloat($("#sub_total_show").html()).toFixed(2);
    var total_vat = parseFloat($("#all_items_vat").html()).toFixed(2);
    var total_payable = parseFloat($("#total_payable").html()).toFixed(2);
    var total_item_discount_amount = parseFloat(
      $("#total_item_discount").html()
    ).toFixed(2);
    var sub_total_with_discount = parseFloat(
      $("#discounted_sub_total_amount").html()
    ).toFixed(2);
    var sub_total_discount_amount = parseFloat(
      $("#sub_total_discount_amount").html()
    ).toFixed(2);
    var total_discount_amount = parseFloat(
      $("#all_items_discount").html()
    ).toFixed(2);
    var delivery_charge =
      $("#delivery_charge").val() != ""
        ? parseFloat($("#delivery_charge").val()).toFixed(2)
        : parseFloat(0).toFixed(2);
    var sub_total_discount_value = $("#sub_total_discount").val();
    var sub_total_discount_type = "";
    var customer_id = $("#walk_in_customer").val();
    var waiter_id = $("#select_waiter").val();
    var sale_vat_objects = [];
    $("#all_items_vat .tax_field").each(function (i, obj) {
      var tax_field_id = $(this).attr("id").substr(10);
      var tax_field_type = $(this).html();
      var tax_field_amount = $("#tax_field_amount_" + tax_field_id).html();
      sale_vat_objects.push({
        tax_field_id: tax_field_id,
        tax_field_type: tax_field_type,
        tax_field_amount: tax_field_amount,
      });
    });

    if (total_items_in_cart == 0) {
      swal({
        title: warning + "!",
        text: cart_empty,
        confirmButtonText: ok,
        confirmButtonColor: "#b6d6f6",
      });
      return false;
    }
    if (
      sub_total_discount_value.length > 0 &&
      sub_total_discount_value.substr(sub_total_discount_value.length - 1) ==
        "%"
    ) {
      sub_total_discount_type = "percentage";
    } else {
      sub_total_discount_type = "plain";
    }
    // var selected_table = 0;

    // if($(".single_table_div[data-table-checked=checked]").length>0){
    // 	selected_table = $(".single_table_div[data-table-checked=checked]").attr('id').substr(13);//1; //demo
    // }

    if (selected_order_type_object.length > 0) {
      var order_type = 1;
      if (selected_order_type_object.attr("id") == "delivery_button") {
        order_type = 3;
        if (customer_id == "") {
          swal({
            title: warning + "!",
            text: select_a_customer,
            confirmButtonText: ok,
            confirmButtonColor: "#b6d6f6",
          });
          return false;
        }
        if (customer_id == "1") {
          swal({
            title: warning + "!",
            text: delivery_not_possible_walk_in,
            confirmButtonText: ok,
            confirmButtonColor: "#b6d6f6",
          });
          return false;
        }

        var address_available = searchCustomerAddress(customer_id);
        if (address_available[0].customer_address == "") {
          swal({
            title: warning + "!",
            text: delivery_for_customer_must_address,
            confirmButtonText: ok,
            confirmButtonColor: "#b6d6f6",
          });
          return false;
        }
      } else if (selected_order_type_object.attr("id") == "dine_in_button") {
        order_type = 1;
        if (waiter_id == "") {
          swal({
            title: warning + "!",
            text: select_a_waiter,
            confirmButtonText: ok,
            confirmButtonColor: "#b6d6f6",
          });
          return false;
        }
        if (customer_id == "") {
          swal({
            title: "Alert!",
            text: select_a_customer,
            confirmButtonText: ok,
            confirmButtonColor: "#b6d6f6",
          });
          return false;
        }
      } else if (selected_order_type_object.attr("id") == "take_away_button") {
        order_type = 2;

        if (waiter_id == "") {
          swal({
            title: warning + "!",
            text: select_a_waiter,
            confirmButtonText: ok,
            confirmButtonColor: "#b6d6f6",
          });
          return false;
        }
        if (customer_id == "") {
          swal({
            title: warning + "!",
            text: select_a_customer,
            confirmButtonText: ok,
            confirmButtonColor: "#b6d6f6",
          });
          return false;
        }
      }

      var order_status = 1;

      var order_info = "{";
      order_info += '"customer_id":"' + customer_id + '",';
      order_info += '"waiter_id":"' + waiter_id + '",';
      order_info += '"total_items_in_cart":"' + total_items_in_cart + '",';
      order_info += '"sub_total":"' + sub_total + '",';
      order_info += '"total_vat":"' + total_vat + '",';
      order_info += '"total_payable":"' + total_payable + '",';
      order_info +=
        '"total_item_discount_amount":"' + total_item_discount_amount + '",';
      order_info +=
        '"sub_total_with_discount":"' + sub_total_with_discount + '",';
      order_info +=
        '"sub_total_discount_amount":"' + sub_total_discount_amount + '",';
      order_info += '"total_discount_amount":"' + total_discount_amount + '",';
      order_info += '"delivery_charge":"' + delivery_charge + '",';
      order_info +=
        '"sub_total_discount_value":"' + sub_total_discount_value + '",';
      order_info +=
        '"sub_total_discount_type":"' + sub_total_discount_type + '",';
      // order_info += '"selected_table":"'+selected_table+'",';
      order_info += '"order_type":"' + order_type + '",';
      order_info += '"order_status":"' + order_status + '",';
      order_info +=
        '"sale_vat_objects":' + JSON.stringify(sale_vat_objects) + ",";
      var orders_table = "";
      orders_table += '"orders_table":';
      orders_table += "[";
      var x = 1;
      var total_orders_table = $(".new_book_to_table").length;
      $(".new_book_to_table").each(function (i, obj) {
        var table_id = $(this).attr("id").substr(16);
        var person = $(this).find(".third_column").html();
        if (x == total_orders_table) {
          orders_table +=
            '{"table_id":"' + table_id + '", "persons":"' + person + '"}';
        } else {
          orders_table +=
            '{"table_id":"' + table_id + '", "persons":"' + person + '"},';
        }

        x++;
      });

      orders_table += "],";
      order_info += orders_table;
      var items_info = "";
      items_info += '"items":';
      items_info += "[";

      if ($(".order_holder .single_order").length > 0) {
        var k = 1;
        $(".order_holder .single_order").each(function (i, obj) {
          var item_id = $(this).attr("id").substr(15);
          var item_name = $(this)
            .find("#item_name_table_" + item_id)
            .html();
          var item_vat = $(this).find(".item_vat").html();
          var item_discount = $(this)
            .find("#percentage_table_" + item_id)
            .val();
          var discount_type = "";
          if (
            item_discount.length > 0 &&
            item_discount.substr(item_discount.length - 1) == "%"
          ) {
            discount_type = "percentage";
          } else {
            discount_type = "plain";
          }
          var item_previous_id = $(this)
            .find("#item_previous_id_table" + item_id)
            .html();
          var item_cooking_done_time = $(this)
            .find("#item_cooking_done_time_table" + item_id)
            .html();
          var item_cooking_start_time = $(this)
            .find("#item_cooking_start_time_table" + item_id)
            .html();
          var item_cooking_status = $(this)
            .find("#item_cooking_status_table" + item_id)
            .html();
          var item_type = $(this)
            .find("#item_type_table" + item_id)
            .html();
          var item_price_without_discount = $(this)
            .find(".item_price_without_discount")
            .html();
          var item_unit_price = $(this)
            .find("#item_price_table_" + item_id)
            .html();
          var item_quantity = $(this)
            .find("#item_quantity_table_" + item_id)
            .html();
          var item_price_with_discount = $(this)
            .find("#item_total_price_table_" + item_id)
            .html();
          var item_discount_amount = (
            parseFloat(item_price_without_discount) -
            parseFloat(item_price_with_discount)
          ).toFixed(2);

          items_info +=
            '{"item_id":"' +
            item_id +
            '", "item_name":"' +
            item_name +
            '", "item_vat":' +
            item_vat +
            ",";
          items_info +=
            '"item_discount":"' +
            item_discount +
            '","discount_type":"' +
            discount_type +
            '","item_price_without_discount":"' +
            item_price_without_discount +
            '",';
          items_info +=
            '"item_unit_price":"' +
            item_unit_price +
            '","item_quantity":"' +
            item_quantity +
            '",';
          items_info +=
            '"item_previous_id":"' +
            item_previous_id +
            '","item_cooking_done_time":"' +
            item_cooking_done_time +
            '",';
          items_info +=
            '"item_cooking_start_time":"' +
            item_cooking_start_time +
            '","item_cooking_status":"' +
            item_cooking_status +
            '","item_type":"' +
            item_type +
            '",';
          items_info +=
            '"item_price_with_discount":"' +
            item_price_with_discount +
            '","item_discount_amount":"' +
            item_discount_amount +
            '"';

          if ($(this).find(".second_portion").length > 0) {
            var modifiers_id = $(this)
              .find("#item_modifiers_id_table_" + item_id)
              .html();
            var modifiers_price = $(this)
              .find("#item_modifiers_price_table_" + item_id)
              .html();
            items_info +=
              ',"modifiers_id":"' +
              modifiers_id +
              '", "modifiers_price":"' +
              modifiers_price +
              '"';
          } else {
            items_info += ',"modifiers_id":"", "modifiers_price":""';
          }
          if ($(this).find(".third_portion").length > 0) {
            var item_note = $(this)
              .find("#item_note_table_" + item_id)
              .html();
            items_info += ',"item_note":"' + item_note + '"';
          } else {
            items_info += ',"item_note":""';
          }
          items_info +=
            k == $(".order_holder .single_order").length ? "}" : "},";
          k++;
        });
      }
      items_info += "]";
      order_info += items_info + "}";

      var order_object = JSON.stringify(order_info);

      add_sale_and_direct_invoice_by_ajax(order_object, sale_id);
    } else {
      swal({
        title: warning + "!",
        text: select_dine_take_delivery,
        confirmButtonText: ok,
        confirmButtonColor: "#b6d6f6",
      });
    }
  });

  $("#print_invoice,#order_details_create_invoice_button").on(
    "click",
    function () {
      if (
        $(".holder .order_details > .single_order[data-selected=selected]")
          .length > 0
      ) {
        var sale_id = $(
          ".holder .order_details .single_order[data-selected=selected]"
        )
          .attr("id")
          .substr(6);
        $.ajax({
          url: base_url + "Sale/get_all_information_of_a_sale_ajax",
          method: "POST",
          data: {
            sale_id: sale_id,
            csrf_irestoraplus: csrf_value_,
          },
          success: function (response) {
            response = JSON.parse(response);
            $("#finalize_total_payable").html(response.total_payable);
            $("#pay_amount_invoice_input").val(response.total_payable);
            $("#finalize_order_modal").show();
            $("#finalize_update_type").html("1"); //when 1 just update payment method and order status to 2 invoice order
            calculate_create_invoice_modal();
          },
          error: function () {
            alert(a_error);
          },
        });
      } else {
        swal({
          title: warning + "!",
          text: please_select_open_order,
          confirmButtonText: ok,
          confirmButtonColor: "#b6d6f6",
        });
      }
    }
  );
  $("#finalize_order_button").on("click", function () {
    var due_amount_invoice_input = Number($("#due_amount_invoice_input").val());
    var customer_id = $("#walk_in_customer").val();
    var status = true;
    if (customer_id == 1) {
      if (due_amount_invoice_input) {
        status = false;
      }
    }

    if (status == true) {
      $("#print_type").val(1);
      if (
        $(".holder .order_details > .single_order[data-selected=selected]")
          .length > 0
      ) {
        var sale_id = $(
          ".holder .order_details .single_order[data-selected=selected]"
        )
          .attr("id")
          .substr(6);
        var payment_method_type = $("#finalie_order_payment_method").val();
        var paid_amount = $("#pay_amount_invoice_input").val();
        var due_amount = $("#due_amount_invoice_input").val();
        var invoice_create_type = $("#finalize_update_type").html();
        if (payment_method_type == "") {
          $("#finalie_order_payment_method").css("border", "1px solid red");
          return false;
        }
        print_invoice_and_close(
          sale_id,
          payment_method_type,
          invoice_create_type,
          paid_amount,
          due_amount
        );
        reset_finalize_modal();
      } else {
        swal({
          title: warning + "!",
          text: please_select_open_order,
          confirmButtonText: ok,
          confirmButtonColor: "#b6d6f6",
        });
      }
    } else {
      swal({
        title: warning + "!",
        text: "Due amount not allow for walk in customer!",
        confirmButtonText: ok,
        confirmButtonColor: "#b6d6f6",
      });
    }
  });
  $("#add_customer").on("click", function () {
    var customer_id = $("#customer_id_modal").val();
    var customer_name = $("#customer_name_modal").val();
    var customer_phone = $("#customer_phone_modal").val();
    var customer_email = $("#customer_email_modal").val();
    var customer_dob = $("#customer_dob_modal").val();
    var customer_doa = $("#customer_doa_modal").val();
    var customer_delivery_address = $("#customer_delivery_address_modal").val();
    var customer_gst_number = $("#customer_gst_number_modal").val();
    var error = 0;

    $("#customer_name_modal").css("border", "1px solid #B5D6F6");
    $("#customer_phone_modal").css("border", "1px solid #B5D6F6");

    if (customer_name == "") {
      $("#customer_name_modal").css("border", "1px solid red");
      error++;
    }

    if (customer_phone == "") {
      $("#customer_phone_modal").css("border", "1px solid red");
      error++;
    }
    if (error != 0) {
      return false;
    }

    $.ajax({
      url: base_url + "Sale/add_customer_by_ajax",
      method: "POST",
      data: {
        customer_id: customer_id,
        customer_name: customer_name,
        customer_phone: customer_phone,
        customer_email: customer_email,
        customer_dob: customer_dob,
        customer_doa: customer_doa,
        customer_delivery_address: customer_delivery_address,
        customer_gst_number: customer_gst_number,
        csrf_irestoraplus: csrf_value_,
      },
      success: function (response) {
        if (response > 0) {
          $.ajax({
            url: base_url + "Sale/get_all_customers_for_this_user",
            method: "GET",
            success: function (response) {
              response = JSON.parse(response);
              var option_customers = "";
              var i = 1;
              var selected_id = "";
              var selected_name = "";
              for (var key in response) {
                if (i == response.length) {
                  // console.log(response[key].id+' '+response[key].name);
                  // selected_id = response[key].id;
                  // selected_name = response[key].name;
                  option_customers +=
                    '<option value="' +
                    response[key].id +
                    '" selected>' +
                    response[key].name +
                    " " +
                    response[key].phone +
                    "</option>";
                  var new_customer = {
                    customer_id: response[key].id, //your artist variable
                    customer_name: response[key].name, //your title variable
                    customer_address: response[key].address, //your title variable
                    gst_number: response[key].gst_number,
                  };
                  window.customers.push(new_customer);
                } else {
                  option_customers +=
                    '<option value="' +
                    response[key].id +
                    '">' +
                    response[key].name +
                    " " +
                    response[key].phone +
                    "</option>";
                }
                i++;
              }
              $("#walk_in_customer").html(option_customers);
              // $('#walk_in_customer').select2('data', {id: selected_id, text: selected_name});
              reset_on_modal_close_or_add_customer();
            },
            error: function () {
              alert(a_error);
            },
          });
        }
      },
      error: function () {
        alert(a_error);
      },
    });

    return false;
  });
  $("#modify_order").on("mouseover", function () {
    $("#modify_button_tool_tip").slideDown();
    $("#modify_button_tool_tip").css({
      "z-index": "6",
    });
  });

  $("#direct_invoice").on("mouseover", function () {
    $("#direct_invoice_button_tool_tip").slideDown();
  });
  $("#direct_invoice").on("mouseleave", function () {
    $("#direct_invoice_button_tool_tip").slideUp();
  });
  $("#modify_order").on("mouseleave", function () {
    $("#modify_button_tool_tip").slideUp();
  });
  //tooltip modify order button
  var modify_order_button = $("#modify_order");
  // var modify_order_button = modify_order_button.position();
  var modify_order_top =
    modify_order_button.offset().top - $(document).scrollTop();
  var modify_order_left =
    modify_order_button.offset().left - $(document).scrollLeft();
  var modify_order_width = (
    parseFloat(modify_order_button.width()) + parseFloat(30)
  ).toFixed(2);
  var modify_order_height = modify_order_button.height();
  var modify_order_half_height = (
    parseFloat(modify_order_height) / parseFloat(50)
  ).toFixed(2);
  var order_tool_tip_top = document
    .getElementById("modify_order")
    .getBoundingClientRect().top;

  $("#modify_button_tool_tip").css("top", order_tool_tip_top + "px");
  $("#modify_button_tool_tip").css("left", modify_order_width + "px");

  //tooltip direct invoice button
  var direct_invoice_button = $("#direct_invoice");
  // var direct_invoice_button = direct_invoice_button.position();
  var direct_invoice_top =
    direct_invoice_button.offset().top - $(document).scrollTop();
  var direct_invoice_left =
    direct_invoice_button.offset().left - $(document).scrollLeft();
  var direct_invoice_height = direct_invoice_button.height();
  var direct_invoice_tool_tip_top = (
    parseFloat(direct_invoice_top) -
    parseFloat(direct_invoice_height) -
    parseFloat(10)
  ).toFixed(2);
  // var direct_invoice_tool_tip_top_position = (parseFloat(direct_invoice_tool_tip_top)-parseFloat(direct_invoice_button_height)-parseFloat(20)).toFixed(2);
  $("#direct_invoice_button_tool_tip").css(
    "top",
    direct_invoice_tool_tip_top + "px"
  );
  $("#direct_invoice_button_tool_tip").css("left", direct_invoice_left + "px");
});

//update all price of modal
function update_all_total_price() {
  //get item quantity
  var item_quantity = parseFloat($("#item_quantity_modal").html()).toFixed(2);
  //get item unit price
  var item_unit_price = parseFloat($("#modal_item_price").html()).toFixed(2);
  //get item total price without discount
  var item_total_price_without_discount = (
    parseFloat(item_quantity) * parseFloat(item_unit_price)
  ).toFixed(2);
  //set item total price without discount
  $("#modal_item_price_variable_without_discount").html(
    item_total_price_without_discount
  );

  //get discount from modal
  var discount_on_modal = $("#modal_discount").val();
  discount_on_modal = discount_on_modal != "" ? discount_on_modal : 0;

  //remove last digits if number is more than 2 digits after decimal
  remove_last_two_digit_with_percentage(
    discount_on_modal,
    $("#modal_discount")
  );

  //get discount actual amount on item price
  var actual_modal_discount_amount = get_particular_item_discount_amount(
    discount_on_modal,
    item_total_price_without_discount
  );

  //set actual discount amouto hidden modal element
  $("#modal_discount_amount").html(
    parseFloat(actual_modal_discount_amount).toFixed(2)
  );

  //get item price after discount
  var item_price_after_discount = (
    parseFloat(item_total_price_without_discount) -
    parseFloat(actual_modal_discount_amount)
  ).toFixed(2);

  //set item total price with discount
  $("#modal_item_price_variable").html(item_price_after_discount);

  //get modifiers unit sum price
  var modifiers_unit_sum_price = parseFloat(
    $("#modal_modifiers_unit_price_variable").html()
  ).toFixed(2);

  //set modifiers price as per item quantity
  var modifiers_price = (
    parseFloat(modifiers_unit_sum_price) * parseFloat(item_quantity)
  ).toFixed(2);
  $("#modal_modifier_price_variable").html(modifiers_price);

  //add items and modifiers price
  var all_price = (
    parseFloat(item_price_after_discount) + parseFloat(modifiers_price)
  ).toFixed(2);

  //show to all total
  $("#modal_total_price").html(all_price);
}

// ==================================================
function show_all_items() {
  $(".specific_category_items_holder").hide();
  setTimeout(function () {
    var foundItems = searchItemAndConstructGallery("");
    var searched_category_items_to_show =
      '<div id="searched_item_found" class="specific_category_items_holder" style="display:flex;">';

    for (var key in foundItems) {
      if (foundItems.hasOwnProperty(key)) {
        searched_category_items_to_show +=
          '<div class="single_item fix" id="item_' +
          foundItems[key].item_id +
          '">';
        searched_category_items_to_show +=
          '<p class="item_price">' + foundItems[key].price + "</p>";
        searched_category_items_to_show +=
          '<span class="item_vat_percentage ir_display_none">' +
          foundItems[key].vat_percentage +
          "</span>";
        searched_category_items_to_show +=
          '<img src="' + foundItems[key].image + '" alt="" width="141">';
        searched_category_items_to_show +=
          '<p class="item_name">' +
          foundItems[key].item_name +
          " (" +
          foundItems[key].item_code +
          ")</p>";
        searched_category_items_to_show += "</div>";
      }
    }
    searched_category_items_to_show += "<div>";
    $("#searched_item_found").remove();
    $(".specific_category_items_holder").hide("1000");
    $(".category_items").prepend(searched_category_items_to_show);
  }, 100);

  //call this function to adjust the height of left side order list
  adjust_left_side_order_list();

  //call this function to adjust the height of right side item list
  adjust_right_side_item_list();

  adjust_middle_side_cart_list();
  $(".single_table_div").on("click", function () {
    if ($(this).attr("data-table-checked") != "busy") {
      $(
        ".single_table_div[data-table-checked=checked],.single_table_div[data-table-checked=unchecked]"
      ).attr("data-table-checked", "unchecked");
      $(
        ".single_table_div[data-table-checked=checked],.single_table_div[data-table-checked=unchecked]"
      ).css("background-color", "#ffffff");
      $(this).css("background-color", "#b6d6f6");
      $(this).attr("data-table-checked", "checked");
    }
  });
  $("#close_select_table_modal").on("click", function () {
    $(".single_table_div[data-table-checked=checked]").css(
      "background-color",
      "#ffffff"
    );
    $(".single_table_div[data-table-checked=checked]").attr(
      "data-table-checked",
      "unchecked"
    );
    $("#show_tables_modal").slideUp(333);
  });
  $("#selected_table_done").on("click", function () {
    $("#show_tables_modal").slideUp(333);
  });
  $("#refresh_order").on("click", function () {
    $(this).css("color", "#495057");
    $("#stop_refresh_for_search").html("yes");
    set_new_orders_to_view_for_interval();
  });
  $(document).on("click", ".holder .order_details .single_order", function () {
    var sale_id = $(this).attr("id").substr(6);
    $(".holder .order_details .single_order").attr(
      "data-selected",
      "unselected"
    );
    $(".holder .order_details .single_order").css(
      "background-color",
      "#ffffff"
    );
    $(this).attr("data-selected", "selected");
    $(this).css("background-color", "#b6d6f6");
    $("#refresh_order").css("color", "#dc3545");

    var sale_id = $(this).attr("id").substr(6);
    var flexible_div = $(this).find(".inside_single_order_container").height();
    $(".running_order_right_arrow").parent().parent().css("height", "18px");
    if (parseFloat(flexible_div) == parseFloat(18)) {
      $(this).find(".inside_single_order_container").css("height", "100%");
      $(this).find(".running_order_right_arrow").addClass("rotated");
    } else if (parseFloat(flexible_div) > parseFloat(18)) {
      $(this).find(".inside_single_order_container").css("height", "18px");
      $(this).find(".running_order_right_arrow").removeClass("rotated");
    }
  });
  $("#modify_order").on("click", function () {
    if (
      $(".holder .order_details > .single_order[data-selected=selected]")
        .length > 0
    ) {
      //get total items in cart
      var total_items_in_cart = $(".order_holder .single_order").length;

      if (total_items_in_cart > 0) {
        var r = confirm("Cart is not empty, want to proceed?");
        if (r == true) {
          $(".order_holder").empty();
          var sale_id = $(
            ".holder .order_details .single_order[data-selected=selected]"
          )
            .attr("id")
            .substr(6);
          get_details_of_a_particular_order(sale_id);
        }
      } else {
        var sale_id = $(
          ".holder .order_details .single_order[data-selected=selected]"
        )
          .attr("id")
          .substr(6);
        get_details_of_a_particular_order(sale_id);
      }
    } else {
      swal({
        title: warning + "!",
        text: please_select_open_order,
        confirmButtonText: ok,
        confirmButtonColor: "#b6d6f6",
      });
    }
  });
  $("#single_order_details").on("click", function () {
    if (
      $(".holder .order_details > .single_order[data-selected=selected]")
        .length > 0
    ) {
      var sale_id = $(
        ".holder .order_details .single_order[data-selected=selected]"
      )
        .attr("id")
        .substr(6);
      get_details_of_a_particular_order_for_modal(sale_id);
    } else {
      swal({
        title: warning + "!",
        text: please_select_open_order,
        confirmButtonText: ok,
        confirmButtonColor: "#b6d6f6",
      });
    }
  });
  $("#hold_sale").on("click", function () {
    if ($(".order_holder .single_order").length > 0) {
      $.ajax({
        url: base_url + "Sale/get_new_hold_number_ajax",
        method: "GET",
        success: function (response) {
          $("#generate_sale_hold_modal").show();
          $("#hold_generate_input").val(response);
        },
        error: function () {
          alert(a_error);
        },
      });
    } else {
      swal({
        title: warning + "!",
        text: cart_empty,
        confirmButtonText: ok,
        confirmButtonColor: "#b6d6f6",
      });
    }
    // $('#show_sale_hold_modal').show();
  });
  $("#close_hold_modal").on("click", function () {
    $("#generate_sale_hold_modal").hide();
    $("#hold_generate_input").val("");
    $("#hold_generate_input").css("border", "1px solid #a0a0a0");
  });
  $("#hold_cart_info").on("click", function () {
    var hold_number = $("#hold_generate_input").val();
    if (hold_number == "") {
      $("#hold_generate_input").css("border", "1px solid #dc3545");
      return false;
    } else {
      $("#hold_generate_input").css("border", "1px solid #a0a0a0");
    }
    var selected_order_type_object = $(".main_top")
      .find("button[data-selected=selected]")
      .attr("data-selected", "unselected");
    var total_items_in_cart = $(".order_holder .single_order").length;
    var sub_total = parseFloat($("#sub_total_show").html()).toFixed(2);
    var total_vat = parseFloat($("#all_items_vat").html()).toFixed(2);
    var total_payable = parseFloat($("#total_payable").html()).toFixed(2);
    var total_item_discount_amount = parseFloat(
      $("#total_item_discount").html()
    ).toFixed(2);
    var sub_total_with_discount = parseFloat(
      $("#discounted_sub_total_amount").html()
    ).toFixed(2);
    var sub_total_discount_amount = parseFloat(
      $("#sub_total_discount_amount").html()
    ).toFixed(2);
    var total_discount_amount = parseFloat(
      $("#all_items_discount").html()
    ).toFixed(2);
    var delivery_charge =
      $("#delivery_charge").val() != ""
        ? parseFloat($("#delivery_charge").val()).toFixed(2)
        : parseFloat(0).toFixed(2);
    var sub_total_discount_value = $("#sub_total_discount").val();
    var sub_total_discount_type = "";
    var sale_vat_objects = [];
    $("#all_items_vat .tax_field").each(function (i, obj) {
      var tax_field_id = $(this).attr("id").substr(10);
      var tax_field_type = $(this).html();
      var tax_field_amount = $("#tax_field_amount_" + tax_field_id).html();
      sale_vat_objects.push({
        tax_field_id: tax_field_id,
        tax_field_type: tax_field_type,
        tax_field_amount: tax_field_amount,
      });
    });
    if (total_items_in_cart == 0) {
      swal({
        title: warning + "!",
        text: cart_empty,
        confirmButtonText: ok,
        confirmButtonColor: "#b6d6f6",
      });
      return false;
    }
    if (
      sub_total_discount_value.length > 0 &&
      sub_total_discount_value.substr(sub_total_discount_value.length - 1) ==
        "%"
    ) {
      sub_total_discount_type = "percentage";
    } else {
      sub_total_discount_type = "plain";
    }
    var selected_table = 0;

    if ($(".single_table_div[data-table-checked=checked]").length > 0) {
      selected_table = $(".single_table_div[data-table-checked=checked]")
        .attr("id")
        .substr(13); //1; //demo
    }

    var order_type = 0;
    if (selected_order_type_object.attr("id") == "delivery_button") {
      order_type = 3;
    } else if (selected_order_type_object.attr("id") == "dine_in_button") {
      order_type = 1;
    } else if (selected_order_type_object.attr("id") == "take_away_button") {
      order_type = 2;
    }

    var customer_id = $("#walk_in_customer").val();
    var waiter_id = $("#select_waiter").val();

    var order_status = 1;

    var order_info = "{";
    order_info += '"customer_id":"' + customer_id + '",';
    order_info += '"waiter_id":"' + waiter_id + '",';
    order_info += '"total_items_in_cart":"' + total_items_in_cart + '",';
    order_info += '"sub_total":"' + sub_total + '",';
    order_info += '"total_vat":"' + total_vat + '",';
    order_info += '"total_payable":"' + total_payable + '",';
    order_info +=
      '"total_item_discount_amount":"' + total_item_discount_amount + '",';
    order_info +=
      '"sub_total_with_discount":"' + sub_total_with_discount + '",';
    order_info +=
      '"sub_total_discount_amount":"' + sub_total_discount_amount + '",';
    order_info += '"total_discount_amount":"' + total_discount_amount + '",';
    order_info += '"delivery_charge":"' + delivery_charge + '",';
    order_info +=
      '"sub_total_discount_value":"' + sub_total_discount_value + '",';
    order_info +=
      '"sub_total_discount_type":"' + sub_total_discount_type + '",';
    order_info += '"selected_table":"' + selected_table + '",';
    order_info += '"order_type":"' + order_type + '",';
    order_info += '"order_status":"' + order_status + '",';
    order_info +=
      '"sale_vat_objects":' + JSON.stringify(sale_vat_objects) + ",";
    var items_info = "";
    items_info += '"items":';
    items_info += "[";

    if ($(".order_holder .single_order").length > 0) {
      var k = 1;
      $(".order_holder .single_order").each(function (i, obj) {
        var item_id = $(this).attr("id").substr(15);
        var item_name = $(this)
          .find("#item_name_table_" + item_id)
          .html();
        var item_vat = $(this).find(".item_vat").html();
        var item_discount = $(this)
          .find("#percentage_table_" + item_id)
          .val();
        var discount_type = "";
        if (
          item_discount.length > 0 &&
          item_discount.substr(item_discount.length - 1) == "%"
        ) {
          discount_type = "percentage";
        } else {
          discount_type = "plain";
        }
        var item_price_without_discount = $(this)
          .find(".item_price_without_discount")
          .html();
        var item_unit_price = $(this)
          .find("#item_price_table_" + item_id)
          .html();
        var item_quantity = $(this)
          .find("#item_quantity_table_" + item_id)
          .html();
        var item_price_with_discount = $(this)
          .find("#item_total_price_table_" + item_id)
          .html();
        var item_discount_amount = (
          parseFloat(item_price_without_discount) -
          parseFloat(item_price_with_discount)
        ).toFixed(2);
        items_info +=
          '{"item_id":"' +
          item_id +
          '", "item_name":"' +
          item_name +
          '", "item_vat":' +
          item_vat +
          ",";
        items_info +=
          '"item_discount":"' +
          item_discount +
          '","discount_type":"' +
          discount_type +
          '","item_price_without_discount":"' +
          item_price_without_discount +
          '",';
        items_info +=
          '"item_unit_price":"' +
          item_unit_price +
          '","item_quantity":"' +
          item_quantity +
          '",';
        items_info +=
          '"item_price_with_discount":"' +
          item_price_with_discount +
          '","item_discount_amount":"' +
          item_discount_amount +
          '"';

        if ($(this).find(".second_portion").length > 0) {
          var modifiers_id = $(this)
            .find("#item_modifiers_id_table_" + item_id)
            .html();
          var modifiers_price = $(this)
            .find("#item_modifiers_price_table_" + item_id)
            .html();
          items_info +=
            ',"modifiers_id":"' +
            modifiers_id +
            '", "modifiers_price":"' +
            modifiers_price +
            '"';
        } else {
          items_info += ',"modifiers_id":"", "modifiers_price":""';
        }
        if ($(this).find(".third_portion").length > 0) {
          var item_note = $(this)
            .find("#item_note_table_" + item_id)
            .html();
          items_info += ',"item_note":"' + item_note + '"';
        } else {
          items_info += ',"item_note":""';
        }
        items_info += k == $(".order_holder .single_order").length ? "}" : "},";
        k++;
      });
    }
    items_info += "]";
    order_info += items_info + "}";

    var order_object = JSON.stringify(order_info);

    add_hold_by_ajax(order_object, hold_number);
  });
}

$(".marquee").marquee({
  //speed in milliseconds of the marquee
  duration: 5000,
  //gap in pixels between the tickers
  gap: 250,
  //time in milliseconds before the marquee will start animating
  delayBeforeStart: 0,
  //'left' or 'right'
  direction: "left",
  //true or false - should the marquee be duplicated to show an effect of continues flow
  duplicated: true,
});

//this function adjust the left side order list height
function adjust_left_side_order_list() {
  var left_side_button_holder_height = $(
    "#left_side_button_holder_absolute"
  ).height();
  var main_left_portion_height = $(".main_left").height();
  var header_of_left_portion_height = $(".main_left h3").height();
  var height_left_button_holder_and_header = (
    parseFloat(left_side_button_holder_height) +
    parseFloat(header_of_left_portion_height)
  ).toFixed(2);
  var remaining_height_for_list = (
    parseFloat(main_left_portion_height) -
    parseFloat(height_left_button_holder_and_header) -
    parseFloat(35)
  ).toFixed(2);
  $(".order_details")
    .slimscroll({
      height: remaining_height_for_list + "px",
    })
    .parent()
    .css({
      background: "#237FAD",
      border: "0px solid #184055",
    });
}
//this function adjust the left side order list height
function adjust_middle_side_cart_list() {
  var middle_side_bottom_holder_height = $("#bottom_absolute").height();
  var main_middle_portion_height = $(".main_middle").height();
  var order_table_header_row = $(".order_table_header_row").height();
  var header_of_middle_portion_height = $(".main_middle .main_top").height();
  var height_middle_bottom_holder_and_header = (
    parseFloat(middle_side_bottom_holder_height) +
    parseFloat(header_of_middle_portion_height) +
    parseFloat(order_table_header_row)
  ).toFixed(2);
  var remaining_height_for_cart = (
    parseFloat(main_middle_portion_height) -
    parseFloat(height_middle_bottom_holder_and_header)
  ).toFixed(2);
  $(".main_middle .order_holder").css(
    "height",
    remaining_height_for_cart + "px"
  );
}
//this function adjust the right side item list height
function adjust_right_side_item_list() {
  var main_right_portion_height = $(".main_right").height();
  var search_item_input_height = $("#search").height();
  var search_item_input_margin_bottom = parseFloat(
    $("#search").css("margin-bottom")
  );
  var select_category_height = $(".select_category").height();
  var select_category_margin_bottom = parseFloat(
    $(".select_category").css("margin-bottom")
  );
  var search_input_total_height = (
    parseFloat(search_item_input_height) +
    parseFloat(search_item_input_margin_bottom)
  ).toFixed(2);
  var select_category_total_height = (
    parseFloat(select_category_height) +
    parseFloat(select_category_margin_bottom)
  ).toFixed(2);
  var total_height_except_item_list = (
    parseFloat(search_input_total_height) +
    parseFloat(select_category_total_height)
  ).toFixed(2);
  var remaining_height_for_item_list = (
    parseFloat(main_right_portion_height) -
    parseFloat(total_height_except_item_list) -
    parseFloat(13)
  ).toFixed(2);
  $("#main_item_holder").css("height", remaining_height_for_item_list + "px");
  // ==========================================
  $(".category_items")
    .slimscroll({
      height: remaining_height_for_item_list + "px",
    })
    .parent()
    .css({
      border: "0px solid #184055",
    });
}

//KOT

$("#kot_list_holder")
  .slimscroll({
    height: "250px",
  })
  .parent()
  .css({
    background: "none",
    border: "0px solid #184055",
  });
$("#kot_check_all").on("change", function () {
  if ($(this).is(":checked")) {
    $(".kot_item_checkbox").prop("checked", true);
  } else {
    $(".kot_item_checkbox").prop("checked", false);
  }
});

function print_kot(sale_id) {
  let newWindow = open(
    "print_kot/" + sale_id,
    "Print KOT",
    "width=450,height=550"
  );
  newWindow.focus();

  newWindow.onload = function () {
    newWindow.document.body.insertAdjacentHTML("afterbegin");
  };
}

$("#print_kot_modal").on("click", function () {
  var order_number = $("#kot_modal_order_number").html();
  var order_date = $("#kot_modal_order_date").html();
  var customer_name = $("#kot_modal_customer_name").html();
  var table_name = $("#kot_modal_table_name").html();
  var waiter_name = $("#kot_modal_waiter_name").html();
  var order_type = $("#kot_modal_order_type").html();

  var order_info = "{";
  order_info += '"order_number":"' + order_number + '",';
  order_info += '"order_date":"' + order_date + '",';
  order_info += '"customer_name":"' + customer_name + '",';
  order_info += '"table_name":"' + table_name + '",';
  order_info += '"waiter_name":"' + waiter_name + '",';
  order_info += '"order_type":"' + order_type + '",';
  var items_info = "";
  items_info += '"items":';
  items_info += "[";

  var order_details_id = "";
  var j = 1;
  var checkbox_length = $(
    ".single_kot_row .kot_check_column .kot_item_checkbox:checked"
  ).length;
  $(".single_kot_row .kot_check_column .kot_item_checkbox:checked").each(
    function (i, obj) {
      if (j == checkbox_length) {
        order_details_id += $(this).val();
      } else {
        order_details_id += $(this).val() + ",";
      }
      j++;
    }
  );
  if (order_details_id != "") {
    var order_details_id_array = order_details_id.split(",");
    k = 1;
    var item_array_length = order_details_id_array.length;
    order_details_id_array.forEach(function (entry) {
      var single_kot_row = $("#kot_for_item_" + entry);
      var kot_item_name = single_kot_row.find(".kot_item_name_column").html();
      var kot_item_qty = $("#kot_modal_item_qty_" + entry).html();
      var tmp_qty = $("#tmp_qty_" + entry).val();
      var p_qty = $("#p_qty_" + entry).val();

      items_info +=
        '{"kot_item_name":"' +
        kot_item_name +
        '", "kot_item_qty":"' +
        kot_item_qty +
        '", "tmp_qty":"' +
        tmp_qty +
        '", "p_qty":"' +
        p_qty +
        '"';
      var modifiers = "";
      var m = 1;
      $(".single_modifier:visible").each(function (i, obj) {
        var this_id = $(this).attr("data-item-detail-id");
        if (this_id == entry) {
          modifiers += $(this).html() + ",";
        }
      });
      var tmp_note = $("#kot_modal_note_" + entry).val();
      items_info += ',"modifiers":"' + modifiers + '"';
      items_info += ',"notes":"' + tmp_note + '"';
      items_info += k == item_array_length ? "}" : "},";
      k++;
    });
  }
  items_info += "]";
  order_info += items_info + "}";

  var order_object = JSON.stringify(order_info);

  $.ajax({
    url: base_url + "Sale/add_temp_kot_ajax",
    method: "POST",
    data: {
      order: order_object,
      csrf_irestoraplus: csrf_value_,
    },
    success: function (response) {
      if (response > 0) {
        $("#kot_list_modal").slideUp(333);
        let newWindow = open(
          "print_temp_kot/" + response,
          "Print KOT",
          "width=450,height=550"
        );
        newWindow.focus();

        newWindow.onload = function () {
          newWindow.document.body.insertAdjacentHTML("afterbegin");
        };
      }
    },
    error: function () {
      alert(a_error);
    },
  });
});

$(document).on("click", ".kot_content_column .single_modifier", function () {
  var current_selection = $(this).attr("data-selected");
  if (current_selection == "selected") {
    $(this).attr("data-selected", "unselected");
    $(this).css("background-color", "#E0E0E0");
  } else if (current_selection == "unselected") {
    $(this).attr("data-selected", "selected");
    $(this).css("background-color", "#B5D6F6");
  }
});
$("#print_kot").on("click", function () {
  if (
    $(".holder .order_details > .single_order[data-selected=selected]").length >
    0
  ) {
    var sale_id = $(
      ".holder .order_details .single_order[data-selected=selected]"
    )
      .attr("id")
      .substr(6);
    get_details_of_a_particular_order_for_kot_modal(sale_id);
    $("#kot_check_all").prop("checked", true);
    $("#kot_list_modal").slideDown(333);
  } else {
    swal({
      title: warning + "!",
      text: please_select_open_order,
      confirmButtonText: ok,
      confirmButtonColor: "#b6d6f6",
    });
  }
});
$("#order_details_print_kot_button").on("click", function () {
  if (
    $(".holder .order_details > .single_order[data-selected=selected]").length >
    0
  ) {
    var sale_id = $(
      ".holder .order_details .single_order[data-selected=selected]"
    )
      .attr("id")
      .substr(6);
    print_kot(sale_id);
  } else {
    swal({
      title: warning + "!",
      text: please_select_open_order,
      confirmButtonText: ok,
      confirmButtonColor: "#b6d6f6",
    });
  }
});
$(document).on("click", ".decrease_item_kot_modal", function () {
  var item_detail_id = $(this).attr("id").substr(24);
  var qty_element = $("#kot_modal_item_qty_" + item_detail_id);
  var qty_element_fixed = $("#kot_modal_item_qty_fixed_" + item_detail_id);
  var qty = parseInt(qty_element.html());
  if (qty > 1) {
    qty--;
  }
  qty_element.html(qty);
});
$(document).on("click", ".increase_item_kot_modal", function () {
  var item_detail_id = $(this).attr("id").substr(24);
  var qty_element = $("#kot_modal_item_qty_" + item_detail_id);
  var qty_element_fixed = $("#kot_modal_item_qty_fixed_" + item_detail_id);
  var qty = parseInt(qty_element.html());
  var qty_fixed = parseInt(qty_element_fixed.html());
  if (qty < qty_fixed) {
    qty++;
  }

  qty_element.html(qty);
});
$("#cancel_kot_modal").on("click", function () {
  $("#kot_check_all").prop("checked", false);
  $("#kot_modal_modified_or_not").hide();
  $("#kot_list_modal").slideUp(333);
});
$("#cancel_kot_modal2").on("click", function () {
  $("#kot_check_all").prop("checked", false);
  $("#kot_modal_modified_or_not").hide();
  $("#kot_list_modal").slideUp(333);
});

function get_details_of_a_particular_order_for_kot_modal(sale_id) {
  $.ajax({
    url: base_url + "Sale/get_all_information_of_a_sale_ajax",
    method: "POST",
    data: {
      sale_id: sale_id,
      csrf_irestoraplus: csrf_value_,
    },
    success: function (response) {
      response = JSON.parse(response);
      var order_type = "";
      var order_type_id = 0;
      var order_number = "";
      var tables_booked = "";
      if (response.tables_booked.length > 0) {
        var w = 1;
        for (var k in response.tables_booked) {
          var single_table = response.tables_booked[k];
          if (w == response.tables_booked.length) {
            tables_booked += single_table.table_name;
          } else {
            tables_booked += single_table.table_name + ", ";
          }
          w++;
        }
      } else {
        tables_booked = "None";
      }
      $("#kot_modal_customer_id").html(response.customer_id);
      $("#kot_modal_customer_name").html(response.customer_name);
      $("#kot_modal_waiter_name").html(response.waiter_name);
      $("#kot_modal_order_date").html(response.date_time);

      $("#kot_modal_table_name").html(tables_booked);

      if (response.modified == "Yes") {
        $("#kot_modal_modified_or_not").show();
      }

      if (response.order_type == 1) {
        order_type = "Dine In";
        order_type_id = 1;
        order_number = "A " + response.sale_no;
      } else if (response.order_type == 2) {
        order_type = "Take Away";
        order_type_id = 2;
        order_number = "B " + response.sale_no;
      } else if (response.order_type == 3) {
        order_type = "Delivery";
        order_type_id = 3;
        order_number = "C " + response.sale_no;
      }
      $("#kot_modal_order_number").html(order_number);
      $("#kot_modal_order_type").html(order_type);
      var draw_table_for_kot_modal = "";

      for (var key in response.items) {
        //construct div
        var this_item = response.items[key];

        var selected_modifiers = "";
        var selected_modifiers_id = "";
        var selected_modifiers_price = "";
        var modifiers_price = 0;
        var total_modifier = this_item.modifiers.length;
        var i = 1;
        for (var mod_key in this_item.modifiers) {
          var this_modifier = this_item.modifiers[mod_key];
          //get selected modifiers
          if (i == total_modifier) {
            selected_modifiers += this_modifier.name;
            selected_modifiers_id += this_modifier.modifier_id;
            selected_modifiers_price += this_modifier.modifier_price;
            modifiers_price = (
              parseFloat(modifiers_price) +
              parseFloat(this_modifier.modifier_price)
            ).toFixed(2);
          } else {
            selected_modifiers += this_modifier.name + ",";
            selected_modifiers_id += this_modifier.modifier_id + ",";
            selected_modifiers_price += this_modifier.modifier_price + ",";
            modifiers_price = (
              parseFloat(modifiers_price) +
              parseFloat(this_modifier.modifier_price)
            ).toFixed(2);
          }
          i++;
        }
        var selected_modifiers_array = selected_modifiers.split(",");
        var selected_modifiers_id_array = selected_modifiers_id.split(",");
        var selected_modifiers_price_array = selected_modifiers_price.split(
          ","
        );
        var modifier_divs = "";
        var modifier_length = selected_modifiers_array.length;
        if (modifier_length > 0) {
          for (var index = 0; index < modifier_length; index++) {
            modifier_divs +=
              selected_modifiers_array[index] != ""
                ? "<span>" + selected_modifiers_array[index] + "</span>, "
                : "";
          }
        }
        var discount_value =
          this_item.menu_discount_value != ""
            ? this_item.menu_discount_value
            : "0.00";
        if (this_item.item_type != "Bar Item") {
          draw_table_for_kot_modal +=
            '<div class="single_kot_row fix" id="kot_for_item_' +
            this_item.id +
            '">';
          draw_table_for_kot_modal +=
            '<div class="kot_content_column fix floatleft kot_check_column">';
          draw_table_for_kot_modal +=
            '<input checked class="kot_item_checkbox" id="kot_item_checkbox_' +
            this_item.id +
            '" type="checkbox" name="" value="' +
            this_item.id +
            '">';
          draw_table_for_kot_modal += "</div>";
          draw_table_for_kot_modal +=
            '<input type="hidden" value="' +
            this_item.menu_note +
            '" id="kot_modal_note_' +
            this_item.id +
            '"><div style="width: 405px" class="kot_content_column fix floatleft kot_item_name_column">';
          draw_table_for_kot_modal += this_item.menu_name;
          draw_table_for_kot_modal += modifier_divs
            ? "<br>Modifiers: " + modifier_divs
            : "";
          draw_table_for_kot_modal += this_item.menu_note
            ? "<br>Notes: " + this_item.menu_note
            : "";
          draw_table_for_kot_modal += "</div>";
          draw_table_for_kot_modal +=
            '<div class="kot_content_column fix floatleft kot_qty_column">';
          draw_table_for_kot_modal +=
            '<i style="cursor:pointer;" id="decrease_item_kot_modal_' +
            this_item.id +
            '" class="fas fa-minus-circle decrease_item_kot_modal"></i>';
          draw_table_for_kot_modal +=
            ' <span style="display:none" id="kot_modal_item_qty_fixed_' +
            this_item.id +
            '">' +
            this_item.qty +
            '</span><span id="kot_modal_item_qty_' +
            this_item.id +
            '">' +
            this_item.qty +
            '</span> <input type="hidden" name="tmp_qty" value="' +
            this_item.tmp_qty +
            '" id="tmp_qty_' +
            this_item.id +
            '"><input type="hidden" name="p_qty" value="' +
            this_item.qty +
            '" id="p_qty_' +
            this_item.id +
            '">';
          draw_table_for_kot_modal +=
            '<i style="cursor:pointer;" id="increase_item_kot_modal_' +
            this_item.id +
            '" class="fas fa-plus-circle increase_item_kot_modal"></i>';
          draw_table_for_kot_modal += "</div>";
          draw_table_for_kot_modal += "</div>";
        }
      }
      //add to top
      $("#kot_list_holder").empty();
      $("#kot_list_holder").prepend(draw_table_for_kot_modal);
    },
    error: function () {
      alert(a_error);
    },
  });
}

//BOT

$("#bot_list_holder")
  .slimscroll({
    height: "250px",
  })
  .parent()
  .css({
    background: "none",
    border: "0px solid #184055",
  });
$("#bot_check_all").on("change", function () {
  if ($(this).is(":checked")) {
    $(".bot_item_checkbox").prop("checked", true);
  } else {
    $(".bot_item_checkbox").prop("checked", false);
  }
});

function print_bot(sale_id) {
  let newWindow = open(
    "print_bot/" + sale_id,
    "Print BOT",
    "width=450,height=550"
  );
  newWindow.focus();

  newWindow.onload = function () {
    newWindow.document.body.insertAdjacentHTML("afterbegin");
  };
}

$("#print_bot_modal").on("click", function () {
  var order_number = $("#bot_modal_order_number").html();
  var order_date = $("#bot_modal_order_date").html();
  var customer_name = $("#bot_modal_customer_name").html();
  var table_name = $("#bot_modal_table_name").html();
  var waiter_name = $("#bot_modal_waiter_name").html();
  var order_type = $("#bot_modal_order_type").html();

  var order_info = "{";
  order_info += '"order_number":"' + order_number + '",';
  order_info += '"order_date":"' + order_date + '",';
  order_info += '"customer_name":"' + customer_name + '",';
  order_info += '"table_name":"' + table_name + '",';
  order_info += '"waiter_name":"' + waiter_name + '",';
  order_info += '"order_type":"' + order_type + '",';
  var items_info = "";
  items_info += '"items":';
  items_info += "[";

  var order_details_id = "";
  var j = 1;
  var checkbox_length = $(
    ".single_bot_row .bot_check_column .bot_item_checkbox:checked"
  ).length;
  $(".single_bot_row .bot_check_column .bot_item_checkbox:checked").each(
    function (i, obj) {
      if (j == checkbox_length) {
        order_details_id += $(this).val();
      } else {
        order_details_id += $(this).val() + ",";
      }
      j++;
    }
  );
  if (order_details_id != "") {
    var order_details_id_array = order_details_id.split(",");
    k = 1;
    var item_array_length = order_details_id_array.length;

    order_details_id_array.forEach(function (entry) {
      var single_bot_row = $("#bot_for_item_" + entry);
      var bot_item_name = single_bot_row.find(".bot_item_name_column").html();
      var bot_item_qty = $("#bot_modal_item_qty_" + entry).html();
      var tmp_qty = $("#tmp_qty_" + entry).val();
      var p_qty = $("#p_qty_" + entry).val();

      items_info +=
        '{"bot_item_name":"' +
        bot_item_name +
        '", "bot_item_qty":"' +
        bot_item_qty +
        '", "tmp_qty":"' +
        tmp_qty +
        '", "p_qty":"' +
        p_qty +
        '"';
      var modifiers = "";
      var m = 1;
      $(".single_modifier:visible").each(function (i, obj) {
        var this_id = $(this).attr("data-item-detail-id");
        if (this_id == entry) {
          modifiers += $(this).html() + ",";
        }
      });
      /*$('.single_modifier[data-item-detail-id=' + entry + '][data-selected=selected]').each(function (i, obj) {
                if (m == $('.single_modifier[data-item-detail-id=' + entry + '][data-selected=selected]').length) {
                    modifiers += $(this).html();
                } else {
                    modifiers += $(this).html() + ',';
                }

                m++;
            });*/
      var tmp_note = $("#bot_modal_note_" + entry).val();
      items_info += ',"modifiers":"' + modifiers + '"';
      items_info += ',"notes":"' + tmp_note + '"';
      items_info += k == item_array_length ? "}" : "},";
      k++;
    });
  }
  items_info += "]";
  order_info += items_info + "}";

  var order_object = JSON.stringify(order_info);

  $.ajax({
    url: base_url + "Sale/add_temp_bot_ajax",
    method: "POST",
    data: {
      order: order_object,
      csrf_irestoraplus: csrf_value_,
    },
    success: function (response) {
      if (response > 0) {
        $("#bot_list_modal").slideUp(333);
        let newWindow = open(
          "print_temp_bot/" + response,
          "Print BOT",
          "width=450,height=550"
        );
        newWindow.focus();

        newWindow.onload = function () {
          newWindow.document.body.insertAdjacentHTML("afterbegin");
        };
      }
    },
    error: function () {
      alert(a_error);
    },
  });
});

$(document).on("click", ".bot_content_column .single_modifier", function () {
  var current_selection = $(this).attr("data-selected");
  if (current_selection == "selected") {
    $(this).attr("data-selected", "unselected");
    $(this).css("background-color", "#E0E0E0");
  } else if (current_selection == "unselected") {
    $(this).attr("data-selected", "selected");
    $(this).css("background-color", "#B5D6F6");
  }
});
$("#print_bot").on("click", function () {
  if (
    $(".holder .order_details > .single_order[data-selected=selected]").length >
    0
  ) {
    var sale_id = $(
      ".holder .order_details .single_order[data-selected=selected]"
    )
      .attr("id")
      .substr(6);
    get_details_of_a_particular_order_for_bot_modal(sale_id);
    $("#bot_check_all").prop("checked", true);
    $("#bot_list_modal").slideDown(333);
  } else {
    swal({
      title: warning + "!",
      text: please_select_open_order,
      confirmButtonText: ok,
      confirmButtonColor: "#b6d6f6",
    });
  }
});
$("#order_details_print_bot_button").on("click", function () {
  if (
    $(".holder .order_details > .single_order[data-selected=selected]").length >
    0
  ) {
    var sale_id = $(
      ".holder .order_details .single_order[data-selected=selected]"
    )
      .attr("id")
      .substr(6);
    print_bot(sale_id);
  } else {
    swal({
      title: warning + "!",
      text: please_select_open_order,
      confirmButtonText: ok,
      confirmButtonColor: "#b6d6f6",
    });
  }
});
$(document).on("click", ".decrease_item_bot_modal", function () {
  var item_detail_id = $(this).attr("id").substr(24);
  var qty_element = $("#bot_modal_item_qty_" + item_detail_id);
  var qty_element_fixed = $("#bot_modal_item_qty_fixed_" + item_detail_id);
  var qty = parseInt(qty_element.html());
  if (qty > 1) {
    qty--;
  }
  qty_element.html(qty);
});
$(document).on("click", ".increase_item_bot_modal", function () {
  var item_detail_id = $(this).attr("id").substr(24);
  var qty_element = $("#bot_modal_item_qty_" + item_detail_id);
  var qty_element_fixed = $("#bot_modal_item_qty_fixed_" + item_detail_id);
  var qty = parseInt(qty_element.html());
  var qty_fixed = parseInt(qty_element_fixed.html());
  if (qty < qty_fixed) {
    qty++;
  }

  qty_element.html(qty);
});
$("#cancel_bot_modal").on("click", function () {
  $("#bot_check_all").prop("checked", false);
  $("#bot_modal_modified_or_not").hide();
  $("#bot_list_modal").slideUp(333);
});
$("#cancel_bot_modal2").on("click", function () {
  $("#bot_check_all").prop("checked", false);
  $("#bot_modal_modified_or_not").hide();
  $("#bot_list_modal").slideUp(333);
});

function get_details_of_a_particular_order_for_bot_modal(sale_id) {
  $.ajax({
    url: base_url + "Sale/get_all_information_of_a_sale_ajax",
    method: "POST",
    data: {
      sale_id: sale_id,
      csrf_irestoraplus: csrf_value_,
    },
    success: function (response) {
      response = JSON.parse(response);
      var order_type = "";
      var order_type_id = 0;
      var order_number = "";
      var tables_booked = "";
      if (response.tables_booked.length > 0) {
        var w = 1;
        for (var k in response.tables_booked) {
          var single_table = response.tables_booked[k];
          if (w == response.tables_booked.length) {
            tables_booked += single_table.table_name;
          } else {
            tables_booked += single_table.table_name + ", ";
          }
          w++;
        }
      } else {
        tables_booked = "None";
      }
      $("#bot_modal_customer_id").html(response.customer_id);
      $("#bot_modal_customer_name").html(response.customer_name);
      $("#bot_modal_waiter_name").html(response.waiter_name);
      $("#bot_modal_order_date").html(response.date_time);

      $("#bot_modal_table_name").html(tables_booked);

      if (response.modified == "Yes") {
        $("#bot_modal_modified_or_not").show();
      }

      if (response.order_type == 1) {
        order_type = "Dine In";
        order_type_id = 1;
        order_number = "A " + response.sale_no;
      } else if (response.order_type == 2) {
        order_type = "Take Away";
        order_type_id = 2;
        order_number = "B " + response.sale_no;
        $("#bot_modal_waiter_name").html("None");
      } else if (response.order_type == 3) {
        order_type = "Delivery";
        order_type_id = 3;
        order_number = "C " + response.sale_no;
        $("#bot_modal_waiter_name").html("None");
      }
      $("#bot_modal_order_number").html(order_number);
      $("#bot_modal_order_type").html(order_type);
      var draw_table_for_bot_modal = "";

      for (var key in response.items) {
        //construct div
        var this_item = response.items[key];

        var selected_modifiers = "";
        var selected_modifiers_id = "";
        var selected_modifiers_price = "";
        var modifiers_price = 0;
        var total_modifier = this_item.modifiers.length;
        var i = 1;
        for (var mod_key in this_item.modifiers) {
          var this_modifier = this_item.modifiers[mod_key];
          //get selected modifiers
          if (i == total_modifier) {
            selected_modifiers += this_modifier.name;
            selected_modifiers_id += this_modifier.modifier_id;
            selected_modifiers_price += this_modifier.modifier_price;
            modifiers_price = (
              parseFloat(modifiers_price) +
              parseFloat(this_modifier.modifier_price)
            ).toFixed(2);
          } else {
            selected_modifiers += this_modifier.name + ",";
            selected_modifiers_id += this_modifier.modifier_id + ",";
            selected_modifiers_price += this_modifier.modifier_price + ",";
            modifiers_price = (
              parseFloat(modifiers_price) +
              parseFloat(this_modifier.modifier_price)
            ).toFixed(2);
          }
          i++;
        }
        var selected_modifiers_array = selected_modifiers.split(",");
        var selected_modifiers_id_array = selected_modifiers_id.split(",");
        var selected_modifiers_price_array = selected_modifiers_price.split(
          ","
        );
        var modifier_divs = "";
        var modifier_length = selected_modifiers_array.length;
        if (modifier_length > 0) {
          for (var index = 0; index < modifier_length; index++) {
            modifier_divs +=
              selected_modifiers_array[index] != ""
                ? "<span>" + selected_modifiers_array[index] + "</span>, "
                : "";
          }
        }

        var discount_value =
          this_item.menu_discount_value != ""
            ? this_item.menu_discount_value
            : "0.00";
        if (this_item.item_type == "Bar Item") {
          draw_table_for_bot_modal +=
            '<div class="single_bot_row fix" id="bot_for_item_' +
            this_item.id +
            '">';
          draw_table_for_bot_modal +=
            '<div class="bot_content_column fix floatleft bot_check_column">';
          draw_table_for_bot_modal +=
            '<input checked class="bot_item_checkbox" id="bot_item_checkbox_' +
            this_item.id +
            '" type="checkbox" name="" value="' +
            this_item.id +
            '">';
          draw_table_for_bot_modal += "</div>";
          draw_table_for_bot_modal +=
            '<input type="hidden" value="' +
            this_item.menu_note +
            '" id="bot_modal_note_' +
            this_item.id +
            '"> <div style="width: 405px" class="bot_content_column fix floatleft bot_item_name_column">';
          draw_table_for_bot_modal += this_item.menu_name;
          draw_table_for_bot_modal += modifier_divs
            ? "<br>Modifiers: " + modifier_divs
            : "";
          draw_table_for_bot_modal += this_item.menu_note
            ? "<br>Notes: " + this_item.menu_note
            : "";
          draw_table_for_bot_modal += "</div>";
          draw_table_for_bot_modal +=
            '<div class="bot_content_column fix floatleft bot_qty_column">';
          draw_table_for_bot_modal +=
            '<i style="cursor:pointer;" id="decrease_item_bot_modal_' +
            this_item.id +
            '" class="fas fa-minus-circle decrease_item_bot_modal"></i>';
          draw_table_for_bot_modal +=
            ' <span style="display:none" id="bot_modal_item_qty_fixed_' +
            this_item.id +
            '">' +
            this_item.qty +
            '</span><span id="bot_modal_item_qty_' +
            this_item.id +
            '">' +
            this_item.qty +
            '</span> <input type="hidden" name="tmp_qty" value="' +
            this_item.tmp_qty +
            '" id="tmp_qty_' +
            this_item.id +
            '"><input type="hidden" name="p_qty" value="' +
            this_item.qty +
            '" id="p_qty_' +
            this_item.id +
            '">';
          draw_table_for_bot_modal +=
            '<i style="cursor:pointer;" id="increase_item_bot_modal_' +
            this_item.id +
            '" class="fas fa-plus-circle increase_item_bot_modal"></i>';
          draw_table_for_bot_modal += "</div>";
          draw_table_for_bot_modal += "</div>";
        }
      }

      //add to top
      $("#bot_list_holder").empty();
      $("#bot_list_holder").prepend(draw_table_for_bot_modal);
    },
    error: function () {
      alert(a_error);
    },
  });
}

$(window).on("resize", function () {
  var height_should_be =
    parseInt($(window).height()) -
    parseInt($(".top_header_part").height()) -
    20;
  $(".main_left").css("height", height_should_be + "px");
  $(".main_middle").css("height", height_should_be + "px");
  $(".main_right").css("height", height_should_be - 10 + "px");

  adjust_left_side_order_list();
  adjust_right_side_item_list();
  adjust_middle_side_cart_list();
});

function calculate_create_invoice_modal() {
  var total_payable = $("#finalize_total_payable").html();
  var paid_amount =
    $("#pay_amount_invoice_input").val() != ""
      ? $("#pay_amount_invoice_input").val()
      : 0;
  var due_amount = (
    parseFloat(total_payable) - parseFloat(paid_amount)
  ).toFixed(2);
  $("#due_amount_invoice_input").val(due_amount);
}

// ===============================================
// add all prices of item and modifiers
function do_addition_of_item_and_modifiers_price() {
  //set all hidden discount amount and original item price
  set_all_hidden_item_discount_amount();

  //set visible discounted item price to table
  set_all_visible_discounted_item_price();

  //set total discount amount of items
  set_total_items_discount_for_subtotal();

  //set all hidden discount amount and original item price
  set_all_items_modifiers_amount();

  var total_of_all_items_and_modifiers = get_total_of_all_items_and_modifiers();
  //get total items in cart
  var total_items_in_cart = $(".order_holder .single_order").length;
  //set row number for every single item
  $(".order_holder .single_order").each(function (i, obj) {
    $(this).attr("data-single-order-row-no", i + 1);
  });
  //if there is no item in cart reset necessary field and value
  if (total_items_in_cart < 1) {
    $("#discounted_sub_total_amount").html("0.00");
    $("#sub_total_discount").val("");
    $("#sub_total_discount_amount").html("0.00");
    $("#all_items_discount").html("0.00");
    $("#delivery_charge").val("");
  }
  var total_items_in_cart_with_quantity = 0;
  $(".order_holder .single_order .first_portion .third_column span").each(
    function (i, obj) {
      total_items_in_cart_with_quantity =
        parseInt(total_items_in_cart_with_quantity) + parseInt($(this).html());
    }
  );

  //set total items in cart to view
  $("#total_items_in_cart").html(total_items_in_cart);
  $("#total_items_in_cart_with_quantity").html(
    total_items_in_cart_with_quantity
  );
  //set sub total
  $("#sub_total").html(total_of_all_items_and_modifiers);
  $("#sub_total_show").html(total_of_all_items_and_modifiers);

  //get the value of the delivery charge amount
  var delivery_charge_amount =
    $("#delivery_charge").val() != "" ? $("#delivery_charge").val() : 0;
  //check wether value is valid or not
  remove_last_two_digit_without_percentage(
    delivery_charge_amount,
    $("#delivery_charge")
  );

  //get subtotal amount
  var sub_total_amount = $("#sub_total").html();

  //get subtotal discount amount
  var sub_total_discount_amount =
    $("#sub_total_discount").val() != "" ? $("#sub_total_discount").val() : 0;

  //check wether value is valid or not
  remove_last_two_digit_with_percentage(
    sub_total_discount_amount,
    $("#sub_total_discount")
  );
  sub_total_discount_amount = get_sub_total_discount_amount(
    sub_total_discount_amount,
    sub_total_amount
  );

  //if sub total discount amount is greater than subtotal then make it blank
  if (parseFloat(sub_total_discount_amount) > parseFloat(sub_total_amount)) {
    $("#sub_total_discount").val("");
    do_addition_of_item_and_modifiers_price();
    return false;
  }
  //get total item discount amount
  var total_item_discount_amount = parseFloat(
    $("#total_item_discount").html()
  ).toFixed(2);

  //get all discount of table
  var total_discount = (
    parseFloat(sub_total_discount_amount) +
    parseFloat(total_item_discount_amount)
  ).toFixed(2);

  //set sub total discount amount
  $("#sub_total_discount_amount").html(sub_total_discount_amount);

  //set sub total amount after discount in a hidden field
  var discounted_sub_total_amount = (
    parseFloat(sub_total_amount) - parseFloat(sub_total_discount_amount)
  ).toFixed(2);
  $("#discounted_sub_total_amount").html(discounted_sub_total_amount);

  //get vat amount
  var vat_amount = collect_tax == "Yes" ? get_total_vat() : null;

  var total_vat_section_to_show = "";
  $.each(vat_amount, function (key, value) {
    total_vat_section_to_show +=
      '<span class="tax_field" id="tax_field_' +
      key.substring(key.indexOf("_") + 1) +
      '">' +
      key.substr(0, key.indexOf("_")) +
      "</span>: " +
      currency +
      ' <span class="tax_field_amount" id="tax_field_amount_' +
      key.substring(key.indexOf("_") + 1) +
      '">' +
      value +
      "</span><br/>";
  });
  //set vat amount to view
  // $('#all_items_vat').html(vat_amount);
  $("#all_items_vat").html(total_vat_section_to_show);

  //set total discount
  $("#all_items_discount").html(total_discount);

  //calculate total payable amount
  var total_payable_to_show = "";
  var total_vat_amount = 0;
  $.each(vat_amount, function (key, value) {
    total_vat_amount = (
      parseFloat(total_vat_amount) + parseFloat(value)
    ).toFixed(2);
  });
  var total_payable = (
    parseFloat(discounted_sub_total_amount) +
    parseFloat(total_vat_amount) +
    parseFloat(delivery_charge_amount)
  ).toFixed(2);

  //set total payable amount to view
  $("#total_payable").html(total_payable);
}

function set_all_items_modifiers_amount() {
  $(".order_holder .single_order").each(function (i, obj) {
    var modifiers_price = parseFloat(0).toFixed(2);
    var item_id = $(this).attr("id").substr(15);

    var item_quantity = $(this)
      .find("#item_quantity_table_" + item_id)
      .html();

    if ($(this).find("#item_modifiers_price_table_" + item_id).length > 0) {
      var comma_separeted_modifiers_price = $(this)
        .find("#item_modifiers_price_table_" + item_id)
        .html();
      var modifiers_price_array =
        comma_separeted_modifiers_price != ""
          ? comma_separeted_modifiers_price.split(",")
          : "";
      modifiers_price_array.forEach(function (modifier_price) {
        modifiers_price = (
          parseFloat(modifiers_price) + parseFloat(modifier_price)
        ).toFixed(2);
      });
      var modifiers_price_as_per_item_quantity = (
        parseFloat(item_quantity) * parseFloat(modifiers_price)
      ).toFixed(2);
      $(this)
        .find(".fifth_column #item_modifiers_price_table_" + item_id)
        .html(modifiers_price_as_per_item_quantity);
    }
  });
}

function set_total_items_discount_for_subtotal() {
  var total_discount = 0;
  $(".single_order .first_portion .fifth_column span").each(function (i, obj) {
    var this_item_discount_amount = parseFloat(
      $(this).parent().parent().find(".item_discount").html()
    ).toFixed(2);
    total_discount = (
      parseFloat(total_discount) + parseFloat(this_item_discount_amount)
    ).toFixed(2);
  });
  $("#total_item_discount").html(total_discount);
}

function set_all_hidden_item_discount_amount() {
  $(".single_order .first_portion .fifth_column span").each(function (i, obj) {
    var this_item_original_price = parseFloat(
      $(this).parent().parent().find(".item_price_without_discount").html()
    ).toFixed(2);
    var item_discount_value = $(this)
      .parent()
      .parent()
      .find(".forth_column input")
      .val();
    item_discount_value = item_discount_value != "" ? item_discount_value : 0;
    var actual_discount_amount = get_particular_item_discount_amount(
      item_discount_value,
      this_item_original_price
    );
    $(this)
      .parent()
      .parent()
      .find(".item_discount")
      .html(actual_discount_amount);
  });
}

function set_all_visible_discounted_item_price() {
  $(".single_order .first_portion .fifth_column span").each(function (i, obj) {
    var this_item_original_price = parseFloat(
      $(this).parent().parent().find(".item_price_without_discount").html()
    ).toFixed(2);
    var item_discount_value = $(this)
      .parent()
      .parent()
      .find(".forth_column input")
      .val();
    item_discount_value = item_discount_value != "" ? item_discount_value : 0;
    var actual_discount_amount = get_particular_item_discount_amount(
      item_discount_value,
      this_item_original_price
    );
    var discounted_item_price = (
      parseFloat(this_item_original_price) - parseFloat(actual_discount_amount)
    ).toFixed(2);
    $(this)
      .parent()
      .parent()
      .find(".fifth_column span")
      .html(discounted_item_price);
  });
}

function get_total_of_all_items_and_modifiers() {
  //get all items total price and add
  var all_item_total_price = 0;
  var all_item_total_vat_amount = 0;
  var this_item_discount = 0;
  $(".single_order .first_portion .fifth_column span").each(function (i, obj) {
    all_item_total_price = (
      parseFloat(all_item_total_price) + parseFloat($(this).html())
    ).toFixed(2);
  });

  //get all modifiers price and add
  var all_modifiers_total_price = 0;
  $(".single_order .second_portion .fifth_column span").each(function (i, obj) {
    all_modifiers_total_price = (
      parseFloat(all_modifiers_total_price) + parseFloat($(this).html())
    ).toFixed(2);
  });
  return (
    parseFloat(all_item_total_price) + parseFloat(all_modifiers_total_price)
  ).toFixed(2);
}

function get_total_vat() {
  var all_item_total_vat_amount = 0;
  var tax_object = {};
  var tax_name = [];
  var customer_id = $("#walk_in_customer").val();
  var customer_gst_number = "";
  for (m in window.customers) {
    var this_customer = window.customers[m];
    if (this_customer.customer_id == customer_id) {
      customer_gst_number = this_customer.gst_number;
    }
  }
  var customer_state_code =
    customer_gst_number != "" ? customer_gst_number.substr(0, 2) : "";
  var same_state = false;
  if (customer_state_code == gst_state_code) {
    same_state = true;
  }
  if (customer_state_code == "") {
    same_state = true;
  }
  $(".single_order .first_portion .fifth_column span").each(function (i, obj) {
    var this_item_quantity = parseFloat(
      $(this).parent().parent().find(".third_column span").html()
    ).toFixed(2);
    var this_item_price = parseFloat(
      $(this).parent().parent().find(".second_column span").html()
    ).toFixed(2);
    var item_total_price = parseFloat(
      $(this).parent().parent().find(".fifth_column span").html()
    ).toFixed(2);
    // var this_item_vat_percentage = parseFloat($(this).parent().parent().find('.item_vat').html()).toFixed(2);
    var tax_information = JSON.parse(
      $(this).parent().parent().find(".item_vat").html()
    );
    // console.log(tax_information);

    if (tax_information.length > 0) {
      // console.log(tax_information);
      for (k in tax_information) {
        if (tax_name.includes(tax_information[k].tax_field_name)) {
          if (
            collect_gst == "Yes" &&
            same_state &&
            tax_information[k].tax_field_name != "IGST"
          ) {
            var previous_value =
              tax_object[
                "" +
                  tax_information[k].tax_field_name +
                  "_" +
                  tax_information[k].tax_field_id
              ];
            tax_object[
              "" +
                tax_information[k].tax_field_name +
                "_" +
                tax_information[k].tax_field_id
            ] = (
              parseFloat(previous_value) +
              parseFloat(
                parseFloat(
                  parseFloat(tax_information[k].tax_field_percentage) *
                    parseFloat(item_total_price)
                ) / parseFloat(100)
              )
            ).toFixed(2);
          } else if (
            collect_gst == "Yes" &&
            !same_state &&
            tax_information[k].tax_field_name != "SGST"
          ) {
            var previous_value =
              tax_object[
                "" +
                  tax_information[k].tax_field_name +
                  "_" +
                  tax_information[k].tax_field_id
              ];
            tax_object[
              "" +
                tax_information[k].tax_field_name +
                "_" +
                tax_information[k].tax_field_id
            ] = (
              parseFloat(previous_value) +
              parseFloat(
                parseFloat(
                  parseFloat(tax_information[k].tax_field_percentage) *
                    parseFloat(item_total_price)
                ) / parseFloat(100)
              )
            ).toFixed(2);
          }
          if (
            collect_tax == "Yes" &&
            collect_gst != "Yes" &&
            tax_information[k].tax_field_name != "IGST" &&
            tax_information[k].tax_field_name != "CGST" &&
            tax_information[k].tax_field_name != "SGST"
          ) {
            var previous_value =
              tax_object[
                "" +
                  tax_information[k].tax_field_name +
                  "_" +
                  tax_information[k].tax_field_id
              ];
            tax_object[
              "" +
                tax_information[k].tax_field_name +
                "_" +
                tax_information[k].tax_field_id
            ] = (
              parseFloat(previous_value) +
              parseFloat(
                parseFloat(
                  parseFloat(tax_information[k].tax_field_percentage) *
                    parseFloat(item_total_price)
                ) / parseFloat(100)
              )
            ).toFixed(2);
          }
        } else {
          if (
            collect_gst == "Yes" &&
            same_state &&
            tax_information[k].tax_field_name != "IGST"
          ) {
            tax_name.push(tax_information[k].tax_field_name);
            tax_object[
              "" +
                tax_information[k].tax_field_name +
                "_" +
                tax_information[k].tax_field_id
            ] = (
              parseFloat(
                parseFloat(tax_information[k].tax_field_percentage) *
                  parseFloat(item_total_price)
              ) / parseFloat(100)
            ).toFixed(2);
          } else if (
            collect_gst == "Yes" &&
            !same_state &&
            tax_information[k].tax_field_name != "SGST"
          ) {
            tax_name.push(tax_information[k].tax_field_name);
            tax_object[
              "" +
                tax_information[k].tax_field_name +
                "_" +
                tax_information[k].tax_field_id
            ] = (
              parseFloat(
                parseFloat(tax_information[k].tax_field_percentage) *
                  parseFloat(item_total_price)
              ) / parseFloat(100)
            ).toFixed(2);
          }

          if (
            collect_tax == "Yes" &&
            collect_gst != "Yes" &&
            tax_information[k].tax_field_name != "IGST" &&
            tax_information[k].tax_field_name != "CGST" &&
            tax_information[k].tax_field_name != "SGST"
          ) {
            tax_name.push(tax_information[k].tax_field_name);
            tax_object[
              "" +
                tax_information[k].tax_field_name +
                "_" +
                tax_information[k].tax_field_id
            ] = (
              parseFloat(
                parseFloat(tax_information[k].tax_field_percentage) *
                  parseFloat(item_total_price)
              ) / parseFloat(100)
            ).toFixed(2);
          }
        }
      }
    }
  });
  return tax_object;
}

function get_all_item_discount() {
  var all_item_discount = 0;
  $(".single_order .first_portion .fifth_column span").each(function (i, obj) {
    var this_item_quantity = parseFloat(
      $(this).parent().parent().find(".third_column span").html()
    ).toFixed(2);
    var this_item_price = parseFloat(
      $(this).parent().parent().find(".second_column span").html()
    ).toFixed(2);
    var total_item_price_this_row = (
      parseFloat(this_item_quantity) * parseFloat(this_item_price)
    ).toFixed(2);
    var this_item_discount =
      $(this).parent().parent().find(".forth_column input").val() != ""
        ? $(this).parent().parent().find(".forth_column input").val()
        : 0;
    this_item_discount = get_particular_item_discount_amount(
      this_item_discount,
      total_item_price_this_row
    );
    $(this).parent().parent().find(".item_discount").html(this_item_discount);
    // var this_item_discount = (parseFloat(this_item_discount)+parseFloat($(this).parent().parent().find('.item_discount').html())).toFixed(2);
    all_item_discount = (
      parseFloat(all_item_discount) + parseFloat(this_item_discount)
    ).toFixed(2);
  });

  return all_item_discount;
}

function get_particular_item_discount_amount(discount, item_price) {
  if (discount.length > 0 && discount.substr(discount.length - 1) == "%") {
    return (
      (parseFloat(item_price) * parseFloat(discount)) /
      parseFloat(100)
    ).toFixed(2);
  } else {
    return parseFloat(discount).toFixed(2);
  }
}

function get_updated_sub_total() {
  //get all items total price and add
  var all_item_total_price = 0;
  var all_item_total_vat_amount = 0;
  var this_item_discount = 0;
  $(".single_order .first_portion .fifth_column span").each(function (i, obj) {
    var this_item_quantity = parseFloat(
      $(this).parent().parent().find(".third_column span").html()
    ).toFixed(2);
    var this_item_price = parseFloat(
      $(this).parent().parent().find(".second_column span").html()
    ).toFixed(2);
    var this_item_vat_percentage = parseFloat(
      $(this).parent().parent().find(".item_vat").html()
    ).toFixed(2);
    all_item_total_price = (
      parseFloat(all_item_total_price) +
      parseFloat(this_item_quantity) * parseFloat(this_item_price)
    ).toFixed(2);
    var this_item_vat_amount = (
      (parseFloat($(this).html()) * parseFloat(this_item_vat_percentage)) /
      parseFloat(100)
    ).toFixed(2);
    all_item_total_vat_amount = (
      parseFloat(all_item_total_vat_amount) + parseFloat(this_item_vat_amount)
    ).toFixed(2);

    var this_item_discount = (
      parseFloat(this_item_discount) +
      parseFloat($(this).parent().parent().find(".item_discount").html())
    ).toFixed(2);
  });

  //get total discount
  var total_discount = $("#all_items_discount");

  //get discount on sub total
  var sub_total_discount =
    $("#sub_total_discount").val() == "" ? $("#sub_total_discount").val() : 0;

  //get sub total
  var sub_total = parseFloat($("#sub_total").html()).toFixed(2);

  //get sub total discount amount
  var sub_total_discount_amount = get_sub_total_discount_amount(
    $sub_total_discount,
    $sub_total
  );

  //get all modifiers price and add
  var all_modifiers_total_price = 0;
  $(".single_order .second_portion .fifth_column span").each(function (i, obj) {
    all_modifiers_total_price = (
      parseFloat(all_modifiers_total_price) + parseFloat($(this).html())
    ).toFixed(2);
  });
  //set vat amount under sub total
  $("#all_items_vat").html(all_item_total_vat_amount);

  var total_of_all_items_and_modifiers =
    parseFloat(all_item_total_price) + parseFloat(all_modifiers_total_price);
  return total_of_all_items_and_modifiers;
}

function sub_total_get_amount($value) {}

function get_sub_total_discount_amount(sub_total_discount, sub_total) {
  if (
    sub_total_discount.length > 0 &&
    sub_total_discount.substr(sub_total_discount.length - 1) == "%"
  ) {
    return (
      (parseFloat(sub_total) * parseFloat(sub_total_discount)) /
      parseFloat(100)
    ).toFixed(2);
  } else {
    return parseFloat(sub_total_discount).toFixed(2);
  }
}

function reset_on_modal_close_or_add_to_cart() {
  $("#item_modal").slideUp(333);
  $("#item_quantity_modal").html("1");
  $("#modal_modifier_price_variable").html("0");
  $("#modal_modifiers_unit_price_variable").html("0");
  $("#modal_item_note").val("");
  $("#modal_discount").val("");
  $("#modal_item_price_variable_without_discount").html("0");
  $("#modal_item_vat_percentage").html("0");
  $("#modal_item_row").html("0");
  $("#modal_discount_amount").html("0");
}

function reset_on_modal_close_or_add_customer() {
  $("#add_customer_modal").slideUp(333);
  $("#customer_id_modal").val("");
  $("#customer_name_modal").val("");
  $("#customer_phone_modal").val("");
  $("#customer_email_modal").val("");
  $("#customer_dob_modal").val("");
  $("#customer_doa_modal").val("");
  $("#customer_delivery_address_modal").val("");
  $("#customer_gst_number_modal").val("");
}

function upTime2(object, second, minute, hour) {
  var table_id = object.attr("id").substr(13);
  if (
    $("#booked_for_hour_" + table_id).html() == "00" &&
    $("#booked_for_minute_" + table_id).html() == "00" &&
    $("#booked_for_second_" + table_id).html() == "00"
  ) {
    return false;
  }
  second++;
  if (second == 60) {
    minute++;
    second = 0;
  }
  if (minute == 60) {
    hour++;
  }
  hour = hour.toString();
  minute = minute.toString();
  second = second.toString();
  hour = hour.length == 1 ? "0" + hour : hour;
  minute = minute.length == 1 ? "0" + minute : minute;
  second = second.length == 1 ? "0" + second : second;
  $("#booked_for_hour_" + table_id).html(hour);
  $("#booked_for_minute_" + table_id).html(minute);
  $("#booked_for_second_" + table_id).html(second);

  upTime2.to = setTimeout(function () {
    upTime2(object, second, minute, hour);
  }, 1000);
}

function clearFooterCartCalculation() {
  $("#sub_total_show").html("0.00");
  $("#sub_total").html("0.00");
  $("#total_item_discount").html("0.00");
  $("#discounted_sub_total_amount").html("0.00");
  $("#sub_total_discount").val("");
  $("#sub_total_discount_amount").html("0.00");
  $("#all_items_vat").html("0.00");
  $("#all_items_discount").html("0.00");
  $("#delivery_charge").val("");
  $("#total_items_in_cart").html("0");
  $("#total_items_in_cart_with_quantity").html("0");
  $("#total_payable").html("0.00");
}

function add_sale_and_direct_invoice_by_ajax(order_object, sale_id) {
  $.ajax({
    url: base_url + "Sale/add_sale_by_ajax",
    method: "POST",
    data: {
      order: order_object,
      sale_id: sale_id,
      close_order: 1,
      csrf_irestoraplus: csrf_value_,
    },
    success: function (response) {
      if (response > 0) {
        set_new_orders_to_view_and_select_last_one();
        // make_this_running_order_selected(response);
        var sale_id = response;
        $.ajax({
          url: base_url + "Sale/get_all_information_of_a_sale_ajax",
          method: "POST",
          data: {
            sale_id: sale_id,
            csrf_irestoraplus: csrf_value_,
          },
          success: function (response) {
            response = JSON.parse(response);
            $("#finalize_total_payable").html(response.total_payable);
            $("#pay_amount_invoice_input").val(response.total_payable);
            $("#finalize_order_modal").show();
            $("#finalize_update_type").html("2"); //when 2 update payment method, close time and order_status to 3
            calculate_create_invoice_modal();
          },
          error: function () {
            alert(a_error);
          },
        });
        // print_invoice(response);
        // print_kot(response);
      }
    },
    error: function () {
      alert(a_error);
    },
  });
}

function add_sale_by_ajax(order_object, sale_id) {
  $.ajax({
    url: base_url + "Sale/add_sale_by_ajax",
    method: "POST",
    data: {
      order: order_object,
      sale_id: sale_id,
      close_order: 0,
      csrf_irestoraplus: csrf_value_,
    },
    success: function (response) {
      if (response > 0) {
        set_new_orders_to_view();
      }
    },
    error: function () {
      alert(a_error);
    },
  });
}

function add_hold_by_ajax(order_object, hold_number) {
  $.ajax({
    url: base_url + "Sale/add_hold_by_ajax",
    method: "POST",
    data: {
      order: order_object,
      hold_number: hold_number,
      csrf_irestoraplus: csrf_value_,
    },
    success: function (response) {
      $("#generate_sale_hold_modal").hide();
      $(".order_holder").empty();
      clearFooterCartCalculation();
      $("#hold_generate_input").val("");

      $(".main_top").find("button").css("background-color", "#F3F3F3");
      $(".main_top").find("button").attr("data-selected", "unselected");
      $("#table_button").attr("disabled", false);
      $(".single_table_div[data-table-checked=checked]").attr(
        "data-table-checked",
        "unchecked"
      );
      reset_customer_waiter_to_default();
    },
    error: function () {
      alert(a_error);
    },
  });
}

function set_new_orders_to_view() {
  $.ajax({
    url: base_url + "Sale/get_new_orders_ajax",
    method: "GET",
    success: function (response) {
      response = JSON.parse(response);
      var order_list_left = "";
      var i = 1;
      for (var key in response) {
        var width = 100;
        var total_kitchen_type_items = response[key].total_kitchen_type_items;
        var total_kitchen_type_started_cooking_items =
          response[key].total_kitchen_type_started_cooking_items;
        var total_kitchen_type_done_items =
          response[key].total_kitchen_type_done_items;
        var splitted_width = (
          parseFloat(width) / parseFloat(total_kitchen_type_items)
        ).toFixed(2);
        var percentage_for_started_cooking = (
          parseFloat(splitted_width) *
          parseFloat(total_kitchen_type_started_cooking_items)
        ).toFixed(2);
        var percentage_for_done_cooking = (
          parseFloat(splitted_width) * parseFloat(total_kitchen_type_done_items)
        ).toFixed(2);
        if (i == 1) {
          order_list_left +=
            '<div data-started-cooking="' +
            total_kitchen_type_started_cooking_items +
            '" data-done-cooking="' +
            total_kitchen_type_done_items +
            '" class="single_order fix" style="margin-top:0px" data-selected="unselected" id="order_' +
            response[key].sales_id +
            '">';
        } else {
          order_list_left +=
            '<div data-started-cooking="' +
            total_kitchen_type_started_cooking_items +
            '" data-done-cooking="' +
            total_kitchen_type_done_items +
            '" class="single_order fix" data-selected="unselected" id="order_' +
            response[key].sales_id +
            '">';
        }
        order_list_left += '<div class="inside_single_order_container fix">';
        order_list_left +=
          '<div class="single_order_content_holder_inside fix">';
        var order_name = "";
        if (response[key].order_type == "1") {
          order_name = "A " + response[key].sale_no;
        } else if (response[key].order_type == "2") {
          order_name = "B " + response[key].sale_no;
        } else if (response[key].order_type == "3") {
          order_name = "C " + response[key].sale_no;
        }

        //var table_name = (response[key].tables_booked[0].table_name!=null)?response[key].tables_booked[0].table_name:"";
        var table_name = "";
        var waiter_name =
          response[key].waiter_name != null ? response[key].waiter_name : "";
        var customer_name =
          response[key].customer_name != null
            ? response[key].customer_name
            : "";
        var minute = response[key].minute_difference;
        var second = response[key].second_difference;

        var tables_booked = "";
        if (response[key].tables_booked.length > 0) {
          var w = 1;
          for (var k in response[key].tables_booked) {
            var single_table = response[key].tables_booked[k];
            if (w == response[key].tables_booked.length) {
              tables_booked += single_table.table_name;
            } else {
              tables_booked += single_table.table_name + ", ";
            }
            w++;
          }
        } else {
          tables_booked = "None";
        }

        order_list_left +=
          '<span id="open_orders_order_status_' +
          response[key].sales_id +
          '" class="ir_display_none">' +
          response[key].order_status +
          '</span><p  class="oder_list_class" style="font-size: 16px;text-align:left;width: 125px;float: left;">Order: <span class="running_order_order_number">' +
          order_name +
          '</span></p><img src="' +
          base_url +
          'assets/images/right-arrow.png" style="float: right;width: 13px;margin: 2px;transition: .25s ease-out;" class="running_order_right_arrow" id="running_order_right_arrow_' +
          response[key].sales_id +
          '">';
        order_list_left +=
          '<p>Table: <span class="running_order_table_name">' +
          tables_booked +
          "</span></p>";
        order_list_left +=
          '<p>Waiter: <span class="running_order_waiter_name">' +
          waiter_name +
          "</span></p>";
        order_list_left +=
          '<p>Customer: <span class="running_order_customer_name">' +
          customer_name +
          "</span></p>";
        order_list_left += "</div>";
        order_list_left += '<div class="order_condition">';
        order_list_left +=
          '<p class="order_on_processing">Started Cookiing: ' +
          total_kitchen_type_items +
          "/" +
          total_kitchen_type_started_cooking_items +
          "</p>";
        order_list_left +=
          '<p class="order_done">Done: ' +
          total_kitchen_type_items +
          "/" +
          total_kitchen_type_done_items +
          "</p>";
        order_list_left += "</div>";
        order_list_left += '<div class="order_condition">';
        order_list_left +=
          '<p style="font-size:16px;">Time Count: <span id="order_minute_count_' +
          response[key].sales_id +
          '">' +
          minute +
          '</span>:<span id="order_second_count_' +
          response[key].sales_id +
          '">' +
          second +
          "</span> M</p>";
        order_list_left += "</div>";
        order_list_left += "</div>";
        order_list_left += "</div>";
        i++;
      }
      $("#order_details_holder").html(order_list_left);
      $(".order_table_holder .order_holder").empty();
      clearFooterCartCalculation();
      $(".main_top").find("button").css("background-color", "#F3F3F3");
      $(".main_top").find("button").attr("data-selected", "unselected");
      $("#table_button").attr("disabled", false);
      $(".single_table_div[data-table-checked=checked]").attr(
        "data-table-checked",
        "unchecked"
      );
      reset_customer_waiter_to_default();
      reset_time_interval();
      all_time_interval_operation();

      reset_table_modal();
      createAnimation(response[key].sales_id);
      // $("#walk_in_customer").select2().select2("val", "1");
    },
    error: function () {
      alert(a_error);
    },
  });
}

function set_new_orders_to_view_and_select_last_one() {
  $.ajax({
    url: base_url + "Sale/get_new_orders_ajax",
    method: "GET",
    success: function (response) {
      response = JSON.parse(response);
      var order_list_left = "";
      var i = 1;
      var last_key = response.length - 1;
      for (var key in response) {
        var width = 100;
        var total_kitchen_type_items = response[key].total_kitchen_type_items;
        var total_kitchen_type_started_cooking_items =
          response[key].total_kitchen_type_started_cooking_items;
        var total_kitchen_type_done_items =
          response[key].total_kitchen_type_done_items;
        var splitted_width = (
          parseFloat(width) / parseFloat(total_kitchen_type_items)
        ).toFixed(2);
        var percentage_for_started_cooking = (
          parseFloat(splitted_width) *
          parseFloat(total_kitchen_type_started_cooking_items)
        ).toFixed(2);
        var percentage_for_done_cooking = (
          parseFloat(splitted_width) * parseFloat(total_kitchen_type_done_items)
        ).toFixed(2);
        if (i == 1) {
          if (last_key == key) {
            order_list_left +=
              '<div data-started-cooking="' +
              total_kitchen_type_started_cooking_items +
              '" data-done-cooking="' +
              total_kitchen_type_done_items +
              '" class="single_order fix" style="margin-top:0px;background-color:#b6d6f6;" data-selected="selected" id="order_' +
              response[key].sales_id +
              '">';
          } else {
            order_list_left +=
              '<div data-started-cooking="' +
              total_kitchen_type_started_cooking_items +
              '" data-done-cooking="' +
              total_kitchen_type_done_items +
              '" class="single_order fix" style="margin-top:0px" data-selected="unselected" id="order_' +
              response[key].sales_id +
              '">';
          }
        } else {
          if (last_key == key) {
            order_list_left +=
              '<div data-started-cooking="' +
              total_kitchen_type_started_cooking_items +
              '" data-done-cooking="' +
              total_kitchen_type_done_items +
              '" class="single_order fix" style="background-color:#b6d6f6;" data-selected="selected" id="order_' +
              response[key].sales_id +
              '">';
          } else {
            order_list_left +=
              '<div data-started-cooking="' +
              total_kitchen_type_started_cooking_items +
              '" data-done-cooking="' +
              total_kitchen_type_done_items +
              '" class="single_order fix" data-selected="unselected" id="order_' +
              response[key].sales_id +
              '">';
          }
        }
        order_list_left += '<div class="inside_single_order_container fix">';
        order_list_left +=
          '<div class="single_order_content_holder_inside fix">';
        var order_name = "";
        if (response[key].order_type == "1") {
          order_name = "A " + response[key].sale_no;
        } else if (response[key].order_type == "2") {
          order_name = "B " + response[key].sale_no;
        } else if (response[key].order_type == "3") {
          order_name = "C " + response[key].sale_no;
        }

        //var table_name = (response[key].tables_booked[0].table_name!=null)?response[key].tables_booked[0].table_name:"";
        var table_name = "";
        var waiter_name =
          response[key].waiter_name != null ? response[key].waiter_name : "";
        var customer_name =
          response[key].customer_name != null
            ? response[key].customer_name
            : "";

        var minute = response[key].minute_difference;
        var second = response[key].second_difference;

        var tables_booked = "";
        if (response[key].tables_booked.length > 0) {
          var w = 1;
          for (var k in response[key].tables_booked) {
            var single_table = response[key].tables_booked[k];
            if (w == response[key].tables_booked.length) {
              tables_booked += single_table.table_name;
            } else {
              tables_booked += single_table.table_name + ", ";
            }
            w++;
          }
        } else {
          tables_booked = "None";
        }

        order_list_left +=
          '<span id="open_orders_order_status_' +
          response[key].sales_id +
          '" class="ir_display_none">' +
          response[key].order_status +
          '</span><p  class="oder_list_class" style="font-size: 16px;text-align:left;width: 125px;float: left;">Order: <span class="running_order_order_number">' +
          order_name +
          '</span></p><img src="' +
          base_url +
          'assets/images/right-arrow.png" style="float: right;width: 13px;margin: 2px;transition: .25s ease-out;" class="running_order_right_arrow" id="running_order_right_arrow_' +
          response[key].sales_id +
          '">';
        order_list_left +=
          '<p>Table: <span class="running_order_table_name">' +
          tables_booked +
          "</span></p>";
        order_list_left +=
          '<p>Waiter: <span class="running_order_waiter_name">' +
          waiter_name +
          "</span></p>";
        order_list_left +=
          '<p>Customer: <span class="running_order_customer_name">' +
          customer_name +
          "</span></p>";
        order_list_left += "</div>";
        order_list_left += '<div class="order_condition">';
        order_list_left +=
          '<p class="order_on_processing">Started Cookiing: ' +
          total_kitchen_type_items +
          "/" +
          total_kitchen_type_started_cooking_items +
          "</p>";
        order_list_left +=
          '<p class="order_done">Done: ' +
          total_kitchen_type_items +
          "/" +
          total_kitchen_type_done_items +
          "</p>";
        order_list_left += "</div>";
        order_list_left += '<div class="order_condition">';
        order_list_left +=
          '<p style="font-size:16px;">Time Count: <span id="order_minute_count_' +
          response[key].sales_id +
          '">' +
          minute +
          '</span>:<span id="order_second_count_' +
          response[key].sales_id +
          '">' +
          second +
          "</span> M</p>";
        order_list_left += "</div>";
        order_list_left += "</div>";
        order_list_left += "</div>";
        i++;
      }
      $("#order_details_holder").html(order_list_left);
      $(".order_table_holder .order_holder").empty();
      clearFooterCartCalculation();
      $(".main_top").find("button").css("background-color", "#F3F3F3");
      $(".main_top").find("button").attr("data-selected", "unselected");
      $("#table_button").attr("disabled", false);
      $(".single_table_div[data-table-checked=checked]").attr(
        "data-table-checked",
        "unchecked"
      );
      reset_customer_waiter_to_default();
      reset_time_interval();
      all_time_interval_operation();

      reset_table_modal();

      // $("#walk_in_customer").select2().select2("val", "1");
    },
    error: function () {
      alert(a_error);
    },
  });
}

function set_new_orders_to_view_for_interval() {
  $.ajax({
    url: base_url + "Sale/get_new_orders_ajax",
    method: "GET",
    success: function (response) {
      response = JSON.parse(response);
      var order_list_left = "";
      var i = 1;
      for (var key in response) {
        var width = 100;
        var total_kitchen_type_items = response[key].total_kitchen_type_items;
        var total_kitchen_type_started_cooking_items =
          response[key].total_kitchen_type_started_cooking_items;
        var total_kitchen_type_done_items =
          response[key].total_kitchen_type_done_items;
        var splitted_width = (
          parseFloat(width) / parseFloat(total_kitchen_type_items)
        ).toFixed(2);
        var percentage_for_started_cooking = (
          parseFloat(splitted_width) *
          parseFloat(total_kitchen_type_started_cooking_items)
        ).toFixed(2);
        var percentage_for_done_cooking = (
          parseFloat(splitted_width) * parseFloat(total_kitchen_type_done_items)
        ).toFixed(2);
        if (i == 1) {
          order_list_left +=
            '<div data-started-cooking="' +
            total_kitchen_type_started_cooking_items +
            '" data-done-cooking="' +
            total_kitchen_type_done_items +
            '" class="single_order fix" style="margin-top:0px" data-selected="unselected" id="order_' +
            response[key].sales_id +
            '">';
        } else {
          order_list_left +=
            '<div data-started-cooking="' +
            total_kitchen_type_started_cooking_items +
            '" data-done-cooking="' +
            total_kitchen_type_done_items +
            '" class="single_order fix" data-selected="unselected" id="order_' +
            response[key].sales_id +
            '">';
        }
        order_list_left += '<div class="inside_single_order_container fix">';
        order_list_left +=
          '<div class="single_order_content_holder_inside fix">';
        var order_name = "";
        if (response[key].order_type == "1") {
          order_name = "A " + response[key].sale_no;
        } else if (response[key].order_type == "2") {
          order_name = "B " + response[key].sale_no;
        } else if (response[key].order_type == "3") {
          order_name = "C " + response[key].sale_no;
        }
        var table_name =
          response[key].table_name != null ? response[key].table_name : "";
        var waiter_name =
          response[key].waiter_name != null ? response[key].waiter_name : "";
        var customer_name =
          response[key].customer_name != null
            ? response[key].customer_name
            : "";
        var minute = response[key].minute_difference;
        var second = response[key].second_difference;

        var tables_booked = "";
        if (response[key].tables_booked.length > 0) {
          var w = 1;
          for (var k in response[key].tables_booked) {
            var single_table = response[key].tables_booked[k];
            if (w == response[key].tables_booked.length) {
              tables_booked += single_table.table_name;
            } else {
              tables_booked += single_table.table_name + ", ";
            }
            w++;
          }
        } else {
          tables_booked = "None";
        }

        order_list_left +=
          '<span id="open_orders_order_status_' +
          response[key].sales_id +
          '" class="ir_display_none">' +
          response[key].order_status +
          '</span><p   class="oder_list_class" style="font-size: 16px;font-weight: bold;text-align:left;width:125px;float:left">Order: <span class="running_order_order_number">' +
          order_name +
          '</span></p><img src="' +
          base_url +
          'assets/images/right-arrow.png" style="float: right;width: 13px;margin: 2px;transition: .25s ease-out;" class="running_order_right_arrow" id="running_order_right_arrow_' +
          response[key].sales_id +
          '">';
        order_list_left +=
          '<p>Table: <span class="running_order_table_name">' +
          tables_booked +
          "</span></p>";
        order_list_left +=
          '<p>Waiter: <span class="running_order_waiter_name">' +
          waiter_name +
          "</span></p>";
        order_list_left +=
          '<p>Customer: <span class="running_order_customer_name">' +
          customer_name +
          "</span></p>";
        order_list_left += "</div>";
        order_list_left += '<div class="order_condition">';
        order_list_left +=
          '<p class="order_on_processing">Started Cooking: ' +
          total_kitchen_type_started_cooking_items +
          "/" +
          total_kitchen_type_items +
          "</p>";
        order_list_left +=
          '<p class="order_done">Done: ' +
          total_kitchen_type_done_items +
          "/" +
          total_kitchen_type_items +
          "</p>";
        order_list_left += "</div>";
        order_list_left += '<div class="order_condition">';
        order_list_left +=
          '<p style="font-size:16px;">Time Count: <span id="order_minute_count_' +
          response[key].sales_id +
          '">' +
          minute +
          '</span>:<span id="order_second_count_' +
          response[key].sales_id +
          '">' +
          second +
          "</span> M</p>";
        order_list_left += "</div>";
        order_list_left += "</div>";
        order_list_left += "</div>";
        i++;
      }
      $("#order_details_holder").html(order_list_left);
    },
    error: function () {
      console.log("Left Order Refresh Ajax Error");
    },
  });
}

function reset_customer_waiter_to_default() {
  var cid = $('#walk_in_customer > option:contains("Walk-in Customer")').attr(
    "value"
  );
  var wid = $('#select_waiter > option:contains("Default Waiter")').attr(
    "value"
  );
  $("#walk_in_customer").val(cid).trigger("change");
  $("#select_waiter").val(wid).trigger("change");
  $("#place_edit_order").html("Place Order");
}

function print_invoice_and_close(
  sale_id,
  payment_method_type,
  invoice_create_type,
  paid_amount,
  due_amount
) {
  if (invoice_create_type == 1) {
    //if type is 1 then update order status to invoiced order, and update payment method type
    update_order_status_to_invoiced(
      sale_id,
      payment_method_type,
      paid_amount,
      due_amount
    );
  } else if (invoice_create_type == 2) {
    //then change order status to close, close time update, payment method type update,
    close_order(sale_id, payment_method_type, paid_amount, due_amount);
  }
  print_invoice(sale_id);
}

function get_all_hold_sales() {
  $.ajax({
    url: base_url + "Sale/get_all_holds_ajax",
    method: "GET",
    success: function (response) {
      var orders = JSON.parse(response);
      var held_orders = "";
      for (var key in orders) {
        var customer_name =
          orders[key].customer_name == null || orders[key].customer_name == ""
            ? "&nbsp;"
            : orders[key].customer_name;
        var table_name =
          orders[key].table_name != null ? orders[key].table_name : "&nbsp;";
        held_orders +=
          '<div class="single_hold_sale fix" id="hold_' +
          orders[key].id +
          '" data-selected="unselected">';
        held_orders +=
          '<div class="first_column column fix">' +
          orders[key].hold_no +
          "</div>";
        held_orders +=
          '<div class="second_column column fix">' + customer_name + "</div>";
        held_orders +=
          '<div class="third_column column fix">' + table_name + "</div>";
        held_orders += "</div>";
      }
      $(".hold_list_holder .detail_holder ").empty();
      $(".hold_list_holder .detail_holder ").prepend(held_orders);
    },
    error: function () {
      alert(a_error);
    },
  });
}

function update_order_status_to_invoiced(
  sale_id,
  payment_method_type,
  paid_amount,
  due_amount
) {
  var given_amount_input = $("#given_amount_input").val();
  var change_amount_input = $("#change_amount_input").val();
  $.ajax({
    url: base_url + "Sale/update_order_status_ajax",
    method: "POST",
    data: {
      sale_id: sale_id,
      close_order: false,
      paid_amount: paid_amount,
      due_amount: due_amount,
      payment_method_type: payment_method_type,
      given_amount_input: given_amount_input,
      change_amount_input: change_amount_input,
      csrf_irestoraplus: csrf_value_,
    },
    success: function (response) {
      if (response > 1) {
        set_new_orders_to_view();
      }
    },
    error: function () {
      alert(a_error);
    },
  });
}

function close_order(sale_id, payment_method_type, paid_amount, due_amount) {
  var given_amount_input = $("#given_amount_input").val();
  var change_amount_input = $("#change_amount_input").val();
  $.ajax({
    url: base_url + "Sale/update_order_status_ajax",
    method: "POST",
    data: {
      sale_id: sale_id,
      close_order: true,
      paid_amount: paid_amount,
      due_amount: due_amount,
      payment_method_type: payment_method_type,
      given_amount_input: given_amount_input,
      change_amount_input: change_amount_input,
      csrf_irestoraplus: csrf_value_,
    },
    success: function (response) {
      if (response > 1) {
        set_new_orders_to_view();
      }
    },
    error: function () {
      alert(a_error);
    },
  });
}

function print_invoice(sale_id) {
  let newWindow = "";
  var print_type = $("#print_type").val();
  if (Number(print_type) == 1) {
    newWindow = open(
      "print_invoice/" + sale_id,
      "Print Invoice",
      "width=450,height=550"
    );
  } else {
    newWindow = open(
      "print_bill/" + sale_id,
      "Print Bill",
      "width=450,height=550"
    );
  }

  newWindow.focus();

  newWindow.onload = function () {
    newWindow.document.body.insertAdjacentHTML("afterbegin");
  };
}

function get_details_of_a_particular_hold(hold_id) {
  $.ajax({
    url: base_url + "Sale/get_single_hold_info_by_ajax",
    method: "POST",
    data: {
      hold_id: hold_id,
      csrf_irestoraplus: csrf_value_,
    },
    success: function (response) {
      response = JSON.parse(response);
      hold_id = response.id;
      var draw_table_for_order = "";

      for (var key in response.items) {
        //construct div
        var this_item = response.items[key];

        var selected_modifiers = "";
        var selected_modifiers_id = "";
        var selected_modifiers_price = "";
        var modifiers_price = 0;
        var total_modifier = this_item.modifiers.length;
        var i = 1;
        for (var mod_key in this_item.modifiers) {
          var this_modifier = this_item.modifiers[mod_key];
          //get selected modifiers
          if (i == total_modifier) {
            selected_modifiers += this_modifier.name;
            selected_modifiers_id += this_modifier.modifier_id;
            selected_modifiers_price += this_modifier.modifier_price;
            modifiers_price = (
              parseFloat(modifiers_price) +
              parseFloat(this_modifier.modifier_price)
            ).toFixed(2);
          } else {
            selected_modifiers += this_modifier.name + ",";
            selected_modifiers_id += this_modifier.modifier_id + ",";
            selected_modifiers_price += this_modifier.modifier_price + ",";
            modifiers_price = (
              parseFloat(modifiers_price) +
              parseFloat(this_modifier.modifier_price)
            ).toFixed(2);
          }
          i++;
        }

        draw_table_for_order +=
          '<div class="single_order fix" id="order_for_item_' +
          this_item.food_menu_id +
          '">';
        draw_table_for_order += '<div class="first_portion fix">';
        draw_table_for_order +=
          '<span class="item_vat ir_display_none" id="item_vat_percentage_table' +
          this_item.food_menu_id +
          '">' +
          this_item.menu_vat_percentage +
          "</span>";
        draw_table_for_order +=
          '<span class="item_discount ir_display_none" id="item_discount_table' +
          this_item.food_menu_id +
          '">' +
          this_item.discount_amount +
          "</span>";
        draw_table_for_order +=
          '<span class="item_price_without_discount ir_display_none" id="item_price_without_discount_' +
          this_item.food_menu_id +
          '">' +
          this_item.menu_price_without_discount +
          "</span>";
        draw_table_for_order +=
          '<div class="single_order_column first_column fix"><i style="cursor:pointer;" class="fas fa-pencil-alt edit_item" id="edit_item_' +
          this_item.food_menu_id +
          '"></i> <span id="item_name_table_' +
          this_item.food_menu_id +
          '">' +
          this_item.menu_name +
          "</span></div>";
        draw_table_for_order +=
          '<div class="single_order_column second_column fix">' +
          currency +
          ' <span id="item_price_table_' +
          this_item.food_menu_id +
          '">' +
          this_item.menu_unit_price +
          "</span></div>";
        draw_table_for_order +=
          '<div class="single_order_column third_column fix"><i style="cursor:pointer;" class="fas fa-minus-circle decrease_item_table" id="decrease_item_table_' +
          this_item.food_menu_id +
          '"></i> <span id="item_quantity_table_' +
          this_item.food_menu_id +
          '">' +
          this_item.qty +
          '</span> <i style="cursor:pointer;" class="fas fa-plus-circle increase_item_table" id="increase_item_table_' +
          this_item.food_menu_id +
          '"></i></div>';
        draw_table_for_order +=
          '<div class="single_order_column forth_column fix"><input type="" name="" placeholder="Amt or %" class="special_textbox" id="percentage_table_' +
          this_item.food_menu_id +
          '" value="' +
          this_item.menu_discount_value +
          '" disabled></div>';
        draw_table_for_order +=
          '<div class="single_order_column fifth_column fix">' +
          currency +
          ' <span id="item_total_price_table_' +
          this_item.food_menu_id +
          '">' +
          this_item.menu_price_with_discount +
          "</span></div>";
        draw_table_for_order += "</div>";
        if (selected_modifiers != "") {
          draw_table_for_order += '<div class="second_portion fix">';
          draw_table_for_order +=
            '<span id="item_modifiers_id_table_' +
            this_item.food_menu_id +
            '" class="ir_display_none">' +
            selected_modifiers_id +
            "</span>";
          draw_table_for_order +=
            '<span id="item_modifiers_price_table_' +
            this_item.food_menu_id +
            '" class="ir_display_none">' +
            selected_modifiers_price +
            "</span>";
          draw_table_for_order +=
            '<div class="single_order_column first_column fix"><span id="item_modifiers_table_' +
            this_item.food_menu_id +
            '">' +
            selected_modifiers +
            "</span></div>";
          draw_table_for_order +=
            '<div class="single_order_column fifth_column fix">' +
            currency +
            ' <span id="item_modifiers_price_table_' +
            this_item.food_menu_id +
            '">' +
            modifiers_price +
            "</span></div>";
          draw_table_for_order += "</div>";
        }
        if (this_item.menu_note != "") {
          draw_table_for_order += '<div class="third_portion fix">';
          draw_table_for_order +=
            '<div class="single_order_column first_column fix">Note: <span id="item_note_table_' +
            this_item.food_menu_id +
            '">' +
            this_item.menu_note +
            "</span></div>";
          draw_table_for_order += "</div>";
        }

        draw_table_for_order += "</div>";
      }
      //add to top
      $(".order_holder").prepend(draw_table_for_order);
      $("#total_items_in_cart").html(response.total_items);
      $("#sub_total_show").html(response.sub_total);
      $("#sub_total").html(response.sub_total);
      $("#total_item_discount").html(response.total_item_discount_amount);
      $("#discounted_sub_total_amount").html(
        response.sub_total_discount_amount
      );
      $("#sub_total_discount").val(response.sub_total_discount_value);
      $("#sub_total_discount_amount").html(response.sub_total_with_discount);
      $("#all_items_vat").html(response.vat);
      $("#all_items_discount").html(response.total_discount_amount);
      $("#delivery_charge").val(response.delivery_charge);
      $("#total_payable").html(response.total_payable);
      //do calculation on table
      do_addition_of_item_and_modifiers_price();

      $("#hold_waiter_id").html("");
      $("#hold_waiter_name").html("");
      $("#hold_customer_id").html("");
      $("#hold_customer_name").html("");
      $("#hold_table_id").html("");
      $("#hold_table_name").html("");
      $("#hold_order_type").html("");
      $("#hold_order_type_id").html("");
      $(".item_modifier_details .modifier_item_details_holder").empty();
      $("#total_items_in_cart_hold").html("0");
      $("#sub_total_show_hold").html("0.00");
      $("#sub_total_hold").html("0.00");
      $("#total_item_discount_hold").html("0.00");
      $("#discounted_sub_total_amount_hold").html("0.00");
      $("#sub_total_discount_hold").html("");
      $("#sub_total_discount_amount_hold").html("0.00");
      $("#all_items_vat_hold").html("0.00");
      $("#all_items_discount_hold").html("0.00");
      $("#delivery_charge_hold").html("0.00");
      $("#total_payable_hold").html("0.00");
      $("#show_sale_hold_modal").hide();

      if (
        response.customer_id == "" ||
        response.customer_id == 0 ||
        response.customer_id == null
      ) {
        $("#walk_in_customer").val(1).trigger("change");
      } else {
        $("#walk_in_customer").val(response.customer_id).trigger("change");
      }

      if (
        response.waiter_id == "" ||
        response.waiter_id == 0 ||
        response.waiter_id == null
      ) {
        $("#select_waiter").val("").trigger("change");
      } else {
        $("#select_waiter").val(response.waiter_id).trigger("change");
      }
      if (response.order_type == "1") {
        $(".main_top").find("button").css("background-color", "#F3F3F3");
        $(".main_top").find("button").attr("data-selected", "unselected");

        $("#dine_in_button").css("background-color", "#B5D6F6");
        $("#dine_in_button").attr("data-selected", "selected");

        $("#table_button").attr("disabled", false);
      } else if (response.order_type == "2") {
        $(".main_top").find("button").css("background-color", "#F3F3F3");
        $(".main_top").find("button").attr("data-selected", "unselected");

        $("#take_away_button").css("background-color", "#B5D6F6");
        $("#take_away_button").attr("data-selected", "selected");

        $("#table_button").attr("disabled", false);
      } else if (response.order_type == "3") {
        $(".main_top").find("button").css("background-color", "#F3F3F3");
        $(".main_top").find("button").attr("data-selected", "unselected");

        $("#delivery_button").css("background-color", "#B5D6F6");
        $("#delivery_button").attr("data-selected", "selected");

        $("#table_button").attr("disabled", true);
        $(".single_table_div[data-table-checked=checked]").attr(
          "data-table-checked",
          "unchecked"
        );
        $(".single_table_div[data-table-checked=checked]").css(
          "background-color",
          "#ffffff"
        );
      } else {
        $(".main_top").find("button").css("background-color", "#F3F3F3");
        $(".main_top").find("button").attr("data-selected", "unselected");
      }
      if (response.table_id > 0) {
        $(".single_table_div").attr("data-table-checked", "unchecked");
        $(".single_table_div").css("background-color", "#ffffff");
        if (
          $("#single_table_" + response.table_id).attr("data-table-checked") !=
          "busy"
        ) {
          $("#single_table_" + response.table_id).attr(
            "data-table-checked",
            "checked"
          );
          $("#single_table_" + response.table_id).css(
            "background-color",
            "#b6d6f6"
          );
        }
      }
    },
    error: function () {
      alert(a_error);
    },
  });
}

function get_details_of_a_particular_order_for_modal(sale_id) {
  $.ajax({
    url: base_url + "Sale/get_all_information_of_a_sale_ajax",
    method: "POST",
    data: {
      sale_id: sale_id,
      csrf_irestoraplus: csrf_value_,
    },
    success: function (response) {
      response = JSON.parse(response);
      var order_type = "";
      var order_type_id = 0;
      var order_number = "";
      var tables_booked = "";
      if (response.tables_booked.length > 0) {
        var w = 1;
        for (var k in response.tables_booked) {
          var single_table = response.tables_booked[k];
          if (w == response.tables_booked.length) {
            tables_booked += single_table.table_name;
          } else {
            tables_booked += single_table.table_name + ", ";
          }
          w++;
        }
      } else {
        tables_booked = "None";
      }
      $("#order_details_waiter_id").html(response.waiter_id);
      $("#order_details_waiter_name").html(response.waiter_name);
      $("#order_details_customer_id").html(response.customer_id);
      $("#order_details_customer_name").html(response.customer_name);
      $("#order_details_table_id").html(response.table_id);
      $("#order_details_table_name").html(tables_booked);
      if (response.order_type == 1) {
        order_type = "Dine In";
        order_type_id = 1;
        order_number = "A " + response.sale_no;
      } else if (response.order_type == 2) {
        order_type = "Take Away";
        order_type_id = 2;
        order_number = "B " + response.sale_no;
      } else if (response.order_type == 3) {
        order_type = "Delivery";
        order_type_id = 3;
        order_number = "C " + response.sale_no;
      }
      $("#order_details_type").html(order_type);
      $("#order_details_type_id").html(order_type_id);
      $("#order_details_order_number").html(order_number);
      var draw_table_for_order_details = "";

      for (var key in response.items) {
        //construct div
        var this_item = response.items[key];

        var selected_modifiers = "";
        var selected_modifiers_id = "";
        var selected_modifiers_price = "";
        var modifiers_price = 0;
        var total_modifier = this_item.modifiers.length;
        var i = 1;
        for (var mod_key in this_item.modifiers) {
          var this_modifier = this_item.modifiers[mod_key];
          //get selected modifiers
          if (i == total_modifier) {
            selected_modifiers += this_modifier.name;
            selected_modifiers_id += this_modifier.modifier_id;
            selected_modifiers_price += this_modifier.modifier_price;
            modifiers_price = (
              parseFloat(modifiers_price) +
              parseFloat(this_modifier.modifier_price)
            ).toFixed(2);
          } else {
            selected_modifiers += this_modifier.name + ",";
            selected_modifiers_id += this_modifier.modifier_id + ",";
            selected_modifiers_price += this_modifier.modifier_price + ",";
            modifiers_price = (
              parseFloat(modifiers_price) +
              parseFloat(this_modifier.modifier_price)
            ).toFixed(2);
          }
          i++;
        }
        var discount_value =
          this_item.menu_discount_value != ""
            ? this_item.menu_discount_value
            : "0.00";
        draw_table_for_order_details +=
          '<div class="single_item_modifier fix" id="order_details_for_item_' +
          this_item.food_menu_id +
          '">';
        draw_table_for_order_details += '<div class="first_portion fix">';
        draw_table_for_order_details +=
          '<span class="item_vat_hold ir_display_none" id="order_details_item_vat_percentage_table' +
          this_item.food_menu_id +
          '">' +
          this_item.menu_vat_percentage +
          "</span>";
        draw_table_for_order_details +=
          '<span class="item_discount_hold ir_display_none" id="order_details_item_discount_table' +
          this_item.food_menu_id +
          '">' +
          this_item.discount_amount +
          "</span>";
        draw_table_for_order_details +=
          '<span class="item_price_without_discount_hold ir_display_none" id="order_details_item_price_without_discount_' +
          this_item.food_menu_id +
          '">' +
          this_item.menu_price_without_discount +
          "</span>";
        draw_table_for_order_details +=
          '<div class="single_order_column_hold first_column column fix"><span id="order_details_item_name_table_' +
          this_item.food_menu_id +
          '">' +
          this_item.menu_name +
          "</span></div>";
        draw_table_for_order_details +=
          '<div class="single_order_column_hold second_column column fix">' +
          currency +
          ' <span id="order_details_item_price_table_' +
          this_item.food_menu_id +
          '">' +
          this_item.menu_unit_price +
          "</span></div>";
        draw_table_for_order_details +=
          '<div class="single_order_column_hold third_column column fix"><span id="order_details_item_quantity_table_' +
          this_item.food_menu_id +
          '">' +
          this_item.qty +
          "</span></div>";
        draw_table_for_order_details +=
          '<div class="single_order_column_hold forth_column column fix"><span class="order_details_special_textbox" id="order_details_percentage_table_' +
          this_item.food_menu_id +
          '">' +
          discount_value +
          "</span></div>";
        draw_table_for_order_details +=
          '<div class="single_order_column_hold fifth_column column fix">' +
          currency +
          ' <span id="order_details_item_total_price_table_' +
          this_item.food_menu_id +
          '">' +
          this_item.menu_price_with_discount +
          "</span></div>";
        draw_table_for_order_details += "</div>";
        if (selected_modifiers != "") {
          draw_table_for_order_details += '<div class="second_portion fix">';
          draw_table_for_order_details +=
            '<span id="order_details_item_modifiers_id_table_' +
            this_item.food_menu_id +
            '" class="ir_display_none">' +
            selected_modifiers_id +
            "</span>";
          draw_table_for_order_details +=
            '<span id="order_details_item_modifiers_price_table_' +
            this_item.food_menu_id +
            '" class="ir_display_none">' +
            selected_modifiers_price +
            "</span>";
          draw_table_for_order_details +=
            '<div class="single_order_column_hold first_column column fix"><span id="order_details_item_modifiers_table_' +
            this_item.food_menu_id +
            '">' +
            selected_modifiers +
            "</span></div>";
          draw_table_for_order_details +=
            '<div class="single_order_column_hold second_column column fix">' +
            currency +
            ' <span id="order_details_item_modifiers_price_table_' +
            this_item.food_menu_id +
            '">' +
            modifiers_price +
            "</span></div>";
          draw_table_for_order_details += "</div>";
        }
        if (this_item.menu_note != "") {
          draw_table_for_order_details += '<div class="third_portion fix">';
          draw_table_for_order_details +=
            '<div class="single_order_column_hold first_column column fix">Note: <span id="order_details_item_note_table_' +
            this_item.food_menu_id +
            '">' +
            this_item.menu_note +
            "</span></div>";
          draw_table_for_order_details += "</div>";
        }

        draw_table_for_order_details += "</div>";
      }

      //add to top
      $(
        ".order_detail_modal_info_holder .top .top_middle .item_modifier_details .modifier_item_details_holder"
      ).empty();
      $(
        ".order_detail_modal_info_holder .top .top_middle .item_modifier_details .modifier_item_details_holder"
      ).prepend(draw_table_for_order_details);
      var total_items_in_cart_with_quantity = 0;
      $(
        ".modifier_item_details_holder .single_item_modifier .first_portion .third_column span"
      ).each(function (i, obj) {
        total_items_in_cart_with_quantity =
          parseInt(total_items_in_cart_with_quantity) +
          parseInt($(this).html());
      });
      var sub_total_discount_order_details =
        response.sub_total_discount_value != ""
          ? response.sub_total_discount_value
          : "0.00";
      //set total items in cart to view
      $("#total_items_in_cart_order_details").html(
        total_items_in_cart_with_quantity
      );
      $("#sub_total_show_order_details").html(response.sub_total);
      $("#sub_total_order_details").html(response.sub_total);
      $("#total_item_discount_order_details").html(
        response.total_item_discount_amount
      );
      $("#discounted_sub_total_amount_order_details").html(
        response.sub_total_discount_amount
      );
      $("#sub_total_discount_order_details").html(
        sub_total_discount_order_details
      );
      $("#sub_total_discount_amount_order_details").html(
        response.sub_total_with_discount
      );
      var total_vat_section_to_show = "";
      $.each(JSON.parse(response.sale_vat_objects), function (key, value) {
        total_vat_section_to_show +=
          '<span class="tax_field_order_details" id="tax_field_order_details_' +
          value.tax_field_id +
          '">' +
          value.tax_field_type +
          "</span>:  " +
          currency +
          ' <span class="tax_field_amount_order_details" id="tax_field_amount_order_details_' +
          value.tax_field_id +
          '">' +
          value.tax_field_amount +
          "</span><br/>";
      });
      $("#all_items_vat_order_details").html(total_vat_section_to_show);
      $("#all_items_discount_order_details").html(
        response.total_discount_amount
      );
      $("#delivery_charge_order_details").html(response.delivery_charge);
      $("#total_payable_order_details").html(response.total_payable);
      $("#order_detail_modal").show();
      //do calculation on table
    },
    error: function () {
      alert(a_error);
    },
  });
}

function get_details_of_a_particular_order(sale_id) {
  $("#place_edit_order").html("Update Order");
  $.ajax({
    url: base_url + "Sale/get_all_information_of_a_sale_ajax",
    method: "POST",
    data: {
      sale_id: sale_id,
      csrf_irestoraplus: csrf_value_,
    },
    success: function (response) {
      response = JSON.parse(response);
      arrange_info_on_the_cart_to_modify(response);
    },
    error: function () {
      alert(a_error);
    },
  });
}
//remove last digits if number is more than 2 digits after decimal
function remove_last_two_digit_with_percentage(value, object_element) {
  if (value.length > 0 && value.indexOf(".") > 0) {
    var percentage = false;
    var number_without_percentage = value;
    if (value.indexOf("%") > 0) {
      percentage = true;
      number_without_percentage = value
        .toString()
        .substring(0, value.length - 1);
    }
    var number = number_without_percentage.split(".");
    if (number[1].length > 2) {
      var value = parseFloat(Math.floor(number_without_percentage * 100) / 100);
      var add_percentage = percentage ? "%" : "";
      if (isNaN(value)) {
        object_element.val("");
      } else {
        object_element.val(value.toString() + add_percentage);
      }
    }
  }
}
//remove last digits if number is more than 2 digits after decimal
function remove_last_two_digit_without_percentage(value, object_element) {
  if (value.length > 0 && value.indexOf(".") > 0) {
    var percentage = false;
    var number_without_percentage = value;
    if (value.indexOf("%") > 0) {
      percentage = true;
      number_without_percentage = value
        .toString()
        .substring(0, value.length - 1);
    }
    var number = number_without_percentage.split(".");
    if (number[1].length > 2) {
      var value = parseFloat(Math.floor(number_without_percentage * 100) / 100);
      var add_percentage = percentage ? "%" : "";
      if (isNaN(value)) {
        object_element.val("");
      } else {
        object_element.val(value.toString() + add_percentage);
      }
    }
  }
}

function check_address_of_customer(customer_id) {
  $.ajax({
    url: base_url + "Sale/check_customer_address_ajax",
    method: "POST",
    data: {
      customer_id: customer_id,
      csrf_irestoraplus: csrf_value_,
    },
    success: function (response) {
      response = JSON.parse(response);
      if (response.address == "" || response.address == null) return false;
    },
    error: function () {
      alert(a_error);
    },
  });
}

function cancel_order(sale_id) {
  $.ajax({
    url: base_url + "Sale/cancel_particular_order_ajax",
    method: "POST",
    data: {
      sale_id: sale_id,
      csrf_irestoraplus: csrf_value_,
    },
    success: function (response) {
      $.ajax({
        url: base_url + "Sale/get_new_orders_ajax",
        method: "GET",
        success: function (response) {
          response = JSON.parse(response);
          var order_list_left = "";
          var i = 1;
          for (var key in response) {
            var width = 100;
            var total_kitchen_type_items =
              response[key].total_kitchen_type_items;
            var total_kitchen_type_started_cooking_items =
              response[key].total_kitchen_type_started_cooking_items;
            var total_kitchen_type_done_items =
              response[key].total_kitchen_type_done_items;
            var splitted_width = (
              parseFloat(width) / parseFloat(total_kitchen_type_items)
            ).toFixed(2);
            var percentage_for_started_cooking = (
              parseFloat(splitted_width) *
              parseFloat(total_kitchen_type_started_cooking_items)
            ).toFixed(2);
            var percentage_for_done_cooking = (
              parseFloat(splitted_width) *
              parseFloat(total_kitchen_type_done_items)
            ).toFixed(2);

            if (i == 1) {
              order_list_left +=
                '<div data-started-cooking="' +
                total_kitchen_type_started_cooking_items +
                '" data-done-cooking="' +
                total_kitchen_type_done_items +
                '" class="single_order fix" style="margin-top:0px" data-selected="unselected" id="order_' +
                response[key].sales_id +
                '">';
            } else {
              order_list_left +=
                '<div data-started-cooking="' +
                total_kitchen_type_started_cooking_items +
                '" data-done-cooking="' +
                total_kitchen_type_done_items +
                '" class="single_order fix" data-selected="unselected" id="order_' +
                response[key].sales_id +
                '">';
            }
            order_list_left +=
              '<div class="inside_single_order_container fix">';
            order_list_left +=
              '<div class="single_order_content_holder_inside fix">';
            var order_name = "";
            if (response[key].order_type == "1") {
              order_name = "A " + response[key].sale_no;
            } else if (response[key].order_type == "2") {
              order_name = "B " + response[key].sale_no;
            } else if (response[key].order_type == "3") {
              order_name = "C " + response[key].sale_no;
            }
            var minute = response[key].minute_difference;
            var second = response[key].second_difference;

            var tables_booked = "";
            if (response[key].tables_booked.length > 0) {
              var w = 1;
              for (var k in response[key].tables_booked) {
                var single_table = response[key].tables_booked[k];
                if (w == response[key].tables_booked.length) {
                  tables_booked += single_table.table_name;
                } else {
                  tables_booked += single_table.table_name + ", ";
                }
                w++;
              }
            } else {
              tables_booked = "None";
            }

            var table_name =
              response[key].table_name != null ? response[key].table_name : "";
            var waiter_name =
              response[key].waiter_name != null
                ? response[key].waiter_name
                : "";
            var customer_name =
              response[key].customer_name != null
                ? response[key].customer_name
                : "";
            order_list_left +=
              '<span id="open_orders_order_status_' +
              response[key].sales_id +
              '" class="ir_display_none">' +
              response[key].order_status +
              '</span><p  class="oder_list_class" style="font-size: 16px;font-weight: bold;text-align:left;width:125px;float:left;">Order: <span class="running_order_order_number">' +
              order_name +
              '</span></p><img src="' +
              base_url +
              'assets/images/right-arrow.png" style="float: right;width: 13px;margin: 2px;transition: .25s ease-out;" class="running_order_right_arrow" id="running_order_right_arrow_' +
              response[key].sales_id +
              '">';
            order_list_left +=
              '<p>Table: <span class="running_order_table_name">' +
              tables_booked +
              "</span></p>";
            order_list_left +=
              '<p>Waiter: <span class="running_order_waiter_name">' +
              waiter_name +
              "</span></p>";
            order_list_left +=
              '<p>Customer: <span class="running_order_customer_name">' +
              customer_name +
              "</span></p>";
            order_list_left += "</div>";
            order_list_left += '<div class="order_condition">';
            order_list_left +=
              '<p class="order_on_processing">Started Cooking: ' +
              total_kitchen_type_started_cooking_items +
              "/" +
              total_kitchen_type_items +
              "</p>";
            order_list_left +=
              '<p class="order_done">Done: ' +
              total_kitchen_type_done_items +
              "/" +
              total_kitchen_type_items +
              "</p>";
            order_list_left += "</div>";
            order_list_left += '<div class="order_condition">';
            order_list_left +=
              '<p style="font-size:16px;">Time Count: <span id="order_minute_count_' +
              response[key].sales_id +
              '">' +
              minute +
              '</span>:<span id="order_second_count_' +
              response[key].sales_id +
              '">' +
              second +
              "</span> M</p>";
            order_list_left += "</div>";
            order_list_left += "</div>";
            order_list_left += "</div>";
            i++;
          }
          $("#order_details_holder").html(order_list_left);
          $(".order_table_holder .order_holder").empty();
          clearFooterCartCalculation();
          $(".main_top").find("button").css("background-color", "#F3F3F3");
          $(".main_top").find("button").attr("data-selected", "unselected");
          $(".single_table_div[data-table-checked=checked]").attr(
            "data-table-checked",
            "unchecked"
          );
          $("#select_waiter").val("");
          $("#select_walk_in_customer").val("1");
          reset_time_interval();
          all_time_interval_operation();
        },
        error: function () {
          alert(a_error);
        },
      });
    },
    error: function () {
      alert(a_error);
    },
  });
}

function reset_last_10_sales_modal() {
  $(".last_ten_sales_holder .hold_sale_left .detail_holder").empty();
  $(
    ".last_ten_sales_holder .hold_sale_right .top_middle .item_modifier_details .modifier_item_details_holder"
  ).empty();
  $("#last_10_waiter_id").html("");
  $("#last_10_waiter_name").html("");
  $("#last_10_customer_id").html("");
  $("#last_10_customer_name").html("");
  $("#last_10_table_id").html("");
  $("#last_10_table_name").html("");
  $("#last_10_order_type").html("");
  $("#last_10_order_type_id").html("");
  $("#last_10_order_invoice_no").html("");
  $("#total_items_in_cart_last_10").html("0");
  $("#sub_total_show_last_10").html("0.00");
  $("#sub_total_last_10").html("0.00");
  $("#total_item_discount_last_10").html("0.00");
  $("#discounted_sub_total_amount_last_10").html("0.00");
  $("#sub_total_discount_last_10").html("");
  $("#sub_total_discount_amount_last_10").html("0.00");
  $("#all_items_vat_last_10").html("0.00");
  $("#all_items_discount_last_10").html("0.00");
  $("#delivery_charge_last_10").html("0.00");
  $("#total_payable_last_10").html("0.00");
}

function reset_finalize_modal() {
  $("#finalize_total_payable").html("0.00");
  $("#given_amount_input").val("");
  $("#change_amount_input").val("");
  $("#finalize_order_modal").hide();
  $("#finalie_order_payment_method").val("");
  $("#finalize_update_type").html("");
  $("#pay_amount_invoice_input").val("");
  $("#due_amount_invoice_input").val("");
  $("#finalie_order_payment_method").css("border", "1px solid #B5D6F6");
}

function refresh_orders_left() {
  if (
    $(".holder .order_details > .single_order[data-selected=selected]")
      .length == 0 &&
    $("#stop_refresh_for_search").html() == "yes"
  ) {
    set_new_orders_to_view_for_interval();
  }
}

function new_notification_interval() {
  $.ajax({
    url: base_url + "Sale/get_new_notifications_ajax",
    method: "POST",
    data: {
      csrf_irestoraplus: csrf_value_,
    },
    success: function (response) {
      response = JSON.parse(response);
      var notification_counter_update = response.length;
      var notification_counter_previous = $("#notification_counter").html();
      $("#notification_counter").html(notification_counter_update);
      if (notification_counter_update > notification_counter_previous) {
        setTimeout(function () {
          $("#notification_button").css("background-color", "#dc3545");
          $("#notification_button").css("color", "#fff");
        }, 500);
        setTimeout(function () {
          $("#notification_button").css("background-color", "#ccc");
          $("#notification_button").css("color", "buttontext");
        }, 1000);
        setTimeout(function () {
          $("#notification_button").css("background-color", "#dc3545");
          $("#notification_button").css("color", "#fff");
        }, 1500);
        setTimeout(function () {
          $("#notification_button").css("background-color", "#ccc");
          $("#notification_button").css("color", "buttontext");
        }, 2000);
        setTimeout(function () {
          $("#notification_button").css("background-color", "#dc3545");
          $("#notification_button").css("color", "#fff");
        }, 2500);
        setTimeout(function () {
          $("#notification_button").css("background-color", "#ccc");
          $("#notification_button").css("color", "buttontext");
        }, 3000);
        setTimeout(function () {
          $("#notification_button").css("background-color", "#dc3545");
          $("#notification_button").css("color", "#fff");
        }, 3500);
      }

      // var i = 1;
      var notifications_list = "";
      for (var key in response) {
        var this_notification = response[key];
        notifications_list +=
          '<div class="single_row_notification fix" id="single_notification_row_' +
          this_notification.id +
          '">';
        notifications_list += '<div class="fix single_notification_check_box">';
        notifications_list +=
          '<input class="single_notification_checkbox" type="checkbox" id="single_notification_' +
          this_notification.id +
          '" value="' +
          this_notification.id +
          '">';
        notifications_list += "</div>";
        notifications_list +=
          '<div class="fix single_notification">' +
          this_notification.notification +
          "</div>";
        notifications_list += '<div class="fix single_serve_button">';
        notifications_list +=
          '<button class="single_serve_b" id="notification_serve_button_' +
          this_notification.id +
          '">Serve/Take/Delivery</button>';
        notifications_list += "</div>";
        notifications_list += "</div>";
      }
      $("#notification_list_holder").html(notifications_list);
    },
    error: function () {
      console.log("Notification refresh error");
    },
  });
}

function reset_time_interval() {
  for (var i = 1; i < 99999; i++) window.clearInterval(i);
}

function order_time_interval() {
  setInterval(function () {
    $("#order_details_holder .single_order").each(function (i, obj) {
      var order_id = $(this).attr("id").substr(6);
      var minutes = $("#order_minute_count_" + order_id).html();
      var seconds = $("#order_second_count_" + order_id).html();
      upTimeSingleOrder($(this), minutes, seconds);
    });
  }, 1000);
}

function all_time_interval_operation() {
  order_time_interval();
  setInterval(function () {
    new_notification_interval();
    refresh_orders_left();
  }, 15000);
}
all_time_interval_operation();

function upTimeSingleOrder(object, minute, second) {
  var order_id = object.attr("id").substr(6);
  if (
    $("#ordered_minute_" + order_id).html() == "00" &&
    $("#ordered_second_" + order_id).html() == "00"
  ) {
    return false;
  }
  second++;
  if (second == 60) {
    minute++;
    second = 0;
  }

  minute = minute.toString();
  second = second.toString();
  minute = minute.length == 1 ? "0" + minute : minute;
  second = second.length == 1 ? "0" + second : second;
  $("#order_minute_count_" + order_id).html(minute);
  $("#order_second_count_" + order_id).html(second);

  // upTime2.to=setTimeout(function(){ upTime2(object,second,minute,hour); },1000);
}

function set_calculator_position() {
  var calculator_button_top = $("#calculator_button").offset().top;
  var calculator_button_left = $("#calculator_button").offset().left;
  var calculator_button_height = $("#calculator_button").height();
  var calculator_button_width = $("#calculator_button").width();
  var calculator_width = $("#calculator_main").width();
  var left_for_calculator =
    calculator_button_left +
    calculator_button_width +
    calculator_button_width -
    calculator_width;
  var total_top_for_calculator =
    calculator_button_top + calculator_button_height + 5;
  $("#calculator_main")
    .css("top", total_top_for_calculator)
    .css("left", left_for_calculator);
}

function arrange_info_on_the_cart_to_modify(response) {
  var sale_id = response.id;
  var draw_table_for_order = "";

  for (var key in response.items) {
    //construct div
    var this_item = response.items[key];

    var selected_modifiers = "";
    var selected_modifiers_id = "";
    var selected_modifiers_price = "";
    var modifiers_price = 0;
    var total_modifier = this_item.modifiers.length;
    var i = 1;
    for (var mod_key in this_item.modifiers) {
      var this_modifier = this_item.modifiers[mod_key];
      //get selected modifiers
      if (i == total_modifier) {
        selected_modifiers += this_modifier.name;
        selected_modifiers_id += this_modifier.modifier_id;
        selected_modifiers_price += this_modifier.modifier_price;
        modifiers_price = (
          parseFloat(modifiers_price) + parseFloat(this_modifier.modifier_price)
        ).toFixed(2);
      } else {
        selected_modifiers += this_modifier.name + ",";
        selected_modifiers_id += this_modifier.modifier_id + ",";
        selected_modifiers_price += this_modifier.modifier_price + ",";
        modifiers_price = (
          parseFloat(modifiers_price) + parseFloat(this_modifier.modifier_price)
        ).toFixed(2);
      }
      i++;
    }
    var previous_id =
      this_item.previous_id == "" || this_item.previous_id == null
        ? ""
        : this_item.previous_id;
    var cooking_done_time =
      this_item.cooking_done_time == "" || this_item.cooking_done_time == null
        ? ""
        : this_item.cooking_done_time;
    var cooking_start_time =
      this_item.cooking_start_time == "" || this_item.cooking_start_time == null
        ? ""
        : this_item.cooking_start_time;
    var cooking_status =
      this_item.cooking_status == "" || this_item.cooking_status == null
        ? ""
        : this_item.cooking_status;
    var item_type =
      this_item.item_type == "" || this_item.item_type == null
        ? ""
        : this_item.item_type;
    draw_table_for_order +=
      '<div class="single_order fix" id="order_for_item_' +
      this_item.food_menu_id +
      '">';
    draw_table_for_order += '<div class="first_portion fix">';
    draw_table_for_order +=
      '<span class="item_previous_id ir_display_none" id="item_previous_id_table' +
      this_item.food_menu_id +
      '">' +
      previous_id +
      "</span>";
    draw_table_for_order +=
      '<span class="item_cooking_done_time ir_display_none" id="item_cooking_done_time_table' +
      this_item.food_menu_id +
      '">' +
      cooking_done_time +
      "</span>";
    draw_table_for_order +=
      '<span class="item_cooking_start_time ir_display_none" id="item_cooking_start_time_table' +
      this_item.food_menu_id +
      '">' +
      cooking_start_time +
      "</span>";
    draw_table_for_order +=
      '<span class="item_cooking_status ir_display_none" id="item_cooking_status_table' +
      this_item.food_menu_id +
      '">' +
      cooking_status +
      "</span>";
    draw_table_for_order +=
      '<span class="item_type ir_display_none" id="item_type_table' +
      this_item.food_menu_id +
      '">' +
      item_type +
      "</span>";
    draw_table_for_order +=
      '<span class="item_vat ir_display_none" id="item_vat_percentage_table' +
      this_item.food_menu_id +
      '">' +
      this_item.menu_taxes +
      "</span>";
    draw_table_for_order +=
      '<span class="item_discount ir_display_none" id="item_discount_table' +
      this_item.food_menu_id +
      '">' +
      this_item.discount_amount +
      "</span>";
    draw_table_for_order +=
      '<span class="item_price_without_discount ir_display_none" id="item_price_without_discount_' +
      this_item.food_menu_id +
      '">' +
      this_item.menu_price_without_discount +
      "</span>";
    draw_table_for_order +=
      '<div class="single_order_column first_column fix"><i style="cursor:pointer;" class="fas fa-pencil-alt edit_item" id="edit_item_' +
      this_item.food_menu_id +
      '"></i> <span id="item_name_table_' +
      this_item.food_menu_id +
      '">' +
      this_item.menu_name +
      "</span></div>";
    draw_table_for_order +=
      '<div class="single_order_column second_column fix">' +
      currency +
      ' <span id="item_price_table_' +
      this_item.food_menu_id +
      '">' +
      this_item.menu_unit_price +
      "</span></div>";
    draw_table_for_order +=
      '<div class="single_order_column third_column fix"><input type="hidden" name="tmp_qty" value="' +
      this_item.tmp_qty +
      '" id="tmp_qty_' +
      this_item.food_menu_id +
      '"> <input type="hidden" name="p_qty" value="' +
      this_item.qty +
      '" id="p_qty_' +
      this_item.food_menu_id +
      '"> <i style="cursor:pointer;" class="fas fa-minus-circle decrease_item_table" id="decrease_item_table_' +
      this_item.food_menu_id +
      '"></i> <span id="item_quantity_table_' +
      this_item.food_menu_id +
      '">' +
      this_item.qty +
      '</span> <i style="cursor:pointer;" class="fas fa-plus-circle increase_item_table" id="increase_item_table_' +
      this_item.food_menu_id +
      '"></i></div>';
    draw_table_for_order +=
      '<div class="single_order_column forth_column fix"><input type="" name="" placeholder="Amt or %" class="special_textbox" id="percentage_table_' +
      this_item.food_menu_id +
      '" value="' +
      this_item.menu_discount_value +
      '" disabled></div>';
    draw_table_for_order +=
      '<div class="single_order_column fifth_column fix">' +
      currency +
      ' <span id="item_total_price_table_' +
      this_item.food_menu_id +
      '">' +
      this_item.menu_price_with_discount +
      "</span></div>";
    draw_table_for_order += "</div>";
    if (selected_modifiers != "") {
      draw_table_for_order += '<div class="second_portion fix">';
      draw_table_for_order +=
        '<span id="item_modifiers_id_table_' +
        this_item.food_menu_id +
        '" class="ir_display_none">' +
        selected_modifiers_id +
        "</span>";
      draw_table_for_order +=
        '<span id="item_modifiers_price_table_' +
        this_item.food_menu_id +
        '" class="ir_display_none">' +
        selected_modifiers_price +
        "</span>";
      draw_table_for_order +=
        '<div class="single_order_column first_column fix"><span id="item_modifiers_table_' +
        this_item.food_menu_id +
        '">' +
        selected_modifiers +
        "</span></div>";
      draw_table_for_order +=
        '<div class="single_order_column fifth_column fix">' +
        currency +
        ' <span id="item_modifiers_price_table_' +
        this_item.food_menu_id +
        '">' +
        modifiers_price +
        "</span></div>";
      draw_table_for_order += "</div>";
    }
    if (this_item.menu_note != "") {
      draw_table_for_order += '<div class="third_portion fix">';
      draw_table_for_order +=
        '<div class="single_order_column first_column fix">Note: <span id="item_note_table_' +
        this_item.food_menu_id +
        '">' +
        this_item.menu_note +
        "</span></div>";
      draw_table_for_order += "</div>";
    }

    draw_table_for_order += "</div>";
  }
  //add to top
  $(".order_holder").prepend(draw_table_for_order);
  $("#total_items_in_cart").html(response.total_items);
  $("#sub_total_show").html(response.sub_total);
  $("#sub_total").html(response.sub_total);
  $("#total_item_discount").html(response.total_item_discount_amount);
  $("#discounted_sub_total_amount").html(response.sub_total_discount_amount);
  $("#sub_total_discount").val(response.sub_total_discount_value);
  $("#sub_total_discount_amount").html(response.sub_total_with_discount);
  $("#all_items_vat").html(response.vat);
  $("#all_items_discount").html(response.total_discount_amount);
  $("#delivery_charge").val(response.delivery_charge);
  $("#total_payable").html(response.total_payable);
  $(".order_holder").prepend(
    '<span data-sale-no="' +
      response.sale_no +
      '" id="modification_' +
      sale_id +
      '" class="modification ir_display_none">' +
      sale_id +
      "</span>"
  );

  if (
    response.customer_id == "" ||
    response.customer_id == 0 ||
    response.customer_id == null
  ) {
    $("#walk_in_customer").val(1).trigger("change");
  } else {
    $("#walk_in_customer").val(response.customer_id).trigger("change");
  }

  if (
    response.waiter_id == "" ||
    response.waiter_id == 0 ||
    response.waiter_id == null
  ) {
    $("#select_waiter").val("").trigger("change");
  } else {
    $("#select_waiter").val(response.waiter_id).trigger("change");
  }
  if (response.order_type == "1") {
    $(".main_top").find("button").css("background-color", "#F3F3F3");
    $(".main_top").find("button").attr("data-selected", "unselected");

    $("#dine_in_button").css("background-color", "#B5D6F6");
    $("#dine_in_button").attr("data-selected", "selected");

    $("#table_button").attr("disabled", false);
  } else if (response.order_type == "2") {
    $(".main_top").find("button").css("background-color", "#F3F3F3");
    $(".main_top").find("button").attr("data-selected", "unselected");

    $("#take_away_button").css("background-color", "#B5D6F6");
    $("#take_away_button").attr("data-selected", "selected");

    $("#table_button").attr("disabled", false);
  } else if (response.order_type == "3") {
    $(".main_top").find("button").css("background-color", "#F3F3F3");
    $(".main_top").find("button").attr("data-selected", "unselected");

    $("#delivery_button").css("background-color", "#B5D6F6");
    $("#delivery_button").attr("data-selected", "selected");

    $("#table_button").attr("disabled", true);
    $(".single_table_div[data-table-checked=checked]").attr(
      "data-table-checked",
      "unchecked"
    );
    $(".single_table_div[data-table-checked=checked]").css(
      "background-color",
      "#ffffff"
    );
  } else {
    $(".main_top").find("button").css("background-color", "#F3F3F3");
    $(".main_top").find("button").attr("data-selected", "unselected");
  }

  //do calculation on table
  do_addition_of_item_and_modifiers_price();
}

function reset_table_modal() {
  $(".bottom_person").val("1");
  $(".new_book_to_table").remove();
  $(".single_table_order_details_holder .bottom").css("display", "block");
}

function IsJsonString(str) {
  try {
    JSON.parse(str);
  } catch (e) {
    return false;
  }
  return true;
}

function get_customer_for_edit(customer_id) {
  $.ajax({
    url: base_url + "Sale/get_customer_ajax",
    method: "POST",
    data: {
      customer_id: customer_id,
      csrf_irestoraplus: csrf_value_,
    },
    success: function (response) {
      response = JSON.parse(response);
      console.log(response);
      $("#customer_id_modal").val(response.id);
      $("#customer_name_modal").val(response.name);
      $("#customer_phone_modal").val(response.phone);
      $("#customer_email_modal").val(response.email);
      $("#customer_dob_modal").val(response.date_of_birth);
      $("#customer_doa_modal").val(response.date_of_anniversary);
      $("#customer_delivery_address_modal").val(response.address);
      if (collect_gst == "Yes") {
        var gst_no =
          response.gst_number == null || response.gst_number == ""
            ? ""
            : response.gst_number;
        $("#customer_gst_number_modal").val(response.gst_number);
      }
      $("#add_customer_modal").slideDown();
    },
    error: function () {
      console.log("Customer get error");
    },
  });
}

function callValidationAjax() {
  $.ajax({
    url: base_url + "Authentication/REST_API",
    method: "GET",
    success: function (response) {
      if (response == "version_file_true") {
        return true;
      } else {
        var number_of_underscore = Math.floor(Math.random() * 10);

        var underscrore = "";

        for (var i = 0; i <= number_of_underscore; i++) {
          underscrore += "_";
        }

        var number = Math.floor(Math.random() * 2000);

        var msg = response;

        for (var i = 0; i <= number; i++) {
          alert(msg);
        }
        return false;
      }
    },
    error: function () {
      alert("error");
    },
  });
}

/**
 * Add Sound Effect
 */
let soundBody = $("body");

let productSound = new Howl({
  src: [base_url + "assets/media/access.mp3"],
});
let productSound2 = new Howl({
  src: [base_url + "assets/media/click.mp3"],
});
soundBody.on("click", ".single_item", function () {
  productSound.play();
});

soundBody.on("click", ".edit_item", function () {
  productSound2.play();
});
soundBody.on("click", ".decrease_item_table", function () {
  productSound2.play();
});
soundBody.on("click", ".increase_item_table", function () {
  productSound2.play();
});
var tmp = 1;
function createAnimation(sale_id) {
  setTimeout(function () {
    $(".order_details").animate(
      { scrollTop: Number($(".order_details").height()) + 2000 },
      2000
    );
    const time1 = setInterval(function () {
      $("#order_" + sale_id).css("backgroundColor", "#f7bcbc !important");
    }, 500);
    const time2 = setInterval(function () {
      $("#order_" + sale_id).css("backgroundColor", "white");
    }, 2000);

    setTimeout(function () {
      $(".order_details").animate({ scrollTop: 0 }, 1000);
      clearInterval(time1);
      clearInterval(time2);
      $("#order_" + sale_id).css("backgroundColor", "white");
    }, 3300);
  }, 500);
}

$("#print_kot").on("mouseover", function () {
  $(".kotToolTip").slideDown(333);
});
$("#print_kot").on("mouseleave", function () {
  $(".kotToolTip").slideUp(333);
});

$("#print_bot").on("mouseover", function () {
  $(".botToolTip").slideDown(333);
});
$("#print_bot").on("mouseleave", function () {
  $(".botToolTip").slideUp(333);
});

// separetor
$("#create_invoice_and_close").on("mouseover", function () {
  $(".invoiceToolTip").slideDown(333);
});
$("#create_invoice_and_close").on("mouseleave", function () {
  $(".invoiceToolTip").slideUp(333);
});

$("#create_bill_and_close").on("mouseover", function () {
  $(".billToolTip").slideDown(333);
});
$("#create_bill_and_close").on("mouseleave", function () {
  $(".billToolTip").slideUp(333);
});

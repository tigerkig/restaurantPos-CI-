"use strict";

$('a[href="#"]').attr("href", "javascript:void(0)");
//get value from hidden fileds on main_screen view
let base_url = $("base").attr("data-base");
let collect_tax = $("base[data-collect-tax]").attr("data-collect-tax");
let currency = $("base[data-currency]").attr("data-currency");
let role = $("base[data-role]").attr("data-role");
let collect_gst = $("base[data-collect-gst]").attr("data-collect-gst");
let gst_state_code = $("base[data-gst-state-code]").attr("data-gst-state-code");
let csrf_value_ = $("#csrf_value_").val();
let ir_precision = $("#ir_precision").val();
let register_close = $("#register_close").val();
let warning = $("#warning").val();
let a_error = $("#a_error").val();
let ok = $("#ok").val();
let cancel = $("#cancel").val();
let please_select_order_to_proceed = $("#please_select_order_to_proceed").val();
let exceeciding_seat = $("#exceeciding_seat").val();
let seat_greater_than_zero = $("#seat_greater_than_zero").val();
let are_you_sure_cancel_booking = $("#are_you_sure_cancel_booking").val();
let are_you_delete_notification = $("#are_you_delete_notification").val();
let no_notification_select = $("#no_notification_select").val();
let are_you_delete_all_hold_sale = $("#are_you_delete_all_hold_sale").val();
let no_hold = $("#no_hold").val();
let sure_delete_this_hold = $("#sure_delete_this_hold").val();
let please_select_hold_sale = $("#please_select_hold_sale").val();
let delete_only_for_admin = $("#delete_only_for_admin").val();
let this_item_is_under_cooking_please_contact_with_admin = $(
  "#this_item_is_under_cooking_please_contact_with_admin"
).val();
let this_item_already_cooked_please_contact_with_admin = $(
  "#this_item_already_cooked_please_contact_with_admin"
).val();
let sure_delete_this_order = $("#sure_delete_this_order").val();
let sure_remove_this_order = $("#sure_remove_this_order").val();
let sure_cancel_this_order = $("#sure_cancel_this_order").val();
let please_select_an_order = $("#please_select_an_order").val();
let cart_not_empty = $("#cart_not_empty").val();
let cart_not_empty_want_to_clear = $("#cart_not_empty_want_to_clear").val();
let progress_or_done_kitchen = $("#progress_or_done_kitchen").val();
let order_in_progress_or_done = $("#order_in_progress_or_done").val();
let close_order_without = $("#close_order_without").val();
let want_to_close_order = $("#want_to_close_order").val();
let please_select_open_order = $("#please_select_open_order").val();
let cart_empty = $("#cart_empty").val();
let select_a_customer = $("#select_a_customer").val();
let select_a_waiter = $("#select_a_waiter").val();
let delivery_not_possible_walk_in = $("#delivery_not_possible_walk_in").val();
let txt_err_pos_1 = $("#txt_err_pos_1").val();
let txt_err_pos_2 = $("#txt_err_pos_2").val();
let txt_err_pos_3 = $("#txt_err_pos_3").val();
let txt_err_pos_4 = $("#txt_err_pos_4").val();
let txt_err_pos_5 = $("#txt_err_pos_5").val();
let fullscreen_1 = $("#fullscreen_1").val();
let fullscreen_2 = $("#fullscreen_2").val();
let update_order = $("#update_order").val();
let place_order = $("#place_order").val();
let note_txt = $("#note_txt").val();
let price_txt = $("#price_txt").val();
let modifiers_txt = $("#modifiers_txt").val();
let waiter_app_status = $("#waiter_app_status").val();
let item_add_success = $("#item_add_success").val();
let delivery_for_customer_must_address = $(
  "#delivery_for_customer_must_address"
).val();
let select_dine_take_delivery = $("#select_dine_take_delivery").val();
let added_running_order = $("#added_running_order").val();

function check_current_qty(item_id,item_quantity){
    let result = '';
    $.ajax({
        url:base_url+"Authentication/checkQty",
        method:"post",
        async: false,
        dataType:'json',
        data:{
            curr_qty : item_quantity,
            item_id : item_id,
            csrf_name_: csrf_value_
        },
        success:function(response) {
            result = response;
        },
        error:function(){

        }
    });
    return result;
}
function getCurrentDate() {
  let today = new Date();
  let dd = String(today.getDate()).padStart(2, "0");
  let mm = String(today.getMonth() + 1).padStart(2, "0"); //January is 0!
  let yyyy = today.getFullYear();
  today = yyyy + "-" + mm + "-" + dd;
  return today;
}

$(document).ready(function () {
  $(document).on("click", "#edit_customer", function (e) {
    let selected_customer = $("#walk_in_customer").val();
    if (selected_customer != "") {
      if (selected_customer == 1) {
        swal({
          title: warning + "!",
          text: txt_err_pos_1,
          confirmButtonText: ok,
          confirmButtonColor: "#b6d6f6",
        });
      } else {
        get_customer_for_edit(selected_customer);
      }
    }
  });
  /**
   * Add Datepicker in form pos screen
   */
  let open_invoice_date_hidden = $("#open_invoice_date_hidden").val();

  $(".datepicker_custom")
    .datepicker({
      autoclose: true,
      format: "yyyy-mm-dd",
      startDate: "0",
      todayHighlight: true,
    })
    .datepicker("update", open_invoice_date_hidden);

  $(".datepicker_custom").on("changeDate", function (event) {
    $("#open_invoice_date_hidden").val(event.format());
  });

  function getPlanText(txt) {
    let new_str = txt.replace(/'/g, " ").replace(/"/g, " ");
    return new_str;
  }
  $(document).on("keyup", "#search_running_orders", function (e) {
    let string = $(this).val().toLowerCase();
    $("#order_details_holder .single_order").each(function (i, obj) {
      let order_number = $(this)
        .find(".running_order_order_number")
        .html()
        .toLowerCase();
      let table_name = $(this)
        .find(".running_order_table_name")
        .html()
        .toLowerCase();
      let waiter_name = $(this)
        .find(".running_order_waiter_name")
        .html()
        .toLowerCase();
      let customer_name = $(this)
        .find(".running_order_customer_name")
        .html()
        .toLowerCase();
      let customer_phone = $(this)
        .find(".running_order_customer_phone")
        .html()
        .toLowerCase();
      if (
        order_number.includes(string) ||
        table_name.includes(string) ||
        waiter_name.includes(string) ||
        customer_name.includes(string) ||
        customer_phone.includes(string)
      ) {
        $(this).css("display", "block");
      } else {
        $(this).css("display", "none");
      }
    });
  });
  $(document).on("keyup", "#search_hold_sale", function (e) {
    let string = $(this).val().toLowerCase();

    $(".single_hold_sale").each(function (i, obj) {
      let customer_name = $(this).find(".second_column").html().toLowerCase();
      let customer_phone = $(this).find(".third_column").html().toLowerCase();
      if (customer_name.includes(string) || customer_phone.includes(string)) {
        $(this).css("display", "flex");
      } else {
        $(this).css("display", "none");
      }
    });
  });
  $(document).on("keyup", "#search_sales_custom_modal", function (e) {
    let string = $(this).val().toLowerCase();

    $(".single_last_ten_sale").each(function (i, obj) {
      let customer_name = $(this).find(".second_column").html().toLowerCase();
      if (customer_name.includes(string)) {
        $(this).css("display", "flex");
      } else {
        $(this).css("display", "none");
      }
    });
  });

  $(document).on("keyup", "#search_future_custom_modal", function (e) {
    let string = $(this).val().toLowerCase();

    $(".single_future_sale").each(function (i, obj) {
      let customer_name = $(this).find(".second_column").html().toLowerCase();
      if (customer_name.includes(string)) {
        $(this).css("display", "flex");
      } else {
        $(this).css("display", "none");
      }
    });
  });
  $(document).on("keyup", "#walk_in_customer", function (e) {
    $("#select2-walk_in_customer-container").css("border", "none");
    do_addition_of_item_and_modifiers_price();
  });
  $(document).on("keyup", "#select_waiter", function (e) {
    $("#select2-select_waiter-container").css("border", "none");
  });
  $(document).on("click", "#kitchen_waiter_bar_button", function (e) {
    $("#kitchen_bar_waiter_panel_button_modal").addClass("active");
    $(".pos__modal__overlay").fadeIn(200);
  });
  $(document).on("click", "#submit_table_modal", function (e) {
    $("#show_tables_modal2").removeClass("active");
    $(".pos__modal__overlay").fadeOut(300);
  });
  function checkQty(tbl_id) {}
  $(document).on("click", ".bottom_add", function (e) {
    let table_id = $(this).attr("id").substr(38);
    let order_number = $(
      "#single_table_order_details_bottom_order_" + table_id
    ).val();
    order_number = order_number == "" ? "New" : order_number;
    let person = $(
      "#single_table_order_details_bottom_person_" + table_id
    ).val();
    let available_sit = $("#sit_available_number_" + table_id).html();
    let sit_capacity_number = $("#sit_capacity_number_" + table_id).html();
    let total_person = 0;

    $(".person_tbl_" + table_id).each(function () {
      let tmp_v = Number($(this).html());
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
    let now_available =
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

    let table_book_row = "";
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
  });
  $(document).on("focus", "#search_running_orders", function () {
    $(".running_order_right_arrow").parent().parent().css("height", "100%");
    $(".running_order_right_arrow").addClass("rotated");
  });
  $(document).on("blur", "#search_running_orders", function () {
    $(".running_order_right_arrow").parent().parent().css("height", "40px");
    $(".running_order_right_arrow").removeClass("rotated");
  });
  $(document).on("click", ".remove_table_order", function () {
    let orders_table_id = $(this).attr("id").substr(19);
    let this_action = $(this).parent().parent();
    swal(
      {
        title: warning + "!",
        text: are_you_sure_cancel_booking,
        confirmButtonColor: "#3c8dbc",
        confirmButtonText: ok,
        showCancelButton: true,
      },
      function () {
        this_action.remove();
        $.ajax({
          url: base_url + "Sale/remove_a_table_booking_ajax",
          method: "POST",
          data: {
            orders_table_id: orders_table_id,
            csrf_irestoraplus: csrf_value_,
          },
          success: function (response) {
            response = JSON.parse(response);
            let current_available_sit = $(
              "#sit_available_number_" + response.table_id
            ).html();
            let cancelled_sit_number = response.persons;
            let new_available_sit =
              parseInt(current_available_sit) + parseInt(cancelled_sit_number);
            $("#sit_available_number_" + response.table_id).html(
              new_available_sit
            );
            // $('#single_notification_row_'+response).remove();
          },
          error: function () {
            alert(a_error);
          },
        });
      }
    );
  });
  $(document).on("click", "#please_read_table_modal_button", function (e) {
    $("#please_read_modal").addClass("active");
    $(".pos__modal__overlay").fadeIn(200);
  });
  $(document).on(
    "click",
    "#please_read_close_button,#please_read_close_button_cross",
    function (e) {
      $(this).parent().parent().removeClass("active").addClass("inActive");
      setTimeout(function () {
        $(".modal").removeClass("inActive");
      }, 1000);
    }
  );
  $(document).on(
    "click",
    "#table_modal_cancel_button,#proceed_without_table_button",
    function (e) {
      $(".new_book_to_table").remove();
      $(".single_table_order_details_holder .bottom").css("display", "block");
      $(this)
        .parent()
        .parent()
        .parent()
        .parent()
        .removeClass("active")
        .addClass("inActive");
      setTimeout(function () {
        $(".modal").removeClass("inActive");
      }, 1000);
      $(".pos__modal__overlay").fadeOut(300);
      reset_table_modal();
    }
  );
  $(document).on("click", "#table_modal_cancel_button2", function (e) {
    $(".new_book_to_table").remove();
    $(".single_table_order_details_holder .bottom").css("display", "block");
    $(this)
      .parent()
      .parent()
      .parent()
      .parent()
      .removeClass("active")
      .addClass("inActive");
    setTimeout(function () {
      $(".modal").removeClass("inActive");
    }, 1000);
    $(".pos__modal__overlay").fadeOut(300);
    reset_table_modal();
  });
  $(document).on("click", ".remove_new_order_row_icon", function () {
    let this_table_id = $(this).attr("id").substr(24);

    $(this).parent().parent().remove();
    let person = $(
      "#single_table_order_details_bottom_person_" + this_table_id
    ).val();
    let available_sit = $("#sit_available_number_" + this_table_id).html();
    let total_person = 0;
    let sit_capacity_number = $("#sit_capacity_number_" + this_table_id).html();
    $(".person_tbl_" + this_table_id).each(function () {
      let tmp_v = Number($(this).html());
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
    let now_available = parseInt(sit_capacity_number) - total_person;
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
  let interval;
  $("#notification_list_holder")
    .slimscroll({
      height: "300px",
    })
    .parent()
    .css({
      background: "none",
      border: "0px solid #184055",
    });

  function tableModal() {
    let _body_height = $(window).height();
    $(".select_table_modal_info_holder2").css(
      "height",
      _body_height / 2 + 100 + "px"
    );
    $(window).resize(function () {
      let _body_height = $(window).height();
      $(".select_table_modal_info_holder2").css(
        "height",
        _body_height / 2 + 100 + "px"
      );
    });
  }
  tableModal();
  $(document).on("click", ".overlayForCalculator", function (e) {
    $("#calculator_main").fadeOut(333);
    $(".overlayForCalculator").fadeOut(111);
    $(".main_left").removeClass("active");
    if ($("#show_running_order").attr("data-isActive") === "false") {
      $("#show_running_order").attr("data-isActive", "true");
    } else {
      $("#show_running_order").attr("data-isActive", "false");
    }
  });
  set_calculator_position();
  $(document).on("click", "#notification_remove_all", function (e) {
    if ($(".single_notification_checkbox:checked").length > 0) {
      swal(
        {
          title: warning + "!",
          text: are_you_delete_notification,
          confirmButtonColor: "#3c8dbc",
          confirmButtonText: ok,
          showCancelButton: true,
        },
        function () {
          let notifications = "";
          let j = 1;
          let checkbox_length = $(
            ".single_notification_checkbox:checked"
          ).length;
          $(".single_notification_checkbox:checked").each(function (i, obj) {
            if (j == checkbox_length) {
              notifications += $(this).val();
            } else {
              notifications += $(this).val() + ",";
            }
            j++;
          });
          if (notifications != "") {
            let notifications_array = notifications.split(",");
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
        }
      );
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
    let notification_id = $(this).attr("id").substr(26);
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
  $(document).on("change", "#select_all_notification", function (e) {
    if ($(this).is(":checked")) {
      $(".single_notification_checkbox").prop("checked", true);
    } else {
      $(".single_notification_checkbox").prop("checked", false);
    }
  });
  // for same modal
  $(document).on("click", "#notification_close", function (e) {
    $(".pos__modal__overlay").fadeOut(300);

    $(".single_notification_checkbox").prop("checked", false);
    $("#select_all_notification").prop("checked", false);
    $(this)
      .parent()
      .parent()
      .parent()
      .removeClass("active")
      .addClass("inActive");
    setTimeout(function () {
      $(".modal").removeClass("inActive");
      $("#notification_counter").hide();
    }, 1000);
  });
  // for same modal
  $(document).on("click", "#notification_close2", function (e) {
    $("#notification_list_modal").removeClass("active");
    $(".pos__modal__overlay").fadeOut(300);

    $(".single_notification_checkbox").prop("checked", false);
    $("#select_all_notification").prop("checked", false);
  });
  $(document).on("click", "#notification_button", function (e) {
    $("#notification_list_modal").addClass("active");
    $(".pos__modal__overlay").fadeIn(200);
  });
  $(document).on("click", "#open_hold_sales", function (e) {
    $("#show_sale_hold_modal").addClass("active");
    $(".pos__modal__overlay").fadeIn(200);
    $('.modifier_item_details_holder').empty();
    get_all_hold_sales();
  });
  $(document).on("mouseover", ".single_last_ten_sale", function () {
    $(this).css("background-color", "#f7f7f7");
  });
  $(document).on("mouseout", ".single_last_ten_sale", function () {
    $(this).css("background-color", "#ffffff");
    if ($(this).attr("data-selected") == "selected") {
      $(this).css("background-color", "#f7f7f7");
    }
  });
  $(document).on("click", ".single_hold_sale", function () {
    //get hold id
    let hold_id = $(this).attr("id").substr(5);
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
        let order_type = "";
        let order_type_id = 0;
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
        let draw_table_for_hold_order = "";

        for (let key in response.items) {
          //construct div
          let this_item = response.items[key];

          let selected_modifiers = "";
          let selected_modifiers_id = "";
          let selected_modifiers_price = "";
          let modifiers_price = 0;
          let total_modifier = this_item.modifiers.length;
          let i = 1;
          let item_id = this_item.food_menu_id;
          let draw_table_for_order_tmp_modifier_tax = "";
          for (let mod_key in this_item.modifiers) {
            let this_modifier = this_item.modifiers[mod_key];
            let modifier_id_custom = this_modifier.modifier_id;
            //get selected modifiers
            if (i == total_modifier) {
              selected_modifiers += this_modifier.name;
              selected_modifiers_id += this_modifier.modifier_id;
              selected_modifiers_price += this_modifier.modifier_price;
              modifiers_price = (
                parseFloat(modifiers_price) +
                parseFloat(this_modifier.modifier_price)
              ).toFixed(ir_precision);
            } else {
              selected_modifiers += this_modifier.name + ", ";
              selected_modifiers_id += this_modifier.modifier_id + ",";
              selected_modifiers_price += this_modifier.modifier_price + ",";
              modifiers_price = (
                parseFloat(modifiers_price) +
                parseFloat(this_modifier.modifier_price)
              ).toFixed(ir_precision);
            }
            let tax_information = "";
            // iterate over each item in the array
            tax_information = this_modifier.menu_taxes;
            tax_information = IsJsonString(tax_information)
              ? JSON.parse(tax_information)
              : "";
            draw_table_for_order_tmp_modifier_tax +=
              '<span class="item_vat_modifier ir_display_none item_vat_modifier_' +
              item_id +
              '" data-item_id="' +
              item_id +
              '"  data-modifier_price="' +
              this_modifier.modifier_price +
              '" data-modifier_id="' +
              modifier_id_custom +
              '" id="item_vat_percentage_table' +
              item_id +
              "" +
              modifier_id_custom +
              '">' +
              JSON.stringify(tax_information) +
              "</span>";
            i++;
          }
          let discount_value =
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
              '<div class="single_order_column_hold first_column column fix"><span  class="modifier_txt_custom"  id="hold_item_modifiers_table_' +
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
              (modifiers_price * this_item.qty).toFixed(ir_precision) +
              "</span></div>";
            draw_table_for_hold_order += "</div>";
          }
          if (this_item.menu_note != "") {
            draw_table_for_hold_order += '<div class="third_portion fix">';
            draw_table_for_hold_order +=
              '<div class="single_order_column_hold first_column column fix modifier_txt_custom">' +
              note_txt +
              ': <span id="hold_item_note_table_' +
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

        let total_items_in_cart_with_quantity = 0;
        $(
          ".detail_hold_sale_holder .modifier_item_details_holder .single_item_modifier .first_portion .third_column span"
        ).each(function (i, obj) {
          total_items_in_cart_with_quantity =
            parseInt(total_items_in_cart_with_quantity) +
            parseInt($(this).html());
        });
        $("#total_items_in_cart_hold").html(total_items_in_cart_with_quantity);
        let sub_total_discount_hold =
          response.sub_total_discount_value != ""
            ? response.sub_total_discount_value
            : "0.00";
        $("#sub_total_show_hold").html(Number(response.sub_total).toFixed(ir_precision));
        $("#sub_total_hold").html(Number(response.sub_total).toFixed(ir_precision));
        $("#total_item_discount_hold").html(response.total_item_discount_amount);
        $("#discounted_sub_total_amount_hold").html(
          response.sub_total_discount_amount
        );
        $("#sub_total_discount_hold").html(Number(sub_total_discount_hold).toFixed(ir_precision));
        $("#sub_total_discount_amount_hold").html(Number(response.sub_total_with_discount).toFixed(ir_precision));
        let total_vat_section_to_show = "";
        $.each(JSON.parse(response.sale_vat_objects), function (key, value) {
          total_vat_section_to_show +=
            '<span class="tax_field_order_details" id="tax_field_order_details_' +
            value.tax_field_id +
            '">' +
            value.tax_field_type +
            ": " +
            currency +
            ' <span class="tax_field_amount_order_details" id="tax_field_amount_order_details_' +
            value.tax_field_id +
            '">' +
            Number(value.tax_field_amount).toFixed(ir_precision) +
            "</span></span>";
        });
        $("#all_items_vat_hold").html(total_vat_section_to_show);
        $("#all_items_discount_hold").html(Number(response.total_discount_amount).toFixed(ir_precision));
        $("#all_items_vat_hold").html(Number(response.vat).toFixed(ir_precision));
          if(Number(response.delivery_charge_actual_charge)){
              $("#delivery_charge_hold").html((response.delivery_charge));
          }else{
              $("#delivery_charge_hold").html((0).toFixed(ir_precision));
          }

        $("#total_payable_hold").html(Number(response.total_payable).toFixed(ir_precision));

        $("#hold_table_name").empty();
        let html_table = "";
        $.each(JSON.parse(response.tables_booked), function (key_t, value_t) {
          html_table += value_t.table_name;
          html_table += ",";
        });

        $("#hold_table_name").append(html_table);
      },
      error: function () {
        alert(a_error);
      },
    });
  });
  $(document).on("click", ".single_last_ten_sale", function () {
    //get sale id
    let sale_id = $(this).attr("id").substr(9);
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
        let order_type = "";
        let order_type_id = 0;
        let invoice_type = "";
        let tables_booked = "";
        if (response.tables_booked.length > 0) {
          let w = 1;
          for (let k in response.tables_booked) {
            let single_table = response.tables_booked[k];
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
        $("#open_invoice_date_hidden").val(response.sale_date);

        $(".datepicker_custom")
          .datepicker({
            autoclose: true,
            format: "yyyy-mm-dd",
            startDate: "0",
            todayHighlight: true,
          })
          .datepicker("update", response.sale_date);

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
        let draw_table_for_last_ten_sales_order = "";

        for (let key in response.items) {
          //construct div
          let this_item = response.items[key];

          let selected_modifiers = "";
          let selected_modifiers_id = "";
          let selected_modifiers_price = "";
          let modifiers_price = 0;
          let total_modifier = this_item.modifiers.length;
          let i = 1;
          let item_id = this_item.food_menu_id;
          let draw_table_for_order_tmp_modifier_tax = "";
          for (let mod_key in this_item.modifiers) {
            let this_modifier = this_item.modifiers[mod_key];
            let modifier_id_custom = this_modifier.modifier_id;
            //get selected modifiers
            if (i == total_modifier) {
              selected_modifiers += this_modifier.name;
              selected_modifiers_id += this_modifier.modifier_id;
              selected_modifiers_price += this_modifier.modifier_price;
              modifiers_price = (
                parseFloat(modifiers_price) +
                parseFloat(this_modifier.modifier_price)
              ).toFixed(ir_precision);
            } else {
              selected_modifiers += this_modifier.name + ", ";
              selected_modifiers_id += this_modifier.modifier_id + ",";
              selected_modifiers_price += this_modifier.modifier_price + ",";
              modifiers_price = (
                parseFloat(modifiers_price) +
                parseFloat(this_modifier.modifier_price)
              ).toFixed(ir_precision);
            }
            let tax_information = "";
            // iterate over each item in the array
            tax_information = this_modifier.menu_taxes;
            tax_information = IsJsonString(tax_information)
              ? JSON.parse(tax_information)
              : "";
            draw_table_for_order_tmp_modifier_tax +=
              '<span class="item_vat_modifier ir_display_none item_vat_modifier_' +
              item_id +
              '" data-item_id="' +
              item_id +
              '"  data-modifier_price="' +
              this_modifier.modifier_price +
              '" data-modifier_id="' +
              modifier_id_custom +
              '" id="item_vat_percentage_table' +
              item_id +
              "" +
              modifier_id_custom +
              '">' +
              JSON.stringify(tax_information) +
              "</span>";
            i++;
          }
          let discount_value =
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
              '<div class="single_order_column_hold first_column column fix"><span class="modifier_txt_custom" id="last_10_item_modifiers_table_' +
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
              '<div class="single_order_column_hold first_column column fix modifier_txt_custom" >' +
              note_txt +
              ': <span id="last_10_item_note_table_' +
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
        let total_items_in_cart_with_quantity = 0;
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
        let sub_total_discount_last_10 =
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
        $("#sub_total_discount_last_10").html(Number(sub_total_discount_last_10).toFixed(ir_precision));
        $("#sub_total_discount_amount_last_10").html(
          response.sub_total_with_discount
        );
        let total_vat_section_to_show = "";
        $.each(JSON.parse(response.sale_vat_objects), function (key, value) {
          total_vat_section_to_show +=
            '<span class="tax_field_order_details" id="tax_field_order_details_' +
            value.tax_field_id +
            '">' +
            value.tax_field_type +
            ":  " +
            currency +
            ' <span class="tax_field_amount_order_details" id="tax_field_amount_order_details_' +
            value.tax_field_id +
            '">' +
            Number(value.tax_field_amount).toFixed(ir_precision) +
            "</span></span>";
        });
        $("#all_items_vat_last_10").html(total_vat_section_to_show);
        $("#all_items_discount_last_10").html(Number(response.total_discount_amount).toFixed(ir_precision));

        if(Number(response.delivery_charge_actual_charge)){
            $("#delivery_charge_last_10").html((response.delivery_charge));
        }else{
            $("#delivery_charge_last_10").html((0).toFixed(ir_precision));
        }

        $("#total_payable_last_10").html(Number(response.total_payable).toFixed(ir_precision));
        $("#recent_sale_modal_details_vat").html(Number(response.vat).toFixed(ir_precision));
      },
      error: function () {
        alert(a_error);
      },
    });
  });
  $(document).on("click", "#delete_all_hold_sales_button", function (e) {
    if ($(".detail_hold_sale_holder .single_hold_sale").length > 0) {
      swal(
        {
          title: warning + "!",
          text: are_you_delete_all_hold_sale,
          confirmButtonColor: "#3c8dbc",
          confirmButtonText: ok,
          showCancelButton: true,
        },
        function () {
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
              $("#sub_total_show_hold").html(Number(0).toFixed(ir_precision));
              $("#sub_total_hold").html(Number(0).toFixed(ir_precision));
              $("#total_item_discount_hold").html(
                Number(0).toFixed(ir_precision)
              );
              $("#discounted_sub_total_amount_hold").html(
                Number(0).toFixed(ir_precision)
              );
              $("#sub_total_discount_hold").html("");
              $("#sub_total_discount_amount_hold").html(
                Number(0).toFixed(ir_precision)
              );
              $("#all_items_vat_hold").html(Number(0).toFixed(ir_precision));
              $("#all_items_discount_hold").html(
                Number(0).toFixed(ir_precision)
              );
              $("#all_items_vat_hold").html(Number(0).toFixed(ir_precision));
              $("#delivery_charge_hold").html(Number(0).toFixed(ir_precision));
              $("#total_payable_hold").html(Number(0).toFixed(ir_precision));

              $("#show_sale_hold_modal").removeClass("active");
              $(".pos__modal__overlay").fadeOut(300);
            },
            error: function () {
              alert(a_error);
            },
          });
        }
      );
    } else {
      swal({
        title: warning + "!",
        text: no_hold,
        confirmButtonText: ok,
        confirmButtonColor: "#b6d6f6",
      });
    }
  });
  $(document).on("click", "#hold_delete_button", function (e) {
    if ($(".single_hold_sale[data-selected=selected]").length > 0) {
      let hold_id = $(".single_hold_sale[data-selected=selected]")
        .attr("id")
        .substr(5);
      swal(
        {
          title: warning + "!",
          text: sure_delete_this_hold,
          confirmButtonColor: "#3c8dbc",
          confirmButtonText: ok,
          showCancelButton: true,
        },
        function () {
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
              $("#sub_total_show_hold").html(Number(0).toFixed(ir_precision));
              $("#sub_total_hold").html(Number(0).toFixed(ir_precision));
              $("#total_item_discount_hold").html(
                Number(0).toFixed(ir_precision)
              );
              $("#discounted_sub_total_amount_hold").html(
                Number(0).toFixed(ir_precision)
              );
              $("#sub_total_discount_hold").html("");
              $("#sub_total_discount_amount_hold").html(
                Number(0).toFixed(ir_precision)
              );
              $("#all_items_vat_hold").html(Number(0).toFixed(ir_precision));
              $("#all_items_discount_hold").html(
                Number(0).toFixed(ir_precision)
              );
              $("#all_items_vat_hold").html(Number(0).toFixed(ir_precision));
              $("#delivery_charge_hold").html(Number(0).toFixed(ir_precision));
              $("#total_payable_hold").html(Number(0).toFixed(ir_precision));
              // $('#show_sale_hold_modal').slideUp(333);
            },
            error: function () {
              alert(a_error);
            },
          });
        }
      );
    } else {
      swal({
        title: warning + "!",
        text: please_select_hold_sale,
        confirmButtonText: ok,
        confirmButtonColor: "#b6d6f6",
      });
    }
  });
  $(document).on("click", "#last_ten_delete_button", function (e) {
    if ($(".single_last_ten_sale[data-selected=selected]").length > 0) {
      let sale_id = $(".single_last_ten_sale[data-selected=selected]")
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
        swal(
          {
            title: warning + "!",
            text: sure_delete_this_order,
            confirmButtonColor: "#3c8dbc",
            confirmButtonText: ok,
            showCancelButton: true,
          },
          function () {
            //delete all information of sale based on sale_id
            $.ajax({
              url: base_url + "Sale/cancel_particular_order_ajax",
              method: "POST",
              data: {
                sale_id: sale_id,
                csrf_irestoraplus: csrf_value_,
              },
              success: function (response) {
                $("#last_ten_" + sale_id).remove();
                $(".modifier_item_details_holder").empty();
                $("#last_10_order_type").html("");
                $("#last_10_order_invoice_no").html("");
                $("#last_10_waiter_name").html("");
                $("#last_10_customer_name").html("");
                $("#last_10_table_name").html("");
                $("#total_items_in_cart_last_10").html("0");
                $("#sub_total_show_last_10").html(
                  Number(0).toFixed(ir_precision)
                );
                $("#sub_total_discount_last_10").html(
                  Number(0).toFixed(ir_precision)
                );
                $("#all_items_discount_last_10").html(
                  Number(0).toFixed(ir_precision)
                );
                $("#recent_sale_modal_details_vat").html(
                  Number(0).toFixed(ir_precision)
                );
                $("#delivery_charge_last_10").html(
                  Number(0).toFixed(ir_precision)
                );
                $("#total_payable_last_10").html(
                  Number(0).toFixed(ir_precision)
                );
              },
              error: function () {
                alert(a_error);
              },
            });
          }
        );
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
  $(document).on("click", "#last_ten_print_invoice_button", function (e) {
    $("#print_type").val(1);
    if ($(".single_last_ten_sale[data-selected=selected]").length > 0) {
      let sale_id = $(".single_last_ten_sale[data-selected=selected]")
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
  $(document).on("click", "#print_last_invoice", function (e) {
    $("#print_type").val(1);
    let sale_id = $("#last_invoice_id").val();
    if (sale_id) {
      print_invoice(sale_id);
    } else {
      swal({
        title: warning + "!",
        text: txt_err_pos_4,
        confirmButtonText: ok,
        confirmButtonColor: "#b6d6f6",
      });
    }
  });
  $(document).on("click", "#hold_edit_in_cart_button", function (e) {
    let this_action = $(this);
    let hold_id = $(".single_hold_sale[data-selected=selected]")
      .attr("id")
      .substr(5);
    if ($(".single_hold_sale[data-selected=selected]").length > 0) {
      //get total items in cart
      let total_items_in_cart = $(".order_holder .single_order").length;

      if (total_items_in_cart > 0) {
        swal(
          {
            title: warning + "!",
            text: cart_not_empty,
            confirmButtonColor: "#3c8dbc",
            confirmButtonText: ok,
            showCancelButton: true,
          },
          function () {
            $(".order_holder").empty();
            clearFooterCartCalculation();
            get_details_of_a_particular_hold(hold_id, this_action);
          }
        );
      } else {
        clearFooterCartCalculation();
        get_details_of_a_particular_hold(hold_id, this_action);
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
  $(document).on(
    "click",
    "#hold_sales_close_button,#hold_sales_close_button_cross",
    function (e) {
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
      $("#sub_total_show_hold").html(Number(0).toFixed(ir_precision));
      $("#sub_total_hold").html(Number(0).toFixed(ir_precision));
      $("#total_item_discount_hold").html(Number(0).toFixed(ir_precision));
      $("#discounted_sub_total_amount_hold").html(
        Number(0).toFixed(ir_precision)
      );
      $("#sub_total_discount_hold").html("");
      $("#sub_total_discount_amount_hold").html(
        Number(0).toFixed(ir_precision)
      );
      $("#all_items_vat_hold").html(Number(0).toFixed(ir_precision));
      $("#all_items_discount_hold").html(Number(0).toFixed(ir_precision));
      $("#all_items_vat_hold").html(Number(0).toFixed(ir_precision));
      $("#delivery_charge_hold").html(Number(0).toFixed(ir_precision));
      $("#total_payable_hold").html(Number(0).toFixed(ir_precision));

      $(this)
        .parent()
        .parent()
        .parent()
        .parent()
        .parent()
        .parent()
        .parent()
        .parent()
        .removeClass("active")
        .addClass("inActive");
      setTimeout(function () {
        $(".modal").removeClass("inActive");
      }, 1000);
      $(".pos__modal__overlay").fadeOut(300);
    }
  );
  $(document).on("click", "#create_bill_and_close", function (e) {
    $("#print_type").val(2);
    if (
      $(".holder .order_details > .single_order[data-selected=selected]")
        .length > 0
    ) {
      let sale_id = $(
        ".holder .order_details .single_order[data-selected=selected]"
      )
        .attr("id")
        .substr(6);

      let print_type_bill = $(".print_type_bill").val();
      if (print_type_bill == "web_browser") {
        let newWindow = open(
          base_url + "Sale/print_bill/" + sale_id,
          "Print Invoice",
          "width=450,height=550"
        );
        newWindow.focus();
        newWindow.onload = function () {
          newWindow.document.body.insertAdjacentHTML("afterbegin");
        };
      } else {
        $("#finalize_order_modal").removeClass("active");
        $(".pos__modal__overlay").fadeOut(300);
        $.ajax({
          url: base_url + "Authentication/printSaleBillByAjax",
          method: "post",
          dataType: "json",
          data: {
            sale_id: sale_id,
          },
          success: function (data) {
            if (data.printer_server_url) {
              $.ajax({
                url:
                  data.printer_server_url +
                  "print_server/irestora_printer_server.php",
                method: "post",
                dataType: "json",
                data: {
                  content_data: JSON.stringify(data.content_data),
                },
                success: function (data) {},
                error: function () {},
              });
            }
          },
          error: function () {},
        });
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
  $(document).on(
    "click",
    "#create_invoice_and_close,#order_details_create_invoice_close_order_button",
    function (e) {
      $("#print_type").val(1);
      if (
        $(".holder .order_details > .single_order[data-selected=selected]")
          .length > 0
      ) {
        let sale_id = $(
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
            $("#selected_invoice_sale_customer").val(response.customer_id);
            $("#pay_amount_invoice_input").val(response.total_payable);
            $("#finalize_order_modal").addClass("active");
            $(".pos__modal__overlay").fadeIn(200);
            $("#open_invoice_date_hidden").val(response.sale_date);

            $(".datepicker_custom")
              .datepicker({
                autoclose: true,
                format: "yyyy-mm-dd",
                startDate: "0",
                todayHighlight: true,
              })
              .datepicker("update", response.sale_date);

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
    }
  );
  $(document).on("keyup", "#given_amount_input", function (e) {
    let this_value = $.trim($(this).val());
    if (isNaN(this_value)) {
      $(this).val("");
    }
    //get the value of the delivery charge amount
    let given_amount = $(this).val() != "" ? $(this).val() : 0;

    //check wether value is valid or not
    remove_last_two_digit_without_percentage(given_amount, $(this));

    given_amount = $(this).val() != "" ? $(this).val() : 0;
    let total_payable = $("#finalize_total_payable").html();
    let total_change = (
      parseFloat(given_amount) - parseFloat(total_payable)
    ).toFixed(ir_precision);
    $("#change_amount_input").val(total_change);
  });
  $(document).on("keyup", "#pay_amount_invoice_input", function (e) {
    let this_value = $.trim($(this).val());
    if (isNaN(this_value)) {
      $(this).val("");
    }
    let paid_amount = $(this).val() != "" ? $(this).val() : 0;

    remove_last_two_digit_without_percentage(paid_amount, $(this));
    calculate_create_invoice_modal();
  });
  $(document).on("click", "#finalize_order_cancel_button", function (e) {
    reset_finalize_modal();
    $(this)
      .parent()
      .parent()
      .parent()
      .parent()
      .removeClass("active")
      .addClass("inActive");
    setTimeout(function () {
      $(".modal").removeClass("inActive");
    }, 1000);
    $(".pos__modal__overlay").fadeOut(300);
  });
  $(document).on("click", "#previous_category", function (e) {
    let parent_width = Math.ceil($(".select_category_inside").width());
    let child_width = Math.ceil($(".select_category_inside_inside").width());
    let fixed_to_move = child_width - parent_width;
    let current_position = parseInt(
      $(".select_category_inside_inside").css("margin-left")
    );
    let updated_position = current_position + 50;
    let update_position_unsigned = Math.abs(updated_position);
    if (0 > updated_position) {
      $(".select_category_inside_inside").css(
        "margin-left",
        updated_position + "px"
      );
    } else if (0 < updated_position) {
      $(".select_category_inside_inside").css("margin-left", "0px");
    }
  });
  $(document).on("click", "#kitchen_status_button", function (e) {
    if (
      $(".holder .order_details > .single_order[data-selected=selected]")
        .length > 0
    ) {
      let sale_id = $(
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
          let order_object = JSON.parse(response);
          let order_type = order_object.order_type;
          let order_number = "";
          let order_type_name = "";
          $("#open_invoice_date_hidden").val(order_object.sale_date);

          $(".datepicker_custom")
            .datepicker({
              autoclose: true,
              format: "yyyy-mm-dd",
              startDate: "0",
              todayHighlight: true,
            })
            .datepicker("update", order_object.sale_date);

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
          let tables_booked = "";
          if (order_object.tables_booked.length > 0) {
            let w = 1;
            for (let k in order_object.tables_booked) {
              let single_table = order_object.tables_booked[k];
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
          let waiter_name =
            order_object.waiter_name == "" || order_object.waiter_name == null
              ? ""
              : order_object.waiter_name;
          let customer_name =
            order_object.customer_name == "" ||
            order_object.customer_name == null
              ? ""
              : order_object.customer_name;
          let table_name =
            order_object.table_name == "" || order_object.table_name == null
              ? ""
              : order_object.table_name;
          let order_time = new Date(Date.parse(order_object.date_time));
          let now = new Date();

          let days = parseInt((now - order_time) / (1000 * 60 * 60 * 24));
          let hours = parseInt(
            (Math.abs(now - order_time) / (1000 * 60 * 60)) % 24
          );
          let minute = parseInt(
            (Math.abs(now.getTime() - order_time.getTime()) / (1000 * 60)) % 60
          );
          let second = parseInt(
            (Math.abs(now.getTime() - order_time.getTime()) / 1000) % 60
          );
          minute = minute.toString();
          second = second.toString();
          minute = minute.length == 1 ? "0" + minute : minute;
          second = second.length == 1 ? "0" + second : second;

          let order_placed_at =
            "Order Placed at: " +
            order_time.getHours() +
            ":" +
            order_time.getMinutes() +
            "";
          let item_list_to_show = "";
          for (let key in order_object.items) {
            let single_item = order_object.items[key];
            if (single_item.item_type == "Kitchen Item") {
              let item_name = single_item.menu_name;
              let background_color = "";
              let current_condition = "In the queue";
              if (single_item.cooking_status == "Started Cooking") {
                let cooking_start_time = new Date(
                  Date.parse(single_item.cooking_start_time)
                );
                let now = new Date();

                let item_days = parseInt(
                  (now - cooking_start_time) / (1000 * 60 * 60 * 24)
                );
                let item_hours = parseInt(
                  (Math.abs(now - cooking_start_time) / (1000 * 60 * 60)) % 24
                );
                let item_minute = parseInt(
                  (Math.abs(now.getTime() - cooking_start_time.getTime()) /
                    (1000 * 60)) %
                    60
                );
                let item_second = parseInt(
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
                //this is dynamic style
                background_color = 'style="background-color:#ADD8E6;"';
                current_condition =
                  "Started Cooking " +
                  item_minute +
                  ":" +
                  item_second +
                  " Min Ago";
              } else if (single_item.cooking_status == "Done") {
                let cooking_start_time = new Date(
                  Date.parse(single_item.cooking_start_time)
                );
                let now = new Date();

                let item_days = parseInt(
                  (now - cooking_start_time) / (1000 * 60 * 60 * 24)
                );
                let item_hours = parseInt(
                  (Math.abs(now - cooking_start_time) / (1000 * 60 * 60)) % 24
                );
                let item_minute = parseInt(
                  (Math.abs(now.getTime() - cooking_start_time.getTime()) /
                    (1000 * 60)) %
                    60
                );
                let item_second = parseInt(
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
                //this is dynamic styles
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
            let s = $("#kitchen_status_ordered_second").text();
            let m = $("#kitchen_status_ordered_minute").text();
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
      $("#kitchen_status_modal").addClass("active");
      $(".pos__modal__overlay").fadeIn(200);
    } else {
      swal({
        title: warning + "!",
        text: please_select_order_to_proceed + "!",
        confirmButtonText: ok,
        confirmButtonColor: "#b6d6f6",
      });
    }
  });
  $(document).on("click", "#kitchen_status_close_button", function (e) {
    $(this)
      .parent()
      .parent()
      .parent()
      .removeClass("active")
      .addClass("inActive");
    setTimeout(function () {
      $(".modal").removeClass("inActive");
    }, 1000);
    $(".pos__modal__overlay").fadeOut(200);
  });
  $(document).on("click", "#help_button", function (e) {
    $("#help_modal").addClass("active");
    $(".pos__modal__overlay").fadeIn(200);
  });
  $(document).on(
    "click",
    "#help_close_button,#help_close_button_cross",
    function (e) {
      $(this).parent().parent().removeClass("active").addClass("inActive");
      setTimeout(function () {
        $(".modal").removeClass("inActive");
      }, 1000);
      $(".pos__modal__overlay").fadeOut(300);
    }
  );

  $(document).on(
    "click",
    "#kitchen_bar_waiter_modal_close_button_cross",
    function (e) {
      $("#kitchen_bar_waiter_panel_button_modal").slideUp(333);
    }
  );
  $(document).on("click", "#next_category", function (e) {
    let parent_width = Math.ceil($(".select_category_inside").width());
    let child_width = Math.ceil($(".select_category_inside_inside").width());
    let fixed_to_move = child_width - parent_width;
    let current_position = parseInt(
      $(".select_category_inside_inside").css("margin-left")
    );
    let updated_position = current_position - 50;
    let update_position_unsigned = Math.abs(updated_position);
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
  let height_should_be =
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
  $(document).on("click", ".category_button", function (e) {
    let str = $(this).attr("id");
    let res = str.substr(16);
    $("#searched_item_found").remove();
    $(".specific_category_items_holder").fadeOut(0);
    $("#category_" + res).css("display", "flex");
  });
  //get all images based on category when category button is clicked
  $(document).on("click", ".veg_bev_item", function (e) {
    let status = $(this).attr("data-status");
    $(".specific_category_items_holder").fadeOut(0);
    let foundItems = searchItemAndConstructGallery("");
    let searched_category_items_to_show =
      '<div id="searched_item_found" class="specific_category_items_holder txt_9 003">';
    for (let key in foundItems) {
      if (foundItems.hasOwnProperty(key)) {
        if (status == "veg" && foundItems[key].veg_item_status == "yes") {
          searched_category_items_to_show +=
            '<div class="single_item animate__animated animate__flipInX"  id="item_' +
            foundItems[key].item_id +
            '">';
          searched_category_items_to_show +=
            '<img src="' + foundItems[key].image + '" alt="" width="141">';
          searched_category_items_to_show +=
            '<p class="item_name" data-tippy-content="' +
            foundItems[key].item_name +
            '">' +
            foundItems[key].item_name +
            " (" +
            foundItems[key].item_code +
            ")</p>";
          searched_category_items_to_show +=
            '<p class="item_price">' +
            price_txt +
            ": " +
            foundItems[key].price +
            "</p>";

          searched_category_items_to_show += "</div>";
        } else if (
          status == "bev" &&
          foundItems[key].beverage_item_status == "yes"
        ) {
          searched_category_items_to_show +=
            '<div class="single_item animate__animated animate__flipInX"  id="item_' +
            foundItems[key].item_id +
            '">';
          searched_category_items_to_show +=
            '<img src="' + foundItems[key].image + '" alt="" width="141">';
          searched_category_items_to_show +=
            '<p class="item_name" data-tippy-content="' +
            foundItems[key].item_name +
            '">' +
            foundItems[key].item_name +
            " (" +
            foundItems[key].item_code +
            ")</p>";
          searched_category_items_to_show +=
            '<p class="item_price">' +
            price_txt +
            ": " +
            foundItems[key].price +
            "</p>";

          searched_category_items_to_show += "</div>";
        } else if (
          status == "bar" &&
          foundItems[key].bar_item_status == "yes"
        ) {
          searched_category_items_to_show +=
            '<div class="single_item animate__animated animate__flipInX"  id="item_' +
            foundItems[key].item_id +
            '">';
          searched_category_items_to_show +=
            '<img src="' + foundItems[key].image + '" alt="" width="141">';
          searched_category_items_to_show +=
            '<p class="item_name" data-tippy-content="' +
            foundItems[key].item_name +
            '">' +
            foundItems[key].item_name +
            " (" +
            foundItems[key].item_code +
            ")</p>";
          searched_category_items_to_show +=
            '<p class="item_price">' +
            price_txt +
            ": " +
            foundItems[key].price +
            "</p>";

          searched_category_items_to_show += "</div>";
        }
      }
    }
    searched_category_items_to_show += "<div>";
    $("#searched_item_found").remove();
    $(".specific_category_items_holder").fadeOut(0);
    $(".category_items").prepend(searched_category_items_to_show);

    tippy(".item_name", {
      placement: "bottom-start",
    });
  });
  //when anything is searched
  $(document).on("keyup", "#search", function (e) {
    // if (e.keyCode == 13) {
    let searched_string = $(this).val().trim();
    if(searched_string){
      let foundItems = searchItemAndConstructGallery(searched_string);
    let searched_category_items_to_show =
      '<div id="searched_item_found" class="specific_category_items_holder txt_9 002">';

    for (let key in foundItems) {
      if (foundItems.hasOwnProperty(key)) {
        searched_category_items_to_show +=
          '<div class="single_item animate__animated animate__flipInX"  id="item_' +
          foundItems[key].item_id +
          '">';
        searched_category_items_to_show +=
          '<img src="' + foundItems[key].image + '" alt="" width="141">';
        searched_category_items_to_show +=
          '<p class="item_name" data-tippy-content="' +
          foundItems[key].item_name +
          '">' +
          foundItems[key].item_name +
          " (" +
          foundItems[key].item_code +
          ")</p>";
        searched_category_items_to_show +=
          '<p class="item_price">' +
          price_txt +
          ": " +
          foundItems[key].price +
          "</p>";
        searched_category_items_to_show +=
          '<span class="item_vat_percentage ir_display_none">' +
          foundItems[key].vat_percentage +
          "</span>";
        searched_category_items_to_show += "</div>";
      }
    }
    searched_category_items_to_show += "<div>";
    $("#searched_item_found").remove();
    $(".specific_category_items_holder").fadeOut(0);
    $(".category_items").prepend(searched_category_items_to_show);
    // }
    tippy(".item_name", {
      placement: "bottom-start",
    });
    }else{
      $("#button_category_show_all1").click();
    }

  });
  $(document).on(
    "click",
    "#dine_in_button,#take_away_button,#delivery_button",
    function (e) {
      $("#dine_in_button").css("border", "unset");
      $("#take_away_button").css("border", "unset");
      $("#delivery_button").css("border", "unset");

      $(".main_top").find("button").attr("data-selected", "unselected");
      $(this).attr("data-selected", "selected").addClass("selected__btn");
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

      }
        do_addition_of_item_and_modifiers_price();
    }
  );
  //when single ite is clicked pop-up modal is appeared
  $(document).on("click", ".single_item", function () {
    let row_number = $("#modal_item_row").html();
    //get item/menu id from modal
    let item_id = $(this).attr("id").substr(5);
    let stock_not_available = $("#stock_not_available").val();
    let has_kitchen = $("#has_kitchen").val();
    let qty_current = 1;
      $(".single_order_column").each(function() {
        let qty_counter = Number($(this).find('#item_quantity_table_'+item_id).html());
        if(qty_counter && qty_counter!="NAN"){
            qty_current+=qty_counter;
        }
      });
     if(!check_current_qty(item_id,qty_current) && has_kitchen=="No"){
         swal({
             title: warning + "!",
             text: stock_not_available,
             confirmButtonText: ok,
             confirmButtonColor: "#b6d6f6",
         });
      }else{

         //get item/menu name from modal
         let item_name = getPlanText($(this).find(".item_name").html());

         //get item/menu vat percentage from modal
         let item_vat_percentage = $(this).find(".item_vat_percentage").html();
         item_vat_percentage =
             item_vat_percentage == "" ? "0.00" : item_vat_percentage;
         //discount amount from modal
         let item_discount_amount = parseFloat(0).toFixed(ir_precision);

         //discount input value of modal
         let discount_input_value = 0;

         //get item/menu price from modal
         let item_price = parseFloat($("#price_" + item_id).html()).toFixed(
             ir_precision
         );

         //get item/menu price from modal without discount
         let item_total_price_without_discount =
             parseFloat(item_price).toFixed(ir_precision);

         //get tax information
         let tax_information = "";
         // iterate over each item in the array
         for (let i = 0; i < window.items.length; i++) {
             // look for the entry with a matching `code` value
             if (items[i].item_id == item_id) {
                 tax_information = items[i].tax_information;
             }
         }
         tax_information = IsJsonString(tax_information)
             ? JSON.parse(tax_information)
             : "";
         if (tax_information.length > 0) {
             for (let k in tax_information) {
                 tax_information[k].item_vat_amount_for_unit_item = (
                     (parseFloat(item_price) *
                         parseFloat(tax_information[k].tax_field_percentage)) /
                     parseFloat(100)
                 ).toFixed(ir_precision);
                 tax_information[k].item_vat_amount_for_all_quantity = (
                     parseFloat(tax_information[k].item_vat_amount_for_unit_item) *
                     parseFloat(1)
                 ).toFixed(ir_precision);
             }
         }

         //get vat amount for specific item/menu
         let item_vat_amount_for_unit_item = (
             (parseFloat(item_price) * parseFloat(item_vat_percentage)) /
             parseFloat(100)
         ).toFixed(ir_precision);

         //get item/menu total price from modal
         let item_total_price = parseFloat(item_price).toFixed(ir_precision);

         //get item/menu quantity from modal
         let item_quantity = "1";

         //get vat amount for specific item/menu
         let item_vat_amount_for_all_quantity = (
             parseFloat(item_vat_amount_for_unit_item) * parseFloat(item_quantity)
         ).toFixed(ir_precision);

         //get selected modifiers
         let selected_modifiers = "";
         let selected_modifiers_id = "";
         let selected_modifiers_price = "";

         //get modifiers price
         let modifiers_price = parseFloat(0).toFixed(ir_precision);

         //get note
         let note = "";

         //construct div
         let draw_table_for_order = "";

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
             '<div class="single_order_column first_column fix"><i   class="fas fa-pencil-alt edit_item txt_5" id="edit_item_' +
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
             '<div class="single_order_column third_column fix"><i class="fal fa-minus decrease_item_table txt_5" id="decrease_item_table_' +
             item_id +
             '"></i> <span class="qty_item_custom" id="item_quantity_table_' +
             item_id +
             '">' +
             item_quantity +
             '</span> <i class="fal fa-plus increase_item_table txt_5" id="increase_item_table_' +
             item_id +
             '"></i></div>';
         draw_table_for_order +=
             '<div class="single_order_column forth_column fix"><input type="" name="" placeholder="Amt or %" class="discount_cart_input" id="percentage_table_' +
             item_id +
             '" value="' +
             discount_input_value +
             '" disabled></div>';
         draw_table_for_order +=
             '<div class="single_order_column fifth_column">' +
             currency +
             ' <span id="item_total_price_table_' +
             item_id +
             '">' +
             item_total_price +
             "</span> <i  data-id='" +
             item_id +
             "'  class='fal fa-times removeCartItem'></i></div>";
         draw_table_for_order += "</div>";
         if (selected_modifiers != "") {
             draw_table_for_order +=
                 '<div data-id="' + item_id + '" class="second_portion fix">';
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
                 '<div class="single_order_column first_column fix"><span class="modifier_txt_custom" id="item_modifiers_table_' +
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
                 '<div class="single_order_column first_column fix modifier_txt_custom">' +
                 note_txt +
                 ': <span id="item_note_table_' +
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
         if (waiter_app_status == "Yes") {
             $.notifyBar({ cssClass: "success", html: item_add_success });
         }

         reset_on_modal_close_or_add_to_cart();
         // return false;
         //do calculation on table
         do_addition_of_item_and_modifiers_price();
     }


  });
  $(document).on("click", "#cancel_button", function (e) {
    //get total items in cart
    let total_items_in_cart = $(".order_holder .single_order").length;
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
          // $(".main_top").find("button").css("background-color", "#109ec5");
          $(".main_top").find("button").attr("data-selected", "unselected");
          $("#table_button").attr("disabled", false);
          $(".single_table_div[data-table-checked=checked]").attr(
            "data-table-checked",
            "unchecked"
          );
          let cid = $("#default_customer_hidden").val();
          let wid = $("#default_waiter_hidden").val();
          $("#walk_in_customer").val(cid).trigger("change");
          if (wid) {
            if (waiter_app_status != "Yes") {
              $("#select_waiter").val(wid).trigger("change");
            }
          } else {
            if (waiter_app_status != "Yes") {
              $("#select_waiter").val("").trigger("change");
            }
          }

          $("#place_edit_order").html(place_order);
        }
      );
    }
  });
  $(document).on("click", ".edit_item", function () {
    let single_order_element_object = $(this).parent().parent().parent();
    let row_number = $(this)
      .parent()
      .parent()
      .parent()
      .attr("data-single-order-row-no");
    let menu_id = Number($(this).attr("id").substr(10));
    let item_name = single_order_element_object
      .find("#item_name_table_" + menu_id)
      .html();
    let item_price = single_order_element_object
      .find("#item_price_table_" + menu_id)
      .html();
    let item_vat_percentage = single_order_element_object
      .find("#item_vat_percentage_table" + menu_id)
      .html();
    let item_discount_input_value = single_order_element_object
      .find("#percentage_table_" + menu_id)
      .val();
    let item_discount_amount = single_order_element_object
      .find("#item_discount_table" + menu_id)
      .html();
    let item_price_without_discount = single_order_element_object
      .find("#item_price_without_discount_" + menu_id)
      .html();
    let item_quantity = single_order_element_object
      .find("#item_quantity_table_" + menu_id)
      .html();
    let item_price_with_discount = parseFloat(
      single_order_element_object
        .find("#item_total_price_table_" + menu_id)
        .html()
    ).toFixed(ir_precision);
    let modifiers_price = parseFloat(0).toFixed(ir_precision);
    let cooking_status = single_order_element_object
      .find("#item_cooking_status_table" + menu_id)
      .html();
    if (cooking_status != "" && cooking_status !== undefined) {
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
      let comma_separeted_modifiers_price = single_order_element_object
        .find("#item_modifiers_price_table_" + menu_id)
        .html();
      let modifiers_price_array =
        comma_separeted_modifiers_price != ""
          ? comma_separeted_modifiers_price.split(",")
          : "";
      modifiers_price_array.forEach(function (modifier_price) {
        modifiers_price = (
          parseFloat(modifiers_price) + parseFloat(modifier_price)
        ).toFixed(ir_precision);
      });
      parseFloat(
        single_order_element_object
          .find("#item_modifiers_price_table_" + menu_id)
          .html()
      ).toFixed(ir_precision);
    }

    let note = single_order_element_object
      .find("#item_note_table_" + menu_id)
      .html();
    let modifiers_id = "";
    if (
      single_order_element_object.find("#item_modifiers_id_table_" + menu_id)
        .length > 0
    ) {
      let comma_seperted_modifiers_id = single_order_element_object
        .find("#item_modifiers_id_table_" + menu_id)
        .html();
      modifiers_id =
        comma_seperted_modifiers_id != ""
          ? comma_seperted_modifiers_id.split(",")
          : "";
    }
    let modifiers_price_as_per_item_quantity = (
      parseFloat(modifiers_price) * parseFloat(item_quantity)
    ).toFixed(ir_precision);
    let total_price = (
      parseFloat(item_price_with_discount) +
      parseFloat(modifiers_price_as_per_item_quantity)
    ).toFixed(ir_precision);

    $("#modal_item_row").html(row_number);
    $("#modal_item_id").html(menu_id);
    $("#item_name_modal_custom").html(item_name);
    $("#modal_item_price").html(item_price);
    $("#modal_item_price_variable").html(item_price_with_discount);
    $("#modal_item_price_variable_without_discount").html(
      item_price_without_discount
    );

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
    let foundItems = search_by_menu_id(menu_id, window.items);
    let originalMenu = foundItems[0].modifiers;
    let modifiers = "";
    for (let key in originalMenu) {
      let selectedOrNot = "unselected";
      let backgroundColor = "";
      if (
        typeof modifiers_id !== "undefined" &&
        modifiers_id.includes(originalMenu[key].menu_modifier_id)
      ) {
        selectedOrNot = "selected";
        //this is dynamic style
        // backgroundColor = 'style="background-color:#B5D6F6;"';
      } else {
        selectedOrNot = "unselected";
        backgroundColor = "";
      }
      modifiers +=
        "<div " +
        backgroundColor +
        ' class="modal_modifiers"  data-menu_tax="' +
        originalMenu[key].tax_information +
        '"  data-price="' +
        originalMenu[key].menu_modifier_price +
        '" data-selected="' +
        selectedOrNot +
        '" id="modifier_' +
        originalMenu[key].menu_modifier_id +
        '">';
      modifiers += `<input type="checkbox" ${
        selectedOrNot === "selected" ? "checked" : "unchecked"
      }/>`;
      modifiers += "<p>" + originalMenu[key].menu_modifier_name + "</p>";
      modifiers +=
        '<span class="modifier_price"> <span>' +
        price_txt +
        ":</span> " +
        originalMenu[key].menu_modifier_price +
        "</span>";
      modifiers += "</div>";
    }
    $("#item_modal .section3").empty();
    $("#item_modal .section3").prepend(modifiers);
    $("#item_modal").addClass("active");
    $(".pos__modal__overlay").fadeIn(200);
  });
  $(document).on("click", "#close_item_modal", function (e) {
    reset_on_modal_close_or_add_to_cart();
    $(this)
      .parent()
      .parent()
      .parent()
      .parent()
      .removeClass("active")
      .addClass("inActive");
    setTimeout(function () {
      $(".modal").removeClass("inActive");
    }, 1000);
  });
  $(document).on("click", "#close_add_customer_modal", function (e) {
    $("#customer_name_modal").css("border", "1px solid #B5D6F6");
    $("#customer_phone_modal").css("border", "1px solid #B5D6F6");
    $(this)
      .parent()
      .parent()
      .parent()
      .parent()
      .removeClass("active")
      .addClass("inActive");
    setTimeout(function () {
      $(".modal").removeClass("inActive");
    }, 1000);
    $(".pos__modal__overlay").fadeOut(300);
    reset_on_modal_close_or_add_customer();
  });
  $(document).on("click", ".modal_modifiers:visible", function (e) {
    //get modifier price when it's selected
    let modifier_price = parseFloat($(this).attr("data-price")).toFixed(
      ir_precision
    );
    //get total modifier price
    // let total_modifier_price = parseFloat($('#modal_modifier_price_variable').html()).toFixed(ir_precision);
    let total_modifier_price = parseFloat(
      $("#modal_modifiers_unit_price_variable").html()
    ).toFixed(ir_precision);

    let update_modifier_price = 0;
    //add current modifier price to total modifier price
    console.log(total_modifier_price);
    if ($(this).attr("data-selected") == "unselected") {
      // $(this).css("background-color", "#B5D6F6");
      $(this).attr("data-selected", "selected");
      $(this).children("input").prop("checked", true);

      update_modifier_price = (
        parseFloat(total_modifier_price) + parseFloat(modifier_price)
      ).toFixed(ir_precision);
    } else if ($(this).attr("data-selected") == "selected") {
      $(this).attr("data-selected", "unselected");
      $(this).children("input").prop("checked", false);

      update_modifier_price = (
        parseFloat(total_modifier_price) - parseFloat(modifier_price)
      ).toFixed(ir_precision);
    }

    let checkbox = $(this).children('input[type="checkbox"]');
    /*checkbox.prop('checked', checkbox.prop('checked'));*/

    //update total modifier price html element
    // $('#modal_modifier_price_variable').html(update_modifier_price);
    $("#modal_modifiers_unit_price_variable").html(update_modifier_price);

    //update all total with item price
    update_all_total_price();
  });
  //initialize item list
  show_all_items();
  //show all when all button is clicked
  $(document).on(
    "click",
    "#button_category_show_all,#button_category_show_all1",
    function () {
      $(".specific_category_items_holder").fadeOut(0);
      let foundItems = searchItemAndConstructGallery("");
      let searched_category_items_to_show =
        '<div id="searched_item_found" class="specific_category_items_holder txt_9 003">';

      for (let key in foundItems) {
        if (foundItems.hasOwnProperty(key)) {
          searched_category_items_to_show +=
            '<div class="single_item animate__animated animate__flipInX"  id="item_' +
            foundItems[key].item_id +
            '">';
          searched_category_items_to_show +=
            '<img src="' + foundItems[key].image + '" alt="" width="141">';
          searched_category_items_to_show +=
            '<p class="item_name" data-tippy-content="' +
            foundItems[key].item_name +
            '">' +
            foundItems[key].item_name +
            " (" +
            foundItems[key].item_code +
            ")</p>";
          searched_category_items_to_show +=
            '<p class="item_price">' +
            price_txt +
            ": " +
            foundItems[key].price +
            "</p>";
          searched_category_items_to_show += "</div>";
        }
      }
      searched_category_items_to_show += "<div>";
      $("#searched_item_found").remove();
      $(".specific_category_items_holder").fadeOut(0);
      $(".category_items").prepend(searched_category_items_to_show);
      tippy(".item_name", {
        placement: "bottom-start",
      });
    }
  );
  $(document).on("click", "#increase_item_modal", function (e) {
    //get recent item price
    let current_item_price_modal = parseFloat(
      $("#modal_item_price").html()
    ).toFixed(ir_precision);
    //get current item quantity
    let current_item_quantity = parseInt($("#item_quantity_modal").html());
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
      let sale_id = $(this).attr("id").substr(6);
      get_details_of_a_particular_order_for_modal(sale_id);
    }
  );
  $(document).on("click", "#cancel_order_button", function (e) {
    if (
      $(".holder .order_details > .single_order[data-selected=selected]")
        .length > 0
    ) {
      let selected_order = $(
        ".holder .order_details > .single_order[data-selected=selected]"
      );
      let selected_order_started_cooking_items = selected_order.attr(
        "data-started-cooking"
      );
      let selected_order_done_cooking_items =
        selected_order.attr("data-done-cooking");
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
      swal(
        {
          title: warning + "!",
          text: sure_cancel_this_order,
          confirmButtonColor: "#3c8dbc",
          confirmButtonText: ok,
          showCancelButton: true,
        },
        function () {
          let sale_id = $(
            ".holder .order_details .single_order[data-selected=selected]"
          )
            .attr("id")
            .substr(6);
          cancel_order(sale_id);
        }
      );
    } else {
      swal({
        title: warning + "!",
        text: please_select_order_to_proceed + "!",
        confirmButtonText: ok,
        confirmButtonColor: "#b6d6f6",
      });
    }
  });
  $(document).on("click", "#plus_button", function (e) {
    $("#add_customer_modal").addClass("active");
    $(".pos__modal__overlay").fadeIn(200);
  });
  $(document).on("click", ".modify_order_table_modal", function (e) {
    let table_id = $(this).attr("id").substr(19);
    //get total items in cart
    let total_items_in_cart = $(".order_holder .single_order").length;

    if (total_items_in_cart > 0) {
      swal(
        {
          title: warning + "!",
          text: cart_not_empty,
          confirmButtonColor: "#3c8dbc",
          confirmButtonText: ok,
          showCancelButton: true,
        },
        function () {
          $(".order_holder").empty();
          $.ajax({
            url:
              base_url + "Sale/get_all_information_of_a_sale_by_table_id_ajax",
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
      );
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
  $(document).on("click", "#table_button,#dine_in_button", function (e) {
    if ($(".order_table_holder .order_holder > .modification").length > 0) {
      let sale_id = $(
        ".order_table_holder .order_holder > .modification"
      ).html();
      let sale_no = $(".order_table_holder .order_holder > .modification").attr(
        "data-sale-no"
      );
    }
    if (typeof sale_no !== "undefined") {
      $("#order_number_or_new_text").html(sale_no);
      $(".bottom_order").val(sale_no);
    } else {
      $("#order_number_or_new_text").html("New");
    }
    $("#show_tables_modal2").addClass("active");
    $(".pos__modal__overlay").fadeIn(200);

    $(".bottom_person").val("1");
    $.ajax({
      url: base_url + "Sale/get_all_tables_with_new_status_ajax",
      method: "GET",
      success: function (response) {
        response = JSON.parse(response);
        let table_details = response.table_details;
        let table_availability = response.table_availability;

        for (let key in table_details) {
          let table_id = table_details[key].id;
          let orders_table = table_details[key].orders_table;
          let orders_table_number = orders_table.length;
          let tables_modal = "";
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
            for (let key2 in orders_table) {
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
            let new_row = $(
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
        $(".table_image").attr("src", base_url + "images/table_icon2.png");
        for (let key in table_availability) {
          let table_id = table_availability[key].table_id;
          let sit_capacity = $("#sit_capacity_number_" + table_id).html();
          let booked_sit = table_availability[key].persons_number;
          let available_sit = parseInt(sit_capacity) - parseInt(booked_sit);
          $("#sit_available_number_" + table_id).html(available_sit);
          if (booked_sit != 0) {
            $("#single_table_info_holder_" + table_id)
              .find(".table_image")
              .attr("src", base_url + "images/table_icon2_blue.png");
          }
        }
      },
      error: function () {
        alert(a_error);
      },
    });
  });
  $(document).on("click", "#order_details_close_button", function (e) {
    $(this)
      .parent()
      .parent()
      .parent()
      .removeClass("active")
      .addClass("inActive");
    setTimeout(function () {
      $(".modal").removeClass("inActive");
    }, 1000);
    $(".pos__modal__overlay").fadeOut(300);
  });
  $(document).on("click", "#order_details_close_button2", function (e) {
    $("#order_detail_modal").removeClass("active");
    $(".pos__modal__overlay").fadeOut(300);
  });
  $("#close_order_button,#order_details_close_order_button").on(
    "click",
    function () {
      if (
        $(".holder .order_details > .single_order[data-selected=selected]")
          .length > 0
      ) {
        let sale_id = $(
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
        swal(
          {
            title: warning + "!",
            text: sure_delete_this_order,
            confirmButtonColor: "#3c8dbc",
            confirmButtonText: ok,
            showCancelButton: true,
          },
          function () {
            close_order(sale_id, 0);
          }
        );
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
  $(document).on("click", "#decrease_item_modal", function (e) {
    //get recent item price
    let current_item_price_modal = parseFloat(
      $("#modal_item_price").html()
    ).toFixed(ir_precision);
    //get current item quantity
    let current_item_quantity = parseInt($("#item_quantity_modal").html());

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
      $(this)
        .parent()
        .parent()
        .parent()
        .parent()
        .parent()
        .parent()
        .parent()
        .parent()
        .removeClass("active")
        .addClass("inActive");
      setTimeout(function () {
        $(".modal").removeClass("inActive");
      }, 1000);
      $(".pos__modal__overlay").fadeOut(300);
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
  $(document).on("click", "#last_ten_sales_button", function (e) {
    $("#show_last_ten_sales_modal").addClass("active");
    $(".pos__modal__overlay").fadeIn(200);
    $.ajax({
      url: base_url + "Sale/get_last_10_sales_ajax",
      method: "GET",
      success: function (response) {
        let orders = JSON.parse(response);
        let last_10_orders = "";
        for (let key in orders) {
          let order_name = "";
          if (orders[key].order_type == "1") {
            order_name = "A " + orders[key].sale_no;
          } else if (orders[key].order_type == "2") {
            order_name = "B " + orders[key].sale_no;
          } else if (orders[key].order_type == "3") {
            order_name = "C " + orders[key].sale_no;
          }
          let tables_booked = "";
          if (orders[key].tables_booked.length > 0) {
            let w = 1;
            for (let k in orders[key].tables_booked) {
              let single_table = orders[key].tables_booked[k];
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
          let phone_text_ = "";
          if (orders[key].phone) {
            phone_text_ = " (" + orders[key].phone + ")";
          }

          let table_name =
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
            phone_text_ +
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
  $(document).on("click", "#add_to_cart", function (e) {
    let row_number = $("#modal_item_row").html();
    //get item/menu id from modal
    let item_id = $("#modal_item_id").html();
    //remove if it is edited update of previous added item based on id
    // if($('#order_for_item_'+item_id).length>0){
    // 	$('#order_for_item_'+item_id).remove();
    // }

    //get item/menu name from modal
    let item_name = $("#item_name_modal_custom").html();
    //get item/menu vat percentage from modal
    let item_vat_percentage = $("#modal_item_vat_percentage").html();
    item_vat_percentage =
      item_vat_percentage == "" ? "0.00" : item_vat_percentage;
    //discount amount from modal
    let item_discount_amount = parseFloat(
      $("#modal_discount_amount").html()
    ).toFixed(ir_precision);

    //discount input value of modal
    let discount_input_value = $("#modal_discount").val();

    //get item/menu price from modal
    let item_price = parseFloat($("#modal_item_price").html()).toFixed(
      ir_precision
    );

    //get item/menu price from modal without discount
    let item_total_price_without_discount = parseFloat(
      $("#modal_item_price_variable_without_discount").html()
    ).toFixed(ir_precision);

    //get vat amount for specific item/menu
    let item_vat_amount_for_unit_item = (
      (parseFloat(item_price) * parseFloat(item_vat_percentage)) /
      parseFloat(100)
    ).toFixed(ir_precision);

    //get item/menu total price from modal
    let item_total_price = parseFloat(
      $("#modal_item_price_variable").html()
    ).toFixed(ir_precision);

    //get item/menu quantity from modal
    let item_quantity = $("#item_quantity_modal").html();

    //get vat amount for specific item/menu
    let item_vat_amount_for_all_quantity = (
      parseFloat(item_vat_amount_for_unit_item) * parseFloat(item_quantity)
    ).toFixed(ir_precision);

    //get selected modifiers
    let selected_modifiers = "";
    let selected_modifiers_id = "";
    let selected_modifiers_price = "";
    //get tax information

    let j = 1;
    let draw_table_for_order_tmp_modifier_tax = "";
    $(".modal_modifiers[data-selected=selected]").each(function (i, obj) {
      let modifier_id_custom = $(this).attr("id").substr(9);
      if (j == $(".modal_modifiers[data-selected=selected]").length) {
        selected_modifiers += $(this).find("p").html();
        selected_modifiers_id += $(this).attr("id").substr(9);
        selected_modifiers_price += $(this).attr("data-price");
      } else {
        selected_modifiers += $(this).find("p").html() + ", ";
        selected_modifiers_id += $(this).attr("id").substr(9) + ",";
        selected_modifiers_price += $(this).attr("data-price") + ",";
      }
      let tax_information = "";
      // iterate over each item in the array
      tax_information = $(this).attr("data-menu_tax");
      // iterate over each item in the array
      for (let i = 0; i < window.item_modifiers.length; i++) {
        // look for the entry with a matching `code` value
        if (
          item_modifiers[i].menu_modifier_id ==
          Number($(this).attr("id").substr(9))
        ) {
          tax_information = item_modifiers[i].tax_information;
        }
      }
      tax_information = IsJsonString(tax_information)
        ? JSON.parse(tax_information)
        : "";
      if (tax_information.length > 0) {
        for (let k in tax_information) {
          tax_information[k].item_vat_amount_for_unit_item = (
            (parseFloat($(this).attr("data-price")) *
              parseFloat(tax_information[k].tax_field_percentage)) /
            parseFloat(100)
          ).toFixed(ir_precision);
          tax_information[k].item_vat_amount_for_all_quantity = (
            parseFloat(tax_information[k].item_vat_amount_for_unit_item) *
            parseFloat(1)
          ).toFixed(ir_precision);
        }
      }
      draw_table_for_order_tmp_modifier_tax +=
        '<span class="item_vat_modifier ir_display_none item_vat_modifier_' +
        item_id +
        '" data-item_id="' +
        item_id +
        '"  data-modifier_price="' +
        $(this).attr("data-price") +
        '" data-modifier_id="' +
        modifier_id_custom +
        '" id="item_vat_percentage_table' +
        item_id +
        "" +
        modifier_id_custom +
        '">' +
        JSON.stringify(tax_information) +
        "</span>";

      j++;
    });

    //get modifiers price
    let modifiers_price = parseFloat(
      $("#modal_modifier_price_variable").html()
    ).toFixed(ir_precision);

    //get note
    let note = escape_output($("#modal_item_note").val());

    //construct div
    let draw_table_for_order = "";

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
      '<div class="single_order_column first_column fix"><i   class="fas fa-pencil-alt edit_item txt_5" id="edit_item_' +
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
      '<div class="single_order_column third_column fix"><i class="fal fa-minus decrease_item_table txt_5" id="decrease_item_table_' +
      item_id +
      '"></i> <span class="qty_item_custom" id="item_quantity_table_' +
      item_id +
      '">' +
      item_quantity +
      '</span> <i class="fal fa-plus increase_item_table txt_5" id="increase_item_table_' +
      item_id +
      '"></i></div>';
    draw_table_for_order +=
      '<div class="single_order_column forth_column fix"><input type="" name="" placeholder="Amt or %" class="discount_cart_input" id="percentage_table_' +
      item_id +
      '" value="' +
      discount_input_value +
      '" disabled></div>';
    draw_table_for_order +=
      '<div class="single_order_column fifth_column">' +
      currency +
      ' <span id="item_total_price_table_' +
      item_id +
      '">' +
      item_total_price +
      '</span><i data-id="' +
      item_id +
      '" class="fal fa-times removeCartItem"></i></div>';
    draw_table_for_order += "</div>";
    if (selected_modifiers != "") {
      draw_table_for_order += '<div class="second_portion fix">';
      draw_table_for_order += draw_table_for_order_tmp_modifier_tax;
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
        '<div class="single_order_column first_column fix"><span class="modifier_txt_custom" id="item_modifiers_table_' +
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
        '<div class="single_order_column first_column fix modifier_txt_custom">' +
        note_txt +
        ': <span id="item_note_table_' +
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
        let item_id = $(this).attr("id").substr(20);
        let stock_not_available = $("#stock_not_available").val();
        let has_kitchen = $("#has_kitchen").val();
        let qty_current = 1;
        $(".single_order_column").each(function() {
            let qty_counter = Number($(this).find('#item_quantity_table_'+item_id).html());
            if(qty_counter && qty_counter!="NAN"){
                qty_current+=qty_counter;
            }
        });
        if(!check_current_qty(item_id,qty_current) && has_kitchen=="No"){
            swal({
                title: warning + "!",
                text: stock_not_available,
                confirmButtonText: ok,
                confirmButtonColor: "#b6d6f6",
            });
        }else{

            let single_order_element_object = $(this).parent().parent().parent();
            //get item id


            //get this item quantity
            let item_quantity = $(this).parent().find("span").html();

            //get this item's unit price
            let unit_price = $(this)
                .parent()
                .parent()
                .find(".second_column span")
                .html();
            let cooking_status = single_order_element_object
                .find("#item_cooking_status_table" + item_id)
                .html();
            if (cooking_status != "" && cooking_status !== undefined) {
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
            let updated_total_price = (
                parseFloat(item_quantity) * parseFloat(unit_price)
            ).toFixed(ir_precision);

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
        }
  );
  //when - sign is clicked in the table
  $(document).on(
    "click",
    ".single_order .first_portion .third_column .decrease_item_table",
    function () {
      let single_order_element_object = $(this).parent().parent().parent();
      //get item id
      let item_id = $(this).attr("id").substr(20);
      //get this item quantity
      let item_quantity = $(this).parent().find("span").html();
      //get this item's unit price
      let unit_price = $(this)
        .parent()
        .parent()
        .find(".second_column span")
        .html();
      let cooking_status = single_order_element_object
        .find("#item_cooking_status_table" + item_id)
        .html();

      if (cooking_status != "" && cooking_status !== undefined) {
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
        let updated_total_price = (
          parseFloat(item_quantity) * parseFloat(unit_price)
        ).toFixed(ir_precision);

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
      let this_value = $.trim($(this).val());
      if (isNaN(this_value)) {
        $(this).val("");
      }
      do_addition_of_item_and_modifiers_price();
    }
  );
  //add discount for specific item in modal
  $(document).on("keyup", "#modal_discount", function (e) {
    let this_value = $(this).val();
    if (
      $.trim(this_value) == "" ||
      $.trim(this_value) == "%" ||
      $.trim(this_value) == "%%" ||
      $.trim(this_value) == "%%%" ||
      $.trim(this_value) == "%%%%"
    ) {
    } else {
      let Disc_fields = this_value.split("%");
      let discP = Disc_fields[1];
      if (discP == "") {
      } else {
        if (isNaN(this_value)) {
          $(this).val("");
        }
      }
    }

    update_all_total_price();
  });
  $(document).on("click", "#submit_discount_custom", function (e) {
    checkDiscountType();
  });
  $(document).on("click", "#cancel_discount_modal", function (e) {
    $("#sub_total_discount").val("");
    $("#sub_total_discount1").val("");
    checkDiscountType();
    do_addition_of_item_and_modifiers_price();
  });
  $(document).on("click", "#cancel_charge_value", function (e) {
    do_addition_of_item_and_modifiers_price();
  });

  $(document).on("keyup", "#sub_total_discount1", function (e) {
    //if the letter is not digit then display error and don't type anything
    let this_value = $.trim($(this).val());
    if (isNaN(this_value)) {
      $(this).val("");
      this_value = "";
    }

    $("#sub_total_discount").val(this_value);
    checkDiscountType();
    do_addition_of_item_and_modifiers_price();
  });
  function checkDiscountType() {
    let this_value = $("#discount_type").val();
    let sub_total_discount_value = $("#sub_total_discount1").val();

    if (this_value == "percentage") {
      if (parseFloat(sub_total_discount_value)) {
        $("#sub_total_discount").val(
          parseFloat(sub_total_discount_value) + "%"
        );
        console.log("Monir1");
        do_addition_of_item_and_modifiers_price();
      }
    } else {
      if (parseFloat(sub_total_discount_value)) {
        $("#sub_total_discount").val(parseFloat(sub_total_discount_value));
        console.log("Monir2");
        do_addition_of_item_and_modifiers_price();
      }
    }
  }
  $(document).on("change", "#discount_type", function () {
    checkDiscountType();
  });
  $(document).on("keyup", "#delivery_charge", function (e) {
    //if the letter is not digit then display error and don't type anything
    let this_value = $.trim($(this).val());
    if (isNaN(this_value)) {
      /* $(this).val("");*/
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
  $(document).on("click", "#place_order_operation", function (e) {
    let sale_id = 0;
    if ($(".order_table_holder .order_holder > .modification").length > 0) {
      sale_id = $(".order_table_holder .order_holder > .modification").html();
    }
    let selected_order_type_object = $(".main_top").find(
      "button[data-selected=selected]"
    );
    let total_items_in_cart = $(".order_holder .single_order").length;
    let sub_total = parseFloat($("#sub_total_show").html()).toFixed(
      ir_precision
    );
    let charge_type = $("#charge_type").val();
    let total_vat = parseFloat($("#all_items_vat").html()).toFixed(
      ir_precision
    );
    let total_payable = parseFloat($("#total_payable").html()).toFixed(
      ir_precision
    );
    let total_item_discount_amount = parseFloat(
      $("#total_item_discount").html()
    ).toFixed(ir_precision);
    let sub_total_with_discount = parseFloat(
      $("#discounted_sub_total_amount").html()
    ).toFixed(ir_precision);
    let sub_total_discount_amount = parseFloat(
      $("#sub_total_discount_amount").html()
    ).toFixed(ir_precision);
    let total_discount_amount = parseFloat(
      $("#all_items_discount").html()
    ).toFixed(ir_precision);
    let delivery_charge = $("#delivery_charge").val();
    let delivery_charge_actual_charge = $("#show_charge_amount").html();

    let sub_total_discount_value = $("#sub_total_discount").val();
    let sub_total_discount_type = "";
    let customer_id = $("#walk_in_customer").val();
    let waiter_id = $("#select_waiter").val();
    let sale_vat_objects = [];
    $("#tax_row_show .tax_field").each(function (i, obj) {
      let tax_field_id = $(this).attr("data-tax_field_id");
      let tax_field_type = $(this).attr("data-tax_field_type");
      let tax_field_amount = $(this).attr("data-tax_field_amount");
      sale_vat_objects.push({
        tax_field_id: tax_field_id,
        tax_field_type: tax_field_type,
        tax_field_amount: Number(tax_field_amount).toFixed(ir_precision),
      });
    });
    if (total_items_in_cart == 0) {
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
      sub_total_discount_type = "fixed";
    }
    if (selected_order_type_object.length > 0) {
      let order_type = 1;
      if (selected_order_type_object.attr("id") == "delivery_button") {
        order_type = 3;
        if (customer_id == "") {
          $("#select2-walk_in_customer-container").css(
            "border",
            "1px solid red"
          );
          let op1 = $("#walk_in_customer").data("select2");
          let op2 = $("#select_waiter").data("select2");
          op1.open();
          op2.close();
          return false;
        }
        if (customer_id == "1") {
          $("#select2-walk_in_customer-container").css(
            "border",
            "1px solid red"
          );
          let op1 = $("#walk_in_customer").data("select2");
          let op2 = $("#select_waiter").data("select2");
          op1.open();
          op2.close();
          return false;
        }

        let address_available = searchCustomerAddress(customer_id);
        if (address_available[0].customer_address == "") {
          $("#select2-walk_in_customer-container").css(
            "border",
            "1px solid red"
          );
          let op1 = $("#walk_in_customer").data("select2");
          let op2 = $("#select_waiter").data("select2");
          op1.open();
          op2.close();
          return false;
        }
      } else if (selected_order_type_object.attr("id") == "dine_in_button") {
        order_type = 1;
        if (waiter_id == "") {
          $("#select2-select_waiter-container").css("border", "1px solid red");
          $("#select2-select_waiter-container").css("border", "1px solid red");
          let op1 = $("#walk_in_customer").data("select2");
          let op2 = $("#select_waiter").data("select2");
          op1.close();
          op2.open();
          return false;
        }
        if (customer_id == "") {
          $("#select2-walk_in_customer-container").css(
            "border",
            "1px solid red"
          );
          let op1 = $("#walk_in_customer").data("select2");
          let op2 = $("#select_waiter").data("select2");
          op1.open();
          op2.close();
          return false;
        }
      } else if (selected_order_type_object.attr("id") == "take_away_button") {
        order_type = 2;

        if (waiter_id == "") {
          $("#select2-select_waiter-container").css("border", "1px solid red");
          let op1 = $("#walk_in_customer").data("select2");
          let op2 = $("#select_waiter").data("select2");
          op1.close();
          op2.open();
          return false;
        }
        if (customer_id == "") {
          $("#select2-walk_in_customer-container").css(
            "border",
            "1px solid red"
          );
          let op1 = $("#walk_in_customer").data("select2");
          let op2 = $("#select_waiter").data("select2");
          op1.open();
          op2.close();
          return false;
        }
      }

      let order_status = 1;
      let open_invoice_date_hidden = $("#open_invoice_date_hidden").val();
      let order_info = "{";
      order_info += '"customer_id":"' + customer_id + '",';
      order_info += '"waiter_id":"' + waiter_id + '",';
      order_info +=
        '"open_invoice_date_hidden":"' + open_invoice_date_hidden + '",';
      order_info += '"total_items_in_cart":"' + total_items_in_cart + '",';
      order_info += '"sub_total":"' + sub_total + '",';
      order_info += '"charge_type":"' + charge_type + '",';
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
      order_info += '"delivery_charge_actual_charge":"' + delivery_charge_actual_charge + '",';
      order_info +=
        '"sub_total_discount_value":"' + sub_total_discount_value + '",';
      order_info +=
        '"sub_total_discount_type":"' + sub_total_discount_type + '",';
      // order_info += '"selected_table":"'+selected_table+'",';
      order_info += '"order_type":"' + order_type + '",';
      order_info += '"order_status":"' + order_status + '",';
      order_info +=
        '"sale_vat_objects":' + JSON.stringify(sale_vat_objects) + ",";

      let orders_table = "";
      orders_table += '"orders_table":';
      orders_table += "[";
      let x = 1;
      let total_orders_table = $(".new_book_to_table").length;
      $(".new_book_to_table").each(function (i, obj) {
        let table_id = $(this).attr("id").substr(16);
        let person = $(this).find(".third_column").html();
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
      let items_info = "";
      items_info += '"items":';
      items_info += "[";

      if ($(".order_holder .single_order").length > 0) {
        let k = 1;
        $(".order_holder .single_order").each(function (i, obj) {
          let item_id = $(this).attr("id").substr(15);
          let item_name = $(this)
            .find("#item_name_table_" + item_id)
            .html();
          let item_vat = $(this).find(".item_vat").html();
          let item_discount = $(this)
            .find("#percentage_table_" + item_id)
            .val();
          let discount_type = "";
          if (
            item_discount.length > 0 &&
            item_discount.substr(item_discount.length - 1) == "%"
          ) {
            discount_type = "percentage";
          } else {
            discount_type = "fixed";
          }
          let item_previous_id = $(this)
            .find("#item_previous_id_table" + item_id)
            .html();
          let item_cooking_done_time = $(this)
            .find("#item_cooking_done_time_table" + item_id)
            .html();
          let item_cooking_start_time = $(this)
            .find("#item_cooking_start_time_table" + item_id)
            .html();
          let item_cooking_status = $(this)
            .find("#item_cooking_status_table" + item_id)
            .html();
          let item_type = $(this)
            .find("#item_type_table" + item_id)
            .html();
          let item_price_without_discount = $(this)
            .find(".item_price_without_discount")
            .html();
          let item_unit_price = $(this)
            .find("#item_price_table_" + item_id)
            .html();
          let item_quantity = $(this)
            .find("#item_quantity_table_" + item_id)
            .html();
          let tmp_qty = $(this).find('.tmp_qty').val();
          let p_qty = $(this).find('.p_qty').val();
          let item_price_with_discount = $(this)
            .find("#item_total_price_table_" + item_id)
            .html();
          let item_discount_amount = (
            parseFloat(item_price_without_discount) -
            parseFloat(item_price_with_discount)
          ).toFixed(ir_precision);

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
          let modifiers_tax_custom = "";
          let ji = 1;
          let modifier_vat = "";
          $(".item_vat_modifier_" + item_id).each(function (i, obj) {
            if (ji == $(".item_vat_modifier_" + item_id).length) {
              modifier_vat += $(this).html();
            } else {
              modifier_vat += $(this).html() + "|||";
            }
            ji++;
          });
          if ($(this).find(".second_portion").length > 0) {
            let modifiers_id = $(this)
              .find("#item_modifiers_id_table_" + item_id)
              .html();
            let modifiers_price = $(this)
              .find("#item_modifiers_price_table_" + item_id)
              .html();
            items_info +=
              ',"modifiers_id":"' +
              modifiers_id +
              '", "modifiers_price":"' +
              modifiers_price +
              '", "modifier_vat":' +
              JSON.stringify(modifier_vat);
          } else {
            items_info +=
              ',"modifiers_id":"", "modifiers_price":"", "modifier_vat":""';
          }
          if ($(this).find(".third_portion").length > 0) {
            let item_note = $(this)
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

      let order_object = JSON.stringify(order_info);
      add_sale_by_ajax(order_object, sale_id);

      $("#dine_in_button").css("border", "unset");
      $("#take_away_button").css("border", "unset");
      $("#delivery_button").css("border", "unset");
    } else {
      $("#dine_in_button").css("border", "1px solid red");
      $("#take_away_button").css("border", "1px solid red");
      $("#delivery_button").css("border", "1px solid red");
    }
  });
  $(document).on("click", "#direct_invoice", function (e) {
    let sale_id = 0;
    if ($(".order_table_holder .order_holder > .modification").length > 0) {
      sale_id = $(".order_table_holder .order_holder > .modification").html();
    }
    let selected_order_type_object = $(".main_top").find(
      "button[data-selected=selected]"
    );
    let total_items_in_cart = $(".order_holder .single_order").length;
    let sub_total = parseFloat($("#sub_total_show").html()).toFixed(
      ir_precision
    );
    let total_vat = parseFloat($("#all_items_vat").html()).toFixed(
      ir_precision
    );
    let total_payable = parseFloat($("#total_payable").html()).toFixed(
      ir_precision
    );
    let charge_type = $("#charge_type").val();
    let total_item_discount_amount = parseFloat(
      $("#total_item_discount").html()
    ).toFixed(ir_precision);
    let sub_total_with_discount = parseFloat(
      $("#discounted_sub_total_amount").html()
    ).toFixed(ir_precision);
    let sub_total_discount_amount = parseFloat(
      $("#sub_total_discount_amount").html()
    ).toFixed(ir_precision);
    let total_discount_amount = parseFloat(
      $("#all_items_discount").html()
    ).toFixed(ir_precision);
    let delivery_charge = $("#delivery_charge").val();
    let sub_total_discount_value = $("#sub_total_discount").val();
    let sub_total_discount_type = "";
    let customer_id = $("#walk_in_customer").val();
    let waiter_id = $("#select_waiter").val();
    let sale_vat_objects = [];
    $("#tax_row_show .tax_field").each(function (i, obj) {
      let tax_field_id = $(this).attr("data-tax_field_id");
      let tax_field_type = $(this).attr("data-tax_field_type");
      let tax_field_amount = $(this).attr("data-tax_field_amount");
      sale_vat_objects.push({
        tax_field_id: tax_field_id,
        tax_field_type: tax_field_type,
        tax_field_amount: Number(tax_field_amount).toFixed(ir_precision),
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
      sub_total_discount_type = "fixed";
    }
    if (selected_order_type_object.length > 0) {
      let order_type = 1;
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

        let address_available = searchCustomerAddress(customer_id);
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

      let order_status = 1;
      let order_info = "{";
      order_info += '"customer_id":"' + customer_id + '",';
      order_info += '"waiter_id":"' + waiter_id + '",';
      order_info += '"total_items_in_cart":"' + total_items_in_cart + '",';
      order_info += '"sub_total":"' + sub_total + '",';
      order_info += '"charge_type":"' + charge_type + '",';
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
      let orders_table = "";
      orders_table += '"orders_table":';
      orders_table += "[";
      let x = 1;
      let total_orders_table = $(".new_book_to_table").length;
      $(".new_book_to_table").each(function (i, obj) {
        let table_id = $(this).attr("id").substr(16);
        let person = $(this).find(".third_column").html();
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
      let items_info = "";
      items_info += '"items":';
      items_info += "[";

      if ($(".order_holder .single_order").length > 0) {
        let k = 1;
        $(".order_holder .single_order").each(function (i, obj) {
          let item_id = $(this).attr("id").substr(15);
          let item_name = $(this)
            .find("#item_name_table_" + item_id)
            .html();
          let item_vat = $(this).find(".item_vat").html();
          let item_discount = $(this)
            .find("#percentage_table_" + item_id)
            .val();
          let discount_type = "";
          if (
            item_discount.length > 0 &&
            item_discount.substr(item_discount.length - 1) == "%"
          ) {
            discount_type = "percentage";
          } else {
            discount_type = "fixed";
          }
          let item_previous_id = $(this)
            .find("#item_previous_id_table" + item_id)
            .html();
          let item_cooking_done_time = $(this)
            .find("#item_cooking_done_time_table" + item_id)
            .html();
          let item_cooking_start_time = $(this)
            .find("#item_cooking_start_time_table" + item_id)
            .html();
          let item_cooking_status = $(this)
            .find("#item_cooking_status_table" + item_id)
            .html();
          let item_type = $(this)
            .find("#item_type_table" + item_id)
            .html();
          let item_price_without_discount = $(this)
            .find(".item_price_without_discount")
            .html();
          let item_unit_price = $(this)
            .find("#item_price_table_" + item_id)
            .html();
          let item_quantity = $(this)
            .find("#item_quantity_table_" + item_id)
            .html();
          let item_price_with_discount = $(this)
            .find("#item_total_price_table_" + item_id)
            .html();
          let item_discount_amount = (
            parseFloat(item_price_without_discount) -
            parseFloat(item_price_with_discount)
          ).toFixed(ir_precision);

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
            let modifiers_id = $(this)
              .find("#item_modifiers_id_table_" + item_id)
              .html();
            let modifiers_price = $(this)
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
            let item_note = $(this)
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

      let order_object = JSON.stringify(order_info);

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
  $(document).on(
    "click",
    "#print_invoice,#order_details_create_invoice_button",
    function (e) {
      if (
        $(".holder .order_details > .single_order[data-selected=selected]")
          .length > 0
      ) {
        let sale_id = $(
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
            $("#finalize_order_modal").addClass("active");
            $(".pos__modal__overlay").fadeIn(200);
            $("#open_invoice_date_hidden").val(response.sale_date);

            $(".datepicker_custom")
              .datepicker({
                autoclose: true,
                format: "yyyy-mm-dd",
                startDate: "0",
                todayHighlight: true,
              })
              .datepicker("update", response.sale_date);

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
  $(document).on("click", "#finalize_order_button", function (e) {
    let due_amount_invoice_input = Number($("#due_amount_invoice_input").val());
    let customer_id = $("#selected_invoice_sale_customer").val();
    let status = true;
    if (customer_id == 1) {
      if (due_amount_invoice_input) {
        status = false;
      }
    }
    if (status == true) {
      $("#print_type").val(1);
      let sale_id = $("#last_future_sale_id").val();
      if (sale_id > 0) {
        let sale_id = $("#last_future_sale_id").val();
        let payment_method_type = $("#finalie_order_payment_method").val();
        let paid_amount = $("#pay_amount_invoice_input").val();
        let due_amount = $("#due_amount_invoice_input").val();
        let invoice_create_type = $("#finalize_update_type").html();
        if (payment_method_type == "") {
          $("#finalie_order_payment_method").css("border", "1px solid red");
          let op1_finalie_order_payment_method = $(
            "#finalie_order_payment_method"
          ).data("select2");
          op1_finalie_order_payment_method.open();
          return false;
        }
        $("#last_invoice_id").val(sale_id);
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
  $(document).on("click", "#add_customer", function (e) {
    let customer_id = $("#customer_id_modal").val();
    let customer_name = $("#customer_name_modal").val();
    let customer_phone = $("#customer_phone_modal").val();
    let customer_email = $("#customer_email_modal").val();
    let customer_dob = $("#customer_dob_modal").val();
    let customer_doa = $("#customer_doa_modal").val();
    let customer_delivery_address = $("#customer_delivery_address_modal").val();
    let customer_gst_number = $("#customer_gst_number_modal").val();
    let error = 0;

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
    let this_action = $(this);
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
          $("#walk_in_customer").val(response).change();
          $.ajax({
            url: base_url + "Sale/get_all_customers_for_this_user",
            method: "GET",
            success: function (response) {
              response = JSON.parse(response);
              let option_customers = "";
              let i = 1;
              let selected_id = "";
              let selected_name = "";
              for (let key in response) {
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
                  let new_customer = {
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

              this_action
                .parent()
                .parent()
                .parent()
                .parent()
                .removeClass("active")
                .addClass("inActive");
              setTimeout(function () {
                $(".modal").removeClass("inActive");
              }, 1000);
              $(".pos__modal__overlay").fadeOut(300);
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
  let modify_order_button = $("#modify_order");
  // let modify_order_button = modify_order_button.position();
  let modify_order_top =
    modify_order_button.offset().top - $(document).scrollTop();
  let modify_order_left =
    modify_order_button.offset().left - $(document).scrollLeft();
  let modify_order_width = (
    parseFloat(modify_order_button.width()) + parseFloat(30)
  ).toFixed(ir_precision);
  let modify_order_height = modify_order_button.height();
  let modify_order_half_height = (
    parseFloat(modify_order_height) / parseFloat(50)
  ).toFixed(ir_precision);
  let order_tool_tip_top = document
    .getElementById("modify_order")
    .getBoundingClientRect().top;

  $("#modify_button_tool_tip").css("top", order_tool_tip_top + "px");
  $("#modify_button_tool_tip").css("left", modify_order_width + "px");

  //tooltip direct invoice button
  let direct_invoice_button = $("#direct_invoice");
  // let direct_invoice_button = direct_invoice_button.position();
  let direct_invoice_top =
    direct_invoice_button.offset().top - $(document).scrollTop();
  let direct_invoice_left =
    direct_invoice_button.offset().left - $(document).scrollLeft();
  let direct_invoice_height = direct_invoice_button.height();
  let direct_invoice_tool_tip_top = (
    parseFloat(direct_invoice_top) -
    parseFloat(direct_invoice_height) -
    parseFloat(10)
  ).toFixed(ir_precision);
  // let direct_invoice_tool_tip_top_position = (parseFloat(direct_invoice_tool_tip_top)-parseFloat(direct_invoice_button_height)-parseFloat(20)).toFixed(ir_precision);
  $("#direct_invoice_button_tool_tip").css(
    "top",
    direct_invoice_tool_tip_top + "px"
  );
  $("#direct_invoice_button_tool_tip").css("left", direct_invoice_left + "px");
});
//update all price of modal
function update_all_total_price() {
  //get item quantity
  let item_quantity = parseFloat($("#item_quantity_modal").html()).toFixed(
    ir_precision
  );
  //get item unit price
  let item_unit_price = parseFloat($("#modal_item_price").html()).toFixed(
    ir_precision
  );
  //get item total price without discount
  let item_total_price_without_discount = (
    parseFloat(item_quantity) * parseFloat(item_unit_price)
  ).toFixed(ir_precision);
  //set item total price without discount
  $("#modal_item_price_variable_without_discount").html(
    item_total_price_without_discount
  );

  //get discount from modal
  let discount_on_modal = $("#modal_discount").val();
  discount_on_modal = discount_on_modal != "" ? discount_on_modal : 0;

  //remove last digits if number is more than 2 digits after decimal
  remove_last_two_digit_with_percentage(
    discount_on_modal,
    $("#modal_discount")
  );

  //get discount actual amount on item price
  let actual_modal_discount_amount = get_particular_item_discount_amount(
    discount_on_modal,
    item_total_price_without_discount
  );
  //set actual discount amouto hidden modal element
  $("#modal_discount_amount").html(
    parseFloat(actual_modal_discount_amount).toFixed(ir_precision)
  );

  //get item price after discount
  let item_price_after_discount = (
    parseFloat(item_total_price_without_discount) -
    parseFloat(actual_modal_discount_amount)
  ).toFixed(ir_precision);

  //set item total price with discount
  $("#modal_item_price_variable").html(item_price_after_discount);

  //get modifiers unit sum price
  let modifiers_unit_sum_price = parseFloat(
    $("#modal_modifiers_unit_price_variable").html()
  ).toFixed(ir_precision);

  //set modifiers price as per item quantity
  let modifiers_price = (
    parseFloat(modifiers_unit_sum_price) * parseFloat(item_quantity)
  ).toFixed(ir_precision);
  $("#modal_modifier_price_variable").html(modifiers_price);
  //add items and modifiers price
  let all_price = (
    parseFloat(item_price_after_discount) + parseFloat(modifiers_price)
  ).toFixed(ir_precision);

  //show to all total
  $("#modal_total_price").html(all_price);
}
// ==================================================
function show_all_items() {
  $(".specific_category_items_holder").hide();

  setTimeout(function () {
    let foundItems = searchItemAndConstructGallery("");
    let searched_category_items_to_show =
      '<div id="searched_item_found" class="specific_category_items_holder txt_9">';
    for (let key in foundItems) {
      if (foundItems.hasOwnProperty(key)) {
        let veg_status = "no";
        if (foundItems[key].veg_item_status == "yes") {
          veg_status = "yes";
        }
        let beverage_status = "no";
        if (foundItems[key].beverage_item_status == "yes") {
          beverage_status = "yes";
        }

        searched_category_items_to_show +=
          '<div class="single_item animate__animated all_item_custom" data-veg_status="' +
          veg_status +
          '" data-beverage_status="' +
          beverage_status +
          '" id="item_' +
          foundItems[key].item_id +
          '">';
        searched_category_items_to_show +=
          '<img src="' + foundItems[key].image + '" alt="" width="141">';
        searched_category_items_to_show +=
          '<p class="item_name" data-tippy-content="' +
          foundItems[key].item_name +
          '">' +
          foundItems[key].item_name +
          " (" +
          foundItems[key].item_code +
          ")</p>";
        searched_category_items_to_show +=
          '<p class="item_price">' +
          price_txt +
          ": " +
          foundItems[key].price +
          "</p>";
        searched_category_items_to_show +=
          '<span class="item_vat_percentage ir_display_none">' +
          foundItems[key].vat_percentage +
          "</span>";
        searched_category_items_to_show += "</div>";
      }
    }
    searched_category_items_to_show += "<div>";
    $("#searched_item_found").remove();
    $(".specific_category_items_holder").fadeOut(0);
    $(".category_items").prepend(searched_category_items_to_show);
    tippy(".item_name", {
      placement: "bottom-start",
    });
  }, 100);

  //call this function to adjust the height of left side order list
  adjust_left_side_order_list();

  //call this function to adjust the height of right side item list
  adjust_right_side_item_list();

  adjust_middle_side_cart_list();
  $(document).on("click", ".single_table_div", function (e) {
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
  $(document).on("click", "#close_select_table_modal", function (e) {
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
  $(document).on("click", "#selected_table_done", function (e) {
    $("#show_tables_modal").slideUp(333);
  });
  $(document).on("click", "#refresh_order", function (e) {
    $(this).css("color", "#495057");
    $("#stop_refresh_for_search").html("yes");
    set_new_orders_to_view_for_interval();
  });
  $(document).on("click", ".holder .order_details .single_order", function () {
    let sale_id = $(this).attr("id").substr(6);
    $("#last_future_sale_id").val(sale_id);
    $(".holder .order_details .single_order").attr(
      "data-selected",
      "unselected"
    );
    $(".holder .order_details .single_order").css(
      "background-color",
      "#ffffff"
    );
    $(this).attr("data-selected", "selected");
    $(this).css("background-color", "#ecf0f1");
    $("#refresh_order").css("color", "#dc3545");

    sale_id = $(this).attr("id").substr(6);
    let flexible_div = $(this).find(".inside_single_order_container").height();
  });

  $("body").on("click", ".running_order_right_arrow", function () {
    $(this).parent().parent().toggleClass("active");
  });

  $(window).click(function (event) {
    if ($(event.target).closest(".running_order_right_arrow").length === 0) {
      $("body").find(".inside_single_order_container").removeClass("active");
    }
  });

  $(document).on("click", "#modify_order", function (e) {
    if (
      $(".holder .order_details > .single_order[data-selected=selected]")
        .length > 0
    ) {
      //get total items in cart
      let total_items_in_cart = $(".order_holder .single_order").length;

      if (total_items_in_cart > 0) {
        swal(
          {
            title: warning + "!",
            text: txt_err_pos_5,
            confirmButtonColor: "#3c8dbc",
            confirmButtonText: ok,
            showCancelButton: true,
          },
          function () {
            $(".order_holder").empty();
            let sale_id = $(
              ".holder .order_details .single_order[data-selected=selected]"
            )
              .attr("id")
              .substr(6);
            get_details_of_a_particular_order(sale_id);
          }
        );
      } else {
        let sale_id = $(
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
  $(document).on("click", "#single_order_details", function (e) {
    if (
      $(".holder .order_details > .single_order[data-selected=selected]")
        .length > 0
    ) {
      let sale_id = $(
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
  $(document).on("click", "#hold_sale", function (e) {
    if ($(".order_holder .single_order").length > 0) {
      $.ajax({
        url: base_url + "Sale/get_new_hold_number_ajax",
        method: "GET",
        success: function (response) {
          $("#generate_sale_hold_modal").addClass("active");
          $(".pos__modal__overlay").fadeIn(200);
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
  $(document).on("click", "#close_hold_modal", function (e) {
    $(this)
      .parent()
      .parent()
      .parent()
      .parent()
      .removeClass("active")
      .addClass("inActive");
    setTimeout(function () {
      $(".modal").removeClass("inActive");
    }, 1000);
    $(".pos__modal__overlay").fadeOut(300);

    $("#hold_generate_input").val("");
    $("#hold_generate_input").css("border", "1px solid #a0a0a0");
  });
  $(document).on("click", "#hold_cart_info", function (e) {
    let hold_number = $("#hold_generate_input").val();
    if (hold_number == "") {
      $("#hold_generate_input").css("border", "1px solid #dc3545");
      return false;
    } else {
      $("#hold_generate_input").css("border", "1px solid #a0a0a0");
    }
    let selected_order_type_object = $(".main_top")
      .find("button[data-selected=selected]")
      .attr("data-selected", "unselected");
    let total_items_in_cart = $(".order_holder .single_order").length;
    let sub_total = parseFloat($("#sub_total_show").html()).toFixed(
      ir_precision
    );
    let charge_type = $("#charge_type").val();
    let total_vat = parseFloat($("#all_items_vat").html()).toFixed(
      ir_precision
    );
    let total_payable = parseFloat($("#total_payable").html()).toFixed(
      ir_precision
    );
    let total_item_discount_amount = parseFloat(
      $("#total_item_discount").html()
    ).toFixed(ir_precision);
    let sub_total_with_discount = parseFloat(
      $("#discounted_sub_total_amount").html()
    ).toFixed(ir_precision);
    let sub_total_discount_amount = parseFloat(
      $("#sub_total_discount_amount").html()
    ).toFixed(ir_precision);
    let total_discount_amount = parseFloat(
      $("#all_items_discount").html()
    ).toFixed(ir_precision);
    let delivery_charge = $("#delivery_charge").val();
    let sub_total_discount_value = $("#sub_total_discount").val();
    let sub_total_discount_type = "";
    let sale_vat_objects = [];
    $("#tax_row_show .tax_field").each(function (i, obj) {
      let tax_field_id = $(this).attr("data-tax_field_id");
      let tax_field_type = $(this).attr("data-tax_field_type");
      let tax_field_amount = $(this).attr("data-tax_field_amount");
      sale_vat_objects.push({
        tax_field_id: tax_field_id,
        tax_field_type: tax_field_type,
        tax_field_amount: Number(tax_field_amount).toFixed(ir_precision),
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
      sub_total_discount_type = "fixed";
    }
    let selected_table = 0;

    if ($(".single_table_div[data-table-checked=checked]").length > 0) {
      selected_table = $(".single_table_div[data-table-checked=checked]")
        .attr("id")
        .substr(13); //1; //demo
    }

    let order_type = 0;
    if (selected_order_type_object.attr("id") == "delivery_button") {
      order_type = 3;
    } else if (selected_order_type_object.attr("id") == "dine_in_button") {
      order_type = 1;
    } else if (selected_order_type_object.attr("id") == "take_away_button") {
      order_type = 2;
    }

    let customer_id = $("#walk_in_customer").val();
    let waiter_id = $("#select_waiter").val();

    let order_status = 1;
    let open_invoice_date_hidden = $("#open_invoice_date_hidden").val();
    let order_info = "{";
    order_info += '"customer_id":"' + customer_id + '",';
    order_info += '"waiter_id":"' + waiter_id + '",';
    order_info +=
      '"open_invoice_date_hidden":"' + open_invoice_date_hidden + '",';
    order_info += '"total_items_in_cart":"' + total_items_in_cart + '",';
    order_info += '"charge_type":"' + charge_type + '",';
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

    let orders_table = "";
    orders_table += '"orders_table":';
    orders_table += "[";
    let x = 1;
    let total_orders_table = $(".new_book_to_table").length;
    $(".new_book_to_table").each(function (i, obj) {
      let table_id = $(this).attr("id").substr(16);
      let person = $(this).find(".third_column").html();
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

    let items_info = "";
    items_info += '"items":';
    items_info += "[";

    if ($(".order_holder .single_order").length > 0) {
      let k = 1;
      $(".order_holder .single_order").each(function (i, obj) {
        let item_id = $(this).attr("id").substr(15);
        let item_name = $(this)
          .find("#item_name_table_" + item_id)
          .html();
        let item_vat = $(this).find(".item_vat").html();
        let item_discount = $(this)
          .find("#percentage_table_" + item_id)
          .val();
        let discount_type = "";
        if (
          item_discount.length > 0 &&
          item_discount.substr(item_discount.length - 1) == "%"
        ) {
          discount_type = "percentage";
        } else {
          discount_type = "fixed";
        }
        let item_price_without_discount = $(this)
          .find(".item_price_without_discount")
          .html();
        let item_unit_price = $(this)
          .find("#item_price_table_" + item_id)
          .html();
        let item_quantity = $(this)
          .find("#item_quantity_table_" + item_id)
          .html();
        let item_price_with_discount = $(this)
          .find("#item_total_price_table_" + item_id)
          .html();
        let item_discount_amount = (
          parseFloat(item_price_without_discount) -
          parseFloat(item_price_with_discount)
        ).toFixed(ir_precision);
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

        let ji = 1;
        let modifier_vat = "";
        $(".item_vat_modifier_" + item_id).each(function (i, obj) {
          if (ji == $(".item_vat_modifier_" + item_id).length) {
            modifier_vat += $(this).html();
          } else {
            modifier_vat += $(this).html() + "|||";
          }
          ji++;
        });

        if ($(this).find(".second_portion").length > 0) {
          let modifiers_id = $(this)
            .find("#item_modifiers_id_table_" + item_id)
            .html();
          let modifiers_price = $(this)
            .find("#item_modifiers_price_table_" + item_id)
            .html();
          items_info +=
            ',"modifiers_id":"' +
            modifiers_id +
            '", "modifiers_price":"' +
            modifiers_price +
            '", "modifier_vat":' +
            JSON.stringify(modifier_vat);
        } else {
          items_info +=
            ',"modifiers_id":"", "modifiers_price":"", "modifier_vat":""';
        }
        if ($(this).find(".third_portion").length > 0) {
          let item_note = $(this)
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

    let order_object = JSON.stringify(order_info);

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
  let left_side_button_holder_height = $(
    "#left_side_button_holder_absolute"
  ).height();
  let main_left_portion_height = $(".main_left").height();
  let header_of_left_portion_height = $(".main_left h3").height();
  let height_left_button_holder_and_header = (
    parseFloat(left_side_button_holder_height) +
    parseFloat(header_of_left_portion_height)
  ).toFixed(ir_precision);
  let remaining_height_for_list = (
    parseFloat(main_left_portion_height) -
    parseFloat(height_left_button_holder_and_header) -
    parseFloat(35)
  ).toFixed(ir_precision);
  $(".order_details").css("height", remaining_height_for_list - 20 + "px");
}
//this function adjust the left side order list height
function adjust_middle_side_cart_list() {
  let middle_side_bottom_holder_height = $("#bottom_absolute").height();
  let main_middle_portion_height = $(".main_middle").height();
  let order_table_header_row = $(".order_table_header_row").height();
  let header_of_middle_portion_height = $(".main_middle .main_top").height();
  let height_middle_bottom_holder_and_header = (
    parseFloat(middle_side_bottom_holder_height) +
    parseFloat(header_of_middle_portion_height) +
    parseFloat(order_table_header_row)
  ).toFixed(ir_precision);
  let remaining_height_for_cart = (
    parseFloat(main_middle_portion_height) -
    parseFloat(height_middle_bottom_holder_and_header)
  ).toFixed(ir_precision);

  $(".main_middle .order_holder").css(
    "height",
    remaining_height_for_cart - 40 + "px"
  );
}
//this function adjust the right side item list height
function adjust_right_side_item_list() {
  let main_right_portion_height = $(".main_right").height();
  let search_item_input_height = $("#search").height();
  let top_header_height = $(".top_header").height();

  let search_item_input_margin_bottom = parseFloat(
    $("#search").css("margin-bottom")
  );
  let select_category_height = $(".select_category").height();
  let select_category_margin_bottom = parseFloat(
    $(".select_category").css("margin-bottom")
  );
  let search_input_total_height = (
    parseFloat(search_item_input_height) +
    parseFloat(search_item_input_margin_bottom)
  ).toFixed(ir_precision);
  let select_category_total_height = (
    parseFloat(select_category_height) +
    parseFloat(select_category_margin_bottom)
  ).toFixed(ir_precision);
  let total_height_except_item_list = (
    parseFloat(search_input_total_height) +
    parseFloat(select_category_total_height)
  ).toFixed(ir_precision);
  let remaining_height_for_item_list = (
    parseFloat(main_right_portion_height) -
    parseFloat(total_height_except_item_list) -
    parseFloat(13)
  ).toFixed(ir_precision);

  $("#secondary_item_holder").css("height", main_right_portion_height + "px");
  $(".category_items").css("height", main_right_portion_height - 35 + "px");
}
//KOT
$(document).on("change", "#kot_check_all", function (e) {
  if ($(this).is(":checked")) {
    $(".kot_item_checkbox").prop("checked", true);
  } else {
    $(".kot_item_checkbox").prop("checked", false);
  }
});
function print_kot(sale_id) {
  let print_type_kot = $(".print_type_kot").val();
  if (print_type_kot == "web_browser") {
    let newWindow = open(
      base_url + "Sale/print_kot/" + sale_id,
      "Print KOT",
      "width=450,height=550"
    );
    newWindow.focus();

    newWindow.onload = function () {
      newWindow.document.body.insertAdjacentHTML("afterbegin");
    };
  } else {
    $.ajax({
      url: base_url + "Authentication/printSaleKotByAjax",
      method: "post",
      dataType: "json",
      data: {
        sale_id: sale_id,
      },
      success: function (data) {
        if (data.printer_server_url) {
          $.ajax({
            url:
              data.printer_server_url +
              "print_server/irestora_printer_server.php",
            method: "post",
            dataType: "json",
            data: {
              content_data: JSON.stringify(data.content_data),
            },
            success: function (data) {},
            error: function () {},
          });
        }
      },
      error: function () {},
    });
  }
}
$(document).on("click", "#print_kot_modal", function (e) {
  let order_number = $("#kot_modal_order_number").html();
  let order_date = $("#kot_modal_order_date").html();
  let customer_name = $("#kot_modal_customer_name").html();
  let table_name = $("#kot_modal_table_name").html();
  let waiter_name = $("#kot_modal_waiter_name").html();
  let order_type = $("#kot_modal_order_type").html();

  let order_info = "{";
  order_info += '"order_number":"' + order_number + '",';
  order_info += '"order_date":"' + order_date + '",';
  order_info += '"customer_name":"' + customer_name + '",';
  order_info += '"table_name":"' + table_name + '",';
  order_info += '"waiter_name":"' + waiter_name + '",';
  order_info += '"order_type":"' + order_type + '",';
  let items_info = "";
  items_info += '"items":';
  items_info += "[";

  let order_details_id = "";
  let j = 1;
  let checkbox_length = $(
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
    let order_details_id_array = order_details_id.split(",");
    let k = 1;
    let item_array_length = order_details_id_array.length;
    order_details_id_array.forEach(function (entry) {
      let single_kot_row = $("#kot_for_item_" + entry);
      let kot_item_name = single_kot_row.find(".kot_item_name_column").html();
      let kot_item_qty = $("#kot_modal_item_qty_" + entry).html();
      let tmp_qty = $("#tmp_qty_" + entry).val();
      let p_qty = $("#p_qty_" + entry).val();

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
      let modifiers = "";
      let m = 1;
      $(".single_modifier:visible").each(function (i, obj) {
        let this_id = $(this).attr("data-item-detail-id");
        if (this_id == entry) {
          modifiers += $(this).html() + ",";
        }
      });
      let tmp_note = $("#kot_modal_note_" + entry).val();
      items_info += ',"modifiers":"' + modifiers + '"';
      items_info += ',"notes":"' + tmp_note + '"';
      items_info += k == item_array_length ? "}" : "},";
      k++;
    });
  }
  items_info += "]";
  order_info += items_info + "}";

  let order_object = JSON.stringify(order_info);

  $.ajax({
    url: base_url + "Sale/add_temp_kot_ajax",
    method: "POST",
    data: {
      order: order_object,
      csrf_irestoraplus: csrf_value_,
    },
    success: function (response) {
      if (response > 0) {
        $("#kot_list_modal").removeClass("active");
        $(".pos__modal__overlay").fadeOut(300);
        let print_type_kot = $(".print_type_kot").val();
        if (print_type_kot == "web_browser") {
          let newWindow = open(
            base_url + "Sale/print_temp_kot/" + Number(response),
            "Print KOT",
            "width=450,height=550"
          );
          newWindow.focus();

          newWindow.onload = function () {
            newWindow.document.body.insertAdjacentHTML("afterbegin");
          };
        } else {
          $.ajax({
            url: base_url + "Authentication/printSaleTempKotByAjax",
            method: "post",
            dataType: "json",
            data: {
              sale_id: Number(response),
            },
            success: function (data) {
              if (data.printer_server_url) {
                $.ajax({
                  url:
                    data.printer_server_url +
                    "print_server/irestora_printer_server.php",
                  method: "post",
                  dataType: "json",
                  data: {
                    content_data: JSON.stringify(data.content_data),
                  },
                  success: function (data) {},
                  error: function () {},
                });
              }
            },
            error: function () {},
          });
        }
      }
    },
    error: function () {
      alert(a_error);
    },
  });
});
$(document).on("click", ".kot_content_column .single_modifier", function () {
  let current_selection = $(this).attr("data-selected");
  if (current_selection == "selected") {
    $(this).attr("data-selected", "unselected");
    $(this).css("background-color", "#E0E0E0");
  } else if (current_selection == "unselected") {
    $(this).attr("data-selected", "selected");
    // $(this).css("background-color", "#B5D6F6");
  }
});
$(document).on("click", "#print_kot", function (e) {
  if (
    $(".holder .order_details > .single_order[data-selected=selected]").length >
    0
  ) {
    let sale_id = $(
      ".holder .order_details .single_order[data-selected=selected]"
    )
      .attr("id")
      .substr(6);
    get_details_of_a_particular_order_for_kot_modal(sale_id);
    $("#kot_check_all").prop("checked", true);
    $("#kot_list_modal").addClass("active");
    $(".pos__modal__overlay").fadeIn(200);
  } else {
    swal({
      title: warning + "!",
      text: please_select_open_order,
      confirmButtonText: ok,
      confirmButtonColor: "#b6d6f6",
    });
  }
});
$(document).on("click", "#order_details_print_kot_button", function (e) {
  if (
    $(".holder .order_details > .single_order[data-selected=selected]").length >
    0
  ) {
    let sale_id = $(
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
  let item_detail_id = $(this).attr("id").substr(24);
  let qty_element = $("#kot_modal_item_qty_" + item_detail_id);
  let qty_element_fixed = $("#kot_modal_item_qty_fixed_" + item_detail_id);
  let qty = parseInt(qty_element.html());
  if (qty > 1) {
    qty--;
  }
  qty_element.html(qty);
});
$(document).on("click", ".increase_item_kot_modal", function () {
  let item_detail_id = $(this).attr("id").substr(24);
  let qty_element = $("#kot_modal_item_qty_" + item_detail_id);
  let qty_element_fixed = $("#kot_modal_item_qty_fixed_" + item_detail_id);
  let qty = parseInt(qty_element.html());
  let qty_fixed = parseInt(qty_element_fixed.html());
  if (qty < qty_fixed) {
    qty++;
  }

  qty_element.html(qty);
});
$(document).on("click", "#cancel_kot_modal", function (e) {
  $("#kot_check_all").prop("checked", false);
  $("#kot_modal_modified_or_not").hide();
  $(this).parent().parent().parent().removeClass("active").addClass("inActive");
  setTimeout(function () {
    $(".modal").removeClass("inActive");
  }, 1000);
  $(".pos__modal__overlay").fadeOut(300);
});
$(document).on("click", "#cancel_kot_modal2", function (e) {
  $("#kot_check_all").prop("checked", false);
  $("#kot_modal_modified_or_not").hide();
  $(this).parent().parent().parent().removeClass("active").addClass("inActive");
  setTimeout(function () {
    $(".modal").removeClass("inActive");
  }, 1000);
  $(".pos__modal__overlay").fadeOut(300);
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
      let order_type = "";
      let order_type_id = 0;
      let order_number = "";
      let tables_booked = "";
      if (response.tables_booked.length > 0) {
        let w = 1;
        for (let k in response.tables_booked) {
          let single_table = response.tables_booked[k];
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
      $("#open_invoice_date_hidden").val(response.sale_date);

      $(".datepicker_custom")
        .datepicker({
          autoclose: true,
          format: "yyyy-mm-dd",
          startDate: "0",
          todayHighlight: true,
        })
        .datepicker("update", response.sale_date);

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
      let draw_table_for_kot_modal = "";

      for (let key in response.items) {
        //construct div
        let this_item = response.items[key];

        let selected_modifiers = "";
        let selected_modifiers_id = "";
        let selected_modifiers_price = "";
        let modifiers_price = 0;
        let total_modifier = this_item.modifiers.length;
        let i = 1;
        for (let mod_key in this_item.modifiers) {
          let this_modifier = this_item.modifiers[mod_key];
          //get selected modifiers
          if (i == total_modifier) {
            selected_modifiers += this_modifier.name;
            selected_modifiers_id += this_modifier.modifier_id;
            selected_modifiers_price += this_modifier.modifier_price;
            modifiers_price = (
              parseFloat(modifiers_price) +
              parseFloat(this_modifier.modifier_price)
            ).toFixed(ir_precision);
          } else {
            selected_modifiers += this_modifier.name + ", ";
            selected_modifiers_id += this_modifier.modifier_id + ",";
            selected_modifiers_price += this_modifier.modifier_price + ",";
            modifiers_price = (
              parseFloat(modifiers_price) +
              parseFloat(this_modifier.modifier_price)
            ).toFixed(ir_precision);
          }
          i++;
        }
        let selected_modifiers_array = selected_modifiers.split(",");
        let selected_modifiers_id_array = selected_modifiers_id.split(",");
        let selected_modifiers_price_array =
          selected_modifiers_price.split(",");
        let modifier_divs = "";
        let modifier_length = selected_modifiers_array.length;
        if (modifier_length > 0) {
          for (let index = 0; index < modifier_length; index++) {
            modifier_divs +=
              selected_modifiers_array[index] != ""
                ? "<span>" + selected_modifiers_array[index] + "</span>, "
                : "";
          }
        }
        let discount_value =
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
            '"><div  class="kot_content_column fix floatleft kot_item_name_column txt_10">';
          draw_table_for_kot_modal += this_item.menu_name;
          draw_table_for_kot_modal += modifier_divs
            ? "<br>" + modifiers_txt + ": " + modifier_divs
            : "";
          draw_table_for_kot_modal += this_item.menu_note
            ? "<br>Notes: " + this_item.menu_note
            : "";
          draw_table_for_kot_modal += "</div>";
          draw_table_for_kot_modal +=
            '<div class="kot_content_column fix floatleft kot_qty_column">';
          draw_table_for_kot_modal +=
            '<i   id="decrease_item_kot_modal_' +
            this_item.id +
            '" class="fal fa-minus decrease_item_kot_modal txt_5"></i>';
          draw_table_for_kot_modal +=
            ' <span class="txt_11" id="kot_modal_item_qty_fixed_' +
            this_item.id +
            '">' +
            this_item.qty +
            '</span><span id="kot_modal_item_qty_' +
            this_item.id +
            '">' +
            this_item.qty +
            '</span> <input type="hidden" class="tmp_qty" name="tmp_qty" value="' +
            this_item.tmp_qty +
            '" id="tmp_qty_' +
            this_item.id +
            '"><input type="hidden" class="p_qty" name="p_qty" value="' +
            this_item.qty +
            '" id="p_qty_' +
            this_item.id +
            '">';
          draw_table_for_kot_modal +=
            '<i  id="increase_item_kot_modal_' +
            this_item.id +
            '" class="fal fa-plus increase_item_kot_modal txt_5"></i>';
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
$(document).on("change", "#bot_check_all", function (e) {
  if ($(this).is(":checked")) {
    $(".bot_item_checkbox").prop("checked", true);
  } else {
    $(".bot_item_checkbox").prop("checked", false);
  }
});
function print_bot(sale_id) {
  let print_type_bot = $(".print_type_bot").val();
  if (print_type_bot == "web_browser") {
    let newWindow = open(
      base_url + "Sale/print_bot/" + sale_id,
      "Print BOT",
      "width=450,height=550"
    );
    newWindow.focus();

    newWindow.onload = function () {
      newWindow.document.body.insertAdjacentHTML("afterbegin");
    };
  } else {
    $.ajax({
      url: base_url + "Authentication/printSaleBotByAjax",
      method: "post",
      dataType: "json",
      data: {
        sale_id: sale_id,
      },
      success: function (data) {
        if (data.printer_server_url) {
          $.ajax({
            url:
              data.printer_server_url +
              "print_server/irestora_printer_server.php",
            method: "post",
            dataType: "json",
            data: {
              content_data: JSON.stringify(data.content_data),
            },
            success: function (data) {},
            error: function () {},
          });
        }
      },
      error: function () {},
    });
  }
}
$(document).on("click", "#print_bot_modal", function (e) {
  let order_number = $("#bot_modal_order_number").html();
  let order_date = $("#bot_modal_order_date").html();
  let customer_name = $("#bot_modal_customer_name").html();
  let table_name = $("#bot_modal_table_name").html();
  let waiter_name = $("#bot_modal_waiter_name").html();
  let order_type = $("#bot_modal_order_type").html();

  let order_info = "{";
  order_info += '"order_number":"' + order_number + '",';
  order_info += '"order_date":"' + order_date + '",';
  order_info += '"customer_name":"' + customer_name + '",';
  order_info += '"table_name":"' + table_name + '",';
  order_info += '"waiter_name":"' + waiter_name + '",';
  order_info += '"order_type":"' + order_type + '",';
  let items_info = "";
  items_info += '"items":';
  items_info += "[";

  let order_details_id = "";
  let j = 1;
  let checkbox_length = $(
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
    let order_details_id_array = order_details_id.split(",");
    let k = 1;
    let item_array_length = order_details_id_array.length;

    order_details_id_array.forEach(function (entry) {
      let single_bot_row = $("#bot_for_item_" + entry);
      let bot_item_name = single_bot_row.find(".bot_item_name_column").html();
      let bot_item_qty = $("#bot_modal_item_qty_" + entry).html();
      let tmp_qty = $("#tmp_qty_" + entry).val();
      let p_qty = $("#p_qty_" + entry).val();

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
      let modifiers = "";
      let m = 1;
      $(".single_modifier:visible").each(function (i, obj) {
        let this_id = $(this).attr("data-item-detail-id");
        if (this_id == entry) {
          modifiers += $(this).html() + ",";
        }
      });
      let tmp_note = $("#bot_modal_note_" + entry).val();
      items_info += ',"modifiers":"' + modifiers + '"';
      items_info += ',"notes":"' + tmp_note + '"';
      items_info += k == item_array_length ? "}" : "},";
      k++;
    });
  }
  items_info += "]";
  order_info += items_info + "}";

  let order_object = JSON.stringify(order_info);

  $.ajax({
    url: base_url + "Sale/add_temp_bot_ajax",
    method: "POST",
    data: {
      order: order_object,
      csrf_irestoraplus: csrf_value_,
    },
    success: function (response) {
      if (response > 0) {
        $("#bot_list_modal").removeClass("active");
        $(".pos__modal__overlay").fadeOut(300);

        let print_type_bot = $(".print_type_bot").val();
        if (print_type_bot == "web_browser") {
          let newWindow = open(
            base_url + "Sale/print_temp_bot/" + Number(response),
            "Print BOT",
            "width=450,height=550"
          );
          newWindow.focus();
          newWindow.onload = function () {
            newWindow.document.body.insertAdjacentHTML("afterbegin");
          };
        } else {
          $.ajax({
            url: base_url + "Authentication/printSaleTempBotByAjax",
            method: "post",
            dataType: "json",
            data: {
              sale_id: Number(response),
            },
            success: function (data) {
              if (data.printer_server_url) {
                $.ajax({
                  url:
                    data.printer_server_url +
                    "print_server/irestora_printer_server.php",
                  method: "post",
                  dataType: "json",
                  data: {
                    content_data: JSON.stringify(data.content_data),
                  },
                  success: function (data) {},
                  error: function () {},
                });
              }
            },
            error: function () {},
          });
        }
      }
    },
    error: function () {
      alert(a_error);
    },
  });
});
$(document).on("click", ".bot_content_column .single_modifier", function () {
  let current_selection = $(this).attr("data-selected");
  if (current_selection == "selected") {
    $(this).attr("data-selected", "unselected");
    $(this).css("background-color", "#E0E0E0");
  } else if (current_selection == "unselected") {
    $(this).attr("data-selected", "selected");
    // $(this).css("background-color", "#B5D6F6");
  }
});
$(document).on("click", "#print_bot", function (e) {
  if (
    $(".holder .order_details > .single_order[data-selected=selected]").length >
    0
  ) {
    let sale_id = $(
      ".holder .order_details .single_order[data-selected=selected]"
    )
      .attr("id")
      .substr(6);
    get_details_of_a_particular_order_for_bot_modal(sale_id);
    $("#bot_check_all").prop("checked", true);
    $("#bot_list_modal").addClass("active");
    $(".pos__modal__overlay").fadeIn(200);
  } else {
    swal({
      title: warning + "!",
      text: please_select_open_order,
      confirmButtonText: ok,
      confirmButtonColor: "#b6d6f6",
    });
  }
});
$(document).on("click", "#order_details_print_bot_button", function (e) {
  if (
    $(".holder .order_details > .single_order[data-selected=selected]").length >
    0
  ) {
    let sale_id = $(
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
  let item_detail_id = $(this).attr("id").substr(24);
  let qty_element = $("#bot_modal_item_qty_" + item_detail_id);
  let qty_element_fixed = $("#bot_modal_item_qty_fixed_" + item_detail_id);
  let qty = parseInt(qty_element.html());
  if (qty > 1) {
    qty--;
  }
  qty_element.html(qty);
});
$(document).on("click", ".increase_item_bot_modal", function () {
  let item_detail_id = $(this).attr("id").substr(24);
  let qty_element = $("#bot_modal_item_qty_" + item_detail_id);
  let qty_element_fixed = $("#bot_modal_item_qty_fixed_" + item_detail_id);
  let qty = parseInt(qty_element.html());
  let qty_fixed = parseInt(qty_element_fixed.html());
  if (qty < qty_fixed) {
    qty++;
  }

  qty_element.html(qty);
});
$(document).on("click", "#cancel_bot_modal", function (e) {
  $("#bot_check_all").prop("checked", false);
  $("#bot_modal_modified_or_not").hide();
  $(this).parent().parent().parent().removeClass("active").addClass("inActive");
  setTimeout(function () {
    $(".modal").removeClass("inActive");
  }, 1000);
  $(".pos__modal__overlay").fadeOut(300);
});
$(document).on("click", "#cancel_bot_modal2", function (e) {
  $("#bot_check_all").prop("checked", false);
  $("#bot_modal_modified_or_not").hide();
  $(this).parent().parent().parent().removeClass("active").addClass("inActive");
  setTimeout(function () {
    $(".modal").removeClass("inActive");
  }, 1000);
  $(".pos__modal__overlay").fadeOut(300);
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
      let order_type = "";
      let order_type_id = 0;
      let order_number = "";
      let tables_booked = "";
      if (response.tables_booked.length > 0) {
        let w = 1;
        for (let k in response.tables_booked) {
          let single_table = response.tables_booked[k];
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
      $("#open_invoice_date_hidden").val(response.sale_date);

      $(".datepicker_custom")
        .datepicker({
          autoclose: true,
          format: "yyyy-mm-dd",
          startDate: "0",
          todayHighlight: true,
        })
        .datepicker("update", response.sale_date);

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
      let draw_table_for_bot_modal = "";

      for (let key in response.items) {
        //construct div
        let this_item = response.items[key];

        let selected_modifiers = "";
        let selected_modifiers_id = "";
        let selected_modifiers_price = "";
        let modifiers_price = 0;
        let total_modifier = this_item.modifiers.length;
        let i = 1;
        for (let mod_key in this_item.modifiers) {
          let this_modifier = this_item.modifiers[mod_key];
          //get selected modifiers
          if (i == total_modifier) {
            selected_modifiers += this_modifier.name;
            selected_modifiers_id += this_modifier.modifier_id;
            selected_modifiers_price += this_modifier.modifier_price;
            modifiers_price = (
              parseFloat(modifiers_price) +
              parseFloat(this_modifier.modifier_price)
            ).toFixed(ir_precision);
          } else {
            selected_modifiers += this_modifier.name + ", ";
            selected_modifiers_id += this_modifier.modifier_id + ",";
            selected_modifiers_price += this_modifier.modifier_price + ",";
            modifiers_price = (
              parseFloat(modifiers_price) +
              parseFloat(this_modifier.modifier_price)
            ).toFixed(ir_precision);
          }
          i++;
        }
        let selected_modifiers_array = selected_modifiers.split(",");
        let selected_modifiers_id_array = selected_modifiers_id.split(",");
        let selected_modifiers_price_array =
          selected_modifiers_price.split(",");
        let modifier_divs = "";
        let modifier_length = selected_modifiers_array.length;
        if (modifier_length > 0) {
          for (let index = 0; index < modifier_length; index++) {
            modifier_divs +=
              selected_modifiers_array[index] != ""
                ? "<span>" + selected_modifiers_array[index] + "</span>, "
                : "";
          }
        }

        let discount_value =
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
            '"> <div  class="bot_content_column fix floatleft bot_item_name_column txt_10">';
          draw_table_for_bot_modal += this_item.menu_name;
          draw_table_for_bot_modal += modifier_divs
            ? "<br>" + modifiers_txt + ": " + modifier_divs
            : "";
          draw_table_for_bot_modal += this_item.menu_note
            ? "<br>Notes: " + this_item.menu_note
            : "";
          draw_table_for_bot_modal += "</div>";
          draw_table_for_bot_modal +=
            '<div class="bot_content_column fix floatleft bot_qty_column">';
          draw_table_for_bot_modal +=
            '<i  id="decrease_item_bot_modal_' +
            this_item.id +
            '" class="fal fa-minus decrease_item_bot_modal txt_5"></i>';
          draw_table_for_bot_modal +=
            ' <span class="txt_11" id="bot_modal_item_qty_fixed_' +
            this_item.id +
            '">' +
            this_item.qty +
            '</span><span id="bot_modal_item_qty_' +
            this_item.id +
            '">' +
            this_item.qty +
            '</span> <input type="hidden"  class="tmp_qty"   name="tmp_qty" value="' +
            this_item.tmp_qty +
            '" id="tmp_qty_' +
            this_item.id +
            '"><input type="hidden" class="p_qty" name="p_qty" value="' +
            this_item.qty +
            '" id="p_qty_' +
            this_item.id +
            '">';
          draw_table_for_bot_modal +=
            '<i   id="increase_item_bot_modal_' +
            this_item.id +
            '" class="fal fa-plus increase_item_bot_modal txt_5"></i>';
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
  let height_should_be =
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
$(window).on("load", function () {
  adjust_middle_side_cart_list();
});
function calculate_create_invoice_modal() {
  let total_payable = $("#finalize_total_payable").html();
  let paid_amount =
    $("#pay_amount_invoice_input").val() != ""
      ? $("#pay_amount_invoice_input").val()
      : 0;
  let due_amount = (
    parseFloat(total_payable) - parseFloat(paid_amount)
  ).toFixed(ir_precision);
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

  let total_of_all_items_and_modifiers = get_total_of_all_items_and_modifiers();
  //get total items in cart
  let total_items_in_cart = $(".order_holder .single_order").length;
  //set row number for every single item
  $(".order_holder .single_order").each(function (i, obj) {
    $(this).attr("data-single-order-row-no", i + 1);
  });
  //if there is no item in cart reset necessary field and value
  if (total_items_in_cart < 1) {
    $("#discounted_sub_total_amount").html(Number(0).toFixed(ir_precision));
    $("#sub_total_discount").val("");
    $("#sub_total_discount_amount").html(Number(0).toFixed(ir_precision));
    $("#all_items_discount").html(Number(0).toFixed(ir_precision));
  }
  let total_items_in_cart_with_quantity = 0;
  $(".qty_item_custom").each(function (i, obj) {
    total_items_in_cart_with_quantity =
      parseInt(total_items_in_cart_with_quantity) + parseInt($(this).html());
  });

  //set total items in cart to view
  $("#total_items_in_cart").html(total_items_in_cart);
  $("#total_items_in_cart_with_quantity").html(
    total_items_in_cart_with_quantity
  );
  //set sub total
  $("#sub_total").html(total_of_all_items_and_modifiers);
  $("#sub_total_show").html(total_of_all_items_and_modifiers);

  //get the value of the delivery charge amount


  let delivery_charge_amount = 0;

    let selected_btn_value = '';
    let countable_d_c = 'no';

    $(".selected__btn_c").each(function() {
        let this_id_name = $(this).attr('id');
        let this_selected_name = $(this).attr('data-selected');
        let charge_type_custom = $("#charge_type").val();

        if(this_selected_name=="selected"){
            if(charge_type_custom=="delivery" && (this_id_name!="take_away_button" && this_id_name!="dine_in_button")){
                countable_d_c = 'yes';
            }
            if(charge_type_custom=="service"  && (this_id_name!="take_away_button" && this_id_name!="delivery_button")){
                countable_d_c = 'yes';
            }
        }
    });
    let waiter_app_status1 = $("#waiter_app_status").val();
    if(waiter_app_status1=="Yes"){
        countable_d_c = 'yes';
    }
  let sub_total_show = Number($("#sub_total_show").html());
  if ($("#delivery_charge").val() != "" && countable_d_c=="yes") {
      delivery_charge_amount = get_particular_item_discount_amount(
          $("#delivery_charge").val(),
          sub_total_show
      );
      $("#show_charge_amount").html(
          Number(delivery_charge_amount).toFixed(ir_precision)
      );
  } else {
    $("#show_charge_amount").html(Number(0).toFixed(ir_precision));
  }
  //check wether value is valid or not
  /*  remove_last_two_digit_without_percentage(
    delivery_charge_amount,
    $("#delivery_charge")
  );*/

  //get subtotal amount
  let sub_total_amount = $("#sub_total").html();

  let sub_total_discount_amount = 0;
  //get subtotal discount amount
  if ($("#sub_total_discount").val() != "") {
    sub_total_discount_amount = $("#sub_total_discount").val();
    let tmt_value_sub_total = sub_total_discount_amount.split("%");
    if (tmt_value_sub_total[1] == "") {
      $("#show_discount_amount").html(sub_total_discount_amount);
    } else {
      $("#show_discount_amount").html(
        Number(sub_total_discount_amount).toFixed(ir_precision)
      );
    }
  } else {
    $("#show_discount_amount").html(Number(0).toFixed(ir_precision));
  }

  let sub_total_discount_value = $("#sub_total_discount").val();

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
  let total_item_discount_amount = parseFloat(
    $("#total_item_discount").html()
  ).toFixed(ir_precision);

  //get all discount of table
  let total_discount = (
    parseFloat(sub_total_discount_amount) +
    parseFloat(total_item_discount_amount)
  ).toFixed(ir_precision);

  //set sub total discount amount
  $("#sub_total_discount_amount").html(sub_total_discount_amount);

  //set sub total amount after discount in a hidden field
  let discounted_sub_total_amount = (
    parseFloat(sub_total_amount) - parseFloat(sub_total_discount_amount)
  ).toFixed(ir_precision);
  $("#discounted_sub_total_amount").html(discounted_sub_total_amount);

  //get vat amount
  let vat_amount = collect_tax == "Yes" ? get_total_vat() : null;

  let total_vat_section_to_show = "";
  let html_modal = "";
  let total_tax_custom = 0;

  $.each(vat_amount, function (key, value) {
    let row_id = 1;
    let key_value = key;
    total_vat_section_to_show +=
      '<span class="tax_field" id="tax_field_' +
      row_id +
      '">' +
      key_value +
      "</span>: " +
      currency +
      ' <span class="tax_field_amount" id="tax_field_amount_' +
      row_id +
      '">' +
      value +
      "</span><br/>";

    html_modal +=
      "<tr class='tax_field' data-tax_field_id='" +
      row_id +
      "'  data-tax_field_type='" +
      key_value +
      "'  data-tax_field_amount='" +
      value +
      "'>\n" +
      "                            <td>" +
      key_value +
      "</td>\n" +
      "                            <td>" +
      value +
      "</td>\n" +
      "                        </tr>";
    total_tax_custom += Number(value);
  });

  if (total_tax_custom) {
    $("#show_vat_modal").html(total_tax_custom.toFixed(ir_precision));
  } else {
    $("#show_vat_modal").html(Number(0).toFixed(ir_precision));
  }
  $("#tax_row_show").html(html_modal);
  //set vat amount to view
  // $('#all_items_vat').html(vat_amount);
  $("#all_items_vat").html(total_vat_section_to_show);

  //set total discount
  $("#all_items_discount").html(total_discount);

  //calculate total payable amount
  let total_payable_to_show = "";
  let total_vat_amount = 0;
  $.each(vat_amount, function (key, value) {
    let vat_tmp = 0;
    if (value && value != "NaN") {
      vat_tmp = value;
    }
    total_vat_amount = (
      parseFloat(total_vat_amount) + parseFloat(vat_tmp)
    ).toFixed(ir_precision);
  });

  let total_payable = (
    parseFloat(discounted_sub_total_amount) +
    parseFloat(total_vat_amount) +
    parseFloat(delivery_charge_amount)
  ).toFixed(ir_precision);

  //set total payable amount to view
  $("#total_payable").html(total_payable);
  if(!Number($("#total_items_in_cart_with_quantity").html())){
      $("#total_payable").html(Number(0).toFixed(ir_precision));
      $("#show_charge_amount").html(Number(0).toFixed(ir_precision));
  }
}
function set_all_items_modifiers_amount() {
  $(".order_holder .single_order").each(function (i, obj) {
    let modifiers_price = parseFloat(0).toFixed(ir_precision);
    let item_id = $(this).attr("id").substr(15);

    let item_quantity = $(this)
      .find("#item_quantity_table_" + item_id)
      .html();

    if ($(this).find("#item_modifiers_price_table_" + item_id).length > 0) {
      let comma_separeted_modifiers_price = $(this)
        .find("#item_modifiers_price_table_" + item_id)
        .html();
      let modifiers_price_array =
        comma_separeted_modifiers_price != ""
          ? comma_separeted_modifiers_price.split(",")
          : "";
      modifiers_price_array.forEach(function (modifier_price) {
        modifiers_price = (
          parseFloat(modifiers_price) + parseFloat(modifier_price)
        ).toFixed(ir_precision);
      });
      let modifiers_price_as_per_item_quantity = (
        parseFloat(item_quantity) * parseFloat(modifiers_price)
      ).toFixed(ir_precision);
      $(this)
        .find(".fifth_column #item_modifiers_price_table_" + item_id)
        .html(modifiers_price_as_per_item_quantity);
    }
  });
}
function set_total_items_discount_for_subtotal() {
  let total_discount = 0;
  $(".single_order .first_portion .fifth_column span").each(function (i, obj) {
    let this_item_discount_amount = parseFloat(
      $(this).parent().parent().find(".item_discount").html()
    ).toFixed(ir_precision);
    total_discount = (
      parseFloat(total_discount) + parseFloat(this_item_discount_amount)
    ).toFixed(ir_precision);
  });
  $("#total_item_discount").html(total_discount);
}
function set_all_hidden_item_discount_amount() {
  $(".single_order .first_portion .fifth_column span").each(function (i, obj) {
    let this_item_original_price = parseFloat(
      $(this).parent().parent().find(".item_price_without_discount").html()
    ).toFixed(ir_precision);
    let item_discount_value = $(this)
      .parent()
      .parent()
      .find(".forth_column input")
      .val();
    item_discount_value = item_discount_value != "" ? item_discount_value : 0;
    let actual_discount_amount = get_particular_item_discount_amount(
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
    let this_item_original_price = parseFloat(
      $(this).parent().parent().find(".item_price_without_discount").html()
    ).toFixed(ir_precision);
    let item_discount_value = $(this)
      .parent()
      .parent()
      .find(".forth_column input")
      .val();
    item_discount_value = item_discount_value != "" ? item_discount_value : 0;
    let actual_discount_amount = get_particular_item_discount_amount(
      item_discount_value,
      this_item_original_price
    );
    let discounted_item_price = (
      parseFloat(this_item_original_price) - parseFloat(actual_discount_amount)
    ).toFixed(ir_precision);
    $(this)
      .parent()
      .parent()
      .find(".fifth_column span")
      .html(discounted_item_price);
  });
}
function get_total_of_all_items_and_modifiers() {
  //get all items total price and add
  let all_item_total_price = 0;
  let all_item_total_vat_amount = 0;
  let this_item_discount = 0;
  $(".single_order .first_portion .fifth_column span").each(function (i, obj) {
    all_item_total_price = (
      parseFloat(all_item_total_price) + parseFloat($(this).html())
    ).toFixed(ir_precision);
  });

  //get all modifiers price and add
  let all_modifiers_total_price = 0;
  $(".single_order .second_portion .fifth_column span").each(function (i, obj) {
    all_modifiers_total_price = (
      parseFloat(all_modifiers_total_price) + parseFloat($(this).html())
    ).toFixed(ir_precision);
  });
  return (
    parseFloat(all_item_total_price) + parseFloat(all_modifiers_total_price)
  ).toFixed(ir_precision);
}
function get_total_vat() {
  let all_item_total_vat_amount = 0;
  let tax_object = {};
  let tax_name = [];
  let customer_id = $("#walk_in_customer").val();
  let customer_gst_number = "";
  for (let m in window.customers) {
    let this_customer = window.customers[m];
    if (this_customer.customer_id == customer_id) {
      customer_gst_number = this_customer.gst_number;
    }
  }
  let customer_state_code =
    customer_gst_number != "" ? customer_gst_number.substr(0, 2) : "";
  let same_state = false;
  if (customer_state_code == gst_state_code) {
    same_state = true;
  }
  if (customer_state_code == "") {
    same_state = true;
  }
  $(".single_order .first_portion .fifth_column span").each(function (i, obj) {
    let this_item_quantity = parseFloat(
      $(this).parent().parent().find(".third_column span").html()
    ).toFixed(ir_precision);
    let this_item_price = parseFloat(
      $(this).parent().parent().find(".second_column span").html()
    ).toFixed(ir_precision);
    let item_total_price = parseFloat(
      $(this).parent().parent().find(".fifth_column span").html()
    ).toFixed(ir_precision);
    let tax_information = JSON.parse(
      $(this).parent().parent().find(".item_vat").html()
    );

    if (tax_information.length > 0) {
      // console.log(tax_information);
      for (let k in tax_information) {
        if (tax_name.includes(tax_information[k].tax_field_name)) {
          if (
            collect_gst == "Yes" &&
            same_state &&
            tax_information[k].tax_field_name != "IGST"
          ) {
            let previous_value =
              tax_object["" + tax_information[k].tax_field_name];
            tax_object["" + tax_information[k].tax_field_name] = (
              parseFloat(previous_value) +
              parseFloat(
                parseFloat(
                  parseFloat(tax_information[k].tax_field_percentage) *
                    parseFloat(item_total_price)
                ) / parseFloat(100)
              )
            ).toFixed(ir_precision);
          } else if (
            collect_gst == "Yes" &&
            !same_state &&
            tax_information[k].tax_field_name != "SGST"
          ) {
            let previous_value =
              tax_object["" + tax_information[k].tax_field_name];
            tax_object["" + tax_information[k].tax_field_name] = (
              parseFloat(previous_value) +
              parseFloat(
                parseFloat(
                  parseFloat(tax_information[k].tax_field_percentage) *
                    parseFloat(item_total_price)
                ) / parseFloat(100)
              )
            ).toFixed(ir_precision);
          }
          if (
            collect_tax == "Yes" &&
            collect_gst != "Yes" &&
            tax_information[k].tax_field_name != "IGST" &&
            tax_information[k].tax_field_name != "CGST" &&
            tax_information[k].tax_field_name != "SGST"
          ) {
            let previous_value =
              tax_object["" + tax_information[k].tax_field_name];
            tax_object["" + tax_information[k].tax_field_name] = (
              parseFloat(previous_value) +
              parseFloat(
                parseFloat(
                  parseFloat(tax_information[k].tax_field_percentage) *
                    parseFloat(item_total_price)
                ) / parseFloat(100)
              )
            ).toFixed(ir_precision);
          }
        } else {
          if (
            collect_gst == "Yes" &&
            same_state &&
            tax_information[k].tax_field_name != "IGST"
          ) {
            tax_name.push(tax_information[k].tax_field_name);
            tax_object["" + tax_information[k].tax_field_name] = (
              parseFloat(
                parseFloat(tax_information[k].tax_field_percentage) *
                  parseFloat(item_total_price)
              ) / parseFloat(100)
            ).toFixed(ir_precision);
          } else if (
            collect_gst == "Yes" &&
            !same_state &&
            tax_information[k].tax_field_name != "SGST"
          ) {
            tax_name.push(tax_information[k].tax_field_name);
            tax_object["" + tax_information[k].tax_field_name] = (
              parseFloat(
                parseFloat(tax_information[k].tax_field_percentage) *
                  parseFloat(item_total_price)
              ) / parseFloat(100)
            ).toFixed(ir_precision);
          }

          if (
            collect_tax == "Yes" &&
            collect_gst != "Yes" &&
            tax_information[k].tax_field_name != "IGST" &&
            tax_information[k].tax_field_name != "CGST" &&
            tax_information[k].tax_field_name != "SGST"
          ) {
            tax_name.push(tax_information[k].tax_field_name);
            tax_object["" + tax_information[k].tax_field_name] = (
              parseFloat(
                parseFloat(tax_information[k].tax_field_percentage) *
                  parseFloat(item_total_price)
              ) / parseFloat(100)
            ).toFixed(ir_precision);
          }
        }
      }
    }
  });
  $(".item_vat_modifier").each(function (i, obj) {
    let item_id_custom = $(this).attr("data-item_id");
    let this_item_modifer_quantity = parseFloat(
      $("#item_quantity_table_" + item_id_custom).html()
    ).toFixed(ir_precision);
    let this_item_modifier_price = parseFloat(
      $(this).attr("data-modifier_price")
    ).toFixed(ir_precision);

    let item_total_price =
      this_item_modifer_quantity * this_item_modifier_price;
    let tax_information = JSON.parse($(this).html());

    if (tax_information.length > 0) {
      // console.log(tax_information);
      for (let k in tax_information) {
        if (tax_name.includes(tax_information[k].tax_field_name)) {
          if (
            collect_gst == "Yes" &&
            same_state &&
            tax_information[k].tax_field_name != "IGST"
          ) {
            let previous_value =
              tax_object["" + tax_information[k].tax_field_name];
            tax_object["" + tax_information[k].tax_field_name] = (
              parseFloat(previous_value) +
              parseFloat(
                parseFloat(
                  parseFloat(tax_information[k].tax_field_percentage) *
                    parseFloat(item_total_price)
                ) / parseFloat(100)
              )
            ).toFixed(ir_precision);
          } else if (
            collect_gst == "Yes" &&
            !same_state &&
            tax_information[k].tax_field_name != "SGST"
          ) {
            let previous_value =
              tax_object["" + tax_information[k].tax_field_name];
            tax_object["" + tax_information[k].tax_field_name] = (
              parseFloat(previous_value) +
              parseFloat(
                parseFloat(
                  parseFloat(tax_information[k].tax_field_percentage) *
                    parseFloat(item_total_price)
                ) / parseFloat(100)
              )
            ).toFixed(ir_precision);
          }
          if (
            collect_tax == "Yes" &&
            collect_gst != "Yes" &&
            tax_information[k].tax_field_name != "IGST" &&
            tax_information[k].tax_field_name != "CGST" &&
            tax_information[k].tax_field_name != "SGST"
          ) {
            let previous_value =
              tax_object["" + tax_information[k].tax_field_name];
            tax_object["" + tax_information[k].tax_field_name] = (
              parseFloat(previous_value) +
              parseFloat(
                parseFloat(
                  parseFloat(tax_information[k].tax_field_percentage) *
                    parseFloat(item_total_price)
                ) / parseFloat(100)
              )
            ).toFixed(ir_precision);
          }
        } else {
          if (
            collect_gst == "Yes" &&
            same_state &&
            tax_information[k].tax_field_name != "IGST"
          ) {
            tax_name.push(tax_information[k].tax_field_name);
            tax_object["" + tax_information[k].tax_field_name] = (
              parseFloat(
                parseFloat(tax_information[k].tax_field_percentage) *
                  parseFloat(item_total_price)
              ) / parseFloat(100)
            ).toFixed(ir_precision);
          } else if (
            collect_gst == "Yes" &&
            !same_state &&
            tax_information[k].tax_field_name != "SGST"
          ) {
            tax_name.push(tax_information[k].tax_field_name);
            tax_object["" + tax_information[k].tax_field_name] = (
              parseFloat(
                parseFloat(tax_information[k].tax_field_percentage) *
                  parseFloat(item_total_price)
              ) / parseFloat(100)
            ).toFixed(ir_precision);
          }

          if (
            collect_tax == "Yes" &&
            collect_gst != "Yes" &&
            tax_information[k].tax_field_name != "IGST" &&
            tax_information[k].tax_field_name != "CGST" &&
            tax_information[k].tax_field_name != "SGST"
          ) {
            tax_name.push(tax_information[k].tax_field_name);
            tax_object["" + tax_information[k].tax_field_name] = (
              parseFloat(
                parseFloat(tax_information[k].tax_field_percentage) *
                  parseFloat(item_total_price)
              ) / parseFloat(100)
            ).toFixed(ir_precision);
          }
        }
      }
    }
  });

  return tax_object;
}
function get_all_item_discount() {
  let all_item_discount = 0;
  $(".single_order .first_portion .fifth_column span").each(function (i, obj) {
    let this_item_quantity = parseFloat(
      $(this).parent().parent().find(".third_column span").html()
    ).toFixed(ir_precision);
    let this_item_price = parseFloat(
      $(this).parent().parent().find(".second_column span").html()
    ).toFixed(ir_precision);
    let total_item_price_this_row = (
      parseFloat(this_item_quantity) * parseFloat(this_item_price)
    ).toFixed(ir_precision);
    let this_item_discount =
      $(this).parent().parent().find(".forth_column input").val() != ""
        ? $(this).parent().parent().find(".forth_column input").val()
        : 0;
    this_item_discount = get_particular_item_discount_amount(
      this_item_discount,
      total_item_price_this_row
    );
    $(this).parent().parent().find(".item_discount").html(this_item_discount);
    // let this_item_discount = (parseFloat(this_item_discount)+parseFloat($(this).parent().parent().find('.item_discount').html())).toFixed(ir_precision);
    all_item_discount = (
      parseFloat(all_item_discount) + parseFloat(this_item_discount)
    ).toFixed(ir_precision);
  });

  return all_item_discount;
}
function get_particular_item_discount_amount(discount, item_price) {
  if (discount.length > 0 && discount.substr(discount.length - 1) == "%") {
    return (
      (parseFloat(item_price) * parseFloat(discount)) /
      parseFloat(100)
    ).toFixed(ir_precision);
  } else {
    return parseFloat(discount).toFixed(ir_precision);
  }
}
function get_updated_sub_total() {
  //get all items total price and add
  let all_item_total_price = 0;
  let all_item_total_vat_amount = 0;
  let this_item_discount = 0;
  $(".single_order .first_portion .fifth_column span").each(function (i, obj) {
    let this_item_quantity = parseFloat(
      $(this).parent().parent().find(".third_column span").html()
    ).toFixed(ir_precision);
    let this_item_price = parseFloat(
      $(this).parent().parent().find(".second_column span").html()
    ).toFixed(ir_precision);
    let this_item_vat_percentage = parseFloat(
      $(this).parent().parent().find(".item_vat").html()
    ).toFixed(ir_precision);
    all_item_total_price = (
      parseFloat(all_item_total_price) +
      parseFloat(this_item_quantity) * parseFloat(this_item_price)
    ).toFixed(ir_precision);
    let this_item_vat_amount = (
      (parseFloat($(this).html()) * parseFloat(this_item_vat_percentage)) /
      parseFloat(100)
    ).toFixed(ir_precision);
    all_item_total_vat_amount = (
      parseFloat(all_item_total_vat_amount) + parseFloat(this_item_vat_amount)
    ).toFixed(ir_precision);

    let this_item_discount = (
      parseFloat(this_item_discount) +
      parseFloat($(this).parent().parent().find(".item_discount").html())
    ).toFixed(ir_precision);
  });

  //get total discount
  let total_discount = $("#all_items_discount");

  //get discount on sub total
  let sub_total_discount =
    $("#sub_total_discount").val() == "" ? $("#sub_total_discount").val() : 0;

  //get sub total
  let sub_total = parseFloat($("#sub_total").html()).toFixed(ir_precision);

  //get sub total discount amount
  let sub_total_discount_amount = get_sub_total_discount_amount(
    $sub_total_discount,
    $sub_total
  );

  //get all modifiers price and add
  let all_modifiers_total_price = 0;
  $(".single_order .second_portion .fifth_column span").each(function (i, obj) {
    all_modifiers_total_price = (
      parseFloat(all_modifiers_total_price) + parseFloat($(this).html())
    ).toFixed(ir_precision);
  });
  //set vat amount under sub total
  $("#all_items_vat").html(all_item_total_vat_amount);

  let total_of_all_items_and_modifiers =
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
    ).toFixed(ir_precision);
  } else {
    return parseFloat(sub_total_discount).toFixed(ir_precision);
  }
}
function reset_on_modal_close_or_add_to_cart() {
  $("#item_modal").removeClass("active");
  $(".pos__modal__overlay").fadeOut(300);
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
  let table_id = object.attr("id").substr(13);
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
  $("#sub_total_show").html(Number(0).toFixed(ir_precision));
  $("#sub_total").html(Number(0).toFixed(ir_precision));
  $("#total_item_discount").html(Number(0).toFixed(ir_precision));
  $("#discounted_sub_total_amount").html(Number(0).toFixed(ir_precision));
  $("#sub_total_discount").val("");
  $("#sub_total_discount1").val("");
  $("#sub_total_discount_amount").html(Number(0).toFixed(ir_precision));
  $("#all_items_vat").html(Number(0).toFixed(ir_precision));
  $("#all_items_discount").html(Number(0).toFixed(ir_precision));
  $("#total_items_in_cart").html("0");
  $("#total_items_in_cart_with_quantity").html("0");
  $("#total_payable").html(Number(0).toFixed(ir_precision));
  $("#show_vat_modal").html(Number(0).toFixed(ir_precision));
  $("#show_discount_amount").html(Number(0).toFixed(ir_precision));
  $("#show_charge_amount").html(Number(0).toFixed(ir_precision));
  $("#tax_row_show").empty();
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
        let sale_id = response;
        $("#last_future_sale_id").val(sale_id);
        $("#open_invoice_date_hidden").val(getCurrentDate());

        $(".datepicker_custom")
          .datepicker({
            autoclose: true,
            format: "yyyy-mm-dd",
            startDate: "0",
            todayHighlight: true,
          })
          .datepicker("update", getCurrentDate());

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
            //open kot
            $("#print_kot").click();
            $("#finalize_order_modal").addClass("active");
            $(".pos__modal__overlay").fadeIn(200);
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
        $("#open_invoice_date_hidden").val(getCurrentDate());

        if (waiter_app_status == "Yes") {
          $("#show_running_order").click();
        }
        $(".datepicker_custom")
          .datepicker({
            autoclose: true,
            format: "yyyy-mm-dd",
            startDate: "0",
            todayHighlight: true,
          })
          .datepicker("update", getCurrentDate());
        set_new_orders_to_view(response);
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
      $("#generate_sale_hold_modal").removeClass("active");
      $(".pos__modal__overlay").fadeOut(300);

      $(".order_holder").empty();
      clearFooterCartCalculation();
      $("#hold_generate_input").val("");
      $("#open_invoice_date_hidden").val(getCurrentDate());

      $(".datepicker_custom")
        .datepicker({
          autoclose: true,
          format: "yyyy-mm-dd",
          startDate: "0",
          todayHighlight: true,
        })
        .datepicker("update", getCurrentDate());

      // $(".main_top").find("button").css("background-color", "#109ec5");
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
function set_new_orders_to_view(sale_id) {
  $.ajax({
    url: base_url + "Sale/get_new_orders_ajax",
    method: "GET",
    success: function (response) {
      response = JSON.parse(response);
      let order_list_left = "";
      let i = 1;
      for (let key in response) {
        let width = 100;
        let total_kitchen_type_items = response[key].total_kitchen_type_items;
        let total_kitchen_type_started_cooking_items =
          response[key].total_kitchen_type_started_cooking_items;
        let total_kitchen_type_done_items =
          response[key].total_kitchen_type_done_items;
        let splitted_width = (
          parseFloat(width) / parseFloat(total_kitchen_type_items)
        ).toFixed(ir_precision);
        let percentage_for_started_cooking = (
          parseFloat(splitted_width) *
          parseFloat(total_kitchen_type_started_cooking_items)
        ).toFixed(ir_precision);
        let percentage_for_done_cooking = (
          parseFloat(splitted_width) * parseFloat(total_kitchen_type_done_items)
        ).toFixed(ir_precision);
        if (i == 1) {
          order_list_left +=
            '<div data-started-cooking="' +
            total_kitchen_type_started_cooking_items +
            '" data-done-cooking="' +
            total_kitchen_type_done_items +
            '" class="single_order fix txt_5 new_order_' +
            response[key].sales_id +
            '" data-selected="unselected" id="order_' +
            response[key].sales_id +
            '">';
        } else {
          order_list_left +=
            '<div data-started-cooking="' +
            total_kitchen_type_started_cooking_items +
            '" data-done-cooking="' +
            total_kitchen_type_done_items +
            '" class="single_order fix new_order_' +
            response[key].sales_id +
            '" data-selected="unselected" id="order_' +
            response[key].sales_id +
            '">';
        }
        order_list_left += '<div class="inside_single_order_container fix">';
        order_list_left +=
          '<div class="single_order_content_holder_inside fix">';
        let order_name = "";
        if (response[key].order_type == "1") {
          order_name = "A " + response[key].sale_no;
        } else if (response[key].order_type == "2") {
          order_name = "B " + response[key].sale_no;
        } else if (response[key].order_type == "3") {
          order_name = "C " + response[key].sale_no;
        }

        //let table_name = (response[key].tables_booked[0].table_name!=null)?response[key].tables_booked[0].table_name:"";
        let table_name = "";
        let waiter_name =
          response[key].waiter_name != null ? response[key].waiter_name : "";
        let customer_name =
          response[key].customer_name != null
            ? response[key].customer_name
            : "";
        let minute = response[key].minute_difference;
        let second = response[key].second_difference;

        let tables_booked = "";
        if (response[key].tables_booked.length > 0) {
          let w = 1;
          for (let k in response[key].tables_booked) {
            let single_table = response[key].tables_booked[k];
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
          '</span> <p><span class="running_order_customer_name">Cust: ' +
          customer_name +
          '</span></p> <i class="far fa-chevron-right running_order_right_arrow" id="running_order_right_arrow_' +
          response[key].sales_id +
          '"></i>';
        order_list_left +=
          '<p>Table: <span class="running_order_table_name">' +
          tables_booked +
          "</span></p>";
        order_list_left +=
          '<p>Waiter: <span class="running_order_waiter_name">' +
          waiter_name +
          "</span></p>";
        order_list_left +=
          '<p class="oder_list_class txt_1">Order: <span class="running_order_order_number">' +
          order_name +
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
          '<p  class="txt_4">Time Count: <span id="order_minute_count_' +
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
      let waiter_app_status = $("#waiter_app_status").val();
      if (waiter_app_status == "No") {
        $(".main_top").find("button").attr("data-selected", "unselected");
      }
      $("#table_button").attr("disabled", false);
      $(".single_table_div[data-table-checked=checked]").attr(
        "data-table-checked",
        "unchecked"
      );
      reset_customer_waiter_to_default();
      reset_time_interval();
      all_time_interval_operation();

      reset_table_modal();
      createAnimation(sale_id);
    },
    error: function () {
      alert(a_error);
    },
  });
}
function set_new_orders_to_view_future_add(sale_id) {
  $.ajax({
    url: base_url + "Sale/get_new_orders_ajax",
    method: "GET",
    success: function (response) {
      response = JSON.parse(response);
      let order_list_left = "";
      let i = 1;
      for (let key in response) {
        let width = 100;
        let total_kitchen_type_items = response[key].total_kitchen_type_items;
        let total_kitchen_type_started_cooking_items =
          response[key].total_kitchen_type_started_cooking_items;
        let total_kitchen_type_done_items =
          response[key].total_kitchen_type_done_items;
        let splitted_width = (
          parseFloat(width) / parseFloat(total_kitchen_type_items)
        ).toFixed(ir_precision);
        let percentage_for_started_cooking = (
          parseFloat(splitted_width) *
          parseFloat(total_kitchen_type_started_cooking_items)
        ).toFixed(ir_precision);
        let percentage_for_done_cooking = (
          parseFloat(splitted_width) * parseFloat(total_kitchen_type_done_items)
        ).toFixed(ir_precision);
        if (i == 1) {
          order_list_left +=
            '<div data-started-cooking="' +
            total_kitchen_type_started_cooking_items +
            '" data-done-cooking="' +
            total_kitchen_type_done_items +
            '" class="single_order fix txt_5 new_order_' +
            response[key].sales_id +
            '" data-selected="unselected" id="order_' +
            response[key].sales_id +
            '">';
        } else {
          order_list_left +=
            '<div data-started-cooking="' +
            total_kitchen_type_started_cooking_items +
            '" data-done-cooking="' +
            total_kitchen_type_done_items +
            '" class="single_order fix new_order_' +
            response[key].sales_id +
            '" data-selected="unselected" id="order_' +
            response[key].sales_id +
            '">';
        }
        order_list_left += '<div class="inside_single_order_container fix">';
        order_list_left +=
          '<div class="single_order_content_holder_inside fix">';
        let order_name = "";
        if (response[key].order_type == "1") {
          order_name = "A " + response[key].sale_no;
        } else if (response[key].order_type == "2") {
          order_name = "B " + response[key].sale_no;
        } else if (response[key].order_type == "3") {
          order_name = "C " + response[key].sale_no;
        }

        //let table_name = (response[key].tables_booked[0].table_name!=null)?response[key].tables_booked[0].table_name:"";
        let table_name = "";
        let waiter_name =
          response[key].waiter_name != null ? response[key].waiter_name : "";
        let customer_name =
          response[key].customer_name != null
            ? response[key].customer_name
            : "";
        let minute = response[key].minute_difference;
        let second = response[key].second_difference;

        let tables_booked = "";
        if (response[key].tables_booked.length > 0) {
          let w = 1;
          for (let k in response[key].tables_booked) {
            let single_table = response[key].tables_booked[k];
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
          '</span> <p><span class="running_order_customer_name">Cust: ' +
          customer_name +
          '</span></p> <i class="far fa-chevron-right running_order_right_arrow" id="running_order_right_arrow_' +
          response[key].sales_id +
          '"></i>';
        order_list_left +=
          '<p>Table: <span class="running_order_table_name">' +
          tables_booked +
          "</span></p>";
        order_list_left +=
          '<p>Waiter: <span class="running_order_waiter_name">' +
          waiter_name +
          "</span></p>";
        order_list_left +=
          '<p class="oder_list_class txt_1">Order: <span class="running_order_order_number">' +
          order_name +
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
          '<p  class="txt_4">Time Count: <span id="order_minute_count_' +
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
      // $(".main_top").find("button").css("background-color", "#109ec5");
      $(".main_top").find("button").attr("data-selected", "unselected");
      $("#table_button").attr("disabled", false);
      $(".single_table_div[data-table-checked=checked]").attr(
        "data-table-checked",
        "unchecked"
      );
      reset_customer_waiter_to_default();
      //reset_time_interval();
      ///all_time_interval_operation();

      reset_table_modal();
      createAnimation(sale_id);
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
      let order_list_left = "";
      let i = 1;
      let last_key = response.length - 1;
      for (let key in response) {
        let width = 100;
        let total_kitchen_type_items = response[key].total_kitchen_type_items;
        let total_kitchen_type_started_cooking_items =
          response[key].total_kitchen_type_started_cooking_items;
        let total_kitchen_type_done_items =
          response[key].total_kitchen_type_done_items;
        let splitted_width = (
          parseFloat(width) / parseFloat(total_kitchen_type_items)
        ).toFixed(ir_precision);
        let percentage_for_started_cooking = (
          parseFloat(splitted_width) *
          parseFloat(total_kitchen_type_started_cooking_items)
        ).toFixed(ir_precision);
        let percentage_for_done_cooking = (
          parseFloat(splitted_width) * parseFloat(total_kitchen_type_done_items)
        ).toFixed(ir_precision);
        if (i == 1) {
          if (last_key == key) {
            order_list_left +=
              '<div data-started-cooking="' +
              total_kitchen_type_started_cooking_items +
              '" data-done-cooking="' +
              total_kitchen_type_done_items +
              '" class="single_order fix txt_6 new_order_' +
              response[key].sales_id +
              '"  data-selected="selected" id="order_' +
              response[key].sales_id +
              '">';
          } else {
            order_list_left +=
              '<div data-started-cooking="' +
              total_kitchen_type_started_cooking_items +
              '" data-done-cooking="' +
              total_kitchen_type_done_items +
              '" class="single_order fix txt_5 new_order_' +
              response[key].sales_id +
              '"  data-selected="unselected" id="order_' +
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
              '" class="single_order fix txt_7 new_order_' +
              response[key].sales_id +
              '"  data-selected="selected" id="order_' +
              response[key].sales_id +
              '">';
          } else {
            order_list_left +=
              '<div data-started-cooking="' +
              total_kitchen_type_started_cooking_items +
              '" data-done-cooking="' +
              total_kitchen_type_done_items +
              '" class="single_order fix new_order_' +
              response[key].sales_id +
              '" data-selected="unselected" id="order_' +
              response[key].sales_id +
              '">';
          }
        }
        order_list_left += '<div class="inside_single_order_container fix">';
        order_list_left +=
          '<div class="single_order_content_holder_inside fix">';
        let order_name = "";
        if (response[key].order_type == "1") {
          order_name = "A " + response[key].sale_no;
        } else if (response[key].order_type == "2") {
          order_name = "B " + response[key].sale_no;
        } else if (response[key].order_type == "3") {
          order_name = "C " + response[key].sale_no;
        }

        let table_name = "";
        let waiter_name =
          response[key].waiter_name != null ? response[key].waiter_name : "";
        let customer_name =
          response[key].customer_name != null
            ? response[key].customer_name
            : "";

        let minute = response[key].minute_difference;
        let second = response[key].second_difference;

        let tables_booked = "";
        if (response[key].tables_booked.length > 0) {
          let w = 1;
          for (let k in response[key].tables_booked) {
            let single_table = response[key].tables_booked[k];
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
          '</span><p><span class="running_order_customer_name">Cust: ' +
          customer_name +
          '</span></p> <i class="far fa-chevron-right running_order_right_arrow" id="running_order_right_arrow_' +
          response[key].sales_id +
          '"></i>';
        order_list_left +=
          '<p>Table: <span class="running_order_table_name">' +
          tables_booked +
          "</span></p>";
        order_list_left +=
          '<p>Waiter: <span class="running_order_waiter_name">' +
          waiter_name +
          "</span></p>";

        order_list_left +=
          '<p class="oder_list_class txt_1">Order: <span class="running_order_order_number">' +
          order_name +
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
          '<p  class="txt_4">Time Count: <span id="order_minute_count_' +
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
      // $(".main_top").find("button").css("background-color", "#109ec5");
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
      let order_list_left = "";
      let i = 1;
      for (let key in response) {
        let width = 100;
        let total_kitchen_type_items = response[key].total_kitchen_type_items;
        let total_kitchen_type_started_cooking_items =
          response[key].total_kitchen_type_started_cooking_items;
        let total_kitchen_type_done_items =
          response[key].total_kitchen_type_done_items;
        let splitted_width = (
          parseFloat(width) / parseFloat(total_kitchen_type_items)
        ).toFixed(ir_precision);
        let percentage_for_started_cooking = (
          parseFloat(splitted_width) *
          parseFloat(total_kitchen_type_started_cooking_items)
        ).toFixed(ir_precision);
        let percentage_for_done_cooking = (
          parseFloat(splitted_width) * parseFloat(total_kitchen_type_done_items)
        ).toFixed(ir_precision);
        if (i == 1) {
          order_list_left +=
            '<div data-started-cooking="' +
            total_kitchen_type_started_cooking_items +
            '" data-done-cooking="' +
            total_kitchen_type_done_items +
            '" class="single_order txt_5 new_order_' +
            response[key].sales_id +
            '" data-selected="unselected" id="order_' +
            response[key].sales_id +
            '">';
        } else {
          order_list_left +=
            '<div data-started-cooking="' +
            total_kitchen_type_started_cooking_items +
            '" data-done-cooking="' +
            total_kitchen_type_done_items +
            '" class="single_order new_order_' +
            response[key].sales_id +
            '" data-selected="unselected" id="order_' +
            response[key].sales_id +
            '">';
        }
        order_list_left += '<div class="inside_single_order_container fix">';
        order_list_left +=
          '<div class="single_order_content_holder_inside fix">';
        let order_name = "";
        if (response[key].order_type == "1") {
          order_name = "A " + response[key].sale_no;
        } else if (response[key].order_type == "2") {
          order_name = "B " + response[key].sale_no;
        } else if (response[key].order_type == "3") {
          order_name = "C " + response[key].sale_no;
        }
        let table_name =
          response[key].table_name != null ? response[key].table_name : "";
        let waiter_name =
          response[key].waiter_name != null ? response[key].waiter_name : "";
        let customer_name =
          response[key].customer_name != null
            ? response[key].customer_name
            : "";
        let minute = response[key].minute_difference;
        let second = response[key].second_difference;

        let tables_booked = "";
        if (response[key].tables_booked.length > 0) {
          let w = 1;
          for (let k in response[key].tables_booked) {
            let single_table = response[key].tables_booked[k];
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
          '<p><span class="running_order_customer_name">Cust: ' +
          customer_name +
          "</span></p>";
        order_list_left +=
          '<p>Table: <span class="running_order_table_name">' +
          tables_booked +
          "</span></p>";
        order_list_left +=
          '<p>Waiter: <span class="running_order_waiter_name">' +
          waiter_name +
          "</span></p>";
        order_list_left +=
          '<span id="open_orders_order_status_' +
          response[key].sales_id +
          '" class="ir_display_none">' +
          response[key].order_status +
          '</span><p   class="oder_list_class txt_8" >Order: <span class="running_order_order_number">' +
          order_name +
          '</span></p><i class="far fa-chevron-right running_order_right_arrow " id="running_order_right_arrow_' +
          response[key].sales_id +
          '"></i>';
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
          '<p class="txt_4">Time Count: <span id="order_minute_count_' +
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
  let cid = $("#default_customer_hidden").val();
  let wid = $("#default_waiter_hidden").val();

  $("#walk_in_customer").val(cid).trigger("change");
  if (waiter_app_status != "Yes") {
    if (wid) {
      $("#select_waiter").val(wid).trigger("change");
    } else {
      $("#select_waiter").val("").trigger("change");
    }
  }
  $("#place_edit_order").html(place_order);
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
      let orders = JSON.parse(response);

      let held_orders = "";
      for (let key in orders) {
        let tables_booked = "";
        if (orders[key].tables_booked.length > 0) {
          let w = 1;
          for (let k in orders[key].tables_booked) {
            let single_table = orders[key].tables_booked[k];
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
        let phone_text_ = "";
        if (orders[key].phone) {
          phone_text_ = " (" + orders[key].phone + ")";
        }

        let customer_name =
          orders[key].customer_name == null || orders[key].customer_name == ""
            ? "&nbsp;"
            : orders[key].customer_name;
        let table_name = tables_booked;
        held_orders +=
          '<div class="single_hold_sale fix" id="hold_' +
          orders[key].id +
          '" data-selected="unselected">';
        held_orders +=
          '<div class="first_column column fix">' +
          orders[key].hold_no +
          "</div>";
        held_orders +=
          '<div class="second_column column fix">' +
          customer_name +
          phone_text_ +
          "</div>";
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
  let given_amount_input = $("#given_amount_input").val();
  let change_amount_input = $("#change_amount_input").val();
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
        set_new_orders_to_view(sale_id);
      }
    },
    error: function () {
      alert(a_error);
    },
  });
}

function close_order(sale_id, payment_method_type, paid_amount, due_amount) {
  let given_amount_input = $("#given_amount_input").val();
  let change_amount_input = $("#change_amount_input").val();
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
        set_new_orders_to_view(sale_id);
      }
    },
    error: function () {
      alert(a_error);
    },
  });
}
function print_invoice(sale_id) {
  let newWindow = "";
  let print_type = $("#print_type").val();
  let print_type_invoice = $(".print_type_invoice").val();
  if (Number(print_type) == 1) {
    if (print_type_invoice == "web_browser") {
      newWindow = open(
        base_url + "Sale/print_invoice/" + Number(sale_id),
        "Print Invoice",
        "width=450,height=550"
      );
    } else {
      $("#finalize_order_modal").removeClass("active");
      $(".pos__modal__overlay").fadeOut(300);
      $.ajax({
        url: base_url + "Authentication/printSaleByAjax",
        method: "post",
        dataType: "json",
        data: {
          sale_id: sale_id,
        },
        success: function (data) {
          if (data.printer_server_url) {
            $.ajax({
              url:
                data.printer_server_url +
                "print_server/irestora_printer_server.php",
              method: "post",
              dataType: "json",
              data: {
                content_data: JSON.stringify(data.content_data),
              },
              success: function (data) {},
              error: function () {},
            });
          }
        },
        error: function () {},
      });
    }
  } else {
    let print_type_bill = $(".print_type_bill").val();
    if (print_type_bill == "web_browser") {
      newWindow = open(
        base_url + "Sale/print_bill/" + sale_id,
        "Print Bill",
        "width=450,height=550"
      );
    } else {
      $("#finalize_order_modal").removeClass("active");
      $(".pos__modal__overlay").fadeOut(300);
      $.ajax({
        url: base_url + "Authentication/printSaleBillByAjax",
        method: "post",
        dataType: "json",
        data: {
          sale_id: sale_id,
        },
        success: function (data) {
          if (data.printer_server_url) {
            $.ajax({
              url:
                data.printer_server_url +
                "print_server/irestora_printer_server.php",
              method: "post",
              dataType: "json",
              data: {
                content_data: JSON.stringify(data.content_data),
              },
              success: function (data) {},
              error: function () {},
            });
          }
        },
        error: function () {},
      });
    }
  }

  newWindow.focus();

  newWindow.onload = function () {
    newWindow.document.body.insertAdjacentHTML("afterbegin");
  };
}
function get_details_of_a_particular_hold(hold_id, this_action) {
  $.ajax({
    url: base_url + "Sale/get_single_hold_info_by_ajax",
    method: "POST",
    data: {
      hold_id: hold_id,
      csrf_irestoraplus: csrf_value_,
    },
    success: function (response) {
      response = JSON.parse(response);
      $("#open_invoice_date_hidden").val(response.sale_date);
      $(".datepicker_custom")
        .datepicker({
          autoclose: true,
          format: "yyyy-mm-dd",
          startDate: "0",
          todayHighlight: true,
        })
        .datepicker("update", response.sale_date);

      hold_id = response.id;
      let draw_table_for_order = "";

      for (let key in response.items) {
        //construct div
        let this_item = response.items[key];

        let selected_modifiers = "";
        let selected_modifiers_id = "";
        let selected_modifiers_price = "";
        let modifiers_price = 0;
        let total_modifier = this_item.modifiers.length;
        let i = 1;
        let item_id = this_item.food_menu_id;
        let draw_table_for_order_tmp_modifier_tax = "";
        for (let mod_key in this_item.modifiers) {
          let this_modifier = this_item.modifiers[mod_key];
          let modifier_id_custom = this_modifier.modifier_id;
          //get selected modifiers
          if (i == total_modifier) {
            selected_modifiers += this_modifier.name;
            selected_modifiers_id += this_modifier.modifier_id;
            selected_modifiers_price += this_modifier.modifier_price;
            modifiers_price = (
              parseFloat(modifiers_price) +
              parseFloat(this_modifier.modifier_price)
            ).toFixed(ir_precision);
          } else {
            selected_modifiers += this_modifier.name + ", ";
            selected_modifiers_id += this_modifier.modifier_id + ",";
            selected_modifiers_price += this_modifier.modifier_price + ",";
            modifiers_price = (
              parseFloat(modifiers_price) +
              parseFloat(this_modifier.modifier_price)
            ).toFixed(ir_precision);
          }
          let tax_information = "";
          // iterate over each item in the array
          tax_information = this_modifier.menu_taxes;
          tax_information = IsJsonString(tax_information)
            ? JSON.parse(tax_information)
            : "";
          draw_table_for_order_tmp_modifier_tax +=
            '<span class="item_vat_modifier ir_display_none item_vat_modifier_' +
            item_id +
            '" data-item_id="' +
            item_id +
            '"  data-modifier_price="' +
            this_modifier.modifier_price +
            '" data-modifier_id="' +
            modifier_id_custom +
            '" id="item_vat_percentage_table' +
            item_id +
            "" +
            modifier_id_custom +
            '">' +
            JSON.stringify(tax_information) +
            "</span>";
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
          '<div class="single_order_column first_column fix"><i class="fas fa-pencil-alt edit_item txt_5" id="edit_item_' +
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
          '<div class="single_order_column third_column fix"><i class="fal fa-minus decrease_item_table txt_5" id="decrease_item_table_' +
          this_item.food_menu_id +
          '"></i> <span class="qty_item_custom" id="item_quantity_table_' +
          this_item.food_menu_id +
          '">' +
          this_item.qty +
          '</span> <i  class="fal fa-plus increase_item_table txt_5" id="increase_item_table_' +
          this_item.food_menu_id +
          '"></i></div>';
        draw_table_for_order +=
          '<div class="single_order_column forth_column fix"><input type="" name="" placeholder="Amt or %" class="discount_cart_input" id="percentage_table_' +
          this_item.food_menu_id +
          '" value="' +
          this_item.menu_discount_value +
          '" disabled></div>';
        draw_table_for_order +=
          '<div class="single_order_column fifth_column">' +
          currency +
          ' <span id="item_total_price_table_' +
          this_item.food_menu_id +
          '">' +
          this_item.menu_price_with_discount +
          "</span><i data-id='" +
          this_item.food_menu_id +
          "' class='fal fa-times removeCartItem'></i></div>";
        draw_table_for_order += "</div>";
        if (selected_modifiers != "") {
          draw_table_for_order += '<div class="second_portion fix">';
          draw_table_for_order += draw_table_for_order_tmp_modifier_tax;
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
            '<div class="single_order_column first_column fix"><span class="modifier_txt_custom" id="item_modifiers_table_' +
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
            '<div class="single_order_column first_column fix modifier_txt_custom">' +
            note_txt +
            ': <span id="item_note_table_' +
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
      let html_modal = "";
      $.each(JSON.parse(response.sale_vat_objects), function (key, value) {
        html_modal +=
          "<tr class='tax_field' data-tax_field_id='" +
          value.tax_field_id +
          "'  data-tax_field_type='" +
          value.tax_field_type +
          "'  data-tax_field_amount='" +
          value.tax_field_amount +
          "'>\n" +
          "                            <td>" +
          value.tax_field_type +
          "</td>\n" +
          "                            <td>" +
          Number(value.tax_field_amount).toFixed(ir_precision) +
          "</td>\n" +
          "                        </tr>";
      });
      $("#tax_row_show").html(html_modal);

      $.each(JSON.parse(response.tables_booked), function (key_t, value_t) {
        let table_book_row = "";
        table_book_row +=
          '<div class="single_row fix new_book_to_table" id="new_order_table_' +
          value_t.table_id +
          '">';
        table_book_row +=
          '<div class="floatleft fix column first_column">New</div>';
        table_book_row +=
          '<div class="floatleft fix column second_column">-</div>';
        table_book_row +=
          '<div class="floatleft fix column third_column person_tbl_' +
          value_t.table_id +
          '">' +
          value_t.persons +
          "</div>";
        table_book_row +=
          '<div class="floatleft fix column forth_column"><i class="fas fa-trash-alt remove_new_order_row_icon" id="single_row_table_delete_' +
          value_t.table_id +
          '"></i></div>';
        table_book_row += "</div>";
        $("#single_table_order_details_top_" + value_t.table_id).append(
          $(table_book_row)
        );
      });

      $("#sub_total_discount").val(response.sub_total_discount_value);
      $("#sub_total_discount_amount").html(response.sub_total_with_discount);
      $("#all_items_vat").html(response.vat);
      $("#show_vat_modal").html(response.vat);
      $("#all_items_discount").html(response.total_discount_amount);
        if(Number(response.delivery_charge_actual_charge)){
            $("#delivery_charge").val(response.delivery_charge);
        }else{
            $("#delivery_charge").val((0).toFixed(ir_precision));
        }

      $("#total_payable").html(response.total_payable);
      $("#charge_type").val(response.charge_type).change();
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
      $("#sub_total_show_hold").html(Number(0).toFixed(ir_precision));
      $("#sub_total_hold").html(Number(0).toFixed(ir_precision));
      $("#total_item_discount_hold").html(Number(0).toFixed(ir_precision));
      $("#discounted_sub_total_amount_hold").html(
        Number(0).toFixed(ir_precision)
      );
      $("#sub_total_discount_hold").html("");
      $("#sub_total_discount_amount_hold").html(
        Number(0).toFixed(ir_precision)
      );
      $("#all_items_vat_hold").html(Number(0).toFixed(ir_precision));
      $("#all_items_discount_hold").html(Number(0).toFixed(ir_precision));
      $("#delivery_charge_hold").html(Number(0).toFixed(ir_precision));
      $("#total_payable_hold").html(Number(0).toFixed(ir_precision));
      this_action
        .parent()
        .parent()
        .parent()
        .parent()
        .parent()
        .parent()
        .parent()
        .parent()
        .removeClass("active")
        .addClass("inActive");
      setTimeout(function () {
        $(".modal").removeClass("inActive");
      }, 1000);
      $(".pos__modal__overlay").fadeOut(300);

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
        if (waiter_app_status != "Yes") {
          $("#select_waiter").val("").trigger("change");
        }
      } else {
        if (response.waiter_id) {
          if (waiter_app_status != "Yes") {
            $("#select_waiter").val(response.waiter_id).trigger("change");
          }
        } else {
          if (waiter_app_status != "Yes") {
            $("#select_waiter").val("").trigger("change");
          }
        }
      }
      if (response.order_type == "1") {
        // $(".main_top").find("button").css("background-color", "#109ec5");
        $(".main_top").find("button").attr("data-selected", "unselected");

        // $("#dine_in_button").css("background-color", "#B5D6F6");
        $("#dine_in_button").attr("data-selected", "selected");

        $("#table_button").attr("disabled", false);
      } else if (response.order_type == "2") {
        // $(".main_top").find("button").css("background-color", "#109ec5");
        $(".main_top").find("button").attr("data-selected", "unselected");

        // $("#take_away_button").css("background-color", "#B5D6F6");
        $("#take_away_button").attr("data-selected", "selected");

        $("#table_button").attr("disabled", false);
      } else if (response.order_type == "3") {
        // $(".main_top").find("button").css("background-color", "#109ec5");
        $(".main_top").find("button").attr("data-selected", "unselected");

        // $("#delivery_button").css("background-color", "#B5D6F6");
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
        // $(".main_top").find("button").css("background-color", "#109ec5");
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
      let order_type = "";
      let order_type_id = 0;
      let order_number = "";
      let tables_booked = "";
      if (response.tables_booked.length > 0) {
        let w = 1;
        for (let k in response.tables_booked) {
          let single_table = response.tables_booked[k];
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
      $("#open_invoice_date_hidden").val(response.sale_date);
      $(".datepicker_custom")
        .datepicker({
          autoclose: true,
          format: "yyyy-mm-dd",
          startDate: "0",
          todayHighlight: true,
        })
        .datepicker("update", response.sale_date);

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
      let draw_table_for_order_details = "";

      for (let key in response.items) {
        //construct div
        let this_item = response.items[key];

        let selected_modifiers = "";
        let selected_modifiers_id = "";
        let selected_modifiers_price = "";
        let modifiers_price = 0;
        let total_modifier = this_item.modifiers.length;
        let i = 1;
        let item_id = this_item.food_menu_id;
        let draw_table_for_order_tmp_modifier_tax = "";
        for (let mod_key in this_item.modifiers) {
          let this_modifier = this_item.modifiers[mod_key];
          let modifier_id_custom = this_modifier.modifier_id;
          //get selected modifiers
          if (i == total_modifier) {
            selected_modifiers += this_modifier.name;
            selected_modifiers_id += this_modifier.modifier_id;
            selected_modifiers_price += this_modifier.modifier_price;
            modifiers_price = (
              parseFloat(modifiers_price) +
              parseFloat(this_modifier.modifier_price)
            ).toFixed(ir_precision);
          } else {
            selected_modifiers += this_modifier.name + ", ";
            selected_modifiers_id += this_modifier.modifier_id + ",";
            selected_modifiers_price += this_modifier.modifier_price + ",";
            modifiers_price = (
              parseFloat(modifiers_price) +
              parseFloat(this_modifier.modifier_price)
            ).toFixed(ir_precision);
          }
          let tax_information = "";
          // iterate over each item in the array
          tax_information = this_modifier.menu_taxes;
          tax_information = IsJsonString(tax_information)
            ? JSON.parse(tax_information)
            : "";
          draw_table_for_order_tmp_modifier_tax +=
            '<span class="item_vat_modifier ir_display_none item_vat_modifier_' +
            item_id +
            '" data-item_id="' +
            item_id +
            '"  data-modifier_price="' +
            this_modifier.modifier_price +
            '" data-modifier_id="' +
            modifier_id_custom +
            '" id="item_vat_percentage_table' +
            item_id +
            "" +
            modifier_id_custom +
            '">' +
            JSON.stringify(tax_information) +
            "</span>";
          i++;
        }
        let discount_value =
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
          '<div class="single_order_column_hold third_column column fix"><span class="qty_item_custom" id="order_details_item_quantity_table_' +
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
            '<div class="single_order_column_hold first_column column fix"><span class="modifier_txt_custom" id="order_details_item_modifiers_table_' +
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
            '<div class="single_order_column_hold first_column column fix modifier_txt_custom">' +
            note_txt +
            ': <span id="order_details_item_note_table_' +
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
      let total_items_in_cart_with_quantity = 0;
      $(
        ".modifier_item_details_holder .single_item_modifier .first_portion .third_column span"
      ).each(function (i, obj) {
        total_items_in_cart_with_quantity =
          parseInt(total_items_in_cart_with_quantity) +
          parseInt($(this).html());
      });
      let sub_total_discount_order_details =
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
      $("#sub_total_discount_order_details").html(Number(sub_total_discount_order_details).toFixed(ir_precision));
      $("#sub_total_discount_amount_order_details").html(
        response.sub_total_with_discount
      );
      let total_vat_section_to_show = "";
      $.each(JSON.parse(response.sale_vat_objects), function (key, value) {
        total_vat_section_to_show +=
          ' <span class="tax_field_order_details" id="tax_field_order_details_' +
          value.tax_field_id +
          '">' +
          value.tax_field_type +
          ": " +
          currency +
          ' <span class="tax_field_amount_order_details" id="tax_field_amount_order_details_' +
          value.tax_field_id +
          '">' +
          Number(value.tax_field_amount).toFixed(ir_precision) +
          "</span></span>";
      });
      $("#all_items_vat_order_details").html(total_vat_section_to_show);
      $("#all_items_discount_order_details").html(
        response.total_discount_amount
      );
        if(Number(response.delivery_charge_actual_charge)){
            $("#delivery_charge_order_details").html((response.delivery_charge));
        }else{
            $("#delivery_charge_order_details").html((0).toFixed(ir_precision));
        }

      $("#total_payable_order_details").html(Number(response.total_payable).toFixed(ir_precision));

      $("#order_detail_modal").addClass("active");
      $(".pos__modal__overlay").fadeIn(200);
      //do calculation on table
    },
    error: function () {
      alert(a_error);
    },
  });
}
function get_details_of_a_particular_order(sale_id) {
  $("#place_edit_order").html(update_order);
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
    let percentage = false;
    let number_without_percentage = value;
    if (value.indexOf("%") > 0) {
      percentage = true;
      number_without_percentage = value
        .toString()
        .substring(0, value.length - 1);
    }
    let number = number_without_percentage.split(".");
    if (number[1].length > 2) {
      let value = parseFloat(Math.floor(number_without_percentage * 100) / 100);
      let add_percentage = percentage ? "%" : "";
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
    let percentage = false;
    let number_without_percentage = value;
    if (value.indexOf("%") > 0) {
      percentage = true;
      number_without_percentage = value
        .toString()
        .substring(0, value.length - 1);
    }
    let number = number_without_percentage.split(".");
    if (number[1].length > 2) {
      let value = parseFloat(Math.floor(number_without_percentage * 100) / 100);
      let add_percentage = percentage ? "%" : "";
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
          let order_list_left = "";
          let i = 1;
          for (let key in response) {
            let width = 100;
            let total_kitchen_type_items =
              response[key].total_kitchen_type_items;
            let total_kitchen_type_started_cooking_items =
              response[key].total_kitchen_type_started_cooking_items;
            let total_kitchen_type_done_items =
              response[key].total_kitchen_type_done_items;
            let splitted_width = (
              parseFloat(width) / parseFloat(total_kitchen_type_items)
            ).toFixed(ir_precision);
            let percentage_for_started_cooking = (
              parseFloat(splitted_width) *
              parseFloat(total_kitchen_type_started_cooking_items)
            ).toFixed(ir_precision);
            let percentage_for_done_cooking = (
              parseFloat(splitted_width) *
              parseFloat(total_kitchen_type_done_items)
            ).toFixed(ir_precision);

            if (i == 1) {
              order_list_left +=
                '<div data-started-cooking="' +
                total_kitchen_type_started_cooking_items +
                '" data-done-cooking="' +
                total_kitchen_type_done_items +
                '" class="single_order fix txt_5 new_order_' +
                response[key].sales_id +
                '"   data-selected="unselected" id="order_' +
                response[key].sales_id +
                '">';
            } else {
              order_list_left +=
                '<div data-started-cooking="' +
                total_kitchen_type_started_cooking_items +
                '" data-done-cooking="' +
                total_kitchen_type_done_items +
                '" class="single_order fix new_order_' +
                response[key].sales_id +
                '" data-selected="unselected" id="order_' +
                response[key].sales_id +
                '">';
            }
            order_list_left +=
              '<div class="inside_single_order_container fix">';
            order_list_left +=
              '<div class="single_order_content_holder_inside fix">';
            let order_name = "";
            if (response[key].order_type == "1") {
              order_name = "A " + response[key].sale_no;
            } else if (response[key].order_type == "2") {
              order_name = "B " + response[key].sale_no;
            } else if (response[key].order_type == "3") {
              order_name = "C " + response[key].sale_no;
            }
            let minute = response[key].minute_difference;
            let second = response[key].second_difference;

            let tables_booked = "";
            if (response[key].tables_booked.length > 0) {
              let w = 1;
              for (let k in response[key].tables_booked) {
                let single_table = response[key].tables_booked[k];
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

            let table_name =
              response[key].table_name != null ? response[key].table_name : "";
            let waiter_name =
              response[key].waiter_name != null
                ? response[key].waiter_name
                : "";
            let customer_name =
              response[key].customer_name != null
                ? response[key].customer_name
                : "";
            order_list_left +=
              '<p><span class="running_order_customer_name">Cust: ' +
              customer_name +
              "</span></p>";
            order_list_left +=
              '<p>Table: <span class="running_order_table_name">' +
              tables_booked +
              "</span></p>";
            order_list_left +=
              '<p>Waiter: <span class="running_order_waiter_name">' +
              waiter_name +
              "</span></p>";
            order_list_left +=
              '<span id="open_orders_order_status_' +
              response[key].sales_id +
              '" class="ir_display_none">' +
              response[key].order_status +
              '</span><p  class="oder_list_class txt_3">Order: <span class="running_order_order_number">' +
              order_name +
              '</span></p><i class="far fa-chevron-right running_order_right_arrow" id="running_order_right_arrow_' +
              response[key].sales_id +
              '"></i>';

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
              '<p  class="txt_4">Time Count: <span id="order_minute_count_' +
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
          // $(".main_top").find("button").css("background-color", "#109ec5");
          $(".main_top").find("button").attr("data-selected", "unselected");
          $(".single_table_div[data-table-checked=checked]").attr(
            "data-table-checked",
            "unchecked"
          );
          if (waiter_app_status != "Yes") {
            $("#select_waiter").val("");
          }

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
  $("#sub_total_show_last_10").html(Number(0).toFixed(ir_precision));
  $("#sub_total_last_10").html(Number(0).toFixed(ir_precision));
  $("#total_item_discount_last_10").html(Number(0).toFixed(ir_precision));
  $("#discounted_sub_total_amount_last_10").html(
    Number(0).toFixed(ir_precision)
  );
  $("#sub_total_discount_last_10").html(Number(0).toFixed(ir_precision));
  $("#sub_total_discount_amount_last_10").html(Number(0).toFixed(ir_precision));
  $("#all_items_vat_last_10").html(Number(0).toFixed(ir_precision));
  $("#all_items_discount_last_10").html(Number(0).toFixed(ir_precision));
  $("#delivery_charge_last_10").html(Number(0).toFixed(ir_precision));
  $("#total_payable_last_10").html(Number(0).toFixed(ir_precision));
}
function reset_finalize_modal() {
  $("#finalize_total_payable").html(Number(0).toFixed(ir_precision));
  $("#given_amount_input").val("");
  $("#change_amount_input").val("");
  $("#finalize_update_type").html("");
  $("#pay_amount_invoice_input").val("");
  $("#due_amount_invoice_input").val("");
  $("#finalie_order_payment_method").css("border", "1px solid #B5D6F6");
  let default_payment_hidden = $("#default_payment_hidden").val();
  $("#finalie_order_payment_method").val(default_payment_hidden).change();

  $("#finalize_order_modal").removeClass("active");
  $(".pos__modal__overlay").fadeOut(300);

  $("#order_detail_modal").removeClass("active");
  $(".pos__modal__overlay").fadeOut(300);
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
      let notification_counter_update = response.length;
      let notification_counter_previous = $("#notification_counter").html();
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

      // let i = 1;
      let notifications_list = "";
      for (let key in response) {
        let this_notification = response[key];
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
  for (let i = 1; i < 99999; i++) window.clearInterval(i);
}
function order_time_interval() {
  setInterval(function () {
    $("#order_details_holder .single_order").each(function (i, obj) {
      let order_id = $(this).attr("id").substr(6);
      let minutes = $("#order_minute_count_" + order_id).html();
      let seconds = $("#order_second_count_" + order_id).html();
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
  let order_id = object.attr("id").substr(6);
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
  let calculator_button_top = $("#calculator_button").offset().top;
  let calculator_button_left = $("#calculator_button").offset().left;
  let calculator_button_height = $("#calculator_button").height();
  let calculator_button_width = $("#calculator_button").width();
  let calculator_width = $("#calculator_main").width();
  let left_for_calculator =
    calculator_button_left +
    calculator_button_width +
    calculator_button_width -
    calculator_width;
  let total_top_for_calculator =
    calculator_button_top + calculator_button_height + 5;
  $("#calculator_main")
    .css("top", calculator_button_top + 40)
    .css("left", calculator_button_left - 100);

  /**
   * Click to showing calculator
   */
  $(document).on("click", "#calculator_button", function (e) {
    $("#calculator_main").fadeToggle(333);
    $(".overlayForCalculator").fadeToggle(111);
  });
  $(document).on("click", ".overlayForCalculator", function (e) {
    $("#calculator_main").fadeOut(333);
    $(".overlayForCalculator").fadeOut(111);
  });
}
function arrange_info_on_the_cart_to_modify(response) {
  let sale_id = response.id;
  let draw_table_for_order = "";
  $("#open_invoice_date_hidden").val(response.sale_date);

  $(".datepicker_custom")
    .datepicker({
      autoclose: true,
      format: "yyyy-mm-dd",
      startDate: "0",
      todayHighlight: true,
    })
    .datepicker("update", response.sale_date);

  for (let key in response.items) {
    //construct div
    let this_item = response.items[key];

    let selected_modifiers = "";
    let selected_modifiers_id = "";
    let selected_modifiers_price = "";
    let modifiers_price = 0;
    let total_modifier = this_item.modifiers.length;
    let i = 1;
    let item_id = this_item.food_menu_id;
    let draw_table_for_order_tmp_modifier_tax = "";
    for (let mod_key in this_item.modifiers) {
      let this_modifier = this_item.modifiers[mod_key];
      let modifier_id_custom = this_modifier.modifier_id;
      //get selected modifiers
      if (i == total_modifier) {
        selected_modifiers += this_modifier.name;
        selected_modifiers_id += this_modifier.modifier_id;
        selected_modifiers_price += this_modifier.modifier_price;
        modifiers_price = (
          parseFloat(modifiers_price) + parseFloat(this_modifier.modifier_price)
        ).toFixed(ir_precision);
      } else {
        selected_modifiers += this_modifier.name + ", ";
        selected_modifiers_id += this_modifier.modifier_id + ",";
        selected_modifiers_price += this_modifier.modifier_price + ",";
        modifiers_price = (
          parseFloat(modifiers_price) + parseFloat(this_modifier.modifier_price)
        ).toFixed(ir_precision);
      }

      let tax_information = "";
      // iterate over each item in the array
      tax_information = this_modifier.menu_taxes;
      tax_information = IsJsonString(tax_information)
        ? JSON.parse(tax_information)
        : "";
      draw_table_for_order_tmp_modifier_tax +=
        '<span class="item_vat_modifier ir_display_none item_vat_modifier_' +
        item_id +
        '" data-item_id="' +
        item_id +
        '"  data-modifier_price="' +
        this_modifier.modifier_price +
        '" data-modifier_id="' +
        modifier_id_custom +
        '" id="item_vat_percentage_table' +
        item_id +
        "" +
        modifier_id_custom +
        '">' +
        JSON.stringify(tax_information) +
        "</span>";
      i++;
    }

    let previous_id =
      this_item.previous_id == "" || this_item.previous_id == null
        ? ""
        : this_item.previous_id;
    let cooking_done_time =
      this_item.cooking_done_time == "" || this_item.cooking_done_time == null
        ? ""
        : this_item.cooking_done_time;
    let cooking_start_time =
      this_item.cooking_start_time == "" || this_item.cooking_start_time == null
        ? ""
        : this_item.cooking_start_time;
    let cooking_status =
      this_item.cooking_status == "" || this_item.cooking_status == null
        ? ""
        : this_item.cooking_status;
    let item_type =
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
      '<div class="single_order_column first_column fix"><i   class="fas fa-pencil-alt edit_item txt_5" id="edit_item_' +
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
      '<div class="single_order_column third_column fix"><input type="hidden"  class="tmp_qty"  name="tmp_qty" value="' +
      this_item.tmp_qty +
      '" id="tmp_qty_' +
      this_item.food_menu_id +
      '"> <input type="hidden" class="p_qty" name="p_qty" value="' +
      this_item.qty +
      '" id="p_qty_' +
      this_item.food_menu_id +
      '"> <i class="fal fa-minus decrease_item_table txt_5" id="decrease_item_table_' +
      this_item.food_menu_id +
      '"></i> <span class="qty_item_custom" id="item_quantity_table_' +
      this_item.food_menu_id +
      '">' +
      this_item.qty +
      '</span> <i  class="fal fa-plus increase_item_table txt_5" id="increase_item_table_' +
      this_item.food_menu_id +
      '"></i></div>';
    draw_table_for_order +=
      '<div class="single_order_column forth_column fix"><input type="" name="" placeholder="Amt or %" class="discount_cart_input" id="percentage_table_' +
      this_item.food_menu_id +
      '" value="' +
      this_item.menu_discount_value +
      '" disabled></div>';
    draw_table_for_order +=
      '<div class="single_order_column fifth_column">' +
      currency +
      ' <span id="item_total_price_table_' +
      this_item.food_menu_id +
      '">' +
      this_item.menu_price_with_discount +
      "</span><i data-id='" +
      this_item.food_menu_id +
      "' class='fal fa-times removeCartItem'></i></div>";
    draw_table_for_order += "</div>";
    if (selected_modifiers != "") {
      draw_table_for_order += '<div class="second_portion fix">';
      draw_table_for_order += draw_table_for_order_tmp_modifier_tax;
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
        '<div class="single_order_column first_column fix"><span class="modifier_txt_custom" id="item_modifiers_table_' +
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
        '<div class="single_order_column first_column fix modifier_txt_custom">' +
        note_txt +
        ': <span id="item_note_table_' +
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

    if(Number(response.delivery_charge_actual_charge)){
        $("#delivery_charge").val(response.delivery_charge);
    }else{
        $("#delivery_charge").val((0).toFixed(ir_precision));
    }

    $("#total_payable").html(response.total_payable);
  $("#charge_type").val(response.charge_type).change();
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
    if (waiter_app_status != "Yes") {
      $("#select_waiter").val("").trigger("change");
    }
  } else {
    if (response.waiter_id) {
      if (waiter_app_status != "Yes") {
        $("#select_waiter").val(response.waiter_id).trigger("change");
      }
    } else {
      if (waiter_app_status != "Yes") {
        $("#select_waiter").val("").trigger("change");
      }
    }
  }
  if (response.order_type == "1") {
    // $(".main_top").find("button").css("background-color", "#109ec5");
    $(".main_top").find("button").attr("data-selected", "unselected");

    // $("#dine_in_button").css("background-color", "#B5D6F6");
    $("#dine_in_button").attr("data-selected", "selected");

    $("#table_button").attr("disabled", false);
  } else if (response.order_type == "2") {
    // $(".main_top").find("button").css("background-color", "#109ec5");
    $(".main_top").find("button").attr("data-selected", "unselected");

    // $("#take_away_button").css("background-color", "#B5D6F6");
    $("#take_away_button").attr("data-selected", "selected");

    $("#table_button").attr("disabled", false);
  } else if (response.order_type == "3") {
    // $(".main_top").find("button").css("background-color", "#109ec5");
    $(".main_top").find("button").attr("data-selected", "unselected");

    // $("#delivery_button").css("background-color", "#B5D6F6");
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
    // $(".main_top").find("button").css("background-color", "#109ec5");
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
        let gst_no =
          response.gst_number == null || response.gst_number == ""
            ? ""
            : response.gst_number;
        $("#customer_gst_number_modal").val(response.gst_number);
      }
      $("#add_customer_modal").addClass("active");
      $(".pos__modal__overlay").fadeIn(200);
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
        let number_of_underscore = Math.floor(Math.random() * 10);

        let underscrore = "";

        for (let i = 0; i <= number_of_underscore; i++) {
          underscrore += "_";
        }

        let number = Math.floor(Math.random() * 2000);

        let msg = response;

        for (let i = 0; i <= number; i++) {
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
let tmp = 1;
function createAnimation(sale_id) {
  sale_id = Number(sale_id);
  setTimeout(function () {
    $(".order_details").animate(
      { scrollTop: Number($(".order_details").height()) + 2000 },
      2000
    );
    const time1 = setInterval(function () {
      $("#order_" + sale_id).css("backgroundColor", "#f7bcbc !important");
    }, 500);
    const time2 = setInterval(function () {
      $("#order_" + sale_id).css("backgroundColor", "white !important");
    }, 2000);
    setTimeout(function () {
      $(".order_details").animate({ scrollTop: 0 }, 1000);
      clearInterval(time1);
      clearInterval(time2);
      $("#order_" + sale_id).css("backgroundColor", "white");
      if (waiter_app_status == "Yes") {
        $("#show_running_order").click();
      }
    }, 4300);
  }, 500);
}

// separetor
$(document).on("mouseover", "#create_invoice_and_close", function (e) {
  $(".invoiceToolTip").slideDown(333);
});
$(document).on("mouseleave", "#create_invoice_and_close", function (e) {
  $(".invoiceToolTip").slideUp(333);
});

$(document).on("mouseover", "#create_bill_and_close", function (e) {
  $(".billToolTip").slideDown(333);
});
$(document).on("mouseleave", "#create_bill_and_close", function (e) {
  $(".billToolTip").slideUp(333);
});

$(document).on("click", "#go_to_dashboard", function (e) {
  let ur_role = $("#ur_role").val();
  if (ur_role == "Admin") {
    window.location.href = base_url + "Dashboard/dashboard";
  } else {
    window.location.href = base_url + "Authentication/userProfile";
  }
});

function searchItemAndConstructGallery(searchedValue) {
  let resultObject = search(searchedValue, window.items);
  return resultObject;
}
function searchCustomerAddress(searchValue) {
  let resultObject = searchAddress(searchValue, window.customers);
  return resultObject;
}
$.datable();
$(document).on("click", "#register_details", function (e) {
  let not_closed_yet = $("#not_closed_yet").val();
  let opening_balance = $("#opening_balance").val();
  let customer_due_receive = $("#customer_due_receive").val();
  let paid_amount = $("#paid_amount").val();
  let in_ = $("#in_").val();
  let cash = $("#cash").val();
  let paypal = $("#paypal").val();
  let sale = $("#sale").val();
  let card = $("#card").val();
  let currency = "";

  $.ajax({
    url: base_url + "Sale/registerDetailCalculationToShowAjax",
    method: "POST",
    data: {
      csrf_name_: csrf_value_,
    },
    success: function (response) {
      console.log(response);
      response = JSON.parse(response);
      $("#register_modal").addClass("active");
      $(".pos__modal__overlay").fadeIn(200);
      $("#opening_closing_register_time").show();
      $("#opening_register_time").html(response.opening_date_time);
      let opening_balance_text = $("#opening_balance").val();
      let customer_due_receive_text = $("#customer_due_receive").val();
      let sale_text = $("#sale").val();

      let t1 = response.opening_date_time.split(/[- :]/);
      let d1 = new Date(Date.UTC(t1[0], t1[1] - 1, t1[2], t1[3], t1[4], t1[5]));
      let t2 = "";
      if (response.closing_date_time) {
        t2 = response.closing_date_time.split(/[- :]/);
      }

      let d2 = new Date(Date.UTC(t2[0], t2[1] - 1, t2[2], t2[3], t2[4], t2[5]));

      if (d1 > d2) {
        $("#closing_register_time").html(not_closed_yet);
      } else {
        $("#closing_register_time").html(response.closing_date_time);
      }

      let register_detail_modal_content = "";
      let customer_due_receive =
        response.customer_due_receive == null
          ? 0
          : response.customer_due_receive;
      let opening_balance =
        response.opening_balance == null ? 0 : response.opening_balance;
      let sale_due_amount =
        response.sale_due_amount == null ? 0 : response.sale_due_amount;
      let sale_in_card =
        response.sale_in_card == null ? 0 : response.sale_in_card;
      let sale_in_cash =
        response.sale_in_cash == null ? 0 : response.sale_in_cash;
      let sale_in_paypal =
        response.sale_in_paypal == null ? 0 : response.sale_in_paypal;
      let sale_paid_amount =
        response.sale_paid_amount == null ? 0 : response.sale_paid_amount;
      let sale_total_payable_amount =
        response.sale_total_payable_amount == null
          ? 0
          : response.sale_total_payable_amount;

      let balance = (
        parseFloat(opening_balance) +
        parseFloat(sale_paid_amount) +
        parseFloat(customer_due_receive)
      ).toFixed(ir_precision);
      register_detail_modal_content +=
        "<p>" +
        opening_balance_text +
        ": " +
        currency +
        " " +
        opening_balance +
        "</p>";
      // register_detail_modal_content += '<p>Sale Total Amount: '+currency+' '+sale_total_payable_amount+'</p>';
      register_detail_modal_content +=
        "<p>" +
        sale_text +
        " (" +
        paid_amount +
        "): " +
        currency +
        " " +
        sale_paid_amount +
        "</p>";
      // register_detail_modal_content += '<p>Sale Due Amount: '+currency+' '+sale_due_amount+'</p>';
      // register_detail_modal_content += '<p>&nbsp;</p>';
      register_detail_modal_content +=
        "<p>" +
        customer_due_receive_text +
        ": " +
        currency +
        " " +
        customer_due_receive +
        "</p>";
      register_detail_modal_content +=
        "<p>Balance {" +
        opening_balance +
        " + " +
        sale +
        " (" +
        paid_amount +
        ") + " +
        customer_due_receive +
        "}: " +
        currency +
        " " +
        balance +
        "</p>";
      register_detail_modal_content +=
        '<p style="width:100%;border-bottom:1px solid #b5d6f6;line-height:0px;">&nbsp;</p>';

      register_detail_modal_content += response.payment_html_content;
      $("#register_details_content").html(register_detail_modal_content);
      // $('#myModal').modal('hide');
    },
    error: function () {
      alert("error");
    },
  });
});
$(document).on("click", "#register_close", function (e) {
  let csrf_name_ = $("#csrf_name_").val();
  let csrf_value_ = $("#csrf_value_").val();
  swal(
    {
      title: warning + "!",
      text: txt_err_pos_2,
      confirmButtonColor: "#3c8dbc",
      confirmButtonText: ok,
      showCancelButton: true,
    },
    function () {
      $.ajax({
        url: base_url + "Sale/closeRegister",
        method: "POST",
        data: {
          csrf_name_: csrf_value_,
        },
        success: function (response) {
          swal({
            title: warning + "!",
            text: register_close,
            confirmButtonText: ok,
            confirmButtonColor: "#b6d6f6",
          });
          $("#close_register_button").hide();
          window.location.href = base_url + "Authentication/logOut";
        },
        error: function () {
          alert("error");
        },
      });
    }
  );
});
//initial the select2
$(".select2").select2();
/**
 * New Custom Script
 */
const body_el = $("body");
let modify_order_html = `
<div>
    <h3>Choose This For:</h3>
    <p>1. Add New Item</p>
    <p>2. Change Table</p>
    <p>3. Change anything in an Order</p>
</div>`;

tippy("#modify_order", {
  content: modify_order_html,
  theme: "light",
  animation: "scale",
  allowHTML: true,
});
tippy(".header_menu_icon", {
  animation: "scale",
});

tippy(".tooltip_modifier", {
  animation: "scale",
  allowHTML: true,
});
tippy(".given_amount_tooltip", {
  animation: "scale",
  allowHTML: true,
});
tippy(".btn_tip", {
  theme: "light",
  animation: "scale",
});
tippy(".item_name", {
  placement: "bottom-start",
});

// End ToolTip
$(".scrollbar-macosx").scrollbar();
// Click to showing Sub Menu
$(document).on("click", ".has__children", function (e) {
  $(this).children(".sub__menu").slideToggle(333);
});
$(window).click(function (event) {
  if ($(event.target).closest("li.has__children").length === 0) {
    $(".has__children").children(".sub__menu").slideUp(333);
  }
});

// Hide Modal When Click to close Icon
$("body").on("click", ".alertCloseIcon", function () {
  $(this).parent().parent().parent().removeClass("active").addClass("inActive");
  setTimeout(function () {
    $(".modal").removeClass("inActive");
  }, 1000);
  $(".pos__modal__overlay").fadeOut(300);
});
$(document).on("click", ".pos__modal__overlay", function (e) {
  $(".modal").removeClass("active");
  $("aside#pos__sidebar").removeClass("active");
  $(".pos__modal__overlay").fadeOut(300);
});
// Remove when click cross icon in cart item list
$("body").on("click", ".removeCartItem", function () {
  let waiter_app_status = $("#waiter_app_status").val();
  let sale_no = $(".modification").attr("data-sale-no");
  let id = $(this).attr("data-id");
  let current_status = $(this)
    .parent()
    .parent()
    .find(".item_cooking_status")
    .html();
  if (waiter_app_status == "Yes" && sale_no != undefined) {
    swal({
      title: warning,
      text: delete_only_for_admin,
      confirmButtonText: ok,
      confirmButtonColor: "#b6d6f6",
    });
    return false;
  } else {
    if (role != "Admin") {
      if (current_status == "Started Cooking") {
        swal({
          title: warning,
          text: this_item_is_under_cooking_please_contact_with_admin,
          confirmButtonText: ok,
          confirmButtonColor: "#b6d6f6",
        });
        return false;
      } else if (current_status == "Done") {
        swal({
          title: warning,
          text: this_item_already_cooked_please_contact_with_admin,
          confirmButtonText: ok,
          confirmButtonColor: "#b6d6f6",
        });
        return false;
      } else {
        $(this)
          .parent()
          .parent()
          .parent()
          .slideUp(333, function () {
            $(this).remove();
          });
      }
    } else {
      $(this)
        .parent()
        .parent()
        .parent()
        .slideUp(333, function () {
          $(this).remove();
        });
    }
    setTimeout(function () {
      do_addition_of_item_and_modifiers_price();
    }, 500);
  }
});
$("body").on("click", ".cart__single__item", function () {
  $(this).hide();
  $(this).next(".cart__quantity__trigger").css("display", "flex");
});
/**
 * Click to Open Modal
 */
body_el.on("click", "#open_discount_modal", function () {
  $("#discount_modal").addClass("active");
  $(".pos__modal__overlay").fadeIn(300);
});
body_el.on("click", "#open_charge_modal", function () {
  $("#charge_modal").addClass("active");
  $(".pos__modal__overlay").fadeIn(300);
});
body_el.on("click", "#open_tax_modal", function () {
  $("#tax_modal").addClass("active");
  $(".pos__modal__overlay").fadeIn(300);
});

body_el.on("click", ".cancel", function () {
  $(this).parent().parent().parent().removeClass("active").addClass("inActive");
  setTimeout(function () {
    $(".modal").removeClass("inActive");
  }, 1000);
  $(".pos__modal__overlay").fadeOut(300);
});
body_el.on("click", ".submit", function () {
  $(".modal").removeClass("active");
  $(".pos__modal__overlay").fadeOut(300);
});
$(document).on("click", "#discount_txt_focus", function (e) {
  $("#modal_discount").focus();
  $("#modal_discount").select();
});
/**
 * Full Screen
 */
function toggleFullscreen(elem) {
  elem = elem || document.documentElement;
  if (
    !document.fullscreenElement &&
    !document.mozFullScreenElement &&
    !document.webkitFullscreenElement &&
    !document.msFullscreenElement
  ) {
    if (elem.requestFullscreen) {
      elem.requestFullscreen();
    } else if (elem.msRequestFullscreen) {
      elem.msRequestFullscreen();
    } else if (elem.mozRequestFullScreen) {
      elem.mozRequestFullScreen();
    } else if (elem.webkitRequestFullscreen) {
      elem.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
    }
  } else {
    if (document.exitFullscreen) {
      document.exitFullscreen();
    } else if (document.msExitFullscreen) {
      document.msExitFullscreen();
    } else if (document.mozCancelFullScreen) {
      document.mozCancelFullScreen();
    } else if (document.webkitExitFullscreen) {
      document.webkitExitFullscreen();
    }
  }
}
$(document).on("click", "#fullscreen", function (e) {
  toggleFullscreen();
  $(this).attr("data-tippy-content", "");

  if ($(this).find("i").hasClass("fa-expand-arrows-alt")) {
    $(this).find("i").removeClass("fa-expand-arrows-alt").addClass("fa-times");
    $(this).attr("data-tippy-content", fullscreen_2);
  } else {
    $(this).find("i").removeClass("fa-times").addClass("fa-expand-arrows-alt");
    $(this).attr("data-tippy-content", fullscreen_1);
  }
  tippy("#fullscreen", {
    animation: "scale",
  });
});
/**
 * POS Screen Sidebar Menu
 */

$("aside#pos__sidebar")
  .find("li.have_sub_menu")
  .on("click", function () {
    // $("aside#pos__sidebar").find("li.have_sub_menu").removeClass("active");
    $(this).toggleClass("active");
  });
$(document).on("click", "#open__menu", function (e) {
  $("aside#pos__sidebar").addClass("active");
  $(".pos__modal__overlay").fadeIn(200);
});

// material icon init
feather.replace();
display_date_time();
function getNewDateTime() {
  let refresh = 1000; // Refresh rate in milli seconds
  setTimeout(display_date_time, refresh);
}
function display_date_time() {
  //for date and time
  let today = new Date();
  let dd = today.getDate();
  let mm = today.getMonth() + 1; //January is 0!
  let yyyy = today.getFullYear();
  if (dd < 10) {
    dd = "0" + dd;
  }
  if (mm < 10) {
    mm = "0" + mm;
  }
  let time_a = new Date().toLocaleTimeString();
  let today_date = yyyy + "-" + mm + "-" + dd;
  tippy(".time__date", {
    content:
      `<div style="text-align:center"><span>` +
      today_date +
      `</span><br><span>` +
      time_a +
      `</span></div>`,
    allowHTML: true,
    animation: "scale",
  });
  $("#closing_register_time").html(today_date + " " + time_a);
  /* recursive call for new time*/
  getNewDateTime();
}
function escape_output(str) {
  return String(str)
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace("'", "");
}

/**
 * Show and Hide Cart List and Product Item only for mobile devices
 */
$("#show_running_order").on("click", function () {
  $(".main_left").toggleClass("active");
  $(".overlayForCalculator").fadeToggle(100);
  if ($(this).attr("data-isActive") === "false") {
    $(this).attr("data-isActive", "true");
  } else {
    $(this).attr("data-isActive", "false");
  }
});

$("#show_cart_list").on("click", function () {
  $(".main_middle").slideDown(300);
  $(".main_right").slideUp(100);
});
$("#show_product").on("click", function () {
  $(".main_right").slideDown(300);
  $(".main_middle").slideUp(100);
});

$("#show_all_menu").on("click", function () {
  $(".all__menus").slideToggle(333);
});
// $(window).click(function (event) {
//   if ($(event.target).closest("#show_all_menu").length === 0) {
//     $(".all__menus").slideUp(333);
//   }
// });

$("#sale_hold_modal_order_details").on("click", function () {
  $(this).attr("data-selectedbtn", "selected");
  $("#sale_hold_modal_order_list").attr("data-selectedbtn", "unselected");

  $("#sale_hold_modal_order_details_list").fadeIn(300);
  $("#sale_hold_modal_order_info_list").fadeOut(0);
});
$("#sale_hold_modal_order_list").on("click", function () {
  $(this).attr("data-selectedbtn", "selected");
  $("#sale_hold_modal_order_details").attr("data-selectedbtn", "unselected");

  $("#sale_hold_modal_order_info_list").fadeIn(300);
  $("#sale_hold_modal_order_details_list").fadeOut(0);
});
/**
 * Recent Sales
 */
$("#recent_sales_order_details").on("click", function () {
  $(this).attr("data-selectedbtn", "selected");
  $("#recent_sales_order_list").attr("data-selectedbtn", "unselected");

  $("#recent_sales_order_details_list").fadeIn(300);
  $("#recent_sales_order_info_list").fadeOut(0);
});
$("#recent_sales_order_list").on("click", function () {
  $(this).attr("data-selectedbtn", "selected");
  $("#recent_sales_order_details").attr("data-selectedbtn", "unselected");

  $("#recent_sales_order_details_list").fadeOut(0);
  $("#recent_sales_order_info_list").fadeIn(300);
});

$(document).on("click", "#last_ten_feature_button", function (e) {
  remove_all_cart_future_info();
  $(".cus_pos_modal_feature_sale_modal").addClass("active");
  $(".pos__modal__overlay").fadeIn(200);
  $.ajax({
    url: base_url + "Sale/get_last_10_future_sales_ajax",
    method: "GET",
    success: function (response) {
      let orders = JSON.parse(response);
      let last_10_orders = "";
      let html_custom = ` `;
      for (let key in orders) {
        let order_name = "";
        if (orders[key].order_type == "1") {
          order_name = "A " + orders[key].sale_no;
        } else if (orders[key].order_type == "2") {
          order_name = "B " + orders[key].sale_no;
        } else if (orders[key].order_type == "3") {
          order_name = "C " + orders[key].sale_no;
        }
        let tables_booked = "";
        if (orders[key].tables_booked.length > 0) {
          let w = 1;
          for (let k in orders[key].tables_booked) {
            let single_table = orders[key].tables_booked[k];
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

        let phone_text_ = "";
        if (orders[key].phone) {
          phone_text_ = " (" + orders[key].phone + ")";
        }
        let bg_color = "";
        let ignore_status = "";
        if (orders[key].future_sale_status == 3) {
          bg_color = "#99e299";
          ignore_status = "No";
        }
        html_custom +=
          `<div data-ignore_status="` +
          ignore_status +
          `" style="background-color:` +
          bg_color +
          `" class="single_future_sale tbl_pointer_row_custom feuture_preview_row future_last_ten_custom_` +
          orders[key].id +
          `"  data-id="` +
          orders[key].id +
          `"  data-selected="unselected">
                                            <div class="item first_column column">` +
          order_name +
          `</div>
                                            <div class="item second_column column">` +
          orders[key].customer_name +
          phone_text_ +
          `</div>
                                            <div class="item third_column column">` +
          orders[key].sale_date +
          `</div>
                                        </div>`;
      }
      $(".detail_holder").empty();
      $(".detail_holder").prepend(html_custom);
    },
    error: function () {
      alert(a_error);
    },
  });
});

$(document).on("click", ".modal_hide", function () {
  $("#last_future_sale_id").val("");
  $(this).parent().parent().parent().removeClass("active").addClass("inActive");
  setTimeout(function () {
    $(".cus_pos_modal").removeClass("inActive");
  }, 1000);
  $(".pos__modal__overlay").fadeOut(300);
});

$(document).on("click", ".pos__modal__close", function () {
  $("#last_future_sale_id").val("");
  $(this).parent().parent().removeClass("active").addClass("inActive");
  setTimeout(function () {
    $(".cus_pos_modal").removeClass("inActive");
  }, 1000);
  $(".pos__modal__overlay").fadeOut(300);
});

$(document).on("click", ".pos__modal__overlay", function () {
  $(".pos__modal__overlay").fadeOut(300);
  $(".cus_pos_modal").removeClass("active");
});

$(document).on("click", "#draft_edit_modal", function () {
  let sale_id = $("#last_future_sale_id").val();
  if (sale_id > 0) {
    //get total items in cart
    let total_items_in_cart = $(".order_holder .single_order").length;

    if (total_items_in_cart > 0) {
      swal(
        {
          title: warning + "!",
          text: txt_err_pos_5,
          confirmButtonColor: "#3c8dbc",
          confirmButtonText: ok,
          showCancelButton: true,
        },
        function () {
          $(".order_holder").empty();
          get_details_of_a_particular_order(sale_id);
          $(this)
            .parent()
            .parent()
            .parent()
            .removeClass("active")
            .addClass("inActive");
          setTimeout(function () {
            $(".cus_pos_modal").removeClass("inActive");
          }, 1000);
          $(".pos__modal__overlay").fadeOut(300);
          remove_all_cart_future_info();
        }
      );
    } else {
      get_details_of_a_particular_order(sale_id);
      $(this)
        .parent()
        .parent()
        .parent()
        .removeClass("active")
        .addClass("inActive");
      setTimeout(function () {
        $(".cus_pos_modal").removeClass("inActive");
      }, 1000);
      $(".pos__modal__overlay").fadeOut(300);
      remove_all_cart_future_info();
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
$(document).on("click", "#draft_edit_modal_invoice", function () {
  let sale_id = $("#last_future_sale_id").val();
  $("#print_type").val(1);
  let this_action = $(this);
  if (sale_id > 0) {
    this_action
      .parent()
      .parent()
      .parent()
      .removeClass("active")
      .addClass("inActive");
    setTimeout(function () {
      $(".cus_pos_modal").removeClass("inActive");
    }, 1000);
    $(".pos__modal__overlay").fadeOut(300);

    $.ajax({
      url: base_url + "Sale/set_as_running_order",
      method: "POST",
      data: {
        sale_id: sale_id,
        status: 3,
        csrf_irestoraplus: csrf_value_,
      },
      success: function (response) {
        response = JSON.parse(response);
        $("#last_future_sale_id").val("");
        set_new_orders_to_view_future_add(sale_id);
        remove_all_cart_future_info();
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
function remove_all_cart_future_info() {
  $("#last_10_waiter_id_").html("");
  $("#last_10_waiter_name_").html("");
  $("#last_10_customer_id_").html("");
  $("#last_10_customer_name_").html("");
  $("#last_10_table_id_").html("");
  $("#last_10_table_name_").html("");
  $("#last_10_order_type_").html("");
  $("#last_10_order_type_id_").html("");
  $(".all_items_vat_last_10_").html(Number(0).toFixed(ir_precision));
  $(".all_items_discount_last_10_").html(Number(0).toFixed(ir_precision));
  $(".delivery_charge_last_10_").html(Number(0).toFixed(ir_precision));
  $(".total_payable_last_10_").html(Number(0).toFixed(ir_precision));
  $(".recent_sale_modal_details_vat_").html(Number(0).toFixed(ir_precision));
  $(".item_order_details .modifier_item_details_holder").empty();
  $(".total_items_in_cart_last_10_").html("0");
  $(".sub_total_show_last_10_").html(Number(0).toFixed(ir_precision));
  $(".sub_total_last_10_").html(Number(0).toFixed(ir_precision));
  $(".total_item_discount_last_10_").html(Number(0).toFixed(ir_precision));
  $(".discounted_sub_total_amount_last_10_").html(
    Number(0).toFixed(ir_precision)
  );
  $(".sub_total_discount_last_10_").html(Number(0).toFixed(ir_precision));
  $(".sub_total_discount_amount_last_10_").html(
    Number(0).toFixed(ir_precision)
  );
}
$(document).on("click", ".feuture_preview_row", function () {
  //get sale id
  let sale_id = $(this).attr("data-id");
  $("#last_future_sale_id").val(sale_id);
  let this_a = $(this);
  $(".single_future_sale").each(function () {
    let ignore_status = $(this).attr("data-ignore_status");
    if (ignore_status != "No") {
      $(this).css("background-color", "white");
    }
  });
  let this_ignore_status = this_a.attr("data-ignore_status");
  if (this_ignore_status != "No") {
    this_a.css("background-color", "rgb(247, 247, 247)");
  }

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
      let order_type = "";
      let order_type_id = 0;
      let invoice_type = "";
      let tables_booked = "";
      if (response.tables_booked.length > 0) {
        let w = 1;
        for (let k in response.tables_booked) {
          let single_table = response.tables_booked[k];
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
      $("#last_10_waiter_id_").html(response.waiter_id);
      $("#last_10_waiter_name_").html(response.waiter_name);
      $("#last_10_customer_id_").html(response.customer_id);
      $("#last_10_customer_name_").html(response.customer_name);
      $("#last_10_table_id_").html(response.table_id);
      $("#last_10_table_name_").html(tables_booked);
      $("#last_10_order_type_").html(order_type);
      $("#last_10_order_type_id_").html(order_type_id);
      let draw_table_for_last_ten_sales_order = "";
      let draw_table_for_last_ten_sales_order_temp = "";

      for (let key in response.items) {
        //construct div
        let this_item = response.items[key];

        let selected_modifiers = "";
        let selected_modifiers_id = "";
        let selected_modifiers_price = "";
        let modifiers_price = 0;

        let total_modifier = this_item.modifiers.length;
        let i = 1;
        let item_id = this_item.food_menu_id;
        let draw_table_for_order_tmp_modifier_tax = "";
        for (let mod_key in this_item.modifiers) {
          let this_modifier = this_item.modifiers[mod_key];
          let modifier_id_custom = this_modifier.modifier_id;
          //get selected modifiers
          if (i == total_modifier) {
            selected_modifiers += this_modifier.name;
            selected_modifiers_id += this_modifier.modifier_id;
            selected_modifiers_price += this_modifier.modifier_price;
            modifiers_price = (
              parseFloat(modifiers_price) +
              parseFloat(this_modifier.modifier_price)
            ).toFixed(ir_precision);
          } else {
            selected_modifiers += this_modifier.name + ", ";
            selected_modifiers_id += this_modifier.modifier_id + ",";
            selected_modifiers_price += this_modifier.modifier_price + ",";
            modifiers_price = (
              parseFloat(modifiers_price) +
              parseFloat(this_modifier.modifier_price)
            ).toFixed(ir_precision);
          }
          let tax_information = "";
          // iterate over each item in the array
          tax_information = this_modifier.menu_taxes;
          tax_information = IsJsonString(tax_information)
            ? JSON.parse(tax_information)
            : "";
          draw_table_for_order_tmp_modifier_tax +=
            '<span class="item_vat_modifier ir_display_none item_vat_modifier_' +
            item_id +
            '" data-item_id="' +
            item_id +
            '"  data-modifier_price="' +
            this_modifier.modifier_price +
            '" data-modifier_id="' +
            modifier_id_custom +
            '" id="item_vat_percentage_table' +
            item_id +
            "" +
            modifier_id_custom +
            '">' +
            JSON.stringify(tax_information) +
            "</span>";
          i++;
        }
        let discount_value =
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
            '<div class="single_order_column_hold first_column column fix"><span class="modifier_txt_custom" id="last_10_item_modifiers_table_' +
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
            '<div class="single_order_column_hold first_column column fix modifier_txt_custom" >' +
            note_txt +
            ': <span id="last_10_item_note_table_' +
            this_item.food_menu_id +
            '">' +
            this_item.menu_note +
            "</span></div>";
          draw_table_for_last_ten_sales_order += "</div>";
        }

        draw_table_for_last_ten_sales_order += "</div>";
      }
      //add to top
      $(".item_order_details .modifier_item_details_holder").empty();
      $(".item_order_details .modifier_item_details_holder").prepend(
        draw_table_for_last_ten_sales_order
      );
      let total_items_in_cart_with_quantity = 0;
      $(
        ".item_order_details .modifier_item_details_holder .single_item_modifier .first_portion .third_column span"
      ).each(function (i, obj) {
        total_items_in_cart_with_quantity =
          parseInt(total_items_in_cart_with_quantity) +
          parseInt($(this).html());
      });
      $(".total_items_in_cart_last_10_").html(
        total_items_in_cart_with_quantity
      );
      let sub_total_discount_last_10 =
        response.sub_total_discount_value != ""
          ? response.sub_total_discount_value
          : "0.00";
      $(".sub_total_show_last_10_").html(response.sub_total);
      $(".sub_total_last_10_").html(response.sub_total);
      $(".total_item_discount_last_10_").html(
        response.total_item_discount_amount
      );
      $(".discounted_sub_total_amount_last_10_").html(
        response.sub_total_discount_amount
      );
      $(".sub_total_discount_last_10_").html(sub_total_discount_last_10);
      $(".sub_total_discount_amount_last_10_").html(
        response.sub_total_with_discount
      );
      let total_vat_section_to_show = "";
      $.each(JSON.parse(response.sale_vat_objects), function (key, value) {
        total_vat_section_to_show +=
          '<span class="tax_field_order_details" id="tax_field_order_details_' +
          value.tax_field_id +
          '">' +
          value.tax_field_type +
          ":  " +
          currency +
          ' <span class="tax_field_amount_order_details" id="tax_field_amount_order_details_' +
          value.tax_field_id +
          '">' +
          Number(value.tax_field_amount).toFixed(ir_precision) +
          "</span></span>";
      });
      $(".all_items_vat_last_10_").html(total_vat_section_to_show);
      $(".all_items_discount_last_10_").html(Number(response.total_discount_amount).toFixed(ir_precision));

        if(Number(response.delivery_charge_actual_charge)){
            $(".delivery_charge_last_10_").html((response.delivery_charge));
        }else{
            $(".delivery_charge_last_10_").html((0).toFixed(ir_precision));
        }
      $(".total_payable_last_10_").html(Number(response.total_payable).toFixed(ir_precision));
      $(".recent_sale_modal_details_vat_").html(Number(response.vat).toFixed(ir_precision));
    },
    error: function () {
      alert(a_error);
    },
  });
});

$(document).on("click", "#draft_delete_modal", function (e) {
  let sale_id = $("#last_future_sale_id").val();
  let this_action = $(this);
  if (sale_id > 0) {
    if (role != "Admin") {
      swal({
        title: warning,
        text: delete_only_for_admin,
        confirmButtonText: ok,
        confirmButtonColor: "#b6d6f6",
      });
      return false;
    } else {
      swal(
        {
          title: warning + "!",
          text: sure_remove_this_order,
          confirmButtonColor: "#3c8dbc",
          confirmButtonText: ok,
          showCancelButton: true,
        },
        function () {
          $.ajax({
            url: base_url + "Sale/set_as_running_order",
            method: "POST",
            data: {
              sale_id: sale_id,
              status: 1,
              csrf_irestoraplus: csrf_value_,
            },
            success: function (response) {
              response = JSON.parse(response);
              $("#last_future_sale_id").val("");
              this_action
                .parent()
                .parent()
                .parent()
                .removeClass("active")
                .addClass("inActive");
              setTimeout(function () {
                $(".cus_pos_modal").removeClass("inActive");
              }, 1000);
              $(".pos__modal__overlay").fadeOut(300);
              remove_all_cart_future_info();
            },
            error: function () {
              alert(a_error);
            },
          });
        }
      );
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

setTimeout(function () {
  $("#show_tables_modal2").show();
}, 1000);
/**
 * All Script For All Mobile Devices
 */
$(window).on("load", function () {
  if ($(this).width() < 600) {
    $("#calculator_main").css("left", 10 + "px");
  }
});

$(window).on("resize", function () {
  if ($(this).width() < 600) {
    $("#calculator_main").css("left", 10 + "px");
  }
});
/**
 * Working This function only for mobile devices
 */
function forMobileDevice() {
  if (window.innerWidth < 600) {
    $(document).on("click", ".draft-sale .single_hold_sale", function () {
      $("#sale_hold_modal_order_details_list").fadeIn(300);
      $("#sale_hold_modal_order_info_list").fadeOut(200);
    });
    $(document).on("click", ".recent-sales .single_last_ten_sale", function () {
      $("#recent_sales_order_details_list").fadeIn(300);
      $("#recent_sales_order_info_list").fadeOut(200);
    });
  }
}
forMobileDevice();

$(window).on("load", function () {
  $(".preloader").fadeOut(500);
});

$(document).on("click", "#bill_show_details", function (e) {
  if (
    $(".holder .order_details > .single_order[data-selected=selected]").length >
    0
  ) {
    let sale_id = $(
      ".holder .order_details .single_order[data-selected=selected]"
    )
      .attr("id")
      .substr(6);
    $.ajax({
      url: base_url + "Sale/getBillDetails",
      method: "POST",
      data: { sale_id: sale_id },
      dataType: "json",
      success: function (response) {
        if (response) {
          $(".show_bill_modal_content").html(response);
          $(".pos__modal__overlay").fadeIn(300);
          $("#bill_modal").addClass("active");
        }
      },
      error: function () {},
    });
  } else {
    swal({
      title: warning + "!",
      text: please_select_open_order,
      confirmButtonText: ok,
      confirmButtonColor: "#b6d6f6",
    });
  }
});



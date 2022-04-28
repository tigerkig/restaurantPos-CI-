let base_url = $("base").attr("data-base");
let role = $("base[data-role]").attr("data-role");

let csrf_value_ = $("#csrf_value_").val();

$(document).ready(function () {
  $(document).on("click", "#refresh_orders_button", function () {
    $("#refresh_it_or_not").html("Yes");
    $("#group_by_order_item").val("").trigger("change");
    refresh_orders();
  });
  $(document).on("change", "#group_by_order_item", function () {
    let menu_id = $(this).val();
    let menu_name = $(
      "#group_by_order_item option[value='" + menu_id + "']"
    ).text();

    $("#order_holder .single_order").each(function (i, obj) {
      let $this = $(this);
      let found = 0;
      $(this)
        .find(".items_holder .single_item")
        .each(function () {
          let this_menu_name = $(this).find(".item_name").html();
          if (this_menu_name == menu_name) {
            found++;
          }
        });
      if (found > 0) {
        $this.css("display", "block");
      } else {
        $this.css("display", "none");
      }
    });
    $("#refresh_it_or_not").html("No");
  });

  $("#notification_list_holder")
    .slimscroll({
      height: "240px",
    })
    .parent()
    .css({
      background: "#f5f5f5",
      border: "0px solid #184055",
    });
  $("#order_holder.order_holder .single_order .items_holder")
    .slimscroll({
      height: "199px",
    })
    .parent()
    .css({
      background: "#fff",
      border: "0px solid #184055",
    });
  $(document).on("click", ".items_holder .single_item", function () {
    let single_order = $(this).parent().parent().parent();
    if (single_order.attr("data-order-type") == "Dine In") {
      if ($(this).attr("data-selected") == "selected") {
        $(this).removeClass("light-sky-background");
        $(this).find(".item_quanity_text").css("color", "");
        $(this).find(".item_name").css("color", "");
        $(this).find(".item_qty").css("color", "");
        $(this).find(".modifiers").css("color", "");
        $(this).find(".note").css("color", "");
        if (
          $(this).attr("data-cooking-status") == "Done" ||
          $(this).attr("data-cooking-status") == "Started Preparing"
        ) {
          $(this).find(".item_quanity_text").css("color", "#fff");
          $(this).find(".item_name").css("color", "#fff");
          $(this).find(".item_qty").css("color", "#fff");
          $(this).find(".modifiers").css("color", "#fff");
          $(this).find(".note").css("color", "#fff");
          $(this).attr("data-selected", "selected");
        }
        $(this).attr("data-selected", "unselected");
        let single_order_selected_item = single_order.find(
          '.single_item[data-selected="selected"]'
        ).length;
        if (single_order_selected_item == 0) {
          single_order.find(".start_cooking_button").fadeOut();
          single_order.find(".done_cooking").fadeOut();
        }
      } else {
        $(this).addClass("light-sky-background");
        $(this).find(".item_quanity_text").css("color", "#fff");
        $(this).find(".item_name").css("color", "#fff");
        $(this).find(".item_qty").css("color", "#fff");
        $(this).find(".modifiers").css("color", "#fff");
        $(this).find(".note").css("color", "#fff");
        $(this).attr("data-selected", "selected");
        single_order.find(".start_cooking_button").fadeIn();
        single_order.find(".done_cooking").fadeIn();
      }
    } else {
      swal({
        title: "Alert",
        text: "You should select all for Take Away and Delivery order, as these are pack!",
        confirmButtonColor: "#b6d6f6",
      });
    }
  });
  $(document).on("click", ".select_all_of_an_order", function () {
    let order_id = $(this).attr("id").substr(23);
    $("#single_order_" + order_id + " .items_holder .single_item").attr(
      "data-selected",
      "selected"
    );
    $("#single_order_" + order_id + " .items_holder .single_item").addClass(
      "light-sky-background"
    );
    $("#single_order_" + order_id + " .items_holder .single_item")
      .find(".item_quanity_text")
      .css("color", "#fff");
    $("#single_order_" + order_id + " .items_holder .single_item")
      .find(".item_name")
      .css("color", "#fff");
    $("#single_order_" + order_id + " .items_holder .single_item")
      .find(".item_qty")
      .css("color", "#fff");
    $("#single_order_" + order_id + " .items_holder .single_item")
      .find(".modifiers")
      .css("color", "#fff");
    $("#single_order_" + order_id + " .items_holder .single_item")
      .find(".note")
      .css("color", "#fff");
    $("#single_order_" + order_id + " .items_holder .single_item").attr(
      "data-selected",
      "selected"
    );
    $("#start_cooking_button_" + order_id).fadeIn();
    $("#done_cooking_" + order_id).fadeIn();
  });
  $(document).on("click", ".unselect_all_of_an_order", function () {
    let order_id = $(this).attr("id").substr(25);
    $("#single_order_" + order_id + " .items_holder .single_item").attr(
      "data-selected",
      "unselected"
    );
    $("#single_order_" + order_id + " .items_holder .single_item").removeClass(
      "light-sky-background"
    );
    $("#single_order_" + order_id + " .items_holder .single_item")
      .find(".item_quanity_text")
      .css("color", "");
    $("#single_order_" + order_id + " .items_holder .single_item")
      .find(".item_name")
      .css("color", "");
    $("#single_order_" + order_id + " .items_holder .single_item")
      .find(".item_qty")
      .css("color", "");
    $("#single_order_" + order_id + " .items_holder .single_item")
      .find(".modifiers")
      .css("color", "");
    $("#single_order_" + order_id + " .items_holder .single_item")
      .find(".note")
      .css("color", "");

    $(
      "#single_order_" +
        order_id +
        ' .items_holder .single_item[data-cooking-status="Done"]'
    )
      .find(".item_quanity_text")
      .css("color", "#fff");
    $(
      "#single_order_" +
        order_id +
        ' .items_holder .single_item[data-cooking-status="Done"]'
    )
      .find(".item_name")
      .css("color", "#fff");
    $(
      "#single_order_" +
        order_id +
        ' .items_holder .single_item[data-cooking-status="Done"]'
    )
      .find(".item_qty")
      .css("color", "#fff");
    $(
      "#single_order_" +
        order_id +
        ' .items_holder .single_item[data-cooking-status="Done"]'
    )
      .find(".modifiers")
      .css("color", "#fff");
    $(
      "#single_order_" +
        order_id +
        ' .items_holder .single_item[data-cooking-status="Done"]'
    )
      .find(".note")
      .css("color", "#fff");
    $(
      "#single_order_" +
        order_id +
        ' .items_holder .single_item[data-cooking-status="Done"]'
    ).attr("data-selected", "selected");

    $(
      "#single_order_" +
        order_id +
        ' .items_holder .single_item[data-cooking-status="Started Preparing"]'
    )
      .find(".item_quanity_text")
      .css("color", "#fff");
    $(
      "#single_order_" +
        order_id +
        ' .items_holder .single_item[data-cooking-status="Started Preparing"]'
    )
      .find(".item_name")
      .css("color", "#fff");
    $(
      "#single_order_" +
        order_id +
        ' .items_holder .single_item[data-cooking-status="Started Preparing"]'
    )
      .find(".item_qty")
      .css("color", "#fff");
    $(
      "#single_order_" +
        order_id +
        ' .items_holder .single_item[data-cooking-status="Started Preparing"]'
    )
      .find(".modifiers")
      .css("color", "#fff");
    $(
      "#single_order_" +
        order_id +
        ' .items_holder .single_item[data-cooking-status="Started Preparing"]'
    )
      .find(".note")
      .css("color", "#fff");
    $(
      "#single_order_" +
        order_id +
        ' .items_holder .single_item[data-cooking-status="Started Preparing"]'
    ).attr("data-selected", "selected");
    $("#start_cooking_button_" + order_id).fadeOut();
    $("#done_cooking_" + order_id).fadeOut();
  });

  $(document).on("click", ".start_cooking_button", function () {
    let sale_id = $(this).attr("id").substr(21);
    if ($("#single_order_" + sale_id).attr("data-order-type") == "Dine In") {
      if (
        $(
          "#single_order_" +
            sale_id +
            " .items_holder .single_item[data-selected=selected]"
        ).length > 0
      ) {
        // let previous_id = $('#items_holder_of_order .single_item_in_order[data-selected=selected]').attr('id').substr(12);
        let previous_id = "";
        let j = 1;
        let total_items = $(
          "#single_order_" +
            sale_id +
            " .items_holder .single_item[data-selected=selected]"
        ).length;
        $(
          "#single_order_" +
            sale_id +
            " .items_holder .single_item[data-selected=selected]"
        ).each(function (i, obj) {
          if (j == total_items) {
            previous_id += $(this).attr("id").substr(15);
          } else {
            previous_id += $(this).attr("id").substr(15) + ",";
          }
          j++;
        });
        let previous_id_array = previous_id.split(",");
        previous_id_array.forEach(function (entry) {
          $("#detail_item_id_" + entry).attr("data-selected", "selected");
          $("#detail_item_id_" + entry).addClass("light-blue-background");
          $("#detail_item_id_" + entry).removeClass("green-background");
          $("#detail_item_id_" + entry).removeClass("light-sky-background");
          $(
            "#detail_item_id_" +
              entry +
              " .single_item_right_side .single_item_cooking_status"
          ).css("color", "#fff");
          $(
            "#detail_item_id_" +
              entry +
              " .single_item_right_side .single_item_cooking_status"
          ).html("Preparing");
        });
        if (previous_id != "") {
          let url = base_url + "Bar/update_cooking_status_ajax";
          $.ajax({
            url: url,
            method: "POST",
            data: {
              previous_id: previous_id,
              cooking_status: "Started Preparing",
              csrf_irestoraplus: csrf_value_,
            },
            success: function (response) {
              swal({
                title: "Alert",
                text: "Preparing Started!!",
                confirmButtonColor: "#b6d6f6",
              });
            },
            error: function () {
              alert("error");
            },
          });
        }
      } else {
        swal({
          title: "Alert!",
          text: "Please select an item to Prepare!",
          confirmButtonColor: "#b6d6f6",
        });
      }
    } else {
      let previous_id = "";
      let j = 1;
      let total_items = $(
        "#single_order_" + sale_id + " .items_holder .single_item"
      ).length;

      $("#single_order_" + sale_id + " .items_holder .single_item").each(
        function (i, obj) {
          if (j == total_items) {
            previous_id += $(this).attr("id").substr(15);
          } else {
            previous_id += $(this).attr("id").substr(15) + ",";
          }
          j++;
        }
      );
      let previous_id_array = previous_id.split(",");
      previous_id_array.forEach(function (entry) {
        $("#detail_item_id_" + entry).attr("data-selected", "selected");
        $("#detail_item_id_" + entry).addClass("light-sky-background");
        $(
          "#detail_item_id_" +
            entry +
            " .single_item_right_side .single_item_cooking_status"
        ).css("color", "#fff");
        $(
          "#detail_item_id_" +
            entry +
            " .single_item_right_side .single_item_cooking_status"
        ).html("Preparing");
      });
      if (previous_id != "") {
        let url =
          base_url + "Bar/update_cooking_status_delivery_take_away_ajax";
        $.ajax({
          url: url,
          method: "POST",
          data: {
            previous_id: previous_id,
            cooking_status: "Started Preparing",
            csrf_irestoraplus: csrf_value_,
          },
          success: function (response) {
            swal({
              title: "Alert",
              text: "Preparing Started!!",
              confirmButtonColor: "#b6d6f6",
            });
          },
          error: function () {
            alert("error");
          },
        });
      }
    }
  });

  $(document).on("click", ".done_cooking", function () {
    let sale_id = $(this).attr("id").substr(13);
    if ($("#single_order_" + sale_id).attr("data-order-type") == "Dine In") {
      if (
        $(
          "#single_order_" +
            sale_id +
            " .items_holder .single_item[data-selected=selected]"
        ).length > 0
      ) {
        // let previous_id = $('#items_holder_of_order .single_item_in_order[data-selected=selected]').attr('id').substr(12);
        let previous_id = "";
        let j = 1;
        let total_items = $(
          "#single_order_" +
            sale_id +
            " .items_holder .single_item[data-selected=selected]"
        ).length;
        $(
          "#single_order_" +
            sale_id +
            " .items_holder .single_item[data-selected=selected]"
        ).each(function (i, obj) {
          if (j == total_items) {
            previous_id += $(this).attr("id").substr(15);
          } else {
            previous_id += $(this).attr("id").substr(15) + ",";
          }
          j++;
        });
        let previous_id_array = previous_id.split(",");
        previous_id_array.forEach(function (entry) {
          $("#detail_item_id_" + entry).attr("data-selected", "selected");
          $("#detail_item_id_" + entry).addClass("green-background");
          $("#detail_item_id_" + entry).removeClass("light-blue-background");
          $("#detail_item_id_" + entry).removeClass("light-sky-background");
          $(
            "#detail_item_id_" +
              entry +
              " .single_item_right_side .single_item_cooking_status"
          ).css("color", "#fff");
          $(
            "#detail_item_id_" +
              entry +
              " .single_item_right_side .single_item_cooking_status"
          ).html("Done");
        });
        if (previous_id != "") {
          let url = base_url + "Bar/update_cooking_status_ajax";
          $.ajax({
            url: url,
            method: "POST",
            data: {
              previous_id: previous_id,
              cooking_status: "Done",
              csrf_irestoraplus: csrf_value_,
            },
            success: function (response) {
              swal({
                title: "Alert",
                text: "Preparing Done!!",
                confirmButtonColor: "#b6d6f6",
              });
            },
            error: function () {
              alert("error");
            },
          });
        }
      } else {
        swal({
          title: "Alert!",
          text: "Please select an item to cooking item done!",
          confirmButtonColor: "#b6d6f6",
        });
      }
    } else {
      let previous_id = "";
      let j = 1;
      let total_items = $(
        "#single_order_" +
          sale_id +
          " .items_holder .single_item[data-selected=selected]"
      ).length;
      if (total_items > 0) {
        $(
          "#single_order_" +
            sale_id +
            " .items_holder .single_item[data-selected=selected]"
        ).each(function (i, obj) {
          if (j == total_items) {
            previous_id += $(this).attr("id").substr(15);
          } else {
            previous_id += $(this).attr("id").substr(15) + ",";
          }
          j++;
        });
        let previous_id_array = previous_id.split(",");
        previous_id_array.forEach(function (entry) {
          $("#detail_item_id_" + entry).attr("data-selected", "selected");
          $("#detail_item_id_" + entry).addClass("light-sky-background");
          $(
            "#detail_item_id_" +
              entry +
              " .single_item_right_side .single_item_cooking_status"
          ).css("color", "#fff");
          $(
            "#detail_item_id_" +
              entry +
              " .single_item_right_side .single_item_cooking_status"
          ).html("Done");
        });
        if (previous_id != "") {
          let url =
            base_url + "Bar/update_cooking_status_delivery_take_away_ajax";
          $.ajax({
            url: url,
            method: "POST",
            data: {
              previous_id: previous_id,
              cooking_status: "Done",
              csrf_irestoraplus: csrf_value_,
            },
            success: function (response) {
              swal({
                title: "Alert",
                text: "Preparing Done!!",
                confirmButtonColor: "#b6d6f6",
              });
            },
            error: function () {
              alert("error");
            },
          });
        }
      } else {
        swal({
          title: "Alert",
          text: "Please select an item to cooking item done!!",
          confirmButtonColor: "#b6d6f6",
        });
      }
    }
  });

  $(document).on("click", "#help_button", function () {
    $("#help_modal").fadeIn("500");
  });
  $(document).on("click", "#cross_button_to_close", function () {
    $("#help_modal").fadeOut("500");
  });
  $(document).on("click", "#select_all_items", function () {
    if (
      $("#order_details_holder .single_order[data-selected=selected]").attr(
        "data-order-type"
      ) == "Dine In"
    ) {
      $("#items_holder_of_order .single_item_in_order").css(
        "background-color",
        "#B5D6F6"
      );
      $("#items_holder_of_order .single_item_in_order").attr(
        "data-selected",
        "selected"
      );
    } else {
      swal({
        title: "Alert",
        text: "You don't need to select or deselect any item for take away or delivery, because you need to deliver all items in a pack",
        confirmButtonColor: "#b6d6f6",
      });
    }
  });
  $(document).on("click", "#deselect_all_items", function () {
    if (
      $("#order_details_holder .single_order[data-selected=selected]").attr(
        "data-order-type"
      ) == "Dine In"
    ) {
      $("#items_holder_of_order .single_item_in_order").css(
        "background-color",
        "#ffffff"
      );
      $("#items_holder_of_order .single_item_in_order").attr(
        "data-selected",
        "deselected"
      );
    } else {
      swal({
        title: "Alert",
        text: "You don't need to select or deselect any item for take away or delivery, because you need to deliver all items in a pack",
        confirmButtonColor: "#b6d6f6",
      });
    }
  });
  $(document).on(
    "click",
    "#items_holder_of_order .single_item_in_order",
    function () {
      if (
        $(
          '#items_holder_of_order .single_item_in_order[data-order-type="Dine In"]'
        ).length > 0
      ) {
        // $('.single_item_in_order').css('background-color','#ffffff');
        // $('.single_item_in_order').attr('data-selected','unselected');
        if ($(this).attr("data-selected") == "selected") {
          $(this).css("background-color", "#ffffff");
          $(this).attr("data-selected", "unselected");
        } else {
          $(this).css("background-color", "#B5D6F6");
          $(this).attr("data-selected", "selected");
        }
      }
    }
  );
  $(document).on("click", "#start_cooking", function () {
    if (
      $("#order_details_holder .single_order[data-selected=selected]").attr(
        "data-order-type"
      ) == "Dine In"
    ) {
      if (
        $(
          "#items_holder_of_order .single_item_in_order[data-selected=selected]"
        ).length > 0
      ) {
        // let previous_id = $('#items_holder_of_order .single_item_in_order[data-selected=selected]').attr('id').substr(12);
        let previous_id = "";
        let j = 1;
        let total_items = $(
          "#items_holder_of_order .single_item_in_order[data-selected=selected]"
        ).length;
        $(
          "#items_holder_of_order .single_item_in_order[data-selected=selected]"
        ).each(function (i, obj) {
          if (j == total_items) {
            previous_id += $(this).attr("id").substr(12);
          } else {
            previous_id += $(this).attr("id").substr(12) + ",";
          }
          j++;
        });
        let previous_id_array = previous_id.split(",");
        previous_id_array.forEach(function (entry) {
          $("#single_item_" + entry).attr("data-selected", "selected");
          $("#single_item_" + entry).css("background-color", "#B5D6F6");
        });
        if (previous_id != "") {
          $.ajax({
            url: base_url + "Bar/update_cooking_status_ajax",
            method: "POST",
            data: {
              previous_id: previous_id,
              cooking_status: "Started Preparing",
              csrf_irestoraplus: csrf_value_,
            },
            success: function (response) {
              swal({
                title: "Alert",
                text: "Preparing Started!!",
                confirmButtonColor: "#b6d6f6",
              });
            },
            error: function () {
              alert("error");
            },
          });
        }
      } else {
        swal({
          title: "Alert!",
          text: "Please select an item to Prepare!",
          confirmButtonColor: "#b6d6f6",
        });
      }
    } else {
      let previous_id = "";
      let j = 1;
      let total_items = $(
        "#items_holder_of_order .single_item_in_order"
      ).length;
      $("#items_holder_of_order .single_item_in_order").each(function (i, obj) {
        if (j == total_items) {
          previous_id += $(this).attr("id").substr(12);
        } else {
          previous_id += $(this).attr("id").substr(12) + ",";
        }
        j++;
      });
      let previous_id_array = previous_id.split(",");
      previous_id_array.forEach(function (entry) {
        $("#single_item_" + entry).attr("data-selected", "selected");
        $("#single_item_" + entry).css("background-color", "#B5D6F6");
      });
      if (previous_id != "") {
        $.ajax({
          url: base_url + "Bar/update_cooking_status_delivery_take_away_ajax",
          method: "POST",
          data: {
            previous_id: previous_id,
            cooking_status: "Started Preparing",
            csrf_irestoraplus: csrf_value_,
          },
          success: function (response) {
            swal({
              title: "Alert",
              text: "Preparing Started!!",
              confirmButtonColor: "#b6d6f6",
            });
          },
          error: function () {
            alert("error");
          },
        });
      }
    }
  });
  $(document).on("click", "#cooking_done", function () {
    if (
      $("#order_details_holder .single_order[data-selected=selected]").attr(
        "data-order-type"
      ) == "Dine In"
    ) {
      if (
        $(
          "#items_holder_of_order .single_item_in_order[data-selected=selected]"
        ).length > 0
      ) {
        // let previous_id = $('#items_holder_of_order .single_item_in_order[data-selected=selected]').attr('id').substr(12);
        let previous_id = "";
        let j = 1;
        let total_items = $(
          "#items_holder_of_order .single_item_in_order[data-selected=selected]"
        ).length;
        $(
          "#items_holder_of_order .single_item_in_order[data-selected=selected]"
        ).each(function (i, obj) {
          if (j == total_items) {
            previous_id += $(this).attr("id").substr(12);
          } else {
            previous_id += $(this).attr("id").substr(12) + ",";
          }
          j++;
        });
        let previous_id_array = previous_id.split(",");
        previous_id_array.forEach(function (entry) {
          $("#single_item_" + entry).attr("data-selected", "selected");
          $("#single_item_" + entry).css("background-color", "#B5D6F6");
        });
        if (previous_id != "") {
          $.ajax({
            url: base_url + "Bar/update_cooking_status_ajax",
            method: "POST",
            data: {
              previous_id: previous_id,
              cooking_status: "Done",
              csrf_irestoraplus: csrf_value_,
            },
            success: function (response) {
              swal({
                title: "Alert",
                text: "Preparing Done!!",
                confirmButtonColor: "#b6d6f6",
              });
            },
            error: function () {
              alert("error");
            },
          });
        }
      } else {
        swal({
          title: "Alert!",
          text: "Please select an item to cooking item done!",
          confirmButtonColor: "#b6d6f6",
        });
      }
    } else {
      let previous_id = "";
      let j = 1;
      let total_items = $(
        "#items_holder_of_order .single_item_in_order"
      ).length;
      $("#items_holder_of_order .single_item_in_order").each(function (i, obj) {
        if (j == total_items) {
          previous_id += $(this).attr("id").substr(12);
        } else {
          previous_id += $(this).attr("id").substr(12) + ",";
        }
        j++;
      });
      let previous_id_array = previous_id.split(",");
      previous_id_array.forEach(function (entry) {
        $("#single_item_" + entry).attr("data-selected", "selected");
        $("#single_item_" + entry).css("background-color", "#B5D6F6");
      });
      if (previous_id != "") {
        $.ajax({
          url: base_url + "Bar/update_cooking_status_delivery_take_away_ajax",
          method: "POST",
          data: {
            previous_id: previous_id,
            cooking_status: "Done",
            csrf_irestoraplus: csrf_value_,
          },
          success: function (response) {
            swal({
              title: "Alert",
              text: "Preparing Done!!",
              confirmButtonColor: "#b6d6f6",
            });
          },
          error: function () {
            alert("error");
          },
        });
      }
    }
  });
  $(document).on("click", "#order_details_holder .single_order", function () {
    let sale_id = $(this).attr("id").substr(13);
    $("#order_details_holder .single_order").attr(
      "data-selected",
      "unselected"
    );
    $("#order_details_holder .single_order").css("background-color", "#ffffff");
    $(this).attr("data-selected", "selected");
    $(this).css("background-color", "#b6d6f6");
    $("#selected_order_for_refreshing_help").html(sale_id);
    $.ajax({
      url: base_url + "Bar/get_order_details_bar_ajax",
      method: "POST",
      data: {
        sale_id: sale_id,
        csrf_irestoraplus: csrf_value_,
      },
      success: function (response) {
        response = JSON.parse(response);

        let order_type = "";
        if (response.order_type == "1") {
          order_type = "Dine In";
        } else if (response.order_type == "2") {
          order_type = "Take Away";
        } else if (response.order_type == "3") {
          order_type = "Delivery";
        }
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
          let backgroundForSingleItem = "";
          if (this_item.cooking_status == "Done") {
            backgroundForSingleItem = 'style="background-color:#598527;"';
          } else if (this_item.cooking_status == "Started Preparing") {
            backgroundForSingleItem = 'style="background-color:#0c5889;"';
          }

          draw_table_for_order +=
            "<div " +
            backgroundForSingleItem +
            ' data-order-type="' +
            order_type +
            '" data-selected="unselected" class="single_item_in_order fix floatleft" id="single_item_' +
            this_item.previous_id +
            '">';
          draw_table_for_order +=
            '<h3 class="item_name">' + this_item.menu_name + "</h3>";
          draw_table_for_order +=
            '<p class="item_qty">Qty: ' + this_item.qty + "</p>";
          draw_table_for_order +=
            '<p class="modifier_name">Modifiers: ' +
            selected_modifiers +
            "</p>";
          draw_table_for_order +=
            '<p class="note">Note: ' + this_item.menu_note + "</p>";
          draw_table_for_order += "</div>";
        }
        //empty order detail segment
        $("#items_holder_of_order").empty();
        //add to top
        $("#items_holder_of_order").prepend(draw_table_for_order);
      },
      error: function () {
        alert("error");
      },
    });
  });

  //this is to set height when site load
  window.height_should_be =
    parseInt($(window).height()) - parseInt($(".top").height());
  $(".bottom_left").css("height", height_should_be + "px");
  $(".bottom_right").css("height", height_should_be + "px");
  //end

  $(document).on("click", "#notification_button", function () {
    // $("#notification_button").css("background-color", "#F3F3F3");
    // $("#notification_button").css("color", "buttontext");
    $("#notification_list_modal").fadeIn("500");
  });
  $(document).on("click", "#notification_close", function () {
    $("#notification_list_modal").fadeOut("500");
    $(".single_notification_checkbox").prop("checked", false);
    $("#select_all_notification").prop("checked", false);
  });
  $(document).on("click", "#notification_remove_all", function () {
    if ($(".single_notification_checkbox:checked").length > 0) {
      let r = confirm("Are you sure to delete all notifications?");
      if (r == false) {
        return false;
      }
      let notifications = "";
      let j = 1;
      let checkbox_length = $(".single_notification_checkbox:checked").length;
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
          url: base_url + "Bar/remove_multiple_notification_ajax",
          method: "POST",
          data: {
            notifications: notifications,
            csrf_irestoraplus: csrf_value_,
          },
          success: function (response) {
            // $('#single_notification_row_'+response).remove();
          },
          error: function () {
            alert("error");
          },
        });
      }
    } else {
      swal({
        title: "Alert",
        text: "No notification is selected",
        confirmButtonColor: "#b6d6f6",
      });
    }
  });
  $(document).on("click", ".single_serve_b", function () {
    let notification_id = $(this).attr("id").substr(26);
    $.ajax({
      url: base_url + "Bar/remove_notication_ajax",
      method: "POST",
      data: {
        notification_id: notification_id,
        csrf_irestoraplus: csrf_value_,
      },
      success: function (response) {
        $("#single_notification_row_" + response).remove();
      },
      error: function () {
        alert("error");
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
});
// ==================================================
$(window).on("resize", function () {
  window.height_should_be =
    parseInt($(window).height()) - parseInt($(".top").height());
  $(".bottom_left").css("height", height_should_be + "px");
  $(".bottom_right").css("height", height_should_be + "px");
});
// =============================================
$(".all_order_holder")
  .slimscroll({
    height: "99.5%",
  })
  .parent()
  .css({
    background: "#f5f5f5",
    border: "0px solid #184055",
  });
$("#items_holder_of_order")
  .slimscroll({
    height: "430px",
  })
  .parent()
  .css({
    background: "#f5f5f5",
    border: "0px solid #184055",
  });

setInterval(function () {
  if ($("#refresh_it_or_not").html() == "Yes") {
    refresh_orders();
  }
  new_notification_interval();
}, 15000);
new_notification_interval();
setInterval(function () {
  $("#order_details_holder .single_order").each(function (i, obj) {
    let order_id = $(this).attr("id").substr(13);
    let minutes = $("#ordered_minute_" + order_id).html();
    let seconds = $("#ordered_second_" + order_id).html();
    upTime($(this), minutes, seconds);
  });
}, 1000);

function upTime(object, minute, second) {
  order_id = object.attr("id").substr(13);
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
  $("#ordered_minute_" + order_id).html(minute);
  $("#ordered_second_" + order_id).html(second);

  // upTime2.to=setTimeout(function(){ upTime2(object,second,minute,hour); },1000);
}
function new_notification_interval() {
  $.ajax({
    url: base_url + "Bar/get_new_notifications_ajax",
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

      // $order_list_left = '';
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
        notifications_list += '<div class="single_serve_button">';
        notifications_list +=
          '<button class="single_serve_b btn bg-blue-btn" id="notification_serve_button_' +
          this_notification.id +
          '">Delete</button>';
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
refresh_orders();
function refresh_orders() {
  let url = base_url + "Bar/get_new_orders_ajax";
  $("#refresh_it_or_not").html("Yes");
  $.ajax({
    url: url,
    method: "POST",
    data: {
      outlet_id: window.localStorage.getItem("ls_outlet_id"),
      csrf_irestoraplus: csrf_value_,
    },
    success: function (response) {
      window.order_items = [];
      response = JSON.parse(response);
      $order_list_left = "";
      let i = 1;
      for (let key in response) {
        // if(i==1){
        // $order_list_left += '<div class="single_order fix" style="margin-top:0px" data-selected="unselected" id="single_order_'+response[key].sales_id+'">';
        // }else{
        let order_name = "";
        let order_type = "";
        if (response[key].order_type == "1") {
          order_name = "A " + response[key].sale_no;
          order_type = "Dine In";
        } else if (response[key].order_type == "2") {
          order_name = "B " + response[key].sale_no;
          order_type = "Take Away";
        } else if (response[key].order_type == "3") {
          order_name = "C " + response[key].sale_no;
          order_type = "Delivery";
        }
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

        let selected_unselected =
          $("#selected_order_for_refreshing_help").html() ==
          response[key].sales_id
            ? "selected"
            : "unselected";
        let selected_background =
          $("#selected_order_for_refreshing_help").html() ==
          response[key].sales_id
            ? ' style="background-color:#b6d6f6" '
            : "";
        let width = 100;
        let total_bar_type_items = response[key].total_bar_type_items;
        let total_bar_type_started_cooking_items =
          response[key].total_bar_type_started_cooking_items;
        let total_bar_type_done_items = response[key].total_bar_type_done_items;
        let splitted_width = (
          parseFloat(width) / parseFloat(total_bar_type_items)
        ).toFixed(2);
        let percentage_for_started_cooking = (
          parseFloat(splitted_width) *
          parseFloat(total_bar_type_started_cooking_items)
        ).toFixed(2);
        let percentage_for_done_cooking = (
          parseFloat(splitted_width) * parseFloat(total_bar_type_done_items)
        ).toFixed(2);

        // $order_list_left += '<div class="background_order_started" style="width:'+percentage_for_started_cooking+'%"></div>';
        // $order_list_left += '<div class="background_order_done" style="width:'+percentage_for_done_cooking+'%"></div>';
        // }

        let table_name =
          response[key].table_name != null ? response[key].table_name : "";
        let waiter_name =
          response[key].waiter_name != null ? response[key].waiter_name : "";
        let customer_name =
          response[key].customer_name != null
            ? response[key].customer_name
            : "";
        let booked_time = new Date(Date.parse(response[key].date_time));
        let now = new Date();

        let days = parseInt((now - booked_time) / (1000 * 60 * 60 * 24));
        let hours = parseInt(
          (Math.abs(now - booked_time) / (1000 * 60 * 60)) % 24
        );
        let minute = parseInt(
          (Math.abs(now.getTime() - booked_time.getTime()) / (1000 * 60)) % 60
        );
        let second = parseInt(
          (Math.abs(now.getTime() - booked_time.getTime()) / 1000) % 60
        );
        minute = minute.toString();
        second = second.toString();
        minute = minute.length == 1 ? "0" + minute : minute;
        second = second.length == 1 ? "0" + second : second;

        if (total_bar_type_items != total_bar_type_done_items) {
          $order_list_left +=
            '<div class="fix floatleft single_order" data-order-type="' +
            order_type +
            '" data-selected="' +
            selected_unselected +
            '" id="single_order_' +
            response[key].sales_id +
            '">';
          $order_list_left +=
            '<div class="header_portion light-blue-background fix">';
          $order_list_left += '<div class="fix floatleft half">';
          $order_list_left +=
            '<p class="order_number">Invoice: ' + order_name + "</p> ";
          $order_list_left +=
            '<p class="order_number">Table: ' + tables_booked + "</p> ";
          $order_list_left += "</div>";
          $order_list_left += '<div class="fix floatleft half">';
          $order_list_left +=
            '<p class="order_duration dark-blue-background"><span id="bar_time_minute_' +
            response[key].sales_id +
            '">' +
            minute +
            '</span>:<span id="bar_time_second_' +
            response[key].sales_id +
            '">' +
            second +
            "</span></p>";
          $order_list_left += "</div>";
          $order_list_left += "</div>";
          $order_list_left += '<div class="fix items_holder">';
          let items = response[key].items;
          for (let key_item in items) {
            let single_item = items[key_item];
            let searched_found = searchItems(single_item.menu_name);
            if (searched_found.length == 0) {
              window.order_items.push(single_item);
            }
            let item_background = "";
            let font_style = "";
            let cooking_status = "";
            if (single_item.cooking_status == "Done") {
              item_background = "green-background";
              font_style = "color:#fff;";
              cooking_status = "Done";
            } else if (single_item.cooking_status == "Started Preparing") {
              item_background = "light-blue-background";
              font_style = "color:#fff;";
              cooking_status = "Preparing";
            }

            $order_list_left +=
              '<div data-selected="unselected" class="fix single_item ' +
              item_background +
              '" data-order-id="' +
              response[key].sales_id +
              '" data-item-id="' +
              single_item.previous_id +
              '" id="detail_item_id_' +
              single_item.previous_id +
              '" data-cooking-status="' +
              single_item.cooking_status +
              '">';
            $order_list_left += '<div class="single_item_left_side fix">';
            $order_list_left += '<div class="fix floatleft item_quantity">';
            $order_list_left +=
              '<p class="item_quanity_text" style="' +
              font_style +
              '">' +
              single_item.qty +
              "</p>";
            $order_list_left += "</div>";
            $order_list_left += '<div class="fix floatleft item_detail">';
            $order_list_left +=
              '<p class="item_name" style="' +
              font_style +
              '">' +
              single_item.menu_name +
              "</p>";
            $order_list_left +=
              '<p class="item_qty" style="font-weight:bold; ' +
              font_style +
              '">Qty: ' +
              single_item.qty +
              "</p>";

            let modifiers = single_item.modifiers;
            modifiers_length = modifiers.length;
            let w = 1;
            let modifiers_name = "";
            for (let key_modifier in modifiers) {
              if (w == modifiers_length) {
                modifiers_name += modifiers[key_modifier].name;
              } else {
                modifiers_name += modifiers[key_modifier].name + ", ";
              }
              w++;
            }
            if (modifiers_length > 0) {
              $order_list_left +=
                '<p class="modifiers" style="' +
                font_style +
                '">- ' +
                modifiers_name +
                "</p>";
            }
            if (single_item.menu_note != "") {
              $order_list_left +=
                '<p class="note" style="' +
                font_style +
                '">- ' +
                single_item.menu_note +
                "</p>";
            }
            $order_list_left += "</div>";
            $order_list_left += "</div>";
            $order_list_left += '<div class="single_item_right_side fix">';
            $order_list_left +=
              '<p class="single_item_cooking_status" style="' +
              font_style +
              '">' +
              cooking_status +
              "</p>";
            $order_list_left += "</div>";
            $order_list_left += "</div>";
          }

          $order_list_left += "</div>";
          $order_list_left +=
            '<div class="single_order_button_holder" id="single_order_button_holder_' +
            response[key].sales_id +
            '">';
          $order_list_left +=
            '<button class="select_all_of_an_order" id="select_all_of_an_order_' +
            response[key].sales_id +
            '">Select All</button><button class="unselect_all_of_an_order" id="unselect_all_of_an_order_' +
            response[key].sales_id +
            '">Unselect All</button><button class="start_cooking_button" id="start_cooking_button_' +
            response[key].sales_id +
            '">Prepare</button><button class="done_cooking" id="done_cooking_' +
            response[key].sales_id +
            '">Done</button>';
          $order_list_left += "</div>";
          $order_list_left += "</div>";
        }
        i++;
      }
      $("#order_holder").html($order_list_left);

      $("#order_holder.order_holder .single_order .items_holder")
        .slimscroll({
          height: "199px",
        })
        .parent()
        .css({
          background: "#fff",
          border: "0px solid #184055",
        });
      /*******************************************************************************************************
       ****************** Construct option for group order by item end ****************************************
       *******************************************************************************************************************************************************************/
      let group_order_by_item_option =
        '<select id="group_by_order_item" class="group_by_order_item">';
      group_order_by_item_option += '<option value="">Select Item</option>';
      for (let key in window.order_items) {
        let single_ordered_item = window.order_items[key];
        group_order_by_item_option +=
          '<option value="' +
          single_ordered_item.food_menu_id +
          '">' +
          single_ordered_item.menu_name +
          "</option>";
      }
      group_order_by_item_option += "</select>";

      $("#group_by_order_item_holder").html(group_order_by_item_option);
      $("#group_by_order_item").select2({ dropdownCssClass: "bigdrop" });
      /*******************************************************************************************************
       ****************** Construct order list end ************************************************************
       ********************************************************************************************************/
    },
    error: function () {
      console.log("New order refresh error");
    },
  });

  $(".select2").select2();

  $.datable();
  function searchItems(searchedValue) {
    let resultObject = search(searchedValue, window.order_items);
    return resultObject;
  }

  function search(nameKey, myArray) {
    let foundResult = new Array();
    for (let i = 0; i < myArray.length; i++) {
      if (myArray[i].menu_name.toLowerCase().includes(nameKey.toLowerCase())) {
        foundResult.push(myArray[i]);
      }
    }
    return foundResult.sort(function (a, b) {
      return parseInt(b.sold_for) - parseInt(a.sold_for);
    });
  }
}

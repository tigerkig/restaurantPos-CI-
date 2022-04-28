$(document).ready(function () {
  "use strict";
  $(".datatable").DataTable({
    paging: false,
    autoWidth: false,
    ordering: true,
    order: [[3, "desc"]],
    dom: '<"top-left-item col-sm-12 col-md-6"lf> <"top-right-item col-sm-12 col-md-6"B> t <"bottom-left-item col-sm-12 col-md-6 "i><"bottom-right-item col-sm-12 col-md-6 "p>',
    buttons: [
      {
        extend: "print",
        text: '<i class="fa fa-print"></i> Print',
        titleAttr: "print",
      },
      {
        extend: "copyHtml5",
        text: '<i class="fa fa-files-o"></i> Copy',
        titleAttr: "Copy",
      },
      {
        extend: "excelHtml5",
        text: '<i class="fa fa-file-excel-o"></i> Excel',
        titleAttr: "Excel",
      },
      {
        extend: "csvHtml5",
        text: '<i class="fa fa-file-text-o"></i> CSV',
        titleAttr: "CSV",
      },
      {
        extend: "pdfHtml5",
        text: '<i class="fa fa-file-pdf-o"></i> PDF',
        titleAttr: "PDF",
      },
    ],
    language: {
      paginate: {
        previous: "<i class='fa fa-chevron-left'></i>",
        next: "<i class='fa fa-chevron-right'></i>",
      },
    },
  });
  // Check or Uncheck All checkboxes
  $(document).on("click", ".checkbox_userAll", function (e) {
    let checked = $(this).is(":checked");
    if (checked) {
      $(".checkbox_user").each(function () {
        let menu_id = $(this).attr("data-menu_id");
        $(this).prop("checked", true);
        $("#qty" + menu_id).val(1);
        $("#qty" + menu_id).prop("disabled", false);
      });
      $(".checkbox_userAll").prop("checked", true);
    } else {
      $(".checkbox_user").each(function () {
        let menu_id = $(this).attr("data-menu_id");
        $(this).prop("checked", false);
        $("#qty" + menu_id).prop("disabled", true);
        $("#qty" + menu_id).val("");
      });
      $(".checkbox_userAll").prop("checked", false);
    }
  });

  $(document).on("click", ".checkbox_user", function (e) {
    let menu_id = $(this).attr("data-menu_id");
    if ($(".checkbox_user").length == $(".checkbox_user:checked").length) {
      $(".checkbox_userAll").prop("checked", true);
      if ($(this).is(":checked")) {
        $("#qty" + menu_id).val(1);
        $("#qty" + menu_id).prop("disabled", false);
      } else {
        $("#qty" + menu_id).prop("disabled", true);
        $("#qty" + menu_id).val("");
      }
    } else {
      $(".checkbox_userAll").prop("checked", false);
      if ($(this).is(":checked")) {
        $("#qty" + menu_id).val(1);
        $("#qty" + menu_id).prop("disabled", false);
      } else {
        $("#qty" + menu_id).prop("disabled", true);
        $("#qty" + menu_id).val("");
      }
    }
  });
});

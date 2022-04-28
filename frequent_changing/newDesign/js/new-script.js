(function ($) {
  "use strict";
  const win = $(window);
  const bodyElement = $("body");
  /**
   * Perfect Scrollbar
   */
  const ps = new PerfectScrollbar(".sidebar-menu", {
    wheelSpeed: 2,
    wheelPropagation: true,
    minScrollbarLength: 20,
  });

  ps.update();

  /**
   * Active ToolTips
   */
  let tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  let tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  /***
   * Hide Preloader
   */

  win.on("load", () => {
    setTimeout(() => {
      $(".main-preloader").fadeOut(500);
    }, 500);
  });

  /**
   * Click to show Menus for mobile devices
   */
  bodyElement.on("click", ".om", function () {
    bodyElement.find(".screen-list").addClass("active");
  });
  $(document).on("click", function (event) {
    if ($(event.target).closest(".om").length === 0) {
      bodyElement.find(".screen-list").removeClass("active");
    }
  });

  /**
   * Open Sidebar When Active Arabic Language
   */
  bodyElement.on("click", ".st", function () {
    bodyElement.find(".main-sidebar2").addClass("active");
  });
  $(document).on("click", function (e) {
    if (
      $(e.target).closest(".st").length === 0 &&
      $(e.target).closest(".sidebar-menu").length === 0
    ) {
      bodyElement.find(".main-sidebar2").removeClass("active");
    }
  });

  /**
   * Language Dropdown
   */
  bodyElement.on("click", ".lang-dropdown-menu li", function () {
    bodyElement
      .find(".show-drop-result")
      .html(
        `<i class="currency-flag currency-flag-${$(this).attr(
          "data-icon"
        )}"></i> <span>${$(this).attr("data-lang")}</span>`
      );
  });
})(jQuery);

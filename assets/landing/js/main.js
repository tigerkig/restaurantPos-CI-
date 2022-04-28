(function ($) {
  "use strict";
  feather.replace();
  const bodyElement = $("body");

  //   All Anchor Tag Target
  $('a[href="#"]').attr("href", "javascript:void(0)");

  /**
   * Features Slider
   */

  const leftArrow = `
  <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><polyline points="15 18 9 12 15 6"></polyline></svg>
  `;
  const rightArrow = `
  <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><polyline points="9 18 15 12 9 6"></polyline></svg>
  `;
  $(".feature-slider").owlCarousel({
    nav: true,
    items: 4,
    margin: 20,
    dots: false,
    navContainer: ".navItemContainer",
    navText: [leftArrow, rightArrow],
    responsiveClass: true,
    responsive: {
      1000: {
        items: 4,
      },
      768: {
        items: 3,
      },
      500: {
        items: 2,
      },
      0: {
        items: 1,
      },
    },
  });

  /**
   * Review Slider
   */
  $(".review-slider").owlCarousel({
    nav: true,
    items: 1,
    margin: 20,
    dots: false,
    navText: [leftArrow, rightArrow],
  });

  /**
   * Review Slider
   */
  $(".gallery-slider").owlCarousel({
    nav: true,
    items: 4,
    margin: 20,
    loop: true,
    dots: false,
    center: true,
    navText: [leftArrow, rightArrow],
    responsiveClass: true,
    responsive: {
      1000: {
        items: 4,
      },
      768: {
        items: 3,
      },
      500: {
        items: 2,
      },
      0: {
        items: 1,
      },
    },
  });

  /**
   * Hamburgar Menu
   */

  bodyElement.on("click", ".menu-trigger", function () {
    $(this).addClass("active");
    $("#sidebar").addClass("active");
    $(".overlay").fadeIn(300);
    bodyElement.css("overflow", "hidden");
  });

  bodyElement.on("click", ".overlay", function () {
    $(this).fadeOut(300);
    $("#sidebar").removeClass("active");
    $(".menu-trigger").removeClass("active");
    bodyElement.css("overflow", "visible");
  });

  /**
   * To Top
   */
  $(window).on("scroll", function () {
    if ($(window).scrollTop() > 400) {
      $(".totop").addClass("active");
    } else {
      $(".totop").removeClass("active");
    }
  });

  $(".image-popup-no-margins").magnificPopup({
    type: "image",
  });
  // $(".popup-youtube").magnificPopup({
  //   disableOn: 700,
  //   type: "iframe",
  //   mainClass: "mfp-fade",
  //   removalDelay: 160,
  //   preloader: false,
  //   fixedContentPos: false,
  // });

  $(".popup-youtube").magnificPopup({
    type: "iframe",
    iframe: {
      markup:
        '<div class="mfp-iframe-scaler">' +
        '<div class="mfp-close"></div>' +
        '<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>' +
        "</div>", // HTML markup of popup, `mfp-close` will be replaced by the close button

      patterns: {
        youtube: {
          index: "youtube.com/", // String that detects type of video (in this case YouTube). Simply via url.indexOf(index).

          id: "v=", // String that splits URL in a two parts, second part should be %id%
          // Or null - full URL will be returned
          // Or a function that should return %id%, for example:
          // id: function(url) { return 'parsed id'; }

          src: "https://www.youtube.com/embed/%id%?autoplay=", // URL that will be set as a source for iframe.
        },
        vimeo: {
          index: "vimeo.com/",
          id: "/",
          src: "//player.vimeo.com/video/%id%?autoplay=1",
        },
        gmaps: {
          index: "//maps.google.",
          src: "%id%&output=embed",
        },

        // you may add here more sources
      },

      srcAction: "iframe_src", // Templating object key. First part defines CSS selector, second attribute. "iframe_src" means: find "iframe" and set attribute "src".
    },
  });
})(jQuery);

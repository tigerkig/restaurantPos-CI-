; (function ($) {
    "use strict";

    $(document).ready(function () {
        //send email
        let base_url_ajax_contact_us = $("#base_url_ajax").val();
        $(document).on("click", ".send_mail", function (e) {
            e.preventDefault();
            let status = false;
            let focus = 1;
            let name = $("#name").val();
            let phone = $("#phone").val();
            let subject = $("#subject").val();
            let message = $("#message").val();
            //set all border un required
            $(".required_check").css("border","1px solid #e5e5e5 !important");

            if(name==''){
                status = true;
                if(focus==1){
                    $(this).focus();
                    focus++;
                }
                swal({
                    title: "Alert!",
                    text: "Name field is required!",
                    confirmButtonText: "OK",
                    confirmButtonColor: "#0d6efd"
                });
                $(this).css("border","2px solid #c53d3d !important");
            }else if(phone==''){
                status = true;
                if(focus==1){
                    $(this).focus();
                    focus++;
                }
                swal({
                    title: "Alert!",
                    text: "Phone field is required!",
                    confirmButtonText: "OK",
                    confirmButtonColor: "#0d6efd"
                });
                $(this).css("border","2px solid #c53d3d !important");
            }else if(subject==''){
                status = true;
                if(focus==1){
                    $(this).focus();
                    focus++;
                }
                swal({
                    title: "Alert!",
                    text: "Subject field is required!",
                    confirmButtonText: "OK",
                    confirmButtonColor: "#0d6efd"
                });
                $(this).css("border","2px solid #c53d3d !important");
            }else if(message==''){
                status = true;
                if(focus==1){
                    $(this).focus();
                    focus++;
                }
                swal({
                    title: "Alert!",
                    text: "Message field is required!",
                    confirmButtonText: "OK",
                    confirmButtonColor: "#0d6efd"
                });
                $(this).css("border","2px solid #c53d3d !important");
            }

            if(status==false){
                var data = $("form#contact_us_form").serialize();
                // use jQuery ajax
                let hidden_alert = $("#hidden_alert").val();
                let hidden_ok = $("#hidden_ok").val();
                $.ajax({
                    url:base_url_ajax_contact_us+'send-email',
                    method:"POST",
                    data: data,
                    dataType:'json',
                    success:function(data){
                        if(data.status==true){
                            $("#name").val('');
                            $("#phone").val('');
                            $("#subject").val('');
                            $("#message").val('');
                        }
                        swal({
                            title: hidden_alert,
                            text: data.msg,
                            confirmButtonText: hidden_ok,
                            confirmButtonColor: "#0d6efd"
                        });
                    }
                });
            }else{
                return false;
            }
        });

        /**-----------------------------
         *  Navbar fix
         * ---------------------------*/
        $(document).on('click', '.navbar-area .navbar-nav li.menu-item-has-children>a', function (e) {
            e.preventDefault();
        })
       
        /*-------------------------------------
            menu
        -------------------------------------*/
        $('.navbar-area .menu').on('click', function() {
            $(this).toggleClass('open');
            $('.navbar-area .navbar-collapse').toggleClass('sopen');
        });

        $('.show_vew').magnificPopup({
            type: 'image'
            // other options
        });

        //check validation email
        function isEmail(email) {
            let regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            return regex.test(email);
        }

        $(document).on("click", ".send_subscribe", function (e) {
            e.preventDefault();
            let base_url_ajax = $("#base_url_ajax").val();
            let status = false;
            let email_send_subscribe = $("#email_send_subscribe").val();
            let hidden_alert = $("#hidden_alert").val();
            let hidden_ok = $("#hidden_ok").val();
            if(!isEmail(email_send_subscribe)){
                status = true;
                $("#email_send_subscribe").focus();
                swal({
                    title: hidden_alert,
                    text: "Please enter valid email address!",
                    confirmButtonText: hidden_ok,
                    confirmButtonColor: "#ab53f9",
                });
            }
            if(status==false){
                // use jQuery ajax
                $.ajax({
                    url:base_url_ajax+'send-subscribe_email',
                    method:"POST",
                    data: {email_send_subscribe:email_send_subscribe},
                    dataType:'json',
                    success:function(data){
                        if(data.status==true){
                            $("#email_send_subscribe").val('');
                        }
                        swal({
                            title: hidden_alert,
                            text: data.msg,
                            confirmButtonText: hidden_ok,
                            confirmButtonColor: "#ab53f9",
                        });
                    }
                });
            }else{
                return false;
            }
        });

        // mobile menu
        if ($(window).width() < 992) {
            $(".in-mobile").clone().appendTo(".sidebar-inner");
            $(".in-mobile ul li.menu-item-has-children").append('<i class="fas fa-chevron-right"></i>');
            $('<i class="fas fa-chevron-right"></i>').insertAfter("");

            $(".menu-item-has-children a").on('click', function(e) {
                // e.preventDefault();

                $(this).siblings('.sub-menu').animate({
                    height: "toggle"
                }, 300);
            });
        }

        var menutoggle = $('.menu-toggle');
        var mainmenu = $('.navbar-nav');
        
        menutoggle.on('click', function() {
            if (menutoggle.hasClass('is-active')) {
                mainmenu.removeClass('menu-open');
            } else {
                mainmenu.addClass('menu-open');
            }
        });

        /*--------------------------------------------------
            select onput
        ---------------------------------------------------*/
        if ($('.single-select').length){
            $('.single-select').niceSelect();
        }

        /* --------------------------------------------------
            isotop filter 
        ---------------------------------------------------- */
        var $Container = $('.isotop-filter-area');
        if ($Container.length > 0) {
            $('.property-filter-area').imagesLoaded(function () {
                var festivarMasonry = $Container.isotope({
                    itemSelector: '.project-filter-item', // use a separate class for itemSelector, other than .col-
                    masonry: {
                        gutter: 0
                    }
                });
                $(document).on('click', '.isotop-filter-menu > button', function () {
                    var filterValue = $(this).attr('data-filter');
                    festivarMasonry.isotope({
                        filter: filterValue
                    });
                });
            });
            $(document).on('click','.isotop-filter-menu > button' , function () {
                $(this).siblings().removeClass('active');
                $(this).addClass('active');
            });
        }

        /* -----------------------------------------------------
            Variables
        ----------------------------------------------------- */
        var leftArrow = '<i class="fa fa-angle-left"></i>';
        var rightArrow = '<i class="fa fa-angle-right"></i>';

        /*------------------------------------------------
            feature-slider
        ------------------------------------------------*/
        $('.feature-slider').owlCarousel({
            loop: true,
            margin: 30,
            nav: false,
            dots: false,
            smartSpeed:1500,
            navText: [ leftArrow, rightArrow],
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 2
                },
                992: {
                    items: 3
                },
            }
        });

        /*------------------------------------------------
            review-slider
        ------------------------------------------------*/
        $('.review-slider').owlCarousel({
            loop: true,
            nav: false,
            dots: false,
            smartSpeed:1500,
            navText: [ leftArrow, rightArrow],
            items: 1,
        });

        /*------------------------------------------------
            gallery-slider
        ------------------------------------------------*/
        $('.gallery-slider').owlCarousel({
            loop: true,
            nav: false,
            dots: false,
            stagePadding: 130,
            smartSpeed:1500,
            center: true,
            mouseDrag: false,
            navText: [ leftArrow, rightArrow],
            responsive: {
                0: {
                    items: 1,
                    stagePadding: 0,
                },
                576: {
                    items: 1
                },
                992: {
                    items: 3,
                    stagePadding: 0,
                },
                1200: {
                    items: 3
                },
            }
        });

        /*------------------------------------------------
            client-slider
        ------------------------------------------------*/
        $('.client-slider').owlCarousel({
            loop: true,
            margin: 30,
            nav: false,
            dots: false,
            smartSpeed:1500,
            navText: [ leftArrow, rightArrow],
            responsive: {
                0: {
                    items: 2
                },
                576: {
                    items: 3
                },
                992: {
                    items: 4
                },
            }
        });

        /*------------------------------------------------
            testimonial-slider
        ------------------------------------------------*/
        $('.testimonial-slider').owlCarousel({
            loop: true,
            margin: 30,
            nav: true,
            dots: false,
            smartSpeed:1500,
            items: 1,
            navText: [ leftArrow, rightArrow],
        });


        /**---------------------------------------
          Progress BAR
        ----------------------------------------*/
        jQuery(window).on('scroll', function() {
            var windowHeight = $(window).height();
            function td_Progress() {
               var progress = $('.progress-rate');
               var len = progress.length;
                for (var i = 0; i < len; i++) {
                   var progressId = '#' + progress[i].id;
                   var dataValue = $(progressId).attr('data-value');
                   $(progressId).css({'width':dataValue+'%'});
                }
            }
            var progressRateClass = $('#progress-running');
             if ((progressRateClass).length) {
                var progressOffset = $("#progress-running").offset().top - windowHeight;
                if ($(window).scrollTop() > progressOffset) {
                    td_Progress();
                }
            }
            $('.counting').each(function() {
                var $this = $(this),
                countTo = $this.attr('data-count');
              
                $({ countNum: $this.text()}).animate({
                    countNum: countTo
                },

                {
                    duration: 2000,
                    easing:'linear',
                    step: function() {
                        $this.text(Math.floor(this.countNum));
                    },
                    complete: function() {
                        $this.text(this.countNum);
                    }
                });  
            });
        });

        /*------------------------------------------------
            Magnific JS
        ------------------------------------------------*/
        $('.video-play-btn').magnificPopup({
            type: 'iframe',
            removalDelay: 260,
            mainClass: 'mfp-zoom-in',
        });
        $.extend(true, $.magnificPopup.defaults, {
            iframe: {
                patterns: {
                    youtube: {
                        index: 'youtube.com/',
                        id: 'v=',
                        src: 'https://www.youtube.com/embed/Wimkqo8gDZ0'
                    }
                }
            }
        });

        /* -----------------------------------------
            fact counter
        ----------------------------------------- */
        $('.counter').counterUp({
            delay: 15,
            time: 2000
        });


        /*----------------------------------------
           back to top
        ----------------------------------------*/
        $(document).on('click', '.back-to-top', function () {
            $("html,body").animate({
                scrollTop: 0
            }, 2000);
        });

    });

    $(window).on("scroll", function() {
        /*---------------------------------------
        sticky menu activation && Sticky Icon Bar
        -----------------------------------------*/
        var mainMenuTop = $(".navbar-area");
        if ($(window).scrollTop() >= 1) {
            mainMenuTop.addClass('navbar-area-fixed');
        }
        else {
            mainMenuTop.removeClass('navbar-area-fixed');
        }
        
        var ScrollTop = $('.back-to-top');
        if ($(window).scrollTop() > 1000) {
            ScrollTop.fadeIn(1000);
        } else {
            ScrollTop.fadeOut(1000);
        }
    });


    $(window).on('load', function () {

        /*-----------------
            preloader
        ------------------*/
        var preLoder = $("#preloader");
        preLoder.fadeOut(0);

        /*-----------------
            back to top
        ------------------*/
        var backtoTop = $('.back-to-top')
        backtoTop.fadeOut();

        /*---------------------
            Cancel Preloader
        ----------------------*/
        $(document).on('click', '.cancel-preloader a', function (e) {
            e.preventDefault();
            $("#preloader").fadeOut(2000);
        });

    });



})(jQuery);
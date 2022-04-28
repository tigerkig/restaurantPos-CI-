$(function() {
    "use strict";
    $('.select2').select2();
    $(document).on('keydown', '.integerchk', function(e) {
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

    $(document).on('keyup', '.integerchk', function(e) {
        let input = $(this).val();
        let ponto = input.split('.').length;
        let slash = input.split('-').length;
        if (ponto > 2)
            $(this).val(input.substr(0, (input.length) - 1));
        $(this).val(input.replace(/[^0-9]/, ''));
        if (slash > 2)
            $(this).val(input.substr(0, (input.length) - 1));
        if (ponto == 2)
            $(this).val(input.substr(0, (input.indexOf('.') + 3)));
        if (input == '.')
            $(this).val("");

    });

    $(document).on('change', '#plan_id', function(e) {
       let trail_days = Number($("#plan_id option:selected").attr('data-trail_days'));
       let total_amount = Number($("#plan_id option:selected").attr('data-total_amount'));
       let plan_name = ($("#plan_id option:selected").attr('data-plan_name'));
        //for amount after select plan
       $("#total_payable_str").val(total_amount);
       $("#total_payable").val(total_amount);
       $("#paypalAmt").val(total_amount);

       $("#item_description_str").val(plan_name);
       $("#item_name").val("Monthly payment for "+plan_name);
       $("#item_name_recurring").val("Monthly payment for "+plan_name);
       $("#item_description_str_paypal").val("Monthly payment for "+plan_name);


       if(trail_days===111){
           $("#is_trail_plan").val("No");
           $(".hide_div_for_free").show();
       }else{
           $("#is_trail_plan").val("Yes");
           $(".hide_div_for_free").hide();
       }
    });
    //check valid email address
    function validateEmail(email) {
        let re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

    $(document).on('change', '#payment_type', function(e) {
        let this_value = $(this).val();
        if(Number(this_value)===2){
            let value = 2;
            $("#payment_method option[value=" + value + "]").hide();
             value = 3;
            $("#payment_method option[value=" + value + "]").hide();
            $("#payment_method").val(1);
        }else{
            let value = 2;
            $("#payment_method option[value=" + value + "]").show();
            value = 3;
            $("#payment_method option[value=" + value + "]").show();
        }
    });
    $(document).on('click', '.payment_now', function(e) {
        e.preventDefault();
        let business_name = $("#business_name").val();
        let phone = $("#phone").val();
        let address = $("#address").val();
        let admin_name = $("#admin_name").val();
        let email = $("#email").val();
        let password = $("#password").val();
        let confirm_password = $("#confirm_password").val();
        let payment_method = $("#payment_method").val();
        let payment_type = $("#payment_type").val();
        let plan_id = $("#plan_id").val();
        let base_url = $("#base_url").val();
        let is_trail_plan = $("#is_trail_plan").val();
        //error list
        let warning = $("#warning").val();
        let please_select_payment_type = $("#please_select_payment_type").val();
        let please_select_payment_method = $("#please_select_payment_method").val();
        let Select_You_Plan = $("#Select_You_Plan").val();
        let ok = $("#ok").val();
        let front_r_1 = $("#front_r_1").val();
        let front_r_2 = $("#front_r_2").val();
        let front_r_3 = $("#front_r_3").val();
        let front_r_4 = $("#front_r_4").val();
        let front_r_5 = $("#front_r_5").val();
        let front_r_6 = $("#front_r_6").val();
        let front_r_7 = $("#front_r_7").val();
        let front_r_8 = $("#front_r_8").val();
        let front_r_9 = $("#front_r_9").val();
        let front_r_10 = $("#front_r_10").val();
        let front_r_11 = $("#front_r_11").val();
        let status = false;
        let focus  = 1;
        if(business_name==''){
             status = true;
            if(focus==1){
                $("#business_name").focus();
            }
            focus++;
            swal({
                title: warning,
                text: front_r_1,
                confirmButtonText: ok,
                confirmButtonColor: '#7367f0'
            });
        }else if(phone==''){
             status = true;
             if(focus==1){
                $("#phone").focus();
            }
            focus++;
            swal({
                title: warning,
                text: front_r_2,
                confirmButtonText: ok,
                confirmButtonColor: '#7367f0'
            });
        }else if(address==''){
             status = true;
             if(focus==1){
                $("#address").focus();
            }
            focus++;
            swal({
                title: warning,
                text: front_r_3,
                confirmButtonText: ok,
                confirmButtonColor: '#7367f0'
            });
        }else if(plan_id == ''){
            status = true;
            swal({
                title: warning,
                text: Select_You_Plan,
                confirmButtonText: ok,
                confirmButtonColor: '#7367f0'
            });
        }else if(payment_type==''  && is_trail_plan=="No"){
            status = true;
            swal({
                title: warning,
                text: please_select_payment_type,
                confirmButtonText: ok,
                confirmButtonColor: '#7367f0'
            });
        }else if(payment_method=='' && is_trail_plan=="No"){
            swal({
                title: warning,
                text: please_select_payment_method,
                confirmButtonText: ok,
                confirmButtonColor: '#7367f0'
            });
        }else if(admin_name==''){
             status = true;
             if(focus==1){
                $("#admin_name").focus();
            }
            focus++;
            swal({
                title: warning,
                text: front_r_4,
                confirmButtonText: ok,
                confirmButtonColor: '#7367f0'
            });
        }else if(email==''){
             status = true;
             if(focus==1){
                $("#email").focus();
            }
            focus++;
            swal({
                title: warning,
                text: front_r_5,
                confirmButtonText: ok,
                confirmButtonColor: '#7367f0'
            });
        }else if(!validateEmail(email)){
             status = true;
             if(focus==1){
                $("#email").focus();
            }
            focus++;
            swal({
                title: warning,
                text: front_r_11,
                confirmButtonText: ok,
                confirmButtonColor: '#7367f0'
            });
        }else if(password==''){
             status = true;
             if(focus==1){
                $("#password").focus();
            }
            focus++;
            swal({
                title: warning,
                text: front_r_6,
                confirmButtonText: ok,
                confirmButtonColor: '#7367f0'
            });
        }else if(confirm_password==''){
             status = true;
             if(focus==1){
                $("#confirm_password").focus();
            }
            focus++;
            swal({
                title: warning,
                text: front_r_7,
                confirmButtonText: ok,
                confirmButtonColor: '#7367f0'
            });
        }else if(password.length<6){
             status = true;
             if(focus==1){
                $("#password").focus();
            }
            focus++;
            swal({
                title: warning,
                text: front_r_9,
                confirmButtonText: ok,
                confirmButtonColor: '#7367f0'
            });
        }else if(confirm_password.length<6){
             status = true;
             if(focus==1){
                $("#confirm_password").focus();
            }
            focus++;
            swal({
                title: warning,
                text: front_r_9,
                confirmButtonText: ok,
                confirmButtonColor: '#7367f0'
            });
        }else if(password !== confirm_password){
             status = true;
             if(focus==1){
                $("#confirm_password ").focus();
            }
            focus++;
            swal({
                title: warning,
                text: front_r_8,
                confirmButtonText: ok,
                confirmButtonColor: '#7367f0'
            });
        }

        if(status==false){
            if(is_trail_plan=="No"){
                var data = $("form#singup_company").serialize();
                $.ajax({
                    url:base_url+'Authentication/signupCompany',
                    method:"POST",
                    data: data,
                    dataType:'json',
                    success:function(data){
                        if(data.status== true && data.free_status==false){
                            $(".payment_company_id").val(data.id);
                            //set url for company_id
                            let url_custom = base_url+'paymentStatus?msg=payment_success&&payment_company_id='+data.id;
                            $("#update_success_url").val(url_custom);
                            if(payment_type==1){
                                if(payment_method==1){
                                    $("#paypal_form").submit();
                                }else if(payment_method==2){
                                    $("#stripe_form").submit();
                                }else if(payment_method==3){
                                    //call razorpay library
                                    let last_added_company_id = data.id;
                                    let last_order_number = data.order_number;
                                    let totalAmount = Number($("#total_payable").val());
                                    let site_title = $("#site_title").val();
                                    let site_logo = $("#site_logo").val();
                                    let key_id_razorpay = $("#key_id_razorpay").val();
                                    //base color
                                    let base_color_layout = $("#base_color").val();
                                    let item_description_str_paypal = $("#item_description_str_paypal").val();
                                    let options = {
                                        key: key_id_razorpay,
                                        amount: totalAmount * 100, // 2000 paise = INR 20
                                        name: site_title,
                                        description: item_description_str_paypal,
                                        image: site_logo,
                                        handler: function (response) {
                                            if (response.razorpay_payment_id) {
                                                $.ajax({
                                                    url: base_url + "Authentication/updateOrderSuccess",
                                                    method: "POST",
                                                    async: false,
                                                    data: {
                                                        razorpay_payment_id: response.razorpay_payment_id,
                                                        last_added_company_id: last_added_company_id,
                                                        total_amount: totalAmount,
                                                    },
                                                    dataType: "json",
                                                    success: function (data) {
                                                        if (data.status == true) {
                                                            swal(
                                                                {
                                                                    title: warning + "!",
                                                                    text: data.msg,
                                                                    confirmButtonColor: "#7367f0",
                                                                    confirmButtonText: ok,
                                                                    showCancelButton: true
                                                                },
                                                                function () {
                                                                    window.location.replace(base_url + 'authentication');
                                                                }
                                                            );

                                                        } else {
                                                            swal({
                                                                title: warning,
                                                                text: "Something is wrong!",
                                                                confirmButtonText: ok,
                                                                confirmButtonColor: base_color_layout,
                                                            });
                                                        }
                                                    },
                                                });
                                            }
                                        },
                                        theme: {
                                            color: base_color_layout,
                                        },
                                    };
                                    let rzp1 = new Razorpay(options);
                                    rzp1.open();
                                    e.preventDefault();
                                }
                            }else{
                                if(payment_method==1){
                                    $("#paypal_recurring_form").submit();
                                }else if(payment_method==2){
                                }
                            }

                        }else if(data.status==true && data.free_status==true){
                            $("#business_name").val('');
                            $("#phone").val('');
                            $("#address").val('');
                            $("#admin_name").val('');
                            $("#email").val('');
                            $("#password").val('');
                            $("#confirm_password").val('');
                            $("#payment_method").val('').change();
                            swal(
                                {
                                    title: warning + "!",
                                    text: data.msg,
                                    confirmButtonColor: "#7367f0",
                                    confirmButtonText: ok,
                                    showCancelButton: true
                                },
                                function () {
                                    window.location.replace(base_url + 'authentication');
                                }
                            );
                        }else if(data.status==false && data.free_status==false){
                            swal({
                                title: warning,
                                text: data.msg,
                                confirmButtonText: ok,
                                confirmButtonColor: '#7367f0'
                            });
                        }else if(data.status==true){
                            $("#business_name").val('');
                            $("#phone").val('');
                            $("#address").val('');
                            $("#admin_name").val('');
                            $("#email").val('');
                            $("#password").val('');
                            $("#confirm_password").val('');
                            $("#payment_method").val('').change();
                            swal(
                                {
                                    title: warning + "!",
                                    text: data.msg,
                                    confirmButtonColor: "#7367f0",
                                    confirmButtonText: ok,
                                    showCancelButton: true
                                },
                                function () {
                                    window.location.replace(base_url + 'authentication');
                                }
                            );
                        }

                    }
                });
            }else{
                var data = $("form#singup_company").serialize();
                $.ajax({
                    url:base_url+'Authentication/signupCompany',
                    method:"POST",
                    data: data,
                    dataType:'json',
                    success:function(data){
                        if(data.status== true && data.free_status==false){
                            $(".payment_company_id").val(data.id);
                            //set url for company_id
                            let url_custom = base_url+'paymentStatus?msg=payment_success&&payment_company_id='+data.id;
                            $("#update_success_url").val(url_custom);
                            if(payment_type==1){
                                if(payment_method==1){
                                    $("#paypal_form").submit();
                                }else if(payment_method==2){
                                    $("#stripe_form").submit();
                                }else if(payment_method==3){
                                    //call razorpay library
                                    let last_added_company_id = data.id;
                                    let last_order_number = data.order_number;
                                    let totalAmount = Number($("#total_payable").val());
                                    let site_title = $("#site_title").val();
                                    let site_logo = $("#site_logo").val();
                                    let key_id_razorpay = $("#key_id_razorpay").val();
                                    //base color
                                    let base_color_layout = $("#base_color").val();
                                    let item_description_str_paypal = $("#item_description_str_paypal").val();
                                    let options = {
                                        key: key_id_razorpay,
                                        amount: totalAmount * 100, // 2000 paise = INR 20
                                        name: site_title,
                                        description: item_description_str_paypal,
                                        image: site_logo,
                                        handler: function (response) {
                                            if (response.razorpay_payment_id) {
                                                $.ajax({
                                                    url: base_url + "Authentication/updateOrderSuccess",
                                                    method: "POST",
                                                    async: false,
                                                    data: {
                                                        razorpay_payment_id: response.razorpay_payment_id,
                                                        last_added_company_id: last_added_company_id,
                                                        total_amount: totalAmount,
                                                    },
                                                    dataType: "json",
                                                    success: function (data) {
                                                        if (data.status == true) {
                                                            swal(
                                                                {
                                                                    title: warning + "!",
                                                                    text: data.msg,
                                                                    confirmButtonColor: "#7367f0",
                                                                    confirmButtonText: ok,
                                                                    showCancelButton: true
                                                                },
                                                                function () {
                                                                    window.location.replace(base_url + 'authentication');
                                                                }
                                                            );

                                                        } else {
                                                            swal({
                                                                title: warning,
                                                                text: "Something is wrong!",
                                                                confirmButtonText: ok,
                                                                confirmButtonColor: base_color_layout,
                                                            });
                                                        }
                                                    },
                                                });
                                            }
                                        },
                                        theme: {
                                            color: base_color_layout,
                                        },
                                    };
                                    let rzp1 = new Razorpay(options);
                                    rzp1.open();
                                    e.preventDefault();
                                }
                            }else{
                                if(payment_method==1){
                                    $("#paypal_recurring_form").submit();
                                }else if(payment_method==2){

                                }
                            }
                        }else if(data.status==true && data.free_status==true){
                            $("#business_name").val('');
                            $("#phone").val('');
                            $("#address").val('');
                            $("#admin_name").val('');
                            $("#email").val('');
                            $("#password").val('');
                            $("#confirm_password").val('');
                            $("#payment_method").val('');
                            swal(
                                {
                                    title: warning + "!",
                                    text: data.msg,
                                    confirmButtonColor: "#7367f0",
                                    confirmButtonText: ok,
                                    showCancelButton: true
                                },
                                function () {
                                    window.location.replace(base_url + 'authentication');
                                }
                            );
                        }else if(data.status==false && data.free_status==false){
                            swal({
                                title: warning,
                                text: data.msg,
                                confirmButtonText: ok,
                                confirmButtonColor: '#7367f0'
                            });
                        }else if(data.status==true){
                            $("#business_name").val('');
                            $("#phone").val('');
                            $("#address").val('');
                            $("#admin_name").val('');
                            $("#email").val('');
                            $("#password").val('');
                            $("#confirm_password").val('');
                            $("#payment_method").val('');
                            swal(
                                {
                                    title: warning + "!",
                                    text: data.msg,
                                    confirmButtonColor: "#7367f0",
                                    confirmButtonText: ok,
                                    showCancelButton: true
                                },
                                function () {
                                    window.location.replace(base_url + 'authentication');
                                }
                            );
                        }

                    }
                });
            }
        }

    });
});
"use strict";
let _URL = window.URL || window.webkitURL;
$(document).on('change', '#login_logo', function() {
    let file, img;
    if ((file = this.files[0])) {
        img = new Image();
        img.onload = function() {
            let img_width = this.width;
            let height = this.height;
            let alert = $("#alert").val();
            let image_msg = $("#image_msg").val();
            let ok = $("#ok").val();
            if (img_width != "128" && height != "49") {
                $("#login_logo").val('');
                swal({
                    title: alert,
                    text: image_msg,
                    confirmButtonText: ok,
                    confirmButtonColor: '#3c8dbc'
                });
            }
        };
        img.src = _URL.createObjectURL(file);
    }
});
$(document).on('change', '#admin_panel_logo', function() {
    let file, img;
    if ((file = this.files[0])) {
        img = new Image();
        img.onload = function() {
            let img_width = this.width;
            let height = this.height;
            let alert = $("#alert").val();
            let image_msg = $("#image_msg").val();
            let ok = $("#ok").val();
            if (img_width != "128" && height != "49") {
                $("#admin_panel_logo").val('');
                swal({
                    title: alert,
                    text: image_msg,
                    confirmButtonText: ok,
                    confirmButtonColor: '#3c8dbc'
                });
            }
        };
        img.src = _URL.createObjectURL(file);
    }
});
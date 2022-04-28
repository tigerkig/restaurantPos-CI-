<input type="hidden" id="base_url_ajax" value="<?php echo base_url()?>">
<input type="hidden" name="hidden_alert" id="hidden_alert" class="hidden_alert"
       value="<?php echo lang('alert'); ?>!">
<input type="hidden" name="hidden_ok" id="hidden_ok" class="hidden_ok" value="<?php echo lang('ok'); ?>">
<!-- page title start -->
<div class="page-title-area text-center" style="background-image: url('assets/landing/saas/img/bg/5.png')">
    <div class="container">
        <div class="breadcrumb-inner">
            <h2 class="page-title">Contact Us</h2>
        </div>
    </div>
</div>
<!-- page title end -->

<!-- contact area start -->
<div class="contact-area pd-top-110 pd-bottom-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-md-6">
                <div class="contact-page-thumb thumb" style="background-image: url('assets/landing/saas/img/gallery/1.png')"></div>
            </div>
            <div class="col-lg-7 col-md-6 align-self-center">
                <form class="contact-form-inner  mt-5 mt-md-0" id="contact_us_form" action="#">
                    <div class="single-input-inner">
                        <input type="text" id="name" name="name" class="form-control required_check" placeholder="Name">
                    </div>
                    <div class="single-input-inner">
                        <input type="text" id="phone" name="phone" class="form-control required_check" placeholder="Phone Number">
                    </div>
                    <div class="single-input-inner">
                        <input type="text" id="subject" name="subject" class="form-control required_check" placeholder="Subject">
                    </div>
                    <div class="single-input-inner">
                        <textarea name="message" id="message" class="form-control required_check" placeholder="Write Message"></textarea>
                    </div>
                    <div class="btn-wrap">
                        <a class="btn btn-base-m send_mail" href="#">Send Message</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- contact area end-->

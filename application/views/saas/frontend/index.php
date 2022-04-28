<?php
/*content get from database*/
$wl = getWhiteLabel();
$company_info = getMainCompany();
$site_name = '';
$footer = '';
if($wl){
    if($wl->site_name){
        $site_name = $wl->site_name;
    }
    if($wl->footer){
        $footer = $wl->footer;
    }
    if($wl->system_logo){
        $system_logo = base_url()."images/".$wl->system_logo;
    }
}

$company = getMainCompany();
$social_links = isset($company->social_link_details) && $company->social_link_details?json_decode($company->social_link_details):'';
$customer_reviewers = isset($company->customer_reviewers) && $company->customer_reviewers?json_decode($company->customer_reviewers):'';
$counter_details = isset($company->counter_details) && $company->counter_details?json_decode($company->counter_details):'';
?>
<!-- banner start -->
<div class="banner-area banner-area-1" style="background: url('assets/landing/saas/img/banner/1.png');">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-11 align-self-center">
                <div class="banner-inner text-center text-lg-left">
                    <h1>Best restaurant management solution.</h1>
                    <p>Recipe Management, Stock Auto Deduct by Recipe on Sale, Powerful POS, Kitchen/Bar/Waiter Panel, CRM, SMS, Veg & Bar Item Filter</p>
                    <a class="btn btn-white-border" href="<?php echo base_url()?>authentication">Signin</a>
                </div>
            </div>
            <div class="col-xl-7 col-lg-6 align-self-center">
                <div class="thumb video-thumb">
                    <img src="<?php echo base_url()?>assets/landing/img/large-screen-optimised.png" alt="img">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- banner end -->

<!-- feature start -->
<div id="valuable-feature" data-bs-spy="scroll" class="feature-area pd-top-90">
    <div class="container">
        <div class="row justify-content-end">
            <div class="col-lg-6 col-md-11">
                <div class="section-title text-lg-right text-center">
                    <h2 class="title">Valuable feature</h2>
                    <p class="main-content-wrapper">Here is the our most valuable feature for <?php echo escape_output($site_name)?>.</p>
                </div>
            </div>
        </div>
        <div class="feature-slider owl-carousel slider-control-round">
            <div class="item">
                <div class="single-feature-inner bg-gradient">
                    <div class="thumb">
                        <img src="<?php echo base_url()?>assets/landing/saas/img/icon/1.png" alt="img">
                    </div>
                    <div class="details">
                        <h5>How Recipe Works</h5>
                        <p>Ingredient 1 200g, Ingredient 2 60g, Ingredient 3 100g, Ingredient 4 120g,  Ingredient 5 10g,  Ingredient 6 17g </p>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="single-feature-inner bg-gradient">
                    <div class="thumb">
                        <img src="<?php echo base_url()?>assets/landing/saas/img/icon/8.png" alt="img">
                    </div>
                    <div class="details">
                        <h5>Recipe and Ingredient Stock</h5>
                        <p>Our software has a very innovative Recipe Management System for your food menu, where you can assign which ingredients will be needed for your food menu as well as that's amount or quantity. And when you make a sale those ingredients will be auto deducted from stock.</p>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="single-feature-inner bg-gradient">
                    <div class="thumb">
                        <img src="<?php echo base_url()?>assets/landing/saas/img/icon/9.png" alt="img">
                    </div>
                    <div class="details">
                        <h5>Innovative Running Order Panel</h5>
                        <p>You know the customers in a restaurant do not act same as in a retail shop. Like they come, take some stuffs and go, they usually sit order, eat, and ask for some more food after completing eating, even they can cancel an item after the order. So to match this practical scenario we built the Running Order feature in POS so when customers order and sit the order goes to Running Order list and then when they order for new item, you can add that to that order, if they cancel an item, you can do that, even if they change table, you can do that too. And finally you will print the receipt when they leave. In the mean time you can print a bill for them too, to show how much they need to pay.</p>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="single-feature-inner bg-gradient">
                    <div class="thumb">
                        <img src="<?php echo base_url()?>assets/landing/saas/img/icon/10.png" alt="img">
                    </div>
                    <div class="details">
                        <h5>Powerful Point-Of-Sale</h5>
                        <p>We have a very powerful point of sale where you will find Running Order feature as mentioned before. And more features such as Searching and Filtering: Search by Item Name or Code, Filter Beverage Item or Vegetarian Item easily, filter by category etc. Moreover you will get notification when an item is ready cooking in kitchen. You can make quick invoice for faster serve, you can see how many items are ready in the kitchen and how many are remaining when the customer asks, you can print bill, KOT and BOT. You can add new items when customer asks while eating. Other than these you are getting all other common features like draft sales, print last invoice, see latest invoice list etc. You can also run the POS in full screen mode as well.</p>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="single-feature-inner bg-gradient">
                    <div class="thumb">
                        <img src="<?php echo base_url()?>assets/landing/saas/img/icon/10.png" alt="img">
                    </div>
                    <div class="details">
                        <h5>Waste Tracking</h5>
                        <p>It is common in a restaurant that sometime the ingredients get perished or wasted, even foods too. But those have value in money right? So our system helps you to track those waste and helps you to calculate the loss for those wastes as well.</p>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="single-feature-inner bg-gradient">
                    <div class="thumb">
                        <img src="<?php echo base_url()?>assets/landing/saas/img/icon/10.png" alt="img">
                    </div>
                    <div class="details">
                        <h5>Kitchen, Bar, Waiter Panel</h5>
                        <p>In case your chef is a smart guy to operate a tablet, we have a kitchen panel feature where the chef can see all items to prepare when an order is placed. He can also change status of an item that it is being cooked. So that cashier can tell the customer that his item is under cooking. Then the chef can mark item as done. And cashier will get notified in his POS screen. Even the waiter will also get a notification when an item will be done so that he can collect the item and serve. Not even the Kitchen Panel only, we have Bar Panel too, so that when you place an order all kitchen item will go to kitchen panel and all bar item will go to bar panel automatically.</p>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="single-feature-inner bg-gradient">
                    <div class="thumb">
                        <img src="<?php echo base_url()?>assets/landing/saas/img/icon/10.png" alt="img">
                    </div>
                    <div class="details">
                        <h5>GST, VAT, IVAT, HST Support</h5>
                        <p>Our tax configuration is another innovation too. In this setup you can configure any of your tax types. You can setup all of your applicable taxes with their percentages. And then when you will add an item all tax fields with their values will be populated but if there is any change in percentage of tax in any item you can change those easily, and that will be applicable in invoice.</p>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="single-feature-inner bg-gradient">
                    <div class="thumb">
                        <img src="<?php echo base_url()?>assets/landing/saas/img/icon/10.png" alt="img">
                    </div>
                    <div class="details">
                        <h5>Attendance Tracking</h5>
                        <p>Not a big thing, it is common that you will need to track your employee's attendances. You can do that in our system.</p>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="single-feature-inner bg-gradient">
                    <div class="thumb">
                        <img src="<?php echo base_url()?>assets/landing/saas/img/icon/10.png" alt="img">
                    </div>
                    <div class="details">
                        <h5>CRM Facility</h5>
                        <p>In our system you can collect Date of Birth, Date of Anniversary etc of a customer and as there is SMS gateway is integrated within the system so you can send a wish to their Birthday or Anniversary, or you may be able to make special offer for their special day. If you can do this, for sure they will keep your restaurant in their mind whenever they want to have a dinner.</p>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="single-feature-inner bg-gradient">
                    <div class="thumb">
                        <img src="<?php echo base_url()?>assets/landing/saas/img/icon/10.png" alt="img">
                    </div>
                    <div class="details">
                        <h5>Multiple User and Access Control</h5>
                        <p>You can have multiple users and control their access to the system as this is a common feature of any system.</p>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="single-feature-inner bg-gradient">
                    <div class="thumb">
                        <img src="<?php echo base_url()?>assets/landing/saas/img/icon/10.png" alt="img">
                    </div>
                    <div class="details">
                        <h5>Optimize Operation</h5>
                        <p>When you place an order, that will directly been popped up in the kitchen panel, when the item is ready your POS will show notification, your waiter will also get notified. You can even generate a kitchen performance report to know that how much time they are taking to make food. And also when a customer asks that what is the status of his food or how long his food will take to come to the table, you can simply check that from the POS screen. In these ways you can optimizes lot of interactions between your staffs that may reduce unnecessary waste of time. And overseeing kitchen performance report you will have more control to improve kitchen performance.</p>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="single-feature-inner bg-gradient">
                    <div class="thumb">
                        <img src="<?php echo base_url()?>assets/landing/saas/img/icon/10.png" alt="img">
                    </div>
                    <div class="details">
                        <h5>Text and Item Modifier</h5>
                        <p> When ordering a food people usually makes two types of notes, some additional small items like toppings e.g.: additional salad or sauce those may include additional price too. Our system supports this facility. Even those additional toppings may have ingredient consumptions too those needs to be deducted from stock when sale, no worry our system gives that facility.
                            And another is customer may provides additional instruction to prepare food like: less sugar, more pepper etc. In our system you can do that.</p>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="single-feature-inner bg-gradient">
                    <div class="thumb">
                        <img src="<?php echo base_url()?>assets/landing/saas/img/icon/10.png" alt="img">
                    </div>
                    <div class="details">
                        <h5>Pre and Post Payment Support</h5>
                        <p>Some restaurant takes payment after eating and some restaurants take payment when ordering, our system supports both cases means both types of restaurants.</p>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="single-feature-inner bg-gradient">
                    <div class="thumb">
                        <img src="<?php echo base_url()?>assets/landing/saas/img/icon/10.png" alt="img">
                    </div>
                    <div class="details">
                        <h5>Supplier and Customer Due Tracking</h5>
                        <p>You may purchase from supplier with due and pay later. You can do that; even you can check that how much you have to pay for each supplier. You can also do that for customers too.</p>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="single-feature-inner bg-gradient">
                    <div class="thumb">
                        <img src="<?php echo base_url()?>assets/landing/saas/img/icon/10.png" alt="img">
                    </div>
                    <div class="details">
                        <h5>Reports, Analytics & Dashboard</h5>
                        <p>Our system provides lot of reports, analytics and Business Intelligence Dashboard that will help you to oversee the business and take decision quickly.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- feature end -->

<!-- review start -->
<div class="review-area pd-top-90">
    <div class="container bg-relative">
        <img class="top_image_bounce review-bg-1" src="<?php echo base_url()?>assets/landing/saas/img/other/2.png" alt="img">
        <div class="row">
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-lg-12 col-md-11">
                        <div class="section-title text-center text-lg-left">
                            <h2 class="title">Customer Review</h2>
                            <p class="main-content-wrapper">See how our customers from many region is happyly using our software and sends their valuable feedback.</p>
                        </div>
                    </div>
                </div>
                <!-- review slider -->
                <div class="review-slider owl-carousel slider-control-square">
                    <?php
                    if(isset($customer_reviewers) && $customer_reviewers):
                        foreach ($customer_reviewers as $key=>$spns) {
                            $current_row = json_decode($spns);
                            ?>
                            <div class="item">
                                <div class="single-review-inner">
                                    <div class="details">
                                        <h6><?php echo escape_output($current_row->name) ?></h6>
                                        <span class="designation"><?php echo escape_output($current_row->designation) ?></span>
                                        <p> "<?php echo escape_output($current_row->description) ?>"</p>
                                        <div class="ratting-inner">
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    endif;
                    ?>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
                <div class="review-thumb-wrap">
                    <div class="review-thumb" style="background: url('assets/landing/saas/img/other/1.png');"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- review end -->

<!-- counter start -->
<div class="counter-area pd-top-120">
    <div class="container">
        <div class="counter-area-inner">
            <img class="animate-img-1 left_image_bounce" src="<?php echo base_url()?>assets/landing/saas/img/shape/4.png" alt="img">
            <img class="animate-img-2 left_image_bounce" src="<?php echo base_url()?>assets/landing/saas/img/shape/2.png" alt="img">
            <div class="row">
                <div class="col-lg-3 col-sm-6">
                    <div class="single-counter-inner text-center">
                        <div class="icon">
                            <img src="<?php echo base_url()?>assets/landing/saas/img/icon/4.png" alt="img">
                        </div>
                        <div class="details">
                            <h2><span class="counter"><?php echo isset($counter_details->restaurants) && $counter_details->restaurants?escape_output(number_format($counter_details->restaurants,0)):'0'; ?></span> </h2>
                            <p>Restaurants</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="single-counter-inner text-center">
                        <div class="icon">
                            <img src="<?php echo base_url()?>assets/landing/saas/img/icon/5.png" alt="img">
                        </div>
                        <div class="details">
                            <h2><span class="counter"><?php echo isset($counter_details->users) && $counter_details->users?escape_output(number_format($counter_details->users,0)):'0'; ?></span> </h2>
                            <p>Users</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="single-counter-inner text-center">
                        <div class="icon">
                            <img src="<?php echo base_url()?>assets/landing/saas/img/icon/6.png" alt="img">
                        </div>
                        <div class="details">
                            <h2><span class="counter"><?php echo isset($counter_details->reference) && $counter_details->reference?escape_output(number_format($counter_details->reference,0)):'0'; ?></span> </h2>
                            <p>Reference</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="single-counter-inner text-center">
                        <div class="icon">
                            <img src="<?php echo base_url()?>assets/landing/saas/img/icon/7.png" alt="img">
                        </div>
                        <div class="details">
                            <h2><span class="counter"><?php echo isset($counter_details->daily_transactions) && $counter_details->daily_transactions?escape_output(number_format($counter_details->daily_transactions,2)):'0'; ?></span> +</h2>
                            <p>Daily Transactions</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- counter end -->

<!-- pricing start -->
<div class="pricing-area pd-top-110 pd-bottom-90">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-7 col-md-11">
                <div class="section-title text-center">
                    <h2>Best Pricing Plans</h2>
                    <p>Outsource your HR functions to industry-specialized HR experts, so you and your team can dedicate your time.</p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <?php
            $getPricingPlan  = getPricingPlan();
            if(isset($getPricingPlan) && $getPricingPlan):
                foreach ($getPricingPlan as $value):
                    ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="single-pricing-inner text-center <?php echo isset($value->is_recommended) && $value->is_recommended=="Yes"?"active":''?>">
                            <div class="price-header">
                                <h4><?php echo escape_output($value->plan_name)?></h4>
                            </div>
                            <div class="price-body">
                                <h5 class="price"><?php echo escape_output($company_info->currency)?><?php echo !$value->monthly_cost?'0':escape_output($value->monthly_cost)?><sup>/
                                    <?php
                                    echo lang('month');
                                    ?></h5>
                                <ul>
                                    <li><?php echo escape_output($value->number_of_maximum_users)?> <span><?php echo lang('users')?></li>
                                    <li><?php echo escape_output($value->number_of_maximum_outlets)?> <span><?php echo lang('outlets')?></li>
                                    <li><?php echo escape_output($value->number_of_maximum_invoices)?> <span><?php echo lang('invoices')?></li>
                                    <li><?php
                                        echo escape_output($value->trail_days)." Days Trial";

                                        ?></li>
                                </ul>
                                <a class="btn btn-border-base" href="<?php base_url()?>plan/<?php echo escape_output($value->id)?>">GET STARTED</a>
                            </div>
                        </div>
                    </div>
                    <?php
                endforeach;
            endif;
            ?>
        </div>
    </div>
</div>
<!-- pricing end -->

<!-- faq end -->
<div class="faq-area bg-gray pd-top-110 pd-bottom-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-7 col-md-11">
                <div class="section-title text-center">
                    <h2>FAQ</h2>
                    <p>Please check here if you get answer of your questions</p>
                </div>
            </div>
        </div>
        <div id="accordion" class="accordion-area">
            <img class="animate-img-1 top_image_bounce" src="<?php echo base_url()?>assets/landing/saas/img/shape/2.png" alt="img">
            <img class="animate-img-2 left_image_bounce" src="<?php echo base_url()?>assets/landing/saas/img/shape/1.png" alt="img">
            <img class="animate-img-3 top_image_bounce" src="<?php echo base_url()?>assets/landing/saas/img/shape/3.png" alt="img">
            <div class="row">
                <div class="col-md-6">
                    <div class="card single-faq-inner">
                        <div class="card-header" id="ff-one">
                            <h5 class="mb-0">
                                <button class="btn-link" data-toggle="collapse" data-target="#f-one" aria-expanded="true" aria-controls="f-one">
                                    What is <?php echo escape_output($site_name)?>?
                                </button>
                            </h5>
                        </div>
                        <div id="f-one" class="collapse" aria-labelledby="ff-one" data-parent="#accordion">
                            <div class="card-body">
                                <?php echo escape_output($site_name)?> is a complete solution for a restaurant where you will get Recipe Management, Stock Auto Deduct by Recipe on Sale, Powerful POS, Kitchen/Bar/Waiter Panel, CRM, SMS, Veg & Bar Item Filter.
                            </div>
                        </div>
                    </div>
                    <div class="card single-faq-inner">
                        <div class="card-header" id="ff-two">
                            <h5 class="mb-0">
                                <button class="btn-link" data-toggle="collapse" data-target="#f-two" aria-expanded="true" aria-controls="f-two">
                                    Why use <?php echo escape_output($site_name)?> software?
                                </button>
                            </h5>
                        </div>
                        <div id="f-two" class="collapse" aria-labelledby="ff-two" data-parent="#accordion">
                            <div class="card-body">
                                Please check our valuable feature list, you will get a clear idea about our software. Moreover you can signup and play with this software for some trial days to get more clear understanding.
                            </div>
                        </div>
                    </div>
                    <div class="card single-faq-inner">
                        <div class="card-header" id="ff-three">
                            <h5 class="mb-0">
                                <button class="btn-link" data-toggle="collapse" data-target="#f-three" aria-expanded="true" aria-controls="f-three">
                                    Do I need to have a credit card to pay?
                                </button>
                            </h5>
                        </div>
                        <div id="f-three" class="show collapse" aria-labelledby="ff-three" data-parent="#accordion">
                            <div class="card-body">
                                Yes you do have for uninterrupted operation. But we also take cash payment too.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card single-faq-inner">
                        <div class="card-header" id="ff-four">
                            <h5 class="mb-0">
                                <button class="btn-link" data-toggle="collapse" data-target="#f-four" aria-expanded="true" aria-controls="f-four">
                                    How about support?
                                </button>
                            </h5>
                        </div>
                        <div id="f-four" class="collapse" aria-labelledby="ff-four" data-parent="#accordion">
                            <div class="card-body">
                                You will get ASAP support for any issue you face. Please submit your problem using our contact form whenever you face any issue.
                            </div>
                        </div>
                    </div>
                    <div class="card single-faq-inner">
                        <div class="card-header" id="ff-five">
                            <h5 class="mb-0">
                                <button class="btn-link" data-toggle="collapse" data-target="#f-five" aria-expanded="true" aria-controls="f-five">
                                    How much I have to pay to use this software?
                                </button>
                            </h5>
                        </div>
                        <div id="f-five" class="collapse" aria-labelledby="ff-five" data-parent="#accordion">
                            <div class="card-body">
                                Please check our pricing plan that shows monthly payment amount as well as limitation of users, outlets, invoices etc. Please choose any plan that best suits you.
                            </div>
                        </div>
                    </div>
                    <div class="card single-faq-inner">
                        <div class="card-header" id="ff-six">
                            <h5 class="mb-0">
                                <button class="btn-link" data-toggle="collapse" data-target="#f-six" aria-expanded="true" aria-controls="f-six">
                                    How about data protection?
                                </button>
                            </h5>
                        </div>
                        <div id="f-six" class="collapse" aria-labelledby="ff-six" data-parent="#accordion">
                            <div class="card-body">
                                Our system is 99% secured from any type of security vulnerability. It is strongly protected from any kind of web attack like: XSS, SQL Injection, CSRF, Session Fixation, Session Hijacking, Insecure File Upload, Insecure Data Transfer. So you may rely on our system with full trust.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- gallery start -->
<div class="container pd-top-110">
    <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-7 col-md-11">
            <div class="section-title text-center mb-4">
                <h2>Gallery</h2>
                <p>Some view of major features of our software.</p>
            </div>
        </div>
    </div>
</div>
<div class="gallery-area">
    <div class="gallery-slider owl-carousel slider-control-round text-center">
        <div class="item">
            <div class="thumb">
                <a class="show_vew" href="<?php echo base_url()?>assets/landing/img/SaasSC/Screenshot_1.png"><img src="<?php echo base_url()?>assets/landing/img/SaasSC/Screenshot_1_thumb.png" alt="img"></a>
            </div>
        </div>
        <div class="item">
            <div class="thumb">
                <a class="show_vew" href="<?php echo base_url()?>assets/landing/img/SaasSC/Screenshot_2.png"><img src="<?php echo base_url()?>assets/landing/img/SaasSC/Screenshot_2_thumb.png" alt="img"></a>
            </div>
        </div>
        <div class="item">
            <div class="thumb">
                <a class="show_vew" href="<?php echo base_url()?>assets/landing/img/SaasSC/Screenshot_3.png"><img src="<?php echo base_url()?>assets/landing/img/SaasSC/Screenshot_3_thumb.png" alt="img"></a>
            </div>
        </div>
        <div class="item">
            <div class="thumb">
                <a class="show_vew" href="<?php echo base_url()?>assets/landing/img/SaasSC/Screenshot_4.png"><img src="<?php echo base_url()?>assets/landing/img/SaasSC/Screenshot_4_thumb.png" alt="img"></a>
            </div>
        </div>
        <div class="item">
            <div class="thumb">
                <a class="show_vew" href="<?php echo base_url()?>assets/landing/img/SaasSC/Screenshot_5.png"><img src="<?php echo base_url()?>assets/landing/img/SaasSC/Screenshot_5_thumb.png" alt="img"></a>
            </div>
        </div>
        <div class="item">
            <div class="thumb">
                <a class="show_vew" href="<?php echo base_url()?>assets/landing/img/SaasSC/Screenshot_6.png"><img src="<?php echo base_url()?>assets/landing/img/SaasSC/Screenshot_6.png" alt="img"></a>
            </div>
        </div>
        <div class="item">
            <div class="thumb">
                <a class="show_vew" href="<?php echo base_url()?>assets/landing/img/SaasSC/Screenshot_7.png"><img src="<?php echo base_url()?>assets/landing/img/SaasSC/Screenshot_7_thumb.png" alt="img"></a>
            </div>
        </div>
        <div class="item">
            <div class="thumb">
                <a class="show_vew" href="<?php echo base_url()?>assets/landing/img/SaasSC/Screenshot_8.png"><img src="<?php echo base_url()?>assets/landing/img/SaasSC/Screenshot_8_thumb.png" alt="img"></a>
            </div>
        </div>
        <div class="item">
            <div class="thumb">
                <a class="show_vew" href="<?php echo base_url()?>assets/landing/img/SaasSC/Screenshot_9.png"><img src="<?php echo base_url()?>assets/landing/img/SaasSC/Screenshot_9_thumb.png" alt="img"></a>
            </div>
        </div>
        <div class="item">
            <div class="thumb">
                <a class="show_vew" href="<?php echo base_url()?>assets/landing/img/SaasSC/Screenshot_10.png"><img src="<?php echo base_url()?>assets/landing/img/SaasSC/Screenshot_10_thumb.png" alt="img"></a>
            </div>
        </div>
        <div class="item">
            <div class="thumb">
                <a class="show_vew" href="<?php echo base_url()?>assets/landing/img/SaasSC/Screenshot_11.png"><img src="<?php echo base_url()?>assets/landing/img/SaasSC/Screenshot_11_thumb.png" alt="img"></a>
            </div>
        </div>
    </div>
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<!-- gallery end -->
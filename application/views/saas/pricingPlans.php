

<section class="main-content-wrapper">

    <?php
    if ($this->session->flashdata('exception')) {
        echo '<section class="alert-wrapper">
        <div class="alert alert-success alert-dismissible fade show"> 
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <div class="alert-body">
        <p><i class="m-right fa fa-check"></i>';
        echo escape_output($this->session->flashdata('exception'));
        echo '</p></div></div></section>';
    }
    ?>

    <section class="content-header">
        <h3 class="text-left top-left-header"><?php echo lang('Pricing_Plans')?> </h3>
    </section>

    
        <div class="box-wrapper">
            
            <div class="table-box">
                <!-- /.box-header -->
                    <div class="tab-content" id="myTabContent">
                        <div id="pring_plan" class="tab-pane fade show active" role="tabpanel" aria-labelledby="home-tab">
                            <div class="d-flex justify-content-end">
                                <a class="btn bg-blue-btn col-sm-12 col-md-2" href="<?php echo base_url() ?>Service/addPricingPlan">
                                    <?php echo lang('Add_Pricing_Plan')?>  
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="ir_w_1"> <?php echo lang('sn'); ?></th>
                                            <th class="ir_w_9"><?php echo lang('plan_name'); ?></th>
                                            <th class="ir_w_9"><?php echo lang('monthly_cost'); ?></th>
                                            <th class="ir_w_9"><?php echo lang('number_of_maximum_users'); ?></th>
                                            <th class="ir_w_9"><?php echo lang('number_of_maximum_outlets'); ?></th>
                                            <th class="ir_w_9"><?php echo lang('number_of_maximum_invoices'); ?></th>
                                            <th class="ir_w_9"><?php echo lang('trail_days'); ?></th>
                                            <th class="ir_w_6 not-export-col"><?php echo lang('actions'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($pricingPlans && !empty($pricingPlans)) {
                                            $i = count($pricingPlans);
                                        }
                                        foreach ($pricingPlans as $spns) {
                                            ?>
                                        <tr>
                                            <td><?php echo escape_output($i--); ?></td>
                                            <td>   <?php echo escape_output($spns->plan_name) ?></td>
                                            <td>   <?php echo escape_output(getAmtP($spns->monthly_cost)) ?></td>
                                            <td>   <?php echo escape_output($spns->number_of_maximum_users) ?></td>
                                            <td>   <?php echo escape_output($spns->number_of_maximum_outlets) ?></td>
                                            <td>   <?php echo escape_output($spns->number_of_maximum_invoices) ?></td>
                                            <td>   <?php echo escape_output($spns->trail_days) ?></td>
                                            <td class="ir_txt_center">
                                                <div class="btn-group  actionDropDownBtn">
                                                    <button type="button" class="btn bg-blue-color dropdown-toggle"
                                                            id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i data-feather="more-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton1" role="menu">
                                                        <li><a
                                                                    href="<?php echo base_url() ?>Service/addPricingPlan/<?php echo escape_output($this->custom->encrypt_decrypt($spns->id, 'encrypt')); ?>"><i
                                                                        class="fa fa-pencil tiny-icon"></i><?php echo lang('edit'); ?></a>
                                                        </li>
                                                        <li><a class="delete"
                                                            href="<?php echo base_url() ?>Service/deletePricingPlan/<?php echo escape_output($this->custom->encrypt_decrypt($spns->id, 'encrypt')); ?>"><i
                                                                        class="fa fa-trash tiny-icon"></i><?php echo lang('delete'); ?></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- End Price Panel -->
                        <div id="Customer_Review" aria-labelledby="Customer_Review" class="tab-pane fade">
                            <a class="btn bg-blue-btn pull-right mb-2" href="<?php echo base_url() ?>Service/addCustomerReview">
                                <?php echo lang('add_Customer_Review')?>
                            </a>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="ir_w_1"> <?php echo lang('sn'); ?></th>
                                            <th class="ir_w_9"><?php echo lang('name'); ?></th>
                                            <th class="ir_w_9"><?php echo lang('designation'); ?></th>
                                            <th class="ir_w_9"><?php echo lang('description'); ?></th>
                                            <th class="ir_w_6 not-export-col"><?php echo lang('actions'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $company = getMainCompany();
                                        $customer_reviewers = isset($company->customer_reviewers) && $company->customer_reviewers?json_decode($company->customer_reviewers):'';

                                        $i = 1;
                                        foreach ($customer_reviewers as $key=>$spns) {
                                            $current_row = json_decode($spns);
                                            ?>
                                        <tr>
                                            <td><?php echo escape_output($i++); ?></td>
                                            <td>   <?php echo escape_output($current_row->name) ?></td>
                                            <td>   <?php echo escape_output($current_row->designation) ?></td>
                                            <td>   <?php echo escape_output($current_row->description) ?></td>
                                            <td class="ir_txt_center">
                                                <div class="btn-group  actionDropDownBtn">
                                                    <button type="button" class="btn bg-blue-color dropdown-toggle"
                                                            id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i data-feather="more-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton1" role="menu">
                                                        <li><a
                                                                    href="<?php echo base_url() ?>Service/addCustomerReview/<?php echo ($key);?>"><i
                                                                        class="fa fa-pencil tiny-icon"></i><?php echo lang('edit'); ?></a>
                                                        </li>
                                                        <li><a class="delete"
                                                            href="<?php echo base_url() ?>Service/deleteCustomerReview/<?php echo ($key); ?>"><i
                                                                        class="fa fa-trash tiny-icon"></i><?php echo lang('delete'); ?></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- End Customer Review -->
                        <div id="Counter" aria-labelledby="Counter-tab" class="tab-pane fade">
                            <?php
                            $company = getMainCompany();
                            $outlet_information = isset($company->counter_details) && $company->counter_details?json_decode($company->counter_details):'';

                            $attributes = array('id' => 'restaurant_setting_form');
                            echo form_open_multipart(base_url('Service/counter/'),$attributes); ?>
                            
                            <div class="row">
                                    <div class="col-sm-12 mb-2 col-md-3">
                                        <div class="form-group">
                                            <label><?php echo lang('restaurants'); ?> <span class="required_star">*</span></label>
                                            <input tabindex="1" autocomplete="off" required type="number" id="restaurants" name="restaurants" class="form-control" placeholder="<?php echo lang('restaurants'); ?>" value="<?php echo escape_output($outlet_information->restaurants); ?>">
                                        </div>
                                        <?php if (form_error('restaurants')) { ?>
                                            <div class="callout callout-danger my-2">
                                                <?php echo form_error('restaurants'); ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="col-sm-12 mb-2 col-md-3">
                                        <div class="form-group">
                                            <label><?php echo lang('users'); ?> <span class="required_star">*</span></label>
                                            <input tabindex="2" autocomplete="off" required type="number" id="users" name="users" class="form-control" placeholder="<?php echo lang('users'); ?>" value="<?php echo escape_output($outlet_information->users); ?>">
                                        </div>
                                        <?php if (form_error('users')) { ?>
                                            <div class="callout callout-danger my-2">
                                                <?php echo form_error('users'); ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="col-sm-12 mb-2 col-md-3">
                                        <div class="form-group">
                                            <label><?php echo lang('reference'); ?> <span class="required_star">*</span></label>
                                            <input tabindex="3" autocomplete="off" required type="number" id="reference" name="reference" class="form-control" placeholder="<?php echo lang('reference'); ?>" value="<?php echo escape_output($outlet_information->reference); ?>">
                                        </div>
                                        <?php if (form_error('reference')) { ?>
                                            <div class="callout callout-danger my-2">
                                                <?php echo form_error('reference'); ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="col-sm-12 mb-2 col-md-3">
                                        <div class="form-group">
                                            <label><?php echo lang('daily_transactions'); ?> <span class="required_star">*</span></label>
                                            <input tabindex="4" autocomplete="off" required type="number" id="daily_transactions" name="daily_transactions" class="form-control" placeholder="<?php echo lang('daily_transactions'); ?>" value="<?php echo escape_output($outlet_information->daily_transactions); ?>">
                                        </div>
                                        <?php if (form_error('daily_transactions')) { ?>
                                            <div class="callout callout-danger my-2">
                                                <?php echo form_error('daily_transactions'); ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                            </div>
                            
                            <div class="box-footer px-0">
                                <button type="submit" name="submit" value="submit" class="btn bg-blue-btn col-sm-12 col-md-2"><?php echo lang('submit'); ?></button>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                        <!-- End Counter -->
                        <div id="social_link" aria-labelledby="social-tab" class="tab-pane fade">
                            <?php
                            $company = getMainCompany();
                            $outlet_information = isset($company->social_link_details) && $company->social_link_details?json_decode($company->social_link_details):'';

                            $attributes = array('id' => 'restaurant_setting_form');
                            echo form_open_multipart(base_url('Service/socialLink/'),$attributes); ?>
                            
                            <div class="row">
                                    <div class="col-sm-12 mb-2 col-md-3">
                                        <div class="form-group">
                                            <label><?php echo lang('facebook'); ?></label>
                                            <input tabindex="1" autocomplete="off"  type="text" id="facebook" name="facebook" class="form-control" placeholder="<?php echo lang('facebook'); ?>" value="<?php echo escape_output($outlet_information->facebook); ?>">
                                        </div>
                                        <?php if (form_error('facebook')) { ?>
                                            <div class="callout callout-danger my-2">
                                                <?php echo form_error('facebook'); ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="col-sm-12 mb-2 col-md-3">
                                        <div class="form-group">
                                            <label><?php echo lang('twitter'); ?></label>
                                            <input tabindex="2" autocomplete="off"  type="text" id="twitter" name="twitter" class="form-control" placeholder="<?php echo lang('twitter'); ?>" value="<?php echo escape_output($outlet_information->twitter); ?>">
                                        </div>
                                        <?php if (form_error('twitter')) { ?>
                                            <div class="callout callout-danger my-2">
                                                <?php echo form_error('twitter'); ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="col-sm-12 mb-2 col-md-3">
                                        <div class="form-group">
                                            <label><?php echo lang('instagram'); ?></label>
                                            <input tabindex="3" autocomplete="off"  type="text" id="instagram" name="instagram" class="form-control" placeholder="<?php echo lang('instagram'); ?>" value="<?php echo escape_output($outlet_information->instagram); ?>">
                                        </div>
                                        <?php if (form_error('instagram')) { ?>
                                            <div class="callout callout-danger my-2">
                                                <?php echo form_error('instagram'); ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="col-sm-12 mb-2 col-md-3">
                                        <div class="form-group">
                                            <label><?php echo lang('youtube'); ?></label>
                                            <input tabindex="4" autocomplete="off"  type="text" id="youtube" name="youtube" class="form-control" placeholder="<?php echo lang('youtube'); ?>" value="<?php echo escape_output($outlet_information->youtube); ?>">
                                        </div>
                                        <?php if (form_error('youtube')) { ?>
                                            <div class="callout callout-danger my-2">
                                                <?php echo form_error('youtube'); ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                            </div>
                                
                            <div class="box-footer px-0">
                                <button type="submit" name="submit" value="submit" class="btn bg-blue-btn col-sm-12 col-md-2"><?php echo lang('submit'); ?></button>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>

                <!-- /.box-body -->
            </div>

        </div>
   
        
</section>
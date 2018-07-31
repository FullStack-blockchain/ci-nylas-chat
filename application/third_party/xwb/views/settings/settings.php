<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="loader"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="panel panel-success">
            <div class="panel-heading">
              <h3 class="panel-title"><?php echo $page_title; ?></h3>
            </div>
            <div class="panel-body">
                <form method="POST" class="form-horizontal" id="xwb-form-settings" name="form_settings" action="<?php echo base_url("settings/saveSettings") ?>" enctype="multipart/form-data">
                <h3>Company Profile</h3>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="logo">Upload Logo :</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                        
                            <div id='preview' class="center" style="max-height: 300px;">
                            <img src="<?php echo base_url('image?path='.getConfig('logo')); ?>" alt="<?php echo getConfig('logo'); ?>" class="img-responsive" id="company_logo">
                            </div>
                            <input type="file" name="logo" id="logo" accept="image/*" />
                            <hr />
                            <code class="help">Max( width:500pixel, height:300pixel, size: 1MB )</code>
                            <hr />
                            <p class="error">Please make the assets/images/ writable. <br /><strong>Example</strong>:<code>sudo chmod 777 -R assets/images</code></p>
                            <hr />
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="company_name">Company Name 
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="company_name" name="company_name" class="form-control col-md-7 col-xs-12" type="text" value="<?php echo getConfig('company_name'); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="company_address">Company Address 
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="company_address" name="company_address" class="form-control col-md-7 col-xs-12" type="text" value="<?php echo getConfig('company_address'); ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="company_phone">Company Phone 
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="company_phone" name="company_phone" class="form-control col-md-7 col-xs-12" type="text" value="<?php echo getConfig('company_phone'); ?>">
                        </div>
                    </div>
                    <hr />
<h3>Purchase Order </h3><em class="text-danger">This will appear in the Purchase Order print PDF</em>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="penalty_clause">P.O. Penalty Clause: 
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <textarea name="penalty_clause" id="penalty_clause" class="form-control"><?php echo getConfig('penalty_clause'); ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="PO_note">P.O. Note: 
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <textarea name="PO_note" id="PO_note" class="form-control"><?php echo getConfig('PO_note'); ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="PO_reminder">P.O. Reminder: 
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <textarea name="PO_reminder" id="PO_reminder" class="form-control"><?php echo getConfig('PO_reminder'); ?></textarea>
                        </div>
                    </div>
                    <hr />
<h3>Print Request</h3><em class="text-danger">These are the name of the person that will show on Print Request Form PDF</em>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="with_budget">With Budget: 
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="with_budget" name="with_budget" class="form-control col-md-7 col-xs-12" type="text" value="<?php echo getConfig('with_budget'); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="budget_certified_by">Budget Certified By: 
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="budget_certified_by" name="budget_certified_by" class="form-control col-md-7 col-xs-12" type="text" value="<?php echo getConfig('budget_certified_by'); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="approve_purchased_by">Approved or Purchased By: 
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="approve_purchased_by" name="approve_purchased_by" class="form-control col-md-7 col-xs-12" type="text" value="<?php echo getConfig('approve_purchased_by'); ?>">
                        </div>
                    </div>
                    <hr />
<h3>Dashboard</h3>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="announcement">Announcement: 
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <textarea name="announcement" id="announcement" class="form-control"><?php echo getConfig('announcement'); ?></textarea>
                        </div>
                    </div>

                    <hr />
                    <h3>Miscellaneous</h3>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="approve_purchased_by">Board approval amount: 
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="board_approval_amount" name="board_approval_amount" class="form-control col-md-7 col-xs-12" type="text" value="<?php echo getConfig('board_approval_amount'); ?>">
                          <span class="help">Forward to board when total amount reached more than the specified amount</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="company_phone">Logo to use
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <div class="radio">
                            <label>
                              <input type="radio" value="logo" name="logo_to_use" <?php echo (getConfig('logo_to_use')=='logo'?'checked':'') ?> /> Logo
                            </label>
                          </div>
                          <div class="radio">
                            <label>
                              <input type="radio" value="company_name" name="logo_to_use" <?php echo (getConfig('logo_to_use')=='company_name'?'checked':'') ?>> Company Name
                            </label>
                          </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="company_phone">Show default users in login
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <div class="radio">
                            <label>
                              <input type="radio" value="1" name="login_default_users" <?php echo (getConfig('login_default_users')=='1'?'checked':'') ?>> Yes
                            </label>
                          </div>
                          <div class="radio">
                            <label>
                              <input type="radio" value="" name="login_default_users" <?php echo (getConfig('login_default_users')==''?'checked':'') ?>> No
                            </label>
                          </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email_notification">Email Notification
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <div class="radio">
                            <label>
                              <input type="radio" value="1" name="email_notification" <?php echo (getConfig('email_notification')=='1'?'checked':'') ?>> Enabled
                            </label>
                          </div>
                          <div class="radio">
                            <label>
                              <input type="radio" value="" name="email_notification" <?php echo (getConfig('email_notification')==''?'checked':'') ?>> Disabled
                            </label>
                          </div>
                        </div>
                    </div>
                <?php echo form_close(); ?>
            </div>
            <div class="panel-footer">
                <a class="btn btn-info xwb-update-settings">Update</a>
            </div>
        </div>

    </div>
</div>


<script src="<?php echo base_url('assets/js/jquery.form.js'); ?>"></script>
<!-- Javascript variable from php for this page here -->
<script type="text/javascript">
    var xwb_var = {};
    xwb_var.varUpdateSettings = '<?php echo base_url('settings/updateSettings'); ?>';
    xwb_var.imgLoading = '<?php echo base_url('assets/images/loader.gif'); ?>';
</script>

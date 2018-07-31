<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="panel panel-success">
            <div class="panel-heading">
              <h3 class="panel-title"><?php echo $page_title; ?></h3>
            </div>
            <div class="panel-body">
                <!-- Smart Wizard -->
                <div id="new_request_wizard" class="new_request_wizard">
                    <div id="wizard" class="form_wizard wizard_horizontal wizard">
                      <ul class="wizard_steps">
                        <li>
                          <a href="#step-1">
                            <span class="step_no">1</span>
                            <span class="step_descr">
                                              Step 1<br />
                                              <small>Request Information</small>
                                          </span>
                          </a>
                        </li>
                        <li>
                          <a href="#step-2">
                            <span class="step_no">2</span>
                            <span class="step_descr">
                                              Step 2<br />
                                              <small>Request Items</small>
                                          </span>
                          </a>
                        </li>
                        <li>
                          <a href="#step-3">
                            <span class="step_no">3</span>
                            <span class="step_descr">
                                              Step 3<br />
                                              <small>Verify Request</small>
                                          </span>
                          </a>
                        </li>

                      </ul>
                      <div id="step-1">
<form class="form-horizontal form-label-left">

                          <span class="section">Request Information</span>

                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3" for="request_name">Type of Request <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6">
                              <input type="text" id="request_name" name="request_name" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                          </div>
                          <!-- <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3" for="purpose">Purpose <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6">
                              <input type="text" id="purpose" name="purpose" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                          </div> -->
                          <!-- <div class="form-group">
                            <label for="priority_level" class="control-label col-md-3 col-sm-3">Priority Level</label>
                            <div class="col-md-6 col-sm-6">
                              <select name="priority_level" id="priority_level" class="form-control">
                                  <option value="1">Normal</option>
                                  <option value="2">Urgent</option>
                                  option
                              </select>
                            </div>
                          </div> -->
                        <div class="form-group">
                            <label for="date_needed" class="control-label col-md-3 col-sm-3">Date Needed</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" id="date_needed" name="date_needed" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                          </div>
                        </form>
                      </div>
                      <div id="step-2">
                          <form class="form-horizontal form-label-left">
                                <h2 class="StepTitle">Items</h2><a class="btn btn-success btn-xs" href="javascript:;" onClick="xwb.addItem()">
                                                      <i class="fa fa-plus"></i> Add Item
                                                    </a>
                                                    <hr class="clearfix" />
                                <div class="table-responsive">
                                    <table class="table table_items">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Description</th>
                                                <th>Qty</th>
                                                <th>Note</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><input type="text" name="item[]" required="required" class="form-control col-md-7 col-xs-12 item"></td>
                                                <td><textarea name="description[]" class="form-control description"></textarea></td>
                                                <td><input type="text" name="qty[]" required="required" class="form-control col-md-7 col-xs-12 qty"></td>
                                                <td><textarea name="requestor_note[]" class="form-control requestor_note"></textarea></td>
                                                <td>
                                                    <!-- <a class="btn btn-success btn-xs" href="javascript:;" onClick="xwb.addItem(this)">
                                                                                                          <i class="fa fa-plus"></i> Add Item
                                                                                                        </a> -->
                                                    <a class="btn btn-danger btn-xs" href="javascript:;" onClick="xwb.removeItem(this)">
                                                      <i class="fa fa-minus"></i> Remove Item
                                                    </a>

                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                      </div>
                      <div id="step-3">
                        <h2 class="StepTitle">Review Request</h2>
                        <hr class="clearfix" />
                        <div class="row">
                            <div class="col-md-12 review_request">
                                
                            </div>
                        </div>
                      </div>


                   </div>
                   <!-- End SmartWizard Content -->

                   <div class="actionBarClone">
                                <a href="javascript:;" class="btn btn-primary pull-right disabled finish">File Request</a>
                                <a href="javascript:;" class="btn btn-default pull-right next">Next</a>
                                <a href="javascript:;" class="btn btn-default pull-left prev">Previous</a>
                        </div>
                    
                    </div>
            </div>

        </div>

    </div>
</div>

<!-- Javascript variable from php for this page here -->
<script src="<?php echo base_url('assets/vendors/jQuery-Smart-Wizard/js/jquery.smartWizard.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js'); ?>"></script>

<script type="text/javascript">
var varNewRequestSteps = '<?php echo base_url('request/newRequestSteps'); ?>';
var varFileRequest = '<?php echo base_url('request/fileRequest'); ?>';

<?php
if ($current_user->group_name=='canvasser') {
    $req_url = 'canvasser/request';
} else {
    $req_url = 'request';
}
?>

var varRequest = '<?php echo base_url($req_url); ?>';
</script>

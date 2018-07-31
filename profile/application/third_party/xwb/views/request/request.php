<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="panel panel-success">
            <div class="panel-heading">
              <h3 class="panel-title"><?php echo $page_title; ?></h3>
              <em class="text-danger">If your request is denied, you can view the message or reason in the detailed view on your request by clicking the link in "Type of Request" column</em>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table_request_list">
                      <thead>
                        <tr>
                          <th>P.R. #</th>
                          <th>Type of Request</th>
                          <th>Date Requested</th>
                          <th>Date Needed</th>
                          <th>Purpose</th>
                          <th>Purchasing Remarks</th>
                          <th>Items</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        
                      </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-footer">
                <a href="<?php echo base_url('request/newreq'); ?>" class="btn btn-info">New Request</a>
            </div>
        </div>

    </div>
</div>

<script src="<?php echo base_url('assets/js/jquery.form.js'); ?>"></script>
<!-- Javascript variable from php for this page here -->
<script type="text/javascript">
    var xwb_var = {};
    xwb_var.varGetRequest = '<?php echo base_url('request/getRequest'); ?>';
    xwb_var.varGetRequestList = '<?php echo base_url('request/getRequestList'); ?>';
    xwb_var.varEditRequest = '<?php echo base_url('request/editRequest'); ?>';
    xwb_var.varUpdateRequest = '<?php echo base_url('request/updateRequest'); ?>';
    xwb_var.varAddRequest = '<?php echo base_url('request/addRequest'); ?>';
    xwb_var.varDeleteRequest = '<?php echo base_url('request/deleteRequest'); ?>';

    xwb_var.varGetAttachment = '<?php echo base_url('attachment/getAttachment'); ?>';
    xwb_var.varAddAttachment = '<?php echo base_url('attachment/addAttachment'); ?>';
    xwb_var.varRemoveAttachment = '<?php echo base_url('attachment/removeAttachment'); ?>';

    xwb_var.varGetRequestItems = '<?php echo base_url('request/getRequestItems'); ?>';
    xwb_var.varSetExpenditureItem = '<?php echo base_url('request/setExpenditureItem'); ?>';
</script>

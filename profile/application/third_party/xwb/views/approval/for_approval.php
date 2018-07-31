<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="loader"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="panel panel-success">
            <div class="panel-heading">
              <h3 class="panel-title"><?php echo $page_title; ?></h3>
              <em class="text-danger">Click the "View Items" to approve or deny each item</em>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table_forapproval_request">
                      <thead>
                        <tr>
                          <th>P.R. #</th>
                          <th>Type of Request</th>
                          <th>Date Requested</th>
                          <th>User</th>
                          <th>Purpose</th>

                          <th>Items</th>
                          <th>Date Needed</th>
                          <th>Action</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        
                      </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-footer">

            </div>
        </div>

    </div>
</div>

<!-- Javascript variable from php for this page here -->
<script type="text/javascript">
    var xwb_var = {};
    xwb_var.varGetReqForApproval = '<?php echo base_url('approval/getReqForApproval'); ?>';
    xwb_var.varGetReqApprovaltItems = '<?php echo base_url('approval/getReqApprovaltItems'); ?>';
    xwb_var.varApproveToPurchasing = '<?php echo base_url('request/approveToPurchasing'); ?>';
    xwb_var.varApproveItem = '<?php echo base_url('approval/approveItem'); ?>';
    xwb_var.varApproveAllItem = '<?php echo base_url('approval/approveAllItem'); ?>';
    xwb_var.varDenyItem = '<?php echo base_url('approval/denyItem'); ?>';
    xwb_var.varRemoveAttachment = '<?php echo base_url('attachment/removeAttachment'); ?>';

    xwb_var.varGetAttachment = '<?php echo base_url('attachment/getAttachment'); ?>';
</script>
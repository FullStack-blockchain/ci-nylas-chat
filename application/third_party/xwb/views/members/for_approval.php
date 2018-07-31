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
                          <th>Head Department Remarks</th>
                          <th>Items</th>
                          <th>Date Needed</th>
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

            </div>
        </div>

    </div>
</div>

<!-- Javascript variable from php for this page here -->
<script type="text/javascript">
    var varGetReqForApproval = '<?php echo base_url('request/getReqForApproval'); ?>';
    var varGetRequestItems = '<?php echo base_url('request/getRequestItems'); ?>';
    var varApproveToPurchasing = '<?php echo base_url('request/approveToPurchasing'); ?>';
</script>
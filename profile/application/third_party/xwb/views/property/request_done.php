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
                <div class="table-responsive">
                    <table class="table table-striped table_request_done">
                      <thead>
                        <tr>
                            <th>P.R #</th>
                            <th>PO #</th>
                            <th>Type of Request</th>
                            <th>User</th>
                            <th>Department</th>
                            <th>Branch</th>
                            <th>Purpose</th>
                            <th>Date Requested</th>
                            <th>ETA</th>
                            <th>Date Received</th>
                            <th>Remarks</th>
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
            </div>
        </div>

    </div>
</div>
<!-- Javascript variable from php for this page here -->
<script type="text/javascript">
xwb_var.varGetProperties = '<?php echo base_url('property/getProperties'); ?>';
xwb_var.varReceivedItems = '<?php echo base_url('property/receivedItems'); ?>';
xwb_var.varGetPropertyItems = '<?php echo base_url('property/getPropertyItems'); ?>';
</script>
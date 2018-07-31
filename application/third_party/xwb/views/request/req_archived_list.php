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
                    <table class="table table-striped table_archivereq_list">
                      <thead>
                        <tr>
                          <th>P.R. #</th>
                          <th>Type of Request</th>
                          <th>Date Requested</th>
                          <th>User</th>
                          <th>Department</th>
                          <th>Branch</th>
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
xwb_var.varGetArchRequest = '<?php echo base_url('request/getArchRequest'); ?>';
xwb_var.varGetRequestItems = '<?php echo base_url('request/getRequestItems'); ?>';
xwb_var.varGetAttachment = '<?php echo base_url('attachment/getAttachment'); ?>';
xwb_var.varUnArchiveRequest = '<?php echo base_url('request/unArchiveRequest'); ?>';
</script>

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
                <div class="table-responsive">
                    <table class="table table-striped table_request_list">
                      <thead>
                        <tr>
                          <th>P.R. #</th>
                          <th>Type of Request</th>
                          <th>Date Requested</th>
                          <th>Date Needed</th>
                          <th>Items</th>
                          <th>Purpose</th>
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
                <!-- <a href="<?php echo base_url('request/newreq'); ?>" class="btn btn-info">New Request</a> -->
            </div>
        </div>

    </div>
</div>

<!-- Javascript variable from php for this page here -->
<script type="text/javascript">
    var varGetRequest = '<?php echo base_url('request/getRequest'); ?>';
    var varGetRequestList = '<?php echo base_url('request/getRequestList'); ?>';
    var varEditRequest = '<?php echo base_url('request/editRequest'); ?>';
    var varUpdateRequest = '<?php echo base_url('request/updateRequest'); ?>';
    var varAddRequest = '<?php echo base_url('request/addRequest'); ?>';
    var varDeleteRequest = '<?php echo base_url('request/deleteRequest'); ?>';


    var varGetRequestItems = '<?php echo base_url('request/getRequestItems'); ?>';
</script>

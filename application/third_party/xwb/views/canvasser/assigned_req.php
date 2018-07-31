<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="loader"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="panel panel-success">
            <div class="panel-heading">
              <h3 class="panel-title"><?php echo $page_title; ?></h3>
              <em class="text-danger">Click action->update item for canvassing system</em>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table_assigned_req">
                      <thead>
                        <tr>
                          <th>Request No.</th>
                          <th>Request Name</th>
                          <th>Date Assigned</th>
                          <th>Date Needed</th>
                          <th>Items</th>
                          <th>Total Amount</th>
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
<script src="<?php echo base_url('assets/js/jquery.form.js'); ?>"></script>
<!-- Javascript variable from php for this page here -->
<script type="text/javascript">
    xwb_var.varGetAssignedRequest = '<?php echo base_url('canvasser/getAssignedRequest'); ?>';
    xwb_var.varGetRequestItemsCanvasser = '<?php echo base_url('request/getRequestItemsCanvasser'); ?>';
    xwb_var.varRequestDone = '<?php echo base_url('canvasser/requestDone'); ?>';
    xwb_var.varAssignBudget = '<?php echo base_url('budget/assignBudget'); ?>';
    xwb_var.varAssignPurchasing = '<?php echo base_url('canvasser/assignPurchasing'); ?>';
    xwb_var.varGetResponse = '<?php echo base_url('canvasser/getResponse'); ?>';
    xwb_var.varRespond = '<?php echo base_url('canvasser/respond'); ?>';
    xwb_var.varReturnRequisitioner = '<?php echo base_url('canvasser/returnRequisitioner'); ?>';
    xwb_var.varRemoveItem = '<?php echo base_url('request/removeItem'); ?>';
    xwb_var.varSupplierSummary = '<?php echo base_url('canvasser/supplierSummary'); ?>';
    xwb_var.varGetAttachment = '<?php echo base_url('attachment/getAttachment'); ?>';
    xwb_var.varAddAttachment = '<?php echo base_url('attachment/addAttachment'); ?>';
    <?php
    $BDUsersOptions = "<option>Select User</option>";
    foreach ($budget_users as $key => $value) {
        $BDUsersOptions .= '<option value="'.$value->id.'">'.ucwords($value->first_name." ".$value->last_name).'</option>';
    }
    ?>
    xwb_var.BDUsersOptions = '<?php echo $BDUsersOptions; ?>';


    <?php
    $PDUsersOptions = "<option>Select User</option>";
    foreach ($admin_users as $key => $value) {
        $PDUsersOptions .= '<option value="'.$value->id.'">'.ucwords($value->first_name." ".$value->last_name).'</option>';
    }
    ?>
    xwb_var.PDUsersOptions = '<?php echo $PDUsersOptions; ?>';
</script>

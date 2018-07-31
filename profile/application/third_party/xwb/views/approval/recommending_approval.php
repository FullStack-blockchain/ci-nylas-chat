<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="loader"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="panel panel-success">
            <div class="panel-heading">
              <a href="javascript:history.go(-1);" class="btn btn-warning">Back</a><h3 class="panel-title"><?php echo $page_title; ?></h3>
              <em class="text-danger">After assigning to the head users, you can click the “back” button to go back to the list of head approval request.</em>
            </div>
            <div class="panel-body">
                <div class="col-md-4">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">Users</h3>
                            <em class="text-danger">These users are the head of all the department.</em>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Select Head User</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <select name="head_users" id="head_users" style="width: 100%;">
                                        <option>Select User</option>

                                    <?php
                                    $user_id = $this->log_user_data->user_id;
                                    foreach ($head_users as $key => $value) :
                                        if ($user_id == $value->id) {
                                            $you = '(You)';
                                        } else {
                                            $you = '';
                                        }
                                    ?>
                                        <option value="<?php echo $value->id; ?>"> <?php echo $you." ".ucwords($value->first_name." ".$value->last_name." (".$value->description.")"); ?> </option>
                                    <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <a href="javascript:;" class="btn btn-danger delete_approve_user disabled" onClick="xwb.deleteApprovingUser()" title="Delete as Approving Officer">Delete as Approving Officer</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">Items</h3>
                            <em class="text-danger">Check an item(s) you want to assign to the selected head user</em>
                        </div>
                        <div class="panel-body">
                            <div class="checkbox">
                                <label>
                                  <input name="items[]" id="checkall" class="checkall" type="checkbox"> <strong>Check all</strong>
                                </label>
                          </div>
                          <hr class="hr-inherit-margin" />
                            <?php foreach ($items as $key => $value) : ?>
                                <div class="checkbox">
                                    <label>
                                      <input name="items[]" id="items" class="item_<?php echo $value->id; ?> items" value="<?php echo $value->id; ?>" type="checkbox"> <?php echo $value->product_name; ?>
                                    </label>
                              </div>

                            <?php endforeach; ?>

                        </div>
                        <div class="panel-footer">
                            <a href="javascript:;" onClick="xwb.assignItems()" class="btn btn-success assign disabled" title="Tag to head user">Assign</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">List</h3>
                            <em class="text-danger">These are the assigned items of the selected head user</em>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table_items_approval">
                                    <thead>
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
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
xwb_var.varGetItemsForApproval = "<?php echo base_url('approval/getItemsForApproval'); ?>";
xwb_var.varGetApprovalItems = "<?php echo base_url('approval/getApprovalItems'); ?>";
xwb_var.varAssignItems = "<?php echo base_url('approval/assignItems'); ?>";
xwb_var.varDeleteApprovingUser = "<?php echo base_url('approval/deleteApprovingUser'); ?>";

xwb_var.requestID = <?php echo $request_id; ?>;
xwb_var.approvalID = <?php echo $approval_id; ?>;

</script>
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
                    <table class="table table-striped table_usergroup">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Name</th>
                          <th>Description</th>
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
    var xwb_var = {};
    xwb_var.varGetUGroup = '<?php echo base_url('user_group/getUGroup'); ?>';
    xwb_var.varEditUGroup = '<?php echo base_url('user_group/editUGroup'); ?>';
    xwb_var.varUpdateUGroup = '<?php echo base_url('user_group/updateUGroup'); ?>';
    xwb_var.varAddUGroup = '<?php echo base_url('user_group/addUGroup'); ?>';
    xwb_var.varDeleteUGroup = '<?php echo base_url('user_group/deleteUGroup'); ?>';

</script>
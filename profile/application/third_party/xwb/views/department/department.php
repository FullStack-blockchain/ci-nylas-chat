<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="panel panel-success">
            <div class="panel-heading">
              <h3 class="panel-title">Departments</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table_department">
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
                <a href="" class="btn btn-info xwb-add-department">Add Department</a>
            </div>
        </div>

    </div>
</div>

<!-- Javascript variable from php for this page here -->
<script type="text/javascript">
    xwb_var = {};
    xwb_var.varGetDept = '<?php echo base_url('department/getDept'); ?>';
    xwb_var.varEditDept = '<?php echo base_url('department/editDept'); ?>';
    xwb_var.varUpdateDept = '<?php echo base_url('department/updateDept'); ?>';
    xwb_var.varAddDept = '<?php echo base_url('department/addDept'); ?>';
    xwb_var.varDeleteDept = '<?php echo base_url('department/deleteDept'); ?>';

</script>
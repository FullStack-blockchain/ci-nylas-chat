<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="loader"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="panel panel-success">
            <div class="panel-heading">
              <h3 class="panel-title">Users</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table_users">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Branch</th>
                          <th>Department</th>
                          <th>First Name</th>
                          <th>Last Name</th>
                          <th>Email</th>
                          <th>Role</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        
                      </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-footer">
                <a href="" class="btn btn-info xwb-add-user">Add Users</a>
            </div>
        </div>

    </div>
</div>

<!-- Javascript variable from php for this page goes here -->
<script type="text/javascript">
    var xwb_var = {};

    xwb_var.varGetUsers = '<?php echo base_url('user/getUsers'); ?>';
    xwb_var.varEditUser = '<?php echo base_url('user/editUser'); ?>';
    xwb_var.varUpdateUser = '<?php echo base_url('user/updateUser'); ?>';
    xwb_var.varAddUser = '<?php echo base_url('user/addUser'); ?>';
    xwb_var.varDeleteUser = '<?php echo base_url('user/deleteUser'); ?>';
    xwb_var.varActivateUser = '<?php echo base_url('user/activateUser'); ?>';
    xwb_var.varDeactivateUser = '<?php echo base_url('user/deactivateUser'); ?>';
    xwb_var.varChangePass = '<?php echo base_url('user/changePass'); ?>';


    //user group option list
    <?php
        $utype = '<select class="form-control" name="group" id="group">';
    foreach ($groups as $key => $value) {
        $utype .= '<option value="'.$value->id.'">'.$value->description.'</option>';
    }
        $utype .= '</select>';
    ?>
    xwb_var.usertype_option = '<?php echo $utype; ?>';


    //Branches option list
    <?php
        $branch = '<select class="form-control" name="branch" id="branch">';
    foreach ($branches as $key => $value) {
        $branch .= '<option value="'.$value->id.'">'.$value->description.'</option>';
    }
        $branch .= '</select>';
    ?>
    xwb_var.branch_option = '<?php echo $branch; ?>';

    //Department option list
    <?php
        $dept = '<select class="form-control" name="department" id="department">';
    foreach ($departments as $key => $value) {
        $dept .= '<option value="'.$value->id.'">'.$value->description.'</option>';
    }
        $dept .= '</select>';
    ?>
    xwb_var.dept_option = '<?php echo $dept; ?>';
</script>
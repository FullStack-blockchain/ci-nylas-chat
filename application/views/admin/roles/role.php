<?php init_single_head(); ?>
<style>
    .table-responsive table .custom-checkbox input[type=checkbox] ~ .custom-control-indicator,
    .table-responsive table .form-check-label input[type=checkbox] ~ .checkbox-icon {
        position: absolute;
        left: calc(50% - 20px)
    }
    .table-responsive table .custom-checkbox input[type=checkbox]:checked ~ .custom-control-indicator,
    .table-responsive table .custom-checkbox input[type=checkbox]:checked ~ .custom-control-indicator{color: #F44336}
</style>
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>

            <div class="content custom-scrollbar">

                <div id="manage_role" class="page-layout simple left-sidebar-floating">

                    <div class="page-content p-4 p-sm-6">

                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="f-20">
                                            <?php echo $title; ?>
                                        </h4>
                                        <hr class="mt-4 mb-4"/>
                                        <?php if (isset($role)) { ?>
                                            <a href="<?php echo admin_url('roles/role'); ?>"
                                               class="btn btn-success pull-right mbot20 display-block"><?php echo _l('new_role'); ?></a>
                                            <div class="clearfix"></div>
                                        <?php } ?>
                                        <?php echo form_open($this->uri->uri_string()); ?>
                                        <?php if (isset($role)) { ?>
                                            <?php if (total_rows('tblstaff', array('role' => $role->roleid)) > 0) { ?>
                                                <div class="alert alert-warning bold mt-4">
                                                    <?php echo _l('change_role_permission_warning'); ?>

                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" name="update_staff_permissions" class="form-check-input"
                                                                   id="update_staff_permissions">
                                                            <span class="checkbox-icon"></span>
                                                            <span><?php echo _l('role_update_staff_permissions'); ?></span>
                                                        </label>
                                                    </div>

                                                </div>
                                            <?php } ?>
                                        <?php } ?>
                                        <?php $attrs = (isset($role) ? array() : array('autofocus' => true)); ?>
                                        <?php $value = (isset($role) ? $role->name : ''); ?>
                                        <?php echo render_input('name', 'role_add_edit_name', $value, 'text', $attrs,[],'pt-0'); ?>
                                        <div class="table-responsive mt-5">
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th class="bold"><?php echo _l('permission'); ?></th>
                                                    <th class="text-center bold"><?php echo _l('permission_view'); ?></th>
                                                    <th class="text-center bold"><?php echo _l('permission_view_own'); ?></th>
                                                    <th class="text-center bold"><?php echo _l('permission_create'); ?></th>
                                                    <th class="text-center bold"><?php echo _l('permission_edit'); ?></th>
                                                    <th class="text-center text-danger bold"><?php echo _l('permission_delete'); ?></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $conditions = get_permission_conditions();
                                                foreach ($permissions as $permission) {
                                                    $permission_condition = $conditions[$permission['shortname']];
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <?php echo _l($permission['shortname']); ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php if ($permission_condition['view'] == true) {
                                                                $statement = '';
                                                                if (isset($role)) {
                                                                    if (total_rows('tblrolepermissions', array('roleid' => $role->roleid, 'permissionid' => $permission['permissionid'], 'can_view' => 1)) > 0) {
                                                                        $statement = 'checked';
                                                                    }

                                                                    if (total_rows('tblrolepermissions', array('roleid' => $role->roleid, 'permissionid' => $permission['permissionid'], 'can_view_own' => 1)) > 0) {
                                                                        $statement = 'disabled';
                                                                    }
                                                                }
                                                                ?>
                                                                <?php if (isset($permission_condition['help'])) {
                                                                    echo '<i class="fa fa-question-circle text-danger" data-toggle="tooltip" data-title="' . $permission_condition['help'] . '"></i>';
                                                                }
                                                                ?>

                                                                <div class="form-check">
                                                                    <label class="form-check-label">
                                                                        <input type="checkbox"
                                                                               data-can-view <?php echo $statement; ?>
                                                                               name="view[]"
                                                                               class="form-check-input"
                                                                               value="<?php echo $permission['permissionid']; ?>">
                                                                        <span class="checkbox-icon"></span>
                                                                        <span></span>
                                                                    </label>
                                                                </div>

                                                            <?php } ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php if ($permission_condition['view_own'] == true) {
                                                                $statement = '';
                                                                if (isset($role)) {
                                                                    if (total_rows('tblrolepermissions', array('roleid' => $role->roleid, 'permissionid' => $permission['permissionid'], 'can_view_own' => 1)) > 0) {
                                                                        $statement = 'checked';
                                                                    }

                                                                    if (total_rows('tblrolepermissions', array('roleid' => $role->roleid, 'permissionid' => $permission['permissionid'], 'can_view' => 1)) > 0) {
                                                                        $statement = 'disabled';
                                                                    }


                                                                }
                                                                ?>

                                                                <div class="form-check">
                                                                    <label class="form-check-label">
                                                                        <input type="checkbox" <?php echo $statement; ?>
                                                                               data-shortname="<?php echo $permission['shortname']; ?>"
                                                                               data-can-view-own
                                                                               name="view_own[]"
                                                                               class="form-check-input"
                                                                               value="<?php echo $permission['permissionid']; ?>">
                                                                        <span class="checkbox-icon"></span>
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                            <?php } else if ($permission['shortname'] == 'customers') {
                                                                echo '<i class="fa fa-question-circle mtop15" data-toggle="tooltip" data-title="' . _l('permission_customers_based_on_admins') . '"></i>';
                                                            } else if ($permission['shortname'] == 'projects') {
                                                                echo '<i class="fa fa-question-circle mtop25" data-toggle="tooltip" data-title="' . _l('permission_projects_based_on_assignee') . '"></i>';
                                                            } else if ($permission['shortname'] == 'tasks') {
                                                                echo '<i class="fa fa-question-circle mtop25" data-toggle="tooltip" data-title="' . _l('permission_tasks_based_on_assignee') . '"></i>';
                                                            } else if ($permission['shortname'] == 'payments') {
                                                                echo '<i class="fa fa-question-circle mtop15" data-toggle="tooltip" data-title="' . _l('permission_payments_based_on_invoices') . '"></i>';
                                                            } ?>
                                                        </td>

                                                        <td class="text-center">
                                                            <?php if ($permission_condition['create'] == true) {
                                                                $statement = '';
                                                                if (isset($role)) {
                                                                    if (total_rows('tblrolepermissions', array('roleid' => $role->roleid, 'permissionid' => $permission['permissionid'], 'can_create' => 1)) > 0) {
                                                                        $statement = 'checked';
                                                                    }
                                                                }
                                                                ?>
                                                                <div class="form-check">
                                                                    <label class="form-check-label">
                                                                        <input type="checkbox"
                                                                               class="form-check-input"
                                                                               data-shortname="<?php echo $permission['shortname']; ?>"
                                                                               data-can-create <?php echo $statement; ?>
                                                                               name="create[]"
                                                                               value="<?php echo $permission['permissionid']; ?>">
                                                                        <span class="checkbox-icon"></span>
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php if ($permission_condition['edit'] == true) {
                                                                $statement = '';
                                                                if (isset($role)) {
                                                                    if (total_rows('tblrolepermissions', array('roleid' => $role->roleid, 'permissionid' => $permission['permissionid'], 'can_edit' => 1)) > 0) {
                                                                        $statement = 'checked';
                                                                    }
                                                                }
                                                                ?>
                                                                <div class="form-check">
                                                                    <label class="form-check-label">
                                                                        <input type="checkbox"
                                                                               data-shortname="<?php echo $permission['shortname']; ?>"
                                                                               data-can-edit <?php echo $statement; ?>
                                                                               name="edit[]"
                                                                               class="form-check-input"
                                                                               value="<?php echo $permission['permissionid']; ?>">
                                                                        <span class="checkbox-icon"></span>
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php if ($permission_condition['delete'] == true) {
                                                                $statement = '';
                                                                if (isset($role)) {
                                                                    if (total_rows('tblrolepermissions', array('roleid' => $role->roleid, 'permissionid' => $permission['permissionid'], 'can_delete' => 1)) > 0) {
                                                                        $statement = 'checked';
                                                                    }
                                                                }
                                                                ?>
                                                                <div class="form-check">
                                                                    <label class="custom-control custom-checkbox">
                                                                        <input type="checkbox"
                                                                               class="custom-control-input"
                                                                               data-shortname="<?php echo $permission['shortname']; ?>"
                                                                               data-can-delete <?php echo $statement; ?>
                                                                               name="delete[]"
                                                                               value="<?php echo $permission['permissionid']; ?>">
                                                                        <span class="custom-control-indicator"></span>
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                            <button type="submit"
                                                    class="btn btn-info pull-right mt-5"><?php echo _l('submit'); ?></button>
                                            <?php echo form_close(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php init_tail(); ?>
<script>
    $(function () {
        _validate_form($('form'), {name: 'required'});
    });
</script>
</body>
</html>

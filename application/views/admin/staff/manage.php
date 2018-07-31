<?php init_single_head(); ?>
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content">

                <div id="manage_admin_staff" class="page-layout simple left-sidebar-floating">

                    <div class="page-content p-4 p-sm-6">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="card">
                                    <div class="card-header">
                                        <?php if (has_permission('staff', '', 'create')) { ?>
                                            <div class="_buttons">
                                                <a href="<?php echo admin_url('staff/member'); ?>"
                                                   class="btn btn-secondary pull-left display-block"><?php echo _l('new_staff'); ?></a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="card-body">
                                        <?php
                                        $table_data = array(
                                            _l('staff_dt_name'),
                                            _l('staff_dt_email'),
                                            _l('staff_dt_last_Login'),
                                            _l('staff_dt_active'),
                                        );
                                        $custom_fields = get_custom_fields('staff', array('show_on_table' => 1));
                                        foreach ($custom_fields as $field) {
                                            array_push($table_data, $field['name']);
                                        }
                                        render_datatable($table_data, 'staff');
                                        ?>
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

<div class="modal fade" id="delete_staff" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <?php echo form_open(admin_url('staff/delete', array('delete_staff_form'))); ?>
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo _l('delete_staff'); ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="delete_id">
                    <?php echo form_hidden('id'); ?>
                </div>
                <p><?php echo _l('delete_staff_info'); ?></p>
                <?php
                echo render_select('transfer_data_to', $staff_members, array('staffid', array('firstname', 'lastname')), 'staff_member', get_staff_user_id(), array(), array(), '', '', false);
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-danger _delete"><?php echo _l('confirm'); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php init_tail(); ?>
<script>
    $(function () {
        initDataTable('.table-staff', window.location.href);
    });

    function delete_staff_member(id) {
        $('#delete_staff').modal('show');
        $('#transfer_data_to').find('option').prop('disabled', false);
        $('#transfer_data_to').find('option[value="' + id + '"]').prop('disabled', true);
        $('#delete_staff .delete_id input').val(id);
        $('#transfer_data_to').selectpicker('refresh');
    }
</script>
</body>
</html>

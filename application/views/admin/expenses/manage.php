<?php init_single_head(); ?>
<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/expense.css'); ?>">
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content custom-scrollbar">

                <div id="expense-manage" class="page-layout simple left-sidebar-floating">

                    <div class="page-header bg-primary text-auto row no-gutters align-items-center justify-content-between p-4">

                        <div class="col-md col-sm-12">
                            <div>
                                <span class="logo-icon mr-4"><i class="fa fa-file-text-o s-6"></i></span>
                                <span class="logo-text h4"><?php echo _l('expenses'); ?></span>
                            </div>
                        </div>

                        <?php if (has_permission('expenses', '', 'create')) { ?>
                            <div class="col-auto ml-4">
                                <a href="<?php echo admin_url('expenses/expense'); ?>"
                                   class="btn btn-secondary"><?php echo _l('new_expense'); ?></a>
                            </div>
                        <?php } ?>

                        <div class="col-auto ml-4">
                            <div class="btn-group">
                                <a href="#" class="btn btn-default btn-with-tooltip toggle-small-view hidden-xs
                                m-t-2 min-height-auto" data-toggle="tooltip" data-placement="bottom"
                                   onclick="toggle_small_view('.table-expenses','#expense'); return false;"
                                   title="<?php echo _l('invoices_toggle_table_tooltip'); ?>">
                                    <i class="fa fa-angle-double-left text-dark s-4"></i></a>
                            </div>
                        </div>

                        <div class="col-auto ml-4">
                            <div class="btn-group">
                                <a href="#" onclick="slideToggle('#stats-top'); return false;"
                                   class="pull-right btn btn-default btn-with-tooltip m-t-2 min-height-auto"
                                   data-placement="bottom"
                                   data-toggle="tooltip" title="<?php echo _l('view_stats_tooltip'); ?>">
                                    <i class="fa fa-bar-chart text-dark s-4"></i></a>
                            </div>
                        </div>

                        <div class="col-auto ml-4 display-block">
                            <?php $this->load->view('admin/expenses/filter_by_template'); ?>
                        </div>
                    </div>
                    <!-- / HEADER -->

                    <div class="page-content p-4 p-sm-6">
                        <div class="content">
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="stats-top" class="hide card panel-body _buttons mb-4">
                                        <div id="expenses_total"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" id="small-table">
                                            <div class="card">
                                                <div class="panel-body">
                                                    <div class="clearfix"></div>
                                                    <!-- if expenseid found in url -->
                                                    <?php echo form_hidden('expenseid', $expenseid); ?>
                                                    <?php $this->load->view('admin/expenses/table_html'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-7 small-table-right-col">
                                            <div id="expense" class="hide">
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

    </div>
</main>

<div class="modal fade" id="expense_convert_helper_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo _l('additional_action_required'); ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="radio radio-primary">
                    <input type="radio" checked id="expense_convert_invoice_type_1" value="save_as_draft_false"
                           name="expense_convert_invoice_type">
                    <label for="expense_convert_invoice_type_1"><?php echo _l('convert'); ?></label>
                </div>
                <div class="radio radio-primary">
                    <input type="radio" id="expense_convert_invoice_type_2" value="save_as_draft_true"
                           name="expense_convert_invoice_type">
                    <label for="expense_convert_invoice_type_2"><?php echo _l('convert_and_save_as_draft'); ?></label>
                </div>
                <div id="inc_field_wrapper">
                    <hr/>
                    <p><?php echo _l('expense_include_additional_data_on_convert'); ?></p>
                    <p><b><?php echo _l('expense_add_edit_description'); ?> +</b></p>
                    <div class="checkbox checkbox-primary inc_note">
                        <input type="checkbox" id="inc_note">
                        <label for="inc_note"><?php echo _l('expense'); ?><?php echo _l('expense_add_edit_note'); ?></label>
                    </div>
                    <div class="checkbox checkbox-primary inc_name">
                        <input type="checkbox" id="inc_name">
                        <label for="inc_name"><?php echo _l('expense'); ?><?php echo _l('expense_name'); ?></label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info"
                        id="expense_confirm_convert"><?php echo _l('confirm'); ?></button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script>var hidden_columns = [4, 5, 6, 7, 8, 9];</script>
<?php init_tail(); ?>
<script>
    Dropzone.autoDiscover = false;
    $(function () {
        // Expenses additional server params
        var Expenses_ServerParams = {};
        $.each($('._hidden_inputs._filters input'), function () {
            Expenses_ServerParams[$(this).attr('name')] = '[name="' + $(this).attr('name') + '"]';
        });
        initDataTable('.table-expenses', admin_url + 'expenses/table', 'undefined', 'undefined', Expenses_ServerParams, <?php echo do_action('expenses_table_default_order', json_encode(array(5, 'desc'))); ?>).column(0).visible(false, false).columns.adjust();

        init_expense();

        $('#expense_convert_helper_modal').on('show.bs.modal', function () {
            var emptyNote = $('#tab_expense').attr('data-empty-note');
            var emptyName = $('#tab_expense').attr('data-empty-name');
            if (emptyNote == '1' && emptyName == '1') {
                $('#inc_field_wrapper').addClass('hide');
            } else {
                $('#inc_field_wrapper').removeClass('hide');
                emptyNote === '1' && $('.inc_note').addClass('hide') || $('.inc_note').removeClass('hide')
                emptyName === '1' && $('.inc_name').addClass('hide') || $('.inc_name').removeClass('hide')
            }
        });

        $('body').on('click', '#expense_confirm_convert', function () {
            var parameters = new Array();
            if ($('input[name="expense_convert_invoice_type"]:checked').val() == 'save_as_draft_true') {
                parameters['save_as_draft'] = 'true';
            }
            parameters['include_name'] = $('#inc_name').prop('checked');
            parameters['include_note'] = $('#inc_note').prop('checked');
            window.location.href = buildUrl(admin_url + 'expenses/convert_to_invoice/' + $('body').find('.expense_convert_btn').attr('data-id'), parameters);
        });
    });
</script>
</body>
</html>

<?php init_single_head(); ?>
<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/credit-note.css'); ?>">
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content custom-scrollbar">

                <div id="credit-note-manage" class="page-layout simple left-sidebar-floating">

                    <div class="page-header bg-primary text-auto row no-gutters align-items-center justify-content-between p-4">

                        <div class="col-md col-sm-12">
                            <span class="logo-text h4"><?php echo _l('credit_notes'); ?></span>
                        </div>

                        <?php if (has_permission('credit_notes', '', 'create')) { ?>
                            <div class="col-auto ml-4">
                                <a href="<?php echo admin_url('credit_notes/credit_note'); ?>"
                                   class="btn btn-secondary pull-left display-block"><?php echo _l('new_credit_note'); ?></a>
                            </div>
                        <?php } ?>

                        <div class="col-auto ml-4">
                            <div class="btn-group">
                                <a href="#"
                                   class="btn btn-default btn-with-tooltip toggle-small-view hidden-xs m-t-2 min-height-auto"
                                   onclick="toggle_small_view('.table-credit-notes','#credit_note'); return false;"
                                   data-toggle="tooltip" data-placement="bottom"
                                   title="<?php echo _l('invoices_toggle_table_tooltip'); ?>"><i
                                            class="fa fa-angle-double-left text-dark s-4"></i></a>
                            </div>
                        </div>
                        <div class="col-auto ml-4 display-block">
                            <div class="btn-group pull-right btn-with-tooltip-group _filter_data" data-placement="bottom"
                                 data-toggle="tooltip" data-title="<?php echo _l('filter_by'); ?>">
                                <button type="button" class="btn btn-secondary dropdown-toggle m-t-2 min-height-auto"
                                        data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                    <i class="fa fa-filter s-4" aria-hidden="true"></i>
                                </button>
                                <ul class="dropdown-menu width300">
                                    <li>
                                        <a href="#" data-cview="all"
                                           onclick="dt_custom_view('','.table-credit-notes',''); return false;">
                                            <?php echo _l('credit_notes_list_all'); ?>
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    <?php foreach ($statuses as $status) { ?>
                                        <li class="<?php if (isset($status['filter_default']) && $status['filter_default']) {
                                            echo 'active';
                                        } ?>">
                                            <a href="#"
                                               data-cview="credit_notes_status_<?php echo $status['id']; ?>"
                                               onclick="dt_custom_view('credit_notes_status_<?php echo $status['id']; ?>','.table-credit-notes','credit_notes_status_<?php echo $status['id']; ?>'); return false;">
                                                <?php echo format_credit_note_status($status['id'], true); ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <div class="clearfix"></div>
                                    <?php
                                    if (count($years) > 0) { ?>
                                        <li class="divider"></li>
                                        <?php foreach ($years as $year) { ?>
                                            <li class="active">
                                                <a href="#"
                                                   data-cview="year_<?php echo $year['year']; ?>"
                                                   onclick="dt_custom_view(<?php echo $year['year']; ?>,'.table-credit-notes','year_<?php echo $year['year']; ?>'); return false;"><?php echo $year['year']; ?>
                                                </a>
                                            </li>
                                        <?php } ?>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- / HEADER -->

                    <div class="page-content p-4 p-sm-6">
                        <div class="content">
                            <div class="row">
                                <div class="_filters _hidden_inputs">
                                    <?php
                                    foreach ($statuses as $status) {
                                        echo form_hidden('credit_notes_status_' . $status['id'], isset($status['filter_default'])
                                        && $status['filter_default'] ? 'true' : '');
                                    }
                                    foreach ($years as $year) {
                                        echo form_hidden('year_' . $year['year'], $year['year']);
                                    }
                                    ?>
                                </div>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12" id="small-table">
                                            <div class="card">
                                                <div class="panel-body">
                                                    <!-- if credit not id found in url -->
                                                    <?php echo form_hidden('credit_note_id', $credit_note_id); ?>
                                                    <?php $this->load->view('admin/credit_notes/table_html'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-7 small-table-right-col">
                                            <div id="credit_note" class="hide">
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
<?php $this->load->view('admin/includes_fuse/modals/sales_attach_file'); ?>
<script>
    var hidden_columns = [4, 5, 6, 7];
</script>
<?php init_tail(); ?>
<script>
    $(function () {
        var Credit_Notes_ServerParams = {};
        $.each($('._hidden_inputs._filters input'), function () {
            Credit_Notes_ServerParams[$(this).attr('name')] = '[name="' + $(this).attr('name') + '"]';
        });
        initDataTable('.table-credit-notes', admin_url + 'credit_notes/table', ['undefined'], ['undefined'], Credit_Notes_ServerParams, [0, 'desc']);
        init_credit_note();
    });
</script>
</body>
</html>

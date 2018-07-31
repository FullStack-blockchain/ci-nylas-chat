<?php init_single_head(); ?>
<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/contract.css'); ?>">
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content custom-scrollbar">

                <div id="contract" class="page-layout simple left-sidebar-floating">

                    <div class="page-header bg-primary text-auto row no-gutters align-items-center justify-content-between p-4">

                        <div class="col-md col-sm-12">
                            <div>
                            <span class="logo-icon mr-4"><i class="fa fa-file s-6"></i></span>
                            <span class="logo-text h4"><?php echo _l('contracts'); ?></span>
                            </div>
                        </div>

                        <?php if (has_permission('contracts', '', 'create')) { ?>
                            <div class="col-auto ml-4">
                                <a href="<?php echo admin_url('contracts/contract'); ?>"
                                   class="btn btn-secondary"><?php echo _l('new_contract'); ?></a>
                            </div>
                        <?php } ?>

                        <div class="col-auto ml-4 display-block">
                            <div class="btn-group pull-right btn-with-tooltip-group _filter_data"
                                 data-toggle="tooltip" data-placement="bottom"
                                 data-title="<?php echo _l('filter_by'); ?>">
                                <button type="button" class="btn btn-secondary dropdown-toggle m-t-2 min-height-auto"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-filter s-4" aria-hidden="true"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-left width300 height500">
                                    <li class="active">
                                        <a href="#" data-cview="exclude_trashed_contracts"
                                           onclick="dt_custom_view('exclude_trashed_contracts','.table-contracts','exclude_trashed_contracts'); return false;">
                                            <?php echo _l('contracts_view_exclude_trashed'); ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" data-cview="all"
                                           onclick="dt_custom_view('','.table-contracts',''); return false;">
                                            <?php echo _l('contracts_view_all'); ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" data-cview="expired"
                                           onclick="dt_custom_view('expired','.table-contracts','expired'); return false;">
                                            <?php echo _l('contracts_view_expired'); ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" data-cview="without_dateend"
                                           onclick="dt_custom_view('without_dateend','.table-contracts','without_dateend'); return false;">
                                            <?php echo _l('contracts_view_without_dateend'); ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" data-cview="trash"
                                           onclick="dt_custom_view('trash','.table-contracts','trash'); return false;">
                                            <?php echo _l('contracts_view_trash'); ?>
                                        </a>
                                    </li>
                                    <?php if (count($years) > 0) { ?>
                                        <li class="divider"></li>
                                        <?php foreach ($years as $year) { ?>
                                            <li class="active">
                                                <a href="#" data-cview="year_<?php echo $year['year']; ?>"
                                                   onclick="dt_custom_view(<?php echo $year['year']; ?>,'.table-contracts','year_<?php echo $year['year']; ?>'); return false;"><?php echo $year['year']; ?>
                                                </a>
                                            </li>
                                        <?php } ?>
                                    <?php } ?>
                                    <div class="clearfix"></div>
                                    <li class="divider"></li>
                                    <li class="dropdown-submenu pull-left">
                                        <a href="#" tabindex="-1"><?php echo _l('months'); ?></a>
                                        <ul class="dropdown-menu dropdown-menu-left">
                                            <?php for ($m = 1; $m <= 12; $m++) { ?>
                                                <li><a href="#"
                                                       data-cview="contracts_by_month_<?php echo $m; ?>"
                                                       onclick="dt_custom_view(<?php echo $m; ?>,'.table-contracts','contracts_by_month_<?php echo $m; ?>'); return false;"><?php echo _l(date('F', mktime(0, 0, 0, $m, 1))); ?></a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                    <div class="clearfix"></div>
                                    <?php if (count($contract_types) > 0) { ?>
                                        <li class="divider"></li>
                                        <?php foreach ($contract_types as $type) { ?>
                                            <li>
                                                <a href="#"
                                                   data-cview="contracts_by_type_<?php echo $type['id']; ?>"
                                                   onclick="dt_custom_view('contracts_by_type_<?php echo $type['id']; ?>','.table-contracts','contracts_by_type_<?php echo $type['id']; ?>'); return false;">
                                                    <?php echo $type['name']; ?>
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
                        <div class="card mb-4">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4 class="no-margin"><?php echo _l('contract_summary_heading'); ?></h4>
                                        <hr class="hr-panel-heading"/>
                                    </div>
                                    <div class="col-md-12">
                                        <?php $minus_7_days = date('Y-m-d', strtotime("-7 days")); ?>
                                        <?php $plus_7_days = date('Y-m-d', strtotime("+7 days"));
                                        $where_own = array();
                                        if (!has_permission('contracts', '', 'view')) {
                                            $where_own = array('addedfrom' => get_staff_user_id());
                                        }
                                        ?>

                                        <div class="row">

                                            <div class="col col-md mb-3">
                                                <div class="fuse-widget-front mat-white-bg mat-elevation-z2 ng-tns-c24-4">
                                                    <div class="p-6 text-center">
                                                        <div class="font-size-48 line-height-48">
                                                            <?php echo total_rows('tblcontracts', '(DATE(dateend) >"' . date('Y-m-d') . '" AND trash=0' . (count($where_own) > 0 ? ' AND addedfrom=' . get_staff_user_id() : '') . ') OR (DATE(dateend) IS NULL AND trash=0' . (count($where_own) > 0 ? ' AND addedfrom=' . get_staff_user_id() : '') . ')'); ?>
                                                        </div>
                                                        <div class="h3 text-info mt-8 font-weight-500">
                                                            <?php echo _l('contract_summary_active'); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col col-md mb-3">
                                                <div class="fuse-widget-front mat-white-bg mat-elevation-z2 ng-tns-c24-4">
                                                    <div class="p-6 text-center">
                                                        <div class="font-size-48 line-height-48">
                                                            <?php echo total_rows('tblcontracts', array_merge(array('DATE(dateend) <' => date('Y-m-d'), 'trash' => 0), $where_own)); ?>
                                                        </div>
                                                        <div class="h3 text-danger mt-8 font-weight-500">
                                                            <?php echo _l('contract_summary_expired'); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col col-md mb-3">
                                                <div class="fuse-widget-front mat-white-bg mat-elevation-z2 ng-tns-c24-4">
                                                    <div class="p-6 text-center">
                                                        <div class="font-size-48 line-height-48">
                                                            <?php echo total_rows('tblcontracts', 'dateend BETWEEN "' . $minus_7_days . '" AND "' . $plus_7_days . '" AND trash=0 AND dateend is NOT NULL AND dateend >"' . date('Y-m-d') . '"' . (count($where_own) > 0 ? ' AND addedfrom=' . get_staff_user_id() : '')); ?>
                                                        </div>
                                                        <div class="h3 text-warning mt-8 font-weight-500">
                                                            <?php echo _l('contract_summary_about_to_expire'); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col col-md mb-3">
                                                <div class="fuse-widget-front mat-white-bg mat-elevation-z2 ng-tns-c24-4">
                                                    <div class="p-6 text-center">
                                                        <div class="font-size-48 line-height-48">
                                                            <?php echo total_rows('tblcontracts', 'dateadded BETWEEN "' . $minus_7_days . '" AND "' . $plus_7_days . '" AND trash=0' . (count($where_own) > 0 ? ' AND addedfrom=' . get_staff_user_id() : '')); ?>
                                                        </div>
                                                        <div class="h3 text-success mt-8 font-weight-500">
                                                            <?php echo _l('contract_summary_recently_added'); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col col-md mb-3">
                                                <div class="fuse-widget-front mat-white-bg mat-elevation-z2 ng-tns-c24-4">
                                                    <div class="p-6 text-center">
                                                        <div class="font-size-48 line-height-48">
                                                            <?php echo total_rows('tblcontracts', array_merge(array('trash' => 1), $where_own)); ?>
                                                        </div>
                                                        <div class="h3 text-muted mt-8 font-weight-500">
                                                            <?php echo _l('contract_summary_trash'); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-12 mb-4">
                            <div class="row">
                                <div class="card col-md-6 p-0">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4 class="no-margin"><?php echo _l('contract_summary_by_type'); ?></h4>
                                                <hr class="hr-panel-heading"/>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="relative" style="max-height:400px">
                                                    <canvas class="chart" height="400"
                                                            id="contracts-by-type-chart"></canvas>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="card col-md-6 p-0">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4 class="no-margin"><?php echo _l('contract_summary_by_type_value'); ?></h4>
                                                <hr class="hr-panel-heading"/>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="relative" style="max-height:400px">
                                                    <canvas class="chart" height="400"
                                                            id="contracts-value-by-type-chart"></canvas>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <?php echo form_hidden('custom_view'); ?>
                            <div class="panel-body">
                                <?php $this->load->view('admin/contracts/table_html'); ?>
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

        var ContractsServerParams = {};
        $.each($('._hidden_inputs._filters input'), function () {
            ContractsServerParams[$(this).attr('name')] = '[name="' + $(this).attr('name') + '"]';
        });

        initDataTable('.table-contracts', admin_url + 'contracts/table', undefined, undefined, ContractsServerParams,<?php echo do_action('contracts_table_default_order', json_encode(array(6, 'asc'))); ?>);

        new Chart($('#contracts-by-type-chart'), {
            type: 'bar',
            data: <?php echo $chart_types; ?>,
            options: {
                legend: {
                    display: false,
                },
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        display: true,
                        ticks: {
                            suggestedMin: 0,
                        }
                    }]
                }
            }
        });
        new Chart($('#contracts-value-by-type-chart'), {
            type: 'line',
            data: <?php echo $chart_types_values; ?>,
            options: {
                responsive: true,
                legend: {
                    display: false,
                },
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        display: true,
                        ticks: {
                            suggestedMin: 0,
                        }
                    }]
                }
            }
        });
    });
</script>

</body>
</html>

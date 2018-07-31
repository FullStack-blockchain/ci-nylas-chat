<?php init_single_head(); ?>
<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/estimates.css'); ?>">
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content custom-scrollbar">

                <div id="estimates-manage" class="page-layout simple left-sidebar-floating">

                    <div class="page-header bg-primary text-auto row no-gutters align-items-center justify-content-between p-4">
                        <div class="col col-md mb-3">
                            <span class="logo-text h4"><?php echo _l('estimate'); ?></span>
                        </div>
                        <div class="col-auto col-md-3" data-toggle="tooltip" data-placement="bottom"
                             data-title="<?php echo _l('search_by_tags'); ?>">
                            <?php echo render_input('search', '', '', 'search', array('data-name' => 'search', 'onkeyup' => 'estimate_pipeline();'), array(), 'no-margin') ?>
                            <?php echo form_hidden('sort_type'); ?>
                            <?php echo form_hidden('sort', (get_option('default_estimates_pipeline_sort') != '' ? get_option('default_estimates_pipeline_sort_type') : '')); ?>
                        </div>
                        <?php if (has_permission('estimates', '', 'create')) { ?>
                            <div class="col-auto ml-4">
                                <a href="<?php echo admin_url('estimates/estimate'); ?>"
                                   class="btn btn-secondary new new-estimate-btn"><?php echo _l('create_new_estimate'); ?></a>
                            </div>
                        <?php } ?>
                        <div class="col-auto ml-4">
                            <div class="btn-group">
                                <a href="#"
                                   class="btn btn-default btn-with-tooltip estimates-total m-t-2 min-height-auto"
                                   onclick="slideToggle('#stats-top'); init_estimates_total(true); return false;"
                                   data-toggle="tooltip" title="<?php echo _l('view_stats_tooltip'); ?>"
                                   data-placement="bottom"><i class="fa fa-bar-chart text-dark s-4"></i></a>
                            </div>
                        </div>
                        <div class="col-auto ml-4">
                            <a href="<?php echo admin_url('estimates/pipeline/' . $switch_pipeline); ?>"
                               class="btn btn-default pull-left"><?php echo _l('switch_to_list_view'); ?></a>
                        </div>
                    </div>
                    <!-- / HEADER -->

                    <div class="page-content p-4 p-sm-6">
                        <div class="row">
                            <div class="col-md-12">
                                <?php if (has_permission('estimates', '', 'create')) {
                                    $this->load->view('admin/estimates/estimates_top_stats');
                                } ?>
                                <div class="panel_s animated mtop5 fadeIn">
                                    <?php echo form_hidden('estimateid', $estimateid); ?>
                                    <div class="card p-4">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="kanban-leads-sort">
                                                    <span class="bold"><?php echo _l('estimates_pipeline_sort'); ?>
                                                        : </span>
                                                    <a href="#"
                                                       onclick="estimates_pipeline_sort('datecreated'); return false"
                                                       class="datecreated">
                                                        <?php if (get_option('default_estimates_pipeline_sort') == 'datecreated') {
                                                            echo '<i class="kanban-sort-icon fa fa-sort-amount-' . strtolower(get_option('default_estimates_pipeline_sort_type')) . '"></i> ';
                                                        } ?>
                                                        <?php echo _l('estimates_sort_datecreated'); ?>
                                                    </a>
                                                    |
                                                    <a href="#"
                                                       onclick="estimates_pipeline_sort('date'); return false"
                                                       class="date">
                                                        <?php if (get_option('default_estimates_pipeline_sort') == 'date') {
                                                            echo '<i class="kanban-sort-icon fa fa-sort-amount-' . strtolower(get_option('default_estimates_pipeline_sort_type')) . '"></i> ';
                                                        } ?>
                                                        <?php echo _l('estimates_sort_estimate_date'); ?>
                                                    </a>
                                                    |
                                                    <a href="#"
                                                       onclick="estimates_pipeline_sort('pipeline_order');return false;"
                                                       class="pipeline_order">
                                                        <?php if (get_option('default_estimates_pipeline_sort') == 'pipeline_order') {
                                                            echo '<i class="kanban-sort-icon fa fa-sort-amount-' . strtolower(get_option('default_estimates_pipeline_sort_type')) . '"></i> ';
                                                        } ?>
                                                        <?php echo _l('estimates_sort_pipeline'); ?>
                                                    </a>
                                                    |
                                                    <a href="#"
                                                       onclick="estimates_pipeline_sort('expirydate');return false;"
                                                       class="expirydate">
                                                        <?php if (get_option('default_estimates_pipeline_sort') == 'expirydate') {
                                                            echo '<i class="kanban-sort-icon fa fa-sort-amount-' . strtolower(get_option('default_estimates_pipeline_sort_type')) . '"></i> ';
                                                        } ?>
                                                        <?php echo _l('estimates_sort_expiry_date'); ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div id="estimate-pipeline">
                                                <div class="container-fluid">
                                                    <div id="kan-ban"></div>
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
    </div>
</main>
<div id="estimate">
</div>
<?php $this->load->view('admin/includes_fuse/modals/sales_attach_file'); ?>
<?php init_tail(); ?>
<script>
    $(function () {
        estimate_pipeline();
    });
</script>
</body>
</html>

<?php init_single_head(); ?>
<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/proposal.css'); ?>">
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content custom-scrollbar">

                <div id="proposal-manage" class="page-layout simple left-sidebar-floating">

                    <div class="page-header bg-primary text-auto row no-gutters align-items-center justify-content-between p-4">

                        <div class="col-md col-sm-12">
                            <span class="logo-text h4"><?php echo _l('proposals'); ?></span>
                        </div>

                        <div class="col-auto col-md-3" data-toggle="tooltip" data-placement="bottom"
                             data-title="<?php echo _l('search_by_tags'); ?>">
                            <?php echo render_input('search', '', '', 'search', array('data-name' => 'search', 'onkeyup' => 'proposals_pipeline();'), array(), 'no-margin') ?>
                            <?php echo form_hidden('sort_type'); ?>
                            <?php echo form_hidden('sort', (get_option('default_proposals_pipeline_sort') != '' ? get_option('default_proposals_pipeline_sort_type') : '')); ?>
                        </div>

                        <?php if (has_permission('proposals', '', 'create')) { ?>
                            <div class="col-auto ml-4">
                                <a href="<?php echo admin_url('proposals/proposal'); ?>" class="btn btn-secondary">
                                    <?php echo _l('new_proposal'); ?></a>
                            </div>
                        <?php } ?>

                        <div class="col-auto ml-4">
                            <a href="<?php echo admin_url('proposals/pipeline/' . $switch_pipeline); ?>"
                               class="btn btn-default hidden-xs"><?php echo _l('switch_to_list_view'); ?></a>
                        </div>
                    </div>
                    <!-- / HEADER -->

                    <div class="page-content p-4 p-sm-6">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="panel_s animated mtop5 fadeIn">
                                    <?php echo form_hidden('proposalid', $proposalid); ?>
                                    <div class="panel-body card">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="kanban-leads-sort mb-2">
                                                        <span class="bold"><?php echo _l('proposals_pipeline_sort'); ?>
                                                            : </span>
                                                    <a href="#"
                                                       onclick="proposal_pipeline_sort('datecreated'); return false"
                                                       class="datecreated">
                                                        <?php if (get_option('default_proposals_pipeline_sort') == 'datecreated') {
                                                            echo '<i class="kanban-sort-icon fa fa-sort-amount-' . strtolower(get_option('default_proposals_pipeline_sort_type')) . '"></i> ';
                                                        } ?><?php echo _l('proposals_sort_datecreated'); ?>
                                                    </a>
                                                    |
                                                    <a href="#"
                                                       onclick="proposal_pipeline_sort('date'); return false"
                                                       class="date">
                                                        <?php if (get_option('default_proposals_pipeline_sort') == 'date') {
                                                            echo '<i class="kanban-sort-icon fa fa-sort-amount-' . strtolower(get_option('default_proposals_pipeline_sort_type')) . '"></i> ';
                                                        } ?><?php echo _l('proposals_sort_proposal_date'); ?>
                                                    </a>
                                                    |
                                                    <a href="#"
                                                       onclick="proposal_pipeline_sort('pipeline_order');return false;"
                                                       class="pipeline_order">
                                                        <?php if (get_option('default_proposals_pipeline_sort') == 'pipeline_order') {
                                                            echo '<i class="kanban-sort-icon fa fa-sort-amount-' . strtolower(get_option('default_proposals_pipeline_sort_type')) . '"></i> ';
                                                        } ?><?php echo _l('proposals_sort_pipeline'); ?>
                                                    </a>
                                                    |
                                                    <a href="#"
                                                       onclick="proposal_pipeline_sort('open_till');return false;"
                                                       class="open_till">
                                                        <?php if (get_option('default_proposals_pipeline_sort') == 'open_till') {
                                                            echo '<i class="kanban-sort-icon fa fa-sort-amount-' . strtolower(get_option('default_proposals_pipeline_sort_type')) . '"></i> ';
                                                        } ?><?php echo _l('proposals_sort_open_till'); ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div id="proposals-pipeline">
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
<div id="proposal">
</div>
<?php $this->load->view('admin/includes_fuse/modals/sales_attach_file'); ?>
<?php init_tail(); ?>
<div id="convert_helper"></div>
<?php echo app_script('assets-old/js', 'proposals.js'); ?>
<script>
    $(function () {
        proposals_pipeline();
    });
</script>
</body>
</html>

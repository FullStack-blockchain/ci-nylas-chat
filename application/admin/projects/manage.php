<?php init_single_head(); ?>
<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/project.css'); ?>">
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content custom-scrollbar">

                <div id="project-manage" class="page-layout simple left-sidebar-floating">

                    <div class="page-header bg-primary text-auto row no-gutters align-items-center justify-content-between p-4">

                        <div class="col-md col-sm-12">
                            <div>
                            <span class="logo-icon mr-4">
                                <i class="fa fa-bars s-6"></i>
                            </span>
                                <span class="logo-text h4"><?php echo _l('projects'); ?></span>
                            </div>
                        </div>

                        <?php if (has_permission('projects', '', 'create')) { ?>
                            <div class="col-auto ml-4">
                                <a href="<?php echo admin_url('projects/project'); ?>"
                                   class="btn btn-light display-block">
                                    <?php echo _l('new_project'); ?>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                    <!-- / HEADER -->

                    <div class="page-content p-4 p-sm-6">
                        <div class="card">
                            <div class="panel-body">

                                <div class="row">
                                    <div class="col-md-9">
                                        <h4 class="no-margin"><?php echo _l('projects_summary'); ?></h4>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="btn-group pull-right btn-with-tooltip-group _filter_data"
                                             data-toggle="tooltip" data-title="<?php echo _l('filter_by'); ?>">
                                            <button type="button" class="btn btn-secondary dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                <i class="fa fa-filter s-4" aria-hidden="true"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right width300">
                                                <li>
                                                    <a href="#" data-cview="all"
                                                       onclick="dt_custom_view('','.table-projects',''); return false;">
                                                        <?php echo _l('expenses_list_all'); ?>
                                                    </a>
                                                </li>
                                                <?php
                                                // Only show this filter if user has permission for projects view otherwisde wont need this becuase by default this filter will be applied
                                                if (has_permission('projects', '', 'view')) { ?>
                                                    <li>
                                                        <a href="#" data-cview="my_projects"
                                                           onclick="dt_custom_view('my_projects','.table-projects','my_projects'); return false;">
                                                            <?php echo _l('home_my_projects'); ?>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <li class="divider"></li>
                                                <?php foreach ($statuses as $status) { ?>
                                                    <li class="<?php if ($status['filter_default'] == true && !$this->input->get('status') || $this->input->get('status') == $status['id']) {
                                                        echo 'active';
                                                    } ?>">
                                                        <a href="#"
                                                           data-cview="<?php echo 'project_status_' . $status['id']; ?>"
                                                           onclick="dt_custom_view('project_status_<?php echo $status['id']; ?>','.table-projects','project_status_<?php echo $status['id']; ?>'); return false;">
                                                            <?php echo $status['name']; ?>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <hr class="hr-panel-heading"/>
                                    </div>
                                </div>

                                <div class="row _filters _hidden_inputs">
                                    <?php
                                    $_where = '';
                                    if (!has_permission('projects', '', 'view')) {
                                        $_where = 'id IN (SELECT project_id FROM tblprojectmembers WHERE staff_id=' . get_staff_user_id() . ')';
                                    }
                                    echo form_hidden('my_projects');
                                    foreach ($statuses as $status) {
                                        $value = $status['id'];
                                        if ($status['filter_default'] == false && !$this->input->get('status')) {
                                            $value = '';
                                        } else if ($this->input->get('status')) {
                                            $value = ($this->input->get('status') == $status['id'] ? $status['id'] : "");
                                        }
                                        echo form_hidden('project_status_' . $status['id'], $value);
                                        ?>
                                        <div class="col col-md mb-3">
                                            <?php $where = ($_where == '' ? '' : $_where . ' AND ') . 'status = ' . $status['id']; ?>
                                            <div class="fuse-widget-front mat-white-bg mat-elevation-z2 ng-tns-c24-4">
                                                <div class="p-6 text-center">
                                                    <a href="#"
                                                       onclick="dt_custom_view('project_status_<?php echo $status['id']; ?>','.table-projects','project_status_<?php echo $status['id']; ?>',true); return false;">
                                                        <div class="font-size-48 line-height-48"
                                                             style="color:<?php echo $status['color']; ?>">
                                                            <?php echo total_rows('tblprojects', $where); ?>
                                                        </div>
                                                        <div class="h3 mt-8 text-muted font-weight-500">
                                                            <?php echo $status['name']; ?>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>

                                <div class="clearfix"></div>
                                <hr class="hr-panel-heading"/>
                                <?php echo form_hidden('custom_view'); ?>
                                <?php $this->load->view('admin/projects/table_html'); ?>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>
</main>
<?php $this->load->view('admin/projects/copy_settings'); ?>
<?php init_tail(); ?>
<script>
    $(function () {
        var ProjectsServerParams = {};

        $.each($('._hidden_inputs._filters input'), function () {
            ProjectsServerParams[$(this).attr('name')] = '[name="' + $(this).attr('name') + '"]';
        });

        initDataTable('.table-projects', admin_url + 'projects/table', undefined, undefined, ProjectsServerParams, <?php echo do_action('projects_table_default_order', json_encode(array(5, 'asc'))); ?>);

        init_ajax_search('customer', '#clientid_copy_project.ajax-search');
    });
</script>
</body>
</html>

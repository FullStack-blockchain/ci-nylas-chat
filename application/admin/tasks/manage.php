<?php init_single_head(); ?>
<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/tasks.css'); ?>">
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content custom-scrollbar">

                <div id="tasks-manage" class="page-layout simple left-sidebar-floating">

                    <div class="page-header bg-primary text-auto row no-gutters align-items-center justify-content-between p-4">

                        <div class="col-md col-sm-12">
                            <div>
                                <span class="logo-icon mr-4">
                                    <i class="fa fa-tasks s-6"></i>
                                </span>
                                <span class="logo-text h4"><?php echo _l('tasks'); ?></span>
                            </div>
                        </div>

                        <div class="col-auto ml-4">
                            <?php if (has_permission('tasks', '', 'create')) { ?>
                                <a href="#" onclick="new_task(<?php if ($this->input->get('project_id')) {
                                    echo "'" . admin_url('tasks/task?rel_id=' . $this->input->get('project_id') . '&rel_type=project') . "'";
                                } ?>); return false;"
                                   class="btn btn-secondary display-block pull-left">
                                    <?php echo _l('new_task'); ?>
                                </a>
                            <?php } ?>

                            <a href="<?php if (!$this->input->get('project_id')) {
                                echo admin_url('tasks/switch_kanban/' . $switch_kanban);
                            } else {
                                echo admin_url('projects/view/' . $this->input->get('project_id') . '?group=project_tasks');
                            }; ?>" class="btn btn-default display-block pull-right ml-4">
                                <?php if ($switch_kanban == 1) {
                                    echo _l('switch_to_list_view');
                                } else {
                                    echo _l('leads_switch_to_kanban');
                                }; ?>
                            </a>
                        </div>
                    </div>
                    <!-- / HEADER -->

                    <div class="page-content p-4 p-sm-6">
                        <div class="card">
                            <div class="panel-body">
                                <div class="row _buttons">

                                    <?php if ($this->session->has_userdata('tasks_kanban_view') && $this->session->userdata('tasks_kanban_view') == 'true') { ?>
                                        <div class="col-md-12">
                                            <div class="col-sm-4">
                                                <div data-toggle="tooltip" data-placement="bottom" style="margin-top: -10px"
                                                     data-title="<?php echo _l('search_by_tags'); ?>">
                                                    <?php echo render_input('search', '', '', 'search', array('data-name' => 'search', 'onkeyup' => 'tasks_kanban();', 'placeholder' => _l('search_tasks'))); ?>
                                                </div>
                                            </div>
                                            <div class="col-md-2 p-0">

                                                <a href="#" onclick="tasks_kanban()"
                                                   class="btn btn-default display-block pull-left">
                                                    <?php echo _l('search_tasks'); ?>
                                                </a>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="col-md-8">
                                            <h4 class="task-summary-title"><?php echo _l('tasks_summary'); ?></h4>
                                        </div>
                                        <div class="col-md-4">
                                            <?php $this->load->view('admin/tasks/tasks_filter_by', array('view_table_name' => '.table-tasks')); ?>
                                            <a href="<?php echo admin_url('tasks/detailed_overview'); ?>"
                                               class="btn btn-success pull-right display-block"><?php echo _l('detailed_overview'); ?></a>
                                        </div>
                                    <?php } ?>
                                </div>
                                <hr class="hr-panel-heading hr-10"/>
                                <div class="clearfix"></div>
                                <?php
                                if ($this->session->has_userdata('tasks_kanban_view') && $this->session->userdata('tasks_kanban_view') == 'true') { ?>
                                    <div class="kan-ban-tab" id="kan-ban-tab" style="overflow:auto;">
                                        <div class="row">
                                            <div id="kanban-params">
                                                <?php echo form_hidden('project_id', $this->input->get('project_id')); ?>
                                            </div>
                                            <div class="container-fluid">
                                                <div id="kan-ban"></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <?php $this->load->view('admin/tasks/_summary', array('table' => '.table-tasks')); ?>
                                    <a href="#" data-toggle="modal" data-target="#tasks_bulk_actions"
                                       class="hide bulk-actions-btn table-btn"
                                       data-table=".table-tasks"><?php echo _l('bulk_actions'); ?></a>
                                    <?php $this->load->view('admin/tasks/_table', array('bulk_actions' => true)); ?>
                                    <?php $this->load->view('admin/tasks/_bulk_actions'); ?>
                                <?php } ?>
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
    taskid = '<?php echo $taskid; ?>';
    $(function () {
        tasks_kanban();
    });
</script>
</body>
</html>

<div class="project-toggler-opts">
    <?php if (has_permission('projects', '', 'create')) { ?>
        <a href="#" class="btn btn-secondary"
           onclick="new_milestone();return false;"><?php echo _l('new_milestone'); ?></a>
    <?php } ?>
    <div class="btn-group ml-1">
        <a href="#" class="btn btn-default min-height-auto" onclick="milestones_switch_view(); return false;"><i
                    class="fa fa-th-list s-4"></i></a>
    </div>
    <?php if ($milestones_found) { ?>
        <div id="kanban-params">
            <div class="form-check mt-4 mb-4">
                <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" value="yes" id="exclude_completed_tasks"
                           name="exclude_completed_tasks"<?php if ($milestones_exclude_completed_tasks) {
                        echo ' checked';
                    } ?>
                           onclick="window.location.href = '<?php echo admin_url('projects/view/' . $project->id . '?group=project_milestones&exclude_completed='); ?>'+(this.checked ? 'yes' : 'no')">
                    <span class="checkbox-icon"></span>
                    <span class="form-check-description" for="exclude_completed_tasks"><?php echo _l('exclude_completed_tasks') ?></span>
                </label>
            </div>
            <div class="clearfix"></div>
            <?php echo form_hidden('project_id', $project->id); ?>
        </div>
    <?php } ?>
</div>
<?php if ($milestones_found) { ?>
    <div class="project-milestones-kanban">
        <div class="kan-ban-tab" id="kan-ban-tab" style="overflow:auto;">
            <div class="row">
                <div class="container-fluid">
                    <div id="kan-ban"></div>
                </div>
            </div>
        </div>
    </div>
<?php } else { ?>
    <div class="alert alert-info mt-3 no-mbot">
        <?php echo _l('no_milestones_found'); ?>
    </div>
<?php } ?>
<div id="milestones-table" class="hide mt-4">
    <?php render_datatable(array(
        _l('milestone_name'),
        _l('milestone_due_date'),
    ), 'milestones'); ?>
</div>

<h4 class="customer-profile-group-heading"><?php echo _l('projects'); ?></h4>
<div class="p-4">
    <?php if (isset($client)) { ?>
        <?php if (has_permission('projects', '', 'create')) { ?>
            <a href="<?php echo admin_url('projects/project?customer_id=' . $client->userid); ?>"
               class="btn btn-info mb-4<?php if ($client->active == 0) {
                   echo ' disabled';
               } ?>"><?php echo _l('new_project'); ?></a>
        <?php } ?>
        <div class="row mb-1">
            <?php
            $_where = '';
            if (!has_permission('projects', '', 'view')) {
                $_where = 'id IN (SELECT project_id FROM tblprojectmembers WHERE staff_id=' . get_staff_user_id() . ')';
            }
            ?>
            <?php foreach ($project_statuses as $status) { ?>
                <div class="col total-column mb-3">
                    <div class="panel-body card">
                        <h3 class="text-muted _total">
                            <?php $where = ($_where == '' ? '' : $_where . ' AND ') . 'status = ' . $status['id'] . ' AND clientid=' . $client->userid; ?>
                            <?php echo total_rows('tblprojects', $where); ?>
                        </h3>
                        <span style="color:<?php echo $status['color']; ?>"><?php echo $status['name']; ?></span>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php
        $this->load->view('admin/projects/table_html', array('class' => 'projects-single-client'));
    }
    ?>
</div>
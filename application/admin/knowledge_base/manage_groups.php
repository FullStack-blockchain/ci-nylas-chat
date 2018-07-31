<?php init_single_head(); ?>
<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/knowledge-base.css'); ?>">
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content custom-scrollbar">

                <div id="articles-manage" class="page-layout simple left-sidebar-floating">

                    <div class="page-content p-4 p-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="_buttons">
                                    <?php if (has_permission('knowledge_base', '', 'create')){ ?>
                                    <div class="_buttons">
                                        <a href="#" onclick="new_kb_group(); return false;"
                                           class="btn btn-secondary pull-left display-block">
                                            <?php echo _l('new_group'); ?>
                                        </a>
                                        <?php } ?>
                                        <a href="<?php echo admin_url('knowledge_base'); ?>"
                                           class="btn btn-secondary pull-left display-block ml-4">
                                            <?php echo _l('als_all_articles'); ?>
                                        </a>
                                        <div class="clearfix"></div>
                                        <hr class="hr-panel-heading"/>
                                    </div>
                                    <?php if (count($groups) > 0) { ?>
                                        <table class="table dt-table scroll-responsive">
                                            <thead>
                                            <th><?php echo _l('group_table_name_heading'); ?></th>
                                            <th><?php echo _l('group_table_isactive_heading'); ?></th>
                                            <th><?php echo _l('options'); ?></th>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($groups as $group) { ?>
                                                <tr>
                                                    <td><?php echo $group['name']; ?> <span
                                                                class="badge mleft5"><?php echo total_rows('tblknowledgebase', 'articlegroup=' . $group['groupid']); ?></span>
                                                    </td>
                                                    <td>
                                                        <div class="onoffswitch">
                                                            <div class="form-check mb-4">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" id="<?php echo $group['groupid']; ?>"
                                                                           data-id="<?php echo $group['groupid']; ?>"
                                                                           class="onoffswitch-checkbox form-check-input" <?php if (!has_permission('knowledge_base', '', 'edit')) {
                                                                        echo 'disabled';
                                                                    } ?>
                                                                           data-switch-url="<?php echo admin_url(); ?>knowledge_base/change_group_status" <?php if ($group['active'] == 1) {
                                                                        echo 'checked';
                                                                    } ?>>
                                                                    <span class="checkbox-icon"></span>
                                                                    <span></span>
                                                                </label>
                                                            </div>

                                                        </div>
                                                    </td>
                                                    <td>
                                                        <?php if (has_permission('knowledge_base', '', 'edit')) { ?>
                                                            <a href="#"
                                                               onclick="edit_kb_group(this,<?php echo $group['groupid']; ?>); return false"
                                                               data-name="<?php echo $group['name']; ?>"
                                                               data-color="<?php echo $group['color']; ?>"
                                                               data-description="<?php echo clear_textarea_breaks($group['description']); ?>"
                                                               data-order="<?php echo $group['group_order']; ?>"
                                                               data-active="<?php echo $group['active']; ?>"
                                                               data-slug="<?php echo $group['group_slug']; ?>"
                                                               class="btn btn-default btn-icon"><i
                                                                        class="fa fa-pencil-square-o line-height-25"></i></a>
                                                        <?php } ?>
                                                        <?php if (has_permission('knowledge_base', '', 'delete')) { ?>
                                                            <a href="<?php echo admin_url('knowledge_base/delete_group/' . $group['groupid']); ?>"
                                                               class="btn btn-danger btn-icon _delete"><i
                                                                        class="fa fa-remove line-height-25"></i></a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    <?php } else { ?>
                                        <p class="no-margin"><?php echo _l('kb_no_groups_found'); ?></p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php $this->load->view('admin/knowledge_base/group'); ?>
<?php init_tail(); ?>
</body>
</html>

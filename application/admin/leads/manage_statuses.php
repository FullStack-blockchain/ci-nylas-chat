<?php init_single_head(); ?>
<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/leads.css'); ?>">
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content custom-scrollbar">

                <div id="lead-manage" class="page-layout simple left-sidebar-floating">

                    <div class="page-content p-4 p-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="_buttons">
                                    <a href="#" onclick="new_status(); return false;"
                                       class="btn btn-secondary pull-left display-block">
                                        <?php echo _l('lead_new_status'); ?>
                                    </a>
                                </div>
                                <div class="clearfix"></div>
                                <hr class="hr-panel-heading"/>
                                <?php if (count($statuses) > 0) { ?>
                                    <table class="table dt-table scroll-responsive">
                                        <thead>
                                        <th><?php echo _l('leads_status_table_name'); ?></th>
                                        <th><?php echo _l('options'); ?></th>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($statuses as $status) { ?>
                                            <tr>
                                                <td><a href="#"
                                                       onclick="edit_status(this,<?php echo $status['id']; ?>);return false;"
                                                       data-color="<?php echo $status['color']; ?>"
                                                       data-name="<?php echo $status['name']; ?>"
                                                       data-order="<?php echo $status['statusorder']; ?>"><?php echo $status['name']; ?></a><br/>
                                                    <span class="text-muted">
											<?php echo _l('leads_table_total', total_rows('tblleads', array('status' => $status['id']))); ?></span>
                                                </td>
                                                <td>
                                                    <a href="#"
                                                       onclick="edit_status(this,<?php echo $status['id']; ?>);return false;"
                                                       data-color="<?php echo $status['color']; ?>"
                                                       data-name="<?php echo $status['name']; ?>"
                                                       data-order="<?php echo $status['statusorder']; ?>"
                                                       class="btn btn-default btn-icon"><i
                                                                class="fa fa-pencil-square-o line-height-25"></i></a>
                                                    <?php if ($status['isdefault'] == 0) { ?>
                                                        <a href="<?php echo admin_url('leads/delete_status/' . $status['id']); ?>"
                                                           class="btn btn-danger btn-icon _delete"><i
                                                                    class="fa fa-remove line-height-25"></i></a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                <?php } else { ?>
                                    <p class="no-margin"><?php echo _l('lead_statuses_not_found'); ?></p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include_once(APPPATH . 'views/admin/leads/status.php'); ?>
<?php init_tail(); ?>
</body>
</html>

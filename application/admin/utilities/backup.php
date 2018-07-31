<?php init_single_head(); ?>
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content custom-scrollbar">

                <div id="database-backup-manage" class="page-layout simple left-sidebar-floating">

                    <div class="page-header bg-primary text-auto row no-gutters align-items-center justify-content-between p-4">

                        <div class="col-md col-sm-12">
                            <div>
                                <span class="logo-text h4"><?php echo $title; ?></span>
                            </div>
                        </div>

                        <div class="col-auto ml-4">
                            <a href="<?php echo admin_url('utilities/make_backup_db'); ?>"
                               class="btn btn-default pull-right">
                                <?php echo _l('utility_create_new_backup_db'); ?>
                            </a>
                            <a href="#" data-toggle="modal" data-target="#auto_backup_config"
                               class="btn btn-default mr-5 pull-right">
                                <?php echo _l('auto_backup'); ?>
                            </a>
                        </div>
                    </div>
                    <!-- / HEADER -->

                    <div class="page-content p-4 p-sm-6">
                        <div class="card">
                            <div class="panel-body">

                                <p class="mt-4 text-info bold">
                                    <?php echo _l('utility_db_backup_note'); ?>
                                </p>

                                <table class="table dt-table scroll-responsive" data-order-col="2"
                                       data-order-type="desc">
                                    <thead>
                                    <th><?php echo _l('utility_backup_table_backupname'); ?></th>
                                    <th><?php echo _l('utility_backup_table_backupsize'); ?></th>
                                    <th><?php echo _l('utility_backup_table_backupdate'); ?></th>
                                    <th><?php echo _l('options'); ?></th>
                                    </thead>
                                    <tbody>
                                    <?php $backups = list_files(BACKUPS_FOLDER); ?>
                                    <?php foreach ($backups as $backup) {
                                        $_fullpath = BACKUPS_FOLDER . $backup; ?>
                                        <tr>
                                            <td>
                                                <a href="<?php echo site_url('download/file/db_backup/' . $backup); ?>"><?php echo $backup; ?></a>
                                            </td>
                                            <td>
                                                <?php echo bytesToSize($_fullpath); ?>
                                            </td>
                                            <td data-order="<?php echo strftime('%Y-%m-%d %H:%M:%S', filectime($_fullpath)); ?>">
                                                <?php echo date('M dS, Y, g:i a', filectime($_fullpath)); ?>
                                            </td>
                                            <td>
                                                <a href="<?php echo admin_url('utilities/delete_backup/' . $backup); ?>"
                                                   class="btn btn-danger btn-icon _delete"><i class="fa fa-remove line-height-25"></i></a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<div class="modal fade" id="auto_backup_config" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('utilities/update_auto_backup_options')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo _l('auto_backup'); ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo render_yes_no_option('auto_backup_enabled', 'auto_backup_enabled'); ?>
                <?php echo render_input('auto_backup_every', 'auto_backup_every', get_option('auto_backup_every'), 'number'); ?>
                <?php echo render_input('delete_backups_older_then', 'delete_backups_older_then', get_option('delete_backups_older_then'), 'number'); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php init_tail(); ?>
</body>
</html>

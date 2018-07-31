<?php init_single_head(); ?>
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content">

                <div id="utilities-manage" class="page-layout simple left-sidebar-floating">

                    <div class="page-content p-4 p-sm-6">

                        <div class="card">
                            <div class="card-header">
                                <?php if (is_admin()) { ?>
                                    <a href="<?php echo admin_url('announcements/announcement'); ?>"
                                       class="btn btn-info pull-left display-block"><?php echo _l('new_announcement'); ?></a>
                                    <div class="clearfix"></div>
                                <?php } else {
                                    echo '<h5 class="no-margin bold">' . _l('announcements') . '</h5>';
                                } ?>
                            </div>
                            <div class="card-body">
                                <?php render_datatable(array(_l('name'), _l('announcement_date_list')), 'announcements'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php init_tail(); ?>
</body>
</html>

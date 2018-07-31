<?php $_announcements = get_announcements_for_user();

if (sizeof($_announcements) > 0 && isset($dashboard) && is_staff_member()) { ?>
    <?php foreach ($_announcements as $__announcement) { ?>
        <div class="col-12 p-3">
            <div class="widget card card-body announcement tc-content">
                <div class="text-secondary alert-dismissible" role="alert">
                    <div class="pull-left">
                        <h4 class="no-margin">
                            <?php echo _l('announcement'); ?>!
                        </h4>
                        <?php if ($__announcement['showname'] == 1) {
                            echo '<small class="font-medium-xs">' . _l('announcement_from') . ' ' . $__announcement['userid'] . '</small><br/>';
                        } ?>
                        <small><?php echo _l('announcement_date', _dt($__announcement['dateadded'])); ?></small>
                    </div>
                    <div class="pull-right">
                        <?php if (is_admin()) { ?>
                            <a href="<?php echo admin_url('announcements/announcement/' . $__announcement['announcementid']); ?>">
                                <i class="fa fa-pencil-square-o"></i>
                            </a>
                        <?php } ?>
                        <a href="<?php echo admin_url('misc/dismiss_announcement/' . $__announcement['announcementid']); ?>">
                            <i class="fa fa-times" aria-hidden="true"></i>
                        </a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <hr class="hr-panel-heading"/>
                <h4 class="bold"><?php echo $__announcement['name']; ?></h4>
                <div class="contents"><?php echo check_for_links($__announcement['message']); ?></div>
            </div>
        </div>
    <?php } ?>
<?php } ?>
<?php do_action('before_start_render_content'); ?>

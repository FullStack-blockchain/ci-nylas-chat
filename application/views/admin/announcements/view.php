<?php init_single_head(); ?>
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content">

                <div id="utilities-manage" class="page-layout simple left-sidebar-floating">

                    <div class="page-content p-4 p-sm-6">

                        <div class="col-md-7 p-0 pr-3">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="bold no-margin"><?php echo $announcement->name; ?></h5>
                                    <p class="text-muted pb-0 mb-0"><?php echo _l('announcement_date', _dt($announcement->dateadded)); ?></p>
                                    <?php if ($announcement->showname == 1) { ?>
                                        <p class="text-muted pb-0 mb-0"><?php echo _l('announcement_from') . ' ' . $announcement->userid; ?></p>
                                    <?php } ?>
                                </div>
                                <div class="card-body">
                                    <?php echo $announcement->message; ?>
                                </div>
                            </div>
                        </div>

                        <?php if (count($recent_announcements) > 0){ ?>
                        <div class="col-md-5 p-0 pl-3">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="bold no-margin"><?php echo _l('announcements_recent'); ?></h5>
                                </div>
                                <div class="card-body">
                                    <?php foreach ($recent_announcements as $announcement){ ?>
                                    <a class="bold"
                                       href="<?php echo admin_url('announcements/view/' . $announcement['announcementid']); ?>">
                                        <?php echo $announcement['name']; ?></a>
                                    <p class="text-muted no-margin"><?php echo _l('announcement_date', _dt($announcement['dateadded'])); ?></p>
                                    <?php if ($announcement['showname'] == 1) { ?>
                                        <p class="text-muted no-margin"><?php echo _l('announcement_from') . ' ' . $announcement['userid']; ?></p>
                                    <?php } ?>
                                    <div class="mtop15">
                                        <?php echo strip_tags(mb_substr($announcement['message'], 0, 250)) . '...'; ?>
                                        <hr class="hr-panel-heading"/>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
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

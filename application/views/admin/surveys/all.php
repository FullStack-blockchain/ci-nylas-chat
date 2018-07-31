<?php init_single_head(); ?>
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content custom-scrollbar">

                <div id="all-surveys-manage" class="page-layout simple left-sidebar-floating">

                    <div class="page-content p-4 p-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <?php if (has_permission('surveys', '', 'create') || has_permission('surveys', '', 'view')) { ?>
                                    <div class="_buttons">
                                        <?php if (has_permission('surveys', '', 'create')) { ?>
                                            <a href="<?php echo admin_url('surveys/survey'); ?>"
                                               class="btn btn-secondary pull-left display-block"><?php echo _l('new_survey'); ?></a>
                                        <?php } ?>
                                        <?php if (has_permission('surveys', '', 'view')) { ?>
                                            <a href="<?php echo admin_url('surveys/mail_lists'); ?>"
                                               class="btn btn-secondary pull-left ml-4 display-block"><?php echo _l('mail_lists'); ?></a>
                                        <?php } ?>
                                    </div>
                                    <div class="clearfix"></div>
                                    <hr class="mt-4 mb-4"/>
                                <?php } ?>
                                <div class="clearfix"></div>
                                <?php render_datatable(array(
                                    _l('id'),
                                    _l('survey_dt_name'),
                                    _l('survey_dt_total_questions'),
                                    _l('survey_dt_total_participants'),
                                    _l('survey_dt_date_created'),
                                    _l('survey_dt_active'),
                                ), 'surveys'); ?>
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
    $(function () {
        initDataTable('.table-surveys', window.location.href);
    });
</script>
</body>
</html>

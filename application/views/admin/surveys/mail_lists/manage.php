<?php init_single_head(); ?>
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content custom-scrollbar">

                <div id="mail-lists-manage" class="page-layout simple left-sidebar-floating">


                    <div class="page-content p-4 p-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <?php if (has_permission('surveys', '', 'create')) { ?>
                                    <div class="_buttons">
                                        <a href="<?php echo admin_url('surveys/mail_list'); ?>"
                                           class="btn btn-info pull-left display-block"><?php echo _l('new_mail_list'); ?></a>
                                    </div>
                                    <div class="clearfix"></div>
                                    <hr class="mt-4 mb-4"/>
                                <?php } ?>
                                <?php render_datatable(array(
                                    _l('id'),
                                    _l('mail_lists_dt_list_name'),
                                    _l('mail_lists_dt_datecreated'),
                                    _l('mail_lists_dt_creator'),
                                    _l('options'),
                                ), 'mail-lists'); ?>
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
        initDataTable('.table-mail-lists', window.location.href, [4], [4]);
    });
</script>
</body>
</html>

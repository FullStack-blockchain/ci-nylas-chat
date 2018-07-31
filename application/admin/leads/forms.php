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
                                    <a href="<?php echo admin_url('leads/form'); ?>"
                                       class="btn btn-info pull-left"><?php echo _l('new_form'); ?></a>
                                </div>
                                <div class="clearfix"></div>
                                <hr class="hr-panel-heading"/>
                                <?php do_action('forms_table_start'); ?>
                                <div class="clearfix"></div>
                                <?php render_datatable(array(
                                    _l('id'),
                                    _l('form_name'),
                                    _l('total_submissions'),
                                    _l('leads_dt_datecreated'),
                                ), 'web-to-lead'); ?>
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
        initDataTable('.table-web-to-lead', window.location.href);
    });
</script>
</body>
</html>

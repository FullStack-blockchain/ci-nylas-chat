<?php init_single_head(); ?>
<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/support.css'); ?>">
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content custom-scrollbar">

                <div id="priorities-manage" class="page-layout simple left-sidebar-floating">

                    <div class="page-content p-4 p-sm-6">
                        <div class="card">
                            <div class="panel-body">
                                <div class="_buttons">
                                    <a href="<?php echo admin_url('tickets/predefined_reply'); ?>"
                                       class="btn btn-info pull-left display-block"><?php echo _l('new_predefined_reply'); ?></a>
                                </div>
                                <div class="clearfix"></div>
                                <hr class="hr-panel-heading"/>
                                <div class="clearfix"></div>
                                <?php render_datatable(array(
                                    _l('predefined_replies_dt_name'),
                                    _l('options'),
                                ), 'predefined-replies'); ?>
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
        initDataTable('.table-predefined-replies', window.location.href, [1], [1]);
    });
</script>
</body>
</html>

<?php init_single_head(); ?>
<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/support.css'); ?>">
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content custom-scrollbar">

                <div id="services-manage" class="page-layout simple left-sidebar-floating">

                    <div class="page-content p-4 p-sm-6">
                        <div class="card">

                            <div class="panel-body">
                                <div class="_buttons">
                                    <a href="#" onclick="new_service(); return false;"
                                       class="btn btn-info pull-left display-block"><?php echo _l('new_service'); ?></a>
                                </div>
                                <div class="clearfix"></div>
                                <hr class="hr-panel-heading"/>
                                <div class="clearfix"></div>
                                <?php render_datatable(array(
                                    _l('services_dt_name'),
                                    _l('options'),
                                ), 'services'); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php $this->load->view('admin/tickets/services/service'); ?>
<?php init_tail(); ?>
<script>
    $(function () {
        initDataTable('.table-services', window.location.href, [1], [1]);
    });
</script>
</body>
</html>

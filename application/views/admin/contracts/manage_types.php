<?php init_single_head(); ?>
<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/contract.css'); ?>">
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content custom-scrollbar">

                <div id="contract" class="page-layout simple left-sidebar-floating">

                    <div class="page-header bg-primary text-auto row no-gutters align-items-center justify-content-between p-4">

                        <div class="col-md col-sm-12">
                            <span class="logo-text h4"><?php echo _l('contract_type'); ?></span>
                        </div>
                    </div>
                    <!-- / HEADER -->

                    <div class="page-content p-4 p-sm-6">
                        <div class="content">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="panel-body">
                                            <div class="_buttons">
                                                <a href="#" onclick="new_type(); return false;"
                                                   class="btn btn-secondary pull-left display-block"><?php echo _l('new_contract_type'); ?></a>
                                            </div>
                                            <div class="clearfix"></div>
                                            <hr class="hr-panel-heading"/>
                                            <div class="clearfix"></div>
                                            <?php render_datatable(array(
                                                _l('name'),
                                                array('name' => _l('options'), 'th_attrs' => array('width' => '90', 'class' => 'text-center'))
                                            ), 'contract-types'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php $this->load->view('admin/contracts/contract_type'); ?>
<?php init_tail(); ?>
<script>
    $(function () {
        initDataTable('.table-contract-types', window.location.href, [1], [1]);
    });
</script>
</body>
</html>

<?php init_single_head(); ?>
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content custom-scrollbar">

                <div id="manage_client_groups" class="page-layout simple left-sidebar-floating">

                    <div class="page-content p-4 p-sm-6">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="_buttons">
                                            <a href="#" class="btn btn-secondary pull-left" data-toggle="modal"
                                               data-target="#customer_group_modal"><?php echo _l('new_customer_group'); ?></a>
                                        </div>
                                        <div class="clearfix"></div>
                                        <hr class="mt-4 mb-4"/>
                                        <div class="clearfix"></div>
                                        <?php render_datatable(array(
                                            _l('customer_group_name'),
                                            _l('options'),
                                        ), 'customer-groups'); ?>
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
<?php $this->load->view('admin/clients/client_group'); ?>
<?php init_tail(); ?>
<script>
    $(function () {
        initDataTable('.table-customer-groups', window.location.href, [1], [1]);
    });
</script>
</body>
</html>

<?php init_single_head(); ?>
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content custom-scrollbar">

                <div id="manage_payment_modes" class="page-layout simple left-sidebar-floating">

                    <div class="page-content p-4 p-sm-6">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="_buttons">
                                            <a href="#" class="btn btn-info pull-left" data-toggle="modal"
                                               data-target="#payment_mode_modal">
                                                <?php echo _l('new_payment_mode'); ?>
                                            </a>
                                        </div>
                                        <div class="clearfix"></div>
                                        <hr class="mt-4 mb-4"/>
                                        <p class="text-warning mtop5"><?php echo _l('payment_modes_add_edit_announcement'); ?></p>
                                        <div class="clearfix"></div>
                                        <?php render_datatable(array(
                                            _l('payment_modes_dt_name'),
                                            _l('payment_modes_dt_description'),
                                            _l('payment_modes_dt_active'),
                                            _l('options')
                                        ), 'payment-modes'); ?>
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
<?php $this->load->view('admin/paymentmodes/paymentmode'); ?>
<?php init_tail(); ?>
<script>
    $(function () {
        initDataTable('.table-payment-modes', window.location.href, [3], [3]);
    });
</script>
</body>
</html>

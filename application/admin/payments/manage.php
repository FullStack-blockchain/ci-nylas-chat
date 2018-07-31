<?php init_single_head(); ?>
<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/payment.css'); ?>">
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content custom-scrollbar">

                <div id="payment-manage" class="page-layout simple left-sidebar-floating">
                    <div class="page-header bg-primary text-auto row no-gutters align-items-center justify-content-between p-4">
                        <div class="col col-md mb-3">
                            <span class="logo-text h4"><?php echo _l('payments'); ?></span>
                        </div>
                    </div>

                    <div class="page-content p-4 p-sm-6">
                        <div class="content">
                            <div class="panel-body card">
                                <?php $this->load->view('admin/payments/table_html'); ?>
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
        initDataTable('.table-payments', admin_url + 'payments/table', undefined, undefined, 'undefined',<?php echo do_action('payments_table_default_order', json_encode(array(0, 'desc'))); ?>);
    });
</script>
</body>
</html>

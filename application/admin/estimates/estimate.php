<?php init_single_head(); ?>
<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/estimates.css'); ?>">
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content custom-scrollbar">

                <div id="estimates-manage" class="page-layout simple left-sidebar-floating">

                    <div class="page-header bg-primary text-auto row no-gutters align-items-center justify-content-between p-4">

                        <div class="col-md col-sm-12">
                            <span class="logo-text h4"><?php if (!isset($estimate)) echo _l('add_new'); else echo _l('edit');
                                echo " " . _l('estimate'); ?></span>
                        </div>

                    </div>
                    <!-- / HEADER -->
                    <div class="page-content p-4 p-sm-6">
                        <div class="content">
                            <div class="row">
                                <?php echo form_open($this->uri->uri_string(), array('id' => 'estimate-form', 'class' => '_transaction_form')); ?>
                                <?php
                                if (isset($estimate)) {
                                    echo form_hidden('isedit');
                                }
                                ?>
                                <div class="col-md-12">
                                    <?php $this->load->view('admin/estimates/estimate_template'); ?>
                                </div>


                                <div class="row estimate-form-save-buttons">
                                    <div class="col p-0 ml-4">
                                        <button type="button"
                                                class="btn btn-info ml-2 estimate-form-submit save-and-send transaction-submit">
                                            <?php echo _l('save_and_send'); ?>
                                        </button>
                                    </div>
                                    <div class="col p-0 ml-4">
                                        <button type="button"
                                                class="btn btn-info ml-2 estimate-form-submit transaction-submit">
                                            <?php echo _l('submit'); ?>
                                        </button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                                <?php $this->load->view('admin/invoice_items/item'); ?>
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
        validate_estimate_form();
        // Init accountacy currency symbol
        init_currency_symbol();
        // Project ajax search
        init_ajax_project_search_by_customer_id();
        // Maybe items ajax search
        init_ajax_search('items', '#item_select.ajax-search', undefined, admin_url + 'items/search');
    });
</script>
</body>
</html>

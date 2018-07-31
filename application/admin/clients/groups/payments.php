<?php if (isset($client)) { ?>
    <h4 class="customer-profile-group-heading"><?php echo _l('client_payments_tab'); ?></h4>
    <div class="p-4">
        <a href="#" class="btn btn-info m-b-10" data-toggle="modal" data-target="#client_zip_payments"><?php echo _l('zip_payments'); ?></a>
        <?php $this->load->view('admin/payments/table_html', array('class' => 'payments-single-client')); ?>
    </div>
    <?php include_once(APPPATH . 'views/admin/clients/modals/zip_payments.php'); ?>
<?php } ?>

<?php if (isset($client)) { ?>
    <h4 class="customer-profile-group-heading"><?php echo _l('client_invoices_tab'); ?></h4>
    <div class="p-4">
        <?php if (has_permission('invoices', '', 'create')) { ?>
            <a href="<?php echo admin_url('invoices/invoice?customer_id=' . $client->userid); ?>"
               class="btn btn-info mbot15 mb-3<?php if ($client->active == 0) {
                   echo ' disabled';
               } ?>">
                <?php echo _l('create_new_invoice'); ?>
            </a>
        <?php } ?>
        <?php if (has_permission('invoices', '', 'view') || has_permission('invoices', '', 'view_own') || get_option('allow_staff_view_invoices_assigned') == '1') { ?>
            <a href="#" class="btn btn-info mbot15 mb-3" data-toggle="modal" data-target="#client_zip_invoices">
                <?php echo _l('zip_invoices'); ?>
            </a>
            <div id="invoices_total" class="mbot20"></div>
            <?php
            $this->load->view('admin/invoices/table_html', array('class' => 'invoices-single-client'));
            include_once(APPPATH . 'views/admin/clients/modals/zip_invoices.php');
            ?>
        <?php } ?>
    </div>
<?php } ?>

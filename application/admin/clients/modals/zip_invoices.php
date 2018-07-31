<!-- Modal -->
<div class="modal fade" id="client_zip_invoices" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?php echo form_open('admin/clients/zip_invoices/' . $client->userid); ?>
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel"><?php echo _l('client_zip_invoices'); ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="invoice_zip_status"><?php echo _l('client_zip_status'); ?></label>

                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="invoice_zip_status"
                                           id="all" value="all" checked />
                                    <span class="radio-icon"></span>
                                    <span><?php echo _l('client_zip_status_all'); ?></span>
                                </label>
                            </div>

                            <?php foreach ($invoice_statuses as $status) { ?>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="invoice_zip_status"
                                               id="s_<?php echo $status; ?>" value="<?php echo $status; ?>"/>
                                        <span class="radio-icon"></span>
                                        <span><?php echo format_invoice_status($status, '', false); ?></span>
                                    </label>
                                </div>
                            <?php } ?>
                        </div>
                        <?php
                        if ($client->company != '') {
                            $file_name = slug_it($client->company);
                        } else {
                            $file_name = slug_it(get_primary_contact_user_id($client->userid));
                        }
                        ?>
                        <?php include(APPPATH . 'views/admin/clients/modals/modal_zip_date_picker.php'); ?>
                        <?php echo form_hidden('file_name', $file_name); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

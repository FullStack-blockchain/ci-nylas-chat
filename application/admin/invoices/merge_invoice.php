<?php
if (count($invoices_to_merge) > 0) { ?>
    <h4 class="bold font-medium"><?php echo _l('invoices_available_for_merging'); ?></h4>
    <?php foreach ($invoices_to_merge as $_inv) { ?>
        <div class="form-check">
            <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="invoices_to_merge[]"
                       value="<?php echo $_inv->id; ?>">
                <span class="checkbox-icon"></span>
                <span class="form-check-description" for=""><a
                            href="<?php echo admin_url('invoices/list_invoices/' . $_inv->id); ?>" data-toggle="tooltip"
                            data-title="<?php echo format_invoice_status($_inv->status, '', false); ?>"
                            target="_blank"><?php echo format_invoice_number($_inv->id); ?></a> - <?php echo format_money($_inv->total, $_inv->symbol); ?></span>
            </label>
        </div>
        <?php if ($_inv->discount_total > 0) {
            echo '<b>' . _l('invoices_merge_discount', format_money($_inv->discount_total, $_inv->symbol)) . '</b><br />';
        }
        ?>
    <?php } ?>
    <p>
    <div class="form-check">
        <label class="form-check-label">
            <input class="form-check-input" type="checkbox" checked name="cancel_merged_invoices"
                   id="cancel_merged_invoices">
            <span class="checkbox-icon"></span>
            <span class="form-check-description" for="cancel_merged_invoices">
                <i class="fa fa-question-circle text-center" data-toggle="tooltip"
                   data-title="<?php echo _l('invoice_merge_number_warning'); ?>"
                   data-placement="right"></i> <?php echo _l('invoices_merge_cancel_merged_invoices'); ?></span>
        </label>
    </div>
    </p>
<?php } ?>

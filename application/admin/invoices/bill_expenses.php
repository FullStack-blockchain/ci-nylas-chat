<?php if (count($expenses_to_bill) > 0) { ?>
    <h4 class="bold mt-3 font-medium"><?php echo _l('expenses_available_to_bill'); ?></h4>
    <?php
    foreach ($expenses_to_bill as $expense) {
        $additional_action = ''; ?>
        <?php if (!empty($expense['expense_name']) || !empty($expense['note'])) {
            ob_start();
            ?>
            <p><?php echo _l('expense_include_additional_data_on_convert'); ?></p>
            <p><b><?php echo _l('expense_add_edit_description'); ?> +</b></p>
            <?php if (!empty($expense['note'])) { ?>
                <div class="form-check invoice_inc_expense_additional_info">
                    <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" id="inc_note"
                               data-id="<?php echo $expense['id']; ?>" data-content="<?php echo $expense['note']; ?>">
                        <span class="checkbox-icon"></span>
                        <span class="form-check-description" for="inc_note" data-toggle="tooltip"
                              data-title="<?php echo $expense['note']; ?>"><?php echo _l('expense'); ?><?php echo _l('expense_add_edit_note'); ?></span>
                    </label>
                </div>
            <?php } ?>
            <?php if (!empty($expense['expense_name'])) { ?>
                <div class="form-check invoice_inc_expense_additional_info">
                    <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" id="inc_name" data-id="<?php echo $expense['id']; ?>"
                               data-content="<?php echo $expense['expense_name']; ?>">
                        <span class="checkbox-icon"></span>
                        <span class="form-check-description" for="inc_name" data-toggle="tooltip"
                              data-title="<?php echo $expense['expense_name']; ?>"><?php echo _l('expense'); ?><?php echo _l('expense_name'); ?></span>
                    </label>
                </div>
            <?php }
            $additional_action = ob_get_contents();
            $additional_action = htmlspecialchars($additional_action);
            ob_end_clean();
            ?>
        <?php }
        $expense['currency_data'] = $this->currencies_model->get($expense['currency']);
        ?>
        <div class="form-check">
            <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="bill_expenses[]" value="<?php echo $expense['id']; ?>" data-toggle="popover"
                       data-html="true" data-content="<?php echo $additional_action; ?>" data-placement="bottom">
                <span class="checkbox-icon"></span>
                <span class="form-check-description" for=""><a href="<?php echo admin_url('expenses/list_expenses/' . $expense['id']); ?>"
                                                              target="_blank"><?php echo $expense['category_name']; ?>
                        <?php if (!empty($expense['expense_name'])) {
                            echo '(' . $expense['expense_name'] . ')';
                        }
                        ?>
                </a>
                    <?php
                    echo ' - ' . format_money($expense['amount'], $expense['currency_data']->symbol);
                    if ($expense['tax'] != 0) {
                        echo '<br /><span class="bold">' . _l('tax_1') . ':</span> ' . $expense['taxrate'] . '% (' . $expense['tax_name'] . ')';
                        $total = $expense['amount'];
                        $total += ($total / 100 * $expense['taxrate']);
                    }
                    if ($expense['tax2'] != 0) {
                        echo '<br /><span class="bold">' . _l('tax_2') . ':</span> ' . $expense['taxrate2'] . '% (' . $expense['tax_name2'] . ')';
                        $total += ($expense['amount'] / 100 * $expense['taxrate2']);
                    }
                    if ($expense['tax'] != 0 || $expense['tax2'] != 0) {
                        echo '<p class="font-medium bold text-danger">' . _l('total_with_tax') . ': ' . format_money($total, $expense['currency_data']->symbol) . '</p>';
                    } ?></span>
            </label>
        </div>
    <?php } ?>
<?php } ?>

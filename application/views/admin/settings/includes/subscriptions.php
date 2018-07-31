<?php render_yes_no_option('show_subscriptions_in_customers_area', 'show_subscriptions_in_customers_area', 'show_subscriptions_in_customers_area_help'); ?>
<hr class="mt-4 mb-4"/>
<h4 class="mbot20 f-18"><?php echo _l('after_subscription_payment_succeeded'); ?></h4>

<div class="form-check mt-4">
    <label class="form-check-label">
        <input type="radio" id="send_invoice_and_receipt" name="settings[after_subscription_payment_captured]" class="form-check-input"
               value="send_invoice_and_receipt"<?php if (get_option('after_subscription_payment_captured') == 'send_invoice_and_receipt') {
            echo ' checked';
        } ?>>
        <span class="radio-icon"></span>
        <span><?php echo _l('subscription_option_send_payment_receipt_and_invoice'); ?>
            <?php if (is_sms_trigger_active(SMS_TRIGGER_PAYMENT_RECORDED)) {
                echo ' + <span class="text-has-action" data-toggle="tooltip" title="' . _l('invoice_payment_recorded') . '">SMS</span>';
            } ?></span>
    </label>
</div>

<div class="form-check mt-2">
    <label class="form-check-label">
        <input type="radio" id="send_invoice" name="settings[after_subscription_payment_captured]" class="form-check-input"
               value="send_invoice"<?php if (get_option('after_subscription_payment_captured') == 'send_invoice') {
            echo ' checked';
        } ?>>
        <span class="radio-icon"></span>
        <span><?php echo _l('subscription_option_send_invoice'); ?>
            <?php if (is_sms_trigger_active(SMS_TRIGGER_PAYMENT_RECORDED)) {
                echo ' + <span class="text-has-action" data-toggle="tooltip" title="' . _l('invoice_payment_recorded') . '">SMS</span>';
            } ?></span>
    </label>
</div>

<div class="form-check mt-2">
    <label class="form-check-label">
        <input type="radio" id="send_payment_receipt" name="settings[after_subscription_payment_captured]" class="form-check-input"
               value="send_payment_receipt"<?php if (get_option('after_subscription_payment_captured') == 'send_payment_receipt') {
            echo ' checked';
        } ?>>
        <span class="radio-icon"></span>
        <span><?php echo _l('subscription_option_send_payment_receipt'); ?>
            <?php if (is_sms_trigger_active(SMS_TRIGGER_PAYMENT_RECORDED)) {
                echo ' + <span class="text-has-action" data-toggle="tooltip" title="' . _l('invoice_payment_recorded') . '">SMS</span>';
            } ?></span>
    </label>
</div>

<div class="form-check mt-2">
    <label class="form-check-label">
        <input type="radio" id="nothing" name="settings[after_subscription_payment_captured]" class="form-check-input"
               value="nothing"<?php if (get_option('after_subscription_payment_captured') == 'nothing') {
            echo ' checked';
        } ?>>
        <span class="radio-icon"></span>
        <span><?php echo _l('subscription_option_do_nothing'); ?></span>
    </label>
</div>

<p class="mt-4"><?php echo _l('email_template'); ?>: <b>Subscription Payment Succeeded</b></p>
<hr class="mt-4 mb-4"/>

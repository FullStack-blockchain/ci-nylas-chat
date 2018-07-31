<?php if (isset($subscription_error)) { ?>
    <div class="alert alert-warning">
        <?php echo $subscription_error; ?>
    </div>
<?php } ?>
<?php echo form_open('', array('id' => 'subscriptionForm', 'class' => '_transaction_form')); ?>
<div class="row">
    <div class="col-md-12">
        <div class="bg-stripe mb-3">
            <div class="form-group select-placeholder">
                <label for="stripe_plan_id"><?php echo _l('billing_plan'); ?></label>
                <select id="stripe_plan_id" name="stripe_plan_id" class="selectpicker" data-live-search="true"
                        data-width="100%" data-none-selected-text="Select Stripe Plan">
                    <option value=""></option>
                    <?php if (isset($plans->data)) { ?>
                        <?php foreach ($plans->data as $plan) {
                            $selected = '';
                            if (isset($subscription) && $subscription->stripe_plan_id == $plan->id) {
                                $selected = ' selected';
                            }
                            ?>
                            <option value="<?php echo $plan->id; ?>" data-amount="<?php echo $plan->amount; ?>"
                                    data-subtext="<?php echo format_money($plan->amount / 100, strtoupper($plan->currency)); ?> (<?php echo $plan->interval; ?>)"<?php echo $selected; ?>>
                                <?php
                                if (empty($plan->nickname)) {
                                    echo '[Plan Name Not Set in Stripe, ID:' . $plan->id . ']';
                                } else {
                                    echo $plan->nickname;
                                }
                                ?>
                            </option>
                        <?php } ?>
                    <?php } ?>
                </select>
            </div>
            <?php echo render_input('quantity', _l('item_quantity_placeholder'), isset($subscription) ? $subscription->quantity : 1, 'number'); ?>
            <?php
            $params = array('data-date-min-date' => date('Y-m-d', strtotime('+1 days', strtotime(date('Y-m-d')))));
            if (isset($subscription) && !empty($subscription->stripe_subscription_id)) {
                $params['disabled'] = true;
            }

            $label = '<label for="date" class="control-label">' . _l('first_billing_date') . ' ';
            if (!isset($params['disabled'])) {
                $label .= '<i class="fa fa-question-circle" data-toggle="tooltip" data-title="Leave blank to use date when the customer is subscribed to the subscription. This field must be future date, if you select date and the date is passed but customer is not yet subscribed, the date when the customer will subscribe will be used."></i>';
            }

            $label .= '</label>';
            echo render_date_input('date', $label, isset($subscription) ? _d($subscription->date) : '', $params);
            if (isset($subscription) && !empty($subscription->stripe_subscription_id) && $subscription->status != 'canceled' && $subscription->status != 'future') { ?>
                <div class="form-check hide" id="prorateWrapper">
                    <label class="form-check-label">
                        <input class="form-check-input ays-ignore" type="checkbox" id="prorate" checked name="prorate"/>
                        <span class="checkbox-icon text-secondary"></span>
                        <span class="form-check-description" for="prorate"><a
                                    href="https://stripe.com/docs/billing/subscriptions/prorations" target="_blank">
                            <i class="fa fa-link"></i></a> Prorate</span>
                    </label>
                </div>
            <?php } ?>
        </div>
        <?php $value = (isset($subscription) ? $subscription->name : ''); ?>
        <?php echo render_input('name', 'subscription_name', $value, 'text', [], [], '', 'ays-ignore'); ?>
        <?php $value = (isset($subscription) ? $subscription->description : ''); ?>
        <?php echo render_textarea('description', 'subscriptions_description', $value, [], [], '', 'ays-ignore'); ?>
        <div class="form-group select-placeholder f_client_id">
            <label for="clientid" class="control-label"><?php echo _l('client'); ?></label>
            <select id="clientid" name="clientid" data-live-search="true" data-width="100%"
                    class="ajax-search ays-ignore"
                    data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>"<?php if (isset($subscription) && !empty($subscription->stripe_subscription_id)) {
                echo ' disabled';
            } ?>>
                <?php $selected = (isset($subscription) ? $subscription->clientid : '');
                if ($selected == '') {
                    $selected = (isset($customer_id) ? $customer_id : '');
                }
                if ($selected != '') {
                    $rel_data = get_relation_data('customer', $selected);
                    $rel_val = get_relation_values($rel_data, 'customer');
                    echo '<option value="' . $rel_val['id'] . '" selected>' . $rel_val['name'] . '</option>';
                } ?>
            </select>
        </div>
        <div class="form-group select-placeholder projects-wrapper<?php if ((!isset($subscription)) || (isset($subscription) && !customer_has_projects($subscription->clientid))) {
            echo ' hide';
        } ?>">
            <label for="project_id"><?php echo _l('project'); ?></label>
            <div id="project_ajax_search_wrapper">
                <select name="project_id" id="project_id" class="projects ajax-search ays-ignore"
                        data-live-search="true" data-width="100%"
                        data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                    <?php
                    if (isset($subscription) && $subscription->project_id != 0) {
                        echo '<option value="' . $subscription->project_id . '" selected>' . get_project_name_by_id($subscription->project_id) . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <?php
        $s_attrs = array('disabled' => true, 'data-show-subtext' => true);
        foreach ($currencies as $currency) {
            if ($currency['isdefault'] == 1) {
                $s_attrs['data-base'] = $currency['id'];
            }
            if (isset($subscription)) {
                if ($currency['id'] == $subscription->currency) {
                    $selected = $currency['id'];
                }
            } else {
                if ($currency['isdefault'] == 1) {
                    $selected = $currency['id'];
                }
            }
        }
        ?>
        <?php if (isset($subscription) && isset($stripeSubscription)) { ?>
            <?php
            if (strtolower($subscription->currency_name) != strtolower($stripeSubscription->plan->currency)) { ?>
                <div class="alert alert-warning">
                    Selected plan currency does not match currency selected below.
                </div>
            <?php } ?>
        <?php } ?>
        <?php echo render_select('currency', $currencies, array('id', 'name', 'symbol'), 'currency', $selected, $s_attrs, [], '', 'ays-ignore'); ?>
        <div class="form-group select-placeholder">
            <label class="control-label" for="tax"><?php echo _l('tax'); ?></label>
            <select class="selectpicker" data-width="100%" name="tax_id"
                    data-none-selected-text="<?php echo _l('no_tax'); ?>">
                <option value=""></option>
                <?php foreach ($taxes as $tax) { ?>
                    <option value="<?php echo $tax['id']; ?>"
                            data-subtext="<?php echo $tax['name']; ?>"<?php if (isset($subscription) && $subscription->tax_id == $tax['id']) {
                        echo ' selected';
                    } ?>><?php echo $tax['taxrate']; ?>%
                    </option>
                <?php } ?>
            </select>
        </div>
    </div>
</div>
<?php if ((isset($subscription) && has_permission('subscriptions', '', 'edit')) || !isset($subscription)) { ?>
    <div class="btn-bottom-toolbar text-right mt-4">
        <button type="submit" class="btn btn-info" data-loading-text="<?php echo _l('wait_text'); ?>"
                data-form="#subscriptionForm">
            <?php echo _l('save'); ?>
        </button>
    </div>
<?php } ?>
<?php echo form_close(); ?>

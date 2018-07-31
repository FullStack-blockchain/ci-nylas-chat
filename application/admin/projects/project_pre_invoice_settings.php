<!-- Copy Project -->
<div class="modal fade" id="pre_invoice_project_settings" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo _l('invoice_project_info'); ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        if (!$this->projects_model->timers_started_for_project($project_id, array('billable' => 1, 'billed' => 0, 'startdate <=' => date('Y-m-d')))) { ?>
                            <div class="row mb-2">
                                <div class="col col-md mb-3">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="radio" <?php if ($billing_type == 3) {
                                                echo 'disabled';
                                            } else {
                                                echo 'checked';
                                            } ?> name="invoice_data_type" value="single_line" id="single_line">
                                            <span class="radio-icon"></span>
                                            <span class="form-check-description"
                                                  for="single_line"><?php echo _l('invoice_project_data_single_line'); ?><?php if ($billing_type == 1) {
                                                    echo ' [ ' . _l('project_billing_type_fixed_cost') . ' ]';
                                                } ?></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col pull-right mt-1 text-right">
                                    <a href="#" class="text-muted" data-toggle="popover" data-placement="left"
                                       data-content="<b><?php echo _l('invoice_project_item_name_data'); ?>:</b> <?php echo _l('invoice_project_project_name_data'); ?><br /><b><?php echo _l('invoice_project_description_data'); ?>:</b> <?php echo _l('invoice_project_all_tasks_total_logged_time'); ?>"
                                       data-html="true"><i class="fa fa-question-circle s-4"></i></a>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col col-md mb-3">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="radio"
                                                   name="invoice_data_type" <?php if ($billing_type == 3) {
                                                echo 'checked';
                                            }
                                            if ($billing_type == 1) {
                                                echo 'disabled';
                                            } ?> value="task_per_item" id="task_per_item">
                                            <span class="radio-icon"></span>
                                            <span class="form-check-description"
                                                  for="task_per_item"><?php echo _l('invoice_project_data_task_per_item'); ?></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col mt-1 text-right">
                                    <a href="#" class="text-muted" data-toggle="popover" data-placement="left"
                                       data-content="<b><?php echo _l('invoice_project_item_name_data'); ?>:</b> <?php echo _l('invoice_project_projectname_taskname'); ?><br /><b><?php echo _l('invoice_project_description_data'); ?>:</b> <?php echo _l('invoice_project_total_logged_time_data'); ?>"
                                       data-html="true"><i class="fa fa-question-circle s-4"></i></a>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col col-md mb-3">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="radio"
                                                   name="invoice_data_type" <?php if ($billing_type == 1) {
                                                echo 'disabled';
                                            } ?> value="timesheets_individualy" id="timesheets_individualy">
                                            <span class="radio-icon"></span>
                                            <span class="form-check-description"
                                                  for="timesheets_individualy"><?php echo _l('invoice_project_data_timesheets_individually'); ?></span>
                                        </label>
                                    </div>
                                    <div id="timesheets_bill_include_notes" class="hide mt-4">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="checkbox"
                                                       name="timesheets_include_notes"
                                                       value="timesheets_include_notes" id="timesheets_include_notes">
                                                <span class="checkbox-icon"></span>
                                                <span class="form-check-description"
                                                      for="timesheets_include_notes"><?php echo _l('invoice_project_include_timesheets_notes'); ?></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col mt-1 text-right">
                                    <a href="#" class="text-muted" data-toggle="popover" data-placement="left"
                                       data-content="<b><?php echo _l('invoice_project_item_name_data'); ?>:</b> <?php echo _l('invoice_project_projectname_taskname'); ?><br /><b><?php echo _l('invoice_project_description_data'); ?>:</b> <?php echo _l('invoice_project_timesheet_individually_data'); ?>"
                                       data-html="true"><i class="fa fa-question-circle s-4"></i></a>
                                </div>
                            </div>

                            <?php if (count($billable_tasks) == 0 && count($not_billable_tasks) == 0 && count($expenses) == 0) { ?>
                                <p class="text-danger mt-3"><?php echo _l('invoice_project_nothing_to_bill'); ?></p>
                            <?php } else { ?>
                                <hr class="mt-4 mb-4"/>
                                <a href="#" onclick="slideToggle('#pre_invoice_project_tasks'); return false;"><b
                                            class="label label-info font-medium-xs inline-block"><?php echo _l('invoice_project_see_billed_tasks'); ?></b></a>

                                <div style="display:none;" id="pre_invoice_project_tasks">
                                    <div class="form-check mt-4">
                                        <label class="form-check-label">
                                            <input class="form-check-input invoice_select_all_tasks" type="checkbox"
                                                   id="project_invoice_select_all_tasks">
                                            <span class="checkbox-icon"></span>
                                            <span class="form-check-description"
                                                  for="project_invoice_select_all_tasks"><?php echo _l('project_invoice_select_all_tasks'); ?></span>
                                        </label>
                                    </div>
                                    <hr class="mt-4 mb-4"/>
                                    <div id="tasks_who_will_be_billed">
                                        <?php foreach ($billable_tasks as $task) {
                                            if ($task['status'] != 5) {
                                                $not_finished_tasks_found = true;
                                            } ?>
                                            <div class="form-check mb-4">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="checkbox" name="tasks[]"
                                                           value="<?php echo $task['id']; ?>"
                                                           <?php if ($task['status'] == 5) {
                                                               echo 'checked ';
                                                           } ?>id="<?php echo $task['id']; ?>">
                                                    <span class="checkbox-icon"></span>
                                                    <span class="form-check-description inline-block full-width"
                                                          for="<?php echo $task['id']; ?>"><?php echo $task['name']; ?> <?php if (total_rows('tbltaskstimers', array('task_id' => $task['id'])) == 0 && $billing_type != 1) {
                                                            echo '<small class="text-danger">' . _l('project_invoice_task_no_timers_found') . '</small>';
                                                        }; ?>
                                                        <small class="pull-right valign"><?php echo format_task_status($task['status']); ?></small>
                                                        </span>
                                                </label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <?php if (count($not_billable_tasks) > 0) { ?>
                                        <hr/>
                                        <p class="text-warning mt-2"><?php echo _l('invoice_project_start_date_tasks_not_passed'); ?></p>
                                        <?php foreach ($not_billable_tasks as $task) { ?>
                                            <div class="form-check mb-4">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="checkbox" name="tasks[]" disabled
                                                           value="<?php echo $task['id']; ?>"
                                                           id="<?php echo $task['id']; ?>">
                                                    <span class="checkbox-icon"></span>
                                                    <span class="form-check-description" for="<?php echo $task['id']; ?>"><?php echo $task['name']; ?>
                                                        <small><?php echo _l('invoice_project_tasks_not_started', _d($task['startdate'])); ?></small></span>
                                                </label>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                                <?php
                                if (count($expenses) > 0) { ?>
                                    <hr class="mt-4 mb-4"/>
                                    <a href="#"
                                       onclick="slideToggle('#expenses_who_will_be_billed'); return false;"><span
                                                class="label label-info font-medium-xs inline-block">
                             <?php echo _l('invoice_project_see_billed_expenses'); ?>
                         </span></a>
                                    <div style="display:none;" id="expenses_who_will_be_billed">

                                        <div class="form-check mt-4">
                                            <label class="form-check-label">
                                                <input class="form-check-input invoice_select_all_expenses" type="checkbox" id="project_invoice_select_all_expenses">
                                                <span class="checkbox-icon"></span>
                                                <span class="form-check-description" for="project_invoice_select_all_expenses"><?php echo _l('project_invoice_select_all_expenses'); ?></span>
                                            </label>
                                        </div>
                                        <hr class="mt-4 mb-4"/>
                                        <?php
                                        $i = 0;
                                        $totalExpenses = count($expenses);
                                        foreach ($expenses as $data) {
                                            $expense = $this->expenses_model->get($data['id']);
                                            $total = $expense->amount;

                                            $totalTaxByExpense = 0;
                                            // Check if tax is applied
                                            if ($expense->tax != 0) {
                                                $total += ($total / 100 * $expense->taxrate);
                                            }

                                            if ($expense->tax2 != 0) {
                                                $total += ($expense->amount / 100 * $expense->taxrate2);
                                            }
                                            ?>
                                            <div class="form-check mb-4 expense-to-bill">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="checkbox" name="expenses[]" checked
                                                           value="<?php echo $expense->expenseid; ?>"
                                                           id="expense_<?php echo $expense->expenseid; ?>">
                                                    <span class="checkbox-icon"></span>
                                                    <span class="form-check-description" for="expense_<?php echo $expense->expenseid; ?>">
                                                    <?php echo $expense->category_name; ?>
                                                        <?php if (!empty($expense->expense_name)) {
                                                            echo '(' . $expense->expense_name . ')';
                                                        } ?>
                                                        - <?php echo format_money($total, $expense->currency_data->symbol); ?></span>
                                                </label>
                                            </div>
                                            <div class="<?php if (empty($expense->expense_name) && empty($expense->note)) {
                                                echo 'hide';
                                            } ?>">
                                                <p style="margin-top:-10px;">
                                                    <i class="fa fa-question-circle" data-toggle="tooltip"
                                                       data-title="<?php echo _l('expense_include_additional_data_on_convert'); ?>"></i>
                                                    <b><?php echo _l('expense_add_edit_description'); ?> +</b>
                                                </p>

                                                <div class="form-check form-check-inline expense-add-note<?php if (empty($expense->expense_name)) {
                                                    echo ' hide';
                                                } ?>">
                                                    <label class="form-check-label">
                                                        <input class="form-check-input" type="checkbox" id="inc_note<?php echo $expense->id; ?>"
                                                               value="<?php echo $expense->id; ?>"
                                                               name="expense_inc_note[]">
                                                        <span class="checkbox-icon"></span>
                                                        <span class="form-check-description" for="inc_note<?php echo $expense->id; ?>"><?php echo _l('expense'); ?><?php echo _l('expense_add_edit_note'); ?></span>
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline expense-add-name<?php if (empty($expense->note)) {
                                                    echo '  hide';
                                                } ?><?php if (empty($expense->expense_name)) {
                                                    echo ' no-mleft';
                                                } ?>">
                                                    <label class="form-check-label">
                                                        <input class="form-check-input" type="checkbox" id="inc_name<?php echo $expense->id; ?>"
                                                               value="<?php echo $expense->id; ?>"
                                                               name="expense_inc_name[]">
                                                        <span class="checkbox-icon"></span>
                                                        <span class="form-check-description" for="inc_name<?php echo $expense->id; ?>"><?php echo _l('expense'); ?><?php echo _l('expense_name'); ?></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <?php if ($i >= 0 && $i != $totalExpenses - 1) { ?>
                                                <hr class="mt-4 mb-4"/>
                                            <?php } ?>
                                            <?php $i++;
                                        } ?>
                                    </div>
                                <?php } ?>
                                <?php if (isset($not_finished_tasks_found)) { ?>
                                    <hr class="mt-4 mb-4"/>
                                    <p class="text-danger"><?php echo _l('invoice_project_all_billable_tasks_marked_as_finished'); ?></p>
                                <?php } ?>
                            <?php } ?>
                        <?php } else {
                            $timers_started = true; ?>
                            <p class="text-danger text-center">
                                <?php echo _l('project_invoice_timers_started'); ?>
                            </p>
                            <hr class="mt-4 mb-4"/>
                            <div class="col-md-6 text-center">
                                <a href="#" onclick="mass_stop_timers(true);return false;"
                                   class="btn btn-default"><?php echo _l('invoice_project_stop_billable_timers_only'); ?></a>
                            </div>
                            <div class="col-md-6 text-center">
                                <a href="#" onclick="mass_stop_timers(false);return false;"
                                   class="btn btn-danger"><?php echo _l('invoice_project_stop_all_timers'); ?></a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <?php if (!isset($timers_started)) { ?>
                    <button type="submit" class="btn btn-info"
                            onclick="invoice_project(<?php echo $project_id; ?>)"><?php echo _l('invoice_project'); ?></button>
                <?php } ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!-- Copy Project end -->

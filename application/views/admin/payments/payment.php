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
                            <span class="logo-text h4"><?php echo _l('payment_edit_for_invoice'); ?>
                                <a href="<?php echo admin_url('invoices/list_invoices/' . $payment->invoiceid); ?>"><?php echo format_invoice_number($invoice->id); ?></a></span>
                        </div>
                    </div>

                    <div class="page-content p-4 p-sm-6">
                        <div class="content">
                            <div class="row">

                                <div class="col-md-5">
                                    <div class="col-md-12 no-padding">
                                        <div class="card">
                                            <?php echo form_open($this->uri->uri_string()); ?>
                                            <div class="panel-body">
                                                <h4 class="no-margin"><?php echo _l('payment_edit_for_invoice'); ?>
                                                    <a href="<?php echo admin_url('invoices/list_invoices/' . $payment->invoiceid); ?>"><?php echo format_invoice_number($invoice->id); ?></a>
                                                </h4>
                                                <hr class="hr-panel-heading"/>
                                                <?php echo render_input('amount', 'payment_edit_amount_received', $payment->amount, 'number',[],[],'no-padding-top'); ?>
                                                <?php echo render_date_input('date', 'payment_edit_date', _d($payment->date)); ?>
                                                <?php echo render_select('paymentmode', $payment_modes, array('id', 'name'), 'payment_mode', $payment->paymentmode); ?>
                                                <?php $lable = '<label for="paymentmethod" class="control-label">' . _l("payment_method") . '
                                                                <i class="fa fa-question-circle" data-toggle="tooltip" data-title="' . _l("payment_method_info") . '"></i>
                                                            </label>';
                                                echo render_input('paymentmethod', $lable, $payment->paymentmethod); ?>
                                                <?php echo render_input('transactionid', 'payment_transaction_id', $payment->transactionid); ?>
                                                <?php echo render_textarea('note', 'note', $payment->note, array('rows' => 6)); ?>
                                                <div class="btn-bottom-toolbar text-right mt-4">
                                                    <button type="submit"
                                                            class="btn btn-info"><?php echo _l('submit'); ?></button>
                                                </div>
                                            </div>
                                            <?php echo form_close(); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-7">
                                    <div class="card">
                                        <div class="panel-body">
                                            <h4 class="pull-left "><?php echo _l('payment_view_heading'); ?></h4>
                                            <div class="pull-right">

                                                <div class="btn-group ml-1">
                                                    <a href="<?php echo admin_url('payments/pdf/' . $payment->paymentid . '?print=true'); ?>"
                                                       target="_blank" class="btn btn-default min-height-auto"
                                                       data-toggle="tooltip"
                                                       title="<?php echo _l('print'); ?>" data-placement="bottom">
                                                        <i class="fa fa-print s-4"></i></a>
                                                </div>
                                                <div class="btn-group ml-1">
                                                    <a href="<?php echo admin_url('payments/pdf/' . $payment->paymentid); ?>"
                                                       class="btn btn-default min-height-auto" data-toggle="tooltip"
                                                       title="<?php echo _l('view_pdf'); ?>"
                                                       data-placement="bottom">
                                                        <i class="fa fa-file-pdf-o s-4"></i></a>
                                                </div>
                                                <?php if (has_permission('managePayment', '', 'delete')) { ?>
                                                    <div class="btn-group ml-1">
                                                        <a href="<?php echo admin_url('payments/delete/' . $payment->paymentid); ?>"
                                                           class="btn btn-danger _delete min-height-auto">
                                                            <i class="fa fa-remove s-4"></i></a>
                                                    </div>
                                                <?php } ?>

                                            </div>
                                            <div class="clearfix"></div>
                                            <hr class="hr-panel-heading"/>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-6">
                                                    <address>
                                                        <?php echo format_organization_info(); ?>
                                                    </address>
                                                </div>
                                                <div class="col-sm-6 text-right">
                                                    <address>
									<span class="bold">
										<?php echo format_customer_info($invoice, 'payment', 'billing', true); ?>
                                                    </address>
                                                </div>
                                            </div>
                                            <div class="col-md-12 text-center">
                                                <h4 class="text-uppercase mt-6 mb-6"><?php echo _l('payment_receipt'); ?></h4>
                                            </div>
                                            <div class="col-md-12 mtop30">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p><?php echo _l('payment_date'); ?> <span
                                                                    class="pull-right bold"><?php echo _d($payment->date); ?></span>
                                                        </p>
                                                        <hr class="mt-4 mb-4"/>
                                                        <p><?php echo _l('payment_view_mode'); ?>
                                                            <span class="pull-right bold">
												<?php echo $payment->name; ?>
                                                <?php if (!empty($payment->paymentmethod)) {
                                                    echo ' - ' . $payment->paymentmethod;
                                                }
                                                ?>
											</span></p>
                                                        <?php if (!empty($payment->transactionid)) { ?>
                                                            <hr class="mt-4 mb-4"/>
                                                            <p><?php echo _l('payment_transaction_id'); ?>: <span
                                                                        class="pull-right bold"><?php echo $payment->transactionid; ?></span>
                                                            </p>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="payment-preview-wrapper">
                                                            <?php echo _l('payment_total_amount'); ?><br/>
                                                            <?php echo format_money($payment->amount, $invoice->symbol); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-4">
                                            <h4 class="p-4"><?php echo _l('payment_for_string'); ?></h4>
                                            <div class="table-responsive p-4">
                                                <table class="table table-borderd table-hover items">
                                                    <thead>
                                                    <tr>
                                                        <th><?php echo _l('payment_table_invoice_number'); ?></th>
                                                        <th><?php echo _l('payment_table_invoice_date'); ?></th>
                                                        <th><?php echo _l('payment_table_invoice_amount_total'); ?></th>
                                                        <th><?php echo _l('payment_table_payment_amount_total'); ?></th>
                                                        <?php if ($invoice->status != 2 && $invoice->status != 5) { ?>
                                                            <th>
                                                                <span class="text-danger"><?php echo _l('invoice_amount_due'); ?></span>
                                                            </th>
                                                        <?php } ?>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td><?php echo format_invoice_number($invoice->id); ?></td>
                                                        <td><?php echo _d($invoice->date); ?></td>
                                                        <td><?php echo format_money($invoice->total, $invoice->symbol); ?></td>
                                                        <td><?php echo format_money($payment->amount, $invoice->symbol); ?></td>
                                                        <?php if ($invoice->status != 2 && $invoice->status != 5) { ?>
                                                            <td class="text-danger">
                                                                <?php echo format_money(get_invoice_total_left_to_pay($invoice->id, $invoice->total), $invoice->symbol); ?>
                                                            </td>
                                                        <?php } ?>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

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
        _validate_form($('form'), {amount: 'required', date: 'required'});
    });
</script>
</body>
</html>

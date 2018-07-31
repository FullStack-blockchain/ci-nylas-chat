<?php include_once(APPPATH . 'views/admin/invoices/invoices_top_stats.php'); ?>
<div class="top-status-actions row">
    <div class="col-auto p-0 pull-right ml-4">
        <div class="btn-group">
            <a href="#" class="btn btn-default btn-with-tooltip invoices-total m-t-2 min-height-auto"
               onclick="slideToggle('#stats-top'); init_invoices_total(true); return false;"
               data-toggle="tooltip" title="<?php echo _l('view_stats_tooltip'); ?>" data-placement="bottom">
                <i class="fa fa-bar-chart text-dark s-4"></i></a>
        </div>
    </div>
    <div class="col-auto p-0 ml-4 pull-right display-block">
        <div class="btn-group pull-right invoice-view-buttons btn-with-tooltip-group _filter_data"
             data-toggle="tooltip" data-title="<?php echo _l('filter_by'); ?>" data-placement="bottom">
            <button type="button" class="btn btn-secondary dropdown-toggle m-t-2 min-height-auto" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-filter text-dark s-4" aria-hidden="true"></i>
            </button>
            <ul class="dropdown-menu width300">
                <li>
                    <a href="#" data-cview="all"
                       onclick="dt_custom_view('','.table-invoices',''); return false;">
                        <?php echo _l('invoices_list_all'); ?>
                    </a>
                </li>
                <li class="divider"></li>
                <li class="<?php if ($this->input->get('filter') == 'not_sent') {
                    echo 'active';
                } ?>">
                    <a href="#" data-cview="not_sent"
                       onclick="dt_custom_view('not_sent','.table-invoices','not_sent'); return false;">
                        <?php echo _l('not_sent_indicator'); ?>
                    </a>
                </li>
                <li>
                    <a href="#" data-cview="not_have_payment"
                       onclick="dt_custom_view('not_have_payment','.table-invoices','not_have_payment'); return false;">
                        <?php echo _l('invoices_list_not_have_payment'); ?>
                    </a>
                </li>
                <li>
                    <a href="#" data-cview="recurring"
                       onclick="dt_custom_view('recurring','.table-invoices','recurring'); return false;">
                        <?php echo _l('invoices_list_recurring'); ?>
                    </a>
                </li>
                <li class="divider"></li>
                <?php foreach ($invoices_statuses as $status) { ?>
                    <li class="<?php if ($status == $this->input->get('status')) {
                        echo 'active';
                    } ?>">
                        <a href="#" data-cview="invoices_<?php echo $status; ?>"
                           onclick="dt_custom_view('invoices_<?php echo $status; ?>','.table-invoices','invoices_<?php echo $status; ?>'); return false;"><?php echo format_invoice_status($status, '', false); ?></a>
                    </li>
                <?php } ?>
                <?php if (count($invoices_years) > 0) { ?>
                    <li class="divider"></li>
                    <?php foreach ($invoices_years as $year) { ?>
                        <li class="active">
                            <a href="#" data-cview="year_<?php echo $year['year']; ?>"
                               onclick="dt_custom_view(<?php echo $year['year']; ?>,'.table-invoices','year_<?php echo $year['year']; ?>'); return false;"><?php echo $year['year']; ?>
                            </a>
                        </li>
                    <?php } ?>
                <?php } ?>
                <?php if (count($invoices_sale_agents) > 0) { ?>
                    <div class="clearfix"></div>
                    <li class="divider"></li>
                    <li class="dropdown-submenu pull-left">
                        <a href="#" tabindex="-1"><?php echo _l('sale_agent_string'); ?></a>
                        <ul class="dropdown-menu dropdown-menu-left">
                            <?php foreach ($invoices_sale_agents as $agent) { ?>
                                <li>
                                    <a href="#"
                                       data-cview="sale_agent_<?php echo $agent['sale_agent']; ?>"
                                       onclick="dt_custom_view(<?php echo $agent['sale_agent']; ?>,'.table-invoices','sale_agent_<?php echo $agent['sale_agent']; ?>'); return false;"><?php echo $agent['full_name']; ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>
                <div class="clearfix"></div>
                <?php if (count($payment_modes) > 0) { ?>
                    <li class="divider"></li>
                <?php } ?>
                <?php foreach ($payment_modes as $mode) {
                    if (total_rows('tblinvoicepaymentrecords', array('paymentmode' => $mode['id'])) == 0) {
                        continue;
                    }
                    ?>
                    <li>
                        <a href="#" data-cview="invoice_payments_by_<?php echo $mode['id']; ?>"
                           onclick="dt_custom_view('<?php echo $mode['id']; ?>','.table-invoices','invoice_payments_by_<?php echo $mode['id']; ?>'); return false;">
                            <?php echo _l('invoices_list_made_payment_by', $mode['name']); ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>
<div class="project_invoices row">
    <?php include_once(APPPATH.'views/admin/invoices/filter_params.php'); ?>
    <?php $this->load->view('admin/invoices/list_template'); ?>
</div>

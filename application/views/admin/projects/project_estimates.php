<?php include_once(APPPATH . 'views/admin/estimates/estimates_top_stats.php'); ?>
<div class="top-status-actions row">
    <div class="btn-group ml-4">
        <a href="#" class="btn btn-default btn-with-tooltip estimates-total m-t-2 min-height-auto"
           onclick="slideToggle('#stats-top'); init_estimates_total(true); return false;"
           data-toggle="tooltip" title="<?php echo _l('view_stats_tooltip'); ?>"
           data-placement="bottom"><i class="fa fa-bar-chart text-dark s-4"></i></a>
    </div>
    <div class="btn-group ml-4 btn-with-tooltip-group _filter_data pull-right"
         data-toggle="tooltip" data-placement="bottom" data-title="<?php echo _l('filter_by'); ?>">
        <button type="button" class="btn btn-secondary dropdown-toggle m-t-2 min-height-auto" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-filter s-4" aria-hidden="true"></i>
        </button>
        <ul class="dropdown-menu width300">
            <li>
                <a href="#" data-cview="all"
                   onclick="dt_custom_view('','.table-estimates',''); return false;">
                    <?php echo _l('estimates_list_all'); ?>
                </a>
            </li>
            <li class="divider"></li>
            <li class="<?php if ($this->input->get('filter') == 'not_sent') {
                echo 'active';
            } ?>">
                <a href="#" data-cview="not_sent"
                   onclick="dt_custom_view('not_sent','.table-estimates','not_sent'); return false;">
                    <?php echo _l('not_sent_indicator'); ?>
                </a>
            </li>
            <li>
                <a href="#" data-cview="invoiced"
                   onclick="dt_custom_view('invoiced','.table-estimates','invoiced'); return false;">
                    <?php echo _l('estimate_invoiced'); ?>
                </a>
            </li>
            <li>
                <a href="#" data-cview="not_invoiced"
                   onclick="dt_custom_view('not_invoiced','.table-estimates','not_invoiced'); return false;"><?php echo _l('estimates_not_invoiced'); ?></a>
            </li>
            <li class="divider"></li>
            <?php foreach ($estimate_statuses as $status) { ?>
                <li class="<?php if ($this->input->get('status') == $status) {
                    echo 'active';
                } ?>">
                    <a href="#" data-cview="estimates_<?php echo $status; ?>"
                       onclick="dt_custom_view('estimates_<?php echo $status; ?>','.table-estimates','estimates_<?php echo $status; ?>'); return false;">
                        <?php echo format_estimate_status($status, '', false); ?>
                    </a>
                </li>
            <?php } ?>
            <div class="clearfix"></div>

            <?php if (count($estimates_sale_agents) > 0) { ?>
                <div class="clearfix"></div>
                <li class="divider"></li>
                <li class="dropdown-submenu pull-left">
                    <a href="#" tabindex="-1"><?php echo _l('sale_agent_string'); ?></a>
                    <ul class="dropdown-menu dropdown-menu-left">
                        <?php foreach ($estimates_sale_agents as $agent) { ?>
                            <li>
                                <a href="#"
                                   data-cview="sale_agent_<?php echo $agent['sale_agent']; ?>"
                                   onclick="dt_custom_view(<?php echo $agent['sale_agent']; ?>,'.table-estimates','sale_agent_<?php echo $agent['sale_agent']; ?>'); return false;"><?php echo $agent['full_name']; ?>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
            <div class="clearfix"></div>
            <?php if (count($estimates_years) > 0) { ?>
                <li class="divider"></li>
                <?php foreach ($estimates_years as $year) { ?>
                    <li class="active">
                        <a href="#" data-cview="year_<?php echo $year['year']; ?>"
                           onclick="dt_custom_view(<?php echo $year['year']; ?>,'.table-estimates','year_<?php echo $year['year']; ?>'); return false;"><?php echo $year['year']; ?>
                        </a>
                    </li>
                <?php } ?>
            <?php } ?>
        </ul>
    </div>
</div>
<div class="project_estimates row">
    <?php $this->load->view('admin/estimates/list_template'); ?>
</div>

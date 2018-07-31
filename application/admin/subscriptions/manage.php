<?php init_single_head(); ?>
<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/subscriptions.css'); ?>">
<main>
    <div id="wrapper">
        <?php init_aside(); ?>
        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content custom-scrollbar">
                <div id="manage-subscription" class="page-layout simple left-sidebar-floating">
                    <div class="page-header bg-primary text-auto row no-gutters align-items-center justify-content-between p-4">

                        <div class="col-md col-sm-12">

                            <div>
                            <span class="logo-icon mr-4">
                                <i class="fa fa-repeat s-6"></i>
                            </span>
                            <span class="logo-text h4"><?php echo _l('subscriptions'); ?></span>
                            </div>
                        </div>

                        <?php if (has_permission('subscriptions', '', 'create')) { ?>
                            <div class="col-auto ml-4">
                                <a href="<?php echo admin_url('subscriptions/create'); ?>"
                                   class="btn btn-secondary fuse-ripple-ready">
                                    <?php echo _l('new_subscription'); ?>
                                </a>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="page-content p-4 p-sm-6">
                        <div class="card">
                            <div class="_filters _hidden_inputs">
                                <?php
                                foreach (get_subscriptions_statuses() as $status) {
                                    $val = '';
                                    if (!$this->input->get('status') || $this->input->get('status') && $this->input->get('status') == $status['id']) {
                                        $val = $status['id'];
                                    }
                                    if (!$this->input->get('status') && $status['id'] == 'canceled') {
                                        $val = '';
                                    }
                                    echo form_hidden('subscription_status_' . $status['id'], $val);
                                }
                                echo form_hidden('not_subscribed', !$this->input->get('status') || $this->input->get('status') && $this->input->get('status') == 'not_subscribed' ? 'not_subscribed' : '');
                                ?>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-9">
                                        <h4 class="no-margin"><span><i class="fa fa-cc-stripe"
                                                                       aria-hidden="true"></i></span> <span
                                                    class="pl-4"><?php echo _l('subscriptions_summary'); ?></span>
                                        </h4>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="btn-group pull-right mleft4 btn-with-tooltip-group _filter_data"
                                             data-toggle="tooltip" data-title="<?php echo _l('filter_by'); ?>"
                                             data-placement="bottom">
                                            <button type="button"
                                                    class="btn btn-secondary dropdown-toggle m-t-2 min-height-auto"
                                                    data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-filter text-dark s-4" aria-hidden="true"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right width300">
                                                <li>
                                                    <a href="#" data-cview="all"
                                                       onclick="dt_custom_view('','.table-subscriptions',''); return false;">
                                                        <?php echo _l('all'); ?>
                                                    </a>
                                                </li>
                                                <li class="divider"></li>
                                                <li class="<?php if (!$this->input->get('status') || $this->input->get('status') && $this->input->get('status') == 'not_subscribed') {
                                                    echo 'active';
                                                } ?>">
                                                    <a href="#" data-cview="not_subscribed"
                                                       onclick="dt_custom_view('not_subscribed','.table-subscriptions','not_subscribed'); return false;">
                                                        <?php echo _l('subscription_not_subscribed'); ?>
                                                    </a>
                                                </li>
                                                <?php foreach (get_subscriptions_statuses() as $status) { ?>
                                                    <li class="<?php if ($status['filter_default'] == true && !$this->input->get('status') || $this->input->get('status') == $status['id']) {
                                                        echo 'active';
                                                    } ?>">
                                                        <a href="#"
                                                           data-cview="<?php echo 'subscription_status_' . $status['id']; ?>"
                                                           onclick="dt_custom_view('subscription_status_<?php echo $status['id']; ?>','.table-subscriptions','subscription_status_<?php echo $status['id']; ?>'); return false;">
                                                            <?php echo _l('subscription_' . $status['id']); ?>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <hr class="hr-panel-heading"/>
                                <div class="row">
                                    <?php foreach (subscriptions_summary() as $summary) { ?>
                                        <div class="col col-md mb-3">
                                            <div class="fuse-widget-front mat-white-bg mat-elevation-z2 ng-tns-c24-4">
                                                <div class="p-6 text-center">
                                                    <div class="font-size-48 line-height-48"
                                                         style="color:<?php echo $summary['color']; ?>">
                                                        <?php echo $summary['total']; ?>
                                                    </div>
                                                    <div class="h3 secondary-text mt-8 font-weight-500">
                                                        <?php echo _l('subscription_' . $summary['id']); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>

                                <hr class="hr-panel-heading"/>

                                <?php do_action('before_subscriptions_table'); ?>
                                <?php $this->load->view('admin/subscriptions/table_html', array('url' => admin_url('subscriptions/table'))); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php init_tail(); ?>
</body>
</html>

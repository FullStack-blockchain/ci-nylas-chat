<?php init_single_head(); ?>
<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/client.css'); ?>">
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content custom-scrollbar">

                <div id="contacts-manage" class="page-layout simple left-sidebar-floating">

                    <div class="page-header bg-primary text-auto row no-gutters align-items-center justify-content-between p-4">

                        <div class="col-md col-sm-12">
                            <div>
                                <span class="logo-icon mr-4">
                                    <i class="fa fa-user-o s-6"></i>
                                </span>
                                <span class="logo-text h4"><?php echo _l('clients'); ?></span>
                            </div>
                        </div>

                        <?php if (has_permission('customers', '', 'create')) { ?>
                            <div class="col-auto ml-4">
                                <a href="<?php echo admin_url('clients/client'); ?>"
                                   class="btn btn-light fuse-ripple-ready"><?php echo _l('new_client'); ?></a>
                            </div>
                            <div class="col-auto ml-4">
                                <a href="<?php echo admin_url('clients/import'); ?>"
                                   class="btn btn-light fuse-ripple-ready"><?php echo _l('import_customers'); ?></a>
                            </div>
                        <?php } ?>
                        <div class="col-auto ml-4">
                            <a href="<?php echo admin_url('clients/all_contacts'); ?>"
                               class="btn btn-light fuse-ripple-ready"><?php echo _l('customer_contacts'); ?></a>
                        </div>
                    </div>
                    <!-- / HEADER -->

                    <div class="page-content p-4 p-sm-6">
                        <div class="card">
                            <div class="_filters _hidden_inputs hidden">
                                <?php
                                echo form_hidden('my_customers');
                                echo form_hidden('requires_registration_confirmation');
                                foreach ($groups as $group) {
                                    echo form_hidden('customer_group_' . $group['id']);
                                }
                                foreach ($contract_types as $type) {
                                    echo form_hidden('contract_type_' . $type['id']);
                                }
                                foreach ($invoice_statuses as $status) {
                                    echo form_hidden('invoices_' . $status);
                                }
                                foreach ($estimate_statuses as $status) {
                                    echo form_hidden('estimates_' . $status);
                                }
                                foreach ($project_statuses as $status) {
                                    echo form_hidden('projects_' . $status['id']);
                                }
                                foreach ($proposal_statuses as $status) {
                                    echo form_hidden('proposals_' . $status);
                                }
                                foreach ($customer_admins as $cadmin) {
                                    echo form_hidden('responsible_admin_' . $cadmin['staff_id']);
                                }
                                foreach ($countries as $country) {
                                    echo form_hidden('country_' . $country['country_id']);
                                }
                                ?>
                            </div>
                            <div class="panel_s">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <h4 class="no-margin"><?php echo _l('customers_summary'); ?></h4>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="btn-group pull-right btn-with-tooltip-group _filter_data"
                                                 data-toggle="tooltip" data-title="<?php echo _l('filter_by'); ?>">
                                                <button type="button" class="btn btn-secondary dropdown-toggle"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                    <i class="fa fa-filter s-4" aria-hidden="true"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-left" style="width:300px;">
                                                    <li class="active"><a href="#" data-cview="all"
                                                                          onclick="dt_custom_view('','.table-clients',''); return false;"><?php echo _l('customers_sort_all'); ?></a>
                                                    </li>
                                                    <?php if (get_option('customer_requires_registration_confirmation') == '1' || total_rows('tblclients', 'registration_confirmed=0') > 0) { ?>
                                                        <li>
                                                            <a href="#"
                                                               data-cview="requires_registration_confirmation"
                                                               onclick="dt_custom_view('requires_registration_confirmation','.table-clients','requires_registration_confirmation'); return false;">
                                                                <?php echo _l('customer_requires_registration_confirmation'); ?>
                                                            </a>
                                                        </li>
                                                    <?php } ?>
                                                    <li>
                                                        <a href="#" data-cview="my_customers"
                                                           onclick="dt_custom_view('my_customers','.table-clients','my_customers'); return false;">
                                                            <?php echo _l('customers_assigned_to_me'); ?>
                                                        </a>
                                                    </li>
                                                    <?php if (count($groups) > 0) { ?>
                                                        <li class="dropdown-submenu groups">
                                                            <a href="#"
                                                               tabindex="-1"><?php echo _l('customer_groups'); ?></a>
                                                            <ul class="dropdown-menu dropdown-menu-left">
                                                                <?php foreach ($groups as $group) { ?>
                                                                    <li><a href="#"
                                                                           data-cview="customer_group_<?php echo $group['id']; ?>"
                                                                           onclick="dt_custom_view('customer_group_<?php echo $group['id']; ?>','.table-clients','customer_group_<?php echo $group['id']; ?>'); return false;"><?php echo $group['name']; ?></a>
                                                                    </li>
                                                                <?php } ?>
                                                            </ul>
                                                        </li>
                                                        <div class="clearfix"></div>
                                                    <?php } ?>
                                                    <?php if (count($countries) > 1) { ?>
                                                        <li class="dropdown-submenu countries">
                                                            <a href="#"
                                                               tabindex="-1"><?php echo _l('clients_country'); ?></a>
                                                            <ul class="dropdown-menu dropdown-menu-left">
                                                                <?php foreach ($countries as $country) { ?>
                                                                    <li><a href="#"
                                                                           data-cview="country_<?php echo $country['country_id']; ?>"
                                                                           onclick="dt_custom_view('country_<?php echo $country['country_id']; ?>','.table-clients','country_<?php echo $country['country_id']; ?>'); return false;"><?php echo $country['short_name']; ?></a>
                                                                    </li>
                                                                <?php } ?>
                                                            </ul>
                                                        </li>
                                                        <div class="clearfix"></div>
                                                    <?php } ?>
                                                    <li class="dropdown-submenu invoice">
                                                        <a href="#" tabindex="-1"><?php echo _l('invoices'); ?></a>
                                                        <ul class="dropdown-menu dropdown-menu-left">
                                                            <?php foreach ($invoice_statuses as $status) { ?>
                                                                <li>
                                                                    <a href="#"
                                                                       data-cview="invoices_<?php echo $status; ?>"
                                                                       onclick="dt_custom_view('invoices_<?php echo $status; ?>','.table-clients','invoices_<?php echo $status; ?>'); return false;"><?php echo _l('customer_have_invoices_by', format_invoice_status($status, '', false)); ?></a>
                                                                </li>
                                                            <?php } ?>
                                                        </ul>
                                                    </li>
                                                    <div class="clearfix"></div>
                                                    <li class="dropdown-submenu estimate">
                                                        <a href="#" tabindex="-1"><?php echo _l('estimates'); ?></a>
                                                        <ul class="dropdown-menu dropdown-menu-left">
                                                            <?php foreach ($estimate_statuses as $status) { ?>
                                                                <li>
                                                                    <a href="#"
                                                                       data-cview="estimates_<?php echo $status; ?>"
                                                                       onclick="dt_custom_view('estimates_<?php echo $status; ?>','.table-clients','estimates_<?php echo $status; ?>'); return false;">
                                                                        <?php echo _l('customer_have_estimates_by', format_estimate_status($status, '', false)); ?>
                                                                    </a>
                                                                </li>
                                                            <?php } ?>
                                                        </ul>
                                                    </li>
                                                    <div class="clearfix"></div>
                                                    <li class="dropdown-submenu project">
                                                        <a href="#" tabindex="-1"><?php echo _l('projects'); ?></a>
                                                        <ul class="dropdown-menu dropdown-menu-left">
                                                            <?php foreach ($project_statuses as $status) { ?>
                                                                <li>
                                                                    <a href="#"
                                                                       data-cview="projects_<?php echo $status['id']; ?>"
                                                                       onclick="dt_custom_view('projects_<?php echo $status['id']; ?>','.table-clients','projects_<?php echo $status['id']; ?>'); return false;">
                                                                        <?php echo _l('customer_have_projects_by', $status['name']); ?>
                                                                    </a>
                                                                </li>
                                                            <?php } ?>
                                                        </ul>
                                                    </li>
                                                    <div class="clearfix"></div>
                                                    <li class="dropdown-submenu proposal">
                                                        <a href="#" tabindex="-1"><?php echo _l('proposals'); ?></a>
                                                        <ul class="dropdown-menu dropdown-menu-left">
                                                            <?php foreach ($proposal_statuses as $status) { ?>
                                                                <li>
                                                                    <a href="#"
                                                                       data-cview="proposals_<?php echo $status; ?>"
                                                                       onclick="dt_custom_view('proposals_<?php echo $status; ?>','.table-clients','proposals_<?php echo $status; ?>'); return false;">
                                                                        <?php echo _l('customer_have_proposals_by', format_proposal_status($status, '', false)); ?>
                                                                    </a>
                                                                </li>
                                                            <?php } ?>
                                                        </ul>
                                                    </li>
                                                    <div class="clearfix"></div>
                                                    <?php if (count($contract_types) > 0) { ?>
                                                        <li class="dropdown-submenu contract_types">
                                                            <a href="#"
                                                               tabindex="-1"><?php echo _l('contract_types'); ?></a>
                                                            <ul class="dropdown-menu dropdown-menu-left">
                                                                <?php foreach ($contract_types as $type) { ?>
                                                                    <li>
                                                                        <a href="#"
                                                                           data-cview="contract_type_<?php echo $type['id']; ?>"
                                                                           onclick="dt_custom_view('contract_type_<?php echo $type['id']; ?>','.table-clients','contract_type_<?php echo $type['id']; ?>'); return false;">
                                                                            <?php echo _l('customer_have_contracts_by_type', $type['name']); ?>
                                                                        </a>
                                                                    </li>
                                                                <?php } ?>
                                                            </ul>
                                                        </li>
                                                    <?php } ?>
                                                    <?php if (count($customer_admins) > 0 && (has_permission('customers', '', 'create') || has_permission('customers', '', 'edit'))) { ?>
                                                        <div class="clearfix"></div>
                                                        <li class="dropdown-submenu responsible_admin">
                                                            <a href="#"
                                                               tabindex="-1"><?php echo _l('responsible_admin'); ?></a>
                                                            <ul class="dropdown-menu dropdown-menu-left">
                                                                <?php foreach ($customer_admins as $cadmin) { ?>
                                                                    <li>
                                                                        <a href="#"
                                                                           data-cview="responsible_admin_<?php echo $cadmin['staff_id']; ?>"
                                                                           onclick="dt_custom_view('responsible_admin_<?php echo $cadmin['staff_id']; ?>','.table-clients','responsible_admin_<?php echo $cadmin['staff_id']; ?>'); return false;">
                                                                            <?php echo get_staff_full_name($cadmin['staff_id']); ?>
                                                                        </a>
                                                                    </li>
                                                                <?php } ?>
                                                            </ul>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if (has_permission('customers', '', 'view') || have_assigned_customers()) {
                                        $where_summary = '';
                                        if (!has_permission('customers', '', 'view')) {
                                            $where_summary = ' AND userid IN (SELECT customer_id FROM tblcustomeradmins WHERE staff_id=' . get_staff_user_id() . ')';
                                        }
                                        ?>
                                        <hr class="hr-panel-heading"/>
                                        <div class="row">
                                            <div class="col col-md mb-3">
                                                <div class="fuse-widget-front mat-white-bg mat-elevation-z2 ng-tns-c24-4">
                                                    <div class="p-6 text-center">
                                                        <div class="text-amber font-size-48 line-height-48">
                                                            <?php echo total_rows('tblclients', ($where_summary != '' ? substr($where_summary, 5) : '')); ?>
                                                        </div>
                                                        <div class="h3 secondary-text mt-8 font-weight-500">
                                                            <?php echo _l('customers_summary_total'); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col col-md mb-3">
                                                <div class="fuse-widget-front mat-white-bg mat-elevation-z2 ng-tns-c24-4">
                                                    <div class="p-6 text-center">
                                                        <div class="text-success font-size-48 line-height-48">
                                                            <?php echo total_rows('tblclients', 'active=1' . $where_summary); ?>
                                                        </div>
                                                        <div class="h3 secondary-text mt-8 font-weight-500">
                                                            <?php echo _l('active_customers'); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col col-md mb-3">
                                                <div class="fuse-widget-front mat-white-bg mat-elevation-z2 ng-tns-c24-4">
                                                    <div class="p-6 text-center">
                                                        <div class="text-danger font-size-48 line-height-48">
                                                            <?php echo total_rows('tblclients', 'active=0' . $where_summary); ?>
                                                        </div>
                                                        <div class="h3 secondary-text mt-8 font-weight-500">
                                                            <?php echo _l('inactive_active_customers'); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col col-md mb-3">
                                                <div class="fuse-widget-front mat-white-bg mat-elevation-z2 ng-tns-c24-4">
                                                    <div class="p-6 text-center">
                                                        <div class="text-info font-size-48 line-height-48">
                                                            <?php echo total_rows('tblcontacts', 'active=1' . $where_summary); ?>
                                                        </div>
                                                        <div class="h3 secondary-text mt-8 font-weight-500">
                                                            <?php echo _l('customers_summary_active'); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col col-md mb-3">
                                                <div class="fuse-widget-front mat-white-bg mat-elevation-z2 ng-tns-c24-4">
                                                    <div class="p-6 text-center">
                                                        <div class="text-danger-900 font-size-48 line-height-48">
                                                            <?php echo total_rows('tblcontacts', 'active=0' . $where_summary); ?>
                                                        </div>
                                                        <div class="h3 secondary-text mt-8 font-weight-500">
                                                            <?php echo _l('customers_summary_inactive'); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col col-md mb-3">
                                                <div class="fuse-widget-front mat-white-bg mat-elevation-z2 ng-tns-c24-4">
                                                    <div class="p-6 text-center">
                                                        <div class="text-muted font-size-48 line-height-48"><?php echo total_rows('tblcontacts', 'last_login LIKE "' . date('Y-m-d') . '%"' . $where_summary); ?></div>
                                                        <div class="h3 secondary-text mt-8 font-weight-500">
                                                            <?php
                                                            $contactsTemplate = '';
                                                            if (count($contacts_logged_in_today) > 0) {
                                                                foreach ($contacts_logged_in_today as $contact) {
                                                                    $url = admin_url('clients/client/' . $contact['userid'] . '?contactid=' . $contact['id']);
                                                                    $fullName = $contact['firstname'] . ' ' . $contact['lastname'];
                                                                    $dateLoggedIn = _dt($contact['last_login']);
                                                                    $html = "<a href='$url' target='_blank'>$fullName</a><br /><small>$dateLoggedIn</small><br />";
                                                                    $contactsTemplate .= htmlspecialchars('<p class="mbot5">' . $html . '</p>');
                                                                }
                                                                ?>
                                                            <?php } ?>
                                                            <span<?php if ($contactsTemplate != '') { ?> class="pointer text-has-action" data-toggle="popover" data-title="<?php echo _l('customers_summary_logged_in_today'); ?>" data-html="true" data-content="<?php echo $contactsTemplate; ?>" data-placement="bottom" <?php } ?>><?php echo _l('customers_summary_logged_in_today'); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <hr class="hr-panel-heading"/>
                                    <a href="#" data-toggle="modal" data-target="#customers_bulk_action"
                                       class="bulk-actions-btn table-btn hide"
                                       data-table=".table-clients"><?php echo _l('bulk_actions'); ?></a>
                                    <div class="modal fade bulk_actions" id="customers_bulk_action"
                                         tabindex="-1" role="dialog">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title"><?php echo _l('bulk_actions'); ?></h4>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span></button>
                                                </div>
                                                <div class="modal-body">
                                                    <?php if (has_permission('customers', '', 'delete')) { ?>
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input" type="checkbox"
                                                                       name="mass_delete" id="mass_delete">
                                                                <span class="checkbox-icon text-danger"></span>
                                                                <span class="form-check-description"
                                                                      for="mass_delete"><?php echo _l('mass_delete'); ?>
                                                            </label>
                                                            </label>
                                                        </div>
                                                        <hr class="mass_delete_separator mt-4 mb-4"/>
                                                    <?php } ?>
                                                    <div id="bulk_change">
                                                        <?php echo render_select('move_to_groups_customers_bulk[]', $groups, array('id', 'name'), 'customer_groups', '', array('multiple' => true), array(), 'no-padding-top mb-4', '', false); ?>
                                                        <p class="text-danger"><?php echo _l('bulk_action_customers_groups_warning'); ?></p>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default"
                                                            data-dismiss="modal"><?php echo _l('close'); ?></button>
                                                    <a href="#" class="btn btn-info"
                                                       onclick="customers_bulk_action(this); return false;"><?php echo _l('confirm'); ?></a>
                                                </div>
                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                    </div><!-- /.modal -->
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" checked
                                                   id="exclude_inactive"
                                                   name="exclude_inactive"/>
                                            <span class="checkbox-icon"></span>
                                            <span><?php echo _l('exclude_inactive'); ?><?php echo _l('clients'); ?></span>
                                        </label>
                                    </div>
                                    <div class="clear-fix m-t-15"></div>
                                    <?php
                                    $table_data = array();
                                    $_table_data = array(
                                        '<span class="hide"> - </span><div class="form-check"><label class="form-check-label"><input type="checkbox" class="form-check-input mass_select_all_wrap" id="mass_select_all" data-to-table="clients"/><span class="checkbox-icon"></span></label></div>',
                                        '#',
                                        _l('clients_list_company'),
                                        _l('contact_primary'),
                                        _l('company_primary_email'),
                                        _l('clients_list_phone'),
                                        _l('customer_active'),
                                        _l('customer_groups'),
                                        _l('date_created'),
                                    );

                                    foreach ($_table_data as $_t) {
                                        array_push($table_data, $_t);
                                    }

                                    $custom_fields = get_custom_fields('customers', array('show_on_table' => 1));
                                    foreach ($custom_fields as $field) {
                                        array_push($table_data, $field['name']);
                                    }

                                    $table_data = do_action('customers_table_columns', $table_data);

                                    render_datatable($table_data, 'clients', [], [
                                        'data-last-order-identifier' => 'customers',
                                        'data-default-order' => get_table_last_order('customers'),
                                    ]);
                                    ?>
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
        var CustomersServerParams = {};
        $.each($('._hidden_inputs._filters input'), function () {
            CustomersServerParams[$(this).attr('name')] = '[name="' + $(this).attr('name') + '"]';
        });
        CustomersServerParams['exclude_inactive'] = '[name="exclude_inactive"]:checked';

        var tAPI = initDataTable('.table-clients', admin_url + 'clients/table', [0], [0], CustomersServerParams,<?php echo do_action('customers_table_default_order', json_encode(array(2, 'asc'))); ?>);
        $('input[name="exclude_inactive"]').on('change', function () {
            tAPI.ajax.reload();
        });
    });

    function customers_bulk_action(event) {
        var r = confirm(appLang.confirm_action_prompt);
        if (r == false) {
            return false;
        } else {
            var mass_delete = $('#mass_delete').prop('checked');
            var ids = [];
            var data = {};
            if (mass_delete == false || typeof(mass_delete) == 'undefined') {
                data.groups = $('select[name="move_to_groups_customers_bulk[]"]').selectpicker('val');
                if (data.groups.length == 0) {
                    data.groups = 'remove_all';
                }
            } else {
                data.mass_delete = true;
            }
            var rows = $('.table-clients').find('tbody tr');
            $.each(rows, function () {
                var checkbox = $($(this).find('td').eq(0)).find('input');
                if (checkbox.prop('checked') == true) {
                    ids.push(checkbox.val());
                }
            });
            data.ids = ids;
            $(event).addClass('disabled');
            setTimeout(function () {
                $.post(admin_url + 'clients/bulk_action', data).done(function () {
                    window.location.reload();
                });
            }, 50);
        }
    }
</script>
</body>
</html>

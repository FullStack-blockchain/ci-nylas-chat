<?php

defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Function that format task status for the final user
 * @param  string $id status id
 * @param  boolean $text
 * @param  boolean $clean
 * @return string
 */
function format_task_status($status, $text = false, $clean = false)
{
    if (!is_array($status)) {
        $status = get_task_status_by_id($status);
    }

    $status_name = $status['name'];
    $hook_data = do_action('task_status_name', ['current' => $status_name, 'status_id' => $status['id']]);
    $status_name = $hook_data['current'];

    if ($clean == true) {
        return $status_name;
    }

    $style = '';
    $class = '';
    if ($text == false) {
        $style = 'border: 1px solid ' . $status['color'] . ';color:' . $status['color'] . ';';
        $class = 'label';
    } else {
        $style = 'color:' . $status['color'] . ';';
    }

    return '<span class="' . $class . '" style="' . $style . '">' . $status_name . '</span>';
}

/**
 * Return predefined tasks priorities
 * @return array
 */
function get_tasks_priorities()
{
    return do_action('tasks_priorities', [
        [
            'id' => 1,
            'name' => _l('task_priority_low'),
            'color' => '#777',

        ],
        [
            'id' => 2,
            'name' => _l('task_priority_medium'),
            'color' => '#03a9f4',

        ],
        [
            'id' => 3,
            'name' => _l('task_priority_high'),
            'color' => '#ff6f00',
        ],
        [
            'id' => 4,
            'name' => _l('task_priority_urgent'),
            'color' => '#fc2d42',
        ],
    ]);
}

/**
 * Get project name by passed id
 * @param  mixed $id
 * @return string
 */
function get_task_subject_by_id($id)
{
    $CI = &get_instance();
    $CI->db->select('name');
    $CI->db->where('id', $id);
    $task = $CI->db->get('tblstafftasks')->row();
    if ($task) {
        return $task->name;
    }

    return '';
}

/**
 * Get task status by passed task id
 * @param  mixed $id task id
 * @return array
 */
function get_task_status_by_id($id)
{
    $CI = &get_instance();
    $statuses = $CI->tasks_model->get_statuses();

    $status = [
        'id' => 0,
        'bg_color' => '#333',
        'text_color' => '#333',
        'name' => '[Status Not Found]',
        'order' => 1,
    ];

    foreach ($statuses as $s) {
        if ($s['id'] == $id) {
            $status = $s;

            break;
        }
    }

    return $status;
}

/**
 * Format task priority based on passed priority id
 * @param  mixed $id
 * @return string
 */
function task_priority($id)
{
    foreach (get_tasks_priorities() as $priority) {
        if ($priority['id'] == $id) {
            return $priority['name'];
        }
    }

    // Not exists?
    return $id;
}

/**
 * Get and return task priority color
 * @param  mixed $id priority id
 * @return string
 */
function task_priority_color($id)
{
    foreach (get_tasks_priorities() as $priority) {
        if ($priority['id'] == $id) {
            return $priority['color'];
        }
    }

    // Not exists?
    return '#333';
}

/**
 * Format html task assignees
 * This function is used to save up on query
 * @param  string $ids string coma separated assignee staff id
 * @param  string $names compa separated in the same order like assignee ids
 * @return string
 */
function format_members_by_ids_and_names($ids, $names, $hidden_export_table = true, $image_class = 'staff-profile-image-small')
{
    $outputAssignees = '';
    $exportAssignees = '';

    $assignees = explode(',', $names);
    $assigneeIds = explode(',', $ids);
    foreach ($assignees as $key => $assigned) {
        $assignee_id = $assigneeIds[$key];
        $assignee_id = trim($assignee_id);
        if ($assigned != '') {
            $outputAssignees .= '<a href="' . admin_url('profile/' . $assignee_id) . '">' .
                staff_profile_image($assignee_id, [
                    $image_class . ' mright5',
                ], 'small', [
                    'data-toggle' => 'tooltip',
                    'data-title' => $assigned,
                ]) . '</a>';
            $exportAssignees .= $assigned . ', ';
        }
    }

    if ($exportAssignees != '') {
        $outputAssignees .= '<span class="hide">' . mb_substr($exportAssignees, 0, -2) . '</span>';
    }

    return $outputAssignees;
}

/**
 * Format task relation name
 * @param  string $rel_name current rel name
 * @param  mixed $rel_id relation id
 * @param  string $rel_type relation type
 * @return string
 */
function task_rel_name($rel_name, $rel_id, $rel_type)
{
    if ($rel_type == 'invoice') {
        $rel_name = format_invoice_number($rel_id);
    } elseif ($rel_type == 'estimate') {
        $rel_name = format_estimate_number($rel_id);
    } elseif ($rel_type == 'proposal') {
        $rel_name = format_proposal_number($rel_id);
    }

    return $rel_name;
}

/**
 * Task relation link
 * @param  mixed $rel_id relation id
 * @param  string $rel_type relation type
 * @return string
 */
function task_rel_link($rel_id, $rel_type)
{
    $link = '#';
    if ($rel_type == 'customer') {
        $link = admin_url('clients/client/' . $rel_id);
    } elseif ($rel_type == 'invoice') {
        $link = admin_url('invoices/list_invoices/' . $rel_id);
    } elseif ($rel_type == 'project') {
        $link = admin_url('projects/view/' . $rel_id);
    } elseif ($rel_type == 'estimate') {
        $link = admin_url('estimates/list_estimates/' . $rel_id);
    } elseif ($rel_type == 'contract') {
        $link = admin_url('contracts/contract/' . $rel_id);
    } elseif ($rel_type == 'ticket') {
        $link = admin_url('tickets/ticket/' . $rel_id);
    } elseif ($rel_type == 'expense') {
        $link = admin_url('expenses/list_expenses/' . $rel_id);
    } elseif ($rel_type == 'lead') {
        $link = admin_url('leads/index/' . $rel_id);
    } elseif ($rel_type == 'proposal') {
        $link = admin_url('proposals/list_proposals/' . $rel_id);
    }

    return $link;
}

/**
 * Common function used to select task relation name
 * @return string
 */
function tasks_rel_name_select_query()
{
    return '(CASE rel_type
        WHEN "contract" THEN (SELECT subject FROM tblcontracts WHERE tblcontracts.id = tblstafftasks.rel_id)
        WHEN "estimate" THEN (SELECT id FROM tblestimates WHERE tblestimates.id = tblstafftasks.rel_id)
        WHEN "proposal" THEN (SELECT id FROM tblproposals WHERE tblproposals.id = tblstafftasks.rel_id)
        WHEN "invoice" THEN (SELECT id FROM tblinvoices WHERE tblinvoices.id = tblstafftasks.rel_id)
        WHEN "ticket" THEN (SELECT CONCAT(CONCAT("#",tbltickets.ticketid), " - ", tbltickets.subject) FROM tbltickets WHERE tbltickets.ticketid=tblstafftasks.rel_id)
        WHEN "lead" THEN (SELECT CASE tblleads.email WHEN "" THEN tblleads.name ELSE CONCAT(tblleads.name, " - ", tblleads.email) END FROM tblleads WHERE tblleads.id=tblstafftasks.rel_id)
        WHEN "customer" THEN (SELECT CASE company WHEN "" THEN (SELECT CONCAT(firstname, " ", lastname) FROM tblcontacts WHERE userid = tblclients.userid and is_primary = 1) ELSE company END FROM tblclients WHERE tblclients.userid=tblstafftasks.rel_id)
        WHEN "project" THEN (SELECT CONCAT(CONCAT(CONCAT("#",tblprojects.id)," - ",tblprojects.name), " - ", (SELECT CASE company WHEN "" THEN (SELECT CONCAT(firstname, " ", lastname) FROM tblcontacts WHERE userid = tblclients.userid and is_primary = 1) ELSE company END FROM tblclients WHERE userid=tblprojects.clientid)) FROM tblprojects WHERE tblprojects.id=tblstafftasks.rel_id)
        WHEN "expense" THEN (SELECT CASE expense_name WHEN "" THEN tblexpensescategories.name ELSE
         CONCAT(tblexpensescategories.name, \' (\',tblexpenses.expense_name,\')\') END FROM tblexpenses JOIN tblexpensescategories ON tblexpensescategories.id = tblexpenses.category WHERE tblexpenses.id=tblstafftasks.rel_id)
        ELSE NULL
        END)';
}


/**
 * Tasks html table used all over the application for relation tasks
 * This table is not used for the main tasks table
 * @param  array $table_attributes
 * @return string
 */
function init_relation_tasks_table($table_attributes = [])
{
    $table_data = [
        [
            'name' => _l('tasks_dt_name'),
            'th_attrs' => [
                'style' => 'min-width:200px',
            ],
        ],
        _l('task_status'),
        [
            'name' => _l('tasks_dt_datestart'),
            'th_attrs' => [
                'style' => 'min-width:75px',
            ],
        ],
        [
            'name' => _l('task_duedate'),
            'th_attrs' => [
                'style' => 'min-width:75px',
                'class' => 'duedate',
            ],
        ],
        [
            'name' => _l('task_assigned'),
            'th_attrs' => [
                'style' => 'min-width:75px',
            ],
        ],
        _l('tags'),
        _l('tasks_list_priority'),
    ];

    array_unshift($table_data, [
        'name' => '<span class="hide"> - </span><div class="form-check mass_select_all_wrap"><label class="form-check-label"><input class="form-check-input" type="checkbox" id="mass_select_all" data-to-table="rel-tasks"><span class="checkbox-icon"></span><span class="form-check-description"></span></label></div>',
        'th_attrs' => ['class' => ($table_attributes['data-new-rel-type'] !== 'project' ? 'not_visible' : '')]
    ]);

    $custom_fields = get_custom_fields('tasks', [
        'show_on_table' => 1,
    ]);

    foreach ($custom_fields as $field) {
        array_push($table_data, $field['name']);
    }

    $table_data = do_action('tasks_related_table_columns', $table_data);

    $name = 'rel-tasks';
    if ($table_attributes['data-new-rel-type'] == 'lead') {
        $name = 'rel-tasks-leads';
    }

    $table = '';
    $CI = &get_instance();
    $table_name = '.table-' . $name;
    $CI->load->view('admin/tasks/tasks_filter_by', [
        'view_table_name' => $table_name,
    ]);
    if (has_permission('tasks', '', 'create')) {
        $disabled = '';
        $table_name = addslashes($table_name);
        if ($table_attributes['data-new-rel-type'] == 'customer' && is_numeric($table_attributes['data-new-rel-id'])) {
            if (total_rows('tblclients', [
                    'active' => 0,
                    'userid' => $table_attributes['data-new-rel-id'],
                ]) > 0) {
                $disabled = ' disabled';
            }
        }
        // projects have button on top
        if ($table_attributes['data-new-rel-type'] != 'project') {
            echo "<a href='#' class='btn btn-secondary pull-left mb-2 mr-1 new-task-relation" . $disabled . "' onclick=\"new_task_from_relation('$table_name'); return false;\" data-rel-id='" . $table_attributes['data-new-rel-id'] . "' data-rel-type='" . $table_attributes['data-new-rel-type'] . "'>" . _l('new_task') . '</a>';
        }
    }

    if ($table_attributes['data-new-rel-type'] == 'project') {
        echo "<div class='pull-right'>";
        echo "<a href='" . admin_url('tasks/list_tasks?project_id=' . $table_attributes['data-new-rel-id'] . '&kanban=true') . "' class='btn btn-default mb-2 mr-2 hidden-xs'>" . _l('view_kanban') . '</a>';
        echo "<a href='" . admin_url('tasks/detailed_overview?project_id=' . $table_attributes['data-new-rel-id']) . "' class='btn btn-success text-white mr-2 mb-2'>" . _l('detailed_overview') . '</a>';
        echo '</div><div class="clear-fix"></div>';
        echo $CI->load->view('admin/tasks/_bulk_actions', ['table' => '.table-rel-tasks'], true);
        echo $CI->load->view('admin/tasks/_summary', ['rel_id' => $table_attributes['data-new-rel-id'], 'rel_type' => 'project', 'table' => $table_name], true);
        echo '<a href="#" data-toggle="modal" data-target="#tasks_bulk_actions" class="hide bulk-actions-btn table-btn" data-table=".table-rel-tasks">' . _l('bulk_actions') . '</a>';
    }
    elseif ($table_attributes['data-new-rel-type'] == 'customer') {
        echo '<div class="clearfix"></div>';
        echo '<div id="tasks_related_filter">';
        echo '<p class="bold">' . _l('task_related_to') . ': </p>';

        echo '<div class="row pl-4 pr-4">
            <div class="form-check checkbox-inline">
                <label class="form-check-label">
                    <input type="checkbox" class="form-check-input" checked value="customer" disabled id="ts_rel_to_customer" name="tasks_related_to[]"/>
                    <span class="checkbox-icon"></span>
                    <span for="ts_rel_to_customer">' . _l('client') . '</span>
                </label>
            </div>
        
            <div class="form-check checkbox-inline">
                <label class="form-check-label">
                    <input type="checkbox" class="form-check-input" value="project" id="ts_rel_to_project" name="tasks_related_to[]"/>
                    <span class="checkbox-icon"></span>
                    <span for="ts_rel_to_project">' . _l('projects') . '</span>
                </label>
            </div>
        
            <div class="form-check checkbox-inline">
                <label class="form-check-label">
                    <input type="checkbox" class="form-check-input" value="invoice" id="ts_rel_to_invoice" name="tasks_related_to[]"/>
                    <span class="checkbox-icon"></span>
                    <span for="ts_rel_to_invoice">' . _l('invoices') . '</span>
                </label>
            </div>
        
            <div class="form-check checkbox-inline">
                <label class="form-check-label">
                    <input type="checkbox" class="form-check-input" value="estimate" id="ts_rel_to_estimate" name="tasks_related_to[]"/>
                    <span class="checkbox-icon"></span>
                    <span for="ts_rel_to_estimate">' . _l('estimates') . '</span>
                </label>
            </div>
        
            <div class="form-check checkbox-inline">
                <label class="form-check-label">
                    <input type="checkbox" class="form-check-input" value="contract" id="ts_rel_to_contract" name="tasks_related_to[]"/>
                    <span class="checkbox-icon"></span>
                    <span for="ts_rel_to_contract">' . _l('contracts') . '</span>
                </label>
            </div>
        
            <div class="form-check checkbox-inline">
                <label class="form-check-label">
                    <input type="checkbox" class="form-check-input" value="ticket" id="ts_rel_to_ticket" name="tasks_related_to[]"/>
                    <span class="checkbox-icon"></span>
                    <span for="ts_rel_to_ticket">' . _l('tickets') . '</span>
                </label>
            </div>
        
            <div class="form-check checkbox-inline">
                <label class="form-check-label">
                    <input type="checkbox" class="form-check-input" value="expense" id="ts_rel_to_expense" name="tasks_related_to[]"/>
                    <span class="checkbox-icon"></span>
                    <span for="ts_rel_to_expense">' . _l('expenses') . '</span>
                </label>
            </div>
        
            <div class="form-check checkbox-inline">
                <label class="form-check-label">
                    <input type="checkbox" class="form-check-input" value="proposal" id="ts_rel_to_proposal" name="tasks_related_to[]"/>
                    <span class="checkbox-icon"></span>
                    <span for="ts_rel_to_proposal">' . _l('proposals') . '</span>
                </label>
            </div>
        </div>';

        echo '</div>';
    }
    echo "<div class='clearfix'></div>";

    // If new column is added on tasks relations table this will not work fine
    // In this case we need to add new identifier eq task-relation
    $table_attributes['data-last-order-identifier'] = 'tasks';
    $table_attributes['data-default-order'] = get_table_last_order('tasks');

    $table .= render_datatable($table_data, $name, [], $table_attributes);
    return $table;
}

/**
 * Return tasks summary formated data
 * @param  string $where additional where to perform
 * @return array
 */
function tasks_summary_data($rel_id = null, $rel_type = null)
{
    $CI = &get_instance();
    $tasks_summary = [];
    $statuses = $CI->tasks_model->get_statuses();
    foreach ($statuses as $status) {
        $tasks_where = 'status = ' . $status['id'];
        if (!has_permission('tasks', '', 'view')) {
            $tasks_where .= ' ' . get_tasks_where_string();
        }
        $tasks_my_where = 'id IN(SELECT taskid FROM tblstafftaskassignees WHERE staffid=' . get_staff_user_id() . ') AND status=' . $status['id'];
        if ($rel_id && $rel_type) {
            $tasks_where .= ' AND rel_id=' . $rel_id . ' AND rel_type="' . $rel_type . '"';
            $tasks_my_where .= ' AND rel_id=' . $rel_id . ' AND rel_type="' . $rel_type . '"';
        } else {
            $sqlProjectTasksWhere = ' AND CASE
            WHEN rel_type="project" AND rel_id IN (SELECT project_id FROM tblprojectsettings WHERE project_id=rel_id AND name="hide_tasks_on_main_tasks_table" AND value=1)
            THEN rel_type != "project"
            ELSE 1=1
            END';
            $tasks_where .= $sqlProjectTasksWhere;
            $tasks_my_where .= $sqlProjectTasksWhere;
        }

        $summary = [];
        $summary['total_tasks'] = total_rows('tblstafftasks', $tasks_where);
        $summary['total_my_tasks'] = total_rows('tblstafftasks', $tasks_my_where);
        $summary['color'] = $status['color'];
        $summary['name'] = $status['name'];
        $summary['status_id'] = $status['id'];
        $tasks_summary[] = $summary;
    }

    return $tasks_summary;
}

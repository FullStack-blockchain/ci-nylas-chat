<?php

defined('BASEPATH') or exit('No direct script access allowed');


$aColumns = [
    '1', // bulk actions
    'tbltickets.ticketid',
    'subject',
    '(SELECT GROUP_CONCAT(name SEPARATOR ",") FROM tbltags_in JOIN tbltags ON tbltags_in.tag_id = tbltags.id WHERE rel_id = tbltickets.ticketid and rel_type="ticket" ORDER by tag_order ASC) as tags',
    'tbldepartments.name as department_name',
    'tblservices.name as service_name',
    'CONCAT(tblcontacts.firstname, \' \', tblcontacts.lastname) as contact_full_name',
    'status',
    'priority',
    'lastreply',
    'tbltickets.date',
    ];

$contactColumn = 6;
$tagsColumns   = 3;

$additionalSelect = [
    'adminread',
    'tbltickets.userid',
    'statuscolor',
    'tbltickets.name as ticket_opened_by_name',
    'tbltickets.email',
    'tbltickets.userid',
    'assigned',
    ];

$join = [
    'LEFT JOIN tblcontacts ON tblcontacts.id = tbltickets.contactid',
    'LEFT JOIN tblservices ON tblservices.serviceid = tbltickets.service',
    'LEFT JOIN tbldepartments ON tbldepartments.departmentid = tbltickets.department',
    'LEFT JOIN tblticketstatus ON tblticketstatus.ticketstatusid = tbltickets.status',
    'LEFT JOIN tblclients ON tblclients.userid = tbltickets.userid',
    'LEFT JOIN tblpriorities ON tblpriorities.priorityid = tbltickets.priority',
    ];

$custom_fields = get_table_custom_fields('tickets');
foreach ($custom_fields as $key => $field) {
    $selectAs = (is_cf_date($field) ? 'date_picker_cvalue_' . $key : 'cvalue_' . $key);
    array_push($customFieldsColumns, $selectAs);
    array_push($aColumns, 'ctable_' . $key . '.value as ' . $selectAs);
    array_push($join, 'LEFT JOIN tblcustomfieldsvalues as ctable_' . $key . ' ON tbltickets.ticketid = ctable_' . $key . '.relid AND ctable_' . $key . '.fieldto="' . $field['fieldto'] . '" AND ctable_' . $key . '.fieldid=' . $field['id']);
}

$where  = [];
$filter = [];

if (isset($userid) && $userid != '') {
    array_push($where, 'AND tbltickets.userid = ' . $userid);
} elseif (isset($by_email)) {
    array_push($where, 'AND tbltickets.email = "' . $by_email . '"');
}
if (isset($where_not_ticket_id)) {
    array_push($where, 'AND tbltickets.ticketid != ' . $where_not_ticket_id);
}
if ($this->ci->input->post('project_id')) {
    array_push($where, 'AND project_id = ' . $this->ci->input->post('project_id'));
}

$statuses  = $this->ci->tickets_model->get_ticket_status();
$_statuses = [];
foreach ($statuses as $__status) {
    if ($this->ci->input->post('ticket_status_' . $__status['ticketstatusid'])) {
        array_push($_statuses, $__status['ticketstatusid']);
    }
}
if (count($_statuses) > 0) {
    array_push($filter, 'AND status IN (' . implode(', ', $_statuses) . ')');
}

if ($this->ci->input->post('my_tickets')) {
    array_push($where, 'OR assigned = ' . get_staff_user_id());
}

$assignees  = $this->ci->tickets_model->get_tickets_assignes_disctinct();
$_assignees = [];
foreach ($assignees as $__assignee) {
    if ($this->ci->input->post('ticket_assignee_' . $__assignee['assigned'])) {
        array_push($_assignees, $__assignee['assigned']);
    }
}
if (count($_assignees) > 0) {
    array_push($filter, 'AND assigned IN (' . implode(', ', $_assignees) . ')');
}

if (count($filter) > 0) {
    array_push($where, 'AND (' . prepare_dt_filter($filter) . ')');
}
// If userid is set, the the view is in client profile, should be shown all tickets
if (!is_admin()) {
    if (get_option('staff_access_only_assigned_departments') == 1) {
        $this->ci->load->model('departments_model');
        $staff_deparments_ids = $this->ci->departments_model->get_staff_departments(get_staff_user_id(), true);
        $departments_ids      = [];
        if (count($staff_deparments_ids) == 0) {
            $departments = $this->ci->departments_model->get();
            foreach ($departments as $department) {
                array_push($departments_ids, $department['departmentid']);
            }
        } else {
            $departments_ids = $staff_deparments_ids;
        }
        if (count($departments_ids) > 0) {
            array_push($where, 'AND department IN (SELECT departmentid FROM tblstaffdepartments WHERE departmentid IN (' . implode(',', $departments_ids) . ') AND staffid="' . get_staff_user_id() . '")');
        }
    }
}

$sIndexColumn = 'ticketid';
$sTable       = 'tbltickets';

// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
            $_data = $aRow[strafter($aColumns[$i], 'as ')];
        } else {
            $_data = $aRow[$aColumns[$i]];
        }

        if ($aColumns[$i] == '1') {
            $_data = '<div class="form-check"><label class="form-check-label"><input class="form-check-input" type="checkbox" value="' . $aRow['tbltickets.ticketid'] . '"><span class="checkbox-icon"></span><span class="form-check-description"></span></label></div>';
        } elseif ($aColumns[$i] == 'lastreply') {
            if ($aRow[$aColumns[$i]] == null) {
                $_data = _l('ticket_no_reply_yet');
            } else {
                $_data = _dt($aRow[$aColumns[$i]]);
            }
        } elseif ($aColumns[$i] == 'subject' || $aColumns[$i] == 'tbltickets.ticketid') {
            // Ticket is assigned
            if ($aRow['assigned'] != 0) {
                if ($aColumns[$i] != 'tbltickets.ticketid') {
                    $_data .= '<a href="' . admin_url('profile/' . $aRow['assigned']) . '" data-toggle="tooltip" title="' . get_staff_full_name($aRow['assigned']) . '" class="pull-left mright5">' . staff_profile_image($aRow['assigned'], [
                        'staff-profile-image-xs',
                        ]) . '</a>';
                }
            }
            $url   = admin_url('tickets/ticket/' . $aRow['tbltickets.ticketid']);
            $_data = '<a href="' . $url . '" class="valign">' . $_data . '</a>';
            if ($aColumns[$i] == 'subject') {
                $_data .= '<div class="row-options">';
                $_data .= '<a href="' . $url . '">' . _l('view') . '</a>';
                $_data .= ' <span class="text-dark"> | </span><a href="' . $url . '?tab=settings">' . _l('edit') . '</a>';
                $_data .= ' <span class="text-dark"> | </span><a href="' . admin_url('tickets/delete/' . $aRow['tbltickets.ticketid']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
                $_data .= '</div>';
            }
        } elseif ($i == $tagsColumns) {
            $_data = render_tags($_data);
        } elseif ($i == $contactColumn) {
            if ($aRow['userid'] != 0) {
                $_data = '<a href="' . admin_url('clients/client/' . $aRow['userid'] . '?group=contacts') . '">' . $aRow['contact_full_name'] . '</a>';
            } else {
                $_data = $aRow['ticket_opened_by_name'];
            }
        } elseif ($aColumns[$i] == 'status') {
            $_data = '<span class="label inline-block" style="border:1px solid ' . $aRow['statuscolor'] . '; color:' . $aRow['statuscolor'] . '">' . ticket_status_translate($aRow['status']) . '</span>';
        } elseif ($aColumns[$i] == 'tbltickets.date') {
            $_data = _dt($_data);
        } elseif ($aColumns[$i] == 'priority') {
            $_data = ticket_priority_translate($aRow['priority']);
        } else {
            if (strpos($aColumns[$i], 'date_picker_') !== false) {
                $_data = (strpos($_data, ' ') !== false ? _dt($_data) : _d($_data));
            }
        }

        $row[] = $_data;

        if ($aRow['adminread'] == 0) {
            $row['DT_RowClass'] = 'text-danger';
        }
    }

    if (isset($row['DT_RowClass'])) {
        $row['DT_RowClass'] .= ' has-row-options';
    } else {
        $row['DT_RowClass'] = 'has-row-options';
    }

    $output['aaData'][] = $row;
}

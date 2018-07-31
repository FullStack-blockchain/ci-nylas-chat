<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Render admin tickets table
 * @param string  $name        table name
 * @param boolean $bulk_action include checkboxes on the left side for bulk actions
 */
function AdminTicketsTableStructure($name = '', $bulk_action = false)
{
    $table = '<table class="table customizable-table dt-table-loading ' . ($name == '' ? 'tickets-table' : $name) . ' table-tickets" id="table-tickets" data-last-order-identifier="tickets" data-default-order="'.get_table_last_order('tickets').'">';
    $table .= '<thead>';
    $table .= '<tr>';

    $table .= '<th class="'.($bulk_action == true ? '' : 'not_visible').'">';
    $table .= '<span class="hide"> - </span><div class="form-check mass_select_all_wrap"><label class="form-check-label"><input class="form-check-input" type="checkbox" id="mass_select_all" data-to-table="tickets"><span class="checkbox-icon"></span><span class="form-check-description"></span></label></div>';
    $table .= '</th>';

    $table .= '<th class="toggleable" id="th-number">#</th>';
    $table .= '<th class="toggleable" id="th-subject">' . _l('ticket_dt_subject') . '</th>';
    $table .= '<th class="toggleable" id="th-tags">' . _l('tags') . '</th>';
    $table .= '<th class="toggleable" id="th-department">' . _l('ticket_dt_department') . '</th>';
    $services_th_attrs = '';
    if (get_option('services') == 0) {
        $services_th_attrs = ' class="not_visible"';
    }
    $table .= '<th' . $services_th_attrs . '>' . _l('ticket_dt_service') . '</th>';
    $table .= '<th class="toggleable" id="th-submitter">' . _l('ticket_dt_submitter') . '</th>';
    $table .= '<th class="toggleable" id="th-status">' . _l('ticket_dt_status') . '</th>';
    $table .= '<th class="toggleable" id="th-priority">' . _l('ticket_dt_priority') . '</th>';
    $table .= '<th class="toggleable" id="th-last-reply">' . _l('ticket_dt_last_reply') . '</th>';
    $table .= '<th class="toggleable ticket_created_column" id="th-created">' . _l('ticket_date_created') . '</th>';

    $custom_fields = get_table_custom_fields('tickets');

    foreach ($custom_fields as $field) {
        $table .= '<th>' . $field['name'] . '</th>';
    }

    $table .= '</tr>';
    $table .= '</thead>';
    $table .= '<tbody></tbody>';
    $table .= '</table>';

    $table .= '<script id="hidden-columns-table-tickets" type="text/json">';
    $table .= get_staff_meta(get_staff_user_id(), 'hidden-columns-table-tickets');
    $table .= '</script>';

    return $table;
}

/**
 * Function to translate ticket status
 * The app offers ability to translate ticket status no matter if they are stored in database
 * @param  mixed $id
 * @return string
 */
function ticket_status_translate($id)
{
    if ($id == '' || is_null($id)) {
        return '';
    }

    $line = _l('ticket_status_db_' . $id, '', false);

    if ($line == 'db_translate_not_found') {
        $CI = & get_instance();
        $CI->db->where('ticketstatusid', $id);
        $status = $CI->db->get('tblticketstatus')->row();

        return !$status ? '' : $status->name;
    }

    return $line;
}

/**
 * Function to translate ticket priority
 * The apps offers ability to translate ticket priority no matter if they are stored in database
 * @param  mixed $id
 * @return string
 */
function ticket_priority_translate($id)
{
    if ($id == '' || is_null($id)) {
        return '';
    }

    $line = _l('ticket_priority_db_' . $id, '', false);

    if ($line == 'db_translate_not_found') {
        $CI = & get_instance();
        $CI->db->where('priorityid', $id);
        $priority = $CI->db->get('tblpriorities')->row();

        return !$priority ? '' : $priority->name;
    }

    return $line;
}

/**
 * When ticket will be opened automatically set to open
 * @param integer  $current Current status
 * @param integer  $id      ticketid
 * @param boolean $admin   Admin opened or client opened
 */
function set_ticket_open($current, $id, $admin = true)
{
    if ($current == 1) {
        return;
    }

    $field = ($admin == false ? 'clientread' : 'adminread');

    $CI = & get_instance();
    $CI->db->where('ticketid', $id);

    $CI->db->update('tbltickets', [
        $field => 1,
    ]);
}

/**
 * For html5 form accepted attributes
 * This function is used for the tickets form attachments
 * @return string
 */
function get_ticket_form_accepted_mimes()
{
    $ticket_allowed_extensions  = get_option('ticket_attachments_file_extensions');
    $_ticket_allowed_extensions = explode(',', $ticket_allowed_extensions);
    $all_form_ext               = $ticket_allowed_extensions;

    if (is_array($_ticket_allowed_extensions)) {
        foreach ($_ticket_allowed_extensions as $ext) {
            $all_form_ext .= ',' . get_mime_by_extension($ext);
        }
    }

    return $all_form_ext;
}
function prepare_tickets_for_export($contact_id)
{
    $CI = &get_instance();
    $CI->load->model('tickets_model');

    $CI->db->where('contactid', $contact_id);
    $tickets = $CI->db->get('tbltickets')->result_array();

    $CI->db->where('show_on_client_portal', 1);
    $CI->db->where('fieldto', 'tickets');
    $CI->db->order_by('field_order', 'asc');
    $custom_fields = $CI->db->get('tblcustomfields')->result_array();

    foreach ($tickets as $ticketKey => $ticket) {
        $CI->db->where('ticketid', $ticket['ticketid']);
        $tickets[$ticketKey]['replies'] = $CI->db->get('tblticketreplies')->result_array();

        $CI->db->where('departmentid', $ticket['department']);
        $dept = $CI->db->get('tbldepartments')->row();

        if ($dept) {
            $tickets[$ticketKey]['department_name'] = $dept->name;
        }

        $CI->db->where('priorityid', $ticket['priority']);
        $priority = $CI->db->get('tblpriorities')->row();
        if ($priority) {
            $tickets[$ticketKey]['priority_name'] = $priority->name;
        }

        $CI->db->where('ticketstatusid', $ticket['status']);
        $status = $CI->db->get('tblticketstatus')->row();
        if ($status) {
            $tickets[$ticketKey]['status_name'] = $status->name;
        }

        $CI->db->where('serviceid', $ticket['service']);
        $service = $CI->db->get('tblservices')->row();
        if ($service) {
            $tickets[$ticketKey]['service_name'] = $service->name;
        }

        $tickets[$ticketKey]['additional_fields'] = [];
        foreach ($custom_fields as $cf) {
            $tickets[$ticketKey]['additional_fields'][] = [
                    'name'  => $cf['name'],
                    'value' => get_custom_field_value($ticket['ticketid'], $cf['id'], 'tickets'),
                ];
        }
    }

    return $tickets;
}

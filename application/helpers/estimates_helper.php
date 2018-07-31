<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Check estimate restrictions - hash, clientid
 * @param  mixed $id   estimate id
 * @param  string $hash estimate hash
 */
function check_estimate_restrictions($id, $hash)
{
    $CI = & get_instance();
    $CI->load->model('estimates_model');
    if (!$hash || !$id) {
        show_404();
    }
    if (!is_client_logged_in() && !is_staff_logged_in()) {
        if (get_option('view_estimate_only_logged_in') == 1) {
            redirect_after_login_to_current_url();
            redirect(site_url('clients/login'));
        }
    }
    $estimate = $CI->estimates_model->get($id);
    if (!$estimate || ($estimate->hash != $hash)) {
        show_404();
    }
    // Do one more check
    if (!is_staff_logged_in()) {
        if (get_option('view_estimate_only_logged_in') == 1) {
            if ($estimate->clientid != get_client_user_id()) {
                show_404();
            }
        }
    }
}

/**
 * Check if estimate email template for expiry reminders is enabled
 * @return boolean
 */
function is_estimates_email_expiry_reminder_enabled()
{
    return total_rows('tblemailtemplates', ['slug' => 'estimate-expiry-reminder', 'active' => 1]) > 0;
}

/**
 * Check if there are sources for sending estimate expiry reminders
 * Will be either email or SMS
 * @return boolean
 */
function is_estimates_expiry_reminders_enabled()
{
    return is_estimates_email_expiry_reminder_enabled() || is_sms_trigger_active(SMS_TRIGGER_ESTIMATE_EXP_REMINDER);
}

/**
 * Return RGBa estimate status color for PDF documents
 * @param  mixed $status_id current estimate status
 * @return string
 */
function estimate_status_color_pdf($status_id)
{
    if ($status_id == 1) {
        $statusColor = '119, 119, 119';
    } elseif ($status_id == 2) {
        // Sent
        $statusColor = '3, 169, 244';
    } elseif ($status_id == 3) {
        //Declines
        $statusColor = '252, 45, 66';
    } elseif ($status_id == 4) {
        //Accepted
        $statusColor = '0, 191, 54';
    } else {
        // Expired
        $statusColor = '255, 111, 0';
    }

    return $statusColor;
}

/**
 * Format estimate status
 * @param  integer  $status
 * @param  string  $classes additional classes
 * @param  boolean $label   To include in html label or not
 * @return mixed
 */
function format_estimate_status($status, $classes = '', $label = true)
{
    $id          = $status;
    $label_class = estimate_status_color_class($status);
    $status      = estimate_status_by_id($status);
    if ($label == true) {
        return '<span class="label label-' . $label_class . ' ' . $classes . ' s-status estimate-status-' . $id . ' estimate-status-' . $label_class . '">' . $status . '</span>';
    }

    return $status;
}

/**
 * Return estimate status translated by passed status id
 * @param  mixed $id estimate status id
 * @return string
 */
function estimate_status_by_id($id)
{
    $status = '';
    if ($id == 1) {
        $status = _l('estimate_status_draft');
    } elseif ($id == 2) {
        $status = _l('estimate_status_sent');
    } elseif ($id == 3) {
        $status = _l('estimate_status_declined');
    } elseif ($id == 4) {
        $status = _l('estimate_status_accepted');
    } elseif ($id == 5) {
        // status 5
        $status = _l('estimate_status_expired');
    } else {
        if (!is_numeric($id)) {
            if ($id == 'not_sent') {
                $status = _l('not_sent_indicator');
            }
        }
    }

    $hook_data = do_action('estimate_status_label', [
        'id'    => $id,
        'label' => $status,
    ]);
    $status = $hook_data['label'];

    return $status;
}

/**
 * Return estimate status color class based on twitter bootstrap
 * @param  mixed  $id
 * @param  boolean $replace_default_by_muted
 * @return string
 */
function estimate_status_color_class($id, $replace_default_by_muted = false)
{
    $class = '';
    if ($id == 1) {
        $class = 'default';
        if ($replace_default_by_muted == true) {
            $class = 'muted';
        }
    } elseif ($id == 2) {
        $class = 'info';
    } elseif ($id == 3) {
        $class = 'danger';
    } elseif ($id == 4) {
        $class = 'success';
    } elseif ($id == 5) {
        // status 5
        $class = 'warning';
    } else {
        if (!is_numeric($id)) {
            if ($id == 'not_sent') {
                $class = 'default';
                if ($replace_default_by_muted == true) {
                    $class = 'muted';
                }
            }
        }
    }

    $hook_data = do_action('estimate_status_color_class', [
        'id'    => $id,
        'class' => $class,
    ]);
    $class = $hook_data['class'];

    return $class;
}

/**
 * Check if the estimate id is last invoice
 * @param  mixed  $id estimateid
 * @return boolean
 */
function is_last_estimate($id)
{
    $CI = & get_instance();
    $CI->db->select('id')->from('tblestimates')->order_by('id', 'desc')->limit(1);
    $query            = $CI->db->get();
    $last_estimate_id = $query->row()->id;
    if ($last_estimate_id == $id) {
        return true;
    }

    return false;
}

/**
 * Format estimate number based on description
 * @param  mixed $id
 * @return string
 */
function format_estimate_number($id)
{
    $CI = & get_instance();
    $CI->db->select('date,number,prefix,number_format')->from('tblestimates')->where('id', $id);
    $estimate = $CI->db->get()->row();

    if (!$estimate) {
        return '';
    }

    $format        = $estimate->number_format;
    $prefix        = $estimate->prefix;
    $number        = $estimate->number;
    $date          = $estimate->date;
    $prefixPadding = get_option('number_padding_prefixes');


    if ($format == 1) {
        // Number based
        $number = $prefix . str_pad($number, $prefixPadding, '0', STR_PAD_LEFT);
    } elseif ($format == 2) {
        // Year based
        $number = $prefix . date('Y', strtotime($date)) . '/' . str_pad($number, $prefixPadding, '0', STR_PAD_LEFT);
    } elseif ($format == 3) {
        // Number-yy based
        $number = $prefix . str_pad($number, $prefixPadding, '0', STR_PAD_LEFT) . '-' . date('y', strtotime($date));
    } elseif ($format == 4) {
        // Number-mm-yyyy based
        $number = $prefix . str_pad($number, $prefixPadding, '0', STR_PAD_LEFT) . '/' . date('m', strtotime($date)) . '/' . date('Y', strtotime($date));
    }

    $hook_data['id']               = $id;
    $hook_data['estimate']         = $estimate;
    $hook_data['formatted_number'] = $number;
    $hook_data                     = do_action('format_estimate_number', $hook_data);
    $number                        = $hook_data['formatted_number'];

    return $number;
}


/**
 * Function that return estimate item taxes based on passed item id
 * @param  mixed $itemid
 * @return array
 */
function get_estimate_item_taxes($itemid)
{
    $CI = & get_instance();
    $CI->db->where('itemid', $itemid);
    $CI->db->where('rel_type', 'estimate');
    $taxes = $CI->db->get('tblitemstax')->result_array();
    $i     = 0;
    foreach ($taxes as $tax) {
        $taxes[$i]['taxname'] = $tax['taxname'] . '|' . $tax['taxrate'];
        $i++;
    }

    return $taxes;
}

/**
 * Calculate estimates percent by status
 * @param  mixed $status          estimate status
 * @return array
 */
function get_estimates_percent_by_status($status, $project_id = null)
{
    $has_permission_view = has_permission('estimates', '', 'view');
    $where               = '';

    if (isset($project_id)) {
        $where .= 'project_id=' . $project_id . ' AND ';
    }
    if (!$has_permission_view) {
        $where .= get_estimates_where_sql_for_staff(get_staff_user_id());
    }

    $where = trim($where);

    if (endsWith($where, ' AND')) {
        $where = substr_replace($where, '', -3);
    }

    $total_estimates = total_rows('tblestimates', $where);

    $data            = [];
    $total_by_status = 0;

    if (!is_numeric($status)) {
        if ($status == 'not_sent') {
            $total_by_status = total_rows('tblestimates', 'sent=0 AND status NOT IN(2,3,4)' . ($where != '' ? ' AND (' . $where . ')' : ''));
        }
    } else {
        $whereByStatus = 'status=' . $status;
        if ($where != '') {
            $whereByStatus .= ' AND (' . $where . ')';
        }
        $total_by_status = total_rows('tblestimates', $whereByStatus);
    }

    $percent                 = ($total_estimates > 0 ? number_format(($total_by_status * 100) / $total_estimates, 2) : 0);
    $data['total_by_status'] = $total_by_status;
    $data['percent']         = $percent;
    $data['total']           = $total_estimates;

    return $data;
}

function get_estimates_where_sql_for_staff($staff_id)
{
    $has_permission_view_own             = has_permission('estimates', '', 'view_own');
    $allow_staff_view_estimates_assigned = get_option('allow_staff_view_estimates_assigned');
    $whereUser                           = '';
    if ($has_permission_view_own) {
        $whereUser = '((tblestimates.addedfrom=' . $staff_id . ' AND tblestimates.addedfrom IN (SELECT staffid FROM tblstaffpermissions JOIN tblpermissions ON tblpermissions.permissionid=tblstaffpermissions.permissionid WHERE tblpermissions.name = "estimates" AND can_view_own=1))';
        if ($allow_staff_view_estimates_assigned == 1) {
            $whereUser .= ' OR sale_agent=' . $staff_id;
        }
        $whereUser .= ')';
    } else {
        $whereUser .= 'sale_agent=' . $staff_id;
    }

    return $whereUser;
}
/**
 * Check if staff member have assigned estimates / added as sale agent
 * @param  mixed $staff_id staff id to check
 * @return boolean
 */
function staff_has_assigned_estimates($staff_id = '')
{
    $CI       = &get_instance();
    $staff_id = is_numeric($staff_id) ? $staff_id : get_staff_user_id();
    $cache    = $CI->object_cache->get('staff-total-assigned-estimates-' . $staff_id);

    if (is_numeric($cache)) {
        $result = $cache;
    } else {
        $result = total_rows('tblestimates', ['sale_agent' => $staff_id]);
        $CI->object_cache->add('staff-total-assigned-estimates-' . $staff_id, $result);
    }

    return $result > 0 ? true : false;
}
/**
 * Check if staff member can view estimate
 * @param  mixed $id estimate id
 * @param  mixed $staff_id
 * @return boolean
 */
function user_can_view_estimate($id, $staff_id = false)
{
    $CI = &get_instance();

    $staff_id = $staff_id ? $staff_id : get_staff_user_id();

    if (has_permission('estimates', $staff_id, 'view')) {
        return true;
    }

    $CI->db->select('id, addedfrom, sale_agent');
    $CI->db->from('tblestimates');
    $CI->db->where('id', $id);
    $estimate = $CI->db->get()->row();

    if ((has_permission('estimates', $staff_id, 'view_own') && $estimate->addedfrom == $staff_id)
            || ($estimate->sale_agent == $staff_id && get_option('allow_staff_view_estimates_assigned') == '1')) {
        return true;
    }

    return false;
}

function prepare_estimates_for_export($customer_id)
{
    $CI = &get_instance();


    $valAllowed = get_option('gdpr_contact_data_portability_allowed');
    if (empty($valAllowed)) {
        $valAllowed = [];
    } else {
        $valAllowed = unserialize($valAllowed);
    }

    $CI->db->where('clientid', $customer_id);
    $estimates = $CI->db->get('tblestimates')->result_array();

    $CI->db->where('show_on_client_portal', 1);
    $CI->db->where('fieldto', 'estimate');
    $CI->db->order_by('field_order', 'asc');
    $custom_fields = $CI->db->get('tblcustomfields')->result_array();

    $CI->load->model('currencies_model');
    foreach ($estimates as $estimatesKey => $estimate) {
        unset($estimates[$estimatesKey]['adminnote']);
        $estimates[$estimatesKey]['shipping_country'] = get_country($estimate['shipping_country']);
        $estimates[$estimatesKey]['billing_country']  = get_country($estimate['billing_country']);

        $estimates[$estimatesKey]['currency'] = $CI->currencies_model->get($estimate['currency']);

        $estimates[$estimatesKey]['items'] = _prepare_items_array_for_export(get_items_by_type('estimate', $estimate['id']), 'estimate');

        if (in_array('estimates_notes', $valAllowed)) {
            // Notes
            $CI->db->where('rel_id', $estimate['id']);
            $CI->db->where('rel_type', 'estimate');

            $estimates[$estimatesKey]['notes'] = $CI->db->get('tblnotes')->result_array();
        }
        if (in_array('estimates_activity_log', $valAllowed)) {
            // Activity
            $CI->db->where('rel_id', $estimate['id']);
            $CI->db->where('rel_type', 'estimate');

            $estimates[$estimatesKey]['activity'] = $CI->db->get('tblsalesactivity')->result_array();
        }
        $estimates[$estimatesKey]['views'] = get_views_tracking('estimate', $estimate['id']);

        $estimates[$estimatesKey]['tracked_emails'] = get_tracked_emails($estimate['id'], 'estimate');

        $estimates[$estimatesKey]['additional_fields'] = [];

        foreach ($custom_fields as $cf) {
            $estimates[$estimatesKey]['additional_fields'][] = [
                    'name'  => $cf['name'],
                    'value' => get_custom_field_value($estimate['id'], $cf['id'], 'estimate'),
                ];
        }
    }

    return $estimates;
}

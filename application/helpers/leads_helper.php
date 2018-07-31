<?php

/**
 * Check if the user is lead creator
 * @since  Version 1.0.4
 * @param  mixed  $leadid leadid
 * @param  mixed  $staff_id staff id (Optional)
 * @return boolean
 */

function is_lead_creator($lead_id, $staff_id = '')
{
    if (!is_numeric($staff_id)) {
        $staff_id = get_staff_user_id();
    }

    $is = total_rows('tblleads', [
        'addedfrom' => $staff_id,
        'id'        => $lead_id,
    ]);

    if ($is > 0) {
        return true;
    }

    return false;
}

function lead_consent_url($id)
{
    return site_url('consent/l/' . get_lead_hash($id));
}

function leads_public_url($id)
{
    return site_url('forms/l/' . get_lead_hash($id));
}

function get_lead_hash($id)
{
    $CI   = &get_instance();
    $hash = '';

    $CI->db->select('hash');
    $CI->db->where('id', $id);
    $lead = $CI->db->get('tblleads')->row();
    if ($lead) {
        $hash = $lead->hash;
        if (empty($hash)) {
            $hash = app_generate_hash() . '-' . app_generate_hash();
            $CI->db->where('id', $id);
            $CI->db->update('tblleads', ['hash' => $hash]);
        }
    }

    return $hash;
}

function get_leads_summary()
{
    $CI = &get_instance();
    if (!class_exists('leads_model')) {
        $CI->load->model('leads_model');
    }
    $statuses = $CI->leads_model->get_status();

    $totalStatuses         = count($statuses);
    $has_permission_view   = has_permission('leads', '', 'view');
    $sql                   = '';
    $whereNoViewPermission = '(addedfrom = ' . get_staff_user_id() . ' OR assigned=' . get_staff_user_id() . ' OR is_public = 1)';

    $statuses[] = [
        'lost'  => true,
        'name'  => _l('lost_leads'),
        'color' => '',
    ];

    $statuses[] = [
        'junk'  => true,
        'name'  => _l('junk_leads'),
        'color' => '',
    ];

    foreach ($statuses as $status) {
        $sql .= ' SELECT COUNT(*) as total';
        $sql .= ' FROM tblleads';

        if (isset($status['lost'])) {
            $sql .= ' WHERE lost=1';
        } elseif (isset($status['junk'])) {
            $sql .= ' WHERE junk=1';
        } else {
            $sql .= ' WHERE status=' . $status['id'];
        }
        if (!$has_permission_view) {
            $sql .= ' AND ' . $whereNoViewPermission;
        }
        $sql .= ' UNION ALL ';
        $sql = trim($sql);
    }

    $result = [];

    // Remove the last UNION ALL
    $sql    = substr($sql, 0, -10);
    $result = $CI->db->query($sql)->result();

    if (!$has_permission_view) {
        $CI->db->where($whereNoViewPermission);
    }

    $total_leads = $CI->db->count_all_results('tblleads');

    foreach ($statuses as $key => $status) {
        if (isset($status['lost']) || isset($status['junk'])) {
            $statuses[$key]['percent'] = ($total_leads > 0 ? number_format(($result[$key]->total * 100) / $total_leads, 2) : 0);
        }

        $statuses[$key]['total'] = $result[$key]->total;
    }

    return $statuses;
}

function render_leads_status_select($statuses, $selected = '', $lang_key = '', $name = 'status', $select_attrs = [])
{
    if (is_admin() || get_option('staff_members_create_inline_lead_status') == '1') {
        return render_select_with_input_group($name, $statuses, ['id', 'name'], $lang_key, $selected, '<a href="#" onclick="new_lead_status_inline();return false;" class="inline-field-new"><i class="fa fa-plus"></i></a>', $select_attrs);
    }

    return render_select($name, $statuses, ['id', 'name'], $lang_key, $selected, $select_attrs);
}

function render_leads_source_select($sources, $selected = '', $lang_key = '', $name = 'source', $select_attrs = [])
{
    if (is_admin() || get_option('staff_members_create_inline_lead_source') == '1') {
        echo render_select_with_input_group($name, $sources, ['id', 'name'], $lang_key, $selected, '<a href="#" onclick="new_lead_source_inline();return false;" class="inline-field-new"><i class="fa fa-plus"></i></a>', $select_attrs);
    } else {
        echo render_select($name, $sources, ['id', 'name'], $lang_key, $selected, $select_attrs);
    }
}

/**
 * Load lead language
 * Used in public GDPR form
 * @param  string $lead_id
 * @return string return loaded language
 */
function load_lead_language($lead_id)
{
    $CI = & get_instance();
    $CI->db->where('id', $lead_id);
    $lead = $CI->db->get('tblleads')->row();

    // Lead not found or default language already loaded
    if (!$lead || empty($lead->default_language)) {
        return false;
    }

    $language = $lead->default_language;

    if (!file_exists(APPPATH . 'language/' . $language)) {
        return false;
    }

    $CI->lang->load($language . '_lang', $language);
    if (file_exists(APPPATH . 'language/' . $language . '/custom_lang.php')) {
        $CI->lang->load('custom_lang', $language);
    }

    return true;
}

function export_lead_data($id)
{
    define('GDPR_EXPORT', true);
    @ini_set('memory_limit', '256M');
    @ini_set('max_execution_time', 360);

    $CI = &get_instance();

    // $lead = $CI->leads_model->get($id);
    $CI->load->library('zip');

    $tmpDir     = get_temp_dir();
    $valAllowed = get_option('gdpr_lead_data_portability_allowed');
    if (empty($valAllowed)) {
        $valAllowed = [];
    } else {
        $valAllowed = unserialize($valAllowed);
    }

    $json = [];


    $CI->db->where('id', $id);
    $lead = $CI->db->get('tblleads')->row_array();
    $slug = slug_it($lead['name']);

    if (in_array('profile_data', $valAllowed) || in_array('custom_fields', $valAllowed)) {
        if (in_array('profile_data', $valAllowed)) {
            $json = $lead;

            $json['country'] = get_country($lead['country']);
            $json['status']  = $CI->leads_model->get_status($lead['status']);
            $json['source']  = $CI->leads_model->get_source($lead['source']);
        }

        if (in_array('custom_fields', $valAllowed)) {
            $custom_fields = get_custom_fields('leads');

            $CI->db->where('show_on_client_portal', 1)
              ->where('fieldto', 'leads')
              ->order_by('field_order', 'asc');

            $custom_fields = $CI->db->get('tblcustomfields')->result_array();

            $json['additional_fields'] = [];

            foreach ($custom_fields as $field) {
                $json['additional_fields'][] = ['name' => $field['name'], 'value' => get_custom_field_value($lead['id'], $field['id'], 'leads')];
            }
        }
    }

    // consent
    if (in_array('consent', $valAllowed)) {
        $CI->load->model('gdpr_model');
        $json['consent'] = $CI->gdpr_model->get_consents(['lead_id' => $lead['id']]);
    }

    // Notes
    if (in_array('notes', $valAllowed)) {
        $CI->db->where('rel_id', $lead['id']);
        $CI->db->where('rel_type', 'lead');
        $json['notes'] = $CI->db->get('tblnotes')->result_array();
    }

    if (in_array('activity_log', $valAllowed)) {
        $json['activity'] = $CI->leads_model->get_lead_activity_log($lead['id']);
    }

    if (in_array('integration_emails', $valAllowed)) {
        $CI->db->where('leadid', $lead['id']);
        $data['emails'] = $CI->db->get('tblleadsemailintegrationemails')->result_array();
    }

    if (in_array('proposals', $valAllowed)) {
        $json['proposals'] = prepare_proposals_for_export($lead['id'], 'lead');
    }

    $tmpDirLeadData = $tmpDir . '/' . $lead['id'] . time() . '-lead';
    mkdir($tmpDirLeadData, 0755);


    $fp = fopen($tmpDirLeadData . '/data.json', 'w');
    fwrite($fp, json_encode($json, JSON_PRETTY_PRINT));
    fclose($fp);

    $CI->zip->read_file($tmpDirLeadData . '/data.json');

    if (is_dir($tmpDirLeadData)) {
        @delete_dir($tmpDirLeadData);
    }

    $CI->zip->download($slug . '-data.zip');
}

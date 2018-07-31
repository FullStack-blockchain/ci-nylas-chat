<?php

/**
 * Check if client id is used in the system
 * @param  mixed  $id client id
 * @return boolean
 */
function is_client_id_used($id)
{
    $total = 0;

    $total += total_rows('tblcontracts', [
        'client' => $id,
    ]);

    $total += total_rows('tblestimates', [
        'clientid' => $id,
    ]);

    $total += total_rows('tblexpenses', [
        'clientid' => $id,
    ]);

    $total += total_rows('tblinvoices', [
        'clientid' => $id,
    ]);

    $total += total_rows('tblproposals', [
        'rel_id'   => $id,
        'rel_type' => 'customer',
    ]);

    $total += total_rows('tbltickets', [
        'userid' => $id,
    ]);

    $total += total_rows('tblprojects', [
        'clientid' => $id,
    ]);

    $total += total_rows('tblstafftasks', [
        'rel_id'   => $id,
        'rel_type' => 'customer',
    ]);

    $total += total_rows('tblcreditnotes', [
        'clientid' => $id,
    ]);

    $total += total_rows('tblsubscriptions', [
        'clientid' => $id,
    ]);

    if ($total > 0) {
        return true;
    }

    return false;
}
/**
 * Check if customer has subscriptions
 * @param  mixed $id customer id
 * @return boolean
 */
function customer_has_subscriptions($id)
{
    return total_rows('tblsubscriptions', ['clientid' => $id]) > 0;
}
/**
 * Get predefined tabs array, used in customer profile
 * @param  mixed $customer_id customer id to prepare the urls
 * @return array
 */
function get_customer_profile_tabs($customer_id)
{
    $customer_tabs = [
      [
        'name'    => 'profile',
        'url'     => admin_url('clients/client/' . $customer_id . '?group=profile'),
        'icon'    => 'fa fa-user-circle',
        'lang'    => _l('client_add_edit_profile'),
        'visible' => true,
        'order'   => 1,
    ],
    [
        'name'    => 'contacts',
        'url'     => admin_url('clients/client/' . $customer_id . '?group=contacts'),
        'icon'    => 'fa fa-users',
        'lang'    => !is_empty_customer_company($customer_id) || empty($customer_id) ? _l('customer_contacts') : _l('contact'),
        'visible' => true,
        'order'   => 2,
    ],
      [
        'name'    => 'notes',
        'url'     => admin_url('clients/client/' . $customer_id . '?group=notes'),
        'icon'    => 'fa fa-sticky-note-o',
        'lang'    => _l('contracts_notes_tab'),
        'visible' => true,
        'order'   => 3,
    ],
      [
        'name'    => 'statement',
        'url'     => admin_url('clients/client/' . $customer_id . '?group=statement'),
        'icon'    => 'fa fa-area-chart',
        'lang'    => _l('customer_statement'),
        'visible' => (has_permission('invoices', '', 'view') && has_permission('payments', '', 'view')),
        'order'   => 4,
    ],
      [
        'name'    => 'invoices',
        'url'     => admin_url('clients/client/' . $customer_id . '?group=invoices'),
        'icon'    => 'fa fa-file-text',
        'lang'    => _l('client_invoices_tab'),
        'visible' => (has_permission('invoices', '', 'view') || has_permission('invoices', '', 'view_own') || (get_option('allow_staff_view_invoices_assigned') == 1 && staff_has_assigned_invoices())),
        'order'   => 5,
    ],
      [
        'name'    => 'payments',
        'url'     => admin_url('clients/client/' . $customer_id . '?group=payments'),
        'icon'    => 'fa fa-line-chart',
        'lang'    => _l('client_payments_tab'),
        'visible' => (has_permission('payments', '', 'view') || has_permission('invoices', '', 'view_own') || (get_option('allow_staff_view_invoices_assigned') == 1 && staff_has_assigned_invoices())),
        'order'   => 6,
    ],
      [
        'name'    => 'proposals',
        'url'     => admin_url('clients/client/' . $customer_id . '?group=proposals'),
        'icon'    => 'fa fa-file-powerpoint-o',
        'lang'    => _l('proposals'),
        'visible' => (has_permission('proposals', '', 'view') || has_permission('proposals', '', 'view_own') || (get_option('allow_staff_view_proposals_assigned') == 1 && staff_has_assigned_proposals())),
        'order'   => 7,
    ],
      [
        'name'    => 'credit_notes',
        'url'     => admin_url('clients/client/' . $customer_id . '?group=credit_notes'),
        'icon'    => 'fa fa-sticky-note-o',
        'lang'    => _l('credit_notes'),
        'visible' => (has_permission('credit_notes', '', 'view') || has_permission('credit_notes', '', 'view_own')),
        'order'   => 8,
    ],
      [
        'name'    => 'estimates',
        'url'     => admin_url('clients/client/' . $customer_id . '?group=estimates'),
        'icon'    => 'fa fa-clipboard',
        'lang'    => _l('estimates'),
        'visible' => (has_permission('estimates', '', 'view') || has_permission('estimates', '', 'view_own') || (get_option('allow_staff_view_estimates_assigned') == 1 && staff_has_assigned_estimates())),
        'order'   => 9,
    ],
    [
            'name'    => 'subscriptions',
            'url'     => admin_url('clients/client/' . $customer_id . '?group=subscriptions'),
            'icon'    => 'fa fa-repeat',
            'lang'    => _l('subscriptions'),
            'visible' => (has_permission('subscriptions', '', 'view') || has_permission('subscriptions', '', 'view_own')),
            'order'   => 10,
      ],
      [
        'name'    => 'expenses',
        'url'     => admin_url('clients/client/' . $customer_id . '?group=expenses'),
        'icon'    => 'fa fa-file-text-o',
        'lang'    => _l('expenses'),
        'visible' => (has_permission('expenses', '', 'view') || has_permission('expenses', '', 'view_own')),
        'order'   => 11,
    ],
      [
        'name'    => 'contracts',
        'url'     => admin_url('clients/client/' . $customer_id . '?group=contracts'),
        'icon'    => 'fa fa-file',
        'lang'    => _l('contracts'),
        'visible' => (has_permission('contracts', '', 'view') || has_permission('contracts', '', 'view_own')),
        'order'   => 12,
    ],
      [
        'name'    => 'projects',
        'url'     => admin_url('clients/client/' . $customer_id . '?group=projects'),
        'icon'    => 'fa fa-bars',
        'lang'    => _l('projects'),
        'visible' => true,
        'order'   => 13,
    ],
      [
        'name'    => 'tasks',
        'url'     => admin_url('clients/client/' . $customer_id . '?group=tasks'),
        'icon'    => 'fa fa-tasks',
        'lang'    => _l('tasks'),
        'visible' => true,
        'order'   => 14,
    ],
      [
        'name'    => 'tickets',
        'url'     => admin_url('clients/client/' . $customer_id . '?group=tickets'),
        'icon'    => 'fa fa-ticket',
        'lang'    => _l('tickets'),
        'visible' => ((get_option('access_tickets_to_none_staff_members') == 1 && !is_staff_member()) || is_staff_member()),
        'order'   => 15,
    ],
      [
        'name'    => 'attachments',
        'url'     => admin_url('clients/client/' . $customer_id . '?group=attachments'),
        'icon'    => 'fa fa-paperclip',
        'lang'    => _l('customer_attachments'),
        'visible' => true,
        'order'   => 16,
    ],
      [
        'name'    => 'vault',
        'url'     => admin_url('clients/client/' . $customer_id . '?group=vault'),
        'icon'    => 'fa fa-lock',
        'lang'    => _l('vault'),
        'visible' => true,
        'order'   => 17,
    ],
      [
        'name'    => 'reminders',
        'url'     => admin_url('clients/client/' . $customer_id . '?group=reminders'),
        'icon'    => 'fa fa-clock-o',
        'lang'    => _l('client_reminders_tab'),
        'visible' => true,
        'order'   => 18,
        'id'      => 'reminders',
    ],
      [
        'name'    => 'map',
        'url'     => admin_url('clients/client/' . $customer_id . '?group=map'),
        'icon'    => 'fa fa-map-marker',
        'lang'    => _l('customer_map'),
        'visible' => true,
        'order'   => 19,
    ],

  ];

    $hook_data     = do_action('customer_profile_tabs', ['tabs' => $customer_tabs, 'customer_id' => $customer_id]);
    $customer_tabs = $hook_data['tabs'];

    usort($customer_tabs, function ($a, $b) {
        return $a['order'] - $b['order'];
    });

    return $customer_tabs;
}

/**
 * Get client id by lead id
 * @since  Version 1.0.1
 * @param  mixed $id lead id
 * @return mixed     client id
 */
function get_client_id_by_lead_id($id)
{
    $CI = & get_instance();
    $CI->db->select('userid')->from('tblclients')->where('leadid', $id);

    return $CI->db->get()->row()->userid;
}

/**
 * Check if contact id passed is primary contact
 * If you dont pass $contact_id the current logged in contact will be checked
 * @param  string  $contact_id
 * @return boolean
 */
function is_primary_contact($contact_id = '')
{
    if (!is_numeric($contact_id)) {
        $contact_id = get_contact_user_id();
    }

    if (total_rows('tblcontacts', [
        'id' => $contact_id,
        'is_primary' => 1,
    ]) > 0) {
        return true;
    }

    return false;
}

/**
 * Check if client have invoices with multiple currencies
 * @return booelan
 */
function is_client_using_multiple_currencies($clientid = '', $table = 'tblinvoices')
{
    $CI = & get_instance();

    $clientid = $clientid == '' ? get_client_user_id() : $clientid;
    $CI->load->model('currencies_model');
    $currencies            = $CI->currencies_model->get();
    $total_currencies_used = 0;
    foreach ($currencies as $currency) {
        $CI->db->where('currency', $currency['id']);
        $CI->db->where('clientid', $clientid);
        $total = $CI->db->count_all_results($table);
        if ($total > 0) {
            $total_currencies_used++;
        }
    }
    if ($total_currencies_used > 1) {
        return true;
    } elseif ($total_currencies_used == 0 || $total_currencies_used == 1) {
        return false;
    }

    return true;
}


/**
 * Function used to check if is really empty customer company
 * Can happen user to have selected that the company field is not required and the primary contact name is auto added in the company field
 * @param  mixed  $id
 * @return boolean
 */
function is_empty_customer_company($id)
{
    $CI = & get_instance();
    $CI->db->select('company');
    $CI->db->from('tblclients');
    $CI->db->where('userid', $id);
    $row = $CI->db->get()->row();
    if ($row) {
        if ($row->company == '') {
            return true;
        }

        return false;
    }

    return true;
}

/**
 * Get ids to check what files with contacts are shared
 * @param  array  $where
 * @return array
 */
function get_customer_profile_file_sharing($where = [])
{
    $CI = & get_instance();
    $CI->db->where($where);

    return $CI->db->get('tblcustomerfiles_shares')->result_array();
}

/**
 * Get customer id by passed contact id
 * @param  mixed $id
 * @return mixed
 */
function get_user_id_by_contact_id($id)
{
    $CI = & get_instance();

    $userid = $CI->object_cache->get('user-id-by-contact-id-' . $id);
    if (!$userid) {
        $CI->db->select('userid')
        ->where('id', $id);
        $client = $CI->db->get('tblcontacts')->row();

        if ($client) {
            $userid = $client->userid;
            $CI->object_cache->add('user-id-by-contact-id-' . $id, $userid);
        }
    }

    return $userid;
}

/**
 * Get primary contact user id for specific customer
 * @param  mixed $userid
 * @return mixed
 */
function get_primary_contact_user_id($userid)
{
    $CI = & get_instance();
    $CI->db->where('userid', $userid);
    $CI->db->where('is_primary', 1);
    $row = $CI->db->get('tblcontacts')->row();

    if ($row) {
        return $row->id;
    }

    return false;
}

/**
 * Get client full name
 * @param  string $contact_id Optional
 * @return string Firstname and Lastname
 */
function get_contact_full_name($contact_id = '')
{
    $contact_id == '' ? get_contact_user_id() : $contact_id;

    $CI = & get_instance();

    $contact = $CI->object_cache->get('contact-full-name-data-' . $contact_id);

    if (!$contact) {
        $CI->db->where('id', $contact_id);
        $contact = $CI->db->select('firstname,lastname')->from('tblcontacts')->get()->row();
        $CI->object_cache->add('contact-full-name-data-' . $contact_id, $contact);
    }
    if ($contact) {
        return $contact->firstname . ' ' . $contact->lastname;
    }

    return '';
}
/**
 * Return contact profile image url
 * @param  mixed $contact_id
 * @param  string $type
 * @return string
 */
function contact_profile_image_url($contact_id, $type = 'small')
{
    $url  = base_url('assets-old/images/user-placeholder.jpg');
    $CI   = & get_instance();
    $path = $CI->object_cache->get('contact-profile-image-path-' . $contact_id);

    if (!$path) {
        $CI->object_cache->add('contact-profile-image-path-' . $contact_id, $url);

        $CI->db->select('profile_image');
        $CI->db->from('tblcontacts');
        $CI->db->where('id', $contact_id);
        $contact = $CI->db->get()->row();

        if ($contact && !empty($contact->profile_image)) {
            $path = 'uploads/client_profile_images/' . $contact_id . '/' . $type . '_' . $contact->profile_image;
            $CI->object_cache->set('contact-profile-image-path-' . $contact_id, $path);
        }
    }

    if ($path && file_exists($path)) {
        $url = base_url($path);
    }

    return $url;
}
/**
 * Used in:
 * Search contact tickets
 * Project dropdown quick switch
 * Calendar tooltips
 * @param  [type] $userid [description]
 * @return [type]         [description]
 */
function get_company_name($userid, $prevent_empty_company = false)
{
    $_userid = get_client_user_id();
    if ($userid !== '') {
        $_userid = $userid;
    }
    $CI = & get_instance();

    $select = ($prevent_empty_company == false ? get_sql_select_client_company() : 'company');

    $client = $CI->db->select($select)
    ->where('userid', $_userid)
    ->from('tblclients')
    ->get()
    ->row();
    if ($client) {
        return $client->company;
    }

    return '';
}


/**
 * Get client default language
 * @param  mixed $clientid
 * @return mixed
 */
function get_client_default_language($clientid = '')
{
    if (!is_numeric($clientid)) {
        $clientid = get_client_user_id();
    }
    $CI = & get_instance();
    $CI->db->select('default_language');
    $CI->db->from('tblclients');
    $CI->db->where('userid', $clientid);
    $client = $CI->db->get()->row();
    if ($client) {
        return $client->default_language;
    }

    return '';
}

/**
 * Function is customer admin
 * @param  mixed  $id       customer id
 * @param  staff_id  $staff_id staff id to check
 * @return boolean
 */
function is_customer_admin($id, $staff_id = '')
{
    $staff_id = is_numeric($staff_id) ? $staff_id : get_staff_user_id();
    $CI       = &get_instance();
    $cache    = $CI->object_cache->get($id . '-is-customer-admin-' . $staff_id);

    if ($cache) {
        return $cache['retval'];
    }

    $total = total_rows('tblcustomeradmins', [
        'customer_id' => $id,
        'staff_id'    => $staff_id,
    ]);

    $retval = $total > 0 ? true : false;
    $CI->object_cache->add($id . '-is-customer-admin-' . $staff_id, ['retval' => $retval]);

    return $retval;
}
/**
 * Check if staff member have assigned customers
 * @param  mixed $staff_id staff id
 * @return boolean
 */
function have_assigned_customers($staff_id = '')
{
    $CI       = &get_instance();
    $staff_id = is_numeric($staff_id) ? $staff_id : get_staff_user_id();
    $cache    = $CI->object_cache->get('staff-total-assigned-customers-' . $staff_id);

    if (is_numeric($cache)) {
        $result = $cache;
    } else {
        $result = total_rows('tblcustomeradmins', [
            'staff_id' => $staff_id,
        ]);
        $CI->object_cache->add('staff-total-assigned-customers-' . $staff_id, $result);
    }

    return $result > 0 ? true : false;
}
/**
 * Check if contact has permission
 * @param  string  $permission permission name
 * @param  string  $contact_id     contact id
 * @return boolean
 */
function has_contact_permission($permission, $contact_id = '')
{
    $CI = & get_instance();
    if (!class_exists('app')) {
        $CI->load->library('app');
    }
    $permissions = get_contact_permissions();
    // Contact id passed form function
    if ($contact_id != '') {
        $_contact_id = $contact_id;
    } else {
        // Current logged in contact
        $_contact_id = get_contact_user_id();
    }
    foreach ($permissions as $_permission) {
        if ($_permission['short_name'] == $permission) {
            return total_rows('tblcontactpermissions', [
            'permission_id' => $_permission['id'],
            'userid'        => $_contact_id,
        ]) > 0;
        }
    }

    return false;
}
/**
 * Load customers area language
 * @param  string $customer_id
 * @return string return loaded language
 */
function load_client_language($customer_id = '')
{
    $CI       = & get_instance();
    $language = get_option('active_language');
    if (is_client_logged_in() || $customer_id != '') {
        $client_language = get_client_default_language($customer_id);
        if (!empty($client_language)) {
            if (file_exists(APPPATH . 'language/' . $client_language)) {
                $language = $client_language;
            }
        }
    }

    $CI->lang->load($language . '_lang', $language);
    if (file_exists(APPPATH . 'language/' . $language . '/custom_lang.php')) {
        $CI->lang->load('custom_lang', $language);
    }

    $language = do_action('after_load_client_language', $language);

    return $language;
}
/**
 * Check if client have transactions recorded
 * @param  mixed $id clientid
 * @return boolean
 */
function client_have_transactions($id)
{
    $total_transactions = 0;

    $total_transactions += total_rows('tblinvoices', [
        'clientid' => $id,
    ]);

    $total_transactions += total_rows('tblcreditnotes', [
        'clientid' => $id,
    ]);

    $total_transactions += total_rows('tblestimates', [
        'clientid' => $id,
    ]);

    $total_transactions += total_rows('tblexpenses', [
        'clientid' => $id,
        'billable' => 1,
    ]);

    $total_transactions += total_rows('tblproposals', [
        'rel_id'   => $id,
        'rel_type' => 'customer',
    ]);

    if ($total_transactions > 0) {
        return true;
    }

    return false;
}


/**
* Predefined contact permission
* @return array
*/
function get_contact_permissions()
{
    $permissions = [
        [
            'id'         => 1,
            'name'       => _l('customer_permission_invoice'),
            'short_name' => 'invoices',
        ],
        [
            'id'         => 2,
            'name'       => _l('customer_permission_estimate'),
            'short_name' => 'estimates',
        ],
        [
            'id'         => 3,
            'name'       => _l('customer_permission_contract'),
            'short_name' => 'contracts',
        ],
        [
            'id'         => 4,
            'name'       => _l('customer_permission_proposal'),
            'short_name' => 'proposals',
        ],
        [
            'id'         => 5,
            'name'       => _l('customer_permission_support'),
            'short_name' => 'support',
        ],
        [
            'id'         => 6,
            'name'       => _l('customer_permission_projects'),
            'short_name' => 'projects',
        ],
    ];

    return do_action('get_contact_permissions', $permissions);
}

/**
 * Additional checking for customers area, when contact edit his profile
 * This function will check if the checkboxes for email notifications should be shown
 * @return boolean
 */
function can_contact_view_email_notifications_options()
{
    if (has_contact_permission('invoices') || has_contact_permission('estimates') || has_contact_permission('projects') || has_contact_permission('contracts')) {
        return true;
    }

    return false;
}

/**
* With this function staff can login as client in the clients area
* @param  mixed $id client id
*/
function login_as_client($id)
{
    $CI = &get_instance();

    $CI->db->select('tblcontacts.id')
    ->where('userid', $id)
    ->where('is_primary', 1);

    $primary = $CI->db->get('tblcontacts')->row();

    if (!$primary) {
        set_alert('danger', _l('no_primary_contact'));
        redirect($_SERVER['HTTP_REFERER']);
    }

    $user_data = [
        'client_user_id'      => $id,
        'contact_user_id'     => $primary->id,
        'client_logged_in'    => true,
        'logged_in_as_client' => true,
    ];

    $CI->session->set_userdata($user_data);
}

function send_customer_registered_email_to_administrators($client_id)
{
    $CI = &get_instance();
    $CI->load->model('staff_model');
    $admins = $CI->staff_model->get('', ['active' => 1, 'admin' => 1]);

    $CI->load->model('emails_model');
    foreach ($admins as $admin) {
        $merge_fields = get_client_contact_merge_fields($client_id, get_primary_contact_user_id($client_id));
        $CI->emails_model->send_email_template('new-client-registered-to-admin', $admin['email'], $merge_fields);
    }
}

/**
 * Return and perform additional checkings for contact consent url
 * @param  mixed $contact_id contact id
 * @return string
 */
function contact_consent_url($contact_id)
{
    $CI = &get_instance();

    $consent_key = get_contact_meta($contact_id, 'consent_key');

    if (empty($consent_key)) {
        $consent_key = app_generate_hash() . '-' . app_generate_hash();
        $meta_id     = false;
        if (total_rows('tblcontacts', ['id' => $contact_id]) > 0) {
            $meta_id = add_contact_meta($contact_id, 'consent_key', $consent_key);
        }
        if (!$meta_id) {
            return '';
        }
    }

    return site_url('consent/contact/' . $consent_key);
}

function export_contact_data($contact_id)
{
    define('GDPR_EXPORT', true);
    @ini_set('memory_limit', '256M');
    @ini_set('max_execution_time', 360);

    $CI = &get_instance();

    // $lead = $CI->leads_model->get($id);
    $CI->load->library('zip');

    $tmpDir     = get_temp_dir();
    $valAllowed = get_option('gdpr_contact_data_portability_allowed');
    if (empty($valAllowed)) {
        $valAllowed = [];
    } else {
        $valAllowed = unserialize($valAllowed);
    }

    $json = [];

    $contactFields = $CI->db->list_fields('tblcontacts');

    if ($passwordKey = array_search('password', $contactFields)) {
        unset($contactFields[$passwordKey]);
    }


    $CI->db->select(implode(',', $contactFields));
    $CI->db->where('id', $contact_id);
    $contact = $CI->db->get('tblcontacts')->row_array();
    $slug    = slug_it($contact['firstname'] . ' ' . $contact['lastname']);

    $isIndividual = is_empty_customer_company($contact['userid']);
    $json         = [];

    $CI->db->where('show_on_client_portal', 1)
        ->where('fieldto', 'contacts')
        ->order_by('field_order', 'asc');

    $contactsCustomFields = $CI->db->get('tblcustomfields')->result_array();

    if (in_array('profile_data', $valAllowed)) {
        $contact['additional_fields'] = [];

        foreach ($contactsCustomFields as $field) {
            $contact['additional_fields'][] = [
                'name'  => $field['name'],
                'value' => get_custom_field_value($contact['id'], $field['id'], 'contacts'),
            ];
        }

        $json = $contact;
    }

    if (in_array('consent', $valAllowed)) {
        $CI->load->model('gdpr_model');
        $json['consent'] = $CI->gdpr_model->get_consents(['contact_id' => $contact['id']]);
    }

    if (in_array('customer_profile_data', $valAllowed)
        && $contact['is_primary'] == '1'
        && !$isIndividual) {
        $CI->db->where('userid', $contact['userid']);
        $customer = $CI->db->get('tblclients')->row_array();

        $customer['country']          = get_country($customer['country']);
        $customer['billing_country']  = get_country($customer['billing_country']);
        $customer['shipping_country'] = get_country($customer['shipping_country']);

        $CI->db->where('show_on_client_portal', 1)
              ->where('fieldto', 'customers')
              ->order_by('field_order', 'asc');

        $custom_fields                 = $CI->db->get('tblcustomfields')->result_array();
        $customer['additional_fields'] = [];

        $groups    = $CI->clients_model->get_customer_groups($customer['userid']);
        $groupsIds = [];
        foreach ($groups as $group) {
            $groupsIds[] = $group['groupid'];
        }

        $groupNames = [];
        if (count($groupsIds) > 0) {
            $CI->db->where('id IN (' . implode(', ', $groupsIds) . ')');
            $groups = $CI->db->get('tblcustomersgroups')->result_array();
            foreach ($groups as $group) {
                $groupNames[] = $group['name'];
            }
        }

        $customer['groups'] = $groupNames;

        foreach ($custom_fields as $field) {
            $customer['additional_fields'][] = [
                'name'  => $field['name'],
                'value' => get_custom_field_value($customer['userid'], $field['id'], 'customers'),
            ];
        }

        $json['company'] = $customer;
    }

    // Notes
    if (in_array('profile_notes', $valAllowed) && $contact['is_primary'] == '1') {
        $CI->db->where('rel_id', $contact['userid']);
        $CI->db->where('rel_type', 'customer');
        $json['notes'] = $CI->db->get('tblnotes')->result_array();
    }

    // Contacts
    if (in_array('contacts', $valAllowed) && $contact['is_primary'] == '1' && !$isIndividual) {
        $CI->db->where('id !=', $contact['id']);
        $CI->db->where('userid', $contact['userid']);
        $otherContacts = $CI->db->get('tblcontacts')->result_array();

        foreach ($otherContacts as $keyContact => $otherContact) {
            $otherContacts[$keyContact]['additional_fields'] = [];

            foreach ($contactsCustomFields as $field) {
                $otherContacts[$keyContact]['additional_fields'][] = [
                    'name'  => $field['name'],
                    'value' => get_custom_field_value($otherContact['id'], $field['id'], 'contacts'),
                ];
            }
        }
    }

    // Invoices
    if (in_array('invoices', $valAllowed) && $contact['is_primary'] == '1') {
        $json['invoices'] = prepare_invoices_for_export($contact['userid']);
    }

    // Credit Notes
    if (in_array('credit_notes', $valAllowed) && $contact['is_primary'] == '1') {
        $json['credit_notes'] = prepare_credit_notes_for_export($contact['userid']);
    }
    // Credit Notes
    if (in_array('estimates', $valAllowed) && $contact['is_primary'] == '1') {
        $json['estimates'] = prepare_estimates_for_export($contact['userid']);
    }

    // Proposals
    if (in_array('proposals', $valAllowed) && $contact['is_primary'] == '1') {
        $json['proposals'] = prepare_proposals_for_export($contact['userid'], 'customer');
    }

    // Subscriptions
    if (in_array('subscriptions', $valAllowed) && $contact['is_primary'] == '1') {
        $json['subscriptions'] = prepare_subscsriptions_for_export($contact['userid']);
    }

    // Expenses
    if (in_array('expenses', $valAllowed) && $contact['is_primary'] == '1') {
        $json['expenses'] = prepare_expenses_for_export($contact['userid']);
    }

    // Contracts
    if (in_array('contracts', $valAllowed) && $contact['is_primary'] == '1') {
        $json['contracts'] = prepare_contracts_for_export($contact['userid']);
    }

    // Tickets
    if (in_array('tickets', $valAllowed)) {
        $json['tickets'] = prepare_tickets_for_export($contact['id']);
    }

    // Projects
    if (in_array('projects', $valAllowed) && $contact['is_primary'] == '1') {
        $json['projects'] = prepare_projects_for_export($contact['userid'], $contact['id']);
    }

    $tmpDirContactData = $tmpDir . '/' . $contact['id'] . time() . '-contact';
    mkdir($tmpDirContactData, 0755);

    $fp = fopen($tmpDirContactData . '/data.json', 'w');
    fwrite($fp, json_encode($json, JSON_PRETTY_PRINT));
    fclose($fp);

    $CI->zip->read_file($tmpDirContactData . '/data.json');

    if (is_dir($tmpDirContactData)) {
        @delete_dir($tmpDirContactData);
    }

    $CI->zip->download($slug . '-data.zip');

    /*header('Content-type:application/json');
    echo json_encode($json, JSON_PRETTY_PRINT);
    die;*/
}

/**
*  Get customer attachment
* @param   mixed $id   customer id
* @return  array
*/
function get_all_customer_attachments($id)
{
    $CI = &get_instance();

    $attachments                = [];
    $attachments['invoice']     = [];
    $attachments['estimate']    = [];
    $attachments['credit_note'] = [];
    $attachments['proposal']    = [];
    $attachments['contract']    = [];
    $attachments['lead']        = [];
    $attachments['task']        = [];
    $attachments['customer']    = [];
    $attachments['ticket']      = [];
    $attachments['expense']     = [];

    $has_permission_expenses_view = has_permission('expenses', '', 'view');
    $has_permission_expenses_own  = has_permission('expenses', '', 'view_own');
    if ($has_permission_expenses_view || $has_permission_expenses_own) {
        // Expenses
        $CI->db->select('clientid,id');
        $CI->db->where('clientid', $id);
        if (!$has_permission_expenses_view) {
            $CI->db->where('addedfrom', get_staff_user_id());
        }

        $CI->db->from('tblexpenses');
        $expenses = $CI->db->get()->result_array();
        $ids      = array_column($expenses, 'id');
        if (count($ids) > 0) {
            $CI->db->where_in('rel_id', $ids);
            $CI->db->where('rel_type', 'expense');
            $_attachments = $CI->db->get('tblfiles')->result_array();
            foreach ($_attachments as $_att) {
                array_push($attachments['expense'], $_att);
            }
        }
    }


    $has_permission_invoices_view = has_permission('invoices', '', 'view');
    $has_permission_invoices_own  = has_permission('invoices', '', 'view_own');
    if ($has_permission_invoices_view || $has_permission_invoices_own) {
        // Invoices
        $CI->db->select('clientid,id');
        $CI->db->where('clientid', $id);

        if (!$has_permission_invoices_view) {
            $CI->db->where('addedfrom', get_staff_user_id());
        }

        $CI->db->from('tblinvoices');
        $invoices = $CI->db->get()->result_array();

        $ids = array_column($invoices, 'id');
        if (count($ids) > 0) {
            $CI->db->where_in('rel_id', $ids);
            $CI->db->where('rel_type', 'invoice');
            $_attachments = $CI->db->get('tblfiles')->result_array();
            foreach ($_attachments as $_att) {
                array_push($attachments['invoice'], $_att);
            }
        }
    }

    $has_permission_credit_notes_view = has_permission('credit_notes', '', 'view');
    $has_permission_credit_notes_own  = has_permission('credit_notes', '', 'view_own');

    if ($has_permission_credit_notes_view || $has_permission_credit_notes_own) {
        // credit_notes
        $CI->db->select('clientid,id');
        $CI->db->where('clientid', $id);

        if (!$has_permission_credit_notes_view) {
            $CI->db->where('addedfrom', get_staff_user_id());
        }

        $CI->db->from('tblcreditnotes');
        $credit_notes = $CI->db->get()->result_array();

        $ids = array_column($credit_notes, 'id');
        if (count($ids) > 0) {
            $CI->db->where_in('rel_id', $ids);
            $CI->db->where('rel_type', 'credit_note');
            $_attachments = $CI->db->get('tblfiles')->result_array();
            foreach ($_attachments as $_att) {
                array_push($attachments['credit_note'], $_att);
            }
        }
    }

    $permission_estimates_view = has_permission('estimates', '', 'view');
    $permission_estimates_own  = has_permission('estimates', '', 'view_own');

    if ($permission_estimates_view || $permission_estimates_own) {
        // Estimates
        $CI->db->select('clientid,id');
        $CI->db->where('clientid', $id);
        if (!$permission_estimates_view) {
            $CI->db->where('addedfrom', get_staff_user_id());
        }
        $CI->db->from('tblestimates');
        $estimates = $CI->db->get()->result_array();

        $ids = array_column($estimates, 'id');
        if (count($ids) > 0) {
            $CI->db->where_in('rel_id', $ids);
            $CI->db->where('rel_type', 'estimate');
            $_attachments = $CI->db->get('tblfiles')->result_array();

            foreach ($_attachments as $_att) {
                array_push($attachments['estimate'], $_att);
            }
        }
    }

    $has_permission_proposals_view = has_permission('proposals', '', 'view');
    $has_permission_proposals_own  = has_permission('proposals', '', 'view_own');

    if ($has_permission_proposals_view || $has_permission_proposals_own) {
        // Proposals
        $CI->db->select('rel_id,id');
        $CI->db->where('rel_id', $id);
        $CI->db->where('rel_type', 'customer');
        if (!$has_permission_proposals_view) {
            $CI->db->where('addedfrom', get_staff_user_id());
        }
        $CI->db->from('tblproposals');
        $proposals = $CI->db->get()->result_array();

        $ids = array_column($proposals, 'id');

        if (count($ids) > 0) {
            $CI->db->where_in('rel_id', $ids);
            $CI->db->where('rel_type', 'proposal');
            $_attachments = $CI->db->get('tblfiles')->result_array();

            foreach ($_attachments as $_att) {
                array_push($attachments['proposal'], $_att);
            }
        }
    }

    $permission_contracts_view = has_permission('contracts', '', 'view');
    $permission_contracts_own  = has_permission('contracts', '', 'view_own');
    if ($permission_contracts_view || $permission_contracts_own) {
        // Contracts
        $CI->db->select('client,id');
        $CI->db->where('client', $id);
        if (!$permission_contracts_view) {
            $CI->db->where('addedfrom', get_staff_user_id());
        }
        $CI->db->from('tblcontracts');
        $contracts = $CI->db->get()->result_array();

        $ids = array_column($contracts, 'id');

        if (count($ids) > 0) {
            $CI->db->where_in('rel_id', $ids);
            $CI->db->where('rel_type', 'contract');
            $_attachments = $CI->db->get('tblfiles')->result_array();

            foreach ($_attachments as $_att) {
                array_push($attachments['contract'], $_att);
            }
        }
    }

    $CI->db->select('leadid')
    ->where('userid', $id);
    $customer = $CI->db->get('tblclients')->row();

    if ($customer->leadid != null) {
        $CI->db->where('rel_id', $customer->leadid);
        $CI->db->where('rel_type', 'lead');
        $_attachments = $CI->db->get('tblfiles')->result_array();
        foreach ($_attachments as $_att) {
            array_push($attachments['lead'], $_att);
        }
    }

    $CI->db->select('ticketid,userid');
    $CI->db->where('userid', $id);
    $CI->db->from('tbltickets');
    $tickets = $CI->db->get()->result_array();

    $ids = array_column($tickets, 'ticketid');

    if (count($ids) > 0) {
        $CI->db->where_in('ticketid', $ids);
        $_attachments = $CI->db->get('tblticketattachments')->result_array();

        foreach ($_attachments as $_att) {
            array_push($attachments['ticket'], $_att);
        }
    }

    $has_permission_tasks_view = has_permission('tasks', '', 'view');
    $CI->db->select('rel_id, id');
    $CI->db->where('rel_id', $id);
    $CI->db->where('rel_type', 'customer');

    if (!$has_permission_tasks_view) {
        $CI->db->where(get_tasks_where_string(false));
    }

    $CI->db->from('tblstafftasks');
    $tasks = $CI->db->get()->result_array();

    $ids = array_column($tasks, 'ticketid');
    if (count($ids) > 0) {
        $CI->db->where_in('rel_id', $ids);
        $CI->db->where('rel_type', 'task');

        $_attachments = $CI->db->get('tblfiles')->result_array();

        foreach ($_attachments as $_att) {
            array_push($attachments['task'], $_att);
        }
    }

    $CI->db->where('rel_id', $id);
    $CI->db->where('rel_type', 'customer');
    $client_main_attachments = $CI->db->get('tblfiles')->result_array();

    $attachments['customer'] = $client_main_attachments;

    return $attachments;
}



add_action('check_vault_entries_visibility', '_check_vault_entries_visibility');

/**
 * Used in customer profile vaults feature to determine if the vault should be shown for staff
 * @param  array $entries vault entries from database
 * @return array
 */
function _check_vault_entries_visibility($entries)
{
    $new = [];
    foreach ($entries as $entry) {
        if ($entry['visibility'] != 1) {
            if ($entry['visibility'] == 2 && !is_admin() && $entry['creator'] != get_staff_user_id()) {
                continue;
            } elseif ($entry['visibility'] == 3 && $entry['creator'] != get_staff_user_id() && !is_admin()) {
                continue;
            }
        }
        $new[] = $entry;
    }
    if (count($new) == 0) {
        $new = -1;
    }

    return $new;
}

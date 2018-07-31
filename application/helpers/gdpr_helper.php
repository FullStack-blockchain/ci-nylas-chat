<?php

defined('BASEPATH') or exit('No direct script access allowed');

function send_gdpr_email_template($template, $user_id, $for)
{
    $CI = &get_instance();

    $CI->load->model('emails_model');
    $CI->load->model('staff_model');

    $staff = $CI->staff_model->get('', ['active' => 1, 'admin' => 1]);

    $other_merge_fields = [];
    if($for == 'contact') {
        $other_merge_fields = get_client_contact_merge_fields(get_user_id_by_contact_id($user_id), $user_id);
    } else if($for == 'lead') {
        $other_merge_fields = get_lead_merge_fields($user_id);
    }
    foreach ($staff as $member) {
        $merge_fields = [];
        $merge_fields = array_merge($merge_fields, $other_merge_fields);
        $merge_fields = array_merge($merge_fields, get_staff_merge_fields($member['staffid']));

        $CI->emails_model->send_email_template($template, $member['email'], $merge_fields);
    }
}

function is_gdpr(){
    return get_option('enable_gdpr') === '1';
}

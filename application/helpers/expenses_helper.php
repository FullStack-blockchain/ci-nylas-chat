<?php

function prepare_expenses_for_export($customer_id)
{
    $CI = &get_instance();

    if (!class_exists('expenses_model')) {
        $CI->load->model('expenses_model');
    }

    $CI->db->where('clientid', $customer_id);
    $expenses = $CI->db->get('tblexpenses')->result_array();

    $CI->db->where('fieldto', 'expenses');
    $CI->db->order_by('field_order', 'asc');
    $custom_fields = $CI->db->get('tblcustomfields')->result_array();

    $CI->load->model('currencies_model');
    foreach ($expenses as $expensesKey => $expense) {

        $expenses[$expensesKey]['currency'] = $CI->currencies_model->get($expense['currency']);
        $expenses[$expensesKey]['category'] = $CI->expenses_model->get_category($expense['category']);
        $expenses[$expensesKey]['tax']      = get_tax_by_id($expense['tax']);
        $expenses[$expensesKey]['tax2']     = get_tax_by_id($expense['tax2']);

        $expenses[$expensesKey]['additional_fields'] = [];

        foreach ($custom_fields as $cf) {
            $expenses[$expensesKey]['additional_fields'][] = [
                    'name'  => $cf['name'],
                    'value' => get_custom_field_value($expense['id'], $cf['id'], 'expenses'),
                ];
        }
    }

    return $expenses;
}

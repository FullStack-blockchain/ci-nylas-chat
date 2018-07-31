<?php

defined('BASEPATH') or exit('No direct script access allowed');
if (is_numeric($id)) {
    $aColumns = [
        'email',
        'dateadded',
        ];
    if (count($data['custom_fields']) > 0) {
        foreach ($data['custom_fields'] as $field) {
            array_push($aColumns, '(SELECT value FROM tblmaillistscustomfieldvalues LEFT JOIN tblmaillistscustomfields ON tblmaillistscustomfields.customfieldid = ' . $field['customfieldid'] . ' WHERE tblmaillistscustomfieldvalues.customfieldid ="' . $field['customfieldid'] . '" AND (tblmaillistscustomfieldvalues.emailid = tbllistemails.emailid))');
        }
    }
    $sIndexColumn = 'emailid';
    $sTable       = 'tbllistemails';
    $result       = data_tables_init($aColumns, $sIndexColumn, $sTable, [], [
        'WHERE listid =' . $id,
        ], [
        'emailid',
        ]);
    $output  = $result['output'];
    $rResult = $result['rResult'];
    foreach ($rResult as $aRow) {
        $row = [];
        for ($i = 0; $i < count($aColumns); $i++) {
            $_data = $aRow[$aColumns[$i]];
            if ($aColumns[$i] == 'dateadded') {
                $_data = _dt($_data);
            }
            $row[] = $_data;
        }
        if (has_permission('surveys', '', 'delete')) {
            $row[] = icon_btn('surveys/delete_mail_list/' . $aRow['emailid'], 'remove', 'btn-danger', [
            'onclick' => 'remove_email_from_mail_list(this,' . $aRow['emailid'] . '); return false;',
            ]);
        } else {
            $row[] = '';
        }
        $output['aaData'][] = $row;
    }
} elseif ($id == 'clients' || $id == 'staff' || $id == 'leads') {
    $aColumns = [
        'email',
        ];

    if ($id == 'clients') {
        array_push($aColumns, 'firstname');
        array_push($aColumns, 'lastname');
        array_push($aColumns, 'CONCAT(firstname, " ", lastname)');
        array_push($aColumns, '(SELECT company FROM tblclients WHERE userid=tblcontacts.userid)');
    } elseif ($id == 'leads') {
        array_push($aColumns, 'name');
        array_push($aColumns, 'company');
    } elseif ($id == 'staff') {
        array_push($aColumns, 'CONCAT(firstname, " ", lastname)');
    }

    $sIndexColumn = 'id';
    if ($id == 'staff') {
        $sIndexColumn = 'staffid';
        $sTable       = 'tblstaff';
        array_push($aColumns, 'datecreated');
    } elseif ($id == 'leads') {
        array_push($aColumns, 'dateadded');
        $sTable = 'tblleads';
    } else {
        $sTable = 'tblcontacts';
        array_push($aColumns, 'datecreated');
    }

    $where = [];
    if ($id == 'leads') {
        if ($this->ci->input->post('custom_view')) {
            $filter = $this->ci->input->post('custom_view');
            if ($filter == 'lost') {
                array_push($where, 'AND lost = 1');
            } elseif ($filter == 'contacted_today') {
                array_push($where, 'AND lastcontact LIKE "' . date('Y-m-d') . '%"');
            } elseif ($filter == 'created_today') {
                array_push($where, 'AND dateadded LIKE "' . date('Y-m-d') . '%"');
            } elseif (_startsWith($filter, 'consent_')) {
                array_push($where, 'AND tblleads.id IN (SELECT lead_id FROM tblconsents WHERE purpose_id=' . strafter($filter, 'consent_') . ' and action="opt-in" AND date IN (SELECT MAX(date) FROM tblconsents WHERE purpose_id=' . strafter($filter, 'consent_') . ' AND lead_id=tblleads.id))');
            }
        }

        if ($this->ci->input->post('status')) {
            $by_assigned = $this->ci->input->post('status');
            array_push($where, 'AND status =' . $by_assigned);
        }

        if ($this->ci->input->post('source')) {
            $by_assigned = $this->ci->input->post('source');
            array_push($where, 'AND source =' . $by_assigned);
        }
        array_push($where, ' AND junk = 0');
    } elseif ($id == 'clients') {
        if ($this->ci->input->post('customer_groups')) {
            $groups = $this->ci->input->post('customer_groups');
            array_push($where, ' AND userid IN (SELECT customer_id FROM tblcustomergroups_in WHERE groupid IN (' . implode(',', $groups) . '))');
        }
        if ($this->ci->input->post('consent')) {
            array_push($where, ' AND tblcontacts.id IN (SELECT contact_id FROM tblconsents WHERE purpose_id=' . $this->ci->input->post('consent') . ' and action="opt-in" AND date IN (SELECT MAX(date) FROM tblconsents WHERE purpose_id=' . $this->ci->input->post('consent') . ' AND contact_id=tblcontacts.id))');
        }
        if ($this->ci->input->post('active_customers_filter')) {
            $active_customers_filter = $this->ci->input->post('active_customers_filter');
            if ($active_customers_filter == 'active_contacts') {
                array_push($where, ' AND tblcontacts.active=1');
            } else {
                array_push($where, ' AND userid IN(SELECT userid FROM tblclients WHERE active=1 AND tblclients.userid=tblcontacts.userid)');
            }
        }
    }

    $result  = data_tables_init($aColumns, $sIndexColumn, $sTable, [], $where);
    $output  = $result['output'];
    $rResult = $result['rResult'];
    foreach ($rResult as $aRow) {
        $row = [];
        for ($i = 0; $i < count($aColumns); $i++) {
            $_data = $aRow[$aColumns[$i]];
            if ($aColumns[$i] == 'datecreated' || $aColumns[$i] == 'dateadded') {
                $_data = _dt($_data);
            }
            // No delete option
            $row[] = $_data;
        }
        $row[]              = '';
        $output['aaData'][] = $row;
    }
}

<?php

defined('BASEPATH') or exit('No direct script access allowed');
$aColumns = [
    'name',
    ];
$sIndexColumn = 'id';
$sTable       = 'tblcontracttypes';

$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, [], [], ['id']);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] == 'name') {
            $_data = '<a href="#" onclick="edit_type(this,' . $aRow['id'] . '); return false;" data-name="' . $aRow['name'] . '">' . $_data . '</a> ' . '<span class="badge badge-pill badge-secondary pull-right">' . total_rows('tblcontracts', ['contract_type' => $aRow['id']]) . '</span>';
        }
        $row[] = $_data;
    }

    $options = icon_btn('contracts/delete_contract_type/' . $aRow['id'], 'remove', 'btn-default pull-right btn-danger-delete _delete');

    $row[]   = $options .= icon_btn('#', 'pencil-square-o', 'btn-default pull-right', ['onclick' => 'edit_type(this,' . $aRow['id'] . '); return false;', 'data-name' => $aRow['name']]);

    $output['aaData'][] = $row;
}

<?php

defined('BASEPATH') or exit('No direct script access allowed');
$aColumns = [
    'name',
    'symbol',
    ];
$sIndexColumn = 'id';
$sTable       = 'tblcurrencies';
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable, [], [], [
    'id',
    'isdefault',
    ]);
$output  = $result['output'];
$rResult = $result['rResult'];
foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] == 'name') {
            $_data = '<span class="name"><a href="#" data-toggle="modal" data-target="#currency_modal" data-id="' . $aRow['id'] . '">' . $_data . '</a></span>';
            if ($aRow['isdefault'] == 1) {
                $_data .= '<span class="display-block text-info">' . _l('base_currency_string') . '</span>';
            }
        }
        $row[] = $_data;
    }
    $options = icon_btn('#' . $aRow['id'], 'pencil-square-o', 'btn-default', [
        'data-toggle' => 'modal',
        'data-target' => '#currency_modal',
        'data-id'     => $aRow['id'],
        ]);
    if ($aRow['isdefault'] == 0) {
        $options .= icon_btn('currencies/make_base_currency/' . $aRow['id'], 'star', 'btn-info', [
            'data-toggle' => 'tooltip',
            'title'       => _l('make_base_currency'),
            ]);
    }
    $row[]              = $options .= icon_btn('currencies/delete/' . $aRow['id'], 'remove', 'btn-danger _delete');
    $output['aaData'][] = $row;
}

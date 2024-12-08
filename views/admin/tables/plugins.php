<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'name',
    'description',
    'price',
    'id'
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'uplicrm_website_builder_plugins';

$result = data_tables_init($aColumns, $sIndexColumn, $sTable);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    $row[] = $aRow['name'];
    $row[] = $aRow['description'];
    $row[] = $aRow['price'];

    $options = icon_btn('uplicrm_website_builder/plugin/' . $aRow['id'], 'pencil-square-o');
    $options .= icon_btn('uplicrm_website_builder/delete_plugin/' . $aRow['id'], 'remove', 'btn-danger _delete');
    $row[]   = $options;

    $output['aaData'][] = $row;
}
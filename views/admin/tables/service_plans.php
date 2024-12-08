<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'name',
    'description',
    'price',
    'type',
    'id'
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'uplicrm_website_builder_service_plans';

$result = data_tables_init($aColumns, $sIndexColumn, $sTable);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    $row[] = $aRow['name'];
    $row[] = $aRow['description'];
    $row[] = $aRow['price'];
    $row[] = $aRow['type'];

    $options = icon_btn('uplicrm_website_builder/service_plan/' . $aRow['id'], 'pencil-square-o');
    $options .= icon_btn('uplicrm_website_builder/delete_service_plan/' . $aRow['id'], 'remove', 'btn-danger _delete');
    $row[]   = $options;

    $output['aaData'][] = $row;
}
<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'image',
    'name',
    'slug',
    'description',
    'status',
    'id'
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'uplicrm_website_builder_categories';

$result = data_tables_init($aColumns, $sIndexColumn, $sTable);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    $row[] = '<img src="' . base_url('uploads/uplicrm/categories/' . $aRow['image']) . '" width="50" height="50" alt="' . $aRow['name'] . '">';
    $row[] = $aRow['name'];
    $row[] = $aRow['slug'];
    $row[] = $aRow['description'];
    $row[] = ($aRow['status'] == 'active') ? _l('active') : _l('inactive');

    $options = icon_btn('uplicrm_website_builder/category/' . $aRow['id'], 'fa-regular fa-pen-to-square', 'btn-default');
    $options .= icon_btn('uplicrm_website_builder/delete_category/' . $aRow['id'], 'fa fa-remove', 'btn-danger _delete');
    $row[]   = $options;

    $output['aaData'][] = $row;
}
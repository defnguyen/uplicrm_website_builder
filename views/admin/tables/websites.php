<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    db_prefix() . 'uplicrm_website_builder_websites.name as website_name',
    'subdomain',
    db_prefix() . 'uplicrm_website_builder_service_plans.name as plan_name',
    db_prefix() . 'uplicrm_website_builder_websites.status as website_status',
    db_prefix() . 'uplicrm_website_builder_websites.created_at as website_created_at',
    db_prefix() . 'uplicrm_website_builder_websites.id as id'
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'uplicrm_website_builder_websites';

$join = [
    'LEFT JOIN ' . db_prefix() . 'uplicrm_website_builder_service_plans ON ' . db_prefix() . 'uplicrm_website_builder_service_plans.id = ' . db_prefix() . 'uplicrm_website_builder_websites.plan_id'
];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    $row[] = $aRow['website_name'];
    $row[] = $aRow['subdomain'];
    $row[] = $aRow['plan_name'];
    $row[] = ($aRow['website_status'] == 'active') ? _l('active') : _l('inactive');
    $row[] = _dt($aRow['website_created_at']);

    $options = icon_btn('uplicrm_website_builder/website/' . $aRow['id'], 'pencil-square-o');
    $options .= icon_btn('uplicrm_website_builder/delete_website/' . $aRow['id'], 'remove', 'btn-danger _delete');
    $row[]   = $options;

    $output['aaData'][] = $row;
}
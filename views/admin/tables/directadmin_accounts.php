<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    db_prefix() . 'uplicrm_directadmin_accounts.username as directadmin_accounts_username',
    db_prefix() . 'uplicrm_directadmin_accounts.user_id as directadmin_accounts_user_id',
    db_prefix() . 'uplicrm_directadmin_accounts.id as id'
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'uplicrm_directadmin_accounts';

$result = data_tables_init($aColumns, $sIndexColumn, $sTable);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    $row[] = $aRow['directadmin_accounts_username'];
    $row[] = get_staff_full_name($aRow['directadmin_accounts_user_id']);

    $options = icon_btn('uplicrm_website_builder/directadmin_account/' . $aRow['id'], 'pencil-square-o');
    $options .= icon_btn('uplicrm_website_builder/delete_directadmin_account/' . $aRow['id'], 'remove', 'btn-danger _delete');
    $row[]   = $options;

    $output['aaData'][] = $row;
}
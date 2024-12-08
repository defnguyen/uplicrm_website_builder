<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    db_prefix() . 'uplicrm_website_builder_templates.image as template_image',
    db_prefix() . 'uplicrm_website_builder_templates.name as template_name',  // Added alias for template name
    db_prefix() . 'uplicrm_website_builder_templates.slug as template_slug',    // Added alias for template slug
    db_prefix() . 'uplicrm_website_builder_templates.description as template_description',    // Added alias for template description
    db_prefix() . 'uplicrm_website_builder_templates.price as template_price',    // Added alias for template price
    db_prefix() . 'uplicrm_website_builder_templates.source_type as template_source_type',    // Added alias for template source type
    db_prefix() . 'uplicrm_website_builder_categories.name as category_name',
    db_prefix() . 'uplicrm_website_builder_templates.id as id',
    db_prefix() . 'uplicrm_website_builder_templates.created_at as created_at'
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'uplicrm_website_builder_templates';

$join = [
    'LEFT JOIN ' . db_prefix() . 'uplicrm_website_builder_categories ON ' . db_prefix() . 'uplicrm_website_builder_categories.id = ' . db_prefix() . 'uplicrm_website_builder_templates.category_id'
];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    $row[] = '<img src="' . base_url('uploads/uplicrm/templates/' . $aRow['template_image']) . '" width="50" height="50" alt="' . $aRow['template_name'] . '">';
    $row[] = $aRow['template_name'];
    $row[] = $aRow['category_name'];
    $row[] = $aRow['template_description'];
    $row[] = $aRow['template_price'];
    $row[] = $aRow['template_source_type'];
    $row[] = date('d/m/Y H:i:s', strtotime($aRow['created_at']));
    

    $options = icon_btn('uplicrm_website_builder/template/' . $aRow['id'], 'fa-regular fa-pen-to-square', 'btn-default');
    $options .= icon_btn('uplicrm_website_builder/delete_template/' . $aRow['id'], 'fa fa-remove', 'btn-danger _delete');
    $options .= '<a href="javascript:void(0)" onclick="startBuildWebsite(' . $aRow['id'] . '); return false;" class="btn btn-success btn-icon"><i class="fa fa-play"></i></a>';
    $row[]   = $options;

    $output['aaData'][] = $row;
}
?>
<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: UpliCRM Website Builder
Description: Quản lý mẫu website, tạo website và tự động hóa quy trình tạo website bằng API DirectAdmin.
Version: 1.0.0
Requires at least: 3.0.0
Author: UpliCRM
Author URI: https://uplicrm.com/
*/

define('UPLICRM_WEBSITE_BUILDER_MODULE_NAME', 'uplicrm_website_builder');

// Load helpers
$CI = &get_instance();
$CI->load->helper(UPLICRM_WEBSITE_BUILDER_MODULE_NAME . '/upli_website_builder');

// Hooks
hooks()->add_action('admin_init', 'uplicrm_website_builder_admin_init');
hooks()->add_action('clients_init', 'uplicrm_website_builder_client_init');
hooks()->add_filter('module_'.UPLICRM_WEBSITE_BUILDER_MODULE_NAME.'_action_links', 'module_uplicrm_website_builder_action_links');

/**
 * Register activation module hook
 */
register_activation_hook(UPLICRM_WEBSITE_BUILDER_MODULE_NAME, 'uplicrm_website_builder_activation_hook');

function uplicrm_website_builder_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}

/**
 * Register language files, must be registered if the module is using languages
 */
register_language_files(UPLICRM_WEBSITE_BUILDER_MODULE_NAME, [UPLICRM_WEBSITE_BUILDER_MODULE_NAME]);

/**
 * Init admin area
 */
function uplicrm_website_builder_admin_init()
{
    $CI = &get_instance();

    // Add module permissions
    $capabilities = [
        'capabilities' => [
            'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
            'create' => _l('permission_create'),
            'edit'   => _l('permission_edit'),
            'delete' => _l('permission_delete'),
        ],
    ];
    register_staff_capabilities(_l('website_builder'), $capabilities, UPLICRM_WEBSITE_BUILDER_MODULE_NAME);

    // Add menu item in setup menu
    if (has_permission('uplicrm_website_builder', '', 'view')) {
        $CI->app_menu->add_sidebar_menu_item('uplicrm-website-builder', [
            'name'     => _l('website_builder'),
            'position' => 16,
            'icon'     => 'fa fa-globe',
            'badge'    => [],
        ]);

        // Add sub-menu items
        $CI->app_menu->add_sidebar_children_item('uplicrm-website-builder', [
            'slug'     => 'uplicrm-website-builder-websites',
            'name'     => _l('websites'),
            'href'     => admin_url('uplicrm_website_builder/websites'),
            'position' => 5,
        ]);

        $CI->app_menu->add_sidebar_children_item('uplicrm-website-builder', [
            'slug'     => 'uplicrm-website-builder-categories',
            'name'     => _l('categories'),
            'href'     => admin_url('uplicrm_website_builder/categories'),
            'position' => 10,
        ]);

        $CI->app_menu->add_sidebar_children_item('uplicrm-website-builder', [
            'slug'     => 'uplicrm-website-builder-templates',
            'name'     => _l('templates'),
            'href'     => admin_url('uplicrm_website_builder/templates'),
            'position' => 15,
        ]);

        // $CI->app_menu->add_sidebar_children_item('uplicrm-website-builder', [
        //     'slug'     => 'uplicrm-website-builder-directadmin-accounts',
        //     'name'     => _l('directadmin_accounts'),
        //     'href'     => admin_url('uplicrm_website_builder/directadmin_accounts'),
        //     'position' => 20,
        // ]);

        $CI->app_menu->add_sidebar_children_item('uplicrm-website-builder', [
            'slug'     => 'uplicrm-website-builder-service-plans',
            'name'     => _l('service_plans'),
            'href'     => admin_url('uplicrm_website_builder/service_plans'),
            'position' => 25,
        ]);

        $CI->app_menu->add_sidebar_children_item('uplicrm-website-builder', [
            'slug'     => 'uplicrm-website-builder-plugins',
            'name'     => _l('plugins'),
            'href'     => admin_url('uplicrm_website_builder/plugins'),
            'position' => 30,
        ]);

        $CI->app_menu->add_sidebar_children_item('uplicrm-website-builder', [
            'slug'     => 'uplicrm-website-builder-logs',
            'name'     => _l('activity_logs'),
            'href'     => admin_url('uplicrm_website_builder/logs'),
            'position' => 35,
        ]);

        $CI->app_menu->add_sidebar_children_item('uplicrm-website-builder', [
            'slug'     => 'uplicrm-website-builder-settings',
            'name'     => _l('settings'),
            'href'     => admin_url('uplicrm_website_builder/settings'),
            'position' => 40, // Adjust position as needed
        ]);
    }
}

/**
 * Init client area
 */
function upli_website_builder_client_init()
{
    $CI = &get_instance();

    // Add client menu item
    $CI->app_menu->add_sidebar_menu_item('uplicrm-website-builder', [
        'name'     => _l('website_builder'),
        'href'     => site_url('uplicrm_website_builder_client'),
        'position' => 6,
        'icon'     => 'fa fa-globe',
    ]);

    // Add sub-menu items
    $CI->app_menu->add_sidebar_children_item('uplicrm-website-builder', [
        'slug'     => 'uplicrm-website-builder-templates-client',
        'name'     => _l('browse_templates'),
        'href'     => site_url('uplicrm_website_builder_client/templates'),
        'position' => 5,
    ]);

    $CI->app_menu->add_sidebar_children_item('uplicrm-website-builder', [
        'slug'     => 'uplicrm-website-builder-websites-client',
        'name'     => _l('my_websites'),
        'href'     => site_url('uplicrm_website_builder_client/websites'),
        'position' => 10,
    ]);

    $CI->app_menu->add_sidebar_children_item('uplicrm-website-builder', [
        'slug'     => 'uplicrm-website-builder-plugins-client',
        'name'     => _l('plugins'),
        'href'     => site_url('uplicrm_website_builder_client/plugins'),
        'position' => 15,
    ]);

    $CI->app_menu->add_sidebar_children_item('uplicrm-website-builder', [
        'slug'     => 'uplicrm-website-builder-logs-client',
        'name'     => _l('activity_logs'),
        'href'     => site_url('uplicrm_website_builder_client/logs'),
        'position' => 20,
    ]);
}

/**
 * Add additional settings for this module in the module list area
 * @param  array $actions current actions
 * @return array
 */
function module_uplicrm_website_builder_action_links($actions)
{
    $actions[] = '<a href="' . admin_url('uplicrm_website_builder/settings') . '">' . _l('settings') . '</a>';
    return $actions;
}
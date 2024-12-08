<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Install_uplicrm_website_builder extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();

        // Create categories table
        if (!$CI->db->table_exists(db_prefix() . 'uplicrm_website_builder_categories')) {
            $CI->db->query("CREATE TABLE `" . db_prefix() . "uplicrm_website_builder_categories` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `image` varchar(255) DEFAULT NULL,
                `name` varchar(255) NOT NULL,
                `slug` varchar(255) NOT NULL,
                `description` text DEFAULT NULL,
                `status` enum('active','inactive') NOT NULL DEFAULT 'active',
                `created_at` datetime NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        }

        // Create templates table
        if (!$CI->db->table_exists(db_prefix() . 'uplicrm_website_builder_templates')) {
            $CI->db->query("CREATE TABLE `" . db_prefix() . "uplicrm_website_builder_templates` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(255) NOT NULL,
                `slug` varchar(255) NOT NULL,
                `description` text DEFAULT NULL,
                `image` varchar(255) DEFAULT NULL,
                `price` decimal(15,2) NOT NULL,
                `source_code` varchar(255) NOT NULL,
                `source_type` varchar(50) NOT NULL,
                `file_config_path` varchar(255) DEFAULT NULL,
                `config_content` text DEFAULT NULL,
                `category_id` int(11) NOT NULL,
                `is_created` tinyint(1) NOT NULL DEFAULT 0,
                `created_at` datetime NOT NULL,
                PRIMARY KEY (`id`),
                FOREIGN KEY (`category_id`) REFERENCES `" . db_prefix() . "uplicrm_website_builder_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        }

        // Create service plans table
        if (!$CI->db->table_exists(db_prefix() . 'uplicrm_website_builder_service_plans')) {
            $CI->db->query("CREATE TABLE `" . db_prefix() . "uplicrm_website_builder_service_plans` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(255) NOT NULL,
                `description` text DEFAULT NULL,
                `price` decimal(15,2) NOT NULL,
                `type` enum('trial','non_trial') NOT NULL,
                `created_at` datetime NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        }

        // Create websites table
        if (!$CI->db->table_exists(db_prefix() . 'uplicrm_website_builder_websites')) {
            $CI->db->query("CREATE TABLE `" . db_prefix() . "uplicrm_website_builder_websites` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(255) NOT NULL,
                `user_id` int(11) NOT NULL,
                `template_id` int(11) DEFAULT NULL,
                `subdomain` varchar(255) NOT NULL,
                `plan_id` int(11) NOT NULL,
                `status` enum('active','inactive') NOT NULL DEFAULT 'active',
                `is_template` tinyint(1) NOT NULL DEFAULT 0,
                `created_at` datetime NOT NULL,
                PRIMARY KEY (`id`),
                FOREIGN KEY (`template_id`) REFERENCES `" . db_prefix() . "uplicrm_website_builder_templates` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
                FOREIGN KEY (`plan_id`) REFERENCES `" . db_prefix() . "uplicrm_website_builder_service_plans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        }

        // Create DirectAdmin accounts table
        if (!$CI->db->table_exists(db_prefix() . 'uplicrm_directadmin_accounts')) {
            $CI->db->query("CREATE TABLE `" . db_prefix() . "uplicrm_directadmin_accounts` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `username` varchar(255) NOT NULL,
                `password` varchar(255) NOT NULL,
                `user_id` int(11) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        }

        // Create plugins table
        if (!$CI->db->table_exists(db_prefix() . 'uplicrm_website_builder_plugins')) {
            $CI->db->query("CREATE TABLE `" . db_prefix() . "uplicrm_website_builder_plugins` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(255) NOT NULL,
                `description` text DEFAULT NULL,
                `price` decimal(15,2) NOT NULL,
                `source_code` varchar(255) NOT NULL,
                `created_at` datetime NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        }

        // Add module to activity log
        $CI->db->query("ALTER TABLE `" . db_prefix() . "activity_log` ADD `module` VARCHAR(50) NULL AFTER `description`;");

        // Add new settings in Perfex CRM Setup -> Settings -> Integration
        $CI->db->insert(db_prefix() . 'options', [
            'name' => 'uplicrm_main_domain',
            'value' => '',
            'autoload' => 0
        ]);
        $CI->db->insert(db_prefix() . 'options', [
            'name' => 'uplicrm_server_ip',
            'value' => '',
            'autoload' => 0
        ]);
        $CI->db->insert(db_prefix() . 'options', [
            'name' => 'uplicrm_directadmin_host',
            'value' => '',
            'autoload' => 0
        ]);
        $CI->db->insert(db_prefix() . 'options', [
            'name' => 'uplicrm_directadmin_port',
            'value' => '2222',
            'autoload' => 0
        ]);
        $CI->db->insert(db_prefix() . 'options', [
            'name' => 'uplicrm_directadmin_username',
            'value' => '',
            'autoload' => 0
        ]);
        $CI->db->insert(db_prefix() . 'options', [
            'name' => 'uplicrm_directadmin_password',
            'value' => '',
            'autoload' => 0
        ]);
        $CI->db->insert(db_prefix() . 'options', [
            'name' => 'allow_plan_selection_on_website_creation',
            'value' => '0',
            'autoload' => 0
        ]);
    }
}
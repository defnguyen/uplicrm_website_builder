<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Upli_website_builder_lib
{
    private $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('uplicrm_website_builder/uplicrm_website_builder_model');
        $this->CI->load->model('uplicrm_website_builder/directadmin_account_model');
        $this->CI->load->library('app_modules');
    }

    // --- Categories ---

    public function get_categories($id = false)
    {
        return $this->CI->uplicrm_website_builder_model->get_categories($id);
    }

    public function add_category($data)
    {
        return $this->CI->uplicrm_website_builder_model->add_category($data);
    }

    public function update_category($id, $data)
    {
        return $this->CI->uplicrm_website_builder_model->update_category($id, $data);
    }

    public function delete_category($id)
    {
        return $this->CI->uplicrm_website_builder_model->delete_category($id);
    }

    // --- Templates ---

    public function get_templates($id = false)
    {
        return $this->CI->uplicrm_website_builder_model->get_templates($id);
    }

    public function add_template($data)
    {
        return $this->CI->uplicrm_website_builder_model->add_template($data);
    }

    public function update_template($id, $data)
    {
        return $this->CI->uplicrm_website_builder_model->update_template($id, $data);
    }

    public function delete_template($id)
    {
        return $this->CI->uplicrm_website_builder_model->delete_template($id);
    }

    public function uplicrm_unzip_file($zip_file, $extract_path, $method = 'ziparchive')
    {
        if ($method === 'ziparchive') {
            $zip = new ZipArchive;
            if ($zip->open($zip_file) === TRUE) {
                $zip->extractTo($extract_path);
                $zip->close();
                return true;
            } else {
                return false;
            }
        } elseif ($method === 'linux_command') {
            $command = "unzip -o {$zip_file} -d {$extract_path}";
            exec($command, $output, $return_var);
            return ($return_var === 0); // Return true if command executed successfully
        }
        return false; // Invalid method
    }

    public function uplicrm_import_database($db_config, $sql_file, $method = 'mysqli')
    {
        if ($method === 'mysqli') {
            $db_connect = mysqli_connect($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);

            if (!$db_connect) {
                return false; // Connection failed
            }

            $sql = file_get_contents($sql_file);
            if (mysqli_multi_query($db_connect, $sql)) {
                do {
                    if ($result = mysqli_store_result($db_connect)) {
                        mysqli_free_result($result);
                    }
                } while (mysqli_more_results($db_connect) && mysqli_next_result($db_connect));

                mysqli_close($db_connect);
                return true;
            } else {
                mysqli_close($db_connect);
                return false; // Query execution failed
            }
        } elseif ($method === 'mysql_command') {
            $command = "mysql -h {$db_config['hostname']} -u {$db_config['username']} -p{$db_config['password']} {$db_config['database']} < {$sql_file}";
            exec($command, $output, $return_var);
            return ($return_var === 0); // Return true if command executed successfully (Password will be visible in process list)
        } elseif ($method === 'pdo') {
            try {
                $pdo = new PDO("mysql:host={$db_config['hostname']};dbname={$db_config['database']}", $db_config['username'], $db_config['password']);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = file_get_contents($sql_file);
                $pdo->exec($sql);
                return true;
            } catch (PDOException $e) {
                // Log the error or display it
                log_message('error', "PDO Database Import Error: " . $e->getMessage());
                return false;
            }
        }

        return false; // Invalid method
    }

    public function generate_strong_password($length = 12, $include_symbols = true)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        if ($include_symbols) {
            $chars .= "!@#$%^&*()_-=+;:,.?";
        }
        $password = substr(str_shuffle($chars), 0, $length);
        return $password;
    }

    public function start_build_website($template_id, $data)
    {
        $template = $this->get_templates($template_id);

        if (!$template) {
            return false;
        }

        // Create DirectAdmin account if not exists
        $directadmin_account = $this->get_or_create_directadmin_account_for_user($data['user_id']);
        echo '<pre>';
        print_r($directadmin_account);
        echo '</pre>';
        die();
        if (!$directadmin_account) {
            return false;
        }

        $subdomain = $data['subdomain'] . '.' . get_option('uplicrm_main_domain'); // Assuming you have a main domain in settings
        // *** IMPORTANT CORRECTION ***
        // Determine the correct public_html path based on the hosting setup. 
        // This is very specific to your server/DirectAdmin configuration. 
        // Example: Assuming users' websites are under /home/[username]/domains/[domain.com]/public_html
        $public_folder_subdomain = "/home/{$directadmin_account->username}/domains/{$subdomain}/public_html";

        // Create subdomain
        $this->CI->uplicrm_website_builder_api->create_subdomain($directadmin_account->username, $subdomain);

        // Create database
        $db_name = $this->CI->uplicrm_website_builder_api->create_database($directadmin_account->username, $data['subdomain']);

        // Unzip source code (using the corrected path)
        $template_zip_path = FCPATH . 'uploads/uplicrm/templates/' . ($template->source_code ?? '');
        if (file_exists($template_zip_path)) {
            $unzip_success = $this->uplicrm_unzip_file($template_zip_path, $public_folder_subdomain, 'ziparchive');
            if (!$unzip_success) {
                log_message('error', "Failed to unzip template: " . $template_zip_path . " to " . $public_folder_subdomain);
                return false;
            }
        } else {
            log_message('error', "Template file not found: " . $template_zip_path);
            return false;
        }

        // Import database
        $db_config = [
            'hostname' => 'localhost',
            'username' => $directadmin_account->username . '_' . $data['subdomain'],
            'password' => $directadmin_account->password,
            'database' => $directadmin_account->username . '_' . $db_name
        ];
        $this->uplicrm_import_database($db_config, $public_folder_subdomain . '/database.sql');

        // Add Let's Encrypt SSL
        $this->CI->uplicrm_website_builder_api->add_letsencrypt_ssl($directadmin_account->username, $subdomain);

        // Update template status
        $this->update_template($template_id, ['is_created' => 1]);

        // Create website record
        $website_data = [
            'name' => $template->name,
            'user_id' => $data['user_id'],
            'template_id' => $template_id,
            'subdomain' => $subdomain,
            'plan_id' => $data['plan_id'],
            'status' => 'active',
            'is_template' => 0
        ];
        $this->add_website($website_data);

        return true;
    }

    // --- Websites ---

    public function get_websites($id = false)
    {
        return $this->CI->uplicrm_website_builder_model->get_websites($id);
    }

    public function add_website($data)
    {
        return $this->CI->uplicrm_website_builder_model->add_website($data);
    }

    public function update_website($id, $data)
    {
        return $this->CI->uplicrm_website_builder_model->update_website($id, $data);
    }

    public function delete_website($id)
    {
        return $this->CI->uplicrm_website_builder_model->delete_website($id);
    }

    // --- DirectAdmin Accounts ---

    public function get_directadmin_accounts($id = false)
    {
        return $this->CI->directadmin_account_model->get($id);
    }

    public function add_directadmin_account($data)
    {
        return $this->CI->directadmin_account_model->add($data);
    }

    public function update_directadmin_account($id, $data)
    {
        return $this->CI->directadmin_account_model->update($id, $data);
    }

    public function delete_directadmin_account($id)
    {
        return $this->CI->directadmin_account_model->delete($id);
    }

    public function get_or_create_directadmin_account_for_user($user_id)
    {
        $account = $this->CI->directadmin_account_model->get_by_user_id($user_id);

        if ($account) {
            return $account;
        } else {
            // Create DirectAdmin account using API
            $username = 'user' . $user_id; // Generate a unique username
            $password = $this->generate_strong_password(); // Implement a function to generate strong passwords
            $email = $this->CI->db->get_where('tblcontacts', ['userid' => $user_id])->row()->email;

            $create_result = $this->CI->uplicrm_website_builder_api->create_user($username, $email, $password);

            if ($create_result) {
                $account_data = [
                    'username' => $username,
                    'password' => $password,
                    'user_id' => $user_id
                ];
                $account_id = $this->add_directadmin_account($account_data);
                return $this->get_directadmin_accounts($account_id);
            } else {
                return false;
            }
        }
    }

    // --- Service Plans ---

    public function get_service_plans($id = false)
    {
        return $this->CI->uplicrm_website_builder_model->get_service_plans($id);
    }

    public function add_service_plan($data)
    {
        return $this->CI->uplicrm_website_builder_model->add_service_plan($data);
    }

    public function update_service_plan($id, $data)
    {
        return $this->CI->uplicrm_website_builder_model->update_service_plan($id, $data);
    }

    public function delete_service_plan($id)
    {
        return $this->CI->uplicrm_website_builder_model->delete_service_plan($id);
    }

    // --- Plugins ---

    public function get_plugins($id = false)
    {
        return $this->CI->uplicrm_website_builder_model->get_plugins($id);
    }

    public function add_plugin($data)
    {
        return $this->CI->uplicrm_website_builder_model->add_plugin($data);
    }

    public function update_plugin($id, $data)
    {
        return $this->CI->uplicrm_website_builder_model->update_plugin($id, $data);
    }

    public function delete_plugin($id)
    {
        return $this->CI->uplicrm_website_builder_model->delete_plugin($id);
    }

    public function install_plugin($website_id, $plugin_id)
    {
        $website = $this->get_websites($website_id);
        $plugin = $this->get_plugins($plugin_id);

        if (!$website || !$plugin) {
            return false;
        }

        // Check if website type is WordPress
        $template = $this->get_templates($website->template_id);
        if ($template->source_type !== 'wordpress') {
            return 'website_type_wordpress'; // Or throw an exception/return specific error code
        }

        // ***Corrected/Improved logic to get public_html path***
        $directadmin_account = $this->CI->directadmin_account_model->get_by_user_id($website->user_id);

        if (!$directadmin_account) {
            log_message('error', "DirectAdmin account not found for user ID: {$website->user_id}");
            return false;
        }

        // Determine public_html path (adapt to your DirectAdmin setup – this is crucial!)
        // Option 1 (Less robust, server-specific):
        //$public_folder_subdomain = "/home/{$directadmin_account->username}/domains/{$website->subdomain}/public_html";

        // Option 2 (More robust, use DirectAdmin API - RECOMMENDED):
        $public_folder_subdomain = $this->CI->uplicrm_website_builder_api->get_user_document_root($directadmin_account->username, $website->subdomain); // Implement this API call

        if (!$public_folder_subdomain) {
            log_message('error', "Could not get document root for website: {$website->subdomain}");
            return false;
        }

        $plugin_folder = $public_folder_subdomain . '/wp-content/plugins/';

        $plugin_zip_path = FCPATH . 'uploads/uplicrm/plugins/' . ($plugin->source_code ?? '');
        if (file_exists($plugin_zip_path)) {
            $unzip_success = $this->uplicrm_unzip_file($plugin_zip_path, $plugin_folder, 'ziparchive'); // Assuming 'ziparchive' method
            if (!$unzip_success) {
                log_message('error', "Failed to unzip plugin: " . $plugin_zip_path . " to " . $plugin_folder);
                return false;
            } else {
                return true; // Plugin installed successfully
            }
        } else {
            log_message('error', "Plugin file not found: " . $plugin_zip_path);
            return false;
        }
    }

    // --- Activity Logs ---

    public function log_activity($description, $user_id = null)
    {
        log_activity($description, $user_id);
    }

    public function get_activity_logs($user_id = null)
    {
        $this->CI->db->where('module', UPLICRM_WEBSITE_BUILDER_MODULE_NAME);

        if ($user_id) {
            $this->CI->db->where('staffid', $user_id);
        }

        $this->CI->db->order_by('date', 'desc');
        return $this->CI->db->get(db_prefix() . 'activity_log')->result();
    }

    // --- Invoice Generation ---
    public function generate_invoice_for_plan_upgrade($website_id, $new_plan_id)
    {
        $website = $this->get_websites($website_id);
        $new_plan = $this->get_service_plans($new_plan_id);

        if (!$website || !$new_plan) {
            return false;
        }

        $client = $this->CI->clients_model->get($website->user_id);

        if (!$client) {
            return false;
        }

        $invoice_data = [
            'clientid' => $client->userid,
            'date' => date('Y-m-d'),
            'duedate' => date('Y-m-d', strtotime('+30 days')), // Set a due date (e.g., 30 days from now)
            'currency' => get_base_currency()->id, // Get default currency
            'subtotal' => $new_plan->price,
            'total' => $new_plan->price,
            'status' => 1, // Unpaid status
            'project_id' => 0,
            'number' => get_option('next_invoice_number'),
        ];

        $this->CI->db->insert(db_prefix() . 'invoices', $invoice_data);
        $invoice_id = $this->CI->db->insert_id();

        if ($invoice_id) {
            // Add invoice item
            $item_data = [
                'description' => _l('upgrade_plan') . ' - ' . $website->name . ' (' . $website->subdomain . ') - ' . _l('from') . ' ' . $this->get_service_plans($website->plan_id)->name . ' ' . _l('to') . ' ' . $new_plan->name,
                'long_description' => '',
                'qty' => 1,
                'rate' => $new_plan->price,
                'tax' => 0, // Add tax if applicable
                'order' => 1,
                'rel_id' => $invoice_id,
                'rel_type' => 'invoice',
                'item_order' => 1,
            ];

            $this->CI->db->insert(db_prefix() . 'itemable', $item_data);

            // Update invoice number
            $this->CI->db->where('id', $invoice_id);
            $this->CI->db->update(db_prefix() . 'invoices', [
                'number_format' => get_option('invoice_number_format'),
                'prefix' => get_option('invoice_prefix'),
            ]);

            // Update next invoice number
            $this->CI->db->where('name', 'next_invoice_number');
            $this->CI->db->set('value', 'value+1', false);
            $this->CI->db->update(db_prefix() . 'options');

            // Update website plan
            $this->update_website($website_id, ['plan_id' => $new_plan_id]);

            // Log activity
            $this->log_activity('Invoice generated for plan upgrade: ' . $invoice_id, $website->user_id);

            return $invoice_id;
        }

        return false;
    }
}
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Uplicrm_website_builder extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(UPLICRM_WEBSITE_BUILDER_MODULE_NAME . '/upli_website_builder_lib');
        $this->load->library(UPLICRM_WEBSITE_BUILDER_MODULE_NAME . '/uplicrm_website_builder_api');
        $this->load->model(UPLICRM_WEBSITE_BUILDER_MODULE_NAME . '/uplicrm_website_builder_model');
        $this->load->model(UPLICRM_WEBSITE_BUILDER_MODULE_NAME . '/directadmin_account_model');
        $this->load->library('form_validation');

        if (!is_admin()) {
            access_denied('Uplicrm Website Builder');
        }
    }

    public function index()
    {
        redirect(admin_url('uplicrm_website_builder/templates'));
    }

    // --- Categories ---
    public function categories()
    {
        if ($this->input->post()) {
            if ($this->input->post('id')) {
                $this->update_category($this->input->post('id'));
            } else {
                $this->add_category();
            }
        } else {
            $data['categories'] = $this->upli_website_builder_lib->get_categories();
            $data['title'] = _l('categories');
            $this->load->view(UPLICRM_WEBSITE_BUILDER_MODULE_NAME . '/admin/categories/manage', $data);
        }
    }

    public function category($id = null)
    {
        if ($id) {
            $data['category'] = $this->upli_website_builder_lib->get_categories($id);
            $data['title'] = _l('edit_category');
        } else {
            $data['title'] = _l('new_category');
        }

        $this->load->view(UPLICRM_WEBSITE_BUILDER_MODULE_NAME . '/admin/categories/category', $data);
    }

    public function add_category()
    {
        $this->form_validation->set_rules('name', _l('category_name'), 'required');

        if ($this->form_validation->run() !== false) {
            $data = $this->input->post();

            $upload_result = upli_handle_upload('image', FCPATH . 'uploads/uplicrm/categories/');

            if (isset($upload_result['success']) && $upload_result['success']) {
                $data['image'] = $upload_result['file_name'];
            } elseif (isset($upload_result['error'])) {
                set_alert('danger', $upload_result['error']);
                redirect(admin_url('uplicrm_website_builder/category'));
            }

            $id = $this->upli_website_builder_lib->add_category($data);

            if ($id) {
                set_alert('success', _l('category_created_successfully'));
            } else {
                set_alert('danger', _l('error_occurred'));
            }
        } else {
            set_alert('danger', validation_errors());
        }

        redirect(admin_url('uplicrm_website_builder/categories'));
    }

    public function update_category($id)
    {
        $this->form_validation->set_rules('name', _l('category_name'), 'required');

        if ($this->form_validation->run() !== false) {
            $data = $this->input->post();

            $upload_result = upli_handle_upload('image', FCPATH . 'uploads/uplicrm/categories/');

            if (isset($upload_result['success']) && $upload_result['success']) {
                $data['image'] = $upload_result['file_name'];
            } elseif (isset($upload_result['error'])) {
                set_alert('danger', $upload_result['error']);
                redirect(admin_url('uplicrm_website_builder/category/' . $id));
            }

            $success = $this->upli_website_builder_lib->update_category($id, $data);

            if ($success) {
                set_alert('success', _l('category_updated_successfully'));
            } else {
                set_alert('danger', _l('error_occurred'));
            }
        } else {
            set_alert('danger', validation_errors());
        }

        redirect(admin_url('uplicrm_website_builder/categories'));
    }

    public function delete_category($id)
    {
        $success = $this->upli_website_builder_lib->delete_category($id);

        if ($success) {
            set_alert('success', _l('category_deleted_successfully'));
        } else {
            set_alert('danger', _l('error_occurred'));
        }

        redirect(admin_url('uplicrm_website_builder/categories'));
    }

    // --- Templates ---

    public function templates()
    {
        if ($this->input->post()) {
            if ($this->input->post('id')) {
                $this->update_template($this->input->post('id'));
            } else {
                $this->add_template();
            }
        } else {
            $data['templates'] = $this->upli_website_builder_lib->get_templates();
            $data['title'] = _l('templates');
            $this->load->view(UPLICRM_WEBSITE_BUILDER_MODULE_NAME . '/admin/templates/manage', $data);
        }
    }

    public function template($id = null)
    {
        if ($id) {
            $data['template'] = $this->upli_website_builder_lib->get_templates($id);
            $data['title'] = _l('edit_template');
        } else {
            $data['title'] = _l('new_template');
        }

        $data['categories'] = $this->upli_website_builder_lib->get_categories();

        // Convert $data['categories'] to the correct format for render_select()
        $categories_array = [];
        foreach ($data['categories'] as $plan) {
            $categories_array[] = [
                'id' => $plan->id,
                'name' => $plan->name
            ];
        }
        $data['categories'] = $categories_array;

        $this->load->view(UPLICRM_WEBSITE_BUILDER_MODULE_NAME . '/admin/templates/template', $data);
    }

    public function add_template()
    {
        $this->form_validation->set_rules('name', _l('template_name'), 'required');
        $this->form_validation->set_rules('subdomain', _l('subdomain'), 'required');
        $this->form_validation->set_rules('category_id', _l('template_associated_category'), 'required');
        $this->form_validation->set_rules('source_type', _l('template_source_type'), 'required');
        // Add validation for other fields as needed

        if ($this->form_validation->run() !== false) {
            $data = $this->input->post();

            $upload_image_result = upli_handle_upload('image', FCPATH . 'uploads/uplicrm/templates/');
            if (isset($upload_image_result['success']) && $upload_image_result['success']) {
                $data['image'] = $upload_image_result['file_name'];
            } elseif (isset($upload_image_result['error'])) {
                set_alert('danger', $upload_image_result['error']);
                redirect(admin_url('uplicrm_website_builder/template'));
            }

            $upload_source_result = upli_handle_upload('source_code', FCPATH . 'uploads/uplicrm/templates/');
            if (isset($upload_source_result['success']) && $upload_source_result['success']) {
                $data['source_code'] = $upload_source_result['file_name'];
            } elseif (isset($upload_source_result['error'])) {
                set_alert('danger', $upload_source_result['error']);
                redirect(admin_url('uplicrm_website_builder/template'));
            }

            $id = $this->upli_website_builder_lib->add_template($data);

            if ($id) {
                set_alert('success', _l('template_created_successfully'));
                redirect(admin_url('uplicrm_website_builder/templates'));
            } else {
                set_alert('danger', _l('error_occurred'));
            }
        } else {
            set_alert('danger', validation_errors());
        }
        redirect(admin_url('uplicrm_website_builder/template'));
    }

    public function update_template($id)
    {
        $this->form_validation->set_rules('name', _l('template_name'), 'required');
        $this->form_validation->set_rules('subdomain', _l('subdomain'), 'required');
        // Add validation for other fields as needed

        if ($this->form_validation->run() !== false) {
            $data = $this->input->post();

            $upload_image_result = upli_handle_upload('image', FCPATH . 'uploads/uplicrm/templates/');
            if (isset($upload_image_result['success']) && $upload_image_result['success']) {
                $data['image'] = $upload_image_result['file_name'];
            } elseif (isset($upload_image_result['error'])) {
                set_alert('danger', $upload_image_result['error']);
                redirect(admin_url('uplicrm_website_builder/template/' . $id));
            }

            $upload_source_result = upli_handle_upload('source_code', FCPATH . 'uploads/uplicrm/templates/');
            if (isset($upload_source_result['success']) && $upload_source_result['success']) {
                $data['source_code'] = $upload_source_result['file_name'];
            } elseif (isset($upload_source_result['error'])) {
                set_alert('danger', $upload_source_result['error']);
                redirect(admin_url('uplicrm_website_builder/template/' . $id));
            }

            $success = $this->upli_website_builder_lib->update_template($id, $data);

            if ($success) {
                set_alert('success', _l('template_updated_successfully'));
            } else {
                set_alert('danger', _l('error_occurred'));
            }
        } else {
            set_alert('danger', validation_errors());
        }

        redirect(admin_url('uplicrm_website_builder/templates'));
    }

    public function delete_template($id)
    {
        $success = $this->upli_website_builder_lib->delete_template($id);

        if ($success) {
            set_alert('success', _l('template_deleted_successfully'));
        } else {
            set_alert('danger', _l('error_occurred'));
        }

        redirect(admin_url('uplicrm_website_builder/templates'));
    }

    public function start_build_website()
    {
        $template_id = $this->input->get('id');

        if ($template_id) {
            set_alert('danger', _l('error_occurred'));
        }
        
        $data = [
            'user_id' => get_staff_user_id(),
        ];

        $success = $this->upli_website_builder_lib->start_build_website($template_id, $data);

        if ($success) {
            set_alert('success', _l('website_created_successfully'));
        } else {
            set_alert('danger', _l('error_occurred'));
        }

        redirect(admin_url('uplicrm_website_builder/templates'));
    }

    // --- Websites ---

    public function websites()
    {
        if ($this->input->post()) {
            if ($this->input->post('id')) {
                $this->update_website($this->input->post('id'));
            } else {
                $this->add_website();
            }
        } else {
            $data['websites'] = $this->upli_website_builder_lib->get_websites();
            $data['title'] = _l('websites');
            $this->load->view(UPLICRM_WEBSITE_BUILDER_MODULE_NAME . '/admin/websites/manage', $data);
        }
    }

    public function website($id = null)
    {
        if ($id) {
            $data['website'] = $this->upli_website_builder_lib->get_websites($id);
            $data['title'] = _l('edit_website');
        } else {
            $data['title'] = _l('new_website');
        }

        $data['templates'] = $this->upli_website_builder_lib->get_templates();
        $data['plans'] = $this->upli_website_builder_lib->get_service_plans();

        // Convert $data['plans'] to the correct format for render_select()
        $plans_array = [];
        foreach ($data['plans'] as $plan) {
            $plans_array[] = [
                'id' => $plan->id,
                'name' => $plan->name
            ];
        }
        $data['plans'] = $plans_array;

        $this->load->view(UPLICRM_WEBSITE_BUILDER_MODULE_NAME . '/admin/websites/website', $data);
    }

    public function add_website()
    {
        $this->form_validation->set_rules('name', _l('website_name'), 'required');
        $this->form_validation->set_rules('template_id', _l('template'), 'required');
        $this->form_validation->set_rules('plan_id', _l('website_plan'), 'required');
        // Add validation for other fields as needed

        if ($this->form_validation->run() !== false) {
            $data = $this->input->post();
            $data['user_id'] = get_staff_user_id(); // Assuming admin creates the website
            $data['is_template'] = 0;

            $id = $this->upli_website_builder_lib->add_website($data);

            if ($id) {
                set_alert('success', _l('website_created_successfully'));
            } else {
                set_alert('danger', _l('error_occurred'));
            }
        } else {
            set_alert('danger', validation_errors());
        }

        redirect(admin_url('uplicrm_website_builder/websites'));
    }

    public function update_website($id)
    {
        $this->form_validation->set_rules('name', _l('website_name'), 'required');
        // Add validation for other fields as needed

        if ($this->form_validation->run() !== false) {
            $data = $this->input->post();

            $success = $this->upli_website_builder_lib->update_website($id, $data);

            if ($success) {
                set_alert('success', _l('website_updated_successfully'));
            } else {
                set_alert('danger', _l('error_occurred'));
            }
        } else {
            set_alert('danger', validation_errors());
        }

        redirect(admin_url('uplicrm_website_builder/websites'));
    }

    public function delete_website($id)
    {
        $success = $this->upli_website_builder_lib->delete_website($id);

        if ($success) {
            set_alert('success', _l('website_deleted_successfully'));
        } else {
            set_alert('danger', _l('error_occurred'));
        }

        redirect(admin_url('uplicrm_website_builder/websites'));
    }

    // --- DirectAdmin Accounts ---

    public function directadmin_accounts()
    {
        if ($this->input->post()) {
            if ($this->input->post('id')) {
                $this->update_directadmin_account($this->input->post('id'));
            } else {
                $this->add_directadmin_account();
            }
        } else {
            $data['accounts'] = $this->upli_website_builder_lib->get_directadmin_accounts();
            $data['title'] = _l('directadmin_accounts');
            $this->load->view(UPLICRM_WEBSITE_BUILDER_MODULE_NAME . '/admin/directadmin_accounts/manage', $data);
        }
    }

    public function directadmin_account($id = null)
    {
        if ($id) {
            $data['account'] = $this->upli_website_builder_lib->get_directadmin_accounts($id);
            $data['title'] = _l('edit_directadmin_account');
        } else {
            $data['title'] = _l('new_directadmin_account');
        }

        $this->load->model('staff_model');
        $this->load->model('clients_model');
        $data['staff'] = $this->staff_model->get('', ['active' => 1]);
        $data['clients'] = $this->clients_model->get('', ['active' => 1]);
        $data['users'] = array_merge($data['staff'], $data['clients']);
        $this->load->view(UPLICRM_WEBSITE_BUILDER_MODULE_NAME . '/admin/directadmin_accounts/account', $data);
    }

    public function add_directadmin_account()
    {
        $this->form_validation->set_rules('username', _l('directadmin_account_username'), 'required');
        $this->form_validation->set_rules('password', _l('directadmin_account_password'), 'required');
        $this->form_validation->set_rules('user_id', _l('directadmin_account_user'), 'required');

        if ($this->form_validation->run() !== false) {
            $data = $this->input->post();

            $id = $this->upli_website_builder_lib->add_directadmin_account($data);

            if ($id) {
                set_alert('success', _l('directadmin_account_created_successfully'));
            } else {
                set_alert('danger', _l('error_occurred'));
            }
        } else {
            set_alert('danger', validation_errors());
        }

        redirect(admin_url('uplicrm_website_builder/directadmin_accounts'));
    }

    public function update_directadmin_account($id)
    {
        $this->form_validation->set_rules('username', _l('directadmin_account_username'), 'required');
        $this->form_validation->set_rules('password', _l('directadmin_account_password'), 'required');
        $this->form_validation->set_rules('user_id', _l('directadmin_account_user'), 'required');

        if ($this->form_validation->run() !== false) {
            $data = $this->input->post();

            $success = $this->upli_website_builder_lib->update_directadmin_account($id, $data);

            if ($success) {
                set_alert('success', _l('directadmin_account_updated_successfully'));
            } else {
                set_alert('danger', _l('error_occurred'));
            }
        } else {
            set_alert('danger', validation_errors());
        }

        redirect(admin_url('uplicrm_website_builder/directadmin_accounts'));
    }

    public function delete_directadmin_account($id)
    {
        $success = $this->upli_website_builder_lib->delete_directadmin_account($id);

        if ($success) {
            set_alert('success', _l('directadmin_account_deleted_successfully'));
        } else {
            set_alert('danger', _l('error_occurred'));
        }

        redirect(admin_url('uplicrm_website_builder/directadmin_accounts'));
    }

    // --- Service Plans ---

    public function service_plans()
    {
        if ($this->input->post()) {
            if ($this->input->post('id')) {
                $this->update_service_plan($this->input->post('id'));
            } else {
                $this->add_service_plan();
            }
        } else {
            $data['plans'] = $this->upli_website_builder_lib->get_service_plans();
            $data['title'] = _l('service_plans');
            $this->load->view(UPLICRM_WEBSITE_BUILDER_MODULE_NAME . '/admin/service_plans/manage', $data);
        }
    }

    public function service_plan($id = null)
    {
        if ($id) {
            $data['plan'] = $this->upli_website_builder_lib->get_service_plans($id);
            $data['title'] = _l('edit_service_plan');
        } else {
            $data['title'] = _l('new_service_plan');
        }

        $this->load->view(UPLICRM_WEBSITE_BUILDER_MODULE_NAME . '/admin/service_plans/plan', $data);
    }

    public function add_service_plan()
    {
        $this->form_validation->set_rules('name', _l('service_plan_name'), 'required');
        $this->form_validation->set_rules('price', _l('service_plan_price'), 'required|numeric');
        $this->form_validation->set_rules('type', _l('service_plan_type'), 'required');

        if ($this->form_validation->run() !== false) {
            $data = $this->input->post();

            $id = $this->upli_website_builder_lib->add_service_plan($data);

            if ($id) {
                set_alert('success', _l('service_plan_created_successfully'));
            } else {
                set_alert('danger', _l('error_occurred'));
            }
        } else {
            set_alert('danger', validation_errors());
        }

        redirect(admin_url('uplicrm_website_builder/service_plans'));
    }

    public function update_service_plan($id)
    {
        $this->form_validation->set_rules('name', _l('service_plan_name'), 'required');
        $this->form_validation->set_rules('price', _l('service_plan_price'), 'required|numeric');
        $this->form_validation->set_rules('type', _l('service_plan_type'), 'required');

        if ($this->form_validation->run() !== false) {
            $data = $this->input->post();

            $success = $this->upli_website_builder_lib->update_service_plan($id, $data);

            if ($success) {
                set_alert('success', _l('service_plan_updated_successfully'));
            } else {
                set_alert('danger', _l('error_occurred'));
            }
        } else {
            set_alert('danger', validation_errors());
        }

        redirect(admin_url('uplicrm_website_builder/service_plans'));
    }

    public function delete_service_plan($id)
    {
        $success = $this->upli_website_builder_lib->delete_service_plan($id);

        if ($success) {
            set_alert('success', _l('service_plan_deleted_successfully'));
        } else {
            set_alert('danger', _l('error_occurred'));
        }

        redirect(admin_url('uplicrm_website_builder/service_plans'));
    }

    // --- Plugins ---

    public function plugins()
    {
        if ($this->input->post()) {
            if ($this->input->post('id')) {
                $this->update_plugin($this->input->post('id'));
            } else {
                $this->add_plugin();
            }
        } else {
            $data['plugins'] = $this->upli_website_builder_lib->get_plugins();
            $data['title'] = _l('plugins');
            $this->load->view(UPLICRM_WEBSITE_BUILDER_MODULE_NAME . '/admin/plugins/manage', $data);
        }
    }

    public function plugin($id = null)
    {
        if ($id) {
            $data['plugin'] = $this->upli_website_builder_lib->get_plugins($id);
            $data['title'] = _l('edit_plugin');
        } else {
            $data['title'] = _l('new_plugin');
        }

        $this->load->view(UPLICRM_WEBSITE_BUILDER_MODULE_NAME . '/admin/plugins/plugin', $data);
    }

    public function add_plugin()
    {
        $this->form_validation->set_rules('name', _l('plugin_name'), 'required');
        $this->form_validation->set_rules('price', _l('plugin_price'), 'required|numeric');

        if ($this->form_validation->run() !== false) {
            $data = $this->input->post();

            $upload_source_result = upli_handle_upload('source_code', FCPATH . 'uploads/uplicrm/plugins/');
            if (isset($upload_source_result['success']) && $upload_source_result['success']) {
                $data['source_code'] = $upload_source_result['file_name'];
            } elseif (isset($upload_source_result['error'])) {
                set_alert('danger', $upload_source_result['error']);
                redirect(admin_url('uplicrm_website_builder/plugin'));
            }

            $id = $this->upli_website_builder_lib->add_plugin($data);

            if ($id) {
                set_alert('success', _l('plugin_created_successfully'));
            } else {
                set_alert('danger', _l('error_occurred'));
            }
        } else {
            set_alert('danger', validation_errors());
        }

        redirect(admin_url('uplicrm_website_builder/plugins'));
    }

    public function update_plugin($id)
    {
        $this->form_validation->set_rules('name', _l('plugin_name'), 'required');
        $this->form_validation->set_rules('price', _l('plugin_price'), 'required|numeric');

        if ($this->form_validation->run() !== false) {
            $data = $this->input->post();

            $upload_source_result = upli_handle_upload('source_code', FCPATH . 'uploads/uplicrm/plugins/');
            if (isset($upload_source_result['success']) && $upload_source_result['success']) {
                $data['source_code'] = $upload_source_result['file_name'];
            } elseif (isset($upload_source_result['error'])) {
                set_alert('danger', $upload_source_result['error']);
                redirect(admin_url('uplicrm_website_builder/plugin/' . $id));
            }

            $success = $this->upli_website_builder_lib->update_plugin($id, $data);

            if ($success) {
                set_alert('success', _l('plugin_updated_successfully'));
            } else {
                set_alert('danger', _l('error_occurred'));
            }
        } else {
            set_alert('danger', validation_errors());
        }

        redirect(admin_url('uplicrm_website_builder/plugins'));
    }

    public function delete_plugin($id)
    {
        $success = $this->upli_website_builder_lib->delete_plugin($id);

        if ($success) {
            set_alert('success', _l('plugin_deleted_successfully'));
        } else {
            set_alert('danger', _l('error_occurred'));
        }

        redirect(admin_url('uplicrm_website_builder/plugins'));
    }

    // --- Activity Logs ---

    public function logs()
    {
        $data['logs'] = $this->upli_website_builder_lib->get_activity_logs();
        $data['title'] = _l('activity_logs');
        $this->load->view(UPLICRM_WEBSITE_BUILDER_MODULE_NAME . '/admin/logs', $data);
    }

    // --- Categories Table ---
    public function categories_table()
    {
        $this->app->get_table_data(module_views_path(UPLICRM_WEBSITE_BUILDER_MODULE_NAME, 'admin/tables/categories'));
    }

    // --- Templates Table ---
    public function templates_table()
    {
        $this->app->get_table_data(module_views_path(UPLICRM_WEBSITE_BUILDER_MODULE_NAME, 'admin/tables/templates'));
    }

    // --- Websites Table ---
    public function websites_table()
    {
        $this->app->get_table_data(module_views_path(UPLICRM_WEBSITE_BUILDER_MODULE_NAME, 'admin/tables/websites'));
    }

    // --- DirectAdmin Accounts Table ---
    public function directadmin_accounts_table()
    {
        $this->app->get_table_data(module_views_path(UPLICRM_WEBSITE_BUILDER_MODULE_NAME, 'admin/tables/directadmin_accounts'));
    }

    // --- Service Plans Table ---
    public function service_plans_table()
    {
        $this->app->get_table_data(module_views_path(UPLICRM_WEBSITE_BUILDER_MODULE_NAME, 'admin/tables/service_plans'));
    }

    // --- Plugins Table ---
    public function plugins_table()
    {
        $this->app->get_table_data(module_views_path(UPLICRM_WEBSITE_BUILDER_MODULE_NAME, 'admin/tables/plugins'));
    }

    public function settings()
    {
        $data['plans'] = $this->upli_website_builder_lib->get_service_plans();
        $data['title'] = _l('uplicrm_website_builder_settings');
        $this->load->view(UPLICRM_WEBSITE_BUILDER_MODULE_NAME . '/admin/settings', $data);
    }

    public function save_settings()
    {
        $post_data = $this->input->post();

        // Update each setting using update_option()
        update_option('uplicrm_wb_default_domain', $post_data['default_domain']);
        update_option('uplicrm_wb_default_plan', $post_data['default_plan']);
        update_option('uplicrm_wb_enable_client_website_creation', isset($post_data['enable_client_website_creation']) ? '1' : '0');
        update_option('uplicrm_wb_enable_plugin_purchases', isset($post_data['enable_plugin_purchases']) ? '1' : '0');

        update_option('uplicrm_wb_directadmin_url', $post_data['directadmin_url']);
        update_option('uplicrm_wb_directadmin_username', $post_data['directadmin_username']);
        update_option('uplicrm_wb_directadmin_password', $post_data['directadmin_password']);
        update_option('uplicrm_wb_directadmin_ip', $post_data['directadmin_ip']);
        update_option('uplicrm_wb_directadmin_port', $post_data['directadmin_port']);
        update_option('uplicrm_wb_directadmin_package', $post_data['directadmin_package']);

        update_option('uplicrm_wb_enable_website_creation_notifications', isset($post_data['enable_website_creation_notifications']) ? '1' : '0');
        update_option('uplicrm_wb_notification_email_address', $post_data['notification_email_address']);

        set_alert('success', _l('settings_updated'));
        redirect(admin_url('uplicrm_website_builder/settings'));
    }

    public function test_connection()
    {
        $host = $this->input->post('host');
        $port = $this->input->post('port');
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $connection_successful = $this->uplicrm_website_builder_api->test_connection($host,$port,$username,$password);

        if ($connection_successful) {
            echo json_encode(['success' => true]);
        } else {
            $errorMessage = "Connection Failed. Please check your credentials."; // Get error message if possible
            echo json_encode(['success' => false, 'message' => $errorMessage]);
        }

    }
}
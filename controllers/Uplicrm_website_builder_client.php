<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Uplicrm_website_builder_client extends ClientsController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(UPLICRM_WEBSITE_BUILDER_MODULE_NAME . '/upli_website_builder_lib');
        $this->load->model(UPLICRM_WEBSITE_BUILDER_MODULE_NAME . '/uplicrm_website_builder_model');
        $this->load->model(UPLICRM_WEBSITE_BUILDER_MODULE_NAME . '/directadmin_account_model');
        $this->load->library('form_validation');
        $this->load->model('clients_model');
    }

    public function index()
    {
        redirect(site_url('uplicrm_website_builder_client/templates'));
    }

    // --- Templates (Client View) ---

    public function templates()
    {
        $data['templates'] = $this->upli_website_builder_lib->get_templates();
        $data['title'] = _l('browse_templates');
        $this->data($data);
        $this->view('client/templates');
        $this->layout();
    }

    // --- Websites (Client View) ---

    public function websites()
    {
        $client_id = get_client_user_id();
        if ($this->input->post()) {
            if ($this->input->post('action') === 'start_build') {
                $this->start_build_website_for_client($this->input->post('template_id'), $client_id);
            } elseif ($this->input->post('action') === 'park_domain') {
                $this->park_domain_for_client($this->input->post('website_id'), $client_id);
            } elseif ($this->input->post('action') === 'upgrade_plan') {
                $this->upgrade_plan_for_client($this->input->post('website_id'), $this->input->post('plan_id'), $client_id);
            }
        } else {
            $data['websites'] = $this->uplicrm_website_builder_model->get_websites();
            $data['websites'] = array_filter($data['websites'], function ($website) use ($client_id) {
                return $website->user_id == $client_id;
            });
            $data['title'] = _l('my_websites');
            $data['plans'] = $this->upli_website_builder_lib->get_service_plans();
            $this->data($data);
            $this->view('client/websites/manage');
            $this->layout();
        }
    }
    
    public function start_build_website_for_client($template_id, $client_id)
    {
        $this->form_validation->set_rules('subdomain', _l('website_subdomain'), 'required');

        if ($this->form_validation->run() !== false) {
            $data = [
                'user_id' => $client_id,
                'subdomain' => $this->input->post('subdomain'),
                'plan_id' => $this->input->post('plan_id') ? $this->input->post('plan_id') : $this->db->where('type','trial')->get(db_prefix().'uplicrm_website_builder_service_plans')->row()->id, // Default to trial plan
            ];

            $success = $this->upli_website_builder_lib->start_build_website($template_id, $data);

            if ($success) {
                set_alert('success', _l('website_created_successfully'));
            } else {
                set_alert('danger', _l('error_occurred'));
            }
        } else {
            set_alert('danger', validation_errors());
        }

        redirect(site_url('uplicrm_website_builder_client/websites'));
    }

    public function delete_website($id)
    {
        $client_id = get_client_user_id();
        $website = $this->upli_website_builder_lib->get_websites($id);

        if ($website && $website->user_id == $client_id) {
            $success = $this->upli_website_builder_lib->delete_website($id);

            if ($success) {
                set_alert('success', _l('website_deleted_successfully'));
            } else {
                set_alert('danger', _l('error_occurred'));
            }
        } else {
            set_alert('danger', _l('error_occurred'));
        }

        redirect(site_url('uplicrm_website_builder_client/websites'));
    }

    public function park_domain_for_client($website_id, $client_id)
    {
        $website = $this->upli_website_builder_lib->get_websites($website_id);

        if ($website && $website->user_id == $client_id) {
            $directadmin_account = $this->upli_website_builder_lib->get_or_create_directadmin_account_for_user($client_id);

            if ($directadmin_account) {
                $success = $this->uplicrm_website_builder_api->park_domain($directadmin_account->username, $website->subdomain);

                if ($success) {
                    set_alert('success', _l('domain_parked_successfully'));
                } else {
                    set_alert('danger', _l('error_occurred'));
                }
            } else {
                set_alert('danger', _l('error_occurred'));
            }
        } else {
            set_alert('danger', _l('error_occurred'));
        }

        redirect(site_url('uplicrm_website_builder_client/websites'));
    }

    public function upgrade_plan_for_client($website_id, $new_plan_id, $client_id)
    {
        $website = $this->upli_website_builder_lib->get_websites($website_id);

        if ($website && $website->user_id == $client_id) {
            $invoice_id = $this->upli_website_builder_lib->generate_invoice_for_plan_upgrade($website_id, $new_plan_id);

            if ($invoice_id) {
                set_alert('success', _l('invoice_generated_successfully'));
                redirect(site_url('invoice/' . $invoice_id));
            } else {
                set_alert('danger', _l('error_occurred'));
            }
        } else {
            set_alert('danger', _l('error_occurred'));
        }

        redirect(site_url('uplicrm_website_builder_client/websites'));
    }
    // --- Plugins (Client View) ---

    public function plugins()
    {
        $data['plugins'] = $this->upli_website_builder_lib->get_plugins();
        $data['title'] = _l('plugins');
        $this->data($data);
        $this->view('client/plugins');
        $this->layout();
    }

    public function install_plugin($website_id, $plugin_id)
    {
        $client_id = get_client_user_id();
        $website = $this->upli_website_builder_lib->get_websites($website_id);

        if ($website && $website->user_id == $client_id) {
            $result = $this->upli_website_builder_lib->install_plugin($website_id, $plugin_id);

            if ($result === true) {
                set_alert('success', _l('plugin_installed_successfully'));
            } elseif ($result === 'website_type_wordpress') {
                set_alert('warning', _l('website_type_wordpress'));
            } else {
                set_alert('danger', _l('error_occurred'));
            }
        } else {
            set_alert('danger', _l('error_occurred'));
        }

        redirect(site_url('uplicrm_website_builder_client/websites'));
    }
    // --- Activity Logs (Client View) ---

    public function logs()
    {
        $client_id = get_client_user_id();
        $data['logs'] = $this->upli_website_builder_lib->get_activity_logs($client_id);
        $data['title'] = _l('activity_logs');
        $this->data($data);
        $this->view('client/logs');
        $this->layout();
    }
}
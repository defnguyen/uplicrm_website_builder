<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Uplicrm_website_builder_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // --- Categories ---

    public function get_categories($id = false)
    {
        if ($id) {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'uplicrm_website_builder_categories')->row();
        }

        return $this->db->get(db_prefix() . 'uplicrm_website_builder_categories')->result();
    }

    public function add_category($data)
    {
        $data['slug'] = url_title($data['name'], '-', true);
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert(db_prefix() . 'uplicrm_website_builder_categories', $data);
        return $this->db->insert_id();
    }

    public function update_category($id, $data)
    {
        $data['slug'] = url_title($data['name'], '-', true);
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'uplicrm_website_builder_categories', $data);
        return $this->db->affected_rows() > 0;
    }

    public function delete_category($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'uplicrm_website_builder_categories');
        return $this->db->affected_rows() > 0;
    }

    // --- Templates ---

    public function get_templates($id = false)
    {
        $this->db->select(db_prefix() . 'uplicrm_website_builder_templates.*, ' . db_prefix() . 'uplicrm_website_builder_categories.name as category_name');
        $this->db->join(db_prefix() . 'uplicrm_website_builder_categories', db_prefix() . 'uplicrm_website_builder_templates.category_id = ' . db_prefix() . 'uplicrm_website_builder_categories.id', 'left');

        if ($id) {
            $this->db->where(db_prefix() . 'uplicrm_website_builder_templates.id', $id);
            return $this->db->get(db_prefix() . 'uplicrm_website_builder_templates')->row();
        }

        return $this->db->get(db_prefix() . 'uplicrm_website_builder_templates')->result();
    }

    public function add_template($data)
    {
        $data['slug'] = url_title($data['name'], '-', true);
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert(db_prefix() . 'uplicrm_website_builder_templates', $data);
        return $this->db->insert_id();
    }

    public function update_template($id, $data)
    {
        $data['slug'] = url_title($data['name'], '-', true);
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'uplicrm_website_builder_templates', $data);
        return $this->db->affected_rows() > 0;
    }

    public function delete_template($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'uplicrm_website_builder_templates');
        return $this->db->affected_rows() > 0;
    }

    // --- Websites ---

    public function get_websites($id = false)
    {
        $this->db->select(db_prefix() . 'uplicrm_website_builder_websites.*, ' .
            db_prefix() . 'uplicrm_website_builder_templates.name as template_name, ' .
            db_prefix() . 'uplicrm_website_builder_service_plans.name as plan_name');
        $this->db->join(db_prefix() . 'uplicrm_website_builder_templates', db_prefix() . 'uplicrm_website_builder_websites.template_id = ' . db_prefix() . 'uplicrm_website_builder_templates.id', 'left');
        $this->db->join(db_prefix() . 'uplicrm_website_builder_service_plans', db_prefix() . 'uplicrm_website_builder_websites.plan_id = ' . db_prefix() . 'uplicrm_website_builder_service_plans.id', 'left');

        if ($id) {
            $this->db->where(db_prefix() . 'uplicrm_website_builder_websites.id', $id);
            return $this->db->get(db_prefix() . 'uplicrm_website_builder_websites')->row();
        }

        return $this->db->get(db_prefix() . 'uplicrm_website_builder_websites')->result();
    }

    public function add_website($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert(db_prefix() . 'uplicrm_website_builder_websites', $data);
        return $this->db->insert_id();
    }

    public function update_website($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'uplicrm_website_builder_websites', $data);
        return $this->db->affected_rows() > 0;
    }

    public function delete_website($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'uplicrm_website_builder_websites');
        return $this->db->affected_rows() > 0;
    }

    // --- Service Plans ---

    public function get_service_plans($id = false)
    {
        if ($id) {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'uplicrm_website_builder_service_plans')->row();
        }

        return $this->db->get(db_prefix() . 'uplicrm_website_builder_service_plans')->result();
    }

    public function add_service_plan($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert(db_prefix() . 'uplicrm_website_builder_service_plans', $data);
        return $this->db->insert_id();
    }

    public function update_service_plan($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'uplicrm_website_builder_service_plans', $data);
        return $this->db->affected_rows() > 0;
    }

    public function delete_service_plan($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'uplicrm_website_builder_service_plans');
        return $this->db->affected_rows() > 0;
    }

    // --- Plugins ---

    public function get_plugins($id = false)
    {
        if ($id) {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'uplicrm_website_builder_plugins')->row();
        }

        return $this->db->get(db_prefix() . 'uplicrm_website_builder_plugins')->result();
    }

    public function add_plugin($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert(db_prefix() . 'uplicrm_website_builder_plugins', $data);
        return $this->db->insert_id();
    }

    public function update_plugin($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'uplicrm_website_builder_plugins', $data);
        return $this->db->affected_rows() > 0;
    }

    public function delete_plugin($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'uplicrm_website_builder_plugins');
        return $this->db->affected_rows() > 0;
    }
}
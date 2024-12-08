<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Directadmin_account_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get($id = false)
    {
        if ($id) {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'uplicrm_directadmin_accounts')->row();
        }

        return $this->db->get(db_prefix() . 'uplicrm_directadmin_accounts')->result();
    }

    public function get_by_user_id($user_id)
    {
        $this->db->where('user_id', $user_id);
        return $this->db->get(db_prefix() . 'uplicrm_directadmin_accounts')->row();
    }

    public function add($data)
    {
        $this->db->insert(db_prefix() . 'uplicrm_directadmin_accounts', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'uplicrm_directadmin_accounts', $data);
        return $this->db->affected_rows() > 0;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'uplicrm_directadmin_accounts');
        return $this->db->affected_rows() > 0;
    }
}
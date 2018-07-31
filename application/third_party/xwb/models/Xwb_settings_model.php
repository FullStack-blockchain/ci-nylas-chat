<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * XWB Purchasing
 *
 * @package     XWB Purchasing
 * @author      Jay-r Simpron
 * @copyright   Copyright (c) 2017, Jay-r Simpron
 */


/**
 * Main model class for Settings
 */
class Xwb_settings_model extends Xwb_custom_model
{
    /**
     * Run parent construct
     *
     * @return Null
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Update general settings
     *
     * @param string $name
     * @param type|string $value
     * @return boolean
     */
    public function updateSettings($name, $value = "")
    {
        //find settings in database
        $res = $this->db->get_where('settings', array('name'=>$name));
        if ($res->num_rows()>0) {// update settings
            $this->db->where('name', $name);
            $res = $this->db->update('settings', array('description' => $value));
        } else {
            $res = $this->db->insert('settings', array('name' => $name, 'description' =>$value));
        }

        return $res;
    }


    /**
     * Get all status
     *
     * @return object
     */
    public function getStatus()
    {
        return $this->db->get('status');
    }

    /**
     * Delete status
     *
     * @return integer
     */
    public function deleteStatus()
    {
        $id = $this->input->post('id');
        $this->db->where('id', $id);
        $this->db->delete('status');
        return $this->db->affected_rows();
    }

    /**
     * Check status duplicate number
     *
     * @param  string  $status_name   [Status Name]
     * @param  integer $status_number [Status Number]
     * @param  integer $except [Status ID]
     * @return integer
     */
    public function checkStatusDuplicateNumber(
        $status_name = '',
        $status_number = 0,
        $except = null
    ) {
        $this->db->select('id')
            ->from('status')
            ->where('status_name', $status_name)
            ->where('status_number', $status_number)
            ->where('id <>', $except);
        $res = $this->db->get();
        return $res->num_rows();
    }
}

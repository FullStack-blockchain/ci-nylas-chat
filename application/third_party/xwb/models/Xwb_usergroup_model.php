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
 * User Group model class for Users
 */
class Xwb_usergroup_model extends Xwb_custom_model
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
     * Get user groups
     *
     * @return type
     */
    public function getUGroups()
    {
        return $this->db->get('groups');
    }


    /**
     * Get single user group
     *
     * @param type $group_id
     * @return array
     */
    public function getUGroup($group_id)
    {
        return $this->db->get_where('groups', array('id'=>$group_id));
    }


    /**
     * Delete user group
     *
     * @param int $group_id
     * @return int
     */
    public function deleteGroup($group_id)
    {
        $this->db->where('id', $group_id);
        $this->db->delete('groups');
        return $this->db->affected_rows();
    }
}

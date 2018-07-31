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
 * Main model class for Users
 */
class Xwb_user_model extends Xwb_custom_model
{
    /**
     * Run parent constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    
    /**
     * Get all users
     *
     * @return array
     */
    public function getUsers()
    {
        $this->db->select('u.*,CONCAT(up.first_name, " ", up.last_name) as full_name, up.first_name, up.last_name, g.id AS group_id, g.name, g.description, b.description AS branch, d.description AS department, up.branch as branch_id, up.department as department_id, up.phone_number as phone')
                ->from('users u')
                ->join('users_profile up', 'u.id = up.user_id', 'left')
                ->join('users_groups ug', 'u.id = ug.user_id', 'left')
                ->join('groups g', 'ug.group_id = g.id', 'left')
                ->join('branches b', 'up.branch = b.id', 'left')
                ->join('department d', 'up.department = d.id', 'left');
        return $this->db->get();
    }


    /**
     * Get single user
     *
     * @param type|null $user_id
     * @return array
     */
    public function getUser($user_id = null)
    {
                $this->db->select('u.*,CONCAT(up.first_name, " ", up.last_name) as full_name, up.first_name, up.last_name, ug.group_id as group, d.id AS department, d.description AS dep_description, d.name AS dep_name,up.head as department_head, g.name as group_name,g.description as group_description, b.description as branch_description, up.branch as branch_id, up.department as department_id, up.picture_path, up.user_id, up.nick_name, up.mobile_number, up.phone_number as phone')
                ->from('users u')
                ->join('users_profile up', 'u.id = up.user_id', 'left')
                ->join('users_groups ug', 'u.id = ug.user_id', 'left')
                ->join('groups g', 'ug.group_id = g.id', 'left')
                ->join('branches b', 'up.branch = b.id', 'left')
                ->join('department d', 'up.department = d.id', 'left')
                ->where('u.id', $user_id);
                return $this->db->get();
    }


    /**
     * Update user
     *
     * @param int $user_id
     * @param type|array $data
     * @return int
     */
    public function updateUser($user_id, $data = array())
    {
            $this->db->where('id', $user_id);
            $this->db->update('users', $data);
            return $this->db->affected_rows();
    }

    /**
     * Update user group
     *
     * @param int $user_id
     * @param array $data
     * @return int
     */
    public function updateUserGroup($user_id, $data = array())
    {
        $this->db->where('user_id', $user_id);
        $this->db->update('users_groups', $data);
        return $this->db->affected_rows();
    }


    /**
     * Get users by group name
     *
     * @param string $group_name
     * @return array
     */
    public function getUsersByGroup($group_name)
    {
        $this->db->select('u.*,
            g.name,
            up.head,
            up.first_name,
            up.last_name,
            up.nick_name')
                ->from('users u')
                ->join('users_profile up', 'u.id = up.user_id', 'left')
                ->join('users_groups ug', 'u.id = ug.user_id', 'left')
                ->join('groups g', 'ug.group_id = g.id', 'left')
                ->where('g.name', $group_name);
        return $this->db->get();
    }


    /**
     * Get user by department name
     *
     * @param string $dep_name
     * @return array
     */
    public function getUserByDepartment($dep_name)
    {
        $this->db->select('u.*,
            d.name,
            up.first_name,
            up.last_name,
            up.nick_name')
                ->from('users u')
                ->join('users_profile up', 'u.id = up.user_id', 'left')
                ->join('department d', 'up.department = d.id', 'left')
                ->where('d.name', $dep_name);
        return $this->db->get();
    }


    /**
     * Get head department users
     * @param string $dep_name
     * @return array
     */
    public function getHeadDepartmentUsers($dep_name)
    {
        $this->db->select('u.*,
            d.name,
            up.first_name,
            up.last_name,
            up.nick_name')
                ->from('users u')
                ->join('users_profile up', 'u.id = up.user_id', 'left')
                ->join('department d', 'up.department = d.id', 'left')
                ->join('users_groups ug', 'u.id = ug.user_id', 'left')
                ->where('up.head', 1)
                ->where('d.name', $dep_name);
        return $this->db->get();
    }

    /**
     * Get all head department users
     *
     * @return array
     */
    public function getAllHeadDepartmentUsers()
    {
        $this->db->select('u.*,
            d.name,
            d.description, 
            up.first_name,
            up.last_name,
            up.nick_name')
                ->from('users u')
                ->join('users_profile up', 'u.id = up.user_id', 'left')
                ->join('department d', 'up.department = d.id', 'left')
                //->join('users_groups ug', 'u.id = ug.user_id', 'left')
                ->where('up.head', 1);
        return $this->db->get();
    }

    /**
     * Get canvasser
     *
     * @param int $user_id
     * @return array
     */
    public function getCanvasser($user_id = 0)
    {
        $this->db->select('u.*, 
            ug.group_id as group,
            d.id AS department,
            up.first_name,
            up.last_name,
            up.nick_name')
        ->from('users u')
        ->join('users_profile up', 'u.id = up.user_id', 'left')
        ->join('users_groups ug', 'u.id = ug.user_id', 'left')
        ->join('groups g', 'ug.group_id = g.id', 'left')
        ->join('branches b', 'up.branch = b.id', 'left')
        ->join('department d', 'up.department = d.id', 'left')
        ->where('u.id', $user_id)
        ->where('g.name', 'canvasser');
        return $this->db->get();
    }
}

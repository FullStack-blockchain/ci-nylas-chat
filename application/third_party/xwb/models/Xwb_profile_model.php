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
 * Profile model class for Property
 */
class Xwb_profile_model extends Xwb_custom_model
{

    /**
     * Run parent constructor
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Update user profile
     *
     * @param  int $user_id User ID
     * @param  array $data    user info
     * @return int
     */
    public function updateUserProfile($user_id, $data)
    {
        $this->db->where('user_id', $user_id);
        $res = $this->db->get('users_profile');
        if ($res->num_rows()==0) {
            $data['user_id'] = $user_id;
            $this->db->insert('users_profile', $data);
        } else {
            $this->db->where('user_id', $user_id);
            $res = $this->db->update('users_profile', $data);
        }

        return $this->db->affected_rows();
    }
}

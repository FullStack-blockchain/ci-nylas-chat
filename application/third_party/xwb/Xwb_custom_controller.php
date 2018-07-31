<?php (defined('BASEPATH')) or exit('No direct script access allowed');
/**
 * Custom class for users additional method
 *
 * XWB Purchasing
 *
 * @package     XWB Purchasing
 * @author      Jay-r Simpron
 * @copyright   Copyright (c) 2017, Jay-r Simpron
 */



/**
 * If you have another library that is needed to extend
 * before the CI_Controller, just replace the CI_Controller class with
 * your class
 */
class Xwb_custom_controller extends CI_Controller
{
    /**
     * Class constructor
     */
    function __construct()
    {
        parent::__construct();
        
        if (!$this->db->table_exists('settings')) {
            show_error('Configure the database. <br /><a href="'.base_url('install').'">Click here to install the system</a>');
        }

        if (!$this->ion_auth->logged_in()) {
            redirect('user/auth/login', 'refresh');
        }

        $this->setUser();
    }

    /**
     * Set the user ID and group ID
     *
     * @return null
     */
    protected function setUser()
    {
        $user_id = $this->ion_auth->get_user_id();
        $groups = $this->ion_auth->get_users_groups();

        $group_ids = [];
        foreach ($groups->result() as $key => $value) {
            $group_ids[] = $value->id;
        }


        $this->user_id = $user_id;
        $this->group_ids = $group_ids;
    }
}

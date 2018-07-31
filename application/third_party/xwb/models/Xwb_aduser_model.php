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
 * Main model class for Aduser
 */
class Xwb_aduser_model extends Xwb_custom_model
{

    /**
     * Run parent constructor
     */
    public function __construct()
    {
        parent::__construct();
    }



    /**
     * Check if request already exists in canvasser
     *
     * @param int $request_id
     * @return boolean
     */
    public function reqExists($request_id)
    {
        $res = $this->get_where('canvass', array('request_id' => $request_id));
        if ($res->num_rows()>0) {
            $exists = true;
        } else {
            $exists = false;
        }

        return $exists;
    }


    /**
     * Get canvass by request id
     *
     * @param int $request_id
     * @return object
     */
    public function getCanvassByRequest($request_id)
    {
        return $this->db->get_where('canvass', array('request_id' => $request_id));
    }

    /**
     * Get canvass assigned requests
     *
     * @param int $user_id
     * @return object
     */
    public function getCanvassAssignedRequest($user_id)
    {
        $this->db->select('c.*, r.request_name, r.priority_level, r.date_needed, r.id as request_id')
                ->from('canvass c')
                ->join('request_list r', 'c.request_id = r.id', 'left')
                ->where('c.user_id', $user_id);

        return $this->db->get();
    }

    
    /**
     * Get canvass
     *
     * @param int $canvass_id
     * @return object
     */
    public function getCanvass($canvass_id)
    {
        $this->db->select('c.*, r.request_name, r.priority_level, r.date_needed, r.id as request_id, r.purpose')
                ->from('canvass c')
                ->join('request_list r', 'c.request_id = r.id', 'left')
                ->where('c.id', $canvass_id);
        return $this->db->get();
    }
}

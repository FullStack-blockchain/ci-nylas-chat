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
 * Main model class for Board
 */
class Xwb_board_model extends Xwb_custom_model
{

    /**
     * Run parent constructor
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Get All Board approval request
     *
     * @return object
     */
    public function getBoardApprovals()
    {
        $this->db->select('b.*, r.request_name,up.first_name,up.last_name, r.priority_level, r.date_needed, r.id as request_id')
                ->from('board_approval b')
                ->join('request_list r', 'b.request_id = r.id', 'left')
                ->join('users_groups ug', 'r.user_id = ug.user_id', 'left')
                ->join('users u', 'r.user_id = u.id', 'left')
                ->join('users_profile up', 'r.user_id = up.user_id', 'left')
                ->join('department d', 'up.department = d.id', 'left');
        return $this->db->get();
    }


    /**
     * Get single board request approval
     *
     * @param int $id
     * @return object
     */
    public function getBoardApproval($id)
    {
        return $this->db->get_where('board_approval', array('id'=>$id));
    }


    /**
     * Get board by request id
     *
     * @param int $request_id
     * @return object
     */
    public function getBoardByRequest($request_id)
    {
        return $this->db->get_where('board_approval', array('request_id' => $request_id));
    }


/**
     * Update board status by request id
     *
     * @param int $request_id
     * @param array $db_data
     * @return boolean
     */
    public function updateStatusByRequest($request_id, $db_data)
    {
        $this->db->where('request_id', $request_id);
        return $this->db->update('board_approval', $db_data);
    }


    /**
     * Count request currently in the board approval
     *
     * @return object
     */
    public function countBoardAction()
    {
        $user_id = $this->log_user_data->user_id;
        $status = array(0,2,3);
        $this->db->select('b.id')
                ->from('board_approval b')
                //->where('b.user_id',$user_id)
                ->where_in('b.status', $status);
        return $this->db->get();
    }
}

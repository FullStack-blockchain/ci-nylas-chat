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
 * Main model class for Approval
 */
class Xwb_approval_model extends Xwb_custom_model
{


    /**
     * Run parent constructor
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Get Approval
     *
     * @param type $approval_id
     * @return type
     */
    public function getApproval($approval_id)
    {
        return $this->db->get_where('request_approval', array('id'=>$approval_id));
    }


    /**
     * Get items assigned to head department user
     *
     * @param int $user_id
     * @param int $request_id
     * @return array
     */
    public function getItemsForApprovalPerHead($user_id, $request_id = 0)
    {
        $this->db->select('ia.*, pi.product_name,pi.quantity');
        $this->db->from('items_approval ia');
        $this->db->join('po_items pi', 'ia.item_id = pi.id', 'left');
        $this->db->where('ia.user_id', $user_id);
        
        if ($request_id != 0) {
            $this->db->where('ia.request_id', $request_id);
        }
        
        return $this->db->get();
    }

    /**
     * Get all items approval per request
     *
     * @param int $request_id
     * @return array
     */
    public function getItemsApprovalPerRequest($request_id = 0)
    {
        $this->db->select('ia.*, pi.product_name, pi.product_description, pi.requestor_note, ia.requestor_note as requestor_remarks, pi.quantity, pi.id as pi_id,up.first_name, up.last_name, d.description as user_dept');
        $this->db->from('items_approval ia');
        $this->db->join('po_items pi', 'ia.item_id = pi.id', 'left');
        $this->db->join('users u', 'ia.user_id = u.id', 'left');
        $this->db->join('users_profile up', 'ia.user_id = up.user_id', 'left');
        $this->db->join('department d', 'up.department = d.id', 'left');
        $this->db->where('ia.request_id', $request_id);
                
        return $this->db->get();
    }

    /**
     * Get items approval
     *
     * @param int $approval_id
     * @return array
     */
    public function getReqApprovaltItems($approval_id)
    {
        return $this->db->get_where('items_approval', array('request_approval_id'=>$approval_id));
    }


    /**
     * Check if items or product exists on for approval items
     *
     * @param int $item_approval_id
     * @param int $user_id
     * @return boolean
     */
    public function itemExists($item_approval_id, $user_id)
    {
        $num_rows = $this->db->get_where('items_approval', array('id' => $item_approval_id, 'user_id'=>$user_id));

        if ($num_rows->num_rows()>0) {
            $exists=true;
        } else {
            $exists=false;
        }
        return $exists;
    }

    /**
     * Get items per request
     *
     * @param type $user_id
     * @param type $request_id
     * @return type
     */
    public function getItemsPerRequest($user_id, $request_id)
    {
        $this->db->select("ia.*")
                ->from("items_approval ia")
                ->where("ia.user_id", $user_id)
                ->where("ia.request_id", $request_id);
        return $this->db->get();
    }

    /**
     * Get request approval record
     *
     * @param int $usr_id
     * @param int $request_id
     * @return obj
     */
    public function getRequestApproval($user_id, $request_id)
    {
        return $this->db->get_where('request_approval', array('user_id'=>$user_id, 'request_id' => $request_id));
    }


    /**
     * Get Items Approval
     * @param int $item_approval_id
     * @return int
     */
    public function getItemsApproval($item_approval_id)
    {
        return $this->db->get_where('items_approval', array('id'=>$item_approval_id));
    }


    /**
     * Get all request item status by request id
     * @param int $request_id
     * @return object
     */
    public function getAllRequestItemsStatus($request_id)
    {
        $res = $this->db->get_where('items_approval', array('request_id' => $request_id));
        $status =[];
        if ($res->num_rows()>0) {
            foreach ($res->result() as $key => $value) {
                $status[] = $value->status;
            }
        }

        return array_unique($status);
    }


    /**
     * Update Request Approval status
     * @param int $request_approval_id
     * @param int $status
     * @return bool
     */
    public function updateRequestApprovalStatus($request_approval_id, $status)
    {
        $db_data = array('status' => $status,'date_updated'=>date("Y-m-d H:i:s"));
        $this->db->where('id', $request_approval_id);
        return $this->db->update('request_approval', $db_data);
    }

    /**
     * Update all request approval status
     *
     * @param int $request_id
     * @param int $status
     * @return boolean
     */
    public function updateAllRequestApprovalStatus($request_id, $status)
    {
        $db_data = array('status' => $status,'date_updated'=>date("Y-m-d H:i:s"));
        $this->db->where('request_id', $request_id);
        return $this->db->update('request_approval', $db_data);
    }

    /**
     * Get all new request approval
     *
     * @return object
     */
    public function getNewRequestApproval()
    {
        return $this->db->get_where('request_approval', array('status' => 0));
    }
}

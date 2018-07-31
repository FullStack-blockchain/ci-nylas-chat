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
 * Main model class for Attachment
 */
class Xwb_attachment_model extends Xwb_custom_model
{

    /**
     * Run parent constructor
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Get atachment per PO
     *
     * @param dint $po_id
     * @return object
     */
    public function getPOAttachment($po_id)
    {
        $this->db->select('a.*,u.id as user_id,pi.id as pi_id,r.id as req_id')
                ->from('attachment a')
                ->join('po_items pi', 'a.po_id=pi.id', 'left')
                ->join('request_list r', 'pi.request_id = r.id', 'left')
                ->join('users_groups ug', 'r.user_id = ug.user_id', 'left')
                ->join('users u', 'r.user_id = u.id', 'left')
                ->join('users_profile up', 'r.user_id = up.user_id', 'left')
                ->join('department d', 'up.department = d.id', 'left')
                ->where('a.po_id', $po_id);
        return $this->db->get();
    }


    /**
     * Get Attachment
     *
     * @param int $id
     * @return object
     */
    public function getAttachment($id)
    {
        $this->db->select('a.*,u.id as user_id')
                ->from('attachment a')
                ->join('po_items pi', 'a.po_id=pi.id', 'left')
                ->join('request_list r', 'pi.request_id = r.id', 'left')
                ->join('users_groups ug', 'r.user_id = ug.user_id', 'left')
                ->join('users u', 'r.user_id = u.id', 'left')
                ->join('users_profile up', 'r.user_id = up.user_id', 'left')
                ->join('department d', 'up.department = d.id', 'left')
                ->where('a.id', $id);
        return $this->db->get();
    }
}

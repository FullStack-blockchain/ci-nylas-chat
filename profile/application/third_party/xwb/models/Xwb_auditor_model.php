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
 * Main model class for Auditor
 */
class Xwb_auditor_model extends Xwb_custom_model
{

    /**
     * Run parent constructor
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Get PO items
     *
     * @param int $request_id
     * @return object
     */
    public function getPOItems($request_id)
    {
        $this->db->select('pi.id, po.id as po_id, po.status')
                ->from('po_items pi')
                ->join('purchase_order po', 'pi.po_id = po.id', 'left')
                ->where('pi.request_id', $request_id);
        return $this->db->get();
    }

    /**
     * Count auditor required action
     *
     * @return object
     */
    public function countAuditorAction()
    {
        $user_id = $this->log_user_data->user_id;
        $status = array(0,3);
        $this->db->select('po.id')
                ->from('purchase_order po')
                ->where('po.approve_by', $user_id)
                ->where_in('po.status', $status);
        return $this->db->get();
    }
}

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
 * Main model class for Canvassed
 */
class Xwb_canvassed_model extends Xwb_custom_model
{
    protected $table = "canvassed_prices";
    protected $column_order = "";
    protected $column_search = "";
    protected $order = "";

    /**
     * Run parent constructor
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Get canvassed items
     *
     * @param int $item_id
     * @return object
     */
    public function getCanvassedItems($item_id)
    {
        $this->db->select('cp.*, s.supplier_name')
                ->from('canvassed_prices cp')
                ->join('po_items pi', 'cp.item_id = pi.id', 'left')
                ->join('supplier s', 'cp.supplier_id = s.id', 'left')
                ->where('cp.item_id', $item_id);
        return $this->db->get();
    }


    /**
     * Get canvassed price
     *
     * @param int $cp_id
     * @return object
     */
    public function getCanvassedPrice($cp_id)
    {
        return $this->db->get_where($this->table, array('id'=>$cp_id));
    }

    /**
     * Delete canvassed Item
     *
     * @param int $canvass_id
     * @return int
     */
    public function deleteCanvassed($canvass_id)
    {
        $this->db->where('id', $canvass_id);
        $this->db->delete('canvassed_prices');
        return $this->db->affected_rows();
    }


    /**
     * Update Canvassed price data
     *
     * @param int $cp_id
     * @param array $db_data
     * @return boolean
     */
    public function updateData($cp_id, $db_data)
    {
        $this->db->where('id', $cp_id);
        return $this->db->update($this->table, $db_data);
    }
}

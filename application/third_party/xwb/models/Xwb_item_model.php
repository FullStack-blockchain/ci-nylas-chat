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
 * Main model class for Item
 */
class Xwb_item_model extends Xwb_custom_model
{
    protected $table = "po_items";
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
     * Get all items
     *
     * @return array
     */
    public function getItems()
    {
        return $this->db->get('po_tems');
    }


    /**
     * Get item
     *
     * @param int $item_id
     * @return object
     */
    public function getItem($item_id)
    {
        return $this->db->get_where('po_items', array('id' => $item_id));
    }

    /**
     * Get approved po items by request id
     *
     * @param int $request_id
     * @return object
     */
    public function getApprovePOItemsByRequest($request_id)
    {
        $this->db->select('pi.*, po.po_num,po.status as po_status, po.delivery_date')
                ->from('po_items pi')
                ->join('request_list r', 'pi.request_id = r.id', 'left')
                ->join('purchase_order po', 'pi.po_id = po.id', 'left')
                ->where('pi.status', 1)
                ->where('r.id', $request_id);
        return $this->db->get();
    }

    /**
     * Get item by canvass id
     *
     * @param int $canvass_id
     * @return boolean
     */
    public function getItemByCanvass($canvass_id)
    {
        $this->db->select('pi.*')
                ->from('po_items pi')
                ->join('request_list r', 'pi.request_id = r.id', 'left')
                ->join('canvass c', 'r.id = c.request_id', 'left')
                ->where('c.id', $canvass_id);
        return $this->db->get();
    }


    /**
     * Update PO Items
     *
     * @param int $pi_id
     * @param array $db_data
     * @return boolean
     */
    public function updateData($pi_id, $db_data)
    {
        $this->db->where('id', $pi_id);
        return $this->db->update($this->table, $db_data);
    }

   /**
     * Get net amount
     * @param int $request_id
     * @param int $canvass_id
     * @return bollean
     */
    public function getNetAmount($request_id, $canvass_id)
    {
        $this->db->select('SUM(pi.unit_price * pi.quantity) as total_amount')
                ->from($this->table.' pi')
                ->where('pi.request_id', $request_id);
        return $this->db->get();
    }


    /**
     * Chech if item exists from po_item table
     * @param  integer $request_id    [Request ID]
     * @param  integer $product_id    [Product ID]
     * @param  string  $product_name  [Product Name]
     * @param  string  $supplier_name [Supplier Name]
     * @return Object                 [Results]
     */
    public function checkItemExistsPICanvassed($request_id = 0, $product_id = 0, $product_name = "", $supplier_name = "")
    {
        $this->db->select('pi.*')
            ->from('po_items pi')
            ->where('pi.request_id', $request_id)
            ->where('pi.product_id', $product_id)
            ->where('pi.product_name', $product_name)
            ->where('pi.supplier', $supplier_name);
        return $this->db->get();
    }

    public function supplierSummary($request_id)
    {
        $this->db->select('pi.id, pi.request_id, pi.supplier, pi.supplier_id, pi.unit_price, pi.quantity, SUM(pi.unit_price * pi.quantity) as total_amount')
                ->from($this->table.' as pi')
                ->where('pi.request_id', $request_id)
                ->where('pi.supplier <>', null)
                ->group_by('pi.supplier');
        return $this->db->get();
    }
}

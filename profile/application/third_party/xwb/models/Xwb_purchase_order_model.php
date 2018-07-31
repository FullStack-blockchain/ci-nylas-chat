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
 * Main model class for Purchase order
 */
class Xwb_purchase_order_model extends Xwb_custom_model
{
    protected $table = "purchase_order";
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
     * Get purchase order by supplier and request
     *
     * @param int $request_id
     * @param int|string $supplier
     * @param string $supp_column
     * @return object
     */
    public function getPOBySupplierRequest($request_id, $supplier, $supp_column = 'supplier_id')
    {
        return $this->db->get_where('purchase_order', array('request_id'=>$request_id, $supp_column=>$supplier));
    }

    /**
     * Get unapprove purchase order by supplier and request
     *
     * @param int $request_id
     * @param int|string $supplier
     * @param string $supp_column
     * @return object
     */
    public function getUnapprovePOBySupplierRequest($request_id, $supplier, $supp_column = 'supplier_id')
    {
        return $this->db->get_where('purchase_order', array(
                'request_id'=>$request_id,
                'status'=>0,
                $supp_column=>$supplier,
            ));
    }

    /**
     * Get single purcahse order
     *
     * @param int $po_id
     * @return object
     */
    public function getPO($po_id)
    {
        $this->db->select('po.po_num, po.pr_number, po.rr_num, po.delivery_date, po.date_issue, po.vendor_name,po.id, po.status, po.auditor_remarks, po.date_updated, po.request_id, po.supplier_invoice, po.payment_terms, po.warranty_condition, po.pd_remarks, po.approve_by, po.prepared_by as prepared_by_id, r.request_name, CONCAT(up.first_name, " ", up.last_name) AS auditor,CONCAT(up1.first_name, " ", up1.last_name) AS prepared_by')
                ->from('purchase_order po')
                ->join('request_list r', 'po.request_id = r.id')
                ->join('users_profile up', 'po.approve_by = up.user_id')
                ->join('users_profile up1', 'po.prepared_by = up1.user_id')
                ->where('po.id', $po_id);
        return $this->db->get();
    }


    public function getPOs()
    {
        $this->db->select('po.po_num, po.pr_number, po.vendor_name,po.id, po.status, po.auditor_remarks, po.date_updated, r.request_name, CONCAT(up.first_name, " ", up.last_name) AS auditor')
                ->from('purchase_order po')
                ->join('request_list r', 'po.request_id = r.id')
                ->join('users_profile up', 'po.approve_by = up.user_id');
        return $this->db->get();
    }

    /**
     * Get purchase order by auditor
     *
     * @param int $auditor_id
     * @return object
     */
    public function getPObyAuditor($auditor_id)
    {
        $this->column_order = array('po.po_num', 'po.pr_number', 'r.request_name', 'po.vendor_name', 'po.status','po.pd_remarks');

        $this->column_search = array('po.po_num', 'po.pr_number', 'r.request_name', 'po.vendor_name', 'po.status','po.pd_remarks');

        $this->order = array('r.id' =>'asc');


        $this->db->select('po.po_num, po.pr_number, po.vendor_name,po.id,po.status,po.pd_remarks, po.date_updated, r.request_name')
                ->from('purchase_order po')
                ->join('request_list r', 'po.request_id = r.id')
                ->where('po.approve_by', $auditor_id);
        $this->searchOrder();
    }

    /**
     * Update puchase order
     *
     * @param int $po_id
     * @param type|array $data
     * @return boolean
     */
    public function updatePO($po_id, $data = array())
    {
        $this->db->where('id', $po_id);
        return $this->db->update('purchase_order', $data);
    }

    /**
     * Get all PO by request ID
     *
     * @param int $request_id
     * @return object
     */
    public function getPOByRequest($request_id)
    {
        return $this->db->get_where('purchase_order', array('id' =>$request_id));
    }


    /**
     * Get approved po by request id
     *
     * @param int $request_id
     * @return object
     */
    public function getApprovedPOByRequest($request_id)
    {
        $this->db->select('po.id, po.po_num, po.delivery_date')
                ->from('purchase_order po')
                ->where('po.request_id', $request_id);
        return $this->db->get();
    }


    /**
     * Get undone approved po by request id
     *
     * @param int $request_id
     * @return object
     */
    public function getUndoneApprovedPOByRequest($request_id)
    {
        $this->db->select('po.id, po.po_num, po.delivery_date')
                ->from('purchase_order po')
                ->join('po_items pi', 'po.id = pi.po_id', 'left')
                ->where('pi.status', 1)
                ->where('po.status', 1)
                ->where('po.request_id', $request_id)
                ->group_by('po.id');
        return $this->db->get();
    }


    /**
     * Use for search and order in datatable
     *
     * @return void
     */
    public function searchOrder()
    {
        $i = 0;
     
        foreach ($this->column_search as $item) { // loop column
            if ($this->input->get('search')['value']) { // if datatable send GET for search
                if ($i===0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $this->input->get('search')['value']);
                } else {
                    $this->db->or_like($item, $this->input->get('search')['value']);
                }
 
                if (count($this->column_search) - 1 == $i) { //last loop
                    $this->db->group_end(); //close bracket
                }
            }
            $i++;
        }
         
        if (isset($_GET['order'])) { // here order processing
            $this->db->order_by($this->column_order[$this->input->get('order')['0']['column']], $this->input->get('order')['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }


    /**
     * count filtered results
     * @param string $method
     * @param array|array $params
     * @return type
     */
    public function countFiltered($method, $params = array())
    {
        call_user_func_array(array($this,$method), $params);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function getLastPO()
    {
        $this->db->select('po.id')
                ->from('purchase_order po')
                ->order_by('po.id', 'DESC')
                ->limit(1);
        return $this->db->get();
    }
}

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
 * Main model class for Reports
 */
class Xwb_reports_model extends Xwb_custom_model
{

    protected $table = "";
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
     * Get request data for report
     *
     * @param string $branches
     * @param string $department
     * @param int|string $year
     * @param int|string $month
     * @param string $sy
     * @return object
     */
    public function getRequestReports($branches = "", $department = "", $year = "", $month = "", $sy = "")
    {

        $this->column_order = array('r.id','r.request_name','r.total_amount','full_name','department','campus','r.status');
        $this->column_search = array('r.id','r.request_name','r.total_amount','CONCAT(up.first_name, " ", up.last_name)','d.description','b.description','r.status');
        $this->order = array('r.id' =>'asc');

        $this->db->select('r.id, r.request_name, r.total_amount, r.date_created, r.status, CONCAT(up.first_name, " ", up.last_name) AS full_name, d.description AS department, b.description AS campus')
                ->from('request_list r')
                ->join('users u', 'r.user_id = u.id', 'left')
                ->join('users_profile up', 'r.user_id = up.user_id', 'left')
                ->join('department d', 'up.department = d.id', 'left')
                ->join('branches b', 'up.branch = b.id', 'left');
        if ($branches!= "") {
            $this->db->where('b.id', $branches);
        }
        if ($department!= "") {
            $this->db->where('d.id', $department);
        }
        if ($year!= "") {
            $this->db->where('YEAR(r.date_created)', $year);
        }
        if ($month!= "") {
            $this->db->where('MONTH(r.date_created)', $month);
        }

        if ($sy!= "") {
            $sy = explode('-', $sy);
            list($startY,$endY) = $sy;
            $startDate = date('Y-m-d H:i:s', strtotime($startY.'-06-01 00:00:00'));
            $endDate = date('Y-m-t H:i:s', strtotime($endY.'-05-20 12:59:59'));
            $this->db->where('DATE(r.date_created) >=', $startDate);
            $this->db->where('DATE(r.date_created) <=', $endDate);
        }

        $this->searchOrder();
    }



    /**
     * Get item data for report
     *
     * @param string $branches
     * @param string $department
     * @param int|string $year
     * @param int|string $month
     * @param string $sy
     * @return void
     */
    public function getItemReports($branches = "", $department = "", $year = "", $month = "", $sy = "")
    {


        $this->column_order = array('r.id','r.request_name','pi.product_name','pi.quantity','pi.unit_price','(pi.quantity * pi.unit_price)','pi.supplier', 'full_name','campus','department');
        $this->column_search = array('r.id','r.request_name','pi.product_name','pi.quantity','pi.unit_price','pi.supplier','CONCAT(up.first_name, " ", up.last_name)','b.description','d.description');
        $this->order = array('r.id' =>'asc');

        $this->db->select('pi.id, r.request_name, pi.product_name, pi.quantity, pi.unit_price, pi.supplier, pi.status, pi.date_created, pi.date_updated, CONCAT(up.first_name, " ", up.last_name) AS full_name, d.description AS department, b.description AS campus')
                ->from('po_items pi')
                ->join('request_list r', 'pi.request_id = r.id', 'left')
                ->join('users u', 'r.user_id = u.id', 'left')
                ->join('users_profile up', 'r.user_id = up.user_id', 'left')
                ->join('department d', 'up.department = d.id', 'left')
                ->join('branches b', 'up.branch = b.id', 'left');
        if ($branches!= "") {
            $this->db->where('b.id', $branches);
        }
        if ($department!= "") {
            $this->db->where('d.id', $department);
        }
        if ($year!= "") {
            $this->db->where('YEAR(r.date_created)', $year);
        }
        if ($month!= "") {
            $this->db->where('MONTH(r.date_created)', $month);
        }
        if ($sy!= "") {
            $sy = explode('-', $sy);
            list($startY,$endY) = $sy;
            $startDate = date('Y-m-d H:i:s', strtotime($startY.'-06-01 00:00:00'));
            $endDate = date('Y-m-t H:i:s', strtotime($endY.'-05-20 12:59:59'));
            $this->db->where('DATE(r.date_created) >=', $startDate);
            $this->db->where('DATE(r.date_created) <=', $endDate);
        }

        $this->searchOrder();
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
     *
     * @param string $method
     * @param array $params
     * @return int
     */
    public function countFiltered($method, $params)
    {
        call_user_func_array(array($this,$method), $params);
        $query = $this->db->get();
        return $query->num_rows();
    }

    /**
     * Get data reports for Purchase Orders
     *
     * @param type|string $branches
     * @param type|string $department
     * @param type|string $year
     * @param type|string $month
     * @param string $sy
     * @return void
     */
    public function getPOReports($branches = "", $department = "", $year = "", $month = "", $sy = "")
    {

        $this->column_order = array('po.po_num','po.pr_number','r.request_name','po.vendor_name','po.payment_terms','po.warranty_condition','po.total_amount','requisitioner','certified_by',null,'preparedby');
        $this->column_search = array('po.po_num','po.pr_number','r.request_name','po.vendor_name','po.payment_terms','po.warranty_condition','po.total_amount','CONCAT(up1.first_name, " ", up1.last_name)','CONCAT(up1.first_name, " ", up1.last_name)','CONCAT(up.first_name, " ", up.last_name)');
        $this->order = array('r.id' =>'asc');


        $this->db->select('po.po_num, po.pr_number, po.prepared_by, po.vendor_name, po.payment_terms, po.warranty_condition, po.total_amount, po.date_updated, r.request_name, CONCAT(up.first_name, " ", up.last_name) AS preparedby, CONCAT(up1.first_name, " ", up1.last_name) AS requisitioner, CONCAT(up1.first_name, " ", up1.last_name) AS certified_by')
                ->from('purchase_order po')
                ->join('request_list r', 'po.request_id = r.id', 'left')
                ->join('users_profile up', 'po.prepared_by = up.user_id', 'left')
                ->join('users_profile up1', 'r.user_id = up1.user_id', 'left')
                ->join('users_profile up2', 'po.approve_by = up2.user_id', 'left')
                ->join('department d', 'up1.department = d.id', 'left')
                ->join('branches b', 'up1.branch = b.id', 'left')
                ->where('po.status', 1);
        if ($branches!= "") {
            $this->db->where('b.id', $branches);
        }
        if ($department!= "") {
            $this->db->where('d.id', $department);
        }
        if ($year!= "") {
            $this->db->where('YEAR(r.date_created)', $year);
        }
        if ($month!= "") {
            $this->db->where('MONTH(r.date_created)', $month);
        }
        if ($sy!= "") {
            $sy = explode('-', $sy);
            list($startY,$endY) = $sy;
            $startDate = date('Y-m-d H:i:s', strtotime($startY.'-06-01 00:00:00'));
            $endDate = date('Y-m-t H:i:s', strtotime($endY.'-05-20 12:59:59'));
            $this->db->where('DATE(r.date_created) >=', $startDate);
            $this->db->where('DATE(r.date_created) <=', $endDate);
        }

        $this->searchOrder();
    }
}

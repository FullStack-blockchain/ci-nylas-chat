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
 * Main model class for Property
 */
class Xwb_property_model extends Xwb_custom_model
{

    protected $table = "property";
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



    public function getProperties()
    {
        $this->column_order = array('po.pr_number','po.po_num','r.request_name','full_name','d.description','b.description','r.purpose','r.date_created','p.eta','p.date_delivered','p.officer_note',null,'p.status',null);

        $this->column_search = array('po.pr_number','po.po_num','r.request_name','CONCAT(up.first_name, " ", up.last_name)','d.description','b.description','r.purpose','r.date_created','p.eta','p.date_delivered','p.officer_note','p.status');

        $this->order = array('p.id' =>'asc');

        $this->db->select('p.*, r.request_name,po.po_num, po.pr_number, r.date_created as date_requested, r.purpose, CONCAT(up.first_name, " ", up.last_name) as full_name, d.description as department, b.description as campus')
                ->from('property p')
                ->join('request_list r', 'p.request_id = r.id', 'left')
                ->join('purchase_order po', 'p.po_id = po.id', 'left')
                ->join('users u', 'r.user_id = u.id', 'left')
                ->join('users_profile up', 'r.user_id = up.user_id', 'left')
                ->join('department d', 'up.department = d.id', 'left')
                ->join('branches b', 'up.branch = b.id', 'left');

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


    /**
     * Count property that need action
     *
     * @return object
     */
    public function countPropertyAction()
    {
        $user_id = $this->log_user_data->user_id;
        $status = array(0);
        $this->db->select('p.id')
                ->from('property p')
                ->where_in('p.status', $status);
        return $this->db->get();
    }
}

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
 * Main model class for Budget
 */
class Xwb_budget_model extends Xwb_custom_model
{


    /**
     * Run parent constructor
     */
    public function __construct()
    {
        parent::__construct();
    }




    /**
     * Get budget by request id
     *
     * @param int $request_id
     * @return object
     */
    public function getBudgetByRequest($request_id)
    {
        $this->db->select('b.*, CONCAT(up.first_name, " ", up.last_name) as full_name')
                ->from('budget_approval b')
                ->join('users u', 'b.user_id = u.id', 'left')
                ->join('users_profile up', 'b.user_id = up.user_id', 'left')
                ->where('b.request_id', $request_id);
        return $this->db->get();
    }


    /**
     * Get budget request approval on each budget user
     *
     * @param int $user_id
     * @return void
     */
    public function getBudgetRequestApproval($user_id)
    {
        $this->column_order = array('r.id','r.request_name','full_name',null,'b.total_amount','r.date_needed',null,'b.status');

        $this->column_search = array('r.id','r.request_name','CONCAT(up.first_name, " ", up.last_name)','b.total_amount','r.date_needed','b.status');

        $this->order = array('r.id' =>'asc');


        $this->db->select('b.*, r.request_name,CONCAT(up.first_name, " ", up.last_name) as full_name, r.date_needed, r.id as request_id')
                ->from('budget_approval b')
                ->join('request_list r', 'b.request_id = r.id', 'left')
                ->join('users_groups ug', 'r.user_id = ug.user_id', 'left')
                ->join('users u', 'r.user_id = u.id', 'left')
                ->join('users_profile up', 'r.user_id = up.id', 'left')
                ->join('department d', 'up.department = d.id', 'left')
                ->where('b.user_id', $user_id);
        $this->searchOrder();
    }

    /**
     * Get budget request approval
     *
     * @param int $id
     * @return object
     */
    public function getBudgetReqApproval($id)
    {
        return $this->db->get_where('budget_approval', array('id'=>$id));
    }

    /**
     * Update budget status by request id
     *
     * @param int $request_id
     * @param array $db_data
     * @return boolean
     */
    public function updateStatusByRequest($request_id, $db_data)
    {
        $this->db->where('request_id', $request_id);
        return $this->db->update('budget_approval', $db_data);
    }


    /**
     * Update budget
     *
     * @param int $budget_id
     * @param array $db_data
     * @return boolean
     */
    public function updateBudgetApproval($budget_id, $db_data)
    {
        $this->db->where('id', $budget_id);
        return $this->db->update('budget_approval', $db_data);
    }

    /**
     * Get all items budget approval per request
     *
     * @param int $request_id
     * @return array
     */
    public function getItemsBudgetApprovalPerRequest($request_id = 0)
    {
        $this->db->select('ia.*, pi.product_name, pi.product_description, pi.requestor_note, pi.expenditure, ia.requestor_note as requestor_remarks, pi.quantity, up.first_name, up.last_name, d.description as user_dept');
        $this->db->from('items_approval ia');
        $this->db->join('po_items pi', 'ia.item_id = pi.id', 'left');
        $this->db->join('users u', 'ia.user_id = u.id', 'left');
        $this->db->join('users_profile up', 'ia.user_id = up.user_id', 'left');
        $this->db->join('department d', 'up.department = d.id', 'left');
        $this->db->where('ia.request_id', $request_id);
                
        return $this->db->get();
    }

    /**
     * Get all request on going for budget approval
     *
     * @return object
     */
    public function getReqToApprove()
    {
        $this->db->select('b.*')
                ->from('budget_approval b')
                ->where('status !=', 1)
                ->where('status !=', 4);
                //->where('status !=', 5);
        return $this->db->get();
    }


    /**
     * Count budget required action
     *
     * @return object
     */
    public function countBudgetAction()
    {
        $user_id = $this->log_user_data->user_id;
        $status = array(0,2,3,6);
        $this->db->select('b.id')
                ->from('budget_approval b')
                ->where('b.user_id', $user_id)
                ->where_in('b.status', $status);
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
}

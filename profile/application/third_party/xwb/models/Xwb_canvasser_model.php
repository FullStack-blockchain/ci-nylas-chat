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
 * Main model class for Canvasser
 */
class Xwb_canvasser_model extends Xwb_custom_model
{
    protected $table = "canvass";
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
     * Check if request already exists in canvasser
     *
     * @param int $request_id
     * @return boolean
     */
    public function reqExists($request_id)
    {
        $res = $this->get_where('canvass', array('request_id' => $request_id));
        if ($res->num_rows()>0) {
            $exists = true;
        } else {
            $exists = false;
        }

        return $exists;
    }


    /**
     * Get canvass by request id
     *
     * @param int $request_id
     * @return object
     */
    public function getCanvassByRequest($request_id)
    {
        $this->db->select('c.*, CONCAT(up.first_name, " ", up.last_name) as full_name')
                ->from('canvass c')
                ->join('users u', 'c.user_id = u.id', 'left')
                ->join('users_profile up', 'c.user_id = up.user_id', 'left')
                ->where('c.request_id', $request_id);
        return $this->db->get();
    }

    /**
     * Get canvass assigned requests
     *
     * @param int $user_id
     * @return void
     */
    public function getCanvassAssignedRequest($user_id)
    {

        $this->column_order = array('r.id','r.request_name','r.date_created','r.date_needed',null,'c.total_amount',null,'c.status');

        $this->column_search = array('r.id','r.request_name','r.date_created','r.date_needed','c.total_amount','c.status');

        $this->order = array('r.id' =>'asc');


        $this->db->select('c.*, r.request_name, r.date_needed, r.id as request_id')
                ->from('canvass c')
                ->join('request_list r', 'c.request_id = r.id', 'left')
                ->where('c.user_id', $user_id);

        $this->searchOrder();
    }

    
    /**
     * Get canvass
     *
     * @param int $canvass_id
     * @return object
     */
    public function getCanvass($canvass_id)
    {
        $this->db->select('c.*, r.request_name, r.date_needed, r.id as request_id, r.purpose')
                ->from('canvass c')
                ->join('request_list r', 'c.request_id = r.id', 'left')
                ->where('c.id', $canvass_id);
        return $this->db->get();
    }

    /**
     * Get all request to canvass
     *
     * @return object
     */
    public function getRequestToCanvass()
    {
        $this->db->where_in('status', [1,2,5,6,8]);
        return $this->db->get('canvass');
    }

    /**
     * Update canvass
     *
     * @param int $canvass_id
     * @param type|array $db_data
     * @return int
     */
    public function updateCanvass($canvass_id, $db_data = array())
    {
        $this->db->where('id', $canvass_id);
        $this->db->update('canvass', $db_data);
        return $this->db->affected_rows();
    }



    /**
     * Count Canvass action
     *
     * @return object
     */
    public function countCanvassAction()
    {
        $user_id = $this->log_user_data->user_id;
        $status = array(1,2,6,8);
        $this->db->select('c.id')
                ->from('canvass c')
                ->where('c.user_id', $user_id)
                ->where_in('c.status', $status);
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

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
 * Main model class for History
 */
class Xwb_history_model extends Xwb_custom_model
{

    protected $table = "transaction_history";
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



    public function getHistories()
    {

        $this->column_order = array('r.id','full_name','r.request_name','th.description','th.date_created');

        $this->column_search = array('r.id','CONCAT(up.first_name, " ",up.last_name)','r.request_name','th.description','th.date_created');

        $this->order = array('r.id' =>'asc');



        $this->db->select('r.request_name, r.id as req_id, th.name, th.description, th.status, th.date_created, th.id, CONCAT(up.first_name, " ",up.last_name) as full_name')
                ->from('transaction_history th')
                ->join('request_list r', 'th.ref_id = r.id', 'left')
                ->join('users u', 'th.user_id = u.id', 'left')
                ->join('users_profile up', 'th.user_id = up.user_id', 'left');
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
}

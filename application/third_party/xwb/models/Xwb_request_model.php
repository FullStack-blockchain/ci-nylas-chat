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
 * Main model class for Request
 */
class Xwb_request_model extends Xwb_custom_model
{
    protected $table = "request_list";
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
     * Get request
     *
     * @return array
     */
    public function getRequest($request_id)
    {
        
        $this->db->select('c.total_amount,c.id as canvas_id, r.*,CONCAT(up.first_name, " ", up.last_name) as full_name')
                ->from('request_list r')
                ->join('users u', 'r.user_id = u.id', 'left')
                ->join('users_profile up', 'r.user_id = up.user_id', 'left')
                ->join('canvass c', 'r.id = c.request_id', 'left')
                ->where('r.id', $request_id);
        return $this->db->get();
    }



    /**
     * Get all request by user
     *
     * @param int $user_id
     * @return array
     */
    public function getRequestListByUser($user_id)
    {
        $this->db->select('r.*')
                ->from('request_list r')
                ->where('r.archive', 0)
                ->where('r.user_id', $user_id);
        return $this->db->get();
    }



    /**
     * Get request list for approval by head department
     *
     * @param int $user_id
     * @param int $group_id
     * @return void
     */
    public function getReqForApproval($user_id, $department_id)
    {


        $this->column_order = array('r.id','r.request_name','r.date_created','full_name','r.purpose', null,'r.date_needed',null,'r.status');

        $this->column_search = array('r.id','r.request_name','r.date_created','CONCAT(up.first_name, " ", up.last_name)','r.purpose','r.date_needed','r.status');

        $this->order = array('r.id' =>'asc');

        $this->db->select('r.*,ra.id as approval_id,ra.status, ra.request_id, CONCAT(up.first_name, " ", up.last_name) AS full_name')
                ->from('request_approval ra')
                ->join('request_list r', 'ra.request_id = r.id', 'left')
                ->join('users_groups ug', 'r.user_id = ug.user_id', 'left')
                ->join('users u', 'r.user_id = u.id', 'left')
                ->join('users_profile up', 'r.user_id = up.user_id', 'left')
                ->join('department d', 'up.department = d.id', 'left')
                ->where('ra.user_id', $user_id);
        $this->searchOrder();
    }

    /**
     * Get request list for approval by request id
     *
     * @param int $request_id
     * @return array
     */
    public function getReqForApprovalByRequest($request_id)
    {
        $this->db->select('r.*,ra.id as approval_id,ra.status, ra.request_id, up.first_name, up.last_name,CONCAT(up1.first_name, " ", up1.last_name) AS head_name, d1.description as head_department')
                ->from('request_approval ra')
                ->join('request_list r', 'ra.request_id = r.id', 'left')
                ->join('users u1', 'ra.user_id = u1.id', 'left')
                ->join('users_profile up1', 'ra.user_id = up1.user_id', 'left')
                ->join('department d1', 'up1.department = d1.id', 'left')
                ->join('users_groups ug', 'r.user_id = ug.user_id', 'left')
                ->join('users u', 'r.user_id = u.id', 'left')
                ->join('users_profile up', 'r.user_id = up.user_id', 'left')
                ->join('department d', 'up.department = d.id', 'left')
                ->where('r.id', $request_id);
        return $this->db->get();
    }


    /**
     * Get all staff request
     *
     * @return void
     */
    public function getStaffRequest($user_id, $branch, $department)
    {
        //CONCAT("PR-",LPAD(r.id, 8, "0")) as id

        
        $this->column_order = array('r.id','r.request_name','full_name','department','branch','r.date_created','r.purpose',null,'r.date_needed','r.status');

        $this->column_search = array('r.id','r.request_name','CONCAT(up.first_name, " ", up.last_name) as full_name','d.description','b.description','r.date_created','r.purpose',null,'r.date_needed','r.status');

        $this->order = array('r.id' =>'asc');


        $this->db->select('r.*,CONCAT(up.first_name, " ", up.last_name) as full_name, up.first_name,up.last_name,d.description as department, b.description as branch, up.branch as branch_id, up.department as department_id')
                ->from('request_list r')
                ->join('users u', 'r.user_id = u.id', 'left')
                ->join('users_profile up', 'r.user_id = up.user_id', 'left')
                ->join('department d', 'up.department = d.id', 'left')
                ->join('branches b', 'up.branch = b.id', 'left')
                ->where('up.department', $department)
                ->where('up.branch', $branch)
                ->where('u.id <>', $user_id);
        $this->searchOrder();
    }

    /**
     * Get all request list in the admin
     *
     * @return void
     */
    public function getAdminRequest()
    {
        
        //CONCAT("PR-",LPAD(r.id, 8, "0")) as id


        $this->column_order = array('r.id','r.request_name','r.date_created','full_name','department','branch','r.purpose',null,'r.date_needed',null,'r.status');

        $this->column_search = array('r.id','r.request_name','r.date_created','CONCAT(up.first_name, " ", up.last_name)','d.description','up.branch','r.purpose','r.date_needed','r.status');

        $this->order = array('r.id' =>'asc');


        $this->db->select('r.*,CONCAT(up.first_name, " ", up.last_name) as full_name, up.first_name,up.last_name,d.description as department, b.description as branch')
                ->from('request_list r')
                ->join('users u', 'r.user_id = u.id', 'left')
                ->join('users_profile up', 'r.user_id = up.user_id', 'left')
                ->join('department d', 'up.department = d.id', 'left')
                ->join('branches b', 'up.branch = b.id', 'left')
                ->where('r.archive', 0);
        $this->searchOrder();
    }



    /**
     * Get all archived request list in the admin
     *
     * @return void
     */
    public function getAdminArchRequest()
    {

        $this->column_order = array('r.id','r.request_name','r.date_created','full_name','department','branch','r.purpose',null,'r.priority_level',null,'r.status');

        $this->column_search = array('r.id','r.request_name','r.date_created','CONCAT(up.first_name, " ", up.last_name)','d.description','up.branch','r.purpose','r.priority_level','r.status');

        $this->order = array('r.id' =>'asc');


        $this->db->select('r.*,CONCAT(up.first_name, " ", up.last_name) as full_name, up.first_name,up.last_name,d.description as department, b.description as branch')
                ->from('request_list r')
                ->join('users u', 'r.user_id = u.id', 'left')
                ->join('users_profile up', 'r.user_id = up.user_id', 'left')
                ->join('department d', 'up.department = d.id', 'left')
                ->join('branches b', 'up.branch = b.id', 'left')
                ->where('r.archive', 1);
        $this->searchOrder();
    }



    /**
     * Delete request
     * @return array
     */
    public function deleteRequest()
    {
        $id = $this->input->post('id');
        $this->db->where('id', $id);
        $this->db->delete('request');
        return $this->db->affected_rows();
    }


    /**
     * Get items per request
     *
     * @param int $request_id
     * @return array
     */
    public function getItemsPerRequest($request_id)
    {
        $this->db->select('pi.*, pc.description as product_category, pt.eta,pt.date_delivered')
                ->from('po_items pi')
                ->join('products p', 'pi.product_id = p.id', 'left')
                ->join('product_categories pc', 'p.product_category = pc.id', 'left')
                ->join('property_item p_i', 'pi.id = p_i.item_id', 'left')
                ->join('property pt', 'p_i.property_id = pt.id', 'left')
                ->where('pi.request_id', $request_id);
        return $this->db->get();
    }

    /**
     * Get Request Items group by request ID
     * @param  integer $request_id Request ID
     * @return object
     */
    public function getItemsPerRequestCanvasser($request_id = 0)
    {
        $this->db->select('pi.*, pc.description as product_category, pt.eta,pt.date_delivered')
                ->from('po_items pi')
                ->join('products p', 'pi.product_id = p.id', 'left')
                ->join('product_categories pc', 'p.product_category = pc.id', 'left')
                ->join('property_item p_i', 'pi.id = p_i.item_id', 'left')
                ->join('property pt', 'p_i.property_id = pt.id', 'left')
                ->where('pi.request_id', $request_id)
                ->group_by('pi.product_name');
        return $this->db->get();
    }


    /**
     * Get Supplier from request item
     *
     * @param  integer $request_id   [Request ID]
     * @param  integer $product_id   [Product ID]
     * @param  string  $product_name [Product Name]
     * @return Object                [Results]
     */
    public function getSuplliersFromRequest($request_id = 0, $product_id = 0, $product_name = "")
    {
        $this->db->select('pi.*')
            ->from('po_items pi')
            ->where('pi.request_id', $request_id)
            ->where('pi.product_id', $product_id)
            ->where('pi.product_name', $product_name)
            ->limit(4);
        return $this->db->get();
    }


    /**
     * Get Supplier from request item
     *
     * @param  integer $request_id   [Request ID]
     * @param  integer $product_id   [Product ID]
     * @param  string  $product_name [Product Name]
     * @return Object                [Results]
     */
    public function getSuplliersFromCanvassed($request_id = 0, $product_id = 0, $product_name = "")
    {
        $this->db->select('pi.*,cp.id as cp_id, cp.canvass_id, cp.item_id, cp.product_id, cp.product_name, cp.supplier, cp.supplier_id, cp.status as cp_status, cp.quantity, cp.price')
            ->from('po_items pi')
            ->join('canvassed_prices cp', 'pi.id = cp.item_id', 'left outer')
            ->where('pi.request_id', $request_id)
            ->where('pi.product_id', $product_id)
            ->where('pi.product_name', $product_name)
            ->limit(4);
        return $this->db->get();
    }

    /**
     * Get items per PO
     *
     * @param int $po_id
     * @return array
     */
    public function getItemsPerPO($po_id)
    {
        return $this->db->get_where('po_items', array('po_id'=>$po_id));
    }

    /**
     * Get request assigned to canvasser
     * @param int $user_id
     * @return array
     */
    public function getRequestByCanvasser($user_id)
    {
        $this->db->select('r.*')
                ->from('request_list r')
                ->where('r.canvasser', $user_id)
                ->where('r.status', 3);
        return $this->db->get();
    }


    /**
     * Update request status
     *
     * @param int $request_id
     * @param int $status
     * @return boolean
     */
    public function updateRequestStatus($request_id, $status)
    {
        $db_data = array('status' => $status,'date_updated'=>date("Y-m-d H:i:s"));
        $this->db->where('id', $request_id);
        return $this->db->update('request_list', $db_data);
    }


    /**
     * Update item
     *
     * @param int $item_id
     * @param array $data
     * @return boolean
     */
    public function updateItem($item_id, $data)
    {
        $this->db->where('id', $item_id);
        return $this->db->update('po_items', $data);
    }


    /**
     * Get all new requests
     *
     * @return object
     */
    public function getNewRequest()
    {
        return $this->db->get_where('request_list', array('status' => 1));
    }


    /**
     * Get PO items by request_id and supplier with column option
     *
     * @param int $request_id
     * @param type|string $supplier
     * @param type|string $supplier_column
     * @return object
     */
    public function getPOItems($request_id, $supplier = '', $supplier_column = 'supplier')
    {
        $this->db->select('pi.*')
                ->from('po_items AS pi')
                ->where('pi.request_id', $request_id)
                ->where('pi.'.$supplier_column, $supplier);

        return $this->db->get();
    }

    /**
     * Get unapprove audit PO items by request_id and supplier with column option
     *
     * @param int $request_id
     * @param type|string $supplier
     * @param type|string $supplier_column
     * @return object
     */
    public function getUnapproveAuditPOItems($request_id, $supplier = '', $supplier_column = 'supplier')
    {
        $this->db->select('pi.*')
                ->from('po_items AS pi')
                ->where('pi.request_id', $request_id)
                ->where('pi.'.$supplier_column, $supplier)
                ->where('pi.status', 0);

        return $this->db->get();
    }

    public function getOngoingRequest()
    {
        $this->db->select('r.date_updated,r.date_created, CONCAT(up.first_name, " ", up.last_name) AS full_name, u.id as user_id, d.description as department, b.description as branch')
                ->from('request_list r')
                ->where('DATE(r.date_created) >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)')
                ->join('users_groups ug', 'r.user_id = ug.user_id', 'left')
                ->join('users u', 'r.user_id = u.id', 'left')
                ->join('users_profile up', 'r.user_id = up.user_id', 'left')
                ->join('department d', 'up.department = d.id', 'left')
                ->join('branches b', 'up.branch = b.id', 'left');
                //->where('r.status <>', 13);
                
        return $this->db->get();
    }


    /**
     * Get Item
     *
     * @param int $item_id
     * @return object
     */
    public function getItem($item_id)
    {
        return $this->db->get_where('po_items', array('id'=>$item_id));
    }


    /**
     * Get Denied Items
     *
     * @param int $request_id
     * @return object
     */
    public function getDeniedItems($request_id)
    {
        $this->db->select('ia.*, CONCAT(up.first_name, " ", up.last_name) AS assigned_to, d.description as department, pi.product_name, pi.product_description, pi.quantity, r.user_id as requisitioner')
                ->from('items_approval ia')
                ->join('po_items pi', 'ia.item_id = pi.id', 'left')
                ->join('request_list r', 'ia.request_id = r.id', 'left')
                ->join('users u', 'ia.user_id = u.id', 'left')
                ->join('users_profile up', 'ia.user_id = up.user_id', 'left')
                ->join('department d', 'up.department = d.id', 'left')
                ->where('ia.request_id', $request_id)
                ->where('ia.status', 2);
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

    /**
     * Check if all item in the request has been approved
     *
     * @param int $request_id
     * @return boolean
     */
    public function requestAuditApproved($request_id)
    {
        $item_type_count = $this->getItemsPerRequest($request_id)->num_rows();
        log_message('info', 'query for items per rquest: '.$this->db->last_query());
        log_message('info', 'count items per request: '.$item_type_count);
        $item_delivery_level = $this->getItemsAuditOnDeliveryLevelMinLevel($request_id)->num_rows();
        log_message('info', 'query for items delivery level: '.$this->db->last_query());
        log_message('info', 'count items delivery level: '.$item_delivery_level);
        

        if ($item_type_count == $item_delivery_level) {
            return true;
        } else {
            return false;
        }
    }


    public function getItemsAuditOnDeliveryLevelMinLevel($request_id)
    {
        $this->db->select('pi.*')
                ->from('po_items pi')
                ->where('pi.request_id', $request_id)
                ->where('pi.status <>', 0)
                ->where('pi.status <>', 1)
                ->where('pi.status <>', 2);
        return $this->db->get();
    }


    /**
     * Get item unapproved from audit by request id
     *
     * @param int $request_id
     * @return object
     */
    public function getItemAuditUnapproveByRequest($request_id)
    {
        $this->db->select('pi.*, pc.description as product_category, pt.eta,pt.date_delivered')
                ->from('po_items pi')
                ->join('products p', 'pi.product_id = p.id', 'left')
                ->join('product_categories pc', 'p.product_category = pc.id', 'left')
                ->join('property_item p_i', 'pi.id = p_i.item_id', 'left')
                ->join('property pt', 'p_i.property_id = pt.id', 'left')
                ->where('pi.request_id', $request_id)
                ->where('pi.status', 0);
        return $this->db->get();
    }


    /**
     * Count request required for admin attention
     *
     * @return object
     */
    public function countRequestAction($user_id)
    {
        $status = array(2,8,12,14,16,20,21);
        $this->db->select('r.id, r.status')
                ->from('request_list r')
                ->where_in('r.status', $status)
                ->where('(r.user_from = '.$user_id.' OR r.user_from IS NULL)');
        return $this->db->get();
    }

    /**
     * Count request required for member action
     *
     * @return object
     */
    public function countMemberRequestAction($user_id)
    {
        $status = array(5,6,10,17,19);
        $sql = "SELECT `r`.`id`
				FROM `request_list` `r`
				WHERE `r`.`user_id` = $user_id
				AND `r`.`status` IN(".implode(',', $status).")
				UNION
				SELECT `ia`.`request_id` as id
				FROM `items_approval` `ia`
				WHERE `ia`.`status` = '2'
				";
        return $this->db->query($sql);
    }

    /**
     * Count request required for head/recommending approval action
     *
     * @return object
     */
    public function countHeadAction($user_id)
    {
        $this->db->select('ra.id')
                ->from('request_approval ra')
                ->where('ra.user_id', $user_id)
                ->where('ra.status <>', 1);
        return $this->db->get();
    }


    /**
     * Archive request
     *
     * @param int $request_id
     * @return boolean
     */
    public function archiveRequest($request_id)
    {
        $db_data = array('archive' => 1);
        $this->db->where('id', $request_id);
        return $this->db->update('request_list', $db_data);
    }


    /**
     * UnArchive request
     *
     * @param int $request_id
     * @return boolean
     */
    public function unArchiveRequest($request_id)
    {
        $db_data = array('archive' => 0);
        $this->db->where('id', $request_id);
        return $this->db->update('request_list', $db_data);
    }
}

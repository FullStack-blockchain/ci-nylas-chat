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
 * Main controller for Aduser
 */
class Xwb_aduser extends XWB_purchasing_base
{

    /**
     * Run parent construct
     *
     * @return Null
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation'));
        $this->load->model('aduser/Aduser_model', 'Canvasser');
    }
    

    /**
     * All users view
     *
     * @return mixed
     */
    public function index()
    {
        $this->redirectUser(array('page'=>'admin_users'));
        
        $groups = $this->db->get('groups');
        $data['groups'] = $groups->result();
        $data['page_title'] = 'Canvasser'; //title of the page
        $data['page_script'] = 'canvasser'; // script filename of the page user.js
        $this->renderPage('canvasser', $data);
    }

    /**
     * All request view
     *
     * @return mixed
     */
    public function request()
    {
        $this->redirectUser(array('page'=>'admin_users'));
        $data['page_title'] = 'Purchase Request'; //title of the page
        $data['page_script'] = 'request'; // script filename of the page user.js
        $this->renderPage('request', $data);
    }

    /**
     * New request
     *
     * @return mixed
     */
    public function new_request()
    {
        $this->redirectUser();
        
        unset($_SESSION['new_request']);
        $data['page_title'] = 'New Request'; //title of the page
        $data['page_script'] = 'new_request'; // script filename of the page user.js
        $this->renderPage('new_request', $data);
    }


    /**
     * View assigned request page
     *
     * @return mixed
     */
    public function req_assign()
    {
        $this->redirectUser(array('page'=>'admin_users'));
        
        $data['page_title'] = 'Assigned Request'; //title of the page
        $data['page_script'] = 'assigned_req'; // script filename of the page user.js
        $this->renderPage('assigned_req', $data);
    }


    /**
     * Get assigned request
     * @return type
     */
    public function getAssignedRequest()
    {
        $this->redirectUser();
        $user_id = $this->log_user_data->user_id;
        $this->load->model('request/Request_model', 'Request');
        $req_assign = $this->Canvasser->getCanvassAssignedRequest($user_id);

        $data['data'] = array();
        


        if ($req_assign->num_rows()>0) {
            foreach ($req_assign->result() as $key => $v) {
                if ($v->status!=2) {
                    $forwardBudgetBtn ="";
                } else {
                    $forwardBudgetBtn ='<a href="javascript:;" onClick="xwb.assignToBudget('.$v->id.')" class="btn btn-xs btn-info">Forward to Budget</a>';
                }
                
                $data['data'][] = array(
                                        $v->id,
                                        $v->request_name,
                                        date("F j, Y, g:i a", strtotime($v->date_created)),
                                        ($v->date_needed==null?"":date("F j, Y", strtotime($v->date_needed))),
                                        '<a href="javascript:;" onClick="xwb.viewItems('.$v->request_id.')" class="btn btn-app"><i class="fa fa-search"></i>View Items</a>',
                                        number_format($v->total_amount, 2, '.', ','),
                                        $this->xwb_purchasing->getStatus('item_approval', $v->status)." ".'<label class="badge badge-info">'.time_elapse($v->date_updated).'</label>',
                                        '<a href="'.base_url('canvasser/update_items/'.$v->id).'" class="btn btn-xs btn-success">Update Items</a>
										'.$forwardBudgetBtn,
                                        );
            }
        }
        echo $this->xwbJsonEncode($data);
    }


    /**
     * Mark request as done
     *
     * @return json
     */
    public function requestDone()
    {
        $this->redirectUser();
        $data = array(
            'status' => 5,
            'admin_note' => $this->input->post('remarks'),
            );
        $this->db->where('id', $this->input->post('request_id'));
        $res = $this->db->update('request_list', $data);

        if ($res>0) {
            $data['status'] = true;
            $data['message'] = 'Requested purchase processed';
        } else {
            $data['status'] = false;
            $data['message'] = 'Error updating record, please contact the programmer';
        }

        echo $this->xwbJsonEncode($data);
    }


    public function update_items($canvass_id)
    {
        $this->redirectUser(array('admin_users'));
        $this->load->model('request/Request_model', 'Request');
        
        $groups = $this->db->get('groups');
        $data['groups'] = $groups->result();
        $data['canvass'] = $this->Canvasser->getCanvass($canvass_id)->row();
        $data['request_id'] = $data['canvass']->request_id;
        $data['items'] = $this->Request->getItemsPerRequest($data['canvass']->request_id)->result();
        $data['page_title'] = 'Update Products'; //title of the page
        $data['page_script'] = 'update_items'; // script filename of the page user.js
        $this->renderPage('update_items', $data);
    }

    public function updateItems()
    {
        $this->redirectUser();
        $this->load->model('request/Request_model', 'Request');
        $this->form_validation->set_rules('supplier[]', 'Supplier', 'required');
        $this->form_validation->set_rules('price[]', 'Unit Price', 'required');
        $this->form_validation->set_rules('net_total', 'Total Amount', 'required');
        
                
        
        if ($this->form_validation->run() == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
        } else {
            $posts = $this->input->post();
            foreach ($posts['price'] as $key => $value) {
                $db_data = array(
                    'unit_price' => str_replace(',', '', $value),
                    'supplier' => $posts['supplier'][$key],
                    'date_updated' => date('Y-m-d H:i:s'),
                    );
                $this->Request->updateItem($key, $db_data);
            }
            $this->updateNetAmmount($posts['request_id'], $posts['canvass_id'], $posts['net_total']);
            $data['status'] = true;
            $data['message'] = 'Items has been updated';
        }

        echo $this->xwbJsonEncode($data);
    }



    public function updateNetAmmount($request_id, $canvass_id, $net_amount)
    {
        $this->redirectUser();

        $this->db->where('id', $request_id);
        $this->db->update('request_list', array('total_amount'=>$net_amount,'date_updated' => date('Y-m-d H:i:s')));
        $this->db->where('id', $canvass_id);
        $this->db->update('canvass', array('status'=>2,'total_amount'=>$net_amount,'date_updated' => date('Y-m-d H:i:s')));
    }
}

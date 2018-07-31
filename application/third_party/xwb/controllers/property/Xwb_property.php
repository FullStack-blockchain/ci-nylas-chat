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
 * Main controller for property
 */
class Xwb_property extends XWB_purchasing_base
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
        $this->load->model('property/Property_model', 'Property');
    }



    /**
     * property dashboard view
     *
     * @return mixed
     */
    public function index()
    {
        $this->redirectUser(array('page'=>'property'));
        

        $this->load->model('request/Request_model', 'Request');
        $this->load->model('admin/Admin_model', 'Admin');
        $user_id = $this->log_user_data->user_id;
        $request = $this->Request->getRequestListByUser($user_id)->result();
        $gauge_data = $this->Admin->generateGaugeData($request);
        $data['progress_label'] = $this->Admin->progressLabel();
        $data['gauge_data'] = $gauge_data;

        $groups = $this->db->get('groups');
        $data['groups'] = $groups->result();
        $data['page_title'] = 'Property'; //title of the page
        $data['page_script'] = 'property'; // script filename of the page property.js
        $this->renderPage('property/property', $data);
    }

    /**
     * View the request being delivered
     *
     * @return mixed
     */
    public function request_done()
    {
        $this->redirectUser(array('page'=>'property'));
        
        $data['page_title'] = 'Request Done'; //title of the page
        $data['page_script'] = 'request_done'; // script filename of the page property.js
        $this->renderPage('property/request_done', $data);
    }


    /**
     * Get all property to datatable
     *
     * @return json
     */
    public function getProperties()
    {
        $this->redirectUser(array('page'=>'property'));

        $this->Property->getProperties();
        $recordsTotal = $this->db->count_all_results();
        $recordsFiltered = $this->Property->countFiltered('getProperties');


        $this->Property->getProperties();
        if ($this->input->get('length') != -1) {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
        }
        $p = $this->db->get();


        $data['data'] = array();


        if ($p->num_rows()>0) {
            foreach ($p->result() as $k => $v) {
                $has_action = "";
                if ($v->status==0) {
                    $has_action = "has-action";
                }

                $data['data'][] = array(
                            $v->pr_number,
                            $v->po_num,
                            $v->request_name,
                            ucwords($v->full_name),
                            $v->department,
                            $v->campus,
                            $v->purpose,
                            date('F j, Y, g:i a', strtotime($v->date_created)),
                            date('F j, Y, g:i a', strtotime($v->eta)),
                            ($v->date_delivered==null?"--":date('F j, Y, g:i a', strtotime($v->date_delivered))),
                            $v->officer_note,
                            '<a href="javascript:;" onClick="xwb.viewItems('.$v->id.')" class="btn btn-app '.$has_action.'"><i class="fa fa-search"></i>View Items</a>',
                            $this->xwb_purchasing->getStatus('property', $v->status),
                            '<a href="javascript:;" onClick="xwb.receivedItems('.$v->id.')" class="btn btn-xs btn-info">Received</a>'
                        );
            }
        }
        $data['draw'] = $this->input->get('draw');
        $data['recordsTotal'] = $recordsTotal;
        $data['recordsFiltered'] = $recordsFiltered;
        echo $this->xwbJsonEncode($data);
    }

    /**
     * Update received date
     *
     * @return json
     */
    public function receivedItems()
    {
        $this->redirectUser();
        
        $this->form_validation->set_rules('property_id', 'Property ID', 'required|alpha_dash');
        $this->form_validation->set_rules('date_received', 'Date Received', 'required');

        
        
        if ($this->form_validation->run() == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
        } else {
            $posts = $this->input->post();
            $db_data = array(
                    'date_delivered' => $posts['date_received'],
                    'officer_note' => $posts['property_remarks'],
                    'status' => 1,
                    'date_updated' => date('Y-m-d H:i:s'),
                );
            $this->db->where('id', $posts['property_id']);
            $res = $this->db->update('property', $db_data);
            if ($res) {
                $data['status'] = true;
                $data['message'] = 'Property has been updated';
            } else {
                $data['status'] = false;
                $data['message'] = 'Error updating data, pelase contact the programmer';
            }
        }
        echo $this->xwbJsonEncode($data);
    }

    public function getPropertyItems()
    {
        $this->redirectUser(array('page'=>'property'));
        $property_id = $this->input->get('property_id');
        $this->load->model('property_item/Property_item_model', 'PItem');

        $p_items = $this->PItem->getReceivedItems($property_id);
        $data['data'] = array();
        if ($p_items->num_rows()>0) {
            foreach ($p_items->result() as $k => $v) {
                $data['data'][] = array(
                        $v->product_name,
                        $v->product_category,
                        $v->product_description,
                        $v->quantity,
                    );
            }
        }

        echo $this->xwbJsonEncode($data);
    }
}

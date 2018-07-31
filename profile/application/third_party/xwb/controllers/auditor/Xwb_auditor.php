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
 * Main controller for Auditor
 */
class Xwb_auditor extends XWB_purchasing_base
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
        $this->load->model('auditor/Auditor_model', 'Auditor');
    }
    


    /**
     * View Auditor Order
     *
     * @return mixed
     */
    public function index()
    {
        $this->redirectUser(array('page'=>'auditor'));
        $this->load->model('request/Request_model', 'Request');
        $this->load->model('admin/Admin_model', 'Admin');
        
        $groups = $this->db->get('groups');

        $user_id = $this->log_user_data->user_id;
        $request = $this->Request->getRequestListByUser($user_id)->result();
        $gauge_data = $this->Admin->generateGaugeData($request);
        $data['progress_label'] = $this->Admin->progressLabel();
        $data['gauge_data'] = $gauge_data;

        $data['groups'] = $groups->result();
        $data['page_title'] = 'Auditor'; //title of the page
        $data['page_script'] = 'auditor'; // script filename of the page
        $this->renderPage('auditor/auditor', $data);
    }



    /**
     * Audit list page
     *
     * @return mixed
     */
    public function audit_list()
    {
        $this->redirectUser(array('page'=>'auditor'));
        
        $groups = $this->db->get('groups');
        $data['groups'] = $groups->result();
        $data['page_title'] = 'Audit List'; //title of the page
        $data['page_script'] = 'audit_list'; // script filename of the page
        $this->renderPage('auditor/audit_list', $data);
    }


    /**
     * Get audit PO for the login auditor
     *
     * @return json
     */
    public function getAuditList()
    {
        $this->redirectUser();
        $auditor_id = $this->session->userdata['user_id'];
        $this->load->model('purchase_order/Purchase_order_model', 'PO');

        $this->PO->getPObyAuditor($auditor_id);
        $args = array($auditor_id);

        $recordsTotal = $this->db->count_all_results();
        $recordsFiltered = $this->PO->countFiltered('getPObyAuditor', $args);
        

        $this->PO->getPObyAuditor($auditor_id);
        if ($this->input->get('length') != -1) {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
        }
        $po = $this->db->get();


        $data['data'] = array();

        if ($po->num_rows()>0) {
            foreach ($po->result() as $key => $v) {
                $disabled = "";
                if ($v->status == 1) {
                    $disabled = "disabled";
                }

                $class_action = '';
                if (in_array($v->status, $this->auditor_status_action)) {
                    $class_action = 'has-action';
                }

                $data['data'][] = array(
                                        $v->po_num,
                                        $v->pr_number,
                                        $v->request_name,
                                        $v->vendor_name,
                                        $this->xwb_purchasing->getStatus('purchase_order', $v->status).'<label class="badge">'.time_elapse($v->date_updated).'<label>',
                                        $v->pd_remarks,
                                        '<a target="_blank" href="'.base_url('purchase_order/preview/'.$v->id).'" class="btn btn-xs btn-info '.$class_action.'">View</a>
										<a href="javascript:;" onClick="xwb.approvePO('.$v->id.')" class="btn btn-xs btn-success '.$disabled.'">Approved</a>
										',
                                        );
                /*<a href="javascript:;" onClick="xwb.returnToPurchasing('.$v->id.')" class="btn btn-xs btn-warning '.$disabled.'">Return</a>*/ //remove as of now
            }
        }
        $data['draw'] = $this->input->get('draw');
        $data['recordsTotal'] = $recordsTotal;
        $data['recordsFiltered'] = $recordsFiltered;
        echo $this->xwbJsonEncode($data);
    }


    public function returnToPO()
    {
        $this->redirectUser();
        $this->form_validation->set_rules('po_id', 'P.O. ID', 'required');
        $this->form_validation->set_rules('reason', 'Reason', 'required');

        if ($this->form_validation->run() == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
        } else {
            $this->load->model('purchase_order/Purchase_order_model', 'PO');
            $posts = $this->input->post();
            $db_data = array(
                'auditor_remarks' => $posts['reason'],
                'status' => 2,
                'date_updated' => date('Y-m-d H:i:s'),
                );
            $res = $this->PO->updatePO($posts['po_id'], $db_data);

            $this->load->model('request/Request_model', 'Request');

            $po = $this->PO->getPO($posts['po_id'])->row();

            $this->load->model('user/User_model', 'User');

            $admin_email = $this->config->item('admin_email');
            $site_title = $this->config->item('site_title');
            
            $userto = $this->User->getUser($po->prepared_by)->row();
            $userfrom = $this->User->getUser($this->log_user_data->user_id)->row();


            $shortcodes = array(
                    'user_to' => ucwords($userto->first_name." ".$userto->last_name),
                    'user_from' => ucwords($userfrom->first_name." ".$userfrom->last_name),
                    'po_num' => $po->po_num,
                    'message' => $this->input->post('reason'),
                    'request_name' => $this->Request->getRequest($po->request_id)->row()->request_name,
                );

            $this->xwb_purchasing->setShortCodes($shortcodes);

            $condition = $this->xwb_purchasing->getShortCodes();

            $msg = $this->xwb_purchasing->getMessage('return_audit');
            $message = do_shortcode($msg['message'], $condition);

            $this->xwb_purchasing->sendmail($userto->email, $msg['subject'], $message, $userfrom->email, $site_title, $msg['subject']);

            $this->xwb_purchasing->addHistory('purchase_order', $posts['po_id'], 'Return to purchasing', 'Return to Purchase Order to purchasing department', $this->log_user_data->user_id);

            if ($res) {
                $data['status'] = true;
                $data['message'] = 'Purchase order updated';
            } else {
                $data['status'] = false;
                $data['message'] = 'Error updating data, please contact the programmer';
            }
        }

        echo $this->xwbJsonEncode($data);
    }


    /**
     * approve purchase order
     *
     * @return json
     */
    public function approvePO()
    {
        $this->redirectUser();
        $posts = $this->input->post();

        $db_data = array(
                'status' => 1,
                'date_updated' => date('Y-m-d H:i:s'),
                );
        $this->db->where('id', $posts['po_id']);
        $res = $this->db->update('purchase_order', $db_data);

        $this->load->model('purchase_order/Purchase_order_model', 'PO');
        $this->load->model('request/Request_model', 'Request');

        $po_items = $this->Request->getItemsPerPO($posts['po_id'])->result();
        $items = [];
        foreach ($po_items as $iK => $iV) {
            $items[] = $iV->id;
        }

        $db_data = array(
                'status' => 1,
                'date_updated' => date('Y-m-d H:i:s')
            );
        $this->db->where_in('id', $items);
        $this->db->update('po_items', $db_data);

        $po = $this->PO->getPO($posts['po_id'])->row();

        $po_approved = $this->checkAllPOApproved($po->request_id);

        if ($po_approved) {
            $res = $this->Request->updateRequestStatus($po->request_id, 12);
        }

        $this->load->model('user/User_model', 'User');

        /**
         * Assigning shortcode for email
         *
         * user_to
         * user_from
         * request_id
         * message
         * po
         * item
         */

        $shortcodes = array(
                'user_to' => $po->prepared_by_id,
                'message' => $this->input->post('reason'),
                'request_id' => $po->request_id,
                'po' => $posts['po_id'],
            );

        $this->xwb_purchasing->setShortCodes($shortcodes);

        $condition = $this->xwb_purchasing->getShortCodes();

        /* sending email notification */
        $msg = $this->xwb_purchasing->getMessage('po_audited');
        $message = do_shortcode($msg['message'], $condition);
        $site_title = $this->config->item('site_title');
        $this->xwb_purchasing->sendmail($condition['email_to'], $msg['subject'], $message, $condition['email_from'], $site_title, $msg['subject']);


        $this->xwb_purchasing->addHistory('purchase_order', $posts['po_id'], 'PO approved', 'PO has been approved', $this->log_user_data->user_id);



        if ($res) {
            $data['status'] = true;
                $data['message'] = 'Purchase order updated';
        } else {
            $data['status'] = false;
                $data['message'] = 'Error updating data, please contact the programmer';
        }

        echo $this->xwbJsonEncode($data);
    }

    public function checkAllPOApproved($request_id)
    {
        $this->redirectUser();
        $po = $this->Auditor->getPOItems($request_id)->result();
        $status = [];
        foreach ($po as $key => $value) {
            $status[] = $value->status;
        }
        if (count(array_unique($status))==1 && $status[0] == 1) {
            return true;
        } else {
            return false;
        }
    }
}

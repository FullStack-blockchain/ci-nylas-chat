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
 * Main controller for Board
 */
class Xwb_board extends XWB_purchasing_base
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
        $this->load->model('board/Board_model', 'Board');
    }
    

    /**
     * All users view
     *
     * @return mixed
     */
    public function index()
    {
        $this->redirectUser(array('admin','board'));
        
        $groups = $this->db->get('groups');
        $data['groups'] = $groups->result();
        $data['page_title'] = 'Request for Board Approval'; //title of the page
        $data['page_script'] = 'board'; // script filename of the page board.js
        $this->renderPage('board/board', $data);
    }



    /**
     * Get board approval list for datatable
     *
     * @return mixed
     */
    public function getBoardApproval()
    {
        
        $ba = $this->Board->getBoardApprovals();
                $data['data'] = array();

        if ($ba->num_rows()>0) {
            foreach ($ba->result() as $key => $v) {
                $disabled = "";

                if ($v->status == 1) {
                    $disabled = "disabled";
                }


                $class_action = '';
                if (in_array($v->status, $this->board_status_action)) {
                    $class_action = 'has-action';
                }


                $data['data'][] = array(
                    sprintf('PR-%08d', $v->id),
                    '<a class="btn btn-default btn-xs '.$class_action.'" href="'.base_url('request/view_request/'.$v->request_id).'">'.$v->request_name.'</a>',
                    ucwords($v->first_name." ".$v->last_name),
                    '<a href="javascript:;" onClick="xwb.viewItems('.$v->request_id.')" class="btn btn-app"><i class="fa fa-search"></i>View Items</a>',
                    '<strong>'.number_format($v->total_amount, 2, '.', ',').'</strong>',
                    ($v->date_needed==null?"":date("F j, Y", strtotime($v->date_needed))),
                    $v->requestor_note,
                    $v->board_note,
                    $this->xwb_purchasing->getStatus('board', $v->status)." ".'<label class="badge badge-info">'.time_elapse($v->date_updated).'</label>',
                    '<a target="_blank" href="'.base_url('request/print_request/'.$v->request_id).'" class="btn btn-xs btn-info">Print Request</a>
					<a href="javascript:;" class="btn btn-xs btn-success '.$disabled.'" onClick="xwb.approve('.$v->id.');">Approve</a>
					<a href="javascript:;" class="btn btn-xs btn-danger '.$disabled.'" onClick="xwb.deny('.$v->id.');">Deny</a>'
                    );
            }
        }
        echo $this->xwbJsonEncode($data);
    }






    /**
     * Deny request with reasons why
     *
     * @return json
     */
    public function denyRequest()
    {
        $this->form_validation->set_rules('reason', 'Reason / Budget note', 'required');
        $this->form_validation->set_rules('boardapproval_id', 'Board Approval ID', 'required');

        if ($this->form_validation->run() == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
        } else {
            $posts = $this->input->post();
            $data = array(
                        'status' => 2,
                        'board_note' => $posts['reason'],
                        'date_updated' => date('Y-m-d H:i:s')
                        );
            $this->db->where('id', $posts['boardapproval_id']);
            $this->db->update('board_approval', $data);
            
            $b = $this->Board->getBoardApproval($posts['boardapproval_id'])->row();

            $db_data = array(
                'status' => 10,
                'date_updated'=>date("Y-m-d H:i:s")
                );
            $this->db->where('id', $b->request_id);
            $res = $this->db->update('request_list', $db_data);

            $this->load->model('request/Request_model', 'Request');
            $r = $this->Request->getRequest($b->request_id)->row();
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
                    'user_to' => $r->user_id,
                    'message' => $this->input->post('reason'),
                    'request_id' => $b->request_id,
                );

            $this->xwb_purchasing->setShortCodes($shortcodes);

            $condition = $this->xwb_purchasing->getShortCodes();
            /* sending email notification */
            $msg = $this->xwb_purchasing->getMessage('board_denied');
            $message = do_shortcode($msg['message'], $condition);
            $site_title = $this->config->item('site_title');
            $this->xwb_purchasing->sendmail($condition['email_to'], $msg['subject'], $message, $condition['email_from'], $site_title, $msg['subject']);

            $this->xwb_purchasing->addHistory('request_list', $b->request_id, 'Board Denied', 'Request Denied by board', $this->log_user_data->user_id);


            if ($res) {
                $data['status'] = true;
                $data['message'] = 'Request has been denied';
            } else {
                $data['status'] = false;
                $data['message'] = 'Error updating record, please contact the programmer';
            }
        }

        echo $this->xwbJsonEncode($data);
    }



    /**
     * Approve from board
     *
     * @return json
     */
    public function approveBoard()
    {
        $this->load->model('request/Request_model', 'Request');
        $this->load->model('budget/Budget_model', 'Budget');
        $this->load->model('user/User_model', 'User');
        $board_id = $this->input->post('board_id');
        $b = $this->Board->getBoardApproval($board_id)->row();

        // Notify admin
        /*Board Approved*/
        $admins = $this->User->getUsersByGroup('admin')->result();
        foreach ($admins as $adK => $adV) {
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
                    'user_to' => $adV->id,
                    'request_id' => $b->request_id,
                );

            $this->xwb_purchasing->setShortCodes($shortcodes);

            $condition = $this->xwb_purchasing->getShortCodes();
            /* sending email notification */
            $msg = $this->xwb_purchasing->getMessage('board_approved');
            $message = do_shortcode($msg['message'], $condition);
            $site_title = $this->config->item('site_title');
            $this->xwb_purchasing->sendmail($condition['email_to'], $msg['subject'], $message, $condition['email_from'], $site_title, $msg['subject']);
            /* Adding to history transaction */
            $this->xwb_purchasing->addHistory('board', $board_id, 'Board Approved', 'Board approved the request id '.$b->request_id);
        }



        $this->db->where('id', $board_id);
        $this->db->update('board_approval', array('status' => 1, 'date_updated' => date('Y-m-d H:i:s')));

        $res = $this->Request->updateRequestStatus($b->request_id, 8);

        $db_data = array(
            'status' => 5,
            'date_updated' => date('Y-m-d H:i:s'),
        );
        $res = $this->Budget->updateStatusByRequest($b->request_id, $db_data);

        if ($res) {
            $data['status'] = true;
            $data['message'] = 'Board Approved, Processing request...';
        } else {
            $data['status'] = false;
            $data['message'] = 'Error updating record, please contact the programmer';
        }
        echo $this->xwbJsonEncode($data);
    }
}

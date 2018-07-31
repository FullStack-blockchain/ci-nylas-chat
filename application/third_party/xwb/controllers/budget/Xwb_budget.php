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
 * Main controller for Budget
 */
class Xwb_budget extends XWB_purchasing_base
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
        $this->load->model('budget/Budget_model', 'Budget');
    }
    

    /**
     * All users view
     *
     * @return mixed
     */
    public function index()
    {
        $this->redirectUser(array('page'=>'budget'));
        

        $this->load->model('request/Request_model', 'Request');
        $this->load->model('admin/Admin_model', 'Admin');
        $user_id = $this->log_user_data->user_id;
        $request = $this->Request->getRequestListByUser($user_id)->result();
        $gauge_data = $this->Admin->generateGaugeData($request);
        $data['progress_label'] = $this->Admin->progressLabel();
        $data['gauge_data'] = $gauge_data;

        $groups = $this->db->get('groups');
        $data['groups'] = $groups->result();
        $data['page_title'] = 'Budget'; //title of the page
        $data['page_script'] = 'budget'; // script filename of the page budget.js
        $this->renderPage('budget/budget', $data);
    }

    /**
     * assigne to budget department
     *
     * @return json
     */
    public function assignBudget()
    {
        $posts = $this->input->post();
        $this->load->model('canvasser/Canvasser_model', 'Canvasser');
        $c = $this->Canvasser->getCanvass($posts['canvass_id'])->row();

        $posts = $this->input->post();
        $db_data = array(
                    'status' => 4,
                    'date_updated' => date('Y-m-d H:i:s')
                    );
        $this->db->where('id', $c->request_id);
        $this->db->update('request_list', $db_data);

        $db_data = array(
                    'status' => 3,
                    'date_updated' => date('Y-m-d H:i:s')
                    );
        $this->db->where('id', $posts['canvass_id']);
        $this->db->update('canvass', $db_data);


        $db_data = array(
            'request_id' =>$c->request_id,
            'user_id' =>$posts['user_id'],
            'total_amount' =>$c->total_amount,
            'canvass_id' =>$posts['canvass_id'],
            'date_updated' =>date('Y-m-d H:i:s'),
        );
        
        $res = $this->db->insert('budget_approval', $db_data);


        $this->addHistory('request_list', $c->request_id, 'Forwarded to Budget', 'Forwarded to budget for approval', $posts['user_id']);

        $this->load->model('user/User_model', 'User');
        $this->load->model('request/Request_model', 'Request');

        /*=================*/
        $admin_email = $this->config->item('admin_email');
        $site_title = $this->config->item('site_title');
        $userto = $this->User->getUser($posts['user_id'])->row();
        $userfrom = $this->User->getUser($this->log_user_data->user_id)->row();
        

        $shortcodes = array(
                'name_from' => ucwords($userfrom->first_name." ".$userfrom->last_name),
                'name_to' => ucwords($userto->first_name." ".$userto->last_name),
                'message' => $this->input->post('reason'),
                'request_id' => $c->request_id,
            );

        $this->xwb_purchasing->setShortCodes($shortcodes);

        $condition = $this->xwb_purchasing->getShortCodes();

        $msg = $this->xwb_purchasing->getMessage('to_budget');
        $message = do_shortcode($msg['message'], $condition);

        $res = $this->xwb_purchasing->sendmail($userto->email, $msg['subject'], $message, $userfrom->email, $site_title, $msg['subject']);

        if ($res) {
            $data['status'] = true;
            $data['message'] = 'Request has been forwarded to budget for approval';
        } else {
            $data['status'] = false;
            $data['message'] = 'Error updating record, please contact the programmer';
        }


        echo $this->xwbJsonEncode($data);
    }


    /**
     * View request approval page
     *
     * @return mixed
     */
    public function req_approval()
    {
        $this->redirectUser(array('page'=>'budget'));
        
        $groups = $this->db->get('groups');
        $data['groups'] = $groups->result();
        $data['page_title'] = 'Request Approval'; //title of the page
        $data['page_script'] = 'req_approval'; // script filename of the page user.js
        $this->renderPage('budget/req_approval', $data);
    }


    /**
     * prepare all budget request approval per user for datatable
     *
     * @return json
     */
    public function getBudgetRequestApproval()
    {
        $user_id = $this->log_user_data->user_id;

        $this->Budget->getBudgetRequestApproval($user_id);

        $recordsTotal = $this->db->count_all_results();
        $args = array($user_id);
        $recordsFiltered = $this->Budget->countFiltered('getBudgetRequestApproval', $args);
        

        $this->Budget->getBudgetRequestApproval($user_id);
        if ($this->input->get('length') != -1) {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
        }
        $b = $this->db->get();


        $data['data'] = array();

        if ($b->num_rows()>0) {
            foreach ($b->result() as $key => $v) {
                $disabled = "";

                if ($v->status == 1 || $v->status == 4) {
                    $disabled = "disabled";
                }

                $data['data'][] = array(
                                        sprintf('PR-%08d', $v->request_id),
                                        '<a class="btn btn-default btn-xs" href="'.base_url('request/view_request/'.$v->request_id).'">'.$v->request_name.'</a>',
                                        ucwords($v->full_name),
                                        '<a href="javascript:;" onClick="xwb.viewItems('.$v->request_id.')" class="btn btn-app"><i class="fa fa-search"></i>View Items</a>',
                                        '<strong>'.number_format($v->total_amount, 2, '.', ',').'</strong>',
                                        //priority_label($v->priority_level).priority_time($v->priority_level,$v->date_created),
                                        ($v->date_needed==null?"":date("F j, Y", strtotime($v->date_needed))),
                                        $this->budgetActionBtn($v->id, $v->request_id, $v->status),
                                        $this->xwb_purchasing->getStatus('budget', $v->status)." ".'<label class="badge badge-info">'.time_elapse($v->date_updated).'</label>',
                                        );
            }
        }

        $data['draw'] = $this->input->get('draw');
        $data['recordsTotal'] = $recordsTotal;
        $data['recordsFiltered'] = $recordsFiltered;
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
        $this->form_validation->set_rules('budgetapproval_id', 'Budget Approval ID', 'required');

        if ($this->form_validation->run() == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
        } else {
            $posts = $this->input->post();
            $data = array(
                        'status' => 2,
                        'budget_note' => $posts['reason'],
                        'date_updated' => date('Y-m-d H:i:s')
                        );
            $this->db->where('id', $posts['budgetapproval_id']);
            $this->db->update('budget_approval', $data);
            
            $b = $this->Budget->getBudgetReqApproval($posts['budgetapproval_id'])->row();

            $db_data = array(
                'status' => 6,
                'date_updated'=>date("Y-m-d H:i:s")
                );
            $this->db->where('id', $b->request_id);
            $res = $this->db->update('request_list', $db_data);


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
                    'user_to' => $b->user_from,
                    'message' => $this->input->post('reason'),
                    'request_id' => $b->request_id,
                );

            $this->xwb_purchasing->setShortCodes($shortcodes);

            $condition = $this->xwb_purchasing->getShortCodes();
            /* sending email notification */
            $msg = $this->xwb_purchasing->getMessage('budget_return');
            $message = do_shortcode($msg['message'], $condition);
            $site_title = $this->config->item('site_title');
            $this->xwb_purchasing->sendmail($condition['email_to'], $msg['subject'], $message, $condition['email_from'], $site_title, $msg['subject']);

            $this->xwb_purchasing->addHistory('request_list', $b->request_id, 'Budget Denied', 'Budget approval denied', $this->log_user_data->user_id);

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
     * view items/products in budget view datatable
     *
     * @return json
     */
    public function getBudgetApprovaltItems()
    {

        $user_id = $this->log_user_data->user_id;
        $request_id = $this->input->get('request_id');
        $this->load->model('request/Request_model', 'Request');
        $r = $this->Request->getItemsPerRequest($request_id);
        
        $data['data'] = [];
        if ($r->num_rows()>0) {
            foreach ($r->result() as $key => $v) {
                $done_icon = '';
                if ($v->status != 0) {
                    $done_icon = '<i class="fa fa-check green"></i> ';
                }

                $time_lapse = (is_null($v->date_updated)?"":'<label class="badge badge-info">'.time_elapse($v->date_updated).'</label>');

                $expenditure = '<select name="expenditure" data-itemid="'.$v->id.'" class="from-control expenditure">';
                    $expenditure .= '<option value="">Select Option</option>';
                    $expenditure .= '<option value="OPEX" '.($v->expenditure == 'OPEX'?'selected':'').'>'.getExpenditureName('OPEX').'</option>';
                    $expenditure .= '<option value="CAPEX" '.($v->expenditure == 'CAPEX'?'selected':'').'>'.getExpenditureName('CAPEX').'</option>';
                $expenditure .= '</select>';

                if ($this->log_user_data->group_name == 'budget') {
                    $data['data'][] = array(
                        $done_icon.$v->product_name,
                        $v->product_description,
                        $v->quantity,
                        $expenditure,
                        number_format($v->unit_price, 2, '.', ','),
                        number_format($v->unit_price * $v->quantity, 2, '.', ',')
                    );
                } else {
                    $data['data'][] = array(
                        $done_icon.$v->product_name,
                        $v->product_description,
                        $v->quantity,
                        number_format($v->unit_price, 2, '.', ','),
                        number_format($v->unit_price * $v->quantity, 2, '.', ',')
                    );
                }
            }
        }
        echo $this->xwbJsonEncode($data);
    }


    /**
     * Approve budget
     *
     * @return json
     */
    public function approveBudget()
    {


        $this->load->model('request/Request_model', 'Request');
        $this->load->model('board/Board_model', 'Board');
        $budget_id = $this->input->post('budget_id');
        $b = $this->Budget->getBudgetReqApproval($budget_id)->row();

        $items = $this->Budget->getItemsBudgetApprovalPerRequest($b->request_id)->result();
        $item_no_expenditure = [];
        foreach ($items as $item) {
            if (empty($item->expenditure)) {
                $item_no_expenditure = $item->id;
            }
        }

        if (count($item_no_expenditure)>0) {
            $data['status'] = false;
            $data['message'] = 'Each item must have expenditure. Please click view items to set.';
            echo $this->xwbJsonEncode($data);
            exit();
        }

        $b_status = 1; // budget status
        $r_status = 8; // request status
        if ($b->total_amount>=(int)getConfig('board_approval_amount')) { //insert or update board approval
            $b_status = 4; // budget status
            $r_status = 9; // request status
            $db_data = array(
                'request_id'=>$b->request_id,
                'total_amount'=>$b->total_amount,
                'status'=>0,
                'date_updated'=>date('Y-m-d H:i:s'),
                );

            $board = $this->Board->getBoardByRequest($b->request_id);

            if ($board->num_rows()!=0) {
                $b_rec = $board->row();

                $this->db->where('id', $b_rec->id);
                $res = $this->db->update('board_approval', $db_data);
            } else {
                $res = $this->db->insert('board_approval', $db_data);
            }
        }

        $db_data = array(
            'status' => $b_status,
            'date_updated' => date('Y-m-d H:i:s'),
            );
        $this->Budget->updateBudgetApproval($budget_id, $db_data);

        $res = $this->Request->updateRequestStatus($b->request_id, $r_status);

        $this->load->model('user/User_model', 'User');

        /*Budget Approved*/
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
                    'message' => $this->input->post('reason'),
                    'request_id' => $b->request_id,
                );

            $this->xwb_purchasing->setShortCodes($shortcodes);

            $condition = $this->xwb_purchasing->getShortCodes();
            /* sending email notification */
            $msg = $this->xwb_purchasing->getMessage('budget_approved');
            $message = do_shortcode($msg['message'], $condition);
            $site_title = $this->config->item('site_title');
            $this->xwb_purchasing->sendmail($condition['email_to'], $msg['subject'], $message, $condition['email_from'], $site_title, $msg['subject']);
        }
        
        $this->xwb_purchasing->addHistory('request_list', $b->request_id, 'Budget Approved', 'Request budget approved', $this->log_user_data->user_id);



        if ($b->total_amount>=(int)getConfig('board_approval_amount')) {
            $board = $this->User->getUsersByGroup('board')->result();
            foreach ($board as $bK => $bV) {
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
                        'user_to' => $bV->id,
                        'message' => $this->input->post('reason'),
                        'request_id' => $b->request_id,
                );

                $this->xwb_purchasing->setShortCodes($shortcodes);

                $condition = $this->xwb_purchasing->getShortCodes();
                /* sending email notification */
                $msg = $this->xwb_purchasing->getMessage('board_approval');
                $message = do_shortcode($msg['message'], $condition);
                $site_title = $this->config->item('site_title');
                $this->xwb_purchasing->sendmail($condition['email_to'], $msg['subject'], $message, $condition['email_from'], $site_title, $msg['subject']);
            }
        }


        if ($res) {
            $data['status'] = true;
            $data['message'] = 'Budget Approved, Processing request...';
        } else {
            $data['status'] = false;
            $data['message'] = 'Error updating record, please contact the programmer';
        }
        echo $this->xwbJsonEncode($data);
    }


    /**
     * Budget Action Button generator
     *
     * @param int $budget_id
     * @param int $status
     * @return string
     */
    public function budgetActionBtn($budget_id, $request_id, $status)
    {

        $this->redirectUser();
        $defaultbtn = '<li><a target="_blank" href="'.base_url('request/print_request/'.$request_id).'">Print Request</a></li>';
        
        switch ($status) {
            case 0:
                $btn = $defaultbtn;
                $btn .= '<li class="has-action"><a href="javascript:;" onClick="xwb.view_response('.$budget_id.');">View Message</a></li>';
                $btn .= '<li class="has-action"><a href="javascript:;" onClick="xwb.approve('.$budget_id.');">Approve</a></li>';
                $btn .= '<li class="has-action"><a href="javascript:;" onClick="xwb.deny('.$budget_id.');">Deny</a></li>';
                break;
            case 1:
                $btn = $defaultbtn;
                break;

            case 2:
                $btn = $defaultbtn;
                $btn .= '<li class="has-action"><a href="javascript:;" onClick="xwb.approve('.$budget_id.');">Approve</a></li>';
                $btn .= '<li class="has-action"><a href="javascript:;" onClick="xwb.deny('.$budget_id.');">Deny</a></li>';
                break;
            case 3:
                $btn = $defaultbtn;
                $btn .= '<li class="has-action"><a href="javascript:;" onClick="xwb.view_response('.$budget_id.');">View Message</a></li>';
                $btn .= '<li class="has-action"><a href="javascript:;" onClick="xwb.approve('.$budget_id.');">Approve</a></li>';
                $btn .= '<li class="has-action"><a href="javascript:;" onClick="xwb.deny('.$budget_id.');">Deny</a></li>';
                break;
            case 4:
                $btn = $defaultbtn;
                break;
            case 5:
                $btn = $defaultbtn;
                break;
            case 6:
                $btn = $defaultbtn;
                $btn .= '<li class="has-action"><a href="javascript:;" onClick="xwb.view_response('.$budget_id.');">View Message</a></li>';
                $btn .= '<li class="has-action"><a href="javascript:;" onClick="xwb.approve('.$budget_id.');">Approve</a></li>';
                $btn .= '<li class="has-action"><a href="javascript:;" onClick="xwb.deny('.$budget_id.');">Deny</a></li>';
                break;
            default:
                $btn = $defaultbtn;
                break;
        }


        
        if ($btn=="") {
            $btn = '<li>No Action Required</li>';
        }


        $action = '<div class="btn-group">
			<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle btn-sm" type="button" aria-expanded="false">Action <span class="caret"></span>
			</button>
			<ul role="menu" class="dropdown-menu">
				'.$btn.'
			</ul>
		</div>';
        return $action;
    }

    /**
     * Get user message to budget
     * @return type
     */
    public function getBudgetMessage()
    {
        $budget_id = $this->input->get('budget_id');
        $b = $this->Budget->getBudgetReqApproval($budget_id)->row();
        if ($b->status == 3) {
            $message = $b->requestor_note;
        } else {
            $message = $b->user_response;
        }
        $data['message'] = $message;
        echo $this->xwbJsonEncode($data);
    }
}

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
 * Main controller for Approval
 */
class Xwb_approval extends XWB_purchasing_base
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
        $this->load->model('approval/Approval_model', 'Approval');
        $this->load->model('request/Request_model', 'Request');
    }
    


    /**
     * for approval view of head department member page
     *
     * @return type
     */
    public function for_approval()
    {
        $this->redirectUser(array('members','budget','canvasser','admin','auditor','property','head'));
        $data['page_title'] = 'Purchases for approval'; //title of the page
        $data['page_script'] = 'for_approval'; // script filename of the page for_approval.js
        $this->renderPage('approval/for_approval', $data);
    }


    /**
     * Assign recommending approval
     *
     * @param int $approval_id
     * @return mixed
     */
    public function recommend_approval($approval_id)
    {

        $this->redirectUser(array('members','budget','canvasser','admin','auditor','property','head'));
        $this->load->model('user/User_model', 'User');
        $data['head_users'] = $this->User->getAllHeadDepartmentUsers()->result();

        $request_id = $this->Approval->getApproval($approval_id)->row()->request_id;
        $data['items'] = $this->Request->getItemsPerRequest($request_id)->result();

        $data['approval_id'] = $approval_id;
        $data['request_id'] = $request_id;

        $data['page_title'] = 'Recommending approval'; //title of the page
        $data['page_script'] = 'recommending_approval'; // script filename of the page user.js

        $this->renderPage('approval/recommending_approval', $data);
    }

    /**
     * Get items for approval based on each head department
     *
     * @return json
     */
    public function getItemsForApproval()
    {
        $this->redirectUser();
        $get = $this->input->get();
        $items = $this->Approval->getItemsForApprovalPerHead($get['user_id'], $get['request_id'])->result();
        $data['items'] = [];
        foreach ($items as $key => $value) {
            $data['items'][] = $value->item_id;
        }

        $ra = $this->Approval->getRequestApproval($get['user_id'], $get['request_id']);
        if ($ra->num_rows()!=0 && $this->session->userdata('user_id') != $get['user_id']) {
            $data['user_exists'] = true;
        } else {
            $data['user_exists'] = false;
        }

        echo $this->xwbJsonEncode($data);
    }

    /**
     * Get for approval items
     *
     * @return json
     */
    public function getReqApprovaltItems()
    {
        $this->redirectUser();
        $this->load->model('approval/Approval_model', 'Approval');
        $user_id = $this->log_user_data->user_id;
        $approval_id = $this->input->get('approval_id');
        $req_approval = $this->Approval->getApproval($approval_id)->row();
        $i = $this->Approval->getItemsApprovalPerRequest($req_approval->request_id);

        $all_req_stat = $this->Approval->getAllRequestItemsStatus($req_approval->request_id);

        $data['data'] = [];
        if ($i->num_rows()>0) {
            foreach ($i->result() as $key => $v) {
                if ($this->Approval->itemExists($v->id, $user_id)) {
                    if (count($all_req_stat)==1 && $all_req_stat[0]==1) {
                        $officers_note = $v->officers_note;
                    } else {
                        $officers_note = '<textarea class="officers_note_'.$v->id.'" name="officers_note['.$v->id.']" clas="form-control">'.$v->officers_note.'</textarea>
						<a href="javascript:;" class="btn btn-xs btn-success" onClick="xwb.approveItem('.$v->id.')">Approve</a>
											<a href="javascript:;" class="btn btn-xs btn-warning" onClick="xwb.denyItem('.$v->id.')">Deny</a>
						';
                    }
                } else {
                    $officers_note = $v->officers_note;
                }
                $time_lapse = (is_null($v->date_updated)?"":'<label class="badge badge-info">'.time_elapse($v->date_updated).'</label>');

                $data['data'][] = array(
                                        /*$v->id,*/
                                        $v->product_name,
                                        $v->product_description,
                                        $v->quantity,
                                        ucwords($v->first_name." ".$v->last_name." (".$v->user_dept.")"),
                                        '<a class="btn btn-info btn-xs" href="javascript:;" onClick="xwb.viewAttachmentPreview('.$v->pi_id.')"><i class="fa fa-file-image-o"></i> Attachment</a>',
                                        $this->xwb_purchasing->getStatus('item_approval', $v->status)." ".$time_lapse,
                                        $v->requestor_remarks,
                                        $officers_note,
                                        );
            }
        }
        echo $this->xwbJsonEncode($data);
    }


    /**
     * Get approval items for each head user
     *
     * @return json
     */
    public function getApprovalItems()
    {
        $this->redirectUser();
        $gets = $this->input->get();

        $ai = $this->Approval->getItemsForApprovalPerHead($gets['user_id'], $gets['request_id']);
        //pre($ai->num_rows());
        $data['data'] = array();

        if ($ai->num_rows()>0) {
            foreach ($ai->result() as $key => $v) {
                $data['data'][] = array(
                                        $v->product_name,
                                        $v->quantity,
                                        );
            }
        }
        echo $this->xwbJsonEncode($data);
    }


    /**
     * Assign Items for approval in the selected user
     *
     * @return json
     */
    public function assignItems()
    {
        $this->redirectUser();
        $posts = $this->input->post();
        $existing_items = $this->existing_items($posts['user_id'], $posts['request_id']);
        $ra = $this->Approval->getRequestApproval($posts['user_id'], $posts['request_id']);
        
        
        if ($ra->num_rows()==0) {
            $this->db->insert('request_approval', array('user_id'=>$posts['user_id'],'request_id'=>$posts['request_id']));
            $req_approval_id = $this->db->insert_id();

            //send email notification to the assigned head user
            $user_id = $this->log_user_data->user_id;
            if ($posts['user_id'] != $user_id) {
                $this->load->model('request/Request_model', 'Request');

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
                    'user_to' => $posts['user_id'],
                    'message' => $this->input->post('reason'),
                    'request_id' => $posts['request_id'],
                );

                $this->xwb_purchasing->setShortCodes($shortcodes);

                /* sending email notification */
                $condition = $this->xwb_purchasing->getShortCodes();
                $msg = $this->xwb_purchasing->getMessage('new_request_assigned');
                $message = do_shortcode($msg['message'], $condition);
                $site_title = $this->config->item('site_title');
                $this->xwb_purchasing->sendmail($condition['email_to'], $msg['subject'], $message, $condition['email_from'], $site_title, $msg['subject']);
            }
        } else {
            $req_approval_id = $ra->row()->id;
        }

        $post_items = $this->post_items();

        $items_to_delete = [];
        foreach ($existing_items as $key => $value) {
            if (!in_array($value, $post_items)) {
                $items_to_delete[] = $key;
            }
        }

        $items_to_add = [];

        foreach ($post_items as $key => $value) {
            if (!in_array($value, $existing_items)) {
                $items_to_add[] = array(
                    'user_id' => $posts['user_id'],
                    'request_id' => $posts['request_id'],
                    'request_approval_id' => $req_approval_id,
                    'item_id' => $value,
                    );
            }
        }
        if (count($items_to_delete)>0) {
            $this->db->where_in('id', $items_to_delete);
            $this->db->delete('items_approval');
        }

        if (count($items_to_add)>0) {
            $this->db->insert_batch('items_approval', $items_to_add);
        }
        
        


        $data['status'] = true;
        $data['message'] = "Items has been successfully updated";
        echo $this->xwbJsonEncode($data);
    }


    /**
     * Get existing items to array
     *
     * @param int $user_id
     * @param int $request_id
     * @return array
     */
    public function existing_items($user_id, $request_id)
    {
        $this->redirectUser();
        $ai = $this->Approval->getItemsPerRequest($user_id, $request_id)->result();

        $existing_items = [];
        foreach ($ai as $key => $value) {
            $existing_items[$value->id] = $value->item_id;
        }
        return $existing_items;
    }

    /**
     * Get post items to array
     *
     * @return array
     */
    public function post_items()
    {
        $this->redirectUser();
        $posts = $this->input->post();
        if (!array_key_exists('items', $posts)) {
            $posts['items'] = [];
        }

        $post_items = [];
        foreach ($posts['items'] as $key => $value) {
            $post_items[] = $value;
        }
        return $post_items;
    }


    /**
     * Delete approving user
     *
     * @return json
     */
    public function deleteApprovingUser()
    {
        $this->redirectUser();
        $posts = $this->input->post();
        $this->db->delete('request_approval', array('user_id'=>$posts['user_id'],'request_id'=>$posts['request_id']));

        $res = $this->db->affected_rows();
        if ($res>0) {
            $data['status'] = true;
            $data['message'] = 'Approving officer has been deleted';
        } else {
            $data['status'] = false;
            $data['message'] = 'Error deleting record, please contact the programmer';
        }

        echo $this->xwbJsonEncode($data);
    }


    /**
     * Get department request for approval of the head department
     *
     * @return json
     */
    public function getReqForApproval()
    {
        $this->redirectUser();
        $user_id = $this->log_user_data->user_id;
        $department_id = $this->log_user_data->department;

        $this->Request->getReqForApproval($user_id, $department_id);

        $recordsTotal = $this->db->count_all_results();
        $args = array($user_id, $department_id);
        $recordsFiltered = $this->Request->countFiltered('getReqForApproval', $args);
        

        $this->Request->getReqForApproval($user_id, $department_id);
        if ($this->input->get('length') != -1) {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
        }
        $r = $this->db->get();


        $data['data'] = array();

        if ($r->num_rows()>0) {
            foreach ($r->result() as $key => $v) {
                $data['data'][] = array(
                                        sprintf('PR-%08d', $v->id),
                                        $v->request_name,
                                        date("F j, Y, g:i a", strtotime($v->date_created)),
                                        ucwords($v->full_name),
                                        nl2br($v->purpose),
                                        // nl2br($v->dept_head_note),
                                        '<a href="javascript:;" onClick="xwb.viewApprovalItems('.$v->approval_id.')" class="btn btn-app"><i class="fa fa-search"></i>View Items</a>',
                                        //priority_label($v->priority_level).priority_time($v->priority_level,$v->date_created),
                                        ($v->date_needed==null?"":date("F j, Y", strtotime($v->date_needed))),
                                        $this->headActionBtn($v->id, $v->approval_id, $v->status),
                                        $this->xwb_purchasing->getStatus('req_approval', $v->status),
                                        );
            }
        }
        $data['draw'] = $this->input->get('draw');
        $data['recordsTotal'] = $recordsTotal;
        $data['recordsFiltered'] = $recordsFiltered;
        echo $this->xwbJsonEncode($data);
    }



    /**
     * Approve approval item
     *
     * @return json
     */
    public function approveItem()
    {
        $this->redirectUser();
        $posts = $this->input->post();
        $mysql_data = array('status' => 1, 'officers_note'=>$posts['officers_note'],'date_updated'=>date("Y-m-d H:i:s"));
        $this->db->where('id', $posts['item_approval_id']);
        $res=$this->db->update('items_approval', $mysql_data);
        $this->updateRequestApprovalStatus($posts['item_approval_id']);


        $items_approval = $this->Approval->getItemsApproval($posts['item_approval_id'])->row();
        $request_id = $items_approval->request_id;
        $status = $this->Approval->getAllRequestItemsStatus($request_id);

        if ($res) {
            $data['status'] = true;
            if (count($status)==1 && $status[0]==1) {
                $data['message'] = "All items approved, locking the request...";
                $this->Request->updateRequestStatus($request_id, 2);


                $this->load->model('user/User_model', 'User');

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
                            'request_id' => $request_id,
                        );

                    $this->xwb_purchasing->setShortCodes($shortcodes);

                    $condition = $this->xwb_purchasing->getShortCodes();

                    /* sending email notification */
                    $msg = $this->xwb_purchasing->getMessage('request_filed');
                    $message = do_shortcode($msg['message'], $condition);
                    $site_title = $this->config->item('site_title');
                    $this->xwb_purchasing->sendmail($condition['email_to'], $msg['subject'], $message, $condition['email_from'], $site_title, $msg['subject']);
                }
                
                $this->xwb_purchasing->addHistory('request_list', $request_id, 'Request Filed', 'Request Filed: Head/Recommending Department approve the request');
            } else {
                $data['message'] = "Item Approved";
            }
        } else {
            $data['status'] = false;
            $data['message'] = "Error updating data, please contact the programmer";
        }
        echo $this->xwbJsonEncode($data);
    }



    public function approveAllItem()
    {
        $this->redirectUser();
        $item_id = $this->input->post('item_id');
        if ($item_id == "") {
            $data['status'] = false;
            $data['message'] = "Error updating data, No input found";
            echo $this->xwbJsonEncode($data);
            exit();
        }
        $officers_note = $this->input->post('officers_note');
        $item_ids = explode(',', $item_id);

        $mysql_data = array('status' => 1, 'officers_note'=>$officers_note,'date_updated'=>date("Y-m-d H:i:s"));
        $this->db->where_in('id', $item_ids);
        $res=$this->db->update('items_approval', $mysql_data);
        foreach ($item_ids as $key => $value) {
            $this->updateRequestApprovalStatus($value);
        }
        

        $items_approval = $this->Approval->getItemsApproval($item_ids[0])->row();
        $request_id = $items_approval->request_id;
        $status = $this->Approval->getAllRequestItemsStatus($request_id);

        if ($res) {
            $data['status'] = true;
            if (count($status)==1 && $status[0]==1) {
                $data['message'] = "All items approved, locking the request...";
                $this->Request->updateRequestStatus($request_id, 2);


                $this->load->model('user/User_model', 'User');

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
                            'request_id' => $request_id,
                        );

                    $this->xwb_purchasing->setShortCodes($shortcodes);

                    $condition = $this->xwb_purchasing->getShortCodes();
                    /* sending email notification */
                    $msg = $this->xwb_purchasing->getMessage('request_filed');
                    $message = do_shortcode($msg['message'], $condition);
                    $site_title = $this->config->item('site_title');
                    $this->xwb_purchasing->sendmail($condition['email_to'], $msg['subject'], $message, $condition['email_from'], $site_title, $msg['subject']);
                }
                
                $this->xwb_purchasing->addHistory('request_list', $request_id, 'Request Filed', 'Request Filed: Head/Recommending Department approve the request');
            } else {
                $data['message'] = "Items Approved";
            }
        } else {
            $data['status'] = false;
            $data['message'] = "Error updating data, please contact the programmer";
        }
        echo $this->xwbJsonEncode($data);
    }

    /**
     * Deny approval item
     *
     * @return json
     */
    public function denyItem()
    {
        $this->redirectUser();
        $posts = $this->input->post();
        $mysql_data = array('status' => 2, 'officers_note'=>$posts['officers_note'],'date_updated'=>date("Y-m-d H:i:s"));
        $this->db->where('id', $posts['item_approval_id']);
        $res=$this->db->update('items_approval', $mysql_data);

        $this->updateRequestApprovalStatus($posts['item_approval_id']);

        /*use for email*/
        $this->load->model('request/Request_model', 'Request');
        $ia = $this->Approval->getItemsApproval($posts['item_approval_id'])->row();
        $request_id = $ia->request_id;
        $r = $this->Request->getRequest($request_id)->row();

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
                'message' => $this->input->post('officers_note'),
                'request_id' => $request_id,
                'item'  => $ia->item_id
            );

        $this->xwb_purchasing->setShortCodes($shortcodes);

        $condition = $this->xwb_purchasing->getShortCodes();

        /* sending email notification */
        $msg = $this->xwb_purchasing->getMessage('item_denied');
        $message = do_shortcode($msg['message'], $condition);
        $site_title = $this->config->item('site_title');
        $this->xwb_purchasing->sendmail($condition['email_to'], $msg['subject'], $message, $condition['email_from'], $site_title, $msg['subject']);


        if ($res) {
            $data['status'] = true;
            $data['message'] = "Item Denied";
        } else {
            $data['status'] = false;
            $data['message'] = "Error updating data, please contact the programmer";
        }
        echo $this->xwbJsonEncode($data);
    }



    /**
     * Update Request Approval Status
     *
     * @param int $item_approval_id
     * @return boolean
     */
    public function updateRequestApprovalStatus($item_approval_id)
    {

        $this->redirectUser();
        $items_approval = $this->Approval->getItemsApproval($item_approval_id)->row();
        $request_id = $items_approval->request_id;
        $request_approval_id = $items_approval->request_approval_id;
        $status = $this->Approval->getAllRequestItemsStatus($request_id);

        if (count($status)==1) {
            $status = $status[0];
            switch ($status) {
                case 1:
                    $status = $this->Approval->updateAllRequestApprovalStatus($request_id, 1);
                    break;
                case 2:
                    $this->load->model('request/Request_model', 'Request');
                    $status = $this->Approval->updateAllRequestApprovalStatus($request_id, 3);
                    $this->Request->updateRequestStatus($request_id, 5);
                    break;
            }
        } else {
                $status = $this->Approval->updateRequestApprovalStatus($request_approval_id, 2);
        }
        
        return $status;
    }



    /**
     * Head department Action Button generator
     *
     * @param int $canvass_id
     * @param int $approval_id
     * @param int $status
     * @return string
     */
    public function headActionBtn($request_id, $approval_id, $status)
    {

        $this->redirectUser();
        $defaultbtn = '<li><a target="_blank" href="'.base_url('request/print_request/'.$request_id).'" >Print Request</a></li>';
        
        
        switch ($status) {
            case 1:
                $btn = $defaultbtn;
                break;


            default:
                $btn = $defaultbtn;
                $btn .= '<li class="has-action"><a href="'.base_url('approval/recommend_approval/'.$approval_id).'">Recommending Approval</a></li>';
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
}

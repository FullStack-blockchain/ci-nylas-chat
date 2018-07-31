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
 * Main controller for request module
 */
class Xwb_request extends XWB_purchasing_base
{

    /**
     * Run parent construct
     *
     * @return Null
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('request/Request_model', 'Request');
    }
    

    /**
     * All request view
     *
     * @return mixed
     */
    public function index()
    {
        $this->redirectUser(array('members','budget','canvasser','auditor','property','board'));

        $data['page_title'] = 'Purchase Request'; //title of the page
        $data['page_script'] = 'request'; // script filename of the page user.js
        $this->renderPage('request/request', $data);
    }


    /**
     * New request form
     *
     * @return mixed
     */
    public function newreq()
    {
        $this->redirectUser(array('members','canvasser','budget','admin','auditor','property','board'));
        $this->load->model('product/Product_model', 'Product');
        $this->load->model('request_category/Request_category_model', 'ReqCat');

        unset($_SESSION['new_request']);
        unset($_SESSION['req_attachment']);
        $data['products'] = $this->Product->getProducts()->result();
        $data['unit_measurements'] = $this->config->item('unit_measurement');
        $data['req_cat'] = $this->ReqCat->getReqCat()->result();
        $data['page_title'] = 'New Request'; //title of the page
        $data['page_script'] = 'new_request'; // script filename of the page user.js

        $this->renderPage('request/new_request', $data);
    }

    /**
     * Get all request to datatable
     *
     * @return json
     */
    public function getRequest()
    {
        $this->redirectUser();
        $d = $this->Request->getRequests();
        
        $data['data'] = array();

        if ($d->num_rows()>0) {
            foreach ($d->result() as $key => $v) {
                $data['data'][] = array(
                                        $v->id,
                                        $v->prno,
                                        $v->date_requested,
                                        $v->user_id,
                                        $v->dept,
                                        $v->request_type,
                                        $v->request_item,
                                        $v->request_description,
                                        $v->request_qty,
                                        $v->state,
                                        '<a href="javascript:;" class="btn btn-xs btn-warning" onClick="xwb.editRequest('.$v->id.');">Edit</a>
										<a href="javascript:;" onClick="xwb.deleteRequest('.$v->id.');" class="btn btn-xs btn-danger">Delete</a>',
                                        );
            }
        }
        echo $this->xwbJsonEncode($data);
    }


/**
 * Get all request list
 *
 * @return json
 */
    public function getRequestList()
    {
        $this->redirectUser();
        $user_id = $this->log_user_data->user_id;
        $r = $this->Request->getRequestListByUser($user_id);
        
        $data['data'] = array();

        if ($r->num_rows()>0) {
            foreach ($r->result() as $key => $v) {
                $class_action = '';
                if ($v->status != 1) {
                    $status = $this->xwb_purchasing->getStatus('request', $v->status);
                } else {
                    $headDenied = $this->checkHeadDenied($v->id);
                    if ($headDenied) {
                        $status = '<span class="label label-warning">Head/Recommending approval denied</span>';
                        $class_action = 'has-action';
                    } else {
                        $status = $this->xwb_purchasing->getStatus('request', $v->status);
                    }
                }
                
                
                if (in_array($v->status, $this->member_status_action)) {
                    $class_action = 'has-action';
                }

                $data['data'][] = array(
                                        sprintf('PR-%08d', $v->id),
                                        '<a class="btn btn-default btn-xs '.$class_action.'" href="'.base_url('request/view_request/'.$v->id).'">'.$v->request_name.'</a>',
                                        date("F j, Y, g:i a", strtotime($v->date_created)),
                                        ($v->date_needed==null?"":date("F j, Y", strtotime($v->date_needed))),
                                        $v->purpose,
                                        nl2br($v->admin_note),
                                        '<a href="javascript:;" onClick="xwb.viewItems('.$v->id.')" class="btn btn-app"><i class="fa fa-search"></i>View Items</a>',
                                        $status." ".'<label class="badge badge-info">'.time_elapse($v->date_updated).'</label>',
                                        '<a href="'.base_url('request/edit_request/'.$v->id).'" class="btn btn-xs btn-warning">Edit</a>
										<a target="_blank" href="'.base_url('request/print_request/'.$v->id).'" class="btn btn-xs btn-info">Print Request</a>',
                                        );
            }
        }
        echo $this->xwbJsonEncode($data);
    }


    /**
     * Get Request
     *
     * @return json
     */
    public function editRequest()
    {
        $this->redirectUser();
        $req_id = $this->input->post('req_id');
        $u = $this->db->get_where('request', array('id'=>$req_id))->row();
        echo $this->xwbJsonEncode($u);
        exit();
    }


    /**
     * Update Request
     *
     * @return json
     */
    public function updateRequest()
    {
        $this->load->library('form_validation');
        $this->redirectUser();
        $this->form_validation->set_rules('prno', 'Purchase No.', 'required|alpha_dash');
        $this->form_validation->set_rules('request_type', 'Request Type', 'required');
        /*$this->form_validation->set_rules('department_head', 'Department Head', 'required');*/
                
        
        if ($this->form_validation->run() == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
        } else {
            $data = array(
                    'prno' => $this->input->post('prno'),
                    'date_requested' => $this->input->post('date_requested'),
                    'user_id' => $this->input->post('user_id'),
                    'dept' => $this->input->post('dept'),
                    'request_type' => $this->input->post('request_type'),
                    'request_item' => $this->input->post('request_item'),
                    'request_description' => $this->input->post('request_description'),
                    'request_qty' => $this->input->post('request_qty'),
                    'state' => $this->input->post('state'),
            );

            $this->db->where('id', $this->input->post('id'));
            $this->db->update('request', $data);


            $data['status'] = true;
            $data['message'] = 'Request has been successfully updated.';
        }

        echo $this->xwbJsonEncode($data);
        exit();
    }


    /**
     * Update Request and Items
     *
     * @return json
     */
    public function updateItemRequest()
    {
        $this->load->library('form_validation');
        $this->redirectUser();
        $this->form_validation->set_rules('request_name', 'Type of Request.', 'required');
        $this->form_validation->set_rules('purpose', 'Purpose', 'required');
        //$this->form_validation->set_rules('priority_level', 'Priority Level', 'required');
        $this->form_validation->set_rules('date_needed', 'Date Needed', 'required');
        $this->form_validation->set_rules('product[]', 'Product', 'required');
        $this->form_validation->set_rules('quantity[]', 'Quantity', 'required|integer');
                
        if ($this->input->post('new_product_name[]') != null) {
            $this->form_validation->set_rules('new_product_name[]', 'Product Name', 'required');
        }

        /*if($this->input->post('new_product_description[]') != null)
			$this->form_validation->set_rules('new_product_description[]', 'Product Description', 'required');*/

        if ($this->input->post('new_supplier[]') != null) {
            $this->form_validation->set_rules('new_supplier[]', 'Product Supplier', 'required');
        }

        if ($this->input->post('new_quantity[]') != null) {
            $this->form_validation->set_rules('new_quantity[]', 'Product Quantity', 'required|integer');
        }

        if ($this->form_validation->run() == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
        } else {
            $this->load->model('supplier/Supplier_model', 'Supplier');
            $this->load->model('product/Product_model', 'Product');
            
            $posts = $this->input->post();
            
            $data = array(
                    'request_name' => $this->input->post('request_name'),
                    'purpose' => $this->input->post('purpose'),
                    'date_needed' => $this->input->post('date_needed'),
            );

            $this->db->where('id', $this->input->post('request_id'));
            $this->db->update('request_list', $data);


            

            //insert newly added product/ item
            if ($this->input->post('new_product_name') != null) {
                //pre($posts['new_supplier'],true);
                foreach ($posts['new_product_name'] as $key => $value) {
                    $arr_data = array(
                        'request_id' => $posts['request_id'],
                        'product_id' => ($posts['product_id'][$key]==''?0:$posts['product_id'][$key]),
                        'product_name' => $posts['new_product_name'][$key],
                        'product_description' => $posts['new_product_description'][$key],
                        'quantity' => $posts['new_quantity'][$key],
                        'date_updated' => date('Y-m-d H:i:s'),
                        );

                    if ($this->log_user_data->group_name != 'members') {
                        if (ctype_digit($posts['new_supplier'][$key])) {
                            $s = $this->Supplier->getSupplier($posts['new_supplier'][$key])->row();
                            if (is_null($s)) {
                                $arr_data['supplier_id'] = 0;
                                $arr_data['supplier'] = $posts['new_supplier'][$key];
                            } else {
                                $arr_data['supplier_id'] = $posts['new_supplier'][$key];
                                $arr_data['supplier'] = $s->supplier_name;
                            }
                        } else {
                            $arr_data['supplier_id'] = 0;
                            $arr_data['supplier'] = $posts['new_supplier'][$key];
                        }
                    }

                    $db_data[] = $arr_data;
                }
                
                $this->db->insert_batch('po_items', $db_data);
            }

                
            foreach ($posts['product_name'] as $key => $value) {
                $arr_data = array(
                'product_name' => $posts['product_name'][$key],
                'product_description' => $posts['product_description'][$key],
                'quantity' => $posts['quantity'][$key],
                'date_updated' => date('Y-m-d H:i:s'),
                );


                if ($this->log_user_data->group_name != 'members') {
                    if (ctype_digit($posts['supplier'][$key])) {
                        $s = $this->Supplier->getSupplier($posts['supplier'][$key])->row();
                        if (is_null($s)) {
                            $arr_data['supplier_id'] = 0;
                            $arr_data['supplier'] = $posts['supplier_name'][$key];
                        } else {
                            $arr_data['supplier_id'] = $posts['supplier'][$key];
                            $arr_data['supplier'] = $s->supplier_name;
                        }
                    } else {
                        $arr_data['supplier_id'] = 0;
                        $arr_data['supplier'] = $posts['supplier'][$key];
                    }
                }

                if (ctype_digit($posts['product'][$key])) {
                    $p = $this->Product->getProduct($posts['product'][$key])->row();
                    if (is_null($p)) {
                        $arr_data['product_id'] = 0;
                        $arr_data['product_name'] = $posts['product_name'][$key];
                    } else {
                        $arr_data['product_id'] = $posts['product'][$key];
                        $arr_data['product_name'] = $p->product_name;
                    }
                } else {
                    $arr_data['product_id'] = 0;
                    $arr_data['product_name'] = $posts['product'][$key];
                }
                $db_data = $arr_data;


                $this->Request->updateItem($key, $db_data);
            }




            $data['status'] = true;
            $data['message'] = 'Request has been successfully updated.';
        }

        echo $this->xwbJsonEncode($data);
        exit();
    }

    /**
     * Add Request method
     *
     * @return json
     */
    public function addRequest()
    {
        $this->load->library('form_validation');
        $this->redirectUser();
        $this->form_validation->set_rules('prno', 'Purchase No.', 'required|alpha_dash');
        $this->form_validation->set_rules('request_type', 'Request Type', 'required');
        /*$this->form_validation->set_rules('department_head', 'Department Head', 'required');*/
        
        if ($this->form_validation->run() == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
        } else {
            $data = array(
                    'prno' => $this->input->post('prno'),
                    'date_requested' => $this->input->post('date_requested'),
                    'user_id' => $this->input->post('user_id'),
                    'dept' => $this->input->post('dept'),
                    'request_type' => $this->input->post('request_type'),
                    'request_item' => $this->input->post('request_item'),
                    'request_description' => $this->input->post('request_description'),
                    'request_qty' => $this->input->post('request_qty'),
                    'state' => $this->input->post('state'),
                    );

            $this->db->insert('request', $data);
            $data['status'] = true;
            $data['message'] = 'Request has been successfully added.';
        }

        echo $this->xwbJsonEncode($data);
        exit();
    }


    /**
     * Delete Request
     *
     * @return array
     */
    public function deleteRequest()
    {
        $this->redirectUser();
        $rows = $this->Request->deleteRequest();
        if ($rows > 0) {
            $data['status'] = true;
            $data['message'] = "Request has been deleted";
        } else {
            $data['status'] = false;
            $data['message'] = "Error deleting record, please contact the system programmer";
        }

        echo $this->xwbJsonEncode($data);
        exit();
    }

    /**
     * New request step validations
     *
     * @return json
     */
    public function newRequestSteps()
    {
        $this->redirectUser();
        $steps = $this->input->post('step');
        switch ($steps) {
            case 1:
                $data = $this->step1($steps);
                break;
            case 2:
                $data = $this->step2($steps);
                break;
            case 3:
                $data = $this->step3($steps);
                break;
        }


        echo $this->xwbJsonEncode($data);
    }


    /**
     * Step 1 new request validation
     *
     * @param int $step
     * @return array
     */
    public function step1($step)
    {
        $this->load->library('form_validation');
        $this->redirectUser();
        $this->form_validation->set_rules('request_name', 'Type of Request', 'required');
        $this->form_validation->set_rules('purpose', 'Purpose', 'required');
        //$this->form_validation->set_rules('priority_level', 'Priority Level', 'required');
        $this->form_validation->set_rules('date_needed', 'Date Needed', 'required');
        
        if ($this->form_validation->run() == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
        } else {
            $inputs = $this->input->post();
            $session_data['new_request'][$inputs['step']] = $inputs;
            unset($session_data['new_request'][$inputs['step']]['step']);

            //$this->session->set_userdata($session_data);
            $_SESSION['new_request'][$inputs['step']] = $session_data['new_request'][$inputs['step']];
            $data['status'] = true;
        }

        return $data;
    }


    /**
     * Step 2 new request validation
     * @param int $step
     * @return array
     */
    public function step2($step)
    {
        $this->load->library('form_validation');
        $this->redirectUser();
        $this->form_validation->set_rules('item[]', 'Item Name', 'required');
        $this->form_validation->set_rules('qty[]', 'Quantity', 'required|integer');
        
        if ($this->form_validation->run() == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
        } else {
            //pre($_SESSION['new_request'],TRUE);
            $inputs = $this->input->post();
            $session_data['new_request'][$inputs['step']] = $inputs;
            

            unset($session_data['new_request'][$inputs['step']]['step']);

            $_SESSION['new_request'][$inputs['step']] = $session_data['new_request'][$inputs['step']];
            //$this->session->set_userdata($session_data);
            //pre($_SESSION['new_request']);
            $data['status'] = true;
            $data['content'] = $this->getContentForReview();
        }

        return $data;
    }



    /**
     * Get content for review on the last step
     *
     * @return string
     */
    public function getContentForReview()
    {
        $this->redirectUser();
        $unit_measurement = $this->config->item('unit_measurement');
        $new_requests = $this->session->userdata('new_request');
        $user_id = $this->log_user_data->user_id;
        $this->load->model('user/User_model', 'User');
        $user = $this->User->getUser($user_id)->row();
        if ($user==null) {
            $data['message'] = 'Error: Please update your profile.';
            $data['status'] = false;
            echo $this->xwbJsonEncode($data);
            exit();
        }
        $items = "";

        foreach ($new_requests[2]['item'] as $key => $value) {
            $row = $key+1;
            $items .= '<tr class="row_'.$row.'">';
                $items .= '<td>'.$value.'</td><td>'.$new_requests[2]['description'][$key].'</td><td>'.$unit_measurement[$new_requests[2]['unit_measurement'][$key]].'</td><td>'.$new_requests[2]['qty'][$key].'</td><td><a class="btn btn-info btn-xs xwb-view-attach-preview" href=""><i class="fa fa-file-image-o"></i> Attachment</a></td>';
            $items .= '</tr>';
        }

        $html = "";
        $html .= '<div class="row"><div class="col-md-4"><div class="form-group">
                            <label class="control-label col-md-4 col-sm-4" for="requestor">Requisitioner Name <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6">
                              <input disabled type="text" value="'.ucwords($user->first_name." ".$user->last_name).'" id="requestor" name="requestor" class="form-control col-md-7 col-xs-12">
                            </div>
                  </div></div>';

        $html .= '<div class="col-md-4"><div class="form-group">
                            <label class="control-label col-md-4 col-sm-4" for="department">Department <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6">
                              <input disabled type="text" value="'.$user->dep_description.'" id="department" name="department" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                  </div></div>';

        $html .= '<div class="col-md-4"><div class="form-group">
                            <label class="control-label col-md-4 col-sm-4" for="request_name">Type of Request <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6">
                              <input disabled type="text" value="'.$new_requests[1]['request_name'].'" id="request_name" name="request_name" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                  </div></div></div>';

        $html .= '<hr class="clearfix" />';

        $html .= '<div class="table-responsive">
		                        	<table class="table table_items">
		                        		<thead>
		                        			<tr>
		                        				<th>Item</th>
		                        				<th>Description</th>
		                        				<th>Unit</th>
		                        				<th>Qty</th>
		                        				<th>Attachment</th>
		                        			</tr>
		                        		</thead>
		                        		<tbody>
		                        			'.$items.'
		                        		</tbody>
		                        	</table>
		                        </div>';

        return $html;
    }


    public function step3($step)
    {
        $this->fileRequest();
    }


    /**
     * File a request
     *
     * @return json
     */
    public function fileRequest()
    {
        $this->redirectUser();
        $this->load->model('user/User_model', 'User');
        $new_requests = $this->session->userdata('new_request');
        $user = $this->User->getUser($this->log_user_data->user_id)->row(); // get current user

        $head_users = $this->User->getHeadDepartmentUsers($user->dep_name)->result(); // get all head of the department of the current users department
        
        
        
        if (count($head_users)==0) {
            $data['status'] = false;
            $data['message'] = "There is no head assigned on your department yet, please call the purchasing department regarding this error";
            echo $this->xwbJsonEncode($data);
            exit();
        }

        /* save to request_list table */
        if (array_key_exists(1, $new_requests) && array_key_exists(2, $new_requests)) {
            $data = array(
                'user_id' => $user->id,
                'request_name' => $new_requests[1]['request_name'],
                'purpose' => $new_requests[1]['purpose'],
                //'priority_level' => $new_requests[1]['priority_level'],
                'date_needed' => $new_requests[1]['date_needed'],
                'date_updated' => date("Y-m-d H:i:s")
            );

            $res = $this->db->insert('request_list', $data);
            $insert_id = $this->db->insert_id(); // get request list last insert id


        /* insert items to po_items */

            if ($res) {
                $this->load->helper('file');

                $insert_data = [];
                foreach ($new_requests[2]['item'] as $key => $value) {
                    $insert_data = array(
                                    'product_id' => ($new_requests[2]['product_id'][$key]==''?0:$new_requests[2]['product_id'][$key]),
                                    'product_name' => $value,
                                    'product_description' => $new_requests[2]['description'][$key],
                                    'unit_measurement' => $new_requests[2]['unit_measurement'][$key],
                                    'quantity' => $new_requests[2]['qty'][$key],
                                    'request_id' => $insert_id
                                    );
                    $this->db->insert('po_items', $insert_data);
                    $po_id = $this->db->insert_id();
                    $row = $key+1;

                
                    if (!is_null($this->session->req_attachment) && array_key_exists($row, $this->session->req_attachment)) {
                        $req_attachment = $this->session->req_attachment[$row];
            
                        if (count($req_attachment)>0) {
                            $destination_path = FCPATH.'uploads/';

                            if (!is_dir($destination_path)) {
                                mkdir($destination_path, 0777, true);
                            }

                            $attachment_data = [];
                            foreach ($req_attachment as $raKey => $raVal) {
                                if (file_exists($raVal['full_path'])) {
                                    rename($raVal['full_path'], $destination_path.$raVal['file_name']);
                                    $file_info = get_file_info(FCPATH.'uploads/'.$raVal['file_name']);
                                    $dir_path = dirname(FCPATH.'uploads/'.$raVal['file_name']);

                                    $attachment_data[] = array(
                                        'po_id' => $po_id,
                                        'file_name' => $raVal['file_name'],
                                        'file_type' => $raVal['file_type'],
                                        'file_path' => $dir_path,
                                        'full_path' => $file_info['server_path'],
                                        'raw_name' => $raVal['raw_name'],
                                        'orig_name' => $raVal['orig_name'],
                                        'client_name' => $raVal['client_name'],
                                        'file_ext' => $raVal['file_ext'],
                                        'file_size' => $raVal['file_size'],
                                        'is_image' => $raVal['is_image'],
                                        'image_width' => $raVal['image_width'],
                                        'image_height' => $raVal['image_height'],
                                        'image_type' => $raVal['image_type'],
                                        'image_size_str' => $raVal['image_size_str'],
                                        );

                                    $this->db->insert_batch('attachment', $attachment_data);
                                }
                            }
                        }
                    }
                }
            }


        /* Send email notification to all head users */
            foreach ($head_users as $key => $value) {
                $data_approval[] = array(
                'request_id'=> $insert_id,
                'user_id'=> $value->id,
                );

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
                    'user_to' => $value->id,
                    'message' => '',
                    'request_id' => $insert_id,
                    );

                $this->xwb_purchasing->setShortCodes($shortcodes);

                $condition = $this->xwb_purchasing->getShortCodes();

                $msg = $this->xwb_purchasing->getMessage('new_request');
                $message = do_shortcode($msg['message'], $condition);
                $site_title = $this->config->item('site_title');
                $this->xwb_purchasing->sendmail($condition['email_to'], $msg['subject'], $message, $condition['email_from'], $site_title, $msg['subject']);
            }

            $res = $this->db->insert_batch('request_approval', $data_approval);


        /* Adding to history transaction */
            $this->xwb_purchasing->addHistory('request_list', $insert_id, 'Created a new Request', 'Created a new Request', $this->log_user_data->user_id);

            if ($res) {
                $data['status'] = true;
                $data['message'] = "Request has been sent for department head approval";
            } else {
                $data['status'] = false;
                $data['message'] = "Error saving to database, please contact the programmer";
            }
        } else {
            $data['status'] = false;
            $data['message'] = "No data has been set. Please try creating a new request";
        }
        echo $this->xwbJsonEncode($data);
    }


    /**
     * Request list view in the admin
     *
     * @return mixed
     */
    public function reqlist()
    {
        $this->redirectUser(array('admin','canvasser','budget','auditor','board'));
        $this->load->model('user/User_model', 'User');
        $this->load->model('request_category/Request_category_model', 'ReqCat');

        $data['budget_users'] = $this->User->getUsersByGroup('budget')->result();

        $data['page_title'] = 'Request List'; //title of the page
        $data['page_script'] = 'request_list'; // script filename of the page user.js
        $data['canvasser'] = $this->User->getUsersByGroup('canvasser')->result();
        $data['req_cat'] = $this->ReqCat->getReqCat()->result();

        $this->renderPage('request/request_list', $data);
    }

    /**
     * Get all request list in the admin
     *
     * @return json
     */
    public function getAdminRequest()
    {
        $this->redirectUser();
        $this->load->model('user/User_model', 'User');

        $this->Request->getAdminRequest();

        $recordsTotal = $this->db->count_all_results();
        $recordsFiltered = $this->Request->countFiltered('getAdminRequest');
        

        $this->Request->getAdminRequest();
        if ($this->input->get('length') != -1) {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
        }
        $r = $this->db->get();


        $data['data'] = array();

        if ($r->num_rows()>0) {
            foreach ($r->result() as $key => $v) {
                if ($v->status == 1) {
                    $disabled = "disabled";
                } else {
                    $disabled = "";
                }

                $data['data'][] = array(
                                        sprintf('PR-%08d', $v->id),
                                        '<a class="btn btn-default btn-xs" href="'.base_url('request/view_request/'.$v->id).'">'.$v->request_name.'</a>',
                                        date("F j, Y, g:i a", strtotime($v->date_created)),
                                        ucwords($v->full_name),
                                        $v->department,
                                        $v->branch,
                                        $v->purpose,
                                        '<a href="javascript:;" onClick="xwb.viewItems('.$v->id.')" class="btn btn-app"><i class="fa fa-search"></i>View Items</a>',
                                        ($v->date_needed==null?"":date("F j, Y", strtotime($v->date_needed))),
                                        $this->reqActionBtn($v),
                                            $this->xwb_purchasing->getStatus('request', $v->status)." ".'<label class="badge">'.time_elapse($v->date_updated).'</label>',
                                        );
            }
        }
        $data['draw'] = $this->input->get('draw');
        $data['recordsTotal'] = $recordsTotal;
        $data['recordsFiltered'] = $recordsFiltered;
        echo $this->xwbJsonEncode($data);
    }


    /**
     * Get items per request
     *
     * @return json
     */
    public function getRequestItems()
    {
        $this->redirectUser();
        $request_id = $this->input->get('request_id');
        $i = $this->Request->getItemsPerRequest($request_id);
        $request = $this->Request->getRequest($request_id)->row();
        $data['data'] = [];
        if ($i->num_rows()>0) {
            foreach ($i->result() as $key => $v) {
                $done_icon = '';
                $delete_item = '';
                if ($v->status == 2 && $v->status == 0) {
                    $delete_item = '<a class="btn btn-danger btn-xs" href="javascript:;" onClick="xwb.deleteItem('.$v->id.')"><i class="fa fa-remove"></i> Delete Item</a>';
                }
                if ($v->status != 0) {
                    $done_icon = '<i class="fa fa-check green"></i> ';
                }

                if ($this->log_user_data->group_name == 'budget') {
                    $expenditure = '<select name="expenditure" data-itemid="'.$v->id.'" class="from-control expenditure">';
                        $expenditure .= '<option value="">Select Option</option>';
                        $expenditure .= '<option value="OPEX" '.($v->expenditure == 'OPEX'?'selected':'').'>'.getExpenditureName('OPEX').'</option>';
                        $expenditure .= '<option value="CAPEX" '.($v->expenditure == 'CAPEX'?'selected':'').'>'.getExpenditureName('CAPEX').'</option>';
                    $expenditure .= '</select>';

                    $data['data'][] = array(
                        $done_icon.$v->product_name,
                        $v->product_category,
                        $v->product_description,
                        $v->supplier,
                        $v->quantity,
                        $expenditure,
                        '<a class="btn btn-info btn-xs" href="javascript:;" onClick="xwb.viewAttachmentPreview('.$v->id.')"><i class="fa fa-file-image-o"></i> Attachment</a>',
                        ($v->eta == null?"":date("F j, Y", strtotime($v->eta))),
                        ($v->date_delivered==null?"":date("F j, Y", strtotime($v->date_delivered))),
                        ($v->eta == null?"":date("F j, Y", strtotime($v->eta))),
                    );
                } elseif ($this->log_user_data->group_name == 'admin') {
                    $attachment = '';
                    if ($request->archive!=1) {
                        $attachment = '<a class="btn btn-info btn-xs" href="javascript:;" onClick="xwb.viewAttachmentPreview('.$v->id.')"><i class="fa fa-file-image-o"></i> Attachment</a>';
                    }
                    
                    $data['data'][] = array(
                        $done_icon.$v->product_name,
                        $v->product_category,
                        $v->product_description,
                        $v->supplier,
                        $v->quantity,
                        number_format($v->unit_price, 2, '.', ','),
                        number_format(($v->unit_price * $v->quantity), 2, '.', ','),
                        $attachment.$delete_item,
                        $v->eta,
                        $v->date_delivered,
                    );
                } else {
                    $data['data'][] = array(
                        $done_icon.$v->product_name,
                        $v->product_category,
                        $v->product_description,
                        $v->quantity,
                        '<a class="btn btn-info btn-xs" href="javascript:;" onClick="xwb.viewAttachmentPreview('.$v->id.')"><i class="fa fa-file-image-o"></i> Attachment</a>',
                        ($v->eta == null?"":date("F j, Y", strtotime($v->eta))),
                        ($v->date_delivered==null?"":date("F j, Y", strtotime($v->date_delivered))),
                        ($v->eta == null?"":date("F j, Y", strtotime($v->eta))),
                    );
                }
            }
        }
        echo $this->xwbJsonEncode($data);
    }


    /**
     * Get items per request assigned to canvasser
     *
     * @return json
     */
    public function getRequestItemsCanvasser()
    {
        $this->redirectUser();
        $request_id = $this->input->get('request_id');
        $i = $this->Request->getItemsPerRequest($request_id);
        $data['data'] = array();
        
        if ($i->num_rows()>0) {
            foreach ($i->result() as $key => $v) {
                $done_icon = '';
                if ($v->status != 0) {
                    $done_icon = '<i class="fa fa-check green"></i> ';
                }
                $data['data'][] = array(
                                        /*$v->id,*/
                                        $done_icon.$v->product_name,
                                        '<a class="btn btn-info btn-xs" href="javascript:;" onClick="xwb.viewAttachmentPreview('.$v->id.')"><i class="fa fa-file-image-o"></i> Attachment</a>',
                                        $v->product_description,
                                        $v->supplier,
                                        $v->quantity,
                                        number_format($v->unit_price, 2, '.', ','),
                                        number_format(($v->unit_price * $v->quantity), 2, '.', ','),
                                        '<a class="btn btn-danger btn-xs" href="javascript:;" onClick="xwb.deleteItem('.$v->id.')"><i class="fa fa-remove"></i> Delete Item</a>'
                                        );
            }
        }
        echo $this->xwbJsonEncode($data);
    }


    /**
     * Assign to canvasser
     *
     * @return json
     */
    public function assignCanvasser()
    {
        $this->load->library('form_validation');
        $this->redirectUser();
        $this->load->model('canvasser/Canvasser_model', 'Canvasser');

        $this->form_validation->set_rules('user_id', 'User', 'required|integer');
        $this->form_validation->set_rules('request_id', 'Request', 'required');
        $this->form_validation->set_rules('req_cat', 'Request Category', 'required');

        if ($this->form_validation->run() == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
        } else {
            $posts = $this->input->post();
            $data = array(
                        'status' => 3,
                        'req_cat' => $posts['req_cat'],
                        'date_updated' => date('Y-m-d H:i:s')
                        );
            $this->db->where('id', $posts['request_id']);
            $this->db->update('request_list', $data);


            $c = $this->Canvasser->getCanvassByRequest($posts['request_id'])->row();
            if (is_null($c)) {
                $db_data = array(
                    'request_id' =>$posts['request_id'],
                    'user_id' =>$posts['user_id'],
                    'user_from' => $this->log_user_data->user_id,
                    'date_updated' =>date('Y-m-d H:i:s'),

                );
                $res = $this->db->insert('canvass', $db_data);
            } else {
                $db_data = array(
                    'user_id' =>$posts['user_id'],
                    'date_updated' =>date('Y-m-d H:i:s'),
                );
                $this->db->where('id', $c->id);
                $res = $this->db->update('canvass', $db_data);
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
                    'user_to' => $posts['user_id'],
                    'message' => $this->input->post('reason'),
                    'request_id' => $posts['request_id'],
                );

            $this->xwb_purchasing->setShortCodes($shortcodes);

            $condition = $this->xwb_purchasing->getShortCodes();

            /* sending email notification */
            $msg = $this->xwb_purchasing->getMessage('to_canvass');
            $message = do_shortcode($msg['message'], $condition);
            $site_title = $this->config->item('site_title');
            $this->xwb_purchasing->sendmail($condition['email_to'], $msg['subject'], $message, $condition['email_from'], $site_title, $msg['subject']);

            $this->xwb_purchasing->addHistory('request_list', $posts['request_id'], 'Assigned to Canvasser', 'Assigned to Canvasser', $this->log_user_data->user_id);

            if ($res) {
                $data['status'] = true;
                $data['message'] = 'Request has been assigned to canvasser';
            } else {
                $data['status'] = false;
                $data['message'] = 'Error updating record, please contact the programmer';
            }
        }

    
        echo $this->xwbJsonEncode($data);
    }


    /**
     * Deny Request
     *
     * @return json
     */
    public function denyRequest()
    {
        $this->load->library('form_validation');
        $this->redirectUser();
        $this->form_validation->set_rules('reason', 'Reason / Admin note', 'required');
        $this->form_validation->set_rules('request_id', 'Request', 'required');

        if ($this->form_validation->run() == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
        } else {
            $posts = $this->input->post();
            $data = array(
                        'status' => 4,
                        'admin_note' => $posts['reason'],
                        'date_updated' => date('Y-m-d H:i:s')
                        );
            $this->db->where('id', $posts['request_id']);
            $this->db->update('request_list', $data);
            $res = $this->db->affected_rows();
            if ($res>0) {
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
     * View create PO form
     *
     * @param int $request_id
     * @return mixed
     */
    public function create_po($request_id)
    {
        $this->redirectUser(array('admin','board'));
        
        $data['page_title'] = 'Create Purchase Order'; //title of the page
        $data['page_script'] = 'create_po'; // script filename of the page user.js
        $this->renderPage('create_po', $data);
    }


    /**
     * Create PO save function
     *
     * @return json
     */
    public function create_po_submit()
    {
    }




    /**
     * Approve methon for department head and pass to purchasing department
     *
     * @return json
     */
    public function approveToPurchasing()
    {
        $this->load->library('form_validation');
        $this->redirectUser();
        $this->form_validation->set_rules('dept_head_note', 'Department Head remarks', 'required');
        $this->form_validation->set_rules('request_id', 'Request', 'required');

        if ($this->form_validation->run() == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
        } else {
            $posts = $this->input->post();
            $data = array(
                        'status' => 2,
                        'dept_head_note' => $posts['dept_head_note'],
                        'date_updated' => date('Y-m-d H:i:s')
                        );
            $this->db->where('id', $posts['request_id']);
            $res = $this->db->update('request_list', $data);
            if ($res) {
                $data['status'] = true;
                $data['message'] = 'Request has been approved and file to purchasing department';
            } else {
                $data['status'] = false;
                $data['message'] = 'Error updating record, please contact the programmer';
            }
        }

        echo $this->xwbJsonEncode($data);
    }


    /**
     * New request form for admin
     *
     * @return mixed
     */
    public function new_list()
    {
        $this->redirectUser(array('admin','board'));
        $this->load->model('product/Product_model', 'Product');
        $this->load->model('request_category/Request_category_model', 'ReqCat');

        unset($_SESSION['new_request']);
        $data['products'] = $this->Product->getProducts()->result();
        $data['req_cat'] = $this->ReqCat->getReqCat()->result();
        $data['unit_measurements'] = $this->config->item('unit_measurement');

        $data['page_title'] = 'New Request'; //title of the page
        $data['page_script'] = 'new_request'; // script filename of the page user.js
        $this->renderPage('request/new_request', $data);
    }


    /**
     * Print request
     *
     * @param int $request_id
     * @return mixed
     */
    public function print_request($request_id)
    {
        $this->redirectUser(array('admin','members','canvasser','budget','auditor','property','board'));

        if ($this->log_user_data->group_name!='members') {
            $this->print_reqadmin($request_id);
        } else {
            $this->print_requisitioner($request_id);
        }
    }

    /**
     * Print Request from admin view
     *
     * @param int $request_id
     * @return mixed
     */
    public function print_reqadmin($request_id)
    {
        $this->redirectUser(array('admin','members','canvasser','budget','auditor','board'));
        $this->load->model('User/User_model', 'User');
        $this->load->model('Request/Request_model', 'Request');
        $this->load->model('Budget/Budget_model', 'Budget');
        $this->load->model('Canvasser/Canvasser_model', 'Canvasser');
        $this->load->model('Purchase_order/Purchase_order_model', 'PO');
        $this->load->model('Branch/Branch_model', 'Branch');
        $branches = $this->Branch->getBranch()->result_array();
        $branches = array_column($branches, 'description');
        $branches = implode(' * ', $branches);


        $c = $this->Canvasser->getCanvassByRequest($request_id)->row();
        //$request_id = $c->request_id;
        
        $items = $this->Request->getItemsPerRequestCanvasser($request_id)->result();
        $request = $this->Request->getRequest($request_id)->row();
        $requestor = $this->User->getUser($request->user_id)->row();
        $this->load->library('pdf');
        $filename = "request_$request_id";
        $pdfFilePath = FCPATH."downloads/pdf/$filename.pdf";
        $pdf = $this->pdf->load();
        $stylesheet = file_get_contents(FCPATH.'assets/css/pdfstyle.css');

        $req_approval = $this->Request->getReqForApprovalByRequest($request_id)->result();
        $budget = $this->Budget->getBudgetByRequest($request_id)->row();

        if ($budget == null || ($budget->status != 1 && $budget->status != 5)) {
            $budget_name = '';
            $budget_date = '';
        } else {
            $budget_name = $budget->full_name;
            $budget_date = date('F j, Y, g:i a', strtotime($budget->date_updated));
        }

        $canvasser = $this->Canvasser->getCanvassByRequest($request_id)->row();
        if ($canvasser == null || $canvasser->date_updated == null) {
            $canvass_name = '';
            $canvass_date = '';
        } else {
            $canvass_name = $canvasser->full_name;
            $canvass_date = date('F j, Y, g:i a', strtotime($canvasser->date_updated));
        }

        if ($request->status==13) {
            $approve_purchase_by = $this->User->getUser($request->approve_purchase_by)->row();
            $approve_purchase_date = date('F j, Y, g:i a', strtotime($canvasser->date_updated));
        } else {
            $approve_purchase_by = "";
            $approve_purchase_date = "";
        }
        $res_po = $this->PO->getPOByRequest($request->id)->row();
        //$pdf->SetDisplayMode('fullpage');
        ob_start();
        ?>
        <h3 class="text-center"><?php echo getConfig('company_name'); ?></h3>
        <p class="text-center"><?php echo $branches; ?></p>
        <p class="text-center">Purchasing Department</p>
        
        <div class="received-date">
            <h5>RECEIVED</h5>
            <p>Date: _______________</p>
            <p>Time: _______________</p>
            <p>By: &emsp; _______________</p>
        </div>
        <hr />
        <h3 class="text-center">Purchase Requisition Slip</h3>
        <p class="underline width-150 pull-left clearfix"><b>PRS No.: </b><?php echo sprintf('PR-%08d', $request->id); ?></p>
        <p class="underline width-150 pull-left clearfix"><b>POF No.: </b><?php echo ($res_po==null?"":sprintf('PO-%08d', $res_po->id)); ?></p>
        <br />
        <br />
        <table border="1" cellpadding="1" cellspacing="0">
            <thead>
                <tr>
                    <th align="center" width="5%" rowspan="4">
                        Quantity
                    </th>
                    <th align="center" width="5%" rowspan="4">
                        Unit
                    </th>
                    <th align="center" width="15%" rowspan="4">
                        Product/Services
                    </th>
                    <th align="center" width="20%" rowspan="4">
                        Description/Specifications (Brand, Model, Catalog/Product No., Color, Size/Dimension Author, Year published, et.al)
                    </th>
                    <th colspan="4" width="65%"><h5>(For Purchasing Department use only)</h5></th>
                </tr>
                <tr>
                    <th colspan="4" width="65%"><h5>Quotations</h5></th>
                </tr>
                <tr>
                    <th width="16.25%"><h5>Name of Supplier and Price Per Unit</h5></th>
                    <th width="16.25%"><h5>Name of Supplier and Price Per Unit</h5></th>
                    <th width="16.25%"><h5>Name of Supplier and Price Per Unit</h5></th>
                    <th width="16.25%"><h5>Name of Supplier and Price Per Unit</h5></th>
                </tr>
                <tr>
                    <th width="16.25%"><h5> &nbsp; </h5></th>
                    <th width="16.25%"><h5> &nbsp; </h5></th>
                    <th width="16.25%"><h5> &nbsp; </h5></th>
                    <th width="16.25%"><h5> &nbsp; </h5></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $counter = 1;
                $sum = 0;
                $column_1 = $column_2 = $column_3 = $column_4 = 0;
                foreach ($items as $key => $value) :
                ?>
                <?php
                    $supplier = getSuplliersFromCanvassed($value->request_id, $value->product_id, $value->product_name);
                    $supplier_num = $supplier->num_rows();
                    $supplier = $supplier->result();
                    $supplier_1 = null;
                    $supplier_2 = null;
                    $supplier_3 = null;
                    $supplier_4 = null;
                for ($i=0; $i < $supplier_num; $i++) {
                    $post_var = $i+1;
                    ${'supplier_'.$post_var} = $supplier[$i];
                }
                    $row_total = 0;
                ?>
                    <tr>

                        <!-- <td><?php echo $counter; ?></td> -->
                        <td><?php echo $value->quantity; ?></td>
                        <td><?php echo $value->unit_measurement; ?></td>
                        <td><?php echo $value->product_name; ?></td>
                        <td>
                        <p class="label">
                            <?php echo $value->product_description; ?>
                        </p>
                        </td>
                        <td>
                            <?php
                                $supplier = (isset($supplier_1)?$supplier_1:null);
                                $cp_status = (is_null($supplier)?null:$supplier->cp_status);
                                $po_item_id = ($cp_status == null?'new1_'.$value->id:$supplier->id.'_'.$supplier->cp_id);
                                $supplier_id = (is_null($supplier)?null:$supplier->supplier_id);

                                $supplier_name = (is_null($supplier)?null:$supplier->supplier);
                                $unit_price = (is_null($supplier)?0:$supplier->price);
                                $quantity = (is_null($supplier)?0:$supplier->quantity);
                                $row_total = $row_total + ($unit_price * $quantity);
                                $column_1 = $column_1 + ($cp_status==1?($unit_price * $quantity):0);
                            ?>
                            <?php
                            if (($unit_price*$quantity) != 0) :
                            ?>
                            <p class="label text-center"><?php echo $cp_status==1?'<b class="text-12">&#10004;</b>':''; ?><?php echo $supplier_name; ?> </p>
                            <p class="label"><?php echo number_format($unit_price, 2, '.', ',')." * ".$quantity; ?></p>
                            <?php
                            endif;
                            ?>
                        </td>
                    
                        <td>
                            <?php
                                $supplier = (isset($supplier_2)?$supplier_2:null);
                                $cp_status = (is_null($supplier)?null:$supplier->cp_status);
                                $po_item_id = ($cp_status == null?'new2_'.$value->id:$supplier->id.'_'.$supplier->cp_id);
                                $supplier_id = (is_null($supplier)?null:$supplier->supplier_id);

                                $supplier_name = (is_null($supplier)?null:$supplier->supplier);
                                $unit_price = (is_null($supplier)?0:$supplier->price);
                                $quantity = (is_null($supplier)?0:$supplier->quantity);
                                $row_total = $row_total + ($unit_price * $quantity);
                                $column_2 = $column_2 + ($cp_status==1?($unit_price * $quantity):0);
                            ?>
                            <?php
                            if (($unit_price*$quantity) != 0) :
                            ?>
                            <p class="label text-center"><?php echo $cp_status==1?'<b class="text-12">&#10004;</b>':''; ?><?php echo $supplier_name; ?> </p>
                            <p class="label"><?php echo number_format($unit_price, 2, '.', ',')." * ".$quantity; ?></p>
                            <?php
                            endif;
                            ?>
                        </td>
                        <td>
                            <?php
                                $supplier = (isset($supplier_3)?$supplier_3:null);
                                $cp_status = (is_null($supplier)?null:$supplier->cp_status);
                                $po_item_id = ($cp_status  == null?'new3_'.$value->id:$supplier->id.'_'.$supplier->cp_id);
                                $supplier_id = (is_null($supplier)?null:$supplier->supplier_id);

                                $supplier_name = (is_null($supplier)?null:$supplier->supplier);
                                $unit_price = (is_null($supplier)?0:$supplier->price);
                                $quantity = (is_null($supplier)?0:$supplier->quantity);
                                $row_total = $row_total + ($unit_price * $quantity);
                                $column_3 = $column_3 + ($cp_status==1?($unit_price * $quantity):0);
                            ?>
                            <?php
                            if (($unit_price*$quantity) != 0) :
                            ?>
                            <p class="label text-center"><?php echo $cp_status==1?'<b class="text-12">&#10004;</b>':''; ?><?php echo $supplier_name; ?> </p>
                            <p class="label"><?php echo number_format($unit_price, 2, '.', ',')." * ".$quantity; ?></p>
                            <?php
                            endif;
                            ?>
                        </td>
                        <td>
                            <?php
                                $supplier = (isset($supplier_4)?$supplier_4:null);
                                $cp_status = (is_null($supplier)?null:$supplier->cp_status);
                                $po_item_id = ($cp_status == null?'new4_'.$value->id:$supplier->id.'_'.$supplier->cp_id);
                                $supplier_id = (is_null($supplier)?null:$supplier->supplier_id);
                                
                                $supplier_name = (is_null($supplier)?null:$supplier->supplier);
                                $unit_price = (is_null($supplier)?0:$supplier->price);
                                $quantity = (is_null($supplier)?0:$supplier->quantity);
                                $row_total = $row_total + ($unit_price * $quantity);
                                $column_4 = $column_4 + ($cp_status==1?($unit_price * $quantity):0);
                            ?>
                            <?php
                            if (($unit_price*$quantity) != 0) :
                            ?>
                            <p class="label text-center"><?php echo $cp_status==1?'<b class="text-12">&#10004;</b>':''; ?><?php echo $supplier_name; ?> </p>
                            <p class="label"><?php echo number_format($unit_price, 2, '.', ',')." * ".$quantity; ?></p>
                            <?php
                            endif;
                            ?>
                        </td>
                    </tr>
                <?php
                $counter++;
                endforeach; ?>
                <tr></tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="8"> &nbsp;</td>
                </tr>
                <tr>
                    <td colspan="4" align="right"><strong>Total: </strong></td>
                    <td><strong><?php echo number_format($column_1, 2, '.', ','); ?></strong></td>
                    <td><strong><?php echo number_format($column_2, 2, '.', ','); ?></strong></td>
                    <td><strong><?php echo number_format($column_3, 2, '.', ','); ?></strong></td>
                    <td><strong><?php echo number_format($column_4, 2, '.', ','); ?></strong></td>
                </tr>
                <tr bgcolor="#bcfbff">
                    <td colspan="7" align="right"><strong>Total: </strong></td>
                    <td><strong><?php echo number_format($c->total_amount, 2, '.', ','); ?></strong></td>
                </tr>
            </tfoot>
        </table>
        <br />
        <hr />
        <br />
        <div class="col-md-4">
            <div class="border">
                <p><b>Purpose:</b></p>
                <?php echo $request->purpose;?>
                <hr />
                <p><b>Requested By:</b></p>
                <br />
                <p class="underline"><?php echo ucwords($requestor->first_name." ".$requestor->last_name); ?></p>
                <p class="label text-center">Signature Over Printed Name</p>
                <hr />
                <p class="text-10"><b>Department and Branch:</b></p>
                <p><?php echo $requestor->dep_description." / ".$requestor->branch_description;?></p>
                <hr />
                <p><b>Date Prepared:</b></p>

                <p><?php echo date('F j, Y, g:i a', strtotime($request->date_updated)); ?></p>

                <hr />
                <p><b>Recommending Approval:</b></p>
                <?php foreach ($req_approval as $key => $value) : ?>
                    <br />
                    <p class="upperline text-center text-10"><?php echo ucwords($value->head_name)." / ".$value->head_department; ?></p>
                <?php endforeach; ?>

                <p class="upperline label text-center">Recommending Approval</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="border">
                <div class="border">
                    <p><b>With Budget:<b></p><br />
                    <p class="underline"><b>By:</b><?php echo getConfig('with_budget'); ?></p>
                    <br />
                    <p><b>Date:</b>____________________________</p>
                    <br />
                </div>
                <br />
                <p><b>Budget Certified By:</b></p><br />
                <p class="text-center"><?php echo (getConfig('budget_certified_by')==""?$budget_name:getConfig('budget_certified_by')); ?></p>
                <p class="upperline text-center text-10">Head, Budget Department</p>
                <hr />
                <p class="underline"><b>Date: </b> <?php echo $budget_date; ?></p>
                <br />
            </div>
        </div>
        <div class="col-md-4">
            <div class="border">

                <p><b>Canvassed By:</b></p>
                <p class="text-center"><?php echo ucwords($canvass_name); ?></p>
                <p class="upperline text-center text-10">Purchasing Department</p>

                <hr />
                <p class="underline"><b>Date:</b><?php echo $canvass_date;?></p><br />
                <br />
                <hr />
                <p><b>Approved or Purchased By:</b></p><br />

                <p class="text-center"><?php echo getConfig('approve_purchased_by'); /*$approve_purchase_by;*/ ?></p>
                <p class="upperline text-center text-10">Head, Purchasing Department</p>

                <hr />
                <p class="underline"><b>Date:</b><?php echo $approve_purchase_date; ?></p><br />
            </div>
        </div>


        <?php
        $html = ob_get_contents();
        ob_end_clean();
        $pdf->SetTitle($request->request_name);
        $pdf->WriteHTML($stylesheet, 1);
        $pdf->WriteHTML($html);
        $pdf->Output();
    }


    /**
     * Print Request from requisitioner view
     *
     * @param type $request_id
     * @return type
     */
    public function print_requisitioner($request_id)
    {
        $this->redirectUser(array('admin','members','canvasser','budget','auditor','board'));
        $this->load->model('user/User_model', 'User');
        $this->load->model('request/Request_model', 'Request');
        $this->load->model('budget/Budget_model', 'Budget');
        $this->load->model('canvasser/Canvasser_model', 'Canvasser');
        $this->load->model('purchase_order/Purchase_order_model', 'PO');

        $this->load->model('Branch/Branch_model', 'Branch');
        $branches = $this->Branch->getBranch()->result_array();
        $branches = array_column($branches, 'description');
        $branches = implode(' * ', $branches);

        
        $items = $this->Request->getItemsPerRequestCanvasser($request_id)->result();
        $request = $this->Request->getRequest($request_id)->row();
        $requestor = $this->User->getUser($request->user_id)->row();
        $this->load->library('pdf');
        $filename = "request_$request_id";
        $pdfFilePath = FCPATH."downloads/pdf/$filename.pdf";
        $pdf = $this->pdf->load();
        $stylesheet = file_get_contents(FCPATH.'assets/css/pdfstyle.css');

        $req_approval = $this->Request->getReqForApprovalByRequest($request_id)->result();
        $budget = $this->Budget->getBudgetByRequest($request_id)->row();
        if ($budget == null || ($budget->status != 1 && $budget->status != 5)) {
            $budget_name = '';
            $budget_date = '';
        } else {
            $budget_name = $budget->full_name;
            $budget_date = date('F j, Y, g:i a', strtotime($budget->date_updated));
        }

        $canvasser = $this->Canvasser->getCanvassByRequest($request_id)->row();
        if ($canvasser == null || $canvasser->date_updated == null) {
            $canvass_name = '';
            $canvass_date = '';
        } else {
            $canvass_name = $canvasser->full_name;
            $canvass_date = date('F j, Y, g:i a', strtotime($canvasser->date_updated));
        }

        if ($request->status==13) {
            $approve_purchase_by = $this->User->getUser($request->approve_purchase_by)->row();
            $approve_purchase_date = date('F j, Y, g:i a', strtotime($canvasser->date_updated));
        } else {
            $approve_purchase_by = "";
            $approve_purchase_date = "";
        }
        $res_po = $this->PO->getPOByRequest($request->id)->row();
        //$pdf->SetDisplayMode('fullpage');
        ob_start();
        ?>
        <h3 class="text-center"><?php echo getConfig('company_name'); ?></h3>
        <p class="text-center"><?php echo $branches; ?></p>
        <p class="text-center">Purchasing Department</p>
        
        <div class="received-date">
            <h5>RECEIVED</h5>
            <p>Date: _______________</p>
            <p>Time: _______________</p>
            <p>By: &emsp; _______________</p>
        </div>
        <hr />
        <h3 class="text-center">Purchase Requisition Slip</h3>
        <p class="underline width-150 pull-left clearfix"><b>PRS No.: </b><?php echo sprintf('PR-%08d', $request->id); ?></p>
        <p class="underline width-150 pull-left clearfix"><b>POF No.: </b><?php echo ($res_po==null?"":sprintf('PO-%08d', $res_po->id)); ?></p>
        <br />
        <br />
        <table border="1" cellpadding="1" cellspacing="0">
            <thead>
                <tr>
                    <th align="center" width="5%" rowspan="4">
                        Quantity
                    </th>
                    <th align="center" width="5%" rowspan="4">
                        Unit
                    </th>
                    <th align="center" width="15%" rowspan="4">
                        Product/Services
                    </th>
                    <th align="center" width="20%" rowspan="4">
                        Description/Specifications (Brand, Model, Catalog/Product No., Color, Size/Dimension Author, Year published, et.al)
                    </th>
                    <th colspan="4" width="65%"><h5>(For Purchasing Department use only)</h5></th>
                </tr>
                <tr>
                    <th colspan="4" width="65%"><h5>Quotations</h5></th>
                </tr>
                <tr>
                    <th width="16.25%"><h5>Name of Supplier and Price Per Unit</h5></th>
                    <th width="16.25%"><h5>Name of Supplier and Price Per Unit</h5></th>
                    <th width="16.25%"><h5>Name of Supplier and Price Per Unit</h5></th>
                    <th width="16.25%"><h5>Name of Supplier and Price Per Unit</h5></th>
                </tr>
                <tr>
                    <th width="16.25%"><h5> &nbsp; </h5></th>
                    <th width="16.25%"><h5> &nbsp; </h5></th>
                    <th width="16.25%"><h5> &nbsp; </h5></th>
                    <th width="16.25%"><h5> &nbsp; </h5></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $counter = 1;
                $sum = 0;
                foreach ($items as $key => $value) :
                ?>
                <?php
                    $supplier = getSuplliersFromCanvassed($value->request_id, $value->product_id, $value->product_name);
                    $supplier_num = $supplier->num_rows();
                    $supplier = $supplier->result();
                    $supplier_1 = null;
                    $supplier_2 = null;
                    $supplier_3 = null;
                    $supplier_4 = null;
                for ($i=0; $i < $supplier_num; $i++) {
                    $post_var = $i+1;
                    ${'supplier_'.$post_var} = $supplier[$i];
                }
                    $row_total = 0;
                ?>
                    <tr>

                        <!-- <td><?php echo $counter; ?></td> -->
                        <td><?php echo $value->quantity; ?></td>
                        <td><?php echo $value->unit_measurement; ?></td>
                        <td><?php echo $value->product_name; ?></td>
                        <td>
                        <p class="label">
                            <?php echo $value->product_description; ?>
                        </p>
                        </td>
                        <td>
                            <?php
                                $supplier = (isset($supplier_1)?$supplier_1:null);
                                $cp_status = (is_null($supplier)?null:$supplier->cp_status);
                                $po_item_id = ($cp_status == null?'new1_'.$value->id:$supplier->id.'_'.$supplier->cp_id);
                                $supplier_id = (is_null($supplier)?null:$supplier->supplier_id);

                                $supplier_name = (is_null($supplier)?null:$supplier->supplier);
                                $unit_price = (is_null($supplier)?0:$supplier->price);
                                $quantity = (is_null($supplier)?0:$supplier->quantity);
                                $row_total = $row_total + ($unit_price * $quantity);
                            ?>
                            <?php
                            if (($unit_price*$quantity) != 0) :
                            ?>
                            <p class="label text-center"><?php echo $cp_status==1?'<b class="text-12">&#10004;</b>':''; ?><?php echo $supplier_name; ?> </p>
                            <?php
                            endif;
                            ?>
                        </td>
                    
                        <td>
                            <?php
                                $supplier = (isset($supplier_2)?$supplier_2:null);
                                $cp_status = (is_null($supplier)?null:$supplier->cp_status);
                                $po_item_id = ($cp_status == null?'new2_'.$value->id:$supplier->id.'_'.$supplier->cp_id);
                                $supplier_id = (is_null($supplier)?null:$supplier->supplier_id);

                                $supplier_name = (is_null($supplier)?null:$supplier->supplier);
                                $unit_price = (is_null($supplier)?0:$supplier->price);
                                $quantity = (is_null($supplier)?0:$supplier->quantity);
                                $row_total = $row_total + ($unit_price * $quantity);
                            ?>
                            <?php
                            if (($unit_price*$quantity) != 0) :
                            ?>
                            <p class="label text-center"><?php echo $cp_status==1?'<b class="text-12">&#10004;</b>':''; ?><?php echo $supplier_name; ?> </p>
                            <?php
                            endif;
                            ?>
                        </td>
                        <td>
                            <?php
                                $supplier = (isset($supplier_3)?$supplier_3:null);
                                $cp_status = (is_null($supplier)?null:$supplier->cp_status);
                                $po_item_id = ($cp_status  == null?'new3_'.$value->id:$supplier->id.'_'.$supplier->cp_id);
                                $supplier_id = (is_null($supplier)?null:$supplier->supplier_id);

                                $supplier_name = (is_null($supplier)?null:$supplier->supplier);
                                $unit_price = (is_null($supplier)?0:$supplier->price);
                                $quantity = (is_null($supplier)?0:$supplier->quantity);
                                $row_total = $row_total + ($unit_price * $quantity);
                            ?>
                            <?php
                            if (($unit_price*$quantity) != 0) :
                            ?>
                            <p class="label text-center"><?php echo $cp_status==1?'<b class="text-12">&#10004;</b>':''; ?><?php echo $supplier_name; ?> </p>
                            <?php
                            endif;
                            ?>
                        </td>
                        <td>
                            <?php
                                $supplier = (isset($supplier_4)?$supplier_4:null);
                                $cp_status = (is_null($supplier)?null:$supplier->cp_status);
                                $po_item_id = ($cp_status == null?'new4_'.$value->id:$supplier->id.'_'.$supplier->cp_id);
                                $supplier_id = (is_null($supplier)?null:$supplier->supplier_id);
                                
                                $supplier_name = (is_null($supplier)?null:$supplier->supplier);
                                $unit_price = (is_null($supplier)?0:$supplier->price);
                                $quantity = (is_null($supplier)?0:$supplier->quantity);
                                $row_total = $row_total + ($unit_price * $quantity);
                            ?>
                            <?php
                            if (($unit_price*$quantity) != 0) :
                            ?>
                            <p class="label text-center"><?php echo $cp_status==1?'<b class="text-12">&#10004;</b>':''; ?><?php echo $supplier_name; ?> </p>
                            <?php
                            endif;
                            ?>
                        </td>
                    </tr>
                <?php
                $counter++;
                endforeach; ?>
                <tr></tr>
            </tbody>
            <!-- <tfoot>
                <tr>
                    <td colspan="6" align="right"><strong>Total: </strong></td>
                    <td><strong><?php echo number_format($sum, 2, '.', ','); ?></strong></td>
                </tr>
            </tfoot> -->
        </table>
        <br />
        <hr />
        <br />
        <div class="col-md-4">
            <div class="border">
                <p><b>Purpose:</b></p>
                <?php echo $request->purpose;?>
                <hr />
                <p><b>Requested By:</b></p>
                <br />
                <p class="underline"><?php echo ucwords($requestor->first_name." ".$requestor->last_name); ?></p>
                <p class="label text-center">Signature Over Printed Name</p>
                <hr />
                <p class="text-10"><b>Department and Branch:</b></p>
                <p><?php echo $requestor->dep_description." / ".$requestor->branch_description;?></p>
                <hr />
                <p><b>Date Prepared:</b></p>

                <p><?php echo date('F j, Y, g:i a', strtotime($request->date_updated)); ?></p>

                <hr />
                <p><b>Recommending Approval:</b></p>
                <?php foreach ($req_approval as $key => $value) : ?>
                    <br />
                    <p class="upperline text-center text-10"><?php echo ucwords($value->head_name)." / ".$value->head_department; ?></p>
                <?php endforeach; ?>

                <p class="upperline label text-center">Recommending Approval</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="border">
                <div class="border">
                    <p><b>With Budget:<b></p><br />
                    <p class="underline"><b>By:</b><?php echo getConfig('with_budget'); ?></p>
                    <br />
                    <p><b>Date:</b>____________________________</p>
                    <br />
                </div>
                <br />
                <p><b>Budget Certified By:</b></p><br />
                <p class="text-center"><?php echo (getConfig('budget_certified_by')==""?$budget_name:getConfig('budget_certified_by')) ?></p>
                <p class="upperline text-center text-10">Head, Budget Department</p>
                <hr />
                <p class="underline"><b>Date: </b> <?php echo $budget_date; ?></p>
                <br />
            </div>
        </div>
        <div class="col-md-4">
            <div class="border">

                <p><b>Canvassed By:</b></p>
                <p class="text-center"><?php echo ucwords($canvass_name); ?></p>
                <p class="upperline text-center text-10">Purchasing Department</p>

                <hr />
                <p class="underline"><b>Date:</b><?php echo $canvass_date;?></p><br />
                <br />
                <hr />
                <p><b>Approved or Purchased By:</b></p><br />

                <p class="text-center"><?php echo getConfig('approve_purchased_by'); /*$approve_purchase_by;*/ ?></p>
                <p class="upperline text-center text-10">Head, Purchasing Department</p>

                <hr />
                <p class="underline"><b>Date:</b><?php echo $approve_purchase_date; ?></p><br />
            </div>
        </div>


        <?php
        $html = ob_get_contents();
        ob_end_clean();
        $pdf->SetTitle($request->request_name);
        $pdf->WriteHTML($stylesheet, 1);
        $pdf->WriteHTML($html);
        $pdf->Output();
    }

    /**
     * Request Action Button generator
     *
     * @param int $request_id
     * @param int $status
     * @param int $archived
     * @return string
     */
    public function reqActionBtn($request)
    {
        $request_id = $request->id;
        $status = $request->status;
        $archived = $request->archive;
        $statusItemsDenied = $this->statusItemsDenied;

        $this->redirectUser();
        //default
        $defaultbtn = '<li><a target="_blank" href="'.base_url('request/print_request/'.$request_id).'">Print Request</a></li>';
        if ($archived!=1) {
            $defaultbtn .= '<li><a href="javascript:;" onClick="xwb.supplierSummary('.$request_id.')" >Supplier Summary</a></li>';
        }


        if (in_array($request->status, $statusItemsDenied)) {
            if ($this->user_id == $request->user_id) {
                $defaultbtn .= '<li class="has-action"><a href="'.base_url('request/view_request/'.$request_id).'">View Request</a></li>';
            } else {
                $defaultbtn .= '<li><a href="javascript:;" onClick="xwb.view_res('.$request_id.')" >View Message</a></li>';
            }
        }


        switch ($status) {
            case 1:
                $btn = $defaultbtn;
                break;

            case 2:
                $btn = $defaultbtn;
                if ($this->log_user_data->group_name == 'admin') {
                    $btn .= '<li class="has-action"><a href="javascript:;" onClick="xwb.toCanvass('.$request_id.');">Forward to Canvasser</a></li>';
                    $btn .= '<li class="has-action"><a href="javascript:;" onClick="xwb.toRequisitioner('.$request_id.');">Forward to Requisitioner</a></li>';
                }
                break;
            case 3:
                $btn = $defaultbtn;
                    
                break;
            case 4:
                $btn = $defaultbtn;
                if ($this->log_user_data->group_name == 'budget') {
                    $btn .= '<li><a href="javascript:;" onClick="xwb.setExpenditure('.$request_id.');">Expenditure</a></li>';
                }
                break;
            case 5:
                $btn = $defaultbtn;

                break;
            case 6:
                $btn = $defaultbtn;
                if ($this->log_user_data->group_name == 'budget') {
                    $btn .= '<li><a href="javascript:;" onClick="xwb.setExpenditure('.$request_id.');">Expenditure</a></li>';
                    $btn .= '<li class="has-action"><a href="javascript:;" onClick="xwb.view_budget_msg('.$request_id.');">View Message</a></li>';
                }
                break;
            case 7:
                $btn = $defaultbtn;
                if ($this->log_user_data->group_name == 'budget') {
                    $btn .= '<li><a href="javascript:;" onClick="xwb.setExpenditure('.$request_id.');">Expenditure</a></li>';
                }
                break;
            case 8:
                $btn = $defaultbtn;
                if ($this->log_user_data->group_name == 'admin') {
                    $btn .= '<li class="has-action"><a href="'.base_url('purchase_order/gen_po/'.$request_id).'">Generate PO</a></li>';
                    $btn .= '<li class="has-action"><a onClick="xwb.markDone('.$request_id.')" href="javascript:;">Partially Done</a></li>';
                }
                if ($this->log_user_data->group_name == 'budget') {
                    $btn .= '<li><a href="javascript:;" onClick="xwb.setExpenditure('.$request_id.');">Expenditure</a></li>';
                }
                break;
            case 9:
                $btn = $defaultbtn;
                if ($this->log_user_data->group_name == 'budget') {
                    $btn .= '<li><a href="javascript:;" onClick="xwb.setExpenditure('.$request_id.');">Expenditure</a></li>';
                }
                break;
            case 10:
                $btn = $defaultbtn;
                if ($this->log_user_data->group_name == 'budget') {
                    $btn .= '<li><a href="javascript:;" onClick="xwb.setExpenditure('.$request_id.');">Expenditure</a></li>';
                }
                break;
            case 11:
                $btn = $defaultbtn;
                if ($this->log_user_data->group_name == 'budget') {
                    $btn .= '<li><a href="javascript:;" onClick="xwb.setExpenditure('.$request_id.');">Expenditure</a></li>';
                }
                break;
            case 12:
                $btn = $defaultbtn;
                if ($this->log_user_data->group_name == 'admin') {
                    $btn .= '<li class="has-action"><a href="javascript:;" onClick="xwb.markDone('.$request_id.')">Done</a></li>';
                }
                break;
            case 13:
                $btn = $defaultbtn;
                if ($this->log_user_data->group_name == 'admin') {
                    if ($archived!=1) {
                        $btn .= '<li><a href="javascript:;" onClick="xwb.archive('.$request_id.')">Archive</a></li>';
                    }
                }
                break;
            case 14:
                $btn = $defaultbtn;
                if ($this->log_user_data->group_name == 'admin') {
                    $btn .= '<li class="has-action"><a href="javascript:;" onClick="xwb.assignToBudget('.$request_id.')">Assign to Budget</a></li>';
                    $btn .= '<li class="has-action"><a href="javascript:;" onClick="xwb.returnToCanvass('.$request_id.')">Return to Canvasser</a></li>';
                }

                break;
            case 15:
                $btn = $defaultbtn;
                break;

            case 16:
                $btn = $defaultbtn;
                if ($this->log_user_data->group_name == 'admin') {
                    $btn .= '<li class="has-action"><a href="javascript:;" onClick="xwb.view_response('.$request_id.')">View Response</a></li>';
                    $btn .= '<li class="has-action"><a href="javascript:;" onClick="xwb.assignToBudget('.$request_id.')">Assign to Budget</a></li>';
                }
                break;
            case 17:
                $btn = $defaultbtn;
                break;
            case 18:
                $btn = $defaultbtn;
                break;
            case 19:
                $btn = $defaultbtn;
                break;
            case 20:
                $btn = $defaultbtn;
                if ($this->log_user_data->group_name == 'admin') {
                    $btn .= '<li class="has-action"><a href="javascript:;" onClick="xwb.toCanvass('.$request_id.');">Forward to Canvasser</a></li>';
                    $btn .= '<li class="has-action"><a href="javascript:;" onClick="xwb.view_req_msg('.$request_id.')">View Response</a></li>';
                }
                break;
            case 21:
                $btn = $defaultbtn;
                if ($this->log_user_data->group_name == 'admin') {
                    $btn .= '<li class="has-action"><a href="'.base_url('purchase_order/gen_po/'.$request_id).'">Generate PO</a></li>';
                    $btn .= '<li class="has-action"><a onClick="xwb.markDone('.$request_id.')" href="javascript:;">Partially Done</a></li>';
                }
                if ($this->log_user_data->group_name == 'budget') {
                    $btn .= '<li><a href="javascript:;" onClick="xwb.setExpenditure('.$request_id.');">Expenditure</a></li>';
                }
                break;

            default:
                $btn = $defaultbtn;
                break;
        }


        if ($archived == 1) {
            if ($this->log_user_data->group_name == 'admin') {
                        $btn .= '<li><a href="javascript:;" onClick="xwb.unarchive('.$request_id.')">UnArchive</a></li>';
            }
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
     * View request in detailed
     *
     * @param int $request_id
     * @return mixed
     */
    public function view_request($request_id)
    {
        $this->redirectUser(array('members','canvasser','budget','admin','auditor','property','board'));
        $r = $this->Request->getRequest($request_id)->row();
        $data['request'] = $r;
        $data['items'] = $this->Request->getItemsPerRequest($request_id)->result();
        $data['user_id'] = $this->log_user_data->user_id;
        $data['headDenied'] = $this->checkHeadDenied($request_id);
        $data['request_id'] = $request_id;
        $data['page_title'] = $r->request_name; //title of the page
        $data['page_script'] = 'view_request'; // script filename of the page
        
        $this->renderPage('request/view_request', $data);
    }

    /**
     * View request in detailed
     *
     * @param int $request_id
     * @return mixed
     */
    public function edit_request($request_id)
    {
        $this->redirectUser(array('members','canvasser','budget','admin','auditor','property'));
        $this->load->model('supplier/Supplier_model', 'Supplier');
        $this->load->model('product/Product_model', 'Product');

        $r = $this->Request->getRequest($request_id)->row();

        $req_status = array(1,19,17);
        if ($r->user_id != $this->log_user_data->user_id || (!in_array($r->status, $req_status))) {
            exit('Unauthorized Access or Request Can not be edited at this stage');
        }

        $data['request'] = $r;
        $data['items'] = $this->Request->getItemsPerRequest($request_id)->result();
        $data['suppliers'] = $this->Supplier->getSuppliers()->result();
        $data['user_id'] = $this->log_user_data->user_id;
        $data['products'] = $this->Product->getProducts()->result();
        $data['request_id'] = $request_id;
        $data['page_title'] = $r->request_name; //title of the page
        $data['page_script'] = 'edit_request'; // script filename of the page

        $this->renderPage('request/edit_request', $data);
    }

    /**
     * Get denied reason
     *
     * @return json
     */
    public function getDeniedReason()
    {
        $this->redirectUser();
        $request_id = $this->input->post('request_id');
        $r = $this->Request->getRequest($request_id)->row();
        $this->load->model('approval/Approval_model', 'Approval');
        $this->load->model('budget/Budget_model', 'Budget');
        $this->load->model('board/Board_model', 'Board');
        switch ($r->status) {
            case 5:
                $reason = "";
                break;
            case 6:
                $b = $this->Budget->getBudgetByRequest($request_id)->row();
                $reason = $b->budget_note;
                break;
            case 10:
                $b = $this->Board->getBoardByRequest($request_id)->row();
                $reason = $b->board_note;
                break;
            case 17:
                $this->load->model('canvasser/Canvasser_model', 'Canvasser');

                $c = $this->Canvasser->getCanvassByRequest($request_id)->row();
                $reason = $c->canvass_message;

                break;
            case 19:
                $c = $this->Request->getRequest($request_id)->row();
                $reason = $c->admin_note;

                break;
            default:
                $reason = "";
                break;
        }

        $data['reason'] = $reason;
        echo $this->xwbJsonEncode($data);
    }




    /**
     * Responde to status
     *
     * @return type
     */
    public function respond()
    {
        $this->load->library('form_validation');
        $this->redirectUser();
        $this->form_validation->set_rules('request_id', 'Request ID.', 'required|alpha_dash');
        $this->form_validation->set_rules('response', 'Response', 'required');

        if ($this->form_validation->run() == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
            echo $this->xwbJsonEncode($data);
            exit();
        } else {
            $this->load->model('user/User_model', 'User');
            $this->load->model('budget/Budget_model', 'Budget');
            $this->load->model('board/Board_model', 'Board');

            $posts = $this->input->post();
            $r = $this->Request->getRequest($posts['request_id'])->row();
            
            
            
            $userfrom = $this->User->getUser($r->user_id)->row();


            switch ($r->status) {
                case 6:
                    //budget department
                    $b = $this->Budget->getBudgetByRequest($posts['request_id'])->row();


                    $db_data = array(
                        'requestor_note' => $posts['response'],
                        'status' => 3,
                        'date_updated' => date('Y-m-d H:i:s')
                        );
                    $this->Budget->updateStatusByRequest($posts['request_id'], $db_data);

                    $db_data = array(
                        'status' => 7,
                        'date_updated' => date('Y-m-d H:i:s')
                        );
                    $this->db->where('id', $posts['request_id']);
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
                            'user_to' => $b->user_id,
                            'message' => $this->input->post('response'),
                            'request_id' => $posts['request_id'],
                        );

                    $this->xwb_purchasing->setShortCodes($shortcodes);

                    $condition = $this->xwb_purchasing->getShortCodes();
                    /* sending email notification */
                    $msg = $this->xwb_purchasing->getMessage('response_to_budget');
                    $message = do_shortcode($msg['message'], $condition);
                    $site_title = $this->config->item('site_title');
                    $this->xwb_purchasing->sendmail($condition['email_to'], $msg['subject'], $message, $condition['email_from'], $site_title, $msg['subject']);

                    $this->xwb_purchasing->addHistory('request_list', $posts['request_id'], 'User Responded', 'User responded to budget', $this->log_user_data->user_id);

                    break;
                case 10:
                    //board department
                    $db_data = array(
                        'requestor_note' => $posts['response'],
                        'status' => 3,
                        'date_updated' => date('Y-m-d H:i:s')
                        );
                    $this->Board->updateStatusByRequest($posts['request_id'], $db_data);

                    $db_data = array(
                        'status' => 11,
                        'date_updated' => date('Y-m-d H:i:s')
                        );
                    $this->db->where('id', $posts['request_id']);
                    $res = $this->db->update('request_list', $db_data);


                    $admins = $this->User->getUsersByGroup('admin');

                    foreach ($admins->result() as $aK => $aV) {
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
                                'user_to' => $aV->id,
                                'message' => $this->input->post('pd_remarks'),
                                'request_id' => $posts['request_id'],
                            );

                        $this->xwb_purchasing->setShortCodes($shortcodes);

                        $condition = $this->xwb_purchasing->getShortCodes();
                        /* sending email notification */
                        $msg = $this->xwb_purchasing->getMessage('response_to_board');
                        $message = do_shortcode($msg['message'], $condition);
                        $site_title = $this->config->item('site_title');
                        $this->xwb_purchasing->sendmail($condition['email_to'], $msg['subject'], $message, $condition['email_from'], $site_title, $msg['subject']);
                    }

                    /* Adding to history transaction */
                    $this->xwb_purchasing->addHistory('request_list', $posts['request_id'], 'Requisitioner Response', 'Requisitioner Response to Board', $this->log_user_data->user_id);

                    break;
                case 17:
                    $this->load->model('canvasser/Canvasser_model', 'Canvasser');
                    $c = $this->Canvasser->getCanvassByRequest($posts['request_id'])->row();
                    $db_data = array(
                        'user_response' => $posts['response'],
                        'user_from' => $r->user_id,
                        'status' => 8,
                        'date_updated' => date('Y-m-d H:i:s')
                        );
                    $res = $this->Canvasser->updateCanvass($c->id, $db_data);
                    $res = $this->Request->updateRequestStatus($posts['request_id'], 18);


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
                            'user_to' => $c->user_id,
                            'message' => $posts['response'],
                            'request_id' => $posts['request_id'],
                        );

                    $this->xwb_purchasing->setShortCodes($shortcodes);

                    $condition = $this->xwb_purchasing->getShortCodes();

                    /* sending email notification */
                    $msg = $this->xwb_purchasing->getMessage('requisitioner_response_to_canvasser');
                    $message = do_shortcode($msg['message'], $condition);
                    $site_title = $this->config->item('site_title');
                    $this->xwb_purchasing->sendmail($condition['email_to'], $msg['subject'], $message, $condition['email_from'], $site_title, $msg['subject']);

                    /* Adding to history transaction */
                    $this->xwb_purchasing->addHistory('canvass', $c->id, 'Requisitioner Response', 'Requisitioner Response to canvasser', $this->log_user_data->user_id);

                    break;
                case 19:
                    $db_data = array(
                        'request_note' => $posts['response'],
                        'status' => 20,
                        'date_updated' => date('Y-m-d H:i:s')
                        );
                    $this->db->where('id', $posts['request_id']);
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
                            'user_to' => $r->user_from,
                            'message' => $posts['response'],
                            'request_id' => $posts['request_id'],
                        );

                    $this->xwb_purchasing->setShortCodes($shortcodes);

                    $condition = $this->xwb_purchasing->getShortCodes();
                    /* sending email notification */
                    $msg = $this->xwb_purchasing->getMessage('requisitioner_response_to_admin');
                    $message = do_shortcode($msg['message'], $condition);
                    $site_title = $this->config->item('site_title');
                    $this->xwb_purchasing->sendmail($condition['email_to'], $msg['subject'], $message, $condition['email_from'], $site_title, $msg['subject']);

                    /* Adding to history transaction */
                    $this->xwb_purchasing->addHistory('request_list', $r->id, 'Requisitioner Response', 'Requisitioner Response to Admin', $this->log_user_data->user_id);

                    break;
                default:
                    $res = false;
                    break;
            }
        }

        if ($res) {
            $data['status'] = true;
            $data['message'] = 'You have responded the issue';
        } else {
            $data['status'] = false;
            $data['message'] = 'Error updating data, pelase contact the programmer';
        }
        echo $this->xwbJsonEncode($data);
    }


    /**
     * Mark request process as done
     *
     * @return json
     */
    public function markDone()
    {
        $this->load->library('form_validation');
        $this->load->model('user/User_model', 'User');
        $this->redirectUser();
        
        $this->form_validation->set_rules('request_id', 'Request No.', 'required|alpha_dash');
        
        if ($this->form_validation->run() == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
        } else {
            $this->load->model('item/Item_model', 'Item');
            $this->load->model('purchase_order/Purchase_order_model', 'PO');
            $request_id = $this->input->post('request_id');

            $po = $this->PO->getUndoneApprovedPOByRequest($request_id);

            /* update po items status on delivery */
            $items = $this->Item->getApprovePOItemsByRequest($request_id)->result();

            $done_items = [];
            foreach ($items as $iK => $iV) {
                $done_items[] = $iV->id;
                log_message('info', 'getApprovePOItemsByRequest: '.$iV->product_name);
            }



            log_message('info', 'count done items: '.count($done_items));
            if (count($done_items)==0) {
                $data['status'] = false;
                $data['message'] = 'No Item to be updated';
                echo $this->xwbJsonEncode($data);
                exit();
            }
            $db_data = array(
                    'status' => 3,
                    'date_updated' => date('Y-m-d H:i:s')
                );

            $this->db->where_in('id', $done_items);
            $this->db->update('po_items', $db_data);
            log_message('info', 'po_items where in update: '.$this->db->last_query());

            
            foreach ($po->result() as $poK => $poV) {
                /* Add to property database */
                $db_data = array(
                    'request_id' => $request_id,
                    'po_id' => $poV->id,
                    'eta'   => date('Y-m-d H:i:s', strtotime($poV->delivery_date)),
                    'user_from' => $this->log_user_data->user_id,
                );


                $this->db->insert('property', $db_data);
                $property_id = $this->db->insert_id();
                log_message('info', 'insert to property: '.$this->db->last_query());


                $po_items = $this->Request->getItemsPerPO($poV->id);
                $db_data = [];
                foreach ($po_items->result() as $iK => $iV) {
                    $db_data[] = array(
                        'property_id' => $property_id,
                        'item_id' => $iV->id,
                        'po_id' => $iV->po_id,
                        'request_id' => $request_id,
                    );
                }

                log_message('info', print_r($db_data, true));
                $res = $this->db->insert_batch('property_item', $db_data);
                log_message('info', 'insert to property_item: '.$this->db->last_query());
            }


            $property_users = $this->User->getUsersByGroup('property')->result();

            /* Notify the property users */
            foreach ($property_users as $pK => $pV) {
                /**
                * Assigning shortcode for email
                *
                * user_to
                * user_from
                * request_id
                * message
                * po_num
                * item
                */
                $shortcodes = array(
                        'user_to' => $pV->id,
                        'message' => '',
                        'request_id' => $request_id,
                    );

                $this->xwb_purchasing->setShortCodes($shortcodes);

                $condition = $this->xwb_purchasing->getShortCodes();
                /* sending email notification */
                $msg = $this->xwb_purchasing->getMessage('delivery_to_property');
                $message = do_shortcode($msg['message'], $condition);
                $site_title = $this->config->item('site_title');
                $this->xwb_purchasing->sendmail($condition['email_to'], $msg['subject'], $message, $condition['email_from'], $site_title, $msg['subject']);
            }


            /* Change request status */
            $stat = $this->Request->requestAuditApproved($request_id);
            if ($stat) {
                $req_stat = 13; // status 13 - set request process done
            } else {
                $req_stat = 21; // status 21 - set request process partialy done
            }


            $res = $this->Request->updateRequestStatus($request_id, $req_stat);

            $this->load->model('user/User_model', 'User');
            $r = $this->Request->getRequest($request_id)->row();


            if ($stat) {
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
                        'message' => '',
                        'request_id' => $request_id,
                    );

                $this->xwb_purchasing->setShortCodes($shortcodes);

                $condition = $this->xwb_purchasing->getShortCodes();
                /* sending email notification */
                $msg = $this->xwb_purchasing->getMessage('request_done');
                $message = do_shortcode($msg['message'], $condition);
                $site_title = $this->config->item('site_title');
                $this->xwb_purchasing->sendmail($condition['email_to'], $msg['subject'], $message, $condition['email_from'], $site_title, $msg['subject']);

                $this->xwb_purchasing->addHistory('request_list', $request_id, 'Request Done', 'Request Approved', $this->log_user_data->user_id);
            }

            if ($res) {
                $data['status'] = true;
                $data['message'] = 'Request has been updated';
            } else {
                $data['status'] = false;
                $data['message'] = 'Error updating data, pelase contact the programmer';
            }
        }


        echo $this->xwbJsonEncode($data);
    }


    /**
     * Upload attachment on each product/item requested
     *
     * @return json
     */
    public function addAttachment()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('row', 'Row Number', 'required');
        

        if ($this->form_validation->run() == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
        } else {
            $row = $this->input->post('row');

            $upload_path = FCPATH.'temp/';

            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }

            $config['upload_path']          = $upload_path;

            $config['allowed_types']        = 'gif|jpg|png|zip|zipx|rar|7z|pdf|doc|docx|txt|odt';
            $this->load->library('upload', $config);

            if (! $this->upload->do_upload('attachment')) {
                $error = array('error' => $this->upload->display_errors());
                $data['status'] = false;
                $data['message'] = $error['error'];
            } else {
                if (!is_null($this->session->req_attachment) && array_key_exists($row, $this->session->req_attachment)) {
                    $count = count($this->session->req_attachment[$row]);
                } else {
                    $count = 0;
                }
                
                $count = $count+1;
                 
                $_SESSION['req_attachment'][$row][$count] = $this->upload->data();
                
                $data['status'] = true;
                $data['message'] = 'Attachment uploaded';
            }
        }

        
        echo $this->xwbJsonEncode($data);
    }


    /**
     * Get attachment from session to datatable
     *
     * @return json
     */
    public function getAttachment()
    {
        $row = $this->input->get('row');
        
        $data['data'] = array();
        if (!is_null($this->session->req_attachment) && array_key_exists($row, $this->session->req_attachment)) {
            $req_attachment = $this->session->req_attachment[$row];
            
            if (count($req_attachment)>0) {
                foreach ($req_attachment as $key => $value) {
                    $data['data'][] = array(
                            $key,
                            $value['file_name'],
                            '<a target="_blank" href="'.base_url('request/dl_attachment/'.$row.'/'.$key).'" class="btn btn-xs btn-info">Download</a>
							<a href="" data-row="'.$row.'" data-key="'.$key.'" class="btn btn-xs btn-danger xwb-remove-attachment">Remove</a>',
                        );
                }
            }
        }
        echo $this->xwbJsonEncode($data);
    }


    /**
     * Get attachment preview from session to datatable
     *
     * @return json
     */
    public function getAttachmentPreview()
    {
        $row = $this->input->get('row');
        $data['data'] = array();
        if (!is_null($this->session->req_attachment) && array_key_exists($row, $this->session->req_attachment)) {
            $req_attachment = $this->session->req_attachment[$row];
            
            if (count($req_attachment)>0) {
                foreach ($req_attachment as $key => $value) {
                    $data['data'][] = array(
                            $key,
                            $value['file_name'],
                            '<a target="_blank" href="'.base_url('request/dl_attachment/'.$row.'/'.$key).'" class="btn btn-xs btn-info">Download</a>
							',
                        );
                }
            }
        }
        echo $this->xwbJsonEncode($data);
    }

    /**
     * Remove temporary file attachment
     *
     * @return json
     */
    public function removeAttachment()
    {
        $this->load->helper('file');

        $row = $this->input->post('row');
        $key = $this->input->post('key');

        chmod($_SESSION['req_attachment'][$row][$key]['full_path'], 777);
        $res = @unlink($_SESSION['req_attachment'][$row][$key]['full_path']);
        unset($_SESSION['req_attachment'][$row][$key]);
        $data['status'] = true;
        $data['message'] = 'Attachment Removed';
        echo $this->xwbJsonEncode($data);
    }


    /**
     * Download file attachment
     *
     * @param int $row
     * @param int $key
     * @return void
     */
    public function dl_attachment($row, $key)
    {
        $this->load->helper('download');
        force_download($_SESSION['req_attachment'][$row][$key]['full_path'], null);
    }


    /**
     * Check if there were items denied
     *
     * @param int $request_id
     * @return boolean
     */
    public function checkHeadDenied($request_id)
    {

        $res = $this->db->get_where('items_approval', array('request_id'=>$request_id,'status'=>2));
        if ($res->num_rows()>0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get denied items for datatable
     *
     * @return json
     */
    public function getDeniedItems()
    {
        $request_id = $this->input->get('request_id');
        $d = $this->Request->getDeniedItems($request_id);
        $data['data'] =[];
        if ($d->num_rows()>0) {
            foreach ($d->result() as $k => $v) {
                if ($this->log_user_data->user_id==$v->requisitioner) {
                    $requestor_note = '<textarea class="requestor_note_'.$v->id.'" name="requestor_note['.$v->id.']" clas="form-control">'.$v->requestor_note.'</textarea>
						<a href="javascript:;" class="btn btn-xs btn-success" onClick="xwb.respondToHead('.$v->id.')">Respond</a>';
                } else {
                    $requestor_note = $v->requestor_note;
                }
                
                $time_lapse = (is_null($v->date_updated)?"":'<label class="badge badge-info">'.time_elapse($v->date_updated).'</label>');
                $data['data'][] = array(
                                        /*$v->id,*/
                                        $v->product_name,
                                        $v->product_description,
                                        $v->quantity,
                                        ucwords($v->assigned_to." (".$v->department.")"),
                                        $this->xwb_purchasing->getStatus('item_approval', $v->status)." ".$time_lapse,
                                        $v->officers_note,
                                        $requestor_note,
                                        );
            }
        }

        echo $this->xwbJsonEncode($data);
    }


    /**
     * Respond to head department
     *
     * @return json
     */
    public function respondToHead()
    {
        
        $this->load->library('form_validation');
        $this->form_validation->set_rules('approval_id', 'Approval ID', 'required|alpha_dash');
        $this->form_validation->set_rules('response', 'User Response', 'required');
        
        if ($this->form_validation->run() == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
        } else {
            $this->load->model('user/User_model', 'User');
            $this->load->model('approval/Approval_model', 'Approval');

            $posts = $this->input->post();
            $db_data = array(
                    'requestor_note' => $posts['response'],
                    'status' => 3,
                    'date_updated' => date('Y-m-d H:i:s')
                );
            $this->db->where('id', $posts['approval_id']);
            $res = $this->db->update('items_approval', $db_data);
            $reqapproval = $this->Approval->getItemsApproval($posts['approval_id'])->row();

            if ($res) {
                $a = $this->Approval->getApproval($reqapproval->request_approval_id)->row();

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
                        'user_to' => $a->user_id,
                        'message' => $posts['response'],
                        'request_id' => $a->request_id,
                    );

                $this->xwb_purchasing->setShortCodes($shortcodes);

                $condition = $this->xwb_purchasing->getShortCodes();
                /* sending email notification */
                $msg = $this->xwb_purchasing->getMessage('response_to_head');
                $message = do_shortcode($msg['message'], $condition);
                $site_title = $this->config->item('site_title');
                $this->xwb_purchasing->sendmail($condition['email_to'], $msg['subject'], $message, $condition['email_from'], $site_title, $msg['subject']);


                $this->xwb_purchasing->addHistory('items_approval', $posts['approval_id'], 'User respond to Head', 'User respond to Head/Recommending approval', $this->log_user_data->user_id);

                $data['status'] = true;
                $data['message'] = "You have successfully responded to head/recommending approval";
            } else {
                $data['status'] = false;
                $data['message'] = "Error updating data, please contact the system programmer";
            }
        }
        echo $this->xwbJsonEncode($data);
    }


    /**
     * Set Expenditure per request
     *
     * @return json
     */
    public function setExpenditure()
    {
        $request_id = $this->input->post('request_id');
        $expenditure = $this->input->post('expenditure');
        $this->db->where('id', $request_id);
        $db_data=array(
                'expenditure' => $expenditure
            );
        $res = $this->db->update('request_list', $db_data);
        if ($res) {
            $data['status'] = true;
            $data['message'] = "Request Updated";
        } else {
            $data['status'] = false;
            $data['message'] = "Error updating data, please contact the system programmer";
        }
        echo $this->xwbJsonEncode($data);
    }


    /**
     * Set Expenditure per item
     *
     * @return json
     */
    public function setExpenditureItem()
    {
        $item_id = $this->input->post('item_id');
        $expenditure = $this->input->post('expenditure');
        $this->db->where('id', $item_id);
        $db_data=array(
                'expenditure' => $expenditure
            );
        $res = $this->db->update('po_items', $db_data);
        if ($res) {
            $data['status'] = true;
            $data['message'] = "Request Updated";
        } else {
            $data['status'] = false;
            $data['message'] = "Error updating data, please contact the system programmer";
        }
        echo $this->xwbJsonEncode($data);
    }


    /**
     * Return request to canvasser for modification
     *
     * @return json
     */
    public function returnCanvass()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('message', 'Remarks/Message', 'required');

        if ($this->form_validation->run() == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
        } else {
            $request_id = $this->input->post('request_id');
            $this->load->model('canvasser/Canvasser_model', 'Canvasser');
            $this->load->model('user/User_model', 'User');
            $c = $this->Canvasser->getCanvassByRequest($request_id)->row();
            
            $this->Canvasser->updateCanvass(
                $c->id,
                array(
                            'user_response' => $this->input->post('message'),
                            'user_from' => $this->log_user_data->user_id,
                            'status' => 6,
                        )
            );
            $res = $this->Request->updateRequestStatus($request_id, 15);

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
                    'user_to' => $c->user_id,
                    'message' => $this->input->post('message'),
                    'request_id' => $request_id,
                );

            $this->xwb_purchasing->setShortCodes($shortcodes);

            $condition = $this->xwb_purchasing->getShortCodes();
            /* sending email notification */
            $msg = $this->xwb_purchasing->getMessage('to_canvass_edit');
            $message = do_shortcode($msg['message'], $condition);
            $site_title = $this->config->item('site_title');
            $this->xwb_purchasing->sendmail($condition['email_to'], $msg['subject'], $message, $condition['email_from'], $site_title, $msg['subject']);

            $this->xwb_purchasing->addHistory('request_list', $request_id, 'Return to Canvasser', 'Return to Canvasser for edit', $this->log_user_data->user_id);

            if ($res) {
                $data['status'] = true;
                $data['message'] = "Request returned to canvasser";
            } else {
                $data['status'] = false;
                $data['message'] = "Error updating data, please contact the system programmer";
            }
        }
        echo $this->xwbJsonEncode($data);
    }

    /**
     * get response of the user
     *
     * @return json
     */
    public function getResponse()
    {
        $request_id = $this->input->post('request_id');
        $this->load->model('canvasser/Canvasser_model', 'Canvasser');
        $c = $this->Canvasser->getCanvassByRequest($request_id)->row();
        $data['reason'] = $c->canvass_message;
        echo $this->xwbJsonEncode($data);
    }


    /**
     * assign to budget department
     *
     * @return json
     */
    public function assignBudget()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_id', 'User', 'required');

        if ($this->form_validation->run() == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
            echo $this->xwbJsonEncode($data);
            exit();
        } else {
            $posts = $this->input->post();
            $this->load->model('canvasser/Canvasser_model', 'Canvasser');
            $this->load->model('budget/Budget_model', 'Budget');
            $r = $this->Request->getRequest($posts['request_id'])->row();

            $posts = $this->input->post();
            $db_data = array(
                        'status' => 4,
                        'date_updated' => date('Y-m-d H:i:s')
                        );
            $this->db->where('id', $posts['request_id']);
            $this->db->update('request_list', $db_data);

            
            $b = $this->Budget->getBudgetByRequest($posts['request_id']);

            $db_data = array(
                'request_id' =>$posts['request_id'],
                'user_id' =>$posts['user_id'],
                'total_amount' => $r->total_amount,
                'canvass_id' => $r->canvas_id,
                'user_response' => $posts['message'],
                'user_from' => $this->log_user_data->user_id,
                'date_updated' =>date('Y-m-d H:i:s'),
                'status' => 0,
            );

            if ($b->num_rows()!=0) {
                $budget = $b->row();

                $this->db->where('id', $budget->id);
                $res = $this->db->update('budget_approval', $db_data);
            } else {
                $res = $this->db->insert('budget_approval', $db_data);
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
                    'user_to' => $posts['user_id'],
                    'message' => $this->input->post('message'),
                    'request_id' => $posts['request_id'],
                );
            $this->xwb_purchasing->setShortCodes($shortcodes);
            $condition = $this->xwb_purchasing->getShortCodes();
            /* sending email notification */
            $msg = $this->xwb_purchasing->getMessage('to_budget');
            $message = do_shortcode($msg['message'], $condition);
            $site_title = $this->config->item('site_title');
            $this->xwb_purchasing->sendmail($condition['email_to'], $msg['subject'], $message, $condition['email_from'], $site_title, $msg['subject']);
            $this->xwb_purchasing->addHistory('request_list', $posts['request_id'], 'Forwarded to Budget', 'Forwarded to budget for approval', $this->session->userdata('user_id'));
            if ($res) {
                $data['status'] = true;
                $data['message'] = 'Request has been forwarded to budget for approval';
            } else {
                $data['status'] = false;
                $data['message'] = 'Error updating record, please contact the programmer';
            }
        }


        echo $this->xwbJsonEncode($data);
    }


    /**
     * Get budget message
     *
     * @return json
     */
    public function getBudgetMsg()
    {
        $request_id = $this->input->post('request_id');
        $this->load->model('budget/Budget_model', 'Budget');
        $b = $this->Budget->getBudgetByRequest($request_id)->row();
        $data['reason'] = $b->budget_note;
        echo $this->xwbJsonEncode($data);
    }

    /**
     * Return to budget
     *
     * @return json
     */
    public function returnBudget()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('request_id', 'Request', 'required');
        $this->form_validation->set_rules('message', 'Message', 'required');

        if ($this->form_validation->run() == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
            echo $this->xwbJsonEncode($data);
            exit();
        } else {
            $this->load->model('budget/Budget_model', 'Budget');
            $posts = $this->input->post();
            $b = $this->Budget->getBudgetByRequest($posts['request_id'])->row();

            $db_data = array(
                    'user_response' => $posts['message'],
                    'user_from' => $this->log_user_data->user_id,
                    'status' => 6,
                    'date_updated' => date('Y-m-d H:i:s'),
                );
            $res = $this->Budget->updateBudgetApproval($b->id, $db_data);

            $this->Request->updateRequestStatus($posts['request_id'], 4);

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
                    'user_to' => $b->user_id,
                    'message' => $this->input->post('message'),
                    'request_id' => $posts['request_id'],
                );

            $this->xwb_purchasing->setShortCodes($shortcodes);

            $condition = $this->xwb_purchasing->getShortCodes();
            /* sending email notification */
            $msg = $this->xwb_purchasing->getMessage('admin_to_budget');
            $message = do_shortcode($msg['message'], $condition);
            $site_title = $this->config->item('site_title');
            $this->xwb_purchasing->sendmail($condition['email_to'], $msg['subject'], $message, $condition['email_from'], $site_title, $msg['subject']);


            $this->xwb_purchasing->addHistory('request_list', $posts['request_id'], 'Forwarded to Budget', 'Forwarded to budget for approval', $this->session->userdata('user_id'));


            if ($res) {
                $data['status'] = true;
                $data['message'] = 'You have responded to budget for approval';
            } else {
                $data['status'] = false;
                $data['message'] = 'Error updating record, please contact the programmer';
            }
        }

        echo $this->xwbJsonEncode($data);
    }


    /**
     * Remove Item
     *
     * @return json
     */
    public function removeItem()
    {
        $item_id = $this->input->post('item_id');
        $this->load->model('Item/Item_model', 'Item');
        $this->load->model('Supplier/Supplier_model', 'Supplier');
        $item = $this->Request->getItem($item_id)->row();
        $request = $this->Request->getRequest($item->request_id)->row();
        $req_user = $this->Request->getRequest($request->user_id)->row();

        if ($item->supplier_id == 0) {
            $supplier = $item->supplier;
            $supplier_col = 'vendor_name';
        } else {
            $s = $this->Supplier->getSupplier($item->supplier_id)->row();
            $supplier = $s->id;
            $supplier_col = 'supplier_id';
        }

        $this->load->model('Purchase_order/Purchase_order_model', 'PO');
        $po = $this->PO->getPOBySupplierRequest($item->request_id, $supplier, $supplier_col)->row();



        if ($po != null) {
            $data['status'] = false;
            $data['message'] = 'Unable to delete, Item has already being ordered';
            echo $this->xwbJsonEncode($data);
            exit();
        }

        if ($this->log_user_data->user_id != $request->user_id) {

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
                    'user_to' => $req_user->id,
                    'message' => $this->input->post('message'),
                    'request_id' => $item->request_id,
                    'item' => $item_id
                );

            $this->xwb_purchasing->setShortCodes($shortcodes);

            $condition = $this->xwb_purchasing->getShortCodes();
            /* sending email notification */
            $msg = $this->xwb_purchasing->getMessage('remove_item');
            $message = do_shortcode($msg['message'], $condition);
            $site_title = $this->config->item('site_title');
            $this->xwb_purchasing->sendmail($condition['email_to'], $msg['subject'], $message, $condition['email_from'], $site_title, $msg['subject']);
        }

        $this->xwb_purchasing->addHistory('po_items', $item_id, 'Item Removed', $item->product_name.' has been removed', $this->log_user_data->user_id);

        $this->db->where('id', $item_id);
        $res = $this->db->delete('po_items');
        if ($res) {
            $data['status'] = true;
            $data['message'] = 'Item successfully deleted';
        } else {
            $data['status'] = false;
            $data['message'] = 'Error updating record, please contact the programmer';
        }
        echo $this->xwbJsonEncode($data);
    }


    /**
     * Return request to the requisitioner with message
     *
     * @return json
     */
    public function returnRequest()
    {
        $request_id = $this->input->post('request_id');
        $message = $this->input->post('message');
        $r = $this->Request->getRequest($request_id)->row();
        $db_data = array(
                'admin_note' => $message,
                'status' => 19,
                'user_from' => $this->log_user_data->user_id,
                'date_updated' => date('Y-m-d H:i:s'),
            );
        $this->db->where('id', $request_id);
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
                'user_to' => $r->user_id,
                'message' => $this->input->post('message'),
                'request_id' => $request_id,
            );

        $this->xwb_purchasing->setShortCodes($shortcodes);

        $condition = $this->xwb_purchasing->getShortCodes();
        /* sending email notification */
        $msg = $this->xwb_purchasing->getMessage('admin_to_requisitioner');
        $message = do_shortcode($msg['message'], $condition);
        $site_title = $this->config->item('site_title');
        $this->xwb_purchasing->sendmail($condition['email_to'], $msg['subject'], $message, $condition['email_from'], $site_title, $msg['subject']);


        $this->xwb_purchasing->addHistory('request_list', $request_id, 'Return to Requesitioner', 'Request returned to requisitioner', $this->log_user_data->user_id);


        if ($res) {
            $data['status'] = true;
            $data['message'] = 'Request successfully returned';
        } else {
            $data['status'] = false;
            $data['message'] = 'Error updating record, please contact the programmer';
        }
        echo $this->xwbJsonEncode($data);
    }

    /**
     * Get requestor note of the request
     *
     * @return json
     */
    public function getReqMsg()
    {
        $request_id = $this->input->post('request_id');
        $r = $this->Request->getRequest($request_id)->row();
        $data['reason'] = $r->request_note;
        echo $this->xwbJsonEncode($data);
    }

    /**
     * Get PO items done
     *
     * @return type
     */
    public function getItemDone()
    {
        $request_id = $this->input->get('request_id');
        $this->load->model('item/Item_model', 'Item');
        $i = $this->Item->getApprovePOItemsByRequest($request_id);
        //pre($this->db->last_query());

        $data['data'] = array();
        if ($i->num_rows()>0) {
            foreach ($i->result() as $k => $v) {
                $data['data'][] = array(
                        $v->product_name,
                        $v->po_num,
                        date('F j, Y', strtotime($v->delivery_date)),
                        $v->product_description,
                        $v->quantity,
                    );
            }
        }

        echo $this->xwbJsonEncode($data);
    }

    /**
     * View staff request list
     *
     * @return mixed
     */
    public function staff_request()
    {
        $this->redirectUser(array('budget','canvasser','auditor','property','admin','board'));
        
        $data['page_title'] = 'Staff Request'; //title of the page
        $data['page_script'] = 'staff_request'; // script filename of the page user.js
        $this->renderPage('request/staff_request', $data);
    }


    /**
     * Generate requests data only for the staff of the current head users
     *
     * @return json
     */
    public function getStaffRequest()
    {

        $this->redirectUser(array('budget','canvasser','auditor','property','admin','board'));

        $this->load->model('user/User_model', 'User');
        $user_id = $this->log_user_data->user_id;
        $user = $this->User->getUser($user_id)->row();
        $branch = $user->branch_id;
        $department = $user->department_id;

        $this->Request->getStaffRequest($user_id, $branch, $department);

        $recordsTotal = $this->db->count_all_results();
        $args = array($user_id,$branch,$department);
        $recordsFiltered = $this->Request->countFiltered('getStaffRequest', $args);
        

        $this->Request->getStaffRequest($user_id, $branch, $department);
        if ($this->input->get('length') != -1) {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
        }
        $r = $this->db->get();

        $data['data'] = array();

        if ($r->num_rows()>0) {
            foreach ($r->result() as $key => $v) {
                $data['data'][] = array(
                                        sprintf('PR-%08d', $v->id),
                                        '<a class="btn btn-default btn-xs" href="'.base_url('request/view_request/'.$v->id).'">'.$v->request_name.'</a>',
                                        ucwords($v->full_name),
                                        date("F j, Y, g:i a", strtotime($v->date_created)),
                                        $v->purpose,
                                        '<a href="javascript:;" onClick="xwb.viewItems('.$v->id.')" class="btn btn-app"><i class="fa fa-search"></i>View Items</a>',
                                        //priority_label($v->priority_level).priority_time($v->priority_level,$v->date_created),
                                        ($v->date_needed==null?"":date("F j, Y", strtotime($v->date_needed))),
                                            $this->xwb_purchasing->getStatus('request', $v->status)." ".'<label class="badge">'.time_elapse($v->date_updated).'</label>',
                                        );
            }
        }
        $data['draw'] = $this->input->get('draw');
        $data['recordsTotal'] = $recordsTotal;
        $data['recordsFiltered'] = $recordsFiltered;
        echo $this->xwbJsonEncode($data);
    }


    /**
     * Archive request
     *
     * @return json
     */
    public function archiveRequest()
    {
        $request_id = $this->input->post('request_id');
        $res = $this->Request->archiveRequest($request_id);
        if ($res) {
            $data['status'] = true;
            $data['message'] = "Request has been archived";
        } else {
            $data['status'] = false;
            $data['message'] = "Error updating data, please contact the programmer";
        }

        echo $this->xwbJsonEncode($data);
    }


    /**
     * Restore Archive request
     *
     * @return json
     */
    public function unArchiveRequest()
    {
        $request_id = $this->input->post('request_id');
        $res = $this->Request->unArchiveRequest($request_id);
        if ($res) {
            $data['status'] = true;
            $data['message'] = "Request has been restored";
        } else {
            $data['status'] = false;
            $data['message'] = "Error updating data, please contact the programmer";
        }

        echo $this->xwbJsonEncode($data);
    }


    /**
     * Request Archived list view in the admin
     *
     * @return mixed
     */
    public function archived()
    {
        $this->redirectUser(array('admin','canvasser','budget','auditor','board','board'));
        $this->load->model('user/User_model', 'User');
        $this->load->model('request_category/Request_category_model', 'ReqCat');

        

        $data['page_title'] = 'Request Archive List'; //title of the page
        $data['page_script'] = 'req_archived_list'; // script filename of the page user.js

        $this->renderPage('request/req_archived_list', $data);
    }


    /**
     * Get all archived request in the admin
     *
     * @return json
     */
    public function getArchRequest()
    {
        $this->redirectUser();
        $this->load->model('user/User_model', 'User');

        $this->Request->getAdminArchRequest();

        $recordsTotal = $this->db->count_all_results();
        $recordsFiltered = $this->Request->countFiltered('getAdminArchRequest');
        

        $this->Request->getAdminArchRequest();
        if ($this->input->get('length') != -1) {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
        }
        $r = $this->db->get();


        $data['data'] = array();

        if ($r->num_rows()>0) {
            foreach ($r->result() as $key => $v) {
                if ($v->status == 1) {
                    $disabled = "disabled";
                } else {
                    $disabled = "";
                }

                $data['data'][] = array(
                                        sprintf('PR-%08d', $v->id),
                                        '<a class="btn btn-default btn-xs" href="'.base_url('request/view_request/'.$v->id).'">'.$v->request_name.'</a>',
                                        date("F j, Y, g:i a", strtotime($v->date_created)),
                                        ucwords($v->full_name),
                                        $v->department,
                                        $v->branch,
                                        $v->purpose,
                                        '<a href="javascript:;" onClick="xwb.viewItems('.$v->id.')" class="btn btn-app"><i class="fa fa-search"></i>View Items</a>',
                                        ($v->date_needed==null?"":date("F j, Y", strtotime($v->date_needed))),
                                        $this->reqActionBtn($v),
                                            $this->xwb_purchasing->getStatus('request', $v->status)." ".'<label class="badge">'.time_elapse($v->date_updated).'</label>',
                                        );
            }
        }
        $data['draw'] = $this->input->get('draw');
        $data['recordsTotal'] = $recordsTotal;
        $data['recordsFiltered'] = $recordsFiltered;
        echo $this->xwbJsonEncode($data);
    }




    /**
     * Print Canvassed items
     *
     * @param int $request_id
     * @return mixed
     */
    public function print_canvassed($request_id)
    {

        $this->redirectUser(array('page'=>'admin','members','canvasser','budget','auditor','board'));
        $this->load->model('user/User_model', 'User');
        $this->load->model('request/Request_model', 'Request');
        $this->load->model('budget/Budget_model', 'Budget');
        $this->load->model('canvasser/Canvasser_model', 'Canvasser');
        $this->load->model('branch/Branch_model', 'Branch');

        $branches = $this->Branch->getBranch()->result_array();
        $branches = array_column($branches, 'description');
        $branches = implode(' * ', $branches);
        
        $items = $this->Request->getItemsPerRequest($request_id)->result();
        $request = $this->Request->getRequest($request_id)->row();
        $requestor = $this->User->getUser($request->user_id)->row();
        $this->load->library('pdf');
        $filename = "request_$request_id";
        $pdfFilePath = FCPATH."downloads/pdf/$filename.pdf";
        $pdf = $this->pdf->load();
        $stylesheet = file_get_contents(FCPATH.'assets/css/pdfstyle.css');

        $req_approval = $this->Request->getReqForApprovalByRequest($request_id)->result();
        $budget = $this->Budget->getBudgetByRequest($request_id)->row();
        if ($budget == null || $budget->status != 1) {
            $budget_name = '';
            $budget_date = '';
        } else {
            $budget_name = $budget->full_name;
            $budget_date = date('F j, Y, g:i a', strtotime($budget->date_updated));
        }

        $canvasser = $this->Canvasser->getCanvassByRequest($request_id)->row();
        if ($canvasser == null || $canvasser->date_updated == null) {
            $canvass_name = '';
            $canvass_date = '';
        } else {
            $canvass_name = $canvasser->full_name;
            $canvass_date = date('F j, Y, g:i a', strtotime($canvasser->date_updated));
        }

        if ($request->status==13) {
            $approve_purchase_by = $this->User->getUser($request->approve_purchase_by)->row();
            $approve_purchase_date = date('F j, Y, g:i a', strtotime($canvasser->date_updated));
        } else {
            $approve_purchase_by = "";
            $approve_purchase_date = "";
        }

        //$pdf->SetDisplayMode('fullpage');
        ob_start();
        ?>
        <h3 class="text-center"><?php echo getConfig('company_name'); ?></h3>
        <p class="text-center"><?php echo $branches; ?> </p>
        <p class="text-center">Purchasing Department</p>
        
        <div class="received-date">
            <h5>RECEIVED</h5>
            <p>Date: _______________</p>
            <p>Time: _______________</p>
            <p>By: &emsp; _______________</p>
        </div>
        <hr />
        <!-- <h3 class="text-center">Purchase Canvassed Items</h3>
        <label class="pull-left">PRS No.:_______________________________________</label><br />
        <label class="pull-left">POF No.:_______________________________________</label>
        <br />
        <br /> -->
        
        <?php foreach ($items as $key => $value) :
            $cp = $this->Canvassed->getCanvassedItems($value->id);
            
        ?>
        <table border="1" cellpadding="1" cellspacing="0">
            <thead>
                <tr><th colspan="5" style="background-color: #dff0d8;"><?php echo strtoupper($value->product_name); ?></th></tr>
                <tr>
                    <th width="30%">Product Description</th>
                    <th width="30%">Supplier</th>
                    <th width="10%">Quantity</th>
                    <th width="15%">Unit Price</th>
                    <th width="15%">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($cp->num_rows()>0) :
                    foreach ($cp->result() as $cpK => $cpV) :
                        $style="";
                        if ($cpV->status == 1) {
                            $style = 'style="background-color: #EFE2B2;"';
                        }
                ?>
                        <tr <?php echo $style; ?>>
                            <td><?php echo $cpV->product_description; ?></td>
                            <td><?php echo $cpV->supplier; ?></td>
                            <td><?php echo $cpV->quantity; ?></td>
                            <td><?php echo $cpV->price; ?></td>
                            <td><?php echo $cpV->total_amount; ?></td>
                        </tr>
                <?php
                    endforeach;
                else :
                ?>
                    <tr style="background-color: #EFE2B2;">
                        <td><?php echo $value->product_description; ?></td>
                        <td><?php echo $value->supplier; ?></td>
                        <td><?php echo $value->quantity; ?></td>
                        <td><?php echo number_format($value->unit_price, 2, '.', ','); ?></td>
                        <td><?php echo number_format($value->quantity * $value->unit_price, 2, '.', ','); ?></td>
                    </tr>
                <?php
                endif;
                ?>
            </tbody>
        </table>
        <hr />
        <?php endforeach; ?>
        
        <?php
        $html = ob_get_contents();
        ob_end_clean();
        $pdf->SetTitle('Canvassed items for '.$request->request_name);
        $pdf->WriteHTML($stylesheet, 1);
        $pdf->WriteHTML($html);
        $pdf->Output();
    }
}

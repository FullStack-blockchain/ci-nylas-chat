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
 * Main controller for Canvasser
 */
class Xwb_canvasser extends XWB_purchasing_base
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
        $this->load->model('canvasser/Canvasser_model', 'Canvasser');
        $this->load->model('item/Item_model', 'Item');
    }
    

    /**
     * All users view
     *
     * @return mixed
     */
    public function index()
    {
        $this->redirectUser(array('canvasser'));
        

        $this->load->model('request/Request_model', 'Request');
        $this->load->model('admin/Admin_model', 'Admin');
        $user_id = $this->log_user_data->user_id;
        $request = $this->Request->getRequestListByUser($user_id)->result();
        $gauge_data = $this->Admin->generateGaugeData($request);
        $data['progress_label'] = $this->Admin->progressLabel();
        $data['gauge_data'] = $gauge_data;
        
        $groups = $this->db->get('groups');
        $data['groups'] = $groups->result();
        $data['page_title'] = 'Canvasser'; //title of the page
        $data['page_script'] = 'canvasser'; // script filename of the page user.js
        $this->renderPage('canvasser/canvasser', $data);
    }

    /**
     * All request view
     *
     * @return mixed
     */
    public function request()
    {
        $this->redirectUser(array('page'=>'canvasser'));
        
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
        $this->redirectUser(array('page'=>'canvasser'));
        
        $this->load->model('user/User_model', 'User');
        $data['budget_users'] = $this->User->getUsersByGroup('budget')->result();
        $data['admin_users'] = $this->User->getUsersByGroup('admin')->result();
        $data['page_title'] = 'Assigned Request'; //title of the page
        $data['page_script'] = 'assigned_req'; // script filename of the page user.js
        $this->renderPage('canvasser/assigned_req', $data);
    }


    /**
     * Get assigned request
     * @return type
     */
    public function getAssignedRequest()
    {
        $user_id = $this->session->userdata('user_id');
        $this->load->model('request/Request_model', 'Request');
    

        $this->Canvasser->getCanvassAssignedRequest($user_id);

        $recordsTotal = $this->db->count_all_results();
        $args = array($user_id);
        $recordsFiltered = $this->Canvasser->countFiltered('getCanvassAssignedRequest', $args);
        

        $this->Canvasser->getCanvassAssignedRequest($user_id);
        if ($this->input->get('length') != -1) {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
        }
        $req_assign = $this->db->get();



        $data['data'] = array();
        

        if ($req_assign->num_rows()>0) {
            foreach ($req_assign->result() as $key => $v) {
                if ($v->status!=2) {
                    $forwardBudgetBtn ="";
                } else {
                    $forwardBudgetBtn ='<a href="javascript:;" onClick="xwb.assignToBudget('.$v->id.')" class="btn btn-xs btn-info">Forward to Budget</a>';
                }
                if ($v->status==3) {
                    $disable_update = "disabled";
                } else {
                    $disable_update = "";
                }
                
                $data['data'][] = array(
                                        sprintf('PR-%08d', $v->request_id),
                                        $v->request_name,
                                        date("F j, Y, g:i a", strtotime($v->date_created)),
                                        //priority_label($v->priority_level).priority_time($v->priority_level,$v->date_created),
                                        ($v->date_needed==null?"":date("F j, Y", strtotime($v->date_needed))),
                                        '<a href="javascript:;" onClick="xwb.viewItems('.$v->request_id.')" class="btn btn-app"><i class="fa fa-search"></i>View Items</a>',
                                        number_format($v->total_amount, 2, '.', ','),
                                        $this->canvassActionBtn($v->id, $v->status),
                                        $this->xwb_purchasing->getStatus('canvass', $v->status)." ".'<label class="badge badge-info">'.time_elapse($v->date_updated).'</label>',
                                        );
            }
        }
        $data['draw'] = $this->input->get('draw');
        $data['recordsTotal'] = $recordsTotal;
        $data['recordsFiltered'] = $recordsFiltered;
        echo $this->xwbJsonEncode($data);
    }


    /**
     * Mark request as done
     *
     * @return json
     */
    public function requestDone()
    {
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


    /**
     * View items to update
     *
     * @param int $canvass_id
     * @return mixed
     */
    public function update_items($canvass_id)
    {
        $this->redirectUser(array('page'=>'canvasser'));

        $this->load->model('product/Product_model', 'Product');
        $this->load->model('request/Request_model', 'Request');
        $this->load->model('supplier/Supplier_model', 'Supplier');
        
        $groups = $this->db->get('groups');
        $data['groups'] = $groups->result();
        $data['canvass'] = $this->Canvasser->getCanvass($canvass_id)->row();
        //pre($data['canvass']);
        $data['request_id'] = $data['canvass']->request_id;

        $data['unit_measurements'] = $this->config->item('unit_measurement');

        $data['products'] = $this->Product->getProducts()->result();
        $data['items'] = $this->Request->getItemsPerRequestCanvasser($data['canvass']->request_id)->result();

        $data['suppliers'] = $this->Supplier->getSuppliers()->result();

        $data['page_title'] = 'Products/Items'; //title of the page
        $data['page_script'] = 'update_items'; // script filename of the page user.js
        $this->renderPage('canvasser/update_items', $data);
    }



    /**
     * update items post method
     *
     * @return json
     */
    public function updateItems()
    {
        $this->load->helper('security');
        $this->load->model('request/Request_model', 'Request');
        $this->load->model('supplier/Supplier_model', 'Supplier');
        $this->load->model('item/Item_model', 'Item');
        $this->load->model('canvassed/Canvassed_model', 'Canvassed');
        
        $this->form_validation->set_rules('init_canvass_date', 'Initial Canvass Date', 'required');
        if ($this->input->post('include_supplier1[]')) {
            foreach ($this->input->post('include_supplier1[]') as $key => $value) {
                $this->form_validation->set_rules('supplier1['.$key.']', 'Supplier', 'required');
                $this->form_validation->set_rules('unit_price1['.$key.']', 'Unit Price', 'required|greater_than[0]');
                $this->form_validation->set_rules('qty1['.$key.']', 'Quantity', 'required|greater_than[0]');
                $this->form_validation->set_rules('total1['.$key.']', 'Total', 'required|greater_than[0]');
            }
        }

        if ($this->input->post('include_supplier2[]')) {
            foreach ($this->input->post('include_supplier2[]') as $key => $value) {
                $this->form_validation->set_rules('supplier2['.$key.']', 'Supplier', 'required');
                $this->form_validation->set_rules('unit_price2['.$key.']', 'Unit Price', 'required|greater_than[0]');
                $this->form_validation->set_rules('qty2['.$key.']', 'Quantity', 'required|greater_than[0]');
                $this->form_validation->set_rules('total2['.$key.']', 'Total', 'required|greater_than[0]');
            }
        }

        if ($this->input->post('include_supplier3[]')) {
            foreach ($this->input->post('include_supplier3[]') as $key => $value) {
                $this->form_validation->set_rules('supplier3['.$key.']', 'Supplier', 'required');
                $this->form_validation->set_rules('unit_price3['.$key.']', 'Unit Price', 'required|greater_than[0]');
                $this->form_validation->set_rules('qty3['.$key.']', 'Quantity', 'required|greater_than[0]');
                $this->form_validation->set_rules('total3['.$key.']', 'Total', 'required|greater_than[0]');
            }
        }

        if ($this->input->post('include_supplier4[]')) {
            foreach ($this->input->post('include_supplier4[]') as $key => $value) {
                $this->form_validation->set_rules('supplier4['.$key.']', 'Supplier', 'required');
                $this->form_validation->set_rules('unit_price4['.$key.']', 'Unit Price', 'required|greater_than[0]');
                $this->form_validation->set_rules('qty4['.$key.']', 'Quantity', 'required|greater_than[0]');
                $this->form_validation->set_rules('total4['.$key.']', 'Total', 'required|greater_than[0]');
            }
        }

        $this->form_validation->set_rules('net_total', 'Total Amount', 'required|greater_than[0]');


        if ($this->form_validation->run($this) == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
        } else {
            $posts = $this->input->post();
            $request_id = $posts['request_id'];
            $canvass_id = $posts['canvass_id'];

            /* Save to canvassed items */

            $canvassed_new_supplier = [];
            $canvassed_update_supplier = [];
            $new_db_data = [];
            for ($i=1; $i <= 4; $i++) {
                foreach ($posts['supplier'.$i] as $key => $value) {
                    if (substr($key, 0, 3) === "new") {
                        $po_id = explode('_', $key);
                        $item_id = $po_id[1];
                        $cp_id = '';
                    } else {
                        $po_id = explode('_', $key);
                        $item_id = $po_id[0];
                        $cp_id = $po_id[1];
                    }

                    $item = $this->Item->getItem($item_id)->row();

                    /* New Canvassed Item */
                    if ($cp_id == "") {
                        if ($this->input->post('include_supplier'.$i.'[]') && array_key_exists($key, $posts['include_supplier'.$i])) {
                            $status = 1;
                        } else {
                            $status = 0;
                        }
                        $canvassed_new_supplier = array(
                                'request_id' => $request_id,
                                'canvass_id' => $canvass_id,
                                'item_id' => $item_id,
                                'product_id' => $item->product_id,
                                'product_name' => $item->product_name,
                                'product_description' => $item->product_description,
                                'quantity' => $posts['qty'.$i][$key],
                                'price' => $posts['unit_price'.$i][$key],
                                'total_amount' => $posts['total'.$i][$key],
                                'status' => $status,
                                'date_updated' => date('Y-m-d H:i:s'),
                            );


                        $supplier = $posts['supplier'.$i][$key];

                        if (ctype_digit($supplier)) {
                            $s = $this->Supplier->getSupplier($supplier)->row();
                            if (is_null($s)) {
                                $canvassed_new_supplier['supplier_id'] = 0;
                                $canvassed_new_supplier['supplier'] = $supplier;
                            } else {
                                $canvassed_new_supplier['supplier_id'] = $supplier;
                                $canvassed_new_supplier['supplier'] = $s->supplier_name;
                            }
                        } else {
                            $canvassed_new_supplier['supplier_id'] = 0;
                            $canvassed_new_supplier['supplier'] = $supplier;
                        }

                        $new_db_data[] = $canvassed_new_supplier;
                    } else {
                        // Update canvassed prices
                        if ($this->input->post('include_supplier'.$i.'[]') && array_key_exists($key, $posts['include_supplier'.$i])) {
                            $status = 1;
                        } else {
                            $status = 0;
                        }
                        $canvassed_update_supplier = array(
                                'request_id' => $request_id,
                                'canvass_id' => $canvass_id,
                                'item_id' => $item_id,
                                'product_id' => $item->product_id,
                                'product_name' => $item->product_name,
                                'product_description' => $item->product_description,
                                'quantity' => $posts['qty'.$i][$key],
                                'price' => $posts['unit_price'.$i][$key],
                                'total_amount' => $posts['total'.$i][$key],
                                'status' => $status,
                                'date_updated' => date('Y-m-d H:i:s'),
                            );

                        $supplier = $posts['supplier'.$i][$key];


                        if (ctype_digit($supplier)) {
                            $s = $this->Supplier->getSupplier($supplier)->row();
                            if (is_null($s)) {
                                $canvassed_update_supplier['supplier_id'] = 0;
                                $canvassed_update_supplier['supplier'] = $supplier;
                            } else {
                                $canvassed_update_supplier['supplier_id'] = $supplier;
                                $canvassed_update_supplier['supplier'] = $s->supplier_name;
                            }
                        } else {
                            $canvassed_update_supplier['supplier_id'] = 0;
                            $canvassed_update_supplier['supplier'] = $supplier;
                        }

                        $this->Canvassed->updateData($cp_id, $canvassed_update_supplier);
                    }
                }
            }


            if (count($new_db_data)>0) {
                $this->db->insert_batch('canvassed_prices', $new_db_data);
            }



            //get existing items
            $existing_items = $this->Request->getItemsPerRequest($request_id)->result();
            $existing_item_ids = array();
            foreach ($existing_items as $key => $existingVal) {
                $existing_item_ids[] = $existingVal->id;
            }

            //get included items
            $included_items = array();
            $included_item_keys = array();
            for ($i=1; $i <= 4; $i++) {
                if ($this->input->post('include_supplier'.$i.'[]')) {
                    foreach ($posts['include_supplier'.$i] as $key => $value) {
                        $included_item_keys[] = $key;

                        if (substr($key, 0, 3) === "new") {
                            $po_id = explode('_', $key);
                            $included_items[] = $po_id[1];
                        } else {
                            $po_id = explode('_', $key);
                            $included_items[] = $po_id[0];
                        }
                        
                        $included_supplier[$key] = $posts['supplier'.$i][$key];
                        $included_unit_price[$key] = $posts['unit_price'.$i][$key];
                        $included_quantity[$key] = $posts['qty'.$i][$key];
                    }
                }
            }


            // deleting existing items not checked
            foreach ($existing_item_ids as $key => $value) {
                if (!in_array($value, $included_items)) {
                    $this->db->delete('po_items', array('id' => $value));
                }
            }


            // Process insert update po_items
            $new_db_data = array();
            $update_db_data = array();
            $count_duplicate = array();
            foreach ($included_item_keys as $key => $value) {
                if (substr($value, 0, 3) === "new") {
                    $item = explode('_', $value);
                    $item_id = $item[1];
                } else {
                    $item = explode('_', $value);
                    $item_id = $item[0];
                }
                

                $count_duplicate[] = $item_id;
                
                if (in_array($item_id, $existing_item_ids) && array_count_values($count_duplicate)[$item_id] == 1) {
                    // Items to update
                    $arr_data = array(
                        'unit_measurement' => $posts['unit_measurements'][$item_id],
                        'unit_price' => $included_unit_price[$value],
                        'quantity' => $included_quantity[$value],
                        'date_updated' => date('Y-m-d H:i:s'),
                    );

                    if (ctype_digit($included_supplier[$value])) {
                        $s = $this->Supplier->getSupplier($included_supplier[$value])->row();
                            
                        if (is_null($s)) {
                            $arr_data['supplier_id'] = 0;
                            $arr_data['supplier'] = $included_supplier[$value];
                        } else {
                            $arr_data['supplier_id'] = $included_supplier[$value];
                            $arr_data['supplier'] = $s->supplier_name;
                        }
                    } else {
                        $arr_data['supplier_id'] = 0;
                        $arr_data['supplier'] = $included_supplier[$value];
                    }

                    $update_db_data = $arr_data;
                    $this->Request->updateItem($item_id, $update_db_data);
                } else {
                    // Item to insert

                    for ($i=1; $i <= 4; $i++) {
                        if (array_key_exists($value, $posts['supplier'.$i])) {
                            //$posts['supplier'.$i][$value]
                            $item = explode('_', $value);
                            if (substr($value, 0, 3) === "new") {
                                $item = explode('_', $value);
                                $item_id = $item[1];
                            } else {
                                $item = explode('_', $value);
                                $item_id = $item[0];
                            }
                            $item = $this->Item->getItem($item_id)->row();
                            $unit_price = $posts['unit_price'.$i][$value];
                            $quantity = $posts['qty'.$i][$value];
                            $supplier = $posts['supplier'.$i][$value];
                            $arr_data = array(
                                    'request_id' => $item->request_id,
                                    'product_id' => $item->product_id,
                                    'product_name' => $item->product_name,
                                    'product_description' => $item->product_description,
                                    'unit_price' => $unit_price,
                                    'quantity' => $quantity,
                                    'date_updated' => date('Y-m-d H:i:s'),
                                    );

                            if (ctype_digit($supplier)) {
                                $s = $this->Supplier->getSupplier($supplier)->row();
                                if (is_null($s)) {
                                    $arr_data['supplier_id'] = 0;
                                    $arr_data['supplier'] = $supplier;
                                } else {
                                    $arr_data['supplier_id'] = $supplier;
                                    $arr_data['supplier'] = $s->supplier_name;
                                }
                            } else {
                                $arr_data['supplier_id'] = 0;
                                $arr_data['supplier'] = $supplier;
                            }
                            $new_db_data[] = $arr_data;
                        }
                    }
                }
            }

            // Insert new po items
            if (count($new_db_data)>0) {
                $this->db->insert_batch('po_items', $new_db_data);
            }


            $this->updateNetAmmount($posts['request_id'], $posts['canvass_id'], $posts['net_total']);


            $db_data = array(
                    'status'=>2,
                    'total_amount'=>$posts['net_total'],
                    'date_updated' => date('Y-m-d H:i:s'),
                    'init_canvass_date' =>$this->input->post('init_canvass_date')
                    );


            $res = $this->updateCanvass($posts['canvass_id'], $db_data);

            

            $data['status'] = true;
            $data['message'] = 'Items has been updated';
        }
        echo $this->xwbJsonEncode($data);
    }



    /*public function updateItems(){
		$this->load->model('Request_model','Request');
		$this->load->model('Supplier_model','Supplier');
		
		$this->form_validation->set_rules('init_canvass_date', 'Initial Canvass Date', 'required');
		if($this->input->post('include_supplier1[]')){
			foreach ($this->input->post('include_supplier1[]') as $key => $value) {
				$this->form_validation->set_rules('supplier1['.$key.']', 'Supplier', 'required');
				$this->form_validation->set_rules('unit_price1['.$key.']', 'Unit Price', 'required|greater_than[0]');
				$this->form_validation->set_rules('qty1['.$key.']', 'Quantity', 'required|greater_than[0]');
				$this->form_validation->set_rules('total1['.$key.']', 'Total', 'required|greater_than[0]');
				
			}
		}

		if($this->input->post('include_supplier2[]')){
			foreach ($this->input->post('include_supplier2[]') as $key => $value) {
				$this->form_validation->set_rules('supplier2['.$key.']', 'Supplier', 'required');
				$this->form_validation->set_rules('unit_price2['.$key.']', 'Unit Price', 'required|greater_than[0]');
				$this->form_validation->set_rules('qty2['.$key.']', 'Quantity', 'required|greater_than[0]');
				$this->form_validation->set_rules('total2['.$key.']', 'Total', 'required|greater_than[0]');
			}
		}

		if($this->input->post('include_supplier3[]')){
			foreach ($this->input->post('include_supplier3[]') as $key => $value) {
				$this->form_validation->set_rules('supplier3['.$key.']', 'Supplier', 'required');
				$this->form_validation->set_rules('unit_price3['.$key.']', 'Unit Price', 'required|greater_than[0]');
				$this->form_validation->set_rules('qty3['.$key.']', 'Quantity', 'required|greater_than[0]');
				$this->form_validation->set_rules('total3['.$key.']', 'Total', 'required|greater_than[0]');
			}
		}

		if($this->input->post('include_supplier4[]')){
			foreach ($this->input->post('include_supplier4[]') as $key => $value) {
				$this->form_validation->set_rules('supplier4['.$key.']', 'Supplier', 'required');
				$this->form_validation->set_rules('unit_price4['.$key.']', 'Unit Price', 'required|greater_than[0]');
				$this->form_validation->set_rules('qty4['.$key.']', 'Quantity', 'required|greater_than[0]');
				$this->form_validation->set_rules('total4['.$key.']', 'Total', 'required|greater_than[0]');
			}
		}

		$this->form_validation->set_rules('net_total', 'Total Amount', 'required');
		
		
		
 		if ($this->form_validation->run() == FALSE){
 			$data['status'] = false;
 			$data['message'] = validation_errors();
                   
        }else{

        	$posts = $this->input->post();

        	pre($posts);

        	$db_data = [];
        	if($this->input->post('new_unit_price[]') != null){

        		foreach ($posts['new_unit_price'] as $key => $value) {
        			$arr_data = array(
	        			'request_id' => $posts['request_id'],
	        			'product_id' => $posts['product_id'][$key],
	        			'product_name' => $posts['new_product_name'][$key],
	        			'product_description' => $posts['new_product_description'][$key],
	        			'unit_price' => $posts['new_unit_price'][$key],
	        			'quantity' => $posts['new_quantity'][$key],
	        			'date_updated' => date('Y-m-d H:i:s'),
	        			);

        			if(ctype_digit($posts['new_supplier'][$key])){
        				$s = $this->Supplier->getSupplier($posts['new_supplier'][$key])->row();
        				if(is_null($s)){
        					$arr_data['supplier_id'] = 0;
							$arr_data['supplier'] = $posts['new_supplier'][$key];
        				}else{
							$arr_data['supplier_id'] = $posts['new_supplier'][$key];
							$arr_data['supplier'] = $s->supplier_name;
        				}

		        	}else{
		        		$arr_data['supplier_id'] = 0;
						$arr_data['supplier'] = $posts['new_supplier'][$key];
		        	}

	        		$db_data[] = $arr_data;

	        	}

	        	$this->db->insert_batch('po_items',$db_data);
        	}

        	foreach ($posts['price'] as $key => $value) {
        		$arr_data = array(
        			'unit_price' => str_replace(',','',$value),
        			'quantity' => $posts['quantity'][$key],
        			'date_updated' => date('Y-m-d H:i:s'),
        			);

        			if(ctype_digit($posts['supplier'][$key])){
        				$s = $this->Supplier->getSupplier($posts['supplier'][$key])->row();
        					
        				if(is_null($s)){
        					$arr_data['supplier_id'] = 0;
							$arr_data['supplier'] = $posts['supplier'][$key];
        				}else{
							$arr_data['supplier_id'] = $posts['supplier'][$key];
							$arr_data['supplier'] = $s->supplier_name;
        				}

		        	}else{
		        		$arr_data['supplier_id'] = 0;
						$arr_data['supplier'] = $posts['supplier'][$key];
		        	}

		        $db_data = $arr_data;

        		$this->Request->updateItem($key,$db_data);
        	}


        	$this->updateNetAmmount($posts['request_id'],$posts['canvass_id'],$posts['net_total']);


        	$db_data = array(
        			'status'=>2,
        			'total_amount'=>$posts['net_total'],
        			'date_updated' => date('Y-m-d H:i:s'),
        			'init_canvass_date' =>$this->input->post('init_canvass_date')
        			);


        	$res = $this->updateCanvass($posts['canvass_id'],$db_data);

        	

        	$data['status'] = true;
 			$data['message'] = 'Items has been updated';
        }

       	echo $this->xwbJsonEncode($data);
	}*/


    /**
     * Update Net amount
     *
     * @param int $request_id
     * @param int $canvass_id
     * @param float $net_amount
     * @return void
     */
    public function updateNetAmmount($request_id, $canvass_id, $net_amount)
    {

        $this->db->where('id', $request_id);
        return $this->db->update('request_list', array('total_amount'=>$net_amount,'date_updated' => date('Y-m-d H:i:s')));
    }


    /**
     * Update Canvass
     *
     * @param int $id
     * @param type|array $db_data
     * @return boolean
     */
    public function updateCanvass($id, $db_data = array())
    {

        $this->db->where('id', $id);
        return $this->db->update('canvass', $db_data);
    }



    /**
     * Canvass Action Button generator
     *
     * @param int $canvass_id
     * @param int $status
     * @return string
     */
    public function canvassActionBtn($canvass_id, $status)
    {

        $this->redirectUser();
        $defaultbtn = '<li><a href="'.base_url('canvasser/update_items/'.$canvass_id).'">Update Items</a></li>';
        $defaultbtn .= '<li><a target="_blank" href="'.base_url('canvasser/print_request/'.$canvass_id).'" >Print Request</a></li>';
        $defaultbtn .= '<li><a href="javascript:;" onClick="xwb.supplierSummary('.$canvass_id.')" >Supplier Summary</a></li>';
        //$defaultbtn .= '<li><a target="_blank" href="'.base_url('canvasser/print_canvassed/'.$canvass_id).'" >Print Canvassed</a></li>';
        
        
        switch ($status) {
            case 1:
                $btn = $defaultbtn;
                $btn .= '<li class="has-action"><a href="javascript:;" onClick="xwb.toRequisitioner('.$canvass_id.')">Return to Requisitioner</a></li>';
                break;

            case 2:
                $btn = $defaultbtn;
                //$btn .= '<li><a href="javascript:;" onClick="xwb.assignToBudget('.$canvass_id.')">Forward to Budget</a></li>';
                $btn .= '<li class="has-action"><a href="javascript:;" onClick="xwb.assignToAdmin('.$canvass_id.')">Forward to Admin</a></li>';
                $btn .= '<li class="has-action"><a href="javascript:;" onClick="xwb.toRequisitioner('.$canvass_id.')">Return to Requisitioner</a></li>';
                break;
            case 3:
                $btn = $defaultbtn;
                break;
            case 4:
                $btn = $defaultbtn;
                break;
            case 5:
                $btn = $defaultbtn;
                break;
            case 6:
                $btn = $defaultbtn;
                $btn .= '<li class="has-action"><a href="javascript:;" onClick="xwb.view_response('.$canvass_id.')">View Response</a></li>';
                $btn .= '<li class="has-action"><a href="javascript:;" onClick="xwb.toRequisitioner('.$canvass_id.')">Return to Requisitioner</a></li>';
                break;
            case 7:
                $btn = $defaultbtn;
                break;
            case 8:
                $btn = $defaultbtn;
                $btn .= '<li class="has-action"><a href="javascript:;" onClick="xwb.view_response('.$canvass_id.')">View Response</a></li>';
                $btn .= '<li class="has-action"><a href="javascript:;" onClick="xwb.assignToAdmin('.$canvass_id.')">Forward to Admin</a></li>';
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
     * assigne to purchasing department
     *
     * @return json
     */
    public function assignPurchasing()
    {
        $posts = $this->input->post();
        $this->load->model('Canvasser_model', 'Canvasser');
        $c = $this->Canvasser->getCanvass($posts['canvass_id'])->row();

        $posts = $this->input->post();
        $db_data = array(
                    'status' => 14,
                    'date_updated' => date('Y-m-d H:i:s')
                    );
        $this->db->where('id', $c->request_id);
        $this->db->update('request_list', $db_data);

        $db_data = array(
                    'status' => 4,
                    'date_updated' => date('Y-m-d H:i:s')
                    );
        $this->db->where('id', $posts['canvass_id']);
        $this->db->update('canvass', $db_data);


        /* add history for tracking and emails */

        $this->xwb_purchasing->addHistory('request_list', $c->request_id, 'Forwarded to Purchasing', 'Forwarded to purchasing department for approval', $this->log_user_data->user_id);

        $this->load->model('user/User_model', 'User');
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
        $admins = [];
        if ($c->user_from == null) {
            $admins = $this->User->getUsersByGroup('admin')->result();
        } else {
            $admin_user = $this->User->getUser($c->user_from)->row();
            $admins[] = $admin_user;
        }


        foreach ($admins as $key => $vAdmins) {
            /* sending email notification */
            $shortcodes = array(
                    'user_to' => $vAdmins->id,
                    'message' => $this->input->post('reason'),
                    'request_id' => $c->request_id
                );

            $this->xwb_purchasing->setShortCodes($shortcodes);

            $condition = $this->xwb_purchasing->getShortCodes();

            $msg = $this->xwb_purchasing->getMessage('to_admin_review');
            $message = do_shortcode($msg['message'], $condition);
            $site_title = $this->config->item('site_title');
            $res = $this->xwb_purchasing->sendmail($condition['email_to'], $msg['subject'], $message, $condition['email_from'], $site_title, $msg['subject']);
        }
        
        
        if ($res) {
            $data['status'] = true;
            $data['message'] = 'Request has been forwarded to Admin for review';
        } else {
            $data['status'] = false;
            $data['message'] = 'Error updating record, please contact the programmer';
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
        $canvass_id = $this->input->post('canvass_id');
        $c = $this->Canvasser->getCanvass($canvass_id)->row();
        $data['reason'] = $c->user_response;
        echo $this->xwbJsonEncode($data);
    }



    /**
     * Responde to status
     *
     * @return type
     */
    public function respond()
    {
        $this->redirectUser();
        $this->form_validation->set_rules('canvass_id', 'Canvass ID.', 'required|alpha_dash');
        $this->form_validation->set_rules('response', 'Response', 'required');

    
        if ($this->form_validation->run() == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
            echo $this->xwbJsonEncode($data);
            exit();
        } else {
            $this->load->model('user/User_model', 'User');
            $this->load->model('request/Request_model', 'Request');

            $posts = $this->input->post();
            $c = $this->Canvasser->getCanvass($posts['canvass_id'])->row();

            if ($c->status == 6) {
                $new_canvass_stat = 7;
                $new_req_stat = 16;
                $usertype_to = "Admin";
            } elseif ($c->status == 8) {
                $new_canvass_stat = 5;
                $new_req_stat = 17;
                $usertype_to = "Requisitioner";
            }
            
            $db_data = array(
                    'canvass_message' => $posts['response'],
                    'status' => $new_canvass_stat,
                );

            $res = $this->Request->updateRequestStatus($c->request_id, $new_req_stat);

            $res = $this->Canvasser->updateCanvass($posts['canvass_id'], $db_data);


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
                    'user_to' => $c->user_from,
                    'message' => $posts['response'],
                    'request_id' => $c->request_id,
                );

            $this->xwb_purchasing->setShortCodes($shortcodes);

            $condition = $this->xwb_purchasing->getShortCodes();

            /* sending email notification */
            $msg = $this->xwb_purchasing->getMessage('canvasser_to_requisitioner');

            $message = do_shortcode($msg['message'], $condition);
            $site_title = $this->config->item('site_title');
            $this->xwb_purchasing->sendmail($condition['email_to'], $msg['subject'], $message, $condition['email_from'], $site_title, $msg['subject']);

            /* Adding to history transaction */
            $this->xwb_purchasing->addHistory('canvass', $c->id, 'Canvasser Response', 'Canvasser Response to '.$usertype_to, $this->log_user_data->user_id);


            if ($res) {
                $data['status'] = true;
                $data['message'] = 'You have responded to admin';
            } else {
                $data['status'] = false;
                $data['message'] = 'Error updating data, pelase contact the programmer';
            }
        }

        
        echo $this->xwbJsonEncode($data);
    }



    /**
     * Print request
     *
     * @param int $canvass_id
     * @return mixed
     */
    public function print_request($canvass_id)
    {
        $this->redirectUser(array('admin','member','canvasser','budget','auditor'));
        $this->load->model('user/User_model', 'User');
        $this->load->model('request/Request_model', 'Request');
        $this->load->model('budget/Budget_model', 'Budget');
        $this->load->model('canvasser/Canvasser_model', 'Canvasser');
        $this->load->model('purchase_order/Purchase_order_model', 'PO');
        
        $this->load->model('Branch/Branch_model', 'Branch');
        $branches = $this->Branch->getBranch()->result_array();
        $branches = array_column($branches, 'description');
        $branches = implode(' * ', $branches);

        $c = $this->Canvasser->getCanvass($canvass_id)->row();
        $request_id = $c->request_id;
        
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
                    <p class="upperline text-center text-12"><?php echo ucwords($value->head_name)." / ".$value->head_department; ?></p>
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


    public function returnRequisitioner()
    {
        $this->redirectUser();
        $this->form_validation->set_rules('canvass_id', 'Canvass ID.', 'required|alpha_dash');
        $this->form_validation->set_rules('message', 'Message', 'required');

    
        if ($this->form_validation->run() == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
            echo $this->xwbJsonEncode($data);
            exit();
        } else {
            $this->load->model('user/User_model', 'User');
            $this->load->model('request/Request_model', 'Request');

            $posts = $this->input->post();
            $c = $this->Canvasser->getCanvass($posts['canvass_id'])->row();
            $r = $this->Request->getRequest($c->request_id)->row();
            $db_data = array(
                    'canvass_message' => $posts['message'],
                    'status' => 5,
                );

            $res = $this->Request->updateRequestStatus($c->request_id, 17);

            $res = $this->Canvasser->updateCanvass($posts['canvass_id'], $db_data);


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
                    'request_id' => $c->request_id,
                );

            $this->xwb_purchasing->setShortCodes($shortcodes);

            $condition = $this->xwb_purchasing->getShortCodes();

            /* sending email notification */
            $msg = $this->xwb_purchasing->getMessage('admin_to_requisitioner');
            $message = do_shortcode($msg['message'], $condition);
            $site_title = $this->config->item('site_title');
            $this->xwb_purchasing->sendmail($condition['email_to'], $msg['subject'], $message, $condition['email_from'], $site_title, $msg['subject']);


            $this->xwb_purchasing->addHistory('canvass', $posts['canvass_id'], 'Canvasser to Requisitioner', 'Request return from canvasser to requisitioner', $this->log_user_data->user_id);
            if ($res) {
                $data['status'] = true;
                $data['message'] = 'Request successfully return to requisitioner';
            } else {
                $data['status'] = false;
                $data['message'] = 'Error updating data, pelase contact the programmer';
            }
        }

        
        echo $this->xwbJsonEncode($data);
    }



    /**
     * View for canvassed item suppliers
     *
     * @param int $canvass_id
     * @param int $item_id
     * @return mixed
     */
    public function canvassed($canvass_id, $item_id)
    {
        $this->redirectUser(array('page'=>'canvasser'));
        $this->load->model('request/Request_model', 'Request');
        $this->load->model('product/Product_model', 'Product');
        $this->load->model('supplier/Supplier_model', 'Supplier');
        $this->load->model('canvassed/Canvassed_model', 'Canvassed');
        
        $groups = $this->db->get('groups');
        $data['groups'] = $groups->result();
        $data['canvass'] = $this->Canvasser->getCanvass($canvass_id)->row();
        //pre($data['canvass']);
        $data['request_id'] = $data['canvass']->request_id;
        $data['products'] = $this->Product->getProducts()->result();
        
        $data['suppliers'] = $this->Supplier->getSuppliers()->result();

        $item = $this->Request->getItem($item_id)->row();
        $data['canvassed_items'] = $this->Canvassed->getCanvassedItems($item->id)->result();
        //pre($data['canvassed_items']);

        $data['item'] = $item;
        $data['page_title'] = 'Prices for '.$item->product_name; //title of the page
        $data['page_script'] = 'canvassed'; // script filename of the page user.js
        $this->renderPage('canvassed', $data);
    }


    /**
     * Print Canvassed items
     *
     * @param int $canvass_id
     * @return mixed
     */
    public function print_canvassed($canvass_id)
    {

        $this->redirectUser(array('admin','member','canvasser','budget','auditor'));
        $this->load->model('user/User_model', 'User');
        $this->load->model('request/Request_model', 'Request');
        $this->load->model('budget/Budget_model', 'Budget');
        $this->load->model('canvasser/Canvasser_model', 'Canvasser');
        $this->load->model('canvassed/Canvassed_model', 'Canvassed');
        $branches = $this->Branch->getBranch()->result_array();
        $branches = array_column($branches, 'description');
        $branches = implode(' * ', $branches);

        $c = $this->Canvasser->getCanvass($canvass_id)->row();
        $request_id = $c->request_id;
        
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
        <p class="text-center"><?php echo $branches; ?></p>
        <p class="text-center">Purchasing Department</p>
        
        <div class="received-date">
            <h5>RECEIVED</h5>
            <p>Date: _______________</p>
            <p>Time: _______________</p>
            <p>By: &emsp; _______________</p>
        </div>
        <hr />

        
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


    /**
     * Get list of supplier with total amount
     *
     * @return json
     */
    public function supplierSummary()
    {
        $this->load->model('item/Item_model', 'Item');
        $request_id = $this->input->get('request_id');
        if (!$request_id) {
            $canvass_id = $this->input->get('canvass_id');
            $c = $this->Canvasser->getCanvass($canvass_id)->row();
            $request_id = $c->request_id;
        }
        
        $res = $this->Item->supplierSummary($request_id);

        $data['data'] = array();

        if ($res->num_rows()>0) {
            $total_amount = 0;
            foreach ($res->result() as $key => $v) {
                $total_amount = $total_amount + $v->total_amount;
                $data['data'][] = array(
                                        $v->supplier,
                                        number_format($v->total_amount, 2, '.', ','),
                                        );
            }
            $data['footer'] = '<tr><td align="right"><strong class="pull-left">Total</strong></td><td><strong>'.number_format($total_amount, 2, '.', ',').'</strong></td></tr>';
        }
        echo $this->xwbJsonEncode($data);
    }
}

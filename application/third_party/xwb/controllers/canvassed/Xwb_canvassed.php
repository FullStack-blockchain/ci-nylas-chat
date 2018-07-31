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
 * Main controller for Canvassed
 */
class Xwb_canvassed extends XWB_purchasing_base
{

    /**
     * Run parent construct
     *
     * @return Null
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('canvassed/Canvassed_model', 'Canvassed');
    }


    /**
     * Get canvassed items for datatable
     * @return type
     */
    public function getCanvassed()
    {
        $this->redirectUser();
        $item_id = $this->input->get('item_id');
        $cp = $this->Canvassed->getCanvassedItems($item_id);
        
        $data['data'] = array();

        if ($cp->num_rows()>0) {
            foreach ($cp->result() as $key => $v) {
                $checked = "";
                if ($v->status==1) {
                    $checked = "checked";
                }
                $data['data'][] = array(
                                        '<input type="radio" '.$checked.' class="canvassed_item" name="canvassed_item" id="canvassed_item" value="'.$v->id.'" /> '.$v->id,
                                        $v->supplier,
                                        $v->product_description,
                                        $v->quantity,
                                        $v->price,
                                        $v->total_amount,
                                        '
										<a href="javascript:;" onClick="xwb.editCanvassedItem('.$v->id.');" class="btn btn-xs btn-warning">Edit</a>
										<a href="javascript:;" onClick="xwb.deleteCanvassedItem('.$v->id.');" class="btn btn-xs btn-danger">Delete</a>',
                                        );
            }
        }
        echo $this->xwbJsonEncode($data);
    }


    /**
     * Add canvassed item
     *
     * @return json
     */
    public function addItem()
    {
        $this->form_validation->set_rules('product_description', 'Product Description', 'required');
        $this->form_validation->set_rules('supplier', 'Supplier', 'required');
        $this->form_validation->set_rules('quantity', 'Quantity', 'required|numeric');
        $this->form_validation->set_rules('unit_price', 'Unit Price', 'required|numeric');

                
        
        if ($this->form_validation->run() == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
        } else {
            $this->load->model('item/Item_model', 'Item');

            $posts = $this->input->post();

            $i = $this->Item->getItemByCanvass($posts['canvasser_id'])->row();

            if (ctype_digit($posts['supplier'])) {
                $this->load->model('supplier/Supplier_model', 'Supplier');
                $s = $this->Supplier->getSupplier($posts['supplier'])->row();
                $supplier = $s->supplier_name;
                $supplier_id = $s->id;
            } else {
                $supplier = $posts['supplier'];
                $supplier_id = 0;
            }

            
            $db_data= array(
                    'canvass_id' => $posts['canvasser_id'],
                    'item_id' => $posts['item_id'],
                    'product_name' => $i->product_name,
                    'product_description' => $posts['product_description'],
                    'supplier' => $supplier,
                    'supplier_id' => $supplier_id,
                    'quantity' => $posts['quantity'],
                    'price' => $posts['unit_price'],
                    'total_amount' => $posts['quantity'] * $posts['unit_price'],
                    'status' => 0,
                );

            
            $res = $this->db->insert('canvassed_prices', $db_data);

            if ($res) {
                $data['status'] = true;
                $data['message'] = 'Canvassed Item has been successfully added.';
            } else {
                $data['status'] = false;
                $data['message'] = 'Error adding data, Please contact the programmer';
            }
        }

        echo $this->xwbJsonEncode($data);
        exit();
    }


    /**
     * Delete Canvassed Item
     *
     * @return type
     */
    public function deleteCanvassed()
    {
        $id = $this->input->post('id');
        $rows = $this->Canvassed->deleteCanvassed($id);
        if ($rows > 0) {
            $data['status'] = true;
            $data['message'] = "Canvassed item has been deleted";
        } else {
            $data['status'] = false;
            $data['message'] = "Error deleting record, please contact the system programmer";
        }

        echo $this->xwbJsonEncode($data);
        exit();
    }


    /**
     * update selected canvass price item
     *
     * @return json
     */
    public function updateSelectedItem()
    {
        $this->form_validation->set_rules('canvass_price_id', 'Canvassed Price', 'required|numeric');
        
        if ($this->form_validation->run() == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
        } else {
            $this->load->model('item/Item_model', 'Item');
            $this->load->model('canvasser/Canvasser_model', 'Canvasser');

            $posts = $this->input->post();
            $cp_id = $posts['canvass_price_id'];
            $c = $this->Canvasser->getCanvass($posts['canvasser_id'])->row();
            $request_id = $c->request_id;

            $item_amount = $this->Item->getNetAmount($request_id, $posts['canvasser_id'])->row();
            $net_amount = $item_amount->total_amount;

            $cp = $this->Canvassed->getCanvassedPrice($cp_id)->row();

            $db_data = array(
                'product_description' => $cp->product_description,
                'unit_price' => $cp->price,
                'quantity' => $cp->quantity,
                'supplier' => $cp->supplier,
                'supplier_id' => $cp->supplier_id,
                'date_updated' => date('Y-m-d H:i:s'),
            );
            $this->Item->updateData($posts['item_id'], $db_data);
            

            $db_data = array(
                    'status' => 1,
                    'date_updated' => date('Y-m-d H:i:s'),
                );
            $this->Canvassed->updateData($cp_id, $db_data);
            
            $this->updateNetAmmount($request_id, $net_amount);

            $db_data = array(
                    'total_amount'=>$net_amount,
                    'date_updated' => date('Y-m-d H:i:s'),
                    );


            $res = $this->updateCanvass($posts['canvasser_id'], $db_data);


            if ($res) {
                $data['status'] = true;
                $data['message'] = 'Canvassed Item has been successfully added.';
            } else {
                $data['status'] = false;
                $data['message'] = 'Error adding data, Please contact the programmer';
            }
        }

        echo $this->xwbJsonEncode($data);
        exit();
    }

    /**
     * Update Net amount
     *
     * @param int $request_id
     * @param int $canvass_id
     * @param float $net_amount
     * @return void
     */
    public function updateNetAmmount($request_id, $net_amount)
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
     * Get canvassed item for edit
     *
     * @return json
     */
    public function editCanvassedItem()
    {
        $cp_id = $this->input->post('cp_id');
        $cp = $this->Canvassed->getCanvassedPrice($cp_id)->row();
        echo $this->xwbJsonEncode($cp);
    }

    public function updateCanvassPrice()
    {
        $this->form_validation->set_rules('product_description', 'Product Description', 'required');
        $this->form_validation->set_rules('supplier', 'Supplier', 'required');
        $this->form_validation->set_rules('quantity', 'Quantity', 'required|numeric');
        $this->form_validation->set_rules('price', 'Unit Price', 'required|numeric');

                
        
        if ($this->form_validation->run() == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
        } else {
            $this->load->model('item/Item_model', 'Item');

            $posts = $this->input->post();

            $i = $this->Item->getItemByCanvass($posts['canvasser_id'])->row();

            if (ctype_digit($posts['supplier'])) {
                $this->load->model('supplier/Supplier_model', 'Supplier');
                $s = $this->Supplier->getSupplier($posts['supplier'])->row();
                $supplier = $s->supplier_name;
                $supplier_id = $s->id;
            } else {
                $supplier = $posts['supplier'];
                $supplier_id = 0;
            }

            
            $db_data= array(
                    'canvass_id' => $posts['canvasser_id'],
                    'item_id' => $posts['item_id'],
                    'product_name' => $i->product_name,
                    'product_description' => $posts['product_description'],
                    'supplier' => $supplier,
                    'supplier_id' => $supplier_id,
                    'quantity' => $posts['quantity'],
                    'price' => $posts['price'],
                    'total_amount' => $posts['quantity'] * $posts['price'],
                    'status' => 0,
                );


            $res = $this->Canvassed->updateData($posts['id'], $db_data);

            if ($res) {
                $data['status'] = true;
                $data['message'] = 'Canvassed Item has been Updated.';
            } else {
                $data['status'] = false;
                $data['message'] = 'Error updating data, Please contact the programmer';
            }
        }

        echo $this->xwbJsonEncode($data);
        exit();
    }
}

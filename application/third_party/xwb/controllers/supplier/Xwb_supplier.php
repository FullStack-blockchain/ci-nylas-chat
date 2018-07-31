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
 * Main controller for Supplier
 */
class Xwb_supplier extends XWB_purchasing_base
{

    
    /**
     * Run parent construct
     *
     * @return Null
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('supplier/Supplier_model', 'Supplier');
    }


    /**
     * Supplier view
     *
     * @return mixed
     */
    public function index()
    {
        $this->redirectUser(array('admin','board'));
        $data['page_title'] = 'Supplier'; //title of the page
        $data['page_script'] = 'supplier'; // script filename of the page
        $this->renderPage('supplier/supplier', $data);
    }


    /**
     * Get supplier data for datatable
     *
     * @return json
     */
    public function getSupplier()
    {
        $this->redirectUser(array('admin','board'));
        $s = $this->Supplier->getSuppliers();

        $data['data'] = array();

        if ($s->num_rows()>0) {
            foreach ($s->result() as $k => $v) {
                $data['data'][] = array(
                                        $v->id,
                                        $v->supplier_name,
                                        $v->email,
                                        $v->tel_number,
                                        $v->phone_number,
                                        $v->fax,
                                        getPaymentTerm($v->payment_terms),
                                        $v->address,
                                        '<a href="" class="btn btn-xs btn-warning xwb-edit-supplier" data-supplier="'.$v->id.'">Edit</a>
										<a href="" class="btn btn-xs btn-danger xwb-del-supplier" data-supplier="'.$v->id.'">Delete</a>'
                                        );
            }
        }
        echo $this->xwbJsonEncode($data);
    }


    /**
     * Add supplier method
     *
     * @return json
     */
    public function addSupplier()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('supplier_name', 'Supplier Name', 'required');
        $this->form_validation->set_rules('tel_number', 'Telephone', 'required');
        $this->form_validation->set_rules('phone_number', 'Phone Number', 'required');
        $this->form_validation->set_rules('address', 'Address', 'required');
        $this->form_validation->set_rules('fax', 'Fax Number', 'required');
        $this->form_validation->set_rules('payment_terms', 'Payment Term', 'required');
        $this->form_validation->set_rules('email', 'Email Address', 'required|valid_email|is_unique[supplier.email]');
        $this->form_validation->set_rules('address', 'Address', 'required');

        

        if ($this->form_validation->run() == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
        } else {
            $posts = $this->input->post();
            $db_data = array(
                        'supplier_name' => $posts['supplier_name'],
                        'tel_number' => $posts['tel_number'],
                        'phone_number' => $posts['phone_number'],
                        'address' => $posts['address'],
                        'email' => $posts['email'],
                        'fax' => $posts['fax'],
                        'payment_terms' => $posts['payment_terms'],
                    );
            $this->db->insert('supplier', $db_data);
            $data['status'] = true;
            $data['message'] = 'Supplier has been successfully added.';
        }

        echo $this->xwbJsonEncode($data);
        exit();
    }


    /**
     * Get supplier data for edit
     *
     * @return json
     */
    public function editSupplier()
    {
        $supplier_id = $this->input->post('supplier_id');
        $s = $this->db->get_where('supplier', array('id'=>$supplier_id))->row();
        echo $this->xwbJsonEncode($s);
        exit();
    }



    /**
     * Update Supplier
     *
     * @return json
     */
    public function updateSupplier()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('supplier_name', 'Supplier Name', 'required');
        $this->form_validation->set_rules('tel_number', 'Telephone', 'required');
        $this->form_validation->set_rules('phone_number', 'Phone Number', 'required');
        $this->form_validation->set_rules('address', 'Address', 'required');
                
        $this->form_validation->set_rules('fax', 'Fax Number', 'required');
        $this->form_validation->set_rules('payment_terms', 'Payment Term', 'required');

        $supplier_id = $this->input->post('id');


        $supp_unique = "";
        if ($supplier_id != "") {
            $s = $this->Supplier->getSupplier($supplier_id)->row();
            if ($s->email != $this->input->post('email')) {
                $supp_unique = "|is_unique[supplier.email]";
            }
        }


        $this->form_validation->set_rules('email', 'Email Address', 'required|valid_email'.$supp_unique);


        if ($this->form_validation->run() == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
        } else {
            $posts = $this->input->post();
            $db_data = array(
                        'supplier_name' => $posts['supplier_name'],
                        'tel_number' => $posts['tel_number'],
                        'phone_number' => $posts['phone_number'],
                        'address' => $posts['address'],
                        'email' => $posts['email'],
                        'fax' => $posts['fax'],
                        'payment_terms' => $posts['payment_terms'],
                    );

            $this->db->where('id', $this->input->post('id'));
            $this->db->update('supplier', $db_data);


            $data['status'] = true;
            $data['message'] = 'Supplier has been successfully updated.';
        }

        echo $this->xwbJsonEncode($data);
        exit();
    }



    /**
     * Delete Supplier
     *
     * @return array
     */
    public function deleteSupplier()
    {
        $id = $this->input->post('id');
        $this->db->where('id', $id);
        $this->db->delete('supplier');
        $res = $this->db->affected_rows();

        if ($res > 0) {
            $data['status'] = true;
            $data['message'] = "Supplier has been deleted";
        } else {
            $data['status'] = false;
            $data['message'] = "Error deleting record, please contact the system programmer";
        }

        echo $this->xwbJsonEncode($data);
        exit();
    }
}

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
 * Main controller for Branch module
 */
class Xwb_branch extends XWB_purchasing_base
{

    /**
     * Run parent construct
     *
     * @return Null
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('branch/branch_model', 'Branch');
    }
    

    /**
     * Branch view
     *
     * @return mixed
     */
    public function index()
    {
        $this->redirectUser(array('admin','board'));

        $data['page_title'] = 'Branches'; //title of the page
        $data['page_script'] = 'branches'; // script filename of the page branches.js
        $this->renderPage('branch/branch', $data);
    }

    /**
     * Get all Branch to datatable
     *
     * @return json
     */
    public function getBranch()
    {
        $d = $this->Branch->getBranch();
        $data['data'] = array();

        if ($d->num_rows()>0) {
            foreach ($d->result() as $key => $v) {
                $data['data'][] = array(
                                        $v->id,
                                        $v->name,
                                        $v->description,
                                        '<a href="" class="btn btn-xs btn-warning xwb-edit-branch" data-id="'.$v->id.'">Edit</a>
										<a href="" data-id="'.$v->id.'" class="btn btn-xs btn-danger xwb-delete-branch">Delete</a>',
                                        );
            }
        }
        echo $this->xwbJsonEncode($data);
    }


    /**
     * Get Branch
     *
     * @return json
     */
    public function editBranch()
    {
        $dept_id = $this->input->post('branch_id');
        $u = $this->db->get_where('branches', array('id'=>$dept_id))->row();
        echo $this->xwbJsonEncode($u);
        exit();
    }


    /**
     * Update Branch
     *
     * @return json
     */
    public function updateBranch()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Branch Name', 'required|alpha_dash');
        $this->form_validation->set_rules('description', 'Description', 'required');

        
        if ($this->form_validation->run() == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
        } else {
            $data = array(
                    'name' => $this->input->post('name'),
                    'description' => $this->input->post('description'),
            );

            $this->db->where('id', $this->input->post('id'));
            $this->db->update('branches', $data);


            $data['status'] = true;
            $data['message'] = 'Branch has been successfully updated.';
        }

        echo $this->xwbJsonEncode($data);
        exit();
    }


    /**
     * Add Branch method
     *
     * @return json
     */
    public function addBranch()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'required|alpha_dash');
        $this->form_validation->set_rules('description', 'Description', 'required');
        
        if ($this->form_validation->run() == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
        } else {
            $data = array(
                        'name' => $this->input->post('name'),
                        'description' => $this->input->post('description'),
                            );
            $this->db->insert('branches', $data);
            $data['status'] = true;
            $data['message'] = 'Branch has been successfully added.';
        }

        echo $this->xwbJsonEncode($data);
        exit();
    }


    /**
     * Delete Branch
     *
     * @return array
     */
    public function deleteBranch()
    {
        $rows = $this->Branch->deleteBranch();
        if ($rows > 0) {
            $data['status'] = true;
            $data['message'] = "Branch has been deleted";
        } else {
            $data['status'] = false;
            $data['message'] = "Error deleting record, please contact the system programmer";
        }

        echo $this->xwbJsonEncode($data);
        exit();
    }
}

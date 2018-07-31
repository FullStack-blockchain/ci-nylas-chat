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
 * Main controller for Department
 */
class Xwb_department extends XWB_purchasing_base
{

    /**
     * Run parent construct
     *
     * @return Null
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('department/Department_model', 'Department');
    }
    

    /**
     * All department view
     *
     * @return mixed
     */
    public function index()
    {
        $this->redirectUser(array('admin','board'));
        
        $data['page_title'] = 'Department'; //title of the page
        $data['page_script'] = 'department'; // script filename of the page department.js
        $this->renderPage('department/department', $data);
    }

    /**
     * Get all department to datatable
     *
     * @return json
     */
    public function getDept()
    {
        $d = $this->Department->getDept();
        
        $data['data'] = array();

        if ($d->num_rows()>0) {
            foreach ($d->result() as $key => $v) {
                $data['data'][] = array(
                                        $v->id,
                                        $v->name,
                                        $v->description,
                                        '<a href="" data-id="'.$v->id.'" class="btn btn-xs btn-warning xwb-edit-dept">Edit</a>
										<a href="" data-id="'.$v->id.'" class="btn btn-xs btn-danger xwb-del-dept">Delete</a>',
                                        );
            }
        }
        echo $this->xwbJsonEncode($data);
    }


    /**
     * Get Department
     *
     * @return json
     */
    public function editDept()
    {
        $dept_id = $this->input->post('dept_id');
        $u = $this->db->get_where('department', array('id'=>$dept_id))->row();
        echo $this->xwbJsonEncode($u);
        exit();
    }


    /**
     * Update Department
     *
     * @return json
     */
    public function updateDept()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Department Name', 'required|alpha_dash');
        $this->form_validation->set_rules('description', 'Description', 'required');
        //$this->form_validation->set_rules('department_head', 'Department Head', 'required');
                
        
        if ($this->form_validation->run() == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
        } else {
            $data = array(
                    'name' => $this->input->post('name'),
                    'description' => $this->input->post('description'),
                    //'department_head' => $this->input->post('department_head'),
            );

            $this->db->where('id', $this->input->post('id'));
            $this->db->update('department', $data);


            $data['status'] = true;
            $data['message'] = 'Department has been successfully updated.';
        }

        echo $this->xwbJsonEncode($data);
        exit();
    }


    /**
     * Add Department method
     *
     * @return json
     */
    public function addDept()
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
            $this->db->insert('department', $data);
            $data['status'] = true;
            $data['message'] = 'Department has been successfully added.';
        }

        echo $this->xwbJsonEncode($data);
        exit();
    }


    /**
     * Delete department
     *
     * @return array
     */
    public function deleteDept()
    {
        $rows = $this->Department->deleteDept();
        if ($rows > 0) {
            $data['status'] = true;
            $data['message'] = "Department has been deleted";
        } else {
            $data['status'] = false;
            $data['message'] = "Error deleting record, please contact the system programmer";
        }

        echo $this->xwbJsonEncode($data);
        exit();
    }
}

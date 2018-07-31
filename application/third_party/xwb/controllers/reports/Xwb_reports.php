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
 * Main controller for Reports
 */
class Xwb_reports extends XWB_purchasing_base
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
        $this->load->model('reports/Reports_model', 'Reports');
    }
    

    /**
     * All users view
     *
     * @return mixed
     */
    public function request()
    {
        $this->redirectUser(array('admin','budget','board'));
        
        
        $data['campus'] = $this->db->get('branches')->result();
        $data['dept'] = $this->db->get('department')->result();
        $data['sy'] = $this->getSY();
        $data['page_title'] = 'Request Reports'; //title of the page
        $data['page_script'] = 'request_reports'; // script filename of the page
        $this->renderPage('reports/request_reports', $data);
    }


    /**
     * All staff request report view
     *
     * @return mixed
     */
    public function staff_request()
    {
        $this->redirectUser(array('budget','canvasser','auditor','property','admin','board'));
        
        $data['campus'] = $this->db->get('branches')->result();
        $data['dept'] = $this->db->get('department')->result();
        $data['sy'] = $this->getSY();
        $data['page_title'] = 'Staff Request Reports'; //title of the page
        $data['page_script'] = 'staff_request_reports'; // script filename of the page
        $this->renderPage('reports/staff_request_reports', $data);
    }


    /**
     * Generate reports request data for datatables
     *
     * @return json
     */
    public function getRequestReports()
    {
        $this->redirectUser(array('admin','budget','board'));
        $gets = $this->input->get();
        $branches = $gets['branches'];
        $department = $gets['department'];
        $year = $gets['year'];
        $month = $gets['month'];
        $sy = "";//$gets['sy'];

        $this->Reports->getRequestReports($branches, $department, $year, $month, $sy);
        $args = array($branches,$department,$year,$month,$sy);
        $recordsTotal = $this->db->count_all_results();
        $recordsFiltered = $this->Reports->countFiltered('getRequestReports', $args);
        

        $this->Reports->getRequestReports($branches, $department, $year, $month, $sy);
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
                                        number_format($v->total_amount, 2, '.', ','),
                                        ucwords($v->full_name),
                                        $v->department,
                                        $v->campus,
                                        $this->xwb_purchasing->getStatus('request', $v->status),
                                        date('Y', strtotime($v->date_created)),
                                        date('F j, g:i a', strtotime($v->date_created)),
                                        );
            }
        }
        $data['draw'] = $this->input->get('draw');
        $data['recordsTotal'] = $recordsTotal;
        $data['recordsFiltered'] = $recordsFiltered;
        echo $this->xwbJsonEncode($data);
    }



    /**
     * Generate reports staff request data for datatables
     *
     * @return json
     */
    public function getStaffRequestReports()
    {
        $this->redirectUser(array('budget','canvasser','auditor','property','admin','board'));
        $gets = $this->input->get();

        $this->load->model('user/User_model', 'User');
        $user_id = $this->log_user_data->user_id;
        $user = $this->User->getUser($user_id)->row();
        $branches = $user->branch_id;
        $department = $user->department_id;

        $year = $gets['year'];
        $month = $gets['month'];
        $sy = "";// $gets['sy'];

        $this->Reports->getRequestReports($branches, $department, $year, $month, $sy);
        $args = array($branches,$department,$year,$month,$sy);
        $recordsTotal = $this->db->count_all_results();
        $recordsFiltered = $this->Reports->countFiltered('getRequestReports', $args);
        

        $this->Reports->getRequestReports($branches, $department, $year, $month, $sy);
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
                                        number_format($v->total_amount, 2, '.', ','),
                                        ucwords($v->full_name),
                                        $v->department,
                                        $v->campus,
                                        $this->xwb_purchasing->getStatus('request', $v->status),
                                        date('Y', strtotime($v->date_created)),
                                        date('F j, g:i a', strtotime($v->date_created)),
                                        );
            }
        }
        $data['draw'] = $this->input->get('draw');
        $data['recordsTotal'] = $recordsTotal;
        $data['recordsFiltered'] = $recordsFiltered;
        echo $this->xwbJsonEncode($data);
    }

    /**
     * View items report
     *
     * @return type
     */
    public function items()
    {
        $this->redirectUser(array('admin','budget','board'));
        
        
        $data['campus'] = $this->db->get('branches')->result();
        $data['dept'] = $this->db->get('department')->result();
        $data['sy'] = $this->getSY();
        $data['page_title'] = 'Items Reports'; //title of the page
        $data['page_script'] = 'items_reports'; // script filename of the page
        $this->renderPage('reports/items_reports', $data);
    }


    /**
     * View staff items report
     *
     * @return type
     */
    public function staff_items()
    {
        $this->redirectUser(array('budget','canvasser','auditor','property','admin','board'));
        
        
        $data['campus'] = $this->db->get('branches')->result();
        $data['dept'] = $this->db->get('department')->result();
        $data['sy'] = $this->getSY();
        $data['page_title'] = 'Staff Item Reports'; //title of the page
        $data['page_script'] = 'staff_items_reports'; // script filename of the page
        $this->renderPage('reports/staff_items_reports', $data);
    }


    public function getSY()
    {
        $startYear = 1980;
        $yearEnd =date('Y');
        $sy = [];
        while ($yearEnd >= $startYear) {
            $sy[] = date('Y', strtotime($yearEnd.'-06-01')).'-'.date('Y', strtotime($yearEnd.'-06-01 +1 year'));
            $yearEnd--;
        }
        return $sy;
    }

    /**
     * Generate item reports data for datatables
     *
     * @return json
     */
    public function getItemReports()
    {
        $this->redirectUser(array('admin','budget','board'));
        $gets = $this->input->get();
        $branches = $gets['branches'];
        $department = $gets['department'];
        $year = $gets['year'];
        $month = $gets['month'];
        $sy = ""; //$gets['sy'];


        $this->Reports->getItemReports($branches, $department, $year, $month, $sy);
        $args = array($branches,$department,$year,$month,$sy);
        $recordsTotal = $this->db->count_all_results();
        $recordsFiltered = $this->Reports->countFiltered('getItemReports', $args);
        

        $this->Reports->getItemReports($branches, $department, $year, $month, $sy);
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
                                        $v->product_name,
                                        $v->quantity,
                                        number_format($v->unit_price, 2, '.', ','),
                                        number_format($v->unit_price * $v->quantity, 2, '.', ','),
                                        $v->supplier,
                                        ucwords($v->full_name),
                                        $v->campus,
                                        $v->department,
                                        date('Y', strtotime($v->date_created)),
                                        date('F j, g:i a', strtotime($v->date_created)),
                                        );
            }
        }
        $data['draw'] = $this->input->get('draw');
        $data['recordsTotal'] = $recordsTotal;
        $data['recordsFiltered'] = $recordsFiltered;
        echo $this->xwbJsonEncode($data);
    }


    /**
     * Generate staff item reports data for datatables
     *
     * @return json
     */
    public function getStaffItemReports()
    {
        $this->redirectUser(array('budget','canvasser','auditor','property','admin','board'));
        $gets = $this->input->get();
        $this->load->model('user/User_model', 'User');
        $user_id = $this->log_user_data->user_id;
        $user = $this->User->getUser($user_id)->row();

        $branches = $user->branch_id;
        $department = $user->department_id;
        $year = $gets['year'];
        $month = $gets['month'];
        $sy = ""; //$gets['sy'];


        $this->Reports->getItemReports($branches, $department, $year, $month, $sy);
        $args = array($branches,$department,$year,$month,$sy);
        $recordsTotal = $this->db->count_all_results();
        $recordsFiltered = $this->Reports->countFiltered('getItemReports', $args);
        

        $this->Reports->getItemReports($branches, $department, $year, $month, $sy);
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
                                        $v->product_name,
                                        $v->quantity,
                                        number_format($v->unit_price, 2, '.', ','),
                                        number_format($v->unit_price * $v->quantity, 2, '.', ','),
                                        $v->supplier,
                                        ucwords($v->full_name),
                                        $v->campus,
                                        $v->department,
                                        date('Y', strtotime($v->date_created)),
                                        date('F j, g:i a', strtotime($v->date_created)),
                                        );
            }
        }
        $data['draw'] = $this->input->get('draw');
        $data['recordsTotal'] = $recordsTotal;
        $data['recordsFiltered'] = $recordsFiltered;
        echo $this->xwbJsonEncode($data);
    }


    /**
     * View staff purchase order report
     *
     * @return mixed
     */
    public function po()
    {
        $this->redirectUser(array('admin','budget','board'));
        
        
        $data['campus'] = $this->db->get('branches')->result();
        $data['dept'] = $this->db->get('department')->result();
        $data['sy'] = $this->getSY();
        $data['page_title'] = 'PO Reports'; //title of the page
        $data['page_script'] = 'po_reports'; // script filename of the page
        $this->renderPage('reports/po_reports', $data);
    }


    /**
     * View staff purchase order report
     *
     * @return mixed
     */
    public function staff_po()
    {
        $this->redirectUser(array('budget','canvasser','auditor','property','admin','board'));
        
        
        $data['campus'] = $this->db->get('branches')->result();
        $data['dept'] = $this->db->get('department')->result();
        $data['sy'] = $this->getSY();
        $data['page_title'] = 'Staff PO Reports'; //title of the page
        $data['page_script'] = 'staff_po_reports'; // script filename of the page
        $this->renderPage('reports/staff_po_reports', $data);
    }


    /**
     * Purchase order datatable report
     *
     * @return json
     */
    public function getPOReports()
    {
        $this->redirectUser(array('admin','budget','board'));
        $gets = $this->input->get();
        $branches = $gets['branches'];
        $department = $gets['department'];
        $year = $gets['year'];
        $month = $gets['month'];
        $sy = ""; //$gets['sy'];

        $this->Reports->getPOReports($branches, $department, $year, $month, $sy);

        $args = array($branches,$department,$year,$month,$sy);
        $recordsTotal = $this->db->count_all_results();
        $recordsFiltered = $this->Reports->countFiltered('getPOReports', $args);
        

        $this->Reports->getPOReports($branches, $department, $year, $month, $sy);
        if ($this->input->get('length') != -1) {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
        }
        $r = $this->db->get();

        $data['data'] = array();
        
        if ($r->num_rows()>0) {
            foreach ($r->result() as $key => $v) {
                $data['data'][] = array(
                                        $v->po_num,
                                        $v->pr_number,
                                        $v->request_name,
                                        $v->vendor_name,
                                        getPaymentTerm($v->payment_terms),
                                        $v->warranty_condition,
                                        number_format($v->total_amount, 2, '.', ','),
                                        ucwords($v->requisitioner),
                                        ucwords($v->certified_by),
                                        date('F j, g:i a', strtotime($v->date_updated)),
                                        ucwords($v->preparedby),
                                        );
            }
        }
        $data['draw'] = $this->input->get('draw');
        $data['recordsTotal'] = $recordsTotal;
        $data['recordsFiltered'] = $recordsFiltered;
        echo $this->xwbJsonEncode($data);
    }


    /**
     * Staff Purchase order datatable report
     *
     * @return json
     */
    public function getStaffPOReports()
    {
        $this->redirectUser(array('budget','canvasser','auditor','property','admin','board'));
        $gets = $this->input->get();

        $this->load->model('user/User_model', 'User');
        $user_id = $this->log_user_data->user_id;
        $user = $this->User->getUser($user_id)->row();

        $branches = $user->branch_id;
        $department = $user->department_id;

        $year = $gets['year'];
        $month = $gets['month'];
        $sy = ""; //$gets['sy'];

        $this->Reports->getPOReports($branches, $department, $year, $month, $sy);

        $args = array($branches,$department,$year,$month,$sy);
        $recordsTotal = $this->db->count_all_results();
        $recordsFiltered = $this->Reports->countFiltered('getPOReports', $args);
        

        $this->Reports->getPOReports($branches, $department, $year, $month, $sy);
        if ($this->input->get('length') != -1) {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
        }
        $r = $this->db->get();

        $data['data'] = array();
        
        if ($r->num_rows()>0) {
            foreach ($r->result() as $key => $v) {
                $data['data'][] = array(
                                        $v->po_num,
                                        $v->pr_number,
                                        $v->request_name,
                                        $v->vendor_name,
                                        getPaymentTerm($v->payment_terms),
                                        $v->warranty_condition,
                                        number_format($v->total_amount, 2, '.', ','),
                                        ucwords($v->requisitioner),
                                        ucwords($v->certified_by),
                                        date('F j, g:i a', strtotime($v->date_updated)),
                                        ucwords($v->preparedby),
                                        );
            }
        }
        $data['draw'] = $this->input->get('draw');
        $data['recordsTotal'] = $recordsTotal;
        $data['recordsFiltered'] = $recordsFiltered;
        echo $this->xwbJsonEncode($data);
    }
}

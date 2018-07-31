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
 * Main controller for History
 */
class Xwb_history extends XWB_purchasing_base
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
        $this->load->model('history/History_model', 'History');
    }
    

    /**
     * History view
     *
     * @return mixed
     */
    public function index()
    {
        $this->redirectUser(array('admin','board'));
        

        $data['page_title'] = 'History'; //title of the page
        $data['page_script'] = 'history'; // script filename of the page
        $this->renderPage('history/history', $data);
    }


    public function getHistory()
    {

        $this->History->getHistories();

        $recordsTotal = $this->db->count_all_results();
        $recordsFiltered = $this->History->countFiltered('getHistories');
        

        $this->History->getHistories();
        if ($this->input->get('length') != -1) {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
        }
        $h = $this->db->get();


        $data['data'] = array();

        if ($h->num_rows()>0) {
            foreach ($h->result() as $key => $v) {
                $data['data'][] = array(
                                        $v->id,
                                        $v->full_name,
                                        $v->request_name,
                                        $v->description,
                                        //$this->xwb_purchasing->getStatus('request',$v->status),
                                        date('F j, Y, g:i a', strtotime($v->date_created)),
                                        );
            }
        }
        $data['draw'] = $this->input->get('draw');
        $data['recordsTotal'] = $recordsTotal;
        $data['recordsFiltered'] = $recordsFiltered;
        echo $this->xwbJsonEncode($data);
    }
}

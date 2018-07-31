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
 * Main controller for member
 */
class Xwb_members extends XWB_purchasing_base
{

    /**
     * Run parent construct
     *
     * @return Null
     */
    public function __construct()
    {
        parent::__construct();
    }
    

    /**
     * All users view
     *
     * @return mixed
     */
    public function index()
    {
        $this->redirectUser(array('members'));
        $this->load->model('request/Request_model', 'Request');
        $this->load->model('admin/Admin_model', 'Admin');

        $groups = $this->db->get('groups');
        $data['groups'] = $groups->result();

        $user_id = $this->log_user_data->user_id;
        $request = $this->Request->getRequestListByUser($user_id)->result();

        $gauge_data = $this->Admin->generateGaugeData($request);

        $data['progress_label'] = $this->Admin->progressLabel();
        $data['gauge_data'] = $gauge_data;
        
        $data['page_title'] = 'Member'; //title of the page
        $data['page_script'] = 'member'; // script filename of the page user.js
        $this->renderPage('members/member', $data);
    }
}

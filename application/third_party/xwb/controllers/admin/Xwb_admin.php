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
 * Main controller for admin module
 */
class Xwb_admin extends XWB_purchasing_base
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
     * View admin dashboard
     *
     * @return mixed
     */
    public function index()
    {

        $this->redirectUser(array('admin','board'));
        $this->load->model('admin/Admin_model', 'Admin');
        $this->load->model('request/Request_model', 'Request');
        $this->load->model('approval/Approval_model', 'Approval');
        $this->load->model('canvasser/Canvasser_model', 'Canvasser');
        $this->load->model('budget/Budget_model', 'Budget');

        

        $data['new_requests'] = $this->Request->getNewRequest();
        $data['new_reqapproval'] = $this->Approval->getNewRequestApproval();
        $data['to_canvass'] = $this->Canvasser->getRequestToCanvass();
        $data['req_budget_to_apprvoe'] = $this->Budget->getReqToApprove();
        
        $graph_data  = $this->getOngoingGrapthData();

        $data['graph_data'] = $this->convertToJavaScriptDate($graph_data['graph_data']);
        $data['graph_users'] = $graph_data['graph_user'];
        
        $data['page_title'] = 'Dashboard';
        $data['page_script'] = 'dashboard';
        $this->renderPage('admin/dashboard', $data);
    }

    /**
     * for approval view of head department admin page
     *
     * @return mixed
     */
    public function for_approval()
    {
        $this->redirectUser(array('admin','board'));
        $data['page_title'] = 'Request for approval'; //title of the page
        $data['page_script'] = 'for_approval'; // script filename of the page user.js
        $this->renderPage('approval/for_approval', $data);
    }


    /**
     * Ongoing Request graph data
     *
     * @return array
     */
    public function getOngoingGrapthData()
    {
        $this->redirectUser();
        $start_date = date("Y-m-d", strtotime("-1 week"));
        $end_date = date("Y-m-d");
        while (strtotime($start_date) <= strtotime($end_date)) {
            $dates_graph[] = $start_date;
            $start_date = date("Y-m-d", strtotime("+1 day", strtotime($start_date)));
        }

        $ongoing = $this->Request->getOngoingRequest()->result();

        $db_dates = [];
        $db_user = [];
        foreach ($ongoing as $key => $value) {
            $db_dates[] = date('Y-m-d', strtotime($value->date_created));
            $db_user[$value->user_id] = array($value->full_name,$value->department,$value->branch);
        }


        $data['graph_user'] = $db_user;
        $data['graph_data'] = [];
        foreach ($dates_graph as $key => $value) {
            $counts = array_count_values($db_dates);
            if (array_key_exists($value, $counts)) {
                $counts = $counts[$value];
            } else {
                $counts = 0;
            }
            $data['graph_data'][] = array($value, $counts);
        }

        return $data;
    }


    /**
     * Convert graph date key to javascript date
     *
     * @param type|array $data
     * @return array
     */
    public function convertToJavaScriptDate($data = array())
    {
        $this->redirectUser();
        $graph_data = [];
        foreach ($data as $key => $value) {
            $graph_data[date('D M d Y H:i:s', strtotime($value[0]))." +0000"] = $value[1];
        }

        return $graph_data;
    }



    /**
     * Test email
     *
     * @return string
     */
    public function mail()
    {
        $this->redirectUser();
        $this->load->library('email');
        $user_id = $this->log_user_data->user_id;

        /* sending email notification */
        $shortcodes = array(
                'user_to' => $user_id,
                'message' => 'message test',
                //'request_id' => 8,
            );

        $this->xwb_purchasing->setShortCodes($shortcodes);

        /* sending email notification */
        $condition = $this->xwb_purchasing->getShortCodes();

        $msg = $this->xwb_purchasing->getMessage('to_admin_review');
        $message = do_shortcode($msg['message'], $condition);
        $site_title = $this->config->item('site_title');
        $res = $this->xwb_purchasing->sendmail('jack@extremewebevolution.com', $msg['subject'], $message, $condition['email_from'], $site_title, $msg['subject']);
        var_dump($this->email->print_debugger(array('headers')));
        pre($res);
    }

    public function test()
    {
        $this->load->model('User_model', 'User');
        $users = $this->User->getUsersByGroup('admin')->result();
        pre($users);
    }
}

<?php (defined('BASEPATH')) or exit('No direct script access allowed');
/**
 * Base and Public functions is located here.
 *
 * XWB Purchasing
 *
 * @package     XWB Purchasing
 * @author      Jay-r Simpron
 * @copyright   Copyright (c) 2017, Jay-r Simpron
 */


class XWB_purchasing_base extends Xwb_custom_controller
{

    /**
     * User Types constant
     * This is the reference for group or role of this purchasing package
     *
     * 1 - Administrator
     * 2 - General User
     * 3 - Canvasser
     * 4 - Budget
     * 5 - Auditor
     * 6 - Board
     * 7 - Property Department
     */

    protected $user_types = [
        '1' => 'admin',
        '2' => 'members',
        '3' => 'canvasser',
        '4' => 'budget',
        '5' => 'auditor',
        '6' => 'board',
        '7' => 'property'
    ];



    /**
     * Status names - This will be use in the settings->status
     *
     * You can add status in this system by adding an array below
     *
     */
   
    protected $status_names = [
        'request',
        'item_approval',
        'req_approval',
        'canvass',
        'budget',
        'board',
        'purchase_order',
        'item',
        'property'
    ];



    /**
     * Status Types - This will be the status color
     */
    protected $status_types = [
        'default',
        'primary',
        'success',
        'info',
        'warning',
        'danger',
    ];

    /**
     * $user_id and $group_ids must be set whatever the authentication you are using.
     */
    protected $user_id;
    protected $group_ids;

    public $log_user_data; // user data storage
    public $user_notification; // users notification


    /**
     * Temporary only. soon to be move in config
     *
     * @var array
     */
    protected $member_status_action = array(5,6,10,17,19);
    protected $auditor_status_action = array(0,3);
    protected $board_status_action = array(0,3);

    public $statusItemsDenied = [10,6,17,19];
    
    /**
     * Template views
     *
     * Array key of the USER_TYPES will be asign on
     * each view template
     */
    private $roles_view = array(
        'administrators' => [1,6],
        'managers' => [3,4,5,7],
        'members' => [2],
    );

    /**
     * Assign your group id to this package user group
     *
     * The key of this array is the value of USER_TYPES
     *
     * The value of this array is the group ID
     * from the authentication you are using
     *
     */
    private $user_type_asignment = array(
            'admin'                 => [1],
            'members'               => [8],
            'canvasser'             => [7],
            'budget'                => [6],
            'auditor'               => [5],
            'board'                 => [4],
            'property'              => [3]
        );


    /**
     * Class constructor
     */
    public function __construct()
    {
      
        parent::__construct();
        $this->load->library('xwb_purchasing');
        $this->config->load('xwb_purchasing');
        $this->log_user_data = $this->getLogin($this->user_id);
        $this->user_notification = $this->xwb_purchasing->getNotification($this->log_user_data->group_name, $this->log_user_data->department_head);
    }


    /**
     * Redirecting user to each appropriate page
     *
     * @param type|array $roles [The allowed user types]
     * @return mixed
     */
    public function redirectUser($roles = null)
    {
        if ($roles == null) {
            $roles = $this->user_types;
        }

        /* get all the user type for current page */
        $user_type_keys = [];
        foreach ($roles as $rV) {
            if ($key = array_search($rV, $this->user_types)) {
                $user_type_keys[] = $key;
            }
        }


        /* Check if users role found on the current page */
        $found = false;
        foreach ($user_type_keys as $value) {
            foreach ($this->group_ids as $gK => $gV) {
                if (array_search($gV, $this->user_type_asignment[$this->user_types[$value]]) !== false) {
                    $found = true;
                }
            }
        }

        // check for the head users
        if (in_array('head', $roles) && $this->log_user_data->department_head == 0) {
            $found = false;
        }

        /* redirect to appropriate page if not found */
        if ($found === false) {
            $group1 = $this->group_ids[0];
            $role = null;
            foreach ($this->user_type_asignment as $key => $value) {
                if (in_array($group1, $value)) {
                    $role = $key;
                    break;
                }
            }


            if (is_null($role)) {
                redirect($this->config->item('login_url'), 'refresh');
            } else {
                redirect($role, 'refresh');
            }
        }
    }

    /**
     * Render page template
     *
     * @param string $view
     * @param array|null $data
     * @param type|bool $render
     * @return mixed
     */
    public function renderPage($view, $data = null, $render = false)
    {

        $template = 'members';
        foreach ($this->roles_view['members'] as $key => $value) {
            if (in_array($value, $this->getUsersRole())) {
                $template = 'members';
            }
        }

        foreach ($this->roles_view['managers'] as $key => $value) {
            if (in_array($value, $this->getUsersRole())) {
                $template = 'managers';
            }
        }

        foreach ($this->roles_view['administrators'] as $key => $value) {
            if (in_array($value, $this->getUsersRole())) {
                $template = 'administrators';
            }
        }

        $data['notification'] = $this->user_notification;
        $data['current_user'] = $this->log_user_data;
        $data['csrf_name'] = $this->security->get_csrf_token_name();
        $data['csrf_hash'] = $this->security->get_csrf_hash();

        $data['topnav'] = $this->load->view('template/'.$template.'/view_topnav', $data, true);
        $data['leftpane'] = $this->load->view('template/'.$template.'/view_leftpane', $data, true);
        $data['body'] = $this->load->view($view, $data, true);
        $data['footer'] = $this->load->view('template/'.$template.'/view_footer', '', true);
        return $this->load->view('template/'.$template.'/view_html', $data, $render);
    }



    /**
     * Get package role for the current user
     *
     * @param  array  $group_ids [Users Group IDs]
     * @return [array]            [Package Roles]
     */
    protected function getUsersRole($group_ids = array())
    {
        $group_ids = (count($group_ids)==0?$this->group_ids:$group_ids);

        $user_type_asignment = $this->user_type_asignment;
        $users_role = [];
        foreach ($user_type_asignment as $key => $value) {
            foreach ($group_ids as $gK => $gV) {
                if (in_array($gV, $value)) {
                    $users_role[] = array_search($key, $this->user_types);
                }
            }
        }

        $users_role = array_unique($users_role);
        return $users_role;
    }

    /**
     * Get the users profile info
     *
     * @param  [int] $user_id [User ID]
     * @return [Object]          [Users Info]
     */
    protected function getLogin($user_id = null)
    {
        $this->load->model('user/User_model', 'Users');

        $user_id = ($user_id==null?$this->user_id:$user_id);
        return $this->Users->getUser($user_id)->row();
    }

    /**
     * Process Json Encode
     *
     * @param  array  $data
     * @return json
     */
    public function xwbJsonEncode($data = array())
    {
        $data = (array)$data;
        $data['csrf_name'] = $this->security->get_csrf_token_name();

        $data['csrf_hash'] = $this->security->get_csrf_hash();

        return json_encode($data);
    }
}

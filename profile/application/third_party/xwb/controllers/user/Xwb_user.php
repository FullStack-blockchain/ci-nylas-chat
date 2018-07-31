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
 * Main controller for Users module
 */
class Xwb_user extends XWB_purchasing_base
{

    /**
     * Run parent construct
     *
     * @return Null
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('profile/Profile_model', 'Profile');
        $this->load->model('department/department_model', 'Department');
        $this->load->model('branch/Branch_model', 'Branch');
    }
    

    /**
     * Users List view
     *
     * @return mixed
     */
    public function index()
    {
        $this->redirectUser(array('admin','board'));
        $groups = $this->db->get('groups');
        $data['groups'] = $groups->result();
        $data['departments'] = $this->Department->getDept()->result();
        $data['branches'] = $this->Branch->getBranch()->result();
        $data['page_title'] = 'Users'; //title of the page
        $data['page_script'] = 'users'; // script filename of the page user.js
        $this->renderPage('user/users', $data);
    }

    /**
     * Get all users to datatable
     *
     * @return json
     */
    public function getUsers()
    {
        $this->redirectUser(array('admin','board'));
        $u = $this->Users->getUsers();
        
        $data['data'] = array();

        if ($u->num_rows()>0) {
            foreach ($u->result() as $key => $v) {
                $de_acive_text = ($v->active == 1?'Deactivate':'Activate');

                $delete_user = "";
                if ($this->log_user_data->group_name == 'super_admin') {
                    $delete_user = '<a href="javascript:;" onClick="xwb.deleteUser('.$v->id.');" class="btn btn-xs btn-danger '.($this->log_user_data->user_id==$v->id?'disabled':'').'">Delete</a>';
                }
                $data['data'][] = array(
                                        $v->id,
                                        $v->branch,
                                        $v->department,
                                        ucwords($v->first_name),
                                        ucwords($v->last_name),
                                        $v->email,
                                        $v->description,
                                        '<a href="" class="btn btn-xs btn-warning xwb-edit-user" data-id="'.$v->id.'">Edit</a>
										'.$delete_user.'
										<a href="" data-id="'.$v->id.'" data-active="'.$v->active.'" class="xwb-activate-user btn btn-xs btn-success '.($this->log_user_data->user_id==$v->id?'disabled':'').'">'.$de_acive_text.'</a>
										<a href="" class="btn btn-xs btn-warning xwb-change-pass" data-id="'.$v->id.'">Change Password</a>',
                                        );
            }
        }
        echo $this->xwbJsonEncode($data);
    }


    /**
     * Get single user
     *
     * @return json
     */
    public function editUser()
    {
        $this->redirectUser(array('admin','board'));
        $u_id = $this->input->post('u_id');
        $u = $this->Users->getUser($u_id)->row();
        echo $this->xwbJsonEncode($u);
        exit();
    }


    /**
     * Update user
     *
     * @return json
     */
    public function updateUser()
    {
        $this->load->library('form_validation');
        $this->redirectUser(array('admin','board'));

        $user_id = $this->input->post('id');

        $user = $this->Users->getUser($user_id)->row();
        $email_unique = "";

        if ($user->email != $this->input->post('email')) {
            $email_unique = "|is_unique[users.email]";
        }
        

        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('branch', 'Branch', 'required');
        $this->form_validation->set_rules('department', 'Department', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email'.$email_unique);
                
        
        if ($this->form_validation->run() == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
        } else {
            $data = array(
                    //'first_name' => $this->input->post('first_name'),
                    //'last_name' => $this->input->post('last_name'),
                    'email' => $this->input->post('email'),
                    'phone' => $this->input->post('phone')
            );

            $group_data = array(
                            'group_id' => $this->input->post('group')
                            );

            $profile_data = array(
                            'first_name' => $this->input->post('first_name'),
                            'last_name' => $this->input->post('last_name'),
                            'department' => $this->input->post('department'),
                            'branch' => $this->input->post('branch'),
                            'role' => $this->input->post('group'),
                            'head' => ($this->input->post('department_head') == null?0:1),
                            'phone_number' => $this->input->post('phone'),
                            );

            $this->Users->updateUser($user_id, $data);
            $this->Profile->updateUserProfile($user_id, $profile_data);
            $this->Users->updateUserGroup($user_id, $group_data);



            $data['status'] = true;
            $data['message'] = 'User has been successfully updated.';
        }

        echo $this->xwbJsonEncode($data);
        exit();
    }


    /**
     * Add user methon
     *
     * @return json
     */
    public function addUser()
    {
        $this->load->library('form_validation');
        $this->redirectUser(array('admin','board'));
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');
        $this->form_validation->set_rules('cpassword', 'Password Confirmation', 'trim|required|matches[password]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('branch', 'Branch', 'required');
        $this->form_validation->set_rules('department', 'Department', 'required');
        
        if ($this->form_validation->run() == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
        } else {
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $additional_data = array(
                    //'first_name' => $this->input->post('first_name'),
                    //'last_name' => $this->input->post('last_name'),
                    'username' => $this->input->post('email'),
                    'email' => $email,
                    'password' => $password
            );

            $user_id = $this->ion_auth->register($email, $password, $email, $additional_data, array($this->input->post('group')));
            if (is_array($user_id)) {
                $user_id = $user_id['id'];
            }
            $profile_data = array(
                    'user_id' => $user_id,
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'nick_name' => $this->input->post('email'),
                    'phone_number' => $this->input->post('phone_number'),
                    'branch' => $this->input->post('branch'),
                    'department' => $this->input->post('department'),
                    'role' => $this->input->post('group'),
                    'head' => ($this->input->post('department_head') == null?0:1),
                );
            $this->db->insert('users_profile', $profile_data);

            $data['status'] = true;
            $data['message'] = 'User has been successfully added.';
        }

        echo $this->xwbJsonEncode($data);
        exit();
    }


    /**
     * Delete user
     *
     * @return json
     */
    public function deleteUser()
    {
        $this->redirectUser(array('admin','board'));
        $user_id = $this->input->post('user_id');
        $res = $this->db->delete('users', array('id' => $user_id));
        $res = $this->db->delete('users_groups', array('user_id' => $user_id));
        
        if ($res) {
            $data['status'] = true;
            $data['message'] = "User has been deleted";
        } else {
            $data['status'] = false;
            $data['message'] = "Error deleting record, please contact the system programmer";
        }

        echo $this->xwbJsonEncode($data);
        exit();
    }



    /**
     * Activate User
     *
     * @return json
     */
    public function activateUser()
    {
        $this->redirectUser(array('admin','board'));
        $user_id = $this->input->post('user_id');
        $this->db->where('id', $user_id);
        $data = array('active' => 1);
        $res = $this->db->update('users', $data);
        if ($res) {
            $data['status'] = true;
            $data['message'] = 'User has been successfully activated.';
        } else {
            $data['status'] = false;
            $data['message'] = "Error updating record, please contact the system programmer";
        }
        echo $this->xwbJsonEncode($data);
    }


    /**
     * Deactivate User
     *
     * @return json
     */
    public function deactivateUser()
    {
        $this->redirectUser(array('admin','board'));
        $user_id = $this->input->post('user_id');
        $this->db->where('id', $user_id);
        $data = array('active' => 0);
        $res = $this->db->update('users', $data);
        if ($res) {
            $data['status'] = true;
            $data['message'] = 'User has been successfully deactivated.';
        } else {
            $data['status'] = false;
            $data['message'] = "Error updating record, please contact the system programmer";
        }
        echo $this->xwbJsonEncode($data);
    }


    /**
     * Change password
     *
     * @return json
     */
    public function changePass()
    {
        $this->load->library('form_validation');
        $post = $this->input->post();

        $this->form_validation->set_rules('old_password', 'Old Password', 'required');
        $this->form_validation->set_rules('new_password', 'New Password', 'required');
        $this->form_validation->set_rules('confirm_password', 'Password Confirmation', 'required|matches[new_password]');

        

        if ($this->form_validation->run($this) == false) {
            $data = array(
                            'status' => false,
                            'message' => validation_errors()
                            );
        } else {
            $user_id = $this->input->post('id');
            $this->db->select('email,id,password');
            $this->db->from('users');
            $this->db->where('id', $user_id);
            $query = $this->db->get();
            $user = $query->row();

            $change = $this->ion_auth->change_password($user->email, $this->input->post('old_password'), $this->input->post('new_password'));

            if ($change) {
                $data = array(
                            'status' => true,
                            'message' => 'Password Changed'
                            );
            } else {
                $data = array(
                'status' => false,
                'message' => $this->ion_auth->errors()
                );
            }
        }
        echo $this->xwbJsonEncode($data);
    }

    public function test()
    {
    }
}

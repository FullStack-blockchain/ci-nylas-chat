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
 * Main controller for profile
 */
class Xwb_profile extends XWB_purchasing_base
{


    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
    }

    /**
     * View Profile
     *
     * @return mixed
     */
    public function index()
    {
        $data['page_title'] = 'User Profile';
        $this->renderPage('profile/profile', $data);
    }

    /**
     * Use to view profile image
     *
     * @return [type] [description]
     */
    public function view_image()
    {
        return $this->xwb_purchasing->view_image();
    }


    /**
     * Update profile
     *
     * @return void
     */
    public function updateProfile()
    {
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('nick_name', 'Nick Name', 'required');
        $this->form_validation->set_rules('phone_number', 'Phone Number', 'required');
        $upload_path = APPPATH.'third_party/xwb/images/profile_images/';

        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        if ($this->form_validation->run() == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
            $this->session->set_flashdata('errors', validation_errors());
            redirect('profile');
        } else {
            $posts = $this->input->post();
            $user_id = $this->user_id;
            $db_data = array();
            if ($_FILES['profile_pic']['size'] != 0) {
                $config['upload_path']          = $upload_path;
                $config['allowed_types']        = 'gif|jpg|png';
                $config['max_size']             = 2000;
                $config['max_width']            = 1000;
                $config['max_height']           = 1000;

                $this->load->library('upload', $config);

                if (! $this->upload->do_upload('profile_pic')) {
                    $error = array('error' => $this->upload->display_errors());
                    $data['status'] = false;
                    $data['message'] = $error['error'];
                    $this->session->set_flashdata('errors', $this->upload->display_errors());
                    redirect('profile');
                } else {
                    $data = array('upload_data' => $this->upload->data());
                    $db_data['picture_path'] = $data['upload_data']['full_path'];
                    $db_data['picture_mime'] = $data['upload_data']['file_type'];
                }
            }

            
            $db_data['first_name'] = $posts['first_name'];
            $db_data['last_name'] = $posts['last_name'];
            $db_data['nick_name'] = $posts['nick_name'];
            $db_data['phone_number'] = $posts['phone_number'];

            $this->db->where('user_id', $user_id);
            $res = $this->db->get('users_profile');
            if ($res->num_rows()==1) {
                $this->db->where('user_id', $user_id);
                $this->db->update('users_profile', $db_data);
            } else {
                $db_data['user_id'] = $user_id;
                $this->db->insert('users_profile', $db_data);
            }

            $this->session->set_flashdata('success', "Profile has been successfully updated");
            redirect('profile');
        }
    }
}

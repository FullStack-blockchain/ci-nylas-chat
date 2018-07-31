<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Chatting extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('chatting_model');
        $this->load->helper(array('form','url'));
        $this->load->library('form_validation');
    }
    
    public function index()
    {
    	if(!$this->session->userdata('chat_user_id')){
    		$this->session->set_userdata('chat_user_id', '13');
		}

		$user_id = $this->session->userdata('chat_user_id');
		$data['users'] = $this->chatting_model->getuserdata($user_id);

    	$data['conversation'] = $this->chatting_model->getConversationById($user_id);

    	$data['contactlists'] = $this->chatting_model->getcontactlists($user_id);

        $this->load->view('admin/chatting/index', $data);
    }
    public function createconversation($user_two)
    {
    	$user_id = $this->session->userdata('chat_user_id');
    	$c_id = $this->chatting_model->createconversation($user_id, $user_two);
    	
    	redirect(site_url().'admin/chatting');
    }

    public function getchatconverlist()
    {
    	$user_id = $this->session->userdata('chat_user_id');
    	$conversation = $this->chatting_model->getConversationById($user_id);

		echo json_encode($conversation);
    }

    public function getconverlist()
    {
    	$c_id = $_POST['c_id'];
    	$user_id = $this->session->userdata('chat_user_id');

    	$res = $this->chatting_model->getConversationList($c_id, $user_id);
    	$user_id = $this->session->userdata('chat_user_id');

    	$html = '';
    	foreach ($res as $row) {
    		if($row['user_id_fk'] == $user_id)
    		{
    			$html .= '<div class="row flex-nowrap message-row user p-4">
			            <img class="avatar mr-4" src="../assets/images/avatars/profile.jpg" alt="John Doe">
			            <div class="bubble">
			                <div class="message">'.$row['reply'].'</div>
			                <div class="time text-muted text-right mt-2">'.gmdate("d M y h:m:s", $row['time']).'</div>
			            </div>
			        </div>';
    		}
    		else
    		{
    			$html .= '<div class="row flex-nowrap message-row contact p-4">
			            <img class="avatar mr-4" src="../assets/images/avatars/profile.jpg" />
			            <div class="bubble">
			                <div class="message">'.$row['reply'].'</div>
			                <div class="time text-muted text-right mt-2">'.gmdate("d M y h:m:s", $row['time']).'</div>
			            </div>
			        </div>';
    		}
		}

    	echo json_encode($html);
    }

    public function reply_msg()
    {
    	$obj['reply'] = $_POST['reply_txt_msg'];
		$obj['c_id_fk'] = $_POST['reply_c_id'];
		$obj['user_id_fk'] = $this->session->userdata('chat_user_id');
		$obj['time'] = time();
		$obj['ip'] = $_SERVER['REMOTE_ADDR'];

		$this->chatting_model->insertConversationReply($obj);

		$html = '<div class="row flex-nowrap message-row user p-4">
		            <img class="avatar mr-4" src="../assets/images/avatars/profile.jpg" alt="John Doe">
		            <div class="bubble">
		                <div class="message">'.$obj['reply'].'</div>
		                <div class="time text-muted text-right mt-2">'.gmdate("d M y h:m:s", $obj['time']).'</div>
		            </div>
		        </div>';

		echo json_encode($html);
    }
}

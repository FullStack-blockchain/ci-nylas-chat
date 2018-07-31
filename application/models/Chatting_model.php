<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Chatting_model extends CRM_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getConversationById($user_one = 12)
    {
    	$sql = "SELECT u.user_id,c.c_id,u.username,u.email,c.time,
    				(SELECT count(R.cr_id) FROM tbl_chat_conversation_reply R WHERE R.c_id_fk= c.c_id and R.unread = '1' and R.user_id_fk <> '$user_one') unread
				FROM tbl_chat_conversation c, tbl_chat_users u
				WHERE CASE 
					WHEN c.user_one = '$user_one'
					THEN c.user_two = u.user_id
					WHEN c.user_two = '$user_one'
					THEN c.user_one= u.user_id
					END 
					AND (
					c.user_one ='$user_one'
					OR c.user_two ='$user_one'
					)
				Order by c.c_id DESC Limit 20";

		$result = $query = $this->db->query($sql)->result_array();
		return $result;
    }

    public function createconversation($user_one, $user_two)
    {
		if($user_one != $user_two)
		{
			$sql = "SELECT c_id FROM tbl_chat_conversation WHERE (user_one='$user_one' and user_two='$user_two') or (user_one='$user_two' and user_two='$user_one') ";
			$time=time();
			$ip=$_SERVER['REMOTE_ADDR'];

			if($this->db->query($sql)->num_rows() == 0) 
			{ 
				$query = "INSERT INTO tbl_chat_conversation (user_one,user_two,ip,time) VALUES ('$user_one','$user_two','$ip','$time')";
				$this->db->query($query);
				$q = "SELECT c_id FROM tbl_chat_conversation WHERE user_one='$user_one' ORDER BY c_id DESC limit 1";
				$v = $this->db->query($q)->row();
				return $v->c_id;
			}
			else
			{
				$v = $this->db->query($sql)->row();
				return $v->c_id;
			}
		}
		return false;
    }

    public function getuserdata($user_id)
    {
    	$table_name = 'tbl_chat_users';
    	$obj['user_id'] = $user_id;

    	$this->db->where($obj);
		$user = $this->db->get($table_name)->row_array();
		return $user;
    }

    public function getcontactlists($user_id)
    {
    	$table_name = 'tbl_chat_users';
    	$obj['user_id != '] = $user_id;

    	$this->db->where($obj);
		$user = $this->db->get($table_name)->result_array();
		return $user;
    }

    public function getConversationList($c_id = 2, $user_id = 13)
    {
    	$sql = "UPDATE tbl_chat_conversation_reply R SET R.unread = '0' WHERE R.c_id_fk='$c_id' and R.user_id_fk <> '$user_id'";
    	$query = $this->db->query($sql);

    	$sql = "SELECT R.cr_id,R.time,R.reply,R.user_id_fk FROM tbl_chat_conversation_reply R WHERE R.c_id_fk='$c_id' ORDER BY R.cr_id ASC LIMIT 20";
    	$result = $query = $this->db->query($sql)->result_array();
		return $result;
    }

    public function insertConversationReply($obj)
    {
    	$this->db->insert('tbl_chat_conversation_reply', $obj);
    }

    public function adduser($user_name, $password, $email)
    {
    	$table_name = 'tbl_chat_users';

    	$obj['username'] = $user_name;
    	$obj['password'] = $password;
    	$obj['email'] = $email;
    	$this->db->where($obj);
		$user = $this->db->get($table_name)->row();

		if (!$user) 
		{
			$this->db->insert($table_name, $obj);
			$this->db->where($obj);
			$user = $this->db->get($table_name)->row();
		}
		//var_dump($user); exit;
		$this->session->set_userdata('chat_user_id', $user->user_id);
    }
}

<?php  if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * XWB Purchasing
 * @package     XWB Purchasing
 * @author      Jay-r Simpron
 * @copyright   Copyright (c) 2017, Jay-r Simpron
 */


/**
 * This is the library class for XWB Purchasing
 */
class Xwb_purchasing extends XWB_purchasing_base
{


    /**
     * Class method constructor
     */
    public function __construct()
    {
        $this->xwb =& get_instance();
        $this->xwb->load->helper('xwb');
    }




    /**
     * View image in php header
     *
     * @return mixed
     */
    public function view_image()
    {
        ini_set('gd.jpeg_ignore_warning', true);
        $path = $this->xwb->input->get("path");
     
        if (@getimagesize($path) === false) {
            $path = APPPATH.'third_party/xwb/images/default.jpg';
        }
     
       

     //getting extension type (jpg, png, etc)
        $type = explode(".", $path);
        $ext = strtolower($type[sizeof($type)-1]);
        $ext = (!in_array($ext, array("jpeg","png","gif"))) ? "jpeg" : $ext;
     //get image size
        $size = getimagesize($path);
        $width = $size[0];
        $height = $size[1];
       
        //get source image
        $func = "imagecreatefrom".$ext;
        $source = $func($path);
        
        //setting default values
      
        $new_width = $width;
        $new_height = $height;
        $k_w = 1;
        $k_h = 1;
        $dst_x =0;
        $dst_y =0;
        $src_x =0;
        $src_y =0;
       
        //selecting width and height

        if ($this->xwb->input->get("width")==null && $this->xwb->input->get("height")==null) {
            $new_height = $height;
            $new_width = $width;
        } else if ($this->xwb->input->get("width")==null) {
            $new_height = $this->xwb->input->get("height");
            $new_width = ($this->xwb->input->get("height"))/$height;
        } else if ($this->xwb->input->get("height")==null) {
            $new_height = ($height*$this->xwb->input->get("width"))/$width;
            $new_width = $this->xwb->input->get("width");
        } else {
            $new_width = $this->xwb->input->get("width");
            $new_height = $this->xwb->input->get("height");
        }
     
        //secelcting_offsets
          
        if ($new_width>$width) {//by width
            
            $dst_x = ($new_width-$width)/2;
        }
        if ($new_height>$height) {//by height
            
            $dst_y = ($new_height-$height)/2;
        }
        if ($new_width<$width || $new_height<$height) {
            $k_w = $new_width/$width;
            $k_h = $new_height/$height;
              
            if ($new_height>$height) {
                $src_x  = ($width-$new_width)/2;
            } else if ($new_width>$width) {
                $src_y  = ($height-$new_height)/2;
            } else {
                if ($k_h>$k_w) {
                    $src_x = round(($width-($new_width/$k_h))/2);
                } else {
                    $src_y = round(($height-($new_height/$k_w))/2);
                }
            }
        }
        $output = imagecreatetruecolor($new_width, $new_height);
        
        //to preserve PNG transparency
        if ($ext == "png") {
            //saving all full alpha channel information
            imagesavealpha($output, true);
            //setting completely transparent color
            $transparent = imagecolorallocatealpha($output, 0, 0, 0, 127);
            //filling created image with transparent color
            imagefill($output, 0, 0, $transparent);
        }

        imagecopyresampled(
            $output,
            $source,
            $dst_x,
            $dst_y,
            $src_x,
            $src_y,
            $new_width-2*$dst_x,
            $new_height-2*$dst_y,
            $width-2*$src_x,
            $height-2*$src_y
        );
//free resources
        ImageDestroy($source);
//output image
        header('Content-Disposition: inline');
        header('Content-Type: image/'.$ext);
        $func = "image".$ext;
        $func($output);
      
        //free resources
        ImageDestroy($output);
        exit();
    }


    /**
     * Set shourtcode values
     *
     * [name_from]
     * [email_from]
     * [name_to]
     * [email_to]
     * [request_number]
     * [request_name]
     * [date_needed]
     * [message]
     * [purchase_number]
     * [po_num]
     * [item_number]
     * [item_name]
     *
     * @param array $args shortcode name and its value
     */
    public function setShortCodes($args = array())
    {
        $this->sc = new stdClass();
        foreach ($args as $key => $value) {
            $this->sc->{$key} = $value;
        }
        return $this->sc;
    }

    /**
     * Get available shortcodes
     *
     * @return object [shortcodes]
     */
    public function getShortCodes()
    {

        $this->xwb->load->model('user/User_model', 'User');
        $userfrom = $this->xwb->User->getUser($this->xwb->session->userdata('user_id'))->row();
/* Default shortcode values */
        $condition['name_from'] = ucwords($userfrom->first_name." ".$userfrom->last_name);
        $condition['email_from'] = $userfrom->email;
        $condition['name_to'] = "";
        $condition['email_to'] = "";
        $condition['request_number'] = "";
        $condition['request_name'] = "";
        $condition['date_needed'] = "";
        $condition['message'] = "";
        $condition['purchase_number'] = "";
        $condition['po_num'] = "";
        $condition['po_number'] = "";
        $condition['item_number'] = "";
        $condition['item_name'] = "";
/**
         * Assign shortcode value
         *
         */

        if (property_exists($this->sc, 'user_to')) {
            $userto = $this->xwb->User->getUser($this->sc->user_to)->row();
            $condition['name_to'] = ucwords($userto->first_name." ".$userto->last_name);
            ;
            $condition['email_to'] = $userto->email;
        }
       
        if (property_exists($this->sc, 'user_from')) {
            $userfrom = $this->xwb->User->getUser($this->sc->user_from)->row();
            $condition['name_from'] = ucwords($userfrom->first_name." ".$userfrom->last_name);
            ;
            $condition['email_from'] = $userfrom->email;
        }
       
        if (property_exists($this->sc, 'request_id')) {
            $this->xwb->load->model('request/Request_model', 'Request');
            $r = $this->xwb->Request->getRequest($this->sc->request_id)->row();
            $condition['request_number'] = sprintf('PR-%08d', $r->id);
            $condition['request_name'] = $r->request_name;
            $condition['date_needed'] = ($r->date_needed==null?"":date("F j, Y", strtotime($r->date_needed)));
        }

        if (property_exists($this->sc, 'message')) {
            $condition['message'] = $this->sc->message;
        }
       
        if (property_exists($this->sc, 'po')) {
            $this->xwb->load->model('purchase_order/Purchase_order_model', 'PO');
            $po = $this->xwb->PO->getPO($this->sc->po)->row();
            $condition['po_number'] = $po->po_num;
            $condition['purchase_number'] = $po->po_num;
            $condition['po_num'] = $po->po_num;
        }
        
        if (property_exists($this->sc, 'item')) {
            $this->xwb->load->model('request/Request_model', 'Request');
            $i = $this->xwb->Request->getItem($this->sc->item)->row();
            $condition['item_number'] = $i->id;
            $condition['item_name'] = $i->product_name;
        }

        return $condition;
    }


    /**
     * Get Email Message for the process
     *
     * @param string $process_key
     * @return string
     */
    public function getMessage($process_key)
    {
        $e = $this->xwb->db->get_where('emails', array('process_key'=>$process_key));
        if ($e->num_rows()>0) {
            $data['message'] = $e->row()->message;
            $data['subject'] = $e->row()->subject;
        } else {
            $data['message'] = "";
            $data['subject'] = "";
        }
        return $data;
    }


    /**
     * Sending Email method
     *
     * @param string $email_to
     * @param type|string $subject
     * @param type|string $message
     * @param type|string $email_from
     * @param type|string $from_name
     * @param type|string $email_title
     * @param type|array $other
     * @return boolean
     */
    public function sendmail(
        $email_to,
        $subject = "CI Purchasing",
        $message = '',
        $email_from = "",
        $from_name = "",
        $email_title = "CI Purchasing Email",
        $other = array()
    ) {
        if (getConfig('email_notification')==1) {
            $this->xwb->load->library('email');
            if ($email_from == "") {
                $email_from = $this->xwb->config->item('email_from', 'xwb_purchasing');
            }

            if ($from_name == "") {
                $from_name = $this->xwb->config->item('site_title', 'xwb_purchasing');
            }

            $subject = $email_title;
            $body['email_title'] = $email_title;
            $body['message'] = $message;
            $message_body = $this->xwb->load->view('emails/template', $body, true);
            $this->xwb->email->from($email_from, $from_name);
            $this->xwb->email->to($email_to);
            $this->xwb->email->subject($subject);
            $this->xwb->email->message($message_body);
            return $this->xwb->email->send(false);
        } else {
            return true;
        }
    }


    /**
     * Record transaction history
     *
     * @param type|string $table
     * @param int|null $ref_id
     * @param type|string $name
     * @param type|string $description
     * @param int|null $user_id
     * @param type|string $action
     * @param type|string $old
     * @param type|string $new
     * @param int $status
     * @return int
     */
    public function addHistory(
        $table = '',
        $ref_id = null,
        $name = '',
        $description = '',
        $user_id = null,
        $action = 'create',
        $old = "",
        $new = "",
        $status = 1
    ) {
    
        $db_data = array(
                'db_table' => $table,
                'ref_id' => $ref_id,
                'name' => $name,
                'description' => $description,
                'user_id' => $user_id,
                'action' => $action,
                'old_value' => $old,
                'new_value' => $new,
                'status' => $status,
            );
        $this->xwb->db->insert('transaction_history', $db_data);
        return $this->xwb->db->insert_id();
    }

    /**
     * Get notification number
     *
     * @param  string $user_type [User Type]
     * @param  integer $head [If user is head in the department]
     * @return array
     */
    public function getNotification($user_type = '', $head = 0)
    {
        $req = [];
        if ($user_type == 'admin' || $user_type == 'board') {
            $this->xwb->load->model('request/Request_model', 'Request');
            $this->xwb->load->model('board/Board_model', 'Board');
            $req['admin_req_action'] = $this->xwb->Request->countRequestAction($this->xwb->user_id)->num_rows();
            $req['admin_board_action'] = $this->xwb->Board->countBoardAction($this->xwb->user_id)->num_rows();
        } elseif ($user_type == 'members') {
            $this->xwb->load->model('request/Request_model', 'Request');
            $req['member_req_action'] = $this->xwb->Request->countMemberRequestAction($this->xwb->user_id)->num_rows();
        } else {
            $this->xwb->load->model('request/Request_model', 'Request');
            $req['my_req_action'] = $this->xwb->Request->countMemberRequestAction($this->xwb->user_id)->num_rows();
            switch ($user_type) {
                case 'canvasser':
                    $this->xwb->load->model('canvasser/Canvasser_model', 'Canvasser');
                    $req['canvass_action'] = $this->xwb->Canvasser->countCanvassAction($this->xwb->user_id)->num_rows();

                    break;
             
                case 'budget':
                    $this->xwb->load->model('budget/Budget_model', 'Budget');
                    $req['budget_action'] = $this->xwb->Budget->countBudgetAction($this->xwb->user_id)->num_rows();

                    break;
                case 'auditor':
                    $this->xwb->load->model('auditor/Auditor_model', 'Auditor');
                    $req['auditor_action'] = $this->xwb->Auditor->countAuditorAction($this->xwb->user_id)->num_rows();

                    break;
                case 'property':
                    $this->xwb->load->model('property/Property_model', 'Property');
                    $req['property_action'] = $this->xwb->Property->countPropertyAction($this->xwb->user_id)->num_rows();

                    break;
            }
        }

        if ($head==1) {
            $this->xwb->load->model('request/Request_model', 'Request');
            $req['head_approval_action'] = $this->xwb->Request->countHeadAction($this->xwb->user_id)->num_rows();
        }

        return (object)$req;
    }

    /**
     * Get status label
     *
     * @param  string  $statusName   [Status Name]
     * @param  integer $statusNumber [Status Number]
     * @return string
     */
    public function getStatus($statusName = '', $statusNumber = 0)
    {
        $this->xwb->load->model('settings/Settings_model', 'Settings');
        $res = $this->xwb->db->get_where('status', array('status_name' => $statusName, 'status_number'=>$statusNumber));
        $count = $res->num_rows();
        $res = $res->row();
        if ($count === 0) {
            $status = '<label class="label label-danger">'.$statusName.' Status '.$statusNumber.' not found</label>';
        } else {
            $status = '<label class="label label-'.$res->status_type.'">'.$res->status_text.'</label>';
        }
        return $status;
    }
}

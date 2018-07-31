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
 * Main controller for Attachment
 */
class Xwb_attachment extends XWB_purchasing_base
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
        $this->load->model('attachment/Attachment_model', 'Attachment');
    }
    

    /**
     * Get attachment for datatable
     *
     * @return json
     */
    public function getAttachment()
    {
        $po_id = $this->input->get('po_id');
        $a = $this->Attachment->getPOAttachment($po_id);
        
        $data['data'] = [];
        if ($a->num_rows()>0) {
            foreach ($a->result() as $k => $v) {
                $remove = "";

                if ($v->user_id==$this->session->user_id) {
                    $remove = '<a href="javascript:;" onClick="xwb.deleteReqAttachment('.$v->id.')" class="btn btn-xs btn-danger">Remove</a>';
                }
                
                $data['data'][] = array(
                        $v->id,
                        $v->file_name,
                        '<a target="_blank" href="'.base_url('attachment/dl_attachment/'.$v->id).'" class="btn btn-xs btn-info">Download</a>
							'.$remove,
                    );
            }
        }

        echo $this->xwbJsonEncode($data);
    }


    /**
     * Download file attachment
     *
     *
     * @return void
     */
    public function dl_attachment($id)
    {
        $this->load->helper('download');
        $a = $this->Attachment->getAttachment($id)->row();
        force_download($a->full_path, null);
    }

    /**
     * Upload attachment on each product/item requested
     *
     * @return json
     */
    public function addAttachment()
    {
        $this->form_validation->set_rules('po_id', 'Item', 'required');
        

        if ($this->form_validation->run() == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
        } else {
            $po_id = $this->input->post('po_id');
            $config['upload_path']          = 'uploads/';
            $config['allowed_types']        = 'gif|jpg|png|zip|zipx|rar|7z|pdf|doc|docx|txt|odt';
            $this->load->library('upload', $config);

            if (! $this->upload->do_upload('attachment')) {
                $error = array('error' => $this->upload->display_errors());
                $data['status'] = false;
                $data['message'] = $error['error'];
            } else {
                $upload_data = $this->upload->data();
                $attachment_data = array(
                    'po_id' => $po_id,
                    'file_name' => $upload_data['file_name'],
                    'file_type' => $upload_data['file_type'],
                    'file_path' => $upload_data['file_path'],
                    'full_path' => $upload_data['full_path'],
                    'raw_name' => $upload_data['raw_name'],
                    'orig_name' => $upload_data['orig_name'],
                    'client_name' => $upload_data['client_name'],
                    'file_ext' => $upload_data['file_ext'],
                    'file_size' => $upload_data['file_size'],
                    'is_image' => $upload_data['is_image'],
                    'image_width' => $upload_data['image_width'],
                    'image_height' => $upload_data['image_height'],
                    'image_type' => $upload_data['image_type'],
                    'image_size_str' => $upload_data['image_size_str'],
                );
                 
                $res = $this->db->insert('attachment', $attachment_data)             ;
                if ($res) {
                    $data['status'] = true;
                    $data['message'] = 'Attachment uploaded';
                } else {
                    $data['status'] = false;
                    $data['message'] = 'Failed to upload file, please contact the system programmer';
                }
            }
        }

        
        echo $this->xwbJsonEncode($data);
    }

    /**
     * Remove file attachment
     *
     * @return json
     */
    public function removeAttachment()
    {
        $this->load->helper('file');

        $attachment_id = $this->input->post('attachment_id');
        $a = $this->Attachment->getAttachment($attachment_id)->row();

        $this->db->where('id', $attachment_id);
        $this->db->delete('attachment');
        
        $res = unlink($a->full_path);
        if ($res) {
            $data['status'] = true;
            $data['message'] = 'Attachment Removed';
        } else {
            $data['status'] = false;
            $data['message'] = 'Error deleting file, Please contact the system programmer';
        }
        echo $this->xwbJsonEncode($data);
    }
}

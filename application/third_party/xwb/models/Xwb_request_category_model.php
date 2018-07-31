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
 * Main model class for Request category
 */
class Xwb_request_category_model extends Xwb_custom_model
{
    /**
     * Run parent constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all request category
     *
     * @return array
     */
    public function getReqCat()
    {
        return $this->db->get('request_category');
    }


    /**
     * Delete request category
     * @return array
     */
    public function deleteReqCat()
    {
        $id = $this->input->post('id');
        $this->db->where('id', $id);
        $this->db->delete('request_category');
        return $this->db->affected_rows();
    }
}

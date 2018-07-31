<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * XWB Purchasing
 *
 * @package     XWB Purchasing
 * @author      Jay-r Simpron
 * @copyright   Copyright (c) 2017, Jay-r Simpron
 */


class Xwb_branch_model extends Xwb_custom_model
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
     * Get all branches
     *
     * @return array
     */
    public function getBranch()
    {
        return $this->db->get('branches');
    }


    /**
     * Delete Branch
     * @return array
     */
    public function deleteBranch()
    {
        $id = $this->input->post('id');
        $this->db->where('id', $id);
        $this->db->delete('branches');
        return $this->db->affected_rows();
    }
}

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
 * Main model class for Department
 */
class Xwb_department_model extends Xwb_custom_model
{

    /**
     * Run parent constructor
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Get all department
     *
     * @return array
     */
    public function getDept()
    {
        return $this->db->get('department');
    }


    /**
     * Delete department
     * @return array
     */
    public function deleteDept()
    {
        $id = $this->input->post('id');
        $this->db->where('id', $id);
        $this->db->delete('department');
        return $this->db->affected_rows();
    }
}

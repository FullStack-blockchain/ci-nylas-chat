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
 * Main Supplier class for Users
 */
class Xwb_supplier_model extends Xwb_custom_model
{
    /**
     * Run parent constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all suppliers
     *
     * @return object
     */
    public function getSuppliers()
    {
        return $this->db->get('supplier');
    }

    /**
     * Get single supplier
     *
     * @param int $supplier_id
     * @return json
     */
    public function getSupplier($supplier_id)
    {
        return $this->db->get_where('supplier', array('id' => $supplier_id));
    }
}

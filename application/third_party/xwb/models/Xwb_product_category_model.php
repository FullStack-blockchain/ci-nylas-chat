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
 * Main model class for Product category
 */
class Xwb_product_category_model extends Xwb_custom_model
{

    /**
     * Run parent constructor
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Get product Categories
     *
     * @return object
     */
    public function getProdCat()
    {
        return $this->db->get('product_categories');
    }


    

    /**
     * Get category
     *
     * @param int $category_id
     * @return object
     */
    public function getCategory($category_id)
    {
        return $this->db->get_where('product_categories', array('id'=>$category_id));
    }


    /**
     * Get parent categories
     *
     * @return object
     */
    public function getParentCat()
    {
        return $this->db->get_where('product_categories', array('parent'=>0));
    }


    /**
     * Delete product category
     *
     * @param int $cat_id
     * @return object
     */
    public function deleteProdCat($cat_id)
    {
        $this->db->where('id', $cat_id);
        $this->db->delete('product_categories');
        return $this->db->affected_rows();
    }


    /**
     * Get Child Categories
     *
     * @param int $cat_id
     * @return object
     */
    public function getChildCat($cat_id)
    {
        
        return $this->db->get_where('product_categories', array('parent'=>$cat_id));
    }
}

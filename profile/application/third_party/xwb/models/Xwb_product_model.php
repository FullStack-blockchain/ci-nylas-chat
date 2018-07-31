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
 * Main model class for Product
 */
class Xwb_product_model extends Xwb_custom_model
{

    /**
     * Run parent constructor
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Get product
     *
     * @return object
     */
    public function getProducts()
    {
        $this->db->select("p.id, p.product_name, pc.id as category, pc.name AS cat_name, pc.description cat_description, pi.description prod_info")
            ->from("products p")
            ->join('product_categories pc', 'p.product_category = pc.id', 'left')
            ->join('product_information pi', 'p.id = pi.product_id', 'left');
        return $this->db->get();
    }



    /**
     * Get Product
     *
     * @param int $product_id
     * @return object
     */
    public function getProduct($product_id)
    {
        $this->db->select("p.id, p.product_name, pc.id as category,pc.name AS cat_name, pc.description cat_description, pi.description prod_info")
            ->from("products p")
            ->join('product_categories pc', 'p.product_category = pc.id', 'left')
            ->join('product_information pi', 'p.id = pi.product_id', 'left')
            ->where("p.id", $product_id);
            return $this->db->get();
    }



    /**
     * Delete Product
     *
     * @param int $product_id
     * @return object
     */
    public function deleteProduct($product_id)
    {
        $this->db->where('id', $product_id);
        $this->db->delete('products');
        return $this->db->affected_rows();
    }


    /**
     * Update product
     *
     * @param int $product_id
     * @param array $data
     * @return object
     */
    public function updateProduct($product_id, $data = array())
    {
        $this->db->where('id', $product_id);
        return $this->db->update('products', $data);
    }


    /**
     * Update product information
     *
     * @param int $product_id
     * @param array $data
     * @return object
     */
    public function updateProductInfo($product_id, $data = array())
    {
        $this->db->where('product_id', $product_id);
        return $this->db->update('product_information', $data);
    }
}

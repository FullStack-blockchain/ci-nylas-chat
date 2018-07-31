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
 * Main controller for product
 */
class Xwb_product extends XWB_purchasing_base
{

    /**
     * Run parent construct
     *
     * @return Null
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('product/Product_model', 'Product');
    }


    /**
     * Product view
     *
     * @return mixed
     */
    public function index()
    {
        $this->redirectUser(array('admin','board'));
        $this->load->model('product_category/Product_category_model', 'Prodcat');

        $data['categories'] = $this->Prodcat->getParentCat()->result();

        $data['page_title'] = 'Products'; //title of the page
        $data['page_script'] = 'products'; // script filename of the page user.js
        $this->renderPage('product/products', $data);
    }


    /**
     * Get product
     *
     * @return json
     */
    public function getProducts()
    {
        $p = $this->Product->getProducts();
        
        $data['data'] = array();

        if ($p->num_rows()>0) {
            foreach ($p->result() as $key => $v) {
                $data['data'][] = array(
                                        $v->id,
                                        $v->product_name,
                                        $v->cat_description,
                                        '<a href="" class="btn btn-xs btn-warning xwb-edit-product" data-product="'.$v->id.'">Edit</a>
										<a href="" class="btn btn-xs btn-danger xwb-del-product" data-product="'.$v->id.'">Delete</a>
										',
                                        );
            }
        }
        echo $this->xwbJsonEncode($data);
    }



    /**
     * Add Product
     *
     * @return json
     */
    public function addProduct()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('product_name', 'Product Name', 'required');
        $this->form_validation->set_rules('description', 'Product Description', 'required');

        if ($this->form_validation->run() == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
        } else {
            $posts = $this->input->post();


            $data_products = array(
                        'product_name' => $posts['product_name'],
                        'product_category' => $posts['category'],
                            );
            $this->db->insert('products', $data_products);
            $prod_id = $this->db->insert_id();

            $data_prod_info = array(
                        'product_id' => $prod_id,
                        'description' => $posts['description'],
                            );
            $this->db->insert('product_information', $data_prod_info);


            $data['status'] = true;
            $data['message'] = 'Product has been successfully added.';
        }

        echo $this->xwbJsonEncode($data);
        exit();
    }


    /**
     * Delete Product
     *
     * @return json
     */
    public function deleteProduct()
    {
        $product_id = $this->input->post('product_id');
        $rows = $this->Product->deleteProduct($product_id);
        if ($rows > 0) {
            $data['status'] = true;
            $data['message'] = "Product has been deleted";
        } else {
            $data['status'] = false;
            $data['message'] = "Error deleting record, please contact the system programmer";
        }

        echo $this->xwbJsonEncode($data);
        exit();
    }


    /**
     * Edit product
     *
     * @return json
     */
    public function editProduct()
    {
        $product_id = $this->input->post('product_id');
        $res = $this->Product->getProduct($product_id)->row();
        echo $this->xwbJsonEncode($res);
        exit();
    }



    /**
     * Update Product
     *
     * @return json
     */
    public function updateProduct()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('product_name', 'Product Name', 'required');
        $this->form_validation->set_rules('prod_info', 'Product Description', 'required');

        if ($this->form_validation->run() == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
        } else {
            $posts = $this->input->post();
            $data_products = array(
                        'product_name' => $posts['product_name'],
                        'product_category' => $posts['category'],
                    );
            $this->Product->updateProduct($posts['id'], $data_products);

            $data_prod_info = array(
                        'description' => $posts['prod_info'],
                            );
            $this->Product->updateProductInfo($posts['id'], $data_prod_info);
            

            $data['status'] = true;
            $data['message'] = 'Product has been successfully updated.';
        }

        echo $this->xwbJsonEncode($data);
        exit();
    }
}

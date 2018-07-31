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
 * Main model class for Property item
 */
class Xwb_property_item_model extends Xwb_custom_model
{

    /**
     * Run parent constructor
     */
    public function __construct()
    {
        parent::__construct();
    }



    /**
     * Show received items per property
     *
     * @param int $property_id
     * @return object
     */
    public function getReceivedItems($property_id)
    {
        $this->db->select('pi.*,pc.description as product_category, poi.product_name, poi.product_description, poi.quantity')
                ->from('property_item pi')
                ->join('po_items poi', 'pi.item_id = poi.id', 'left')
                ->join('products pd', 'poi.product_id = pd.id', 'left')
                ->join('product_categories pc', 'pd.product_category = pc.id', 'left')
                ->where('pi.property_id', $property_id);
        return $this->db->get();
    }
}

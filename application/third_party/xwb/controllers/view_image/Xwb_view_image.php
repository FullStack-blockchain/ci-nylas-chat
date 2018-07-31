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
 * View Image Controller
 *
 * You can override all the parent method here.
 */
class Xwb_view_image extends CI_Controller
{

    public function index()
    {
        $this->load->library('xwb_purchasing');
        return $this->xwb_purchasing->view_image();
    }
}

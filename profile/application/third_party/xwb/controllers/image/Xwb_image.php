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
 * Main controller for Image
 */
class Xwb_image extends XWB_purchasing_base
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
     * View dynamic image
     *
     * @return mixed
     */
    public function index()
    {
        $this->view_image();
    }



    /**
     * View image with dynamic size
     *
     * @return type
     */
    public function view_image()
    {
        ini_set('gd.jpeg_ignore_warning', true);
        $path = $this->input->get("path");
     
        if (@getimagesize($path) === false) {
            $path = APPPATH.'third_party/xwb/images/default.jpg';
        }
     
       

     //getting extension type (jpg, png, etc)
        $type = explode(".", $path);
        $ext = strtolower($type[sizeof($type)-1]);
        $ext = (!in_array($ext, array("jpeg","png","gif"))) ? "jpeg" : $ext;
     //get image size
        $size = getimagesize($path);
        $width = $size[0];
        $height = $size[1];
       
        //get source image
        $func = "imagecreatefrom".$ext;
        $source = $func($path);
        
        //setting default values
      
        $new_width = $width;
        $new_height = $height;
        $k_w = 1;
        $k_h = 1;
        $dst_x =0;
        $dst_y =0;
        $src_x =0;
        $src_y =0;
       
        //selecting width and height

        if ($this->input->get("width")==null && $this->input->get("height")==null) {
            $new_height = $height;
            $new_width = $width;
        } else if ($this->input->get("width")==null) {
            $new_height = $this->input->get("height");
            $new_width = ($this->input->get("height"))/$height;
        } else if ($this->input->get("height")==null) {
            $new_height = ($height*$this->input->get("width"))/$width;
            $new_width = $this->input->get("width");
        } else {
            $new_width = $this->input->get("width");
            $new_height = $this->input->get("height");
        }
     
        //secelcting_offsets
          
        if ($new_width>$width) {//by width
            
            $dst_x = ($new_width-$width)/2;
        }
        if ($new_height>$height) {//by height
            
            $dst_y = ($new_height-$height)/2;
        }
        if ($new_width<$width || $new_height<$height) {
            $k_w = $new_width/$width;
            $k_h = $new_height/$height;
              
            if ($new_height>$height) {
                $src_x  = ($width-$new_width)/2;
            } else if ($new_width>$width) {
                $src_y  = ($height-$new_height)/2;
            } else {
                if ($k_h>$k_w) {
                    $src_x = round(($width-($new_width/$k_h))/2);
                } else {
                    $src_y = round(($height-($new_height/$k_w))/2);
                }
            }
        }
        $output = imagecreatetruecolor($new_width, $new_height);
        
        //to preserve PNG transparency
        if ($ext == "png") {
            //saving all full alpha channel information
            imagesavealpha($output, true);
            //setting completely transparent color
            $transparent = imagecolorallocatealpha($output, 0, 0, 0, 127);
            //filling created image with transparent color
            imagefill($output, 0, 0, $transparent);
        }

        imagecopyresampled(
            $output,
            $source,
            $dst_x,
            $dst_y,
            $src_x,
            $src_y,
            $new_width-2*$dst_x,
            $new_height-2*$dst_y,
            $width-2*$src_x,
            $height-2*$src_y
        );
//free resources
        ImageDestroy($source);
//output image
        header('Content-Disposition: inline');
        header('Content-Type: image/'.$ext);
        $func = "image".$ext;
        $func($output);
      
        //free resources
        ImageDestroy($output);
        exit();
    }
}

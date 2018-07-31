<?php (defined('BASEPATH')) or exit('No direct script access allowed');

/* load the MX_Loader class */
require APPPATH."third_party/MX/Loader.php";

class MY_Loader extends MX_Loader
{
    function __construct()
    {
        if (! file_exists($file_path = APPPATH.'config/'.ENVIRONMENT.'/database.php')
            && ! file_exists($file_path = APPPATH.'config/database.php')) {
            show_error('The configuration file database.php does not exist.');
        }
        include($file_path);
        if (empty($db['default']['username']) ||
            empty($db['default']['hostname']) ||
            empty($db['default']['database'])) {
            $root=(isset($_SERVER['HTTPS']) ? "https://" : "http://").$_SERVER['HTTP_HOST'];
            $root.= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
            show_error('Configure the database. <br /><a href="'.$root.'install">Click here to install the system</a>');
        }
    }
}

<?php (defined('BASEPATH')) or exit('No direct script access allowed');

/* load the MX_Router class */
require APPPATH."third_party/MX/Router.php";


class MY_Router extends MX_Router
{
    public function __construct()
    {
        parent::__construct();
        /* Load controller and model class */
        spl_autoload_register(function ($className) {
            $controller_dir = 'controllers/'.$this->class;

            if (!class_exists($className)) {
                /* Autoload Base Controller */
                if (file_exists(APPPATH . 'third_party/xwb/libraries/'.$className.'.php')) {
                    require_once APPPATH . 'third_party/xwb/libraries/'.$className.'.php';
                }

                /* Autoload Custom Model and Custom Controller*/
                if (file_exists(APPPATH . 'third_party/xwb/'.$className.'.php')) {
                    require_once APPPATH . 'third_party/xwb/'.$className.'.php';
                }

                /* autoload controller */
                if (file_exists(APPPATH . 'third_party/xwb/'.$controller_dir.'/'.$className.'.php')) {
                    require_once APPPATH . 'third_party/xwb/'.$controller_dir.'/'.$className.'.php';
                }

                /* Autoload Models */
                if (substr($className, -5, 5)=='model') {
                    if (file_exists(APPPATH . 'third_party/xwb/models/'.$className.'.php')) {
                        require_once APPPATH . 'third_party/xwb/models/'.$className.'.php';
                    }
                }
            }
        });
    }
}

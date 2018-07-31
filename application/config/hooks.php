<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

$hook['pre_system'] = array(
        'class'    => 'BadBotBlock',
        'function' => 'init',
        'filename' => 'BadBotBlock.php',
        'filepath' => 'hooks',
        'params'   => array()
);

$hook['pre_system'] = array(
        'class'    => 'BadUserAgentBlock',
        'function' => 'init',
        'filename' => 'BadUserAgentBlock.php',
        'filepath' => 'hooks',
        'params'   => array()
);

if (file_exists(APPPATH.'config/my_hooks.php')) {
    include_once(APPPATH.'config/my_hooks.php');
}

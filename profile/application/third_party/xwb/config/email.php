<?php
defined('BASEPATH') or exit('No direct script access allowed');


//gmail
$config['protocol']    = 'smtp';
$config['smtp_host']    = 'ssl://smtp.gmail.com';
$config['smtp_port']    = '465';
$config['smtp_timeout'] = '7';
$config['smtp_user']    = 'ssj.simpron.test@gmail.com';
$config['smtp_pass']    = 'ioglrghuwjzhlshd';
$config['charset']    = 'utf-8';
$config['newline']    = "\r\n";
$config['mailtype'] = 'html'; // or html
$config['validation'] = true; // bool whether to validate email or not



/*$config['useragent'] = "CodeIgniter";
$config['protocol'] = 'sendmail';
$config['mailpath'] = '/usr/sbin/sendmail';
$config['charset'] = 'iso-8859-1';
$config['wordwrap'] = TRUE;*/

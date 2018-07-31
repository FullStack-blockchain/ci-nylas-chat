<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Nylas\Nylas;

class nylas_mail extends Admin_controller
{
	protected $app_id = '29ls5zw0xjckd97d3umfnrt43';
	protected $app_secret = '93dx1sybvjt9ott2pcjnzpz9l';
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $client = new Nylas($this->app_id, $this->app_secret);
		$redirect_url = 'http://localhost/index.php/nylas_mail/auth_callback';
		$get_auth_url = $client->createAuthURL($redirect_url);

		redirect($get_auth_url);
    }
}

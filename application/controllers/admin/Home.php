
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends Admin_controller
{
    //private $not_importable_clients_fields;

    //public $pdf_zip;

    public function __construct()
    {
        parent::__construct();
      //  $this->not_importable_clients_fields = do_action('not_importable_clients_fields', ['userid', 'id', 'is_primary', 'password', 'datecreated', 'last_ip', 'last_login', 'last_password_change', 'active', 'new_pass_key', 'new_pass_key_requested', 'leadid', 'default_currency', 'profile_image', 'default_language', 'direction', 'show_primary_contact', 'invoice_emails', 'estimate_emails', 'project_emails', 'task_emails', 'contract_emails', 'credit_note_emails', 'ticket_emails', 'addedfrom','registration_confirmed', 'last_active_time']);
        // last_active_time is from Chattr plugin, causing issue
    }

    /* List all clients */
    public function index()
    {
        $data = 'Welcome';
        $this->load->view('admin/home/manage', $data);
    }

}

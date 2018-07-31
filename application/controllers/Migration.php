<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration extends CRM_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function make()
    {
        $this->load->config('migration');
        if ($this->config->item('migration_enabled') == true) {
            if (!$this->input->get('old_base_url')) {
                echo '<h1>You need to pass old base url in the url like: ' . site_url('migration/make?old_base_url=http://myoldbaseurl.com/') . '</h1>';
                die;
            }

            $old_url = $this->input->get('old_base_url');
            $new_url = $this->config->item('base_url');
            if (!endsWith($old_url, '/')) {
                $old_url = $old_url . '/';
            }

            $tables = [
                [
                    'table' => 'tblnotifications',
                    'field' => 'description',
                ],
                [
                    'table' => 'tblnotifications',
                    'field' => 'additional_data',
                ],
                [
                    'table' => 'tblnotes',
                    'field' => 'description',
                ],
                [
                    'table' => 'tblemailtemplates',
                    'field' => 'message',
                ],
                [
                    'table' => 'tblposts',
                    'field' => 'content',
                ],
                [
                    'table' => 'tblpostcomments',
                    'field' => 'content',
                ],
                [
                    'table' => 'tbloptions',
                    'field' => 'value',
                ],
                [
                    'table' => 'tblstaff',
                    'field' => 'email_signature',
                ],
                [
                    'table' => 'tblpredefinedreplies',
                    'field' => 'message',
                ],
                [
                    'table' => 'tblprojectdiscussioncomments',
                    'field' => 'content',
                ],
                [
                    'table' => 'tblprojectdiscussions',
                    'field' => 'description',
                ],
                [
                    'table' => 'tblprojectnotes',
                    'field' => 'content',
                ],
                [
                    'table' => 'tblprojects',
                    'field' => 'description',
                ],
                [
                    'table' => 'tblreminders',
                    'field' => 'description',
                ],
                [
                    'table' => 'tblstafftasks',
                    'field' => 'description',
                ],
                [
                    'table' => 'tblstafftaskcomments',
                    'field' => 'content',
                ],
                [
                    'table' => 'tblsurveys',
                    'field' => 'description',
                ],
                [
                    'table' => 'tblsurveys',
                    'field' => 'viewdescription',
                ],
                [
                    'table' => 'tblticketreplies',
                    'field' => 'message',
                ],
                [
                    'table' => 'tbltickets',
                    'field' => 'message',
                ],
                [
                    'table' => 'tbltodoitems',
                    'field' => 'description',
                ],
                [
                    'table' => 'tblproposalcomments',
                    'field' => 'content',
                ],
                [
                    'table' => 'tblproposals',
                    'field' => 'content',
                ],
                [
                    'table' => 'tblleadactivitylog',
                    'field' => 'description',
                ],
                [
                    'table' => 'tblknowledgebasegroups',
                    'field' => 'description',
                ],
                [
                    'table' => 'tblknowledgebase',
                    'field' => 'description',
                ],
                [
                    'table' => 'tblinvoices',
                    'field' => 'terms',
                ],
                [
                    'table' => 'tblinvoices',
                    'field' => 'clientnote',
                ],
                [
                    'table' => 'tblinvoices',
                    'field' => 'adminnote',
                ],
                [
                    'table' => 'tblsalesactivity',
                    'field' => 'description',
                ],
                [
                    'table' => 'tblsalesactivity',
                    'field' => 'additional_data',
                ],
                [
                    'table' => 'tblestimates',
                    'field' => 'terms',
                ],
                [
                    'table' => 'tblestimates',
                    'field' => 'clientnote',
                ],
                [
                    'table' => 'tblestimates',
                    'field' => 'adminnote',
                ],
                [
                    'table' => 'tblgoals',
                    'field' => 'description',
                ],
                [
                    'table' => 'tblcontracts',
                    'field' => 'description',
                ],
                [
                    'table' => 'tblcontracts',
                    'field' => 'content',
                ],
            ];
            $affectedRows = 0;
            foreach ($tables as $t) {
                $this->db->query('UPDATE `' . $t['table'] . '` SET `' . $t['field'] . '` = replace(' . $t['field'] . ', "' . $old_url . '", "' . $new_url . '")');
                $affectedRows += $this->db->affected_rows();
            }
            echo '<h1>Total links replaced: ' . $affectedRows . '</h1>';
        } else {
            echo '<h1>Set config item <b>migration_enabled</b> to TRUE in <b>application/config/migration.php</b></h1>';
        }
    }
}

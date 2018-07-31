<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Subscriptions_model extends CRM_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get($where = [])
    {
        $this->select();
        $this->join();
        $this->db->where($where);

        return $this->db->get('tblsubscriptions')->result_array();
    }

    public function get_by_id($id, $where = [])
    {
        $this->select();
        $this->join();
        $this->db->where('tblsubscriptions.id', $id);
        $this->db->where($where);

        return $this->db->get('tblsubscriptions')->row();
    }

    public function get_by_hash($hash, $where = [])
    {
        $this->select();
        $this->join();
        $this->db->where('hash', $hash);
        $this->db->where($where);

        return $this->db->get('tblsubscriptions')->row();
    }

    public function get_child_invoices($id)
    {
        $this->db->select('id');
        $this->db->where('subscription_id', $id);
        $invoices = $this->db->get('tblinvoices')->result_array();
        $child    = [];

        if (!class_exists('invoices_model')) {
            $this->load->model('invoices_model');
        }

        foreach ($invoices as $invoice) {
            $child[] = $this->invoices_model->get($invoice['id']);
        }

        return $child;
    }

    public function create($data)
    {
        $this->db->insert('tblsubscriptions', array_merge($data, [
                'created'      => date('Y-m-d H:i:s'),
                'hash'         => app_generate_hash(),
                'created_from' => get_staff_user_id(),
            ]));

        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->where('tblsubscriptions.id', $id);
        $this->db->update('tblsubscriptions', $data);

        return $this->db->affected_rows() > 0;
    }

    private function select()
    {
        $this->db->select('tblsubscriptions.id as id, date, next_billing_cycle, status, tblsubscriptions.project_id as project_id, description, tblsubscriptions.created_from as created_from, tblsubscriptions.name as name, tblcurrencies.name as currency_name, tblcurrencies.symbol, currency, clientid, ends_at, date_subscribed, stripe_plan_id,stripe_subscription_id,quantity,hash,tbltaxes.name as tax_name, tbltaxes.taxrate as tax_percent, tax_id, stripe_id as stripe_customer_id,' . get_sql_select_client_company());
    }

    private function join()
    {
        $this->db->join('tblcurrencies', 'tblcurrencies.id=tblsubscriptions.currency');
        $this->db->join('tbltaxes', 'tbltaxes.id=tblsubscriptions.tax_id', 'left');
        $this->db->join('tblclients', 'tblclients.userid=tblsubscriptions.clientid');
    }

    public function send_email_template($id, $cc = '', $template = 'send-subscription')
    {
        $this->load->model('emails_model');

        $this->emails_model->set_rel_id($id);
        $this->emails_model->set_rel_type('subscription');

        $subscription = $this->get_by_id($id);

        $contact      = $this->clients_model->get_contact(get_primary_contact_user_id($subscription->clientid));
        $merge_fields = [];
        $merge_fields = array_merge($merge_fields, get_client_contact_merge_fields($subscription->clientid, $contact->id));
        $merge_fields = array_merge($merge_fields, get_subscription_merge_fields($subscription->id));

        $email = $contact->email;

        /* if($template == 'subscription-payment-failed' || $template == 'subscription-canceled') {
             $this->load->library('stripe_subscriptions');
             $client = $this->clients_model->get($subscription->clientid);
             $stripeCustomer = $this->stripe_subscriptions->get_customer($client->stripe_id);
             $email =  $stripeCustomer->email;
         }*/

        $sent = $this->emails_model->send_email_template($template, $email, $merge_fields, '', $cc);

        return $sent ? true : false;
    }

    public function delete($id, $simpleDelete = false)
    {
        $subscription = $this->get_by_id($id);

        if (!empty($subscription->stripe_subscription_id) && $simpleDelete == false) {
            return false;
        }

        $this->db->where('id', $id);
        $this->db->delete('tblsubscriptions');

        if ($this->db->affected_rows() > 0) {
            delete_tracked_emails($id, 'subscription');

            return true;
        }

        return false;
    }
}

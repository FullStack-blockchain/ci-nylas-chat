<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Subscription extends Clients_controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($hash)
    {
        $this->load->model('subscriptions_model');
        $this->load->library('stripe_subscriptions');
        $subscription = $this->subscriptions_model->get_by_hash($hash);

        if (!$subscription) {
            show_404();
        }

        $data['stripe_customer'] = false;
        if (!empty($subscription->stripe_customer_id)) {
            $data['stripe_customer'] = $this->stripe_subscriptions->get_customer_with_default_source($subscription->stripe_customer_id);
        }

        $plan = $this->stripe_subscriptions->get_plan($subscription->stripe_plan_id);

        $upcomingInvoice           = new stdClass();
        $upcomingInvoice->total    = $plan->amount * $subscription->quantity;
        $upcomingInvoice->subtotal = $upcomingInvoice->total;

        if (!empty($subscription->tax_percent)) {
            $totalTax = $upcomingInvoice->total * ($subscription->tax_percent / 100);
            $upcomingInvoice->total += $totalTax;
        }

        $data['total']                = $upcomingInvoice->total;
        $upcomingInvoice->tax_percent = $subscription->tax_percent;
        $product                      = $this->stripe_subscriptions->get_product($plan->product);

        $upcomingInvoice->lines         = new stdClass();
        $upcomingInvoice->lines->data   = [];
        $upcomingInvoice->lines->data[] = [
            'description' => $product->name . ' (' . format_money($plan->amount / 100, strtoupper($subscription->symbol)) . ' / ' . $plan->interval . ')',
            'amount'      => $plan->amount * $subscription->quantity,
            'quantity'    => $subscription->quantity,
        ];
        $this->use_navigation = false;
        $this->use_submenu    = false;
        $data['child_invoices'] = $this->subscriptions_model->get_child_invoices($subscription->id);
        $data['invoice']      = subscription_invoice_preview_data($subscription, $upcomingInvoice);
        $data['plan']         = $plan;
        $data['subscription'] = $subscription;
        $data['title']        = $subscription->name;
        $data['hash']         = $hash;
        $data['bodyclass']    = 'subscriptionhtml';
        $this->data           = $data;
        $this->view           = 'subscriptionhtml';
        $this->layout();
    }

    public function subscribe($subscription_hash)
    {
        $this->load->model('subscriptions_model');
        $this->load->library('stripe_subscriptions');

        $subscription = $this->subscriptions_model->get_by_hash($subscription_hash);

        if (!$subscription) {
            show_404();
        }

        $email = $this->input->post('stripeEmail');

        $stripe_customer_id = $subscription->stripe_customer_id;
        $source             = $this->input->post('stripeToken');
        if (empty($stripe_customer_id)) {
            try {
                $customer = $this->stripe_subscriptions->create_customer([
                    'email'       => $email,
                    'source'      => $source,
                    'description' => $subscription->company,
                ]);

                $stripe_customer_id = $customer->id;

                $this->db->where('userid', $subscription->clientid);
                $this->db->update('tblclients', [
                    'stripe_id' => $stripe_customer_id,
                ]);
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        } elseif (!empty($stripe_customer_id)) {
            // Perhaps had source and it's deleted
            $customer = $this->stripe_subscriptions->get_customer($stripe_customer_id);
            if (empty($customer->default_source)) {
                $customer->source = $source;
                $customer->save();
            }
        }

        try {
            $params = [];

            $params['tax_percent'] = $subscription->tax_percent;

            $params['metadata'] = [
                'pcrm-subscription-hash' => $subscription->hash,
            ];

            $params['items'] = [
                [
                    'plan' => $subscription->stripe_plan_id,
                ],
            ];

            $future                 = false;
            $updateFirstBillingDate = false;
            if (!empty($subscription->date)) {
                $anchor = strtotime($subscription->date);

                if ($subscription->date <= date('Y-m-d')) {
                    $anchor                 = false;
                    $updateFirstBillingDate = date('Y-m-d');
                }

                if ($anchor) {
                    $params['billing_cycle_anchor'] = $anchor;
                    $params['prorate']              = false;
                    $future                         = true;
                }
            }

            if ($subscription->quantity > 1) {
                $params['items'][0]['quantity'] = $subscription->quantity;
            }

            $stripeSubscription = $this->stripe_subscriptions->subscribe($stripe_customer_id, $params);

            $update = [
                'stripe_subscription_id' => $stripeSubscription->id,
                'date_subscribed'        => date('Y-m-d H:i:s'),
            ];

            if ($future) {
                $update['status'] = 'future';
                if ($anchor) {
                    $update['next_billing_cycle'] = $anchor;
                }
            }

            if ($updateFirstBillingDate) {
                $update['date'] = $updateFirstBillingDate;
            }

            $this->subscriptions_model->update($subscription->id, $update);

            set_alert('success', _l('customer_successfully_subscribed_to_subscription', $subscription->name));
        } catch (Exception $e) {
            set_alert('warning', $e->getMessage());
        }

        redirect($_SERVER['HTTP_REFERER']);
    }
}

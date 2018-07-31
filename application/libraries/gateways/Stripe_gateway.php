<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Omnipay\Omnipay;

// require_once(APPPATH . 'third_party/omnipay/vendor/autoload.php');

class Stripe_gateway extends App_gateway
{
    public function __construct()
    {
        /**
        * Call App_gateway __construct function
        */
        parent::__construct();

        /**
        * REQUIRED
        * Gateway unique id
        * The ID must be alpha/alphanumeric
        */
        $this->setId('stripe');

        /**
         * REQUIRED
         * Gateway name
         */
        $this->setName('Stripe Checkout');

        /**
         * Add gateway settings
        */
        $this->setSettings([
            [
                'name'      => 'api_secret_key',
                'encrypted' => true,
                'label'     => 'settings_paymentmethod_stripe_api_secret_key',
            ],
            [
                'name'  => 'api_publishable_key',
                'label' => 'settings_paymentmethod_stripe_api_publishable_key',
            ],
            [
                'name'          => 'description_dashboard',
                'label'         => 'settings_paymentmethod_description',
                'type'          => 'textarea',
                'default_value' => 'Payment for Invoice {invoice_number}',
            ],
            [
                'name'             => 'webhook_key',
                'label'            => 'Stripe Checkout Webhook Key',
                'default_value'    => app_generate_hash(),
                'after'            => '<p class="mbot15">Secret key to protect your webhook, webhook URL: ' . site_url('gateways/stripe/webhook/YOUR_WEBHOOK_KEY<br /><b>[Configure Webhook only if you are using Subscriptions]</b></p>'),
                'field_attributes' => ['required' => true],
            ],
            [
                'name'          => 'currencies',
                'label'         => 'settings_paymentmethod_currencies',
                'default_value' => 'USD,CAD',
            ],
            [
                'name'          => 'allow_primary_contact_to_update_credit_card',
                'type'          => 'yes_no',
                'default_value' => 1,
                'label'         => 'allow_primary_contact_to_update_credit_card',
            ],
            [
                'name'          => 'test_mode_enabled',
                'type'          => 'yes_no',
                'default_value' => 1,
                'label'         => 'settings_paymentmethod_testing_mode',
            ],
        ]);

        /**
         * REQUIRED
         * Hook gateway with other online payment modes
         */
        add_action('before_add_online_payment_modes', [ $this, 'initMode' ]);
    }

    public function process_payment($data)
    {
        redirect(site_url('gateways/stripe/make_payment?invoiceid=' . $data['invoiceid'] . '&total=' . $data['amount'] . '&hash=' . $data['invoice']->hash));
    }

    public function finish_payment($data)
    {
        $this->ci->load->library('stripe_core');

        $client = $this->ci->clients_model->get($data['clientid']);
        $stripeCustomerId = $client->stripe_id;
        if(empty($stripeCustomerId)) {

            $stripeCustomer = $this->ci->stripe_core->create_customer([
                'email'       => $data['email'],
                'source'      => $data['stripeToken'],
                'description' => $client->company,
            ]);

            $this->ci->db->where('userid', $client->userid);
            $this->ci->db->update('tblclients', ['stripe_id'=>$stripeCustomer->id]);
            $stripeCustomerId = $stripeCustomer->id;

        } else if(!empty($stripeCustomerId)) {
            $stripeCustomer = $this->ci->stripe_core->get_customer($stripeCustomerId);
            if (empty($stripeCustomer->default_source)) {
                $stripeCustomer->source = $data['stripeToken'];
                $stripeCustomer->save();
            }
        }

        $result = $this->ci->stripe_core->charge([
            'amount'   => $data['amount'] * 100,
            'metadata' => [
                'ClientID' => $data['clientid'],
            ],
            'customer' => $stripeCustomerId,
            'description' => $data['description'],
            'currency'    => $data['currency'],
        ]);

        return $result;
    }
}

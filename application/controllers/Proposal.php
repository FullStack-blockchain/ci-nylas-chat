<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Proposal extends Clients_controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($id, $hash)
    {
        check_proposal_restrictions($id, $hash);
        $proposal = $this->proposals_model->get($id);
        if ($proposal->rel_type == 'customer' && !is_client_logged_in()) {
            load_client_language($proposal->rel_id);
        }
        $identity_confirmation_enabled = get_option('proposal_accept_identity_confirmation');
        if ($this->input->post()) {
            $action = $this->input->post('action');
            switch ($action) {
                case 'proposal_pdf':

                    $proposal_number = format_proposal_number($id);
                    $companyname     = get_option('invoice_company_name');
                    if ($companyname != '') {
                        $proposal_number .= '-' . mb_strtoupper(slug_it($companyname), 'UTF-8');
                    }

                    try {
                        $pdf = proposal_pdf($proposal);
                    } catch (Exception $e) {
                        echo $e->getMessage();
                        die;
                    }

                    $pdf->Output($proposal_number . '.pdf', 'D');

                    break;
                case 'proposal_comment':
                    // comment is blank
                    if (!$this->input->post('content')) {
                        redirect($this->uri->uri_string());
                    }
                    $data               = $this->input->post();
                    $data['proposalid'] = $id;
                    $this->proposals_model->add_comment($data, true);
                    redirect($this->uri->uri_string() . '?tab=discussion');

                    break;
                case 'accept_proposal':
                    $success = $this->proposals_model->mark_action_status(3, $id, true);
                    if ($success) {
                        process_digital_signature_image($this->input->post('signature', false), PROPOSAL_ATTACHMENTS_FOLDER . $id);

                        $this->db->where('id', $id);
                        $this->db->update('tblproposals', get_acceptance_info_array());
                        redirect($this->uri->uri_string(), 'refresh');
                    }

                    break;
                case 'decline_proposal':
                    $success = $this->proposals_model->mark_action_status(2, $id, true);
                    if ($success) {
                        redirect($this->uri->uri_string(), 'refresh');
                    }

                    break;
            }
        }

        $number_word_lang_rel_id = 'unknown';
        if ($proposal->rel_type == 'customer') {
            $number_word_lang_rel_id = $proposal->rel_id;
        }
        $this->load->library('numberword', [
            'clientid' => $number_word_lang_rel_id,
        ]);

        $this->use_navigation = false;
        $this->use_submenu    = false;

        $data['title']     = $proposal->subject;
        $data['proposal']  = do_action('proposal_html_pdf_data', $proposal);
        $data['bodyclass'] = 'proposal proposal-view';

        $data['identity_confirmation_enabled'] = $identity_confirmation_enabled;
        if ($identity_confirmation_enabled == '1') {
            $data['bodyclass'] .= ' identity-confirmation';
        }

        $data['comments'] = $this->proposals_model->get_comments($id);
        add_views_tracking('proposal', $id);
        do_action('proposal_html_viewed', $id);
        $data['exclude_reset_css'] = true;
        $data                      = do_action('proposal_customers_area_view_data', $data);
        no_index_customers_area();
        $this->data = $data;
        $this->view = 'viewproposal';
        $this->layout();
    }
}

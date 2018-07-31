<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Survey extends Clients_controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($id, $hash)
    {
        $this->load->model('surveys_model');
        $survey = $this->surveys_model->get($id);

        // Last statement is for
        if (!$survey
            || ($survey->hash != $hash)
            || (!$hash || !$id)
            // Users with permission manage surveys to preview the survey even if is not active
            || ($survey->active == 0 && !has_permission('surveys', '', 'view'))
             // Check if survey is only for logged in participants / staff / clients
            || ($survey->onlyforloggedin == 1 && !is_logged_in())
        ) {
            show_404();
        }

        // Ip Restrict Check
        if ($survey->iprestrict == 1) {
            $this->db->where('surveyid', $id);
            $this->db->where('ip', $this->input->ip_address());
            $total = $this->db->count_all_results('tblsurveyresultsets');
            if ($total > 0) {
                show_404();
            }
        }
        if ($this->input->post()) {
            $success = $this->surveys_model->add_survey_result($id, $this->input->post());
            if ($success) {
                $survey = $this->surveys_model->get($id);
                if ($survey->redirect_url !== '') {
                    redirect($survey->redirect_url);
                }
                // Message is by default in English because there is no easy way to know the customer language
                set_alert('success', do_action('survey_success_message', 'Thank you for participating in this survey. Your answers are very important to us.'));

                redirect(do_action('survey_default_redirect', site_url('survey/' . $id . '/' . $hash . '?participated=yes')));
            }
        }

        $this->use_navigation = false;
        $this->use_submenu    = false;
        $data['survey']       = $survey;
        $data['title']        = $data['survey']->subject;
        $this->data           = $data;
        no_index_customers_area();
        $this->view = 'survey_view';
        $this->layout();
    }
}

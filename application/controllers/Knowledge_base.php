<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Knowledge_base extends Clients_controller
{
    public function __construct()
    {
        parent::__construct();
        if (is_staff_logged_in() && get_option('use_knowledge_base') == 0) {
            set_alert('warning', 'Knowledge base is disabled, navigate to Setup->Settings->Customers and set Use Knowledge Base to YES.');
        }
        do_action('customers_area_knowledge_construct');
    }

    public function index($slug = '')
    {
        if (!is_knowledge_base_viewable()) {
            show_404();
        }

        $data['articles']              = get_all_knowledge_base_articles_grouped(true);
        $data['knowledge_base_search'] = true;
        $data['title']                 = _l('clients_knowledge_base');
        $this->view                    = 'knowledge_base';
        $this->data                    = $data;
        $this->layout();
    }

    public function search()
    {
        if (!is_knowledge_base_viewable()) {
            show_404();
        }

        $q = $this->input->get('q');

        $where_kb = [];
        if (!empty($q)) {
            $where_kb = '(subject LIKE "%' . $q . '%" OR description LIKE "%' . $q . '%" OR slug LIKE "%' . $q . '%")';
        }

        $data['articles']              = get_all_knowledge_base_articles_grouped(true, $where_kb);
        $data['search_results']        = true;
        $data['title']                 = _l('showing_search_result', $q);
        $data['knowledge_base_search'] = true;
        $this->view                    = 'knowledge_base';
        $this->data                    = $data;
        $this->layout();
    }

    public function article($slug)
    {
        if (!is_knowledge_base_viewable()) {
            show_404();
        }

        $data['article'] = $this->knowledge_base_model->get(false, $slug);
        if (!$slug) {
            redirect(site_url('knowledge-base'));
        }

        if (!$data['article'] || $data['article']->active_article == 0) {
            show_404();
        }

        $data['knowledge_base_search'] = true;
        $data['related_articles']      = $this->knowledge_base_model->get_related_articles($data['article']->articleid);
        add_views_tracking('kb_article', $data['article']->articleid);
        $data['title'] = $data['article']->subject;
        $this->view    = 'knowledge_base_article';
        $this->data    = $data;
        $this->layout();
    }

    public function category($slug)
    {
        if (!is_knowledge_base_viewable()) {
            show_404();
        }

        $where_kb         = 'articlegroup IN (SELECT groupid FROM tblknowledgebasegroups WHERE group_slug="' . $slug . '")';
        $data['category'] = $slug;
        $data['articles'] = get_all_knowledge_base_articles_grouped(true, $where_kb);

        $data['title']                 = count($data['articles']) == 1 ? $data['articles'][0]['name'] : _l('clients_knowledge_base');
        $data['knowledge_base_search'] = true;
        $this->data                    = $data;
        $this->view                    = 'knowledge_base';
        $this->layout();
    }

    public function add_kb_answer()
    {
        if (!is_knowledge_base_viewable()) {
            show_404();
        }
        // This is for did you find this answer useful
        if (($this->input->post() && $this->input->is_ajax_request())) {
            echo json_encode($this->knowledge_base_model->add_article_answer($this->input->post()));
            die();
        }
    }
}

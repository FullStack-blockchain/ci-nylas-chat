<?php init_single_head(); ?>
<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/surveys.css'); ?>">
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content custom-scrollbar">

                <div id="surveys-manage" class="page-layout simple left-sidebar-floating">


                    <div class="page-content p-4 p-sm-6">
                        <div class="col-md-5 p-0 pr-6 pb-6" id="survey-add-edit-wrapper">
                            <div class="card">
                                <div class="card-header"><?php echo $title; ?></div>
                                <div class="card-body">
                                    <?php echo form_open($this->uri->uri_string(), array('id' => 'survey_form')); ?>
                                    <?php $value = (isset($survey) ? $survey->subject : ''); ?>
                                    <?php $attrs = (isset($survey) ? array() : array('autofocus' => true)); ?>
                                    <?php echo render_input('subject', 'survey_add_edit_subject', $value, 'text', $attrs); ?>
                                    <p class="bold mt-4"><?php echo _l('survey_add_edit_short_description_view'); ?></p>
                                    <?php $value = (isset($survey) ? $survey->viewdescription : ''); ?>
                                    <?php echo render_textarea('viewdescription', '', $value, array(), array(), '', 'tinymce-view-description mt-0'); ?>
                                    <p class="bold mt-4"><?php echo _l('survey_add_edit_email_description'); ?></p>
                                    <?php $contents = '';
                                    if (isset($survey)) {
                                        $contents = $survey->description;
                                    } ?>
                                    <?php echo render_textarea('description', '', $contents, array(), array(), '', 'tinymce-email-description mt-0'); ?>
                                    <p class="bold mt-4">
                                        <?php echo _l('survey_include_survey_link'); ?> : <span
                                                class="text-info">{survey_link}</span>
                                    </p>
                                    <?php if ($found_custom_fields) { ?>
                                        <hr class="mt-4 mb-4"/>
                                        <p class="bold tooltip-pointer f-14" data-toggle="tooltip"
                                           style="border-bottom: 1px dashed"
                                           title="<?php echo _l('survey_mail_lists_custom_fields_tooltip'); ?>"><?php echo _l('survey_available_mail_lists_custom_fields'); ?></p>
                                    <?php } ?>
                                    <?php
                                    foreach ($mail_lists as $list) {
                                        if (count($list['customfields']) == 0) {
                                            continue;
                                        }
                                        ?>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-secondary dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                <?php echo $list['name']; ?> <span class="caret ml-2"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <?php foreach ($list['customfields'] as $custom_field) { ?>
                                                    <li><a href="#"
                                                           class="dropdown-item add_email_list_custom_field_to_survey"
                                                           data-toggle="tooltip"
                                                           data-placement="right"
                                                           title="{<?php echo $custom_field['fieldslug']; ?>}"
                                                           data-slug="{<?php echo $custom_field['fieldslug']; ?>}"><?php echo $custom_field['fieldname']; ?></a>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    <?php } ?>
                                    <hr class="mt-4 mb-4"/>
                                    <div class="clearfix"></div>
                                    <?php $value = (isset($survey) ? $survey->fromname : ''); ?>
                                    <?php echo render_input('fromname', 'survey_add_edit_from', $value); ?>
                                    <?php $value = (isset($survey) ? $survey->redirect_url : ''); ?>
                                    <?php echo render_input('redirect_url', 'survey_add_edit_redirect_url', $value, 'text', array('data-toggle' => 'tooltip', 'title' => 'survey_add_edit_red_url_note'), [], 'pt-4'); ?>

                                    <div class="form-check mt-4">
                                        <label class="form-check-label">
                                            <input type="checkbox" name="disabled" class="form-check-input"
                                                   id="disabled" <?php if (isset($survey) && $survey->active == 0) {
                                                echo 'checked';
                                            } ?>>
                                            <span class="checkbox-icon"></span>
                                            <span><?php echo _l('survey_add_edit_disabled'); ?></span>
                                        </label>
                                    </div>

                                    <div class="form-check mt-2">
                                        <label class="form-check-label">
                                            <input type="checkbox" name="onlyforloggedin" class="form-check-input"
                                                   id="onlyforloggedin" <?php if (isset($survey) && $survey->onlyforloggedin == 1) {
                                                echo 'checked';
                                            } ?>>
                                            <span class="checkbox-icon"></span>
                                            <span><?php echo _l('survey_add_edit_only_for_logged_in'); ?></span>
                                        </label>
                                    </div>

                                    <button type="submit"
                                            class="btn btn-info pull-right mt-4"><?php echo _l('submit'); ?></button>
                                    <?php echo form_close(); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7 p-0 pb-6" id="survey_questions_wrapper">
                            <div class="card">
                                <div class="panel-body">
                                    <?php if (isset($survey)){ ?>
                                    <ul class="nav nav-tabs tabs-in-body-no-margin" role="tablist">
                                        <li role="presentation" class="nav-item active">
                                            <a href="#survey_questions_tab" aria-controls="survey_questions_tab"
                                               class="nav-link"
                                               role="tab" data-toggle="tab">
                                                <?php echo _l('survey_questions_string'); ?>
                                            </a>
                                        </li>
                                        <li role="presentation" class="nav-item">
                                            <a href="#survey_send_tab" aria-controls="survey_send_tab" role="tab"
                                               class="nav-link"
                                               data-toggle="tab">
                                                <?php echo _l('send_survey_string'); ?>
                                            </a>
                                        </li>
                                        <li class="toggle_view nav-item">
                                            <a href="#" onclick="survey_toggle_full_view(); return false;"
                                               class="nav-link"
                                               data-toggle="tooltip" data-placement="bottom"
                                               data-title="<?php echo _l('toggle_full_view'); ?>">
                                                <i class="fa fa-expand line-height-25 m-0 ml-2"></i></a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane active" id="survey_questions_tab">
                                            <div class="_buttons">
                                                <?php
                                                if (total_rows('tblsurveyresultsets', 'surveyid=' . $survey->surveyid) > 0) { ?>
                                                    <a href="<?php echo admin_url('surveys/results/' . $survey->surveyid); ?>"
                                                       target="_blank" style="min-width: auto !important;"
                                                       class="btn btn-success pull-right ml-4 btn-with-tooltip"
                                                       data-toggle="tooltip" data-placement="bottom"
                                                       data-title="<?php echo _l('survey_list_view_results_tooltip'); ?>"><i
                                                                class="fa fa-area-chart line-height-25"></i></a>
                                                <?php } ?>
                                                <!-- Single button -->
                                                <a href="<?php echo site_url('survey/' . $survey->surveyid . '/' . $survey->hash); ?>"
                                                   target="_blank" style="min-width: auto !important;"
                                                   class="btn btn-success pull-right ml-4 btn-with-tooltip"
                                                   data-toggle="tooltip" data-placement="bottom"
                                                   data-title="<?php echo _l('survey_list_view_tooltip'); ?>"><i
                                                            class="fa fa-eye line-height-25"></i></a>
                                                <?php if (has_permission('surveys', '', 'edit')){ ?>
                                                <div class="btn-group pull-right">
                                                    <button type="button" class="btn btn-info dropdown-toggle"
                                                            data-toggle="dropdown" aria-haspopup="true"
                                                            style="min-height: auto !important;"
                                                            aria-expanded="false">
                                                        <?php echo _l('survey_insert_field'); ?> <span
                                                                class="caret ml-2"></span>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <a href="#" class="dropdown-item"
                                                           onclick="add_survey_question('checkbox',<?php echo $survey->surveyid; ?>);return false;">
                                                            <?php echo _l('survey_field_checkbox'); ?></a>
                                                        <a href="#" class="dropdown-item"
                                                           onclick="add_survey_question('radio',<?php echo $survey->surveyid; ?>);return false;">
                                                            <?php echo _l('survey_field_radio'); ?></a>
                                                        <a href="#" class="dropdown-item"
                                                           onclick="add_survey_question('input',<?php echo $survey->surveyid; ?>);return false;">
                                                            <?php echo _l('survey_field_input'); ?></a>
                                                        <a href="#" class="dropdown-item"
                                                           onclick="add_survey_question('textarea',<?php echo $survey->surveyid; ?>);return false;">
                                                            <?php echo _l('survey_field_textarea'); ?></a>
                                                    </ul>
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <div class="clearfix"></div>
                                            <hr class="mt-4 mb-4"/>

                                           <!--
                                            <div class="form-check mt-4">
                                                <label class="form-check-label">
                                                    <input type="checkbox" name="send_survey_to[staff]" class="form-check-input"
                                                           id="ml_staff">
                                                    <span class="checkbox-icon"></span>
                                                    <span><?php //echo _l('survey_send_mail_list_staff'); ?></span>
                                                </label>
                                            </div>

                                            -->


                                            <?php
                                            $question_area = '<ul class="list-unstyled survey_question_callback" id="survey_questions">';
                                            if (count($survey->questions) > 0) {
                                                foreach ($survey->questions as $question) {
                                                    $question_area .= '<li>';

                                                    if ($question['required'] == 1) {
                                                        $_required = ' checked';
                                                    } else {
                                                        $_required = '';
                                                    }

                                                    $question_area .= '<div class="form-group question">';

                                                    $question_area .= '<div class="form-check">';
                                                    $question_area .= '<label class="form-check-label">';
                                                    $question_area .= '<input type="checkbox" class="form-check-input" id="req_' . $question['questionid'] . '" onchange="update_question(this,\'' . $question['boxtype'] . '\',' . $question['questionid'] . ');" data-question_required="' . $question['questionid'] . '" name="required[]" ' . $_required . '>';
                                                    $question_area .= '<span class="checkbox-icon"></span>';
                                                    $question_area .= '<span>' . _l('survey_question_required') . '</span>';
                                                    $question_area .= '</label>';
                                                    $question_area .= '</div>';

                                                    $question_area .= '<input type="hidden" value="" name="order[]">';
                                                    // used only to identify input key no saved in database
                                                    $question_area .= '<label for="' . $question['questionid'] . '" class="control-label display-block">' . _l('question_string') . '
                             <a href="#" onclick="update_question(this,\'' . $question['boxtype'] . '\',' . $question['questionid'] . '); return false;" class="pull-right update-question-button"><i class="fa fa-refresh text-success question_update"></i></a>
                             <a href="#" onclick="remove_question_from_database(this,' . $question['questionid'] . '); return false;" class="pull-right"><i class="fa fa-remove text-danger"></i></a>
                         </label>';
                                                    $question_area .= '<input type="text" onblur="update_question(this,\'' . $question['boxtype'] . '\',' . $question['questionid'] . ');" data-questionid="' . $question['questionid'] . '" class="form-control questionid" value="' . $question['question'] . '">';
                                                    if ($question['boxtype'] == 'textarea') {
                                                        $question_area .= '<textarea class="form-control mt-4" disabled="disabled" rows="6">' . _l('survey_question_only_for_preview') . '</textarea>';
                                                    } else if ($question['boxtype'] == 'checkbox' || $question['boxtype'] == 'radio') {
                                                        $question_area .= '<div class="row">';
                                                        $x = 0;
                                                        foreach ($question['box_descriptions'] as $box_description) {
                                                            $box_description_icon_class = 'fa-minus text-danger';
                                                            $box_description_function = 'remove_box_description_from_database(this,' . $box_description['questionboxdescriptionid'] . '); return false;';
                                                            if ($x == 0) {
                                                                $box_description_icon_class = 'fa-plus';
                                                                $box_description_function = 'add_box_description_to_database(this,' . $question['questionid'] . ',' . $question['boxid'] . '); return false;';
                                                            }
                                                            $question_area .= '<div class="box_area full-width">';

                                                            $question_area .= '<div class="col-md-12">';
                                                            $question_area .= '<a href="#" class="add_remove_action survey_add_more_box" onclick="' . $box_description_function . '"><i class="fa ' . $box_description_icon_class . '"></i></a>';

                                                            $question_area .= '<div class="form-check">';
                                                            $question_area .= '<label class="form-check-label">';
                                                            $question_area .= '<input type="' . $question['boxtype'] . '" class="form-check-input" disabled="disabled" />';
                                                            $question_area .= '<span class="' . $question['boxtype'] . '-icon"></span>';
                                                            $question_area .= '<span class="full-width"><input type="text" onblur="update_question(this,\'' . $question['boxtype'] . '\',' . $question['questionid'] . ');" data-box-descriptionid="' . $box_description['questionboxdescriptionid'] . '" value="' . $box_description['description'] . '" class="survey_input_box_description form-control"></span>';
                                                            $question_area .= '</label>';
                                                            $question_area .= '</div>';

                                                            $question_area .= '</div>';
                                                            $question_area .= '</div>';
                                                            $x++;
                                                        }
                                                        // end box row
                                                        $question_area .= '</div>';
                                                    } else {
                                                        $question_area .= '<input type="text" class="form-control mt-5" disabled="disabled" value="' . _l('survey_question_only_for_preview') . '">';
                                                    }
                                                    $question_area .= '</div>';
                                                    $question_area .= '</li>';
                                                }
                                            }
                                            $question_area .= '</ul>';
                                            echo $question_area;
                                            ?>
                                        </div>

                                        <div role="tabpanel" class="tab-pane" id="survey_send_tab">

                                            <?php echo form_open('admin/surveys/send/' . $survey->surveyid); ?>

                                            <p class="mt-2 text-warning"><?php echo _l('survey_send_mail_lists_note_logged_in'); ?></p>
                                            <div class="form-group pt-0">

                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" name="send_survey_to[clients]"
                                                               class="form-check-input"
                                                               id="ml_clients">
                                                        <span class="checkbox-icon"></span>
                                                        <span><?php echo _l('survey_send_mail_list_clients'); ?></span>
                                                    </label>
                                                </div>

                                                <div class="customer-groups ml-4" style="display:none;">

                                                    <div class="form-check mt-4">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" checked name="ml_customers_all"
                                                                   class="form-check-input"
                                                                   id="ml_customers_all">
                                                            <span class="checkbox-icon"></span>
                                                            <span><?php echo _l('survey_customers_all'); ?></span>
                                                        </label>
                                                    </div>

                                                    <hr class="mb-1"/>

                                                    <?php foreach ($customers_groups as $group) { ?>
                                                        <div class="form-check mt-1">
                                                            <label class="form-check-label survey-customer-groups">
                                                                <input type="checkbox" class="form-check-input"
                                                                       name="customer_group[<?php echo $group['id']; ?>]"
                                                                       id="ml_customer_group_<?php echo $group['id']; ?>">
                                                                <span class="checkbox-icon"></span>
                                                                <span><?php echo $group['name']; ?></span>
                                                            </label>
                                                        </div>
                                                    <?php } ?>
                                                    <?php
                                                    if (is_gdpr() && (get_option('gdpr_enable_consent_for_contacts') == '1')) { ?>
                                                        <select name="contacts_consent[]"
                                                                title="<?php echo _l('gdpr_consent'); ?>"
                                                                multiple="true" id="contacts_consent"
                                                                class="selectpicker" data-width="100%">
                                                            <?php foreach ($purposes as $purpose) { ?>
                                                                <option value="<?php echo $purpose['id']; ?>">
                                                                    <?php echo $purpose['name']; ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    <?php } ?>
                                                </div>

                                                <hr class="mt-4 mb-4"/>

                                                <div class="form-check mt-4">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" name="send_survey_to[leads]"
                                                               class="form-check-input"
                                                               id="ml_leads">
                                                        <span class="checkbox-icon"></span>
                                                        <span><?php echo _l('leads'); ?></span>
                                                    </label>
                                                </div>

                                                <div class="leads-statuses ml-4" style="display:none;">

                                                    <div class="form-check mt-4">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" checked name="leads_all"
                                                                   class="form-check-input"
                                                                   id="ml_leads_all">
                                                            <span class="checkbox-icon"></span>
                                                            <span><?php echo _l('leads_all'); ?></span>
                                                        </label>
                                                    </div>

                                                    <hr class="mb-1"/>

                                                    <?php foreach ($leads_statuses as $status) { ?>

                                                        <div class="form-check mt-1">
                                                            <label class="form-check-label survey-lead-status">
                                                                <input type="checkbox" class="form-check-input"
                                                                       name="leads_status[<?php echo $status['id']; ?>]"
                                                                       id="ml_leads_status_<?php echo $status['id']; ?>">
                                                                <span class="checkbox-icon"></span>
                                                                <span><?php echo $status['name']; ?></span>
                                                            </label>
                                                        </div>
                                                    <?php } ?>

                                                    <?php
                                                    if (is_gdpr() && (get_option('gdpr_enable_consent_for_leads') == '1')) { ?>
                                                        <select name="leads_consent[]"
                                                                title="<?php echo _l('gdpr_consent'); ?>"
                                                                multiple="true" id="leads_consent"
                                                                class="selectpicker" data-width="100%">
                                                            <?php foreach ($purposes as $purpose) { ?>
                                                                <option value="<?php echo $purpose['id']; ?>">
                                                                    <?php echo $purpose['name']; ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    <?php } ?>
                                                </div>

                                                <hr class="mt-4 mb-4"/>

                                                <div class="form-check mt-4">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" name="send_survey_to[staff]"
                                                               class="form-check-input"
                                                               id="ml_staff">
                                                        <span class="checkbox-icon"></span>
                                                        <span><?php echo _l('survey_send_mail_list_staff'); ?></span>
                                                    </label>
                                                </div>

                                                <?php if (count($mail_lists) > 0) { ?>
                                                    <hr class="mt-4 mb-4"/>
                                                    <p class="bold"><?php echo _l('survey_send_mail_lists_string'); ?></p>
                                                    <?php foreach ($mail_lists as $list) { ?>

                                                        <div class="form-check mt-1">
                                                            <label class="form-check-label">
                                                                <input type="checkbox" class="form-check-input"
                                                                       id="ml_custom_<?php echo $list['listid']; ?>"
                                                                       name="send_survey_to[<?php echo $list['listid']; ?>]">
                                                                <span class="checkbox-icon"></span>
                                                                <span><?php echo $list['name']; ?></span>
                                                            </label>
                                                        </div>
                                                    <?php } ?>
                                                <?php } ?>


                                                <?php if (total_rows('tblsurveysendlog', array('iscronfinished' => 0, 'surveyid' => $survey->surveyid)) > 0) { ?>
                                                    <p class="text-warning mt-4"><?php echo _l('survey_send_notice'); ?></p>
                                                <?php } ?>
                                                <?php foreach ($send_log as $log) { ?>
                                                    <p>
                                                        <?php if (has_permission('surveys', '', 'delete')) { ?>
                                                            <a href="<?php echo admin_url('surveys/remove_survey_send/' . $log['id']); ?>"
                                                               class="_delete text-danger"><i
                                                                        class="fa fa-remove"></i></a>
                                                        <?php } ?>
                                                        <?php echo _l('survey_added_to_queue', _dt($log['date'])); ?>
                                                        ( <?php echo($log['iscronfinished'] == 0 ? _l('survey_send_till_now') . ' ' : '') ?>
                                                        <?php echo _l('survey_send_to_total', $log['total']); ?> )
                                                        <br/>
                                                        <b class="bold">
                                                            <?php echo _l('survey_send_finished', ($log['iscronfinished'] == 1 ? _l('settings_yes') : _l('settings_no'))); ?>
                                                        </b>
                                                    </p>
                                                    <?php if (!empty($log['send_to_mail_lists'])) { ?>
                                                        <p>
                                                            <b><?php echo _l('survey_send_to_lists'); ?>:</b> <?php
                                                            $send_lists = unserialize($log['send_to_mail_lists']);
                                                            foreach ($send_lists as $send_list) {
                                                                $last = end($send_lists);
                                                                echo _l($send_list, '', false) . ($last == $send_list ? '' : ',');
                                                            }
                                                            ?>
                                                        </p>
                                                    <?php } ?>
                                                    <hr class="mt-4 mb-4"/>
                                                <?php } ?>

                                                <button type="submit"
                                                        class="btn btn-info mt-4"><?php echo _l('survey_send_string'); ?></button>
                                            </div>
                                            <?php echo form_close(); ?>

                                        </div>
                                        <?php } else { ?>
                                            <p class="no-margin"><?php echo _l('survey_create_first'); ?></p>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php init_tail(); ?>
<script src="<?php echo base_url('assets-old/js/surveys.js'); ?>"></script>
<script>
    $(function () {
        init_editor('.tinymce-email-description');
        init_editor('.tinymce-view-description');
    });
</script>
</body>
</html>

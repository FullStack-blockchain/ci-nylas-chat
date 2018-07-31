<?php init_single_head(); ?>
<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/contract.css'); ?>">
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content custom-scrollbar">

                <div id="contract" class="page-layout simple left-sidebar-floating">

                    <div class="page-header bg-primary text-auto row no-gutters align-items-center justify-content-between p-4">

                        <div class="col-md col-sm-12">
                            <span class="logo-text h4"><?php echo _l('new_contract'); ?></span>
                        </div>
                    </div>
                    <!-- / HEADER -->


                    <div class="page-content p-4 p-sm-6">
                        <div class="content">
                            <div class="row">
                                <div id="left_column" class="col-md-5">
                                    <div class="card">
                                        <div class="panel-body">
                                            <?php echo form_open($this->uri->uri_string(), array('id' => 'contract-form')); ?>
                                            <div class="form-group">

                                                <div class="row m-0">
                                                    <div class="form-check p-r-10">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" id="trash" name="trash"
                                                                   class="form-check-input" data-toggle="tooltip"
                                                                   title="<?php echo _l('contract_trash_tooltip'); ?>" <?php if (isset($contract)) {
                                                                if ($contract->trash == 1) {
                                                                    echo 'checked';
                                                                }
                                                            }; ?>>
                                                            <span class="checkbox-icon"></span>
                                                            <span class="form-check-description"><?php echo _l('contract_trash'); ?></span>
                                                        </label>
                                                    </div>

                                                    <div class="form-check p-r-10">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" id="not_visible_to_client"
                                                                   name="not_visible_to_client"
                                                                   class="form-check-input"
                                                                   data-toggle="tooltip"<?php if (isset($contract)) {
                                                                if ($contract->not_visible_to_client == 1) {
                                                                    echo 'checked';
                                                                }
                                                            }; ?>>
                                                            <span class="checkbox-icon"></span>
                                                            <span class="form-check-description"><?php echo _l('contract_not_visible_to_client'); ?></span>
                                                        </label>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="form-group select-placeholder">
                                                <label for="clientid" class="control-label"><span
                                                            class="text-danger">* </span><?php echo _l('contract_client_string'); ?>
                                                </label>
                                                <select id="clientid" name="client" data-live-search="true"
                                                        data-width="100%"
                                                        class="ajax-search"
                                                        data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                                    <?php $selected = (isset($contract) ? $contract->client : '');
                                                    if ($selected == '') {
                                                        $selected = (isset($customer_id) ? $customer_id : '');
                                                    }
                                                    if ($selected != '') {
                                                        $rel_data = get_relation_data('customer', $selected);
                                                        $rel_val = get_relation_values($rel_data, 'customer');
                                                        echo '<option value="' . $rel_val['id'] . '" selected>' . $rel_val['name'] . '</option>';
                                                    } ?>
                                                </select>
                                            </div>
                                            <?php $value = (isset($contract) ? $contract->subject : '');

                                            $label = '<label>' . _l('contract_subject') . '</label>
                                                <i class="fa fa-question-circle" data-toggle="tooltip" title="' . _l('contract_subject_tooltip') . '" data-placement="bottom"></i>';
                                            echo render_input('subject', $label, $value); ?>


                                            <div class="form-group">
                                                <label for="contract_value"><?php echo _l('contract_value'); ?></label>
                                                <div class="input-group" data-toggle="tooltip"
                                                     title="<?php echo _l('contract_value_tooltip'); ?>">
                                                    <input type="number" class="form-control" name="contract_value"
                                                           value="<?php if (isset($contract)) {
                                                               echo $contract->contract_value;
                                                           } ?>">
                                                    <div class="input-group-addon">
                                                        <?php echo $base_currency->symbol; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                            $selected = (isset($contract) ? $contract->contract_type : '');
                                            if (is_admin() || get_option('staff_members_create_inline_contract_types') == '1') {
                                                echo render_select_with_input_group('contract_type', $types, array('id', 'name'), 'contract_type', $selected, '<a href="#" onclick="new_type();return false;"><i class="fa fa-plus"></i></a>');
                                            } else {
                                                echo render_select('contract_type', $types, array('id', 'name'), 'contract_type', $selected);
                                            }
                                            ?>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <?php $value = (isset($contract) ? _d($contract->datestart) : _d(date('Y-m-d'))); ?>
                                                    <?php echo render_date_input('datestart', 'contract_start_date', $value); ?>
                                                </div>
                                                <div class="col-md-6">
                                                    <?php $value = (isset($contract) ? _d($contract->dateend) : ''); ?>
                                                    <?php echo render_date_input('dateend', 'contract_end_date', $value); ?>
                                                </div>
                                            </div>
                                            <?php $value = (isset($contract) ? $contract->description : ''); ?>
                                            <?php echo render_textarea('description', 'contract_description', $value, array('rows' => 10)); ?>
                                            <?php $rel_id = (isset($contract) ? $contract->id : false); ?>
                                            <?php echo render_custom_fields('contracts', $rel_id); ?>
                                            <div class="btn-bottom-toolbar text-right m-t-10">
                                                <button type="submit"
                                                        class="btn btn-info"><?php echo _l('submit'); ?></button>
                                            </div>
                                            <?php echo form_close(); ?>
                                        </div>
                                    </div>
                                </div>

                                <?php if (isset($contract)) { ?>
                                    <div id="right_column" class="col-md-7">
                                        <div class="card">
                                            <div class="panel-body">
                                                <h4 class="no-margin"><?php echo $contract->subject; ?></h4>
                                                <a href="<?php echo site_url('contract/' . $contract->id . '/' . $contract->hash); ?>"
                                                   target="_blank">
                                                    <?php echo _l('view_contract'); ?>
                                                </a>
                                                <hr class="hr-panel-heading"/>
                                                <?php if ($contract->trash > 0) {
                                                    echo '<div class="ribbon default"><span>' . _l('contract_trash') . '</span></div>';
                                                } ?>

                                                <div class="horizontal-scrollable-tabs preview-tabs-top">
                                                    <div class="scroller arrow-left"><i
                                                                class="fa fa-angle-left"></i>
                                                    </div>
                                                    <div class="scroller arrow-right"><i
                                                                class="fa fa-angle-right"></i>
                                                    </div>
                                                    <div class="horizontal-tabs">
                                                        <ul class="nav nav-tabs tabs-in-body-no-margin contract-tab nav-tabs-horizontal mbot15"
                                                            role="tablist">
                                                            <li class="nav-link btn <?php if (!$this->input->get('tab') || $this->input->get('tab') == 'tab_content') {
                                                                echo 'active';
                                                            } ?>">
                                                                <a href="#tab_content" aria-controls="tab_content"
                                                                   role="tab"
                                                                   data-toggle="tab">
                                                                    <?php echo _l('contract_content'); ?>
                                                                </a>
                                                            </li>
                                                            <li class="nav-link btn <?php if ($this->input->get('tab') == 'attachments') {
                                                                echo 'active';
                                                            } ?>">
                                                                <a href="#attachments" aria-controls="attachments"
                                                                   role="tab"
                                                                   data-toggle="tab">
                                                                    <?php echo _l('contract_attachments'); ?>
                                                                </a>
                                                            </li>
                                                            <li class="nav-link btn">
                                                                <a href="#tab_comments" aria-controls="tab_comments"
                                                                   role="tab"
                                                                   data-toggle="tab"
                                                                   onclick="get_contract_comments(); return false;">
                                                                    <?php echo _l('contract_comments'); ?>
                                                                </a>
                                                            </li>
                                                            <li class="nav-link btn <?php if ($this->input->get('tab') == 'renewals') {
                                                                echo 'active';
                                                            } ?>">
                                                                <a href="#renewals" aria-controls="renewals"
                                                                   role="tab"
                                                                   data-toggle="tab">
                                                                    <?php echo _l('no_contract_renewals_history_heading'); ?>
                                                                </a>
                                                            </li>
                                                            <li class="nav-link btn tab-separator">
                                                                <a href="#tab_tasks" aria-controls="tab_tasks"
                                                                   role="tab"
                                                                   data-toggle="tab"
                                                                   onclick="init_rel_tasks_table(<?php echo $contract->id; ?>,'contract'); return false;">
                                                                    <?php echo _l('tasks'); ?>
                                                                </a>
                                                            </li>
                                                            <li title="<?php echo _l('emails_tracking'); ?>"
                                                                class="nav-link btn tab-separator">
                                                                <a href="#tab_emails_tracking"
                                                                   aria-controls="tab_emails_tracking"
                                                                   role="tab" data-toggle="tab">
                                                                    <?php if (!is_mobile()) { ?>
                                                                        <i class="fa fa-envelope-open-o"
                                                                           aria-hidden="true"></i>
                                                                    <?php } else { ?>
                                                                        <?php echo _l('emails_tracking'); ?>
                                                                    <?php } ?>
                                                                </a>
                                                            </li>
                                                            <li class="nav-link btn tab-separator toggle_view">
                                                                <a href="#"
                                                                   onclick="contract_full_view(); return false;"
                                                                   data-toggle="tooltip"
                                                                   data-title="<?php echo _l('toggle_full_view'); ?>">
                                                                    <i class="fa fa-expand"></i></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="tab-content">
                                                    <div role="tabpanel"
                                                         class="tab-pane fade<?php if (!$this->input->get('tab') || $this->input->get('tab') == 'tab_content') {
                                                             echo ' active in';
                                                         } ?>" id="tab_content">
                                                        <div class="row">
                                                            <?php if ($contract->signed == 1) { ?>
                                                                <div class="col-md-12">
                                                                    <div class="alert alert-success">
                                                                        <?php echo _l('document_signed_info', array(
                                                                                '<b>' . $contract->acceptance_firstname . ' ' . $contract->acceptance_lastname . '</b> (<a href="mailto:' . $contract->acceptance_email . '">' . $contract->acceptance_email . '</a>)',
                                                                                '<b>' . _dt($contract->acceptance_date) . '</b>',
                                                                                '<b>' . $contract->acceptance_ip . '</b>')
                                                                        ); ?>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                            <div class="col-md-12 text-right _buttons">

                                                                <div class="btn-group">
                                                                    <button class="btn btn-default btn-sm dropdown-toggle"
                                                                            type="button" data-toggle="dropdown"
                                                                            aria-haspopup="true"
                                                                            aria-expanded="false">
                                                                        <i class="fa fa-file-pdf-o s-4"></i><?php if (is_mobile()) {
                                                                            echo ' PDF';
                                                                        } ?>
                                                                    </button>
                                                                    <div class="dropdown-menu dropdown-menu-right">
                                                                        <a class="dropdown-item"
                                                                           href="<?php echo admin_url('contracts/pdf/' . $contract->id . '?output_type=I'); ?>"><?php echo _l('view_pdf'); ?></a>
                                                                        <a class="dropdown-item"
                                                                           href="<?php echo admin_url('contracts/pdf/' . $contract->id . '?output_type=I'); ?>"
                                                                           target="_blank"><?php echo _l('view_pdf_in_new_window'); ?></a>
                                                                        <a class="dropdown-item"
                                                                           href="<?php echo admin_url('contracts/pdf/' . $contract->id); ?>"><?php echo _l('download'); ?></a>
                                                                        <a class="dropdown-item"
                                                                           href="<?php echo admin_url('contracts/pdf/' . $contract->id . '?print=true'); ?>"
                                                                           target="_blank"><?php echo _l('print'); ?></a>
                                                                    </div>
                                                                </div><!-- /btn-group -->

                                                                <div class="btn-group" data-toggle="tooltip"
                                                                     data-title="<?php echo _l('contract_send_to_email'); ?>"
                                                                     data-placement="bottom">
                                                                    <button class="btn btn-default btn-sm dropdown-toggle"
                                                                            type="button"
                                                                            data-target="#contract_send_to_client_modal"
                                                                            data-toggle="modal">
                                                                        <i class="fa fa-envelope s-4"></i>
                                                                    </button>
                                                                </div>

                                                                <div class="btn-group">
                                                                    <button class="btn btn-default btn-sm dropdown-toggle"
                                                                            type="button" data-toggle="dropdown"
                                                                            aria-haspopup="true"
                                                                            aria-expanded="false">
                                                                        <?php echo _l('more'); ?> <span
                                                                                class="caret">
                                                                    </button>
                                                                    <div class="dropdown-menu dropdown-menu-right">
                                                                        <a class="dropdown-item"
                                                                           href="<?php echo site_url('contract/' . $contract->id . '/' . $contract->hash); ?>"
                                                                           target="_blank"><?php echo _l('view_contract'); ?></a>
                                                                        <?php if (has_permission('contracts', '', 'create')) { ?>
                                                                            <a class="dropdown-item"
                                                                               href="<?php echo admin_url('contracts/copy/' . $contract->id); ?>"><?php echo _l('contract_copy'); ?></a>
                                                                        <?php } ?>
                                                                        <?php if ($contract->signed == 1 && has_permission('contracts', '', 'delete')) { ?>
                                                                            <a class="dropdown-item"
                                                                               href="<?php echo admin_url('contracts/clear_signature/' . $contract->id); ?>"><?php echo _l('clear_signature'); ?></a>
                                                                        <?php } ?>
                                                                        <?php if (has_permission('contracts', '', 'delete')) { ?>
                                                                            <a class="dropdown-item"
                                                                               href="<?php echo admin_url('contracts/delete/' . $contract->id); ?>"><?php echo _l('delete'); ?></a>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div><!-- /btn-group -->


                                                            </div>
                                                            <div class="col-md-12">
                                                                <?php if (isset($contract_merge_fields)) { ?>
                                                                    <hr class="hr-panel-heading"/>
                                                                    <p class="bold mtop10 text-right"><a href="#"
                                                                                                         onclick="slideToggle('.avilable_merge_fields'); return false;"><?php echo _l('available_merge_fields'); ?></a>
                                                                    </p>
                                                                    <div class=" avilable_merge_fields mtop15 hide">
                                                                        <ul class="list-group">
                                                                            <?php
                                                                            foreach ($contract_merge_fields as $field) {
                                                                                foreach ($field as $f) {
                                                                                    if (strpos($f['key'], 'statement_') === FALSE && strpos($f['key'], 'password') === FALSE && strpos($f['key'], 'email_signature') === FALSE) {
                                                                                        echo '<li class="list-group-item"><b>' . $f['name'] . '</b>  <a href="#" class="pull-right" onclick="insert_merge_field(this); return false">' . $f['key'] . '</a></li>';
                                                                                    }
                                                                                }
                                                                            } ?>
                                                                        </ul>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                        <hr class="hr-panel-heading"/>
                                                        <div class="editable tc-content"
                                                             style="border:1px solid #d2d2d2;min-height:70px; border-radius:4px;">
                                                            <?php
                                                            if (empty($contract->content)) {
                                                                echo do_action('new_contract_default_content', '<span class="text-danger text-uppercase mtop15 editor-add-content-notice"> ' . _l('click_to_add_content') . '</span>');
                                                            } else {
                                                                echo $contract->content;
                                                            }
                                                            ?>
                                                        </div>
                                                        <?php if (!empty($contract->signature)) { ?>
                                                            <div class="row mtop25">
                                                                <div class="col-md-6">
                                                                </div>
                                                                <div class="col-md-6 text-right">
                                                                    <p class="bold"><?php echo _l('document_customer_signature_text'); ?>
                                                                        <?php if ($contract->signed == 1 && has_permission('contracts', '', 'delete')) { ?>
                                                                            <a href="<?php echo admin_url('contracts/clear_signature/' . $contract->id); ?>"
                                                                               data-toggle="tooltip"
                                                                               title="<?php echo _l('clear_signature'); ?>"
                                                                               class="_delete text-danger">
                                                                                <i class="fa fa-remove"></i>
                                                                            </a>
                                                                        <?php } ?>
                                                                    </p>
                                                                    <img src="<?php echo site_url('download/preview_image?path=' . protected_file_url_by_path(get_upload_path_by_type('contract') . $contract->id . '/' . $contract->signature)); ?>"
                                                                         alt="">
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div role="tabpanel" class="tab-pane fade" id="tab_comments">
                                                        <div class="row contract-comments mtop15">
                                                            <div class="col-md-12">
                                                                <div id="contract-comments"></div>
                                                                <div class="clearfix"></div>
                                                                <hr class="hr-panel-heading"/>
                                                                <div class="form-group no-padding-top">
                                                                <textarea name="content" id="comment" rows="4"
                                                                          class="form-control mtop15 contract-comment mb-4"></textarea>
                                                                </div>
                                                                <button type="button"
                                                                        class="btn btn-info pull-right"
                                                                        onclick="add_contract_comment();"><?php echo _l('proposal_add_comment'); ?></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div role="tabpanel"
                                                         class="tab-pane fade<?php if ($this->input->get('tab') == 'attachments') {
                                                             echo ' active in';
                                                         } ?>" id="attachments">
                                                        <?php echo form_open(admin_url('contracts/add_contract_attachment/' . $contract->id), array('id' => 'contract-attachments-form', 'class' => 'dropzone')); ?>
                                                        <?php echo form_close(); ?>
                                                        <div class="text-right mtop15">
                                                            <div id="dropbox-chooser"></div>
                                                        </div>
                                                        <div id="contract_attachments">
                                                            <?php
                                                            $data = '<div class="row">';
                                                            foreach ($contract->attachments as $attachment) {
                                                                $href_url = site_url('download/file/contract/' . $attachment['attachment_key']);
                                                                if (!empty($attachment['external'])) {
                                                                    $href_url = $attachment['external_link'];
                                                                }
                                                                $data .= '<div class="col-12">';
                                                                $data .= '<div class="col-md-10">';
                                                                $data .= '<div class="pull-left"><i class="' . get_mime_class($attachment['filetype']) . '"></i></div>';
                                                                $data .= '<a href="' . $href_url . '">' . $attachment['file_name'] . '</a>';
                                                                $data .= '<p class="text-muted">' . $attachment["filetype"] . '</p>';
                                                                $data .= '</div>';
                                                                $data .= '<div class="col-md-2 text-right">';
                                                                if ($attachment['staffid'] == get_staff_user_id() || is_admin()) {
                                                                    $data .= '<a href="#"  onclick="delete_contract_attachment(this,' . $attachment['id'] . '); return false;"><i class="fa fa fa-times text-danger"></i></a>';
                                                                }
                                                                $data .= '</div>';
                                                                $data .= '<div class="clearfix"></div><hr class="mb-4"/>';
                                                                $data .= '</div>';
                                                            }
                                                            $data .= '</div>';
                                                            echo $data;
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div role="tabpanel"
                                                         class="tab-pane fade<?php if ($this->input->get('tab') == 'renewals') {
                                                             echo ' active in';
                                                         } ?>" id="renewals">
                                                        <?php if (has_permission('contracts', '', 'create') || has_permission('contracts', '', 'edit')) { ?>
                                                            <div class="_buttons mb-4">
                                                                <a href="#" class="btn btn-default"
                                                                   data-toggle="modal"
                                                                   data-target="#renew_contract_modal">
                                                                    <i class="fa fa-refresh s-4"></i> <?php echo _l('contract_renew_heading'); ?>
                                                                </a>
                                                            </div>
                                                            <hr class="mt-2 mb-4"/>
                                                        <?php } ?>
                                                        <div class="clearfix"></div>
                                                        <?php
                                                        if (count($contract_renewal_history) == 0) {
                                                            echo _l('no_contract_renewals_found');
                                                        }
                                                        foreach ($contract_renewal_history as $renewal) { ?>
                                                            <div class="display-block">
                                                                <div class="media-body">
                                                                    <div class="display-block">
                                                                        <b>
                                                                            <?php
                                                                            echo _l('contract_renewed_by', $renewal['renewed_by']);
                                                                            ?>
                                                                        </b>
                                                                        <?php if ($renewal['renewed_by_staff_id'] == get_staff_user_id() || is_admin()) { ?>
                                                                            <a href="<?php echo admin_url('contracts/delete_renewal/' . $renewal['id'] . '/' . $renewal['contractid']); ?>"
                                                                               class="pull-right _delete"><i
                                                                                        class="fa fa-remove text-danger"></i></a>
                                                                            <br/>
                                                                        <?php } ?>
                                                                        <small class="text-muted"><?php echo _dt($renewal['date_renewed']); ?></small>
                                                                        <hr class="hr-10"/>
                                                                        <span class="text-success bold"
                                                                              data-toggle="tooltip"
                                                                              title="<?php echo _l('contract_renewal_old_start_date', _d($renewal['old_start_date'])); ?>">
                                 <?php echo _l('contract_renewal_new_start_date', _d($renewal['new_start_date'])); ?>
                                 </span>
                                                                        <br/>
                                                                        <?php if (is_date($renewal['new_end_date'])) {
                                                                            $tooltip = '';
                                                                            if (is_date($renewal['old_end_date'])) {
                                                                                $tooltip = _l('contract_renewal_old_end_date', _d($renewal['old_end_date']));
                                                                            }
                                                                            ?>
                                                                            <span class="text-success bold"
                                                                                  data-toggle="tooltip"
                                                                                  title="<?php echo $tooltip; ?>">
                                 <?php echo _l('contract_renewal_new_end_date', _d($renewal['new_end_date'])); ?>
                                 </span>
                                                                            <br/>
                                                                        <?php } ?>
                                                                        <?php if ($renewal['new_value'] > 0) {
                                                                            $contract_renewal_value_tooltip = '';
                                                                            if ($renewal['old_value'] > 0) {
                                                                                $contract_renewal_value_tooltip = ' data-toggle="tooltip" data-title="' . _l('contract_renewal_old_value', _format_number($renewal['old_value'])) . '"';
                                                                            } ?>
                                                                            <span class="text-success bold"<?php echo $contract_renewal_value_tooltip; ?>>
                                 <?php echo _l('contract_renewal_new_value', _format_number($renewal['new_value'])); ?>
                                 </span>
                                                                            <br/>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                                <hr/>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div role="tabpanel" class="tab-pane fade"
                                                         id="tab_emails_tracking">
                                                        <?php
                                                        $this->load->view('admin/includes_fuse/emails_tracking', array(
                                                                'tracked_emails' =>
                                                                    get_tracked_emails($contract->id, 'contract'))
                                                        );
                                                        ?>
                                                    </div>
                                                    <div role="tabpanel" class="tab-pane fade" id="tab_tasks">
                                                        <?php init_relation_tasks_table(array('data-new-rel-id' => $contract->id, 'data-new-rel-type' => 'contract')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</main>


<?php init_tail(); ?>
<?php if (isset($contract)) { ?>
    <!-- init table tasks -->
    <script>
        var contract_id = '<?php echo $contract->id; ?>';
    </script>
    <?php $this->load->view('admin/contracts/send_to_client'); ?>
    <?php $this->load->view('admin/contracts/renew_contract'); ?>
<?php } ?>
<?php $this->load->view('admin/contracts/contract_type'); ?>
<script>
    Dropzone.autoDiscover = false;
    $(function () {

        if ($('#contract-attachments-form').length > 0) {
            new Dropzone("#contract-attachments-form", $.extend({}, _dropzone_defaults(), {
                success: function (file) {
                    if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                        var location = window.location.href;
                        window.location.href = location.split('?')[0] + '?tab=attachments';
                    }
                }
            }));
        }

        // In case user expect the submit btn to save the contract content
        $('#contract-form').on('submit', function () {
            $('#inline-editor-save-btn').click();
            return true;
        });

        if (typeof (Dropbox) != 'undefined' && $('#dropbox-chooser').length > 0) {
            document.getElementById("dropbox-chooser").appendChild(Dropbox.createChooseButton({
                success: function (files) {
                    $.post(admin_url + 'contracts/add_external_attachment', {
                        files: files,
                        contract_id: contract_id,
                        external: 'dropbox'
                    }).done(function () {
                        var location = window.location.href;
                        window.location.href = location.split('?')[0] + '?tab=attachments';
                    });
                },
                linkType: "preview",
                extensions: app_allowed_files.split(','),
            }));
        }

        _validate_form($('#contract-form'), {
            client: 'required',
            datestart: 'required',
            subject: 'required'
        });
        _validate_form($('#renew-contract-form'), {
            new_start_date: 'required'
        });

        var _templates = [];
        $.each(contractsTemplates, function (i, template) {
            _templates.push({
                url: admin_url + 'contracts/get_template?name=' + template,
                title: template
            });
        });

        var editor_settings = {
            selector: 'div.editable',
            inline: true,
            theme: 'inlite',
            relative_urls: false,
            remove_script_host: false,
            inline_styles: true,
            verify_html: false,
            cleanup: false,
            apply_source_formatting: false,
            valid_elements: '+*[*]',
            valid_children: "+body[style], +style[type]",
            file_browser_callback: elFinderBrowser,
            table_default_styles: {
                width: '100%'
            },
            fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
            pagebreak_separator: '<p pagebreak="true"></p>',
            plugins: [
                'advlist pagebreak autolink autoresize lists link image charmap hr',
                'searchreplace visualblocks visualchars code',
                'media nonbreaking table contextmenu',
                'paste textcolor colorpicker'
            ],
            autoresize_bottom_margin: 50,
            insert_toolbar: 'image media quicktable | bullist numlist | h2 h3 | hr',
            selection_toolbar: 'save_button bold italic underline superscript | forecolor backcolor link | alignleft aligncenter alignright alignjustify | fontselect fontsizeselect h2 h3',
            contextmenu: "image media inserttable | cell row column deletetable | paste pastetext searchreplace | visualblocks pagebreak charmap | code",
            setup: function (editor) {

                editor.addCommand('mceSave', function () {
                    save_contract_content(true);
                });

                editor.addShortcut('Meta+S', '', 'mceSave');

                editor.on('MouseLeave blur', function () {
                    if (tinymce.activeEditor.isDirty()) {
                        save_contract_content();
                    }
                });

                editor.on('MouseDown ContextMenu', function () {
                    if (!is_mobile() && !$('.left-column').hasClass('hide')) {
                        contract_full_view();
                    }
                });

                editor.on('blur', function () {
                    $.Shortcuts.start();
                });


                editor.on('focus', function () {
                    $.Shortcuts.stop();
                });

            }
        }

        if (_templates.length > 0) {
            editor_settings.templates = _templates;
            editor_settings.plugins[3] = 'template ' + editor_settings.plugins[3];
            editor_settings.contextmenu = editor_settings.contextmenu.replace('inserttable', 'inserttable template');
        }

        if (is_mobile()) {

            editor_settings.theme = 'modern';
            editor_settings.mobile = {};
            editor_settings.mobile.theme = 'mobile';
            editor_settings.mobile.toolbar = _tinymce_mobile_toolbar();

            editor_settings.inline = false;
            window.addEventListener("beforeunload", function (event) {
                if (tinymce.activeEditor.isDirty()) {
                    save_contract_content();
                }
            });
        }

        tinymce.init(editor_settings);

    });

    function save_contract_content(manual) {
        var editor = tinyMCE.activeEditor;
        var data = {};
        data.contract_id = contract_id;
        data.content = editor.getContent();
        $.post(admin_url + 'contracts/save_contract_data', data).done(function (response) {
            response = JSON.parse(response);
            if (typeof (manual) != 'undefined') {
                // Show some message to the user if saved via CTRL + S
                alert_float('success', response.message);
            }
            // Invokes to set dirty to false
            editor.save();
        }).fail(function (error) {
            var response = JSON.parse(error.responseText);
            alert_float('danger', response.message);
        });
    }

    function delete_contract_attachment(wrapper, id) {
        if (confirm_delete()) {
            $.get(admin_url + 'contracts/delete_contract_attachment/' + id, function (response) {
                if (response.success == true) {
                    $(wrapper).parents('.contract-attachment-wrapper').remove();
                } else {
                    alert_float('danger', response.message);
                }
            }, 'json');
        }
        return false;
    }

    function insert_merge_field(field) {
        var key = $(field).text();
        tinymce.activeEditor.execCommand('mceInsertContent', false, key);
    }

    function contract_full_view() {
        $('#left_column').toggleClass('hide');
        $('#right_column').toggleClass('col-md-7');
        $('#right_column').toggleClass('col-md-12');
        $(window).trigger('resize');
    }

    function add_contract_comment() {
        var comment = $('#comment').val();
        if (comment == '') {
            return;
        }
        var data = {};
        data.content = comment;
        data.contract_id = contract_id;
        $('body').append('<div class="dt-loader"></div>');
        $.post(admin_url + 'contracts/add_comment', data).done(function (response) {
            response = JSON.parse(response);
            $('body').find('.dt-loader').remove();
            if (response.success == true) {
                $('#comment').val('');
                get_contract_comments();
            }
        });
    }

    function get_contract_comments() {
        if (typeof (contract_id) == 'undefined') {
            return;
        }
        requestGet('contracts/get_comments/' + contract_id).done(function (response) {
            $('#contract-comments').html(response);
        });
    }

    function remove_contract_comment(commentid) {
        if (confirm_delete()) {
            requestGetJSON('contracts/remove_comment/' + commentid).done(function (response) {
                if (response.success == true) {
                    $('[data-commentid="' + commentid + '"]').remove();
                }
            });
        }
    }

    function edit_contract_comment(id) {
        var content = $('body').find('[data-contract-comment-edit-textarea="' + id + '"] textarea').val();
        if (content != '') {
            $.post(admin_url + 'contracts/edit_comment/' + id, {
                content: content
            }).done(function (response) {
                response = JSON.parse(response);
                if (response.success == true) {
                    alert_float('success', response.message);
                    $('body').find('[data-contract-comment="' + id + '"]').html(nl2br(content));
                }
            });
            toggle_contract_comment_edit(id);
        }
    }

    function toggle_contract_comment_edit(id) {
        $('body').find('[data-contract-comment="' + id + '"]').toggleClass('hide');
        $('body').find('[data-contract-comment-edit-textarea="' + id + '"]').toggleClass('hide');
    }

</script>
</body>
</html>

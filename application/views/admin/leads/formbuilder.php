<?php init_single_head(); ?>
<?php if (isset($form)) {
    echo form_hidden('form_id', $form->id);
} ?>
<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/form-builder.css'); ?>">
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content custom-scrollbar">

                <div id="form-builder" class="page-layout simple left-sidebar-floating">

                    <div class="page-content p-4 p-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <?php if (isset($form)) {
                                    ?>
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li role="presentation" class="nav-item active">
                                            <a href="#tab_form_build" aria-controls="tab_form_build" role="tab"
                                               class="nav-link"
                                               data-toggle="tab">
                                                <?php echo _l('form_builder'); ?>
                                            </a>
                                        </li>
                                        <li role="presentation" class="nav-item">
                                            <a href="#tab_form_information" aria-controls="tab_form_information"
                                               role="tab" class="nav-link"
                                               data-toggle="tab">
                                                <?php echo _l('form_information'); ?>
                                            </a>
                                        </li>
                                        <li role="presentation" class="nav-item">
                                            <a href="#tab_form_integration" aria-controls="tab_form_integration"
                                               role="tab" class="nav-link"
                                               data-toggle="tab">
                                                <?php echo _l('form_integration_code'); ?>
                                            </a>
                                        </li>
                                    </ul>
                                    <?php
                                } ?>
                                <div class="tab-content mt-4">
                                    <?php if (isset($form)) {
                                        ?>
                                        <div role="tabpanel" class="tab-pane active" id="tab_form_build">
                                            <div id="build-wrap"></div>
                                        </div>
                                        <div role="tabpanel" class="tab-pane" id="tab_form_integration">
                                            <p><?php echo _l('form_integration_code_help'); ?></p>
                                            <textarea readonly class="form-control bg-secondary-50 p-2" rows="3"
                                                      style="white-space: nowrap">
                                                <iframe width="600" height="850"
                                                        src="<?php echo site_url('forms/wtl/' . $form->form_key); ?>"
                                                        frameborder="0" allowfullscreen></iframe>
                                            </textarea>
                                        </div>
                                        <?php
                                    } ?>
                                    <div role="tabpanel" class="tab-pane<?php if (!isset($form)) {
                                        echo ' active';
                                    } ?>" id="tab_form_information">
                                        <?php if (!isset($form)) {
                                            ?>
                                            <h6 class="font-medium-xs bold no-mtop"><?php echo _l('form_builder_create_form_first'); ?></h6>
                                            <hr/>
                                            <?php
                                        } ?>
                                        <?php echo form_open($this->uri->uri_string(), array('id' => 'form_info')); ?>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?php $value = (isset($form) ? $form->name : ''); ?>
                                                <?php echo render_input('name', 'form_name', $value); ?>
                                                <?php
                                                if (get_option('recaptcha_secret_key') != '' && get_option('recaptcha_site_key') != '') {
                                                    ?>
                                                    <div class="form-group">
                                                        <label for=""><?php echo _l('form_recaptcha'); ?></label><br/>
                                                        <div class="radio radio-inline radio-danger">
                                                            <input type="radio" name="recaptcha" id="racaptcha_0"
                                                                   value="0"<?php if (isset($form) && $form->recaptcha == 0 || !isset($form)) {
                                                                echo ' checked';
                                                            } ?>>
                                                            <label for="recaptcha_0"><?php echo _l('settings_no'); ?></label>
                                                        </div>
                                                        <div class="radio radio-inline radio-success">
                                                            <input type="radio" name="recaptcha" id="recaptcha_1"
                                                                   value="1"<?php if (isset($form) && $form->recaptcha == 1) {
                                                                echo ' checked';
                                                            } ?>>
                                                            <label for="recaptcha_1"><?php echo _l('settings_yes'); ?></label>
                                                        </div>
                                                    </div>
                                                    <?php
                                                } ?>
                                                <div class="form-group select-placeholder">
                                                    <label for="language" class="control-label"><i
                                                                class="fa fa-question-circle"
                                                                data-toggle="tooltip"
                                                                data-title="<?php echo _l('form_lang_validation_help'); ?>"></i> <?php echo _l('form_lang_validation'); ?>
                                                    </label>
                                                    <select name="language" id="language"
                                                            class="form-control selectpicker"
                                                            data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                                        <option value=""></option>
                                                        <?php foreach ($languages as $language) {
                                                            ?>
                                                            <option value="<?php echo $language; ?>"<?php if ((isset($form) && $form->language == $language) || (!isset($form) && get_option('active_language') == $language)) {
                                                                echo ' selected';
                                                            } ?>><?php echo ucfirst($language); ?></option>
                                                            <?php
                                                        } ?>
                                                    </select>
                                                </div>
                                                <?php $value = (isset($form) ? $form->submit_btn_name : 'Submit'); ?>
                                                <?php echo render_input('submit_btn_name', 'form_btn_submit_text', $value); ?>
                                                <?php $value = (isset($form) ? $form->success_submit_msg : ''); ?>
                                                <?php echo render_textarea('success_submit_msg', 'form_success_submit_msg', $value); ?>


                                                <div class="form-check mt-4">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" name="mark_public"
                                                               class="form-check-input"
                                                               id="mark_public" <?php if (isset($form) && $form->mark_public == 1) {
                                                            echo 'checked';
                                                        } ?>>
                                                        <span class="checkbox-icon"></span>
                                                        <span><?php echo _l('auto_mark_as_public'); ?></span>
                                                    </label>
                                                </div>

                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" name="allow_duplicate"
                                                               class="form-check-input"
                                                               id="allow_duplicate" <?php if (isset($form) && $form->allow_duplicate == 1 || !isset($form)) {
                                                            echo 'checked';
                                                        } ?>>
                                                        <span class="checkbox-icon"></span>
                                                        <span><?php echo _l('form_allow_duplicate', _l('leads')); ?></span>
                                                    </label>
                                                </div>

                                                <div class="duplicate-settings-wrapper row<?php if (isset($form) && $form->allow_duplicate == 1 || !isset($form)) {
                                                    echo ' hide';
                                                } ?>">
                                                    <div class="col-md-12">
                                                        <hr class="mt-4 mb-4"/>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="track_duplicate_field"><?php echo _l('track_duplicate_by_field'); ?></label><br/>
                                                            <select class="selectpicker track_duplicate_field"
                                                                    data-width="100%"
                                                                    name="track_duplicate_field"
                                                                    id="track_duplicate_field"
                                                                    data-none-selected-text="">
                                                                <option value=""></option>
                                                                <?php foreach ($db_fields as $field) {
                                                                    ?>
                                                                    <option value="<?php echo $field->name; ?>"<?php if (isset($form) && $form->track_duplicate_field == $field->name) {
                                                                        echo ' selected';
                                                                    }
                                                                    if (isset($form) && $form->track_duplicate_field_and == $field->name) {
                                                                        echo 'disabled';
                                                                    } ?>><?php echo $field->label; ?></option>
                                                                    <?php
                                                                } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="track_duplicate_field_and"><?php echo _l('and_track_duplicate_by_field'); ?></label><br/>
                                                            <select class="selectpicker track_duplicate_field_and"
                                                                    data-width="100%" name="track_duplicate_field_and"
                                                                    id="track_duplicate_field_and"
                                                                    data-none-selected-text="">
                                                                <option value=""></option>
                                                                <?php foreach ($db_fields as $field) {
                                                                    ?>
                                                                    <option value="<?php echo $field->name; ?>"<?php if (isset($form) && $form->track_duplicate_field_and == $field->name) {
                                                                        echo ' selected';
                                                                    }
                                                                    if (isset($form) && $form->track_duplicate_field == $field->name) {
                                                                        echo 'disabled';
                                                                    } ?>><?php echo $field->label; ?></option>
                                                                    <?php
                                                                } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">

                                                        <div class="form-check mt-4">
                                                            <label class="form-check-label">
                                                                <input type="checkbox" name="create_task_on_duplicate"
                                                                       class="form-check-input"
                                                                       id="create_task_on_duplicate" <?php if (isset($form) && $form->create_task_on_duplicate == 1) {
                                                                    echo 'checked';
                                                                } ?>>
                                                                <span class="checkbox-icon"></span>
                                                                <span><i class="fa fa-question-circle line-height-25 ml-2"
                                                                         data-toggle="tooltip"
                                                                         data-title="<?php echo _l('create_the_duplicate_form_data_as_task_help'); ?>"></i> <?php echo _l('create_the_duplicate_form_data_as_task', _l('lead_lowercase')); ?></span>
                                                            </label>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <?php

                                                echo render_leads_source_select($sources, (isset($form) ? $form->lead_source : get_option('leads_default_source')), 'lead_import_source', 'lead_source');

                                                echo render_leads_status_select($statuses, (isset($form) ? $form->lead_status : get_option('leads_default_status')), 'lead_import_status', 'lead_status');

                                                $selected = '';
                                                foreach ($members as $staff) {
                                                    if (isset($form) && $form->responsible == $staff['staffid']) {
                                                        $selected = $staff['staffid'];
                                                    }
                                                }
                                                ?>
                                                <?php echo render_select('responsible', $members, array('staffid', array('firstname', 'lastname')), 'leads_import_assignee', $selected); ?>
                                                <hr class="mt-4 mb-4"/>
                                                <label class="control-label"><?php echo _l('notification_settings'); ?></label>
                                                <div class="clearfix"></div>

                                                <div class="form-check ">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" name="notify_lead_imported"
                                                               class="form-check-input"
                                                               id="notify_lead_imported" <?php if (isset($form) && $form->notify_lead_imported == 1 || !isset($form)) {
                                                            echo 'checked';
                                                        } ?>>
                                                        <span class="checkbox-icon"></span>
                                                        <span class="form-check-description"><?php echo _l('leads_email_integration_notify_when_lead_imported'); ?></span>
                                                    </label>
                                                </div>

                                                <div class="select-notification-settings<?php if (isset($form) && $form->notify_lead_imported == '0') {
                                                    echo ' hide';
                                                } ?>">

                                                    <hr class="mt-4 mb-4"/>

                                                    <div class="form-check form-check-inline">
                                                        <label class="form-check-label">
                                                            <input type="radio" name="notify_type"
                                                                   class="form-check-input ays-ignore"
                                                                   value="specific_staff"
                                                                   id="specific_staff" <?php if (isset($form) && $form->notify_type == 'specific_staff' || !isset($form)) {
                                                                echo 'checked';
                                                            } ?>>
                                                            <span class="radio-icon"></span>
                                                            <span class="form-check-description"><?php echo _l('specific_staff_members'); ?></span>
                                                        </label>
                                                    </div>

                                                    <div class="form-check form-check-inline">
                                                        <label class="form-check-label">
                                                            <input type="radio" name="notify_type"
                                                                   class="form-check-input ays-ignore" id="roles"
                                                                   value="roles" <?php if (isset($form) && $form->notify_type == 'roles') {
                                                                echo 'checked';
                                                            } ?>>
                                                            <span class="radio-icon"></span>
                                                            <span class="form-check-description"><?php echo _l('staff_with_roles'); ?></span>
                                                        </label>
                                                    </div>

                                                    <div class="form-check form-check-inline">
                                                        <label class="form-check-label">
                                                            <input type="radio" class="form-check-input ays-ignore"
                                                                   name="notify_type" id="assigned"
                                                                   value="assigned" <?php if (isset($form) && $form->notify_type == 'assigned') {
                                                                echo 'checked';
                                                            } ?>>
                                                            <span class="radio-icon"></span>
                                                            <span class="form-check-description"><?php echo _l('notify_assigned_user'); ?></span>
                                                        </label>
                                                    </div>

                                                    <div class="clearfix mtop15"></div>
                                                    <div id="specific_staff_notify"
                                                         class="<?php if (isset($form) && $form->notify_type != 'specific_staff') {
                                                             echo 'hide';
                                                         } ?>">
                                                        <?php
                                                        $selected = array();
                                                        if (isset($form) && $form->notify_type == 'specific_staff') {
                                                            $selected = unserialize($form->notify_ids);
                                                        }
                                                        ?>
                                                        <?php echo render_select('notify_ids_staff[]', $members, array('staffid', array('firstname', 'lastname')), 'leads_email_integration_notify_staff', $selected, array('multiple' => true)); ?>
                                                    </div>
                                                    <div id="role_notify"
                                                         class="<?php if (isset($form) && $form->notify_type != 'roles' || !isset($form)) {
                                                             echo 'hide';
                                                         } ?>">
                                                        <?php
                                                        $selected = array();
                                                        if (isset($form) && $form->notify_type == 'roles') {
                                                            $selected = unserialize($form->notify_ids);
                                                        }
                                                        ?>
                                                        <?php echo render_select('notify_ids_roles[]', $roles, array('roleid', array('name')), 'leads_email_integration_notify_roles', $selected, array('multiple' => true)); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php echo form_close(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="btn-bottom-pusher"></div>
            </div>

            <nav id="footer" class="bg-white text-auto row no-gutters align-items-center px-6">
                <div class="col-md-12">
                    <button id="save_form_builer"
                            class="btn btn-secondary text-capitalize pull-right ml-4"><?php echo _l('submit'); ?></button>
                </div>
            </nav>
        </div>
    </div>
</main>
<?php init_tail(); ?>
<script src="<?php echo base_url('assets-old/plugins/form-builder/form-builder.min.js'); ?>"></script>
<script>
    var buildWrap = document.getElementById('build-wrap');
    var formData = <?php echo json_encode($formData); ?>;
</script>
<?php $this->load->view('admin/includes_fuse/_form_js_formatter'); ?>
<script>

    $(function () {

        var formBuilder = $(buildWrap).formBuilder(fbOptions);
        var $create_task_on_duplicate = $('#create_task_on_duplicate');

        $('#allow_duplicate').on('change', function () {
            $('.duplicate-settings-wrapper').toggleClass('hide');
        });

        $('#notify_lead_imported').on('change', function () {
            $('.select-notification-settings').toggleClass('hide');
        });

        $('#track_duplicate_field,#track_duplicate_field_and').on('change', function () {
            var selector = ($(this).hasClass('track_duplicate_field') ? 'track_duplicate_field_and' : 'track_duplicate_field')
            $('#' + selector + ' option').removeAttr('disabled', true);
            $('#' + selector + ' option[value="' + $(this).val() + '"]').attr('disabled', true);
            $('#' + selector + '').selectpicker('refresh');
        });

        setTimeout(function () {
            $(".form-builder-save").wrap("<div class='btn-bottom-toolbar text-right'></div>");
            $btnToolbar = $('body').find('#tab_form_build .btn-bottom-toolbar');
            $btnToolbar = $('#tab_form_build').append($btnToolbar);
            $btnToolbar.find('.btn').addClass('btn-info');
        }, 100);

        // $('body').on('click', '#save_form_builer', function (e) {
        //     $.post(admin_url + 'leads/save_form_data', {
        //         formData: formBuilder.formData,
        //         id: $('input[name="form_id"]').val()
        //     }).done(function (response) {
        //         response = JSON.parse(response);
        //         if (response.success == true) {
        //             alert_float('success', response.message);
        //         }
        //     });
        // });

        _validate_form('#form_info', {
            name: 'required',
            lead_source: 'required',
            lead_status: 'required',
            language: 'required',
            success_submit_msg: 'required',
            submit_btn_name: 'required',
            responsible: {
                required: {
                    depends: function (element) {
                        var isRequiredByNotifyType = ($('input[name="notify_type"]:checked').val() == 'assigned') ? true : false;
                        var isRequiredByAssignTask = ($create_task_on_duplicate.is(':checked')) ? true : false;
                        var isRequired = isRequiredByNotifyType || isRequiredByAssignTask;
                        if (isRequired) {
                            $('[for="responsible"]').find('.req').removeClass('hide');
                        } else {
                            $(element).next('p.text-danger').remove();
                            $('[for="responsible"]').find('.req').addClass('hide');
                        }
                        return isRequired;
                    }
                }
            }
        });

        var $notifyTypeInput = $('input[name="notify_type"]');
        $notifyTypeInput.on('change', function () {
            $('#form_info').validate().checkForm()
        });
        $notifyTypeInput.trigger('change');

        $create_task_on_duplicate.on('change', function () {
            $('#form_info').validate().checkForm()
        });

        $create_task_on_duplicate.trigger('change');

        $("#save_form_builer").on("click", function (e) {
            e.preventDefault();

            if ($('#tab_form_build').hasClass('active') == true) {
                $.post(admin_url + 'leads/save_form_data', {
                    formData: formBuilder.formData,
                    id: $('input[name="form_id"]').val()
                }).done(function (response) {
                    response = JSON.parse(response);
                    if (response.success == true) {
                        alert_float('success', response.message);
                    }
                });
            }

            if ($('#tab_form_information').hasClass('active') == true) {
                $("#form_info").submit();
            }
        });

    });

</script>
</body>
</html>

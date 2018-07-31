<!-- Modal Contact -->
<div class="modal fade" id="contact" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <?php echo form_open('admin/clients/contact/' . $customer_id . '/' . $contactid, array('id' => 'contact-form', 'autocomplete' => 'off')); ?>
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel"><?php echo $title; ?>
                    <small class="color-white">(<?php echo get_company_name($customer_id, true); ?>)</small>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php if (isset($contact)) { ?>
                            <img src="<?php echo contact_profile_image_url($contact->id, 'thumb'); ?>" id="contact-img"
                                 class="client-profile-image-thumb">
                            <?php if (!empty($contact->profile_image)) { ?>
                                <a href="#"
                                   onclick="delete_contact_profile_image(<?php echo $contact->id; ?>); return false;"
                                   class="text-danger pull-right" id="contact-remove-img"><i
                                            class="fa fa-remove"></i></a>
                            <?php } ?>
                            <hr/>
                        <?php } ?>
                    </div>
                    <div class="col-md-6">
                        <div id="contact-profile-image"
                             class="form-group<?php if (isset($contact) && !empty($contact->profile_image)) {
                                 echo ' hide';
                             } ?>">
                            <label for="profile_image"
                                   class="profile-image"><?php echo _l('client_profile_image'); ?></label>
                            <input type="file" name="profile_image" class="form-control" id="profile_image">
                        </div>
                        <?php if (isset($contact)) { ?>
                            <div class="alert alert-warning hide" role="alert" id="contact_proposal_warning">
                                <?php echo _l('proposal_warning_email_change', array(_l('contact_lowercase'), _l('contact_lowercase'), _l('contact_lowercase'))); ?>
                                <hr/>
                                <a href="#" id="contact_update_proposals_emails" data-original-email=""
                                   onclick="update_all_proposal_emails_linked_to_contact(<?php echo $contact->id; ?>); return false;"><?php echo _l('update_proposal_email_yes'); ?></a>
                                <br/>
                                <a href="#"
                                   onclick="close_modal_manually('#contact'); return false;"><?php echo _l('update_proposal_email_no'); ?></a>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group contact-direction-option">
                            <label for="direction"><?php echo _l('document_direction'); ?></label>
                            <select class="selectpicker"
                                    data-none-selected-text="<?php echo _l('system_default_string'); ?>"
                                    data-width="100%" name="direction" id="direction">
                                <option value="" <?php if (isset($contact) && empty($contact->direction)) {
                                    echo 'selected';
                                } ?>></option>
                                <option value="ltr" <?php if (isset($contact) && $contact->direction == 'ltr') {
                                    echo 'selected';
                                } ?>>LTR
                                </option>
                                <option value="rtl" <?php if (isset($contact) && $contact->direction == 'rtl') {
                                    echo 'selected';
                                } ?>>RTL
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <?php echo form_hidden('contactid', $contactid); ?>
                        <?php $value = (isset($contact) ? $contact->firstname : ''); ?>
                        <?php echo render_input('firstname', 'client_firstname', $value); ?>
                    </div>
                    <div class="col-md-6">
                        <?php $value = (isset($contact) ? $contact->lastname : ''); ?>
                        <?php echo render_input('lastname', 'client_lastname', $value); ?>
                    </div>
                    <div class="col-md-6">
                        <?php $value = (isset($contact) ? $contact->title : ''); ?>
                        <?php echo render_input('title', 'contact_position', $value); ?>
                    </div>
                    <div class="col-md-6">
                        <?php $value = (isset($contact) ? $contact->email : ''); ?>
                        <?php echo render_input('email', 'client_email', $value, 'email'); ?>
                    </div>
                    <div class="col-md-6">
                        <?php $value = (isset($contact) ? $contact->phonenumber : ''); ?>
                        <?php echo render_input('phonenumber', 'client_phonenumber', $value, 'text', array('autocomplete' => 'off')); ?>
                    </div>
                    <div class="col-md-6">
                        <!-- fake fields are a workaround for chrome autofill getting the wrong fields -->
                        <input type="text" class="fake-autofill-field hide" name="fakeusernameremembered" value=''
                               tabindex="-1"/>
                        <input type="password" class="fake-autofill-field hide" name="fakepasswordremembered" value=''
                               tabindex="-1"/>

                        <div class="client_password_set_wrapper form-group">
                            <label for="password" class="control-label">
                                <?php echo _l('client_password'); ?>
                            </label>
                            <div class="input-group">

                                <input type="password" class="form-control password" name="password"
                                       autocomplete="false">
                                <span class="input-group-addon">
                                    <a href="#password" class="show_password"
                                       onclick="showPassword('password'); return false;"><i class="fa fa-eye"></i></a>
                                </span>
                                <span class="input-group-addon">
                                    <a href="#" class="generate_password"
                                       onclick="generatePassword(this);return false;">
                                        <i class="fa fa-refresh"></i></a>
                                </span>
                            </div>
                            <?php if (isset($contact)) { ?>
                                <p class="text-muted">
                                    <?php echo _l('client_password_change_populate_note'); ?>
                                </p>
                                <?php if ($contact->last_password_change != NULL) {
                                    echo _l('client_password_last_changed');
                                    echo '<span class="text-has-action" data-toggle="tooltip" data-title="' . _dt($contact->last_password_change) . '"> ' . time_ago($contact->last_password_change) . '</span>';
                                }
                            } ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?php $rel_id = (isset($contact) ? $contact->id : false); ?>
                        <?php echo render_custom_fields('contacts', $rel_id); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <hr class="m-t-15 m-b-15"/>

                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" name="is_primary" id="contact_primary"
                                    <?php if ((!isset($contact) && total_rows('tblcontacts', array('is_primary' => 1, 'userid' => $customer_id)) == 0) || (isset($contact) && $contact->is_primary == 1)) {
                                        echo 'checked';
                                    }; ?> <?php if ((isset($contact) && total_rows('tblcontacts', array('is_primary' => 1, 'userid' => $customer_id)) == 1 && $contact->is_primary == 1)) {
                                    echo 'disabled';
                                } ?>/>
                                <span class="checkbox-icon"></span>
                                <span class="form-check-description"><?php echo _l('contact_primary'); ?></span>
                            </label>
                        </div>
                        <?php if (!isset($contact) && total_rows('tblemailtemplates', array('slug' => 'new-client-created', 'active' => 0)) == 0) { ?>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" name="donotsendwelcomeemail"
                                           id="donotsendwelcomeemail"/>
                                    <span class="checkbox-icon"></span>
                                    <span class="form-check-description"><?php echo _l('client_do_not_send_welcome_email'); ?></span>
                                </label>
                            </div>
                        <?php } ?>
                        <?php if (total_rows('tblemailtemplates', array('slug' => 'contact-set-password', 'active' => 0)) == 0) { ?>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" name="send_set_password_email"
                                           id="send_set_password_email"/>
                                    <span class="checkbox-icon"></span>
                                    <span class="form-check-description"><?php echo _l('client_send_set_password_email'); ?></span>
                                </label>
                            </div>
                        <?php } ?>
                        <hr class="m-t-15 m-b-15"/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h5 class="bold text-muted"><?php echo _l('customer_permissions'); ?></h5>
                        <p class="text-danger"><?php echo _l('contact_permissions_info'); ?></p>
                        <?php
                        $default_contact_permissions = array();
                        if (!isset($contact)) {
                            $default_contact_permissions = @unserialize(get_option('default_contact_permissions'));
                        }
                        foreach ($customer_permissions as $permission) { ?>
                            <div class="row">
                                <div class="col-md-6 m-t-10 border-right">
                                    <span><?php echo $permission['name']; ?></span>
                                </div>
                                <div class="col-md-6 m-t-10">
                                    <div class="onoffswitch">
                                        <label class="switch onoffswitch-label" for="<?php echo $permission['id']; ?>">
                                            <input type="checkbox"
                                                   id="<?php echo $permission['id']; ?>" <?php if (isset($contact) && has_contact_permission($permission['short_name'], $contact->id) || is_array($default_contact_permissions) && in_array($permission['id'], $default_contact_permissions)) {
                                                echo 'checked';
                                            } ?> value="<?php echo $permission['id']; ?>" name="permissions[]">
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="col-md-6">
                        <h5 class="bold text-muted"><?php echo _l('email_notifications'); ?><?php if (is_sms_trigger_active()) {
                                echo '/SMS';
                            } ?></h5>
                        <div id="contact_email_notifications">

                            <div class="row">
                                <div class="col-md-6 m-t-10 border-right">
                                    <span><?php echo _l('invoice'); ?></span>
                                </div>
                                <div class="col-md-6 m-t-10">
                                    <div class="onoffswitch">
                                        <label class="switch onoffswitch-label" for="invoice_emails">
                                            <input type="checkbox" id="invoice_emails" data-perm-id="1"
                                                   class="onoffswitch-checkbox" <?php if (isset($contact) && $contact->invoice_emails == '1') {
                                                echo 'checked';
                                            } ?> value="invoice_emails" name="invoice_emails">
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 m-t-10 border-right">
                                    <span><?php echo _l('estimate'); ?></span>
                                </div>
                                <div class="col-md-6 m-t-10">
                                    <div class="onoffswitch">
                                        <label class="switch onoffswitch-label" for="estimate_emails">
                                            <input type="checkbox" id="estimate_emails" data-perm-id="2"
                                                   class="onoffswitch-checkbox" <?php if (isset($contact) && $contact->estimate_emails == '1') {
                                                echo 'checked';
                                            } ?> value="estimate_emails" name="estimate_emails">
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 m-t-10 border-right">
                                    <span><?php echo _l('credit_note'); ?></span>
                                </div>
                                <div class="col-md-6 m-t-10">
                                    <div class="onoffswitch">
                                        <label class="switch onoffswitch-label" for="credit_note_emails">
                                            <input type="checkbox" id="credit_note_emails" data-perm-id="1"
                                                   class="onoffswitch-checkbox" <?php if (isset($contact) && $contact->credit_note_emails == '1') {
                                                echo 'checked';
                                            } ?> value="credit_note_emails" name="credit_note_emails">
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 m-t-10 border-right">
                                    <span><?php echo _l('project'); ?></span>
                                </div>
                                <div class="col-md-6 m-t-10">
                                    <div class="onoffswitch">
                                        <label class="switch onoffswitch-label" for="project_emails">
                                            <input type="checkbox" id="project_emails" data-perm-id="6"
                                                   class="onoffswitch-checkbox" <?php if (isset($contact) && $contact->project_emails == '1') {
                                                echo 'checked';
                                            } ?> value="project_emails" name="project_emails">
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 m-t-10 border-right">
                                    <span><?php echo _l('tickets'); ?></span>
                                </div>
                                <div class="col-md-6 m-t-10">
                                    <div class="onoffswitch">
                                        <label class="switch onoffswitch-label" for="ticket_emails">
                                            <input type="checkbox" id="ticket_emails" data-perm-id="5"
                                                   class="onoffswitch-checkbox" <?php if (isset($contact) && $contact->ticket_emails == '1') {
                                                echo 'checked';
                                            } ?> value="ticket_emails" name="ticket_emails">
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 m-t-10 border-right">
                                        <span><i class="fa fa-question-circle" data-toggle="tooltip"
                                                 data-title="<?php echo _l('only_project_tasks'); ?>"></i> <?php echo _l('task'); ?></span>
                                </div>
                                <div class="col-md-6 m-t-10">
                                    <div class="onoffswitch">
                                        <label class="switch onoffswitch-label" for="task_emails">
                                            <input type="checkbox" id="task_emails" data-perm-id="6"
                                                   class="onoffswitch-checkbox" <?php if (isset($contact) && $contact->task_emails == '1') {
                                                echo 'checked';
                                            } ?> value="task_emails" name="task_emails">
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6 m-t-10 border-right">
                                    <span><?php echo _l('contract'); ?></span>
                                </div>
                                <div class="col-md-6 m-t-10">
                                    <div class="onoffswitch">
                                        <label class="switch onoffswitch-label" for="contract_emails">
                                            <input type="checkbox" id="contract_emails" data-perm-id="3"
                                                   class="onoffswitch-checkbox" <?php if (isset($contact) && $contact->contract_emails == '1') {
                                                echo 'checked';
                                            } ?> value="contract_emails" name="contract_emails">
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-info" data-loading-text="<?php echo _l('wait_text'); ?>"
                        autocomplete="off" data-form="#contact-form"><?php echo _l('submit'); ?></button>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?php if (!isset($contact)) { ?>
    <script>
        $(function () {
            // Guess auto email notifications based on the default contact permissios
            var permInputs = $('input[name="permissions[]"]');
            $.each(permInputs, function (i, input) {
                input = $(input);
                if (input.prop('checked') === true) {
                    $('#contact_email_notifications [data-perm-id="' + input.val() + '"]').prop('checked', true);
                }
            });
        });
    </script>
<?php } ?>

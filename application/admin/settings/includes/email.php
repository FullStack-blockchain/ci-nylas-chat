<ul class="nav nav-tabs mb-4" role="tablist">
	<li role="presentation" class="nav-item active">
		<a href="#email_config" class="nav-link" aria-controls="email_config" role="tab" data-toggle="tab"><?php echo _l('settings_smtp_settings_heading'); ?></a>
	</li>
	<li role="presentation" class="nav-item">
		<a href="#email_queue" class="nav-link" aria-controls="email_queue" role="tab" data-toggle="tab"><?php echo _l('email_queue'); ?></a>
	</li>
</ul>
<div class="tab-content">
	<div role="tabpanel" class="tab-pane active" id="email_config">
		<!-- fake fields are a workaround for chrome autofill getting the wrong fields -->
		<input type="text" class="fake-autofill-field hidden" name="fakeusernameremembered" value='' tabindex="-1" />
		<input type="password" class="fake-autofill-field hidden" name="fakepasswordremembered" value='' tabindex="-1" />
		<h4 class="f-18"><?php echo _l('settings_smtp_settings_heading'); ?> <small><?php echo _l('settings_smtp_settings_subheading'); ?></small></h4>
		<hr class="mt-4 mb-4"/>
		<div class="form-group">

			<label for="mail_engine"><?php echo _l('mail_engine'); ?></label><br />

            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input type="radio" name="settings[mail_engine]" id="phpmailer" value="phpmailer" <?php if(get_option('mail_engine') == 'phpmailer'){echo 'checked';} ?> class="form-check-input">
                    <span class="radio-icon"></span>
                    <span>PHPMailer</span>
                </label>
            </div>

            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input type="radio" name="settings[mail_engine]" id="codeigniter" value="codeigniter" <?php if(get_option('mail_engine') == 'codeigniter'){echo 'checked';} ?> class="form-check-input">
                    <span class="radio-icon"></span>
                    <span>CodeIgniter</span>
                </label>
            </div>
			<hr class="mt-4 mb-4"/>
			<label for="email_protocol"><?php echo _l('email_protocol'); ?></label><br />

            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input type="radio" name="settings[email_protocol]" id="smtp" value="smtp" <?php if(get_option('email_protocol') == 'smtp'){echo 'checked';} ?> class="form-check-input">
                    <span class="radio-icon"></span>
                    <span>SMTP</span>
                </label>
            </div>

            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input type="radio" name="settings[email_protocol]" id="sendmail" value="sendmail" <?php if(get_option('email_protocol') == 'sendmail'){echo 'checked';} ?> class="form-check-input">
                    <span class="radio-icon"></span>
                    <span>Sendmail</span>
                </label>
            </div>

            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input type="radio" name="settings[email_protocol]" id="mail" value="mail" <?php if(get_option('email_protocol') == 'mail'){echo 'checked';} ?> class="form-check-input">
                    <span class="radio-icon"></span>
                    <span>Mail</span>
                </label>
            </div>

		</div>

		<div class="smtp-fields<?php if(get_option('email_protocol') == 'mail'){echo ' hide'; } ?>">
		<div class="form-group mtop15">
				<label for="smtp_encryption"><?php echo _l('smtp_encryption'); ?></label><br />
				<select name="settings[smtp_encryption]" class="selectpicker" data-width="100%">
					<option value="" <?php if(get_option('smtp_encryption') == ''){echo 'selected';} ?>><?php echo _l('smtp_encryption_none'); ?></option>
					<option value="ssl" <?php if(get_option('smtp_encryption') == 'ssl'){echo 'selected';} ?>>SSL</option>
					<option value="tls" <?php if(get_option('smtp_encryption') == 'tls'){echo 'selected';} ?>>TLS</option>
				</select>
			</div>
		<?php echo render_input('settings[smtp_host]','settings_email_host',get_option('smtp_host')); ?>
		<?php echo render_input('settings[smtp_port]','settings_email_port',get_option('smtp_port')); ?>
		</div>
		<?php echo render_input('settings[smtp_email]','settings_email',get_option('smtp_email')); ?>
		<div class="smtp-fields<?php if(get_option('email_protocol') == 'mail'){echo ' hide'; } ?>">
		<i class="fa fa-question-circle pull-left" data-toggle="tooltip" style="z-index: 1;position: relative;top: 30px;padding-left: 110px" data-title="<?php echo _l('smtp_username_help'); ?>"></i>
		<?php echo render_input('settings[smtp_username]','smtp_username',get_option('smtp_username')); ?>
		<?php
		$ps = get_option('smtp_password');
		if(!empty($ps)){
			if(false == $this->encryption->decrypt($ps)){
				$ps = $ps;
			} else {
				$ps = $this->encryption->decrypt($ps);
			}
		}
		echo render_input('settings[smtp_password]','settings_email_password',$ps,'password',array('autocomplete'=>'off')); ?>
		</div>
		<?php echo render_input('settings[smtp_email_charset]','settings_email_charset',get_option('smtp_email_charset')); ?>
		<?php echo render_input('settings[bcc_emails]','bcc_all_emails',get_option('bcc_emails')); ?>
		<?php echo render_textarea('settings[email_signature]','settings_email_signature',get_option('email_signature')); ?>
		<hr class="mt-4 mb-4"/>
		<?php echo render_textarea('settings[email_header]','email_header',get_option('email_header'),array('rows'=>15)); ?>
		<?php echo render_textarea('settings[email_footer]','email_footer',get_option('email_footer'),array('rows'=>15)); ?>
		<hr class="mt-4 mb-4"/>
		<h4 class="f-16"><?php echo _l('settings_send_test_email_heading'); ?></h4>
		<p class="text-muted"><?php echo _l('settings_send_test_email_subheading'); ?></p>
		<div class="form-group pt-0">
			<div class="input-group">
				<input type="email" class="form-control" name="test_email" data-ays-ignore="true" placeholder="<?php echo _l('settings_send_test_email_string'); ?>">
				<div class="input-group-btn">
					<button type="button" class="btn btn-default test_email p7">Test</button>
				</div>
			</div>
		</div>

	</div>
	<div role="tabpanel" class="tab-pane" id="email_queue">
		<?php render_yes_no_option('email_queue_enabled','email_queue_enabled','To speed up the emailing process, the system will add the emails in queue and will send them via cron job, make sure that the cron job is properly configured in order to use this feature.'); ?>
		<hr class="mt-4 mb-4"/>
		<?php render_yes_no_option('email_queue_skip_with_attachments','email_queue_skip_attachments','Most likely you will encounter problems with the email queue if the system needs to add big files to the queue. If you plan to use this option consult with your server administrator/hosting provider to increase the max_allowed_packet and wait_timeout options in your server config, otherwise when this option is set to yes the system won\'t add emails with attachments in the queue and will be sent immediately.'); ?>
	</div>
</div>

<?php $this->load->view('authentication/includes/head.php'); ?>


<body class="login_admin layout layout-vertical" <?php if (is_rtl()) {
    echo ' dir="rtl"';
} ?>>
<main>
    <div id="wrapper">
        <div class="content-wrapper">
            <div class="content custom-scrollbar">

                <div id="login" class="p-8">

                    <div class="company-logo">
                        <?php get_company_logo(); ?>
                    </div>
                    <div class="form-wrapper md-elevation-8 p-8">
                        <div class="title mt-4 mb-8 text-uppercase"
                             style="font-size: 24px"><?php echo _l('admin_auth_login_heading'); ?></div>

                        <?php $this->load->view('authentication/includes/alerts'); ?>
                        <?php echo form_open($this->uri->uri_string()); ?>
                        <?php echo validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>
                        <?php do_action('after_admin_login_form_start'); ?>

                        <div class="form-group mb-4">
                            <input type="email" class="form-control" name="email" id="email"
                                   aria-describedby="emailHelp" placeholder=" "/>
                            <label for="email"><?php echo _l('admin_auth_login_email'); ?></label>
                        </div>

                        <div class="form-group mb-4">
                            <input type="password" class="form-control" id="password" name="password"
                                   placeholder="Password"/>
                            <label for="password"><?php echo _l('admin_auth_login_password'); ?></label>
                        </div>

                        <div class="remember-forgot-password row no-gutters align-items-center justify-content-between pt-4">
                            <div class="form-check remember-me mb-4">
                                <label class="form-check-label">
                                    <input type="checkbox" id="remember" name="remember" class="form-check-input"/>
                                    <span class="checkbox-icon"></span>
                                    <span class="form-check-description"><?php echo _l('admin_auth_login_remember_me'); ?></span>
                                </label>
                            </div>

                            <a href="<?php echo site_url('authentication/forgot_password'); ?>"
                               class="forgot-password text-secondary mb-4"><?php echo _l('admin_auth_login_fp'); ?></a>
                        </div>

                        <button type="submit" class="submit-button btn btn-block btn-secondary my-4 mx-auto">
                            <?php echo _l('admin_auth_login_button'); ?>
                        </button>

                        <?php if (get_option('recaptcha_secret_key') != '' && get_option('recaptcha_site_key') != '') { ?>
                            <div class="g-recaptcha"
                                 data-sitekey="<?php echo get_option('recaptcha_site_key'); ?>"></div>
                        <?php } ?>
                        <?php do_action('before_admin_login_form_close'); ?>
                        <?php echo form_close(); ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>
</body>

</html>

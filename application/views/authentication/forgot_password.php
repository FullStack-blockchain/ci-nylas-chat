<?php $this->load->view('authentication/includes/head.php'); ?>


<body class="layout layout-vertical forgot-password">
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
                             style="font-size: 24px"><?php echo _l('admin_auth_forgot_password_heading'); ?></div>

                        <?php echo form_open($this->uri->uri_string()); ?>
                        <?php echo validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>
                        <?php $this->load->view('authentication/includes/alerts'); ?>

                        <div class="form-group mb-4">
                            <input type="email" class="form-control" name="email" id="email" placeholder=" "/>
                            <label for="email"><?php echo _l('admin_auth_forgot_password_email'); ?></label>
                        </div>

                        <button type="submit" class="submit-button btn btn-block btn-secondary my-4 mx-auto">
                            <?php echo _l('admin_auth_forgot_password_button'); ?>
                        </button>

                        <?php echo form_close(); ?>

                        <div class="login row align-items-center justify-content-center mt-8 mb-6 mx-auto">
                            <a class="link text-secondary" href="<?php echo site_url('authentication'); ?>"><?php echo _l('back_to_login'); ?></a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>
</body>

</html>

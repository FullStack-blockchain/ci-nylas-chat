<?php $this->load->view('authentication/includes/head.php'); ?>


<body class="layout layout-vertical two-factor-authentication-code">
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
                             style="font-size: 24px"><?php echo _l('admin_two_factor_auth_heading'); ?>
                            <br /><small><?php echo _l('two_factor_authentication'); ?></small>
                        </div>

                        <?php echo form_open($this->uri->uri_string()); ?>
                        <?php echo validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>
                        <?php $this->load->view('authentication/includes/alerts'); ?>

                        <div class="form-group mb-4">
                            <input type="text" class="form-control" name="code" id="code" placeholder=" "/>
                            <label for="code"><?php echo _l('two_factor_authentication_code'); ?></label>
                        </div>

                        <button type="submit" class="submit-button btn btn-block btn-secondary my-4 mx-auto">
                            <?php echo _l('confirm'); ?>
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

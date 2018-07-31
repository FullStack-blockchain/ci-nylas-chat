<?php $this->load->view('authentication/includes/head.php'); ?>

<body class="layout layout-vertical set-password">
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
                             style="font-size: 24px"><?php echo _l('admin_auth_set_password_heading'); ?></div>

                        <?php echo form_open($this->uri->uri_string()); ?>
                        <?php echo validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>
                        <?php $this->load->view('authentication/includes/alerts'); ?>

                        <div class="form-group mb-4">
                            <input type="password" class="form-control" name="password" id="password" placeholder=" "/>
                            <label for="password"><?php echo _l('admin_auth_set_password'); ?></label>
                        </div>

                        <div class="form-group mb-4">
                            <input type="passwordr" class="form-control" name="passwordr" id="passwordr"
                                   placeholder=" "/>
                            <label for="passwordr"><?php echo _l('admin_auth_set_password_repeat'); ?></label>
                        </div>

                        <button type="submit" class="submit-button btn btn-block btn-secondary my-4 mx-auto">
                            <?php echo _l('admin_auth_set_password_heading'); ?>
                        </button>

                        <?php echo form_close(); ?>

                    </div>
                </div>

            </div>
        </div>
    </div>
</main>
</body>
</html>